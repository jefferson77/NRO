<div class="news">
			<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="fulltitre" colspan="2">Infos</td>
				</tr>
				<tr>
					<td class="newstit"><?php echo $menu_new_01; ?></td>
					<td class="newsdat"></td>
				</tr>
				<tr>
					<td class="newstxt" colspan="2">
						<br>
						<li><b><?php echo $menu_new_02; ?></b><br>
						<?php echo $menu_new_03; ?><br><br>
						<li><b><?php echo $menu_new_04; ?></b><br>
						<?php echo $menu_new_05; ?><br><br>
						<li><b><?php echo $menu_new_06; ?></b><br>
						<?php echo $menu_new_07; ?><br>
						<?php echo $menu_new_08; ?><br>
						<?php echo $menu_new_09; ?>
						<br><br>
					</td>
				</tr>
			</table>
</div>
<div class="corps">
	<table width="99%" border="0" align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="newstit">
				<?php echo $_SESSION['qualite'].' '.$_SESSION['prenom'].' '.$_SESSION['nom'].', '.$menu_00.' '.$_SESSION['societe']; ?>
				<?php #echo '<br>idwebclient = '.$_SESSION['idwebclient'].' idclient = '.$_SESSION['idclient'].' idcofficer = '.$_SESSION['idcofficer'].' new = '.$_SESSION['new'].' newvip ='.$_SESSION['newvip']; ?>
			</td>
		</tr>
	</table>
	<br>
<?php
$detailid = new db('webclient', 'idwebclient', 'webneuro');
$detailid->inline("SELECT * FROM `webclient` WHERE `idwebclient` = $idwebclient");
$infosid = mysql_fetch_array($detailid->result) ; 
$idwebclient = $infosid['idwebclient'];	
$etat = $infosid['etat'];	

if ($etat < 5) {
if ($s == '') {$s = 0;}
################### Fin Code PHP ########################
$validez = 'Valider'; # pour le bouton du formulaire
?>
		<form action="adminclient.php?act=modifcontact" method="post">
			<input type="hidden" name="idwebpeople" value="<?php echo $idwebpeople;?>"> 
			<input type="hidden" name="s" value="<?php echo $s;?>">
	<Fieldset class="width">
		<legend class="width"><?php echo $contact_01; ?></legend>
				<table class="standard" border="0" cellspacing="1" cellpadding="4" align="center" width="98%">
					<tr>
						<td colspan="2" class="etiq2">
							<?php echo $contact_02; ?>
						</td>
						<td class="contenu">
							<input type="text" size="20" name="societe" value="<?php echo $infosid['societe']; ?>">
						</td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td class="contenu">
							<?php 
							echo '
							<select name="qualite">
								<option value="Monsieur"
							';	
								if ((isset($infosid['qualite'])) OR ($infosid['qualite'] == 'Monsieur')) {echo 'selected';}
							echo '
								>'.$contact_03.'</option>
								<option value="Madame"
							';	
								if ($infosid['qualite'] == 'Madame') {echo 'selected';}
							echo '
								>'.$contact_04.'</option>
								<option value="Mlle"
							';	
								if ($infosid['qualite'] == 'Mlle') {echo 'selected';}
							echo '
								>'.$contact_05.'</option>
							</select>
							';
							?>
						</td>
						<td class="etiq2">
							<?php echo $contact_06; ?>
						</td>
						<td class="contenu">
							<input type="text" size="14" name="cprenom" value="<?php echo $infosid['cprenom']; ?>">
						</td>
						<td class="etiq2">
							<?php echo $contact_07; ?>
						</td>
						<td>
							<input type="text" size="20" name="cnom" value="<?php echo $infosid['cnom']; ?>">
						</td>
					</tr>
					<tr>
						<td colspan="2" class="etiq2">
							<?php echo $contact_08; ?>
						</td>
						<td class="contenu">
							<input type="text" size="20" name="fonction" value="<?php echo $infosid['fonction']; ?>">
						</td>
						<td class="etiq2">
							<?php echo $contact_09; ?>
						</td>
						<td class="contenu">
							<?php 
							echo '<input type="radio" name="langue" value="FR" '; if (($infosid['langue'] == '') OR ($infosid['langue'] == 'FR')) { echo 'checked';} echo'> FR';
							echo '<input type="radio" name="langue" value="NL" '; if ($infosid['langue'] == 'NL') { echo 'checked';} echo'> NL';
							?>
						</td>
					</tr>
					<tr>
						<td colspan="5">
						</td>
					</tr>
					<tr>
						<td colspan="2" class="etiq2">
							<?php echo $contact_10; ?>
						</td>
						<td colspan="3" class="contenu">
							<input type="text" size="35" name="adresse" value="<?php echo $infosid['adresse']; ?>">
						</td>
					</tr>
					<tr>
						<td colspan="2" class="etiq2">
							<?php echo $contact_11; ?>
						</td>
						<td class="contenu">
							<input type="text" size="20" name="cp" value="<?php echo $infosid['cp']; ?>">
						</td>
						<td class="etiq2">
							<?php echo $contact_12; ?>
						</td>
						<td class="contenu">
							<?php
								if ((!empty($infosid['cp'])) and (is_numeric($infosid['cp']))) {
								$cpbcode = $infosid['cp'];
								$detail1 = new db();
								$detail1->inline("SELECT * FROM `codepost` WHERE `cpbcode`=$cpbcode");
								echo '<select name="ville">';
									if (!empty($infosid['ville'])) 
									{
									echo '<option value="'.$infosid['ville'].'" selected>'.$infosid['ville'].'</option>';
									echo '<option value="">--------</option>';
										
									}
									while ($row = mysql_fetch_array($detail1->result)) 
									{ 
										if ($infosid['ville'] == $row['cpblocalite']) {$select = ' selected';} else {$select = '';}
										echo '<option value="'.$row['cpblocalite'].'"'.$select.'>'.$row['cpblocalite'].'</option>';	
									}
								echo '</select>';
								}
								else { ?>
									<input type="text" size="20" name="ville" value="<?php echo $infosid['ville']; ?>">
								<?php }							
							?>
							<?php # echo $infosid['ville']; ?>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="etiq2">
							<?php echo $contact_13; ?>
						</td>
						<td class="contenu">
							<SELECT NAME="pays" SIZE="1">
								<?php
								If ($infosid['pays'] == '') { 
									echo '<OPTION VALUE="Belgique">Belgique</OPTION>';
								} else {
									echo '<OPTION VALUE="'.$infosid['pays'].'" SELECTED>'.$infosid['pays'].'</option>';
								}
								include "../data/inc-pays.php" ;
								?>
							</SELECT>
						</td>
						<td>
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td colspan="5">
						</td>
					</tr>
					<tr>
						<td colspan="2" class="etiq2">
							<?php echo $contact_14; ?>
						</td>
						<td class="contenu">
							<input type="text" size="20" name="tel" value="<?php echo $infosid['tel']; ?>">
						</td>
						<td class="etiq2">
							<?php echo $contact_15; ?>
						</td>
						<td class="contenu">
							<input type="text" size="20" name="fax" value="<?php echo $infosid['fax']; ?>">
						</td>
					</tr>
					<tr>
						<td colspan="5">
						</td>
					</tr>
					<tr>
						<td colspan="2" class="etiq2">
			<?php
			if (isset($errtva)) {
				echo '<font color="#FF0000">'.$contact_16.'</font>';
			} else {
				echo $contact_16;
			}
			?>
			
						</td>
						<td colspan="2" class="contenu">
							<?php 
							$ctva = array ('BE', 'FR', 'DE', 'DK', 'IT', 'LU', 'NL', 'UK', '');
			
							echo '<select name="codetva">';
							foreach ($ctva as $value) {
								echo '<option value="'.$value.'"';
								if ($infosid['codetva'] == $value) {
									echo ' selected';
								}
								echo '>'.$value.'</option>';
							}
							echo '</select>';
							?>
							 &nbsp; <input type="text" size="20" name="tva" value="<?php echo $infosid['tva']; ?>"> &nbsp; 
						</td>
						<td class="contenu">
							<?php 
							$astva = array (
								'4' => $contact_17, 
								'3' => $contact_18,
								'5' => $contact_19, 
								'6' => $contact_20
							);
			
							echo '<select name="astva">';
							foreach ($astva as $key => $value) {
								echo '<option value="'.$key.'"';
								if ($infosid['astva'] == $key) {
									echo ' selected';
								}
								echo '>'.$value.'</option>';
							}
							echo '</select>';
							?>
							 &nbsp; <div style="font-size: 9px;color: #FF0000;"><?php echo $errtva; ?></div>
						
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="5">
						</td>
					</tr>
					<tr>
						<td colspan="2" class="etiq2">
							Email / Web Login
						</td>
						<td class="contenu">
							<input type="text" size="20" name="email" value="<?php echo $infosid['email']; ?>">
						</td>
						<td class="etiq2">
							Web Password
						</td>
						<td class="contenu">
							<input type="text" size="20" name="password" value="<?php echo $infosid['password']; ?>">
						</td>
					</tr>
					<tr>
						<td colspan="5">
						</td>
					</tr>
					<tr>
						<td colspan="5" align="center">
							<font color="#FF9900"><?php echo $menu_new_00; ?></font>
						</td>
					</tr>
				</table>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<td width="98%" align="center">
							<input type="submit" name="act" value="<?php echo $tool_02 ; ?>"> 
						</td>
					</tr>
				</table>
			</fieldset>
			<br>
		</form>
<?php
### voir si fiche n'est pas VISUALISEE en management Exception
#/## OK
} else {
### PAS OK
?>

			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
				<tr>
					<td width="98%" align="center">
						<br><br>
						<Fieldset>
						Fiche indisponible <br>
						==> management<br>
						TEXTE SACHA<br>
						Retour menu
						<br><br><br>
						<a href="adminpeople.php">MENU</a>
						<br><br>
						</Fieldset>
					</td>
				</tr>
			</table>

<?php
### voir si fiche n'est pas VISUALISEE en management Exception
}
#/## PAS OK
?>
</div>