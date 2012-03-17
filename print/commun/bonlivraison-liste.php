<?php
function makebdlsup($period, $supplier)
{
	global $DB;

	$bdl = $DB->getArray("SELECT
			b.*, a.prenom, s.societe, s.idsupplier 
		FROM bonlivraison b
			LEFT JOIN agent a on a.idagent = b.idagent
			LEFT JOIN supplier s on b.idsupplier = s.idsupplier
		WHERE b.idsupplier = ".$supplier." 
			AND b.etat='in' 
			AND b.valide = 'Y' 
			AND DATE_FORMAT(b.date, '%Y%m') = '".$period."'");

	//création du pdf et définition des variables
	$path = "document/detail-suppliers/";
	$file = "boncommande-liv-".$supplier."-".$period.".pdf";
	
	$pdf = new fpdi('P', 'mm', 'A4', true, 'UTF-8', false);
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
			if($lastbdc != $temp['nbdc']) {
				$soustotal=0;
				//cloturer ici
				$pdf->SetFont('Helvetica','B',12);
				if($begin != true) {
					$pdf->Cell(95,10,"Total",0,0,'R',0);
					$pdf->Cell(15,10,"".$soustotal,1,0,'R',0);
				}
				$pdf->setFillColor(225);
				$pdf->Cell(10,10,"N",1,0,'C',1);
				$pdf->Cell(20,10,"Date",1,0,'C',1);
				$pdf->Cell(130,10,"Bon de commande COM".prezero($temp['nbdc'],4),1,0,'L',1);
				$pdf->Cell(15,10,"Prix",1,0,'C',1);
				$pdf->Cell(15,10,"Fact",1,1,'C',1);
				
				//$pdf->Cell(65,10,$temp['prenom'],1,1,'R',1);
				$pdf->SetFillColor(250);
				$montantp+=$temp['montant'];
				$factureclientp=$factureclientp+$temp['factureclient'];
			}
			
			$total += $temp['prix'];
			$pdf->SetFont('Helvetica','',9);
			$pdf->Cell(10,7,$temp['id'],1,0,'C');
			$pdf->Cell(20,7,fdate($temp['date']),1,0,'C');
			$pdf->Cell(62,7,$temp['fnom'],'B',0,'C');
			$pdf->Cell(5, 7," -> ",'B',0,"C");
			$pdf->Cell(63,7,$temp['tnom'],'B',0,'C');
			//$pdf->Cell(15,7,$temp['prix'],1,1,'R');
			$pdf->Cell(15,7,$temp['prix'],1,0,'R');
			$pdf->Cell(15,7,$temp['factureclient'],1,1,'R');

			$soustotal = $soustotal+$temp['prix'];
			$totalfac = $totalfac+$temp['factureclient'];
			$lastbdc = $temp['nbdc'];
			$begin = false;
			$pdf->SetFont('Helvetica','B',12);
		}
		$soustotal = $soustotal+$temp['prix'];
		$totalfac = $totalfac+$temp['factureclient'];
		
		$pdf->Cell(160,10,"Sous total pour ce bon de commande : ",1,0,'C');
		$pdf->Cell(15,10,$soustotal,1,0,'R');
		$pdf->Cell(15,10,"".$totalfac,1,1,'R',0);
		
		if($soustotal > $temp['montant'] or $soustotal > $temp['factureclient']) {
			$pdf->setFillColor(175);
		}
		//$pdf->Cell(80,10," Payé : ".$temp['montant']." Refacturé : ".$temp['factureclient'],1,1,'C',1);
		$pdf->Cell(10,10,"",0,1);
		$pdf->Cell(50,10,"Total BDL",1,0);
		$pdf->Cell(20,10,$soustotal,1,1,'R');
		$pdf->Cell(50,10,utf8_decode("Total Facturé"),1,0);
		$pdf->Cell(20,10,$totalfac,1,1,'R');
		$pdf->setY(260);
		$pdf->SetFont('Helvetica','B','18');
		$pdf->SetFillColor(200);
		$pdf->Cell(190,15,"Solde : ".($totalfac-$total),1,1,'C',1);
	
	$pdf->Output(Conf::read('Env.root').$path.$file, 'F');
	
	return "../../".$path.$file;
}
?>