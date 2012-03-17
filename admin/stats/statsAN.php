<?php
$oldjumper = 'START';
$outfac = array('97', '98');
if (mysql_num_rows($listingAN->result) > 0) {
	if (mysql_num_rows($listingAN->result) > 200) { $limit = 'ON'; } else { $limit = 'OFF'; }
?>
<h1>ANIM Regroup&eacute; par <?php echo $labeljumper; ?></h1>
<table class="fac" border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
<?php
	while ($row = mysql_fetch_array($listingAN->result)) {
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
				$T1 = utf8_decode(strftime("%a %d/%m/%y", strtotime ($row['datem'])));
				$T2 = "Sem ".$row['weekm']." ".$row['yearm'];
				$L1 = "People";
				$L2 = "Client";
				$D1 = $row['codepeople'].' '.$row['pnom'];
				$D2 = $row['clsociete'].' ('.$row['idclient'].')';
				$D3 = $row['prenom'];
			break;
			
			case"mois":
				$newjumper = $row['moism'].'-'.$row['yearm'];
				$T1 = utf8_decode(strftime("%B %y", strtotime ($row['datem'])));
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
####################################
	if (($oldjumper != $newjumper) and ($oldjumper != 'START')) {

	## SS TOTAUX
	if (in_array("soustotaux", $_POST['detail'])) { ?>
<tr>
	<td class="jobtot">Total</td>
	<td class="jobtot" colspan="5">sur <?php echo $counter; ?> missions</td>
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
	<td class="jobtot"></td>
</tr>
<?php
}
	## SS MOYENNE
if (in_array("moyennes", $_POST['detail'])) {
?>
<tr>
	<td class="moyen">Moyenne</td>
	<td class="moyen" colspan="5">sur <?php echo $counter; ?> missions</td>
	<td class="moyenF"><?php echo fnbr0($fachtot / $counter); ?></td>
	<td class="moyenP"><?php echo fnbr0($payhtot / $counter); ?></td>
	<td class="moyenS"><?php echo fnbr0($hsuptot / $counter); ?></td>
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

	<td class="moyen"><?php echo fnbr0($totbriefing / $counter);  ?></td>
	<td class="moyen"><?php echo fnbr0($totstand / $counter); ?></td>
	<td class="moyen"><?php echo fnbr0($totgobelet / $counter); ?></td>
	<td class="moyen"><?php echo fnbr0($totserviette / $counter); ?></td>
	<td class="moyen"><?php echo fnbr0($totfour / $counter); ?></td>
	<td class="moyen"><?php echo fnbr0($totcuredent / $counter); ?></td>
	<td class="moyen"><?php echo fnbr0($totcuillere / $counter); ?></td>
	<td class="moyen"><?php echo fnbr0($totrechaud / $counter); ?></td>
	<td class="moyen"><?php echo fnbr0($totautre / $counter); ?></td>
	<td class="moyen"></td>
</tr>

<?php }
}

#############################################################################################
### Entete de nouveau job #############
####################################
if ($oldjumper != $newjumper) {
	$i = 0;
	
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
	
	unset ($counter);

?>
</table>
<table class="fac" border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
	<thead>
		<tr>
			<td width="45">ANIM</td>
			<td colspan="4"><?php echo $T1 ; ?></td>
			<td><?php echo $T2 ; ?></td>
			<td colspan="4">Heures</td>
			<td colspan="3">D&eacute;plac.</td>
			<td colspan="3">Frais</td>
			<td colspan="3">Livraison</td>
			<td colspan="5"></td>
			<td colspan="4"></td>
			<td></td>
		</tr>
		<tr>
			<th>N&deg;</th>
			<th>Fac</th>
			<th>Job</th>
			<th>Date</th>
			<th><?php echo $L1 ; ?></th>
			<th><?php echo $L2 ; ?></th>

			<th>Fac</th>
			<th>Pay</th>
			<th class="D">Sup</th>
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

			<th>Brief.</th>
			<th>Stand</th>
			<th>Gob</th>
			<th>Serv</th>
			<th>Four</th>
			<th>Curd</th>
			<th>Cuil</th>
			<th>Rech</th>
			<th>autre</th>
			<th></th>
		</tr>
	</thead>
<?php
}

	$anim = new coreanim($row['idanimation']);

	if ((in_array("detail", $_POST['detail'])) and ($limit != 'OFF')) {

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
	<td class="data"><a href="<?php echo NIVO ?>animation2/adminanim.php?act=showmission&idanimation=<?php echo $row['idanimation']; ?>&idanimjob=<?php echo $row['idanimjob']; ?>#"><?php echo $row['idanimation']; ?></a></td>
	<td class="data"><?php if (in_array($row['facturation'], $outfac)) { echo 'out'; } else { echo $row['facnum']; }?></td>
	<td class="data"><a href="<?php echo NIVO ?>animation2/adminanim.php?act=showjob&idanimjob=<?php echo $row['idanimjob']; ?>#"><?php echo $row['idanimjob']; ?></a></td>
	<td class="data"><?php echo  strftime("%a %d/%m/%y", strtotime ($row['datem'])); ?></td>
	<td class="data"><?php echo $D1 ; ?></td>
	<td class="data"><?php echo $D2 ; ?></td>
	
	<td class="dataF"><?php echo $anim->hprest;?></td>
	<td class="dataP"><?php echo $anim->hprest;?></td>
	<td class="dataS"><?php if ($anim->hprest > 9) { echo fnbr0($anim->hprest - 9);} ?></td>
	<td class="dataD"><?php echo fnega($anim->hprest - $anim->hprest);?></td>
	
	<td class="dataF"><?php echo fnbr0($anim->kifac);?></td> 
	<td class="dataP"><?php echo fnbr0($anim->kipay);?></td>
	<td class="dataD"><?php echo fnega($anim->kifac - $anim->kipay);?></td>
	
	<td class="dataF"><?php echo fnbr0($row['montantfacture']);?></td>
	<td class="dataP"><?php echo fnbr0($row['montantpaye']);?></td>
	<td class="dataD"><?php echo fnega($row['montantfacture'] - $row['montantpaye']);?></td>
	
	<td class="dataF"><?php echo fnbr0($row['livraisonfacture']);?></td>
	<td class="dataP"><?php echo fnbr0($row['livraisonpaye']);?></td>
	<td class="dataD"><?php echo fnega($row['livraisonfacture'] - $row['livraisonpaye']);?></td>
	
	<td class="data"><?php echo fnbr0($row['briefing']);?></td>
	<td class="data"><?php echo fnbr0($row['stand']);?></td>
	<td class="data"><?php echo fnbr0($row['gobelet']);?></td>
	<td class="data"><?php echo fnbr0($row['serviette']);?></td>
	<td class="data"><?php echo fnbr0($row['four']);?></td>
	<td class="data"><?php echo fnbr0($row['curedent']);?></td>
	<td class="data"><?php echo fnbr0($row['cuillere']);?></td>
	<td class="data"><?php echo fnbr0($row['rechaud']);?></td>
	<td class="data"><?php echo fnbr0($row['autre']);?></td>
	<td class="data"><?php echo $D3 ;?></td>
</tr>
<?php 

	}

	$fachtot += $anim->hprest;
	$payhtot += $anim->hprest;
	
	if ($anim->hprest > 9) $hsuptot += ($anim->hprest - 9);
	
	$totkmfac += $anim->kifac;
	$totkmpay += $anim->kipay;
	$totfraisfac += $row['montantfacture'];
	$totfraispay += $row['montantpaye'];
	$totlivfac += $row['livraisonfacture'];
	$totlivpay += $row['livraisonpaye'];
	$totbriefing += $row['briefing']; 
	$totstand += $row['stand']; 
	$totgobelet += $row['gobelet']; 
	$totserviette += $row['serviette']; 
	$totfour += $row['four']; 
	$totcuredent += $row['curedent']; 
	$totcuillere += $row['cuillere']; 
	$totrechaud += $row['rechaud']; 
	$totautre += $row['autre']; 

	$oldjumper = $newjumper;
	
	$counter++;


}
	## SS TOTAUX
if (in_array("soustotaux", $_POST['detail'])) { ?>
<tr>
	<td class="jobtot">Total</td>
	<td class="jobtot" colspan="5">sur <?php echo $counter; ?> missions</td>
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
	<td class="jobtot"></td>
</tr>
<?php }

	## SS MOYENNE
if (in_array("moyennes", $_POST['detail'])) { ?>
<tr>
	<td class="moyen">Moyenne</td>
	<td class="moyen" colspan="5">sur <?php echo $counter; ?> missions</td>
	<td class="moyenF"><?php echo fnbr0($fachtot / $counter); ?></td>
	<td class="moyenP"><?php echo fnbr0($payhtot / $counter); ?></td>
	<td class="moyenS"><?php echo fnbr0($hsuptot / $counter); ?></td>
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

	<td class="moyen"><?php echo fnbr0($totbriefing / $counter);  ?></td>
	<td class="moyen"><?php echo fnbr0($totstand / $counter); ?></td>
	<td class="moyen"><?php echo fnbr0($totgobelet / $counter); ?></td>
	<td class="moyen"><?php echo fnbr0($totserviette / $counter); ?></td>
	<td class="moyen"><?php echo fnbr0($totfour / $counter); ?></td>
	<td class="moyen"><?php echo fnbr0($totcuredent / $counter); ?></td>
	<td class="moyen"><?php echo fnbr0($totcuillere / $counter); ?></td>
	<td class="moyen"><?php echo fnbr0($totrechaud / $counter); ?></td>
	<td class="moyen"><?php echo fnbr0($totautre / $counter); ?></td>
	<td class="moyen"></td>
</tr>
<?php }
	
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
	unset ($counter);

?></table>
<?php } ?>