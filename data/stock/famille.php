<div id="centerzonelargewhite">
<?php
	$detail = new db();
	$detail->inline("SELECT * FROM `stockf` WHERE `idstockf` = $idstockf");
	$infos = mysql_fetch_array($detail->result) ; 
	$did = $infos['idstockf'];
?>
<fieldset class="blue">
	<legend class="blue">
		Famille : <?php echo $infos['idstockf']; ?>
	</legend>
	<?php $classe = "etiq2" ?>
	<?php $classe2 = "standard" ?>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=famillemodif" method="post">
		<input type="hidden" name="idstockf" value="<?php echo $did;?>"> 
	<?php #  Menu de gauche ?>
		<table class="<?php echo $classe; ?>" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
			<tr valign="top">
				<td>
					<table class="<?php echo $classe; ?>" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
						<tr>
							<td class="<?php echo $classe; ?>">
								R&eacute;f&eacute;rence
							</td>
							<td class="<?php echo $classe2; ?>" valign="top">
								<input type="text" size="20" name="reference" value="<?php echo $infos['reference']; ?>">
							</td>
							<td class="<?php echo $classe; ?>"> Description</td>
							<td class="<?php echo $classe2; ?>" rowspan="3">
								<textarea name="description" rows="3" cols="53"><?php echo $infos['description']; ?></textarea>
							</td>
						</tr>
						<tr>
							<td>&nbsp</td>
							<td>&nbsp</td>
							<td>&nbsp</td>
						</tr>
						<tr>
							<td class="<?php echo $classe; ?>">
								Type
							</td>
							<td class="<?php echo $classe2; ?>" valign="top">
								<?php
								echo '<input type="radio" name="type" value="unit" '; if ($infos['type'] == 'unit') { echo 'checked';} echo'> Unit&eacute;s';
								echo '<input type="radio" name="type" value="pack" '; if ($infos['type'] == 'pack') { echo 'checked';} echo'> Package';
								?>
							</td>
							<td>&nbsp</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center">
					<?php if (isset($did)) { ?>
						<input type="submit" name="Modifier" value="Modifier" accesskey="M">
					<?php } else { ?>
						<input type="submit" name="act" value="Ajouter"> 
					<?php } ?>
				</td>
			</tr>
	
		</table>
	</form>
	<table class="<?php echo $classe; ?>" border="0" cellspacing="0" cellpadding="0" align="center" width="98%">
		<tr>
			<td valign="middle">
				<table border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
					<tr>
						<td valign="middle" width="100%">
							<iframe frameborder="0" marginwidth="0" marginheight="0" name="detail-main" src="<?php echo 'famille-onglet.php?idstockf='.$did.'&s='.$_GET['s'].'&action='.$_POST['act'].'';?>" width="100%" height="400" align="top">Marche pas les IFRAMES !</iframe> 
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</fieldset>

