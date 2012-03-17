<?php
### Declaration des fontes
$TimesBold = PDF_load_font($pdf, "Times-Bold", "host", "");
$TimesRoman = PDF_load_font($pdf, "Times-Roman", "host", "");
$Helvetica = PDF_load_font($pdf, "Helvetica", "host", "");
$HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");


	setlocale(LC_TIME, 'fr_FR');

	$semin = date('W', strtotime($_POST['date1']));
	$semout = date('W', strtotime($_POST['date2']));

	$structure = array(
		array('titre1', $phrase[14]),
		array('line', 'mfo1a', ''),
		array('line', 'mfo2a', ''),
		array('line', 'mfo3a', ''),
		array('line', 'mfo4a', ''),
		array('spacer'),
		array('titre1', $phrase[15]),
		array('titre2', $phrase[16]),
		array('line', 'mpa1a', ''),
		array('line', 'mpa2a', ''),
		array('line', 'mpa3a', ''),
		array('line', 'mpa4a', ''),
		array('line', 'mpa5a', ''),
		array('line', 'mpa6a', ''),
		array('line', 'mpa7a', ''),
		array('line', 'mpa8a', ''),
		array('line', 'mpa9a', ''),
		array('line', 'mpa10a', ''),
		array('spacer'),
		array('titre2', $phrase[17]),
		array('line', 'mte1a', ''),
		array('line', 'mte2a', ''),
		array('line', 'mte3a', ''),
		array('line', 'mte4a', ''),
		array('line', 'mte5a', ''),
		array('line', 'mte6a', ''),
		array('line', 'mte7a', ''),
		array('line', 'mte8a', ''),
		array('spacer'),
		array('titre2', $phrase[18]),
		array('line', 'mep1a', ''),
		array('line', 'mep2a', ''),
		array('line', 'mep3a', ''),
		array('line', 'mep4a', ''),
		array('line', 'mep5a', ''),
		array('spacer'),
		array('titre2', $phrase[19]),
		array('line', 'mba1a', ''),
		array('line', 'mba2a', ''),
		array('line', 'mba3a', ''),
		array('line', 'mba4a', ''),
		array('line', 'mba5a', ''),
		array('line', 'mba6a', ''),
		array('spacer'),
		array('titre2', $phrase[20]),
		array('line', 'mau1a', ''),
		array('line', 'mau2a', ''),
		array('line', 'mau3a', ''),
		array('line', 'mau4a', ''),
		array('line', 'mau5a', ''),
		array('spacer'),
		array('total')
		#array('spacer'),
		#array('caisse')
	);
	
#######################################################################
# data

$leschamps = array('fo1', 'fo2', 'fo3', 'fo4', 'pa1', 'pa2', 'pa3', 'pa4', 'pa5', 'pa6', 'pa7', 'pa8', 'pa9', 'pa10', 'te1', 'te2', 'te3', 'te4', 'te5', 'te6', 'te7', 'te8', 'ep1', 'ep2', 'ep3', 'ep4', 'ep5', 'ba1', 'ba2', 'ba3', 'ba4', 'ba5', 'ba6', 'au1', 'au2', 'au3', 'au4', 'au5');
$lestrois = array('a', 'b', 'c');

$sql = "SELECT m.weekm, m.idpeople, m.idmerch, eas.au1n, eas.au2n, eas.au3n, eas.au4n, eas.au5n ";

foreach ($leschamps as $champ) {
	foreach ($lestrois as $let) {
		$sql .= ", eas.".$champ.$let;
	}
}

$sql .= " FROM mercheasproduit eas
LEFT JOIN merch m ON eas.idmerch = m.idmerch 

WHERE m.datem BETWEEN '".$_POST['date1']."' AND  '".$_POST['date2']."'  AND eas.idmerch > 1 AND m.idshop = '".$valideas['idshop']."'";

$lesdatas = new db();
$lesdatas->inline("SET NAMES latin1");
$lesdatas->inline($sql);

if (isset($tabl)) unset($tabl);
if (isset($total)) unset($total);

while ($r = mysql_fetch_array($lesdatas->result)) {
	foreach ($leschamps as $champ) {
		foreach ($lestrois as $let) {
			$tabl[$r['weekm']][$champ.$let] += $r[$champ.$let];
			$total[$r['weekm']][$let] += $r[$champ.$let];
				
			if ((!empty($r[$champ.'n'])) and (!strstr($phrase['m'.$champ.'a'], $r[$champ.'n']))) $phrase['m'.$champ.'a'] .= $r[$champ.'n'].' ';	
		}
	}

	## People
	$pps[] = $r['idpeople'];
}

## Semaines
$lessemaines = array_keys($tabl);
sort ($lessemaines);

if (is_array($lessemaines)) {

## People
if (is_array($pps)) {
	$peops = $DB->getArray("SELECT idpeople, pnom, pprenom, codepeople FROM people WHERE idpeople IN (".implode(", ", array_unique($pps)).")");
	foreach ($peops as $pe) $infosp[$pe['idpeople']] = $pe['pprenom']." ".$pe['pnom'];
	unset($pps);
}

#######################################################################
# fichier

		## début de page
		pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
		$nbpages++;
		PDF_create_bookmark($pdf, "DET. ".$ccfd['pagename'], "");
		pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche
		### Titre Page
		pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);
		pdf_rect($pdf, 0, 762, 534, 16);
		pdf_rect($pdf, 0, 603, 534, 11);
		pdf_fill_stroke($pdf);						#
		## Cadres infos
		pdf_rect($pdf, 200, 706, 334, 50);
		pdf_rect($pdf, 200, 656, 334, 50);
		pdf_stroke($pdf);
		## Cadres Jours 25%
		pdf_setcolor($pdf, "fill", "gray", 0.75, 0, 0, 0);
		$tab = 620;
#		pdf_rect($pdf, 0, $tab, 109, 30);
		pdf_rect($pdf, 110, $tab, 84, 30);
		pdf_rect($pdf, 195, $tab, 84, 30);
		pdf_rect($pdf, 280, $tab, 84, 30);
		pdf_rect($pdf, 365, $tab, 84, 30);
		pdf_rect($pdf, 450, $tab, 84, 30);
		pdf_fill_stroke($pdf);						#
		
		pdf_setcolor($pdf, "fill", "gray", 1, 0, 0, 0);
	
		pdf_setfont($pdf, $HelveticaBold, 14); pdf_set_value ($pdf, 'leading', 14);
		pdf_show_boxed($pdf, $phrase[0] , 0, 762, 534, 17 , 'center', "");
		$tab = 605;
		pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, 'leading', 10);
		pdf_show_boxed($pdf, $phrase[3] , 110, $tab, 28, 10 , 'center', "");
		pdf_show_boxed($pdf, $phrase[1] , 138, $tab, 28, 10 , 'center', "");
		pdf_show_boxed($pdf, $phrase[2] , 166, $tab, 28, 10 , 'center', "");
		pdf_show_boxed($pdf, $phrase[3] , 195, $tab, 28, 10 , 'center', "");
		pdf_show_boxed($pdf, $phrase[1] , 223, $tab, 28, 10 , 'center', "");
		pdf_show_boxed($pdf, $phrase[2] , 251, $tab, 28, 10 , 'center', "");
		pdf_show_boxed($pdf, $phrase[3] , 280, $tab, 28, 10 , 'center', "");
		pdf_show_boxed($pdf, $phrase[1] , 308, $tab, 28, 10 , 'center', "");
		pdf_show_boxed($pdf, $phrase[2] , 336, $tab, 28, 10 , 'center', "");
		pdf_show_boxed($pdf, $phrase[3] , 365, $tab, 28, 10 , 'center', "");
		pdf_show_boxed($pdf, $phrase[1] , 393, $tab, 28, 10 , 'center', "");
		pdf_show_boxed($pdf, $phrase[2] , 421, $tab, 28, 10 , 'center', "");
		pdf_show_boxed($pdf, $phrase[3] , 450, $tab, 28, 10 , 'center', "");
		pdf_show_boxed($pdf, $phrase[1] , 478, $tab, 28, 10 , 'center', "");
		pdf_show_boxed($pdf, $phrase[2] , 506, $tab, 28, 10 , 'center', "");
		
		pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);
		
		## Adresse Xception
		pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, 'leading', 10);
		pdf_show_boxed($pdf, $phrase[4] , 0, 744, 200, 13 , 'center', "");
		pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, 'leading', 9);
		pdf_show_boxed($pdf, $phrase[5] , 0, 720, 200, 22 , 'center', "");
		#pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, 'leading', 9); # fax
		#pdf_show_boxed($pdf, 'Fax : 02 / 732 79 38' , 0, 700, 200, 15 , 'center', ""); # fax
		
		## Cadre Magasin
		pdf_setfont($pdf, $HelveticaBold, 12); pdf_set_value ($pdf, 'leading', 12);
		pdf_show_boxed($pdf, $ccfd['codeshop']."\r".$ccfd['ssociete'] , 200, 722, 334, 30 , 'center', "");

		## Cadre Mois
		pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, 'leading', 10);
		pdf_show_boxed($pdf, $phrase[43].fdate($_POST['date1']).$phrase[44].fdate($_POST['date2']) ,200, 710, 334, 12 , 'center', "");

		## Peoples
		pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, 'leading', 12);
		pdf_show_boxed($pdf, implode("\r", $infosp) ,200, 645, 334, 60 , 'center', "");

		unset($infosp);
		
		## Infos Responsable
		pdf_setfont($pdf, $HelveticaBold, 9); pdf_set_value ($pdf, 'leading', 9);
		pdf_show_boxed($pdf, $phrase[10] , 0, 691, 200, 10 , 'center', "");
		pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, 'leading', 9);
		pdf_show_boxed($pdf, $phrase[29] , 0, 677, 200, 10 , 'center', "");

		## Noms jours
		$xrect = array('0' => '110', '1' => '195', '2' => '280', '3' => '365', '4' => '450');

		$tab = 637;
		pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, 'leading', 10);

		foreach($xrect as $key => $value) {
			if (!empty($lessemaines[$key])) {
				pdf_show_boxed($pdf, $phrase[12]." ".$lessemaines[$key] , $value, $tab, 84, 12 , 'center', "");
			}
		}

		$tab = 622;
		pdf_setfont($pdf, $Helvetica, 6); pdf_set_value ($pdf, 'leading', 6);
		
		foreach($xrect as $key => $value) {
			if (!empty($lessemaines[$key])) {
				$sem = weekdate($lessemaines[$key], substr($_POST['date1'], 0, 4));
				if ($sem['lun'] < $_POST['date1']) $sem['lun'] = $_POST['date1'];
				if ($sem['dim'] > $_POST['date2']) $sem['dim'] = $_POST['date2'];
				pdf_show_boxed($pdf, $phrase[43]." ".fdate($sem['lun'])."\r".$phrase[44]." ".fdate($sem['dim']) , $value, $tab, 84, 15 , 'center', "");
			}
		}

		$tab = 604;

		$lesx = array('110', '138', '166', '195', '223', '251', '280', '308', '336', '365', '393', '421', '450', '478', '506');

		foreach ($structure as $vals) {
			switch ($vals[0]) {
				
					case "titre1": 
						$tab -= 11;
						pdf_setcolor($pdf, "fill", "gray", 0.5, 0, 0, 0);
						pdf_rect($pdf, 0, $tab, 534, 11);
						pdf_fill_stroke($pdf);						#
						pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);
						pdf_setfont($pdf, $HelveticaBold, 9); pdf_set_value ($pdf, 'leading', 9);
						pdf_show_boxed($pdf, $vals[1] , 0, $tab, 110, 11 , 'center', "");
					break;
				
					case "titre2": 
						$tab -= 11;
						pdf_setcolor($pdf, "fill", "gray", 0.75, 0, 0 ,0);
						pdf_rect($pdf, 0, $tab, 534, 11);
						pdf_fill_stroke($pdf);						#
						pdf_setcolor($pdf, "fill", "gray", 0, 0, 0 ,0);
						pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, 'leading', 9);
						pdf_show_boxed($pdf, $vals[1] , 0, $tab, 110, 11 , 'center', "");
					break;
		
					case "spacer": 
						$tab -= 6;
					break;
				
					case "line": 
						$tab -= 11;
						## Cadres
						pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);
						pdf_rect($pdf, 0, $tab, 109, 11);

						foreach ($lesx as $x) {
							pdf_rect($pdf, $x, $tab, 28, 11);
						}
						pdf_stroke($pdf);

						## Datas
						pdf_setfont($pdf, $TimesRoman, 8); pdf_set_value ($pdf, 'leading', 8);
						
						$mv = 0;
						foreach ($lessemaines as $sem) {
							foreach ($lestrois as $let) {
								pdf_show_boxed($pdf, fnbr0($tabl[$sem][substr($vals[1], 1, -1).$let]) , $lesx[$mv], $tab, 28, 11, 'center', "");

								$mv++;
							}
						}
						unset($mv);

						## Texte
						pdf_setfont($pdf, $TimesRoman, 8); pdf_set_value ($pdf, 'leading', 8);
						pdf_show_boxed($pdf, $phrase[$vals[1]] , 2, $tab, 108, 11 , 'left', "");
						pdf_show_boxed($pdf, $vals[2] , 100, $tab, 8, 11 , 'center', "");
					break;
		
					case "horaires":
						$tab -= 21; #22
						## Cadres
						pdf_rect($pdf, 0, $tab, 109, 21);
						pdf_rect($pdf, 110, $tab, 84, 21);
						pdf_rect($pdf, 195, $tab, 84, 21);
						pdf_rect($pdf, 280, $tab, 84, 21);
						pdf_rect($pdf, 365, $tab, 84, 21);
						pdf_rect($pdf, 450, $tab, 84, 21);
						pdf_stroke($pdf);
		
						## titres
						pdf_setfont($pdf, $TimesRoman, 8); pdf_set_value ($pdf, 'leading', 8);
						pdf_show_boxed($pdf, $phrase[22] , 0, $tab + 10, 108, 11 , 'center', "");
						pdf_setfont($pdf, $TimesRoman, 7); pdf_set_value ($pdf, 'leading', 7);
						pdf_show_boxed($pdf, $phrase[27] , 87, $tab, 23, 11 , 'center', "");
						pdf_show_boxed($pdf, $phrase[28] , 87, $tab + 10, 23, 11 , 'center', "");
						
					break;
				
					case "caisse": 
						$tab -= 10;
		
						## titres cadre
						pdf_setcolor($pdf, "fill", "gray", 0.80, 0, 0 ,0);
						pdf_rect($pdf, 0, $tab, 109, 10);
		
						$start = 110;
						$stop = 479;
						$nombre = 36;
		
						for ($i = $start; $i < $stop; $i += (($stop - $start) / $nombre)) {
							pdf_rect($pdf, $i, $tab, (($stop - $start) / $nombre), 10);
						}
		
						pdf_rect($pdf, 480, $tab, 54, 10);
						pdf_fill_stroke($pdf);						#
						pdf_setcolor($pdf, "fill", "gray", 0, 0 ,0 ,0);
						## textes titre
						pdf_setfont($pdf, $TimesRoman, 8); pdf_set_value ($pdf, 'leading', 8);
						pdf_show_boxed($pdf, $phrase[25] , 2, $tab, 108, 10 , 'center', "");
		
						pdf_setfont($pdf, $TimesRoman, 8); pdf_set_value ($pdf, 'leading', 8);
		
						$t=0;
						for ($i = $start; $i < $stop; $i += (($stop - $start) / $nombre)) {
							$t++;
							pdf_show_boxed($pdf, $t , $i, $tab, (($stop - $start) / $nombre), 10, 'center', "");
						}
								
						pdf_show_boxed($pdf, $phrase[26] , 480, $tab, 54, 10 , 'center', "");
					break;

					case "total": 
						$tab -= 10;

						pdf_setcolor($pdf, "fill", "gray", 0, 0, 0 ,0);
						pdf_rect($pdf, 0, $tab, 109, 11);

						foreach ($lesx as $x) {
							pdf_rect($pdf, $x, $tab, 28, 11);
						}
						pdf_stroke($pdf);

						## Datas
						pdf_setfont($pdf, $TimesRoman, 8); pdf_set_value ($pdf, 'leading', 8);
						pdf_show_boxed($pdf, "Total" , 2, $tab, 108, 11 , 'left', "");

						pdf_setfont($pdf, $TimesBold, 8); pdf_set_value ($pdf, 'leading', 8);
						
						$mv = 0;
						foreach ($lessemaines as $sem) {
							foreach ($lestrois as $let) {
								pdf_show_boxed($pdf, fnbr0($total[$sem][$let]) , $lesx[$mv], $tab, 28, 11, 'center', "");

								$mv++;
							}
						}
						unset($mv);

					break;
			}
		}

pdf_end_page($pdf);
}

?>