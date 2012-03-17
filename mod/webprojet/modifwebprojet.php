<?php
# Entete de page
define('NIVO', '../../');

$Titre = 'WEB projet';
$PhraseBas = 'Modifier une projet';
$Style = 'admin';

include NIVO."includes/entete.php" ;

if ($_REQUEST['idwebprojet'] > 0) $infos = $DB->getRow("SELECT * FROM webneuro.webprojet WHERE idwebprojet = ".$_REQUEST['idwebprojet']);
?>
<div id="leftmenu"></div>
<form action="adminwebprojet.php" method="post">
	<div id="infozone">
		<input type="hidden" name="idwebprojet" value="<?php echo $_REQUEST['idwebprojet'];?>"> 
		<div align="center">
			<h2><?php echo ($_REQUEST['idwebprojet'] > 0)?"Mise &agrave; jour d'une Web projet":"Ajout d'une Web projet" ?></h2>
			<table class="standard" border="0" cellspacing="3" cellpadding="3">
				<tr>
					<td>Date 1</td>
					<td><input type="text" name="date1" value="<?php echo fdate($infos['date1']); ?>"></td>
				</tr>
				<tr>
					<td>Date 2</td>
					<td><input type="text" name="date2" value="<?php echo fdate($infos['date2']); ?>"></td>
				</tr>
				<tr>
					<td>Titre Fr</td>
					<td><textarea name="titrefr" rows="2" cols="100"><?php echo $infos['titrefr']; ?></textarea></td>
				</tr>
				<tr>
					<td>Texte Fr</td>
					<td><textarea name="textefr" rows="10" cols="100"><?php echo $infos['textefr']; ?></textarea></td>
				</tr>
				<tr>
					<td colspan="2">N&eacute;&eacute;rlandais</td>
				</tr>
				<tr>
					<td>Titre Nl</td>
					<td><textarea name="titrenl" rows="2" cols="100"><?php echo $infos['titrenl']; ?></textarea></td>
				</tr>
				<tr>
					<td>Texte Nl</td>
					<td><textarea name="textenl" rows="10" cols="100"><?php echo $infos['textenl']; ?></textarea></td>
				</tr>
				<tr>
					<td>Online ?</td>
					<td>
						<?php 
						echo '<input type="radio" name="online" value="non" '; if ((isset($infos['online'])) OR ($infos['online'] == 'non')) { echo 'checked';} echo'> Non';
						echo '<input type="radio" name="online" value="oui" '; if ($infos['online'] == 'oui') { echo 'checked';} echo'> Oui';
						?>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
			</table>
			<input type="submit" name="act" value="Modifier"> 
		</div>
	</div>
</form>
<?php include NIVO."includes/pied.php" ?>
