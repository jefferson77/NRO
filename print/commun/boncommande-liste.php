<?php
function makebdcsup($period)
{
	global $DB;

	$tab = $DB->getArray("SELECT b.*, s.societe, s.idsupplier, a.prenom FROM bondecommande b 
		LEFT JOIN supplier s ON b.idsupplier = s.idsupplier
		LEFT JOIN agent a ON b.idagent = a.idagent
		WHERE CONCAT(MONTH(date), '-',YEAR(date)) LIKE '".$period."' AND b.etat='in' ORDER BY b.idsupplier");

	//création du pdf et définition des variables
	$path = "document/boncommande/";
	$file = "boncommande-supplier-".$period.".pdf";

	$lineheight = '5';
	$fontsize = '7';

	$tbinfo = array(
		'id'	=>			array('name'=>'N',					'width'=>'10',   'align'=>'C'),
		'secteur' => 		array('name'=>'Mission', 			'width'=>'14',  'align'=>'C'),
		'agent' => 			array('name'=>'Agent', 				'width'=>'20',  'align'=>'C'),
		'date' => 			array('name'=>'Date', 				'width'=>'10',  'align'=>'C'),
		'intitule' => 		array('name'=>'Intitulé', 			'width'=>'60',  'align'=>'C'),
		'paye' => 			array('name'=>'Payé', 				'width'=>'12',  'align'=>'C'),
		'facture' => 		array('name'=>'Facturé', 			'width'=>'12',  'align'=>'C'),
		'soldej' => 		array('name'=>'Solde Job', 			'width'=>'16',  'align'=>'C'),
		'facin' => 			array('name'=>'Fac S', 				'width'=>'16',  'align'=>'C'),
		'facout' => 		array('name'=>'Fac C',				'width'=>'16',  'align'=>'C')
	);
		
	$pdf= new fpdi('P', 'mm', 'A4', true, 'UTF-8', false);
	$pdf->SetFillColor(225);
	
//chaque tab contient toutes les infos d'un bon de commande !
	foreach($tab as $row)
	{
		//$lastsup = $row['idsupplier'];
		if($row['type']=='ext')
		{
			/*$tbinfo=$tbinfoext;*/
			$spacer=
			 $tbinfo['secteur']['width']+$tbinfo['agent']['width']+$tbinfo['id']['width']
			+$tbinfo['date']['width']+$tbinfo['intitule']['width']+$tbinfo['description']['width']
			+$tbinfo['paye']['width']+$tbinfo['facture']['width']+$tbinfo['facin']['width']+$tbinfo['facout']['width'];
		}
		else
		{
			/*$tbinfo=$tbinfoint;*/
			$spacer= $tbinfo['secteur']['width']+$tbinfo['agent']['width']+$tbinfo['id']['width']
			+$tbinfo['date']['width']+$tbinfo['intitule']['width']+$tbinfo['description']['width']
			+$tbinfo['paye']['width']+$tbinfo['facture']['width']+$tbinfo['facin']['width']+$tbinfo['facout']['width'];
		}
		//vérifie si on est dans le même supplier que précédemment
		if($lastsup != $row['idsupplier'])
		{
			//nouveau supplier
			$sup = true;
			$pdf->SetFont('Helvetica','B',$fontsize);
			$pdf->SetFillColor(200);
			$temp = $tbinfo['paye']['width']+$tbinfo['facture']['width']+$tbinfo['facin']['width']+$tbinfo['facout']['width'];
			$pdf->Cell($spacer-$temp,$lineheight,'Solde total pour ce fournisseur',1,0,R,1);
			$pdf->Cell($tbinfo['paye']['width'],$lineheight,$totpay,1,0,$tbinfo['facture']['align'],1);
			$pdf->Cell($tbinfo['facture']['width'],$lineheight,$totfac,1,0,$tbinfo['facture']['align'],1);
			$pdf->Cell($tbinfo['soldej']['width'],$lineheight,$nftotalfac,1,0,$tbinfo['soldej']['align'],1);
			//legende
			$pdf->setY(251);
			$pdf->SetFillColor(225);
			$pdf->Cell(10,$lineheight, '', 1, 0,'C',1);
			$pdf->SetFillColor(255);
			$pdf->Cell(50,$lineheight, ' Informations manquantes pour ce bon de commande', 0, 1,'L',1);
			$pdf->SetFillColor(100);
			$pdf->Cell(10,$lineheight, '', 1, 0,'C',1);
			$pdf->SetFillColor(255);
			$pdf->Cell(50,$lineheight, ' Erreur de refacturation', 0, 1,'L',1);

			$pdf->SetFillColor(200);
			$pdf->setY(264); //on va au pied de page
			$pdf->SetFont('Helvetica','B','18');
			$pdf->Cell(130, 12, 'Solde', 'LTB', 0, 'LTB', 1);
			$pdf->Cell(0,12, $nftotalfac.' '.chr(128).' ', 'RTB', 1, 'R', 1);
				
				include("bdc-list-bdl.php");
				
			$pdf->SetFont('Helvetica','B',18);
			$pdf->setFillColor(200);
			//nouvelle page
			$pdf->addPage();
			$nftotalfac = 0;
			$nftotalpaye = 0;
			$totpay=0;
			$totfac=0;			
			$pdf->Image($_SERVER['DOCUMENT_ROOT']."/print/illus/logoPrint.png", 17, 17,50);
			$pdf->Cell(0, 20, "Listing des bons de commande", 0, 1, 'R');
			$pdf->Cell(0, 8, "", 0, 1);
			$pdf->Cell(130, 12, $row['societe'].' ('.$row['idsupplier'].')', 'LTB', 0, 'LTB', 1);
			$pdf->Cell(0,12, $period, 'RTB', 1, 'R', 1);
			$pdf->Cell(0, $lineheight*2, "", 0, 1);
			## Entête tableau
			$pdf->SetFont('Helvetica','B',$fontsize);
			foreach($tbinfo as $header) {
				$pdf->Cell($header['width'],$lineheight,$header['name'],1,0,$header['align'],1);
			}
			$pdf->Ln($lineheight);
			$pdf->SetFillColor(250);
		}
		$sup=false;
		if($row['type']=='ext')
		{
			if (($row['montant']-$row['factureclient'])>0)
			{
				$pdf->SetFillColor(100);
			}
			else if(empty($row['nfacout']) or empty($row['nfac']))
			{
				$pdf->SetFillColor(225);
			}
		}
		else
		{
			if(empty($row['nfac']))
			{
				$pdf->SetFillColor(225);
			}
		}
		
		$pdf->Cell($tbinfo['id']['width'],		$lineheight,						$row['id'],							 	 1,0,$tbinfo['client']['align'],1);
		$pdf->Cell($tbinfo['secteur']['width'],	$lineheight,						$row['secteur'].' '.$row['idjob'], 		 1,0,$tbinfo['secteur']['align'],1);
		$pdf->Cell($tbinfo['agent']['width'],	$lineheight,						$row['prenom'],						 	 1,0,$tbinfo['agent']['align'],1);
		$pdf->Cell($tbinfo['date']['width'],	$lineheight,date("d/m",   strtotime($row['date'])),				  	 		 1,0,$tbinfo['date']['align'],1);
		$pdf->Cell($tbinfo['intitule']['width'],$lineheight,						$row['titre'],							 1,0,$tbinfo['intitule']['align'],1);
		$pdf->Cell($tbinfo['paye']['width'],	$lineheight,						$row['montant'],						 1,0,$tbinfo['paye']['align'],1);
		$pdf->Cell($tbinfo['facture']['width'],	$lineheight,						$row['factureclient'],					 1,0,$tbinfo['facture']['align'],1);
		$pdf->Cell($tbinfo['soldej']['width'],	$lineheight,						$row['factureclient']-$row['montant'],	 1,0,$tbinfo['soldej']['align'],1);
		$pdf->Cell($tbinfo['facin']['width'],	$lineheight,						$row['nfac'],							 1,0,$tbinfo['facin']['align'],1);
		$pdf->Cell($tbinfo['facout']['width'],	$lineheight,						$row['nfacout'],						 1,1,$tbinfo['facout']['align'],1);
		
		$pdf->SetFillColor(250);
		
		$nftotalfac += $row['factureclient']-$row['montant'];
		$totpay += $row['montant'];
		$totfac+= $row['factureclient'];
		//supplier identique, on ajoute une ligne dans le tableau
		$lastsup = $row['idsupplier'];
	}
	$pdf->SetFont('Helvetica','B',$fontsize);
	$pdf->SetFillColor(200);
	$temp = $tbinfo['paye']['width']+$tbinfo['facture']['width']+$tbinfo['facin']['width']+$tbinfo['facout']['width'];
	$pdf->Cell($spacer-$temp,$lineheight,'Soldes pour ce fournisseur',1,0,R,1);
	$pdf->Cell($tbinfo['paye']['width'],$lineheight,$totpay,1,0,$tbinfo['paye']['align'],1);
	$pdf->Cell($tbinfo['facture']['width'],$lineheight,$totfac,1,0,$tbinfo['facture']['align'],1);
	$pdf->Cell($tbinfo['soldej']['width'],$lineheight,$nftotalfac,1,0,$tbinfo['soldej']['align'],1);
	//legende
	$pdf->setY(251);
	$pdf->SetFillColor(225);
	$pdf->Cell(10,$lineheight, '', 1, 0,'C',1);
	$pdf->SetFillColor(255);
	$pdf->Cell(50,$lineheight, ' Informations manquantes pour ce bon de commande', 0, 1,'L',1);
	
	$pdf->SetFillColor(100);
	$pdf->Cell(10,$lineheight, '', 1, 0,'C',1);
	$pdf->SetFillColor(255);
	$pdf->Cell(50,$lineheight, ' Erreur de refacturation', 0, 1,'L',1);
	
	$pdf->SetFillColor(200);
	$pdf->setY(264); //on va au pied de page
	$pdf->SetFont('Helvetica','B','18');
	$pdf->Cell(130, 12, 'Solde', 'LTB', 0, 'LTB', 1);
	$pdf->Cell(0,12, $nftotalfac.' '.chr(128).' ', 'RTB', 1, 'R', 1);
	
	//si c'est le dernier
	include("bdc-list-bdl.php");
	
	$pdf->Output(Conf::read('Env.root').$path.$file, 'F');
	
	return "../../".$path.$file;
}
?>