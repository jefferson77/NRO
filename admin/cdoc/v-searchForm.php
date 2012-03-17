<?php
$periods281          = array();
$periodsFicheSalaire = array();

## find 281.10 years
$fyears = dirFiles(Conf::read('Env.root')."document/people/281.10/originaux");
foreach ($fyears as $fichier) if (preg_match("/^281\.10-(\d{4}).pdf$/", $fichier, $m)) $periods281[] = $m[1];

## find salaires years
$fyears = dirFiles(Conf::read('Env.root')."document/people/fichesalaire/originaux");
foreach ($fyears as $fichier) if (preg_match("/^FichesSalaire-(\d{4})(\d{2}).pdf$/", $fichier, $m)) $periodsFicheSalaire[] = $m[1].'-'.$m[2];

?>
<div id="centerzonelarge">
	<div class="infosection">Recherche du people</div>
	<form action="?act=show" method="post" name="searchDoc">
		<fieldset>
			<legend>Infos de recherche</legend>
			<div align="center">
				<table class="standard" border="0" cellspacing="10" cellpadding="0" align="center">
					<tr>
						<td align="right" width="45%">Document</td>
						<td>
							<input type="radio" name="doctype" value="contrats"> Contrats <br>
							<input type="radio" name="doctype" value="c4"> C4 <br>
							<input type="radio" name="doctype" value="c131a"> C131A <br>
							<input type="radio" name="doctype" value="attest"> Attestation de Travail <br>
							<input type="radio" name="doctype" value="281.10"> 281.10<br>
							<input type="radio" name="doctype" value="fichesalaire"> Fiche Salaire
						</td>
					</tr>
					<tr>
						<td align="right">People</td>
						<td>
							<input id="idpeople" type="hidden" name="idpeople" value="">
							<input id="pname" type="text" size="30" name="pname" value=""> *
						</td>
					</tr>
					<tr id="dateRange">
						<td align="right">Dates</td>
						<td><input type="text" size="30" name="dates" value=""> ...</td>
					</tr>
					<tr id="dateYear">
						<td align="right">Dates</td>
						<td>
							<select name="period281[]" multiple onchange="" size="4">
								<?php foreach ($periods281 as $year) echo '<option value="'.$year.'">'.$year.'</option>'; ?>
							</select>
						</td>
					</tr>
					<tr id="dateYearMonth">
						<td align="right">Dates</td>
						<td>
							<select name="periodFiche[]" multiple onchange="" size="4">
								<?php foreach ($periodsFicheSalaire as $year) echo '<option value="'.substr($year, 0, 4).substr($year, -2).'">'.$year.'</option>'; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td align="right">Destination</td>
						<td><select name="destination">
							<option value="people">People</option>
							<option value="cofficer">Client / Contact Officer</option>
							<option value="shop">Shop</option>
							</select></td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" name="Rechercher" value="Rechercher"><br><br>
							Les dates entr&eacute;es doivent être comprises entre<br>
							le 01/09/2003 et le <?php echo fdate($DB->CONFIG('lastpayement')); ?>.<br>
							Toutes les dates en dehors de cette p&eacute;riode seront ignor&eacute;es
						</td>
					</tr>
				</table>
				<br>
				<span>* entrez le début du nom/prénom ou le code people</span>
			</div>
		</fieldset>
  	</form>
<br>
<br>
</div>
<script type="text/javascript" charset="utf-8">
	function formatResult(row) {
		return row[0];
	}
	function formatItem(row) {
		return "("+row[2]+") "+row[1];
	}

	$(document).ready(function() {
		// People autocomplete
		$("input#pname").autocomplete("<?php echo Conf::read('Env.urlroot') ?>query/peoplebyname.php", {
			inputClass: 'autocomp',
			width: 250,
			minChars: 2,
			formatItem: formatItem,
			formatResult: formatResult,
			delay: 200,
		});

		$("input#pname").result(function(data, row) {
			if (data) document.searchDoc.idpeople.value = row[0];
			if (data) document.searchDoc.pname.value = row[1];
		});

		// formatage du champ date
		$("#dateYear").hide();
		$("#dateYearMonth").hide();

		$("input[name='doctype']").change(
			function() {
			    if ($("input[name='doctype']:checked").val() == '281.10') {
		        	$("#dateRange").hide();
		        	$("#dateYear").show();
		        	$("#dateYearMonth").hide();
				}
			    else if ($("input[name='doctype']:checked").val() == 'fichesalaire') {
	        		$("#dateRange").hide();
	        		$("#dateYear").hide();
	        		$("#dateYearMonth").show();
				}
			    else {
        			$("#dateRange").show();
        			$("#dateYear").hide();
        			$("#dateYearMonth").hide();
				}
			}
		);
	});
</script>