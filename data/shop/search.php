<div id="centerzonelarge">
<?php 
if ($_GET['etat'] == 0) {
?>
	<div class="infosection">Recherche des LIEUX</div>
	
	<form action="<?php echo $_SERVER['PHP_SELF'].'?act=search&etat=1';?>" method="post">
		<fieldset>
			<legend>
				Infos de recherche
			</legend>
			<label for="codeshop">Code</label><input type="text" name="codeshop" id="codeshop"><br>
			<label for="societe">Soci&eacute;t&eacute;</label><input type="text" name="societe" id="societe"><br>
			<label for="cp">CP</label><input type="text" name="cp" id="cp"><br>
			<label for="ville">Ville</label><input type="text" name="ville" id="ville"><br>
			<label for="snom">Contact</label><input type="text" name="snom" id="snom"><br>
			<label for="ccfd">CCFD</label><input type="text" name="ccfd" id="ccfd"><br>
			<label for="newshop">New ?</label><input type="radio" name="newweb" value="0"> Non &nbsp; <input type="radio" name="newweb" value="1"> Oui &nbsp; <input type="radio" name="newweb" value="" checked> Tous &nbsp;
		</fieldset>
		<div align="center"><input type="submit" name="Rechercher" value="Rechercher"></div>
  	</form>
<?php 
} 
if ($_GET['etat'] == 1)
{
	if ($_GET['action'] != 'skip')
	{
			$searchfields = array(
					'codeshop' => 'codeshop',
					'societe' => 'societe',
					'snom' => 'snom',
					'cp' => 'cp',
					'ville' => 'ville',
					'newweb' => 'newweb',
					'ccfd' => 'ccfd'
			  );

			$_SESSION['shopquid'] = $DB->MAKEsearch($searchfields);
	}

	# Recherche des résultats pour la liste
	$DB->inline("SELECT * FROM `shop` WHERE ".$_SESSION['shopquid']." ORDER BY codeshop LIMIT 200");
?>
	<fieldset>
		<legend>
			Recherche des LIEUX : <?php echo $DB->quod ; ?>
		</legend>
		<?php $classe = "standard" ?>
		<br>
		<table class="sortable-onload-3 paginate-25 rowstyle-alt no-arrow" border="0" width="100%" cellspacing="1" align="center">
			<thead>
				<tr>
					<th></th>
					<th class="sortable-numeric">Idshop</th>
					<th class="sortable-text">Code</th>
					<th class="sortable-text">Soci&eacute;t&eacute;</th>
					<th class="sortable-numeric">CP</th>
					<th class="sortable-text">Ville</th>
					<th class="sortable-text">adresse</th>
					<th>Pr&eacute;nom - Nom - Langue</th>
					<th></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
	<?php
	while ($row = mysql_fetch_array($DB->result)) {
		echo '<tr ondblclick="location.href=\''.$_SERVER['PHP_SELF'].'?act=show&idshop='.$row['idshop'].'\'">';
	?>
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=listingmodif&etat=1&action=skip';?>" method="post">
			<input type="hidden" name="idshop" value="<?php echo $row['idshop'] ;?>"> 
				<td><?php if ($row['glat'] > 0) echo '<img src="'.STATIK.'illus/geoloc.png" alt="geoloc.png" width="16" height="15" align="right">'; ?></td>
				<td><?php echo $row['idshop']; ?></td>
				<td><?php if ($row['newweb'] == 1) {echo '<font color="red">New </font>'; } ?><?php echo $row['codeshop'] ?></td>
				<td><?php echo $row['societe']; ?></td>
				<td><?php echo $row['cp']; ?></td>
				<td><?php echo $row['ville']; ?></td>
				<td><?php echo $row['adresse']; ?></td>
				<td>
					<?php echo createSelectList('qualite',array ( 'Monsieur' => 'Mr', 'Madame' => 'Mme', 'Mlle' => 'Mlle'),$row['qualite']); ?> 
				p: <input type="text" size="15" name="sprenom" value="<?php echo $row['sprenom']; ?>"> n: <input type="text" size="15" name="snom" value="<?php echo $row['snom']; ?>">
				&nbsp; l:
					<?php echo createSelectList('slangue',array ( 'FR' => 'FR', 'NL' => 'NL'),$row['slangue'], 'yes'); ?> 
				</td>
				<td><input type="submit" name="Modifier" value="M"><td>
			</tr>
		</form>
	<?php } ?>
			</tbody>
		</table>
	</fieldset>
<?php } ?>
</div>