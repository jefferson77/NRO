<?php
## Start Chrono  ##
$time_string = explode(" ", microtime());
$stime = $time_string[1] . substr($time_string[0],1,strlen($time_string[0]));
## fin Start Chrono ##
session_start();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="PRAGMAS" CONTENT="NO-CACHE">
	<title>Exception&sup2; - Corporate Section - <?php echo $Titre ;?></title>
	<link rel="StyleSheet" type="text/css" href="<?php echo NIVO ?>webclient/client.css">
	
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