<?php
/**
* Classe de controle
* Apresenta a tela de login do sistema
* @package Sistema
* @subpackage ControleAcesso
*/
class CControleAcesso_verLogin extends controlePadrao{
	/**
	* Método inicial do controle
	*/
	public function inicial(){
		sessaoSistema::encerrar();
		$this->gerarMenuPrincipal();
		$this->registrarInternacionalizacao($this,$this->visualizacao);
		$this->visualizacao->action = sprintf('?c=%s',definicaoEntidade::controle($this,'validar'));
		
		$this->visualizacao->nmLogin = VComponente::montar('caixa de entrada','nmLogin', null);
		$this->visualizacao->nmLogin->obrigatorio(true);
		$this->visualizacao->nmLogin->passarTitle('Digite o login do usuário');
		
		$this->visualizacao->nmSenha = VComponente::montar('senha','nmSenha', null);
		$this->visualizacao->nmSenha->obrigatorio(true);
		$this->visualizacao->nmSenha->passarTitle('Digite a senha de acesso');
		
		$this->visualizacao->btEnviar = VComponente::montar('confirmar','btEnviar', $this->inter->pegarTexto('enviar'));
		$this->visualizacao->btEnviar->adicionarClass('btn btn-primary');
		$this->visualizacao->mostrar();
	}
	/**
	* Método de validação do controle de acesso
	* @return boolean resultado da validação
	*/
	public function validarAcessoAoControle(){
		return true;
	}
}
?>