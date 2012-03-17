<?php
#########################################################################################################################################################################
### Page de Rapport EAS MERCH ###########################################################################################################################################
#########################################################################################################################################################################
# TODO : !! moteur PDFlib, a remplacer
require_once($_SERVER["DOCUMENT_ROOT"].'/nro/fm.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/classes/merch.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/classes/document.php');

##############################################################################################################################
# renvoie les path 'dossier', 'full', 'history', 'filename'
function path_rapporteasmerch($idshop, $sem, $year, $idpeople) {

## declarations
    $fname = prezero($idshop, 5).'-'.prezero($idpeople, 5);

    $lespath['dossier'] = Conf::read('Env.root').'document/merch/eas/rapport/'.$year.'-'.prezero($sem, 2).'/';
    $lespath['filename'] = $fname.'.pdf';
    $lespath['full'] = $lespath['dossier'].$lespath['filename'];
    $lespath['history'] = Conf::read('Env.root').'history/merch/eas/rapport/'.$year.'-'.prezero($sem, 2).'/';

    if (file_exists($lespath['full'])) $lespath['historyfull'] = $lespath['history'].$fname.'-'.date ("Ymd.Hi", filemtime($lespath['full'])).'.pdf';

## check et creation si besoin
    if(!is_dir($lespath['dossier'])) mkdir($lespath['dossier'], 0777, true); #le 'true' c'est pour le recursif
    if(!is_dir($lespath['history'])) mkdir($lespath['history'], 0777, true); 

    return $lespath;
}

##############################################################################################################################
# sauve un contrat vip d'une mission donnée et retourne le chemin du fichier
function print_rapporteasmerch($idshop, $sem, $year, $idpeople) {
###### GEt infos ######
	global $DB;
    $DB->inline("SET NAMES latin1");
    $rows = $DB->getArray("SELECT
		me.idmerch, me.idshop, me.datem, me.hin1, me.hout1, me.hin2, me.hout2, 
		a.agsm, a.nom, a.prenom, 
		p.codepeople, p.lbureau, p.pnom, p.pprenom, 
		s.societe, s.ville
	FROM merch me
		LEFT JOIN agent a ON me.idagent = a.idagent
		LEFT JOIN people p ON me.idpeople = p.idpeople
		LEFT JOIN shop s ON me.idshop = s.idshop
	WHERE me.idshop = '".$idshop."'
		AND me.idpeople = '".$idpeople."'
		AND me.genre = 'EAS'
		AND me.yearm = '".$year."'
		AND me.weekm = '".$sem."'");

	if (!empty($idpeople)) {

	###### check dir ######
		$path = path_rapporteasmerch($idshop, $sem, $year, $idpeople);
		
		## implode weekmis
		$hashdata = "";
		foreach ($rows as $row) {
			$hashdata .= implode("|", $row);
		}

		if (hashcheck($hashdata, $path['full']) == 'new') { # verifie si les données du PDF ont changé, si oui, recree un PDF, si non renvoie l'ancien
		###### check file ######
			if (file_exists($path['full'])) rename($path['full'], $path['historyfull']);
			
			foreach($rows as $eas) {
				
			## Communs ##
				$EAS['agent'] = $eas['prenom'].' '.$eas['nom'].' - '.$eas['agsm'];
				$EAS['people'] = $eas['pprenom'].' '.$eas['pnom'].' ('.$eas['codepeople'].')';
				$EAS['shop'] = $eas['societe'].' '.$eas['ville'].' ('.$eas['idshop'].')';
				$EAS['pagename'] = $eas['idshop'].' '.$eas['societe'].' '.$eas['ville'];
				$EAS['langue'] = $eas['lbureau'];

			## Jours ##
				setlocale(LC_TIME, strtolower($eas['lbureau']).'_'.strtoupper($eas['lbureau']));

				$mkdate = strtotime($eas['datem']);
				$day = date("w", $mkdate); # 0 = dimanche, 6 = Samedi
				$ladate = date("d/m", $mkdate);
				$lejour = strftime("%A", $mkdate);
				$year = date("Y", $mkdate);
				$mois = date("m", $mkdate);

				$EAS['days'][$day]['jour'] = $lejour;
				$EAS['days'][$day]['date'] = $ladate;
				$EAS['days'][$day]['idmerch'] = 'Job ID : '.$eas['idmerch'];
				$EAS['days'][$day]['amin'] = ftime($eas['hin1']);
				$EAS['days'][$day]['amout'] = ftime($eas['hout1']);
				$EAS['days'][$day]['pmin'] = ftime($eas['hin2']);
				$EAS['days'][$day]['pmout'] = ftime($eas['hout2']);

				$merch = new coremerch($eas['idmerch']);

				$EAS['days'][$day]['toth'] = $merch->hprest;

				$EAS['totheures'] += $EAS['days'][$day]['toth'];
			}

			switch ($EAS['langue']) {
				case "FR":
					include $_SERVER["DOCUMENT_ROOT"].'/print/merch/eas/fr.php';
				break;

				case "NL":
					include $_SERVER["DOCUMENT_ROOT"].'/print/merch/eas/nl.php';
				break;

				default:
				echo "<br>Langue manquante pour le people : ".$EAS['people']."<br>";
			}

			$pdf = pdf_new();
			pdf_open_file($pdf, $path['full']);

			# Infos pour le document
			pdf_set_info($pdf, "Author", "neuro");
			pdf_set_info($pdf, "Title", "Rapport EAS");
			pdf_set_info($pdf, "Creator", "NEURO");
			pdf_set_info($pdf, "Subject", "Rapport EAS");

			### Declaration des fontes
			$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");
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

			pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
			# pdf_create_bookmark($pdf, $EAS['pagename'], "");
			pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche
			### Titre Page
			pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);
			pdf_rect($pdf, 0, 762, 534, 16);
			pdf_rect($pdf, 0, 603, 534, 11);
			pdf_fill_stroke($pdf);						#
			## Cadres infos
			pdf_rect($pdf, 200, 706, 334, 50);
			pdf_rect($pdf, 200, 656, 334, 50);
			pdf_stroke($pdf);
			## Cadres Jours 25%
			pdf_setcolor($pdf, "fill", "gray", 0.75, 0, 0, 0);
			$tab = 620;
			pdf_rect($pdf, 0, $tab, 109, 30);
			pdf_rect($pdf, 110, $tab, 84, 30);
			pdf_rect($pdf, 195, $tab, 84, 30);
			pdf_rect($pdf, 280, $tab, 84, 30);
			pdf_rect($pdf, 365, $tab, 84, 30);
			pdf_rect($pdf, 450, $tab, 84, 30);
			pdf_fill_stroke($pdf);						#

			pdf_setcolor($pdf, "fill", "gray", 1, 0, 0, 0);

			pdf_setfont($pdf, $HelveticaBold, 14); pdf_set_value ($pdf, 'leading', 14);
			pdf_show_boxed($pdf, $phrase[0] , 0, 762, 534, 17 , 'center', "");
			$tab = 605;
			pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, 'leading', 10);
			pdf_show_boxed($pdf, $phrase[3] , 110, $tab, 28, 10 , 'center', "");
			pdf_show_boxed($pdf, $phrase[1] , 138, $tab, 28, 10 , 'center', "");
			pdf_show_boxed($pdf, $phrase[2] , 166, $tab, 28, 10 , 'center', "");
			pdf_show_boxed($pdf, $phrase[3] , 195, $tab, 28, 10 , 'center', "");
			pdf_show_boxed($pdf, $phrase[1] , 223, $tab, 28, 10 , 'center', "");
			pdf_show_boxed($pdf, $phrase[2] , 251, $tab, 28, 10 , 'center', "");
			pdf_show_boxed($pdf, $phrase[3] , 280, $tab, 28, 10 , 'center', "");
			pdf_show_boxed($pdf, $phrase[1] , 308, $tab, 28, 10 , 'center', "");
			pdf_show_boxed($pdf, $phrase[2] , 336, $tab, 28, 10 , 'center', "");
			pdf_show_boxed($pdf, $phrase[3] , 365, $tab, 28, 10 , 'center', "");
			pdf_show_boxed($pdf, $phrase[1] , 393, $tab, 28, 10 , 'center', "");
			pdf_show_boxed($pdf, $phrase[2] , 421, $tab, 28, 10 , 'center', "");
			pdf_show_boxed($pdf, $phrase[3] , 450, $tab, 28, 10 , 'center', "");
			pdf_show_boxed($pdf, $phrase[1] , 478, $tab, 28, 10 , 'center', "");
			pdf_show_boxed($pdf, $phrase[2] , 506, $tab, 28, 10 , 'center', "");

			pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);

			## Adresse Xception
			pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, 'leading', 10);
			pdf_show_boxed($pdf, $phrase[4] , 0, 744, 200, 13 , 'center', "");
			pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, 'leading', 9);
			pdf_show_boxed($pdf, $phrase[5] , 0, 720, 200, 22 , 'center', "");
			pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, 'leading', 9); # fax
			pdf_show_boxed($pdf, 'Fax : 02 / 732 79 38' , 0, 700, 200, 15 , 'center', ""); # fax

			## Titres People
			pdf_setfont($pdf, $HelveticaBold, 9); pdf_set_value ($pdf, 'leading', 9);
			pdf_show_boxed($pdf, $phrase[6] , 200, 738, 110, 10 , 'right', "");
			pdf_show_boxed($pdf, $phrase[7] , 200, 726, 110, 10 , 'right', "");
			pdf_show_boxed($pdf, $phrase[8] , 200, 714, 110, 10 , 'right', "");
			## Valeurs People
			pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, 'leading', 9);
			pdf_show_boxed($pdf, $EAS['people'] , 320, 738, 214, 10 , 'left', "");
			pdf_show_boxed($pdf, $EAS['shop'] , 320, 726, 214, 10 , 'left', "");
			pdf_show_boxed($pdf, $EAS['totheures'].$phrase[9] , 320, 714, 214, 10 , 'left', "");
			## Infos Responsable
			pdf_setfont($pdf, $HelveticaBold, 9); pdf_set_value ($pdf, 'leading', 9);
			pdf_show_boxed($pdf, $phrase[10] , 0, 691, 200, 10 , 'center', "");
			pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, 'leading', 9);
			pdf_show_boxed($pdf, $EAS['agent'] , 0, 677, 200, 10 , 'center', "");
			pdf_show_boxed($pdf, $phrase[29] , 0, 666, 200, 10 , 'center', "");
			## Cachet
			pdf_setfont($pdf, $HelveticaBold, 8); pdf_set_value ($pdf, 'leading', 8);
			pdf_show_boxed($pdf, $phrase[11] , 200, 695, 60, 10 , 'center', "");
			## Case Semaine
			pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, 'leading', 12);
			pdf_show_boxed($pdf, $phrase[12]."\r".$year.' '.$sem , 0, 620, 109, 30 , 'center', "");
			## Noms jours
			$tab = 640;
			pdf_setfont($pdf, $HelveticaBold, 8); pdf_set_value ($pdf, 'leading', 8);
			pdf_show_boxed($pdf, $EAS['days'][1]['jour'] , 110, $tab, 84, 9 , 'center', "");
			pdf_show_boxed($pdf, $EAS['days'][2]['jour'] , 195, $tab, 84, 9 , 'center', "");
			pdf_show_boxed($pdf, $EAS['days'][3]['jour'] , 280, $tab, 84, 9 , 'center', "");
			pdf_show_boxed($pdf, $EAS['days'][4]['jour'] , 365, $tab, 84, 9 , 'center', "");
			pdf_show_boxed($pdf, $EAS['days'][5]['jour'] , 450, $tab, 84, 9 , 'center', "");
			## Dates
			$tab = 628;
			pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, 'leading', 10);
			pdf_show_boxed($pdf, $EAS['days'][1]['date'] , 110, $tab, 84, 12 , 'center', "");
			pdf_show_boxed($pdf, $EAS['days'][2]['date'] , 195, $tab, 84, 12 , 'center', "");
			pdf_show_boxed($pdf, $EAS['days'][3]['date'] , 280, $tab, 84, 12 , 'center', "");
			pdf_show_boxed($pdf, $EAS['days'][4]['date'] , 365, $tab, 84, 12 , 'center', "");
			pdf_show_boxed($pdf, $EAS['days'][5]['date'] , 450, $tab, 84, 12 , 'center', "");
			## ID missions
			$tab = 621;
			pdf_setfont($pdf, $Helvetica, 6); pdf_set_value ($pdf, 'leading', 6);
			pdf_show_boxed($pdf, $EAS['days'][1]['idmerch'] , 110, $tab, 84, 8 , 'center', "");
			pdf_show_boxed($pdf, $EAS['days'][2]['idmerch'] , 195, $tab, 84, 8 , 'center', "");
			pdf_show_boxed($pdf, $EAS['days'][3]['idmerch'] , 280, $tab, 84, 8 , 'center', "");
			pdf_show_boxed($pdf, $EAS['days'][4]['idmerch'] , 365, $tab, 84, 8 , 'center', "");
			pdf_show_boxed($pdf, $EAS['days'][5]['idmerch'] , 450, $tab, 84, 8 , 'center', "");

			$tab = 604;

			$structure = array(
				array('titre1', $phrase[14]),
				array('line', 'mfo1a', '!'),
				array('line', 'mfo2a', ''),
				array('line', 'mfo3a', ''),
				array('line', 'mfo4a', ''),
				array('spacer'),
				array('titre1', $phrase[15]),
				array('titre2', $phrase[16]),
				array('line', 'mpa1a', '!'),
				array('line', 'mpa2a', '!'),
				array('line', 'mpa3a', '!'),
				array('line', 'mpa4a', '!'),
				array('line', 'mpa5a', ''),
				array('line', 'mpa6a', ''),
				array('line', 'mpa7a', ''),
				array('line', 'mpa8a', ''),
				array('line', 'mpa9a', ''),
				array('line', 'mpa10a', '!'),
				array('spacer'),
				array('titre2', $phrase[17]),
				array('line', 'mte1a', '!'),
				array('line', 'mte2a', ''),
				array('line', 'mte3a', ''),
				array('line', 'mte4a', ''),
				array('line', 'mte5a', '!'),
				array('line', 'mte6a', ''),
				array('line', 'mte7a', ''),
				array('line', 'mte8a', ''),
				array('spacer'),
				array('titre2', $phrase[18]),
				array('line', 'mep1a', ''),
				array('line', 'mep2a', '!'),
				array('line', 'mep3a', ''),
				array('line', 'mep4a', ''),
				array('line', 'mep5a', ''),
				array('spacer'),
				array('titre2', $phrase[19]),
				array('line', 'mba1a', ''),


				array('line', 'mba2a', '!'),
				array('line', 'mba3a', ''),
				array('line', 'mba4a', ''),
				array('line', 'mba5a', ''),
				array('line', 'mba6a', ''),
				array('spacer'),
				array('titre2', $phrase[20]),
				array('line', '', ''),
				array('line', '', ''),
				array('line', '', ''),
				array('line', '', ''),
				array('spacer'),
				array('horaires'),
				array('spacer'),
				array('caisse')
			);


			foreach ($structure as $vals) {
				switch ($vals[0]) {

						case "titre1": 
							$tab -= 11;
							pdf_setcolor($pdf, "fill", "gray", 0.5, 0, 0, 0);
							pdf_rect($pdf, 0, $tab, 534, 11);
							pdf_fill_stroke($pdf);						#
							pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);
							pdf_setfont($pdf, $HelveticaBold, 9); pdf_set_value ($pdf, 'leading', 9);
							pdf_show_boxed($pdf, $vals[1] , 0, $tab, 110, 11 , 'center', "");
						break;

						case "titre2": 
							$tab -= 11;
							pdf_setcolor($pdf, "fill", "gray", 0.75, 0, 0, 0);
							pdf_rect($pdf, 0, $tab, 534, 11);
							pdf_fill_stroke($pdf);						#
							pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);
							pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, 'leading', 9);
							pdf_show_boxed($pdf, $vals[1] , 0, $tab, 110, 11 , 'center', "");
						break;

						case "spacer": 
							$tab -= 6;
						break;

						case "line": 
							$tab -= 11;
							## Cadres
							pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);
							pdf_rect($pdf, 0, $tab, 109, 11);

							pdf_rect($pdf, 110, $tab, 28, 11);
							pdf_rect($pdf, 138, $tab, 28, 11);
							pdf_rect($pdf, 166, $tab, 28, 11);

							pdf_rect($pdf, 195, $tab, 28, 11);
							pdf_rect($pdf, 223, $tab, 28, 11);
							pdf_rect($pdf, 251, $tab, 28, 11);

							pdf_rect($pdf, 280, $tab, 28, 11);
							pdf_rect($pdf, 308, $tab, 28, 11);
							pdf_rect($pdf, 336, $tab, 28, 11);

							pdf_rect($pdf, 365, $tab, 28, 11);
							pdf_rect($pdf, 393, $tab, 28, 11);
							pdf_rect($pdf, 421, $tab, 28, 11);

							pdf_rect($pdf, 450, $tab, 28, 11);
							pdf_rect($pdf, 478, $tab, 28, 11);
							pdf_rect($pdf, 506, $tab, 28, 11);

							pdf_stroke($pdf);
							## Texte
							pdf_setfont($pdf, $TimesRoman, 8); pdf_set_value ($pdf, 'leading', 8);
							pdf_show_boxed($pdf, $phrase[$vals[1]] , 2, $tab, 108, 11 , 'left', "");
							pdf_show_boxed($pdf, $vals[2] , 100, $tab, 8, 11 , 'center', "");
						break;

						case "horaires":
							$tab -= 21; #22
							## Cadres
							pdf_rect($pdf, 0, $tab, 109, 21);
							pdf_rect($pdf, 110, $tab, 84, 21);
							pdf_rect($pdf, 195, $tab, 84, 21);
							pdf_rect($pdf, 280, $tab, 84, 21);
							pdf_rect($pdf, 365, $tab, 84, 21);
							pdf_rect($pdf, 450, $tab, 84, 21);
							pdf_stroke($pdf);

							## titres
							pdf_setfont($pdf, $TimesRoman, 8); pdf_set_value ($pdf, 'leading', 8);
							pdf_show_boxed($pdf, $phrase[22] , 0, $tab + 10, 108, 11 , 'center', "");
							pdf_setfont($pdf, $TimesRoman, 7); pdf_set_value ($pdf, 'leading', 7);
							pdf_show_boxed($pdf, $phrase[27] , 87, $tab, 23, 11 , 'center', "");
							pdf_show_boxed($pdf, $phrase[28] , 87, $tab + 10, 23, 11 , 'center', "");

							## heures
							pdf_setfont($pdf, $TimesRoman, 7); pdf_set_value ($pdf, 'leading', 7);
							pdf_show_boxed($pdf, $phrase[23] , 135, $tab + 3, 12, 9 , 'center', "");
							pdf_show_boxed($pdf, $phrase[23] , 135, $tab + 13, 12, 9 , 'center', "");
							pdf_show_boxed($pdf, $phrase[23] , 220, $tab + 3, 12, 9 , 'center', "");
							pdf_show_boxed($pdf, $phrase[23] , 220, $tab + 13, 12, 9 , 'center', "");
							pdf_show_boxed($pdf, $phrase[23] , 305, $tab + 3, 12, 9 , 'center', "");
							pdf_show_boxed($pdf, $phrase[23] , 305, $tab + 13, 12, 9 , 'center', "");
							pdf_show_boxed($pdf, $phrase[23] , 390, $tab + 3, 12, 9 , 'center', "");
							pdf_show_boxed($pdf, $phrase[23] , 390, $tab + 13, 12, 9 , 'center', "");
							pdf_show_boxed($pdf, $phrase[23] , 475, $tab + 3, 12, 9 , 'center', "");
							pdf_show_boxed($pdf, $phrase[23] , 475, $tab + 13, 12, 9 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][1]['pmin'] , 110, $tab, 25, 11 , 'center', "");			# Lun PM in
							pdf_show_boxed($pdf, $EAS['days'][1]['amin'] , 110, $tab + 10, 25, 11 , 'center', "");	# Lun AM in
							pdf_show_boxed($pdf, $EAS['days'][1]['pmout'] , 145, $tab, 25, 11 , 'center', "");			# Lun PM out
							pdf_show_boxed($pdf, $EAS['days'][1]['amout'] , 145, $tab + 10, 25, 11 , 'center', "");	# Lun AM out
							pdf_show_boxed($pdf, $EAS['days'][2]['pmin'] , 195, $tab, 25, 11 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][2]['amin'] , 195, $tab + 10, 25, 11 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][2]['pmout'] , 230, $tab, 25, 11 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][2]['amout'] , 230, $tab + 10, 25, 11 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][3]['pmin'] , 280, $tab, 25, 11 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][3]['amin'] , 280, $tab + 10, 25, 11 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][3]['pmout'] , 315, $tab, 25, 11 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][3]['amout'] , 315, $tab + 10, 25, 11 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][4]['pmin'] , 365, $tab, 25, 11 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][4]['amin'] , 365, $tab + 10, 25, 11 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][4]['pmout'] , 400, $tab, 25, 11 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][4]['amout'] , 400, $tab + 10, 25, 11 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][5]['pmin'] , 450, $tab, 25, 11 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][5]['amin'] , 450, $tab + 10, 25, 11 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][5]['pmout'] , 485, $tab, 25, 11 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][5]['amout'] , 485, $tab + 10, 25, 11 , 'center', "");

							## totaux
							pdf_setfont($pdf, $TimesRoman, 13); pdf_set_value ($pdf, 'leading', 13);
							pdf_show_boxed($pdf, $EAS['days'][1]['toth'].$phrase[24] , 170, $tab + 2, 25, 18 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][2]['toth'].$phrase[24] , 255, $tab + 2, 25, 18 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][3]['toth'].$phrase[24] , 340, $tab + 2, 25, 18 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][4]['toth'].$phrase[24] , 425, $tab + 2, 25, 18 , 'center', "");
							pdf_show_boxed($pdf, $EAS['days'][5]['toth'].$phrase[24] , 510, $tab + 2, 25, 18 , 'center', "");
						break;

						case "caisse": 
							$tab -= 10;

							## titres cadre
							pdf_setcolor($pdf, "fill", "gray", 0.80, 0, 0, 0);
							pdf_rect($pdf, 0, $tab, 109, 10);

							$start = 110;
							$stop = 479;
							$nombre = 36;

							for ($i = $start; $i < $stop; $i += (($stop - $start) / $nombre)) {
								pdf_rect($pdf, $i, $tab, (($stop - $start) / $nombre), 10);
							}

							pdf_rect($pdf, 480, $tab, 54, 10);
							pdf_fill_stroke($pdf);						#
							pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);
							## textes titre
							pdf_setfont($pdf, $TimesRoman, 8); pdf_set_value ($pdf, 'leading', 8);
							pdf_show_boxed($pdf, $phrase[25] , 2, $tab, 108, 10 , 'center', "");

							pdf_setfont($pdf, $TimesRoman, 8); pdf_set_value ($pdf, 'leading', 8);

							$t=0;
							for ($i = $start; $i < $stop; $i += (($stop - $start) / $nombre)) {
								$t++;
								pdf_show_boxed($pdf, $t , $i, $tab, (($stop - $start) / $nombre), 10, 'center', "");
							}

							pdf_show_boxed($pdf, $phrase[26] , 480, $tab, 54, 10 , 'center', "");

							## datas		
							if (is_array($EAS['days'])) {
								foreach ($EAS['days'] as $key => $data) {
									if (!empty($data['date'])) {
										$tab -= 10;

										## Cadres
										pdf_rect($pdf, 0, $tab, 109, 10);

										for ($i = $start; $i < $stop; $i += (($stop - $start) / $nombre)) {
											pdf_rect($pdf, $i, $tab, (($stop - $start) / $nombre), 10);
										}

										pdf_rect($pdf, 480, $tab, 54, 10);
										pdf_stroke($pdf);

										pdf_setfont($pdf, $TimesRoman, 8); pdf_set_value ($pdf, 'leading', 8);
										pdf_show_boxed($pdf, $data['date'] , 2, $tab, 108, 10 , 'center', "");
									}
								}
							}
						break;
				}
			}

			pdf_end_page($pdf);

			pdf_end_document($pdf, '');
			pdf_delete($pdf);
		}

		return $path['full'];
	} else {
		return "";
	}
}
?>