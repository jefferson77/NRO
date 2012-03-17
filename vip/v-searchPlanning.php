<div id="centerzonelarge">
	<fieldset>
		<legend>Recherche pour le Planning</legend>
		<form name="PlanSearch" action="?act=planning" method="post">
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
				<tr>
					<td>Agent</td>
					<?php 
					# recherche client et cofficer
					if (!empty($_SESSION['idagent'])) $infosagent = $DB->getRow("SELECT idagent,prenom FROM `agent` WHERE `idagent` = ".$_SESSION['idagent']);
					?>
					<td colspan="3">
						<input type="hidden" name="idagent" value="" id="idagent">
						<input type="text" name="nomagent" value="" id="nomagent" size="18" title="Entrez le début d'un nom" style="text-align:center;">
					</td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
					<td>Mission ID</td>
					<td><input type="text" size="20" name="idvip" value=""></td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
					<td>Devis - Mission ...</td>
					<td>
						<font color="blue"> DEVIS </font> : <input type="checkbox" name="etat[]" value="0" checked><br>
						<font color="green"> JOB </font> : <input type="checkbox" name="etat[]" value="1" checked><br>
						<font color="peru"> READY </font> : <input type="checkbox" name="etat[]" value="11" checked><br>
						<font color="white"> Admin </font> : <input type="checkbox" name="etat[]" value="13"><br>
						<font color="black"> Arch </font> : <input type="checkbox" name="etat[]" value="15"><br>
						<font color="red"> OUT </font> : <input type="checkbox" name="etat[]" value="2">
					</td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
					<td>Job</td>
					<td>ID : <input type="text" size="20" name="idvipjob" value=""> R&eacute;f&eacute;rence :<input type="text" size="20" name="reference" value=""></td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
					<td>People</td>
					<td>
						prenom : <input type="text" size="20" name="pprenom" value=""> 
						nom : <input type="text" size="20" name="pnom" value=""> &nbsp; 
						(code registre) <input type="text" size="5" name="codepeople" value="">
						<input type="checkbox" name="lbureau" value="FR"> FR &nbsp;
						<input type="checkbox" name="lbureau" value="NL"> NL
					</td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
					<td>Client</td>
					<td>ID : <input type="text" size="20" name="idclient" value=""> Soci&eacute;t&eacute; : <input type="text" size="20" name="csociete" value=""></td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
					<td>Lieux</td>
					<td>ID : <input type="text" size="20" name="idshop" value=""> Soci&eacute;t&eacute; : <input type="text" size="20" name="shsociete" value=""> Ville : <input type="text" size="20" name="shville" value=""></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Activit&eacute;</td>
					<td><input type="text" size="20" name="activite" value=""></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Date</td>
					<td><input type="text" size="20" name="vipdate" value=""></td>
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
					<td>Champs supplémentaires :</td>
					<td><input type="checkbox" name="supfields[]" value="ndate" > Date de naissance du People</td>
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
			if (data) document.PlanSearch.idagent.value = row[0];
			if (data) document.PlanSearch.nomagent.value = row[1];
		});
	});
</script>