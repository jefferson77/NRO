<?php
#########################################################################################################################################################################
### Page de Rapport People ##############################################################################################################################################
#########################################################################################################################################################################
# TODO : !! moteur PDFlib, a remplacer
require_once(NIVO."nro/fm.php");
require_once(NIVO.'classes/anim.php');
require_once(NIVO.'classes/document.php');
require_once(NIVO.'classes/payement.php');

##############################################################################################################################
# renvoie les path 'dossier', 'full', 'history', 'filename'
function path_rapportpanim($idanim) {
	global $DB;	
	$row = $DB->getRow("SELECT idanimation, idpeople, idanimjob FROM animation WHERE idanimation = ".$idanim);
		
## declarations
    $fname = prezero($row['idanimation'], 6).'-'.prezero($row['idpeople'], 5);

    $lespath['dossier'] = Conf::read('Env.root').'document/anim/'.prezero($row['idanimjob'], 5).'/rapportp/';
    $lespath['filename'] = $fname.'.pdf';
    $lespath['full'] = $lespath['dossier'].$lespath['filename'];
    $lespath['history'] = Conf::read('Env.root').'history/anim/'.prezero($row['idanimjob'], 5).'/rapportp/';

    if (file_exists($lespath['full'])) $lespath['historyfull'] = $lespath['history'].$fname.'-'.date ("Ymd.Hi", filemtime($lespath['full'])).'.pdf';

## check et creation si besoin
    if(!is_dir($lespath['dossier'])) mkdir($lespath['dossier'], 0777, true); #le 'true' c'est pour le recursif
    if(!is_dir($lespath['history'])) mkdir($lespath['history'], 0777, true); 

    return $lespath;
}

##############################################################################################################################
# sauve un contrat vip d'une mission donnée et retourne le chemin du fichier
function print_rapportpanim($idanim) {

###### GEt infos ######
	global $DB;
	$DB->inline("SET NAMES latin1");
    $row = $DB->getRow("SELECT
			an.idpeople, an.idanimation, an.idanimjob, an.datem, an.hin1, an.hin2, an.hout1, an.hout2, an.weekm,
			p.lbureau, p.pnom, p.pprenom,
			c.societe AS clsociete,
			s.societe AS ssociete, s.ville AS sville,
			mat.stand, mat.gobelet, mat.four, mat.cuillere, mat.autre, mat.serviette, mat.curedent, mat.rechaud
		FROM animation an
			LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
			LEFT JOIN people p ON an.idpeople = p.idpeople
			LEFT JOIN client c ON j.idclient = c.idclient
			LEFT JOIN shop s ON an.idshop = s.idshop
			LEFT JOIN animmateriel mat ON an.idanimation = mat.idanimation
		WHERE an.idanimation = ".$idanim);

	$produits = $DB->getArray("SELECT `types` FROM `animproduit` WHERE `idanimation` = ".$row['idanimation']);

	if (!empty($row['idpeople'])) {
		
	###### check dir ######
		$path = path_rapportpanim($row['idanimation']);

		if (hashcheck(implode("|",$row)."|".implode("|",$produits), $path['full']) == 'new') { 
		###### check file ######
			if (file_exists($path['full'])) rename($path['full'], $path['historyfull']);
	
			$pdf = pdf_new();
			pdf_open_file($pdf, $path['full']); # définit l'emplacement de la sauvegarde
			
			# Infos pour le document
			pdf_set_info($pdf, "Author", "Neuro");
			pdf_set_info($pdf, "Title", "Rapport people Anim");
			pdf_set_info($pdf, "Creator", "NEURO");
			pdf_set_info($pdf, "Subject", "Rapport people Anim");

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
			$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");
			$TimesBold = PDF_load_font($pdf, "Times-Bold", "host", "");
			$TimesItalic = PDF_load_font($pdf, "Times-Italic", "host", "");
			$Helvetica = PDF_load_font($pdf, "Helvetica", "host", "");
			$HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");

			$tour = 1;
			$turntot = 1;
			$jump = 25;

			################### Phrasebook ########################
			switch ($row['lbureau']) {
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
						echo '<br> Langue pas d&eacute;finie pour le promoboy : '.$row['pprenom']." ".$row['pnom'];
			}
			################### Phrasebook ########################


			pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
			PDF_create_bookmark($pdf, $phrase[10]." ".$row['idanimation'], "");

			pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le rep?re au point bas-gauche


			#### Entete de Page  ########################################
			#															#
				# illu
				$logobig = PDF_load_image($pdf, "png", NIVO."print/illus/logoPrint.png", "");
				pdf_place_image($pdf, $logobig, 445, 761, 0.2);

				$logobig = PDF_load_image($pdf, "png", NIVO."print/illus/logoPrint.png", "");
				pdf_place_image($pdf, $logobig, 448, 370, 0.15);

			#															#
			#### Entete de Page  ########################################

			# Cadres doubles
			pdf_rect($pdf, 0, 338, 534, 373);
			pdf_rect($pdf, 2, 340, 530, 369);

			pdf_rect($pdf, 0, 195, 534, 132);
			pdf_rect($pdf, 2, 197, 530, 128);

			pdf_rect($pdf, 0, 45, 534, 140);
			pdf_rect($pdf, 2, 47, 530, 136);

			# Cadres simple 1
			pdf_rect($pdf, 2, 647, 210, 43);
			pdf_rect($pdf, 212, 647, 320, 43);

			$hcadreh = 15;
			$hcadre = 610;
			$cadre = 0;
			
			while ($cadre < 13) {
				$hcadre -= $hcadreh;
				pdf_rect($pdf, 2, $hcadre, 135, 15);
				pdf_rect($pdf, 137, $hcadre, 75, 15);
				pdf_rect($pdf, 212, $hcadre, 75, 15);
				pdf_rect($pdf, 287, $hcadre, 75, 15);
				pdf_rect($pdf, 362, $hcadre, 75, 15);
				pdf_rect($pdf, 437, $hcadre, 25, 15);
				pdf_rect($pdf, 462, $hcadre, 70, 15);
				$cadre++;
			}

			$hcadre -= $hcadreh;
		#	pdf_rect($pdf, 2, $hcadre, 135, 15);
			pdf_rect($pdf, 137, $hcadre, 75, 15);
			pdf_rect($pdf, 212, $hcadre, 75, 15);
			pdf_rect($pdf, 287, $hcadre, 75, 15);
			pdf_rect($pdf, 362, $hcadre, 75, 15);
			$hcadre -= $hcadreh;
		#	pdf_rect($pdf, 2, $hcadre, 135, 15);
			pdf_rect($pdf, 137, $hcadre, 75, 15);
			pdf_rect($pdf, 212, $hcadre, 75, 15);
			pdf_rect($pdf, 287, $hcadre, 75, 15);
			pdf_rect($pdf, 362, $hcadre, 75, 15);
			$hcadre -= $hcadreh;
		#	pdf_rect($pdf, 2, $hcadre, 135, 15);
			pdf_rect($pdf, 137, $hcadre, 75, 15);
			pdf_rect($pdf, 212, $hcadre, 75, 15);
			pdf_rect($pdf, 287, $hcadre, 75, 15);
			pdf_rect($pdf, 362, $hcadre, 75, 15);
			$hcadre -= $hcadreh;
		#	pdf_rect($pdf, 2, $hcadre, 135, 15);
			pdf_rect($pdf, 137, $hcadre, 75, 15);
			pdf_rect($pdf, 212, $hcadre, 75, 15);
			pdf_rect($pdf, 287, $hcadre, 75, 15);
			pdf_rect($pdf, 362, $hcadre, 75, 15);
			$hcadre -= $hcadreh;
		#	pdf_rect($pdf, 2, $hcadre, 135, 15);
			pdf_rect($pdf, 137, $hcadre, 75, 15);
			pdf_rect($pdf, 212, $hcadre, 75, 15);
			pdf_rect($pdf, 287, $hcadre, 75, 15);
			pdf_rect($pdf, 362, $hcadre, 75, 15);

		#-60
			# Cadres simple tableau titre
			pdf_rect($pdf, 2, 594, 530, 15);
			# Cadres simple tableau corps
			pdf_rect($pdf, 2, 416, 530, 178);
			# Cadres simple tableau illus
			pdf_rect($pdf, 437, 340, 95, 75);

			# Cadres simple remarque animatrice
			pdf_rect($pdf, 2, 307, 530, 18);

			# Cadres simple remarque magasin 1
			pdf_rect($pdf, 2, 160, 530, 23);

			# Cadres simple remarque magasin 2
			pdf_rect($pdf, 2, 88, 135, 71);
			pdf_rect($pdf, 137, 88, 394, 71);

			# Cadres simple remarque magasin 3
			pdf_rect($pdf, 2, 65, 530, 22);

			# Cadres simple remarque magasin 3
			pdf_rect($pdf, 2, 47, 530, 17);

			pdf_stroke($pdf);

		#	# Textes titres
			pdf_setfont($pdf, $HelveticaBold, 18); pdf_set_value ($pdf, "leading", 24);
			pdf_show_boxed($pdf, $phrase[10] , 140 , 765 , $LargeurUtile - 140, 25 , 'center', ""); # Titre

			pdf_setfont($pdf, $TimesRoman, 10); pdf_set_value ($pdf, "leading", 12);
			pdf_show_boxed($pdf, $phrase[11], 230, 715, $LargeurUtile - 230, 30, 'center', ""); # Titre

		#	# Textes Données Animatrice
			pdf_setfont($pdf, $TimesBold, 12); pdf_set_value ($pdf, "leading", 18);
			pdf_show_boxed($pdf, $phrase[13] , 0 , 690 , 530, 25 , 'center', ""); # Titre Données Animatrice
			pdf_setfont($pdf, $TimesRoman, 10); pdf_set_value ($pdf, "leading", 12);
			pdf_show_boxed($pdf, $phrase[14]." ".$row['idanimjob'], 5, 675, 210, 14, 'left', ""); # Job
			pdf_show_boxed($pdf, $phrase[61]." ".$row['idanimation'], 100, 675, 210, 14, 'left', ""); # Mission
			pdf_show_boxed($pdf, $phrase[15]." ".$row['clsociete'], 5, 662, 210, 14, 'left', ""); # Client
			pdf_show_boxed($pdf, $phrase[16]." ".$row['ssociete'] ." ".$row['sville'], 5, 649, 210, 14, 'left', ""); # Lieu

			pdf_show_boxed($pdf, $phrase[17]." ".$row['pprenom']." ".$row['pnom'], 215, 675, 320, 14, 'left', ""); # Animatrice

			pdf_show_boxed($pdf, $phrase[19], 215, 649, 320, 14, 'left', ""); # Frais achat produits *

			pdf_show_boxed($pdf, $phrase[20]." ".fdate($row['datem']), 5, 630, 210, 14, 'left', ""); # Date
			pdf_show_boxed($pdf, $phrase[67]." ".$row['weekm'], 5, 615, 210, 14, 'left', ""); # Semaine
			# Journée / matin / après-midi
			if (($row['hin1'] != '') and (ftime($row['hin1']) != '00:00')) {
				if (($row['hin2'] != '') and (ftime($row['hin2']) != '00:00')) {
					pdf_show_boxed($pdf, $phrase[21]." ".$phrase[22]." ".ftime($row['hin1'])." ".$phrase[23]." ".ftime($row['hout1']).''.$phrase[24]." ".$phrase[22]." ".ftime($row['hin2'])." ".$phrase[23]." ".ftime($row['hout2']), 215, 630, 320, 14, 'left', ""); # Présence
				} else {
					pdf_show_boxed($pdf, $phrase[21]." ".$phrase[22]." ".ftime($row['hin1'])." ".$phrase[23]." ".ftime($row['hout1']), 215, 630, 320, 14, 'left', ""); # Présence
				}
			} else {
					pdf_show_boxed($pdf, $phrase[21]." ".$phrase[22]." ".ftime($row['hin2'])." ".$phrase[23]." ".ftime($row['hout2']), 215, 630, 320, 14, 'left', ""); # Présence
			}

		#	Tableau produit
			pdf_show_boxed($pdf, $phrase[25] , 5 , 596 , 130, 15 , 'center', ""); # Titre Produit
			pdf_show_boxed($pdf, $phrase[26] , 140 , 596 , 65, 15 , 'center', ""); # Titre Prix
			pdf_show_boxed($pdf, $phrase[27] , 215 , 596 , 65, 15 , 'center', ""); # Titre Stock début
			pdf_show_boxed($pdf, $phrase[28] , 290 , 596 , 65, 15 , 'center', ""); # Titre Stock fin
			pdf_show_boxed($pdf, $phrase[29] , 365 , 596 , 65, 15 , 'center', ""); # Titre Ventes
			pdf_show_boxed($pdf, $phrase[30] , 440 , 596 , 20, 15 , 'center', ""); # Titre Rupt
			pdf_show_boxed($pdf, $phrase[31] , 465 , 596 , 65, 15 , 'center', ""); # Titre Dégustation

		###  produits
			$hautprod = 580;
			
			foreach($produits as $produit) {
				pdf_show_boxed($pdf, $produit['types'] , 5 , $hautprod , 130, 15 , 'left', ""); # Titre Produit
				$hautprod = $hautprod -15 ;
			}
		###/  produits

		#-60
			pdf_show_boxed($pdf, $phrase[32] , 440 , 580 , 20, 15 , 'center', ""); # Titre Rupt O
			pdf_show_boxed($pdf, $phrase[32] , 440 , 565 , 20, 15 , 'center', ""); # Titre Rupt O
			pdf_show_boxed($pdf, $phrase[32] , 440 , 550 , 20, 15 , 'center', ""); # Titre Rupt O
			pdf_show_boxed($pdf, $phrase[32] , 440 , 535 , 20, 15 , 'center', ""); # Titre Rupt O
			pdf_show_boxed($pdf, $phrase[32] , 440 , 520 , 20, 15 , 'center', ""); # Titre Rupt O
			pdf_show_boxed($pdf, $phrase[32] , 440 , 505 , 20, 15 , 'center', ""); # Titre Rupt O
			pdf_show_boxed($pdf, $phrase[32] , 440 , 490 , 20, 15 , 'center', ""); # Titre Rupt O
			pdf_show_boxed($pdf, $phrase[32] , 440 , 475 , 20, 15 , 'center', ""); # Titre Rupt O
			pdf_show_boxed($pdf, $phrase[32] , 440 , 460 , 20, 15 , 'center', ""); # Titre Rupt O
			pdf_show_boxed($pdf, $phrase[32] , 440 , 445 , 20, 15 , 'center', ""); # Titre Rupt O
			pdf_show_boxed($pdf, $phrase[32] , 440 , 430 , 20, 15 , 'center', ""); # Titre Rupt O
			pdf_show_boxed($pdf, $phrase[32] , 440 , 416 , 20, 15 , 'center', ""); # Titre Rupt O

			pdf_show_boxed($pdf, $phrase[33] , 5 , 400 , 130, 15 , 'left', ""); # Titre Matériel à disposition
			pdf_show_boxed($pdf, $phrase[34] , 140 , 400 , 65, 15 , 'left', ""); # Titre stand
			pdf_show_boxed($pdf, $phrase[35] , 140 , 385 , 65, 15 , 'left', ""); # Titre Gobelets
			pdf_show_boxed($pdf, $phrase[36] , 140 , 370 , 65, 15 , 'left', ""); # Titre Four
			pdf_show_boxed($pdf, $phrase[37] , 140 , 355 , 65, 15 , 'left', ""); # Titre Cuillères
			pdf_show_boxed($pdf, $phrase[38] , 140 , 340 , 65, 15 , 'left', ""); # Titre Autres
			pdf_show_boxed($pdf, $phrase[39] , 290 , 400 , 65, 15 , 'left', ""); # Titre Livraison
			pdf_show_boxed($pdf, $phrase[40] , 290 , 385 , 65, 15 , 'left', ""); # Titre Serviettes
			pdf_show_boxed($pdf, $phrase[41] , 290 , 370 , 65, 15 , 'left', ""); # Titre Cure-dents
			pdf_show_boxed($pdf, $phrase[42] , 290 , 355 , 65, 15 , 'left', ""); # Titre Réchaud

			pdf_show_boxed($pdf, fnbr0($row['stand']) , 220 , 400 , 65, 15 , 'left', ""); # stand
			pdf_show_boxed($pdf, fnbr0($row['gobelet']) , 220 , 385 , 65, 15 , 'left', ""); # Gobelets
			pdf_show_boxed($pdf, fnbr0($row['four']) , 220 , 370 , 65, 15 , 'left', ""); # Four
			pdf_show_boxed($pdf, fnbr0($row['cuillere']) , 220 , 355 , 65, 15 , 'left', ""); # Cuillères
			pdf_show_boxed($pdf, fnbr0($row['autre']) , 220 , 340 , 65, 15 , 'left', ""); # Autres
			pdf_show_boxed($pdf, "" , 370 , 400 , 65, 15 , 'left', ""); # Livraison
			pdf_show_boxed($pdf, fnbr0($row['serviette']) , 370 , 385 , 65, 15 , 'left', ""); # Serviettes
			pdf_show_boxed($pdf, fnbr0($row['curedent']) , 370 , 370 , 65, 15 , 'left', ""); # Cure-dents
			pdf_show_boxed($pdf, fnbr0($row['rechaud']) , 370 , 355 , 65, 15 , 'left', ""); # Réchaud

		#	Remarque animatrice
			pdf_setfont($pdf, $TimesBold, 12); pdf_set_value ($pdf, "leading", 18);
			pdf_show_boxed($pdf, $phrase[44] , 0 , 305 , 530, 25 , 'center', ""); # Titre Remarque Animatrice
			pdf_show_boxed($pdf, $phrase[32].' '.$phrase[48] , 145 , 285 , 65, 25 , 'left', ""); # 0 +
			pdf_show_boxed($pdf, $phrase[32].' '.$phrase[49] , 220 , 285 , 65, 25 , 'left', ""); # 0 +/-
			pdf_show_boxed($pdf, $phrase[32].' '.$phrase[50] , 295 , 285 , 65, 25 , 'left', ""); # 0 -

			pdf_show_boxed($pdf, $phrase[32].' '.$phrase[51] , 145 , 245 , 65, 25 , 'left', ""); # 0 Non
			pdf_show_boxed($pdf, $phrase[32].' '.$phrase[52] , 220 , 245 , 65, 25 , 'left', ""); # 0 oui

			pdf_setfont($pdf, $TimesRoman, 10); pdf_set_value ($pdf, "leading", 12);
			pdf_show_boxed($pdf, $phrase[45], 5, 290, 210, 14, 'left', ""); # Emplacement Stand
			pdf_show_boxed($pdf, $phrase[46], 5, 250, 210, 28, 'left', ""); # Autres Animations
			pdf_show_boxed($pdf, ' '.$phrase[54].''.$phrase[54].''.$phrase[54].'-
			-'.$phrase[54].''.$phrase[54].''.$phrase[54].'
			-'.$phrase[54].''.$phrase[54].''.$phrase[54], 295, 225, 220, 42, 'left', ""); # ----- Autres Animations

			pdf_show_boxed($pdf, $phrase[47], 5, 214, 210, 14, 'left', ""); # Commentaires
			pdf_show_boxed($pdf, ' '.$phrase[54].''.$phrase[54].''.$phrase[54].''.$phrase[54].''.$phrase[54].'----
			-'.$phrase[54].''.$phrase[54].''.$phrase[54].''.$phrase[54].''.$phrase[54].''.$phrase[60], 145, 200, 380, 26, 'left', ""); # ----- Commentaires

		#	Remarque(s) Magasin
			pdf_setfont($pdf, $TimesBold, 12); pdf_set_value ($pdf, "leading", 18);
			pdf_show_boxed($pdf, $phrase[55] , 0 , 160 , 530, 25 , 'center', ""); # Titre Remarque Magasin
			pdf_setfont($pdf, $TimesRoman, 10); pdf_set_value ($pdf, "leading", 12);
			pdf_show_boxed($pdf, $phrase[56], 5, 145, 210, 14, 'left', ""); # Emplacement Stand
			pdf_show_boxed($pdf, $phrase[57], 145, 145, 195, 14, 'left', ""); # Remarques du responsable
			pdf_show_boxed($pdf, $phrase[54].''.$phrase[54].''.$phrase[60].''.$phrase[60].''.$phrase[60], 345, 140, 245, 14, 'left', ""); # ----- Remarques du responsable
			pdf_show_boxed($pdf, $phrase[54].''.$phrase[54].''.$phrase[54].''.$phrase[54].''.$phrase[54].''.$phrase[60].''.$phrase[60], 145, 125, 375, 14, 'left', ""); # ----- Remarques du responsable
			pdf_show_boxed($pdf, $phrase[54].''.$phrase[54].''.$phrase[54].''.$phrase[54].''.$phrase[54].''.$phrase[60].''.$phrase[60], 145, 110, 375, 14, 'left', ""); # ----- Remarques du responsable
			pdf_show_boxed($pdf, $phrase[54].''.$phrase[54].''.$phrase[54].''.$phrase[54].''.$phrase[54].''.$phrase[60].''.$phrase[60], 145, 95, 375, 14, 'left', ""); # ----- Remarques du responsable
			pdf_show_boxed($pdf, $phrase[58], 5, 70, 185, 14, 'left', ""); # Remarques du responsable
			pdf_show_boxed($pdf, $phrase[59], 5, 50, 485, 14, 'left', ""); # Remarques du responsable

		#### Pied de Page    ########################################
		#															#
			pdf_setfont($pdf, $TimesRoman, 10);

			pdf_show_boxed($pdf, $phrase[7] ,0 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
			pdf_show_boxed($pdf, $phrase[8] , $LargeurUtile / 3,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
			pdf_show_boxed($pdf, $phrase[9] , $LargeurUtile * 2 / 3 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire

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