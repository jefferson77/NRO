<style type="text/css" media="screen">
	img {
		border: 0px;
	}
</style>
<?php
$events = $DB->getArray("SELECT * FROM webneuro.webevents ORDER BY idwebevent DESC LIMIT 40");
?>
<div id="centerzonelarge">
	<h1 align="center">Gestion des Web Events</h1>
<?php
if (!empty($action)) echo '<div class="info">'.$action.'</div>';
?>
	<table class="sortable-onload-0r rowstyle-alt paginate-10 no-arrow" border="0" width="90%" cellspacing="1" align="center">
		<thead>
			<tr>
				<th class="sortable-date-dmy">Date</th>
				<th class="sortable-text">Event</th>
				<th class="sortable-text">Online ?</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
	<?php
	foreach ($events as $row)
	{
		echo '<tr ondblclick="location.href=\'?act=show&idwebevent='.$row['idwebevent'].'\'">
				<td>'.fdate($row['datepublic']).'</td>
				<td>'.$row['titrefr'].'</td>
				<td>'.$row['online'].'</td>
				<td style="padding: 0px;width: 16px"><a href="?act=del&idwebevent='.$row['idwebevent'].'" onclick="javascript:return confirm(\'Realy delete this event ?\')"><img src="'.NIVO.'nro/illus/minus_circle.png" alt="del"></a></td>
			</tr>';
	} ?>
		</tbody>	
	</table>
	<br>
	<div align="center">
		<a href="?act=add">Ajouter</a>
	</div>
</div>
