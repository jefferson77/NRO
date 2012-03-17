<?php
	$dts = $DB->getRow("SELECT MIN(vipdate) as datein, MAX(vipdate) as dateout FROM vipmission WHERE idvipjob = ".$_REQUEST['idvipjob']);
?>
<fieldset>
	<legend><font color="black">Recherche pour le print des pr&eacute;sences</font></legend>
	<form action="?print=go&idvipjob=<?php echo $_REQUEST['idvipjob']; ?>" method="post">
		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
			<tr>
				<td>Date de d&eacute;but</td>
				<td><input type="text" size="10" name="vipdate1" value="<?php echo fdate($dts['datein']) ?>"></td>
			</tr>
			<tr>
				<td>Date de fin</td>
				<td><input type="text" size="10" name="vipdate2" value="<?php echo fdate($dts['dateout']) ?>"></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="Modifier" value="Rechercher"></td>
			</tr>
		</table>
	</form>
</fieldset>
