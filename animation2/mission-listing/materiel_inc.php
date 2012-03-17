<?php
if ($down == 'down') {
	$colspa = 9;
	$colspa2 = 2;
} else {
	$colspa = 11;
	$colspa2 = 3;
}
###############################################################################################################################
### Barre des titres et tri ##################################################################################################
#############################################################################################################################
$cURL = $_SERVER['PHP_SELF'].'?act=listing&down='.$down.'&action=skip&idanimjob='.$idanimjob.'&viewall='.$_GET['viewall'].'&';
?>
	<table class="<?php echo $classe; ?>" border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
		<tr>
		<?php if ($down != 'down') { ?>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=an.idanimjob';?>">J</a></th>
		<?php } ?>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=an.idanimation';?>">M</a></th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=an.weekm';?>">S</a></th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=an.datem';?>">Date</a></th>
			<th class="<?php echo $classe; ?>">code - <a href="<?php echo $cURL.'sort=p.pnom, p.pprenom';?>">Nom People</a></th>
		<?php if ($down != 'down') { ?>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=c.idclient'; ?>">Client</a></th>
		<?php } ?>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=s.codeshop';?>">Lieux</a></th>
			<th class="<?php echo $classe; ?>">A In</th>
			<th class="<?php echo $classe; ?>">A Out</th>
			<th class="<?php echo $classe; ?>">P In</th>
			<th class="<?php echo $classe; ?>">P Out</th>
			<th class="<?php echo $classe; ?>">tot</th>
			<th class="<?php echo $classe; ?>">Sta</th>
			<th class="<?php echo $classe; ?>">Gob</th>
			<th class="<?php echo $classe; ?>">Ser</th>
			<th class="<?php echo $classe; ?>">Fo</th>
			<th class="<?php echo $classe; ?>">Cur</th>
			<th class="<?php echo $classe; ?>">Cui</th>
			<th class="<?php echo $classe; ?>">R&eacute;</th>
			<th class="<?php echo $classe; ?>">Au</th>
			<th class="<?php echo $classe; ?>"></th>
		<?php if ($down != 'down') { ?>
			<th class="<?php echo $classe; ?>">J</th>
		<?php } ?>
			<th class="<?php echo $classe; ?>">M</th>
		</tr>
<?php
###############################################################################################################################
### Datas ####################################################################################################################
#############################################################################################################################
	$xpnom = '';
	while ($row = mysql_fetch_array($listing->result)) {
		
		if (($row['facturation'] > 1) and ($row['facturation'] != 3)) {
			$disable = 'disabled';
		} else {
			$disable = '';
		}
		$i++;
		
		if ($disable != 'disabled') { ?>
			<form action="<?php echo $_SERVER['PHP_SELF'].'?act=listingmodif&down='.$down.'&action=skip&matos=yes';?>" method="post">
			<input type="hidden" name="idanimation" value="<?php echo $row['idanimation'] ;?>">
			<input type="hidden" name="idanimjob" value="<?php echo $row['idanimjob'] ;?>">
			<input type="hidden" name="idanimmateriel" value="<?php echo $row['idanimmateriel'] ;?>">
		<?php } 
		echo '<tr bgcolor="',(fmod($i, 2) == 1)?'#9CBECA':'#8CAAB5','">'; ?>
			<?php if ($down != 'down') { ?>
				<td class="<?php echo $classe; ?>"><?php echo $row['idanimjob'] ?></td>
			<?php } ?>
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
					$timetot =  $anim->hprest;
					echo $timetot;
					$timetotx = $timetotx + $timetot;
				 ?>
				 </td>
				<td class="<?php echo $classe; ?>">
					<input type="text" size="3" name="stand" value="<?php echo fnbr($row['stand']); ?>" <?php echo $disable; ?>>
					<?php $standx += $row['stand']; ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<input type="text" size="3" name="gobelet" value="<?php echo fnbr($row['gobelet']); ?>" <?php echo $disable; ?>>
					<?php $gobeletx += $row['gobelet']; ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<input type="text" size="3" name="serviette" value="<?php echo fnbr($row['serviette']); ?>" <?php echo $disable; ?>>
					<?php $serviettex += $row['serviette']; ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<input type="text" size="3" name="four" value="<?php echo fnbr($row['four']); ?>" <?php echo $disable; ?>>
					<?php $fourx += $row['four']; ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<input type="text" size="3" name="curedent" value="<?php echo fnbr($row['curedent']); ?>" <?php echo $disable; ?>>
					<?php $curedentx += $row['curedent']; ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<input type="text" size="3" name="cuillere" value="<?php echo fnbr($row['cuillere']); ?>" <?php echo $disable; ?>>
					<?php $cuillerex += $row['cuillere']; ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<input type="text" size="3" name="rechaud" value="<?php echo fnbr($row['rechaud']); ?>" <?php echo $disable; ?>>
					<?php $rechaudx += $row['rechaud']; ?>
				</td>
				<td class="<?php echo $classe; ?>">
					<input type="text" size="3" name="autre" value="<?php echo fnbr($row['autre']); ?>" <?php echo $disable; ?>>
					<?php $autrex += $row['autre']; ?>
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
### Totaux ###################################################################################################################
#############################################################################################################################
?>
		<tr>
			<th class="<?php echo $classe; ?>" colspan="<?php echo $colspa; ?>"></th>
			<th class="<?php echo $classe; ?>"><?php echo $timetotx; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $standx; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $gobeletx; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $serviettex; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $fourx; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $curedentx; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $cuillerex; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $rechaudx; ?></th>
			<th class="<?php echo $classe; ?>"><?php echo $autrex; ?></th>
			<th class="<?php echo $classe; ?>" colspan="<?php echo $colspa2; ?>"></th>
		</tr>
	</table>