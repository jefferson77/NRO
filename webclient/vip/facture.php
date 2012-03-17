<?php
define('NIVO', '../../'); 
$Style = 'vip';
include NIVO."includes/ifentete.php" ;
# Classes utilisées
include_once(NIVO."classes/vip.php");

###
$web = 'web';

if (!empty($_GET['facnum'])) {
	include_once(NIVO."classes/facture.php");
	$_POST['facs'] = $_GET['facnum'];
	include NIVO."admin/factures/print.php" ;
}

# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
