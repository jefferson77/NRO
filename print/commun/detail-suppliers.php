<?php

require_once(NIVO.'classes/document.php');

require_once(NIVO."classes/anim.php");
require_once(NIVO."classes/merch.php");
require_once(NIVO."classes/vip.php");

function makebdcsup($period,$supplier)
{
	global $DB;
	//j'ai besoin des détails de chaque mission : numéro, heures, km, client ?
	$tarif = $DB->getRow("SELECT th, tkm FROM supplier WHERE idsupplier = ".$supplier);
			
	$merch = $DB->getArray("SELECT CONCAT(p.pprenom,' ',p.pnom) as people, m.idmerch, m.datem as date, p.idsupplier, CONCAT(sh.societe,' - ', sh.ville) as lieu, c.societe as client, 'merch' as secteur, 'ME' as sect FROM people p
		LEFT JOIN merch m ON p.idpeople = m.idpeople
		LEFT JOIN supplier s ON p.idsupplier = s.idsupplier
		LEFT JOIN shop sh ON sh.idshop = m.idshop
		LEFT JOIN client c ON m.idclient = c.idclient
		WHERE p.idsupplier = $supplier AND 
		DATE_FORMAT(m.datem, '%Y%m') LIKE '".$period."'
		ORDER BY p.idsupplier;");

	$anim = $DB->getArray("SELECT CONCAT(p.pprenom,' ',p.pnom) as people,a.idanimation, a.datem as date, p.idsupplier, CONCAT(sh.societe,' - ', sh.ville) as lieu, c.societe as client, 'anim' as secteur, 'AN' as sect FROM people p
		LEFT JOIN animation a ON p.idpeople = a.idpeople
		LEFT JOIN supplier s ON p.idsupplier = s.idsupplier
		LEFT JOIN shop sh ON sh.idshop = a.idshop
		LEFT JOIN animjob an ON an.idanimjob = a.idanimjob
		LEFT JOIN client c ON an.idclient = c.idclient
		WHERE p.idsupplier = $supplier AND
		DATE_FORMAT(a.datem, '%Y%m') LIKE '".$period."'
		ORDER BY p.idsupplier");

	$vip = $DB->getArray("SELECT CONCAT(p.pprenom,' ',p.pnom) as people,v.idvip, v.vipdate as date, p.idsupplier, CONCAT(sh.societe,' - ', sh.ville) as lieu, c.societe as client, 'vip' as secteur, 'VIP' as sect FROM people p
		LEFT JOIN vipmission v ON p.idpeople = v.idpeople
		LEFT JOIN supplier s ON p.idsupplier = s.idsupplier
		LEFT JOIN shop sh ON sh.idshop = v.idshop
		LEFT JOIN vipjob vi ON vi.idvipjob = v.idvipjob
		LEFT JOIN client c ON vi.idclient = c.idclient
		WHERE p.idsupplier = $supplier
		AND DATE_FORMAT(v.vipdate, '%Y%m') LIKE '".$period."'
		ORDER BY p.idsupplier");


	$res = array_merge($merch, $anim, $vip);
	$heures[] = array();

	$i=0;
	foreach($res as $row)
	{
		switch($row['secteur'])
		{
			case "merch" :
				$coremerch = new coremerch($row['idmerch']);
				$heures[$i] = 
				array(
					"idmission" => 	$row['idmerch'], 
					"idsupplier" => $row['idsupplier'],
					"people" =>     $row['people'],
					"secteur"=>		$row['secteur'], 
					"sect"=>		$row['sect'],
					"date" => 		$row['date'],
					"lieu" => 		$row['lieu'],
					"client" => 	$row['client'],
					"heures"=>		$coremerch->hprest, 
					"km"=>			$coremerch->kipay
					);
			break;

			case "anim" : 
				$coreanim = new coreanim($row['idanimation']);
				$heures[$i] = 
				array(
					"idmission" => 	$row['idanimation'], 
					"idsupplier" => $row['idsupplier'], 
					"people" =>     $row['people'],
					"secteur"=>		$row['secteur'], 
					"sect"=>		$row['sect'],
					"date" => 		$row['date'],
					"lieu" => 		$row['lieu'],
					"client" => 	$row['client'],
					"heures"=>		$coreanim->hprest, 
					"km"=>			$coreanim->kipay
					);
			break;
//frais437 = montant en euro des déplacements (forfait ou autre)
			case "vip" :
				$corevip = new corevip($row['idvip']);
				$heures[$i] = 
				array(
					"idmission" => 	$row['idvip'], 
					"idsupplier" => $row['idsupplier'], 
					"people" =>     $row['people'],
					"secteur"=>		$row['secteur'], 
					"sect"=>		$row['sect'], 
					"date" => 		$row['date'],
					"lieu" => 		$row['lieu'],
					"client" => 	$row['client'],
					"heures"=>		$corevip->hprest, 
					"km"=>			$corevip->kipay,
					"kmf" =>		$corevip->kiforf
				);
			break;
		}
		$i++;
	}

	foreach ($heures as $key => $row) $idsupplier[$key]  = $row['idsupplier'];

	array_multisort($idsupplier, SORT_NUMERIC, $heures);

	//création du pdf et définition des variables
	$path = "document/detail-suppliers/";
	$file = "boncommande-supplier-".$supplier."-".$period.".pdf";

	$lineheight = '5';
	$fontsize = '10';

	
	$tbinfo = array(
		'id'	=>			array('name'=>'N',					'width'=>'20',  'align'=>'C'),
		'date' => 			array('name'=>'Date', 				'width'=>'20',  'align'=>'C'),
		'client' => 		array('name'=>'Client', 			'width'=>'60',  'align'=>'C'),
		'people' =>  		array('name'=>'People',				'width'=>'60',  'align'=>'C'),
		'lieu' => 			array('name'=>'Lieu', 				'width'=>'60',  'align'=>'C'),
		'heures' => 		array('name'=>'H', 					'width'=>'20',  'align'=>'C'),
		'km' => 			array('name'=>'Km', 				'width'=>'20',  'align'=>'C'),
		'kmf' => 			array('name'=>'Forfait',			'width'=>'17',  'align'=>'C'),
		
		);
		
	$pdf= new fpdi('L', 'mm', 'A4', true, 'UTF-8', false);
	$pdf->SetFillColor(225);
	$h1 = $heures;
	
//chaque tab contient toutes les infos d'un bon de commande !
	foreach($h1 as $heures)
	{
			$spacer=$tbinfo['id']['width']+$tbinfo['secteur']['width'];
			
		//vérifie si on est dans le même supplier que précédemment
		if($lastsup != $heures['idsupplier']) {
			//nouveau supplier
			$sup = true;
			$pdf->SetFont('Helvetica','',$fontsize);
			$pdf->SetFillColor(200);
			$temp = $tbinfo['paye']['width']+$tbinfo['facture']['width']+$tbinfo['facin']['width']+$tbinfo['facout']['width'];
			$pdf->Cell($spacer-$temp,$lineheight,'Total',1,0,R,1);
			$pdf->Cell($tbinfo['heures']['width'],$lineheight,$totheures,1,0,$tbinfo['heures']['align'],1);
			$pdf->Cell($tbinfo['km']['width'],$lineheight,$totkm,1,0,$tbinfo['km']['align'],1);				
			$pdf->SetFont('Helvetica','B',20);
			$pdf->setFillColor(200);
			//nouvelle page
			$pdf->addPage();
			$nftotalfac = 0;
			$nftotalpaye = 0;
			$totheures=0;
			$totkmforf = 0;
			$totkm=0;			
			$pdf->Image($_SERVER['DOCUMENT_ROOT']."/print/illus/logoPrint.png", 17, 17, 50);
			$pdf->Cell(0, 20, "Listing des missions de ce fournisseur", 0, 1, 'R');
			$pdf->Cell(0, 8, "", 0, 1);
			$supplier = $DB->getOne("SELECT societe from supplier WHERE idsupplier=".$heures['idsupplier']);
			$pdf->Cell(130, 12, $supplier.' ('.$heures['idsupplier'].')', 'LTB', 0, 'LTB', 1);
			$pdf->Cell(0,12, date("F Y", strtotime(substr($period, 0, 4)."-".substr($period, 4, 2)."-01")), 'RTB', 1, 'R', 1);
			$pdf->Cell(0, 3, "", 0, 1);
			## Entête tableau
			$pdf->SetFont('Helvetica','B','14');
			foreach($tbinfo as $header) {
				$pdf->Cell($header['width'],$lineheight,utf8_decode($header['name']),1,0,$header['align'],1);
			}
			$pdf->Ln($lineheight);
			$pdf->SetFillColor(250);
		}
		$sup=false;
		$pdf->SetFillColor(250);
		$pdf->SetFont('Helvetica','','8');
		
		$pdf->Cell($tbinfo['id']['width'],		$lineheight,  $heures['sect'].' '.$heures['idmission'], 	 1,0,$tbinfo['id']['align'],1);
		$pdf->Cell($tbinfo['date']['width'],	$lineheight,  fdate($heures['date']), 						 1,0,$tbinfo['secteur']['align'],1);
		$pdf->Cell($tbinfo['client']['width'],	$lineheight,  $heures['client'], 							 1,0,$tbinfo['client']['align'],1);
		$pdf->Cell($tbinfo['people']['width'],	$lineheight,  $heures['people'], 							 1,0,$tbinfo['people']['align'],1);
		$pdf->Cell($tbinfo['lieu']['width'],	$lineheight,  $heures['lieu'], 								 1,0,$tbinfo['lieu']['align'],1);
		$pdf->Cell($tbinfo['heures']['width'],	$lineheight,  fnbr($heures['heures']),						 1,0,$tbinfo['heures']['align'],1);
		$pdf->Cell($tbinfo['km']['width'],		$lineheight,  fnbr($heures['km']),						     1,0,$tbinfo['km']['align'],0);
		$pdf->Cell($tbinfo['kmf']['width'],		$lineheight,  fnbr($heures['kmf']),						     1,1,$tbinfo['kmf']['align'],0);
		
		
		$pdf->SetFillColor(250);
		
		$totheures += $heures['heures'];
		$totkm+= $heures['km'];
		$totkmforf += $heures['kmf'];
		//supplier identique, on ajoute une ligne dans le tableau
		$lastsup = $heures['idsupplier'];
	}
	
	//finissage
	$pdf->SetFont('Helvetica','B',$fontsize);
	$temp = $tbinfo['id']['width']+$tbinfo['date']['width']+$tbinfo['client']['width']+$tbinfo['people']['width']+$tbinfo['lieu']['width'];
	$pdf->Cell($temp,$lineheight,'Total (h / km / '.chr(128).' )',0,0,R,1);	
	$pdf->Cell($tbinfo['heures']['width'],$lineheight,$totheures,1,0,$tbinfo['heures']['align'],1);
	$pdf->Cell($tbinfo['km']['width'],$lineheight,fnbr($totkm),1,0,$tbinfo['km']['align'],1);
	$pdf->Cell($tbinfo['kmf']['width'],$lineheight,fnbr($totkmforf),1,1,$tbinfo['kmf']['align'],1);	
	
	$pdf->SetFont('Helvetica','',$fontsize);
	$pdf->Cell($temp,$lineheight,'Tarif ('.chr(128).' )',0,0,R,1);	
	$pdf->Cell($tbinfo['heures']['width'],$lineheight,fnbr($tarif['th']),1,0,$tbinfo['heures']['align'],1);
	$pdf->Cell($tbinfo['km']['width'],$lineheight,fnbr($tarif['tkm']),1,0,$tbinfo['km']['align'],1);
	$pdf->Cell($tbinfo['kmf']['width'],$lineheight,'1',1,1,$tbinfo['kmf']['align'],1);

	$pdf->SetFont('Helvetica','B',$fontsize);
	$pdf->Cell($temp,$lineheight,'Total ('.chr(128).' )',0,0,R,1);
	$pdf->Cell($tbinfo['heures']['width'],$lineheight,fnbr($totheures * $tarif['th']),1,0,$tbinfo['heures']['align'],1);
	$pdf->Cell($tbinfo['km']['width']+$tbinfo['kmf']['width'],$lineheight,fnbr(($totkm * $tarif['tkm']) + $totkmforf),1,1,$tbinfo['km']['align'],1);	
	
	$pdf->Cell($temp,$lineheight,'Total',0,0,R,1);
	$pdf->SetFont('Helvetica','B',"14");
	$pdf->Cell($tbinfo['heures']['width'] + $tbinfo['km']['width'] + $tbinfo['kmf']['width'],$lineheight,fnbr(($totheures * $tarif['th']) + ($totkm * $tarif['tkm']) + $totkmforf).' €',1,0,$tbinfo['heures']['align'],1);
	
	$pdf->Output(Conf::read('Env.root').$path.$file, 'F');
	
	return "../../".$path.$file;
}
?>