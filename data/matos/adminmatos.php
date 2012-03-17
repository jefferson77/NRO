<?php
define('NIVO', '../../'); 

# Classes utilisées
include NIVO.'classes/vip.php';
include NIVO."classes/anim.php";

# Entete de page
$Titre = 'MATERIEL';
$Style = 'standard';
$PhraseBas = 'S&eacute;lection d un matos';
include NIVO."includes/entete.php" ;

# Carousel des fonctions
switch ($_REQUEST['act']) {
############## Effacement d'un matos #############################################
		case "delete": 
			$infos = $DB->getRow("SELECT * FROM `matos` WHERE `idmatos` = ".$_REQUEST['idmatos']);

			if (($infos['idvip'] + $infos['idanimation'] + $infos['idmerch']) > 0) {
				$message = 'Materiel NON vide NE peut pas &ecirc;tre supprim&eacute;';
				include "v-detail.php" ;
			} else {
				$DB->inline("DELETE FROM `matos` WHERE `idmatos` = ".$_REQUEST['idmatos']);
				/*
					TODO : insert journal
				*/
				if (!empty($_SESSION['matosquid'])) {
					include "v-list.php" ;
				} else {
					include "v-search.php" ;
				}
			}
			$PhraseBas = 'matos Effac&eacute;';
		break;
############## Ajout d'une Mission #############################################
		case "add": 
			$_REQUEST['idmatos'] = $DB->ADD('matos');

			$PhraseBas = 'Nouveau matos';
			include "v-detail.php" ;
		break;
############## Affichage d'une Mission #########################################
		case "show": 
			$PhraseBas = 'Detail d\'un matos';
			include "v-detail.php" ;
		break;
############## Retour d'un matos d'un matos #########################################
		case "retour":
			$DB->inline("UPDATE matos SET dateout = NULL, idvip = '', idmerch = '', idanimation = '', idpeople = '' WHERE idmatos = ".$_REQUEST['idmatos']);
			$PhraseBas = 'Matos retourné';
			if (!empty($_SESSION['matosquid'])) {
				include "v-list.php" ;
			} else {
				include "v-search.php" ;
			}
		break;
############## Modification et affichage d'une Mission #########################################
		case "modif": 
			$DB->MAJ('matos');
			$PhraseBas = 'Materiel Modifi&eacute;';
			include "v-list.php" ;
		break;
############## Afficher le listing de recherche #########################################
		case "list": 
			$PhraseBas = 'Liste matos';
			include "v-list.php" ;
		break;
############## Recherche d'une mission #########################################
		case "search": 
		default: 
			$PhraseBas = 'Recherche matos';
			include "v-search.php" ;
}
?>
<div id="topboutons">
<table border="0" cellspacing="1" cellpadding="0">
	<tr>
		<td class="on"><a href="?act=add"><img src="<?php echo STATIK ?>illus/ajouter.gif" alt="ajouter" width="32" height="32" border="0"><br>Ajouter</a></td>
		<td class="on"><a href="?act=search"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
<?php 
if (!empty($_SESSION['matosquid'])) { ?>
		<td class="on"><a href="?act=list"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Retour Liste</a></td>
<?php } 
if ((($_GET['act'] == 'show') OR ($_GET['act'] == 'modif')) and ($infos['idpeople'] < 1)) { ?>
		<td class="on"><a href="?act=delete&idmatos=<?php echo $infos['idmatos'] ?>" onClick="return confirm('Etes-vous sur de vouloir effacer ce mat&eacute;riel?')"><img src="<?php echo STATIK ?>illus/trash.gif" alt="search" width="32" height="32" border="0"><br>Supprimer</a></td>
	<?php } ?>
<?php
if (($infos['idmatos'] > 0) and ($infos['idpeople'] > 0)) {
	?><td class="on"><a href="?act=retour&idmatos=<?php echo $infos['idmatos'] ?>"><img src="<?php echo STATIK ?>illus/retour.png" alt="retour" width="32" height="32" border="0"><br>Rentré</a></td><?php
}

?>

	</tr>
</table> 
</div>
<?php

# Pied de Page
include NIVO."includes/pied.php" ;
?>
