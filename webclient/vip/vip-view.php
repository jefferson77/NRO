<div class="corps2">
<?php
$detailjob = new db('vipjob', 'idvipjob ');
$detailjob->inline("SELECT * FROM `vipjob` WHERE `idvipjob` = $idvipjob ");
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
							<?php echo $infosjob['etat'].' - '.$infosjob['reference']; ?>
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
							<?php 
							if (!empty($infosjob['idshop'])) {
								$idshop=$infosjob['idshop'];
								$detail5 = new db();
								$detail5->inline("SELECT * FROM `shop` WHERE `idshop`=$idshop");
								$infosshop = mysql_fetch_array($detail5->result) ; 
								echo $infosshop['codeshop'].' '.$infosshop['societe'];
								echo '<br>'.$infosshop['adresse'];
								echo '<br>'.$infosshop['cp'].' '.$infosshop['ville'].'<br>';
							}
							?>
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
				</table>
				<br>


				<?php
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
						if ($infosjob['etat'] < 1) {
							# Recherche des résultats
							$vip = new db();
							$vip->inline("SELECT * FROM `vipdevis` WHERE `idvipjob` = '$idvipjob' GROUP BY 'vipactivite' ORDER BY 'vipactivite', 'vipdate'");
						} else {
							$vip = new db();
							$vip->inline("SELECT * FROM `vipmission` WHERE `idvipjob` = '$idvipjob' GROUP BY 'vipactivite' ORDER BY 'vipactivite', 'vipdate'");
						
						}
						$vipactivite = $infos['vipactivite'];
						$i = 0;
						while ($infos = mysql_fetch_array($vip->result)) { 
							$vipactivite = addslashes($infos['vipactivite']);
							$i++;
						?>
							<tr class="vip">
								<td colspan="3" class="etiq2">
									 &nbsp; <?php echo $vipactivite_a; ?> <?php echo $i; ?> : 
									<?php #$vipactivite2 = 'vipactivite_'.$infos['vipactivite'];
										# echo $$vipactivite2;
										echo $infos['vipactivite'];
									?>
								</td>
							</tr>	
							<tr>	
							<?php
							if ($infosjob['etat'] < 1) {
								# Recherche des résultats
								$vip2 = new db();
								$vip2->inline("SELECT * FROM `vipdevis` WHERE `idvipjob` = '$idvipjob' AND `vipactivite` = '$vipactivite' GROUP BY 'vipdate' ORDER BY 'vipactivite', 'vipdate'");
							} else {
								$vip2 = new db();
								$vip2->inline("SELECT * FROM `vipmission` WHERE `idvipjob` = '$idvipjob' AND `vipactivite` = '$vipactivite' GROUP BY 'vipdate' ORDER BY 'vipactivite', 'vipdate'");
							
							}
							$h = 0; 
							while ($infos2 = mysql_fetch_array($vip2->result)) { 
								$vipdate = $infos2['vipdate'];
							$h++;
								if (fmod($h, 3) == 1) {
									echo '</tr><tr>';
								}
							?>
									<td valign="top">
										<table class="vip" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
											<tr class="vip">
												<td colspan="6" class="contenu">
													<?php echo fdate($infos2['vipdate']); ?>
												</td>	
											</tr>
											<?php
											if ($infosjob['etat'] < 1) {
												# Recherche des résultats
												$vip3 = new db();
												$vip3->inline("SELECT * FROM `vipdevis` WHERE `idvipjob` = $idvipjob AND `vipactivite` = '$vipactivite' AND `vipdate` = '$vipdate' ORDER BY 'vipactivite', 'vipdate'");
											} else {
												$vip3 = new db();
												$vip3->inline("SELECT * FROM `vipmission` WHERE `idvipjob` = $idvipjob AND `vipactivite` = '$vipactivite' AND `vipdate` = '$vipdate' ORDER BY 'vipactivite', 'vipdate'");
											
											}
											$vipnombre = 0;
											while ($infos3 = mysql_fetch_array($vip3->result)) { 
												$vipnombre++;
												$sexe = $infos3['sexe'];
												$vipin = $infos3['vipin'];
												$vipout = $infos3['vipout'];
											}
											?>
											<tr class="vip">
												<td class="vip"><b><?php echo  $vipnombre; ?></b></td>
												<td class="vip">
													<?php if (strtolower($sexe) == 'f') {echo $vipdetail_1_04b;} ?>
													<?php if (strtolower($sexe) == 'h') {echo $vipdetail_1_04a;} ?>
													<?php # echo $infos3['sexe'].$comptehote; ?>
												</td>
												<td class="vip">De</td>
												<td class="vip"><?php echo ftime($vipin).' '.$tool_54; ?></td>
												<td class="vip">A</td>
												<td class="vip"><?php echo ftime($vipout).' '.$tool_54; ?></td>
											</tr>
										</table>
									</td>
							<?php } ?>
								</tr>
						<?php } ?>
					</table>
				</fieldset>		
</div>				