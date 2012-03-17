<?php
#####################
# Path PDF
$pathpdf = 'document/temp/anim/prefacture/';
$nompdf = 'prefacture-an-'.$printinfo.'-'.date("Ymd").'.pdf';

$pdf = pdf_new();
pdf_open_file($pdf, Conf::read('Env.root').$pathpdf.$nompdf); # définit l'emplacement de la sauvegarde
# Infos pour le document
pdf_set_info($pdf, "Author", $infos['prenom'].' '.$infos['nomagent']);
pdf_set_info($pdf, "Title", $nompdf);
pdf_set_info($pdf, "Creator", "NEURO");
pdf_set_info($pdf, "Subject", "Anim Prefacture");


################### Début Recherche de toutes les fiches tempfactureanim ########################
$anim1 = new db();
$anim1->inline("SELECT idfac FROM `tempfactureanim`;");

while ($infoa = mysql_fetch_array($anim1->result)) { 

	$fac = new facture($infoa['idfac'], 'PREAN');
	$facan = new facanim('PREFAC', $infoa['idfac']);

# Page d'entete ###########
	include $printpage.'.php';
# Fin Page d'entete #######

}

pdf_end_document($pdf, '');
pdf_delete($pdf); # Efface le fichier en mémoire

?>
<div align="center">
<br>
<img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0">
<A href="<?php echo NIVO.$pathpdf.$nompdf ;?>" target="_blank">Imprimer <?php echo $printpage; ?></A>
<br>
</div>