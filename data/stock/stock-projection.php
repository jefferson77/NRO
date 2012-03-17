<?php
$classe = "etiq2";
$classe2 = "standard";
?>
<div id="centerzonelargewhite">
	<?php if ($_GET['act'] == 'stockprojectionsearch') { 
		$recherchemodele='
			SELECT 
			idstockf, reference
			FROM stockf
			WHERE idstockf >= 1
			ORDER BY reference
		';
		$listingfamille = new db();
		$listingfamille->inline("$recherchemodele;");
		while ($rowfamille = mysql_fetch_array($listingfamille->result)) { 
			$option2 .= '<option value="'.$rowfamille['idstockf'].'">'.substr($rowfamille['reference'], 0, 65).'</option>';
			$selected = '';
		}
	?>
		<fieldset class="blue">
			<legend class="blue">
				Recherche de Projection
			</legend>
			<form action="/data/stock/adminstock.php?act=stockprojectionresult&etat=1" method="post">
				<table class="etiq" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
					<tr>
						<td class="etiq">Famille</td>
						<td>
							<select name="idstockf" size="1">
								<?php echo $option2; ?>
							</select>
						<td>
					</tr>
					<tr>
						<td class="etiq">Entre le (date) </td>
		
						<td><input type="text" name="date1" id="date1" value=""> (20/11/2005)</td>
					</tr>
					<tr>
						<td class="etiq">Et le (date) </td>
		
						<td><input type="text" name="date2" id="date2" value=""> (31/12/2005)</td>
					</tr>
					<tr>
						<td align="center" colspan="2"><input type="submit" name="Rechercher" value="Rechercher"></td>
					</tr>
					<tr>
						<td colspan="2"><input type="reset" name="Reset" value="Reset"></td>
					</tr>
				</table>
			</form>
		</fieldset>
	<?php } ?>
	<?php 
	if ($_GET['act'] == 'stockprojectionresult') { 

		if (!empty($_POST['idstockf'])) {
			if (!empty($quid)) 
			{
				$quid .= " AND ";
				$quod .= " ET ";
			}	
			$quid .= "stf.idstockf = '".$_POST['idstockf']."'";
			$quod = $quod."idstockf = ".$_POST['idstockf'];
		}
		if (($_POST['date1'] != '') and $_POST['date2'] == '') { $_POST['date2'] = $_POST['date1']; }
		if (($_POST['date2'] != '') and $_POST['date1'] == '') { $_POST['date1'] = $_POST['date2']; }
	
		if ($_POST['date1'] != '') {
			if (!empty($quid)) 
			{
				$quid .= " AND ";
				$quod .= " ET ";
			}	
			$quid .= "ti.stockin >= '".fdatebk($_POST['date1'])."'";
			$quod = $quod."stockin >= ".fdatebk($_POST['date1']);
				$quid .= " AND ";
				$quod .= " ET ";
			$quid .= "ti.stockout <= '".fdatebk($_POST['date2'])."'";
			$quod = $quod."stockout <= ".fdatebk($_POST['date2']);
		}

		#### POUR le nombre de MODELE et FAMILLES
			$recherchecalcul1='
				SELECT 
				ma.idmatos, 
				stm.idstockm, 
				stf.idstockf 
				FROM matos ma
				LEFT JOIN stockm stm ON ma.idstockm = stm.idstockm 
				LEFT JOIN stockf stf ON stm.idstockf = stf.idstockf 
			';
	
			$recherchecalcul='
				'.$recherchecalcul1.'
				WHERE stf.idstockf = '.$_POST['idstockf'].'
				ORDER BY stm.idstockm 
			';
			$calcul = new db();
			$calcul->inline("$recherchecalcul;");
			while ($rowcalcul = mysql_fetch_array($calcul->result)) {
				$totmodel = 'tot'.$rowcalcul['idstockm'];
				$$totmodel++;
				$totalunit++;
			}
		#### POUR le nombre de MODELE et FAMILLES


		$recherche1='
			SELECT 
			ti.idticket, ti.idvip, ti.idanimation, ti.idvip, ti.idpeople, ti.dateticket, ti.stockout, ti.stockin, ti.suser, ti.inuse, ti.note,
			ma.idmatos, ma.codematos, ma.mnom, ma.autre, 
			ma.situation, ma.complet, ma.idstockm, 
			stm.idstockf, stm.reference,
			stf.reference AS reffamille, stf.stype,
			m.idvipjob, 
			p.pnom, p.pprenom, p.email, p.gsm, p.codepeople,
			j.reference AS jreference, 
			a.reference AS areference, me.produit  
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

		$stocksort='ti.stockout, ti.stockin, ma.idstockm';

		
		$recherche='
			'.$recherche1.'
			WHERE '.$quid.'
			 ORDER BY '.$stocksort.'
		';
		$ticket = new db();
		$ticket->inline("$recherche;");
		$FoundCount = mysql_num_rows($ticket->result);
		#	echo $recherche;
	
	?>
	<?php
	while ($row = mysql_fetch_array($ticket->result)) {
		$reffamille = $row['reffamille'];
		$tablekeymodele = $row['stockout'].'-sep-'.$row['stockin'].'-sep-'.$row['reference'].'-sep-'.$row['idstockm'];
		$table[$tablekeymodele]++; #compteur total par modele par date
		$tablekeyfamille = $row['stockout'].'-sep-'.$row['stockin'];
		$$tablekeyfamille++; #compteur total par famille par date
	 } ?>
		<fieldset class="blue">
			<legend class="blue">
				R&eacute;sultat de recherche de Projection (<?php echo $FoundCount; ?>)
			</legend>
			<table class="<?php echo $classe; ?>" border="0" width="55%" cellspacing="1" cellpadding="1" align="center">
			<tr>
				<td class="<?php echo $classe; ?>" colspan="11"><font size=+1><?php echo $reffamille; ?> (# <?php echo $totalunit; ?>) du <?php echo $_POST['date1']; ?> au <?php echo $_POST['date2']; ?></font> </td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>">#</td>
				<td class="<?php echo $classe; ?>">Du</td>
				<td class="<?php echo $classe; ?>">Au</td>
				<td class="<?php echo $classe; ?>">Mod&egrave;le</td>
				<td class="<?php echo $classe; ?>" width="70">par Mod&egrave;le</td>
				<td class="<?php echo $classe; ?>" width="70">par Famille</td>
			</tr>
			<?php 
				$stockouttemp = '';
				$stockintemp = '';
				if ($FoundCount > 0) {
					foreach ($table as $key => $value) { 
						$stocktemp = explode ('-sep-', $key);
						$stockout = $stocktemp[0];
						$stockin = $stocktemp[1];
						$reference = $stocktemp[2];
						$idstockm = $stocktemp[3];
						$totmodelunit = 'tot'.$idstockm;
						$totfamilleunit = $stockout.'-sep-'.$stockin;
			?>
						<tr id="line1">
							<td class="<?php echo $classe2; ?>"><?php echo $i; ?></td>
							<td class="<?php echo $classe2; ?>"><?php if (($stockout != $stockouttemp) or ($stockin != $stockintemp)) { echo fdate($stockout); } ?></td>
							<td class="<?php echo $classe2; ?>"><?php if (($stockout != $stockouttemp) or ($stockin != $stockintemp)) { echo fdate($stockin); } $stockouttemp = $stockout; $stockintemp = $stockin; ?></td>
							<td class="<?php echo $classe2; ?>"><?php echo $reference; ?></td>
							<td class="<?php echo $classe; ?>"><?php echo $value; ?>  / <?php echo $$totmodelunit; ?></td>
							<td bgcolor="#FF9900"><?php echo $$totfamilleunit; ?>  / <?php echo $totalunit; ?></td>
						</tr>
			<?php 
					}
				}	
			?>	
			</table>
		</fieldset>
		<?php
		###
		###
		###
		###
		### RECHERCHE des missions dans cet interval
		#	if (($_POST['date2'] != '') and ($_POST['date1'] != '') and ($FoundCount > 0)) { 
			if (($_POST['date2'] != '') and ($_POST['date1'] != '')) { 
				$quidvip .= "WHERE 1 AND ";
				$quidvip .= "m.vipdate >= '".fdatebk($_POST['date1'])."'";
				$quidvip .= " AND ";
				$quidvip .= "m.vipdate <= '".fdatebk($_POST['date2'])."'";
				$sortvip = 'j.datein, j.dateout, m.idvipjob';
				$recherchevip1='
					SELECT 
					m.idvip, m.vipdate, m.vipactivite, m.sexe, m.vipin, m.vipout, m.brk, m.night, m.ts, m.fts, m.ajust, m.vkm, m.vfkm, m.vcat, m.vdisp, m.vfr1, m.vfrpeople, m.datecontrat, m.metat, 
					j.idvipjob, j.reference, j.etat, j.datein, j.dateout,
					a.prenom, 
					p.pprenom, p.pnom, p.gsm, p.idpeople, p.nrnational, p.codepeople, 
					c.idclient, c.societe AS clsociete, 
					s.idshop, s.societe AS shsociete, s.ville 
					FROM vipmission m
					LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob
					LEFT JOIN agent a ON j.idagent = a.idagent
					LEFT JOIN client c ON j.idclient = c.idclient
					LEFT JOIN people p ON m.idpeople = p.idpeople
					LEFT JOIN shop s ON m.idshop = s.idshop
				';
				$recherchevip='
					'.$recherchevip1.'
					'.$quidvip.'
					 ORDER BY '.$sortvip.'
				';
				#	echo $recherchevip;
				# Recherche des résultats
				$listingvip = new db();
				$listingvip->inline("$recherchevip;");
				$FoundCountvip = mysql_num_rows($listingvip->result); 
				# $classe = "planning";
		?>
			<br>
			<fieldset class="blue">
				<legend class="blue">
					Missions pr&eacute;vues
				</legend>
				<table class="<?php echo $classe; ?>" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
					<tr>
						<td class="<?php echo $classe; ?>">Job</td>
						<td class="<?php echo $classe; ?>" colspan="2">Date</td>
						<td class="<?php echo $classe; ?>">R&eacute;f&eacute;rence</td>
						<td class="<?php echo $classe; ?>">Assi.</td>
						<td class="<?php echo $classe; ?>">Client</td>
						<td class="<?php echo $classe; ?>">Etat</td>
						<td class="<?php echo $classe; ?>">J</td>
						<td class="<?php echo $classe; ?>">Nbre missions</td>
					</tr>

					<?php
					$idvipjob = 0;
					while ($row = mysql_fetch_array($listingvip->result)) { 
						$i++;
					?>
						<?php if (($idvipjob != $row['idvipjob']) and ($idvipjob != 0)) { ?>
								<td class="<?php echo $classe; ?>"><?php echo $i; $i = 1; $i++; ?></td>
							</tr>
						<?php } ?>
						<?php if ($idvipjob != $row['idvipjob']) { ?>
							<tr>
								<td class="<?php echo $classe2; ?>"><?php echo $row['idvipjob'] ?></td>
								<td class="<?php echo $classe2; ?>"><?php echo fdate($row['datein']) ?></td>
								<td class="<?php echo $classe2; ?>"><?php echo fdate($row['dateout']) ?></td>
								<td class="<?php echo $classe2; ?>"><?php echo $row['reference'] ?></td>
								<td class="<?php echo $classe2; ?>"><?php echo $row['prenom'] ?></td>
								<td class="<?php echo $classe2; ?>"><?php echo $row['idclient']; ?> - <?php echo $row['clsociete']; ?></td>
								<td class="<?php echo $classe2; ?>">
									<?php
									switch ($row['etat']) {
										case "0": 
											echo '<font color="blue"> DEVIS';
										break;
										case "1": 
											echo '<font color="green"> JOB';
										break;
										case "2": 
											echo '<font color="red"> OUT';
										break;
										case "11": 
										case "12": 
											echo '<font color="peru"> READY';
										break;
										case "13": 
										case "14": 
											echo '<font color="#BBBBBB"> Admin';
										break;
										default :
											echo '<font color="black"> Arch';
										break;
									} 
									?>
										</font>
								</td>
								<td class="<?php echo $classe2; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=show&idvipjob='.$row['idvipjob'].'&etat='.$row['etat'];?>"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
						<?php 
							$idvipjob = $row['idvipjob'];
						} 
						?>
					<?php } ?>
								<td class="<?php echo $classe; ?>"><?php echo $i; $i = 1; $i++; ?></td>
							</tr>
				</table>
			</fieldset>
		<?php } ?>
	<?php } ?>
</div>