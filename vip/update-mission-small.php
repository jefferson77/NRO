<?php
################### Code PHP ########################
	$vipdevis = new db();
	$vipdevis->inline("SELECT * FROM `vipmission` WHERE `idvip` = $idvip;");
	$infos = mysql_fetch_array($vipdevis->result) ; 
echo $idvip;
echo $idvipjob;
################### Fin Code PHP ########################
?>
<?php #  Corps de la Page 
?>
<div id="minicentervip">
<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&etat=1&mod=missionupdate';?>" method="post">
	<input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>"> 
	<input type="hidden" name="idvip" value="<?php echo $idvip;?>"> 
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
		<tr>
			<td valign="top" colspan="3">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip2" width="70">
							Date
						</th>
						<td>
							<input type="text" size="12" name="vipdate" value="<?php echo fdate($infos['vipdate']) ?>">
						</td>
						<th class="vip2" width="70">
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
							$detailsh->inline("SELECT * FROM `shop` WHERE `idshop` = $idshop");
							$infosshop = mysql_fetch_array($detailsh->result) ; 
						}
						if ($infosshop['codeshop'] == '') {$selection = '';}
						if (!empty($infosshop['codeshop'])) {$selection = $infosshop['codeshop'];}
						?>
						<td>
							<?php echo $selection; ?>
						</td>
						<th class="vip2" width="70">
							Activit&eacute;
						</th>
						<td>
							<input type="text" size="20" name="vipactivite" value="<?php echo $infos['vipactivite']; ?>">
						</td>
						<th class="vip2" width="70">
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
						<td>
							&nbsp;
						</td>
					</tr>
				</table>		
			</td>
		</tr>
		<tr>
			<th class="space4" colspan="3">
				&nbsp;
			</th>
		</tr>
		<tr>
			<th class="space4" colspan="3">
				<hr>
			</th>
		</tr>
		<tr>
			<th class="space4" colspan="3">
				&nbsp;
			</th>
		</tr>
		<tr>
			<td valign="top" colspan="3">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip2" width="70">
							Prestations 
						</th>
						<th class="vip2" width="70">
							IN
						</th>
						<th class="vip2" width="70">
							OUT 
						</th>
						<th class="vip2" width="70">
							Brk 
						</th>
						<th class="vip2" width="70">
							Night 
						</th>
						<th class="vip2" width="70">
							Sup 
						</th>
						<th class="vip2" width="70">
							TS 
						</th>
						<th class="vip2" width="70">
							Fts 
						</th>
						<th class="vip2" width="70">
							200% 
						</th>
						<th class="vip2" width="70">
							Tot 
						</th>
						<th class="vip2" width="70">
							Ajust 
						</th>
						<th class="vip2" width="70">
							Pay&eacute; 
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
							<input type="text" size="8" name="brk" value="<?php echo $infos['brk']; ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="night" value="<?php echo $infos['night']; ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="h150" value="<?php echo $infos['h150']; ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="ts" value="<?php echo $infos['ts']; ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="fts" value="<?php echo $infos['fts']; ?>">
						</td>
						<td align="center">
							<input type="checkbox" name="h200" value="1" <?php if ($infos['h200'] == 1) echo 'checked'; ?>>
						</td>
						<td align="center">
							<?php
								$fich = new corevip ($infos['idvip']);
								echo $fich->thfact;
							?>
						</td>
						<td align="center">
							<input type="text" size="8" name="ajust" value="<?php echo $infos['ajust']; ?>">
						</td>
						<td align="center">
							<?php  echo $fich->thpaye;?>
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
				</table>		
			</td>
		</tr>
		<tr>
			<th class="space4" colspan="3">
				&nbsp;
			</th>
		</tr>
		<tr>
			<th class="space4" colspan="3">
				<hr>
			</th>
		</tr>
		<tr>
			<th class="space4" colspan="3">
				&nbsp;
			</th>
		</tr>
		<tr>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip2" width="70">
							D&eacute;placement 
						</th>
						<th class="vip2" width="20">
							KM
						</th>
						<th class="vip2" width="20">
							Fkm 
						</th>
						<th class="vip2" width="20">
							-
						</th>
						<th class="vip2" width="20">
							KM
						</th>
						<th class="vip2" width="20">
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
							<input type="text" size="8" name="km" value="<?php echo $infos['km']; ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="fkm" value="<?php echo $infos['fkm']; ?>">
						</td>
						<td>
							&nbsp;
						</td>
						<td align="center">
							<input type="text" size="8" name="vkm" value="<?php echo $infos['vkm']; ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="vfkm" value="<?php echo $infos['vfkm']; ?>">
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
				</table>		
			</td>
			<td valign="top">
				&nbsp;
			</td>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip2" width="70">
							Location
						</th>
						<th class="vip2" width="70">
							Unif.
						</th>
						<th class="vip2" width="70">
							Net. 
						</th>
						<th class="vip2" width="70">
							Loc1 
						</th>
						<th class="vip2" width="70">
							Loc2 
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
							<input type="text" size="8" name="unif" value="<?php echo $infos['unif']; ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="net" value="<?php echo $infos['net']; ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="loc1" value="<?php echo $infos['loc1']; ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="loc2" value="<?php echo $infos['loc2']; ?>">
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
				</table>		
			</td>
		</tr>
		<tr>
			<th class="space4" colspan="3">
				&nbsp;
			</th>
		</tr>
		<tr>
			<th class="space4" colspan="3">
				<hr>
			</th>
		</tr>
		<tr>
			<th class="space4" colspan="3">
				&nbsp;
			</th>
		</tr>
		<tr>
			<td valign="top" colspan="3">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip2" width="70">
							Frais
						</th>
						<th class="vip2" width="70">
							Cat. 
						</th>
						<th class="vip2" width="70">
							Disp 
						</th>
						<th class="vip2" width="70">
							FR
						</th>
						<td>
							&nbsp;
						</td>
						<th class="vip2" width="70">
							Frais Factur&eacute;
						</th>
						<th class="vip2" width="70">
							Cat. 
						</th>
						<th class="vip2" width="70">
							Disp 
						</th>
						<th class="vip2" width="70">
							FR
						</th>
						<th class="vip2" width="70">
							Frais people
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
							<input type="text" size="8" name="cat" value="<?php echo $infos['cat']; ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="disp" value="<?php echo $infos['disp']; ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="fr1" value="<?php echo $infos['fr1']; ?>" disabled>
						</td>
						<td>
							&nbsp;
						</td>
						<td align="center">
							&nbsp;
						</td>
						<td align="center">
							<input type="text" size="8" name="vcat" value="<?php echo $infos['vcat']; ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="vdisp" value="<?php echo $infos['vdisp']; ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="vfr1" value="<?php echo $infos['vfr1']; ?>">
						</td>
						<td align="center">
							<input type="text" size="8" name="vfrpeople" value="<?php echo $infos['vfrpeople']; ?>">
						</td>
						<td>
							&nbsp;
						</td>
					</tr>
				</table>		
			</td>
		</tr>
		<tr>
			<th class="space4" colspan="3">
				&nbsp;
			</th>
		</tr>
		<tr>
			<th class="space4" colspan="3">
				<hr>
			</th>
		</tr>
		<tr>
			<th class="space4" colspan="3">
				&nbsp;
			</th>
		</tr>
		<tr>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip2" width="70">
							Briefing particulier 
						</th>
					</tr>
					<tr>
						<td align="center">
							<textarea name="notes" rows="2" cols="30"><?php echo $infos['notes']; ?></textarea>
						</td>
					</tr>
				</table>		
			</td>
			<td valign="top">
				&nbsp;
			</td>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip2" width="70">
							Notes Frais people 
						</th>
					</tr>
					<tr>
						<td align="center">
							<textarea name="notefrpeople" rows="2" cols="30"><?php echo $infos['notefrpeople']; ?></textarea>
						</td>
					</tr>
				</table>		
			</td>
		</tr>
	</table>	
	<br>
	<div align="center">
		<input type="submit" name="Modifier" value="Modifier">
	</div>
</form>
