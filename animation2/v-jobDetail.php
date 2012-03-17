<?php
if (!empty($_REQUEST['idanimjob']))
{
	################### début vérif datein et dateout  ########################
	$DB->inline("UPDATE animjob SET datein = (SELECT MIN(datem) FROM animation WHERE `idanimjob` = ".$_REQUEST['idanimjob']."), dateout = (SELECT MAX(datem) FROM animation WHERE `idanimjob` = ".$_REQUEST['idanimjob'].") WHERE idanimjob = ".$_REQUEST['idanimjob']);

	################### Get infos  ########################
	$infos = $DB->getRow("SELECT
		aj.boncommande, aj.briefing, aj.datecommande, aj.datein, aj.dateout, aj.etat, aj.idagent, aj.idanimjob, aj.idclient, aj.idcofficer, aj.notedeplac, aj.notefrais, 
		aj.notejob, aj.noteloca, aj.noteprest, aj.offreweb, aj.planningweb, aj.reference, aj.statutarchive, aj.webdoc, aj.shopselection,
		a.nom AS anom, a.prenom AS aprenom,
		c.codeclient, c.societe,
		co.langue, co.onom, co.oprenom, co.qualite
		FROM animjob aj
			LEFT JOIN agent a ON aj.idagent = a.idagent
			LEFT JOIN client c ON aj.idclient = c.idclient
			LEFT JOIN cofficer co ON aj.idcofficer = co.idcofficer
		WHERE aj.idanimjob = ".$_REQUEST['idanimjob']);
		
	$infos['shopselection'] = cleanShopSelection($infos['shopselection']);
}

$disable = ($infos['statutarchive'] == 'closed')?'disabled':'';
?>
<div id="centerzonelarge">
<?php if ($disable != 'disabled') { ?>
	<form action="?act=modifjobfull" method="post" name="jobDetail">
		<input type="hidden" name="idanimjob" value="<?php echo $infos['idanimjob'];?>"> 
<?php } ?>
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="99%">
		<tr>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="anim" width="90">Assistant</th>
						<td colspan="3">
							<input type="hidden" name="idagent" value="<?php echo $infos['idagent'] ?>" id="idagent">
							<input type="text" name="nomagent" value="<?php echo $infos['aprenom'].' '.$infos['anom'] ?>" id="nomagent" size="18" title="Entrez le début d'un nom" style="text-align:center;">
						</td>
						<th class="anim" width="90">Id Job</td>
						<td>&nbsp;<?php echo $infos['idanimjob']; ?></td>
					</tr>
					<tr>
						<th class="space4" colspan="6">&nbsp;</th>
					</tr>
					<tr>
						<th class="anim" width="90">Nom action</td>
						<td colspan="3"><input type="text" size="35" name="reference" value="<?php echo stripslashes($infos['reference']); ?>" <?php echo $disable; ?>></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<th class="space4" colspan="6">&nbsp;</th>
					</tr>
					<tr>
						<th class="anim" width="90" rowspan="2">Description action</td>
						<td colspan="3" rowspan="2"><textarea name="notejob" rows="2" cols="32" <?php echo $disable; ?>><?php echo stripslashes($infos['notejob']); ?></textarea></td>
						<th class="anim">Bon Comm.</th>
						<td><input type="text" size="20" name="boncommande" value="<?php echo $infos['boncommande']; ?>" <?php echo $disable; ?>></td>
					</tr>
					<tr>
						<th class="anim">Date Comm.</th>
						<td><input type="text" size="20" name="datecommande" value="<?php echo fdate($infos['datecommande']); ?>" <?php echo $disable; ?>></td>
					</tr>
					<tr>
						<th class="space4">&nbsp;</th>
						<th class="space4" >&nbsp;</th>
						<th class="space4" colspan="4">&nbsp;</th>
					</tr>
					<tr>
						<th class="anim">
							<?php if ($disable != 'disabled') { ?>
								<a href="<?php echo $_SERVER['PHP_SELF'].'?act=selectclient&idanimjob='.$infos['idanimjob'].'&sel=client&etat='.$infos['etat'];?>">Client</a>
							<?php } else { ?>
								Client
							<?php } ?>
						</th>
						<td colspan="5"><?php echo $infos['codeclient']; ?> <?php echo $infos['societe']; ?> (<?php echo $infos['qualite'].' '.$infos['onom'].' '.$infos['oprenom'].' '.$infos['langue']; ?>)</td>
					</tr>
					<tr>
						<th class="space4" colspan="6">&nbsp;</th>
					</tr>
					<tr>
						<th class="anim" width="90">Date</td>
						<td colspan="3"><?php echo 'du '.fdate($infos['datein']).' au '.fdate($infos['dateout']); ?></td>
						<td bgcolor="#FF9933" colspan="2" rowspan="2">
							&nbsp; Offre online ? 
							<input type="radio" <?php echo $disable; ?> name="offreweb" value="yes" <?php if ($infos['offreweb'] == 'yes') { echo 'checked';} ?>> oui
							<input type="radio" <?php echo $disable; ?> name="offreweb" value="no" <?php if ($infos['offreweb'] == 'no') { echo 'checked';} ?>> non 
							<br>
							&nbsp; Planning online ? 
							<input type="radio" <?php echo $disable; ?> name="planningweb" value="yes" <?php if ($infos['planningweb'] == 'yes') { echo 'checked';} ?>> oui
							<input type="radio" <?php echo $disable; ?> name="planningweb" value="no" <?php if ($infos['planningweb'] == 'no') { echo 'checked';} ?>> non 
							<br>
							&nbsp; Contrat Online ? &nbsp;
							<input type="radio" <?php echo $disable; ?> name="webdoc" value="yes" <?php if ($infos['webdoc'] == 'yes') { echo 'checked';} ?>> oui
							<input type="radio" <?php echo $disable; ?> name="webdoc" value="no" <?php if ($infos['webdoc'] == 'no') { echo 'checked';} ?>> non 
						</td>
					</tr>
					<tr>
						<th class="anim" width="90">Etat</td>
						<td colspan="3">
							<?php 
							if ($infos['statutarchive'] != 'closed') { /* n'a pas été archivé */
								echo '<input type="radio" name="statutarchive" value="open" checked> En cours &nbsp; - &nbsp;';
								### Vérification : Job Partiellement ou complètement facturé
								if ($infos['etat'] == 8) {
									if ($infos['statutarchive'] == 'open') {
										$facjob = $DB->getOne("SELECT MIN(facturation) FROM `animation` WHERE `idanimjob` = ".$infos['idanimjob']);
									}
									if ($facjob >= 8) {
										echo '<input type="radio" name="statutarchive" value="closed" '; if ($infos['statutarchive'] == 'closed') { echo "checked";} echo '> Cloturer (Job factur&eacute;)';
									} else {
										echo ' Job partiellement factur&eacute;';
									}
								### Vérification : Job Partiellement ou complétement facturé
								} else {
									echo '<input type="radio" name="statutarchive" value="canceled" '; if ($infos['statutarchive'] == 'canceled') { echo "checked";} echo '> Archiver';
								}
							} else { /* $infos['statutarchive'] == 'closed') */
								echo 'Archive';
							}
?>
						</td>
					</tr>
					<tr><th class="space4" colspan="6">&nbsp;</th></tr>
				</table>		
			</td>
			<td width="300" valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr><td valign="top" colspan="2" style="padding: 0px;">Briefing promoboy</td></tr>
					<tr><td colspan="2"><textarea name="briefing" style="width: 300px; height: 100px;" <?php echo $disable; ?>><?php echo stripslashes($infos['briefing']); ?></textarea></td></tr>
					<tr><td style="padding: 0px;">Uniforme</td><td style="padding: 0px;">Notes Frais</td></tr>
					<tr>
						<td><textarea name="noteloca" style="width: 148px; height: 20px;" <?php echo $disable; ?>><?php echo stripslashes($infos['noteloca']); ?></textarea></td>
						<td><textarea name="notefrais" style="width: 148px; height: 20px;" <?php echo $disable; ?>><?php echo stripslashes($infos['notefrais']); ?></textarea></td>
					</tr>
					<tr>
						<td style="padding: 0px;">Notes Prestations</td>
						<td style="padding: 0px;">Notes D&eacute;placement</td>
					</tr>
					<tr>
						<td><textarea name="noteprest" style="width: 148px; height: 20px;" <?php echo $disable; ?>><?php echo stripslashes($infos['noteprest']); ?></textarea></td>
						<td><textarea name="notedeplac" style="width: 148px; height: 20px;" <?php echo $disable; ?>><?php echo stripslashes($infos['notedeplac']); ?></textarea></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>		
	<div align="center">
	<?php if ($disable != 'disabled') { ?>
		<input type="submit" name="Modifier" value="Modifier">
	<?php } ?>
	</div>
</form>
<?php
## Nombre de missions actives et archivées pour le job
$nbrmis = $DB->getRow("SELECT COUNT(*) AS countm, SUM(IF(facturation > 4,1,0)) AS archived, SUM(IF(facturation <= 4, 1, 0)) AS active FROM  `animation` WHERE `idanimjob` = ".$_REQUEST['idanimjob']);
if (strstr($infos['shopselection'], "|")) {
	$noops = array_filter(explode("|", $infos['shopselection']));
	$valid = $DB->getOne("SELECT COUNT(idshop) FROM animation WHERE idanimjob = '".$_REQUEST['idanimjob']."' AND idshop IN (".implode(", ", $noops).") GROUP BY idshop");
} else {
	$valid = 0;
}
$nbr = $DB->getOne("SELECT COUNT(id) FROM bondecommande WHERE idjob = ".$_REQUEST['idanimjob']." AND etat = 'in'");
?>
<table class="standard" border="0" cellspacing="0" cellpadding="0" width="100%" height="25">
	<tr height="25">
		<td class="navbarleft"></td>
		<td class="navbar">
			<?php
			if ($infos['statutarchive'] != 'closed') {
				echo '<a href="adminonglet.php?act=grpshow&idanimjob='.$_REQUEST['idanimjob'].'" target="onglets">Cr&eacute;a Missions</a>';
			} else { 
				echo 'Cr&eacute;a Missions';
			}
			?>
		</td>
		<td class="navbarmid"></td>
		<td class="navbar"><?php echo '<a href="adminanim.php?act=listing&idanimjob='.$_REQUEST['idanimjob'].'&down=down" target="onglets">Missions ('.$nbrmis['active'].' et '.$nbrmis['archived'].' : '.$nbrmis['countm'].')</a>'; ?></td>
		<td class="navbarmid"></td>
		<td class="navbar"><?php echo '<a href="adminonglet.php?act=animshopshow&idanimjob='.$_REQUEST['idanimjob'].'" target="onglets">POS ('.$valid.' sur '.count($noops).')</a>'; ?></td>
		<td class="navbarmid"></td>
		<td class="navbar"><?php echo '<a href="adminonglet.php?act=annexeshow&idanimjob='.$_REQUEST['idanimjob'].'" target="onglets">Annexes</a>'; ?></td>
		<td class="navbarmid"></td>
		<td class="navbar"><?php echo '<a href="adminonglet.php?act=updateselect&idanimjob='.$_REQUEST['idanimjob'].'" target="onglets">Update Missions</a>'; ?></td>
		<td class="navbarmid"></td>
		<td class="navbar"><?php echo '<a href="'.NIVO.'data/boncommande/adminboncommande.php?act=list&idanimjob='.$_REQUEST['idanimjob'].'&secteur=AN" target="onglets">Bon de commande <span id="nbrbdc">('.$nbr.')</span></font></a>'; ?></td>
		<td class="navbarright"></td>
	</tr>
</table>
<iframe frameborder="0" marginwidth="0" marginheight="0" name="onglets" src="<?php echo 'adminanim.php?act=listing&idanimjob='.$infos['idanimjob'] ;?>&down=down" width="100%" height="400" align="top">Marche pas les IFRAMES !</iframe> 
</div>
<script type="text/javascript" charset="utf-8">
	function formatResult(row) { return row[0]; }
	function formatItem(row) { return row[1]; }

	$(document).ready(function() {
		$("input#nomagent").autocomplete("<?php echo Conf::read('Env.urlroot') ?>query/agent.php", {
			inputClass: 'autocomp',
			width: 250,
			minChars: 2,
			formatItem: formatItem,
			formatResult: formatResult,
			delay: 200,
			extraParams: {
				isout: 'N'
			}
		});

		$("input#nomagent").result(function(data, row) { 
			if (data) document.jobDetail.idagent.value = row[0];
			if (data) document.jobDetail.nomagent.value = row[1];
		});
	});
</script>