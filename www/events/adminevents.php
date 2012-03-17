<?php
define('NIVO', '../../'); 

# entete
$Titre = 'Web Events';
$PhraseBas = 'Liste des Events';

include NIVO."includes/entete.php";

require_once NIVO."nro/xlib/phpthumb/ThumbLib.inc.php";

# Config
$photoroot = Conf::read('Env.root').'media/events/';

switch ($_REQUEST['act']) {
	## Suppression de l'Event ##############################
	case 'del':
		if ($_REQUEST['idwebevent'] > 0) {
			$DB->inline("DELETE FROM webneuro.webevents WHERE idwebevent = ".$_REQUEST['idwebevent']." LIMIT 1");
			$notify[] = "Event n&deg; ".$_REQUEST['idwebevent']." effac&eacute;e";
		}
		
		/*
			TODO : Suppression (archivage des photos)
		*/

		include 'v-list.php';
	break;

	## Ajout de l'Event #####################################
	case 'Ajouter':
		$Addid = $DB->ADD('webneuro.webevents');
		$notify[] = "Event n° ".$Addid." ajout&eacute;e";
		include 'v-list.php';
	break;

	## Modification de l'Event ##############################
	case 'Modifier':
		$DB->MAJ('webneuro.webevents');
		$notify[] = "Fiche ".$_REQUEST['idwebevent']." mise &agrave; jour";
		include 'v-list.php';
	break;

	## Upload d'une Photo ####################################
	case 'addphoto':
		if ($_REQUEST['idwebevent'] > 0) {
			## Upload de la Photo
			$dossier = $photoroot.$_REQUEST['idwebevent'].'/';
			if(!is_dir($dossier)) mkdir($dossier, 0777, true);
			
			$lesfiles = dirFiles($dossier, 'raw');
			if(is_array($lesfiles)) $lastfile =  array_pop($lesfiles);
			
			if (!empty($lastfile)) {
				$f = pathinfo($lastfile);
				$nextfile = prezero((substr($f['filename'], 0, 2) + 1), 2).'raw';
			} else {
				$nextfile = '01raw';
			}

			$extensions = array('.png', '.gif', '.jpg', '.jpeg');
			$extension = strtolower(strrchr($_FILES['fichier']['name'], '.')); 

			if(in_array($extension, $extensions)) {
				if(move_uploaded_file($_FILES['fichier']['tmp_name'], $dossier.$nextfile.$extension)) {
					## Resize de la photo
					$thumb = PhpThumbFactory::create($dossier.$nextfile.$extension);
					$thumb->resize(100, 100);
					$thumb->save($dossier.(substr($nextfile, 0, 2)).'thumb.png', 'png');
					
					## resize
					$thumb = PhpThumbFactory::create($dossier.$nextfile.$extension);
					$thumb->resize(800, 800);
					$thumb->save($dossier.(substr($nextfile, 0, 2)).'sized.jpg', 'jpg');

					$notify[] = 'Photo '.$nextfile.' envoyée';
				} else {
					$error[] = "Echec de l\'upload !";
				}
			} else {
				$error[] = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg';
			} 
		} else {
			$error[] = "Aucun idEvent Spécifié";
		}

		include 'v-detail.php';
	break;
	
	## Affichage de l'Event ##################################
	case 'add':
	case 'show':
		include 'v-detail.php';
	break;
	
	## Affichage de la liste d'Event ##################################
	default:
		include 'v-list.php';
	break;
}

include NIVO."includes/pied.php";
?>