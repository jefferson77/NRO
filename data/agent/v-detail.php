<?php
## get datas
if (!empty($_REQUEST['id'])) {
	$infos = $DB->getRow("SELECT * FROM `agent` WHERE `idagent` = ".$_GET['id']);
} else $infos = array();
?>
<div id="centerzonelarge">
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
		<input type="hidden" name="idagent" value="<?php echo $infos['idagent'];?>">
		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="80%">
			<tr>
				<td>Qualité</td>
				<td>
					<select name="qualite">
					<?php 
					echo '<option value="Monsieur"'.((($infos['qualite'] == '') OR ($infos['qualite'] == 'Monsieur'))?'selected':'').'>Monsieur</option>';
					echo '<option value="Madame"'.(($infos['qualite'] == 'Madame')?'selected':'').'>Madame</option>';
					echo '<option value="Mademoiselle"'.(($infos['qualite'] == 'Mademoiselle')?'selected':'').'>Mademoiselle</option>';
					?>
					</select>
				</td>
				<td rowspan="14"><img src="<?php echo Conf::read('WebSiteURL') ?>illus/agents/<?php echo getAgentIllu($infos['idagent']) ?>.png" width="58" height="108" alt="69"></td>
			</tr>
			<tr>
				<td>Nom</td>
				<td><input type="text" name="nom" size="30" value="<?php echo $infos['nom']; ?>"></td>
			</tr>
			<tr>
				<td>Pr&eacute;nom</td>
				<td><input type="text" name="prenom" size="30" value="<?php echo $infos['prenom']; ?>"></td>
			</tr>
			<tr>
				<td>Fonction</td>
				<td><input type="text" name="fonction" size="60" value="<?php echo $infos['fonction']; ?>"></td>
			</tr>
			<tr>
				<td>Login</td>
				<td><input type="text" name="login" size="10" value="<?php echo $infos['login']; ?>"></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input type="text" name="pass" size="10" value="<?php echo $infos['pass']; ?>"></td>
			</tr>
			<tr>
				<td>E-mail</td>
				<td><input type="text" name="email" size="40" value="<?php echo $infos['email']; ?>"></td>
			</tr>
			<tr>
				<td>T&eacute;l&eacute;phone</td>
				<td><input type="text" name="atel" size="20" value="<?php echo $infos['atel']; ?>"></td>
			</tr>
			<tr>
				<td>GSM</td>
				<td><input type="text" name="agsm" size="20" value="<?php echo $infos['agsm']; ?>"></td>
			</tr>
			<tr>
				<td>Secteur</td>
				<td>
					<?php
						echo '<input type="radio" name="secteur" value="Anim" '; if ($infos['secteur'] == 'Anim') { echo 'checked';} echo'> Anim &nbsp;';
						echo '<input type="radio" name="secteur" value="Merch" '; if ($infos['secteur'] == 'Merch') { echo 'checked';} echo'> Merch &nbsp; ';
						echo '<input type="radio" name="secteur" value="Hotes" '; if ($infos['secteur'] == 'Hotes') { echo 'checked';} echo'> Hotes';
						echo '<input type="radio" name="secteur" value="Admin" '; if ($infos['secteur'] == 'Admin') { echo 'checked';} echo'> Admin';
						echo '<input type="radio" name="secteur" value="IT" '; if ($infos['secteur'] == 'IT') { echo 'checked';} echo'> IT';
						echo '<input type="radio" name="secteur" value="" '; if ($infos['secteur'] == '') { echo 'checked';} echo'> None';
					?>
				</td>
			</tr>

			<tr>
				<td>Niveau d'acc&egrave;s</td>
				<td>
<?php
					echo '<input type="radio" name="adlevel" value="user" '; if ($infos['adlevel'] == 'user' or empty($infos['adlevel'])) { echo 'checked';} echo'> Utilisateur &nbsp;';
					echo '<input type="radio" name="adlevel" value="admin" '; if ($infos['adlevel'] == 'admin') { echo 'checked';} echo'> Administrateur &nbsp; ';
		if ($_SESSION['roger'] == 'devel') { 
					echo '<input type="radio" name="adlevel" value="devel" '; if ($infos['adlevel'] == 'devel') { echo 'checked';} echo'> D&eacute;veloppeur &nbsp; ';
		}
?>
				</td>
			</tr>
			<tr>
				<td>Statut</td>
				<td>
<?php                                
					echo '<input type="radio" name="isout" value="N" '; if ($infos['isout'] == 'N' or empty($infos['isout'])) { echo 'checked';} echo'> In &nbsp;';
					echo '<input type="radio" name="isout" value="Y" '; if ($infos['isout'] == 'Y') { echo 'checked';} echo'> Out &nbsp; ';
?>
				</td>
			</tr>
			<tr>
				<td>Online (apparaît sur le site)</td>
				<td>
<?php                                
					echo '<input type="radio" name="onsite" value="oui" '; if ($infos['onsite'] == 'oui' or empty($infos['onsite'])) { echo 'checked';} echo'> Oui &nbsp;';
					echo '<input type="radio" name="onsite" value="non" '; if ($infos['onsite'] == 'non') { echo 'checked';} echo'> Non &nbsp; ';
?>
				</td>
			</tr>
			<tr>
				<td colspan="2"><hr></td>
			</tr>
			<tr>
				<td>ID people</td>
				<td><input type="text" name="idpeople" id="pname" size="10" value="<?php echo $infos['idpeople']; ?>"></td>
			</tr>
			<tr>
				<td>Send Dimona auto</td>
				<td>
<?php                                
					echo '<input type="radio" name="dimonaauto" value="yes" '; if ($infos['dimonaauto'] == 'yes' or empty($infos['dimonaauto'])) { echo 'checked';} echo'> Oui &nbsp;';
					echo '<input type="radio" name="dimonaauto" value="no" '; if ($infos['dimonaauto'] == 'no') { echo 'checked';} echo'> Non &nbsp; ';
?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<?php if (isset($infos['idagent'])) { ?>
						<input type="submit" name="act" value="Modifier">  -
						<input type="submit" name="act" value="Supprimer">
					<?php } else { ?>
						<input type="submit" name="act" value="Ajouter">
					<?php } ?>
				</td>
			</tr>
		</table>
	</form>
</div>
<script type="text/javascript" charset="utf-8">
	function formatResult(row) {return row[0];}
	function formatItem(row) {return "("+row[0]+") "+row[1];}
	function formatItemPeople(row) {return "("+row[2]+") "+row[1];}

	$("input#pname").autocomplete("<?php echo Conf::read('Env.urlroot') ?>query/peoplebyname.php?mode=full", {
		inputClass: 'autocomp',
		width: 250,
		minChars: 2,
		formatItem: formatItemPeople,
		formatResult: formatResult,
		delay: 200
	});
</script>