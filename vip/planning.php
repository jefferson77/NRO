<div id="centerzonelarge">
<?php
# Vars #########
if (!isset($_GET['listing'])) $_GET['listing'] = '';
if (!empty($_GET['sort'])) $_SESSION['missionsort'] = $_GET['sort'];
if (!isset($_SESSION['supfields'])) $_SESSION['supfields'] = array();

### Query
if ($_GET['action'] != "skip") {
	$quid = $DB->MAKEsearch(array(
				'a.idagent' => 'idagent',
				'm.idvip' => 'idvip',
				'j.idvipjob' => 'idvipjob',
				'j.reference' => 'reference',
				'p.pprenom' => 'pprenom',
				'p.pnom' => 'pnom',
				'p.codepeople' => 'codepeople',
				'p.lbureau' => 'lbureau',
				'c.idclient' => 'idclient',
				'c.societe' => 'csociete',
				's.idshop' => 'idshop',
				's.societe' => 'shsociete',
				's.ville' => 'shville',
				'm.vipactivite' => 'activite',
				'm.vipdate' => 'vipdate')
			);

	# Recherche des TO DO
	if (($_GET['listing'] == 'missing') or (!empty($_POST['todo']))) {
		if (!empty($quid)) { $quid .= " AND "; $quod .= " ET "; }
		$quid .= " (m.idpeople IS NULL OR m.idpeople < 1) AND j.etat < 14 ";
		$quod .= ' + to do';
		$sort = 'm.vipdate, m.vipactivite, m.vipin, m.vipout';
	}

	### Etats Facturation
	if (is_array($_POST['etat'])) {

		if (!empty($quid)) { $quid .= " AND "; $quod .= " ET "; }

			$quid .= "(";
			$quid1 = '';

			foreach ($_POST['etat'] as $val) {
				if (!empty($val)) {
					if (!empty($quid1)) {$quid1 .= " OR ";}
					switch ($val) {
						case "0":
						case "1":
						case "2":
							$quid1 .= "j.etat = ".$val;
						break;
						case "11":
							$quid1 .= "j.etat = 11 OR j.etat = 12";
						break;
						case "13":
							$quid1 .= "j.etat = 13 OR j.etat = 14";
						break;
						case "15":
							$quid1 .= "j.etat >= 15";
						break;
					}
					$quod .= " etat = ".$val;
				}
			}

		$quid .= ''.$quid1.")";
	}

	if (!empty($quid)) { $quid .= " AND "; $quod .= " ET "; }
	$quid .= " j.etat != 2";

	if (empty($sort)) { $sort = 'm.vipdate, m.vipin, m.vipout';}

	$_SESSION['missionquid'] = $quid;
	$_SESSION['missionsort'] = $sort;

	if (is_array($_REQUEST['supfields'])) $_SESSION['supfields'] = $_REQUEST['supfields'];
}

# Recherche des résultats
$rows = $DB->getArray('SELECT
		m.idvip, m.vipdate, m.idvipjob, m.idpeople, m.vipin, m.vipout, m.brk, m.datecontrat, m.vipactivite, m.matospeople,
		j.idclient, j.reference, j.etat,
		a.prenom,
		p.pprenom, p.pnom, m.sexe, p.gsm, p.ndate,
		c.societe AS clsociete,
		s.societe AS ssociete, s.ville
	FROM vipmission m
		LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob
		LEFT JOIN agent a ON j.idagent = a.idagent
		LEFT JOIN client c ON j.idclient = c.idclient
		LEFT JOIN people p ON m.idpeople = p.idpeople
		LEFT JOIN shop s ON m.idshop = s.idshop
	WHERE '.$_SESSION['missionquid'].'
	ORDER BY '.$_SESSION['missionsort']);

?>
<fieldset>
	<legend><b>Planning des VIPs</b></legend>
	Votre Recherche : <?php echo $_SESSION['missionquid']; ?> ( <?php echo count($rows); ?> )
</fieldset>

<?php if (count($rows) != 0) { ?>
	<div align="center">
		<form action="<?php echo NIVO ?>print/vip/planning/planning.php" method="post" target="_blank">
			<input type="submit" class="btn printr01"><br>
			Afficher les Gsm : <input type="radio" name="gsm" value="1"> Oui <input type="radio" name="gsm" value="0"> Non<br>
			Par People : <input type="checkbox" name="split_people" value="oui">
		</form>
	</div>
<?php } ?>
<br>
	<table class="rowstyle-alt no-arrow" border="0" width="90%" cellspacing="1" align="center">
		<thead>
			<tr>
				<th><a href="?act=planning&amp;action=skip&amp;sort=m.idvip">N&deg;</a></th>
				<th><a href="?act=planning&amp;action=skip&amp;sort=j.idvipjob">Job</a></th>
				<th><a href="?act=planning&amp;action=skip&amp;sort=m.vipdate,m.vipin,m.vipout">Date</a></th>
				<th><a href="?act=planning&amp;action=skip&amp;sort=j.reference">R&eacute;f&eacute;rence</a></th>
				<th><a href="?act=planning&amp;action=skip&amp;sort=a.prenom">Assi.</a></th>
				<th><a href="?act=planning&amp;action=skip&amp;sort=m.sexe">S</a></th>
				<th><a href="?act=planning&amp;action=skip&amp;sort=p.pnom">people</a></th>
	<?php if (in_array("ndate", $_SESSION['supfields'])): ?>
				<th><a href="?act=planning&amp;action=skip&amp;sort=p.ndate">Date Naissance</a></th>
	<?php endif ?>
				<th>GSM</th>
				<th>Situ</th>
				<th><a href="?act=planning&amp;action=skip&amp;sort=c.idclient">code</a> &nbsp; <a href="?act=planning&action=skip&sort=c.clsociete">Client</a></th>
				<th><a href="?act=planning&amp;action=skip&amp;sort=m.vipin">Heures</a></th>
				<th>H Pay</th>
				<th>frais</th>
				<th>Cont</th>
				<th><a href="?act=planning&amp;action=skip&amp;sort=s.societe">Lieux</a></th>
				<th><a href="?act=planning&amp;action=skip&amp;sort=m.vipactivite">Activit&eacute;</a></th>
				<th></th>
				<th>Etat</th>
				<th>J</th>
				<th>M</th>
			</tr>
		</thead>
		<tbody>
<?php
$totpay = 0;
$totfrais = 0;
foreach ($rows as $i => $row) {
	$fich = new corevip ($row['idvip']);
	echo '<tr bgcolor="'.((fmod($i, 2) == 1)?'#9CBECA':'#8CAAB5').'">';
?>
			<td><?php echo $row['idvip'] ?></td>
			<td><?php echo $row['idvipjob'] ?></td>
			<td><?php echo fdate($row['vipdate']) ?></td>
			<td><?php echo $row['reference'] ?></td>
			<td><?php echo $row['prenom'] ?></td>
			<td><?php echo $row['sexe'] ?></td>
			<td><?php echo $row['pprenom'].' '.$row['pnom'] ?></td>
<?php if (in_array("ndate", $_SESSION['supfields'])): ?>
			<td><?php echo fdate($row['ndate']) ?></td>
<?php endif ?>
			<td><?php echo $row['gsm'] ?></td>
			<td>---</td>
			<td><?php echo $row['idclient']; ?> - <?php echo $row['clsociete']; ?></td>
			<td><?php echo ftime($row['vipin']) ?> - <?php echo ftime($row['vipout']) ?></td>
			<td><?php echo $fich->thpaye; $totpay += $fich->thpaye ; ?></td>
			<td align="right"><?php echo feuro($fich->fraispay); $totfrais += $fich->fraispay; ?></td>
			<td><?php echo fdate($row['datecontrat']) ?></td>
			<td><?php echo $row['ssociete'].' ('.$row['ville'].' )' ; ?></td>
			<td><?php echo $row['vipactivite'] ?></td>
			<td>
				<?php
				if ($row['idpeople'] > 1) {
					if ($row['matospeople'] == 0) { echo '<img src="'.STATIK.'illus/taille-t-shirt-m-rouge.gif" alt="Supplier" width="16" height="14" border="0">'; }
					if ($row['matospeople'] == 1) { echo '<img src="'.STATIK.'illus/taille-t-shirt-m-vert.gif" alt="Supplier" width="16" height="14" border="0">'; }
					if ($row['matospeople'] == 2) { echo '<img src="'.STATIK.'illus/taille-t-shirt-m-orange.gif" alt="Supplier" width="16" height="14" border="0">'; }
				}
				?>
			</td>
			<td>
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
						echo '<font color="white"> Admin';
					break;
					default :
						echo '<font color="black"> Arch';
					break;
				}
				?>
					</font>
			</td>
			<td><a href="<?php echo $_SERVER['PHP_SELF'].'?act=show&idvipjob='.$row['idvipjob'].'&etat='.$row['etat'];?>"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
			<td>
				<?php if (($row['etat'] >= 1) and ($row['etat'] < 15)) { ?>
					<form action="adminvip.php?act=showmission&idvip=<?php echo $row['idvip'];?>" method="post" target="_parent">
						<input type="submit" name="Modifier" value="M">
					</form>
				<?php } ?>
				<?php if ($row['etat'] >= 15 ) { ?>
				<form action="adminvip.php?act=showmission&idvip=<?php echo $row['idvip'];?>" method="post" target="_parent">
					<input type="submit" name="Modifier" value="V">
				</form>
				<?php } ?>

			</td>
		</tr>
<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="<?php echo 11 + count($_SESSION['supfields']) ?>">&nbsp;</th>
				<th><?php echo $totpay ?></th>
				<th align="right"><?php echo feuro($totfrais) ?></th>
				<th colspan="7">&nbsp;</th>
			</tr>
		</tfoot>
	</table>
</div>
