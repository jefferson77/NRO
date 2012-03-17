<?php
function CheckLogin($log, $pass) {
	global $DB;

	if (!empty($log) and !empty($pass)) {
		$peoples = $DB->getArray("SELECT idpeople, email, webpass, pprenom, pnom, lbureau, webdoc, isout FROM people WHERE email = '".$log."' AND webpass = '".$pass."'");

		switch (count($peoples)) {
			## log OK
			case "1":
				$_SESSION = array();
				$loginfo = array_shift($peoples);

				$_SESSION['idpeople'] = $loginfo['idpeople'];
				$_SESSION['nom']      = $loginfo['pnom'];
				$_SESSION['prenom']   = $loginfo['pprenom'];
				$_SESSION['lang']     = $loginfo['lbureau'];
				$_SESSION['webtype']  = 1;
				$_SESSION['webdoc']   = $loginfo['webdoc'];
				$_SESSION['out']      = $loginfo['isout'];

				$erreurlog = 'logok' ;
			break;

			case "0":
			## Mauvais pass
				$erreurlog = 'wrongpass' ;
			break;

			default;
			## erreur DB : duplicata
				$erreurlog = 'duplic' ;
		}
	} else {
		$erreurlog = 'empty';
	}

	return $erreurlog ;
}

/**
 * Envoie du message mail avec le mod de passe pour le people
 *
 * @return void
 * @author Nico
 **/
function SendPassMail($pinfos)
{
	global $lg;

	include (NIVO.'nro/xlib/phpmailer/class.phpmailer.php');

	$mail				= new PHPMailer(true);
	$mail->CharSet 		= "UTF-8";
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host			= Conf::read('Mail.smtpHost'); // SMTP server

	$mail->SetFrom("people@exception2.be", "Exception2");

	$mail->Subject		= "[Exception2] Vos coordonnées de connection";

	/*
		TODO : message mail avec la mise en page des mails document + bilingue
	*/

	$body = "
Dear ".$pinfos['pprenom']." ".$pinfos['pnom']."

You can enter in your private section on the www.exception2.be site with those informations :

Your Login 		: ".$pinfos['email']."
Your password 	: ".$pinfos['webpass']."

Yours sincerely,

The Exception2 team.

---------
This e-mail and any attachments to it may contain confidential information,
which is strictly intended for the use of the authorized recipient.  If you
have received this e-mail in error, please delete it and notify the sender
by replying to this e-mail.
Thank you for your co-operation.";

	$mail->MsgHTML(nl2br($body));
	$mail->AltBody		= $body;

	$mail->WordWrap 	= 50;

	$mail->AddAddress($pinfos['email']);

	if(!$mail->Send()) {
		$message['red'] = $lg['mailPasEnvoye'].$pinfos['email'].$lg['le'].date("d/m/Y");
	} else {
		$message['green'] = $lg['mailEnvoye'].$pinfos['email'].$lg['le'].date("d/m/Y");
	}

	return $message;
}?>