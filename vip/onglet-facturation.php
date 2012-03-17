<link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8">
<div style="background-color: #D3D3D3;">
<?php
if (empty($_GET['jumper'])) $_GET['jumper'] = '';

switch ($_GET['jumper']) {
	case"people":
		$sort = 'p.pnom, p.pprenom, m.vipdate';
		$jumper = 'idpeople';
	break;
	case"date":
		$sort = 'm.vipdate, p.pnom, p.pprenom';
		$jumper = 'vipdate';
	break;
	default:
		$sort = 'm.idvipjob, m.vipdate, m.idvip';
		$jumper = '';
}

$facmis = $DB->getArray('SELECT
		m.idvip, m.vipdate, m.vipactivite, m.sexe, m.vipin, m.vipout, m.brk, m.night, m.h150, m.h200, m.ts, m.fts, m.ajust,
			m.km, m.fkm, m.cat, m.disp, m.unif, m.loc1,
			m.vkm, m.vfkm, m.vcat, m.vdisp, m.vfr1, m.vfrpeople, m.metat, m.contratencode,
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
	WHERE j.idvipjob = '.$_REQUEST['idvipjob'].'
	ORDER BY '.$sort);
?>
	<table class="fac" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
<?php
setlocale(LC_TIME, 'fr_FR');
if (count($facmis) > 0) {
### Barre entete
?>
<thead>
<tr>
	<th width="40"><a href="?act=jobfactoring&idvipjob=<?php echo $_GET['idvipjob'].'&jumper=' ;?>">N&deg;</a></th>
	<th><a href="?act=jobfactoring&idvipjob=<?php echo $_GET['idvipjob'].'&jumper=date' ;?>">Date</a></th>
	<th><a href="?act=jobfactoring&idvipjob=<?php echo $_GET['idvipjob'].'&jumper=people' ;?>">People</a></th>
	<th>Hin</th>
	<th>Hout</th>
	<th>Night</th>
	<th>Sup</th>
	<th>Prest</th>
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
	<th>Ca F</th>
	<th>Ca P</th>
	<th class="D">=</th>
	<th>Di F</th>
	<th>Di P</th>
	<th class="D">=</th>
	<th>Fr F</th>
	<th>Fr P</th>
	<th class="D">=</th>
	<th>Unif</th>
	<th>Loca</th>
	<th title="Contrat Encodé">C</th>
	<th></th>
</tr>
</thead>
<tbody>
<?php
	$i          = 0;
	$newjumper  = '';
	$oldjumper  = '';

	## Def Vars
	$jhtot      = 0;
	$jhhigh     = 0;
	$jhlow      = 0;
	$jhnight    = 0;
	$jh150      = 0;
	$jhspec     = 0;
	$jthp100    = 0;
	$jthp150    = 0;
	$jthp200    = 0;
	$jkm        = 0;
	$jvkm       = 0;
	$jfkm       = 0;
	$jvfkm      = 0;
	$jcat       = 0;
	$jvcat      = 0;
	$jdisp      = 0;
	$jvdisp     = 0;
	$jNfr       = 0;
	$jFraisP    = 0;
	$junif      = 0;
	$jloc1      = 0;

	$tothtot    = 0;
	$tothhigh   = 0;
	$tothlow    = 0;
	$tothnight  = 0;
	$toth150    = 0;
	$tothspec   = 0;
	$totthp100  = 0;
	$totthp150  = 0;
	$totthp200  = 0;
	$totkm      = 0;
	$totvkm     = 0;
	$totfkm     = 0;
	$totvfkm    = 0;
	$totcat     = 0;
	$totvcat    = 0;
	$totdisp    = 0;
	$totvdisp   = 0;
	$totNfr     = 0;
	$totFraisP  = 0;
	$totunif    = 0;
	$totloc1    = 0;

	$tothtotz   = 0;
	$tothhighz  = 0;
	$tothlowz   = 0;
	$tothnightz = 0;
	$toth150z   = 0;
	$tothspecz  = 0;
	$totthp100z = 0;
	$totthp150z = 0;
	$totthp200z = 0;
	$totkmz     = 0;
	$totvkmz    = 0;
	$totfkmz    = 0;
	$totvfkmz   = 0;
	$totcatz    = 0;
	$totvcatz   = 0;
	$totdispz   = 0;
	$totvdispz  = 0;
	$totNfrz    = 0;
	$totFraisPz = 0;
	$totunifz   = 0;
	$totloc1z   = 0;

	foreach ($facmis as $row) {

		if (!empty($jumper)) $newjumper = $row[$jumper];

		if (($oldjumper != $newjumper) and ($i > 0)) {
?>
<tr>
	<th></th>
	<th colspan="6"></th>
	<th><?php echo fnbr0($jhtot);?></th>
	<th></th>
	<th class="factitreF"><?php echo fnbr0($jhhigh);?></th>
	<th class="factitreF"><?php echo fnbr0($jhlow);?></th>
	<th class="factitreF"><?php echo fnbr0($jhnight);?></th>
	<th class="factitreF"><?php echo fnbr0($jh150);?></th>
	<th class="factitreF"><?php echo fnbr0($jhspec);?></th>
	<th class="factitreP"><?php echo fnbr0($jthp100);?></th>
	<th class="factitreP"><?php echo fnbr0($jthp150);?></th>
	<th class="factitreP"><?php echo fnbr0($jthp200);?></th>
	<th><?php echo fnbr0($jkm);?></th>
	<th><?php echo fnbr0($jvkm);?></th>
	<th class="D"><?php echo fnega(fnbr0($jkm - $jvkm));?></th>
	<th><?php echo fnbr0($jfkm);?></th>
	<th><?php echo fnbr0($jvfkm);?></th>
	<th class="D"><?php echo fnega(fnbr0($jfkm - $jvfkm));?></th>
	<th><?php echo fnbr0($jcat);?></th>
	<th><?php echo fnbr0($jvcat);?></th>
	<th class="D"><?php echo fnega(fnbr0($jcat - $jvcat));?></th>
	<th><?php echo fnbr0($jdisp);?></th>
	<th><?php echo fnbr0($jvdisp);?></th>
	<th class="D"><?php echo fnega(fnbr0($jdisp - $jvdisp));?></th>
	<th><?php echo fnbr0($jNfr);?></th>
	<th><?php echo fnbr0($jFraisP);?></th>
	<th class="D"><?php echo fnega(fnbr0($jNfr - $jFraisP));?></th>
	<th><?php echo fnbr0($junif);?></th>
	<th><?php echo fnbr0($jloc1);?></th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
</tr>
<?php
	$jhtot   = 0;
	$jhhigh  = 0;
	$jhlow   = 0;
	$jhnight = 0;
	$jh150   = 0;
	$jhspec  = 0;
	$jthp100 = 0;
	$jthp150 = 0;
	$jthp200 = 0;
	$jkm     = 0;
	$jvkm    = 0;
	$jfkm    = 0;
	$jvfkm   = 0;
	$jcat    = 0;
	$jvcat   = 0;
	$jdisp   = 0;
	$jvdisp  = 0;
	$jNfr    = 0;
	$jFraisP = 0;
	$junif   = 0;
	$jloc1   = 0;

	}

	$oldjumper = $newjumper;
?>
<form action="?act=modfacmis&idvipjob=<?php echo $_GET['idvipjob'] ?>&jumper=<?php echo $_GET['jumper'] ?>" method="post">
<input name="idvip" type="hidden" value="<?php echo $row['idvip']; ?>">

<?php
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
			$fich = new corevip ($row['idvip']);

			## Totaux Jumpers
			$jhtot   += $fich->htot;
			$jhhigh  += $fich->hhigh ;
			$jhlow   += $fich->hlow ;
			$jhnight += $fich->hnight ;
			$jh150   += $fich->h150 ;
			$jhspec  += $fich->hspec ;
			$jthp100 += $fich->thp100 ;
			$jthp150 += $fich->thp150 ;
			$jthp200 += $fich->thp200 ;
			$jkm     += $row['km'];
			$jvkm    += $row['vkm'];
			$jfkm    += $row['fkm'];
			$jvfkm   += $row['vfkm'];
			$jcat    += $row['cat'];
			$jvcat   += $row['vcat'];
			$jdisp   += $row['disp'];
			$jvdisp  += $row['vdisp'];
			$jNfr    += $fich->MontNfrais;
			$jFraisP += $fich->FraisP;
			$junif   += $row['unif'];
			$jloc1   += $row['loc1'];
	?>
	<td class="data"><a target="_blank" href="<?php echo NIVO.'vip/adminvip.php?act=showmission&idvip='.$row['idvip'];?>"><?php if ($row['metat'] == 14) { echo '<font color="red">'.$row['idvip'].'</font>';} else {echo $row['idvip'];} ?></a></td>
	<td class="data"><?php echo  strftime("%d/%m", strtotime ($row['vipdate'])); ?></td>
	<td class="data"><?php echo $row['codepeople'].' '.$row['pnom'] ?></td>
	<td class="data"><input type="text" size="4" name="vipin" value="<?php echo ftime($row['vipin']) ?>"></td>
	<td class="data"><input type="text" size="4" name="vipout" value="<?php echo ftime($row['vipout']) ?>"></td>
	<td class="data"><input type="text" size="3" name="night" value="<?php echo fnbr0($row['night']) ?>"></td>
	<td class="data"><input type="text" size="3" name="h150" value="<?php echo fnbr0($row['h150']) ?>"></td>
	<td class="data"><?php echo fnbr0($fich->htot); $tothtot += $fich->htot;?></td>
	<td class="data"><input type="text" size="4" name="brk" value="<?php echo fnbr0($row['brk']) ?>"></td>
	<td class="dataF"><?php echo fnbr0($fich->hhigh); $tothhigh += $fich->hhigh ; ?></td>
	<td class="dataF"><?php echo fnbr0($fich->hlow); $tothlow += $fich->hlow ; ?></td>
	<td class="dataF"><?php echo fnbr0($fich->hnight); $tothnight += $fich->hnight ; ?></td>
	<td class="dataF"><?php echo fnbr0($fich->h150); $toth150 += $fich->h150 ; ?></td>
	<td class="dataF"><?php echo fnbr0($fich->hspec); $tothspec += $fich->hspec ; ?></td>
	<td class="dataP"><?php echo fnbr0($fich->thp100); $totthp100 += $fich->thp100 ; ?></td>
	<td class="dataP"><?php echo fnbr0($fich->thp150); $totthp150 += $fich->thp150 ; ?></td>
	<td class="dataP"><?php echo fnbr0($fich->thp200); $totthp200 += $fich->thp200 ; ?></td>
	<td class="data"><input type="text" size="4" name="km" value="<?php echo fnbr0($row['km']);?>"><?php $totkm += $row['km'];?></td>
	<td class="data"><input type="text" size="4" name="vkm" value="<?php echo fnbr0($row['vkm']);?>"><?php $totvkm += $row['vkm'];?></td>
	<td class="dataD"><?php echo fnega(fnbr0($row['km'] - $row['vkm']));?></td>
	<td class="data"><input type="text" size="4" name="fkm" value="<?php echo fnbr0($row['fkm']);?>"><?php $totfkm += $row['fkm'];?></td>
	<td class="data"><input type="text" size="4" name="vfkm" value="<?php echo fnbr0($row['vfkm']);?>"><?php $totvfkm += $row['vfkm']; ?></td>
	<td class="dataD"><?php echo fnega(fnbr0($row['fkm'] - $row['vfkm']));?></td>
	<td class="data"><input type="text" size="4" name="cat" value="<?php echo fnbr0($row['cat']);?>"><?php $totcat += $row['cat'];?></td>
	<td class="data"><input type="text" size="4" name="vcat" value="<?php echo fnbr0($row['vcat']);?>"><?php $totvcat += $row['vcat'];?></td>
	<td class="dataD"><?php echo fnega(fnbr0($row['cat'] - $row['vcat']));?></td>
	<td class="data"><input type="text" size="4" name="disp" value="<?php echo fnbr0($row['disp']);?>"><?php $totdisp += $row['disp'];?></td>
	<td class="data"><input type="text" size="4" name="vdisp" value="<?php echo fnbr0($row['vdisp']);?>"><?php $totvdisp += $row['vdisp'];?></td>
	<td class="dataD"><?php echo fnega(fnbr0($row['disp'] - $row['vdisp']));?></td>
	<td class="data"><?php echo fnbr0($fich->MontNfrais); $totNfr += $fich->MontNfrais;?></td>
	<td class="data"><?php echo fnbr0($fich->FraisP); $totFraisP += $fich->FraisP;?></td>
	<td class="dataD"><?php echo fnega(fnbr0($fich->MontNfrais - $fich->FraisP));?></td>
	<td class="data"><input type="text" size="4" name="unif" value="<?php echo fnbr0($row['unif']);?>"><?php $totunif += $row['unif'];?></td>
	<td class="data"><input type="text" size="4" name="loc1" value="<?php echo fnbr0($row['loc1']);?>"><?php $totloc1 += $row['loc1'];?></td>
	<td class="data" style="width: 10px;"><img src='<?php echo STATIK.'/nro/illus/'.(($row['contratencode'] == "1")?'tick_small.png':'exclamation_small.png'); ?>' width="10" height="10" alt="Contrat"></td>
	<td class="data"><input name="M" type="submit" value="M" class="lineupdate"></td>
</tr>
</form>
<?php
	}
### Sous totaux
?>
</tbody>
<tfoot>
<tr>
	<td class="jobtot"></td>
	<td class="jobtot" colspan="6"></td>
	<td class="jobtot"><?php echo fnbr0($tothtot); $tothtotz += $tothtot; ?></td>
	<td class="jobtot"></td>
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
	<td class="jobtot"><?php echo fnbr0($totcat); $totcatz += $totcat; ?></td>
	<td class="jobtot"><?php echo fnbr0($totvcat); $totvcatz += $totvcat; ?></td>
	<td class="jobtotD"><?php echo fnega(fnbr0($totcat - $totvcat));?></td>
	<td class="jobtot"><?php echo fnbr0($totdisp); $totdispz += $totdisp; ?></td>
	<td class="jobtot"><?php echo fnbr0($totvdisp); $totvdispz += $totvdisp; ?></td>
	<td class="jobtotD"><?php echo fnega(fnbr0($totdisp - $totvdisp));?></td>
	<td class="jobtot"><?php echo fnbr0($totNfr); $totNfrz += $totNfr; ?></td>
	<td class="jobtot"><?php echo fnbr0($totFraisP); $totFraisPz += $totFraisP; ?></td>
	<td class="jobtotD"><?php echo fnega(fnbr0($totNfr - $totFraisP));?></td>
	<td class="jobtot"><?php echo fnbr0($totunif); $totunifz += $totunif; ?></td>
	<td class="jobtot"><?php echo fnbr0($totloc1); $totloc1z += $totloc1; ?></td>
	<td class="jobtot">&nbsp;</td>
	<td class="jobtot">&nbsp;</td>
</tr>
</tfoot>
<?php } ?>
</table>
</div>