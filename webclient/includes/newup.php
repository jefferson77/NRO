<div class="titre">
<?php echo $Titre ;?>
</div>

<div class="menu">
<table class="uptable" border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td class="dark"><a class="menu" href="<?php echo NIVO ?>webclient/adminclient.php">MENU <img src="<?php echo STATIK ?>illus/precedent.gif" alt="arrow_left.gif" border="0" align="bottom"> <img src="<?php echo STATIK ?>illus/precedent.gif" alt="arrow_left.gif" border="0" align="bottom"></a></td>
		<td class="dark2">
			<?php if ($commande == 'oui') { ?><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=vip0&etat=0">Commander</a> &nbsp;- &nbsp; <?php } ?>
			<?php if ($action == 'oui') { ?><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=vipaction0&etat=0">Actions</a> &nbsp;- &nbsp; <?php } ?>
			<?php if ($archive == 'ouix') { ?><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=viparchive0&etat=0">Archives</a><?php } ?>
		</td>
		<td align="center"><?php echo ${$textehautt} ;?></td>
		<td class="on"><a class="menu" href="<?php echo NIVO ?>webclient/logout.php">LOG OUT<img src="<?php echo STATIK ?>illus/precedent.gif" alt="arrow_left.gif" border="0" align="bottom"> <img src="<?php echo STATIK ?>illus/precedent.gif" alt="arrow_left.gif" border="0" align="bottom"></a></td>
	</tr>
</table>
</div>
