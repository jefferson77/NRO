<?php
/**
* Classe de manipulation des fichiers 281.10
*/
class doc28110
{
	# Config
	var $tempFilesPath = '';
	var $nbrfiche = 0;

	# init
	var $toSplitFile = '';
	var $toSplitFolder = '';
	var $SplitPath = '';

	/**
	 * Stockage du fichier PDF provenant de Groupe-s
	 *
	 * @return void
	 * @author Nico
	 **/
	function __construct($annee)
	{
		global $notify, $error;

		$this->tempFilesPath = Conf::read('Env.root').'document/people/281.10/';
		if(!is_dir($this->tempFilesPath)) mkdir($this->tempFilesPath, 0777, true); 
		$this->periode = $annee;
	}
	
	/**
	 * Stocke le document Uploadé
	 *
	 * @return void
	 * @author Nico
	 **/
	function uploadFile ($fieldname)
	{
		$this->toSplitFolder = $this->tempFilesPath.'originaux/';
		if(!is_dir($this->toSplitFolder)) mkdir($this->toSplitFolder, 0777, true); 
		
		$this->toSplitFile = $this->toSplitFolder.'281.10-'.$this->periode.'.pdf';

		if(move_uploaded_file($_FILES[$fieldname]['tmp_name'], $this->toSplitFile)) {
			$notify[] = 'Upload effectué avec succès !';
		} else {
			$error[] = 'Echec de l\'upload !';
			return "ERROR";
		}
	}
	
	/**
	 * Découpage du Fichier PDF par page et renomage par people
	 *
	 * @return void
	 * @author Nico
	 **/
	function splitPDF()
	{
		$this->SplitPath = $this->tempFilesPath."split/".$this->periode."/";
	    if(!is_dir($this->SplitPath)) mkdir($this->SplitPath, 0777, true); 
	
		## recup le nombre de pages
		exec("pdftk '".$this->toSplitFile."' dump_data output | grep -i Num", $result);

		if (preg_match("/NumberOfPages: (\d*)/", $result[0], $m)) {
			$nbrpages = $m[1];

			for ($i=1; $i < $nbrpages; $i += 2) { 
				exec("pdftk ".$this->toSplitFile." cat ".$i."-".($i+1)." output ".$this->SplitPath."page_".prezero($i, 4).".pdf");
			}
		}
	}

	/**
	 * Identification des fiches de salaires
	 *
	 * @return void
	 * @author Nico
	 **/
	function batchIdentify($folder)
	{
		global $error;

		$lesfiles = dirFiles($folder, "/^page_\d{4}\.pdf/");
		
		foreach ($lesfiles as $pdfpage) {
			$ret = $this->findPeopleInfos($folder.$pdfpage);

			if (!empty($ret['regnat'])) {
				$this->storeFiche28110($folder.$pdfpage, $ret['regnat']);
			} else {
				$error[] = 'Aucun Registre National trouvé dans la page';
			}
		}
	}
	
	/**
	 * Extrait le Regnat du 281.10
	 *
	 * @return string : registre national
	 * @author Nico
	 **/
	function findPeopleInfos($file)
	{
		$ret = array();
		### Regnat
		exec("ps2ascii ".$file, $pdftexte);

		foreach ($pdftexte as $line) if (
				preg_match("/(\d{9})-(\d{2})/", addslashes(trim($line)), $m)
			) $ret['regnat'] = $m[1].$m[2];
			
		### people name
		foreach ($pdftexte as $line) if (preg_match("/^([A-Z]+ [A-Z]+)$/", addslashes(trim($line)), $m)) $ret['name'] = $m[1];
			
		### codepeople
		foreach ($pdftexte as $line) if (
				preg_match("/^CONFIDENTIEL 431\/67442\/(\d{*})/", addslashes(trim($line)), $m) or 
				preg_match("/^VERTROUWELIJK 431\/67442\/(\d{*})/", addslashes(trim($line)), $m)) $ret['codepeople'] = $m[1];
		return $ret;
	}
	
	/**
	 * Essaye de stocker la fiche de salaire en fonction du registre national
	 *
	 * @return void
	 * @author Nico
	 **/
	function storeFiche28110($file, $niss)
	{
		global $DB, $error;
		
		$peoples = $DB->getArray("SELECT idpeople, pnom, pprenom, codepeople FROM people WHERE nrnational = '".$niss."' ORDER BY codepeople");
		if (count($peoples) == 1) {
			$people = array_shift($peoples);
			$fichesPath = Conf::read('Env.root').'document/people/'.prezero($people['idpeople'], 5).'/';
		    if(!is_dir($fichesPath)) mkdir($fichesPath, 0777, true); 
			$destfile = $fichesPath.'281.10-'.$this->periode.'.pdf';
			if (rename($file, $destfile)) {
				$this->nbrfiche++;
			} else {
				$error[] = 'Impossible de renommer le fichier "'.$file.'" en "'.$destfile.'"';
			}
		} elseif(count($peoples) > 1) {
			$error[] = 'Plusieurs people correspondent au registre national ('.$registre.') : '.implode(", ", array_keys($peoples));
		} else {
			$error[] = 'Aucun People ne correspond au registre national : '.$niss;
		}
		return $peoples;
	}
}


?>