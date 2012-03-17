<div class="news">
	<table width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="fulltitre" colspan="2"><?php echo $tool_01; ?><br><?php echo $anim_sales_00; ?></td>
		</tr>
		<tr>
			<td class="newstit">1. <?php echo $anim_sales_menu_01; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li><img src="<?php echo STATIK ?>illus/printer.png" alt="search" width="16" height="16" border="0">
					<li> <?php echo $anim_sales_menu_02a; ?>
					<li> <?php echo $anim_sales_menu_02b; ?>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="newstit">2. <?php echo $anim_sales_menu_03; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li><img src="<?php echo STATIK ?>illus/semaine.gif" alt="search" border="0">
					<li><?php echo $anim_sales_menu_04; ?>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="newstit">3. <?php echo $anim_sales_menu_05; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li> <?php echo $anim_sales_menu_06; ?>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="newstit">4. <?php echo $anim_sales_menu_07; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li> <?php echo $anim_sales_menu_08; ?>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="newstxt">
				<?php echo $anim_sales_menu_11; ?>
			</td>
		</tr>
	</table>
</div>

<div class="corps">
<?php $classe = "planning" ;

		$sort = 'me.yearm, me.weekm, p.idpeople, me.datem, me.hin1, s.idshop';

		$quid .= "me.idclient = ".$_SESSION['idclient'];	# Client
		#$quid .= "me.genre LIKE '%Rack assistance%'"; # Genre
	## Semaine
		if (($_POST['weekm2'] == '') and ($_POST['weekm1'] != '')) { $_POST['weekm2'] = $_POST['weekm1'];}

		if ($_POST['weekm1'] != '') {
			if (!empty($quid)) { $quid .= " AND "; }
			$quid .= "me.weekm >= '".$_POST['weekm1']."'";
			$quod .= " de la semaine ".$_POST['weekm1'];
		}
		if ($_POST['weekm2'] != '') {
			if (!empty($quid)) { $quid .= " AND "; }
			$quid .= "me.weekm <= '".$_POST['weekm2']."'";
			$quod .= " &agrave; la semaine ".$_POST['weekm2'];
		}
	## Annee
		if ($_POST['yearm1'] != '') {
			if (!empty($quid)) { $quid .= " AND "; }
			$quid .= "me.yearm >= ".$_POST['yearm1'];
			$quod .= " de l&rsquo;ann&eacute;e ".$_POST['yearm1'];
		}
	### merch etat Facturation
		if (!empty($quid)) { $quid .= " AND "; }
		$quid .= "me.facturation = 1";
	#/## merch etat Facturation

		if (!empty($quid)) {$quid='WHERE '.$quid;}
		$recherche2='
			me.idmerch, me.datem, me.weekm, me.yearm, me.genre,
			me.hin1, me.hout1, me.hin2, me.hout2,
			me.kmpaye, me.kmfacture, me.frais, me.fraisfacture,
			me.produit, me.facturation,
			me.ferie, me.contratencode,
			me.idshop,
			a.prenom, a.idagent,
			c.idclient, c.codeclient, c.societe AS clsociete, c.idclient, c.tel, c.fax,
			co.idcofficer, co.qualite, co.onom, co.oprenom, co.fax AS cofax,
			s.codeshop, s.societe AS ssociete, s.ville AS sville,
			p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.gsm, p.codepeople
			FROM merch me
			LEFT JOIN agent a ON me.idagent = a.idagent
			LEFT JOIN client c ON me.idclient = c.idclient
			LEFT JOIN cofficer co ON me.idcofficer = co.idcofficer
			LEFT JOIN people p ON me.idpeople = p.idpeople
			LEFT JOIN shop s ON me.idshop = s.idshop
		';
		$recherche1='
			SELECT '.$recherche2;

		$recherche='
			'.$recherche1.'
			'.$quid.'
			 ORDER BY '.$sort.'
		';

$listing = new db();
$listing->inline($recherche);
$FoundCount = mysql_num_rows($listing->result);

?>
<fieldset  class="width">
	<legend  class="width">
		<b>Planning du Merchandising : <?php echo $quod; ?> ( <?php echo $FoundCount; ?> )</b>
	</legend>
<?php

$colspa = 4;?>
<form action="<?php echo NIVO ?>print/merch/printmerch.php" method="post" target="popup" onsubmit="OpenBrWindow('about:blank','popup','scrollbars=yes,status=yes,resizable=yes','500','400','true')" >
	<input type="hidden" name="ptype[]" value="planning">
	<table class="<?php echo $classe; ?>" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<td class="tabtitre">Mission</a></td>
			<td class="tabtitre">Date</a></td>
			<td class="tabtitre">P.O.S.</a></td>
			<td class="tabtitre"><input type="submit" class="btn printer"></td>
			<td></td>
		</tr>
	<?php

	$zweekm = 'z';
	$zyearm = 'z';
	$zidpeople = 'z';
	$zidshop = 'z';
	while ($row = mysql_fetch_array($listing->result)) {


	$annee = substr($row['datem'], 0, 4);
	## ligne de semaine ##
	if ($row['yearm'] != $zyearm) { $titre = 'go'; }
	if ($row['weekm'] != $zweekm) { $titre = 'go'; }
	if ($row['idpeople'] != $zidpeople) { $titre = 'go'; }
	if (($row['genre'] == 'Rack assistance') and ($row['idshop'] != $zidshop))	{ $titre = 'go'; }
	if (($row['genre'] == 'EAS') and ($row['idshop'] != $zidshop))	{ $titre = 'go'; }

	if ($titre == 'go') {
	?>
	<tr>
		<td bgcolor="white" colspan="2">
			semaine : <?php echo $row['weekm']; $date = weekdate($row['weekm'], $row['yearm']); echo ' ('.fdate($date['lun']).' au '.fdate($date['dim']).')'; ?>
		</td>
		<td bgcolor="white">
			jobiste : <?php echo $row['codepeople'].' - '.$row['pnom'].' '.$row['pprenom'] ?>
		</td>
		<th class="<?php echo $classe; ?>" width="15">
			<?php if (!empty($row['idpeople'])){ ?>
				<input type="checkbox" name="print[]" checked value="<?php echo $row['idpeople'].'/'.$row['datem'].'/'.$row['idmerch'].'/'.$row['weekm'].'/'.$row['genre'].'/'.$row['yearm']; ?>">
			<?php } ?>
		</th>
		<th class="<?php echo $classe; ?>" width="13">
			<?php if (!empty($row['idpeople'])){
				$act = 'planningweek';
			?>
				<a href="<?php echo $_SERVER['PHP_SELF'].'?act='.$act.'&idpeople='.$row['idpeople'].'&weekm='.$row['weekm'].'&idshop='.$row['idshop'].'&genre='.$row['genre'].'&datem='.$row['datem'];?>"><img src="<?php echo STATIK ?>illus/semaine.gif" alt="search" border="0"></a>
			<?php } else { ?>
				X
			<?php } ?>
		</th>
	<?php
	$titre = 'roger';
	}
	#/ ## ligne de semaine ##

		#> Changement de couleur des lignes #####>>####
		$i++;
		if (fmod($i, 2) == 1) {
			echo '<tr bgcolor="#9CBECA">';
		} else {
			echo '<tr bgcolor="#8CAAB5">';
		}
		#< Changement de couleur des lignes #####<<####
	?>
					<td class="<?php echo $classe; ?>"><?php echo $row['idmerch'] ?></td>
					<td class="<?php echo $classe; ?>"><?php echo fdate($row['datem']) ?></td>
					<td class="<?php echo $classe; ?>" colspan="2"><?php echo $row['codeshop']; ?> - <?php echo $row['ssociete']; ?> - <?php echo $row['sville']; ?></td>
			</tr>
	<?php
	$zyearm = $row['yearm'];
	$zweekm = $row['weekm'];
	$zidpeople = $row['idpeople'];
	$zidshop = $row['idshop'];

	} ?>
	</table>
</form>

<?php
#-----------------
?>
<br>
</fieldset>

</div>
