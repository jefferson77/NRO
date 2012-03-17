<?php
class corevip
{
	var $tarifkmpay   = 0.1983; # Prix d'un kilomètre payé a un Promoboy
	var $tarifdimona  = 0.251; # Frais dimona par Heure
	var $forfait2011  = 3; # Nombre minimal d'heure facturées/payées en 2011
	var $forfait2010  = 4; # Nombre minimal d'heure facturées/payées en 2010
	var $htot         = 0;
	var $hprest       = 0;
	var $hnight       = 0;
	var $h150         = 0;
	var $h200         = 0;
	var $hhigh        = 0;
	var $hlow         = 0;
	var $hspec        = 0;
	var $thfact       = 0;
	var $kipay        = 0;
	var $kiforf       = 0;
	var $fraispay     = 0;
	var $thp100       = 0;
	var $thp150       = 0;
	var $thp250       = 0;
	var $brk          = 0;
	var $date         = 0;

	var $facnum       = 0;
	var $idclient     = 0;

	var $frais433     = 0;
	var $frais437     = 0;
	var $frais441     = 0;
	var $FraisDimona  = 0;
	var $HeuresDimona = 0;
	var $MontNfrais   = 0;
	var $FraisP       = 0;

	var $MontPrest    = 0;
	var $MontDepl     = 0;
	var $MontLoc      = 0;
	var $MontTotal    = 0;


	function corevip ($idvip, $mode = 'M') {

		if ($mode == 'M') {
			$mistable  = 'vipmission';
			$supchamps = ' j.facnum,';
		} else {
			$mistable  = 'vipdevis';
			$supchamps = '';
		}

	#### Get les infos de la mission ################################################################################################################
		$getinfo = new db();
		$mif = $getinfo->getRow("SELECT
		m.vipin, m.vipout, m.brk, m.night, m.ts, m.fts, m.ajust, m.vcat, m.vdisp, m.vfr1, m.vfrpeople, m.vkm, m.vfkm, m.h150, m.h200,
		m.km, m.fkm, m.unif, m.net, m.loc1, m.loc2, m.cat, m.disp, m.fr1, m.vipdate,
		j.idclient, ".$supchamps."
		nf.montantfacture, nf.montantpaye
		FROM ".$mistable." m
		LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob
		LEFT JOIN notefrais nf ON nf.secteur = 'VI' AND nf.idmission = m.idvip
		WHERE m.idvip = '".$idvip."'");

	#### Numéro de facture et de client pour la fonction 'montants'
		$this->facnum        = $mif['facnum'];
		$this->idclient      = $mif['idclient'];
		$this->Tarif['spec'] = $mif['ts'];
		$this->MontNfrais    = ($mif['montantfacture'] > 0)?$mif['montantfacture']:$mif['fr1'];
		$this->FraisP        = $mif['montantpaye'];
		$this->brk           = $mif['brk'];
		$this->date          = $mif['vipdate'];

	#### Get les tarifs #############################################################################################################################
		$gettarif = new db();

		if ($this->facnum > 0) {
			## Tarifs stockés sur la facture
			$gettarif->inline("SELECT tarif01 as tvheure05, tarif02 as tvheure6, tarif03 as tvnight, tarif04 as tvkm, tarif05 as tv150, fraisdimona FROM `facture` WHERE `idfac` = '".$this->facnum."'");
		} else {
			## Tarifs Client
			$gettarif->inline("SELECT `tvheure05`,`tvheure6`,`tvnight`,`tvkm`,`tv150`, `fraisdimona` FROM `client` WHERE `idclient` = '".$this->idclient."'");
		}

		$trfs = mysql_fetch_array($gettarif->result);

		$this->Tarif['high'] 			= $trfs['tvheure05'];
		$this->Tarif['low'] 			= $trfs['tvheure6'];
		$this->Tarif['night'] 			= $trfs['tvnight'];
		$this->Tarif['km'] 				= $trfs['tvkm'];
		$this->Tarif['150'] 			= $trfs['tv150'];
		$this->Tarif['fraisdimona'] 	= $trfs['fraisdimona'];

	#### Passage des heures au format numérique #######

		if (empty($mif['vipin'])) $mif['vipin'] = '00:00:00';
		$h1 = explode (':', $mif['vipin']);
                if (!isset($h1[1])) $h1[1] = 0 ;

		$mif['vipin'] = $h1[0] + ($h1[1] / 60);

		if (empty($mif['vipout'])) $mif['vipout'] = '00:00:00';
		$h2 = explode (':', $mif['vipout']);
                if (!isset($h2[1])) $h2[1] = 0 ;

		$mif['vipout'] = $h2[0] + ($h2[1] / 60);

	#### Calcul des heures Réelle ###################
		if ($mif['vipin'] <= $mif['vipout']) {
			$this->htot = $mif['vipout'] - $mif['vipin'];
		} else {
			$this->htot = $mif['vipout'] + 24 - $mif['vipin'];
		}

	#### Calcul des heures prestées ###################
			$this->hprest = $this->htot - $this->brk;

	#### Calcul des heures de nuit ####################
		if ($this->Tarif['spec'] > 0) {
			$this->hnight = 0;
		} else {
			$this->hnight = $mif['night'];
		}

	#### Calcul des heures 150 % ####################
		if ($this->Tarif['spec'] > 0) {
			$this->h150 = 0;
		} else {
			$this->h150 = $mif['h150'];
		}

	#### Heures High ##################################

		$hhighlow = $this->hprest - $mif['night'] - $mif['h150'];

		if ($this->Tarif['spec'] > 0) {
			$this->hhigh = 0;
		} else {
			# Minimum d'heures facturées

			if (
					($this->date >= '2011-01-01')
				 && ($this->date <  '2011-06-01')
				 && ($this->hprest <= $this->forfait2011)
				 && ($this->hprest > 0)
			 ) {
				$this->hhigh = $this->forfait2011 - $mif['night'] - $mif['h150'];
				$this->hprest = 3;
			} elseif (
					(
						($this->date <  '2011-01-01')
					 || ($this->date >= '2011-06-01')
					)
				 && ($this->hprest <= $this->forfait2010)
				 && ($this->hprest > 0)
			) {
				$this->hhigh = $this->forfait2010 - $mif['night'] - $mif['h150'];
				$this->hprest = ($this->date >= '2011-06-01')?3:4;
			} else {
				if (($this->hprest < 6) and ($this->hprest > 0)) {
					$this->hhigh = $hhighlow;
				} else {
					$this->hhigh = 0;
				}
			}
		}

	#### Heures Low ###################################
		if ($this->Tarif['spec'] > 0) {
			$this->hlow = 0;
		} else {
			if ($this->hprest >= 6) {
				$this->hlow = $hhighlow;
			} else {
				$this->hlow = 0;
			}
		}

	#### Heures Tarif Special ##########################
		if ($this->Tarif['spec'] > 0) {
			if ($this->hprest <= $mif['fts']) {
				$this->hspec = $mif['fts'];
			} else {
				$this->hspec = $this->hprest;
			}
		} else {
			$this->hspec = 0;
		}

	#### Calcul des heures si 200 % ####################
		if ($mif['h200'] == 1) {
			$this->htot 	*= 2;
			$this->hprest 	*= 2;
			$this->hhigh 	*= 2;
			$this->hlow 	*= 2;
			$this->hnight 	*= 2;
			$this->h150 	*= 2;
			$this->hspec 	*= 2;
		}

	#### Total Heures ##################################
		$this->thfact = $this->hhigh + $this->hlow + $this->hnight + $this->h150 + $this->hspec;

	#### Calcul des Frais DIMONA ###################
		if ($this->Tarif['fraisdimona'] == 'oui') {
			$tauxDimona = array(
								'2000-01-01' => 0.251,
								'2009-06-01' => 0.271,
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

			if (($mif['h200'] == 1) and ($this->date >= "2009-08-10")) { # <= pour ne pas modifier les anciennes factures
				$this->HeuresDimona = $this->thfact / 2;
			} else {
				$this->HeuresDimona = $this->thfact;
			}
			$this->FraisDimona = $this->HeuresDimona * $this->tarifdimona;

		}

	#### Total Heures Payées ##################################

		if ($mif['h200'] == 1) {
			$this->thpaye = ($this->thfact / 2) + $mif['ajust'];
			$this->thp150 = 0;
			$this->thp100 = 0;
			$this->thp200 = $this->thpaye;
		} else {
			$this->thpaye = $this->thfact + $mif['ajust'];
			$this->thp150 = $mif['night'] + $this->h150;
			$this->thp100 = $this->thpaye - $this->thp150;
			$this->thp200 = 0;
		}


		# KM et forfait
		$this->kipay  = $mif['vkm'];
		$this->kiforf = $mif['vfkm'];

	##### FRAIS Payement
		# Frais 437 : Frais déplacement (KM * 0,2Û + forfait)
		$tarifkmpay_list = array(
							'2000-01-01' => 0.1983,
							'2011-09-01' => 0.23,
						);
		krsort($tarifkmpay_list);
		foreach ($tarifkmpay_list as $date => $taux) {
			if ($this->date >= $date) {
				$this->tarifkmpay = $taux;
				break;
			}
		}

		$this->frais437 = ($this->kipay * $this->tarifkmpay) + $this->kiforf;

		# Frais 441 : Reste des frais (remboursement ts frais)
		$datebut = implode("", explode("-", $date));

		if ($datebut > '20060931') {
			$this->frais441 = 0; ## Plus de frais payés a partir du 1er Octobre 2006
		} else {
			$this->frais441 = $mif['vdisp'] + $mif['vfr1'] + $mif['vfrpeople'];
		}

		# Frais 433 : Frais Repas
		$this->frais433 = $mif['vcat'];

		$this->fraispay = $this->frais433 + $this->frais437 + $this->frais441;

	##### FRAIS TABLEAU FACTURATION COMPARATIF Payement

		$this->fraiscomp = $mif['vfr1'] + $mif['vfrpeople'];

	##### FRAIS TABLEAU FACTURATION
	#### Calcul des postes Facturation
		$this->MontPrest = ($this->hlow * $this->Tarif['low']) + ($this->hhigh * $this->Tarif['high']) + ($this->hnight * $this->Tarif['night']) + ($this->h150 * $this->Tarif['150']) + ($this->hspec * $this->Tarif['spec']);
		$this->MontDepl  = ($mif['km'] * $this->Tarif['km']) + $mif['fkm'];
		$this->MontLoc   = $mif['unif'] + $mif['net'] + $mif['loc1'] + $mif['loc2'] ;
		$this->MontFrais = $mif['cat'] + $mif['disp'] + $this->MontNfrais;

		$this->MontTotal = $this->MontPrest + $this->MontDepl + $this->MontLoc + $this->MontFrais + $this->FraisDimona ;
	}
}

?>