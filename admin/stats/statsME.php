<?php
$oldjumper = 'START';
$outfac = array('97', '98');
if (mysql_num_rows($listingME->result) > 0) {
	if (mysql_num_rows($listingME->result) > 200) { $limit = 'ON'; } else { $limit = 'OFF'; }
?>
<h1>MERCH Regroup&eacute; par <?php echo $labeljumper; ?></h1>
<table class="fac" border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
<?php
	while ($row = mysql_fetch_array($listingME->result)) {
## Détermine le Jumper selon les modes defacturation
		switch($_POST['totaux']) {
			case"people":
				$newjumper = $row['idpeople'];
				$T1 = $row['codepeople'].' '.$row['pnom'].' '.$row['pprenom'];
				$T2 = "";
				$D1 = $row['clsociete'].' ('.$row['idclient'].')';
				$L1 = "";
				$L2 = "";
				$D2 = $row['codeshop'].' '.$row['ssociete'].' '.$row['sville'];
				$D3 = $row['prenom'];
			break;
			
			case"agent":
				$newjumper = $row['idagent'];
				$T1 = $row['prenom'];
				$T2 = "";
				$L1 = "People";
				$L2 = "Lieu";
				$D1 = $row['codepeople'].' '.$row['pnom'];
				$D2 = $row['codeshop'].' '.$row['ssociete'].' '.$row['sville'];
				$D3 = $row['clsociete'].' ('.$row['idclient'].')';
			break;
			
			case"facture":
				$newjumper = $row['facnum'];
				$T1 = $row['intitule'].' ('.$row['facnum'].')';
				$T2 = $row['clsociete'].' ('.$row['idclient'].')';
				$L1 = "People";
				$L2 = "Lieu";
				$D1 = $row['codepeople'].' '.$row['pnom'];
				$D2 = $row['codeshop'].' '.$row['ssociete'].' '.$row['sville'];
				$D3 = $row['prenom'];
			break;

			case"client":
				$newjumper = $row['idclient'];
				$T1 = $row['clsociete'].' ('.$row['idclient'].')';
				#$T2 = $row['clsociete'].' ('.$row['idclient'].')';
				$L1 = "People";
				$L2 = "Lieu";
				$D1 = $row['codepeople'].' '.$row['pnom'];
				$D2 = $row['codeshop'].' '.$row['ssociete'].' '.$row['sville'];
				$D3 = $row['prenom'];
			break;

			case"semaine":
				$newjumper = $row['weekm'].'-'.$row['yearm'];
				$T1 = "Sem ".$row['weekm']." ".$row['yearm'];
				$T2 = "";
				$L1 = "People";
				$L2 = "Client";
				$D1 = $row['codepeople'].' '.$row['pnom'];
				$D2 = $row['clsociete'].' ('.$row['idclient'].')';
				$D3 = $row['prenom'];
			break;
			
			case"jour":
				$newjumper = $row['datem'];
				$T1 = strftime("%a %d/%m/%y", strtotime ($row['datem']));
				$T2 = "Sem ".$row['weekm']." ".$row['yearm'];
				$L1 = "People";
				$L2 = "Client";
				$D1 = $row['codepeople'].' '.$row['pnom'];
				$D2 = $row['clsociete'].' ('.$row['idclient'].')';
				$D3 = $row['prenom'];
			break;
			
			case"mois":
				$newjumper = $row['moism'].'-'.$row['yearm'];
				$T1 = strftime("%B %y", strtotime ($row['datem']));
				$T2 = "";
				$L1 = "People";
				$L2 = "Client";
				$D1 = $row['codepeople'].' '.$row['pnom'];
				$D2 = $row['clsociete'].' ('.$row['idclient'].')';
				$D3 = $row['prenom'];
			break;
			
			case"job":
			default:
				$newjumper = $row['idanimjob'];
				$T1 = $row['idanimjob'].' ('.$row['jobreference'].')';
				$T2 = $row['clsociete'].' ('.$row['idclient'].')';
				$L1 = "People";
				$L2 = "Lieu";
				$D1 = $row['codepeople'].' '.$row['pnom'];
				$D2 = $row['codeshop'].' '.$row['ssociete'].' '.$row['sville'];
				$D3 = $row['prenom'];
		}
	
#############################################################################################
### Affichage des totaux de jobs #########
if (($oldjumper != $newjumper) and ($oldjumper != 'START')) { 

	## SS TOTAUX
	if (in_array("soustotaux", $_POST['detail'])) { ?>
	<tr>
		<td class="jobtot">Total</td>
		<td class="jobtot" colspan="4">sur <?php echo $counter; ?> missions</td>
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
		<td class="jobtot"></td>
	</tr><?php
	}
	
	## SS MOYENNE
	if (in_array("moyennes", $_POST['detail'])) { ?>
	<tr>
		<td class="moyen">Moyenne</td>
		<td class="moyen" colspan="4">sur <?php echo $counter; ?> missions</td>
		<td class="moyenF"><?php echo fnbr0($fachtot / $counter); ?></td>
		<td class="moyenP"><?php echo fnbr0($payhtot / $counter); ?></td>
		<td class="moyenD"></td>
	
		<td class="moyenF"><?php echo fnbr0($totkmfac / $counter); ?></td>
		<td class="moyenP"><?php echo fnbr0($totkmpay / $counter); ?></td>
		<td class="moyenD"></td>
	
		<td class="moyenF"><?php echo fnbr0($totfraisfac / $counter); ?></td>
		<td class="moyenP"><?php echo fnbr0($totfraispay / $counter); ?></td>
		<td class="moyenD"></td>
	
		<td class="moyenF"><?php echo fnbr0($totlivfac / $counter); ?></td>
		<td class="moyenP"><?php echo fnbr0($totlivpay / $counter); ?></td>
		<td class="moyenD"></td>
		<td class="moyen"></td>
	</tr><?php
	}
}

#############################################################################################
### Entete de nouveau job #############
####################################
if ($oldjumper != $newjumper) {
	$i = 0;

### Clear les totaux par jobs
	unset ($fachtot);
	unset ($payhtot);

	unset ($totkmfac);
	unset ($totkmpay);

	unset ($totfraisfac);
	unset ($totfraispay);

	unset ($totlivfac);
	unset ($totlivpay);
	unset ($counter);


?>
</table>
<table class="fac" border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
	<thead>
		<tr>
			<td width="45">MERCH</td>
			<td colspan="3"><?php echo $T1 ; ?></td>
			<td><?php echo $T2 ; ?></td>
			<td colspan="3">Heures</td>
			<td colspan="3">D&eacute;plac.</td>
			<td colspan="3">Frais</td>
			<td colspan="3">Livraison</td>
			<td></td>
		</tr>
		<tr>
			<th>N&deg;</th>
			<th>Fac</th>
			<th>Date</th>
			<th>People</th>
			<th>Lieu</th>

			<th>Fac</th>
			<th>Pay</th>
			<th class="D">&#8710;</th>

			<th>Fac</th>
			<th>Pay</th>
			<th class="D">&#8710;</th>

			<th>Fac</th>
			<th>Pay</th>
			<th class="D">&#8710;</th>

			<th>Fac</th>
			<th>Pay</th>
			<th class="D">&#8710;</th>
			<th></th>
		</tr>
		
	</thead>
<?php
}

$merch = new coremerch($row['idmerch']);

if ((in_array("detail", $_POST['detail'])) and ($limit != 'ON')) {


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

?>
	<td class="data"><a href="<?php echo NIVO ?>merch/adminmerch.php?act=show&idmerch=<?php echo $row['idmerch']; ?>"><?php echo $row['idmerch']; ?></a></td>
	<td class="data"><?php if (in_array($row['facturation'], $outfac)) { echo 'out'; } else { echo $row['facnum']; }?></td>
	<td class="data"><?php echo  strftime("%a %d/%m/%y", strtotime ($row['datem'])); ?></td>
	<td class="data"><?php echo $row['codepeople'].' '.$row['pnom']; ?></td>
	<td class="data"><?php echo $row['codeshop'].' '.$row['ssociete'].' '.$row['sville']; ?></td>
	
	<td class="dataF"><?php echo $merch->hprest; ?></td>
	<td class="dataP"><?php echo $merch->hprest; ?></td>
	<td class="dataD"><?php echo fnega($merch->hprest - $merch->hprest);?></td>
	
	<td class="dataF"><?php echo fnbr0($merch->kifac); ?></td> 
	<td class="dataP"><?php echo fnbr0($merch->kipay); ?></td>
	<td class="dataD"><?php echo fnega($merch->kifac - $merch->kipay);?></td>
	
	<td class="dataF"><?php echo fnbr0($row['montantfacture']);?></td>
	<td class="dataP"><?php echo fnbr0($row['montantpaye']); ?></td>
	<td class="dataD"><?php echo fnega($row['montantfacture'] - $row['montantpaye']);?></td>
	
	<td class="dataF"><?php echo fnbr0($row['livraisonfacture']); ?></td>
	<td class="dataP"><?php echo fnbr0($row['livraisonpaye']); ?></td>
	<td class="dataD"><?php echo fnega($row['livraisonfacture'] - $row['livraisonpaye']);?></td>
	<td class="data"><?php echo $D3 ;?></td>
</tr>
<?php 

}

	$fachtot += $merch->hprest;
	$payhtot += $merch->hprest;
	$totkmfac += $merch->kifac;
	$totkmpay += $merch->kipay;
	$totfraisfac += $row['montantfacture'];
	$totfraispay += $row['montantpaye'];
	$totlivfac += $row['livraisonfacture'];
	$totlivpay += $row['livraisonpaye'];
	
	$oldjumper = $newjumper;
	
	$counter++;

}

	## SS TOTAUX
	if (in_array("soustotaux", $_POST['detail'])) { ?>
	<tr>
		<td class="jobtot">Total</td>
		<td class="jobtot" colspan="4">sur <?php echo $counter; ?> missions</td>
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
		<td class="jobtot"></td>
	</tr><?php
	}

	## SS MOYENNE
	if (in_array("moyennes", $_POST['detail'])) { ?>
	<tr>
		<td class="moyen">Moyenne</td>
		<td class="moyen" colspan="4">sur <?php echo $counter; ?> missions</td>
		<td class="moyenF"><?php echo fnbr0($fachtot / $counter); ?></td>
		<td class="moyenP"><?php echo fnbr0($payhtot / $counter); ?></td>
		<td class="moyenD"></td>
	
		<td class="moyenF"><?php echo fnbr0($totkmfac / $counter); ?></td>
		<td class="moyenP"><?php echo fnbr0($totkmpay / $counter); ?></td>
		<td class="moyenD"></td>
	
		<td class="moyenF"><?php echo fnbr0($totfraisfac / $counter); ?></td>
		<td class="moyenP"><?php echo fnbr0($totfraispay / $counter); ?></td>
		<td class="moyenD"></td>
	
		<td class="moyenF"><?php echo fnbr0($totlivfac / $counter); ?></td>
		<td class="moyenP"><?php echo fnbr0($totlivpay / $counter); ?></td>
		<td class="moyenD"></td>
		<td class="moyen"></td>
	</tr><?php
	}

### Clear les totaux par jobs
	unset ($fachtot);
	unset ($payhtot);

	unset ($totkmfac);
	unset ($totkmpay);

	unset ($totfraisfac);
	unset ($totfraispay);

	unset ($totlivfac);
	unset ($totlivpay);
	unset ($counter);
?></table>
<?php } ?>