<?php
#########################################################################################################################################################################
### Page de bon de commande #############################################################################################################################################
#########################################################################################################################################################################
# -- moteur FPDF
require_once(NIVO."nro/fm.php");
require_once(NIVO.'classes/document.php');

# renvoie les path 'dossier', 'full', 'history', 'filename'
function path_bdl($idbdl)
{
## declarations
    $fname = prezero($idbdl, 5);
    $lespath['dossier'] = Conf::read('Env.root').'document/bonlivraison/';
    $lespath['filename'] = $fname.'.pdf';
    $lespath['full'] = $lespath['dossier'].$lespath['filename'];
    $lespath['history'] = Conf::read('Env.root').'history/bonlivraison/';

    if (file_exists($lespath['full'])) $lespath['historyfull'] = $lespath['history'].$fname.'-'.date ("Ymd.Hi", filemtime($lespath['full'])).'.pdf';

## check et creation si besoin
    if(!is_dir($lespath['dossier'])) mkdir($lespath['dossier'], 0777, true); #le 'true' c'est pour le recursif
    if(!is_dir($lespath['history'])) mkdir($lespath['history'], 0777, true); 

    return $lespath;
}

# sauve un bon de commande et retourne le chemin du fichier
function print_bdl($idbdl) {
###### GEt infos ######
	global $DB; 
	
	$bdl = $DB->getRow('SELECT * FROM bonlivraison WHERE id='.$idbdl.' LIMIT 1');

	if ($bdl['id'] > 0) {
		if(!empty($bdl['id'])) {
			$infomission = $DB->getRow('SELECT b.*, a.nom, a.prenom, s.idsupplier, s.societe, s.tel, s.fax, s.email, s.adresse, s.cp, s.ville
			FROM bonlivraison b
			LEFT JOIN supplier s ON s.idsupplier = b.idsupplier
			LEFT JOIN agent a ON b.idagent = a.idagent
			WHERE b.id = '.$bdl['id'].' LIMIT 1');
			$infomission['week'] = strftime('%W', strtotime($infomission['date']));
		}

		$path = path_bdl($bdl['id']);
		
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
				$infp['titre'] = 'Bon de livraison '.$infomission['ltype'];
			}
			else
			{
				$infp['titre'] = 'Bon de livraison '.$infomission['ltype'];
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
			$pdf->SetFont(Helvetica,'',24);
			$pdf->Cell(0,6,$infp['titre'],0,1,'C'); // titre
			$pdf->Cell(0,20,'',0,1);
			
			//change à partir d'ici
			$pdf->SetX(30);
			$pdf->SetFont('Times','I',14);
			$pdf->Cell(60,10,"Ce bon de livraison est à joindre avec la facture",0,1,'C');
			//$pdf->Line(105,131,105,181);
			$idzero = prezero($idbdl,4);
			$pdf->SetFont('Helvetica','B',12);
			$pdf->Cell(61,10,"Bon de livraison n° LIV".$idzero,1,2);
			$pdf->Cell(2,5,"",0,2);
			$pdf->setXY(13,131);
			
			// adresse d'enlèvement câdre gauche
			$pdf->SetFont('Helvetica','B',12);
			$pdf->Cell(0,10,"Adresse d'enlèvement : ",0,2);
			$pdf->SetFont('Helvetica','',16);
			$pdf->Cell(0,10,$infomission['fnom'],0,2);
			$pdf->Cell(0,10,$infomission['fadr'],0,2);
			$pdf->Cell(0,10,$infomission['fcp'].' '.$infomission['fville'],0,2);
			$pdf->Cell(0,10,$infomission['fgsm'],0,2);
			//$pdf->Cell(0,10,"",0,2);
			//descriptif cadre gauche
			
			//date cadre gauche
			
			$pdf->SetFont('Helvetica','B',12);
			$pdf->Cell(15,10,"Date : ",0,0);
			$pdf->SetFont('Helvetica','',10);
			$pdf->Cell(15,10,fdate($infomission['fdate']),0,2);
			
			$pdf->setX(13);
			$pdf->SetFont('Helvetica','B',12);
			$pdf->Cell(0,10,"Descriptif : ",0,2);
			$pdf->SetFont('Helvetica','',10);
			$pdf->MultiCell(90,6,$infomission['detail'],0,2);
			
			$haut = $pdf->getY() - 111;
			$pdf->setXY(10,131);
			$pdf->Cell(190,$haut,"",1,2);
			$pdf->Line(105,131,105,131+$haut);
			
			//adresse de dépot câdre droit
			$pdf->setXY(110,131);
			$pdf->SetFont('Helvetica','B',12);
			$pdf->Cell(0,10,"Adresse de dépot : ",0,2);
			$pdf->SetFont('Helvetica','',16);
			$pdf->Cell(0,10,$infomission['tnom'],0,2);
			$pdf->Cell(0,10,$infomission['tadr'],0,2);
			$pdf->Cell(0,10,$infomission['tcp'].' '.$infomission['tville'],0,2);
			$pdf->Cell(0,10,$infomission['tgsm'],0,2);
			//$pdf->Cell(0,10,"",0,2);
			//date cadre droit
			$pdf->SetFont('Helvetica','B',12);
			$pdf->Cell(15,10,"Date : ",0,0);
			$pdf->SetFont('Helvetica','',10);
			$pdf->Cell(15,10,fdate($infomission['tdate']),0,2);
			
			//détails
			$pdf->Line(105,180,200,180);
			$pdf->setXY(110,190);
			$pdf->SetFont('Helvetica','B',12);
			$pdf->Cell(15,10,"Agent :  ",0,0);
			
			$pdf->SetFont('Helvetica','',10);
			$pdf->Cell(15,10,' '.$infomission['prenom'].' '.$infomission['nom'],0,2);
			
			$pdf->setX(110);
			$pdf->SetFont('Helvetica','B',12);
			$pdf->Cell(23,10,"Référence :  ",0,0);
			$pdf->SetFont('Helvetica','',10);
			$pdf->Cell(15,10,' '.$infomission['secteur'].' '.$infomission['idjob'],0,2);
			
			$pdf->setX(110);
			$pdf->SetFont('Helvetica','B',12);
			$pdf->Cell(40,10,"Bon de commande : ",0,0);
			$pdf->SetFont('Helvetica','',10);
			$pdf->setX(153);
			$pdf->Cell(15,10,'COM'.prezero($infomission['nbdc'],4),0,2);
			
			//prix en dessous à droite du cadre
			$pdf->setXY(160,131+$haut);
			$pdf->SetFont('Helvetica','B',12);
			$pdf->Cell(10,10,"Prix : ",0,0);
			$pdf->SetFont('Helvetica','',10);
			$pdf->Cell(15,10,$infomission['prix'],0,0,'R');
			$pdf->SetFont('Helvetica','B',12);
			$pdf->Cell(10,10,"  Euros",0,0);
			
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
		echo 'ERREUR : aucun bon de commande n° "'.$idbdl.'" n\'existe';		
	}
}
?>