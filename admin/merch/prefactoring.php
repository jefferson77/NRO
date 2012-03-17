<link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8">
<style type="text/css" media="screen">
	table.fac { font-size: 10px; }
	th.factitre { font-size: 18px; }
	table.fac td.data { padding: 5px; }
</style>
<div id="centerzonelarge">
<?php
### Recherche des Jobs MERCH en pre-facturation (etat = 5)
$rows = $DB->getArray("SELECT
		YEAR(me.datem) as annee, me.weekm, me.datem, me.idmerch, me.genre, me.idshop,
		me.idclient, me.boncommande, me.idcofficer,
		s.societe as ssociete,
		c.facturation, c.factureofficer, c.societe,
		a.prenom
	FROM merch me
		LEFT JOIN agent a ON me.idagent = a.idagent
		LEFT JOIN client c ON me.idclient = c.idclient
		LEFT JOIN shop s ON me.idshop = s.idshop
		LEFT JOIN cofficer co ON me.idcofficer = co.idcofficer
	WHERE me.facturation = 5 AND (me.facnum IS NULL OR me.facnum = 0)");

foreach ($rows as $row) {
	if ($row['genre'] == 'EAS') {
		if (in_array($row['idclient'], $gbclients)) {
			$lakey = 'EASGB//'.$row['annee'].'//'.date("m", strtotime($row['datem']));
			$tabl[$lakey]['reference'] =  'EAS GB '.date("m", strtotime($row['datem'])).' '.$row['annee'];
			$tabl[$lakey]['client'] = 'GB EAS (2651)';
		} else {
			$lakey = 'EAS//'.$row['annee'].'//'.date("m", strtotime($row['datem'])).'//'.$row['idclient'].'//'.$row['idshop'];
			$tabl[$lakey]['reference'] =  'EAS '.date("m", strtotime($row['datem'])).' '.$row['annee'].' '.$row['ssociete'];
			$tabl[$lakey]['client'] = $row['societe'].'('.$row['idclient'].')';
		}
	} else {
		switch ($row['facturation'].$row['factureofficer']) {
			case"12":
				$lakey = 'SB//'.$row['annee'].'//'.$row['weekm'].'//'.$row['idclient'].'//'.$row['boncommande'];
				$tabl[$lakey]['reference'] = 'PO '.$row['boncommande'].' - Semaine '.$row['weekm'].' '.$row['annee'];
			break;
			case"11":
				$lakey = 'SO//'.$row['annee'].'//'.$row['weekm'].'//'.$row['idclient'].'//'.$row['idcofficer'];
				$tabl[$lakey]['reference'] = 'Semaine '.$row['weekm'].' '.$row['annee'];
			break;
			case"32":
				$lakey = 'MB//'.$row['annee'].'//'.date("m", strtotime($row['datem'])).'//'.$row['idclient'].'//'.$row['boncommande'];
				$tabl[$lakey]['reference'] = 'PO '.$row['boncommande'].'Mois '.date("m", strtotime($row['datem'])).' '.$row['annee'];
			break;
			case"31":
				$lakey = 'MO//'.$row['annee'].'//'.date("m", strtotime($row['datem'])).'//'.$row['idclient'].'//'.$row['idcofficer'];
				$tabl[$lakey]['reference'] =  'Mois '.date("m", strtotime($row['datem'])).' '.$row['annee'];
			break;
			default:
				$lakey = 'AU//'.$row['annee'].'//'.$row['weekm'].'//'.$row['idclient'].'//'.$row['idcofficer'];
				$tabl[$lakey]['reference'] = 'Semaine '.$row['weekm'].' '.$row['annee'];
		}
		$tabl[$lakey]['client'] = $row['societe'].'('.$row['idclient'].')';
	}

	$tabl[$lakey]['ids'] .= '-'.$row['idmerch'];
	$tabl[$lakey]['planner'] = $row['prenom'];
}

echo '<h3>PRE Factoring des MERCHS - '.count($tabl).' Prefactures</h3>' ;

if (count($tabl) > 0) {
?>
	<div align="center">
		<form action="<?php echo NIVO ?>print/merch/facture/prefacture.php" method="post" target="popup" onsubmit="OpenBrWindow('about:blank','popup','scrollbars=yes,status=yes,resizable=yes','600','600','true')" >
			<input type="submit" class="printer_48">
		</form>
	<br><br>
	<form action="?act=prefac" method="post">
	<table class="rowstyle-alt fac" border="0" width="80%" cellspacing="1">
		<thead>
			<tr>
				<th>Reference</th>
				<th>Client</th>
				<th>Planner</th>
				<th class="D" width="6%">Goto<br>Fact.</th>
				<th class="D" width="6%">Back<br>Planner</th>
				<th class="D" width="6%">OUT</th>
				<th class="D" width="6%">Stand<br>By*</th>
			</tr>
		</thead>
		<tbody>
<?php
$i = 0;
foreach ($tabl as $key => $value) {
?>
		<tr class="<?php echo (fmod($i, 2) == 1)?'alt':''; ?>">
			<td class="data"><?php echo $value['reference']; ?></td>
			<td class="data"><?php echo $value['client']; ?></td>
			<td class="data"><?php echo $value['planner']; ?></td>
			<td class="jobtotF"><?php if (substr($key, 0, 5) != 'EASGB') echo '<input type="radio" name="ids'.$value['ids'].'" value="6">'; ?></td>
			<td class="jobtotP"><input type="radio" name="ids<?php echo $value['ids'] ;?>" value="3"></td>
			<td class="jobtot"><input type="radio" name="ids<?php echo $value['ids'] ;?>" value="25"></td>
			<td class="data"><input type="radio" name="ids<?php echo $value['ids'] ;?>" value=""></td>
		</tr>
<?php $i++;} ?>
		</tbody>
	</table>
	<br>
	<input type="submit" name="valider" value="Modifier">

	</div>
	<br><br>
	* L'&eacute;tat StandBy ne sert qu'a 'd&eacute;cocher' un job en cas de cochage accidentel, il ne modifie en rien le job
	<br>
	</form>
	<br>
<?php } else { ?>
<div align="center">Il n'y a aucun job MERCH &agrave; facturer pour le moment</div>
<?php } ?>
</div>
