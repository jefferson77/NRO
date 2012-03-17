<div align="center">
	<form action="?act=modinfos" method="post">
		<input type="hidden" name="s" value="<?php echo $_REQUEST['s'];?>"> 
		<input type="hidden" name="idclient" value="<?php echo $_REQUEST['idclient'];?>">
		<textarea name="notelarge" rows="10" cols="130"><?php echo $infos['notelarge']; ?></textarea><br>
		<input type="submit" name="bouton" value="Modifier">
	</form>
</div>
