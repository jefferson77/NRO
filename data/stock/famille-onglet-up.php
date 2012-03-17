<?php
# Entete de page
define('NIVO', '../../'); 
$css = standard;
include NIVO."includes/ifentete.php" ;

$idstockf = $_GET['idstockf'];
if ($_GET['s'] == 1) {$l1 = '#FFFFFF';}
if ($_GET['s'] == 2) {$l2 = '#FFFFFF';}
if ($_GET['s'] == 3) {$l3 = '#FFFFFF';}
if ($_GET['s'] == 4) {$l4 = '#FFFFFF';}
if ($_GET['s'] == 5) {$l5 = '#FF6633';}
if ($_GET['s'] == 6) {$l6 = '#FF6633';}
if ($_GET['s'] == 7) {$l7 = '#FF6633';}
?>

<?php
if (!empty($idstockf)) {
	#/## recherche Nombre de Modèles ###
		$recherchemod='
			SELECT 
			idstockm
			FROM stockm 
			WHERE idstockf = '.$idstockf.'
			ORDER BY idstockf
		';
		$matosmod = new db();
		$matosmod->inline("$recherchemod;");
		$FoundCountModele = mysql_num_rows($matosmod->result);
	#/## recherche Nombre de Modèles ###
	### recherche Nombre de Unités ###
			$rechercheunit='
				SELECT 
				ma.idmatos
				FROM matos ma
				LEFT JOIN stockm stm ON ma.idstockm = stm.idstockm 
				LEFT JOIN stockf stf ON stm.idstockf = stf.idstockf 
				WHERE stm.idstockf = '.$idstockf.'
				ORDER BY stm.idstockf
			';
		$matosunit = new db();
		$matosunit->inline("$rechercheunit;");
		$FoundCountUnit = mysql_num_rows($matosunit->result);
	#/## recherche Nombre de Unités ###
	}

?>
				<table class="standard" border="1" cellspacing="1" cellpadding="0" align="center" width="100%" height="25">
					<tr height="25">
						<td class="etiq2" align="center" width="20%">
						<?php echo '<a href="famille-onglet.php?idstockf='.$idstockf.'&s=1" target="detail-main"><font color="'.$l1.'" size=+1>Mod&egrave;les ( '.$FoundCountModele.' )</font></a>'; ?>
						</th>
						<td class="etiq2" align="center" width="20%">
						<?php echo '<a href="famille-onglet.php?idstockf='.$idstockf.'&s=2" target="detail-main"><font color="'.$l2.'" size=+1>Unit&eacute;s ( '.$FoundCountUnit.' )</font></a>'; ?>
						</th>
						<td class="etiq2" align="center" width="20%">
						<?php echo '<a href="famille-onglet.php?idstockf='.$idstockf.'&s=3" target="detail-main"><font color="'.$l3.'" size=+1>Listing</font></a>'; ?>
						</th>
						<td class="etiq2" align="center" width="20%">
						<?php echo '<a href="famille-onglet.php?idstockf='.$idstockf.'&s=4" target="detail-main"><font color="'.$l4.'" size=+1>Tickets</font></a>'; ?>
						</th>
						<td align="center"><font color='gray'></font>
						</td>
					</tr>
				</table>

<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
</div>