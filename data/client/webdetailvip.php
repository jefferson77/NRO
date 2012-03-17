<?php
### recherche client et cofficer
	$detail3 = new db('webclient', 'idwebclient', 'webneuro');
	$detail3->inline("SELECT * FROM `webclient` WHERE `idwebclient` = $idwebclient");
	$infosclient = mysql_fetch_array($detail3->result) ; 
#/ ## recherche client et cofficer

################### Code PHP ########################
$detailjob = new db('webvipjob', 'idwebvipjob ', 'webneuro');
$detailjob->inline("SELECT * FROM `webvipjob` WHERE `idwebclient` = $idwebclient AND `isnew` = 1");
$infosjob = mysql_fetch_array($detailjob->result) ; 
$idwebvipjob = $infosjob['idwebvipjob'];
################### Fin Code PHP ########################
?>
<?php #  Corps de la Page ?>
<div id="centerzonelarge">
				<br>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip" rowspan="4">
							Client
						</th>
						<td>
							<?php echo $infosclient['idwebclient']; ?> <?php echo $infosclient['societe']; ?>
						</td>
						<td>
							<?php echo $infosclient['qualite'].' '.$infosclient['cnom'].' '.$infosclient['cprenom'].' '.$infosclient['langue'].' ('.$infosclient['fonction'].')'; ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo $infosclient['adresse']; ?> <?php echo $infosclient['cp']; ?> <?php echo $infosclient['ville']; ?> <?php echo $infosclient['pays']; ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							Tel : <?php echo $infosclient['tel']; ?> Fax : <?php echo $infosclient['fax']; ?> Email : <?php echo $infosclient['email']; ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							Tva : <?php echo $infosclient['codetva']; ?> <?php echo $infosclient['tva']; ?>
						</td>
					</tr>
				</table>
				<br>
	<?php 
	### SI MISSION VIP EXISTE
		if (!empty($idwebvipjob)) { 
	?>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<td valign="top">
							<table class="vip" border="0" cellspacing="1" cellpadding="0" align="center" width="250">
								<tr>
									<th class="vip">
										Nom de l&rsquo;action
									</td>
								</tr>
								<tr>
									<td>
										<?php echo $infosjob['reference']; ?>
									</td>
								</tr>
								<tr>
									<td>
										&nbsp;
									</td>
								</tr>
								<tr>
									<th class="vip">
										Lieu (Adresse compl&egrave;te)
									</td>
								</tr>
								<tr>
									<td>
										<?php echo $infosjob['notedeplac']; ?>
									</td>
								</tr>
							</table>
						</td>
						<td valign="top">
							<table class="vip" border="0" cellspacing="1" cellpadding="0" align="center" width="300">
								<tr>
									<th class="vip" colspan="2">
										Description de l&rsquo;action
									</td>
								</tr>
								<tr>
									<td>
										<?php echo $infosjob['notejob']; ?>
									</td>
								</tr>
								<tr>
									<td>
										&nbsp;
									</td>
								</tr>
								<tr>
									<th class="vip">
										Langues (n&eacute;cessaires pour cette action)
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<?php echo $infosjob['noteprest']; ?>
									</td>
								</tr>
							</table>
						</td>
						<td valign="top">
							<table class="vip" border="0" cellspacing="1" cellpadding="0" align="center" width="250">
								<tr>
									<th class="vip">
										Uniforme
									</td>
								</tr>
								<tr>
									<td>
										<?php echo $infosjob['noteloca']; ?>
									</td>
								</tr>
								<tr>
									<td>
										&nbsp;
									</td>
								</tr>
								<tr>
									<th class="vip">
										Catering Fourni
									</td>
								</tr>
								<tr>
									<td>
										<?php if ($infosjob['notefrais'] == 'yes') { echo 'Oui'; } ?><?php if ($infosjob['notefrais'] == 'no') { echo 'Non'; } ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
							&nbsp;
						</td>
					</tr>
				</table>
				<br>
				<fieldset><legend>Activit&eacute;s</legend>
					<table class="standard" border="0" cellspacing="3" cellpadding="0" align="center" width="98%">
						<tr>
							<td width="33%">&nbsp;<td>
							<td width="33%">&nbsp;<td>
							<td>&nbsp;<td>
						</tr>
						<tr>
							<td colspan="3" align="left">dates actuelles : du <?php echo fdate($infosjob['datein']); ?> au <?php echo fdate($infosjob['dateout']); ?><br>&nbsp</td>
						</tr>
						<?php
						$detailbuild = new db('webvipbuild', 'idvipbuild', 'webneuro');
						$detailbuild->inline("SELECT * FROM `webvipbuild` WHERE `idwebvipjob` = $idwebvipjob ORDER BY 'vipactivite'");
						while ($infos = mysql_fetch_array($detailbuild->result)) { 
						$i++;
						?>
							<tr>
								<td coslpan="3">
									Activit&eacute; <?php echo $i; ?> : 
									<?php 
									if ($infos['vipactivite'] == '1') {echo 'Bar';}
									if ($infos['vipactivite'] == '2') {echo 'Salle';}
									if ($infos['vipactivite'] == '3') {echo 'caf&eacute; service';}
									if ($infos['vipactivite'] == '4') {echo 'massage du dos de Pat';} 
									?>
								</td>
							</tr>
							<tr>
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
									<table class="vip" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
										<tr class="vip">
											<th coslpan="3" class="vip">
												<?php echo fdate($vipdate1);?>
											</th>	
										</tr>	
										<?php 
										$vipnombre = $infos['vipnombre'];
										$comptehote = 0;
										while ($vipnombre > 0) { 
										$comptehote++; ?>
											<tr class="vip">
												<td class="vip">
													<?php if ($infos['sexe'] == 'f') {echo 'H&ocirc;tesse';} ?>
													<?php if ($infos['sexe'] == 'h') {echo 'Promoboy';} ?>
													<?php echo $comptehote; ?>
												</td>
												<td class="vip">De</td>
												<td class="vip"><?php echo ftime($infos['vipin']); ?> h</td>
												<td class="vip">A</td>
												<td class="vip"><?php echo ftime($infos['vipout']); ?> h</td>
											</tr>
										<?php 
											$vipnombre--;
										} ?>
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
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<td align="center" colspan="2">
							<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=webvalidervip" method="post">
								<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>"> 
								<input type="hidden" name="idwebclient" value="<?php echo $idwebclient;?>"> 
								<input type="hidden" name="s" value="2">
								<input type="submit" name="action" value="Cloturer"> 
							</form>
						</td>
					</tr>
					<tr>
						<td width="50%" align="center">
							<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=webdeletevip" method="post" onClick="return confirm('Etes-vous sur de vouloir effacer ce job?')">
								<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>"> 
								<input type="hidden" name="idwebclient" value="<?php echo $idwebclient;?>"> 
								<input type="hidden" name="s" value="2">
								<input type="submit" name="action" value="Effacer"> 
							</form>
						</td>
						<td width="50%" align="right">
							<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=weblist" method="post">
								<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>"> 
								<input type="hidden" name="s" value="2">
								<input type="submit" name="action" value="Laisser en Brouillon et retourner au Menu"> 
							</form>
						</td>
					</tr>
				</table>
	<?php 
		}
	#/## SI MISSION VIP EXISTE
		else { ?>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<td width="50%" align="center">
							<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=webdeletevip" method="post" onClick="return confirm('Etes-vous sur de vouloir effacer ce job?')">
								<input type="hidden" name="idwebvipjob" value="0"> 
								<input type="hidden" name="idwebclient" value="<?php echo $idwebclient;?>"> 
								<input type="hidden" name="s" value="2">
								<input type="submit" name="action" value="Effacer"> 
							</form>
						</td>
						<td width="50%" align="right">
							<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=weblist" method="post">
								<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>"> 
								<input type="hidden" name="s" value="2">
								<input type="submit" name="action" value="Laisser en Brouillon et retourner au Menu"> 
							</form>
						</td>
					</tr>
				</table>

	<?php
		}
	?>
</div>