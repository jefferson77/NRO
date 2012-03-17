<?php #  Centre de recherche de Clients ?>
<div id="centerzonelarge">
<?php 

		$quid='WHERE 1 ';
		if ($sort == '') {$sort='l.datemodif DESC';}
		#if ($_SESSION['roger'] != 'devel') { $limitclient = ' LIMIT 5 '; }

		$recherche1='
			SELECT 
			l.datemodif,
			c.idclient, c.societe, 
			o.oprenom , o.onom, o.idcofficer
			FROM webneuro.clientlog l
			LEFT JOIN client c ON l.idclient = c.idclient
			LEFT JOIN cofficer o ON l.idcofficer = o.idcofficer
		';

		$recherche='
			'.$recherche1.'
			'.$quid.'
			 ORDER BY '.$sort.'
			 '.$limitclient.'
		';

	#echo $recherche;
	# Recherche des résultats
	$client = new db();
	$client->inline("$recherche;");
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
				<th class="<?php echo $classe; ?>">Date</th>
				<th class="<?php echo $classe; ?>">ID</th>
				<th class="<?php echo $classe; ?>">Soci&eacute;t&eacute;</th>
				<th class="<?php echo $classe; ?>">Nom</th>
				<th class="<?php echo $classe; ?>">Pr&eacute;nom</th>
				<th class="<?php echo $classe; ?>"></th>
			</tr>
	<?php 
		$zidcofficer[] = $idcofficer;
		while ($row = mysql_fetch_array($client->result)) { 
			$idcofficer = $row['idcofficer'];
			if (in_array ($idcofficer, $zidcofficer)) {
			} else {
			?>
			<tr>
				<td class="<?php echo $classe; ?>"><?php echo fdatetime($row['datemodif']); ?></td>
				<td class="<?php echo $classe; ?>"><?php echo $row['idclient']; ?></td>
				<td class="<?php echo $classe; ?>"><?php echo $row['societe']; ?></td>
				<td class="<?php echo $classe; ?>"><?php echo $row['onom']; ?></td>
				<td class="<?php echo $classe; ?>"><?php echo $row['oprenom']; ?></td>
				<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=show&idclient='.$row['idclient'];?>"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
			</tr>
	<?php 
			$of++;
			$idcofficer = $row['idcofficer'];
			$zidcofficer[] = $idcofficer;
			}
	$count++;
	} ?>
		</table>
	</fieldset>
<?php # echo $quid;?><br>	
<?php echo $of.' / '.$count;?>	Results
</div>


</div>

