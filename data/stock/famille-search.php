<div id="centerzonelargewhite">
<?php 
	$_SESSION['stockfamillequid'] = $quid;
	$_SESSION['stockfamillequod'] = $quod;
	$_SESSION['stockfamillesort'] = $sort;

	$classe = "etiq";
?>
<fieldset class="blue">
	<legend class="blue">
		Infos de recherche FAMILLE
	</legend>
	<form action="<?php echo $_SERVER['PHP_SELF'].'?act=famillesearchresult';?>" method="post">
		<table class="<?php echo $classe; ?>" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
			<tr>
				<td class="<?php echo $classe; ?>">Famille</td>
				<td><input type="text" size="6" name="idstockf" id="idstockf"> <input type="text" size="20" name="referencef" id="referencef"><td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>">Mod&egrave;le</td>
				<td><input type="text" size="6" name="idstockm" id="idstockm"> <input type="text" size="20" name="referencem" id="referencem"><td>
			</tr>
			<tr>
				<td align="center" colspan="2"><input type="submit" name="Rechercher" value="Rechercher"></td>
			</tr>
			<tr>
				<td colspan="2"><input type="reset" name="Reset" value="Reset"></td>
			</tr>
		</table>
  	</form>
</fieldset>
</div>