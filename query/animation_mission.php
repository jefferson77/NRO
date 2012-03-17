<?php
define('NIVO', '../');

require_once(NIVO."nro/fm.php");

$q = $_GET["q"];

if (!empty($q) and ctype_digit($q)) {
	$items = $DB->getArray('SELECT idanimation, produit FROM animation WHERE idanimation = "'.$q.'" ORDER BY idanimation LIMIT 15');

	foreach ($items as $value) {
		echo $value['idanimation']."|".$value['produit']."\n";
	}
} else {
	return false;
}

?>