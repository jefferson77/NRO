<?php
define('NIVO', '../../');

#Vars
if(isset($_GET['idjob'])) $idjob = $_GET['idjob'];
else $erreur = "Pas d'id de job.";
if(isset($_GET['secteur'])) $secteur = $_GET['secteur'];
else $erreur =  "Pas de secteur.";
$ln='FR';
$infp='';

$path = "document/notefrais/";
$file = 'notefraisjob-'.$idjob.'-'.date("Ymd").'.pdf';

# Classes 
require_once(NIVO."nro/fm.php");
require_once(NIVO.'classes/document.php');

$pdf= new fpdi('P', 'mm', 'A4', true, 'UTF-8', false);

#Recup toutes les notes de frais
$notefraislist = $DB->getArray('SELECT * FROM notefrais WHERE idjob='.$idjob);
foreach($notefraislist as $notefrais) {
	#Si mission, get info people
	if(!empty($notefrais['idmission'])) {
		if($secteur=='VI') {
			$infomission = $DB->getRow('SELECT vipmission.idpeople, vipmission.vipdate AS date, shop.societe AS shop, vipjob.idclient, client.societe AS client
										FROM vipmission 
										LEFT JOIN shop ON shop.idshop=vipmission.idshop
										LEFT JOIN vipjob ON vipjob.idvipjob=vipmission.idvipjob
										LEFT JOIN client ON client.idclient=vipjob.idclient
										WHERE vipmission.idvip='.$notefrais['idmission'].' LIMIT 1');
			$infomission['week'] = strftime('%W', strtotime($infomission['date']));
		} else if($secteur=='AN') {
			$infomission = $DB->getRow('SELECT animation.idpeople, animation.datem AS date, animation.weekm AS week, shop.societe AS shop, client.idclient, client.societe AS client
										FROM animation 
										LEFT JOIN shop ON shop.idshop=vipmission.idshop
										LEFT JOIN vipjob ON vipjob.idvipjob='.$notefrais['idjob'].'
										LEFT JOIN client ON client.idclient=animation.idclient
										WHERE idanimation='.$notefrais['idjob'].' LIMIT 1');
		}
		$infopeople = $DB->getRow('SELECT * FROM people WHERE idpeople='.$infomission['idpeople'].' LIMIT 1');
		if(!empty($infopeople['lbureau'])) $ln=$infopeople['lbureau'];	
	} else {
	# sinon get info job
		if($secteur=='VI') { $table = 'vipjob'; $field = 'idvipjob'; }
		else if($secteur=='AN') { $table = 'animjob'; $field='idanimjob'; }
		else if($secteur=='ME') { $table = 'merch'; $field='idmerch'; }
		$infomission = $DB->getRow('SELECT '.$table.'.idshop, shop.societe AS shop, client.idclient, client.societe AS client
							FROM '.$table.' 
							LEFT JOIN shop ON '.$table.'.idshop=shop.idshop
							LEFT JOIN client ON '.$table.'.idclient=client.idclient
							WHERE '.$field.'='.$notefrais['idjob'].' LIMIT 1');
	}
	
	
	# switch langue
	switch ($ln) {
		case 'FR':
			$infp['employeur'] = 	'Exception2 scrl'."\n".'195 Avenue de la Chasse'."\n".'1040 Etterbeek'."\n".'TVA: BE 0 430 597 846';
			$infp['nomprenom'] = 'Nom & prénom';
			$infp['adresse'] = 'Adresse';
			$infp['bank'] = 'N° compte en banque';
			$infp['titre'] = 'Note de frais';
			$infp['week'] = 'Semaine';
			$infp['date'] = 'Date';
			$infp['idclient'] = 'Client ID';
			$infp['client'] = 'Client';
			$infp['intitule'] = 'Intitulé';
			$infp['description'] = 'Description';
			$infp['dateachat'] = 'Achat du';
			$infp['shop'] = 'Magasin';
			$infp['montant'] = 'Frais engagés';
			$infp['disclaimer'] = "Tickets originaux à envoyer agrafés à l'arrière de la note de frais."."\n"."Sans les tickets originaux les notes de frais ne sont pas remboursées.";
			$infp['bruxelles'] = 'Bruxelles';
		break;
		case 'NL':
			$infp['employeur'] = 	'Exception2 bvba'."\n".'Jachtlaan 195'."\n".'1040 Etterbeek'."\n".'TVA: BE 0 430 597 846';
			$infp['nomprenom'] = 'Naam + voornaam';
			$infp['adresse'] = 'Adres';
			$infp['bank'] = 'Bankrekeningnummer';
			$infp['titre'] = 'Onkostennota';
			$infp['week'] = 'Week';
			$infp['date'] = 'Datum';
			$infp['idclient'] = 'Klant ID';
			$infp['client'] = 'Klant';
			$infp['intitule'] = 'Titel';
			$infp['description'] = 'Beschrijving';
			$infp['dateachat'] = 'Aankoopdatum';
			$infp['shop'] = 'Winkel';
			$infp['montant'] = 'Onkosten';
			$infp['disclaimer'] = "De originele tiketten moeten op de achterkant van uw onkostennota geniet worden."."\n"."Zonder tiketten worden de onkostennota's niet terugbetaald."."\n"."Originele ticket geniet aan de achterkant van de onkostennota.";
			$infp['bruxelles'] = 'Brussel';
		break;
		default:
			echo "erreur langue people";
	}
	
	#### Génération du PDF ###############################
	
	$pdf->addPage();
	#info people
	$pdf->SetFont('Helvetica','',12);
	if(!empty($notefrais['idmission'])) {
		$pdf->Cell(40,6,$infp['nomprenom'].' : '.$infopeople['pnom'].' '.$infopeople['pprenom'],0,1);
		$pdf->Cell(40,6,$infp['adresse'].' : '.$infopeople['adresse1'].' '.$infopeople['num1'].' - '.$infopeople['cp1'].' '.$infopeople['ville1'],0,1);
		$pdf->Cell(0,6,'N° ID : '.$infopeople['idpeople'],0,1);
		$pdf->Cell(40,6,$infp['bank'].' : '.$infopeople['banque'],0,1);
	}
	$pdf->Cell(0,20,'',0,1);
	#info exception
	$pdf->Cell(120,6);
	$pdf->MultiCell(0,6,$infp['employeur'],0,1);
	$pdf->Cell(0,20,'',0,1);
	
	#info note de frais
	$pdf->SetFont('Helvetica','BU',14);
	$pdf->Cell(0,6,$infp['titre'],0,1,'C');
	$pdf->Cell(0,20,'',0,1);
	
	if(!empty($notefrais['idmission'])) {
		$pdf->SetFont('Helvetica','B',12);
		$pdf->Cell(40,6,$infp['week']." : ");
		$pdf->SetFont('Helvetica','',12);
		$pdf->Cell(0,6,$infomission['week'],0,1);
		$pdf->SetFont('Helvetica','B',12);
		$pdf->Cell(40,6,$infp['date']." : ");
		$pdf->SetFont('Helvetica','',12);
		$pdf->Cell(0,6,fdate($infomission['date']),0,1);
	}
	$pdf->SetFont('Helvetica','B',12);
	$pdf->Cell(40,6,$infp['idclient']." : ");
	$pdf->SetFont('Helvetica','',12);
	$pdf->Cell(0,6,$infomission['idclient'],0,1);
	$pdf->SetFont('Helvetica','B',12);
	$pdf->Cell(40,6,$infp['client']." : ");
	$pdf->SetFont('Helvetica','',12);
	$pdf->Cell(0,6,$infomission['client'],0,1);
	$pdf->SetFont('Helvetica','B',12);
	$pdf->Cell(40,6,$infp['intitule']." : ");
	$pdf->SetFont('Helvetica','',12);
	$pdf->Cell(0,6,$notefrais['intitule'],0,1);
	$pdf->SetFont('Helvetica','B',12);
	$pdf->Cell(40,6,$infp['description']." : ");
	$pdf->SetFont('Helvetica','',12);
	$pdf->Cell(0,6,$notefrais['description'],0,1);
	$pdf->SetFont('Helvetica','B',12);
	$pdf->Cell(40,6,$infp['shop']." : ");
	$pdf->SetFont('Helvetica','',12);
	$pdf->Cell(0,6,$infomission['shop'],0,1);
	$pdf->SetFont('Helvetica','B',12);
	$pdf->Cell(40,6,$infp['montant'].' : ');
	$pdf->SetFont('Helvetica','',12);
	$pdf->Cell(0,6,$notefrais['montantpaye'],0,1);
	
	$pdf->Cell(0,20,'',0,1);
	$pdf->MultiCell(0,6,$infp['disclaimer'],0,1);
	
	$pdf->Cell(0,20,'',0,1);
	$pdf->Cell(0,6,$infp['bruxelles'].', '.date("d/m/Y"),0,1,'R');
}
$pdf->Output(Conf::read('Env.root').$path.$file, 'F');

?>
<div align="center">
<br><br>
<img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0">


<A href="<?php echo NIVO.$path.$file ;?>" target="_blank">Imprimer toutes les notes de frais</A>
</div>