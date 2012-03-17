<div id="centerzone">
<?php
unset($_SESSION['mquid']);
unset($_SESSION['msort']);
unset($_SESSION['mskip']);
unset($_SESSION['type']);
?>
		<fieldset>
			<legend>Recherche pour le listing des Merch</legend>
			<form action="?act=listing&amp;listing=newsearch" method="post">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td>Agent</td>
						<?php 
						# recherche client et cofficer
						if (!empty($_SESSION['idagent'])) {
							$infosagent = $DB->inline("SELECT idagent,prenom FROM `agent` WHERE `idagent` = ".$_SESSION['idagent']);
						}
						?>
						<td colspan="3">
							<?php
								$DB->inline("SELECT idagent, nom, prenom FROM `agent` WHERE isout = 'N'");
								while ($row = mysql_fetch_array($DB->result))
								{
									$agent[$row['idagent']]= $row['prenom']." ".$row['nom'];
								} 
								 echo createSelectList('idagent',$agent,$infosagent['idagent'],'--');
							?>
						</td>
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
								<option value="" selected>Tout</option>
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
						<td>
							Nom : <input type="text" size="20" name="pnom" value="">
							Pr&eacute;nom : <input type="text" size="20" name="pprenom" value="">
							To do : <input type="checkbox" name="todo"  value="todo"> &nbsp;
							code (registre) <input type="text" size="5" name="codepeople" value="">
							Langue <input type="radio" name="lbureau" value="fr"> FR / <input type="radio" name="lbureau" value="nl"> NL
						</td>
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
						<td><input type="text" size="10" name="weekm" value=""> Annee : <input type="text" size="5" name="yearm" value=""></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>Porduit / remarque</td>
						<td><input type="text" size="20" name="produit" value=""></td>
					</tr>
					<tr>
						<td>Bon Commande</td>
						<td><input type="text" size="20" name="boncommande" value=""></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>

					<tr>
						<td>Contrat encod&eacute;</td>
						<td><input type="radio" name="contratencode" value="1"> oui / <input type="radio" name="contratencode" value="non"> non / <input type="radio" name="contratencode" value=""> tous</td>
					</tr>
					<tr>
						<td>Rapport encod&eacute;</td>
						<td><input type="radio" name="rapportencode" value="1"> oui / <input type="radio" name="rapportencode" value="0"> non / <input type="radio" name="rapportencode" value=""> tous</td>
					</tr>
					<tr>
						<td>R&eacute;current</td>
						<td><input type="radio" name="recurrence" value="1"> oui / <input type="radio" name="recurrence" value="0"> non / <input type="radio" name="recurrence" value=""> tous</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>Affichage</td>
						<td><input type="radio" name="merchlisttype" value="0" checked> Listing / <input type="radio" name="merchlisttype" value="2"> Info &eacute;ditable / <input type="radio" name="merchlisttype" value="3"> D&eacute;compte</td>
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