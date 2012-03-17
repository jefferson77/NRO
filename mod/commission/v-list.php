<?php 

setlocale(LC_TIME, 'fr_FR');

include 'v-leftmenu.php'; 

$periodstart = date("Y-m-01", strtotime($activeyear.'-'.$activemonth.'-01'));
$periodend = date("Y-m-t", strtotime($activeyear.'-'.$activemonth.'-01'));

### get commissions
$commissions = $DB->getArray("SELECT
		co.idcommission, co.idsupplier,
		su.societe
	FROM commissions co 
		LEFT JOIN supplier su ON co.idsupplier = su.idsupplier
	WHERE co.datein BETWEEN '".$periodstart."' AND '".$periodend."' 
		OR co.dateout BETWEEN '".$periodstart."' AND '".$periodend."' 
		OR (co.datein <= '".$periodstart."' AND co.dateout >= '".$periodend."')");

foreach ($commissions as $commission) {
	if (!is_array($tabl[$commission['idsupplier']])) $tabl[$commission['idsupplier']] = array();
	$comdue = commissionDue($commission['idcommission'], $periodstart, $periodend);
	if(count($comdue) > 0) $tabl[$commission['idsupplier']] = array_merge($tabl[$commission['idsupplier']], $comdue);
	$supinfos[$commission['idsupplier']]['societe'] = $commission['societe'];
}

# init totaux
$fourntot=0;
$grantot=0;
?>
<link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8">
<div id="centerzone">
	<br>
<?php foreach ($tabl as $supplier => $listcoms): ?>
		<table class="sortable-onload-1 no-arrow rowstyle-alt fac" border="0" width="90%" cellspacing="1" cellpadding="1" align="center">
			<thead>
				<tr>
					<td colspan="5"><?php echo $supinfos[$supplier]['societe'] ?></td>
					<td colspan="2"><?php echo ucwords(utf8_encode(strftime("%B %Y", strtotime($periodstart)))) ?></td>
				</tr>
				<tr>
					<th class="sortable-text">Secteur</th>
					<th class="sortable-text">Job</th>
					<th class="sortable-text">Reference</th>
					<th class="sortable-text">Heures</th>
					<th class="sortable-text">Com</th>
					<th class="sortable-text">Manuel</th>
					<th class="sortable-text">Total</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($listcoms as $row): ?>
				<tr>
					<td><?php echo $row['secteur'] ?></td>
					<td><?php echo $row['idjob'] ?></td>
					<td><?php echo $row['reference'] ?></td>
					<td><?php echo fnbr0($row['heures']) ?></td>
					<td><?php echo feuro($row['com']) ?></td>
					<td><?php echo $row[''] ?></td>
					<td><?php echo feuro($row['total']);$fourntot += $row['total'] ?></td>
				</tr>
			<?php endforeach ?>
 			</tbody>
			<tfoot>
				<tr>
					<td class="jobtot" colspan="4"></td>
					<td class="jobtot">Total</td>
					<td class="jobtot"></td>
					<td class="jobtot"><?php echo feuro($fourntot); $grantot += $fourntot; $fourntot=0 ?></td>
				</tr>
			</tfoot>
		</table>
		<br>
<?php endforeach ?>
	Total des commissions pour le mois : <?php echo feuro($grantot) ?>
</div>
