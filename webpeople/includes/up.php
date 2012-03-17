<div class="titre">
<?php echo $Titre ;?>
</div>

<div class="menu">
<table class="uptable" border="0" cellpadding="2" cellspacing="0">
	<tr>
		<td class="on"><a class="menu" href="<?php echo $niveauweb; ?>adminpeople.php">MENU <img src="<?php echo $niveauweb; ?>../illus/precedent.gif" alt="arrow_left.gif" border="0" align="bottom"> <img src="<?php echo $niveauweb; ?>../illus/precedent.gif" alt="arrow_left.gif" border="0" align="bottom"></a></td>
		<td class="dark"><?php echo $_SESSION['prenom'].' '.$_SESSION['nom'] ;?></td>
		<td align="center"><?php echo ${$textehautt} ;?></td>
		<?php if (@$_SESSION['celsys'] == 'celsys') { ?>
			<td class="dark2">
				<font size="1"><?php echo 'id = '.$_SESSION['idpeople']; ?> <a href="<?php echo $_SERVER['PHP_SELF'].'?act='.$_GET['act'].'&langue=nl'; ?>">NL</a> <a href="<?php echo $_SERVER['PHP_SELF'].'?act='.$_GET['act'].'&langue=fr'; ?>">FR</a></font>
			</td>
		<?php } ?>
		<td class="on"><a class="menu" href="<?php echo NIVO ?>www/people/index.php?p=logout">LOG OUT<img src="<?php echo $niveauweb; ?>../illus/precedent.gif" alt="arrow_left.gif" border="0" align="bottom"> <img src="<?php echo $niveauweb; ?>../illus/precedent.gif" alt="arrow_left.gif" border="0" align="bottom"></a></td>
	</tr>
</table>
</div>