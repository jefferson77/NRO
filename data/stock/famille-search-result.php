<div id="centerzonelargewhite">
<?php 
switch ($_GET['skip']) {
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
		if ($_GET['sort'] == '') {$sort = $_SESSION['stockfamillesort'];}
		else {$sort = $_GET['sort'];}
		$quid = $_SESSION['stockfamillequid'];
	break;
	### Première étape : Afficher la liste des Anim a la recherche SANS SORT
	default: 

	if (!empty($_POST['idstockf'])) {
		if (!empty($quid)) 
		{
			$quid=$quid." AND ";
			$quod=$quod." ET ";
		}	
		$quid = $quid."stf.idstockf = '".$_POST['idstockf']."'";
		$quod = $quod."idstockf = ".$_POST['idstockf'];
	}
	if (!empty($_POST['idstockm'])) {
		if (!empty($quid)) 
		{
			$quid=$quid." AND ";
			$quod=$quod." ET ";
		}	
		$quid = $quid."stm.idstockm = '".$_POST['idstockm']."'";
		$quod = $quod."idstockm = ".$_POST['idstockm'];
	}
	
	if (!empty($quid)) {$quid='WHERE '.$quid;} else {$quid='WHERE 1';}
	if ($quod == '') {$quod='ALL';}
	if ($sort == '') {$sort='stf.idstockf, stm.idstockm';}

		# echo $recherche;

}
	$sort .= ', stf.idstockf, stm.idstockm';
		$_SESSION['stockfamillequid'] = $quid;
		$_SESSION['stockfamillequod'] = $quod;
		$_SESSION['stockfamillesort'] = $sort;

$recherche1='
	SELECT 
	stm.idstockm, stm.reference,
	stf.idstockf, stf.reference AS reffamille, stf.stype
	FROM stockf stf
	LEFT JOIN stockm stm ON stf.idstockf = stm.idstockf 
';

$recherche='
	'.$recherche1.'
	'.$quid.'
	 ORDER BY '.$sort.'
';

#	echo $recherche;
	# Recherche des résultats
	$client = new db();
	$client->inline("$recherche;");
			$FoundCount = mysql_num_rows($client->result); 
?>
	<fieldset class="blue">
		<legend class="blue">
			R&eacute;sultats de recherche Materiel : <?php echo $_SESSION['stockfamillequod'].' ( # =  '.$FoundCount.' )' ;?>
		</legend>
		<?php $classe = "etiq2" ?>
		<table class="<?php echo $classe; ?>" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
			<tr>
				<td class="<?php echo $classe; ?>" width="30">&nbsp;</td>
				<td class="<?php echo $classe; ?>" width="30">Code</td>
				<td class="<?php echo $classe; ?>"><a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=famillesearchresult&skip=skip&sort=stm.reference'; ?>">Nom</a></td>
				<td class="<?php echo $classe; ?>" width="15">M</td>
				<td class="<?php echo $classe; ?>" width="15"><a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=famillesearchresult&skip=skip&sort=stf.idstockf, stm.idstockm'; ?>">F</a></td>
			</tr>
			<?php $classe = "standard" ?>
	<?php
	$idstockf = z;
	while ($row = mysql_fetch_array($client->result)) { 
	?>
			<?php if ($idstockf != $row['idstockf']) { $idstockf = $row['idstockf']; ?>
				<tr>
					<td class="<?php echo $classe; ?>" bgcolor="#EEEEEE" colspan="3">
						<?php echo '('.$row['idstockf'].') '.$row['reffamille']; ?>
					</td>
					<td class="<?php echo $classe; ?>" bgcolor="#EEEEEE"></td>
					<td class="<?php echo $classe; ?>" bgcolor="#EEEEEE"><?php if ($row['idstockf'] > 0) { ?><a href="<?php echo $_SERVER['PHP_SELF'].'?act=familleshow&idstockf='.$row['idstockf'];?>"><img src="<?php echo STATIK ?>illus/stock_right-16.png" alt="Vers la famille" width="13" height="15" border="0"></a><?php } ?></td>
				</tr>
			<?php } ?>
			<tr>
				<td class="<?php echo $classe; ?>">&nbsp;</td>
				<td class="<?php echo $classe; ?>"><?php echo $row['idstockm']; ?></td>
				<td class="<?php echo $classe; ?>"><?php echo $row['reference']; ?></td>
				<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=familleshow&idstockf='.$row['idstockf'];?>"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="Vers le materiel" width="13" height="15" border="0"></a></td>
				<td class="<?php echo $classe; ?>"></td>
			</tr>
	<?php } ?>
			<?php $classe = "etiq2" ?>
		</table>
	</fieldset>
<?php #echo $quid;?>
</div>