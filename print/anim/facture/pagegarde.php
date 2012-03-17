<?php 
switch ($facan->mode) {
	case"OFFRE":
		$contact = "Attn : ".utf8_decode($facan->contact);
		$prdate = date("d/m/Y");
		$intitule = $facan->phrasebook[2];
		$reference = $facan->phrasebook[4]." ".$facan->phrasebook[72]." (".$infos['idanimjob'].") ".$infos['reference'];
		$boncommande = "PO : ".$infos['boncommande'];
		if($facan->hforfait == 1 and empty($facan->DetDeplac))
		{
			$deplacement = $phrase[76].fnbr($facan->takm)." Euro par kilomètre";
		}
		else
		{
			$deplacement = $facan->DetDeplac;
		}
	break;
	case"FACTURE":
		$contact = $facan->phrasebook[64];
		$prdate = fdate($fac->datefac);
		$intitule = $facan->phrasebook[2];
		$reference = utf8_decode($fac->intitule);
		$boncommande = "PO : ".$fac->boncommande;
		$numdefac = $facan->phrasebook[61].' : '.ffac($fac->id);
	break;
	case"PREFAC":
		$contact = $facan->phrasebook[64];
		$prdate = fdate($fac->datefac);
		$intitule = $facan->phrasebook[0];
		$reference = $fac->intitule;
		$boncommande = "PO : ".$fac->boncommande;
		$numdefac = $facan->phrasebook[60].' : '.ffac($fac->id);
	break;
}

### Declaration des fontes
$TimesBold = PDF_load_font($pdf, "Times-Bold", "host", "");
$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");
$TimesItalic = PDF_load_font($pdf, "Times-Italic", "host", "");

pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
PDF_create_bookmark($pdf, $facan->phrasebook[3].$np, "");

pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche


#### Entete de Page  ########################################
#															#

###### duplicata illus ######################################################
if (!empty($duplic)) { 
	$duplicata = $_SERVER["DOCUMENT_ROOT"]."/print/illus/duplicata.jpg";	
	$duplicata = PDF_load_image($pdf, "jpeg", $duplicata, "");
	pdf_place_image($pdf, $duplicata, 175, 730, 0.35);
}

###### logo exception ######################################################
if (($_POST['ent'] == 'entete') or ($facan->mode == 'OFFRE') or ($facan->mode == 'PREFAC') or !empty($entete)) {
	# illu
	$logobig = pdf_load_image($pdf, "png", $_SERVER["DOCUMENT_ROOT"]."/print/illus/logoPrint.png", "");
	$haut = PDF_get_value($pdf, "imageheight", $logobig) * 0.4; # Calcul de la hauteur
	pdf_place_image($pdf, $logobig, 10, $HauteurUtile - $haut, 0.4);
}

### Sur les prints , si non assujetti => mettre la mention : BE N.A. (2005-09-16)
if ($facan->astva == 7) { $astva = $facan->phrasebook[73];}

###### contacts clients ######################################################
	pdf_setfont($pdf, $TimesBold, 12);
	pdf_set_value ($pdf, "leading", 17);
	pdf_show_boxed($pdf, utf8_decode($facan->societe)." (".$facan->idclient.")
".$contact."
".utf8_decode($facan->adresse)."
".utf8_decode($facan->pays)."
".$facan->ftva." ".$astva."
" , 265 , 565, 310, 170, 'left', ""); # infos contact

	pdf_setfont($pdf, $TimesRoman, 12);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $facan->phrasebook[23].$prdate , 200 , 750 , 325, 16 , 'right', ""); # date
#															#
#### Entete de Page  ########################################

#### Cadres #################################################
#	 														#
	pdf_setcolor($pdf, "fill", "gray", 0.9, 0 ,0 ,0);
	
	pdf_rect($pdf, 0, 548, 95, 27);			#
	pdf_rect($pdf, 95, 548, 335, 27);			# rectangles remplis
	pdf_rect($pdf, 430, 548, 95, 27);			#
	pdf_fill_stroke($pdf);						#

	pdf_setcolor($pdf, "fill", "gray", 0, 0 ,0 ,0);

	pdf_rect($pdf, 0, 515, 95, 33);			#
	pdf_rect($pdf, 95, 515, 335, 33);			# Rectangles Prestations
	pdf_rect($pdf, 430, 515, 95, 33);			#

	pdf_rect($pdf, 0, 475, 95, 40);			#
	pdf_rect($pdf, 95, 475, 335, 40);			# Rectangles Deplacement
	pdf_rect($pdf, 430, 475, 95, 40);			#

	pdf_rect($pdf, 0, 445, 95, 30);			#
	pdf_rect($pdf, 95, 445, 335, 30);			# Rectangles stand
	pdf_rect($pdf, 430, 445, 95, 30);			#

	pdf_rect($pdf, 0, 415, 95, 30);			#
	pdf_rect($pdf, 95, 415, 335, 30);			# Rectangles Frais
	pdf_rect($pdf, 430, 415, 95, 30);			#

	pdf_rect($pdf, 0, 385, 95, 30);			#
	pdf_rect($pdf, 95, 385, 335, 30);			# Rectangles Livraison
	pdf_rect($pdf, 430, 385, 95, 30);			#

	pdf_rect($pdf, 0, 355, 95, 30);			#
	pdf_rect($pdf, 95, 355, 335, 30);			# Rectangles Divers
	pdf_rect($pdf, 430, 355, 95, 30);			#

	pdf_rect($pdf, 0, 315, 95, 40);			#
	pdf_rect($pdf, 95, 315, 335, 40);			# Rectangles Briefing
	pdf_rect($pdf, 430, 315, 95, 40);			#

	pdf_rect($pdf, 0, 275, 95, 40);			#
	pdf_rect($pdf, 95, 275, 335, 40);			# Rectangles forfait
	pdf_rect($pdf, 430, 275, 95, 40);			#

	pdf_rect($pdf, 430, 225, 95, 50);			# Rectangles Vides
	pdf_rect($pdf, 430, 205, 95, 20);			#

	pdf_stroke($pdf);
#															            #
#### Cadres ###################################################

#### Corps de l'Offre #########################################
#	 														            #
# Phrase Time 24 sans interligne
    pdf_setfont($pdf, $TimesRoman, 24);
    pdf_set_value ($pdf, "leading", 24);
    pdf_show_boxed($pdf, $intitule , 0 , 695 , 145, 30 , 'center', ""); # titre
    
    # Phrase Time 12 BOLD sans interligne
	pdf_setfont($pdf, $TimesBold, 12);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, showmax($reference, 45) , 0 , 670 , 530, 15 , 'left', ""); # Sujet
	pdf_show_boxed($pdf, $boncommande , 0 , 655 , 530, 15 , 'left', ""); # Bon de commande

    if ($facan->mode != 'OFFRE') {
		pdf_show_boxed($pdf, $facan->phrasebook[14].utf8_decode($facan->agent) , 0 , 640 , 270, 15 , 'left', ""); # Agent Dossier
	    # Phrase Time 12 sans interligne
	    pdf_setfont($pdf, $TimesRoman, 12);
        pdf_show_boxed($pdf, $facan->phrasebook[65].$fac->qual." ".$fac->nom , 0 , 620 , 270, 15 , 'left', ""); # Cofficer
        pdf_show_boxed($pdf, $numdefac , 0 , 595 , 500, 15 , 'left', ""); # Phrase intro
    }

	pdf_setfont($pdf, $TimesBold, 12);
	pdf_show_boxed($pdf, $facan->phrasebook[5] , 0 , 550 , 95, 20 , 'center', ""); 			# Poste
	pdf_show_boxed($pdf, $facan->phrasebook[6] , 100 , 550 , 290, 20 , 'center', ""); 		# Detail
	pdf_show_boxed($pdf, $facan->phrasebook[8] , 430 , 550 , 95, 20 , 'center', "");	 		# Montant
	
	pdf_show_boxed($pdf, $facan->phrasebook[9] , 0 , 512 , 95, 30 , 'center', ""); 			# Prestations
	pdf_show_boxed($pdf, $facan->phrasebook[10] , 0 , 475 , 95, 30 , 'center', ""); 			# Deplacement
	pdf_show_boxed($pdf, $facan->phrasebook[11] , 0 , 439 , 95, 30 , 'center', ""); 			# stand
	pdf_show_boxed($pdf, $facan->phrasebook[12] , 0 , 409 , 95, 30 , 'center', ""); 			# Frais
	pdf_show_boxed($pdf, $facan->phrasebook[34] , 0 , 379 , 95, 30 , 'center', ""); 			# Livraison
	pdf_show_boxed($pdf, $facan->phrasebook[121] , 0 , 349 , 95, 30 , 'center', ""); 			# Divers
	pdf_show_boxed($pdf, $facan->phrasebook[122] , 0 , 315 , 95, 30 , 'center', ""); 			# Briefing
	pdf_show_boxed($pdf, $facan->phrasebook[123] , 0 , 275 , 95, 30 , 'center', ""); 			# forfait

	pdf_setfont($pdf, $TimesRoman, 10);
	pdf_set_value ($pdf, "leading", 10);

	pdf_show_boxed($pdf, $facan->DetPresta , 100 , 515 , 300, 33 , 'left', ""); 				# Detail Prestations
	pdf_show_boxed($pdf, $deplacement , 100 , 485 , 300, 30 , 'left', ""); 				# Detail Deplacement
	pdf_show_boxed($pdf, $facan->stand , 100 , 440 , 300, 30 , 'left', ""); 					# Detail stand
	pdf_show_boxed($pdf, $facan->DetFrais , 100 , 410 , 300, 30 , 'left', ""); 				# Detail Frais
	pdf_show_boxed($pdf, $facan->livraison , 100 , 380 , 300, 30 , 'left', ""); 				# Detail Livraison
	pdf_show_boxed($pdf, fpeuro($facan->MontDivers) , 100 , 350 , 300, 30 , 'left', ""); 	# Detail Divers
	pdf_show_boxed($pdf, $facan->briefing , 100 , 315 , 300, 30 , 'left', ""); 				# Detail Briefing
	pdf_show_boxed($pdf, $facan->DescForfait , 100 , 275 , 300, 30 , 'left', ""); 				# Detail P forfait
 
	# Phrases italic
	pdf_setfont($pdf, $TimesItalic, 12);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $facan->phrasebook[13] , 0 , 250 , 270, 20 , 'left', ""); # Paiement 7 jours	

	# Exemption TVA
	if ((($fac->astva == 6) or ($fac->astva == 5)) and ($fac->rubhortva == 7)) $exemption = $facan->phrasebook[74];
	if ($fac->astva == 3) $exemption = $facan->phrasebook[67];
	if ($fac->astva == 8) $exemption = $facan->phrasebook[68];

	pdf_setfont($pdf, $TimesItalic, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $exemption , 0 , 210 , 270, 40 , 'left', ""); 

	unset($exemption);

	if ($facan->mode == 'OFFRE') {
		pdf_setfont($pdf, $TimesItalic, 12); pdf_set_value ($pdf, "leading", 14);
		pdf_show_boxed($pdf, $infos['qualite'].",\r".$facan->phrasebook[15]."\r".$facan->phrasebook[77].$infos['prenomagent'].' '.$infos['nomagent'].$facan->phrasebook[78] , 0 , 575 , $LargeurUtile, 64 , 'left', ""); 			# Phrase intro
	}

    pdf_setfont($pdf, $TimesBold, 12);
	pdf_show_boxed($pdf, fpeuro($facan->MontPrest) , 430 , 512 , 90, 30 , 'right', ""); 		# Montant Prestations
	pdf_show_boxed($pdf, fpeuro($facan->MontDepl) , 430 , 475 , 90, 30 , 'right', ""); 		# Montant Deplacement
	pdf_show_boxed($pdf, fpeuro($facan->MontStand) , 430 , 439 , 90, 30 , 'right', ""); 		# Montant stand
	pdf_show_boxed($pdf, fpeuro($facan->MontFrais + $facan->MontDimona) , 430 , 409 , 90, 30 , 'right', ""); 		# Montant Frais
	pdf_show_boxed($pdf, fpeuro($facan->MontLivraison) , 430 , 379 , 90, 30 , 'right', ""); 	# Montant Livraison
	pdf_show_boxed($pdf, fpeuro($facan->MontDivers) , 430 , 349 , 90, 30 , 'right', ""); 	# Montant Divers
	pdf_show_boxed($pdf, fpeuro($facan->MontBriefingH) , 430 , 330 , 90, 15 , 'right', ""); 	# Montant Briefing
	pdf_show_boxed($pdf, fpeuro($facan->MontBriefingKM) , 430 , 315 , 90, 15 , 'right', ""); # Montant Briefing
	pdf_show_boxed($pdf, fpeuro($facan->MontForfait) , 430 , 275 , 90, 30 , 'right', ""); 	# Montant forfait

	pdf_show_boxed($pdf, fpeuro($facan->MontHTVA) , 430 , 249 , 90, 20 , 'right', ""); 		# T Montant
	pdf_show_boxed($pdf, fpeuro($facan->MontTVA) , 430 , 227 , 90, 20 , 'right', ""); 		# T Montant
	pdf_show_boxed($pdf, fpeuro($facan->MontTTC) , 430 , 203 , 90, 20 , 'right', ""); 		# T Montant

	if ($facan->mode != 'OFFRE') {
		pdf_setfont($pdf, $TimesRoman, 10);
		pdf_set_value ($pdf, "leading", 12);
		pdf_show_boxed($pdf, $facan->phrasebook[69] , 0 , 115 , 130, 36 , 'right', ""); # Comptes
		pdf_show_boxed($pdf, $facan->phrasebook[70] , 180 , 115 , 150, 36 , 'left', ""); # IBAN
		pdf_show_boxed($pdf, $facan->phrasebook[71] , 375 , 115 , 150, 36 , 'left', ""); # Swift
	}

	# Avec Interligne
	pdf_set_value ($pdf, "leading", 23);
	pdf_show_boxed($pdf, $facan->phrasebook[16] , 280 , 190 , 140, 90 , 'right', ""); # T Montant

	#### Signature Planner sur OFFRE ########################################
	if ($facan->mode == 'OFFRE') {
		# illu
		
		$infos = $DB->getRow("SELECT atel, agsm FROM agent WHERE idagent = (SELECT idagent FROM animjob WHERE idanimjob=".$idfac.")");
		
		if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/print/illus/signature/'.$_SESSION['idagent'].'.jpg')) {
			echo "<br>Fichier signature non trouvé pour : (".$_SESSION['idagent'].")".remaccents($_SESSION['prenomagent']);
		} else {
			$signature = $_SERVER["DOCUMENT_ROOT"].'/print/illus/signature/'.$_SESSION['idagent'].'.jpg';
			$signature = PDF_load_image($pdf, "jpeg", $signature, "");
			pdf_place_image($pdf, $signature, 410, 135, 0.36);	
		}

		# Phrase Time 12 sans interligne
		pdf_setfont($pdf, $TimesRoman, 12);
		pdf_set_value ($pdf, "leading", 12);
	
		pdf_show_boxed($pdf, $_SESSION['prenom']." ".$_SESSION['nom']."\r".$infos['atel']." - ".$infos['agsm'] , 380 , 90 , 150, 50 , 'center', ""); # Signature
	
		# Avec Interligne
		pdf_set_value ($pdf, "leading", 15);
		pdf_show_boxed($pdf, $facan->phrasebook[17].$infos['qualite'].$facan->phrasebook[18] , 0 , 160 , 300, 70 , 'left', ""); # Phrase si ca vous agree
	}

	# Avec Interligne
	pdf_setfont($pdf, $TimesRoman, 10); pdf_set_value ($pdf, 'leading', 12);
	
	if ($facan->mode == 'OFFRE') {
		pdf_show_boxed($pdf, $facan->phrasebook[66] , 0, 100, 300, 50 , 'left', "");
	} else {
		pdf_show_boxed($pdf, $facan->phrasebook[67] , 0, 120, 300, 50 , 'left', "");
	}
#															#
#### Corps de l'Offre #######################################

#### Pied de Page    ########################################
#															#
	# Closes
	pdf_setfont($pdf, $TimesItalic, 8);
	pdf_show_boxed($pdf, $facan->phrasebook[19] , 0 , 50 , $LargeurUtile, 52 , 'left', "");

	# Ligne de bas de page
	pdf_moveto($pdf, 0, 50);
	pdf_lineto($pdf, $LargeurUtile, 50);
	pdf_stroke($pdf); # Ligne de bas de page

	# Coordonnées Exception
	pdf_setfont($pdf, $TimesRoman, 10);
	
	pdf_show_boxed($pdf, $facan->phrasebook[20] ,0 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
	pdf_show_boxed($pdf, $facan->phrasebook[21] , $LargeurUtile / 3,0 , $LargeurUtile / 3,40, 'center', ""); #texte du commentaire
	pdf_show_boxed($pdf, $facan->phrasebook[22] , $LargeurUtile * 2 / 3 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire

#															#
#### Pied de Page    ########################################

pdf_end_page($pdf);
 ?> 