<?php
$notefraislist = $DB->getArray('SELECT * FROM notefrais');

foreach($notefraislist as $notefrais) {
	if($notefrais['secteur']=='VI') $infoDB = array('missiontable' => 'vipmission', 'missionfield'=>'idvip', 'datefield'=>'vipdate');
	else if($notefrais['secteur']=='AN') $infoDB = array('missiontable' => 'animation', 'missionfield'=>'idanimation', 'datefield'=>'datem');
	else if($notefrais['secteur']=='ME') $infoDB = array('missiontable' => 'merch', 'missionfield'=>'idmerch', 'datefield'=>'datem');
	$list = $DB->getRow('SELECT '.$infoDB['datefield'].' AS date
							FROM '.$infoDB['missiontable'].' 
							WHERE '.$infoDB['missionfield'].'='.$notefrais['idmission'].'
							ORDER BY date DESC');
	$calendar[substr($list['date'],0,4)][substr($list['date'],5,2)] = '';
}

$allyears = array_keys($calendar);
foreach($allyears as $year) {
	echo '
	<table class="paymenu" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
		<tr><th colspan="3">'.$year.'</th></tr>
		<tr>
			<td>'; if (in_array('01', array_keys($calendar[$year]))) { echo '<a href="'.$_SERVER['PHP_SELF'].'?nfyear='.$year.'&nfmonth=01">Jan</a>'; } else { echo'Jan';} echo '</td>
			<td>'; if (in_array('02', array_keys($calendar[$year]))) { echo '<a href="'.$_SERVER['PHP_SELF'].'?nfyear='.$year.'&nfmonth=02">F&eacute;v</a>'; } else { echo'F&eacute;v';} echo '</td>
			<td>'; if (in_array('03', array_keys($calendar[$year]))) { echo '<a href="'.$_SERVER['PHP_SELF'].'?nfyear='.$year.'&nfmonth=03">Mar</a>'; } else { echo'Mar';} echo '</td>
		</tr>
		<tr>
			<td>'; if (in_array('04', array_keys($calendar[$year]))) { echo '<a href="'.$_SERVER['PHP_SELF'].'?nfyear='.$year.'&nfmonth=04">Avr</a>'; } else { echo'Avr';} echo '</td>
			<td>'; if (in_array('05', array_keys($calendar[$year]))) { echo '<a href="'.$_SERVER['PHP_SELF'].'?nfyear='.$year.'&nfmonth=05">Mai</a>'; } else { echo'Mai';} echo '</td>
			<td>'; if (in_array('06', array_keys($calendar[$year]))) { echo '<a href="'.$_SERVER['PHP_SELF'].'?nfyear='.$year.'&nfmonth=06">Jun</a>'; } else { echo'Jun';} echo '</td>
		</tr>
		<tr>
			<td>'; if (in_array('07', array_keys($calendar[$year]))) { echo '<a href="'.$_SERVER['PHP_SELF'].'?nfyear='.$year.'&nfmonth=07">Jui</a>'; } else { echo'Jui';} echo '</td>
			<td>'; if (in_array('08', array_keys($calendar[$year]))) { echo '<a href="'.$_SERVER['PHP_SELF'].'?nfyear='.$year.'&nfmonth=08">Aou</a>'; } else { echo'Aou';} echo '</td>
			<td>'; if (in_array('09', array_keys($calendar[$year]))) { echo '<a href="'.$_SERVER['PHP_SELF'].'?nfyear='.$year.'&nfmonth=09">Sep</a>'; } else { echo'Sep';} echo '</td>
		</tr>
		<tr>
			<td>'; if (in_array('10', array_keys($calendar[$year]))) { echo '<a href="'.$_SERVER['PHP_SELF'].'?nfyear='.$year.'&nfmonth=10">Oct</a>'; } else { echo'Oct';} echo '</td>
			<td>'; if (in_array('11', array_keys($calendar[$year]))) { echo '<a href="'.$_SERVER['PHP_SELF'].'?nfyear='.$year.'&nfmonth=11">Nov</a>'; } else { echo'Nov';} echo '</td>
			<td>'; if (in_array('12', array_keys($calendar[$year]))) { echo '<a href="'.$_SERVER['PHP_SELF'].'?nfyear='.$year.'&nfmonth=12">D&eacute;c</a>'; } else { echo'D&eacute;c';} echo '</td>
		</tr>
	</table>
	';
}
?>