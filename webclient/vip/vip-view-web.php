<div class="corps2">
<?php
$detailjob = new db('webvipjob', 'idwebvipjob ', 'webneuro');
$detailjob->inline("SELECT * FROM `webvipjob` WHERE `idwebvipjob` = $idwebvipjob ");
$infosjob = mysql_fetch_array($detailjob->result) ; 


if ($s == '') {$s = 0;}
################### Fin Code PHP ########################
?>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<td class="etiq2" width="230">
							<?php echo $vipdetail_0_01; ?>
						</td>
						<td class="contenu">
							<?php echo $infosjob['reference']; ?>
						</td>
					</tr>
					<tr>
						<td class="etiq2">
							<?php echo $vipdetail_0_02; ?>
						</td>
						<td class="contenu">
							<?php echo $infosjob['notejob']; ?>
						</td>
					</tr>
					<tr>
						<td class="etiq2">
							<?php echo $vipdetail_0_03; ?><br>(<?php echo $vipdetail_0_04; ?>)
						</td>
						<td class="contenu">
							<?php echo $infosjob['notedeplac']; ?>
						</td>
					</tr>
					<tr>
						<td class="etiq2">
							<?php echo $vipdetail_0_05; ?><br>(<?php echo $vipdetail_0_06; ?>)
						</td>
						<td colspan="2" class="contenu">
							<?php echo $infosjob['noteprest']; ?>
						</td>
					</tr>
					<tr>
						<td class="etiq2">
							<?php echo $vipdetail_0_07; ?>
						</td>
						<td class="contenu">
							<?php echo $infosjob['noteloca']; ?>
						</td>
					</tr>
					<tr>
						<td class="etiq2">
							<?php echo $vipdetail_0_08; ?>
						</td>
						<td class="contenu">
							<?php if ($infosjob['notefrais'] == 'yes') { echo $tool_05; } ?><?php if ($infosjob['notefrais'] == 'no') { echo $tool_04; } ?>
						</td>
					</tr>
				</table>
				<br>


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
				<legend class="width">
				<?php echo $vipdetail_02; ?></legend>
					<table class="standard" border="0" cellspacing="3" cellpadding="0" align="center" width="98%">
						<tr>
							<td width="33%"><?php echo $vipdetail_1_06; ?>: <?php echo $tool_50; ?> <?php echo fdate($infosjob['datein']); ?> <?php echo $tool_51; ?> <?php echo fdate($infosjob['dateout']); ?><br>&nbsp</td>
							<td width="34%">&nbsp</td>
							<td width="33%">&nbsp</td>
						</tr>
						<?php
						$i = 0;
						$detailbuild = new db('webvipbuild', 'idvipbuild', 'webneuro');
						$detailbuild->inline("SELECT * FROM `webvipbuild` WHERE `idwebvipjob` = $idwebvipjob ORDER BY 'vipactivite'");
						while ($infos = mysql_fetch_array($detailbuild->result)) { 
						$i++;
						?>
							<tr class="vip">
								<td colspan="3" class="etiq2">
									 &nbsp; <?php echo $vipactivite_a; ?> <?php echo $i; ?> : 
									<?php $vipactivite = 'vipactivite_'.$infos['vipactivite'];
										echo $$vipactivitei;
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
											</td>	
										</tr>	
										<?php 
									#	$vipnombre = $infos['vipnombre'];
										#$comptehote = 0;
										#while ($vipnombre > 0) { 
										#$comptehote++; ?>
											<tr class="vip">
												<td class="vip"><b><?php echo  $infos['vipnombre']; ?></b></td>
												<td class="vip">
													<?php if ($infos['sexe'] == 'f') {echo $vipdetail_1_04b;} ?>
													<?php if ($infos['sexe'] == 'h') {echo $vipdetail_1_04a;} ?>
													<?# echo $comptehote; ?>
												</td>
												<td class="vip">De</td>
												<td class="vip"><?php echo ftime($infos['vipin']).' '.$tool_54; ?></td>
												<td class="vip">A</td>
												<td class="vip"><?php echo ftime($infos['vipout']).' '.$tool_54; ?></td>
											</tr>
										<?php 
										#	$vipnombre--;
									#	} ?>
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
</div>				