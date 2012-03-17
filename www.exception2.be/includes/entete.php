<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $_GET['l'] ?>" lang="<?php echo $_GET['l'] ?>">
<head>
<title>www.exception2.be</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="css/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	img {behavior:url('css/iepngfix.htc');}
</style>

</head>
<body>
<?php
if ($menu == 'full') {
	$flashfile = 'header';
	$fileheight = '501';
} else {
	$flashfile = 'header-light';
	$fileheight = '250';
}

?>
<div id="main">
	<div id="header">
		<div id="flags">
			<a href="?l=fr"><img src="illus/flags/fr.png" width="16" height="11" alt="Fr"></a>
			<a href="#" onclick="javascript:alert('Coming Soon !')"><img src="illus/flags/nl.png" width="16" height="11" alt="Nl"></a>
			<a href="#" onclick="javascript:alert('Coming Soon !')"><img src="illus/flags/gb.png" width="16" height="11" alt="En"></a>
		</div>
		<div class="flash">
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
           codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,24"
           width="980" height="<?php echo $fileheight; ?>">
			<param name="movie" value="flash/<?php echo $flashfile; ?>.swf?langue=<?php echo $_GET['l'] ?>" />
			<param name="quality" value="high" />
			<param name="menu" value="false" />
			<param name="wmode" value="transparent" />
			<!--[if !IE]> <-->
			<object data="flash/<?php echo $flashfile; ?>.swf?langue=<?php echo $_GET['l'] ?>"
					width="980" height="<?php echo $fileheight; ?>" type="application/x-shockwave-flash">
			 <param name="quality" value="high" />
			 <param name="menu" value="false" />
			 <param name="wmode" value="transparent" />
			 <param name="pluginurl" value="http://www.macromedia.com/go/getflashplayer" />
			 FAIL (the browser should render some flash content, not this).
			</object>
			<!--> <![endif]-->
		   </object>
		</div>
	</div>