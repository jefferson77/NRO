<div id="centerzone">
	<h2><?php echo $titreduplic; ?></h2>
	<fieldset>
		<legend>Recherche pour le listing des Merch</legend>
		<form action="?act=listing" method="post">
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
				<tr>
					<td>Agent</td>
					<td><input type="text" size="20" name="prenom" value="<?php echo $_SESSION['prenom']; ?>"></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Merch</td>
					<td>ID : <input type="text" size="20" name="idmerch" value=""></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Genre</td>
					<td>
						<select name="genre" size="1">
							<option value="">Tout</option>
							<option value="Rack assistance">Rack assistance</option>
							<option value="Store Check">Store Check</option>
							<option value="EAS">EAS</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>People</td>
					<td>Nom : <input type="text" size="20" name="pnom" value=""> Pr&eacute;nom : <input type="text" size="20" name="pprenom" value=""> To do : <input type="checkbox" name="todo"  value="todo"> &nbsp; code (registre) <input type="text" size="5" name="codepeople" value=""></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Client</td>
					<td>Id : <input type="text" size="20" name="codeclient" value=""> Soci&eacute;t&eacute; : <input type="text" size="20" name="csociete" value=""></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Lieux</td>
					<td>Code : <input type="text" size="20" name="codeshop" value=""> Soci&eacute;t&eacute; : <input type="text" size="20" name="ssociete" value=""> Ville : <input type="text" size="20" name="sville" value=""></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Date</td>
					<td><input type="text" size="20" name="date" value=""></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Semaine</td>
					<td><input type="text" size="10" name="weekm" value=""></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Porduit / remarque</td>
					<td><input type="text" size="20" name="produit" value=""></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>

				<tr>
					<td>Contrat encod&eacute;</td>
					<td><input type="radio" name="contratencode" value="1"> oui / <input type="radio" name="contratencode" value="0"> non / <input type="radio" name="contratencode" value=""> tous</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Affichage</td>
					<td><input type="radio" name="merchlisttype" value="0" checked> Listing / <input type="radio" name="merchlisttype" value="1"> Info &eacute;ditable / <input type="radio" name="merchlisttype" value="3"> D&eacute;compte</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td><input type="reset" name="Reset" value="Reset"></td>
					<td align="center"><input type="submit" name="Modifier" value="Rechercher"></td>
				</tr>
			</table>
		</form>
	</fieldset>
</div>