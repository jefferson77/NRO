<div class="corps">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td class="menutitre">
				<?php echo $menu_01; ?>
			</td>
		</tr>
		<tr>
			<td class="newstxt">
				<?php echo $menu_02; ?>
			</td>
		</tr>
	</table>
	<br>
	<table border="0" width="98%" cellspacing="5" cellpadding="1" align="center">
		<tr>
			<td align="center" width="50%">
				<?php if ($_SESSION['webetat'] > 8) { # verif si fiche disponible ?>
					<br>
					<?php echo $menu_03; ?>
					<br>
				<?php } else { ?>
					<a href="newpeople.php?act=modif"><img src="../web/illus/fiche.gif" alt="fiche.gif" width="62" height="60" border="0" align="bottom">
					<br><?php echo $menu_new_01; ?></a>
					<div class="nota"><?php echo $menu_new_02; ?></div>
				<?php } ?>
			</td>
			<td align="center"><img src="../web/illus/dispo.gif" alt="dispo.gif" width="58" height="63" border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_05; ?></font></td>
		</tr>
	</table>
	<br>
	<br>
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="2">
		<tr>
			<td class="menutitre">
				<?php echo $menu_06; ?>
			</td>
		</tr>
		<tr>
			<td class="newstxt">
				<?php echo $menu_07; ?>
			</td>
		</tr>
	</table>
	<br>
	<table class="<?php echo $classe; ?>" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
	<tr>
		<td align="center" width="34%"><img src="../web/illus/anim.gif" alt="anim.gif" width="51" height="64" border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_08; ?></font></td>
		<td align="center" width="33%"><img src="../web/illus/merch.gif" alt="merch.gif" width="51" height="64" border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_09; ?></font></td>
		<td align="center"><img src="../web/illus/vip.gif" alt="vip.gif" width="51" height="64" border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_10; ?></font></td>
	</tr>
	<tr>
		<td colspan="3"><br>&nbsp;</td>
	</tr>
	<tr>
		<td align="center" width="34%"><img src="../web/illus/c4.gif" alt="c4.gif" width="51" height="64" border="0" align="bottom"><br><font color="#cccccc">C 4</font></td>
		<td align="center" width="33%"></td>
		<td align="center"><br>&nbsp;</td>
	</tr>
	</table>
</div>
<div class="news">
			<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="fulltitre" colspan="2">Infos</td>
				</tr>
				<tr>
					<td class="newstit"><?php echo $menu_new_03; ?></td>
					<td class="newsdat"></td>
				</tr>
				<tr>
					<td class="newstxt" colspan="2">
						<?php echo $menu_new_04; ?>
					</td>
				</tr>
			</table>
</div>

