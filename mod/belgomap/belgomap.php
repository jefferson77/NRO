<?php

/*

l	largeur de l'image		l=300
p	peoples					p=45.1-68.1





*/


############################################################################################
### Peoples #####
if (!empty($_GET['p'])) {
	$ps = explode("-", $_GET['p']); 
	foreach ($ps as $peop) {
		$var = explode(".", $peop);
		
		if ($var[1] = 1) {
			
		}
		
		
		$peoples[$var[0]] = $var[1];
	}

	$sql = "SELECT glat1, glong1, glat2, glong2 FROM people WHERE idpeople IN(".implode(", ", array_keys($peoples)).")"; $findpeople = new db(); $findpeople->inline($sql);
}



############################################################################################
### Shop #####




############################################################################################
### Fixes #####

list($R_larg, $R_haut) = getimagesize("belgomap.png"); # rech la taille de la photo originale

$fixX = 475;
$fixY = 400;
## Zero
$zeroX = 4;
$zeroY = 85;

$zeroLat = 51.0916;
$zeroLon =  2.5466;
## Ref
$refX = 375;
$refY = 388;

$refLat = 49.5083;
$refLon =  5.6166;

## Deltas
$delLat = $zeroLat - $refLat;
$delLon = $refLon - $zeroLon;

$delX = $refX - $zeroX;
$delY = $refY - $zeroY;


############################################################################################
### Point rouge #####



############################################################################################
### Dimension de l'illu #####
if (!empty($_GET['l'])) {
	$larg = $_GET['l'];
} else {
	$larg = $R_larg;
}
$haut = ($larg / $R_larg) * $R_haut;


############################################################################################
### Image de fond #####

list($o_larg, $o_haut) = getimagesize("belgomap.png"); # rech la taille de la photo originale

$image = imagecreatetruecolor($larg, $haut);

$original_image = imagecreatefrompng("belgomap.png");

imagecopyresampled($image, $original_image, 0, 0, 0, 0, $larg, $haut, $o_larg, $o_haut);

# output the image #
header("Content-type: image/jpeg");
imagejpeg($image, "", 100);

# clean up the image resources #
imagedestroy($src);
imagedestroy($image);
imagedestroy($original_image);



?>
