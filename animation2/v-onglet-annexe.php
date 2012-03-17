<?php
## Liste des fichiers Annexes de ce job
$lesfiles = array_filter(scandir($pathannexe), 'isannex');

## Photos
$nphot = 4; #nombre de photos par rangée
$lesphotos = array_filter(scandir($pathgaleries.$_REQUEST['idanimjob']), 'isphoto');

$listmiss = $DB->getArray("SELECT
			a.idanimation, s.societe, s.ville, p.pnom, p.pprenom
		FROM animation a
			LEFT JOIN shop s ON a.idshop = s.idshop
			LEFT JOIN people p ON a.idpeople = p.idpeople
		WHERE a.idanimjob = '".$_REQUEST['idanimjob']."'");

?>
<table width="100%" >
	<tr>
		<td width="30%" valign="top">
			<h2>Fichiers en Annexe</h2>
			<table class="sortable rowstyle-alt" border="0" width="90%" cellspacing="1" align="center">
				<thead>
					<tr>
						<th colspan="3">
							<form enctype="multipart/form-data" action="?act=annexeupfile" method="POST">
								<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'] ?>">
								<input type="hidden" name="MAX_FILE_SIZE" value="12000000" />
								<input name="userfile" type="file">
								<input type="submit" value="Ajouter">
							</form>
						</th>
					</tr>
				</thead>
				<tbody>
<?php foreach ($lesfiles as $name) { ?>
					<tr>
						<td width="18"><?php echo iconfile($name); ?></td>
						<td><a href="/annexe/anim/<?php echo $name ?>" target="_blank"><?php echo $name ?></a></td>
						<td width="18">
							<form action="?act=annexedelfile" method="POST" onSubmit="return confirm('<?php echo $tool_21; ?>')">
								<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'] ?>">
								<input type="hidden" name="delfile" value="<?php echo $name; ?>">
								<input type="submit" class="btn bin_closed" name="action" value="DeleteF" title="Effacer le fichier">
							</form>
						</td>
					</tr>
<?php  } ?>
				</tbody>
			</table>
		</td>
		<td valign="top">
			<h2>Photos</h2>
			<table class="sortable rowstyle-alt" border="0" width="90%" cellspacing="1" align="center">
				<thead>
					<tr>
						<th colspan="<?php echo $nphot; ?>">
							<form enctype="multipart/form-data" action="?act=annexeupphoto" method="POST">
								<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'] ?>">
								<input type="hidden" name="MAX_FILE_SIZE" value="20000000" />
								<input name="photofile" type="file">
								<select name="mission">
									<option value="none">Assigner la photo a une mission</option>
									<option value="none">--------------------------------</option>
									<?php  foreach ($listmiss as $row) echo '<option value="'.$row['idanimation'].'">'.$row['idanimation'].' | '.$row['pprenom'].' '.$row['pnom'].' | '.$row['societe'].' '.$row['ville'].'</option>'; ?>
								</select>
								<input type="submit" value="Ajouter">
							</form>
						</th>
					</tr>
				</thead>
				<tbody>

				</tbody>
<?php
$i=0;
foreach ($lesphotos as $name) {
	$i++;
	if (fmod($i, $nphot) == 1) echo '<tr>';
?>
					<td align="center" valign="bottom" width="<?php echo floor(100 / $nphot) ?>%">
						<div class="phot">
							<a href="<?php echo '/galerie/anim/'.$_REQUEST['idanimjob'].'/'.$name; ?>"><img src="<?php echo '/galerie/anim/'.$_REQUEST['idanimjob'].'/t'.$name; ?>" alt=""></a>
							<div class="labl">
								<form action="?act=annexedelphoto" method="POST" onSubmit="return confirm('<?php echo $tool_21; ?>')">
									<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'] ?>">
									<input type="hidden" name="delfile" value="<?php echo $name; ?>">
									<?php $nms = explode("_", $name); echo $nms[0].' '.$nms[1]{0}; ?>
									<input type="submit" name="action" value="DeleteP" title="Effacer la photo">
								</form>
							</div>
						</div>
					</td>
<?php
	if (fmod($i, $nphot) == 0) echo '</tr>';
}

if ($i > 0) {
	while(fmod($i, $nphot) != 0) {
		echo '<td></td>';
		$i++;
	}
	echo '</tr>';
}

?>
			</table>
		</td>
	</tr>
</table>