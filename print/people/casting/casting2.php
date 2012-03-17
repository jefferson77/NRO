<?php
### Declaration des fontes
$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");
$TimesItalic = PDF_load_font($pdf, "Times-Italic", "host", "");
$Helvetica = PDF_load_font($pdf, "Helvetica", "host", "");
$HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");

################### Get infos du people ########################
if (!empty($quid)) {$quid = 'WHERE idpeople = '.$quid;}
$recherche='
	SELECT *
	FROM people
	'.$quid.'
	 ORDER BY pnom, pprenom
';

$people = new db();
$people->inline("SET NAMES latin1");
$people->inline($recherche);

$row = mysql_fetch_array($people->result);

###################/ Get infos du people ########################



################### Phrasebook ########################
			include 'frnl.php';
			setlocale(LC_TIME, 'nl_NL');
###################/ Phrasebook ########################

	pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
	PDF_create_bookmark($pdf, $phrase[1]." ".$row['idanimation'], "");

	pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le rep?re au point bas-gauche

	#logo
	$logobig = PDF_load_image($pdf, "png", $_SERVER["DOCUMENT_ROOT"].'/print/illus/logoPrint.png', "");
	pdf_place_image($pdf, $logobig, 0, 720, 0.25);
	
	
	#### Entete de Page  ########################################
	#															#
		# illu
		$idpeople = $row['idpeople'];
		## illus cadrées
		$photofile = GetPhotoCropPath($idpeople);
		$photofile2 = GetPhotoCropPath($idpeople, '-b');

		if (file_exists($photofile)) {
			$logobig = PDF_load_image($pdf, "jpeg", $photofile, "");
			pdf_place_image($pdf, $logobig, 46, 580, 0.82);
		} else {
			
		## illus non cadrées
			$photofile = GetPhotoPath($idpeople);
			if (file_exists($photofile)) {
				$dims = getimagesize($photofile);
				$logobig = PDF_load_image($pdf, "jpeg", $photofile, "");
				pdf_place_image($pdf, $logobig, 46, 580, (164 / $dims[1]));
			}
		}
		if (file_exists($photofile2)) {
			$logobig = PDF_load_image($pdf, "jpeg", $photofile2, "");
			pdf_place_image($pdf, $logobig, 330, 580, 0.82);
		} else {
			$photofile2 = GetPhotoPath($idpeople, '-b');
			if (file_exists($photofile2)) {
				$dims = getimagesize($photofile2);
				$logobig = PDF_load_image($pdf, "jpeg", $photofile2, "");
				pdf_place_image($pdf, $logobig, 330, 580, (164 / $dims[1]));
			}
		}


	#															#
	#### Entete de Page  ########################################

	# Cadres personne
	pdf_rect($pdf, 0, 520, 270, 30);
	pdf_rect($pdf, 270, 520, 264, 30);

	pdf_rect($pdf, 0, 490, 270, 30);
	pdf_rect($pdf, 270, 490, 264, 30);

	pdf_rect($pdf, 0, 460, 270, 30);
	pdf_rect($pdf, 270, 460, 264, 30);

	pdf_stroke($pdf);
	#/ Cadres personne

	# Textes
	pdf_setfont($pdf, $HelveticaBold, 20); pdf_set_value ($pdf, "leading", 18);
	pdf_show_boxed($pdf, $phrase[2], 335 , 750 , 200, 25 , 'right', ""); # Titre


	#Nom en gras
	pdf_setfont($pdf, $Helvetica, 25); pdf_set_value ($pdf, "leading", 18);
	pdf_show_boxed($pdf, $row['pprenom'].' '.$row['pnom'], 0 , 700 , 535, 50 , 'center', ""); # Titre

	# Textes
	pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 12);
	#pdf_show_boxed($pdf, $phrase[3], 0, 560, 300, 15, 'left', ""); # fiche signaletique

	# Textes
	pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[4], 5, 520, 250, 29, 'left', ""); # PreNom/Voor Naam en gras
	
	pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $row['pprenom'], 135, 520, 250, 29, 'left', ""); #prenom en normal
	
	pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, "\n".$phrase[5], 5, 520, 250, 29, 'left', ""); # Nom/Naam en gras
	
	pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, "\n".$row['pnom'], 135, 520, 250, 29, 'left', ""); #nom en normal
	
	
	# Textes
	if ($row['ccheveux'] == 'brun') {$ccheveuxnl = 'bruin';}
	if ($row['ccheveux'] == 'noir') {$ccheveuxnl = 'zwart';}
	if ($row['ccheveux'] == 'blond') {$ccheveuxnl = 'blond';}
	if ($row['ccheveux'] == 'chatain') {$ccheveuxnl = 'licht bruin';}
	if ($row['ccheveux'] == 'roux') {$ccheveuxnl = 'ros';}

	pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[6], 275, 520, 250, 29, 'left', ""); #champ cheveux
	
	pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $row['ccheveux'], 405, 520, 100, 29, 'left', ""); # cheveux
	
	pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, "\n".$phrase[7], 275, 520, 250, 29, 'left', ""); #champ cheveux nl
	
	pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf,"\n".$ccheveuxnl, 405, 520, 100, 29, 'left', ""); # cheveux nl

	# Textes
	pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[8].' / '.$phrase[9], 5, 490, 250, 29, 'left', ""); # naissance
	
	pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf,"\n".fdate($row['ndate']), 5, 490, 250, 29, 'left', ""); # naissance

	# Textes VESTE
	pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[10].' / '.$phrase[11], 275, 490, 250, 29, 'left', ""); # taille veste
	pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $row['tveste'], 435, 490, 250, 29, 'left', ""); # taille veste
	
	
	#Texte JUPE
	pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[40].' / '.$phrase[41], 275, 490, 250, 17, 'left', ""); # taille jupe
	pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, "\n".$row['tjupe'], 435, 490, 250, 29, 'left', ""); # taille jupe

	# Textes
	pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[12], 5, 460, 250, 29, 'left', ""); # taille
	pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf,$row['taille'], 125, 460, 250, 29, 'left', ""); # taille
	

	# Textes
	if ($row['voiture'] == 'non') {$voiturenl = ' / nee'; }
	if ($row['voiture'] == 'oui') {$voiturenl = ' / ja'; }
	pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[13], 275, 460, 250, 29, 'left', ""); # voiture
	pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf,$row['voiture'].$voiturenl, 375, 460, 250, 29, 'left', ""); # voiture
	$voiturenl = '';

	# Cadres langue 430 -30-15-15-15-15 (-30-30 out) = 340
	pdf_rect($pdf, 0, 340, 534, 90);

for($i = 0; $i <= 4 ; $i++)
{
	$haut = 400 - ($i*30);
	pdf_rect($pdf, 0, $haut, 108, 30);
	pdf_rect($pdf, 109, $haut, 85, 30);
	pdf_rect($pdf, 194, $haut, 85, 30);
	pdf_rect($pdf, 279, $haut, 85, 30);
	pdf_rect($pdf, 364, $haut, 85, 30);
	pdf_rect($pdf, 449, $haut, 85, 30);
}
	

	pdf_stroke($pdf);
	#/ Cadres langue

# Array avec pour chaque ligne tout ce qui change (hauteur nom de la langue, nom de la variable)
# array(array('haut' => '400'))

	# Textes Cadres langue ligne 1
	
	$haut = 400 ;# titre
	pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[14], 0, $haut, 108, 30, 'center', ""); # langue
	pdf_show_boxed($pdf, $phrase[15], 109, $haut, 85, 30, 'center', ""); # faible
	pdf_show_boxed($pdf, $phrase[16], 194, $haut, 85, 30, 'center', ""); # suff
	pdf_show_boxed($pdf, $phrase[17], 279, $haut, 85, 30, 'center', ""); # bien
	pdf_show_boxed($pdf, $phrase[18], 364, $haut, 85, 30, 'center', ""); # t bien
	pdf_show_boxed($pdf, $phrase[19], 449, $haut, 85, 30, 'center', ""); # excellent

$datas = array(
		array('hauteur' => '370', 'nom' => "Francais\nFrans", 'nomchamp' => 'lfr'),
		array('hauteur' => '340', 'nom' => "Néerlandais\nNederlands", 'nomchamp' => 'lnl'),
		array('hauteur' => '310', 'nom' => "Anglais\nEnglish", 'nomchamp' => 'len'),
		array('hauteur' => '280', 'nom' => "Allemand\nDeutsch", 'nomchamp' => 'ldu')
	);

pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, "leading", 12);
foreach($datas as $ligne)
{
	$haut = $ligne['hauteur']; # titre
	
	pdf_show_boxed($pdf, $ligne['nom'], 0, $haut, 108, 30, 'center', ""); # langue
	if ($row[$ligne['nomchamp']] == 0) {$x='X';} else {$x='';}
	pdf_show_boxed($pdf, $x, 109, $haut, 85, 30, 'center', ""); # faible
	if ($row[$ligne['nomchamp']] == 1) {$x='X';} else {$x='';}
	pdf_show_boxed($pdf, $x, 194, $haut, 85, 30, 'center', ""); # suff
	if ($row[$ligne['nomchamp']] == 2) {$x='X';} else {$x='';}
	pdf_show_boxed($pdf, $x, 279, $haut, 85, 30, 'center', ""); # bien
	if ($row[$ligne['nomchamp']] == 3) {$x='X';} else {$x='';}
	pdf_show_boxed($pdf, $x, 364, $haut, 85, 30, 'center', ""); # t bien
	if ($row[$ligne['nomchamp']] == 4) {$x='X';} else {$x='';}
	pdf_show_boxed($pdf, $x, 449, $haut, 85, 30, 'center', ""); # excellent
	
}


	# Textes Remarque
	pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[23], 0, 220, 534, 30, 'left', ""); # remarques
	pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 12);
	pdf_show_boxed($pdf, $phrase[24], 0, 200, 534, 20, 'left', ""); # petits points
	pdf_show_boxed($pdf, $phrase[24], 0, 180, 534, 20, 'left', ""); # petits points

	pdf_show_boxed($pdf, $phrase[24], 0, 160, 534, 20, 'left', ""); # petits points
	pdf_show_boxed($pdf, $phrase[24], 0, 140, 534, 20, 'left', ""); # petits points
	pdf_show_boxed($pdf, $phrase[24], 0, 120, 534, 20, 'left', ""); # petits points
	
	#/ Textes Cadres langue



#### Pied de Page    ########################################
#															#
	# Closes
	pdf_setfont($pdf, $TimesItalic, 8); pdf_set_value ($pdf, "leading", 10);
	pdf_show_boxed($pdf, $phrase[27] , 5 , 135 , 525, 120 , 'left', "");

	# Ligne de bas de page
	pdf_moveto($pdf, 0, 40);
	pdf_lineto($pdf, $LargeurUtile, 40);
	pdf_stroke($pdf); # Ligne de bas de page

	# Coordonn?es Exception
	pdf_setfont($pdf, $TimesRoman, 10);

	pdf_show_boxed($pdf, $phrase[28] ,0 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
	pdf_show_boxed($pdf, $phrase[29] , $LargeurUtile / 3,0 , $LargeurUtile / 3,40, 'center', ""); #texte du commentaire
	pdf_show_boxed($pdf, $phrase[30] , $LargeurUtile * 2 / 3 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire

#															#
#### Pied de Page    ########################################

	pdf_end_page($pdf);
	$np++;

?>
