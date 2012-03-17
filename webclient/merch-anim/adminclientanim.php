<?php
define('NIVO', '../../'); 

# TODO : Argh PABO code : a relier a NIVO
$section = '../';
$print = '../../print/';
$document = '../../document/';
$illus = STATIK.'illus/';
$secteurexception = 'anim';

# Classes utilisées
include NIVO."classes/anim.php" ;

# Entete de page
$Style = 'standard';
include NIVO."webclient/includes/entete.php" ;

### langue 
if ($_SESSION['lang'] == '') {$_SESSION['lang'] = 'fr';}
$pagevar = NIVO.'webclient/var'.strtolower($_SESSION['lang']).'.php';
include $pagevar;
#/## langue 

$Titre = $titre_00.' / ANIM';

### Variable de Base : $idmembre & $s
if (isset($_GET['idwebanimjob'])) {
	#$s = $_GET['s'];
	$idwebanimjob = $_GET['idwebanimjob'];
}
if (isset($_POST['idwebanimjob'])) {
	#$s = $_POST['s'];
	$idwebanimjob = $_POST['idwebanimjob'];
}
#/ ## Variable de Base : $idmembre & $s

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
############## affichage nouveau job anim CREATION  #########################################
		case "anim0": 
			### ajout de fiche vide avec 'etat', 'idclient' , 'new'
			$_POST['etat'] = 0;
			$_POST['new'] = $new;
			$_POST['idclient'] = $_SESSION['idclient'];
			$_POST['idwebclient'] = $_SESSION['idwebclient'];
			$_POST['idcofficer'] = $_SESSION['idcofficer'];
				$ajout = new db('webanimjob', 'idwebanimjob', 'webneuro');
				### client webnew 
					if (!empty($_SESSION['idwebclient'])) {  
						$ajout->AJOUTE(array('etat', 'idwebclient', 'idcofficer', 'new')); 
						$_SESSION['newanim'] = $ajout->addid;
						$_SESSION['newanim'] = 'closed';
						$_SESSION['newmerch'] = 'closed';
					} 
				### client Exception
					else { $ajout->AJOUTE(array('etat', 'idclient', 'idcofficer', 'new')); } 
			$idwebanimjob = $ajout->addid;
			$_POST['idwebanimjob'] = $ajout->addid;
			#/## ajout de fiche vide avec 'etat', 'idclient' 
					$_POST['animactivite'] = "70";
					$ajout = new db('webanimbuild', 'idanimbuild', 'webneuro');
					$ajout->AJOUTE(array('metat', 'idwebanimjob', 'animactivite'));
			$include = "anim.php" ;
			$textehaut = $titre_02.', '.$animdetail_01;
			$commande = 'non';
		break;
############## affichage nouveau job anim RECUOPERATION POUR CLIENT NEW #########################################
		case "anim0a": 
			### ajout de fiche vide avec 'etat', 'idclient' , 'new'
			$_POST['etat'] = 0;
			$_POST['new'] = $new;
			$_POST['idclient'] = $_SESSION['idclient'];
			$_POST['idwebclient'] = $_SESSION['idwebclient'];
			$_POST['idcofficer'] = $_SESSION['idcofficer'];

			$idwebanimjob = $_SESSION['newanim'];
			$_POST['idwebanimjob'] = $_SESSION['newanim'];
			$include = "anim.php" ;
			$textehaut = $titre_02.', '.$animdetail_01;
			$commande = 'non';
		break;

############## affichage et modif job animjob  PAGE 1 Infos Générales #########################################
		case "animmodif0": 

			$idwebanimjob = $_POST['idwebanimjob'];

			$modif = new db('webanimjob', 'idwebanimjob', 'webneuro');
			$modif->MODIFIE($idwebanimjob, array('reference' , 'notejob' , 'bondecommande'));

			$s = $_POST['s'];
			$s++;

			$include = "anim.php" ;
			$textehaut = $titre_02.', '.$animdetail_01;
			$commande = 'non';
		break;
############## affichage Pour RETOUR job animjob  PAGE 1 Infos Générales #########################################
		case "animmodif0a": 
			$idwebanimjob = $_GET['idwebanimjob'];
			if (!empty($_POST['idwebanimjob'])) {$idwebanimjob = $_POST['idwebanimjob'];}
			$s = 0;

			$include = "anim.php" ;
			$textehaut = $titre_02.', '.$animdetail_01;
			$commande = 'non';
		break;
############## affichage et modif job animjob  PAGE 2 Points of sales #########################################
		case "animmodif1": 
			$s = $_POST['s'];
			$idwebanimjob = $_POST['idwebanimjob'];

			### recréation de la sélection
			switch ($_GET['section']) {
			############## affichage nouveau job anim POINT OF SALES : historique + search ############################
				case "searchadd": 
				case "search": 
				case "historique": 
					### recréation de la sélection
						### ancienne selection
						$_POST['shopselection'] = str_replace('-%%', '-', $_POST['shopselection']);
						$posgroup = $_POST['shopselection'];
						$sh = explode("-", $posgroup);
						foreach ($sh as $val) {
							$val = trim($val);
							$pos[] = $val.'-';
						}
						#/# ancienne selection shopselectionsearch
						### selection search
						if (!empty($_POST['shopselectionsearch'])) {
							$_POST['shopselectionsearch'] = str_replace('-%%', '-', $_POST['shopselectionsearch']);
							foreach ($_POST['shopselectionsearch'] as $val) {
								$val = trim($val);
								$pos[] = $val;
							}
						}
						### selection historique
						if (!empty($_POST['shophistorique'])) {
							$_POST['shophistorique'] = str_replace('-%%', '-', $_POST['shophistorique']);
							foreach ($_POST['shophistorique'] as $val) {
								$val = trim($val);
								$pos[] = $val;
							}
						}
		
						$pos = str_replace('%%', '--', $pos);
						$pos = str_replace('--', '-', $pos);
						$_POST['shopselection'] = array_unique ($pos);
					#/## recréation de la sélection
				
					$modif = new db('webanimjob', 'idwebanimjob', 'webneuro');
					$modif->MODIFIE($idwebanimjob, array('shopselection'));
				break;
			#/############# affichage nouveau job anim POINT OF SALES : historique  + search ############################
			############## affichage nouveau job anim POINT OF SALES : selection modif ############################
				case "selection": 
					$modif = new db('webanimjob', 'idwebanimjob', 'webneuro');
					$modif->MODIFIE($idwebanimjob, array('shopselection'));
				break;
			#/############# affichage nouveau job anim POINT OF SALES : selection modif ############################
				default: 
					$s++;
			}
			$include = "anim.php" ;
			$textehaut = $titre_02.', '.$animdetail_01;
			$commande = 'non';
		break;
############## affichage Pour RETOUR job animjob  PAGE 1 Points of sales #########################################
		case "animmodif1a": 
			$idwebanimjob = $_GET['idwebanimjob'];
			if (!empty($_POST['idwebanimjob'])) {$idwebanimjob = $_POST['idwebanimjob'];}
			if (!empty($_GET['idwebanimjob'])) {$idwebanimjob = $_GET['idwebanimjob'];}
			$s = 1;

			$include = "anim.php" ;
			$textehaut = $titre_02.', '.$animdetail_01;
			$commande = 'non';
		break;

############## affichage Pour RETOUR job animjob  PAGE 1 AJOUT d'un Points of sales dans WEB SHOP #########################################
		case "animmodif1add": 
			$idwebanimjob = $_GET['idwebanimjob'];
			if (!empty($_POST['idwebanimjob'])) {$idwebanimjob = $_POST['idwebanimjob'];}
			if (!empty($_GET['idwebanimjob'])) {$idwebanimjob = $_GET['idwebanimjob'];}
			$s = 1;

			if ((!empty($_POST['societe'])) and (!empty($_POST['cp'])) and (!empty($_POST['ville'])) and (!empty($_POST['adresse']))) {
				$_POST['newweb'] = 1;
				$ajout = new db('shop', 'idshop');
				$ajout->AJOUTE(array('societe', 'cp', 'ville', 'adresse', 'newweb'));
				$idshop = $ajout->addid;

					### recréation de la sélection NEW
						### ancienne selection new
						$_POST['shopselection'] = str_replace('-%%', '-', $_POST['shopselection']);
						$posgroup = $_POST['shopselection'];
						$sh = explode("-", $posgroup);
						foreach ($sh as $val) {
							$val = trim($val);
							$pos[] = $val.'-';
						}
						#/# ancienne selection new
						$pos[] = $idshop.'-';
		
						#/## selection search
						$pos = str_replace('%%', '--', $pos);
						$pos = str_replace('--', '-', $pos);
						$_POST['shopselection'] = array_unique ($pos);
					#/## recréation de la sélection NEW
					$modif = new db('webanimjob', 'idwebanimjob', 'webneuro');
					$modif->MODIFIE($idwebanimjob, array('shopselection'));

				$_GET['section'] = '';
			}
			$include = "anim.php" ;
			$textehaut = $titre_02.', '.$animdetail_01;
			$commande = 'non';
		break;
############## affichage Pour RETOUR job animjob  PAGE 3 Activités #########################################
		case "animmodif2a": 
			$idwebanimjob = $_POST['idwebanimjob'];
			$s = 2;

			$include = "anim.php" ;
			$textehaut = $titre_02.', '.$animdetail_01;
			$commande = 'non';
		break;

############## affichage et modif job animjob  PAGE 3 Activités #########################################
		case "animmodif2": 

			$idwebanimjob = $_POST['idwebanimjob'];
			$idanimbuild = $_POST['idanimbuild'];

			$_POST['metat'] = 0;
			
			### AJOUT type=ajout
			if ($_GET['type'] == 'ajout') {
					$_POST['animactivite'] = "70";
					$ajout = new db('webanimbuild', 'idanimbuild', 'webneuro');
					$ajout->AJOUTE(array('metat', 'idwebanimjob', 'animactivite'));
					$idanimbuild = $ajout->addid;
			}
			#/## AJOUT type=ajout

			### AJOUT type=Delete
			if ($_GET['type'] == 'Delete') {
					$idanimbuild = $_POST['idanimbuild'];
					$jobdel = new db('webanimbuild', 'idanimbuild', 'webneuro');
					$sqldel = "DELETE FROM `webanimbuild` WHERE `idanimbuild` = $idanimbuild;";
					$jobdel->inline($sqldel);
			}
			#/## AJOUT type=Delete

			### UPDATE type=update
			if ($_GET['type'] == 'update') {
					$_POST['animdate1'] = fdatebk($_POST['animdate1']);
					$_POST['animdate2'] = fdatebk($_POST['animdate2']);
					if ($_POST['animdate2'] == '') {$_POST['animdate2'] = $_POST['animdate1'];}
					if ($_POST['animnombre'] == 0) {$_POST['animnombre'] = 1;}
					if ($_POST['animdays'] == '') {$_POST['animdays'][] = 5; $_POST['animdays'][] = 6;}

					$_POST['animin1'] = ftimebk($_POST['animin1']);
					$_POST['animout1'] = ftimebk($_POST['animout1']);
					$_POST['animin2'] = ftimebk($_POST['animin2']);
					$_POST['animout2'] = ftimebk($_POST['animout2']);
					$idanimbuild = $_POST['idanimbuild'];
					$shopping = explode('-xsep-',$_POST['idshop']);
					$_POST['idshop'] = $shopping[0];
					$_POST['animactivite'] = $shopping[1];
					$modif = new db('webanimbuild', 'idanimbuild', 'webneuro');
					$modif->MODIFIE($idanimbuild, array('idshop', 'animactivite' , 'animnombre' , 'sexe' , 'animdate1' , 'animdate2' , 'animdays', 'animin1' , 'animout1', 'animin2' , 'animout2'));

					### Duplicate type=Duplicate
					if ($_POST['action'] != $tool_02) { ## tool_02 ==> Valider
							$idanimbuild = $_POST['idanimbuild'];
							$idwebanimjob = $_POST['idwebanimjob'];
							### search source	
								$animduplic = new db('webanimbuild', 'idanimbuild', 'webneuro');
								$animduplic->inline("SELECT * FROM `webanimbuild` WHERE `idanimbuild` = $idanimbuild");
								$infosduplic = mysql_fetch_array($animduplic->result) ; 
							#/## search source	
							$_POST['metat'] = $infosduplic['metat'];
							$ajout = new db('webanimbuild', 'idanimbuild', 'webneuro');
							$ajout->AJOUTE(array('idshop', 'metat', 'idwebanimjob', 'idshop', 'animactivite' , 'animnombre' , 'sexe' , 'animdate1' , 'animdate2' , 'animdays', 'animin1' , 'animout1', 'animin2' , 'animout2'));
					}
					#/## Duplicate type=Duplicate
			}
			#/## UPDATE type=update

			$s = $_POST['s'];
			#$s++;

			$include = "anim.php" ;
			$textehaut = $titre_02.', '.$animdetail_02;
			$commande = 'non';
		break;
############## affichage Pour RETOUR job animjob  PAGE Annexes #########################################
		case "animmodif3a": 
			$idwebanimjob = $_POST['idwebanimjob'];
			$s = 3;

			$include = "anim.php" ;
			$textehaut = $titre_02.', '.$animdetail_01;
			$commande = 'non';
		break;

############## affichage et modif job animjob  PAGE 2 Activités VISUEL #########################################
		case "animmodif3": 
			$idwebanimjob = $_POST['idwebanimjob'];

			$s = $_POST['s'];
			#$s++;

			$include = "anim.php" ;
			$textehaut = $titre_02.', '.$animdetail_02;
			$commande = 'non';
		break;
############## affichage et modif job animjob  PAGE 1 Infos Générales #########################################
		case "animmodif4": 

			### CLIENT
			if ($_SESSION['new'] == 1) {
				$_POST['etat'] = 1; # Client
				$_POST['secteur'] = 'anim';
				$modif = new db('webclient', 'idwebclient', 'webneuro');
				$modif->MODIFIE($idwebclient, array('etat', 'secteur'));
			}
			### WEBanimJOB
			$_POST['datecommande'] = date("Y-m-d G:i:s");
			$idwebanimjob = $_POST['idwebanimjob'];
			$_POST['etat'] = 5; # JOB
			$modif = new db('webanimjob', 'idwebanimjob', 'webneuro');
			$modif->MODIFIE($idwebanimjob, array('etat', 'datecommande'));


			$s = $_POST['s'];
			$s++;
			$_SESSION['newanim'] = 'closed';

			$include = "anim.php" ;
			$textehaut = $titre_02.', '.$animdetail_01;
			$commande = 'non';
		break;

############## affichage TABLEAU ACTIONS job anim  #########################################
		case "animaction0": 


			$include = "animlist-action.php" ;
			$textehaut = $titre_03;
			$action = 'non';
		break;

############## affichage TABLEAU ACTIONS job anim  #########################################
		case "animarchive0": 

			$include = "animlist-action.php" ;
			$textehaut = $titre_04;
			$archive = 'non';
		break;

############## affichage et modif job animjob  PAGE 2 Activités VISUEL pour job neuroweb #########################################
		case "animviewweb": 

			$idwebanimjob = $_GET['idwebanimjob'];
			#$s++;

			$include = "anim-view-web.php" ;
			$textehaut = $titre_05;
		break;

############## affichage et modif job animjob  PAGE 2 Activités VISUEL pour job neuroweb #########################################
		case "animview": 

			$idanimjob = $_GET['idanimjob'];
			#$s++;

			$include = "anim-view.php" ;
			$textehaut = $titre_06;
		break;

############## Web Delete #############################################
		case "webdelete": 
			$idwebanimjob = $_POST['idwebanimjob'];
			$did = $_POST['idwebanimjob'];

			############## Delete des fichier annexé de ce job de ce JOB ################################

				$path = Conf::read('Env.root').'media/annexe/animweb/';
				$ledir = $path;
				$d = opendir ($ledir);
				$nomanim = 'anim-'.$idwebanimjob.'-';
				while ($name = readdir($d)) {
					if (
						($name != '.') and 
						($name != '..') and 
						($name != 'index.php') and 
						($name != 'index2.php') and 
						($name != 'temp') and
						(strchr($name, $nomanim))
					) {
						if (!empty($name)) {
							if(!unlink("$ledir$name")) die ("this file cannot be delete");
						}
					}
				}
				closedir ($d);

			############## Delete des webanimbuild de ce JOB ################################
				$jobdel1 = new db('webanimbuild', 'idanimbuild', 'webneuro');
				$sqldel1 = "DELETE FROM `webanimbuild` WHERE `idwebanimjob` = $idwebanimjob;";
				$jobdel1->inline($sqldel1);

			############## PAS DE DEVIS + DEL ###########################################
				$jobdel2 = new db('webanimjob', 'idwebanimjob ', 'webneuro');
				$sqldel2 = "DELETE FROM `webanimjob` WHERE `idwebanimjob` = $idwebanimjob;";
				$jobdel2->inline($sqldel2);

			$action = 'non';
			$_SESSION['newanim'] = '';
			$textehaut = $titre_03;
			$include = "animlist-action.php" ;
		break;

############## ANIM : RAPPORT DE VENTES SEARCH #########################################
		case "animvente": 
			$include = "anim-vente-search.php" ;
			$textehaut = $titre_07;
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
			$textehaut0 = $titre_02.', '.$animdetail_02;
			$textehaut1 = $titre_02.', '.$animdetail_04;
			$textehaut2 = $titre_02.', '.$animdetail_03;
			$textehaut3 = $titre_02.', '.$animdetail_05;
			$textehaut4 = $titre_02.', '.$animdetail_01;

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