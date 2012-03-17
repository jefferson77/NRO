<?php
switch ($facan->mode) {
	case"OFFRE":
		$sqwhere = "WHERE an.idanimjob = ".$facan->id;
		$iddocu = $facan->phrasebook[72].' - '.ffac($facan->id);
		$referencejob = $facan->phrasebook[73]." : (".$infos['idanimjob'].") ".$infos['reference'];
	break;
	case"FACTURE":
		$sqwhere = "WHERE an.facnum = ".$facan->id;
		$iddocu = ffac($facan->id);
		$referencejob = $facan->phrasebook[4].utf8_decode($fac->intitule);
	break;
	case"PREFAC":
		$sqwhere = "WHERE an.facnumtemp = ".$facan->id;
		$iddocu = ffac($facan->id);
		$referencejob = $facan->phrasebook[4].utf8_decode($fac->intitule);
	break;
}


### Declaration des fontes
$TimesBold = PDF_load_font($pdf, "Times-Bold", "host", "");
$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");

$tour = 1;
$turntot = 1;
		$DetHeures = 0;
		$DetKm = 0;
		$MontFrais  = 0;
		$DetStand = 0;
		$MontLivraison = 0;
		$MontGobelets = 0;
		$MontServiette = 0;
		$MontFour = 0;
		$MontCuredent = 0;
		$MontCuillere = 0;
		$MontRechaud = 0;
		$MontAutre = 0;

	$det = new db();
	$det->inline("SET NAMES latin1");
	$det->inline("SELECT
			an.idanimation, an.datem, an.kmfacture, an.livraisonfacture, an.ferie,
			j.reference, j.boncommande, j.idanimjob,
			ma.stand, ma.gobelet, ma.serviette, ma.four, ma.curedent, ma.cuillere, ma.rechaud, ma.autre,
			s.societe, s.ville,
			nf.montantfacture
			FROM animation an
			LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
			LEFT JOIN notefrais nf ON an.idanimation = nf.idmission AND nf.secteur = 'AN'
			LEFT JOIN animmateriel ma ON an.idanimation = ma.idanimation
			LEFT JOIN shop s ON an.idshop = s.idshop
		".$sqwhere."
		ORDER BY j.idclient, an.datem");

	$dernier = mysql_num_rows($det->result);
	$reste = $dernier;

	while ($row = mysql_fetch_array($det->result))

	{
		$anim = new coreanim($row['idanimation']);

		## incrÈmentation des totaux H et KM pour print
		$DetHeures += $anim->hprest;
		$DetKm += $row['kmfacture'];
		$MontFrais += $row['montantfacture'];
		$DetStand += $row['stand'];
		$MontLivraison += $row['livraisonfacture'];
		$MontGobelets += $row['gobelet'];
		$MontServiette += $row['serviette'];
		$MontFour += $row['four'];
		$MontCuredent += $row['curedent'];
		$MontCuillere += $row['cuillere'];
		$MontRechaud += $row['rechaud'];
		$MontAutre += $row['autre'];

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
			PDF_create_bookmark($pdf, $facan->phrasebook[3].$np, "");

			pdf_rotate($pdf, 90);
			pdf_translate($pdf, $MargeBottom, $MargeRight - $LargeurPage); # Positionne le repère au point bas-gauche

			#### Entete de Page  ########################################
			#															#
			
				# illu
				$logobig = pdf_load_image($pdf, "png", $_SERVER["DOCUMENT_ROOT"]."/print/illus/logoPrint.png", "");
				$haut = PDF_get_value($pdf, "imageheight", $logobig) * 0.3; # Calcul de la hauteur
				pdf_place_image($pdf, $logobig, 5, $LargeurUtile - $haut, 0.3);
				
				# Titre
				pdf_setfont($pdf, $TimesBold, 18);
				pdf_set_value ($pdf, "leading", 18);
				pdf_show_boxed($pdf, $facan->phrasebook[24].' ('.$iddocu.' )' , 228 , 502 , 525, 25 , 'center', ""); 		# Titre
				pdf_rect($pdf, 200, 500, 581, 30);			#
				pdf_stroke($pdf);

				# Sujet et client
				pdf_setfont($pdf, $TimesRoman, 12);
				pdf_set_value ($pdf, "leading", 17);

				pdf_show_boxed($pdf, $referencejob."\r".$facan->phrasebook[14].utf8_decode($facan->agent) , 200 , 460 , 295, 40 , 'left', ""); 		# Titre

				pdf_show_boxed($pdf, $facan->phrasebook[25].$facan->idclient." ".utf8_decode($facan->societe)."\r PO : ".$row['boncommande'] , 500 , 460 , 279, 40 , 'right', "");
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


				pdf_rect($pdf, 465 , $tab , 280, $hl); # Divers
				pdf_stroke($pdf);
				pdf_setfont($pdf, $TimesBold, 9);
				pdf_set_value ($pdf, "leading", 10);
				pdf_show_boxed($pdf, $facan->phrasebook[121] , 465 , $tabt , 280, $ht , 'center', ""); # divers

				# Ligne titre 2
			$tab = 420;
			$tabt = 422;
			$ht = 13;
			$hl = 15;

				pdf_rect($pdf, 0 , $tab , 40, $hl); # date
				pdf_rect($pdf, 40 , $tab , 78, $hl); # BC
				pdf_rect($pdf, 118 , $tab , 59, $hl); # job
				pdf_rect($pdf, 177 , $tab , 88, $hl); # lieu

				pdf_rect($pdf, 265 , $tab , 40, $hl); # h fac
				pdf_rect($pdf, 305 , $tab , 40, $hl); # km
				pdf_rect($pdf, 345 , $tab , 40, $hl); # frais
				pdf_rect($pdf, 385 , $tab , 40, $hl); # stand

				pdf_rect($pdf, 425 , $tab , 40, $hl); # livraison
				pdf_rect($pdf, 465 , $tab , 40, $hl); # gobelet
				pdf_rect($pdf, 505 , $tab , 40, $hl); # serviettes
				pdf_rect($pdf, 545 , $tab , 40, $hl); # four
				pdf_rect($pdf, 585 , $tab , 40, $hl); # curedent
				pdf_rect($pdf, 625 , $tab , 40, $hl); # cuilleres
				pdf_rect($pdf, 665 , $tab , 40, $hl); # rechaud
				pdf_rect($pdf, 705 , $tab , 40, $hl); # autres
				pdf_rect($pdf, 745 , $tab , 35, $hl); # Dimona
				pdf_stroke($pdf);

				pdf_setfont($pdf, $TimesBold, 9);
				pdf_set_value ($pdf, "leading", 10);

				pdf_show_boxed($pdf, $facan->phrasebook[26] , 0 , $tabt , 38, $ht , 'center', ""); # date
				pdf_show_boxed($pdf, $facan->phrasebook[29] , 40 , $tabt , 78, $ht , 'center', ""); # BC
				pdf_show_boxed($pdf, $facan->phrasebook[27].'/ '.$facan->phrasebook[72] , 118 , $tabt , 59, $ht , 'center', ""); # job
				pdf_show_boxed($pdf, $facan->phrasebook[28] , 177 , $tabt , 88, $ht , 'center', ""); #lieu
				pdf_show_boxed($pdf, $facan->phrasebook[30] , 265 , $tabt , 40, $ht , 'center', ""); # h fac
				pdf_show_boxed($pdf, $facan->phrasebook[31] , 305 , $tabt , 40, $ht , 'center', ""); # km
				pdf_show_boxed($pdf, $facan->phrasebook[32] , 345 , $tabt , 40, $ht , 'center', ""); # frais
				pdf_show_boxed($pdf, $facan->phrasebook[33] , 385 , $tabt , 40, $ht , 'center', ""); # stand

				pdf_show_boxed($pdf, $facan->phrasebook[34] , 425 , $tabt , 40, $ht , 'center', ""); # livraison
				pdf_show_boxed($pdf, $facan->phrasebook[35] , 465 , $tabt , 40, $ht , 'center', ""); # gobelet
				pdf_show_boxed($pdf, $facan->phrasebook[36] , 505 , $tabt , 40, $ht , 'center', ""); # serviettes
				pdf_show_boxed($pdf, $facan->phrasebook[37] , 545 , $tabt , 40, $ht , 'center', ""); # four
				pdf_show_boxed($pdf, $facan->phrasebook[38] , 585 , $tabt , 40, $ht , 'center', ""); # cure
				pdf_show_boxed($pdf, $facan->phrasebook[39] , 625 , $tabt , 40, $ht , 'center', ""); # cuilleres
				pdf_show_boxed($pdf, $facan->phrasebook[40] , 665 , $tabt , 40, $ht , 'center', ""); # rechaud
				pdf_show_boxed($pdf, $facan->phrasebook[41] , 705 , $tabt , 40, $ht , 'center', ""); # autres
				pdf_show_boxed($pdf, $facan->phrasebook[68] , 745 , $tabt , 35, $ht , 'center', ""); # Dimona

			$tab = $tab - $hl;
			$tabt = $tabt - $hl;

			}
	### lignes #####
	#
				# Ligne Contenu
				pdf_rect($pdf, 0 , $tab , 40, $hl); # date
				pdf_rect($pdf, 40 , $tab , 78, $hl); # BC
				pdf_rect($pdf, 118 , $tab , 59, $hl); # job
				pdf_rect($pdf, 177 , $tab , 88, $hl); # lieu
				pdf_rect($pdf, 265 , $tab , 40, $hl); # h fac
				pdf_rect($pdf, 305 , $tab , 40, $hl); # km
				pdf_rect($pdf, 345 , $tab , 40, $hl); # frais
				pdf_rect($pdf, 385 , $tab , 40, $hl); # stand
				
				pdf_rect($pdf, 425 , $tab , 40, $hl); # livraison
				pdf_rect($pdf, 465 , $tab , 40, $hl); # gobelet
				pdf_rect($pdf, 505 , $tab , 40, $hl); # serviettes
				pdf_rect($pdf, 545 , $tab , 40, $hl); # four
				pdf_rect($pdf, 585 , $tab , 40, $hl); # curedent
				pdf_rect($pdf, 625 , $tab , 40, $hl); # cuilleres
				pdf_rect($pdf, 665 , $tab , 40, $hl); # rechaud
				pdf_rect($pdf, 705 , $tab , 40, $hl); # autres
				pdf_rect($pdf, 745 , $tab , 35, $hl); # Dimona

				pdf_stroke($pdf);

			pdf_setfont($pdf, $TimesRoman, 7);
			pdf_set_value ($pdf, "leading", 10);

				pdf_show_boxed($pdf, fdate($row['datem']) , 0 , $tabt , 38, $ht , 'center', ""); # date
				pdf_show_boxed($pdf, $row['boncommande'] , 40 , $tabt , 78, $ht , 'center', ""); # BC
				pdf_show_boxed($pdf, $row['idanimjob'].' / '.$row['idanimation'] , 118 , $tabt , 59, $ht , 'center', ""); # job
				pdf_show_boxed($pdf, $row['societe'].' '.$row['ville'] , 177 , $tabt , 88, $ht , 'center', ""); #lieu
				if ($row['ferie'] > 100) {
					pdf_show_boxed($pdf, $anim->hprest.' ('.fnbr($row['ferie']).'%)' , 265 , $tabt , 40, $ht , 'center', ""); # h fac
				} else {
					pdf_show_boxed($pdf, $anim->hprest , 265 , $tabt , 40, $ht , 'center', ""); # h fac
				}
				pdf_show_boxed($pdf, fnbr($row['kmfacture']) , 305 , $tabt , 40, $ht , 'center', ""); # km
				pdf_show_boxed($pdf, fpeuro($row['montantfacture']) , 345 , $tabt , 40, $ht , 'center', ""); # frais
				pdf_show_boxed($pdf, fnbr($row['stand']) , 385 , $tabt , 40, $ht , 'center', ""); # stand
				
				pdf_show_boxed($pdf, fpeuro($row['livraisonfacture']) , 	425 , $tabt , 40, $ht , 'center', ""); # livraison
				pdf_show_boxed($pdf, fpeuro($row['gobelet']) , 			465 , $tabt , 40, $ht , 'center', ""); # gobelet
				pdf_show_boxed($pdf, fpeuro($row['serviette']) , 			505 , $tabt , 40, $ht , 'center', ""); # serviettes
				pdf_show_boxed($pdf, fpeuro($row['four']) , 				545 , $tabt , 40, $ht , 'center', ""); # four
				pdf_show_boxed($pdf, fpeuro($row['curedent']) , 			585 , $tabt , 40, $ht , 'center', ""); # cure
				pdf_show_boxed($pdf, fpeuro($row['cuillere']) ,			625 , $tabt , 40, $ht , 'center', ""); # cuilleres
				pdf_show_boxed($pdf, fpeuro($row['rechaud']) , 			665 , $tabt , 40, $ht , 'center', ""); # rechaud
				pdf_show_boxed($pdf, fpeuro($row['autre']) , 				705 , $tabt , 40, $ht , 'center', ""); # autres
				pdf_show_boxed($pdf, fpeuro($anim->FraisDimona) , 			745 , $tabt , 35, $ht , 'center', ""); # Dimona

				$tab = $tab - $hl;
				$tabt = $tabt - $hl;

	#
	### lignes #####

			if (($tour == $jump) or ($turntot == $dernier)) {

				if ($turntot == $dernier) {

			# Lignes Totaux
			$tab = $tab - $hl;
			$tabt = $tabt - $hl;

				# Ligne Contenu
				pdf_rect($pdf, 265 , $tab , 40, $hl); # h fac
				pdf_rect($pdf, 305 , $tab , 40, $hl); # km
				pdf_rect($pdf, 345 , $tab , 40, $hl); # frais
				pdf_rect($pdf, 385 , $tab , 40, $hl); # stand
				
				pdf_rect($pdf, 425 , $tab , 40, $hl); # livraison
				pdf_rect($pdf, 465 , $tab , 40, $hl); # gobelet
				pdf_rect($pdf, 505 , $tab , 40, $hl); # serviettes
				pdf_rect($pdf, 545 , $tab , 40, $hl); # four
				pdf_rect($pdf, 585 , $tab , 40, $hl); # curedent
				pdf_rect($pdf, 625 , $tab , 40, $hl); # cuilleres
				pdf_rect($pdf, 665 , $tab , 40, $hl); # rechaud
				pdf_rect($pdf, 705 , $tab , 40, $hl); # autres
				pdf_rect($pdf, 745 , $tab , 35, $hl); # Dimona
				pdf_stroke($pdf);

			pdf_setfont($pdf, $TimesBold, 7);
			pdf_set_value ($pdf, "leading", 10);

				pdf_show_boxed($pdf, $facan->phrasebook[48] , 177 , $tabt , 88, $ht , 'center', ""); #txt : Total
				pdf_show_boxed($pdf, $DetHeures , 265 , $tabt , 40, $ht , 'center', ""); # h fac
				pdf_show_boxed($pdf, fnbr($DetKm) , 305 , $tabt , 40, $ht , 'center', ""); # km
				pdf_show_boxed($pdf, fpeuro($MontFrais) , 345 , $tabt , 40, $ht , 'center', ""); # frais
				pdf_show_boxed($pdf, fnbr($DetStand) , 385 , $tabt , 40, $ht , 'center', ""); # stand
				
				pdf_show_boxed($pdf, fpeuro($MontLivraison) , 	425 , $tabt , 40, $ht , 'center', ""); # livraison
				pdf_show_boxed($pdf, fpeuro($MontGobelets) , 	465 , $tabt , 40, $ht , 'center', ""); # gobelet
				pdf_show_boxed($pdf, fpeuro($MontServiette) , 	505 , $tabt , 40, $ht , 'center', ""); # serviettes
				pdf_show_boxed($pdf, fpeuro($MontFour) , 		545 , $tabt , 40, $ht , 'center', ""); # four
				pdf_show_boxed($pdf, fpeuro($MontCuredent) , 	585 , $tabt , 40, $ht , 'center', ""); # cure
				pdf_show_boxed($pdf, fpeuro($MontCuillere) , 	625 , $tabt , 40, $ht , 'center', ""); # cuilleres
				pdf_show_boxed($pdf, fpeuro($MontRechaud) , 	665 , $tabt , 40, $ht , 'center', ""); # rechaud
				pdf_show_boxed($pdf, fpeuro($MontAutre) , 		705 , $tabt , 40, $ht , 'center', ""); # autres
				pdf_show_boxed($pdf, fpeuro($facan->MontDimona) , 	745 , $tabt , 35, $ht , 'center', ""); # Dimona

		#															#
		#### Corps de Page  #########################################

				}

		#### Pied de Page    ########################################
		#															#
			#date
			pdf_setfont($pdf, $TimesRoman, 9);
			pdf_show_boxed($pdf, $facan->phrasebook[50].date("d/m/Y").$facan->phrasebook[63].date("H:i:s") ,0 ,30 , 200, 15, 'left', ""); #texte du commentaire

			# Ligne de bas de page
			pdf_moveto($pdf, 0, 30);
			pdf_lineto($pdf, $HauteurUtile, 30);
			pdf_stroke($pdf); # Ligne de bas de page


			# CoordonnÈes Exception
			pdf_setfont($pdf, $TimesRoman, 10);

			pdf_show_boxed($pdf, $facan->phrasebook[20] ,0 ,-10 , $HauteurUtile / 3, 40, 'center', ""); #texte du commentaire
			pdf_show_boxed($pdf, $facan->phrasebook[21] , $HauteurUtile / 3,-10 , $HauteurUtile / 3,40, 'center', ""); #texte du commentaire
			pdf_show_boxed($pdf, $facan->phrasebook[22] , $HauteurUtile * 2 / 3 ,-10 , $HauteurUtile / 3, 40, 'center', ""); #texte du commentaire
		#															#
		#### Pied de Page    ########################################

			pdf_end_page($pdf);

			$tour = 0;
			}
		$tour++;
		$turntot++;
	}

		unset ($DetHeures);
		unset ($DetKm);
 ?>