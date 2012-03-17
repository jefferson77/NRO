<?php
#Vars $nfyear, $nfprint et $tableau définies dans /admin/notefrais/adminfrais.php

$path = "document/notefrais/";
$file = "notefrais-people-".$nfmonth.$nfyear.".pdf";

## Define tableau
$lineheight = '5';
$fontsize = '7';
$tbinfo = array(
			'client' =>			array('name'=>'Client',		'width'=>'40',	'align'=>'L'),
			'facnum' =>			array('name'=>'Fac',		'width'=>'8',	'align'=>'C'),
			'secteur' =>		array('name'=>'Mission',	'width'=>'12',	'align'=>'C'),
			'agent' =>			array('name'=>'Agent',		'width'=>'18',	'align'=>'C'),
			'date' =>			array('name'=>'Date',		'width'=>'10',	'align'=>'C'),
			'intitule' =>		array('name'=>'Intitulé',	'width'=>'40',	'align'=>'L'),
			'description' =>	array('name'=>'Description', 'width'=>'43', 'align'=>'L'),
			'facture' =>		array('name'=>'Facturé',	'width'=>'10',	'align'=>'C'),
			'paye' =>			array('name'=>'Payé',		'width'=>'10',	'align'=>'C'),
			);
			
## Génération du PDF ###############################
$pdf= new fpdi('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetFillColor(225);

## Contenu tableau
$pdf->SetFont('Helvetica','',$fontsize);
$line=0;
foreach($tableau as $nfrais) {
	
	
	if ($nfrais['mfacnum'] > 0) $nfrais['facnum'] = $nfrais['mfacnum'];
	else $nfrais['facnum'] = $nfrais['jfacnum'];


	if($currentpeople !== $nfrais['idpeople']) {
		
		$pdf->SetFont('Helvetica','B',$fontsize);
		$pdf->SetFillColor(200);
		//largeur du tableau
		$spacer = $tbinfo['client']['width']+$tbinfo['facnum']['width']+$tbinfo['secteur']['width']+$tbinfo['agent']['width']+$tbinfo['date']['width']+$tbinfo['intitule']['width']+$tbinfo['description']['width'];
		$pdf->Cell($spacer,$lineheight,'Total',1,0,R,1);
		$pdf->Cell($tbinfo['facture']['width'],$lineheight,$nftotalfac,1,0,$tbinfo['facture']['align'],1);
		$pdf->Cell($tbinfo['paye']['width'],$lineheight,$nftotalpaye,1,1,$tbinfo['paye']['align'],1);
		
		$pdf->setY(264);
		$pdf->SetFont('Helvetica','B','18');
		$pdf->Cell(130, 12, ' '.fbanque($compte), 'LTB', 0, 'LTB', 1);
		$pdf->Cell(0,12, $nftotalpaye.' '.chr(128).' ', 'RTB', 1, 'R', 1);
		
		$pdf->addPage();
		
		$nftotalfac = 0;
		$nftotalpaye = 0;
		
		$pdf->Image(NIVO."print/illus/logoPrint.png", 20, 10, 50);
		$pdf->Cell(0, 20, "Listing Des Frais", 0, 1, 'R');
		$pdf->Cell(0, 8, "", 0, 1);
		
		$pdf->Cell(130, 12, utf8_decode($nfrais['codepeople'].'   '.$nfrais['pnom'].' '.$nfrais['pprenom']), 'LTB', 0, 'LTB', 1);
		$pdf->Cell(0,12, $nfmonth.' / '.$nfyear.' ', 'RTB', 1, 'R', 1);
		
		$pdf->Cell(0, $lineheight*2, "", 0, 1);
		
		## Entête tablea
		$pdf->SetFont('Helvetica','B',$fontsize);
		foreach($tbinfo as $header) {
			$pdf->Cell($header['width'],$lineheight,$header['name'],1,0,$header['align'],1);
		}
		$pdf->Ln($lineheight);
		
		$pdf->SetFillColor(250);

	}
	
	$compte = $nfrais['banque'];
	
	if ($nfrais['montantpaye'] > $nfrais['montantfacture']) $pdf->SetFillColor(100);
	if ($currentclient !== $nfrais['client'] OR $currentpeople !== $nfrais['idpeople']) {
		$pdf->Cell($tbinfo['client']['width'],$lineheight,utf8_decode($nfrais['client']),1,0,$tbinfo['client']['align'],1);
	} else {
		$pdf->Cell($tbinfo['client']['width'],$lineheight,'',1,0,$tbinfo['client']['align'],1);
	}
	$pdf->Cell($tbinfo['facnum']['width'],$lineheight,$nfrais['facnum'],1,0,$tbinfo['facnum']['align'],1);
	$pdf->Cell($tbinfo['secteur']['width'],$lineheight,$nfrais['secteur'].' '.$nfrais['idmission'],1,0,$tbinfo['secteur']['align'],1);
	$pdf->Cell($tbinfo['agent']['width'],$lineheight,utf8_decode($nfrais['prenom']),1,0,$tbinfo['agent']['align'],1);
	$pdf->Cell($tbinfo['date']['width'],$lineheight,date("d/m", strtotime($nfrais['datemission'])),1,0,$tbinfo['date']['align'],1);
	$pdf->Cell($tbinfo['intitule']['width'],$lineheight,utf8_decode($nfrais['intitule']),1,0,$tbinfo['intitule']['align'],1);
	$pdf->Cell($tbinfo['description']['width'],$lineheight,utf8_decode($nfrais['description']),1,0,$tbinfo['description']['align'],1);
	$pdf->Cell($tbinfo['facture']['width'],$lineheight,$nfrais['montantfacture'],1,0,$tbinfo['facture']['align'],1);
	$pdf->Cell($tbinfo['paye']['width'],$lineheight,$nfrais['montantpaye'],1,1,$tbinfo['paye']['align'],1);
	
	$pdf->SetFillColor(250);
	
	$nftotalfac += $nfrais['montantfacture'];
	$nftotalpaye += $nfrais['montantpaye'];
	
	$currentclient = $nfrais['client'];
	$currentpeople = $nfrais['idpeople'];

}

$pdf->SetFont('Helvetica','B',$fontsize);
$spacer =	$tbinfo['client']['width']+
			$tbinfo['facnum']['width']+
			$tbinfo['secteur']['width']+
			$tbinfo['agent']['width']+
			$tbinfo['date']['width']+
			$tbinfo['intitule']['width']+
			$tbinfo['description']['width'];
$pdf->Cell($spacer,$lineheight,'Total',1,0,'R',1);
$pdf->Cell($tbinfo['facture']['width'],$lineheight,$nftotalfac,1,0,$tbinfo['facture']['align'],1);
$pdf->Cell($tbinfo['paye']['width'],$lineheight,$nftotalpaye,1,1,$tbinfo['paye']['align'],1);

# > # dernier bas de page ##
$pdf->SetFont('Helvetica','B',$fontsize);
$pdf->SetFillColor(200);
$spacer = $tbinfo['client']['width']+$tbinfo['facnum']['width']+$tbinfo['secteur']['width']+$tbinfo['agent']['width']+$tbinfo['date']['width']+$tbinfo['intitule']['width']+$tbinfo['description']['width'];;
$pdf->Cell($spacer,$lineheight,'Total',1,0,R,1);
$pdf->Cell($tbinfo['facture']['width'],$lineheight,$nftotalfac,1,0,$tbinfo['facture']['align'],1);
$pdf->Cell($tbinfo['paye']['width'],$lineheight,$nftotalpaye,1,1,$tbinfo['paye']['align'],1);

$pdf->setY(264);
$pdf->SetFont('Helvetica','B','18');
$pdf->Cell(130, 12, ' '.fbanque($compte), 'LTB', 0, 'LTB', 1);
$pdf->Cell(0,12, $nftotalpaye.' '.chr(128).' ', 'RTB', 1, 'R', 1);
# < # dernier bas de page ##

$pdf->Output(Conf::read('Env.root').$path.$file, 'F');

echo '
<div align="center">
<br><br>
<img src="'.STATIK.'illus/minipdf.gif" alt="print" width="16" height="16" border="0">
<A href="'.NIVO.$path.$file.'" target="_blank">Imprimer la note de frais</A>
</div>
';
?>