<?php
$page=$_GET['page'];
$section=$_GET['section'];
$lang=$_GET['lang'];

if ($lang == '') {$lang ='fr'; }
if ($section == '') {$section = 'accueil';}
if ($page == '') {$page = 'presentation';}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
</head>

<frameset cols="155,645" frameborder="NO" border="0" framespacing="0" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
	<frameset rows="137,281,182" frameborder="NO" border="0" framespacing="0">
		<frame src="gauche-haut.php?<?php echo 'page='.$page.'&section='.$section.'&lang='.$lang; ?>" name="leftup" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
		<frame src="gauche-milieu.php?<?php echo 'page='.$page.'&swf='.$section.'&lang='.$lang; ?>" name="leftmid" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
	 	<frame src="gauche-bas.php?<?php echo 'page='.$page.'&section='.$section.'&lang='.$lang; ?>" name="leftdown" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
	</frameset>
	<frameset rows="101,464,35" frameborder="NO" border="0" framespacing="0" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
		<frame src="haut.php?<?php echo 'page='.$page.'&section='.$section.'&lang='.$lang; ?>" name="up" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
<?php
		switch ($_GET['page']){
		### page news
			case "news" : ?>
		    <frame src="http://194.78.216.195/web/webnews/adminnews.php?<?php echo 'page='.$page.'&section='.$section.'&lang='.$lang; ?>" name="main" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
		<?php	break;
		### page default
			default: ?>
		    <frame src="../page/page.php?<?php echo 'page='.$page.'&section='.$section.'&lang='.$lang; ?>" name="main" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
		<?php	break;
		}
		?>
		<frame src="bas-bas.php?<?php echo 'page='.$page.'&section='.$section.'&lang='.$lang; ?>" name="bas-bas" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
	</frameset>

</frameset>
<noframes><body>


</body></noframes>
</html>
