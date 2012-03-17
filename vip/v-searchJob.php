<div id="centerzonelarge">
	<fieldset>
		<legend>Recherche pour le listing des Job</legend>
		<form name="jobSearch" action="?act=listing" method="post">
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
				<tr>
					<td>Agent</td>
					<td colspan="3">
						<input type="hidden" name="idagent" value="" id="idagent">
						<input type="text" name="nomagent" value="" id="nomagent" size="18" title="Entrez le début d'un nom" style="text-align:center;">
					</td>
				</tr>
				<tr>
					<td>Job</td>
					<td>ID : <input type="text" size="20" name="idvipjob" value=""> R&eacute;f&eacute;rence : <input type="text" size="20" name="reference" value=""></td>
				</tr>

				<tr>
					<td>Client</td>
					<td>ID : <input type="text" size="20" name="idclient" value=""> Soci&eacute;t&eacute; : <input type="text" size="20" name="csociete" value=""></td>
				</tr>
				<tr>
					<td>Devis - Mission ...</td>
					<td>
						<div style="width: 150px; text-align: right;">
							<font color="blue"> DEVIS </font> : <input type="checkbox" name="etat[]" value="devis" checked><br>
							<font color="green"> JOB </font> : <input type="checkbox" name="etat[]" value="1" checked><br>
							<font color="peru"> READY </font> : <input type="checkbox" name="etat[]" value="11" checked><br>
							<font color="white"> Admin </font> : <input type="checkbox" name="etat[]" value="13"><br>
							<font color="black"> Arch </font> : <input type="checkbox" name="etat[]" value="15"><br>
							<font color="red"> Jobs OUT </font> : <input type="checkbox" name="etat[]" value="2"><br>
							<font color="#AE0101"> Devis OUT </font> : <input type="checkbox" name="etat[]" value="99">
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" name="Modifier" value="Rechercher"></td>
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
			if (data) document.jobSearch.idagent.value = row[0];
			if (data) document.jobSearch.nomagent.value = row[1];
		});
	});
</script>