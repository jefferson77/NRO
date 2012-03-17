<?php #  Centre de recherche de Clients ?>
<div id="centerzonelarge">
<?php 

	#echo $recherche;
	# Recherche des résultats
	$client = new db('webclient', 'idwebclient', 'webneuro');
#	$client->inline("SELECT * FROM `webclient` WHERE 1 ORDER BY secteur");
	$client->inline("SELECT * FROM `webclient` WHERE etat = 1 ORDER BY secteur");
	$secteur = 'z'
?>
	<fieldset>
		<legend>
			Recherche des CLIENTS : <?php echo $_SESSION['clientquod']; ?>
		</legend>
		<?php $classe = "standard" ?>
		<br>
		<table class="<?php echo $classe; ?>" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
			<tr>
				<th class="<?php echo $classe; ?>">ID web</th>
				<th class="<?php echo $classe; ?>">Soci&eacute;t&eacute;</th>
				<th class="<?php echo $classe; ?>">Nom</th>
				<th class="<?php echo $classe; ?>">Tel</th>
				<th class="<?php echo $classe; ?>">Fax</th>
				<th class="<?php echo $classe; ?>">Email</th>
				<th class="<?php echo $classe; ?>"></th>
			</tr>
	<?php
	while ($row = mysql_fetch_array($client->result)) { 
		if ($row['secteur'] != $secteur) { echo '<tr><th class="'.$classe.'">'.$row['secteur'].'</th></tr>'; $secteur = $row['secteur'];}
	?>
			<tr>
				<td class="<?php echo $classe; ?>"><?php echo $row['idwebclient']; ?></td>
				<td class="<?php echo $classe; ?>"><?php echo $row['societe']; ?></td>
				<td class="<?php echo $classe; ?>"><?php echo $row['cnom']; ?></td>
				<td class="<?php echo $classe; ?>"><?php echo $row['tel']; ?></td>
				<td class="<?php echo $classe; ?>"><?php echo $row['fax']; ?></td>
				<td class="<?php echo $classe; ?>"><?php echo $row['email']; ?></td>
				<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=webshow'.$row['secteur'].'&idwebclient='.$row['idwebclient'];?>"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
			</tr>
	<?php 
	$count++;
	} ?>
		</table>
	</fieldset>
<?php # echo $quid;?><br>	
<?php echo $count;?>	Results
</div>


</div>

