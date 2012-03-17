<div id="centerzonelarge">
<?php
$classe = 'standard' ;

$_SESSION['animhistoricquid'] = $quid;
$_SESSION['animhistoricquod'] = $quod;
$_SESSION['animhistoricsort'] = $sort;
$_SESSION['animhistoricsearch'] = $recherche1;
?>
		<fieldset>
			<legend>Recherche pour le listing des Anim - historic</legend>
			<form action="?act=historic" method="post" name="historicSearch">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td>Agent</td>
						<td colspan="3">
							<input type="hidden" name="idagent" value="" id="idagent">
							<input type="text" name="nomagent" value="" id="nomagent" size="18" title="Entrez le début d'un nom" style="text-align:center;">
						</td>
					</tr>
					<tr>
						<td>Genre</td>
						<td>
							<select name="genre" size="1">
								<option value=""></option>
							<?php 
								$sql = "SELECT `genre` FROM `animation` GROUP BY `genre` HAVING `genre` NOT LIKE '' ORDER BY `genre`"; $search = new db (); $search->inline($sql); 
								while ($legenre = mysql_fetch_array($search->result)) {
									echo '<option value="'.$legenre['genre'].'">'.$legenre['genre'].'</option>';
								}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>Anim JOB</td>
						<td>ID : <input type="text" size="20" name="idanimjob" value=""> R&eacute;f&eacute;rence : <input type="text" size="20" name="referencejob" value=""></td>
					</tr>
					<tr>
						<td>Anim MISSIONS</td>
						<td>ID : <input type="text" size="20" name="idanimation" value=""> R&eacute;f&eacute;rence : <input type="text" size="20" name="reference" value=""></td>
					</tr>

					<tr>
						<td colspan="2">&nbsp;</td>
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
						<td>De : <input type="text" size="20" name="date1" value=""> &agrave; : <input type="text" size="20" name="date2" value=""></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>Semaine</td>
						<td>
							De : <input type="text" size="10" name="weekm1" value=""> &agrave; : <input type="text" size="10" name="weekm2" value=""> &nbsp;
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
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td valign="top">Etat</td>
						<td>
							<font color="green">1 En cours </font> : <input type="checkbox" name="facturation[]" value="1" checked> - 
							<font color="blue">2 A v&eacute;rifier </font> : <input type="checkbox" name="facturation[]" value="2" checked> - 
							<font color="green">3 A corriger </font> : <input type="checkbox" name="facturation[]" value="3" checked> - 
							<font color="blue">4 A re v&eacute;rifier </font> : <input type="checkbox" name="facturation[]" value="4" checked> <br> 
							<font color="peru">5 Prefactoring </font> : <input type="checkbox" name="facturation[]" value="5" checked> - 
							<font color="peru">6 Factoring </font> : <input type="checkbox" name="facturation[]" value="6" checked> -
							<font color="black">8 Factur&eacute;es </font> : <input type="checkbox" name="facturation[]" value="8" checked> - 
							<font color="black">9 FL </font> : <input type="checkbox" name="facturation[]" value="9" checked><br>
							<font color="red">20 Purge FL </font> : <input type="checkbox" name="facturation[]" value="20" checked> -
							<font color="red">25 Purge Out </font> : <input type="checkbox" name="facturation[]" value="25" checked><br>
							<font color="white">95 Out Celsys </font> : <input type="checkbox" name="facturation[]" value="95"> -
							<font color="white">97 Out Exception </font> : <input type="checkbox" name="facturation[]" value="97"> -
							<font color="white">98 Out Annul&eacute; </font> : <input type="checkbox" name="facturation[]" value="98">
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
			if (data) document.historicSearch.idagent.value = row[0];
			if (data) document.historicSearch.nomagent.value = row[1];
		});
	});
</script>