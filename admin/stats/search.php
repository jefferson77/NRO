<form action="?act=list" method="post">
<div id="infozone">
	<fieldset>
		<legend>
			Recherche
		</legend>
		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
			<tr>
				<td>Secteur</td>
				<td>
					<input type="checkbox" name="secteur[]" value="anim"> Anim - 
					<input type="checkbox" name="secteur[]" value="vip"> Vip - 
					<input type="checkbox" name="secteur[]" value="merch"> Merch - 
					<input type="checkbox" name="secteur[]" value="eas"> EAS
				</td>
			</tr>
					<tr>
						<td>
							Genre
						</td>
						<td>
							<select name="genre" size="1">
								<option value="" selected>Tout</option>
								<option value="Rack assistance">Rack assistance</option>
								<option value="Store Check">Store Check</option>
								<option value="EAS">EAS</option>
							</select>
						</td>
					</tr>
			<tr>
				<td>Date</td>
				<td><input type="text" size="30" name="date" value=""></td>
			</tr>
			<tr>
				<td>Job</td>
				<td><input type="text" size="30" name="job" value=""></td>
			</tr>
			<tr>
				<td>Client</td>
				<td>ID:<input type="text" size="5" name="cid" value=""> Societe:<input type="text" size="30" name="csociete" value=""></td>
			</tr>
			<tr>
				<td>Facture</td>
				<td> n&deg; <input type="text" size="30" name="facnum" value=""></td>
			</tr>
			<tr>
				<td>Semaine</td>
				<td><input type="text" size="20" name="semaine" value=""> Ann&eacute;e <input type="text" size="6" name="year" value=""> (PAS EN VIP)</td>
			</tr>
			<tr>
				<td>Planner</td>
				<td>
					<select name="idagent">
						<option value=""> </option>
					<?php
					$planns = new db();
					$planns->inline("SELECT `idagent`, `prenom` FROM `agent` WHERE isout = 'N' ORDER BY prenom");
					
					while ($row = mysql_fetch_array($planns->result)) { 
						echo '<option value="'.$row['idagent'].'">'.$row['prenom'].'</option>';
					}
					?>
					</select>
				</td>
			</tr>
		</table>
	</fieldset>
	<fieldset>
		<legend>
			R&eacute;sultats
		</legend>
		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
			<tr>
				<td><b>Affichage</b><br>
					<input type="checkbox" name="detail[]" value="detail" checked> Details
					<input type="checkbox" name="detail[]" value="soustotaux" checked> Sous-Totaux
					<input type="checkbox" name="detail[]" value="moyennes" checked> Moyenne
					<input type="checkbox" name="detail[]" value="totaux" checked> Total
				</td>
			</tr>
			<tr>
				<td><b>Totaux</b><br>
					<input type="radio" name="totaux" value="facture"> par Facture<br>
					<input type="radio" name="totaux" value="job"> par Job<br>
					<input type="radio" name="totaux" value="jour"> par Jour<br>
					<input type="radio" name="totaux" value="semaine"> par Semaine<br>
					<input type="radio" name="totaux" value="mois"> par Mois<br>
					<input type="radio" name="totaux" value="people"> par People<br>
					<input type="radio" name="totaux" value="agent"> par Planner<br>
					<input type="radio" name="totaux" value="client"> par Client<br>
				</td>
			</tr>
		</table>
	</fieldset>
</div>

<div id="infobouton">
<input type="submit" name="search" value="Rechercher">
</div>


</form>
