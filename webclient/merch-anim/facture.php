<?php
define('NIVO', '../../'); 

include NIVO."includes/ifentete.php" ;

include_once(NIVO."classes/anim.php");
include_once(NIVO."classes/vip.php");
include_once(NIVO."classes/merch.php");

include_once(NIVO."classes/facture.php");
###
$_POST['facs'] = $_GET['facnum'];
$web = 'web';
include NIVO."admin/factures/print.php" ;

#/##

# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
