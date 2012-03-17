<?php
class coremerch
{
	# Config Values
	var $tmrifkmpay = 0.23; # Prix d'un kilomètre payé a un Promoboy
	var $tarifdimona = 0.271; # Frais dimona par Heure

	# Variables
	var $hprest = 0;
	var $hprestr = 0;
	var $kipay = 0;
	var $kifac = 0;
	var $fraispay = 0;

	var $frais437 = 0;
	var $frais441 = 0;
	var $frais433 = 0;
	var $date = 0;

	var $FraisDimona = 0;
	var $HeuresDimona = 0;
	var $Tarif = array();

	# fonctions de formatage
	function htonum ($heures) {
		$h1 = explode (':', $heures);

		if (!isset($h1[1])) $h1[1] = 0;

		$hin = $h1[0] + ($h1[1] / 60);

		return $hin;
	}


	## Fonctin principale
	function coremerch ($idmission, $table = 'MAIN') {

		if ($table == 'dup') $sqltable = 'merchduplic';
		else $sqltable = 'merch';

		$findinfos = new db();
		$row = $findinfos->getRow("SELECT
			m.hin1, m.hout1, m.hin2, m.hout2, m.ferie, m.kmfacture, m.kmpaye, m.datem, m.facnum,
			nf.montantpaye,
			c.fraisdimona,
			f.fraisdimona as ffraisdimona
			FROM ".$sqltable." m
			LEFT JOIN client c ON m.idclient = c.idclient
			LEFT JOIN facture f ON m.facnum = f.idfac
			LEFT JOIN notefrais nf ON m.idmerch = nf.idmission AND nf.secteur = 'ME'
			WHERE m.idmerch = '".$idmission."'");

		# formatage des heures en nombres
		$row['hin1'] = $this->htonum ($row['hin1']) ;
		$row['hout1'] = $this->htonum ($row['hout1']) ;
		$row['hin2'] = $this->htonum ($row['hin2']) ;
		$row['hout2'] = $this->htonum ($row['hout2']) ;

		$this->Tarif['fraisdimona'] = ($row['facnum'] > 0)?$row['ffraisdimona']:$row['fraisdimona'];
		$this->date = $row['datem'];

		# ajuste les heures du matin
		if ($row['hout1'] < $row['hin1']) $row['hout1'] = $row['hout1'] + 24;
		if ($row['hout2'] < $row['hin2']) $row['hout2'] = $row['hout2'] + 24;

		# heures prestées par le promoboy
		$total = $row['hout1'] - $row['hin1'] + $row['hout2'] - $row['hin2'];

		$arrondi2 = ceil($total * 4);
		$this->hprestr = $arrondi2 / 4; # heures reelement prestées

		$row['ferie'] = $row['ferie'] * 1.0000;
		$total = $total * ($row['ferie'] / 100);

		$arrondi = ceil($total * 4);
		$this->hprest = $arrondi / 4; # heures payées

	#### Calcul des Frais DIMONA ###################
		if (($this->Tarif['fraisdimona'] == 'oui') and ($this->date >= "2009-06-01")) {
			$tauxDimona = array(
								'2009-07-01' => 0.291,
								'2011-01-01' => 0.310,
								'2011-06-01' => 0.500,
							);
			krsort($tauxDimona);
			foreach ($tauxDimona as $date => $taux) {
				if ($this->date >= $date) {
					$this->tarifdimona = $taux;
					break;
				}
			}

			$this->FraisDimona = $this->hprestr * $this->tarifdimona;
			$this->HeuresDimona = $this->hprestr;
		}

			$this->kipay = $row['kmpaye'];
			$this->kifac = $row['kmfacture'];

		# FRAIS Payement

			# Frais 437 : Frais déplacement
			$this->frais437 = $this->kipay * $this->tmrifkmpay;

			# Frais 441 : Reste des frais (remboursement ts frais)
			$datebut = implode("", explode("-", $row['datem']));

			if ($datebut > '20060931') {
				$this->frais441 = 0; ## Plus de frais payés a partir du 1er Octobre 2006
			} else {
				$this->frais441 = $row['montantpaye'];
			}

		$this->fraispay = $this->frais433 + $this->frais437 + $this->frais441;
	}
}

?>