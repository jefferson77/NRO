<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
</head>

<body>

<?php 
$swf=$_GET['swf']; #section
$page=$_GET['page'];
$lang=$_GET['lang'];

	$lang = $_GET['lang'];
	if ($lang == '') {$lang = 'fr';}
	$swf = $_GET['swf'];
	if ($swf == '') {$swf = 'accueil';}
	$swf .= '_'.$lang;
?>

<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="155" height="281">
  <param name="movie" value="../flash/<?php echo $swf; ?>.swf?page=<?php echo $page; ?>&section=<?php echo $swf; ?>&lang=<?php echo $lang; ?>">
  <param name="quality" value="high">
  <embed src="../flash/<?php echo $swf; ?>.swf?page=<?php echo $page; ?>&section=<?php echo $swf; ?>&lang=<?php echo $lang; ?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="155" height="281"></embed>
</object>

</body>
</html>
