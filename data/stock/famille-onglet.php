<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<?php
if ($_GET['act'] == '') { $_GET['act'] = $_POST['act']; }

$idstockf = $_GET['idstockf'];
if ($_GET['s'] == '') {$_GET['s'] = 1;}
if ($_GET['s'] == 1) {$page = 'famille-modele-modif';}
if ($_GET['s'] == 2) {$page = 'famille-unite-modif';}
if ($_GET['s'] == 3) {$page = 'unite-listing';}
if ($_GET['s'] == 4) {$page = 'unite-ticket';}
#if ($_GET['s'] == 5) {$page = 'famille-onglet-down';}
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf8">
		<title>Exception - STOCK</title>
	</head>
		<frameset border="0" frameborder="no" framespacing="0" rows="35,*">
			<frame name="up" src="<?php echo 'famille-onglet-up.php?idstockf='.$_GET['idstockf'].'&s='.$_GET['s'].'&action='.$_GET['act'].'';?>" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="-1" scrolling="NO" >
			<frame name="down" src="<?php echo $page.'.php?idstockf='.$_GET['idstockf'].'&s='.$_GET['s'].'&action='.$_GET['act'].'';?>" noresize framespacing="0" frameheight="0" frameborder="0" marginheight="0" marginwidth="0">
			<noframes>
				<body bgcolor="#ffffff">
					<p></p>
				</body>
			</noframes>
		</frameset>
		
<body>
</body>

</html>