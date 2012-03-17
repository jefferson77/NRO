<div class="corps">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="newstit">
				<?php echo $_SESSION['qualite'].' '.$_SESSION['prenom'].' '.$_SESSION['nom'].', '.$menu_00.' '.$_SESSION['societe']; ?>
				<?php /* #echo '<br>idwebclient = '.$_SESSION['idwebclient'].' idclient = '.$_SESSION['idclient'].' idcofficer = '.$_SESSION['idcofficer'].' new = '.$_SESSION['new'].' newvip ='.$_SESSION['newvip']; */ ?>
			</td>
		</tr>
	</table>
	<br>
<?php 
if ($_SESSION['new'] == '1') {
	if (($_SESSION['newvip'] == 'closed') or ($_SESSION['newanim'] == 'closed')) {
		echo '<font color="#00427B"><i>'.$menu_10.'</i></font><br><br>';
	}
}
?>
<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td rowspan="3" bgcolor="#CC0000" width="2"></td>
			<td class="menutitre">
				<?php echo $menu_01; ?> 
			</td>
		</tr>
		<tr>
			<td class="newstxt">
				<?php echo $menu_02; ?>
			</td>
		</tr>
		<tr>
			<td>
				<table border="0" width="98%" cellspacing="5" cellpadding="1" align="center">
					<tr>
						<td align="center" width="25%">
							<?php if ($_SESSION['new'] == '1') { /* ### NEW CLIENT */ ?> 
								<?php if ($_SESSION['newvip'] == 'closed') { /* ### NEW CLIENT SI VIP a été cloturée OU SI une anim/merch a été commencée */ ?> 
									<img src="../web/illus/fiche.gif" alt="fiche.gif" height="50"border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_03; ?></font>
								<?php } else { /* ### NEW CLIENT SI VIP N'a PAS été cloturée ET SI une anim/merch N'a PAS été commencée */ ?>
									<?php if (!empty($_SESSION['newvip'])) { /* ### NEW CLIENT SI VIP a été commencée et donc == $idwebvipjob */ ?> 
										<a href="vip/adminclientvip.php?act=vip0a&etat=0"><img src="../web/illus/fiche.gif" alt="fiche.gif" height="50"border="0" align="bottom"><br><?php echo $menu_03; ?></a>
									<?php } else { /* ### NEW CLIENT SI VIP N'a PAS été commencée */ ?>
										<a href="vip/adminclientvip.php?act=vip0&etat=0"><img src="../web/illus/fiche.gif" alt="fiche.gif" height="50"border="0" align="bottom"><br><?php echo $menu_03; ?></a>
									<?php } ?>
								<?php } ?>
							<?php } else { /* ### CLIENT (PAS NEW) */ ?>
								<a href="vip/adminclientvip.php?act=vip0&etat=0"><img src="../web/illus/fiche.gif" alt="fiche.gif" height="50"border="0" align="bottom"><br><?php echo $menu_03; ?></a>
							<?php } ?>
						</td>
						<td align="center">
							<?php if ($_SESSION['new'] == '1') { /* ### NEW CLIENT */ ?> 
								<img src="../web/illus/dispo.gif" alt="dispo.gif" height="50" border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_04; ?></font>
							<?php } else {  /* ### CLIENT (PAS NEW) */ ?>
								<a href="vip/adminclientvip.php?act=vipaction0&etat=0"><img src="../web/illus/dispo.gif" alt="dispo.gif" height="50" border="0" align="bottom"><br><?php echo $menu_04; ?></a>
							<?php } ?>
						</td>
						<td align="center" width="25%">
							<?php if ($_SESSION['new'] == '1') { /* NEW CLIENT */ ?> 
								<img src="../web/illus/generic.gif" alt="dispo.gif" height="50" border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_05; ?></font>
							<?php } else { /* CLIENT (PAS NEW) */ ?>
								<a href="vip/adminclientvip.php?act=viparchive0&etat=0"><img src="../web/illus/generic.gif" alt="dispo.gif" height="50" border="0" align="bottom"><br><?php echo $menu_05; ?></a>
							<?php } ?>
						</td>
						<td align="center" width="25%">
							<br>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td rowspan="3" bgcolor="#33CC00" width="2"></td>
			<td class="menutitre">
				<?php echo $menu_06; ?> <font color="#888888"></font>
			</td>
		</tr>
		<tr>
			<td class="newstxt">
				<?php echo $menu_07; ?>
			</td>
		</tr>
		<tr>
			<td>
				<table border="0" width="98%" cellspacing="5" cellpadding="1" align="center">
					<tr>
						<td align="center" width="25%">
							<?php 
							##### ATTTENTION ENLEVER LE  and ($_SESSION['celsys'] == 'celsys')) QUAND ANIM OK !!!!!!!
							if ($_SESSION['new'] == '1'){ /* NEW CLIENT */ ?> 
								<?php if ($_SESSION['newanim'] == 'closed') { /* NEW CLIENT SI ANIM a été cloturée OU SI une vip/merch a été commencée*/ ?> 
									<img src="../web/illus/fiche.gif" alt="fiche.gif" height="50"border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_03; ?></font>
								<?php } else { /* NEW CLIENT SI ANIM N'a PAS été cloturée ET SI une vip/merch N'a PAS été commencée */ ?>
									<?php if (!empty($_SESSION['newanim'])) { /* NEW CLIENT SI ANIM a été commencée et donc == $idwebanimjob */ ?> 
										<a href="anim/adminclientanim.php?act=anim0a&etat=0"><img src="../web/illus/fiche.gif" alt="fiche.gif" height="50"border="0" align="bottom"><br><?php echo $menu_03; ?></a>
									<?php } else { /* NEW CLIENT SI ANIM N'a PAS été commencée */ ?>
										<a href="anim/adminclientanim.php?act=anim0&etat=0"><img src="../web/illus/fiche.gif" alt="fiche.gif" height="50"border="0" align="bottom"><br><?php echo $menu_03; ?></a>
									<?php } ?>
								<?php } ?>
							<?php } else { /* CLIENT (PAS NEW)      
							
							
							<a href="anim/adminclientanim.php?act=anim0&etat=0"><img src="../web/illus/fiche.gif" alt="fiche.gif" height="50"border="0" align="bottom"><br><?php echo $menu_03; ?></a>
							*/ ?>
									
									<img src="../web/illus/fiche.gif" alt="fiche.gif" height="50"border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_03; ?></font>
							<?php } ?>
						</td>
						<td align="center" width="25%">
							<?php if ($_SESSION['new'] == '1') { /* NEW CLIENT */ ?> 
								<img src="../web/illus/dispo.gif" alt="dispo.gif" height="50" border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_04; ?></font>
							<?php } else {  /* CLIENT (PAS NEW) */ ?>
									<a href="anim/adminclientanim.php?act=animaction0&etat=0"><img src="../web/illus/dispo.gif" alt="dispo.gif" height="50" border="0" align="bottom"><br><?php echo $menu_04; ?></a>
							<?php } ?>
						</td>
						<td align="center" width="25%">
							<?php if ($_SESSION['new'] == '1') { /* NEW CLIENT */ ?> 
								<img src="../web/illus/generic.gif" alt="dispo.gif" height="50" border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_05; ?></font>
							<?php } else {  ### CLIENT (PAS NEW) ?>
									<a href="anim/adminclientanim.php?act=animarchive0&etat=0"><img src="../web/illus/generic.gif" alt="dispo.gif" height="50" border="0" align="bottom"><br><?php echo $menu_05; ?></a>
							<?php } ?>
						</td>
						<td align="center" width="25%">
							<?php if ($_SESSION['new'] == '1') { /* NEW CLIENT */ ?> 
								<img src="../web/illus/vente.gif" alt="dispo.gif" height="50" border="0" align="bottom"><br><font color="#cccccc">Sales</font>
							<?php } else {  /* CLIENT (PAS NEW) */ ?>
									<a href="anim/adminclientanim.php?act=animvente&etat=0"><img src="../web/illus/vente.gif" alt="dispo.gif" height="50" border="0" align="bottom"><br>Sales</a>
							<?php } ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td rowspan="3" bgcolor="#FF9900" width="2"></td>
			<td class="menutitre">
				<?php echo $menu_08; ?> <font color="#888888">(<?php echo $tool_18; ?>)</font>
			</td>
		</tr>
		<tr>
			<td class="newstxt">
				<?php echo $menu_09; ?>
			</td>
		</tr>
		<tr>
			<td>
				<table border="0" width="98%" cellspacing="5" cellpadding="1" align="center">
					<tr>
						<td align="center" width="25%">
							<img src="../web/illus/fiche.gif" alt="fiche.gif" height="50" border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_03; ?></font>
						</td>
						<td align="center">
							<img src="../web/illus/dispo.gif" alt="dispo.gif" height="50" border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_04; ?></font>
						</td>
						<td align="center" width="25%">
							<img src="../web/illus/generic.gif" alt="dispo.gif" height="50" border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_05; ?></font>
						</td>
						<td align="center" width="25%">
							<?php if ($_SESSION['new'] == '1') { /* NEW CLIENT */ ?> 
								<img src="../web/illus/planning2.gif" alt="planning2.gif" height="50" border="0" align="bottom"><br><font color="#cccccc">Planning</font>
							<?php } else {  /* CLIENT (PAS NEW) */ ?>
									<a href="merch/adminclientmerch.php?act=merchplanningsearch&etat=0"><img src="../web/illus/planning2.gif" alt="planning2.gif" height="50" border="0" align="bottom"><br>Planning</a>
							<?php } ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<div style="text-align: center; margin-top: 20px"><a style="font-size: 10px; font-weight: normal" href="conditions.pdf"><?php echo $menu_conditions; ?></a></div>
	<br>
</div>
<div class="news">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="fulltitre" colspan="2">Infos</td>
		</tr>
		<tr>
			<td class="newstit"><?php echo $menu_news_03; ?></td>
			<td class="newsdat"></td>
		</tr>
		<tr>
			<td class="newstxt" colspan="2">
				<p>
					<?php echo $menu_news_05; ?>
					<br><br>
					<?php echo $menu_news_06; ?>
					<br>
					<?php echo $menu_news_07; ?>
					<br><br>
					<img src='../web/illus/fiche.gif' alt='fiche.gif' height='20' border='0' align='bottom'> 
					<b><?php echo $menu_news_08; ?></b> :
					<br>
					<?php echo $menu_news_09; ?>
					<br>
					<?php echo $menu_news_10; ?>
					<br><br>
					<img src='../web/illus/dispo.gif' alt='dispo.gif' height='20' border='0' align='bottom'> <b><?php echo $menu_news_11; ?></b> :
					<br>
					<?php echo $menu_news_12; ?>
					<br>
					<?php echo $menu_news_13; ?>
					<br><br>
					<img src='../web/illus/generic.gif' alt='dispo.gif' height='20' border='0' align='bottom'> <b><?php echo $menu_news_14; ?></b> :
					<br>
					<?php echo $menu_news_15; ?> 
					<br>
					<?php echo $menu_news_16; ?>
					<br><br>
					<img src='../web/illus/vente.gif' alt='vente.gif' height='20' border='0' align='bottom'> <b>Sales</b> :
					<br>
					<?php echo $menu_news_19; ?> 
					<br><br>
					<img src='../web/illus/planning2.gif' alt='planning2.gif' height='20' border='0' align='bottom'> <b>Planning</b> :
					<br>
					<?php echo $menu_news_20; ?> 
					<br><br><br>
					<?php echo $menu_news_17; ?>
					<br><br>
					<?php echo $menu_news_18; ?>
				</p>
			</td>
		</tr>
	</table>
</div>
