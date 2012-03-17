<?php 
### Declaration des fontes
$TimesItalic = PDF_load_font($pdf, "Times-Italic", "host", "");
$Helvetica = PDF_load_font($pdf, "Helvetica", "host", "");
$HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");
$HelveticaOblique = PDF_load_font($pdf, "Helvetica-Oblique", "host", "");

$tour = 1;
$turntot = 1; 

$recherche = "
	SELECT 
		an.idanimation, an.datem, an.hin1, an.hout1, an.hin2, an.hout2, an.produit, an.weekm, an.yearm,
		j.idcofficer, j.idclient, j.idagent,
		c.societe as clsociete,
		o.qualite, o.onom, o.oprenom, o.fax, o.langue,
		s.societe as ssociete, s.ville as sville,
		p.pprenom, p.pnom, p.idpeople
	FROM animation an
		LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
		LEFT JOIN client c ON j.idclient = c.idclient
		LEFT JOIN cofficer o ON j.idcofficer = o.idcofficer
		LEFT JOIN shop s ON an.idshop = s.idshop
		LEFT JOIN people p ON an.idpeople = p.idpeople ";

if ($_GET['web'] == 'web') {
	$recherche .= $animjobquid.' ORDER BY '.$animjobsort;
} else {
	$recherche .= " WHERE an.idanimation IN(".implode(", ", $_POST['print']).") ORDER BY ".$animjobsort;
}


################### Get infos du job ########################
$det = new db();
$det->inline("SET NAMES latin1");
$det->inline($recherche);
################### Fin infos du job ########################

	while ($row = mysql_fetch_array($det->result)) {

		## datas
		if (($dattin[$row['idcofficer']] > $row['datem']) or (empty($dattin[$row['idcofficer']]))) $dattin[$row['idcofficer']] = $row['datem'];
		if (($dattout[$row['idcofficer']] < $row['datem']) or (empty($dattout[$row['idcofficer']]))) $dattout[$row['idcofficer']] = $row['datem'];
		
		$agents[$row['idagent']] += 1;
	}

	## Infos Agent
	asort($agents);
	
	$agents = array_flip($agents);
	
	$agent = array_shift($agents);
	$DB->inline("SET NAMES latin1");
	$agt = $DB->getRow("SELECT * FROM agent WHERE idagent = ".$agent);
	

	mysql_data_seek($det->result, 0);

	$reste = $dernier;
	while ($row = mysql_fetch_array($det->result))
	{ 
	

	if (
		(($_GET['web'] != 'web') and ($idcofficer == $row['idcofficer'])) 
		or
		(($_GET['web'] == 'web') and ($weekm == $row['weekm']))
		)
	{
	
		### Phrasebook
		switch($row['langue']) {
			case"FR":
				include "fr.php";
			break;
			case"NL":
				include "nl.php";
			break;
		}	
	
	
		if ($tour == 1) {
		
			# ##### change le changement de page pour que les totaux ne recouvrent pas le bas de page. 
			$jump = 20;
			$reste -= $jump;
			# #######

			$np++;
			pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
			PDF_create_bookmark($pdf, $phrase[3].$np, "");
			
			pdf_rotate($pdf, 90);
			pdf_translate($pdf, $MargeBottom, $MargeRight - $LargeurPage); # Positionne le rep re au point bas-gauche
			
			#### Entete de Page  ########################################
			#															#
				# illu
				$logobig = PDF_load_image($pdf, "png", "../illus/logoPrint.png", "");
				$haut = PDF_get_value($pdf, "imagewidth", $logobig) * 0.1; # Calcul de la hauteur
				pdf_place_image($pdf, $logobig, 5, $LargeurUtile - $haut, 0.2);

				pdf_rect($pdf, 400, 475, $HauteurUtile - 400, 65);			#
				pdf_stroke($pdf);
				
				# Titre
				pdf_setfont($pdf, $HelveticaBold, 24); pdf_set_value ($pdf, "leading", 24);
				pdf_show_boxed($pdf, $phrase[1] , 130 , 525 , 525, 24 , 'left', "");
				
				# Semaine
				pdf_setfont($pdf, $HelveticaBold, 14); pdf_set_value ($pdf, "leading", 14);
				pdf_show_boxed($pdf, $phrase[27].' '.$row['weekm'].' / '.$row['yearm'] , 130 , 520 , 240, 20 , 'right', "");
				
				# Ligne de bas de page
				pdf_setlinewidth($pdf, 0.5);
				pdf_moveto($pdf, 130, 515);
				pdf_lineto($pdf, 370, 515);
				pdf_stroke($pdf); # Ligne de bas de page
				pdf_setlinewidth($pdf, 1);
				
				pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, "leading", 10);
				pdf_show_boxed($pdf, $phrase[2] ,130 ,495 , 150, 15, 'left', ""); #texte du commentaire

				pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, $phrase[3] ,130 ,465 , 150, 30, 'left', ""); #texte du commentaire
				pdf_setfont($pdf, $Helvetica, 8); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, $phrase[4] ,250 ,465 , 120, 30, 'right', ""); #texte du commentaire

				pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, $phrase[5] , 400 , 520 , 100, 15 , 'right', "");
				pdf_show_boxed($pdf, $phrase[6] , 400 , 500 , 100, 15 , 'right', "");
				pdf_show_boxed($pdf, $phrase[7] , 400 , 480 , 100, 15 , 'right', "");

				pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, $row['clsociete'] , 500 , 520 , 325, 15 , 'left', "");
				pdf_show_boxed($pdf, $row['qualite']." ".$row['onom']." ".$row['oprenom'] , 500 , 500 , 325, 15 , 'left', "");
				pdf_show_boxed($pdf, $row['fax'] , 500 , 480 , 325, 15 , 'left', "");

				pdf_setfont($pdf, $HelveticaOblique, 6); pdf_set_value ($pdf, "leading", 6);
				pdf_show_boxed($pdf, $row['idclient'] , $HauteurUtile - 35 , 520 , 30, 10 , 'right', "");
				pdf_show_boxed($pdf, $row['idcofficer'] , $HauteurUtile - 35 , 500 , 30, 10 , 'right', "");

			#															#
			#### Entete de Page  ########################################
			
			#### Politesse  #############################################
			#															#

			if ($turntot == 1) {
				pdf_setfont($pdf, $TimesItalic, 14); pdf_set_value ($pdf, "leading", 16);
				pdf_show_boxed($pdf, "     ".$phrase[8].$row['qualite']." ".$row['onom'].$phrase[9].$row['weekm'].' '.$row['yearm'] , 20 , 420 , $HauteurUtile - 20, 40 , 'left', "");
			}	
			#															#
			#### Politesse  #############################################
			
				# illu
			pdf_setlinewidth($pdf, 0.25);

			#### Corps de Page  #########################################
			# Ligne titre 1 (2)	
			$tab = 400;
			$tabt = 403;
			$ht = 16;
			$hl = 16;
			

				pdf_setlinewidth($pdf, 0.5);
				pdf_setcolor($pdf, "fill", "gray", 0, 0, 0 ,0);
				pdf_setcolor($pdf, "stroke", "gray", 1, 0, 0 ,0);

				pdf_rect($pdf, 0 , $tab , 60, $hl);
				pdf_rect($pdf, 60 , $tab , 35, $hl);
				pdf_rect($pdf, 95 , $tab , 35, $hl);
				pdf_rect($pdf, 130 , $tab , 35, $hl);
				pdf_rect($pdf, 165 , $tab , 35, $hl);
				pdf_rect($pdf, 200 , $tab , 180, $hl);
				pdf_rect($pdf, 380 , $tab , 200, $hl);
				pdf_rect($pdf, 580 , $tab , 160, $hl);
				pdf_rect($pdf, 740 , $tab , 40, $hl);

#				pdf_rect($pdf, 675 , $tab , 105, $hl);
				pdf_fill_stroke($pdf); 
				
				pdf_setcolor($pdf, "both", "gray", 1, 0, 0 ,0);
				
				pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, "leading", 14);
			
				pdf_show_boxed($pdf, $phrase[11] , 0 , $tabt , 56, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[12] , 60 , $tabt , 35, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[13] , 95 , $tabt , 35, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[12] , 130 , $tabt , 35, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[11] , 165 , $tabt , 35, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[14] , 200 , $tabt , 180, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[15] , 380 , $tabt , 200, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[16] , 580 , $tabt , 170, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[17] , 740 , $tabt , 40, $ht , 'center', "");
	
			$tab = $tab - $hl;	
			$tabt = $tabt - $hl;
			
		} //ferme if tour == 1
	### lignes #####
	#	
				pdf_setcolor($pdf, "both", "gray", 0, 0, 0 ,0);
		
				if (fmod($tour, 2) == 0) { pdf_setcolor($pdf, "fill", "gray", 0.9, 0 ,0 ,0) ;} else {pdf_setcolor($pdf, "fill", "gray", 1, 0, 0 ,0) ;}
				# Ligne Contenu
				pdf_rect($pdf, 0 , $tab , 60, $hl);
				pdf_rect($pdf, 60 , $tab , 35, $hl);
				pdf_rect($pdf, 95 , $tab , 35, $hl);
				pdf_rect($pdf, 130 , $tab , 35, $hl);
				pdf_rect($pdf, 165 , $tab , 35, $hl);
				pdf_rect($pdf, 200 , $tab , 180, $hl);
				pdf_rect($pdf, 380 , $tab , 200, $hl);
				pdf_rect($pdf, 580 , $tab , 160, $hl);
				pdf_rect($pdf, 740 , $tab , 40, $hl);
				pdf_fill_stroke($pdf); 
				
				pdf_setcolor($pdf, "both", "gray", 0, 0 ,0 ,0);

				pdf_setfont($pdf, $Helvetica, 10);
				pdf_set_value ($pdf, "leading", 14);
				pdf_show_boxed($pdf, fdate($row['datem']) , 0 , $tabt , 60, $ht , 'center', "");
				pdf_show_boxed($pdf, ftime($row['hin1']) , 60 , $tabt , 35, $ht , 'center', "");
				pdf_show_boxed($pdf, ftime($row['hout1']) , 95 , $tabt , 35, $ht , 'center', "");
				pdf_show_boxed($pdf, ftime($row['hin2']) , 130 , $tabt , 35, $ht , 'center', "");
				pdf_show_boxed($pdf, ftime($row['hout2']) , 165 , $tabt , 35, $ht , 'center', "");
				pdf_show_boxed($pdf, $row['ssociete']." - ".$row['sville'] , 205 , $tabt , 170, $ht , 'left', "");
				pdf_show_boxed($pdf, $row['idpeople']." - ".$row['pprenom']." ".$row['pnom'] , 385 , $tabt , 190, $ht , 'left', "");
				pdf_show_boxed($pdf, $row['produit'] , 585 , $tabt , 165, $ht , 'left', "");
				pdf_show_boxed($pdf, $row['idanimation'] , 740 , $tabt , 40, $ht , 'center', "");

				
				$tab -= $hl;	
				$tabt -= $hl;	
			
	#			
	### lignes #####		
				
			if (($tour == $jump) or ($turntot == $dernier)) {
			
			#### Politesse  #############################################
			#	
			if ($turntot == $dernier) {
				$tab -= 40;

				pdf_setfont($pdf, $TimesItalic, 14); pdf_set_value ($pdf, "leading", 16);
				pdf_show_boxed($pdf, $phrase[18] , 20 , $tab , $HauteurUtile - 20, 50 , 'left', "");
				
				#### Signature Planner  ########################################
                
                # illu
                    if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/print/illus/signature/'.$agt['idagent'].'.jpg')) {
                        echo "<br>fichier signature non trouvé pour : (".$agt['idagent'].") ".remaccents($agt['prenom']);
                    } else {
                        $signature = $_SERVER["DOCUMENT_ROOT"].'/print/illus/signature/'.$agt['idagent'].'.jpg';
                        $signature = PDF_load_image($pdf, "jpeg", $signature, "");
                        pdf_place_image($pdf, $signature, $HauteurUtile - 200, $tab - 35, 0.36);	
                    }

				#															#
				pdf_set_value ($pdf, "leading", 14);
				pdf_show_boxed($pdf, $phrase[19].$agt['prenom']." ".$agt['nom']." \r ".$agt['atel']." - ".$agt['agsm'], $HauteurUtile - 400, $tab - 35, 180, 52, 'right', ""); # Titre
			
			}
			#															#
			#### Politesse  #############################################
			
		
		#### Pied de Page    ########################################
		#															#
			# Ligne de bas de page
			pdf_moveto($pdf, 0, 20);
			pdf_lineto($pdf, $HauteurUtile, 20);
			pdf_stroke($pdf); # Ligne de bas de page
			
			#date - Page
			pdf_setfont($pdf, $Helvetica, 6); pdf_set_value ($pdf, "leading", 6);
			pdf_show_boxed($pdf, $phrase[20].date("d/m/Y").$phrase[21].date("H:i:s") ,0 ,14 , 200, 6, 'left', ""); #texte du commentaire
			pdf_show_boxed($pdf, $phrase[22].$np ,0 ,0 , 200, 6, 'left', ""); #texte du commentaire
		
			#Infos Site
			pdf_setfont($pdf, $Helvetica, 6); pdf_set_value ($pdf, "leading", 6);
			pdf_show_boxed($pdf, $phrase[23] ,$HauteurUtile - 200 ,14 , 200, 6, 'right', ""); #texte du commentaire
			pdf_show_boxed($pdf, $phrase[24] , $HauteurUtile - 200 ,0 , 200, 6, 'right', ""); #texte du commentaire
		
			pdf_setfont($pdf, $HelveticaBold, 6); pdf_set_value ($pdf, "leading", 6);
			pdf_show_boxed($pdf, $phrase[25] ,$HauteurUtile - 200 ,8 , 200, 6, 'right', ""); #texte du commentaire
		
			
			# Coordonnees Exception
			pdf_setfont($pdf, $Helvetica, 7);pdf_set_value ($pdf, "leading", 9);
			
			pdf_show_boxed($pdf, $phrase[26] , $HauteurUtile / 3,-10 , $HauteurUtile / 3,28, 'center', ""); #texte du commentaire
		#															#
		#### Pied de Page    ########################################
		
		pdf_end_page($pdf);
					
			
			$tour = 0;
			}
		$tour++;
		$turntot++;
		
		}
	}
 ?> 