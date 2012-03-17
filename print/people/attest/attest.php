<?php
# Path PDF
$pathpdf = 'document/temp/people/attest/';
$nompdf = 'attest-'.$_POST['idpeople'].'.pdf';

################### Get infos du people ########################
$infpeople = new db();
$infpeople->inline("SET NAMES latin1");
$row = $infpeople->getRow("SELECT
			idpeople, pprenom, pnom, adresse1, num1, bte1, cp1, ville1, banque, codepeople, nville, lbureau, nrnational
			FROM people
			WHERE idpeople = ".$_POST['idpeople']);

################### Fin infos du people ########################

$pdf = pdf_new();
pdf_open_file($pdf, Conf::read('Env.root').$pathpdf.$nompdf); # définit l'emplacement de la sauvegarde

### Declaration des fontes
$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");
$TimesBold = PDF_load_font($pdf, "Times-Bold", "host", "");
$TimesItalic = PDF_load_font($pdf, "Times-Italic", "host", "");
$Helvetica = PDF_load_font($pdf, "Helvetica", "host", "");
$HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");
$Courier = PDF_load_font($pdf, "Courier", "host", "");

# Infos pour le document
pdf_set_info($pdf, "Author", "Françoise Lannoo");
pdf_set_info($pdf, "Title", "Attestations de travail");
pdf_set_info($pdf, "Creator", "NEURO");
pdf_set_info($pdf, "Subject", "Attestation de travail");

######## Variables de taille  ###############
$LargeurPage = 595; # Largeur A4
$HauteurPage = 842; # Hauteur A4
$MargeLeft = 0;
$MargeRight = 30;
$MargeTop = 30;
$MargeBottom = 0;

$np = 1; # Numéro de la première page
######## Variables de taille  ###############
$LargeurUtile = $LargeurPage - $MargeRight - $MargeLeft;
$HauteurUtile = $HauteurPage - $MargeTop - $MargeBottom;

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

############### PDF #####################
pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
PDF_create_bookmark($pdf, $datemission.' 1', "");
pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche

#### Entete de Page  ########################################
#															#
	# illu
	$logobig = PDF_load_image($pdf, "png", $_SERVER["DOCUMENT_ROOT"]."/print/illus/logoPrint.png", "");
	pdf_place_image($pdf, $logobig, 40, 720, 0.25);
	
#															#
#### Entete de Page  ########################################

#### Signature Planner  #########################################
#																#
	# illu
	$signature = $_SERVER["DOCUMENT_ROOT"]."/print/illus/signature/20.jpg";	
	$signature = PDF_load_image($pdf, "jpeg", $signature, "");
	pdf_place_image($pdf, $signature, 400, 70, 0.5);
	#															#
	pdf_setfont($pdf, $TimesRoman, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, "Françoise Lannoo", 350, 75, 180, 12, 'center', ""); # Titre

	# Cachet illus 
	$cachet = $_SERVER["DOCUMENT_ROOT"]."/print/illus/cachet.jpg";	
	$cachet = PDF_load_image($pdf, "jpeg", $cachet, "");
	pdf_place_image($pdf, $cachet, 220, 70, 0.18);

#																#
#/ ### Signature Planner  #######################################

## People
pdf_setfont($pdf, $TimesBold, 14); pdf_set_value ($pdf, 'leading', 14);
pdf_show_boxed($pdf, $row['pprenom'].' '.$row['pnom'] , 351, 751, 179, 20 , 'left', "");

$adresse = $row['adresse1'].', '.$row['num1'];
if ($row['bte1'] > 0) $adresse .= $phrase[32].$row['bte1'];

pdf_show_boxed($pdf, $adresse , 350, 731, 250, 20 , 'left', "");
pdf_show_boxed($pdf, $row['cp1'].' - '.$row['ville1'] , 348, 711, 250, 20 , 'left', "");

pdf_setfont($pdf, $TimesRoman, 9); pdf_set_value ($pdf, 'leading', 9);
pdf_show_boxed($pdf, $row['codepeople'] , 506, 783, 34, 12 , 'left', "");

## blabla
pdf_setfont($pdf, $Courier, 11); pdf_set_value ($pdf, 'leading', 11);
pdf_show_boxed($pdf, $row['pprenom'].' '.$row['pnom'] , 335, 625, 231, 13 , 'left', "");
pdf_setfont($pdf, $Helvetica, 11); pdf_set_value ($pdf, 'leading', 11);
pdf_show_boxed($pdf, $phrase[3] , 49, 610, 268, 29 , 'left', "");
pdf_setfont($pdf, $HelveticaBold, 20); pdf_set_value ($pdf, 'leading', 20);
pdf_show_boxed($pdf, $phrase[2] , 53, 640, 198, 29 , 'left', "");


foreach ($dtable as $key => $value) {
	$mn = substr($key, 0, 4).'-'.substr($key, 4, 2);

	$mnth[$mn] += 1;
}

reset($dtable);

$tab = 590;
$lineheight = 10;

foreach ($dtable as $key => $value) {
#### Saut de page #####################

	$tt++;
	$moisencours = ucfirst(strftime("%B %Y", strtotime($value['date']))); # jour semaine
	$fulldate = ucfirst(utf8_decode(strftime ("%a %d/%m/%y", strtotime($value['date'])))); # jour semaine
	$mn = substr($key, 0, 4).'-'.substr($key, 4, 2);

	$lestables = $DB->tableliste("/^salaires/", 'grps');

	$nomtables = 'salaires'.substr($key, 2, 2).substr($key, 4, 2);

	#># Heure de repas
	if (($value['heures'] >= Conf::read('Payement.maxheure')) and ($value['date'] < '2010-02-01')) $value['heures'] -= 1;
	
	$infs = $DB->getRow("SELECT `idsalaire`, `modh` FROM grps.$nomtables WHERE `idpeople` = ".$_POST['idpeople']." AND `date` = '".$value['date']."'");

	### totaux
	/*
		TODO : calcul des heures exact ? On ne tient pas compt des 150 et 200 dans ce calcul
	*/
	$totheures = $value['heures'] + $infs['modh'];

	##### Totaux mensuels de frais ########################################################################## 2005-06-16 ############################
	#																																				#
		## Heures
		$totx[$moisencours]['toth'] += $totheures;
		
		## Salaire
		$totx[$moisencours]['heure'] += (($totheures - $infs['modh150'] - $infs['modh200']) + ($infs['modh150'] * 1.5) + ($infs['modh200'] * 2)) * salaire($row['idpeople'], $value['date']);

		## KM
		$totx[$moisencours]['km'] += $value['frais437'] + $infs['mod437'];
	#																																				#
	#################################################################################################################################################

	if ($moisencours != $lastmn) {
	
		if ($tab < 100) {
		
		## Totaux Salaires et KM
		foreach ($totx as $val) {
			pdf_setfont($pdf, $Helvetica, 11); pdf_set_value ($pdf, 'leading', 12);
			pdf_show_boxed($pdf, $phrase[34].$val['toth'].$phrase[1] , 150, $val['tab'] + 2, 140, 12 , 'right', "");
			# pdf_show_boxed($pdf, $phrase[35].fnbr($val['heure']).$phrase[37] , 300, $val['tab'] + 2, 140, 12 , 'right', "");
			pdf_show_boxed($pdf, $phrase[36].fnbr($val['km']).$phrase[37] , 400, $val['tab'] + 2, 140, 12 , 'right', "");
		}

		unset($totx);

		#### Pied de Page    ########################################
		#															#
			# Ligne de bas de page
			pdf_moveto($pdf, 30, 70);
			pdf_lineto($pdf, $LargeurUtile, 70);
			pdf_stroke($pdf); # Ligne de bas de page
			
			# Coordonnées Exception
			pdf_setfont($pdf, $TimesRoman, 10);
	
			pdf_show_boxed($pdf, $phrase[28] ,30 ,30 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
			pdf_show_boxed($pdf, $phrase[29] , $LargeurUtile / 3 ,30 , $LargeurUtile / 3,40, 'center', ""); #texte du commentaire
			pdf_show_boxed($pdf, $phrase[30] , $LargeurUtile * 2 / 3 ,30 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
		#															#
		#### Pied de Page    ########################################
			
			pdf_end_page($pdf);
		
		############### PDF #####################
			pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
			PDF_create_bookmark($pdf, $datemission.' 1', "");
			pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche
	
			$tab = 640;
		}

		$tab += ($tt - $mid - 2) * $lineheight;

		pdf_moveto($pdf, 48, $tab);
		pdf_lineto($pdf, 539, $tab);
		pdf_stroke($pdf);
	
		pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, 'leading', 12);
		pdf_show_boxed($pdf, $moisencours , 52, $tab + 2, 150, 12 , 'left', "");
		
		$totx[$moisencours]['tab'] = $tab;
		
		$tub = $tab;
		$tab -= 15;
		
		$tt = 1;
		
		$mid = ceil($mnth[$mn] / 3);
		$mad = $mid * 2;
		$rst = fmod($mnth[$mn] , 3);
	}
	
	pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, 'leading', 9);
	if ($tt <= $mid) {
		$lft = 40;
		pdf_show_boxed($pdf, $fulldate , $lft, $tab, 88, 12 , 'right', "");
		pdf_show_boxed($pdf, ': '.fnbr($totheures) , $lft + 95, $tab, 120, 12 , 'left', "");
		pdf_show_boxed($pdf, $phrase[1] , $lft + 120, $tab, 120, 12 , 'left', "");
	} elseif (($tt > $mid) and ($tt <= $mad)) {
		$tob = $tab + $mid * $lineheight;
		$lft = 200;
		pdf_show_boxed($pdf, $fulldate , $lft, $tob, 88, 12 , 'right', "");
		pdf_show_boxed($pdf, ': '.fnbr($totheures) , $lft + 95, $tob, 120, 12 , 'left', "");
		pdf_show_boxed($pdf, $phrase[1] , $lft + 120, $tob, 120, 12 , 'left', "");
	} else {
		$tib = $tab + $mad * $lineheight;
		$lft = 360;
		pdf_show_boxed($pdf, $fulldate , $lft, $tib, 88, 12 , 'right', "");
		pdf_show_boxed($pdf, ': '.fnbr($totheures) , $lft + 95, $tib, 120, 12 , 'left', "");
		pdf_show_boxed($pdf, $phrase[1] , $lft + 120, $tib, 120, 12 , 'left', "");
	}

	# lines

	$tab -= $lineheight;

	$lastmn = $moisencours;
}

foreach ($totx as $val) {
	pdf_setfont($pdf, $Helvetica, 11); pdf_set_value ($pdf, 'leading', 12);
	pdf_show_boxed($pdf, $phrase[34].$val['toth'].$phrase[1] , 150, $val['tab'] + 2, 140, 12 , 'right', "");
	pdf_show_boxed($pdf, $phrase[36].fnbr($val['km']).$phrase[37] , 400, $val['tab'] + 2, 140, 12 , 'right', "");
}
unset($totx);

pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, 'leading', 12);
pdf_show_boxed($pdf, $phrase[4].ucfirst(strftime("%e %B %Y")) , 69, 90, 120, 17 , 'center', "");
pdf_setfont($pdf, $Helvetica, 11); pdf_set_value ($pdf, 'leading', 11);
pdf_show_boxed($pdf, $phrase[6] , 49, 120, 500, 16 , 'left', "");
	
#### Pied de Page    ########################################
#															#
	# Ligne de bas de page
	pdf_moveto($pdf, 30, 70);
	pdf_lineto($pdf, $LargeurUtile, 70);
	pdf_stroke($pdf); # Ligne de bas de page
	
	# Coordonnées Exception
	pdf_setfont($pdf, $TimesRoman, 10);
	pdf_show_boxed($pdf, $phrase[28] ,30 ,30 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
	pdf_show_boxed($pdf, $phrase[29] , $LargeurUtile / 3 ,30 , $LargeurUtile / 3,40, 'center', ""); #texte du commentaire
	pdf_show_boxed($pdf, $phrase[30] , $LargeurUtile * 2 / 3 ,30 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire

#															#
#### Pied de Page    ########################################
	
	pdf_end_page($pdf);

	pdf_end_document($pdf, '');
	pdf_delete($pdf); # Efface le fichier en mémoire
	
	if($web)
	{
		?>
			<div align="center">
				<br><br>
				<img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0">
				<A href="<?php echo NIVO.$pathpdf.$nompdf ;?>" target="_blank"><?php echo $phrase[33]; ?></A>
			</div>
		<?php
	}
	else
	{
		$docsparpeople[$_POST['idpeople']][] = Conf::read('Env.root').$pathpdf.$nompdf;

		echo '<div id="centerzonelarge" align="center">';
		generateSendTable($docsparpeople, 'people', 'temp/PeAt', 'PeAt', "Attestation de travail");
		echo '</div>';
	}
	
?>