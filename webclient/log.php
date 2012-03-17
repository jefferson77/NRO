<?php
define("NIVO", "../");

if ($_GET['debut'] == 'oui')
{
### ATTENTION !!!!!!!! idclient = idcofficer !!!!!!!!
	$idofficer = $_GET['idclient'];
### ATTENTION !!!!!!!! idclient = idcofficer !!!!!!!!
	require_once(NIVO."nro/fm.php");

	$loginfo = $DB->getRow("SELECT
			co.idcofficer, co.qualite AS coqualite, co.onom, co.oprenom, co.email, co.langue,
			c.societe AS clsociete, c.idclient, c.password
			FROM cofficer co
			LEFT JOIN client c ON co.idclient = c.idclient
			WHERE
			co.idcofficer = '$idofficer'");


			session_start();
			$_SESSION['idclient']   = $loginfo['idclient'];
			$_SESSION['idcofficer'] = $loginfo['idcofficer'];
			$_SESSION['nom']        = $loginfo['onom'];
			$_SESSION['prenom']     = $loginfo['oprenom'];
			$_SESSION['lang']       = $loginfo['langue'];
			$_SESSION['societe']    = $loginfo['clsociete'];
			if ($loginfo['langue'] == 'NL') {
				$loginfo['coqualite'] = qualiteNL($loginfo['coqualite']);
			}
			$_SESSION['qualite'] = $loginfo['coqualite'];
			$_SESSION['webtype'] = 1;
			$_SESSION['web']     = 'web';
			$_SESSION['new']     = '0';

			$_POST['idclient']   = $loginfo['idclient'];
			$_POST['idcofficer'] = $loginfo['idcofficer'];
			$ajout = new db('clientlog', 'idlog', 'webneuro');
			$ajout->AJOUTE(array('idclient' , 'idcofficer', 'idagent' ));

}
else
{
	$idclient = $_GET['idclient'];
	$email    = $_GET['email'];
	$pass     = $_GET['pass'];
	require_once(NIVO."nro/fm.php");

	$sql = "
			SELECT
			co.idcofficer, co.qualite, co.onom, co.oprenom, co.email, co.langue,
			c.societe AS clsociete, c.idclient, c.password
			FROM cofficer co
			LEFT JOIN client c ON co.idclient = c.idclient
			WHERE
			co.email = '$email'
			AND c.password = '$pass'
			AND c.idclient = '$idclient'
			";
$login = new db();
$login->inline($sql);

	switch (mysql_num_rows($login->result))
	{
		case "1":

			$loginfo = mysql_fetch_array($login->result) ;

			# ---------- log ok : ouverture session ----------
			session_start();
			$_SESSION['idclient']           = $loginfo['idclient'];
			$_SESSION['idcofficer']         = $loginfo['idcofficer'];
			$_SESSION['nom']                = $loginfo['onom'];
			$_SESSION['prenom']             = $loginfo['oprenom'];
			$_SESSION['lang']               = $loginfo['langue'];
			$_SESSION['webtype']            = 1;
			$_GET['act']                    = 'start';
			$idclient                       = $loginfo['idclient'];
			$_POST['idclient']              = $loginfo['idclient'];
### ATTENTION !!!!!!!! idclient = idcofficer !!!!!!!!
			$_POST['idclient']              = $loginfo['idcofficer'];
### ATTENTION !!!!!!!! idclient = idcofficer !!!!!!!!
			echo 'yes';
			echo "\n";
			echo $loginfo['idcofficer'];
			echo "\n";
		break;

		case "0":
			$erreurlog = 'wrongpass' ;
		break;

		default;
			$erreurlog = 'dberror' ;
	}
}
?>