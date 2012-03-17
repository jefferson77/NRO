<?php
define('NIVO', '../');

require_once(NIVO."nro/fm.php");

$q = strtolower($_GET["q"]);
if (empty($q)) return;

if (ctype_digit($q)) {
	$quid = 'idagent = "'.$q.'"';
} else {
	if (strstr($q, " ")) {
		$qs = explode(" ", $q);
		foreach ($qs as $leq) {
			$leq = trim($leq);
			if (!empty($leq)) {
				if (!empty($quid)) $quid .= " AND ";
				$quid .= '((nom LIKE CONVERT(_utf8 "%'.$leq.'%" USING utf8) COLLATE utf8_general_ci) OR (prenom LIKE CONVERT(_utf8 "%'.$leq.'%" USING utf8) COLLATE utf8_general_ci))';
			}
		}
	} else {
		$quid = '((nom LIKE "%'.$q.'%") OR (prenom LIKE CONVERT(_utf8 "%'.$q.'%" USING utf8) COLLATE utf8_general_ci))';
	}
}

### extra params
$clauses = '';

if (!empty($_GET['isout'])) $clauses .= " AND isout = '".$_GET['isout']."'";

$items = $DB->getArray('SELECT idagent, CONCAT(prenom, " ", nom) AS name FROM agent WHERE '.$quid.' '.$clauses.' ORDER BY prenom, nom LIMIT 15');

foreach ($items as $value) {
	echo $value['idagent']."|".$value['name']."\n";
}
?>