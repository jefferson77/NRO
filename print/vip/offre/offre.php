<?php
define('NIVO', '../../../');

# Classes utilisées
include NIVO.'classes/vip.php';
include NIVO.'classes/facture.php';

# Entete de page
$Style = 'vip';
include NIVO."includes/ifentete.php";

# vars
if (!empty($_POST['idvipjob'])) { $_GET['idvipjob'] = $_POST['idvipjob']; }

### update de la datedevis
if (!empty($_POST['updated'])) {
	$modif = new db();
	$modif->inline("UPDATE vipjob SET datedevis = '".$_POST['datedevis']."' WHERE idvipjob = '".$_GET['idvipjob']."'");
}

### get de la datedevis et creation d'un PDF si vide
$job = new db();
$job->inline("SELECT datedevis FROM `vipjob` WHERE `idvipjob` = '".$_GET['idvipjob']."'");
$infosjob = mysql_fetch_array($job->result);
$datedevis = $infosjob['datedevis'];

# Path PDF  ## Ne pas mettre plus bas, on en a besoin pour afficher la liste.
$pathpdf = '/document/offre/vip/';
$jobnum = str_repeat('0', 5 - strlen($_GET['idvipjob'])).$_GET['idvipjob'];
$nompdf = 'offre-'.$jobnum.'-'.date("Ymd").'.pdf';

if (empty($datedevis) or ($datedevis == '0000-00-00') or (!empty($_POST['creation']))) {

	### update de la datedevis
	$datedevis = date("Y-m-d");#pour comparaison dans tableau
	$modif = new db();
	$modif->inline("UPDATE vipjob SET datedevis = '".$datedevis."' WHERE idvipjob = '".$_GET['idvipjob']."'");

	######## Continuer le changement de moteur a partir d'ici ####### 2006 09 12

	################### Get infos du job ########################
	$detail = new db();
	$detail->inline("SET NAMES 'latin1'");
	$infos = $detail->getRow("SELECT
	j.idvipjob, j.idagent, j.idcommercial, j.reference, j.noteprest, j.notedeplac, j.noteloca, j.notefrais, j.etat, j.forfait, j.bondecommande,
	c.idclient, c.societe, c.adresse, c.cp, c.ville, c.tvheure05, c.tvheure6, c.tvnight, c.tvkm, c.tvforfait, c.tv150, c.codeclient, c.codetva, c.tva, c.astva, c.pays, c.hforfait,
	o.onom , o.oprenom , o.tel, o.fax, o.qualite, o.langue,
	a.nom AS nomagent, a.prenom AS prenomagent, a.atel, a.agsm, a.qualite as qualiteagent,
	cm.nom AS nomcom, cm.prenom AS prenomcom, cm.atel AS telcom, cm.agsm AS gsmcom, cm.qualite as qualitecom
	FROM vipjob j
	LEFT JOIN client c ON j.idclient = c.idclient
	LEFT JOIN agent a ON j.idagent  = a.idagent
	LEFT JOIN agent cm ON j.idcommercial = cm.idagent
	LEFT JOIN cofficer o ON j.idcofficer  = o.idcofficer
	WHERE j.idvipjob = ".$_GET['idvipjob']);

	################### Phrasebook ########################
	switch ($infos['langue']) {
		case "NL":
			include 'nl.php';
			setlocale(LC_TIME, 'nl_NL');
			$infos['qualiteagent'] = qualiteNL($infos['qualiteagent']);
			$infos['qualite'] = qualiteNL($infos['qualite']);
		break;
		case "FR":
			include 'fr.php';
			setlocale(LC_TIME, 'fr_FR');
		break;
		default:
			$phrase = array('');
			echo '<br> Langue pas d&eacute;finie pour le client : '.$infos['idclient']." ".$infos['societe'];
	}

	################### Phrasebook ########################


	################### Calcul des détails et des totaux ########################

		# Recherche des missions en devis
		$vip = new db();

		if ($infos['etat'] == 0) { $base = 'vipdevis'; $mode = 'D'; } else { $base = 'vipmission'; $mode = 'M'; }

			$vip->inline("SELECT idvip, ts, km, fkm, unif, net, loc1, loc2, cat, disp, h200 FROM `$base` WHERE `idvipjob` = ".$_GET['idvipjob']." ORDER BY 'vipdate', 'vipactivite'");

			$MontPrest = 0;
			$MontDepl = 0;
			$MontLoc = 0;
			$MontFrais = 0;
			$MontCat = 0;
			$MontDisp = 0;
			$MontFr = 0;
			$MontFraisDimona = 0;
			$MontHeuresDimona = 0;

			$DetHhigh = 0;
			$DetHlow = 0;
			$DetHnight = 0;
			$DetH150 = 0;
			$DetH200 = 0;
			$DetHspec = 0;

			$DetKm = 0;
			$DetFKm = 0;

		while ($row = mysql_fetch_array($vip->result)) {
				$fich = new corevip ($row['idvip'], $mode);

				## Forfaits

				if ($infos['forfait'] == 'Y') { # forfaitaire
					if ($row['h200'] == 1) {
						$ForfNbr += 2;
						$MontPrest += $infos['tvforfait'] * 2;
					} else {
						$ForfNbr++;
						$MontPrest += $infos['tvforfait'];
					}
				} else { # horaire
					$MontPrest += ($fich->hhigh * $infos['tvheure05']) + ($fich->hlow * $infos['tvheure6']) + ($fich->hnight * $infos['tvnight']) + ($fich->h150 * $infos['tv150']) + ($fich->hspec * $row['ts']);
				}

				$MontDepl  += ($row['km'] * $infos['tvkm']) + $row['fkm'];
				$MontLoc   += $row['unif'] + $row['net'] + $row['loc1'] + $row['loc2'] ;
				$MontCat += $row['cat'] ;
				$MontDisp += $row['disp'];
				$MontFr += $fich->MontNfrais;

				$MontFraisDimona += $fich->FraisDimona;
				$MontHeuresDimona += $fich->HeuresDimona;
				$tarifdimona = $fich->tarifdimona;

				$DetHhigh  += $fich->hhigh;
				$DetHlow   += $fich->hlow;
				$DetHnight += $fich->hnight;
				$DetH150   += $fich->h150;
				$DetH200   += $row['h200'];
				$DetHspec  += $fich->hspec;

				$DetKm  += $row['km'];
				$DetFKm += $row['fkm'];
		}

		# Totaux

			$MontFrais = $MontCat + $MontDisp + $MontFr + $MontFraisDimona;
			$MontHTVA = $MontPrest + $MontDepl + $MontLoc + $MontFrais ;

			$rubhortva = rubriqueTVA($infos['astva'], $infos['tva'], $infos['pays'], 'OFFVI');
			$MontTVA = round(tauxTVA($rubhortva) * $MontHTVA, 2);

			$MontTTC = $MontHTVA + $MontTVA;

			$Detail['prestations'] = '';
			$Detail['deplacements'] = '';
			$Detail['frais'] = '';

		switch ($infos['langue']) {
				case "FR":
					# Detail des prestations

						if ($infos['forfait'] == 'Y') { # forfaitaire
								$Detail['prestations'] .= $ForfNbr." forfaits x ".fpeuro($infos['tvforfait'])."\r";

						} else { # horaire
							if ($DetHhigh > 0) {
								$Detail['prestations'] .= fnbr($DetHhigh)." heures au tarif de base (- de 6h) x ".fpeuro($infos['tvheure05'])."\r";
							}
							if ($DetHlow > 0) {
								$Detail['prestations'] .= fnbr($DetHlow)." heures au tarif préférentiel (6h et +) x ".fpeuro($infos['tvheure6'])."\r";
							}
							if ($DetHnight > 0) {
								$Detail['prestations'] .= fnbr($DetHnight)." heures au tarif de nuit x ".fpeuro($infos['tvnight'])."\r";
							}
							if ($DetH150 > 0) {
								$Detail['prestations'] .= fnbr($DetH150)." heures supplémentaires x ".fpeuro($infos['tv150'])."\r";
							}
							if ($DetHspec > 0) {
								$Detail['prestations'] .= fnbr($DetHspec)." heures au tarif spécial\r";
							}
							if ($DetH200 > 0) {
								$Detail['prestations'] .= "! ".fnbr($DetH200)." prestations jour ferié ou dimanche (200%)";
							}
						}

					# Detail des déplacement
						if ($DetKm > 0) {
							$Detail['deplacements'] .= fnbr($DetKm)." km au tarif ".fpeuro($infos['tvkm'])." (selon décompte réel en fin d'action)\r";
						}
						if ($DetFKm > 0) {
							# $Detail['deplacements'] .= fpeuro($DetFKm)." de forfait.";
						}
						if($infos['hforfait'] == 1 and empty($DetFKm) and empty($DetKm)) {
							$Detail['deplacements'] .= $phrase[53].fnbr($infos['tvkm']).$phrase[54]."\r";
						}

					# Detail des Frais
						if ($MontCat > 0) {
							$Detail['frais'] .= fnbr($MontCat)." Euros de Catering\r";
						}

						if ($MontDisp > 0) {
							$Detail['frais'] .= fnbr($MontDisp)." Euros de Dispatching\r";
						}

						if ($MontFr > 0) {
							$Detail['frais'] .= fnbr($MontFr)." Euros de Frais\r";
						}

						if ($MontFraisDimona > 0) {
							$Detail['frais'] .= fnbr($MontHeuresDimona)." x ".fnbr($tarifdimona)." Euros de frais DIMONA\r";
						}
				break;
				case "NL":
					# Detail des prestations
						if ($infos['forfait'] == 'Y') { # forfaitaire
								$Detail['prestations'] .= $ForfNbr." forfaits x ".fpeuro($infos['tvforfait'])."\r";

						} else { # horaire
							if ($DetHhigh > 0) {
								$Detail['prestations'] .= fnbr($DetHhigh)." uren aan basis tarief (- dan 6u) x ".fpeuro($infos['tvheure05'])."\r";
							}
							if ($DetHlow > 0) {
								$Detail['prestations'] .= fnbr($DetHlow)." uren aan voordelig tarief (6u en +) x ".fpeuro($infos['tvheure6'])."\r";
							}
							if ($DetHnight > 0) {
								$Detail['prestations'] .= fnbr($DetHnight)." uren aan nachttarief x ".fpeuro($infos['tvnight'])."\r";
							}
							if ($DetH150 > 0) {
								$Detail['prestations'] .= fnbr($DetH150)." extra uren x ".fpeuro($infos['tv150'])."\r";
							}
							if ($DetHspec > 0) {
								$Detail['prestations'] .= fnbr($DetHspec)." uren aan speciaal tarief\r";
							}
							if ($DetH200 > 0) {
								$Detail['prestations'] .= "! ".fnbr($DetH200)." prestatie(s) tijdens feestdag of 2de zondag (200%)";
							}
						}

					# Detail des déplacement
						if ($DetKm > 0) {
							$Detail['deplacements'] .= fnbr($DetKm)." km aan tarief ".fpeuro($infos['tvkm'])." (volgens reële afrekening op einde van aktie)\r";
						}
						if ($DetFKm > 0) {
							# $Detail['deplacements'] .= fpeuro($DetFKm)." vaste vergoeding.\r";
						}
						if($infos['hforfait'] == 1 and empty($DetFKm) and empty($DetKm)) {
							$Detail['deplacements'] .= $phrase[53].fnbr($infos['tvkm']).$phrase[54]."\r";
						}

					# Detail des Frais
						if ($MontCat > 0) {
							$Detail['frais'] .= fnbr($MontCat)." Eur Catering\r";
						}

						if ($MontDisp > 0) {
							$Detail['frais'] .= fnbr($MontDisp)." Eur Dispatching\r";
						}

						if ($MontFr > 0) {
							$Detail['frais'] .= fnbr($MontFr)." Eur Onkosten\r";
						}

						if ($MontFraisDimona > 0) {
							$Detail['frais'] .= fnbr($MontHeuresDimona)." x ".fnbr($tarifdimona)." Eur DIMONA kosten\r";
						}
				break;
		}

	################### Fin des détails et des totaux ###########################

	$pdf = pdf_new();
	pdf_open_file($pdf, '/NRO'.$pathpdf.$nompdf); # définit l'emplacement de la sauvegarde
	# Infos pour le document

	pdf_set_info($pdf, "Author", $infos['prenomagent'].' '.$infos['nomagent']);
	pdf_set_info($pdf, "Title", $phrase[1].$infos['idvipjob']);
	pdf_set_info($pdf, "Creator", "NEURO");
	pdf_set_info($pdf, "Subject", $phrase[2]);

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

	# Page d'entete ###########
		include 'pagegarde.php';
	# Fin Page d'entete #######


	# Pages de détail ###########
		include 'pagedetail.php';
	# Fin Pages de détail #######

	pdf_end_document($pdf, '');
	pdf_delete($pdf); # Efface le fichier en mémoire
	# Lien vers le PDF
	### /pour création d'une nouvelle offre
}
?>
<br>
<?php
####### Création du tableau des offres
?>
<form action="<?php echo $_SERVER['PHP_SELF'].'?idvipjob='.$_GET['idvipjob'];?>" method="post">
	<input type="hidden" name="updated" value="yes">

	<table border="0" cellspacing="1" cellpadding="4" align="center" bgcolor="#ffffff">
	<tr>
		<td><h2 align="center">Offres du job <?php echo $jobnum; ?></h2></td>
	</tr>
<?php
$d = opendir ('/NRO'.$pathpdf);

while ($name = readdir($d)) {
if (
	($name != '.') and
	($name != '..') and
	($name != 'index.php') and
	($name != 'index2.php') and
	(strchr($name, '-'.$jobnum.'-')) and
	($name != 'temp')
) {
	# format de date du fichier en yyyy-mm-dd et dd/mm/yyyy
	$explo = explode("-", $name);
	$explodate = $explo[2];
	$explo = explode(".", $explodate);
	$explodate = $explo[0];
	$nomfichier = $explo[0];
	$nomfichier = substr($nomfichier, 0, 4).'-'.substr($nomfichier, 4, 2).'-'.substr($nomfichier, 6, 2);
	$nomfichier2 = fdate($nomfichier);
	if ($nomfichier == $datedevis) { $nomfichier2 = '<font color="#003399"><b>'.$nomfichier2.'</b></font>'; $checked = 'checked';} else {$checked = '';}
?>
	<tr>
		<td align="center">
			<A href="<?php echo $pathpdf.$name ;?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"> <?php echo $nomfichier2; ?></A> <input type="radio" name="datedevis" value="<?php echo $nomfichier; ?>" <?php echo $checked; ?>>
		</td>
	</tr>
<?php }
}

closedir ($d);
#######/ Création du tableau des offres
 ?>
	<tr>
		<td align="center">
			<input type="submit" name="updat" value="Mettre la base de donn&eacute;e &agrave; jour">
		</td>
	</tr>

</table>
</form>
<br>
<br>
<div align="center">
<form action="<?php echo $_SERVER['PHP_SELF'].'?idvipjob='.$_GET['idvipjob'];?>" method="post">
	<input type="hidden" name="creation" value="yes">
	<input type="submit" name="nouvelle" value="Cr&eacute;er une nouvelle offre">
</form>
<br>
<br><a href="javascript:window.close();"><b>&gt; Fermer &lt;</b></a>
<br>
<br>
</div>
<table border="0" cellspacing="1" cellpadding="4" align="center" bgcolor="#ffffff">
	<tr>
		<td>
			<font color="black">
				Note : Le fichier en <img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"> <font color="#003399"><b>Bleu</b></font> est le fichier de l'offre qui a &eacute;t&eacute; envoy&eacute;e au client. (Pas un brouillon mais la v&eacute;ritable offre)<br><br>
				Apr&egrave;s la cr&eacute;ation d'une nouvelle offre, il faut, si n&eacute;cessaire, mettre la base de donn&eacute;e &agrave; jour afin de la valider comme &eacute;tant "l'offre qui a &eacute;t&eacute; envoy&eacute;e au client".
			</font>
		</td>
	</tr>
	<tr>
		<td>
			<br>
			<font color="red">ATTENTION ! </font>
			<font color="black">Lors de la cr&eacute;ation de la premi&egrave;re offre, <font color="red"><b>si vous souhaitez publier l'offre online</b></font>, il faut imp&eacute;rativement mettre la base de donn&eacute;e &agrave; jour afin de la publier !<br>
			</font>
		</td>
	</tr>
</table>

<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
