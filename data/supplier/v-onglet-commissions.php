<?php if (!empty($_REQUEST['idsupplier'])) {
	$commissions = $DB->getArray("SELECT
			k.*,
			c.societe, c.tvheure05, c.taheure, c.tmheure
		FROM commissions k
			LEFT JOIN client c ON k.idclient = c.idclient
		WHERE idsupplier = ".$_REQUEST['idsupplier']);
} ?>
<style type="text/css" media="screen">
	.rightnote {
		float: right;
		width: 150px;
		padding: 10px;
		margin: 10px;
		background-color: #BABBC5;
		text-align: justify;
	}
</style>
<div id="orangepeople">
	<h2>Commissions</h2>
	<div class="rightnote">
		Les commissions reprises ici servent de valeur de base et sont modifiables par job. Les commissions sont totalisées en fin de mois et perçues sur chaque heure facturée au client donné.
	</div>
	<table class="sortable rowstyle-alt no-arrow" border="0" cellspacing="1" align="center">
		<thead>
			<tr>
				<th>Client</th>
				<th>Du</th>
				<th>Au</th>
				<th width="45">VI</th>
				<th width="45">AN</th>
				<th width="45">ME</th>
				<th>Montant €/h</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
	<?php
	foreach ($commissions as $row)
	{
		$idcoms[] = $row['idcommission'];

		echo '<tr>
				<form action="'.$_SERVER['PHP_SELF'].'" method="post" accept-charset="utf-8" name="Comm'.$row['idcommission'].'">
					<td>
						<input type="hidden" name="idsupplier" value="'.$_REQUEST['idsupplier'].'">
						<input type="hidden" name="idcommission" value="'.$row['idcommission'].'">
						<input type="hidden" name="idclient" value="'.$row['idclient'].'">
						<input type="text" name="nomclient" value="'.$row['societe'].'" id="nomclient'.$row['idcommission'].'">
					</td>
					<td><input type="text" name="datein" value="'.fdate($row['datein']).'"></td>
					<td><input type="text" name="dateout" value="'.fdate($row['dateout']).'"></td>
					<td>'.feuro($row['tvheure05']).'</td>
					<td>'.feuro($row['taheure']).'</td>
					<td>'.feuro($row['tmheure']).'</td>
					<td><input type="text" name="montant" value="'.fnbr($row['montant']).'"></td>
					<td>
						<input type="submit" class="btn tick_circle" name="act" value="commissionModif">
						<input type="submit" class="btn minus_circle" name="act" value="commissionDelete">
					</td>
				</form>
			</tr>';
	} ?>
		</tbody>
		<tfoot>
			<tr>
				<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" accept-charset="utf-8" name="Comm">
					<td>
						<input type="hidden" name="idsupplier" value="<?php echo $_REQUEST['idsupplier'] ?>">
						<input type="hidden" name="idclient" value="">
						<input type="text" name="nomclient" value="" id="nomclient">
					</td>
					<td><input type="text" name="datein" value=""></td>
					<td><input type="text" name="dateout" value=""></td>
					<td></td>
					<td></td>
					<td></td>
					<td><input type="text" name="montant" value=""></td>
					<td><input type="submit" class="btn plus_circle" name="act" value="commissionAdd"></td>
				</form>
			</tr>
		</tfoot>
	</table>
</div>
<script type="text/javascript" charset="utf-8">
	function formatResult(row) {return row[0];}
	function formatItem(row) {return row[1];}


	$(document).ready(function() {

<?php foreach ($idcoms as $idcom) { ?>

		$("input#nomclient<?php echo $idcom ?>").autocomplete("<?php echo Conf::read('Env.urlroot') ?>query/client.php", {
			inputClass: 'autocomp',
			width: 250,
			minChars: 2,
			formatItem: formatItem,
			formatResult: formatResult,
			delay: 200
		});

		$("input#nomclient<?php echo $idcom ?>").result(function(data, row) {
			if (data) document.Comm<?php echo $idcom ?>.idclient.value = row[0];
			if (data) document.Comm<?php echo $idcom ?>.nomclient.value = row[1];
		});

<?php } ?>

		$("input#nomclient").autocomplete("<?php echo Conf::read('Env.urlroot') ?>query/client.php", {
			inputClass: 'autocomp',
			width: 250,
			minChars: 2,
			formatItem: formatItem,
			formatResult: formatResult,
			delay: 200
		});

		$("input#nomclient").result(function(data, row) {
			if (data) document.Comm.idclient.value = row[0];
			if (data) document.Comm.nomclient.value = row[1];
		});
	});
</script>