<?php

function path_fac_vip($idfacture) {
	global $DB;
		
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

    return $lespath;
}


function print_fac_vip($idfacture, $entete = 'yes', $duplic, $prefac)
{
	global $DB;
#####################
# Path PDF
if (!empty($prefac)) {
	$pathpdf = 'document/temp/vip/prefacture/';
	$nompdf = $idfacture.'.pdf';
	$path['full'] = Conf::read('Env.root').$pathpdf.$nompdf;
} else {
	
	$path = path_fac_vip($idfacture);
	
	if (file_exists($path['full'])) rename($path['full'], $path['historyfull']);
}

$pdf = pdf_new();
$path_pdf = $path['full'];
pdf_open_file($pdf, $path_pdf); # définit l'emplacement de la sauvegarde
# Infos pour le document
pdf_set_info($pdf, "Author", $infos['prenom'].' '.$infos['nomagent']);
pdf_set_info($pdf, "Title", $path['filename']);
pdf_set_info($pdf, "Creator", "Celsys-NEURO");
pdf_set_info($pdf, "Subject", "Vip Facture");

######## Variables de taille  ###############
$LargeurPage = 595; # Largeur A4
$HauteurPage = 842; # Hauteur A4
$MargeLeft = 30;
$MargeRight = 30;
$MargeTop = 30;
$MargeBottom = 30;

$np = 1; # Numéro de la première page
######## Variables de taille  ###############
$LargeurUtile = $LargeurPage - $MargeRight - $MargeLeft;
$HauteurUtile = $HauteurPage - $MargeTop - $MargeBottom;

################### Début Recherche de toutes les factures à imprimer ########################
if (!empty($prefac)) {
	$fac = new facture($idfacture, 'PREVI');
} else {
	$fac = new facture($idfacture);
}
	
	################### Phrasebook ########################
	switch ($fac->langue) {
			case "NL": 
				include 'nl.php';
				setlocale(LC_TIME, 'nl_NL');
			break;
			case "FR": 
				include 'fr.php';
				setlocale(LC_TIME, 'fr_FR');
			break;
			default:
				$phrase = array('');
				echo '<br> Langue pas d&eacute;finie pour le client : '.$fac->idclient." ".$fac->societe;
	}

	# Page d'entete ###########
		include 'f-pagegarde.php';
		include 'f-pagedetail.php';
		pdf_end_document($pdf, '');
		pdf_delete($pdf); # Efface le fichier en mémoire
	# Fin Page d'entete #######

	return $path_pdf;
	
}



?>
