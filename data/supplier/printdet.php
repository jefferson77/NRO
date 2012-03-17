<div id="leftmenu" style="overflow:auto;">
	<div id="accordion">
<?php

$thisyear = substr($_REQUEST['period'], 0, 4);
$lastdate = date("Y-m-d", strtotime('+3 month'));

//création du menu de gauche

## Merch
$row = $DB->getColumn("SELECT DATE_FORMAT(m.datem, '%Y%m') as period
						FROM merch m
							LEFT JOIN people p ON m.idpeople = p.idpeople
						WHERE p.idsupplier > 0
							AND m.datem BETWEEN '2003-01-01' AND '$lastdate'
						GROUP BY DATE_FORMAT(m.datem, '%Y%m')

					UNION

						SELECT DATE_FORMAT(a.datem, '%Y%m') as period
						FROM animation a
							LEFT JOIN people p ON a.idpeople = p.idpeople
						WHERE p.idsupplier > 0
							AND a.datem BETWEEN '2003-01-01' AND '$lastdate'
						GROUP BY DATE_FORMAT(a.datem, '%Y%m')

					UNION

						SELECT DATE_FORMAT(v.vipdate, '%Y%m') as period
						FROM vipmission v
							LEFT JOIN people p ON v.idpeople = p.idpeople
						WHERE p.idsupplier > 0
							AND v.vipdate BETWEEN '2003-01-01' AND '$lastdate'
						GROUP BY DATE_FORMAT(v.vipdate, '%Y%m')

					ORDER BY period");

$min = substr(min($row), 0, 4);
$max = substr(max($row), 0, 4);

$i=0;
while($min != $max+1) {
		$tyears[$min] = $i;

		echo '<h3><a href="#">'.$min.'</a></h3>
	    <div style="margin: 0;padding:0">
	    	<table class="paymenu" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
		<tr>
			<td>'.((in_array($min.'01', $row))?'<a href="?period='.$min.'01&act=bdc">Jan</a>':'Jun').'</td>
			<td>'.((in_array($min.'02', $row))?'<a href="?period='.$min.'02&act=bdc">F&eacute;v</a>':'F&eacute;v').'</td>
			<td>'.((in_array($min.'03', $row))?'<a href="?period='.$min.'03&act=bdc">Mar</a>':'Mar').'</td>
		</tr>
		<tr>
			<td>'.((in_array($min.'04', $row))?'<a href="?period='.$min.'04&act=bdc">Avr</a>':'Avr').'</td>
			<td>'.((in_array($min.'05', $row))?'<a href="?period='.$min.'05&act=bdc">Mai</a>':'Mai').'</td>
			<td>'.((in_array($min.'06', $row))?'<a href="?period='.$min.'06&act=bdc">Jun</a>':'Jun').'</td>
		</tr>
		<tr>
			<td>'.((in_array($min.'07', $row))?'<a href="?period='.$min.'07&act=bdc">Jun</a>':'Jui').'</td>
			<td>'.((in_array($min.'08', $row))?'<a href="?period='.$min.'08&act=bdc">Aou</a>':'Aou').'</td>
			<td>'.((in_array($min.'09', $row))?'<a href="?period='.$min.'09&act=bdc">Sep</a>':'Sep').'</td>
		</tr>
		<tr>
			<td>'.((in_array($min.'10', $row))?'<a href="?period='.$min.'10&act=bdc">Oct</a>':'Oct').'</td>
			<td>'.((in_array($min.'11', $row))?'<a href="?period='.$min.'11&act=bdc">Nov</a>':'Nov').'</td>
			<td>'.((in_array($min.'12', $row))?'<a href="?period='.$min.'12&act=bdc">D&eacute;c</a>':'D&eacute;c').'</td>
		</tr></table></div>';

	$min++;
	$i++;
} ?>
	</div>
</div>
<div id="infozone" align="center">
<?php
		$suppliers1 = $DB->getArray("SELECT p.idsupplier, s.societe FROM people p
			LEFT JOIN merch m ON p.idpeople = m.idpeople
			LEFT JOIN supplier s ON p.idsupplier = s.idsupplier
			WHERE p.idsupplier > 0 AND DATE_FORMAT(m.datem, '%Y%m') LIKE '".$_REQUEST['period']."'
			GROUP BY p.idsupplier
			ORDER BY p.idsupplier");

		foreach($suppliers1 as $sup1) $suppliers[$sup1['idsupplier']] = $sup1['societe'];

		$suppliers2 = $DB->getArray("SELECT p.idsupplier, s.societe FROM people p
			LEFT JOIN animation a ON p.idpeople = a.idpeople
			LEFT JOIN supplier s ON p.idsupplier = s.idsupplier
			WHERE p.idsupplier > 0 AND DATE_FORMAT(a.datem, '%Y%m') LIKE '".$_REQUEST['period']."'
			GROUP BY p.idsupplier
			ORDER BY p.idsupplier;");

		foreach($suppliers2 as $sup1) $suppliers[$sup1['idsupplier']] = $sup1['societe'];

		$suppliers3 = $DB->getArray("SELECT p.idsupplier, s.societe FROM people p
			LEFT JOIN vipmission v ON p.idpeople = v.idpeople
			LEFT JOIN supplier s ON p.idsupplier = s.idsupplier
			WHERE p.idsupplier > 0 AND DATE_FORMAT(v.vipdate, '%Y%m') LIKE '".$_REQUEST['period']."'
			GROUP BY p.idsupplier
			ORDER BY p.idsupplier;");

		foreach($suppliers3 as $sup1) $suppliers[$sup1['idsupplier']] = $sup1['societe'];

		ksort($suppliers);
?>

		Sous-traités : 	<br><br>
		<?php foreach($suppliers as $supplier=>$key) { ?>
			<a href="<?php echo makebdcsup($_REQUEST['period'],$supplier); ?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"> <?php echo $key." (".$supplier.")"; ?></a>
		<?php echo "<br><br>"; }?>

		Bons de livraison : <br><br>

		<?php
		$supliv = $DB->getArray("SELECT b.idsupplier, s.societe
							FROM bonlivraison b
							LEFT JOIN supplier s ON s.idsupplier = b.idsupplier
							WHERE b.etat='in'
								AND DATE_FORMAT(b.date, '%Y%m') = '".$_REQUEST['period']."'");
		$supliv = array_unique($supliv);
		foreach($supliv as $liv) {
			?><a href="<?php echo makebdlsup($_REQUEST['period'],$liv['idsupplier']); ?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"> <?php echo $liv['societe']." (".$liv['idsupplier'].")"; ?></a>
	<?php } ?>
</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('#accordion').accordion({ active: <?php echo $tyears[$thisyear] ?> });
	});
</script>