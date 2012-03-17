<?php
define('NIVO', '../../');

include NIVO."includes/ifentete.php" ;

switch ($_REQUEST['act']) {
# =============================================================================
# = Commissions ============================================================= =
# =============================================================================
	case 'commissionAdd':
		$DB->ADD('commissions');
		include 'v-onglet-commissions.php';
	break;

	case 'commissionDelete':
		if (!empty($_REQUEST['idcommission'])) $DB->inline("DELETE FROM commissions WHERE idcommission = ".$_REQUEST['idcommission']);
		include 'v-onglet-commissions.php
		';
	break;
	
	case 'commissionModif':
		$DB->MAJ('commissions');
	case "commissionShow":
		include 'v-onglet-commissions.php';
	break;

# =============================================================================
# = Tarifs ================================================================== =
# =============================================================================
	case 'tarifModif':
		$DB->MAJ("supplier");
	case 'tarifShow':
		include 'v-onglet-tarifs.php';
	break;

# =============================================================================
# = Contacts ================================================================ =
# =============================================================================
	case "contactAdd":
		$DB->ADD('sofficer');
		include 'v-onglet-contacts.php';
	break;
	
	case "contactDelete":
		if (!empty($_REQUEST['idsofficer'])) $DB->inline("DELETE FROM sofficer WHERE idsofficer = ".$_REQUEST['idsofficer']);
		include 'v-onglet-contacts.php';
	break;

	case 'contactModif':
		$DB->MAJ('sofficer');
	case 'contactShow':
	default:
		include 'v-onglet-contacts.php';
	break;
}

include NIVO."includes/ifpied.php" ;
?>