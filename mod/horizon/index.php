<?php
define('NIVO', '../../'); 

#### Classes utilisées ####
include NIVO."classes/vip.php" ;
include NIVO."classes/anim.php" ;
include NIVO."classes/merch.php" ;
include NIVO."classes/tvacheck.php" ;
include NIVO."classes/facture.php" ;

include 'm-horizon.php';

# Entete de page
$Titre = 'Horizon';
$Style = 'admin';

include NIVO."includes/entete.php" ;

switch ($_REQUEST['act']) {
	case 'assignation':
		include 'v-assign.php';
	break;
	
	case 'assign':
		$DB->inline("UPDATE `client` SET `codecompta` = '".$_POST['codeh']."' WHERE `tva` = '".$_POST['tva']."' AND `codetva` = '".$_POST['codetva']."';");
	break;
	
	case 'generate':
		$hor = new horizon();
		
		if ($_POST['facfromid'] > 0) $hor->add_facs($_POST['facfromid'], $_POST['factoid']);
		if ($_POST['ncfromid'] > 0) $hor->add_ncs($_POST['ncfromid'], $_POST['nctoid']);
		
		$hor->check_client();

		if (count($hor->tvaerror)) {
			$showpart = 'tvaerror';
		} elseif (count($hor->newclients) > 0) {
			$showpart = 'newclients';
		} else {
			$retclient = $hor->generate_client();
			$retencfac = $hor->generate_encfac();

			$d = opendir($hor->ledir);
			$showpart = 'genresult';
		}

		include 'v-result.php';
	break;
			
	default:
		include 'v-search.php';
	break;
}
?>
<div id="topboutonsadmin">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
		</tr>
	</table>
</div>
<?php include NIVO."includes/pied.php"; ?>