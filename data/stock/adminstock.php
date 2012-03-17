<?php
define('NIVO', '../../'); 

# Classes utilisées
include NIVO.'classes/vip.php';
include NIVO."classes/anim.php";

# Entete de page
$Titre = 'STOCK';
$Style = 'standard';
$PhraseBas = 'S&eacute;lection d un matos';
include NIVO."includes/entete.php" ;

$onemonthago  = date ("Y-m-01", strtotime("+1 day")); 

$_POST['dateticket'] = date("Y-m-d");

	if (!empty($_GET['idstockf'])) {$idstockf = $_GET['idstockf'];}
	if (!empty($_POST['idstockf'])) {$idstockf = $_POST['idstockf'];}
	
	if (!empty($_GET['idstockm'])) {$idstockm = $_GET['idstockm'];}
	if (!empty($_POST['idstockm'])) {$idstockm = $_POST['idstockm'];}

	if (!empty($_GET['idmatos'])) {$idmatos = $_GET['idmatos'];}
	if (!empty($_POST['idmatos'])) {$idmatos = $_POST['idmatos'];}


# Carousel des fonctions
switch ($_GET['act']) {
############## Ajout d'une famille #############################################
		case "familleadd": 
			$_POST['reference'] = 'new';
			$ajout = new db('stockf', 'idstockf');
			$ajout->AJOUTE(array('reference'));
			$PhraseBas = 'Nouveau matos';
			$idstockf = $ajout->addid;
			$_GET['idstockf'] = $ajout->addid;
			$_POST['idstockf'] = $ajout->addid;

			$PhraseBas = 'Stock : NOUVELLE Famille - d&eacute;tail';
			include "famille.php" ;
		break;
############## Modification et affichage d'une Mission #########################################
		case "famillemodif": 
			$modif = new db('stockf', 'idstockf');
			$modif->MODIFIE($idstockf, array('reference' , 'description' , 'stype'));	
			$PhraseBas = 'Stock : Famille - Modifi&eacute;';
			include "famille.php" ;
		break;
############## Recherche des unités #########################################
		case "familleshow": 
			$PhraseBas = 'Stock : Famille - d&eacute;tail';
			include "famille.php" ;
		break;
############## Recherche des unités #########################################
		case "famillesearch": 
			$PhraseBas = 'Stock : Recherche Famille';
			$did = '';
			include "famille-search.php" ;
		break;
############## Résultat de Recherche des unités #########################################
		case "famillesearchresult": 
			$PhraseBas = 'Stock : R&eacute;sultat de Recherche Famille';
			$did = '';
			include "famille-search-result.php" ;
		break;
############## Recherche des Projection #########################################
		case "stockprojectionsearch": 
			$PhraseBas = 'Stock : Projection par de Recherche Famille';
			$did = '';
			include "stock-projection.php" ;
		break;
############## Résultat de Recherche des Projection #########################################
		case "stockprojectionresult": 
			$PhraseBas = 'Stock : R&eacute;sultat de Projection par de Recherche Famille';
			$did = '';
			include "stock-projection.php" ;
		break;
#/## Famille ###
### unités ###
############## Recherche des unités #########################################
		case "unitsearch": 
			$PhraseBas = 'Stock : Recherche Unit&eacute;s';
			$did = '';
			include "unite-search.php" ;
		break;
############## Résultat de Recherche des unités #########################################
		case "unitsearchresultmodif": 
			$stocking = explode('-xsep-',$_POST['idstockm']);
			$_POST['idstockm'] = $stocking[0];
			$_POST['idstockf'] = $stocking[1];

			$modif = new db('matos', 'idmatos');
			$modif->MODIFIE($idmatos, array('idstockf' , 'idstockm' ));
		case "unitsearchresult": 
			$PhraseBas = 'Stock : R&eacute;sultat de Recherche Unit&eacute;s';
			$did = '';
			include "unite-search-result.php" ;
		break;
############## Résultat de Recherche des unités #########################################
		case "unitshow": 
			$PhraseBas = 'Stock : Fiche Unit&eacute;s';
			$did = '';
			include "unite-detail.php" ;
		break;
############## SUPPLIER & UNASSIGN #########################################
	############## SUPPLIER #########################################
		case "supplier": 
			$idticket = $_GET['idticket'];
			$_POST['idmatos'] = $_GET['idmatos'];
			$_POST['idstockf'] = $_GET['idstockf'];
			$_POST['idstockm'] = $_GET['idstockm'];
			$_POST['stockout'] = date("Y-m-d");
			$_POST['stockin'] = date("Y-m-d", strtotime("+3 days"));
			$_POST['user'] = 'supplier';
			$_POST['inuse'] = 1;
			if (!empty($idticket)) {
				$ajout = new db('stockticket', 'idticket');
				$ajout->AJOUTE(array('idstockf', 'idstockm', 'idmatos', 'stockout', 'stockin', 'dateticket', 'suser', 'inuse'));
				$message = $_GET['codematos'].' to supplier';
			}
	############## UNASSIGN #########################################
		case "unasign": 
			$idvip = $_GET['idvip'];
			$idticket = $_GET['idticket'];
			$_POST['stockin'] = date("Y-m-d");
			$_POST['inuse'] = 0;
			if (!empty($idticket)) {
				$modif = new db('stockticket', 'idticket');
				$modif->MODIFIE($idticket, array('stockin', 'idpeople', 'inuse'));
				$message = $_GET['codematos'].' unasigned';
			}
		include "unite-detail.php" ;
	break;
############## Recherche d'une mission #########################################

############## unités UPDATE#########################################
		case "unitmodif": 
			$PhraseBas = 'Stock : Fiche Unit&eacute;s';
			$idmatos = $_GET['idmatos'];
			switch ($_POST['action']) {
				case "Modifier": 
						$modif = new db('matos', 'idmatos');
						$modif->MODIFIE($idmatos, array('codematos', 'mnom', 'autre', 'complet'));
					include "unite-detail.php" ;
				break;
			}	
		break;
#			ma.idmatos, ma.idvip, ma.codematos, ma.mnom, ma.dateout AS madateout, ma.autre, ma.idpeople, 
#			ma.situation, ma.complet, ma.idstockm, 

##########
		default: 
			$PhraseBas = 'Stock / Structure / Notes';
			$did = '';
			include "build.php" ;
}
?>
<!-- Barre des Boutons -->
<div id="topboutons">
<table border="0" cellspacing="1" cellpadding="0">
	<tr>
		<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=familleadd"><img src="<?php echo STATIK ?>illus/ajouter.gif" alt="ajouter" width="32" height="32" border="0"><br>Ajouter</a></td>
		<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=famillesearch"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
		<?php if ($_SESSION['stockfamillequid'] != "") { ?>
			<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=famillesearchresult&skip=skip"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Retour Liste</a></td>
		<?php } ?>
		<td class="on" width="32" height="32" align=center><font color="black"><b><= FAMILLE&nbsp;&nbsp;<br><hr>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UNITE =></b></font><br>&nbsp;</td>
		<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=unitsearch"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
		<?php if ($_SESSION['stockquid'] != "") { ?>
			<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=unitsearchresult&skip=skip&attribfamille=<?php echo $attribfamille; ?>"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Retour Liste</a></td>
		<?php } ?>
		<?php if (($_GET['act'] == 'show') OR ($_GET['act'] == 'modif')) { ?>
			<?php if ($infos['idpeople'] < 1) { ?>
				<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=delete&did=<?php echo $did ?>" onClick="return confirm('Etes-vous sur de vouloir effacer ce mat&eacute;riel?')"><img src="<?php echo STATIK ?>illus/trash.gif" alt="search" width="32" height="32" border="0"><br>Supprimer</a></td>
			<?php } ?>
		<?php } ?>
		<td class="off" width="100" border="0"></td>
		<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=unitsearchresult&skip=out"><img src="<?php echo STATIK ?>illus/taille-t-shirt-rouge.gif" alt="search" width="32" height="32" border="0"><br>OUT</a></td>
		<td class="off" width="100" border="0"></td>
		<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=stockprojectionsearch"><img src="<?php echo STATIK ?>illus/prevision.gif" alt="avenir" width="32" height="32" border="0"><br>Projection</a></td>
	</tr>
</table> 
</div>
<?php

# Pied de Page
include NIVO."includes/pied.php" ;
?>
