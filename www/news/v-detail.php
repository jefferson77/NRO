<?php
if ($_REQUEST['idwebnews'] > 0) $infos = $DB->getRow("SELECT * FROM webneuro.webnews WHERE idwebnews = ".$_REQUEST['idwebnews']);
?>
<div id="centerzonelarge">
	<form action="adminnews.php" method="post">
		<input type="hidden" name="idwebnews" value="<?php echo $_REQUEST['idwebnews'];?>"> 
		<div align="center">
			<h2><?php echo ($_REQUEST['idwebnews'] > 0)?"Mise &agrave; jour d'une Web News":"Ajout d'une Web News" ?></h2>
			<table class="standard" border="0" cellspacing="3" cellpadding="3">
				<tr>
					<td>Date</td>
					<td><input type="text" name="datepublic" value="<?php echo fdate($infos['datepublic']); ?>"></td>
					<td align="right">Online ?</td>
					<td>
						<input type="radio" name="online" value="non" <?php echo ((empty($infos['online'])) OR ($infos['online'] == 'non'))?'checked':'' ?>> Non
						<input type="radio" name="online" value="oui" <?php echo ($infos['online'] == 'oui')?'checked':'' ?>> Oui
					</td>
				</tr>
				<tr>
					<th></th>
					<th><img src="<?php echo NIVO ?>illus/flags/fr.png" width="16" height="11" alt="Fr"> Fran&ccedil;ais</th>
					<th><img src="<?php echo NIVO ?>illus/flags/nl.png" width="16" height="11" alt="Nl"> Nederlands</th>
					<th><img src="<?php echo NIVO ?>illus/flags/gb.png" width="16" height="11" alt="En"> English</th>
				</tr>
				<tr>
					<td>Titre</td>
					<td><textarea name="titrefr" rows="2" cols="50"><?php echo $infos['titrefr']; ?></textarea></td>
					<td><textarea name="titrenl" rows="2" cols="50"><?php echo $infos['titrenl']; ?></textarea></td>
					<td><textarea name="titreen" rows="2" cols="50"><?php echo $infos['titreen']; ?></textarea></td>
				</tr>
				<tr>
					<td>Texte</td>
					<td><textarea name="textefr" rows="15" cols="50"><?php echo $infos['textefr']; ?></textarea></td>
					<td><textarea name="textenl" rows="15" cols="50"><?php echo $infos['textenl']; ?></textarea></td>
					<td><textarea name="texteen" rows="15" cols="50"><?php echo $infos['texteen']; ?></textarea></td>
				</tr>
			</table>
			<p><input type="submit" name="act" value="<?php echo ($_REQUEST['idwebnews'] > 0)?'Modifier':'Ajouter' ?>"></p>
		</div>
	</form>
</div>