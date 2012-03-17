<?php
#########################################################################################################################################################################
### Page de contrat ANIM ################################################################################################################################################
#########################################################################################################################################################################
# TODO : !! moteur PDFlib, a remplacer
require_once(NIVO."nro/fm.php");
require_once(NIVO.'classes/anim.php');
require_once(NIVO.'classes/document.php');
require_once(NIVO.'classes/payement.php');

##############################################################################################################################
# renvoie les path 'dossier', 'full', 'history', 'filename'
function path_contratanim($idanim) {
	global $DB;
	$row = $DB->getRow("SELECT idanimation, idpeople, idanimjob FROM animation WHERE idanimation = ".$idanim);
		
## declarations
    $fname = prezero($row['idanimation'], 6).'-'.prezero($row['idpeople'], 5);

    $lespath['dossier'] = Conf::read('Env.root').'document/anim/'.prezero($row['idanimjob'], 5).'/contrat/';
    $lespath['filename'] = $fname.'.pdf';
    $lespath['full'] = $lespath['dossier'].$lespath['filename'];
    $lespath['history'] = Conf::read('Env.root').'history/anim/'.prezero($row['idanimjob'], 5).'/contrat/';

    if (file_exists($lespath['full'])) $lespath['historyfull'] = $lespath['history'].$fname.'-'.date ("Ymd.Hi", filemtime($lespath['full'])).'.pdf';

## check et creation si besoin
    if(!is_dir($lespath['dossier'])) mkdir($lespath['dossier'], 0777, true); #le 'true' c'est pour le recursif
    if(!is_dir($lespath['history'])) mkdir($lespath['history'], 0777, true); 

    return $lespath;
}

##############################################################################################################################
# sauve un contrat vip d'une mission donnée et retourne le chemin du fichier
function print_contratanim($idanim) {

###### GEt infos ######
	global $DB;
	$DB->inline("SET NAMES latin1");
    $row = $DB->getRow("SELECT
			an.idanimation, an.idanimjob, an.idshop, an.datem, an.idpeople, an.weekm, an.kmpaye, an.kmfacture, 
			an.hin1, an.hout1, an.hin2, an.hout2, an.produit, an.ferie, an.noteanim,
			j.briefing, j.idagent,
			s.societe, s.adresse, s.cp AS shopcp, s.ville AS shopville,
			p.pprenom, p.pnom, p.adresse1, p.num1, p.bte1, p.cp1, p.ville1, p.banque, p.codepeople, p.nville, p.ndate, p.lbureau, p.gsm, YEAR(p.ndate) as nyear,
			a.nom AS agentnom, a.prenom AS agentprenom, a.atel, a.agsm, a.email,
			nf.montantpaye
		FROM animation an
			LEFT JOIN people p ON an.idpeople = p.idpeople
			LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
			LEFT JOIN shop s ON an.idshop = s.idshop
			LEFT JOIN agent a ON j.idagent = a.idagent
			LEFT JOIN notefrais nf ON an.idanimation = nf.idmission AND nf.secteur = 'AN'
		WHERE an.idanimation = ".$idanim);

	if (!empty($row['idpeople'])) {
		
	###### check dir ######
		$path = path_contratanim($row['idanimation']);

		if (hashcheck(implode("|",$row), $path['full']) == 'new') { # verifie si les données du PDF ont changé, si oui, recree un PDF, si non renvoie l'ancien
		###### check file ######
			if (file_exists($path['full'])) rename($path['full'], $path['historyfull']);
	
		###### create PDF ######
			$pdf = pdf_new();
			pdf_open_file($pdf, $path['full']); # définit l'emplacement de la sauvegarde
			
			# Infos pour le document
			pdf_set_info($pdf, "Author", $_SESSION['prenom'].' '.$_SESSION['nom']);
			pdf_set_info($pdf, "Title", "Contrat Exception");
			pdf_set_info($pdf, "Creator", "NEURO");
			pdf_set_info($pdf, "Subject", "Contrat");
			
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

			### Declaration des fontes
			$TimesBold = PDF_load_font($pdf, "Times-Bold", "host", "");
			$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");
			$TimesItalic = PDF_load_font($pdf, "Times-Italic", "host", "");
			$Helvetica = PDF_load_font($pdf, "Helvetica", "host", "");
			$HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");

			################### Fin infos du job ########################
			$anim = new coreanim($row['idanimation']);

				################### Phrasebook ########################
				$xp = 1988 - $row['nyear']; # calcul des années d'expériences pour la clause des barèmes
				$xp = ($xp < 1)?1:$xp;
				
				switch ($row['lbureau']) {
						case "NL":
							include NIVO.'print/anim/contrat/nl.php';
							setlocale(LC_TIME, 'nl_NL');
						break;
						case "FR":
							include NIVO.'print/anim/contrat/fr.php';
							setlocale(LC_TIME, 'fr_FR');
						break;
						default:
							$phrase = array('');
							echo '<br> Langue pas d&eacute;finie pour le promoboy : '.$row['pprenom']." ".$row['pnom'];
				}
				################### Phrasebook ########################
			
				pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
				PDF_create_bookmark($pdf, $phrase[1]." ".$row['idanimation'], "");
			
				pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche

				# illu
				$logobig = PDF_load_image($pdf, "png", $_SERVER["DOCUMENT_ROOT"].'/print/illus/logoPrint.png', "");
				pdf_place_image($pdf, $logobig, 32, 660, 0.4);
			
				# Cadres
				pdf_rect($pdf, 0, 620, 270, 110);
				pdf_rect($pdf, 270, 620, 264, 110);
				pdf_rect($pdf, 484, 715, 50, 15);
			
				pdf_rect($pdf, 0, 545, 534, 75); # Document individuel
			
				pdf_rect($pdf, 0, 470, 534, 75); # Job par +  Lieu de présence
				pdf_rect($pdf, 0, 470, 260, 35); # Job par
				pdf_rect($pdf, 260, 470, 274, 75); # verticale entre Job et Lieu de présence
			
				pdf_rect($pdf, 0, 400, 534, 70); # Prestations
			
				pdf_rect($pdf, 0, 275, 350, 125); # noteanim / brifing
				pdf_rect($pdf, 350, 275, 184, 125); # Materiel mis à disposition
			
				pdf_rect($pdf, 0, 100, 534, 140); # Condition de travail
			
				pdf_stroke($pdf);
			
				# Cadre gris
				pdf_setcolor($pdf, "fill", "gray", 0.8, 0 ,0 ,0);
				pdf_rect($pdf, 0, 240, 534, 35);
				pdf_fill_stroke($pdf);
				pdf_setcolor($pdf, "fill", "gray", 0, 0 ,0 ,0);
			
				# Textes
				pdf_setfont($pdf, $Helvetica, 17); pdf_set_value ($pdf, "leading", 22);
				pdf_show_boxed($pdf, $phrase[2] , 155 , 755 , 373, 25 , 'right', ""); # Titre
			
				# Textes
				pdf_setfont($pdf, $TimesRoman, 9); pdf_set_value ($pdf, "leading", 10);
				pdf_show_boxed($pdf, $row['codepeople'], 486 , 717 , 45, 12 , 'center', ""); # Titre
			
				# Textes
				pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, $phrase[3], 0, 730, 267, 15, 'right', ""); # Titre
				pdf_show_boxed($pdf, $phrase[4], 267, 730, 267, 15, 'right', ""); # Titre
			
				pdf_setfont($pdf, $TimesBold, 14); pdf_set_value ($pdf, "leading", 17);
				pdf_show_boxed($pdf, $phrase[5], 5, 625, 160, 55, 'left', ""); # Titre
			
				pdf_setfont($pdf, $TimesBold, 14); pdf_set_value ($pdf, "leading", 20);;
				pdf_show_boxed($pdf, $row['pprenom']." ".$row['pnom']."
			".$row['adresse1'].", ".$row['num1']." ".$row['bte1']."
			".$row['cp1']." ".$row['ville1']."
			".$row['gsm'], 290, 630, 225, 80, 'left', ""); # Info adresse people
			
				# Textes
				pdf_setfont($pdf, $Helvetica, 11); pdf_set_value ($pdf, "leading", 15);
				pdf_show_boxed($pdf, $phrase[6], 20, 565, 200, 45, 'center', ""); # Document individuel
			
				pdf_setfont($pdf, $Helvetica, 11); pdf_set_value ($pdf, "leading", 16);
				pdf_show_boxed($pdf, $phrase[7], 230, 550, 160, 70, 'right', ""); # N° de compte
				pdf_show_boxed($pdf, $row['banque']."\r".$row['codepeople']."\r".fdate($row['ndate'])."\r".$row['nville'], 400, 550, 140, 70, 'left', ""); # Titre
			
				pdf_setfont($pdf, $TimesRoman, 12); pdf_set_value ($pdf, "leading", 17);
				pdf_show_boxed($pdf, $phrase[8], 120, 606, 160, 40, 'center', ""); # RCB : 498 586\rTVA : BE 430.597.846 ??????
			
				pdf_setfont($pdf, $TimesBold, 12); pdf_set_value ($pdf, "leading", 15);
				pdf_show_boxed($pdf, $phrase[9].'A '.$row['idanimation'], 15, 500, 250, 48, 'left', ""); # Mission n° :
				pdf_show_boxed($pdf, $phrase[10].'A '.$row['idanimjob'], 15, 500, 250, 30, 'left', ""); # Job n° :
			
				pdf_show_boxed($pdf, $phrase[20], 5, 475, 105, 30, 'right', ""); # Job par
				pdf_show_boxed($pdf, $row['agentprenom']." ".$row['agentnom']."\r".$row['atel'], 115, 475, 115, 30, 'left', ""); # Agent (Job par)
			
				pdf_setfont($pdf, $TimesRoman, 12); pdf_set_value ($pdf, "leading", 15);
				pdf_show_boxed($pdf, $phrase[21]."\r".$row['societe']."\r".$row['adresse']."\r".$row['shopcp']." ".$row['shopville'], 280, 470, 245, 75, 'center', ""); # Lieu de présence : ";
			
				##### pour "Jour de prestation :\r"; + #"Présence de : "; + "\rNombre d'heures = "; ...
					$ladate = strftime("%a %d/%m/%Y", strtotime($row['datem'])); # pour "Jour de prestation :\r";
			
					$kipay = fnbr($anim->kipay);
					if (empty($kipay)) $kipay  = '...';
					pdf_show_boxed($pdf, $phrase[11].$ladate , 5, 440, 110, 30, 'center', ""); # "Jour de prestation :\r";
					pdf_show_boxed($pdf, $phrase[40]."\r".$row['weekm'] , 160, 440, 60, 30, 'center', ""); # "Semaine :\r";
					pdf_show_boxed($pdf, $phrase[12].$kipay , 270, 440, 130, 30, 'center', ""); # Kilomètres parcourus :\r";
			
					## Note Frais payé
					$montantpaye = fpeuro($row['montantpaye']);
					if (($montantpaye == 0) or ($montantpaye == '')) {$montantpaye  = '...'; }
					pdf_show_boxed($pdf, $phrase[14].$montantpaye , 460, 440, 50, 30, 'center', ""); # "Frais :\r";
			
					pdf_setfont($pdf, $TimesRoman, 12); pdf_set_value ($pdf, "leading", 15);
					pdf_show_boxed($pdf, $phrase[32].$row['produit'] , 350, 410, 170, 30, 'left', ""); # "produit promotion:\r"
			
					pdf_setfont($pdf, $TimesBold, 12); pdf_set_value ($pdf, "leading", 15);
					$pres = '';
						if (($row['hin1'] != '00:00:00') or ($row['hout1'] != '00:00:00')) {
							if (($row['hin2'] != '00:00:00') or ($row['hout2'] != '00:00:00')) {
								$pres = ftime($row['hin1']).$phrase[18].ftime($row['hout1']).$phrase[31].ftime($row['hin2']).$phrase[18].ftime($row['hout2']);
							} else {
								$pres = ftime($row['hin1']).$phrase[18].ftime($row['hout1']);
							}
			
						} else {
							$pres = ftime($row['hin2']).$phrase[18].ftime($row['hout2']);
						}
					pdf_show_boxed($pdf, $phrase[17].$pres.$phrase[19].fnbr($anim->hprest).$phrase[34].fpeuro(salaire($row['idpeople'], $row['datem'])).$phrase[33] , 5, 410, 350, 30, 'left', ""); #"Présence de : "; + "\rNombre d'heures = ";
				#/#### pour "Jour de prestation :\r"; + #"Présence de : "; + "\rNombre d'heures = "; ...
			
			
				pdf_setfont($pdf, $TimesRoman, 10); pdf_set_value ($pdf, "leading", 11);
				pdf_show_boxed($pdf, $phrase[22].$row['noteanim'].' '.$row['briefing'], 5, 242, 345, 157, 'left', ""); # noteanim / brifing
			
				##### Manque inline de recherche du matos prete
				pdf_setfont($pdf, $TimesRoman, 12); pdf_set_value ($pdf, "leading", 15);
				pdf_show_boxed($pdf, $phrase[23], 355, 265, 175, 135, 'left', ""); # Materiel mis à disposition

				pdf_setfont($pdf, $TimesBold, 12); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, $phrase[24], 0, 	80, 180, 20, 'center', ""); # l'employeur
				pdf_show_boxed($pdf, $phrase[25], 178, 	80, 180, 20, 'center', ""); # le client
				pdf_show_boxed($pdf, $phrase[26], 355, 	80, 180, 20, 'center', ""); # l'employé
			
				pdf_setfont($pdf, $TimesBold, 10); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, $row['agentprenom']." ".$row['agentnom']." \r ".$row['atel']." - ".$row['agsm']." \r ".$row['email'], 5, 25, 180, 55, 'center', ""); # Titre

			#### Pied de Page    ########################################
			#															#
				# Closes
				pdf_setfont($pdf, $TimesItalic, 7.5); pdf_set_value ($pdf, "leading", 8);
				pdf_show_boxed($pdf, $phrase[27] , 5 , 100 , 525, 140 , 'justify', ""); # Closes
			
				# Ligne de bas de page
				pdf_moveto($pdf, 0, 40);
				pdf_lineto($pdf, $LargeurUtile, 40);
				pdf_stroke($pdf); # Ligne de bas de page
			
				# Coordonn?es Exception
				pdf_setfont($pdf, $TimesRoman, 10);
				
				pdf_show_boxed($pdf, $phrase[28] ,0 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
				pdf_show_boxed($pdf, $phrase[29] , $LargeurUtile / 3,0 , $LargeurUtile / 3,40, 'center', ""); #texte du commentaire
				pdf_show_boxed($pdf, $phrase[30] , $LargeurUtile * 2 / 3 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
			
				pdf_setfont($pdf, $HelveticaBold, 10);
				pdf_set_value ($pdf, "leading", 14);
				pdf_show_boxed($pdf, $phrase[41], 5, 242, 534, 35, 'left', ""); # Matos prêté
				pdf_setfont($pdf, $Helvetica, 10);
				
				pdf_show_boxed($pdf, $phrase[42], 130, 230, 384, 30, 'left', ""); # Matos prêté
			
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