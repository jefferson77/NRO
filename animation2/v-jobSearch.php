<div id="centerzone">
	<fieldset>
		<legend>Recherche des Actions Anim</legend>
		<form name="jobSearch" action="?act=listingjob" method="post">
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
					<td>Action Anim</td>
					<td>ID : <input type="text" size="20" name="idanimjob" value=""> R&eacute;f&eacute;rence : <input type="text" size="20" name="reference" value=""></td>
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
					<td>Client</td>
					<td>Code : <input type="text" size="20" name="codeclient" value=""> Soci&eacute;t&eacute; : <input type="text" size="20" name="csociete" value=""></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Date</td>
					<td>De : <input type="text" size="20" name="date" value=""></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
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
					<td>Situation</td>
					<td>
						<input type="radio" name="statutarchive" value="open" checked> En cours &nbsp; - &nbsp;
						<input type="radio" name="statutarchive" value="closed"> Clotur&eacute;s &amp; factur&eacute;s &nbsp; - &nbsp;
						<input type="radio" name="statutarchive" value="canceled"> Archiv&eacute;s &amp; non prest&eacute;s &nbsp; - &nbsp;
						<input type="radio" name="statutarchive" value=""> Tous
					</td>
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
			if (data) document.jobSearch.idagent.value = row[0];
			if (data) document.jobSearch.nomagent.value = row[1];
		});
	});
</script>