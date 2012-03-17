<link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8">
<style type="text/css" media="screen">
	table.fac { font-size: 10px; }
	th.factitre { font-size: 18px; }
	table.fac td.data { padding: 5px; }
</style>
<div id="centerzonelarge">
	<h3>Factoring des VIPs</h3>
	<b>FACTURES A IMPRIMER</b><br>
	<div align="center">
<?php
### VIp READY en 16 (prêt pour impression)
$misfacs = $DB->getArray("SELECT
		j.idvipjob, j.reference, j.idclient,
		count(m.idvip) AS nbrmission,
		c.societe,
		a.prenom
	FROM vipjob j
		LEFT JOIN vipmission m ON j.idvipjob = m.idvipjob
		LEFT JOIN client c ON j.idclient = c.idclient
		LEFT JOIN agent a ON j.idagent = a.idagent
	WHERE j.etat = 16 GROUP BY j.idvipjob");


if (count($misfacs) > '0') { ?>
		<form action="<?php echo NIVO ?>print/vip/facture/facture.php" method="post" target="popup" onsubmit="OpenBrWindow('about:blank','popup','scrollbars=yes,status=yes,resizable=yes','600','600','true')" >
			<input type="submit" class="printer_48"><br> Imprimer
		</form>
		<br><br>
		<table class="rowstyle-alt fac" border="0" width="80%" cellspacing="1">
			<thead>
				<tr>
					<th>ID job</th>
					<th>Reference</th>
					<th>Client</th>
					<th>Planner</th>
					<th>Missions</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($misfacs as $row) { ?>
				<tr>
					<td class="dataP"><b><?php echo $row['idvipjob']; ?></b></td>
					<td class="data"><?php echo $row['reference']; ?></td>
					<td class="data"><?php echo $row['idclient']; ?> - <?php echo $row['societe']; ?></td>
					<td class="data"><?php echo $row['prenom']; ?></td>
					<td class="data"><?php echo $row['nbrmission']; ?></td>
				</tr>
<?php } ?>
			</tbody>
		</table>
<?php } else { ?>
	<p>Aucune Facture a Créer</p>
<?php } ?>
	</div>
</div>
