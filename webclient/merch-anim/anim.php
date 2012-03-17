<div class="corps98">
<?php
setlocale(LC_TIME, $_SESSION['lang']);

$infosjob = $DB->getRow("SELECT * FROM webneuro.webanimjob WHERE `idwebanimjob` = ".$idwebanimjob);

### voir si fiche n'est pas VISUALISEE en management Exception
### OK
if ($infosjob['etat'] <= 4) {


if ($s == '') {$s = 0;}
################### Fin Code PHP ########################
?>
		<?php
		### MENU DU DESSUS
		?>
		<table border="0" cellspacing="1" cellpadding="0" align="center" width="99%" height="20">
			<tr bgcolor="#EEEEEE">
				<td align="center" width="20%">
					<?php if ($s == 0) { $bouton = "boutonx2"; } else { $bouton = "boutonx"; }?>
					<table align="center" cellspacing="0" cellpadding="0" border="0">
						<tr>
						<td width="8"><img src="<?php echo $illus; ?>bouton/left.gif" width="8" height="24" alt="" border="0"></td>
						<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
						<td class="<?php echo $bouton; ?>" height="24" valign="middle" width="125">
							<b><?php echo $vipdetail_01; ?></b>
						</td>
						<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
						<td width="8"><img src="<?php echo $illus; ?>bouton/right.gif" width="8" height="24" alt="" border="0"></td>
						</tr>
					</table>
				</td>
				<td align="center" width="20%">
					<?php if ($s == 1) { $bouton = "boutonx2"; } else { $bouton = "boutonx"; }?>
					<table align="center" cellspacing="0" cellpadding="0" border="0">
						<tr>
						<td width="8"><img src="<?php echo $illus; ?>bouton/left.gif" width="8" height="24" alt="" border="0"></td>
						<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
						<td class="<?php echo $bouton; ?>" height="24" valign="middle" width="125">
							<b>Points of Sales (POS)</b>
						</td>
						<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
						<td width="8"><img src="<?php echo $illus; ?>bouton/right.gif" width="8" height="24" alt="" border="0"></td>
						</tr>
					</table>
				</td>
				<td align="center" width="20%">
					<?php if ($s == 2) { $bouton = "boutonx2"; } else { $bouton = "boutonx"; }?>
					<table align="center" cellspacing="0" cellpadding="0" border="0">
						<tr>
						<td width="8"><img src="<?php echo $illus; ?>bouton/left.gif" width="8" height="24" alt="" border="0"></td>
						<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
						<td class="<?php echo $bouton; ?>" height="24" valign="middle" width="105">
							<b><?php echo $vipdetail_02; ?></b>
						</td>
						<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
						<td width="8"><img src="<?php echo $illus; ?>bouton/right.gif" width="8" height="24" alt="" border="0"></td>
						</tr>
					</table>
				</td>
				<td align="center" width="20%">
					<?php if ($s == 3) { $bouton = "boutonx2"; } else { $bouton = "boutonx"; }?>
					<table align="center" cellspacing="0" cellpadding="0" border="0">
						<tr>
						<td width="8"><img src="<?php echo $illus; ?>bouton/left.gif" width="8" height="24" alt="" border="0"></td>
						<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
						<td class="<?php echo $bouton; ?>" height="24" valign="middle" width="115">
							<b><?php echo $vipdetail_04; ?></b>
						</td>
						<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
						<td width="8"><img src="<?php echo $illus; ?>bouton/right.gif" width="8" height="24" alt="" border="0"></td>
						</tr>
					</table>
				</td>
				<td align="center">
					<?php if ($s == 3) { $bouton = "boutonx2"; } else { $bouton = "boutonx"; }?>
					<table align="center" cellspacing="0" cellpadding="0" border="0">
						<tr>
						<td width="8"><img src="<?php echo $illus; ?>bouton/left.gif" width="8" height="24" alt="" border="0"></td>
						<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
						<td class="<?php echo $bouton; ?>" height="24" valign="middle" width="105">
							<b><?php echo $vipdetail_03; ?></b>
						</td>
						<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
						<td width="8"><img src="<?php echo $illus; ?>bouton/right.gif" width="8" height="24" alt="" border="0"></td>
						</tr>
					</table>
				</td>
				<td align="center">
					<?php echo $s; ?>
				</td>
			</tr>
		</table>
		<?php
		#/ ## MENU DU DESSUS
		?>
		<br>
			<?php if ($s == '0') { ?>
			<?php




			### PAGE 1 : Info Générales
			?>
			<Fieldset class="width2">
				<legend class="width"><?php echo $vipdetail_01; ?></legend>
				<form action="?act=animmodif<?php echo $s; ?>" method="post">
					<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
					<input type="hidden" name="s" value="<?php echo $s;?>">
					<table class="standard" border="0" cellspacing="1" cellpadding="6" align="center" width="98%">
						<tr>
							<td class="etiq3" width="230" valign="top">
								<?php echo $vipdetail_0_01 ?>
							</td>
							<td class="contenu">
								<input type="text" size="100" name="reference" value="<?php echo $infosjob['reference']; ?>">
							</td>
						</tr>
						<tr>
							<td class="etiq3" valign="top">
								<?php echo $vipdetail_0_02; ?>
							</td>
							<td class="contenu">
								<textarea name="notejob" rows="6" cols="75"><?php echo $infosjob['notejob']; ?></textarea>
							</td>
						</tr>
						<tr>
							<td class="etiq3" valign="top">
								<?php echo $vipdetail_0_10; ?>
							</td>
							<td class="contenu">
								<input type="text" size="100" name="bondecommande" value="<?php echo $infosjob['bondecommande']; ?>">
							</td>
						</tr>
					</table>
					<table class="standard" border="0" cellspacing="1" cellpadding="6" align="center" width="98%">
						<tr>
							<td></td>
							<td class="contenu" align="center" width="150">
								<input type="submit" name="action" value="<?php echo $tool_03; ?> &nbsp; &gt;&gt;">
							</td>
							<td></td>
						</tr>
					</table>
				</form>
			</Fieldset>

				<!-- EFFACER LE JOB ET RETOUR LISTING -->
				<form action="?act=webdelete" method="post" onSubmit="return confirm('<?php echo $tool_19; ?>')">
					<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
					<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
						<tr>
							<td width="98%" align="right">
								<input type="submit" name="action" value="<?php echo $tool_05a; ?>">
							</td>
						</tr>
					</table>
				</form>
				<!-- / EFFACER LE JOB ET RETOUR LISTING -->

			<?php } ?>
			<?php
			#/ ## PAGE 1 : Info Générales




			?>
			<?php




			### PAGE 2 : POS
			?>
			<?php if ($s == '1') {
			$shopselection = $infosjob['shopselection'];
			?>
			<Fieldset class="width2">
				<legend class="width">Points of Sales / POS : <?php echo $animpos_25; ?></legend>
					<table class="standard" border="0" cellspacing="4" cellpadding="10" align="center" width="99%">
						<tr>
							<td class="contenu" valign="top" width="33%" align="center">
								<form action="?act=animmodif<?php echo $s; ?>a&section=historique&shophistoric=1" method="post">
									<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
									<input type="hidden" name="s" value="1">
									<input type="submit" name="action" value="<?php echo $animpos_04; ?>">
								</form>
								<b><?php echo $animpos_03; ?></b>
							</td>
							<td class="contenu" valign="top" width="34%" align="center">
								<form action="?act=animmodif<?php echo $s; ?>a&shopsearch=1" method="post">
									<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
									<input type="hidden" name="s" value="1">
									<input type="submit" name="action" value="<?php echo $animpos_08; ?>">
								</form>
								<b><?php echo $animpos_09; ?></b>
							</td>
							<td class="contenu" valign="top" width="33%" align="center">
								<form action="?act=animmodif<?php echo $s; ?>a&section=addshop&addshop=1" method="post">
									<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
									<input type="hidden" name="s" value="1">
									<input type="submit" name="action" value="<?php echo $animpos_12; ?>">
								</form>
								<b><?php echo $animpos_13; ?></b>
							</td>
						</tr>
					</table>
			</Fieldset>
			<br>
			<br>
<!--
			<Fieldset class="width2">
				<legend class="width">Points of Sales / POS</legend>
					<table class="standard" border="0" cellspacing="4" cellpadding="10" align="center" width="98%">
						<tr>
							<td class="etiq3" valign="top" width="33%" align="center">
								<br>
								<form action="?act=animmodif<?php echo $s; ?>a&section=historique&shophistoric=1" method="post">
									<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
									<input type="hidden" name="s" value="1">
									<input type="submit" name="action" value="<?php echo $animpos_04; ?>">
								</form>
							</td>
							<td class="etiq3" valign="top" align="center">
								<br>
								<form action="?act=animmodif<?php echo $s; ?>a&shopsearch=1" method="post">
									<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
									<input type="hidden" name="s" value="1">
									<input type="submit" name="action" value="<?php echo $animpos_08; ?>">
								</form>
							</td>
							<td class="etiq3" valign="top" width="33%" align="center">
								<br>
								<form action="?act=animmodif<?php echo $s; ?>a&section=addshop&addshop=1" method="post">
									<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
									<input type="hidden" name="s" value="1">
									<input type="submit" name="action" value="<?php echo $animpos_12; ?>">
								</form>
							</td>
						</tr>
						<tr>
							<td class="contenu" valign="top" width="33%" align="center">
								<b><?php echo $animpos_03; ?></b>
							</td>
							<td class="contenu" valign="top" align="center">
								<b><?php echo $animpos_09; ?></b>
							</td>
							<td class="contenu" valign="top" width="33%" align="center">
								<b><?php echo $animpos_13; ?></b>
							</td>
						</tr>
					</table>
			</Fieldset>
			<br>
			<br>
-->
			<?php if ($_GET['shophistoric'] > 0) { ?>
				<Fieldset class="width2">
					<legend class="width">Points of Sales : <?php echo $animpos_01; ?></legend>
					<form action="?act=animmodif<?php echo $s; ?>&section=historique" method="post">
						<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
						<input type="hidden" name="s" value="<?php echo $s;?>">
						<input type="hidden" name="shopselection" value="<?php echo $shopselection;?>">
						<table class="standard" border="0" cellspacing="1" cellpadding="6" align="center" width="98%">
							<tr>
							<?php
								### Création du Listing shop
								$idclient = $_SESSION['idclient'];
	#								WHERE an.idclient=1910
								$recherche='
									SELECT s.idshop, s.societe AS ssociete, s.ville
									FROM animation an
									LEFT JOIN shop s ON an.idshop = s.idshop
									WHERE an.idclient='.$idclient.'
									GROUP BY s.idshop
									ORDER BY s.societe
								';
								$listingshop = new db();
								$listingshop->inline("$recherche;");
								#/## Création du Listing shop
									while ($rowshop = mysql_fetch_array($listingshop->result)) {
									$ii++;
									if (fmod($ii, 3) == 1) {
										echo '<tr><tr>';
									}
									?>
										<td class="etiq3" width="350" valign="top">
											<input type="checkbox" name="shophistorique[]" value="<?php echo $rowshop['idshop'].'-'; ?>" <?php if (strchr($infosjob['shopselection'], $rowshop['idshop'])) { echo 'checked'; } ?>> <?php echo $rowshop['ssociete'].' / '.$rowshop['ville']; ?>
										</td>
									<?php
									}
							?>
							</tr>
							<tr>
								<td align="center" colspan="3">
									<input type="submit" name="action" value="<?php echo $animpos_02; ?>">
								</td>
							</tr>
						</table>
					</form>
				</Fieldset>
				<br>
				<br>
			<?php } ?>

			<?php if ($_GET['shopsearch'] > 0) { ?>
				<Fieldset class="width2">
					<legend class="width">Points of Sales : <?php echo $animpos_06; ?></legend>
					<?php include 'anim-search-shop.php'; ?>
				</Fieldset>
				<br>
				<br>
			<?php } ?>

			<?php if ($_GET['section'] == 'addshop') { ?>
				<Fieldset class="width2">
					<legend class="width">Points of Sales : <?php echo $animpos_10; ?></legend>
					<?php include 'anim-add-shop.php'; ?>
				</Fieldset>
				<br>
				<br>
			<?php } ?>
			<?php
			### Tableau de séléction
			?>
					<?php
					$posgroup = $shopselection;
					$f = explode("-%%", $posgroup);

					foreach ($f as $val) {
						$val = trim($val);
						if (is_numeric($val)) $posz[] = $val;
					}
					### si tableau des selection des shops est non vide
					if (is_array ($posz)) {
					### Construction de la requete
						$sql = "SELECT idshop, codeshop, societe, cp, ville, adresse FROM shop
						WHERE `idshop` IN ('";

						foreach ($posz as $val) {
							$sql .= $val."', '";
						}
						$sql = substr($sql, 0, -3);
						$sql .= ") GROUP BY `idshop` ASC";
						$sql .= " ORDER BY `societe` ASC";

						$shops = new db();
						$shops->inline($sql);
					}
					if (count ($posz) >= 1) {
					?>
					<Fieldset class="width2">
						<legend class="width">Points of Sales : <?php echo $animpos_14; ?></legend>
						<form action="?act=animmodif<?php echo $s; ?>&section=selection" method="post">
							<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
							<input type="hidden" name="s" value="<?php echo $s;?>">
							<table class="standard" border="0" cellspacing="1" cellpadding="6" align="center" width="98%">
								<tr>
									<td colspan="6" valign="top">
										<i><?php echo $animpos_15; ?></i>
									</td>
								</tr>
								<tr>
									<td class="etiq3"><a class="white2" href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=2&action=skip&skip='.$skip.'&sort=codeshop';?>"><?php echo $animpos_16; ?></a></td>
									<td class="etiq3"><a class="white2" href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=2&action=skip&skip='.$skip.'&sort=societe';?>"><?php echo $animpos_17; ?></a></td>
									<td class="etiq3"><a class="white2" href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=2&action=skip&skip='.$skip.'&sort=cp';?>"><?php echo $animpos_18; ?></a></td>
									<td class="etiq3"><a class="white2" href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=2&action=skip&skip='.$skip.'&sort=ville';?>"><?php echo $animpos_19; ?></a></td>
									<td class="etiq3"><?php echo $animpos_20; ?></td>
									<td class="etiq3"><?php echo $animpos_21; ?></td>
								</tr>
								<?php
								if (count ($posz) >= 1) {
									while ($row = mysql_fetch_array($shops->result)) {
								?>
									<tr>
										<td class="contenu"><?php echo $row['codeshop'] ?></td>
										<td class="contenu"><?php echo $row['societe']; ?></td>
										<td class="contenu"><?php echo $row['cp']; ?></td>
										<td class="contenu"><?php echo $row['ville']; ?></td>
										<td class="contenu"><?php echo $row['adresse']; ?></td>
										<td class="etiq3" valign="top">
											<input type="checkbox" name="shopselection[]" value="<?php echo $row['idshop'].'-'; ?>" <?php if (strchr($infosjob['shopselection'], $row['idshop'])) { echo 'checked'; } ?>>
										</td>
									</tr>
								<?php
									}
								}
								?>
								<tr>
									<td class="etiq3"><a class="white2" href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=2&action=skip&skip='.$skip.'&sort=codeshop';?>"><?php echo $animpos_16; ?></a></td>
									<td class="etiq3"><a class="white2" href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=2&action=skip&skip='.$skip.'&sort=societe';?>"><?php echo $animpos_17; ?></a></td>
									<td class="etiq3"><a class="white2" href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=2&action=skip&skip='.$skip.'&sort=cp';?>"><?php echo $animpos_18; ?></a></td>
									<td class="etiq3"><a class="white2" href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=2&action=skip&skip='.$skip.'&sort=ville';?>"><?php echo $animpos_19; ?></a></td>
									<td class="etiq3"><?php echo $animpos_20; ?></td>
									<td class="etiq3"><?php echo $animpos_21; ?></td>
								</tr>
								<tr>
									<td align="center" colspan="6">
										<input type="submit" name="action" value="<?php echo $animpos_22; ?>">
									</td>
								</tr>
							</table>
						</form>
					</Fieldset>
					<?php
					}
					### si tableau des selection des shops est non vide
					?>
			<?php
			### Tableau de séléction
			?>

				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<td>
						<!-- Retour -->
							<form action="?act=animmodif0a" method="post">
								<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
								<input type="hidden" name="s" value="1">
								<input type="submit" name="action" value="&lt;&lt;&nbsp; <?php echo $tool_05c; ?>">
							</form>
						</td>
						<?php
							### Création du Listing shop ==> si POS = vide alors on ne passe pas plus loin
								$posgroup = $infosjob['shopselection'];
								$f = explode("-%%", $posgroup);

								foreach ($f as $val) {
									$val = trim($val);
									if (is_numeric($val)) $pos[] = $val;
								}
							if (is_array ($pos)) {
						?>
							<form action="?act=animmodif<?php echo $s; ?>" method="post">
								<td class="contenu" align="center" width="150">
									<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
									<input type="hidden" name="s" value="1">
									<input type="submit" name="action" value="<?php echo $tool_03; ?> &nbsp;&gt;&gt;">
								</td>
							</form>
						<?php } ?>
					</tr>
				</table>
			<?php } ?>
			<?php
			#/ ## PAGE 2 : POS
			?>
			<?php if ($s == '2') { ?>
				<?php
					################### début vérif datein et dateout  ########################
					$datein = '3000-00-00';
					$dateout = '0000-00-00';

					$anim = new db('webanimbuild', 'idanimbuild', 'webneuro');
					$anim->inline("SELECT * FROM `webanimbuild` WHERE `idwebanimjob` = $idwebanimjob");
					while ($row2 = mysql_fetch_array($anim->result)) {
						if (($row2['animdate1'] < $datein) and ($row2['animdate1'] != '0000-00-00')) { $datein = $row2['animdate1']; }
						if ($row2['animdate2'] > $dateout) { $dateout = $row2['animdate2']; }
					}
						$_POST['datein'] = $datein;
						$_POST['dateout'] = $dateout;

						$modif = new db('webanimjob', 'idwebanimjob', 'webneuro');
						$modif->MODIFIE($idwebanimjob, array('datein' , 'dateout' ));

						$detailjob = new db('webanimjob', 'idwebanimjob ', 'webneuro');
						$detailjob->inline("SELECT * FROM `webanimjob` WHERE `idwebanimjob` = $idwebanimjob ");
						$infosjob = mysql_fetch_array($detailjob->result) ;

					################### Fin vérif datein et dateout  ########################
				?>
			<Fieldset class="width2">
				<legend class="width"><?php echo $vipdetail_02; ?></legend>
					<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="99%">
						<tr>
							<td colspan="20" align="right">
								<img src="<?php echo $illus; ?>info.png" alt="<?php echo $tool_05b; ?>"> &nbsp;
								<?php echo $tool_55; ?> <font color="#00427B">01/01/2005</font> &nbsp;&nbsp;&nbsp;
								<img src="<?php echo $illus; ?>info.png" alt="<?php echo $tool_05b; ?>"> &nbsp;
								<?php echo $tool_56; ?> <font color="#00427B">15:00</font> &nbsp;&nbsp;&nbsp;
								<img src="<?php echo $illus; ?>duplic-mission.gif" alt="<?php echo $tool_05g; ?>" height="20" width="20"> : <?php echo $tool_05g; ?> /
								<img src="<?php echo $illus; ?>redtrash.gif" alt="<?php echo $tool_05b; ?>"> : <?php echo $tool_05b; ?>
							</td>
						</tr>
						<tr class="vip">
							<td class="etiq2"></td>
							<td class="etiq2" align="center">Shop : <?php echo $vipdetail_1_01; ?></td>
							<td class="etiq2"><?php /* echo $vipdetail_1_03; */ ?></td>
							<td class="etiq2" align="center">#</td>
							<td class="etiq2"><?php /* echo $vipdetail_1_04; */ ?></td>
							<td class="etiq2" align="center"><?php echo $tool_50; ?></td>
							<td class="etiq2" align="center"><?php echo $tool_51; ?></td>
							<td class="etiq2" colspan="7" align="center"><?php echo $jour0; ?></td>
							<td class="etiq2" align="center"><?php echo $tool_52; ?></td>
							<td class="etiq2" align="center"><?php echo $tool_53; ?></td>
							<td class="etiq2" align="center"><?php echo $tool_52; ?></td>
							<td class="etiq2" align="center"><?php echo $tool_53; ?></td>
						</tr>
						<tr>
							<td colspan="7" align="left"></td>
							<td class="etiq2" width="10"><?php echo $jour1; ?></td>
							<td class="etiq2" width="10"><?php echo $jour2; ?></td>
							<td class="etiq2" width="10"><?php echo $jour3; ?></td>
							<td class="etiq2" width="10"><?php echo $jour4; ?></td>
							<td class="etiq2" width="10"><?php echo $jour5; ?></td>
							<td class="etiq2" width="10"><?php echo $jour6; ?></td>
							<td class="etiq2" width="10"><?php echo $jour7; ?></td>
						</tr>
						<?php
						### Création du Listing shop
							$posgroup = $infosjob['shopselection'];
							$f = explode("-%%", $posgroup);

							foreach ($f as $val) {
								$val = trim($val);
								if (is_numeric($val)) $pos[] = $val;
							}
						if (is_array ($pos)) {
							$sql = "SELECT idshop, codeshop, societe, cp, ville, adresse FROM shop
							WHERE `idshop` IN ('";

							foreach ($pos as $val) {
								$sql .= $val."', '";
							}
							$recherche = substr($sql, 0, -3);
							$recherche .= ") GROUP BY `idshop` ASC";
							$recherche .= " ORDER BY `societe` ASC";

							#/## Création du Listing shop
							$idclient = $_SESSION['idclient'];
							$listingshop = new db();
							$listingshop->inline("$recherche;");
							#/## Création du Listing shop
							$detailbuild = new db('webanimbuild', 'idanimbuild', 'webneuro');
							$detailbuild->inline("SELECT * FROM `webanimbuild` WHERE `idwebanimjob` = $idwebanimjob ORDER BY 'idanimbuild'");
							while ($infos = mysql_fetch_array($detailbuild->result)) {
								mysql_data_seek($listingshop->result,0);
								$option = '<option value="" selected></option>';
								$option2 = '<option value="" selected></option>';
								while ($rowshop = mysql_fetch_array($listingshop->result)) {
									$option .= '<option value="'.$rowshop['idshop'].'-xsep-'.$rowshop['societe'].'"';
									$option2 .= '<option value="'.$rowshop['idshop'].'-xsep-'.$rowshop['societe'].'"';
									if ($infos['idshop'] == $rowshop['idshop']) {$option .= ' selected ';}
									if ($infos['idshop'] == $rowshop['idshop']) {$option2 .= ' selected ';}
									$option .= '>'.$rowshop['societe'].'</option>';
									$option2 .= '>'.substr($rowshop['societe'], 0, 15).' - '.$rowshop['cp'].' '.substr($rowshop['ville'], 0, 10).'</option>';
								}
#							}
#							$detailbuild = new db('webanimbuild', 'idanimbuild', 'webneuro');
#							$detailbuild->inline("SELECT * FROM `webanimbuild` WHERE `idwebanimjob` = $idwebanimjob ORDER BY 'idanimbuild'");
#							while ($infos = mysql_fetch_array($detailbuild->result)) {
							$i++;
						$classe = "standard";
						?>
						<tr class="contenu">
							<form action="?act=animmodif<?php echo $s; ?>&type=update" method="post" name="CelsysForm" onsubmit="return scanForm(this)">
							<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
							<input type="hidden" name="idanimbuild" value="<?php echo $infos['idanimbuild'];?>">
							<input type="hidden" name="s" value="<?php echo $s;?>">
								<td class="<?php echo $classe; ?>"><?php echo $i; ?>&nbsp;</td>
								<td class="<?php echo $classe; ?>">
									<select name="idshop" size="1">
										<?php echo $option2; ?>
									</select>
								</td>
								<td class="<?php echo $classe; ?>"><?php echo $vipdetail_1_03; ?></td>
								<td class="<?php echo $classe; ?>"><input type="text" size="1" name="animnombre" value="<?php echo $infos['animnombre']; ?>"></td>
								<td class="<?php echo $classe; ?>"><?php echo $animaction_00; ?></td>
								<td class="<?php echo $classe; ?>"><input type="text" size="7" name="animdate1" value="<?php echo fdate($infos['animdate1']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input type="text" size="7" name="animdate2" value="<?php echo fdate($infos['animdate2']); ?>"></td>

								<td class="<?php echo $classe; ?>"><input type="checkbox" name="animdays[]" value="1" <?php if (strchr($infos['animdays'], '1')) { echo 'checked';}?>></td>
								<td class="<?php echo $classe; ?>"><input type="checkbox" name="animdays[]" value="2" <?php if (strchr($infos['animdays'], '2')) { echo 'checked';}?>></td>
								<td class="<?php echo $classe; ?>"><input type="checkbox" name="animdays[]" value="3" <?php if (strchr($infos['animdays'], '3')) { echo 'checked';}?>></td>
								<td class="<?php echo $classe; ?>"><input type="checkbox" name="animdays[]" value="4" <?php if (strchr($infos['animdays'], '4')) { echo 'checked';}?>></td>
								<td class="<?php echo $classe; ?>"><input type="checkbox" name="animdays[]" value="5" <?php if (strchr($infos['animdays'], '5')) { echo 'checked';}?>></td>
								<td class="<?php echo $classe; ?>"><input type="checkbox" name="animdays[]" value="6" <?php if (strchr($infos['animdays'], '6')) { echo 'checked';}?>></td>
								<td class="<?php echo $classe; ?>"><input type="checkbox" name="animdays[]" value="0" <?php if (strchr($infos['animdays'], '0')) { echo 'checked';}?>></td>

								<td class="<?php echo $classe; ?>"><input type="text" size="2" name="animin1" value="<?php echo ftime($infos['animin1']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input type="text" size="2" name="animout1" value="<?php echo ftime($infos['animout1']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input type="text" size="2" name="animin2" value="<?php echo ftime($infos['animin2']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input type="text" size="2" name="animout2" value="<?php echo ftime($infos['animout2']); ?>"></td>
								<td class="<?php echo $classe; ?>">
									<input type="submit" name="action" value="<?php echo $tool_02; ?>">
								</td>
								<td class="contenu">
									<input type="submit" name="action2" value="duplication" class="btn duplic-mission">
								</td>
							</form>
							<form action="?act=animmodif<?php echo $s; ?>&type=Delete" method="post" onSubmit="return confirm('<?php echo $tool_20; ?>')">
							<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
							<input type="hidden" name="idanimbuild" value="<?php echo $infos['idanimbuild'];?>">
							<input type="hidden" name="s" value="<?php echo $s;?>">
								<td class="contenu">
									<input type="submit" name="action" value="Delete" class="btn redtrash">
								</td>
							</form>
						</tr>
						<?php
						}
					}
						?>
						<tr class="white">
							<td colspan="10">
								<font color="white"><i><b><?php echo $tool_101; ?></b></i></font>
							</td>
							<td class="white" align="left"></td>
						</tr>

						<?php if ($infosjob['datein'] != '3000-00-00') {?>
							<tr>
								<td colspan="18" align="left"><br><?php echo $vipdetail_1_06; ?> : <?php echo $tool_50; ?> <?php echo fdate($infosjob['datein']); ?> <?php echo $tool_51; ?> <?php echo fdate($infosjob['dateout']); ?><br>&nbsp</td>
							</tr>
						<?php } ?>
						<tr>
							<td colspan="20" align="center">
								<form action="?act=animmodif<?php echo $s; ?>&type=ajout" method="post">
									<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
									<input type="hidden" name="s" value="<?php echo $s;?>">
									<input type="submit" name="action" value="<?php echo $vipdetail_1_07; ?>">
								</form>
							</td>
						</tr>

					</table>
				</fieldset>
				<br><br>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<td>
							<form action="?act=animmodif1a" method="post">
								<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
								<input type="hidden" name="s" value="2">
								<input type="submit" name="action" value="&lt;&lt;&nbsp; <?php echo $tool_05c; ?>">
							</form>
						</td>
						<form action="?act=animmodif<?php echo $s; ?>" method="post">
							<td class="contenu" align="center" width="150">
								<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
								<input type="hidden" name="s" value="3">
								<input type="submit" name="action" value="<?php echo $tool_03; ?> &nbsp;&gt;&gt;">
							</td>
						</form>
					</tr>
				</table>
			<?php } ?>

			<?php if ($s == '3') { /* fichiers en annexe */ ?>
				<br>
			<Fieldset class="width">
				<legend class="width"><?php echo $vipdetail_04; ?></legend>
				<?php include 'uploadannexe.php';	?>
			</Fieldset>
				<br><br>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
					<tr>
						<td>
							<form action="?act=animmodif2a" method="post">
								<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
								<input type="hidden" name="s" value="3">
								<input type="submit" name="action" value="&lt;&lt;&nbsp; <?php echo $tool_05c; ?>">
							</form>
						</td>
						<form action="?act=animmodif<?php echo $s; ?>" method="post">
							<td class="contenu" align="center" width="150">
								<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
								<input type="hidden" name="s" value="4">
								<input type="submit" name="action" value="<?php echo $tool_03; ?> &nbsp;&gt;&gt;">
							</td>
						</form>
					</tr>
				</table>
			<?php } /* #/## fichiers en annexe */ ?>
			<?php
			### TABLEAU Figé des info de job
			if ($s == '4') {
			?>
				<hr align="center" size="1" width="99%">
				<Fieldset class="width2">
					<legend class="width"><?php echo $tool_05f;?></legend>
					<table class="standard" border="0" cellspacing="1" cellpadding="6" align="center" width="98%">
						<tr>
							<td class="etiq3" width="230" valign="top">
								<?php echo $vipdetail_0_01; ?>
							</td>
							<td class="contenu" valign="top">
								<?php echo $infosjob['reference']; ?>
							</td>
						</tr>
						<tr>
							<td class="etiq3" valign="top">
								<?php echo $vipdetail_0_02; ?>
							</td>
							<td class="contenu" valign="top">
								<?php echo nl2br($infosjob['notejob']); ?>
							</td>
						</tr>
					</table>
				</fieldset>
			<?php
			}
			### TABLEAU Figé des info de job
			?>

			<?php if ($s == '4') { ?>
				<?php
					################### début vérif datein et dateout  ########################
					$datein = '3000-00-00';
					$dateout = '0000-00-00';

					$anim = new db('webanimbuild', 'idanimbuild', 'webneuro');
					$anim->inline("SELECT * FROM `webanimbuild` WHERE `idwebanimjob` = $idwebanimjob");
					while ($row2 = mysql_fetch_array($anim->result)) {
						if ($row2['animdate1'] < $datein) { $datein = $row2['animdate1']; }
						if ($row2['animdate2'] > $dateout) { $dateout = $row2['animdate2']; }
					}
						$_POST['datein'] = $datein;
						$_POST['dateout'] = $dateout;

						$modif = new db('webanimjob', 'idwebanimjob', 'webneuro');
						$modif->MODIFIE($idwebanimjob, array('datein' , 'dateout' ));

						$detailjob = new db('webanimjob', 'idwebanimjob ', 'webneuro');
						$detailjob->inline("SELECT * FROM `webanimjob` WHERE `idwebanimjob` = $idwebanimjob ");
						$infosjob = mysql_fetch_array($detailjob->result) ;

					################### Fin vérif datein et dateout  ########################
				?>
			<Fieldset class="width2">
				<legend class="width"><?php echo $vipdetail_02; ?></legend>
					<table class="standard" border="0" cellspacing="2" cellpadding="0" align="center" width="98%">
						<tr>
							<td width="32%"></td>
							<td>&nbsp</td>
							<td width="32%"></td>
							<td>&nbsp</td>
							<td width="32%"></td>
						</tr>
						<tr>
							<td colspan="3" align="left">
							<?php echo $vipdetail_1_06; ?> : <?php echo $tool_50; ?> <?php echo fdate($infosjob['datein']); ?> <?php echo $tool_51; ?> <?php echo fdate($infosjob['dateout']); ?><br>&nbsp</td>
						</tr>
						<?php
						$detailbuild = new db('webanimbuild', 'idanimbuild', 'webneuro');
						$detailbuild->inline("SELECT * FROM `webanimbuild` WHERE `idwebanimjob` = $idwebanimjob ORDER BY 'animactivite'");
						while ($infos = mysql_fetch_array($detailbuild->result)) {
						$i++;
						?>
							<tr class="vip">
								<td colspan="5" class="etiq2">
									 &nbsp; Shop <?php echo $i; ?> :
									<?php echo $infos['animactivite']; ?>
								</td>
							<?php
							$h = 0; #pour nombre hotesse
							$animdate1 = $infos['animdate1'];
							setlocale(LC_TIME, $_SESSION['lang']);
							while ($animdate1 <= $infos['animdate2']) {
								$dd = explode('-', $animdate1);
								$jour = date ("w", mktime (0,0,0,$dd[1],$dd[2],$dd[0]));
								$joursemaine = strftime("%A", mktime(0,0,0,$dd[1],$dd[2],$dd[0]));
								if (strchr($infos['animdays'], $jour)) { ### si jour de la semaine est dans la demande du client
									$h++;
							?>
									<?php
										if (fmod($h, 3) == 1) {
											echo '</tr><tr>';
										}
									?>
									<td>
										<table class="vip" border="0" cellspacing="1" cellpadding="0" align="center" width="99%">
											<tr class="vip">
												<td colspan="5" class="contenu">
													<?php echo $joursemaine.' '.fdate($animdate1);?>
												</th>
											</tr>
											<?php
											$animnombre = $infos['animnombre'];
											#$comptehote = 0;
											#while ($animnombre > 0) {
											#$comptehote++;
											?>
												<tr class="vip">
													<td class="vip"><b><?php echo $infos['animnombre'];?></b></td>
													<td class="vip">
														<?php echo $animaction_00a; ?>
													</td>
													<td class="vip" width="20">&nbsp;</td>
													<td class="vip"><?php echo ftime($infos['animin1']); ?> - <?php echo ftime($infos['animout1']); ?></td>
													<td class="vip"><?php echo ftime($infos['animin2']); ?> - <?php echo ftime($infos['animout2']); ?></td>

												</tr>
											<?php
											#	$animnombre--;
											# } ?>
										</table>
									</td>
									<td>&nbsp</td>

							<?php
									$dd = explode('-', $animdate1);
									$animdate1 = date ("Y-m-d", mktime (0,0,0,$dd[1],$dd[2]+1,$dd[0]));
								} else {	### si jour de la semaine n'est pas dans la demande du client
									$dd = explode('-', $animdate1);
									$animdate1 = date ("Y-m-d", mktime (0,0,0,$dd[1],$dd[2]+1,$dd[0]));
								} # / ## si jour de la semaine n'est pas dans la demande du client
							}
							?>
							</tr>
						<?php
						}
						?>
					</table>
				</fieldset>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td align="center" colspan="2">
							<form action="?act=animmodif4" method="post">
								<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
								<input type="hidden" name="s" value="4">
								<input type="submit" name="action" value="<?php echo $tool_05d1;?>">
							</form>
						</td>
					</tr>
					<tr>
						<td width="50%" align="left">
							<form action="?act=animmodif3a" method="post">
								<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
								<input type="hidden" name="s" value="4">
								<input type="submit" name="action" value="&lt;&lt;&nbsp; <?php echo $tool_05c;?>">
							</form>
						</td>
					<?php if ($_SESSION['new'] != 1) { ?>
						<td width="50%" align="right">
							<form action="<?php echo NIVO ?>webclient/adminclient.php" method="post">
								<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>">
								<input type="hidden" name="s" value="4">
								<input type="submit" name="action" value="<?php echo $tool_05e;?>">
							</form>
						</td>
					<?php } ?>
					</tr>
				</table>
				<?php } ?>
<?php
#/## OK
### voir si fiche n'est pas VISUALISEE en management Exception OU CLOTURE JOB
} else {
### PAS OK
?>
			<?php
### FIN
			if ($s == '5') { ?>
				<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
					<tr>
						<td class="newstit">
							<?php echo $_SESSION['qualite'].' '.$_SESSION['prenom'].' '.$_SESSION['nom'].', '.$menu_00.' '.$_SESSION['societe']; ?>
							<?php #echo '<br>idwebclient = '.$_SESSION['idwebclient'].' idclient = '.$_SESSION['idclient'].' idcofficer = '.$_SESSION['idcofficer'].' new = '.$_SESSION['new'].' newvip ='.$_SESSION['newvip']; ?>
						</td>
					</tr>
				</table>
				<br>
				<Fieldset class="width">
					<legend class="width"><?php echo $tool_02b; ?></legend>
						<table class="standard" border="0" cellspacing="3" cellpadding="0" align="center" width="98%">
							<tr>
								<td align="center">
									<br><br>
									<i>
									<b><font color="#00427B"><?php echo $detail_4_01; ?><br><i><?php echo fdatetime2($infosjob['datecommande']); ?></i><br><br></font></b>
									<br>

									<?php echo $detail_4_02; ?><br><br>
									<?php echo $detail_4_03; ?><br><br>
									<br><br>
									<b><font color="#00427B"><?php echo $detail_4_04; ?></font></b>
									<br>
									</i>
									<br><br>
									<a class="menu" href="http://www.exception2.be/poule/exception-l.jpg" target="popupC" onclick="window.open('','popupC','scrollbars=yes,status=yes,resizable=yes,width=475,height=670');"><img src="http://www.exception2.be/poule/exception2-m.jpg" border="1"></a>
									<br>
						<?php if ($_SESSION['new'] == 1) { ?>
									<br>
						<?php } ?>
								</td>
							</tr>
						</table>
					</fieldset>
					<br><br>
					<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
						<tr>
							<td align="center" colspan="2">
								<form action="<?php echo NIVO ?>webclient/adminclient.php" method="post">
									<input type="submit" name="action" value="<?php echo $tool_07; ?>">
								</form>
							</td>
						</tr>
					</table>
				<?php } ?>
<?php
### voir si fiche n'est pas VISUALISEE en management Exception
}
# echo 's = '.$s;
#/## PAS OK
?>
</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		// Highlight modified lines
		$("tr input").change(function () { $(this).parents('tr').css({'background-color' : '#FF6600'}); })
	});
</script>