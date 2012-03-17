<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

include dirname(__FILE__).'/config/config.php';

## phrase book
if (isset($_GET['l']) && in_array($_GET['l'], array('fr', 'nl', 'en'))) include dirname(__FILE__).'/lang/'.$_GET['l'].'.php';
else {
	include dirname(__FILE__).'/lang/fr.php';
	$_GET['l'] = 'fr';
}

switch (isset($_GET['p'])?$_GET['p']:'') {
	case 'presentation':
	case 'events':
	case 'partenaires':
	case 'contacts':
	case 'video':
	case 'vip':
	case 'anim':
	case 'merch':
	case 'eas':
		$menu = 'light';
		include 'includes/entete.php';
		include 'views/'.$_GET['p'].'.php';
	break;

	case 'menu':
	default:
		$menu = 'full';
		include 'includes/entete.php';
		include 'views/menu.php';
	break;
}

include 'includes/pied.php';
?>