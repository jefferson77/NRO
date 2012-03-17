<?php
define('NIVO', '../../');

## Entete
include NIVO.'includes/entete.php' ;
include NIVO.'mod/sms/m-sms.php';

switch($_REQUEST['act'])
{
	case "show":
		include "v-sendsms.php";
	break;
	
	case "redirect":
		//ici ça va gérer l'envoi du sms, l'ajout et la suppression de people
		if(!empty($_POST['addidpeople'])) // on ajoute un people en clickant sur sur +
		{
			$_POST['dest'][] = $_POST['idpeople'];
		}
		elseif(!empty($_POST['rmidpeople'])) // on enlève une personne
		{
			$remove[] = $_POST['rmidpeople'];
			$_POST['dest'] = array_diff($_POST['dest'],$remove);
		}
		elseif(!empty($_POST['send'])) // on envoie le sms
		{
			## récupération et formatage des informations d'envoi
			if((!empty($_POST['text'])) && (count($_POST['dest'])>0))
			{
				$infagent = $DB->getRow("SELECT prenom, agsm FROM agent WHERE idagent = ".$_POST['idagent']);				
				$phoneagent = fphone($infagent['agsm']);
				$agentgsm = $phoneagent['human'];
				sendsms($_POST['dest'],$_POST['idagent'],utf8_decode($_POST['text']." ".$infagent['prenom']." ".$agentgsm));
				$_POST['text'] = "message envoyé, vous pouvez quitter";
				$_POST['disable'] = "disable";
			}
			else
			{
				$_POST['text'] = "Veillez rentrer un message et vérifier qu'il n'y a pas 0 destinataires !";
			}
		}

		include "v-sendsms.php";
	break;
}
# Pied de Page
include NIVO.'includes/pied.php' ;
?>