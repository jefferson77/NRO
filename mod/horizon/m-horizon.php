<?php

function shortFacId($facid) {
	return (int)substr($facid, -5);
}


/**
* Classe Horizon : generation des fichiers CLIENTS.MOD et ENCFACXXXX
*/
class Horizon
{
	## init
	var $clients = array();
	var $factures = array();
	var $newclients = array();
	var $tvaerror = array();
	var $pasexport = array();
	var $c = 0;
	var $f = 0;
	var $n = 0;
	var $secteurs = '';
	var $ledir = '';

	## separators
	var $csep = "	";
	var $lsep = "\r\n";

	function __construct()
	{
		$this->secteurs = Conf::read('Secteurs');
		$this->ledir = Conf::read('Env.root').'media/horizon/';
	}
	
	/**
	 * Ajout des factures
	 *
	 * @return void
	 * @author Nico
	 **/
	function add_facs ($facin, $facout="") {
		global $DB;
		
		$infosfactures = $DB->getArray("SELECT
			f.idfac, f.idclient,
			c.societe, c.codetva, c.tva, c.codecompta, c.astva
			FROM facture f
				LEFT JOIN client c ON f.idclient = c.idclient
			WHERE f.idclient >= 1 AND f.idfac >= ".$facin.(($facout > 0)?" AND f.idfac <= ".$facout:"")." ORDER BY f.idfac");

		foreach ($infosfactures as $row) {
			$this->clients[$row['idclient']] = array(
					'soc' => $row['societe'], 
					'ctva' => $row['codetva'], 
					'tva' => $row['tva'], 
					'chorizon' => $row['codecompta'], 
					'astva' => $row['astva']
				);
			$this->factures[] = array(
					'id' => $row['idfac'],
					'type' => 'VEN'
				);
		}
	}
	
	/**
	 * Ajout des notes de credit
	 *
	 * @return void
	 * @author Nico
	 **/
	function add_ncs ($ncin, $ncout) {
		global $DB;
		
		$infosnc = $DB->getArray("SELECT
			nc.idfac, 
			f.idclient,
			c.societe, c.codetva, c.tva, c.codecompta, c.astva
		FROM credit nc
			LEFT JOIN facture f ON nc.facref = f.idfac
			LEFT JOIN client c ON f.idclient = c.idclient
		WHERE f.idclient >= 1 AND nc.idfac >= ".$ncin.(($ncout > 0)?" AND nc.idfac <= ".$ncout:"")." ORDER BY nc.idfac");

		foreach ($infosnc as $row) {
			$this->clients[$row['idclient']] = array(
					'soc' => $row['societe'], 
					'ctva' => $row['codetva'], 
					'tva' => $row['tva'], 
					'chorizon' => $row['codecompta'], 
					'astva' => $row['astva']
				);
			$this->factures[] = array (
					'id' => $row['idfac'],
					'type' => 'NCV'
				);
		}
	}

	/**
	 * Check les nums de TVA des clients
	 *
	 * @return void
	 * @author Nico
	 **/
	function check_client() {
		if (count($this->clients) > 0) ksort($this->clients);

		$check = new tva();

		foreach ($this->clients as $key => $value) {

			$check->tvacheck($value['ctva'], $value['tva'], $key, $value['soc'], $value['astva']);
			
			$this->tvaerror = $check->errtva;

			## repère les clients sans code HORIZON
			if ($value['chorizon'] == '') {
				$this->newclients[$key] = array('soc' => $value['soc'], 'ctva' => $value['ctva'], 'tva' => $value['tva'], 'chorizon' => $value['chorizon']);
			}

			## les clients a ne pas exporter
			$this->pasexport = array_unique(array_merge(array_keys($this->tvaerror), array_keys($this->newclients)));
		}
	}
	
	/**
	 * Génère le fichier CLIENT.MOD
	 *
	 * @return void
	 * @author Nico
	 **/
	function generate_client() {
		global $DB;
		
		$clifile = Conf::read('Env.root').'media/horizon/CLIENT.MOD';

		if (file_exists($clifile)) $delete = unlink($clifile);

		$fcli = fopen($clifile, "a");

		foreach ($this->clients as $key => $value) {

				$infos = $DB->getRow("SELECT societe, adresse, ville, cp, pays, cprenom, cnom, langue,
					codetva, tva, tel, fax, email, qualite, codecompta
				 	FROM `client` WHERE `idclient` = ".$key);

				$string  = '1'.$this->csep ; 					#
				$string .= $infos['codecompta'].$this->csep ;	# Numéro du client

					$societe = wordwrap( $infos['societe'], 30, "-sep-", 1);
					$soc = explode("-sep-", $societe);

				$string .= $soc[0].$this->csep ;				# Nom du client 
				$string .= $soc[1].$this->csep ;				# Nom du client suite

					$adresse = wordwrap( $infos['adresse'], 30, "-sep-", 1);
					$addr = explode("-sep-", $adresse);
				$string .= $addr[0].$this->csep ;				# Adresse 
				$string .= $addr[1].$this->csep ;				# Adresse suite


				$string .= $infos['ville'].$this->csep ;		# Localité
				$string .= $infos['cp'].$this->csep ;			# Code Postal
		#  ***  # $string .= $infos['pays'].$this->csep ;		# Code Pays
				if ($infos['pays'] == 'Belgique') {
					$string .= 'BE'.$this->csep ;				# Code Pays
				} else {
					$string .= $this->csep ;					# Code Pays
				}

				$string .= '...'.$this->csep ;					# Code Représentant

				$string .= '...'.$this->csep ;					# Code Tournée
				$string .= 'CLI'.$this->csep ;					# Code Catégorie

				$string .= $infos['langue']{0}.$this->csep ;	# Code langue

				$string .= '...'.$this->csep ;					# Code Tarif
				$string .= 'EUR'.$this->csep ;					# Code devise
		#  ***  Modifier les codes tva poru tenir compte des valeurs des code TVA horizon		
				if ($infos['codetva'] == 'BE') {
					$string .= '2'.$this->csep ;				# Code TVA
				} else {
					$string .= '3'.$this->csep ;				# Code TVA
				}

				if (substr($infos['tva'], 0, 1) == '0') {
					$infos['tva'] = substr($infos['tva'], 1);
				}
					$numtva = $infos['codetva'].str_replace(" ", "", $infos['tva']);
				$string .= $numtva.$this->csep ;				# Numéro de TVA

				$string .= '1'.$this->csep ;					# Mode calcul de la TVA
				$string .= '2'.$this->csep ;					# Code échéance = X jours date de facture

				$string .= '30'.$this->csep ;					# Nombre de jours d'échéance
				$string .= '1'.$this->csep ;					# Mode de paiement
				$string .= '00,00'.$this->csep ;				# Taux d'escompte
				$string .= '2'.$this->csep ;					# Code rappel de payement
				$string .= '1'.$this->csep ;					# Nombre de factures à imprimer
				$string .= '0'.$this->csep ;					# Limite de crédit

				$string .= '0'.$this->csep ;					# ?? colonne supplémentaire dans le fichier XLS

				$string .= ''.$this->csep ;						# Code Masque
				$string .= $infos['tel'].$this->csep ;			# Numéro de téléphone 1
				$string .= ''.$this->csep ;						# Numéro de téléphone 2
				$string .= $infos['fax'].$this->csep ;			# Numéro de fax 1
				$string .= ''.$this->csep ;						# Numéro de fax 2
				$string .= $infos['email'].$this->csep ;		# Adresse E-Mail
				$string .= ''.$this->csep ;						# Mémo 1
				$string .= ''.$this->csep ;						# Date de création AAAAMMJJ
				$string .= ''.$this->csep ;						# Date de Modification AAAAMMJJ
				$string .= ''.$this->csep ;						# Date de dernière vente AAAAMMJJ
				$string .= '0'.$this->csep ;					# Egalement fournisseur
				$string .= ''.$this->csep ;						# Code Fournisseur
				$string .= '1'.$this->csep ;					# Utilisé
				$string .= '0'.$this->csep ;					# Bloqué

				$string .= $infos['qualite'].' '.$infos['cprenom'].' '.$infos['cnom'].$this->csep ; # Contact 1
				$string .= ''.$this->csep ;						# Contact 2
				$string .= ''.$this->csep ;						# Code chanage
				$string .= '0'.$this->csep ;					# Taux de remise
				$string .= '2'.$this->csep ;					# statut assurance de crédit
				$string .= ''.$this->csep ;						# Date d'assurance de crédit
				$string .= '1'.$this->csep ;					# Groupe de clients
				$string .= ''.$this->csep ;						# Tarif spécial
				$string .= ''.$this->csep ;						# Adresse Internet (URL) Non géré dans NEURO
				$string .= ''.$this->csep ;						# Code frais de port
				$string .= ''.$this->csep ;						# Catégorie de commission
				$string .= '0'.$this->csep ;					# Bons de livraisons séparés

				$string .= ''.$this->csep ;						# Code commentaire de livraison
				$string .= ''.$this->csep ;						# Code commentaire de payement
				$string .= '0'.$this->csep ;					# Bloqué (dépassement de limite de crédit)
				$string .= '0'.$this->csep ;					# Bloqué pour retard de payement
				$string .= ''.$this->csep ;						# Mémo 2
				$string .= ''.$this->csep ;						# Mémo 3
				$string .= ''.$this->csep ;						# Mémo 4
				$string .= ''.$this->csep ;						# Pseudonyme
				$string .= ''.$this->csep ;						# Code NACE
				$string .= ''.$this->csep ;						# Objectif annuel

				$write = fputs($fcli, $string.$this->lsep);		# write + Fin de Ligne
				$this->c++;
		}

		fclose($fcli);

		return $this->c.' Clients Export&eacute;s';

	}

	/**
	 * Génère les fichiers ENCFAC
	 *
	 * @return void
	 * @author Nico
	 **/
	function generate_encfac() {
		global $DB;
		
		$d = opendir($this->ledir);

		while ($name = readdir($d)) { # Efface tous les fichiers ENCFAC précédents
			if (($name != '.') and ($name != '..') and ($name != 'CLIENT.MOD')) {
				$delete = unlink($this->ledir.$name);
			}
		}
		
		foreach ($this->factures as $fact) {

			$fac = new facture($fact['id'], $fact['type']);

			if ($fact['type'] == 'NCV') {
				$debcred = '2';
				$debcredinv = '1';
			} else {
				$debcred = '1';
				$debcredinv = '2';
			}

			$facfile = $this->ledir.'ENCFAC'.$fac->horizfile.'.TXT';
			$ffac = fopen($facfile, "a");

			$t = 1;

			$string  = $fac->CodeHoriz.$this->csep ; 				# Numéro du client
			$string .= $fact['type'].$this->csep ;					# Code Facturier 'VEN' -> factures ; 'NCV' -> note credit
			$string .= shortFacId($fac->id).$this->csep ;						# Numéro de facture
			$string .= $t.$this->csep ;								# Sequence
			$string .= '400000'.$this->csep ;						# Compte
			$string .= '1'.$this->csep ;							# Rubrique
			$string .= $fac->intitule.$this->csep ;					# Libellé
			$string .= $fac->intitule.$this->csep ;					# Commentaire
			$string .= $fac->HorizDate.$this->csep ;				# Date facturation
			$string .= ''.$this->csep ;								# Date échéance
			$string .= $debcred.$this->csep ;						# Débit Crédit
			$string .= $fac->CompteHoriz['400000'].$this->csep ;	# Montant
			$string .= 'EUR'.$this->csep ;							# Code devise
			$string .= '1'.$this->csep ;							# Cours de change
			$string .= $fac->CompteHoriz['400000'].$this->csep ;	# Montant en EUR

			$string .= '0'.$this->csep ;							# Escompte
			$string .= ''.$this->csep ;								# Division analytique
			$string .= ''.$this->csep ;								# Compte analytique

			$string .= ''.$this->csep ;								# Quantité

			$write = fputs($ffac, $string.$this->lsep);				# write + Fin de Ligne


			######### Comptes #############
			foreach ($fac->CompteHoriz as $comptenum => $montant) {
				$tempdebcre = $debcredinv;
				## Montants Négatifs
				if (($montant < 0) and ($comptenum >= '700100')) {
					$montant = abs($montant);
					$tempdebcre = $debcred;
				}

				if (($montant > 0) and ($comptenum >= '700100')) {
					$t++;
					$string  = $fac->CodeHoriz.$this->csep ; 					# Numéro du client
					$string .= $fact['type'].$this->csep ;						# Code Facturier
					$string .= shortFacId($fac->id).$this->csep ;							# Numéro de facture
					$string .= $t.$this->csep ;									# Sequence
					$string .= $comptenum.$this->csep ;							# Compte
					$string .= $fac->rubhortva.$this->csep ;					# Rubrique
					$string .= $fac->intitule.$this->csep ;						# Libellé
					$string .= ''.$this->csep ;									# Commentaire
					$string .= $fac->HorizDate.$this->csep ;					# Date facturation
					$string .= ''.$this->csep ;									# Date échéance
					$string .= $tempdebcre.$this->csep ;						# Débit Crédit
					$string .= $montant.$this->csep ;							# Montant
					$string .= 'EUR'.$this->csep ;								# Code devise
					$string .= '1'.$this->csep ;								# Cours de change
					$string .= $montant.$this->csep ;							# Montant en EUR
					$string .= '0'.$this->csep ;								# Escompte
					$string .= 'E'.$this->csep ;								# Division analytique
					$string .= 'S-'.$this->secteurs[$fac->horizsecteur].$this->csep ;	# Compte analytique ***
					$string .= ''.$this->csep ;									# Quantité

					$write = fputs($ffac, $string.$this->lsep);					# write + Fin de Ligne
				}

				unset($tempdebcre);
			}

			######### TVA #############

			$t++;

			$string  = $fac->CodeHoriz.$this->csep ;				# Numéro du client
			$string .= $fact['type'].$this->csep ;					# Code Facturier
			$string .= shortFacId($fac->id).$this->csep ;						# Numéro de facture
			$string .= $t.$this->csep ;								# Sequence
			$string .= '451000'.$this->csep ;						# Compte
			$string .= '9'.$this->csep ;							# Rubrique *** a mod selon client
			$string .= $fac->intitule.$this->csep ;					# Libellé ***
			$string .= ''.$this->csep ;								# Commentaire
			$string .= $fac->HorizDate.$this->csep ;				# Date facturation
			$string .= ''.$this->csep ;								# Date échéance
			$string .= $debcredinv.$this->csep ;					# Débit Crédit
			$string .= $fac->CompteHoriz['451000'].$this->csep ;	# Montant
			$string .= 'EUR'.$this->csep ;							# Code devise
			$string .= '1'.$this->csep ;							# Cours de change

			$string .= $fac->CompteHoriz['451000'].$this->csep ;	# Montant TVA
			$string .= '0'.$this->csep ;							# Escompte
			$string .= ''.$this->csep ;								# Division analytique
			$string .= ''.$this->csep ;								# Compte analytique 
			$string .= ''.$this->csep ;								# Quantité

			$write = fputs($ffac, $string.$this->lsep);				# write + Fin de Ligne

			if ($fact['type'] == 'VEN') $this->f++;	
			if ($fact['type'] == 'NCV') $this->n++;

			fclose($ffac);

			unset($debcred);
			unset($debcredinv);

			######## Mise a jour de l'état d'exportation dans horizon
			$DB->inline("UPDATE `".(($fact['type'] == 'VEN')?"facture":"credit")."` SET `horizon` = 'Y' WHERE `idfac` = '".$fact['id']."' LIMIT 1");

		}
		
		return '<br> '.$this->f.' Factures Export&eacute;es<br> '.$this->n.' Notes de cr&eacute;dit Export&eacute;es';
	}
}

?>