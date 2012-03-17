<?php
# init
$i = $MontPrest = $MontDepl = $MontLoc = $MontFrais = 0;

# get data
$vips = $DB->getArray("SELECT
		m.brk, m.cat, m.disp, m.fkm, m.fts, m.idpeople, m.idshop, m.idvip, m.km, m.loc1, m.net, m.night, m.sexe, m.ts, m.unif, m.vipactivite, m.vipdate, m.vipin, m.vipout,
		s.codeshop,
		p.pnom, p.pprenom, p.sexe as psexe
	FROM vipmission m
		LEFT JOIN shop s ON m.idshop = s.idshop
		LEFT JOIN people p ON m.idpeople = p.idpeople

	WHERE m.idvipjob = ".$idvipjob."
	ORDER BY m.vipdate, m.vipactivite");

?>
<div id="miniinfozone">
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
		<tr>
			<td colspan="5">&nbsp;</td>
			<th class="vip2" colspan="6">Prestations</th>
			<th class="vip2" colspan="2">D&eacute;placements</th>
			<th class="vip2" colspan="2">Location</th>
			<th class="vip2" colspan="3">Frais</th>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<th class="vip2">Date</th>
			<th class="vip2">Lieu</th>
			<th class="vip2">Activit&eacute;</th>
			<th class="vip2">Sexe</th>
			<th class="vip2">people</th>
			<th class="vip2">IN</th>
			<th class="vip2">OUT</th>
			<th class="vip2">Brk</th>
			<th class="vip2">Night</th>
			<th class="vip2">TS</th>
			<th class="vip2">Fts</th>
			<th class="vip2">KM</th>
			<th class="vip2">Fkm</th>
			<th class="vip2">Unif.</th>
			<th class="vip2">Loc</th>
			<th class="vip2">Cat.</th>
			<th class="vip2">Disp</th>
			<th class="vip2">FR</th>
			<th class="vip2">&nbsp;</th>
		</tr>
<?php
foreach ($vips as $row) {

	## Calcul des Montants ####################
	$fich = new corevip ($row['idvip']);

	$MontPrest += $fich->MontPrest;
	$MontDepl  += $fich->MontDepl;
	$MontLoc   += $fich->MontLoc;
	$MontFrais += $fich->MontFrais;

	$colorsex = (($row['sexe'] != 'x') && ($row['psexe'] != $row['sexe']))?array('<Font color="red">', '</Font>'):array('','');
?>
		<tr bgcolor="<?php echo (++$i%2)?'#9CBECA':'#8CAAB5'; ?>">
			<td align="center"> <?php echo fdate($row['vipdate']) ?> </td>
			<td align="center"> <?php echo $row['codeshop']; ?> </td>
			<td align="center"> <?php echo $row['vipactivite'] ?> </td>
			<td align="center"> <?php echo $row['sexe'] ?> </td>
			<td align="center">
				<?php echo $colorsex[0].$row['pprenom'].' '.$row['pnom'].$colorsex[1]; ?>-<a href="<?php echo NIVO.'data/people/adminpeople.php?act=show&idpeople='.$row['idpeople'];?>" target="_blank"><img src="<?php echo STATIK ?>illus/icon_profile.gif" border="0" width="15" height="12"></a>
			</td>
			<td align="center"><?php echo ftime($row['vipin']) ?></td>
			<td align="center"><?php echo ftime($row['vipout']) ?></td>
			<td align="center"><?php echo fnbr($row['brk']) ?></td>
			<td align="center"><?php echo fnbr($row['night']) ?></td>
			<td align="center"><?php echo feuro($row['ts']) ?></td>
			<td align="center"><?php echo fnbr($row['fts']) ?></td>
			<td align="center"><?php echo fnbr($row['km']) ?></td>
			<td align="center"><?php echo feuro($row['fkm']) ?></td>
			<td align="center"><?php if ($row['unif'] == '') {$row['unif'] = $row['net'];} ?><?php echo feuro($row['unif']) ?></td>
			<td align="center"><?php echo feuro($row['loc1']) ?></td>
			<td align="center"><?php echo feuro($row['cat']) ?></td>
			<td align="center"><?php echo feuro($row['disp']) ?></td>
			<td align="center"><?php echo feuro($fich->MontNfrais) ?></td>
			<td>
				<form action="adminvip.php?act=showmission&idvip=<?php echo $row['idvip'];?>" method="post" target="_top">
					<input type="submit" name="Modifier" value="V">
				</form>
			</td>
		</tr>
<?php } ?>
		<tr>
			<td colspan="5">&nbsp;</td>
			<th class="vip2" colspan="6">Prest. :<?php echo feuro($MontPrest);?></th>
			<th class="vip2" colspan="2">D&eacute;pl. :<?php echo feuro($MontDepl);?></th>
			<th class="vip2" colspan="2">Loc. :<?php echo feuro($MontLoc);?></th>
			<th class="vip2" colspan="3">Frais :<?php echo feuro($MontFrais);?></th>
			<th class="vip2">&nbsp;</th>
		</tr>
		<tr>
			<td colspan="15"></td>
			<th  class="vip2" colspan="3">
				 Total :<?php echo feuro($MontPrest + $MontDepl + $MontLoc + $MontFrais);?>
			</th>
			<td></td>
		</tr>

	</table>
</div>