<?php $lesfiles = dirFiles($pathannexe); ?>
<h2>Fichiers en Annexe</h2>
<table class="sortable rowstyle-alt" border="0" width="40%" cellspacing="1" align="center">
	<thead>
		<tr>
			<th colspan="3">
				<form enctype="multipart/form-data" action="?act=annexeupfile" method="POST">
					<input type="hidden" name="idclient" value="<?php echo $_REQUEST['idclient'] ?>">
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
			<td><a href="<?php echo "/".substr($pathannexe, strlen(Conf::read('Env.root')) + 6)."/".$name ?>" target="_blank"><?php echo $name; ?></a></td>
			<td width="18">
				<form action="?act=annexedelfile" method="POST" onSubmit="return confirm('<?php echo $tool_21; ?>')">
					<input type="hidden" name="idclient" value="<?php echo $_REQUEST['idclient'] ?>">
					<input type="hidden" name="delfile" value="<?php echo $name; ?>">
					<input type="sumbit" name="action" value="DeleteF" class="btn stock_stop-16" title="Effacer le fichier">
				</form>
			</td>
		</tr>
<?php  } ?>
	</tbody>
</table>