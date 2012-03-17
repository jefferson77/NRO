<?php
define('NIVO', '../');

$classe = "etiq2";
$classe1 = "etiq";
$classe2 = "standard";

# Entete de page
include NIVO."includes/ifentete.php" ;

$_POST['dateticket'] = date ("Y-m-d");

## Vars init
$message = '';

# Carousel des fonctions
switch ($_GET['act']) {
############## ASSIGN #########################################
	case "asign": 
		$_POST['stockout'] = fdatebk($_POST['stockout']);
		$_POST['stockin'] = fdatebk($_POST['stockin']);
		$_POST['sview'] = 1;
		$_POST['nombre'] = 1;
		$_POST['jobstatut'] = 'mission';
		$_POST['suser'] = 'people';
		if (!empty($_POST['codematos'])) {
			$codematos = $_POST['codematos'];
			$listing = new db('matos', 'idmatos');
			$listing->inline("SELECT * FROM `matos` WHERE `codematos` LIKE '$codematos'");
			$infos = mysql_fetch_array($listing->result) ; 
			$idmatos = $infos['idmatos'];
			$_POST['idmatos'] = $infos['idmatos'];
			$_POST['idstockf'] = $infos['idstockf'];
			$_POST['idstockm'] = $infos['idstockm'];
			$_POST['inuse'] = 1;
			if (!empty($idmatos)) {
				### VERIF SI DISPONIBLE ###
					$ajout = new db('stockticket', 'idticket');
					$ajout->AJOUTE(array('idstockf', 'idstockm', 'idmatos', 'stockout', 'stockin', 'nombre', 'idvipjob', 'idpeople', 'dateticket', 'sview', 'jobstatut', 'suser', 'inuse' ));
				#/## VERIF SI DISPONIBLE ###
			} else {
				$message = 'code materiel inconnu';
			}
		} else {
			$message = 'pas de code materiel';
		}
	break;
############## SUPPLIER & UNASSIGN #########################################
	############## SUPPLIER #########################################
		case "supplier": 
			$idticket = $_GET['idticket'];
			$_POST['idmatos'] = $_GET['idmatos'];
			$_POST['idstockf'] = $_GET['idstockf'];
			$_POST['idstockm'] = $_GET['idstockm'];
			$_POST['stockout'] = date ("Y-m-d");
			$_POST['stockin'] = date("Y-m-d", strtotime("+3 days"));
			$_POST['suser'] = 'supplier';
			$_POST['inuse'] = 1;
			if (!empty($idticket)) {
				$ajout = new db('stockticket', 'idticket');
				$ajout->AJOUTE(array('idstockf', 'idstockm', 'idmatos', 'stockout', 'stockin', 'dateticket', 'suser', 'inuse' ));
				$message = $_GET['codematos'].' to supplier';
			}
	############## UNASSIGN #########################################
		case "unasign": 
			$idticket = $_GET['idticket'];
			$_POST['stockin'] = date ("Y-m-d");
			$_POST['inuse'] = 0;
			if (!empty($idticket)) {
				$modif = new db('stockticket', 'idticket');
				$modif->MODIFIE($idticket, array('idvip' , 'stockin', 'idpeople', 'inuse'));
				$message = $_GET['codematos'].' unasigned';
			}
	break;
############## Recherche d'une mission #########################################
	default: 
}
### recherche date job + people
	$row1 = $DB->getRow('
		SELECT 
		m.idpeople, m.matospeople, 
		p.sexe,
		j.dateout, j.datein
		FROM vipmission m
		LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob
		LEFT JOIN people p ON m.idpeople = p.idpeople
		WHERE m.idvip = '.$_REQUEST['idvip'].'
		ORDER BY m.idvip
	');
	
	$idpeople = $row1['idpeople'];
	$sexe = $row1['sexe'];
	$datein = fdate($row1['dateout']);
	$dateout = fdate($row1['datein']);
#/## recherche date job
?>
<div id="minicentervipwhite">
<fieldset class="blue">
	<legend class="blue">
		<b>MATERIEL VIPs <?php echo $message; ?></b>
	</legend>
<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=asign&idvipjob=<?php echo $_GET['idvipjob'];?>" method="post">
	<input type="hidden" name="idvip" value="<?php echo $_REQUEST['idvip'];?>"> 
	<input type="hidden" name="idvipjob" value="<?php echo $_GET['idvipjob'];?>"> 
	<input type="hidden" name="idpeople" value="<?php echo $idpeople;?>"> 
	<table class="<?php echo $classe1; ?>" border="0" cellspacing="1" cellpadding="2" align="center" width="100%">
		<tr>
			<td align="center">Code Materiel</td>
			<td align="center"><input type="text" size="5" name="codematos" value=""></td>
			<td align="center">du : <input type="text" size="8" name="stockout" value="<?php echo $dateout; ?>"></td>
			<td align="center">au : <input type="text" size="8" name="stockin" value="<?php echo $datein; ?>"></td>
			<td align="center"><input type="submit" name="Ajouter" value="Assigner"></td>
		</tr>
	</table>
</form>
<?php 
		$sql = '
			SELECT 
			st.idticket, st.idvipjob, st.dateticket, st.stockout, st.stockin, st.suser, st.inuse, st.note, st.idstockf, st.nombre, st.sex,
			sf.reference, 
			sm.reference AS refmodele, 
			ma.idmatos, ma.idvip, ma.codematos, ma.mnom, ma.dateout AS madateout, ma.autre, ma.idpeople 
			FROM stockticket st
			LEFT JOIN stockf sf ON st.idstockf = sf.idstockf
			LEFT JOIN stockm sm ON st.idstockm = sm.idstockm
			LEFT JOIN matos ma ON st.idmatos = ma.idmatos
			WHERE st.idvipjob = '.$_GET['idvipjob'].' AND st.idpeople = '.$idpeople.' AND st.jobstatut = "mission"
			ORDER BY st.sex, st.stockout, st.stockin, st.idstockf
		';
		$listing = new db();
		$listing->inline($sql);

?>
	<table class="<?php echo $classe1; ?>" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<td class="<?php echo $classe; ?>">Ret.</td>
			<td class="<?php echo $classe; ?>">Sex</td>
			<td class="<?php echo $classe; ?>">Famille</td>
			<td class="<?php echo $classe; ?>">Mod.</td>
			<td class="<?php echo $classe; ?>">Code</td>
			<td class="<?php echo $classe; ?>">Date out</td>
			<td class="<?php echo $classe; ?>">Date in</td>
			<td class="<?php echo $classe; ?>">Fourn.</td>
		</tr>
<?php
while ($row = mysql_fetch_array($listing->result)) { 
	# $i++;
	$idstockfjob .= '-sep-'.$row['idstockf'];
#	if (strchr($idstockfjob, $row['idstockf'])) { echo 'checked';}
?>
		<tr>
			<td class="<?php echo $classe2; ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=unasign&idvip=<?php echo $_REQUEST['idvip'];?>&idvipjob=<?php echo $_GET['idvipjob'];?>&idticket=<?php echo $row['idticket'];?>&idstockf=<?php echo $row['idstockf'];?>&idstockm=<?php echo $row['idstockm'];?>&idmatos=<?php echo $row['idmatos'];?>"><img src="<?php echo STATIK ?>illus/stock-retour.gif" alt="UnAssign" width="16" border="0"></a></td>
			<td class="<?php echo $classe2; ?>"><?php echo $row['sex'] ;?></td>
			<td class="<?php echo $classe2; ?>"><?php echo $row['reference'] ;?></td>
			<td class="<?php echo $classe2; ?>"><?php echo $row['refmodele']; ?></td>
			<td class="<?php echo $classe2; ?>"><?php echo $row['codematos'] ;?></td>
			<td class="<?php echo $classe2; ?>"><?php echo fdate($row['stockout']); ?></td>
			<td class="<?php echo $classe2; ?>"><?php echo fdate($row['stockin']); ?></td>
			<td class="<?php echo $classe2; ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=supplier&idvip=<?php echo $_REQUEST['idvip'];?>"> 
	</tr>
<?php } 
	### recherche des famille pour le job
		$sql = '
			SELECT 
			st.idticket, st.idvipjob, st.dateticket, st.stockout, st.stockin, st.suser, st.inuse, st.note, st.idstockf, st.nombre, st.sex,
			sf.reference 
			FROM stockticket st
			LEFT JOIN stockf sf ON st.idstockf = sf.idstockf
			WHERE st.idvipjob = '.$_GET['idvipjob'].' AND st.jobstatut = "job" AND (st.sex = "x" OR st.sex = "'.$sexe.'")
			ORDER BY st.sex, st.stockout, st.stockin, st.idstockf
		';
		$ticketjob = new db();
		$ticketjob->inline($sql);
		
		$matostotal = 0;
		$matosmissing = 0;

		while ($rowjob = mysql_fetch_array($ticketjob->result)) { 
		$matostotal++;
			if (!strchr($idstockfjob, $rowjob['idstockf'])) { 
		?>
				<tr>
					<td class="<?php echo $classe2; ?>"></td>
					<td class="<?php echo $classe2; ?>"><?php echo $rowjob['sex'] ;?></td>
					<td class="<?php echo $classe2; ?>"><?php echo '<font color="red"><b>'.$rowjob['reference'].'</b></font>' ;?></td>
				</tr>
		<?php 
				$matosmissing++;
			}
		}
	#/## recherche des famille pour le job
	### Mise à jour du materiel reu pour ce people
		if ($matostotal == 0) {$matospeople = 1; } # pas de materiel necessaire ==> people = OK
		else {
			if ($matosmissing == 0) {$matospeople = 1; } # pas de materiel necessaire MANQUANT ==> people = OK
			else {
				if ($matosmissing == $matostotal) {$matospeople = 0; } # TOUT  materiel necessaire MANQUANT ==> people = PAS OK
				if ($matosmissing < $matostotal) {$matospeople = 2; } # CERTAIN  materiel necessaire MANQUANT ==> people = a moitié pas OK
			}
		}
	#	if ($row1['matospeople'] != $matospeople) { # mise à jour de la mission
			$_POST['matospeople'] = $matospeople;
		#	$modif = new db('vipmission', 'idvip');
		#	$modif->MODIFIE($_REQUEST['idvip'], array('matospeople'));

			$modif = new db();
			$modif->inline("UPDATE `vipmission` SET `matospeople` = '$matospeople' WHERE `idvipjob` = ".$_GET['idvipjob']." AND `idpeople` = $idpeople");
	#	}
	#/## Mise à jour du materiel reu pour ce people
?>
	</table>
<?php
	if ($matospeople == 0) { echo '<img src="'.STATIK.'illus/taille-t-shirt-rouge.gif" alt="Supplier" width="32" height="28" border="0">'; }
	if ($matospeople == 1) { echo '<img src="'.STATIK.'illus/taille-t-shirt-vert.gif" alt="Supplier" width="32" height="28" border="0">'; }
	if ($matospeople == 2) { echo '<img src="'.STATIK.'illus/taille-t-shirt-orange.gif" alt="Supplier" width="32" height="28" border="0">'; }
?>
	</fieldset>
</div>
<?php include(NIVO."includes/ifpied.php"); ?>