<?php
# Entete de page
define('NIVO', '../../');
$Titre = 'FACTURATION';
$Style = 'admin';

# Entete
include NIVO."includes/entete.php" ;
# Classes utilisees
include_once(NIVO."classes/facture.php");
include_once(NIVO."classes/anim.php");
include_once(NIVO."classes/vip.php");
include_once(NIVO."classes/merch.php");
# Modèle
include("m-facture.php");

$classe = "standard";

if (!isset($_GET['skip'])) $_GET['skip'] = 0;

# Carousel des fonctions
switch ($_GET['act']) {

	### Impression Facture
	case "print":
		$PhraseBas = 'R&eacute;-impression de factures';
		include "print.php" ;
	break;

	### Ajout d'une facture manuelle
	case "add":
		$lastfac = $DB->getRow("SELECT intitule, montant, idfac FROM facture WHERE modefac = 'M' ORDER BY idfac DESC LIMIT 1");
		if ((empty($lastfac['intitule'])) and ($lastfac['montant'] == 0)) {
			$warning[] = "La dernière facture manuelles n'a pas été remplie, veuillez l'utiliser avant d'en créer une nouvelle";
			$_REQUEST['idfac'] = $lastfac['idfac'];
		} else {
			$DB->inline("INSERT INTO `facture` (`datefac`, `etat`, `modefac`,`secteur`, `idclient`, `idagent`) VALUES ('".facdate()."', '1' , 'M', '2', '1038', '20');");
			$_REQUEST['idfac'] = $DB->addid;
		}

	### Affichage du détail de facture
	case "detail":
		$PhraseBas = 'D&eacute;tail d\'une facture';

	########### Switch du mode de facturation #########
	###########################################################################################
		# Ne permet pas sauver une date antérieure aux factures précédentes
		if (!empty($_POST['datefac'])) {
			$mindate = $DB->getOne("SELECT MAX(datefac) FROM facture WHERE idfac < ".$_REQUEST['idfac']);
			if (strtotime(fdatebk($_POST['datefac'])) < strtotime($mindate)) {
				$warning[] = "La date Minimum pour cette facture est le ".fdate($mindate);
				$_POST['datefac'] = fdate($mindate);
			}
		}

		## Modification du client #############
		if ($_POST['dact'] == 'Tarifs') $DB->MAJ('facture');
		## Modification du client #############
		if ($_POST['dact'] == 'Infos') 
		{
			updatePO($_POST['idfac'], $_POST['po']);

			## update reste
			$DB->MAJ('facture');
		}
		## Suppression de la ligne #############
		if (($_POST['dact'] == 'S') and ($_POST['idman'] > 0)) $DB->inline("DELETE FROM facmanuel WHERE idman = ".$_POST['idman']);

		## Ajout de la ligne #############
		if ($_POST['dact'] == 'Add') $DB->ADD('facmanuel');

		## Modification de la ligne #############
		if ($_POST['dact'] == 'M') $DB->MAJ('facmanuel');

		$fac = new facture($_REQUEST['idfac']);

		include "v-detail.php" ;
	break;

	### Recherche des Factures
	case "search":
		$PhraseBas = 'Recherche de Factures';
		include "v-search.php" ;
	break;

############## Listing des PURGE FL #############################################
	case "purgefl1":
		include "purgefl1.php" ;
		$PhraseBas = 'PURGE FL des ANIM et Merch';
	break;

#/############# Listing des PURGE FL #############################################
############## SWITCH des PURGE FL ANIM  #############################################
	case "purgefl2anim":
		foreach($_POST as $key => $valeur) {
		### Update de l'état de facturation ---- 2 EME PASSAGE
			if (substr($key, 0, 10) == 'factswitch') {
				$dd = explode('-', $key);
				$idanim = $dd[1];
				$_POST['facturation'] = $valeur;
				# pour courcircuit avec 1ER PASSAGE
				if ($idanimtemp != $idanim) {
					$modif = new db('animation', 'idanimation');
					$modif->MODIFIE($idanim, array('facturation' ));
				}
			}
		### Update du num de facture ---- 1ER PASSAGE
			if (substr($key, 0, 6) == 'facnum') {
				$dd = explode('-', $key);
				$idanim = $dd[1];
				if ($_POST['factswitch-'.$idanim] == 8) {
					$idanimtemp = $idanim; # POUR 2 EME PASSAGE
					$_POST['facturation'] = $_POST['factswitch-'.$idanim];
					$_POST['facnum'] = $valeur;
					### vérif facnum != ''
					if (!empty($_POST['facnum'])) {
						### vérif facture est bien manuelle : modefac = M
						$facnum = $_POST['facnum'];
						$verifmanuel = new db();
						$verifmanuel->inline("SELECT idfac, modefac FROM `facture` WHERE `idfac` = $facnum");
						$infosverifmanuel = mysql_fetch_array($verifmanuel->result) ;

						if ($infosverifmanuel['modefac'] == 'M') {
							$updfacnum = new db('animation', 'idanimation');
							$updfacnum->MODIFIE($idanim, array('facnum', 'facturation'));
						}
						else {
							$_POST['facturation'] = 9;
							$updfacnum1 = new db('animation', 'idanimation');
							$updfacnum1->MODIFIE($idanim, array('facturation'));
							$erreurmodefac .= $idanim." : Ce num&eacute;ro de FACTURE ".$facnum." ne correspond PAS &agrave;  une facture MANUELLE<br>"; # Message pour page purgefl1.php
						}
						#/## vérif facture est bien manuelle : modefac = M
					}
					else {
						$_POST['facturation'] = 9;
						$updfacnum1 = new db('animation', 'idanimation');
						$updfacnum1->MODIFIE($idanim, array('facturation'));
					}
					#/## vérif facnum != ''
				}
			}
		}
		include "purgefl1.php" ;
		$PhraseBas = 'PURGE FL des ANIM et Merch';
	break;


#/############# SWITCH des PURGE FL ANIM #############################################
############## SWITCH des PURGE FL MERCH  #############################################
	case "purgefl2merch":
		foreach($_POST as $key => $valeur) {
		### Update de l'état de facturation ---- 2 EME PASSAGE
			if (substr($key, 0, 10) == 'factswitch') {
				$dd = explode('-', $key);
				$idmerch = $dd[1];
				$_POST['facturation'] = $valeur;
				# pour courcircuit avec 1ER PASSAGE
				if ($idmerchtemp != $idmerch) {
					$modif = new db('merch', 'idmerch');
					$modif->MODIFIE($idmerch, array('facturation' ));
				}
			}
		### Update du num de facture ---- 1ER PASSAGE
			if (substr($key, 0, 6) == 'facnum') {
				$dd = explode('-', $key);
				$idmerch = $dd[1];
				if ($_POST['factswitch-'.$idmerch] == 8) {
					$idmerchtemp = $idmerch; # POUR 2 EME PASSAGE
					$_POST['facturation'] = $_POST['factswitch-'.$idmerch];
					$_POST['facnum'] = $valeur;
					### vérif facnum != ''
					if (!empty($_POST['facnum'])) {
						### vérif facture est bien manuelle : modefac = M
						$facnum = $_POST['facnum'];
						$verifmanuel = new db();
						$verifmanuel->inline("SELECT idfac, modefac FROM `facture` WHERE `idfac` = $facnum");
						$infosverifmanuel = mysql_fetch_array($verifmanuel->result) ;

						if ($infosverifmanuel['modefac'] == 'M') {
							$updfacnum = new db('merch', 'idmerch');
							$updfacnum->MODIFIE($idmerch, array('facnum', 'facturation'));
						}
						else {
							$_POST['facturation'] = 9;
							$updfacnum1 = new db('merch', 'idmerch');
							$updfacnum1->MODIFIE($idmerch, array('facturation'));
							$erreurmodefac .= $idmerch." : Ce num&eacute;ro de FACTURE ".$facnum." ne correspond PAS &agrave;  une facture MANUELLE<br>"; # Message pour page purgefl1.php
						}
						#/## vérif facture est bien manuelle : modefac = M
					}
					else {
						$_POST['facturation'] = 9;
						$updfacnum1 = new db('merch', 'idmerch');
						$updfacnum1->MODIFIE($idmerch, array('facturation'));
					}
					#/## vérif facnum != ''
				}
			}
		}
		include "purgefl1.php" ;
		$PhraseBas = 'PURGE FL des ANIM et Merch';
	break;

#/############# SWITCH des PURGE FL MERCH #############################################

	case "list":
	default:

		$lister = 400;

		### Skiping
		if ($_GET['skip'] > 0) { $from = $_GET['skip']; } else { $from = 0; }

		$skip = $from + $lister;
		$rwd = $from - $lister;

		### Construction de la requete
		$afficher = new db();

		$where = '';

		if (!empty($_POST['search'])) {
			
			$_POST['montant'] = fnbrbk($_POST['montant']);

			$searchfields = array (
				'f.datefac'  => 'datefac',
				'f.idfac'    => 'idfac',
				'f.idclient' => 'idclient',
				'c.societe' => 'societe',
				'f.montant'  => 'montant',
				'f.intitule' => 'intitule',
				'f.po' => 'po'
			);

			## secteur
			if(is_array($_POST['secteur'])) $quid .= " AND f.secteur in ('".implode("', '", $_POST['secteur'])."')";

			## modefac
			if(is_array($_POST['modefac'])) $quid .= " AND f.modefac in ('".implode("', '", $_POST['modefac'])."')";

			## echéance
			if (is_array($_POST['echeance'])) {
				if ((in_array('echu', $_POST['echeance'])) and (in_array('nonechu', $_POST['echeance']))) {
					$quid .= " AND e.montantdu > 0";
				} else {
					if (in_array('echu', $_POST['echeance'])) $quid .= " AND e.dateecheance < '".date("Y-m-d")."' AND e.montantdu > 0";
					if (in_array('nonechu', $_POST['echeance'])) $quid .= " AND e.dateecheance > '".date("Y-m-d")."' AND e.montantdu > 0";
				}
			}

			$where = 'WHERE '.$afficher->MAKEsearch($searchfields).$quid;

			if ($where != 'WHERE ') {
				$_SESSION['where'] = $where;
			} else {
				$where = '';
				unset($_SESSION['where']);
			}

		} else {
			if(!empty($_SESSION['where'])) $where = $_SESSION['where'];
		}

		### Recherche des fiches correspondantes
		$afficher->inline("SELECT
				f.idfac, f.datefac, f.idclient, f.idcofficer, f.modefac, f.montant, f.secteur, f.intitule, f.po,
				c.societe, c.factureofficer,
				e.montantdu, e.dateecheance
			FROM facture f
				LEFT JOIN client c ON f.idclient = c.idclient
				LEFT JOIN echeancier e ON f.idfac = e.idfac
			".$where."
			ORDER BY f.idfac DESC
			LIMIT ".$from.", ".$lister);
		### Nombre de fiches correspondant a la recherche

		$compter = new db();
		$compter->inline("SELECT 
				COUNT(f.idfac) 
			FROM facture f 
				LEFT JOIN client c ON f.idclient = c.idclient 
				LEFT JOIN echeancier e ON f.idfac = e.idfac
			".$where);
		$Count = mysql_result($compter->result,0);

		include "list.php" ;
}
?>
<div id="topboutonsadmin">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<?php if($_SESSION['roger']=="admin" or $_SESSION['roger']=="devel"){ ?>
			<td class="on"><a href="?act=add"><img src="<?php echo STATIK ?>illus/ajouter.gif" alt="ajouter" width="32" height="32" border="0"><br>Ajouter</a></td><?php } ?>
			<td class="on"><a href="?act=search"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
			<td class="on"><a href="?act=list"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="retourliste" width="32" height="32" border="0"><br>Liste</a></td>
			<td class="on"><a href="?act=print"><img src="<?php echo STATIK ?>illus/printr01.gif" alt="printr01" width="32" height="32" border="0"><br>RePrint</a></td>
			<td class="on" width="32"><br></td>
			<?php if($_SESSION['idagent']==20 or $_SESSION['roger']=="devel"){ ?>
			<td class="on"><a href="?act=purgefl1"><img src="<?php echo STATIK ?>illus/girl.png" alt="out" width="32" height="32" border="0"><br>Manuelles</a></td> <?php } ?>
		</tr>
	</table>
</div>
<?php include NIVO."includes/pied.php" ?>