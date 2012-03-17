<?php
define('NIVO', '../../');

if (isset($_POST['idp'])) {	$idp = $_POST['idp'];} elseif(isset($_GET['idp'])) {$idp = $_GET['idp'];}
if (isset($_POST['sfx'])) {	$sfx = $_POST['sfx'];} elseif(isset($_GET['sfx'])) { $sfx = $_GET['sfx'];}

	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////                                                        ////////////////////
	////////////////////                      12CropImage                       ////////////////////
	////////////////////                                                        ////////////////////
	////////////////////                      v. 2.12 beta                      ////////////////////
	////////////////////                 (c) 2005 Roel Meurders                 ////////////////////
	////////////////////        mail: support [at] roelmeurders [dot] com       ////////////////////
	////////////////////                                                        ////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
	//  (copyright) info: http://roel.meurders.nl/web_php/12cropimage-2-php-image-crop-plugin/    //
	////////////////////////////////////////////////////////////////////////////////////////////////

	// include the config file
	if ($_GET['do'] != "finish" && !isset($crRotate)){
		include_once("config.php");
	}

	// extract the $_GET and $_POST variable
	extract($_GET, EXTR_SKIP);

	// first check if there is a file specified and if the file can be read...
	if (!$orgFile) {
		echo "There is no original file specified. This is compulsory...";
		exit;
	}

	$imgData = getimagesize($orgFile);
	if (!$imgData) {
		echo "The original file is no valid image...";
		exit;
	}
	//if ($imgData[2] != 2 && $imgData[2] != 3) {
	if ($imgData[2] > 3) {
		echo "The original file can only be a PNG, GIF or JPEG/JPG file...";
		exit;
	}

	$orgWidth = $imgData[0];
	$orgHeight = $imgData[1];
	$fileType = $imgData[2];

	if ($fileType == 3) {
		$img = imagecreatefrompng($orgFile);
	} else if ($fileType == 1){
		$img = imagecreatefromgif($orgFile);
	} else {
		$img = imagecreatefromjpeg($orgFile);
	}

	if (isset($crRotate)){
		if ($crRotate == "right"){
			$new = imagerotate($img, 270, 0);
		} else {
			$new = imagerotate($img, 90, 0);
		}
		@unlink($crOrgFile);
		imagejpeg($new,$crOrgFile,$crCropQuality);

	} else if ($do == "crop") {
		if (!$divWidth){ $divWidth = $orgWidth; }
		if (!$divHeight){ $divHeight = $orgHeight; }

		if($orgHeight < $divHeight && $orgWidth < $divWidth){
			$cropH = $orgHeight;
			$cropW = $orgWidth;
			$prop = 1;
		} else if (($divHeight/$divWidth) < ($orgHeight/$orgWidth)){
			$cropH = $divHeight;
			$cropW = floor(($divHeight / $orgHeight) * $orgWidth);
			$prop = $orgHeight / $cropH;
		} else {
			$cropW = $divWidth;
			$cropH = floor(($divWidth / $orgWidth) * $orgHeight);
			$prop = $orgWidth / $cropW;
		}

		$new = imagecreatetruecolor($cropW,$cropH);
		imagecopyresampled ($new, $img, 0, 0, 0, 0, $cropW, $cropH, $orgWidth, $orgHeight);
		header("Content-Type: image/jpeg");
		imagejpeg($new,"",$crCropQuality);

	} else if ($do == "preview") {
		$selX = round($crSelLeft / $crProp);
		$selY = round($crSelTop / $crProp);
		$selW = round($crSelWidth / $crProp);
		$selH = round($crSelHeight / $crProp);

		$new = imagecreatetruecolor($crWidth,$crHeight);
		imagecopyresampled ($new, $img, 0, 0, $selX, $selY, $crWidth, $crHeight, $selW, $selH);
		header("Content-Type: image/jpeg");
		imagejpeg($new,"",$crCropQuality);

	} else if ($do == "finish"){
		$selX = round($vars['crSelLeft'] / $vars['crProp']);
		$selY = round($vars['crSelTop'] / $vars['crProp']);
		$selW = round($vars['crSelWidth'] / $vars['crProp']);
		$selH = round($vars['crSelHeight'] / $vars['crProp']);

		$new = imagecreatetruecolor($vars['crWidth'],$vars['crHeight']);
		imagecopyresampled ($new, $img, 0, 0, $selX, $selY, $vars['crWidth'], $vars['crHeight'], $selW, $selH);
		@unlink($crOrgFile);
		imagejpeg($new,$crOrgFile,$crCropQuality);

	}

	imagedestroy($img);
	imagedestroy($new);
?>