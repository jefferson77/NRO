<?php
define("NIVO", "../");

if ($_GET['debut'] == 'oui')
{
	// Initialisation de la session.
	// Si vous utilisez un autre nom
	// session_name("something")
	session_start();
	// Détruit toutes les variables de session

	$_SESSION = array();
	// Finalement, détruit la session
	session_destroy();

		session_start();
	if ($_SESSION['idwebclient'] == '') {
		#echo '$SESSION['idwebclient'] = '.$_SESSION['idwebclient'];
		#echo '$_GET['idwebclient'] = '.$_GET['idwebclient'];
		$_SESSION['idwebclient'] = $_GET['idwebclient'];
		$idwebclient = $_SESSION['idwebclient'];
		#echo $idwebclient;

		require_once(NIVO."nro/fm.php");
		$login = new db('webclient', 'idwebclient', 'webneuro');
		$login->inline("SELECT * FROM `webclient` WHERE `idwebclient` = '$idwebclient'");

		$loginfo = mysql_fetch_array($login->result) ;
		$_SESSION['idwebclient'] = $loginfo['idwebclient'];
		$_SESSION['nom']         = $loginfo['cnom'];
		$_SESSION['prenom']      = $loginfo['cprenom'];
		$_SESSION['societe']     = $loginfo['societe'];
		$_SESSION['qualite']     = $loginfo['qualite'];
		$_SESSION['lang']        = $loginfo['langue'];
		$_SESSION['webtype']     = 1;
		$_SESSION['web']         = 'web';
		$_SESSION['new']         = '1';
	}
}
else
{
	$_POST['email']   = $_GET['email'];
	$_POST['cnom']    = $_GET['cnom'];
	$_POST['cprenom'] = $_GET['cprenom'];
	$_POST['societe'] = $_GET['societe'];
	$_POST['qualite'] = $_GET['qualite'];
	$_POST['langue']  = $_GET['lang'];
	require_once(NIVO."nro/fm.php");
			# ---------- log ok : ouverture session ----------
			session_start();

				$_POST['etat'] = 0;

				$ajout = new db('webclient', 'idwebclient', 'webneuro');
				$ajout->AJOUTE(array('email', 'cnom', 'cprenom', 'etat', 'societe', 'qualite', 'langue'));
				$idwebclient = $ajout->addid;

			$_GET['act'] = 'start';
			$_POST['idwebclient'] = $idwebclient;
			echo 'yes';
			echo "\n";
			echo $idwebclient;
			echo "\n";
}
?>