<div id="leftmenu"></div>
<div id="infozone">
<?php

if (!isset($_GET['date'])) $_GET['date'] = '';

################################################
#### Barre d'infos people					####
################################################

$pay = new payement($_REQUEST['idpeople']);

if ($pay->infp['err'] == 'Y') {
	$errp = '<img src="'.STATIK.'illus/attention.gif" alt="attention.gif" width="14" height="14">';
}

echo '<table border="0" width="95%" cellspacing="2" cellpadding="1" align="center">
	<tr bgcolor="#003333">
		<td rowspan="4" align="center" valign="middle"><h1>'.$pay->infp['codepeople'].' - '.@$errp.' <a style="color: #FFF;" href="'.NIVO.'/data/people/adminpeople.php?act=show&idpeople='.$pay->infp['idpeople'].'">'.$pay->infp['pprenom'].' '.$pay->infp['pnom'].'</a></h1></td>
		<td align="center">id</td>
		<td>'.$pay->infp['idpeople'].'</td>
	</tr>
	<tr bgcolor="#003333">
		<td align="center">Jours </td>
		<td>'.$pay->infp['nbjours'].'</td>
	</tr>
	<tr bgcolor="#003333">
		<td align="center">Brut</td>
		<td>'.feuro($pay->tarifb).'</td>
	</tr>
	<tr bgcolor="#003333">
		<td align="center">Pay</td>
		<td>'.$pay->paiement.'</td>
	</tr>
</table><br>';

################################################
#### ID Adjacents							####
################################################

## NExt ID people
$np = $DB->getRow("SELECT
		s.idpeople, p.codepeople, p.pnom, p.pprenom
	FROM grps.".$_SESSION['table']." s
		LEFT JOIN neuro.people p ON s.idpeople = p.idpeople
	GROUP BY s.idpeople
	HAVING p.codepeople > ".$pay->reg."
	ORDER BY p.codepeople
	LIMIT 1");


$nxtp = $np['idpeople'];
$nxtn = $np['pprenom'].' '.$np['pnom'];

## Prev ID people
$pp = $DB->getRow("SELECT
		s.idpeople, p.codepeople, p.pnom, p.pprenom
	FROM grps.".$_SESSION['table']." s
		LEFT JOIN neuro.people p ON s.idpeople = p.idpeople
	GROUP BY s.idpeople
	HAVING p.codepeople < ".$pay->reg."
	ORDER BY p.codepeople DESC
	LIMIT 1");

$prvp = $pp['idpeople'];
$prvn = $pp['pprenom'].' '.$pp['pnom'];

################################################
#### Liste des Jours de boulot avec totaux	####
################################################
$ptable = paytable($_REQUEST['idpeople'], $pay->datein, $pay->dateout);
$dtable = array_shift($ptable);

?>
<fieldset>
	<legend>Payements de <?php echo nomtable($_SESSION['table']);?></legend>
		<table border="0" width="95%" cellspacing="1" cellpadding="0" align="center">
			<tr>
				<td colspan="5"></td>
				<td bgcolor="#FFFF00" align="center"><font color="#000000">100%</font></td>
				<td bgcolor="#CC9900" align="center"><font color="#000000">150%</font></td>
				<td bgcolor="#CC6600" align="center"><font color="#000000">200%</font></td>
				<td colspan="5"></td>
				<td style="text-align: center; background-color: #FFB267;"><a style="color: #333333; text-decoration: none;" href="<?php echo NIVO.'merch/routing.php?act=show&idp='.$_REQUEST['idpeople'].'&din='.$pay->datein.'&dout='.$pay->dateout; ?>">Routing</a></td>
				<td colspan="4"></td>
			</tr>
			<tr bgcolor="#006633">
				<th>Date</th>
				<th colspan="4">Heures</th>
				<th colspan="3">Tarification</th>
				<th colspan="3">Catering</th>
				<th colspan="3">D&eacute;pla.</th>
				<th colspan="3">Frais</th>
				<th></th>
			</tr>
<?php
foreach ($dtable as $value) {
		setlocale(LC_TIME, 'fr_FR');
		$ladate = strftime("%a %d", strtotime($value['date']));

		    if ($_GET['date'] == $value['date']) $fonddim = ' bgcolor="#333"';		 # Highlight si détail de la date activé
		elseif (substr($ladate, 0, 3) == 'Dim')  $fonddim = ' bgcolor="#009966"'; # Highlight si Dimanche
		else 									 $fonddim = '';
		# fetch infos
		$infs = $DB->getRow("SELECT `idsalaire`, `modh`, `modh150`, `modh200`, `mod433`, `mod437`, `mod441`
		FROM grps.".$_SESSION['table']." WHERE `idpeople` = ".$_REQUEST['idpeople']." AND `date` = '".$value['date']."'");

		if (empty($infs['idsalaire'])) {
			$DB->inline("INSERT INTO grps.".$_SESSION['table']." (`idpeople`, `date`, `modh150`, `modh200`) VALUES ('".$_REQUEST['idpeople']."', '".$value['date']."', '".$value['h150']."', '".$value['h200']."')");

			$infs = $DB->getRow("SELECT `idsalaire`, `modh`, `modh150`, `modh200`, `mod433`, `mod437`, `mod441`
			FROM grps.".$_SESSION['table']." WHERE `idpeople` = ".$_REQUEST['idpeople']." AND `date` = '".$value['date']."'");
		}

		#># Heure de repas
		if (($value['heures'] >= Conf::read('Payement.maxheure')) and ($value['date'] < '2010-02-01')) {
			$heuresfact = $value['heures'];
			if ($infs['modh200'] != 0) $infs['modh200'] -= 1;
			$value['heures'] -= 1;
			$value['rp'] = '<img src="'.STATIK.'illus/repas.gif" alt="" width="16" height="13" border="0" align="middle">';
			$value['frais433'] += Conf::read('Payement.prixrepas');
		} else {
			$heuresfact = $value['heures'];
		}
		#<# Heure de repas

		### totaux
		$totheures = $value['heures']   + $infs['modh'];
		$totf433   = $value['frais433'] + $infs['mod433'];
		$totf437   = $value['frais437'] + $infs['mod437'];
		$totf441   = $value['frais441'] + $infs['mod441'];

		$modh100 = $totheures - $infs['modh150'] - $infs['modh200'];

		if ($infs['modh']    != 0) $modh    = $infs['modh'];
		if ($infs['modh150'] != 0) $modh150 = $infs['modh150'];
		if ($infs['modh200'] != 0) $modh200 = $infs['modh200'];
		if ($infs['mod433']  != 0) $mod433  = $infs['mod433'];
		if ($infs['mod437']  != 0) $mod437  = $infs['mod437'];
		if ($infs['mod441']  != 0) $mod441  = $infs['mod441'];

		if ($modh100 > 9) {$troph = ' bgcolor="#990000"';}
		else {if ($totheures > 9) {$troph = ' bgcolor="#194D4D"';} else {$troph = ' bgcolor="#006666"';}}

		echo '
		<form action="?act=updatePeople&idpeople='.$_REQUEST['idpeople'].'&skip='.$_REQUEST['skip'].'" method="post">
			<tr align="center"'.$fonddim.'>
				<td><a href="?act=showPeople&idpeople='.$_REQUEST['idpeople'].'&skip='.$_REQUEST['skip'].'&date='.$value['date'].'">'.$ladate.'</a></td>
				<td>'.fnbr($heuresfact).'</td>
				<td>'.$value['rp'].'</td>
				<td><input type="text" name="modh" size="8" value="'.fnbr($modh).'"></td>
				<td'.$troph.'>'.$totheures.'</td>
				<td bgcolor="#FFFF00"><font color="#000000">'.fnbr($modh100).'</font></td>
				<td bgcolor="#CC9900"><input type="text" name="modh150" size="4" value="'.fnbr($modh150).'"></td>
				<td bgcolor="#CC6600"><input type="text" name="modh200" size="4" value="'.fnbr($modh200).'"></td>
				<td>'.feuro($value['frais433']).'</td>
				<td><input type="text" name="mod433" size="8" value="'.fnbr($mod433).'"></td>
				<td bgcolor="#006666">'.feuro($totf433).'</td>
				<td>'.feuro($value['frais437']).'</td>
				<td><input type="text" name="mod437" size="8" value="'.fnbr($mod437).'"></td>
				<td bgcolor="#006666">'.feuro($totf437).'</td>
				<td>'.feuro($value['frais441']).'</td>
				<td><input type="text" name="mod441" size="8" value="'.fnbr($mod441).'"></td>
				<td bgcolor="#006666">'.feuro($totf441).'</td>
				<td>
					<input type="hidden" name="idsalaire" value="'.$infs['idsalaire'].'">
					<input type="submit" name="submit" value="M">
				</td>
			</tr>
		</form>
		';

		$ttheures += $totheures;
		$ttfr433  += $totf433;
		$ttfr437  += $totf437;
		$ttfr441  += $totf441;
		$tth100   += $modh100;
		$tth150   += $modh150;
		$tth200   += $modh200;

		$ttmodh += $infs['modh'];

		unset($totheures);
		unset($totf433);
		unset($totf437);
		unset($totf441);

		unset($modh);
		unset($modh100);
		unset($modh150);
		unset($modh200);
		unset($mod433);
		unset($mod437);
		unset($mod441);
		unset($troph);
		unset($fonddim);
		unset($heuresfact);
	}

$montantheures = ($tth100 + ($tth150 * 1.5) + ($tth200 * 2)) * $pay->tarifb;
$fulltotal = $montantheures + $ttfr433 + $ttfr437 + $ttfr441;

	echo '
			<tr bgcolor="#006633">
				<th>Totaux</th>
				<th></th>
				<th></th>
				<th>'.$ttmodh.'</th>
				<th>'.$ttheures.'</th>
				<th>'.$tth100.'</th>
				<th>'.$tth150.'</th>
				<th>'.$tth200.'</th>
				<th></th>
				<th></th>
				<th>'.feuro($ttfr433).'</th>
				<th></th>
				<th></th>
				<th>'.feuro($ttfr437).'</th>
				<th></th>
				<th></th>
				<th>'.feuro($ttfr441).'</th>
				<th></th>
			</tr>
			<tr>
				<td colspan="18"><font size="1">&nbsp;</font></td>
			</tr>
			<tr align="right">
				<th>Tot</th>

				<th colspan="2" bgcolor="#FF0000" align="center">'.feuro($fulltotal).'</th>
				<th colspan="2">Presta:</th>
				<th bgcolor="#006633" colspan="3" align="center">'.feuro($montantheures).'</th>
				<th colspan="2">Catering</th>
				<th bgcolor="#006633" align="center">'.feuro($ttfr433).'</th>
				<th colspan="2">D&eacute;pla.</th>
				<th bgcolor="#006633" align="center">'.feuro($ttfr437).'</th>
				<th colspan="2">Frais</th>
				<th bgcolor="#006633" align="center">'.feuro($ttfr441).'</th>
				<th></th>
			</tr>
			';
			?>

		</table>
</fieldset>

<?php
################################################ ##
#>># Détail des missions sur 1 Jour			####   ##
################################################ ##
if (!empty($_GET['date'])) {

if ((substr($_SESSION['table'], -5, 1) == 'a') or (substr($_SESSION['table'], -5, 1) == 's') or (substr($_SESSION['table'], -5, 1) == 'r')) {
	############# ANIM #######################
	$anim = $DB->getArray("SELECT
			a.idanimation, a.genre, a.facnum, a.hin1,
			c.societe,
			s.societe AS ssociete, s.ville
		FROM animation a
		LEFT JOIN animjob j ON a.idanimjob = j.idanimjob
		LEFT JOIN client c ON j.idclient = c.idclient
		LEFT JOIN shop s ON a.idshop = s.idshop
		WHERE a.idpeople = ".$pay->infp['idpeople']." AND a.datem = '".$_GET['date']."' ORDER BY a.hin1 ASC");
}

if ((substr($_SESSION['table'], -5, 1) == 'v') or (substr($_SESSION['table'], -5, 1) == 's') or (substr($_SESSION['table'], -5, 1) == 'r')) {
	############# VIP #######################
	$vip = $DB->getArray("SELECT
			v.idvip, v.idvipjob, v.facnum,
			j.reference,
			c.societe,
			s.societe AS ssociete, s.ville
		FROM vipmission v
		LEFT JOIN vipjob j ON v.idvipjob = j.idvipjob
		LEFT JOIN client c ON j.idclient = c.idclient
		LEFT JOIN shop s ON v.idshop = s.idshop
		WHERE v.idpeople = ".$pay->infp['idpeople']." AND v.vipdate = '".$_GET['date']."' ORDER BY v.vipin ASC");
}

if ((substr($_SESSION['table'], -5, 1) == 'm') or (substr($_SESSION['table'], -5, 1) == 's') or (substr($_SESSION['table'], -5, 1) == 'r')) {
	############# MERCH #######################
	$merch = $DB->getArray("SELECT
			m.idmerch, m.facnum, m.genre, m.hin1,
			c.societe,
			s.societe AS ssociete, s.ville
		FROM merch m
		LEFT JOIN client c ON m.idclient = c.idclient
		LEFT JOIN shop s ON m.idshop = s.idshop
		WHERE m.idpeople = ".$pay->infp['idpeople']." AND m.datem = '".$_GET['date']."' AND m.genre != 'EAS' ORDER BY m.hin1 ASC");
}

if ((substr($_SESSION['table'], -5, 1) == 'e') or (substr($_SESSION['table'], -5, 1) == 's') or (substr($_SESSION['table'], -5, 1) == 'r')) {
	############# EAS #######################
	$eas = $DB->getArray("SELECT
			m.idmerch, m.facnum, m.genre, m.hin1,
			c.societe,
			s.societe AS ssociete, s.ville
		FROM merch m
		LEFT JOIN client c ON m.idclient = c.idclient
		LEFT JOIN shop s ON m.idshop = s.idshop
		WHERE m.idpeople = ".$pay->infp['idpeople']." AND m.datem = '".$_GET['date']."' AND m.genre = 'EAS' ORDER BY m.hin1 ASC");
}
	?>
<fieldset>
<legend>D&eacute;tail du <?php echo  implode('/',array_reverse(explode('-',$_GET['date'])));?></legend>
		<table border="0" width="95%" cellspacing="1" cellpadding="0" align="center">
			<tr bgcolor="#333">
				<td>Mission</td>
				<td>Facture</td>
				<td>Type/Job</td>
				<td>Client</td>
				<td>Place</td>
				<td>Prestation</td>
				<td>Catering</td>
				<td>D&eacute;pla</td>
				<td>Frais</td>
			</tr>
	<?php
		##### ANIM #######
	if (count($anim) > 0) {
		foreach ($anim as $infm) {
			$zanim = new coreanim($infm['idanimation']);

			echo '
			<tr bgcolor="#336699">
				<td align="center"><a href="'.NIVO.'animation2/adminanim.php?act=showmission&idanimation='.$infm['idanimation'].'">AN '.$infm['idanimation'].'</a></td>
				<td align="center"><a href="'.NIVO.'admin/factures/adminfac.php?act=detail&idfac='.$infm['facnum'].'">'.$infm['facnum'].'</a></td>
				<td>'.$infm['genre'].'</td>
				<td>'.$infm['societe'].'</td>
				<td>'.$infm['ssociete'].' '.$infm['ville'].'</td>
				<td align="center">'.$zanim->hprest.'</td>
				<td align="center">'.feuro($zanim->frais433).'</td>
				<td align="center">'.feuro($zanim->frais437).'</td>
				<td align="center">'.feuro($zanim->frais441).'</td>
			</tr>
			';

			unset ($zanim);
		}
	}

	if (count($vip) > 0) {
		##### VIP #######
		foreach ($vip as $infv) {
			$fich = new corevip ($infv['idvip']);

			echo '
			<tr bgcolor="#666699">
				<td align="center"><a href="'.NIVO.'vip/adminvip.php?act=showmission&idvip='.$infv['idvip'].'">VI '.$infv['idvip'].'</a></td>
				<td align="center"><a href="'.NIVO.'admin/factures/adminfac.php?act=detail&idfac='.$infv['facnum'].'">'.$infv['facnum'].'</a></td>
				<td>'.$infv['reference'].'</td>
				<td>'.$infv['societe'].'</td>
				<td>'.$infv['ssociete'].' '.$infv['ville'].'</td>
				<td align="center">'.$fich->thpaye.'</td>
				<td align="center">'.feuro($fich->frais433).'</td>
				<td align="center">'.feuro($fich->frais437).'</td>
				<td align="center">'.feuro($fich->frais441).'</td>
			</tr>
			';

			unset ($fich);
		}
	}

	if (count($merch) > 0) {
		##### MERCH #######
		foreach ($merch as $infm) {
			$zmerch = new coremerch($infm['idmerch']);

			echo '
			<tr bgcolor="#336699">
				<td align="center"><a href="'.NIVO.'merch/adminmerch.php?act=show&act2=listing&idmerch='.$infm['idmerch'].'">ME '.$infm['idmerch'].'</a></td>
				<td align="center"><a href="'.NIVO.'admin/factures/adminfac.php?act=detail&idfac='.$infm['facnum'].'">'.$infm['facnum'].'</a></td>
				<td>'.$infm['genre'].'</td>
				<td>'.$infm['societe'].'</td>
				<td>'.$infm['ssociete'].' '.$infm['ville'].'</td>
				<td align="center">'.$zmerch->hprest.'</td>
				<td align="center">'.feuro($zmerch->frais433).'</td>
				<td align="center">'.feuro($zmerch->frais437).'</td>
				<td align="center">'.feuro($zmerch->frais441).'</td>
			</tr>
			';

			unset ($zmerch);
		}
	}

	if (count($eas) > 0) {
		##### EAS #######
		foreach ($eas as $infe) {
			$zmerch = new coremerch($infe['idmerch']);

			echo '
			<tr bgcolor="#336699">
				<td align="center"><a href="'.NIVO.'merch/adminmerch.php?act=show&act2=listing&idmerch='.$infe['idmerch'].'">EAS '.$infe['idmerch'].'</a></td>
				<td align="center"><a href="'.NIVO.'admin/factures/adminfac.php?act=detail&idfac='.$infe['facnum'].'">'.$infe['facnum'].'</a></td>
				<td>'.$infe['genre'].'</td>
				<td>'.$infe['societe'].'</td>
				<td>'.$infe['ssociete'].' '.$infe['ville'].'</td>
				<td align="center">'.$zmerch->hprest.'</td>
				<td align="center">'.feuro($zmerch->frais433).'</td>
				<td align="center">'.feuro($zmerch->frais437).'</td>
				<td align="center">'.feuro($zmerch->frais441).'</td>
			</tr>
			';

			unset ($zmerch);
		}
	}
	?>
		</table>
</fieldset>

	<?php
	############### < # Tableau ANIM #######################
}
################################################   ##
#<<# Détail des missions sur 1 Jour			#### ##
################################################   ##
?>
</div>

<div id="infobouton">
	<table border="0" width="95%" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td width="200" align="left"><?php echo ((!empty($prvp))?'<a href="?act=showPeople&idpeople='.$prvp.'&skip='.$_REQUEST['skip'].'"><img src="'.STATIK.'illus/NAVprv.gif" alt="NAVprv.gif" width="13" height="15" border="0" align="left"></a>&nbsp;'.$prvn:'') ?></td>
			<td><form action="?act=skip&skip=<?php echo $_REQUEST['skip']; ?>" method="post"><input type="submit" name="Retour Liste" value="Retour Liste"></form></td>
			<td width="200" align="right"><?php echo ((!empty($nxtp))?'<a href="?act=showPeople&idpeople='.$nxtp.'&skip='.$_REQUEST['skip'].'"><img src="'.STATIK.'illus/NAVnxt.gif" alt="NAVnxt.gif" width="13" height="15" border="0" align="right"></a>'.$nxtn.'&nbsp;':'') ?></td>
		</tr>
	</table>
</div>