<?php
define('NIVO', '../../'); 

include NIVO."includes/ifentete.php" ;

## Recuperation des datas dans les 3 secteurs
$lesvips = $DB->getArray("SELECT 
	p.codepeople, p.pnom, p.pprenom, 'VI' as secteur
	FROM vipmission m 
		LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob 
		LEFT JOIN people p ON m.idpeople = p.idpeople 
	WHERE j.idclient = ".$_REQUEST['idclient']." AND j.etat >= 15 AND m.idpeople > 0
	GROUP BY p.idpeople
	ORDER BY p.pnom");

$lesanims = $DB->getArray("SELECT 
	p.codepeople, p.pnom, p.pprenom, 'AN' as secteur
	FROM animation an 
		LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
		LEFT JOIN people p ON an.idpeople = p.idpeople 
	WHERE j.idclient = ".$_REQUEST['idclient']." AND an.facturation >= 8 AND an.idpeople > 0
	GROUP BY p.idpeople
	ORDER BY p.pnom");
	
$lesmerchs = $DB->getArray("SELECT
	p.codepeople, p.pnom, p.pprenom, 'ME' as secteur
	FROM merch me 
		LEFT JOIN people p ON me.idpeople = p.idpeople 
	WHERE me.idclient = ".$_REQUEST['idclient']." AND me.facturation >= 8 AND me.idpeople > 0
	GROUP BY p.idpeople
	ORDER BY p.pnom");
	
$lespeople = array_merge($lesvips, $lesanims, $lesmerchs);

?>
<style type="text/css" media="screen">
	body {background-color: transparent;}
</style>
<table id="" class="sortable-onload-0 rowstyle-alt no-arrow" border="0" width="98%" cellspacing="1" align="center">
	<thead>
		<tr>
			<th class="sortable-text">Sec</th>
			<th class="sortable-numeric">Regis</th>
			<th class="sortable-text">Nom</th>
			<th class="sortable-text">Prenom</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($lespeople as $row) { ?>
	<tr>
		<td><?php echo $row['secteur'] ?></td>
		<td><?php echo $row['codepeople'] ?></td>
		<td><?php echo $row['pnom'] ?></td>
		<td><?php echo $row['pprenom'] ?></td>
	</tr>
<?php } ?>
	<tbody>
</table>
<?php include NIVO."includes/ifpied.php"; ?>
