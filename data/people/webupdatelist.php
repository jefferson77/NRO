<?php 
$rows = $DB->getArray("
	SELECT codepeople, cp1, cp2, email, err, gsm, idpeople, len, lfr, lnl, pnom, pprenom, sexe, ville1, ville2
	FROM webneuro.webpeople
	WHERE webetat >= 4 AND webtype = 1
	GROUP BY idpeople
	ORDER BY idpeople DESC");
?>
<div id="centerzonelarge">
	<fieldset>
		<legend>
			Recherche des People Web update : <?php echo ' ('.count($rows).' Results)'; ?>
		</legend>
		<table class="sortable-onload-3 paginate-25 rowstyle-alt no-arrow" border="0" width="99%" cellspacing="1" align="center">
			<thead>
				<tr>
					<th></th>
					<th class="sortable-numeric">Code</th>
					<th class="sortable-numeric">ID</th>
					<th class="sortable-text">Nom</th>
					<th class="sortable-text">Pr&eacute;nom</th>
					<th class="sortable-text">Sexe</th>
					<th class="sortable-numeric">Fr</th>
					<th class="sortable-numeric">NL</th>
					<th class="sortable-numeric">En</th>
					<th class="sortable-numeric">CP</th>
					<th class="sortable-text">Ville</th>
					<th class="sortable-numeric">CP2</th>
					<th class="sortable-text">Ville2</th>
					<th class="sortable-text">GSM</th>
					<th class="sortable-text">mail</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($rows as $row) {
	if ($row['err'] == 'Y') {
		$trargs = ' style="background-color: #F4D25C;"';
		$tdargs = '<img src="'.STATIK.'illus/attention.gif" alt="attention.gif" width="14" height="14" align="right">';
	}
?>				
				<tr ondblclick="location.href='<?php echo $_SERVER['PHP_SELF'].'?act=webupdate1&idpeople='.$row['idpeople'];?>'" <?php echo $trargs ?>>
					<td><?php echo $tdargs ?></td>
					<td><?php echo $row['codepeople']; ?></td>
					<td><?php echo $row['idpeople']; ?></td>
					<td><?php echo $row['pnom']; ?></td>
					<td><?php echo $row['pprenom']; ?></td>
					<td><?php echo $row['sexe']; ?></td>
					<td><?php echo $row['lfr']; ?></td>
					<td><?php echo $row['lnl']; ?></td>
					<td><?php echo $row['len']; ?></td>
					<td><?php echo $row['cp1']; ?></td>
					<td><?php echo $row['ville1']; ?></td>
					<td><?php echo $row['cp2']; ?></td>
					<td><?php echo $row['ville2']; ?></td>
					<td><?php echo $row['gsm']; ?></td>
					<td><a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a></td>
				</tr>
	<?php } ?>
			</tbody>
		</table>
	</fieldset>
</div>
