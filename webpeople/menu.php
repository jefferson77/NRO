<div class="corps">
<?php if ($_SESSION['out'] != 'out') { ?>
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
				<?php if (@$infos['webetat'] > 8) { # verif si fiche disponible ?>
					<br>
					<?php echo $menu_03; ?>
					<br>
				<?php } else { ?>
					<a href="adminpeople.php?act=modif&s=0"><img src="../web/illus/fiche.gif" alt="fiche.gif" width="62" height="60" border="0" align="bottom"><br><?php echo $menu_04; ?></a>
				<?php } ?>
			</td>
			<td align="center">
				<a href="adminpeople.php?act=dispo0"><img src="../web/illus/dispo.gif" alt="dispo.gif" width="58" height="63" border="0" align="bottom"><br><?php echo $menu_05; ?></a>
			</td>
			<td align="center" width="34%">
				<a href="anim/adminanim.php?act=sales0"><img src="../web/illus/anim.gif" alt="anim.gif" width="51" height="64" border="0" align="bottom"><br>Anim / Sales</a>
			</td>
		</tr>
	</table>
	<br>
<?php } ?>
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
	<table class="<?php echo @$classe; ?>" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
	<tr>
		<?php if ($_SESSION['webdoc'] == 'yes') { ?>
			<td align="center" width="34%"><a href="adminpeople.php?act=contratanim"><img src="../web/illus/anim.gif" alt="anim.gif" width="51" height="64" border="0" align="bottom"><br><?php echo $menu_08; ?></a></td>
			<td align="center" width="33%"><a href="adminpeople.php?act=contratmerch"><img src="../web/illus/merch.gif" alt="merch.gif" width="51" height="64" border="0" align="bottom"><br><?php echo $menu_09; ?></a></td>
			<td align="center"><a href="adminpeople.php?act=contratvip"><img src="../web/illus/vip.gif" alt="vip.gif" width="51" height="64" border="0" align="bottom"><br><?php echo $menu_10; ?></a></td>
		<?php } else { ?>
			<td align="center" width="34%"><img src="../web/illus/anim.gif" alt="anim.gif" width="51" height="64" border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_08; ?></font></td>
			<td align="center" width="33%"><img src="../web/illus/merch.gif" alt="merch.gif" width="51" height="64" border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_09; ?></font></td>
			<td align="center"><img src="../web/illus/vip.gif" alt="vip.gif" width="51" height="64" border="0" align="bottom"><br><font color="#cccccc"><?php echo $menu_10; ?></font></td>
		<?php } ?>

	</tr>
	<tr>
		<td colspan="3"><br>&nbsp;</td>
	</tr>
	<tr>
		<td align="center" width="33%">
			<?php if ($_SESSION['webdoc'] == 'yes') { ?>
				<a href="adminpeople.php?act=easmerch"><img src="../web/illus/merch.gif" alt="merch.gif" width="51" height="64" border="0" align="bottom"><br><?php echo $menu_12; ?></a>
			<?php } else { ?>
				<img src="../web/illus/merch.gif" alt="merch.gif" width="51" height="64" border="0" align="bottom"><br><?php echo $menu_12; ?>
			<?php } ?>
		</td>
		<td align="center" width="34%">
			<?php if ($_SESSION['webdoc'] == 'yes') { ?>
				<a href="adminpeople.php?act=c4"><img src="../web/illus/c4.gif" alt="c4.gif" width="51" height="64" border="0" align="bottom"><br>C 4</a>
			<?php } else { ?>
				<img src="../web/illus/c4.gif" alt="c4.gif" width="51" height="64" border="0" align="bottom"><br>C 4
			<?php } ?>
		</td>
		<td align="center">
			<?php if ($_SESSION['webdoc'] == 'yes') { ?>
			<a href="adminpeople.php?act=attest"><img src="../web/illus/generic.gif" alt="generic.gif" width="51" height="64" border="0" align="bottom"><br><?php echo $menu_13; ?></a>
			<?php } else { ?>
			<img src="../web/illus/generic.gif" alt="generic.gif" width="51" height="64" border="0" align="bottom"><br><?php echo $menu_13; ?>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<td colspan="3"><br>&nbsp;</td>
	</tr>
	<tr>
		<td align="center" width="33%">
			<?php
			$files = array();
			exec('find '.Conf::read('Env.root').'document/people/'.prezero($_SESSION['idpeople'], 5).' -name "281.10-????.pdf"', $files);

			if (($_SESSION['webdoc'] == 'yes') and (count($files) > 0)) { ?>
				<a href="adminpeople.php?act=281.10"><img src="../web/illus/generic.gif" alt="generic.gif" width="51" height="64" border="0" align="bottom"><br>281.10</a>
			<?php } else { ?>
				<img src="../web/illus/generic.gif" alt="merch.gif" width="51" height="64" border="0" align="bottom"><br>281.10
			<?php } ?>
		</td>
		<td align="center" width="34%">
			<?php
			$files = array();
			exec('find '.Conf::read('Env.root').'document/people/'.prezero($_SESSION['idpeople'], 5).' -name "FicheSalaire-??????.pdf"', $files);

			if (($_SESSION['webdoc'] == 'yes') and (count($files) > 0)) { ?>
				<a href="adminpeople.php?act=ficheSalaire"><img src="../web/illus/generic.gif" alt="generic.gif" width="51" height="64" border="0" align="bottom"><br><?php echo $menu_14 ?></a>
			<?php } else { ?>
				<img src="../web/illus/generic.gif" alt="merch.gif" width="51" height="64" border="0" align="bottom"><br><?php echo $menu_14 ?>
			<?php } ?>
		</td>
		<td align="center">
			<a href="../www/people/doc/regl_travail_<?php echo $_SESSION['lang'] ?>.pdf"><img src="../web/illus/generic.gif" alt="generic.gif" width="51" height="64" border="0" align="bottom"><br><?php echo $menu_15 ?></a>
		</td>
	</tr>
	</table>
</div>
<div class="news">
<?php
$titre = 'titre'.$_SESSION['lang'];
$texte = 'texte'.$_SESSION['lang'];
?>
			<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
				<tr>
					<td class="fulltitre" colspan="2">News</td>
				</tr>
<?php
	$detail2 = new db('webnewspeople', 'idwebnewspeople', 'webneuro');
	$detail2->inline("SELECT * FROM `webnewspeople` WHERE `online` = 'oui' ORDER BY datepublic DESC LIMIT 7");
	while ($infos2 = mysql_fetch_array($detail2->result)) {
?>
				<tr>
					<?php if ($infos2['urgent'] == 1) { ?><td class="newstitred"> <?php } else { ?> <td class="newstit"> <?php } ?>
					<?php if ($infos2['urgent'] == 1) { echo '<img src="'.STATIK.'illus/100light.gif" alt="100light.gif" width="16" height="16" border="0" align="bottom">'; } ?> <?php echo stripslashes($infos2[$titre]);?></td>
					<?php if ($infos2['urgent'] == 1) { ?><td class="newstitred"> <?php } else { ?> <td class="newsdat"> <?php } ?>
					<?php echo fdate($infos2['datepublic']);?></td>
				</tr>
				<tr>
					<?php if ($infos2['urgent'] == 1) { ?><td class="newstxtred" colspan="2"><?php echo stripslashes(nl2br($infos2[$texte]));?></td><?php } else { ?><td class="newstxt" colspan="2"><?php echo stripslashes(nl2br($infos2[$texte]));?></td><?php } ?>
				</tr>
<?php } ?>
			</table>
</div>
