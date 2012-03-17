<?php
define('NIVO', '../');
$css = standard;
$classe = "etiq2";
$classe1 = "etiq";
$classe2 = "standard";

# Classes utilisées
include NIVO.'classes/vip.php';

# Entete de page
include NIVO."includes/ifentete.php" ;

$idvip = $_GET['idvip'];
$idvipjob = $_GET['idvipjob'];
$idpeople = $_GET['idpeople'];
$_POST['dateticket'] = date("Y-m-d");

# Carousel des fonctions
switch ($_GET['act']) {
############## SUPPLIER & UNASSIGN #########################################
	############## SUPPLIER #########################################
		case "supplier": 
			$idticket = $_GET['idticket'];
			$_POST['idmatos'] = $_GET['idmatos'];
			$_POST['idstockf'] = $_GET['idstockf'];
			$_POST['idstockm'] = $_GET['idstockm'];
			$_POST['stockout'] = date("Y-m-d");
			$_POST['stockin'] = date("Y-m-d", strtotime("+3 days"));
			$_POST['suser'] = 'supplier';
			$_POST['inuse'] = 1;
			if (!empty($idticket)) {
				$ajout = new db('stockticket', 'idticket');
				$ajout->AJOUTE(array('idstockf', 'idstockm', 'idmatos', 'stockout', 'stockin', 'dateticket', 'suser' ));
				$message = $_GET['codematos'].' to supplier';
			}
	############## UNASSIGN #########################################
		case "unasign": 
			$idvip = $_GET['idvip'];
			$idticket = $_GET['idticket'];
			$_POST['stockin'] = date("Y-m-d";
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
?>
<div id="minicentervipwhite">
<fieldset class="blue">
	<legend class="blue">
		<b>MATERIEL VIPs <?php echo $message; ?></b>
	</legend>
<?php 
		$recherche='
			SELECT 
			st.idticket, st.idvipjob, st.dateticket, st.stockout, st.stockin, st.suser, st.inuse, st.note, st.idstockf, st.nombre, st.sex,
			sf.reference, 
			sm.reference AS refmodele, 
			ma.idmatos, ma.idvip, ma.codematos, ma.mnom, ma.dateout AS madateout, ma.autre, ma.idpeople 
			FROM stockticket st
			LEFT JOIN stockf sf ON st.idstockf = sf.idstockf
			LEFT JOIN stockm sm ON st.idstockm = sm.idstockm
			LEFT JOIN matos ma ON st.idmatos = ma.idmatos
			WHERE st.idvipjob = '.$idvipjob.' AND st.idpeople = '.$idpeople.' AND st.jobstatut = "mission"
			ORDER BY st.sex, st.stockout, st.stockin, st.idstockf
		';
		$listing = new db();
		$listing->inline("$recherche;");

	#$listing = new db();
	#$listing->inline("SELECT * FROM `matos` WHERE `idvip` = $idvip");
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
			<td class="<?php echo $classe2; ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=unasign&idvip=<?php echo $idvip;?>&idvipjob=<?php echo $idvipjob;?>&idticket=<?php echo $row['idticket'];?>&idstockf=<?php echo $row['idstockf'];?>&idstockm=<?php echo $row['idstockm'];?>&idmatos=<?php echo $row['idmatos'];?>&idpeople=<?php echo $idpeople;?>"><img src="<?php echo STATIK ?>illus/stock-retour.gif" alt="UnAssign" width="16" border="0"></a></td>
			<td class="<?php echo $classe2; ?>"><?php echo $row['sex'] ;?></td>
			<td class="<?php echo $classe2; ?>"><?php echo $row['reference'] ;?></td>
			<td class="<?php echo $classe2; ?>"><?php echo $row['refmodele']; ?></td>
			<td class="<?php echo $classe2; ?>"><?php echo $row['codematos'] ;?></td>
			<td class="<?php echo $classe2; ?>"><?php echo fdate($row['stockout']); ?></td>
			<td class="<?php echo $classe2; ?>"><?php echo fdate($row['stockin']); ?></td>
			<td class="<?php echo $classe2; ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=supplier&idvip=<?php echo $idvip;?>&idvipjob=<?php echo $idvipjob;?>&idticket=<?php echo $row['idticket'];?>&idstockf=<?php echo $row['idstockf'];?>&idstockm=<?php echo $row['idstockm'];?>&idmatos=<?php echo $row['idmatos'];?>&idpeople=<?php echo $idpeople;?>"><img src="<?php echo STATIK ?>illus/stock-supplier.gif" alt="Supplier" width="33" height="33" border="0"></a></td>
		</tr>
<?php } 
?>
	</table>
</fieldset>

<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
</div>
