<?php
if ($_GET['debut'] == 'oui') include "lognew.php"; $_GET['act'] = 'new';

define('NIVO', '../');

$section  = '';
$print    = '../print/';
$document = '../document/';
$illus    = '../illus/';

# Classes utilisées
include NIVO."classes/anim.php" ;
include NIVO."classes/merch.php" ;
include NIVO."classes/vip.php" ;

# Entete de page
$Titre = 'CLIENTS';
$Style = 'standard';
include NIVO."webclient/includes/entete.php" ;

### langue
if (empty($_SESSION['lang'])) $_SESSION['lang'] = 'fr';
include 'var'.strtolower($_SESSION['lang']).'.php';

$idwebclient = $_SESSION['idwebclient'];
$new         = $_SESSION['new'];
$textehaut   = 'Mise &agrave; jour de la fiche : Introduction';
# Carousel des fonctions
switch ($_GET['act']) {
#OK############# Affichage d'un Client / contact #########################################
		case "new":
			$PhraseBas = 'Fiche de contact';
			$include   = "contact.php" ;
		break;
############## Modification et affichage d'un Client / contact  Validation #########################################
		case "modifcontact":
			$_SESSION['qualite'] = $_POST['qualite'];
			$_SESSION['prenom']  = $_POST['prenom'];
			$_SESSION['nom']     = $_POST['nom'];
			$_SESSION['societe'] = $_POST['societe'];

			$modif = new db('webclient', 'idwebclient', 'webneuro');
			$modif->MODIFIE($idwebclient, array('astva', 'codeclient' , 'societe' , 'qualite' , 'cprenom' , 'cnom' , 'fonction' , 'langue' , 'adresse' , 'cp' , 'ville' , 'pays' , 'email' , 'tva' , 'codetva' , 'codecompta' , 'tel' , 'fax' , 'password', 'etat'));
			if (
				($_POST['astva'] == '') or ($_POST['codeclient'] == '') or ($_POST['societe'] == '') or
				($_POST['qualite'] == '') or ($_POST['cprenom'] == '') or ($_POST['cnom'] == '') or
				($_POST['fonction'] == '') or ($_POST['langue'] == '') or ($_POST['adresse'] == '') or
				($_POST['cp'] == '') or ($_POST['ville'] == '') or ($_POST['pays'] == '') or
				($_POST['email'] == '') or ($_POST['tva'] == '') or ($_POST['codetva'] == '') or
				($_POST['codecompta'] == '') or ($_POST['tel'] == '') or ($_POST['fax'] == '') or
				($_POST['password'] == '') or ($_POST['etat'] == '')
			) { $include = "contact.php" ;} else {$include = "menu.php" ;}
			$textehaut = $titre_01;
		break;

############## affichage nouveau job Vip  #########################################
		case "vip0":
			### ajout de fiche vide avec 'etat', 'idwebclient', 'new'
			$_POST['etat']        = 0;
			$_POST['new']         = 1; # = new client
			$_POST['idwebclient'] = $_SESSION['idwebclient'];
			$_POST['idcofficer']  = $_SESSION['idcofficer'];
			$ajout = new db('webvipjob', 'idwebvipjob', 'webneuro');
			$ajout->AJOUTE(array('etat', 'idwebclient', 'idcofficer', 'new'));
			$idwebvipjob = $ajout->addid;
			$_POST['idwebvipjob'] = $ajout->addid;
			#/## ajout de fiche vide avec 'etat', 'idwebclient'
			$_POST['vipactivite'] = "70";
			$ajout = new db('webvipbuild', 'idvipbuild', 'webneuro');
			$ajout->AJOUTE(array('metat', 'idwebvipjob', 'vipactivite'));
			$include = "vip/vip.php" ;
			$textehaut = 'Commande en ligne : Secteur Vip, Infos G&eacute;n&eacute;rales';
		break;
############## Menu  #########################################
		case "menu":
		default:
			$textehaut = $titre_01;
			$PhraseBas = 'Menu Clients';
			$include   = "menu.php" ;
}
			$textehaut0 = 'Mise &agrave; jour de la fiche : Contacts';

###si retour à page précedente
if (!empty($_POST['retour'])) {
	$s = $s - 2;
	if ($s >= 1) { $s1 = $s - 1;}
} else {
	if ($s >= 1) { $s1 = $s - 1;}
	if ($s == 0) { $s1 = 0;}
	if ($s == '') { $s1 = '';}
}

$textehautt = 'textehaut'.$s1;

include "includes/up.php" ;
include $include ;

# Pied de Page
include "includes/ppied.php" ;
?>