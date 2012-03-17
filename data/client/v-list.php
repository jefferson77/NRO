<?php
if ((empty($_SESSION['clientquid'])) and ($_SESSION['clientquid'] != 1)) {
	$_SESSION['clientquid'] = "1";
	$order = "c.idclient DESC";
} else {
	$order = "c.societe";
}

$DB->inline('SELECT 
			c.cnom, c.codecompta, c.email, c.etat, c.fax, c.idclient, c.societe, c.tel
		FROM client c
			LEFT JOIN cofficer co ON c.idclient = co.idclient
		WHERE '.$_SESSION['clientquid'].'
		GROUP BY c.idclient
		ORDER BY '.$order.'
		LIMIT 150');

$FoundCount = mysql_num_rows($DB->result);
?>
<div id="centerzonelarge">
	<h2>Recherche des CLIENTS : <?php echo $_SESSION['clientquod']; ?></h2>
	<br>
	<table id="" class="sortable-onload-2 paginate-25 rowstyle-alt no-arrow" border="0" width="98%" cellspacing="1" align="center">
		<thead>
			<tr>
				<th class="sortable-numeric">ID</th>
				<th class="sortable-text">Soci&eacute;t&eacute;</th>
				<th class="sortable-text">Nom</th>
				<th class="sortable-text">Tel</th>
				<th class="sortable-text">Fax</th>
				<th class="sortable-text">Email</th>
				<th class="sortable-numeric">Compta</th>
				<th class="sortable-text">Etat</th>
			</tr>
		</thead>
		<tbody>
<?php while ($row = mysql_fetch_array($DB->result)) { ?>
		<tr ondblclick="location.href='<?php echo $_SERVER['PHP_SELF'].'?act=show&idclient='.$row['idclient'];?>'">
			<td><?php echo $row['idclient']; ?></td>
			<td><b><?php echo $row['societe']; ?></b></td>
			<td><?php echo $row['cnom']; ?></td>
			<td><?php echo $row['tel']; ?></td>
			<td><?php echo $row['fax']; ?></td>
			<td><?php echo $row['email']; ?></td>
			<td><?php echo $row['codecompta']; ?></td>
			<td>
			<?php
			switch ($row['etat']) {
				case "0": 
					echo '<font color="red"> Out';
				break;
				case "5": 
					echo '<font color="green"> In';
				break;
			} 
			?>
				</font>
			</td>
		</tr>
<?php } ?>
		<tbody>
	</table>
<br>	
<?php echo $FoundCount;?>	Results
</div>