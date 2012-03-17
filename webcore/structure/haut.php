<?php
$page=$_GET['page'];
$section=$_GET['section'];
$lang=$_GET['lang'];
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
</head>

<body>
<font size = 1>
<?php
function Parse($variable,$valeur){
echo '&$section=animation';
}
?>
</font>

<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="645" height="101">
  <param name="movie" value="../flash/menu_up_<?php echo $lang; ?>.swf?page=<?php echo $page; ?>&section=<?php echo $section; ?>&lang=<?php echo $lang; ?>">
  <param name="quality" value="high">
  <embed src="../flash/menu_up_<?php echo $lang; ?>.swf?page=<?php echo $page; ?>&section=<?php echo $section; ?>&lang=<?php echo $lang; ?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="645" height="101"></embed>
</object>
</body>
</html>
