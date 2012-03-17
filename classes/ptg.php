<?php
############################################################################################################
#
#	Ce fichier gŽnre un fichier PTG comportant les informations de payement envoyŽs au secretariat social Groupe
#
#	Ce rapport doit etre envoyŽ mensuellement au Groupe-S
#
#	Copyright Celsys 2003-2006
#
#	Ce fichier ne peut etre reproduit ou modifié sans l'accord préalable et écrit de Celsys SPRL
#	Celsys SPRL est entière propriétaire de ce code
#	Ce fichier a été réalisé dans le cadre du projet NEURO
#
############################################################################################################

## Classe PTG
include_once(NIVO."classes/vip.php");
include_once(NIVO."classes/anim.php");
include_once(NIVO."classes/merch.php");

class ptg
{
######## Config #####################################
	var $numaffiliation = '0000067442'; # Numéro d'affiliation chez Groupe-S (10 N)
	var $devise         = 'EUR '; # Devise employée 'EUR ' ou 'BEF ' (4 AN)

	######## Initianlisation des Variables ##############
	var $stocksql       = array();
	var $tarif_salaire  = array();
	var $lefile         = '';
	var $urlPTG         = '';
	var $filepath       = '';
	var $dateSQL        = '';
	var $datefrom       = '';
	var $dateto         = '';
	var $registre       = '';
	var $precompte      = '';

	var $nombre001      = 0;
	var $nombre002      = 0;
	var $nombre003      = 0;
	var $nombre004      = 0;
	var $nombre005      = 0;
	var $nombre006      = 0;
	var $nombre007      = 0;
	var $nombre008      = 0;
	var $nombre009      = 0;
	var $nombre010      = 0;
	var $nombre011      = 0;
	var $nombre020      = 0;
	var $nombre021      = 0;
	var $nombre022      = 0;
	var $nombre023      = 0;
	var $nombre024      = 0;
	var $nombre025      = 0;
	var $nombre030      = 0;
	var $nombre031      = 0;
	var $nombre032      = 0;
	var $nombre033      = 0;
	var $nombre034      = 0;
	var $nombre035      = 0;
	var $nombre036      = 0;
	var $nombre037      = 0;
	var $nombre038      = 0;
	var $nombre039      = 0;

## Fonctions Génériques
	function formatnombre ($pre, $post, $nombre) {
		$nombre = str_replace(",", ".", $nombre) ;
		$numers = explode ('.', $nombre);
		if (!isset($numers[1])) $numers[1] = '';
		return str_repeat('0', $pre - strlen($numers[0])).$numers[0].$numers[1].str_repeat('0', $post - strlen($numers[1]));
	}

## Fonction principale
	function ptg ($path = '') {
		if (!empty($path)) {
			$nomdufile = strftime("%Y%m%d", time()).'-EX'.$this->numaffiliation.'.dat';

			$this->filepath = Conf::read('Env.root').$path.$nomdufile;
			if (file_exists($this->filepath)) unlink($this->filepath);
			$this->lefile = fopen($this->filepath, "a");
			$this->urlPTG = Conf::read('Env.urlroot').$path.$nomdufile;
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Nico
	 **/
	function affichepeople($idpeople) {
		global $DB;

### Génère les infos de DESCRIPTION du people
		$this->stocksql = array();
		$row = '';

		$people = new db();
		$people->inline("SET NAMES latin1");
		$row = $people->getRow("SELECT
			`idpeople` ,`pprenom` ,`pnom` ,`adresse1` ,`num1` ,`bte1` ,`cp1` ,`ville1` ,`pays1` ,
			`etatcivil` ,`sexe` ,`ncp` ,`nville` ,`nomconjoint` ,`dateconjoint` ,`datemariage` ,
			`ncidentite` ,`nrnational` ,`jobconjoint` ,`categorie` ,
			`catsociale` , `banque`, `nationalite` ,`ndate` ,`npays` , `dateentree` ,`codepeople` ,
			`lbureau` , `pacharge` ,`eacharge`,`iban`,`bic`,`modepay`, `salaire`, `precompte`

			FROM `people`
			WHERE `idpeople` = ".$idpeople);

		$this->precompte = $row['precompte'];

# infos SQL
$pSQL = $DB->getRow("SELECT * FROM grps.gsdb WHERE `Z00` = ".$idpeople);

			# création d'une fiche vide si people non existant dans base.
			if (empty($pSQL['Z00'])) {
				# création de la fiche
				$DB->inline('INSERT INTO grps.gsdb (`Z00`) VALUES (\''.$idpeople.'\');');
				# recherche de la fiche créée
				$pSQL = $DB->getRow("SELECT * FROM grps.gsdb WHERE `Z00` = ".$idpeople);
			}

			$chLGE = array(
				'FR' => '1',
				'NL' => '2',
			);

			$chRLG = array(
				'FR' => '1',
				'NL' => '2',
				''   => '0',
			);

			$chSEX = array(
				'm' => '1',
				'f' => '2'
			);

			$chCPE = array(
				'2' => '1',
				'1' => '3'
			);

			$fixSQL = array(
						'CSC' => '1', # Contrats de travail a durée déterminée
						'CBN' => '0', # Rémunaration Normale Brute
						'CBR' => '4', # Rémunaration Montant Horaire
						'CCC' => '2', # Contrats d'Employés
						'CSO' => '1', # Soumis a l'ONSS
						'PAR' => '21800000000', # Commission Paritaire
						'PFO' => '010', # Préfixe ONSS
						'Z32' => '1', # Par Mois
						'QUA' => '1', # Qualification professionnell
						'Z36' => 'A', # Contrats de Moins de 3 mois
						'CTP' => '1',
						'Z61' => '20010101',
						'Z62' => 'RFT',
						'Z65' => '3800',
						'Z63' => '06',
						'Z64' => '6',
						'Z66' => '',
						'Z67' => '',
						'Z68' => '',
						'Z92' => '000',
						'Z93' => '0000000000',
						'H01' => '0000',
						'H02' => '0000',
						'H03' => '0000',
						'H04' => '0000',
						'H05' => '0000',
						'H06' => '0000',
						'C01' => '',
						'C02' => '',
						'C03' => '',
						'C04' => '',
						'C05' => '',
						'C06' => '',
						'T01' => '0000',
						'T02' => '0000',
						'T03' => '0000',
						'T04' => '0000',
						'T05' => '0000',
						'T06' => '0000');

			$FMPSQL = array(
						'NOM' => 'pnom',         # Nom
						'PRE' => 'pprenom',      # Prénom
						'Z00' => 'idpeople',     # ID People
						'RUE' => 'adresse1',     # Rue
						'NUR' => 'num1',         # Numéro de Rue
						'NUB' => 'bte1',         # Numéro de Rue
						'CPO' => 'cp1',          # Code Postal
						'LOC' => 'ville1',       # Localité
						'CPP' => 'pays1',        # Pays de résidence
						'LGE' => 'lbureau',      # Langue Maternelle
						'RLG' => 'lbureau',      # Régime Linguistique
						'SEX' => 'sexe',         # Sexe
						'NAT' => 'nationalite',  # Nationalité
						'ETC' => 'etatcivil',    # Etat Civil
						'DTN' => 'ndate',        # Date Naissance People
						'CPN' => 'ncp',          # Code Postal Naissance
						'LON' => 'nville',       # Localité de naissance
						'NUI' => 'ncidentite',   # Num carte d'identité
						'RNA' => 'nrnational',   # Num registre national
						'NOC' => 'nomconjoint',  # Nom du conjoint
						'DNC' => 'dateconjoint', # Date Naissance Conjointc
						'QPC' => 'jobconjoint',  # Profession du conjoint
						'DTM' => 'datemariage',  # Date du mariage
						'RPE' => 'codepeople',   # Num registre du personnel
						'DTE' => 'dateentree',   # Date d'entrée
						'CPE' => 'catsociale',   # Type de personnel
						'PRO' => 'categorie',    # Profession du travailleur
						'NEC' => 'eacharge',     # Nombre d'enfants a charge
						'PRC' => 'pays1',        # Pays de résidence
						'Z85' => 'npays',        # Pays de Naissance
						'APC' => 'pacharge',     # Nombre d'autres personnes a charge
						'CMP' => 'modepay',		 # Mode de paiement
						'CCT' => 'catsociale', 	 # Préfixe ONSS
						'PCC' => 'catsociale',   # Precision categorie contrat
					);

			foreach($FMPSQL as $key => $value) {
				if ($row[$value] != '') {

					$infos = trim($row[$value]);

					switch ($key) {
						case "RPE":
							$infos = prezero($infos, 6);
							$this->enreg008 ($key, $infos, $pSQL[$key]);
						break;

						case "APC":
						case "NEC":
							$infos = prezero($infos, 2, 'L418');
							$this->enreg008 ($key, $infos, $pSQL[$key]);
						break;

						case "Z80":
						case "Z81":
						case "Z82":
							$infos = strtr($infos, "/ .", " ");
							$this->enreg008 ($key, $infos, $pSQL[$key]);
						break;

						case "LGE":
							$infos = strtoupper($infos);
							$infos = $chLGE[$infos];
							$this->enreg008 ($key, $infos, $pSQL[$key]);
						break;

						case "RLG":
							$infos = strtoupper($infos);
							$infos = $chRLG[$infos];
							$this->enreg008 ($key, $infos, $pSQL[$key]);
						break;

						case "DTN":
						case "DTE":
						case "DNC":
						case "DTM":
							if ($infos != '0000-00-00') {
								$dd = explode('-', $infos);
								$infos = $dd[0].$dd[1].$dd[2];
								$this->enreg008 ($key, $infos, $pSQL[$key]);
							} else {
								if ($pSQL[$key] != '') {
									$this->stocksql[$key] = 'NULL';
								}
							}
						break;

						case "SEX":
							$infos = $chSEX[$infos];
							$this->enreg008 ($key, $infos, $pSQL[$key]);
						break;

						case "CPP":
							if (!empty($infos) and ($infos != 'BE')) {
								$this->enreg008 ($key, $infos, $pSQL[$key]);
							}
						break;

						case "Z85":
							if (!empty($infos)) {
								$this->enreg008 ($key, $infos, $pSQL[$key]);
							} else {
								if ($pSQL[$key] != '') {
									$this->stocksql[$key] = 'NULL';
								}
							}
						break;

						case "NAT":
							if (!empty($infos)) {
								$this->enreg008 ($key, $infos, $pSQL[$key]);
							} else {
								if ($pSQL[$key] != $infos) {
									$this->stocksql[$key] = 'NULL';
								}
							}
						break;

						case "QPC":
							if (!empty($infos)) {
								$this->enreg008 ($key, $infos, $pSQL[$key]);
							} else {
								if ($pSQL[$key] != $infos) {
									$this->stocksql[$key] = 'NULL';
								}
							}
						break;

						case "CPE":
							$infos = $chCPE[$infos];
							$this->enreg008 ($key, $infos, $pSQL[$key]);
						break;

						case "PRC":
							if ($row['catsociale'] == 'E') {
								$this->enreg008 ($key, '0', $pSQL[$key]);
							} elseif ($infos == 'BE') {
								$this->enreg008 ($key, '1', $pSQL[$key]);
							} else {
								$this->enreg008 ($key, '2', $pSQL[$key]);
							}
						break;

						case "CCT":
							if ($infos == 'E') {
								$this->enreg008 ($key, '840', $pSQL[$key]); // Etudiant
							} elseif ((date ("Y-m-d") - $row['ndate']) < 18) {
								$this->enreg008 ($key, '487', $pSQL[$key]); // Moins de 18 ans
							} else {
								$this->enreg008 ($key, '495', $pSQL[$key]); // default
							}
						break;

						case "PCC":
							if ($infos == 'E') {
								$this->enreg008 ($key, '04', $pSQL[$key]); // Etudiant
							} else {
								$this->enreg008 ($key, '01', $pSQL[$key]); // Travailleur Ordinaire
							}
						break;

						## Registre National : n'envoie le registre que si la personne est de nationalite belge
						case "RNA":
							if ($row['nationalite'] == 'B') {
								$this->enreg008 ($key, $infos, $pSQL[$key]);
							}
						break;
						## Mode Paiement
						case "CMP":
							switch ($infos) {

								case "1": # Bancaire
									$this->enreg008 ($key, '1', $pSQL[$key]);
									$this->enreg008 ('NCF', $row['banque'], $pSQL['NCF']);
									$this->enreg008 ('Z75', '', $pSQL['Z75']);
								break;

								case "2": # Chèque
									$this->enreg008 ($key, '0', $pSQL[$key]);
									$this->enreg008 ('NCF', '', $pSQL['NCF']);
									$this->enreg008 ('Z75', '', $pSQL['Z75']);
								break;

								case "3": # IBAN BIC
									$this->enreg008 ($key, '5', $pSQL[$key]);
									$this->enreg008 ('NCF', '', $pSQL['NCF']);
									$this->enreg008 ('Z75', 'IBAN '.$row['iban'].' BIC '.$row['bic'], $pSQL['Z75']);
								break;

								case "4": # Comptant
									$this->enreg008 ($key, '2', $pSQL[$key]);
									$this->enreg008 ('NCF', '', $pSQL['NCF']);
									$this->enreg008 ('Z75', '', $pSQL['Z75']);
								break;
							}
						break;

						default;
							$infos = strtoupper($infos);
							$infos = strtr($infos, "ÎÏÙ´µËçåÌ€®‚éƒæèíêëì„ñîïÍ…¯ôòó†§à‡‰‹ŠŒ¾èé‘“’”•–˜—™›š¿œžŸØ

", "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy ");
							$infos = strtr($infos, "'", " ");

							$this->enreg008 ($key, $infos, $pSQL[$key]);
					}
				} else {
					if ($pSQL[$key] != '') {
						$this->stocksql[$key] = 'NULL';
					}
				}
			}

			foreach($fixSQL as $key => $value) {
				$this->enreg008 ($key, $value, 'no');
			}

		#### > # Calcul du salaire brut horaire
			$vdate   = substr($this->datefrom, 0,4).'-'.substr($this->datefrom, 4,2).'-'.substr($this->datefrom, 6,2);

			$this->tarif_salaire[$row['idpeople']] = salaire($row['idpeople'], $vdate);

			$numers  = explode (".", $this->tarif_salaire[$row['idpeople']]);
			$salbrut = prezero($numers[0], 7).postzero($numers[1], 4) ;

			$this->enreg008 ('MRB', $salbrut, $pSQL['MRB']);
		#### < # Calcul du salaire brut horaire

			# Stockage des nouvelles infos dans la base 'people' SQL
			if (count($this->stocksql) > 0) {

				$i = 0;
				foreach($this->stocksql as $key => $value) {
					$val = str_replace("'", "\'", $value) ;
					if ($i == 0) {
						$sqlstring = "UPDATE grps.gsdb SET";
						if ($val == 'NULL') {
							$sqlstring .= " `$key` = NULL";
						} else {
							$sqlstring .= " `$key` = '$val'";
						}
						$i++;
					} else {
						if ($val == 'NULL') {
							$sqlstring .= ", `$key` = NULL";
						} else {
							$sqlstring .= ", `$key` = '$val'";
						}
						$i++;
					}
				}
				$sqlstring .= " WHERE `Z00` = '$idpeople' LIMIT 1;";
				$DB->inline($sqlstring);
			}
	}

	function affichesalaire($idpeople, $tablesql) {
### Génère les infos de PAYEMENT du people
		$ddd     = explode ('XX', $this->dateSQL);
		$datein  = $ddd[0];
		$dateout = $ddd[1];

		$ptable  = paytable ($idpeople, $datein, $dateout);
		$dtable  = array_shift($ptable);

		# Prestations Brutes
		$f433    = array('00100' => 0, '00200' => 0, '00300' => 0, '00400' => 0, '00000' => 0);
		$f437    = array('00100' => 0, '00200' => 0, '00300' => 0, '00400' => 0, '00000' => 0);
		$f441    = array('00100' => 0, '00200' => 0, '00300' => 0, '00400' => 0, '00000' => 0);
		$mdh150  = array('00100' => 0, '00200' => 0, '00300' => 0, '00400' => 0, '00000' => 0);
		$mdh200  = array('00100' => 0, '00200' => 0, '00300' => 0, '00400' => 0, '00000' => 0);

		#># tableau des modifs SalairesXX
		$modh = new db('', '', 'grps');
		$modh->inline("SELECT * FROM `$tablesql` WHERE `idpeople` = $idpeople ORDER BY `date` ASC");

		while ($mod = mysql_fetch_array($modh->result)) {
			$date8 = str_replace("-", "", $mod['date']);
			# heures
			$mtable[$date8] = $mod['modh'];
			$th200[$date8] = $mod['modh200'];
			$th150[$date8] = $mod['modh150'];
			# frais
			$tf433[$date8] = $mod['mod433'];
			$tf437[$date8] = $mod['mod437'];
			$tf441[$date8] = $mod['mod441'];
		}
		#<# tableau des modifs SalairesXX

	unset($code664);
	foreach ($dtable as $key => $value) {
		#># Heure de repas
		if (($value['heures'] >= Conf::read('Payement.maxheure')) and ($value['date'] < '2010-02-01')) {
			$value['heures'] -= 1;
			$value['frais433'] += Conf::read('Payement.prixrepas');

			if ($th200[$key] > 0) $th200[$key] -= 1; # retire l'heure de sandwich aux h a 200
		}
		#<# Heure de repas

		#># Modifs de la base salaireXX
		$value['heures'] += $mtable[$key];
		#<# Modifs de la base salaireXX

		$char_table = count_chars($value['nivo'], 1);
		arsort($char_table);
		switch (chr(key($char_table))) {
			case"A": $sect = '00100'; break;
			case"V": $sect = '00200'; break;
			case"M": $sect = '00300'; break;
			case"E": $sect = '00400'; break;
			default: $sect = '00000';
		};

		$ctp[$key] = $value['heures'];

		if ($value['heures'] < 0) echo 'IDP : '.$idpeople; # DEBUG

		$prestations = explode ('.' , $value['heures']);
		$heures      = prezero ($prestations[0], 2, 'L675');
		$minutes     = isset($prestations[1]) ? round((('0.'.$prestations[1]) * 60), 0) : 0;
		$presta      = $heures.prezero($minutes, 2, 'L678');
		if ($presta > 0) {
			$this->enreg009 ($key , $presta, $sect);
			$code664[$sect] = $sect;
		}
		$f433[$sect]   += $value['frais433'] + $tf433[$key];
		$f437[$sect]   += $value['frais437'] + $tf437[$key];
		$f441[$sect]   += $value['frais441'] + $tf441[$key];

		$mdh150[$sect] += $th150[$key];
		$mdh200[$sect] += $th200[$key];
	}

	# Prestations Interprétées

		## Frais 433 Repas
			foreach ($f433 as $sec => $fr433) {
				$fr433 = round($fr433, 4);
				if ($fr433 > 0) {
					$this->enreg011 ('433' , $fr433, '0', $sec);
				}
			}
		## Frais 437 Déplacements
			foreach ($f437 as $sec => $fr437) {
				$fr437 = round($fr437, 4);
				if ($fr437 > 0) {
					$this->enreg011 ('437' , $fr437, '0', $sec);
				}
			}
		## Frais 441 Autres
			foreach ($f441 as $sec => $fr441) {
				$fr441 = round($fr441, 4);
				if ($fr441 > 0) {
					$this->enreg011 ('441' , $fr441, '0', $sec);
				}
			}

	############################################################################################
	## Retenue Précompte pour certains employés ################################################
	############################################################################################
		if (!empty($this->precompte)) {

			if ($montant_precompte = strstr($this->precompte, '&euro;', true)) {
				$this->enreg011 ('605' , $montant_precompte, '0');
			} elseif(substr($this->precompte, -1) == '%') {
				$montant_salaire = (
										array_sum($ctp) +
										array_sum($mdh150) +
										array_sum($mdh200)
									) * $this->tarif_salaire[$idpeople];
				$montant_precompte = round($montant_salaire / 100 * substr($this->precompte, 0, -1));
				$this->enreg011 ('605' , $montant_precompte, '0');
			} else {
				error_log("Format de precompte incorrect");
			}

		}

	############################################################################################
	############################################################################################
	############################################################################################

		## Heures a 150 %
		foreach ($mdh150 as $sec => $modh150) {
			$modh150 = round($modh150, 4);
			$modh150 = $modh150 * 60; # transforme les heures en minutes
			if ($modh150 > 0) {
				$this->enreg011 ('751' , $modh150, '50', $sec);
			}
		}

		foreach ($mdh200 as $sec => $modh200) {
			## Heures a 200 %
			$modh200 = round($modh200, 4);
			$modh200 = $modh200 * 60; # transforme les heures en minutes
			if ($modh200 > 0) {
				$this->enreg011 ('752' , $modh200, '100', $sec);
			}
		}

	# Pour le calcul des pécules de vacances
		foreach ((array)$code664 as $sec) {
			$this->enreg011 ('664' , '0', '0', $sec);
		}

	# Z94 et Z95 pour chaque date de contrat
	foreach ($ctp as $key => $value) {
		$this->enreg006 ($this->registre, $key, $key);
		$this->enreg008 ('Z94', $key, 'no');
		$this->enreg008 ('Z95', $key, 'no');

		if ($value >= 7.6) {
		##	Regime temps plein (8 et plus)
			$this->enreg008 ('CTP', '1', 'no');
			$this->enreg008 ('Z61', '20010101', 'no');
			$this->enreg008 ('Z62', 'RFT', 'no');
			$this->enreg008 ('Z65', '3800', 'no');
		} else {
		##	Regime temps partiel (moins de 8)
			$this->enreg008 ('CTP', '5', 'no');
			$this->enreg008 ('Z61', '20010101', 'no');
			$this->enreg008 ('Z62', 'RPT', 'no');
			$this->enreg008 ('Z65', '0000', 'no');
		}

		$this->enreg008 ('Z63', '06', 'no');
		$this->enreg008 ('Z64', '6', 'no');
		$this->enreg008 ('Z66', '', 'no');
		$this->enreg008 ('Z67', '', 'no');
		$this->enreg008 ('Z68', '', 'no');
		$this->enreg008 ('Z92', '000', 'no');
		$this->enreg008 ('Z93', '0000000000', 'no');
		$this->enreg008 ('H01', '0000', 'no');
		$this->enreg008 ('H02', '0000', 'no');
		$this->enreg008 ('H03', '0000', 'no');
		$this->enreg008 ('H04', '0000', 'no');
		$this->enreg008 ('H05', '0000', 'no');
		$this->enreg008 ('H06', '0000', 'no');
		$this->enreg008 ('C01', '', 'no');
		$this->enreg008 ('C02', '', 'no');
		$this->enreg008 ('C03', '', 'no');
		$this->enreg008 ('C04', '', 'no');
		$this->enreg008 ('C05', '', 'no');
		$this->enreg008 ('C06', '', 'no');
		$this->enreg008 ('T01', '0000', 'no');
		$this->enreg008 ('T02', '0000', 'no');
		$this->enreg008 ('T03', '0000', 'no');
		$this->enreg008 ('T04', '0000', 'no');
		$this->enreg008 ('T05', '0000', 'no');
		$this->enreg008 ('T06', '0000', 'no');
	}

	unset($ctp);
}

## Fonctions Enregistrements

	function enreg000 () {
		$string = '000'; # 000 ############# Indentification de l'affilié #####################
		$string .= 'PTG'; # Désignation du fichier (3 AN)
		$string .= '001'; # Version (3 AN)
		$string .= strftime("%Y%m%d", time()); # Date de création (8 N)
		$string .= strftime("%H%M%S", time()); # Heure de création (6 N)
		$string .= $this->numaffiliation ; # N° d'affiliation de l'employeur (10 N)
		$string .= 'EUR '; # Devise (4 AN)

		$string .= ' '; # Filler (1 AN)
		$string .= ''; # N° Version de la DB (6 AN)
		$string .= ' '; # Filler (1 AN)
		$string .= ''; # N° Version de l'application (6 AN)
		$string .= "\r\n";# CR/LF
		$write = fputs($this->lefile, $string);
		echo '000 Identification de l\'affili&eacute;<BR>';
	}

	function enreg001 ($typeniv, $numniv, $lfr='', $lnl='', $lal='', $lau='') {
		$string = '001'; # 001 ############# Définition des niveaux #####################
		$string .= $typeniv; # Type de niveau (1 N)
		$string .= $numniv; # Numéro de niveau (5 N)
		$string .= '00'; # Réservé (2 N)
		$string .= '00000'; # Num de commune INS (5 N)
		$string .= '4'; # Réegime Linguisitqueé (1 N)
		$string .= '1'; # Code langue principalé (1 N)
		$string .= '2'; # Code langue Secondaireé (1 N)
		$string .= '00'; # Réservé (2 N)
		$string .= ' '; # Code siege ONSSé (1 N)
		$string .= '1'; # Code Libelle de niveau (1 N)
		$string .= $lfr.str_repeat(' ', 24 - strlen($lfr)); # Libelle Francais (24 1N)
		$string .= $lnl.str_repeat(' ', 24 - strlen($lfr)); # Libelle Neerlandais (24 1N)
		$string .= $lal.str_repeat(' ', 24 - strlen($lfr)); # Libelle Allemand (24 1N)
		$string .= $lau.str_repeat(' ', 24 - strlen($lfr)); # Libelle Autre (24 1N)
		$string .= "\r\n";# CR/LF
		$write = fputs($this->lefile, $string);
		echo '001 D&eacute;laration du niveau '.$numniv.' : '.$lfr.'<BR>';
	}

	function enreg003 ($mois, $annee, $perso, $remu) {
		$string = '003'; # 003 ############# Période de Paye #####################
		$string .= $annee.$mois; # Mois de rémunération (6 N)
		$string .= '1'; # numéro d'ordre de la période (1 N)
		$string .= $perso; # Type de personnel (1 N) 1-Ouvrier, 3-Employe
		$string .= $remu; # Type de rémunération (2 N) 02-normale
		$string .= '1'; # Périodicite de rémunération (1 N) 1-Par mois
		$string .= $annee.$mois; # Mois fiscal (6 N)
		$string .= $annee.$mois.'01'; # Date de début de période (8 N)
		$this->datefrom = $annee.$mois.'01';
		$this->dateto = $annee.$mois.date("t", strtotime($annee."-".$mois."-01") );
		$this->dateSQL = $annee.'-'.$mois.'-01'.'XX'.$annee.'-'.$mois.'-'.date("t", strtotime($annee."-".$mois."-01") );
		$string .= $annee.$mois.date("t", strtotime($annee."-".$mois."-01") ); # Date de fin de période (8 N)
		$string .= '1'; # Mode de rémunération (1 N) 1-Prestations brutes complètes 6-Informations People
		$string .= $annee; # Référence (Année) du fichier (4 N)
		$string .= '00000000'; # N° séquentiel attribué a chaque extraction de fichier PTG (8 N)
		$string .= $annee.$mois.date("t", strtotime($annee."-".$mois."-01") ); # Date de passage en paie (8 N)
		$string .= '00000000'; # Date d'exécution de paye (8 N)
		$string .= "\r\n";# CR/LF
		$write = fputs($this->lefile, $string);

		echo '003 P&eacute;riode de Paye<BR>';
		$this->nombre003++;
	}

	function enreg006 ($idtravailleur, $datein, $dateout) {
		$this->registre = $idtravailleur;
		$string = '006'; # 006 ############# Début travailleur #####################
		$string .= '00000'; # Réservé (5 N)
		$string .= prezero($idtravailleur, 6); # N° du travailleur (6 N)
		$string .= $datein; # Date début sous-période (8 N)
		$string .= $dateout; # Date fin sous-période (8 N)
		$string .= "\r\n";# CR/LF
		$write = fputs($this->lefile, $string);
		$this->nombre006++;
	}

	function enreg008 ($code, $valeur, $valeursql) {
		if ($valeur != $valeursql) {
			$string = '008'; # 008 ############# Données signalétiques #####################
			$string .= $code; # Code de la donnée (3 AN)
			$string .= $valeur; # Valeur de la donnée (1-36 AN)
			$string .= "\r\n";# CR/LF
			$write = fputs($this->lefile, $string);
			$this->nombre008++;
			if ($valeursql != 'no') {
				$this->stocksql[$code] = $valeur;
			}
		}
	}

	function enreg009 ($dateprest, $heures, $n1='00000', $n2='00000', $n3='00000') {
		$string = '009'; # 009 ############# Prestations Brutes #####################
		$string .= $dateprest; # Date de la prestation (8 N)
		$string .= 'P  '; # Code de présence (3 AN)
		$string .= ' '; # Filler (1 AN)
		$string .= '+'; # Signe de la prestation (1 AN)
		$string .= $heures; # Prestations (4 N)
		$string .= $n1; # Niveau 1 (5 N)
		$string .= $n2; # Niveau 2 (5 N)
		$string .= $n3; # Niveau 3 (5 N)
		$string .= '00000000000'; # Valeur unitaire de la prestation (7.4 N)
		$string .= '00000'; # Pourcentage associé à la prestation (3.2 N)
		$string .= "\r\n";# CR/LF
		$write = fputs($this->lefile, $string);
		$this->nombre009++;
	}

	function enreg011 ($code, $frais, $pourcent = '0', $n1='00000', $n2='00000', $n3='00000') {
		$string = '011'; # 011 ############# Prestations Interprétées #####################
		$string .= $code; # Code de présence (3 AN)
		if ($frais >= 0) {
			$string .= '+'; # Signe de la prestation (1 AN)
		} else {
			$string .= '-'; # Signe de la prestation (1 AN)
		}
		$frais = abs($frais);
		$string .= $this->formatnombre (7, 4, $frais);
		$string .= '00000000000'; # Valeur unitaire de la prestation (7.4 N)
		$string .= $this->formatnombre (3, 2, $pourcent); # Pourcentage associé à la prestation (3.2 N)
		$string .= ' '; # Filler (1 AN)
		$string .= $n1; # Niveau 1 (5 N)
		$string .= $n2; # Niveau 2 (5 N)
		$string .= $n3; # Niveau 3 (5 N)
		$string .= "\r\n";# CR/LF
		$write = fputs($this->lefile, $string);
		$this->nombre011++;
	}

	function enreg999 () {
		$string = '999'; # 999 ############# Fin d'affilié #####################
		$string .= prezero($this->nombre001, 6); # Nombre d'enregistrements 001 (6 N)
		$string .= prezero($this->nombre002, 6); # Nombre d'enregistrements 002 (6 N)
		$string .= prezero($this->nombre003, 6); # Nombre d'enregistrements 003 (6 N)

		$string .= prezero($this->nombre004, 6); # Nombre d'enregistrements 004 (6 N)
		$string .= prezero($this->nombre005, 6); # Nombre d'enregistrements 005 (6 N)
		$string .= prezero($this->nombre006, 6); # Nombre d'enregistrements 006 (6 N)
		$string .= prezero($this->nombre007, 6); # Nombre d'enregistrements 007 (6 N)
		$string .= prezero($this->nombre008, 6); # Nombre d'enregistrements 008 (6 N)

		$string .= prezero($this->nombre009, 6); # Nombre d'enregistrements 009 (6 N)
		$string .= prezero($this->nombre010, 6); # Nombre d'enregistrements 010 (6 N)
		$string .= prezero($this->nombre011, 6); # Nombre d'enregistrements 011 (6 N)
		$string .= prezero($this->nombre020, 6); # Nombre d'enregistrements 020 (6 N)
		$string .= prezero($this->nombre021, 6); # Nombre d'enregistrements 021 (6 N)
		$string .= prezero($this->nombre022, 6); # Nombre d'enregistrements 022 (6 N)
		$string .= prezero($this->nombre023, 6); # Nombre d'enregistrements 023 (6 N)
		$string .= prezero($this->nombre024, 6); # Nombre d'enregistrements 024 (6 N)
		$string .= prezero($this->nombre025, 6); # Nombre d'enregistrements 025 (6 N)
		$string .= prezero($this->nombre030, 6); # Nombre d'enregistrements 030 (6 N)
		$string .= prezero($this->nombre031, 6); # Nombre d'enregistrements 031 (6 N)
		$string .= prezero($this->nombre032, 6); # Nombre d'enregistrements 032 (6 N)
		$string .= prezero($this->nombre033, 6); # Nombre d'enregistrements 033 (6 N)
		$string .= prezero($this->nombre034, 6); # Nombre d'enregistrements 034 (6 N)
		$string .= prezero($this->nombre035, 6); # Nombre d'enregistrements 035 (6 N)
		$string .= prezero($this->nombre036, 6); # Nombre d'enregistrements 036 (6 N)
		$string .= prezero($this->nombre037, 6); # Nombre d'enregistrements 037 (6 N)
		$string .= prezero($this->nombre038, 6); # Nombre d'enregistrements 038 (6 N)
		$string .= prezero($this->nombre039, 6); # Nombre d'enregistrements 039 (6 N)
		$string .= "\r\n";# CR/LF
		$write = fputs($this->lefile, $string);
		echo '990 Fin d\'affili&eacute;<BR>';
		fclose($this->lefile);
	}
}
?>
