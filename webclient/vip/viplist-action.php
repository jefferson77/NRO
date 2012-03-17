<div class="corps2">
<Fieldset class="width">
	<legend class="width"><?php if ($action == 'non') { echo $tool_16; } else { echo $tool_17; } ?></legend>
	<br>	
	<table class="planning" border="0" cellspacing="1" cellpadding="4" align="center" width="98%">
		<tr class="planning">
			<td class="tabtitre">N</th>
			<td class="tabtitre"><?php echo $vipaction_01; ?></th>
			<td class="tabtitre"><?php echo $vipaction_02; ?></th>
			<td class="tabtitre"><?php echo $vipaction_03; ?></th>
			<td class="tabtitre"><?php echo $animaction_31; ?></th>
			<td class="tabtitre"><?php echo $vipaction_04; ?></th>
			<td class="tabtitre"><?php echo $vipaction_05; ?></th>
			<td class="tabtitre" width="60"><?php echo $vipaction_06; ?></th>
			<td class="tabtitre" width="60"><?php echo $vipaction_07; ?></th>
			<td class="tabtitre" width="60"><?php echo $vipaction_08; ?></th>
			<?php if ($action == 'oui') { #pour les client neuro, pas pour le new web ?>
				<td class="tabtitre" width="70"><?php echo $vipaction_09; ?></th>
			<?php } ?>
		</tr>
		<?php
		### offres Job - QUE POUR ACTIONS
		if (($action == 'non')  and ($_SESSION['new'] == 0)) { ### PAS POUR NEW CLIENT
			$detailjob = new db('webvipjob', 'idwebvipjob', 'webneuro');
			$detailjob->inline("SELECT * FROM `webvipjob` WHERE `idclient` = $idclient AND `etat` >= 5 AND `isnew` = '$new' ORDER BY `datein` DESC");
			while ($infos = mysql_fetch_array($detailjob->result)) { 
				#> Changement de couleur des lignes #####>>####
				$i++;
				if (fmod($i, 2) == 1) {
					echo '<tr bgcolor="#78ABD7">';
				} else {
					echo '<tr bgcolor="#97B4CD">';
				}
				#< Changement de couleur des lignes #####<<####
		?>
					<td class="line"><?php echo $i; ?></td>
					<td class="line"><div align="left"><?php echo $infos['reference']; ?> &nbsp;</div></td>
					<td class="line"><?php echo fdate($infos['datein']); ?> &nbsp;</td>
					<td class="line"><?php echo fdate($infos['dateout']); ?> &nbsp;</td>
					<td class="line"><?php if (($infos['datecommande'] != '0000-00-00 00:00:00') and (!empty($infos['datecommande']))) {echo fdatetime2($infos['datecommande']); } ?></td>
					<td class="line"><a class="white2" href="adminclientvip.php?act=vipviewweb&etat=0&idwebvipjob=<?php echo $infos['idwebvipjob']; ?>"><?php echo $vipaction_10; ?></a>
					</td>
					<td class="line">
						<?php echo $vipaction_11; ?>
					</td>
					<td class="line"> &nbsp;</td>
					<td class="line"> &nbsp;</td>
					<td class="line"> &nbsp;</td>
					<?php if ($action == 'oui') { #pour les client neuro, pas pour le new web ?>
							<td class="line"> &nbsp;</td>
					<?php } ?>
				</tr>
		<?php 
			} 
		} 
		#/ ## offres Job - QUE POUR ACTIONS
		?>
<?php
if ($new != 1) { #pour les client neuro, pas pour le new web
	$zetat2 = 'z';
	$detailjob = new db('vipjob', 'idvipjob ');
	if ($action == 'non') { ### recherche pour ACTION
		$detailjob->inline("SELECT * FROM `vipjob` WHERE `idclient` = $idclient AND `etat` != 2 AND `etat` <= 14 AND `datein` > '$onyearago' ORDER BY `etat`, `datein` DESC, `datedevis`");
	}
	if ($archive == 'non') { ### recherche pour ACTION
		$detailjob->inline("SELECT * FROM `vipjob` WHERE `idclient` = $idclient AND `etat` != 2 AND `etat` >= 15 AND `datein` > '$onyearago' ORDER BY `etat`, `datein` DESC, `datedevis`");
	}
	#$detailjob->inline("SELECT * FROM `webvipjob` WHERE `idclient` = $idclient ORDER BY `datein` DESC");
	while ($infos = mysql_fetch_array($detailjob->result)) { 
				switch ($infos['etat']) {
					case "0": 
						if ($infos['datedevis'] == '') {
							$etat2 = 0;
						} else {
							$etat2 = 1;
						}
					break;
					case "1": 
					case "11": 
					case "12": 
					case "13": 
					case "14": 
						$etat2 = 2;
					break;
					default :
						$etat2 = 5;
					break;
				} 
		if (($zetat2 != 'z') and ($zetat2 != $etat2)) {
?>
		<tr>
			<td colspan="10" height="8"></td>
		</tr>
<?php 	}
		#> Changement de couleur des lignes #####>>####
		$i++;
		if (fmod($i, 2) == 1) {
			echo '<tr bgcolor="#78ABD7">';
		} else {
			echo '<tr bgcolor="#97B4CD">';
		}
		#< Changement de couleur des lignes #####<<####
?>

			<td class="line"><?php echo $i; ?></td>
			<td class="line"><div align="left"><?php echo $infos['reference']; ?> &nbsp;</div></td>
			<td class="line"><?php echo fdate($infos['datein']); ?> &nbsp;</td>
			<td class="line"><?php echo fdate($infos['dateout']); ?> &nbsp;</td>
			<td class="line"><?php if (($infos['datecommande'] != '0000-00-00 00:00:00') and (!empty($infos['datecommande']))) {echo fdatetime2($infos['datecommande']); } ?></td>
			<td class="line"><a class="white2" href="adminclientvip.php?act=vipview&etat=0&idvipjob=<?php echo $infos['idvipjob']; ?>"><?php echo $vipaction_10; ?></a>
			<td class="line">
				<?php
				switch ($infos['etat']) {
					case "0": 
						if ($infos['datedevis'] == '') {
							echo $vipaction_11;
						} else {
							echo $vipaction_12;
						}
					break;
					case "1": 
						echo 'JOB';
					break;
					case "2": 
						echo 'OUT';
					break;
					case "11": 
					case "12": 
						echo 'JOB';
					break;
					case "13": 
					case "14": 
						echo 'JOB';
					break;
					default :
						echo $vipaction_13;
					break;
				} 
				$zetat2 = $etat2;
				?>
			</td>
			<td align="center">
				<?php if (!empty($infos['datedevis'])) { 
					$jobnum = str_repeat('0', 5 - strlen($infos['idvipjob'])).$infos['idvipjob']; 
					$explo = explode("-", $infos['datedevis']);
					$explodate = $explo[0].$explo[1].$explo[2];
					$nompdf = 'offre-'.$jobnum.'-'.$explodate.'.pdf';
				?>
					<a class="white2" href="<?php echo $document; ?>offre/vip/<?php echo $nompdf; ?>" method="post" target="popupC" onclick="window.open('','popupC','scrollbars=yes,status=yes,resizable=yes,width=500,height=600');"><img src="<?php echo $illus; ?>minipdf.gif" width="16" height="16" border="0"></a>
				<?php } ?>
					&nbsp;
			</td>
			<td align="center">
				<?php
				 if ($infos['planningweb'] == 'yes') { # planning online == oui
					$idvipjob = $infos['idvipjob'];
					$detailmission = new db('vipmission', 'idvip');
					$detailmission->inline("SELECT idvip FROM `vipmission` WHERE `idvipjob` = $idvipjob");
						$Foundmission = mysql_num_rows($detailmission->result); 
						if ($Foundmission >= 1) { ?>
							<a class="white2" href="javascript:;" onClick="OpenBrWindow('<?php echo $print; ?>vip/presence/presence.php?web=web&print=go&idvipjob=<?php echo $infos['idvipjob'];?>','Main','','300','300','true')"><img src="<?php echo $illus; ?>minipdf.gif" width="16" height="16" border="0"></a>
						<?php } ?>	
				<?php } ?>
				&nbsp;
			</td>
			<td align="center">
				<?php 
					$casting=$infos['casting'];
					$explo = explode("-", $casting);
					if ($explo[0] != '') { ?>
						<a class="white2" href="<?php echo $print; ?>people/casting/casting1.php?act=search&casting=<?php echo $infos['casting']; ?>&idvipjob=<?php echo $infos['idvipjob']; ?>" method="post" target="popupC" onclick="window.open('','popupC','scrollbars=yes,status=yes,resizable=yes,width=500,height=400');"><img src="<?php echo $illus; ?>minipdf.gif" width="16" height="16" border="0"></a>
				<?php } ?> &nbsp;
			</td>
			<?php if ($action == 'oui') { #pour les client neuro, pas pour le new web ?>
				<td align="center">
					<?php if ((!empty($infos['facnum'])) and ($infos['facnum'] != 'NULL') and ($infos['facnum'] != NULL)) { ?>
						<a class="white2" href="javascript:;" onClick="OpenBrWindow('facture.php?facnum=<?php echo $infos['facnum'];?>','Main','','300','300','true')"><img src="<?php echo $illus; ?>minipdf.gif" width="16" height="16" border="0"></a> <?php echo $infos['facnum'];?>
					<?php } ?>
					&nbsp;
				</td>
			<?php } ?>
		</tr>
<?php } # fin du while
} # fin du if new
?>
		<tr>
			<td colspan="10" height="8"></td>
		</tr>
	<?php
	### Nouveaux Job - QUE POUR ACTIONS
	if ($action == 'non') {
		$detailjob = new db('webvipjob', 'idwebvipjob ', 'webneuro');

		if ($_SESSION['new'] == 1) {
		### client webnew
			$detailjob->inline("SELECT * FROM `webvipjob` WHERE `idwebclient` = $idwebclient AND `etat` <= 4 AND `isnew` = '$new' ORDER BY `datein` DESC");
		}
		### client webnew
		else {
			$detailjob->inline("SELECT * FROM `webvipjob` WHERE `idclient` = $idclient AND `etat` <= 4 AND `isnew` = '$new' ORDER BY `datein` DESC");
		}
		while ($infos = mysql_fetch_array($detailjob->result)) { 
				#> Changement de couleur des lignes #####>>####
				$i++;
				if (fmod($i, 2) == 1) {
					echo '<tr bgcolor="#78ABD7">';
				} else {
					echo '<tr bgcolor="#97B4CD">';
				}
				#< Changement de couleur des lignes #####<<####
		?>
					<th class="line"><?php echo $i; ?></td>
					<td class="line"><div align="left"><?php echo $infos['reference']; ?> &nbsp;</div></td>
					<td class="line"><?php echo fdate($infos['datein']); ?> &nbsp;</td>
					<td class="line"><?php echo fdate($infos['dateout']); ?> &nbsp;</td>
					<td class="line"><?php if (($infos['datecommande'] != '0000-00-00 00:00:00') and (!empty($infos['datecommande']))) {echo fdatetime2($infos['datecommande']); } ?></td>
					<td class="line">
						<?php if ($infos['etat'] <= 4) { ?>
							<a class="white2" href="adminclientvip.php?act=vipmodif0a&etat=0&idwebvipjob=<?php echo $infos['idwebvipjob']; ?>"><?php echo $vipaction_14; ?></a>
						<?php } else { ?>
							<?php echo $vipaction_15; ?>
						<?php } ?>
					</td>
					<td class="line">
						<?php if ($infos['etat'] <= 4) { ?>
							<?php echo $vipaction_16; ?>
						<?php } else { ?>
							<?php echo $vipaction_15; ?>
						<?php } ?>
					</td>
					<td class="line"> &nbsp;</td>
					<td class="line"> &nbsp;</td>
					<td class="line"> &nbsp;</td>
					<?php if ($action == 'oui') { #pour les client neuro, pas pour le new web ?>
							<td class="line"> &nbsp;</td>
					<?php } ?>
				</tr>
	<?php 
		} 
	} 
	#/ ## Nouveaux Job - QUE POUR ACTIONS
	?>
				<tr class="planning">
					<td class="tabtitre">N</th>
					<td class="tabtitre"><?php echo $vipaction_01; ?></th>
					<td class="tabtitre"><?php echo $vipaction_02; ?></th>
					<td class="tabtitre"><?php echo $vipaction_03; ?></th>
					<td class="tabtitre"><?php echo $animaction_31; ?></th>
					<td class="tabtitre"><?php echo $vipaction_04; ?></th>
					<td class="tabtitre"><?php echo $vipaction_05; ?></th>
					<td class="tabtitre" width="60"><?php echo $vipaction_06; ?></th>
					<td class="tabtitre" width="60"><?php echo $vipaction_07; ?></th>
					<td class="tabtitre" width="60"><?php echo $vipaction_08; ?></th>
					<?php if ($action == 'oui') { #pour les client neuro, pas pour le new web ?>
						<td class="tabtitre" width="60"><?php echo $vipaction_09; ?></th>
					<?php } ?>
				</tr>
			</table>
			<br>	
</fieldset>
</div>