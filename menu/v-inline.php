<?php
switch ($_REQUEST['link']) {
	case 'mail':
		$lelink = "http://77.109.79.37:8080/";
	break;
	
	case 'cal':
		$lelink = "http://77.109.79.37/calendar/";
	break;
	
	default:
		$lelink = '';
	break;
}


?>
<div id="centerzonelarge">
	<iframe frameborder="0" marginwidth="0" marginheight="0" name="news" src="<?php echo $lelink; ?>" width="100%" height="100%">Marche pas les IFRAMES !</iframe>
</div>