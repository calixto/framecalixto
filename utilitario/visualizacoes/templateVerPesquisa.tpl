<div class="container0">
	<div class="a"></div>
	<div class="b"></div>
	<div class="c"></div>
	<div class="d"></div>
	<div class="e"></div>
	<div class="f"></div>
	<div class="g"></div>
	<div class="h"></div>
	<h1>&nbsp;</h1>
	<div class="texto">
		<table class="tabela2">
			�foreach from=$nomes key=indice item=propriedade �<tr>
				<td class="campo1">�ldelim�$�$indice��rdelim�:</td>
				<td class="campo2">�ldelim�$�$propriedade��rdelim�</td>
			</tr>
		�/foreach�</table>
		<div class="container2">
			<div class="a"></div>
			<div class="b"></div>
			<div class="c"></div>
			<div class="d"></div>
			<div class="e"></div>
			<div class="f"></div>
			<div class="g"></div>
			<div class="h"></div>
			<h1>Listagem</h1>
			<div class="texto">
				�ldelim�$listagem�rdelim�
			</div>
		</div>
	</div>
</div>
