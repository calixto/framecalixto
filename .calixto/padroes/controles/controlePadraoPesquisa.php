<?php

/**
 * Classe de definição da camada de controle
 * @package FrameCalixto
 * @subpackage Controle
 */
class controlePadraoPesquisa extends controlePadrao {

	/**
	 * @var pagina pagina a ser listada
	 */
	public $pagina;
	/**
	 * @var negocioPadrao objeto de negócio que será utilizado para gerar a pesquisa
	 */
	public $filtro;

	/**
	 * Método inicial do controle
	 */
	public function inicial() {
		$this->definirPagina();
		$this->definirFiltro();
		if (controle::tipoResposta() == controle::xml)
			controle::responderXml($this->definirColecao()->xml());
		if (controle::tipoResposta() == controle::json)
			controle::responderJson($this->definirColecao()->json());
		$this->registrarInternacionalizacao($this, $this->visualizacao);
		$this->gerarMenus();
		$this->montarListagem($this->visualizacao, $this->definirColecao(), $this->pegarPagina());
		$this->montarApresentacao($this->filtro);
		parent::inicial();
		$this->finalizar();
	}

	/**
	 * Método executado para realizar finalização da pesquisa
	 */
	public function finalizar() {
		if ($this->sessao->tem('negocio'))
			$this->sessao->retirar('negocio');
	}

	/**
	 * Preenche os itens da propriedade menuPrograma
	 * @return colecaoPadraoMenu do menu do programa
	 */
	function montarMenuPrograma() {
		$menu = parent::montarMenuPrograma();
		$this->montarBotaoNovo($menu);
		$this->montarBotaoPesquisar($menu);
		$this->montarBotaoImpressao($menu);
		return $menu;
	}
	public function montarBotaoNovo($menu){
		$novo = $this->inter->pegarTexto('botaoNovo');
		$menu->$novo		= new VMenu($novo, sprintf("?c=%s", definicaoEntidade::controle($this, 'verEdicao')), 'icon-plus-sign icon-white');
        $menu->$novo->passar_classeLink('btn btn-success');
	}
	public function montarBotaoPesquisar($menu){
		$pesquisar = $this->inter->pegarTexto('botaoPesquisar');
		$menu->$pesquisar	= new VMenu($pesquisar, 'javascript:document.formulario.submit();', 'icon-search');
	}
	public function montarBotaoImpressao($menu){
		try{
			$impressao = $this->inter->pegarTexto('botaoImpressao');
			arquivoClasse(definicaoEntidade::controle($this, 'verListagemPdf'));
			$menu->$impressao	= new VMenu($impressao, sprintf('?c=%s', definicaoEntidade::controle($this, 'verListagemPdf')), 'icon-print');
		}  catch (erroInclusao $e){}
	}
	

	/**
	 * Retorna o nome da variável que irá segurar a página na sessão
	 * @return string
	 */
	protected function nomeDaPagina() {
		return 'pagina';
	}

	/**
	 * Retorna o nome da variável que irá segurar o filtro na sessão
	 * @return string
	 */
	protected function nomeDoFiltro() {
		return 'filtro';
	}

	/**
	 * Método que define como se comporta um filtro novo ou limpo
	 * @return negocioPadrao
	 */
	protected function filtroNovo() {
		$negocio = definicaoEntidade::negocio($this);
		return new $negocio();
	}

	/**
	 * Método que define a página que será exibida na pesquisa
	 */
	protected function definirPagina() {
		$mapeador = controlePadrao::pegarEstrutura($this);
		$this->pagina = ($this->sessao->tem($this->nomeDaPagina())) ? $this->sessao->pegar($this->nomeDaPagina()) : new pagina($mapeador['tamanhoPaginaListagem']);
		if (isset($_GET['pagina']))
			$this->pagina->passarPagina($_GET['pagina']);
		if (isset($_GET['tamanhoPagina']))
			$this->pagina->passarTamanhoPagina($_GET['tamanhoPagina']);
		$this->sessao->registrar($this->nomeDaPagina(), $this->pagina);
	}

	/**
	 * Método que define o objeto de negócio que executará a pesquisa
	 */
	protected function definirFiltro() {
		if ($_POST) {
			$this->filtro = $this->filtroNovo();
			$this->montarNegocio($this->filtro);
			$this->sessao->registrar($this->nomeDoFiltro(), $this->filtro);
		} else {
			$this->filtro = ($this->sessao->tem($this->nomeDoFiltro())) ? $this->sessao->pegar($this->nomeDoFiltro()) : $this->filtroNovo();
		}
	}

	/**
	 * Método de criação da coleção a ser listada
	 * @return colecaoPadraoNegocio coleção a ser listada
	 */
	protected function definirColecao() {
		$metodo = ($this->sessao->tem($this->nomeDoFiltro())) ? 'pesquisar' : 'lerTodos';
		return $this->filtro->$metodo($this->pegarPagina());
	}

	/**
	 * metodo de apresentação do negocio
	 * @param negocio objeto para a apresentação
	 */
	public function montarApresentacao(negocio $negocio, $tipo = 'edicao') {
		parent::montarApresentacao($negocio);
		$this->visualizacao->descricaoDeAjuda = $this->inter->pegarTexto('ajudaPesquisa');
		$this->visualizacao->tituloListagem = $this->inter->pegarTexto('tituloListagem');
	}

	/**
	 * Método de apresentação da listagem
	 * @param visualizacao $visualizacao
	 * @param colecao $colecao
	 * @param pagina $pagina
	 */
	public static function montarListagem(visualizacao $visualizacao, colecao $colecao, pagina $pagina, $entidade = null) {
		$visualizacao->listagem = new VListaPaginada($colecao, $pagina, $entidade);
	}

}

?>