<?php
## Get Shops
$idshops = array_filter(explode("|", $infosjob['shopselection']));
if (count($idshops) > 0) $shops = $DB->getArray("SELECT * FROM shop WHERE idshop IN (".implode(", ", $idshops).")");
?>
<Fieldset class="width2">
	<legend class="width">Points of Sales / POS : <?php echo $animpos_25; ?></legend>
		<table class="standard" border="0" cellspacing="4" cellpadding="10" align="center" width="99%">
			<tr>
				<td class="contenu" valign="top" width="50%" align="center">
					<form action="?act=animshophistoric" method="post">
						<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>"> 
						<input type="submit" name="action" value="A partir de l'historique du client"> 
					</form>
				</td>
				<td class="contenu" valign="top" align="center">
					<form action="?act=animshopsearch" method="post">
						<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>"> 
						<input type="submit" name="action" value="<?php echo $animpos_08; ?>"> 
					</form>
				</td>
			</tr>
		</table>
</Fieldset>
<br>
<?php if (count($shops) > 0): ?>
<Fieldset class="width2">
	<legend class="width">Points of Sales : <?php echo $animpos_14; echo ' ( '.count($shops).' ) '; ?></legend>
	<i><?php echo $animpos_15; ?></i>
	<form action="?act=animshopmodif" method="post">
		<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>"> 
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
					<td align="center"><input type="checkbox" name="shopselection[]" value="'.$row['idshop'].'" '.((strchr("|".$infosjob['shopselection']."|", $row['idshop']))?'checked':'').'></td>
				</tr>';
		} ?>
			</tbody>	
		</table>
		<p align="center"><input type="submit" name="action" value="<?php echo $animpos_22; ?>"></p>
	</form>
</Fieldset>
<script type="text/javascript" charset="utf-8">
	function ounCheck(status) {
		if (status == 0) uncheckAll(document.getElementsByName("shopselection[]"));
		if (status == 1) checkAll(document.getElementsByName("shopselection[]"));
	}

	function checkAll(field) {
		for (i = 0; i < field.length; i++) field[i].checked = true ;
	}

	function uncheckAll(field) {
		for (i = 0; i < field.length; i++) field[i].checked = false ;
	}
</script>
<?php else: ?>
	<p align="center">Vous n'avez aucun Magasin sélectionné pour ce Job</p>
<?php endif ?>
