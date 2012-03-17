<?php
# Entete de page
define('NIVO', '../../'); 
# Classes utilisées
require_once(NIVO."nro/fm.php");

#init vars
if (!isset($_GET['lang'])) $_GET['lang'] = 'fr';
$titre = 'titre'.$_GET['lang'];
$texte = 'texte'.$_GET['lang'];
$txtDate = '';
$txtTitre = '';
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
<?php
$idwebnews = 7;
if (!empty($_GET['idwebnews'])) 
{
	$idwebnews = $_GET['idwebnews'];
} else {
	$detail0 = new db('webnews', 'idwebnews', 'webneuro');
	$detail0->inline("SELECT * FROM `webnews` WHERE `online` = 'oui' ORDER BY datepublic DESC LIMIT 1");
	$infos0 = mysql_fetch_array($detail0->result) ; 
	$idwebnews = $infos0['idwebnews'];

}
	$detail = new db('webnews', 'idwebnews', 'webneuro');
	$detail->inline("SELECT * FROM `webnews` WHERE `idwebnews` = $idwebnews");
	$infos = mysql_fetch_array($detail->result) ; 

###phrase book#

# FR
if ($_GET['lang'] == 'fr') {
	$txtDate = 'Date';
	$txtTitre = 'Titre';
	$txtAfficher = 'Afficher';
}
# NL
if ($_GET['lang'] == 'nl') {
	$txtDate = 'Datum';
	$txtTitre = 'Titel';
	$txtAfficher = 'Tonen';
}
#/##phrase book#

?>        
<table width="600" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="30">&nbsp;</td>
		<td valign="top" align="left">
			<table width="560" border="0" align="center" cellpadding="2" cellspacing="0">
				<tr>
					<th class="news"><?php echo $txtDate;?></th>
					<th class="news"><?php echo $txtTitre;?></th>
					<th class="news"></th>
				</tr>
<?php
	$detail2 = new db('webnews', 'idwebnews', 'webneuro');
	$detail2->inline("SELECT * FROM `webnews` WHERE `online` = 'oui' ORDER BY datepublic DESC LIMIT 7");
	while ($infos2 = mysql_fetch_array($detail2->result)) { 
?>        
				<tr>
					<td class="news" align="center" valign="top"><?php echo fdate($infos2['datepublic']);?></td>
					<td class="news" valign="top"><?php echo $infos2[$titre];?></td>
					<td class="news2" valign="top"><a href="<?php echo $_SERVER['PHP_SELF'];?>?idwebnews=<?php echo $infos2['idwebnews'];?>&lang=<?php echo $_GET['lang'];?>" target="_self"><?php echo $txtAfficher;?></a></td>
				</tr>
<?php } ?>
				<tr>
					<td align="center" valign="top" colspan="3"><br><hr size="1" width="50%"></td>
				</tr>
			</table>
			<br>
			<table width="560" border="0" cellpadding="0">
				<tr valign="top">
					<td width="82" align="left"><h5><?php echo fdate($infos['datepublic']);?></h5></td>
					<td align="left"><h5><?php echo $infos[$titre];?></h5></td>
				</tr>
				<tr>
					<td colspan="2"><h6><?php echo nl2br($infos[$texte]);?></h6></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>