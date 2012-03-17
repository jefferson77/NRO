<?php #  Centre de recherche de shop
if ($_GET['addshop'] == 2) { ### pour empêcher la recherche à vide
	if (($_POST['societe'] == '') or ($_POST['cp'] == '')) {
		$_GET['addshop'] = 1;
		$textvide = "<br><i>".$animpos_23."</i>";
	} else {
		$_GET['addshop'] = 3;
	}
}
if ($_GET['addshop'] == 1) { 
	echo $textvide;
?>
	<form action="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&addshop=2&section=addshop';?>" method="post">
		<table class="standard" border="0" cellspacing="1" cellpadding="6" width="50%">
			<tr>
				<td><label for="societe"><?php echo $vipdetail_0_03; ?> / POS</label> <input type="text" name="societe" id="societe"></td>
				<td><label for="cp"><?php echo $contact_11; ?></label> <input type="text" name="cp" id="cp" value="<?php echo $_POST['cp']; ?>"></td>
			</tr>
			<tr>
				<td align="center" colspan="2"><input type="submit" name="Rechercher" value="<?php echo $animpos_12;?>"></td>
			</tr>
		</table>	
  	</form>
<?php 
} 
if ($_GET['addshop'] == 3) {
			# VARIABLE SELECT
			if (!empty($_POST['societe'])) {
				if (!empty($quid)) 
				{
					$quid=$quid." AND ";
					$quod=$quod." ET ";
				}	
				$quid=$quid."societe LIKE '%".$_POST['societe']."%'";
				$quod=$quod."societe = ".$_POST['societe'];
			}
			if (!empty($_POST['cp'])) {
				if (!empty($quid)) 
				{
					$quid=$quid." AND ";
					$quod=$quod." ET ";
				}	
				$quid=$quid."cp LIKE '%".$_POST['cp']."%'";
				$quod=$quod."CP = ".$_POST['cp'];
			}
		
			# ATTENTION POUR TEXTE : LIKE '%XXXX%'
			
			if (!empty($quid)) {$quid='WHERE '.$quid;}
			if ($sort == '') {$sort='codeshop';}
			$recherche='
				SELECT * FROM `shop`
				'.$quid.'
				 ORDER BY '.$sort.'
			';
			# echo $recherche;
	# Recherche des résultats pour la liste NEURO
	$shop = new db();
	$shop->inline("$recherche;");
	$FoundCount = mysql_num_rows($shop->result); 
	$classe = "standard";
	### tableau de résultat
	if ($FoundCount > 0) { 
?>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=animmodif<?php echo $s; ?>&section=searchadd" method="post">
		<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>"> 
		<input type="hidden" name="shopselection" value="<?php echo $shopselection;?>">
		<input type="hidden" name="shopselectionnew" value="<?php echo $shopselectionnew;?>">
		<input type="hidden" name="s" value="<?php echo $s;?>">
		<table class="standard" border="0" cellspacing="1" cellpadding="6" align="center" width="98%">
			<tr>
				<td colspan="6">
					<img src="<?php echo $illus; ?>info.png" alt="<?php echo $tool_05h; ?>"> &nbsp; <?php echo $tool_104; ?>
				</td>
			</tr>
			<tr>
				<td class="etiq3"><?php echo $animpos_16; ?></td>
				<td class="etiq3"><?php echo $animpos_17; ?></td>
				<td class="etiq3"><?php echo $animpos_18; ?></td>
				<td class="etiq3"><?php echo $animpos_19; ?></td>
				<td class="etiq3"><?php echo $animpos_20; ?></td>
				<td class="etiq3"><?php echo $animpos_21; ?></td>
			</tr>
		<?php while ($row = mysql_fetch_array($shop->result)) { ?>
			<tr>
				<td class="contenu"><?php echo $row['codeshop'] ?></td>
				<td class="contenu"><?php echo $row['societe']; ?></td>
				<td class="contenu"><?php echo $row['cp']; ?></td>
				<td class="contenu"><?php echo $row['ville']; ?></td>
				<td class="contenu"><?php echo $row['adresse']; ?></td>
				<td class="etiq3" valign="top">
					<input type="checkbox" name="shopselectionsearch[]" value="<?php echo $row['idshop'].'-'; ?>" checked>
				</td>
			</tr>
		<?php } #/## tableau de résultat NEURO 
		?>
			<tr>
				<td class="etiq3"><?php echo $animpos_16; ?></td>
				<td class="etiq3"><?php echo $animpos_17; ?></td>
				<td class="etiq3"><?php echo $animpos_18; ?></td>
				<td class="etiq3"><?php echo $animpos_19; ?></td>
				<td class="etiq3"><?php echo $animpos_20; ?></td>
				<td class="etiq3"><?php echo $animpos_21; ?></td>
			</tr>
			<tr>
				<td align="center" colspan="6">
					<input type="submit" name="action" value="<?php echo $animpos_24; ?>"> 
				</td>
			</tr>
			<tr>
				<td colspan="6">
					<img src="<?php echo $illus; ?>info.png" alt="<?php echo $tool_05h; ?>"> &nbsp; <?php echo $tool_105; ?>
				</td>
			</tr>
		</table>
	</form>
<?php 
	}
	#/## tableau de résultat
	### tableau de ajout
?>
	<font color="#FF0000"><?php echo $animpos_26; ?></font><br>
	<form action="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'add&idwebanimjob='.$idwebanimjob.'&addshop=2&section=addshop';?>" method="post">
		<input type="hidden" name="shopselection" value="<?php echo $shopselection;?>">
		<table class="standard" border="0" cellspacing="1" cellpadding="6" align="center" width="98%">
			<tr>
				<td><label for="societe"><?php echo $vipdetail_0_03; ?> / POS</label> <input type="text" name="societe" id="societe" value="<?php echo $_POST['societe']; ?>"></td>
				<td><label for="cp"><?php echo $contact_11; ?></label> <input type="text" width="10" name="cp" id="cp" value="<?php echo $_POST['cp']; ?>"></td>
				<td><label for="ville"><?php echo $animpos_19; ?></label> <input type="text" name="ville" id="ville" value="<?php echo $_POST['ville']; ?>"></td>
				<td><label for="adresse"><?php echo $animpos_20; ?></label> <input type="text" width="50" name="adresse" id="adresse" value="<?php echo $_POST['adresse']; ?>"></td>
			</tr>
			<tr>
				<td align="center" colspan="5"><input type="submit" name="Rechercher" value="<?php echo $animpos_24; ?>"></td>
			</tr>
		</table>	
  	</form>
<?php
	#/## tableau de ajout
} 
?>

