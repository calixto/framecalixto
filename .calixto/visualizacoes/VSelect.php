<?php
/**
* Objeto de apresentação de uma etiqueta HTML
* @package FrameCalixto
* @subpackage visualização
*/
class VSelect extends VComponente{
	public $valor;
	function __construct($nome = 'naoInformado',$valor = null){
		parent::__construct('select',$nome, null);
		$this->valor = $valor;
	}
	function configurar(){
		$this->conteudo = $this->options();
	}
	function options(){
		$conteudo = '';
		if(is_array($this->conteudo)){
			foreach($this->conteudo as $indice => $texto){
				$indice = htmlspecialchars($indice);
				$texto = htmlspecialchars($texto);
				if($indice == $this->valor){
					$conteudo .= "<option value='{$indice}' selected='selected'>{$texto}</option>";
				}else{
					$conteudo .= "<option value='{$indice}'>{$texto}</option>";
				}
			}
		}
		return $conteudo;
	}
	function passarValores($valores){
		$this->conteudo = $valores;
	}
}
?>
