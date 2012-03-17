<div id="centerzonelarge">
<?php
$secteurs = Conf::read('Secteurs');
## Seule Françoise (idagent 20) peut editer les factures manuelles
if (($_SESSION['idagent'] != '20') and ($_SESSION['roger'] != 'devel')) $frozen = 'disabled';

### Infos Client
echo '<table border="0" width="95%" cellspacing="1" cellpadding="1" align="center" bgcolor="#003333">
<form action="?act=detail" method="post">
	<tr>
		<td bgcolor="#006666"><b>NC</b>: '.$leyear.'-'.$infnc['idfac'].'</td>
		<td bgcolor="#006666"><b>CLIENT</b>: ('.$infnc['idclient'].') '.$infnc['societe'].' ('.$infnc['langue'].')</td>
		<td bgcolor="#006666"><b>DATE</b>: <input type="text" name="datefac" value="'.fdate($infnc['datefac']).'" size="11" '.$frozen.'></td>
		<td bgcolor="#006666"><b>SECTEUR</b>: '.$secteurs[$infnc['secteur']].'</td>
	</tr>
	<tr>
		<td bgcolor="#006666" colspan="2"><b>Intitule</b>: <input type="text" name="intitule" value="'.$infnc['intitule'].'" size="100" '.$frozen.'></td>
		<td bgcolor="#006666"><b>FAC REF</b>: <input type="text" name="facref" value="'.$infnc['facref'].'" size="10" '.$frozen.'></td>
		<td bgcolor="#006666"><input type="hidden" name="idfac" value="'.$infnc['idfac'].'" '.$frozen.'><input type="submit" name="dact" value="Infos" '.$frozen.'></td>
	</tr>

</form>
</table><br>';
?>
	<table class="standard" border="0" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="standard">Poste</th>
			<th class="standard">Units</th>
			<th class="standard">Description</th>
			<th class="standard">Montant Facture</th>
			<th class="standard">Montant</th>
			<th class="standard">Action</th>
		</tr>
<?php
###
if (!empty($_REQUEST['idfac'])) {
	## Check que les poste sont bien dans le bon secteur
	$DB->inline("UPDATE creditdetail d
		LEFT JOIN postescompta pc ON d.poste = pc.poste
		LEFT JOIN postescompta gpc ON pc.nom = gpc.nom AND gpc.secteur = '".$infnc['secteur']."'
	SET d.poste = gpc.poste
	WHERE d.idfac = ".$_REQUEST['idfac']);

	$creditlines = $DB->getArray("SELECT * FROM `creditdetail` WHERE `idfac` = ".$_REQUEST['idfac']." ORDER BY poste ASC");
} 

$Postes = $DB->getArray("SELECT * FROM postescompta WHERE secteur = '".$infnc['secteur']."' ORDER BY poste");

if ($infnc['facref'] > 0) $facref = new facture($infnc['facref']);

$htva = 0;
if (count($creditlines) >= 1) {
	foreach ($creditlines as $row) {
		$MontHTVA += $row['montant'];
	?>
		<form action="?act=detail" method="post">
			<input type="hidden" name="idfac" value="<?php echo $_REQUEST['idfac'];?>">
			<tr>
				<td class="standard">
					<?php 

					echo '<select name="poste"  '.$frozen.'>';
					
					foreach ($Postes as $poste) {
						echo '<option value="'.$poste['poste'].'"'.(($row['poste'] == $poste['poste'])?'selected':'').'>'.$poste['intituleFR'].'</option>';
					}
					
					echo '</select>';
					?>
				</td>
				<td class="standard"><input type="text" size="10" name="units" value="<?php echo fnbr($row['units']); ?>" <?php echo $frozen ?>></td>
				<td class="standard"><input type="text" size="100" name="description" value="<?php echo $row['description']; ?>" <?php echo $frozen ?>></td>
				<td align="right"><?php echo feuro($facref->CompteHoriz[$row['poste']]) ?></td>
				<td class="standard"><input type="text" size="10" name="montant" value="<?php echo feuro($row['montant']); ?>" style="text-align: right;" <?php echo $frozen ?>></td>
				<td class="standard">
					<input type="hidden" name="idman" value="<?php echo $row['idman'];?>"> 
					<input type="submit" name="dact" value="M" <?php echo $frozen ?>>
					<input type="submit" name="dact" value="S" <?php echo $frozen ?>>
				</td>
			</tr>
		</form>
	<?php }
}
 ?>
	<form action="?act=detail" method="post">
		<input type="hidden" name="idfac" value="<?php echo $_REQUEST['idfac'];?>"> 
		<tr id="ajout" bgcolor="#003333">
			<td class="standard">
				<?php 
				echo '<select name="poste" '.$frozen.'><option value="" selected> </option>';
				
				foreach ($Postes as $poste) {
					echo '<option value="'.$poste['poste'].'">'.$poste['intituleFR'].'</option>';
				}
				
				echo '</select>';
				
				
###################################################################################################
## Calcul des montants TVA

	/*
		TODO : standardisation ASTVA
	*/

		switch ($infnc['astva']) {
			case "4": ## Assujeti
			case "7": ## Particulier sans num de TVA
				$MontTVA = round((0.21 * $MontHTVA), 2);
			break;

			case "3": ## Exonéré
			case "5": ## Etranger CEE 
			case "6": ## Etranger Hors CEE
				$MontTVA = 0;
			break;
		}
		
		$MontTTC = $MontHTVA + $MontTVA;
?>
			</td>
			<td class="standard"></td>
			<td class="standard"><input type="text" size="100" name="description" value="" <?php echo $frozen ?>></td>
			<td class="standard"></td>
			<td class="standard"><input type="text" size="10" name="montant" value="" <?php echo $frozen ?>></td>
			<td class="standard"><input type="submit" name="dact" value="Add" <?php echo $frozen ?>></td>
		</tr>
		<tr id="ajout" align="right">
			<td></td>
			<td></td>
			<td></td>
			<td>HTVA</td>
			<td class="standard"><?php echo feuro($MontHTVA); ?></td>
			<td class="standard"></td>
		</tr>
		<tr id="ajout" align="right">
			<td></td>
			<td></td>
			<td></td>
			<td>TVA</td>
			<td class="standard"><?php echo feuro($MontTVA); ?></td>
			<td class="standard"></td>
		</tr>
		<tr id="ajout" align="right">
			<td></td>
			<td></td>
			<td></td>
			<td><b>TTC</b></td>
			<td class="standard"><b><?php echo feuro($MontTTC); ?></b></td>
			<td class="standard"></td>
		</tr>
	</form>
	</table>
</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		// Highlight modified lines
		$("tr input").change(function () { $(this).parents("tr").css({'background-color' : '#FF6600'}); })
	});
</script>