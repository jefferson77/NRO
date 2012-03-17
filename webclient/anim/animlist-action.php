<?php $classe = "planning" ; ?>
<div class="corps2">
<Fieldset class="width2">
	<legend class="width"><?php if ($action == 'non') { echo $tool_16; } else { echo $tool_17; } ?></legend>
	<table class="<?php echo $classe; ?>" border="0" cellspacing="1" cellpadding="4" align="center" width="98%">
		<tr>
			<td class="newstxt">
				<img src="<?php echo $illus; ?>info.png" alt="<?php echo $tool_05h; ?>"> &nbsp;<?php echo $tool_60; ?><br>
				<img src="<?php echo $illus; ?>info.png" alt="<?php echo $tool_05h; ?>"> &nbsp;<?php echo $anim_sales_menu_06c; ?> (<?php echo fdate($onyearago).' - '.fdate(date("Y-m-d"); ?>)<br>
				<img src="<?php echo $illus; ?>info.png" alt="<?php echo $tool_05h; ?>"> &nbsp;<?php echo $anim_sales_menu_06a; ?> <b><?php echo $anim_sales_menu_06b; ?></b>
			</td>
		</tr>
	</table>
	<table class="<?php echo $classe; ?>" border="0" cellspacing="1" cellpadding="4" align="center" width="98%">
		<tr class="<?php echo $classe; ?>">
			<td class="tabtitre"><?php echo $animaction_01; ?></td>
			<td class="tabtitre"><?php echo $animaction_02; ?></td>
			<td class="tabtitre"><?php echo $animaction_03; ?></td>
			<td class="tabtitre"><?php echo $animaction_31; ?></td>
			<td class="tabtitre" width="60"><?php echo $animaction_06; ?></td>
			<td class="tabtitre" width="80"><?php echo $animaction_07; ?></td>
			<td class="tabtitre" width="60"><?php echo $animaction_08; ?></td>
			<?php if ($action == 'oui') { ?><td class="tabtitre" width="60"><?php echo $animaction_09; ?></td><?php } ?>
		</tr><?php
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
					<td class="line"><div align="left"><?php echo stripslashes($infos['reference']); ?> &nbsp;</div></td>
					<td class="line"><?php echo fdate($infos['datein']); ?> &nbsp;</td>
					<td class="line"><?php echo fdate($infos['dateout']); ?> &nbsp;</td>
					<td class="line"><?php #echo fdatetime2($infos['datecommande']); ?></td>
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
					<td class="line">&nbsp; </td>
					<td class="line">&nbsp; </td>
					<td class="line">&nbsp; </td>
					<?php if ($action == 'oui') { ?><td class="line">&nbsp; </td>
					<?php } ?>
				</tr>
			<?php } ?>
			<tr>
				<td colspan="10" height="8"></td>
			</tr>
		<?php 
		} 
		#/ ## Nouveaux Job - QUE POUR ACTIONS

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
					<td class="line"><div align="left"><?php echo stripslashes($infos['reference']); ?></div></td>
					<td class="line"><?php echo fdate($infos['datein']); ?> &nbsp;</td>
					<td class="line"><?php echo fdate($infos['dateout']); ?> &nbsp;</td>
					<td class="line"><?php echo fdatetime($infos['datecommande']); ?></td>
					<td class="line">
						<?php echo $animaction_11; ?>
					</td>
					<td class="line">&nbsp; </td>
					<td class="line">&nbsp; </td>
					<td class="line">&nbsp; </td>
					<?php if ($action == 'oui') { ?><td class="line">&nbsp; </td><?php } ?>
				</tr>
		<?php 
			} 
		} 
#/ ## offres Job - QUE POUR ACTIONS

if ($new != 1) { #pour les client neuro, pas pour le new web
	$zetat2 = 'z';
	$detailjob = new db();

	if ($action == 'non') { ### recherche pour ACTION
		$detailjob->inline("SELECT j.* FROM animation a LEFT JOIN animjob j ON a.idanimjob = j.idanimjob WHERE j.idclient = '$idclient' GROUP BY a.idanimjob HAVING AVG(a.facturation) < 8 ORDER BY j.datein DESC");
	}

	if ($archive == 'non') { ### recherche pour ACTION
		$detailjob->inline("SELECT j.* FROM animation a LEFT JOIN animjob j ON a.idanimjob = j.idanimjob WHERE j.idclient = '$idclient' AND a.datem > '$onyearago' GROUP BY a.idanimjob HAVING AVG(a.facturation) = 8 ORDER BY j.datein DESC");
	}

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
<?php		}
		#> Changement de couleur des lignes #####>>####
		$i++;
		if (fmod($i, 2) == 1) {
			echo '<tr bgcolor="#78ABD7">';
		} else {
			echo '<tr bgcolor="#97B4CD">';
		}
		#< Changement de couleur des lignes #####<<####
?>

			<td class="line"><div align="left"><?php echo stripslashes($infos['reference']); ?> &nbsp;</div></td>
			<td class="line"><?php echo fdate($infos['datein']); ?> &nbsp;</td>
			<td class="line"><?php echo fdate($infos['dateout']); ?> &nbsp;</td>
			<td class="line"><?php echo fdatetime($infos['datecommande']); ?></td>
			<td align="center"><?php 
			## Offres
				if (!empty($infos['datedevis'])) { 
				 if ($infos['offreweb'] == 'yes') { # planning online == oui
					$jobnum = str_repeat('0', 5 - strlen($infos['idanimjob'])).$infos['idanimjob']; 
					$explo = explode("-", $infos['datedevis']);
					$explodate = $explo[0].$explo[1].$explo[2];
					$nompdf = 'offre-'.$jobnum.'-'.$explodate.'.pdf';
				?><a class="white2" href="<?php echo $document; ?>offre/anim/<?php echo $nompdf; ?>" method="post" target="popupC" onclick="window.open('','popupC','scrollbars=yes,status=yes,resizable=yes,width=500,height=600');"><img src="<?php echo $illus; ?>minipdf.gif" width="12" height="12" border="0" alt="">&nbsp;<?php echo $animaction_06; ?></a><?php } } ?>
			</td>
			<td align="center"><?php
			## Consulter
				 if ($infos['planningweb'] == 'yes') { # planning online == oui
					$idanimjob = $infos['idanimjob'];
					$detailmission = new db();
					$detailmission->inline("SELECT idanimation FROM `animation` WHERE `idanimjob` = $idanimjob");
						$Foundmission = mysql_num_rows($detailmission->result); 
						if ($Foundmission >= 1) { ?><a class="white2" href="adminclientanim.php?act=animview&idanimjob=<?php echo $infos['idanimjob']; ?>"><img src="<?php echo $illus; ?>minipdf.gif" width="12" height="12" border="0" alt="">&nbsp;<?php echo $animaction_07; ?></a>
						<?php } 
				} ?>
			</td>
			<td align="center"><?php
			## Rapport Client
				 if ($infos['planningweb'] == 'yes') { # planning online == oui

					$date = weekdate(date ("W") + 1, date ("Y"));

					$detailmission = new db();
					$detailmission->inline("SELECT idanimation FROM `animation` WHERE `idanimjob` = '$idanimjob' AND `datem` < '".$date['lun']."'");
				 	
					if (mysql_num_rows($detailmission->result) >= 1) { ?><a class="white2" href="javascript:;" onClick="OpenBrWindow('<?php echo $print; ?>anim/printanim.php?web=web&ptype=rapportc&print=go&idanimjob=<?php echo $infos['idanimjob'];?>','Main','','300','300','true')"><img src="<?php echo $illus; ?>minipdf.gif" width="12" height="12" border="0" alt="">&nbsp;<?php echo $animaction_08; ?></a>
				<?php } 
			} ?>
			</td>
			<?php if ($action == 'oui') { /* #pour les client neuro, pas pour le new web */ ?>
				<td align="center"><?php 
					if ($infos['facturation'] >= 8)  {
							$detailmission = new db(); 

							$detailmission->inline("SELECT facnum FROM `animation` WHERE `idanimjob` = '".$infos['idanimjob']."'");
							
							while ($info = mysql_fetch_array($detailmission->result)) {
								if ($info['facnum']   > 0) $lesfacs[]   = $info['facnum']; 
							}
							#dedoublonne
							if (is_array($lesfacs)) {
								$lesfacs = array_unique ($lesfacs); 
								$rowsp = count($lesfacs);
								$facs = implode(", ", $lesfacs);
							}
							if (is_array($lesfacs06)) {
								$lesfacs06 = array_unique ($lesfacs06);
								$rowsp06 = count($lesfacs06);
								$facs06 = implode(", ", $lesfacs06);
							}
					?>
						<table style="font-size: 9px;">
<?php if (!empty($facs)) { ?>
							<tr><td rowspan="<?php echo $rowsp; ?>"><a class="white2" href="javascript:;" onClick="OpenBrWindow('facture.php?facs=<?php echo urlencode($facs);?>','Main','','300','300','true')"><img src="<?php echo $illus; ?>minipdf.gif" width="16" height="16" border="0" alt=""></a></td>
							<?php foreach ($lesfacs as $numfac) {?>
								<td><?php echo $numfac?></td></tr><tr>
							<?php } ?>
							</tr>
<?php }
if (!empty($facs06)) { ?>
							<tr><td rowspan="<?php echo $rowsp06; ?>"><a class="white2" href="javascript:;" onClick="OpenBrWindow('facture.php?facs06=<?php echo urlencode($facs06);?>','Main','','300','300','true')"><img src="<?php echo $illus; ?>minipdf.gif" width="16" height="16" border="0" alt=""></a></td>
							<?php foreach ($lesfacs06 as $numfac) {?>
								<td><?php echo $numfac?></td></tr><tr>
							<?php } ?>
							</tr>
<?php } ?>
						</table><?php
						unset($lesfacs);
						unset($facs);
						unset($rowsp);
						unset($lesfacs06);
						unset($facs06);
						unset($rowsp06);
					} ?></td><?php } ?>
		</tr>
<?php } # fin du while
} # fin du if new
?>
				<tr class="<?php echo $classe; ?>">
					<td class="tabtitre"><?php echo $animaction_01; ?></td>
					<td class="tabtitre"><?php echo $animaction_02; ?></td>
					<td class="tabtitre"><?php echo $animaction_03; ?></td>
					<td class="tabtitre"><?php echo $animaction_31; ?></td>
					<td class="tabtitre" width="60"><?php echo $animaction_06; ?></td>
					<td class="tabtitre" width="80"><?php echo $animaction_07; ?></td>
					<td class="tabtitre" width="60"><?php echo $animaction_08; ?></td>
					<?php if ($action == 'oui') { ?><td class="tabtitre" width="60"><?php echo $animaction_09; ?></td><?php } ?>
				</tr>
			</table>
			<br>	
</fieldset>
</div>