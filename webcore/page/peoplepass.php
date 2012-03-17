<?php
$page=$_GET['page'];
$section=$_GET['section'];
$lang=$_GET['lang'];
$niveau = '../';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<?php
# Entete de page
$niveau = '';

$email = 0;
If ($_GET['lost'] == 'yes') 
{
$email = 1;
}
If (($_POST['log'] != '') and ($_POST['pass'] != '')) {
	$log = $_POST['log'];
	$pass = $_POST['pass'];

		$a0 = $infos['a0'];
		$a1 = $infos['a1'];
		$a2 = $infos['a2'];
		$a3 = $infos['a3'];
		$c0 = $infos['c0'];
		$stop = mysql_num_rows($detail->result);
		If ($infos['c0'] != '') {
			$email = 2;
		} else {
		$email = 1;
		$oops = '<font color="red">The email you entered ( '.$_POST['c0'].' ) is not in our records.</font><br>';
		}	
}
?>
<html>
<head>
	<title>Exception&sup2; - Corporate Section</title> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf8">
	<link href="../classe/page.css" rel="stylesheet" type="text/css">
</head>
<body class="blanc">
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
				Exception&sup2; - Corporate Section - Secured Area
				<br><br><br><br>
				<b>Under construction</b>
				<br><br><br><br>
		</td>
	</tr>
	<tr>
		<td width="48%">
		<!-- -=-=-=-=-=-=-=-=-=-=-=-=- Fin new  -=-=-=-=-=-=-=-=-=-=-=-=-=-=- -->
		<FIELDSET>
			<legend>Worker Secured Area</legend>
			<!-- -=-=-=-=-=-=-=-=-=-=-=-=- Debut log -=-=-=-=-=-=-=-=-=-=-=-=-=-=- --> 
			<table border="0" cellpadding="0" cellspacing="5" width="95%">
				<form action="updttrad2.php?v9=9" method="post" name="CelsysForm" onsubmit="return scanForm(this)">
					<tr>
						<td>
							Login :
						</td>
						<td>
							
						</td>
					</tr>
					<tr>
						<td>
							Password :
						</td>
						<td>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
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
			switch ($email) {
			#### input
				case "1":
				echo $oops;
				?>
				<form action="<?php echo $PHP_SELF; ?>" method="post" name="CelsysForm" onsubmit="return scanForm(this)">
							pPlease send me my password at :<br>
							<input type="text" name="c0" size="25" value=""><br>
							(The email address you entered in the database)<br>
							<input type="submit" name="-Nothing" value="Send me my password">
				</form>
				<?php
				break;
			#### send
				case "2":
					require(NIVO."nro/xlib/phpmailer/class.phpmailer.php");

								$body = "
Dear ".$a3." ".$a2."


Your password : ".$a1."


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
                    			$mail->Host			= Conf::read('Mail.smtpHost'); // SMTP server
								$mail->SMTPAuth = false;
								$mail->From = "nico@exception2.be";
								$mail->FromName = "Exception2";
								$mail->AddAddress($c0, $c0);
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
									echo '
										An email with your password has been sent to '.$c0.'<br>
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
					<br><br>
				<?php
				break;
			}
			?>	
				</td>
			</tr>
		</table>
		</td>
	</tr>
<!-- -=-=-=-=-=-=-=-=-=-=-=-=- Fin forget pass  -=-=-=-=-=-=-=-=-=-=-=-=-=-=- -->
	<tr class="corps2" >
		<td align="center">
			<br><br><br>
		</td>
	</tr>
</table>
<br><br><br><br><br><br><br><br><br><br>
		</td>
	</tr>
</table>
		</div>
	</body>
</html>

