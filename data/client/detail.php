<?php

## get datas
if (!empty($did)) $infos = $DB->getRow("SELECT * FROM `client` WHERE `idclient` = ".$did);

#>## Check N° TVA
function cleannombre($nombre) {
	$nombre = preg_replace("/[^0-9]/", "", $nombre); # vire les crasses
	if (strlen($nombre) == 9) $nombre = '0'.$nombre;

	return $nombre;
}

if ($infos['astva'] == '4') { ## si assujeti

	switch ($infos['codetva']) {

			case "BE":
			### Teste la validité
				$tva = cleannombre($infos['tva']);
				if (preg_match("/([0-9]{4})([0-9]{3})([0-9]{3})/", $tva, $regs)) {
					$reste = substr($tva, -2) ;
					$nombre = substr($tva, 0, -2) ;
					if (fmod($nombre, 97) == 0) {
						$mod = 97;
					} else {
						$mod = 97 - fmod($nombre, 97);
					}
					if ($mod != $reste) {
						$errtva = 'Erreur sur le chiffre de controle';
					} else {
						$formtva = $regs[1].' '.$regs[2].' '.$regs[3];
						if ($formtva != $infos['tva']) {
							$DB->inline("UPDATE `client` SET `tva` = '$formtva' WHERE `idclient` = ".$did);
							$infos = $DB->getRow("SELECT * FROM `client` WHERE `idclient` = ".$did);
						}
					}
				} else {
					$errtva = 'Doit etre sous la forme XXXX XXX XXX';
				}
			break;

			case "UK":
			break;

			case "FR":
			break;

			case "CZ":
			break;

			case "LU":
			break;

			case "NL":
			break;

			case "ATU":
			break;

			case "IE":
			break;

			case "PO":
			break;

			case "MT":
			break;

			case "US":
			break;

			case "ES":
			break;

			case "GR":
			break;

			case "TUR":
			break;

			default:
	}
}


#<## Check N° TVA

################### Fin Code PHP ########################
?>
<form action="?act=modif" method="post">
	<input type="hidden" name="idclient" value="<?php echo $did;?>">
	<div id="leftmenu">
		<div id="idsquare">
			<table border="0" cellspacing="1" cellpadding="2" align="center" width="100%">
				<tr><td align="center">Client</td></tr>
	<?php if (($infos['codeclient'] == 0) or ($infos['codeclient'] == '')) { $infos['codeclient'] = $infos['idclient']; } ?>
				<tr><td align="center"><?php echo $infos['codeclient']; ?></td></tr>
			</table>
		</div>
	</div>
	<div id="infozone">
		<div class="infosection">Infos G&eacute;n&eacute;rales</div>

		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
			<tr valign="top">
				<td>
					<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
						<tr>
							<td>
								Soci&eacute;t&eacute;
							</td>
							<td>
								<input type="text" size="20" name="societe" value="<?php echo $infos['societe']; ?>">
							</td>
							<td> Horizon</td>
							<td>

							<input type="text" size="20" name="codecompta" value="<?php echo $infos['codecompta']; ?>" disabled>
							</td>
						</tr>
						<?php
						?>
						<tr>
							<td>Fonction</td>
							<td>
								<input type="text" size="20" name="fonction" value="<?php echo $infos['fonction']; ?>">
							</td>
							<td>langue facture</td>
							<td>
								<?php
								echo '<input type="radio" name="langue" value="FR" '; if (($infos['langue'] == '') OR ($infos['langue'] == 'FR')) { echo 'checked';} echo'> FR';
								echo '<input type="radio" name="langue" value="NL" '; if ($infos['langue'] == 'NL') { echo 'checked';} echo'> NL';
								?>
							</td>
						</tr>
						<tr>
							<td>Adresse</td>
							<td colspan="3"><input type="text" size="35" name="adresse" value="<?php echo $infos['adresse']; ?>"></td>
						</tr>
						<tr>
							<td>Code postal</td>
							<td><input type="text" size="20" name="cp" value="<?php echo $infos['cp']; ?>"></td>
							<td>Ville</td>
							<td>
								<?php
									if (($infos['cp'] != '') and (is_numeric($infos['cp'])) and ($infos['pays'] == 'Belgique')) {
									$cpbcode = $infos['cp'];
									$detail1 = new db();
									$detail1->inline("SELECT * FROM `codepost` WHERE `cpbcode` = $cpbcode");
									echo '<select name="ville">';
										if (!empty($infos['ville']))
										{
										echo '<option value="'.$infos['ville'].'" selected>'.$infos['ville'].'</option>';
										echo '<option value="">--------</option>';

										}
										while ($row = mysql_fetch_array($detail1->result))
										{
											if ($infos['ville'] == $row['cpblocalite']) {$select = ' selected';} else {$select = '';}
											echo '<option value="'.$row['cpblocalite'].'"'.$select.'>'.$row['cpblocalite'].'</option>';
										}
									echo '</select>';
									}
									else { ?>
										<input type="text" size="20" name="ville" value="<?php echo $infos['ville']; ?>">
									<?php }
								?>
							</td>
						</tr>
						<tr>
							<td>Pays</td>
							<td>
								<SELECT NAME="pays" SIZE="1">
									<?php
									/*
										TODO : standardiser la liste des pays (BE, NL, UK....)
									*/
									If ($infos['pays'] == '') {
										echo '<OPTION VALUE="Belgique">Belgique</OPTION>';
									} else {
										echo '<OPTION VALUE="'.$infos['pays'].'" SELECTED>'.$infos['pays'].'</option>';
									}
									include "../inc-pays.php" ;
									?>
								</SELECT>
							</td>
							<td>Email</td>
							<td><input type="text" size="20" name="email" value="<?php echo $infos['email']; ?>"></td>
						</tr>
						<tr>
							<td>Tel</td>
							<td><input type="text" size="20" name="tel" value="<?php echo $infos['tel']; ?>"></td>
							<td>Fax</td>
							<td><input type="text" size="20" name="fax" value="<?php echo $infos['fax']; ?>"></td>
						</tr>
						<tr>
							<td>
				<?php
				if (isset($errtva)) {
					echo '<font color="#FF0000">TVA</font>';
				} else {
					echo 'TVA';
				}
				?>

							</td>
							<td>
								<?php
								$ctva = array ('BE', 'FR', 'DE', 'DK', 'IT', 'LU', 'NL', 'UK', 'ATU', 'US', 'IE', 'CZ', 'PO', 'MT', 'TUR', 'ES', 'GR', '');

								echo '<select name="codetva">';
								foreach ($ctva as $value) {
									echo '<option value="'.$value.'"';
									if ($infos['codetva'] == $value) {
										echo ' selected';
									}
									echo '>'.$value.'</option>';
								}
								echo '</select>';
								?>
								 &nbsp; <input type="text" size="20" name="tva" value="<?php echo $infos['tva']; ?>"><br>
								<?php
								$astva = array (
									'4' => 'Assujeti',
									'3' => 'Exon&eacute;r&eacute;',
									'5' => 'Etranger CEE',
									'6' => 'Etranger Hors CEE',
									'7' => 'Non assujetti ',
									'8' => 'Communaut&eacute;s Europ&eacute;ennes'
								);

								echo '<select name="astva">';
								foreach ($astva as $key => $value) {
									echo '<option value="'.$key.'"';
									if ($infos['astva'] == $key) {
										echo ' selected';
									}
									echo '>'.$value.'</option>';
								}
								echo '</select>';
								?>
								 &nbsp; <div style="font-size: 9px;color: #FF0000;"><?php echo @$errtva; ?></div>

							</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td>Web Password</td>
							<td><input type="text" size="20" name="password" value="<?php echo $infos['password']; ?>"></td>
						</tr>
						<tr>
							<td>Notes clients</td>
							<td colspan="3"><textarea style="color: #DA0000;font-size: 12px;" name="notes" rows="3" cols="53"><?php echo $infos['notes']; ?></textarea></td>
						</tr>
						<tr>
							<td>Départements</td>
							<td colspan="3">
								Anim:  <?php echo ($infos['anim']  == 1) ? 'oui ' : 'non '; ?> &nbsp; &nbsp; &nbsp;
								Merch: <?php echo ($infos['merch'] == 1) ? 'oui ' : 'non '; ?> &nbsp; &nbsp; &nbsp;
								Vip:   <?php echo ($infos['vip']   == 1) ? 'oui ' : 'non '; ?>
							</td>
						</tr>

					</table>
				</td>
				<td width="45%">
					<table border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
						<tr>
							<td colspan="2">
								<iframe frameborder="0" marginwidth="0" marginheight="0" name="cofficer" src="jobiste.php?idclient=<?php echo $infos['idclient']; ?>&facturation=<?php echo $infos['facturation'];?>" width="98%" height="200" align="top">Marche pas les IFRAMES !</iframe>
							</td>
						</tr>
						<tr>
							<td>
								&nbsp
							</td>
							<td>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								Etat :
								<?php
								echo '<input type="radio" name="etat" value="5" '; if (($infos['etat'] == '') OR ($infos['etat'] == '5')) { echo 'checked';} echo'> In';
								echo '<input type="radio" name="etat" value="0" '; if ($infos['etat'] == '0') { echo 'checked';} echo'> Out';
								?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center">
					<?php if (isset($did)) { ?>
						<input type="submit" name="Modifier" value="Modifier" accesskey="M">
					<?php } else { ?>
						<input type="submit" name="act" value="Ajouter">
					<?php } ?>
				</td>
			</tr>

		</table>
		<div class="infosection">D&eacute;tails</div>
		<iframe frameborder="0" marginwidth="0" marginheight="0" name="detail-main" src="client-onglet.php?s=1&idclient=<?php echo $did;?>" width="100%" height="240" align="top">Marche pas les IFRAMES !</iframe>
	</div>
</form>


