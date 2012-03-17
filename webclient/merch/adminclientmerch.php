<?php
define('NIVO', '../../'); 

# Classes utilisées
include_once NIVO."classes/merch.php" ;

# Entete de page
include NIVO."webclient/includes/entete.php" ;

$secteurexception = 'merch';

$onemonthago	= date ("Y-m-01", strtotime("+1 day")); 

### langue 
if ($_SESSION['lang'] == '') $_SESSION['lang'] = 'fr';
$pagevar = NIVO.'webclient/var'.strtolower($_SESSION['lang']).'.php';
include $pagevar;
#/## langue 

$Titre = $titre_00.' / MERCH';

### variables pour menu haut ###
	$commande = 'non';
	$action = 'non';
	$archive = 'non';
	$sales = 'oui';
#/ ## variables pour menu haut ###

switch ($_GET['act']) {
############## planning des Job Results #############################################
		case "planning": 
			if (($_GET['listing'] == 'direct') OR ($_GET['listing'] == 'missing')) {
				$_SESSION['view'] = 'n';

				$_SESSION['archive'] = 'normal';
				$_GET['listtype'] = '0';
			}	
			switch ($_SESSION['archive']) {
				#### Normal
				case "normal":
					if ($_SESSION['view'] == 'f') { 
					} else {
						$include = "planning.php" ;
						$PhraseBas = 'Planning des Merch';
					}
				break;
			}
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "ccfd", "Rech Fac", "Liste Facture", "Historic");
		break;
############## planning des Missions Search #############################################
		case "planningweek": 
			$_SESSION['archive'] = 'normal' ; 
			$_SESSION['view'] = 'n' ; 
			$include = "planningweek.php" ;

			$PhraseBas = 'planning des Merch par semaine';
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "ccfd", "Rech Fac", "Liste Facture", "Historic");
		break;
####
############## planning des Missions Search #############################################
		case "merchplanningsearch": 
		case "sales": 
		default: 
			$_SESSION['archive'] = 'normal' ; 
			$_SESSION['view'] = 'n' ; 
			$include = "planningsearch.php" ;
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "ccfd", "Rech Fac", "Liste Facture", "Historic");

			$PhraseBas = 'planning des Merch';
		break;
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


# Pied de Page
include NIVO."webclient/includes/ppied.php" ;
?>