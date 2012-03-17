<?php
## Formater une heure (venant de DB)
	function ftime($temps) {
		$explo = explode(":", $temps);
			if (strlen($explo[1]) == '0') { $explo[1] = '00';}
			if (strlen($explo[0]) == '0') { $explo[0] = '00';}
		$formated = $explo[0].':'.$explo[1];
		return $formated;
	}
## Formater une heure (VERS la DB)
	function ftimebk($temps) {
		$explo = explode(":", $temps);
			if (strlen($explo[2]) == '0') { $explo[2] = '00';}
			if (strlen($explo[1]) == '0') { $explo[1] = '00';}
			if (strlen($explo[0]) == '0') { $explo[0] = '00';}
		$formated = $explo[0].':'.$explo[1].':'.$explo[2];
		return $formated;
	}
## Formater une date (venant de DB)
	function fdate($jour) {
		$explo = explode("-", $jour);
		$formated = $explo[2].'/'.$explo[1].'/'.$explo[0];
		if ($formated == '//'){$formated = '';}
		return $formated;
	}
## Formater une date (VERS la DB)
	function fdatebk($jour) {
		$explo = explode("/", $jour);
			if (strlen($explo[2]) == '0') { $explo[2] = '2003';}
			if (strlen($explo[2]) == '1') { $explo[2] = '200'.$explo[2];}
			if (strlen($explo[2]) == '2') { $explo[2] = '20'.$explo[2];}
			if (strlen($explo[1]) == '1') { $explo[1] = '0'.$explo[1];}
			if (strlen($explo[0]) == '1') { $explo[0] = '0'.$explo[0];}
		$formated = $explo[2].'-'.$explo[1].'-'.$explo[0];
		if ($formated == '2003--'){$formated = '';}
		return $formated;
	}
## Formater un Montant et ne pas afficher les 0 (venant de DB)
	function feuro ($montant) {
		if ($montant > 0) {
			$mnt = number_format($montant, 2, ',', ' ');
			return $mnt.' &euro;';
		}
	}
	
## Formater un Montant et ne pas afficher les 0 (venant de DB) pour l'impression
	function fpeuro ($montant) {
		if ($montant > 0) {
			$mnt = number_format($montant, 2, ',', ' ');
			return $mnt.' Eur';
		}
	}
	
## Formater un nombre et ne pas afficher les 0 (venant de DB)
	function fnbr ($montant) {
		if (($montant > '0,00') OR ($montant < '0,00')) {
			$explo = explode(".", $montant);
			if ($explo[1] > 0) {
				$mnt = number_format($montant, 2, ',', ' ');
			} else {
				$mnt = $explo[0];
			}
			return $mnt;
		}
	}

## Formater un nombre les , en . (VERS la DB)
	function fnbrbk ($montant) {
		if (($montant > '0,00') OR ($montant < '0,00')) {
			$explo = explode(",", $montant);
			if ($explo[1] > 0) {
				$mnt = $explo[0].'.'.$explo[1];
			} else {
				$mnt = $explo[0];
			}
		return $mnt;
		}
	}
	
## Formater un nombre négatif 
	function fnega($nombre) {
		if ($nombre < 0) {
			$formated = '<Font color="red">'.$nombre.'</font>';
		} else {
			$formated = $nombre;
		}
		return $formated;
	}

## Formater une idfacture pour print
	function ffac($idfacture) {
			if (strlen($idfacture) == '2') { $zero = '000';}
			if (strlen($idfacture) == '3') { $zero = '00';}
			if (strlen($idfacture) == '4') { $zero = '0';}
		$formated = $zero.$idfacture;
		return $formated;
	}

?>
