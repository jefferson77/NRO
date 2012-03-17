<?php
###############################################################################################################################
### Nouvelle classe anim #####################################################################################################
#############################################################################################################################

class coreanim
{
	# Config Values
	var $tarifkmpay   = 0.23; # Prix d'un kilomètre payé a un Promoboy
	var $tarifdimona  = 0.251;
	# Variables
	var $hprest       = 0;
	var $hprestr      = 0;
	var $kipay        = 0;
	var $kifac        = 0;
	var $fraispay     = 0;

	var $frais437     = 0;
	var $frais441     = 0;
	var $frais433     = 0;

	var $h100         = 0;
	var $h150         = 0;
	var $h200         = 0;
	var $date         = 0;

	var $FraisDimona  = 0;
	var $HeuresDimona = 0;

	var $Tarif        = array();

	# fonctions de formatage
	function hnum ($heures) {
		$h1 = explode (':', $heures);
		$hin = $h1[0] + ($h1[1] / 60);

		return $hin;
	}

	## Fonctin principale
	function coreanim ($idmission) {

		$findinfos = new db();
		$lf = $findinfos->getRow("SELECT
			a.datem, a.hin1, a.hout1, a.hin2, a.hout2, a.ferie, a.kmpaye, a.kmfacture, a.facnum,
			nf.montantpaye, nf.montantfacture,
			c.fraisdimona,
			f.fraisdimona as ffraisdimona
			FROM animation a
			LEFT JOIN animjob j on a.idanimjob = j.idanimjob
			LEFT JOIN facture f ON a.facnum = f.idfac
			LEFT JOIN client c ON j.idclient = c.idclient
			LEFT JOIN notefrais nf ON a.idanimation = nf.idmission AND nf.secteur = 'AN'
			WHERE a.idanimation = '".$idmission."'");

		$amin = $lf['hin1'];
		$amout = $lf['hout1'];
		$pmin = $lf['hin2'];
		$pmout = $lf['hout2'];
		$kmfac = $lf['kmfacture'];
		$kmpay = $lf['kmpaye'];
		$fraisp = $lf['montantpaye'];
		$this->Tarif['fraisdimona'] = ($lf['facnum'] > 0)?$lf['ffraisdimona']:$lf['fraisdimona'];
		$this->date = $lf['datem'];

		$jourdemission = date("N", strtotime($this->date));

		# formatage des heures en nombres
		$amin = $this->hnum ($amin) ;
		$amout = $this->hnum ($amout) ;
		$pmin = $this->hnum ($pmin) ;
		$pmout = $this->hnum ($pmout) ;

		# si passage de minuit
		if ($amout < $amin) $amout = $amout + 24;
		if ($pmout < $pmin) $pmout = $pmout + 24;

		# heures prestées par le promoboy
		$total = $amout - $amin + $pmout - $pmin;

	### Decoupage H100, H150, H200 ############################
		if (($jourdemission == 7) or ($lf['ferie'] == 200)) { # Dimanche a 200
			$this->h200 += $total;
		} elseif ($lf['ferie'] == 150) { # jour a 150
			$this->h150 += $total;
		} else { # Pas Dimanche
			# matin
			if (($amin > 0) and ($amout > 0)) {
				if ($amin < 6) {
					if ($amout < 6) {
						$this->h150 += $amout - $amin;
					} else {
						$this->h150 += 6 - $amin;
					}
				}

				if ($amout > 22) {
					if ($amin > 22) {
						$this->h150 += $amout - $amin;
					} else {
						$this->h150 += $amout - 22;
					}
				}
			}
			# aprem
			if (($pmin > 0) and ($pmout > 0)) {
				if ($pmin < 6) {
					if ($pmout < 6) {
						$this->h150 += $pmout - $pmin;
					} else {
						$this->h150 += 6 - $pmin;
					}
				}

				if ($pmout > 22) {
					if ($pmin > 22) {
						$this->h150 += $pmout - $pmin;
					} else {
						$this->h150 += $pmout - 22;
					}
				}
			}
		}

		## Arrondi au quart d'heure
		$this->h150    = ceil($this->h150 * 4) / 4;
		$this->h200    = ceil($this->h200 * 4) / 4;
		$this->hprestr = ceil($total      * 4) / 4;

		## h100
		$this->h100 = $this->hprestr - $this->h150 - $this->h200;

		$this->hprest = $this->h100 + ($this->h150 * 1.5) + ($this->h200 * 2);

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

				$this->FraisDimona = $this->hprestr * $this->tarifdimona;
				$this->HeuresDimona = $this->hprestr;
			}
	#/# Decoupage H100, H150, H200 ############################

		# KM pay et fac
		if ($kmpay != '') {
			$this->kipay = $kmpay;
			$this->kifac = $kmfac;
		} else {
			$this->kipay = $kmfac;
			$this->kifac = $kmfac;
		}

		# FRAIS Payement

			# Frais 437 : Frais déplacement (KM * 0,2 + forfait)
			$this->frais437 = $this->kipay * $this->tarifkmpay;

			# Frais 441 : Reste des frais (remboursement ts frais)
			$datebut = implode("", explode("-", $lf['datem']));

			if ($datebut > '20060931') {
				$this->frais441 = 0; ## Plus de frais payés a partir du 1er Octobre 2006
			} else {
				$this->frais441 = $fraisp;
			}

			# Frais 433 : Frais Repas Pas encore utilisés en Anim
			# $this->frais433 = ;

		$this->fraispay = $this->frais433 + $this->frais437 + $this->frais441;
	}
}
###############################################################################################################################
### Calcul des valeurs de  factures/offre ####################################################################################
#############################################################################################################################

class facanim {
# Variables
	var $mode = '';
	var $id = 0;
	## divers


	## infos
	var $societe = '';
	var $idclient = '';
	var $contact = '';
	var $adresse = '';
	var $pays = '';
	var $ftva = '';
	var $astva = '';
	var $agent = '';
	var $rubhortva = '';

	var $numtva = '';
	var $phrasebook = '';

	## montants
	var $MontFrais = 0;
	var $MontDivers = 0;
	var $MontBriefingH = 0;
	var $MontBriefingKM = 0;
	var $MontForfait = 0;
	var $MontPrest = 0;
	var $MontDepl = 0;
	var $MontStand = 0;
	var $MontLivraison = 0;
	var $MontDimona = 0;
	var $MontHTVA = 0;
	var $MontTVA = 0;
	var $MontTTC = 0;
	var $takm='';
	var $MontGobelets = 0;
	var $MontServiette = 0;
	var $MontFour = 0;
	var $MontCuredent = 0;
	var $MontCuillere = 0;
	var $MontRechaud = 0;
	var $MontFraisAutre = 0;
	var $nbrheures = 0;

	var $hforfait='';

	## Details
	var $DetPresta = '';
	var $DetForfait = 0;
	var $DetFrais;
	var $stand = 0;
	var $DetDeplac = '';
	var $livraison = '';
	var $briefing = '';
	var $DescForfait = '';
	var $HeuresDimona = 0;

	function facanim($mode, $idmode) {
		$this->mode = $mode;
		$this->id = $idmode;

	######################################################################
	##### infos ##########################################################
		$req = new db();

	## SELECT
		$sqldata = "SELECT
			a.idanimation, a.livraisonfacture, a.hout1, a.hin2, a.fmode, a.datem, a.kmfacture, a.idshop,
			ma.stand, ma.gobelet, ma.serviette, ma.four, ma.curedent, ma.cuillere, ma.rechaud, ma.autre,
			nf.montantfacture

			FROM animation a
			LEFT JOIN notefrais nf ON nf.idmission = a.idanimation AND secteur = 'AN'
			LEFT JOIN animmateriel ma ON ma.idanimation = a.idanimation ";


	## WHERE
		switch ($mode) {
			case"OFFRE":
				$sqldata  .= "WHERE a.idanimjob = ".$this->id." ";

				$req->inline("SELECT
					j.notefrais, j.noteprest , j.notedeplac,
					c.societe, c.idclient, c.adresse, c.cp, c.ville, c.pays, c.codetva, c.tva,
					c.codetva, c.tva, c.astva, c.hforfait, c.taforfaitkm, c.taforfait, c.taheure, c.takm, c.tastand, c.fraisdimona,
					o.langue, o.qualite, o.oprenom, o.onom,
					ag.nom as nomagent, ag.prenom as prenomagent
					FROM animjob j
					LEFT JOIN client c ON j.idclient = c.idclient
					LEFT JOIN cofficer o ON j.idcofficer = o.idcofficer
					LEFT JOIN agent ag ON j.idagent = ag.idagent
					WHERE j.idanimjob = ".$this->id);

				$infos = mysql_fetch_array($req->result);
				$forfdate = date("Y-m-d");

			break;
			case"PREFAC":
				$sqldata  .= "WHERE a.facnumtemp = ".$this->id." ";

			## Infos facture
				$req->inline('SELECT
					c.taheure, c.takm, c.taforfait, c.taforfaitkm, c.tastand,
					c.societe, c.idclient, c.adresse, c.cp, c.ville, c.pays, c.codetva, c.tva, c.codetva, c.tva, c.astva, c.hforfait, c.fraisdimona,
					o.langue, o.qualite, o.oprenom, o.onom
					FROM tempfactureanim f
					LEFT JOIN client c ON f.idclient = c.idclient
					LEFT JOIN cofficer o ON f.idcofficer = o.idcofficer
					WHERE f.idfac = '.$this->id.' GROUP BY f.idfac');

				$infos = mysql_fetch_array($req->result);

				$forfdate = date("Y-m-d");

			## Infos Agent
				$req->inline('SELECT
					ag.nom as nomagent, ag.prenom as prenomagent
					FROM animation a
					LEFT JOIN agent ag ON a.idagent = ag.idagent
					WHERE a.facnumtemp = '.$this->id.'
					GROUP BY a.idagent
					ORDER BY count(*) DESC
					LIMIT 1');

				$infos = array_merge($infos, mysql_fetch_array($req->result));

			## note job
				$req->inline('SELECT
					TRIM(GROUP_CONCAT(notefrais SEPARATOR " ")) as notefrais,
					TRIM(GROUP_CONCAT(noteprest SEPARATOR " ")) as noteprest,
					TRIM(GROUP_CONCAT(notedeplac SEPARATOR " ")) as notedeplac
					FROM animjob
					WHERE idanimjob IN (SELECT idanimjob FROM animation WHERE facnumtemp = '.$this->id.')
					GROUP BY idclient');

				$infos = array_merge($infos, mysql_fetch_array($req->result));

			break;
			case"FACTURE":
				$sqldata  .= "WHERE a.facnum = ".$this->id." ";

			## Infos facture
				$req->inline('SELECT
					f.datefac, f.tarif01 as taheure, f.tarif02 as takm, f.tarif03 as taforfait, f.tarif04 as taforfaitkm, f.tarif05 as tastand, f.fraisdimona, f.hforfait,
					c.societe, c.idclient, c.adresse, c.cp, c.ville, c.pays, c.codetva, c.tva, c.codetva, c.tva, c.astva,
					o.langue, o.qualite, o.oprenom, o.onom
					FROM facture f
					LEFT JOIN client c ON f.idclient = c.idclient
					LEFT JOIN cofficer o ON f.idcofficer = o.idcofficer
					WHERE f.idfac = '.$this->id.' GROUP BY f.idfac');

				$infos = mysql_fetch_array($req->result);

				$forfdate = $infos['datefac'];

			## Infos Agent
				$req->inline('SELECT
					ag.nom as nomagent, ag.prenom as prenomagent
					FROM animation a
					LEFT JOIN agent ag ON a.idagent = ag.idagent
					WHERE a.facnum = '.$this->id.'
					GROUP BY a.idagent
					ORDER BY count(*) DESC
					LIMIT 1');

				$infos = array_merge($infos, mysql_fetch_array($req->result));

			## note job
				$req->inline('SELECT
					TRIM(GROUP_CONCAT(notefrais SEPARATOR " ")) as notefrais,
					TRIM(GROUP_CONCAT(noteprest SEPARATOR " ")) as noteprest,
					TRIM(GROUP_CONCAT(notedeplac SEPARATOR " ")) as notedeplac
					FROM animjob
					WHERE idanimjob IN (SELECT idanimjob FROM animation WHERE facnum = '.$this->id.')
					GROUP BY idclient');

				$infos = array_merge($infos, mysql_fetch_array($req->result));
		}

	## ORDER BY
		$sqldata  .= "ORDER BY a.datem";

		$this->societe = $infos['societe'];
 		$this->idclient = $infos['idclient'];
		$this->contact = $infos['qualite']." ".$infos['oprenom']." ".$infos['onom'];
		$this->adresse = $infos['adresse']."\r".$infos['cp']." ".$infos['ville'];
		$this->pays = $infos['pays'];
		$this->ftva = $infos['codetva']." ".$infos['tva'];
		$this->astva = $infos['astva'];
		$this->agent = $infos['prenomagent'].' '.$infos['nomagent'];
		$this->hforfait = $infos['hforfait'];


	## PhraseBook #####
		switch ($infos['langue']) {
			case "FR":
				include NIVO.'print/anim/facture/fr.php';
				setlocale(LC_TIME, 'fr_FR');
			break;
			case "NL":
				include NIVO.'print/anim/facture/nl.php';
				setlocale(LC_TIME, 'nl_NL');
				$infos['qualite'] = qualiteNL($infos['qualite']);
			break;
			default:
				$phrase = array('');
				echo '<br> 1-Langue pas d&eacute;finie pour le client : '.$infos['idclient']." ".$infos['societe'];
		}

		$this->phrasebook = $phrase;

	## Num tva #####
		if ($this->astva == 7) {
			$this->numtva = $phrase[75];
		} else {
			$this->numtva = $infos['codetva']." ".$infos['tva'];
		}

	######################################################################
	##### datas ##########################################################
		$req->inline($sqldata);

		# Init vars
		$forfait = array();
		$mis = array();
		$forfaitkm = 0;
		$forfaitkmf = 0;
		$briefingTimetot = 0;
		$briefingKm = 0;
		$timetot100 = 0;
		$timetot150 = 0;
		$timetot200 = 0;
		$DetKm = 0;
		$DetFKm = 0;

      $tofmod = array();

		while ($row = mysql_fetch_array($req->result)) {
			$fich = new coreanim($row['idanimation']);

			$tarifdimona = $fich->tarifdimona;

		## ??? livraison ################################ PATCODE je ne vois pas a quoi ca peut servir
			if($row['livraisonfacture'] > '0') $mis[$row['livraisonfacture']] += 1; ### +1 pour chaque mission

		## Heures et KM #########################

         ## check que le fmode est bien rempli sinon -> normal
         if (empty($row['fmode'])) {
            $tofmod[] = $row['idanimation'];
            $row['fmode'] = 'normal';
         }

			if (($infos['hforfait'] == 2) and ($row['fmode'] == 'normal')) {
			### Client = facturation forfaitaire
				if (!isset($forfait[$row['datem'].'-'.$row['idshop']])) $forfait[$row['datem'].'-'.$row['idshop']] = 0;
				$forfait[$row['datem'].'-'.$row['idshop']] += $fich->hprest;
				$forfaitkm += $row['kmfacture'];
				$forfaitkmf += $infos['taforfaitkm'];

			} elseif ($row['fmode'] == 'briefing') {
			### Briefing
				$briefingTimetot += $fich->hprest;
				$briefingKm += $row['kmfacture'];
			} else {
			### facturation NON forfaitaire
				$timetot100 += $fich->h100;
				$timetot150 += $fich->h150;
				$timetot200 += $fich->h200;
				$DetKm += $row['kmfacture'];
			}

		## Matériel #####################################
			$this->stand += $row['stand'];

			$this->MontGobelets += round($row['gobelet'], 2);
			$this->MontServiette += round($row['serviette'], 2);
			$this->MontFour += round($row['four'], 2);
			$this->MontCuredent += round($row['curedent'], 2);
			$this->MontCuillere += round($row['cuillere'], 2);
			$this->MontRechaud += round($row['rechaud'], 2);
			$this->MontFraisAutre += round($row['autre'], 2);

			$this->MontDimona += round($fich->FraisDimona, 2);
			$this->HeuresDimona += round($fich->HeuresDimona, 2);
			$this->MontFrais += round($row['montantfacture'], 2);
			$this->MontLivraison += round($row['livraisonfacture'], 2);
		}

		$this->MontDivers += $this->MontGobelets + $this->MontServiette + $this->MontFour + $this->MontCuredent + $this->MontCuillere + $this->MontRechaud + $this->MontFraisAutre;


      ## MAJ des fmod
      if (count($tofmod) > 0) {
         $req->inline("UPDATE `animation` SET fmode = 'normal' WHERE `idanimation` IN (".implode(", ", $tofmod).")");
      }


	######################################################################
	##### Montants #######################################################

	# décomposition des forfaits
		foreach ($forfait as $date => $nheures) {
			if ($forfdate >= '2008-10-30') {
				$this->DetForfait += ceil($nheures / 7);
			} else {
				$this->DetForfait += ceil($nheures / 9);
			}
		}

		$this->MontForfait = round($this->DetForfait * $infos['taforfait'], 2);

		if ($forfaitkm > $forfaitkmf) { ## facture les KMs si supérieur aux forfaits
			$DetFKm += $forfaitkm - $forfaitkmf;
		}

	# briefing
		$this->MontBriefingH = round($briefingTimetot * $infos['taheure'], 2);
		$this->MontBriefingKM = round($briefingKm * $infos['takm'], 2);

	# nombre d'heures
		if ($this->DetForfait > 0 ) {
			$this->nbrheures = $this->DetForfait * 8;
		} else {
			$this->nbrheures = round($timetot100, 2) + round($timetot150, 2) + round($timetot200, 2);
		}

	# normal
		$this->MontDepl = round((($DetKm + $DetFKm) * $infos['takm']), 2);
		$this->MontPrest100 = round($timetot100 * $infos['taheure'] * 1.0, 2);
		$this->MontPrest150 = round($timetot150 * $infos['taheure'] * 1.5, 2);
		$this->MontPrest200 = round($timetot200 * $infos['taheure'] * 2.0, 2);
		$this->MontPrest = ($this->MontPrest100 + $this->MontPrest150 + $this->MontPrest200);
		$this->MontStand = round($this->stand * $infos['tastand'], 2) ;
		$this->takm = $infos['takm'];
	# Totaux
		$this->MontHTVA = $this->MontForfait + $this->MontBriefingH + $this->MontBriefingKM + $this->MontPrest + $this->MontDepl + $this->MontFrais + $this->MontLivraison + $this->MontStand + $this->MontDivers + $this->MontDimona ;

		## si étranger sans num de TVA rentré
			switch ($this->astva) {
				case "3": ## Exonéré
					$this->rubhortva = '5';
				break;

				case "4": ## Assujeti
				case "7": ## Particulier sans num de TVA
					$this->rubhortva = '5';
				break;

				case "5": ## Etranger CEE
					if ((!empty($infos['tva'])) and ($this->pays != 'Belgique')) {
						$this->rubhortva = '14';
					} else {
						$this->rubhortva = '5';
					}
				break;

				case "6": ## Etranger Hors CEE
					if ((!empty($infos['tva'])) and ($this->pays != 'Belgique')) {
						$this->rubhortva = '7';
					} else {
						$this->rubhortva = '5';
					}
				break;

				case "8": ## Communauté Européenne
					$this->rubhortva = '7';
				break;
			}
		## Totaux TVA et TTC #############################################
		if ($this->rubhortva != '5') {
			$this->MontTVA = 0;
		} else {
			$this->MontTVA = round(0.21 * $this->MontHTVA, 2);
		}

		$this->MontTTC = $this->MontHTVA + $this->MontTVA;

	######################################################################
	##### Intitules des postes ###########################################
		switch ($infos['langue']) {
			case "FR":
				$phra[1] = " heures au tarif de base de ";
				$phra[2] = " /h";
				$phra[3] = " heures supplémentaires (150%) ";
				$phra[4] = " heures de prestations jour ferié ou dimanche (200%)";
				$phra[5] = " km au tarif de ";
				$phra[6] = " /km";
				$phra[7] = " km supplémentaires au tarif de ";
				$phra[8] = " stand(s) au tarif de ";
				$phra[9] = " x forfait(s) au tarif de ";
				$phra[10] = " heures de briefing au tarif de ";
				$phra[11] = " Euros de frais DIMONA ";
			break;
			case "NL":
				$phra[1] = " uren aan het basis tarief van ";
				$phra[2] = " /u";
				$phra[3] = " uren aan speciaal tarief (150%)";
				$phra[4] = " prestatie(s) tijdens feestdag of zondag (200%)";
				$phra[5] = " km aan tarief van ";
				$phra[6] = " /km";
				$phra[7] = " extra km aan tarief van ";
				$phra[8] = " stand(en) aan tarief van ";
				$phra[9] = " x forfait aan het tarief van ";
				$phra[10] = " briefing uren aan ";
				$phra[11] = " Euros DIMONA kosten ";
			break;
		}
	# Notes Frais
		$this->DetFrais = $infos['notefrais'];

	# Detail des Frais
		if ($this->MontDimona > 0) {
			$this->DetFrais .= fnbr($this->HeuresDimona)." x ".$tarifdimona.$phra[11]." \r";
		}
	# Detail des prestations
		if (!empty($infos['noteprest'])) 	$this->DetPresta .= $infos['noteprest']."\r";
		if ($this->MontPrest100 > 0) 		$this->DetPresta .= fnbr($timetot100).$phra[1].fpeuro($infos['taheure']).$phra[2]."\r";
		if ($this->MontPrest150 > 0) 		$this->DetPresta .= fnbr($timetot150).$phra[3]."\r";
		if ($this->MontPrest200 > 0) 		$this->DetPresta .= fnbr($timetot200).$phra[4]."\r";
	# Detail des déplacement
		if (!empty($infos['notedeplac'])) 	$this->DetDeplac .= $infos['notedeplac']."\r";
		if ($DetKm > 0)  						$this->DetDeplac .= fnbr($DetKm).$phra[5].fpeuro($infos['takm']).$phra[6]."\r";
		if ($DetFKm > 0) 						$this->DetDeplac .= $DetFKm.$phra[7].fpeuro($infos['takm']).$phra[6]."\r";
	# Stand
		if ($this->stand > 0) {			$this->stand .= $phra[8].fpeuro($infos['tastand']); } else { $this->stand = ''; }
	# Detail des forfaits
		if ($this->DetForfait > 0) 		$this->DescForfait .= $this->DetForfait.$phra[9].fpeuro($infos['taforfait']).".";
	# Detail des briefings
		if ($this->MontBriefingH > 0)  	$this->briefing .= fnbr($briefingTimetot).$phra[10].fpeuro($infos['taheure']).$phra[2]."\r";
		if ($this->MontBriefingKM > 0) 	$this->briefing .= fnbr($briefingKm).$phra[5].fpeuro($infos['takm']).$phra[6]."\r";
	# Detail de livraison
		if (count($mis) > 0) {
			foreach ($mis as $montant => $nombre) {
				$this->livraison .= $nombre. " x ". fpeuro($montant) . "      ";
			}
		}
	}
}
?>