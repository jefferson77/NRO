<div id="centerzonelargewhite">
<?php 
switch ($_GET['skip']) {
	case "out": 
	### Deuxième étape : Afficher la liste des stocks correspondant a la recherche après SORT

		# VARIABLE SELECT
		if ($_GET['sort'] == '') {$sort = $_SESSION['stocksort'];}
		else {$sort = $_GET['sort'];}
		$attribfamille = $_GET['attribfamille'];
		if (!empty($quid)) 
		{
			$quid .= " AND ";
			$quod .= " ET ";
		}	
		$quid .= "WHERE ti.inuse = '1'";
		$quod .= "en usage = oui";

	break;

	case "skip": 
	### Deuxième étape : Afficher la liste des stocks correspondant a la recherche après SORT

		# VARIABLE skip
		if (!empty($_GET['skip'])) {
			$skip = $_GET['skip'];
		}
		else {$skip = 0;}
		$skipprev = $skip - 25;
		$skipnext = $skip + 25;

		# VARIABLE SELECT
		if ($_GET['sort'] == '') {$sort = $_SESSION['stocksort'];}
		else {$sort = $_GET['sort'];}
		$quid = $_SESSION['stockquid'];
		$attribfamille = $_GET['attribfamille'];
	break;
	### Première étape : Afficher la liste des Anim a la recherche SANS SORT
	default: 
#		$sort = $_SESSION['stocksort'];
#		$quod = $_SESSION['stockquod'];
#		$quid = $_SESSION['stockquid'];
#		$search = $_SESSION['stocksearch'];


	if (!empty($_POST['idstockf'])) {
		if (!empty($quid)) 
		{
			$quid .= " AND ";
			$quod .= " ET ";
		}	
		$quid .= "stf.idstockf = '".$_POST['idstockf']."'";
		$quod = $quod."idstockf = ".$_POST['idstockf'];
	}
	if (!empty($_POST['idstockm'])) {
		if (!empty($quid)) 
		{
			$quid .= " AND ";
			$quod .= " ET ";
		}	
		$quid .= "stm.idstockm = '".$_POST['idstockm']."'";
		$quod = $quod."idstockm = ".$_POST['idstockm'];
	}

	if (!empty($_POST['codematos'])) {
		if (!empty($quid)) 
		{
			$quid .= " AND ";
			$quod .= " ET ";
		}	
		$quid .= "ma.codematos = '".$_POST['codematos']."'";
		$quod .= "codematos = ".$_POST['codematos'];
	}
	if (!empty($_POST['mnom'])) {
		if (!empty($quid)) 
		{
			$quid .= " AND ";
			$quod .= " ET ";
		}	
		$quid .= "ma.mnom LIKE '%".$_POST['mnom']."%'";
		$quod .= "mnom = ".$_POST['mnom'];
	}
	if (!empty($_POST['dateout'])) {
		if (!empty($quid)) 
		{
			$quid .= " AND ";
			$quod .= " ET ";
		}	
		$quid .= "ma.dateout <= '".fdatebk($_POST['dateout'])."'";
		$quod .= "date <= ".fdatebk($_POST['dateout']);
	}
	if (!empty($_POST['suser'])) {
		if (!empty($quid)) 
		{
			$quid .= " AND ";
			$quod .= " ET ";
		}	
		$quid .= "ti.suser LIKE '%".$_POST['suser']."%'";
		$quod .= "usager = ".$_POST['suser'];
	}

	if (!empty($_POST['inuse'])) {
		if (!empty($quid)) 
		{
			$quid .= " AND ";
			$quod .= " ET ";
		}	
		$quid .= "ti.inuse LIKE '%".$_POST['inuse']."%'";
		$quod .= "en usage = ".$_POST['inuse'];
	}

	if ($_POST['sansfamille'] == 2) {
		if (!empty($quid)) 
		{
			$quid .= " AND ";
			$quod .= " ET ";
		}	
		$quid .= "ma.idstockm != 0";
		$quod .= "sans famille ";
		$sort='ma.mnom';
	}

	if ($_POST['sansfamille'] == 1) {
		if (!empty($quid)) 
		{
			$quid .= " AND ";
			$quod .= " ET ";
		}	
		$quid .= "ma.idstockm = 0";
		$quod .= "avec famille ";
		$sort='ma.mnom';
	}

		$attribfamille = $_POST['attribfamille']; # Listing editable ou non

	# ATTENTION POUR TEXTE : LIKE '%XXXX%'
	
	if (!empty($quid)) {$quid='WHERE '.$quid;} else {$quid='WHERE ma.idmatos IS NOT NULL';}
	if ($quod == '') {$quod='ALL';}

		# echo $recherche;

}
		if (!empty($sort)) {$sort .= ','; }
		$sort = 'stf.idstockf, stm.idstockm, ti.inuse DESC, ti.suser DESC,p.idpeople DESC';
		$_SESSION['stockquid'] = $quid;
		$_SESSION['stockquod'] = $quod;

$recherche1='
	SELECT 
	ma.idmatos, ma.codematos, ma.mnom, ma.dateout AS madateout, ma.autre, 
	ma.situation, ma.complet, 
	stm.idstockm, stm.reference,
	stf.idstockf, stf.reference AS reffamille, stf.stype,
	a.idanimation, a.reference AS areference, 
	me.idmerch, me.produit,
	p.pnom, p.pprenom, p.email, p.gsm, p.codepeople,
	j.reference AS jreference, 
	ti.idticket, ti.suser, ti.inuse, ti.idpeople, ti.stockout, ti.stockin, ti.idvip, ti.idvipjob
	FROM matos ma
	LEFT JOIN stockticket ti ON ma.idmatos = ti.idmatos 
	LEFT JOIN stockm stm ON ma.idstockm = stm.idstockm 
	LEFT JOIN stockf stf ON stm.idstockf = stf.idstockf 
	LEFT JOIN vipmission m ON ti.idvip = m.idvip 
	LEFT JOIN vipjob j ON ti.idvipjob = j.idvipjob 
	LEFT JOIN animation a ON ti.idanimation = a.idanimation 
	LEFT JOIN merch me ON ti.idmerch = me.idmerch 
	LEFT JOIN people p ON ti.idpeople = p.idpeople
';
$recherche='
	'.$recherche1.'
	'.$quid.'
	ORDER BY '.$sort.'
';
# 	 GROUP BY ma.idmatos

	echo $recherche;
	# Recherche des résultats
	$client = new db();
	$client->inline("$recherche;");
			$FoundCount = mysql_num_rows($client->result); 
?>
	<fieldset class="blue">
		<legend class="blue">
			R&eacute;sultats de recherche Materiel : <?php echo $_SESSION['stockquod'].' ( # =  '.$FoundCount.' )' ;?>
		</legend>

<?php /* LISTING NORMAL - PAS POUR ATTRIBUTION */ ?>
<?php if ($attribfamille != 1) { ?>
		<?php $classe = "etiq2" ?>
		<table class="<?php echo $classe; ?>" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
			<tr>
				<td class="<?php echo $classe; ?>" colspan="16"><a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=unitsearchresult&attribfamille=1&skip=skip'; ?>"> Afficher le listing d'attribution de famille</a></td>
			</tr>
			<?php $baremenu = '
			<tr>
				<td class="'.$classe.'">&nbsp;</td>
				<td class="'.$classe.'">Code</td>
				<td class="'.$classe.'"><a class="creme" href="'.$_SERVER['PHP_SELF'].'?act=unitsearchresult&skip=skip&sort=ma.mnom">Nom</a></td>
				<td class="'.$classe.'">OUT ?</td>
				<td class="'.$classe.'"><a class="creme" href="'.$_SERVER['PHP_SELF'].'?act=unitsearchresult&skip=skip&sort=ti.suser">Usager</a></td>
				<td class="'.$classe.'">Comp</td>
				<td class="'.$classe.'">stock out</td>
				<td class="'.$classe.'">stock in</td>
				<td class="'.$classe.'"><a class="creme" href="'.$_SERVER['PHP_SELF'].'?act=unitsearchresult&skip=skip&sort=ma.dateout">Date</a></td>
				<td class="'.$classe.'"><a class="creme" href="'.$_SERVER['PHP_SELF'].'?act=unitsearchresult&skip=skip&sort=m.idvip DESC, m.idvipjob DESC, a.idanimation DESC, me.idmerch DESC">Mission</a></td>
				<td class="'.$classe.'">Reference</td>
				<td class="'.$classe.'"><a class="creme" href="'.$_SERVER['PHP_SELF'].'?act=unitsearchresult&skip=skip&sort=p.pnom DESC, p.pprenom ASC">People</a></td>
				<td class="'.$classe.'">GSM</td>
				<td class="'.$classe.'">mail</td>
				<td class="'.$classe.'"><a class="creme" href="'.$_SERVER['PHP_SELF'].'?act=unitsearchresult&skip=skip&sort=stf.idstockf, stm.idstockm">F</a></td>
				<td class="'.$classe.'">U</td>
			</tr>
			'; ?>
			<?php echo $baremenu;?>
			<?php $classe = "standard" ?>
	<?php
	$idstockf = z;
	$idstockm = z;
	$inuse = array();
	while ($row = mysql_fetch_array($client->result)) { 
	
		If (!in_array ($row['idmatos'], $inuse)) {
	?>
			<?php if ($idstockm != $row['idstockm']) { $idstockm = $row['idstockm']; ?>
				<tr>
					<td class="<?php echo $classe; ?>" bgcolor="#EEEEEE" colspan="14">
						<?php echo '('.$row['idstockf'].') '.$row['reffamille']; ?> - <?php echo '('.$row['idstockm'].') '.$row['reference']; ?>
					</td>
					<td class="<?php echo $classe; ?>" bgcolor="#EEEEEE"><?php if ($row['idstockm'] > 0) { ?><a href="<?php echo $_SERVER['PHP_SELF'].'?act=familleshow&idstockf='.$row['idstockf'].'&idmatos='.$row['idmatos'];?>"><img src="<?php echo STATIK ?>illus/stock_right-16.png" alt="Vers la famille" width="13" height="15" border="0"></a><?php } ?></td>
					<td class="<?php echo $classe; ?>" bgcolor="#EEEEEE"></td>
				</tr>
			<?php } ?>
			<tr id="line<?php echo $i; ?>" class="contenu">
				<td class="<?php echo $classe; ?>">&nbsp;</td>
				<td class="<?php echo $classe; ?>"><?php echo $row['codematos']; ?></td>
				<td class="<?php echo $classe; ?>"><?php echo $row['mnom']; ?></td>
				<?php if ($row['inuse'] == 1) { ?>
					<td class="<?php echo $classe; ?>"><font color="red">oui</font></td>
					<td class="<?php echo $classe; ?>"><font color="red"></font></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['complet']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo fdate($row['stockout']); ?></td>
					<td class="<?php echo $classe; ?>"><?php echo fdate($row['stockin']); ?></td>
					<td class="<?php echo $classe; ?>"><?php echo fdate($row['madateout']); ?></td>
					<td class="<?php echo $classe; ?>">
						<?php 
						if ($row['idvipjob'] > 0) {echo 'V-'.$row['idvipjob'];}
						if ($row['idmerch'] > 0) {echo 'M-'.$row['idmerch'];}
						if ($row['idanimation'] > 0) {echo 'A-'.$row['idanimation'];}
						?>
					</td>
					<td class="<?php echo $classe; ?>"><?php echo $row['jreference'].$row['areference'].$row['produit']; ?></td>
					<td class="<?php echo $classe; ?>"><?php if ($row['idpeople'] > 0) { echo '<a class="tab" href="../people/adminpeople.php?act=show&idpeople='.$row['idpeople'].'" target="_blank">'; echo "(".$row['codepeople'].") ".$row['pnom'].' '.$row['pprenom']; ?></a><?php } ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['gsm']; ?></td>
					<td class="<?php echo $classe; ?>"><?php if (!empty($row['email'])) { ?><a href="mailto:<?php echo $row['email']; ?>">email</a><?php } ?></td>
				<?php } else { ?>
					<td class="<?php echo $classe; ?>" colspan="11"></td>
				<?php } ?>
				<td class="<?php echo $classe; ?>"></td>
				<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=unitshow&idstockf='.$row['idstockf'].'&idstockm='.$row['idstockm'].'&idmatos='.$row['idmatos'];?>"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="Vers le materiel" width="13" height="15" border="0"></a></td>
			</tr>
	<?php } 
	$inuse[] = $row['idmatos'];
	} ?>
			<?php $classe = "etiq2" ?>
			<?php echo $baremenu;?>
		</table>
<?php } ?>
<?php /* FIN - LISTING NORMAL - PAS POUR ATTRIBUTION */ ?>


<?php /* LISTING POUR ATTRIBUTION */ ?>
<?php if ($attribfamille == 1) { ?>
		<?php $classe = "etiq2" ?>
		<table class="<?php echo $classe; ?>" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
			<tr>
				<td class="<?php echo $classe; ?>" colspan="4"><a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=unitsearchresult&attribfamille=0&skip=skip'; ?>"> Afficher le listing g&eacute;n&eacute;ral</a></td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>">&nbsp;</td>
				<td class="<?php echo $classe; ?>"><a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=unitsearchresult&attribfamille='.$attribfamille.'&skip=skip&sort=ma.codematos'; ?>">Code</a></td>
				<td class="<?php echo $classe; ?>"><a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=unitsearchresult&attribfamille='.$attribfamille.'&skip=skip&sort=ma.mnom'; ?>">Nom</a></td>
				<td class="<?php echo $classe; ?>">U</td>
			</tr>
			<?php $classe = "standard" ?>
	<?php
	$idstockf = z;
	$idstockm = z;
	while ($row = mysql_fetch_array($client->result)) { 
	$i++;
	?>
			<tr id="line<?php echo $i; ?>" class="contenu">
				<?php
					$recherchemodele='
						SELECT 
						stm.idstockm, stm.reference,
						stf.idstockf, stf.reference AS reffamille
						FROM stockm stm 
						LEFT JOIN stockf stf ON stm.idstockf = stf.idstockf 
						WHERE stm.idstockm > 1
						ORDER BY stf.reference, stm.reference
					';
					$listingmodele = new db();
					$listingmodele->inline("$recherchemodele;");
					$option2 = '<option value="" selected></option>';
					while ($rowmodele = mysql_fetch_array($listingmodele->result)) { 
						if ($rowmodele['idstockm'] == $row['idstockm']) { $selected = 'selected'; }
						$option2 .= '<option value="'.$rowmodele['idstockm'].'-xsep-'.$rowmodele['idstockf'].'" '.$selected.'>'.substr($rowmodele['reffamille'], 0, 35).' ('.substr($rowmodele['reference'], 0, 35).')</option>';
						$selected = '';
					}
				?>
					<td class="<?php echo $classe; ?>">
						<form action="<?php echo $_SERVER['PHP_SELF'].'?act=unitsearchresultmodif&skip=skip&attribfamille='.$attribfamille;?>" method="post">
							<input type="hidden" name="idmatos" value="<?php echo $row['idmatos']; ?>">
							<select name="idstockm" size="1">
								<?php echo $option2; ?>
							</select>
							<input type="submit" name="Valider" value="OK">
						</form>
					</td>
				<td class="<?php echo $classe; ?>"><?php echo $row['codematos']; ?></td>
				<td class="<?php echo $classe; ?>"><?php echo $row['mnom']; ?></td>
				<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=unitshow&idstockf='.$row['idstockf'].'&idstockm='.$row['idstockm'].'&idmatos='.$row['idmatos'];?>"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="Vers le materiel" width="13" height="15" border="0"></a></td>
			</tr>
	<?php } ?>
		</table>
<?php } ?>
	</fieldset>
<?php #echo $quid;?>
</div>