<?php
define('NIVO', '');

require_once(NIVO."nro/fm.php");
include NIVO.'classes/log.php';

#># initvars
if (!isset($_POST['log']))       $_POST['log']       = '';
if (!isset($_POST['pass']))      $_POST['pass']      = '';
if (!isset($_POST['firstload'])) $_POST['firstload'] = '';
if (!isset($_GET['logout']))     $_GET['logout']     = '';
$logok = '';
#<# initvars

# ------------ check Serial Number ------------
if ($_SERVER["REMOTE_ADDR"] == $_SERVER["SERVER_ADDR"]) {
	$erreurlog = 'machineok';
} else {
	$erreurlog = LOGCheckMachine($_SERVER["HTTP_USER_AGENT"], $_SERVER["REMOTE_ADDR"]);
}

if ($erreurlog == 'machineok') {
# ------------ check log and pass ------------

	if ((!empty($_POST['log'])) and (!empty($_POST['pass'])))
	{
		$log = $_POST['log'];
		$pass = $_POST['pass'];

		$login = new db();
		$login->inline("SELECT idagent, nom, prenom, adlevel, secteur FROM agent WHERE login = '$log' AND pass = '$pass' AND isout = 'N'");

		switch (mysql_num_rows($login->result))
		{
			case "1":
				$logok = 'yes' ;
				$loginfo = mysql_fetch_array($login->result) ;

				# ---------- log ok : ouverture session ----------
				if (!isset($_POST['idm'])) $_POST['idm'] = '';
				session_start();
				$_SESSION['idagent']      = $loginfo['idagent'];
				$_SESSION['nom']          = $loginfo['nom'];
				$_SESSION['prenom']       = $loginfo['prenom'];
				$_SESSION['roger']        = $loginfo['adlevel'];
				$_SESSION['rogersecteur'] = $loginfo['secteur'];

				$logage = new db();
				$sql = "INSERT INTO `logins` (`idagent` , `idmachine` , `logdate` , `logip` ) VALUES ( '".$loginfo['idagent']."', '".$_POST['idm']."', NOW(), '".$_SERVER["REMOTE_ADDR"]."');";
				$logage->inline($sql);

			break;

			case "0":
				$erreurlog = 'wrongpass' ;
			break;

			default;
				$erreurlog = 'dberror' ;
		}
	}
	else
	{
		if ($_POST['firstload'] == 'no') {
			$erreurlog = 'nologorpass' ;
		} else {
			$erreurlog = 'firstload' ;
		}
	}

}

# ------------ Display -----------------------

if ($logok == 'yes')
{
	Header("Location:".Conf::read('Env.urlroot')."menu.php");
}

else
{
	if ($_GET['logout'] == 'out') $erreurlog = 'delog' ;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=UTF-8">
	<title>Neuro</title>
	<style type="text/css" title="text/css">
		body
		{
			background-color: #FFFFFF;
			font-family: "Trebuchet MS", Verdana, Arial, sans-serif;
			font-size: 10px;
			color: #000000;
		}

		table
		{
			background-color: #999;
			width: 300px;

		}
		input[type="text"]
		{
			border-color: #000;
			border-width: 1px;
			border-style: solid;
			background-color: #EEE;
			text-align: center;
		}

		input[type="password"]
		{
			border-color: #000;
			border-width: 1px;
			border-style: solid;
			background-color: #EEE;
			text-align: center;
		}

		input.home
		{
			border-color: #000;
			border-width: 1px;
			border-style: solid;
			background-color: #EEE;
			padding: 0px 20px 0px 20px;
			background-image: url("illus/log/degrad.gif");
		}

		td.logtitre
		{
			background-color: #FFF;
			font-size: 14px;
			font-weight: bold;
			background-image: url("illus/log/degrad.gif");
			height: 20px;
			padding: 2px;
		}
		td.logbody
		{
			background-color: #FFF;
			background-image: url("illus/log/cadenas.jpg");
			padding: 5px 5px 5px 80px;
			background-repeat: no-repeat;
			vertical-align: middle;
			text-transform: inherit;
			height: 145px;
			text-align: center;
		}
		td.logfoot
		{
			background-image: url("illus/log/degrad.gif");
			background-color: #FFF;
			text-align: center;
			padding: 3px;
		}

		.devbar {
			background-color:#CC0000;
			text-align:center;
			color:#FFFFFF;
			font-weight:bold;
			font-size: 20px;
		}

</style>
</head>
<body>
<br>
<br>
<div style="text-decoration: underline;
font-size: 30px;color: #000;text-align: center;">Exception2 scrl</div>
<br><br>
<table border="0" cellspacing="1" cellpadding="0" align="center">
	<tr>
		<td class="logtitre">Login</td>
	</tr>
	<tr>
		<td class="logbody"><?php
	switch ($erreurlog) {

		case "badip":
		case "badmachine":
		?>
		Votre Machine n'est pas autoris&eacute;e sur ce serveur.<br>
		Votre tentative d'intrusion a &#233;t&#233; enregistr&#233;e
		<br><br>
		IP : <b><?php echo $_SERVER['REMOTE_ADDR'];?></b><br>
		AGENT : <b><?php echo $_SERVER['HTTP_USER_AGENT'];?></b><br>
		<?php
		break;
		case "dberror":
		case "tomanymachines":
			echo 'Une erreur s\'est produite dans le syt&egrave;me, veuillez contacter l\'administrateur';
		break;
		case "nologorpass":
		case "wrongpass":
		case "delog":
			echo '<font color="#990000">';
			switch ($erreurlog)
			{
				case "nologorpass":
					echo 'Vous devez fournir un Login et un Password pour vous connecter';
				break;

				case "wrongpass":
					echo 'Les infos entr&eacute;es sont incorrectes, veuillez r&eacute;entrer votre Login et votre Password';
				break;

				case "delog":
					echo 'Vous avez &eacute;t&eacute; d&eacute;logu&eacute; apr&egrave;s une p&eacute;riode d\'inactivit&eacute; trop longue';
				break;
			}
			echo '</font>';
		case "firstload":
		?>
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
		<br>
			Login : <br>
			<input type="text" name="log" size="10" maxlength="15"><br><br>
			Password : <br>
			<input type="password" name="pass" size="10" maxlength="15">
			<input type="hidden" name="firstload" value="no"><br><br><br>
			<input class="home" type="submit" name="Entrer" value="Entrer dans Neuro">
		</form>
		<?php
		break;
	}
?>
		</td>
	</tr>
	<tr>
		<td class="logfoot">Click here to <a href="mailto:nico@exception2.be">Contact Admin</a></td>
	</tr>
</table>
<div style="font-family: Arial;font-size: 10px;color: #AAA;text-align: center;"><br>Exception2 2007 &copy; - Security Module</div>
</body>
</html>
<?php
}
?>