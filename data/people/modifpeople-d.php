<?php
define('NIVO', '../../');

## init vars
if (!isset($_REQUEST['s'])) $_REQUEST['s'] = '';
if (!isset($_POST['act'])) $_POST['act'] = '';

$titrepage = ($_REQUEST['s'] == '8')?'people2':'people';
# Classes utilisées
require NIVO."classes/test.php" ;
require NIVO."classes/payement.php" ;
require NIVO."classes/makehtml.php" ;
require NIVO."print/dispatch/dispatch_functions.php";
# Entete de page
include NIVO."includes/ifentete.php" ;

if ($_REQUEST['s'] != '8') echo '<div id="orangepeople">';
#####################################################################################################################
## Modification des infos people #######################################
	if ($_POST['act'] == 'Modifier') {
		# preparation des datas
		switch ($_REQUEST['s']) {
			case '1':
				$mailAndFax = $DB->getRow("SELECT email, fax FROM people WHERE idpeople=".$_REQUEST['idpeople']);
				if(
					($_POST['docpref'] == 'email' and empty($_POST['email'])) ||
					($_POST['docpref'] == 'fax' and empty($_POST['fax'])))
				{
					$_POST['docpref'] = 'post';
				}
				break;

			# Langues
			case '2':
				if(!empty($_POST['conninformatiq'])) $_POST['conninformatiq'] = (($_POST['conninformatiq'][0]=='1')?'1':'0').(($_POST['conninformatiq'][1]=='1')?'1':'0').(($_POST['conninformatiq'][2]=='1')?'1':'0').(($_POST['conninformatiq'][3]=='1')?'1':'0');
				break;

			# Hotesse
			case '3':
				$_POST['taille'] = cleannombreonly($_POST['taille']);
				break;

			# Social
			case '5':
				$_POST['precompte'] = mb_convert_encoding($_POST['precompte'], 'HTML-ENTITIES', 'UTF-8');
				break;
		}

		# maj de la DB
		$DB->MAJ("people");
	}
#####################################################################################################################
## Test de la validite des infos people #######################################
	if (isset($_REQUEST['idpeople'])) {
		$infos = $DB->getRow("SELECT * FROM `people` WHERE `idpeople` = ".$_REQUEST['idpeople']);

		$Verif = new test($infos['idpeople']);
		# Teste tous les champ !! ordre a de l'importance

		$Verif->checkPRE($infos['pprenom']);
		$Verif->checkNOM($infos['pnom']);
		$Verif->checkSEX($infos['sexe']);
		$Verif->checkRUE($infos['adresse1']);
		$Verif->checkLOC($infos['ville1']);
		$Verif->checkCPO($infos['cp1']);
		$Verif->checkNUR($infos['num1']);
		$Verif->checkNUB($infos['bte1']);

		if (empty($infos['idsupplier'])) {
			$Verif->checkCCC($infos['catsociale']);
			switch ($infos['modepay']) {
				case "1":
					$Verif->checkNCF($infos['banque']);
				break;

				case "3":
					$Verif->checkZ75($infos['iban']);
					$Verif->checkBIC($infos['bic']);
				break;
			}
			$Verif->checkNAT($infos['nationalite']);
			$Verif->checkNUI($infos['ncidentite']);
			$Verif->checkDTN($infos['ndate']);
			$Verif->checkNCP($infos['ncp'], $infos['npays']);
			$Verif->checkCPP($infos['pays1']);
			$Verif->checkRNA($infos['nrnational'], $infos['ndate'], $infos['sexe'], $infos['npays']);
			$Verif->checkETC($infos['etatcivil']);
			$Verif->checkLGE($infos['lbureau']);
			$Verif->checkAPC($infos['pacharge']);
			$Verif->checkNEC($infos['eacharge']);
		}

		$Verif->listerr();

		### Anotes le people si il y a des erreurs
		$errP = 0;

		if (count($Verif->ErrGS) >= 1) $errP += count($Verif->ErrGS);
		if (count($Verif->ErrDI) >= 1) $errP += count($Verif->ErrDI);

		if ($errP > 0) {
			$DB->inline("UPDATE `people` SET `err` ='Y' WHERE `idpeople` ='".$_REQUEST['idpeople']."' LIMIT 1;");
		} else {
			$DB->inline("UPDATE `people` SET `err` ='N' WHERE `idpeople` ='".$_REQUEST['idpeople']."' LIMIT 1;");
		}

		#### Recupere les infos People
		$infos = $DB->getRow("SELECT * FROM `people` WHERE `idpeople` = ".$_REQUEST['idpeople']);
	}


if ($_REQUEST['s'] < '6') { ?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
	<input type="hidden" name="s" value="<?php echo $_REQUEST['s'];?>">
	<input type="hidden" name="idpeople" value="<?php echo $_REQUEST['idpeople'];?>">
	<table border="0" class="standard" cellspacing="1" cellpadding="0" align="center" width="98%">
		<tr>
	<?php if ($_REQUEST['s'] == '0') { ?>
			<td width="100%">
				<!-- INFO Materiel -->
					<br>
					<table class="standard" border="0" cellspacing="1" cellpadding="0" width="98%">
						<tr>
							<td valign="top" height="150">
								<?php if ($_REQUEST['idpeople'] >= 1) { ?>
									<iframe frameborder="0" marginwidth="0" marginheight="0" name="matos" src="<?php echo 'matos.php?act=show&etat=0&idpeople='.$_REQUEST['idpeople'].'';?>" width="98%" height="150" align="top">Marche pas les IFRAMES !</iframe>
								<?php } ?>
							</td>
						</tr>
					</table>
				<!-- / INFO Materiel -->
			</td>
	<?php } ?>
	<?php if ($_REQUEST['s'] == '1') { ?>
			<td width="100%">
				<br>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td width="50%">
							<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
								<tr>
									<td> Tel </td>
									<td colspan="4">
										<input type="text" size="20" name="tel" value="<?php echo $infos['tel']; ?>">
									</td>
								</tr>
								<tr>
									<td> Fax </td>
									<td colspan="3">
										<input type="text" size="20" name="fax" id="fax"  onkeyup='disableDocPrefRadio()' value="<?php echo $infos['fax']; ?>">
									</td>
									<td>
										<input type="radio" name="docpref" value="email" id="docprefEmail"
											<?php if($infos['docpref'] == 'email' or empty($infos['docpref'])) 	echo "checked";  ?>>
											&nbsp;<img src="<?php echo NIVO.'illus/mail.png'; ?>"/>&nbsp;E-mail
										</input>
									</td>
								</tr>
								<tr>
									<td colspan="4"> &nbsp; </td>
									<td>
										<input type="radio" name="docpref" value="fax" id="docprefFax"
											<?php if($infos['docpref'] == 'fax') 	echo "checked";  ?>>
											&nbsp;<img src="<?php echo NIVO.'illus/telephone-fax.png'; ?>"/>&nbsp;Fax
										</input>
									</td>
								</tr>
								<tr>
									<td> GSM </td>
									<td colspan="3">
										<input type="text" size="20" name="gsm" value="<?php echo $infos['gsm']; ?>">
									</td>
									<td>
										<input type="radio" name="docpref" value="post" id="docprefPost"
											<?php if($infos['docpref'] == 'post') 	echo "checked";  ?>>
											&nbsp;<img src="<?php echo NIVO.'illus/printer.png'; ?>"/>&nbsp;Poste
										</input>
									</td>
								</tr>
								<tr>
									<td> Email </td>
									<td colspan="4">
										<input type="text" size="20" name="email" id="email"  onkeyup='disableDocPrefRadio()' value="<?php echo $infos['email']; ?>">
									</td>
								</tr>
							</table>
						</td>
						<td>
							<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
								<tr>
									<td valign="middle">
										<input type="submit" name="act" value="Modifier">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
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

	<?php } ?>
	<?php if ($_REQUEST['s'] == '2') { ?>
			<td>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							FR
						</td>
						<td>
							<?php
							echo '<input type="radio" name="lfr" value="0" '; if ($infos['lfr'] == '0') { echo 'checked';} echo'> 0';
							echo '<input type="radio" name="lfr" value="1" '; if ($infos['lfr'] == '1') { echo 'checked';} echo'> 1';
							echo '<input type="radio" name="lfr" value="2" '; if ($infos['lfr'] == '2') { echo 'checked';} echo'> 2';
							echo '<input type="radio" name="lfr" value="3" '; if ($infos['lfr'] == '3') { echo 'checked';} echo'> 3';
							echo '<input type="radio" name="lfr" value="4" '; if ($infos['lfr'] == '4') { echo 'checked';} echo'> 4';
							?>
						</td>
					</tr>
					<tr>
						<td>
							NL
						</td>
						<td>
							<?php
							echo '<input type="radio" name="lnl" value="0" '; if ($infos['lnl'] == '0') { echo 'checked';} echo'> 0';
							echo '<input type="radio" name="lnl" value="1" '; if ($infos['lnl'] == '1') { echo 'checked';} echo'> 1';
							echo '<input type="radio" name="lnl" value="2" '; if ($infos['lnl'] == '2') { echo 'checked';} echo'> 2';
							echo '<input type="radio" name="lnl" value="3" '; if ($infos['lnl'] == '3') { echo 'checked';} echo'> 3';
							echo '<input type="radio" name="lnl" value="4" '; if ($infos['lnl'] == '4') { echo 'checked';} echo'> 4';
							?>
						</td>
					</tr>
					<tr>
						<td>
							EN
						</td>
						<td>
							<?php
							echo '<input type="radio" name="len" value="0" '; if ($infos['len'] == '0') { echo 'checked';} echo'> 0';
							echo '<input type="radio" name="len" value="1" '; if ($infos['len'] == '1') { echo 'checked';} echo'> 1';
							echo '<input type="radio" name="len" value="2" '; if ($infos['len'] == '2') { echo 'checked';} echo'> 2';
							echo '<input type="radio" name="len" value="3" '; if ($infos['len'] == '3') { echo 'checked';} echo'> 3';
							echo '<input type="radio" name="len" value="4" '; if ($infos['len'] == '4') { echo 'checked';} echo'> 4';
							?>
						</td>
					</tr>





					<tr>
						<td>
							DU
						</td>
						<td>
							<?php
							echo '<input type="radio" name="ldu" value="0" '; if ($infos['ldu'] == '0') { echo 'checked';} echo'> 0';
							echo '<input type="radio" name="ldu" value="1" '; if ($infos['ldu'] == '1') { echo 'checked';} echo'> 1';
							echo '<input type="radio" name="ldu" value="2" '; if ($infos['ldu'] == '2') { echo 'checked';} echo'> 2';
							echo '<input type="radio" name="ldu" value="3" '; if ($infos['ldu'] == '3') { echo 'checked';} echo'> 3';
							echo '<input type="radio" name="ldu" value="4" '; if ($infos['ldu'] == '4') { echo 'checked';} echo'> 4';
							?>
						</td>
					</tr>
					<tr>
						<td>
							IT
						</td>
						<td>
							<?php
							echo '<input type="radio" name="lit" value="0" '; if ($infos['lit'] == '0') { echo 'checked';} echo'> 0';
							echo '<input type="radio" name="lit" value="1" '; if ($infos['lit'] == '1') { echo 'checked';} echo'> 1';
							echo '<input type="radio" name="lit" value="2" '; if ($infos['lit'] == '2') { echo 'checked';} echo'> 2';
							echo '<input type="radio" name="lit" value="3" '; if ($infos['lit'] == '3') { echo 'checked';} echo'> 3';
							echo '<input type="radio" name="lit" value="4" '; if ($infos['lit'] == '4') { echo 'checked';} echo'> 4';
							?>
						</td>
					</tr>
					<tr>
						<td>
							ES
						</td>
						<td>
							<?php
							echo '<input type="radio" name="lsp" value="0" '; if ($infos['lsp'] == '0') { echo 'checked';} echo'> 0';
							echo '<input type="radio" name="lsp" value="1" '; if ($infos['lsp'] == '1') { echo 'checked';} echo'> 1';
							echo '<input type="radio" name="lsp" value="2" '; if ($infos['lsp'] == '2') { echo 'checked';} echo'> 2';
							echo '<input type="radio" name="lsp" value="3" '; if ($infos['lsp'] == '3') { echo 'checked';} echo'> 3';
							echo '<input type="radio" name="lsp" value="4" '; if ($infos['lsp'] == '4') { echo 'checked';} echo'> 4';
							?>
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td colspan="3"> &nbsp; </td>
					</tr>
					<tr>
						<td colspan="3"> &nbsp; </td>
					</tr>
					<tr>
						<td> &nbsp; </td>
						<td colspan="2"> Notes pour Langues </td>
					</tr>
					<tr>
						<td> &nbsp; </td>
						<td colspan="2">
							<textarea name="notelangue" rows="4" cols="30"><?php echo $infos['notelangue']; ?></textarea>
						</td>
					</tr>
					<tr>
						<td colspan="2"> &nbsp; </td>
					</tr>
					<tr>
						<td> &nbsp; </td>
						<td> Informatique : </td>
						<td>
						<?php
						$conninformatiq = array(
							'0' => 'Word',
							'1' => 'Excel',
							'2' => 'Powerpoint',
							'3' => 'Internet'
						);
							echo createNumericCheckboxList('conninformatiq',$conninformatiq,$infos['conninformatiq']);
						?>
						</td>
					</tr>
				</table>
			</td>
	<?php } ?>
	<?php if ($_REQUEST['s'] == '3') { ?>
			<td>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td colspan="4"> &nbsp; </td>
					</tr>
					<tr>
						<td> Physionomie </td>
						<td colspan="3">
							<?php

							$physios = array(
								'occidental'    => 'Occidental',
								'slave'         => 'Slave',
								'asiatique'     => 'Asiatique',
								'orientale'     => 'Orientale',
								'black'         => 'Black',
								'nordafricain'  => 'Nord-africain',
								'hispanique'    => 'Hispanique',
								'mediterraneen' => 'M&eacute;dit&eacute;rran&eacute;en'
							);


								echo '<select name="physio">';

								foreach ($physios as $key => $value) {

									echo '<option value="'.$key.'"';
									if ($infos['physio'] == $key) {echo 'selected';}
									echo '>'.$value.'</option>';
								}

								echo '</select>';
							?>
							<?php echo $physios[$infos['physio']]; ?>
						</td>
					</tr>
					<tr>
						<td colspan="4"> &nbsp; </td>
					</tr>
					<tr>
						<td> Couleur Cheveux </td>
						<td colspan="3">
							<?php
							$coulcheveux = array(
								'blond'   => 'Blond',
								'brun'    => 'Brun',
								'noir'    => 'Noir',
								'chatain' => 'Chatain',
								'roux'    => 'Roux'
							);

								echo '<select name="ccheveux">';

								foreach ($coulcheveux as $key => $value) {
									echo '<option value="'.$key.'"';
									if ($infos['ccheveux'] == $key) {echo 'selected';}
									echo '>'.$value.'</option>';
								}

								echo '</select>';
							?>
							<?php echo $coulcheveux[$infos['ccheveux']]; ?>
						</td>
					</tr>
					<tr>
						<td> Longueur cheveux </td>
						<td colspan="3">
							<?php
								echo '<select name="lcheveux" size="1">
									<option value="long"';if ($infos['lcheveux'] == 'long') {echo 'selected';}echo '>long</option>
									<option value="mi-long"';if ($infos['lcheveux'] == 'mi-long') {echo 'selected';}echo '>mi-long</option>
									<option value="court"';if ($infos['lcheveux'] == 'court') {echo 'selected';}echo '>court</option>
									<option value="rase"';if ($infos['lcheveux'] == 'rase') {echo 'selected';}echo '>ras&eacute;</option>
								</select>
								';
							?>
							<?php echo $infos['lcheveux']; ?>
						</td>
					</tr>
					<tr>
						<td colspan="4"> &nbsp; </td>
					</tr>
					<tr>
						<td> Taille </td>
						<td colspan="3">
							<input type="text" size="3" name="taille" value="<?php echo $infos['taille']; ?>"> cm
						</td>
					</tr>
					<tr>
						<td> Taille Veste </td>
						<td colspan="3">
							<?php
								echo '<select name="tveste" size="1">
									<option value="34"';if ($infos['tveste'] == '34') {echo 'selected';}echo '>34</option>
									<option value="36"';if ($infos['tveste'] == '36') {echo 'selected';}echo '>36</option>
									<option value="38"';if ($infos['tveste'] == '38') {echo 'selected';}echo '>38</option>
									<option value="40"';if ($infos['tveste'] == '40') {echo 'selected';}echo '>40</option>
									<option value="42"';if ($infos['tveste'] == '42') {echo 'selected';}echo '>42</option>
									<option value="44"';if ($infos['tveste'] == '44') {echo 'selected';}echo '>44</option>
									<option value="50"';if ($infos['tveste'] == '50') {echo 'selected';}echo '>50</option>
									<option value="52"';if ($infos['tveste'] == '52') {echo 'selected';}echo '>52</option>
									<option value="54"';if ($infos['tveste'] == '54') {echo 'selected';}echo '>54</option>
									<option value="56"';if ($infos['tveste'] == '56') {echo 'selected';}echo '>56</option>
									<option value="L"';if ($infos['tveste'] == 'L') {echo 'selected';}echo '>L</option>
									<option value="XL"';if (($infos['tveste'] == 'XL') or (($infos['sexe'] == 'm') and ($infos['tveste'] == ''))) {echo 'selected';}echo '>XL</option>
								';
								echo '
								</select>
								';
							?>
							<?php echo $infos['tveste']; ?>
						</td>
					</tr>
					<tr>
						<td> Taille Jupe </td>
						<td colspan="3">
							<?php
								echo '<select name="tjupe" size="1">
									<option value="34"';if ($infos['tjupe'] == '34') {echo 'selected';}echo '>34</option>

									<option value="36"';if ($infos['tjupe'] == '36') {echo 'selected';}echo '>36</option>
									<option value="38"';if ($infos['tjupe'] == '38') {echo 'selected';}echo '>38</option>
									<option value="40"';if ($infos['tjupe'] == '40') {echo 'selected';}echo '>40</option>
									<option value="42"';if ($infos['tjupe'] == '42') {echo 'selected';}echo '>42</option>
									<option value="44"';if ($infos['tjupe'] == '44') {echo 'selected';}echo '>44</option>
									<option value="50"';if ($infos['tjupe'] == '50') {echo 'selected';}echo '>50</option>
									<option value="52"';if ($infos['tjupe'] == '52') {echo 'selected';}echo '>52</option>
									<option value="54"';if ($infos['tjupe'] == '54') {echo 'selected';}echo '>54</option>
									<option value="56"';if ($infos['tjupe'] == '56') {echo 'selected';}echo '>56</option>
								';
									if ($infos['sexe'] == 'm') { echo '<option value="" selected>male</option>';}
								echo '
								</select>
								';
							?>
							<?php echo $infos['tjupe']; ?>
						</td>
					</tr>
					<tr>
						<td> Pointure </td>
						<td colspan="3">
							<input type="text" size="3" name="pointure" value="<?php echo $infos['pointure']; ?>">
						</td>
					</tr>
					<tr>
						<td colspan="4"> &nbsp; </td>
					</tr>
				</table>
			</td>
			<td>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td colspan=4>
						<fieldset>
							<legend>Mensurations</legend>
							<label>Poitrine</label><input type="text" size="4" name="menspoi" value="<?php echo $infos['menspoi']; ?>"><br>
							<label>Taille</label><input type="text" size="4" name="menstai" value="<?php echo $infos['menstai']; ?>"><br>
							<label>Hanche</label><input type="text" size="4" name="menshan" value="<?php echo $infos['menshan']; ?>"><br>
						</fieldset>
						</td>
					</tr>
					<tr>
						<td> &nbsp; </td>
					</tr>
					<tr>
						<td> Fume </td>
						<td colspan="3">
							<?php
							echo '<input type="radio" name="fume" value="non" '; if (($infos['fume'] == 'non') or ($infos['fume'] == '')) { echo 'checked';} echo'> Non';
							echo '<input type="radio" name="fume" value="oui" '; if ($infos['fume'] == 'oui') { echo 'checked';} echo'> Oui';
							?>
						</td>
					</tr>
					<tr>
						<td> &nbsp; </td>
					</tr>
					<tr>
						<td> Permis </td>
						<td colspan="3">
							<?php
							echo '<input type="radio" name="permis" value="non" '; if (($infos['permis'] == 'non') or ($infos['permis'] == '')) { echo 'checked';} echo'> Non';
							echo '<input type="radio" name="permis" value="A" '; if ($infos['permis'] == 'A') { echo 'checked';} echo'> A';
							echo '<input type="radio" name="permis" value="B" '; if ($infos['permis'] == 'B') { echo 'checked';} echo'> B';
							echo '<input type="radio" name="permis" value="C" '; if ($infos['permis'] == 'C') { echo 'checked';} echo'> C';
							echo '<input type="radio" name="permis" value="D" '; if ($infos['permis'] == 'D') { echo 'checked';} echo'> D';
							echo '<input type="radio" name="permis" value="E" '; if ($infos['permis'] == 'E') { echo 'checked';} echo'> E';
							?>
						</td>
					</tr>
					<tr>

						<td> Voiture </td>
						<td colspan="3">
							<?php
							echo '<input type="radio" name="voiture" value="non" '; if ($infos['voiture'] == 'non') { echo 'checked';} echo'> Non';
							echo '<input type="radio" name="voiture" value="oui" '; if ($infos['voiture'] == 'oui') { echo 'checked';} echo'> Oui';
							?>
						</td>
					</tr>
					<tr>
						<td colspan="4"> &nbsp; </td>
					</tr>

					<tr>
						<td> Be </td>
						<td colspan="3">
							<?php
							echo '<input type="radio" name="beaute" value="1" '; if ($infos['beaute'] == '1') { echo 'checked';} echo'> 1';
							echo '<input type="radio" name="beaute" value="2" '; if ($infos['beaute'] == '2') { echo 'checked';} echo'> 2';
							echo '<input type="radio" name="beaute" value="3" '; if ($infos['beaute'] == '3') { echo 'checked';} echo'> 3';
							echo '<input type="radio" name="beaute" value="4" '; if ($infos['beaute'] == '4') { echo 'checked';} echo'> 4';
							?>
						</td>
					</tr>
					<tr>
						<td> Ch </td>
						<td colspan="3">
							<?php
							echo '<input type="radio" name="charme" value="1" '; if ($infos['charme'] == '1') { echo 'checked';} echo'> 1';
							echo '<input type="radio" name="charme" value="2" '; if ($infos['charme'] == '2') { echo 'checked';} echo'> 2';
							echo '<input type="radio" name="charme" value="3" '; if ($infos['charme'] == '3') { echo 'checked';} echo'> 3';
							echo '<input type="radio" name="charme" value="4" '; if ($infos['charme'] == '4') { echo 'checked';} echo'> 4';
							?>
						</td>
					</tr>
					<tr>
						<td> Dy </td>
						<td colspan="3">
							<?php
							echo '<input type="radio" name="dynamisme" value="1" '; if ($infos['dynamisme'] == '1') { echo 'checked';} echo'> 1';
							echo '<input type="radio" name="dynamisme" value="2" '; if ($infos['dynamisme'] == '2') { echo 'checked';} echo'> 2';
							echo '<input type="radio" name="dynamisme" value="3" '; if ($infos['dynamisme'] == '3') { echo 'checked';} echo'> 3';
							echo '<input type="radio" name="dynamisme" value="4" '; if ($infos['dynamisme'] == '4') { echo 'checked';} echo'> 4';
							?>
						</td>
					</tr>
			</table>
			</td>
	<?php } ?>
	<?php if ($_REQUEST['s'] == '4') {
			dispatcher_feedback("people",$_GET['idpeople']);
		} ?>

	<?php if ($_REQUEST['s'] == '5') { ?>
			<td>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
					<tr>
						<th class="vip" colspan="2">Naissance</th>
					</tr>
					<tr>
						<td>
							<?php $Verif->erraffiche('Date de Naissance', 'Date') ;?>
						</td>
						<td>
							<input type="text" size="20" name="ndate" value="<?php echo fdate($infos['ndate']); ?>">
						</td>
					</tr>
					<tr>
						<td> Age </td>
						<td> <?php echo age($infos['ndate']); ?> </td>
					</tr>
					<tr>
						<td>
							<?php $Verif->erraffiche('Code Postal Naissance', 'CP - Loca') ;?>
						</td>
						<td>
							<input type="text" size="4" name="ncp" value="<?php echo $infos['ncp']; ?>">
							<input type="text" size="15" name="nville" value="<?php echo $infos['nville']; ?>">

						</td>
					</tr>
					<tr>
						<td>
							<?php $Verif->erraffiche('Pays de Naissance', 'Pays') ;?>
						</td>
						<td>

						<?php
	    		include(NIVO."conf/pays.php");

				echo '<select name="npays">';

				foreach ($iso_pays_fr as $key => $value) {
					echo '<option value="'.$key.'"';
					if ($infos['npays'] == $key) {echo 'selected';}
					if (($infos['npays'] == '') and ($key == 'BE')) {echo 'selected';}
					echo '>'.$value.'</option>';
				}

				echo '</select>';
?>
						</td>
					</tr>
				</table>
				<br>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
					<tr>
						<th class="vip" colspan="2">Service</th>
					</tr>
					<tr>
						<td>
							<?php $Verif->erraffiche('Date d\'entrée en service', 'Date ENTREE'); ?>
						</td>
						<td>
							<input type="text" size="20" name="dateentree" value="<?php echo fdate($infos['dateentree']); ?>">
						</td>
					</tr>
					<tr>

						<td>
							Date SORTIE
						</td>
						<td>
							<input type="text" size="20" name="datesortie" value="<?php echo fdate($infos['datesortie']); ?>">
						</td>
					</tr>
				</table>
				<br>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
					<tr>
						<td>
							<?php $Verif->erraffiche('Registre Personnel', 'N Reg Pers') ;?>
						</td>
						<td>
							<?php echo $infos['codepeople']; ?>
						</td>
					</tr>
					<tr>
						<td>
							Ancien N Reg Pers
						</td>
						<td>
							<input type="text" size="20" name="noteregistre" value="<?php echo $infos['noteregistre']; ?>">
						</td>
					</tr>
				</table>
			</td>


			<td>
				<br>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
					<tr>
						<td>
							<?php $Verif->erraffiche('Categorie Sociale', 'Cat Sociale') ;?>
						</td>
					</tr>
					<tr>
						<td>

<?php
	 	$catsoc = array( # Liste des catégories sociales
					'1' => 'Employ&eacute;',
					'E' => 'Etudiant',
					'3' => 'Ind&eacute;pendant',
 				);

				echo '<select name="catsociale">';

				foreach ($catsoc as $key => $value) {
					echo '<option value="'.$key.'"';
					if ($infos['catsociale'] == $key) {echo 'selected';}
					if (($infos['catsociale'] == '') and ($key == '1')) {echo 'selected';}
					echo '>'.$value.'</option>';
				}

				echo '</select>';
?>
						</td>
					</tr>
				</table>
				<br>
				<br>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
					<tr>
						<td>
							<?php $Verif->erraffiche('Numéro Carte Identité', 'N&deg; Carte d&rsquo;identit&eacute;') ;?>
						</td>
					</tr>
					<tr>
						<td>
							<input type="text" size="20" name="ncidentite" value="<?php echo $infos['ncidentite']; ?>">
						</td>
					</tr>
					<tr>
						<td>
							<?php $Verif->erraffiche('Registre National', 'N&deg; Reg national') ;?>
						</td>
					</tr>
					<tr>
						<td>
							<input type="text" size="20" name="nrnational" value="<?php echo $infos['nrnational']; ?>">
						</td>
					</tr>
					<tr>
						<td>
							<?php $Verif->erraffiche('Nationalité', 'Nationalit&eacute;') ;?>
						</td>
					</tr>
					<tr>
						<td>
<?php
	 	$natios = array( # Liste des nationalités Permises par Groupe-S


 				'A' => 'Alg&eacute;rienne',
 				'B' => 'Belge',
 				'C' => 'Za&iuml;roise',
 				'D' => 'Allemande',
 				'E' => 'Espagnole',
 				'F' => 'Fran&ccedil;aise',
 				'G' => 'Britannique',
 				'H' => 'Grecque',
 				'I' => 'Italienne',
 				'J' => 'Danoise',
 				'K' => 'Apatride',
 				'L' => 'Luxembourgeoise',
 				'M' => 'Marocaine',
 				'N' => 'N&eacute;erlandaise',
 				'O' => 'Polonaise',
 				'P' => 'Portugaise',
 				'Q' => 'Irlandaise',
 				'R' => 'Autres pays europ&eacute;ens',
 				'S' => 'Helv&eacute;tique',
 				'T' => 'Turque',
 				'U' => 'Tunisienne',
 				'V' => 'Autres pays africains',
 				'W' => 'Asie (tous pays)',
 				'X' => 'Am&eacute;rique (tous pays)',
 				'Y' => 'Yougoslavie',
 				'Z' => 'Oc&eacute;anie (tous pays)',
 				'9' => 'R&eacute;fugi&eacute; politique');



				echo '<select name="nationalite">';

				foreach ($natios as $key => $value) {
					echo '<option value="'.$key.'"';
					if ($infos['nationalite'] == $key) {echo 'selected';}
					if (($infos['nationalite'] == '') and ($key == 'B')) {echo 'selected';}
					echo '>'.$value.'</option>';
				}

				echo '</select>';
?>
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
					<tr>
						<th class="vip" colspan="4">Famille</th>
						<td colspan="3">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>

							<?php $Verif->erraffiche('Etat Civil', 'Etat civil') ;?>
						</td>
						<td colspan="3">
				<?php
				echo '
				<select name="etatcivil">
					<option value="1"
				';
					if (($infos['etatcivil'] == '') or ($infos['etatcivil'] == '1')) {echo 'selected';}
				echo '
					>C&eacute;libataire</option>
					<option value="2"
				';
					if ($infos['etatcivil'] == '2') {echo 'selected';}
				echo '
					>Mari&eacute;</option>
					<option value="3"
				';
					if ($infos['etatcivil'] == '3') {echo 'selected';}
				echo '
					>Veuf</option>
					<option value="4"
				';
					if ($infos['etatcivil'] == '4') {echo 'selected';}
				echo '
					>Divorc&eacute;</option>
					<option value="5"
				';
					if ($infos['etatcivil'] == '5') {echo 'selected';}
				echo '
					>S&eacute;par&eacute;</option>
				</select>
				';
				?>
						</td>
					</tr>
					<tr>
						<td> Date Mariage </td>
						<td colspan="3">
							<input type="text" size="20" name="datemariage" value="<?php echo fdate($infos['datemariage']); ?>">
						</td>
					</tr>
					<tr>
						<td> Nom Conjoint </td>
						<td colspan="3">
							<input type="text" size="20" name="nomconjoint" value="<?php echo $infos['nomconjoint']; ?>">
						</td>
					</tr>
					<tr>
						<td> Date Nais. Conj. </td>
						<td colspan="3">
							<input type="text" size="20" name="dateconjoint" value="<?php echo fdate($infos['dateconjoint']); ?>">
						</td>
					</tr>
					<tr>
						<td> Job Conj. </td>
						<td colspan="3">
<?php
				$jobconjoint_list = array(
					'0' => 'Inconnue',
					'1' => 'Ouvrier',
					'2' => 'Gens De Maison',
					'3' => 'Employe',
					'4' => 'Independant',
					'5' => 'Ouvrier Des Mines',
					'6' => 'Marin',
					'7' => 'Travailleur Des Services Publics',
					'8' => 'Autre',
					'9' => 'Sans',
				);

				echo '<select name="jobconjoint">';

				foreach ($jobconjoint_list as $key => $value) {
					echo '<option value="'.$key.'"'.((($infos['jobconjoint'] == $key) || (empty($infos['jobconjoint']) && ($key == '0'))) ? 'selected':'').'>'.$value.'</option>';
				}

				echo '</select>';
?>
						</td>
					</tr>

					<tr>
						<td>
							<?php $Verif->erraffiche('Personnes a charge', 'Pers &agrave; Ch.') ;?>
						</td>
						<td>
							<input type="text" size="2" name="pacharge" value="<?php echo $infos['pacharge']; ?>">
						</td>
						<td>
							<?php $Verif->erraffiche('Enfants a charge', 'Enf &agrave; Ch.') ;?>
						</td>
						<td>
							<input type="text" size="2" name="eacharge" value="<?php echo $infos['eacharge']; ?>">
						</td>
					</tr>
				</table>
				<br>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
					<tr>
						<th class="vip" colspan="4">Payement</th>
					</tr>
					<tr>
						<td>Mode de paiement</td>
						<td colspan="3">
<?php
	 	$modspay = array( # Liste des modes de paiement
 				'1' => 'Compte Bancaire',
 				'2' => 'Ch&egrave;que',
 				'3' => 'IBAN BIC',
 				'4' => 'Comptant');

				echo '<select name="modepay">';

				foreach ($modspay as $key => $value) {
					echo '<option value="'.$key.'"';
					if ($infos['modepay'] == $key) {echo 'selected';}
					if (($infos['modepay'] == '') and ($key == '1')) {echo 'selected';}
					echo '>'.$value.'</option>';
				}


				echo '</select>';
?>
						</td>
					</tr>
					<tr>

<?php

switch ($infos['modepay']) {

		case "1":

?>
						<td>
							<?php $Verif->erraffiche('Compte Bancaire', 'Compte') ;?>
						</td>
						<td colspan="3">
							<input type="text" size="20" name="banque" value="<?php echo $infos['banque']; ?>">
						</td>
<?php
		break;

		case "3":
?>
						<td>
							<?php $Verif->erraffiche('Code IBAN', 'IBAN') ;?><br>
							<?php $Verif->erraffiche('Code BIC', 'BIC') ;?>
						</td>
						<td colspan="3">
							<input type="text" size="20" name="iban" value="<?php echo $infos['iban']; ?>"><br>
							<input type="text" size="20" name="bic" value="<?php echo $infos['bic']; ?>">
						</td>
<?php
		break;
}


?>
					</tr>

					<tr>
						<td>
							Tarif Brut
						</td>
						<td colspan="3">
<?php
	echo feuro(salaire($infos['idpeople'], date("Y-m-d")))."&nbsp;";

	if ((@$_SESSION['roger'] == 'devel') || (@$_SESSION['idagent'] == '20')) {
		## Modif du bareme salaire
		echo '<select name="salaire">';

		echo '<option value="0" ';
		if ($infos['salaire'] <= age($infos['ndate'])) echo 'selected';
		echo '>AUTO</option>';

		for ($i = 1; $i <= 5; $i++) {

			$lage = age($infos['ndate']) + $i;
			$value = $lage.' ans ';


			echo '<option value="'.$lage.'"';
			if ($infos['salaire'] == $lage) {echo 'selected';}
			echo '>'.$value.'</option>';

		}
		echo '</select>';
	} else {
		echo '<input type="hidden" name="salaire" value="'.$infos['salaire'].'">';
	}
?>
						</td>
					</tr>
					<tr>
						<td>Précompte</td>
						<td>
							<input type="text" size="20" size="10" name="precompte" value="<?php echo html_entity_decode($infos['precompte']); ?>"> (XX% ou XX&euro;)
						</td>
					</tr>
				</table>
			</td>
	<?php } ?>
		</tr>
	</table>
	<?php if ($_REQUEST['s'] > '1') { ### pas pour contact ==> alignement gaucgh pour materiel ?>
	<div align="center"><input type="submit" name="act" value="Modifier"></div>
	<?php } ?>
</form>
<?php
}
else
{
### POUR EXPERIENCE VIP MERCH ANIM
if ($_REQUEST['s'] == '6') {
	include 'experiencepeople.php';
}
#/ ## POUR EXPERIENCE VIP MERCH ANIM

### POUR NON-EXPERIENCE VIP MERCH ANIM
if ($_REQUEST['s'] == '7') {
	include 'nonexperiencepeople.php';
}
#/ ## POUR EXPERIENCE VIP MERCH ANIM

#### Onglet Disponibilités
	if ($_REQUEST['s'] == '8') {
		$pagevar = NIVO.'webpeople/varfr.php';
		include $pagevar;
		include NIVO.'webpeople/dispo2.php';
	}
}

if($_REQUEST['s']=='9')
{
	switch($_REQUEST['act'])
	{
		case "show" :
			include NIVO."data/people/v-Vacances.php";
		break;
		case "add" :

			$DB->inline("INSERT INTO peoplevac (idpeople) VALUES (".$_REQUEST['idpeople'].")");
			include NIVO."data/people/v-Vacances.php";

		break;
		case "update" :
		echo "ici";
			$DB->MAJ("peoplevac");
			include NIVO."data/people/v-Vacances.php";

		break;
		case "delete" :
			$DB->inline("UPDATE peoplevac SET etat='out' WHERE idpeoplevac = ".$_REQUEST['idpeoplevac']);
			include NIVO."data/people/v-Vacances.php";

		break;
	}
}

if ($_REQUEST['s'] != '8') echo '</div>';

# Pied de Page
include NIVO."includes/ifpied.php" ;
?>