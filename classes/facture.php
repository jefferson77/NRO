<?php
## dépendances
include NIVO."merch/easclients.php";

## envoie la date de facturation, si on est avant le 12 du mois, la fac passe sur le moins précédent
function facdate() {
	if ((date("m") == 11) and (date("d") >= 27)) { ## pour facturer le moins possible en novembre a cause du double TVA
		return date("Y-m-01", strtotime("+5 days"));
	} elseif ((date("d") < 12) and (date("m") != 12)) { ## pour ne pas facturer les 12 premiers jours du mois. (on place tout sur le mois précédent)
		return date("Y-m-t", strtotime("-14 days"));
	} else {
		return date("Y-m-d");
	}
}

function rubriqueTVA ($astva, $tva, $pays, $kind) {
	$rubhortva = 0;
	## si étranger sans num de TVA rentré
	switch ($astva) {
		case "3": ## Exonéré
			$rubhortva = '5';
		break;

		case "4": ## Assujeti
		case "7": ## Particulier sans num de TVA
			$rubhortva = '5';
		break;

		case "5": ## Etranger CEE
			if ((!empty($tva)) and ($pays != 'Belgique')) {
				$rubhortva = ($kind == 'NCV')?8:14;
			} else {
				$rubhortva = '5';
			}
		break;

		case "6": ## Etranger Hors CEE
			if ($pays != 'Belgique') {
				$rubhortva = '7';
			} else {
				$rubhortva = '5';
			}
		break;

		case "8": ## Communauté Européenne
			$rubhortva = '7';
		break;
	}

	return $rubhortva;
}

function tauxTVA ($rubhortva) {
	$tauxTVA = 0.21;
	if ($rubhortva != '5') {
		$tauxTVA = 0;
	}

	return $tauxTVA;
}

class facture
{
	# Config Values

		## défini dans la recherche fa c
	var $id = '';
	var $idclient = '';
	var $frdate = '';
	var $langue = '';
	var $societe = '';
	var $adresse = '';
	var $leyear = '';
	var $factu = '';
	var $mode = '';

		## VIP / Anim / Merch
	var $idcofficer = '';
	var $DetHeure = '';

		## VIP
	var $idjob = '';

		## Post
	var $nom = '';
	var $dept = '';
	var $qual = '';

	var $horizfile = '';
	var $horizsecteur = '';
	var $CodeHoriz = '';
	var $HorizDate = '';

	var $Detail = array();

	var $DetFKm = '';
	var $DetHhigh = '';
	var $DetHlow = '';
	var $DetHnight = '';
	var $DetHspec = '';
	var $DetKm = '';

	var $ftva = '';
	var $intitule = '';

	var $MontDepl = '';
	var $MontFrais = '';
	var $MontDimona = 0;
	var $HeuresDimona = 0;
	var $MontLivraison = '';
	var $MontHTVA = '';
	var $MontLoc = '';
	var $MontPrest = '';
	var $MontTTC = '';
	var $MontTVA = '';

	var $ForfNbr;

	var $nomagent = '';
	var $notefrais = '';
	var $noteloca = '';
	var $pays = '';
	var $agent = '';
	var $reference = '';
	var $boncommande = '';

	var $Tarif = array();
	var $CompteHoriz = array();

	var $kind = '';
	var $modefac = '';

	var $facbase = '';
	var $facnum = '';
	var $astva = '';
	var $rubhortva = '';

	var $horizetat = '';

	function facture ($id, $kind = 'FAC') {

		global $DB, $gbclients;

		if ($kind == 'VEN') $kind = 'FAC'; # Adapte la dénomination Horizon a la classe Factures

		$this->kind = $kind;

		###### Modifs Préfacture ##########
		switch ($this->kind) {
			case "FAC":
				$this->facbase = 'facture';
				$this->facnum = 'facnum';
				$this->mode = 'FACTURE';
			break;

			case "PREVI":
				$this->facbase = 'tempfacturevip';
				$this->facnum = 'facnumtemp';
			break;

			case "PREAN":
				$this->facbase = 'tempfactureanim';
				$this->facnum = 'facnumtemp';
				$this->mode = 'PREFAC';
			break;

			case "PREME":
				$this->facbase = 'tempfacturemerch';
				$this->facnum = 'facnumtemp';
			break;

			case "TEST":
				$this->facbase = 'facturetest';
				$this->facnum = 'facnum';
			break;

			case "NCV": # dénomination des notes de crédit dans Horizon
				$this->facbase = 'credit';
				$this->facnum = 'facnum';
			break;

			case "OFFVI":
				$this->facnum = "idvipjob";
			break;
		}

	#### Infos Facture et Client/Officer
		$this->id = $id;  ### ID Facture

		$facmodes = array('FAC', 'PREVI', 'PREAN', 'PREME', 'TEST');
		if (in_array($this->kind, $facmodes)) $fields = "f.tarif01,  f.tarif02, f.tarif03, f.tarif04, f.tarif05, f.tarif06, f.tarif07, f.modefac, f.idagent, ";

		$facmodes2 = array('FAC', 'PREVI');
		if(in_array($this->kind, $facmodes2)) $fields .= "f.hforfait as fhforfait, ";


		if ($this->kind == 'NCV') {
			$infoa = $DB->getRow("SELECT
				nc.idfac, nc.datefac, nc.intitule, f.horizon,
				f.secteur, f.idclient, f.idcofficer,
				SUM(cd.montant) AS montant,
				c.societe, c.adresse, c.cp, c.ville, c.pays, c.tva, c.codetva, c.astva, c.codecompta,
					c.tvheure05 ,c.tvheure6 ,c.tvnight ,c.tvkm, c.tvforfait ,c.tv150,
					c.taheure, c.takm, c.taforfait, c.taforfaitkm, c.tastand, c.hforfait, c.facturation ,
					c.tmheure, c.tmkm, c.tm150, c.fraisdimona,
				o.onom , o.oprenom, o.qualite, o.langue, o.departement
				FROM ".$this->facbase." nc
					LEFT JOIN facture f ON nc.facref = f.idfac
					LEFT JOIN creditdetail cd ON nc.idfac = cd.idfac
					LEFT JOIN client c ON f.idclient = c.idclient
					LEFT JOIN cofficer o ON f.idcofficer = o.idcofficer
				WHERE nc.idfac = ".$this->id);
		} else {
			$infoa = $DB->getRow("SELECT
				f.idfac, f.datefac, f.intitule, f.secteur, f.idclient, f.idcofficer, f.montant, f.horizon, f.po, f.fraisdimona as ffraisdimona, ".$fields."
				c.societe, c.adresse, c.cp, c.ville, c.pays, c.tva, c.codetva, c.astva, c.codecompta,
					c.tvheure05 ,c.tvheure6 ,c.tvnight ,c.tvkm, c.tvforfait ,c.tv150,
					c.taheure, c.takm, c.taforfait, c.taforfaitkm, c.tastand, c.hforfait, c.facturation ,
					c.tmheure, c.tmkm, c.tm150, c.fraisdimona,
				o.onom , o.oprenom, o.qualite, o.langue, o.departement
				FROM ".$this->facbase." f
				LEFT JOIN client c ON f.idclient = c.idclient
				LEFT JOIN cofficer o ON f.idcofficer = o.idcofficer
				WHERE idfac = ".$this->id);
		}

		$this->horizetat = $infoa['horizon']; # etat d'exporation dans Horizon

		if (($this->kind == 'FAC') or ($this->kind == 'TEST')) {
			$this->modefac = $infoa['modefac'];
		} elseif ($this->kind == 'NCV') {
			$this->modefac = 'NC';
		} else {
			$this->modefac = 'A';
		}

		$this->astva = $infoa['astva'];
		$this->rubhortva = rubriqueTVA($this->astva, $infoa['tva'], $infoa['pays'], $this->kind);

	# > #### Vérification de l'ID officer ############################################################
		if ($infoa['idcofficer'] == 0) {
			$idofficer = $this->updofficer($this->id, $infoa['secteur'], $this->modefac);
		} else {
			$this->nom = $infoa['oprenom']." ".$infoa['onom'];
			$this->dept = $infoa['departement'];
			$this->langue = strtoupper($infoa['langue']);
			$this->qual = $this->qualite ($infoa['qualite']);
		}
	# < #### Vérification de l'ID officer ############################################################

	################################################################################################################################################
	# > #### Vérification des Tarifs Clients sur la facture ########################################################################################
	################################################################################################################################################

		$maxtarif = $infoa['tarif01'] + $infoa['tarif02'] + $infoa['tarif03'] + $infoa['tarif04'] + $infoa['tarif05'] + $infoa['tarif06'] + $infoa['tarif07'];

		if (($maxtarif == 0) and ($infoa['idclient'] >= 1)) {

			switch ($infoa['secteur']) {
				case "1": # VIP
					$jobhforfait = $DB->getOne("SELECT forfait FROM vipjob WHERE ".$this->facnum." = ".$this->id);
					$fhforfait = ($jobhforfait == 'Y')?2:1;

					$ssql = "UPDATE `".$this->facbase."` SET
					`tarif01` = '".$infoa['tvheure05']."',
					`tarif02` = '".$infoa['tvheure6']."',
					`tarif03` = '".$infoa['tvnight']."',
					`tarif04` = '".$infoa['tvkm']."',
					`tarif05` = '".$infoa['tv150']."',
					`tarif06` = '".$infoa['tvforfait']."',
					`hforfait` = '".$fhforfait."',
					`fraisdimona` = '".$infoa['fraisdimona']."'
					WHERE `idfac` = '".$this->id."'"; ## VIP

					$this->Tarif['high'] 	= $infoa['tvheure05'];
					$this->Tarif['low'] 	= $infoa['tvheure6'];
					$this->Tarif['night'] 	= $infoa['tvnight'];
					$this->Tarif['150'] 	= $infoa['tv150'];
					$this->Tarif['km'] 		= $infoa['tvkm'];
					$this->Tarif['forfait'] = $infoa['tvforfait'];
					$this->Tarif['hforfait'] = $fhforfait;
					$this->Tarif['fraisdimona'] = $infoa['fraisdimona'];
				break;

				case "2": # ANIM

					$ssql = "UPDATE `".$this->facbase."` SET
					`tarif01` = '".$infoa['taheure']."',
					`tarif02` = '".$infoa['takm']."',
					`tarif03` = '".$infoa['taforfait']."',
					`tarif04` = '".$infoa['taforfaitkm']."',
					`tarif05` = '".$infoa['tastand']."',
					`tarif06` = '',
					`tarif07` = '',
					`hforfait` = '".$infoa['hforfait']."',
					`fraisdimona` = '".$infoa['fraisdimona']."'
					WHERE `idfac` = '".$this->id."'"; ## ANIM

					$this->Tarif['heure'] 	= $infoa['taheure'];
					$this->Tarif['km'] 		= $infoa['takm'];
					$this->Tarif['forf'] 	= $infoa['taforfait'];
					$this->Tarif['forfkm'] 	= $infoa['taforfaitkm'];
					$this->Tarif['stand'] 	= $infoa['tastand'];
					$this->Tarif['hforfait'] = $infoa['hforfait'];
					$this->Tarif['fraisdimona'] = $infoa['fraisdimona'];
				break;

				case "3": # MERCH
				case "4": # EAS

					$ssql = "UPDATE `".$this->facbase."` SET
					`tarif01` = '".$infoa['tmheure']."',
					`tarif02` = '".$infoa['tmkm']."',
					`tarif03` = '".$infoa['tm150']."',
					`hforfait` = '".$infoa['hforfait']."',
					`fraisdimona` = '".$infoa['fraisdimona']."'
					WHERE `idfac` = '".$this->id."'"; ## MERCH

					$this->Tarif['heure'] 	= $infoa['tmheure'];
					$this->Tarif['km'] 		= $infoa['tmkm'];
					$this->Tarif['hforfait'] = $infoa['hforfait'];
					$this->Tarif['fraisdimona'] = $infoa['fraisdimona'];
				break;

			}

			if ($this->kind == 'FAC') {
				$majtarif = new db();
				$majtarif->inline($ssql);
			}


		} else {
			switch ($infoa['secteur']) {
				case "1": # VIP
					$this->Tarif['high'] 	= $infoa['tarif01'];
					$this->Tarif['low'] 	= $infoa['tarif02'];
					$this->Tarif['night'] 	= $infoa['tarif03'];
					$this->Tarif['km'] 		= $infoa['tarif04'];
					$this->Tarif['150'] 	= $infoa['tarif05'];
					$this->Tarif['forfait'] = $infoa['tarif06'];
					$this->Tarif['hforfait'] = $infoa['fhforfait'];
					$this->Tarif['fraisdimona'] = $infoa['ffraisdimona'];
				break;

				case "2": # ANIM
					$this->Tarif['heure'] 	= $infoa['tarif01'];
					$this->Tarif['km'] 		= $infoa['tarif02'];
					$this->Tarif['forf'] 	= $infoa['tarif03'];
					$this->Tarif['forfkm'] 	= $infoa['tarif04'];
					$this->Tarif['stand'] 	= $infoa['tarif05'];
					$this->Tarif['hforfait'] = $infoa['fhforfait'];
					$this->Tarif['fraisdimona'] = $infoa['ffraisdimona'];
				break;

				case "3": # MERCH
				case "4": # EAS
					$this->Tarif['heure'] 	= $infoa['tarif01'];
					$this->Tarif['km'] 		= $infoa['tarif02'];
					$this->Tarif['150'] 	= $infoa['tarif03'];
					$this->Tarif['hforfait'] = $infoa['fhforfait'];
					$this->Tarif['fraisdimona'] = $infoa['ffraisdimona'];
				break;
			}
		}


	################################################################################################################################################
	# < #### Vérification des Tarifs Clients sur la facture ########################################################################################
	################################################################################################################################################

		$this->idclient = $infoa['idclient'];
		$this->societe = $infoa['societe'];
		$this->adresse = $infoa['adresse']."\r".$infoa['cp']." ".$infoa['ville'];
		$this->ftva = $infoa['codetva']." ".$infoa['tva'];
		$this->CodeHoriz = $infoa['codecompta'];
		$this->pays = ($infoa['pays'] != 'Belgique')?$infoa['pays']:'';

		$this->factu = $infoa['facturation'];

	################### Date ########################
		if ((!empty($infoa['datefac'])) and ($infoa['datefac'] != '0000-00-00')) {
			$timestamp = strtotime($infoa['datefac']);
		} else {
			$timestamp = strtotime("now");
		}

		$this->datefac = date("Y-m-d", $timestamp);
		$this->leyear = date("Y", $timestamp);
		$this->horizfile = date("Ym", $timestamp);
		$this->HorizDate = date("Ymd", $timestamp);

	################### Langue ######################

	################### Client ######################

		switch ($this->modefac) {

##############################################
### Factures Automatiques ########################################################################
##############################################

			case "A":

				switch ($infoa['secteur']) {
	##############################################################################################################################################
	### VIP ######################################################################################################################################
					case "1":

						$this->horizsecteur = "1";

						############# Infos Job ######################################
						$infos = $DB->getRow("SELECT
						j.idvipjob, j.reference, j.noteprest, j.notedeplac, j.noteloca, j.notefrais, j.etat, j.facnum, j.bondecommande, j.forfait,
						a.nom AS nomagent, a.prenom AS prenomagent
						FROM vipjob j
						LEFT JOIN agent a ON j.idagent  = a.idagent
						WHERE j.".$this->facnum." = ".$this->id);

					## id's
						$this->idjob 		= $infos['idvipjob'];
					## infos
						$this->intitule 	= 'VIP '.$infos['idvipjob'];
						$this->reference 	= ftxtpdf($infos['reference']);
						if (!empty($infos['bondecommande'])) $this->boncommande = $infos['bondecommande'];
						$this->noteloca 	= ftxtpdf($infos['noteloca']);
						$this->notefrais 	= ftxtpdf($infos['notefrais'])."\r";
						$this->agent 	= $infos['prenomagent'].' '.$infos['nomagent'];
					## Tarifs #

						# Recherche des missions en devis
						$missions = $DB->getArray("SELECT idvip, ts, km, fkm, unif, net, loc1, loc2, cat, disp, h200 FROM `vipmission` WHERE `idvipjob` = ".$infos['idvipjob']);

						foreach ($missions as $row) {

							$fich = new corevip ($row['idvip']);

							## Forfaits

							if ($this->Tarif['hforfait'] == 2) { # forfaitaire
								if ($row['h200'] == 1) {
									$this->MontPrest += $this->Tarif['forfait'] * 2;
									$this->ForfNbr += 2;
									$this->DetHeure += 16; # On compte 8 heures par forfait
								} else {
									$this->MontPrest += $this->Tarif['forfait'];
									$this->ForfNbr++;
									$this->DetHeure += 8; # On compte 8 heures par forfait
								}
							} else { # horaire
								$this->MontPrest += ($fich->hhigh * $this->Tarif['high']) + ($fich->hlow * $this->Tarif['low']) + ($fich->hnight * $this->Tarif['night']) + ($fich->h150 * $this->Tarif['150']) + ($fich->hspec * $row['ts']);
								$this->DetHeure += $fich->hhigh + $fich->hlow + $fich->hnight + $fich->h150 + $fich->hspec;
							}


							$this->MontDepl += ($row['km'] * $this->Tarif['km']) + $row['fkm'];

							$this->MontUnif += $row['unif'] + $row['net'];
							$this->MontLocation +=  + $row['loc1'] + $row['loc2'] ;

							$this->MontCat += $row['cat'];
							$this->MontDisp += $row['disp'];
							$this->MontFr += $fich->MontNfrais;
							$this->MontDimona += $fich->FraisDimona;
							$this->HeuresDimona += $fich->HeuresDimona;

							$this->DetHhigh += $fich->hhigh;
							$this->DetHlow += $fich->hlow;
							$this->DetHnight += $fich->hnight;
							$this->DetHspec += $fich->hspec;
							$this->DetH150 += $fich->h150;
							$this->DetH200 += $row['h200'];

							$this->DetKm += $row['km'];
							$this->DetFKm += $row['fkm'];
						}

						# Totaux Location
						$this->MontUnif		= round($this->MontUnif, 2);
						$this->MontLocation	= round($this->MontLocation, 2);

						$this->MontLoc = $this->MontUnif + $this->MontLocation ;

						# Totaux Frais
						$this->MontCat	= round($this->MontCat, 2);
						$this->MontDisp	= round($this->MontDisp, 2);
						$this->MontFr	= round($this->MontFr, 2);
						$this->MontDimona	= round($this->MontDimona, 2);

						$this->MontFrais = $this->MontCat + $this->MontDisp + $this->MontFr + $this->MontDimona ;

						# Total HTVA
						$this->MontPrest	= round($this->MontPrest, 2);
						$this->MontDepl		= round($this->MontDepl, 2);
						$this->MontLoc		= round($this->MontLoc, 2);
						$this->MontFrais	= round($this->MontFrais, 2);

						$this->MontHTVA = $this->MontPrest + $this->MontDepl + $this->MontLoc + $this->MontFrais;

						$this->Detail['prestations'] = '';
						$this->Detail['deplacements'] = '';

						switch ($this->langue) {
								case "FR":
									# Detail des prestations
									if ($this->Tarif['hforfait'] == 2) { # forfaitaire
											$this->Detail['prestations'] .= $this->ForfNbr." forfaits x ".fpeuro($this->Tarif['forfait'])."\r";

									} else { # horaire
										if ($this->DetHhigh > 0) {
											$this->Detail['prestations'] .= fnbr($this->DetHhigh)." heures au tarif de base (- de 6h) x ".fpeuro($this->Tarif['high'])."\r";
										}
										if ($this->DetHlow > 0) {
											$this->Detail['prestations'] .= fnbr($this->DetHlow)." heures au tarif préférentiel (6h et +) x ".fpeuro($this->Tarif['low'])."\r";
										}
										if ($this->DetHnight > 0) {
											$this->Detail['prestations'] .= fnbr($this->DetHnight)." heures au tarif de nuit x ".fpeuro($this->Tarif['night'])."\r";
										}
										if ($this->DetH150 > 0) {
											$this->Detail['prestations'] .= fnbr($this->DetH150)." heures supplémentaires x ".fpeuro($this->Tarif['150'])."\r";
										}
										if ($this->DetHspec > 0) {
											$this->Detail['prestations'] .= fnbr($this->DetHspec)." heures au tarif spécial\r";
										}
										if ($this->DetH200 > 0) {
											$this->Detail['prestations'] .= "! ".fnbr($this->DetH200)." prestations jour férié ou 2ème dimanche (200%)";
										}
									}

									# Detail des déplacements
										if ($this->DetKm > 0) {
											$this->Detail['deplacements'] .= fnbr($this->DetKm)." km au tarif ".fpeuro($this->Tarif['km'])."\r";
										}
										if ($this->DetFKm > 0) {
											$this->Detail['deplacements'] .= fpeuro($this->DetFKm)." de forfait.";
										}

									# Detail des Frais
										if ($this->MontCat > 0) {
											$this->notefrais .= fnbr($this->MontCat)." Euros de Catering\r";
										}

										if ($this->MontDisp > 0) {
											$this->notefrais .= fnbr($this->MontDisp)." Euros de Dispatching\r";
										}

										if ($this->MontFr > 0) {
											$this->notefrais .= fnbr($this->MontFr)." Euros de Frais\r";
										}

										if ($this->MontDimona > 0) {
											$this->notefrais .= fnbr($this->HeuresDimona)." x ".fnbr($fich->tarifdimona)." Euros de frais DIMONA\r";
										}

								break;
								case "NL":
									# Detail des prestations
									if ($this->Tarif['hforfait'] == 2) { # forfaitaire
											$this->Detail['prestations'] .= $this->ForfNbr." forfaits x ".fpeuro($this->Tarif['forfait'])."\r";

									} else { # horaire
										if ($this->DetHhigh > 0) {
											$this->Detail['prestations'] .= fnbr($this->DetHhigh)." uren aan basis tarief (- dan 6u) x ".fpeuro($this->Tarif['high'])."\r";
										}
										if ($this->DetHlow > 0) {
											$this->Detail['prestations'] .= fnbr($this->DetHlow)." uren aan voordelig tarief (6u en +) x ".fpeuro($this->Tarif['low'])."\r";
										}
										if ($this->DetHnight > 0) {
											$this->Detail['prestations'] .= fnbr($this->DetHnight)." uren aan nachttarief x ".fpeuro($this->Tarif['night'])."\r";
										}
										if ($this->DetH150 > 0) {
											$this->Detail['prestations'] .= fnbr($this->DetH150)." extra uren x ".fpeuro($this->Tarif['150'])."\r";
										}
										if ($this->DetHspec > 0) {
											$this->Detail['prestations'] .= fnbr($this->DetHspec)." uren aan speciaal tarief\r";
										}
										if ($this->DetH200 > 0) {
											$this->Detail['prestations'] .= "! ".fnbr($this->DetH200)." prestatie(s) tijdens feestdag of 2de zondag (200%)";
										}

									# Detail des déplacement
										if ($this->DetKm > 0) {
											$this->Detail['deplacements'] .= fnbr($this->DetKm)." km aan tarief ".fpeuro($this->Tarif['km'])."\r";
										}
										if ($this->DetFKm > 0) {
											$this->Detail['deplacements'] .= fpeuro($this->DetFKm)." forfaitaire verplaatsingskosten.";
										}
									}

									# Detail des Frais
										if ($this->MontCat > 0) {
											$this->notefrais .= fnbr($this->MontCat)." Euros Catering\r";
										}

										if ($this->MontDisp > 0) {
											$this->notefrais .= fnbr($this->MontDisp)." Euros Dispatching\r";
										}

										if ($this->MontFr > 0) {
											$this->notefrais .= fnbr($this->MontFr)." Euros Onkosten\r";
										}

										if ($this->MontDimona > 0) {
											$this->notefrais .= fnbr($this->HeuresDimona)." x ".fnbr($fich->tarifdimona)." Euros DIMONA kosten\r";
										}
								break;
						}

						### Notes
						if (!empty($infos['noteprest']))  $this->Detail['prestations'] .=  $infos['noteprest'];
						if (!empty($infos['notedeplac'])) $this->Detail['deplacements'] .= $infos['notedeplac'];

						### Codes Compta Horizon
						$this->CompteHoriz['700120'] = $this->MontPrest;	# Prestations VIP
						$this->CompteHoriz['700220'] = $this->MontDepl;		# Déplacements VIP
						$this->CompteHoriz['700320'] = $this->MontUnif;		# Refacturation Uniformes  VIP
						$this->CompteHoriz['700420'] = $this->MontCat;		# Catering  VIP
						$this->CompteHoriz['700920'] = $this->MontFr;		# Frais remboursés  VIP
						$this->CompteHoriz['700933'] = $this->MontDimona;	# Frais DIMONA  VIP
						$this->CompteHoriz['700820'] = $this->MontLocation;	# Refactu Location Vehicule  VIP
						$this->CompteHoriz['700960'] = $this->MontDisp;		# Coursier  VIP

					break;
	###############################################################################################################################################
	### ANIM ######################################################################################################################################
	###############################################################################################################################################
					case "2":
						$infac = new facanim($this->mode, $this->id);

						# Nombre d'Heures
						$this->DetHeure = $infac->nbrheures;

						$this->horizsecteur = "2";

						## Infos Anims #########################
						$DB->inline("SELECT
							an.datem,
							a.nom, a.prenom,
							j.boncommande
							FROM animation an
							LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
							LEFT JOIN agent a ON j.idagent = a.idagent
							WHERE an.".$this->facnum." = ".$this->id."
							ORDER BY an.datem");

						$i = 0;

						$zdatem = 'nodate';

						while ($row = mysql_fetch_array($DB->result)) {
							if (($zdatem == 'nodate') and ($row['datem'] != '0000-00-00')) $zdatem = $row['datem']; ## utilise pour la date

							$i++;

							$this->agent 	= $row['prenom'].' '.$row['nom'];

							if (!empty($row['boncommande'])) $this->boncommande = $row['boncommande'];
                    	}

					####### Sujet Facture #######
					if ($zdatem == '2007-12-31') $zdatem = '2007-12-28'; # sinon le 31/12/2007 valse en semaine 1 !!! bouuuhh le laid code ! pas bien nico, pas bien
					list($yyyy, $mm, $dd) = explode('-',$zdatem);

					if ($this->langue == 'NL') {
						$tmois = "Maand";
						$tsemaine = "Week";
						setlocale(LC_TIME, 'nl_NL');

					} else {
						$tmois = "Mois";
						$tsemaine = "Semaine";
						setlocale(LC_TIME, 'fr_FR');
					}

					if ($this->factu == '3') {
						$this->intitule = "ANIM $tmois ".utf8_encode(strftime('%B %Y', mktime(0,0,0,$mm,$dd,$yyyy)));
					} else {
						$lasem = date('W', strtotime($zdatem));
						if (($lasem == '01') and (date('m', strtotime($zdatem)) == 12)) $lasem = 53;
						$this->intitule = "ANIM $tsemaine ".$lasem." ".date('Y', strtotime($zdatem));
					}

					$this->MontStand = $this->DetStand * $this->Tarif['stand'];

					$this->MontLivraison	= round($this->MontLivraison, 2);
					$this->MontGobelets		= round($this->MontGobelets, 2);
					$this->MontServiette	= round($this->MontServiette, 2);
					$this->MontFour			= round($this->MontFour, 2);
					$this->MontCuredent		= round($this->MontCuredent, 2);
					$this->MontCuillere		= round($this->MontCuillere, 2);
					$this->MontRechaud 		= round($this->MontRechaud, 2);
					$this->MontAutre		= round($this->MontAutre, 2);

					$this->MontDivers = $this->MontLivraison + $this->MontGobelets + $this->MontServiette + $this->MontFour + $this->MontCuredent + $this->MontCuillere + $this->MontRechaud + $this->MontAutre;

					$this->MontBriefing = $this->DetBriefing;

					$this->MontPrest	= round($this->MontPrest, 2);
					$this->MontDepl		= round($this->MontDepl, 2);
					$this->MontStand	= round($this->MontStand, 2);
					$this->MontFrais	= round($this->MontFrais, 2);
					$this->MontDivers	= round($this->MontDivers, 2);
					$this->MontBriefing = round($this->MontBriefing, 2);
					$this->MontForfaitH = round($this->MontForfaitH, 2);

					$this->MontHTVA = $infac->MontHTVA;

					# Détail pour affichage droite
					if ($this->MontPrest != 0) {	$this->Detail['prestations'] = $this->DetHeure.' '.$phrase[30]. ' x '.fpeuro($this->Tarif['heure']);}
					if ($this->MontDepl != 0) {		$this->Detail['deplacements'] = $this->DetKm.' Km x '.fpeuro($this->Tarif['km']);}
					if ($this->MontStand != 0) {	$this->Detail['stand'] = $this->DetStand.' x '.fpeuro($this->Tarif['stand']);}
					if ($this->MontLivraisonfacture != 0) {	$this->Detail['livraisonfacture'] = $this->DetLivraisonfacture;}

					if ($this->MontBriefing != 0) {	$this->Detail['briefing'] = $this->DetBriefing.' x '.fpeuro($this->Tarif['briefing']);}

					if ($this->MontForfaitH != 0) {	$this->Detail['forfait'] = $i.' x '.fpeuro($this->Tarif['forf']);}

					### Comptes Horizon ###
					$this->CompteHoriz['700100'] = $infac->MontPrest + $infac->MontForfait;			# Prestations ANIM
					$this->CompteHoriz['700105'] = $infac->MontBriefingH;							# Briefing ANIM
					$this->CompteHoriz['700200'] = $infac->MontBriefingKM + $infac->MontDepl;		# Déplacements ANIM
					$this->CompteHoriz['700900'] = $infac->MontFrais + $infac->MontFraisAutre;		# Frais remboursés  ANIM
					$this->CompteHoriz['700550'] = $infac->MontStand;								# Location stand ANIM
					$this->CompteHoriz['700940'] = $infac->MontLivraison;							# Coursier ANIM
					$this->CompteHoriz['700931'] = $infac->MontDimona;								# Frais DIMONA  ANIM

					$this->CompteHoriz['700941'] = $infac->MontGobelets;							# Gobelets  ANIM
					$this->CompteHoriz['700942'] = $infac->MontServiette;							# Serviettes  ANIM
					$this->CompteHoriz['700943'] = $infac->MontFour;								# Four  ANIM
					$this->CompteHoriz['700944'] = $infac->MontCuredent;							# Cure dents  ANIM
					$this->CompteHoriz['700945'] = $infac->MontCuillere;							# Cuillere  ANIM
					$this->CompteHoriz['700946'] = $infac->MontRechaud;								# Rechaud  ANIM

					break;
	###############################################################################################################################################
	### MERCH / EAS ###############################################################################################################################
	###############################################################################################################################################
					case "3":
					case "4":
						$this->horizsecteur = "3";

 						## Infos Merch #########################
 						$lesmerchs = $DB->getArray("SELECT
 							me.idmerch, me.genre, me.ferie, me.kmfacture, me.kmpaye, me.datem, me.livraison, me.diversfrais, me.boncommande,
 							a.nom, a.prenom, s.societe as ssociete,
							nf.montantfacture
 							FROM merch me
 							LEFT JOIN agent a ON me.idagent = a.idagent
 							LEFT JOIN shop s ON me.idshop = s.idshop
							LEFT JOIN notefrais nf ON nf.secteur = 'ME' and me.idmerch = nf.idmission
 							WHERE me.".$this->facnum." = ".$this->id."
 							ORDER BY me.datem");

 						$i = 0;
						$easdesc = '';

 						foreach ($lesmerchs as $row) {

 							### mise à zero des valeurs pour EAS
 							if ($row['genre'] == 'EAS')
 							{
								if (in_array($this->idclient, $gbclients)) {
 									$easdesc = 'EAS GB '.date("m Y", strtotime($row['datem']));
								} else {
 									$easdesc = 'EAS '.$row['ssociete'].' '.date("m Y", strtotime($row['datem']));
								}
								$this->horizsecteur = "4";
								$row['ferie'] = 100 ;
								if ($this->idclient != 2316) $row['kmfacture'] = 0 ;  ## garde les KM pour champion (client 2316)
								$row['kmpaye'] = 0 ;
								$row['montantfacture'] = 0 ;
								$row['diversfrais'] = 0 ;
								$row['livraison'] = 0 ;
 							}
 							#/## mise à zero des valeurs pour EAS
 							$i++;

 							$merch = new coremerch($row['idmerch']);

 							$this->DetHeure += $merch->hprest;
 							$this->DetKm += $row['kmfacture'];
  							$this->MontFrais += $row['montantfacture'];
  							$this->MontDivers += $row['diversfrais'];
							$this->MontLivraison += $row['livraison'];
							$this->MontDimona += $merch->FraisDimona;
							$this->HeuresDimona += $merch->HeuresDimona;

 							$this->agent 	= $row['prenom'].' '.$row['nom'];

 							$zdatem = $row['datem'];

							if (!empty($row['boncommande'])) $this->boncommande = $row['boncommande'];


 						}

 						####### Sujet Facture #######
						list($yyyy, $mm, $dd) = explode('-',$zdatem);

	 					if ($this->langue == 'NL') {
	 						$tmois = "Maand";
	 						$tsemaine = "Week";
	 						setlocale(LC_TIME, 'nl_NL');
	 					} else {
	 						$tmois = "Mois";
	 						$tsemaine = "Semaine";
	 						setlocale(LC_TIME, 'fr_FR');
	 					}

						if (!empty($easdesc)) {
							$this->intitule = $easdesc;
						} elseif ($this->factu == '3') {
	 						$this->intitule = "MERCH $tmois ".utf8_encode(strftime('%B %Y', strtotime($zdatem)));
	 					} else {
							$lasem = date('W', strtotime($zdatem));
							if (($lasem == '01') and (date('m', strtotime($zdatem)) == 12)) $lasem = 53;
							$this->intitule = "MERCH $tsemaine ".$lasem." ".date('Y', strtotime($zdatem));
	 					}

	 					unset($easdesc);

						$this->MontPrest = $this->DetHeure * $this->Tarif['heure'];
						$this->MontDepl = $this->DetKm * $this->Tarif['km'];
						$this->MontDimona	= round($this->MontDimona, 2);
						$this->MontFrais += $this->MontDimona;

						## Arondi
						$this->MontPrest	= round($this->MontPrest, 2);
						$this->MontDepl		= round($this->MontDepl, 2);
						$this->MontFrais	= round($this->MontFrais, 2);
						$this->MontDivers	= round($this->MontDivers, 2);
						$this->MontLivraison	= round($this->MontLivraison, 2);

						$this->MontHTVA = $this->MontPrest + $this->MontDepl + $this->MontFrais + $this->MontDivers + $this->MontLivraison;

						switch ($this->langue) {
							case "FR":
								$detdimona = " Euros de frais DIMONA\r";
							break;
							case "NL":
								$detdimona = " Euros DIMONA kosten\r";
							break;
						}

						# Détail pour affichage droite
						if ($this->MontPrest != 0) {	$this->Detail['prestations'] = $this->DetHeure.' '.$phrase[30]. ' x '.fpeuro($this->Tarif['heure']);}
						if ($this->MontDepl != 0) {		$this->Detail['deplacements'] = $this->DetKm.' Km x '.fpeuro($this->Tarif['km']);}
						if ($this->MontDimona != 0) {	$this->Detail['frais'] = fnbr($this->HeuresDimona)." x ".fnbr($merch->tarifdimona).$detdimona;}

						### Comptes Horizon ###
						if ($this->horizsecteur == 4) {
							$this->CompteHoriz['700130'] = $this->MontPrest;		# Prestations EAS
							$this->CompteHoriz['700230'] = $this->MontDepl;			# Déplacements EAS
							$this->CompteHoriz['700930'] = $this->MontFrais + $this->MontDivers - $this->MontDimona;	# Frais remboursés  EAS
							$this->CompteHoriz['700970'] = $this->MontLivraison;	# Coursier  EAS
							$this->CompteHoriz['700934'] = $this->MontDimona;		# Frais DIMONA  EAS
						} else {
							$this->CompteHoriz['700110'] = $this->MontPrest;		# Prestations MERCH
							$this->CompteHoriz['700210'] = $this->MontDepl;			# Déplacements MERCH
							$this->CompteHoriz['700910'] = $this->MontFrais + $this->MontDivers - $this->MontDimona;	# Frais remboursés  MERCH
							$this->CompteHoriz['700950'] = $this->MontLivraison;	# Coursier  MERCH
							$this->CompteHoriz['700932'] = $this->MontDimona;		# Frais DIMONA  MERCH
						}
					break;
				}
			break;
##############################################
### Factures Manuelles ########################################################################
##############################################
			case "M":
				$this->horizsecteur = $infoa['secteur'];

				$detman = $DB->getArray("SELECT * FROM `facmanuel` WHERE `idfac` = '".$this->id."'");

				$this->intitule = $infoa['intitule'];
				$this->boncommande = $infoa['po'];

				$heurespostes = array(700100, 700110, 700120, 700130);

				foreach ($detman as $detm) {
					$detm['montant'] = round($detm['montant'], 2);

					if (in_array($detm['poste'], $heurespostes)) $this->DetHeure += $detm['units'];

					$this->CompteHoriz[$detm['poste']] += $detm['montant'];
					$this->MontHTVA += $detm['montant'];
				}

				## Infos Agent
				if (empty($infoa['idagent'])) $infoa['idagent'] = '20'; ## Françoise Lannoo par défaut

				$this->agent = $DB->getOne("SELECT CONCAT(prenom, ' ', nom) FROM agent WHERE idagent = ".$infoa['idagent']);

			break;
##############################################
### Notes de Credit ########################################################################
##############################################
			case "NC":
				$this->horizsecteur = $infoa['secteur'];
				$this->intitule = $infoa['intitule'];

				$detms = $DB->getArray("SELECT * FROM `creditdetail` WHERE `idfac` = '".$this->id."'");

				foreach ($detms as $detm) {
					$detm['montant'] = round($detm['montant'], 2);

					$this->CompteHoriz[$detm['poste']] += $detm['montant'];
					$this->MontHTVA += $detm['montant'];
				}

			break;
		}

		## Totaux TVA et TTC #############################################
		$this->MontTVA = round(tauxTVA($this->rubhortva) * $this->MontHTVA, 2);
		$this->MontTTC = $this->MontHTVA + $this->MontTVA;

		#### Comptes Horizons Globaux :
		$this->CompteHoriz['400000'] = $this->MontTTC;	# Total TTC
		$this->CompteHoriz['451000'] = $this->MontTVA;	# TVA

###############################################
# > # Mise a jour fiche Facture ###############
###############################################
		if (($this->kind == 'FAC') or ($this->kind == 'TEST')) {
			$usql = '';

			### Montant HTVA
			if (($infoa['montant'] != $this->MontHTVA) and ($this->MontHTVA > 0)) {
				if (!empty($usql)) $usql .= ' , ';
				$usql .= "`montant` = '".$this->MontHTVA."'";
			}

			### Intitule
			if (($infoa['intitule'] != $this->intitule) and ($this->intitule != '')) {
				if (!empty($usql)) $usql .= ' , ';
				$usql .= "`intitule` = '".$this->intitule."'";
			}

			### po (boncommande)
			if (($infoa['po'] != $this->boncommande) and !empty($this->boncommande)) {
				if (!empty($usql)) $usql .= ' , ';
				$usql .= "`po` = '".$this->boncommande."'";
			}

			### Stockage
			if (!empty($usql)) $DB->inline("UPDATE `".$this->facbase."` SET ".$usql." WHERE `idfac` = '".$this->id."' LIMIT 1");

		}

###############################################
# < # Mise a jour fiche Facture ###############
###############################################

	}

	function qualite ($qual) {
		if ($this->langue == 'NL') {
			if ($qual == 'Mr') return 'Meneer';
			if ($qual == 'Monsieur') return 'Meneer';
			if ($qual == 'Madame') return 'Mevrouw';
			if ($qual == 'Mlle') return 'Juffrouw';
		} else {
			if ($qual == 'Mr') return 'Monsieur';
			return $qual;
		}
	}

	## Mise a jour de l'idcofficer si manquant
	function updofficer ($idfac, $secteur, $modefac) {
		global $DB;

		switch ($modefac) {
			case "A":
				switch ($secteur) {
				## VIP
					case "1":
						$idcofficer = $DB->getOne("SELECT idcofficer FROM vipjob WHERE ".$this->facnum." = '".$idfac."'");
					break;
				## ANIM
					case "2":
						$infoo = $DB->getRow("SELECT j.idcofficer, COUNT(an.idanimation) AS nbr FROM animation an LEFT JOIN animjob j ON an.idanimjob = j.idanimjob WHERE an.".$this->facnum." = '$idfac' GROUP BY j.idcofficer ORDER BY nbr DESC LIMIT 1");
						$idcofficer = $infoo['idcofficer'];
					break;
				## MERCH
					case "3":
					case "4":
						$infoo = $DB->getRow("SELECT idcofficer, COUNT(idmerch) AS nbr FROM merch WHERE ".$this->facnum." = '$idfac' GROUP BY idcofficer ORDER BY nbr DESC LIMIT 1");
						$idcofficer = $infoo['idcofficer'];
					break;
				}
				if ($idcofficer > 0) $DB->inline("UPDATE `".$this->facbase."` SET `idcofficer` = '".$idcofficer."' WHERE `idfac` = ".$idfac);
			break;
		}

		if ($idcofficer > 1) {
			$cinf = $DB->getRow("SELECT oprenom, onom, qualite, langue, departement FROM cofficer WHERE idcofficer = ".$idcofficer);
			$this->nom = $cinf['oprenom']." ".$cinf['onom'];
			$this->dept = $cinf['departement'];
			$this->langue = strtoupper($cinf['langue']);
			$this->qual = $this->qualite ($cinf['qualite']);
		}

		return $idcofficer;
	}
}