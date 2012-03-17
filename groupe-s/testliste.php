<?php
# Entete de page
define('NIVO', '../');
$Titre = 'Groupe-S';
$PhraseBas = 'Stockage du tableau Groupe-S';

include NIVO."includes/entete.php" ;
# Classes utilisées
include_once(NIVO."classes/test.php");

$classe = "standard";

$filename = 'fichiers/people.php';
$fp = 'fichiers/rapporterreurs.html';

$traiter = 10;

$listepeople = file ($filename);

$listepeople = array_unique($listepeople) ; # remove duplicates
sort ($listepeople, SORT_NUMERIC) ;
if (trim($listepeople[0]) == '') array_shift($listepeople);


$nombrepeople = count ($listepeople) ;

?>
<div id="leftmenu"> </div>
<div id="infozone"> <br>
<?php
echo 'people restant a v&eacute;rifier = '.$nombrepeople.'<br>';

foreach ($listepeople as $thispeople) {

	$thispeople = trim ($thispeople);
	if ($thispeople > 0) {

 		# Inline Infos People
 		$people = new db();
		$people->inline("SELECT `idpeople` , `pprenom` , `pnom` , `catsociale` , `sexe` , `adresse1` , `ville1` , `cp1` , `banque`, `nationalite` , `ncidentite` , `ndate` , `ncp` , `pays1` , `nrnational` , `npays` , `dateentree` , `codepeople` , `etatcivil` , `num1` , `bte1` , `lbureau` , `pacharge` , `eacharge` FROM `people` WHERE `idpeople` = $thispeople");
		$row = mysql_fetch_array($people->result);

		$Verif = new test($row['idpeople'], $fp);
		# Teste tous les champ !! ordre a de l'importance
		$Verif->checkPRE($row['pprenom']);
		$Verif->checkNOM($row['pnom']);
		$Verif->checkCCC($row['catsociale']);
		$Verif->checkSEX($row['sexe']);
		$Verif->checkRUE($row['adresse1']);
		$Verif->checkLOC($row['ville1']);
		$Verif->checkCPO($row['cp1']);
		$Verif->checkNCF($row['banque']);
		$Verif->checkNAT($row['nationalite']);
		$Verif->checkNUI($row['ncidentite']);
		$Verif->checkDTN($row['ndate']);
		$Verif->checkNCP($row['ncp'], $row['npays']);
		$Verif->checkCPP($row['pays1']);
		$Verif->checkRNA($row['nrnational'], $row['ndate'], $row['sexe'], $row['npays']);
		$Verif->checkZ05($row['dateentree']);
		$Verif->checkRPE($row['codepeople']);
		$Verif->checkETC($row['etatcivil']);
		$Verif->checkNUR($row['num1']);
		$Verif->checkNUB($row['bte1']);
		$Verif->checkLGE($row['lbureau']);
		$Verif->checkAPC($row['pacharge']);
		$Verif->checkNEC($row['eacharge']);

		$Verif->afficher();

		# Fin Vérification de la validité des infos pour chaque promoboy
	} else {echo 'People Vide : '.$thispeople.'<br>';}
}



# ################### Ecriture de la liste des people a checker dans un ficher #####
unset($_SESSION['peopletest']);

?>
</div>
<div id="infobouton">
<a href="rapport.php">Afficher le tableau des r&eacute;sultats</a>
</div>

<?php
# ################### Fin Ecriture de la liste des people a checker dans un ficher #####

# Pied de page
include NIVO."includes/pied.php" ;
?>