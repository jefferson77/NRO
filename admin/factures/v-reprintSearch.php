<div id="centerzonelarge">
	<h3 align="center">Entrez les num&eacute;ros des factures &agrave; ressortir,<br> s&eacute;par&eacute;s par des virgules ou par '...' pour définir une plage de numéros</h3>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=print" method="post">
		<input type="hidden" name="ent" value="entete">
		<table border="0" align="center">
			<tr>
				<td>Factures :</td>
				<td><input type="text" name="facs" size="50" value="<?php echo $_GET['idf'];?>"></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" value="Imprimer"></td>
			</tr>
		</table>
	</form>
</div>
