<div id="centerzone">
<?php
$classe = 'standard' ;

		$_SESSION['planquid'] = $quid;
		$_SESSION['planquod'] = $quod;
		$_SESSION['plansort'] = $sort;
		$_SESSION['plansearch'] = $recherche1;
?>
		<fieldset>
			<legend>Recherche pour le planning des Merch</legend>
			<form action="?act=planning" method="post" name="planningSearch">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td>Agent</td>
						<td colspan="3">
							<input type="hidden" name="idagent" value="" id="idagent">
							<input type="text" name="nomagent" value="" id="nomagent" size="18" title="Entrez le début d'un nom" style="text-align:center;">
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>Genre</td>
						<td>
							<select name="genre" size="1">
								<option value="Rack assistance">Rack assistance</option>
								<option value="Store Check">Store Check</option>
								<option value="EAS" selected>EAS</option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>People</td>
						<td>Nom : <input type="text" size="20" name="pnom" value=""> Pr&eacute;nom : <input type="text" size="20" name="pprenom" value="">  &nbsp; code (registre) <input type="text" size="5" name="codepeople" value=""></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>Client</td>
						<td>ID : <input type="text" size="4" name="idclient" value=""> Soci&eacute;t&eacute; : <input type="text" size="20" name="csociete" value="">  </td>
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
						<td>Ann&eacute;e</td>
						<td><input type="text" size="10" name="yearm" value="<?php echo date("Y") ?>"></td>
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
						<td>Archive</td>
						<td><input type="checkbox" name="arch" value="yes"></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
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
	function formatResult(row) {return row[0];}
	function formatItem(row) {return row[1];}
	
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
			if (data) document.planningSearch.idagent.value = row[0];
			if (data) document.planningSearch.nomagent.value = row[1];
		});
	});
</script>