<?php
define('NIVO', '../../../');

# Classes utilisées
include NIVO."classes/anim.php";
include NIVO."classes/facture.php";
include NIVO.'print/anim/facture/facture_inc.php';
include NIVO.'print/dispatch/dispatch_functions.php';

# Entete de Page
include NIVO."includes/ifentete.php" ;

### Recherche des Jobs ANIM en facturation (etat = 6)
$sql = "SELECT
YEAR(an.datem) as annee, an.weekm, an.datem, an.idanimation,
j.idclient, j.boncommande, j.idcofficer,
c.facturation, c.factureofficer, c.societe,
a.prenom,
co.langue

FROM animation an
LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
LEFT JOIN agent a ON j.idagent = a.idagent
LEFT JOIN client c ON j.idclient = c.idclient
LEFT JOIN cofficer co ON j.idcofficer = co.idcofficer

WHERE an.facturation = 6";

## Separation des missions en factures
$listing = $DB->getArray($sql);
foreach($listing as $row) {
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
		$tabl[$lakey]['ids'][] = $row['idanimation'];
		$tabl[$lakey]['idclient'] = $row['idclient'];
		$tabl[$lakey]['langue'] = $row['langue'];
		$tabl[$lakey]['idcofficer'] = $row['idcofficer'];
}

foreach ($tabl as $key => $value) {
	$DB->inline("INSERT INTO `facture` (`secteur`, `etat`, `datefac`, `idclient`, `langue`, `idcofficer`, `modefac`)
			VALUES ('2', '1', '".facdate()."', '".$value['idclient']."', '".$value['langue']."', '".$value['idcofficer']."', 'A')");
	$newfac = $DB->addid;
	
	$DB->inline("UPDATE animation SET facturation = '7', facnum = '".$newfac."' WHERE idanimation IN(".implode(", ", $value['ids']).");");
}
################### Fin Création de la DB factureanim ########################

######## Variables de taille  ###############

######## Variables de taille  ###############

################### Début Recherche de toutes les fiches factureanim ########################
$facts = $DB->getArray("SELECT idfac FROM `facture` WHERE secteur = '2' AND etat = '1' AND modefac = 'A';");

foreach ($facts as $infoa) { 
	
	$infosDocs = $DB->getRow("SELECT o.idcofficer
					FROM facture f
					LEFT JOIN cofficer o ON f.idcofficer = o.idcofficer
					LEFT JOIN client c ON o.idclient = c.idclient 
					WHERE f.idfac = ".$infoa['idfac']);
						
	$fac = new facture($infoa['idfac']);
    #### switch statut pour archivage ########################################
        $DB->inline("UPDATE animation SET facturation = 8 WHERE facnum = ".$infoa['idfac']);
        $DB->inline("UPDATE animjob SET facturation = 8 WHERE idanimjob IN (SELECT DISTINCT(idanimjob) from animation WHERE facnum = ".$infoa['idfac'].")");
	
	
	$docparofficer[$infosDocs['idcofficer']][] = print_fac_anim($infoa['idfac'], 'entete','',"FACTURE");
	
}

generateSendTable($docparofficer, 'cofficer', 'temp/facture', 'facture', "Facture");



#####################
########################################################

### Marque les factures comme imprimées
$DB->inline("UPDATE`facture` SET etat = 2 WHERE secteur = '2' AND etat = '1' AND modefac = 'A';");

?>
<div align="center">
<br><br><br><a href="javascript:window.close();"><b>&gt; Fermer &lt;</b></a>
</div>
<?php include NIVO."includes/ifpied.php" ?>