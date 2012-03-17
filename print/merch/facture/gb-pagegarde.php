<?php 
## init vars

### Declaration des fontes
$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");
$TimesBold = PDF_load_font($pdf, "Times-Bold", "host", "");
$TimesItalic = PDF_load_font($pdf, "Times-Italic", "host", "");

pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
PDF_create_bookmark($pdf, $phrase[3].$np, "");

pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repËre au point bas-gauche


#### Entete de Page  ########################################
#															#
###### Duplicatas
if ($_POST['ent'] == 'entete') { 
	# logo illu
	$logobig = PDF_load_image($pdf, "tiff", $_SERVER["DOCUMENT_ROOT"]."/print/illus/logobig.tif", "");
	$haut = PDF_get_value($pdf, "imagewidth", $logobig) * 0.13; # Calcul de la hauteur
	pdf_place_image($pdf, $logobig, 5, $HauteurUtile - $haut, 0.13);
}

if (!empty($dupa)) {
	# duplicata illus 
	$duplicata = $_SERVER["DOCUMENT_ROOT"]."/print/illus/duplicata.jpg";	
	$duplicata = PDF_load_image($pdf, "jpeg", $duplicata, "");
	pdf_place_image($pdf, $duplicata, 175, 730, 0.35);
}

### Sur les prints , si non assujetti => mettre la mention : BE N.A. (2005-09-16)
if ($fac->astva == 7) { $astva = $phrase[72];} else { $astva = '';}
#/ ## Sur les prints , si non assujetti => mettre la mention : BE N.A. (2005-09-16)

	# Contacts
	pdf_setfont($pdf, $TimesBold, 12);
	pdf_set_value ($pdf, "leading", 17);
	pdf_show_boxed($pdf, "\r".($fac->societe)." (".$fac->idclient.")
".$fac->dept."
".$phrase[62]."  ".$fac->qual." ".$fac->nom."
".$fac->adresse." 
".$fac->pays."
".$fac->ftva." ".$astva."
" , 265 , 555, 310, 170, 'left', ""); # infos contact

	pdf_setfont($pdf, $TimesRoman, 12);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[23].utf8_decode(fdate($fac->datefac)) , 280 , 750 , 255, 16 , 'right', ""); # date
#															#
#### Entete de Page  ########################################

#### Cadres #################################################
#	 														#
	pdf_setcolor($pdf, "fill", "gray", 0.9, 0, 0, 0);
	
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


	pdf_rect($pdf, 430, 305, 95, 50);			# Rectangles Vides de total
	pdf_rect($pdf, 430, 285, 95, 20);			#

	pdf_stroke($pdf);
#															#
#### Cadres #################################################

#### Corps de l'Offre #######################################
#	 														#
	# Phrase Time 24 sans interligne
	pdf_setfont($pdf, $TimesRoman, 24);
	pdf_set_value ($pdf, "leading", 24);
	if ($fac->kind == 'FAC') {
		pdf_show_boxed($pdf, $phrase[2] , 0 , 705 , 145, 30 , 'center', ""); # Titre
	} else {
		pdf_show_boxed($pdf, $phrase[0] , 0 , 705 , 145, 30 , 'center', ""); # Titre
	}

	# Phrase Time 12 BOLD sans interligne
	pdf_setfont($pdf, $TimesBold, 12);
	pdf_set_value ($pdf, "leading", 12);

	pdf_show_boxed($pdf, utf8_decode($fac->intitule) , 0 , 675 , 530, 20 , 'left', ""); # Sujet
	pdf_show_boxed($pdf, utf8_decode((!empty($fac->boncommande))?'PO : '.$fac->boncommande:'') , 0 , 660 , 530, 20 , 'left', ""); # Sujet

	
	pdf_show_boxed($pdf, $phrase[5] , 0 , 555 , 95, 20 , 'center', ""); 			# Titre gris 1
	pdf_show_boxed($pdf, $phrase[6] , 100 , 555 , 290, 20 , 'center', ""); 		# Titre gris 2
	pdf_show_boxed($pdf, $phrase[8] , 430 , 555 , 95, 20 , 'center', "");	 		# Titre gris 3
	
	pdf_show_boxed($pdf, $phrase[9] , 0 , 515 , 95, 30 , 'center', ""); 			# Titre gauche l1
	pdf_show_boxed($pdf, $phrase[10] , 0 , 475 , 95, 30 , 'center', ""); 			# Titre gauche l2
	pdf_show_boxed($pdf, $phrase[12] , 0 , 435 , 95, 30 , 'center', ""); 			# Titre gauche l4
	pdf_show_boxed($pdf, $phrase[121] , 0 , 395 , 95, 30 , 'center', ""); 		# Titre gauche l5
	pdf_show_boxed($pdf, $phrase[34] , 0 , 355 , 95, 30 , 'center', ""); 		# Titre gauche l6

	
	# Phrase Time 9 sans interligne
	pdf_setfont($pdf, $TimesRoman, 10);
	pdf_set_value ($pdf, "leading", 10);
	
	pdf_show_boxed($pdf, utf8_decode($fac->Detail['prestations']) , 100 , 515 , 300, 30 , 'left', ""); 		# Detail Prestations
	pdf_show_boxed($pdf, utf8_decode($fac->Detail['deplacements']) , 100 , 475 , 300, 30 , 'left', ""); 	# Detail Deplacement
	pdf_show_boxed($pdf, fpeuro($fac->MontFrais) , 100 , 435 , 300, 30 , 'left', ""); 	# Detail Frais
	pdf_show_boxed($pdf, fpeuro($fac->MontDivers) , 100 , 395 , 300, 30 , 'left', ""); 	# Detail Divers
	pdf_show_boxed($pdf, fpeuro($fac->MontLivraison) , 100 , 355 , 300, 30 , 'left', ""); 		# Detail Briefing

	# Exemption TVA
	$exemption = '';
	if ((($fac->astva == 6) or ($fac->astva == 5)) and ($fac->rubhortva == 7)) $exemption = $phrase[73];
	if ($fac->astva == 3) $exemption = $phrase[67];
	if ($fac->astva == 8) $exemption = $phrase[68];

	pdf_setfont($pdf, $TimesItalic, 12);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $exemption , 0 , 225 , 270, 40 , 'left', ""); 
	unset($exemption);

	
	# Phrases italic
	pdf_setfont($pdf, $TimesItalic, 12);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[13] , 0 , 185 , 270, 20 , 'left', ""); # Paiement 30 jours	

	# Phrase Time 12 sans interligne
	pdf_setfont($pdf, $TimesRoman, 12);
	pdf_set_value ($pdf, "leading", 12);

	pdf_show_boxed($pdf, $phrase[14]."\r".utf8_decode($fac->agent) , 0 , 625 , 270, 40 , 'left', ""); # Agent Dossier

	pdf_show_boxed($pdf, $phrase[61].' : '.ffac($fac->id) , 0 , 590 , 500, 20 , 'left', ""); # Phrase intro
	
	pdf_show_boxed($pdf, fpeuro($fac->MontPrest) , 430 , 515 , 90, 20 , 'right', ""); 	# Montant Prestations
	pdf_show_boxed($pdf, fpeuro($fac->MontDepl) , 430 , 475 , 90, 20 , 'right', ""); 	# Montant Deplacement
	pdf_show_boxed($pdf, fpeuro($fac->MontFrais) , 430 , 435 , 90, 20 , 'right', ""); 	# Montant Frais
	pdf_show_boxed($pdf, fpeuro($fac->MontDivers) , 430 , 395 , 90, 20 , 'right', ""); 	# Montant Divers
	pdf_show_boxed($pdf, fpeuro($fac->MontLivraison) , 430 , 355 , 90, 20 , 'right', ""); 	# Montant Briefing
	
	pdf_show_boxed($pdf, fpeuro($fac->MontTVA) , 430 , 305 , 90, 20 , 'right', ""); 	# T Montant
	pdf_show_boxed($pdf, fpeuro($fac->MontHTVA) , 430 , 330 , 90, 20 , 'right', ""); 	# T Montant
	pdf_show_boxed($pdf, fpeuro($fac->MontTTC) , 430 , 285 , 90, 20 , 'right', ""); # T Montant

	
	pdf_setfont($pdf, $TimesRoman, 10);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[69] , 0 , 115 , 130, 36 , 'right', ""); # Comptes
	pdf_show_boxed($pdf, $phrase[70] , 180 , 115 , 150, 36 , 'left', ""); # IBAN
	pdf_show_boxed($pdf, $phrase[71] , 375 , 115 , 150, 36 , 'left', ""); # Swift

	# Avec Interligne
	pdf_set_value ($pdf, "leading", 23);
	pdf_show_boxed($pdf, $phrase[16] , 280 , 270 , 140, 90 , 'right', ""); # T Montant

	# Avec Interligne
	pdf_set_value ($pdf, "leading", 15);
	
	pdf_setfont($pdf, $TimesRoman, 10); pdf_set_value ($pdf, 'leading', 12);
	pdf_show_boxed($pdf, $phrase[65] , 0, 150, 534, 31 , 'left', "");

	

#															#
#### Corps de l'Offre #######################################


#### Pied de Page    ########################################
#															#
	# Closes

	pdf_setfont($pdf, $TimesItalic, 8);
	pdf_show_boxed($pdf, $phrase[19] , 10 , 50 , $LargeurUtile, 48 , 'left', "");
	
if ($_POST['ent'] == 'entete') {	
	# Ligne de bas de page
	pdf_moveto($pdf, 0, 50);
	pdf_lineto($pdf, $LargeurUtile, 50);
	pdf_stroke($pdf); # Ligne de bas de page

	
	# CoordonnÈes Exception
	pdf_setfont($pdf, $TimesRoman, 10);
	
	pdf_show_boxed($pdf, $phrase[20] ,0 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
	pdf_show_boxed($pdf, $phrase[21] , $LargeurUtile / 3,0 , $LargeurUtile / 3,40, 'center', ""); #texte du commentaire
	pdf_show_boxed($pdf, $phrase[22] , $LargeurUtile * 2 / 3 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire

}
	
#															#
#### Pied de Page    ########################################

pdf_end_page($pdf);
 ?> 