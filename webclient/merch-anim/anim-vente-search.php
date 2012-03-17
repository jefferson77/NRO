<?php $classe = "planning" ; ?>

<div class="corps2">
<Fieldset class="width">
	<legend class="width">Sales Repporting</legend>
	<br>	
			<form action="anim-vente.php?table=table" method="post" target="_blank">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							Date
						</td>
						<td>
							De : <input type="text" size="20" name="date1" value=""> &agrave; : <input type="text" size="20" name="date2" value="">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							Affichage des Titres
						</td>
						<td>
							<input type="radio" name="titre" value="0" checked> Non / <input type="radio" name="titre" value="1"> Oui
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							Affichage des notes
						</td>
						<td>
							<input type="radio" name="note" value="0" checked> Non / <input type="radio" name="note" value="1"> Oui
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							Affichage des sous Totaux
						</td>
						<td>
							<input type="radio" name="tot" value="0" checked> Non / <input type="radio" name="tot" value="1"> Oui
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							Affichage de la Version Imprimable
						</td>
						<td>
							<input type="radio" name="pdf" value="0" checked> Non / <input type="radio" name="pdf" value="1"> Oui
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							<input type="reset" name="Reset" value="Reset">
						</td>

						<td align="center">
							<input type="submit" name="Modifier" value="Rechercher">
						</td>
					</tr>
				</table>
			</form>
	<br>	
</fieldset>
</div>