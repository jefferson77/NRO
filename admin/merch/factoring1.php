<?php
$oldjumper = 0;
$chclient = array('1038', '1726', '1498', '633', '2928', '2929', '1713');

setlocale(LC_TIME, 'fr_FR'); 
if (mysql_num_rows($DB->result) > 0) {
?>
<table class="sortable-onload-2 no-arrow rowstyle-alt fac" border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
<?php
while ($row = mysql_fetch_array($DB->result)) { 
## Détermine le Jumper selon les modes defacturation
	switch ($jump) {
		case "SE-PO": # Par semaine / Bon de commande
			$newjumper = $row['weekm'].'-'.$row['idclient'].'-'.$row['boncommande'];
			$period = 'Sem : '.$row['weekm'].'-'.$row['yearm'];
			$formdir = 'facture';
		break;
		case "SE-OF": # Par semaine / Bon de commande
			$newjumper = $row['weekm'].'-'.$row['idclient'].'-'.$row['idcofficer'];	
			$period = 'Sem : '.$row['weekm'].'-'.$row['yearm'];
			$formdir = 'facture';
		break;
		case "MO-PO": # Par mois
			$newjumper = date("m", strtotime($row['datem'])).'-'.$row['idclient'].'-'.$row['boncommande'];
			$period = 'Mois : '.date("m-Y", strtotime($row['datem']));
			$formdir = 'facture';
		break;
		case "MO-OF": # Par mois
			$newjumper = date("m", strtotime($row['datem'])).'-'.$row['idclient'].'-'.$row['idcofficer'];			
			$period = 'Mois : '.date("m-Y", strtotime($row['datem']));
			$formdir = 'facture';
		break;
		case "EAS": # EAS
			$newjumper = date("m-Y", strtotime($row['datem'])).'-'.$row['idclient'].'-'.$row['idshop'];			
			$period = $row['ssociete'].' : '.date("m-Y", strtotime($row['datem']));
			$formdir = 'facture';
		break;
		case "GB": # GB
			$newjumper = date("m-Y", strtotime($row['datem']));			
			$period = $row['clsociete'].' : '.date("m-Y", strtotime($row['datem']));
			$formdir = 'facture';
		break;
		default: # autres
			$newjumper = $row['weekm'].'-'.$row['idclient'].'-'.$row['idcofficer'];			
			$period = '';
			$formdir = 'facture';
	}
	
#############################################################################################
### Affichage des totaux de jobs #########
####################################
if (($oldjumper != $newjumper) and ($oldjumper > 0)) { ?>
</tbody>
<tfoot>
	<tr>
		<td class="jobtot"><input type="submit" name="modstate" value="Valider"></td>
		<td class="jobtot" colspan="3">
<?php 
		$sep = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

		if ($mode == 'USER') {	
			echo '<input type="hidden" name="state" value="3">';
		} else {
			if ($fachtot > 0) echo '<input type="radio" name="state" value="5" '.((!in_array($lastclient, $chclient))?' checked':'').'> OK '.$sep.'<input type="radio" name="state" value="1"> NO '.$sep;
			echo '<input type="radio" name="state" '.((in_array($lastclient, $chclient) or ($lastclient == ''))?'checked':'').' value="25"> OUT';
		 } 
?>
		</td>
		<td class="jobtot"></td>
		<td class="jobtotF"><?php echo fnbr0($fachtot); ?></td>
		<td class="jobtotP"><?php echo fnbr0($payhtot); ?></td>
		<td class="jobtotD"><?php echo fnega($fachtot - $payhtot); ?></td>

		<td class="jobtotF"><?php echo fnbr0($totkmfac); ?></td>
		<td class="jobtotP"><?php echo fnbr0($totkmpay); ?></td>
		<td class="jobtotD"><?php echo fnega($totkmfac - $totkmpay); ?></td>

		<td class="jobtotF"><?php echo fnbr0($totfraisfac); ?></td>
		<td class="jobtotP"><?php echo fnbr0($totfraispay); ?></td>
		<td class="jobtotD"><?php echo fnega($totfraisfac - $totfraispay); ?></td>

		<td class="jobtotF"><?php echo fnbr0($totlivfac); ?></td>
		<td class="jobtotP"><?php echo fnbr0($totlivpay); ?></td>
		<td class="jobtotD"><?php echo fnega($totlivfac - $totlivpay); ?></td>
		<td class="jobtotF"><?php echo fnbr0($totDimona); ?></td>
		<td class="jobtot">&nbsp;</td>
	</tr>
</tfoot>
<?php
### Clear les totaux par jobs
	unset ($fachtot);
	unset ($payhtot);

	unset ($totkmfac);
	unset ($totkmpay);

	unset ($totfraisfac);
	unset ($totfraispay);

	unset ($totlivfac);
	unset ($totlivpay);
}

#############################################################################################
### Entete de nouveau job #############
####################################
if ($oldjumper != $newjumper) {
	$i = 0;
?>
</table>
</form>
<br>
<form action="?act=<?php echo $formdir;?>" method="post">
<table class="sortable-onload-2 no-arrow rowstyle-alt fac" border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
	<thead>
		<tr>
			<td><?php echo $row['prenom'] ?></td>
			<td colspan="2"><?php echo $period; ?></td>
			<td><?php echo $row['clsociete'].' ('.$row['idclient'].')' ; ?></td>
		<?php if (substr($jump, -2) == 'PO') { ?>
			<td><?php echo $row['boncommande']; ?></td>
		<?php } else { ?>
			<td><?php echo $row['oprenom'].' '.$row['onom'].' ('.$row['idcofficer'].')'; ?></td>
		<?php } ?>	
			<td colspan="3">Heures</td>
			<td colspan="3">D&eacute;plac.</td>
			<td colspan="3">Frais</td>
			<td colspan="3">Livraison</td>
			<td></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th class="sortable-numeric">N&deg;</th>
			<th class="sortable-date-dmy" colspan="2">Date</th>
			<th class="sortable-text">People</th>
			<th class="sortable-text">Lieu</th>

			<th class="sortable-numeric">Fac</th>
			<th class="sortable-numeric">Pay</th>
			<th  class="sortable-numeric D">&#8710;</th>

			<th class="sortable-numeric">Fac</th>
			<th class="sortable-numeric">Pay</th>
			<th  class="sortable-numeric D">&#8710;</th>

			<th class="sortable-numeric">Fac</th>
			<th class="sortable-numeric">Pay</th>
			<th  class="sortable-numeric D">&#8710;</th>

			<th class="sortable-numeric">Fac</th>
			<th class="sortable-numeric">Pay</th>
			<th class="sortable-numeric D">&#8710;</th>
			<th class="sortable-numeric">Dimona</th>
			<th title="Contrat encodé">C</th>
		</tr>
	</thead>
	<tbody>
<?php
}

#> Changement de couleur des lignes #####>>####
$i++;
if ($row['ferie'] == '200') {
		echo '<tr class="ddim">';
} else {
	if (fmod($i, 2) == 1) {
		echo '<tr class="even">';
	} else {
		echo '<tr class="odd">';
	}
}
#< Changement de couleur des lignes #####<<####



$merch = new coremerch($row['idmerch']);
?>
	<td class="data"><input type="hidden" name="ids[]" value="<?php echo $row['idmerch']; ?>"><a href="<?php echo NIVO ?>merch/adminmerch.php?act=show&idmerch=<?php echo $row['idmerch']; ?>"><?php echo $row['idmerch']; ?></a></td>
	<td class="data"><?php echo  strftime("%d/%m/%y", strtotime ($row['datem'])); ?></td>
	<td class="data"><?php echo  strftime("%a", strtotime ($row['datem'])); ?></td>
	<td class="data"><?php echo $row['codepeople'].' '.$row['pnom']; ?></td>
	<td class="data"><?php echo $row['codeshop'].' '.$row['ssociete'].' '.$row['sville']; ?></td>
	
	<td class="dataF"><?php echo $merch->hprest; $fachtot += $merch->hprest;?></td>
	<td class="dataP"><?php echo $merch->hprest; $payhtot += $merch->hprest;?></td>
	<td class="dataD"><?php echo fnega($merch->hprest - $merch->hprest);?></td>
	
	<td class="dataF"><?php echo fnbr0($merch->kifac); $totkmfac += $merch->kifac;?></td> 
	<td class="dataP"><?php echo fnbr0($merch->kipay); $totkmpay += $merch->kipay;?></td>
	<td class="dataD"><?php echo fnega($merch->kifac - $merch->kipay);?></td>
	
	<td class="dataF"><?php echo fnbr0($row['montantfacture']); $totfraisfac += $row['montantfacture'];?></td>
	<td class="dataP"><?php echo fnbr0($row['montantpaye']); $totfraispay += $row['montantpaye'];?></td>
	<td class="dataD"><?php echo fnega($row['montantfacture'] - $row['montantpaye']);?></td>
	
	<td class="dataF"><?php echo fnbr0($row['livraisonfacture']); $totlivfac += $row['livraisonfacture'];?></td>
	<td class="dataP"><?php echo fnbr0($row['livraisonpaye']); $totlivpay += $row['livraisonpaye'];?></td>
	<td class="dataD"><?php echo fnega($row['livraisonfacture'] - $row['livraisonpaye']);?></td>
	<td class="dataF"><?php echo fnbr0($merch->FraisDimona); $totDimona += $merch->FraisDimona;?></td>
	<td class="data" style="width: 10px;"><img src="<?php echo STATIK.'/nro/illus/'.(($row['contratencode'] == 1)?'tick_small.png':'exclamation_small.png'); ?>" width="10" height="10" alt="Contrat"></td>
</tr>
<?php 
$oldjumper = $newjumper;
$lastclient = $row['idclient'];

}
 ?>
</tbody>
<tfoot>
<tr>
	<td class="jobtot"><?php if ($fachtot > 0) echo '<input type="submit" name="modstate" value="Valider">'; ?></td>
	<td class="jobtot" colspan="3">
<?php 
		$sep = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

		if ($mode == 'USER') {	
			echo '<input type="hidden" name="state" value="3">';
		} else {
			if ($fachtot > 0) echo '<input type="radio" name="state" value="5" '.((!in_array($lastclient, $chclient))?' checked':'').'> OK '.$sep.'<input type="radio" name="state" value="1"> NO '.$sep;
			echo '<input type="radio" name="state" '.((in_array($lastclient, $chclient) or ($lastclient == ''))?'checked':'').' value="25"> OUT';
		 } 
?>
 	</td>
	<td class="jobtot"></td>
	<td class="jobtotF"><?php echo fnbr0($fachtot); ?></td>
	<td class="jobtotP"><?php echo fnbr0($payhtot); ?></td>
	<td class="jobtotD"><?php echo fnega($fachtot - $payhtot); ?></td>

	<td class="jobtotF"><?php echo fnbr0($totkmfac); ?></td>
	<td class="jobtotP"><?php echo fnbr0($totkmpay); ?></td>
	<td class="jobtotD"><?php echo fnega($totkmfac - $totkmpay); ?></td>

	<td class="jobtotF"><?php echo fnbr0($totfraisfac); ?></td>
	<td class="jobtotP"><?php echo fnbr0($totfraispay); ?></td>
	<td class="jobtotD"><?php echo fnega($totfraisfac - $totfraispay); ?></td>

	<td class="jobtotF"><?php echo fnbr0($totlivfac); ?></td>
	<td class="jobtotP"><?php echo fnbr0($totlivpay); ?></td>
	<td class="jobtotD"><?php echo fnega($totlivfac - $totlivpay); ?></td>
	<td class="jobtotF"><?php echo fnbr0($totDimona); ?></td>
	<td class="jobtot"></td>
</tr>
</form>
<?php
### Clear les totaux par jobs
	unset ($fachtot);
	unset ($payhtot);

	unset ($totkmfac);
	unset ($totkmpay);

	unset ($totfraisfac);
	unset ($totfraispay);

	unset ($totlivfac);
	unset ($totlivpay);
?></tfoot>
</table>
<?php } ?>