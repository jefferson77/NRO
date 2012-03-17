<?php
session_start();

define('NIVO', '../');

# Classes utilisées
require_once(NIVO."nro/fm.php");

include_once NIVO."classes/makehtml.php";

## Initialise la Session
if ($_GET['debut'] == 'oui') {
	$_SESSION                = array();
	$loginfo                 = $DB->getRow("SELECT idwebpeople, idpeople, email, webpass, pprenom, pnom, lbureau FROM webneuro.webpeople WHERE idwebpeople = '".$_GET['idwebpeople']."' ");

	$_SESSION['idwebpeople'] = $loginfo['idwebpeople'];
	$_SESSION['idpeople']    = $loginfo['idpeople'];
	$_SESSION['nom']         = $loginfo['pnom'];
	$_SESSION['prenom']      = $loginfo['pprenom'];
	$_SESSION['lang']        = (!empty($loginfo['lbureau']))?strtolower($loginfo['lbureau']):"fr";
	$_SESSION['webtype']     = 0;
}

# Entete de page
include "includes/newentete.php" ;

### langue
include 'var'.$_SESSION['lang'].'.php';

$Titre     = $titre_00.' '.$titre_new_00;

$idpeople  = $_SESSION['idpeople'];
$textehaut = $detail_00.' : '.$detail_01;

# Carousel des fonctions
switch ($_REQUEST['act']) {
############## Debut de l'inscription #####################################################################
		case "modif":
			$textehaut = $detail_00.' : '.$detail_01;
			$include = "v-detail.php" ;
		break;
#OK############# Affichage d'une Mission #########################################
		case "show":
			$PhraseBas = 'Detail d\'un PEOPLE';
			$include = "v-detail.php" ;
		break;
############## Modification et affichage d'un PEOPLE Introduction #########################################
		case "modif0":
			$include = "v-detail.php" ;
			$s = $_POST['s'];
			$s++;
		break;
############## Modification et affichage d'un PEOPLE CONTACT #########################################
		case "modif1":
			$_POST['webetat'] = 0;
			$DB->MAJ('webneuro.webpeople');

			$include = "v-detail.php" ;

			$s = $_POST['s'];
			$s++;

		break;
############## Modification et affichage d'un PEOPLE Infos Générales #########################################
		case "modif2":
			if(!empty($_POST['conninformatiq'])) $_POST['conninformatiq'] = (($_POST['conninformatiq'][0]=='1')?'1':'0').(($_POST['conninformatiq'][1]=='1')?'1':'0').(($_POST['conninformatiq'][2]=='1')?'1':'0').(($_POST['conninformatiq'][3]=='1')?'1':'0');
			if(!empty($_POST['categorie'])) $_POST['categorie'] = (($_POST['categorie'][0]=='1')?'1':'0').(($_POST['categorie'][1]=='1')?'1':'0').(($_POST['categorie'][2]=='1')?'1':'0');

			$modif = new db('webpeople', 'idwebpeople', 'webneuro');
			$modif->MODIFIE($_POST['idwebpeople'], array('categorie', 'lfr', 'lnl', 'len', 'ldu', 'lit', 'lsp', 'physio', 'ccheveux', 'lcheveux', 'taille', 'tveste', 'tjupe', 'pointure', 'permis', 'voiture', 'menstai', 'menspoi', 'menshan', 'fume', 'conninformatiq'));
			$include = "v-detail.php" ;
			$s = $_POST['s'];
			$s++;
		break;
############## Modification et affichage d'un PEOPLE Secrétariat Social #########################################
		case "modif3":
			$_POST['ndate'] = fdatebk($_POST['ndate']);
			$_POST['datemariage'] = fdatebk($_POST['datemariage']);
			$_POST['dateconjoint'] = fdatebk($_POST['dateconjoint']);

			$arrayfrom = array(".", "/", "=", "-", " ");
 			$_POST['nrnational'] = str_replace($arrayfrom, "", $_POST['nrnational']);
 			$_POST['ncidentite'] = str_replace($arrayfrom, "", $_POST['ncidentite']);

			$modif = new db('webpeople', 'idwebpeople', 'webneuro');
			$modif->MODIFIE($_POST['idwebpeople'], array('ndate', 'ncp', 'nville', 'npays', 'ncidentite', 'nrnational', 'nationalite', 'etatcivil', 'datemariage', 'nomconjoint', 'dateconjoint', 'jobconjoint', 'pacharge', 'eacharge', 'banque', 'iban', 'bic'));
			$include = "v-detail.php" ;
			$s = $_POST['s'];
			$s++;
		break;
############## Modification et affichage d'un PEOPLE Photo #########################################
		case "modif4":
			$include = "v-detail.php" ;
			$s = $_POST['s'];
			$s++;
		break;

############## Modification et affichage d'un PEOPLE  Validation #########################################
		case "modif5":
			$_POST['webetat']    = 4;

			$DB->MAJ('webneuro.webpeople');

			$s = $_POST['s'];
			$s++;

			$include = "newmenu.php" ;
				if (!empty($_POST['retour'])) {
					$include = "v-detail.php" ;
				}

		break;

############## Menu  #########################################
		case "menu":
		default:
			$infos = $DB->getRow("SELECT idwebpeople, webetat FROM webneuro.webpeople WHERE `idwebpeople` = ".$_SESSION['idwebpeople']);

			$textehaut = $menu_00;
			$PhraseBas = 'Menu People';
			$include = "newmenu.php" ;
}

$textehaut0 = $detail_00.' : '.$detail_02;
$textehaut1 = $detail_00.' : '.$detail_03;
$textehaut2 = $detail_00.' : '.$detail_04;
$textehaut3 = $detail_00.' : '.$detail_05;
$textehaut4 = $detail_00.' : '.$detail_06;

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


include "includes/newup.php" ;

include $include ;

include "includes/ppied.php" ;
?>