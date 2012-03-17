<?php
# Entete de page
define('NIVO', '../');
$Titre = 'Groupe-S';
$PhraseBas = 'Test des Peoples';
#$Style = 'admin';
# Classes utilisées

# Entete de page
session_start();

$errorfile = 'fichiers/rapporterreurs.html';

include NIVO."includes/entete.php" ;
include 'm-salaire.php';


# ################### Formulaire de choix période a vérifier #####
if (!isset($_GET['mois'])) {
?>
<div id="leftmenu">

</div>
<div id="infozone">
<table border="0" align="center">
<tr>
<?php
$lestables = $DB->tableliste("/^salaires/", "grps"); 

foreach ($lestables as $value) {
	echo '<tr><td>'.nomtable($value).': </td><td><a href="'.$_SERVER['PHP_SELF'].'?mois='.$value.'">Tester</a></td>
	</tr>
';

}
?>
</tr>
</table>
</div>
<div id="infobouton">
</div>

<?php }
# ################### Fin Formulaire de choix période a vérifier #

else
{
?>
<div id="leftmenu">

</div>
<div id="infozone">
<?php 

	# ################### Recherche des Promoboys #########
	
	$_SESSION['daterech'] = $daterecherche ;
		
	$peoples = array();
	
	$nomtable = $_GET['mois'];
	$afficher = new db('', '', 'grps');
	$afficher->inline("SELECT idpeople FROM  `$nomtable`");

	while ($row = mysql_fetch_array($afficher->result)) {
		array_push ($peoples, $row['idpeople']) ; 
	}
	
	$listepeople = array_unique($peoples) ; # remove duplicates
	sort ($listepeople) ;
	if ($listepeople[0] == '') {array_shift($listepeople); }
	
	$nombrepeople = count ($listepeople) ;
	echo 'people trouv&eacute;s = '.$nombrepeople.'<br><br>' ;
	$_SESSION['peopletest'] = $nombrepeople ;
	# ################### Fin Recherche des Promoboys #####
	
	# ################### Ecriture de la liste des people a checker dans un ficher #####
	$filename = 'fichiers/people.php';
	$delete = unlink($filename); 
	$fp = fopen($filename, "a");
	foreach ($listepeople as $value) {
		$string = $value.'
';
		$write = fputs($fp, $string);
	}
	fclose($fp);
	
	if (file_exists($errorfile)) { 
		$delete = unlink($errorfile); 
	}
	# ################### Fin Ecriture de la liste des people a checker dans un ficher #####
?>
</div>
<div id="infobouton">
<a href="testliste.php">Commencer le traitement</a>
</div>
<?php

}



# Pied de page
include NIVO."includes/pied.php" ; 
?>