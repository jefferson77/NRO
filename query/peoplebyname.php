<?php
define('NIVO', '../');

require_once(NIVO."nro/fm.php");

$q = strtolower($_GET["q"]);
if (empty($q)) return;

$clauses = (!isset($_GET['mode']) || $_GET['mode'] != 'full') ? ' AND codepeople > 0' : '';

if (ctype_digit($q)) {
	$quid = 'codepeople = "'.$q.'"';
} else {
	if (strstr($q, " ")) {
		$qs = explode(" ", $q);
		foreach ($qs as $leq) {
			$leq = trim($leq);
			if (!empty($leq)) {
				if (!empty($quid)) $quid .= " AND ";
				$quid .= '((pnom LIKE CONVERT(_utf8 "%'.$leq.'%" USING utf8) COLLATE utf8_general_ci) OR (pprenom LIKE CONVERT(_utf8 "%'.$leq.'%" USING utf8) COLLATE utf8_general_ci))';
			}
		}
	} else {
		$quid = '((pnom LIKE CONVERT(_utf8 "%'.$q.'%" USING utf8) COLLATE utf8_general_ci) OR (pprenom LIKE CONVERT(_utf8 "%'.$q.'%" USING utf8) COLLATE utf8_general_ci))';
    }
}

$items = $DB->getArray('SELECT idpeople, CONCAT(pnom, " ", pprenom) AS name , codepeople FROM people WHERE '.$quid.' '.$clauses.' ORDER BY pnom, pprenom LIMIT 100');

foreach ($items as $value) {
	echo $value['idpeople']."|".$value['name']."|".$value['codepeople']."\n";
}


?>