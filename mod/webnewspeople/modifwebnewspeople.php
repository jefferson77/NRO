<?php
# Entete de page
define('NIVO', '../../'); 
$Titre = 'WEB NEWS';
$PhraseBas = 'Modifier une news';
$Style = 'vip';

include NIVO."includes/entete.php" ;

if ($_REQUEST['idwebnewspeople'] > 0) $infos = $DB->getRow("SELECT * FROM webneuro.webnewspeople WHERE idwebnewspeople = ".$_REQUEST['idwebnewspeople']);
?>
<div id="leftmenu"></div>
<form action="adminwebnewspeople.php" method="post">
	<div id="infozone">
		<input type="hidden" name="idwebnewspeople" value="<?php echo $_REQUEST['idwebnewspeople'];?>"> 
		<div align="center">
			<h2><?php echo ($_REQUEST['idwebnewspeople'] > 0)?"Mise &agrave; jour d'une Web News PEOPLE":"Ajout d'une Web News PEOPLE" ?></h2>
			<table class="standard" border="0" cellspacing="3" cellpadding="3">
				<tr>
					<td>Date</td>
					<td><input type="text" name="datepublic" value="<?php echo fdate($infos['datepublic']); ?>"></td>
				</tr>
				<tr>
					<td colspan="2">Fran&ccedil;ais</td>
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
					<td>Urgent ?</td>
					<td>
						<?php 
						echo '<input type="radio" name="urgent" value="0" '; if ((isset($infos['urgent'])) OR ($infos['urgent'] == '0')) { echo 'checked';} echo'> Non';
						echo '<input type="radio" name="urgent" value="1" '; if ($infos['urgent'] == '1') { echo 'checked';} echo'> Oui';
						?>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
			</table>
			<input type="submit" name="act" value="<?php echo ($_REQUEST['idwebnewspeople'] > 0)?'Modifier':'Ajouter' ?>">
		</div>
	</div>
</form>
<?php include NIVO."includes/pied.php" ?>