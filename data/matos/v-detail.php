<?php
################### Code PHP ########################
if (!empty($_REQUEST['idmatos'])) {
	$infos = $DB->getRow("SELECT 
			ma.idmatos, ma.idvip, ma.codematos, ma.mnom, ma.dateout AS madateout, ma.autre, ma.idpeople, 
			m.idvipjob, a.idanimation, me.idmerch,
			p.pnom, p.pprenom, p.email, p.gsm, p.codepeople,
			j.reference, a.reference AS areference, me.produit  
		FROM matos ma
			LEFT JOIN vipmission m ON ma.idvip = m.idvip 
			LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob 
			LEFT JOIN animation a ON ma.idanimation = a.idanimation 
			LEFT JOIN merch me ON ma.idmerch = me.idmerch 
			LEFT JOIN people p ON ma.idpeople = p.idpeople
		WHERE ma.idmatos = ".$_REQUEST['idmatos']."
	");
}
################### Fin Code PHP ########################
?>
<form action="?act=modif" method="post">
	<input type="hidden" name="idmatos" value="<?php echo $infos['idmatos'];?>"> 

	<div id="leftmenu">
		<div id="idsquare">
			<table border="0" cellspacing="1" cellpadding="2" align="center" width="90%">
				<tr><td align="center">Matos</td></tr>
				<tr><td align="center"><input type="text" size="12" name="codematos" value="<?php echo $infos['codematos']; ?>"></td></tr>
			</table>
		</div>
	</div>

	<div id="infozone">
		<div class="infosection">Infos G&eacute;n&eacute;rales <?php echo $message?></div>
		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
			<tr>	
				<td>D&eacute;nomination</td>
				<td colspan="3"><input type="text" size="20" name="mnom" value="<?php echo $infos['mnom']; ?>"></td>
			</tr>
			<tr>
				<td>Mission</td>
				<td>
					<?php if ($infos['idvip'] > 0) {echo 'V-'.$infos['idvip'];} ?>
					<?php if ($infos['idmerch'] > 0) {echo 'M-'.$infos['idmerch'];} ?>
					<?php if ($infos['idanimation'] > 0) {echo 'A-'.$infos['idanimation'];} ?>
				</td>
				<td>
					<?php if ($infos['idvip'] > 0) {echo 'Job';} ?>
				</td>
				<td>
					<?php if ($infos['idvip'] > 0) {echo $infos['idvipjob'];} ?>
				</td>
			</tr>
			<tr>
				<td>Promoboy</td>
				<td colspan="3">
					<?php echo $infos['idpeople'].' - '.$infos['pprenom'].' '.$infos['pnom']; ?><br>
					TEl : <?php echo $infos['tel'].' Gsm : '.$infos['gsm']; ?>
				</td>
			</tr>
			<tr>
				<td>Date Sortie</td>
				<td colspan="3"><?php echo $infos['dateout']; ?></td>
			</tr>
			<tr>
				<td>Autre</td>
				<td colspan="3"><input type="text" size="20" name="autre" value="<?php echo $infos['autre']; ?>"></td>
			</tr>
		</table>
	</div>

	<div id="infobouton">
	<?php if ($_REQUEST['idmatos'] > 0) { ?>
		<input type="submit" name="Modifier" value="Modifier" accesskey="M">
	<?php } else { ?>
		<input type="submit" name="act" value="Ajouter"> 
	<?php } ?>
	</div>

</form>
