<?php
class tva
{
################################# 

	var $acp = array('BE', 'FR', 'NL', 'LU', 'DE', 'IT', 'NON', 'UK', 'IE', 'CZ', 'PO', 'MT', 'ATU','US','TUR', 'GR', 'ES');
	var $errtva = array();
	
# Fonction Principale
	function tvacheck ($codepays, $numtva, $idclient, $societe, $astva) {
		if (($astva != '3') and ($astva != '7') and ($astva != '8')) {
			if (in_array($codepays, $this->acp)) { 
			#>## Check N° TVA
	
				switch ($codepays) {
				
					case "BE":
					### Teste la validité
						$numtva = $this->cleannombre($numtva);
						## ajout du pré 0 en cas d'ancien num
						## test de la forme
						if (preg_match("/([0-9]{3})([0-9]{3})([0-9]{3})/", $numtva, $regs)) {
						
						## test du num de controle
							$reste = substr($numtva, -2) ;
							$nombre = substr($numtva, 0, -2) ;
							if (fmod($nombre, 97) == 0) {
								$mod = 97;
							} else {
								$mod = 97 - fmod($nombre, 97);
							}
							if ($mod != $reste) {
								$this->errtva[$idclient]['err'] = 'Erreur sur le chiffre de controle';
								$this->errtva[$idclient]['soc'] = $societe;
								$this->errtva[$idclient]['num'] = $codepays.' '.$numtva;
							} else {
								$formtva = '0'.$regs[1].' '.$regs[2].' '.$regs[3];
								if ($formtva != $numtva) {
									$client = new db();
									$client->inline("UPDATE `client` SET `tva` = '$formtva' WHERE `idclient` = '$idclient';");
								}
							}
						} else {
							$this->errtva[$idclient]['err'] = 'Doit &ecirc;tre sous la forme XXXX XXX XXX';
							$this->errtva[$idclient]['soc'] = $societe;
							$this->errtva[$idclient]['num'] = $codepays.' '.$numtva;
						}
					break;
				
					case "FR": # France
					break;
					
					case "LU": # Luxembourg
					break;
					
					case "NL": # Pays-Bas
					break;
					
					case "IT": # Italie
					break;
					
					case "DE": # Deutchland
					break;
					
					case "UK": # United Kingdom
					break;
					
					case "IE": 
					break;
					
					case "CZ": # République Chèque
					break;
					
					case "PO": # Pologne
					break;
					
					case "ATU": 
					break;
					
					case "MT": # Malte
					break;
					
					case "US": # USA 
					break;
					
					case "ES": # Espagne
					break;
					
					case "GR": # Grèce
					break;
					
					case "TUR": # Turquie mais bon, c'est non officiel comme nomenclature (ya pas de préfix en turquie)
					break;
					
					default: 
				}
				
			#<## Check N° TVA
			} else {
				if (!empty($codepays)) {
					$this->errtva[$idclient]['err'] = $codepays.' : Pays non trait&eacute;';
					$this->errtva[$idclient]['soc'] = $societe;
					$this->errtva[$idclient]['num'] = $codepays.' '.$numtva;
				} else {
					$this->errtva[$idclient]['err'] = 'Code Pays Manquant';
					$this->errtva[$idclient]['soc'] = $societe;
					$this->errtva[$idclient]['num'] = $codepays.' '.$numtva;
				}
			}
		}
	}
	
	function cleannombre($nombre) {
		$carrep = array('-', '.', ' ');
		$crep = array_merge ($this->acp, $carrep); 
		
		$nombre = str_replace($crep, "", $nombre); 
		$nombre = ltrim($nombre, '0');
		return $nombre;
	}
}
?>