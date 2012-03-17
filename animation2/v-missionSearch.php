<?php
## Clean SESSION variables
unset($_SESSION['animmissionquid']);
unset($_SESSION['animmissionsort']);
?>
<div id="centerzone">
		<fieldset>
			<legend>Recherche pour le listing des Anim MISSIONS</legend>
			<form name="missionSearch" action="?act=listingmissionresult" method="post">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td>Agent</td>
						<td colspan="3">
							<input type="hidden" name="idagent" value="" id="idagent">
							<input type="text" name="nomagent" value="" id="nomagent" size="18" title="Entrez le début d'un nom" style="text-align:center;"><br><br>
						</td>
					</tr>
					<tr>
						<td>Anim Job</td>
						<td>ID : <input type="text" size="20" name="idanimjob" value=""> R&eacute;f&eacute;rence : <input type="text" size="20" name="reference" value=""></td>
					</tr>
					<tr>
						<td>Anim Mission</td>
						<td>ID : <input type="text" size="20" name="idanimation" value=""> R&eacute;f&eacute;rence : <input type="text" size="20" name="reference" value=""></td>
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
						<td><input type="text" size="40" name="date" value=""></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>Semaine</td>
						<td><input type="text" size="15" name="weekm" value=""> Annee : <input type="text" size="6" name="yearm" value=""></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>Produit promo</td>
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
						<td>Contrat Encod&eacute;</td>
						<td>
							<input type="radio" name="ccheck" value="Y"> Oui &nbsp;&nbsp;
							<input type="radio" name="ccheck" value="N"> Non &nbsp;&nbsp;
							<input type="radio" name="ccheck" value=""> Tous
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>Affichage</td>
						<td><input type="radio" name="listtype" value="0" checked> Listing / <input type="radio" name="listtype" value="1"> Frais / <input type="radio" name="listtype" value="3"> Payement</td>
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
<script type="text/javascript" charset="utf-8">
	function formatResult(row) {
		return row[0];
	}
	function formatItem(row) {
		return row[1];
	}
	$(document).ready(function() {
		$("input#nomagent").autocomplete("<?php echo Conf::read('Env.urlroot') ?>query/agent.php", {
			inputClass: 'autocomp',
			width: 250,
			minChars: 2,
			formatItem: formatItem,
			formatResult: formatResult,
			delay: 200
		});

		$("input#nomagent").result(function(data, row) { 
			if (data) document.missionSearch.idagent.value = row[0];
			if (data) document.missionSearch.nomagent.value = row[1];
		});
	});
</script>