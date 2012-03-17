<?php
/**
 * calcule se salaire d'un people a une date donnée (now si la date n'est pas spécifiée)
 *
 * @return void
 * @author Nico
 **/
function salaire($idpeople, $vdate = '') {
	global $DB;

	if (empty($vdate)) $vdate = date("Y-m-d"); # date de validite du salaire

	$dats = $DB->getRow("SELECT ndate, salaire FROM neuro.people WHERE idpeople = $idpeople");

	$datenaiss = $dats['ndate'];
	$agepay = $dats['salaire'];

	##<# Baremes Custom ####################
		$salcust = array(
			"9874" => "15.5" ## Nico
		);

		if (array_key_exists($idpeople, $salcust)) {
			return $salcust[$idpeople];
		}
	##># Baremes Custom ####################

	if (date("Y", strtotime($datenaiss)) > 1910) {

		$age = age($datenaiss, $vdate);

		if ($age < $agepay) {
			## Utilise le barème de l'age custom
			$tarbrut = $DB->getOne("SELECT `tarifbrut` FROM neuro.barsalaires WHERE `age` = '$agepay' AND datein <= '$vdate' AND dateout >= '$vdate' ");
		} else {
			## Calcul le barème sur la date de naissance
			if ($agepay > 10) {
				## Remise en AUTO du salaire
				$DB->inline("UPDATE neuro.people SET `salaire` = '0' WHERE `idpeople` = '".$idpeople."' LIMIT 1 ;");
			}

			$minma = $DB->getRow("SELECT MIN( age ) AS min, MAX( age ) AS max FROM neuro.barsalaires WHERE datein <= '$vdate' AND dateout >= '$vdate'");

			if ($age > $minma['max']) $age = $minma['max'];
			if ($age < $minma['min']) $age = $minma['min'];

			$tarbrut = $DB->getOne("SELECT `tarifbrut` FROM neuro.barsalaires WHERE `age` = '$age' AND datein <= '$vdate' AND dateout >= '$vdate'");
		}

		return $tarbrut;
	} else {
		return "ERROR ann naiss";
	}
}

/**
 * Calcul de la table de paiement pour un people pour une période donnée
 *
 * @return tableau par jour : date, heures, frais433, frais437, frais441, h150, h200, nivo
 * @author Nico
 **/
function paytable ($idpeople, $datein, $dateout) {

	global $DB;
	$dtable = array();

	## mode multi ou unique
	if ($idpeople == 'ALL') {
		$where = "p.codepeople >= 1 AND p.catsociale IN (1, 'E') AND p.idsupplier < 1";
	} elseif (is_numeric($idpeople)) {
		$where = "p.idpeople = ".$idpeople;
	} else {
		$error[] = "paytable : le people spécifié est invalide";
		return "error";
	}

	## ANIM ##################################################################
	$anims = $DB->getArray("SELECT a.idanimation, a.datem, a.idpeople
		FROM animation a
			LEFT JOIN people p ON a.idpeople = p.idpeople
		WHERE a.datem BETWEEN '".$datein."' AND '".$dateout."'
			AND ".$where."
		ORDER BY a.idpeople, a.datem");

	foreach ($anims as $row) {
		$dateform = strftime("%Y%m%d", strtotime($row['datem']));
		$zanim = new coreanim($row['idanimation']);

		if (!isset($dtable[$row['idpeople']][$dateform])) $dtable[$row['idpeople']][$dateform] = array('date' => '', 'heures' => 0, 'frais433' => 0, 'frais437' => 0, 'frais441' => 0, 'h150' => 0, 'h200' => 0, 'nivo' => '');

		$dtable[$row['idpeople']][$dateform]['date']      = $row['datem'];
		$dtable[$row['idpeople']][$dateform]['heures']   += $zanim->hprest;
		$dtable[$row['idpeople']][$dateform]['frais433'] += $zanim->frais433;
		$dtable[$row['idpeople']][$dateform]['frais437'] += $zanim->frais437;
		$dtable[$row['idpeople']][$dateform]['frais441'] += $zanim->frais441;
		$dtable[$row['idpeople']][$dateform]['h150']     += $zanim->h150;
		$dtable[$row['idpeople']][$dateform]['h200']     += $zanim->h200;
		$dtable[$row['idpeople']][$dateform]['nivo']     .= 'A';
	}

	## VIP ###################################################################
	$vips = $DB->getArray("SELECT v.idvip, v.vipdate, v.idpeople
		FROM vipmission v
			LEFT JOIN people p ON v.idpeople = p.idpeople
		WHERE v.vipdate BETWEEN '".$datein."' AND '".$dateout."'
			AND ".$where."
		ORDER BY v.idpeople, v.vipdate");

	foreach ($vips as $row) {
		$dateform = strftime("%Y%m%d", strtotime($row['vipdate']));
		$fich = new corevip ($row['idvip']);

		if (!isset($dtable[$row['idpeople']][$dateform])) $dtable[$row['idpeople']][$dateform] = array('date' => '', 'heures' => 0, 'frais433' => 0, 'frais437' => 0, 'frais441' => 0, 'h150' => 0, 'h200' => 0, 'nivo' => '');

		$dtable[$row['idpeople']][$dateform]['date']      = $row['vipdate'];
		$dtable[$row['idpeople']][$dateform]['heures']   += $fich->thpaye;
		$dtable[$row['idpeople']][$dateform]['frais433'] += $fich->frais433;
		$dtable[$row['idpeople']][$dateform]['frais437'] += $fich->frais437;
		$dtable[$row['idpeople']][$dateform]['frais441'] += $fich->frais441;
		$dtable[$row['idpeople']][$dateform]['h150']     += $fich->thp150;
		$dtable[$row['idpeople']][$dateform]['h200']     += $fich->thp200;
		$dtable[$row['idpeople']][$dateform]['nivo']     .= 'V';
	}

	## MERCH et EAS ##########################################################
	$merchs = $DB->getArray("SELECT m.idmerch, m.datem, m.genre, m.idpeople
		FROM merch m
			LEFT JOIN people p ON m.idpeople = p.idpeople
		WHERE m.datem BETWEEN '".$datein."' AND '".$dateout."'
			AND ".$where."
		ORDER BY m.idpeople, m.datem");

	foreach ($merchs as $row) {
		$dateform = strftime("%Y%m%d", strtotime($row['datem']));
		$zmerch = new coremerch($row['idmerch']);

		if (!isset($dtable[$row['idpeople']][$dateform])) $dtable[$row['idpeople']][$dateform] = array('date' => '', 'heures' => 0, 'frais433' => 0, 'frais437' => 0, 'frais441' => 0, 'h150' => 0, 'h200' => 0, 'nivo' => '');

		$dtable[$row['idpeople']][$dateform]['date']      = $row['datem'];
		$dtable[$row['idpeople']][$dateform]['heures']   += $zmerch->hprest;
		$dtable[$row['idpeople']][$dateform]['frais433'] += $zmerch->frais433;
		$dtable[$row['idpeople']][$dateform]['frais437'] += $zmerch->frais437;
		$dtable[$row['idpeople']][$dateform]['frais441'] += $zmerch->frais441;
		$dtable[$row['idpeople']][$dateform]['nivo']     .= ($row['genre'] == 'EAS')?'E':'M';
	}

	if (is_array($dtable)) ksort($dtable);

  array_walk($dtable, 'ksort');

	return $dtable;
}

class payement
{
	# Config Values
	var $id       = '';
	var $reg      = '';
	var $tarifb   = '';
	var $paiement = '';


	function payement ($id, $cust = 'AUTO') {
		global $DB;

		if ($cust == 'AUTO') {
			$latable = $_SESSION['table'];

			#### Calcul des datein et datout
			$tmois = substr($_SESSION['table'], -2, 2);
			$tyear = '20'.substr($_SESSION['table'], -4, 2);

			$this->datein  = date("Y-m-d", mktime(0,0,0,$tmois,01,$tyear));
			$this->dateout = date("Y-m-t", mktime(0,0,0,$tmois,01,$tyear));
			#### datein fin

		} else {
			$latable = 'custom009';

			#### Calcul des datein et datout
			$zedates = $DB->getRow("SELECT `nomptg`, `datein`, `dateout` FROM grps.customptg WHERE `idptg` = '$cust'");

			$this->datein  = $zedates['datein'];
			$this->dateout = $zedates['dateout'];

			$this->nomptg  = $zedates['nomptg'];
			#### datein fin
		}

	#### Infos People
		$this->id = $id;  ### ID People

		$infp = $DB->getRow("SELECT
				s.idpeople, p.pnom, p.pprenom, p.codepeople, COUNT(s.date) AS nbjours, p.err, p.ndate, p.lbureau,
				p.adresse1, p.num1, p.bte1, p.cp1, p.ville1, p.gsm, p.nrnational, p.banque, p.modepay, p.iban, p.bic, p.salaire
			FROM grps.".$latable." s
				LEFT JOIN neuro.people p ON s.idpeople = p.idpeople
			GROUP BY s.idpeople
			HAVING s.idpeople = ".$this->id);

		## Mode de paiement
		switch ($infp['modepay']) {

			case "1": # Bancaire
				if (preg_match("/([0-9]{3})([0-9]{7})([0-9]{2})/", $infp['banque'], $regs)) {
					$this->paiement = $regs[1].'-'.$regs[2].'-'.$regs[3];
				} else {
					$this->paiement = 'Erreur Num de compte';
				}
			break;

			case "2": # Chèque
					$this->paiement = 'Payement par ch&egrave;que';
			break;

			case "3": # Iban Bic
					$this->paiement = 'IBAN : '.$infp['iban'].'<br>BIC :'.$infp['bic'];
			break;

			case "4": # Comptant
					$this->paiement = 'Payement comptant';
			break;

		}


		$this->reg    = $infp['codepeople']; ### Num Reg

		$this->age    = date ("Y-m-d") - $infp['ndate'];

		$this->tarifb = salaire($id, $this->datein);

		$this->infp   = $infp;
	}


}

