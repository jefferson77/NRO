<?php
define('NIVO', '../../');

#Vars $nfyear, $nfprint et $tableau définies dans /admin/notefrais/adminfrais.php

$path = "document/notefrais/";
$file = "notefrais-liste-".$nfmonth.$nfyear.".pdf";

## Define tableau
$lineheight = '5';
$fontsize = '7';
$tbinfo = array(
			'people' => array('name'=>'People', 'width'=>'40', 'align'=>'L'),
			'client' => array('name'=>'Client', 'width'=>'40', 'align'=>'L'),
			'facnum' => array('name'=>'Fac', 'width'=>'8', 'align'=>'C'),
			'secteur' => array('name'=>'Sec', 'width'=>'8', 'align'=>'C'),
			'mission' => array('name'=>'Mission', 'width'=>'15', 'align'=>'C'),
			'date' => array('name'=>'Date', 'width'=>'15', 'align'=>'L'),
			'intitule' => array('name'=>'Intitulé', 'width'=>'40', 'align'=>'L'),
			'description' => array('name'=>'Description', 'width'=>'92', 'align'=>'L'),
			'facture' => array('name'=>'Facturé', 'width'=>'10', 'align'=>'C'),
			'paye' => array('name'=>'Payé', 'width'=>'10', 'align'=>'C'),
			);
			
## Génération du PDF ###############################
$pdf= new fpdi('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetFillColor(225);
$pdf->addPage();

$pdf->SetFont('Helvetica','B','18');
$pdf->Cell(0,10,'Listing notes de frais: '.$nfmonth.'/'.$nfyear,0,1);

## Entête tableau
$pdf->SetFont('Helvetica','B',$fontsize);
foreach($tbinfo as $header) {
	$pdf->Cell($header['width'],$lineheight,$header['name'],1,0,$header['align'],1);
}
$pdf->Ln($lineheight);

## Contenu tableau
$pdf->SetFont('Helvetica','',$fontsize);
$line=0;
foreach($tableau as $nfrais) {
	if ($nfrais['mfacnum'] > 0) $nfrais['facnum'] = $nfrais['mfacnum'];
	else $nfrais['facnum'] = $nfrais['jfacnum'];

	
	if($currentpeople !== $nfrais['idpeople']) { 
		$line++; 
		$pdf->SetFillColor((fmod($line,2)==1)?200:255);
		$pdf->Cell($tbinfo['people']['width'],$lineheight,$nfrais['pnom'].' '.$nfrais['pprenom'].' ('.$nfrais['codepeople'].')',1,0,$tbinfo['people']['align'],1);
	} else {
		$pdf->SetFillColor((fmod($line,2)==1)?200:255);
		$pdf->Cell($tbinfo['people']['width'],$lineheight,'',1,0,$tbinfo['people']['align'],1);
	}
	$pdf->Cell($tbinfo['client']['width'],$lineheight,$nfrais['client'],1,0,$tbinfo['client']['align'],1);
	$pdf->Cell($tbinfo['facnum']['width'],$lineheight,$nfrais['facnum'],1,0,$tbinfo['facnum']['align'],1);
	$pdf->Cell($tbinfo['secteur']['width'],$lineheight,$nfrais['secteur'],1,0,$tbinfo['secteur']['align'],1);
	$pdf->Cell($tbinfo['mission']['width'],$lineheight,$nfrais['idmission'],1,0,$tbinfo['mission']['align'],1);
	$pdf->Cell($tbinfo['date']['width'],$lineheight,fdate($nfrais['datemission']),1,0,$tbinfo['date']['align'],1);
	$pdf->Cell($tbinfo['intitule']['width'],$lineheight,$nfrais['intitule'],1,0,$tbinfo['intitule']['align'],1);
	$pdf->Cell($tbinfo['description']['width'],$lineheight,$nfrais['description'],1,0,$tbinfo['description']['align'],1);
	$pdf->Cell($tbinfo['facture']['width'],$lineheight,$nfrais['montantfacture'],1,0,$tbinfo['facture']['align'],1);
	$pdf->Cell($tbinfo['paye']['width'],$lineheight,$nfrais['montantpaye'],1,1,$tbinfo['paye']['align'],1);

	$currentpeople = $nfrais['idpeople'];
	$nftotalfac += $nfrais['montantfacture'];
	$nftotalpaye += $nfrais['montantpaye'];
}

$pdf->SetFont('Helvetica','B',$fontsize);
$spacer = $tbinfo['people']['width']+$tbinfo['client']['width']+$tbinfo['mission']['width']+$tbinfo['secteur']['width']+$tbinfo['date']['width']+$tbinfo['intitule']['width']+$tbinfo['description']['width'];
$pdf->Cell($spacer,$lineheight,'Total',1,0,R,1);
$pdf->Cell($tbinfo['facture']['width'],$lineheight,$nftotalfac,1,0,$tbinfo['facture']['align'],1);
$pdf->Cell($tbinfo['paye']['width'],$lineheight,$nftotalpaye,1,1,$tbinfo['paye']['align'],1);

$pdf->Output(Conf::read('Env.root').$path.$file, 'F');

echo '
<div align="center">
<br><br>
<img src="'.STATIK.'illus/minipdf.gif" alt="print" width="16" height="16" border="0">
<A href="'.NIVO.$path.$file.'" target="_blank">Imprimer la note de frais</A>
</div>
';

?>
