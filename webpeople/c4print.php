<?php
define('NIVO', '../');

include "includes/ifentete.php" ;

include_once(NIVO."classes/anim.php");
include_once(NIVO."classes/vip.php");
include_once(NIVO."classes/merch.php");

include_once(NIVO."classes/payement.php");
require_once(NIVO.'classes/document.php');

## découpage de la variable contenant les dates à imprimer
$ptable = paytable($_SESSION['idpeople'], '2003-09-01', $DB->CONFIG('lastpayement'));
$ztable = array_shift($ptable);

foreach($_POST['print'] as $value) {
	$dtable[$value] = $ztable[$value];
}

$_POST['idpeople'] = $_SESSION['idpeople'];
$web = true;
include(NIVO."print/people/c4/c4.php");

# Pied de Page
include "includes/ifpied.php" ;
?>
