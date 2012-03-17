<?php
define('NIVO', '../../../');

# Classes utilisées
include NIVO.'classes/vip.php';

switch ($_REQUEST['print']) {
	case 'date':
		include NIVO."includes/ifentete.php" ;
		include 'v-search.php';
		include NIVO."includes/ifpied.php" ;
	break;
	
	case 'go':
		include 'p-presence.php';
	break;
}
?>