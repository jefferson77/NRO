<?php
define("NIVO", "../../");

# Classes utilisées
require_once(NIVO."nro/fm.php");

$quid = (!empty($_REQUEST['n']))?"WHERE newspage LIKE '%".$_REQUEST['n']."%'":"";
$limit = (!empty($_REQUEST['c']))?"LIMIT ".$_REQUEST['c']:"";

$news = $DB->getArray("SELECT * FROM news ".$quid." ORDER BY id DESC ".$limit);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>News</title>
	<link rel="StyleSheet" type="text/css" href="<?php echo STATIK ?>css/standard.css">
</head>
<body>
	<table class="standard" border="0" width="99%" cellspacing="1" cellpadding="1">
		<tr>
			<th class="standard" colspan="2">News</th>
		</tr>
<?php foreach ($news as $row) { ?>
		<tr>
			<td class="standard" valign="top"><?php echo fdate($row['ndate']); ?></td>
			<td class="standard" align="left"><?php echo stripslashes($row['description']); ?></td>
		</tr>
<?php } ?>
	</table>
</body>
</html>
