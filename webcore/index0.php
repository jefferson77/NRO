<?php
$page=$_GET['page'];
$section=$_GET['section'];
$lang=$_GET['lang'];
if ($lang == '') {$lang = $_GET['l']; }
if ($lang == '') {$lang ='fr'; }
if ($section == '') {$section = 'accueil';}
if ($page == '') {$page = 'presentation';}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>Exception</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
</head>

<frameset rows="*" cols="*,800,*" frameborder="NO" border="0" framespacing="0">
  <frameset rows="*,*" frameborder="NO" border="0" framespacing="0">
		<frame src="structure/blanc.php" name="A" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
 		<frame src="structure/blanc.php" name="A" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
 </frameset>
  <frameset rows="*,600,*" frameborder="NO" border="0" framespacing="0">
		<frame src="structure/blanc.php" name="b" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
		<frame src="structure/index1.php?<?php echo 'page='.$page.'&section='.$section.'&lang='.$lang; ?>" name="site" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
		<frame src="structure/blanc.php" name="b" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
  </frameset>
  <frameset rows="*,*" frameborder="NO" border="0" framespacing="0">
		<frame src="structure/blanc.php" name="A" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
	 	<frame src="structure/blanc.php" name="c" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
 </frameset>
</frameset>
<noframes><body>


</body></noframes>
</html>
