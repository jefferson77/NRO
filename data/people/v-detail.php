<?php
# Classes utilisées
include NIVO.'classes/test.php';

################### Code PHP ########################
if (!empty($idpeople)) {
	$infos = $DB->getRow("SELECT * FROM `people` WHERE `idpeople` = '$idpeople'");

	$idpeople = $infos['idpeople'];

	$Verif = new test($infos['idpeople']);

	# Teste tous les champ !! ordre a de l'importance
	$Verif->checkPRE(addslashes($infos['pprenom']));
	$Verif->checkNOM(addslashes($infos['pnom']));
	$Verif->checkCCC($infos['catsociale']);
	$Verif->checkSEX($infos['sexe']);
	$Verif->checkRUE(addslashes($infos['adresse1']));
	$Verif->checkCPO($infos['cp1']);
	$Verif->checkNUR($infos['num1']);
	$Verif->checkNUB($infos['bte1']);
	$Verif->checkLGE($infos['lbureau']);

	if (empty($infos['idsupplier'])) {
		$Verif->checkNCF($infos['banque']);
		$Verif->checkNAT($infos['nationalite']);
		$Verif->checkNUI($infos['ncidentite']);
		$Verif->checkDTN($infos['ndate']);
		$Verif->checkNCP($infos['ncp'], $infos['npays']);
		$Verif->checkCPP($infos['pays1']);
		$Verif->checkRNA($infos['nrnational'], $infos['ndate'], $infos['sexe'], $infos['npays']);
		$Verif->checkETC($infos['etatcivil']);
		$Verif->checkAPC($infos['pacharge']);
		$Verif->checkNEC($infos['eacharge']);
	}

	$Verif->listerr();

	##########
	$dateinscr = $infos['dateinscription'];
	$datemodif = $infos['datemodifweb'];

	$diff1 = nbjours($dateinscr, date("Y-m-d"));
	$diff2 = nbjours($datemodif, date("Y-m-d"));

	### Anotes le people si il y a des erreurs
	$errP = 0;

	if (count($Verif->ErrGS) >= 1) {
		$errP = $errP + count($Verif->ErrGS);
	}

	if (count($Verif->ErrDI) >= 1) {
		$errP = $errP + count($Verif->ErrDI);
	}

	if ($errP > 0) {
		$errpeople = new db();
		$errpeople->inline("UPDATE `people` SET `err` ='Y' WHERE `idpeople` ='$idpeople' LIMIT 1;");
	} else {
		$errpeople = new db();
		$errpeople->inline("UPDATE `people` SET `err` ='N' WHERE `idpeople` ='$idpeople' LIMIT 1;");
	}
	####

	$infos = $DB->getRow("SELECT * FROM `people` WHERE `idpeople` = '$idpeople'");
}
################### Fin Code PHP ########################
?>
<form name="people" action="?act=modif" method="post">
	<input type="hidden" name="idpeople" value="<?php echo $idpeople;?>">
	<input type="hidden" name="idmerch" value="<?php echo @$_GET['idmerch'];?>">
	<input type="hidden" name="idanimation" value="<?php echo @$_GET['idanimation'];?>">
	<input type="hidden" name="idanimjob" value="<?php echo @$_GET['idanimjob'];?>">
	<input type="hidden" name="idvip" value="<?php echo @$_GET['idvip'];?>">
	<input type="hidden" name="idvipjob" value="<?php echo @$_GET['idvipjob'];?>">
<?php /*  Menu de gauche */ ?>
<div id="leftmenu">
	<div id="idsquare">
		<table border="0" cellspacing="1" cellpadding="2" align="center" width="100%">
			<tr><td align="center">People</td></tr>
			<tr><td align="center">
				<?php if (($_SESSION['roger'] == 'devel') or ($_SESSION['roger'] == 'admin')) { ?>
					<input type="text" size="5" name="codepeople" value="<?php echo $infos['codepeople']; ?>">
				<?php } else { echo $infos['codepeople']; ?> <input type="hidden" name="codepeople" value="<?php echo $infos['codepeople']; ?>"> <?php } ?>
				<br>&nbsp;<br>
			</td></tr>
		</table>
	</div>
	<div id="idsquare2">
		<table border="0" cellspacing="1" cellpadding="2" align="center" width="100%">
			<tr><td align="center">GeoLoc</td></tr>
<?php
			if($infos['peoplehome'] == '1') {
				$_SESSION['adr'] = 1;
				$glat1 = 'glat1';
				$glong1 = 'glong1';
			}
			else {
				$_SESSION['adr'] = 2;
				$glat1 = 'glat2';
				$glong1 = 'glong2';
			}
?>
			<tr><td align="center" style="font-size:11px">Lat: <?php if($infos[$glat1] != 0) { echo $infos[$glat1]; } ?></td></tr>
			<tr><td align="center" style="font-size:11px">Lon: <?php if($infos[$glat1] != 0) { echo $infos[$glong1]; } ?></td></tr>
			<tr><td align="center"><a style="font-size:10px; text-decoration:none; background-color:#FFFFFF; padding:1px; border: 1px solid #527184;" href="<?php echo $_SERVER['PHP_SELF'].'?act=geoloc&idpeople='.$infos['idpeople']; ?>">Rechercher</a></td></tr>
		</table>
	</div>
</div>
<?php /*  Corps de la Page */ ?>

<div id="infozone">
	<div class="infosection">Infos G&eacute;n&eacute;rales</div>
	<table border="0" class="standard" cellspacing="1" cellpadding="0" align="center" width="99%">
		<tr>
			<td width="10" valign="top">
			</td>
			<td>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="99%">
					<tr>
						<td>
							<?php $Verif->erraffiche('Sexe', 'Sexe') ;?>
						</td>
						<td>
							<?php
							echo '<input type="radio" name="sexe" value="f" '; if (($infos['sexe'] == 'f') OR ($infos['sexe'] == '')) { echo 'checked';} echo'> F';
							echo '<input type="radio" name="sexe" value="m" '; if ($infos['sexe'] == 'm') { echo 'checked';} echo'> M';
							?>
						</td>
						<td>
							<?php $Verif->erraffiche('Langue Usuelle', 'Langue Bureau') ;?> :
						</td>
						<td>
							<?php
							echo '<input type="radio" name="lbureau" value="FR" '; if ($infos['lbureau'] == 'FR') { echo 'checked';} echo'> FR';
							echo '<input type="radio" name="lbureau" value="NL" '; if ($infos['lbureau'] == 'NL') { echo 'checked';} echo'> NL';
							?>
						</td>
						<td rowspan="9" align="center" width="150"><?php echo '<a href="photopeople.php?idp='.$infos['idpeople'].'&picnb=1"><img src="'.NIVO.'data/people/photo.php?id='.$idpeople.'" alt="'.$infos['pprenom'].$infos['pnom'].'"></a><br>'; ?></td>
						<td rowspan="9" align="center" width="150"><?php echo '<a href="photopeople.php?idp='.$infos['idpeople'].'&picnb=2"><img src="'.NIVO.'data/people/photo.php?id='.$idpeople.'&sfx=-b" alt="'.$infos['pprenom'].$infos['pnom'].'"></a><br>'; ?></td>
					</tr>
					<tr>
						<td>
<?php $Verif->erraffiche('Nom', 'Nom') ;?>
						</td>
						<td colspan="3">
							<input type="text" size="20" name="pnom" value="<?php echo $infos['pnom']; ?>">
						</td>
					</tr>
					<tr>
						<td>
<?php $Verif->erraffiche('Prénom', 'Pr&eacute;nom') ;?>
						</td>
						<td>
							<input type="text" size="20" name="pprenom" value="<?php echo $infos['pprenom']; ?>">
						</td>
						<td>
							Web Pass
						</td>
						<td width="50">
							<input type="text" size="20" name="webpass" value="<?php echo $infos['webpass']; ?>">
						</td>
					</tr>
					<tr>
						<td colspan="4"><br>&nbsp;</td>
					</tr>
					<tr>
						<td valign="top">
<?php $Verif->erraffiche('Rue', 'Adresse') ;?>
						</td>
						<td colspan="2">
							<input type="text" size="40" name="adresse1" value="<?php echo $infos['adresse1']; ?>">
						 &nbsp;
							<?php $Verif->erraffiche('Numéro de Rue', ' N&deg; ') ;?><input type="text" size="5" name="num1" value="<?php echo $infos['num1']; ?>"> <?php $Verif->erraffiche('Numéro de boite', 'Bte') ;?> <input type="text" size="5" name="bte1" value="<?php echo $infos['bte1']; ?>">
						</td>
						<td><?php echo ($infos['conditions_accepted'] == 'yes')?'Règlement travail':'' ?></td>
					</tr>
					<tr>
						<td>Geo <input type="radio" <?php if($infos['adresse1'] != '') { ?> <?php } ?> name="peoplehome" <?php if($infos['peoplehome'] == '1') { echo 'checked'; } ?> value="1"></td>
						<td colspan="2">
							<?php $Verif->erraffiche('Code Postal', 'CP') ;?>
							<input type="text" size="10" name="cp1" value="<?php echo $infos['cp1']; ?>">
							<?php $Verif->erraffiche('Localité', 'Ville') ;?>
							<?php
								if (($infos['cp1'] !== '') and (is_numeric($infos['cp1'])) and ($infos['pays1'] == 'BE')) {
									$cpbcode1 = $infos['cp1'];
									$detailcp2 = new db();
									$detailcp2->inline("SELECT * FROM `codepost` WHERE `cpbcode`=$cpbcode1");
									echo '<select name="ville1">';
										while ($row = mysql_fetch_array($detailcp2->result))
										{
											if ($infos['ville1'] == $row['cpblocalite']) {$select = ' selected';} else {$select = '';}
											echo '<option value="'.$row['cpblocalite'].'"'.$select.'>'.$row['cpblocalite'].'</option>';
										}
										if ($infos['ville1'] != '')
										{
										echo '<option value="'.$infos['ville1'].'">'.$infos['ville1'].'</option>';
										echo '<option value="">-- avant --</option>';

										}
									echo '</select>';

								} else { ?>
									<input type="text" size="10" name="ville1" value="<?php echo $infos['ville1']; ?>">
								<?php }
							?>
								 &nbsp;
								 <?php $Verif->erraffiche('Pays de résidence', 'Pays') ;?>
									<?php
										echo '
										<select name="pays1" size="1">
											<option value="BE"'; if (($infos['pays1'] == 'BE') OR ($infos['pays1'] == '')) {echo 'selected';} echo '>Belgique</option>
											<option value="FR"'; if ($infos['pays1'] == 'FR') {echo 'selected';} echo '>France</option>
											<option value="LU"'; if ($infos['pays1'] == 'LU') {echo 'selected';} echo '>Luxembourg</option>
										</select>
										';
										?>
						</td>
						<td>
							<?php echo ($infos['conditions_accepted'] == 'yes')?'<b>Accepté.</b>':'' ?>
						</td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;
						</td>

					</tr>
					<tr>
						<td valign="top">
							Adresse 2
						</td>
						<td colspan="3">
							<input type="text" size="40" name="adresse2" value="<?php echo $infos['adresse2']; ?>">
							&nbsp; N&deg; <input type="text" size="5" name="num2" value="<?php echo $infos['num2']; ?>"> Bte <input type="text" size="5" name="bte2" value="<?php echo $infos['bte2']; ?>">
						</td>
					</tr>
					<tr>
						<td>Geo <input type="radio" <?php if($infos['adresse2'] != '') { ?> <?php } ?> name="peoplehome" <?php if($infos['peoplehome'] == '2') { echo 'checked'; } ?> value="2"></td>
						<td colspan="3">
							CP <input type="text" size="10" name="cp2" value="<?php echo $infos['cp2']; ?>">
							Ville
							<?php
								if (($infos['cp2'] != '') and (is_numeric($infos['cp2'])) and ($infos['pays2'] == 'BE')) {
									$cpbcode2 = $infos['cp2'];
									$detailcp2 = new db();
									$detailcp2->inline("SELECT * FROM `codepost` WHERE 1 AND `cpbcode`=$cpbcode2");
									echo '<select name="ville2">';
										while ($row = mysql_fetch_array($detailcp2->result))
										{
											if ($infos['ville2'] == $row['cpblocalite']) {$select = ' selected';} else {$select = '';}
											echo '<option value="'.$row['cpblocalite'].'"'.$select.'>'.$row['cpblocalite'].'</option>';
										}
										if ($infos['ville2'] != '')
										{
										echo '<option value="">-- avant --</option>';
										echo '<option value="'.$infos['ville2'].'">'.$infos['ville2'].'</option>';

										}

									echo '</select>';
								} else { ?>
									<input type="text" size="10" name="ville2" value="<?php echo $infos['ville2']; ?>">
								<?php }
								?>
							&nbsp;
								Pays <?php
										echo '
										<select name="pays2" size="1">
											<option value="BE"'; if (($infos['pays2'] == 'BE') OR ($infos['pays2'] == '')) {echo 'selected';} echo '>Belgique</option>
											<option value="FR"'; if ($infos['pays2'] == 'FR') {echo 'selected';} echo '>France</option>
											<option value="LU"'; if ($infos['pays2'] == 'LU') {echo 'selected';} echo '>Luxembourg</option>
										</select>
										';
							?>
						</td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;</td>
						<td rowspan="3" colspan="2">
							<?php
								if ($infos['idsupplier'] > 0) {
									$supinfo = $DB->getOne("SELECT societe FROM supplier WHERE idsupplier = ".$infos['idsupplier']);
									$style = 'border:1px solid #C54200;background-color: #EE8C49; color: #990703;';
									?><style type="text/css" media="screen">table.standard {background-color:#EEBA92;}#infozone {background-color:#EE8C49;}</style><?php
								} else {
									$supinfo = false;
									$style = 'border:1px solid #8D8B92;background-color: #CDCCC9; color: #666;';
								}
							?>
							<div style="<?php echo $style ?>padding: 10px;font-size: 14px;text-align: center;">
									Sous traitant : <input type="text" name="idsupplier" value="<?php echo $infos['idsupplier'] ?>" id="idsupplier" size="4" title="Entrez le début d'un nom de supplier" style="text-align:center;">
									<b><?php echo $supinfo; ?></b>
								</form>
							</div>
						</td>
					</tr>
					<tr>
						<td <?php if($diff1 <= '15') { echo 'bgcolor="#FFCC66"'; } ?>>
							Date inscription
						</td>
						<td <?php if($diff1 <= '15') { echo 'bgcolor="#FFCC66"'; } ?>>
							<?php echo fdate($infos['dateinscription']); ?>
						</td>
						<td <?php if($diff2 <= '15') { echo 'bgcolor="#FFCC66"'; } ?>>
							Date Modification
						</td>
						<td <?php if($diff2 <= '15') { echo 'bgcolor="#FFCC66"'; } ?>>
							<input type="text" size="15" name="datemodifweb" value="<?php echo fdate($infos['datemodifweb']); ?>">
						</td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="99%" height="320">
		<tr>
			<td width="99%" height="320" valign="top">
				<table class="standard" border="0" cellspacing="0" cellpadding="0" align="center" width="99%" height="310">
					<tr height="25">
						<td class="navbarleft"></td>
						<td class="navbar"><?php echo '<a href="modifpeople-d.php?idpeople='.$idpeople.'&s=1" target="detail">Contacts</a>'; ?></td>
						<td class="navbarmid"></td>
						<td class="navbar"><?php echo '<a href="modifpeople-d.php?idpeople='.$idpeople.'&s=3" target="detail">Hôtesses</a>'; ?></td>
						<td class="navbarmid"></td>
						<td class="navbar"><?php echo '<a href="modifpeople-d.php?idpeople='.$idpeople.'&s=2" target="detail">Langues</a>'; ?></td>
						<td class="navbarmid"></td>
						<td class="navbar"><?php echo '<a href="modifpeople-d.php?idpeople='.$idpeople.'&s=5" target="detail">S-Social</a>'; ?></td>
						<td class="navbarmid"></td>
						<td class="navbar"><?php echo '<a href="modifpeople-d.php?idpeople='.$idpeople.'&s=8" target="detail">Disponibilit&eacute;s</a>'; ?></td>
						<td class="navbarmid"></td>
						<td class="navbar"><?php echo '<a href="modifpeople-d.php?idpeople='.$idpeople.'&s=6" target="detail">Exp&eacute;rience</a>'; ?></td>
						<td class="navbarmid"></td>
						<td class="navbar"><?php echo '<a href="modifpeople-d.php?idpeople='.$idpeople.'&s=7" target="detail">De-booking</a>'; ?></td>
						<td class="navbarmid"></td>
						<td class="navbar"><?php echo '<a href="http://77.109.79.37/mod/gmap.php?lat='.$infos[$glat1].'&amp;long='.$infos[$glong1].'&amp;w=99%&amp;h=270" target="detail">Map</a>'; ?></td>
						<td class="navbarmid"></td>
						<td class="navbar"><?php echo '<a href="modifpeople-d.php?idpeople='.$idpeople.'&s=9&act=show" target="detail">Vacances</a>'; ?></td>
						<td class="navbarmid"></td>
						<td class="navbar">
							<?php
							$nbr = $DB->getOne("SELECT COUNT(*) as nbr FROM matos WHERE idpeople =".$infos['idpeople']." GROUP BY idpeople");
							echo '<a href="modifpeople-d.php?idpeople='.$idpeople.'&s=0" target="detail"><span id="nbrmatos">Matos('.$nbr.')</span></a>' ?>
						</td>
						<td class="navbarmid"></td>
						<td class="navbar"><?php echo '<a href="modifpeople-d.php?idpeople='.$idpeople.'&s=4" target="detail">Documents Envoyés</a>'; ?></td>
						<td class="navbarright"></td>
					</tr>
					<tr>
						<td valign="middle" colspan="21" bgcolor="#304052">
							<iframe frameborder="0" marginwidth="1" marginheight="1" name="detail" src="<?php echo 'modifpeople-d.php?idpeople='.$idpeople.'&s=1'; ?>" width="100%" height="285" align="top">Marche pas les IFRAMES !</iframe>
						</td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="99%">
					<tr>
						<th colspan="2" align="left">
							Cat&eacute;gories
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<?php
							echo '<input type="checkbox" name="categorie[0]" value="1" '; if ($infos['categorie']{0}=='1') { echo 'checked';} echo'> Anim&nbsp;&nbsp;&nbsp;&nbsp;';
							echo '<input type="checkbox" name="categorie[1]" value="1" '; if ($infos['categorie']{1}=='1') { echo 'checked';} echo'> Merch&nbsp;&nbsp;&nbsp;&nbsp;';
							echo '<input type="checkbox" name="categorie[2]" value="1" '; if ($infos['categorie']{2}=='1') { echo 'checked';} echo'> Hotes';
							?>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;

						</td>
					</tr>
					<tr>
						<th colspan="2" align="left">
							Notes people
						</th>
					</tr>
					<tr>
						<td colspan="2">
							<textarea style="color: #DA0000;font-size: 12px;" name="notegenerale" rows="4" cols="30"><?php echo $infos['notegenerale']; ?></textarea>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;

						</td>
					</tr>
					<tr>
						<th colspan="2" align="left">
							Contrat disponible Online :
						</th>
					</tr>
					<tr>
						<td colspan="2" bgcolor="#FF9933">
							<?php
							echo '<input type="radio" name="webdoc" value="yes" '; if (strchr($infos['webdoc'], 'yes')) { echo 'checked';} echo'> oui';
							echo '<input type="radio" name="webdoc" value="no" '; if (strchr($infos['webdoc'], 'no')) { echo 'checked';} echo'> non';
							?>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;

						</td>
					</tr>
					<tr>
						<th align="left">
							People Out :
						</th>
						<td>
							<?php
							echo '<input type="checkbox" name="isout" value="out" '; if (strchr($infos['isout'], 'out')) { echo 'checked';} echo'> Out';
							?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<textarea name="noteout" rows="2" cols="30"><?php echo $infos['noteout']; ?></textarea>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
</div>
<div id="infobouton">
<?php
if (isset($idpeople)) {
	echo '<input type="submit" name="modif" value="Modifier" accesskey="M">';
	if(isset($_SESSION['idmerch']) or isset($_SESSION['idvip']) or isset($_SESSION['idanimation'])) {
		echo '<input type="submit" name="select" value="Valider et Retour" accesskey="V">';
	}
	if (($_SESSION['roger'] == 'devel') or ($_SESSION['roger'] == 'admin')) {
		echo '<br><br><input type="submit" name="act" value="delete">';
	}
} else {
	echo '<input type="submit" name="act" value="Ajouter">';
}
echo '
</div>
</form>';
?>
<script type="text/javascript" charset="utf-8">
	function formatResult(row) {return row[0];}
	function formatItem(row) {return "("+row[0]+") "+row[1];}
	$(document).ready(function() {
		$("input#idsupplier").autocomplete("<?php echo Conf::read('Env.urlroot') ?>query/supplier.php", {
			inputClass: 'autocomp',
			width: 200,
			minChars: 2,
			formatItem: formatItem,
			formatResult: formatResult,
			delay: 200
		});

		$("input#idsupplier").result(function(data) {
			if (data) document.people.submit();
		});
	});
</script>
