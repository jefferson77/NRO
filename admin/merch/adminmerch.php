<?php
# Entete de page
define('NIVO', '../../'); 
$Titre = 'ADMIN MERCH';
$Style = 'admin';

# Entete
include NIVO."includes/entete.php" ;

# Classes utilisées
include NIVO."classes/merch.php";
include NIVO."classes/hh.php";

## EAS GB
include NIVO."merch/easclients.php";
$clients = array_flip($clients);
$gbs = $clients['GB EAS'];

# Carousel des fonctions
$onemonthago  = date("Y-m-01", strtotime("+2 days"));
$oneweekago = date("Y-m-d", strtotime("last Monday"));

switch ($_GET['act']) {
###############################################################################################################################################################################
# 1 ########## Facturation #############################################																									###
#####   																																									###
		case "facture": 
			## Validation d'une facture
			if (($_POST['modstate'] == 'Valider') and ($_POST['state'] > 0)) {
				$DB->inline("UPDATE merch SET facturation = '".$_POST['state']."' WHERE idmerch IN (".implode(", ", $_POST['ids']).");");
			}
			## Affichage de la liste des missions a facturer
			$mode = 'ADMIN';
			include "factoring.php" ;

			$PhraseBas = 'FACTORING des Merch';
		break;
		
############## Listing des PRE-FACTURATION #############################################
		case "prefac": 
			if ($_POST['valider'] == 'Modifier') {
				foreach ($_POST as $key => $value) {
					if (substr($key, 0, 4) == 'ids-') {
						$ids = explode('-', substr($key, 4));
						$DB->inline("UPDATE merch SET facturation = '".$value."' WHERE idmerch IN(".implode(", ", $ids).");");
					}
				}	
			}
	
			include "prefactoring.php" ;
			$PhraseBas = 'PRE-FACTORING des MERCH';
		break;
############## Menu pour print facture - etat = 6 #############################################
		case "facready": 
			include "facture_inc.php" ;
			$PhraseBas = 'PRINT de FACTORING des MERCH';
		break;
############## Impression des factures mensuelles EAS (GB et CARREFOUR) #############################################
		case "faceas": 
			include "faceas.php" ;
			$PhraseBas = 'Facturation EAS';
		break;
#####   																																									###
############## Facturation #############################################																									###
###############################################################################################################################################################################

############## Listing des PURGE FL #############################################
	case "purgefl1": 
		include "purgefl1.php" ;
		$PhraseBas = 'PURGE FL des MERCH';
	break;

############## SWITCH des PURGE FL  #############################################
	case "purgefl2": 
		foreach($_POST as $key => $valeur) {
			if (substr($key, 0, 10) == 'factswitch') {
				$dd = explode('-', $key);
				$idmerch = $dd[1];
				$_POST['facturation'] = $valeur;
				$facturation = $valeur;
				$modif = new db('merch', 'idmerch');
				$modif->MODIFIE($idmerch, array('facturation' ));
			}
		}	
		include "purgefl1.php" ;
		$PhraseBas = 'PURGE FL des MERCH';
	break;

############## Listing des PURGE OUT #############################################
	case "purgeout1": 
		include "purgeout1.php" ;
		$PhraseBas = 'PURGE OUT des MERCH';
	break;

############## SWITCH des PURGE OUT  #############################################
	case "purgeout2": 
		foreach($_POST as $key => $valeur) {
			if (substr($key, 0, 10) == 'factswitch') {
				$dd = explode('-', $key);
				$idmerch = $dd[1];
				$_POST['facturation'] = $valeur;
				$facturation = $valeur;
				$modif = new db('merch', 'idmerch');
				$modif->MODIFIE($idmerch, array('facturation' ));
			} else {
				unset($facturation);
			}

				### etat = 26 ==> Duplication zoutmerch d'une Mission merch et Delete d'une merch
				if ($facturation == '26') {

					############## Recherche infos d'une Mission merch 
					$merch1 = new db();
					$fields = "`idagent`, `idclient`, `idcofficer`, `idshop`, `idpeople`, `produit`, `datem`, `weekm`, `hin1`, `hout1`, `hin2`, `hout2`, `ferie`, `recurrence`,
					`genre`, `kmpaye`, `kmfacture`, `livraison`, `diversfrais`, `note`, `facturation`";

					$merch1->inline("INSERT INTO `zoutmerch` (".$fields.") SELECT ".$fields." FROM `merch` WHERE `idmerch` = ".$idmerch);
					$merch1->inline("DELETE FROM `merch` WHERE `idmerch` = ".$idmerch);

				}
				#/## etat = 26 ==> Duplication zoutmerch d'une Mission merch et Delete d'une merch
		}
		include "purgeout1.php" ;
		$PhraseBas = 'PURGE OUT des MERCH';
	break;
}

?>
<div id="topboutonsadmin">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
	<?php 
	if ($_SESSION['jobquid'] != "") { ?>
			<td class="on"><a href="?act=facture&action=skip"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Retour Liste</a></td>
	<?php } ?>
			<td class="on"><a href="?act=facture"><img src="<?php echo STATIK ?>illus/list.png" alt="search" width="42" height="29" border="0"><br>Listing</a></td>
			<td class="on"><a href="?act=prefac"><img src="<?php echo STATIK ?>illus/listepresence.gif" alt="search" width="32" height="32" border="0"><br>Pr&eacute;-factures</a></td>
			<td class="on"><a href="?act=facready"><img src="<?php echo STATIK ?>illus/rapportmail.gif" alt="search" width="32" height="32" border="0"><br>Factures</a></td>
			<td class="on" width="32"><br></td>
			<td class="on"><a href="?act=purgeout1"><img src="<?php echo STATIK ?>illus/trash.gif" alt="out" width="32" height="32" border="0"><br>OUT</a></td>
			<td class="on"><a href="?act=purgefl1"><img src="<?php echo STATIK ?>illus/girl.png" alt="out" width="32" height="32" border="0"><br>F L</a></td>
		</tr>
	</table> 
</div>
<?php include NIVO."includes/pied.php" ; ?>