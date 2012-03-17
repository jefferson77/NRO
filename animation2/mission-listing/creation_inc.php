<?php
###############################################################################################################################
### Ligne d'ajout de mission #################################################################################################
#############################################################################################################################
	function CodeAddForm($down, $idanimjob, $classe) { ?>
		<!-- Form d'ajout d'une mission -->
		<tr style="background-color: #CCC;">
			<form action="<?php echo $_SERVER['PHP_SELF'].'?act=listingmodifdevisadd&down='.$down.'&action=skip';?>" method="post">
				<input type="hidden" name="idanimjob" value="<?php echo $idanimjob ;?>">
				<td colspan="2"></td>
				<td><input type="submit" class="btn add" title="Ajouter" name="Modifier" value="Add"></td>
				<td></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="25" name="produit" value=""></td>
				<td class="<?php echo $classe; ?>"></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="11" name="datem" value=""></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="4" name="idshop" value=""></td>
				<td class="<?php echo $classe; ?>"></td>
				<td class="<?php echo $classe; ?>"></td>
				<td class="<?php echo $classe; ?>"></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="5" name="hin1" value=""></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="5" name="hout1" value=""></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="5" name="hin2" value=""></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="5" name="hout2" value=""></td>
				<td class="<?php echo $classe; ?>" colspan="4">
					<select name="fmode">
						<option value="normal" selected >Normal</option>
						<option value="briefing">Briefing</option>
						<option value="noforfait">Hors For</option>
					</select>
				</td>
			</form>
<?php }
$colspa = 5;
###############################################################################################################################
### Barre de titres et tri ###################################################################################################
#############################################################################################################################
$cURL = $_SERVER['PHP_SELF'].'?act=listing&down='.$down.'&action=skip&idanimjob='.$idanimjob.'&viewall='.$_GET['viewall'].'&';
?>
	<table class="<?php echo $classe; ?>" border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="<?php echo $classe; ?>"></th>
			<th class="<?php echo $classe; ?>">D</th>
			<th class="<?php echo $classe; ?>">S</th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=an.idanimation'; ?>">M</a></th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=an.produit'; ?>">Produits</a></th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=an.weekm'; ?>">S</a></th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=an.datem'; ?>">Date</a></th>
			<th class="<?php echo $classe; ?>" colspan="2"><a href="<?php echo $cURL.'sort=s.codeshop'; ?>">Lieux</a></th>
			<th class="<?php echo $classe; ?>" colspan="2"><a href="<?php echo $cURL.'sort=an.idpeople'; ?>">People</a></th>
			<th class="<?php echo $classe; ?>">Am In</th>
			<th class="<?php echo $classe; ?>">Am Out</th>
			<th class="<?php echo $classe; ?>">Pm In</th>
			<th class="<?php echo $classe; ?>">Pm Out</th>
			<th class="<?php echo $classe; ?>">tot</th>
			<th class="<?php echo $classe; ?>">M</th>
			<th class="<?php echo $classe; ?>" colspan="2"></th>
		</tr>
<?php CodeAddForm($down, $idanimjob, $classe); ?>
		</tr>
<?php
###############################################################################################################################
### Datas ####################################################################################################################
#############################################################################################################################
	$xpnom = '';
	while ($row = mysql_fetch_array($listing->result)) {
		$i++;

		## Disabled or not en fonction de l'état de facturation
		if (($row['facturation'] > 1) and ($row['facturation'] != 3)) { $disable = 'disabled'; } else { $disable = ''; }

		echo '<tr bgcolor="',(fmod($i, 2) == 1)?'#9CBECA':'#8CAAB5','">'; ?>
				<td><?php echo $i; ?></td>
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=listingmodifdevisdupplic&down='.$down.'&action=skip';?>" method="post">
			<input type="hidden" name="idanimation" value="<?php echo $row['idanimation'] ;?>">
			<input type="hidden" name="idanimjob" value="<?php echo $row['idanimjob'] ;?>">
				<td><input type="submit" class="btn duplic" name="Modifier" value="Dup" title="Dupliquer"></td>
		</form>
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=listingmodifdevisdel&down='.$down.'&action=skip';?>" method="post" onsubmit="return confirm('ATTENTION !!!!! \nEffacer la Mission ?')">
			<input type="hidden" name="idanimation" value="<?php echo $row['idanimation'] ;?>">
			<input type="hidden" name="idanimjob" value="<?php echo $row['idanimjob'] ;?>">
				<td>
					<?php if ($disable != 'disabled') { ?>
						<input type="submit" title="Effacer" name="Modifier" value="Sup">
					<?php } ?>
				</td>
		</form>
	<?php if ($disable != 'disabled') { ?>
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=listingmodifdevis&down='.$down.'&action=skip';?>" method="post">
			<input type="hidden" name="idanimation" value="<?php echo $row['idanimation'] ;?>">
			<input type="hidden" name="idanimjob" value="<?php echo $row['idanimjob'] ;?>">
	<?php } ?>
				<td class="<?php echo $classe; ?>"><?php echo $row['idanimation'] ?></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="25" name="produit" value="<?php echo $row['produit']; ?>" <?php echo $disable; ?>></td>
				<td class="<?php echo $classe; ?>"><?php echo $row['weekm'] ?></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="11" name="datem" value="<?php echo fdate($row['datem']); ?>" <?php echo $disable; ?>></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="4" name="idshop" value="<?php echo $row['idshop']; ?>" <?php echo $disable; ?>></td>
				<td class="<?php echo $classe; ?>">
					<?php if (($row['facturation'] > 1) and ($row['facturation'] != 3)) { ?>
						<?php echo $row['codeshop']; ?> - <?php echo $row['ssociete']; ?> - <?php echo $row['sville']; ?>
					<?php } else { ?>
						<a href="<?php echo $_SERVER['PHP_SELF'].'?act=selectlieu&down='.$down.'&action=skip&sort=an.idanimation&idanimation='.$row['idanimation'].'&idanimjob='.$idanimjob; ?>"><?php echo $row['codeshop']; ?> - <?php echo $row['ssociete']; ?> - <?php echo $row['sville']; ?></a>
					<?php } ?>
				</td>
				<td class="<?php echo $classe; ?>"><input type="text" size="4" name="idpeople" value="<?php echo $row['idpeople']; ?>" <?php echo $disable; ?>></td>
				<td class="<?php echo $classe; ?>">
					<?php if (($row['facturation'] > 1) and ($row['facturation'] != 3)) { ?>
						<img src="<?php echo STATIK ?>illus/<?php echo $row['lbureau']; ?>.gif" alt="<?php echo $row['lbureau']; ?>" width="12" height="9"> <?php echo $row['pnom'].' '.$row['pprenom'] ?><?php if ($row['idpeople'] == '') { echo 'select';}?></a> <?php if (!empty($row['idpeople'])) { ?>&nbsp; <a href="<?php echo NIVO.'data/people/adminpeople.php?act=show&idpeople='.$row['idpeople'];?>" target="_blank"><img src="<?php echo STATIK ?>illus/icon_profile.gif" border="0" width="15" height="12"><?php } ?>
					<?php } else { ?>
						<img src="<?php echo STATIK ?>illus/<?php echo $row['lbureau']; ?>.gif" alt="<?php echo $row['lbureau']; ?>" width="12" height="9"> <a href="<?php echo $_SERVER['PHP_SELF'].'?act=selectpeople&down='.$down.'&action=skip&sort=an.idanimation&idanimation='.$row['idanimation'].'&idanimjob='.$idanimjob; ?>"><?php echo $row['pnom'].' '.$row['pprenom'] ?><?php if ($row['idpeople'] == '') { echo 'select';}?></a> <?php if (!empty($row['idpeople'])) { ?>&nbsp; <a href="<?php echo NIVO.'data/people/adminpeople.php?act=show&idpeople='.$row['idpeople'];?>" target="_blank"><img src="<?php echo STATIK ?>illus/icon_profile.gif" border="0" width="15" height="12"></a><?php } ?>
					<?php } ?>
				</td>
				<td class="<?php echo $classe; ?>"><input type="text" size="5" name="hin1" value="<?php echo ftime($row['hin1']); ?>" <?php echo $disable; ?>></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="5" name="hout1" value="<?php echo ftime($row['hout1']); ?>" <?php echo $disable; ?>></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="5" name="hin2" value="<?php echo ftime($row['hin2']); ?>" <?php echo $disable; ?>></td>
				<td class="<?php echo $classe; ?>"><input type="text" size="5" name="hout2" value="<?php echo ftime($row['hout2']); ?>" <?php echo $disable; ?>></td>
				<td class="<?php echo $classe; ?>">
				<?php
					$anim = new coreanim($row['idanimation']);
					$timetot =  $anim->hprest;
					echo $timetot;
					$timetotx = $timetotx + $timetot;
				 ?>
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
				<td class="<?php echo $classe; ?>" width="13"><a href="<?php echo 'adminanim.php?act=showmission&idanimation='.$row['idanimation'].'&idanimjob='.$row['idanimjob'];?>" target="_top"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
			<?php if ($disable != 'disabled') { ?>
				</form>
			<?php } ?>
		</tr>
	<?php } ?>
		<tr>
			<th class="<?php echo $classe; ?>" colspan="15"></th>
			<th class="<?php echo $classe; ?>"><?php echo $timetotx; ?></th>
			<th class="<?php echo $classe; ?>" colspan="3"></th>
		</tr>
	<?php CodeAddForm($down, $idanimjob, $classe); ?>
	</table>
<br>
