<?php
$page = $_GET['page'];
$section = $_GET['section'];
$lang = $_GET['lang'];

include "var".$lang.".php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
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
				if ($_GET['section'] == 'vip') {
					#include 'http://194.78.216.195/mod/webnews/webnews.php';
				} else {
					#include 'news.php';
				}
			break;

		### page tenues
			case "tenues" :
				echo '
					<table width="100%" height="100%" border="0" cellpadding="2">
						<tr>
							<td width="50%" valign="top">
								<h1>'.$titre.'</h1>
								<h2>'.$stitre1.'</h2>
								<br>
								'.$texte1.'<br>
							</td>
							<td valign="top">
								<img src="../images/tenue/madonna.jpg" border="0">
							</td>
						</tr>
					</table>
							';
			break;

		### page default
			default:
				echo '<br><h1>'.$titre.'</h1>'.'<h2>'.$stitre1.'</h2>';
				echo $texte1.'<br>'.'<h2>'.$stitre2.'</h2>'.$texte2.'<br>';
			break;
		}
?>
    </div></td>
  </tr>
</table>
</body>
</html>

