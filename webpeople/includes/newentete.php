<?php
session_start();

# SESSION CHECK : Vérifie si la session est encore bien déclarée, sinon affiche la page de relogage.
if ($_SESSION['idwebpeople'] == '') {
        Header("Location:http://www.exception2.be/index0.php?l=fr");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="PRAGMAS" CONTENT="NO-CACHE">
	<title>Exception&sup2; - Corporate Section - <?php echo $Titre.' '.$_SESSION['idpeople'] ;?></title>
	<link rel="StyleSheet" type="text/css" href="<?php echo $niveauweb; ?>people.css">
</head>
<body>