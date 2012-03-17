<?php
if ($down == 'down') {
	$colspa = 10;
	$colspa2 = 3;
} else {
	$colspa = 12;
	$colspa2 = 4;
}
###############################################################################################################################
### Barre titre et tri #######################################################################################################
#############################################################################################################################
$cURL = $_SERVER['PHP_SELF'].'?act=listing&down='.$down.'&action=skip&idanimjob='.$idanimjob.'&viewall='.$_GET['viewall'].'&';
?>
	<table class="<?php echo $classe; ?>" border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
		<tr>
		<?php if ($down != 'down') { ?>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=an.idanimjob'; ?>">J</a></th>
		<?php } ?>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=an.idanimation'; ?>">Mission</a></th>
			<th class="<?php echo $classe; ?>">C</th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=an.weekm'; ?>">S</a></th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=an.datem'; ?>">Date</a></th>
			<th class="<?php echo $classe; ?>">code - <a href="<?php echo $cURL.'sort=p.pnom, p.pprenom'; ?>">Nom People</a></th>
		<?php if ($down != 'down') { ?>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=c.idclient'; ?>">Client</a></th>
		<?php } ?>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=s.codeshop'; ?>">Lieux</a></th>
			<th class="<?php echo $classe; ?>">A In</th>
			<th class="<?php echo $classe; ?>">A Out</th>
			<th class="<?php echo $classe; ?>">P In</th>
			<th class="<?php echo $classe; ?>">P Out</th>
			<th class="<?php echo $classe; ?>">tot</th>
			<th class="<?php echo $classe; ?>">KM Auto</th>
			<th class="<?php echo $classe; ?>">KM P</th>
			<th class="<?php echo $classe; ?>">KM F</th>
			<th class="<?php echo $classe; ?>">&#8710;</th>
			<th class="<?php echo $classe; ?>">Fra P</th>
			<th class="<?php echo $classe; ?>">Fra F</th>
			<th class="<?php echo $classe; ?>">&#8710;</th>
			<th class="<?php echo $classe; ?>">Liv P</th>
			<th class="<?php echo $classe; ?>">Liv F</th>
			<th class="<?php echo $classe; ?>">&#8710;</th>
			<th class="<?php echo $classe; ?>">Enc</th>
			<th class="<?php echo $classe; ?>"></th>
			<th class="<?php echo $classe; ?>"></th>
		<?php if ($down != 'down') { ?>
			<th class="<?php echo $classe; ?>">J</th>
		<?php }  ?>
			<th class="<?php echo $classe; ?>">M</th>
		</tr>
	<?php
###############################################################################################################################
### Datas ####################################################################################################################
#############################################################################################################################
	$xpnom = '';

	while ($row = mysql_fetch_array($listing->result)) {
		$disable = (($row['facturation'] > 1) and ($row['facturation'] != 3))?' disabled':'';
		$dist = CalcDeplacement('AN', $row['idanimation']);

		$i++;

		if ($disable != 'disabled') { ?>
			<form action="?act=listingmodif&down=<?php echo $down ?>&action=skip" method="post">
			<input type="hidden" name="idanimation" value="<?php echo $row['idanimation'] ;?>">
			<input type="hidden" name="idanimjob" value="<?php echo $row['idanimjob'] ;?>">
			<input type="hidden" name="idnfrais" value="<?php echo $row['idnfrais'] ;?>">
		<?php }
		echo '<tr bgcolor="',(fmod($i, 2) == 1)?'#9CBECA':'#8CAAB5','">'; ?>
			<?php if ($down != 'down') { ?>
				<td class="<?php echo $classe; ?>"><?php echo $row['idanimjob'] ?></td>
			<?php } ?>
				<td class="<?php echo $classe; ?>"><?php echo $row['idanimation'] ?> <?php echo $row['produit'] ?></td>
				<td class="<?php echo $classe; ?>" width="13"><?php if ($row['tchkdate'] != '0000-00-00') { echo '<img src="'.STATIK.'illus/stock_edit-16.png" alt="" width="16" height="16" title="'.fdate($row['tchkdate']).'">';} ?></td>
				<td class="<?php echo $classe; ?>"><?php echo $row['weekm'] ?></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="9" name="datem" value="<?php echo fdate($row['datem']); ?>" <?php echo $disable; ?>></td>
				<td class="<?php echo $classe; ?>"><img src="<?php echo STATIK ?>illus/<?php echo $row['lbureau']; ?>.gif" alt="<?php echo $row['lbureau']; ?>" width="12" height="9"> <?php echo $row['codepeople'].' - '.$row['pnom'].' '.$row['pprenom'] ?></td>
			<?php if ($down != 'down') { ?>
				<td class="<?php echo $classe; ?>"><?php echo $row['codeclient'].' - '.$row['clsociete']; ?></td>
			<?php } ?>
				<td class="<?php echo $classe; ?>"><?php echo showmax($row['codeshop'].' - '.$row['ssociete'].' - '.$row['sville'], 30); ?></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="3" name="hin1" value="<?php echo ftime($row['hin1']); ?>" <?php echo $disable; ?>></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="3" name="hout1" value="<?php echo ftime($row['hout1']); ?>" <?php echo $disable; ?>></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="3" name="hin2" value="<?php echo ftime($row['hin2']); ?>" <?php echo $disable; ?>></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="3" name="hout2" value="<?php echo ftime($row['hout2']); ?>" <?php echo $disable; ?>></td>
				<td class="<?php echo $classe; ?>">
				<?php
					$anim = new coreanim($row['idanimation']);
					$timetot = $anim->hprest;
					echo $timetot;
					$timetotx = $timetotx + $timetot;
				 ?>
				 </td>
				<td class="<?php echo $classe; ?>">
					<input type="hidden" name="distauto" value="<?php echo $dist['AR'] ?>">
					<input type="checkbox" name="kmauto" value="Y" <?php echo ($row['kmauto'] == 'Y')?' checked':'' ?>> <?php echo $dist['AR'] ?> km
					<?php $kmautox += $dist['AR'] ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<input type="text" size="3" name="kmpaye" value="<?php echo fnbr($row['kmpaye']); ?>" <?php echo $disable; ?> <?php echo ($row['kmauto'] == 'Y')?' disabled':'' ?>>
					<?php $kmpayex += $row['kmpaye']; ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<input type="text" size="3" name="kmfacture" value="<?php echo fnbr($row['kmfacture']); ?>" <?php echo $disable; ?><?php echo ($row['kmauto'] == 'Y')?' disabled':'' ?>>
					<?php $kmfacturex += $row['kmfacture']; ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<?php $kmtemp = $row['kmfacture'] - $row['kmpaye']; echo fnega($kmtemp); ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<input type="text" size="3" name="montantpaye" value="<?php echo fnbr($row['montantpaye']); ?>" <?php echo $disable; ?>>
					<?php $fraisx += $row['montantpaye']; ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<input type="text" size="3" name="montantfacture" value="<?php echo fnbr($row['montantfacture']); ?>" <?php echo $disable; ?>>
					<?php $fraisfacturex += $row['montantfacture']; ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<?php echo fnega($row['montantfacture'] - $row['montantpaye']); ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<input type="text" size="3" name="livraisonpaye" value="<?php echo fnbr($row['livraisonpaye']); ?>" <?php echo $disable; ?>>
					<?php $livraisonpayex += $row['livraisonpaye']; ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<input type="text" size="3" name="livraisonfacture" value="<?php echo fnbr($row['livraisonfacture']); ?>" <?php echo $disable; ?>>
					<?php $livraisonfacturex += $row['livraisonfacture']; ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<?php $livraisontemp = $row['livraisonfacture'] - $row['livraisonpaye']; echo fnega($livraisontemp); ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<?php echo '<input type="checkbox" name="ccheck" value="Y" '; if ($row['ccheck'] == 'Y') { echo 'checked';} echo'>'; ?>
				</td>
				<td class="<?php echo $classe; ?>">
				<?php
					if ($row['fmode'] == 'briefing') echo '<img src="'.STATIK.'illus/briefing.png" title="Briefing" alt="briefing.png" width="16" height="16">';
					if ($row['fmode'] == 'noforfait') echo '<img src="'.STATIK.'illus/noforfait.png" title="Hors Forfait" alt="noforfait.png" width="16" height="16">';
				?>
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
	<?php }

	}
###############################################################################################################################
### Sous Totaux ##############################################################################################################
#############################################################################################################################
?>
		<tr>
			<th class="<?php echo $classe; ?>" colspan="<?php echo $colspa; ?>"></th>
			<th class="<?php echo $classe; ?>"><?php echo $timetotx; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $kmautox; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $kmpayex; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $kmfacturex; ?></th>
			<th class="<?php echo $classe; ?>"><?php $kmdifz = $kmfacturex - $kmpayex ; echo fnega($kmdifz); ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $fraisx; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $fraisfacturex; ?></th>
			<th class="<?php echo $classe; ?>"><?php $fraisdifz = $fraisfacturex - $fraisx ; echo fnega($fraisdifz); ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $livraisonpayex; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $livraisonfacturex; ?></th>
			<th class="<?php echo $classe; ?>"><?php $livraisondifz = $livraisonfacturex - $livraisonpayex ; echo fnega($livraisondifz); ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $briefingx; ?></th>
			<th class="<?php echo $classe; ?>" colspan="<?php echo $colspa2; ?>"></th>
		</tr>
	</table>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		// Highlight modified lines
		$("tr input").change(function () { $(this).parents('tr').css({'background-color' : '#FF6600'}); })
		// disable de kmfacture sur kmauto est coché
		$("tr input[name='kmauto']").change(function () { 
			if ($(this).attr('checked')) {
				$(this).parent("td").next("td").children("input[name='kmpaye']").attr({disabled: 'disabled'});
				$(this).parent("td").next("td").next("td").children("input[name='kmfacture']").attr({disabled: 'disabled'});
			} else {
				$(this).parent("td").next("td").children("input[name='kmpaye']").attr('disabled', '');
				$(this).parent("td").next("td").next("td").children("input[name='kmfacture']").attr('disabled', '');
			}
			$(this).parent("td").next("td").children("input[name='kmpaye']").val($(this).parent("td").children("input[name='distauto']").val());
			$(this).parent("td").next("td").next("td").children("input[name='kmfacture']").val($(this).parent("td").children("input[name='distauto']").val());
		})
	});
</script>