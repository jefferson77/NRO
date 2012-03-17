<?php
$oldjumper = 'START';
$outfac = array('97', '98');
if (mysql_num_rows($listingVI->result) > 0) {
	if (mysql_num_rows($listingVI->result) > 200) { $limit = 'ON'; } else { $limit = 'OFF'; }

?>
<h1>VIP Regroup&eacute; par <?php echo $labeljumper; ?></h1>
<table class="fac" border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
<?php
	while ($row = mysql_fetch_array($listingVI->result)) { 
## Détermine le Jumper selon les modes defacturation
		switch($_POST['totaux']) {
			case"people":
				$newjumper = $row['idpeople'];
				$T1 = $row['codepeople'].' '.$row['pnom'].' '.$row['pprenom'];
				$T2 = "";
				$L1 = "Client";
				$L2 = "Lieu";
				$D1 = $row['clsociete'].' ('.$row['idclient'].')';
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

			case"jour":
				$newjumper = $row['vipdate'];
				$T1 = strftime("%a %d/%m/%y", strtotime ($row['vipdate']));
				$T2 = "Mois ".strftime("%B %y", strtotime ($row['vipdate']));
				$L1 = "People";
				$L2 = "Client";
				$D1 = $row['codepeople'].' '.$row['pnom'];
				$D2 = $row['clsociete'].' ('.$row['idclient'].')';
				$D3 = $row['prenom'];
			break;
			
			case"semaine":
			case"mois":
				$newjumper = $row['moism'].'-'.$row['yearm'];
				$T1 = strftime("%B %y", strtotime ($row['vipdate']));
				$T2 = "";
				$L1 = "People";
				$L2 = "Client";
				$D1 = $row['codepeople'].' '.$row['pnom'];
				$D2 = $row['clsociete'].' ('.$row['idclient'].')';
				$D3 = $row['prenom'];
			break;
			
			case"job":
			default:
				$newjumper = $row['idvipjob'];
				$T1 = $row['idvipjob'].' ('.$row['reference'].')'; #
				$T2 = $row['clsociete'].' ('.$row['idclient'].')';
				$L1 = "People";
				$L2 = "Lieu";
				$D1 = $row['codepeople'].' '.$row['pnom'];
				$D2 = $row['codeshop'].' '.$row['ssociete'].' '.$row['sville'];
				$D3 = $row['prenom'];
		}
###############################################################################################################################################################
######## Affichage des totaux de jobs #########################################################
if (($oldjumper != $newjumper) and ($oldjumper != 'START')) {

	## SS TOTAUX
	if (in_array("soustotaux", $_POST['detail'])) { ?>
	<tr>
		<td class="jobtot">Total</td>
		<td class="jobtot" colspan="6">sur <?php echo $counter; ?> missions</td>
		<td class="jobtot"><?php echo fnbr0($tothtot); ?></td>
		<td class="jobtot"><?php echo fnbr0($totbrk); ?></td>
		<td class="jobtotF"><?php echo fnbr0($tothhigh); ?></td>
		<td class="jobtotF"><?php echo fnbr0($tothlow); ?></td>
		<td class="jobtotF"><?php echo fnbr0($tothnight); ?></td>
		<td class="jobtotF"><?php echo fnbr0($toth150); ?></td>
		<td class="jobtotF"><?php echo fnbr0($tothspec); ?></td>
		<td class="jobtotP"><?php echo fnbr0($totthp100); ?></td>
		<td class="jobtotP"><?php echo fnbr0($totthp150); ?></td>
		<td class="jobtotP"><?php echo fnbr0($totthp200); ?></td>
		<td class="jobtot"><?php echo fnbr0($totkm); $totkmz += $totkm; ?></td>
		<td class="jobtot"><?php echo fnbr0($totvkm); ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totkm - $totvkm));?></td>
		<td class="jobtot"><?php echo fnbr0($totfkm); ?></td>
		<td class="jobtot"><?php echo fnbr0($totvfkm); ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totfkm - $totvfkm));?></td>
		<td class="jobtot"><?php echo fnbr0($totloc); ?></td>
		<td class="jobtot"><?php echo fnbr0($totunif); ?></td>
		<td class="jobtot"><?php echo fnbr0($totcat); ?></td>
		<td class="jobtot"><?php echo fnbr0($totvcat); ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totcat - $totvcat));?></td>
		<td class="jobtot"><?php echo fnbr0($totdisp); ?></td>
		<td class="jobtot"><?php echo fnbr0($totvdisp); ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totdisp - $totvdisp));?></td>
		<td class="jobtot"><?php echo fnbr0($totNfr); ?></td>
		<td class="jobtot"><?php echo fnbr0($totfraiscomp); ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totNfr - $totfraiscomp));?></td>
		<td class="jobtot"></td>
	</tr><?php 
	}

	## SS MOYENNE
	if (in_array("moyennes", $_POST['detail'])) { ?>
	<tr>
		<td class="moyen">Moyenne</td>
		<td class="moyen" colspan="6">sur <?php echo $counter; ?> missions</td>
		<td class="moyen"><?php echo fnbr0($tothtot / $counter); ?></td>
		<td class="moyen"><?php echo fnbr0($totbrk / $counter); ?></td>
		<td class="moyenF"><?php echo fnbr0($tothhigh / $counter); ?></td>
		<td class="moyenF"><?php echo fnbr0($tothlow / $counter); ?></td>
		<td class="moyenF"><?php echo fnbr0($tothnight / $counter); ?></td>
		<td class="moyenF"><?php echo fnbr0($toth150 / $counter); ?></td>
		<td class="moyenF"><?php echo fnbr0($tothspec / $counter); ?></td>
		<td class="moyenP"><?php echo fnbr0($totthp100 / $counter); ?></td>
		<td class="moyenP"><?php echo fnbr0($totthp150 / $counter); ?></td>
		<td class="moyenP"><?php echo fnbr0($totthp200 / $counter); ?></td>
		<td class="moyen"><?php echo fnbr0($totkm / $counter);?></td>
		<td class="moyen"><?php echo fnbr0($totvkm / $counter); ?></td>
		<td class="moyenD"></td>
		<td class="moyen"><?php echo fnbr0($totfkm / $counter); ?></td>
		<td class="moyen"><?php echo fnbr0($totvfkm / $counter); ?></td>
		<td class="moyenD"></td>
		<td class="moyen"><?php echo fnbr0($totloc / $counter); ?></td>
		<td class="moyen"><?php echo fnbr0($totunif / $counter); ?></td>
		<td class="moyen"><?php echo fnbr0($totcat / $counter); ?></td>
		<td class="moyen"><?php echo fnbr0($totvcat / $counter); ?></td>
		<td class="moyenD"></td>
		<td class="moyen"><?php echo fnbr0($totdisp / $counter); ?></td>
		<td class="moyen"><?php echo fnbr0($totvdisp / $counter); ?></td>
		<td class="moyenD"></td>
		<td class="moyen"><?php echo fnbr0($totNfr / $counter); ?></td>
		<td class="moyen"><?php echo fnbr0($totfraiscomp / $counter); ?></td>
		<td class="moyenD"></td>
		<td class="moyen"></td>
	</tr><?php
	}
}

#############################################################################################
### Entete de nouveau job #############
####################################
if ($oldjumper != $newjumper) {
	$i=0;

	### Clear les totaux par jobs
		unset ($tothtot);
		unset ($totbrk);

		unset ($tothhigh);
		unset ($tothlow);
		unset ($toth150);
		unset ($tothnight);
		unset ($tothspec);
		
		unset ($totthp100);
		unset ($totthp150);
		unset ($totthp200);

		unset ($totvkm);
		unset ($totkm);
		unset ($totvfkm);
		unset ($totfkm);
		unset ($totvcat);
		unset ($totloc);
		unset ($totunif);
		unset ($totcat);
		unset ($totvdisp);
		unset ($totdisp);
		unset ($totfraiscomp);
		unset ($totNfr);
		unset ($totunif);
		unset ($totloc);
		unset($jdata);
	
		unset ($counter);
?>
</table>
<table class="fac" border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
	<thead>
		<tr>
			<td width="45">VIP</td>
			<td colspan="4"><?php echo $T1 ; ?></td>
			<td colspan="4"><?php echo $T2 ; ?></td>
			<td colspan="5">Factur&eacute;</td>
			<td colspan="3">Pay&eacute;</td>
			<td colspan="12"></td>
			<td colspan="5"></td>
			<td></td>
		</tr>
		<tr>
			<th width="40">N&deg;</th>
			<th>Fac</th>
			<th>Job</th>
			<th>Date</th>
			<th><?php echo $L1 ; ?></th>
			<th><?php echo $L2 ; ?></th>
			<th colspan="2">H.</th>
			<th>Brk</th>
			<th>B</th>
			<th>P</th>
			<th>NI</th>
			<th>SUP</th>
			<th>SP</th>
			<th>100</th>
			<th>150</th>
			<th>200</th>
			<th>Km F</th>
			<th>Km P</th>
			<th class="D">=</th>
			<th>Kmf F</th>
			<th>Kmf P</th>
			<th class="D">=</th>
			<th>Loc</th>
			<th>Unif</th>
			<th>Ca F</th>
			<th>Ca P</th>
			<th class="D">=</th>
			<th>Di F</th>
			<th>Di P</th>
			<th class="D">=</th>
			<th>Fr F</th>
			<th>Fr P</th>
			<th class="D">=</th>
			<th></th>
		</tr>
		
	</thead>
<?php
}

	$fich = new corevip ($row['idvip']);

	if ((in_array("detail", $_POST['detail'])) and ($limit != 'ON')) {
		#> Changement de couleur des lignes #####>>####
		$i++;
		if ($row['h200'] == 1) {
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
	<td class="data"><a href="<?php echo NIVO.'vip/adminvip.php?act=showmission&idvip='.$row['idvip'] ;?>"><?php if ($row['etat'] == 14) { echo '<font color="red">'.$row['idvip'].'</font>';} else {echo $row['idvip'];} ?></a></td>
	<td class="data"><?php if (in_array($row['etat'], $outfac)) { echo 'out'; } else { echo $row['facnum']; } ?></td>
	<td class="data"><?php echo $row['idvipjob'];?></td>
	<td class="data"><?php echo  strftime("%a %d/%m/%y", strtotime ($row['vipdate'])); ?></td>
	<td class="data"><?php echo $D1 ; ?></td>
	<td class="data"><?php echo $D2 ; ?></td>
	<td class="data"><?php echo ftime($row['vipin']) ?> - <?php echo ftime($row['vipout']) ?></td>
	<td class="data"><?php echo $fich->htot; ?></td>
	<td class="data"><?php echo fnbr0($row['brk']);?></td>
	<td class="dataF"><?php echo fnbr0($fich->hhigh);  ?></td>
	<td class="dataF"><?php echo fnbr0($fich->hlow); ?></td>
	<td class="dataF"><?php echo fnbr0($fich->hnight);  ?></td>
	<td class="dataF"><?php echo fnbr0($fich->h150); ?></td>
	<td class="dataF"><?php echo fnbr0($fich->hspec);  ?></td>
	<td class="dataP"><?php echo fnbr0($fich->thp100);  ?></td>
	<td class="dataP"><?php echo fnbr0($fich->thp150);  ?></td>
	<td class="dataP"><?php echo fnbr0($fich->thp200);  ?></td>
	<td class="data"><?php echo fnbr0($row['km']);?></td>
	<td class="data"><?php echo fnbr0($row['vkm']); ?></td>
	<td class="dataD"><?php echo fnega(fnbr0($row['km'] - $row['vkm']));?></td>
	<td class="data"><?php echo fnbr0($row['fkm']); ?></td>
	<td class="data"><?php echo fnbr0($row['vfkm']); ?></td>
	<td class="dataD"><?php echo fnega(fnbr0($row['fkm'] - $row['vfkm']));?></td>
	<td class="data"><?php echo fnbr0($row['loc1'] + $row['loc2']); ?></td>
	<td class="data"><?php echo fnbr0($row['unif']);?></td>
	<td class="data"><?php echo fnbr0($row['cat']);?></td>
	<td class="data"><?php echo fnbr0($row['vcat']);?></td>
	<td class="dataD"><?php echo fnega(fnbr0($row['cat'] - $row['vcat']));?></td>
	<td class="data"><?php echo fnbr0($row['disp']);?></td>
	<td class="data"><?php echo fnbr0($row['vdisp']);?></td>
	<td class="dataD"><?php echo fnega(fnbr0($row['disp'] - $row['vdisp']));?></td>
	<td class="data"><?php echo fnbr0($fich->MontNfrais); ?></td>
	<td class="data"><?php echo fnbr0($fich->fraiscomp); ?></td>
	<td class="dataD"><?php echo fnega(fnbr0($fich->MontNfrais - $fich->fraiscomp));?></td>
	<td class="data"><?php echo $D3 ;?></td>
</tr>
<?php 
		}

	## ajout aux totaux
		$totNfr += $fich->MontNfrais;
		$tothtot += $fich->htot;
		$totbrk += $row['brk'];
		$tothhigh += $fich->hhigh ;
		$tothlow += $fich->hlow ;
		$tothnight += $fich->hnight ;
		$toth150 += $fich->h150 ;
		$tothspec += $fich->hspec ;
		$totthp100 += $fich->thp100 ;
		$totthp150 += $fich->thp150 ;
		$totthp200 += $fich->thp200 ;
		$totkm += $row['km'];
		$totvkm += $row['vkm'];
		$totfkm += $row['fkm'];
		$totvfkm += $row['vfkm'];
		$totloc += $row['loc1'] + $row['loc2'];
		$totunif += $row['unif'];
		$totcat += $row['cat'];
		$totvcat += $row['vcat'];
		$totdisp += $row['disp'];
		$totvdisp += $row['vdisp'];
		$totfraiscomp += $fich->fraiscomp;

		$oldjumper = $newjumper;
	
	$counter++;

	}

	## SS TOTAUX
	if (in_array("soustotaux", $_POST['detail'])) { ?>
	<tr>
		<td class="jobtot">Total</td>
		<td class="jobtot" colspan="6">sur <?php echo $counter; ?> missions</td>
		<td class="jobtot"><?php echo fnbr0($tothtot); ?></td>
		<td class="jobtot"><?php echo fnbr0($totbrk); ?></td>
		<td class="jobtotF"><?php echo fnbr0($tothhigh); ?></td>
		<td class="jobtotF"><?php echo fnbr0($tothlow); ?></td>
		<td class="jobtotF"><?php echo fnbr0($tothnight); ?></td>
		<td class="jobtotF"><?php echo fnbr0($toth150); ?></td>
		<td class="jobtotF"><?php echo fnbr0($tothspec); ?></td>
		<td class="jobtotP"><?php echo fnbr0($totthp100); ?></td>
		<td class="jobtotP"><?php echo fnbr0($totthp150); ?></td>
		<td class="jobtotP"><?php echo fnbr0($totthp200); ?></td>
		<td class="jobtot"><?php echo fnbr0($totkm); $totkmz += $totkm; ?></td>
		<td class="jobtot"><?php echo fnbr0($totvkm); ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totkm - $totvkm));?></td>
		<td class="jobtot"><?php echo fnbr0($totfkm); ?></td>
		<td class="jobtot"><?php echo fnbr0($totvfkm); ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totfkm - $totvfkm));?></td>
		<td class="jobtot"><?php echo fnbr0($totloc); ?></td>
		<td class="jobtot"><?php echo fnbr0($totunif); ?></td>
		<td class="jobtot"><?php echo fnbr0($totcat); ?></td>
		<td class="jobtot"><?php echo fnbr0($totvcat); ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totcat - $totvcat));?></td>
		<td class="jobtot"><?php echo fnbr0($totdisp); ?></td>
		<td class="jobtot"><?php echo fnbr0($totvdisp); ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totdisp - $totvdisp));?></td>
		<td class="jobtot"><?php echo fnbr0($totNfr); ?></td>
		<td class="jobtot"><?php echo fnbr0($totfraiscomp); ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totNfr - $totfraiscomp));?></td>
		<td class="jobtot"></td>
	</tr><?php 
	}

	## SS MOYENNE
	if (in_array("moyennes", $_POST['detail'])) { ?>
	<tr>
		<td class="moyen">Moyenne</td>
		<td class="moyen" colspan="6">sur <?php echo $counter; ?> missions</td>
		<td class="moyen"><?php echo fnbr0($tothtot / $counter); ?></td>
		<td class="moyen"><?php echo fnbr0($totbrk / $counter); ?></td>
		<td class="moyenF"><?php echo fnbr0($tothhigh / $counter); ?></td>
		<td class="moyenF"><?php echo fnbr0($tothlow / $counter); ?></td>
		<td class="moyenF"><?php echo fnbr0($tothnight / $counter); ?></td>
		<td class="moyenF"><?php echo fnbr0($toth150 / $counter); ?></td>
		<td class="moyenF"><?php echo fnbr0($tothspec / $counter); ?></td>
		<td class="moyenP"><?php echo fnbr0($totthp100 / $counter); ?></td>
		<td class="moyenP"><?php echo fnbr0($totthp150 / $counter); ?></td>
		<td class="moyenP"><?php echo fnbr0($totthp200 / $counter); ?></td>
		<td class="moyen"><?php echo fnbr0($totkm / $counter);?></td>
		<td class="moyen"><?php echo fnbr0($totvkm / $counter); ?></td>
		<td class="moyenD"></td>
		<td class="moyen"><?php echo fnbr0($totfkm / $counter); ?></td>
		<td class="moyen"><?php echo fnbr0($totvfkm / $counter); ?></td>
		<td class="moyenD"></td>
		<td class="moyen"><?php echo fnbr0($totloc / $counter); ?></td>
		<td class="moyen"><?php echo fnbr0($totunif / $counter); ?></td>
		<td class="moyen"><?php echo fnbr0($totcat / $counter); ?></td>
		<td class="moyen"><?php echo fnbr0($totvcat / $counter); ?></td>
		<td class="moyenD"></td>
		<td class="moyen"><?php echo fnbr0($totdisp / $counter); ?></td>
		<td class="moyen"><?php echo fnbr0($totvdisp / $counter); ?></td>
		<td class="moyenD"></td>
		<td class="moyen"><?php echo fnbr0($totNfr / $counter); ?></td>
		<td class="moyen"><?php echo fnbr0($totfraiscomp / $counter); ?></td>
		<td class="moyenD"></td>
		<td class="moyen"></td>
	</tr><?php
	}


?>
<tr><td colspan="29" style="background-color: #D3D3D3;">&nbsp;</td></tr>
<?php

### Clear les totaux par jobs
	unset ($tothtot);
	unset ($totbrk);

	unset ($tothhigh);
	unset ($tothlow);
	unset ($toth150);
	unset ($tothnight);
	unset ($tothspec);
	
	unset ($totthp100);
	unset ($totthp150);
	unset ($totthp200);

	unset ($totvkm);
	unset ($totkm);
	unset ($totvfkm);
	unset ($totfkm);
	unset ($totvcat);
	unset ($totloc);
	unset ($totunif);
	unset ($totcat);
	unset ($totvdisp);
	unset ($totdisp);
	unset ($totfraiscomp);
	unset ($totNfr);
	unset ($totunif);
	unset ($totloc);
	unset ($jdata);
	unset ($counter);
?>
</table>
<?php } ?>