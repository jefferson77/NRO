<?php

$classe = "etiq2";
$classe2 = "standard";
?>
	<table class="<?php echo $classe; ?>" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
<?php
# Recherche des officers
# if (!empty($_GET['idmatos'])) {
		$stocksort='ti.inuse DESC, ti.stockin, ti.stockout, stm.reference, ma.idstockm, ma.idmerch, ma.mnom';
		$recherche1='
			SELECT 
			ti.idticket, ti.idvip, ti.idanimation, ti.idvip, ti.idpeople, ti.dateticket, ti.stockout, ti.stockin, ti.suser, ti.inuse, ti.note, ti.idvipjob, 
			ma.idmatos, ma.codematos, ma.mnom, ma.autre, 
			ma.situation, ma.complet, ma.idstockm, 
			stm.idstockf, stm.reference,
			stf.reference AS reffamille, stf.stype,
			p.pnom, p.pprenom, p.email, p.gsm, p.codepeople,
			j.reference AS jreference, a.reference AS areference, me.produit  
			FROM stockticket ti
			LEFT JOIN matos ma ON ti.idmatos = ma.idmatos 
			LEFT JOIN stockm stm ON ma.idstockm = stm.idstockm 
			LEFT JOIN stockf stf ON stm.idstockf = stf.idstockf 
			LEFT JOIN vipmission m ON ti.idvip = m.idvip 
			LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob 
			LEFT JOIN animation a ON ti.idanimation = a.idanimation 
			LEFT JOIN merch me ON ti.idmerch = me.idmerch 
			LEFT JOIN people p ON ti.idpeople = p.idpeople
		';

		$quid = " WHERE ma.idmatos = ".$idmatos;
		
		$recherche='
			'.$recherche1.'
			'.$quid.'
			 ORDER BY '.$stocksort.'
		';
	$matos = new db();
	$matos->inline("$recherche;");
	$FoundCount = mysql_num_rows($matos->result);
#	echo $recherche;

# }
$i = 0;
$idstockm = 'z';
while ($row = mysql_fetch_array($matos->result)) {
$i++;
$j++; # compteur modele
?>
	<?php /* Ligne Famille */ if ($i == 1) {?>
	<?php } ?>
	<?php /* Ligne Modele et titres */
	if ($row['idstockm'] != $idstockm) { $idstockm = $row['idstockm']; $j = 1;
	?>
		<tr>
			<td class="<?php echo $classe; ?>"></td>
			<td class="<?php echo $classe; ?>">#</td>
			<td class="<?php echo $classe; ?>">ID</td>
			<td class="<?php echo $classe; ?>">Code</td>
			<td class="<?php echo $classe; ?>">R&eacute;f.</td>
			<td class="<?php echo $classe; ?>">User</td>
			<td class="<?php echo $classe; ?>">in use</td>
			<td class="<?php echo $classe; ?>">Situation</td>
			<td class="<?php echo $classe; ?>">Complet</td>
			<td class="<?php echo $classe; ?>">Ticket</td>
			<td class="<?php echo $classe; ?>">Date out</td>
			<td class="<?php echo $classe; ?>">Date in</td>
			<td class="<?php echo $classe; ?>">Mission</td>
			<td class="<?php echo $classe; ?>">People</td>
			<td class="<?php echo $classe; ?>">Gsm</td>
			<td class="<?php echo $classe; ?>">Email</td>
		</tr>
		<tr <?php if ($row['inuse'] == 1) { echo 'bgcolor="#cccccc"';} ?>>
			<td class="<?php echo $classe2; ?>"><?php if ($row['inuse'] == 1) { ?><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=unasign&idticket=<?php echo $row['idticket'];?>&idvipjob=<?php echo $idvipjob;?>&idstockf=<?php echo $row['idstockf'];?>&idstockm=<?php echo $row['idstockm'];?>&idmatos=<?php echo $row['idmatos'];?>"><img src="<?php echo STATIK ?>illus/stock-retour.gif" alt="UnAssign" width="16" border="0"></a><?php } ?></td>
			<td class="<?php echo $classe; ?>"><?php echo $j; ?></td>
			<td class="<?php echo $classe2; ?>"><?php echo $row['idticket']; ?></td>
			<td class="<?php echo $classe2; ?>"><?php echo $row['codematos']; ?></td>
			<td class="<?php echo $classe2; ?>"><?php echo $row['mnom']; ?></td>
			<td class="<?php echo $classe2; ?>"><?php echo $row['suser']; ?></td>
			<td class="<?php echo $classe2; ?>"><?php echo $row['inuse']; ?></td>
			<td class="<?php echo $classe2; ?>">
				<?php 
					if ($row['situation'] == 'in') {echo 'In';}
					if ($row['situation'] == 'out') {echo 'Out';}
					if ($row['situation'] == 'going') {echo 'En partance';}
					if ($row['situation'] == 'supplier') {echo 'Supplier';}
					if ($row['situation'] == 'client') {echo 'Retour client';}
				?>
			</td>
			<td class="<?php echo $classe2; ?>">
				<?php 
					if ($row['complet'] == '0') {echo 'Non';}
					if ($row['complet'] == '1') {echo 'Oui';}
					if ($row['complet'] == '2') {echo 'Partiel';}
				?>
			</td>
			<td class="<?php echo $classe2; ?>"><?php echo fdate($row['dateticket']); ?></td>
			<td class="<?php echo $classe2; ?>"><?php echo fdate($row['stockout']); ?></td>
			<td class="<?php echo $classe2; ?>"><?php echo fdate($row['stockin']); ?></td>
			<td class="<?php echo $classe2; ?>">
				<?php if ($row['idvipjob'] > 0) { echo 'V '.$row['idvipjob'];} ?>
				<?php if ($row['idanimation'] > 0) { echo 'A '.$row['idanimation'];} ?>
				<?php if ($row['idmerch'] > 0) { echo 'M '.$row['idmerch'];} ?>
			</td>
			<td class="<?php echo $classe2; ?>"><?php if ($row['idpeople'] > 0) { echo '<a class="tab" href="../people/adminpeople.php?act=show&idpeople='.$row['idpeople'].'" target="_blank">'; echo "(".$row['codepeople'].") ".$row['pnom'].' '.$row['pprenom']; ?></a><?php } ?></td>
			<td class="<?php echo $classe2; ?>"><?php echo $row['gsm']; ?></td>
			<td class="<?php echo $classe2; ?>"><?php if (!empty($row['email'])) { ?><a href="mailto:<?php echo $row['email']; ?>">email</a><?php } ?></td>
			<td class="<?php echo $classe2; ?>"><?php if (($row['inuse'] == 1) and ($row['suser'] != 'supplier')) { ?><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=supplier&idticket=<?php echo $row['idticket'];?>&idvipjob=<?php echo $idvipjob;?>&idstockf=<?php echo $row['idstockf'];?>&idstockm=<?php echo $row['idstockm'];?>&idmatos=<?php echo $row['idmatos'];?>"><img src="<?php echo STATIK ?>illus/stock-supplier.gif" alt="Supplier" width="33" height="33" border="0"></a><?php } ?></td>
		</tr>
<?php } ?>
	</table>