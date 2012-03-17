<?php
#########################################################################################################################################################################
### Page de Semaine MERCH ###############################################################################################################################################
#########################################################################################################################################################################
# TODO : !! moteur PDFlib, a remplacer
require_once($_SERVER["DOCUMENT_ROOT"].'/nro/fm.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/classes/merch.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/classes/document.php');

##############################################################################################################################
# renvoie les path 'dossier', 'full', 'history', 'filename'
function path_semainemerch($genre, $sem, $year, $idpeople) {

## declarations
    $fname = prezero($idpeople, 5);

    $lespath['dossier'] = Conf::read('Env.root').'document/merch/'.cleanalpha($genre).'/semaine/'.$year.'-'.prezero($sem, 2).'/';
    $lespath['filename'] = $fname.'.pdf';
    $lespath['full'] = $lespath['dossier'].$lespath['filename'];
    $lespath['history'] = Conf::read('Env.root').'history/merch/'.cleanalpha($genre).'/semaine/'.$year.'-'.prezero($sem, 2).'/';

    if (file_exists($lespath['full'])) $lespath['historyfull'] = $lespath['history'].$fname.'-'.date ("Ymd.Hi", filemtime($lespath['full'])).'.pdf';

## check et creation si besoin
    if(!is_dir($lespath['dossier'])) mkdir($lespath['dossier'], 0777, true); #le 'true' c'est pour le recursif
    if(!is_dir($lespath['history'])) mkdir($lespath['history'], 0777, true); 

    return $lespath;
}

##############################################################################################################################
# sauve un contrat vip d'une mission donnée et retourne le chemin du fichier
function print_semainemerch($genre, $weekm, $yearm, $idpeople) {
###### GEt infos ######
	global $DB;
	$DB->inline("SET NAMES latin1");
    $rows = $DB->getArray("SELECT
				me.idmerch, me.datem, me.weekm, me.genre, YEAR(me.datem) as yearm,
					me.hin1, me.hout1, me.hin2, me.hout2,
					me.kmpaye, me.kmfacture, me.frais, me.fraisfacture,
					me.produit, me.facturation,
					me.ferie, me.contratencode,
				c.codeclient, c.societe AS clsociete, c.idclient, c.tel, c.fax,
				s.societe, s.adresse, s.cp AS shopcp, s.ville AS shopville,
				p.idpeople, p.pprenom, p.pnom, p.adresse1, p.num1, p.bte1, p.cp1, p.ville1, p.banque, p.codepeople, p.nville, p.ndate, p.lbureau,
				a.nom AS agentnom, a.prenom AS agentprenom, a.atel, a.agsm
			FROM merch me
				LEFT JOIN people p ON me.idpeople = p.idpeople
				LEFT JOIN client c ON me.idclient = c.idclient
				LEFT JOIN shop s ON me.idshop = s.idshop
				LEFT JOIN agent a ON me.idagent = a.idagent
			WHERE p.idpeople = ".$idpeople." AND YEAR(me.datem) = ".$yearm."  AND me.weekm = ".$weekm." AND me.genre LIKE '".$genre."'
			ORDER BY me.datem, me.hin1");

	if (!empty($rows[0]['idpeople'])) {

	###### check dir ######
		$path = path_semainemerch($genre, $weekm, $yearm, $idpeople);
		
		## implode weekmis
		$hashdata = "";
		foreach ($rows as $row) {
			$hashdata .= implode("|", $row);
		}

		//if (hashcheck($hashdata, $path['full']) == 'new') { # verifie si les données du PDF ont changé, si oui, recree un PDF, si non renvoie l'ancien
		###### check file ######
		if(true == true){
			/*
				TODO gaffe true = true
			*/
		$pdf = pdf_new();
		pdf_open_file($pdf, $path['full']);

		# Infos pour le document
		pdf_set_info($pdf, "Author", "neuro");
		pdf_set_info($pdf, "Title", "Contrat Exception");
		pdf_set_info($pdf, "Creator", "NEURO");
		pdf_set_info($pdf, "Subject", "Contrat");

		### Declaration des fontes
		$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");
		$TimesBold = PDF_load_font($pdf, "Times-Bold", "host", "");
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

		$tour = 1;
		$turntot = 0;

			$titre = 'go';
			$date2 = '0000-00-00';
			$datetemp = '00000000';
			$jour1 = 'lun';
			$jour2 = 'mar';
			$jour3 = 'mer';
			$jour4 = 'jeu';
			$jour5 = 'ven';
			$jour6 = 'sam';
			$jour7 = 'dim';
			$jourx1 = 'Lundi';
			$jourx2 = 'Mardi';
			$jourx3 = 'Mercredi';
			$jourx4 = 'Jeudi';
			$jourx5 = 'Vendredi';
			$jourx6 = 'Samedi';
			$jourx7 = 'Dimanche';
			$jj = 1;

			$dernier = count($rows);

		foreach($rows as $row) {

			$lignevide = 'oui';
			################### Phrasebook ########################
			switch ($row['lbureau']) {
					case "FR":
						include $_SERVER["DOCUMENT_ROOT"].'/print/merch/semaine/fr.php';
						setlocale(LC_TIME, 'fr_FR');
					break;
					case "NL":
						include $_SERVER["DOCUMENT_ROOT"].'/print/merch/semaine/nl.php';
						setlocale(LC_TIME, 'nl_NL');
						$jourx1 = 'Maandag';
						$jourx2 = 'Dinsdag';
						$jourx3 = 'Woensdag';
						$jourx4 = 'Donderdag';
						$jourx5 = 'Vrijdag';
						$jourx6 = 'Zaterdag';
						$jourx7 = 'Zondag';
					break;
					default:
						$phrase = array('');
						echo '<br> Langue pas d&eacute;finie pour le promoboy : '.$row['idpeople'].' '.$row['pprenom'].' '.$row['pnom'];
			}
			################### Phrasebook ########################

				if ($tour == 1) {

					# ##### change le changement de page pour que les totaux ne recouvrent pas le bas de page.
					$jump = 14;
					if ($genre == 'Rack assistance') {
						$jump = 8;
					}
					$reste -= $jump;
					# #######

					$np++;
					pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
					pdf_create_bookmark($pdf, $phrase[3].$np, "");

					pdf_rotate($pdf, 90);
					pdf_translate($pdf, $MargeBottom, $MargeRight - $LargeurPage); # Positionne le repère au point bas-gauche

					#### Entete de Page  ########################################
					#															#
						# illu
						$logobig = PDF_load_image($pdf, "png", $_SERVER["DOCUMENT_ROOT"].'/print/illus/logoPrint.png', "");
						//$haut = PDF_get_value($pdf, "imagewidth", $logobig) * 0.2; # Calcul de la hauteur
						pdf_place_image($pdf, $logobig, 660, 480, 0.25);

						# Titre
						pdf_setfont($pdf, $TimesBold, 14);
						pdf_set_value ($pdf, "leading", 14);
						pdf_show_boxed($pdf, $phrase[1]." ".$row['weekm']."\r".$genre, 258 , 440 , 425, 95 , 'left', ""); 		# Titre
						pdf_show_boxed($pdf, $row['pprenom'].' '.$row['pnom'] , 458 , 440 , 175, 95 , 'left', ""); 		# Titre
						if ($genre == 'Rack assistance') {
							pdf_show_boxed($pdf, $row['societe']." - ".$row['shopville'] , 508 , 440 , 175, 55 , 'left', ""); 		# Titre
						}
						pdf_rect($pdf, 250, 470, 400, 70);			#
						pdf_stroke($pdf);
						
						pdf_setcolor($pdf, "fill", "gray", 0.8, 0 ,0 ,0);
						pdf_rect($pdf, 0, 470, 250, 70);
						pdf_fill_stroke($pdf);
						pdf_setcolor($pdf, "fill", "gray", 0, 0 ,0 ,0);
						
					#															#
					#### Entete de Page  ########################################


					#### Corps de Page  #########################################
					# Ligne titre 1 (2)
					$tab = 435;
					$tabt = 438;
					$ht = 20;
					$hl = 22;

						pdf_rect($pdf, 100 , $tab , 35, $hl);
						if ($genre == 'Rack assistance') {
							pdf_rect($pdf, 135 , $tab , 340, $hl);
						} else {
							pdf_rect($pdf, 135 , $tab , 120, $hl);
							pdf_rect($pdf, 255 , $tab , 130, $hl);
							pdf_rect($pdf, 385 , $tab , 90, $hl);
						}
						pdf_rect($pdf, 475 , $tab , 45, $hl);
						pdf_rect($pdf, 520 , $tab , 45, $hl);
						pdf_rect($pdf, 565 , $tab , 45, $hl);
						pdf_rect($pdf, 610 , $tab , 45, $hl);
						pdf_rect($pdf, 655 , $tab , 45, $hl);
						pdf_rect($pdf, 700 , $tab , 40, $hl);
						pdf_rect($pdf, 740 , $tab , 40, $hl);
						pdf_stroke($pdf);

						pdf_setfont($pdf, $TimesBold, 10);
						pdf_set_value ($pdf, "leading", 13);

						pdf_show_boxed($pdf, $phrase[2] , 0 , $tabt , 100, $ht , 'center', "");
						pdf_show_boxed($pdf, "Id" , 100 , $tabt , 35, $ht , 'center', "");
						if ($genre == 'Rack assistance') {
							pdf_show_boxed($pdf, "notes" , 135 , $tabt , 340, $ht , 'center', "");
						} else {
							pdf_show_boxed($pdf, $phrase[50] , 135 , $tabt , 120, $ht , 'center', "");#client
							pdf_show_boxed($pdf, $phrase[51] , 255 , $tabt , 130, $ht , 'center', "");#lieu
							pdf_show_boxed($pdf, $phrase[52] , 385 , $tabt , 90, $ht , 'center', "");#Produit
						}
						pdf_show_boxed($pdf, $phrase[53] , 475 , $tabt , 45, $ht , 'center', "");#am in
						pdf_show_boxed($pdf, $phrase[54] , 520 , $tabt , 45, $ht , 'center', "");#am out
						pdf_show_boxed($pdf, $phrase[55] , 565 , $tabt , 45, $ht , 'center', "");#pm in
						pdf_show_boxed($pdf, $phrase[56] , 610 , $tabt , 45, $ht , 'center', "");#pm out
						pdf_show_boxed($pdf, "Tot" , 655 , $tabt , 45, $ht , 'center', "");
						pdf_show_boxed($pdf, "Km" , 700 , $tabt , 40, $ht , 'center', "");
						pdf_show_boxed($pdf, $phrase[58] , 740 , $tabt , 40, $ht , 'center', "");#tot
			#770

					if ($genre == 'Rack assistance') {
						$ht = 40;
						$hl = 42;
					}
					$tab = $tab - $hl;
					$tabt = $tabt - $hl;

					}
			### lignes #####
			#
		if 	($titre == 'go') {
			$date = weekdate($row['weekm'], $row['yearm']);

			$jour = $date['lun'];
			$jour = implode('',explode("-",$jour));
			$titre = 'stop';
		}

		$datemtemp = implode('',explode("-",$row['datem']));

		### Store check - ajout ligne vide si une ligne mission avec ID
		if (($datetemp != $datemtemp) and ($tour != 1) and ($lignevide == 'oui') and ($genre != 'Rack assistance'))
		{
			pdf_rect($pdf, 100 , $tab , 35, $hl);
			pdf_rect($pdf, 135 , $tab , 120, $hl);
			pdf_rect($pdf, 255 , $tab , 130, $hl);
			pdf_rect($pdf, 385 , $tab , 90, $hl);
			pdf_rect($pdf, 475 , $tab , 45, $hl);
			pdf_rect($pdf, 520 , $tab , 45, $hl);
			pdf_rect($pdf, 565 , $tab , 45, $hl);
			pdf_rect($pdf, 655 , $tab , 45, $hl);
			pdf_rect($pdf, 700 , $tab , 40, $hl);
			pdf_rect($pdf, 740 , $tab , 40, $hl);
			pdf_stroke($pdf);

			$tab = $tab - $hl;
			$tabt = $tabt - $hl;
		}
		#/## Store check - ajout ligne vide si une ligne mission avec ID

		### ajout jour vide
		while ($jour < $datemtemp)
		{
			if ($jj > 7) break;
			$jourprint = 'jour'.$jj;
			$jourprintx = 'jourx'.$jj;

			pdf_setfont($pdf, $TimesRoman, 10);
			pdf_set_value ($pdf, "leading", 12);
			pdf_show_boxed($pdf, $$jourprintx , 2 , $tabt , 47, $ht , 'left', "");
			pdf_show_boxed($pdf, fdate($date[$$jourprint]) , 50 , $tabt , 46, $ht , 'right', "");

			# Ligne Contenu
			pdf_rect($pdf, 100 , $tab , 35, $hl);
			if ($genre == 'Rack assistance') {
				pdf_rect($pdf, 135 , $tab , 340, $hl);
			} else {
				pdf_rect($pdf, 135 , $tab , 120, $hl);
				pdf_rect($pdf, 255 , $tab , 130, $hl);
				pdf_rect($pdf, 385 , $tab , 90, $hl);
			}
			pdf_rect($pdf, 475 , $tab , 45, $hl);
			pdf_rect($pdf, 520 , $tab , 45, $hl);
			pdf_rect($pdf, 565 , $tab , 45, $hl);
			pdf_rect($pdf, 610 , $tab , 45, $hl);
			pdf_rect($pdf, 655 , $tab , 45, $hl);
			pdf_rect($pdf, 700 , $tab , 40, $hl);
			pdf_rect($pdf, 740 , $tab , 40, $hl);
			pdf_stroke($pdf);

			pdf_setfont($pdf, $TimesRoman, 10);
			pdf_set_value ($pdf, "leading", 12);
			if ($genre == 'Rack assistance') {
				pdf_show_boxed($pdf, $phrase[4] , 140 , $tabt , 280, $ht , 'left', "");
			}

			$tab = $tab - $hl;
			$tabt = $tabt - $hl;

			$jj++;
			$jourpourjour = 'jour'.$jj;
			$jour = implode('',explode("-",$date[$$jourpourjour]));

			$tour++;
			$lignevide = 'non';
		}
		#/### ajout jour vide

		$jourprint = 'jour'.$jj;
		$jourprintx = 'jourx'.$jj;

		if (($datetemp != $datemtemp) or (($tour == 1) and ($datetemp == $datemtemp)))
		{
			pdf_setfont($pdf, $TimesRoman, 10);
			pdf_set_value ($pdf, "leading", 12);
			pdf_show_boxed($pdf, $$jourprintx , 2 , $tabt , 47, $ht , 'left', "");
			pdf_show_boxed($pdf, fdate($row['datem']) , 50 , $tabt , 46, $ht , 'right', "");
			$jj++;
			$jourpourjour = 'jour'.$jj;

			$jour = implode('',explode("-",$date[$$jourpourjour]));
			$lignevide = 'oui';
		}

					# Ligne Contenu
					pdf_rect($pdf, 100 , $tab , 35, $hl);
					if ($genre == 'Rack assistance') {
						pdf_rect($pdf, 135 , $tab , 340, $hl);
					} else {
						pdf_rect($pdf, 135 , $tab , 120, $hl);
						pdf_rect($pdf, 255 , $tab , 130, $hl);
						pdf_rect($pdf, 385 , $tab , 90, $hl);
					}
					pdf_rect($pdf, 475 , $tab , 45, $hl);
					pdf_rect($pdf, 520 , $tab , 45, $hl);
					pdf_rect($pdf, 565 , $tab , 45, $hl);
					pdf_rect($pdf, 610 , $tab , 45, $hl);
					pdf_rect($pdf, 655 , $tab , 45, $hl);
					pdf_rect($pdf, 700 , $tab , 40, $hl);
					pdf_rect($pdf, 740 , $tab , 40, $hl);
					pdf_stroke($pdf);

					pdf_setfont($pdf, $TimesRoman, 10);
					pdf_set_value ($pdf, "leading", 12);
						pdf_show_boxed($pdf, $row['idmerch'] , 100 , $tabt , 35, $ht , 'center', "");
						if ($genre == 'Rack assistance') {
							pdf_show_boxed($pdf, $phrase[4] , 140 , $tabt , 250, $ht , 'left', "");
						} else {
							pdf_show_boxed($pdf, $row['clsociete'] , 140 , $tabt , 120, $ht , 'left', "");
							pdf_show_boxed($pdf, $row['societe']." - ".$row['shopville'] , 259 , $tabt , 130, $ht , 'left', "");

							pdf_setfont($pdf, $TimesRoman, 9);
							pdf_set_value ($pdf, "leading", 10);
							pdf_show_boxed($pdf, $row['produit'] , 390 , $tabt , 90, $ht , 'left', "");
						}


						$tab = $tab - $hl;
						$tabt = $tabt - $hl;
						$turntot++;


			#
			### lignes #####

			if (($tour == $jump) or ($turntot == $dernier)) {

						### Store check - ajout ligne vide si une ligne mission avec ID
						if (($lignevide == 'oui') and ($genre != 'Rack assistance')) {
							pdf_rect($pdf, 100 , $tab , 35, $hl);
							pdf_rect($pdf, 135 , $tab , 120, $hl);
							pdf_rect($pdf, 255 , $tab , 130, $hl);
							pdf_rect($pdf, 385 , $tab , 90, $hl);
							pdf_rect($pdf, 475 , $tab , 45, $hl);
							pdf_rect($pdf, 520 , $tab , 45, $hl);
							pdf_rect($pdf, 565 , $tab , 45, $hl);
							pdf_rect($pdf, 610 , $tab , 45, $hl);
							pdf_rect($pdf, 655 , $tab , 45, $hl);
							pdf_rect($pdf, 700 , $tab , 40, $hl);
							pdf_rect($pdf, 740 , $tab , 40, $hl);
							pdf_stroke($pdf);

							$tab = $tab - $hl;
							$tabt = $tabt - $hl;
						}
						#/## Store check - ajout ligne vide si une ligne mission avec ID

					if ($turntot == $dernier) {
						### ajout jour vide
							while (($jour > $datemtemp) and ($jj < 8))
							{
								$jourprint = 'jour'.$jj;
								$jourprintx = 'jourx'.$jj;

								pdf_setfont($pdf, $TimesRoman, 10);
								pdf_set_value ($pdf, "leading", 12);
								pdf_show_boxed($pdf, $$jourprintx , 2 , $tabt , 47, $ht , 'left', "");
								pdf_show_boxed($pdf, fdate($date[$$jourprint]) , 50 , $tabt , 46, $ht , 'right', "");

								# Ligne Contenu
								pdf_rect($pdf, 100 , $tab , 35, $hl);
								if ($genre == 'Rack assistance') {
									pdf_rect($pdf, 135 , $tab , 340, $hl);
								} else {
									pdf_rect($pdf, 135 , $tab , 120, $hl);
									pdf_rect($pdf, 255 , $tab , 130, $hl);
									pdf_rect($pdf, 385 , $tab , 90, $hl);
								}
								pdf_rect($pdf, 475 , $tab , 45, $hl);
								pdf_rect($pdf, 520 , $tab , 45, $hl);
								pdf_rect($pdf, 565 , $tab , 45, $hl);
								pdf_rect($pdf, 610 , $tab , 45, $hl);
								pdf_rect($pdf, 655 , $tab , 45, $hl);
								pdf_rect($pdf, 700 , $tab , 40, $hl);
								pdf_rect($pdf, 740 , $tab , 40, $hl);
								pdf_stroke($pdf);

								pdf_setfont($pdf, $TimesRoman, 10);
								pdf_set_value ($pdf, "leading", 12);

								if ($genre == 'Rack assistance') {
									pdf_show_boxed($pdf, $phrase[4] , 140 , $tabt , 250, $ht , 'left', "");#normale et 2 phrases
								}

								$tab = $tab - $hl;
								$tabt = $tabt - $hl;

								$jj++;
								$jourpourjour = 'jour'.$jj;
								$jour = implode('',explode("-",$date[$$jourpourjour]));
								$lignevide = 'non';
							}
						#/### ajout jour vide

		##total semaine

							pdf_rect($pdf, 655 , $tab , 45, $hl);
							pdf_rect($pdf, 700 , $tab , 40, $hl);
							pdf_rect($pdf, 740 , $tab , 40, $hl);
							pdf_stroke($pdf);
							pdf_setfont($pdf, $TimesRoman, 12);pdf_set_value ($pdf, "leading", 12);
							$tabt = $tabt - 5;
							pdf_show_boxed($pdf, $phrase[5] , 450 , $tabt , 190, $ht , 'right', "");#total semaine
							pdf_setfont($pdf, $TimesBold, 12);pdf_set_value ($pdf, "leading", 12);
							$tabt = $tabt - 5;
							pdf_show_boxed($pdf, $phrase[6] , 50 , $tabt , 200, $ht , 'center', "");#signature

					}

				#### Pied de Page    ########################################
				#															#
					#date
					pdf_setfont($pdf, $TimesRoman, 9);
					pdf_show_boxed($pdf, date("d/m/Y")." - ".date("H:i:s") ,0 ,30 , 200, 15, 'left', ""); #texte du commentaire

					# Ligne de bas de page
					pdf_moveto($pdf, 0, 30);
					pdf_lineto($pdf, $HauteurUtile, 30);
					pdf_stroke($pdf); # Ligne de bas de page

					
					if($row['lbureau'] !="FR")
					{
						pdf_setfont($pdf, $HelveticaBold, 12);
						pdf_set_value ($pdf, "leading", 14);
						pdf_show_boxed($pdf, $phrase[7], 8, 470, 234, 70, 'justify', ""); # retour contrat
						pdf_setfont($pdf, $Helvetica, 10);
						pdf_show_boxed($pdf, $phrase[8], 8, 420, 242, 70, 'left', ""); # retour contrat
					}
					else
					{
						pdf_setfont($pdf, $HelveticaBold, 11);
						pdf_set_value ($pdf, "leading", 14);
						pdf_show_boxed($pdf, $phrase[7], 8, 468, 234, 70, 'left', ""); # retour contrat
						pdf_setfont($pdf, $Helvetica, 10);
						pdf_show_boxed($pdf, $phrase[8], 65, 423, 242, 70, 'left', ""); # retour contrat
					}
					# Coordonnées Exception
					pdf_setfont($pdf, $TimesRoman, 10);

					pdf_show_boxed($pdf, "Exception - Exception scrl\r\rJachtlaan 195 Av. de la Chasse\rBrussel - 1040 - Bruxelles" ,0 ,-10 , $HauteurUtile / 3, 40, 'center', ""); #texte du commentaire
					pdf_show_boxed($pdf, "\rBTW BE 430 597 846 TVA\rHCB 489 589 RCB" , $HauteurUtile / 3,-10 , $HauteurUtile / 3,40, 'center', ""); #texte du commentaire
					pdf_show_boxed($pdf, "www.exception2.be\r\rTel : 02 732.74.40\rFax : 02 732.79.38" , $HauteurUtile * 2 / 3 ,-10 , $HauteurUtile / 3, 40, 'center', ""); #texte du commentaire
				#															#
				#### Pied de Page    ########################################

					pdf_end_page($pdf);

					$tour = 0;
					}
				$tour++;
				$datetemp = $datemtemp ;
			}

			pdf_end_document($pdf, '');
			pdf_delete($pdf);
		}

		return $path['full'];
	} else {
		return "";
	}
}
?>