<form action="?act=animshoplist" method="post">
	<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'] ?>">
	<table class="standard" border="0" cellspacing="1" cellpadding="6" align="center" width="98%">
		<tr>
			<td><label for="codeshop">Code</label> <input type="text" name="codeshop" id="codeshop"></td>
			<td><label for="societe">Soci&eacute;t&eacute;</label> <input type="text" name="societe" id="societe"></td>
			<td><label for="cp">CP</label> <input type="text" name="cp" id="cp"></td>
			<td><label for="ville">Ville</label> <input type="text" name="ville" id="ville"></td>
		</tr>
		<tr>
			<td align="center" colspan="4"><input type="submit" name="Rechercher" value="Rechercher"></td>
		</tr>
	</table>	
</form>
