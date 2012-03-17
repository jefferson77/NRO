<?php
### Declaration des fontes
$TimesBold = PDF_load_font($pdf, "Times-Bold", "host", "");

$recherche = "SELECT an.idpeople, p.pnom, p.pprenom, p.adresse1, p.num1, p.bte1, p.cp1, p.ville1, p.pays1 FROM animation an LEFT JOIN people p ON an.idpeople = p.idpeople
	".$jobquid."
	GROUP BY an.idpeople ORDER BY p.pnom ";
		
$detail = new db();
$detail->inline($recherche);

?>
<table width="95%"><tr>
<?php
# Path PDF
$pathpdf = 'document/temp/anim/etiquette/';
$nompdf = 'etiq-people-'.date("Ymd").'.pdf';

$pdf = pdf_new();
pdf_open_file($pdf, Conf::read('Env.root').$pathpdf.$nompdf); # définit l'emplacement de la sauvegarde
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

while ($infos = mysql_fetch_array($detail->result)) { 

	$c = fmod($et, $cols);
	$l = $ligns - floor($et / $cols);
	
	$x = $c * $lx;
	$y = $l * $ly;
	
	## cadre test
	pdf_rect($pdf, $x, $y, $lx, $ly);
	pdf_stroke($pdf);
	
	pdf_setfont($pdf, $TimesBold, 30); pdf_set_value ($pdf, "leading", 30);
	pdf_show_boxed($pdf, $et , $x, $y, $lx, $ly, 'center', ""); # Titre Données Animatrice
	
	
	
	
	
	
	
	### Saut de page
	if ($et >= ($cols * $ligns)) {
	
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

pdf_end_page($pdf);
$np++;




pdf_end_document($pdf, '');
pdf_delete($pdf); # Efface le fichier en mémoire
# Lien vers le PDF
?>
<td align="left">
<a href="<?php echo NIVO.$pathpdf.$nompdf ;?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"> Etiquettes</a><br>
</td></tr></table>
