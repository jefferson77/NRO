<?php
	define("NIVO", "../");

	$email    = $_GET['email'];
	$pass     = $_GET['pass'];
	$idclient = $_GET['idclient'];
	require_once(NIVO."nro/fm.php");

	$recherche = "
			SELECT
			co.idcofficer, co.qualite, co.onom, co.oprenom, co.email, co.langue,
			c.societe AS clsociete, c.idclient, c.password
			FROM cofficer co
			LEFT JOIN client c ON co.idclient = c.idclient
			WHERE
			co.email = '$email'
			AND c.idclient = '$idclient'
			LIMIT 1
			";
	$login = new db();
	$login->inline("$recherche;");

	switch (mysql_num_rows($login->result))
	{
		case "1":

			$loginfo = mysql_fetch_array($login->result) ;

			# ---------- log ok : ouverture session ----------
			session_start();
			$_SESSION['idclient'] = $loginfo['idclient'];
			$_SESSION['nom']      = $loginfo['onom'];
			$_SESSION['prenom']   = $loginfo['oprenom'];
			$_SESSION['lang']     = $loginfo['langue'];
			$_SESSION['webtype']  = 1;
			$_GET['act']          = 'start';
			$idclient             = $loginfo['idclient'];
			$_POST['idclient']    = $loginfo['idclient'];
			echo 'yes';
			echo "\n";
			echo $loginfo['oprenom'];
			echo "\n";
			echo $loginfo['onom'];
			echo "\n";
			echo $loginfo['password'];
			echo "\n";
		break;

		case "0":
			$erreurlog = 'wrongpass' ;
		break;

		default;
			$erreurlog = 'dberror' ;
	}
?>