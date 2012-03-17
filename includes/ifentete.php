<?php
session_start();

require_once(NIVO."nro/fm.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf8">
	<title>Neuro</title>
	<link rel="stylesheet" href="<?php echo STATIK ?>nro/css/main.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<link rel="StyleSheet" type="text/css" href="<?php echo STATIK ?>css/zones.css">
	<link rel="StyleSheet" type="text/css" href="<?php echo STATIK ?>css/standard.css">
	<link rel="stylesheet" type="text/css" href="<?php echo STATIK ?>css/jquery-ui/exception.css">
	<script src="<?php echo STATIK ?>nro/xlib/jquery/jquery.js" type="text/javascript"></script>
	<script src="<?php echo STATIK ?>nro/xlib/jquery/jquery-ui.js" type="text/javascript"></script>
	<script src='<?php echo STATIK ?>nro/xlib/jquery/jquery.autocomplete.js'type='text/javascript'></script>
	<script src='<?php echo STATIK ?>nro/xlib/jquery/jquery.jgrowl.js'type='text/javascript'></script>
	<script src="<?php echo STATIK ?>nro/xlib/tablesort/packed.js" type="text/javascript"></script>
	<script language="JavaScript" type="text/JavaScript">
<!--
	$(document).ready(function() {
		// Orange si modif d'un champ input dans infozone
		$('#infozone input').change( function() {
			$('#orangepeople').css("background-color","#FFFF99");
		});

		$("#varsButton").click(function() { $("#vars").toggle(); });
		$("#resButton").click(function() { $("#res").toggle(); });
	});
//-->
	</script>
</head>
<body>
	<?php if (@$_SESSION['roger'] == 'devel') { ?>
		<div id="topvars">
			<a id="resButton" href="#" >Res</a>
			<a id="sqlButton" href="#" >SQL</a>
			<a id="varsButton" href="#" >Vars</a>
		</div>
		<div id="vars" class="devpanel">
			<table border="0" cellspacing="1" cellpadding="0">
			<?php
			# GET vars
			echo'<tr><td align="center" bgcolor="#123456"><font size="1" color="#EEEEEE" face="arial">GET vars</font></td></tr>';
			foreach ($_GET as $key => $value)
			{echo'<tr><td><font size="1" color="#123456" face="arial">$_GET[\'<font size="1" color="#000000" face="arial">'.stripslashes($key).'</font>\'] == \'<font size="1" color="#660000" face="arial">'.stripslashes($value).'</font>\'</font></td></tr>';}
			?>
			</table>
			<table border="0" cellspacing="1" cellpadding="0">
			<?php
			# POST vars
			echo'<tr><td align="center" bgcolor="#123456"><font size="1" color="#EEEEEE" face="arial">POST vars</font></td></tr>';
			foreach ($_POST as $key => $value) {
			echo'<tr><td><font size="1" color="#123456" face="arial">$_POST[\'<font size="1" color="#000000" face="arial">'.stripslashes($key).'</font>\'] == \'<font size="1" color="#660000" face="arial">';

			if (is_array($value)) {
				print_r($value);
			} else { echo stripslashes($value); }

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
			{echo'<tr><td><font size="1" color="#123456" face="arial">$_SESSION[\'<font size="1" color="#000000" face="arial">'.stripslashes($key).'</font>\'] == \'<font size="1" color="#660000" face="arial">'.stripslashes($value).'</font>\'</font></td></tr>';}
			}
			?>
			</table>
		</div>
	<?php } ?>