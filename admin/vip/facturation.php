<link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8">
<div id="centerzonelarge" style="background-color: #D3D3D3;">
<?php
$classe = "planning";

# Recherche des missions a facturer
## Switch mode USER/ADMIN
switch ($mode) {
	case"USER":
		$quid = "WHERE ((j.etat = '11' OR j.etat = '12')  OR (j.etat = '1' AND j.dateout < DATE(NOW())))";
		$page2 = NIVO.'admin/vip/facturation.php';
	break;
	case"ADMIN":
		$quid = "WHERE (j.etat = '13' OR j.etat = '14')";
		$page2 = 'facturation.php';
	break;
}

$missions = $DB->getArray("SELECT 
		m.idvip, m.vipdate, m.vipactivite, m.sexe, m.vipin, m.vipout, m.brk, m.night, m.h150, m.h200, m.ts, m.fts, m.ajust, 
			m.km, m.fkm, m.cat, m.disp, m.unif, m.loc1, m.loc2, m.contratencode,
			m.vkm, m.vfkm, m.vcat, m.vdisp, m.vfr1, m.vfrpeople,
		j.idvipjob, j.reference, j.etat, 
		a.prenom, 
		p.pprenom, p.pnom, p.gsm, p.idpeople, p.nrnational, p.codepeople,
		c.idclient, c.societe AS clsociete, 
		s.idshop, s.societe AS shsociete, s.ville 
	FROM vipmission m
		LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob
		LEFT JOIN agent a ON j.idagent = a.idagent
		LEFT JOIN client c ON j.idclient = c.idclient
		LEFT JOIN people p ON m.idpeople = p.idpeople
		LEFT JOIN shop s ON m.idshop = s.idshop
	".$quid."
	ORDER BY m.idvipjob, m.vipdate, m.idvip
	LIMIT 0 , 2000");

?>
	<table class="sortable-onload-1 no-arrow rowstyle-alt fac" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
<?php
# init vars
$jobnum = 0;

$tothtot = 0;		$tothtotz = 0;
$totbrk = 0;        $totbrkz = 0;
$tothhigh = 0;      $tothhighz = 0;
$tothlow = 0;       $tothlowz = 0;
$tothnight = 0;     $tothnightz = 0;
$toth150 = 0;       $toth150z = 0;
$tothspec = 0;      $tothspecz = 0;
$totthp100 = 0;     $totthp100z = 0;
$totthp150 = 0;     $totthp150z = 0;
$totthp200 = 0;     $totthp200z = 0;
$totkm = 0;         $totkmz = 0;
$totvkm = 0;        $totvkmz = 0;
$totfkm = 0;        $totfkmz = 0;
$totvfkm = 0;       $totvfkmz = 0;
$totloc = 0;        $totlocz = 0;
$totunif = 0;       $totunifz = 0;
$totcat = 0;        $totcatz = 0;
$totvcat = 0;       $totvcatz = 0;
$totdisp = 0;       $totdispz = 0;
$totvdisp = 0;      $totvdispz = 0;
$totNfr = 0;        $totNfrz = 0;
$totfraiscomp = 0;  $totfraiscompz = 0;

setlocale(LC_TIME, 'fr_FR'); 
if (count($missions) > 0) {
	foreach ($missions as $row) { 
		### Clear Last Job values
		
		# séparation par JOB
		if (($jobnum != $row['idvipjob']) and ($jobnum > 0))
		### Affichage des totaux de jobs
		{
		?>
		</tbody>
		<tfoot>
<form action="?act=facture" method="post">
	<input type="hidden" name="idvipjob" value="<?php echo $jdata['idvipjob'] ; ?>">
	<tr>
		<td class="jobtot"><input type="submit" name="modstate" value="Valider"></td>
		<td class="jobtot" colspan="4">
<?php 
		if ($mode == 'USER') { 
			echo '<input type="hidden" name="state" value="13">';
 		} else {
			$sep = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			if ($tothtot > 0) echo '<input type="radio" name="state" checked value="15"> OK '.$sep;
      echo '<input type="radio" name="state" value="12"> NO '.$sep;
			$checked = '';
			switch ($jdata['idclient']) {
				case"1038":
				case"1726":
				case"1498":
					$jdata['etat'] = '97'; 
					$checked = 'checked';
				break;
				case"":
				case"633":
					$jdata['etat'] = '98'; 
					$checked = 'checked';
				break;
				default:
					$jdata['etat'] = '98'; 
			}
	
			echo '<input type="radio" name="state" '.$checked.' value="'.$jdata['etat'].'"> OUT';
		}
?>
		<td class="jobtot"><?php echo fnbr0($tothtot); $tothtotz += $tothtot; ?></td>
		<td class="jobtot"><?php echo fnbr0($totbrk); $totbrkz += $totbrk; ?></td>
		<td class="jobtotF"><?php echo fnbr0($tothhigh); $tothhighz += $tothhigh ; ?></td>
		<td class="jobtotF"><?php echo fnbr0($tothlow); $tothlowz += $tothlow ; ?></td>
		<td class="jobtotF"><?php echo fnbr0($tothnight); $tothnightz += $tothnight ; ?></td>
		<td class="jobtotF"><?php echo fnbr0($toth150); $toth150z += $toth150 ; ?></td>
		<td class="jobtotF"><?php echo fnbr0($tothspec); $tothspecz += $tothspec ; ?></td>
		<td class="jobtotP"><?php echo fnbr0($totthp100); $totthp100z += $totthp100 ; ?></td>
		<td class="jobtotP"><?php echo fnbr0($totthp150); $totthp150z += $totthp150 ; ?></td>
		<td class="jobtotP"><?php echo fnbr0($totthp200); $totthp200z += $totthp200 ; ?></td>
		<td class="jobtot"><?php echo fnbr0($totkm); $totkmz += $totkm; ?></td>
		<td class="jobtot"><?php echo fnbr0($totvkm); $totvkmz += $totvkm; ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totkm - $totvkm));?></td>
		<td class="jobtot"><?php echo fnbr0($totfkm); $totfkmz += $totfkm; ?></td>
		<td class="jobtot"><?php echo fnbr0($totvfkm); $totvfkmz += $totvfkm; ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totfkm - $totvfkm));?></td>
		<td class="jobtot"><?php echo fnbr0($totloc); $totlocz += $totloc; ?></td>
		<td class="jobtot"><?php echo fnbr0($totunif); $totunifz += $totunif; ?></td>
		<td class="jobtot"><?php echo fnbr0($totcat); $totcatz += $totcat; ?></td>
		<td class="jobtot"><?php echo fnbr0($totvcat); $totvcatz += $totvcat; ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totcat - $totvcat));?></td>
		<td class="jobtot"><?php echo fnbr0($totdisp); $totdispz += $totdisp; ?></td>
		<td class="jobtot"><?php echo fnbr0($totvdisp); $totvdispz += $totvdisp; ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totdisp - $totvdisp));?></td>
		<td class="jobtot"><?php echo fnbr0($totNfr); $totNfrz += $totNfr; ?></td>
		<td class="jobtot"><?php echo fnbr0($totfraiscomp); $totfraiscompz += $totfraiscomp; ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totNfr - $totfraiscomp));?></td>
		<td class="jobtot">&nbsp;</td>
	</tr>
</form>
<?php
			### Clear les totaux par jobs
			$tothtot = 0;
			$totbrk = 0;
			$tothhigh = 0;
			$tothlow = 0;
			$tothnight = 0;
			$toth150 = 0;
			$tothspec = 0;
			$totthp100 = 0;
			$totthp150 = 0;
			$totthp200 = 0;
			$totkm = 0;
			$totvkm = 0;
			$totfkm = 0;
			$totvfkm = 0;
			$totloc = 0;
			$totunif = 0;
			$totcat = 0;
			$totvcat = 0;
			$totdisp = 0;
			$totvdisp = 0;
			$totNfr = 0;
			$totfraiscomp = 0;

			unset($jdata);
		}
	
		if ($jobnum != $row['idvipjob'])
		{
		$i=0;
	
		### Define Job Values
			$jdata['etat'] = $row['etat'];
			$jdata['idvipjob'] = $row['idvipjob'];
			$jdata['idclient'] = $row['idclient'];
	
		?>
	</tfoot>
</table>
<br>
<table class="sortable-onload-1 no-arrow rowstyle-alt fac" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
	<thead>
		<tr>
			<td><?php echo $row['idvipjob'] ; ?></td>
			<td colspan="6"><?php echo $row['reference'] ; ?></td>
			<td colspan="5">Factur&eacute;</td>
			<td colspan="3">Pay&eacute;</td>
			<td colspan="12"><?php echo $row['idclient']; ?> - <?php echo $row['clsociete']; ?></td>
			<td colspan="6"><?php echo $row['prenom'] ?></td>
		</tr>
		<tr>
			<th class="sortable-numeric" width="40">N&deg;</th>
			<th class="sortable-date-dmy" colspan="2">Date</th>
			<th class="sortable-text">people</th>
			<th class="sortable-text" colspan="2">H.</th>
			<th class="sortable-numeric">Brk</th>
			<th class="sortable-numeric F">B</th>
			<th class="sortable-numeric F">P</th>
			<th class="sortable-numeric F">NI</th>
			<th class="sortable-numeric F">SUP</th>
			<th class="sortable-numeric F">SP</th>
			<th class="sortable-numeric P">100</th>
			<th class="sortable-numeric P">150</th>
			<th class="sortable-numeric P">200</th>
			<th class="sortable-numeric">Km F</th>
			<th class="sortable-numeric">Km P</th>
			<th class="sortable-numeric D">=</th>
			<th class="sortable-numeric">Kmf F</th>
			<th class="sortable-numeric">Kmf P</th>
			<th class="sortable-numeric D">=</th>
			<th class="sortable-numeric">Loc</th>
			<th class="sortable-numeric">Unif</th>
			<th class="sortable-numeric">Ca F</th>
			<th class="sortable-numeric">Ca P</th>
			<th class="sortable-numeric D">=</th>
			<th class="sortable-numeric">Di F</th>
			<th class="sortable-numeric">Di P</th>
			<th class="sortable-numeric D">=</th>
			<th class="sortable-numeric">Fr F</th>
			<th class="sortable-numeric">Fr P</th>
			<th class="sortable-numeric D">=</th>
			<th title="Contrat Encodé">C</th>
		</tr>
	</thead>
	<tbody>
	<?php
	}

	$fich = new corevip ($row['idvip']);
	?>
		<tr <?php echo ($row['h200'] == 1)?'class="ddim"':''; ?>>
			<td class="data"><a href="<?php echo NIVO.'vip/adminvip.php?act=showmission&idvip='.$row['idvip'] ;?>"><?php if ($row['etat'] == 14) { echo '<font color="red">'.$row['idvip'].'</font>';} else {echo $row['idvip'];} ?></a></td>
			<td class="data"><?php echo  strftime("%d/%m/%y", strtotime ($row['vipdate'])); ?></td>
			<td class="data"><?php echo  strftime("%a", strtotime ($row['vipdate'])); ?></td>
			<td class="data"><?php echo $row['pnom'].' '.$row['codepeople'] ?></td>
			<td class="data"><?php echo ftime($row['vipin']) ?> - <?php echo ftime($row['vipout']) ?></td>
			<td class="data"><?php echo $fich->htot; $tothtot += $fich->htot;?></td>
			<td class="data"><?php echo fnbr0($row['brk']); $totbrk += $row['brk'];?></td>
			<td class="dataF"><?php echo fnbr0($fich->hhigh); $tothhigh += $fich->hhigh ; ?></td>
			<td class="dataF"><?php echo fnbr0($fich->hlow); $tothlow += $fich->hlow ; ?></td>
			<td class="dataF"><?php echo fnbr0($fich->hnight); $tothnight += $fich->hnight ; ?></td>
			<td class="dataF"><?php echo fnbr0($fich->h150); $toth150 += $fich->h150 ; ?></td>
			<td class="dataF"><?php echo fnbr0($fich->hspec); $tothspec += $fich->hspec ; ?></td>
			<td class="dataP"><?php echo fnbr0($fich->thp100); $totthp100 += $fich->thp100 ; ?></td>
			<td class="dataP"><?php echo fnbr0($fich->thp150); $totthp150 += $fich->thp150 ; ?></td>
			<td class="dataP"><?php echo fnbr0($fich->thp200); $totthp200 += $fich->thp200 ; ?></td>
			<td class="data"><?php echo fnbr0($row['km']); $totkm += $row['km'];?></td>
			<td class="data"><?php echo fnbr0($row['vkm']); $totvkm += $row['vkm'];?></td>
			<td class="dataD"><?php echo fnega(fnbr0($row['km'] - $row['vkm']));?></td>
			<td class="data"><?php echo fnbr0($row['fkm']); $totfkm += $row['fkm'];?></td>
			<td class="data"><?php echo fnbr0($row['vfkm']); $totvfkm += $row['vfkm']; ?></td>
			<td class="dataD"><?php echo fnega(fnbr0($row['fkm'] - $row['vfkm']));?></td>
			<td class="data"><?php echo fnbr0($row['loc1'] + $row['loc2']); $totloc += $row['loc1'] + $row['loc2'];?></td>
			<td class="data"><?php echo fnbr0($row['unif']); $totunif += $row['unif'];?></td>
			<td class="data"><?php echo fnbr0($row['cat']); $totcat += $row['cat'];?></td>
			<td class="data"><?php echo fnbr0($row['vcat']); $totvcat += $row['vcat'];?></td>
			<td class="dataD"><?php echo fnega(fnbr0($row['cat'] - $row['vcat']));?></td>
			<td class="data"><?php echo fnbr0($row['disp']); $totdisp += $row['disp'];?></td>
			<td class="data"><?php echo fnbr0($row['vdisp']); $totvdisp += $row['vdisp'];?></td>
			<td class="dataD"><?php echo fnega(fnbr0($row['disp'] - $row['vdisp']));?></td>
			<td class="data"><?php echo fnbr0($fich->MontNfrais); $totNfr += $fich->MontNfrais;?></td>
			<td class="data"><?php echo fnbr0($fich->fraiscomp); $totfraiscomp += $fich->fraiscomp;?></td>
			<td class="dataD"><?php echo fnega(fnbr0($fich->MontNfrais - $fich->fraiscomp));?></td>
			<td class="data" style="width: 10px;"><img src='<?php echo STATIK.'/nro/illus/'.(($row['contratencode'] == "1")?'tick_small.png':'exclamation_small.png'); ?>' width="10" height="10" alt="Contrat"></td>
		</tr>
<?php 
		$jobnum = $row['idvipjob'];
	}
} else {
	?><div align="center">Il n'y a aucune mission VIP &agrave; valider pour le moment</div><?php
}

?>
</tbody>
<tfoot>
	<tr>
		<form action="?act=facture" method="post">
		<input type="hidden" name="idvipjob" value="<?php echo $jdata['idvipjob'] ; ?>">
		<td class="jobtot"><input type="submit" name="modstate" value="Valider"></td>
		<td class="jobtot" colspan="4">
		<?php if ($mode == 'USER') { ?>
			<input type="hidden" name="state" value="13">
		<?php } else {
			$sep = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			if ($tothtot > 0) echo '<input type="radio" name="state" checked value="15"> OK '.$sep.'<input type="radio" name="state" value="12"> NO'.$sep;
			
			$checked = '';
			switch ($jdata['idclient']) {
				case"1038":
				case"1726":
				case"1498":
					$jdata['etat'] = '97'; 
					$checked = 'checked';
				break;
				case"":
				case"633":
					$jdata['etat'] = '98'; 
					$checked = 'checked';
				break;
				default:
					$jdata['etat'] = '98'; 
			}
	
			echo '<input type="radio" name="state" '.$checked.' value="'.$jdata['etat'].'"> OUT';
		} ?></td>
		<td class="jobtot"><?php echo fnbr0($tothtot); $tothtotz += $tothtot; ?></td>
		<td class="jobtot"><?php echo fnbr0($totbrk); $totbrkz += $totbrk; ?></td>
		<td class="jobtotF"><?php echo fnbr0($tothhigh); $tothhighz += $tothhigh ; ?></td>
		<td class="jobtotF"><?php echo fnbr0($tothlow); $tothlowz += $tothlow ; ?></td>
		<td class="jobtotF"><?php echo fnbr0($tothnight); $tothnightz += $tothnight ; ?></td>
		<td class="jobtotF"><?php echo fnbr0($toth150); $toth150z += $toth150 ; ?></td>
		<td class="jobtotF"><?php echo fnbr0($tothspec); $tothspecz += $tothspec ; ?></td>
		<td class="jobtotP"><?php echo fnbr0($totthp100); $totthp100z += $totthp100 ; ?></td>
		<td class="jobtotP"><?php echo fnbr0($totthp150); $totthp150z += $totthp150 ; ?></td>
		<td class="jobtotP"><?php echo fnbr0($totthp200); $totthp200z += $totthp200 ; ?></td>
		<td class="jobtot"><?php echo fnbr0($totkm); $totkmz += $totkm; ?></td>
		<td class="jobtot"><?php echo fnbr0($totvkm); $totvkmz += $totvkm; ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totkm - $totvkm));?></td>
		<td class="jobtot"><?php echo fnbr0($totfkm); $totfkmz += $totfkm; ?></td>
		<td class="jobtot"><?php echo fnbr0($totvfkm); $totvfkmz += $totvfkm; ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totfkm - $totvfkm));?></td>
		<td class="jobtot"><?php echo fnbr0($totloc); $totlocz += $totloc; ?></td>
		<td class="jobtot"><?php echo fnbr0($totunif); $totunifz += $totunif; ?></td>
		<td class="jobtot"><?php echo fnbr0($totcat); $totcatz += $totcat; ?></td>
		<td class="jobtot"><?php echo fnbr0($totvcat); $totvcatz += $totvcat; ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totcat - $totvcat));?></td>
		<td class="jobtot"><?php echo fnbr0($totdisp); $totdispz += $totdisp; ?></td>
		<td class="jobtot"><?php echo fnbr0($totvdisp); $totvdispz += $totvdisp; ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totdisp - $totvdisp));?></td>
		<td class="jobtot"><?php echo fnbr0($totNfr); $totNfrz += $totNfr; ?></td>
		<td class="jobtot"><?php echo fnbr0($totfraiscomp); $totfraiscompz += $totfraiscomp; ?></td>
		<td class="jobtotD"><?php echo fnega(fnbr0($totNfr - $totfraiscomp));?></td>
		<td class="jobtot">&nbsp;</td>
	</form>
	</tr>
</tfoot>
</table>
<br>
	<div align="left" style="font-size: 9px; color: #000;margin: 0 0 0 20px;">*seules les 2000 premi&egrave;res missions sont affich&eacute;es. Pour acc&eacute;der aux autres, validez les jobs apparents.</div>
</div>