<?php
# Entete de page
define('NIVO', '../../'); 
$Style = 'standard';
$section = '../';
$print = '../../print/';
$document = '../../document/';
$illus = '../../illus/';
$secteurexception = 'vip';

# Classes utilisées
include NIVO."classes/vip.php" ;

include NIVO."webclient/includes/entete.php" ;

$onyearago  = date ("Y-m-d", strtotime("-1 year"));

### langue 
if ($_SESSION['lang'] == '') {$_SESSION['lang'] = 'fr';}
$pagevar = NIVO.'webclient/var'.strtolower($_SESSION['lang']).'.php';
include $pagevar;
#/## langue 

$Titre = $titre_00.' / VIP';

### Variable de Base : $idmembre & $s
if (isset($_GET['idwebvipjob'])) {
	#$s = $_GET['s'];
	$idwebvipjob = $_GET['idwebvipjob'];
}
if (isset($_POST['idwebvipjob'])) {
	#$s = $_POST['s'];
	$idwebvipjob = $_POST['idwebvipjob'];
}
#/ ## Variable de Base : $idmembre & $s
?>
<?php
$idclient = $_SESSION['idclient'];
$idwebclient = $_SESSION['idwebclient'];
$idcofficer = $_SESSION['idcofficer'];
$new = $_SESSION['new'];
# Carousel des fonctions
### variables pour menu haut ###
	$commande = 'oui';
	$action = 'oui';
	$archive = 'oui';
#/ ## variables pour menu haut ###
switch ($_GET['act']) {
############## affichage nouveau job Vip CREATION  #########################################
		case "vip0": 
			### ajout de fiche vide avec 'etat', 'idclient' , 'new'
			$_POST['etat'] = 0;
			$_POST['new'] = $new;
			$_POST['idclient'] = $_SESSION['idclient'];
			$_POST['idwebclient'] = $_SESSION['idwebclient'];
			$_POST['idcofficer'] = $_SESSION['idcofficer'];
				$ajout = new db('webvipjob', 'idwebvipjob', 'webneuro');
				### client webnew 
					if (!empty($_SESSION['idwebclient'])) {  
						$ajout->AJOUTE(array('etat', 'idwebclient', 'idcofficer', 'isnew')); 
						$_SESSION['newvip'] = $ajout->addid;
						$_SESSION['newanim'] = 'closed';
						$_SESSION['newmerch'] = 'closed';
					} 
				### client Exception
					else { $ajout->AJOUTE(array('etat', 'idclient', 'idcofficer', 'isnew')); } 
			$idwebvipjob = $ajout->addid;
			$_POST['idwebvipjob'] = $ajout->addid;
			#/## ajout de fiche vide avec 'etat', 'idclient' 
					$_POST['vipactivite'] = "70";
					$ajout = new db('webvipbuild', 'idvipbuild', 'webneuro');
					$ajout->AJOUTE(array('metat', 'idwebvipjob', 'vipactivite'));
			$include = "vip.php" ;
			$textehaut = $titre_02.', '.$vipdetail_01;
			$commande = 'non';
		break;
############## affichage nouveau job Vip RECUOPERATION POUR CLIENT NEW #########################################
		case "vip0a": 
			### ajout de fiche vide avec 'etat', 'idclient' , 'new'
			$_POST['etat'] = 0;
			$_POST['new'] = $new;
			$_POST['idclient'] = $_SESSION['idclient'];
			$_POST['idwebclient'] = $_SESSION['idwebclient'];
			$_POST['idcofficer'] = $_SESSION['idcofficer'];

			$idwebvipjob = $_SESSION['newvip'];
			$_POST['idwebvipjob'] = $_SESSION['newvip'];
			$include = "vip.php" ;
			$textehaut = $titre_02.', '.$vipdetail_01;
			$commande = 'non';
		break;

############## affichage et modif job Vipjob  PAGE 1 Infos Générales #########################################
		case "vipmodif0": 

			$idwebvipjob = $_POST['idwebvipjob'];

			$modif = new db('webvipjob', 'idwebvipjob', 'webneuro');
			$modif->MODIFIE($idwebvipjob, array('reference' , 'notejob' , 'noteprest' , 'notedeplac' , 'noteloca' , 'notefrais', 'bondecommande'));

			$s = $_POST['s'];
			$s++;

			$include = "vip.php" ;
			$textehaut = $titre_02.', '.$vipdetail_01;
			$commande = 'non';
		break;
############## affichage Pour RETOUR job Vipjob  PAGE 1 Infos Générales #########################################
		case "vipmodif0a": 
			$idwebvipjob = $_GET['idwebvipjob'];
			if (!empty($_POST['idwebvipjob'])) {$idwebvipjob = $_POST['idwebvipjob'];}
			$s = 0;

			$include = "vip.php" ;
			$textehaut = $titre_02.', '.$vipdetail_01;
			$commande = 'non';
		break;
############## affichage Pour RETOUR job Vipjob  PAGE 2 Activités #########################################
		case "vipmodif1a": 
			$idwebvipjob = $_POST['idwebvipjob'];
			$s = 1;

			$include = "vip.php" ;
			$textehaut = $titre_02.', '.$vipdetail_01;
			$commande = 'non';
		break;

############## affichage et modif job Vipjob  PAGE 2 Activités #########################################
		case "vipmodif1": 

			$idwebvipjob = $_POST['idwebvipjob'];
			$_POST['metat'] = 0;
			
			### AJOUT type=ajout
			if ($_GET['type'] == 'ajout') {
					$_POST['vipactivite'] = "70";
					$ajout = new db('webvipbuild', 'idvipbuild', 'webneuro');
					$ajout->AJOUTE(array('metat', 'idwebvipjob', 'vipactivite'));
					$idvipbuild = $ajout->addid;
			}
			#/## AJOUT type=ajout

			### AJOUT type=Delete
			if ($_GET['type'] == 'Delete') {
					$idvipbuild = $_POST['idvipbuild'];
					$jobdel = new db('webvipbuild', 'idvipbuild', 'webneuro');
					$sqldel = "DELETE FROM `webvipbuild` WHERE `idvipbuild` = $idvipbuild;";
					$jobdel->inline($sqldel);
			}
			#/## AJOUT type=Delete

			### UPDATE type=update
			if ($_GET['type'] == 'update') {
					if (strchr($_POST['vipdate1'], '/')) {	} else { $_POST['vipdate1'] = ''; }
					if (strchr($_POST['vipdate1'], '-')) {$_POST['vipdate1'] = ''; }
					if (strchr($_POST['vipdate2'], '/')) {	} else { $_POST['vipdate2'] = ''; }
					if (strchr($_POST['vipdate2'], '-')) {	$_POST['vipdate2'] = ''; }

					$_POST['vipdate1'] = fdatebk($_POST['vipdate1']);
					$_POST['vipdate2'] = fdatebk($_POST['vipdate2']);
					if ($_POST['vipdate2'] == '') {$_POST['vipdate2'] = $_POST['vipdate1'];}
					if ($_POST['vipnombre'] == 0) {$_POST['vipnombre'] = 1;}
					$_POST['vipin'] = ftimebk($_POST['vipin']);
					$_POST['vipout'] = ftimebk($_POST['vipout']);
					$idvipbuild = $_POST['idvipbuild'];

					$modif = new db('webvipbuild', 'idvipbuild', 'webneuro');
					$modif->MODIFIE($idvipbuild, array('vipactivite' , 'vipnombre' , 'sexe' , 'vipdate1' , 'vipdate2' , 'vipin' , 'vipout'));
			}
			#/## UPDATE type=update


			$s = $_POST['s'];
			#$s++;

			$include = "vip.php" ;
			$textehaut = $titre_02.', '.$vipdetail_02;
			$commande = 'non';
		break;
############## affichage Pour RETOUR job Vipjob  PAGE Annexes #########################################
		case "vipmodif2a": 
			$idwebvipjob = $_POST['idwebvipjob'];
			$s = 2;

			$include = "vip.php" ;
			$textehaut = $titre_02.', '.$vipdetail_01;
			$commande = 'non';
		break;

############## affichage et modif job Vipjob  PAGE 2 Activités VISUEL #########################################
		case "vipmodif2": 
			$idwebvipjob = $_POST['idwebvipjob'];

			$s = $_POST['s'];
			#$s++;

			$include = "vip.php" ;
			$textehaut = $titre_02.', '.$vipdetail_02;
			$commande = 'non';
		break;
############## affichage et modif job Vipjob  PAGE 1 Infos Générales #########################################
		case "vipmodif3": 

			### CLIENT
			if ($_SESSION['new'] == 1) {
				$_POST['etat'] = 1; # Client
				$_POST['secteur'] = 'vip';
				$modif = new db('webclient', 'idwebclient', 'webneuro');
				$modif->MODIFIE($idwebclient, array('etat', 'secteur'));
			}
			### WEBVIPJOB
			$_POST['datecommande'] = date("Y-m-d G:i:s");
			$idwebvipjob = $_POST['idwebvipjob'];
			$_POST['etat'] = 5; # JOB
			$modif = new db('webvipjob', 'idwebvipjob', 'webneuro');
			$modif->MODIFIE($idwebvipjob, array('etat', 'datecommande'));


			$s = $_POST['s'];
			$s++;
			$_SESSION['newvip'] = 'closed';

			$include = "vip.php" ;
			$textehaut = $titre_02.', '.$vipdetail_01;
			$commande = 'non';
		break;

############## affichage TABLEAU ACTIONS job Vip  #########################################
		case "vipaction0": 

			$include = "viplist-action.php" ;
			$textehaut = $titre_03;
			$action = 'non';
		break;

############## affichage TABLEAU ACTIONS job Vip  #########################################
		case "viparchive0": 

			$include = "viplist-action.php" ;
			$textehaut = $titre_04;
			$archive = 'non';
		break;

############## affichage et modif job Vipjob  PAGE 2 Activités VISUEL pour job neuroweb #########################################
		case "vipviewweb": 

			$idwebvipjob = $_GET['idwebvipjob'];
			#$s++;

			$include = "vip-view-web.php" ;
			$textehaut = $titre_05;
		break;

############## affichage et modif job Vipjob  PAGE 2 Activités VISUEL pour job neuroweb #########################################
		case "vipview": 

			$idvipjob = $_GET['idvipjob'];
			#$s++;

			$include = "vip-view.php" ;
			$textehaut = $titre_06;
		break;

############## Web Delete #############################################
		case "webdelete": 
			$idwebvipjob = $_POST['idwebvipjob'];
			$did = $_POST['idwebvipjob'];

			############## Delete des fichier annexé de ce job de ce JOB ################################
				$path = Conf::read('Env.root').'media/annexe/vipweb/';
				$ledir = $path;
				$d = opendir ($ledir);
				$nomvip = 'vip-'.$idwebvipjob.'-';
				while ($name = readdir($d)) {
					if (
						($name != '.') and 
						($name != '..') and 
						($name != 'index.php') and 
						($name != 'index2.php') and 
						($name != 'temp') and
						(strchr($name, $nomvip))
					) {
						if (!empty($name)) {
							if(!unlink("$ledir$name")) die ("this file cannot be delete");
						}
					}
				}
				closedir ($d);

			############## Delete des webvipbuild de ce JOB ################################
				$jobdel1 = new db('webvipbuild', 'idvipbuild', 'webneuro');
				$sqldel1 = "DELETE FROM `webvipbuild` WHERE `idwebvipjob` = $idwebvipjob;";
				$jobdel1->inline($sqldel1);

			############## PAS DE DEVIS + DEL ###########################################
				$jobdel2 = new db('webvipjob', 'idwebvipjob ', 'webneuro');
				$sqldel2 = "DELETE FROM `webvipjob` WHERE `idwebvipjob` = $idwebvipjob;";
				$jobdel2->inline($sqldel2);

			$action = 'non';
			$_SESSION['newvip'] = '';
			$textehaut = $titre_03;
			$include = "viplist-action.php" ;
		break;

############## Menu  #########################################
		case "menu": 
		default: 
			$textehaut = 'Bienvenu dans l&rsquo;espace s&eacute;curis&eacute; d&rsquo;Exception';

			$PhraseBas = 'Menu Clients';
			$include = "../menu.php" ;
			$commande = 'non';
			$action = 'non';
			$archive = 'non';
}
			$textehaut0 = $titre_02.', '.$vipdetail_02;
			$textehaut1 = $titre_02.', '.$vipdetail_04;
			$textehaut2 = $titre_02.', '.$vipdetail_03;
			$textehaut3 = $titre_02.', '.$vipdetail_05;
			$textehaut4 = $titre_02.', '.$vipdetail_01;

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

#/##si retour à page précedente

include NIVO."webclient/includes/up.php" ;
include $include ;


?>
<?php
# Pied de Page
include NIVO."webclient/includes/ppied.php" ;
?>