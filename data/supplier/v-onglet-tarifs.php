<?php $infos = $DB->getRow("SELECT th, tkm FROM supplier WHERE idsupplier = ".$_REQUEST['idsupplier']); ?>
<div id="orangepeople">
	<form action="?act=tarifModif" method="post">
		<input type = "hidden" name="idsupplier" value="<?php echo $_REQUEST['idsupplier']; ?>"><br>
		Tarif H &nbsp;&nbsp;	<input type="text" name="th" value="<?php echo fnbr($infos['th']); ?>"><br>
		Tarif Km 				<input type="text" name="tkm" value="<?php echo fnbr($infos['tkm']); ?>"><br>
		<input type="submit" value="Modifier" name="Modifier">
	</form>
</div>