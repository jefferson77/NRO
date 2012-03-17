<?php 
function LOGCheckMachine($serial, $ip) {
	## Recherche du sérial dans la base machinesé	
	$searchserial = new db();
	$searchserial->inline("SELECT * FROM `machine` WHERE `serial` LIKE '$serial'");
	$nbrserial = mysql_num_rows($searchserial->result);
	$infos = mysql_fetch_array($searchserial->result);
	$idm = $infos['idmachine'];
	$_POST['idm'] = $idm;

	switch ($nbrserial) {
	# Sérial non enregistré
		case "0":
			#log de l'IP, date, DNS de l'attaque
			return 'badmachine';
		break;
	## Sérial Enregistré
		case "1":
			#Vérification de l'IP et log de la dernière date de login
			if ($infos['iptype'] == "FX") {
				if (!empty($infos['lastip'])) {
					if ($ip == $infos['lastip']) {
						##### LOG OK #####
						$updlastip = new db();
						$updlastip->inline("UPDATE `machine` SET `lastlog` = NOW() WHERE `idmachine` = $idm");
						
						return 'machineok';
					} else {
						return 'badip';
					}
				
				} else {
					$updlastip = new db();
					$updlastip->inline("UPDATE `machine` SET `lastip` = '$ip', `lastlog` = NOW() WHERE `idmachine` = $idm");
					
					return 'machineok';
				}
			} else {
				$updlastip = new db();
				$updlastip->inline("UPDATE `machine` SET `lastip` = '$ip', `lastlog` = NOW() WHERE `idmachine` = $idm");
				
				return 'machineok';
			}	
		break;
	## Sérial non unique ! problem
		default: 
			return 'tomanymachines';
	}

}




?>