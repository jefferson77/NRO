<form action="<?php echo NIVO ?>admin/factures/adminfac.php?act=print&mde=multi" method="post" target="_top">
<table class="sortable-onload-0r paginate-35 rowstyle-alt no-arrow" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
	<thead>
	<tr align="center">
		<th class="sortable-numeric" align="left">N&deg; Fac</th>
		<th class="sortable-text" align="left">Intitule</th>
		<th class="sortable-text" align="left">PO</th>
		<th class="sortable-numeric">IDclient</th>
		<th class="sortable-numeric">Officer</th>
		<th class="sortable-text" align="left">Client</th>
		<th class="sortable-date-dmy">Date</th>
		<th class="sortable-currency">Montant</th>
		<th class="sortable-sortImage" width="16">P</th>
		<th align="center"></th>
	</tr>
	<tr align="center">
		<th colspan="9" align="right">
			En-t&ecirc;te : <input name="ent" type="checkbox" value="entete" checked> &nbsp; &nbsp;
			Duplicata : <input name="dup" type="checkbox" value="duplicata"> &nbsp; &nbsp;
 			Page de Garde : <input name="page[]" type="checkbox" value="garde" checked> &nbsp; &nbsp;
			Detail :<input name="page[]" type="checkbox" value="detail" checked> &nbsp; &nbsp;
		</th>
		<th><input type="submit" class="btn printer"></th>
	</tr>
	</thead>
	<tbody>
<?php
$mtot = 0;
while ($row = mysql_fetch_array($afficher->result)) {
	### calcul le montant et/ou l'intitulé si manquants
	if 	(
			($row['intitule'] == '') or
			($row['montant'] == '') or
			(($row['factureofficer'] == 2) and (empty($row['po'])))
		)
	{
		$fac = new facture($row['idfac']);
		$row['intitule'] = $fac->intitule;
		$row['montant'] = $fac->MontHTVA;
		$row['po'] = $fac->boncommande;
		$row['modefac'] = $fac->modefac;
	}

	## switch modefac
	switch ($row['modefac']) {
		case "M":
			$color = 'bgcolor="#FFF99D"';
		break;
		case "A":
			$color = '';
		break;
		Default :
			$color = '';
	}

	## switch secteur
	switch ($row['secteur']) {
		case "1":
			$secc = '#3300CC';
			$secn = 'bull-red.png';
		break;
		case "2":
			$secc = '#FF9900';
			$secn = 'bull-green.png';
		break;
		case "3":
			$secc = '#006600';
			$secn = 'bull-blue.png';
		break;
		case "4":
			$secc = '#ffc000';
			$secn = 'bull-orange.png';
		break;

		Default :
			$secc = '';
			$secn = '';
	}

	$clockillu = (strtotime(date("Y-m-d")) > strtotime($row['dateecheance']))?'clock_red':'clock';

	echo '
	<tr ondblclick="location.href=\''.$_SERVER['PHP_SELF'].'?act=detail&idfac='.$row['idfac'].'&skip='.$from.'\'">
		<td align="center"><b>'.$row['idfac'].'</b></td>
		<td align="left">'.$row['modefac'].' <img src="'.STATIK.'illus/'.$secn.'" alt="search" width="10" height="10" border="0">&nbsp;'.$row['intitule'].'</td>
		<td align="left">'.$row['po'].'</td>
		<td align="left">'.$row['idclient'].'</td>
		<td align="left">'.$row['idcofficer'].'</td>
		<td align="left">'.$row['societe'].'</td>
		<td align="center">'.fdate($row['datefac']).'</td>
		<td align="right">'.feuro($row['montant']).'</td>
		<td align="right" style="padding: 0px 1px;">'.(($row['montantdu'] > 0)?'<img src="'.STATIK.'illus/'.$clockillu.'.png" width="16" height="16" alt="Coins" title="'.$row['dateecheance'].'">':'').'</td>
		<td align="center"><input type="checkbox" name="print[]" value="'.$row['idfac'].'"></td>
	</tr>
';
$mtot += $row['montant'];

}
?>
</tbody>
<tfood>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th></th>
	<th align="right"><?php echo feuro($mtot) ?></th>
	<th></th>
	<th></th>
</tfood>
</table>
</form>
<img src="<?php echo STATIK ?>illus/clock_red.png" width="16" height="16" alt="Clock Red"> = Echu<br>
<img src="<?php echo STATIK ?>illus/clock.png" width="16" height="16" alt="Clock"> = Non Echu
