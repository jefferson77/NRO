<?php
# Entete de page
define('NIVO', '../../'); 
$Titre = 'Horizon Ech&eacute;ancier';
$PhraseBas = 'Importation de l\'echeancier Horizon';
$Style = 'admin';
#### Classes utilisées ####

$sharedrep = "/shared/shared/HORIZON/data/033/";
$echancierfile = "IMMA.TXT";

# Entete de page
include NIVO."includes/entete.php" ;
?>
<style type="text/css">
 .cadre { width: 200px; background-color: #8c9dad; margin: 40px auto auto auto; }
 .cadre tr td, th {text-align: center; padding: 5px;}
 .cadre tr td { background-color: #FFF; font-size: 14px;}
 .cadre tr th { background-color: #d8dcdf; font-size: 14px;}
 .cadre tr td a { display: block; 	text-decoration: none;}
 .cadre tr td a:hover { display: block; background-color: #697789; }
</style>
<div id="infomain">
<h1>Mode d'emploi</h1>
<h2>1. Exporter les données HORIZON</h2>
<p>Dans HORIZON, choisir '<span>Param&egrave;tres &gt; Maintenance &gt; Exportation &gt; Soldes et Impay&eacute;s</span>'</p>
<img src="<?php echo STATIK ?>illus/exporthorizon.png" alt="exporthorizon.png" width="290" height="232">
<p>et sauver les fichier dans '<span>S:/horizon/data</span>'</p>
<h2>2. Importer les donn&eacute;es dans NEURO</h2>
<p>De retour dans NEURO, cliquer sur le bouton '<span>Importer le fichier</span>' ici &agrave; gauche pour importer les donn&eacute;es

</div>
<div id="lmain">
<?php
switch ($_GET['act']) {
	### Importation du fichier ###################################################################################################################
	case"importIMMA":
		## Clear DB
		$echeancier = new db();
		$echeancier->inline("TRUNCATE TABLE `echeancier`");

		## Insert IMMA	
		$lines = file($sharedrep.$echancierfile);
		
		$sql = "INSERT INTO `echeancier` (`idhorizon` , `journal` , `annee` , `idfac` , `dateecheance` , `debitcredit` , `montantdu` , `totalfac` , `dejapaye` ) VALUES ";

		foreach ($lines as $line) {
			$d = explode(";", $line);
			
			if ($d[0] == 'C') {
				$d[5] = substr(trim($d[5]), 0, 4)."-".substr(trim($d[5]), 4, 2)."-".substr(trim($d[5]), 6, 2);
				$sql .= "('".trim($d[1])."', '".trim($d[2])."', '".trim($d[3])."', '".trim($d[4])."', '".trim($d[5])."', '".trim($d[6])."', '".trim($d[7])."', '".trim($d[11])."', '".trim($d[14])."'),";
			}			
		}

		$sql = substr($sql, 0, -1).";";
		
		$echeancier->inline($sql);

		## date store
		$echeancier->inline("UPDATE config SET vvaleur = '".date("Y-m-d")."' WHERE vnom = 'importhorizon'");
	break;
	### Export Graydon ###########################################################################################################################
	case"graydon":
		$storepath = Conf::read('Env.root')."media/graydon/files/";
		$storefile = "GRAYDON-".date("Ymd").".TXT";

		## Datas
		$getdata = new db();
		$getdata->inline("SELECT e.*, c.codetva, c.tva FROM echeancier e LEFT JOIN client c ON e.idhorizon = c.codecompta WHERE e.journal = 'VEN' AND e.montantdu > 0 AND c.codetva = 'BE' AND c.tva != ''");

		if (mysql_num_rows($getdata->result) > 0) {
			while ($row = mysql_fetch_array($getdata->result)) {
				$key = $row['codetva'].preg_replace('@[^0-9]@','',$row['tva']); # ONDNUM num de tva avec BE
	
				$nbjours = round(((strtotime(date("Y-m-d")) - strtotime($row['dateecheance'])) / 86400) - 1);
				$tab[$key]['UITSTD'] += $row['montantdu']; 

				if     (strtotime(date("Y-m-d")) < strtotime($row['dateecheance'])) { $tab[$key]['UITBBT'] += $row['montantdu']; } # Non echu
				elseif ($nbjours <= 30) { $tab[$key]['UITST1'] += $row['montantdu']; } # Echeance 1  - 30 jours
				elseif ($nbjours <= 60) { $tab[$key]['UITST2'] += $row['montantdu']; } # Echeance 30 - 60 jours
				elseif ($nbjours <= 90) { $tab[$key]['UITST3'] += $row['montantdu']; } # Echeance 60 - 90 jours
				else 					{ $tab[$key]['UITST4'] += $row['montantdu']; } # Echeance    + 90 jours
				$tab[$key]['ERVFCT'] += 1; # Nombre de factures			
			}
		}

		## File
		$file = fopen($storepath.$storefile, 'w');
		fwrite($file, "\"ONDNUM\",\"ONDLEV\",\"UITSTD\",\"UITBBT\",\"UITSTO\",\"UITST1\",\"UITST2\",\"UITST3\",\"UITST4\",\"ERVMND\",\"ERVJAR\",\"LEVDEB\",\"ERVMNT\",\"ERVFCT\"\r\n");

		foreach($tab as $tva => $dat) {
			fwrite($file, "\"".$tva."\",\"\",\"".$dat['UITSTD']."\",\"".$dat['UITBBT']."\",\"\",\"".$dat['UITST1']."\",\"".$dat['UITST2']."\",\"".$dat['UITST3']."\",\"".$dat['UITST4']."\",\"".date("m")."\",\"".date("Y")."\",\"\",\"EUR\",\"".$dat['ERVFCT']."\"\r\n");
		}
		
		fclose ($file);

	break;
	### Get du fichier et confirmation de l'importation ##########################################################################################
	case"get":
	default:
		## Last import
		$imprt = new db();
?>
<table class="cadre">
	<tr>
		<th>Fichier actuel</th>
	</tr>
	<tr>
		<td><?php echo fdate($imprt->CONFIG('importhorizon')); ?></td>
	</tr>
	<tr>
		<td><?php echo round(((strtotime(date("Y-m-d")) - strtotime($imprt->CONFIG('importhorizon'))) / 86400)).' Jours'; ?></td>
	</tr>
</table>
<table class="cadre">
	<tr>
		<th>Nouveau Fichier</th>
	</tr>
<?php 	
		## test du répertoire monté
		if (is_dir($sharedrep)) {
			if (file_exists($sharedrep.$echancierfile)) {
				echo "<tr><td>".date("d/m/Y", filemtime($sharedrep.$echancierfile))."</td></tr>";
				echo '</table><table class="cadre">';
				echo '<tr><td><a href="?act=importIMMA">Importer le fichier</a></td></tr>';
			} else {
				echo '<tr><td>Aucun fichier IMMA</td></tr>';
			}
		} else {
			echo '<tr><td>SHARED non mont&eacute;</td></tr>';
		}
?>
</table>
<table class="cadre">
</table>



<?php
}
?>
<div id="topboutons">
<table border="0" cellspacing="1" cellpadding="0">
	<tr>
		<td class="on"><a href="?act=get"><img src="<?php echo STATIK ?>illus/stock.gif" alt="stock.gif" width="32" height="32" border="0"><br>Import</a></td>
		<td class="on"><a href="?act=graydon"><img src="<?php echo STATIK ?>illus/correct.gif" alt="corriger.gif" width="32" height="32" border="0"><br>Graydon</a></td>
	</tr>
</table> 
</div>
</div><?php
include NIVO."includes/pied.php" ;
?>