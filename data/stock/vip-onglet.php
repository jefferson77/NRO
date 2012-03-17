<?php 
session_start();
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<?php
# Entete de page
define('NIVO', '../../'); 

# Classes utilisées
include NIVO."nro/fm.php" ;

if (empty($_REQUEST['act'])) $_REQUEST['act'] = 'show';

$infos = $DB->getRow("SELECT casting, etat FROM `vipjob` WHERE `idvipjob` = ".$_REQUEST['idvipjob']);

### compte du casting
if (!empty($infos['casting'])) {
	$explo = explode("-", $infos['casting']);
	$castingcount = count($explo);
} 

##### recherche de l'état du JOB et nombre de missions
switch ($infos['etat']) {
	case "0": 
		$titre = 'DEVIS ('.$DB->getOne("SELECT  COUNT(idvip)  FROM  `vipdevis` WHERE `idvipjob` = ".$_REQUEST['idvipjob']).')';
	break;
	case "1": 
	case "11": 
	case "12": 
		$titre = 'JOB ('.$DB->getOne("SELECT  COUNT(idvip)  FROM  `vipmission` WHERE `idvipjob` = ".$_REQUEST['idvipjob']).')';
	break;
	case "2": 
		$titre = 'OUT ('.$DB->getOne("SELECT  COUNT(idvip)  FROM  `vipdelete` WHERE `idvipjob` = ".$_REQUEST['idvipjob']).')';
}

if (($infos['etat'] == 11 or $infos['etat'] == 12)) $infos['etat'] = 1;

$page = ($_GET['s'] == "7")?'vip-modele':'vip-ticket';
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf8">
		<title>Neuro</title>
	</head>
	<frameset border="0" frameborder="no" framespacing="0" rows="35,*">
		<frame name="up" src="<?php echo 'vip-onglet-up.php?idvipjob='.$_REQUEST['idvipjob'].'&s='.$_GET['s'].'&etat='.$infos['etat'].'&act='.$_REQUEST['act'].'&titre='.$titre.'&castingcount='.$castingcount;?>" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="-1" scrolling="NO" >
		<frame name="down" src="<?php echo $page.'.php?idvipjob='.$_REQUEST['idvipjob'].'&s='.$_GET['s'].'&etat='.$infos['etat'].'&act='.$_REQUEST['act'].'';?>" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0">
		<noframes>
			<body bgcolor="#ffffff">
				<p></p>
			</body>
		</noframes>
	</frameset>
	<body>
	</body>
</html>