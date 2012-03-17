<?php
################### Code PHP ########################
$detailjob = new db('webvipjob', 'idwebvipjob ', 'webneuro');
$detailjob->inline("SELECT * FROM `webvipjob` WHERE `idwebvipjob` = $idwebvipjob ");
$infosjob = mysql_fetch_array($detailjob->result) ; 
################### Fin Code PHP ########################
?>
<?php #  Corps de la Page ?>
<div id="centerzonelarge">
<?php $classe = "planning" ?>
				<br>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip" width="15%">
							Client
						</th>
						<?php 
						# recherche client et cofficer
						if (!empty($infosjob['idclient'])) {
							$idclient=$infosjob['idclient'];
							$detail3 = new db();
							$detail3->inline("SELECT * FROM `client` WHERE `idclient`=$idclient");

							$infosclient = mysql_fetch_array($detail3->result) ; 
							
							$idcofficer=$infosjob['idcofficer'];
							$detail4 = new db();
							$detail4->inline("SELECT * FROM `cofficer` WHERE `idcofficer`=$idcofficer");
							$infosofficer = mysql_fetch_array($detail4->result) ; 
						}
						?>
						<td width="40%">
							<?php echo $infosclient['codeclient']; ?> <?php echo $infosclient['societe']; ?>
						</td>
						<td>
							<?php echo $infosofficer['qualite'].' '.$infosofficer['onom'].' '.$infosofficer['oprenom'].' '.$infosofficer['langue']; ?>
						</td>
					</tr>
				</table>
				<br>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip" width="15%">
							Commande
						</th>
						<td width="40%">
							<?php echo fdatetime2($infosjob['datecommande']); ?>
						</td>
						<th class="vip" width="15%">
							P O
						</th>
						<td>
							<?php echo $infosjob['bondecommande']; ?>
						</td>
					</tr>
				</table>
				<br>

				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<td valign="top">
							<table class="<?php echo $classe; ?>" border="0" cellspacing="1" cellpadding="0" align="center" width="250">
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
							<table class="<?php echo $classe; ?>" border="0" cellspacing="1" cellpadding="0" align="center" width="300">
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
							<table class="<?php echo $classe; ?>" border="0" cellspacing="1" cellpadding="0" align="center" width="250">
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
							<td colspan="14" align="left">dates actuelles : du <?php echo fdate($infosjob['datein']); ?> au <?php echo fdate($infosjob['dateout']); ?><br>&nbsp</td>
						</tr>
						<?php
						$detailbuild = new db('webvipbuild', 'idvipbuild', 'webneuro');
						$detailbuild->inline("SELECT * FROM `webvipbuild` WHERE `idwebvipjob` = $idwebvipjob ORDER BY 'vipactivite'");
						while ($infos = mysql_fetch_array($detailbuild->result)) { 
						$i++;
						?>
							<tr>
								<td coslpan="3">
									<b><font color="white">
										Activit&eacute; <?php echo $i; ?> : 
										<?php 
										if ($infos['vipactivite'] == '1') {echo 'Accueil';}
										if ($infos['vipactivite'] == '2') {echo 'Chauffeur';}
										if ($infos['vipactivite'] == '3') {echo 'Chef H&ocirc;tesse';}
										if ($infos['vipactivite'] == '4') {echo 'D&eacute;bit';}
										if ($infos['vipactivite'] == '5') {echo 'D&eacute;monstration';}
										if ($infos['vipactivite'] == '6') {echo 'Encadrement';}
										if ($infos['vipactivite'] == '7') {echo 'Encodage';}
										if ($infos['vipactivite'] == '8') {echo 'Flyering';}
										if ($infos['vipactivite'] == '9') {echo 'Information';}
										if ($infos['vipactivite'] == '10') {echo 'Parking';}
										if ($infos['vipactivite'] == '11') {echo 'R&eacute;ception';}
										if ($infos['vipactivite'] == '12') {echo 'Sampling';}
										if ($infos['vipactivite'] == '13') {echo 'Service';}
										if ($infos['vipactivite'] == '14') {echo 'Shuttle';}
										if ($infos['vipactivite'] == '15') {echo 'Spraying';}
										if ($infos['vipactivite'] == '16') {echo 'Vestiaire';}
										?>
									</font></b>
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
											<th colspan="2" class="vip">
												<?php echo fdate($vipdate1);?>
											</th>	
										</tr>	
											<tr class="vip">
												<td class="vip"><b><?php echo $infos['vipnombre']; ?></b></td>
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
									</table>
								</td>
							<?php 
								$dd = explode('-', $vipdate1);
								$vipdate1 = date ("Y-m-d", mktime (0,0,0,$dd[1],$dd[2]+1,$dd[0]));
							} 
							?>	
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						<?php 
						}
						?>
					</table>
				</fieldset>		
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<td align="center" colspan="2">
							<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=webvalider" method="post">
								<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>"> 
								<input type="hidden" name="s" value="2">
								<input type="submit" name="action" value="Cloturer"> 
							</form>
						</td>
					</tr>
					<tr>
						<td width="50%" align="center">
							<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=webdelete" method="post" onClick="return confirm('Etes-vous sur de vouloir effacer ce job du Web?')">
								<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>"> 
								<input type="hidden" name="s" value="2">
								<input type="submit" name="action" value="Effacer"> 
							</form>
						</td>
						<td width="50%" align="right">
							<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=webviplist" method="post">
								<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>"> 
								<input type="hidden" name="s" value="2">
								<input type="submit" name="action" value="Laisser en Brouillon et retourner au Menu"> 
							</form>
						</td>
					</tr>
				</table>

			<br>
			<hr align="center" size="1" width="100%">
			<br>
			<fieldset class="width">
				<legend class="width">Fichiers en annexe</legend>
					<table class="standard" border="0" cellspacing="1" cellpadding="3" align="left" width="500">
						<?php
						$path = Conf::read('Env.root').'media/annexe/vipweb/';
						$ledir = $path;
						$d = opendir ($ledir);
						$nomvip = 'vip-'.$idwebvipjob.'-';
						while ($name = readdir($d)) {
							if (
								($name != '.') and 
								($name != '..') and 
								($name != 'index.php') and 
								($name != 'index2.php') and 
								($name != 'temp') and
								(strchr($name, $nomvip))
							) {
						?>
								<tr>
									<td class="contenu"><a href="<?php echo $path.$name; ?>" target="_blank"><?php echo $name ?></a></td>
								</tr>
						<?php 
							}
						}
						closedir ($d);
					 ?>
				</table>
			</fieldset>

</div>