<?php
/**
* Classe de representação de uma camada de negócio da entidade Controle Menu
* A camada de negócio é a parte que engloba as regras e efetua os comandos de execução de um sistema
* @package Sistema
* @subpackage Controle Menu
*/
class NControleMenu extends negocio{
	/**
	* Menu principal do sistema
	* @var array
	*/
	protected $menuPrincipal = array();
	/**
	* Menu do sistema
	* @var array
	*/
	protected $menuSistema = array();
	/**
	* Array de acessos liberados para o usuário
	* @var array
	*/
	protected $acessosLiberados = array();
	/**
	* Flag de liberação dos menus
	* @var boolean
	*/
	protected $menuLiberado = false;
	/**
	* Método construtor que lê os acessos liberados para o usuário logado
	*/
	public function __construct(){
		try{
			$definicoes = definicao::pegarDefinicao();
			$controleDeAcesso = $definicoes->xpath('//controleDeAcesso');
			$this->menuLiberado = (isset($controleDeAcesso[0]) && strval($controleDeAcesso[0]['liberado']) == 'sim') ? true : false;
			if(!$this->menuLiberado){
				$nUsuario = sessaoSistema::pegar('usuario');
				$nAcesso = new NAcesso();
				$this->acessosLiberados = array_flip($nAcesso->lerAcessosPorUsuario($nUsuario)->gerarVetorDeAtributo('nmAcesso'));
			}
			return true;
		}catch (erro $e){
			return false;
		}
	}
	/**
	* Método criado para fazer a verificação do menuPrincipal do sistema quanto ao controle de acesso
	* @param string $propriedadeMenu propriedade que ficara adicionado o item
	* @param string $caminhoItem caminho do item separado por / (barra)
	* @param string $valorItem item do menu que será acessado
	* @param string $destravar destrava a validação do controle de acesso
	*/
	protected function adicionarItem($propriedadeMenu,$caminhoItem,$valorItem, $imagem = null ,$destravar = false, $prefixo = '?c='){
		if($destravar || $this->menuLiberado || isset($this->acessosLiberados[$valorItem])){
			$arCaminho = explode('/',$caminhoItem);
			$item = $arCaminho[count($arCaminho)-1];
			$imagem = $imagem ? ",'{$imagem}'":null;
			eval("\$this->{$propriedadeMenu}->{'".str_replace('/',"'}->{'",$caminhoItem)."'} = new VMenu('{$item}','{$prefixo}{$valorItem}'{$imagem});");
		}
	}
	/**
	* Método criado para efetuar a montagem do menu do site
	*/
	public function menuPrincipal(){
		try{
			$this->menuPrincipal = new colecaoPadraoMenu();
			$this->menuPrincipal->passar_id('menuPrincipal');
			
			$nmLoginLabel = sessaoSistema::tem('usuario') ? 'Sair' : 'Entrar';
			$nmLoginImagem = sessaoSistema::tem('usuario') ? 'door_out.png' : 'key.png';
			
			$this->menuPrincipal->Sistema->passar_imagem('.sistema/temas/frameCalixto/icones/computer.png');
			
			$this->adicionarItem('menuPrincipal','Sistema/Página Principal','CControleAcesso_verPrincipal','.sistema/temas/frameCalixto/icones/monitor.png',true);
			$this->adicionarItem('menuPrincipal',"Sistema/{$nmLoginLabel}",'CControleAcesso_verLogin',".sistema/temas/frameCalixto/icones/{$nmLoginImagem}",true);
			
			$this->menuPrincipal->{'Administração'}->passar_imagem('.sistema/temas/frameCalixto/icones/server.png');
			$this->menuPrincipal->{'Administração'}->Estados->passar_imagem('.sistema/temas/frameCalixto/icones/group.png');
			$this->menuPrincipal->{'Administração'}->Pessoas->passar_imagem('.sistema/temas/frameCalixto/icones/vcard.png');
			$this->menuPrincipal->{'Administração'}->Perfis->passar_imagem('.sistema/temas/frameCalixto/icones/medal_gold_1.png');
			$this->menuPrincipal->{'Administração'}->{'Usuários'}->passar_imagem('.sistema/temas/frameCalixto/icones/user.png');
			
			$this->adicionarItem('menuPrincipal','Administração/Estados/Pesquisar','CEstado_verPesquisa','.sistema/temas/frameCalixto/icones/group.png');
			$this->adicionarItem('menuPrincipal','Administração/Estados/Novo Registro','CEstado_verEdicao','.sistema/temas/frameCalixto/icones/group_add.png');
			
			$this->adicionarItem('menuPrincipal','Administração/Pessoas/Pesquisar','CPessoa_verPesquisa','.sistema/temas/frameCalixto/icones/vcard.png');
			$this->adicionarItem('menuPrincipal','Administração/Pessoas/Novo Registro','CPessoa_verEdicao','.sistema/temas/frameCalixto/icones/vcard_add.png');
			
			$this->adicionarItem('menuPrincipal','Administração/Perfis/Pesquisar','CPerfil_verPesquisa','.sistema/temas/frameCalixto/icones/medal_gold_1.png');
			$this->adicionarItem('menuPrincipal','Administração/Perfis/Novo Registro','CPerfil_verEdicao','.sistema/temas/frameCalixto/icones/medal_gold_add.png');
			
			$this->adicionarItem('menuPrincipal','Administração/Usuários/Pesquisar','CUsuario_verPesquisa','.sistema/temas/frameCalixto/icones/user.png');
			$this->adicionarItem('menuPrincipal','Administração/Usuários/Novo Registro','CUsuario_verEdicao','.sistema/temas/frameCalixto/icones/user_add.png');
			
			$this->menuPrincipal->Apoio->passar_imagem('.sistema/temas/frameCalixto/icones/help.png');
			
			$this->adicionarItem('menuPrincipal','Apoio/Pesquisar','CUtilitario_pesquisaGeral','.sistema/temas/frameCalixto/icones/find.png');
			$this->adicionarItem('menuPrincipal','Apoio/Gerador','CUtilitario_listarEntidade','.sistema/temas/frameCalixto/icones/cog.png');
			$this->adicionarItem('menuPrincipal','Apoio/Tabelas','CUtilitario_listarTabelas','.sistema/temas/frameCalixto/icones/application_tile_horizontal.png');
			$this->adicionarItem('menuPrincipal','Apoio/Recriador de Base','CUtilitario_atualizadorBase','.sistema/temas/frameCalixto/icones/application_side_contract.png');
			$this->adicionarItem('menuPrincipal','Apoio/Importador','CUtilitario_verImportador','.sistema/temas/frameCalixto/icones/arrow_in.png');
			$this->adicionarItem('menuPrincipal','Apoio/Definições do Sistema','CUtilitario_geradorDefinirSistema','.sistema/temas/frameCalixto/icones/wrench.png');
			
			return $this->menuPrincipal;
		}
		catch(erro $e){
			return array();
		}
	}
	/**
	* Método criado para efetuar a montagem do menu do sistema
	*/
	public function menuMenuSistema(){
		try {
			return $this->menuSistema;
		}catch (erro $e){
			return array();
		}
	}
}
?>