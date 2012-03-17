<?php 
### Declaration des fontes
$tour = 1;
$turntot = 1; 

	$det = new db();
	$det->inline("SET NAMES latin1");
	$det->inline("SELECT 
	m.idvip, m.h200, m.vipdate, s.societe, m.sexe, m.vipactivite, m.vipin, m.vipout, m.brk, m.km, m.fkm, m.unif, m.loc1, m.cat, m.disp, m.ts
	FROM `vipmission` m
	LEFT JOIN shop s ON m.idshop  = s.idshop 
	LEFT JOIN people p ON m.idpeople  = p.idpeople 
	WHERE `idvipjob` = ".$fac->idjob." ORDER BY m.vipdate ASC");

	$dernier = mysql_num_rows($det->result);
	$reste = $dernier;

	while ($row = mysql_fetch_array($det->result))
	{ 
	
		if ($tour == 1) {
		
			# ##### change le changement de page pour que les totaux ne recouvrent pas le bas de page. 
			if (($reste >= 22) and ($reste <= 25)) {
				$jump = 21;
			} else {
				$jump = 25;
			}
			$reste = $reste - 25;
			# #######

			$np++;
			pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
			PDF_create_bookmark($pdf, $phrase[3].$np, "");
			
			pdf_rotate($pdf, 90);
			pdf_translate($pdf, $MargeBottom, $MargeRight - $LargeurPage); # Positionne le repère au point bas-gauche
			
			#### Entete de Page  ########################################
			#															#
				# illu
                $logobig = PDF_load_image($pdf, "png", $_SERVER["DOCUMENT_ROOT"]."/print/illus/logoPrint.png", "");
                $haut = PDF_get_value($pdf, "imagewidth", $logobig) * 0.1; # Calcul de la hauteur
                pdf_place_image($pdf, $logobig, 5, $LargeurUtile - $haut, 0.3);
				
				# Titre
				if (!empty($prefac)) {
					$laphrase = $phrase[2];
				} else {
					$laphrase = $phrase[24];
				}

				pdf_setfont($pdf, $TimesBold, 18);
				pdf_set_value ($pdf, "leading", 18);
				pdf_show_boxed($pdf, $laphrase.' ('.ffac($fac->id).' )' , 228 , 502 , 525, 25 , 'center', ""); 		# Titre
				pdf_rect($pdf, 200, 500, 581, 30);			#
				pdf_stroke($pdf);
				
				# Sujet et client
				pdf_setfont($pdf, $TimesRoman, 12);
				pdf_set_value ($pdf, "leading", 17);
				pdf_show_boxed($pdf, $phrase[4].$fac->idjob.") ".utf8_decode($fac->reference)."\r".$phrase[14].utf8_decode($fac->agent) , 200 , 460 , 295, 40 , 'left', ""); 		# Titre
				pdf_show_boxed($pdf, $phrase[25].$fac->idclient." ".utf8_decode($fac->societe), 500 , 480 , 279, 20 , 'right', ""); 		
				# Titre

			#															#
			#### Entete de Page  ########################################
			
			
			#### Corps de Page  #########################################
			#															#
				# Ligne titre 1
			$tab = 435;
			$tabt = 438;
			$ht = 17;
			$hl = 20;
			
				pdf_rect($pdf, 252 , $tab , 245, $hl);
				pdf_rect($pdf, 497 , $tab , 75, $hl);
				pdf_rect($pdf, 572 , $tab , 68, $hl);
				pdf_rect($pdf, 640 , $tab , 140, $hl);
				
				pdf_stroke($pdf);
			
				pdf_setfont($pdf, $TimesBold, 12);
				pdf_set_value ($pdf, "leading", 12);
			
				pdf_show_boxed($pdf, $phrase[9] , 225 , $tabt , 240, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[10] , 500 , $tabt , 70, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[11] , 575 , $tabt , 60, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[12] , 645 , $tabt , 130, $ht , 'center', "");

				# Ligne titre 2	
			$tab = 420;
			$tabt = 422;
			$ht = 13;
			$hl = 15;
			
				pdf_rect($pdf, 0 , $tab , 47, $hl);
				pdf_rect($pdf, 47 , $tab , 40, $hl);
				pdf_rect($pdf, 87 , $tab , 90, $hl);
				pdf_rect($pdf, 177 , $tab , 75, $hl);
				pdf_rect($pdf, 252 , $tab , 30, $hl);
				pdf_rect($pdf, 282 , $tab , 35, $hl);
				pdf_rect($pdf, 317 , $tab , 30, $hl);
				pdf_rect($pdf, 347 , $tab , 30, $hl);
				pdf_rect($pdf, 377 , $tab , 30, $hl);
				pdf_rect($pdf, 407 , $tab , 30, $hl);
				pdf_rect($pdf, 437 , $tab , 30, $hl);
				pdf_rect($pdf, 467 , $tab , 30, $hl);
				pdf_rect($pdf, 497 , $tab , 32, $hl);
				pdf_rect($pdf, 529 , $tab , 43, $hl);
				pdf_rect($pdf, 572 , $tab , 33, $hl);
				pdf_rect($pdf, 605 , $tab , 35, $hl);
				pdf_rect($pdf, 640 , $tab , 35, $hl);
				pdf_rect($pdf, 675 , $tab , 35, $hl);
				pdf_rect($pdf, 710 , $tab , 35, $hl);
				pdf_rect($pdf, 745 , $tab , 35, $hl);
				pdf_stroke($pdf); 
				
				pdf_setfont($pdf, $TimesBold, 9);
				pdf_set_value ($pdf, "leading", 10);
			
				pdf_show_boxed($pdf, $phrase[26] , 0 , $tabt , 45, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[27] , 50 , $tabt , 35, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[28] , 90 , $tabt , 85, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[29] , 180 , $tabt , 70, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[30] , 255 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[31] , 285 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[32] , 320 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[33] , 350 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[34] , 380 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[35] , 410 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[66] , 440 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[36] , 470 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[37] , 500 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[38] , 535 , $tabt , 35, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[39] , 575 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[40] , 610 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[41] , 645 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[42] , 680 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[43] , 715 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, $phrase[44] , 750 , $tabt , 25, $ht , 'center', "");
				
			$tab = $tab - $hl;	
			$tabt = $tabt - $hl;
			
			}

	### lignes #####
	#	
				$fich = new corevip ($row['idvip']);
		
				# Ligne Contenu
				if ($row['h200'] == 1) { # Grise les lignes si 200%
					pdf_setcolor($pdf, "fill", "gray", 0.8, 0, 0, 0);
				}
				
				pdf_rect($pdf, 0 , $tab , 47, $hl);
				pdf_rect($pdf, 47 , $tab , 40, $hl);
				pdf_rect($pdf, 87 , $tab , 90, $hl);
				pdf_rect($pdf, 177 , $tab , 75, $hl);
				pdf_rect($pdf, 252 , $tab , 30, $hl);
				pdf_rect($pdf, 282 , $tab , 35, $hl);
				pdf_rect($pdf, 317 , $tab , 30, $hl);
				pdf_rect($pdf, 347 , $tab , 30, $hl);
				pdf_rect($pdf, 377 , $tab , 30, $hl);
				pdf_rect($pdf, 407 , $tab , 30, $hl);
				pdf_rect($pdf, 437 , $tab , 30, $hl);
				pdf_rect($pdf, 467 , $tab , 30, $hl);
				if ($row['h200'] == 1) { # Grise les lignes si 200%
					pdf_fill_stroke($pdf);						#
					pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);
				} else {
					pdf_stroke($pdf); 
				}
				pdf_rect($pdf, 497 , $tab , 32, $hl);
				pdf_rect($pdf, 529 , $tab , 43, $hl);
				pdf_rect($pdf, 572 , $tab , 33, $hl);
				pdf_rect($pdf, 605 , $tab , 35, $hl);
				pdf_rect($pdf, 640 , $tab , 35, $hl);
				pdf_rect($pdf, 675 , $tab , 35, $hl);
				pdf_rect($pdf, 710 , $tab , 35, $hl);
				pdf_rect($pdf, 745 , $tab , 35, $hl);
				pdf_stroke($pdf); 
				
			pdf_setfont($pdf, $TimesRoman, 9);
			pdf_set_value ($pdf, "leading", 10);
				pdf_show_boxed($pdf, fdate($row['vipdate']) , 2 , $tabt , 45, $ht , 'center', "");
				pdf_show_boxed($pdf, $row['idvip'] , 50 , $tabt , 35, $ht , 'center', "");
				pdf_show_boxed($pdf, $row['societe'] , 90 , $tabt , 85, $ht , 'center', "");
				pdf_show_boxed($pdf, $row['sexe'] , 175 , $tabt , 15, $ht , 'center', "");
				pdf_show_boxed($pdf, $row['vipactivite'] , 195 , $tabt , 55, $ht , 'center', "");
				pdf_show_boxed($pdf, ftime($row['vipin']) , 255 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, ftime($row['vipout']) , 285 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, fnbr($row['brk']) , 320 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, fnbr($fich->hhigh) , 350 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, fnbr($fich->hlow) , 380 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, fnbr($fich->hnight) , 410 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, fnbr($fich->h150) , 440 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, fnbr($row['km']) , 500 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, fpeuro($row['fkm']) , 535 , $tabt , 35, $ht , 'center', "");
				pdf_show_boxed($pdf, fpeuro($row['unif']) , 575 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, fpeuro($row['loc1']) , 610 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, fpeuro($row['cat']) , 645 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, fpeuro($row['disp']) , 680 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, fpeuro($fich->MontNfrais) , 715 , $tabt , 25, $ht , 'center', "");
				pdf_show_boxed($pdf, fpeuro($fich->FraisDimona) , 750 , $tabt , 25, $ht , 'center', "");
				
				if ($fich->hspec > 0) {
					pdf_setfont($pdf, $TimesRoman, 8);
					pdf_set_value ($pdf, "leading", 9);
					
					pdf_show_boxed($pdf, fnbr($fich->hspec)." x" , 470 , $tabt + 3 , 25, $ht , 'left', "");
					pdf_show_boxed($pdf, fpeuro($row['ts']) , 470 , $tabt - 4 , 25, $ht , 'right', "");
				}
				
				
				$tab = $tab - $hl;	
				$tabt = $tabt - $hl;	
			
	#			
	### lignes #####		
				
			if (($tour == $jump) or ($turntot == $dernier)) {
			
				if ($turntot == $dernier) {

			# Lignes Totaux
			# Ligne detail 1
				
			$ht = 13;
			$hl = 15;
		
			pdf_rect($pdf, 252 , $tab , 95, $hl);
			pdf_rect($pdf, 347 , $tab , 30, $hl);
			pdf_rect($pdf, 377 , $tab , 30, $hl);
			pdf_rect($pdf, 407 , $tab , 30, $hl);
			pdf_rect($pdf, 437 , $tab , 30, $hl);
			pdf_rect($pdf, 467 , $tab , 30, $hl);
			pdf_rect($pdf, 497 , $tab , 32, $hl);
			pdf_rect($pdf, 529 , $tab , 43, $hl);
#			pdf_rect($pdf, 640 , $tab , 140, $hl);
				pdf_rect($pdf, 572 , $tab , 33, $hl);
				pdf_rect($pdf, 605 , $tab , 35, $hl);
				pdf_rect($pdf, 640 , $tab , 35, $hl);
				pdf_rect($pdf, 675 , $tab , 35, $hl);
				pdf_rect($pdf, 710 , $tab , 35, $hl);
				pdf_rect($pdf, 745 , $tab , 35, $hl);
			
			pdf_stroke($pdf); 
		
			pdf_setfont($pdf, $TimesBold, 9);
			pdf_set_value ($pdf, "leading", 10);
		
			pdf_show_boxed($pdf, $phrase[6] , 180 , $tabt , 60, $ht , 'right', "");
		
			pdf_setfont($pdf, $TimesRoman, 9);
			pdf_set_value ($pdf, "leading", 10);
		
			pdf_show_boxed($pdf, $phrase[45] , 255 , $tabt , 85, $ht , 'right', "");
			pdf_show_boxed($pdf, fnbr($fac->DetHhigh) , 350 , $tabt , 25, $ht , 'center', "");
			pdf_show_boxed($pdf, fnbr($fac->DetHlow) , 380 , $tabt , 25, $ht , 'center', "");
			pdf_show_boxed($pdf, fnbr($fac->DetHnight) , 410 , $tabt , 25, $ht , 'center', "");
			pdf_show_boxed($pdf, fnbr($fac->DetH150) , 440 , $tabt , 25, $ht , 'center', "");
			pdf_show_boxed($pdf, fnbr($fac->DetHspec) , 470 , $tabt , 25, $ht , 'center', "");
			pdf_show_boxed($pdf, fnbr($fac->DetKm) , 500 , $tabt , 25, $ht , 'center', "");
			pdf_show_boxed($pdf, fpeuro($fac->DetFKm) , 535 , $tabt , 35, $ht , 'center', "");

			pdf_show_boxed($pdf, fpeuro($fac->MontCat) , 645 , $tabt , 25, $ht , 'center', "");
			pdf_show_boxed($pdf, fpeuro($fac->MontDisp) , 680 , $tabt , 25, $ht , 'center', "");
			pdf_show_boxed($pdf, fpeuro($fac->MontFr) , 715 , $tabt , 25, $ht , 'center', "");
			pdf_show_boxed($pdf, fpeuro($fac->MontDimona) , 750 , $tabt , 25, $ht , 'center', "");
			
		
			# Ligne detail 2	
			
			$ht = 13;
			$hl = 15;
		
			$tab = $tab - $hl;	
			$tabt = $tabt - $hl;
		
			pdf_rect($pdf, 252 , $tab , 95, $hl);
			pdf_rect($pdf, 347 , $tab , 30, $hl);
			pdf_rect($pdf, 377 , $tab , 30, $hl);
			pdf_rect($pdf, 407 , $tab , 30, $hl);
			pdf_rect($pdf, 437 , $tab , 30, $hl);
			pdf_rect($pdf, 467 , $tab , 30, $hl);
			pdf_rect($pdf, 497 , $tab , 32, $hl);
			pdf_rect($pdf, 529 , $tab , 43, $hl);
			pdf_rect($pdf, 572 , $tab , 68, $hl);
			pdf_rect($pdf, 640 , $tab , 140, $hl);
			pdf_stroke($pdf); 
		
			pdf_setfont($pdf, $TimesRoman, 9);
			pdf_set_value ($pdf, "leading", 10);
		
			pdf_show_boxed($pdf, $phrase[46] , 255 , $tabt , 85, $ht , 'right', "");
			pdf_show_boxed($pdf, fpeuro($fac->Tarif['high']) , 350 , $tabt , 25, $ht , 'center', "");
			pdf_show_boxed($pdf, fpeuro($fac->Tarif['low']) , 380 , $tabt , 25, $ht , 'center', "");
			pdf_show_boxed($pdf, fpeuro($fac->Tarif['night']) , 410 , $tabt , 25, $ht , 'center', "");
			pdf_show_boxed($pdf, fpeuro($fac->Tarif['150']) , 440 , $tabt , 25, $ht , 'center', "");
			pdf_show_boxed($pdf, fpeuro($fac->Tarif['km']) , 500 , $tabt , 25, $ht , 'center', "");
			pdf_show_boxed($pdf, "" , 535 , $tabt , 35, $ht , 'center', "");
		
			# Ligne sous-totaux	
			$tab = $tab - $hl;	
			$tabt = $tabt - $hl;
		
			pdf_rect($pdf, 252 , $tab , 245, $hl);
			pdf_rect($pdf, 497 , $tab , 75, $hl);
			pdf_rect($pdf, 572 , $tab , 68, $hl);
			pdf_rect($pdf, 640 , $tab , 140, $hl);
			pdf_stroke($pdf); 
		
			
			pdf_setfont($pdf, $TimesBold, 9);
			pdf_set_value ($pdf, "leading", 10);
		
			pdf_show_boxed($pdf, $phrase[47] , 180 , $tabt , 60, $ht , 'right', "");
		
			pdf_setfont($pdf, $TimesBold, 9);
			pdf_set_value ($pdf, "leading", 10);
		
			pdf_show_boxed($pdf, fpeuro($fac->MontPrest) , 435 , $tabt , 55, $ht , 'right', "");
			pdf_show_boxed($pdf, fpeuro($fac->MontDepl) , 510 , $tabt , 55, $ht , 'right', "");
			pdf_show_boxed($pdf, fpeuro($fac->MontLoc) , 575 , $tabt , 55, $ht , 'right', "");
			pdf_show_boxed($pdf, fpeuro($fac->MontFrais) , 715 , $tabt , 55, $ht , 'right', "");
		
			# Ligne Total	
			$ht = 14;
			$hl = 17;
		
			$tab = $tab - $hl;	
			$tabt = $tabt - $hl;
		
			pdf_rect($pdf, 572 , $tab , 208, $hl); 
			pdf_stroke($pdf); 
		
			
			pdf_setfont($pdf, $TimesBold, 12);
			pdf_set_value ($pdf, "leading", 12);
			
			pdf_show_boxed($pdf, $phrase[48] , 575 , $tabt , 60, $ht , 'left', "");
			pdf_show_boxed($pdf, fpeuro($fac->MontHTVA) , 715 , $tabt , 55, $ht , 'right', "");
			
			# Ligne note
			$ht = 60;
			
			pdf_setfont($pdf, $TimesItalic, 8);
			pdf_set_value ($pdf, "leading", 9);
		
			pdf_show_boxed($pdf, $phrase[49] , 0 , $tabt , 155, $ht , 'left', "");
		
		
		#															#
		#### Corps de Page  #########################################
				
				}
		
		#### Pied de Page    ########################################
		#															#
			#date
			pdf_setfont($pdf, $TimesRoman, 9);
			pdf_show_boxed($pdf, $phrase[50].date("d/m/Y")." à ".date("H:i:s") ,0 ,30 , 200, 15, 'left', ""); #texte du commentaire
		
			# Ligne de bas de page
			pdf_moveto($pdf, 0, 30);
			pdf_lineto($pdf, $HauteurUtile, 30);
			pdf_stroke($pdf); # Ligne de bas de page
			
			
			# Coordonnées Exception
			pdf_setfont($pdf, $TimesRoman, 10);
			
			pdf_show_boxed($pdf, $phrase[20] ,0 ,-10 , $HauteurUtile / 3, 40, 'center', ""); #texte du commentaire
			pdf_show_boxed($pdf, $phrase[21] , $HauteurUtile / 3,-10 , $HauteurUtile / 3,40, 'center', ""); #texte du commentaire
			pdf_show_boxed($pdf, $phrase[22] , $HauteurUtile * 2 / 3 ,-10 , $HauteurUtile / 3, 40, 'center', ""); #texte du commentaire
		#															#
		#### Pied de Page    ########################################
		
		pdf_end_page($pdf);
					
			
			$tour = 0;
			}

		$tour++;
		$turntot++;
	}
 ?> 