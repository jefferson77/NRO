<div class="corps2">
<?php
setlocale(LC_TIME, $_SESSION['lang']);

$detailjob = new db('webvipjob', 'idwebvipjob ', 'webneuro');
$detailjob->inline("SELECT * FROM `webvipjob` WHERE `idwebvipjob` = $idwebvipjob ");
$infosjob = mysql_fetch_array($detailjob->result) ;

### voir si fiche n'est pas VISUALISEE en management Exception
### OK
if ($infosjob['etat'] <= 4) {


if ($s == '') {$s = 0;}
################### Fin Code PHP ########################
?>
		<table border="0" cellspacing="1" cellpadding="0" align="center" width="98%" height="20">
			<tr bgcolor="#EEEEEE">
				<td align="center" width="25%">
					<?php if ($s == 0) { $bouton = "boutonx2"; } else { $bouton = "boutonx"; }?>
					<table align="center" cellspacing="0" cellpadding="0" border="0">
						<tr>
						<td width="8"><img src="<?php echo $illus; ?>bouton/left.gif" width="8" height="24" alt="" border="0"></td>
						<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
						<td class="<?php echo $bouton; ?>" height="24" valign="middle" width="120">
							<b><?php echo $vipdetail_01; ?></b>
						</td>
						<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
						<td width="8"><img src="<?php echo $illus; ?>bouton/right.gif" width="8" height="24" alt="" border="0"></td>
						</tr>
					</table>
				</td>
				<td align="center" width="25%">
					<?php if ($s == 1) { $bouton = "boutonx2"; } else { $bouton = "boutonx"; }?>
					<table align="center" cellspacing="0" cellpadding="0" border="0">
						<tr>
						<td width="8"><img src="<?php echo $illus; ?>bouton/left.gif" width="8" height="24" alt="" border="0"></td>
						<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
						<td class="<?php echo $bouton; ?>" height="24" valign="middle" width="120">
							<b><?php echo $vipdetail_02; ?></b>
						</td>
						<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
						<td width="8"><img src="<?php echo $illus; ?>bouton/right.gif" width="8" height="24" alt="" border="0"></td>
						</tr>
					</table>
				</td>
				<td align="center" width="25%">
					<?php if ($s == 2) { $bouton = "boutonx2"; } else { $bouton = "boutonx"; }?>
					<table align="center" cellspacing="0" cellpadding="0" border="0">
						<tr>
						<td width="8"><img src="<?php echo $illus; ?>bouton/left.gif" width="8" height="24" alt="" border="0"></td>
						<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
						<td class="<?php echo $bouton; ?>" height="24" valign="middle" width="120">
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
						<td class="<?php echo $bouton; ?>" height="24" valign="middle" width="120">
							<b><?php echo $vipdetail_03; ?></b>
						</td>
						<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
						<td width="8"><img src="<?php echo $illus; ?>bouton/right.gif" width="8" height="24" alt="" border="0"></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<br>
			<?php if ($s == '0') { ?>
			<Fieldset class="width">
				<legend class="width"><?php echo $vipdetail_01; ?></legend>
				<form action="?act=vipmodif<?php echo $s; ?>" method="post">
					<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>">
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
								<?php echo $vipdetail_0_03; ?>
								<br>(<?php echo $vipdetail_0_04; ?>)
							</td>
							<td class="contenu">
								<textarea name="notedeplac" rows="4" cols="75"><?php echo $infosjob['notedeplac']; ?></textarea>
							</td>
						</tr>
						<tr>
							<td class="etiq3" valign="top">
								<?php echo $vipdetail_0_05; ?>
								<br>(<?php echo $vipdetail_0_06; ?>)
							</td>
							<td class="contenu">
								<textarea name="noteprest" rows="2" cols="75"><?php echo $infosjob['noteprest']; ?></textarea>
							</td>
						</tr>
						<tr>
							<td class="etiq3" valign="top">
								<?php echo $vipdetail_0_07; ?>
							</td>
							<td class="contenu">
								<textarea name="noteloca" rows="3" cols="75"><?php echo $infosjob['noteloca']; ?></textarea>
							</td>
						</tr>
						<tr>
							<td class="etiq3" valign="top">
								<?php echo $vipdetail_0_08; ?>
							</td>
							<td class="contenu">
								<input type="radio" name="notefrais" value="yes" <?php if ($infosjob['notefrais'] == 'yes') { echo 'checked'; } ?>> <?php echo $tool_05; ?> &nbsp; <input type="radio" name="notefrais" value="no" <?php if ($infosjob['notefrais'] == 'no') { echo 'checked'; } ?>> <?php echo $tool_04; ?>
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
						<tr>
							<td align="center" colspan="2">
								<input type="submit" name="action" value="<?php echo $tool_03; ?> &nbsp; &gt;&gt;">
							</td>
						</tr>
					</table>
				</form>
			</Fieldset>

				<!-- EFFACER LE JOB ET RETOUR LISTING -->
				<form action="?act=webdelete" method="post" onSubmit="return confirm('<?php echo $tool_19; ?>')">
					<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>">
					<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
						<tr>
							<td width="100%" align="right">
								<input type="submit" name="action" value="<?php echo $tool_05a; ?>">
							</td>
						</tr>
					</table>
				</form>
				<!-- / EFFACER LE JOB ET RETOUR LISTING -->

			<?php } ?>
			<?php if ($s == '1') { ?>
				<?php
					################### début vérif datein et dateout  ########################
					$datein = '3000-00-00';
					$dateout = '0000-00-00';

					$vip = new db('webvipbuild', 'idvipbuild', 'webneuro');
					$vip->inline("SELECT * FROM `webvipbuild` WHERE `idwebvipjob` = $idwebvipjob");
					while ($row2 = mysql_fetch_array($vip->result)) {
						if (($row2['vipdate1'] < $datein) and ($row2['vipdate1'] != '0000-00-00')) { $datein = $row2['vipdate1']; }
						if ($row2['vipdate2'] > $dateout) { $dateout = $row2['vipdate2']; }
					}
						$_POST['datein'] = $datein;
						$_POST['dateout'] = $dateout;

						$modif = new db('webvipjob', 'idwebvipjob', 'webneuro');
						$modif->MODIFIE($idwebvipjob, array('datein' , 'dateout' ));

						$detailjob = new db('webvipjob', 'idwebvipjob ', 'webneuro');
						$detailjob->inline("SELECT * FROM `webvipjob` WHERE `idwebvipjob` = $idwebvipjob ");
						$infosjob = mysql_fetch_array($detailjob->result) ;

					################### Fin vérif datein et dateout  ########################
				?>
			<Fieldset class="width">
				<legend class="width"><?php echo $vipdetail_02; ?></legend>
					<table class="standard" border="0" cellspacing="1" cellpadding="2" align="center" width="100%">
						<tr class="white" >
<!--
							<td class="white" colspan="2" align="left"><font color="peru"><b><?# echo $vipdetail_1_00; ?> </b></font></td>
							<td class="white" colspan="3">
								<img src="<?php echo $illus; ?>info.png" alt="<?php echo $tool_05b; ?>"> &nbsp;&nbsp;&nbsp;
								Les dates doivent &ecirc;tre au format : <font color="peru">01/01/2005</font> &nbsp;&nbsp;&nbsp;
								Les heures doivent &ecirc;tre au format : <font color="peru">15:00</font> &nbsp;&nbsp;&nbsp;
							</td>
-->
							<td class="white" colspan="11" align="right">
								<img src="<?php echo $illus; ?>info.png" alt="<?php echo $tool_05b; ?>"> &nbsp;
								<?php echo $tool_55; ?> <font color="#00427B">01/01/2005</font> &nbsp;&nbsp;&nbsp;
								<img src="<?php echo $illus; ?>info.png" alt="<?php echo $tool_05b; ?>"> &nbsp;
								<?php echo $tool_56; ?> <font color="#00427B">15:00</font> &nbsp;&nbsp;&nbsp;
								<img src="<?php echo $illus; ?>redtrash.gif" alt="<?php echo $tool_05b; ?>"> : <?php echo $tool_05b; ?>
							</td>
						</tr>
						<tr class="vip">
							<td class="etiq2"></td>
							<td class="etiq2" align="center"><?php echo $vipdetail_1_01; ?></td>
							<td class="etiq2"></td>
							<td class="etiq2" align="center">#</td>
							<td class="etiq2"></td>
							<td class="etiq2" align="center"><?php echo $tool_50; ?></td>
							<td class="etiq2" align="center"><?php echo $tool_51; ?></td>
							<td class="etiq2" align="center"><?php echo $tool_52; ?></td>
							<td class="etiq2" align="center"><?php echo $tool_53; ?></td>
							<td class="etiq2" colspan="2"></td>
						</tr>
<!--
						<tr class="vip">
							<td class="etiq2"></td>
							<td class="etiq2"><?php echo $vipactivite_3; ?></td>
							<td class="etiq2"><?php echo $vipdetail_1_03; ?></td>
							<td class="etiq2">2</td>
							<td class="etiq2"><?php echo $vipdetail_1_04; ?></td>
							<td class="etiq2">01/01/2004</td>
							<td class="etiq2">05/01/2004</td>
							<td class="etiq2">08:00</td>
							<td class="etiq2">16:00</td>
							<td class="white" colspan="2"></td>
						</tr>
-->
						<tr>
							<td class="white" colspan="3" align="left">&nbsp;</td>
							<td class="white" colspan="10" align="right">
							</td>
						</tr>
						<?php
						$classe = "standard";
#						$classe = "contenu";
						$detailbuild = new db('webvipbuild', 'idvipbuild', 'webneuro');
						$detailbuild->inline("SELECT * FROM `webvipbuild` WHERE `idwebvipjob` = $idwebvipjob ORDER BY 'idvipbuild'");
						while ($infos = mysql_fetch_array($detailbuild->result)) {
						$i++;
						?>
						<tr class="contenu">
							<form action="?act=vipmodif<?php echo $s; ?>&type=update" method="post" name="CelsysForm" onsubmit="return scanForm(this)">
							<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>">
							<input type="hidden" name="idvipbuild" value="<?php echo $infos['idvipbuild'];?>">
							<input type="hidden" name="s" value="<?php echo $s;?>">
								<td><?php echo $i; ?>&nbsp;</td>
								<td class="<?php echo $classe; ?>">
									<select name="vipactivite" size="1">
										<option value="1" <?php if ($infos['vipactivite'] == '1') {echo 'selected';} ?>><?php echo $vipactivite_1; ?></option>
										<option value="2" <?php if ($infos['vipactivite'] == '2') {echo 'selected';} ?>><?php echo $vipactivite_2; ?></option>
										<option value="3" <?php if ($infos['vipactivite'] == '3') {echo 'selected';} ?>><?php echo $vipactivite_3; ?></option>
										<option value="4" <?php if ($infos['vipactivite'] == '4') {echo 'selected';} ?>><?php echo $vipactivite_4; ?></option>
										<option value="5" <?php if ($infos['vipactivite'] == '5') {echo 'selected';} ?>><?php echo $vipactivite_5; ?></option>
										<option value="6" <?php if ($infos['vipactivite'] == '6') {echo 'selected';} ?>><?php echo $vipactivite_6; ?></option>
										<option value="7" <?php if ($infos['vipactivite'] == '7') {echo 'selected';} ?>><?php echo $vipactivite_7; ?></option>
										<option value="8" <?php if ($infos['vipactivite'] == '8') {echo 'selected';} ?>><?php echo $vipactivite_8; ?></option>
										<option value="9" <?php if ($infos['vipactivite'] == '9') {echo 'selected';} ?>><?php echo $vipactivite_9; ?></option>
										<option value="10" <?php if ($infos['vipactivite'] == '10') {echo 'selected';} ?>><?php echo $vipactivite_10; ?></option>
										<option value="11" <?php if ($infos['vipactivite'] == '11') {echo 'selected';} ?>><?php echo $vipactivite_11; ?></option>
										<option value="12" <?php if ($infos['vipactivite'] == '12') {echo 'selected';} ?>><?php echo $vipactivite_12; ?></option>
										<option value="13" <?php if ($infos['vipactivite'] == '13') {echo 'selected';} ?>><?php echo $vipactivite_13; ?></option>
										<option value="14" <?php if ($infos['vipactivite'] == '14') {echo 'selected';} ?>><?php echo $vipactivite_14; ?></option>
										<option value="15" <?php if ($infos['vipactivite'] == '15') {echo 'selected';} ?>><?php echo $vipactivite_15; ?></option>
										<option value="16" <?php if ($infos['vipactivite'] == '16') {echo 'selected';} ?>><?php echo $vipactivite_16; ?></option>
									</select>
								</td>
								<td class="<?php echo $classe; ?>"><?php echo $vipdetail_1_03; ?></td>
								<td class="<?php echo $classe; ?>"><input type="text" size="2" name="vipnombre" value="<?php echo $infos['vipnombre']; ?>"></td>
								<td class="<?php echo $classe; ?>">
									<select name="sexe" size="1">
										<option value="f" <?php if ($infos['sexe'] == 'f') {echo 'selected';} ?>><?php echo $vipdetail_1_04; ?></option>
										<option value="h" <?php if ($infos['sexe'] == 'h') {echo 'selected';} ?>><?php echo $vipdetail_1_04a; ?></option>
									</select>
								</td>
								<td class="<?php echo $classe; ?>"><?php #echo $tool_50; ?> <input type="text" size="10" name="vipdate1" value="<?php echo fdate($infos['vipdate1']); ?>"></td>
								<td class="<?php echo $classe; ?>"><?php #echo $tool_51; ?> <input type="text" size="10" name="vipdate2" value="<?php echo fdate($infos['vipdate2']); ?>"></td>
								<td class="<?php echo $classe; ?>"><?php #echo $tool_52; ?> <input type="text" size="5" name="vipin" value="<?php echo ftime($infos['vipin']); ?>"> <?php echo $tool_54; ?></td>
								<td class="<?php echo $classe; ?>"><?php #echo $tool_53; ?> <input type="text" size="5" name="vipout" value="<?php echo ftime($infos['vipout']); ?>"> <?php echo $tool_54; ?></td>
								<td class="<?php echo $classe; ?>">
									<input type="submit" name="action" value="<?php echo $tool_02; ?>">
								</td>
							</form>
							<form action="?act=vipmodif<?php echo $s; ?>&type=Delete" method="post" onSubmit="return confirm('<?php echo $tool_20; ?>')">
							<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>">
							<input type="hidden" name="idvipbuild" value="<?php echo $infos['idvipbuild'];?>">
							<input type="hidden" name="s" value="<?php echo $s;?>">
								<td class="contenu">
									<input type="submit" name="action" value="Delete" class="btn redtrash">
								</td>
							</form>
						</tr>
						<?php
						}
						?>
						<tr class="white">
							<td colspan="10">
								<font color="white"><i><b>Ligne orange ? Les modifications de cette ligne doivent &ecirc;tre valid&eacute;es.</b></i></font>
							</td>
							<td class="white" align="left"></td>
						</tr>

						<?php if ($infosjob['datein'] != '3000-00-00') {?>
							<tr>
								<td class="white" colspan="14" align="left"><br><?php echo $vipdetail_1_06; ?> : <?php echo $tool_50; ?> <?php echo fdate($infosjob['datein']); ?> <?php echo $tool_51; ?> <?php echo fdate($infosjob['dateout']); ?></td>
							</tr>
						<?php } ?>
						<tr>
							<td class="white" colspan="11" align="center">
								<form action="?act=vipmodif<?php echo $s; ?>&type=ajout" method="post">
									<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>">
									<input type="hidden" name="s" value="<?php echo $s;?>">
									<input type="submit" name="action" value="<?php echo $vipdetail_1_07; ?>">
								</form>
							</td>
						</tr>
					</table>
				</fieldset>
				<br><br>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" width="95%">
					<tr>
						<td>
							<form action="?act=vipmodif0a" method="post">
								<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>">
								<input type="hidden" name="s" value="1">
								<input type="submit" name="action" value="&lt;&lt;&nbsp; <?php echo $tool_05c; ?>">
							</form>
						</td>
						<td align="right">
							<?php if ($infosjob['datein'] != '3000-00-00') {?>
								<form action="?act=vipmodif<?php echo $s; ?>" method="post">
									<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>">
									<input type="hidden" name="s" value="2">
									<input type="submit" name="action" value="<?php echo $tool_03; ?> &nbsp;&gt;&gt;">
								</form>
							<?php } ?>
						</td>
					</tr>
				</table>
			<?php } ?>
			<?php if ($s == '2') { ### fichiers en annexe ?>
				<br>
			<Fieldset class="width">
				<legend class="width"><?php echo $vipdetail_04; ?></legend>
				<?php include 'uploadannexe.php';	?>
			</Fieldset>
				<br><br>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
					<tr>
						<td>
							<form action="?act=vipmodif1a" method="post">
								<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>">
								<input type="hidden" name="s" value="2">
								<input type="submit" name="action" value="&lt;&lt;&nbsp; <?php echo $tool_05c; ?>">
							</form>
						</td>
						<td align="right">
							<form action="?act=vipmodif<?php echo $s; ?>" method="post">
								<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>">
								<input type="hidden" name="s" value="3">
								<input type="submit" name="action" value="<?php echo $tool_03; ?> &nbsp;&gt;&gt;">
							</form>
						</td>
					</tr>
				</table>

			<?php } #/## fichiers en annexe ?>

			<?php ### TABLEAU Figé des info de job
			if (($s == '1') or ($s == '2') or ($s == '3')) { ?>
			<hr align="center" size="1" width="100%">
			<Fieldset class="width">
				<legend class="width"><?php echo $vipdetail_01;?></legend>
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
					<tr>
						<td class="etiq3" valign="top">
							<?php echo $vipdetail_0_03; ?>
							<br>(<?php echo $vipdetail_0_04; ?>)
						</td>
						<td class="contenu" valign="top">
							<?php echo $infosjob['notedeplac']; ?>
						</td>
					</tr>
					<tr>
						<td class="etiq3" valign="top">
							<?php echo $vipdetail_0_05; ?>
							<br>(<?php echo $vipdetail_0_06; ?>)
						</td>
						<td class="contenu" valign="top">
							<?php echo $infosjob['noteprest']; ?>
						</td>
					</tr>
					<tr>
						<td class="etiq3" valign="top">
							<?php echo $vipdetail_0_07; ?>
						</td>
						<td class="contenu" valign="top">
							<?php echo $infosjob['noteloca']; ?>
						</td>
					</tr>
					<tr>
						<td class="etiq3" valign="top">
							<?php echo $vipdetail_0_08; ?>
						</td>
						<td class="contenu" valign="top">
							<?php if ($infosjob['notefrais'] == 'yes') { echo $tool_05; } ?><?php if ($infosjob['notefrais'] == 'no') { echo $tool_04; } ?>
						</td>
					</tr>
				</table>
				</fieldset>
			<?php } ?>

			<?php if ($s == '3') { ?>
				<?php
					################### début vérif datein et dateout  ########################
					$datein = '3000-00-00';
					$dateout = '0000-00-00';

					$vip = new db('webvipbuild', 'idvipbuild', 'webneuro');
					$vip->inline("SELECT * FROM `webvipbuild` WHERE `idwebvipjob` = $idwebvipjob");
					while ($row2 = mysql_fetch_array($vip->result)) {
						if ($row2['vipdate1'] < $datein) { $datein = $row2['vipdate1']; }
						if ($row2['vipdate2'] > $dateout) { $dateout = $row2['vipdate2']; }
					}
						$_POST['datein'] = $datein;
						$_POST['dateout'] = $dateout;

						$modif = new db('webvipjob', 'idwebvipjob', 'webneuro');
						$modif->MODIFIE($idwebvipjob, array('datein' , 'dateout' ));

						$detailjob = new db('webvipjob', 'idwebvipjob ', 'webneuro');
						$detailjob->inline("SELECT * FROM `webvipjob` WHERE `idwebvipjob` = $idwebvipjob ");
						$infosjob = mysql_fetch_array($detailjob->result) ;

					################### Fin vérif datein et dateout  ########################
				?>
			<Fieldset class="width">
				<legend class="width"><?php echo $vipdetail_02; ?></legend>
					<table class="standard" border="0" cellspacing="3" cellpadding="0" align="center" width="98%">
						<tr>
							<td width="33%"></td>
							<td width="34%"></td>
							<td width="33%"></td>
						</tr>
						<tr>
							<td colspan="3" align="left">
							<?php echo $vipdetail_1_06; ?> : <?php echo $tool_50; ?> <?php echo fdate($infosjob['datein']); ?> <?php echo $tool_51; ?> <?php echo fdate($infosjob['dateout']); ?><br>&nbsp</td>
						</tr>
						<?php
						$detailbuild = new db('webvipbuild', 'idvipbuild', 'webneuro');
						$detailbuild->inline("SELECT * FROM `webvipbuild` WHERE `idwebvipjob` = $idwebvipjob ORDER BY 'idvipbuild'");
						while ($infos = mysql_fetch_array($detailbuild->result)) {
						$i++;
						?>
							<tr class="vip">
								<td colspan="3" class="etiq2">
									 &nbsp; <?php echo $vipactivite_a; ?> <?php echo $i; ?> :
									<?php $vipactivite = 'vipactivite_'.$infos['vipactivite'];
										echo $$vipactivite;
									?>
								</td>
							<?php
							$h = 0; #pour nombre hotesse
							$vipdate1 = $infos['vipdate1'];
							while ($vipdate1 <= $infos['vipdate2']) {
							$h++;
							?>
								<?php
									if (fmod($h, 3) == 1) {
										echo '</tr><tr>';
									}
								?>
								<td>
									<table class="vip" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
										<tr class="vip">
											<td colspan="6" class="contenu">
												<?php echo fdate($vipdate1);?>
											</th>
										</tr>
										<?php
										#$vipnombre = $infos['vipnombre'];
										#$comptehote = 0;
										#while ($vipnombre > 0) {
										#$comptehote++; ?>
											<tr class="vip">
												<td class="vip"><b><?php echo $infos['vipnombre'];?></b></td>
												<td class="vip">
													<?php if ($infos['sexe'] == 'f') {echo $vipdetail_1_04;} ?>
													<?php if ($infos['sexe'] == 'h') {echo $vipdetail_1_04b;} ?>
													<?php # echo $comptehote; ?>
												</td>
												<td class="vip"><?php echo $tool_52;?></td>
												<td class="vip"><?php echo ftime($infos['vipin']); ?> <?php echo $tool_54;?></td>
												<td class="vip"><?php echo $tool_53;?></td>
												<td class="vip"><?php echo ftime($infos['vipout']); ?> <?php echo $tool_54;?></td>
											</tr>
										<?php
											# $vipnombre--;
										# } ?>
									</table>
								</td>
							<?php
								$dd = explode('-', $vipdate1);
								$vipdate1 = date ("Y-m-d", mktime (0,0,0,$dd[1],$dd[2]+1,$dd[0]));
							}
							?>
							</tr>
						<?php
						}
						?>
					</table>
				</fieldset>
			<Fieldset class="width">
				<legend class="width"><?php echo $vipdetail_04; ?></legend>
					<table border="0" cellspacing="0" cellpadding="4">
					<?php
					$path = Conf::read('Env.root').'media/annexe/vipweb/';
					$ledir = $path;
					$d = opendir ($ledir);
					$nomvip = 'vip-'.$idwebvipjob.'-';
					while ($name = readdir($d)) {
						if (($name != '.') and ($name != '..') and ($name != 'index.php') and ($name != 'index2.php') and ($name != 'temp') and (strchr($name, $nomvip))) {
						$tour++;
					?>
						<tr>
							<td class="contenu">
								<?php echo $name; ?>
							</td>
						</tr>
					<?php
						}
					}
					closedir ($d);
					 ?>
					<?php if($tour = 0) { ?>
						<tr>
							<td class="contenu">
								Aucun fichier n'est annex&eacute; &agrave; votre action.
							</td>
						</tr>
					<?php } ?>
					</table>
				</fieldset>

				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
					<tr>
						<td align="center" colspan="2">
							<form action="?act=vipmodif3" method="post">
								<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>">
								<input type="hidden" name="s" value="3">
								<input type="submit" name="action" value="<?php echo $tool_05d1;?>">
							</form>
						</td>
					</tr>
					<tr>
						<td width="50%">
							<form action="?act=vipmodif2a" method="post">
								<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>">
								<input type="hidden" name="s" value="3">
								<input type="submit" name="action" value="&lt;&lt;&nbsp; <?php echo $tool_05c;?>">
							</form>
						</td>
						<td width="50%" align="right">
						<?php if ($_SESSION['new'] != 1) { ?>
							<form action="<?php echo NIVO ?>webclient/adminclient.php" method="post">
								<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>">
								<input type="hidden" name="s" value="3">
								<input type="submit" name="action" value="<?php echo $tool_05e;?>">
							</form>
						<?php } ?>
						</td>
					</tr>
					<?php if ($_SESSION['new'] != 1) { ?>
						<tr>
							<td align="right" colspan="2">
								<font color="#00427B"><i>( <?php echo $tool_05e1;?> )</i></font>
							</td>
						</tr>
					<?php } ?>
				</table>
				<?php } ?>
		</td></tr></table>

<?php
#/## OK
### voir si fiche n'est pas VISUALISEE en management Exception OU CLOTURE JOB
} else {
### PAS OK
?>
			<?php
### FIN
			if ($s == '4') { ?>
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
			<?php } else { ?>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<td width="98%" align="center">
							<br><br>
							<Fieldset>
							Fiche indisponible <br>
							==> management<br>
							<br>
							Retour menu
							<br><br><br>
							<a href="<?php echo NIVO ?>webclient/adminclient.php">MENU</a>
							<br><br>
							</Fieldset>
						</td>
					</tr>
				</table>
			<?php } ?>
<?php
### voir si fiche n'est pas VISUALISEE en management Exception
}
#/## PAS OK
?>
</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		// Highlight modified lines
		$("tr input").change(function () { $(this).parents('tr').css({'background-color' : '#FF6600'}); })
	});
</script>