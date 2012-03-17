<?php
# Path PDF
$pathpdf = 'document/temp/merch/planningint/';
$nompdf = 'global-'.remaccents($_SESSION['prenom']).'-'.date("Ymd").'.pdf';

$pdf = pdf_new();
pdf_open_file($pdf, Conf::read('Env.root').$pathpdf.$nompdf); # définit l'emplacement de la sauvegarde
# Infos pour le document
pdf_set_info($pdf, "Title", "Planning merch");
pdf_set_info($pdf, "Creator", "NEURO");
pdf_set_info($pdf, "Subject", "Planning Merch");

######## Variables de taille  ###############
$LargeurPage = 595; # Largeur A4
$HauteurPage = 842; # Hauteur A4
$MargeLeft = 30;
$MargeRight = 30;
$MargeTop = 30;
$MargeBottom = 30;

$np = 1; # Numéro de la première page
######## Variables de taille  ###############
$LargeurUtile = $LargeurPage - $MargeRight - $MargeLeft;
$HauteurUtile = $HauteurPage - $MargeTop - $MargeBottom;

### Declaration des fontes
$Helvetica = PDF_load_font($pdf, "Helvetica", "host", "");
$HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");

$tour = 1;
$turntot = 1; 

if ($_SESSION['msort'] == 'p.idpeople') $_SESSION['msort'] = 'p.pnom, p.pprenom, me.datem ';
if ($_SESSION['msort'] == 's.codeshop') $_SESSION['msort'] = 's.societe, s.ville, me.datem, p.pnom, p.pprenom ';
if (empty($_SESSION['msort']))			$_SESSION['msort'] = 'me.weekm, s.societe, s.ville, me.datem, p.pnom, p.pprenom ';

$sql = "SELECT 
	me.idmerch, me.datem, me.weekm, me.genre, 
	me.hin1, me.hout1, me.hin2, me.hout2, 
	me.kmpaye, me.kmfacture,
	me.produit, me.facturation, 
	me.ferie, me.contratencode, me.rapportencode, me.recurrence, me.easremplac,
	a.prenom, a.idagent, 
	c.codeclient, c.societe AS clsociete, c.idclient, c.tel, c.fax, 
	co.idcofficer, co.qualite, co.onom, co.oprenom, co.fax AS cofax, co.langue, 
	s.idshop, s.codeshop, s.societe AS ssociete, s.ville AS sville, 
	p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.gsm, p.codepeople,
	nf.montantfacture, nf.montantpaye
	FROM merch me
	LEFT JOIN agent a ON me.idagent = a.idagent 
	LEFT JOIN client c ON me.idclient = c.idclient 
	LEFT JOIN cofficer co ON me.idcofficer = co.idcofficer 
	LEFT JOIN people p ON me.idpeople = p.idpeople
	LEFT JOIN shop s ON me.idshop = s.idshop
	LEFT JOIN notefrais nf ON nf.secteur = 'ME' AND nf.idmission  = me.idmerch
	".$merchjobquid." ORDER BY ".$_SESSION['msort'];

################### Get infos du job ########################
$det = new db();
$det->inline("SET NAMES latin1");
$det->inline($sql);
################### Fin infos du job ########################

	$dernier = mysql_num_rows($det->result);

	while ($row = mysql_fetch_array($det->result))
	{ 
		## Datas
		$merch = new coremerch($row['idmerch']);

		$cols = array (
			array(30, "Job", $row['idmerch'], 'center'),
			array(15, "S", $row['weekm'], 'center'),
			array(45, "Date", fdate($row['datem']), 'center'),
			array(20, "Reg", $row['codepeople'], 'center'),
			array(95, "People", $row['pprenom']." ".$row['pnom'], 'center'),
			array(50, "GSM", $row['gsm'], 'center'),
			array(100, "Customer", $row['clsociete'], 'left'),
			array(110, "Place", $row['ssociete']." - ".$row['sville'], 'left'),
			array(25, "In", ftime($row['hin1']), 'center'),
			array(25, "Out", ftime($row['hout1']), 'center'),
			array(25, "In", ftime($row['hin2']), 'center'),
			array(25, "Out", ftime($row['hout2']), 'center'),
			array(25, "Tot", $merch->hprest, 'center'),
			array(20, "K P", fnbr0($row['kmpaye']), 'center'),
			array(20, "K F", fnbr0($row['kmfacture']), 'center'),
			array(25, "Fra P", fnbr0($row['montantpaye']), 'center'),
			array(25, "Fra F", fnbr0($row['montantfacture']), 'center'),
			array(110, "Product / Note", $row['produit'], 'left')
		);
		
		## Print
		if ($tour == 1) {
		
			# ##### change le changement de page pour que les totaux ne recouvrent pas le bas de page. 
			$jump = 37;
			# #######

			$np++;
			pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
			PDF_create_bookmark($pdf, 'Page '.$np, "");
			
			pdf_rotate($pdf, 90);
			pdf_translate($pdf, $MargeBottom, $MargeRight - $LargeurPage); # Positionne le repère au point bas-gauche
			
			#### Entete de Page  ########################################
			#															#
				pdf_setfont($pdf, $HelveticaBold, 14);
				pdf_set_value ($pdf, "leading", 14);
				pdf_show_boxed($pdf, "EXCEPTION2 - Planning Interne MERCH" , 0 , 525 , 525, 15 , 'left', ""); 		# Titre
			#															#
			#### Entete de Page  ########################################
			
			
			#### Corps de Page  #########################################
			# Ligne titre 1 (2)	
			$tab = 500;
			$tabt = $tab - 1;
			$ht = 13;
			$hl = 13;
			
				pdf_setlinewidth($pdf, 0.5);
				pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);
				pdf_setcolor($pdf, "stroke", "gray", 1, 0, 0, 0);

				## Cadres Titres
				$x = 0;
				foreach ($cols as $d) {
					pdf_rect($pdf, $x , $tab , $d[0], $hl);
					$x += $d[0]	;
				}

				pdf_fill_stroke($pdf); 

				pdf_setlinewidth($pdf, 1);
				pdf_setcolor($pdf, "both", "gray", 1, 0, 0, 0);
				
				pdf_setfont($pdf, $HelveticaBold, 9);
				pdf_set_value ($pdf, "leading", 9);
			
				## Titres
				$x = 0;
				foreach ($cols as $d) {
					pdf_show_boxed($pdf, $d[1] , $x , $tabt , $d[0], $ht , 'center', "");
					$x += $d[0]	;
				}

			$tab = $tab - $hl;	
			$tabt = $tabt - $hl;
			
			}
	### lignes #####
	#
				pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);
		
				# Ligne Contenu
				pdf_setlinewidth($pdf, 0.5);
				if (fmod($tour, 2) == 0) { pdf_setcolor($pdf, "fill", "gray", 0.9, 0, 0, 0) ;} else {pdf_setcolor($pdf, "fill", "gray", 1, 0, 0, 0) ;}
				## Cadres Titres
				$x = 0;
				foreach ($cols as $d) {
					pdf_rect($pdf, $x , $tab , $d[0], $hl);
					$x += $d[0]	;
				}
				
				pdf_fill_stroke($pdf); 
				pdf_setlinewidth($pdf, 1);
				
				pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);

				$merch = new coremerch($row['idmerch']);

				if (!empty($row['gsm'])) { $row['gsm'] = ' ( '.$row['gsm'].' ) '; }
			pdf_setfont($pdf, $Helvetica, 7);
			pdf_set_value ($pdf, "leading", 7);
			
				## Cadres Titres
				$x = 0;
				foreach ($cols as $d) {
					if (substr($d[1], 0, 7) == 'Product') {
						pdf_setfont($pdf, $Helvetica, 6);
						pdf_set_value ($pdf, "leading", 7);
						pdf_show_boxed($pdf, showmax($d[2], 30) , $x + 2 , $tabt , $d[0] - 4, $ht , $d[3], "");
						pdf_setfont($pdf, $Helvetica, 7);
						pdf_set_value ($pdf, "leading", 7);
					} else  {
						pdf_show_boxed($pdf, $d[2] , $x + 2 , $tabt , $d[0] - 4, $ht , $d[3], "");
					}
					$x += $d[0]	;
				}

				$tab = $tab - $hl;	
				$tabt = $tabt - $hl;
	#			
	### lignes #####		
				
			if (($tour == $jump) or ($turntot == $dernier)) {
			
		
		#### Pied de Page    ########################################
		#															#
			# Ligne de bas de page
			pdf_moveto($pdf, 0, 10);
			pdf_lineto($pdf, $HauteurUtile, 10);
			pdf_stroke($pdf); # Ligne de bas de page
			
			
			# Coordonnées Exception
			pdf_setfont($pdf, $Helvetica, 8);
			pdf_show_boxed($pdf, "Imprime le ".date("d/m/Y")." à ".date("H:i:s") ,0 ,0 , 200, 10, 'left', ""); #texte du commentaire
			pdf_show_boxed($pdf, "Exception2 scrl   /   195 Av. de la Chasse - 1040 - Bruxelles" ,0 ,0 , $HauteurUtile, 10, 'right', ""); #texte du commentaire
		#															#
		#### Pied de Page    ########################################
		
		pdf_end_page($pdf);
					
			
			$tour = 0;
			}
		$tour++;
		$turntot++;
		
	}
 # Fin Pages de détail #######
	pdf_end_document($pdf, '');
pdf_delete($pdf); # Efface le fichier en mémoire
# Lien vers le PDF
?>
<A href="<?php echo NIVO.$pathpdf.$nompdf ;?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"> <?php echo $nompdf;?></A><br>
