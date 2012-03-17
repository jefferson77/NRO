<?php $classe = "planning" ; ?>

<div class="corps2">
<Fieldset class="width">
	<legend class="width"><?php if ($action == 'non') { echo $tool_16; } else { echo $tool_17; } ?></legend>
	<br>	
	<table class="<?php echo $classe; ?>" border="0" cellspacing="1" cellpadding="4" align="center" width="98%">
		<tr class="<?php echo $classe; ?>">
			<td class="tabtitre">N</th>
			<td class="tabtitre"><?php echo $animaction_01; ?></th>
			<td class="tabtitre"><?php echo $animaction_02; ?></th>
			<td class="tabtitre"><?php echo $animaction_03; ?></th>
			<td class="tabtitre"><?php echo $animaction_31; ?></th>
			<td class="tabtitre"><?php echo $animaction_04; ?></th>
			<td class="tabtitre"><?php echo $animaction_05; ?></th>
			<td class="tabtitre" width="60"><?php echo $animaction_06; ?></th>
			<td class="tabtitre" width="60"><?php echo $animaction_07; ?></th>
			<td class="tabtitre" width="60"><?php echo $animaction_08; ?></th>
			<?php if ($action == 'oui') { #pour les client neuro, pas pour le new web ?>
				<td class="tabtitre" width="60"><?php echo $animaction_09; ?></th>
			<?php } ?>
		</tr>
		<?php
		### offres Job - QUE POUR ACTIONS
		if (($action == 'non')  and ($_SESSION['new'] == 0)) { ### PAS POUR NEW CLIENT
			$detailjob = new db('webanimjob', 'idwebanimjob', 'webneuro');
			$detailjob->inline("SELECT * FROM `webanimjob` WHERE `idclient` = $idclient AND `etat` >= 5 AND `isnew` = '$new' ORDER BY `datein` DESC");
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
					<td class="line"><?php if ($infos['datecommande'] != '0000-00-00 00:00:00') {echo fdatetime2($infos['datecommande']); } ?></td>
					<td class="line"><a class="white2" href="adminclientanim.php?act=animviewweb&etat=0&idwebanimjob=<?php echo $infos['idwebanimjob']; ?>"><?php echo $animaction_10; ?></a>
					</td>
					<td class="line">
						<?php echo $animaction_11; ?>
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
	$detailjob = new db('animjob', 'idanimjob ');
	if ($action == 'non') { ### recherche pour ACTION
		$detailjob->inline("SELECT * FROM `animjob` WHERE `idclient` = $idclient AND `etat` != 2 AND `etat` <= 14 ORDER BY `etat`, `datein` DESC, `datedevis`");
	}
	if ($archive == 'non') { ### recherche pour ACTION
		$detailjob->inline("SELECT * FROM `animjob` WHERE `idclient` = $idclient AND `etat` != 2 AND `etat` >= 15 ORDER BY `etat`, `datein` DESC, `datedevis`");
	}
	#$detailjob->inline("SELECT * FROM `webanimjob` WHERE `idclient` = $idclient ORDER BY `datein` DESC");
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
			<td class="line"><?php if ($infos['datecommande'] != '0000-00-00 00:00:00') {echo fdatetime2($infos['datecommande']); } ?></td>
			<td class="line"><a class="white2" href="adminclientanim.php?act=animview&etat=0&idanimjob=<?php echo $infos['idanimjob']; ?>"><?php echo $animaction_10; ?></a>
			<td class="line">
				<?php
				switch ($infos['etat']) {
					case "0": 
						if ($infos['datedevis'] == '') {
							echo $animaction_11;
						} else {
							echo $animaction_12;
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
						echo $animaction_13;
					break;
				} 
				$zetat2 = $etat2;
				?>
			</td>
			<td align="center">
				<?php if (!empty($infos['datedevis'])) { 
					$jobnum = str_repeat('0', 5 - strlen($infos['idanimjob'])).$infos['idanimjob']; 
					$explo = explode("-", $infos['datedevis']);
					$explodate = $explo[0].$explo[1].$explo[2];
					$nompdf = 'offre-'.$jobnum.'-'.$explodate.'.pdf';

				?>
					<a class="white2" href="<?php echo $document; ?>offre/anim/<?php echo $nompdf; ?>" method="post" target="popupC" onclick="window.open('','popupC','scrollbars=yes,status=yes,resizable=yes,width=500,height=600');"><img src="<?php echo $illus; ?>minipdf.gif" width="16" height="16" border="0"></a>
				<?php } ?>
					&nbsp;
			</td>
			<td align="center">
				<?php
				 if ($infos['planningweb'] == 'yes') { # planning online == oui
					$idanimjob = $infos['idanimjob'];
					$detailmission = new db('animation', 'idanimation');
					$detailmission->inline("SELECT idanimation FROM `animation` WHERE `idanimjob` = $idanimjob");
						$Foundmission = mysql_num_rows($detailmission->result); 
						if ($Foundmission >= 1) { ?>
							<a class="white2" href="javascript:;" onClick="OpenBrWindow('<?php echo $print; ?>anim/printanim.php?web=web&ptype=planning&print=go&idanimjob=<?php echo $infos['idanimjob'];?>','Main','','300','300','true')"><img src="<?php echo $illus; ?>minipdf.gif" width="16" height="16" border="0"></a>
						<?php } ?>	
				<?php } ?>
				&nbsp;
			</td>
			<td align="center">
				<?php
				 if ($infos['planningweb'] == 'yes') { # planning online == oui
						$Foundmission = mysql_num_rows($detailmission->result); 
						if ($Foundmission >= 1) { ?>
							<a class="white2" href="javascript:;" onClick="OpenBrWindow('<?php echo $print; ?>anim/printanim.php?web=web&ptype=rapportc&print=go&idanimjob=<?php echo $infos['idanimjob'];?>','Main','','300','300','true')"><img src="<?php echo $illus; ?>minipdf.gif" width="16" height="16" border="0"></a>
						<?php } ?>	
				<?php } ?>
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
		$detailjob = new db('webanimjob', 'idwebanimjob ', 'webneuro');

		if ($_SESSION['new'] == 1) {
		### client webnew
			$detailjob->inline("SELECT * FROM `webanimjob` WHERE `idwebclient` = $idwebclient AND `etat` <= 4 AND `isnew` = '$new' ORDER BY `datein` DESC");
		}
		### client webnew
		else {
			$detailjob->inline("SELECT * FROM `webanimjob` WHERE `idclient` = $idclient AND `etat` <= 4 AND `isnew` = '$new' ORDER BY `datein` DESC");
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
					<td class="line"><?php if ($infos['datecommande'] != '0000-00-00 00:00:00') {echo fdatetime2($infos['datecommande']); } ?></td>
					<td class="line">
						<?php if ($infos['etat'] <= 4) { ?>
							<a class="white2" href="adminclientanim.php?act=animmodif0a&etat=0&idwebanimjob=<?php echo $infos['idwebanimjob']; ?>"><?php echo $animaction_14; ?></a>
						<?php } else { ?>
							<?php echo $animaction_15; ?>
						<?php } ?>
					</td>
					<td class="line">
						<?php if ($infos['etat'] <= 4) { ?>
							<?php echo $animaction_16; ?>
						<?php } else { ?>
							<?php echo $animaction_15; ?>
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
				<tr class="<?php echo $classe; ?>">
					<td class="tabtitre">N</th>
					<td class="tabtitre"><?php echo $animaction_01; ?></th>
					<td class="tabtitre"><?php echo $animaction_02; ?></th>
					<td class="tabtitre"><?php echo $animaction_03; ?></th>
					<td class="tabtitre"><?php echo $animaction_31; ?></th>
					<td class="tabtitre"><?php echo $animaction_04; ?></th>
					<td class="tabtitre"><?php echo $animaction_05; ?></th>
					<td class="tabtitre" width="60"><?php echo $animaction_06; ?></th>
					<td class="tabtitre" width="60"><?php echo $animaction_07; ?></th>
					<td class="tabtitre" width="60"><?php echo $animaction_08; ?></th>
					<?php if ($action == 'oui') { #pour les client neuro, pas pour le new web ?>
						<td class="tabtitre" width="60"><?php echo $animaction_09; ?></th>
					<?php } ?>
				</tr>
			</table>
			<br>	
</fieldset>
</div>