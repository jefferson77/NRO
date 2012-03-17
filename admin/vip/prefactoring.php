<link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8">
<style type="text/css" media="screen">
	table.fac { font-size: 10px; }
	th.factitre { font-size: 18px; }
	table.fac td.data { padding: 5px; }
</style>
<div id="centerzonelarge">
<?php
### Recherche des Jobs VIP en pre-facturation (etat = 15)

$prefjobs = $DB->getArray("SELECT
		j.idvipjob, j.reference, j.etat, j.idclient, j.idcofficer,
		c.societe,
		a.prenom
	FROM vipjob j
		LEFT JOIN client c ON j.idclient = c.idclient
		LEFT JOIN agent a ON j.idagent = a.idagent
	WHERE j.etat = '15'
	ORDER BY j.idvipjob");

echo '<h3>PRE Factoring des VIPs - '.count($prefjobs).' Jobs</h3>' ;

if (count($prefjobs) > 0) {
?>
	<div align="center">
		<form action="<?php echo NIVO ?>print/vip/facture/prefacture.php" method="post" target="popup" onsubmit="OpenBrWindow('about:blank','popup','scrollbars=yes,status=yes,resizable=yes','600','600','true')" >
			<input type="submit" class="printer_48">
		</form>
		<br><br>
		<form action="?act=prefactoring&action=job" method="post">
			<table class="rowstyle-alt fac" border="0" width="80%" cellspacing="1">
				<thead>
					<tr>
						<th>ID job</th>
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
					foreach ($prefjobs as $row) {
					?>
					<tr class="<?php echo (fmod($i, 2) == 1)?'alt':''; ?>">
						<td class="dataP"><b><?php echo $row['idvipjob']; ?></b></td>
						<td class="data"><?php echo $row['reference']; ?></td>
						<td class="data"><?php echo $row['idclient']; ?> - <?php echo $row['societe']; ?></td>
						<td class="data"><?php echo $row['prenom']; ?></td>
						<td class="jobtotF"><input type="radio" name="idj-<?php echo $row['idvipjob'] ;?>" value="16"></td>
						<td class="jobtotP"><input type="radio" name="idj-<?php echo $row['idvipjob'] ;?>" value="12"></td>
						<td class="jobtot"><input type="radio" name="idj-<?php echo $row['idvipjob'] ;?>" value="30"></td>
						<td class="data"><input type="radio" name="idj-<?php echo $row['idvipjob'] ;?>" value=""></td>
					</tr>
					<?php $i++;} ?>
				</tbody>
			</table>
			<br>
			<input type="submit" name="valider" value="Modifier">
			<br><br>
			<p align="left">* L'&eacute;tat StandBy ne sert qu'a 'd&eacute;cocher' un job en cas de cochage accidentel, il ne modifie en rien le job</p>
			<br>
		</form>
	</div>
<?php } else { ?>
<div align="center">Il n'y a aucun job VIP &agrave; facturer pour le moment</div>
<?php } ?>
</div>
