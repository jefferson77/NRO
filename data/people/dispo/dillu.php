<?php

# Emploi : 
# dillu.php?size=B&disp=3&hhcode=000000011001111000000000

if ($_GET['size'] == 'B') {
	$size['largillu'] = 20;
	$size['hautillu'] = 23;
} else {
	$size['largillu'] = 8;
	$size['hautillu'] = 11;
}

$hautzone = ($size['hautillu'] - 2) / 3;

$img = imagecreatetruecolor($size['largillu'],$size['hautillu']);

## Couleurs ##
$bgcol = imagecolorallocate($img,0,0,0);		# noir de fond
$C1 = imagecolorallocate($img,0,204,0);		# vert de ON
$C2 = imagecolorallocate($img,255,139,0);	# orange de busy
if ($_GET['size'] == 'B') {
	$C0 = imagecolorallocate($img,170,170,170);	# gris de non dispo
} else {
	$C0 = imagecolorallocate($img,255,0,0);	# rouge de non dispo
}

# Dispo
$disp[0] = '000';
$disp[1] = '100';
$disp[2] = '010';
$disp[3] = '110';
$disp[4] = '001';
$disp[5] = '101';
$disp[6] = '011';
$disp[7] = '111';
$disp[8] = '000';

$coul1 = 'C'.substr($disp[$_GET['disp']], 0, 1);
$coul2 = 'C'.substr($disp[$_GET['disp']], 1, 1);
$coul3 = 'C'.substr($disp[$_GET['disp']], 2, 1);

# HHcode

if (!empty($_GET['hhcode'])) {
	if (array_sum(preg_split('//',substr($_GET['hhcode'], 0, 9),-1,PREG_SPLIT_NO_EMPTY)) > 0) $coul1 = 'C2'; 
	if (array_sum(preg_split('//',substr($_GET['hhcode'], 9, 6),-1,PREG_SPLIT_NO_EMPTY)) > 0) $coul2 = 'C2'; 
	if (array_sum(preg_split('//',substr($_GET['hhcode'], 15, 9),-1,PREG_SPLIT_NO_EMPTY)) > 0) $coul3 = 'C2';
}

# Tracage
imagefill($img,0,0,$bgcol);

ImageFilledRectangle($img,0,0,$size['largillu'],$hautzone - 1,$$coul1);
ImageFilledRectangle($img,0,$hautzone + 1,$size['largillu'],$hautzone * 2,$$coul2);
ImageFilledRectangle($img,0,($hautzone + 1) * 2,$size['largillu'],$size['hautillu'],$$coul3);

header("Content-type: image/png");
imagepng($img);
imagedestroy($img);
?>