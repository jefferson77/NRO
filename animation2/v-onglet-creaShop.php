<?php
$shopselectionbuild = $DB->getOne("SELECT shopselectionbuild FROM `animbuild` WHERE `idanimjob` = ".$_REQUEST['idanimjob']." LIMIT 1");

$posz = array_filter(explode("|", $infosjob['shopselection']));

### si tableau des selection des shops est non vide
if (is_array($posz)) $shops = $DB->getArray("SELECT idshop, codeshop, societe, cp, ville, adresse FROM shop WHERE `idshop` IN ('".implode("', '", $posz)."')");
if (count ($posz) >= 1) {
?>
<Fieldset class="width2">
	<legend class="width">Points of Sales : <?php echo $animpos_14.' ( '.count($shops).' ) '; ?></legend>
	<form name="pos" action="?act=shopselect" method="post">
		<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>">
		<i><?php echo $animpos_15; ?></i>
		<table class="sortable-onload-0r rowstyle-alt paginate-10 no-arrow" border="0" width="90%" cellspacing="1" align="center">
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
					<td align="center"><input type="checkbox" name="shopselectionbuild[]" value="'.$row['idshop'].'" '.(((strchr($shopselectionbuild, "|".$row['idshop']."|")) or ($_GET['checked'] == 'checked'))?'checked':'').'></td>
				</tr>';
		} ?>
			</tbody>	
		</table>
		<p align="center"><input type="submit" name="action" value="<?php echo $animpos_22; ?>"> </p>
		
	</form>
</Fieldset>
<?php } ?>
<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
<tr>
	<td width="50%">
		<form action="?act=prodshow" method="post">
			<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>"> 
			<input type="submit" name="action" value="&lt;&lt;&nbsp; <?php echo $tool_05c;?>"> 
		</form>
	</td>
	<td width="50%" align="right">
		<?php if ((empty($shopselectionbuild)) or ($shopselectionbuild == '--')) { ?>
			Aucun POS s&eacute;l&eacute;ctionn&eacute;
		<?php } else { ?>
			<form action="adminanim.php?act=moulinette" target="_top" method="post">
				<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>"> 
				<input type="submit" name="action" value="<?php echo $tool_05d1;?>"> 
			</form>
		<?php } ?>
	</td>
</tr>
</table>
<script type="text/javascript" charset="utf-8">
	function ounCheck(status) {
		if (status == 0) uncheckAll(document.getElementsByName("shopselectionbuild[]"));
		if (status == 1) checkAll(document.getElementsByName("shopselectionbuild[]"));
	}

	function checkAll(field) {
		for (i = 0; i < field.length; i++) field[i].checked = true ;
	}

	function uncheckAll(field) {
		for (i = 0; i < field.length; i++) field[i].checked = false ;
	}
</script>
