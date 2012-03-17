<?php 
if (!isset($_GET['selectall'])) $_GET['selectall'] = '';
?>
<div id="miniinfozone">
<form action="?act=moulinetteupdate2&idvipjob=<?php echo $_GET['idvipjob'].'&down=down&etat='.$_GET['etat']; ?>" method="post">
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="99%">
		<tr>
			<td class="etiq2" colspan="12" align="center"><b>Update Missions - S&eacute;l&eacute;ction des missions <?php echo $_GET['etat']; ?></b></td>
			<td class="contenu" colspan="3" align="center"><a href="?act=moulinetteupdate1&idvipjob=<?php echo $_SERVER['PHP_SELF'].''.$_GET['idvipjob'].'&down=down&etat='.$_GET['etat'].'&selectall=checked'; ?>">TOUS</a></td>
			<td class="contenu" colspan="3" align="center"><a href="?act=moulinetteupdate1&idvipjob=<?php echo $_SERVER['PHP_SELF'].''.$_GET['idvipjob'].'&down=down&etat='.$_GET['etat'].'&selectall=no'; ?>">AUCUN</a></td>
			<td class="etiq2" colspan="3" align="center"><input type="submit" name="Modifier" value="Continuer"></td>
		</tr>
		<tr>
			<td colspan="5">&nbsp;</td>
			<th class="vip2" colspan="7">Prestations</th>
			<th class="vip2" colspan="2">D&eacute;placements</th>
			<th class="vip2" colspan="2">Location</th>
			<th class="vip2" colspan="4">Frais</th>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th class="vip2">N&deg;</th>
			<th class="vip2">Date </th>
			<th class="vip2">Lieu</th>
			<th class="vip2">Activit&eacute;</th>
			<th class="vip2">Sexe</th>
			<th class="vip2">IN</th>
			<th class="vip2">OUT</th>
			<th class="vip2">Brk</th>
			<th class="vip2">Night</th>
			<th class="vip2">Sup</th>
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
			<td>&nbsp;</td>
		</tr>
<?php
if (!empty($_GET['idvipjob'])) {
	# Recherche des résultats
	$vip = new db();
	if($_GET['etat'] == '0') {
		$sql = "SELECT v.* , s.codeshop, s.societe
				FROM vipdevis v
				";
	} else {
		$sql = "SELECT v.* , s.codeshop, s.societe
				FROM vipmission v
				";
	}
		$sql .= "LEFT JOIN shop s ON v.idshop = s.idshop
				WHERE v.idvipjob = ".$_GET['idvipjob']."
				ORDER BY s.codeshop, v.vipdate, v.vipactivite";
		$vip->inline($sql);
	# Recherche des Tarifs
	$tarif = new db();
	$tarif->inline("SELECT c.tvheure05 ,c.tvheure6 ,c.tvnight ,c.tv150 ,c.tvkm FROM vipjob v LEFT JOIN client c ON v.idclient = c.idclient WHERE `idvipjob` = ".$_GET['idvipjob']);
	$tar = mysql_fetch_array($tarif->result);

	$i = 0; # Pour les couleurs alternées
	$j = 0;
	
	$MontPrest = 0;
	$MontDepl = 0;
	$MontLoc = 0;
	$MontFrais = 0;
	
	$compt = 0;
	
	while ($row = mysql_fetch_array($vip->result)) { 
	
			## Calcul des Montants ####################
			$fich = new corevip ($row['idvip']);

			$MontPrest += $fich->MontPrest; 
			$MontDepl += $fich->MontDepl;
			$MontLoc += $fich->MontLoc;
			$MontFrais += $fich->MontFrais;
						
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
			<td align="center"><?php echo fdate($row['vipdate']) ?></td>
			<td align="center"><?php echo $row['codeshop'].' - '.$row['societe']; ?></td>
			<td align="center"><?php echo $row['vipactivite']; ?></td>
			<td align="center"><?php echo $row['sexe'] ?></td>
			<td align="center"><?php echo ftime($row['vipin']) ?></td>
			<td align="center"><?php echo ftime($row['vipout']) ?></td>
			<td align="center"><?php echo fnbr($row['brk']) ?></td>
			<td align="center"><?php echo fnbr($row['night']) ?></td>
			<td align="center"><?php echo fnbr($row['h150']) ?></td>
			<td align="center"><?php echo feuro($row['ts']) ?></td>
			<td align="center"><?php echo fnbr($row['fts']) ?></td>
			<td align="center"><?php echo fnbr($row['km']) ?></td>
			<td align="center"><?php echo feuro($row['fkm']) ?></td>
			<td align="center"><?php echo feuro($row['unif']) ?></td>
			<td align="center"><?php echo feuro($row['loc1']) ?></td>
			<td align="center"><?php echo feuro($row['cat']) ?></td>
			<td align="center"><?php echo feuro($row['vcat']) ?></td>
			<td align="center"><?php echo feuro($row['disp']) ?></td>
			<td align="center"><?php echo feuro($row['vdisp']) ?></td>
			<th class="vip2" width="15"><input type="checkbox" name="idtoupdate[]" <?php if ($_GET['selectall'] != 'no') { echo 'checked'; } ?> value="<?php echo $row['idvip'] ?>"></th>
		</tr>
<?php
$compt++;
	} 
}
?>
		<tr>
			<td class="etiq2" colspan="12" align="center"><b>Update Missions - S&eacute;l&eacute;ction des missions <?php echo $_GET['etat']; ?></b></td>
			<td class="contenu" colspan="3" align="center"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=moulinetteupdate1&idvipjob='.$_GET['idvipjob'].'&down=down&etat='.$_GET['etat'].'&selectall=checked'; ?>">TOUS</a></td>
			<td class="contenu" colspan="3" align="center"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=moulinetteupdate1&idvipjob='.$_GET['idvipjob'].'&down=down&etat='.$_GET['etat'].'&selectall=no'; ?>">AUCUN</a></td>
			<td class="etiq2" colspan="5" align="center"><input type="submit" name="Modifier" value="Continuer"></td>
		</tr>
	</table>
</form>
</div>