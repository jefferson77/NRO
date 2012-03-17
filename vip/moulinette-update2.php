<?php 
/*
	TODO Rewrite page
*/
$idvipjob = $_GET['idvipjob'];

	$classe = "planning" ; 

			foreach($_POST['idtoupdate'] as $idvipz) {
				$i++;
				if ($i == 1) { $idvip = $idvipz; }
				$idtoupdate .= $idvipz."%";
			}

	$listing = new db();
	if($_GET['etat'] == '0') {
		$sql = "SELECT v . * , s.codeshop, s.societe
				FROM vipdevis v
				";
	} else {
		$sql = "SELECT v . * , s.codeshop
				FROM vipmission v
				";
	}
		$sql .= "LEFT JOIN shop s ON v.idshop = s.idshop
				WHERE v.idvip = ".$idvip."
				ORDER BY s.codeshop, v.vipdate, v.vipactivite";
		$listing->inline($sql);

# Recherche des résultats

$FoundCount = mysql_num_rows($listing->result); 
?>
<div id="minicentervipwhite">
<br>
	<table class="<?php echo $classe; ?>" border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<td class="etiq2" colspan="21" align="center"><b>Update Missions - S&eacute;l&eacute;ction des champs (pour <?php echo $i;?> missions)</b></td>
		</tr>
		<?php $classe = "planning" ; ?>
		<tr>
			<td colspan="5">&nbsp;</td>
			<th class="vip2" colspan="8">Prestations</th>
			<th class="vip2" colspan="2">D&eacute;placements</th>
			<th class="vip2" colspan="2">Location</th>
			<th class="vip2" colspan="4">Frais</th>
		</tr>
		<tr>
			<th class="vip2">N&deg;</th>
			<th class="vip2">Date </th>
			<th class="vip2" colspan="2">Activit&eacute;</th>
			<th class="vip2">Sexe</th>
			<th class="vip2">IN</th>
			<th class="vip2">OUT</th>
			<th class="vip2">Brk</th>
			<th class="vip2">Night</th>
			<th class="vip2">Sup</th>
			<th class="vip2">200%</th>
			<th class="vip2">TS</th>
			<th class="vip2">Fts</th>
			<th class="vip2">KM</th>
			<th class="vip2">Fkm</th>
			<th class="vip2">Unif.</th>
			<th class="vip2">Loc</th>
			<th class="vip2">Cat F</th>
			<th class="vip2">Cat P</th>
			<th class="vip2">Disp F</th>
			<th class="vip2">Disp P</th>
		</tr>
	<?php
	$xpnom = '';
	while ($row = mysql_fetch_array($listing->result)) { 
	?>
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=moulinetteupdate3&down='.$down.'&etat='.$_GET['etat'].'&idvipjob='.$row['idvipjob'].'&action=skip';?>" method="post" name="CelsysForm" onsubmit="return scanForm(this)">
			<input type="hidden" name="idvipjob" value="<?php echo $row['idvipjob'] ;?>"> 
			<input type="hidden" name="idtoupdate" value="<?php echo $idtoupdate ;?>"> 
<?php 
			## Calcul des Montants ####################
			$fich = new corevip ($row['idvip']);

			$MontPrest = $MontPrest + ($fich->hlow * $tar['tvheure6']) + ($fich->hhigh * $tar['tvheure05']) + ($fich->hnight * $tar['tvnight']) + ($fich->h150 * $tar['tv150']) + ($fich->hspec * $row['ts']); 
			$MontDepl = $MontDepl + ($row['km'] * $tar['tvkm']) + $row['fkm'];
			$MontLoc = $MontLoc + $row['unif'] + $row['net'] + $row['loc1'] + $row['loc2'] ;
			$MontFrais = $MontFrais + $row['cat'] + $row['disp'] + $fich->MontNfrais;
						
	#> Changement de couleur des lignes #####>>####
	$i++;
	$j++;
	
	if ($row['h200'] == 1) {
		echo '<tr bgcolor="#FFFF99">';
	} else {
		if (fmod($i, 2) == 1) {
			echo '<tr bgcolor="#9CBECA">';
		} else {
			echo '<tr bgcolor="#8CAAB5">';
		}
	}
	
	#< Changement de couleur des lignes #####<<####
?>
				<th class="vip2" align="center"><?php echo $j;?></th>
				<td class="<?php echo $classe; ?>"><input type="text" size="9" name="vipdate" value="<?php echo fdate($row['vipdate']); ?>" <?php echo $disable; ?>></td>
				<td class="<?php echo $classe; ?>"><input type="text" name="vipactivite" value="<?php echo $row['vipactivite']; ?>"></td>
				<td class="<?php echo $classe; ?>">
					<select class="mini" name="vipactivite1" size="1">
						<option value=""></option>
						<option value="<?php echo $vipactivite_1; ?>"><?php echo substr($vipactivite_1, 0, 11); ?></option>
						<option value="<?php echo $vipactivite_2; ?>"><?php echo substr($vipactivite_2, 0, 11); ?></option>
						<option value="<?php echo $vipactivite_3; ?>"><?php echo substr($vipactivite_3, 0, 11); ?></option>
						<option value="<?php echo $vipactivite_4; ?>"><?php echo substr($vipactivite_4, 0, 11); ?></option>
						<option value="<?php echo $vipactivite_5; ?>"><?php echo substr($vipactivite_5, 0, 11); ?></option>
						<option value="<?php echo $vipactivite_6; ?>"><?php echo substr($vipactivite_6, 0, 11); ?></option>
						<option value="<?php echo $vipactivite_7; ?>"><?php echo substr($vipactivite_7, 0, 11); ?></option>
						<option value="<?php echo $vipactivite_17; ?>"><?php echo substr($vipactivite_17, 0, 11); ?></option>
						<option value="<?php echo $vipactivite_8; ?>"><?php echo substr($vipactivite_8, 0, 11); ?></option>
						<option value="<?php echo $vipactivite_9; ?>"><?php echo substr($vipactivite_9, 0, 11); ?></option>
						<option value="<?php echo $vipactivite_10; ?>"><?php echo substr($vipactivite_10, 0, 11); ?></option>
						<option value="<?php echo $vipactivite_11; ?>"><?php echo substr($vipactivite_11, 0, 11); ?></option>
						<option value="<?php echo $vipactivite_12; ?>"><?php echo substr($vipactivite_12, 0, 11); ?></option>
						<option value="<?php echo $vipactivite_13; ?>"><?php echo substr($vipactivite_13, 0, 11); ?></option>
						<option value="<?php echo $vipactivite_14; ?>"><?php echo substr($vipactivite_14, 0, 11); ?></option>
						<option value="<?php echo $vipactivite_15; ?>"><?php echo substr($vipactivite_15, 0, 11); ?></option>
						<option value="<?php echo $vipactivite_16; ?>"><?php echo substr($vipactivite_16, 0, 11); ?></option>
					</select>
				</td>
				<td class="<?php echo $classe; ?>">
					<select class="mini" name="sexe" size="1">
						<option value="f" <?php if ($row['sexe'] == 'f') {echo 'selected';} ?>>F</option>
						<option value="m" <?php if ($row['sexe'] == 'm') {echo 'selected';} ?>>M</option>
						<option value="x" <?php if ($row['sexe'] == 'x') {echo 'selected';} ?>>X</option>
					</select>							
				</td>
				<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="vipin" value="<?php echo ftime($row['vipin']); ?>"></td>
				<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="vipout" value="<?php echo ftime($row['vipout']); ?>"></td>
				<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="brk" value="<?php echo fnbr($row['brk']); ?>"></td>
				<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="night" value="<?php echo fnbr($row['night']); ?>"></td>
				<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="h150" value="<?php echo fnbr($row['h150']); ?>"></td>
				<td class="<?php echo $classe; ?>"><input class="mini" type="checkbox" name="h200" value="1" <?php echo ($row['h200'] == 1)?' checked':''; ?>></td>
				<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="ts" value="<?php echo fnbr($row['ts']); ?>"></td>
				<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="fts" value="<?php echo fnbr($row['fts']); ?>"></td>
				<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="km" value="<?php echo fnbr($row['km']); ?>"></td>
				<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="fkm" value="<?php echo fnbr($row['fkm']); ?>"></td>
				<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="unif" value="<?php echo fnbr($row['unif']); ?>"></td>
				<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="loc1" value="<?php echo fnbr($row['loc1']); ?>"></td>
				<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="cat" value="<?php echo fnbr($row['cat']); ?>"></td>
				<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="vcat" value="<?php echo fnbr($row['vcat']); ?>"></td>
				<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="disp" value="<?php echo fnbr($row['disp']); ?>"></td>
				<td class="<?php echo $classe; ?>"><input class="mini" type="text" size="3" name="vdisp" value="<?php echo fnbr($row['vdisp']); ?>"></td>
			</tr>
			<?php $classe = "contenu" ; ?>
			<tr>
				<td></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="vipdate"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="vipactivite"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="vipactivite1"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="sexe"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="vipin"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="vipout"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="brk"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="night"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="h150"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="h200"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="ts"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="fts"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="km"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="fkm"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="unif"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="loc1"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="cat"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="vcat"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="disp"></td>
				<td class="<?php echo $classe; ?>" align="center"><input type="checkbox" name="fieldtoupdate[]" value="vdisp"></td>
			</tr>
			<tr>
				<td class="etiq2" colspan="21" align="center"><input type="submit" name="Modifier" value="Mettre &agrave; jour"></td>
			</tr>

		</form>
	<?php } ?>
	</table>
</div>
