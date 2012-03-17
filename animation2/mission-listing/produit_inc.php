	<table class="<?php echo $classe; ?>" border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
		<tr bgcolor="#B9C2C5">
			<td class="<?php echo $classe; ?>" colspan="2">
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=listing&down='.$down.'&listtype=9&action=skip';?>" method="post">
					<input type="text" size="15" name="produit">
					<input type="hidden" name="idanimjob" value="<?php echo $idanimjob;?>">
					<input type="submit" name="modallprod" value="Add">
				</form>
			</td>
			<td class="<?php echo $classe; ?>" colspan="5">Pour ajouter un produit a toutes les animations du job</td>
		</tr>
		<tr>
			<th class="<?php echo $classe; ?>">Id M.</th>
			<th class="<?php echo $classe; ?>">Produit</th>
			<th class="<?php echo $classe; ?>">Week</th>
			<th class="<?php echo $classe; ?>">Date</th>
			<th class="<?php echo $classe; ?>">People</th>
			<th class="<?php echo $classe; ?>">Shop</th>
			<th class="<?php echo $classe; ?>"></th>
		</tr>
<?php 	while ($row = mysql_fetch_array($listing->result)) {		#> Changement de couleur des lignes #####>>####
		$i++;
		if (fmod($i, 2) == 1) {
			echo '<tr bgcolor="#9CBECA">';
		} else {
			echo '<tr bgcolor="#8CAAB5">';
		}
		#< Changement de couleur des lignes #####<<####
	?>
			<td class="<?php echo $classe; ?>"><?php echo $row['idanimation'] ?></td>
			<td class="<?php echo $classe; ?>"><?php echo $row['produit'] ?></td>
			<td class="<?php echo $classe; ?>"><?php echo $row['weekm'].' '.$row['yearm'] ?></td>
			<td class="<?php echo $classe; ?>"><?php echo fdate($row['datem']) ?></td>
       
			<td class="<?php echo $classe; ?>">
                        <?php
                        if (!empty($row['idpeople'])) {
                            echo '<img src="'.STATIK.'illus/'.$row['lbureau'].'.gif" alt="'.$row['lbureau'].'" width="12" height="9"> '.$row['codepeople'].' - '.$row['pnom'].' '.$row['pprenom'].' <a href="'.NIVO.'data/people/adminpeople.php?act=show&idpeople='.$row['idpeople'].'" target="_blank"><img src="'.STATIK.'illus/icon_profile.gif" border="0" width="15" height="12"></a>';
                        }
                        ?>
                        
			</td>
			<td class="<?php echo $classe; ?>"><?php echo $row['codeshop']; ?> - <?php echo $row['ssociete']; ?> - <?php echo $row['sville']; ?></td>
			<td class="<?php echo $classe; ?>" width="13"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=showmission&idanimation='.$row['idanimation'].'&idanimjob='.$row['idanimjob'];?>" target="_top"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
		</tr>
<?php
$prods = new db();
$prods->inline("SELECT types FROM animproduit WHERE idanimation = '".$row['idanimation']."' ORDER BY types");

while ($pr = mysql_fetch_array($prods->result)) {
	$produits .= '<span style="background-color: #FC6;padding: 3px; margin: 0 2px 0 2px;">'.$pr['types'].'</span> ';
}

if (!empty($produits)) {
?>
		<tr bgcolor="#B9C2C5">
			<td class="<?php echo $classe; ?>" colspan="2">
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=listing&down='.$down.'&listtype=9&action=skip';?>" method="post">
					<input type="text" size="15" name="produit">
					<input type="hidden" name="idanimation" value="<?php echo $row['idanimation'];?>">
					<input type="submit" name="modprod" value="Add">
				</form>
			</td>
			<td class="<?php echo $classe; ?>" colspan="5"><b><?php echo $produits; ?></b></td>
		</tr>
<?php
}

	unset($produits);
} ?>
	</table>