<?php
$totexp = 0;
############################################################################################################
#####  VIP  ############################################################################################################
$vips = $DB->getArray("SELECT
		m.idvipjob, m.vipdate, m.vipin, m.vipout, m.idvip, m.km, m.vipactivite, m.contratencode,
		j.idclient, j.etat, j.reference,
		a.prenom,
		c.societe AS clsociete,
		s.societe AS shsociete, s.ville,
		MAX(d.iddecl) AS iddecl
	FROM vipmission m
		LEFT JOIN people p ON m.idpeople = p.idpeople
		LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob
		LEFT JOIN agent a ON j.idagent = a.idagent
		LEFT JOIN client c ON j.idclient = c.idclient
		LEFT JOIN dimona.declarations d ON (p.nrnational = d.rna AND m.vipdate = d.datein)
		LEFT JOIN shop s ON m.idshop = s.idshop
	WHERE m.idpeople = ".$_REQUEST['idpeople']."
	GROUP BY m.idvip
	ORDER BY  m.vipdate DESC, m.vipactivite DESC");

if (count($vips) > 0) {
		$totexp += count($vips);
?>
		<table class="sortable rowstyle-alt no-arrow" border="0" width="99%" cellspacing="1" align="center">
			<thead>
				<tr><th colspan="13" style="font-size: 14px;">VIP</th></tr>
				<tr>
					<th width="16"></th>
					<th width="16"></th>
					<th>Job</th>
					<th class="sortable-text">R&eacute;f&eacute;rence</th>
					<th class="sortable-text">Assi.</th>
					<th class="sortable-text" colspan="2">Client</th>
					<th class="sortable-date-dmy">Date</th>
					<th>In</th>
					<th>Out</th>
					<th class="sortable-text">Lieux</th>
					<th>Km</th>
					<th class="sortable-text">Activit&eacute;</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($vips as $row): ?>
	<?php $decl = ($row['iddecl'] > 0)?$DB->getRow("SELECT d.numaccuse, f.datesend FROM dimona.declarations d LEFT JOIN dimona.fichiers f ON d.idfile = f.idfile WHERE d.iddecl = ".$row['iddecl']):array(); ?>
				<tr>
					<td><?php echo ($decl['numaccuse'] > 0)?'<img src="'.STATIK.'illus/flag_green.png" style="margin: -2px;" alt="" width="16" height="16" title="Dimo : '.$decl['numaccuse'].' le :'.$decl['datesend'].'">':''; ?></td>
					<td style="width: 10px;"><img src='<?php echo STATIK.'/nro/illus/'.(($row['contratencode'] == "1")?'tick_small.png':'exclamation_small.png'); ?>' width="10" height="10" alt="Contrat"></td>
					<td><a href="<?php echo NIVO.'vip/adminvip.php?act=show&idvipjob='.$row['idvipjob'].'&etat='.$row['etat']; ?>" target="_blank"><?php echo $row['idvipjob']; ?></a></td>
					<td align="center"><?php echo $row['reference'] ?></td>
					<td align="center"><?php echo $row['prenom'] ?></td>
					<td align="center"><?php echo $row['idclient']; ?></td>
					<td align="center"><?php echo $row['clsociete']; ?></td>
					<td align="center"><?php echo '<font color="'.((strchr($datetot, $row['vipdate']))?'red':'black').'">'.fdate($row['vipdate']) ?></color></td>
					<td align="center"><?php echo ftime($row['vipin']) ?></td>
					<td align="center"><?php echo ftime($row['vipout']) ?></td>
					<td align="center"><?php echo $row['shsociete'].' ('.$row['ville'].' )' ; ?></td>
					<td align="center"><?php echo $row['km'] ?></td>
					<td align="center"><?php echo $row['vipactivite'] ?></td>
				</tr>
<?php $datetot .= ','.$row['vipdate']; ?>				
<?php endforeach ?>
			</tbody>
		</table>
<?php }
############################################################################################################
#####  ANIM  ############################################################################################################
		$anims = $DB->getArray("SELECT
				an.datem, an.idanimjob, an.reference, an.hin1, an.hout1, an.hin2, an.hout2, an.ccheck,
				j.idclient, 
				a.prenom,
				c.societe AS clsociete,
				s.societe AS shsociete, s.ville AS sville, 
				MAX(d.iddecl) AS iddecl
			FROM animation an
				LEFT JOIN animjob j ON an.idanimjob = j.idanimjob 
				LEFT JOIN agent a ON j.idagent = a.idagent 
				LEFT JOIN client c ON j.idclient = c.idclient 
				LEFT JOIN shop s ON an.idshop = s.idshop
				LEFT JOIN people p ON an.idpeople = p.idpeople
				LEFT JOIN dimona.declarations d ON (p.nrnational = d.rna AND an.datem = d.datein)
			WHERE an.idpeople = ".$_REQUEST['idpeople']."
			GROUP BY an.idanimation
			ORDER BY an.datem DESC");

	if (count($anims) > 0) {
		$totexp += count($anims);
		
?>
		<table class="sortable rowstyle-alt no-arrow" border="0" width="99%" cellspacing="1" align="center">
			<thead>
				<tr><th colspan="11">ANIM</th></tr>
				<tr>
					<th width="16"></th>
					<th width="16"></th>
					<th>Job</th>
					<th class="sortable-text">R&eacute;f&eacute;rence</th>
					<th class="sortable-text">Assi.</th>
					<th class="sortable-text" colspan="2">Client</th>
					<th class="sortable-date-dmy">Date</th>
					<th>AM</th>
					<th>PM</th>
					<th class="sortable-text">Lieux</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($anims as $rowanim): ?>
	<?php $decl = ($rowanim['iddecl'] > 0)?$DB->getRow("SELECT d.numaccuse, f.datesend FROM dimona.declarations d LEFT JOIN dimona.fichiers f ON d.idfile = f.idfile WHERE d.iddecl = ".$rowanim['iddecl']):array(); ?>
				<tr>
					<td><?php echo ($decl['numaccuse'] > 0)?'<img src="'.STATIK.'illus/flag_green.png" style="margin: -2px;" alt="" width="16" height="16" title="Dimo : '.$decl['numaccuse'].' le :'.$decl['datesend'].'">':''; ?></td>
					<td style="width: 10px;"><img src='<?php echo STATIK.'/nro/illus/'.(($rowanim['ccheck'] == "Y")?'tick_small.png':'exclamation_small.png'); ?>' width="10" height="10" alt="Contrat"></td>
					<td><a href="<?php echo NIVO.'animation2/adminanim.php?act=showjob&idanimjob='.$rowanim['idanimjob']; ?>" target="_blank"><?php echo $rowanim['idanimjob']; ?></a></td>
					<td align="center"><?php echo $rowanim['reference'] ?></td>
					<td align="center"><?php echo $rowanim['prenom'] ?></td>
					<td align="center"><?php echo $rowanim['idclient']; ?></td>
					<td align="center"><?php echo $rowanim['clsociete']; ?></td>
					<td align="center"><?php echo '<font color="'.((strchr($datetot, $rowanim['datem']))?'red':'black').'">'.fdate($rowanim['datem']) ?></color></td>
					<td align="center"><?php echo ftime($rowanim['hin1']).' - '.ftime($rowanim['hout1']); ?></td>
					<td align="center"><?php echo ftime($rowanim['hin2']).' - '.ftime($rowanim['hout2']); ?></td>
					<td align="center"><?php echo $rowanim['shsociete'].' ('.$rowanim['sville'].' )' ; ?></td>
				</tr>
<?php $datetot .= ','.$rowanim['datem']; ?>
<?php endforeach ?>
			</tbody>
		</table>
<?php }
############################################################################################################
#####  MERCH  ############################################################################################################
		$merchs = $DB->getArray("SELECT
				me.idmerch, me.idclient,
					me.produit, me.datem, me.hin1, me.hout1, me.hin2, me.hout2, me.contratencode,
				a.prenom, 
				c.societe AS clsociete, 
				s.societe AS ssociete, s.ville AS sville, 
				MAX(d.iddecl) AS iddecl
			FROM merch me
				LEFT JOIN people p ON me.idpeople = p.idpeople
				LEFT JOIN agent a ON me.idagent = a.idagent 
				LEFT JOIN client c ON me.idclient = c.idclient 
				LEFT JOIN shop s ON me.idshop = s.idshop
				LEFT JOIN dimona.declarations d ON p.nrnational = d.rna AND me.datem = d.datein
			WHERE me.idpeople = ".$_REQUEST['idpeople']."
			GROUP BY me.idmerch
			ORDER BY me.datem DESC");

	if (count($merchs) > 0) {
		$totexp += count($merchs);
	?>
		<table class="sortable rowstyle-alt no-arrow" border="0" width="99%" cellspacing="1" align="center">
			<thead>
				<tr><th colspan="11">MERCH</th></tr>
				<tr>
					<th width="16"></th>
					<th width="16"></th>
					<th>Mis.</th>
					<th class="sortable-text">R&eacute;f&eacute;rence</th>
					<th class="sortable-text">Assi.</th>
					<th class="sortable-text" colspan="2">Client</th>
					<th class="sortable-date-dmy">Date</th>
					<th>AM</th>
					<th>PM</th>
					<th class="sortable-text">Lieux</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ($merchs as $rowmerch): ?>
	<?php $decl = ($rowmerch['iddecl'] > 0)?$DB->getRow("SELECT d.numaccuse, f.datesend FROM dimona.declarations d LEFT JOIN dimona.fichiers f ON d.idfile = f.idfile WHERE d.iddecl = ".$rowmerch['iddecl']):array(); ?>
				<tr>
					<td><?php echo ($decl['numaccuse'] > 0)?'<img src="'.STATIK.'illus/flag_green.png" style="margin: -2px;" alt="" width="16" height="16" title="Dimo : '.$decl['numaccuse'].' le :'.$decl['datesend'].'">':''; ?></td>
					<td style="width: 10px;"><img src="<?php echo STATIK.'/nro/illus/'.(($rowmerch['contratencode'] == 1)?'tick_small.png':'exclamation_small.png'); ?>" width="10" height="10" alt="Contrat"></td>
					<td><a href="<?php echo NIVO.'merch/adminmerch.php?act=show&act2=listing&idmerch='.$rowmerch['idmerch']; ?>" target="_blank"><?php echo $rowmerch['idmerch']; ?></a></td>
					<td align="center"><?php echo $rowmerch['produit'] ?></td>
					<td align="center"><?php echo $rowmerch['prenom'] ?></td>
					<td align="center"><?php echo $rowmerch['idclient']; ?></td>
					<td align="center"><?php echo $rowmerch['clsociete']; ?></td>
					<td align="center"><?php echo '<font color="'.((strchr($datetot, $rowmerch['datem']))?'red':'black').'">'.fdate($rowmerch['datem']) ?></color></td>

					<td align="center"><?php echo ftime($rowmerch['hin1']).' - '.ftime($rowmerch['hout1']); ?></td>
					<td align="center"><?php echo ftime($rowmerch['hin2']).' - '.ftime($rowmerch['hout2']); ?></td>
					<td align="center"><?php echo $rowmerch['ssociete'].' ('.$rowmerch['sville'].' )' ; ?></td>
				</tr>
<?php $datetot .= ','.$rowmerch['datem']; ?>
<?php endforeach ?>				
			</tbody>
		</table>
<?php
	}
	if ($totexp == 0) {
		echo '<div align="center">No Experience</div>';
	}
?>