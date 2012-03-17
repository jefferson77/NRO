<div id="miniinfozone">
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
		<tr>
			<td colspan="8">&nbsp;</td>
			<th class="vip2" colspan="7">Prestations</th>
			<th class="vip2" colspan="2">D&eacute;placements</th>
			<th class="vip2" colspan="4">Frais</th>
			<form action="<?php echo NIVO."mod/sms/adminsms.php?act=show";?>" target="centerzonelarge" method="post">
				<th colspan="3"><input type="submit" class="btn phone" value="sms" name="send"></th>
				<?php
					$peopsDuJob = $DB->getColumn("SELECT DISTINCT(idpeople) FROM vipmission WHERE idpeople > 0 AND idvipjob = ".$idvipjob);

					if ((count($peopsDuJob) > 0) and $peopsDuJob) {
						foreach ($peopsDuJob as $people) echo '<input type = "hidden" value="'.$people.'" name="dest[]">';
					}
				?>
				<input type="hidden" name="secteur" value="VI">
			</form>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<th class="vip2">N&deg;</th>
			<th class="vip2">Date</th>
			<th class="vip2">Lieu</th>
			<th class="vip2">Activit&eacute;</th>
			<th class="vip2">Sexe</th>
			<th class="vip2" title="Contrat Encodé">C</th>
			<th class="vip2">people</th>
			<th class="vip2">IN</th>
			<th class="vip2">OUT</th>
			<th class="vip2">Brk</th>
			<th class="vip2">Night</th>
			<th class="vip2">Sup</th>
			<th class="vip2">TS</th>
			<th class="vip2">Fts</th>
			<th class="vip2">KM</th>
			<th class="vip2">Fkm</th>
			<th class="vip2">Loc.</th>
			<th class="vip2">Cat.</th>
			<th class="vip2">Frais</th>
			<th class="vip2">Dim</th>
			<th class="vip2" colspan="3">&nbsp;</th>
		</tr>
<?php
if (!empty($idvipjob)) {
	# Recherche des résultats
	$vip = new db();
	$vip->inline('SELECT IF(vipdate < CURDATE(), 1,0) as tri, m.*, notefrais.montantpaye FROM vipmission m
				LEFT JOIN shop s ON m.idshop = s.idshop
				LEFT JOIN notefrais ON m.idvip = notefrais.idmission
				WHERE idvipjob = "'.$idvipjob.'" ORDER BY tri, m.vipdate, s.societe, m.vipactivite, m.idvip');

	$i = 0; # Pour les couleurs alternées
	$j = 0;

	$tarifforfait = array(8, 9, 10);

	$MontPrest       = 0;
	$MontDepl        = 0;

	$MontLoc         = 0;
	$MontCat         = 0;
	$MontFrais       = 0;
	$MontFraisDimona = 0;

	$MontTotal       = 0;

	$compt           = 0;
	$act             = 0;

	while ($row = mysql_fetch_array($vip->result)) {

	### > #### Vérifs KM et FKM ## 2005-04-26 ##################################################################################################################
		## Check Forfait ## Place 4.5 dans les payements si le forfait est de 8 ou 9 dans les fac

			if (($row['fkm'] >= 8) and ($row['vfkm'] == 0)) {
				$DB->inline("UPDATE vipmission SET vfkm = '4.5' WHERE idvip = '".$row['idvip']."' LIMIT 1 ");
			}

		## Check Déplace ## Place le montant des KM moins 15 dans les KM payés
			if (($row['km'] > 60) and ($row['vkm'] == 0)) {
				$sql = "UPDATE vipmission SET vkm = (km - 15) WHERE idvip = '".$row['idvip']."' LIMIT 1 "; $upkm = new db(); $upkm->inline($sql);
			} elseif (($row['km'] > 30) and ($row['vkm'] == 0)) {
				$sql = "UPDATE vipmission SET vkm = (km - 10) WHERE idvip = '".$row['idvip']."' LIMIT 1 "; $upkm = new db(); $upkm->inline($sql);
			} elseif (($row['km'] > 20) and ($row['vkm'] == 0)) {
				$sql = "UPDATE vipmission SET vkm = (km - 5) WHERE idvip = '".$row['idvip']."' LIMIT 1 "; $upkm = new db(); $upkm->inline($sql);
			} elseif (($row['km'] > 0) and ($row['vkm'] == 0)) {
				$sql = "UPDATE vipmission SET vkm = km WHERE idvip = '".$row['idvip']."' LIMIT 1 "; $upkm = new db(); $upkm->inline($sql);
			}


	### < #### Vérifs KM et FKM ## 2005-04-26 ##################################################################################################################


		## Calcul des Montants ####################
		$fich = new corevip ($row['idvip']);

		$MontPrest += $fich->MontPrest;
		$MontDepl        += $fich->MontDepl;

		$MontLoc         += $fich->MontLoc;
		$MontCat         += $row['cat'];
		$MontFrais       += $fich->MontNfrais;
		$MontFraisDimona += $fich->FraisDimona;

		$MontTotal       += $fich->MontTotal;


	#> Changement de couleur des lignes #####>>####
	$i++;
	$j++;

	if($_REQUEST['idvip'] == $row['idvip']) echo '<tr bgcolor="#FFA303">';
	else if ($row['h200'] == 1) echo '<tr bgcolor="#FFFF99">';
	else if($row['tri'] >0) echo '<tr bgcolor="'.((fmod($i, 2) == 1)?'#B8B8B8':'#A4A4A4').'">';
	else echo '<tr bgcolor="'.((fmod($i, 2) == 1)?'#9CBECA':'#8CAAB5').'">';
	#< Changement de couleur des lignes #####<<####
?>
			<td>
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=duplic&etat=1' ?>" method="post">
					<input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>">
					<input type="hidden" name="idvip" value="<?php echo $row['idvip'];?>">
					<input type="submit" class="btn duplic" name="Modifier" value="D" title="Dupliquer le Job">
				</form>
			</td>
			<th align="center" class="vip2">
				<?php
					if($act == $row['vipactivite']) {
						echo $j;
					}
					else {
						$j = 1;
						echo $j;
					}
					$act = $row['vipactivite'];
				?>
			</th>
			<td align="center">
				<?php echo fdate($row['vipdate']) ?>
			</td>
						<?php
						$selection = '';
						# recherche client et cofficer
						$infosshop = ($row['idshop'] > 0) ? $DB->getRow("SELECT * FROM shop WHERE idshop = ".$row['idshop']) : '';
						if ($infosshop['codeshop'] == '') {$selection = 'selection';}
						if (!empty($infosshop['codeshop'])) {$selection = $infosshop['codeshop'];}
						?>
			<td align="center">
				<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvip='.$row['idvip'].'&idvipjob='.$idvipjob.'&sel=lieu&etat='.$_GET['etat'].'&from=vip&s=1';?>"><?php echo $selection; ?></a>
			</td>
			<td align="center">
				<?php echo $row['vipactivite'] ?>
			</td>
			<td align="center">
				<?php echo $row['sexe'] ?>
			</td>
			<td style="width: 10px;"><img src='<?php echo STATIK.'/nro/illus/'.(($row['contratencode'] == "1")?'tick_small.png':'exclamation_small.png'); ?>' width="10" height="10" alt="Contrat"></td>
<?php
						$colorsex1   = '';
						$colorsex2   = '';
						$selection   = '';
						$idpeople    = '';
						$infospeople = '';
						# recherche client et cofficer
						if ($row['idpeople'] > 1) {
							$idpeople = $row['idpeople'];
							$detailp = new db();
							$detailp->inline("SELECT * FROM people WHERE idpeople=$idpeople");
							$infospeople = mysql_fetch_array($detailp->result) ;
							if (($row['sexe'] != 'x') AND ($infospeople['sexe'] != $row['sexe']))
							{
								$colorsex1 = '<Font color="red">';
								$colorsex2 = '</Font>';
							}
						}
						if ((empty($infospeople['pprenom'])) AND (empty($infospeople['pnom']))) {
							$selection = 'selection';
						} else {
							$selection = $infospeople['pprenom'].' '.$infospeople['pnom'];
						}
?>
			<td align="center">
				<?php if ($selection == 'selection') { ?>
					<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvip='.$row['idvip'].'&idvipjob='.$idvipjob.'&sexe='.$row['sexe'].'&sel=people';?>"><?php echo $selection; ?></a>
				<?php } else { ?>
					<a href="<?php echo $_SERVER['PHP_SELF'].'?act=remove&idvip='.$row['idvip'].'&idvipjob='.$idvipjob.'&sexe='.$row['sexe'].'&sel=people';?>"><?php echo $colorsex1; ?> <?php echo $selection; ?> <?php echo $colorsex2; ?></a>-<a href="<?php echo NIVO.'data/people/adminpeople.php?act=show&idpeople='.$idpeople;?>" target="_blank"><img src="<?php echo STATIK ?>illus/icon_profile.gif" border="0" width="15" height="12"></a>
				<?php } ?>
			</td>
			<td align="center"> <?php echo ftime($row['vipin']) ?> </td>
			<td align="center"> <?php echo ftime($row['vipout']) ?> </td>
			<td align="center"> <?php echo fnbr($row['brk']) ?> </td>
			<td align="center"> <?php echo fnbr($row['night']) ?> </td>
			<td align="center"> <?php echo fnbr($row['h150']) ?> </td>
			<td align="center"> <?php echo feuro($row['ts']) ?> </td>
			<td align="center"> <?php echo fnbr($row['fts']) ?> </td>
			<td align="center"> <?php echo fnbr($row['km']) ?> </td>
			<td align="center"> <?php echo feuro($row['fkm']) ?> </td>
			<td align="center"> <?php echo feuro($fich->MontLoc) ?> </td>
			<td align="center"> <?php echo feuro($row['cat']) ?> </td>
			<td align="center"> <?php echo feuro($fich->MontNfrais); ?> </td>
			<td align="center"> <?php echo feuro($fich->FraisDimona); ?> </td>
			<td style="width: 10px;">
				<?php if ($row['metat'] <= '12') { ?>
					<form action="adminvip.php?act=showmission&idvip=<?php echo $row['idvip'] ?>" method="post" target="_top">
						<input type="submit" name="Modifier" value="M" class="btn afficher" title="Modifier la mission">
					</form>
				<?php } else { echo $row['metat']; }?>
			</td>
			<td align="center">
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=delete&etat=1&step=note';?>" method="post">
					<input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>">
					<input type="hidden" name="idvip" value="<?php echo $row['idvip'];?>">
					<input type="submit" name="Modifier" value="S" class="btn delete" title="Effacer la Mission">
				</form>
			</td>
			<td>
			<?php
				# verif si note de frais existante
				if($vip->getArray('SELECT * FROM notefrais WHERE idjob='.$idvipjob)) {
					echo '
					<form action="../commun/notefrais.php?idvipjob='.$idvipjob.'&secteur=VI" method="post">
						<input type="submit" name="Notes de frais" value="N">
					</form>
					';
				}
			?>
			</td>
		</tr>
<?php
$compt++;
	}
}
if(empty($compt)) {
?>
		<tr>
			<td>
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=add&etat=1&idvipjob='.$idvipjob.'';?>" method="post">
					<input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>">
					<input type="submit" name="Modifier" value="A">
				</form>
			</td>
			<td colspan="20">&nbsp;

			</td>
			<td>
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=add&etat=1&idvipjob='.$idvipjob.'';?>" method="post">
					<input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>">
					<input type="submit" name="Modifier" value="A">
				</form>
			</td>
		</tr>
<?php
}
?>
		<tr>
			<td colspan="8">&nbsp;</td>
			<th class="vip2" colspan="7"> Prest. : <?php echo feuro($MontPrest);?> </th>
			<th class="vip2" colspan="2"> D&eacute;pl. : <?php echo feuro($MontDepl);?> </th>
			<th class="vip2"> <?php echo feuro($MontLoc);?> </th>
			<th class="vip2"> <?php echo feuro($MontCat);?> </th>
			<th class="vip2"> <?php echo feuro($MontFrais);?> </th>
			<th class="vip2"> <?php echo feuro($MontFraisDimona);?> </th>
			<th class="vip2" colspan="3">&nbsp;</th>
		</tr>
		<tr>
			<td colspan="16">&nbsp;</td>
			<th  class="vip2" colspan="4"> Total : <?php echo feuro($MontTotal);?> </th>
			<td colspan="3"></td>
		</tr>

	</table>
</div>