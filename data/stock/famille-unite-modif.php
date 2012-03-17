<?php
# Entete de page
define('NIVO', '../../'); 
$css = 'standard';
include NIVO."includes/ifentete.php" ;

$idstockf = $_REQUEST['idstockf'];
$idstockm = $_REQUEST['idstockm'];

### Actions DB #############
	## Suppression de la ligne #############
		if ($_GET['act'] == 'delete') {
			$del = new db('matos', 'idmatos'); #défini la table et le nom du champ ID
			$del->EFFACE($_POST['idmatos']);	# efface la fiche de cet ID
		echo 'delete';
		}
	## Ajout de la ligne #############
		if ($_GET['act'] == 'add') {

			## Va chercher le prochain num de code matos
				$nreg = new db();
				$nextcode = $nreg->CONFIG('nextcodematos');

			if ($_POST['pack'] == 'yes') { /* pour les packs ==> moulinettes */
				$n = $_POST['nombre'];
				if ($_POST['nombre'] < 1) {$n = 1; }
				$idstockf = $_POST['idstockf'];
				$idstockm = $_POST['idstockm'];
				$situation = $_POST['situation'];
				$complet = $_POST['complet'];
				while ($n > 0) {
					$codematos = $nextcode.'-'.$n;
					$mnom = $_POST['mnom'].'-'.$n;
					$ajoutpack = new db('matos', 'idmatos');
					$ajoutpack->inline("INSERT INTO `matos` (`idstockf` , `idstockm` , `codematos`, `mnom`, `situation`, `complet` ) VALUES ('$idstockf' , '$idstockm' , '$codematos' , '$mnom' , '$situation' , '$complet');");
					$n--;
				}
			} else {
				$ajout = new db('matos', 'idmatos');
				$ajout->AJOUTE(array('idstockf' , 'idstockm' , 'mnom' , 'complet'));
				$PhraseBas = 'Nouveau matos';
				$did = $ajout->addid;
				$idmatos = $ajout->addid;
				$_GET['idmatos'] = $ajout->addid;
				
				$_POST['codematos'] = $nextcode;
				$modif = new db('matos', 'idmatos');
				$modif->MODIFIE($did, array('codematos' ));
			}

			# Incrémente le num de code suivant
				$nextcode++;
				$updreg = new db();
				$updreg->inline("UPDATE `config` SET `vvaleur` = '$nextcode' WHERE `vnom` ='nextcodematos';");
		}
	## Modification de la ligne #############
		if ($_GET['act'] == 'update') {
			$modif = new db('matos', 'idmatos');
			$modif->MODIFIE($_POST['idmatos'], array('codematos' , 'mnom' , 'complet'));	
			echo '<div class="dbaction">Fiche '.$_POST['idmatos'].' : '.$_POST['mnom'].' (code '.$_POST['codematos'].') mise &agrave; jour</div>';
		}
#/## Actions DB #############


$classe = "etiq2";
$classe2 = "standard";
?>

	<table class="<?php echo $classe2; ?>" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
<?php
# Recherche des officers
if (!empty($idstockf)) {
		$stocksort='stm.reference, ma.idstockm, ma.mnom';
		$recherche1='
			SELECT 
			ma.idmatos, ma.idvip, ma.codematos, ma.mnom, ma.dateout AS madateout, ma.autre, ma.idpeople, 
			ma.situation, ma.complet, ma.idstockm, 
			stm.idstockf, stm.reference,
			stf.reference AS reffamille, stf.stype
			FROM matos ma
			LEFT JOIN stockm stm ON ma.idstockm = stm.idstockm 
			LEFT JOIN stockf stf ON stm.idstockf = stf.idstockf 
		';

		$quid = " WHERE stm.idstockf = ".$idstockf;
		
		$recherche='
			'.$recherche1.'
			'.$quid.'
			 ORDER BY '.$stocksort.'
		';
	$matos = new db();
	$matos->inline("$recherche;");

	$FoundCount = mysql_num_rows($matos->result);

}
$i = 0;
$idstockm = 0;
while ($row = mysql_fetch_array($matos->result)) {
$i++;
$j++; # compteur modele
?>
	<?php 
	if ($row['idstockm'] != $idstockm) { $idstockm = $row['idstockm']; $j = 1;
	?>
		<tr>
			<td class="<?php echo $classe; ?>" colspan="9"><font size=+1><?php echo $row['reference'];?></font></td>
		</tr>
		<tr>
			<td class="<?php echo $classe; ?>">#</td>
			<td class="<?php echo $classe; ?>">Code</td>
			<td class="<?php echo $classe; ?>">R&eacute;f&eacute;rence</td>
			<td class="<?php echo $classe; ?>">Complet</td>
			<td class="<?php echo $classe; ?>"></td>
			<td class="<?php echo $classe; ?>" colspan="2">Action</td>
		</tr>
	<form action="?act=add" method="post">
		<input type="hidden" name="idstockf" value="<?php echo $row['idstockf'];?>"> 
		<input type="hidden" name="idstockm" value="<?php echo $row['idstockm'];?>"> 
		<tr id="line<?php echo $i; ?>">
			<td class="<?php echo $classe; ?>"></td>
			<td class="<?php echo $classe2; ?>"></td>
			<td class="<?php echo $classe2; ?>"><input type="text" size="25" name="mnom" value=""></td>
			<td class="<?php echo $classe2; ?>">
				<select name="complet">
					<option value="0">Non</option>
					<option value="1" selected>Oui</option>
					<option value="2">Partiel</option>
				</select>
			</td>
			<td class="<?php echo $classe2; ?>">
				<?php if ($row['type'] == 'pack') { ?>
					Quantit&eacute; <input type="text" size="1" name="nombre" value="1">
					<input type="hidden" name="pack" value="yes">
				<?php } ?>
			</td>
			<td class="<?php echo $classe2; ?>">
				<input type="hidden" name="idmatos" value="<?php echo $row['idmatos'];?>"> 
				<input type="hidden" name="idstockf" value="<?php echo $row['idstockf'];?>"> 
				<input type="submit" name="action" value="A">
			</td>
		</tr>
	</form>

	<?php  $i++;  }/*/ Ligne Modele et titres */?>
	<tr>
		<form action="?idmatos=<?php echo $row['idmatos'] ?>&act=update" method="post">
			<input type="hidden" name="idstockf" value="<?php echo $row['idstockf'];?>"> 
			<input type="hidden" name="idstockm" value="<?php echo $row['idstockm'];?>"> 
			<input type="hidden" name="idmatos" value="<?php echo $row['idmatos'];?>"> 
			<td class="<?php echo $classe; ?>"><?php echo $j; ?></td>
			<td class="<?php echo $classe2; ?>"><input type="text" size="8" name="codematos" value="<?php echo $row['codematos']; ?>"></td>
			<td class="<?php echo $classe2; ?>"><input type="text" size="25" name="mnom" value="<?php echo $row['mnom']; ?>"></td>
			<td class="<?php echo $classe2; ?>">
				<?php 
				echo '<select name="complet">';
					echo '<option value="0"';if ($row['complet'] == '0') {echo 'selected';} echo '>Non</option>';
					echo '<option value="1"';if ($row['complet'] == '1') {echo 'selected';} echo '>Oui</option>';
					echo '<option value="2"';if ($row['complet'] == '2') {echo 'selected';} echo '>Partiel</option>';
				echo '</select>';
				?>
			</td>
			<td class="<?php echo $classe2; ?>"></td>
			<td class="<?php echo $classe2; ?>">
				<input type="submit" name="action" value="M">
			</td>
		</form>
		<td class="<?php echo $classe2; ?>">
			<?php if (($_SESSION['roger'] == 'devel') or ($_SESSION['roger'] == 'admin')) { ?>
				<form action="?idmatos=<?php echo $row['idmatos'] ?>&act=delete" onClick="return confirm('Etes-vous sur de vouloir effacer ce mat&eacute;riel?')" method="post">
					<input type="hidden" name="idstockf" value="<?php echo $row['idstockf'];?>"> 
					<input type="hidden" name="idstockm" value="<?php echo $row['idstockm'];?>"> 
					<input type="hidden" name="idmatos" value="<?php echo $row['idmatos'];?>"> 
					<input type="submit" name="action" value="S">
				</form>
			</td>
		<?php } ?>
	</tr>
<?php } ?>
</table>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		// Highlight modified lines
		$("tr input").change(function () { $(this).parents('tr').css({'background-color' : '#FF6600'}); })
	});
</script>	
<?php include NIVO."includes/ifpied.php"; ?>