<?php 

function path_note_credit($idnote)
{

## declarations

    $lespath['dossier'] = Conf::read('Env.root').'document/notecredit/';
    $lespath['filename'] = $idnote.'.pdf';
    $lespath['full'] = $lespath['dossier'].$lespath['filename'];
    $lespath['history'] = Conf::read('Env.root').'history/notecredit/';

    if (file_exists($lespath['full'])) $lespath['historyfull'] = $lespath['history'].$idnote.'-'.date ("Ymd.Hi", filemtime($lespath['full'])).'.pdf';

## check et creation si besoin
    if(!is_dir($lespath['dossier'])) mkdir($lespath['dossier'], 0777, true); #le 'true' c'est pour le recursif
    if(!is_dir($lespath['history'])) mkdir($lespath['history'], 0777, true); 

    return $lespath;
}

function print_note_credit($idnote, $entete = 'yes', $duplic)
{
	global $DB;
	// ici path_note_credit
	
	$path = path_note_credit($idnote);
	if (file_exists($path['full'])) rename($path['full'], $path['historyfull']);

	//$pathpdf = 'document/notecredit/';
	//$nompdf = 'NC-'.$idnote.'-'.date("Ymd").'.pdf';
	
	$pdf = pdf_new();
	pdf_open_file($pdf, $path['full']); # définit l'emplacement de la sauvegarde
	
	# Infos pour le document
	pdf_set_info($pdf, "Author", "NEURO");
	pdf_set_info($pdf, "Title", "Note Credit");
	pdf_set_info($pdf, "Creator", "NEURO");
	pdf_set_info($pdf, "Subject", "Note Credit");
	
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
	
	################### Début Recherche de toutes les Notes de crédit ########################
	$DB->inline("SET NAMES latin1");
	$ncinfs = $DB->getArray("SELECT 
			nc.datefac, nc.idfac, nc.intitule,
			f.secteur, f.idclient, 
			c.societe, c.tva, c.codetva, c.adresse, c.cp, c.ville, c.pays, c.astva, 
			o.langue
		FROM credit nc 
			LEFT JOIN facture f ON f.idfac = nc.facref
			LEFT JOIN client c ON f.idclient = c.idclient
			LEFT JOIN cofficer o ON f.idcofficer = o.idcofficer
		WHERE nc.idfac = $idnote 
		ORDER BY f.secteur ASC");
	
	foreach ($ncinfs as $ncinf) {
		$exx = explode('-', $ncinf['datefac']);
		$leyear = $exx[0];
		$ladate = fdate($ncinf['datefac']);
	
		################### Phrasebook ########################
		switch ($ncinf['langue']) {
				case "NL": 
					include NIVO.'print/commun/nl.php';
					setlocale(LC_TIME, 'nl_NL');
					$lposte = 'PostesNL';
				break;
				case "FR": 
					include NIVO.'print/commun/fr.php';
					setlocale(LC_TIME, 'fr_FR');
					$lposte = 'PostesFR';
				break;
				default:
					$phrase = array('');
					echo '<br> Langue pas d&eacute;finie pour le client : '.$ncinf['idclient']." ".$ncinf['societe'];
		}
	
		$ncdetails = $DB->getArray("SELECT * FROM `creditdetail` WHERE `idfac` = ".$ncinf['idfac']." ORDER BY poste ASC");
		$nbrdetail = count($ncdetails);
	
		$u = 0;
		foreach ($ncdetails as $row) {
			$tttable[$u]['desc'] = $row['description'];
			$tttable[$u]['poste'] = $Postes[$row['poste']];
			$tttable[$u]['montant'] = $row['montant'];
			
			$u++;
			$MontHTVA += $row['montant'];
		}
		/*
			TODO : ASTVA check
		*/
	
		if (($ncinf['astva'] == '7') or ($ncinf['astva'] == '4')) { 
			$MontTVA = 0.21 * $MontHTVA;
		} else {
			$MontTVA = 0;
		}
		$MontTTC = $MontHTVA + $MontTVA;
	
	### Declaration des fontes
	$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");
	$TimesBold = PDF_load_font($pdf, "Times-Bold", "host", "");
	$TimesItalic = PDF_load_font($pdf, "Times-Italic", "host", "");
	
	pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
	PDF_create_bookmark($pdf, $phrase[3].$np, "");
	
	pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche
	
	if (!empty($duplic)) {
		# duplicata
		$duplicata = $_SERVER["DOCUMENT_ROOT"]."/print/illus/duplicata.jpg";	
		$duplicata = PDF_load_image($pdf, "jpeg", $duplicata, "");
		pdf_place_image($pdf, $duplicata, 175, 730, 0.35);
	}
	
	###### logo exception ######################################################
	if (!empty($entete)) {
		# logo illu
		$logobig = PDF_load_image($pdf, "png", $_SERVER["DOCUMENT_ROOT"]."/print/illus/logoPrint.png", "");
		$haut = PDF_get_value($pdf, "imageheight", $logobig) * 0.4;
		pdf_place_image($pdf, $logobig, 10, $HauteurUtile - $haut, 0.3);
	}
	
	
	
	#### Entete de Page  ########################################
	#															#
		# Contacts
		pdf_setfont($pdf, $TimesBold, 12);
		pdf_set_value ($pdf, "leading", 17);
		pdf_show_boxed($pdf, "\r".$ncinf['societe']." (".$ncinf['idclient'].")
	".$phrase[64]."
	".$ncinf['adresse']."\r".$ncinf['cp']." ".$ncinf['ville']." 
	".(($ncinf['pays'] != 'Belgique')?$ncinf['pays']:'')."
	".$ncinf['codetva']." ".$ncinf['tva']."
	" , 265 , 555, 310, 170, 'left', ""); # infos contact
	
		# Date
		if ($ladate == '0000-00-00') {
			$ladate = date("d/m/Y");
		}
	
		pdf_setfont($pdf, $TimesRoman, 12);
		pdf_set_value ($pdf, "leading", 12);
		pdf_show_boxed($pdf, $phrase[23].$ladate , 280 , 750 , 255, 16 , 'right', ""); # date
	#															#
	#### Entete de Page  ########################################
	
	#### Cadres #################################################
	#	 														#
		pdf_setcolor($pdf, "fill", "gray", 0.9, 0 ,0 ,0);
		
		pdf_rect($pdf, 0, 548, 95, 27);				#
		pdf_rect($pdf, 95, 548, 335, 27);			# rectangles remplis
		pdf_rect($pdf, 430, 548, 95, 27);			#
		pdf_fill_stroke($pdf);						#
	
		pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);
	
		pdf_rect($pdf, 0, 515, 95, 33);				#
		pdf_rect($pdf, 95, 515, 335, 33);			# Rectangles Vides 1
		pdf_rect($pdf, 430, 515, 95, 33);			#
	
		pdf_rect($pdf, 0, 475, 95, 40);				#
		pdf_rect($pdf, 95, 475, 335, 40);			# Rectangles Vides 2
		pdf_rect($pdf, 430, 475, 95, 40);			#
	
		pdf_rect($pdf, 0, 435, 95, 40);				#
		pdf_rect($pdf, 95, 435, 335, 40);			# Rectangles Vides 3
		pdf_rect($pdf, 430, 435, 95, 40);			#
	
		pdf_rect($pdf, 0, 395, 95, 40);				#
		pdf_rect($pdf, 95, 395, 335, 40);			# Rectangles Vides 4
		pdf_rect($pdf, 430, 395, 95, 40);			#
	
		pdf_rect($pdf, 0, 355, 95, 40);				#
		pdf_rect($pdf, 95, 355, 335, 40);			# Rectangles Vides 5
		pdf_rect($pdf, 430, 355, 95, 40);			#
	
		pdf_rect($pdf, 0, 315, 95, 40);				#
		pdf_rect($pdf, 95, 315, 335, 40);			# Rectangles Vides 6
		pdf_rect($pdf, 430, 315, 95, 40);			#
	
		pdf_rect($pdf, 0, 275, 95, 40);				#
		pdf_rect($pdf, 95, 275, 335, 40);			# Rectangles Vides 7
		pdf_rect($pdf, 430, 275, 95, 40);			#
	
		pdf_rect($pdf, 430, 225, 95, 50);			# Rectangles Vides
		pdf_rect($pdf, 430, 205, 95, 20);			#
	
		pdf_stroke($pdf);
	#															#
	#### Cadres #################################################
	
	#### Corps de l'Offre #######################################
	#	 														#
		# Phrase Time 24 sans interligne
		pdf_setfont($pdf, $TimesRoman, 24);
		pdf_set_value ($pdf, "leading", 24);
		pdf_show_boxed($pdf, $phrase[66] , 0 , 685 , 145, 30 , 'center', ""); # Titre
	
		# Phrase Time 12 BOLD sans interligne
		pdf_setfont($pdf, $TimesBold, 12);
		pdf_set_value ($pdf, "leading", 12);
		pdf_show_boxed($pdf, $ncinf['intitule'] , 0 , 648 , 230, 30 , 'left', ""); # Sujet
		
		pdf_show_boxed($pdf, $phrase[5] , 0 , 555 , 95, 20 , 'center', ""); 		# Titre gris 1
		pdf_show_boxed($pdf, $phrase[6] , 100 , 555 , 290, 20 , 'center', ""); 	# Titre gris 2
		pdf_show_boxed($pdf, $phrase[8] , 430 , 555 , 95, 20 , 'center', "");	 	# Titre gris 3
		
		pdf_show_boxed($pdf, $tttable[0]['poste'] , 0 , 515 , 95, 30 , 'center', ""); 	# Titre gauche l1
		pdf_show_boxed($pdf, $tttable[1]['poste'] , 0 , 475 , 95, 30 , 'center', ""); 	# Titre gauche l2
		pdf_show_boxed($pdf, $tttable[2]['poste'] , 0 , 435 , 95, 30 , 'center', ""); 	# Titre gauche l3
		pdf_show_boxed($pdf, $tttable[3]['poste'] , 0 , 395 , 95, 30 , 'center', ""); 	# Titre gauche l4
		pdf_show_boxed($pdf, $tttable[4]['poste'] , 0 , 355 , 95, 30 , 'center', ""); 	# Titre gauche l5
		pdf_show_boxed($pdf, $tttable[5]['poste'] , 0 , 315 , 95, 30 , 'center', ""); 	# Titre gauche l6
		pdf_show_boxed($pdf, $tttable[6]['poste'] , 0 , 275 , 95, 30 , 'center', ""); 	# Titre gauche l7
	
		
		# Phrase Time 9 sans interligne
		pdf_setfont($pdf, $TimesRoman, 10);
		pdf_set_value ($pdf, "leading", 10);
		
		pdf_show_boxed($pdf, utf8_decode(ftxtpdf($tttable[0]['desc'])) , 100 , 515 , 300, 30 , 'left', ""); 	# Detail Prestations
		pdf_show_boxed($pdf, utf8_decode(ftxtpdf($tttable[1]['desc'])) , 100 , 475 , 300, 30 , 'left', ""); 	# Detail Deplacement
		pdf_show_boxed($pdf, utf8_decode(ftxtpdf($tttable[2]['desc'])) , 100 , 435 , 300, 30 , 'left', ""); 	# Detail Location
		pdf_show_boxed($pdf, utf8_decode(ftxtpdf($tttable[3]['desc'])) , 100 , 395 , 300, 30 , 'left', ""); 	# Detail Frais
		pdf_show_boxed($pdf, utf8_decode(ftxtpdf($tttable[4]['desc'])) , 100 , 355 , 300, 30 , 'left', "");	# Detail Divers
		pdf_show_boxed($pdf, utf8_decode(ftxtpdf($tttable[5]['desc'])) , 100 , 315 , 300, 30 , 'left', ""); 	# Detail Briefing
		pdf_show_boxed($pdf, utf8_decode(ftxtpdf($tttable[6]['desc'])) , 100 , 275 , 300, 30 , 'left', "");	# Detail P forfait
	
		# Exemption TVA
		if (($ncinf['astva'] == 6) or ($ncinf['astva'] == 5) or ($ncinf['astva'] == 3)) $exemption = $phrase[72];
		if ($ncinf['astva'] == 8) $exemption = $phrase[73];
	
		pdf_setfont($pdf, $TimesItalic, 12);
		pdf_set_value ($pdf, "leading", 12);
		pdf_show_boxed($pdf, $exemption , 0 , 225 , 270, 40 , 'left', ""); 
		unset($exemption);
	
		
		# Phrases italic
		pdf_setfont($pdf, $TimesItalic, 12);
		pdf_set_value ($pdf, "leading", 12);
	
		# Phrase Time 12 sans interligne
		pdf_setfont($pdf, $TimesRoman, 12);
		pdf_set_value ($pdf, "leading", 12);
	
		pdf_show_boxed($pdf, $phrase[14]."\r".'Françoise'.' '.'Lannoo' , 0 , 605 , 270, 40 , 'left', ""); # Agent Dossier
		
		pdf_show_boxed($pdf, $phrase[67].' : '.$leyear.'-'.$ncinf['idfac'] , 0 , 590 , 500, 20 , 'left', ""); # Phrase intro
		
		pdf_show_boxed($pdf, fpeuro($tttable[0]['montant']) , 430 , 515 , 90, 20 , 'right', ""); 	# Montant Prestations
		pdf_show_boxed($pdf, fpeuro($tttable[1]['montant']) , 430 , 475 , 90, 20 , 'right', ""); 	# Montant Deplacement
		pdf_show_boxed($pdf, fpeuro($tttable[2]['montant']) , 430 , 435 , 90, 20 , 'right', ""); 	# Montant Location
		pdf_show_boxed($pdf, fpeuro($tttable[3]['montant']) , 430 , 395 , 90, 20 , 'right', ""); 	# Montant Frais
		pdf_show_boxed($pdf, fpeuro($tttable[4]['montant']) , 430 , 355 , 90, 20 , 'right', ""); 	# Montant Divers
		pdf_show_boxed($pdf, fpeuro($tttable[5]['montant']) , 430 , 315 , 90, 20 , 'right', ""); 	# Montant Briefing
		pdf_show_boxed($pdf, fpeuro($tttable[6]['montant']) , 430 , 275 , 90, 20 , 'right', ""); 	# Montant forfait
		
		pdf_show_boxed($pdf, fpeuro($MontTVA) , 430 , 225 , 90, 20 , 'right', ""); 	# T Montant
		pdf_show_boxed($pdf, fpeuro($MontHTVA) , 430 , 250 , 90, 20 , 'right', ""); 	# T Montant
		pdf_show_boxed($pdf, fpeuro($MontTTC) , 430 , 205 , 90, 20 , 'right', ""); # T Montant
	
		# Avec Interligne
		pdf_set_value ($pdf, "leading", 23);
		pdf_show_boxed($pdf, $phrase[68] , 240 , 190 , 180, 90 , 'right', ""); # T Montant
	
		# Avec Interligne
		pdf_set_value ($pdf, "leading", 15);
	
	#															#
	#### Corps de l'Offre #######################################
	
	#### Pied de Page    ########################################
	#															#
		# Closes
		pdf_setfont($pdf, $TimesItalic, 8);
		pdf_show_boxed($pdf, $phrase[19] , 10 , 50 , $LargeurUtile, 48 , 'left', "");
		
		if (!empty($entete)) {
		# Ligne de bas de page
		pdf_moveto($pdf, 0, 50);
		pdf_lineto($pdf, $LargeurUtile, 50);
		pdf_stroke($pdf); # Ligne de bas de page
		
		# Coordonnées Exception
		pdf_setfont($pdf, $TimesRoman, 10);
		
		pdf_show_boxed($pdf, $phrase[20] ,0 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
		pdf_show_boxed($pdf, $phrase[21] , $LargeurUtile / 3,0 , $LargeurUtile / 3,40, 'center', ""); #texte du commentaire
		pdf_show_boxed($pdf, $phrase[22] , $LargeurUtile * 2 / 3 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
		}
	
	#															#
	#### Pied de Page    ########################################
	
	pdf_end_page($pdf);
	unset($htva);
	unset($tttable);
	unset($lposte);
	unset($MontTVA);
	unset($MontTTC);
	unset($MontHTVA);
}
	pdf_end_document($pdf, '');
	pdf_delete($pdf);
	
	return $path['full'];
}
?>