<?php
session_start();

define('NIVO', '../../../');

# Classes utilisées
include NIVO.'classes/vip.php';
require_once(NIVO.'classes/document.php');
require_once(NIVO.'print/dispatch/dispatch_functions.php');

# Entete de page
include NIVO.'nro/fm.php';

## define
$titrePDF = "Planning VIP";
if (!isset($_SESSION['supfields'])) $_SESSION['supfields'] = array();
$temppath = Conf::read('Env.root')."document/temp/vip/planning/";

## PDF
include NIVO.'print/initpdf.php';

### Settings = 
$showmax = 24;
$hline = 5;

$splitwhere = ($_POST['split_people'] == 'oui')?'m.idpeople > 0 AND ':'';
$splitorder = ($_POST['split_people'] == 'oui')?'p.pnom, p.pprenom, ':'';

## get datas
$rows = $DB->getArray('SELECT
		m.idvip, m.vipdate, m.idvipjob, m.idpeople, m.vipin, m.vipout, m.brk, m.datecontrat, m.vipactivite, m.matospeople, 
		j.idclient, j.reference, j.etat,
		a.prenom,
		p.pprenom, p.pnom, m.sexe, p.gsm, p.ndate,
		c.societe AS clsociete,
		s.societe AS ssociete, s.ville
	FROM vipmission m
		LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob
		LEFT JOIN agent a ON j.idagent = a.idagent
		LEFT JOIN client c ON j.idclient = c.idclient
		LEFT JOIN people p ON m.idpeople = p.idpeople
		LEFT JOIN shop s ON m.idshop = s.idshop
	WHERE '.$splitwhere.$_SESSION['missionquid'].' 
	ORDER BY '.$splitorder.$_SESSION['missionsort']);

## Plan des colonnes :
$tbinfo = array(
	'idvip' => 			array('name'=>'M', 			'align'=>'C', 'value' => "\$row['idvip']"							),
	'idvipjob' => 		array('name'=>'J', 			'align'=>'C', 'value' => "\$row['idvipjob']"						),
	'vipdate' => 		array('name'=>'Date', 		'align'=>'C', 'value' => "fdate(\$row['vipdate'])"					),
	'reference' => 		array('name'=>'Référence', 	'align'=>'C', 'value' => "\$row['reference']"						),
	'prenom' => 		array('name'=>'Assist', 	'align'=>'C', 'value' => "\$row['prenom']"							),
	'people' => 		array('name'=>'Promoboy', 	'align'=>'C', 'value' => "\$row['pprenom'].\" \".\$row['pnom']"		),
	'ndate' => 			array('name'=>'Naissance', 	'align'=>'C', 'value' => "fdate(\$row['ndate'])"					),
	'gsm' => 			array('name'=>'Gsm', 		'align'=>'C', 'value' => "\$row['gsm']"								),
	'clsociete' => 		array('name'=>'Client', 	'align'=>'C', 'value' => "\$row['clsociete']"						),
	'vipin' => 			array('name'=>'In', 		'align'=>'C', 'value' => "ftime(\$row['vipin'])"					),
	'vipout' => 		array('name'=>'Out', 		'align'=>'C', 'value' => "ftime(\$row['vipout'])"					),
	'brk' => 			array('name'=>'B', 			'align'=>'C', 'value' => "fnbr0(\$row['brk'])"						),
	'ssociete' => 		array('name'=>'Lieux', 		'align'=>'C', 'value' => "\$row['ssociete'].\" - \".\$row['ville']"	),
	'vipactivite' => 	array('name'=>'Activité', 	'align'=>'C', 'value' => "\$row['vipactivite']"						)
);

if ($_POST['gsm'] != 1) unset($tbinfo['gsm']);
if (!in_array('ndate', $_SESSION['supfields'])) unset($tbinfo['ndate']);	

## Calcul des largeurs de colonnes :
$largeurs = array();

foreach ($rows as $row) {
	## find max lenght
	foreach ($tbinfo as $key => $tb) {
		eval("\$texte = ".$tb['value'].";");
		$largeur = strlen($texte);
		if ($largeur > $showmax) $largeur = $showmax;
		if ($largeur == 0) $largeur = 1;
		$largeur *= (pow($showmax - $largeur, 4) / pow($showmax, 4)) + 1;
		if ($largeur > $largeurs[$key]) $largeurs[$key] = $largeur;
	}
}

## Ajout des largeurs au plan des colonnes
$largeurTotale = array_sum($largeurs);
$largeurReelle = $pdf->getPageHeight() - 10;

foreach ($tbinfo as $key => $tb) $tbinfo[$key]['largeur'] = floor($largeurReelle / $largeurTotale * $largeurs[$key]);

## Affichage des Entetes
function pdfheader ($pdf, $tbinfo) {
	global $hline;
	$pdf->AddPage('L');

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
}

pdfheader($pdf, $tbinfo);

## Affichage du tableau
$lastpeople = '';
foreach ($rows as $row) {
	if (($lastpeople != $row['idpeople']) and ($_POST['split_people'] == 'oui') and !empty($lastpeople)) {
		# Stock temp file
		$filepath = $temppath."planning-".$lastpeople."-".date("Ymd-His").".pdf";
		$pdf->Output($filepath, 'F');
		$docpeople[$lastpeople][] = $filepath;
		
		include NIVO.'print/initpdf.php';
		pdfheader($pdf, $tbinfo);
	}
	foreach ($tbinfo as $key => $tb) {
		eval("\$contenu = ".$tb['value'].";");
		$pdf->MultiCell($tb['largeur'], $hline, substr($contenu, 0, $showmax), 1, $tb['align'], 0, 0, '', '', true);
	}
	$pdf->Ln();
	$lastpeople = $row['idpeople'];
}

if ($_POST['split_people'] == 'oui') {
	$filepath = $temppath."planning-".$lastpeople."-".date("Ymd-His").".pdf";
	$pdf->Output($filepath, 'F');
	$docpeople[$lastpeople][] = $filepath;

	include NIVO.'includes/ifentete.php';
	generateSendTable($docpeople, "people","temp/VPpe", "VPpe", "Planning");
	include NIVO.'includes/ifpied.php';
} else {
	$pdf->Output('VIPplanning-'.date("Ymd-His").'.pdf', 'I');
}
?>