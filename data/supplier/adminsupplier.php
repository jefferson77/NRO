<?php
define('NIVO', '../../');

# Entete de page
$Titre = 'SUPPLIERS';
$Style = 'standard';

include NIVO."includes/entete.php" ;

include(NIVO."print/commun/detail-suppliers.php");
include(NIVO."print/commun/bonlivraison-liste.php");

# Carousel des fonctions
switch ($_REQUEST['act']) {
	# < # pas encore vérifié
		case "bdc":
			include "printdet.php";
		break;
	# > # pas encore vérifié
	
		case "add": 
			$_POST['codetva'] = 'BE';
			$_POST['pays'] = 'Belgique';

			$_REQUEST['idsupplier'] = $DB->ADD('supplier');
			include "v-detail.php" ;
		break;

		case "delete": 
			if (!empty($_REQUEST['idsupplier'])) {
				$DB->inline("DELETE FROM `sofficer` WHERE `idsupplier` = ".$_REQUEST['idsupplier']);
				$DB->inline("DELETE FROM `supplier` WHERE `idsupplier` = ".$_REQUEST['idsupplier']);
			}

			$PhraseBas = 'Supplier '.$_REQUEST['idsupplier'].' Effac&eacute;';
			include "v-list.php" ;
		break;

		case 'modif':
			$DB->MAJ('supplier');
		case "show": 
			$PhraseBas = 'Detail d\'un supplier';
			include "v-detail.php" ;
		break;
		
		case "find":
			$searchfields = array (
				'societe' => 'societe',
				'fonction' => 'fonction',
				'cp' => 'cp',
				'ville' => 'ville',
				'notes' => 'notes',
				'etat' => 'etat'
			);

			$quid = $DB->MAKEsearch($searchfields);
			$_SESSION['supplierquid'] = (!empty($quid))?$quid:'1';
		case "list":
			include "v-list.php" ;
		break;

		case "search":
		default: 
			$PhraseBas = 'Recherche Supplier';
			include "v-search.php" ;
}
?>
<div id="topboutons">
<table border="0" cellspacing="1" cellpadding="0">
	<tr>
		<td class="on"><a href="?act=add"><img src="<?php echo STATIK ?>illus/ajouter.gif" alt="ajouter" width="32" height="32" border="0"><br>Ajouter</a></td>
		<td class="on"><a href="?act=search"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
		<?php if (!empty($_SESSION['supplierquid'])) { ?><td class="on"><a href="?act=list"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Retour Liste</a></td><?php } ?>
		<?php if ((($_SESSION['roger'] == 'devel') or ($_SESSION['roger'] == 'admin')) and (!empty($_REQUEST['idsupplier']))) { ?><td class="on"><a href="?act=delete&idsupplier=<?php echo $_REQUEST['idsupplier'] ?>" onClick="return confirm('Etes-vous sur de vouloir effacer ce supplier?')"><img src="<?php echo STATIK ?>illus/trash.gif" alt="search" width="32" height="32" border="0"><br>Supprimer</a></td><?php } ?>
	</tr>
</table> 
</div>
<?php include NIVO."includes/pied.php" ; ?>