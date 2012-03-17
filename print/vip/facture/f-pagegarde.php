<?php 
### Declaration des fontes
$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");
$TimesBold = PDF_load_font($pdf, "Times-Bold", "host", "");
$TimesItalic = PDF_load_font($pdf, "Times-Italic", "host", "");

pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
PDF_create_bookmark($pdf, $phrase[3].$np, "");

pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche


#### Entete de Page  ########################################
#				

if (!empty($duplic)) {
	# duplicata illus 
	$duplicata = $_SERVER["DOCUMENT_ROOT"]."/print/illus/duplicata.jpg";	
	$duplicata = PDF_load_image($pdf, "jpeg", $duplicata, "");
	pdf_place_image($pdf, $duplicata, 175, 730, 0.35);

}

###### Duplicatas
if (!empty($entete)) { 
	# logo illu
	$logobig = PDF_load_image($pdf, "png", $_SERVER["DOCUMENT_ROOT"]."/print/illus/logoPrint.png", "");
	$haut = PDF_get_value($pdf, "imagewidth", $logobig) * 0.13; # Calcul de la hauteur
	pdf_place_image($pdf, $logobig, 5, $HauteurUtile - $haut, 0.4);
}



### Sur les prints , si non assujetti => mettre la mention : BE N.A.
if ($fac->astva == 7) { $astva = $phrase[72];} else {$astva = $fac->ftva; }

	# Contacts
	pdf_setfont($pdf, $TimesBold, 12); pdf_set_value ($pdf, "leading", 17);
	pdf_show_boxed($pdf, utf8_decode($fac->societe)." (".$fac->idclient.")
".$phrase[64]."
".utf8_decode($fac->adresse)."
".utf8_decode($fac->pays)."
".$astva."
" , 290 , 605 , 290, 130, 'left', ""); # infos contact

	# Date
	pdf_setfont($pdf, $TimesRoman, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[23].fdate($fac->datefac) , 280 , 750 , 255, 16 , 'right', ""); # date
#															#
#### Entete de Page  ########################################

#### Cadres #################################################
#	 														#
	pdf_setcolor($pdf, "fill", "gray", 0.9, 0, 0, 0);
	
	pdf_rect($pdf, 0, 495, 95, 20);				#
	pdf_rect($pdf, 95, 495, 335, 20);			# rectangles remplis
	pdf_rect($pdf, 430, 495, 95, 20);			#
	pdf_fill_stroke($pdf);						#

	pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);

	pdf_rect($pdf, 0, 445, 95, 50);				#
	pdf_rect($pdf, 95, 445, 335, 50);			# Rectangles Vides
	pdf_rect($pdf, 430, 445, 95, 50);			#

	pdf_rect($pdf, 0, 395, 95, 50);				#
	pdf_rect($pdf, 95, 395, 335, 50);			# Rectangles Vides
	pdf_rect($pdf, 430, 395, 95, 50);			#

	pdf_rect($pdf, 0, 345, 95, 50);				#
	pdf_rect($pdf, 95, 345, 335, 50);			# Rectangles Vides
	pdf_rect($pdf, 430, 345, 95, 50);			#

	pdf_rect($pdf, 0, 295, 95, 50);				#
	pdf_rect($pdf, 95, 295, 335, 50);			# Rectangles Vides
	pdf_rect($pdf, 430, 295, 95, 50);			#
	
	pdf_rect($pdf, 430, 245, 95, 50);			# Rectangles Vides
	pdf_rect($pdf, 430, 225, 95, 20);			#

	pdf_stroke($pdf);
#															#
#### Cadres #################################################

#### Corps de l'Offre #######################################
#	 														#
	# Phrase Time 24 sans interligne
	pdf_setfont($pdf, $TimesRoman, 24); pdf_set_value ($pdf, "leading", 24);
	
	if (!empty($prefac)) {
		pdf_show_boxed($pdf, $phrase[2] , 0 , 670 , 195, 40 , 'center', ""); # Signature
	} else {
		pdf_show_boxed($pdf, $phrase[62] , 0 , 670 , 195, 40 , 'center', ""); # Signature
	}

	# Phrase Time 12 BOLD sans interligne
	pdf_setfont($pdf, $TimesBold, 12);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[4].' '.utf8_decode($fac->intitule).")
	".utf8_decode($fac->reference)."
	".(!empty($fac->boncommande)?'PO : '.$fac->boncommande:'') , 0 , 620 , 530, 40 , 'left', ""); # Sujet
	
	pdf_show_boxed($pdf, $phrase[5] , 0 , 495 , 95, 20 , 'center', ""); 			# Titre
	pdf_show_boxed($pdf, $phrase[6] , 120 , 495 , 95, 20 , 'center', ""); 			# Titre
	pdf_show_boxed($pdf, $phrase[8] , 430 , 495 , 95, 20 , 'center', "");	 		# Titre
	
	pdf_show_boxed($pdf, $phrase[9] , 0 , 475 , 95, 20 , 'center', ""); 			# Titre
	pdf_show_boxed($pdf, $phrase[10] , 0 , 425 , 95, 20 , 'center', ""); 			# Titre
	pdf_show_boxed($pdf, $phrase[11] , 0 , 375 , 95, 20 , 'center', ""); 			# Titre
	pdf_show_boxed($pdf, $phrase[12] , 0 , 325 , 95, 20 , 'center', ""); 			# Titre

	pdf_show_boxed($pdf, fpeuro($fac->MontTTC) , 430 , 225 , 90, 20 , 'right', ""); # T Montant
	
	# Phrase Time 9 sans interligne
	pdf_setfont($pdf, $TimesRoman, 9);
	pdf_set_value ($pdf, "leading", 9);
	
	pdf_show_boxed($pdf, utf8_decode($fac->Detail['prestations']) , 100 , 440 , 235, 55 , 'left', ""); 	# Detail Prestations
	pdf_show_boxed($pdf, utf8_decode($fac->Detail['deplacements']) , 100 , 390 , 235, 55 , 'left', ""); 	# Detail Deplacement
	pdf_show_boxed($pdf, utf8_decode($fac->noteloca) , 100 , 345 , 235, 50 , 'left', ""); 				# Detail Location
	pdf_show_boxed($pdf, utf8_decode($fac->notefrais) , 100 , 295 , 235, 50 , 'left', ""); 				# Detail Frais
	
	# Phrases italic
	pdf_setfont($pdf, $TimesItalic, 12);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[13] , 0 , 165 , 270, 20 , 'left', ""); # Paiement 7 jours	

	# Exemption TVA
	$exemption = ''; 
	if ((($fac->astva == 6) or ($fac->astva == 5)) and ($fac->rubhortva == 7)) $exemption = $phrase[73];
	if ($fac->astva == 3) $exemption = $phrase[67];
	if ($fac->astva == 8) $exemption = $phrase[68];

	pdf_setfont($pdf, $TimesItalic, 12);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $exemption , 0 , 225 , 270, 40 , 'left', ""); 
	unset($exemption);

	# Phrase Time 12 sans interligne
	pdf_setfont($pdf, $TimesRoman, 12);
	pdf_set_value ($pdf, "leading", 12);

	pdf_show_boxed($pdf, $phrase[14].utf8_decode($fac->agent) , 0 , 575 , 270, 20 , 'left', ""); # Agent Dossier
	pdf_show_boxed($pdf, $phrase[65].utf8_decode($fac->qual." ".$fac->nom) , 0 , 560 , 270, 20 , 'left', ""); # commandé par
	pdf_show_boxed($pdf, $phrase[61].' : '.ffac($fac->id) , 0 , 530 , 500, 20 , 'left', ""); # Phrase intro

	pdf_show_boxed($pdf, fpeuro($fac->MontPrest) , 430 , 465 , 90, 20 , 'right', ""); 	# Montant
	pdf_show_boxed($pdf, fpeuro($fac->MontDepl) , 430 , 415 , 90, 20 , 'right', ""); 	# Montant
	pdf_show_boxed($pdf, fpeuro($fac->MontLoc) , 430 , 365 , 90, 20 , 'right', ""); 	# Montant
	pdf_show_boxed($pdf, fpeuro($fac->MontFrais) , 430 , 315 , 90, 20 , 'right', ""); 	# Montant
	
	pdf_show_boxed($pdf, fpeuro($fac->MontTVA) , 430 , 245 , 90, 20 , 'right', ""); 	# T Montant
	pdf_show_boxed($pdf, fpeuro($fac->MontHTVA) , 430 , 270 , 90, 20 , 'right', ""); 	# T Montant

	## Comptes Bancaires
	pdf_setfont($pdf, $TimesRoman, 10);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[69] , 0 , 115 , 130, 36 , 'right', ""); # Comptes
	pdf_show_boxed($pdf, $phrase[70] , 180 , 115 , 150, 36 , 'left', ""); # IBAN
	pdf_show_boxed($pdf, $phrase[71] , 375 , 115 , 150, 36 , 'left', ""); # Swift

	pdf_setfont($pdf, $TimesRoman, 10); pdf_set_value ($pdf, 'leading', 12);
	pdf_show_boxed($pdf, $phrase[63] , 0, 135, 534, 31 , 'left', ""); ## Iban
	
	# Avec Interligne
	pdf_set_value ($pdf, "leading", 23);
	pdf_show_boxed($pdf, $phrase[16] , 280 , 230 , 140, 70 , 'right', ""); # T Montant

	# Avec Interligne
	pdf_set_value ($pdf, "leading", 15);
	


#															#
#### Corps de l'Offre #######################################


#### Pied de Page    ########################################
#															#
	# Closes
	pdf_setfont($pdf, $TimesItalic, 8);
	pdf_show_boxed($pdf, $phrase[19] , 0 , 50 , $LargeurUtile, 48 , 'left', "");
	
if ($_POST['ent'] == 'entete') {
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
 ?> 