<div class="corps98">
	<?php
	setlocale(LC_TIME, $_SESSION['lang']);
	
	$detailjob = new db('webanimjob', 'idwebanimjob ', 'webneuro');
	$detailjob->inline("SELECT * FROM `webanimjob` WHERE `idwebanimjob` = $idwebanimjob ");
	$infosjob = mysql_fetch_array($detailjob->result) ; 
	$s = 4;
	################### Fin Code PHP ########################
	?>
	<?php
	### MENU DU DESSUS
	?>
	<table border="0" cellspacing="1" cellpadding="0" align="center" width="99%" height="20">
		<tr bgcolor="#EEEEEE">
			<td align="center" width="20%">
				<?php if ($s == 0) { $bouton = "boutonx2"; } else { $bouton = "boutonx"; }?>
				<table align="center" cellspacing="0" cellpadding="0" border="0">
					<tr>
					<td width="8"><img src="<?php echo $illus; ?>bouton/left.gif" width="8" height="24" alt="" border="0"></td>
					<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
					<td class="<?php echo $bouton; ?>" height="24" valign="middle" width="125">
						<b><?php echo $vipdetail_01; ?></b>
					</td>
					<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
					<td width="8"><img src="<?php echo $illus; ?>bouton/right.gif" width="8" height="24" alt="" border="0"></td>
					</tr>
				</table>
			</td>
			<td align="center" width="20%">
				<?php if ($s == 1) { $bouton = "boutonx2"; } else { $bouton = "boutonx"; }?>
				<table align="center" cellspacing="0" cellpadding="0" border="0">
					<tr>
					<td width="8"><img src="<?php echo $illus; ?>bouton/left.gif" width="8" height="24" alt="" border="0"></td>
					<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
					<td class="<?php echo $bouton; ?>" height="24" valign="middle" width="125">
						<b>Points of Sales (POS)</b>
					</td>
					<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
					<td width="8"><img src="<?php echo $illus; ?>bouton/right.gif" width="8" height="24" alt="" border="0"></td>
					</tr>
				</table>
			</td>
			<td align="center" width="20%">
				<?php if ($s == 2) { $bouton = "boutonx2"; } else { $bouton = "boutonx"; }?>
				<table align="center" cellspacing="0" cellpadding="0" border="0">
					<tr>
					<td width="8"><img src="<?php echo $illus; ?>bouton/left.gif" width="8" height="24" alt="" border="0"></td>
					<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
					<td class="<?php echo $bouton; ?>" height="24" valign="middle" width="105">
						<b><?php echo $vipdetail_02; ?></b>
					</td>
					<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
					<td width="8"><img src="<?php echo $illus; ?>bouton/right.gif" width="8" height="24" alt="" border="0"></td>
					</tr>
				</table>
			</td>
			<td align="center" width="20%">
				<?php if ($s == 3) { $bouton = "boutonx2"; } else { $bouton = "boutonx"; }?>
				<table align="center" cellspacing="0" cellpadding="0" border="0">
					<tr>
					<td width="8"><img src="<?php echo $illus; ?>bouton/left.gif" width="8" height="24" alt="" border="0"></td>
					<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
					<td class="<?php echo $bouton; ?>" height="24" valign="middle" width="115">
						<b><?php echo $vipdetail_04; ?></b>
					</td>
					<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
					<td width="8"><img src="<?php echo $illus; ?>bouton/right.gif" width="8" height="24" alt="" border="0"></td>
					</tr>
				</table>
			</td>
			<td align="center">
				<?php if ($s == 4) { $bouton = "boutonx2"; } else { $bouton = "boutonx"; }?>
				<table align="center" cellspacing="0" cellpadding="0" border="0">
					<tr>
					<td width="8"><img src="<?php echo $illus; ?>bouton/left.gif" width="8" height="24" alt="" border="0"></td>
					<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
					<td class="<?php echo $bouton; ?>" height="24" valign="middle" width="105">
						<b><?php echo $vipdetail_03; ?></b>
					</td>
					<td class="<?php echo $bouton; ?>" width="15"><img src="<?php echo $illus; ?>bouton/free.gif" width="15" border="0"></td>
					<td width="8"><img src="<?php echo $illus; ?>bouton/right.gif" width="8" height="24" alt="" border="0"></td>
					</tr>
				</table>
			</td>
			<td align="center">
				<?php /* echo $s; */ ?>
			</td>
		</tr>
	</table>
	<?php
	#/ ## MENU DU DESSUS
	?>
	<br>
	<hr align="center" size="1" width="99%">
	<Fieldset class="width2">
		<legend class="width"><?php echo $tool_05f;?></legend>
		<table class="standard" border="0" cellspacing="1" cellpadding="6" align="center" width="99%">
			<tr>
				<td class="etiq2" width="230" valign="top">
					<?php echo $vipdetail_0_01; ?>
				</td>
				<td class="contenu" valign="top">
					<?php echo $infosjob['reference']; ?>
				</td>
			</tr>
			<tr>
				<td class="etiq2" valign="top">
					<?php echo $vipdetail_0_02; ?>
				</td>
				<td class="contenu" valign="top">
					<?php echo nl2br($infosjob['notejob']); ?>
				</td>
			</tr>
			<tr>
				<td class="etiq2" valign="top">
					<?php echo $vipdetail_0_10; ?>
				</td>
				<td class="contenu">
					<?php echo $infosjob['bondecommande']; ?>
				</td>
			</tr>
			<tr>
				<td class="etiq2" valign="top">
					<?php echo $animaction_31; ?>
				</td>
				<td class="contenu">
					<?php if ($infosjob['datecommande'] != '0000-00-00 00:00:00') {echo fdatetime2($infosjob['datecommande']); } ?>
				</td>
			</tr>
		<?php
			################### début vérif datein et dateout  ########################
			$datein = '3000-00-00';
			$dateout = '0000-00-00';
		
			$anim = new db('webanimbuild', 'idanimbuild', 'webneuro');
			$anim->inline("SELECT animdate1 FROM `webanimbuild` WHERE `idwebanimjob` = $idwebanimjob");
			while ($row2 = mysql_fetch_array($anim->result)) { 
				if ($row2['animdate1'] < $datein) { $datein = $row2['animdate1']; }
				if ($row2['animdate1'] > $dateout) { $dateout = $row2['animdate1']; }
			}
				$_POST['datein'] = $datein;
				$_POST['dateout'] = $dateout;
		
				$modif = new db('webanimjob', 'idwebanimjob', 'webneuro');
				$modif->MODIFIE($idwebanimjob, array('datein' , 'dateout' ));
		
				$detailjob = new db('webanimjob', 'idwebanimjob ', 'webneuro');
				$detailjob->inline("SELECT * FROM `webanimjob` WHERE `idwebanimjob` = $idwebanimjob ");
				$infosjob = mysql_fetch_array($detailjob->result) ; 
		
			################### Fin vérif datein et dateout  ########################
		?>
			<?php if ($infosjob['datein'] != '3000-00-00') {?>
				<tr>
					<td class="etiq2" valign="top">
						<?php echo $vipdetail_1_06; ?>
					</td>
					<td class="contenu" valign="top">
						<?php echo $tool_50; ?> <?php echo fdate($infosjob['datein']); ?> <?php echo $tool_51; ?> <?php echo fdate($infosjob['dateout']); ?>
					</td>
				</tr>
			<?php } ?>
		</table>
		<br>
		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="99%">
			<tr class="vip">
				<td class="etiq2"></td>
				<td class="etiq2" align="center">Shop / POS</td>
				<td class="etiq2"><?php /* echo $vipdetail_1_03; */ ?></td>
				<td class="etiq2" align="center">#</td>
				<td class="etiq2"><?php /* echo $vipdetail_1_04; */ ?></td>
				<td class="etiq2" align="center"><?php echo $tool_49; ?></td>
				<td class="etiq2" align="center"><?php echo $tool_52; ?></td>
				<td class="etiq2" align="center"><?php echo $tool_53; ?></td>
				<td class="etiq2" align="center"><?php echo $tool_52; ?></td>
				<td class="etiq2" align="center"><?php echo $tool_53; ?></td>
			</tr>
			<?php
			$classe = "standard";
			$recherche='
				SELECT 
				ab.animnombre, ab.animdate1, ab.animin1, ab.animout1, ab.animin2, ab.animout2, 
				s.societe, s.cp, s.ville, s.adresse 
				FROM webneuro.webanimbuild ab
				LEFT JOIN neuro.shop s ON ab.idshop = s.idshop
				WHERE ab.idwebanimjob = '.$idwebanimjob.' 
				ORDER BY ab.animdate1, s.societe, s.cp
			';

			$detailbuild = new db('webanimbuild', 'idanimbuild', 'webneuro');
			$detailbuild->inline("$recherche;");
			while ($infos = mysql_fetch_array($detailbuild->result)) { 
				$i++;
			?>
				<tr id="line<?php echo $i; ?>" class="contenu">
					<td class="<?php echo $classe; ?>"><?php echo $i; ?>&nbsp;</td>
					<td class="<?php echo $classe; ?>">
						<?php echo substr($infos['societe'], 0, 35).' - '.$infos['cp'].' '.substr($infos['ville'], 0, 20).' ('.substr($infos['adresse'], 0, 30).')'; ?>
					</td>
					<td class="<?php echo $classe; ?>"><?php echo $vipdetail_1_03; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $infos['animnombre']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $animaction_00; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo fdate($infos['animdate1']); ?></td>

					<td class="<?php echo $classe; ?>"><?php echo ftime($infos['animin1']); ?></td>
					<td class="<?php echo $classe; ?>"><?php echo ftime($infos['animout1']); ?></td>
					<td class="<?php echo $classe; ?>"><?php echo ftime($infos['animin2']); ?></td>
					<td class="<?php echo $classe; ?>"><?php echo ftime($infos['animout2']); ?></td>
				</tr>
			<?php } ?>
			<tr class="vip">
				<td class="etiq2"></td>
				<td class="etiq2" align="center">Shop / POS</td>
				<td class="etiq2"><?php /* echo $vipdetail_1_03; */ ?></td>
				<td class="etiq2" align="center">#</td>
				<td class="etiq2"><?php /* echo $vipdetail_1_04; */ ?></td>
				<td class="etiq2" align="center"><?php echo $tool_49; ?></td>
				<td class="etiq2" align="center"><?php echo $tool_52; ?></td>
				<td class="etiq2" align="center"><?php echo $tool_53; ?></td>
				<td class="etiq2" align="center"><?php echo $tool_52; ?></td>
				<td class="etiq2" align="center"><?php echo $tool_53; ?></td>
			</tr>
		</table>
		<br>
	</fieldset>	
</div>
