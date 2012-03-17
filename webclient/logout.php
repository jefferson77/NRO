<?php

// Initialisation de la session.
// Si vous utilisez un autre nom
// session_name("something")
session_start();
// Détruit toutes les variables de session


$l = $_SESSION['lang'];

$_SESSION = array();
// Finalement, détruit la session
session_destroy();
if (!empty($_GET['l'])) {$l = $_GET['l']; }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
	<title>Exception&sup2; - Corporate Section</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8">
</head>

<frameset frameborder="NO" border="0" framespacing="0" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
		<frame src="http://www.exception2.be/page/client.php?l=<?php echo $l; ?>" name="leftup" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0" scrolling="NO">
</frameset>
<noframes>
<body class="blanc">
</body></noframes>
</html>

?>