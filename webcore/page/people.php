<?php
$page=$_GET['page'];
$_GET['section'] = 'webpeople';
$lang=$_GET['l'];
if ($lang == ''){$lang = 'fr';}
if ($lang == 'en'){$lang = 'fr';}
$niveau = '../';
include "var".$lang.".php";

# Entete de page
$niveau = '';

$sendemail = 0;
if ($_GET['lost'] == 'yes') 
{
	$sendemail = 1;
}
if ($_GET['lost2'] == 'yes') 
{

	$mailresultats = file ('http://77.109.79.37/webpeople/mail.php?email='.$_POST['email']);
	if (trim ($mailresultats[0]) == 'yes') {
		$sendemail = 2;
		$pprenom = trim ($mailresultats[1]);
		$pnom = trim ($mailresultats[2]);
		$webpass = trim ($mailresultats[3]);

	} else {
		$sendemail = 3;
	}
}

if (($_POST['log'] != '') and ($_POST['pass'] != '')) {
	$log = $_POST['log'];
	$email = $_POST['log'];
	$pass = $_POST['pass'];
	$pageresultats = file ('http://77.109.79.37/webpeople/log.php?email='.$email.'&pass='.$pass);
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
if (trim ($pageresultats[0]) == 'yes') {
	$idpeople = trim ($pageresultats[1]);
?>	
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.#version=5,0,30,0" height="20" width="20" align="top">
					<param name="movie" value="../flash/log.swf?idpeople=<?php echo $idpeople; ?>">
					<param name="quality" value="best">
					<param name="play" value="true">
					<embed align="top" height="20" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" src="../flash/log.swf?idpeople=<?php echo $idpeople; ?>" type="application/x-shockwave-flash" width="20" quality="best" play="true"> 
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
				<?php echo $texte2; ?>
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
				<form action="<?php echo $PHP_SELF; ?>?l=<?php echo $lang; ?>" method="post" name="CelsysForm" onsubmit="return scanForm(this)">
					<tr>
						<td>
							<?php echo $texte4; ?> :
						</td>
						<td>
							<input type="text" name="log" size="20" value="">
						</td>
					</tr>
					<tr>
						<td>
							<?php echo $texte5; ?> :
						</td>
						<td>
							<input type="password" name="pass" size="20" value="">
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
	<tr>
		<td colspan="3">
		<table border="0" cellpadding="0" cellspacing="5" width="100%">
			<tr>
				<td align="center">
			<?php	
			switch ($sendemail) {
			#### input
				case "1":
				case "3":
				echo $oops;
				?>
				<form action="<?php echo $PHP_SELF.'?l='.$lang; ?>&lost2=yes" method="post" name="CelsysForm" onsubmit="return scanForm(this)">
							<?php if ($sendemail == 3) {
								echo $_POST['email'].' : '.$texte7.'<br>';
							} ?>
							<?php echo $texte8; ?> :<br>
							<input type="text" name="email" size="25" value=""><br>
							(<?php echo $texte9; ?>)<br>
							<input type="submit" name="-Nothing" value="<?php echo $texte10; ?>">
				</form>
				<?php
				break;
			#### send
				case "2":
				# DEBUT du resultat apres envoi de Formulaire dÕenvoi d'infos / mail.
								require(NIVO."nro/xlib/phpmailer/class.phpmailer.php");
							# ci dessous le corps du mail
								$body = "
Dear ".$pprenom." ".$pnom."


Your password : ".$webpass."


Yours sincerely,

The Exception2 team.



---------
This e-mail and any attachments to it may contain confidential information,
which is strictly intended for the use of the authorized recipient.  If you
have received this e-mail in error, please delete it and notify the sender
by replying to this e-mail.
Thank you for your co-operation.
							";
							# fin du corps du message
								# mail send
								$mail = new phpmailer();
								$mail->IsSMTP();
								$mail->Host = "192.168.1.12";
								$mail->SMTPAuth = false;
								$mail->From = "people@exception2.be";
								$mail->FromName = "people@exception2.be";
								$mail->AddAddress($email, $email);
								$mail->AddBCC('nico@exception2.be', 'nico@exception2.be');
								$mail->WordWrap = 80;
								$mail->IsHTML(false);
								$mail->Subject = "Your account information";
								$mail->Body    = $body;
								echo '
								<table border="0" cellpadding="0" cellspacing="5" width="100%" height="100%" align="center">
									<tr>
										<td align="center">
								';
								if(!$mail->Send())
								{
									echo "<font color=\"#990000\">- (\"Mailer Error: ".$mail->ErrorInfo.") - <br> please, contact the webmaster <a href=\"mailto:info@celsys.be\" target=\"_blank\">info@celsys.be</a> </font><br>";
								}
								else
								{
									echo 
										$texte11.' : '.$email.'<br>
									';
								}
								echo '
										</td>
									</tr>
								</table>
								';
						# FIN du resultat apres envoi de Formulaire d'envoi d'infos / mail.
				break;
			#### base
				case "0": 
				default: 
				?>
					<a href="<?php echo 'peoplenew.php?l='.$lang; ?>"><?php echo $texte19; ?></a><br><br>
				<?php
				break;
			}
			?>	
				<br>
				<?php echo $texte18; ?> <a href="<?php echo $PHP_SELF.'?l='.$lang; ?>&lost=yes"><?php echo $texte12; ?></a>
				</td>
			</tr>
		</table>
		</td>
	</tr>
<!-- -=-=-=-=-=-=-=-=-=-=-=-=- Fin forget pass  -=-=-=-=-=-=-=-=-=-=-=-=-=-=- -->
	<tr class="corps2" >
		<td align="center">
			<?php echo $texte13; ?>
			<br><i>02/ 732 74 40</i>
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