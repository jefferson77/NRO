<?php
/**
* Classe de manipulation des fichiers salaires
*/
class FicheSalaire
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
	function __construct($annee, $mois)
	{
		global $notify, $error;

		$this->tempFilesPath = Conf::read('Env.root').'document/people/fichesalaire/';
		if(!is_dir($this->tempFilesPath)) mkdir($this->tempFilesPath, 0777, true);
		$this->periode = $annee.$mois;
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

		$this->toSplitFile = $this->toSplitFolder.'FichesSalaire-'.$this->periode.'.pdf';

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
		exec("pdftk ".$this->toSplitFile." burst output ".$this->SplitPath."page_%04d.pdf");
		if (file_exists("doc_data.txt")) unlink("doc_data.txt");
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
	 * Extrat le Regnat de la fiche de salaire
	 *
	 * @return string : registre national
	 * @author Nico
	 **/
	function findPeopleInfos($file)
	{
		$ret = array(
			'regnat'     => '',
			'name'       => '',
			'codepeople' => '',
		);

		exec("ps2ascii ".$file, $pdftexte);

		foreach ($pdftexte as $line) {
		### Regnat
			if (
				preg_match("/Rijksregisternr\. (\d{6})-(\d{3})-(\d{2})/", addslashes(trim($line)), $m) ||
				preg_match("/Nr\^registrenational (\d{6})-(\d{3})-(\d{2})/", addslashes(trim($line)), $m)
			) $ret['regnat'] = $m[1].$m[2].$m[3];
		### people name
			if (
				preg_match("/^([A-Z\-]+ [A-Z\-]+)$/", addslashes(trim($line)), $m)
			) $ret['name'] = $m[1];
		### codepeople
			if (
				preg_match("/^CONFIDENTIEL 67442\/3-(\d{4})/", addslashes(trim($line)), $m) ||
				preg_match("/^VERTROUWELIJK 67442\/3-(\d{4})/", addslashes(trim($line)), $m)
			) $ret['codepeople'] = $m[1];
		}

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
			$destfile = $fichesPath.'FicheSalaire-'.$this->periode.'.pdf';
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