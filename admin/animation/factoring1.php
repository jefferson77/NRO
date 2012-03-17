<?php
$oldjumper = 0;
$chclient = array('1038', '1726', '1498');

setlocale(LC_TIME, 'fr_FR');

if (mysql_num_rows($listing->result) > 0) {
?>
<table class="sortable-onload-2 no-arrow rowstyle-alt fac" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
<?php

	while ($row = mysql_fetch_array($listing->result)) {
	## Détermine le Jumper selon les modes defacturation
		switch ($jump) {
			case "SE-PO": # Par semaine / Bon de commande
				$newjumper = $row['weekm'].'-'.$row['idclient'].'-'.$row['boncommande'];
			break;
			case "SE-OF": # Par semaine / Officer
				$newjumper = $row['weekm'].'-'.$row['idclient'].'-'.$row['idcofficer'];
			break;
			case "MO-PO": # Par mois / Bon de Commande
				$newjumper = date("m", strtotime($row['datem'])).'-'.$row['idclient'].'-'.$row['boncommande'];
			break;
			case "MO-OF": # Par mois / Officer
				$newjumper = date("m", strtotime($row['datem'])).'-'.$row['idclient'].'-'.$row['idcofficer'];
			break;
			default: # autres
				$newjumper = $row['weekm'].'-'.$row['idclient'].'-'.$row['idcofficer'];
		}

#############################################################################################
### Affichage des totaux de jobs #########
####################################
if (($oldjumper != $newjumper) and ($oldjumper > 0)) { ?>
	</tbody>
	<tfoot>
		<tr>
			<td class="jobtot"><input type="submit" name="modstate" value="Valider"></td>
			<td class="jobtot" colspan="5">
		<?php if ($mode == 'USER') { ?>
			<input type="hidden" name="state" value="2">
		<?php } else { 
			$sep = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			if ($fachtot > 0) echo '<input type="radio" name="state" value="5" '.((!in_array($lastclient, $chclient))?' checked':'').'> OK '.$sep.'<input type="radio" name="state" value="3"> NO '.$sep;

			$checked = '';
			$outetat = '97';
			if (in_array($lastclient, $chclient)) { $checked = 'checked';}
			if (empty($lastclient) or ($lastclient == 633)) { $outetat = '98'; $checked = 'checked';}

			echo '<input type="radio" name="state" '.$checked.' value="'.$outetat.'"> OUT';
		} ?>
			</td>
			<td class="jobtotF"><?php echo fnbr0($fachtot); ?></td>
			<td class="jobtotP"><?php echo fnbr0($payhtot); ?></td>
			<td class="jobtotS"><?php echo fnbr0($hsuptot); ?></td>
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

			<td class="jobtot"><?php echo fnbr0($totbriefing);  ?></td>
			<td class="jobtot"><?php echo fnbr0($totstand); ?></td>
			<td class="jobtot"><?php echo fnbr0($totgobelet); ?></td>
			<td class="jobtot"><?php echo fnbr0($totserviette); ?></td>
			<td class="jobtot"><?php echo fnbr0($totfour); ?></td>
			<td class="jobtot"><?php echo fnbr0($totcuredent); ?></td>
			<td class="jobtot"><?php echo fnbr0($totcuillere); ?></td>
			<td class="jobtot"><?php echo fnbr0($totrechaud); ?></td>
			<td class="jobtot"><?php echo fnbr0($totautre); ?></td>
			<td class="jobtot">&nbsp;</td>
		</tr>
	</tfoot>
<?php
}

#############################################################################################
### Entete de nouveau job #############
####################################
if ($oldjumper != $newjumper) {
	$i = 0;

	### Clear les totaux par jobs
	$fachtot = 0;
	$payhtot = 0;
	$hsuptot = 0;

	$totkmfac = 0;
	$totkmpay = 0;
	$totfraisfac = 0;
	$totfraispay = 0;
	$totlivfac = 0;
	$totlivpay = 0;
	$totbriefing = 0;
	$totstand = 0;
	$totgobelet = 0;
	$totserviette = 0;
	$totfour = 0;
	$totcuredent = 0;
	$totcuillere = 0;
	$totrechaud = 0;
	$totautre = 0;

?>
</table>
</form>
<br>
<form action="?act=facture" method="post">
<table class="sortable-onload-2 no-arrow rowstyle-alt fac" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
	<thead>
		<tr>
			<td colspan="5"><?php echo $row['clsociete'].' ('.$row['idclient'].')' ; ?></td>
		<?php if (substr($jump, -2) == 'PO') { ?>
			<td><?php echo $row['boncommande']; ?></td>
		<?php } else { ?>
			<td><?php echo $row['oprenom'].' '.$row['onom'].' ('.$row['idcofficer'].')'; ?></td>
		<?php } ?>
			<td colspan="4">Heures</td>
			<td colspan="3">D&eacute;plac.</td>
			<td colspan="3">Frais</td>
			<td colspan="3">Livraison</td>
			<td colspan="5">
		<?php
			switch ($jump) {
				case "SE-OF": # Par semaine / Officer
				case "SE-PO": # Par semaine / Bon de commande
					echo 'Sem : '.$row['weekm'].'-'.date("Y", strtotime($row['datem']));
				break;
				case "MO-PO": # Par mois / Bon de Commande
				case "MO-OF": # Par mois / Officer
					echo 'Mois : '.date("m-Y", strtotime($row['datem']));
				break;
			}
		?>
			</td>
			<td colspan="4"><?php echo $row['prenom'] ?></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th class="sortable-numeric" width="40">N&deg;</th>
			<th class="sortable-numeric" width="40">Job</th>
			<th class="sortable-date-dmy" colspan="2">Date</th>
			<th class="sortable-text">People</th>
			<th class="sortable-text">Lieu</th>

			<th class="sortable-numeric">Fac</th>
			<th class="sortable-numeric">Pay</th>
			<th class="sortable-numeric D">Sup</th>
			<th class="sortable-numeric D">&#8710;</th>

			<th class="sortable-numeric">Fac</th>
			<th class="sortable-numeric">Pay</th>
			<th class="sortable-numeric D">&#8710;</th>

			<th class="sortable-numeric">Fac</th>
			<th class="sortable-numeric">Pay</th>
			<th class="sortable-numeric D">&#8710;</th>

			<th class="sortable-numeric">Fac</th>
			<th class="sortable-numeric">Pay</th>
			<th class="sortable-numeric D">&#8710;</th>

			<th class="sortable-numeric">Brief.</th>
			<th class="sortable-numeric">Stand</th>
			<th class="sortable-numeric">Gob</th>
			<th class="sortable-numeric">Serv</th>
			<th class="sortable-numeric">Four</th>
			<th class="sortable-numeric">Curd</th>
			<th class="sortable-numeric">Cuil</th>
			<th class="sortable-numeric">Rech</th>
			<th class="sortable-numeric">autre</th>
			<th title="Contrat encodé">C</th>
		</tr>
	</thead>
	<tbody>
<?php
}

$anim = new coreanim($row['idanimation']);
?>
<tr <?php echo ($row['ferie'] == '200')?'class="ddim"':''; ?>>
	<td class="data"><input type="hidden" name="ids[]" value="<?php echo $row['idanimation']; ?>"><a href="<?php echo NIVO ?>animation2/adminanim.php?act=showmission&idanimation=<?php echo $row['idanimation']; ?>&idanimjob=<?php echo $row['idanimjob']; ?>#"><?php echo $row['idanimation']; ?></a></td>
	<td class="data"><a href="<?php echo NIVO ?>animation2/adminanim.php?act=showjob&idanimjob=<?php echo $row['idanimjob']; ?>#"><?php echo $row['idanimjob']; ?></a></td>
	<td class="data"><?php echo  strftime("%d/%m/%y", strtotime ($row['datem'])); ?></td>
	<td class="data"><?php echo  strftime("%a", strtotime ($row['datem'])); ?></td>
	<td class="data"><?php echo $row['pnom'].' '.$row['codepeople']; ?></td>
	<td class="data"><?php echo $row['codeshop'].' '.$row['ssociete'].' '.$row['sville']; ?></td>

	<td class="dataF"><?php echo $anim->hprest; $fachtot += $anim->hprest;?></td> <!-- Manque Calcul des hfac (avec l'heure du midi) -->
	<td class="dataP"><?php echo $anim->hprest; $payhtot += $anim->hprest;?></td>
	<td class="dataS"><?php if (($anim->hprest > 9) and ($anim->h200 == 0)) { echo fnbr0($anim->hprest - 9); $hsuptot += ($anim->hprest - 9);} ?></td>
	<td class="dataD"><?php echo fnega($anim->hprest - $anim->hprest);?></td>

	<td class="dataF"><?php echo fnbr0($anim->kifac); $totkmfac += $anim->kifac;?></td>
	<td class="dataP"><?php echo fnbr0($anim->kipay); $totkmpay += $anim->kipay;?></td>
	<td class="dataD"><?php echo fnega($anim->kifac - $anim->kipay);?></td>

	<td class="dataF"><?php echo fnbr0($row['montantfacture']); $totfraisfac += $row['montantfacture'];?></td>
	<td class="dataP"><?php echo fnbr0($row['montantpaye']); $totfraispay += $row['montantpaye'];?></td>
	<td class="dataD"><?php echo fnega($row['montantfacture'] - $row['montantpaye']);?></td>

	<td class="dataF"><?php echo fnbr0($row['livraisonfacture']); $totlivfac += $row['livraisonfacture'];?></td>
	<td class="dataP"><?php echo fnbr0($row['livraisonpaye']); $totlivpay += $row['livraisonpaye'];?></td>
	<td class="dataD"><?php echo fnega($row['livraisonfacture'] - $row['livraisonpaye']);?></td>

	<td class="data"><?php echo fnbr0($row['briefing']); $totbriefing += $row['briefing']; ?></td>
	<td class="data"><?php echo fnbr0($row['stand']); $totstand += $row['stand']; ?></td>
	<td class="data"><?php echo fnbr0($row['gobelet']); $totgobelet += $row['gobelet']; ?></td>
	<td class="data"><?php echo fnbr0($row['serviette']); $totserviette += $row['serviette']; ?></td>
	<td class="data"><?php echo fnbr0($row['four']); $totfour += $row['four']; ?></td>
	<td class="data"><?php echo fnbr0($row['curedent']); $totcuredent += $row['curedent']; ?></td>
	<td class="data"><?php echo fnbr0($row['cuillere']); $totcuillere += $row['cuillere']; ?></td>
	<td class="data"><?php echo fnbr0($row['rechaud']); $totrechaud += $row['rechaud']; ?></td>
	<td class="data"><?php echo fnbr0($row['autre']); $totautre += $row['autre']; ?></td>
	<td class="data" style="width: 10px;"><img src='<?php echo STATIK.'/nro/illus/'.(($row['ccheck'] == "Y")?'tick_small.png':'exclamation_small.png'); ?>' width="10" height="10" alt="Contrat"></td>
</tr>
<?php
$oldjumper = $newjumper;
$lastclient = $row['idclient'];

}
 ?>
</tbody>
<tfoot>
	<tr>
		<td class="jobtot"><input type="submit" name="modstate" value="Valider"></td>
		<td class="jobtot" colspan="5">
	<?php if ($mode == 'USER') { ?>
		<input type="hidden" name="state" value="2">
	<?php } else { 
		$sep = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		if ($fachtot > 0) echo '<input type="radio" name="state" value="5" '.((!in_array($lastclient, $chclient))?' checked':'').'> OK '.$sep.'<input type="radio" name="state" value="3"> NO '.$sep;

		$checked = '';
		$outetat = '97';
		if (in_array($lastclient, $chclient)) { $checked = 'checked';}
		if (empty($lastclient) or ($lastclient == 633)) { $outetat = '98'; $checked = 'checked';}

		echo '<input type="radio" name="state" '.$checked.' value="'.$outetat.'"> OUT';
	}?></td>
		<td class="jobtotF"><?php echo fnbr0($fachtot); ?></td>
		<td class="jobtotP"><?php echo fnbr0($payhtot); ?></td>
		<td class="jobtotS"><?php echo fnbr0($hsuptot); ?></td>
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

		<td class="jobtot"><?php echo fnbr0($totbriefing);  ?></td>
		<td class="jobtot"><?php echo fnbr0($totstand); ?></td>
		<td class="jobtot"><?php echo fnbr0($totgobelet); ?></td>
		<td class="jobtot"><?php echo fnbr0($totserviette); ?></td>
		<td class="jobtot"><?php echo fnbr0($totfour); ?></td>
		<td class="jobtot"><?php echo fnbr0($totcuredent); ?></td>
		<td class="jobtot"><?php echo fnbr0($totcuillere); ?></td>
		<td class="jobtot"><?php echo fnbr0($totrechaud); ?></td>
		<td class="jobtot"><?php echo fnbr0($totautre); ?></td>
		<td class="jobtot">&nbsp;</td>
	</tr>
</tfoot>
<?php
### Clear les totaux par jobs
	unset ($fachtot);
	unset ($payhtot);
	unset ($hsuptot);

	unset ($totkmfac);
	unset ($totkmpay);

	unset ($totfraisfac);
	unset ($totfraispay);

	unset ($totlivfac);
	unset ($totlivpay);

	unset ($totbriefing);
	unset ($totstand);
	unset ($totgobelet);
	unset ($totserviette);
	unset ($totfour);
	unset ($totcuredent);
	unset ($totcuillere);
	unset ($totrechaud);
	unset ($totautre);
?>
	</table>
</form>

<?php } ?>