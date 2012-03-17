<?php
function path_fac_man($idfacture) {
	global $DB;
		
	$annee = $DB->getOne("SELECT YEAR(datefac) FROM facture WHERE idfac = ".$idfacture);

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


function print_fac_man($idfac, $entete = 'yes', $duplic, $prefac = null, $facinf)
{
	global $DB;
	$path = path_fac_man($idfac);
	if (file_exists($path['full'])) rename($path['full'], $path['historyfull']);

	## init
	$MontHTVA = 0;
	$np = 1; # Numéro de la première page
	
	$fac = new facture($idfac);

	$pdf = pdf_new();
	pdf_open_file($pdf, $path['full']); # définit l'emplacement de la sauvegarde
	
	# Infos pour le document
	pdf_set_info($pdf, "Author", "NEURO");
	pdf_set_info($pdf, "Title", "Factures");
	pdf_set_info($pdf, "Creator", "NEURO");
	pdf_set_info($pdf, "Subject", "Facture");
	
	######## Variables de taille  ###############
	$LargeurPage = 595; # Largeur A4
	$HauteurPage = 842; # Hauteur A4
	$MargeLeft = 30;
	$MargeRight = 30;
	$MargeTop = 30;
	$MargeBottom = 30;

	######## Variables de taille  ###############
	$LargeurUtile = $LargeurPage - $MargeRight - $MargeLeft;
	$HauteurUtile = $HauteurPage - $MargeTop - $MargeBottom;

	################### Phrasebook ########################
	switch ($facinf['langue']) {
		case "NL": 
			include NIVO.'print/commun/nl.php';
			setlocale(LC_TIME, 'nl_NL');
			$lposte = 'PostesNL';
		break;

		case "":
			echo '<br> Langue pas d&eacute;finie pour le client xx : '.$facinf['idclient']." ".$facinf['societe'];
		case "FR": 
			include NIVO.'print/commun/fr.php';
			setlocale(LC_TIME, 'fr_FR');
			$lposte = 'PostesFR';
		break;
	}
	
	################### Phrasebook ########################

	$facdet = $DB->getArray("SELECT * FROM `facmanuel` WHERE `idfac` = ".$idfac." ORDER BY poste ASC");
	$nbrdetail = count($facdet);

	$PostesFR = array( # Liste des postes dans la compta
		'700100' => 'Prestations',
		'700110' => 'Prestations',
		'700120' => 'Prestations',
		'700130' => 'Prestations',
		'700105' => 'Briefing',
		'700115' => 'Briefing',
		'700125' => 'Briefing',
		'700135' => 'Briefing',
		'700200' => 'Déplacements',
		'700210' => 'Déplacements',
		'700220' => 'Déplacements',
		'700230' => 'Déplacements',
		'700300' => 'Loc Uniformes',
		'700310' => 'Loc Uniformes',
		'700320' => 'Loc Uniformes',
		'700330' => 'Loc Uniformes',
		'700400' => 'Catering',
		'700410' => 'Catering',
		'700420' => 'Catering',
		'700430' => 'Catering',
		'700500' => 'Loc Stock',
		'700510' => 'Loc Stock',
		'700520' => 'Loc Stock',
		'700530' => 'Loc Stock',
		'700550' => 'Loc stand',
		'700560' => 'Loc stand',
		'700570' => 'Loc stand',
		'700580' => 'Loc stand',
		'700600' => 'Parking',
		'700610' => 'Parking',
		'700620' => 'Parking',
		'700630' => 'Parking',
		'700700' => 'Essence',
		'700710' => 'Essence',
		'700720' => 'Essence',
		'700730' => 'Essence',
		'700800' => 'Loc Vehicule',
		'700810' => 'Loc Vehicule',
		'700820' => 'Loc Vehicule',
		'700830' => 'Loc Vehicule',
		'700900' => 'Frais remboursés',
		'700910' => 'Frais remboursés',
		'700920' => 'Frais remboursés',
		'700930' => 'Frais remboursés',
		'700931' => 'Dimona',
		'700932' => 'Dimona',
		'700933' => 'Dimona',
		'700934' => 'Dimona',
		'700940' => 'Coursier',
		'700941' => 'Gobelets',
		'700942' => 'Serviettes',
		'700943' => 'Four',
		'700944' => 'Cure dents',
		'700945' => 'Cuillere',
		'700946' => 'Rechaud',
		'700950' => 'Coursier',
		'700960' => 'Coursier',
		'700970' => 'Coursier',
		'703000' => 'Prestations Informatique'
	);

	$PostesNL = array( # Liste des postes dans la compta
		'700100' => 'Prestaties',
		'700110' => 'Prestaties',
		'700120' => 'Prestaties',
		'700130' => 'Prestaties',
		'700105' => 'Briefing',
		'700115' => 'Briefing',
		'700125' => 'Briefing',
		'700135' => 'Briefing',
		'700200' => 'Verplaatsing',
		'700210' => 'Verplaatsing',
		'700220' => 'Verplaatsing',
		'700230' => 'Verplaatsing',
		'700300' => 'Locatie',
		'700310' => 'Locatie',
		'700320' => 'Locatie',
		'700330' => 'Locatie',
		'700400' => 'Catering',
		'700410' => 'Catering',
		'700420' => 'Catering',
		'700430' => 'Catering',
		'700500' => 'Locatie',
		'700510' => 'Locatie',
		'700520' => 'Locatie',
		'700530' => 'Locatie',
		'700550' => 'Locatie',
		'700560' => 'Locatie',
		'700570' => 'Locatie',
		'700580' => 'Locatie',
		'700600' => 'Parking',
		'700610' => 'Parking',
		'700620' => 'Parking',
		'700630' => 'Parking',
		'700700' => 'Benzine',
		'700710' => 'Benzine',
		'700720' => 'Benzine',
		'700730' => 'Benzine',
		'700800' => 'Locatie',
		'700810' => 'Locatie',
		'700820' => 'Locatie',
		'700830' => 'Locatie',
		'700900' => 'Onkosten',
		'700910' => 'Onkosten',
		'700920' => 'Onkosten',
		'700930' => 'Onkosten',
		'700931' => 'Dimona',
		'700932' => 'Dimona',
		'700933' => 'Dimona',
		'700934' => 'Dimona',
		'700940' => 'Courser',
		'700941' => 'Cups',
		'700942' => 'Handdoeken',
		'700943' => 'Oven',
		'700944' => 'Tandenstokers',
		'700945' => 'Lepels',
		'700946' => 'Brander',
		'700950' => 'Courser',
		'700960' => 'Courser',
		'700970' => 'Courser',
		'703000' => 'Informatica'
	);

$u = 0;
foreach ($facdet as $row) {
	$tttable[$u]['desc'] = ftxtpdf($row['description']);
	$tttable[$u]['poste'] = ($lposte == 'PostesNL')?$PostesNL[$row['poste']]:$PostesFR[$row['poste']];
	$tttable[$u]['montant'] = $row['montant'];
	$u++;
}

### Declaration des fontes
$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");
$TimesBold = PDF_load_font($pdf, "Times-Bold", "host", "");
$TimesItalic = PDF_load_font($pdf, "Times-Italic", "host", "");

pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
PDF_create_bookmark($pdf, $phrase[3].$np, "");

pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche


###### Logo
if (!empty($entete)) { 
	# illu
	$logobig = pdf_load_image($pdf, "png", $_SERVER["DOCUMENT_ROOT"]."/print/illus/logoPrint.png", "");
	$haut = PDF_get_value($pdf, "imageheight", $logobig) * 0.4; # Calcul de la hauteur
	pdf_place_image($pdf, $logobig, 5, $HauteurUtile - $haut, 0.4);
}

###### Duplicatas
if (!empty($duplic)) { 
	# duplicata illus 
	$duplicata = $_SERVER["DOCUMENT_ROOT"].'/print/illus/duplicata.jpg';	
	$duplicata = PDF_load_image($pdf, "jpeg", $duplicata, "");
	pdf_place_image($pdf, $duplicata, 225, 730, 0.35);
}

	# Contacts
	pdf_setfont($pdf, $TimesBold, 12);
	pdf_set_value ($pdf, "leading", 17);
	pdf_show_boxed($pdf, "\r".utf8_decode($fac->societe)." (".$fac->idclient.")
".$phrase[64]."
".utf8_decode($fac->adresse)." 
".$fac->pays."
".$fac->ftva."
" , 265 , 555, 310, 170, 'left', ""); # infos contact

	pdf_setfont($pdf, $TimesRoman, 12);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[23].fdate($fac->datefac) , 280 , 750 , 255, 16 , 'right', ""); # date
#															#
#### Entete de Page  ########################################

#### Cadres #################################################
#	 														#
	pdf_setcolor($pdf, "fill", "gray", 0.9, 0, 0, 0);
	
	pdf_rect($pdf, 0, 548, 95, 27);				#
	pdf_rect($pdf, 95, 548, 335, 27);			# rectangles remplis
	pdf_rect($pdf, 430, 548, 95, 27);			#
	pdf_fill_stroke($pdf);						#

	pdf_setcolor($pdf, "fill", "gray", 0, 0, 0 ,0);

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
	pdf_show_boxed($pdf, $phrase[2] , 0 , 705 , 145, 30 , 'center', ""); # Titre

	# Phrase Time 12 BOLD sans interligne
	pdf_setfont($pdf, $TimesBold, 12);
	pdf_set_value ($pdf, "leading", 12);

	pdf_show_boxed($pdf, utf8_decode($fac->intitule) , 0 , 670 , 530, 20 , 'left', ""); # Sujet
	
	# PO
	if (!empty($fac->boncommande)) {
		pdf_setfont($pdf, $TimesRoman, 10);
		pdf_set_value ($pdf, "leading", 10);

		pdf_show_boxed($pdf, "PO : ".utf8_decode($fac->boncommande) , 0 , 658 , 530, 20 , 'left', ""); # Sujet
	}

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
	
	pdf_show_boxed($pdf, utf8_decode($tttable[0]['desc']) , 100 , 515 , 300, 30 , 'left', ""); 	# Detail Prestations
	pdf_show_boxed($pdf, utf8_decode($tttable[1]['desc']) , 100 , 475 , 300, 30 , 'left', ""); 	# Detail Deplacement
	pdf_show_boxed($pdf, utf8_decode($tttable[2]['desc']) , 100 , 435 , 300, 30 , 'left', ""); 	# Detail Location
	pdf_show_boxed($pdf, utf8_decode($tttable[3]['desc']) , 100 , 395 , 300, 30 , 'left', ""); 	# Detail Frais
	pdf_show_boxed($pdf, utf8_decode($tttable[4]['desc']) , 100 , 355 , 300, 30 , 'left', "");	# Detail Divers
	pdf_show_boxed($pdf, utf8_decode($tttable[5]['desc']) , 100 , 315 , 300, 30 , 'left', ""); 	# Detail Briefing
	pdf_show_boxed($pdf, utf8_decode($tttable[6]['desc']) , 100 , 275 , 300, 30 , 'left', "");	# Detail P forfait

	# Phrases italic
	pdf_setfont($pdf, $TimesItalic, 12);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[13] , 0 , 185 , 270, 20 , 'left', ""); # Paiement 30 jours	

	# Exemption TVA
	if (($fac->astva == 6) or ($fac->astva == 5) or ($fac->astva == 3)) $exemption = $phrase[72];
	if ($fac->astva == 8) $exemption = $phrase[73];

	pdf_setfont($pdf, $TimesItalic, 12);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $exemption , 0 , 225 , 270, 40 , 'left', ""); 
	
	unset($exemption);

	# Phrase Time 12 sans interligne
	pdf_setfont($pdf, $TimesRoman, 12);
	pdf_set_value ($pdf, "leading", 12);

	pdf_show_boxed($pdf, $phrase[14]."\r".utf8_decode($fac->agent) , 0 , 625 , 270, 40 , 'left', ""); # Agent Dossier
	pdf_show_boxed($pdf, $phrase[65].utf8_decode($fac->qual." ".$fac->nom) , 0 , 610 , 270, 20 , 'left', ""); # commandé par

	pdf_show_boxed($pdf, $phrase[61].' : '.ffac($idfac) , 0 , 590 , 500, 20 , 'left', ""); # Phrase intro
	
	pdf_show_boxed($pdf, fpeuro($tttable[0]['montant']) , 410 , 515 , 110, 20 , 'right', ""); 	# Montant Prestations
	pdf_show_boxed($pdf, fpeuro($tttable[1]['montant']) , 410 , 475 , 110, 20 , 'right', ""); 	# Montant Deplacement
	pdf_show_boxed($pdf, fpeuro($tttable[2]['montant']) , 410 , 435 , 110, 20 , 'right', ""); 	# Montant Location
	pdf_show_boxed($pdf, fpeuro($tttable[3]['montant']) , 410 , 395 , 110, 20 , 'right', ""); 	# Montant Frais
	pdf_show_boxed($pdf, fpeuro($tttable[4]['montant']) , 410 , 355 , 110, 20 , 'right', ""); 	# Montant Divers
	pdf_show_boxed($pdf, fpeuro($tttable[5]['montant']) , 410 , 315 , 110, 20 , 'right', ""); 	# Montant Briefing
	pdf_show_boxed($pdf, fpeuro($tttable[6]['montant']) , 410 , 275 , 110, 20 , 'right', ""); 	# Montant forfait
	
	pdf_show_boxed($pdf, fpeuro($fac->MontTVA) , 430 , 225 , 90, 20 , 'right', ""); 	# T Montant
	pdf_show_boxed($pdf, fpeuro($fac->MontHTVA) , 430 , 250 , 90, 20 , 'right', ""); 	# T Montant
	pdf_show_boxed($pdf, fpeuro($fac->MontTTC) , 430 , 205 , 90, 20 , 'right', ""); # T Montant

	pdf_setfont($pdf, $TimesRoman, 10);
	pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[69] , 0 , 115 , 130, 36 , 'right', ""); # Comptes
	pdf_show_boxed($pdf, $phrase[70] , 180 , 115 , 150, 36 , 'left', ""); # IBAN
	pdf_show_boxed($pdf, $phrase[71] , 375 , 115 , 150, 36 , 'left', ""); # Swift

	# Avec Interligne
	pdf_set_value ($pdf, "leading", 23);
	pdf_show_boxed($pdf, $phrase[16] , 280 , 190 , 140, 90 , 'right', ""); # T Montant
	
	pdf_setfont($pdf, $TimesRoman, 10); pdf_set_value ($pdf, 'leading', 12);
	pdf_show_boxed($pdf, $phrase[63] , 0, 150, 534, 31 , 'left', "");


	# Avec Interligne
	pdf_set_value ($pdf, "leading", 15);

	# Clauses
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
	pdf_end_document($pdf, '');
	pdf_delete($pdf);

	unset($tttable);
	unset($lposte);

	return $path['full'];
}
?> 