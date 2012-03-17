<div id="centerzonelargewhite">
<?php
################### Code PHP ########################
if (!empty($idmatos)) {
		$recherche1='
			SELECT 
			ma.idmatos, ma.idvip, ma.codematos, ma.mnom, ma.dateout AS madateout, ma.autre, ma.idpeople, 
			ma.situation, ma.complet, ma.idstockm, 
			stm.idstockf, stm.reference,
			stf.reference AS reffamille, stf.stype
			FROM matos ma
			LEFT JOIN stockm stm ON ma.idstockm = stm.idstockm 
			LEFT JOIN stockf stf ON stm.idstockf = stf.idstockf 
		';

		$quid = " WHERE idmatos = ".$_GET['idmatos'];
		
		$recherche='
			'.$recherche1.'
			'.$quid.'
		';
	$detail = new db();
	$detail->inline("$recherche;");
	$infos = mysql_fetch_array($detail->result) ; 
#	echo $recherche;
}
################### Fin Code PHP ########################
?>
<div class="infosection">D&eacute;tail</div>
<fieldset class="blue">
	<legend class="blue">
		ID : <?php echo $infos['idmatos']; ?>
	</legend>
	<?php $classe = "etiq2" ?>
	<?php $classe2 = "standard" ?>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=unitmodif&idmatos=<?php echo $infos['idmatos'];?>" method="post">
		<input type="hidden" name="idmatos" value="<?php echo $infos['idmatos'];?>"> 
		<table class="<?php echo $classe; ?>" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
			<tr>
				<td width="120"></td>
				<td colspan="3"></td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>">Famille</td>
				<td class="<?php echo $classe; ?>" colspan="3"><?php echo $infos['idstockf'].' - '.$infos['reffamille']; ?></td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>">Mod&egrave;le</td>
				<td class="<?php echo $classe; ?>" colspan="3"><?php echo $infos['idstockm'].' - '.$infos['reference']; ?></td>
			</tr>
			<tr>	
				<td class="<?php echo $classe; ?>">
					Code
				</td>
				<td class="<?php echo $classe2; ?>" colspan="3">
					<input type="text" size="12" name="codematos" value="<?php echo $infos['codematos']; ?>">
				</td>
			</tr>
			<tr>	
				<td class="<?php echo $classe; ?>">
					D&eacute;nomination
				</td>
				<td class="<?php echo $classe2; ?>" colspan="3">
					<input type="text" size="40" name="mnom" value="<?php echo $infos['mnom']; ?>">
				</td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>">
					Autre
				</td>
				<td class="<?php echo $classe2; ?>" colspan="3">
					<input type="text" size="40" name="autre" value="<?php echo $infos['autre']; ?>">
				</td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>">Complet</td>
				<td class="<?php echo $classe2; ?>" colspan="3">
					<?php
					echo '<input type="radio" name="complet" value="0" '; if ($infos['complet'] == '0') { echo 'checked';} echo'> Non';
					echo '<input type="radio" name="complet" value="1" '; if ($infos['complet'] == '1') { echo 'checked';} echo'> Oui';
					echo '<input type="radio" name="complet" value="2" '; if ($infos['complet'] == '2') { echo 'checked';} echo'> Partiel';
					?>
				</td>
			</tr>
		</table>
		<br>
		<?php if (isset($idmatos)) { ?>
			<input type="submit" name="action" value="Modifier" accesskey="M">
		<?php } ?>
	</form>
</fieldset>
<br>
<fieldset class="blue">
	<legend class="blue">
		Historique des Tickets
	</legend>
	<?php $classe = "etiq2" ?>
	<?php $classe2 = "standard" ?>
		<?php include 'unite-detail-ticket.php' ;?>
</fieldset>
</div>