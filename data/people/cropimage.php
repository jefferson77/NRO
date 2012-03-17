<?php
# Entete de page
define('NIVO', '../../'); 
# Classes utilisées
require_once(NIVO."nro/fm.php");
include NIVO."classes/photo.php";

////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////                                                //////////////////////////
//////////////////////////                 1 2 CROP IMAGE                 //////////////////////////
//////////////////////////                                                //////////////////////////
//////////////////////////             (c) 2002 Roel Meurders             //////////////////////////
//////////////////////////         mail: scripts@roelmeurders.com         //////////////////////////
//////////////////////////                  version 0.2                   //////////////////////////
//////////////////////////                                                //////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
///// CREDITS: Most Javascript is taken from DHTMLCentral.com and is made by Thomas Brattli. ///////
////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////
// SET VARIABLES ///////////////////////////////////////////////////////////////////////////////////

$idp = $_GET['idp'];


if ($_GET['picnb'] == 2) {
	$nomphoto = str_repeat("0", 6 - strlen($idp)).$idp."-b";
} else {
	$nomphoto = str_repeat("0", 6 - strlen($idp)).$idp;
}

GetPhotoPath($nomphoto,'raw', 'path', $sfx = '');
$org = GetPhotoPath($nomphoto,'raw', 'path', $sfx = ''); //where the original can be found (incl fullpath);
$res = GetPhotoPath($nomphoto,'', 'path', $sfx = ''); //how the result should be saved (incl fullpath);
$crw = 250; // sets the heigth to crop to;
$crh = 200; // sets the heigth to crop to;

$maxwinw = 500; //Sets the maxmimum width for displaying the original while cropping
$maxwinh = 500; //Same, only this time it's the height

$jpegqual = 75; //Sets the jpeg quality
$redirect = "cropimage.php"; //Sets the file to which the forms should be posted Change you include this file in another.
$javafile = "/classes/cropimage.js"; //Relative path to js-file
$gdversion = 2; // set to 2 if you have gd2 installed
$spacer = "/illus/spacer.gif"; //Relactive path tpo spacer.gif, transparent image

$txt['cropimage'] = "crop";
$txt['preview'] = "preview";
$txt['bigger'] = "+";
$txt['smaller'] = "-";
$txt['closewindow'] = "close window";
$txt['selectioninpicture'] = "The selection has to be completely on the image.";


////////////////////////////////////////////////////////////////////////////////////////////////////
// GET PROPORTIONS /////////////////////////////////////////////////////////////////////////////////

if ($crA != "nada"){
$size = getimagesize($org);
$trueW = $size[0];
$trueH = $size[1];

if($trueH<$maxwinh && $trueW<$maxwinw){
	 $imgH = $trueH;
	 $imgW = $trueW;
	 $imgProp = 1;
} else {
	 if (($maxwinh/$maxwinw) < ($trueH/$trueW)){
	 $imgH = $maxwinh;
	 $imgW = ($maxwinh / $trueH) * $trueW;
	 $imgProp = $trueH / $imgH;
	 } else {
	 $imgW = $maxwinw;
	 $imgH = ($maxwinw / $trueW) * $trueH;
	 $imgProp = $trueW / $imgW;
	 }
}
}


////////////////////////////////////////////////////////////////////////////////////////////////////
// FUNCTION TO SHOW THE USER INTERFACE /////////////////////////////////////////////////////////////

function CR_showUI(){
global $txt, $imgW, $imgH, $imgProp, $spacer, $redirect, $javafile, $crw, $crh, $org, $res, $idp;
?>
<html>
<head>
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="PRAGMAS" CONTENT="NO-CACHE">

<title>Crop image</title>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf8">
<script language="JavaScript" src="<?php echo $javafile?>"></script>
<script language="JavaScript">
<!--
	function libinit(){
	 obj=new lib_obj('cropDiv')
	 obj.dragdrop()
	}

	function cropCheck(crA){
	 if ((((obj.x + obj.cr)-11) <= <?php echo $imgW?>)&&(((obj.y + obj.cb)-11) <= <?php echo $imgH?>)&&(obj.x >= 11)&&(obj.y >= 11)){
		var url = '<?php echo $redirect?>?idp=<?php echo $idp?>&crA='+crA+'&org=<?php echo $org?>&res=<?php echo $res?>&crw=<?php echo $crw?>&crh=<?php echo $crh?>&l='+(obj.x-11)+'&t='+(obj.y-11)+'&s='+obj.cr;
		if (crA == 'pre'){
		 window.open(url,'prevWin','width=<?php echo $crw?>,height=<?php echo $crh?>');
		} else {
		 location.href=url;
		}
	 } else {

		alert('<?php echo $txt['selectioninpicture']?>');
	 }
	}

	function stopZoom() {
	 loop = false;
	 clearTimeout(zoomtimer);
	}

	function cropZoom(dir) {
	 loop = true;
	 prop = <?php echo $crh?> / <?php echo $crw?>;
	 zoomtimer = null;
	 direction = dir;
	 if (loop == true) {
		if (direction == "in") {
		 if ((obj.cr > <?php echo ($crw/$imgProp)?>)&&(obj.cb > <?php echo ($crh/$imgProp)?>)){
			cW = obj.cr - 1;
			cH = parseInt(prop * cW);
			obj.clipTo(0,cW,cH,0,1);
		 }
		} else {
		 if ((obj.cr < (<?php echo $imgW?>-5))&&(obj.cb < (<?php echo $imgH?>-5))){
			cW = obj.cr + 1;
			cH = parseInt(prop * cW);
			obj.clipTo(0,cW,cH,0,1);
		 }
		}
		zoomtimer = setTimeout("cropZoom(direction)", 10);
	 }
	}

	onload=libinit;
// -->
</script>
<style>
body{font-family:arial,helvetica; font-size:12px}
#cropDiv{position:absolute; left:11px; top:11px; width:<?php echo ($crw/$imgProp)?>px; height:<?php echo ($crh/$imgProp)?>px; z-index:2; background-image: url(<?php echo $spacer?>); }
</style>
</head>
<body bgcolor="#FFFFFF" text="#000066" link="#000066" alink="#000066" vlink="#000066" marginwidth="10" marginheight="10" topmargin="10" leftmargin="10">
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left" valign="top"><IMG SRC="<?php echo $redirect?>?idp=<?php echo $idp?>&crA=img&org=<?php echo $org?>&res=<?php echo $res?>&crw=<?php echo $crw?>&crh=<?php echo $crh?>" border="1"></td>
</tr>
<tr>
	<td><img src="<?php echo $spacer?>" border=0 height="5" width="5"></td>
</tr>
<tr>
	<td align="right">
	 <input type="button" name="Submit1" value="<?php echo $txt['cropimage']?>" onclick="cropCheck('def');">
	 <input type="button" name="Submit3" value="<?php echo $txt['smaller']?>" onMouseDown="cropZoom('in');" onMouseUp="stopZoom();">
	 <input type="button" name="Submit4" value="<?php echo $txt['bigger']?>" onMouseDown="cropZoom('out');" onMouseUp="stopZoom();">
	</td>
</tr>
</table>
<div id="cropDiv">
<table width="100%" height="100%" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
	<tr>
	 <td><img src="<?php echo $spacer?>"></td>
	</tr>
</table>
</div>
</body>
</html>
<?php
}


////////////////////////////////////////////////////////////////////////////////////////////////////
// FUNCTION TO SHOW THE CROP PREVIEW ///////////////////////////////////////////////////////////////

function CR_showPrev($t,$l,$s){
global $txt, $imgW, $imgH, $redirect, $crh, $crw, $org, $res, $idp;
?>
<html>
<head>
<title>crop voorbeeld</title>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="top.focus();">
<a href="#" onClick="top.close();"><img alt="<?php echo $txt['closewindow']?>" src="<?php echo $redirect?>?idp=<?php echo $idp?>&crA=crop&org=<?php echo $org?>&res=<?php echo $res?>&crw=<?php echo $crw?>&crh=<?php echo $crh?>&l=<?php echo $l?>&t=<?php echo $t?>&s=<?php echo $s?>" border=0></a>
</body>
</html>
<?php
}


////////////////////////////////////////////////////////////////////////////////////////////////////
// FUNCTION TO ACTUALLY MAKE THE CROP //////////////////////////////////////////////////////////////

function CR_make_crop($l,$t,$s,$w,$h){
global $org,$imgProp, $gdversion, $idp;
$l1 = ceil($imgProp * $l);
$t1 = ceil($imgProp * $t);
$s1 = ceil($imgProp * $s);
$s2 = ceil(($h / $w)* $s1);
if ($gdversion == 2){
	 $new = imagecreatetruecolor($w,$h);
} else {
	 $new = imagecreate($w,$h);
}
$img = imagecreatefromjpeg ($org);
imagecopyresized ($new, $img, 0, 0, $l1, $t1, $w, $h, $s1, $s2);
imagedestroy($img);
return $new;
}


////////////////////////////////////////////////////////////////////////////////////////////////////
// SCRIPT CONTROL VIA VARIABLE $crA ////////////////////////////////////////////////////////////////

switch($_GET['crA']){
case img:
	 header("Content-Type: image/jpeg");
	 $im = CR_make_crop(0,0,($trueW/$imgProp),$imgW,$imgH);
	 imagejpeg($im,"",$jpegqual);
	 imagedestroy($im);
	 exit;
case crop:

	 header("Content-Type: image/jpeg");
	 $im = CR_make_crop($_GET['l'],$_GET['t'],$_GET['s'],$_GET['crw'],$_GET['crh']);
	 imagejpeg($im,"",$jpegqual);
	 imagedestroy($im);
	 exit;
case pre:
	 CR_showPrev($t,$l,$s);
	 exit;
case def:
	 $im = CR_make_crop($_GET['l'],$_GET['t'],$_GET['s'],$_GET['crw'],$_GET['crh']);
	 imagejpeg($im,$res,$jpegqual);
	 imagedestroy($im);
	 
	 ?> 
<table border="0" align="center" cellspacing="1" cellpadding="2">
	<tr align="center">
		<td colspan="2">
			<img src="/photo/<?php echo GetMillier($nomphoto)."/".$nomphoto; ?>.jpg" border="0">
		</td>
	</tr>
</table>
	 <?php
	 
	 break;
default:
	 CR_showUI();
	 exit;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////
// END OF STORY
?>
