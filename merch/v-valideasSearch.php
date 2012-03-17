<?php
include NIVO.'merch/easclients.php';

$PhraseBas = 'Recherche EAS valideas';

if (empty($_GET['idclient'])) $_GET['idclient'] = '1713'; # Carrefour EAS par défaut

?>
<style type="text/css" media="screen">
	.societe {
		font-size: 10px;
		color: #555;
	}
</style>
<div id="centerzonelarge">
	<fieldset>
		<legend>Recherche pour Validation des heures EAS</legend>
		<form action="?act=valideasresult" method="post">
			<table class="standard" border="0" cellspacing="1" cellpadding="2" align="center" width="90%">
				<tr>
					<td><b>P&eacute;riode</b></td>
					<td>
						Du : <input type="text" size="20" name="date1" value="<?php echo date("01/m/Y", strtotime("-10 days")) ?>"> 
						au : <input type="text" size="20" name="date2" value="<?php echo date("t/m/Y", strtotime("-10 days")) ?>">
					</td>
				</tr>
				<tr>
					<td><b>Document</b></td>
					<td>
						<input type="checkbox" name="doc[]" value="valideas" checked> Validation des Heures EAS &nbsp;&nbsp;&nbsp;
						<input type="checkbox" name="doc[]" value="reas"> Detail EAS
					</td>
				</tr>
				<tr>
					<td><b>Envoyer à : </b></td>
					<td>
						<select name="typeSend">
							<option value="people">People</option>
							<option value="shop">Shop</option>
							<option value="cofficer">Client / Contact Officer</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td><b>POS</b></td>
					<td>
<?php
## selection du client
foreach ($clients as $key => $client) {
	if (strpos($key, $_GET['idclient']) !== false) {
		echo '<font color="#FFF">'.$client.'</font>';
		$idclients = $key;
	} else {
		echo '<a href="?act=valideas&idclient='.$key.'">'.$client.'</a>';
	}
	echo " - ";
}
?>
					</td>
				</tr>
<?php
$rows = $DB->getArray("SELECT cp, idshop, ville, societe FROM `shop` WHERE ccfd IN (".$idclients.") ORDER BY ville");
if ($_REQUEST['checked'] != 'non') $checked = 'checked';

?>
				<tr>
					<td colspan="2">
						<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
							<tr>
								<td>
								<?php
									$i=0;
									foreach ($rows as $row) { 
										$i++;
										echo '<input type="checkbox" name="idshop[]" value="'.$row['idshop'].'" '.$checked.'> '.$row['cp'].' <b>'.$row['ville'].'</b> <span class="societe">'.$row['societe'].'</span><br>';
										if ($i > (count($rows)/3)) { 
											echo '</td><td valign="top">'; 
											$i = 0;
										}
									}
								?>
								</td>
							</tr>
						</table>	
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td align="center" colspan="2"><input type="submit" name="Modifier" value="Rechercher"></td>
				</tr>
			</table>
		</form>
		<br>
		<div align="center">
			<input type="checkbox" onclick="ounCheck(this.checked ?'1':'0')" checked title="Check/UnCheck ALL"> CheckAll
		</div>
	</fieldset>
</div>
<script type="text/javascript" charset="utf-8">
	function ounCheck(status) {
		if (status == 0) uncheckAll(document.getElementsByName("idshop[]"));
		if (status == 1) checkAll(document.getElementsByName("idshop[]"));
	}

	function checkAll(field) {
		for (i = 0; i < field.length; i++) field[i].checked = true ;
	}

	function uncheckAll(field) {
		for (i = 0; i < field.length; i++) field[i].checked = false ;
	}
</script>
