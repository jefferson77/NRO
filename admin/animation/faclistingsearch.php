<?php
$_SESSION['jobquid'] = $quid;
$_SESSION['jobquod'] = $quod;
$_SESSION['jobsort'] = $sort;
?>
<div id="centerzone">
	<fieldset>
		<legend>Recherche pour le listing FACTURATION des Anim</legend>
		<form action="?act=facture" method="post">
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
				<tr>
					<td>Agent</td>
					<td><input type="text" size="20" name="prenom" value="<?php echo $_SESSION['prenom']; ?>"></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Anim</td>
					<td>ID : <input type="text" size="20" name="idanimation" value=""> R&eacute;f&eacute;rence : <input type="text" size="20" name="reference" value=""></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Genre</td>
					<td>
						<select name="genre" size="1">
							<option value="">--------</option>
							<option value="Merchandising">Merchandising</option>
							<option value="Store Check">Store Check</option>
							<option value="Rack assistance">Rack assistance</option>
							<option value="Tasting">Tasting</option>
							<option value="Briefing">Briefing</option>
							<option value="Office">Office</option>
							<option value="Sampling">Sampling</option>
							<option value="">--------</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>People</td>
					<td>Nom : <input type="text" size="20" name="pnom" value=""> Pr&eacute;nom : <input type="text" size="20" name="pprenom" value=""> To do : <input type="checkbox" name="todo"  value="todo"></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Client</td>
					<td>Code : <input type="text" size="20" name="codeclient" value=""> Soci&eacute;t&eacute; : <input type="text" size="20" name="csociete" value=""></td>
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
					<td>De : <input type="text" size="20" name="date1" value=""> &agrave; : <input type="text" size="20" name="date2" value=""></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Porduit promo</td>
					<td><input type="text" size="20" name="produit" value=""></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Bon de commande</td>
					<td><input type="text" size="20" name="boncommande" value=""></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td><input type="reset" name="Reset" value="Reset"></td>
					<td align="center"><input type="submit" name="Modifier" value="Rechercher"><input type="hidden" name="listtype" value="1"></td>
				</tr>
			</table>
		</form>
	</fieldset>
</div>