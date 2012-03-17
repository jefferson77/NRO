<?php
# Entete de page
define('NIVO', '../../');

include NIVO."includes/ifentete.php" ;

## vars
$pathannexe = Conf::read('Env.root')."media/annexe/client/".prezero($_REQUEST['idclient'], 5);

switch ($_REQUEST['act']) {
## infos notes + tarifs ############################################################################################################################################
	case 'modinfos':
		$_POST['codeclient'] = $_REQUEST['idclient'];
		$DB->MAJ('client');
	break;

## Officers ############################################################################################################################################
	case 'delofficer':
		if ($_REQUEST['idcofficer'] > 0) {
			## recherche autre officer  ###################################################
				$idcofficercible = $DB->getOne("SELECT idcofficer FROM `cofficer` WHERE `idclient` = ".$_REQUEST['idclient']." AND `idcofficer` != ".$_REQUEST['idcofficer']." LIMIT 1");

				if ($idcofficercible > 0) {
					## Regroupement Assignation cofficer  #########################################
						$DB->inline("UPDATE `vipjob`  SET `idcofficer` = '$idcofficercible' WHERE `idcofficer` = ".$_REQUEST['idcofficer']); 	$vipassign = $DB->affected;
						$DB->inline("UPDATE `animjob` SET `idcofficer` = '$idcofficercible' WHERE `idcofficer` = ".$_REQUEST['idcofficer']); 	$animassign = $DB->affected;
						$DB->inline("UPDATE `merch`   SET `idcofficer` = '$idcofficercible' WHERE `idcofficer` = ".$_REQUEST['idcofficer']); 	$merchassign = $DB->affected;

					## delete officer  #############################################################
						$DB->inline("DELETE from cofficer WHERE idcofficer = ".$_REQUEST['idcofficer']);
				}
		}
	break;

	case 'addofficer':
		$Addid = $DB->ADD('cofficer');
	break;

	case 'modofficer':
		$DB->MAJ('cofficer');
	break;

## Annexes ############################################################################################################################################
	case 'annexeupfile':
		$dbfilename = strtolower(basename($_FILES['userfile']['name']));
		if(!is_dir($pathannexe)) mkdir($pathannexe, 0777, true);

		if(move_uploaded_file($_FILES['userfile']['tmp_name'],$pathannexe."/".$dbfilename)){
			$notify[] = "Le fichier '".$dbfilename."' a bien été uploadé";
		} else {
			$error[] = "Le fichier '".$dbfilename."' n'a pas pu être uploadé";
		}

		$_REQUEST['s'] = 5;
	break;

	case 'annexedelfile':
		if (!empty($_POST['delfile'])) {
			if(unlink($pathannexe."/".$_POST['delfile'])) {
				$notify[] = "Fichier ".$_POST['delfile']." effacé";
			} else {
				$error[] = "Impossible d'effacer le fichier : ".$_POST['delfile'];
			}
		}
		$_REQUEST['s'] = 5;
	break;
}

$infos = $DB->getRow("SELECT * FROM `client` WHERE `idclient` = ".$_REQUEST['idclient']);

?>
<table class="standard" border="0" cellspacing="0" cellpadding="0" align="center" width="100%">
	<tr height="25">
		<td class="navbarleft"></td>
		<td class="navbar" width="16%">
		<a href="?idclient=<?php echo $_REQUEST['idclient']; ?>&s=1" target="detail-main">Contacts</a>
		</td>
		<td class="navbarmid"></td>
		<td class="navbar" width="16%">
			<a href="?idclient=<?php echo $_REQUEST['idclient']; ?>&s=2" target="detail-main">Tarification</a>
		</td>
		<td class="navbarmid"></td>
		<td class="navbar" width="16%">
			<a href="?idclient=<?php echo $_REQUEST['idclient']; ?>&s=3" target="detail-main">Notes</a>
		</td>
		<td class="navbarmid"></td>
		<td class="navbar" width="16%">
			<a href="?idclient=<?php echo $_REQUEST['idclient']; ?>&s=4" target="detail-main">Echéancier</a>
		</td>
		<td class="navbarmid"></td>
		<td class="navbar" width="16%">
			<a href="?idclient=<?php echo $_REQUEST['idclient']; ?>&s=5" target="detail-main">Annexes</a>
		</td>
		<td class="navbarmid"></td>
		<td class="navbar">
			<a href="?idclient=<?php echo $_REQUEST['idclient']; ?>&s=6" target="detail-main">Financier</a>
		</td>
		<td class="navbarmid"></td>
		<td class="navbar">
			<a href="?idclient=<?php echo $_REQUEST['idclient']; ?>&s=7" target="detail-main">Documents Envoyés</a>
		</td>
		<td class="navbarright"></td>
	</tr>
</table>
<div id="orangepeople">
<?php
switch ($_REQUEST['s']) {
	case '1':
		include 'v-onglet-officers.php';
	break;

	case '2':
		include 'v-onglet-tarifs.php';
	break;

	case '3':
		include 'v-onglet-notes.php';
	break;

	case '4':
		$afficher = new db();
		$afficher->inline("SELECT
				f.idfac, f.datefac, f.idclient, f.idcofficer, f.modefac, f.montant, f.secteur, f.intitule, f.po,
				c.societe, c.factureofficer,
				e.montantdu, e.dateecheance
			FROM facture f
				LEFT JOIN client c ON f.idclient = c.idclient
				LEFT JOIN echeancier e ON f.idfac = e.idfac
			WHERE f.idclient = ".$_REQUEST['idclient']." AND e.montantdu > 0
			ORDER BY f.idfac DESC");

		include NIVO.'admin/factures/v-list.php';
	break;

	case '5':
		include 'v-onglet-annexes.php';
	break;

	case '6':
		include 'v-onglet-factoring.php';
	break;

	case '7':
		include 'v-onglet-documents.php';
	break;
}
?>
</div>
<?php include NIVO.'includes/ifpied.php'; ?>