<?php
if (!empty($_REQUEST['idsupplier'])) $infos = $DB->getRow("SELECT * FROM `supplier` WHERE `idsupplier` = ".$_REQUEST['idsupplier']);

if ($infos['astva'] == '4') {

	switch ($infos['codetva']) {
		
			case "BE":
			### Teste la validité
				$tva = cleannombreonly($infos['tva']);
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
							$supplier = new db();
							$supplier->inline("UPDATE `supplier` SET `tva` = '$formtva' WHERE `idsupplier` = ".$_REQUEST['idsupplier']);
							$detail = new db();
							$detail->inline("SELECT * FROM `supplier` WHERE `idsupplier` = ".$_REQUEST['idsupplier']);
							$infos = mysql_fetch_array($detail->result) ; 
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
			
			case "LU": 
			break;
			
			case "NL": 
			break;
			
			default: 
	}
}

?>
<form action="?act=modif" method="post">
	<input type="hidden" name="idsupplier" value="<?php echo $_REQUEST['idsupplier'];?>"> 
	<div id="leftmenu">
		<div id="idsquare" align="center">
			Supplier<br>
			<?php echo $infos['idsupplier']; ?>
		</div>
	</div>

	<div id="infozone">
		<div class="infosection">Infos G&eacute;n&eacute;rales</div>
		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
			<tr valign="top">
				<td>
					<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
						<tr>
							<td>Soci&eacute;t&eacute;</td>
							<td><input type="text" size="20" name="societe" value="<?php echo $infos['societe']; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Activit&eacute;</td>
							<td><input type="text" size="20" name="fonction" value="<?php echo $infos['fonction']; ?>"></td>
							<td>langue</td>
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
							<td>
								Ville
							</td>
							<td>
								<?php
									if ((!empty($infos['cp'])) and (is_numeric($infos['cp']))) {
									$cpbcode = $infos['cp'];
									$detail1 = new db();
									$detail1->inline("SELECT * FROM `codepost` WHERE `cpbcode`=$cpbcode");
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
								<?php # echo $infos['ville']; ?>
							</td>
						</tr>
						<tr>
							<td>
								Pays
							</td>
						
							<td>
								<SELECT NAME="pays" SIZE="1">
									<?php
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
							<td><?php echo (isset($errtva))?'<font color="#FF0000">TVA</font>':'TVA'; ?></td>
							<td>
								<?php 
								$ctva = array ('BE', 'FR', 'DE', 'DK', 'IT', 'LU', 'NL', 'UK', '');
			
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
									'7' => 'Particulier (pas de n&deg; TVA)'
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
								 &nbsp; <div style="font-size: 9px;color: #FF0000;"><?php echo $errtva; ?></div>
						
							</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Notes suppliers</td>
							<td colspan="3"><textarea name="notes" rows="3" cols="53"><?php echo $infos['notes']; ?></textarea></td>
						</tr>
					</table>
				</td>
				<td width="45%">
					Etat
					<?php 
					echo '<input type="radio" name="etat" value="5" '; if (($infos['etat'] == '') OR ($infos['etat'] == '5')) { echo 'checked';} echo'> In';
					echo '<input type="radio" name="etat" value="0" '; if ($infos['etat'] == '0') { echo 'checked';} echo'> Out';
					?>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center"><input type="submit" name="Modifier" value="Modifier" accesskey="M"></td>
			</tr>
		</table>
	</form>
	<div class="infosection">D&eacute;tails</div>
	<table class="standard" border="1" cellspacing="1" cellpadding="0" align="center" width="100%">
		<tr height="25">
			<th class="standard" align="center" width="16%"><a href="supplier-onglet.php?idsupplier=<?php echo $_REQUEST['idsupplier'] ?>&act=contactShow" target="detail-main">Contacts</a></th>
			<th class="standard" align="center" width="16%"><a href="supplier-onglet.php?idsupplier=<?php echo $_REQUEST['idsupplier'] ?>&act=tarifShow" target="detail-main">Tarification</a></th>
			<th class="standard" align="center" width="16%"><a href="supplier-onglet.php?idsupplier=<?php echo $_REQUEST['idsupplier'] ?>&act=commissionShow" target="detail-main">Commissions</a></th>
			<th class="standard" align="center" width="16%"></th>
			<th class="standard" align="center" width="16%"></th>
			<th class="standard" align="center"></th>
		</tr>
	</table>
	<iframe frameborder="0" marginwidth="0" marginheight="0" name="detail-main" src="<?php echo 'supplier-onglet.php?idsupplier='.$_REQUEST['idsupplier'].'&s=1&action='.$_POST['act'].'';?>" width="100%" height="240" align="top">Marche pas les IFRAMES !</iframe> 
</div>

