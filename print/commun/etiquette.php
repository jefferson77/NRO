<?php
if (!empty($jobquid)) {
	$recherche = "SELECT an.idpeople, p.pnom, p.pprenom, p.adresse1, p.num1, p.bte1, p.cp1, p.ville1, p.pays1 FROM animation an LEFT JOIN people p ON an.idpeople = p.idpeople
	".$jobquid."
	GROUP BY an.idpeople ORDER BY p.pnom ";
}

if (!empty($merchjobquid)) {
	switch ($etiqmode) {
	## Etiquettes Merch People
		case"people":
			$recherche = "SELECT me.idpeople, p.pnom, p.pprenom, p.adresse1, p.num1, p.bte1, p.cp1, p.ville1, p.pays1 FROM merch me LEFT JOIN people p ON me.idpeople = p.idpeople
			".$merchjobquid."
			GROUP BY me.idpeople ORDER BY p.pnom ";
		break;	
	## Etiquettes Merch shop
		case"shop":
			$recherche = "SELECT me.idshop, s.snom,  s.sprenom,  s.societe, s.adresse, s.cp, s.ville, s.pays FROM merch me LEFT JOIN shop s ON me.idshop = s.idshop
			".$merchjobquid."
			GROUP BY me.idshop ORDER BY s.societe ";
		break;	
	}
}

$detail = new db();
$detail->inline("SET NAMES latin1");
$detail->inline($recherche);

?>
<table width="95%"><tr>
<?php
# Path PDF
$pathpdf = 'document/temp/anim/etiquette/';
$nompdf = 'etiq-'.date("Ymd").'.pdf';

$pdf = pdf_new();
pdf_open_file($pdf, Conf::read('Env.root').$pathpdf.$nompdf); # définit l'emplacement de la sauvegarde

### Declaration des fontes
$Helvetica = PDF_load_font($pdf, "Helvetica", "host", "");
$HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");

# Infos pour le document
pdf_set_info($pdf, "Author", "Neuro");
pdf_set_info($pdf, "Title", "Etiquettes");
pdf_set_info($pdf, "Creator", "NEURO");
pdf_set_info($pdf, "Subject", "Etiquettes");

######## Variables de taille  ###############
$LargeurPage = 595; # Largeur A4
$HauteurPage = 842; # Hauteur A4

$np = 1; # Numéro de la première page

# Pages de détail ###########
pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
PDF_create_bookmark($pdf, "Page ".$np, "");

$cols = 3;
$ligns = 8;
## Coordonnées de départ
$spx = 0;
$spy = 0;
## longeurs
$lx = $LargeurPage / $cols ;
$ly = $HauteurPage / $ligns ;

$et = 0;

### multi etiquettes
if ($_POST['netiq1'] > 0) $_POST['netiq'] = $_POST['netiq1'];
if ($_POST['netiq2'] > 0) $_POST['netiq'] = $_POST['netiq2'];

if ($_POST['netiq'] < 1) {$_POST['netiq'] = 1;}

while ($infos = mysql_fetch_array($detail->result)) { 
	for ($i = 1; $i <= $_POST['netiq']; $i++) { ## pour plusieurs etiquettes du meme nom
		switch ($etiqmode) {
		## Etiquettes Merch People
			case"people":
				$adress = $infos['adresse1']." ".$infos['num1']."
".$infos['cp1']." - ".$infos['ville1']."
".$infos['pays1'];
	
				$nom = $infos['pnom']." ".$infos['pprenom'];
			break;
			case"shop":
				if (strlen($infos['snom'].$infos['sprenom']) > 2 ) { $attn = "Attn : ".$infos['snom']." ".$infos['sprenom']."
"; } else {$attn = "";}
				$adress = $attn.$infos['adresse']."
".$infos['cp']." - ".$infos['ville']."
".$infos['pays'];
	
				$nom = $infos['societe'];
			break;
		}
	
		$c = fmod($et, $cols);
		$l = $ligns - floor($et / $cols) - 1;
		
		$x = floor($c * $lx);
		$y = floor($l * $ly);
	
		## cadre test
		#pdf_rect($pdf, $x, $y, $lx, $ly);
		#pdf_stroke($pdf);
		
		pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 14);
		pdf_show_boxed($pdf, $adress , $x + 20, $y + 10, $lx - 20, $ly - 40, 'left', ""); # Titre Données Animatrice
		
		pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, "leading", 14);
		pdf_show_boxed($pdf, $nom , $x + 20, $y + $ly - 30, $lx - 20, 20, 'left', ""); # Titre Données Animatrice

		### Saut de page
		if (($et + 1) >= ($cols * $ligns)) {
		
			pdf_end_page($pdf);
			$np++;
			
			$et = 0;
			
			pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
			PDF_create_bookmark($pdf, "Page ".$np, "");
			
			pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le rep?re au point bas-gauche
		
		} else {
			$et++;
		}
	}
}

pdf_end_page($pdf);
$np++;

pdf_end_document($pdf, '');
pdf_delete($pdf); # Efface le fichier en mémoire
# Lien vers le PDF
?>
<td align="left">
<a href="<?php echo NIVO.$pathpdf.$nompdf ;?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"> Etiquettes</a><br>
</td></tr></table>
