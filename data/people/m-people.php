<?php
function makePeopleSearch()
{
	global $DB;
	## init vars
	if (!isset($_GET['sexe'])) 		$_GET['sexe'] = '';
	if (!isset($_GET['casting'])) 	$_GET['casting'] = '';
	if (!isset($_GET['action'])) 	$_GET['action'] = '';
	if (!isset($_POST['notemerch'])) $_POST['notemerch'] = '';
	$quid = '';
	$quod = '';

	## Reset Session Vars
	unset($_SESSION['searchpeoplequid']);
	unset($_SESSION['searchpeoplequod']);

	$searchfields = array (
		'p.pnom' 			=> 'pnom', 
		'p.pprenom' 		=> 'pprenom', 
		'p.codepeople' 		=> 'codepeople', 
		'p.idpeople' 		=> 'idpeople',
		'p.notegenerale' 	=> 'notegenerale',
		'p.lbureau' 		=> 'lbureau',
		'p.gsm' 			=> 'gsm',
		'm.vipactivite' 	=> 'vipactivite',
		'p.err' 			=> 'err',
		'p.nrnational' 		=> 'nrnational',
		'p.permis' 			=> 'permis',
		'p.voiture' 		=> 'voiture',
		'p.physio' 			=> 'physio',
		'p.email'			=>	'email',
		'p.tjupe'			=>	'tjupe',
		'p.tveste'			=>	'tveste',
		'p.lcheveux'		=>	'lcheveux',
		'p.ccheveux'		=>	'ccheveux',
		'p.tel'				=>	'tel',
		'p.taille' 			=> 'taille'
		);

	$quid = $DB->MAKEsearch($searchfields);

	## sexe
	if ((!empty($_POST['sexe'])) AND ($_POST['sexe'] != 'x')) { if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.sexe LIKE '".$_POST['sexe']."'";
	}

	## age
	if (!empty($_POST['age'])) {
		$searchage = $DB->quidage($_POST['age'], 'p.ndate');
		
		if (!empty($searchage)) {
			if (!empty($quid)) $quid .= " AND ";
			$quid .= $searchage;
		}
	}

	## langues
	$langfields = array('lfr', 'lnl', 'len', 'ldu', 'lit', 'lsp');

	foreach ($langfields as $langue) {
		if (!empty($_POST[$langue])) { if (!empty($quid)) $quid .= " AND ";
			$quid .= "p.".$langue." >= ".$_POST[$langue];
			$quod .= " ".$langue." >= ".$_POST[$langue];
		}
	}

	## Codes Postaux
	if (!empty($_POST['cp1a'])) {
		if (!empty($quid)) $quid .= " AND ";
		if (!empty($_POST['cp1b'])) {
			$quid .= "(p.cp1 BETWEEN '".$_POST['cp1a']."' AND '".$_POST['cp1b']."')";
		} else {
			$quid .= "((p.cp1 = '".$_POST['cp1a']."') OR (p.cp2 = '".$_POST['cp1a']."')) ";
		}
	}

	## Localites
	if (!empty($_POST['ville1'])) {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "((p.ville1 LIKE '%".$_POST['ville1']."%' OR  p.ville2 LIKE '%".$_POST['ville1']."%'))";
	}

	if ($_POST['notemerch'] == 'yes') {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.notemerch != ''";
	}

	## Categorie
	if (!empty($_POST['categorie'])) {
		$searchstr = ($_POST['categorie'][0]=='1')?'1':'_';
		$searchstr.=($_POST['categorie'][1]=='1')?'1':'_';
		$searchstr.=($_POST['categorie'][2]=='1')?'1':'_';
		if (!empty($quid)) $quid .= " AND ";
		$quid = $quid."p.categorie LIKE '".$searchstr."'";
	}

	## out
	if ($_POST['isout'] == 'out') {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.isout LIKE 'out'";
	}

	if ($_POST['isout'] == 'notout') {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.isout NOT LIKE '%out%'";
	}

	## BE CH DY
	if (!empty($_POST['beaute'])) {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.beaute >= ".$_POST['beaute'];
	}

	if (!empty($_POST['charme'])) {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.charme >= ".$_POST['charme'];
	}

	if (!empty($_POST['dynamisme'])) {
		if (!empty($quid)) $quid .= " AND ";
		$quid .= "p.dynamisme >= ".$_POST['dynamisme'];
	}

	$_SESSION['searchpeoplequid'] = $quid;
	$_SESSION['searchpeoplequod'] = $quod;
}
?>