<?php
# Entete de page
define('NIVO', '../');
$Titre = 'ROUTING';
$Style = 'merch';

# Classes utilisees
include (NIVO."classes/hh.php") ;

include NIVO."includes/entete.php" ;
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

	return round($dist * $x);
}
?>
<link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8">
<div id="centerzonelarge" style="background-color: #D3D3D3;">
		<table class="fac" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
<?php

		$datein = '2006-10-01';
		$dateout = '2006-10-31';

		$lespeoples = new db ();
		$lespeoples->inline("SELECT COUNT(*) AS Rows , idpeople FROM grps.salaires0610 GROUP BY idpeople ORDER BY idpeople");

		while ($ip = mysql_fetch_array($lespeoples->result)) {
		## Infos People
			$idpeop = new db();
	
			$sql = "SELECT idpeople, pnom, pprenom, peoplehome, codepeople,
			glat1,  glong1, adresse1, num1, bte1, cp1, ville1, pays1, 
			glat2, glong2,adresse2, num2, bte2, cp2, ville2, pays2
			FROM people WHERE idpeople = '".$ip['idpeople']."'";
		
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
			} else { $illus = '';
				$errpeop[$idp['idpeople']]++;
			}
	
		
		## Table des missions
			$ht = new hh(); 
			$ht->hhtable ($idp['idpeople'], $datein, $dateout) ;
	
		## Affichage
/*
	?>
	<thead>
		<tr>
			<th>Mission</th>
			<th>de</th>
			<th>a</th>
			<th>de</th>
			<th>a</th>
			<th>Client</th>
			<th>hh</th>
			<th width="10"></th>
			<th>Magasin</th>
			<th>km F</th>
			<th>km P</th>
			<th>AUTO</th>
			<th>Diff</th>
		</tr>
	<?php
*/			ksort($ht->prtab);
	
			foreach ($ht->prtab as $date => $da) {
		
	
			# tri par hh	
				foreach ($da as $l) {
					$ld[$l['hh']] = $l;
				}
	
				krsort ($ld);
	
			# Initialisation du trajet
				$destfromlat = $idp['glat1'];			
				$destfromlong = $idp['glong1'];			
		
/*	?>
		<tr>
			<td><?php echo fdate($date); ?></td>
			<td colspan="6"></td>
			<td><?php echo $illu; ?></td>
			<td><?php echo $idp['adresse'] ?></td>
			<td colspan="4"></td>
		</tr>
	</thead>
	<?php
*/
		$nbmis = count($ld);
		$st = 1;
		$i = 0;
		
		foreach ($ld as $d) { 
			$d['shop'] = trim($d['shop']);

		## Illu
			if (($d['shoplat'] > 0) and ($d['shoplong'] > 0)) {
				$illu = '<img src="'.STATIK.'illus/geoloc.png" alt="geoloc.png" width="10" height="10" title="'.$d['shoplat'].' '.$d['shoplong'].'">';		
			} else {
				$illu = '';
				$errshop[$d['idshop']]++;
			}
			
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
				$kmauto = 30;		
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
/*	
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
			<td class="data"><?php echo ftime($d['h1']); ?></td>
			<td class="data"><?php echo ftime($d['h2']); ?></td>
			<td class="data"><?php echo ftime($d['h3']); ?></td>
			<td class="data"><?php echo ftime($d['h4']); ?></td>
			<td class="data"><?php echo $d['client']; ?></td>
			<td class="data"><?php echo $d['hh']; ?></td>
			<td class="data"><?php echo $illu; ?></td>
			<td class="data"><?php echo $d['shop']; ?></td>
			<td class="dataF"><?php echo $d['kmf']; ?></td>
			<td class="dataP"><?php echo $d['kmp']; ?></td>
			<td class="data"><?php echo $kmauto; ?></td>
			<td class="dataD"><?php echo fnega($diff); ?></td>
		</tr><?php
*/	
			if (!empty($d['shop'])) {
				$destfromlat = $d['shoplat'];			
				$destfromlong = $d['shoplong'];			
			}
	
			$totkmf += $d['kmf'];
			$totkmp += $d['kmp'];
			$totkmauto += $kmauto ;		
			$totdiff += $diff;
			
			unset($kmauto);
			unset($diff);
			
			$st++;
		
		}
		
/*		?>
		<tr>
			<td class="jobtot"></td>
			<td class="jobtot"><?php echo $start; ?></td>
			<td class="jobtot"><?php echo $stop; ?></td>
			<td class="jobtot"></td>
			<td class="jobtot"></td>
			<td class="jobtot"><?php echo $ht->hhtable[$date]; ?></td>
			<td class="jobtot"></td>
			<td class="jobtot"></td>
			<td class="jobtot"></td>
			<td class="jobtotF"><?php echo $totkmf; ?></td>
			<td class="jobtotP"><?php echo $totkmp; ?></td>
			<td class="jobtot"><?php echo $totkmauto; ?></td>
			<td class="jobtotD"><?php echo fnega($totdiff); ?></td>
		</tr>
		<tr><td colspan="29" style="background-color: #D3D3D3;">&nbsp;</td></tr>
	
		<?php 
*/
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
			<td class="bigtot" colspan="3"><?php echo $idp['codepeople'].' '.$idp['pnom'].' '.$idp['pprenom']; ?></td>
			<td class="bigtot"></td>
			<td class="bigtot"></td>
			<td class="bigtot"></td>
			<td class="bigtot"></td>
			<td class="bigtot"></td>
			<td class="bigtot"></td>
			<td class="bigtotF"><?php echo $totmaxkmf; ?></td>
			<td class="bigtotP"><?php echo $totmaxkmp; ?></td>
			<td class="bigtot"><?php echo $totmaxkmauto; ?></td>
			<td class="bigtotD"><?php echo fnega($totmaxdiff); ?></td>
		</tr>
<?php 
		unset($totmaxkmf);
		unset($totmaxkmp);
		unset($totmaxkmauto);
		unset($totmaxdiff);

		}
?> 	</table> 

<?php 
foreach ($errshop as $shop => $nbr) {

	echo $shop.'<br>';

}


?>

Peoples
<?php 
foreach ($errpeop as $shop => $nbr) {

	echo $shop.'<br>';

}
?>


</div>
<div id="topboutons">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=search"><img src="<?php echo STATIK ?>illus/ajouter.gif" alt="ajouter" width="32" height="32" border="0"><br>Rechercher</a></td>
		</tr>
	</table>
</div>

<?php
# Pied de Page
include NIVO."includes/pied.php" ;
?>