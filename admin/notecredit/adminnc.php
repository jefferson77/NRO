<?php
# Entete de page
define('NIVO', '../../'); 
$Titre = 'NOTES DE CREDIT';
$Style = 'admin';

## init vars
if (!isset($_POST['search'])) $_POST['search'] = '';

# Entete
include NIVO."includes/entete.php" ;

require_once NIVO."classes/facture.php";
require_once NIVO."classes/anim.php";
require_once NIVO."classes/vip.php";
require_once NIVO."classes/merch.php";

# Carousel des fonctions
switch ($_GET['act']) {
	
	### Impression Note de Crédit
	case "print": 
		$PhraseBas = 'Impression des notes de cr&eacute;dit';
		
		if ((!empty($_POST['creds'])) or (!empty($_POST['print'])) or (!empty($_GET['idnc']))) {
			include "print.php" ;
		} else {
			include "v-printSearch.php" ;
		}
	break;

	### Ajout d'une Note de crédit
	case "add":
		## check si la dernière est bien remplie, pour ne plus avoir de note vides.
		$last = $DB->getRow("SELECT * FROM credit ORDER BY idfac DESC LIMIT 1");

		if (empty($last['facref']) and empty($last['intitule'])) {
			$warning[] = "La dernière note de crédit n'est pas remplie, veuillez utiliser cette note avant d'en créer une autre";
			$_REQUEST['idfac'] = $last['idfac'];
		} else {
			$DB->inline("INSERT INTO `credit` (`datefac`, `etat`) VALUES (NOW(), '1');");
			$_REQUEST['idfac'] = $DB->addid;
		}

	### Affichage du détail de note de crédit
	case "detail": 
		$PhraseBas = 'D&eacute;tail d\'une note de cr&eacute;dit';

		## Modification du client #############
		if ($_POST['dact'] == 'Infos') {
			$DB->MAJ('credit');
		}

		## Suppression de la ligne #############
		if ($_POST['dact'] == 'S') {
			if ($_POST['idman'] > 0) $DB->inline("DELETE from creditdetail WHERE idman = ".$_REQUEST['idman']);
		}

		## Ajout de la ligne #############
		if ($_POST['dact'] == 'Add') {
			$Addid = $DB->ADD('creditdetail');
		}
		
		## Modification de la ligne #############
		if ($_POST['dact'] == 'M') {
			$DB->MAJ('creditdetail');
		}
		
		$infnc = $DB->getRow("SELECT
			 	n.idfac, n.datefac, n.intitule, n.facref,
				c.societe, c.astva,
				f.secteur, f.idclient,
				o.langue
			FROM credit n
				LEFT JOIN facture f ON f.idfac = n.facref
				LEFT JOIN client c ON f.idclient = c.idclient
				LEFT JOIN cofficer o ON f.idcofficer = o.idcofficer
			WHERE n.idfac = ".$_REQUEST['idfac']);

		$leyear = substr($infnc['datefac'], 0, 4);
		
		include "v-detail.php" ;
	break;
	
	### Recherche des Notes de crédit
	case "search": 
		$PhraseBas = 'Recherche de Note de Cr&eacute;dit';
		include "v-search.php" ;
	break;
	
	case "list": 
	default: 

		if ($_POST['search'] == 'Rechercher') {

			$searchfields = array (
				'n.idfac' 		=> 'idfac', 
				'c.societe' 	=> 'societe', 
				'f.idclient' 	=> 'idclient', 
				'f.secteur' 	=> 'secteur', 
				'n.datefac' 	=> 'datefac', 
				'f.datefac' 	=> 'facturedate', 
				'n.intitule' 	=> 'intitule',
				'n.facref' 		=> 'facref'
			);

			$_SESSION['wherenc'] = $DB->MAKEsearch($searchfields);
			
		}

		if (empty($_SESSION['wherenc'])) $_SESSION['wherenc'] = 1;

		### Recherche des fiches correspondantes
		$rows = $DB->getArray("SELECT
				n.idfac, n.datefac, n.intitule, n.facref,
				c.societe, c.langue,
				f.secteur, f.idclient,
				SUM(d.units) as units,  SUM(d.montant) as montant
			FROM credit n
				LEFT JOIN facture f ON n.facref = f.idfac
				LEFT JOIN client c ON f.idclient = c.idclient 
				LEFT JOIN creditdetail d ON n.idfac = d.idfac
			WHERE ".$_SESSION['wherenc']."
			GROUP BY n.idfac
			ORDER BY n.idfac DESC");

		include "v-list.php" ;
}

?>
<div id="topboutonsadmin">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="on"><a href="?act=add"><img src="<?php echo STATIK ?>illus/ajouter.gif" alt="ajouter" width="32" height="32" border="0"><br>Ajouter</a></td>
			<td class="on"><a href="?act=search"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
			<td class="on"><a href="?act=list"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="retourliste" width="32" height="32" border="0"><br>Liste</a></td>
			<td class="on"><a href="?act=print&idnc=<?php echo $_REQUEST['idfac'];?>"><img src="<?php echo STATIK ?>illus/printr01.gif" alt="printr01" width="32" height="32" border="0"><br>Print</a></td>
		</tr>
	</table> 
</div>
<?php include NIVO."includes/pied.php"; ?>