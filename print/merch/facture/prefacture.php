<?php
define('NIVO', '../../../');

# Classes utilisées
include NIVO.'classes/facture.php';
include NIVO."classes/merch.php";
include NIVO."print/merch/facture/facture_inc.php";
# Entete de page

include NIVO."includes/ifentete.php" ;

## Clients EAS
include NIVO."merch/easclients.php";

## Vidage de la base Temp Fac Merch
$DB->inline("TRUNCATE TABLE `tempfacturemerch`;");
$DB->inline("UPDATE `merch` SET `facnumtemp` = 0 WHERE `facnumtemp` > 0"); ## vide les anciens nums de factemp

## Get missions a facturer
$prefacs = $DB->getArray("SELECT
		YEAR(me.datem) as annee, me.weekm, me.idclient, me.boncommande,  me.idmerch, me.genre, me.idshop,
		me.datem,c.facturation, c.factureofficer,
		me.idcofficer,
		co.langue
	FROM merch me
		LEFT JOIN client c ON me.idclient = c.idclient
		LEFT JOIN cofficer co ON me.idcofficer = co.idcofficer
	WHERE me.facturation = 5");

## Separation Facs
foreach ($prefacs as $row) {
	if ($row['genre'] == 'EAS') {
		if (in_array($row['idclient'], $gbclients)) {
			$lakey = 'EASGB//'.$row['annee'].'//'.date("m", strtotime($row['datem']));
			$tabl[$lakey]['idclient'] = '2651';
			$tabl[$lakey]['intitule'] = 'EASGB '.date("m Y", strtotime($row['datem']));
		} else {
			$lakey = 'EAS//'.$row['annee'].'//'.date("m", strtotime($row['datem'])).'//'.$row['idclient'].'//'.$row['idshop'];
			$tabl[$lakey]['idclient'] = $row['idclient'];
		}
	} else {
		switch ($row['facturation'].$row['factureofficer']) {
			case"12":
				$lakey = 'SB//'.$row['annee'].'//'.$row['weekm'].'//'.$row['idclient'].'//'.$row['boncommande'];
			break;
			case"11":
				$lakey = 'SO//'.$row['annee'].'//'.$row['weekm'].'//'.$row['idclient'].'//'.$row['idcofficer'];
			break;
			case"32":
				$lakey = 'MB//'.$row['annee'].'//'.date("m", strtotime($row['datem'])).'//'.$row['idclient'].'//'.$row['boncommande'];
			break;
			case"31":
				$lakey = 'MO//'.$row['annee'].'//'.date("m", strtotime($row['datem'])).'//'.$row['idclient'].'//'.$row['idcofficer'];
			break;
			default:
				$lakey = 'AU//'.$row['annee'].'//'.$row['weekm'].'//'.$row['idclient'].'//'.$row['idcofficer'];
		}
		$tabl[$lakey]['idclient'] = $row['idclient'];
	}
	$tabl[$lakey]['ids'][] .= $row['idmerch'];
	$tabl[$lakey]['langue'] = $row['langue'];
}

foreach ($tabl as $key => $value) {
## Ajout nouvelle prefac
	$DB->inline("INSERT INTO `tempfacturemerch` (`secteur`, `datefac`, `idclient`, `langue`, `intitule`) VALUES ('3', DATE(NOW()), '".$value['idclient']."', '".$value['langue']."', '".$value['intitule']."')");
	$did = $DB->addid;
	$DB->inline("UPDATE `merch` SET `facnumtemp` = '".$did."' WHERE `idmerch` IN (".implode(", ", $value['ids']).")");
}

?>
<div align="center">
<?php

# ==========================================================================================================
# = Normal Prefactures =================================================================================== =
# ==========================================================================================================
$infoas = $DB->getArray("SELECT idfac FROM `tempfacturemerch` WHERE intitule NOT LIKE 'EASGB%'");

if (count($infoas) >= 1) {
	foreach ($infoas as $infoa)
	{
		$pdfs[] = print_fac_merch($infoa['idfac'],'yes','','PREME');
	}

	$book = reliure($pdfs,"MFPr");
	
	$filepath = pathinfo($book['path']);
	$filename = $filepath['filename'];

	?>
	<img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0">
	<a href="<?php echo NIVO.'../document/temp/MFPr/'.$filename.'.pdf'; ?>" target="_blank">Imprimer Prefactures</a>
	<br>
	<?php
}
?>

<br><br><br><a href="javascript:window.close();"><b>&gt; Fermer &lt;</b></a>
</div>
<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
