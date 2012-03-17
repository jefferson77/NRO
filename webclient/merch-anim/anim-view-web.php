<div class="corps98">
<?php
setlocale(LC_TIME, $_SESSION['lang']);

$detailjob = new db('webanimjob', 'idwebanimjob ', 'webneuro');
$detailjob->inline("SELECT * FROM `webanimjob` WHERE `idwebanimjob` = $idwebanimjob ");
$infosjob = mysql_fetch_array($detailjob->result) ; 

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
											# } 
											?>
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
</div>
