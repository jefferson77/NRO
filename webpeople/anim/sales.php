<div class="news">
	<table width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="fulltitre" colspan="2"><?php echo $tool_01; ?><br><?php echo $sales_00; ?></td>
		</tr>
		<tr>
			<td class="newstit">1. <?php echo $sales_menu_01; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li> <?php echo $sales_menu_02; ?>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="newstit">2. <?php echo $sales_menu_03; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li><?php echo $sales_menu_04; ?>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="newstit">3. <?php echo $sales_menu_05; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li> <?php echo $sales_menu_06; ?>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="newstit">4. <?php echo $sales_menu_07; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li> <?php echo $sales_menu_08; ?>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="newstit">5. <?php echo $sales_menu_09; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li> <?php echo $sales_menu_10; ?>
				</ul>
			</td>
		</tr>

		<tr>
			<td class="newstxt">
				<?php echo $sales_menu_11; ?>
			</td>
		</tr>
	</table>
</div>
<div class="corps">
<?php 

### Recherche du jour à partir duquel on affiche les missions
	$date = weekdate(date("W") - (((date("D") == 'Mon') or (date("D") == 'Tue'))?'1':'0'), date("Y"));
	$jourencours = $date['mar'];

#/## Recherche du jour à partir duquel on affiche les missions
	if ($_SESSION['celsys'] == 'celsys') { # mode devel
		$quid = ' an.idpeople = '.$_SESSION['idpeople'].' AND an.datem <= "'.date("Y-m-d").'" AND (an.facturation < "2" OR an.facturation IS NULL) AND an.peopleonline < "1"';

	} else { #mode jobistes
		$quid = ' an.idpeople = '.$_SESSION['idpeople'].' AND an.datem >= "'.$jourencours.'" AND an.datem <= "'.date("Y-m-d").'" AND (an.facturation < "2" OR an.facturation IS NULL) AND an.peopleonline < "1"';
	}

# Recherche des résultats
$rows = $DB->getArray('SELECT
 	an.idanimation, an.datem, an.weekm, an.reference, an.hin1, an.hout1, an.hin2, an.hout2,
		an.kmpaye, an.kmfacture, an.frais, an.fraisfacture, an.isbriefing, an.produit, j.boncommande, an.facturation, an.peoplenote,
		an.ferie, an.idanimjob, an.livraisonpaye, an.livraisonfacture, an.peopleonline,
		an.standqualite , an.standnote , an.autreanim , an.autreanimnote , an.peoplenote , an.shopnote,
	j.genre,
	c.codeclient, c.societe AS clsociete, c.idclient, c.tel, c.fax,
	s.idshop, s.codeshop, s.societe AS ssociete, s.ville AS sville,
	p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.gsm, p.codepeople
FROM animation an
	LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
	LEFT JOIN agent a ON j.idagent = a.idagent
	LEFT JOIN client c ON j.idclient = c.idclient
	LEFT JOIN cofficer co ON j.idcofficer = co.idcofficer
	LEFT JOIN people p ON an.idpeople = p.idpeople
	LEFT JOIN shop s ON an.idshop = s.idshop
WHERE '.$quid.'
ORDER BY an.datem DESC');

?>
<br>
<table class="standard" border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
<?php
# -------  LISTING FRAIS TITRE --------
foreach ($rows as $row) {
	### Vérification si mission peut être affichée
		if ((($row['facturation'] > 1) and ($row['facturation'] != 3)) or ($row['peopleonline'] > '0') or ((date("D") == 'Tue') and (date("H") >= 13))) {
			$disable = 'disabled';
		} else {
			$disable = '';
		}
	#/## Vérification si mission peut être affichée
	if ($disable != 'disabled') { /* si page input */
		$i++;
?>
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=sale1&udpate=animation';?>" method="post">
			<input type="hidden" name="idanimation" value="<?php echo $row['idanimation'] ;?>">
			<tr bgcolor="#DDDDDD">
				<th class="contenu">Job</th>
				<th class="contenu"><?php echo $sales_01; ?></th>
				<th class="contenu"><?php echo $sales_02; ?></th>
				<th class="contenu"><?php echo $sales_03; ?></th>
				<th class="contenu">P.O.S.</th>
				<th class="contenu">Am In</th>
				<th class="contenu">Am Out</th>
				<th class="contenu">Pm In</th>
				<th class="contenu">Pm Out</th>
				<th class="contenu">KM</th>
				<th class="contenu"></th>
			</tr>
			<tr bgcolor="#9CBECA">
				<td class="contenu"><?php echo $row['idanimjob']; ?></td>
				<td class="contenu"><?php echo $row['idanimation']; ?> <?php echo $row['produit'] ?></td>
				<td class="contenu"><?php echo $row['weekm']; ?></td>
				<td class="contenu"><?php echo fdate($row['datem']); ?></td>
				<td class="contenu"><?php echo $row['codeshop']; ?> - <?php echo $row['ssociete']; ?> - <?php echo $row['sville']; ?></td>
				<td class="contenu"><?php echo ftime($row['hin1']); ?></td>
				<td class="contenu"><?php echo ftime($row['hout1']); ?></td>
				<td class="contenu"><?php echo ftime($row['hin2']); ?></td>
				<td class="contenu"><?php echo ftime($row['hout2']); ?></td>
				<td class="contenu">
					<input type="text" size="3" name="kmpaye" value="<?php echo fnbr($row['kmpaye']); ?>" <?php echo $disable; ?>>
				</td>
				<td class="contenu" rowspan="2">
					<input type="submit" name="Modifier" value="OK">
				</td>
			</tr>
			<tr>
				<td colspan="10" class="contenu">
					<table border="0" class="standard" cellspacing="1" cellpadding="0" width="99%">
						<tr>
							<td>
								<table border="0" cellspacing="1" cellpadding="0" width="98%">
									<tr>
										<th class="vip">
											Stand
										</th>
										<td>
											<?php
											echo '<input type="radio" name="standqualite" value="2" '; if ($row['standqualite'] == '2') { echo 'checked';} echo'> + ';
											echo '<input type="radio" name="standqualite" value="1" '; if ($row['standqualite'] == '1') { echo 'checked';} echo'> +/- ';
											echo '<input type="radio" name="standqualite" value="0" '; if ($row['standqualite'] == '0') { echo 'checked';} echo'> -';
											?>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<textarea name="standnote" rows="2" cols="30" <?php echo $disable; ?>><?php echo $row['standnote']; ?></textarea>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<br>
										</td>
									</tr>
									<tr>
										<th class="vip">
											<?php echo $sales_04; ?>
										</th>
										<td>
											&nbsp;
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<textarea name="peoplenote" rows="2" cols="30" <?php echo $disable; ?>><?php echo $row['peoplenote']; ?></textarea>
										</td>
									</tr>
								</table>
							</td>
							<td valign="top">
								<table border="0" cellspacing="1" cellpadding="0" width="98%">
									<tr>
										<th class="vip">
											<?php echo $sales_05; ?>
										</th>
										<td>
											&nbsp;
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<textarea name="shopnote" rows="2" cols="30" <?php echo $disable; ?>><?php echo $row['shopnote']; ?></textarea>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<br>
										</td>
									</tr>
									<tr>
										<th class="vip">
											<?php echo $sales_06; ?>
										</th>
										<td>
											<?php
											echo '<input type="radio" name="autreanim" value="1" '; if ($row['autreanim'] == '1') { echo 'checked';} echo'> '.$sales_07;
											echo '<input type="radio" name="autreanim" value="0" '; if ($row['autreanim'] == '0') { echo 'checked';} echo'> '.$sales_08;
											?>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<textarea name="autreanimnote" rows="2" cols="30" <?php echo $disable; ?>><?php echo $row['autreanimnote']; ?></textarea>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</form>
		<tr>
			<td colspan="2">
				<br>
			</td>
		</tr>
		<?php
		$produits = $DB->getArray("SELECT * FROM `animproduit` WHERE `idanimation` = ".$row['idanimation']);

		if (count($produits) > 0) { ?>
			<tr bgcolor="#9CBECA">
				<td colspan="11" class="contenu">
					<table border="1" cellspacing="1" cellpadding="0" align="center" width="99%">
						<tr>
							<th class="vip2">
								<?php echo $sales_prod_01; ?>
							</th>
							<th class="vip2">
								<?php echo $sales_prod_02; ?>
							</th>
							<th class="vip2">
								<?php echo $sales_prod_03; ?>
							</th>
							<th class="vip2">
								<?php echo $sales_prod_04; ?>
							</th>
							<th class="vip2">
								<?php echo $sales_prod_05; ?> ?
							</th>
							<th class="vip2">
								<?php echo $sales_prod_06; ?>
							</th>
							<th class="vip2">
							</th>
						</tr>
						<?php
						$i = 0; # Pour les couleurs altern?es
						foreach ($produits as $prod) {
							$i++;
						?>
							<form action="<?php echo $_SERVER['PHP_SELF'].'?act=sale1&udpate=produit';?>" method="post">
								<input type="hidden" name="idanimproduit" value="<?php echo $row['idanimproduit'] ;?>">
								<tr bgcolor="#9CBECA">
									<td align="center">
										<?php echo $prod['types']; ?>
									</td>
									<td align="center">
										<input type="text" size="6" name="produitin" value="<?php echo fnbr($prod['produitin']); ?>" <?php echo $disable; ?>>
									</td>
									<td align="center">
										<input type="text" size="6" name="produitend" value="<?php echo fnbr($prod['produitend']); ?>" <?php echo $disable; ?>>
									</td>
									<td align="center">
										<input type="text" size="6" name="ventes" value="<?php echo fnbr($prod['ventes']); ?>" <?php echo $disable; ?>>
									</td>
									<td align="center">
										<?php echo '<input type="checkbox" name="produitno" value="no" '; if ($prod['produitno'] == 'no') { echo 'checked';} echo'>'; ?> <?php echo $sales_07;?>
									</td>
									<td align="center">
										<input type="text" size="10" name="degustation" value="<?php echo $prod['degustation']; ?>" <?php echo $disable; ?>>
									</td>
									<td>
										<input type="submit" name="Modifier" value="OK">
									</td>
								</tr>
							</form>
							<?php
							$compt++;
						} # /while
						?>
					</table>
				</td>
			</tr>
		<?php } /* si il y a des produit */ ?>
		<tr bgcolor="#FFFFFF">
			<td align="center" colspan="12">
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=sale1&udpate=cloturer';?>" method="post">
					<input type="hidden" name="idanimation" value="<?php echo $row['idanimation'] ;?>">
					<input type="submit" name="Modifier" value="<?php echo $sales_09;?>">
				</form>
			</td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td colspan="10">
				<br><br><hr><br><br>
			</td>
		</tr>
	<?php } ?>
<?php } ?>
</table>
<br>
</div>
