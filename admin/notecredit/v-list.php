<div id="centerzonelarge">
	<form action="?act=print&mde=multi" method="post">
		<table class="sortable-onload-0r rowstyle-alt paginate-30 no-arrow" border="0" width="90%" cellspacing="1" align="center">
			<thead>
				<tr>
					<th class="sortable-numeric">N&deg; NC</th>
					<th class="sortable-text">Intitule</th>
					<th class="sortable-numeric">Fac Ref</th>
					<th class="sortable-text">Client</th>
					<th class="sortable-date-dmy">Date</th>
					<th class="sortable-numeric">Units</th>
					<th class="sortable-numeric">Montant</th>
					<th><input type="submit" class="btn printer"></th>
				</tr>
				<tr>
					<th colspan="8">
						En-t&ecirc;te : <input name="ent" type="checkbox" value="entete"> &nbsp; &nbsp;
						Duplicata : <input name="dup" type="checkbox" value="duplicata">
					</th>
				</tr>
			</thead>
			<tbody>
		<?php
		$mtot = 0;
		foreach ($rows as $row) {

			## switch secteur
			switch ($row['secteur']) {
				case "1":
					$secn = 'bull-red.png';
				break;
				case "2":
					$secn = 'bull-green.png';
				break;
				case "3":
					$secn = 'bull-blue.png';
				break;
				case "4":
					$secn = 'bull-orange.png';
				break;
			}

			echo '
			<tr align="right" ondblclick="location.href=\''.$_SERVER['PHP_SELF'].'?act=detail&idfac='.$row['idfac'].'&skip='.$from.'\'">
				<td align="center"><b>'.$row['idfac'].'</b></td>
				<td align="left"><img src="'.STATIK.'illus/'.$secn.'" alt="search" width="10" height="10" border="0">&nbsp;'.$row['intitule'].'</td>
				<td align="center">'.$row['facref'].'</td>
				<td align="left">'.$row['societe'].' ('.$row['idclient'].')</td>
				<td>'.fdate($row['datefac']).'</td>
				<td>'.fnbr0($row['units']).'</td>
				<td>'.feuro($row['montant']).'</td>
				<td width="30" align="center"><input type="checkbox" name="print[]" value="'.$row['idfac'].'"></td>
			</tr>';

			$mtot += $row['montant'];

		} ?>
			</tbody>
			<tfood>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th align="right"><?php echo feuro($mtot) ?></th>
				<th></th>
			</tfood>
		</table>
	</form>
</div>