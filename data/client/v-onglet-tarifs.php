<form action="?act=modinfos" method="post">
	<input type="hidden" name="s" value="<?php echo $_REQUEST['s'];?>"> 
	<input type="hidden" name="idclient" value="<?php echo $_REQUEST['idclient']?>"> 
	<table class="standard" border="0" cellspacing="0" cellpadding="0" align="center" width="98%">
		<tr>
			<td valign="middle">
				<table border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<td>
							<b>P&eacute;riode de Facturation</b>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo '<input type="radio" name="facturation" value="1" '; if (($infos['facturation'] == '1') or ($infos['facturation'] == '')) { echo 'checked';} echo'> Par Semaine';?><br>
							<?php echo '<input type="radio" name="facturation" value="3" '; if ($infos['facturation'] == '3') { echo 'checked';} echo'> Par mois';?>
						</td>
					</tr>
					<tr>
						<td>
							<b>Mode de Facturation</b>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo '<input type="radio" name="factureofficer" value="1" '; if ($infos['factureofficer'] == '1') { echo 'checked';} echo'> Contact';?><br>
							<?php echo '<input type="radio" name="factureofficer" value="2" '; if (($infos['factureofficer'] == '') OR ($infos['factureofficer'] == '2')) { echo 'checked';} echo'> Bon Commande (PO)';?>
						</td>
					</tr>
					<tr>
						<td>
							<b>Facturation Forfaitaire</b>
						</td>
					</tr>
					<tr>
						<td>
							<?php 
							echo '<input type="radio" name="hforfait" value="2" '; if ($infos['hforfait'] == '2') { echo 'checked';} echo'> Oui';
							echo ' &nbsp; ';
							echo '<input type="radio" name="hforfait" value="1" '; if (empty($infos['hforfait']) OR ($infos['hforfait'] == '1')) { echo 'checked';} echo'> Non';
							?>
						</td>
					</tr>
					<tr>
						<td>
							<b>Frais Dimona</b>
						</td>
					</tr>
					<tr>
						<td>
							<?php 
							echo '<input type="radio" name="fraisdimona" value="oui" '; if ($infos['fraisdimona'] == 'oui') { echo 'checked';} echo'> Oui';
							echo ' &nbsp; ';
							echo '<input type="radio" name="fraisdimona" value="non" '; if ($infos['fraisdimona'] == 'non') { echo 'checked';} echo'> Non';
							?>
						</td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td colspan="2"><b>Animation</b></td>
					</tr>
					<tr>
						<td>Heure</td>
						<td><input type="text" size="10" name="taheure" value="<?php echo fnbr($infos['taheure']); ?>"> &euro;</td>
					</tr>
					<tr>
						<td>KM</td>
						<td><input type="text" size="10" name="takm" value="<?php echo fnbr($infos['takm']); ?>"> &euro;</td>
					</tr>
					<tr>
						<td>Forfait</td>
						<td><input type="text" size="10" name="taforfait" value="<?php echo fnbr($infos['taforfait']); ?>"> &euro;</td>
					</tr>
					<tr>
						<td>Forfait km</td>
						<td><input type="text" size="10" name="taforfaitkm" value="<?php echo fnbr($infos['taforfaitkm']); ?>"> Km</td>
					</tr>
					<tr>
						<td>Stand</td>
						<td><input type="text" size="10" name="tastand" value="<?php echo fnbr($infos['tastand']); ?>"> &euro;</td>
					</tr>
					<tr>
						<td>Briefing</td>
						<td><input type="text" size="10" name="tabriefing" value="<?php echo fnbr($infos['tabriefing']); ?>"> &euro;</td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td colspan="2"><b>Merchandising</b></td>
					</tr>
					<tr>
						<td>Heure</td>
						<td><input type="text" size="10" name="tmheure" value="<?php echo fnbr($infos['tmheure']); ?>"> &euro;</td>
					</tr>
					<tr>
						<td>H Sup (150%)</td>
						<td><input type="text" size="10" name="tm150" value="<?php echo fnbr($infos['tm150']); ?>"> &euro;</td>
					</tr>
					<tr>
						<td>KM</td>
						<td><input type="text" size="10" name="tmkm" value="<?php echo fnbr($infos['tmkm']); ?>"> &euro;</td>
					</tr>
					<tr>
						<td>Forfait</td>
						<td><input type="text" size="10" name="tmforfait" value="<?php echo fnbr($infos['tmforfait']); ?>"> &euro; (Non actif)</td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td colspan="2"><b>VIP</b></td>
					</tr>
					<tr>
						<td>Heure 0-5</td>
						<td><input type="text" size="10" name="tvheure05" value="<?php echo fnbr($infos['tvheure05']); ?>"> &euro;</td>
					</tr>
					<tr>
						<td>Heure 6+</td>
						<td><input type="text" size="10" name="tvheure6" value="<?php echo fnbr($infos['tvheure6']); ?>"> &euro;</td>
					</tr>
					<tr>
						<td>H Night</td>
						<td><input type="text" size="10" name="tvnight" value="<?php echo fnbr($infos['tvnight']); ?>"> &euro;</td>
					</tr>
					<tr>
						<td>H Sup (150%)</td>
						<td><input type="text" size="10" name="tv150" value="<?php echo fnbr($infos['tv150']); ?>"> &euro;</td>
					</tr>
					<tr>
						<td>KM</td>
						<td><input type="text" size="10" name="tvkm" value="<?php echo fnbr($infos['tvkm']); ?>"> &euro;</td>
					</tr>
					<tr>
						<td>Forfait</td>
						<td><input type="text" size="10" name="tvforfait" value="<?php echo fnbr($infos['tvforfait']); ?>"> &euro;</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<div align="center"><input type="submit" name="bouton" value="Modifier"></div>
</form>