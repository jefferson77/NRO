<?php
$blocparts = explode('-', $_POST['bloc']);


## Detail Facture GB
$gb = $DB->getArray("SELECT 
		me.idmerch, me.idshop, me.datem, me.produit, me.facnum,
		s.societe as ssociete, s.cp as scp, s.ville as sville,
		c.tmheure
	FROM merch me
		LEFT JOIN shop s ON me.idshop = s.idshop
		LEFT JOIN client c ON me.idclient = c.idclient
	WHERE me.idclient IN (".$_POST['clients'].")
		AND me.datem BETWEEN '".$blocparts[0]."-".prezero($blocparts[1], 2)."-01' AND '".date('Y-m-t', strtotime($blocparts[0]."-".prezero($blocparts[1], 2)."-01"))."'
		AND me.genre LIKE 'EAS'
	ORDER BY s.societe, s.ville, me.datem, me.hin2, me.hin1
");

$pdf->AddPage('P');

## TABLE HEADER
$lheight = 4.5; #Hauteur d'une ligne du tableau

$pdf->SetFillColor(0); # background color of next Cell
$pdf->SetTextColor(255); # font color of next cell
$pdf->SetFont("helvetica",'B', 10); # b for bold
$pdf->SetDrawColor(0); # cell borders - similiar to border color
$pdf->SetLineWidth(.1); # similiar to cellspacing
$pdf->SetCellPadding(.5);

$cols = array(
	'1' => array('20',	'Date', 	'C'),
	'2' => array('25',	'N° BC', 	'C'),
	'3' => array('15',	'Mission', 	'C'),
	'4' => array('15',	'Heures', 	'C'),
	'5' => array('20',	'Tarif', 	'C'),
	'6' => array('20',	'Total', 	'R'),
	'7' => array('65',	'Notes', 	'C')
);

$pdf->SetX(15);
foreach ($cols as $col) {
    $pdf->Cell($col[0],$lheight,$col[1],1,0,'C',1);
}
 
$pdf->Ln(); # new row
 
# styling for normal non header cells
$pdf->SetTextColor(0); # black
$pdf->SetFillColor(255); # white
$pdf->SetFont('helvetica','', 9);

$lastmaga = 'none';
$tot = array();
foreach ($gb as $row) {
	if ($lastmaga != $row['idshop']) {
		## ligne des totaux du magasin
		if ($lastmaga != 'none') {
			$pdf->SetX(15);
			Totaux($tot);
			$tot = array();
		}
		
		## ligne de titre du magasoin
		$pdf->SetFont('helvetica','B', 11);
		$pdf->MultiCell(0,$lheight,$row['ssociete'].' - '.$row['scp'].' '.$row['sville'],0,'L',0,1, '', '', 1, 0, 0);
	}

	$merch = new coremerch($row['idmerch']);
	
	$data = array(
		'1' => fdate($row['datem']),
	    '2' => $row['facnum'],
	    '3' => $row['idmerch'],
	    '4' => fnbr($merch->hprest),
	    '5' => feurotcpdf($row['tmheure']).'/h',
		'6' => feurotcpdf($merch->hprest * $row['tmheure']).'  ',
	    '7' => stripslashes($row['produit'])
	);
	
	$lastfac = $row['facnum'];
	
	
	## Affichage des cellules
	$pdf->SetX(15);
	foreach ($cols as $c => $col) {
		switch ($c) {
			case '7':
				$pdf->SetFont('helvetica','I', 7);
			break;

			default:
				$pdf->SetFont('helvetica','', 8);
			break;
		}
		$pdf->MultiCell($col[0],$lheight,$data[$c],1,$col[2],1,0, '', '', 1, 0, 0);
	}

    $pdf->Ln(); # new row 

	## totaux
	$tot['heures'] += $merch->hprest;
	$tot['montant'] += $merch->hprest * $row['tmheure'];
	$grtot[$row['tmheure']] += $merch->hprest;

	$lastmaga = $row['idshop'];
}

## last totaux
$pdf->SetX(15);
Totaux($tot);
$pdf->Ln();

## Grands Totaux
$pdf->SetFont('helvetica','B', 11);
$pdf->MultiCell(0,$lheight,'Totaux',0,'L',0,1, '', '', 1, 0, 0);
$pdf->Ln();

foreach ($grtot as $tarif => $heures) {
	$pdf->SetX(15);
	$pdf->SetFont('helvetica', '', 9); $pdf->MultiCell($cols[1][0] + $cols[2][0] + $cols[3][0] ,$lheight,'au tarif '.feurotcpdf($tarif).'/h   ',0,'R',0,0, '', '', 1, 0, 0);
	$pdf->SetFont('helvetica','B', 9); $pdf->MultiCell($cols[4][0] ,$lheight,fnbr($heures),1,'C',0,0, '', '', 1, 0, 0);
	$pdf->SetFont('helvetica', '', 9); $pdf->MultiCell($cols[5][0] ,$lheight,' ',0,'R',0,0, '', '', 1, 0, 0);
	$pdf->SetFont('helvetica','B', 9); $pdf->MultiCell($cols[6][0] ,$lheight,feurotcpdf($heures * $tarif).' ',1,'R',0,1, '', '', 1, 0, 0);
	$ttot['heures'] += $heures;
	$ttot['montant'] += $heures * $tarif;
}

$pdf->SetX(15);
$pdf->Line(0,  $pdf->GetY(), $pdf->getPageWidth(), $pdf->GetY());
$pdf->SetFont('helvetica','B', 9); 
$pdf->MultiCell($cols[1][0] + $cols[2][0] + $cols[3][0] ,$lheight,'Total   ',0,'R',0,0, '', '', 1, 0, 0);
$pdf->MultiCell($cols[4][0] ,$lheight,fnbr($ttot['heures']),1,'C',0,0, '', '', 1, 0, 0);
$pdf->MultiCell($cols[5][0] ,$lheight,'Total',0,'R',0,0, '', '', 1, 0, 0);
$pdf->MultiCell($cols[6][0] ,$lheight,feurotcpdf($ttot['montant']).' ',1,'R',0,1, '', '', 1, 0, 0);

?>