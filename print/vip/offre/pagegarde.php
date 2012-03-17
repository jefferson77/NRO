<?php 
### Declaration des fontes
$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");
$TimesBold = PDF_load_font($pdf, "Times-Bold", "host", "");
$TimesItalic = PDF_load_font($pdf, "Times-Italic", "host", "");

pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
PDF_create_bookmark($pdf, $phrase[3].$np, "");

pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche


#### Entete de Page  ########################################
#															#
	# illu
	$logobig = pdf_load_image($pdf, "png", $_SERVER["DOCUMENT_ROOT"]."/print/illus/logoPrint.png", "");
	$haut = PDF_get_value($pdf, "imageheight", $logobig) * 0.33; # Calcul de la hauteur
	pdf_place_image($pdf, $logobig, 5, $HauteurUtile - $haut - 30, 0.5);

if ($infos['pays'] != 'Belgique') { $pays1 = $infos['pays'];} else { $pays1 = '';}
	
	# Contacts
	pdf_setfont($pdf, $TimesBold, 12);
	pdf_set_value ($pdf, "leading", 17);

### Sur les prints , si non assujetti => mettre la mention : BE N.A. (2005-09-16)
if ($infos['astva'] == 7) { $astva = $phrase[52];} else { $astva = ''; }
#/ ## Sur les prints , si non assujetti => mettre la mention : BE N.A. (2005-09-16)

	pdf_show_boxed($pdf, $infos['societe']." (".$infos['idclient'].")"."
Attn : ".$infos['qualite']." ".$infos['oprenom']." ".$infos['onom']."
".$infos['adresse']."\r".$infos['cp']." ".$infos['ville']."
".$pays1."
".$infos['codetva']." ".$infos['tva']." ".$astva."
Fax : ".$infos['fax']."
Tel : ".$infos['tel']."
" , 290 , 585 , 285, 150, 'left', ""); # infos contact

$pays1 = '';

	# Date
	$ladate = date("d/m/Y");
	pdf_setfont($pdf, $TimesRoman, 12);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[23].$ladate , 280 , 750 , 255, 16 , 'right', ""); # date
#															#
#### Entete de Page  ########################################

#### Cadres #################################################
#	 														#
	pdf_setcolor($pdf, 'fill', 'gray', 0.9, 0, 0, 0);	# Gris de remplissage
	
	pdf_rect($pdf, 0, 495, 95, 20);				#
	pdf_rect($pdf, 95, 495, 335, 20);			# rectangles remplis
	pdf_rect($pdf, 430, 495, 95, 20);			#
	pdf_fill_stroke($pdf);						#

	pdf_setcolor($pdf, 'fill', 'gray', 0, 0, 0, 0);		# Retour au Noir

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
	pdf_setfont($pdf, $TimesRoman, 24);
	pdf_set_value ($pdf, "leading", 24);
	pdf_show_boxed($pdf, $phrase[2] , 0 , 670 , 145, 30 , 'center', ""); # Signature

	# Phrase Time 12 BOLD sans interligne
	pdf_setfont($pdf, $TimesBold, 12);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[4]."VIP ".$infos['idvipjob'].") ".$infos['reference']."
".$infos['bondecommande'] , 0 , 610 , 530, 20 , 'left', ""); # Sujet
	
	pdf_show_boxed($pdf, $phrase[5] , 0 , 495 , 95, 20 , 'center', ""); 			# Titre
	pdf_show_boxed($pdf, $phrase[6] , 120 , 495 , 95, 20 , 'center', ""); 		# Titre
	pdf_show_boxed($pdf, $phrase[8] , 430 , 495 , 95, 20 , 'center', "");	 		# Titre
	
	pdf_show_boxed($pdf, $phrase[9] , 0 , 475 , 95, 20 , 'center', ""); 			# Titre
	pdf_show_boxed($pdf, $phrase[10] , 0 , 425 , 95, 20 , 'center', ""); 			# Titre
	pdf_show_boxed($pdf, $phrase[11] , 0 , 375 , 95, 20 , 'center', ""); 			# Titre
	pdf_show_boxed($pdf, $phrase[12] , 0 , 325 , 95, 20 , 'center', ""); 			# Titre

	pdf_show_boxed($pdf, fpeuro($MontTTC) , 430 , 225 , 90, 20 , 'right', ""); # T Montant
	
	# Phrase Time 9 sans interligne
	pdf_setfont($pdf, $TimesRoman, 9);
	pdf_set_value ($pdf, "leading", 9);
	
	pdf_show_boxed($pdf, $Detail['prestations']."".$infos['noteprest'], 100 , 440 , 305, 55 , 'left', ""); # Detail Prestations
	pdf_show_boxed($pdf, $Detail['deplacements'] ."".$infos['notedeplac'], 100 , 390 , 305, 55 , 'left', ""); # Detail Deplacement

	pdf_show_boxed($pdf, $infos['noteloca'] , 100 , 345 , 305, 50 , 'left', ""); # Detail Location
	pdf_show_boxed($pdf, $infos['notefrais']."\r".$Detail['frais'] , 100 , 295 , 305, 50 , 'left', ""); # Detail Frais

	#### Signature Planner  ########################################
	#															#
		# illu
		$fichierSignature = $_SERVER["DOCUMENT_ROOT"].'/print/illus/signature/'.$infos['idcommercial'].'.jpg';
		
        if (!file_exists($fichierSignature)) {
            echo "<br>fichier signature non trouvé pour : (".$infos['idcommercial'].") ".remaccents($infos['prenomcom']);
        } else {
            $signature = PDF_load_image($pdf, "jpeg", $fichierSignature, "");
            pdf_place_image($pdf, $signature, 410, 140, 0.36);	
        }
	#															#
	#/ ### Signature Planner  ########################################
	# Phrases
	pdf_setfont($pdf, $TimesRoman, 12);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[13] , 0 , 135 , 270, 20 , 'left', ""); # Paiement 30 jours	

	# Exemption TVA
	$exemption = ''; 
	if ((($infos['astva'] == 6) or ($infos['astva'] == 5)) and ($infos['rubhortva'] == 7)) $exemption = $phrase[73];
	if ($infos['astva'] == 3) $exemption = $phrase[67];
	if ($infos['astva'] == 8) $exemption = $phrase[68]; 

	pdf_setfont($pdf, $TimesRoman, 12);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $exemption , 0 , 225 , 270, 40 , 'left', ""); 
	unset($exemption);

	# Phrase Time 12 sans interligne
	pdf_setfont($pdf, $TimesRoman, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $infos['prenomcom']." ".$infos['nomcom'] , 360 , 120 , 190, 25 , 'center', ""); # Signature
	pdf_setfont($pdf, $TimesRoman, 10); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $infos['telcom']." - ".$infos['gsmcom'] , 360 , 105 , 190, 25 , 'center', ""); # Signature

	pdf_setfont($pdf, $TimesRoman, 12); pdf_set_value ($pdf, "leading", 16);
	pdf_show_boxed($pdf, $phrase[15]."\r".$phrase[74].$infos['qualiteagent']." ".$infos['prenomagent'].' '.$infos['nomagent'].$phrase[75] , 0 , 530 , $LargeurUtile, 64 , 'left', ""); # Qualite

	pdf_setfont($pdf, $TimesRoman, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, fpeuro($MontPrest) , 430 , 465 , 90, 20 , 'right', ""); 	# Montant
	pdf_show_boxed($pdf, fpeuro($MontDepl) , 430 , 415 , 90, 20 , 'right', ""); 	# Montant
	pdf_show_boxed($pdf, fpeuro($MontLoc) , 430 , 365 , 90, 20 , 'right', ""); 	# Montant
	pdf_show_boxed($pdf, fpeuro($MontFrais) , 430 , 315 , 90, 20 , 'right', ""); 	# Montant
	
	pdf_show_boxed($pdf, fpeuro($MontTVA) , 430 , 245 , 90, 20 , 'right', ""); 	# T Montant
	pdf_show_boxed($pdf, fpeuro($MontHTVA) , 430 , 270 , 90, 20 , 'right', ""); 	# T Montant

	# Avec Interligne
	pdf_set_value ($pdf, "leading", 23);
	pdf_show_boxed($pdf, $phrase[16] , 280 , 230 , 140, 70 , 'right', ""); # T Montant

	# Avec Interligne
	pdf_set_value ($pdf, "leading", 15);
	pdf_show_boxed($pdf, $phrase[17] , 0 , 165 , 500, 50 , 'left', ""); # Phrase si ca vous agree
	
	

#															#
#### Corps de l'Offre #######################################


#### Pied de Page    ########################################
#															#
	# Clauses
	pdf_setfont($pdf, $TimesItalic, 8);
	pdf_show_boxed($pdf, $phrase[19] , 0 , 40 , $LargeurUtile, 77 , 'left', "");
	
	# Ligne de bas de page
	pdf_moveto($pdf, 0, 40);
	pdf_lineto($pdf, $LargeurUtile, 40);
	pdf_stroke($pdf); # Ligne de bas de page
	
	# Coordonnées Exception
	pdf_setfont($pdf, $TimesRoman, 10);
	
	pdf_show_boxed($pdf, $phrase[20] ,0 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
	pdf_show_boxed($pdf, $phrase[21] , $LargeurUtile / 3,0 , $LargeurUtile / 3,40, 'center', ""); #texte du commentaire
	pdf_show_boxed($pdf, $phrase[22] , $LargeurUtile * 2 / 3 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire

#															#
#### Pied de Page    ########################################

pdf_end_page($pdf);
 ?> 