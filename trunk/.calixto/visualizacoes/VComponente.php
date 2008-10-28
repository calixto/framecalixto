<?php
/**
* Objeto de apresentação de uma etiqueta HTML
* @package FrameCalixto
* @subpackage visualização
*/
class VComponente extends VEtiquetaHtml{
	/**
	* Flag de apresentação do campo obrigatório
	* @var boolean
	*/
	public $obrigatorio = false;
	
	function __construct($etiqueta = 'naoInformada',$nome = 'naoInformado',$valor = null){
		parent::__construct($etiqueta);
		$this->passarTabindex(1);
		$this->passarName($nome);
		$this->passarId($nome);
		if($valor) $this->passarValue($valor);
	}
	/**
	 * Factory de componentes padronizados
	 *
	 * @param [string] Tipo do componente
	 * @param [string] Nome do componente (name)
	 * @param [string] Valor do componente (value)
	 * @param [array] Opções de modificação do componente
	 * @param [array] Valores para componentes multiplos
	 * @return unknown
	 */
	final static function montar($componente,$nome,$valor,$opcoes = null,$valores = null){
		try{
			$objeto = null;
			switch(strtolower($componente)){
				case 'envio':
				case 'enviar':
				case 'confirmar':
					if(definicaoNavegador::pegarNome() == 'mozilla'){
						$objeto = new VButtonSubmit($nome,$valor);
						$objeto->passarConteudo($valor);
					}else{
						$objeto = new VInputButton($nome,$valor);
						$objeto->passarType('submit');
					}
				break;
				case 'botão':
				case 'botao':
					$objeto = new VButton($nome,$valor);
					$objeto->passarConteudo($valor);
				break;
				case 'palavra chave':
				case 'senha':
					$objeto = new VPassword($nome,$valor);
				break;
				case 'menu de sistema':
					$objeto = new VMenu($valores,'menu1','9999');
				break;
				case 'menu de modulo':
					$objeto = new VMenu($valores,'menu2','9998');
				break;
				case 'menu de programa':
					$objeto = new VMenu($valores,'menu3','9997');
				break;
				case 'seletor':
				case 'radio':
					$objeto = new VRadio($nome,$valor);
				break;
				case 'radios':
				case 'listagem de radios':
				case 'lista de radios':
				case 'radiolist':
				case 'radiolista':
				case 'listaradio':
				case 'listaradios':
					if(is_array($valores)) {
						unset($valores['']);
						$objeto = new VRadioLista($nome,$valor);
						$objeto->passarColunas(1);
						$objeto->passarValores($valores);
					}
				break;
				case 'marcador':
				case 'check':
				case 'checkbox':
					$objeto = new VCheck($nome,$valor);
				break;
				case 'input':
				case 'entrada':
				case 'caixa de entrada':
					$objeto = new VInput($nome,$valor);
				break;
				case 'nr documento':
				case 'documento pessoal':
					$tTipoDado = ($valor instanceof TNumerico) ? $valor : new TDocumentoPessoal(null) ;
					$objeto = new VInputDocumentoPessoal($nome,$tTipoDado);
				break;
				case 'cep':
					$tTipoDado = ($valor instanceof TCep) ? $valor : new TCep(null) ;
					$objeto = new VInputCep($nome,$tTipoDado);
				break;
				case 'telefone':
					$tTipoDado = ($valor instanceof TTelefone) ? $valor : new TTelefone(null) ;
					$objeto = new VInputTelefone($nome,$tTipoDado);
				break;
				case 'data':
					$tTipoDado =  ($valor instanceof TData) ? $valor : new TData(null)  ;
					$objeto = new VInputData($nome,$tTipoDado);
				break;
				case 'hora':
					$tTipoDado =  ($valor instanceof TData) ? $valor : new TData(null)  ;
					$objeto = new VInputHora($nome,$tTipoDado);
				break;
				case 'data e hora':
					$tTipoDado =  ($valor instanceof TData) ? $valor : new TData(null)  ;
					$objeto = new VInputDataHora($nome,$tTipoDado);
				break;
				case 'numero':
				case 'numerico':
					$tTipoDado = ($valor instanceof TNumerico) ? $valor : new TNumerico(null) ;
					$objeto = new VInputNumerico($nome,$tTipoDado);
				break;
				case 'moeda':
					$tTipoDado = ($valor instanceof TMoeda) ? $valor : new TMoeda(null) ;
					$objeto = new VInputMoeda($nome,$tTipoDado);
				break;
				case 'oculto':
					$objeto = new VHidden($nome,$valor);
				break;
				case 'caixa de combinacao':
				case 'caixa de combinação':
				case 'combobox':
				case 'combo':
				case 'select':
					if(is_array($valores)) {
						$objeto = new VSelect($nome,$valor);
						$objeto->passarValores($valores);
					}
				break;
				case 'caixa de selecao':
				case 'caixa de seleção':
					if(is_array($valores)) {
						$objeto = new VSelect($nome,$valor);
						$objeto->passarMultiple(true);
						$objeto->passarValores($valores);
					}
				break;
				case 'checks':
				case 'listagem de checks':
				case 'checklist':
				case 'checklista':
				case 'listacheck':
				case 'listachecks':
					if(is_array($valores)) {
						$objeto = new VCheckLista($nome,$valor);
						if(isset($opcoes['legend'])){
							$objeto->passarTitulo($opcoes['legend']);
							unset($opcoes['legend']);
						}
						$objeto->tipoListagem();
						$objeto->passarValores($valores);
					}
				break;
				case 'texto':
				case 'textarea':
				case 'caixa de texto':
					$objeto = new VTextArea($nome,$valor);
					$objeto->passarCols(50);
					$objeto->passarRows(2);
				break;
				default:
					$objeto = new $componente($nome,$valor);
				break;
			}
			if(is_array($opcoes)){
				foreach($opcoes as $propriedade => $valor){
					$propriedade = 'passar'.$propriedade;
					$objeto->$propriedade($valor);
				}
			}
			return $objeto;
		}catch(erro $e){
			x(func_get_args());
			throw $e;
		}
	}
	/**
	* Método de sobrecarga para printar a classe
	* @return [string] texto de saída da classe
	*/
	public function __toString(){
		if($this->obrigatorio){
			return parent::__toString().$this->campoObrigatorio();
		}else{
			return parent::__toString();
		}
	}
	/**
	* Método de complemento de campo obrigatório
	* @return string
	*/
	protected function campoObrigatorio(){
		return '<span id="campoObrigatorio">*</span>';
	}
}
?>