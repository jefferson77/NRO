<?php 
define("NIVO", "../../");

require_once(NIVO."nro/fm.php");

include NIVO.'includes/entete.php';

require_once NIVO."classes/anim.php";
require_once NIVO."classes/vip.php";
require_once NIVO."classes/merch.php";
require_once NIVO."classes/facture.php";

switch ($_REQUEST['act']) {
	case 'display':
		include 'v-display.php';
		break;
	
	case 'search':
	default:
			include 'v-search.php';
		break;
}

?>
<div id="topboutonsadmin">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="on"><a href="?act=search"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
		</tr>
	</table>
</div>
<?php
include NIVO.'includes/pied.php';
?>