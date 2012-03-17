<?php
define('NIVO', '../');
$niveauweb = '';

# Entete de page
include "includes/entete.php" ;
# Classes utilisées
include NIVO."classes/makehtml.php" ;
require_once(NIVO.'classes/document.php');

if (isset($_REQUEST['debut']) && $_REQUEST['debut'] == 'oui') {
	$loginfo = $DB->getRow("SELECT idpeople, email, webpass, pprenom, pnom, lbureau, webdoc, isout FROM people WHERE idpeople = '".$_REQUEST['idpeople']."'");

	$_SESSION['idpeople'] = $loginfo['idpeople'];
	$_SESSION['nom']      = $loginfo['pnom'];
	$_SESSION['prenom']   = $loginfo['pprenom'];
	$_SESSION['lang']     = strtolower($loginfo['lbureau']);
	$_SESSION['out']      = $loginfo['isout'];
	$_SESSION['webtype']  = 1;
	$_SESSION['webdoc']   = $loginfo['webdoc'];
}

### langue
if (!empty($_GET['langue']) and in_array(strtolower($_GET['langue']), array('fr', 'nl', 'en')))
								$_SESSION['lang'] = strtolower($_GET['langue']);
if (empty($_SESSION['lang']))	$_SESSION['lang'] = "fr";

include 'var'.strtolower($_SESSION['lang']).'.php';

$Titre     = $titre_00;
$idpeople  = $_SESSION['idpeople'];
$textehaut = $detail_00.' : '.$detail_01;

# Carousel des fonctions
switch (@$_REQUEST['act']) {
#OK############# Affichage d'une Mission #########################################
		case "show":
			$PhraseBas = 'Detail d\'un PEOPLE';
			$include = "v-detail.php" ;
		break;
############## affichage d'une Fiche PEOPLE #########################################
		case "modif":
			$infos = $DB->getRow("SELECT idwebpeople, webetat FROM webneuro.webpeople WHERE `idpeople` = ".$_SESSION['idpeople']);

			# Création fiche webpeople

			if ($infos['idwebpeople'] > 0) {
				$_SESSION['idwebpeople'] = $infos['idwebpeople'];
			} else {
				### nouvelle fiche web + webpeople WebETAT = 0
	            $ajout = new db();
	            $fields = "`sexe` , `pnom` , `pprenom` , `adresse1` , `num1` , `bte1` , `cp1` , `ville1` , `pays1` , `adresse2` , `num2` , `bte2` , `cp2` , `ville2` , `pays2` , `photo` , `tel` ,
	            `fax` , `gsm` , `email` , `lfr` , `lnl` , `len`, `ldu` , `lit` , `lsp` , `lbase` , `lbureau` , `notelangue`, `physio` , `province` , `ccheveux` , `lcheveux` , `taille` , `tveste`,
	            `tjupe` , `pointure` , `permis` , `voiture` , `ndate` , `ncp`, `nville` , `npays` , `catsociale` , `ncidentite` , `nrnational` , `nationalite`, `etatcivil` , `datemariage` ,
	            `nomconjoint` , `dateconjoint` , `jobconjoint` , `pacharge`, `eacharge` , `banque` , `iban` , `bic`, `webpass` , `categorie` , `menspoi`, `menstai` , `menshan` , `fume` , `conninformatiq`, `conditions_accepted`";
	            $ajout->inline("INSERT INTO webneuro.webpeople ($fields, `webetat`, `idpeople`, `webtype`) SELECT $fields, '0', '".$idpeople."', '1' FROM neuro.people WHERE `idpeople` = $idpeople");
	            $_SESSION['idwebpeople'] = $ajout->addid;
			}

			$textehaut = $detail_00.' : '.$detail_01;

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
			$_POST['taille'] = cleannombreonly($_POST['taille']);

			$DB->MAJ('webneuro.webpeople');

			$include = "v-detail.php" ;
			$s = $_POST['s'];
			$s++;
		break;
############## Modification et affichage d'un PEOPLE Secrétariat Social #########################################
		case "modif3":
			$_POST['ndate']        = fdatebk($_POST['ndate']);
			$_POST['datemariage']  = fdatebk($_POST['datemariage']);
			$_POST['dateconjoint'] = fdatebk($_POST['dateconjoint']);

			$arrayfrom = array(".", "/", "=", "-", " ");
 			$_POST['nrnational'] = str_replace($arrayfrom, "", $_POST['nrnational']);
 			$_POST['ncidentite'] = str_replace($arrayfrom, "", $_POST['ncidentite']);

			$DB->MAJ('webneuro.webpeople');

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
			$_POST['webetat'] = 4;

			$DB->MAJ('webneuro.webpeople');

			$s = $_POST['s'];
			$s++;

			$include = "menu.php" ;
				if (!empty($_POST['retour'])) {
					$include = "v-detail.php" ;
				}

		break;

############## affichage et impression des contrats Anim  #########################################
		case "contratanim":
			$include   = "contratanim.php" ;
			$textehaut = $contrat_anim_00;
			break;

############## affichage et impression des contrats Merch  #########################################
		case "contratmerch":
			$textehaut = $contrat_merch_00;
			$include   = "contratmerch.php" ;
			break;

############## affichage et impression des contrats Vip  #########################################
		case "contratvip":
			$textehaut = $contrat_vip_00;
			$include   = "contratvip.php" ;
			break;

############## affichage et impression des Semaine Merch EAS #########################################
		case "easmerch":
			$textehaut = $eas_merch_00;
			$include   = "easmerch.php" ;
			break;
############## affichage et impression des C4 #########################################
		case "c4":
			$textehaut = $eas_merch_00;
			$web       = true;
			$include   = "c4.php" ;
			break;
############## affichage et impression des Attestations de travail #########################################
		case "attest":
			$textehaut = $menu_13;
			$include   = "attest.php" ;
			break;
############## affichage et impression des 281.10 (pour la declaration d'impots ############################
		case "281.10":
			$textehaut = "281.10";
			$include   = "281.10.php";
			break;
############## affichage et impression des 281.10 (pour la declaration d'impots ############################
		case "ficheSalaire":
			$textehaut = $menu_14;
			$include   = "ficheSalaire.php";
			break;
############## Disponibilités  #########################################
		case "dispo0":
			$textehaut = $dispo_00;
			$include   = "dispo2.php" ;
			break;
############## Menu  #########################################
		case "menu":
		default:
			$textehaut = $menu_00;
			$PhraseBas = 'Menu People';
			$include   = "menu.php" ;
	}

$textehaut0 = $detail_00.' : '.$detail_02;
$textehaut1 = $detail_00.' : '.$detail_03;
$textehaut2 = $detail_00.' : '.$detail_04;
$textehaut3 = $detail_00.' : '.$detail_05;
$textehaut4 = $detail_00.' : '.$detail_06;

###si retour à page précedente
if(!isset($s)) $s = 0;
if (!empty($_POST['retour'])) {
	$s = $s - 2;
	if ($s >= 1) { $s1 = $s - 1;}
} else {
	if ($s >= 1) { $s1 = $s - 1;}
	if ($s == 0) { $s1 = 0;}
	if ($s == '') { $s1 = '';}
}
$textehautt = 'textehaut'.$s1;

#/##si retour à page précedente

include "includes/up.php" ;
include $include ;
include "includes/ppied.php" ;
?>