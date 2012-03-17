<?php
define('NIVO', '../../');

function obfuscateMail ($email) {
	$obfuscatedEmail = '';
    $length = strlen($email);                         // get the length of the email address
    for ($i = 0; $i < $length; $i++) $obfuscatedEmail .= "&#" . ord($email[$i]); // get the deicmal ASCII value of the character
	return $obfuscatedEmail;
}


## Classes
include __DIR__.'/../lang/'.$_GET['l'].'.php';
require_once(NIVO."nro/fm.php");
require_once NIVO.'data/agent/m-agent.php';

$agents = $DB->getArray("SELECT * FROM agent WHERE onsite = 'oui' AND isout = 'N' ORDER BY secteur, adlevel, nom");

$lastsecteur = '';

foreach ($agents as $agent) {
	if ($agent['secteur'] != $lastsecteur) {
		echo '<h1 class="secteur">'.$secteurs[$agent['secteur']].'</h1>';
	}

	echo '<div class="agentcard">
	<img src="illus/agents/'.getAgentIllu($agent['idagent']).'.png" alt=""/>
	<span class="nom">'.$agent['prenom'].' '.$agent['nom'].'</span><br/>
	<span class="fonction">'.$agent['fonction'].'</span><br/><br/>
	'.obfuscateMail($agent['email']).'<br/><br/>
	'.$agent['atel'].'<br/>
	'.$agent['agsm'].'<br/>
	</div>';

	$lastsecteur = $agent['secteur'];
}
?>
