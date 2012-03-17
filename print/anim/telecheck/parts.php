<?php
### Declaration des fontes
$TimesItalic = PDF_load_font($pdf, "Times-Italic", "host", "");
$Helvetica = PDF_load_font($pdf, "Helvetica", "host", "");
$HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");
$HelveticaOblique = PDF_load_font($pdf, "Helvetica-Oblique", "host", "");


switch ($part) {
##### entete  ####################################################################################
#########################################################################################
	case"entete1":
		pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
		PDF_create_bookmark($pdf, 'W '.$row['weekm'].' '.$row['yearm'].' '.$row['clsociete'], "");
		
		pdf_rotate($pdf, 90);
		pdf_translate($pdf, $MargeBottom, $MargeRight - $LargeurPage); # Positionne le rep re au point bas-gauche
		
	break;
##### Politessein  ##################################################################################
#########################################################################################
	case"politessein":
		# illu
		$logobig = PDF_load_image($pdf, "png", "../illus/logoPrint.png", "");
		$haut = PDF_get_value($pdf, "imagewidth", $logobig) * 0.05; # Calcul de la hauteur
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
		pdf_show_boxed($pdf, $phrase[5] , 350 , 520 , 100, 15 , 'right', "");
		pdf_show_boxed($pdf, $phrase[6] , 350 , 500 , 100, 15 , 'right', "");
		pdf_show_boxed($pdf, $phrase[7] , 350 , 480 , 100, 15 , 'right', "");

		pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 12);
		pdf_show_boxed($pdf, $row['clsociete'] , 450 , 520 , 325, 15 , 'left', "");
		pdf_show_boxed($pdf, $row['qualite']." ".$row['onom']." ".$row['oprenom'] , 450 , 500 , 325, 15 , 'left', "");
		pdf_show_boxed($pdf, $row['cofax'] , 450 , 480 , 325, 15 , 'left', "");

		pdf_setfont($pdf, $HelveticaOblique, 6); pdf_set_value ($pdf, "leading", 6);
		pdf_show_boxed($pdf, $row['idclient'] , $HauteurUtile - 35 , 520 , 30, 10 , 'right', "");
		pdf_show_boxed($pdf, $row['idcofficer'] , $HauteurUtile - 35 , 500 , 30, 10 , 'right', "");

		pdf_setfont($pdf, $TimesItalic, 14); pdf_set_value ($pdf, "leading", 16);
		pdf_show_boxed($pdf, "     ".$phrase[8].$row['qualite']." ".$row['onom'].$phrase[9].$row['weekm'].' '.$row['yearm'] , 20 , 420 , $HauteurUtile - 20, 40 , 'left', "");
	break;
##### entete2  #####################################################################################
#########################################################################################
	case"entete2":
		$ht = 16;
		$hl = 16;

		pdf_setlinewidth($pdf, 0.5);
		pdf_setcolor($pdf, "fill", "gray", 0, 0 ,0 ,0);
		pdf_setcolor($pdf, "stroke", "gray", 1, 0, 0 ,0);

		pdf_rect($pdf,   0 , $tab ,  60, $hl);
		pdf_rect($pdf,  60 , $tab ,  40, $hl);
		pdf_rect($pdf, 100 , $tab , 170, $hl);
		pdf_rect($pdf, 270 , $tab , 190, $hl);
		pdf_rect($pdf, 460 , $tab ,  60, $hl);
		pdf_rect($pdf, 520 , $tab , 260, $hl);

		pdf_fill_stroke($pdf); 
		
		pdf_setcolor($pdf, "both", "gray", 1, 0, 0 ,0);
		
		pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, "leading", 14);
	
		pdf_show_boxed($pdf, $phrase[11] ,   0 , $tabt ,  60, $ht , 'center', "");
		pdf_show_boxed($pdf, $phrase[17] ,  60 , $tabt ,  40, $ht , 'center', "");
		pdf_show_boxed($pdf, $phrase[14] , 100 , $tabt , 170, $ht , 'center', "");
		pdf_show_boxed($pdf, $phrase[15] , 270 , $tabt , 190, $ht , 'center', "");
		pdf_show_boxed($pdf, $phrase[12] , 460 , $tabt ,  60, $ht , 'center', "");
		pdf_show_boxed($pdf, $phrase[13] , 520 , $tabt , 260, $ht , 'center', "");
 
		$tab = $tab - $hl;	
		$tabt = $tabt - $hl;
	break;
##### corps  #####################################################################################
#########################################################################################
	case"corps":
		## longueur de Commentaire
		pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, "leading", 10);

		$nlig = 0;
		$tabtext = explode("\r", trim(stripslashes($row['tchkcomment'])));
		
		foreach($tabtext as $line) {
			$nlig += ceil(pdf_stringwidth($pdf, trim($line), $Helvetica, 9) / 255);
		}

		if ($nlig < 1 ) $nlig = 1;
		$tab -= ($nlig - 1) * $hl;
		
		$jump -= ($nlig - 1);


		pdf_setcolor($pdf, "stroke", "gray", 0, 0, 0 ,0);
		if (fmod($tour, 2) == 0) { pdf_setcolor($pdf, "fill", "gray", 0.9, 0, 0, 0) ;} else {pdf_setcolor($pdf, "fill", "gray", 1, 0, 0 ,0) ;}
		# Ligne Contenu
		pdf_rect($pdf,   0 , $tab ,  60, $hl * $nlig);
		pdf_rect($pdf,  60 , $tab ,  40, $hl * $nlig);
		pdf_rect($pdf, 100 , $tab , 170, $hl * $nlig);
		pdf_rect($pdf, 270 , $tab , 190, $hl * $nlig);
		pdf_rect($pdf, 460 , $tab ,  60, $hl * $nlig);
		pdf_rect($pdf, 520 , $tab , 260, $hl * $nlig);
		pdf_fill_stroke($pdf); 
		
		pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);
		
		pdf_show_boxed($pdf, implode("\r", $tabtext) , 525 , $tab , 255, $hl * $nlig , 'left', "");

		pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
		
		pdf_show_boxed($pdf, fdate($row['datem']) 										, 0 , $tabt ,  60, $ht , 'center', "");
		pdf_show_boxed($pdf, $row['idanimation'] 		   							   , 60 , $tabt ,  40, $ht , 'center', "");
		pdf_show_boxed($pdf, $row['ssociete']." - ".$row['sville']  , 105 , $tabt , 160, $ht , 'left', "");
		pdf_show_boxed($pdf, $row['pprenom']." ".$row['pnom'] 	  , 275 , $tabt , 180, $ht , 'left', "");
		pdf_show_boxed($pdf, fdate($row['tchkdate']) 								  , 460 , $tabt ,  60, $ht , 'center', "");

		$tab -= $hl;	
		$tabt = $tab + 1;	
	break;
##### Politesse out  #####################################################################################
#########################################################################################
	case"politesseout":
		##infos agent
		asort($agents);
		$agents = array_flip($agents);
		$agent = array_shift($agents);
		
		$DB->inline("SET NAMES latin1");
		$agt = $DB->getRow("SELECT * FROM agent WHERE idagent = ".$agent);

		$tab -= 40;
	
		pdf_setfont($pdf, $TimesItalic, 14); pdf_set_value ($pdf, "leading", 16);
		pdf_show_boxed($pdf, $phrase[18] , 20 , $tab , $HauteurUtile - 20, 50 , 'left', "");
		
		#### Signature Planner  ########################################
            if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/print/illus/signature/'.$agt['idagent'].'.jpg')) {
                echo "<br>fichier signature non trouvé pour (".$agt['idagent'].") :".remaccents($agt['prenom']);
            } else {
                $signature = $_SERVER["DOCUMENT_ROOT"].'/print/illus/signature/'.$agt['idagent'].'.jpg';
                $signature = PDF_load_image($pdf, "jpeg", $signature, "");
                pdf_place_image($pdf, $signature, $HauteurUtile - 200, $tab - 35, 0.36);	
            }

		#															#
		pdf_set_value ($pdf, "leading", 14);
		pdf_show_boxed($pdf, $phrase[19].$agt['prenom']." ".$agt['nom']." \r ".$agt['atel']." - ".$agt['agsm'], $HauteurUtile - 400, $tab - 35, 180, 52, 'right', ""); # Titre
		unset($agents);
	break;
##### Pied  ######################################################################################
#########################################################################################
	case"pied":
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
		
		pdf_end_page($pdf);
	break;
}
?>