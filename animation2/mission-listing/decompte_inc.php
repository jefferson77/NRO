<?php

	$colspa = 8;?>
	<table class="<?php echo $classe; ?>" border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
		<tr>
		<?php  $colspa = 9;?>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=listing&down='.$down.'&action=skip&sort=an.idanimation&idanimjob='.$idanimjob.'&viewall='.$_GET['viewall'];?>">M</a></th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=listing&down='.$down.'&action=skip&sort=an.weekm&idanimjob='.$idanimjob.'&viewall='.$_GET['viewall'];?>">S</a></th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=listing&down='.$down.'&action=skip&sort=an.datem&idanimjob='.$idanimjob.'&viewall='.$_GET['viewall'];?>">Date</a></th>
			<th class="<?php echo $classe; ?>">codepeople - <a href="<?php echo $_SERVER['PHP_SELF'].'?act=listing&down='.$down.'&action=skip&sort=p.pnom, p.pprenom&idanimjob='.$idanimjob.'&viewall='.$_GET['viewall'];?>">Nom People</a></th>
			<?php if ($down != 'down') { ?>
				<th class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=listing&down='.$down.'&action=skip&sort=c.idclient&idanimjob='.$idanimjob.'&viewall='.$_GET['viewall']; ?>">Client</a></th>
			<?php } ?>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=listing&down='.$down.'&action=skip&sort=s.codeshop&idanimjob='.$idanimjob.'&viewall='.$_GET['viewall'];?>">Lieux</a></th>
			<th class="<?php echo $classe; ?>">Am In</th>
			<th class="<?php echo $classe; ?>">Am Out</th>
			<th class="<?php echo $classe; ?>">Pm In</th>
			<th class="<?php echo $classe; ?>">Pm Out</th>
			<th class="<?php echo $classe; ?>">tot</th>
			<th class="<?php echo $classe; ?>">KM P</th>
			<th class="<?php echo $classe; ?>">KM F</th>
			<th class="<?php echo $classe; ?>">F - P</th>
			<th class="<?php echo $classe; ?>">Fra P</th>
			<th class="<?php echo $classe; ?>">Fra F</th>
			<th class="<?php echo $classe; ?>">F - P</th>
			<th class="<?php echo $classe; ?>">Liv P</th>
			<th class="<?php echo $classe; ?>">Liv F</th>
			<th class="<?php echo $classe; ?>">F - P</th>
			<th class="<?php echo $classe; ?>" colspan="2">Mode</th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=listing&down='.$down.'&action=skip&sort=an.facturation&idanimjob='.$idanimjob.'&viewall='.$_GET['viewall'];?>">$</a></th>
			<?php if ($down != 'down') { ?>
				<th class="<?php echo $classe; ?>">J</th>
			<?php } ?>
			<th class="<?php echo $classe; ?>">M</th>
		</tr>
		<?php
		$xpnom = '';
		while ($row = mysql_fetch_array($listing->result)) {
			if (($row['facturation'] > 1) and ($row['facturation'] != 3)) {
				$disable = 'disabled';
			} else {
				$disable = '';
			}
			#> Changement de couleur des lignes #####>>####
			$i++;
			if (fmod($i, 2) == 1) {
				$tr = '<tr bgcolor="#9CBECA">';
			} else {
				$tr = '<tr bgcolor="#8CAAB5">';
			}
			#< Changement de couleur des lignes #####<<####
		?>
			<?php if ($disable != 'disabled') { ?>
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=listingmodif&down='.$down.'&action=skip';?>" method="post">
				<input type="hidden" name="idanimation" value="<?php echo $row['idanimation'] ;?>">
				<input type="hidden" name="idanimjob" value="<?php echo $row['idanimjob'] ;?>">
				<input type="hidden" name="idnfrais" value="<?php echo $row['idnfrais'] ;?>">
			<?php }
				## Sous-Total ########################################################################
					if ((!empty($xpnom)) AND ($xpnom != $row['idpeople'])) {
					?>
						<tr bgcolor="white">
							<th colspan="9"></th>
							<th class="<?php echo $classe; ?>"><?php echo $timetotz; $timetotz = 0; ?></th>
							<th class="<?php echo $classe; ?>"><?php echo $kmpayez; ?></th>
							<th class="<?php echo $classe; ?>"><?php echo $kmfacture; ?></th>
							<th class="<?php echo $classe; ?>"><?php $kmdifz = $kmfacturez - $kmpayez ; echo fnega($kmdifz); $kmdifz = 0; $kmpayez = 0; $kmfacturez = 0; ?></th>
							<th class="<?php echo $classe; ?>"><?php echo $fraisz; ?></th>
							<th class="<?php echo $classe; ?>"><?php echo $fraisfacturez; ?></th>
							<th class="<?php echo $classe; ?>"><?php $fraisdifz = $fraisfacturez - $fraisz ; echo fnega($fraisdifz); $fraisdifz = 0; $fraisz = 0; $fraisfacturez = 0; ?></th>
							<th class="<?php echo $classe; ?>"><?php echo $livraisonpayez; ?></th>
							<th class="<?php echo $classe; ?>"><?php echo $livraisonfacturez; ?></th>
							<th class="<?php echo $classe; ?>"><?php $livraisondifz = $livraisonfacturez - $livraisonpayez ; echo fnega($livraisondifz); $livraisondifz = 0; $livraisonpayez = 0; $livraisonfacturez = 0; ?></th>
							<th class="<?php echo $classe; ?>"></th>
							<th class="<?php echo $classe; ?>"></th>
						<?php
						echo '</tr>'.$tr;
					}
				## Rows ########################################################################
				?>
						<td class="<?php echo $classe; ?>"><?php echo $row['idanimation'] ?> <?php echo $row['produit'] ?></td>
						<td class="<?php echo $classe; ?>"><?php echo $row['weekm'] ?></td>
						<td class="<?php echo $classe; ?>"><input type="text" size="9" name="datem" value="<?php echo fdate($row['datem']); ?>" <?php echo $disable; ?>></td>
						<td class="<?php echo $classe; ?>"><img src="<?php echo STATIK ?>illus/<?php echo $row['lbureau']; ?>.gif" alt="<?php echo $row['lbureau']; ?>" width="12" height="9"> <?php echo $row['codepeople'].' - '.$row['pnom'].' '.$row['pprenom'] ?></td>
						<?php if ($down != 'down') { ?>
							<td class="<?php echo $classe; ?>"><?php echo $row['codeclient']; ?> - <?php echo $row['clsociete']; ?></td>
						<?php } ?>
						<td class="<?php echo $classe; ?>"><?php echo $row['codeshop']; ?> - <?php echo $row['ssociete']; ?> - <?php echo $row['sville']; ?></td>
						<td class="<?php echo $classe; ?>"><input type="text" size="3" name="hin1" value="<?php echo ftime($row['hin1']); ?>" <?php echo $disable; ?>></td>
						<td class="<?php echo $classe; ?>"><input type="text" size="3" name="hout1" value="<?php echo ftime($row['hout1']); ?>" <?php echo $disable; ?>></td>
						<td class="<?php echo $classe; ?>"><input type="text" size="3" name="hin2" value="<?php echo ftime($row['hin2']); ?>" <?php echo $disable; ?>></td>
						<td class="<?php echo $classe; ?>"><input type="text" size="3" name="hout2" value="<?php echo ftime($row['hout2']); ?>" <?php echo $disable; ?>></td>
						<td class="<?php echo $classe; ?>">
						<?php
							$anim = new coreanim($row['idanimation']);
							echo $anim->hprest;
							$timetotx += $anim->hprest; $timetotz += $anim->hprest;
						 ?>
						 </td>
						<td class="<?php echo $classe; ?>">
							<input type="text" size="3" name="kmpaye" value="<?php echo fnbr($row['kmpaye']); ?>" <?php echo $disable; ?>>
							<?php $kmpayex += $row['kmpaye']; $kmpayez += $row['kmpaye']; ?>
						</td>
						<td class="<?php echo $classe; ?>">
							<input type="text" size="3" name="kmfacture" value="<?php echo fnbr($row['kmfacture']); ?>" <?php echo $disable; ?>>
							<?php $kmfacturex += $row['kmfacture']; $kmfacturez += $row['kmfacture']; ?>
						</td>
						<td class="<?php echo $classe; ?>">
							<?php $kmtemp = $row['kmfacture'] - $row['kmpaye']; echo fnega($kmtemp); ?>
						</td>
						<td class="<?php echo $classe; ?>">
							<input type="text" size="3" name="montantpaye" value="<?php echo fnbr($row['montantpaye']); ?>" <?php echo $disable; ?>>
							<?php $fraisx += $row['montantpaye']; $fraisz += $row['montantpaye']; ?>
						</td>
						<td class="<?php echo $classe; ?>">
							<input type="text" size="3" name="montantfacture" value="<?php echo fnbr($row['montantfacture']); ?>" <?php echo $disable; ?>>
							<?php $fraisfacturex += $row['montantfacture']; $fraisfacturez += $row['montantfacture']; ?>
						</td>
						<td class="<?php echo $classe; ?>">
							<?php $fraistemp = $row['montantfacture'] - $row['montantpaye']; echo fnega($fraistemp); ?>
						</td>
						<td class="<?php echo $classe; ?>">
							<input type="text" size="3" name="livraisonpaye" value="<?php echo fnbr($row['livraisonpaye']); ?>" <?php echo $disable; ?>>
							<?php $livraisonpayex += $row['livraisonpaye']; ?>
							<?php $livraisonpayez += $row['livraisonpaye']; ?>
						</td>
						<td class="<?php echo $classe; ?>">
							<input type="text" size="3" name="livraisonfacture" value="<?php echo fnbr($row['livraisonfacture']); ?>" <?php echo $disable; ?>>
							<?php $livraisonfacturex += $row['livraisonfacture']; ?>
							<?php $livraisonfacturez += $row['livraisonfacture']; ?>
						</td>
						<td class="<?php echo $classe; ?>">
							<?php $livraisontemp = $row['livraisonfacture'] - $row['livraisonpaye']; echo fnega($livraisontemp); ?>
						</td>
						<td class="<?php echo $classe; ?>">
						<?php
							if ($row['fmode'] == 'briefing') echo '<img src="'.STATIK.'illus/briefing.png" title="Briefing" alt="briefing.png" width="16" height="16">';
							if ($row['fmode'] == 'noforfait') echo '<img src="'.STATIK.'illus/noforfait.png" title="Hors Forfait" alt="noforfait.png" width="16" height="16">';
						?>
						</td>
						<td class="<?php echo $classe; ?>">
						</td>
						<td>
							<?php if ($disable != 'disabled') { ?>
								<input type="submit" name="Modifier" value="M">
							<?php } ?>
						</td>
						<?php if ($down != 'down') { ?>
							<td class="<?php echo $classe; ?>" width="13"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=showjob&idanimation='.$row['idanimation'].'&idanimjob='.$row['idanimjob'];?>" target="_top"><img src="<?php echo STATIK ?>illus/stock_right-16.png" alt="search" width="13" height="15" border="0"></a></td>
						<?php } ?>
						<td class="<?php echo $classe; ?>" width="13"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=showmission&idanimation='.$row['idanimation'].'&idanimjob='.$row['idanimjob'];?>" target="_top"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
					</tr>
			<?php if ($disable != 'disabled') { ?>
			</form>
			<?php } ?>
		<?php
		$xpnom = $row['idpeople'] ;
		} ?>

			<tr bgcolor="white">
				<th colspan="9"></th>
				<th class="<?php echo $classe; ?>"><?php echo $timetotz; $timetotz = 0; ?></th>
				<th class="<?php echo $classe; ?>"><?php echo $kmpayez; ?></th>
				<th class="<?php echo $classe; ?>"><?php echo $kmfacturez; ?></th>
				<th class="<?php echo $classe; ?>"><?php $kmdifz = $kmfacturez - $kmpayez ; echo fnega($kmdifz); $kmdifz = 0; $kmpayez = 0; $kmfacturez = 0; ?></th>
				<th class="<?php echo $classe; ?>"><?php echo fnbr($fraisz); ?></th>
				<th class="<?php echo $classe; ?>"><?php echo fnbr($fraisfacturez); ?></th>
				<th class="<?php echo $classe; ?>"><?php $fraisdifz = $fraisfacturez - $fraisz ; echo fnega($fraisdifz); $fraisdifz = 0; $fraisz = 0; $fraisfacturez = 0; ?></th>
				<th class="<?php echo $classe; ?>"><?php echo fnbr($livraisonpayez); ?></th>
				<th class="<?php echo $classe; ?>"><?php echo fnbr($livraisonfacturez); ?></th>
				<th class="<?php echo $classe; ?>"><?php $livraisondifz = $livraisonfacturez - $livraisonpayez ; echo fnega($livraisondifz); $livraisondifz = 0; $livraisonpayez = 0; $livraisonfacturez = 0; ?></th>
				<th class="<?php echo $classe; ?>"><?php echo fnbr($briefingz); $briefingz = 0; ?></th>
				<th class="<?php echo $classe; ?>"></th>
				<th class="<?php echo $classe; ?>"></th>
			</tr>
		<tr>
			<th colspan="9"></th>
			<th class="<?php echo $classe; ?>"><?php echo $timetotx; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $kmpayex; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $kmfacturex; ?></th>
			<th class="<?php echo $classe; ?>"><?php $kmfinal = $kmfacturex - $kmpayex ; echo fnega($kmfinal);?></th>
			<th class="<?php echo $classe; ?>"><?php echo fnbr($fraisx); ?></th>
			<th class="<?php echo $classe; ?>"><?php echo fnbr($fraisfacturex); ?></th>
			<th class="<?php echo $classe; ?>"><?php $fraisfinal = $fraisfacturex - $fraisx ; echo fnega($fraisfinal);?></th>
			<th class="<?php echo $classe; ?>"><?php echo fnbr($livraisonpayex); ?></th>
			<th class="<?php echo $classe; ?>"><?php echo fnbr($livraisonfacturex); ?></th>
			<th class="<?php echo $classe; ?>"><?php $livraisonfinal = $livraisonfacturex - $livraisonpayex ; echo fnega($livraisonfinal);?></th>
			<th class="<?php echo $classe; ?>"><?php echo fnbr($briefingx); ?></th>
			<th class="<?php echo $classe; ?>"></th>
			<th class="<?php echo $classe; ?>"></th>
		</tr>
	</table>
