<?php
session_start();

# Entete de page
include NIVO.'nro/fm.php';

## PDF
include NIVO.'print/initpdf.php';

$titrePDF = "RAPPORT DE PRESENCE / AANWEZIGHEIDSRAPPORT";

### Settings = 
$showmax = 24;
$hline = 6;

################### Get infos du job ########################
$rows = $DB->getArray("SELECT 
		m.idvipjob, m.idvip, m.vipactivite, m.vipdate, m.vipin, m.vipout, m.brk,
		j.reference, j.datein, j.dateout, 
		c.societe AS clsociete, c.idclient, c.fax,
		s.societe AS ssociete, s.ville, 
		p.pprenom, p.pnom, p.gsm
	FROM vipmission m
		LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob
		LEFT JOIN client c ON j.idclient = c.idclient
		LEFT JOIN shop s ON m.idshop = s.idshop
		LEFT JOIN people p ON m.idpeople = p.idpeople
	
	WHERE j.idvipjob = ".$_REQUEST['idvipjob']." ".(((!empty($_REQUEST['vipdate1']) and !empty($_REQUEST['vipdate2'])))?"AND m.vipdate BETWEEN '".fdatebk($_POST['vipdate1'])."' AND '".fdatebk($_POST['vipdate2'])."' ":"")."
	ORDER BY m.vipdate, m.vipin");

## Plan des colonnes :
$tbinfo = array(
	'num' => 		array('name' => 'N°', 						'align'=>'C', 'largeur' => 11, 'value' => "\$row['idvip']"								),
	'lieu' => 		array('name' => 'Lieux/Plaats', 			'align'=>'C', 'largeur' => 40, 'value' => "\$row['ssociete'].\" - \".\$row['ville']"	),
	'people' => 	array('name' => 'People', 					'align'=>'C', 'largeur' => 40, 'value' => "\$row['pprenom'].\" \".\$row['pnom']"		),
	'gsm' => 		array('name' => 'GSM',	 					'align'=>'C', 'largeur' => 22, 'value' => "\$row['gsm']"								),
	'activité' => 	array('name' => 'Activité/Functie',			'align'=>'C', 'largeur' => 35, 'value' => "\$row['vipactivite']"						),
	'date' => 		array('name' => 'Date', 					'align'=>'C', 'largeur' => 18, 'value' => "fdate(\$row['vipdate'])"						),
	'in' => 		array('name' => 'In', 						'align'=>'C', 'largeur' => 10, 'value' => "ftime(\$row['vipin'])"						),
	'out' => 		array('name' => 'Out', 						'align'=>'C', 'largeur' => 10, 'value' => "ftime(\$row['vipout'])"						),
	'br' => 		array('name' => 'Br', 						'align'=>'C', 'largeur' =>  7, 'value' => "fnbr0(\$row['brk'])"							),
	'sep' => 		array('name' => '', 						'align'=>'C', 'largeur' =>  1, 'value' => "''"											),
	'inreel' => 	array('name' => 'In', 						'align'=>'C', 'largeur' => 15, 'value' => "''"											),
	'outreel' => 	array('name' => 'Out', 						'align'=>'C', 'largeur' => 15, 'value' => "''"											),
	'brreel' => 	array('name' => 'Br', 						'align'=>'C', 'largeur' =>  8, 'value' => "''"											),
	'remarque' => 	array('name' => 'Remarque / Opmerkingen', 	'align'=>'C', 'largeur' => 44, 'value' => "''"											)
);

## PDF
$pdf->AddPage('L');

$pdf->SetTextColor(0); # black
$pdf->SetFillColor(255); # white
$pdf->SetFont('helvetica','', 8);

$pdf->MultiCell($pdf->getPageHeight(), 10, "Job : ".$rows[0]['idvipjob']." Reference : ".$rows[0]['reference']."  Date/Datum : ".fdate($rows[0]['datein']).' - '.fdate($rows[0]['dateout'])."  Client/Klant : ".$rows[0]['idclient']." ".$rows[0]['clsociete'] ." (fax : ".$rows[0]['fax'].")", 0, 'L', 0, 0, '', '', true);
$pdf->Ln();

$pdf->SetFillColor(0); # background color of next Cell
$pdf->SetTextColor(255); # font color of next cell
$pdf->SetFont("helvetica",'B', 9); # b for bold
$pdf->SetDrawColor(0); # cell borders - similiar to border color
$pdf->SetLineWidth(.1); # similiar to cellspacing
$pdf->SetCellPadding(.5);

foreach ($tbinfo as $key => $tb) {
	$pdf->MultiCell($tb['largeur'], $hline, $tb['name'], 1, 'C', 1, 0, '', '', true);
}
$pdf->Ln();

$pdf->SetTextColor(0); # black
$pdf->SetFillColor(255); # white
$pdf->SetFont('helvetica','', 8);

## Affichage du tableau
foreach ($rows as $row) {
	foreach ($tbinfo as $key => $tb) {
		eval("\$contenu = ".$tb['value'].";");
		$pdf->MultiCell($tb['largeur'], $hline, substr($contenu, 0, $showmax), 1, $tb['align'], 0, 0, '', '', true);
	}
	$pdf->Ln();
}

$pdf->MultiCell(200, $hline, "Ce rapport tient lieu de bon de commande et de justificatif de payment / Dit rapport is geldig als bestelbon en betalingsdocument", 0, 'L', 0, 0, '', '', true);

$pdf->Output('VIPpresence-'.date("Ymd-His").'.pdf', 'I');
?>