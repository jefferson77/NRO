<div class="news">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="newstitinfo"><?php echo $contrat_09;?></td>
		</tr>
		<tr>
			<td class="fulltitre" colspan="2"><?php echo $tool_01;?></td>
		</tr>
		<tr>
			<td class="newstit"><?php echo $tool_09;?></td>
		</tr>
		<tr>
			<td class="newstxt"><?php echo $contrat_02;?><br><?php echo $contrat_03;?><br><?php echo $contrat_04;?></td>
		</tr>
		<tr>
			<td class="newstit"><?php echo $tool_10;?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<input type="checkbox" name="exemple" checked> : <?php echo $contrat_06;?><br>
				<img src="<?php echo STATIK ?>illus/printer.png" width="16" height="16"> : <?php echo $contrat_07;?><br>
				<img src="<?php echo STATIK ?>illus/minipdf.gif" width="16" height="16"> : <?php echo $contrat_08;?>
			</td>
		</tr>
	</table>
</div>

<div class="corps">
<?php

switch ($_GET['action']) {
	case "skip":
	break;
	### Première étape : Afficher la liste des merch a la recherche SANS SORT
	default:

			if (!empty($quid)) { $quid .= " AND "; $quod .= " ET "; }
			$quid .= "p.idpeople = ".$idpeople;


			if (!empty($quid)) { $quid .= " AND "; $quod .= " ET "; }
			$quid .= "me.webdoc = 'yes'";

			if (!empty($quid)) { $quid .= " AND "; $quod .= " ET "; }
			$quid .= "me.genre = 'EAS'";

			if (!empty($quid)) { $quid .= " AND "; $quod .= " ET "; }
			$quid .= "p.webdoc = 'yes'";

		if (!empty($quid)) {$quid='WHERE '.$quid;}
		if ($sort == '') {$sort='me.datem DESC';}
			$sort = 'me.yearm DESC, me.weekm DESC, me.datem DESC, me.hin1, s.idshop';

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
}

$listing = new db();
$listing->inline($recherche);
$FoundCount = mysql_num_rows($listing->result);

$colspa = 3;?>
<form action="<?php echo NIVO ?>print/merch/printmerch.php" method="post" target="popup" onsubmit="OpenBrWindow('_blank','popup','scrollbars=yes,status=yes,resizable=yes','500','400','true')" >
<Fieldset class="width">
	<legend class="width">
		<b><?php echo $eas_merch_01;?></b> ( <?php echo $FoundCount; ?> )
	</legend>
	<table class="planning" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="planning" colspan="<?php echo $colspa; ?>" align="right">
				<input type="hidden" name="printeas" value="rapporteas"></th>
			<th class="planning"></th>
			<th class="planning"><input type="submit" class="btn printer"></th>
			<th class="planning"></th>
		</tr>
		<tr>
			<td class="tabtitre"><?php echo $tool_20;?></td>
			<td class="tabtitre"><?php echo $tool_27;?></td>
			<td class="tabtitre"><?php echo $tool_22;?></td>
			<td class="tabtitre"><?php echo $tool_23;?></td>
			<td class="tabtitre"><?php echo $tool_24;?></td>
		</tr>
	<?php
	$zweekm = 'z';
	$zidpeople = 'z';
	$zidshop = 'z';
	while ($row = mysql_fetch_array($listing->result)) {
		## ligne de semaine ##
		$titre = 'nogo';
		if ($row['weekm'] != $zweekm) { $titre = 'go'; }
		if ($row['idshop'] != $zidshop)	{ $titre = 'go'; }

		if ($titre == 'go') {


			#> Changement de couleur des lignes #####>>####
			$i++;
			if (fmod($i, 2) == 1) {
				echo '<tr bgcolor="#78ABD7">';
			} else {
				echo '<tr bgcolor="#97B4CD">';
			}
			#< Changement de couleur des lignes #####<<####
		?>
						<td class="line"><?php echo $row['idmerch'] ?></td>
						<td class="line"><?php echo $row['weekm'] ?> / <?php echo $row['yearm'] ?></td>
						<td class="line"><?php echo $row['clsociete']; ?></td>
						<td class="line"><?php echo $row['ssociete']; ?> - <?php echo $row['sville']; ?></td>
					<?php if (!empty($row['idpeople'])){ ?>
						<th class="line" width="15"><input type="checkbox" name="print[]" value="<?php echo $row['idpeople'].'/'.$row['idshop'].'/'.$row['weekm'].'/'.$row['yearm'] ?>"></th>
					<?php } ?>
				</tr>
		<?php 	} ?>
	<?php
	$zweekm = $row['weekm'];
	$zidpeople = $row['idpeople'];
	$zidshop = $row['idshop'];

	} ?>
		<tr>
			<td class="tabtitre"><?php echo $tool_20;?></td>
			<td class="tabtitre"><?php echo $tool_27;?></td>
			<td class="tabtitre"><?php echo $tool_22;?></td>
			<td class="tabtitre"><?php echo $tool_23;?></td>
			<td class="tabtitre"><?php echo $tool_24;?></td>
		</tr>
		<tr>
			<th class="planning" colspan="<?php echo $colspa; ?>" align="right"></th>
			<th class="planning"></th>
			<th class="planning"><input type="submit" class="btn printer"></th>
			<th class="planning"></th>
		</tr>
	</table>
</fieldset>
</form>
</div>