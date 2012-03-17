<?php
### Declaration des fontes
$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");
$TimesItalic = PDF_load_font($pdf, "Times-Italic", "host", "");
$HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");
$Helvetica = PDF_load_font($pdf, "Helvetica", "host", "");


pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
$nbpages++;
PDF_create_bookmark($pdf, utf8_decode($valideas['ssociete']), "");
pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche

#### Entete de Page  ########################################

	$logobig = pdf_load_image($pdf, "png", $_SERVER["DOCUMENT_ROOT"]."/print/illus/logoPrint.png", "");
	$haut = PDF_get_value($pdf, "imageheight", $logobig) * 0.3; # Calcul de la hauteur
	pdf_place_image($pdf, $logobig, 0, 690, 0.33);

	# titre
	pdf_setfont($pdf, $HelveticaBold, 25);
	pdf_set_value ($pdf, "leading", 25);
	pdf_show_boxed($pdf, $phrase[30] , 100 , 690 , $LargeurUtile, 32 , 'center', ""); # date
#															#
#### Entete de Page  ########################################

pdf_setlinewidth($pdf, 0.5);

## Cadre 'a renvoyer au plus vite'
pdf_setcolor($pdf, "fill", "gray", 0.7, 0, 0, 0);
pdf_rect($pdf, 0, $HauteurUtile - 30, $LargeurUtile, 30);
pdf_fill_stroke($pdf); # Ligne de bas de page
pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);
pdf_setfont($pdf, $Helvetica, 16); pdf_set_value ($pdf, 'leading', 14);
pdf_show_boxed($pdf, $phrase[46], 10, $HauteurUtile - 30, $LargeurUtile - 20, 25 , 'left', "");
pdf_setfont($pdf, $HelveticaBold, 16); pdf_set_value ($pdf, 'leading', 14);
pdf_show_boxed($pdf, $phrase[47], 10, $HauteurUtile - 30, $LargeurUtile - 20, 25, 'right', "");

## Case magazin people
pdf_setcolor($pdf, "fill", "gray", 0.9, 0, 0, 0);
pdf_rect($pdf, 0, 630, ($LargeurUtile - 10) / 2, 30);							# Rectangles POS
pdf_rect($pdf, (($LargeurUtile - 10) / 2) + 10, 630, ($LargeurUtile - 10) / 2, 30);			# Rectangles Attn
pdf_fill_stroke($pdf); # Ligne de bas de page
pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);

pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, 'leading', 12);
pdf_show_boxed($pdf, "POS", 0, 655, 300, 20 , 'left', "");
pdf_show_boxed($pdf, "Attn", 0, 655, $LargeurUtile, 20 , 'right', "");
pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, 'leading', 14);
pdf_show_boxed($pdf, utf8_decode($valideas['shop']."\r\n".$valideas['cp']." ".$valideas['sville']), 0, 632, ($LargeurUtile - 10) / 2, 30 , 'center', "");
pdf_show_boxed($pdf, utf8_decode($valideas['snom']." ".$valideas['sprenom']), (($LargeurUtile - 10) / 2) + 10, 625, ($LargeurUtile - 10) / 2, 30 , 'center', "");

## tableau titre
$tab = 595;
$htab = 13;
$htabtxt = 11;
$ltab = 100;
$page = 1;

	pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);
	pdf_rect($pdf, 50, $tab, $ltab, $htab);			# Rectangles date
	pdf_rect($pdf, 150, $tab, $ltab, $htab);		# Rectangles heure
	pdf_rect($pdf, 250, $tab, 150, $htab);			# Rectangles heure
	pdf_rect($pdf, 400, $tab, 50, $htab);			# Rectangles heure
	pdf_fill_stroke($pdf);
	pdf_setcolor($pdf, "both", "gray", 1, 0, 0, 0);

	pdf_setfont($pdf, $HelveticaBold, 10); 
	pdf_set_value ($pdf, 'leading', 10);
	pdf_show_boxed($pdf, $phrase[36], 50, $tab + 2, $ltab, $htabtxt , 'center', "");
	pdf_show_boxed($pdf, $phrase[37], 150, $tab + 2, $ltab, $htabtxt , 'center', "");
	pdf_show_boxed($pdf, $phrase[38], 250, $tab + 2, 150, $htabtxt , 'center', "");
	pdf_show_boxed($pdf, $phrase[39], 400, $tab + 2, 50, $htabtxt , 'center', "");
	pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);

$tab -= $htab;

## tableau ligne DB
	$DB->inline("SET NAMES latin1");
	$detaileas = $DB->getArray("SELECT
			me.datem, me.idmerch, 
			p.codepeople, p.pnom, p.pprenom, p.idpeople, me.idshop, me.idcofficer
		FROM merch me
			LEFT JOIN people p ON me.idpeople = p.idpeople
		WHERE me.datem BETWEEN '".$_POST['date1']."' AND '".$_POST['date2']."' 
			AND me.idshop = '".$valideas['idshop']."' 
			AND me.genre = 'EAS'
		ORDER BY me.datem");

	foreach ($detaileas as $infos) {
		
		switch($_POST['typeSend'])
		{
			case "people":
				if(!empty($infos['idpeople']))
				{
					$fichiersparpeople[$infos['idpeople']][] = Conf::read('Env.root').$pathpdf.$nompdf;
				}
			break;
			case "shop":
				if(!empty($infos['idshop']))
				{
					$fichiersparpeople[$infos['idshop']][] = Conf::read('Env.root').$pathpdf.$nompdf;
				}
			break;
			case "cofficer":
				if(!empty($infos['idcofficer']))
				{
					$fichiersparpeople[$infos['idcofficer']][] = Conf::read('Env.root').$pathpdf.$nompdf;
				}
			break;
		}
		
		

        ## TODO: !! AJOUT A L'ARRACHE, PAS BEAU !! repasser en tcpdf
        if ($tab < 50) {
            #### Pied de Page    ########################################
            #															#
            
            # Ligne de bas de page
            pdf_moveto($pdf, 0, 40);
            pdf_lineto($pdf, $LargeurUtile, 40);
            pdf_stroke($pdf); # Ligne de bas de page
            
            # Coordonnées Exception
            pdf_setfont($pdf, $TimesRoman, 10);
            
            pdf_show_boxed($pdf, $phrase[40] ,0 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
            pdf_show_boxed($pdf, $phrase[41] , $LargeurUtile / 3,0 , $LargeurUtile / 3,40, 'center', ""); #texte du commentaire
            pdf_show_boxed($pdf, $phrase[42] , $LargeurUtile * 2 / 3 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
            
            #															#
            #### Pied de Page    ########################################
            pdf_end_page($pdf);
            $page++;
            $tab = 700;
            pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
            PDF_create_bookmark($pdf, $valideas['ssociete'], "");
            pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche
            
            ## Case magazin people
            pdf_setfont($pdf, $HelveticaBold, 12); 
            pdf_set_value ($pdf, 'leading', 12);
            pdf_show_boxed($pdf, "POS : ".$valideas['shop']." - ".$valideas['sville']." Page ".$page , 0, 720, 300, 20 , 'left', "");
        }

		pdf_rect($pdf, 50, $tab, $ltab, $htab);		# Rectangles date
		pdf_rect($pdf, 150, $tab, $ltab, $htab);	# Rectangles heure
		pdf_rect($pdf, 250, $tab, 150, $htab);		# Rectangles People
		pdf_rect($pdf, 400, $tab, 50, $htab);		# Rectangles People
		pdf_stroke($pdf);

		$merch = new coremerch($infos['idmerch']);
		$timetot = $merch->hprest;
		$timetotx += $timetot;
		
		pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, 'leading', 10);
		pdf_show_boxed($pdf, fdate($infos['datem']), 50, $tab + 2, $ltab, $htabtxt , 'center', "");
		pdf_show_boxed($pdf, fnbr($timetot), 150, $tab + 2, $ltab, $htabtxt , 'center', "");
		pdf_show_boxed($pdf, $infos['pnom'].' '.$infos['pprenom'], 250, $tab + 2, 150, $htabtxt , 'center', "");
		pdf_show_boxed($pdf, $infos['codepeople'], 400, $tab + 2, 50, $htabtxt , 'center', "");
		
		$tab = $tab - $htab;
	}
	


## tableau total

	pdf_rect($pdf, 50, $tab, $ltab, $htab);			# Rectangles date
	pdf_rect($pdf, 150, $tab, $ltab, $htab);			# Rectangles heure
	pdf_stroke($pdf);

	pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, 'leading', 10);
	pdf_show_boxed($pdf, $phrase[35], 50, $tab + 2, $ltab, $htabtxt , 'center', "");
	pdf_show_boxed($pdf, fnbr($timetotx), 150, $tab + 2, $ltab, $htabtxt , 'center', "");
	$timetotx = 0;
	$tab -= $htab;
	
## Numéro de Fax du Magasin
	$tab = 263;
	pdf_setcolor($pdf, "fill", "gray", 0.7, 0, 0, 0);
	pdf_rect($pdf, 0, $tab, $LargeurUtile, 15);
	pdf_fill_stroke($pdf);
	pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);
	pdf_show_boxed($pdf, "SHOP FAX : ".$valideas['fax'], 10, $tab - 1, $LargeurUtile - 10, 15 , 'left', "");

## Cadre chef d'exploitation
	$tab = 240;
	pdf_setfont($pdf, $TimesItalic, 10); pdf_set_value ($pdf, 'leading', 10);
	pdf_show_boxed($pdf, $phrase[45], 0, $tab, $LargeurUtile, 20 , 'left', "");
	
## Cadre chef d'exploitation
 	$tab = 150;
	pdf_rect($pdf, 0, $tab, ($LargeurUtile - 10) / 2, 80);			# Rectangles signature
	pdf_rect($pdf, (($LargeurUtile - 10) / 2) + 10, $tab, ($LargeurUtile - 10) / 2, 80);			# Rectangles cachet
	$tab -= 100;
	pdf_rect($pdf, 0, $tab, $LargeurUtile, 80);			# Rectangles commentaire
	pdf_stroke($pdf);
	
 	$tab = 145;
	pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, 'leading', 12);
	pdf_show_boxed($pdf, $phrase[31], 0, $tab + 80, 300, 20 , 'left', "");
	pdf_show_boxed($pdf, $phrase[32], (($LargeurUtile - 10) / 2) + 10, $tab + 80, 300, 20 , 'left', "");
	pdf_show_boxed($pdf, $phrase[33], 0, $tab - 20, 300, 20 , 'left', "");
	
	pdf_setfont($pdf, $Helvetica, 8); pdf_set_value ($pdf, 'leading', 8);
	pdf_show_boxed($pdf, $phrase[34], 5, $tab + 65, 300, 20 , 'left', "");
	pdf_setlinewidth($pdf, 1);



#### Pied de Page    ########################################
#															#
	
	# Ligne de bas de page
	pdf_moveto($pdf, 0, 40);
	pdf_lineto($pdf, $LargeurUtile, 40);
	pdf_stroke($pdf); # Ligne de bas de page
	
	# Coordonnées Exception
	pdf_setfont($pdf, $Helvetica, 9);
	
	pdf_show_boxed($pdf, $phrase[40] ,0 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire
	pdf_show_boxed($pdf, $phrase[41] , $LargeurUtile / 3,0 , $LargeurUtile / 3,40, 'center', ""); #texte du commentaire
	pdf_show_boxed($pdf, $phrase[42] , $LargeurUtile * 2 / 3 ,0 , $LargeurUtile / 3, 40, 'center', ""); #texte du commentaire

#															#
#### Pied de Page    ########################################

pdf_end_page($pdf);
?>