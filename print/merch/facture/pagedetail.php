<?php 
### Declaration des fontes
$Helvetica = PDF_load_font($pdf, "Helvetica", "host", "");
$HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");

	$DB->inline("SET NAMES latin1");
	$rows = $DB->getArray("SELECT 
		me.idmerch, me.datem, me.genre, me.hin1, me.hout1, me.hin2, me.hout2, me.idclient,
		me.kmpaye, me.kmfacture, me.boncommande, me.ferie, 
		me.livraison, me.diversfrais, me.produit, 
		a.nom, a.prenom, 
		s.idshop, s.societe, s.ville,
		nf.montantfacture
		FROM merch me
		LEFT JOIN agent a ON me.idagent = a.idagent 
		LEFT JOIN shop s ON me.idshop = s.idshop
		LEFT JOIN notefrais nf ON nf.secteur = 'ME' AND nf.idmission = me.idmerch 
		WHERE me.".$fac->facnum." = ".$fac->id."
		ORDER BY me.idclient, s.societe, s.ville, me.datem");

	$dernier = count($rows);
	$reste = $dernier;
	
	## init vars
	$tour = 1;
	$turntot = 1;
	
	$idpos = 0; # séparation des POS
	$DetHeures = 0;
	$DetKm = 0;
	$poshprest = 0;
	$poskmfacture = 0;
	$posfraisfacture = 0;
	$posdiversfrais = 0;
	$poslivraison = 0;
	
	$dim = array(
		0 => array('l' =>  47, 'a' => 'center', 'ph' =>  26),
		1 => array('l' =>  40, 'a' => 'center', 'ph' =>  29),
		2 => array('l' =>  50, 'a' => 'center', 'ph' =>  27),
		3 => array('l' =>  88, 'a' => 'center', 'ph' =>  28),
		4 => array('l' =>  40, 'a' => 'center', 'ph' =>  30),
		5 => array('l' =>  40, 'a' => 'center', 'ph' =>  31),
		6 => array('l' =>  45, 'a' => 'center', 'ph' =>  32),
		7 => array('l' =>  45, 'a' => 'center', 'ph' => 121),
		8 => array('l' =>  45, 'a' => 'center', 'ph' =>  34),
		9 => array('l' =>  40, 'a' => 'center', 'ph' =>  74),
	   10 => array('l' => 300, 'a' => 'center', 'ph' =>  35)
	);
	
	$cadreshow = array(4, 5, 6, 7, 8, 9);

	foreach ($rows as $row) {
		### mise à zero des valeurs pour EAS
		if ($row['genre'] == 'EAS')
		{
			$row['ferie'] = 100 ;
			if ($row['idclient'] != 2316) $row['kmfacture'] = 0 ; ## garde les KM pour champion (client 2316)
			$row['kmpaye'] = 0 ;
			$row['montantfacture'] = 0 ;
			$row['diversfrais'] = 0 ;
			$row['livraison'] = 0 ;
		}
		#/## mise à zero des valeurs pour EAS

		$merch = new coremerch($row['idmerch']);

		$datas = array(
			0 => fdate($row['datem']),
			1 => $row['boncommande'],			
			2 => $row['idmerch'],
			3 => $row['societe'].' '.$row['ville'],
			4 => $merch->hprest,
			5 => fnbr($row['kmfacture']),
			6 => fpeuro($row['montantfacture']),
			7 => fpeuro($row['diversfrais']),
			8 => fpeuro($row['livraison']),
			9 => fpeuro($merch->FraisDimona),
		   10 => $row['produit']
		);

		## incrémentation des totaux H et KM pour print
		$DetHeures += $merch->hprest;
		$DetKm += $row['kmfacture'];

		if ($tour == 1) {
		
			# ##### change le changement de page pour que les totaux ne recouvrent pas le bas de page. 
			if (($reste >= 21) and ($reste <= 24)) {
				$jump = 20;
			} else {
				$jump = 24;
			}
			$reste = $reste - 24;
			# #######

			$np++;
			pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
			PDF_create_bookmark($pdf, $phrase[3].$np, "");
			
			pdf_rotate($pdf, 90);
			pdf_translate($pdf, $MargeBottom, $MargeRight - $LargeurPage); # Positionne le repère au point bas-gauche
			
			#### Entete de Page  ########################################
			#															#
				# illu
				$logobig = pdf_load_image($pdf, "png", $_SERVER["DOCUMENT_ROOT"]."/print/illus/logoPrint.png", "");

				$haut = PDF_get_value($pdf, "imageheight", $logobig) * 0.2; # Calcul de la hauteur
				pdf_place_image($pdf, $logobig, 5, $LargeurUtile - $haut - 20, 0.3);
				
				# Titre
				pdf_setfont($pdf, $HelveticaBold, 18);
				pdf_set_value ($pdf, "leading", 18);
				pdf_show_boxed($pdf, $phrase[24].' ('.ffac($fac->id).' )' , 228 , 502 , 525, 25 , 'center', ""); 		# Titre
				pdf_rect($pdf, 200, 500, 581, 30);			#
				pdf_stroke($pdf);
				
				# Sujet et client
				pdf_setfont($pdf, $Helvetica, 12);
				pdf_set_value ($pdf, "leading", 17);
				
				pdf_show_boxed($pdf, $phrase[4].utf8_decode($fac->intitule)."\r".$phrase[14].$row['prenom'].' '.$row['nom'] , 200 , 460 , 295, 40 , 'left', ""); 		# Titre

				pdf_show_boxed($pdf, $phrase[25].$fac->idclient." ".utf8_decode($fac->societe) , 500 , 480 , 279, 20 , 'right', ""); 		
			#															#
			#### Entete de Page  ########################################
			
			
			#### Corps de Page  #########################################
			#															#
				# Ligne titre 1
				$tab = 435;
				$tabt = 438;
				$ht = 17;
				$hl = 20;

				# Ligne titre 2	
				$tab = 420;
				$tabt = 422;
				$ht = 13;
				$hl = 15;
				
				pdf_setlinewidth($pdf, 0.5);

				## cadres titre
				$x = 0;
				foreach ($dim as $val) {
					pdf_rect($pdf, $x , $tab , $val['l'], $hl);
					$x += $val['l'];
				}

				pdf_stroke($pdf); 
		
				pdf_setfont($pdf, $HelveticaBold, 9);
				pdf_set_value ($pdf, "leading", 10);
	
				## textes titre
				$x = 0;
				foreach ($dim as $val) {
					pdf_show_boxed($pdf, $phrase[$val['ph']] , $x , $tabt , $val['l'], $ht , $val['a'], "");
					$x += $val['l'];
				}

				$tab -= $hl;	
				$tabt -= $hl;
			}
	### lignes #####
	#	


		#### Lignes sous-Totaux POS
		if (($idpos != $row['idshop']) and ($idpos != 0)) {
		
				# Ligne Contenu
				$x = 0;
				foreach ($dim as $key => $val) {
					if (in_array($key, $cadreshow)) pdf_rect($pdf, $x , $tab , $val['l'], $hl);
					$x += $val['l'];
				}

				pdf_stroke($pdf); 
		
				pdf_setfont($pdf, $HelveticaBold, 7);
				pdf_set_value ($pdf, "leading", 10);

				$datashow = array(
					3 => $phrase[47],
					4 => $poshprest,
					5 => fnbr($poskmfacture),
					6 => fpeuro($posfraisfacture),
					7 => fpeuro($posdiversfrais),
					8 => fpeuro($poslivraison),
				    9 => fpeuro($posdimona) 	
				);

				$x = 0;
				foreach ($dim as $key => $val) {
					if (array_key_exists($key, $datashow)) pdf_show_boxed($pdf, $datashow[$key] , $x , $tabt , $val['l'], $ht, $val['a'], "");
					$x += $val['l'];
				}

				# incr totaux
				$tothprest       += $poshprest;
				$totkmfacture    += $poskmfacture;
				$totfraisfacture += $posfraisfacture;
				$totdiversfrais  += $posdiversfrais;
				$totlivraison    += $poslivraison;
                $totdimona       += $posdimona;

				$poshprest = 0;
				$poskmfacture = 0;
				$posfraisfacture = 0;
				$posdiversfrais = 0;
				$poslivraison = 0;
				$posdimona = 0;

			$tab  -= ($hl +2);	
			$tabt -= ($hl +2);

			$tour++;
		#### Lignes sous-Totaux POS
		}

				# Ligne Contenu
				$x = 0;
				foreach ($dim as $val) {
					pdf_rect($pdf, $x , $tab , $val['l'], $hl);
					$x += $val['l'];
				}

				pdf_stroke($pdf); 
				
			pdf_setfont($pdf, $Helvetica, 7);
			pdf_set_value ($pdf, "leading", 10);
			
			# textes datas
			$x = 0;
			foreach ($dim as $key => $val) {
				pdf_show_boxed($pdf, $datas[$key] , $x , $tabt , $val['l'], $ht, $val['a'], "");
				$x += $val['l'];
			}

		#### séparation des POS 
			$poshprest += $merch->hprest;
			$poskmfacture += $row['kmfacture'];
			$posfraisfacture += $row['montantfacture'];
			$posdiversfrais += $row['diversfrais'];
			$poslivraison += $row['livraison'];
			$posdimona += $merch->FraisDimona;
		#/ ### séparation des POS 
		
			$tab  -= $hl;	
			$tabt -= $hl;	
			
	#			
	### lignes #####		
				
			if (($tour >= $jump) or ($turntot == $dernier)) {
			
				if ($turntot == $dernier) {

				#### Lignes sous-Totaux POS
					# Ligne Contenu
					$x = 0;
					foreach ($dim as $key => $val) {
						if (in_array($key, $cadreshow)) pdf_rect($pdf, $x , $tab , $val['l'], $hl);
						$x += $val['l'];
					}

					pdf_stroke($pdf); 
				
					pdf_setfont($pdf, $HelveticaBold, 7);
					pdf_set_value ($pdf, "leading", 10);
	
					$datashow = array(
						3 => $phrase[47],
						4 => $poshprest,
						5 => fnbr($poskmfacture),
						6 => fpeuro($posfraisfacture),
						7 => fpeuro($posdiversfrais),
						8 => fpeuro($poslivraison),
					    9 => fpeuro($posdimona) 	
					);

					$x = 0;
					foreach ($dim as $key => $val) {
						if (array_key_exists($key, $datashow)) pdf_show_boxed($pdf, $datashow[$key] , $x , $tabt , $val['l'], $ht, $val['a'], "");
						$x += $val['l'];
					}
					
					## incr des totaux
					$tothprest       += $poshprest;
					$totkmfacture    += $poskmfacture;
					$totfraisfacture += $posfraisfacture;
					$totdiversfrais  += $posdiversfrais;
					$totlivraison    += $poslivraison;
                    $totdimona       += $posdimona;

					$poshprest = 0;
					$poskmfacture = 0;
					$posfraisfacture = 0;
					$posdiversfrais = 0;
					$poslivraison = 0;
					$posdimona = 0;
		
					$tab  -= $hl;	
					$tabt -= $hl;
				#### Lignes sous-Totaux POS

					# Lignes Totaux
					$tab  -= $hl;	
					$tabt -= $hl;
		
					# Ligne Contenu
					$x = 0;
					foreach ($dim as $key => $val) {
						if (in_array($key, $cadreshow)) pdf_rect($pdf, $x , $tab , $val['l'], $hl);
						$x += $val['l'];
					}

				pdf_stroke($pdf); 
		
			
			pdf_setfont($pdf, $HelveticaBold, 7);
			pdf_set_value ($pdf, "leading", 10);
			

			$datashow = array(
				3 => $phrase[48],
				4 => $tothprest,
				5 => $totkmfacture,
				6 => fpeuro($totfraisfacture),
				7 => fpeuro($totdiversfrais),
				8 => fpeuro($totlivraison),
			    9 => fpeuro($totdimona)   	
			);

			$x = 0;
			foreach ($dim as $key => $val) {
				if (array_key_exists($key, $datashow)) pdf_show_boxed($pdf, $datashow[$key] , $x , $tabt , $val['l'], $ht, $val['a'], "");
				$x += $val['l'];
			}
			
			$tothprest       = 0;
			$totkmfacture    = 0;
			$totfraisfacture = 0;
			$totdiversfrais  = 0;
			$totlivraison    = 0;
			$totdimona       = 0;
		#															#
		#### Corps de Page  #########################################
				}
		#### Pied de Page    ########################################
		#															#
			#date
			pdf_setfont($pdf, $Helvetica, 9);
			pdf_show_boxed($pdf, $phrase[50].date("d/m/Y").$phrase[63].date("H:i:s") ,0 ,30 , 200, 15, 'left', ""); #texte du commentaire
		
			# Ligne de bas de page
			pdf_moveto($pdf, 0, 30);
			pdf_lineto($pdf, $HauteurUtile, 30);
			pdf_stroke($pdf); # Ligne de bas de page
			
			
			# Coordonnées Exception
			pdf_setfont($pdf, $Helvetica, 10);
			
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

			$idpos = $row['idshop']; # séparation des POS

			#### switch statut pour archivage ########################################
			if ($fac->kind == 'FAC') { ## Facture
				$_POST['facturation'] = '8';
				$idmerch = $row['idmerch'];
				$modif = new db('merch', 'idmerch');
				$modif->MODIFIE($idmerch, array('facturation'));
			}
		}
		
		unset ($DetHeures);
		unset ($DetKm);
 ?> 