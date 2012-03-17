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

$listing = new db();
$listing->inline("SELECT
		me.idmerch, me.datem, me.weekm, me.genre,
			me.hin1, me.hout1, me.hin2, me.hout2,
			me.kmpaye, me.kmfacture, me.frais, me.fraisfacture,
			me.produit, me.facturation,
			me.ferie, me.contratencode, me.recurrence,
		a.prenom, a.idagent,
		c.codeclient, c.societe AS clsociete, c.idclient, c.tel, c.fax,
		co.idcofficer, co.qualite, co.onom, co.oprenom, co.fax AS cofax,
		s.idshop, s.codeshop, s.societe AS ssociete, s.ville AS sville,
		p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.gsm, p.codepeople
	FROM merch me
		LEFT JOIN agent a ON me.idagent = a.idagent
		LEFT JOIN client c ON me.idclient = c.idclient
		LEFT JOIN cofficer co ON me.idcofficer = co.idcofficer
		LEFT JOIN people p ON me.idpeople = p.idpeople
		LEFT JOIN shop s ON me.idshop = s.idshop
	WHERE
		p.idpeople = ".$_SESSION['idpeople']."
		AND me.datem < '".date("Y-m-d", strtotime("+3 days"))."'
		AND p.webdoc = 'yes'
		AND me.webdoc = 'yes'
	ORDER BY me.datem DESC");

$FoundCount = mysql_num_rows($listing->result);

# ------- DEBUT LISTING GENERAL --------
$colspa = 3;?>
<form action="<?php echo NIVO ?>print/merch/printmerch.php" method="post" target="popup" onsubmit="OpenBrWindow('_blank','popup','scrollbars=yes,status=yes,resizable=yes','500','400','true')" >
<Fieldset class="width">
	<legend class="width">
		<b><?php echo $contrat_merch_01;?></b> ( <?php echo $FoundCount; ?> )
	</legend>
	<table class="planning" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="planning" colspan="<?php echo $colspa; ?>" align="right">
				<input type="hidden" name="ptype[]" value="contrat"></th>
			<th class="planning"></th>
			<th class="planning"><input type="submit" class="btn printer"></th>
			<th class="planning"></th>
		</tr>
		<tr>
			<td class="tabtitre"><?php echo $tool_20;?></td>
			<td class="tabtitre"><?php echo $tool_21;?></td>
			<td class="tabtitre"><?php echo $tool_22;?></td>
			<td class="tabtitre"><?php echo $tool_23;?></td>
			<td class="tabtitre"><?php echo $tool_24;?></td>
		</tr>
	<?php
	while ($row = mysql_fetch_array($listing->result)) {
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
					<td class="line"><?php echo fdate($row['datem']) ?></td>
					<td class="line"><?php echo $row['clsociete']; ?></td>
					<td class="line"><?php echo $row['ssociete']; ?> - <?php echo $row['sville']; ?></td>
					<th class="line" width="15">
						<?php
							if ($row['datem'] >= date("Y-m-d")) { ?>
								<input type="checkbox" name="print[]" value="<?php echo $row['idmerch'];?>">
						<?php } ?>
					</th>
			</tr>
	<?php } ?>
		<tr>
			<td class="tabtitre"><?php echo $tool_20;?></td>
			<td class="tabtitre"><?php echo $tool_21;?></td>
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
<?php
# ------- FIN LISTING GENERAL --------
?>
</div>