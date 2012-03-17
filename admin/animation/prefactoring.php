<link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8">
<style type="text/css" media="screen">
	table.fac { font-size: 10px; }
	th.factitre { font-size: 18px; }
	table.fac td.data { padding: 5px; }
</style>
<div id="centerzonelarge">
<?php
### Recherche des Jobs ANIM en pre-facturation (etat = 5)
$sql = "SELECT

YEAR(an.datem) as annee, an.weekm, an.datem, an.idanimation,
j.idclient, j.boncommande, j.idcofficer,
c.facturation, c.factureofficer, c.societe,
a.prenom

FROM animation an
LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
LEFT JOIN agent a ON j.idagent = a.idagent
LEFT JOIN client c ON j.idclient = c.idclient
LEFT JOIN cofficer co ON j.idcofficer = co.idcofficer

WHERE an.facturation = 5";

## Sem / BC
$infosfac = $DB->getArray($sql);
foreach($infosfac as $row) {
	switch ($row['facturation'].$row['factureofficer']) {
		case"12":
			$lakey = 'SB//'.$row['annee'].'//'.$row['weekm'].'//'.$row['idclient'].'//'.$row['boncommande'];
			$tabl[$lakey]['ids'] .= '-'.$row['idanimation'];
			$tabl[$lakey]['reference'] = 'PO '.$row['boncommande'].' - Semaine '.$row['weekm'].' '.$row['annee'];
		break;
		case"11":
			$lakey = 'SO//'.$row['annee'].'//'.$row['weekm'].'//'.$row['idclient'].'//'.$row['idcofficer'];
			$tabl[$lakey]['ids'] .= '-'.$row['idanimation'];
			$tabl[$lakey]['reference'] = 'Semaine '.$row['weekm'].' '.$row['annee'];
		break;
		case"32":
			$lakey = 'MB//'.$row['annee'].'//'.date("m", strtotime($row['datem'])).'//'.$row['idclient'].'//'.$row['boncommande'];
			$tabl[$lakey]['ids'] .= '-'.$row['idanimation'];
			$tabl[$lakey]['reference'] = 'PO '.$row['boncommande'].'Mois '.date("m", strtotime($row['datem'])).' '.$row['annee'];
		break;
		case"31":
			$lakey = 'MO//'.$row['annee'].'//'.date("m", strtotime($row['datem'])).'//'.$row['idclient'].'//'.$row['idcofficer'];
			$tabl[$lakey]['ids'] .= '-'.$row['idanimation'];
			$tabl[$lakey]['reference'] =  'Mois '.date("m", strtotime($row['datem'])).' '.$row['annee'];
		break;
		default:
			$lakey = 'AU//'.$row['annee'].'//'.$row['weekm'].'//'.$row['idclient'].'//'.$row['idcofficer'];
			$tabl[$lakey]['ids'] .= '-'.$row['idanimation'];
			$tabl[$lakey]['reference'] = 'Semaine '.$row['weekm'].' '.$row['annee'];
	}
		$tabl[$lakey]['client'] = $row['societe'].'('.$row['idclient'].')';
		$tabl[$lakey]['planner'] = $row['prenom'];
}

echo '<h3>PRE Factoring des ANIMS - '.count($tabl).' Prefactures</h3>' ;

if (count($tabl) > 0) {
?>
	<div align="center">
		<form action="<?php echo NIVO ?>print/anim/facture/prefacture.php" method="post" target="popup" onsubmit="OpenBrWindow('about:blank','popup','scrollbars=yes,status=yes,resizable=yes','600','600','true')" >
			<input type="submit" class="printer_48">
			<input type="hidden" name="action" value="<?php echo $sql; ?>">
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
			foreach ($tabl as $key => $value) { ?>
					<tr class="<?php echo (fmod($i, 2) == 1)?'alt':''; ?>">
						<td class="data"><?php echo $value['reference']; ?></td>
						<td class="data"><?php echo $value['client']; ?></td>
						<td class="data"><?php echo $value['planner']; ?></td>
						<td class="jobtotF"><input type="radio" name="ids<?php echo $value['ids'] ;?>" value="6"></td>
						<td class="jobtotP"><input type="radio" name="ids<?php echo $value['ids'] ;?>" value="3"></td>
						<td class="jobtot"><input type="radio" name="ids<?php echo $value['ids'] ;?>" value="97"></td>
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
<div align="center">Il n'y a aucun job ANIM &agrave; facturer pour le moment</div>
<?php } ?>
</div>
