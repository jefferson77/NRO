<?php if (!empty($_REQUEST['idsupplier'])) $sofficers = $DB->getArray("SELECT * FROM `sofficer` where `idsupplier` = ".$_REQUEST['idsupplier']); ?>
<div id="orangepeople">
	<table class="sortable rowstyle-alt no-arrow" border="0" width="90%" cellspacing="1" align="center">
		<thead>
			<tr>
				<th>Lang</th>
				<th>Qualit&eacute;</th>
				<th>Nom</th>
				<th>Pr&eacute;nom</th>
				<th>Dept</th>
				<th>Tel</th>
				<th>Fax</th>
				<th>Gsm</th>
				<th>email</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
<?php foreach ($sofficers as $row) { ?>
			<tr>
				<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
					<input type="hidden" name="idsupplier" value="<?php echo $_REQUEST['idsupplier'];?>">
					<input type="hidden" name="idsofficer" value="<?php echo $row['idsofficer'];?>">
				<td>
					<select name="langue">
					<?php
					echo '<option value="FR"'.((($row['langue'] == '') OR ($row['langue'] == 'FR'))?'selected':'').'>FR</option>';
					echo '<option value="NL"'.(($row['langue'] == 'NL')?'selected':'').'>NL</option>';
					?>
					</select>
				</td>
				<td>
					<select name="qualite">
					<?php
					echo '<option value="Monsieur"'.((($row['qualite'] == '') OR ($row['qualite'] == 'Monsieur') OR ($row['qualite'] == 'Mr'))?'selected':'').'>Mr</option>';
					echo '<option value="Madame"'.(($row['qualite'] == 'Madame')?'selected':'').'>Mme</option>';
					echo '<option value="Mlle"'.(($row['qualite'] == 'Mlle')?'selected':'').'>Mlle</option>';
					?>
					</select>
				</td>
				<td><input type="text" size="15" name="onom" value="<?php echo $row['onom']; ?>"></td>
				<td><input type="text" size="12" name="oprenom" value="<?php echo $row['oprenom']; ?>"></td>
				<td><input type="text" size="14" name="departement" value="<?php echo $row['departement']; ?>"></td>
				<td><input type="text" size="14" name="tel" value="<?php echo $row['tel']; ?>"></td>
				<td><input type="text" size="14" name="fax" value="<?php echo $row['fax']; ?>"></td>
				<td><input type="text" size="14" name="gsm" value="<?php echo $row['gsm']; ?>"></td>
				<td><input type="text" size="25" name="email" value="<?php echo $row['email']; ?>"></td>
				<td>
					<input type="submit" class="btn tick_circle" name="act" value="contactModif">
					<input type="submit" class="btn minus_circle" name="act" value="contactDelete">
				</td>
				</form>
			</tr>
<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
					<input type="hidden" name="idsupplier" value="<?php echo $_REQUEST['idsupplier'];?>">
				<td>
					<select name="langue">
					<?php
					echo '<option value="FR"'.(((isset($infos['langue'])) OR ($infos['langue'] == 'FR'))?'selected':'').'>FR</option>';
					echo '<option value="NL"'.(($infos['langue'] == 'NL')?'selected':'').'>NL</option>';
					?>
					</select>
				</td>
				<td>
					<select name="qualite">
					<?php
					echo '<option value="Monsieur"'.(((isset($infos['qualite'])) OR ($infos['qualite'] == 'Monsieur'))?'selected':'').'>Mr</option>';
					echo '<option value="Madame"'.(($infos['qualite'] == 'Madame')?'selected':'').'>Mme</option>';
					echo '<option value="Mlle"'.(($infos['qualite'] == 'Mlle')?'selected':'').'>Mlle</option>';
					?>
					</select>
				</td>
				<td><input type="text" size="15" name="onom" value=""></td>
				<td><input type="text" size="12" name="oprenom" value=""></td>
				<td><input type="text" size="14" name="departement" value=""></td>
				<td><input type="text" size="14" name="tel" value=""></td>
				<td><input type="text" size="14" name="fax" value=""></td>
				<td><input type="text" size="14" name="gsm" value=""></td>
				<td><input type="text" size="25" name="email" value=""></td>
				<td><input type="submit" class="btn plus_circle" name="act" value="contactAdd"></td>
				</form>
			</tr>
		</tfoot>
	</table>
</div>