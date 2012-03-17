<?php
define('NIVO', '../../'); 
$Style = 'anim';
include NIVO."includes/ifentete.php" ;
# Classes utilisées
include_once(NIVO."classes/anim.php");

###
$web = 'web';

if (!empty($_GET['facs']))  {
	include_once(NIVO."classes/facture.php");
	$_POST['facs'] = $_GET['facs'];
	include NIVO."admin/factures/print.php" ;
}	
#/##

# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
