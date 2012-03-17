<?php
# Entete de page
define('NIVO', '../../');

$Titre = 'DOCUMENTS';
$Style = 'user';

# Entete
include NIVO."includes/entete.php";

# Classes utilisees
include_once(NIVO."classes/anim.php");
include_once(NIVO."classes/vip.php");
include_once(NIVO."classes/merch.php");
include_once(NIVO."classes/ptg.php");
include_once(NIVO."classes/payement.php");
require_once(NIVO."print/dispatch/dispatch_functions.php");

$classe = "standard";

/**
 * Vérifie que les dates sont correctes
 *
 * @return array(datein, dateout, error);
 * @author Nico
 **/
function checkDates ($datestring, $max = "yes")
{
	global $DB;

	$mindate = '2003-09-01';
	$maxdate = $DB->CONFIG('lastpayement');

	$ret = array();

	if (!empty($datestring)) {
		if(strpos($datestring, '...')) {
			$stucks = explode('...', $datestring);
			$ret['datein'] = fdatebk($stucks[0]);
			$ret['dateout'] = fdatebk($stucks[1]);
		} else {
			$ret['datein'] = fdatebk($datestring);
			$ret['dateout'] = fdatebk($datestring);
		}

		## min date
		if ((empty($ret['datein'])) or ($ret['datein'] < $mindate)) $ret['datein'] = $mindate;

		## maxdate
		if ($max == "yes") {
			if ($ret['datein'] > $maxdate) $ret['datein'] = $maxdate;
			if (empty($ret['dateout']) or ($ret['dateout'] > $maxdate)) $ret['dateout'] = $maxdate;
		}
	} else {
		$ret['error'] = "Aucune Date spécifiée";
	}

	return $ret;
}

# Carousel des fonctions
switch ($_GET['act']) {
##################################################################################################################
### Formulaire de Recherche ####
	case "search":
		$PhraseBas = 'Recherche de Documents';
		include 'v-searchForm.php';
		break;
##################################################################################################################
### Affichage ####
	case "show":
		$PhraseBas = 'Affichage documents';

		switch ($_POST['doctype']) {
		## Contrats #######################################################################
			case 'contrats':
				if ($_POST['idpeople'] > 0) {
					$dates = checkDates($_POST['dates'], 'no');

					if (empty($dates['error'])) {
						$ptable = paytable ($_POST['idpeople'], $dates['datein'], $dates['dateout']);
						$dtable = array_shift($ptable);
						if (count($dtable) > 0) {
							include(NIVO."print/people/contrat_inc.php");
						} else {
							$waring[] = "Aucune Mission ne correspond à votre recherche";
							include 'v-searchForm.php';
						}
					} else {
						$error[] = $dates['error'];
						include 'v-searchForm.php';
					}
				} else {
					$error[] = "Aucun people Spécifié";
					include 'v-searchForm.php';
				}
			break;
		## C4 #############################################################################
			case 'c4':
				if ($_POST['idpeople'] > 0) {
					$dates = checkDates($_POST['dates']);

					if (empty($dates['error'])) {
						$web = false;
						$ptable = paytable ($_POST['idpeople'], $dates['datein'], $dates['dateout']);
						$dtable = array_shift($ptable);
						if (count($dtable) > 0) {
							include(NIVO."print/people/c4/c4.php");
						} else {
							$waring[] = "Aucune Mission ne correspond à votre recherche";
							include 'v-searchForm.php';
						}
					} else {
						$error[] = $dates['error'];
						include 'v-searchForm.php';
					}
				} else {
					$error[] = "Aucun people Spécifié";
					include 'v-searchForm.php';
				}
			break;
		## c131 ###########################################################################
			case 'c131a':
				$dates = checkDates($_POST['dates']);
				include(NIVO."print/people/c131/c131a.php");
			break;
		## Attestation de travail #########################################################
			case 'attest':
				if ($_POST['idpeople'] > 0) {
					$dates = checkDates($_POST['dates']);

					if (empty($dates['error'])) {
						$web = false;
						$ptable = paytable ($_POST['idpeople'], $dates['datein'], $dates['dateout']);
						$dtable = array_shift($ptable);
						if (count($dtable) > 0) {
							include(NIVO."print/people/attest/attest.php");
						} else {
							$waring[] = "Aucune Mission ne correspond à votre recherche";
							include 'v-searchForm.php';
						}
					} else {
						$error[] = $dates['error'];
						include 'v-searchForm.php';
					}
				} else {
					$error[] = "Aucun people Spécifié";
					include 'v-searchForm.php';
				}

			break;
		## 281.10 (pour les impots) #######################################################
			case '281.10':
				# check idpeople
				if ($_POST['idpeople'] > 0) {
					# check période
					if (count($_POST['period281']) > 0) {
						# dispatch docs
						include(NIVO."print/people/281.10.php");
					} else {
						$error[] = "Aucune période de recherche spéficiée";
						include 'v-searchForm.php';
					}
				} else {
					$error[] = "Aucun people Spécifié";
					include 'v-searchForm.php';
				}
			break;
		## Fiches de Salaire ###############################################################
			case 'fichesalaire':
				# check idpeople
				if ($_POST['idpeople'] > 0) {
					# check période
					if (count($_POST['periodFiche']) > 0) {
						# dispatch docs
						include(NIVO."print/people/ficheSalaire.php");
					} else {
						$error[] = "Aucune période de recherche spéficiée";
						include 'v-searchForm.php';
					}
				} else {
					$error[] = "Aucun people Spécifié";
					include 'v-searchForm.php';
				}
			break;
			default:
				$error[] = "Vous devez selectionner un type de document.";
				include 'v-searchForm.php';
		}
	break;
}
?>
<div id="topboutonsadmin">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="on"><a href="?act=search"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
		</tr>
	</table>
</div>
<?php include NIVO."includes/pied.php" ;?>