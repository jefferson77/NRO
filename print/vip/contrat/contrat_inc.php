<?php
#########################################################################################################################################################################
### Page de contrat VIP #################################################################################################################################################
#########################################################################################################################################################################
# TODO : !! moteur PDFlib, a remplacer
require_once(NIVO."nro/fm.php");
require_once(NIVO.'classes/vip.php');
require_once(NIVO.'classes/document.php');
require_once(NIVO.'classes/payement.php');

# renvoie les path 'dossier', 'full', 'history', 'filename'
function path_contratvip($idvip) {
	global $DB;
	$row = $DB->getRow("SELECT idvip, idpeople, idvipjob FROM vipmission WHERE idvip = ".$idvip);
		
## declarations
    $fname = prezero($row['idvip'], 6).'-'.prezero($row['idpeople'], 5);

    $lespath['dossier'] = Conf::read('Env.root').'document/vip/'.prezero($row['idvipjob'], 5).'/contrat/';
    $lespath['filename'] = $fname.'.pdf';
    $lespath['full'] = $lespath['dossier'].$lespath['filename'];
    $lespath['history'] = Conf::read('Env.root').'history/vip/'.prezero($row['idvipjob'], 5).'/contrat/';

    if (file_exists($lespath['full'])) $lespath['historyfull'] = $lespath['history'].$fname.'-'.date ("Ymd.Hi", filemtime($lespath['full'])).'.pdf';

## check et creation si besoin
    if(!is_dir($lespath['dossier'])) mkdir($lespath['dossier'], 0777, true); #le 'true' c'est pour le recursif
    if(!is_dir($lespath['history'])) mkdir($lespath['history'], 0777, true); 

    return $lespath;
}

# sauve un contrat vip d'une mission donnée et retourne le chemin du fichier
function print_contratvip($idvip) {

###### GEt infos ######
	global $DB;	
	$DB->inline("SET NAMES latin1");
	$row = $DB->getRow("SELECT
				v.idvipjob, v.idvip, v.idpeople, v.vipdate, v.vcat, v.vdisp, v.vkm, v.vfkm, v.vipactivite, v.brk, v.vipin, v.vipout, v.notes,
				j.reference, j.briefing, j.idagent,
				p.lbureau, p.pprenom, p.pnom, p.codepeople, p.bte1, p.adresse1, p.num1, p.cp1, p.ville1, p.banque, p.ndate, p.nville, p.idpeople, p.gsm, YEAR(p.ndate) as nyear,
				a.prenom as agentprenom, a.nom as agentnom, a.atel, a.agsm, a.email,
				s.societe, s.adresse, s.cp as shopcp, s.ville as shopville,
				nf.montantpaye
				FROM vipmission v
				LEFT JOIN vipjob j ON v.idvipjob = j.idvipjob
				LEFT JOIN shop s ON v.idshop = s.idshop
				LEFT JOIN people p ON v.idpeople = p.idpeople
				LEFT JOIN agent a ON j.idagent = a.idagent
				LEFT JOIN notefrais nf ON nf.secteur = 'VI' and nf.idmission = v.idvip
				WHERE v.idvip = ".$idvip);

	if (!empty($row['idpeople'])) {
		
	###### check dir ######
		$path = path_contratvip($row['idvip']);

		#if (hashcheck(implode("|",$row), $path['full']) == 'new') { # verifie si les données du PDF ont changé, si oui, recree un PDF, si non renvoie l'ancien
		if(true){
		###### check file ######
			if (file_exists($path['full'])) rename($path['full'], $path['historyfull']);
	
		###### create PDF ######
			$pdf = pdf_new();
			pdf_open_file($pdf, $path['full']); # définit l'emplacement de la sauvegarde
			# Infos pour le document
			pdf_set_info($pdf, "Author", $_SESSION['prenom'].' '.$_SESSION['nom']);
			pdf_set_info($pdf, "Title", "Contrat Exception ");
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
			$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");
			$TimesBold = PDF_load_font($pdf, "Times-Bold", "host", "");
			$TimesItalic = PDF_load_font($pdf, "Times-Italic", "host", "");
			$Helvetica = PDF_load_font($pdf, "Helvetica", "host", "");
			$HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");
			
				#### Mise à jour de la fiche : datecontrat
				$DB->inline("UPDATE vipmission SET datecontrat = '".date("Y-m-d")."' WHERE idvip = ".$row['idvip']);	
				
		
				################### Phrasebook ########################
				$xp = 1988 - $row['nyear']; # calcul des années d'expériences pour la clause des barèmes
				$xp = ($xp < 1)?1:$xp;
				
				switch ($row['lbureau']) {
						case "NL": 
							include $_SERVER["DOCUMENT_ROOT"].'/print/vip/contrat/nl.php';
							setlocale(LC_TIME, 'nl_NL');
						break;
						case "FR": 
							include $_SERVER["DOCUMENT_ROOT"].'/print/vip/contrat/fr.php';
							setlocale(LC_TIME, 'fr_FR');
						break;
						default:
							$phrase = array('');
							echo '<br> Langue pas d&eacute;finie pour le promoboy : '.$row['pprenom']." ".$row['pnom'];
				}
				################### Phrasebook ########################
			
				$fich = new corevip ($row['idvip']);
			
				pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
				pdf_create_bookmark($pdf, $phrase[1]." ".$row['idvip'], "");
				
				pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche
		
				# illu
				$logobig = PDF_load_image($pdf, "png", $_SERVER["DOCUMENT_ROOT"].'/print/illus/logoPrint.png', "");
				pdf_place_image($pdf, $logobig, 30, 646, 0.4);
				
				# Cadres
				pdf_rect($pdf, 0, 620, 270, 110);
				pdf_rect($pdf, 270, 620, 264, 110);
				pdf_rect($pdf, 484, 715, 50, 15);
				pdf_rect($pdf, 0, 530, 534, 75);
				pdf_rect($pdf, 0, 445, 534, 85);
			
				pdf_rect($pdf, 0, 445, 260, 30);
				pdf_rect($pdf, 260, 445, 274, 85);
				pdf_rect($pdf, 0, 360, 534, 85);
				pdf_rect($pdf, 0, 260, 300, 100);
				pdf_rect($pdf, 300, 260, 234, 100);
				pdf_rect($pdf, 0, 120, 534, 140);
				pdf_stroke($pdf);
				
				pdf_setcolor($pdf, "fill", "gray", 0.8, 0 ,0 ,0);
				if($row['lbureau'] =="FR") {
					pdf_rect($pdf, 0, 745, 240, 47);
				} else {
					pdf_rect($pdf, 0, 745, 220, 47);
				}
				pdf_fill_stroke($pdf);
			
				pdf_setcolor($pdf, "fill", "gray", 0, 0 ,0 ,0);
				
			
				# Textes
				pdf_setfont($pdf, $Helvetica, 17); pdf_set_value ($pdf, "leading", 24);
				pdf_show_boxed($pdf, $phrase[2] , 155 , 755 , 373, 25 , 'right', ""); # Titre
				
				# Textes
				pdf_setfont($pdf, $TimesRoman, 9); pdf_set_value ($pdf, "leading", 10);
				pdf_show_boxed($pdf, $row['codepeople'], 486 , 717 , 45, 12 , 'center', ""); # Titre
				
				# Textes
				pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, $phrase[3], 0, 730, 265, 15, 'right', ""); # Titre
				pdf_show_boxed($pdf, $phrase[4], 330, 730, 200, 15, 'right', ""); # Titre
			
				pdf_setfont($pdf, $TimesBold, 14); pdf_set_value ($pdf, "leading", 17);
				# pdf_show_boxed($pdf, $phrase[5], 10, 630, 160, 55, 'left', ""); # Titre
				
				pdf_setfont($pdf, $TimesBold, 14); pdf_set_value ($pdf, "leading", 20);;
				$bte = '';
				if (($row['bte1'] != '') and ($row['bte1'] != ' ')) {$bte = $phrase[32];}
				pdf_show_boxed($pdf, $row['pprenom']." ".$row['pnom']."
		".$row['adresse1'].", ".$row['num1']." ".$bte." ".$row['bte1']."
		".$row['cp1']." ".$row['ville1']."
		".$row['gsm'], 290, 640, 225, 80, 'left', ""); # Titre
			
				# Textes
				pdf_setfont($pdf, $Helvetica, 11); pdf_set_value ($pdf, "leading", 15);
				pdf_show_boxed($pdf, $phrase[6], 20, 535, 200, 50, 'center', ""); # Titre
			
				pdf_setfont($pdf, $Helvetica, 11); pdf_set_value ($pdf, "leading", 16);
				pdf_show_boxed($pdf, $phrase[7], 230, 534, 160, 70, 'right', ""); # Titre
				pdf_show_boxed($pdf, $row['banque']."\r".$row['codepeople']."\r".fdate($row['ndate'])."\r".$row['nville'], 400, 533, 140, 70, 'left', ""); # Titre
			
				pdf_setfont($pdf, $TimesRoman, 12); pdf_set_value ($pdf, "leading", 17);
				# pdf_show_boxed($pdf, $phrase[8], 110, 610, 160, 40, 'center', ""); # Titre
			
				pdf_setfont($pdf, $TimesBold, 12); pdf_set_value ($pdf, "leading", 15);
				pdf_show_boxed($pdf, $phrase[9].$row['idvip']."\r".$phrase[10].$row['idvipjob'].") ".$row['reference'], 15, 475, 250, 50, 'left', ""); # Titre
			
				$plode = explode('-', $row['vipdate']);
				
				$ladate = strftime("%a %d/%m/%Y", mktime(0,0,0,$plode[1],$plode[2],$plode[0]) );
				$frais = fpeuro($row['vcat'] + $row['vdisp'] + $row['montantpaye']); # ??? faut il laisser le montant paie de la note de frais ?
			
				pdf_show_boxed($pdf, $phrase[11].$ladate , 5, 417, 110, 30, 'center', ""); # Titre
				pdf_show_boxed($pdf, $phrase[12].fnbr($row['vkm']) , 170, 417, 130, 30, 'center', ""); # Titre
				pdf_show_boxed($pdf, $phrase[13].fpeuro($row['vfkm']) , 350, 417, 70, 30, 'center', ""); # Titre
				pdf_show_boxed($pdf, $phrase[14].$frais , 460, 417, 50, 30, 'center', ""); # Titre
				
				pdf_show_boxed($pdf, $phrase[31].$row['vipactivite'] , 5, 385, 275, 30, 'left', ""); # Titre
			
				$break = '';
				if ($row['brk'] > 0) {$break = $phrase[15].fnbr($row['brk']).$phrase[16];}
				
				pdf_show_boxed($pdf, $phrase[17].ftime($row['vipin']).$phrase[18].ftime($row['vipout']).$break.$phrase[19].$fich->thpaye.$phrase[34].fpeuro(salaire($row['idpeople'], $row['vipdate'])).$phrase[33] , 5, 370, 475, 30, 'left', ""); # Titre
			
				pdf_setfont($pdf, $TimesRoman, 12); pdf_set_value ($pdf, "leading", 15);
		
				pdf_show_boxed($pdf, $phrase[20]." ".$row['agentprenom']." ".$row['agentnom']."\r".$row['atel'], 5, 447, 240, 30, 'left', ""); # Titre
				pdf_show_boxed($pdf, $phrase[21]."\r".$row['societe']."\r".$row['adresse']."\r".$row['shopcp']." ".$row['shopville'], 280, 450, 245, 75, 'center', ""); # Titre
			
				pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, $phrase[22].$row['briefing']."\r".$row['notes'], 5, 265, 285, 95, 'left', ""); # Titre
				
				pdf_show_boxed($pdf, $phrase[23], 305, 265, 225, 95, 'left', ""); # Matos prêté
				
				//affichage du matos prêté
				$r = $DB->getColumn("SELECT mnom FROM matos WHERE idpeople = ".$row['idpeople']);
				
				if(is_array($r)) $matos = implode("\r", $r); else $matos = "";

				pdf_show_boxed($pdf, $matos, 305, 250, 225, 95, 'left', ""); # Matos prêté
				//
				pdf_setfont($pdf, $TimesBold, 12); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, $phrase[24], 0, 100, 180, 20, 'center', ""); # Titre
				pdf_show_boxed($pdf, $phrase[25], 178, 100, 180, 20, 'center', ""); # Titre
				pdf_show_boxed($pdf, $phrase[26], 355, 100, 180, 20, 'center', ""); # Titre
			
				pdf_setfont($pdf, $TimesBold, 10); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, $row['agentprenom']." ".$row['agentnom']."\r".$row['atel']." - ".$row['agsm']."\r".$row['email'], 0, 26, 180, 55, 'center', ""); # Titre

			#### Pied de Page    ########################################
			#															#
				# Clauses
				pdf_setfont($pdf, $TimesItalic, 7.5); pdf_set_value ($pdf, "leading", 8);
				pdf_show_boxed($pdf, $phrase[27] , 5 , 120 , 525, 140 , 'justify', "");
				
				# Ligne de bas de page
				pdf_moveto($pdf, 0, 40);
				pdf_lineto($pdf, $LargeurUtile, 40);
				pdf_stroke($pdf); # Ligne de bas de page
				
				# Coordonnées Exception
				pdf_setfont($pdf, $TimesRoman, 10);
				
				pdf_show_boxed($pdf, $phrase[28] ,0 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
				pdf_show_boxed($pdf, $phrase[29] , $LargeurUtile / 3,0 , $LargeurUtile / 3,40, 'center', ""); #texte du commentaire
				pdf_show_boxed($pdf, $phrase[30] , $LargeurUtile * 2 / 3 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
				pdf_setfont($pdf, $HelveticaBold, 10);
				pdf_show_boxed($pdf, $phrase[35], 5, 690, 235, 100, 'left', ""); # Matos prêté
				pdf_setfont($pdf, $Helvetica, 10);
				
				pdf_show_boxed($pdf, $phrase[36], 5, 690, 235, 70, 'left', ""); # Matos prêté
				
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