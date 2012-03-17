<?php
################### Code PHP ########################
if (!empty($idvipjob)) {
	$detail = new db();
	$detail->inline("SELECT * FROM `vipjob` WHERE `idvipjob` = $idvipjob");
	$row = mysql_fetch_array($detail->result) ; 
	$etat=$row['etat'];
}
################### Fin Code PHP ########################
################### Code PHP ########################
# Si update par Next ou Previous
switch ($_POST['Modifier']) {
	case "Previous":
		$idvip = $_POST['idprev'];
	break;
	case "Next":
		$idvip = $_POST['idnext'];
}
#/ Si update par Next ou Previous
if (!empty($idvip)) {
	# recherche Job en cours
		$detail = new db();
		$detail->inline("SELECT * FROM `vipdevis` WHERE `idvip` = $idvip");
		$infos = mysql_fetch_array($detail->result) ; 
		$idpeople=$infos['idpeople'];
		$vipdate = $infos['vipdate'];
		$vipactivite = addslashes($infos['vipactivite']);
	#/ recherche Job en cours
	# recherche Job Next : date >=; vipactivite >= idvip >
		# recherche Job Next : date = ; vipactivite= ; idvip >
		$detail0 = new db();
		$detail0->inline("SELECT * FROM `vipdevis` WHERE `idvipjob` = $idvipjob AND `vipdate` = '$vipdate' AND `vipactivite` = '$vipactivite' AND `idvip` > $idvip ORDER BY vipdate ASC, vipactivite ASC, idvip ASC LIMIT 1 ");
		$infosnext = mysql_fetch_array($detail0->result) ; 
		$idnext=$infosnext['idvip'];
		# recherche Job Next : date = ; vipactivite > 
				if ($idnext == '') {
					$detail1 = new db();
					$detail1->inline("SELECT * FROM `vipdevis` WHERE `idvipjob` = $idvipjob AND `vipdate` = '$vipdate'  AND `vipactivite` > '$vipactivite' ORDER BY vipdate ASC, vipactivite ASC, idvip ASC LIMIT 1 ");
					$infosnext1 = mysql_fetch_array($detail1->result) ;
					$idnext=$infosnext1['idvip'];
				}

			# recherche Job Next : date >
				if ($idnext == '') {
					$detail1 = new db();
					$detail1->inline("SELECT * FROM `vipdevis` WHERE `idvipjob` = $idvipjob AND `vipdate` > '$vipdate' ORDER BY vipdate ASC, vipactivite ASC, idvip ASC LIMIT 1 ");
					$infosnext1 = mysql_fetch_array($detail1->result) ;
					$idnext=$infosnext1['idvip'];
				}
			#/ recherche Job Next : date =
	#/ recherche Job Next >=

	# recherche Job Next : date <=; vipactivite <= idvip <
		# recherche Job Next : date = ; vipactivite= ; idvip <
		$detail2 = new db();
		$detail2->inline("SELECT * FROM `vipdevis` WHERE `idvipjob` = $idvipjob AND `vipdate` = '$vipdate' AND `vipactivite` = '$vipactivite' AND `idvip` < $idvip ORDER BY vipdate DESC, vipactivite DESC, idvip DESC LIMIT 1 ");
		$infosprev = mysql_fetch_array($detail2->result) ; 
		$idprev=$infosprev['idvip'];
			# recherche Job Next : date = ; vipactivite <
				if ($idprev == '') {
					$detail3 = new db();
					$detail3->inline("SELECT * FROM `vipdevis` WHERE `idvipjob` = $idvipjob AND `vipdate` = '$vipdate'  AND `vipactivite` < '$vipactivite' ORDER BY vipdate DESC, vipactivite DESC, idvip DESC LIMIT 1 ");
					$infosprev1 = mysql_fetch_array($detail3->result) ;
					$idprev=$infosprev1['idvip'];
				}
			#/ recherche Job Next : date = ; vipactivite < 
			# recherche Job Previous : date <
				if ($idprev == '') {
					$detail3 = new db();
					$detail3->inline("SELECT * FROM `vipdevis` WHERE `idvipjob` = $idvipjob AND `vipdate` < '$vipdate' ORDER BY vipdate DESC, vipactivite DESC, idvip DESC LIMIT 1 ");
					$infosprev1 = mysql_fetch_array($detail3->result) ;
					$idprev=$infosprev1['idvip'];
				}
			#/ recherche Job Previous : date <
	#/ recherche Job Previous <=

}
################### Fin Code PHP ########################

?>

<?php
################### Code PHP ########################
	$vipdevis = new db();
	$vipdevis->inline("SELECT * FROM `vipdevis` WHERE `idvip` = $idvip;");
	$infos = mysql_fetch_array($vipdevis->result) ; 
################### Fin Code PHP ########################
?>
<?php #  Corps de la Page 
?>
<div id="minicentervip">
<br>
<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=missionupdate&etat=0';?>" method="post">

	<input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>"> 
	<input type="hidden" name="idvip" value="<?php echo $idvip;?>"> 
	<input type="hidden" name="idprev" value="<?php echo $idprev;?>"> 
	<input type="hidden" name="idnext" value="<?php echo $idnext;?>"> 

	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
		<tr>
			<td valign="top" colspan="2">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip2" width="60">
							Date
						</th>
						<td>
							<input type="text" size="12" name="vipdate" value="<?php echo fdate($infos['vipdate']) ?>">
						</td>

						<th class="vip2" width="60">
							Lieux
						</th>
						<?php
						$selection = '';
						$idshop = '';
						$infosshop = '';
						# recherche client et cofficer
						if (!empty($infos['idshop'])) {
							$idshop = $infos['idshop'];
							$detailsh = new db();
							$detailsh->inline("SELECT * FROM `shop` WHERE `idshop`=$idshop");
							$infosshop = mysql_fetch_array($detailsh->result) ; 
						}
						if ($infosshop['codeshop'] == '') {$selection = '';}
						if (!empty($infosshop['codeshop'])) {$selection = $infosshop['codeshop'];}
						?>
						<td>
							<?php echo $selection; ?>
						</td>
						<th class="vip2" width="60">
							Activit&eacute;
						</th>
						<td>
							<input type="text" size="20" name="vipactivite" value="<?php echo $infos['vipactivite']; ?>">
						</td>
						<th class="vip2" width="60">
							<?php if ($infospeople['sexe'] != $row['sexe'])
							{ 
								$colorsex1 = '<Font color="red">';
								$colorsex2 = '</Font>';
							} ?>
							Sexe
						</th>
						<td>
							<?php 
							echo '
							<select name="sexe">
								<option value="f"
							';	
								if (($infos['sexe'] == 'f') OR ($infos['sexe'] == '')) {echo 'selected';}
							echo '
								>F</option>
								<option value="m"
							';	
								if ($infos['sexe'] == 'm') {echo 'selected';}
							echo '
								>M</option>
								<option value="x"
							';	
								if ($infos['sexe'] == 'x') {echo 'selected';}
							echo '
								>X</option>
							</select>
							';
							?>
						</td>
					</tr>
				</table>		
			</td>
		</tr>
		<tr>
			<th class="space4" colspan="2">
				&nbsp;
			</th>
		</tr>
		<tr>
			<td valign="top" colspan="2">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip2" width="60">
							Prestations 
						</th>
						<th class="vip2" width="60">
							IN
						</th>
						<th class="vip2" width="60">
							OUT 
						</th>
						<th class="vip2" width="60">
							Brk 
						</th>
						<th class="vip2" width="60">
							Night 
						</th>
						<th class="vip2" width="60">
							TS 
						</th>
						<th class="vip2" width="60">
							Fts 
						</th>
						<th class="vip2" width="60">
							H sup (150%) 
						</th>
						<th class="vip2" width="60">
							D Dim. (200%)
						</th>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td align="center">
							&nbsp;
						</td>
						<td align="center">
							<input type="text" size="8" name="vipin" value="<?php echo ftime($infos['vipin']) ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="vipout" value="<?php echo ftime($infos['vipout']) ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="brk" value="<?php echo fnbr($infos['brk']); ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="night" value="<?php echo fnbr($infos['night']); ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="ts" value="<?php echo fnbr($infos['ts']); ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="fts" value="<?php echo fnbr($infos['fts']); ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="h150" value="<?php echo fnbr($infos['h150']); ?>">
						</td>
						<td align="center">
							<input type="checkbox" name="h200" value="1" <?php if ($infos['h200'] == 1) echo 'checked'; ?>>
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
				</table>		
			</td>
		</tr>
		<tr>
			<th class="space4" colspan="2">
				&nbsp;
			</th>
		</tr>
		<tr>
			<td valign="top" colspan="2">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip2" width="60">
							D&eacute;placement 
						</th>
						<th class="vip2" width="60">
							KM
						</th>
						<th class="vip2" width="60">
							Fkm 
						</th>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td align="center">
							&nbsp;
						</td>
						<td align="center">
							<input type="text" size="8" name="km" value="<?php echo fnbr($infos['km']); ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="fkm" value="<?php echo fnbr($infos['fkm']); ?>">
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
				</table>		
			</td>
		</tr>
		<tr>
			<th class="space4" colspan="2">
				&nbsp;
			</th>
		</tr>
		<tr>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip2" width="60">
							Location
						</th>
						<th class="vip2" width="60">
							Unif.
						</th>
						<th class="vip2" width="60">
							Loc
						</th>
						<td>
							&nbsp;
						</td>
					</tr>
					<tr>
						<td align="center">
							&nbsp;
						</td>
						<td align="center"><?php if ($infos['unif'] == '') {$infos['unif'] = $infos['net'];} ?>
							<input type="text" size="8" name="unif" value="<?php echo fnbr($infos['unif']); ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="loc1" value="<?php echo fnbr($infos['loc1']); ?>">
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
				</table>		
			</td>
			<td valign="top" rowspan="3">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip2" width="60">
							Briefing particulier 
						</th>
					</tr>
					<tr>
						<td align="center">
							<textarea name="notes" rows="3" cols="40"><?php echo $infos['notes']; ?></textarea>
						</td>
					</tr>
				</table>		
			</td>
		</tr>
		<tr>
			<th class="space4">&nbsp;</th>
		</tr>
		<tr>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip2" width="60">Frais</th>
						<th class="vip2" width="60">Cat.</th>
						<th class="vip2" width="60">Disp</th>
						<th class="vip2" width="60">FR</th>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td align="center">&nbsp;</td>
						<td align="center">
							<input type="text" size="8" name="cat" value="<?php echo fnbr($infos['cat']); ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="disp" value="<?php echo fnbr($infos['disp']); ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="fr1" value="<?php echo fnbr($infos['fr1']); ?>">
						</td>
						<td>&nbsp;</td>
					</tr>
				</table>		
			</td>
		</tr>
	</table>	
	<br>
	<div align="center">
	<table border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
		<tr>
			<td align="center"> <input type="submit" name="Modifier" value="Modifier"></td>
			<?php if (!empty($idprev)) { ?>
				<td align="center"> 
					<input type="submit" name="Modifier" value="Previous">
				</td>
			<?php }?>
			<?php if (!empty($idnext)) { ?>
				<td align="center">
					<input type="submit" name="Modifier" value="Next">
				</td>
			<?php } ?>
		</tr>
	</table>
	</div>
</form>
