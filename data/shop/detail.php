<?php
################### Code PHP ########################
if (!empty($idshop)) {
	$detail = new db();
	$detail->inline("SELECT * FROM `shop` WHERE `idshop` = $idshop");
	$infos = mysql_fetch_array($detail->result) ; 
	$newweb = $infos['newweb'];
}
################### Fin Code PHP ########################
?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=modif&embed=<?php echo $_GET['embed']; ?>&from=<?php echo $_GET['from']; ?>" method="post">
	<input type="hidden" name="idshop" value="<?php echo $idshop;?>"> 
	<input type="hidden" name="idvip" value="<?php echo $idvip;?>"> 
	<input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>"> 
	<input type="hidden" name="idmerch" value="<?php echo $idmerch;?>"> 
	<input type="hidden" name="idanimation" value="<?php echo $idanimation;?>"> 
	<input type="hidden" name="idanimjob" value="<?php echo $idanimjob;?>"> 
<?php /* #  Menu de gauche */ ?>
<div id="leftmenu<?php if($_GET['embed']=='yes') echo '-embed'; ?>">
	<div id="idsquare">
		<table border="0" cellspacing="1" cellpadding="2" align="center" width="100%">
			<tr><td align="center">LIEUX</td></tr>
			<tr><td align="center"><input type="text" size="5" name="codeshop" value="<?php echo (!empty($infos['codeshop']))?$infos['codeshop']:'SH'.$infos['idshop']; ?>"></td></tr>
		</table>
	</div>
	<div id="idsquare2">
		<table border="0" cellspacing="1" cellpadding="2" align="center" width="100%">	
			<tr><td align="center">GeoLoc</td></tr>
			<tr><td align="center" style="font-size:11px">Lat: <?php if($infos['glat'] != 0) { echo $infos['glat']; } ?></td></tr>
			<tr><td align="center" style="font-size:11px">Lon: <?php if($infos['glong'] != 0) { echo $infos['glong']; } ?></td></tr>
			<tr><td align="center">
			<a href="<?php echo $_SERVER['PHP_SELF'].'?act=geoloc&idshop='.$infos['idshop'].'&idvip='.$idvip.'&idvipjob='.$idvipjob.'&idmerch='.$idmerch.'&idanimation='.$idanimation.'&idanimjob='.$idanimjob.'&from='.$_GET['from'].'&embed='.$_GET['embed']; ?>" style="font-size:10px; text-decoration:none; background-color:#FFFFFF; padding:1px; border: 1px solid #527184;">Rechercher</a></td></tr>
		</table>
	</div>
</div>
<?php /* #  Corps de la Page */ ?>
<div id="infozone<?php if($_GET['embed']=='yes') echo '-embed'; ?>">
<div class="infosection">Infos G&eacute;n&eacute;rales</div>
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
		<tr>
			<td width="150">
				Soci&eacute;t&eacute; <?php if ($infos['newweb'] == 1) {echo '<font color="red">New </font>'; } ?>
			</td>
			<td colspan="3">
				<input type="text" size="50" name="societe" value="<?php echo $infos['societe']; ?>">
			</td>
			<td rowspan="7" width="400">
				<iframe style="border: 0px;" scrolling="no" src="http://77.109.79.37/mod/gmap.php?lat=<?php echo $infos['glat'] ?>&amp;long=<?php echo $infos['glong'] ?>&amp;w=400&amp;h=250" width="402" height="252" ></iframe>
			</td>
	    </tr>
		<tr>
			<td>
				Adresse
			</td>
		    <td colspan="3">
				<input type="text" size="50" name="adresse" value="<?php echo $infos['adresse']; ?>">
			</td>
		</tr>
		<tr>
			<td>
				Code postal
			</td>
			<td>
				<input type="text" size="20" name="cp" value="<?php echo $infos['cp']; ?>">
			</td>
			<td>
				Ville
			</td>
			<td>
				<input type="text" size="20" name="ville" value="<?php echo $infos['ville']; ?>">
			</td>
        </tr>
		<tr>
			<td>
				Pays
			</td>
			<td colspan="3" >
				<select name="pays">
					<option value="BE" <?php if($infos['pays']=='Belgique' OR empty($infos['pays'])) echo 'selected'; ?>>Belgique</option>
					<option value="FR" <?php if($infos['pays']=='France') echo 'selected'; ?>>France</option>
					<option value="LU" <?php if($infos['pays']=='Luxembourg') echo 'selected'; ?>>Luxembourg</option>
					<option value="NL" <?php if($infos['pays']=='Pays-Bas' OR $infos['pays']=='Duitsland') echo 'selected'; ?>>Pays-Bas</option>					
				</select>
			</td>
        </tr>
		<tr>
			<td>
				Tel
			</td>
			<td>
				<input type="text" size="20" name="tel" value="<?php echo $infos['tel']; ?>">
			</td>
			<td>
				Fax
			</td>
			<td>
				<input type="text" size="20" name="fax" id="fax" value="<?php echo $infos['fax']; ?>" onkeyup="disableDocPrefRadio()">
			</td>
        </tr>
		<tr>
			<td>
				Web
			</td>
			<td>
				<input type="text" size="50" name="web" value="<?php echo $infos['web']; ?>">
			</td>
			<td>
				Email
			</td>
			<td>
				<input type="text" size="20" name="email" id="email" value="<?php echo $infos['email']; ?>" onkeyup='disableDocPrefRadio()'>
			</td>
        </tr>
		<tr>
			<td>
				Notes
			</td>
			<td>
				<textarea name="notes" rows="5" cols="50"><?php echo $infos['notes']; ?></textarea>
			</td>
			<td>
				Préférence envoi de documents
			</td>
			<td>
				<input type="radio" name="docpref" value="email" id="docprefEmail"
					<?php if($infos['docpref'] == 'email'  or empty($infos['docpref'])) 	echo "checked";  ?>>&nbsp;<img src="<?php echo NIVO."illus/mail.png" ?>">&nbsp;E-mail	
				</input><br>
				<input type="radio" name="docpref" value="fax" id="docprefFax"
					<?php if($infos['docpref'] == 'fax') 	echo "checked";  ?>>&nbsp;<img src="<?php echo NIVO."illus/telephone-fax.png" ?>">&nbsp;Fax		
				</input><br>
				<input type="radio" name="docpref" value="post"	 
					<?php if($infos['docpref'] == 'post') 	echo "checked";  ?>>&nbsp;<img src="<?php echo NIVO."illus/printer.png" ?>">&nbsp;Poste	
				</input>
			</td>
        </tr>
	</table>
		<div class="infosection">Info Responsable</div>
		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">		<tr>
		<tr>
			<td width="74">
				<?php 
				echo '
				<select name="qualite">
					<option value="Monsieur"
				';	
					if ((isset($infos['qualite'])) OR ($infos['qualite'] == 'Monsieur')) {echo 'selected';}
				echo '
					>Mr</option>
					<option value="Madame"
				';	
					if ($infos['qualite'] == 'Madame') {echo 'selected';}
				echo '
					>Mme</option>
					<option value="Mlle"
				';	
					if ($infos['qualite'] == 'Mlle') {echo 'selected';}
				echo '
					>Mlle</option>
				</select>
				';
				?>
			&nbsp;&nbsp;&nbsp; </td>
			<td width="75">Pr&eacute;nom</td>
			<td>
				<input type="text" size="20" name="sprenom" value="<?php echo $infos['sprenom']; ?>">
			</td>
			<td> Fonction 
			</td>
			<td>
				<input type="text" size="20" name="fonction" value="<?php echo $infos['fonction']; ?>">
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>Nom</td>
			<td><input type="text" size="20" name="snom" value="<?php echo $infos['snom']; ?>"></td>
			<td>Langue</td>
			<td>
				<?php 
				echo '<input type="radio" name="slangue" value="FR" '; if (($infos['slangue'] == '') OR ($infos['slangue'] == 'FR')) { echo 'checked';} echo'> FR';
				echo '<input type="radio" name="slangue" value="NL" '; if ($infos['slangue'] == 'NL') { echo 'checked';} echo'> NL';
				?>
			</td>
		</tr>
		<?php if ($infos['newweb'] == 1) { /* que si new web */ 
		# "newweb" value="0" ==> n'est pas ou plus un new
		# "newweb" value="1" ==> est un new
		?>
		<tr>
				<td>Valider ce <font color="red">New</font> POS ?</td>
				<td colspan="6">
					<input type="radio" name="newweb" value="0"> Oui
					<input type="radio" name="newweb" value="1" checked> Non
				</td>
		</tr>
		<?php } else { ?>
			
			<input type="hidden" name="newweb" value="0"> 
		<?php } ?>

		
	</table>
	<script type="text/javascript" charset="utf-8">

		function trim (myString)
		{
			return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
		}

		function disableDocPrefRadio()
		{
			var fax = document.getElementById('fax').value;
			var email = document.getElementById('email').value;

			email = trim(email);
			fax = trim(fax);
						
			if(fax == "")
			{
				document.getElementById("docprefFax").disabled = true;
			}
			else
			{
				document.getElementById("docprefFax").disabled = false;
			}
			if(email == "")
			{
				document.getElementById("docprefEmail").disabled = true;
			}
			else
			{
				document.getElementById("docprefEmail").disabled = false;
			}

		}

		disableDocPrefRadio();
	</script>
	<div class="infosection">EAS</div>
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">		<tr>
		<tr>
			<td>client CCFD</td>
			<td><input type="text" size="5" name="ccfd" value="<?php echo $infos['ccfd']; ?>"></td>
		</tr>
		<tr>
		    <td valign="top">EAS semaine type </td>
		    <td valign="top"><input type="text" size="5" name="eassemaine" value="<?php echo $infos['eassemaine']; ?>"></td>
		</tr>
	</table>
</div>

<div id="infobouton<?php if($_GET['embed']=='yes') echo '-embed'; ?>">
<?php
if (isset($idshop)) { ?>
	<input type="submit" name="modif" value="Modifier" accesskey="M">
	<?php if(!empty($idmerch) or !empty($_GET['from'])) ?> <input type="submit" name="select" value="Selection et retour">
<?php } else { ?>
	<input type="submit" name="act" value="delete"> 
<?php } ?>
</div>
</form>
