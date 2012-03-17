<?php
/*
	TODO : AArgh HORRORPAGE : nettoyer les variables ici et tout relier a NIVO
*/

# Entete de page
define('NIVO', '../');
$Titre            = 'VIP';
$niveauvip        = '../../';
$classe           = '../../';
$section          = '../';
$print            = '../../print/';
$document         = '../../document/';
$illus            = '../../illus/';
$secteurexception = 'vip';

# Classes utilisées
include NIVO."classes/hh.php" ;
include NIVO.'classes/vip.php';

# Entete de page
include NIVO."includes/ifentete.php" ;

### langue
if (empty($_SESSION['lang'])) $_SESSION['lang'] = 'fr';
include 'var'.$_SESSION['lang'].'.php';

### Variable de Base : $idmembre & $s
$idvipjob = !empty($_REQUEST['idvipjob']) ? $_REQUEST['idvipjob'] : '' ;

# Carousel des fonctions
### variables pour menu haut ###
#/ ## variables pour menu haut ###
switch ($_GET['act']) {
############## affichage nouveau job Vip CREATION  #########################################
		case "vip0":
			### ajout de fiche vide avec 'etat', 'idclient' , 'new'
			$_POST['etat'] = 0;
			#/## ajout de fiche vide avec 'etat', 'idclient'
					$_POST['vipactivite'] = "70";
					$ajout = new db('vipbuild', 'idvipbuild');
					$ajout->AJOUTE(array('metat', 'idvipjob', 'vipactivite'));
			$include = "moulinette.php" ;
		break;
############## Selection du lieu #############################################
		case "select":
			$_SESSION['selectfrom'] = 'mission';
			$PhraseBas              = 'Selection des &eacute;l&eacute;ments';
			$did                    = $_GET['idvipjob'];
			$idvipjob               = $_GET['idvipjob'];
			$_POST['idvipjob']      = $_GET['idvipjob'];
			$idvip                  = $_GET['idvip'];
			$etat                   = $_GET['etat'];
			if($_GET['etape']=="listepeople") $include='select/select-listepeople.php';
			else $include='select/select-'.$_GET['sel'].'.php' ;
		break;
############## Modification d'une Vipbuild pour le lieu #########################################
		case "modif":
			$idvipbuild = (!empty($_POST['idvip']))?$_POST['idvip']:$_GET['idvip'];
			$idvipjob   = (!empty($_POST['idvipjob']))?$_POST['idvipjob']:$_GET['idvipjob'];
			$idshop     = (!empty($_POST['idshop']))?$_POST['idshop']:$_GET['idshop'];

			$DB->inline('UPDATE vipbuild SET idshop="'.$idshop.'" WHERE idvipbuild="'.$idvipbuild.'" LIMIT 1');
			$include = "moulinette.php" ;
		break;

############## affichage nouveau job Vip RECUOPERATION POUR CLIENT NEW #########################################
		case "vip0a":
			### ajout de fiche vide avec 'etat', 'idclient' , 'new'
			$_POST['etat'] = 0;
			$_POST['new']  = $new;
			$include = "moulinette.php" ;
		break;

############## affichage et modif job Vipjob  PAGE 1 Infos Générales #########################################
		case "vipmodif0":

			$idvipjob = $_POST['idvipjob'];

			$s = $_POST['s'];
			$s++;

			$include = "moulinette.php" ;
		break;
############## affichage Pour RETOUR job Vipjob  PAGE 1 Infos Générales #########################################
		case "vipmodif0a":
			$idvipjob = $_GET['idvipjob'];
			if (!empty($_POST['idvipjob'])) {$idvipjob = $_POST['idvipjob'];}
			$s = 0;

			$include = "moulinette.php" ;
		break;
############## affichage Pour RETOUR job Vipjob  PAGE 2 Activités #########################################
		case "vipmodif1a":
			$idvipjob = $_POST['idvipjob'];
			$s = 1;

			$include = "moulinette.php" ;
		break;

############## affichage et modif job Vipjob  PAGE 2 Activités #########################################
		case "vipmodif1":

			$idvipjob = $_POST['idvipjob'];
			$_POST['metat'] = 0;
			if (!isset($_GET['type'])) $_GET['type'] = '';

			### AJOUT type=ajout
			if ($_GET['type'] == 'ajout') {
					$_POST['vipactivite'] = "70";
					$ajout = new db('vipbuild', 'idvipbuild');
					$ajout->AJOUTE(array('metat', 'idvipjob', 'vipactivite', 'idshop'));
					$idvipbuild = $ajout->addid;
			}
			#/## AJOUT type=ajout

			### AJOUT type=Delete
			if ($_GET['type'] == 'Delete') {
					$idvipbuild = $_POST['idvipbuild'];
					$jobdel = new db('vipbuild', 'idvipbuild');
					$sqldel = "DELETE FROM `vipbuild` WHERE `idvipbuild` = $idvipbuild;";
					$jobdel->inline($sqldel);
			}
			#/## AJOUT type=Delete

			### UPDATE type=update
			if ($_GET['type'] == 'update') {
					if (strchr($_POST['vipdate1'], '/')) {	} else { $_POST['vipdate1'] = ''; }
					if (strchr($_POST['vipdate1'], '-')) {$_POST['vipdate1'] = ''; }
					if (strchr($_POST['vipdate2'], '/')) {	} else { $_POST['vipdate2'] = ''; }
					if (strchr($_POST['vipdate2'], '-')) {	$_POST['vipdate2'] = ''; }

					if ($_POST['vipactivite'] != '99') {
						$_POST['tvipactivite'] = '';
					}

					$_POST['vipdate1'] = fdatebk($_POST['vipdate1']);
					$_POST['vipdate2'] = fdatebk($_POST['vipdate2']);
					if ($_POST['vipdate2'] == '') {$_POST['vipdate2'] = $_POST['vipdate1'];}
					if ($_POST['vipnombre'] == 0) {$_POST['vipnombre'] = 1;}
					$_POST['vipin'] = ftimebk($_POST['vipin']);
					$_POST['vipout'] = ftimebk($_POST['vipout']);

					$_POST['vipactivite'] = addslashes($_POST['vipactivite']);

					$_POST['brk'] = fnbrbk($_POST['brk']);
					$_POST['night'] = fnbrbk($_POST['night']);
					$_POST['h150'] = fnbrbk($_POST['h150']);
					$_POST['ts'] = fnbrbk($_POST['ts']);
					$_POST['fts'] = fnbrbk($_POST['fts']);
					$_POST['fkm'] = fnbrbk($_POST['fkm']);
					$_POST['unif'] = fnbrbk($_POST['unif']);
					$_POST['loc1'] = fnbrbk($_POST['loc1']);
					$_POST['cat'] = fnbrbk($_POST['cat']);
					$_POST['disp'] = fnbrbk($_POST['disp']);
					$_POST['fr1'] = fnbrbk($_POST['fr1']);

					$idvipbuild = $_POST['idvipbuild'];

					$modif = new db('vipbuild', 'idvipbuild');
					$modif->MODIFIE($idvipbuild, array('vipactivite' , 'tvipactivite' , 'vipnombre' , 'sexe' , 'vipdate1' , 'vipdate2' , 'vipin' , 'vipout', 'idshop', 'brk', 'night', 'h150', 'ts', 'fts', 'km', 'fkm', 'unif', 'loc1', 'cat', 'disp', 'fr1'));
			}
			#/## UPDATE type=update


			$s = $_POST['s'];
			#$s++;


			$include = "moulinette.php" ;
		break;
############## affichage Pour RETOUR job Vipjob  PAGE Annexes #########################################
		case "vipmodif2a":
			$idvipjob = $_POST['idvipjob'];
			$s = 1;

			$include = "moulinette.php" ;
		break;

############## affichage et modif job Vipjob  PAGE 2 Activités VISUEL #########################################
		case "vipmodif2":
			$idvipjob = $_POST['idvipjob'];

			$s = $_POST['s'];
			#$s++;

			$include = "moulinette.php" ;
		break;
############## affichage Pour RETOUR job Vipjob  PAGE Annexes #########################################
		case "vipmodif3a":
			$idvipjob = $_POST['idvipjob'];
			$s = 2;

			$include = "moulinette.php" ;
		break;

############## Menu  #########################################
		default:
			$include = "moulinette.php" ;
}

###si retour à page précedente
if (!empty($_POST['retour'])) {
	$s = $s - 2;
	if ($s >= 1) { $s1 = $s - 1;}
} else {
	if (empty($s)) { $s1 = '';}
	elseif ($s >= 1) { $s1 = $s - 1;}
	elseif ($s == 0) { $s1 = 0;}
}
#/##si retour à page précedente

include $include ;


# Pied de Page
include NIVO."includes/ifpied.php" ;
?>