<?php
# Entete de page
define('NIVO', '../../'); 

include NIVO."includes/ifentete.php" ;

### Actions DB #############
	## Suppression de la ligne #############
	if ($_POST['action'] == 'S') {
		### Verif
			$detailmatos = $DB->getArray("SELECT * FROM `matos` WHERE `idstockm` = ".$_REQUEST['idstockm']);

			if (count($detailmatos) < 1) { # pas de delete si modele non vide
				$DB->inline("DELETE FROM stockm WHERE idstockm = ".$_REQUEST['idstockm']);
			} else {
				echo '<div class="dbaction">Fiche '.$_REQUEST['idstockm'].' non vide</div><br>';
			}
		#/## Verif	
	}
	
	## Ajout de la ligne #############
	if ($_POST['action'] == 'Add') {
		$ajout = new db('stockm', 'idstockm');
		$ajout->AJOUTE(array('idstockf' , 'reference' , 'description'));	
	}
	## Modification de la ligne #############
	if ($_POST['action'] == 'M') {
		$modif = new db('stockm', 'idstockm');
		$modif->MODIFIE($_REQUEST['idstockm'], array('reference' , 'description'));	
		echo '<div class="dbaction">Fiche '.$_REQUEST['idstockm'].' mise &agrave; jour</div><br>';
	}
#/## Actions DB #############

$classe = "etiq2";
$classe2 = "standard";
?>

<table class="<?php echo $classe2; ?>" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
	<?php
	# Recherche des Modeles
	if (!empty($_REQUEST['idstockf'])) {
			$stocksort='stm.reference';
			$recherche1='
				SELECT 
				stm.idstockm, stm.idstockf, stm.reference, stm.description, 
				stf.reference AS reffamille, stf.stype
				FROM stockm stm
				LEFT JOIN stockf stf ON stm.idstockf = stf.idstockf 
			';
			$quid = " WHERE stm.idstockf = ".$_REQUEST['idstockf'];
			$recherche='
				'.$recherche1.'
				'.$quid.'
				 ORDER BY '.$stocksort.'
			';
		$matos = new db();
		$matos->inline("$recherche;");
		$FoundCount = mysql_num_rows($matos->result);
	#	echo $recherche;
	}
	$i = 0;
	$_REQUEST['idstockm'] = 0;
	while ($row = mysql_fetch_array($matos->result)) {
	?>
		<?php /* Ligne Famille et titres */ if ($i == 0) {?>
			<tr>
				<td class="<?php echo $classe; ?>">#</td>
				<td class="<?php echo $classe; ?>">ID</td>
				<td class="<?php echo $classe; ?>">R&eacute;f&eacute;rence</td>
				<td class="<?php echo $classe; ?>">Description</td>
				<td class="<?php echo $classe; ?>">Action</td>
			</tr>
			<form action="?act=add-modele" method="post">
				<input type="hidden" name="idstockf" value="<?php echo $_REQUEST['idstockf'];?>"> 
				<input type="hidden" name="idstockm" value="<?php echo $row['idstockm'];?>"> 
				<tr>
					<td class="<?php echo $classe; ?>"></td>
					<td class="<?php echo $classe2; ?>"></td>
					<td class="<?php echo $classe2; ?>"><input type="text" size="25" name="reference" value=""></td>
					<td class="<?php echo $classe2; ?>"><input type="text" size="50" name="description" value=""></td>
					<td class="<?php echo $classe2; ?>">
						<input type="submit" name="action" value="Add">
					</td>
				</tr>
			</form>
		<?php 
		} /* Ligne Famille et titres */
		$i++;
		?>
		<form action="?act=modif-modele" method="post">
			<input type="hidden" name="idstockf" value="<?php echo $_REQUEST['idstockf'];?>"> 
			<input type="hidden" name="idstockm" value="<?php echo $row['idstockm'];?>"> 
			<tr>
				<td class="<?php echo $classe; ?>"><?php echo $i; ?></td>
				<td class="<?php echo $classe2; ?>"><?php echo $row['idstockm']; ?></td>
				<td class="<?php echo $classe2; ?>"><input type="text" size="25" name="reference" value="<?php echo $row['reference']; ?>"></td>
				<td class="<?php echo $classe2; ?>"><input type="text" size="50" name="description" value="<?php echo $row['description']; ?>"></td>
				<td class="<?php echo $classe2; ?>">
					<input type="submit" name="action" value="M">
					<?php if (($_SESSION['roger'] == 'devel') or ($_SESSION['roger'] == 'admin')) { ?>
						<input type="submit" name="action" value="S">
					<?php } ?>
				</td>
			</tr>
		</form>
	<?php 
	} 
	$i++;
	?>
	<form action="?act=add-modele" method="post">
		<input type="hidden" name="idstockf" value="<?php echo $_REQUEST['idstockf'] ;?>"> 
		<input type="hidden" name="idstockm" value="<?php echo $row['idstockm'];?>"> 
		<tr>
			<td class="<?php echo $classe; ?>"></td>
			<td class="<?php echo $classe2; ?>"></td>
			<td class="<?php echo $classe2; ?>"><input type="text" size="25" name="reference" value=""></td>
			<td class="<?php echo $classe2; ?>"><input type="text" size="50" name="description" value=""></td>
			<td class="<?php echo $classe2; ?>">
				<input type="submit" name="action" value="Add">
			</td>
		</tr>
	</form>
</table>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		// Highlight modified lines
		$("tr input").change(function () { $(this).parents("tr").css({'background-color' : '#FF6600'}); })
	});
</script>
<?php include NIVO."includes/ifpied.php"; ?>