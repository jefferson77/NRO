<?php
## fonctions générales
function trim_value(&$value)
{
    $value = trim($value);
}

## renvoie l'illu de l'agent si existe, sinon nopics
function getAgentIllu($idagent) {
	$illusagent = file(Conf::read('WebSiteURL').'illus/agents/agentlist.php');
	array_walk($illusagent, 'trim_value');
	$agentillu = in_array($idagent, $illusagent)?$idagent:'nopics';

	return $agentillu ;
}
?>