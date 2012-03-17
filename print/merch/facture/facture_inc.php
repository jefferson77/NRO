<?php

require_once(NIVO."print/dispatch/dispatch_functions.php");

function path_fac_merch($idfacture,$mode) {
	global $DB;

	switch($mode)
	{
		case "FAC":
			$annee = $DB->getOne("SELECT YEAR(datefac) FROM facture WHERE idfac = ".$idfacture);
			## declarations

		    $lespath['dossier'] = Conf::read('Env.root').'document/facture/'.$annee.'/';
		    $lespath['filename'] = $idfacture.'.pdf';
		    $lespath['full'] = $lespath['dossier'].$lespath['filename'];
		    $lespath['history'] = Conf::read('Env.root').'history/facture/'.$annee.'/';

		    if (file_exists($lespath['full'])) $lespath['historyfull'] = $lespath['history'].$idfacture.'-'.date ("Ymd.Hi", filemtime($lespath['full'])).'.pdf';

			## check et creation si besoin
		    if(!is_dir($lespath['dossier'])) mkdir($lespath['dossier'], 0777, true); #le 'true' c'est pour le recursif
		    if(!is_dir($lespath['history'])) mkdir($lespath['history'], 0777, true);
		break;
		case "PREME":

			$lespath['dossier'] = Conf::read('Env.root').'document/temp/merch/prefacture/';
		    $lespath['filename'] = 'prefacture-me-'.date("Ymd").'.pdf';
		    $lespath['full'] = $lespath['dossier'].$lespath['filename'];

			if(!is_dir($lespath['dossier'])) mkdir($lespath['dossier'], 0777, true); #le 'true' c'est pour le recursif
		break;
	}

    return $lespath;
}

/*

$mode = '' ou 'PREME'

*/

function print_fac_merch($idfac, $entete='yes',$duplic,$mode)
{
	global $DB;

	$fac = new facture($idfac,$mode); #  "FAC" par défault

	################### Phrasebook ########################
	switch ($fac->langue) {
			case "NL":
				include NIVO.'print/merch/facture/nl.php';
				setlocale(LC_TIME, 'nl_NL');
			break;
			case "FR":
				include NIVO.'print/merch/facture/fr.php';
				setlocale(LC_TIME, 'fr_FR');
			break;
			default:
				$phrase = array('');
				echo '<br> Langue pas définie pour le client : '.$fac->idclient." ".$fac->societe;

	}
	################### Phrasebook ########################

	$path = path_fac_merch($idfac,$mode);
	if($mode == "FAC")
	{
		if (file_exists($path['full'])) rename($path['full'], $path['historyfull']);
	}

	#####################
	# Path PDF

	$pdf = pdf_new();
	pdf_open_file($pdf, $path['full']); # définit l'emplacement de la sauvegarde
	# Infos pour le document
	pdf_set_info($pdf, "Author", $infos['prenom'].' '.$infos['nomagent']);
	pdf_set_info($pdf, "Title", $path['filename']);
	pdf_set_info($pdf, "Creator", "NEURO");
	pdf_set_info($pdf, "Subject", "Merch Facture");

	######## Variables de taille  ###############
	$LargeurPage = 595; # Largeur A4
	$HauteurPage = 842; # Hauteur A4
	$MargeLeft   = 30;
	$MargeRight  = 30;
	$MargeTop    = 30;
	$MargeBottom = 30;


	$np = 1; # Numeéro de la première page
	######## Variables de taille  ###############
	$LargeurUtile = $LargeurPage - $MargeRight - $MargeLeft;
	$HauteurUtile = $HauteurPage - $MargeTop - $MargeBottom;


	include NIVO.'print/merch/facture/pagegarde.php';
	include NIVO.'print/merch/facture/pagedetail.php';

	pdf_end_document($pdf, '');
	pdf_delete($pdf); # Efface le fichier en mémoire
	return $path['full'];
}

?>