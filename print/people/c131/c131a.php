<?php
$path = "document/temp/people/c131a/";
$file = 'c131a-'.$_POST['idpeople'].'-'.date("Ymd").'.pdf';

#### Get infos people ###############################
$row = $DB->getRow("SELECT idpeople, pprenom, pnom, lbureau, nrnational FROM people WHERE idpeople = ".$_POST['idpeople']);

#Infos People
$infp['regnat'] = 	$row['nrnational'];
$infp['nom'] = 		$row['pnom'].' '.$row['pprenom'];

# Fixes
$infp['emplcategorie'] = 	'010';
$infp['numentreprise'] = 	'0430597846';
$infp['comparitaire'] = 	'21800';
$infp['numonss'] = 			'112651531';
$infp['maxhebdo'] = 		'3800';
$infp['nomemployeur'] = 	'Françoise Lannoo';
$infp['codetravailleur'] = 	'495';
$infp['x'] = 				'x';

# switch langue
$ln = $row['lbureau'];

switch ($ln) {
	case"FR":
		$pdfsource = 			NIVO.'print/people/c131/C131A-FR.pdf';
		$infp['employeur'] = 	'Exception2 scrl';
		$infp['empladresse'] = 	'195 Avenue de la Chasse - 1040 Etterbeek';
		$infp['noteT'] = 		'Travaille avec contrat journalier';
	break;
	case"NL":
		$pdfsource = 			NIVO.'print/people/c131/C131A-NL.pdf';
		$infp['employeur'] = 	'Exception2 bvba';
		$infp['empladresse'] = 	'Jachtlaan 195 - 1040 Etterbeek';
		$infp['noteT'] = 		'Dag Kontrakten'; # TODO a traduire en NL
	break;
	default:
	 	echo "erreur langue people";
}

#### Ouverture du PDF et import des pages template ###############################

$pdf = new fpdi('P', 'mm', 'A4', true, 'UTF-8', false);

$pagecount = $pdf->SetSourceFile($pdfsource);

$page1 = $pdf->ImportPage(1);

$pdf->SetAutoPageBreak(0);

#### Get data prestas ###############################
$merchs = $DB->getArray("SELECT idmerch, datem FROM merch WHERE idpeople = '".$_POST['idpeople']."' AND datem BETWEEN '".$dates['datein']."' AND '".$dates['dateout']."'");

foreach ($merchs as $me) {
	$infm = new coremerch($me['idmerch']);
	if (($infm->hprest >= Conf::read('Payement.maxheure')) and ($me['datem'] < '2010-02-01')) $infm->hprest -= 1; #># Heure de repas
	$c131a[date("Ym", strtotime($me['datem']))][weekfromdate($me['datem'])][date("N", strtotime($me['datem']))] += $infm->hprest;
}

ksort($c131a);

#################################################################################################################################
#### Generation du PDF ########################################################################################################
#############################################################################################################################
if(!empty($c131a)) {
	foreach ($c131a as $period => $GrilleT) {
		$pdf->SetFont('Courier','B',13);

		/*
			TODO : faut il les ajouter au prestas (probleme des heures a 150 et 200)
		*/

		# corrections manuelles aux salaires 
		# 
		# $mods = $liste->getArray("SELECT `date`, `modh` FROM `salaires".substr($period, 2, 4)."` WHERE `idpeople` = '".$_POST['idpeople']); 
		# foreach ($mods as $mod) {
		# 	$GrilleT[weekfromdate($mod['date'])][date("N", strtotime($mod['date']))] += $mod['modh'];
		# }

		ksort($GrilleT);
		$GrilleT = array_slice($GrilleT, 0, 4); # ne garde que les 4 première semaines

		foreach ($GrilleT as $sem => $lej) {
			$moy[$sem] = array_sum($lej);
		}

		$mth = explode(".", array_sum($moy) / 4);
		if (!isset($mth[1])) $mth[1] = 0; # pour éviter un warning si pas de decimals
		$dat['moyhebdo'] =  str_repeat('0', 2 - strlen($mth[0])).$mth[0].$mth[1].str_repeat('0', 2 - strlen($mth[1])); 

		$per = strtotime(substr($period, 0, 4)."-".substr($period, 4, 2)."-01");

		$dat['in'] = fdate(date("Y-m-01", $per));
		#################################################################################################################################
		#### Page 1 #######################################################################
		$pdf->addPage();

		# Signature illus
		$pdf->Image($_SERVER["DOCUMENT_ROOT"]."/print/illus/signature/20.jpg",120,210,50);
	
		# Cachet illus 
		$pdf->Image($_SERVER["DOCUMENT_ROOT"]."/print/illus/cachet.jpg",150,200,60);

		$pdf->useTemplate($page1,0,0);

		## People
		$FR = array(33.3, 57); $NL = array(33.3, 56.75); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 
			$pdf->Cell(4.6, 5, $infp['regnat']{0});
			$pdf->Cell(4.6, 5, $infp['regnat']{1});
			$pdf->Cell(4.6, 5, $infp['regnat']{2});
			$pdf->Cell(4.6, 5, $infp['regnat']{3});
			$pdf->Cell(4.6, 5, $infp['regnat']{4});
			$pdf->Cell(4.6, 5, $infp['regnat']{5});
			$pdf->Cell(4.6, 5, $infp['regnat']{6});
			$pdf->Cell(4.6, 5, $infp['regnat']{7});
			$pdf->Cell(4.6, 5, $infp['regnat']{8});
		$FR = array(77, 57); $NL = array(77, 56.75); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 
			$pdf->Cell(4.6, 5, $infp['regnat']{9});
			$pdf->Cell(4.6, 5, $infp['regnat']{10});
	
		$FR = array(95, 57); $NL = array(95, 56.75); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(110, 5, $infp['nom']);
	
		## Employeur
		$FR = array(34, 65.5); $NL = array(34, 65.75); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(80, 5, $infp['employeur']);

		$FR = array(124.25, 65); $NL = array(124.25, 66); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(4.6, 5, $infp['emplcategorie']{0});
			$pdf->Cell(4.6, 5, $infp['emplcategorie']{1});
			$pdf->Cell(4.6, 5, $infp['emplcategorie']{2});
	
		$FR = array(147, 65); $NL = array(146.75, 66); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(4.6, 5, $infp['numentreprise']{0});
			$pdf->Cell(4.6, 5, $infp['numentreprise']{1});
			$pdf->Cell(4.6, 5, $infp['numentreprise']{2});
			$pdf->Cell(4.6, 5, $infp['numentreprise']{3});
		$FR = array(167.5, 65); $NL = array(167.25, 66); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(4.6, 5, $infp['numentreprise']{4});
			$pdf->Cell(4.6, 5, $infp['numentreprise']{5});
			$pdf->Cell(4.6, 5, $infp['numentreprise']{6});
		$FR = array(183.50, 65); $NL = array(183.25, 66); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(4.6, 5, $infp['numentreprise']{7});
			$pdf->Cell(4.6, 5, $infp['numentreprise']{8});
			$pdf->Cell(4.6, 5, $infp['numentreprise']{9});
	
		$FR = array(121.5, 74); $NL = array(121.5, 74); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(4.6, 5, $infp['comparitaire']{0});
			$pdf->Cell(4.6, 5, $infp['comparitaire']{1});
			$pdf->Cell(4.6, 5, $infp['comparitaire']{2});
		$FR = array(137, 74); $NL = array(137, 74); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(4.6, 5, $infp['comparitaire']{3});
			$pdf->Cell(4.6, 5, $infp['comparitaire']{4});
	
		$FR = array(153.5, 74); $NL = array(153.5, 74); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(4.6, 5, $infp['numonss']{0});
			$pdf->Cell(4.6, 5, $infp['numonss']{1});
			$pdf->Cell(4.6, 5, $infp['numonss']{2});
			$pdf->Cell(4.6, 5, $infp['numonss']{3});
			$pdf->Cell(4.6, 5, $infp['numonss']{4});
			$pdf->Cell(4.6, 5, $infp['numonss']{5});
			$pdf->Cell(4.6, 5, $infp['numonss']{6});
		$FR = array(187.5, 74); $NL = array(187.5, 74); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(4.6, 5, $infp['numonss']{7});
			$pdf->Cell(4.6, 5, $infp['numonss']{8});
	
	
		$FR = array(12, 82.5); $NL = array(12, 82.75); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(45, 5, $infp['empladresse']);
	
		## Dates
		$FR = array(90.5, 91); $NL = array(90.5, 90.75); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(4.6, 5, $dat['in']{0});
			$pdf->Cell(4.6, 5, $dat['in']{1});
		$FR = array(101.75, 91); $NL = array(101.75, 90.75); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(4.6, 5, $dat['in']{3});
			$pdf->Cell(4.6, 5, $dat['in']{4});
		$FR = array(113.25, 91); $NL = array(113.25, 90.75); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(4.6, 5, $dat['in']{6});
			$pdf->Cell(4.6, 5, $dat['in']{7});
			$pdf->Cell(4.6, 5, $dat['in']{8});
			$pdf->Cell(4.6, 5, $dat['in']{9});

		## Code Travailleur
		$FR = array(71.5, 96.5); $NL = array(71.25, 96.25); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(4.6, 5, $infp['codetravailleur']{0});
			$pdf->Cell(4.6, 5, $infp['codetravailleur']{1});
			$pdf->Cell(4.6, 5, $infp['codetravailleur']{2});
		
		## duree hebdomadaire
		$FR = array(21, 116); $NL = array(21, 119); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(6.25, 5, $dat['moyhebdo']{0});
			$pdf->Cell(6.25, 5, $dat['moyhebdo']{1});
		$FR = array(36, 116); $NL = array(36, 119); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(6.25, 5, $dat['moyhebdo']{2});
			$pdf->Cell(6.25, 5, $dat['moyhebdo']{3});
	
		$FR = array(21, 134.5); $NL = array(21, 135.5); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(6.25, 5, $infp['maxhebdo']{0});
			$pdf->Cell(6.25, 5, $infp['maxhebdo']{1});
		$FR = array(36, 134.5); $NL = array(36, 135.5); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(6.25, 5, $infp['maxhebdo']{2});
			$pdf->Cell(6.25, 5, $infp['maxhebdo']{3});
		
		$FR = array(80, 150); $NL = array(95, 151); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(45, 5, $infp['noteT']);
		$FR = array(41.75, 155.75); $NL = array(56.75, 156.75); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(5, 5, $infp['x']); # a l'adresse de l'employeur
		$FR = array(10, 168.75); $NL = array(10.25, 170.25); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(5, 5, $infp['x']); # occupation a temps partiel

		$s = explode(".", salaire($row['idpeople'], fdatebk($dat['in'])));
	
		if ($s[0] < 10) {
			$FR = array(67.25, 192.5); $NL = array(71.75, 193.75); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
				$pdf->Cell(4.6, 5, $s[0]{0});
		} else {
			$FR = array(62.5, 192); $NL = array(66.75, 193.75); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
				$pdf->Cell(4.6, 5, $s[0]{0});
				$pdf->Cell(4.6, 5, $s[0]{1});
		}
	
		$FR = array(74.25, 192.5); $NL = array(79, 193.75); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); 	
			$pdf->Cell(4.6, 5, $s[1]{0});
			$pdf->Cell(4.6, 5, $s[1]{1});
			$pdf->Cell(4.6, 5, $s[1]{2});
			$pdf->Cell(4.6, 5, $s[1]{3});
	
		$FR = array(102.75, 193.25); $NL = array(106.75, 194); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(5, 5, $infp['x']); # uur
	
		## Grille T
		$pdf->SetFont('Courier','B',10);
		$startX = 119.5;
		if ($ln == 'FR') { $startY = 125.5; } else { $startY = 126; }
	
		$tX = 10.25;
		$tY = 7.9;
	
		$sem = 0;
		foreach ($GrilleT as $jours) {
			foreach ($jours as $j => $time) {
				$pdf->SetXY($startX + ($tX * ($j - 1)), $startY + ($tY * ($sem - 1))); $pdf->Cell($tX, $tY, fnbr0($time),0,0,'C');
			}
			$sem++;
		}
		unset($moy);
	}

	$pdf->Output(Conf::read('Env.root').$path.$file, 'F');

	$docparpeople[$_POST['idpeople']][] = Conf::read('Env.root').$path.$file;
}

## display
if (count($docparpeople) > 0) {
	echo '<div id="centerzonelarge" align="center">';
	generateSendTable($docparpeople, 'people', 'temp/P131', 'P131', "C131a");
	echo '</div>';
} else {
	$warning[] = "Aucune Mission merch dans cette période";
	include 'v-searchForm.php';
}
?>