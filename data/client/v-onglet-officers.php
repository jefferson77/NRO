<?php
if (!empty($_REQUEST['idclient'])) {
	$officers = $DB->getArray("SELECT * FROM `cofficer` where `idclient` = ".$_REQUEST['idclient']);

	if (count($officers) == 0) {
		## Ajout un officer avec les infos de la base client si aucun n'est encore défini
		$DB->inline("INSERT INTO `cofficer` (`idclient` , `langue` , `qualite` , `oprenom` , `onom` , `email` , `tel` , `fax`, `departement`)
		VALUES ('".$infos['idclient']."' , '".$infos['langue']."' , '".$infos['qualite']."' , '".$infos['cprenom']."' , '".$infos['cnom']."' , '".$infos['email']."' , '".$infos['tel']."' , '".$infos['fax']."' , '".$infos['departement']."')");
	}
}

if (($vipassign > 0) or ($animassign > 0) or ($merchassign > 0)) echo 'Regroupement effectu&eacute; de '.$_REQUEST['idcofficer'].' vers '.$idcofficercible.' :<br>Vip : '.$vipassign.' - Anim : '.$animassign.' - Merch : '.$merchassign.'<br>';
?>
<table class="sortable-onload-0r rowstyle-alt no-arrow" border="0" width="90%" cellspacing="1" align="center">
	<thead>
		<tr>
			<th>Lang</th>
			<th>Qualit&eacute;</th>
			<th>Nom</th>
			<th>Pr&eacute;nom</th>
			<th>Dept</th>
			<th>Tel</th>
			<th>Fax</th>
			<th>Gsm</th>
			<th>email</th>
			<th><img src="<?php echo NIVO."illus/mail.png" ?>"></th>
			<th><img src="<?php echo NIVO."illus/telephone-fax.png" ?>"></th>
			<th><img src="<?php echo NIVO."illus/printer.png" ?>"></th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($officers as $row) { ?>
	<tr>
		<form action="?s=1" method="post">
			<input type="hidden" name="idclient" value="<?php echo $_REQUEST['idclient'] ?>">
		<td>
			<select name="langue">
			<?php
			echo '
			<option value="FR"'.((($row['langue'] == '') OR ($row['langue'] == 'FR'))?' selected':'').'>FR</option>
			<option value="NL"'.(($row['langue'] == 'NL')?' selected':'').'>NL</option>';?>
			</select>
		</td>
		<td>
			<select name="qualite">
			<?php echo '
			<option value="Monsieur"'.((($row['qualite'] == '') OR ($row['qualite'] == 'Monsieur') OR ($row['qualite'] == 'Mr'))?' selected':'').'>Mr</option>
			<option value="Madame"'.(($row['qualite'] == 'Madame')?' selected':'').'>Mme</option>
			<option value="Mlle"'.(($row['qualite'] == 'Mlle')?' selected':'').'>Mlle</option>';
			?>
			</select>
		</td>
		<td><input type="text" size="15" name="onom" value="<?php echo $row['onom']; ?>"></td>
		<td><input type="text" size="12" name="oprenom" value="<?php echo $row['oprenom']; ?>"></td>
		<td><input type="text" size="14" name="departement" value="<?php echo $row['departement']; ?>"></td>
		<td><input type="text" size="14" name="tel" value="<?php echo $row['tel']; ?>"></td>
		<td><input type="text" size="14" name="fax" value="<?php echo $row['fax']; ?>" id="fax<?php echo $row['idcofficer'] ?>" onkeyup="disableDocPrefRadio(<?php echo $row['idcofficer'] ?>);"></td>
		<td><input type="text" size="14" name="gsm" value="<?php echo $row['gsm']; ?>"></td>
		<td><input type="text" size="25" name="email" value="<?php echo $row['email']; ?>" id="email<?php echo $row['idcofficer'] ?>" onkeyup="disableDocPrefRadio(<?php echo $row['idcofficer'] ?>);"></td>
		<td>
			<input type="radio" name="docpref" value="email" id="docprefEmail<?php echo $row['idcofficer'] ?>"
				<?php if($row['docpref'] == 'email' or empty($row['docpref'])) 	echo "checked";  ?>>
			</input>
		</td>
		<td>
			<input type="radio" name="docpref" value="fax"	 id="docprefFax<?php echo $row['idcofficer'] ?>"
				<?php if($row['docpref'] == 'fax') 	echo "checked";  ?>>
			</input>
		</td>
		<td>
			<input type="radio" name="docpref" value="post"
				<?php if($row['docpref'] == 'post') 	echo "checked";  ?>>
			</input>
		</td>
		<td>
			<input type="hidden" name="idcofficer" value="<?php echo $row['idcofficer'];?>">
			<input type="submit" class="btn tick_circle" name="act" value="modofficer">
			<?php if (count($officers) > 1) { ?>
				<input type="submit" class="btn minus_circle" name="act" value="delofficer">
			<?php } ?>
		</td>
		</form>
	</tr>
<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<form action="?s=1" method="post">
				<input type="hidden" name="idclient" value="<?php echo $_REQUEST['idclient'] ?>">
			<td>
				<select name="langue">
				<?php echo '
					<option value="FR"'.(((isset($infos['langue'])) OR ($infos['langue'] == 'FR'))?' selected':'').'>FR</option>
					<option value="NL"'.(($infos['langue'] == 'NL')?' selected':'').'>NL</option>'; ?>
				</select>
			</td>
			<td>
				<select name="qualite">
				<?php echo '
					<option value="Monsieur"'.(((isset($infos['qualite'])) OR ($infos['qualite'] == 'Monsieur'))?' selected':'').'>Mr</option>
					<option value="Madame"'.(($infos['qualite'] == 'Madame')?' selected':'').'>Mme</option>
					<option value="Mlle"'.(($infos['qualite'] == 'Mlle')?' selected':'').'>Mlle</option>'; ?>
				</select>
			</td>
			<td><input type="text" size="15" name="onom" value=""></td>
			<td><input type="text" size="12" name="oprenom" value=""></td>
			<td><input type="text" size="14" name="departement" value=""></td>
			<td><input type="text" size="14" name="tel" value=""></td>
			<td><input type="text" size="14" name="fax" value=""></td>
			<td><input type="text" size="14" name="gsm" value=""></td>
			<td><input type="text" size="25" name="email" value=""></td>
			<td colspan=3>&nbsp;</td>
			<td><input type="submit" class="btn plus_circle" name="act" value="addofficer"></td>
			</form>
		</tr>
	</tfoot>
</table>
<?php
$var = "";
foreach($officers as $row)
{
	$var.=$row['idcofficer'].",";
}

$var = substr($var,0,-1);

?>
<script type="text/javascript" charset="utf-8">

	var ids = "<?php echo $var; ?>";

	function trim (myString)
	{
		return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
	}

	function disableDocPrefRadio(id)
	{
		var fax = document.getElementById('fax'+id).value;
		var email = document.getElementById('email'+id).value;

		email = trim(email);
		fax = trim(fax);

		if(fax == "")
		{
			document.getElementById("docprefFax"+id).disabled = true;
		}
		else
		{
			document.getElementById("docprefFax"+id).disabled = false;
		}
		if(email == "")
		{
			document.getElementById("docprefEmail"+id).disabled = true;
		}
		else
		{
			document.getElementById("docprefEmail"+id).disabled = false;
		}

	}
	var i = 0;

	var splitted = new Array();

	splitted = ids.split(',');

	while(i < splitted.length)
	{
		disableDocPrefRadio(splitted[i]);
		i++;
	}


</script>