<td width="100%">
	<div align="center"><font size="14" color = "green"> Vacances </font></div><br>
<?php $array = $DB->getArray("SELECT idpeoplevac, idpeople, vacin, vacout from peoplevac WHERE idpeople = ".$_REQUEST['idpeople']." AND etat = 'in';"); ?>
	<div align="center"><table class="standard" border="0" cellspacing="1" cellpadding="0" width="50%">
		<tr>
			<td align="center"> Début </td>
			<td align="center"> Fin </td>
		<tr>
		<?php foreach($array as $row) { ?>
		<tr>
		<form action="modifpeople-d.php?idpeople=<?php echo $row['idpeople']; ?>&s=9&act=update" method="post" target="detail">
			<input type="hidden" name ="idpeoplevac" value="<?php echo $row['idpeoplevac']; ?>">
			<td align ="center">
				<input type="text" name = "vacin" value="<?php echo fdate($row['vacin']); ?>">
			</td>
			<td align ="center">
				<input type="text" name = "vacout" value="<?php echo fdate($row['vacout']); ?>">
			</td>
			<td> <input type="submit" name="Modifier" class="btn accept"></td>
		</form>
			<td align="center"> <a href="modifpeople-d.php?idpeople=<?php echo $row['idpeople']; ?>&s=9&act=delete&idpeoplevac=<?php echo $row['idpeoplevac']; ?>" target="detail"> <img src="<?php echo STATIK ?>illus/delete.png" alt="print" width="16" height="16" border="0"></a></td>			</tr>
		<?php } ?>
		<tr>
			<td align="center" colspan="4"> <a style="text-decoration:none;" href="modifpeople-d.php?idpeople=<?php echo $_REQUEST['idpeople']; ?>&s=9&act=add" target="detail"> Ajouter <img align="absmiddle" src="<?php echo STATIK ?>illus/add.png" alt="print" width="16" height="16" border="0"></td>
		</tr>
	</table>
</td>