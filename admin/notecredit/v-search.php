<form action="?act=list" method="post">
	<div id="centerzonelarge">
		<fieldset>
			<legend>Recherche Note de Cr&eacute;dit</legend>
			<table class="standard" border="0" cellspacing="10" cellpadding="0" align="center">
				<tr>
					<td>Num NC</td>
					<td><input type="text" size="20" name="idfac" value=""></td>
				</tr>
				<tr>
					<td>Intitule</td>
					<td><input type="text" size="20" name="intitule" value=""></td>
				</tr>
				<tr>
					<td>Client</td>
					<td><input type="text" size="20" name="societe" value=""> - <input type="text" size="5" name="idclient" value=""></td>
				</tr>
				<tr>
					<td>Date de la Note de Crédit</td>
					<td><input type="text" size="20" name="datefac" value=""> ...</td>
				</tr>
				<tr>
					<td>Date de la FACTURE</td>
					<td><input type="text" size="10" name="facturedate" value=""> ...</td>
				</tr>
				<tr>
					<td>Facture de référence</td>
					<td><input type="text" size="10" name="facref" value=""></td>
				</tr>
				<tr>
					<td>Secteur</td>
					<td>
						<input type="radio" name="secteur" value="1"> Vip
						<input type="radio" name="secteur" value="2"> Anim
						<input type="radio" name="secteur" value="3"> Merch
						<input type="radio" name="secteur" value="4"> Eas
					</td>
				</tr>
			</table>
		</fieldset>
		<div align="center">
			<input type="submit" name="search" value="Rechercher">
		</div>
	</div>
</form>
