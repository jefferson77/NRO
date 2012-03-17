<?php
$motifs = array(	'1' => 'D&eacute;sistement',
					'2' => 'Absent',
					'3' => 'Re-booking',
					'4' => 'Annulation Exception',
					'5' => 'De-booking Exception',
					'6' => 'Autre');

############################################################################################################
#####  VIP  ############################################################################################################
$DB->inline('
	SELECT
		pm.idvip, pm.motif, pm.note,
		m.vipdate, m.vipin, m.vipout, m.vipactivite, 
		j.idvipjob, j.idclient, j.etat, j.reference, 
		a.prenom, 
		c.societe AS clsociete, 
		s.societe AS shsociete, s.ville
	FROM peoplemission pm
		LEFT JOIN vipmission m ON pm.idvip = m.idvip
		LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob
		LEFT JOIN agent a ON j.idagent = a.idagent
		LEFT JOIN client c ON j.idclient = c.idclient
		LEFT JOIN shop s ON m.idshop = s.idshop
	WHERE pm.idpeople = '.$_REQUEST['idpeople'].' AND pm.idvip > 0
	ORDER BY m.vipdate DESC, m.vipactivite DESC');

if (mysql_num_rows($DB->result) > 0) {
?>
<table class="sortable rowstyle-alt no-arrow" border="0" width="99%" cellspacing="1">
	<thead>
		<tr><th colspan="12">VIP</th></tr>
		<tr>
			<th>Job</th>
			<th class="sortable-text">R&eacute;f&eacute;rence</th>
			<th class="sortable-text">Assi.</th>
			<th class="sortable-text" colspan="2">Client</th>
			<th class="sortable-date-dmy">Date</th>
			<th>In</th>
			<th>Out</th>
			<th class="sortable-text">Lieux</th>
			<th class="sortable-text">Motif</th>
			<th class="sortable-text">Note</th>
			<th class="sortable-text">Activit&eacute;</th>
		</tr>
	</thead>
	<tbody>
<?php while ($row = mysql_fetch_array($DB->result)) { ?>
		<tr align="center">
			<td><?php echo ($row['idvipjob'] > 0)?('<a href="'.NIVO.'vip/adminvip.php?act=show&idvipjob='.$row['idvipjob'].'&etat='.$row['etat'].'" target="_blank">'.$row['idvipjob'].'</a>'):'OUT'; ?></td>
			<td><?php echo $row['idvip'].'-'.$row['reference']; ?></td>
			<td><?php echo $row['prenom'] ?></td>
			<td><?php echo $row['clsociete']; ?></td>
			<td><?php echo $row['idclient']; ?></td>
			<td><?php echo '<font color="'.((strchr($datetot, $row['vipdate']))?'red':'black').'">'.fdate($row['vipdate']) ?></font></td>
			<td><?php echo ftime($row['vipin']) ?></td>
			<td><?php echo ftime($row['vipout']) ?></td>
			<td><?php echo $row['shsociete'].' ('.$row['ville'].' )' ; ?></td>
			<td><?php echo $motifs[$row['motif']]; ?></td>
			<td><?php echo $row['note'] ?></td>
			<td><?php echo $row['vipactivite'] ?></td>
		</tr>
<?php 
	$datetot .= ','.$row['vipdate'];
} ?>
			</tbody>
		</table>
<?php
}

unset ($datetot);

############################################################################################################
#####  ANIM  ############################################################################################################
$DB->inline('
	SELECT
		pm.motif, pm.note, 
		an.idanimation, an.idanimjob, an.produit, an.datem, an.hin1, an.hout1, an.hin2, an.hout2, 
		j.idclient,
		a.prenom, 
		c.societe AS clsociete, 
		s.societe AS ssociete, s.ville AS sville 
	FROM peoplemission pm
		LEFT JOIN animation an ON pm.idanimation = an.idanimation 
		LEFT JOIN animjob j ON an.idanimjob = j.idanimjob 
		LEFT JOIN agent a ON j.idagent = a.idagent 
		LEFT JOIN client c ON j.idclient = c.idclient 
		LEFT JOIN shop s ON an.idshop = s.idshop
	WHERE pm.idpeople = '.$_REQUEST['idpeople'].' AND pm.idanimation > 0
	ORDER BY an.datem DESC');

if (mysql_num_rows($DB->result) > 0) {
?>
<table class="sortable rowstyle-alt no-arrow" border="0" width="99%" cellspacing="1">
	<thead>
		<tr><th colspan="12">ANIM</th></tr>
		<tr>
			<th>Job</th>
			<th class="sortable-text">R&eacute;f&eacute;rence</th>
			<th class="sortable-text">Assi.</th>
			<th class="sortable-text" colspan="2">Client</th>
			<th class="sortable-date-dmy">Date</th>
			<th>AM</th>
			<th>PM</th>
			<th class="sortable-text">Lieux</th>
			<th class="sortable-text">Motif</th>
			<th class="sortable-text">Note</th>
		</tr>
	</thead>
	<tbody>
<?php while ($rowanim = mysql_fetch_array($DB->result)) { ?>
		<tr align="center">
			<td><a href="<?php echo NIVO.'animation2/adminanim.php?act=showjob&idanimjob='.$rowanim['idanimjob']; ?>" target="_blank"><?php echo $rowanim['idanimjob']; ?></a></td>
			<td><?php echo $rowanim['idanimation'].' - '.$rowanim['produit'] ?></td>
			<td><?php echo $rowanim['prenom'] ?></td>
			<td><?php echo $rowanim['idclient']; ?></td>
			<td><?php echo $rowanim['clsociete']; ?></td>
			<td><?php echo '<font color="'.((strchr($datetot, $rowanim['datem']))?'red':'black').'">'.fdate($rowanim['datem']) ?></font></td>
			<td><?php echo ftime($rowanim['hin1']).' - '.ftime($rowanim['hout1']); ?></td>
			<td><?php echo ftime($rowanim['hin2']).' - '.ftime($rowanim['hout2']); ?></td>
			<td><?php echo $rowanim['ssociete'].' ('.$rowanim['sville'].' )' ; ?></td>
			<td><?php echo $motifs[$rowanim['motif']]; ?></td>
			<td><?php echo $rowanim['note'] ?></td>
		</tr>
<?php } 
	$datetot .= ','.$rowanim['datem'];
?>
			</tbody>
		</table>
<?php
}
unset ($datetot);

############################################################################################################
#####  Merch  ############################################################################################################

$DB->inline('
	SELECT
		pm.note, pm.motif,
		me.idmerch, me.produit, me.idclient, me.datem, me.hin1, me.hout1, me.hin2, me.hout2, 
		a.prenom, 
		c.societe AS clsociete,
		s.societe AS ssociete, s.ville AS sville
	FROM peoplemission pm
		LEFT JOIN merch me ON pm.idmerch = me.idmerch 
		LEFT JOIN agent a ON me.idagent = a.idagent 
		LEFT JOIN client c ON me.idclient = c.idclient 
		LEFT JOIN shop s ON me.idshop = s.idshop
	WHERE pm.idpeople = '.$_REQUEST['idpeople'].' AND pm.idmerch > 0
	ORDER BY me.datem DESC');

if (mysql_num_rows($DB->result) > 0) {
?>
<table class="sortable rowstyle-alt no-arrow" border="0" width="99%" cellspacing="1">
	<thead>
		<tr><th colspan="12">MERCH</th></tr>
		<tr>
			<th>Mis.</th>
			<th class="sortable-text">R&eacute;f&eacute;rence</th>
			<th class="sortable-text">Assi.</th>
			<th class="sortable-text" colspan="2">Client</th>
			<th class="sortable-date-dmy">Date</th>
			<th>AM</th>
			<th>PM</th>
			<th class="sortable-text">Lieux</th>
			<th class="sortable-text">Motif</th>
			<th class="sortable-text">Note</th>
		</tr>
	</thead>
	<tbody>
<?php while ($rowmerch = mysql_fetch_array($DB->result)) { ?>
		<tr align="center">
			<td><a href="<?php echo NIVO.'merch/adminmerch.php?act=show&act2=listing&idmerch='.$rowmerch['idmerch']; ?>" target="_blank"><?php echo $rowmerch['idmerch']; ?></a></td>
			<td><?php echo $rowmerch['produit'] ?></td>
			<td><?php echo $rowmerch['prenom'] ?></td>
			<td><?php echo $rowmerch['idclient']; ?></td>
			<td><?php echo $rowmerch['clsociete']; ?></td>
			<td><?php echo '<font color="'.((strchr($datetot, $rowmerch['datem']))?'red':'black').'">'.fdate($rowmerch['datem']) ?></font></td>
			<td><?php echo ftime($rowmerch['hin1']).' - '.ftime($rowmerch['hout1']); ?></td>
			<td><?php echo ftime($rowmerch['hin2']).' - '.ftime($rowmerch['hout2']); ?></td>
			<td><?php echo $rowmerch['ssociete'].' ('.$rowmerch['sville'].' )' ; ?></td>
			<td><?php echo $motifs[$rowmerch['motif']]; ?></td>
			<td><?php echo $rowmerch['note'] ?></td>
		</tr>
<?php } 
	$datetot .= ','.$rowmerch['datem'];
?>
			</tbody>
		</table>
<?php
}
unset ($datetot);
?>