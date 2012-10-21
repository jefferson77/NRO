<?php
define("NIVO", "../");

require_once(NIVO."nro/fm.php");
include NIVO."classes/anim.php";
include NIVO."classes/vip.php";
include NIVO."classes/merch.php";
include NIVO."classes/facture.php";

## Facture a problèmes
$notin = array();

## Recherche de toutes les factures 2006 pour des clients étrangers
$factures = $DB->getArray("SELECT
f.idfac, c.idclient, c.societe, f.datefac
FROM facture f
	LEFT JOIN client c ON c.idclient = f.idclient
WHERE c.codetva NOT LIKE 'BE'
	AND c.codetva NOT LIKE ''
	AND f.datefac BETWEEN '2011-01-01' AND '2011-12-31'");

foreach ($factures as $row) {
	if (!in_array($row['idfac'], $notin))
	{
		$fac = new facture($row['idfac'], "FAC");
		echo "'".$row['idfac']."', '".$row['idclient']."', '".$row['societe']."', '".$row['datefac']."', '".$fac->CompteHoriz['700220']."', '".$fac->CompteHoriz['700200']."', '".$fac->CompteHoriz['700210']."'<br>";
	}
}

?>
