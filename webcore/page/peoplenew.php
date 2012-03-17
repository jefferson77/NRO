<?php
$page=$_GET['page'];
$_GET['section'] = 'webpeople';
$section=$_GET['section'];
$lang=$_GET['lang'];
$l=$_GET['l'];
if ($l != ''){$lang = $l;}
if ($lang == ''){$lang = 'fr';}
if ($lang == 'en'){$lang = 'fr';}

$niveau = '../';
include "var".$lang.".php";

# Entete de page
$niveau = '';

$email = 0;
If ($_GET['lost'] == 'yes') 
{
$email = 1;
}
If (($_POST['log'] != '') and ($_POST['pnom'] != '') and ($_POST['pprenom'] != '')) {
	$log = urlencode($_POST['log']);
	$email = urlencode($_POST['log']);
	$pnom = urlencode($_POST['pnom']);
	$pprenom = urlencode($_POST['pprenom']);
	
	$pageresultats = file ('http://77.109.79.37/webpeople/newlog.php?email='.$email.'&pnom='.$pnom.'&pprenom='.$pprenom.'&langu='.$lang.'&lang='.$lang);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
	<title>Exception&sup2; - Corporate Section</title> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf8">
	<link href="../classe/page.css" rel="stylesheet" type="text/css">
</head>
<body class="blanc">

<?php
### Début de la page Neuro
#echo '$truc = '.$logok.'z<br>';
#echo '$logok = '.trim ($pageresultats[0]).'z<br>';
If (trim ($pageresultats[0]) == 'yes') {
	$idwebpeople = trim ($pageresultats[1]);
?>	

			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.#version=5,0,30,0" height="20" width="20" align="top">
					<param name="movie" value="../flash/lognew.swf?idwebpeople=<?php echo $idwebpeople; ?>">
					<param name="quality" value="best">
					<param name="play" value="true">
					<embed align="top" height="20" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" src="../flash/lognew.swf?idwebpeople=<?php echo $idwebpeople; ?>" type="application/x-shockwave-flash" width="20" quality="best" play="true"> 
		  </object>

<?php
}
#/# Fin de la page Neuro
else
#/# Début DE LA PAGE publique
{
?>
	<div align="center">
<br>
<table border="0" cellpadding="0" cellspacing="5" width="670" align="center" bgcolor="#FFFFFF" background="../images/page2.jpg" propriety="fixed" >
	<tr>
		<td align="center"><br><br><br>
<table border="0" cellpadding="0" cellspacing="5" width="80%" align="center">
	<tr>
		<td align="center">
			<img src="../images/logo.jpg" alt="" border="0">
			<br><br>
				<?php echo $texte1; ?>
				<br>
				<?php echo $texte15; ?>
				<br><br>
		</td>
	</tr>
	<tr>
		<td width="48%">
		<!-- -=-=-=-=-=-=-=-=-=-=-=-=- Fin new  -=-=-=-=-=-=-=-=-=-=-=-=-=-=- -->
		<FIELDSET>
			<legend><?php echo $texte3; ?></legend>
			<!-- -=-=-=-=-=-=-=-=-=-=-=-=- Debut log -=-=-=-=-=-=-=-=-=-=-=-=-=-=- --> 
			<table border="0" cellpadding="0" cellspacing="5" width="95%">
				<form action="<?php echo $PHP_SELF; ?>?lang=<?php echo $lang; ?>" method="post">
					<tr>
						<td>
							<?php echo $texte16; ?> :
						</td>
						<td>
							<input type="text" name="pnom" size="20" value="">
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $texte17; ?> :
						</td>
						<td>
							<input type="text" name="pprenom" size="20" value="">
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $texte4; ?> :
						</td>
						<td>
							<input type="text" name="log" size="20" value="">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" name="-Nothing" value="<?php echo $texte6; ?>">
						</td>
					</tr>
				</form>
			</table> 
		</FIELDSET>
		<br>
		</td>
	</tr>
<!-- -=-=-=-=-=-=-=-=-=-=-=-=- Fin Log -=-=-=-=-=-=-=-=-=-=-=-=-=-=- -->
<!-- -=-=-=-=-=-=-=-=-=-=-=-=- Debut forget pass  -=-=-=-=-=-=-=-=-=-=-=-=-=-=- -->
	<tr class="corps2" >
		<td align="center">
			<?php If (trim ($pageresultats[0]) == 'email') { 
			echo '<font color="#FF9933">'.$email. ' existe d&eacute;j&agrave; dans notre syst&egrave;me.
			<br><a href="people.php?l='.$lang.'">Aller &agrave; la page de mise &agrave; jour</a></font>';
			} ?>
			<br>
			<a href="<?php echo 'people.php?l='.$lang; ?>"><?php echo $texte20; ?></a>
			<br><br>
			<?php echo $texte13; ?>
			<br><i>02/ 732 74 40</i>
			<br><br><br>
		</td>
	</tr>
</table>
<br><br><br>
		</td>
	</tr>
</table>
		</div>
<?php
#/## FINDE LA PAGE publique
}
?>
	</body>
</html>