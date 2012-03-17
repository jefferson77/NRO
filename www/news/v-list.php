<style type="text/css" media="screen">
	img {
		border: 0px;
	}
</style>
<?php
$news = $DB->getArray("SELECT * FROM webneuro.webnews ORDER BY idwebnews DESC LIMIT 40");
?>
<div id="centerzonelarge">
	<h1 align="center">Gestion des Web News</h1>
<?php
if (!empty($action)) echo '<div class="info">'.$action.'</div>';
?>
	<table class="sortable-onload-0r rowstyle-alt paginate-10 no-arrow" border="0" width="90%" cellspacing="1" align="center">
		<thead>
			<tr>
				<th class="sortable-date-dmy">Date</th>
				<th class="sortable-text">News</th>
				<th class="sortable-text">Online ?</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
	<?php
	foreach ($news as $row)
	{
		echo '<tr ondblclick="location.href=\'?act=show&idwebnews='.$row['idwebnews'].'\'">
				<td>'.fdate($row['datepublic']).'</td>
				<td>'.$row['titrefr'].'</td>
				<td>'.$row['online'].'</td>
				<td style="padding: 0px;width: 16px"><a href="?act=del&idwebnews='.$row['idwebnews'].'" onclick="javascript:return confirm(\'Realy delete this news ?\')"><img src="'.NIVO.'nro/illus/minus_circle.png" alt="del"></a></td>
			</tr>';
	} ?>
		</tbody>	
	</table>
	<br>
	<div align="center">
		<a href="?act=add">Ajouter</a>
	</div>
</div>
