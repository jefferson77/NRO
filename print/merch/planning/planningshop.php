<?php
#########################################################################################################################################################################
### Page de Planning Shop en MERCH ######################################################################################################################################
#########################################################################################################################################################################
# TODO : !! moteur PDFlib, a remplacer
require_once($_SERVER["DOCUMENT_ROOT"].'/nro/fm.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/classes/merch.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/classes/document.php');

##############################################################################################################################
# renvoie les path 'dossier', 'full', 'history', 'filename'
function path_planningshopmerch($week, $year, $idshop, $genre) {

## declarations
    $fname = $year.'-'.$week;

    $lespath['dossier'] = Conf::read('Env.root').'document/merch/'.cleanalpha($genre).'/planningshop/'.prezero($idshop, 5).'/';
    $lespath['filename'] = $fname.'.pdf';
    $lespath['full'] = $lespath['dossier'].$lespath['filename'];
    $lespath['history'] = Conf::read('Env.root').'history/merch/'.cleanalpha($genre).'/planningshop/'.prezero($idshop, 5).'/';

    if (file_exists($lespath['full'])) $lespath['historyfull'] = $lespath['history'].$fname.'-'.date ("Ymd.Hi", filemtime($lespath['full'])).'.pdf';

## check et creation si besoin
    if(!is_dir($lespath['dossier'])) mkdir($lespath['dossier'], 0777, true); #le 'true' c'est pour le recursif
    if(!is_dir($lespath['history'])) mkdir($lespath['history'], 0777, true); 

    return $lespath;
}

##############################################################################################################################
# sauve un contrat vip d'une mission donnée et retourne le chemin du fichier
function print_planningshopmerch($week, $year, $idshop, $genre)
{
###### GEt infos ######
	global $DB;
    $DB->inline("SET NAMES latin1");

	$infosshop = $DB->getRow("SELECT
		s.slangue, s.societe, s.ville, s.fax,
		s.snom, s.sprenom
		FROM shop s
		WHERE s.idshop = ".$idshop);
		
	$listemission = $DB->getArray("SELECT 
		me.idmerch, me.idagent, me.hin1, me.hout1, me.hin2, me.hout2, me.produit, me.ferie, me.kmpaye, me.kmfacture, me.datem, me.weekm,
		c.societe,
		co.qualite, co.onom, co.oprenom, 
		p.pnom, p.pprenom,
		a.nom AS agnom, a.prenom AS agprenom, a.email AS agemail, a.atel AS agatel
		FROM merch me
			LEFT JOIN client c ON me.idclient = c.idclient 
			LEFT JOIN cofficer co ON me.idcofficer = co.idcofficer 
			LEFT JOIN people p ON me.idpeople = p.idpeople
			LEFT JOIN shop s ON me.idshop = s.idshop
			LEFT JOIN agent a ON me.idagent = a.idagent
		WHERE me.weekm = ".$week."
			AND me.yearm = ".$year."
			AND me.idshop = ".$idshop."
			AND me.genre = '".$genre."'
		ORDER BY s.societe, me.yearm, me.weekm, me.genre");
		
	if (count($listemission) > 0)
	{
		$dernier = count($listemission);
		$path = path_planningshopmerch($week, $year, $idshop, $genre);
		
		###### check file ######
		if (file_exists($path['full'])) rename($path['full'], $path['historyfull']);
	
		$pdf = pdf_new();
		pdf_open_file($pdf, $path['full']); # définit l'emplacement de la sauvegarde

		# Infos pour le document
		pdf_set_info($pdf, "Author", "neuro");
		pdf_set_info($pdf, "Title", "Planning Merch Shop");
		pdf_set_info($pdf, "Creator", "NEURO");
		pdf_set_info($pdf, "Subject", "Planning");

		### Declaration des fontes
		$TimesItalic = pdf_load_font($pdf, "Times-Italic", "host", "");
		$Helvetica = pdf_load_font($pdf, "Helvetica", "host", "");
		$HelveticaBold = pdf_load_font($pdf, "Helvetica-Bold", "host", "");

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
		switch ($infosshop['slangue'])
		{
			case "NL":
				include $_SERVER["DOCUMENT_ROOT"].'/print/merch/planning/nl.php';
				setlocale(LC_TIME, 'nl_NL');
			break;
			case "FR":
				include $_SERVER["DOCUMENT_ROOT"].'/print/merch/planning/fr.php';
				setlocale(LC_TIME, 'fr_FR');
			break;
			default:
				$phrase = array('');
				echo '<br> Langue pas d&eacute;finie pour le shop : '.$infosshop['societe'].' '.$infosshop['ville'];
		}
		
		# jumpers
		$tour = 1;
		$turntot = 1; 
		$reste = $dernier;
		$np = 0;
		
		$dim = array(
			0 => array('l' =>  60, 'a' => 'center', 'ph' => 5),
			1 => array('l' =>  35, 'a' => 'center', 'ph' => 6),
			2 => array('l' =>  35, 'a' => 'center', 'ph' => 7),
			3 => array('l' =>  35, 'a' => 'center', 'ph' => 6),
			4 => array('l' =>  35, 'a' => 'center', 'ph' => 7),
			5 => array('l' =>  35, 'a' => 'center', 'ph' => 8),
			6 => array('l' => 145, 'a' => 'left'  , 'ph' => 9),
			7 => array('l' => 200, 'a' => 'left'  , 'ph' => 10),
			8 => array('l' => 165, 'a' => 'left'  , 'ph' => 11),
			9 => array('l' =>  35, 'a' => 'center', 'ph' => 12)
		);

		foreach ($listemission as $row) {
			if ($tour == 1)
			{
				$np++;
				pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
				pdf_create_bookmark($pdf, $phrase[14].$np, "");

				pdf_rotate($pdf, 90);
				pdf_translate($pdf, $MargeBottom, $MargeRight - $LargeurPage); # Positionne le repère au point bas-gauche

				if ($turntot > 1) {
					$jump = 23;
					$tab = 425;
					$tabt = 426;
				} else {
					$jump = 21;
					$tab = 385;
					$tabt = 386;
					
					pdf_setfont($pdf, $TimesItalic, 14); pdf_set_value ($pdf, "leading", 18);
					pdf_show_boxed($pdf, $phrase[3] , 0 , 420 , 400, 36 , 'left', "");
				}

				$ht = 16;
				$hl = 16;

				$reste -= $jump;

				#### Entete de Page  ########################################
				# illu
				$logobig = pdf_load_image($pdf, "png", $_SERVER["DOCUMENT_ROOT"]."/print/illus/logoPrint.png", "");
				$haut = pdf_get_value($pdf, "imagewidth", $logobig) * 0.1; # Calcul de la hauteur
				pdf_place_image($pdf, $logobig, 0, $LargeurUtile - $haut, 0.25);

				# Titre document
				pdf_setfont($pdf, $HelveticaBold, 30); pdf_set_value ($pdf, "leading", 30);
				pdf_show_boxed($pdf, $phrase[13] , 145 , 500 , 200, 32 , 'left', "");

				## cadre infos
				pdf_setfont($pdf, $HelveticaBold, 20); pdf_set_value ($pdf, "leading", 30);
				pdf_show_boxed($pdf, $phrase[0]."\r".$row['weekm'] , 275 , 485 , 100, 60 , 'center', "");

				pdf_setfont($pdf, $HelveticaBold, 14); pdf_set_value ($pdf, "leading", 14);
				pdf_show_boxed($pdf, $phrase[4]." : " , 350 , 520 , 100, 20 , 'right', ""); 		# Titre
				pdf_show_boxed($pdf, "Attn : " , 350 , 495 , 100, 20 , 'right', ""); 		# Titre
				pdf_show_boxed($pdf, "Fax : " , 350 , 470 , 100, 20 , 'right', ""); 		# Titre

				pdf_setfont($pdf, $Helvetica, 14);
				pdf_show_boxed($pdf, $infosshop['societe'] , 450 , 520 , 525, 20 , 'left', ""); 		# Titre
				pdf_show_boxed($pdf, $infosshop['snom']." ".$infosshop['sprenom'] , 450 , 495 , 525, 20 , 'left', ""); 		# Titre
				pdf_show_boxed($pdf, $infosshop['fax'] , 450 , 470 , 525, 20 , 'left', ""); 		# Titre

				pdf_rect($pdf, 370, 470, $HauteurUtile - 370, 75);			#
				pdf_stroke($pdf);

				# cadres des titres
				pdf_setlinewidth($pdf, 0.5);
				pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);
				pdf_setcolor($pdf, "stroke", "gray", 1, 0, 0, 0);

				$x = 0;
				foreach ($dim as $val) {
					pdf_rect($pdf, $x , $tab , $val['l'], $hl);
					$x += $val['l'];
				}
				pdf_fill_stroke($pdf); 

				pdf_setcolor($pdf, "both", "gray", 1, 0, 0, 0);
				pdf_setfont($pdf, $HelveticaBold, 12);
				pdf_set_value ($pdf, "leading", 14);

				# textes des titres
				$x = 0;
				foreach ($dim as $val) {
					pdf_show_boxed($pdf, $phrase[$val['ph']], $x, $tabt, $val['l'], $ht , 'center', "");
					$x += $val['l'];
				}

				pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);
				
				$tab = $tab - $hl;	
				$tabt = $tabt - $hl;
			}
			
			# Cadre Ligne Contenu
			$x = 0;
			foreach ($dim as $val) {
				pdf_rect($pdf, $x , $tab , $val['l'], $hl);
				$x += $val['l'];
			}
			pdf_stroke($pdf); 
			
			## datas
			$merch = new coremerch($row['idmerch']);
			$htotz += $merch->hprest;
			
			$datas = array(
				0 => fdate($row['datem']),
				1 => ftime($row['hin1']),
				2 => ftime($row['hout1']),
				3 => ftime($row['hin2']),
				4 => ftime($row['hout2']),
				5 => fnbr($merch->hprest),
				6 => $row['societe'],
				7 => $row['pprenom']." ".$row['pnom'], 
				8 => $row['produit'], 
				9 => $row['idmerch']
			);

			pdf_setfont($pdf, $Helvetica, 10);
			pdf_set_value ($pdf, "leading", 12);
			
			# textes datas
			$x = 0;
			foreach ($dim as $key => $val) {
				pdf_show_boxed($pdf, $datas[$key] , $x + 2 , $tabt , $val['l'] - 4, $ht, $val['a'], "");
				$x += $val['l'];
			}

			$tab -= $hl;	
			$tabt -= $hl;

			if (($tour == $jump) or ($turntot == $dernier) or ((($dernier - $turntot) < 4) and ($tab < 120))) {
				## totaux
				if ($turntot == $dernier) {
					pdf_rect($pdf, 200 , $tab , 35, $hl);
					pdf_stroke($pdf); 
					pdf_set_value ($pdf, "leading", 12);
					pdf_show_boxed($pdf, fnbr($htotz) , 200 , $tabt , 35, $ht , 'center', "");
					$htotz = 0;
					$tab -= 50;	
					$tabt -= 50;

					## illu signature
					if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/print/illus/signature/'.$row['idagent'].'.jpg')) {
						echo "<br>fichier signature non trouvé pour : (".$row['idagent'].") ".remaccents(utf8_encode($row['agprenom']));
					} else {
						$signature = $_SERVER["DOCUMENT_ROOT"].'/print/illus/signature/'.$row['idagent'].'.jpg';
						$signature = PDF_load_image($pdf, "jpeg", $signature, "");
						pdf_place_image($pdf, $signature, 660, $tab - 30, 0.36);	
					}

					## bien a vous + resp. job
					pdf_setfont($pdf, $TimesItalic, 14);
					pdf_set_value ($pdf, "leading", 16);
					pdf_show_boxed($pdf, $phrase[1], 0, $tabt, 600, 50, 'left', "");
					pdf_show_boxed($pdf, $row['agprenom']." ".$row['agnom']." \r ".$row['agatel']." \r ".$row['agemail'], 480, $tabt - 30, 200, 60, 'center', "");
				}

			#### Pied de Page    ########################################
				#date
				pdf_setfont($pdf, $Helvetica, 9);
				pdf_show_boxed($pdf, $phrase[50].date("d/m/Y")." ".$phrase[7]." ".date("H:i:s") ,0 ,27 , 200, 15, 'left', "");

				# Ligne de bas de page
				pdf_moveto($pdf, 0, 30);
				pdf_lineto($pdf, $HauteurUtile, 30);
				pdf_stroke($pdf);
				
				# Coordonnées Exception
				pdf_setfont($pdf, $Helvetica, 10);
				pdf_show_boxed($pdf, "Exception - Exception scrl\r\rJachtlaan 195 Av. de la Chasse\rBrussel - 1040 - Bruxelles" ,0 ,-10 , $HauteurUtile / 3, 40, 'center', "");
				pdf_show_boxed($pdf, "\rBTW BE 430 597 846 TVA\rHCB 489 589 RCB" , $HauteurUtile / 3,-10 , $HauteurUtile / 3,40, 'center', "");
				pdf_show_boxed($pdf, "www.exception2.be\r\rTel : 02 732.74.40\rFax : 02 732.79.38" , $HauteurUtile * 2 / 3 ,-10 , $HauteurUtile / 3, 40, 'center', "");
			#### Pied de Page    ########################################

				pdf_end_page($pdf);
				$tour = 0;
			}
			$tour++;
			$turntot++;
		}

		pdf_end_document($pdf, '');
		pdf_delete($pdf); # Efface le fichier en mémoire

		return $path['full'];
	} else return "";		
}
?>