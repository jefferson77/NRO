<?php
# Entete de page
define('NIVO', '../');
$css = standard;
include NIVO."includes/ifentete.php" ;

## Modification de la ligne #############
if ($_REQUEST['act'] == 'modifier') $DB->MAJ('animation');

$infos = $DB->getRow("SELECT * FROM `animation` WHERE `idanimation` = ".$_REQUEST['idanimation']);
?>
<div id="orangepeople">
<?php
if ($disable != 'disabled') { /* ### page input */ ?>
<form action="?act=modifier" method="post">
	<input type="hidden" name="idanimation" value="<?php echo $_REQUEST['idanimation'] ?>">
<?php } /* ### page input */ ?>
	<table border="0" class="standard" cellspacing="1" cellpadding="0" align="center" width="99%">
		<tr>
			<td>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" width="98%">
					<tr>
						<th class="vip">Stand</th>
						<td>
							<?php 
							echo '<input type="radio" name="standqualite" value="2" '; if ($infos['standqualite'] == '2') { echo 'checked';} echo'> + ';
							echo '<input type="radio" name="standqualite" value="1" '; if ($infos['standqualite'] == '1') { echo 'checked';} echo'> +/- ';
							echo '<input type="radio" name="standqualite" value="0" '; if ($infos['standqualite'] == '0') { echo 'checked';} echo'> -';
							?>
						</td>
					</tr>
					<tr>
						<td colspan="2"><textarea name="standnote" rows="2" cols="30" <?php echo $disable; ?>><?php echo $infos['standnote']; ?></textarea></td>
					</tr>
					<tr>
						<th class="vip">Notes people</th>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><textarea name="peoplenote" rows="2" cols="30" <?php echo $disable; ?>><?php echo $infos['peoplenote']; ?></textarea></td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip">Note magasin</th>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2"><textarea name="shopnote" rows="2" cols="30" <?php echo $disable; ?>><?php echo $infos['shopnote']; ?></textarea></td>
					</tr>
					<tr>
						<th class="vip">Autres<br>animations</th>
						<td>
							<?php 
							echo '<input type="radio" name="autreanim" value="1" '.(($infos['autreanim'] == '1')?'checked':'').'> oui ';
							echo '<input type="radio" name="autreanim" value="0" '; if ($infos['autreanim'] == '0') { echo 'checked';} echo'> non';
							?>
						</td>
					</tr>
					<tr>
						<td colspan="2"><textarea name="autreanimnote" rows="2" cols="30" <?php echo $disable; ?>><?php echo $infos['autreanimnote']; ?></textarea></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<?php if ($disable != 'disabled') { ### page input ?>
					<input type="submit" name="Modifier" value="Modifier">
				<?php } ### page input ?>
			</td>
		</tr>
	</table>
<?php if ($disable != 'disabled') { ?></form><?php } ?>
</div>
<?php include NIVO."includes/ifpied.php"; ?>