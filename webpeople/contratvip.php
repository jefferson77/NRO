<?php
include_once NIVO."classes/document.php";
include_once NIVO."print/vip/contrat/contrat_inc.php" ;
?>
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
			<td class="newstxt"><?php echo $contrat_02;?><br><?php echo $contrat_03;?></td>
		</tr>
	</table>
</div>

<div class="corps">
<?php
# Recherche des résultats
$listing = new db();
$listing->inline("SELECT
			m.idvipjob, m.idvip, m.vipdate,
			j.reference
		FROM vipmission m
			LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob
			LEFT JOIN people p ON m.idpeople = p.idpeople
		WHERE p.idpeople = ".$_SESSION['idpeople']."
			AND j.webdoc = 'yes' 
			AND p.webdoc = 'yes' 
			AND j.etat != 2
		ORDER BY m.vipdate DESC");

$FoundCount = mysql_num_rows($listing->result); 

# ------- DEBUT LISTING GENERAL --------
?>
<fieldset class="width">
	<legend class="width">
		<b><?php echo $contrat_vip_01;?></b> ( <?php echo $FoundCount; ?> )
	</legend>
	<table border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<td class="tabtitre"><?php echo $tool_25;?></td>
			<td class="tabtitre"><?php echo $tool_20;?></td>
			<td class="tabtitre"><?php echo $tool_21;?></td>
			<td class="tabtitre"><?php echo $tool_26;?></td>
			<td class="tabtitre"><?php echo $tool_24;?></td>
		</tr>
<?php

$i=0;

while ($row = mysql_fetch_array($listing->result)) { 
	$i++;
	echo '<tr bgcolor="'.((fmod($i, 2) == 1)?'#78ABD7':'#97B4CD').'">';
?>
			<td class="line"><?php echo $row['idvipjob'] ?></td>
			<td class="line"><?php echo $row['idvip'] ?></td>
			<td class="line"><?php echo fdate($row['vipdate']) ?></td>
			<td class="line"><?php echo $row['reference'] ?></td>
			<td class="line" style="text-align: center;">
				<?php 
					if ($row['vipdate'] >= date("Y-m-d")) {
						if ($row['vipdate'] < date("Y-m-d", strtotime("+3 days"))) {
							echo '<a href="'.substr(print_contratvip($row['idvip']), 4).'" target="_blank"><img title="Print Me" src="'.STATIK.'illus/minipdf.gif" alt="print" width="16" height="16" border="0"></a><br>';
						} else {
							$dateprint = date ("Y-m-d", strtotime("-2 days", strtotime($row['vipdate'])));
							echo fdate($dateprint).'*';
						}
					}
				?>
			</td>
		</tr>
<?php } 
?>
		<tr>
			<td class="tabtitre"><?php echo $tool_25;?></td>
			<td class="tabtitre"><?php echo $tool_20;?></td>
			<td class="tabtitre"><?php echo $tool_21;?></td>
			<td class="tabtitre"><?php echo $tool_26;?></td>
			<td class="tabtitre"><?php echo $tool_24;?></td>
		</tr>
	</table>
	<div style="font-size: 10px;"><?php echo $tool_37;?></div>
</fieldset>
</div>