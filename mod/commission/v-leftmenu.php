<div id="leftmenu" style="overflow:auto;">
<?php

$moiscommissions = $DB->getArray("SELECT MONTH(datein) as min, YEAR(datein) as yin, MONTH(dateout) as mout,YEAR(dateout) as yout FROM `commissions` GROUP BY CONCAT(YEAR(datein), '-', MONTH(datein),'-',YEAR(dateout),'-',MONTH(dateout)) ORDER BY yin, min,yout,mout");

foreach ($moiscommissions as $moiscom) {
	$actmoi = date("Ym", mktime(0,0,0,$moiscom['min'],1,$moiscom['yin']));
	$outmoi = date("Ym", mktime(0,0,0,$moiscom['mout'],1,$moiscom['yout']));
	
	for ($i=1; $actmoi <= $outmoi ; $i++) { 
		$calendar[substr($actmoi, 0, 4)][substr($actmoi, 4, 2)] = 'ON';
		$actmoi = date("Ym", mktime(0,0,0,$moiscom['min'] + $i,1,$moiscom['yin']));
	}
}

$allyears = array_keys($calendar);

foreach($allyears as $year) {
	echo '
	<table class="paymenu" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
		<tr><th colspan="3">'.$year.'</th></tr>
		<tr>
			<td'.(($activeyear.$activemonth == $year.'01')?' class="actif"':'').'>'.((in_array('01', array_keys($calendar[$year])))?'<a href="'.$_SERVER['PHP_SELF'].'?activeyear='.$year.'&activemonth=01">Jan</a>':'Jan').'</td>
			<td'.(($activeyear.$activemonth == $year.'02')?' class="actif"':'').'>'.((in_array('02', array_keys($calendar[$year])))?'<a href="'.$_SERVER['PHP_SELF'].'?activeyear='.$year.'&activemonth=02">F&eacute;v</a>':'F&eacute;v').'</td>
			<td'.(($activeyear.$activemonth == $year.'03')?' class="actif"':'').'>'.((in_array('03', array_keys($calendar[$year])))?'<a href="'.$_SERVER['PHP_SELF'].'?activeyear='.$year.'&activemonth=03">Mar</a>':'Mar').'</td>
		</tr>
		<tr>
			<td'.(($activeyear.$activemonth == $year.'04')?' class="actif"':'').'>'.((in_array('04', array_keys($calendar[$year])))?'<a href="'.$_SERVER['PHP_SELF'].'?activeyear='.$year.'&activemonth=04">Avr</a>':'Avr').'</td>
			<td'.(($activeyear.$activemonth == $year.'05')?' class="actif"':'').'>'.((in_array('05', array_keys($calendar[$year])))?'<a href="'.$_SERVER['PHP_SELF'].'?activeyear='.$year.'&activemonth=05">Mai</a>':'Mai').'</td>
			<td'.(($activeyear.$activemonth == $year.'06')?' class="actif"':'').'>'.((in_array('06', array_keys($calendar[$year])))?'<a href="'.$_SERVER['PHP_SELF'].'?activeyear='.$year.'&activemonth=06">Jun</a>':'Jun').'</td>
		</tr>
		<tr>
			<td'.(($activeyear.$activemonth == $year.'07')?' class="actif"':'').'>'.((in_array('07', array_keys($calendar[$year])))?'<a href="'.$_SERVER['PHP_SELF'].'?activeyear='.$year.'&activemonth=07">Jui</a>':'Jui').'</td>
			<td'.(($activeyear.$activemonth == $year.'08')?' class="actif"':'').'>'.((in_array('08', array_keys($calendar[$year])))?'<a href="'.$_SERVER['PHP_SELF'].'?activeyear='.$year.'&activemonth=08">Aou</a>':'Aou').'</td>
			<td'.(($activeyear.$activemonth == $year.'09')?' class="actif"':'').'>'.((in_array('09', array_keys($calendar[$year])))?'<a href="'.$_SERVER['PHP_SELF'].'?activeyear='.$year.'&activemonth=09">Sep</a>':'Sep').'</td>
		</tr>
		<tr>
			<td'.(($activeyear.$activemonth == $year.'10')?' class="actif"':'').'>'.((in_array('10', array_keys($calendar[$year])))?'<a href="'.$_SERVER['PHP_SELF'].'?activeyear='.$year.'&activemonth=10">Oct</a>':'Oct').'</td>
			<td'.(($activeyear.$activemonth == $year.'11')?' class="actif"':'').'>'.((in_array('11', array_keys($calendar[$year])))?'<a href="'.$_SERVER['PHP_SELF'].'?activeyear='.$year.'&activemonth=11">Nov</a>':'Nov').'</td>
			<td'.(($activeyear.$activemonth == $year.'12')?' class="actif"':'').'>'.((in_array('12', array_keys($calendar[$year])))?'<a href="'.$_SERVER['PHP_SELF'].'?activeyear='.$year.'&activemonth=12">D&eacute;c</a>':'D&eacute;c').'</td>
		</tr>
	</table>
	<br>
	';
}
?>
</div>	
