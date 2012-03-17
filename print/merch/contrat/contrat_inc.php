<?php
#########################################################################################################################################################################
### Page de contrat MERCH ################################################################################################################################################
#########################################################################################################################################################################
# TODO : !! moteur PDFlib, a remplacer
require_once($_SERVER["DOCUMENT_ROOT"].'/nro/fm.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/classes/merch.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/classes/document.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/classes/payement.php');

##############################################################################################################################
# renvoie les path 'dossier', 'full', 'history', 'filename'
function path_contratmerch($genre, $datem, $idpeople) {

## declarations
    $fname = date("Ymd", strtotime($datem)).'-'.prezero($idpeople, 5);

    $lespath['dossier'] = Conf::read('Env.root').'document/merch/'.cleanalpha($genre).'/contrat/'.date("Ym", strtotime($datem)).'/';
    $lespath['filename'] = $fname.'.pdf';
    $lespath['full'] = $lespath['dossier'].$lespath['filename'];
    $lespath['history'] = Conf::read('Env.root').'history/merch/'.cleanalpha($genre).'/contrat/'.date("Ym", strtotime($datem)).'/';

    if (file_exists($lespath['full'])) $lespath['historyfull'] = $lespath['history'].$fname.'-'.date ("Ymd.Hi", filemtime($lespath['full'])).'.pdf';

## check et creation si besoin
    if(!is_dir($lespath['dossier'])) mkdir($lespath['dossier'], 0777, true); #le 'true' c'est pour le recursif
    if(!is_dir($lespath['history'])) mkdir($lespath['history'], 0777, true); 

    return $lespath;
}

##############################################################################################################################
# sauve un contrat vip d'une mission donnée et retourne le chemin du fichier
function print_contratmerch($genre, $datem, $idpeople) {
###### GEt infos ######
	global $DB;

	if (!empty($idpeople) and (!empty($datem)) and (!empty($genre))) {
		
	    $DB->inline("SET NAMES latin1");
	    $row = $DB->getRow("SELECT
				me.idmerch, me.idpeople, me.idagent, me.genre, me.datem, me.weekm,
				p.lbureau, p.pprenom, p.pnom, p.codepeople, p.bte1, p.adresse1, p.num1, p.cp1, p.ville1, p.banque, p.nville, p.ndate, p.gsm, YEAR(p.ndate) as nyear,
				a.nom AS agentnom, a.prenom AS agentprenom, a.atel, a.agsm, a.email
			FROM merch me
				LEFT JOIN people p ON me.idpeople = p.idpeople
				LEFT JOIN agent a ON me.idagent = a.idagent
				WHERE me.idpeople = ".$idpeople." AND me.datem = '".$datem."' AND me.genre = '".$genre."' LIMIT 1");

		$weekmis = $DB->getArray("SELECT
				me.idmerch, me.hin1, me.hout1, me.hin2, me.hout2, me.kmpaye, me.produit,
				c.societe AS clsociete,
				s.adresse, s.ville AS shopville, s.societe, 
				nf.montantpaye
			FROM merch me
				LEFT JOIN client c ON me.idclient = c.idclient
				LEFT JOIN shop s ON me.idshop = s.idshop
				LEFT JOIN notefrais nf ON nf.secteur = 'ME' AND nf.idmission = me.idmerch
			WHERE me.idpeople = ".$idpeople." AND me.datem = '".$datem."'
			ORDER BY me.hin1, me.hin2");

	###### check dir ######
		$path = path_contratmerch($genre, $datem, $idpeople);
		
		## implode weekmis
		$hashdata = "";
		foreach ($weekmis as $wmis) {
			$hashdata .= implode("|", $wmis);
		}

		if (hashcheck(implode("|",$row)."|".$hashdata, $path['full']) == 'new') { # verifie si les données du PDF ont changé, si oui, recree un PDF, si non renvoie l'ancien
		###### check file ######
			if (file_exists($path['full'])) rename($path['full'], $path['historyfull']);

			$pdf = pdf_new();
			pdf_open_file($pdf, $path['full']); # définit l'emplacement de la sauvegarde

			# Infos pour le document
			pdf_set_info($pdf, "Author", "neuro");
			pdf_set_info($pdf, "Title", "Contrat Merch Exception");
			pdf_set_info($pdf, "Creator", "NEURO");
			pdf_set_info($pdf, "Subject", "Contrat");

			### Declaration des fontes
			$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");
			$TimesBold = PDF_load_font($pdf, "Times-Bold", "host", "");
			$TimesItalic = PDF_load_font($pdf, "Times-Italic", "host", "");
			$Helvetica = PDF_load_font($pdf, "Helvetica", "host", "");
			$HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");

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
			$xp = 1988 - $row['nyear']; # calcul des années d'expériences pour la clause des barèmes
			$xp = ($xp < 1)?1:$xp;
			
			switch ($row['lbureau']) {
				case "NL":
					include $_SERVER["DOCUMENT_ROOT"].'/print/merch/contrat/nl.php';
					setlocale(LC_TIME, 'nl_NL');
				break;
				case "FR":
					include $_SERVER["DOCUMENT_ROOT"].'/print/merch/contrat/fr.php';
					setlocale(LC_TIME, 'fr_FR');
				break;
				default:
					$phrase = array('');
					echo '<br> Langue pas d&eacute;finie pour le promoboy : '.$row['idpeople'].' '.$row['pprenom'].' '.$row['pnom'];
			}
			################### Phrasebook ########################

			pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
			PDF_create_bookmark($pdf, $phrase[1]." ".$row['idmerch'], "");

			pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche

			# illu
			$logobig = PDF_load_image($pdf, "png", $_SERVER["DOCUMENT_ROOT"].'/print/illus/logoPrint.png', "");
			pdf_place_image($pdf, $logobig, 32, 660, 0.4);

			# Cadres
			pdf_rect($pdf, 0, 620, 270, 110);
			pdf_rect($pdf, 270, 620, 264, 110);
			pdf_rect($pdf, 484, 715, 50, 15);
			pdf_rect($pdf, 0, 555, 534, 60); #cadre onss

			pdf_rect($pdf, 0, 505, 534, 50); #cadre contrat N Large
			pdf_rect($pdf, 0, 505, 180, 50); #cadre contrat : contrat
			pdf_rect($pdf, 180, 505, 144, 50); #cadre contrat : date
			pdf_rect($pdf, 324, 505, 210, 50); #cadre contrat : agent


			pdf_stroke($pdf);

			# Textes
			pdf_setfont($pdf, $Helvetica, 16); pdf_set_value ($pdf, "leading", 18);
			pdf_show_boxed($pdf, $phrase[2] , 155 , 755 , 373, 25 , 'right', ""); # Titre

			# Textes
			pdf_setfont($pdf, $TimesRoman, 9); pdf_set_value ($pdf, "leading", 10);
			pdf_show_boxed($pdf, $row['codepeople'], 486 , 717 , 45, 12 , 'center', ""); # Titre

			# Textes
			pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 12);
			pdf_show_boxed($pdf, $phrase[3], 0, 730, 267, 15, 'right', ""); # Titre
			pdf_show_boxed($pdf, $phrase[4], 330, 730, 200, 15, 'right', ""); # Titre

			pdf_setfont($pdf, $TimesBold, 14); pdf_set_value ($pdf, "leading", 17);
			pdf_show_boxed($pdf, $phrase[5], 5, 625, 160, 55, 'left', ""); # Exception titre

			pdf_setfont($pdf, $TimesRoman, 14); pdf_set_value ($pdf, "leading", 17);
			pdf_show_boxed($pdf, $phrase[8], 115, 606, 160, 40, 'center', ""); # Exception titre RCB tva

			if ($row['bte1'] != '') {$row['bte1'] = '/'.$row['bte1'];}
			pdf_setfont($pdf, $TimesBold, 14); pdf_set_value ($pdf, "leading", 20);
			pdf_show_boxed($pdf, $row['pprenom']." ".$row['pnom']."
		".$row['adresse1'].", ".$row['num1']." ".$row['bte1']."
		".$row['cp1']." ".$row['ville1']."
		".$row['gsm'], 290, 630, 225, 80, 'left', ""); # Cartouche adresse people

			# Textes
			pdf_setfont($pdf, $Helvetica, 11); pdf_set_value ($pdf, "leading", 15);
			pdf_show_boxed($pdf, $phrase[6], 20, 555, 200, 50, 'center', ""); # ONSS

			pdf_setfont($pdf, $Helvetica, 11); pdf_set_value ($pdf, "leading", 14);
			pdf_show_boxed($pdf, $phrase[7], 230, 545, 160, 70, 'right', ""); # banque etc
			pdf_show_boxed($pdf, $row['banque']."\r".$row['codepeople']."\r".fdate($row['ndate'])."\r".$row['nville'], 400, 545, 140, 70, 'left', ""); # banque etc variable


			pdf_setfont($pdf, $TimesRoman, 12); pdf_set_value ($pdf, "leading", 14);
			# pdf_show_boxed($pdf, $phrase[9].'M'.$row['idmerch']."\r".$row['genre'], 15, 495, 165, 60, 'left', ""); #cadre contrat¡ : contrat
			$plode = explode('-', $row['datem']);
			$ladate = strftime("%a %d/%m/%Y", mktime(0,0,0,$plode[1],$plode[2],$plode[0]) );
			pdf_show_boxed($pdf, $phrase[11].$ladate."\r".$phrase[12].' : '.$row['weekm'] , 185, 505, 170, 50, 'left', ""); #cadre contrat¡ : date
			pdf_show_boxed($pdf, $phrase[20]."\r".$row['agentprenom']." ".$row['agentnom']."\r".$row['atel'], 335, 505, 200, 50, 'left', ""); #cadre contrat¡ : agent


		########## Cadre de mission

		#titre
			# Cadres Titre
			$htab = 488;
			pdf_rect($pdf, 0, $htab, 30, 17); #idmission
			pdf_rect($pdf, 30, $htab, 98, 17); #client
			pdf_rect($pdf, 128, $htab, 98, 17); #lieu
			pdf_rect($pdf, 226, $htab, 98, 17); #produit
			pdf_rect($pdf, 324, $htab, 30, 17); #in1
			pdf_rect($pdf, 354, $htab, 30, 17); #out1
			pdf_rect($pdf, 384, $htab, 30, 17);	#in2
			pdf_rect($pdf, 414, $htab, 30, 17); #out2
			pdf_rect($pdf, 444, $htab, 30, 17); #tot
			pdf_rect($pdf, 474, $htab, 30, 17); #km
			pdf_rect($pdf, 504, $htab, 30, 17); #frais

			pdf_stroke($pdf);
			#/ ######### Cadre Titre

			########## Texte de Titre
			#s.societe, s.adresse, s.cp AS shopcp, s.ville AS shopville,
			pdf_setfont($pdf, $TimesBold, 9); pdf_set_value ($pdf, "leading", 12);
			pdf_show_boxed($pdf, "ID" , 2, $htab, 26, 17, 'left', ""); # idmission
			pdf_show_boxed($pdf, $phrase[50] , 32, $htab, 94, 17, 'left', ""); # client
			pdf_show_boxed($pdf, $phrase[51] , 130, $htab, 94, 17, 'left', ""); # lieu
			pdf_show_boxed($pdf, $phrase[52] , 228, $htab, 94, 17, 'left', ""); # produit

			pdf_show_boxed($pdf, $phrase[53] , 324, $htab, 30, 17, 'center', ""); # in1
			pdf_show_boxed($pdf, $phrase[54] , 354, $htab, 30, 17, 'center', ""); # out1
			pdf_show_boxed($pdf, $phrase[55] , 384, $htab, 30, 17, 'center', ""); # in2
			pdf_show_boxed($pdf, $phrase[56] , 414, $htab, 30, 17, 'center', ""); # out2
			pdf_show_boxed($pdf, 'Tot' , 444, $htab, 30, 17, 'center', ""); # out2
			pdf_show_boxed($pdf, $phrase[57] , 474, $htab, 30, 17, 'center', ""); # km
			pdf_show_boxed($pdf, $phrase[58] , 504, $htab, 30, 17, 'center', ""); # frais

			#/######### Texte de Titre
		#/titre

		$zip = 10;
		
		foreach ($weekmis as $row2) {

		### pour totaux
			$timetot = 0;
			$merch = new coremerch($row2['idmerch']);
			$timetot =  $merch->hprest;
			$timetotz += $timetot;
			$kmpayez += $row2['kmpaye'];
			$fraisz += $row2['montantpaye'];

		### pour totaux

			$zip--;
			#titre
				# Cadres contenu
				$htab = $htab - 26;
				pdf_rect($pdf, 0, $htab, 30, 26); #idmission
				pdf_rect($pdf, 30, $htab, 98, 26); #client
				pdf_rect($pdf, 128, $htab, 98, 26); #lieu
				pdf_rect($pdf, 226, $htab, 98, 26); #produit
				pdf_rect($pdf, 324, $htab, 30, 26); #in1
				pdf_rect($pdf, 354, $htab, 30, 26); #out1
				pdf_rect($pdf, 384, $htab, 30, 26);	#in2
				pdf_rect($pdf, 414, $htab, 30, 26); #out2
				pdf_rect($pdf, 444, $htab, 30, 26); #tot
				pdf_rect($pdf, 474, $htab, 30, 26); #km
				pdf_rect($pdf, 504, $htab, 30, 26); #frais

				pdf_stroke($pdf);
				#/ ######### Cadre Titre

				########## Texte de contenu
				#s.societe, s.adresse, s.cp AS shopcp, s.ville AS shopville,
				pdf_setfont($pdf, $TimesRoman, 8); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, $row2['idmerch'] , 2, $htab, 26, 26, 'center', ""); # idmission
				pdf_setfont($pdf, $TimesRoman, 10); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, $row2['clsociete'] , 32, $htab, 94, 26, 'left', ""); # client
				pdf_show_boxed($pdf, $row2['societe']." - ".$row2['shopville']." (".$row2['adresse'].')' , 130, $htab, 94, 26, 'left', ""); # lieu
				pdf_show_boxed($pdf, $row2['produit'] , 228, $htab, 94, 26, 'left', ""); # produit

				pdf_show_boxed($pdf, ftime($row2['hin1']) , 324, $htab, 30, 26, 'center', ""); # in1
				pdf_show_boxed($pdf, ftime($row2['hout1']) , 354, $htab, 30, 26, 'center', ""); # out1
				pdf_show_boxed($pdf, ftime($row2['hin2']) , 384, $htab, 30, 26, 'center', ""); # in2
				pdf_show_boxed($pdf, ftime($row2['hout2']) , 414, $htab, 30, 26, 'center', ""); # out2

				pdf_show_boxed($pdf, fnbr($timetot), 444, $htab, 30, 26, 'center', ""); # out2
				pdf_show_boxed($pdf, fnbr($row2['kmpaye']) , 474, $htab, 30, 26, 'center', ""); # km
				pdf_show_boxed($pdf, fnbr($row2['montantpaye']) , 504, $htab, 30, 26, 'center', ""); # frais

				#/######### Texte de Titre
			#/titre
		}
		### remplissage pour avoir 10 lignes
		while ($zip > 0) {
			$zip--;
			#titre
				# Cadres Titre
				$htab = $htab - 26;
				pdf_rect($pdf, 0, $htab, 30, 26); #idmission
				pdf_rect($pdf, 30, $htab, 98, 26); #client
				pdf_rect($pdf, 128, $htab, 98, 26); #lieu
				pdf_rect($pdf, 226, $htab, 98, 26); #produit
				pdf_rect($pdf, 324, $htab, 30, 26); #in1
				pdf_rect($pdf, 354, $htab, 30, 26); #out1
				pdf_rect($pdf, 384, $htab, 30, 26);	#in2
				pdf_rect($pdf, 414, $htab, 30, 26); #out2
				pdf_rect($pdf, 444, $htab, 30, 26); #tot
				pdf_rect($pdf, 474, $htab, 30, 26); #km
				pdf_rect($pdf, 504, $htab, 30, 26); #frais
				pdf_stroke($pdf);
				#/ ######### Cadre Titre
			#/titre

		}
		#/## remplissage pour avoir 10 lignes

		### TOTAUX
				$htab = $htab - 26;
				#pdf_rect($pdf, 226, $htab, 218, 26); #texte total
				pdf_rect($pdf, 444, $htab, 30, 26); #tot
				pdf_rect($pdf, 474, $htab, 30, 26); #km
				pdf_rect($pdf, 504, $htab, 30, 26); #frais
				pdf_stroke($pdf);

				pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, "leading", 9);
				pdf_show_boxed($pdf, $phrase[60].fpeuro(salaire($row['idpeople']), $row['datem']).$phrase[61] , 100, $htab + 10, 215, 10, 'right', ""); # out2

				pdf_setfont($pdf, $TimesRoman, 10); pdf_set_value ($pdf, "leading", 12);

				pdf_show_boxed($pdf, $phrase[59] , 226, $htab, 215, 26, 'right', ""); # out2
				pdf_show_boxed($pdf, fnbr($timetotz) , 444, $htab, 30, 26, 'center', ""); # out2
				pdf_show_boxed($pdf, fnbr($kmpayez) , 474, $htab, 30, 26, 'center', ""); # km
				pdf_show_boxed($pdf, fnbr($fraisz) , 504, $htab, 30, 26, 'center', ""); # frais

			$timetotz = 0;
			$kmpayez = 0;
			$fraisz = 0;

		### TOTAUX

			pdf_setfont($pdf, $TimesBold, 10); pdf_set_value ($pdf, "leading", 12);
			pdf_show_boxed($pdf, $row['agentprenom']." ".$row['agentnom']." \r ".$row['atel']." - ".$row['agsm']." \r ".$row['email'], 5, 24, 180, 55, 'center', ""); # Titre

			########## Cadre de mission
			pdf_setfont($pdf, $TimesBold, 12); pdf_set_value ($pdf, "leading", 12);
			pdf_show_boxed($pdf, $phrase[24], 0,   80, 180, 20, 'center', ""); # Titre
			pdf_show_boxed($pdf, $phrase[25], 178, 80, 180, 20, 'center', ""); # Titre
			pdf_show_boxed($pdf, $phrase[26], 355, 80, 180, 20, 'center', ""); # Titre

		#### Pied de Page    ########################################
		#															#

		### remarques
			pdf_rect($pdf, 0, 105, 534, 94); # remarque en bas en petit
			pdf_stroke($pdf);

			pdf_setfont($pdf, $TimesItalic, 6); pdf_set_value ($pdf, "leading", 6);
			pdf_show_boxed($pdf, $phrase[27] , 4 , 108 , 527, 90 , 'justify', ""); #remarques

		# Ligne de bas de page
			pdf_moveto($pdf, 0, 40);
			pdf_lineto($pdf, $LargeurUtile, 40);
			pdf_stroke($pdf); # Ligne de bas de page

		# Coordonnées Exception
			pdf_setfont($pdf, $TimesRoman, 10);

			pdf_show_boxed($pdf, $phrase[28] ,0 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
			pdf_show_boxed($pdf, $phrase[29] , $LargeurUtile / 3,0 , $LargeurUtile / 3,40, 'center', ""); #texte du commentaire
			pdf_show_boxed($pdf, $phrase[30] , $LargeurUtile * 2 / 3 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire

		#															#
		#### Pied de Page    ########################################

			pdf_end_page($pdf);
			pdf_end_document($pdf, '');
			pdf_delete($pdf); # Efface le fichier en mémoire
		}

		return $path['full'];
	} else {
		return "";
	}
}
?>