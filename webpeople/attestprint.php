<?php
define('NIVO', '../');

include "includes/ifentete.php" ;
## Classes utilitées
include_once(NIVO."classes/anim.php");
include_once(NIVO."classes/vip.php");
include_once(NIVO."classes/merch.php");
include_once(NIVO."classes/payement.php");

/*
	TODO : !! recopie de code par rapport a l'impression interne, faut changer ca !
*/

$_POST['idpeople'] = $_SESSION['idpeople'];

$dates['datein'] = fdatebk($_POST['datein']);
$dates['dateout'] = fdatebk($_POST['dateout']);

$mindate = '2003-09-01';
$maxdate = $DB->CONFIG('lastpayement');

if ($dates['datein'] < $mindate) $datein = $mindate;
if (($dates['dateout'] > $maxdate) or ($dates['dateout'] < $mindate)) $dates['dateout'] = $maxdate;
		
$ptable = paytable ($_POST['idpeople'], $dates['datein'], $dates['dateout']);
$dtable = array_shift($ptable);

$web = true;
include(NIVO."print/people/attest/attest.php");

# Pied de Page
include "includes/ifpied.php" ;
?>
