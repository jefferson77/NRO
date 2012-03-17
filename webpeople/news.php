<?php
# Entete de page
define('NIVO', '../');
# Classes utilisées
require_once(NIVO."nro/fm.php");

$lang = $_SESSION['lang'];
if ($lang == '') { $lang = 'fr';}
$titre = 'titre'.$lang;
$texte = 'texte'.$lang;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="people.css" rel="stylesheet" type="text/css">
</head>
<body>
<br><br>
<table width="260" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="30">&nbsp;</td>
		<td valign="top" align="left">
			<h5>NEWS</h5>
			<table width="260" border="0" align="center" cellpadding="2" cellspacing="0">
<?php
	$detail2 = new db('webnewspeople', 'idwebnewspeople', 'webneuro');
	$detail2->inline("SELECT * FROM `webnewspeople` WHERE `online` = 'oui' ORDER BY datepublic DESC LIMIT 7");
	while ($infos2 = mysql_fetch_array($detail2->result)) {
?>
				<tr>
					<td class="news" align="center" valign="top">
					<li><?php echo stripslashes($infos2[$titre]);?>
					<br><?php echo fdate($infos2['datepublic']);?>
					<br><?php echo nl2br(stripslashes($infos2[$texte]));?></td>
				</tr>
<?php } ?>
			</table>
		</td>
	</tr>
</table>
</body>
</html>