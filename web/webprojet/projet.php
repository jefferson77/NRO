<?php
# Entete de page
define('NIVO', '../../'); 
# Classes utilisées
require_once(NIVO."nro/fm.php");

$section = $_GET['section'];
if ($section == 'vip') {$section = 1;}
if ($section == 'animation') {$section = 2;}
if ($section == 'merchandising') {$section = 3;}

$lang = $_GET['lang'];
$titre = 'titre'.$lang;
$texte = 'texte'.$lang;

###phrase book#
# FR
if ($lang == 'fr') {
	$txtDu = 'du';
	$txtAu = 'au';
}
# NL
if ($lang == 'nl') {
	$txtDu = 'van';
	$txtAu = 'tot';
}
#/##phrase book#
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<link href="../webtools/page.css" rel="stylesheet" type="text/css">
</head>

<body>
<br><br>
<table width="600" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="30">&nbsp;</td>
		<td valign="top" align="left">
			<table width="560" border="0" cellpadding="2" cellspacing="0">
<?php
	$detail2 = new db('webprojet', 'idwebprojet', 'webneuro');
	$detail2->inline("SELECT * FROM `webprojet` WHERE `online` = 'oui' AND `section` = $section ORDER BY date1 DESC LIMIT 5");
	while ($infos2 = mysql_fetch_array($detail2->result)) { 
?>        
				<tr>
					<th class='bleu' colspan='2'>
						<li><?php echo $infos2[$titre];?> :&nbsp; (<?php echo $txtDu;?> <?php echo fdate($infos2['date1']);?> <?php echo $txtAu;?> <?php echo fdate($infos2['date2']);?>)
					</th>
				</tr>
				<tr>
					<td width='25'><br></td>
					<td>
						<?php echo nl2br($infos2[$texte]);?>
					</td>
				</tr>
<?php } ?>
			</table>
		</td>
	</tr>
</table>
</body>
</html>