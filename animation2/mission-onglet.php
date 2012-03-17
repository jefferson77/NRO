<?php 
session_start();
define('NIVO', '../');
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<?php
# Classes utilisées
require_once(NIVO."nro/fm.php");

if ($_GET['act'] == '') { $_GET['act'] = 'show'; }			# si POST est aussi vide, show par défaut

$idanimation = $_REQUEST['idanimation'];
$did = $_GET['idanimation'];

$disable = $_GET['disable'];

$infos = $DB->getRow("SELECT * FROM `animation` WHERE `idanimation` = $idanimation");
$etat=$infos['etat'];

	if (!empty($_REQUEST['idanimation'])) { 
		if ($infos['etat'] == 0) { $etat=0; }
		if ($infos['etat'] == 1) { $etat=1; }
		if (($infos['etat'] == 11 or $infos['etat'] == 12)) { $etat=1; }
	}

if ($_GET['s'] == 0) {$page = 'mission-produit';}
if ($_GET['s'] == 1) {$page = 'mission-materiel';}
if ($_GET['s'] == 2) {$page =  NIVO.'data/people/matos';}
if ($_GET['s'] == 3) {$page = 'mission-materiel-note';}
if ($_GET['s'] == 4) {$page = 'contact';}
if ($_GET['s'] == 5) {$page = 'onglet-down';}
if ($_GET['s'] == 6) {$page = 'onglet-down';}
if ($_GET['s'] == 7) {$page = 'modiftache';}
if ($_GET['s'] == 9) {$page = NIVO."data/bonlivraison/adminbonlivraison"; $_REQUEST['act']="list"; $_REQUEST['secteur']="AN";}

?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf8">
		<title>Neuro</title>
	</head>
		<frameset border="0" frameborder="no" framespacing="0" rows="26,*">
			<frame name="up" id="up" src="<?php echo 'mission-onglet-up.php?idanimation='.$_GET['idanimation'].'&s='.$_GET['s'].'&etat='.$etat.'&disable='.$disable.'&act='.$_GET['act'].'&titre='.$titre.'&castingcount='.$castingcount;?>" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="-1" scrolling="NO" >
			<frame name="down" src="<?php echo $page.'.php?idanimation='.$_GET['idanimation'].'&s='.$_GET['s'].'&etat='.$etat.'&disable='.$disable.'&act='.$_GET['act'].'&idpeople='.$infos['idpeople'].'&secteur=AN';?>" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0">
			<noframes>
				<body bgcolor="#ffffff">
					<p></p>
				</body>
			</noframes>
		</frameset>
		
<body>
</body>

</html>