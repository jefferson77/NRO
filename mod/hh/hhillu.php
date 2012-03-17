<?php
# Emploi : 
# hhillu.php?idpeople=456&date=2005-12-31&mission=AN20000

define('NIVO', '../../'); 

include_once(NIVO.'classes/hh.php');

$img = imagecreatetruecolor(200,10);


## Double Booking
$ht = new hh(); 
$ht->hhtable ($_GET['idpeople'], $_GET['date'], $_GET['date']) ;

if (count($ht->prtab[$date]) > 1) {
	foreach ($ht->prtab[$date] as $val) {
		$sum = sprintf("%024.0f",$sum + $val['hh']);
	}

	if(preg_match('/[2-9]/', $sum)) {
		## Tableau Rouge : Double booking
		echo '<div style="border-color: red; border-width: 2px; border-style: solid;width: 200px; display: block;">';
		echo $autresmissions;
		echo '</div>';
	} else {
		## Tableau vert (pas de chevauchement
		echo '<div style="border-color: green; border-width: 2px; border-style: solid;width: 200px; display: block;">';
		echo $autresmissions;
		echo '</div>';
	}
}


header("Content-type: image/png");
imagepng($img);
imagedestroy($img);
?>