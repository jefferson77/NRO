<div id="centerzonelarge">
<?php
$idmerch = $_GET['idmerch'];
$idmerchjob = $_GET['idmerchjob'];
$classe = 'standard' ;

### Deuxième étape : Afficher la liste des People correspondant à la recherche
# VARIABLE skip
$skip =($_GET['skip']>0)?$_GET['skip']:0;
$skipprev=$skip-20;
$skipnext=$skip+20;

# SESSION
$sort = (!empty($_SESSION['sort']))?$_SESSION['sort']:$_GET['sort'];
$quid = (!empty($_SESSION['quid']))?$_SESSION['quid']:null;

include(NIVO.'classes/geocalc.php');
$geo = new Geocalc;
$people = new db();
### Date de la mission MERCH pour disponibilités
$datev = $DB->getRow('SELECT m.datem, s.glat, s.glong
					FROM merch m
					LEFT JOIN shop s ON m.idshop = s.idshop
					WHERE idmerch = "'.$idmerch.'"');
#echo '<pre>', print_r($datev), '</pre>';
$mm = explode("-", $datev['datem']);
$dd = 'd'.str_repeat('0', 2 - strlen($mm[2])).$mm[2];

# VARIABLE SELECT
if(empty($quid)) {
	$searchfields = array(
			'p.pnom' 			=> 'pnom',
			'p.pprenom' 		=> 'pprenom',
			'p.codepeople' 		=> 'codepeople',
			'p.idpeople'		=> 'idpeople',
			'p.notegenerale'	=> 'notegenerale',
			'p.sexe'			=> 'sexe',
			'p.lbureau'			=> 'lbureau',
			'p.gsm'				=> 'gsm',
			'p.err'				=> 'err',
			'p.permis'			=> 'permis',
			'p.voiture'			=> 'voiture',
			'p.province'		=> 'province',
			'p.email'			=> 'email',
		);

	$quid = $people->MAKEsearch($searchfields);
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
	if ($_POST['cp1a'] != '') {
		if ($_POST['cp1b'] == '') { $_POST['cp1b'] = $_POST['cp1a']; }
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "((p.cp1 >= ".$_POST['cp1a']." AND p.cp1 <= ".$_POST['cp1b'].") OR (p.cp2 >= ".$_POST['cp1a']." AND p.cp2 <= ".$_POST['cp1b']."))";
	}
	if ($_POST['ville1'] != '') {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "(p.ville1 LIKE '%".$_POST['ville1']."%' OR p.ville2 LIKE '%".$_POST['ville2']."%')";
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
	} else if ($_POST['out'] == 'notout') {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.isout NOT LIKE '%out%'";
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
		$dAddLon = $geo->getLonPerKmAtLat($datev['glat']) * $rayon;
		$dNorthBounds = $datev['glat'] + $dAddLat;
		$dSouthBounds = $datev['glat'] - $dAddLat;
		$dWestBounds = $datev['glong'] - $dAddLon;
		$dEastBounds = $datev['glong'] + $dAddLon;

		if (!empty($quid)) $quid .= " AND ";
		$quid .= " IF(p.peoplehome=1,p.glat1,p.glat2) BETWEEN ".$dSouthBounds." AND ".$dNorthBounds." AND
				  IF(p.peoplehome=1,p.glong1,p.glong2) BETWEEN ".$dWestBounds." AND ".$dEastBounds;
	}
	if (!empty($quid)) { $quid='WHERE '.$quid; }
}
if ($sort == '') { $sort='disp DESC, idpeople DESC'; }
$recherche="
	SELECT p.idpeople, p.pnom, p.pprenom, p.sexe, p.lfr, p.lnl, p.len, p.codepeople,  p.idsupplier,
	p.cp1, p.ville1, p.cp2, p.ville2, p.gsm, p.isout, p.err, p.notemerch, p.vacin, p.vacout,
	IF(p.peoplehome=1,p.glat1,p.glat2) AS pglat, IF(p.peoplehome=1,p.glong1,p.glong2) AS pglong,
	d.".$dd." AS disp
	FROM people p
	LEFT JOIN peoplevac v ON v.idpeople = p.idpeople AND v.etat LIKE 'in' AND '".$datev['datem']."' BETWEEN v.vacin AND v.vacout
	LEFT JOIN disponib d  ON p.idpeople = d.idboy AND d.annee = '".$mm[0]."' AND d.mois = '".$mm[1]."'
	".$quid." AND v.idpeople IS NULL
	 GROUP BY p.idpeople
	 ORDER BY ".$sort."
	 LIMIT ".$skip.", 20
	";

	$_SESSION['quid'] = $quid;
	$_SESSION['sort'] = $sort;


### Deuxième étape - 2 A Et 2B COMMUN : Afficher la liste des people
# Recherche des 20 people

$people->inline($recherche);
?>
<fieldset>
<legend>
Recherche d'un People : <?php #echo $_SESSION['animpeoplequod'].' &nbsp;'; $FoundCount = mysql_num_rows($people2->result); echo '('.$FoundCount.' Results)'; ?>
<br/>
</legend>

<table width="98%" cellspacing="1" cellpadding="1" border="0" align="center">
<tr>
<td align="left"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$idmerch.'&sel=people';?>">Retour &agrave; la recherche</a></td>
<td align="center">
	<form action="<?php echo NIVO."mod/sms/adminsms.php?act=show";?>" target="centerzonelarge" method="post">
	<input type="hidden" name="query" value="<?php echo $_SESSION['quid']; ?>">
	<input type="hidden" name="secteur" value="ME">
	SMS: <input type="submit" class="btn phone" name="send" value="sms" title="Envoyer un SMS group&eacute;">
	</form>
	 </td>
<tr>
</table>



<?php
if ($_GET['contact'] == 'yes') {
	### RECHERCHE Contact people
	$peoplecontact = $DB->getRow("SELECT
						p.codepeople, p.pnom, p.pprenom,
						c.etatcontact, c.notecontact
					FROM people p
						LEFT JOIN jobcontact c ON p.idpeople = c.idpeople AND c.idmerch = '".$idmerch."'
					WHERE p.idpeople = ".$_GET['idpeople']) ;

	#/## RECHERCHE Contact people
	?>
	<form action="<?php echo $_SERVER['PHP_SELF'].'?&idmerch='.$idmerch.'&act=select&sel=people&etape=listepeople&action=skip&skip='.$skip.'&contact=modif&idpeople='.$_GET['idpeople']; ?>" method="post">
	<input type="hidden" name="idmerch" value="<?php echo $idmerch ;?>">
	<input type="hidden" name="idpeople" value="<?php echo $_GET['idpeople'] ;?>">
	<table border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
	<tr>
		<td class="<?php echo $classe; ?>">
			<?php echo $peoplecontact['codepeople'].' - '.$_GET['idpeople']; ?> <?php echo $peoplecontact['pnom']; ?> <?php echo $peoplecontact['pprenom']; ?>
		</td>
		<td class="<?php echo $classe; ?>">
			<input type="radio" name="etatcontact" value="0" <?php if (($peoplecontact['etatcontact'] == '0') or ($rowcontact['etatcontact'] == '')) { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-grey.gif" border="0" alt="phone-grey.gif" width="15" height="15">'; ?> N&eacute;ant
			<input type="radio" name="etatcontact" value="10" <?php if ($peoplecontact['etatcontact'] == '10') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-blue.gif" border="0" alt="phone-blue.gif" width="15" height="15">'; ?> Pas de r&eacute;p
			<input type="radio" name="etatcontact" value="20" <?php if ($peoplecontact['etatcontact'] == '20') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-red.gif" border="0" alt="phone-red.gif" width="15" height="15">'; ?> Non dispo
			<input type="radio" name="etatcontact" value="30" <?php if ($peoplecontact['etatcontact'] == '30') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-orange.gif" border="0" alt="phone-green.gif" width="15" height="15">'; ?> Message
			<input type="radio" name="etatcontact" value="40" <?php if ($peoplecontact['etatcontact'] == '40') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-green.gif" border="0" alt="phone-green.gif" width="15" height="15">'; ?> Ok
		</td>
		<td class="<?php echo $classe; ?>">
			Notes : <input type="text" name="notecontact" value="<?php echo $peoplecontact['notecontact']; ?>" size="25">
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
		<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?idmerch='.$idmerch.'&act=select&sel=people&etape=listepeople&action=skip&skip='.$skipprev; ?>"><img src="<?php echo STATIK ?>illus/avant.gif" alt="search" width="20" height="20" border="0"></a></td>
		<td class="<?php echo $classe; ?>" align="center"><?php echo $skip;?> - <?php echo $skip+19;?></td>
		<td class="<?php echo $classe; ?>" align="right">
		<a href="<?php echo $_SERVER['PHP_SELF'].'?idmerch='.$idmerch.'&act=select&sel=people&etape=listepeople&action=skip&skip='.$skipnext; ?>"><img src="<?php echo STATIK ?>illus/apres.gif" alt="search" width="20" height="20" border="0"></a>
		</td>
	</tr>
</table>

<table class="standard" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
	<tr>
		<th class="<?php echo $classe; ?>">Code</th>
		<th class="<?php echo $classe; ?>">Id</th>
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
		### RECHERCHE Contact people
		if($datev['datem']<$row['vacin'] or $datev['datem']>$row['vacout'])
		{
		$sqlcontact = "SELECT
		c.notecontact, c.etatcontact
		FROM jobcontact c
		WHERE c.idmerch = ".$idmerch." AND c.idpeople = ".$row['idpeople'];
		$detailcontact = new db();
		$detailcontact->inline($sqlcontact);
		$rowcontact = mysql_fetch_array($detailcontact->result) ;
		#/## RECHERCHE Contact people

			if (!empty($datev['datem']) and ($datev['datem'] != '0000-00-00')) {
				$calchh = new hh();

				if (date("w", strtotime($datev['datem'])) == 0) {
					$dds = explode("-", $datev['datem']);
					$lastweek = date("Y-m-d", mktime(0, 0, 0, $dds[1], $dds[2] - 7, $dds[0]));

					$worktable = $calchh->hhtable($row['idpeople'], $lastweek, $datev['datem']);
					if (array_sum(preg_split('//',$worktable[$lastweek],-1,PREG_SPLIT_NO_EMPTY)) > 0) { $ddillu = '<img src="'.STATIK.'illus/ddim.gif" alt="dim" width="7" height="13">'; } else { $ddillu = ''; }
				} else {
					$worktable = $calchh->hhtable($row['idpeople'], $datev['datem'], $datev['datem']);
				}
			} else {
				$worktable = array();
			}
		#> Changement de couleur des lignes #####>>####
		$i++;
		if($row['idsupplier']>0)
		{
			$color = 'bgcolor="#FF6600"';#FF6600
		}
		else
		{
			$color = '';
		}
		?>
		<tr bgcolor="<?php echo (fmod($i, 2) == 1)?'#9CBECA':'#8CAAB5'; ?>">
			<td <?php echo $color ?>><b><?php echo $row['codepeople']; ?></b></td>
			<td <?php echo $color ?>><?php echo $row['idpeople']; ?></td>
			<td <?php echo $color ?>>
			<?php
				echo $ddillu;
				if ((!empty($row['disp'])) or (array_sum(preg_split('//',$worktable[$datev['datem']],-1,PREG_SPLIT_NO_EMPTY)) > 0)) {
					echo '<img src="/data/people/dispo/dillu.php?disp='.$row['disp'].'&hhcode='.$worktable[$datev['datem']].'" border="1" alt="1.gif" width="8" height="11">';
				}
			?>
			</td>
			<td <?php echo $color ?>><?php echo $row['pnom']; ?></td>
			<td <?php echo $color ?>><?php echo $row['pprenom']; ?></td>
			<td <?php echo $color ?>>
				<a href="<?php echo NIVO.'data/people/adminpeople.php?act=show&idpeople='.$row['idpeople'];?>" target="_blank"><img src="<?php echo STATIK ?>illus/icon_profile.gif" border="0" width="15" height="12"></a>
				<?php ### CONTACT people ?>
				<a href="<?php echo $_SERVER['PHP_SELF'].'?idmerch='.$idmerch.'&act=select&sel=people&etape=listepeople&action=skip&skip='.$skip.'&contact=yes&idpeople='.$row['idpeople']; ?>">
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
				if($datev['glat']==0 && $datev['glong']==0) echo 'geoloc';
				else {
					if($row['pglat'] > 0 && $row['pglong'] > 0) {
						$coeff = array(5 => 1.40, 10 => 1.35, 20 => 1.42, 30 => 1.34, 40 => 1.30, 50 => 1.42, 75 => 1.29, 100 => 1.28);
						$dist = round($geo->EllipsoidDistance($datev['glat'], $datev['glong'], $row['pglat'], $row['pglong']));
						##### Chercher le bon coefficient dans l'array #####
						foreach($coeff as $k => $v) {
							if($dist >= $k) {
								$x = $v;
							}
						}
						$dist = $dist * $x;
						echo round($dist);
					} else echo '-';
				}
			 ?>
			 </td>
			<td <?php echo $color ?>><?php echo $row['cp1']; ?></td>
			<td <?php echo $color ?>><?php echo $row['ville1']; ?></td>
			<td <?php echo $color ?>><?php echo $row['cp2']; ?></td>
			<td <?php echo $color ?>><?php echo $row['ville2']; ?></td>
			<td <?php echo $color ?>><?php echo $row['gsm']; ?></td>
			<td <?php echo $color ?>>
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=people&etat=1&idmerch='.$idmerch;?>" method="post">
				<input type="hidden" name="idmerch" value="<?php echo $idmerch ;?>">
				<input type="hidden" name="idpeople" value="<?php echo $row['idpeople'] ;?>">
				<input type="submit" class="btn afficher">
				</form>
			</td>
		</tr>
	<?php
	} }
	?>
</table>
</fieldset>
</div>