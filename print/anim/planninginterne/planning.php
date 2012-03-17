<?php
########################################################################################################################################################
### Mise en place PDF ##################################################################################################################################
########################################################################################################################################################

## Path PDF #######################################################
$pathpdf = 'document/temp/anim/planningint/';
$jobnum = str_repeat('0', 5 - strlen($_GET['idvipjob'])).$_GET['idvipjob'];
$nompdf = 'planning int-global-'.remaccents($_SESSION['prenom']).'-'.date("Ymd").'.pdf';

$pdf = pdf_new();
pdf_open_file($pdf, Conf::read('Env.root').$pathpdf.$nompdf); # définit l'emplacement de la sauvegarde
# Infos pour le document
pdf_set_info($pdf, "Author", $infos['prenomagent'].' '.$infos['nomagent']);
pdf_set_info($pdf, "Title", "Planning Anim");
pdf_set_info($pdf, "Creator", "NEURO");
pdf_set_info($pdf, "Subject", "Planning Anim");

### Declaration des fontes
$HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");
$Helvetica = PDF_load_font($pdf, "Helvetica", "host", "");

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

$tour = 1;
$turntot = 1;

if (empty($_SESSION['animmissionsort'])) $_SESSION['animmissionsort'] = 'an.datem, an.hin1, an.hout1, s.societe';

########################################################################################################################################################
### Page Detail ########################################################################################################################################
########################################################################################################################################################
$det = new db();
$det->inline("SET NAMES latin1");
$det->inline("SELECT
an.idanimation, an.idanimjob, an.datem, an.hin1, an.hout1, an.hin2, an.hout2, an.produit,
p.gsm, p.pnom, p.pprenom,
s.societe as ssociete, s.ville as sville, s.tel,
c.societe as clsociete

FROM animation an
LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
LEFT JOIN people p ON an.idpeople = p.idpeople
LEFT JOIN shop s ON an.idshop = s.idshop
LEFT JOIN client c ON j.idclient = c.idclient
LEFT JOIN agent a ON j.idagent = a.idagent

WHERE an.idanimation IN (".implode(", ", $_POST['print']).")
ORDER BY ".$_SESSION['animmissionsort']);
################### Fin infos du job ########################

	$dernier = mysql_num_rows($det->result);

	while ($row = mysql_fetch_array($det->result))
	{
		$colarray = array(                                                                  
			array( 50, 'Date'	  , fdate($row['datem'])                  , 'center'),
			array( 30, 'from'	  , ftime($row['hin1'])                   , 'center'),
			array( 30, 'to'		  , ftime($row['hout1'])                  , 'center'),
			array( 30, 'from'	  , ftime($row['hin2'])                   , 'center'),
			array( 30, 'to'		  , ftime($row['hout2'])                  , 'center'),
			array(140, 'Place'	  , showmax($row['ssociete']." - ".$row['sville'], 45) , 'center'),
			array( 50, 'Tel'	  , $row['tel']                   	      , 'center'),
			array(130, 'Name'	  , $row['pprenom']." ".$row['pnom']      , 'center'),
			array( 50, 'GSM'	  , $row['gsm']                           , 'center'),
			array(130, 'Customer' , $row['clsociete']                     , 'center'),
			array( 80, 'Produit'  , showmax($row['produit'], 20)		  , 'center'),
			array( 30, 'Mis.'	  , $row['idanimation']                   , 'center')
		);

		if ($tour == 1)
		{
			# ##### change le changement de page pour que les totaux ne recouvrent pas le bas de page.
			$jump = 30;
			$reste -= $jump;
			# #######

			$np++;
			pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
			pdf_create_bookmark($pdf, $phrase[3].$np, "");

			pdf_rotate($pdf, 90);
			pdf_translate($pdf, $MargeBottom, $MargeRight - $LargeurPage); # Positionne le repère au point bas-gauche

			#### Entete de Page  ########################################
			#															#
				# illu
				$logobig = PDF_load_image($pdf, "png", "../illus/logoPrint.png", "");
				$haut = PDF_get_value($pdf, "imagewidth", $logobig) * 0.03; # Calcul de la hauteur
				pdf_place_image($pdf, $logobig, 5, $LargeurUtile - $haut, 0.25);

				# Titre
				pdf_setfont($pdf, $HelveticaBold, 14);
				pdf_set_value ($pdf, "leading", 14);
				pdf_show_boxed($pdf, "Internal Planning " , 228 , 520 , 525, 20 , 'left', ""); 		# Titre
				pdf_rect($pdf, 220, 510, 560, 40);			#
				pdf_stroke($pdf);
				# Titre


			#															#
			#### Entete de Page  ########################################


			#### Corps de Page  #########################################
			# Ligne titre 1 (2)
			$tab = 435;
			$tabt = 433;
			$ht = 13;
			$hl = 13;

			pdf_setlinewidth($pdf, 0.5);
			pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);
			pdf_setcolor($pdf, "stroke", "gray", 1, 0, 0, 0);

			## cadres headers
			$z = 0;
			foreach ($colarray as $col)
			{
				pdf_rect($pdf, $z , $tab , $col[0], $hl);
				$z += $col[0];
			}

			pdf_fill_stroke($pdf); 

			pdf_setlinewidth($pdf, 1);
			pdf_setcolor($pdf, "both", "gray", 1, 0, 0, 0);
			
			pdf_setfont($pdf, $HelveticaBold, 9);
			pdf_set_value ($pdf, "leading", 9);

			## Textes Headers
			$z = 0;
			foreach ($colarray as $col)
			{
				pdf_show_boxed($pdf, $col[1] , $z , $tabt , $col[0], $ht , 'center', "");
				$z += $col[0];
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

		## cadres contenu
		$z = 0;
		foreach ($colarray as $col)
		{
			pdf_rect($pdf, $z , $tab , $col[0], $hl);
			$z += $col[0];
		}
		
		pdf_fill_stroke($pdf); 
		pdf_setlinewidth($pdf, 1);
		pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);

		pdf_setfont($pdf, $Helvetica, 7);
		pdf_set_value ($pdf, "leading", 7);

		## cadres contenu
		$z = 0;
		foreach ($colarray as $col)
		{
			pdf_show_boxed($pdf, $col[2] , $z + 2 , $tabt , $col[0] - 4, $ht , $col[3], "");
			$z += $col[0];
		}

		$tab = $tab - $hl;
		$tabt = $tabt - $hl;

	#
	### lignes #####

		if (($tour == $jump) or ($turntot == $dernier)) {
		#### Pied de Page    ########################################
		#															#
			#date
			pdf_setfont($pdf, $Helvetica, 9);
			pdf_show_boxed($pdf, $phrase[50].date("d/m/Y")." à ".date("H:i:s") ,0 ,30 , 200, 15, 'left', ""); #texte du commentaire

			# Ligne de bas de page
			pdf_moveto($pdf, 0, 30);
			pdf_lineto($pdf, $HauteurUtile, 30);
			pdf_stroke($pdf); # Ligne de bas de page

			# Coordonnées Exception
			pdf_setfont($pdf, $Helvetica, 10);

			pdf_show_boxed($pdf, "Exception - Exception scrl\r\rJachtlaan 195 Av. de la Chasse\rBrussel - 1040 - Bruxelles" ,0 ,-10 , $HauteurUtile / 3, 40, 'center', ""); #texte du commentaire
			pdf_show_boxed($pdf, "\rBTW BE 430 597 846 TVA\rHCB 489 589 RCB" , $HauteurUtile / 3,-10 , $HauteurUtile / 3,40, 'center', ""); #texte du commentaire
			pdf_show_boxed($pdf, "www.exception2.be\r\rTel : 02 732.74.40\rFax : 02 732.79.38" , $HauteurUtile * 2 / 3 ,-10 , $HauteurUtile / 3, 40, 'center', ""); #texte du commentaire
		#															#
		#### Pied de Page    ########################################

		pdf_end_page($pdf);


			$tour = 0;
		}
		$tour++;
		$turntot++;

	}

########################################################################################################################################################
### Fermeture PDF ######################################################################################################################################
########################################################################################################################################################
		pdf_end_document($pdf, '');
		pdf_delete($pdf); # Efface le fichier en mémoire
		# Lien vers le PDF
		?>
		<A href="<?php echo NIVO.$pathpdf.$nompdf ;?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"> <?php echo $nompdf;?></A><br>
