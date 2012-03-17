<?php
define('NIVO', '../../');

# Entete de page
$Titre = 'COMMISSIONS';

include NIVO."includes/entete.php" ;

include NIVO.'classes/vip.php';
include NIVO.'classes/anim.php';
include NIVO.'classes/merch.php';

include 'm-commission.php';

$activeyear = (!empty($_REQUEST['activeyear']))?$_REQUEST['activeyear']:date("Y");
$activemonth = (!empty($_REQUEST['activemonth']))?$_REQUEST['activemonth']:date("m");

# Carousel des fonctions
switch ($_REQUEST['act']) {
	case 'variable':
		# code...
	break;
	default:
		include 'v-list.php';
}

?>
<!-- <div id="topboutons">
<table border="0" cellspacing="1" cellpadding="0">
	<tr>
		<td class="on"><a href="?act=search"><img src="<?php echo NIVO ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
	</tr>
</table> 
</div> -->
<?php include NIVO."includes/pied.php" ; ?>