<?php 
$bdl = $DB->getArray("SELECT b.*, a.prenom, s.societe, s.idsupplier FROM bonlivraison b
	LEFT JOIN agent a on a.idagent = b.idagent
	LEFT JOIN supplier s on b.idsupplier = s.idsupplier
	LEFT JOIN bondecommande d on d.id=b.nbdc
	WHERE b.idsupplier='".$lastsup."' and b.etat='in' and d.etat='in' and CONCAT(MONTH(b.date), '-',YEAR(b.date)) LIKE '".$_REQUEST['period']."' ORDER BY b.nbdc;");
if(count($bdl)>0)
{
	$pdf->addPage();
	$pdf->setFillColor(200);
	$pdf->Image($_SERVER['DOCUMENT_ROOT']."/print/illus/logoPrint.png", 17, 17, 50);
	$pdf->SetFont('Helvetica','B',30);
	$pdf->Cell(0, 20, "Livraisons", 0, 1, 'C');
	$pdf->SetFont('Helvetica','B',16);
	$pdf->Cell(0, 8, "", 0, 1);
	
	$pdf->Cell(130, 12, $bdl[0]['societe'].' ('.$bdl[0]['idsupplier'].')', 'LTB', 0, 'LTB', 1);
	$pdf->Cell(0,12, $period, 'RTB', 1, 'R', 1);
	$pdf->Cell(0, $lineheight*2, "", 0, 1);
	$pdf->SetFont('Helvetica','B',12);
	$begin = true;
	$soustotal = 0;
	$total = 0;
	$montantp=0;
	$factureclientp=0;
	foreach($bdl as $temp)
	{
		if($lastbdc != $temp['nbdc']) //nouveau bon de commande
		{
			if($begin!=true)
			{
				$test = $DB->getRow("SELECT montant, factureclient from bondecommande where id =".$lastbdc);
				$pdf->Cell(95,10,"Sous total pour ce bon de commande : ",1,0,'R');
				$pdf->Cell(15,10,$soustotal,1,0,'C');
				if($soustotal > $test['montant'] or $soustotal > $test['factureclient'])
				{
					$pdf->setFillColor(175);
				}
				$pdf->Cell(80,10," Payé : ".$test['montant']." Refacturé : ".$test['factureclient'],1,1,'C',1);
				$pdf->setFillColor(250);
				$pdf->Cell(0,5,"",0,1);
				$height = $pdf->getY();
				if($height > 250)
				{
					$pdf->Cell(5,10,$height,1,1);
					$pdf->addPage();
				}
			}
			$soustotal=0;
			//cloturer ici
			$pdf->SetFont('Helvetica','B',12);
			$pdf->setFillColor(225);
			$pdf->Cell(95,10,"COM".prezero($temp['nbdc'],4),1,0,'L',1);
			$pdf->Cell(15,10,"Prix",1,0,'C',1);
			$pdf->Cell(80,10,$temp['prenom'],1,1,'R',1);
			$pdf->SetFillColor(250);
			$montantp=$montantp+$test['montant'];
			$factureclientp=$factureclientp+$test['factureclient'];
		}
		$total = $total+$temp['prix'];
		$pdf->SetFont('Helvetica','',10);
		$pdf->Cell(10,7,$temp['id']."  ",1,0,'R');
		$pdf->Cell(40,7,$temp['fnom'],0,0,'L');
		$pdf->Cell(5, 7," -> ",0,0,"C");
		$pdf->Cell(40,7,$temp['tnom'],0,0,'R');
		$pdf->Cell(15,7,$temp['prix'],1,1,'R');
		$soustotal = $soustotal+$temp['prix'];
		$lastbdc = $temp['nbdc'];
		$begin = false;
		$pdf->SetFont('Helvetica','B',12);
		}
	$test = $DB->getRow("SELECT montant, factureclient from bondecommande where id =".$lastbdc);
	$montantp=$montantp+$test['montant'];
	$factureclientp=$factureclientp+$test['factureclient'];
	$pdf->Cell(95,10,"Sous total pour ce bon de commande : ",1,0,'C');
	$pdf->Cell(15,10,$soustotal,1,0,'R');
	if($soustotal > $test['montant'] or $soustotal > $test['factureclient'])
	{
		$pdf->setFillColor(175);
	}
	$pdf->Cell(80,10," Payé : ".$test['montant']." Refacturé : ".$test['factureclient'],1,1,'C',1);
	$pdf->Cell(10,10,"",0,1);
	$pdf->Cell(50,10,"Total BDL",1,0);
	$pdf->Cell(20,10,$total,1,1,'R');
	$pdf->Cell(50,10,"Total BDC",1,0);
	$pdf->Cell(20,10,$montantp,1,1,'R');
	$pdf->Cell(50,10,"Total Facturé",1,0);
	$pdf->Cell(20,10,$factureclientp,1,1,'R');
	$pdf->setY(260);
	$pdf->SetFont('Helvetica','B','18');
	$pdf->SetFillColor(200);
	$pdf->Cell(190,15,"Solde : ".($factureclientp-$total),1,1,'C',1);
}
?>