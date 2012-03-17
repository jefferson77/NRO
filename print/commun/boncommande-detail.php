<?php
#########################################################################################################################################################################
### Page de bon de commande #############################################################################################################################################
#########################################################################################################################################################################
# -- moteur FPDF
require_once(NIVO."nro/fm.php");
require_once(NIVO.'classes/document.php');

# renvoie les path 'dossier', 'full', 'history', 'filename'
function path_bdc($idbdc) {
## declarations
    $fname = prezero($idbdc, 5);
    $lespath['dossier'] = Conf::read('Env.root').'document/boncommande/';
    $lespath['filename'] = $fname.'.pdf';
    $lespath['full'] = $lespath['dossier'].$lespath['filename'];
    $lespath['history'] = Conf::read('Env.root').'history/boncommande/';

    if (file_exists($lespath['full'])) $lespath['historyfull'] = $lespath['history'].$fname.'-'.date ("Ymd.Hi", filemtime($lespath['full'])).'.pdf';

## check et creation si besoin
    if(!is_dir($lespath['dossier'])) mkdir($lespath['dossier'], 0777, true); #le 'true' c'est pour le recursif
    if(!is_dir($lespath['history'])) mkdir($lespath['history'], 0777, true); 

    return $lespath;
}

# sauve un bon de commande et retourne le chemin du fichier
function print_bdc($idbdc) {
###### GEt infos ######
	global $DB;

	$bdc = $DB->getRow('SELECT * FROM bondecommande WHERE id='.$idbdc.' LIMIT 1');

	if ($bdc['id'] > 0) {
		if(!empty($bdc['id'])) {
			$infomission = $DB->getRow('SELECT b.*, a.nom, a.prenom, s.idsupplier, s.societe, s.tel, s.fax, s.email, s.adresse, s.cp, s.ville
			FROM bondecommande b
			LEFT JOIN supplier s ON s.idsupplier = b.idsupplier
			LEFT JOIN agent a ON b.idagent = a.idagent
			WHERE b.id = '.$bdc['id'].' LIMIT 1');
			$infomission['week'] = strftime('%W', strtotime($infomission['date']));
		}

		$path = path_bdc($bdc['id']);
		
		$ln = 'FR';
		$infp = '';

		$infp['employeur'] = 	'Exception2 scrl'."\n".'195 Avenue de la Chasse'."\n".'1040 Etterbeek'."\n".'TVA: BE 0 430 597 846';
		$infp['employeur2'] = 	$infomission['societe']." (".$infomission['idsupplier'].")"."\n";
		if(!empty($infomission['adresse'])) $infp['employeur2'] .= $infomission['adresse']."\n";
		if(!empty($infomission['cp'])) $infp['employeur2'] .= $infomission['cp'];
		if(!empty($infomission['ville'])) $infp['employeur2'] .= ' '.$infomission['ville']."\n";
		if(!empty($infomission['tel'])) $infp['employeur2'] .= 'Tel: '.$infomission['tel']."\n";
		if(!empty($infomission['fax'])) $infp['employeur2'] .= 'Fax: '.$infomission['fax']."\n";
		if(!empty($infomission['email'])) $infp['employeur2'] .= 'Email: '.$infomission['email'];
		if(empty($infomission['secteur'])) { 
			$infp['titre'] = 'Bon de commande Exception';
		} else {
			$infp['titre'] = 'Bon de commande Exception';
		}
		$infp['agent'] = 		'Commandé par : ';
		$infp['job'] = 			'Job : ';
		$infp['intitule'] = 	'Intitulé : ';
		$infp['description'] = 	'Description : ';
		$infp['dateachat'] = 	"Date : ";
		$infp['montantp'] = 	'Prix : ';
		$infp['refac'] = 		'Facturé au client : ';
		$infp['nfac'] = 		'Votre Numéro de facture/offre : ';
		$infp['nfacout'] = 		'Numéro de facture client : ';
		$infp['bruxelles'] = 	'Imprimé à Bruxelles le';

		#### Génération du PDF ###############################

		$pdf= new fpdi('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetFont('Helvetica','',12);
		$pdf->addPage();
		$pdf->SetAutoPageBreak(false);
		$pdf->Image($_SERVER["DOCUMENT_ROOT"]."/print/illus/logoPrint.png",15,30,50);
		$pdf->Cell(0,20,'',0,1);
		#info exception
		$pdf->Cell(120,6);
		$pdf->MultiCell(0,6,$infp['employeur2'],0,1);
		$pdf->Cell(0,20,'',0,1);

		#info bon de commande
		$pdf->SetFont('Helvetica','BU',16);
		$pdf->Cell(0,6,$infp['titre'],0,1,'C'); // titre
		$pdf->Cell(0,20,'',0,1);

		//change à partir d'ici
		$pdf->SetX(30);
		$pdf->SetFont('Times','I',14);
		$pdf->Cell(60,10,"Ce bon de commande est à joindre avec la facture",0,1,'C');
		$idzero = prezero($idbdc,4);
		$pdf->SetFont('Helvetica','B',12);
		$pdf->Cell(68,10,"Bon de commande n° COM".$idzero,1,2);
		if(!empty($infomission['secteur'])) {
			$intitules[] = $infp['agent'];
			$datas[] = $infomission['nom'].' '.$infomission['prenom'];
			$intitules[] = $infp['job'];
			$datas[] = $infomission['secteur'].' '.$infomission['idjob'];
		}
		$intitules[] = $infp['dateachat'];
		$datas[] = fdate($infomission['date']);
		$intitules[] = $infp['montantp'];
		$datas[] = fnbr($infomission['montant']).' Euros';
		$intitules[] = $infp['nfac'];
		$datas[] = $infomission['nfac'];
		$intitules[] = $infp['intitule'];
		$datas[] = $infomission['titre'];
		$intitules[] = $infp['description'];

		//PAS en bold
		$hauteur = $pdf->GetY();
		$pdf->SetFont('Helvetica','B',12);
		foreach($intitules as $intitule) {
			$pdf->Cell(60,10,$intitule,0,2);
		}
		//en bold
		$pdf->SetXY(100,$hauteur);
		$pdf->SetFont('Helvetica','',12);
		foreach($datas as $data) {
			$pdf->Cell(60,10,$data,0,2);
		}
		$hauteur = $pdf->GetY();
		$pdf->SetXY(100,$hauteur+2);
		$pdf->MultiCell(60,6,$infomission['description'],0,2);
		$pdf->SetFont('Helvetica','',8);
		$pdf->SetXY(160,270);
		$pdf->Cell(0,6,$infp['bruxelles'].' '.date("d/m/Y"),0,2,'R');
		$pdf->SetXY(25,280);
		$pdf->Line(10,277,200,277);
		$pdf->SetFont('Helvetica','',8);
		$pdf->Cell(0,3,'Exception - Exception scrl 195 Av. de la Chasse 1040 - Bruxelles',0,1,'C');
		$pdf->Cell(0,3,'TVA BE 430 597 846  RCB 489 589',0,1,'C');
		$pdf->Cell(0,3,'www.exception2.be Tel : 02/732.74.40 Fax : 02/732.79.38',0,1,'C');
		$pdf->Output($path['full'], 'F');
		return $path['full'];
	} else {
		echo 'ERREUR : aucun bon de commande n° "'.$idbdc.'" n\'existe';		
	}
}
?>