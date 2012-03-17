<div id="centerzonelargewhite">
<?php 
	$_SESSION['stockquid'] = $quid;
	$_SESSION['stockquod'] = $quod;
	$_SESSION['stocksort'] = $sort;
	$_SESSION['stocksearch'] = $recherche1;

	$classe = "etiq";
?>
<fieldset class="blue">
	<legend class="blue">
		Infos de recherche
	</legend>
	<form action="<?php echo $_SERVER['PHP_SELF'].'?act=unitsearchresult';?>" method="post">
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
				<td class="<?php echo $classe; ?>">Unit&eacute;</td>
				<td><input type="text" size="6" name="codematos" id="codematos"> <input type="text" size="20" name="mnom" id="mnom"><td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>">Date out est avant</td>
				<td><input type="text" name="dateout" id="dateout"></td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>">Usager</td>
				<td>
					<input type="radio" name="user" id="user" value="" checked> Tous &nbsp;
					<input type="radio" name="user" id="user" value="people"> Jobiste &nbsp;
					<input type="radio" name="user" id="user" value="supplier"> Supplier &nbsp; 
					<input type="radio" name="user" id="user" value="client"> Client &nbsp;
				</td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>">En Usage ?</td>
				<td>
					<input type="radio" name="inuse" id="inuse" value="" checked> Tous &nbsp;
					<input type="radio" name="inuse" id="inuse" value="0"> Non &nbsp; 
					<input type="radio" name="inuse" id="inuse" value="1"> Oui (Materiel est hors stock)&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>">Complet</td>
				<td>
					<input type="radio" name="complet" id="complet" value="" checked> Tous &nbsp;
					<input type="radio" name="complet" id="complet" value="0"> Non &nbsp;
					<input type="radio" name="complet" id="complet" value="1"> Oui &nbsp;
					<input type="radio" name="complet" id="complet" value="2"> Partiel
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>">Sans famille</td>
				<td>
					<input type="radio" name="sansfamille" id="sansfamille" value="" checked> Tous &nbsp;
					<input type="radio" name="sansfamille" id="sansfamille" value="1"> Sans &nbsp;
					<input type="radio" name="sansfamille" id="sansfamille" value="2"> Avec &nbsp;
				</td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>">Liste modifiable<br>pour attribution famille ?</td>
				<td>
					<input type="radio" name="attribfamille" id="attribfamille" value="" checked> Non &nbsp;
					<input type="radio" name="attribfamille" id="attribfamille" value="1"> Oui &nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
<!--
			<tr>
				<td class="<?php echo $classe; ?>">Situation</td>
				<td>
					<input type="radio" name="situation" id="situation" value="" checked> Tous &nbsp;
					<input type="radio" name="situation" id="situation" value="in"> In &nbsp;
					<input type="radio" name="situation" id="situation" value="out"> Out &nbsp;
					<input type="radio" name="situation" id="situation" value="supplier"> Supplier &nbsp; 
					<input type="radio" name="situation" id="situation" value="going"> En partance &nbsp;
					<input type="radio" name="situation" id="situation" value="coming"> En revenance &nbsp;
					<input type="radio" name="situation" id="situation" value="client"> Retour client
				</td>
			</tr>
-->
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