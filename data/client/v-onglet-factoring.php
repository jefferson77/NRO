<?php
include NIVO.'classes/anim.php';
include NIVO.'classes/vip.php';
include NIVO.'classes/merch.php';
include NIVO.'classes/facture.php';
?>
<table border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
	<tr>
		<td width="50%">
			<table class="sortable rowstyle-alt no-arrow" border="0" width="60%" cellspacing="1" align="center">
				<thead>
					<tr>
						<th class="sortable-numeric">Fact</th>
						<th class="sortable-date-dmy">Date</th>
						<th class="sortable-numeric">Montant</th>
					</tr>
				</thead>
				<tbody>
			<?php
			# Recherche des factures de ce client
			$rows = $DB->getArray("SELECT idfac, secteur, datefac FROM `facture` WHERE `idclient` = ".$_REQUEST['idclient']." ORDER BY idfac DESC");
			
			foreach ($rows as $row) {
				$fac = new facture($row['idfac']);
				switch ($row['secteur']) {
					case "1": $sec = "VI"; break;
					case "2": $sec = "AN"; break;
					case "3": $sec = "ME"; break;
					case "4": $sec = "EA"; break;
				}
			?>
				<tr>
					<td><?php echo $sec." ".$row['idfac']; ?></td>
					<td><?php echo fdate($row['datefac']); ?></td>
					<td align="right"><?php echo feuro($fac->MontTTC); ?></td>
				</tr>
			<?php 
				### POUR TABLEAU ANNUEL
					$year = substr($row['datefac'], 0, 4);
					$yearcount = 'y'.$year; # total par ann�e
					$$yearcount += $fac->MontTTC; # total par ann�e
					$yearsecteur = $sec.$year; # total par ann�e / secteur
					$$yearsecteur += $fac->MontTTC; # total par ann�e / secteur
					if (!strchr($years, $year)) { $years .= $year."-sep-"; } # toutes les ann�es de la liste
			} ?>
				</tbody>	
			</table>
		</td>
		<td valign="top">
			<table class="sortable rowstyle-alt no-arrow" border="0" width="60%" cellspacing="1" align="center">
				<thead>
					<tr>
						<th class="sortable-numeric">Ann&eacute;e</th>
						<th class="sortable-numeric">Total</th>
						<th class="sortable-numeric">Vip</th>
						<th class="sortable-numeric">Anim</th>
						<th class="sortable-numeric">Merch</th>
						<th class="sortable-numeric">Eas</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$f = explode("-sep-", $years);
					foreach ($f as $val) {
						if ($val > 0) {
							$yearcount = 'y'.$val; # total de ann�e
							$yearvip = 'VI'.$val; # total de ann�e / VIP
							$yearanim = 'AN'.$val; # total de ann�e / ANIM
							$yearmerch = 'ME'.$val; # total de ann�e / MERCH
							$yeareas = 'EA'.$val; # total de ann�e / EAS
						?>					
							<tr>
								<td><b><?php echo $val; ?></b></td>
								<td align="right"><b><?php echo feuro($$yearcount); ?><b></td>
								<td align="right"><?php echo feuro($$yearvip); ?></td>
								<td align="right"><?php echo feuro($$yearanim); ?></td>
								<td align="right"><?php echo feuro($$yearmerch); ?></td>
								<td align="right"><?php echo feuro($$yeareas); ?></td>
							</tr>
						<?php
						}
					}
					?>
				</tbody>	
			</table>
		</td>
	</tr>
</table>