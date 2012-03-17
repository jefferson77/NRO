<?php
define('NIVO', '../../../');

include NIVO."includes/ifentete.php" ;
# Classes utilisées
include NIVO."classes/merch.php";
include NIVO."classes/facture.php";
include 'facture_inc.php';

$sql = "SELECT
YEAR(me.datem) as annee, me.weekm, me.idclient, me.boncommande,  me.idmerch, me.genre, me.idshop,
me.datem,c.facturation, c.factureofficer,
me.idcofficer,
co.langue

FROM merch me
LEFT JOIN client c ON me.idclient = c.idclient
LEFT JOIN cofficer co ON me.idcofficer = co.idcofficer

WHERE me.facturation = 6 AND me.facnum IS NULL ";

$factures = $DB->getArray($sql);

## Separation Facs

foreach ($factures as $row) {
	if ($row['genre'] == 'EAS') {
		$lakey = 'EAS//'.$row['annee'].'//'.date("m", strtotime($row['datem'])).'//'.$row['idclient'].'//'.$row['idshop'];
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
	}
		$tabl[$lakey]['ids'][] = $row['idmerch'];
		$tabl[$lakey]['idclient'] = $row['idclient'];
		$tabl[$lakey]['langue'] = $row['langue'];
}

foreach ($tabl as $key => $value) {
## Ajout nouvelle prefac
	$DB->inline("INSERT INTO `facture` (`secteur`, `etat`, `datefac`, `idclient`, `langue`) VALUES ('3', '1', '".facdate()."', '".$value['idclient']."', '".$value['langue']."')");
	$newfac = $DB->addid;

	$DB->inline("UPDATE `merch` SET `facnum` = '".$newfac."', `facturation` = 7 WHERE `idmerch` IN (".implode(", ", $value['ids']).")");
}

	
	
	
	################### Début Recherche de toutes les fiches facturemerch ########################
	$anim1 = $DB->getArray("SELECT idfac, YEAR(datefac) as year FROM `facture` WHERE secteur = '3' AND etat = '1' AND modefac = 'A';");

	foreach ($anim1 as $infoa) { 

		$idofficer = $DB->getOne("SELECT o.idcofficer
						FROM facture f
						LEFT JOIN client c ON f.idclient = c.idclient 
						LEFT JOIN cofficer o ON c.idclient = o.idclient
						WHERE f.idfac = ".$infoa['idfac']);

		$docparofficer[$idofficer][] = print_fac_merch($infoa['idfac'],'entete','','FAC');

	}
	
	generateSendTable($docparofficer, 'cofficer', 'temp/facture', 'facture', "Facture");

#####################
########################################################

$DB->inline ("UPDATE `facture` SET `etat` = 2 WHERE `etat` = 1 AND `secteur` = 3;");
?>
<div align="center">
<br><br><br><a href="javascript:window.close();"><b>&gt; Fermer &lt;</b></a>
</div>

<?php include NIVO."includes/ifpied.php" ?>