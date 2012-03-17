<div id="centerzonelarge">
<?php

##################################################################################################################################################################
##### TEMPORAIRE, A REVERIFIER ###################################################################################################################################
##################################################################################################################################################################

	### Add fiche contact
	if ($_GET['contact'] == 'yes') { 
	### recherche fiche contact
	
		$sql = "SELECT * FROM `jobcontact` WHERE `idanimation` = $idanimjob AND `idpeople` = '".$_GET['idpeople']."'"; $jobcontact1 = new db(); $jobcontact1->inline($sql);
		$infocontact1 = mysql_fetch_array($jobcontact1->result) ; 

		### si 0 ADD
			if (mysql_num_rows($jobcontact1->result) == 0) {
			
				$_POST['idanimation'] = $_REQUEST['idanimjob'];
				$_POST['idpeople'] = $_GET['idpeople'];
				$ajout = new db('jobcontact', 'idjobcontact');
				$ajout->AJOUTE(array('idanimation', 'idpeople'));
			}
	#/## recherche fiche contact
	} 
	#/## Add fiche contact

	### Update fiche contact
	if ($_GET['contact'] == 'modif') { 

	### recherche fiche contact
		$sql = "SELECT * FROM `jobcontact` WHERE `idanimation` = ".$_REQUEST['idanimjob']." AND `idpeople` = '".$_POST['idpeople']."'"; $jobcontact1 = new db(); $jobcontact1->inline($sql);
		$infocontact1 = mysql_fetch_array($jobcontact1->result) ; 

		### si 1 UPDATE
			if (mysql_num_rows($jobcontact1->result) == 1) {
				$idjobcontact = $infocontact1['idjobcontact'];
				$modif = new db('jobcontact', 'idjobcontact');
				$modif->MODIFIE($idjobcontact, array('etatcontact', 'notecontact'));
			}
	#/## recherche fiche contact
	} 

##################################################################################################################################################################
##################################################################################################################################################################
##################################################################################################################################################################

$classe = 'standard' ;
if (!empty($_POST['etape'])) {$_GET['etape'] = $_POST['etape'];}

$geo = new Geocalc;		
$people = new db();	
### Date de la mission VIP pour disponibilités
$datea = $DB->getRow('SELECT a.datem, s.glat, s.glong 
					FROM animation a 
					LEFT JOIN shop s ON s.idshop=a.idshop
					WHERE a.idanimation = '.$idanimation);
$mm = explode("-", $datea['datem']);
$dd = 'd'.str_repeat('0', 2 - strlen($mm[2])).$mm[2];
##echo '<pre>', print_r($datea), '</pre>';

if($_GET['etape']!=="listepeople") {
	### Première étape : formulaire de recherche de People
	include("v-peopleSearch.php");
		
} else if($_GET['etape']=="listepeople") {
	### Deuxième étape : Afficher la liste des People correspondant à la recherche
	include("v-peopleList.php");
}
	?>
</div>