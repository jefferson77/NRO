<?php
define("NIVO", "../../");

## PhraseBook
$_REQUEST['l'] = isset($_REQUEST['l'])?$_REQUEST['l']:'fr';
switch ($_REQUEST['l']) {
	case 'nl':
		include NIVO.'www/lang/nl.php';
		break;

	default:
		include NIVO.'www/lang/fr.php';
		break;
}

include NIVO.'nro/fm.php';
include NIVO.'www/includes/entete.php';

include 'models/people.php';

## Controlleur ##
$_REQUEST['p'] = isset($_REQUEST['p'])?$_REQUEST['p']:'';
switch ($_REQUEST['p']) {
	## Send Password ########################################################################################################
	case 'sendpass':
		include 'views/login.php';
	break;
	case 'mailpass':
		$peoples = $DB->getArray("SELECT pnom, pprenom, email, webpass, idpeople FROM people WHERE email LIKE '".$_POST['email']."'");

		if (count($peoples) == 1) {
			$message = SendPassMail(array_shift($peoples));
		} elseif (count($peoples) == 0) {
			$message['red'] = $lg['badMail'];
			$_REQUEST['p'] = 'sendpass';
		} else {
			$message['red'] = "Erreur DB (more than one)";
		}
		include 'views/login.php';
	break;

	## Inscription ##########################################################################################################
	case 'register':
		include 'views/login.php';
	break;
	case 'newpeople':
		$peoples = $DB->getArray("SELECT idpeople FROM people WHERE email LIKE '".$_POST['email']."'");

		if (count($peoples) == 1) {
			$message['red'] = $lg['dejaDansDB'];
		} elseif (count($peoples) == 0) {
			if (!empty($_POST['pnom']) and !empty($_POST['pprenom']) and !empty($_POST['email'])) {

				$_POST['lbureau'] = (!empty($_REQUEST['l']))?$_REQUEST['l']:'fr';
				$_POST['webetat'] = 0;
				$_POST['webtype'] = 0;

				$idwebpeople = $DB->ADD('webneuro.webpeople');
				$DB->inline("UPDATE webneuro.webpeople SET idpeople = 'n".$idwebpeople."' WHERE idwebpeople = ".$idwebpeople);

				Header("Location:".Conf::read('Env.urlroot')."webpeople/newpeople.php?debut=oui&idwebpeople=".$idwebpeople);
			} else {
				$message['red'] = $lg['infosManquantes'];
			}
		} else {
			$message['red'] = "Erreur DB (more than one)";
		}
		include 'views/login.php';
	break;

	## Login ################################################################################################################
	case 'log':

		switch (CheckLogin(mysql_escape_string($_POST['email']), mysql_escape_string($_POST['pass']))) {
			## Login Successful
			case 'logok':
			Header("Location:".Conf::read('Env.urlroot')."webpeople/adminpeople.php?debut=oui&idpeople=".$_SESSION['idpeople']);
				break;

			## Error Login
			case 'wrongpass':
				$message['red'] = $lg['mauvaisPassword'];
				include 'views/login.php';
				break;
			case 'duplic':
				$message['red'] = $lg['erreurDB'];
				include 'views/login.php';
				break;
			case 'empty':
				$message['red'] = $lg['loginVide'];
				include 'views/login.php';
				break;
			default:
				include 'views/login.php';
				break;
		}
	break;

	case 'logout':
		$_SESSION = array();
	case 'login':
	default:
		include 'views/login.php';
	break;
}

include NIVO.'www/includes/pied.php';
?>