<?php
	if (empty($_SESSION['matosquid'])) {
		$searchfields = array (
			'ma.codematos' => 'codematos',
			'ma.mnom' => 'mnom'
		);

		$quid = $DB->MAKEsearch($searchfields);

		if (!empty($_POST['dateout'])) {
			if (!empty($quid)) { $quid .= " AND "; $quod .= " ET "; }	
			$quid = $quid."ma.dateout <= '".fdatebk($_POST['dateout'])."'";
			$quod = $quod."date <= ".fdatebk($_POST['dateout']);
		}

		if (!empty($_POST['out'])) { 
			if (!empty($quid)) { $quid .= " AND "; $quod .= " ET "; }	
			$sort = 'p.codepeople';
			$quid .= "ma.dateout IS NOT NULL";
			$quod .= "Materiel out ";
		}

		$_SESSION['matosquid'] = $quid;
	} 
	
	$lesmatos = $DB->getArray("SELECT 
			ma.idmatos, ma.idvip, ma.codematos, ma.mnom, ma.dateout AS madateout, ma.autre, ma.idpeople, 
			m.idvipjob, a.idanimation, me.idmerch,
			p.pnom, p.pprenom, p.email, p.gsm, p.codepeople,
			j.reference, a.reference AS areference, me.produit  
		FROM matos ma
			LEFT JOIN vipmission m ON ma.idvip = m.idvip 
			LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob 
			LEFT JOIN animation a ON ma.idanimation = a.idanimation 
			LEFT JOIN merch me ON ma.idmerch = me.idmerch 
			LEFT JOIN people p ON ma.idpeople = p.idpeople
		WHERE ".((!empty($_SESSION['matosquid']))?$_SESSION['matosquid']:'idmatos IS NOT NULL')."
		ORDER BY ".((!empty($sort))?$sort:'p.codepeople, ma.mnom, ma.codematos, ma.idmatos'));

?>
<div id="centerzonelarge">
	<h2>R&eacute;sultats de recherche Materiel : <?php echo $DB->quod.' ( '.count($lesmatos).' )' ;?></h2>
	<form action="<?php echo NIVO."mod/sms/adminsms.php?act=show";?>" target="centerzonelarge" method="post">
		<table class="sortable-onload-0r rowstyle-alt paginate-30 no-arrow" border="0" width="90%" cellspacing="1" align="center">
			<thead>
				<tr>
					<th class="sortable-text">Code</th>
					<th class="sortable-text">Nom</th>
					<th sortable-date-dmy>Date</th>
					<th sortable-numeric>Mission</th>
					<th class="sortable-text">Reference</th>
					<th class="sortable-text">People</th>
					<th class="sortable-text">GSM</th>
					<th class="sortable-text">mail</th>
				</tr>
			</thead>
			<tbody>
<?php
	foreach ($lesmatos as $row)
		{
			echo '<tr ondblclick="location.href=\'?act=show&idmatos='.$row['idmatos'].'\'">
					<td>'.$row['codematos'].'</td>
					<td>'.$row['mnom'].'</td>
					<td>'.(($row['madateout'] != '0000-00-00')?fdate($row['field1']):'').'</td>
					<td>'.(($row['idvip'] > 0)?'V-'.$row['idvip']:'').(($row['idmerch'] > 0)?'M-'.$row['idmerch']:'').(($row['idanimation'] > 0)?'A-'.$row['idanimation']:'').'</td>
					<td>'.$row['reference'].$row['areference'].$row['produit'].'</td>
					<td>'.((!empty($row['idpeople']))?'<a class="tab" href="../people/adminpeople.php?act=show&idpeople='.$row['idpeople'].'" target="_blank">('.$row['codepeople'].') '.$row['pnom'].' '.$row['pprenom'].'</a>':'').'</td>
					<td>'.$row['gsm'].'</td>
					<td><a href="mailto:'.$row['email'].'">'.$row['email'].'</a></td>
				</tr>';
		} ?>		
			</tbody>	
		</table>
	</form>
</div>