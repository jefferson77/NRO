<?php

$quid=(!empty($_SESSION['quid']))?$_SESSION['quid']:null;
$sort=(!empty($_SESSION['sort']))?$_SESSION['sort']:null;

# VARIABLE skip
$skip=($_GET['skip']>0)?$_GET['skip']:0;
$skipprev=$skip-20;
$skipnext=$skip+20;

if(empty($quid)) {
	# VARIABLE SELECT

	$searchfields = array (
		'p.pnom' 			=> 'pnom',
		'p.pprenom' 		=> 'pprenom',
		'p.codepeople' 		=> 'codepeople',
		'p.idpeople' 		=> 'idpeople',
		'p.notegenerale' 	=> 'notegenerale',
		'p.sexe' 			=> 'sexe',
		'p.lbureau' 		=> 'lbureau',
		'p.gsm' 			=> 'gsm',
		'p.email' 			=> 'email',
		'p.province' 		=> 'province',
		'p.voiture' 		=> 'voiture',
		'p.permis' 			=> 'permis',
		'p.err' 			=> 'err'
	);
	$quid = $people->MAKEsearch($searchfields);

	## langues

	if (!empty($_POST['lfr'])) {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.lfr >= ".$_POST['lfr'];
	}
	if (!empty($_POST['lnl'])) {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.lnl >= ".$_POST['lnl'];
	}
	if (!empty($_POST['len'])) {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.len >= ".$_POST['len'];
	}
	if ($_POST['indep'] == "n") {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.idsupplier = 0 ";
	}
	else if($_POST['indep']=="y")
	{
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.idsupplier > 0 ";
	}
	if (!empty($_POST['ldu'])) {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.ldu >= ".$_POST['ldu'];
	}
	if (!empty($_POST['lit'])) {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.lit >= ".$_POST['lit'];
	}
	if (!empty($_POST['lsp'])) {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.lsp >= ".$_POST['lsp'];
	}

	## fourchette code postaux
	if ($_POST['cp1a'] != '') {
		if ($_POST['cp1b'] == '') { $_POST['cp1b'] = $_POST['cp1a']; }
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "((p.cp1 >= ".$_POST['cp1a']." AND p.cp1 <= ".$_POST['cp1b'].") OR (p.cp2 >= ".$_POST['cp1a']." AND p.cp2 <= ".$_POST['cp1b']."))";
	}

	if ($_POST['ville1'] != '') {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "(";
		$quid .= "p.ville1 LIKE '%".$_POST['ville1']."%'";
		$quid .= " OR ";
		$quid .= "p.ville2 LIKE '%".$_POST['ville2']."%'";
		$quid .= ")";
	}

	if ($_POST['notemerch'] == 'yes') {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.notemerch != ''";
	}
	if (!empty($_POST['categorie'])) {
		$searchstr = ($_POST['categorie'][0]=='1')?'1':'_';
		$searchstr.=($_POST['categorie'][1]=='1')?'1':'_';
		$searchstr.=($_POST['categorie'][2]=='1')?'1':'_';
		if (!empty($quid)) $quid .= " AND ";
		$quid = $quid."p.categorie LIKE '".$searchstr."'";
	}
	if ($_POST['out'] == 'out') {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.isout LIKE 'out'";
	}

	if ($_POST['out'] == 'notout') {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.isout NOT LIKE '%out%'";
	}

	if (!empty($_POST['age'])) {
		$searchage = $DB->quidage($_POST['age'], 'p.ndate');

		if (!empty($searchage)) {
			if (!empty($quid)) $quid .= " AND ";
			$quid .= $searchage;
		}
	}

	if(!empty($_POST['rayon'])) {
		$rayon = $_POST['rayon'];	// Rayon en km
		$coeff = array(
			 '5' => '1.40',
			'10' => '1.35',
			'20' => '1.42',
			'30' => '1.34',
			'40' => '1.30',
			'50' => '1.42',
			'75' => '1.29',
		   '100' => '1.28');
		##### Chercher le bon coefficient dans l'array #####
		foreach($coeff as $k => $v) {
			if($rayon >= $k) {
				$x = $v;
			}
		}
		$rayon = $rayon / $x;

		$dAddLat = $geo->getLatPerKm() * $rayon;
		$dAddLon = $geo->getLonPerKmAtLat($datea['glat']) * $rayon;
		$dNorthBounds = $datea['glat'] + $dAddLat;
		$dSouthBounds = $datea['glat'] - $dAddLat;
		$dWestBounds = $datea['glong'] - $dAddLon;
		$dEastBounds = $datea['glong'] + $dAddLon;

		if (!empty($quid)) $quid .= " AND ";
		$quid .= " IF(p.peoplehome=1,p.glat1,p.glat2) BETWEEN ".$dSouthBounds." AND ".$dNorthBounds." AND
				  IF(p.peoplehome=1,p.glong1,p.glong2) BETWEEN ".$dWestBounds." AND ".$dEastBounds;
	}
	if (!empty($quid)) {$quid='WHERE '.$quid;}
}

if ($sort == '') { $sort='disp DESC, idpeople DESC'; }
$recherche = "
	SELECT p.codepeople, p.idpeople, p.pnom, p.pprenom, p.sexe, p.lfr, p.lnl, p.len, p.glat1, p.idsupplier,
	p.cp1, p.ville1, p.cp2, p.ville2, p.gsm, p.isout, p.err, p.vacin, p.vacout,
	IF(p.peoplehome=1,p.glat1,p.glat2) AS pglat, IF(p.peoplehome=1,p.glong1,p.glong2) AS pglong,
	d.".$dd." AS disp
	FROM people p
	LEFT JOIN peoplevac v ON v.idpeople = p.idpeople AND v.etat LIKE 'in' AND '".$datea['datem']."' BETWEEN v.vacin AND v.vacout
	LEFT JOIN disponib d  ON p.idpeople = d.idboy AND d.annee = '".$mm[0]."' AND d.mois = '".$mm[1]."'
	".$quid." AND v.idpeople IS NULL
	 GROUP BY p.idpeople
	 ORDER BY ".$sort."
	 LIMIT ".$skip.", 20
";

$_SESSION['quid'] = $quid;
$_SESSION['sort'] = $sort;

$people->inline($recherche);

?>
<fieldset>
<legend>
	Recherche d'un People :
	<br/>
	<?php echo $quid; ?>
</legend>
<table width="98%" cellspacing="1" cellpadding="1" border="0" align="center">
<tr>
<td align="left"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=selectpeople&down='.$down.'&idanimation='.$idanimation.'&idanimjob='.$idanimjob.'&sel=people';?>">Retour &agrave; la recherche</a></td>
<td align="center">
	<form action="<?php echo NIVO."mod/sms/adminsms.php?act=show";?>" target="centerzonelarge" method="post">
		<input type="hidden" name="query" value="<?php echo $_SESSION['quid']; ?>">
		<input type="hidden" name="secteur" value="AN">
		SMS: <input type="submit" class="btn phone" name="send" value="sms" title="Envoyer un SMS group&eacute;">
	</form>
	 </td>
<tr>
</table>
<?php
if ($_GET['contact'] == 'yes') {
	### RECHERCHE Contact peopler
		$sqlcontact1 = "SELECT
		c.notecontact, c.etatcontact,
		p.pprenom, p.pnom, p.codepeople, p.idpeople
		FROM jobcontact c
		LEFT JOIN people p ON c.idpeople = p.idpeople
		LEFT JOIN agent a ON c.idagent = a.idagent
		WHERE c.idanimation = ".$idanimjob." AND c.idpeople = ".$_GET['idpeople'];

		$detailcontact1 = new db();
		$detailcontact1->inline($sqlcontact1);
		$rowcontact1 = mysql_fetch_array($detailcontact1->result) ;
	#/## RECHERCHE Contact people
	?>
	<form action="<?php echo $_SERVER['PHP_SELF'].'?idanimation='.$idanimation.'&idanimjob='.$idanimjob.'&act=selectpeople&down='.$down.'&sel=people&etape=listepeople&action=skip&skip='.$skip.'&contact=modif&idpeople='.$rowcontact1['idpeople']; ?>" method="post">
	<input type="hidden" name="idanimation" value="<?php echo $idanimation ;?>">
	<input type="hidden" name="idanimjob" value="<?php echo $idanimjob ;?>">
	<input type="hidden" name="idpeople" value="<?php echo $_GET['idpeople'] ;?>">
	<table border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<td class="<?php echo $classe; ?>">
				<?php echo $rowcontact1['codepeople'].' - '.$rowcontact1['idpeople']; ?> <?php echo $rowcontact1['pnom']; ?> <?php echo $rowcontact1['pprenom']; ?>
			</td>
			<td class="<?php echo $classe; ?>">
				<input type="radio" name="etatcontact" value="0" <?php if (($rowcontact1['etatcontact'] == '0') or ($rowcontact['etatcontact'] == '')) { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-grey.gif" border="0" alt="phone-grey.gif" width="15" height="15">'; ?> N&eacute;ant
				<input type="radio" name="etatcontact" value="10" <?php if ($rowcontact1['etatcontact'] == '10') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-blue.gif" border="0" alt="phone-blue.gif" width="15" height="15">'; ?> Pas de r&eacute;p
				<input type="radio" name="etatcontact" value="20" <?php if ($rowcontact1['etatcontact'] == '20') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-red.gif" border="0" alt="phone-red.gif" width="15" height="15">'; ?> Non dispo
				<input type="radio" name="etatcontact" value="30" <?php if ($rowcontact1['etatcontact'] == '30') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-orange.gif" border="0" alt="phone-green.gif" width="15" height="15">'; ?> Message
				<input type="radio" name="etatcontact" value="40" <?php if ($rowcontact1['etatcontact'] == '40') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-green.gif" border="0" alt="phone-green.gif" width="15" height="15">'; ?> Ok
			</td>
			<td class="<?php echo $classe; ?>">
				Notes : <input type="text" name="notecontact" value="<?php echo $rowcontact1['notecontact']; ?>" size="25">
			</td>
			<td class="<?php echo $classe; ?>" align="right">
				<input type="submit" name="ok" value="ok">
			</td>
		</tr>
	</table>
</form>
<?php
}
?>

<table class="<?php echo $classe; ?>" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
	<tr>

		<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?idanimation='.$idanimation.'&idanimjob='.$idanimjob.'&act=selectpeople&down='.$down.'&sel=people&etape=listepeople&action=skip&skip='.$skipprev; ?>"><img src="<?php echo STATIK ?>illus/avant.gif" alt="search" width="20" height="20" border="0"></a></td>
		<td class="<?php echo $classe; ?>" align="center"><?php echo $skip +1;?> - <?php echo $skip + 20;?></td>
		<td class="<?php echo $classe; ?>" align="right"><a href="<?php echo $_SERVER['PHP_SELF'].'?idanimation='.$idanimation.'&idanimjob='.$idanimjob.'&act=selectpeople&down='.$down.'&sel=people&etape=listepeople&action=skip&skip='.$skipnext; ?>"><img src="<?php echo STATIK ?>illus/apres.gif" alt="search" width="20" height="20" border="0"></a></td>
	</tr>
</table>

<table class="standard" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
	<tr>
		<th class="<?php echo $classe; ?>"></th>
		<th class="<?php echo $classe; ?>">Code</th>
		<th class="<?php echo $classe; ?>">id</th>
		<th class="<?php echo $classe; ?>">D</th>
		<th class="<?php echo $classe; ?>">Nom</th>
		<th class="<?php echo $classe; ?>">Pr&eacute;nom</th>
		<th class="<?php echo $classe; ?>"></th>
		<th class="<?php echo $classe; ?>">Sexe</th>
		<th class="<?php echo $classe; ?>">Fr</th>
		<th class="<?php echo $classe; ?>">NL</th>
		<th class="<?php echo $classe; ?>">KM</th>
		<th class="<?php echo $classe; ?>">CP</th>
		<th class="<?php echo $classe; ?>">Ville</th>
		<th class="<?php echo $classe; ?>">CP2</th>
		<th class="<?php echo $classe; ?>">Ville2</th>
		<th class="<?php echo $classe; ?>">GSM</th>
		<th class="<?php echo $classe; ?>"></th>
	</tr>
<?php
while ($row = mysql_fetch_array($people->result)) {

	//ICI

	if($datea['datem']<$row['vacin'] or $datea['datem']>$row['vacout']){


		//ICI

	### RECHERCHE Contact people
		$sqlcontact = "SELECT
		c.notecontact, c.etatcontact
		FROM jobcontact c
		WHERE c.idanimation = ".$idanimjob." AND c.idpeople = ".$row['idpeople'];
		$detailcontact = new db();
		$detailcontact->inline($sqlcontact);
		$rowcontact = mysql_fetch_array($detailcontact->result) ;
		#		echo $detailcontact ;
	#/## RECHERCHE Contact people

	$calchh = new hh();
	$dds = explode("-", $datea['datem']);

	if (date("w", mktime(0, 0, 0, $dds[1], $dds[2], $dds[0])) == 0) {
		$lastweek = date("Y-m-d", mktime(0, 0, 0, $dds[1], $dds[2] - 7, $dds[0]));

		$worktable = $calchh->hhtable($row['idpeople'], $lastweek, $datea['datem']);
		if (array_sum(preg_split('//',$worktable[$lastweek],-1,PREG_SPLIT_NO_EMPTY)) > 0) { $ddillu = '<img src="'.STATIK.'illus/ddim.gif" alt="dim" width="7" height="13">'; } else { $ddillu = ''; }
	} else {
		$worktable = $calchh->hhtable($row['idpeople'], $datea['datem'], $datea['datem']);
	}
#> Changement de couleur des lignes #####>>####
$i++;

if (fmod($i, 2) == 1) {
echo '<tr bgcolor="#9CBECA">';
} else {
echo '<tr bgcolor="#8CAAB5">';
}

#< Changement de couleur des lignes #####<<####
if($row['idsupplier']>0)
{
	$color = 'bgcolor="#FF6600"';#FF6600
}
else
{
	$color = '';
}
## Geoloc
if ($row['glat1'] > 0) {
echo '<td '.$color.'><img src="'.STATIK.'illus/geoloc.png" alt="geoloc.png" width="16" height="15" align="right"></td>';
} else {
echo '<td '.$color.'></td>';
}
?>
		<td <?php echo $color ?>><?php echo $row['codepeople']; ?></td>
		<td <?php echo $color ?>><?php echo $row['idpeople']; ?></td>
		<td <?php echo $color ?>><?php echo $ddillu; if ((!empty($row['disp'])) or (array_sum(preg_split('//',$worktable[$datea['datem']],-1,PREG_SPLIT_NO_EMPTY)) > 0)) {echo '<img src="/data/people/dispo/dillu.php?disp='.$row['disp'].'&hhcode='.$worktable[$datea['datem']].'" border="1" alt="1.gif" width="8" height="11">';} ?></td>
		<td <?php echo $color ?>><?php echo $row['pnom']; ?></td>
		<td <?php echo $color ?>><?php echo $row['pprenom']; ?></td>
		<td <?php echo $color ?>>
			<a href="<?php echo NIVO.'data/people/adminpeople.php?act=show&idpeople='.$row['idpeople'];?>" target="_blank"><img src="<?php echo STATIK ?>illus/icon_profile.gif" border="0" width="15" height="12"></a>
			<?php ### CONTACT people ?>
			<a href="<?php echo $_SERVER['PHP_SELF'].'?idanimation='.$idanimation.'&idanimjob='.$idanimjob.'&act=selectpeople&down='.$down.'&sel=people&etape=listepeople&action=skip&skip='.$skip.'&contact=yes&idpeople='.$row['idpeople']; ?>">
				<?php if (($rowcontact['etatcontact'] == '0') or ($rowcontact['etatcontact'] == '')) {echo '<img src="'.STATIK.'illus/phone-grey.gif" border="0" alt="phone-grey.gif" width="15" height="15">';} ?>
				<?php if ($rowcontact['etatcontact'] == '10') {echo '<img src="'.STATIK.'illus/phone-blue.gif" border="0" alt="phone-green.gif" width="15" height="15">';} ?>
				<?php if ($rowcontact['etatcontact'] == '20') {echo '<img src="'.STATIK.'illus/phone-red.gif" border="0" alt="phone-green.gif" width="15" height="15">';} ?>
				<?php if ($rowcontact['etatcontact'] == '30') {echo '<img src="'.STATIK.'illus/phone-orange.gif" border="0" alt="phone-green.gif" width="15" height="15">';} ?>
				<?php if ($rowcontact['etatcontact'] == '40') {echo '<img src="'.STATIK.'illus/phone-green.gif" border="0" alt="phone-green.gif" width="15" height="15">';} ?>
			</a>
			<?php #/## CONTACT people ?>
		</td>
		<td <?php echo $color ?>><?php echo $row['sexe']; ?></td>
		<td <?php echo $color ?>><?php echo $row['lfr']; ?></td>
		<td <?php echo $color ?>><?php echo $row['lnl']; ?></td>
		<td <?php echo $color ?>>
			<?php
			if($datea['glat']>0 && $datea['glong']>0) {
				if($row['pglat'] > 0 && $row['pglong'] > 0) {
					$coeff = array(5 => 1.40, 10 => 1.35, 20 => 1.42, 30 => 1.34, 40 => 1.30, 50 => 1.42, 75 => 1.29, 100 => 1.28);
					$dist = round($geo->EllipsoidDistance($datea['glat'], $datea['glong'], $row['pglat'], $row['pglong']));
					##### Chercher le bon coefficient dans l'array #####
					foreach($coeff as $k => $v) {
						if($dist >= $k) {
							$x = $v;
						}
					}
					$dist = $dist * $x;
					echo round($dist);
				} else echo '-';
			} else echo '!shop';
		 ?>

		</td>
		<td <?php echo $color ?>><?php echo $row['cp1']; ?></td>
		<td <?php echo $color ?>><?php echo $row['ville1']; ?></td>
		<td <?php echo $color ?>><?php echo $row['cp2']; ?></td>
		<td <?php echo $color ?>><?php echo $row['ville2']; ?></td>
		<td <?php echo $color ?>><?php echo $row['gsm']; ?></td>
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modifpeople&down='.$down.'&etat=1';?>" method="post">
			<input type="hidden" name="idanimation" value="<?php echo $idanimation ;?>">
			<input type="hidden" name="idanimjob" value="<?php echo $idanimjob ;?>">
			<input type="hidden" name="idpeople" value="<?php echo $row['idpeople'] ;?>">
			<td><input type="submit" class="btn afficher"></td>
		</form>
	</tr>
<?php } }?>
</table>
</fieldset>
