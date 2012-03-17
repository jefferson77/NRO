<?php
#########################################################################################################################################################################
### Page de note de frais ###############################################################################################################################################
#########################################################################################################################################################################
# -- moteur FPDF
require_once(NIVO."nro/fm.php");
require_once(NIVO.'classes/document.php');

# renvoie les path 'dossier', 'full', 'history', 'filename'
function path_notefrais($idnfrais) {
## declarations
    $fname = prezero($idnfrais, 5);

    $lespath['dossier'] = Conf::read('Env.root').'document/notefrais/';
    $lespath['filename'] = $fname.'.pdf';
    $lespath['full'] = $lespath['dossier'].$lespath['filename'];
    $lespath['history'] = Conf::read('Env.root').'history/notefrais/';

    if (file_exists($lespath['full'])) $lespath['historyfull'] = $lespath['history'].$fname.'-'.date ("Ymd.Hi", filemtime($lespath['full'])).'.pdf';

## check et creation si besoin
    if(!is_dir($lespath['dossier'])) mkdir($lespath['dossier'], 0777, true); #le 'true' c'est pour le recursif
    if(!is_dir($lespath['history'])) mkdir($lespath['history'], 0777, true); 

    return $lespath;
}

# sauve une note de frais et retourne le chemin du fichier
function print_notefrais($idnfrais) {

###### GEt infos ######
	global $DB;

	$notefrais = $DB->getRow('SELECT * FROM notefrais WHERE idnfrais='.$idnfrais.' LIMIT 1');

	if ($notefrais['idnfrais'] > 0) { # Verifie si la note de frais existe bien

			#Si mission, get info people
			if(!empty($notefrais['idmission'])) {
				switch ($notefrais['secteur']) {
					case"VI":
						$infomission = $DB->getRow('SELECT v.idvipjob, v.idpeople, v.vipdate AS date, s.societe AS shop, j.idclient, client.societe AS client
													FROM vipmission v
													LEFT JOIN shop s ON s.idshop = v.idshop
													LEFT JOIN vipjob j ON j.idvipjob = v.idvipjob
													LEFT JOIN client ON client.idclient = j.idclient
													WHERE v.idvip = '.$notefrais['idmission'].' LIMIT 1');
						$infomission['week'] = strftime('%W', strtotime($infomission['date']));
					break;
					case"ME":
						$infomission = $DB->getRow('SELECT me.idpeople, me.datem AS date, me.weekm AS week, s.societe AS shop, c.idclient, c.societe AS client
													FROM merch me 
													LEFT JOIN shop s ON me.idshop = s.idshop
													LEFT JOIN client c ON c.idclient = me.idclient
													WHERE me.idmerch = '.$notefrais['idmission'].' LIMIT 1');
					break;
					case"AN":
						$infomission = $DB->getRow('SELECT an.idanimjob, an.idpeople, an.datem AS date, an.weekm AS week, shop.societe AS shop, client.idclient, client.societe AS client
													FROM animation an
													LEFT JOIN shop ON shop.idshop = an.idshop
													LEFT JOIN animjob ON animjob.idanimjob = an.idanimjob
													LEFT JOIN client ON client.idclient = animjob.idclient
													WHERE an.idanimation = '.$notefrais['idmission'].' LIMIT 1');
					break;
				}
				if($infomission['idpeople']>0) $infopeople = $DB->getRow('SELECT * FROM people WHERE idpeople='.$infomission['idpeople'].' LIMIT 1');
				
				
				if(!empty($infopeople['lbureau'])) $ln = $infopeople['lbureau'];	
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

		$path = path_notefrais($notefrais['idnfrais']);
		if (hashcheck(implode("|",$notefrais).implode("|", $infomission).implode("|", $infopeople), $path['full']) == 'new') { # verifie si les données du PDF ont changé, si oui, recree un PDF, si non renvoie l'ancien

			if (file_exists($path['full'])) rename($path['full'], $path['historyfull']);
		
			$ln = 'FR';
			$infp = '';

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
					$infp['montantp'] = 'Paye';
					$infp['disclaimer'] = "Tickets originaux à envoyer agrafés à l'arrière de la note de frais."."\n"."Sans les tickets originaux les notes de frais ne sont pas remboursées.";
					$infp['bruxelles'] = 'Bruxelles';
				break;
				case 'NL':
					$infp['employeur'] = 	'Exception2 bvba'."\n".'Jachtlaan 195'."\n".'1040 Etterbeek'."\n".'TVA: BE 0 430 597 846';
					$infp['nomprenom'] = 'Naam + voornaam';
					$infp['adresse'] = 'Adresse';
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
					$infp['montantp'] = 'Betaald';
					$infp['disclaimer'] = "De originele tiketten moeten op de achterkant van uw onkostennota geniet worden."."\n"."Zonder tiketten worden de onkostennota's niet terugbetaald.";
					$infp['bruxelles'] = 'Brussel';
				break;
				default:
					echo "erreur langue people";
			}
			
			#### Génération du PDF ###############################
			
			$pdf= new fpdi('P', 'mm', 'A4', true, 'UTF-8', false);
			$pdf->SetFont('Helvetica','',12);
			$pdf->addPage();
			
			#info people
			if(!empty($notefrais['idmission'])) {
				$pdf->Cell(40,6,$infp['nomprenom'].' : '.$infopeople['pnom'].' '.$infopeople['pprenom'],0,1);
				$pdf->Cell(40,6,$infp['adresse'].' : '.$infopeople['adresse1'].' '.$infopeople['num1'].' - '.$infopeople['cp1'].' '.$infopeople['ville1'],0,1);
				$pdf->Cell(0,6,'Code People : '.$infopeople['codepeople'],0,1);
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
			
			$pdf->SetFont('Helvetica','B',12);
			$pdf->Cell(40,6,"Job : ");
			$pdf->SetFont('Helvetica','',12);
			$pdf->Cell(0,6,$notefrais['idjob'],0,1);
			$pdf->SetFont('Helvetica','B',12);
			$pdf->Cell(40,6,"Mission : ");
			$pdf->SetFont('Helvetica','',12);
			$pdf->Cell(0,6,$notefrais['secteur'].' '.$notefrais['idmission'],0,1);
			
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
			$pdf->Cell(40,6,$infp['dateachat']." : ");
			$pdf->SetFont('Helvetica','',12);
			$pdf->Cell(0,6,$infomission['dateachat'],0,1);
			$pdf->SetFont('Helvetica','B',12);
			$pdf->Cell(40,6,$infp['shop']." : ");
			$pdf->SetFont('Helvetica','',12);
			$pdf->Cell(0,6,$infomission['shop'],0,1);
			$pdf->SetFont('Helvetica','B',12);
			$pdf->Cell(40,6,$infp['montantp'].' : ');
			$pdf->SetFont('Helvetica','',12);
			if ($notefrais['montantpaye'] > 0) {
				$pdf->Cell(0,6,fnbr($notefrais['montantpaye']).' Euros',0,1);
			} else {
				$pdf->Cell(0,6,'........ Euros',0,1);
			}
			
			$pdf->Cell(0,20,'',0,1);
			$pdf->MultiCell(0,6,$infp['disclaimer'],0,1);
			
			$pdf->Cell(0,20,'',0,1);
			$pdf->Cell(0,6,$infp['bruxelles'].', '.date("d/m/Y"),0,1,'R');
			$pdf->Output($path['full'], 'F');
		}		
		return $path['full'];
	} else {
		echo 'ERREUR : aucune note de frais n° "'.$idnfrais.'" n\'existe';		
	}
}
?>