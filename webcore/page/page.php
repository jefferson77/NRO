<?php
$page=$_GET['page'];
$section=$_GET['section'];
$lang=$_GET['lang'];

include "var".$lang.".php";
?>

<?php 
if (($_GET['page'] == 'news') or ($_GET['page'] == 'projet')) {
	if ($_GET['page'] == 'news') {
?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
		<html>
		<head>
		<title>Document sans titre</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf8">
		</head>
		<frameset cols="645" frameborder="NO" border="0" framespacing="0" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
			<frameset rows="464" frameborder="NO" border="0" framespacing="0" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
					<frame src="http://77.109.79.37/web/webnews/news.php?<?php echo 'page='.$page.'&section='.$section.'&lang='.$lang; ?>" name="main" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
			</frameset>
		</frameset>
		<noframes><body>
		
		</body></noframes>
		</html>
	<?php
	}
	if ($_GET['page'] == 'projet') {
	?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
		<html>
		<head>
		<title>Document sans titre</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf8">
		</head>
		<frameset cols="645" frameborder="NO" border="0" framespacing="0" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
			<frameset rows="464" frameborder="NO" border="0" framespacing="0" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
					<frame src="http://77.109.79.37/web/webprojet/projet.php?<?php echo 'page='.$page.'&section='.$section.'&lang='.$lang; ?>" name="main" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
			</frameset>
		</frameset>
		<noframes><body>
		
		
		</body></noframes>
		</html>
	<?php } ?>
<?php
} 
else 
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<link href="../classe/page.css" rel="stylesheet" type="text/css">
</head>

<body>
<br><br>
<table width="600" height="499" border="0" cellpadding="0">
  <tr>
    <td width="30">&nbsp;</td>
	<td valign="top">
    <div align="left">
<?php
		switch ($_GET['page']){
		### page plan
			case "plan1" :
			case "plan2" :
				echo 
				$texte1.'<br>'.
				'<h2>'.$stitre2.'</h2>'.
				$texte2.'<br>'
				;
			break;
		### page news
			case "news" :
			break;

		### page tenues
			case "tenues" :
				if ($_GET['mode'] == '') {$_GET['mode'] = 'a';}
				switch ($_GET['mode']){
				### page plan
					case "a" : $last = 4; break;
					case "b" : $last = 3; break;
					case "c" : $last = 2; break;
					case "d" : $last = 3; break;
					case "e" : $last = 5; break;
					case "f" : $last = 0; break;
					case "g" : $last = 3; break;
					case "h" : $last = 3; break;
				}
				$image = $_GET['image'];
				if ($image > $last) {$image = 1;}
				if ($image == 0) {$image = 1;}
				if ($image == '') {$image = 1;}
				if ($_GET['image'] == '') {$_GET['image'] = 1;}
				if ($_GET['image'] > $last) {$_GET['image'] = 1;}
				
				$image++;
				$imagemoins = $_GET['image'];
				$imagemoins--;
				if ($imagemoins == 0) {$imagemoins = $last;}
# CHEMISE ROUGE	EN STAND BY		<br><li> <a href="page.php?section=vip&page=tenues&mode=f&image=1&lang='.$lang.'">'.$texte7.'</a><br>
				echo '
					<table width="100%" height="100%" border="0" cellpadding="2">
						<tr>
							<td width="50%" valign="top" colspan="2">
								<h1>'.$titre.'</h1>
								<h2>'.$stitre1.'</h2>
								<br>
								'.$texte1.'<br>
								<br>
								<br><li> <a href="page.php?section=vip&page=tenues&mode=a&image=1&lang='.$lang.'">'.$texte2.'</a><br>
								<br><li> <a href="page.php?section=vip&page=tenues&mode=b&image=1&lang='.$lang.'">'.$texte3.'</a><br>
								<br><li> <a href="page.php?section=vip&page=tenues&mode=c&image=1&lang='.$lang.'">'.$texte4.'</a><br>
								<br><li> <a href="page.php?section=vip&page=tenues&mode=d&image=1&lang='.$lang.'">'.$texte5.'</a><br>
								<br><li> <a href="page.php?section=vip&page=tenues&mode=e&image=1&lang='.$lang.'">'.$texte6.'</a><br>
								<br><li> <a href="page.php?section=vip&page=tenues&mode=g&image=1&lang='.$lang.'">'.$texte8.'</a><br>
								<br><li> <a href="page.php?section=vip&page=tenues&mode=h&image=1&lang='.$lang.'">'.$texte9.'</a><br>
								<br>
							</td>
							<td valign="top">
								<img src="../images/tenue/'.$_GET['mode'].'-'.$_GET['image'].'.jpg" border="1">
								<br>
								<table width="100%" height="100%" border="0" cellpadding="2">
									<tr>
										<td width="25%" valign="top" align="center">
											<a href="page.php?section=vip&page=tenues&lang='.$lang.'&mode='.$_GET['mode'].'&image='.$imagemoins.'" target="_self">
												<img src="../images/tenue/previous.gif" border="0">
											</a>
										</td>
										<td width="25%" valign="top" align="center">
											<a href="page.php?section=vip&page=tenues&lang='.$lang.'&mode='.$_GET['mode'].'&image='.$image.'" target="_self">
												<img src="../images/tenue/next.gif" border="0">
											</a>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
							';
			break;
		### page materiel
			case "materiel" :
				echo '
					<table width="100%" height="100%" border="0" cellpadding="2">
						<tr>
							<td width="50%" valign="top" colspan="2">
								<h1>'.$titre.'</h1>
								<h2>'.$stitre1.'</h2>
								<br>
								'.$texte1.'<br>
								<br>
							</td>
							<td valign="top">
								<img src="../images/tenue/h-2.jpg" border="1">
								<br>
							</td>
						</tr>
					</table>
							';
			break;
		### page materiel
			case "contact" :
				include 'contact.php'; 
			break;

		### page default
			default:
				echo '<br><h1>'.$titre.'</h1>'.
				'<h2>'.$stitre1.'</h2>';
				echo 
				$texte1.'<br>'.
				'<h2>'.$stitre2.'</h2>'.
				$texte2.'<br>'
				;

				if (($_GET['section'] == 'accueil') and (($_GET['page'] == 'presentation') or ($_GET['page'] == ''))) {
					?>
						<div align="center">	
							<a href="../poule/exception-l.jpg" target="popupC" onclick="window.open('','popupC','scrollbars=yes,status=yes,resizable=yes,width=455,height=650');"><img src="../poule/exception2-m.jpg" border="1"></a>
							<!--
							&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
							<a href="../poule/exception-xmas-l.jpg" target="popupC" onclick="window.open('','popupC','scrollbars=yes,status=yes,resizable=yes,width=455,height=650');"><img src="../poule/exception2-xmas-m.jpg" border="1"></a>
							 -->
						 </div>
					<?php
				}
			break;
		}
?>
    </div></td>
  </tr>
</table>
</body>
</html>

<?php
}
?>
