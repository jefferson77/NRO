<?php
session_start();

define('NIVO', '../../../');

# Classes utilisées
require_once(NIVO."nro/fm.php");
include NIVO.'classes/facture.php';
include NIVO."classes/merch.php";

$titrePDF = $_POST['titre'];

include NIVO.'print/initpdf.php';

## parts
function Totaux ($tot) {
	global $pdf, $cols, $lheight, $lastfac;
	$pdf->SetFont('helvetica', '', 9); $pdf->MultiCell($cols[1][0] + $cols[2][0] + $cols[3][0] ,$lheight,'Heures',0,'R',0,0, '', '', 1, 0, 0);
	$pdf->SetFont('helvetica','B', 9); $pdf->MultiCell($cols[4][0] ,$lheight,fnbr($tot['heures']),1,'C',0,0, '', '', 1, 0, 0);
	$pdf->SetFont('helvetica', '', 9); $pdf->MultiCell($cols[5][0] ,$lheight,'Total',0,'R',0,0, '', '', 1, 0, 0);
	$pdf->SetFont('helvetica','B', 9); $pdf->MultiCell($cols[6][0] ,$lheight,feurotcpdf($tot['montant']).'  ',1,'R',0,1, '', '', 1, 0, 0);
}

include 'gb-pagedetail.php';

$pdf->Output('detail-eas-'.date("Ymd").'.pdf', 'I');

?>