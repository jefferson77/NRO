<?php
define("NIVO", "../../");

$Titre = 'AGENTS';
$PhraseBas = 'Liste des Agents';
$Style = 'admin';

include NIVO."includes/entete.php" ;

# Classes utilisées
require_once NIVO.'data/agent/m-agent.php';

## Fonctions
function makeRandomPassword() {
	$salt = "ABCHEFGHJKMNPQRSTUVWXYZ123456789";
	srand((double)microtime()*1000000);
	$i = 0;
	while ($i <= 3) {
		$num = rand() % 33;
		$tmp = substr($salt, $num, 1);
		$pass .= $tmp;
		$i++;
	}
	return $pass;
}

## controleur
switch ($_REQUEST['act']) {
	case 'Supprimer':
		$DB->inline("DELETE FROM agent WHERE idagent = ".$_POST['idagent']);
		$msg = 'Agent n&deg; '.$_POST['idagent'].' effac&eacute;';
		include 'v-list.php';
	break;
	
	case 'Ajouter':
		$_POST['serial'] = 'NE-'.makeRandomPassword().'-'.makeRandomPassword();
		$DB->ADD('agent');
		$msg = 'Agent n&deg; '.$ajout->addid.' ajout&eacute;';
		include 'v-list.php';
	break;
	
	case 'show':
		include 'v-detail.php';
	break;
	
	case 'Modifier':
		$DB->MAJ('agent');
		$msg = 'Fiche '.$_POST['idagent'].' mise &agrave; jour';
		include 'v-list.php';
	break;

	default:
		include 'v-list.php';
}

# Pied de Page
include NIVO."includes/pied.php" ;
?>
