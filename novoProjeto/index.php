<?php
/**
* Arquivo de indice para o funcionamento do sistema
* @package Sistema
* @subpackage Index
*/
$versao = '5.1.2';
if(phpversion() < $versao) throw new Exception(sprintf('O Calixto Framework não funciona com versão inferior a %s.',$versao));
header("Content-type:text/html; charset=utf-8");
date_default_timezone_set('America/Sao_Paulo');
set_time_limit(0);
//Lendo o arquivo XML de definições de diretórios e arquivos
$definicoes = simplexml_load_file('.sistema/xml/definicoes.xml');
define('DIRAPP',dirname(__FILE__));
$dirCalixto = strval($definicoes->classes->classe[0]['dir']);
//Carrregando as classes de definições e erros
include_once($dirCalixto.'definicoes/include.php');
$definicoes = definicao::pegarDefinicao('.sistema/xml/definicoes.xml');
switch (definicaoSistema::pegarAmbiente()) {
	case definicaoSistema::homologacao  :
		ini_set('display_errors','Off');
	break;
	case definicaoSistema::producao :
		ini_set('display_errors','Off');
	break;
	case definicaoSistema::desenvolvimento :
	default:
		ini_set('display_errors','On');
	break;
}
error_reporting(E_ALL | E_STRICT);
set_error_handler('reportarErro');
/**
 * Apresenta os erros do PHP ao desenvolvedor
 * @param integer $codigo
 * @param string $mensagem
 * @param string $arquivo
 * @param integer $linha
 * @param string $tipoErro
 * @return void
 */
function reportarErro($codigo,$mensagem,$arquivo,$linha,$tipoErro){
	if(strpos($arquivo,'conexaoPadrao')) return;
	$clsErro = 'icon-warning-sign icon-danger';
	switch($codigo){
		case E_NOTICE:
			$tipoErro = 'Notice';
			$clsErro = 'icon-warning-sign icon-warning';
		break;
		case E_WARNING:
			$tipoErro = 'Warning';
		break;
		case E_PARSE:
			$tipoErro = 'Parser';
		break;
		case E_COMPILE_ERROR:
			$tipoErro = 'Fatal';
		break;
	}
	if(preg_match('/(.*)\.html\.php(.*)/', $arquivo, $resultado)){
		$mensagem = str_replace('Undefined index:','Variável não registrada no controle para apresentação no template: ',$mensagem);
		$back = null;
	}else{
		ob_start();
		debug_print_backtrace();
		$back = ob_get_clean();
	}
	$tipoErro = is_object($tipoErro) ? get_class($tipoErro) : $tipoErro;
	$tipoErro = is_array($tipoErro) ? 'array' : $tipoErro; 
	echo "
		<link type='text/css' rel='stylesheet' href='.sistema/debug.css' />
		<fieldset class='erroNegro'>
			<legend><i class='{$clsErro}'></i> {$tipoErro}</legend>
			<table summary='text' class='erroNegro'>
				<tr>
					<td>Mensagem:</td>
					<td><b>{$mensagem}</b></td>
				</tr>
				<tr>
					<td>Arquivo:</td>
					<td>## {$arquivo}({$linha})</td>
				</tr>
			</table>
		<pre>{$back}
		</pre>
		</fieldset>";
}
include_once('.sistema/debug.php');
include_once('.sistema/definicoes.php');
$dir = definirDiretorio('Sistema');
define('diretorioPrioritario',$dir['stDiretorio']);
new gerenteControles(isset($_REQUEST['c'])? $_REQUEST['c']:definicaoSistema::pegarControleInicial());
?>
