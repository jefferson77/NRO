<?php
/**
 * commissionDue : Génère la table des montants dus pour une commission donnée
 * 
 * @author nico
 * @param int ID de la commission
 * @param date Date de début de la période, si non spécifié : début de la commission
 * @param date Date de la fin de la période, si non spécifié : fin de la commission
 * @return Tableau des montants a reverser pour la commission donnée array(secteur, idjob, reference, heures, commissionStandard, commissionCustom, montant)
 *
 */
function commissionDue($idcommission, $periodin = 'NO', $periodout = 'NO')
{
	global $DB;

	## get commission data ################################################################################
	$commission = $DB->getRow("SELECT * FROM commissions WHERE idcommission = ".$idcommission);
	
	## period ##############################################################################################
	$startdate = ($periodin != 'NO')?$periodin:$commission['datein'];
	$enddate = ($periodout != 'NO')?$periodout:$commission['dateout'];

	## get infos missions ##################################################################################
	$vips = $DB->getArray("SELECT m.idvip, m.idvipjob, j.reference FROM vipmission m LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob WHERE m.vipdate BETWEEN '".$startdate."' AND '".$enddate."' AND j.idclient = ".$commission['idclient']);
	$anims = $DB->getArray("SELECT m.idanimation, m.idanimjob FROM animation m LEFT JOIN animjob j ON m.idanimjob = j.idanimjob WHERE m.datem BETWEEN '".$startdate."' AND '".$enddate."' AND j.idclient = ".$commission['idclient']);
	$merchs = $DB->getArray("SELECT m.idmerch, m.weekm, m.yearm FROM merch m WHERE m.datem BETWEEN '".$startdate."' AND '".$enddate."' AND m.idclient = ".$commission['idclient']);

	## process #############################################################################################
	if ((count($vips) + count($anims) + count($merchs)) > 0) {
		## Remplissage de la table de résultats VIP
		if (count($vips) > 0) {
			foreach ($vips as $vip) {
				$fich = new corevip ($vip['idvip']);

				$tabldata['V-'.$vip['idvipjob']]['secteur'] = 'VIP';
				$tabldata['V-'.$vip['idvipjob']]['idjob'] = $vip['idvipjob'];
				$tabldata['V-'.$vip['idvipjob']]['reference'] = $vip['reference'];
				$tabldata['V-'.$vip['idvipjob']]['heures'] += $fich->thfact;
				$tabldata['V-'.$vip['idvipjob']]['com'] = $commission['montant'];
			}
		}

		## Remplissage de la table de résultats ANIM
		if (count($anims) > 0) {
			foreach ($anims as $anim) {
				$fich = new coreanim ($anim['idanimation']);

				$tabldata['A-'.$anim['idanimjob']]['secteur'] = 'ANIM';
				$tabldata['A-'.$anim['idanimjob']]['idjob'] = $anim['idanimjob'];
				$tabldata['A-'.$anim['idanimjob']]['reference'] = $anim['reference'];
				$tabldata['A-'.$anim['idanimjob']]['heures'] += $fich->hprest;
				$tabldata['A-'.$anim['idanimjob']]['com'] = $commission['montant'];
			}
		}
		
		## Remplissage de la table de résultats MERCH
		if (count($merchs) > 0) {
			foreach ($merchs as $merch) {
				$fich = new coremerch ($merch['idmerch']);

				$tabldata['M-'.$merch['weekm'].'-'.$merch['yearm']]['secteur'] = 'MERCH';
				$tabldata['M-'.$merch['weekm'].'-'.$merch['yearm']]['idjob'] = $merch['weekm'].'-'.$merch['yearm'];
				$tabldata['M-'.$merch['weekm'].'-'.$merch['yearm']]['reference'] = 'Semaine '.$merch['weekm'].' '.$merch['yearm'];
				$tabldata['M-'.$merch['weekm'].'-'.$merch['yearm']]['heures'] += $fich->hprest;
				$tabldata['M-'.$merch['weekm'].'-'.$merch['yearm']]['com'] = $commission['montant'];
			}
		}
	}

	## Calcul des totaux
	if (count($tabldata) > 0) array_walk($tabldata, "total");
	
	## return
	return $tabldata;
}

/**
 * fonction de calcul du total 
 *
 * @return rien, il manipule juste les données de l'array fournie
 * @param row d'une array
 * @author Nico
 **/
function total(&$ar) {
	$ar['total'] = $ar['heures'] * $ar['com'];
}

?>
