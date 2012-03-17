<?php
### Declaration des fontes
$TimesItalic = PDF_load_font($pdf, "Times-Italic", "host", "");
$Helvetica = PDF_load_font($pdf, "Helvetica", "host", "");
$HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");
$HelveticaOblique = PDF_load_font($pdf, "Helvetica-Oblique", "host", "");

$tour = 1;
$jump = 18;
$jobquid2 = $jobquid.' AND j.idcofficer = '.$infos['idcofficer'].' ';
$irow = 0; # mission : ligne sql en cours
$prodrow = 0; # produit : ligne sql en cours

$first = 0;

################### Get infos du job ########################
$det = new db();
$det->inline("SET NAMES latin1");
$det->inline('SELECT 
			an.idanimation, an.datem, an.peoplenote, an.weekm, YEAR(an.datem) as yearm, an.autreanimnote, an.shopnote, an.standnote,
			c.societe,
			co.qualite, co.onom, co.oprenom, co.fax, co.langue,
			j.idcofficer, j.idagent, j.boncommande,
			s.societe AS ssociete, s.ville AS sville ,
			a.prenom, a.nom, a.atel, a.agsm,
			p.pnom, p.pprenom
		FROM animation an
			LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
			LEFT JOIN agent a ON j.idagent = a.idagent
			LEFT JOIN client c ON j.idclient = c.idclient
			LEFT JOIN cofficer co ON j.idcofficer = co.idcofficer
			LEFT JOIN shop s ON an.idshop = s.idshop
			LEFT JOIN people p ON an.idpeople = p.idpeople
		'.$jobquid2.'
		ORDER BY co.idcofficer, an.idpeople, an.datem');

################### Fin infos du job ########################

while ($row = mysql_fetch_array($det->result))
{
	$saut = 'oui'; # pour find de page finale
	$produit = new db();
	$produit->inline("SET NAMES latin1");
	
	$produit->inline("SELECT * FROM `animproduit` WHERE `idanimation` = ".$row['idanimation']);
	$FoundCount = mysql_num_rows($produit->result); # Le nombre de produit par mission
	if ($retour == 'retour') { # a faire # mise à la bonne ligne après coupure sur page d'avant
		$FoundCount = $FoundCount - $prodrow ;
	}

	####### Pour Entete de Page ET la première ligne de titre
		if ($tour == 1) {
			switch($row['langue']) {
				case"FR":
					include "fr.php";
				break;
				case"NL":
					include "nl.php";
				break;
			}

			$np++; # Nuro de page
			pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
			PDF_create_bookmark($pdf, $phrase[3].$np, "");

			pdf_rotate($pdf, 90);
			pdf_translate($pdf, $MargeBottom, $MargeRight - $LargeurPage); # Positionne le repère au point bas-gauche

			#### Entete de Page  ########################################
				# illu
				if ($_GET['web'] == 'web') {
					$logobig = PDF_load_image($pdf, "png", NIVO."print/illus/logoPrint.png", "");
				} else {
					$logobig = PDF_load_image($pdf, "png", "../illus/logoPrint.png", "");
				}
				$haut = PDF_get_value($pdf, "imagewidth", $logobig) * 0.025; # Calcul de la hauteur
				pdf_place_image($pdf, $logobig, 5, 500, 0.2);

				# Titre
				pdf_setfont($pdf, $HelveticaBold, 28); pdf_set_value ($pdf, "leading", 28);
				pdf_show_boxed($pdf, $phrase[1] , 140 , 520 , 200, 28 , 'left', ""); 		# Titre
				pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, "leading", 12);
				# Client
				pdf_show_boxed($pdf, $phrase[2] , 235 , 520 , 100, 15 , 'right', ""); 		# Titre
				pdf_show_boxed($pdf, $row['societe'] , 350 , 520 , 325, 15 , 'left', "");# Titre
				# Contact
				pdf_show_boxed($pdf, $phrase[3] , 235 , 500 , 100, 15 , 'right', ""); 		# Titre
				pdf_show_boxed($pdf, $row['qualite']." ".$row['onom']." ".$row['oprenom']." (".$row['idcofficer'].")" , 350 , 500 , 400, 15 , 'left', ""); 		# Titre
				# Fax
				pdf_show_boxed($pdf, $phrase[4] , 235 , 480 , 100, 15 , 'right', ""); 		# Titre
				pdf_show_boxed($pdf, $row['fax'] , 350 , 480 , 300, 15 , 'left', ""); 		# Titre

				pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, $phrase[5].$row['weekm']." / ".$row['yearm'] , 140 , 490 , 200, 20 , 'left', ""); 		# Titre
				pdf_setlinewidth($pdf, 0.5);
				pdf_rect($pdf, 270, 470, 500, 75);			#
				pdf_stroke($pdf);
				# Titre
			#### Entete de Page  ########################################

			if ($first == 0) {
				pdf_setfont($pdf, $TimesItalic, 14); pdf_set_value ($pdf, "leading", 16);
				pdf_show_boxed($pdf, $phrase[8].$row['qualite']." ".$row['onom'].$phrase[9] , 50 , 430 , $HauteurUtile - 20, 40 , 'left', "");

				$first = 1;

				$tab = 415;
				$tabt = 418;
			} else {
				$tab = 435;
				$tabt = 438;
			}

			#### Corps de Page  #########################################
			# Ligne titre 1 (2)
			$ht = 12;
			$hl = 15;
			$ho = 9;

			### Gris de remplissage des TITRES #########################################
				pdf_setcolor($pdf, "fill", "gray", 0.8, 0, 0 ,0);
				pdf_rect($pdf, 0 , $tab , 60, $hl);
				pdf_rect($pdf, 60 , $tab , 150, $hl);
				pdf_rect($pdf, 210 , $tab , 150, $hl);
				pdf_rect($pdf, 360 , $tab , 150, $hl);
				pdf_rect($pdf, 510 , $tab , 70, $hl);
				pdf_rect($pdf, 580 , $tab , 70, $hl);
				pdf_rect($pdf, 650 , $tab , 60, $hl);
				pdf_rect($pdf, 710 , $tab , 60, $hl);
				pdf_fill_stroke($pdf);
				# fin = 770
				pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);
			#/## Gris de remplissage des TITRES #########################################

			### Texte de remplissage des TITRES #########################################
			pdf_setfont($pdf, $HelveticaBold, 9);
			pdf_set_value ($pdf, "leading", 10);
			pdf_show_boxed($pdf, "Date" , 5 , $tabt , 55, $ht , 'center', "");
			pdf_show_boxed($pdf, "Place" , 65 , $tabt , 140, $ht , 'center', "");
			pdf_show_boxed($pdf, "Name" , 215 , $tabt , 140, $ht , 'center', "");
			pdf_show_boxed($pdf, "Product" , 365 , $tabt , 140, $ht , 'center', "");
			pdf_show_boxed($pdf, "units" , 515 , $tabt , 60, $ht , 'center', "");
			pdf_show_boxed($pdf, "Quantity sold" , 585 , $tabt , 60, $ht , 'center', "");
			pdf_show_boxed($pdf, "Free" , 655 , $tabt , 50, $ht , 'center', "");
			pdf_show_boxed($pdf, "Out of stock" , 715 , $tabt , 50, $ht , 'center', "");
			#/## Texte de remplissage des TITRES #########################################
			$tab = $tab - $hl;
			$tabt = $tabt - $hl;
			$tab0 = $tab + 2;

		}
	####### Pour Entete de Page ET la première ligne de titre

	#### Calcul de la place libre en bas de page : fin de la zone = 50 et calcul de place utile
		pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, "leading", 10);

		$peopnote = str_replace("\r\n", " ", $row['peoplenote']);
		$nlig = ceil(pdf_stringwidth($pdf, $peopnote, $Helvetica, 9) / 365);
		if ($nlig == 0) $nlig = 1;

		$placelibre = $tab - 50; # hauteur en cours - pied de page
		$placeutil = ($FoundCount * $hl) + ($hl * ($nlig - 1)); # +15 de marge pour ligne de récap # nombre de produit * la hauteur d'une ligne
		$lignelibre0 = ($placelibre / $hl); # (hauteur en cours - pied de page) / la hauteur d'une ligne
		$lignelibre = floor($lignelibre0);

		if ($placelibre < $placeutil) {
			$coupure = 1; # passage à autre page est necessaire
			$FoundCount = $lignelibre ;

		} # Il va falloir couper la mission en deux pages
		else {
			$coupure = 0; # passage à autre page non necessaire
		}
	#/### Calcul de la place libre en bas de page

	### lignes de MISSION ET PRODUIT #####
		$ip = 0; # compteur de produits
		if ($retour != 'retour') { # a faire # mise à la bonne ligne après coupure sur page d'avant
			$ventestot = 0; # mise à zéro du total des ventes
			$prodrow = 0;
		}
			if ($retour == 'retour') { # a faire # mise à la bonne ligne après coupure sur page d'avant
				mysql_data_seek($produit->result, $prodrow);
				$retour = 'non';
			}
		while ($prod = mysql_fetch_array($produit->result))
		{
			$ip++; # compteur de produits
			if ($lignelibre < $ip) {
				$retour = 'retour';
				$tab = 80;
			} else {
				$prodrow++;
				### Première ligne : Dessin du cadre autours des infos mission : taille = taille de tous les produit
					if ($ip == 1) {
						$tabplus = $tab - ($FoundCount * $hl) + $hl;
						$hlplus = $FoundCount * $hl;
						# Ligne Contenu
						pdf_rect($pdf, 0 , $tabplus , 60, $hlplus);
						pdf_rect($pdf, 60 , $tabplus , 150, $hlplus);
						pdf_rect($pdf, 210 , $tabplus , 150, $hlplus);
						pdf_stroke($pdf);
					}
				#/## Première ligne : Dessin du cadre autours des infos mission : taille = taille de tous les produit
				### toutes les lignes : Dessin du cadre autours des infos produits
					pdf_rect($pdf, 360 , $tab , 150, $hl);
					pdf_rect($pdf, 510 , $tab , 70, $hl);
					pdf_rect($pdf, 580 , $tab , 70, $hl);
					pdf_rect($pdf, 650 , $tab , 60, $hl);
					pdf_rect($pdf, 710 , $tab , 60, $hl);
					pdf_rect($pdf, 735 , $tab0 , 10, $ho); # pour le "X" de out of stock
					pdf_stroke($pdf);
				### toutes les lignes : Dessin du cadre autours des infos produits

				pdf_setfont($pdf, $Helvetica, 9);
				pdf_set_value ($pdf, "leading", 10);

				### Première ligne : Texte infos mission : date shop et promoboy
					if ($ip == 1) {
						pdf_show_boxed($pdf, fdate($row['datem']) , 5 , $tabt , 55, $ht , 'left', "");
						pdf_show_boxed($pdf, $row['ssociete']." - ".$row['sville'] , 65 , $tabt , 140, $ht , 'left', "");
						pdf_show_boxed($pdf, $row['pprenom']." ".$row['pnom'] , 215 , $tabt , 140, $ht , 'left', "");
					}
				#/## Première ligne : Texte infos mission : date shop et promoboy

				### toutes les lignes : info produit et nombres
					pdf_show_boxed($pdf, $prod['types'] , 365 , $tabt , 140, $ht , 'left', "");
					pdf_show_boxed($pdf, $prod['unite'] , 515 , $tabt , 60, $ht , 'left', "");
					pdf_show_boxed($pdf, fnbr($prod['ventes']) , 585 , $tabt , 40, $ht , 'right', "");
					$ventestot += fnbr($prod['ventes']);
					pdf_show_boxed($pdf, fnbr($prod['degustation']) , 655 , $tabt , 50, $ht , 'center', "");
					if ($prod['produitno'] == 'no') {
						pdf_show_boxed($pdf, "x" , 735 , $tab0 , 10, $ht , 'center', "");
					}
					$tab = $tab - $hl;
					$tabt = $tabt - $hl;
					$tab0 = $tab + 2;
				#/## toutes les lignes : info produit et nombres

				### ligne supplémentaire après le dernier produit : Note promoboy + Total des ventes
					if (($ip == $FoundCount) and ($coupure != 1)) {
						$row['peoplenote'] = str_replace("\r\n", " ", $row['peoplenote']);
						$nbligne = ceil(pdf_stringwidth($pdf,$row['peoplenote'], $Helvetica, 9) / 365);

						if ($nbligne == 0) $nbligne = 1;

						if ($nbligne > 1) {
							$tab -= ($hl * ($nbligne - 1));
							$tabt -= ($hl * ($nbligne - 1));
						}

						$tabplus = $tab - ($FoundCount * $hl) + $hl;
						$hlplus = $FoundCount * $hl;
						# Ligne Contenu

						pdf_setcolor($pdf, "fill", "gray", 0.9, 0, 0 ,0);
						pdf_rect($pdf, 0 , $tab , 60, $hl * $nbligne);
						pdf_rect($pdf, 60 , $tab , 150, $hl * $nbligne);
						pdf_rect($pdf, 210 , $tab , 370, $hl * $nbligne);
						pdf_rect($pdf, 580 , $tab , 70, $hl * $nbligne);
						pdf_rect($pdf, 650 , $tab , 60, $hl * $nbligne);
						pdf_rect($pdf, 710 , $tab , 60, $hl * $nbligne);
						pdf_fill_stroke($pdf);						#
						pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);
						pdf_show_boxed($pdf, '( '.$row['idanimation'].' ) ' , 5 , $tabt , 55, $ht , 'left', "");
						pdf_show_boxed($pdf, 'PO: '.$row['boncommande'] , 65 , $tabt , 145, $ht , 'left', "");
						pdf_show_boxed($pdf, $row['peoplenote'] , 215 , $tabt , 365, $ht * $nbligne , 'left', "");
						pdf_show_boxed($pdf, fnbr($ventestot) , 585 , $tabt , 40, $ht , 'right', "");

						$hl = 10;
						$ht = 9;

						## Notes autre anim
						if (!empty($row['autreanimnote'])) {
							$tab -= $hl;
							$tabt -= $hl;

							pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, "leading", 9);

							$txtligne = ceil(pdf_stringwidth($pdf,$row['autreanimnote'], $Helvetica, 9) / 700);

							if ($txtligne == 0) $txtligne = 1;

							if ($txtligne > 1) {
								$tab -= ($hl * ($txtligne - 1));
								$tabt -= ($hl * ($txtligne - 1));
							}

							pdf_rect($pdf, 0 , $tab , 60, $hl * $txtligne);
							pdf_rect($pdf, 60 , $tab , 710, $hl * $txtligne);
							pdf_stroke($pdf);						#
							pdf_setfont($pdf, $HelveticaBold, 6); pdf_set_value ($pdf, "leading", 8);
							pdf_show_boxed($pdf, $phrase[12] , 0 , $tabt - 1, 60, $ht * $txtligne , 'center', "");
							pdf_setfont($pdf, $HelveticaOblique, 7); pdf_set_value ($pdf, "leading", 8);
							pdf_show_boxed($pdf, $row['autreanimnote'] , 65 , $tabt - 1, 700, $ht * $txtligne , 'left', "");

						}
						## Notes magasin
						if (!empty($row['shopnote'])) {
							$tab -= $hl;
							$tabt -= $hl;

							pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, "leading", 9);

							$txtligne = ceil(pdf_stringwidth($pdf,$row['shopnote'], $Helvetica, 9) / 700);

							if ($txtligne == 0) $txtligne = 1;

							if ($txtligne > 1) {
								$tab -= ($hl * ($txtligne - 1));
								$tabt -= ($hl * ($txtligne - 1));
							}

							pdf_rect($pdf, 0 , $tab , 60, $hl * $txtligne);
							pdf_rect($pdf, 60 , $tab , 710, $hl * $txtligne);
							pdf_stroke($pdf);						#
							pdf_setfont($pdf, $HelveticaBold, 6); pdf_set_value ($pdf, "leading", 8);
							pdf_show_boxed($pdf, $phrase[10] , 0 , $tabt - 1, 60, $ht * $txtligne , 'center', "");
							pdf_setfont($pdf, $HelveticaOblique, 7); pdf_set_value ($pdf, "leading", 8);
							pdf_show_boxed($pdf, $row['shopnote'] , 65 , $tabt - 1, 700, $ht * $txtligne , 'left', "");

						}
						## Notes stand
						if (!empty($row['standnote'])) {
							$tab -= $hl;
							$tabt -= $hl;

							pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, "leading", 9);

							$txtligne = ceil(pdf_stringwidth($pdf,$row['standnote'], $Helvetica, 9) / 700);

							if ($txtligne == 0) $txtligne = 1;

							if ($txtligne > 1) {
								$tab -= ($hl * ($txtligne - 1));
								$tabt -= ($hl * ($txtligne - 1));
							}

							pdf_rect($pdf, 0 , $tab , 60, $hl * $txtligne);
							pdf_rect($pdf, 60 , $tab , 710, $hl * $txtligne);
							pdf_stroke($pdf);						#
							pdf_setfont($pdf, $HelveticaBold, 6); pdf_set_value ($pdf, "leading", 8);
							pdf_show_boxed($pdf, $phrase[11] , 0 , $tabt - 1, 60, $ht * $txtligne , 'center', "");
							pdf_setfont($pdf, $HelveticaOblique, 7); pdf_set_value ($pdf, "leading", 8);
							pdf_show_boxed($pdf, $row['standnote'] , 65 , $tabt - 1, 700, $ht * $txtligne , 'left', "");

						}

						$hl = 15;
						$ht = 12;

						$tab = $tab - $hl - 10;
						$tabt = $tabt - $hl - 10;
						$tab0 = $tab + 2;
						$ip++;
					}
				### ligne supplémentaire après le dernier produit : Note promoboy + Total des ventes
			}
		}
	#/## lignes de MISSION ET PRODUIT #####
	if ($ip == 0) { $tour++;} else {$tour += $ip;} # correction du compteur de ligne misssion en ajoutant le compteur de produits, ou +1 si misssion vide de produit

	### Pied de page naturel, nécessaire parce que compteur #####
		if ($tab <= 80) {
			$saut = 'non'; # pour fin de page finale, pas besoin de forcer la création d'un bas de page
			#### Pied de Page si trop long   ########################################
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
				pdf_show_boxed($pdf, "\rBTW BE 0430 597 846 TVA\rHCB 489 589 RCB" , $HauteurUtile / 3,-10 , $HauteurUtile / 3,40, 'center', ""); #texte du commentaire
				pdf_show_boxed($pdf, "www.exception2.be\r\rTel : 02 732.74.40\rFax : 02 732.79.38" , $HauteurUtile * 2 / 3 ,-10 , $HauteurUtile / 3, 40, 'center', ""); #texte du commentaire
			#### Pied de Page    ########################################

			pdf_end_page($pdf);
			$tour = 1;
		}
	#/## Pied de page naturel, nécessaire parce que compteur #####

	#### Calcul de la place libre en bas de page : fin de la zone = 80 et calcul de place utile
		if ($coupure == 1) {
			 mysql_data_seek($det->result, $irow);
		} # Il va falloir couper la mission en deux pages
		else {
			$irow++; # la ligne de résultat sql pour les missions
		}
	#/### Calcul de la place libre en bas de page


$infa = $row['prenom']." ".$row['nom']." \r ".$row['atel']." - ".$row['agsm'];
$infp = $row['idagent'];

}# fin du while des mission

### Pied de page NON naturel, nécessaire parce que compteur en milieu de page #####
	if ($saut == 'oui') { # pour find de page finale

		#### Politesse  #############################################
		#
			$tab -= 40;

			pdf_setfont($pdf, $TimesItalic, 14); pdf_set_value ($pdf, "leading", 16);
			pdf_show_boxed($pdf, $phrase[18] , 20 , $tab , $HauteurUtile - 20, 50 , 'left', "");

			#### Signature Planner  ########################################
                if (!file_exists($_SERVER["DOCUMENT_ROOT"].'/print/illus/signature/'.$infp.'.jpg')) {
                    echo utf8_encode("<br>fichier signature non trouvé pour :".$infp);
                } else {
                    $signature = $_SERVER["DOCUMENT_ROOT"].'/print/illus/signature/'.$infp.'.jpg';
                    $signature = PDF_load_image($pdf, "jpeg", $signature, "");
                    pdf_place_image($pdf, $signature, $HauteurUtile - 200, $tab - 35, 0.36);	
                }
			#															#
			pdf_set_value ($pdf, "leading", 14);
			pdf_show_boxed($pdf, $phrase[19].$infa, $HauteurUtile - 400, $tab - 35, 180, 52, 'right', ""); # Titre

		#															#
		#### Politesse  #############################################



		#### Pied de Page  Final  ########################################
			#date
			pdf_setfont($pdf, $Helvetica, 9);
			pdf_show_boxed($pdf, $phrase[50].date("d/m/Y")." à ".date("H:i:s") ,0 ,30 , 200, 15, 'left', ""); #texte du commentaire
			# Ligne de bas de page
			pdf_moveto($pdf, 0, 30);
			pdf_lineto($pdf, $HauteurUtile, 30);
			pdf_stroke($pdf); # Ligne de bas de page
			# Coordonnées Exception
			pdf_setfont($pdf, $Helvetica, 9);pdf_set_value ($pdf, "leading", 10);
			pdf_show_boxed($pdf, "Exception - Exception scrl\r\rJachtlaan 195 Av. de la Chasse\rBrussel - 1040 - Bruxelles" ,0 ,-10 , $HauteurUtile / 3, 40, 'center', ""); #texte du commentaire
			pdf_show_boxed($pdf, "\rBTW BE 0430 597 846 TVA\rHCB 489 589 RCB" , $HauteurUtile / 3,-10 , $HauteurUtile / 3,40, 'center', ""); #texte du commentaire
			pdf_show_boxed($pdf, "www.exception2.be\r\rTel : 02 732.74.40\rFax : 02 732.79.38" , $HauteurUtile * 2 / 3 ,-10 , $HauteurUtile / 3, 40, 'center', ""); #texte du commentaire
		pdf_end_page($pdf);
		#### fin Pied de Page  Anticipé  ########################################
	}
 ?>