<div class="corps2">
<?php
$detailjob = new db('animjob', 'idanimjob ');
$detailjob->inline("SELECT * FROM `animjob` WHERE `idanimjob` = $idanimjob ");
$infosjob = mysql_fetch_array($detailjob->result) ; 


if ($s == '') {$s = 0;}
################### Fin Code PHP ########################
?>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<td class="etiq2">
							<?php echo $vipdetail_0_01; ?>
						</td>
						<td class="contenu">
							<?php echo '('.$infosjob['etat'].') '.$idanimjob.' - '.$infosjob['reference']; ?>
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
							# Recherche des résultats
							
						$recherche='
							SELECT 
							an.idanimation, an.datem, an.weekm, an.reference, an.hin1, an.hout1, an.hin2, an.hout2, 
							an.kmpaye, an.kmfacture, an.frais, an.fraisfacture, an.isbriefing, an.produit, an.facturation, an.peoplenote, 
							an.ferie, an.idanimjob, an.livraisonpaye, an.livraisonfacture,
							j.genre, j.boncommande, 
							p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.gsm, p.codepeople, p.sexe, 
							s.idshop, s.codeshop, s.societe AS ssociete, s.ville AS sville 
							FROM animation an
							LEFT JOIN animjob j ON an.idanimjob = j.idanimjob 
							LEFT JOIN people p ON an.idpeople = p.idpeople
							LEFT JOIN shop s ON an.idshop = s.idshop
							WHERE an.idanimjob = '.$idanimjob.' GROUP BY s.idshop ORDER BY s.idshop, an.datem
						';
						$anim = new db();
						$anim->inline("$recherche;");
						$FoundCount = mysql_num_rows($anim->result); 
							
						$idshop = $infos['idshop'];
						$i = 0;
						while ($infos = mysql_fetch_array($anim->result)) { 
							$idshop = $infos['idshop'];
							$i++;
						?>
							<tr class="vip">
								<td colspan="3" class="etiq2">
									 &nbsp; Shop <?php echo $i; ?> : 
									<?php echo $infos['ssociete']; ?>
								</td>
							</tr>	
							<tr>	
							<?php
								# Recherche des résultats
								$anim2 = new db();
								$anim2->inline("SELECT * FROM `animation` WHERE `idanimjob` = $idanimjob AND `idshop` = '$idshop' GROUP BY 'datem' ORDER BY 'idshop', 'datem'");
							$h = 0; 
							while ($infos2 = mysql_fetch_array($anim2->result)) { 
								$datem = $infos2['datem'];
							$h++;
								if (fmod($h, 3) == 1) {
									echo '</tr><tr>';
								}
							?>
									<td valign="top">
										<table class="vip" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
											<tr class="vip">
												<td colspan="5" class="contenu">
													
													<?php 
													setlocale(LC_TIME, $_SESSION['lang']);
													$dd = explode('-', $infos2['datem']);
													$jour = date ("w", mktime (0,0,0,$dd[1],$dd[2],$dd[0]));
													$joursemaine = strftime("%A", mktime(0,0,0,$dd[1],$dd[2],$dd[0]));
													echo $joursemaine.' '.fdate($infos2['datem']); 
													?>
												</td>	
											</tr>
											<?php
												# Recherche des résultats
												$anim3 = new db();
												$anim3->inline("SELECT * FROM `animation` WHERE `idanimjob` = $idanimjob AND `idshop` = '$idshop' AND `datem` = '$datem' ORDER BY 'idshop', 'datem'");
											while ($infos3 = mysql_fetch_array($anim3->result)) { 
											?>
											<tr class="vip">
												<td class="vip">
													<?php if (strtolower($infos3['sexe']) == 'h') {echo $vipdetail_1_04a;} 
													else { echo $vipdetail_1_04b; } ?>
													<?php # echo $infos3['sexe'].$comptehote; ?>
												</td>
												<td class="vip"><?php echo ftime($infos3['hin1']) ?> - <?php echo ftime($infos3['hout1']); ?></td>
												<td class="vip"><?php echo ftime($infos3['hin2']) ?> - <?php echo ftime($infos3['hout2']); ?></td>
											</tr>
											<?php } ?>
										</table>
									</td>
							<?php } ?>
								</tr>
						<?php } ?>
					</table>
				</fieldset>		
</div>				