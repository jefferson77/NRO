<?php
define('NIVO', '../');

# Entete de page
$Titre = 'Groupe-S';

include NIVO."includes/entete.php" ;

# Classes utilisées
include_once(NIVO."classes/vip.php");
include_once(NIVO."classes/anim.php");
include_once(NIVO."classes/merch.php");
include_once(NIVO."classes/payement.php");

include_once("m-salaire.php");

## Table Active
$lestables = $DB->tableliste('/^salaires/', 'grps');

if (isset($_GET['nt'])) $_SESSION['table'] = $_GET['nt'];
if (isset($_SESSION['table'])) {
	$tablesalaires = $_SESSION['table'];
} else {
	$tablesalaires = array_pop($lestables);
	$_SESSION['table'] = $tablesalaires;
}

switch (@$_REQUEST['act']) {
	## Afficher le salaire d'un People ###################################
	case 'showPeople':
		include 'v-detail.php';
	break;
	## Modifier le salaire d'un People ###################################
	case 'updatePeople':
		if ($_POST['idsalaire'] > 0) $DB->MAJ("grps.".$_SESSION['table']);
		include 'v-detail.php';
	break;
	## Ajout d'un People ##################################################
	case 'addPeople':
		if ($_POST['numreg'] > 0) {
			salaire_add($_POST['numreg'], $_SESSION['table']);
			$notify[] = 'Fiche n&deg; '.$_REQUEST['numreg'].' ajoutée';
		}
		include 'v-list.php';
	break;
	## Suppression d'un People ############################################
	case 'delPeople':
		if ($_REQUEST['idpeople'] > 0) {
			$DB->inline("DELETE FROM grps.".$_SESSION['table']." WHERE `idpeople` = ".$_REQUEST['idpeople']);
			$notify[] = 'Fiche n&deg; '.$_REQUEST['idpeople'].' effac&eacute;e';
		}
		include 'v-list.php';
	break;
	## Stocker le mois ####################################################
	case 'addStock':
		if (empty($_POST['datein']) or empty($_POST['dateout'])) {
			$warning[] = 'Vous devez spécifier une date de début et une date de fin valide';
			include 'v-stock.php';
		} else {
			$dates = explode ('/', $_POST['datein']);
			$tablestock = 'salaires'.substr($dates[2], -2).$dates[1];
			$_SESSION['table'] = $tablestock;
			# Création de la table si non existente ou vidage de la table
			$DB->inline("DROP TABLE IF EXISTS grps.".$tablestock);
			$DB->inline("CREATE TABLE `grps`.`$tablestock` (
				 `idsalaire` int(11) NOT NULL auto_increment,
				 `idpeople` int(11) NOT NULL default '0',
				 `date` date NOT NULL default '0000-00-00',
				 `mod433` decimal(8,4) default NULL,
				 `mod437` decimal(8,4) default NULL,
				 `mod441` decimal(8,4) default NULL,
				 `modh` decimal(8,4) default NULL,
				 `modh150` decimal(8,4) default NULL,
				 `modh200` decimal(8,4) default NULL,
				 PRIMARY KEY (`idsalaire`),
				 KEY `idpeople` (`idpeople`)
				) TYPE=MyISAM;");

			salaire_add ('ALL', $tablestock);

			$lestables = $DB->tableliste('/^salaires/', 'grps');

			include 'v-list.php';
		}
	break;
	## Formulaire de stockage #############################################
	case 'showStock':
		include 'v-stock.php';
	break;
	## Liste des salaires du mois #########################################
	case 'skip':
	default:
		include 'v-list.php';
	break;
}

?>
<div id="topboutons">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="on"><a href="<?php echo NIVO ?>groupe-s/admingroupes.php?act=showStock"><img src="<?php echo STATIK ?>illus/stock.gif" alt="stock.gif" width="32" height="32" border="0"><br>Stocker</a></td>
			<td class="on"><a href="<?php echo NIVO ?>groupe-s/admingroupes.php"><img src="<?php echo STATIK ?>illus/correct.gif" alt="corriger.gif" width="32" height="32" border="0"><br>Corriger</a></td>
			<td class="on"><a href="<?php echo NIVO ?>groupe-s/writeptg.php"><img src="<?php echo STATIK ?>illus/generer.gif" alt="generer.gif" width="32" height="32" border="0"><br>G&eacute;n&eacute;rer</a></td>
		</tr>
	</table>
</div>
<?php include NIVO."includes/pied.php"; ?>