<?php
### (1/2) INCLUS L'ENTETE DEDIE en fonction de l'url de la page courante.
### Les ent�tes doivent �tre mis dans /NRO/includes/entetes/memecheminquelurldufichierappelant/*.inc
### Des param�tres peuvent �tre ajout�s � <body> si on d�finit $pagebody dans le *.inc.
### On peut aussi en option rajouter une variable $headerfile2 qui est le chemin relatif (� partir de
### /webpeople/includes/entetes) vers l'ent�te *.inc (ex : perso/photoupload.php)
session_start();

## Classes utilis�es
require_once(NIVO."nro/fm.php");

$pagebody   = '';
$urlsplit   = explode("webpeople/", $_SERVER['PHP_SELF']); ### on supprime ce qu'il y a avant webpeople/
$urlsplit   = explode(".", $urlsplit[1]); ### on supprime l'extension
$headerfile = $urlsplit[0];
$headerpath = $_SERVER['DOCUMENT_ROOT'].'/webpeople/includes/entetes/'.$headerfile.'.php';
###
###
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="PRAGMAS" CONTENT="NO-CACHE">
	<title>Exception&sup2; - Corporate Section - <?php echo @$Titre.' '.@$_SESSION['idpeople'] ;?></title>
	<link rel="StyleSheet" type="text/css" href="<?php echo $niveauweb; ?>people.css">
<?php
### (2/2) INCLUS L'ENTETE DEDIE en fonction de l'url de la page courante.
if (file_exists($headerpath)) {
	echo"<!--DEBUT HEADER1 -->\r\n";
	include($headerpath);
	echo"\r\n<!--FIN HEADER1 -->\r\n";
	}

if (isset($headerfile2) && !empty($headerfile2)) {
	$headerpath2 = $_SERVER['DOCUMENT_ROOT'].'/webpeople/includes/entetes/'.$headerfile2;
	if (file_exists($headerpath2)) {
		echo"<!--DEBUT HEADER2 -->\r\n";
		include($headerpath2);
		echo"\r\n<!--FIN HEADER2 -->\r\n";
		}

	}
###
?>
</head>
<body <?php echo $pagebody ?>>