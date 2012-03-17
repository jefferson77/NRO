<?php
## config array
$lvls['user'] = array('color' => '#009900', 'titre' => 'Utilisateurs', 'pos' => '1');
$lvls['admin'] = array('color' => '#FF6600', 'titre' => 'Administrateurs', 'pos' => '2');
$lvls['devel'] = array('color' => '#990000', 'titre' => 'Developpers', 'pos' => '3');
$lvls['out'] = array('color' => '#666666', 'titre' => 'OUT', 'pos' => '4');

## get agent datas
$agents = $DB->getArray("SELECT * FROM `agent` ORDER BY nom, prenom");

foreach ($agents as $row) {
	if($row['isout'] == 'Y') $row['adlevel'] = 'out';
	$tabs[$row['adlevel']][] = $row;
}

?>
<div id="centerzonelarge">
	<h1 align="center">Gestion des Agents</h1>
	<?php if (!empty($msg)) echo '<div class="dbaction">'.$msg.'</div><br>'; ?>
	<div id="tabs" style="width: 80%;margin: auto;">
        <ul>
	<?php
	foreach ($lvls as $lvl)
	{
		echo '<li><a href="#fragment-'.$lvl['pos'].'">'.$lvl['titre'].'</a></li>';
	}
	?>
		</ul>
	<?php foreach ($tabs as $key => $tab) { ?>
        <div id="fragment-<?php echo $lvls[$key]['pos'] ?>">
			<table class="sortable-onload-2 paginate-20 rowstyle-alt no-arrow" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
				<thead>
					<tr>
						<th class="sortable-numeric">ID</th>
						<th class="sortable-numeric">People</th>
						<th class="sortable-text">Qualite</th>
						<th class="sortable-text">Prenom</th>
						<th class="sortable-text">Nom</th>
						<th class="sortable-text">Email</th>
						<th class="sortable-text">Secteur</th>
						<th class="sortable-text">T&eacute;l&eacute;phone</th>
						<th class="sortable-text">GSM</th>
					</tr>
				</thead>
				<tbody>
			<?php foreach($tab as $row) { ?>
				<tr ondblclick="location.href='?act=show&id=<?php echo $row['idagent'];?>'">
					<td><?php echo $row['idagent'] ?></td>
					<td><?php echo $row['idpeople'] ?></td>
					<td><?php echo $row['qualite'] ?></td>
					<td><?php echo $row['prenom'] ?></td>
					<td><?php echo $row['nom'] ?></td>
					<td align="left"><?php echo $row['email'] ?></td>
					<td><?php echo $row['secteur'] ?></td>
					<td><?php echo $row['atel'] ?></td>
					<td><?php echo $row['agsm'] ?></td>
				</tr>
			<?php } ?>
				</tbody>
			</table>
		</div>
	<?php } ?>
	</div>
	<br>
	<table border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<td align="center"><a href="?act=show">Ajouter</a></td>
		</tr>
	</table>
</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('#tabs').tabs();
	});
</script>