<?php
### Update d'un PO
function updatePO ($idfac, $newpo) {
	global $DB;
	
	## Recup ancien PO
	$old = $DB->getRow("SELECT po, secteur, idfac, modefac FROM facture WHERE idfac = ".$idfac);
	if($old['modefac'] == 'A') {
		if ($newpo != $old['po']) {
			switch ($old['secteur']) {
				## Vip
				case '1':
					## le bon de commande est stocké sur le job
					$DB->inline("UPDATE vipjob SET bondecommande = '".$newpo."' WHERE facnum = ".$idfac);
				break;

				## Anim
				case '2':
					## le bon de commande est stocké sur le job, mais le num de fac sur les missions (ouais bon, d'accord, c'est pas top)
					$DB->inline("UPDATE animjob SET boncommande = '".$newpo."' WHERE idanimjob IN (SELECT idanimjob FROM animation WHERE facnum = ".$idfac." GROUP BY idanimjob)");
				break;

				## Merch / Eas
				case '3':
				case '4':
					## le bon de commande est stocké sur chaque mission
					$DB->inline("UPDATE merch SET boncommande = '".$newpo."' WHERE facnum = ".$idfac);
				break;
			}
		}
	}
}


?>