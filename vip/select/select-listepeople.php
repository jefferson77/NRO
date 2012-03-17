<?php
## Init
$emails = '';

### $idvip et $idvipjob défini dans adminvip-devis.php ou adminvip.php selon le referrer
include_once(NIVO.'classes/hh.php');
include_once(NIVO."classes/geocalc.php");

### Date de la mission VIP pour disponibilités
$datev = $DB->getRow('SELECT v.vipdate, s.glat, s.glong, v.idshop
						FROM vipmission v
						LEFT JOIN shop s ON v.idshop = s.idshop
						WHERE v.idvip = '.$idvip);

$mm = explode("-", $datev['vipdate']);
$dd = 'd'.str_repeat('0', 2 - strlen($mm[2])).$mm[2];


# affichage avec skip
if (!empty($_GET['skip']) or ($_GET['contact'] == 'yes') or ($_GET['contact'] == 'modif')) {
	if ($_GET['skip'] == 'start') $skip = 0;
	else $skip = $_GET['skip'];
} else {
# Création de la recherche

	$searchfields = array (
		'p.pnom' => 'pnom',
		'p.pprenom' => 'pprenom',
		'p.codepeople' => 'codepeople',
		'p.notegenerale' => 'notegenerale',
		'p.ccheveux' => 'ccheveux',
		'p.physio' => 'physio',
		'p.permis' => 'permis',
		'p.voiture' => 'voiture',
		'p.err' => 'err',
		);

	$quid = $DB->MAKEsearch($searchfields);

	## age
	if (!empty($_POST['age'])) {
		$searchage = $DB->quidage($_POST['age'], 'p.ndate');

		if (!empty($searchage)) {
			if (!empty($quid)) $quid .= " AND ";
			$quid .= $searchage;
		}
	}

	# recherche shop
	if(!empty($_POST['rayon'])) {
		include_once(NIVO.'classes/geocalc.php');
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

		$geo = new Geocalc;

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

	## Sexe
	if ((!empty($_POST['sexe'])) AND ($_POST['sexe'] != 'x')) {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.sexe LIKE '%".$_POST['sexe']."%'";
	}

	## Sous-traité or not
	if ($_POST['indep'] == "n") {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.idsupplier = 0 ";
	}
	else if($_POST['indep']=="y")
	{
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.idsupplier > 0 ";
	}

	## Langues
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


	## BE CH DY
	if (!empty($_POST['beaute'])) {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.beaute >= ".$_POST['beaute'];
	}

	if (!empty($_POST['charme'])) {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.charme >= ".$_POST['charme'];
	}

	if (!empty($_POST['dynamisme'])) {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.dynamisme >= ".$_POST['dynamisme'];
	}

	## Code postal
	if (!empty($_POST['cp1a'])) {
		if (empty($_POST['cp1b'])) { $_POST['cp1b'] = $_POST['cp1a']; }
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "((p.cp1 >= ".$_POST['cp1a']." AND p.cp1 <= ".$_POST['cp1b'].") OR (p.cp2 >= ".$_POST['cp1a']." AND p.cp2 <= ".$_POST['cp1b']."))";
	}

	if ($_POST['ville1'] != '') {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "(";
			$quid .= "p.ville1 LIKE '%".addslashes($_POST['ville1'])."%'";
			$quid .= " OR ";
			$quid .= "p.ville2 LIKE '%".addslashes($_POST['ville2'])."%'";
			$quid .= ")";
	}
	if (!empty($_POST['categorie'])) {
		$searchstr = ($_POST['categorie'][0]=='1')?'1':'_';
		$searchstr.= ($_POST['categorie'][1]=='1')?'1':'_';
		$searchstr.= ($_POST['categorie'][2]=='1')?'1':'_';
		if (!empty($quid)) $quid .= " AND ";
		$quid = $quid."p.categorie LIKE '".$searchstr."'";
	}

	if (!empty($_POST['taillea'])) {
		if ($_POST['tailleb'] == '') { $_POST['tailleb'] = $_POST['taillea']; }
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "((p.taille >= '".$_POST['taillea']."' AND p.taille <= '".$_POST['tailleb']."') OR (p.cp2 >= '".$_POST['taillea']."' AND p.cp2 <= '".$_POST['tailleb']."'))";
	}

	## Pas les OUT
	if (!empty($quid)) $quid .= " AND ";
	$quid .= "p.isout NOT LIKE '%out%'";
	$skip = 0;

	## Assemblage de la recherche
	if (!empty($quid)) { $quid='WHERE '.$quid; }

	$_SESSION['peoplevipsearch'] = "SELECT p.*,
		IF(p.peoplehome=1,p.glat1,p.glat2) AS pglat, IF(p.peoplehome=1,p.glong1,p.glong2) AS pglong,
		d.".$dd." AS disp
		FROM people p
		LEFT JOIN vipmission m  ON p.idpeople = m.idpeople
		LEFT JOIN peoplevac v ON v.idpeople = p.idpeople AND v.etat LIKE 'in' AND '".$datev['vipdate']."' BETWEEN v.vacin AND v.vacout
		LEFT JOIN disponib d  ON p.idpeople = d.idboy AND d.annee = '".$mm[0]."' AND d.mois = '".$mm[1]."'
		".$quid." AND v.idpeople IS NULL
		GROUP BY p.idpeople";

	$_SESSION['peoplevipquid'] = $quid;
}

if (empty($sort)) { $sort = 'disp DESC, idpeople DESC'; }

if ($skip > 25) { $skipprev = $skip - 25; } else { $skipprev = 'start'; }
$skipnext = $skip + 25;

### recherchetot
$FoundCount = $DB->getOne('SELECT COUNT(idpeople) FROM people p '.$_SESSION['peoplevipquid']);

### RECHERCHE Info de base de Mission
$infosmission = $DB->getRow("SELECT
		m.vipactivite, m.vipdate, m.vipin, m.vipout,
		s.societe, s.adresse, s.cp AS shopcp, s.ville AS shopville
	FROM vipmission m
		LEFT JOIN shop s ON m.idshop = s.idshop
	WHERE m.idvip = ".$idvip);

?>
<fieldset>
	<legend>Recherche d'un People : <?php echo $_SESSION['peoplevipquod'].' &nbsp; &nbsp; &nbsp; &nbsp;'; echo '('.$FoundCount.' Results)'; ?></legend>
	<table border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<td class="standard">
				<?php echo "(".$infosmission['vipactivite'].") &nbsp; ".fdate($infosmission['vipdate'])." &nbsp; &nbsp;".ftime($infosmission['vipin'])."-".ftime($infosmission['vipout']); ?>
			</td>
			<td class="standard">
				Lieu : <?php echo $infosmission['societe']." ".$infosmission['adresse']." ".$infosmission['shopcp']." ".$infosmission['shopville']; ?>
			</td>
			<td>
				<form action="<?php echo NIVO."mod/sms/adminsms.php?act=show";?>" target="centerzonelarge" method="post">
				<input type="hidden" name="query" value="<?php echo $_SESSION['peoplevipquid']; ?>">
				<input type="hidden" name="secteur" value="VI">

				SEND:
				<input type="submit" class="btn phone" name="send" value="sms" title="Envoyer un SMS group&eacute;">
				<img src="<?php echo STATIK ?>illus/mail.png" width="16" height="16" alt="Mail" border="0" title="Adresses Mail" align="top" id="showmails">
				</form>
			</td>
			<td class="standard" align="right">
				<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$idvipjob.'&idvip='.$idvip.'&sel=people';?>"> &gt;&gt; Retour &agrave; la recherche &lt;&lt;</a><br>
			</td>
		</tr>
	</table>
<?php
if ($_GET['contact'] == 'yes') {
### RECHERCHE Contact people
$rowcontact1 = $DB->getRow("SELECT
		c.notecontact, c.etatcontact,
		p.*,
		a.nom AS agentnom, a.prenom AS agentprenom, a.atel, a.agsm
	FROM jobcontact c
		LEFT JOIN people p ON c.idpeople = p.idpeople
		LEFT JOIN agent a ON c.idagent = a.idagent
	WHERE c.idvipjob = ".$idvipjob."
		AND c.idpeople = ".$_GET['idpeople']);

?>
<form action="<?php echo '?idvipjob='.$idvipjob.'&idvip='.$idvip.'&act=select&sel=people&etape=listepeople&skip='.$skip.'&contact=modif&idpeople='.$rowcontact1['idpeople']; ?>" method="post">
	<input type="hidden" name="idvipjob" value="<?php echo $idvipjob ;?>">
	<input type="hidden" name="idvip" value="<?php echo $idvip ;?>">
	<input type="hidden" name="idpeople" value="<?php echo $_GET['idpeople'] ;?>">
	<table border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<td class="standard">
				<?php echo $rowcontact1['codepeople'].' - '.$rowcontact1['idpeople']; ?> <?php echo $rowcontact1['pnom']; ?> <?php echo $rowcontact1['pprenom']; ?>
			</td>
			<td class="standard">
				<input type="radio" name="etatcontact" value="0" <?php if (($rowcontact1['etatcontact'] == '0') or ($rowcontact['etatcontact'] == '')) { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-grey.gif" border="0" alt="phone-grey.gif" width="15" height="15">'; ?> N&eacute;ant
				<input type="radio" name="etatcontact" value="10" <?php if ($rowcontact1['etatcontact'] == '10') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-blue.gif" border="0" alt="phone-blue.gif" width="15" height="15">'; ?> Pas de r&eacute;p
				<input type="radio" name="etatcontact" value="20" <?php if ($rowcontact1['etatcontact'] == '20') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-red.gif" border="0" alt="phone-red.gif" width="15" height="15">'; ?> Non dispo
				<input type="radio" name="etatcontact" value="30" <?php if ($rowcontact1['etatcontact'] == '30') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-orange.gif" border="0" alt="phone-green.gif" width="15" height="15">'; ?> Message
				<input type="radio" name="etatcontact" value="40" <?php if ($rowcontact1['etatcontact'] == '40') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-green.gif" border="0" alt="phone-green.gif" width="15" height="15">'; ?> Ok
			</td>
			<td class="standard">
				Notes : <input type="text" name="notecontact" value="<?php echo $rowcontact1['notecontact']; ?>" size="25">
			</td>
			<td class="standard" align="right">
				<input type="submit" name="ok" value="ok">
			</td>
		</tr>
	</table>
</form>
<?php }

$prevlink = ($skip > 0)?'<a href="'.$_SERVER['PHP_SELF'].'?&idvipjob='.$idvipjob.'&idvip='.$idvip.'&act=select&sel=people&etape=listepeople&skip='.$skipprev.'"><img src="'.STATIK.'illus/avant.gif" alt="search" width="20" height="20" border="0"></a>':'';
$nextlink = ($skipnext < $FoundCount)?'<a href="'.$_SERVER['PHP_SELF'].'?&idvipjob='.$idvipjob.'&idvip='.$idvip.'&act=select&sel=people&etape=listepeople&skip='.$skipnext.'"><img src="'.STATIK.'illus/apres.gif" alt="search" width="20" height="20" border="0"></a>':'';

?>
<table class="standard" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
	<tr>
		<td class="standard"><?php echo $prevlink; ?></td>
		<td class="standard" align="center"><?php echo $skip;?> - <?php echo $skip+24;?></td>
		<td class="standard" align="right"><?php echo $nextlink; ?></td>
	</tr>
</table>
<table class="standard" border="0" width="100%" cellspacing="1" cellpadding="0" align="center">
	<tr>
		<th class="vip2">Code</th>
		<th class="vip2">D</th>
		<th class="vip2">Nom</th>
		<th class="vip2">Pr&eacute;nom</th>
		<th class="vip2"></th>
		<th class="vip2">Sx</th>
		<th class="vip2">Age</th>
		<th class="vip2">Fr</th>
		<th class="vip2">NL</th>
		<th class="vip2">En</th>
		<th class="vip2">KM</th>
		<th class="vip2">CP</th>
		<th class="vip2">Ville</th>
		<th class="vip2">CP2</th>
		<th class="vip2">Ville2</th>
		<th class="vip2">GSM</th>
		<th class="vip2"></th>
	</tr>
<?php
# Recherche des 15 clients

$peoples = $DB->getArray($_SESSION['peoplevipsearch'].' ORDER BY '.$sort.' LIMIT '.$skip.', 25');
$lesemails = $DB->getArray($_SESSION['peoplevipsearch'].' ORDER BY '.$sort);

foreach ($lesemails as $row) if (!empty($row['email'])) $emails[] = $row['email'];

$geo = new GeoCalc();

foreach ($peoples as $row) {
	if($datev['vipdate']<$row['vacin'] or $datev['vipdate']>$row['vacout']){
	### RECHERCHE Contact people
	$rowcontact = $DB->getRow("SELECT
		c.notecontact, c.etatcontact
		FROM jobcontact c
		WHERE c.idvipjob = ".$idvipjob." AND c.idpeople = ".$row['idpeople']);

	#/## RECHERCHE Contact people
	$calchh = new hh();
	$dds = explode("-", $datev['vipdate']);

	if (date("w", strtotime($datev['vipdate'])) == 0) {
	$lastweek = date("Y-m-d", mktime(0, 0, 0, $dds[1], $dds[2] - 7, $dds[0]));

	$worktable = $calchh->hhtable($row['idpeople'], $lastweek, $datev['vipdate']);

	if (array_sum(preg_split('//',$worktable[$lastweek],-1,PREG_SPLIT_NO_EMPTY)) > 0) {
		$ddillu = '<img src="'.STATIK.'illus/ddim.gif" alt="dim" width="7" height="13">'; } else { $ddillu = ''; }
	} else {
		$worktable = $calchh->hhtable($row['idpeople'], $datev['vipdate'], $datev['vipdate']);
	}

	$i++;

	echo '<tr bgcolor="'.((fmod($i, 2) == 1)?'#9CBECA':'#8CAAB5').'">';
	$color = ($row['idsupplier']>0)?'bgcolor="#FF6600"':'';
?>
<td <?php echo $color ?>><?php echo $row['codepeople']; ?></td>
<td <?php echo $color ?>><?php echo $ddillu; if (($row['disp'] != '') or (array_sum(preg_split('//',$worktable[$datev['vipdate']],-1,PREG_SPLIT_NO_EMPTY)) > 0)) {echo '<img src="/data/people/dispo/dillu.php?disp='.$row['disp'].'&hhcode='.$worktable[$datev['vipdate']].'" border="1" alt="1.gif" width="8" height="11">';} ?></td>
<td <?php echo $color ?>><?php echo showmax($row['pnom'], 20); ?></td>
<td <?php echo $color ?>><?php echo $row['pprenom']; ?></td>
<td <?php echo $color ?>>
	<a href="<?php echo NIVO.'data/people/adminpeople.php?act=show&idpeople='.$row['idpeople'];?>" target="_blank"><img src="<?php echo STATIK ?>illus/icon_profile.gif" border="0" width="15" height="12"></a>

	<a href="<?php echo $_SERVER['PHP_SELF'].'?&idvipjob='.$idvipjob.'&idvip='.$idvip.'&act=select&sel=people&etape=listepeople&skip='.$skip.'&contact=yes&idpeople='.$row['idpeople']; ?>">
		<?php if (($rowcontact['etatcontact'] == '0') or ($rowcontact['etatcontact'] == '')) {echo '<img src="'.STATIK.'illus/phone-grey.gif" border="0" alt="phone-grey.gif" width="15" height="15">';} ?>
		<?php if ($rowcontact['etatcontact'] == '10') {echo '<img src="'.STATIK.'illus/phone-blue.gif" border="0" alt="phone-green.gif" width="15" height="15">';} ?>
		<?php if ($rowcontact['etatcontact'] == '20') {echo '<img src="'.STATIK.'illus/phone-red.gif" border="0" alt="phone-green.gif" width="15" height="15">';} ?>
		<?php if ($rowcontact['etatcontact'] == '30') {echo '<img src="'.STATIK.'illus/phone-orange.gif" border="0" alt="phone-green.gif" width="15" height="15">';} ?>
		<?php if ($rowcontact['etatcontact'] == '40') {echo '<img src="'.STATIK.'illus/phone-green.gif" border="0" alt="phone-green.gif" width="15" height="15">';} ?>
	</a>

</td>
<td <?php echo $color ?>><?php echo $row['sexe']; ?></td>
<td <?php echo $color ?>><?php echo age($row['ndate']); ?></td>
<td <?php echo $color ?>><?php echo $row['lfr']; ?></td>
<td <?php echo $color ?>><?php echo $row['lnl']; ?></td>
<td <?php echo $color ?>><?php echo $row['len']; ?></td>
<td <?php echo $color ?>>
<?php
	if($datev['glat']>0 && $datev['glong']>0) {
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
		} else echo '<img src="'.STATIK.'illus/nogeoloc.png" alt="Pas de geoloc pour ce people">';
	} else echo '<img src="'.STATIK.'illus/nogeoloc.png" alt="Pas de geoloc pour ce shop">';
?>
</td>
<td <?php echo $color ?>><?php echo $row['cp1']; ?></td>
<td <?php echo $color ?>><?php echo showmax($row['ville1'], 12); ?></td>
<td <?php echo $color ?>><?php echo $row['cp2']; ?></td>
<td <?php echo $color ?>><?php echo showmax($row['ville2'], 12); ?></td>
<td <?php echo $color ?>><?php echo $row['gsm']; ?></td>
<td <?php echo $color ?>><form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=people&etat=1&from='.$_GET['from'];?>" method="post"><input type="hidden" name="idvipjob" value="<?php echo $idvipjob ;?>"><input type="hidden" name="idvip" value="<?php echo $idvip ;?>"><input type="hidden" name="idpeople" value="<?php echo $row['idpeople'] ;?>"><input type="submit" name="Selectionner" value="Selectionner"></form></td>
</tr>
<?php
	$count++;
	}
}
?>
</table>
<br>
</fieldset>
<br>
<?php echo $count;?>	Results
<fieldset>
	<legend>Legende</legend>
	<table>
		<tr>
			<td><img src="<?php echo STATIK ?>illus/ddim.gif" alt="dim" width="7" height="13"></td>
			<td>La personne a d&eacute;j&agrave; travaill&eacute; le dimanche pr&eacute;cendent</td>
		</tr>
		<tr>
			<td><img src="/data/people/dispo/dillu.php?disp=&hhcode=000001111001110000000000" border="1" alt="1.gif" width="8" height="11"></td>
			<td>Travaille d&eacute;ja le matin et l'apr&egrave;s midi</td>
		</tr>
		<tr>
			<td><img src="/data/people/dispo/dillu.php?disp=6" border="1" alt="1.gif" width="8" height="11"></td>
			<td>Est disponible l'apr&egrave;s midi et en soir&eacute;e</td>
		</tr>
		<tr>
			<td><img src="/data/people/dispo/dillu.php?disp=0" border="1" alt="1.gif" width="8" height="11"></td>
			<td>N'est pas disponible</td>
		</tr>
	</table>
</fieldset>
<div id="emails" style="top:50px;left: 100px;right: 100px;height: 200px; overflow:auto">
	<p>Pour envoyer un mail aux personnes suivantes, copiez le adresses ci-dessous et collez les dans un nouveau mail</p>
	<?php
	if (!empty($emails)) {
		echo implode(", ", $emails);
	} else {
		echo "Aucun mail";
	}
	?>
</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$("#showmails").click(function(){
			$("#emails").toggle();
		});
	});
</script>