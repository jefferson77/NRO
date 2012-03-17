<form action="?act=animshopadd" method="post">
	<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>">
	<h1 id="echo"><?php echo $titreListe ?> (<?php echo count($shops); ?>)</h1>
	<?php echo (count($shops) == 200)?'<i>Votre recherche est trop large, seuls les 200 premiers résultats sont affichés</i>':'' ?>
	<table class="sortable-onload-1 rowstyle-alt no-arrow" border="0" width="90%" cellspacing="1" align="center">
		<thead>
			<tr>
				<th class="sortable-text"><?php echo $animpos_16; ?></th>
				<th class="sortable-text"><?php echo $animpos_17; ?></th>
				<th class="sortable-text"><?php echo $animpos_20; ?></th>
				<th class="sortable-numeric"><?php echo $animpos_18; ?></th>
				<th class="sortable-text"><?php echo $animpos_19; ?></th>
				<th><input type="checkbox" onclick="ounCheck(this.checked ?'1':'0')" checked title="Check/UnCheck ALL"></th>
			</tr>
		</thead>
		<tbody>
	<?php
	foreach ($shops as $row)
	{
		echo '<tr>
				<td>'.$row['codeshop'].'</td>
				<td>'.$row['societe'].'</td>
				<td>'.$row['adresse'].'</td>
				<td>'.$row['cp'].'</td>
				<td>'.$row['ville'].'</td>
				<td align="center"><input type="checkbox" name="shopadd[]" value="'.$row['idshop'].'" '.((strchr($infosjob['shopselection'], $row['idshop']))?'checked':'').'></td>
			</tr>';
	} ?>
		</tbody>	
	</table>
	<p align="center"><input type="submit" name="action" value="Ajouter la s&eacute;lection"></p> 
</form>
<script type="text/javascript" charset="utf-8">
	function ounCheck(status) {
		if (status == 0) uncheckAll(document.getElementsByName("shopadd[]"));
		if (status == 1) checkAll(document.getElementsByName("shopadd[]"));
	}

	function checkAll(field) {
		for (i = 0; i < field.length; i++) field[i].checked = true ;
	}

	function uncheckAll(field) {
		for (i = 0; i < field.length; i++) field[i].checked = false ;
	}
</script>
