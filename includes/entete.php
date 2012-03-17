<?php
session_start();

## Menu mode
if (empty($_SESSION['menu']) || $_SERVER["PHP_SELF"] == '/menu.php') $_SESSION['menu'] = 'menu.php';
if ($_SERVER["PHP_SELF"] == '/admin/admenu.php') $_SESSION['menu'] = 'admin/admenu.php';

#<# init vars
if (!isset($Style)) $Style = '';
if (!isset($_GET['act'])) $_GET['act'] = '';
#># init vars

require_once(NIVO."nro/fm.php");

# SESSION CHECK : Vrifie si la session est encore bien dclare, sinon affiche la page de relogage
if (empty($_SESSION['idagent'])) Header("Location:".Conf::read('Env.urlroot')."index.php?logout=out");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="pragmas" content="no-cache">
	<title><?php echo $Titre ;?></title>
	<link rel="stylesheet" href="<?php echo STATIK ?>nro/css/main.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<link rel="StyleSheet" type="text/css" href="<?php echo STATIK ?>css/zones.css">
	<link rel="StyleSheet" type="text/css" href="<?php echo STATIK ?>css/standard.css">
	<link rel="stylesheet" type="text/css" href="<?php echo STATIK ?>css/jquery-ui/exception.css">
	<script src="<?php echo STATIK ?>nro/xlib/jquery/jquery.js" type="text/javascript"></script>
	<script src="<?php echo STATIK ?>nro/xlib/jquery/jquery-ui.js" type="text/javascript"></script>
	<script src='<?php echo STATIK ?>nro/xlib/jquery/jquery.autocomplete.js'type='text/javascript'></script>
	<script src='<?php echo STATIK ?>nro/xlib/jquery/jquery.jgrowl.js'type='text/javascript'></script>
	<script src="<?php echo STATIK ?>nro/xlib/tablesort/packed.js" type="text/javascript"></script>
	<script type="text/javascript" charset="utf-8">
		function OpenBrWindow(theURL,winName,features, myWidth, myHeight, isCenter) {
		  if(window.screen)if(isCenter)if(isCenter=="true"){
		    var myLeft = (screen.width-myWidth)/2;
		    var myTop = (screen.height-myHeight)/2;
		    features+=(features!='')?',':'';
		    features+=',left='+myLeft+',top='+myTop;
		  }
		  window.open(theURL,winName,features+((features!='')?',':'')+'width='+myWidth+',resizable=no,  menubar=no, height='+myHeight);
		}

		$(document).ready(function() {
			// Orange si modif d'un champ input dans infozone
			$('#infozone input').change( function() {
				$('#infozone').css("background-color","#FF6600");
				$('#infobouton').css("background-color","#FF6600");
			});

			$("#varsButton").click(function() { $("#vars").toggle(); });
			$("#resButton").click(function() { $("#res").toggle(); });
		});
	</script>
</head>
<body>
<?php
if (@$_SESSION['roger'] == 'devel' && @$_GET['act'] != 'facturation') {
?>
<div id="vars" class="devpanel">
	<table border="0" cellspacing="1" cellpadding="0">
	<?php
	# GET vars
	echo'<tr><td align="center" bgcolor="#123456"><font size="1" color="#EEEEEE" face="arial">GET vars</font></td></tr>';
	foreach ($_GET as $key => $value)
	{echo'<tr><td><font size="1" color="#123456" face="arial">$_GET[\'<font size="1" color="#000000" face="arial">'.stripslashes($key).'</font>\'] == \'<font size="1" color="#660000" face="arial">'.htmlentities(stripslashes($value)).'</font>\'</font></td></tr>';}
	?>
	</table>
	<table border="0" cellspacing="1" cellpadding="0">
	<?php
	# POST vars
	echo'<tr><td align="center" bgcolor="#123456"><font size="1" color="#EEEEEE" face="arial">POST vars</font></td></tr>';
	foreach ($_POST as $key => $value) {
	echo'<tr><td><font size="1" color="#123456" face="arial">$_POST[\'<font size="1" color="#000000" face="arial">'.stripslashes($key).'</font>\'] == \'<font size="1" color="#660000" face="arial">';

	if (is_array($value)) {
		echo 'Array '.count($value);
	} else { echo htmlentities(stripslashes($value)); }

	echo '</font>\'</font></td></tr>';
	}
	?>
	</table>
	<table border="0" cellspacing="1" cellpadding="0">
	<?php
	if(session_id() != '')
	{
	# SESSION vars
	echo'<tr><td align="center" bgcolor="#123456"><font size="1" color="#EEEEEE" face="arial">SESSION vars</font></td></tr>';
	foreach ($_SESSION as $key => $value)
	{echo'<tr><td><font size="1" color="#123456" face="arial">$_SESSION[\'<font size="1" color="#000000" face="arial">'.stripslashes($key).'</font>\'] == \'<font size="1" color="#660000" face="arial">'.htmlentities(stripslashes($value)).'</font>\'</font></td></tr>';}
	}
	?>
	</table>
</div>
<?php } ?>
<div id="entete">
<a href="<?php echo NIVO.$_SESSION['menu']; ?>">
<img src="<?php echo STATIK ?>illus/logoexc.png" alt="logoexc.png" width="67" height="66" border="0" align="middle" style="margin: -3px 0px 0px -3px;"></a>
<?php
if ($Titre == 'MERCH-d') {
$Titre = '<font color="orange">MERCH-d</font>';
}
?>
<?php echo $Titre ;?>
</div>