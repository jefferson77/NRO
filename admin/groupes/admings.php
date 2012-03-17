<?php
define('NIVO', '../../');
$Titre = 'Groupe-S';
$PhraseBas = 'Tableau Manuel Groupe-S';
$Style = 'admin';

## Init Vars
if (!isset($_GET['skip'])) $_GET['skip'] = 0;

# Entete de page
include NIVO.'includes/entete.php' ;

# Classes utilisées
require_once(NIVO."classes/anim.php");
require_once(NIVO."classes/vip.php");
require_once(NIVO."classes/merch.php");

require_once(NIVO."classes/payement.php");

switch ($_GET['act']) {

		## Nouveau PTG
		case "add":
			$DB->inline("INSERT INTO grps.customptg (`nomptg`) VALUES (NULL)");
			$_GET['idptg'] = $DB->addid;

		## Mise à jour des infos PTG
		case "detail":
			if ($_POST['modinf'] == 'Infos') {
				$dateout = date("Y-m-t", strtotime($_POST['datein']));
				$DB->inline("UPDATE grps.customptg SET `nomptg` = '".$_POST['nomptg']."' ,`datein` = '".$_POST['datein']."' ,`dateout` = '".$dateout."' ,`datesend` = '".fdatebk($_POST['datesend'])."' WHERE `idptg` = '".$_GET['idptg']."' LIMIT 1");
			}

			### Recherche des infos PTG  ##
			$infosptg = $DB->getArray("SELECT * FROM grps.customptg WHERE idptg = ".$_GET['idptg']);

			### Ajout d'un People au PTG ###
			if ($_POST['ptgact'] == 'add') salaire_add($_POST['numreg'], $_SESSION['table']);

			###########################################
			### Suppression d'un People dans le PTG ##################### >>> ##########################
			###########################################
			if ($_GET['ptgact'] == 'sup') {
				$pid = $_GET['idpeople'];

				$sql = "DELETE FROM `custom009` WHERE `idpeople` = $pid AND idptg = ".$infosptg['idptg'];

				$del = new db('', '', 'grps');
				$del->inline($sql);

				echo '<font color="#990000">Fiche n&deg; '.$id.' effac&eacute;e</font><br>';
			}
			###########################################
			### Suppression d'un People dans le PTG ##################### <<< ##########################
			###########################################

			include NIVO.'admin/groupes/detail.php';
		break;

		case "mod009":

		# Entete de page

			################################################
			#### Update d'une fiche salaire				####

			if (isset($_POST['id009'])) {
				$infpeople = new db('', '', 'grps');
				$infpeople->inline("UPDATE `custom009` SET
					`mod433` = '".fnbrbk($_POST['mod433'])."',
					`mod437` = '".fnbrbk($_POST['mod437'])."',
					`mod441` = '".fnbrbk($_POST['mod441'])."',
					`modh` = '".fnbrbk($_POST['modh'])."',
					`modh150` = '".fnbrbk($_POST['modh150'])."',
					`modh200` = '".fnbrbk($_POST['modh200'])."'
				WHERE `id009` = '".$_POST['ids']."' LIMIT 1 ;");
			}

			####										####
			################################################

			include NIVO.'admin/groupes/mod009.php';
		break;

		case "list":
		default:
			$lister = 25;

			### Skiping
			if ($_GET['skip'] > 0) { $from = $_GET['skip']; } else { $from = 0; }

			$skip = $from + $lister;
			$rwd = $from - $lister;

			$sql = "SELECT * FROM `customptg` ORDER BY `datesend` ASC LIMIT $from, $lister";

			$afficher = new db('', '', 'grps');
			$afficher->inline($sql);

			$compter = new db('', '', 'grps');
			$compter->inline("SELECT COUNT(idptg) FROM `customptg`");
			$Count = mysql_result($compter->result,0);

			include NIVO.'admin/groupes/list.php';


}
?>
<div id="topboutons">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=list"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="retourliste" width="32" height="32" border="0"><br>Liste</a></td>
			<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=add"><img src="<?php echo STATIK ?>illus/ajouter.gif" alt="ajouter.gif" width="32" height="32" border="0"><br>Ajouter</a></td>
			<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=mod"><img src="<?php echo STATIK ?>illus/correct.gif" alt="corriger.gif" width="32" height="32" border="0"><br>Modifier</a></td>
			<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=list"><img src="<?php echo STATIK ?>illus/testerp.gif" alt="correct.gif" width="32" height="32" border="0"><br>Tester</a></td>
			<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=list"><img src="<?php echo STATIK ?>illus/generer.gif" alt="generer.gif" width="32" height="32" border="0"><br>Envoyer</a></td>
		</tr>
	</table>
</div>
<?php
# Pied de Page
include NIVO.'includes/pied.php';
?>