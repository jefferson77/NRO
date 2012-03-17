<?php
################### Code PHP ######################
## recup l'id du job si on a qu'une mission id
if (!empty($_REQUEST['idvip']) and empty($_REQUEST['idvipjob'])) {
	$_REQUEST['idvipjob'] = $DB->getOne("SELECT idvipjob FROM vipmission WHERE idvip = ".$_REQUEST['idvip']);
}

if (!empty($_REQUEST['idvipjob'])) {
	$etat = $DB->getOne("SELECT etat FROM vipjob WHERE idvipjob = ".$_REQUEST['idvipjob']);

	$vipbase = ($etat == '0')?'vipdevis':'vipmission';

	### Update datesIN et datesOUT ###
	$DB->inline("UPDATE `vipjob` SET `datein` = (SELECT MIN(vipdate) FROM `".$vipbase."` WHERE `idvipjob` = ".$_REQUEST['idvipjob']."), `dateout` = (SELECT MAX(vipdate) FROM `".$vipbase."` WHERE idvipjob = ".$_REQUEST['idvipjob'].") WHERE `idvipjob` = ".$_REQUEST['idvipjob'].";");

	### Get infos Job ###

	$infos = $DB->getRow("
		SELECT
		j.*,
		a.prenom as aprenom, a.nom as anom,
		o.qualite, o.onom, o.oprenom, o.langue,
		c.codeclient, c.societe,
		s.codeshop, s.societe as ssociete, s.adresse as sadresse, s.cp, s.ville

		FROM vipjob j
		LEFT JOIN agent a ON j.idagent = a.idagent
		LEFT JOIN client c ON j.idclient = c.idclient
		LEFT JOIN cofficer o ON j.idcofficer = o.idcofficer
		LEFT JOIN shop s ON j.idshop = s.idshop

		WHERE `idvipjob` = ".$_REQUEST['idvipjob']);
}
################### Fin Code PHP ###################
?>
<div id="centerzonelarge">
<form action="?act=modiffull" method="post">
	<input type="hidden" name="idvipjob" value="<?php echo $_REQUEST['idvipjob'];?>">
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
		<tr>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
					<tr>
						<th class="vip" width="90">
							Responsable
						</th>
						<td colspan="2">
							<?php
								$DB->inline("SELECT idagent, nom, prenom FROM `agent` WHERE isout = 'N'");
								while ($row = mysql_fetch_array($DB->result)) {
									$agent[$row['idagent']]= $row['prenom']." ".$row['nom'];
								}
								echo createSelectList('idagent',$agent,$infos['idagent'],'--');
							?>

						</td>
					</tr>
					<tr>
						<th class="space4" colspan="3">&nbsp;</th>
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
						<th class="space4" colspan="3">&nbsp;</th>
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
						<th class="space4" colspan="3">&nbsp;</th>
					</tr>
					<tr>
						<th class="vip"  rowspan="2">
							<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$_REQUEST['idvipjob'].'&sel=client&etat='.$infos['etat'];?>">Client</a>
						</th>
						<td>
							<?php echo $infos['codeclient']; ?> <?php echo $infos['societe']; ?>
						</td>
						<td>
							Facturation par Forfait
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $infos['qualite'].' '.$infos['onom'].' '.$infos['oprenom'].' '.$infos['langue']; ?>
						</td>
						<td>
							<input name="forfait" type="radio" value="Y" <?php if ($infos['forfait'] == 'Y') echo 'checked'; ?>> Oui
							<input name="forfait" type="radio" value="N" <?php if ($infos['forfait'] == 'N') echo 'checked'; ?>> Non
						</td>
					</tr>
					<tr>
						<th class="space4" colspan="3">&nbsp;

						</th>
					</tr>
					<tr>
						<th class="vip" rowspan="2">
							<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$_REQUEST['idvipjob'].'&sel=lieu&etat='.$infos['etat'].'&from=JOB';?>">lieux</a>
						</th>
						<td colspan="2">
							<?php echo $infos['codeshop']; ?> <?php echo $infos['ssociete']; ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $infos['sadresse']; ?>
						</td>
						<td>
							<?php echo $infos['cp'].' '.$infos['ville'];?>
						</td>
					</tr>
					<tr>
						<th class="space4" colspan="3">&nbsp;

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
						<th class="space4" colspan="3">&nbsp;

						</th>
					</tr>
				</table>
			</td>
			<td width="200" valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr><th class="vip">Code Job : <?php echo $infos['idvipjob']; ?></th></tr>
					<tr>
						<th class="space4">&nbsp;</th>
					</tr>
					<tr>
						<th class="space4">&nbsp;</th>
					</tr>
					<tr>
						<th class="vip">Commande</th>
					</tr>
					<tr>
						<td><?php echo fdatetime2($infos['datecommande']); ?></td>
					</tr>
					<tr>
						<th class="space4">&nbsp;</th>
					</tr>
					<tr>
						<th class="vip">P O</th>
					</tr>
					<tr>
						<td><input type="text" size="35" name="bondecommande" value="<?php echo $infos['bondecommande']; ?>"></td>
					</tr>

					<tr>
						<th class="space4">&nbsp;</th>
					</tr>
					<tr>
						<td align="center"></td>
					</tr>
					<tr>
						<th class="space4">&nbsp;</th>
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
					<tr>
						<td style="padding: 5px; text-align: center;"><a href="<?php echo NIVO.'data/people/myweek.php?date='.$infos['datein'].'&job='.$infos['idvipjob']; ?>">MyWeek</a></td>
					</tr>
					<tr>
						<th class="vip">Commercial</th>
					</tr>
					<tr>
						<td>
							<?php
								$DB->inline("SELECT idagent, nom, prenom FROM `agent` WHERE isout = 'N'");
								while ($row = mysql_fetch_array($DB->result))
								{
									$agent[$row['idagent']]= $row['prenom']." ".$row['nom'];
								 }
								 echo createSelectList('idcommercial',$agent,$infos['idcommercial'],'--');
							?>

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
						<th class="space4" colspan="2">&nbsp;

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
						<th class="space4" colspan="2">&nbsp;

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
	<table class="standard" border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
		<tr>
			<td valign="middle">
				<table border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
					<tr>
						<td valign="middle" width="100%" height="330">
<?php
#### pour le casting
	if(isset($_GET['s'])) $s=$_GET['s'];
	else $s = 1;
	if ((!empty($_SESSION['casting'])) and ($_SESSION['casting'] != 'z')) { $s = 2; }
#### pour les notes de frais
	$path='onglet.php?&act=show&etat='.$infos['etat'].'&idvipjob='.$_REQUEST['idvipjob'].'&s='.$s;
	if (isset($_REQUEST['idvip'])) $path.='&idvip='.$_REQUEST['idvip'];
	if (isset($_REQUEST['bdact']) && $s == '11') $path.='&bdact='.$_GET['bdact'];
?>
	<iframe frameborder="0" marginwidth="0" marginheight="0" name="detail-main" src="<?php echo $path; ?>" width="100%" height="98%" align="top">Marche pas les IFRAMES !</iframe>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

</div>
</div>