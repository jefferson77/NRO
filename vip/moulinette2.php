<?php
setlocale(LC_TIME, $_SESSION['lang']);

$detailjob = new db('vipjob', 'idvipjob');
$detailjob->inline("SELECT * FROM `vipjob` WHERE `idvipjob` = $idvipjob ");
$infosjob = mysql_fetch_array($detailjob->result) ;
$idshop = $infosjob['idshop'];
if (($s == '') or ($s == 0)) {$s = 1;}
if ($s == 2) {$s = 3;}

 if ($s == '1') {
			################### début vérif datein et dateout  ########################
					$datein = '3000-00-00';
					$dateout = '0000-00-00';

					$vip = new db('vipbuild', 'idvipbuild');
					$vip->inline("SELECT * FROM `vipbuild` WHERE `idvipjob` = $idvipjob");
					while ($row2 = mysql_fetch_array($vip->result)) {
						if (($row2['vipdate1'] < $datein) and ($row2['vipdate1'] != '0000-00-00')) { $datein = $row2['vipdate1']; }
						if ($row2['vipdate2'] > $dateout) { $dateout = $row2['vipdate2']; }
					}
						$_POST['datein'] = $datein;
						$_POST['dateout'] = $dateout;

						$modif = new db('vipjob', 'idvipjob');
						$modif->MODIFIE($idvipjob, array('datein' , 'dateout' ));

						$detailjob = new db('vipjob', 'idvipjob');
						$detailjob->inline("SELECT * FROM `vipjob` WHERE `idvipjob` = $idvipjob ");
						$infosjob = mysql_fetch_array($detailjob->result) ;

					################### Fin vérif datein et dateout  ########################
				?>
					<table class="standard" border="0" cellspacing="1" cellpadding="1" align="center" width="100%">
						<tr class="white" >
							<td class="white" colspan="22" align="right">
								<img src="<?php echo $illus; ?>info.png" alt="<?php echo $tool_05b; ?>"> &nbsp;
								<?php echo $tool_55; ?> <font color="#00427B">01/01/2005</font> &nbsp;&nbsp;&nbsp;
								<img src="<?php echo $illus; ?>info.png" alt="<?php echo $tool_05b; ?>"> &nbsp;
								<?php echo $tool_56; ?> <font color="#00427B">15:00</font> &nbsp;&nbsp;&nbsp;
								<img src="<?php echo $illus; ?>redtrash.gif" alt="<?php echo $tool_05b; ?>"> : <?php echo $tool_05b; ?>
							</td>
							<td class="white"></td>
						</tr>
						<tr>
							<td class="white" colspan="7"></td>
							<td class="etiq2" align="center" colspan="7">
								Prestations
							</td>
							<td class="etiq2" align="center" colspan="2">
								D&eacute;plac.
							</td>
							<td class="etiq2" align="center" colspan="2">
								Location
							</td>
							<td class="etiq2" align="center" colspan="4">
								Frais
							</td>
							<td class="etiq2"></td>
						</tr>
						<tr class="vip">
							<td class="etiq2"></td>
							<td class="etiq2" align="center">#</td>
							<td class="etiq2" align="center">Du</td>
							<td class="etiq2" align="center">Au</td>
							<td class="etiq2" align="center">Lieux</td>
							<td class="etiq2" align="center">Activit&eacute;</td>
							<td class="etiq2" align="center">sexe</td>
							<td class="etiq2" align="center">IN</td>
							<td class="etiq2" align="center">OUT</td>
							<td class="etiq2" align="center">Brk</td>
							<td class="etiq2" align="center">Night</td>
							<td class="etiq2" align="center">Sup</td>
							<td class="etiq2" align="center">TS</td>
							<td class="etiq2" align="center">Fts</td>
							<td class="etiq2" align="center">KM</td>
							<td class="etiq2" align="center">Fkm</td>
							<td class="etiq2" align="center">Unif</td>
							<td class="etiq2" align="center">Loc</td>
							<td class="etiq2" align="center">Cat</td>
							<td class="etiq2" align="center">Disp</td>
							<td class="etiq2" align="center">FR</td>
							<td class="etiq2"></td>
						</tr>
						<tr>
							<td class="white" colspan="7"></td>
							<td class="white" colspan="7"></td>
							<td class="white" colspan="2"></td>
							<td class="white" colspan="2"></td>
							<td class="white" colspan="4"></td>
							<td class="white"></td>
						</tr>
						<?php
						$classe = "standard";

						$recherche="SELECT
							vb.*,
							s.codeshop, s.societe
							FROM vipbuild vb
							LEFT JOIN shop s ON vb.idshop = s.idshop
							WHERE `idvipjob` = ".$idvipjob."
							ORDER BY 'idvipbuild'";

						$detailbuild = new db();
						$detailbuild->inline("$recherche;");
						while ($infos = mysql_fetch_array($detailbuild->result)) {
						$i++;
						?>
						<tr class="contenu">
							<form action="?act=vipmodif<?php echo $s; ?>&type=Delete" method="post" onSubmit="return confirm('<?php echo $tool_20; ?>')">
								<input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>">
								<input type="hidden" name="idvipbuild" value="<?php echo $infos['idvipbuild'];?>">
								<input type="hidden" name="s" value="<?php echo $s;?>">
								<td class="<?php echo $classe; ?>">
									<input type="submit" class="btn redtrash" name="action" value="Delete">
								</td>
							</form>
							<form action="?act=vipmodif<?php echo $s; ?>&type=update" method="post" name="CelsysForm" onsubmit="return scanForm(this)">
							<input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>">
							<input type="hidden" name="idvipbuild" value="<?php echo $infos['idvipbuild'];?>">
							<input type="hidden" name="s" value="<?php echo $s;?>">
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="1" name="vipnombre" value="<?php echo $infos['vipnombre']; ?>"></td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="7" name="vipdate1" value="<?php echo fdate($infos['vipdate1']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="7" name="vipdate2" value="<?php echo fdate($infos['vipdate2']); ?>"></td>
								<?php
									if (($infos['codeshop'] == '') and ($infos['societe'] == '')) {$selection = 'selection';}
									else { $selection = $infos['codeshop'].' '.$infos['societe'];}
								?>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="idshop" value="<?php echo $infos['idshop']; ?>"> <a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvip='.$infos['idvipbuild'].'&idvipjob='.$idvipjob.'&sel=lieu&etat=1';?>"><?php echo substr($selection, 0, 8); ?></a></td>
								<td class="<?php echo $classe; ?>">
									<select class="mini" name="vipactivite" size="1">
										<option value="1" <?php if ($infos['vipactivite'] == '1') {echo 'selected';} ?>><?php echo substr($vipactivite_1, 0, 11); ?></option>
										<option value="2" <?php if ($infos['vipactivite'] == '2') {echo 'selected';} ?>><?php echo substr($vipactivite_2, 0, 11); ?></option>
										<option value="3" <?php if ($infos['vipactivite'] == '3') {echo 'selected';} ?>><?php echo substr($vipactivite_3, 0, 11); ?></option>
										<option value="4" <?php if ($infos['vipactivite'] == '4') {echo 'selected';} ?>><?php echo substr($vipactivite_4, 0, 11); ?></option>
										<option value="5" <?php if ($infos['vipactivite'] == '5') {echo 'selected';} ?>><?php echo substr($vipactivite_5, 0, 11); ?></option>
										<option value="6" <?php if ($infos['vipactivite'] == '6') {echo 'selected';} ?>><?php echo substr($vipactivite_6, 0, 11); ?></option>
										<option value="7" <?php if ($infos['vipactivite'] == '7') {echo 'selected';} ?>><?php echo substr($vipactivite_7, 0, 11); ?></option>
										<option value="17" <?php if ($infos['vipactivite'] == '17') {echo 'selected';} ?>><?php echo substr($vipactivite_17, 0, 11); ?></option>
										<option value="8" <?php if ($infos['vipactivite'] == '8') {echo 'selected';} ?>><?php echo substr($vipactivite_8, 0, 11); ?></option>
										<option value="9" <?php if ($infos['vipactivite'] == '9') {echo 'selected';} ?>><?php echo substr($vipactivite_9, 0, 11); ?></option>
										<option value="10" <?php if ($infos['vipactivite'] == '10') {echo 'selected';} ?>><?php echo substr($vipactivite_10, 0, 11); ?></option>
										<option value="11" <?php if ($infos['vipactivite'] == '11') {echo 'selected';} ?>><?php echo substr($vipactivite_11, 0, 11); ?></option>
										<option value="12" <?php if ($infos['vipactivite'] == '12') {echo 'selected';} ?>><?php echo substr($vipactivite_12, 0, 11); ?></option>
										<option value="13" <?php if ($infos['vipactivite'] == '13') {echo 'selected';} ?>><?php echo substr($vipactivite_13, 0, 11); ?></option>
										<option value="14" <?php if ($infos['vipactivite'] == '14') {echo 'selected';} ?>><?php echo substr($vipactivite_14, 0, 11); ?></option>
										<option value="15" <?php if ($infos['vipactivite'] == '15') {echo 'selected';} ?>><?php echo substr($vipactivite_15, 0, 11); ?></option>
										<option value="16" <?php if ($infos['vipactivite'] == '16') {echo 'selected';} ?>><?php echo substr($vipactivite_16, 0, 11); ?></option>
									</select>
								</td>
								<td class="<?php echo $classe; ?>">
									<select class="mini" name="sexe" size="1">
										<option value="f" <?php if ($infos['sexe'] == 'f') {echo 'selected';} ?>>F</option>
										<option value="m" <?php if ($infos['sexe'] == 'm') {echo 'selected';} ?>>M</option>
										<option value="x" <?php if ($infos['sexe'] == 'x') {echo 'selected';} ?>>X</option>
									</select>
								</td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="vipin" value="<?php echo ftime($infos['vipin']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="vipout" value="<?php echo ftime($infos['vipout']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="brk" value="<?php echo fnbr($infos['brk']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="night" value="<?php echo fnbr($infos['night']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="h150" value="<?php echo fnbr($infos['h150']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="ts" value="<?php echo fnbr($infos['ts']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="fts" value="<?php echo fnbr($infos['fts']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="km" value="<?php echo fnbr($infos['km']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="fkm" value="<?php echo fnbr($infos['fkm']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="unif" value="<?php echo fnbr($infos['unif']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="loc1" value="<?php echo fnbr($infos['loc1']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="cat" value="<?php echo fnbr($infos['cat']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="disp" value="<?php echo fnbr($infos['disp']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="fr1" value="<?php echo fnbr($infos['fr1']); ?>"></td>
								<td class="<?php echo $classe; ?>"><input class="mini" type="submit" name="action" value="M"></td>
							</form>
						</tr>
						<?php
						}
						?>
						<tr>
							<td class="white" colspan="6"></td>
							<td class="white" colspan="7"></td>
							<td class="white" colspan="2"></td>
							<td class="white" colspan="2"></td>
							<td class="white" colspan="4"></td>
							<td class="white" colspan="2"></td>
						</tr>
						<tr class="vip">
							<td class="etiq2"></td>
							<td class="etiq2" align="center">#</td>
							<td class="etiq2" align="center">Du</td>
							<td class="etiq2" align="center">Au</td>
							<td class="etiq2" align="center">Lieux</td>
							<td class="etiq2" align="center">Activit&eacute;</td>
							<td class="etiq2" align="center">sexe</td>
							<td class="etiq2" align="center">IN</td>
							<td class="etiq2" align="center">OUT</td>
							<td class="etiq2" align="center">Brk</td>
							<td class="etiq2" align="center">Night</td>
							<td class="etiq2" align="center">Sup</td>
							<td class="etiq2" align="center">TS</td>
							<td class="etiq2" align="center">Fts</td>
							<td class="etiq2" align="center">KM</td>
							<td class="etiq2" align="center">Fkm</td>
							<td class="etiq2" align="center">Unif</td>
							<td class="etiq2" align="center">Loc</td>
							<td class="etiq2" align="center">Cat</td>
							<td class="etiq2" align="center">Disp</td>
							<td class="etiq2" align="center">FR</td>
							<td class="etiq2"></td>
						</tr>
						<?php if ($infosjob['datein'] != '3000-00-00') {?>
							<tr>
								<td class="white" colspan="23" align="left"><?php echo $vipdetail_1_06; ?> : <?php echo $tool_50; ?> <?php echo fdate($infosjob['datein']); ?> <?php echo $tool_51; ?> <?php echo fdate($infosjob['dateout']); ?></td>
							</tr>
						<?php } ?>
						<tr>
							<td class="white" colspan="23" align="center">
								<form action="?act=vipmodif<?php echo $s; ?>&type=ajout" method="post">
									<input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>">
									<input type="hidden" name="s" value="<?php echo $s;?>">
									<input type="hidden" name="idshop" value="<?php echo $idshop;?>">
									<input type="submit" name="action" value="<?php echo $vipdetail_1_07; ?>">
								</form>
							</td>
						</tr>
						<tr>
							<td class="white" colspan="23" align="right">
								<?php if ($infosjob['datein'] != '3000-00-00') {?>
									<form action="?act=vipmodif<?php echo $s; ?>" method="post">
										<input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>">
										<input type="hidden" name="s" value="2">
										<input type="submit" name="action" value="<?php echo $tool_03; ?> &nbsp;&gt;&gt;">
									</form>
								<?php } ?>
							</td>
						</tr>
					</table>
			<?php }

			### TABLEAU Figé des info de job
			if (($s == '1') or ($s == '2') or ($s == '3')) { ?>
			<hr align="center" size="1" width="100%">
			<?php } ?>

			<?php if ($s == '3') { ?>
				<?php
					################### début vérif datein et dateout  ########################
					$datein = '3000-00-00';
					$dateout = '0000-00-00';

					$vip = new db('vipbuild', 'idvipbuild');
					$vip->inline("SELECT * FROM `vipbuild` WHERE `idvipjob` = $idvipjob");
					while ($row2 = mysql_fetch_array($vip->result)) {
						if ($row2['vipdate1'] < $datein) { $datein = $row2['vipdate1']; }
						if ($row2['vipdate2'] > $dateout) { $dateout = $row2['vipdate2']; }
					}
						$_POST['datein'] = $datein;
						$_POST['dateout'] = $dateout;

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
						<tr class="white">
							<td colspan="3" align="left" class="white">
								<?php echo $vipdetail_1_06; ?> : <?php echo $tool_50; ?> <?php echo fdate($infosjob['datein']); ?> <?php echo $tool_51; ?> <?php echo fdate($infosjob['dateout']); ?><br>&nbsp
							</td>
						</tr>
						<?php
						$recherche="SELECT
							vb.*,
							s.codeshop, s.societe
							FROM vipbuild vb
							LEFT JOIN shop s ON vb.idshop = s.idshop
							WHERE `idvipjob` = ".$idvipjob."
							ORDER BY 'idvipbuild'";

						$detailbuild = new db();
						$detailbuild->inline("$recherche;");
						while ($infos = mysql_fetch_array($detailbuild->result)) {
						$i++;
						?>
							<tr class="white">
								<td colspan="3" class="etiq2">
									 &nbsp; <?php echo $vipactivite_a; ?> <?php echo $i; ?> :
									<?php $vipactivite = 'vipactivite_'.$infos['vipactivite'];
										echo $$vipactivite.' ( '.$infos['codeshop'].' '.$infos['societe'].' )';
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
									<table class="white" border="0" cellspacing="3" cellpadding="0" align="center" width="100%">
										<tr class="white">
											<td colspan="6" class="white">
												<?php echo fdate($vipdate1);?>
											</th>
										</tr>
											<tr class="vip">
												<td class="vip"><b><?php echo $infos['vipnombre'];?></b></td>
												<td class="vip">
													<?php if ($infos['sexe'] == 'f') {echo $vipdetail_1_04b;} ?>
													<?php if ($infos['sexe'] == 'm') {echo $vipdetail_1_04c;} ?>
													<?php if ($infos['sexe'] == 'x') {echo "Mixte";} ?>
												</td>
												<td class="vip"><?php echo $tool_52;?></td>
												<td class="vip"><?php echo ftime($infos['vipin']); ?> <?php echo $tool_54;?></td>
												<td class="vip"><?php echo $tool_53;?></td>
												<td class="vip"><?php echo ftime($infos['vipout']); ?> <?php echo $tool_54;?></td>
											</tr>
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
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
					<tr>
						<td align="center" colspan="2">
							<?php if ($idshop == '') {
								echo '<font color="red">Pour pouvoir valider ceci, il faut d&rsquo;abord attribuer un lieu au job !</font><br>';
							?>
							<form action="?act=vipmodif3a" method="post">
								<input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>">
								<input type="hidden" name="s" value="2">
								<input type="submit" name="action" value="<?php echo $tool_05d1;?>">
							</form>
							<?php } else { ?>
							<form action="adminvip.php?act=moulinette" target="_top" method="post">
								<input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>">
								<input type="hidden" name="s" value="3">
								<input type="submit" name="action" value="<?php echo $tool_05d1;?>">
							</form>
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td width="50%">
							<form action="?act=vipmodif2a" method="post">
								<input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>">
								<input type="hidden" name="s" value="0">
								<input type="submit" name="action" value="&lt;&lt;&nbsp; <?php echo $tool_05c;?>">
							</form>
						</td>
						<td width="50%" align="right">
						</td>
					</tr>
				</table>
				<?php } ?>
		</td></tr></table>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		// Highlight modified lines
		$("tr input").change(function () { $(this).parents('tr').css({'background-color' : '#FF6600'}); })
	});
</script>