<?php 
function LOGCheckMachine($serial, $ip) {
	## Recherche du s�rial dans la base machines�	
	$searchserial = new db();
	$searchserial->inline("SELECT * FROM `machine` WHERE `serial` LIKE '$serial'");
	$nbrserial = mysql_num_rows($searchserial->result);
	$infos = mysql_fetch_array($searchserial->result);
	$idm = $infos['idmachine'];
	$_POST['idm'] = $idm;

	switch ($nbrserial) {
	# S�rial non enregistr�
		case "0":
			#log de l'IP, date, DNS de l'attaque
			return 'badmachine';
		break;
	## S�rial Enregistr�
		case "1":
			#V�rification de l'IP et log de la derni�re date de login
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
	## S�rial non unique ! problem
		default: 
			return 'tomanymachines';
	}

}




?>