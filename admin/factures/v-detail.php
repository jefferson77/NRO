<div id="centerzonelarge">
<?php
## get datas
if ($fac->idclient > 0) $officers = $DB->getArray("SELECT `idcofficer` , `oprenom` , `onom` FROM `cofficer` WHERE `idclient` = ".$fac->idclient.' ORDER BY onom');
$agents = $DB->getArray("SELECT `idagent` , `prenom` , `nom` FROM `agent` WHERE `isout` = 'N'");
$secteurs = Conf::read('Secteurs');

## Seule Françoise (idagent 20) peut editer les factures manuelles
if (($_SESSION['idagent'] != '20') and ($_SESSION['roger'] != 'devel')) $frozen = 'disabled';
## Les factures envoyées en Compta sont bloquées pour tt le monde (meme françoise)
if ($fac->horizetat == 'Y') {
	$frozen = 'disabled';
	$comptawarning = '<tr><td colspan="4" style="background-color: #F00; color: #FFF;font-weight: bold; text-align: center;padding: 5px; ">Cette facture a déjà été importée en compta, elle n\'est donc plus modifiable</td></tr>';
} 

### Infos Client
echo '<form action="?act=detail" method="post">
<table border="0" width="95%" cellspacing="1" cellpadding="1" align="center" bgcolor="#003333">
	'.(string)$comptawarning.'
	<tr>
		<td bgcolor="#006666"><b>FAC</b>: '.$fac->leyear.'-'.$fac->id.'</td>
		<td bgcolor="#006666"><b>CLIENT</b>: <input type="text" name="idclient" value="'.$fac->idclient.'" size="5" '.$frozen.'>'.$fac->societe;
		if (!empty($fac->idclient)) {
		
			## Liste des Officers
			echo '<select name="idcofficer" '.$frozen.'>';
			foreach ($officers as $row) echo '<option value="'.$row['idcofficer'].'"'.(($fac->nom == $row['oprenom'].' '.$row['onom'])?' selected':'').'>'.$row['oprenom'].' '.$row['onom'].'</option>';
			echo '</select>';
		}
		echo '</td>
		<td bgcolor="#006666"><b>DATE</b>: <input type="text" name="datefac" value="'.fdate($fac->datefac).'" size="11" '.$frozen.'></td>
		<td bgcolor="#006666"><b>SECTEUR</b>: '; 
		if($fac->modefac == 'A') {
			echo $secteurs[$fac->horizsecteur];
		} else {
			foreach ($secteurs as $code => $secteur) {
				echo '<input type="radio" name="secteur" value="'.$code.'" '.(($fac->horizsecteur == $code)?'checked':'').' '.$frozen.'> '.$secteur.' ';
			}
		}

		echo '</td>
	</tr>
	<tr>
		<td bgcolor="#006666"><b>PO</b>: <input type="text" name="po" value="'.$fac->boncommande.'" size="20"></td>
		<td bgcolor="#006666"><b>Intitule</b>: <input type="text" name="intitule" value="'.$fac->intitule.'" size="60" '.$frozen.'></td>
		<td bgcolor="#006666" colspan="2"><b>Agent</b>: <select name="idagent" '.$frozen.'>';
		## Liste des Agents
		foreach ($agents as $row) echo '<option value="'.$row['idagent'].'"'.(($fac->agent == $row['prenom'].' '.$row['nom'])?' selected':'').'>'.$row['prenom'].' '.$row['nom'].'</option>';
		echo '</select></td>
	</tr>
	<tr>
		 <td colspan="3"><b>Mode de facturation </b>: 
			<input type="radio" name="modefac" value="A" '; if ($fac->modefac == 'A') { echo 'checked';} echo' '.$frozen.'> Automatique &nbsp; &nbsp;
			<input type="radio" name="modefac" value="M" '; if ($fac->modefac == 'M') { echo 'checked';} echo' '.$frozen.'> Manuel
		</td>
		<td><input type="hidden" name="idfac" value="'.$fac->id.'"><input type="submit" name="dact" value="Infos"></td>
	</tr>

</form>
</table><br>';
### Tarifs
echo '<table border="0" width="95%" cellspacing="1" cellpadding="1" align="center" bgcolor="#003333">
<form action="?act=detail" method="post">';
	switch ($fac->horizsecteur) {
	
		case "1": 
			echo '
			<tr>
				<td bgcolor="#006666"><b>- de 6</b>: <input type="text" name="tarif01" value="'.fnbr($fac->Tarif['high']).'" size="5" '.$frozen.'> &euro;</td>
				<td bgcolor="#006666"><b>6 et +</b>: <input type="text" name="tarif02" value="'.fnbr($fac->Tarif['low']).'" size="5" '.$frozen.'> &euro;</td>
				<td bgcolor="#006666"><b>Nuit</b>: <input type="text" name="tarif03" value="'.fnbr($fac->Tarif['night']).'" size="5" '.$frozen.'> &euro;</td>
				<td bgcolor="#006666"><b>Km</b>: <input type="text" name="tarif04" value="'.fnbr($fac->Tarif['km']).'" size="5" '.$frozen.'> Km</td>
			</tr>
			<tr>
				<td bgcolor="#006666"><b>150%</b>: <input type="text" name="tarif05" value="'.fnbr($fac->Tarif['150']).'" size="5" '.$frozen.'> &euro;</td>
				<td bgcolor="#006666"><b>Forfait</b>: <input type="text" name="tarif06" value="'.fnbr($fac->Tarif['forfait']).'" size="5" '.$frozen.'> &euro;</td>
				<td bgcolor="#006666"><b>Forfait</b>: 
					<input type="radio" name="hforfait" value="2" '.(($fac->Tarif['hforfait'] == 2)?' checked':'').' '.$frozen.'> Oui
					<input type="radio" name="hforfait" value="1" '.(($fac->Tarif['hforfait'] != 2)?' checked':'').' '.$frozen.'> Non
				</td>
				<td bgcolor="#006666"><b>Dimona</b>: 
					<input type="radio" name="fraisdimona" value="oui" '.(($fac->Tarif['fraisdimona'] == 'oui')?' checked':'').' '.$frozen.'> Oui
					<input type="radio" name="fraisdimona" value="non" '.(($fac->Tarif['fraisdimona'] != 'oui')?' checked':'').' '.$frozen.'> Non
				</td>
			</tr>';
			$colspan="4";
			
		break;
	
		case "2": 
			echo '
			<tr>
				<td bgcolor="#006666"><b>Heures</b>: <input type="text" name="tarif01" value="'.fnbr($fac->Tarif['heure']).'" size="5" '.$frozen.'> &euro;</td>
				<td bgcolor="#006666"><b>Km</b>: <input type="text" name="tarif02" value="'.fnbr($fac->Tarif['km']).'" size="5" '.$frozen.'> &euro;</td>
				<td bgcolor="#006666"><b>Forfait</b>: <input type="text" name="tarif03" value="'.fnbr($fac->Tarif['forf']).'" size="5" '.$frozen.'> &euro;</td>
				<td bgcolor="#006666"><b>Km Forf</b>: <input type="text" name="tarif04" value="'.fnbr($fac->Tarif['forfkm']).'" size="5" '.$frozen.'> Km</td>
				<td bgcolor="#006666"><b>Stand</b>: <input type="text" name="tarif05" value="'.fnbr($fac->Tarif['stand']).'" size="5" '.$frozen.'> &euro;</td>
				<td bgcolor="#006666"><b>Forfait</b>: 
					<input type="radio" name="hforfait" value="2" '.(($fac->Tarif['hforfait'] == 2)?' checked':'').' '.$frozen.'> Oui
					<input type="radio" name="hforfait" value="1" '.(($fac->Tarif['hforfait'] != 2)?' checked':'').' '.$frozen.'> Non
				</td>
				<td bgcolor="#006666"><b>Dimona</b>: 
					<input type="radio" name="fraisdimona" value="oui" '.(($fac->Tarif['fraisdimona'] == 'oui')?' checked':'').' '.$frozen.'> Oui
					<input type="radio" name="fraisdimona" value="non" '.(($fac->Tarif['fraisdimona'] != 'oui')?' checked':'').' '.$frozen.'> Non
				</td>
			</tr>';
			$colspan="7";
		break;
		
		case "3": 
		case "4": 
			echo '
			<tr>
				<td bgcolor="#006666"><b>Heures</b>: <input type="text" name="tarif01" value="'.fnbr($fac->Tarif['heure']).'" size="5" '.$frozen.'> &euro;</td>
				<td bgcolor="#006666"><b>Km</b>: <input type="text" name="tarif02" value="'.fnbr($fac->Tarif['km']).'" size="5" '.$frozen.'> &euro;</td>
				<td bgcolor="#006666"><b>150 %</b>: <input type="text" name="tarif03" value="'.fnbr($fac->Tarif['150']).'" size="5" '.$frozen.'> &euro;</td>
				<td bgcolor="#006666"><b>Forfait</b>: 
					<input type="radio" name="hforfait" value="2" '.(($fac->Tarif['hforfait'] == 2)?' checked':'').' '.$frozen.'> Oui
					<input type="radio" name="hforfait" value="1" '.(($fac->Tarif['hforfait'] != 2)?' checked':'').' '.$frozen.'> Non
				</td>
				<td bgcolor="#006666"><b>Dimona</b>: 
					<input type="radio" name="fraisdimona" value="oui" '.(($fac->Tarif['fraisdimona'] == 'oui')?' checked':'').' '.$frozen.'> Oui
					<input type="radio" name="fraisdimona" value="non" '.(($fac->Tarif['fraisdimona'] != 'oui')?' checked':'').' '.$frozen.'> Non
				</td>
			</tr>';
			
			$colspan="5";
		break;
}

		
echo '
	<tr>
		<td colspan="'.$colspan.'" bgcolor="#006666" align="center"><input type="hidden" name="idfac" value="'.$fac->id.'" '.$frozen.'><input type="submit" name="dact" value="Tarifs" '.$frozen.'></td>
	</tr>
</form>
</table><br>';
?>

<?php if ($fac->modefac == 'M') { 

########################################################################################################################################################
###########  Factures Manuelles  ########################################################################################################
####
?>
	<table class="standard" border="0" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="standard">Poste</th>
			<th class="standard">Unites</th>
			<th class="standard">Description</th>
			<th class="standard">Montant</th>
			<th class="standard">Action</th>
		</tr>
<?php
###
if (!empty($_REQUEST['idfac'])) {
	## Check que les poste sont bien dans le bon secteur
	$DB->inline("UPDATE facmanuel d
		LEFT JOIN postescompta pc ON d.poste = pc.poste
		LEFT JOIN postescompta gpc ON pc.nom = gpc.nom AND gpc.secteur = ".$fac->horizsecteur."
	SET d.poste = gpc.poste
	WHERE d.idfac = ".$_REQUEST['idfac']);
	
	## Get details
	$facdetails = $DB->getArray("SELECT
			d.*, 
			pc.secteur 
		FROM facmanuel d 
			LEFT JOIN postescompta pc ON d.poste = pc.poste 
		WHERE d.idfac = ".$_REQUEST['idfac']." 
		ORDER BY d.poste ASC");
}

$Postes = $DB->getArray("SELECT * FROM postescompta WHERE secteur = ".$fac->horizsecteur." ORDER BY poste");

$htva = 0;
if (count($facdetails) >= 1) {
	foreach ($facdetails as $row) {
?>
		<form action="?act=detail" method="post">
			<input type="hidden" name="idfac" value="<?php echo $_REQUEST['idfac'];?>"> 
			<tr>
				<td class="standard">
					<?php 
					echo '<select name="poste" '.$frozen.'>';

					foreach ($Postes as $poste) {
						echo '<option value="'.$poste['poste'].'"'.(($row['poste'] == $poste['poste'])?'selected':'').'>'.$poste['intituleFR'].'</option>';
					}
					
					echo '</select>';
					?>
				</td>
				<td class="standard"><input type="text" size="10" name="units" value="<?php echo fnbr($row['units']); ?>" <?php echo $frozen; ?>></td>
				<td class="standard"><input type="text" size="100" name="description" value="<?php echo $row['description']; ?>" <?php echo $frozen; ?>></td>
				<td class="standard"><input type="text" size="10" name="montant" value="<?php echo feuro($row['montant']); ?>" <?php echo $frozen; ?>></td>
				<td class="standard">
					<input type="hidden" name="idman" value="<?php echo $row['idman'];?>"> 
					<input type="submit" name="dact" value="M"<?php echo $frozen; ?>>
					<input type="submit" name="dact" value="S"<?php echo $frozen; ?>>
				</td>
			</tr>
		</form>
	<?php }
}

if (($fac->horizetat != 'Y') and ($frozen != 'disabled')) {
 ?>
	<form action="?act=detail" method="post">
		<input type="hidden" name="idfac" value="<?php echo $_REQUEST['idfac'];?>"> 
		<tr id="ajout" bgcolor="#003333">
			<td class="standard">
				<?php 

				echo '<select name="poste"><option value="" selected> </option>';

				foreach ($Postes as $poste) {
					echo '<option value="'.$poste['poste'].'">'.$poste['intituleFR'].'</option>';
				}
				echo '</select>';
				?>
			</td>
			<td class="standard"><input type="text" size="10" name="units" value=""></td>
			<td class="standard"><input type="text" size="100" name="description" value=""></td>
			<td class="standard"><input type="text" size="10" name="montant" value=""></td>
			<td class="standard"><input type="submit" name="dact" value="Add"></td>
		</tr>
<?php } ?>
<!-- Totaux -->
		<tr id="ajout" align="right">
			<td></td>
			<td>HTVA</td>
			<td class="standard"><?php echo fpeuro($fac->MontHTVA); ?></td>
			<td class="standard"></td>
		</tr>
		<tr id="ajout" align="right">
			<td></td>
			<td>TVA</td>
			<td class="standard"><?php echo fpeuro($fac->MontTVA); ?></td>
			<td class="standard"></td>
		</tr>
		<tr id="ajout" align="right">
			<td></td>
			<td><b>TTC</b></td>
			<td class="standard"><b><?php echo fpeuro($fac->MontTTC); ?></b></td>
			<td class="standard"></td>
		</tr>
	</form>
	</table>
</div>
<div id="infobouton">
	<form action="?act=detail" method="post">
		Renvoie de la facture en mode <font color="#CC0000">automatique</font>
		&nbsp;
		<input type="hidden" name="idfac" value="<?php echo $fac->id; ?>">
		<input type="submit" name="mact" value="AUTO">
	</form>
</div>
<?php } else {
########################################################################################################################################################
###########  Factures Automatiques  ########################################################################################################
####
?>
	<table class="standard" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="standard">Poste</th>
			<th class="standard">Detail</th>
			<th class="standard">Montant</th>
		</tr>
<?php 

switch ($fac->horizsecteur) {
	#### VIP ####
	case "1": 
		echo '
		<tr>
			<td class="standard">Prestations</td>
			<td class="standard">'.$fac->Detail['prestations'].'</td>
			<td class="standard">'.fpeuro($fac->MontPrest).'</td>
		</tr>
		<tr>
			<td class="standard">D&#233;placements</td>
			<td class="standard">'.$fac->Detail['deplacements'].'</td>
			<td class="standard">'.fpeuro($fac->MontDepl).'</td>
		</tr>
		<tr>
			<td class="standard">Locations</td>
			<td class="standard">'.$fac->noteloca.'</td>
			<td class="standard">'.fpeuro($fac->MontLoc).'</td>
		</tr>
		<tr>
			<td class="standard">Frais</td>
			<td class="standard">'.$fac->notefrais.'</td>
			<td class="standard">'.fpeuro($fac->MontFrais).'</td>
		</tr>
';
	break;
	#### ANIM ####
	case "2": 
		/*
			TODO : Detail des facs ANIM
		*/
	break;
	#### MERCH ####
	case "3": 
	case "4": 
		/*
			TODO : Detail des facs MERCH
		*/
	break;
}
?>		
		<tr id="ajout" align="right">
			<td></td>
			<td>HTVA</td>
			<td class="standard"><?php echo fpeuro($fac->MontHTVA); ?></td>
		</tr>
		<tr id="ajout" align="right">
			<td></td>
			<td>TVA</td>
			<td class="standard"><?php echo fpeuro($fac->MontTVA); ?></td>
		</tr>
		<tr id="ajout" align="right">
			<td></td>
			<td><b>TTC</b></td>
			<td class="standard"><b><?php echo fpeuro($fac->MontTTC); ?></b></td>
		</tr>
	</form>
	</table>
</div>
<?php } ?>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		// Highlight modified lines
		$("tr input").change(function () { $(this).parents('tr').css({'background-color' : '#FF6600'}); })
	});
</script>