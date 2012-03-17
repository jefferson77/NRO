<?php 
## Vars init
if (!isset($_GET['action'])) $_GET['action'] = '';
if (!isset($_GET['listing'])) $_GET['listing'] = '';

if ($_GET['action'] != 'skip') {
	if ($_GET['listing'] == 'direct') {
		## Venant du lien 'mes jobs' ######################
		$_SESSION['animjobquid'] = "anj.idagent = '".$_SESSION['idagent']."' AND anj.statutarchive = 'open'";
	} else {
		## Recherche Normale ###############################
		$searchfields = array(
			'a.idagent' => 'idagent',
			'anj.idanimjob' => 'idanimjob',
			'anj.genre' => 'genre',
			'anj.reference' => 'reference',
			'c.codeclient' => 'codeclient',
			'c.societe' => 'csociete',
			'anj.boncommande' => 'boncommande',
			'anj.statutarchive' => 'statutarchive',
			'anj.datein' => 'date');

		$_SESSION['animjobquid'] = $DB->MAKEsearch($searchfields);
	}
}

$jobs = $DB->getArray("SELECT 
	anj.idanimjob, anj.reference, anj.genre, anj.briefing, anj.boncommande, anj.statutarchive, anj.etat, 
		CASE anj.facturation
			WHEN 0 THEN 1
			WHEN 1 THEN 1
			WHEN 2 THEN 2
			WHEN 3 THEN 1
			WHEN 4 THEN 2
			WHEN 5 THEN 5
			WHEN 6 THEN 6
			WHEN 8 THEN 8
			ELSE 9
		END AS facturation,
	COUNT(m.idanimjob) as nbr, MIN(m.datem) as datein, MAX(m.datem) as dateout, 
	a.prenom, a.idagent, 
	c.codeclient, c.societe AS clsociete, c.idclient, c.tel, c.fax, 
	co.idcofficer, co.qualite, co.onom, co.oprenom, co.fax AS cofax 
	FROM animjob anj
		LEFT JOIN agent a ON anj.idagent = a.idagent
		LEFT JOIN animation m ON anj.idanimjob = m.idanimjob
		LEFT JOIN client c ON anj.idclient = c.idclient 
		LEFT JOIN cofficer co ON anj.idcofficer = co.idcofficer 
	WHERE ".$_SESSION['animjobquid']."
	GROUP BY anj.idanimjob
	ORDER BY anj.facturation, datein DESC, dateout DESC
	LIMIT 300");

$format = array(
	"1" => array('id'=>'1', 'nom'=>'Open', 'font'=>'green'),
	"2" => array('id'=>'2', 'nom'=>'Admin', 'font'=>'white'),
	"5" => array('id'=>'5', 'nom'=>'Prefac', 'font'=>'#AE0101'),
	"6" => array('id'=>'6', 'nom'=>'Facturation', 'font'=>'peru'),
	"8" => array('id'=>'8', 'nom'=>'Archive', 'font'=>'black'),
	"9" => array('id'=>'9', 'nom'=>'OUT', 'font'=>'red')
);	

foreach($jobs as $row) {
	$format[$row['facturation']]['count']++;
}
?>
<div id="centerzonelarge">
<fieldset>
	<legend>
		<b>listing des Actions Anim</b>
	</legend>
	<b>Votre Recherche : ( <?php echo count($jobs); ?> ) <?php echo $DB->quod; ?></b><br>
</fieldset>
<div id="tabs">
	<ul><?php
		foreach($format as $onglet) {
			if($onglet['count']>0) echo '<li><a href="#Page_'.$onglet['id'].'"><font color="'.$onglet['font'].'">'.$onglet['nom'].' ('.$onglet['count'].')</font></a></li>';
		} ?>
	</ul>
<br>
<?php
$last_type = -1;
foreach ($jobs as $row) {
	if($last_type !== $row['facturation']) {		
		$type = $row['facturation'];
		if($last_type !== -1) echo '</tbody></table></div>';
?>
	<div id="Page_<?php echo $type ?>">
		<table class="sortable-onload-1r rowstyle-alt no-arrow" border="0" width="99%%" cellspacing="1" align="center">
			<thead>
				<tr>
					<th class="sortable-text">Agent</th>
					<th class="sortable-numeric">ID</th>
					<th class="sortable-numeric">Mis</th>
					<th class="sortable-text">Action</th>
					<th class="sortable-text">PO</th>
					<th class="sortable-date-dmy" colspan="2">Dates</th>
					<th class="sortable-text" colspan="2">Client</th>
					<th class="sortable-text">Genre</th>
					<th class="sortable-text">Fac</th>
					<th class="sortable-text">Etat</th>
				</tr>
			</thead>
			<tbody>
		
	<?php
		$last_type = $type;
		$i=0;
	}
	$i++;
?>
			<tr ondblclick="location.href='<?php echo $_SERVER['PHP_SELF'].'?act=showjob&idanimjob='.$row['idanimjob'];?>'">
				<td><?php echo $row['prenom'] ?></td>
				<td><?php echo $row['idanimjob'] ?></td>
				<td><?php echo $row['nbr'] ?></td>
				<td><?php echo $row['reference'] ?></td>
				<td><?php echo $row['boncommande'] ?></td>
				<td><?php echo fdate($row['datein']) ?></td>
				<td><?php echo fdate($row['dateout']) ?></td>
				<td><?php echo $row['clsociete']; ?></td>
				<td><?php echo $row['codeclient']; ?></td>
				<td><?php echo $row['genre'] ?></td>
				<td><?php echo $row['facturation'] ?></td>
				<td><?php echo $row['etat'] ?></td>
			</tr>
<?php }  ?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('#tabs').tabs();
	});
</script>