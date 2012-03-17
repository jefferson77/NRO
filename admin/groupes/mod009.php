<div id="leftmenu"></div>
<div id="infozone">
<?php
$skip = $_GET['skip'] ;

################################################
#### Barre d'infos people					####
################################################
$pay = new payement($_GET['idpeople'], $_GET['idptg']);

if ($pay->infp['err'] == 'Y') {
	$errp = '<img src="'.STATIK.'illus/attention.gif" alt="attention.gif" width="14" height="14">';
}

echo '<table border="0" width="95%" cellspacing="2" cellpadding="1" align="center">
	<tr bgcolor="#003333">
		<td rowspan="4" align="center" valign="middle"><h1>'.$pay->infp['codepeople'].' - '.$errp.' '.$pay->infp['pprenom'].' '.$pay->infp['pnom'].'</h1></td>
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
$nxtpeop = new db('', '', 'grps');
$nxtpeop->inline("SELECT s.idptg, s.idpeople, p.codepeople, p.pnom, p.pprenom
FROM grps.custom009 s
LEFT JOIN neuro.people p ON s.idpeople = p.idpeople
GROUP BY s.idpeople
HAVING p.codepeople > ".$pay->reg."
AND s.idptg = '".$_GET['idptg']."'
ORDER BY p.codepeople
LIMIT 1");

$np = mysql_fetch_array($nxtpeop->result);

$nxtp = $np['idpeople'];
$nxtn = $np['pprenom'].' '.$np['pnom'];

## Prev ID people
$prepeop = new db('', '', 'grps');
$prepeop->inline("SELECT s.idptg, s.idpeople, p.codepeople, p.pnom, p.pprenom
FROM grps.custom009 s
LEFT JOIN neuro.people p ON s.idpeople = p.idpeople
GROUP BY s.idpeople
HAVING p.codepeople < ".$pay->reg."
AND s.idptg = '".$_GET['idptg']."'
ORDER BY p.codepeople DESC
LIMIT 1");

$pp = mysql_fetch_array($prepeop->result);

$prvp = $pp['idpeople'];
$prvn = $pp['pprenom'].' '.$pp['pnom'];

################################################
#### Liste des Jours de boulot avec totaux	####
################################################
	$ptable = paytable ($_GET['idpeople'], $pay->datein, $pay->dateout);
	$dtable = array_shift($ptable);

	?>
<fieldset>
<legend>Payements de</legend>
		<table border="0" width="95%" cellspacing="1" cellpadding="0" align="center">
			<tr>
				<td colspan="5"></td>
				<td bgcolor="#FFFF00" align="center"><font color="#000000">100%</font></td>
				<td bgcolor="#CC9900" align="center"><font color="#000000">150%</font></td>
				<td bgcolor="#CC6600" align="center"><font color="#000000">200%</font></td>
				<td colspan="10"></td>
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

		if (substr($ladate, 0, 3) == 'Dim') $fonddim = ' bgcolor="#009966"'; # Highlight si Dimanche
		if ($_GET['date'] == $value['date']) $fonddim = ' bgcolor="#333"';		# Highlight si détail de la date activé
	
		#># Heure de repas : Plus utilisé après le 1/2/2010
		if (($value['heures'] >= Conf::read('Payement.maxheure')) and ($value['date'] < '2010-02-01')) {
			$heuresfact = $value['heures'];
			$value['heures'] -= 1;
			$value['rp'] = '<img src="'.STATIK.'illus/repas.gif" alt="" width="16" height="13" border="0" align="middle">';
			$value['frais433'] += Conf::read('Payement.prixrepas');
		} else {
			$heuresfact = $value['heures'];
		}
		#<# Heure de repas

		$infs = $DB->getRow("SELECT `modh`, `modh150`, `modh200`, `mod433`, `mod437`, `mod441` 
			FROM `custom009` WHERE `idpeople` = ".$_GET['idpeople']." AND `date` = '".$value['date']."'");

		### totaux
		$totheures = $value['heures'] + $infs['modh'];
		$totf433 = $value['frais433'] + $infs['mod433'];
		$totf437 = $value['frais437'] + $infs['mod437'];
		$totf441 = $value['frais441'] + $infs['mod441'];
		
		$modh100 = $totheures - $infs['modh150'] - $infs['modh200'];
		
		if ($infs['modh'] != 0) $modh = $infs['modh'];
		if ($infs['modh150'] != 0) $modh150 = $infs['modh150'];
		if ($infs['modh200'] != 0) $modh200 = $infs['modh200'];
		if ($infs['mod433'] != 0) $mod433 = $infs['mod433'];
		if ($infs['mod437'] != 0) $mod437 = $infs['mod437'];
		if ($infs['mod441'] != 0) $mod441 = $infs['mod441'];
		
		if ($totheures > 9) {$troph = ' bgcolor="#990000"';} else {$troph = ' bgcolor="#006666"';}
				
		echo '
		<form action="'.$_SERVER['PHP_SELF'].'?id='.$_GET['idpeople'].'&skip='.$skip.'" method="post">
			<tr align="center"'.$fonddim.'>
				<td><a href="'.$_SERVER['PHP_SELF'].'?id='.$_GET['idpeople'].'&skip='.$skip.'&date='.$value['date'].'">'.$ladate.'</a></td>
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
					<input type="hidden" name="id009" value="'.$infs['id009'].'">
					<input type="submit" name="submit" value="M">
				</td>
			</tr>
		</form>
		';
		
		$ttheures += $totheures;
		$ttfr433 += $totf433;
		$ttfr437 += $totf437;
		$ttfr441 += $totf441;
		$tth100 += $modh100;
		$tth150 += $modh150;
		$tth200 += $modh200;
		
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

	############# ANIM #######################
	$anim = new db();
	$anim->inline("SELECT 
	a.idanimation, a.genre,
	c.societe, 
	s.societe AS ssociete, s.ville
	FROM animation a
	LEFT JOIN client c ON a.idclient = c.idclient
	LEFT JOIN shop s ON a.idshop = s.idshop
	WHERE `idpeople` = ".$pay->infp['idpeople']." AND `datem` = '".$_GET['date']."' ORDER BY `hin1` ASC");
	
	############# VIP #######################
	$vip = new db();
	$vip->inline("
	SELECT v.idvip, v.idvipjob, 
	j.reference,
	c.societe, 
	s.societe AS ssociete, s.ville
	FROM vipmission v
	LEFT JOIN vipjob j ON v.idvipjob = j.idvipjob
	LEFT JOIN client c ON j.idclient = c.idclient
	LEFT JOIN shop s ON v.idshop = s.idshop
	WHERE `idpeople` = ".$pay->infp['idpeople']." AND `vipdate` = '".$_GET['date']."' ORDER BY `vipin` ASC");

	############# MERCH #######################
		### Manque la collecte des infos Merch XXX
			
	?>
<fieldset>
<legend>D&eacute;tail du <?php echo  implode('/',array_reverse(explode('-',$_GET['date'])));?></legend>
	<table border="0" width="95%" cellspacing="1" cellpadding="0" align="center">
		<tr bgcolor="#333">
			<td>Mission</td>
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
	while ($infm = mysql_fetch_array($anim->result)) {
	
		$zanim = new coreanim($infm['idanimation']);

		echo '
		<tr bgcolor="#336699">
			<td><a href="'.NIVO.'animation/adminanim.php?act=show&idanimation='.$infm['idanimation'].'">AN '.$infm['idanimation'].'</a></td>
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
	
	##### VIP #######
	while ($infv = mysql_fetch_array($vip->result)) {
	
		$fich = new corevip ($infv['idvip']);

		echo '
		<tr bgcolor="#666699">
			<td><a href="'.NIVO.'vip/adminvip.php?act=showmission&idvip='.$infv['idvip'].'">VI '.$infv['idvip'].'</a></td>
			<td></td>
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
?>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
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
		<form action="salaires.php?skip=<?php echo $skip; ?>" method="post"><tr>
			<td width="200" align="left"><a href="<?php echo $_SERVER['PHP_SELF'];?>?id=<?php echo $prvp;?>&skip=<?php echo $skip;?>"><img src="<?php echo STATIK ?>illus/NAVprv.gif" alt="NAVprv.gif" width="13" height="15" border="0" align="middle"></a>&nbsp;<?php echo $prvn;?></td>
			<td><input type="submit" name="Retour Liste" value="Retour Liste"></td>
			<td width="200" align="right"><?php echo $nxtn;?>&nbsp;<a href="<?php echo $_SERVER['PHP_SELF'];?>?id=<?php echo $nxtp;?>&skip=<?php echo $skip;?>"><img src="<?php echo STATIK ?>illus/NAVnxt.gif" alt="NAVprv.gif" width="13" height="15" border="0" align="middle"></a></td>
		</tr></form>
	</table>
</div>