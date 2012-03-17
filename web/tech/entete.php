<?php
## Start Chrono  ##
$time_string = explode(" ", microtime());
$stime = $time_string[1] . substr($time_string[0],1,strlen($time_string[0]));
## fin Start Chrono ##
session_start();

# SESSION CHECK : Vérifie si la session est encore bien déclarée, sinon affiche la page de relogage.
if ($_SESSION['idagent'] == '') {
	include NIVO.'index.php';
	exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="PRAGMAS" CONTENT="NO-CACHE">
	<title><?php echo $Titre ;?></title>
<?php if ($reload == 'OK') {?>	
	<META HTTP-EQUIV="Refresh" CONTENT="0" URL="http://<?php echo $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']; ?>"> 
<?php } ?>
	<link rel="StyleSheet" type="text/css" href="<?php echo $niveauweb ;?>zones.css">
	<link rel="StyleSheet" type="text/css" href="<?php echo $niveauweb ;?>standard.css">
	<script language="JavaScript" type="text/JavaScript">
	<!--
function OpenBrWindow(theURL,winName,features, myWidth, myHeight, isCenter) { 
  if(window.screen)if(isCenter)if(isCenter=="true"){
    var myLeft = (screen.width-myWidth)/2;
    var myTop = (screen.height-myHeight)/2;
    features+=(features!='')?',':'';
    features+=',left='+myLeft+',top='+myTop;
  }
  window.open(theURL,winName,features+((features!='')?',':'')+'width='+myWidth+',resizable=no,  menubar=no, height='+myHeight);
}
	//-->
	</script>
</head>
<body>
<?php if ($Style == 'admin') { 
	$tete .= '<td valign="middle"><a href="'.NIVO.'admin/admenu.php">';
} else { 
	$tete .= '<td><a href="'.NIVO.'indexpeople.php">';
} 
$tete .= '<img src="'.STATIK.'illus/logomini.gif" alt="logomini.gif" width="50" height="50" border="0" align="middle"></a>';
$tete .= ' '.$Titre.'</td>' ;
?>
