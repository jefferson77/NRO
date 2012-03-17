<?php if ($_REQUEST['idwebevent'] > 0) $infos = $DB->getRow("SELECT * FROM webneuro.webevents WHERE idwebevent = ".$_REQUEST['idwebevent']); ?>
<div id="centerzonelarge">
	<form action="adminevents.php" method="post">
		<input type="hidden" name="idwebevent" value="<?php echo $_REQUEST['idwebevent'];?>"> 
		<div align="center">
			<h2><?php echo ($_REQUEST['idwebevent'] > 0)?"Mise &agrave; jour d'un Web Event":"Ajout d'un Web Event" ?></h2>
			<table class="standard" border="0" cellspacing="3" cellpadding="3">
				<tr>
					<td>Dates</td>
					<td>
						<input type="text" name="datepublic" value="<?php echo fdate($infos['datepublic']); ?>">
					</td>
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
					<td>Période</td>
					<td><input type="text" name="periodfr" size="50" value="<?php echo $infos['periodfr']; ?>"></td>
					<td><input type="text" name="periodnl" size="50" value="<?php echo $infos['periodnl']; ?>"></td>
					<td><input type="text" name="perioden" size="50" value="<?php echo $infos['perioden']; ?>"></td>
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
			<div>
				<h1>Photos</h1>
				<table border="0" cellspacing="5" cellpadding="5">
					<tr><th>Header</th></tr>
					<tr><td>Data</td></tr>
				</table>
			</div>
			<p><input type="submit" name="act" value="<?php echo ($_REQUEST['idwebevent'] > 0)?'Modifier':'Ajouter' ?>"></p>
		</div>
	</form>
	<form method="POST" action="?act=addphoto" enctype="multipart/form-data">
		<input type="hidden" name="idwebevent" value="<?php echo $infos['idwebevent'] ?>">
		<p>Ajouter une photo</p>
	     Fichier : <input type="file" name="fichier">
	     <input type="submit" name="envoyer" value="Envoyer le fichier">
	</form>
</div>