<?php
#################################################################################################################################
#																																#
#	Ce fichier génère un PDF reprenant les informations de payement pour les employés											#
#																																#
#	Copyright Celsys 2004																										#
#																																#
#	Ce fichier ne peut etre reproduit ou modifié sans l'accord préalable et écrit de Celsys SPRL								#
#	Celsys SPRL est entière propriétaire de ce code																				#
#	Ce fichier a été réalisé dans le cadre du projet NEURO																		#
#																																#
#################################################################################################################################

define('NIVO', '../');

# Classes utilisées
include_once (NIVO."classes/anim.php");
include_once (NIVO."classes/vip.php");
include_once (NIVO."classes/merch.php");
include_once (NIVO.'classes/test.php');
include_once (NIVO.'classes/payement.php');

include NIVO."includes/ifentete.php" ;

### Declaration des fontes
$HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");
$Helvetica = PDF_load_font($pdf, "Helvetica-Bold", "host", "");

#############################################################################
#####  1. Recherche des infos Uniq/All                #######################
#############################################################################

	setlocale(LC_TIME, 'fr_FR');

	$tmois = substr($_SESSION['table'], -2, 2);
	$tyear = '20'.substr($_SESSION['table'], -4, 2);

	$moistxt = strftime("%B %Y", mktime (0,0,0, $tmois, 1, $tyear));;

if (!empty($_GET['id'])) {
	### One fiche
		$nompdf = 'RPAY-'.date("Ymd").'.pdf';

		$paytable[0] = $_GET['id'];
}
else {
	### All month
		$nompdf = 'FULLPAY-'.$tmois.'-'.$tyear.'.pdf';

		$tablepay = new db();
		$tablepay->inline("SELECT s.idpeople FROM grps.".$_SESSION['table']." s LEFT JOIN neuro.people p ON s.idpeople = p.idpeople GROUP BY s.idpeople ORDER BY p.codepeople");

		while ($row = mysql_fetch_array($tablepay->result)) {
			$paytable[] = $row['idpeople'];
		}
}


#############################################################################
#####  2. Début PDF                                   #######################
#############################################################################

### Entete PDF

		# Variables Path PDF
		$pathpdf = 'document/temp/people/';


		$pdf = pdf_new();
		pdf_open_file($pdf, Conf::read('Env.root').$pathpdf.$nompdf); # définit l'emplacement de la sauvegarde
		# Infos pour le document
		pdf_set_info($pdf, "Author", "NEURO");
		pdf_set_info($pdf, "Title", "Rapport Payement");
		pdf_set_info($pdf, "Creator", "NEURO");
		pdf_set_info($pdf, "Subject", "Rapport Payement");

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

		$maxtab = 755;
		$mintab = 25;
		#####

		foreach ($paytable as $pid) {

			$pay = new payement($pid);


#############################################################################
#####  4. Chaque Page                                 #######################
#############################################################################
			$tab = 542;

			pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
			PDF_create_bookmark($pdf, "Page ".$np, "");
			pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche

			##### Cadres Dessus ######
					pdf_setcolor($pdf, "fill", "gray", 0.8, 0 ,0 ,0);

					pdf_rect($pdf, 0, 750, 13, 30);		# gris NOM
					pdf_rect($pdf, 316, 750, 13, 30);	# gris Date
					pdf_rect($pdf, 447, 750, 13, 30);	# gris Reg
					pdf_rect($pdf, 0, 708, 13, 40);		# gris Contact
					pdf_rect($pdf, 316, 708, 13, 40);	# gris Infos

					pdf_fill_stroke($pdf);

					pdf_rect($pdf, 13, 750, 301, 30);		# blanc NOM
					pdf_rect($pdf, 329, 750, 116, 30);	# blanc Date
					pdf_rect($pdf, 460, 750, 74, 30);	# blanc Reg
					pdf_rect($pdf, 13, 708, 301, 40);		# blanc Contact
					pdf_rect($pdf, 329, 708, 205, 40);	# blanc Infos

					pdf_stroke($pdf);

			##### Cadres Entete lignes ######
					pdf_setcolor($pdf, "fill", "gray", 0.8, 0 ,0 ,0);

					pdf_rect($pdf, 0, 648, 70, 12);		# gris NOM
					pdf_rect($pdf, 70, 648, 50, 12);		# gris NOM
					pdf_rect($pdf, 120, 648, 50, 12);		# gris NOM
					pdf_rect($pdf, 170, 648, 50, 12);		# gris NOM
					pdf_rect($pdf, 220, 648, 50, 12);		# gris NOM
					pdf_rect($pdf, 270, 648, 50, 12);		# gris NOM
					pdf_rect($pdf, 320, 648, 50, 12);		# gris NOM
					pdf_rect($pdf, 370, 648, 55, 12);		# gris NOM
					pdf_rect($pdf, 425, 648, 55, 12);		# gris NOM
					pdf_rect($pdf, 480, 648, 54, 12);		# gris NOM

					pdf_fill_stroke($pdf);

			##### Cadres Totaux Bas ######

					pdf_setcolor($pdf, "fill", "gray", 0.8, 0, 0 ,0);

					pdf_rect($pdf, 0, 25, 534, 30);

					pdf_stroke($pdf);

					pdf_rect($pdf, 0, 25, 69, 30);
					pdf_rect($pdf, 71, 27, 49, 12);
					pdf_rect($pdf, 120, 27, 50, 12);
					pdf_rect($pdf, 170, 27, 49, 12);
					pdf_rect($pdf, 221, 27, 49, 12);
					pdf_rect($pdf, 270, 27, 50, 12);
					pdf_rect($pdf, 320, 27, 49, 12);
					pdf_rect($pdf, 71, 41, 49, 12);
					pdf_rect($pdf, 120, 41, 50, 12);
					pdf_rect($pdf, 170, 41, 49, 12);

					pdf_fill_stroke($pdf);

					pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);

					pdf_rect($pdf, 443, 25, 90, 30);

					pdf_fill_stroke($pdf);

			##### Textes Fixes Hozizontaux ######
					pdf_setfont($pdf, $HelveticaBold, 18); pdf_set_value ($pdf, "leading", 18);
					pdf_show_boxed($pdf, "Total" , 0 , 32 , 70, 20 , 'center', "");

					pdf_setfont($pdf, $HelveticaBold, 22); pdf_set_value ($pdf, "leading", 22);
					pdf_show_boxed($pdf, "Prestations" , 0 , 666 , 162, 22 , 'left', "");
					pdf_show_boxed($pdf, $moistxt , 308 , 666 , 226, 22 , 'right', "");
					pdf_show_boxed($pdf, $pay->infp['pprenom'].' '.$pay->infp['pnom'] , 24 , 752 , 260, 26 , 'left', ""); #Nom du People
					pdf_show_boxed($pdf, $tmois.' '.$tyear , 329 , 752 , 116, 26 , 'center', "");	# Date en chiffres
					pdf_show_boxed($pdf, $pay->reg , 460 , 752 , 74, 26 , 'center', "");	# Date en chiffres

					pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, "leading", 10);
					pdf_show_boxed($pdf, "Date" , 0, 650, 70, 10 , 'center', "");
					pdf_show_boxed($pdf, "100 %" , 70, 650, 50, 10 , 'center', "");
					pdf_show_boxed($pdf, "150 %" , 120, 650, 50, 10 , 'center', "");
					pdf_show_boxed($pdf, "200 %" , 170, 650, 50, 10 , 'center', "");
					pdf_show_boxed($pdf, "Catering" , 220, 650, 50, 10 , 'center', "");
					pdf_show_boxed($pdf, "Déplac." , 270, 650, 50, 10 , 'center', "");
					pdf_show_boxed($pdf, "Frais" , 320, 650, 50, 10 , 'center', "");
					pdf_show_boxed($pdf, "VIP" , 370, 650, 55, 10 , 'center', "");
					pdf_show_boxed($pdf, "ANIM" , 425, 650, 55, 10 , 'center', "");
					pdf_show_boxed($pdf, "MERCH" , 480, 650, 54, 10 , 'center', "");

					pdf_show_boxed($pdf, "Adresse :" , 17, 735, 50, 12 , 'right', "");
					pdf_show_boxed($pdf, "GSM :" , 17, 710, 50, 12 , 'right', "");
					pdf_show_boxed($pdf, "ID :" , 228, 710, 60, 12 , 'right', "");

					pdf_show_boxed($pdf, "Compte B :" , 330, 734, 60, 12 , 'right', "");
					pdf_show_boxed($pdf, "Reg Nat :" , 330, 722, 60, 12 , 'right', "");
					pdf_show_boxed($pdf, "Age :" , 330, 710, 60, 12 , 'right', "");
					pdf_show_boxed($pdf, "Brut :" , 428, 710, 60, 12 , 'right', "");
					pdf_show_boxed($pdf, $pay->infp['lbureau'] , 293, 767, 17, 12 , 'right', "");

					pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
					pdf_show_boxed($pdf, $pay->infp['adresse1'].', '.$pay->infp['num1'].' '.$pay->infp['bte1']."\r".$pay->infp['cp1'].' - '.$pay->infp['ville1'] , 73, 721, 194, 27 , 'left', "");
					pdf_show_boxed($pdf, $pay->infp['gsm'] , 73, 712, 135, 12 , 'left', "");
					pdf_show_boxed($pdf, $pay->id, 290, 712, 30, 12 , 'left', "");


					if (preg_match("/([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{3})([0-9]{2})/", $pay->infp['nrnational'], $nr)) pdf_show_boxed($pdf, $nr[1].' '.$nr[2].' '.$nr[3].' '.$nr[4].' '.$nr[5] , 394, 724, 135, 12 , 'left', "");
					if (preg_match("/([0-9]{3})([0-9]{6})([0-9]{2})/", $pay->infp['banque'], $br)) pdf_show_boxed($pdf, $br[1].'-'.$br[2].'-'.$br[3] , 394, 736, 135, 12 , 'left', "");
					pdf_show_boxed($pdf, $pay->age , 394, 712, 40, 12 , 'left', "");
					pdf_show_boxed($pdf, fpeuro($pay->tarifb) , 492, 712, 40, 12 , 'left', "");

				$tab = 626 ;
				$ligne = 22;
				$t = 0;

				$ptable = paytable ($pay->id, $pay->datein, $pay->dateout);
				$dtable = array_shift($ptable);

				foreach ($dtable as $key => $value) {
					$ttab = $tab + 4;

				### Calculs info
					setlocale(LC_TIME, 'fr_FR');
					$ladate = strftime("%d %a", strtotime($value['date']));

					#># Heure de repas
					if (($value['heures'] >= Conf::read('Payement.maxheure')) and ($value['date'] < '2010-02-01')) {
						$heuresfact = $value['heures'];
						$value['heures'] -= 1;
						$value['frais433'] += Conf::read('Payement.prixrepas');
					} else {
						$heuresfact = $value['heures'];
					}
					#<# Heure de repas


					$infsal = new db('', '', 'grps');
					$sql = "SELECT `idsalaire`, `modh`, `modh150`, `modh200`, `mod433`, `mod437`, `mod441`
					FROM `".$_SESSION['table']."` WHERE `idpeople` = ".$pay->id." AND `date` = '".$value['date']."'";
					$infsal->inline($sql);

					$infs = mysql_fetch_array($infsal->result);

					$totheures = $value['heures'] + $infs['modh'];
					$totf433 = $value['frais433'] + $infs['mod433'];
					$totf437 = $value['frais437'] + $infs['mod437'];
					$totf441 = $value['frais441'] + $infs['mod441'];

					$modh100 = $totheures - $infs['modh150'] - $infs['modh200'];

					if ($infs['modh'] != 0) $modh = $infs['modh'];
					if ($infs['modh150'] != 0) $modh150 = $infs['modh150'];
					if ($infs['modh200'] != 0) $modh200 = $infs['modh200'];
					if ($infs['mod433'] != 0) $mod433 = $infs['mod433'];
					if ($infs['mod437'] != 0) $mod437 = $infs['mod437'];
					if ($infs['mod441'] != 0) $mod441 = $infs['mod441'];




				### Affichage
					if (fmod($t, 2) == 1) {
						pdf_setcolor($pdf, "fill", "gray", 0.9, 0, 0, 0);
						pdf_rect($pdf, 0, $tab, 534, $ligne);
						pdf_fill($pdf);
					}

					pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);

					pdf_rect($pdf, 0, $tab, 70, $ligne);
					pdf_rect($pdf, 70, $tab, 150, $ligne);
					pdf_rect($pdf, 220, $tab, 150, $ligne);
					pdf_rect($pdf, 370, $tab, 55, $ligne);
					pdf_rect($pdf, 425, $tab, 55, $ligne);
					pdf_rect($pdf, 480, $tab, 54, $ligne);

					pdf_stroke($pdf);


					pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, 'leading', 10);
					pdf_show_boxed($pdf, $ladate , 16, $ttab, 54, 12 , 'left', "");

					pdf_setfont($pdf, $Helvetica, 10);

					pdf_show_boxed($pdf, fnbr($modh100) , 70, $ttab, 50, 12 , 'center', "");
					pdf_show_boxed($pdf, fnbr($modh150) , 120, $ttab, 50, 12 , 'center', "");
					pdf_show_boxed($pdf, fnbr($modh200) , 170, $ttab, 50, 12 , 'center', "");

					pdf_show_boxed($pdf, fnbr($totf433) , 220, $ttab, 50, 12 , 'center', "");
					pdf_show_boxed($pdf, fnbr($totf437) , 270, $ttab, 50, 12 , 'center', "");
					pdf_show_boxed($pdf, fnbr($totf441) , 320, $ttab, 50, 12 , 'center', "");


						############# ANIM #######################

						$anim = new db();
						$anim->inline("SELECT idanimation FROM animation WHERE `idpeople` = ".$pay->infp['idpeople']." AND datem = '".$value['date']."' ORDER BY idanimation ASC");

						############# VIP #######################
						$vip = new db();
						$vip->inline("SELECT idvip FROM vipmission WHERE `idpeople` = ".$pay->infp['idpeople']." AND `vipdate` = '".$value['date']."' ORDER BY `idvip` ASC");

						############# MERCH #######################


						while ($infa = mysql_fetch_array($anim->result)) {
							$aanim[] = $infa['idanimation'];
						}

						while ($infv = mysql_fetch_array($vip->result)) {
							$vvip[] = $infv['idvip'];
						}

					$uptab = $tab + 13;
					$downtab = $uptab - 10;

					pdf_setfont($pdf, $Helvetica, 6); pdf_set_value ($pdf, 'leading', 6);
					pdf_show_boxed($pdf, $vvip[0] , 370, $uptab, 27, 7 , 'center', "");
					pdf_show_boxed($pdf, $vvip[2] , 370, $downtab, 27, 7 , 'center', "");
					pdf_show_boxed($pdf, $vvip[1] , 398, $uptab, 27, 7 , 'center', "");
					pdf_show_boxed($pdf, $vvip[3] , 398, $downtab, 27, 7 , 'center', "");

					pdf_show_boxed($pdf, $aanim[0] , 425, $uptab, 27, 7 , 'center', "");
					pdf_show_boxed($pdf, $aanim[2] , 425, $downtab, 27, 7 , 'center', "");
					pdf_show_boxed($pdf, $aanim[1] , 453, $uptab, 27, 7 , 'center', "");
					pdf_show_boxed($pdf, $aanim[3] , 453, $downtab, 27, 7 , 'center', "");

					pdf_show_boxed($pdf, $mmerch[0] , 480, $uptab, 27, 7 , 'center', "");
					pdf_show_boxed($pdf, $mmerch[2] , 480, $downtab, 27, 7 , 'center', "");
					pdf_show_boxed($pdf, $mmerch[1] , 507, $uptab, 27, 7 , 'center', "");
					pdf_show_boxed($pdf, $mmerch[3] , 507, $downtab, 27, 7 , 'center', "");

					$ttheures += $totheures;
					$ttfr433 += $totf433;
					$ttfr437 += $totf437;
					$ttfr441 += $totf441;
					$tth100 += $modh100;
					$tth150 += $modh150;
					$tth200 += $modh200;

					$ttmodh += $infs['modh'];

					unset($totheures);
					unset($totf433);
					unset($totf437);
					unset($totf441);

					unset($modh);
					unset($modh100);
					unset($modh150);
					unset($modh200);
					unset($mod433);
					unset($mod437);
					unset($mod441);
					unset($troph);
					unset($fonddim);
					unset($heuresfact);

					unset($aanim);
					unset($vvip);
					unset($mmerch);



					$tab -= $ligne;
					$t++;

				}

				$montantheures = ($tth100 + ($tth150 * 1.5) + ($tth200 * 2)) * $pay->tarifb;
				$fulltotal = $montantheures + $ttfr433 + $ttfr437 + $ttfr441;


				pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, 'leading', 10);
				pdf_show_boxed($pdf, $tth100 , 71, 42, 49, 12 , 'center', "");
				pdf_show_boxed($pdf, $tth150 , 120, 42, 50, 12 , 'center', "");
				pdf_show_boxed($pdf, $tth200 , 170, 42, 49, 12 , 'center', "");

				pdf_show_boxed($pdf, fnbr($tth100 * $pay->tarifb) , 71, 28, 49, 12 , 'center', "");
				pdf_show_boxed($pdf, fnbr($tth150 * $pay->tarifb * 1.5) , 120, 28, 50, 12 , 'center', "");
				pdf_show_boxed($pdf, fnbr($tth200 * $pay->tarifb * 2) , 170, 28, 49, 12 , 'center', "");

				pdf_show_boxed($pdf, fnbr($ttfr433) , 221, 28, 49, 12 , 'center', "");
				pdf_show_boxed($pdf, fnbr($ttfr437) , 270, 28, 50, 12 , 'center', "");
				pdf_show_boxed($pdf, fnbr($ttfr441) , 320, 28, 49, 12 , 'center', "");

				pdf_setcolor($pdf, "fill", "gray", 1, 0, 0, 0);
				pdf_setfont($pdf, $Helvetica, 18); pdf_set_value ($pdf, 'leading', 18);
				pdf_show_boxed($pdf, fnbr($fulltotal) , 445, 29, 85, 22 , 'center', "");
				pdf_setcolor($pdf, "fill", "gray", 0, 0, 0, 0);




				#### Pied de Page    ########################################
				#															#
					pdf_setfont($pdf, $Helvetica, 8); pdf_set_value ($pdf, "leading", 8);

					pdf_show_boxed($pdf, "Exception - Exception scrl\r195 Av. de la Chasse\r1040 - Bruxelles" ,0 ,0 , $LargeurUtile / 3, 24, 'center', ""); #texte du commentaire
					pdf_show_boxed($pdf, "\rTVA BE 430 597 846 \rRCB 489 589" , $LargeurUtile / 3,0 , $LargeurUtile / 3,24, 'center', ""); #texte du commentaire
					pdf_show_boxed($pdf, "www.exception2.be\rTel : 02 732.74.40\rFax : 02 732.79.38" , $LargeurUtile * 2 / 3 ,0 , $LargeurUtile / 3, 24, 'center', ""); #texte du commentaire
				#															#
				#### Pied de Page    ########################################

			pdf_end_page($pdf);
			$np++;
		}

		pdf_end_document($pdf, '');
		pdf_delete($pdf); # Efface le fichier en mémoire


#############################################################################
#####  5. Affichage du PDF                            #######################
#############################################################################
?>
<div align="center">
<br><br><br><br>
<img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0">
<br>
<A href="<?php echo NIVO.$pathpdf.$nompdf ;?>" target="_blank">Imprimer</A>
</div>
<?php


# Pied de Page
include NIVO."includes/ifpied.php" ;

##########################################################################################################################################################
##########################################################################################################################################################
##########################################################################################################################################################


exit();



#############################################################################
#####  1. Création de la liste des people au travail  #######################
#############################################################################

	$listtotest = array(); # Initialisation de la liste

	$datefrom = date("Y-m-d", mktime (0,0,0,date("m"),date("d") - 10,date("Y")) ); # renvoie la date d'il y a 10 jours en format SQL

	### ajout des People de ANIMATION
	$anims = new db();

	if (isset($_GET['mois'])) {
		$tabl = $_SESSION['table'];
		$anims = new db('', '', 'grps');
		$sql = "SELECT idpeople FROM $tabl";
		echo $sql;
		$anims->inline($sql);

		while ($row = mysql_fetch_array($anims->result)) {
			array_push ($listtotest, $row['idpeople']);
		}
	} else {
		$anims = new db();
		$anims->inline("SELECT an.idpeople FROM animation an LEFT JOIN people p ON an.idpeople = p.idpeople WHERE an.idpeople >= 1 AND p.catsociale NOT IN ('3')");

		$vip = new db();
		$vip->inline("SELECT v.idpeople FROM vipmission v LEFT JOIN people p ON v.idpeople = p.idpeople WHERE v.idpeople >= 1 AND p.catsociale NOT IN ('3')");

		while ($row = mysql_fetch_array($anims->result)) {
			array_push ($listtotest, $row['idpeople']);
		}

		while ($row = mysql_fetch_array($vip->result)) {
			array_push ($listtotest, $row['idpeople']) ;
		}
	}



	### ajout des People de MERCH

	### MERCHCODE ###

	### tri de la liste
	$listtotest = array_unique($listtotest) ; # remove duplicates
	sort ($listtotest, SORT_NUMERIC) ;
	if (trim($listtotest[0]) == '') array_shift($listtotest);

#############################################################################
#####  2. Recherche des Erreurs People et création de la table d'erreurs  ###
#############################################################################

	foreach ($listtotest as $value) {
		$value = trim ($value);
		if ($value > 0) {

		### Recherche des infos du people
			$people = new db();
			$people->inline("SELECT `idpeople` ,`pprenom` ,`gsm` ,`pnom` ,`catsociale` ,`sexe` ,`adresse1` ,`ville1` ,`cp1` ,`banque`,`nationalite` ,`ncidentite` ,`ndate` ,`ncp` ,`pays1` ,`nrnational` ,`npays` ,`dateentree` ,`codepeople` ,`etatcivil` ,`num1` ,`bte1` ,`lbureau` ,`pacharge` ,`eacharge`FROM `people` WHERE `idpeople` = $value");
			$row = mysql_fetch_array($people->result);

		### Test des infos du people

			$Verif = new test($row['idpeople']);
			# Teste tous les champ !! ordre a de l'importance
			$Verif->checkPRE($row['pprenom']);
			$Verif->checkNOM($row['pnom']);
			$Verif->checkCCC($row['catsociale']);
			$Verif->checkSEX($row['sexe']);
			$Verif->checkRUE($row['adresse1']);
			$Verif->checkLOC($row['ville1']);
			$Verif->checkCPO($row['cp1']);
			$Verif->checkNCF($row['banque']);
			$Verif->checkNAT($row['nationalite']);
			$Verif->checkNUI($row['ncidentite']);
			$Verif->checkDTN($row['ndate']);
			$Verif->checkNCP($row['ncp'], $row['npays']);
			$Verif->checkCPP($row['pays1']);
			$Verif->checkRNA($row['nrnational'], $row['ndate'], $row['sexe'], $row['npays']);
			$Verif->checkZ05($row['dateentree']);
			$Verif->checkRPE($row['codepeople']);
			$Verif->checkETC($row['etatcivil']);
			$Verif->checkNUR($row['num1']);
			$Verif->checkNUB($row['bte1']);
			$Verif->checkLGE($row['lbureau']);
			$Verif->checkAPC($row['pacharge']);
			$Verif->checkNEC($row['eacharge']);

		### Stockage dans la table d'erreurs
			$errP = 0;

			if (count($Verif->ErrGS) >= 1) {
				$latable[$Verif->idpeople]['nom'] = $row['pnom'];
				$latable[$Verif->idpeople]['pre'] = $row['pprenom'];
				$latable[$Verif->idpeople]['reg'] = $row['codepeople'];
				$latable[$Verif->idpeople]['gsm'] = $row['gsm'];
				$latable[$Verif->idpeople]['lg'] = $row['lbureau'];
				$latable[$Verif->idpeople]['EGS'] = $Verif->ErrGS;
				$cGRS = $cGRS + count($Verif->ErrGS);
				$errP = $errP + count($Verif->ErrGS);
			}

			if (count($Verif->ErrDI) >= 1) {
				$latable[$Verif->idpeople]['nom'] = $row['pnom'];
				$latable[$Verif->idpeople]['pre'] = $row['pprenom'];
				$latable[$Verif->idpeople]['reg'] = $row['codepeople'];
				$latable[$Verif->idpeople]['gsm'] = $row['gsm'];
				$latable[$Verif->idpeople]['lg'] = $row['lbureau'];
				$latable[$Verif->idpeople]['EDI'] = $Verif->ErrDI;
				$cDIM = $cDIM + count($Verif->ErrDI);
				$errP = $errP + count($Verif->ErrDI);
			}

			if (count($Verif->Corrections) >= 1) {
				$latable[$Verif->idpeople]['nom'] = $row['pnom'];
				$latable[$Verif->idpeople]['pre'] = $row['pprenom'];
				$latable[$Verif->idpeople]['reg'] = $row['codepeople'];
				$latable[$Verif->idpeople]['gsm'] = $row['gsm'];
				$latable[$Verif->idpeople]['lg'] = $row['lbureau'];
				$latable[$Verif->idpeople]['MOD'] = $Verif->Corrections;
				$cCOR = $cCOR + count($Verif->Corrections);
			}
			### Anotes le people si il y a des erreurs
			if ($errP > 0) {
				$errpeople = new db();
				$errpeople->inline("UPDATE `people` SET `err` ='Y' WHERE `idpeople` ='$value' LIMIT 1;");
			} else {
				$errpeople = new db();
				$errpeople->inline("UPDATE `people` SET `err` ='N' WHERE `idpeople` ='$value' LIMIT 1;");
			}
		}
	}

#############################################################################
#####  3. Génération du rapport PDF  ########################################
#############################################################################
	$totalpeople = count($latable);

	if ($totalpeople >= 1) {

		# Variables Path PDF
		$pathpdf = 'document/temp/people/';
		$nompdf = 'RDGS-'.date("Ymd").'.pdf';

		$pdf = pdf_new();
		pdf_open_file($pdf, Conf::read('Env.root').$pathpdf.$nompdf); # définit l'emplacement de la sauvegarde
		# Infos pour le document
		pdf_set_info($pdf, "Author", "NEURO auto");
		pdf_set_info($pdf, "Title", "Rapport D-GS ");
		pdf_set_info($pdf, "Creator", "NEURO");
		pdf_set_info($pdf, "Subject", "Rapport D-GS");

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

		$maxtab = 755;
		$mintab = 25;
		#####
		$tab = 542;
		$turn = 1;
		$reste = $totalpeople;

		foreach ($latable as $idp => $value) {
			if (($tab == $maxtab) or ($turn == 1)) {
			######## Nouvelle Page ###############
				pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
				PDF_create_bookmark($pdf, "Page ".$np, "");
				pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche

			######## Première Page ###############
				if ($turn == 1) {
				## * Titre Page
					pdf_setfont($pdf, $HelveticaBold, 22); pdf_set_value ($pdf, "leading", 26);
					pdf_show_boxed($pdf, "Tableau des Erreurs People (Dimona / Groupe-S)" , 0 , 755 , 534, 26 , 'center', "");

				## * Cadres de couleur
					pdf_setcolor($pdf, "both", "cmyk", $gris[0], $gris[1], $gris[2], $gris[3]);
					pdf_rect($pdf, 0, 647, 534, 102);
					pdf_rect($pdf, 180, 583, 172, 30);
					pdf_fill_stroke($pdf);

					pdf_setcolor($pdf, "both", "cmyk", $orange[0], $orange[1], $orange[2], $orange[3]);
					pdf_rect($pdf, 0, 583, 172, 30);
					pdf_fill_stroke($pdf);

					pdf_setcolor($pdf, "both", "cmyk", $bleu[0], $bleu[1], $bleu[2], $bleu[3]);
					pdf_rect($pdf, 0, 691, 534, 15);
					pdf_fill_stroke($pdf);

					pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);

				## * Lignes
					pdf_moveto($pdf, 0, 707); pdf_lineto($pdf, $LargeurUtile, 707);
					pdf_moveto($pdf, 0, 646); pdf_lineto($pdf, $LargeurUtile, 646);
					pdf_moveto($pdf, 0, 614); pdf_lineto($pdf, $LargeurUtile, 614);
					pdf_moveto($pdf, 0, 582); pdf_lineto($pdf, $LargeurUtile, 582);
					pdf_moveto($pdf, 0, 542); pdf_lineto($pdf, $LargeurUtile, 542);
					pdf_stroke($pdf);

				## * Texte Intro
					pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
					pdf_show_boxed($pdf, "Ce rapport regroupe les erreurs trouvées dans les informations des people et qu'il est impératif de corriger pour permettre le bon fonctionnement des envois de DIMONA et Groupe-S.\rCe rapport est à vérifier tous les jours. Ci-dessous la légende explicative des erreurs." , 6 , 713 , 520, 36 , 'left', "");


				## * Titres sections
					pdf_setfont($pdf, $HelveticaBold, 16); pdf_set_value ($pdf, "leading", 24);
					pdf_show_boxed($pdf, "Récapitulatif des erreurs" , 0 , 616 , 534, 24 , 'left', "");
					pdf_show_boxed($pdf, "Liste des erreurs" , 0 , 544 , 534, 24 , 'left', "");

				## * Récapitulatifs
					pdf_setfont($pdf, $Helvetica, 20); pdf_set_value ($pdf, "leading", 28);
					pdf_show_boxed($pdf, "Dimona" , 0 , 592 , 114, 28 , 'center', "");
					pdf_show_boxed($pdf, "Groupe-S" , 180 , 592 , 114, 28 , 'center', "");
					pdf_show_boxed($pdf, "Corrigées" , 361 , 592 , 114, 28 , 'center', "");

					pdf_setfont($pdf, $HelveticaBold, 20); pdf_set_value ($pdf, "leading", 28);
					pdf_show_boxed($pdf, $cDIM , 112 , 592 , 60, 28 , 'center', "");
					pdf_show_boxed($pdf, $cGRS , 292 , 592 , 60, 28 , 'center', "");
					pdf_show_boxed($pdf, $cCOR , 473 , 592 , 60, 28 , 'center', "");

				## * Légende

					## Ligne Promoboy
					$tab = 691;
					pdf_setcolor($pdf, "both", "cmyk", $bleu[0], $bleu[1], $bleu[2], $bleu[3]);
					pdf_rect($pdf, 0, $tab, 534, 15);
					pdf_fill_stroke($pdf);

					pdf_setcolor($pdf, "both", "gray", 1, 0, 0, 0);
					pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 15);
					pdf_show_boxed($pdf, "Nr Registre" , 6 , $tab + 3 , 76, 15 , 'right', "");
					pdf_show_boxed($pdf, "Nom du promoboy" , 102 , $tab + 3 , 279, 15 , 'left', "");
					pdf_show_boxed($pdf, "GSM du promoboy" , 295 , $tab + 3 , 143, 15 , 'left', "");

					pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 13);
					pdf_show_boxed($pdf, "Id" , 478 , $tab + 3 , 55, 15 , 'right', "");

					pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);

					## Ligne Groupe-S
					$tab = $tab - 14;

					pdf_setcolor($pdf, "stroke", "gray", 0, 0, 0, 0); pdf_setcolor($pdf, "fill", "gray", 1, 0, 0, 0);
					pdf_rect($pdf, 7, $tab + 1, 10, 10);
					pdf_fill_stroke($pdf);

					pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);
					pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, "leading", 12);
					pdf_show_boxed($pdf, "Nom du Champ" , 24 , $tab + 2 , 107, 12 , 'left', "");

					pdf_setcolor($pdf, "both", "cmyk", $rouge[0], $rouge[1], $rouge[2], $rouge[3]);
					pdf_show_boxed($pdf, "Valeur" , 135 , $tab + 2 , 119, 12 , 'left', "");
					pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);

					pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
					pdf_show_boxed($pdf, "Erreur bloquante pour le Groupe-S, pour la fin du mois" , 240 , $tab + 2 , 293, 12 , 'left', "");

					## Ligne DIMONA
					$tab = $tab - 14;
					pdf_setcolor($pdf, "both", "cmyk", $orange[0], $orange[1], $orange[2], $orange[3]);
					pdf_rect($pdf, 6, $tab, 528, 12);
					pdf_fill_stroke($pdf);

					pdf_setcolor($pdf, "stroke", "gray", 0, 0, 0, 0); pdf_setcolor($pdf, "fill", "gray", 1, 0, 0, 0);
					pdf_rect($pdf, 7, $tab + 1, 10, 10);
					pdf_fill_stroke($pdf);

					pdf_setcolor($pdf, "both", "gray", 1, 0, 0, 0);
					pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, "leading", 12);
					pdf_show_boxed($pdf, "Nom du Champ" , 24 , $tab + 2 , 107, 12 , 'left', "");
					pdf_show_boxed($pdf, "Valeur" , 135 , $tab + 2 , 119, 12 , 'left', "");

					pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
					pdf_show_boxed($pdf, "Erreur Bloquante pour la DIMONA, à corriger IMMEDIATEMENT" , 240 , $tab + 2 , 293, 12 , 'left', "");

					pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);

					## Ligne Corrigée
					$tab = $tab - 14;

					pdf_setcolor($pdf, "both", "gray", 0.3, 0, 0, 0);
					pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, "leading", 12);
					pdf_show_boxed($pdf, "Nom du Champ" , 24 , $tab + 2 , 107, 12 , 'left', "");
					pdf_show_boxed($pdf, "Valeur" , 135 , $tab + 2 , 119, 12 , 'left', "");

					pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
					pdf_show_boxed($pdf, "Erreur Corrigée par le système" , 240 , $tab + 2 , 293, 12 , 'left', "");

					pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);

					$tab = 542;
				}
			}

			$more = (17 + ((count($value['EDI']) + count($value['EGS']) + count($value['MOD'])) * 14));
			$mixtab = $tab - $more;

			if ($mixtab <= $mintab) {


			######## Fin de Page   ###############

			## * Ligne de bas de page
				pdf_moveto($pdf, 0, 12); pdf_lineto($pdf, $LargeurUtile, 12);
				pdf_stroke($pdf);

			## * Imprimé le
				pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, "Imprimé le : ".date("d/m/Y") , 0 , 0 , 276, 12 , 'left', "");

			## * NEURO
				pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, "NEURO" , 0 , 0 , 534, 12 , 'center', "");

			## * Page
				pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, "Page ".$np , 271 , 0 , 262, 12 , 'right', "");

				pdf_end_page($pdf);
				$np++;
				$tab = $maxtab;

				pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
				PDF_create_bookmark($pdf, "Page ".$np, "");
				pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche

			}

		### Répétition  #####

			## Ligne Promoboy
			$tab = $tab - 17;
			pdf_setcolor($pdf, "both", "cmyk", $bleu[0], $bleu[1], $bleu[2], $bleu[3]);
			pdf_rect($pdf, 0, $tab, 534, 15);
			pdf_fill_stroke($pdf);

			pdf_setcolor($pdf, "both", "gray", 1, 0, 0 ,0);
			pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 15);
			pdf_show_boxed($pdf, $value['reg'] , 6 , $tab + 3 , 36, 15 , 'right', "");
			pdf_show_boxed($pdf, $value['pre'].' '.$value['nom'] , 55 , $tab + 3 , 279, 15 , 'left', "");
			pdf_show_boxed($pdf, $value['gsm'] , 295 , $tab + 3 , 143, 15 , 'left', "");
			pdf_show_boxed($pdf, $value['lg'] , 400 , $tab + 3 , 30, 15 , 'left', "");

			pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 13);
			pdf_show_boxed($pdf, $idp , 478 , $tab + 3 , 55, 15 , 'right', "");

			pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);

			## Lignes DIMONA
			if (count($value['EDI']) >= 1) {
				foreach ($value['EDI'] as $dim) {

					$tab = $tab - 14;
					pdf_setcolor($pdf, "both", "cmyk", $orange[0], $orange[1], $orange[2], $orange[3]);
					pdf_rect($pdf, 6, $tab, 528, 12);
					pdf_fill_stroke($pdf);

					pdf_setcolor($pdf, "stroke", "gray", 0, 0 ,0, 0); pdf_setcolor($pdf, "fill", "gray", 1, 0 ,0 ,0);
					pdf_rect($pdf, 7, $tab + 1, 10, 10);
					pdf_fill_stroke($pdf);

					pdf_setcolor($pdf, "both", "gray", 1, 0 ,0 ,0);
					pdf_setfont($pdf, $HelveticaBold, 9); pdf_set_value ($pdf, "leading", 12);
					pdf_show_boxed($pdf, $dim['Champ'] , 24 , $tab + 3 , 107, 12 , 'left', "");
					pdf_show_boxed($pdf, "'".$dim['Valeur']."'" , 135 , $tab + 3 , 119, 12 , 'left', "");

					pdf_setfont($pdf, $Helvetica, 8); pdf_set_value ($pdf, "leading", 12);
					pdf_show_boxed($pdf, $dim['Infos'] , 240 , $tab + 3 , 293, 12 , 'left', "");

					pdf_setcolor($pdf, "both", "gray", 0, 0, 0 ,0);

				}
			}

			## Lignes Groupe-S
			if (count($value['EGS']) >= 1) {
				foreach ($value['EGS'] as $dim) {
					$tab = $tab - 14;

					pdf_setcolor($pdf, "stroke", "gray", 0, 0, 0 ,0); pdf_setcolor($pdf, "fill", "gray", 1, 0 ,0 ,0);
					pdf_rect($pdf, 7, $tab + 1, 10, 10);
					pdf_fill_stroke($pdf);

					pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);
					pdf_setfont($pdf, $HelveticaBold, 9); pdf_set_value ($pdf, "leading", 12);
					pdf_show_boxed($pdf, $dim['Champ'] , 24 , $tab + 3 , 107, 12 , 'left', "");

					pdf_setcolor($pdf, "both", "cmyk", $rouge[0], $rouge[1], $rouge[2], $rouge[3]);
					pdf_show_boxed($pdf, "'".$dim['Valeur']."'" , 135 , $tab + 3 , 119, 12 , 'left', "");
					pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);

					pdf_setfont($pdf, $Helvetica, 8); pdf_set_value ($pdf, "leading", 12);
					pdf_show_boxed($pdf, $dim['Infos'] , 240 , $tab + 3 , 293, 12 , 'left', "");

				}
			}


			## Lignes Corrections
			if (count($value['MOD']) >= 1) {
				foreach ($value['MOD'] as $dim) {
					$tab = $tab - 14;

					pdf_setcolor($pdf, "both", "gray", 0.3, 0, 0 ,0);
					pdf_setfont($pdf, $HelveticaBold, 9); pdf_set_value ($pdf, "leading", 12);
					pdf_show_boxed($pdf, $dim['Champ'] , 24 , $tab + 3 , 107, 12 , 'left', "");
					pdf_show_boxed($pdf, "'".$dim['Valeur']."'" , 135 , $tab + 3 , 119, 12 , 'left', "");

					pdf_setfont($pdf, $Helvetica, 8); pdf_set_value ($pdf, "leading", 12);
					pdf_show_boxed($pdf, "Corrigé en : "."'".$dim['ValeurCorrige']."'" , 240 , $tab + 3 , 293, 12 , 'left', "");

					pdf_setcolor($pdf, "both", "gray", 0, 0 ,0 ,0);

				}
			}

			if ($reste == 1) {

			######## Fin de Page   ###############

			## * Ligne de bas de page
				pdf_moveto($pdf, 0, 12); pdf_lineto($pdf, $LargeurUtile, 12);
				pdf_stroke($pdf);

			## * Imprimé le
				pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, "Imprimé le : ".date("d/m/Y") , 0 , 0 , 276, 12 , 'left', "");

			## * NEURO
				pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, "NEURO" , 0 , 0 , 534, 12 , 'center', "");

			## * Page
				pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
				pdf_show_boxed($pdf, "Page ".$np , 271 , 0 , 262, 12 , 'right', "");

				pdf_end_page($pdf);
				$np++;
			}


		### Fin Répétition ##

			$turn++;
			$reste--;
		}
		#####


		pdf_end_document($pdf, '');
		pdf_delete($pdf); # Efface le fichier en mémoire
	} else {
		echo 'No errors';
	}

?>