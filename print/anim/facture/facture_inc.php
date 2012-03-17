<?php

#####################
# Path PDF

function path_fac_anim($idfacture,$mode) {
	global $DB;
		
	switch($mode)
	{
		case "FACTURE" : 
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
		case "PREFAC" : 
			$lespath['dossier'] = Conf::read('Env.root').'document/temp/anim/prefacture/';
			$lespath['filename'] = 'prefacture-an-'.date("Ymd").'.pdf';
			$lespath['full'] = $lespath['dossier'].$lespath['filename'];
			
			if(!is_dir($lespath['dossier'])) mkdir($lespath['dossier'], 0777, true); #le 'true' c'est pour le recursif
		break;
		case "OFFRE":
			$lespath['dossier'] = Conf::read('Env.root').'document/offre/anim/';
			$lespath['filename'] = 'offre-'.prezero($idfacture,5).'-'.date("Ymd").'.pdf';
			$lespath['full'] = $lespath['dossier'].$lespath['filename'];
			
			if(!is_dir($lespath['dossier'])) mkdir($lespath['dossier'], 0777, true); #le 'true' c'est pour le recursif
		break;
	}
	

    return $lespath;
}


function print_fac_anim($idfac,$entete = 'yes',$duplic,$mode)
{
	global $DB;
	global $infos;
		
	$path = path_fac_anim($idfac,$mode);
	
	if($mode == "FACTURE")
	{
		if (file_exists($path['full'])) rename($path['full'], $path['historyfull']);
	}
	
	$LargeurPage = 595; # Largeur A4
	$HauteurPage = 842; # Hauteur A4
	$MargeLeft = 30;
	$MargeRight = 30;
	$MargeTop = 30;
	$MargeBottom = 30;
	$LargeurUtile = $LargeurPage - $MargeRight - $MargeLeft;
	$HauteurUtile = $HauteurPage - $MargeTop - $MargeBottom;

	$np = 1; # Numéro de la première page
	
	$pdf = pdf_new();
	pdf_open_file($pdf, $path['full']); # définit l'emplacement de la sauvegarde
	# Infos pour le document
	pdf_set_info($pdf, "Author", $infos['prenom'].' '.$infos['nomagent']);
	pdf_set_info($pdf, "Title", $nompdf);
	pdf_set_info($pdf, "Creator", "NEURO");
	pdf_set_info($pdf, "Subject", "Anim Facture");
	
	if($mode != "OFFRE")
	{
		$fac = new facture($idfac);
	}
	else
	{
		$infos = $DB->getRow("SELECT
					j.idanimjob, j.idagent, j.reference, j.boncommande, 
					a.nom AS nomagent, a.prenom AS prenomagent, a.atel, a.agsm,
					c.societe, c.adresse, c.cp, c.ville, c.pays, c.tel, c.fax, 
					co.qualite, co.onom, co.oprenom
					FROM animjob j
					LEFT JOIN agent a ON j.idagent = a.idagent
					LEFT JOIN client c ON j.idclient = c.idclient
					LEFT JOIN cofficer co ON j.idcofficer = co.idcofficer
				WHERE j.idanimjob = ".$idfac);
	}
	
	$facan = new facanim($mode, $idfac);
    
    $printinfo = 'garde';
	include 'pagegarde.php';
	
	$printinfo = 'detail';
	include 'pagedetail.php';
	
	pdf_end_document($pdf, '');
	pdf_delete($pdf); # Efface le fichier en mémoire
	
	return $path['full'];
}

?>