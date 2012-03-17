<?php
include_once NIVO."classes/document.php";
include_once NIVO."print/anim/contrat/contrat_inc.php";
include_once NIVO."print/commun/notefrais.php";
include_once NIVO."print/anim/rapportp/rapportp.php";
include_once NIVO."classes/document.php";
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

$DB->inline("SELECT 
		an.idanimation, an.reference, an.datem, 
		c.codeclient, c.societe AS clsociete, 
		s.societe AS ssociete, s.ville AS sville,
		nf.idnfrais
	FROM animation an 
		LEFT JOIN animjob j ON an.idanimjob = j.idanimjob 
		LEFT JOIN client c ON j.idclient = c.idclient 
		LEFT JOIN people p ON an.idpeople = p.idpeople 
		LEFT JOIN shop s ON an.idshop = s.idshop 
		LEFT JOIN notefrais nf ON nf.secteur = 'AN' AND nf.idmission = an.idanimation
	WHERE 
		p.idpeople = ".$_SESSION['idpeople']."
		AND an.datem < '".date("Y-m-d", strtotime("+8 days"))."' 
		AND an.webdoc = 'yes' 
		AND p.webdoc = 'yes' 
	ORDER BY an.datem DESC");

$FoundCount = mysql_num_rows($DB->result); 

# ------- DEBUT LISTING GENERAL --------
$colspa = 3;
$i=0;
?>
<Fieldset class="width">
	<legend class="width">
		<b><?php echo $contrat_anim_01;?></b> ( <?php echo $FoundCount; ?> )
	</legend>
	<table class="planning" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<td class="tabtitre"><?php echo $tool_20;?></td>
			<td class="tabtitre"><?php echo $tool_21;?></td>
			<td class="tabtitre"><?php echo $tool_22;?></td>
			<td class="tabtitre"><?php echo $tool_23;?></td>
			<td class="tabtitre"><?php echo $tool_24;?></td>
		</tr>
	<?php
	while ($row = mysql_fetch_array($DB->result)) { 
		$i++;
		echo '<tr bgcolor="'.((fmod($i, 2) == 1)?'#78ABD7':'#97B4CD').'">';
	?>
					<td class="line"><?php echo $row['idanimation'].' '.$row['reference']; ?></td>
					<td class="line"><?php echo fdate($row['datem']) ?></td>
					<td class="line"><?php echo $row['codeclient'].' - '.$row['clsociete']; ?></td>
					<td class="line"><?php echo $row['ssociete'].' - '.$row['sville']; ?></td>
					<th class="line" width="15">
						<?php 
							if ($row['datem'] >= date("Y-m-d")) {
								if ($row['datem'] < date("Y-m-d", strtotime("+3 days"))) {
									
									
									$global[] = print_contratanim($row['idanimation']);
									if ($row['idnfrais'] > 0) $global[] = print_notefrais($row['idnfrais']);
									$global[] = print_rapportpanim($row['idanimation']);
									
									$book = reliure($global, 'AWCo');
									
									echo '<a href="'.NIVO.$book['path'].'" target="_blank"><img title="Print Me" src="'.STATIK.'illus/minipdf.gif" alt="print" width="16" height="16" border="0"></a><br>';
								} else {
									$dateprint = date ("Y-m-d", strtotime("-2 days", strtotime($row['datem'])));
									echo fdate($dateprint).'*';
								}
							}
						?>
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
	</table>
	<div style="font-size: 10px;"><?php echo $tool_37;?></div>
</Fieldset>
</div>