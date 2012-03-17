<div id="centerzonelarge">
	<form action="?act=list" method="post">
		<fieldset>
			<legend>
				Recherche Facture
			</legend>
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
				<tr>
					<td>Num Facture</td>
					<td><input type="text" size="15" name="idfac" value=""></td>
				</tr>
				<tr>
					<td>Secteur</td>
					<td>
						<input type="checkbox" name="secteur[]" value="1"> Vip 
						<input type="checkbox" name="secteur[]" value="2"> Anim 
						<input type="checkbox" name="secteur[]" value="3"> Merch 
						<input type="checkbox" name="secteur[]" value="4"> EAS 
					</td>
				</tr>
				<tr>
					<td>Mode</td>
					<td>
						<input type="checkbox" name="modefac[]" value="A"> AUTO 
						<input type="checkbox" name="modefac[]" value="M"> Manuel 
					</td>
				</tr>
				<tr>
					<td>Date</td>
					<td><input type="text" size="20" name="datefac" value=""> ...</td>
				</tr>
				<tr>
					<td>Client</td>
					<td>ID <input type="text" size="4" name="idclient" value=""> Soci&eacute;t&eacute; <input type="text" size="30" name="societe" value=""></td>
				</tr>
				<tr>
					<td>Montant</td>
					<td><input type="text" size="20" name="montant" value=""></td>
				</tr>
				<tr>
					<td>Intitule</td>
					<td><input type="text" size="20" name="intitule" value=""></td>
				</tr>
				<tr>
					<td>Echeance</td>
					<td>
						<input type="checkbox" name="echeance[]" value="echu"> <img src="<?php echo STATIK ?>illus/clock_red.png" width="16" height="16" alt="Echu" align="absmiddle"> Echu <br>
						<input type="checkbox" name="echeance[]" value="nonechu"> <img src="<?php echo STATIK ?>illus/clock.png" width="16" height="16" alt="Non Echu" align="absmiddle"> Non Echu
					</td>
				</tr>
				<tr>
					<td>PO</td>
					<td><input type="text" size="20" name="po" value=""></td>
				</tr>
			</table>
		</fieldset>
	<div align="center">
		<input type="submit" name="search" value="Rechercher">
	</div>
</form>
</div>
