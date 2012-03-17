	<form action="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl2&action=skip';?>" method="post">
<?php $colspa = 8;?>
	<table class="<?php echo $classe; ?>" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
		<tr>
		<?php  $colspa = 9;?>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=purgefl1&action=skip&sort=me.idmerch">Mission</a></th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=purgefl1&action=skip&sort=me.datem">Date</a></th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=purgefl1&action=skip&sort=p.pnom">People</a></th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=purgefl1&action=skip&sort=c.idclient">Clients</a></th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=purgefl1&action=skip&sort=s.codeshop">Lieux</a></th>
			<th class="<?php echo $classe; ?>">heures</th>
			<th class="<?php echo $classe; ?>">KM P</th>
			<th class="<?php echo $classe; ?>">KM F</th>
			<th class="<?php echo $classe; ?>">P - F</th>
			<th class="<?php echo $classe; ?>">Frais</th>
			<th class="<?php echo $classe; ?>">FL</th>
			<th class="<?php echo $classe; ?>">OUT</th>
			<th class="<?php echo $classe; ?>">Back</th>
			<th class="<?php echo $classe; ?>">Stb</th>
		</tr>
		<?php
		$xpnom = '';
		$timetotz = 0; $kmdifz = 0; $kmpayez = 0; $kmfacturez = 0;
		
		$i = 0;
		while ($row = mysql_fetch_array($$listing->result)) { 
			#> Changement de couleur des lignes #####>>####
			$i++;
			if (fmod($i, 9) == 0) 
				{
				?>
				<tr>
					<th colspan="8"></th>
					<th class="<?php echo $classe; ?>" colspan="2"><input type="submit" name="Modifier" value="Valider"></th>
					<th class="<?php echo $classe; ?>">FL</th>
					<th class="<?php echo $classe; ?>">OUT</th>
					<th class="<?php echo $classe; ?>">Back</th>
					<th class="<?php echo $classe; ?>">Stb</th>
				</tr>
				<?php
				}
			if (fmod($i, 2) == 1) {
				$tr = '<tr bgcolor="#9CBECA">';
			} else {
				$tr = '<tr bgcolor="#8CAAB5">';
			}
			#< Changement de couleur des lignes #####<<####
		?>
				<?php 
					$pnom = $row['pnom'];
					if ((!empty($xpnom)) AND ($xpnom != $pnom)) {
					?>
						<tr bgcolor="white">
							<th colspan="5"><br></th>
							<th class="<?php echo $classe; ?>"><?php echo $timetotz; $timetotz = 0; ?></th>
							<th class="<?php echo $classe; ?>"><?php echo $kmpayez; ?></th>
							<th class="<?php echo $classe; ?>"><?php echo $kmfacture; ?></th>
							<th class="<?php echo $classe; ?>"><?php $kmdifz = $kmfacturez - $kmpayez ; echo fnega($kmdifz); $kmdifz = 0; $kmpayez = 0; $kmfacturez = 0; ?></th>
							<th class="<?php echo $classe; ?>"><?php echo $fraisz; $fraisz = 0; ?></th>
							<th class="<?php echo $classe; ?>"></th>
							<th class="<?php echo $classe; ?>"></th>
						<?php
						echo '</tr>'.$tr;
					}
				?>
						<td class="<?php echo $classe; ?>"><?php echo $row['idmerch'] ?> <?php echo $row['reference'] ?></td>
						<td class="<?php echo $classe; ?>"><?php echo fdate($row['datem']) ?></td>
						<td class="<?php echo $classe; ?>"><img src="<?php echo STATIK ?>illus/<?php echo $row['lbureau']; ?>.gif" alt="<?php echo $row['lbureau']; ?>" width="12" height="9"> <?php echo $row['idpeople'].' - '.$row['pnom'].' '.$row['pprenom'] ?></td>
						<td class="<?php echo $classe; ?>"><?php echo $row['codeclient']; ?> - <?php echo $row['clsociete']; ?></td>
							<td class="<?php echo $classe; ?>"><?php echo $row['codeshop']; ?> - <?php echo $row['ssociete']; ?> - <?php echo $row['sville']; ?></td>
						<td class="<?php echo $classe; ?>">
						<?php 
							$merch = new coremerch($row['idmerch']);
							$timetot =  $merch->hprest;
							echo $timetot;
							$timetotx = $timetotx + $timetot;
							$timetotz = $timetotz + $timetot;
						 ?>
						 </td>
						<td class="<?php echo $classe; ?>">
						<?php echo fnbr($row['kmpaye']); ?>
						<?php $kmpayex = $kmpayex + fnbr($row['kmpaye']); ?>
						<?php $kmpayez = $kmpayez + fnbr($row['kmpaye']); ?>
						</td>
						<td class="<?php echo $classe; ?>">
						<?php echo fnbr($row['kmfacture']); ?>
						<?php $kmfacturex = $kmfacturex + fnbr($row['kmfacture']); ?>
						<?php $kmfacturez = $kmfacturez + fnbr($row['kmfacture']); ?>
						</td>
						<td class="<?php echo $classe; ?>">
						<?php $kmtemp = fnbr($row['kmfacture']) - fnbr($row['kmpaye']); echo fnega($kmtemp); ?>
						</td>
						<td class="<?php echo $classe; ?>">
						<?php echo fnbr($row['montantpaye']); ?>
						<?php $fraisx = $fraisx + fnbr($row['montantpaye']); ?>
						<?php $fraisz = $fraisz + fnbr($row['montantpaye']); ?>
						</td>
						<td class="<?php echo $classe; ?>"><input type="radio" name="factswitch-<?php echo $row['idmerch'] ?>" <?php echo $checkedfl ;?> value="9"></td>
						<td class="<?php echo $classe; ?>"><input type="radio" name="factswitch-<?php echo $row['idmerch'] ?>" <?php echo $checkedout ;?> value="25"></td>
						<td class="<?php echo $classe; ?>"><input type="radio" name="factswitch-<?php echo $row['idmerch'] ?>" <?php echo $checkedback ;?> value="1"></td>
						<td class="<?php echo $classe; ?>"><input type="radio" name="factswitch-<?php echo $row['idmerch'] ?>" <?php echo $checkedsb ;?> value="20"></td>

					</tr>
		<?php 
		$xpnom = $pnom ;
		} ?>

			<tr bgcolor="white">	
				<th colspan="5"></th>
				<th class="<?php echo $classe; ?>"><?php echo $timetotz; $timetotz = 0; ?></th>
				<th class="<?php echo $classe; ?>"><?php echo $kmpayez; ?></th>
				<th class="<?php echo $classe; ?>"><?php echo $kmfacturez; ?></th>
				<th class="<?php echo $classe; ?>"><?php $kmdifz = $kmfacturez - $kmpayez ; echo fnega($kmdifz); $kmdifz = 0; $kmpayez = 0; $kmfacturez = 0; ?></th>
				<th class="<?php echo $classe; ?>"><?php echo $fraisz; $fraisz = 0; ?></th>
				<th class="<?php echo $classe; ?>"></th>
				<th class="<?php echo $classe; ?>"></th>
			</tr>
		<tr>
			<th colspan="5"></th>
			<th class="<?php echo $classe; ?>"><?php echo $timetotx; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $kmpayex; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $kmfacturex; ?></th>
			<th class="<?php echo $classe; ?>"><?php $kmfinal = $kmfacturex - $kmpayex ; echo fnega($kmfinal);?></th>
			<th class="<?php echo $classe; ?>"><?php echo $fraisx; ?></th>
		</tr>
		<tr>
			<th colspan="8"></th>
			<th class="<?php echo $classe; ?>" colspan="2"><input type="submit" name="Modifier" value="Valider"></th>
					<th class="<?php echo $classe; ?>">FL</th>
					<th class="<?php echo $classe; ?>">OUT</th>
					<th class="<?php echo $classe; ?>">Back</th>
					<th class="<?php echo $classe; ?>">Stb</th>
		</tr>
	</table>
<?php echo $i; 
$timetotx = 0; $kmpayex = 0; $kmfacturex = 0; $kmfinal = 0; $fraisx = 0;
?>
	</form>