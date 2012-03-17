<div class="titre">
<?php echo $Titre ;?>
</div>

<div class="menu">
<table class="uptable" border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td class="dark">
			<?php if ($_GET['act'] == 'modifcontact') { ### SI NEW CLIENT sur fiche Contact ?>
				<?php echo $tool_14 ;?> <img src="<?php echo STATIK ?>illus/precedent.gif" alt="arrow_left.gif" border="0" align="bottom"> <img src="<?php echo STATIK ?>illus/precedent.gif" alt="arrow_left.gif" border="0" align="bottom">
			<?php } else { ?>
				<a class="menu" href="<?php echo NIVO ?>webclient/adminclient.php"><?php echo $tool_14 ;?> <img src="<?php echo STATIK ?>illus/precedent.gif" alt="arrow_left.gif" border="0" align="bottom"> <img src="<?php echo STATIK ?>illus/precedent.gif" alt="arrow_left.gif" border="0" align="bottom"></a>
			<?php } ?>
		</td>
		<td class="dark2">
			<?php if (($_SESSION['new'] != 1) and ($action == 'oui')) { ?><a href="<?php echo $_SERVER['PHP_SELF'].'?act='.$secteurexception.'action0&etat=0'; ?>"><?php echo $tool_16 ;?></a> &nbsp;- &nbsp; <?php } ?>
			<?php if (($_SESSION['new'] != 1) and ($archive == 'oui')) { ?><a href="<?php echo $_SERVER['PHP_SELF'].'?act='.$secteurexception.'archive0&etat=0'; ?>"><?php echo $tool_17 ;?></a><?php } ?>
			<?php if (($_SESSION['new'] != 1) and ($sales == 'oui')) { ?><a href="<?php echo $_SERVER['PHP_SELF'].'?act=sales'; ?>">Sales</a><?php } ?>
		</td>
		<td align="center"><?php echo ${$textehautt} ;?></td>
		<?php if ($_SESSION['celsys'] == celsys) { ?>
			<td class="dark2">
				<?php echo 'id = '.$_SESSION['idclient']; ?> <a href="<?php echo $_SERVER['PHP_SELF'].'?act='.$_GET['act'].'&langue=nl'; ?>">NL</a> <a href="<?php echo $_SERVER['PHP_SELF'].'?act='.$_GET['act'].'&langue=fr'; ?>">FR</a>
			</td>
		<?php } ?>
		<td class="on"><a class="menu" href="<?php echo NIVO ?>webclient/logout.php">LOG OUT<img src="<?php echo STATIK ?>illus/precedent.gif" alt="arrow_left.gif" border="0" align="bottom"> <img src="<?php echo STATIK ?>illus/precedent.gif" alt="arrow_left.gif" border="0" align="bottom"></a></td>
	</tr>
</table>
</div>
<?php if (!empty($_GET['langue'])) $_SESSION['lang'] = $_GET['langue']; ?>