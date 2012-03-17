<?php
# Vars #########
$listing = (!empty($_GET['listing']))?$_GET['listing']:null;
$quid = (!empty($_SESSION['vipjobquid']))?$_SESSION['vipjobquid']:null;
$_POST['Modifier'] = (isset($_POST['Modifier'])) ? $_POST['Modifier'] : '';

# VARIABLE $_SESSION['prenom'] pour recherche direct
if ($listing == 'direct') $quid = " WHERE a.idagent = ".$_SESSION['idagent']." AND j.etat IN (0, 1, 11, 12, 13, 14)";

# VARIABLE SELECT
if(empty($quid) or ($_POST['Modifier'] == 'Rechercher')) {
	$quid = $DB->MAKEsearch(array(
					'a.idagent' 	=> 'idagent',
					'j.idvipjob' 	=> 'idvipjob',
					'j.reference' 	=> 'reference',
					'c.idclient' 	=> 'idclient',
					'c.societe' 	=> 'csociete'
					));

	if (is_array($_POST['etat'])) {
		$quid1 = '';
		foreach ($_POST['etat'] as $val) {
			if (!empty($val)) {
				if (!empty($quid1)) $quid1 .= " OR ";
				switch ($val) {
					case "devis":
						$quid1 .= "j.etat = 0";
					break;
					case "1":
					case "2":
					case "99":
						$quid1 .= "j.etat = ".$val;
					break;
					case "11":
						$quid1 .= "j.etat = 11 OR j.etat = 12";
					break;
					case "13":
						$quid1 .= "j.etat = 13 OR j.etat = 14";
					break;
					case "15":
						$quid1 .= "j.etat >= 15";
					break;
				}
			}
		}
		$quid .= " AND (".$quid1.")";
	}

	if (!empty($quid)) $quid = 'WHERE '.$quid;
}
# Sauvegarde de la recherche
$_SESSION['vipjobquid'] = $quid;
# Recherche des résultats
$listing = $DB->getArray('SELECT
		j.etat, j.idvipjob, j.reference, a.prenom, c.idclient, c.societe AS clsociete, j.datedevis,
		CASE j.etat
			WHEN "0" THEN "1"
			WHEN "1" THEN "0"
			WHEN "2" THEN "2"
			WHEN "11" THEN "3"
			WHEN "12" THEN "3"
			WHEN "13" THEN "4"
			WHEN "14" THEN "4"
			WHEN "99" THEN "99"
			ELSE "5"
		END AS type,
		CASE j.etat
			WHEN "0" THEN (SELECT COUNT(*) FROM vipdevis WHERE vipdevis.idvipjob=j.idvipjob)
			ELSE (SELECT COUNT(*) FROM vipmission WHERE vipmission.idvipjob=j.idvipjob)
		END	AS countmission
		FROM vipjob j
		LEFT JOIN agent a ON j.idagent = a.idagent
		LEFT JOIN client c ON j.idclient = c.idclient
		'.$quid.'
		ORDER BY type ASC, datedevis DESC
		LIMIT  500');

$format = array(
	"0"  => array('id' => '0',  'nom' => 'JOB',       'font' => 'green',  'count' => 0),
	"1"  => array('id' => '1',  'nom' => 'DEVIS',     'font' => 'blue',   'count' => 0),
	"2"  => array('id' => '2',  'nom' => 'OUT',       'font' => 'red',    'count' => 0),
	"3"  => array('id' => '3',  'nom' => 'READY',     'font' => 'peru',   'count' => 0),
	"4"  => array('id' => '4',  'nom' => 'Admin',     'font' => 'white',  'count' => 0),
	"5"  => array('id' => '5',  'nom' => 'Arch',      'font' => 'black',  'count' => 0),
	"99" => array('id' => '99', 'nom' => 'Devis Out', 'font' => '#AE0101','count' => 0),
);

foreach($listing as $row) $format[$row['type']]['count']++;

## ENTETE
echo'
<div id="centerzonelarge">
	<div id="tabs">
		<ul>';
			foreach($format as $onglet) {
				if($onglet['count']>0) echo '<li><a href="#Page_'.$onglet['id'].'"><font color="'.$onglet['font'].'">'.$onglet['nom'].' ('.$onglet['count'].')</font></a></li>';
			}
		echo '
		</ul>';
		$last_type = -1;
		foreach($listing as $row) {
			## si changement d'type, recree un nouveau div
			if($last_type !== $row['type']) {
				$type = $row['type'];
				if($last_type !== -1) echo '</tbody></table></div>';
				echo '
				<div id="Page_'.$type.'">
					<table class="sortable-onload-6r rowstyle-alt no-arrow" border="0" width="100%" cellspacing="1" align="center">
						<thead>
							<tr>
								<th class="sortable-numeric">Job</th>
								<th width="50">Missions</th>
								<th class="sortable-text">R&eacute;f&eacute;rence</th>
								<th class="sortable-text">Assistant</th>
								<th class="sortable-text" colspan=2>Client</th>
								<th class="sortable-date-dmy">Date offre</th>
							</tr>
						</thead>
						<tbody>';
				$last_type = $type;
				$i=0;
			## si même état, continue d'afficher les lignes du tableau
			}
			$i++;
			echo '
			<tr ondblclick="location.href=\''.$_SERVER['PHP_SELF'].'?act=show&idvipjob='.$row['idvipjob'].'&etat='.$row['etat'].'\'">
				<td>'.$row['idvipjob'].'</td>
				<td align="center">'.$row['countmission'].'</td>
				<td><b>'.$row['reference'].'</b></td>
				<td>'.$row['prenom'].'</td>
				<td>'.$row['clsociete'].'</td>
				<td>'.$row['idclient'].'</td>
				<td>'.fdate($row['datedevis']).'</td>
			</tr>';
		}
		echo '</tbody>
		</table>
	</div>
</div>';
?>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('#tabs').tabs();
	});
</script>