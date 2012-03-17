
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>MiniMap</title>
	<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAncK7u6Y6vCOout4CjUjPfhSbH9EkmmQTmyUx1tkKHz09g2FIOhSnJBa42uTM9Ld3c_iZ_wRU9n7fiQ" type="text/javascript"></script>
	<script type="text/javascript">
	function initialize() {
		// map
	    var map = new GMap2(document.getElementById("map_canvas"));
	    map.setCenter(new GLatLng(<?php echo $_GET['lat'] ?>, <?php echo $_GET['long'] ?>), 14);
		// pointer
	    var point = new GLatLng(<?php echo $_GET['lat'] ?>, <?php echo $_GET['long'] ?>);
	    map.addOverlay(new GMarker(point));
		// controls
	    var mapTypeControl = new GMapTypeControl();
	    var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(2,2));
	    var bottomRight = new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(2,2));
	    map.addControl(mapTypeControl, topRight);
	    map.addControl(new GSmallMapControl());
	}
	</script>
	<style type="text/css" media="screen">
		body {
			margin: 0px;
			padding: 0px;
			text-align: center;
		}
	</style>
</head>
<body onload="initialize()" onunload="GUnload()">
	<div id="map_canvas" style="width: <?php echo $_GET['w'] ?>; height: <?php echo $_GET['h'] ?>px;border: 1px solid #000; padding: 2px;"></div>
</body>
</html>



