<?php
#########################################################################################################################################################################
### Page de bon de commande #############################################################################################################################################
#########################################################################################################################################################################
# -- moteur FPDF
require_once(NIVO."nro/fm.php");
require_once(NIVO.'classes/document.php');
require_once(NIVO.'classes/vip.php');
require_once(NIVO.'classes/anim.php');

# renvoie les path 'dossier', 'full', 'history', 'filename'
function path_bdc($idmission, $secteur)
{
## declarations
    $fname = $secteur.'-'.$idmission;
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
function print_bdc($idmission, $secteur) {
###### GEt infos ######
	global $DB;

	switch($secteur)
	{
		case "VI":
			$infomission = $DB->getRow('SELECT 
										v.vipdate as date, v.idshop, v.idvip, v.vipin, v.vipout,
										a.nom, a.prenom,
										s.idsupplier, s.societe, s.tel, s.fax, s.email, s.adresse, s.cp, s.ville,
										sh.societe as shop
		       							FROM vipmission v
										LEFT JOIN vipjob vj ON v.idvipjob = vj.idvipjob
										LEFT JOIN agent a ON vj.idagent = a.idagent
										LEFT JOIN people p ON v.idpeople = p.idpeople
										LEFT JOIN supplier s ON s.idsupplier = p.idsupplier
										LEFT JOIN shop sh ON v.idshop = sh.idshop
		        						WHERE v.idvip = '.$idmission.' LIMIT 1');
									
			$vip = new corevip($idmission);
			$infocore['heures'] = $vip->hprest;
			$infocore['km'] = $vip->kipay;
		
		break;
		
		case "AN":
		
		$infomission = $DB->getRow('SELECT 
									a.datem as date, a.idshop, a.idanimation, 
									ag.nom, ag.prenom,
									s.idsupplier, s.societe, s.tel, s.fax, s.email, s.adresse, s.cp, s.ville,
									sh.societe as shop
	       							FROM animation a
									LEFT JOIN animjob aj ON a.idanimjob = aj.idanimjob
									LEFT JOIN agent ag ON aj.idagent = ag.idagent
									LEFT JOIN people p ON a.idpeople = p.idpeople
									LEFT JOIN supplier s ON s.idsupplier = p.idsupplier
									LEFT JOIN shop sh ON a.idshop = sh.idshop
	        						WHERE a.idanimation = '.$idmission.' LIMIT 1');
								
		$anim = new coreanim($idmission);
		$infocore['heures'] = $anim->hprest;
		$infocore['km'] = $anim->kipay;
		
		break;
		
		case 'ME':
			$infomission = $DB->getRow('SELECT 
										m.datem as date, m.idshop, m.idmerch, 
										ag.nom, ag.prenom,
										s.idsupplier, s.societe, s.tel, s.fax, s.email, s.adresse, s.cp, s.ville,
										sh.societe as shop
		       							FROM merch m
										LEFT JOIN agent ag ON m.idagent = ag.idagent
										LEFT JOIN people p ON m.idpeople = p.idpeople
										LEFT JOIN supplier s ON s.idsupplier = p.idsupplier
										LEFT JOIN shop sh ON m.idshop = sh.idshop
		        						WHERE m.idmerch = '.$idmission.' LIMIT 1');

			$merch = new coremerch($idmission);
			$infocore['heures'] = $merch->hprest;
			$infocore['km'] = $merch->kipay;
		break;		
	}
    

		$path = path_bdc($idmission, $secteur);
		
			$ln = 'FR';
			$infp = '';

			$infp['employeur'] = 	'Exception2 scrl'."\n".'195 Avenue de la Chasse'."\n".'1040 Etterbeek'."\n".'TVA: BE 0 430 597 846';
            $infp['employeur2'] = 	$infomission['societe']." (".$infomission['idsupplier'].")"."\n";
            if(!empty($infomission['adresse']))
            {
                $infp['employeur2'] .= $infomission['adresse']."\n";
            }
            if(!empty($infomission['cp']))
            {
                $infp['employeur2'] .= $infomission['cp'];
            }
            if(!empty($infomission['ville']))
            {
                $infp['employeur2'] .= ' '.$infomission['ville']."\n";
            }
            if(!empty($infomission['tel']))
            {
                $infp['employeur2'] .= 'Tel: '.$infomission['tel']."\n";
            }
            if(!empty($infomission['fax']))
            {
                $infp['employeur2'] .= 'Fax: '.$infomission['fax']."\n";
            }
            if(!empty($infomission['email']))
            {
                $infp['employeur2'] .= 'Email: '.$infomission['email'];
            }              
			if(empty($infomission['secteur'])) //  C'est un bon de commande interne
			{
				$infp['titre'] = 'Bon de commande Exception';
			}
			else
			{
				$infp['titre'] = 'Bon de commande Exception';
			}
			$infp['agent'] = 		'Commandé par : ';
			$infp['job'] = 			'Mission : ';
			$infp['dateprest'] = 	"Date de prestation : ";
			$infp['hprest'] = 		'Heures payées : ';
			$infp['kmpay'] = 		'Kilomètres payés : ';
			$infp['lieu'] = 		'Lieu de prestation : ';
			$infp['horaire'] = 		'Horaire : ';
			$infp['nfac'] = 		'Votre Numéro de facture/offre : ';
			
			$infp['bruxelles'] = 	'Imprimé à Bruxelles le';
			
			#### Génération du PDF ###############################
			
			$pdf= new fpdi('P', 'mm', 'A4', true, 'UTF-8', false);
			$pdf->SetFont('Helvetica','',12);
			$pdf->addPage();
			$pdf->SetAutoPageBreak(false);
            $pdf->Image($_SERVER["DOCUMENT_ROOT"]."/print/illus/logoPrint.png", 15, 30, 50);
			$pdf->Cell(0,20,'',0,1);
			#info exception
			$pdf->Cell(120,6);
			$pdf->MultiCell(0,6,$infp['employeur2'],0,1);
			$pdf->Cell(0,20,'',0,1);
			
			#info bon de commande
			$pdf->SetFont('Helvetica','BU',16);
			$pdf->Cell(0,6,$infp['titre'],0,1,'C'); // titre
			$pdf->Cell(0,20,'',0,1);
			
			$pdf->SetX(30);
			$pdf->SetFont('Times','I',14);
			$pdf->Cell(60,10,"Ce bon de commande est à joindre avec la facture",0,1,'C');
			$idzero = prezero($idbdc,4);
			$pdf->SetFont('Helvetica','B',12);

			$intitules[] = $infp['agent']; 
			$datas[] = $infomission['nom'].' '.$infomission['prenom']; //concat nom prénom agent
			$intitules[] = $infp['job'];
			$datas[] = $secteur.' '.$idmission; // concat secteur idjob
			$intitules[] = $infp['dateprest'];
			$datas[] = fdate($infomission['date']); // date formatée
			$intitules[] = $infp['hprest'];
			$datas[] = fnbr($infocore['heures']).' Heures'; //prix euros
			$intitules[] = $infp['kmpay'];
			$datas[] = fnbr($infocore['km']).' Kilomètres'; //prix euros
			$intitules[] = $infp['lieu'];
			$datas[] = $infomission['shop']; // numéro de facture
			if($secteur =='VI')
			{
				$intitules[] = $infp['horaire'];
				$datas[] = 'De '.$infomission['vipin'].' à '.$infomission['vipout']; // titre
			}
			$intitules[] = $infp['nfac'];
			$datas[] = ''; // titre
			
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
}
?>