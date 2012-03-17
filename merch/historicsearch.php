<div id="centerzonelarge">
<?php
$classe = 'standard' ;
$_SESSION['sqlwhere']='';
$_SESSION['sqltxt']='';
?>
		<fieldset>
			<legend>Recherche pour le listing des Merch - historic</legend>
			<form action="?act=historic" method="post" name="merchSearch">
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
						<td>Merch</td>
						<td>ID : <input type="text" size="20" name="idmerch" value=""> R&eacute;f&eacute;rence : <input type="text" size="20" name="produit" value=""></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>Genre</td>
						<td>
							<select name="genre" size="1">
								<option value="">--------</option>
								<option value="Store Check">Store Check</option>
								<option value="Rack assistance">Rack assistance</option>
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
						<td><input type="text" size="20" name="date" value=""></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>Semaine</td>
						<td>
							<input type="text" size="10" name="weekm" value="">
							<select name="yearm" size="1">
								<option value="">Toutes</option>
								<?php
								for ($i=-7; $i < 2; $i++) { 
									$year = date("Y") + $i;
									echo '<option value="'.$year.'" '.((date("Y") == $year)?'selected':'').'>'.$year.'</option>';
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>To do</td>
						<td><input type="radio" name="todo" value="0" > Missions sans people</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>Affichage</td>
						<td><input type="radio" name="listtype" value="0" checked> Listing / <input type="radio" name="listtype" value="1"> Frais</td>
					</tr>
					<tr>
						<td colspan="2">
							<fieldset id="mission_effacee" class="">
								<legend>Mission Effacée</legend>
								<input type="checkbox" name="zoutmerch" value="yes"> Mission Effacée. Raison : 
								<select name="raisonout">
									<option value=""></option selected>
									<?php foreach ($raisonout as $key => $raison) echo '<option value="'.$key.'">'.$raison.'</option>'; ?>
								</select> Note : 
								<input type="text" name="noteout" value="">
							</fieldset>
						</td>
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
			if (data) document.merchSearch.idagent.value = row[0];
			if (data) document.merchSearch.nomagent.value = row[1];
		});
	});
</script>