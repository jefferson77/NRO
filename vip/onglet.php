<?php
session_start();

# Entete de page
define('NIVO', '../');

# Classes utilisées
require_once(NIVO."nro/fm.php");

## init vars
if (empty($_REQUEST['act'])) $_REQUEST['act'] = 'show';

if (!isset($page)) $page = '';

$menufac = '';

$infos = $DB->getRow("SELECT * FROM `vipjob` WHERE `idvipjob` = ".$_REQUEST['idvipjob']);

$etat = $infos['etat'];

### compte du casting
	if (!empty($infos['casting'])) {
		$explo = explode("-", $infos['casting']);
		$castingcount = count($explo);
	}

### compte du Stock Job
		$stockcount = $DB->getOne("SELECT COUNT(idticket) FROM stockticket WHERE idvipjob = ".$_REQUEST['idvipjob']." AND jobstatut = 'job'");

### compte le materiel du De-booking
	### VIP
		$FoundCountmatosvip = $DB->getOne("SELECT COUNT(st.idticket)
			FROM peoplemission pm
				LEFT JOIN vipmission m ON pm.idvip = m.idvip
				LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob
				LEFT JOIN people p ON pm.idpeople = p.idpeople
				LEFT JOIN stockticket st ON p.idpeople = st.idpeople
			WHERE m.idvipjob = ".$_REQUEST['idvipjob']." AND pm.idvip > 0 AND st.suser = 'people' AND st.inuse = 1
			ORDER BY m.idvipjob");

##### recherche de l'état du JOB et nombre de missions
	switch ($infos['etat']) {
		case "0":
			$titre = 'DEVIS (';
			$Count = $DB->getOne("SELECT COUNT(idvip) FROM `vipdevis` WHERE `idvipjob` = ".$_REQUEST['idvipjob']);
			$titre .= $Count.')';
		break;
		case "2":
			$titre = 'OUT (';
			$Count = $DB->getOne("SELECT COUNT(idvip) FROM `vipdelete` WHERE `idvipjob` = ".$_REQUEST['idvipjob']);
			$titre .= $Count.')';
		break;
		default:
			$titre = 'JOB (';
			$Count = $DB->getOne("SELECT COUNT(idvip) FROM `vipmission` WHERE `idvipjob` = ".$_REQUEST['idvipjob']);
			$titre .= $Count.')';
			$menufac = 1;
	}

	if (!empty($_REQUEST['idvipjob'])) {
		if ($infos['etat'] == 0) { $etat=0; }
		if ($infos['etat'] == 1) { $etat=1; }
		if (($infos['etat'] == 11 or $infos['etat'] == 12)) { $etat=1; }
	}

$params = '';
if ($_GET['s'] != 2)  { $_SESSION['casting'] = 'z'; }
if ($_GET['s'] == 1)  { $_SESSION['casting'] = 'z'; }
if ($_GET['s'] == 8)  { $page = 'adminvip-moulinette2'; }
if ($_GET['s'] == 0)  { $page = 'adminvip-moulinette'; }
if ($_GET['s'] == 1)  { $page = 'adminvip-devis'; }
if ($_GET['s'] == 2)  { $page = 'casting'; }
if ($_GET['s'] == 3)  { $page = 'contact'; }
if ($_GET['s'] == 4)  { $page = 'annexe'; }
if ($_GET['s'] == 5)  { $page = 'adminvip-devis';} # factorin g
if ($_GET['s'] == 6)  { $page = 'stock'; }
if ($_GET['s'] == 7)  { $page = 'modiftache'; }
if ($_GET['s'] == 9)  { $page = 'debooking';} # De-bookin g
if ($_GET['s'] == 10) { $page = 'adminvip-devis'; }
if ($_GET['s'] == 12) { $page = NIVO."data/boncommande/adminboncommande"; }
if ($_GET['s'] == 13) { $page = NIVO."data/bonlivraison/adminbonlivraison"; }
if ($_GET['s'] == 14) { $page = 'vipmailall'; }
if ($_GET['s'] == 11) {
	$page = NIVO.'/commun/notefrais';
	if(isset($_GET['bdact'])) $params .='&bdact='.$_GET['bdact'];
	if(isset($_GET['idvip'])) $params .='&idvip='.$_GET['idvip'];
}

?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	 "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
	<html>
		<head>
			<meta http-equiv="content-type" content="text/html;charset=utf8">
			<title>Neuro</title>
		</head>
<?php if(!isset($_GET['sort'])) { ?>
		<frameset border="0" frameborder="no" framespacing="0" rows="21,*">
			<frame name="up" src="<?php echo 'onglet-up.php?idvipjob='.$_REQUEST['idvipjob'].'&s='.$_GET['s'].'&etat='.$etat.'&act='.$_REQUEST['act'].'&titre='.$titre.'&castingcount='.@$castingcount.'&stockcount='.$stockcount.'&FoundCountmatosvip='.$FoundCountmatosvip.'&menufac='.$menufac;?>" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="-1" scrolling="NO" >
			<frame name="down" src="<?php echo $page.'.php?idvipjob='.$_REQUEST['idvipjob'].'&idvip='.@$_GET['idvip'].'&s='.$_GET['s'].'&etat='.$etat.'&act='.$_REQUEST['act'].'&secteur=VI'.@$bdparams;?>" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0">
<?php } else { ?>
		<frameset border="0" frameborder="no" framespacing="0">
			<frame name="down" src="<?php echo $page.'.php?idvipjob='.$_REQUEST['idvipjob'].'&s='.$_GET['s'].'&etat='.$etat.'&act='.$_REQUEST['act'].'&sort='.$_GET['sort'];?>" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0">
<?php }	?>
		<noframes>
			<body bgcolor="#ffffff"></body>
		</noframes>
	</frameset>
</html>
