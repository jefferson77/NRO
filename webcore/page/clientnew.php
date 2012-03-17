<?php
$page=$_GET['page'];
$_GET['section'] = 'webclient';
$section=$_GET['section'];
if ($_GET['lang'] == '') {$_GET['lang'] = $_GET['l'];}
$lang=$_GET['lang'];
if (($lang != 'fr') and ($lang != 'nl')) {$lang = 'fr';}
$niveau = '../';
include "var".$lang.".php";

# Entete de page
$niveau = '';

If (($_POST['societe'] != '') and ($_POST['cnom'] != '') and ($_POST['cprenom'] != '') and ($_POST['email'] != '')) {
	$societe = urlencode($_POST['societe']);
	$cnom = urlencode($_POST['cnom']);
	$cprenom = urlencode($_POST['cprenom']);
	$email = urlencode($_POST['email']);
	$pageresultats = file ('http://77.109.79.37/webclient/lognew.php?societe='.$societe.'&cnom='.$cnom.'&cprenom='.$cprenom.'&email='.$email.'&qualite='.$qualite.'&lang='.$lang);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Exception&sup2; - Corporate Section</title> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf8">
	<link href="../classe/page.css" rel="stylesheet" type="text/css">
</head>
<body class="blanc">

<?php
### Début de la page Neuro
If (trim ($pageresultats[0]) == 'yes') {
	$idwebclient = trim ($pageresultats[1]);
?>	
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.#version=5,0,30,0" height="20" width="20" align="top">
					<param name="movie" value="../flash/logclientnew1.swf?idwebclient=<?php echo $idwebclient; ?>">
					<param name="quality" value="best">

					<param name="play" value="true">
					<embed align="top" height="20" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" src="../flash/logclientnew1.swf?idwebclient=<?php echo $idwebclient; ?>" type="application/x-shockwave-flash" width="20" quality="best" play="true"> 
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
<table border="0" cellpadding="0" cellspacing="5" width="670" align="center" bgcolor="#FFFFFF" background="../images/page2.jpg" propriety="fixed">
	<tr>
		<td align="center"><br><br><br>
<table border="0" cellpadding="0" cellspacing="5" width="80%" align="center">
	<tr>
		<td align="center">
			<img src="../images/logo.jpg" alt="" border="0">
			<br><br>
				<?php echo $texte1; ?>
				<br>
				<?php echo $texte19; ?>
				<br><br>
		</td>
	</tr>
		<!-- -=-=-=-=-=-=-=-=-=-=-=-=- Fin new  -=-=-=-=-=-=-=-=-=-=-=-=-=-=- -->
			<!-- -=-=-=-=-=-=-=-=-=-=-=-=- Debut log -=-=-=-=-=-=-=-=-=-=-=-=-=-=- --> 
	<tr>
		<td>
		<FIELDSET>
			<legend><?php echo $texte3; ?></legend>
			<!-- -=-=-=-=-=-=-=-=-=-=-=-=- Debut log -=-=-=-=-=-=-=-=-=-=-=-=-=-=- --> 
			<table border="0" cellpadding="0" cellspacing="5" width="95%">
				<form action="<?php echo $PHP_SELF; ?>?lang=<?php echo $lang; ?>&section=webclient" method="post" name="CelsysForm" onsubmit="return scanForm(this)">
					<tr>
						<td>
							<?php echo $texte30; ?>
						</td>
						<td align="left">
							<input type="text" name="societe" size="30" value="<?php echo $_POST['societe'];?>">
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $texte31; ?>
						</td>
						<td align="left">
							<select name="qualite">
							<?php 
							echo '
								<option value="Monsieur"
							';	
								if ((isset($_POST['qualite'])) OR ($_POST['qualite'] == 'Monsieur')) {echo 'selected';}
							echo '>'.$texte32.'</option>
								<option value="Madame"';	
								if ($_POST['qualite'] == 'Madame') {echo 'selected';}
							echo '>'.$texte33.'</option>
								<option value="Mlle"';
								if ($_POST['qualite'] == 'Mlle') {echo 'selected';}
							echo '>'.$texte34.'</option>';
							?>
							</select>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $texte35; ?>
						</td>
						<td align="left">
							<input type="text" name="cnom" size="30" value="<?php echo $_POST['cnom'];?>">
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $texte36; ?>
						</td>
						<td align="left">
							<input type="text" name="cprenom" size="30" value="<?php echo $_POST['cprenom'];?>">
						</td>
					</tr>
					<tr>
						<td>
							Email
						</td>
						<td align="left">
							<input type="text" name="email" size="30" value="<?php echo $_POST['email'];?>">
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" name="-Nothing" value="<?php echo $texte10; ?>">
						</td>
					</tr>
				</form>
			</table> 
		</FIELDSET>
		<br>
		</td>
	</tr>
<!-- -=-=-=-=-=-=-=-=-=-=-=-=- Fin Log -=-=-=-=-=-=-=-=-=-=-=-=-=-=- -->
	<tr class="corps2" >
		<td align="center">
			<font color="#FF9900"><?php echo $texte20; ?></font><br><br>
			<?php echo $texte13; ?>.
		</td>
	</tr>
</table>
<br><br><br><br><br><br><br><br><br><br>
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