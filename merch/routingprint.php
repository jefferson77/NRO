<?php
# Entete de page
define('NIVO', '../');
?>
<html>
<head>
	<link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8">
</head>
<body>
<?php
$Titre = 'ROUTING';
$Style = 'merch';

# Classes utilisees
require_once(NIVO."nro/fm.php");
include (NIVO."classes/hh.php") ;

include NIVO."classes/geocalc.php";

#### Fonctions #########################################################################################################
function gdist ($destfromlat, $destfromlong, $desttolat, $desttolong) {
	$coeff = array(0 => 1, 5 => 1.40, 10 => 1.35, 20 => 1.42, 30 => 1.34, 40 => 1.30, 50 => 1.42, 75 => 1.29, 100 => 1.28);

	$geoloc = new GeoCalc();
	$dist = $geoloc->EllipsoidDistance($destfromlat, $destfromlong, $desttolat, $desttolong);
	$dist = round($dist);

	##### Chercher le bon coefficient dans l'array #####
	foreach($coeff as $k => $v) {
		if($dist >= $k) {
			$x = $v;
		}
	}

	return round($dist * 1.7);
#	return round($dist * $x);
}

## Formater un nombre négatif
	function fnega10($nombre) {
		if ($nombre < -10) {
			$formated = '<Font color="red">'.$nombre.'</font>';
		} else {
			$formated = $nombre;
		}
		return $formated;
	}
?>
<?php

switch($_GET['act']) {
##################################################################################################################################
### Modif Mission ###############################################################################################################################
	case"modmis":

		$modr = new db();

		switch (substr($_POST['idmiss'], 0, 2)) {
			case"AN":
				$sql = "UPDATE animation SET
					hin1 = '".ftimebk($_POST['h1'])."',
					hout1 = '".ftimebk($_POST['h2'])."',
					hin2 = '".ftimebk($_POST['h3'])."',
					hout2 = '".ftimebk($_POST['h4'])."',
					kmpaye = '".$_POST['kmp']."',
					kmfacture = '".$_POST['kmf']."'
				WHERE idanimation = '".substr($_POST['idmiss'], 2)."'";
			break;
			case"VI":
				$sql = "UPDATE vipmission SET
					vipin = '".ftimebk($_POST['h1'])."',
					vipout = '".ftimebk($_POST['h2'])."',
					brk = '".$_POST['hb']."',
					night = '".$_POST['hs']."',
					km = '".$_POST['kmf']."',
					vkm = '".$_POST['kmp']."'
				WHERE idvip = '".substr($_POST['idmiss'], 2)."'";
			break;
			case"ME":
				$sql = "UPDATE merch SET
					hin1 = '".ftimebk($_POST['h1'])."',
					hout1 = '".ftimebk($_POST['h2'])."',
					hin2 = '".ftimebk($_POST['h3'])."',
					hout2 = '".ftimebk($_POST['h4'])."',
					kmpaye = '".$_POST['kmp']."',
					kmfacture = '".$_POST['kmf']."'
				WHERE idmerch = '".substr($_POST['idmiss'], 2)."'";
			break;

		}

		$modr->inline($sql);

##################################################################################################################################
### Routing ####################################################################################################################################
	case"show":

	## Infos People
		$idpeop = new db();

		$sql = "SELECT idpeople, pnom, pprenom, peoplehome,
		glat1,  glong1, adresse1, num1, bte1, cp1, ville1, pays1,
		glat2, glong2,adresse2, num2, bte2, cp2, ville2, pays2
		FROM people WHERE 1 ";
		if (!empty($_GET['idp'])) {
			$sql .= "AND idpeople = '".$_GET['idp']."' ";
		} else {
			if (!empty($_POST['reg'])) 		$sql .= "AND codepeople = '".$_POST['reg']."' ";
			if (!empty($_POST['nom'])) 		$sql .= "AND pnom LIKE '%".$_POST['nom']."%' ";
			if (!empty($_POST['prenom'])) 	$sql .= "AND pprenom LIKE '%".$_POST['prenom']."%' ";
		}


		$idpeop->inline($sql);

		$idp = mysql_fetch_array($idpeop->result);

		if ($idp['peoplehome'] == 2) {
			$idp['glong1'] = $idp['glong2'];
			$idp['glat1']  = $idp['glat2'];

			$idp['adresse'] = $idp['adresse2'].' '.$idp['num2'].' '.$idp['cp2'].' '.$idp['ville2'];
		} else {
			$idp['adresse'] = $idp['adresse1'].' '.$idp['num1'].' '.$idp['cp1'].' '.$idp['ville1'];
		}

		# illu loc people
		if (($idp['glat1'] > 0) and ($idp['glong1'] > 0)) {
			$illu = '<img src="'.STATIK.'illus/geoloc.png" alt="geoloc.png" width="10" height="10">';
		} else { $illus = '';}

		echo '<h1>Routing de '.$idp['pprenom'].' '.$idp['pnom'].'</h1>';

	## Periode
		# date
		if (!empty($_POST['date'])) {
			if (strstr($_POST['date'], "...")) {
				$dd = explode("...", $_POST['date']);

				$datein  = fdatebk($dd[0]);
				$dateout = fdatebk($dd[1]);
			} else {
				$datein  = fdatebk($_POST['date']);
				$dateout = fdatebk($_POST['date']);
			}
		} elseif ((!empty($_GET['din'])) and (!empty($_GET['din']))) {
			$datein  = $_GET['din'];
			$dateout = $_GET['dout'];
		}

		# semaine
#		if (!empty($_POST['sem'])) {
#
#		}

		echo '<h2>Periode du '.fdate($datein).' au '.fdate($dateout).'</h2>';

	## Table des missions
		$ht = new hh();
		$ht->hhtable ($idp['idpeople'], $datein, $dateout) ;

	## Affichage
?>
	<table class="fac" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
		<thead>
			<tr>
				<th>Mission</th>
				<th width="9">F</th>
				<th width="30">de</th>
				<th width="30">a</th>
				<th width="30">de</th>
				<th width="30">a</th>
				<th width="200"></th>
				<th>Client</th>
				<th width="10"></th>
				<th>Magasin</th>
				<th>Demande</th>
				<th>paye</th>
				<th width="13"></th>
				<th width="15"></th>
			</tr>
		<?php
				ksort($ht->prtab);

				foreach ($ht->prtab as $date => $da) {


				# tri par hh
					foreach ($da as $l) {
						$ld[$l['hh']] = $l;
					}

					krsort ($ld);

				# Initialisation du trajet
					$destfromlat = $idp['glat1'];
					$destfromlong = $idp['glong1'];
					$destfromadr = $idp['glong1'];

		?>
			<tr>
				<td colspan="2"><?php echo fdate($date); ?></td>
				<td colspan="4">Sem <?php echo date("Y", strtotime($date));?></td>
				<td><img src="routingpng.php?entete=1" alt="" width="200" height="13"></td>
				<td></td>
				<td><?php echo $illu; ?></td>
				<td><?php echo $idp['adresse'] ?></td>
				<td colspan="4"></td>
			</tr>
		</thead>

<?php
	$nbmis = count($ld);
	$st = 1;
	$i = 0;

	foreach ($ld as $d) {

		$d['shop'] = trim($d['shop']);

	## Illus
		if (($d['shoplat'] > 0) and ($d['shoplong'] > 0)) {
			$illu = '<img src="'.STATIK.'illus/geoloc.png" alt="geoloc.png" width="10" height="10" title="'.$d['shoplat'].' '.$d['shoplong'].'">';
		} else { $illu = '';}

		if ($d['idfac'] > 0) {
			$illuf = '<img src="'.STATIK.'illus/fac.png" alt="" width="9" height="10" title="'.$d['idfac'].'">';
			$disf = ' disabled';
		} else {
			$illuf = '';
			$disf = '';
		}

	# MapLink

		$url = 'http://www.multimap.com/map/aproute.cgi?client=public&lang=&rn=EU&input_rt=aproute_pan';
		# start
		$url .= '&startcountry='.$destfrompays;
		$url .= '&startrd='.$destfromadr;
		$url .= '&starttown='.$destfromloca;
		$url .= '&startpc='.$destfromcp;
		# end
		$url .= '&endcountry='.$desttopays;
		$url .= '&endrd='.$desttoadr;
		$url .= '&endtown='.$desttoloca;
		$url .= '&endpc='.$desttocp;

		$url .= '&qs=q&starttime='.date("H").'%3A'.date("i");


		$mapillu = '<a href="'.$url.'" target="_blank"><img src="'.STATIK.'illus/map.png" alt="" width="13" height="13" border="0"></a>';

	## Distance
		$desttolat = $d['shoplat'];
		$desttolong = $d['shoplong'];

		$kmauto = gdist($destfromlat, $destfromlong, $desttolat, $desttolong);

		# retour ajoute a la dernière mission
		if ($st == $nbmis) {
			$kmauto += gdist($desttolat, $desttolong, $idp['glat1'], $idp['glong1']);
		}

		# EAS
		if (($d['client'] == 'Carrefour EAS') and ($kmauto > 30)) {
			$kmauto = "30 <font style=\"color: #999;\">(".$kmauto.")</font>";
		}

		# Shop non localises et aucun km dans la mission
		if (($d['shoplat'] == 0) and ($d['shoplong'] == 0) and ($d['kmp'] == 0)) {
			$kmauto = 0;
		}

		## Si shop inlocalisable (BRUXELLES, LIege, Mons...) ou shop vide
		$inlocshops = array('4308', '2882', '2880', '3433', '5354', '2923');
		if ((in_array ($d['idshop'], $inlocshops)) or ($d['idshop'] == '')) {
			$kmauto = $d['kmp'];
		}



	## Diff
		$diff = $kmauto - $d['kmp'];



	## Form
	echo '<form action="'.$_SERVER['PHP_SELF'].'?act=modmis&idp='.$idp['idpeople'].'&din='.$datein.'&dout='.$dateout.'" method="post">';
	echo '<input name="idmiss" type="hidden" value="'.$d['secteur'].$d['idmission'].'">';

		#> Changement de couleur des lignes #####>>####
		$i++;
		if (fmod($i, 2) == 1) {
			echo '<tr class="even">';
		} else {
			echo '<tr class="odd">';
		}
		#< Changement de couleur des lignes #####<<####

?>
		<td class="data"><?php echo $d['secteur'].' '.$d['idmission']; ?></td>
		<td class="data"><?php echo $illuf; ?></td>
		<td class="data"><input type="text" size="6" name="h1" value="<?php echo ftime($d['h1']); ?>"></td>
		<td class="data"><input type="text" size="6" name="h2" value="<?php echo ftime($d['h2']); ?>"></td>
<?php if ($d['secteur'] == 'VI') { ?>
		<td class="data">B<input style="width: auto;" type="text" size="2" name="hb" value="<?php echo fnbr($d['hb']); ?>"></td>
		<td class="data">N<input style="width: auto;" type="text" size="2" name="hs" value="<?php echo fnbr($d['hs']); ?>"></td>
<?php } else { ?>
		<td class="data"><input type="text" size="6" name="h3" value="<?php echo ftime($d['h3']); ?>"></td>
		<td class="data"><input type="text" size="6" name="h4" value="<?php echo ftime($d['h4']); ?>"></td>
<?php } ?>
		<td class="data"><img src="routingpng.php?mis=<?php echo $d['secteur'].$d['idmission']; ?>" alt="routingpng" width="200" height="13"></td>
		<td class="data"><?php echo $d['client']; ?></td>
		<td class="data"><?php echo $illu; ?></td>
		<td class="data"><?php echo $d['shop']; ?></td>
		<td class="dataF"><input type="text" size="6" name="kmf" value="<?php echo $d['kmf']; ?>" <?php echo $disf; ?>></td>
		<td class="dataP"><input type="text" size="6" name="kmp" value="<?php echo $d['kmp']; ?>" <?php echo $disp; ?>></td>
		<td class="data"><?php echo $mapillu; ?></td>
		<td class="data"><input type="submit" class="btn send></td>

	</tr>
	</form>
<?php

		if (!empty($d['shop'])) {
			$destfromlat = $d['shoplat'];
			$destfromlong = $d['shoplong'];
			$destfromadr = $d['shopadr'];
		}

		$totkmf += $d['kmf'];
		$totkmp += $d['kmp'];
		$totkmauto += $kmauto ;
		$totdiff += $diff;

		unset($kmauto);
		unset($diff);

		$st++;

	} ?>
	<tr>
		<td class="jobtot"></td>
		<td class="jobtot"></td>
		<td class="jobtot" colspan="4"></td>
		<td class="jobtot"></td>
		<td class="jobtot"></td>
		<td class="jobtot"></td>
		<td class="jobtot"></td>
		<td class="jobtotF"><?php echo $totkmf; ?></td>
		<td class="jobtotP"><?php echo $totkmp; ?></td>
		<td class="jobtot"></td>
		<td class="jobtot"></td>
	</tr>
	<tr><td colspan="14" style="background-color: #D3D3D3;">&nbsp;</td></tr>

	<?php
			## Totaux max
			$totmaxkmf += $totkmf;
			$totmaxkmp += $totkmp;
			$totmaxkmauto += $totkmauto;
			$totmaxdiff += $totdiff;

			unset($totkmf);
			unset($totkmp);
			unset($totkmauto);
			unset($totdiff);
			unset($ld);

		}

?>
	<tr>
		<td class="bigtot"></td>
		<td class="bigtot"></td>
		<td class="bigtot" colspan="4"></td>
		<td class="bigtot"></td>
		<td class="bigtot"></td>
		<td class="bigtot"></td>
		<td class="bigtot"></td>
		<td class="bigtotF"><?php echo $totmaxkmf; ?></td>
		<td class="bigtotP"><?php echo $totmaxkmp; ?></td>
		<td class="bigtot"></td>
		<td class="bigtot"></td>
	</tr>

</table><?php
	break;
##################################################################################################################################
### Recherche ##################################################################################################################################
	default:
	case"search":
?>
<fieldset>
	<legend>Recherche Routing</legend>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=show" method="post">
		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
			<tr>
				<td>
					People
				</td>
				<td>
					Num <input type="text" size="5" name="reg" value=""> Nom <input type="text" size="20" name="nom" value=""> Prenom <input type="text" size="20" name="prenom" value="">
				</td>
			</tr>
			<tr>
				<td>
					Periode
				</td>
				<td>
					Date <input type="text" size="20" name="date" value="">
				</td>
			</tr>
			<tr>
				<td>
				</td>
				<td align="center">
					<input type="submit" name="Modifier" value="Rechercher">
				</td>
			</tr>
		</table>
	</form>
</fieldset>
<?php
	break;
}
?>
</body>
</html>
