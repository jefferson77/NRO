<?php
################### Code PHP ########################
	$detail = new db();
	$detail->inline("SELECT * FROM `vipjob` WHERE `idvipjob` = $did");
	$infos = mysql_fetch_array($detail->result) ; 
	$etat=$infos['etat'];
################### Fin Code PHP ########################
?>
<!-- Menu Gauche --> 
	<table border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
		<tr><td>Code Job</td></tr>
		<tr><td><?php echo $infos['idvipjob']; ?></td></tr>
	</table>

<!-- INFO GENERALES --> 
<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modiffull';?>" method="post">
	<input type="hidden" name="idvipjob" value="<?php echo $did;?>"> 
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="99%">
		<tr>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip" width="90">
							<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$did.'&sel=agent&etat='.$etat;?>">Assistant</a>
						</th>
						<?php 
						# recherche client et cofficer
						if (!empty($infos['idagent'])) {
							$idagent=$infos['idagent'];
							$detail1 = new db();
							$detail1->inline("SELECT * FROM `agent` WHERE `idagent`=$idagent");

							$infosagent = mysql_fetch_array($detail1->result) ; 
							
						}
						?>
						<td colspan="2">
							<?php echo $infosagent['prenom']; ?> <?php echo $infosagent['nom']; ?>
						</td>
					</tr>
					<tr>
						<th class="space4" colspan="3">
							&nbsp;
						</th>
					</tr>
					<tr>
						<th class="vip" width="90">
							R&eacute;f&eacute;rence
						</td>
						<td colspan="2">
							<input type="text" size="45" name="reference" value="<?php echo $infos['reference']; ?>">
						</td>
					</tr>
					<tr>
						<th class="space4" colspan="3">
							&nbsp;
						</th>
					</tr>
					<tr>
						<th class="vip" width="90">
							Notes Job
						</td>
						<td colspan="2">
							<textarea name="notejob" rows="2" cols="42"><?php echo $infos['notejob']; ?></textarea>
						</td>
					</tr>
					<tr>
						<th class="space4" colspan="3">
							&nbsp;
						</th>
					</tr>
					<tr>
						<th class="vip"  rowspan="2">
							<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$did.'&sel=client&etat='.$etat;?>">Client</a>
						</th>
						<?php 
						# recherche client et cofficer
						if (!empty($infos['idclient'])) {
							$idclient=$infos['idclient'];
							$detail3 = new db();
							$detail3->inline("SELECT * FROM `client` WHERE `idclient`=$idclient");

							$infosclient = mysql_fetch_array($detail3->result) ; 
							
							$idcofficer=$infos['idcofficer'];
							$detail4 = new db();
							$detail4->inline("SELECT * FROM `cofficer` WHERE `idcofficer`=$idcofficer");
							$infosofficer = mysql_fetch_array($detail4->result) ; 
						}
						?>
						<td colspan="2">
							<?php echo $infosclient['codeclient']; ?> <?php echo $infosclient['societe']; ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<?php echo $infosofficer['qualite'].' '.$infosofficer['onom'].' '.$infosofficer['oprenom'].' '.$infosofficer['langue']; ?>
						</td>
					</tr>
					<tr>
						<th class="space4" colspan="3">
							&nbsp;
						</th>
					</tr>
					<tr>
						<th class="vip" rowspan="2">
							<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$did.'&sel=lieu&etat='.$etat;?>">lieux</a>
						</th>
						<td colspan="2">
						<?php 
						# recherche shop
						if (!empty($infos['idshop'])) {
							$idshop=$infos['idshop'];
							$detail5 = new db();
							$detail5->inline("SELECT * FROM `shop` WHERE `idshop`=$idshop");
							$infosshop = mysql_fetch_array($detail5->result) ; 
						}
						?>
							<?php echo $infosshop['codeshop']; ?> <?php echo $infosshop['societe']; ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $infosshop['adresse']; ?>
						</td>
						<td>
							<?php echo $infosshop['cp'].' '.$infosshop['ville']; ?>
						</td>
					</tr>
					<tr>
						<th class="space4" colspan="3">
							&nbsp;
						</th>
					</tr>
					<tr>
						<th class="vip" width="90">
							Date
						</td>
						<td colspan="2">
							<?php echo 'du '.fdate($infos['datein']).' au '.fdate($infos['dateout']); ?>
						</td>
					</tr>
					<tr>
						<th class="vip" width="90">
							Etat
						</td>
						<td colspan="2">
							<?php 
							if ($infos['etat'] == 0) {echo 'DEVIS';}
							if ($infos['etat'] == 1) {echo 'JOB';}
							if ($infos['etat'] == 2) {echo 'OUT';}
							if ($infos['etat'] == 11) {echo 'JOB Ready';}
							echo ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cloture du JOB (';
							echo ' '.$infos['etat'].' ) :<br>';
							echo '<input type="radio" name="etat" value="'.$infos['etat'].'" checked> NON &nbsp; - &nbsp;';
							if (($infos['dateout'] < date("Y-m-d")) and ($infos['etat'] >= '1') and ($infos['etat'] <= '10')) {
								echo '<input type="radio" name="etat" value="11"> OK';
							}
							if (($infos['dateout'] < date("Y-m-d")) and ($infos['etat'] == '11')) {
								echo '<input type="radio" name="etat" value="11" checked> OK';
							}
							if (($infos['dateout'] < date("Y-m-d")) and ($infos['etat'] == '12')) {
								echo '<input type="radio" name="etat" value="'.$infos['etat'].'" checked> OK';
							}
							?>
						</td>
					</tr>
					<tr>
						<th class="space4" colspan="3">
							&nbsp;
						</th>
					</tr>
				</table>		
			</td>
			<td width="200" valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="space4">
							&nbsp;
						</th>
					</tr>
					<tr>
						<th class="vip">
							Commande
						</th>
					</tr>
					<tr>
						<td>
							<?php echo fdatetime2($infos['datecommande']); ?>
						</td>
					</tr>
					<tr>
						<th class="space4">
							&nbsp;
						</th>
					</tr>
					<tr>
						<th class="vip">
							P O
						</th>
					</tr>
					<tr>
						<td>
							<?php echo $infos['bondecommande']; ?>
						</td>
					</tr>

					<tr>
						<th class="space4">
							&nbsp;
						</th>
					</tr>
					<tr>
						<td align="center">
							<input type="submit" name="Modifier" value="Modifier">
						</td>
					</tr>
					<tr>
						<th class="space4">
							&nbsp;
						</th>
					</tr>
					<tr>
						<td bgcolor="#FF9933">
							Planning online ? 
							<input type="radio" name="planningweb" value="yes" <?php if ($infos['planningweb'] == 'yes') { echo 'checked';} ?>> oui
							<input type="radio" name="planningweb" value="no" <?php if ($infos['planningweb'] == 'no') { echo 'checked';} ?>> non 
							<br>
							Contrat Online ? &nbsp;
							<input type="radio" name="webdoc" value="yes" <?php if ($infos['webdoc'] == 'yes') { echo 'checked';} ?>> oui
							<input type="radio" name="webdoc" value="no" <?php if ($infos['webdoc'] == 'no') { echo 'checked';} ?>> non 
						</td>
					</tr>
				</table>		
			</td>
			<td width="300" valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<td>
							Notes Prestations
						</td>
						<td>
							Notes D&eacute;placement
						</td>
					</tr>
					<tr>
						<td>
							<textarea name="noteprest" rows="2" cols="30"><?php echo $infos['noteprest']; ?></textarea>
						</td>
						<td>
							<textarea name="notedeplac" rows="2" cols="30"><?php echo $infos['notedeplac']; ?></textarea>
						</td>
					</tr>
					<tr>
						<th class="space4" colspan="2">
							&nbsp;
						</th>
					</tr>
					<tr>
						<td>
							Notes Location
						</td>
						<td>
							Notes Frais
						</td>
					</tr>
					<tr>
						<td>
							<textarea name="noteloca" rows="2" cols="30"><?php echo $infos['noteloca']; ?></textarea>
						</td>
						<td>
							<textarea name="notefrais" rows="2" cols="30"><?php echo $infos['notefrais']; ?></textarea>
						</td>
					</tr>
					<tr>
						<th class="space4" colspan="2">
							&nbsp;
						</th>
					</tr>
					<tr>
						<td colspan="2">
							Briefing promoboy
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<textarea name="briefing" rows="3" cols="60"><?php echo $infos['briefing']; ?></textarea>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>		
<div align="center">
	<input type="submit" name="Modifier" value="Modifier">
</div>
</form>
	<table class="standard" border="0" cellspacing="0" cellpadding="0" align="center" width="98%">
		<tr>
			<td valign="middle">
				<table border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
					<tr>
						<td valign="middle" width="100%" height="330">
<?php
#### pour le casting
	$s = 1;
	if (($_SESSION['casting'] != 'z') and (!empty($_SESSION['casting']))) {$s = 2;}
#### pour le casting
?>							
							<iframe frameborder="0" marginwidth="0" marginheight="0" name="detail-main" src="<?php echo 'vip-onglet.php?act=show&etat='.$etat.'&idvipjob='.$did.'&s='.$_GET['s'].'&action='.$_POST['act'].'';?>" width="100%" height="98%" align="top">Marche pas les IFRAMES !</iframe> 
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>