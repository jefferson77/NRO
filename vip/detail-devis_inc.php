<?php
$i = $j = $compt = $MontPrest = $MontDepl = $MontLoc = $MontCat = $MontDisp = $MontFrais = $MontFraisDimona = 0;
if (!isset($_GET['sort'])) $_GET['sort'] = '';

# Recherche des résultats
$vip = new db();
if($_GET['sort'] == '1') {
	$sql = "SELECT v . * , s.codeshop
			FROM vipdevis v
			LEFT JOIN shop s ON v.idshop = s.idshop
			WHERE v.idvipjob = ".$idvipjob."
			ORDER BY s.codeshop, v.vipdate, v.vipactivite";
	$vip->inline($sql);
}
else {
	if($_GET['sort'] == '2') {
		$sql = "SELECT v . * , s.codeshop
				FROM vipdevis v
				LEFT JOIN shop s ON v.idshop = s.idshop
				WHERE v.idvipjob = ".$idvipjob."
				ORDER BY s.codeshop desc, v.vipdate, v.vipin, v.vipout, v.vipactivite";
		$vip->inline($sql);
	}
	else {
		$vip->inline("SELECT
			 d.*
			FROM vipdevis d
			LEFT JOIN shop s ON d.idshop = s.idshop
			WHERE d.idvipjob = $idvipjob
			ORDER BY d.vipdate, s.societe, d.vipactivite");
	}
}

?>
<div id="miniinfozone">
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
		<tr>
			<td colspan="6">&nbsp;</td>
			<th class="vip2" colspan="7">Prestations</th>
			<th class="vip2" colspan="2">D&eacute;placements</th>
			<th class="vip2" colspan="2">Location</th>
			<th class="vip2" colspan="4">Frais</th>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td style="width: 10px;">&nbsp;</td>
			<th class="vip2">N&deg;</th>
			<th class="vip2">Date</th>
			<th class="vip2"><a href="<?php echo 'onglet.php?&act=show&etat=0&idvipjob='.$_GET['idvipjob'].'&s=1&action=&sort='.(($_GET['sort'] == '2')?1:2).'"'; ?>">Lieu</a></th>
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
			<th class="vip2">Cat.</th>
			<th class="vip2">Disp</th>
			<th class="vip2">Frais</th>
			<th class="vip2">Dimona</th>
			<td style="width: 10px;">&nbsp;</td>
			<td style="width: 10px;">&nbsp;</td>
		</tr>
<?php

while ($row = mysql_fetch_array($vip->result)) {
	$i++;

	## Calcul des Montants ####################
	$fich = new corevip ($row['idvip'], 'D');

	$MontPrest       += $fich->MontPrest;
	$MontDepl        += $fich->MontDepl;
	$MontLoc         += $fich->MontLoc;

	$MontCat         += $row['cat'];
	$MontDisp        += $row['disp'];
	$MontFrais       += $fich->MontNfrais;
	$MontFraisDimona += $fich->FraisDimona;

	if($act != $row['vipactivite']) $j = 0;
	$act = $row['vipactivite'];

?>
		<tr bgcolor="<?php echo ($row['h200'] == 1)?'#FFFF99':$i%2?'#9CBECA':'#8CAAB5'; ?>">
			<td>
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=duplic&etat=0';?>" method="post"><input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>"><input type="hidden" name="idvip" value="<?php echo $row['idvip'];?>"><input type="submit" name="Modifier" value="D"></form>
			</td>
			<th class="vip2" align="center">
				<?php echo ++$j; ?>
			</th>
			<td align="center">
				<?php echo fdate($row['vipdate']) ?>
			</td>
						<?php
						$selection = '';
						$idshop    = '';
						$infosshop = '';
						# recherche client et cofficer
						if (!empty($row['idshop'])) {
							$idshop = $row['idshop'];
							$detailsh = new db();
							$detailsh->inline("SELECT * FROM `shop` WHERE `idshop`=$idshop");
							$infosshop = mysql_fetch_array($detailsh->result) ;
						}
						if ($infosshop['codeshop'] == '') {$selection = 'selection';}
						if (!empty($infosshop['codeshop'])) {$selection = $infosshop['codeshop'];}
						?>
			<td align="center">
				<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvip='.$row['idvip'].'&idvipjob='.$idvipjob.'&sel=lieu&etat='.$_GET['etat'].'&s=1&from=vipdevis';?>"><?php echo $selection; ?></a>
			</td>
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
			<td align="center"><?php echo feuro($row['disp']) ?></td>
			<td align="center"><?php echo feuro($fich->MontNfrais) ?></td>
			<td align="center"><?php echo feuro($fich->FraisDimona) ?></td>
			<td>
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=mission&etat=0';?>" method="post"><input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>"><input type="hidden" name="idvip" value="<?php echo $row['idvip'];?>"><input type="submit" name="Modifier" value="M"></form>
			</td>
			<td align="center">
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=delete&etat=0';?>" method="post"><input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>"><input type="hidden" name="idvip" value="<?php echo $row['idvip'];?>"><input type="submit" name="Modifier" value="S"></form>
			</td>
		</tr>
<?php
$compt++;
	}

if($compt == '') {
?>
		<tr>
			<td>
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=add&etat=0&idvipjob='.$idvipjob.'';?>" method="post"><input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>"><input type="submit" name="Modifier" value="A"></form>
			</td>
			<td colspan="20">&nbsp; </td>
			<td>
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=add&etat=0&idvipjob='.$idvipjob.'';?>" method="post"><input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>"><input type="submit" name="Modifier" value="A"></form>
			</td>
		</tr>
<?php
}
?>
		<tr>
			<td colspan="6"></td>
			<th class="vip2" colspan="7">Prest. : <?php echo feuro($MontPrest);?></th>
			<th class="vip2" colspan="2">D&eacute;pl. : <?php echo feuro($MontDepl);?></th>
			<th class="vip2" colspan="2">Loc. : <?php echo feuro($MontLoc);?></th>
			<th class="vip2"><?php echo feuro($MontCat);?></th>
			<th class="vip2"><?php echo feuro($MontDisp);?></th>
			<th class="vip2"><?php echo feuro($MontFrais);?></th>
			<th class="vip2"><?php echo feuro($MontFraisDimona);?></th>
			<td colspan="2"></td>
		</tr>
		<tr>
			<td colspan="18"></td>
			<th  class="vip2" colspan="3">
				 Total : <?php echo feuro($MontPrest + $MontDepl + $MontLoc + $MontCat + $MontDisp + $MontFrais + $MontFraisDimona);?>
			</th>
			<td colspan="2"></td>
		</tr>
	</table>
</div>