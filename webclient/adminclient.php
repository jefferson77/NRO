<?php
if ($_GET['debut'] == 'oui') { include "log.php"; }
define('NIVO', '../');

$section  = '';
$print    = '../print/';
$document = '../document/';
$illus    = STATIK.'illus/';

# Entete de page
$Style = 'standard';
include NIVO."webclient/includes/entete.php" ;

### langue
if (empty($_SESSION['lang'])) $_SESSION['lang'] = 'fr';
include NIVO.'webclient/var'.$_SESSION['lang'].'.php';
#/## langue
$Titre = $titre_00;

$idwebclient = $_SESSION['idwebclient'];
$idclient    = $_SESSION['idclient'];
$idcofficer  = $_SESSION['idcofficer'];
$new         = $_SESSION['new'];

### variables pour menu haut ###
	$commande = 'non';
	$action   = 'oui';
	$archive  = 'oui';
#/ ## variables pour menu haut ###

switch ($_GET['act']) {
############## Modification et affichage d'un Client / contact  Validation #########################################
		case "modifcontact":
			$_SESSION['qualite'] = $_POST['qualite'];
			$_SESSION['prenom']  = $_POST['cprenom'];
			$_SESSION['nom']     = $_POST['cnom'];
			$_SESSION['societe'] = $_POST['societe'];
			$modif = new db('webclient', 'idwebclient', 'webneuro');
			$modif->MODIFIE($idwebclient, array('astva', 'codeclient' , 'societe' , 'qualite' , 'cprenom' , 'cnom' , 'fonction' , 'langue' , 'adresse' , 'cp' , 'ville' , 'pays' , 'email' , 'tva' , 'codetva' , 'codecompta' , 'tel' , 'fax' , 'password', 'etat'));
			if (
				($_POST['astva'] == '') or ($_POST['societe'] == '') or
				($_POST['qualite'] == '') or ($_POST['cprenom'] == '') or ($_POST['cnom'] == '') or
				($_POST['fonction'] == '') or ($_POST['langue'] == '') or ($_POST['adresse'] == '') or
				($_POST['cp'] == '') or ($_POST['ville'] == '') or ($_POST['pays'] == '') or
				($_POST['email'] == '') or ($_POST['tva'] == '') or ($_POST['codetva'] == '') or
				($_POST['tel'] == '') or ($_POST['fax'] == '') or
				($_POST['password'] == '')
			)
			{ $include = "contact.php" ;} else {$include = "menu.php" ;}
			$commande  = 'non';
			$action    = 'non';
			$archive   = 'non';
			$textehaut = $titre_01;
		break;

############## Menu  #########################################
		case "menu":
		default:
			$textehaut = $titre_01;
			$include   = "menu.php" ;
			$commande  = 'non';
			$action    = 'non';
			$archive   = 'non';
}
$textehautt = 'textehaut'.$s1;

#/##si retour à page précedente

include "includes/up.php" ;
include $include ;

# Pied de Page
include "includes/ppied.php" ;
?>