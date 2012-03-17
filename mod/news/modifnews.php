<?php
# Entete de page
define('NIVO', '../../');
$Titre = 'NEWS';
$PhraseBas = 'Modifier une news';

include_once NIVO."includes/entete.php" ;

if (!empty($_REQUEST['id'])) $infos = $DB->getRow("SELECT * FROM `news` WHERE `id` = ".$_REQUEST['id']);

?>
<form action="adminnews.php" method="post">
<div id="leftmenu"></div>
<div id="infozone">
	<input type="hidden" name="id" value="<?php echo $_REQUEST['id'] ?>">
	<table class="standard" border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td>Table</td>
			<td>
				<input type="checkbox" name="newspage[]" value="user" <?php echo (strstr($infos['newspage'], 'user') or empty($infos['newspage']))?'checked':'' ?>> User
				<input type="checkbox" name="newspage[]" value="admin"<?php echo (strstr($infos['newspage'], 'admin'))?'checked':'' ?>> Admin
			</td>
		</tr>
		<tr>
			<td>Date</td>
			<td><input type="text" name="ndate" value="<?php echo (isset($infos['ndate']))?fdate($infos['ndate']):strftime("%d/%m/%Y", time()) ?>"></td>
		</tr>
		<tr>
			<td>News</td>
			<td><textarea name="description" rows="8" cols="40"><?php echo stripslashes($infos['description']); ?></textarea></td>
		</tr>
	</table>
	<input type="submit" name="act" value="<?php echo ($_REQUEST['id'] > 0)?'Modifier':'Ajouter' ?>">
</div>
</form>
<?php include NIVO."includes/pied.php"; ?>
