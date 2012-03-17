<?php
define('NIVO', '../../'); 

# entete
$Titre = 'WEB NEWS';
$PhraseBas = 'Liste des News';

include NIVO."includes/entete.php";

switch ($_REQUEST['act']) {
	## Suppression de la ligne #############
	case 'del':
		if ($_REQUEST['idwebnews'] > 0) {
			$DB->inline("DELETE FROM webneuro.webnews WHERE idwebnews = ".$_REQUEST['idwebnews']." LIMIT 1");
			$notify[] = 'News n&deg; '.$_GET['idwebnews'].' effac&eacute;e';
		}
		include 'v-list.php';
	break;

	## Ajout de la ligne #############
	case 'Ajouter':
		$DB->ADD('webneuro.webnews');
		$notify[] = 'Fiche news '.$DB->addid.' ajoutée';
		include 'v-list.php';
	break;

	## Modification de la ligne #############
	case 'Modifier':
		$DB->MAJ('webneuro.webnews');
		$notify[] = 'Fiche news '.$_REQUEST['idwebnews'].' mise &agrave; jour';
		include 'v-list.php';
	break;

	## Modification de la ligne #############
	case 'add':
	case 'show':
		include 'v-detail.php';
	break;
	
	default:
		include 'v-list.php';
	break;
}

include NIVO."includes/pied.php";

?>