<?php
define('NIVO', '../');

require_once(NIVO."nro/fm.php");

$q = strtolower($_GET["q"]);
if (empty($q)) return;

if (ctype_digit($q)) {
	$quid = 'idsupplier = "'.$q.'"';
} else {
	if (strstr($q, " ")) {
		$qs = explode(" ", $q);
		foreach ($qs as $leq) {
			$leq = trim($leq);
			if (!empty($leq)) {
				if (!empty($quid)) $quid .= " AND ";
				$quid .= '(societe LIKE CONVERT(_utf8 "%'.$leq.'%" USING utf8) COLLATE utf8_general_ci)';
			}
		}
	} else {
		$quid = '(societe LIKE CONVERT(_utf8 "%'.$q.'%" USING utf8) COLLATE utf8_general_ci)';
	}
}

$items = $DB->getArray('SELECT idsupplier, societe FROM supplier WHERE '.$quid.' ORDER BY societe LIMIT 100');

foreach ($items as $value) {
	echo $value['idsupplier']."|".$value['societe']."\n";
}
?>