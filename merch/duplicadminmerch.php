<?php
# Entete de page
define('NIVO', '../');
$Titre = 'MERCH-d';
$Style = 'merch';

# Classes utilis?es
include NIVO."classes/merch.php";
include NIVO."classes/hh.php";

include NIVO."includes/entete.php" ;

# Carousel des fonctions
$onemonthago  = date ("Y-m-01", mktime (0,0,0,date("m"),date("d")+1,date("Y"))); 

switch ($_GET['act']) {
############## Ajout d'une Mission #############################################
		case "add": 
			$_SESSION['archive'] = 'normal' ; 
			$_POST['idagent'] = $_SESSION['idagent'];
			$_POST['facturation'] = '1';
			$_POST['recurrence'] = '1';
			$ajout = new db('merchduplic', 'idmerch');
			$ajout->AJOUTE(array('idclient' , 'idcofficer', 'idagent', 'facturation', 'recurrence' ));
			$PhraseBas = 'Nouvelle merch';
			$did = $ajout->addid;
			$_POST['idmerch'] = $did;

			include "duplicdetail.php" ;
		break;
############## Selection des ?l?ments #############################################
		case "select": 
			$_SESSION['archive'] = 'normal' ; 
			$PhraseBas = 'Selection des &eacute;l&eacute;ments';
			$did = $_GET['idmerch'];

			include "duplicselect.php" ;
		break;
############## duplic-set input de la semaine cible et source #############################################
		case "duplic-set": 
			$idagent = $_SESSION['idagent'];
			$merch = new db();
			$merch->inline("SELECT * FROM `merchduplic` WHERE `idagent` = $idagent GROUP BY genre ORDER BY idmerch;");
				while ($infossearch = mysql_fetch_array($merch->result)) { 
				$genretemp .= $infossearch['genre'].'-';
				}
			$FoundCount = mysql_num_rows($merch->result); 
			
			if ($FoundCount < 3) {
				$PhraseBas = 'S&eacute;lection de la semaine &agrave; dupliquer';
				include "duplicweeks.php" ;
			} else {
				$PhraseBas = 'Base de duplication non vide';
				$_GET['listing'] = direct;
				$attention = '<h2>'.$PhraseBas.'</h2>';
				include "dupliclisting.php" ;
			}
		break;
############## duplic-set input de la semaine cible et source + jours de la semaine #############################################
		case "duplic-ready": 
			$PhraseBas = 'S&eacute;lection de la semaine &agrave; dupliquer';
			include "duplicweeks.php" ;
		break;
############## duplic-process input de la semaine cible et source + jours de la semaine + duplic vers merchduplic #############################################
		case "duplic-process": 
			# check si il n'y a pas d'autre process de duplication en cours
			$FoundCount = $DB->getOne("SELECT COUNT(idmerch) FROM `merchduplic` WHERE `idagent` = ".$_SESSION['idagent']." AND `genre` = '".$_POST['genre']."';");

			if ($FoundCount < 1) {

				$datesource = weekdate($_POST['semainesource'], $_POST['anneesource']);
				$datelun = $datesource['lun'];
				$datedim = $datesource['dim'];

				$datecible = weekdate($_POST['semainecible'], $_POST['anneecible']);

				$merchDups = $DB->getArray("SELECT 
						datem, easremplac, genre, hin1, hin2, hout1, hout2, idagent, idclient, idcofficer, idpeople, idshop, kmfacture, kmpaye, mtype, note, produit, recurrence, weekm 
					FROM `merch` 
					WHERE `idagent` = ".$_SESSION['idagent']." 
						AND `datem` BETWEEN '".$datelun."' AND '".$datedim."' 
						AND `genre` = '".$_POST['genre']."' 
						AND `recurrence` = 1");

				$iduplic = 0;
				$nombreduplic = 0;
				foreach ($merchDups as $infosduplic) { 
					
					$iduplic++;
					$nombreduplic++;
					
					foreach ($datesource as $key => $value) {
						if ($infosduplic['datem'] == $value) $_POST['datem'] = $datecible[$key];
					}
					
					## clean les backslashes qui trainent dans les notes merch
					while (strstr($infosduplic['note'], '\\')) {
						$infosduplic['note'] = stripslashes($infosduplic['note']);
					}

					$_POST['idclient'] = 	$infosduplic['idclient'];
					$_POST['idcofficer'] = 	$infosduplic['idcofficer'];
					$_POST['idshop'] = 		$infosduplic['idshop'];
					$_POST['idpeople'] = 	$infosduplic['idpeople'];
					$_POST['easremplac'] = 	$infosduplic['easremplac'];
					$_POST['produit'] = 	$infosduplic['produit'];
					$_POST['hin1'] = 		$infosduplic['hin1'];
					$_POST['hout1'] = 		$infosduplic['hout1'];
					$_POST['hin2'] = 		$infosduplic['hin2'];
					$_POST['hout2'] = 		$infosduplic['hout2'];
					$_POST['recurrence'] = 	$infosduplic['recurrence'];
					$_POST['kmpaye'] = 		$infosduplic['kmpaye'];
					$_POST['kmfacture'] = 	$infosduplic['kmfacture'];
					$_POST['genre'] = 		$infosduplic['genre'];
					$_POST['note'] = 		addslashes($infosduplic['note']);

					$_POST['weekm'] = 		$_POST['semainecible'];
					$_POST['idagent'] = 	$_SESSION['idagent'];
					$_POST['ferie'] = 		100;
					$_POST['frais'] = 		0 ;
					$_POST['fraisfacture'] = 0 ;
					$_POST['livraison'] = 	0 ;
					$_POST['diversfrais'] = 0 ;
					$_POST['facturation'] = '1';	
					
					$vacances = $DB->getColumn("SELECT dateconge FROM conges");				
					
					############## Création d'une Mission en Merch
					if(in_array($_POST['datem'],$vacances))
					{
						$_POST['datem'] = "0000-00-00";
					
					}
					$ajout = new db('merchduplic', 'idmerch');
					$ajout->AJOUTE(array(
					'idclient' , 'idcofficer', 'idagent', 'idshop', 'idpeople', 'produit', 'facturation', 'recurrence', 'easremplac', 
					'datem', 'weekm' , 'hin1' , 'hout1' , 'hin2' , 'hout2' , 'ferie',
					'kmpaye' , 'kmfacture' , 'frais' , 'fraisfacture' , 'note' , 'genre', 'livraison', 'diversfrais'));
				}
				#/#######		
				$titreduplic = 'La semaine '.$_POST['semainecible'].' '.$_POST['anneecible'].' a &eacute;t&eacute; cr&eacute;&eacute;e sur base de la semaine '.$_POST['semainesource'].' '.$_POST['anneesource'].' pour les missions '.$genre.' ( '.$nombreduplic.' Missions dupliqu&eacute;es)<br>';

				$PhraseBas = 'Semaine dupliqu&eacute;e';
			} else {
				$PhraseBas = 'Base de duplication non vide';
			
			}
			include "dupliclistingsearch.php" ;
		break;

############## NETTOYAGE Merge duplic #############################################
		case "nettoyage": 
			$idagent = $_GET['idagent'];
			$genre = $_GET['genre'];
#			$merchdel = new db('merchduplic', 'idmerch');
			$sqldel = "DELETE FROM `merchduplic` WHERE `idagent` = $idagent AND `genre` = '$genre';";
			echo '<br>'.$sqldel.'<br>';
#			$merchdel->inline($sqldel);

			$PhraseBas = 'S&eacute;lection de la semaine &agrave; finaliser';
			include "duplicmerge.php" ;
		break;
############## Merge duplic Select #############################################
		case "merge": 
			$PhraseBas = 'S&eacute;lection de la semaine &agrave; finaliser';
			include "duplicmerge.php" ;
		break;
############## Merge duplic vers merch et del merchduplic #############################################
		case "mergefinal": 
			$merch = new db();
			$merch->inline("SELECT idmerch FROM `merchduplic` WHERE `idagent` = ".$_SESSION['idagent']." AND `genre` = '".$_POST['genre']."';");
			$FoundCount = mysql_num_rows($merch->result); 

			if ($FoundCount > 0) {
				$merchdup = new db();
				## duplication puis delete
				$champs = "`idagent`, `idclient`, `idcofficer`, `idshop`, `idpeople`, `mtype`, `easremplac`, `datem`, `weekm`, `produit`, `hin1`, `hout1`, `hin2`, `hout2`, `recurrence`, `genre`, `kmpaye`, `kmfacture`, `note`";
				$merchdup->inline("INSERT INTO `merch` ($champs) SELECT $champs FROM `merchduplic` WHERE `idagent` = ".$_SESSION['idagent']." AND `genre` = '".$_POST['genre']."'");
				$merchdup->inline("DELETE FROM `merchduplic` WHERE `idagent` = ".$_SESSION['idagent']." AND `genre` = '".$_POST['genre']."'");

				$titreduplic = 'La semaine '.$_POST['weekm'].' de '.$_POST['genre'].' a bien &eacute;t&eacute; cr&eacute;&eacute;e dans la base de travail ('.$FoundCount.')<br>';

				$PhraseBas = 'Semaine dupliqu&eacute;e';
			} else {
				$PhraseBas = 'Base de duplication non vide';
			}
			include "duplicmerge.php" ;
		break;

############## Affichage d'une Mission #########################################
		case "show": 
			$PhraseBas = 'Detail d\'une merch';
			$did = $_GET['idmerch'];
			include "duplicdetail.php" ;
		break;
############## Modification et affichage d'une Mission #########################################
		case "modif": 
			$_SESSION['archive'] = 'normal' ; 
			$did = $_POST['idmerch'];
			$toinclude = 'duplicdetail.php';
			switch ($_GET['mod']) {
			#### Mise à jour des infos Agent
					case "agent":
						$modif = new db('merchduplic', 'idmerch');
						$modif->MODIFIE($did, array('idagent' ));
						$PhraseBas = 'Detail d\'une merch : Assistant Modifi&eacute;';
					break;

			#### Mise à jour des infos Client
					case "client":
						$modif = new db('merchduplic', 'idmerch');
						$modif->MODIFIE($did, array('idclient' , 'idcofficer'));
						$PhraseBas = 'Detail d\'une merch : Client Modifi&eacute;';
					break;
			#### Mise à jour des infos Lieu
					case "lieu": 
						$modif = new db('merchduplic', 'idmerch');
						$modif->MODIFIE($did, array('idshop'));
						$PhraseBas = 'Detail d\'une merch : Lieu Modifi&eacute;';
					break;
			#### Mise à jour des infos People
					case "people": 
						$modif = new db('merchduplic', 'idmerch');
						$modif->MODIFIE($did, array('idpeople'));
						$PhraseBas = 'Detail d\'une merch : People Modifi&eacute;';
					break;
			#### Mise ? jour de la fiche compl?te
					default: 
						$_POST['datem'] = fdatebk($_POST['datem']);
						$_POST['weekm'] = date('W', strtotime($_POST['datem']));
						$_POST['yearm'] = date('Y', strtotime($_POST['datem']));
						
						$_POST['hin1'] = ftimebk($_POST['hin1']);
						$_POST['hout1'] = ftimebk($_POST['hout1']);
						$_POST['hin2'] = ftimebk($_POST['hin2']);
						$_POST['hout2'] = ftimebk($_POST['hout2']);
						
						$hcode = new hh ();
						$_POST['hhcode'] = $hcode->makehhcode($_POST['hin1'], $_POST['hout1'], $_POST['hin2'], $_POST['hout2']);

						
						$_POST['kmpaye'] = fnbrbk($_POST['kmpaye']);
						if ($_POST['kmfacture'] == '0')	{
							$_POST['kmfacture'] = fnbrbk($_POST['kmpaye']);
						} else {
							$_POST['kmfacture'] = fnbrbk($_POST['kmfacture']);
						}
						$_POST['frais'] = fnbrbk($_POST['frais']);
						if ($_POST['fraisfacture'] == '0')	{
							$_POST['fraisfacture'] = fnbrbk($_POST['frais']);
						} else {
							$_POST['fraisfacture'] = fnbrbk($_POST['fraisfacture']);
						}
						$_POST['briefing'] = fnbrbk($_POST['briefing']);
						$_POST['livraison'] = fnbrbk($_POST['livraison']);
						$_POST['diversfrais'] = fnbrbk($_POST['diversfrais']);

						$modif = new db('merchduplic', 'idmerch');
						$modif->MODIFIE($did, array('mtype', 'produit' ,  
						'datem', 'weekm', 'yearm' , 'hin1' , 'hout1' , 'hin2' , 'hout2' , 'ferie', 'recurrence' , 'easremplac', 
						'kmpaye' , 'kmfacture' , 'frais' , 'fraisfacture' , 'note' , 'genre', 'hhcode', 'contratencode', 'livraison', 'diversfrais'));
						$PhraseBas = 'Detail d\'une merch : Fiche Mise &agrave; Jour';
			}


			include "$toinclude" ;
		break;

############## Duplication d'une Mission (search + duplic) #############################################


		case "duplic": 
			$_SESSION['archive'] = 'normal' ; 
			$idmerch = $_GET['idmerch'];
			############## Recherche infos d'une Mission 
			$merch1 = new db();
			$merch1->inline("SELECT * FROM `merchduplic` WHERE `idmerch` = $idmerch;");
			$infosduplic = mysql_fetch_array($merch1->result) ; 
				$_POST['idagent'] = $infosduplic['idagent'];
				$_POST['idclient'] = $infosduplic['idclient'];
				$_POST['idcofficer'] = $infosduplic['idcofficer'];
				$_POST['idshop'] = $infosduplic['idshop'];
				$_POST['idpeople'] = $infosduplic['idpeople'];
				$_POST['mtype'] = $infosduplic['mtype'];
				$_POST['easremplac'] = $infosduplic['easremplac'];
#				$_POST['produit']=addslashes($infosduplic['produit']);
				$_POST['produit'] = $infosduplic['produit'];
				$_POST['datem'] = $infosduplic['datem'];
				$_POST['weekm'] = $infosduplic['weekm'];
				$_POST['hin1'] = $infosduplic['hin1'];
				$_POST['hout1'] = $infosduplic['hout1'];
				$_POST['hin2'] = $infosduplic['hin2'];
				$_POST['hout2'] = $infosduplic['hout2'];
				$_POST['ferie']=100;
				$_POST['recurrence'] = $infosduplic['recurrence'];
				$_POST['genre'] = $infosduplic['genre'];
				$_POST['kmpaye'] = $infosduplic['kmpaye'];
				$_POST['kmfacture'] = $infosduplic['kmfacture'];
				$_POST['frais'] = 0 ;
				$_POST['fraisfacture'] = 0 ;
				$_POST['livraison'] = 0 ;
				$_POST['diversfrais'] = 0 ;
				$_POST['note']=addslashes($infosduplic['note']);
				$_POST['facturation'] = '1';	

			$didold = $idmerch;

			############## Création d'une Mission 
			$ajout = new db('merchduplic', 'idmerch');
			$ajout->AJOUTE(array(
			'idclient' , 'idcofficer', 'idagent', 'idshop', 'idpeople', 'mtype', 'produit', 'facturation', 'recurrence', 'easremplac',
			'datem', 'weekm' , 'hin1' , 'hout1' , 'hin2' , 'hout2' , 'ferie',
			'kmpaye' , 'kmfacture' , 'frais' , 'fraisfacture' , 'note' , 'genre', 'livraison', 'diversfrais'));
			$PhraseBas = 'Nouvelle merch';
			$did = $ajout->addid;
			$_POST['idmerch'] = $did;

			$PhraseBas = 'Detail d\'une merch : Fiche dupication';
			
			include "duplicdetail.php" ;
	  
		break;

############## DELETE d'une merch #############################################
		case "delete": 
			$_SESSION['archive'] = 'normal' ; 
			$idmerch = $_GET['idmerch'];

			$merchdel = new db('merchduplic', 'idmerch');
			$sqldel = "DELETE FROM `merchduplic` WHERE `idmerch` = $idmerch;";
			$merchdel->inline($sqldel);

			if (($_GET['listing'] == 'direct') OR ($_GET['listing'] == 'missing')) {
				$_SESSION['view'] = 'n';
			}	
			$_GET['act'] = 'listingsearch';

			$_GET['action'] = 'skip';
			include "dupliclisting.php" ;
		break;

############## Listing des Missions Search #############################################
	case "listingsearch": 
			$_SESSION['archive'] = 'normal' ; 
			$_SESSION['view'] = 'n' ; 
			include "dupliclistingsearch.php" ;

			$PhraseBas = 'Listing des Merch';
		break;

############## Listing des Job Results #############################################
		case "listing": 
			if (($_GET['listing'] == 'direct') OR ($_GET['listing'] == 'missing')) {
				$_GET['listtype'] = '0';
			}	
			include "dupliclisting.php" ;
			$PhraseBas = 'Planning des Merch';
		break;
####
############## UPDATE dans Listing des Job Results #############################################
		case "listingmodif": 
			$_SESSION['archive'] = 'normal' ; 
			$did = $_POST['idmerch'];

				$_POST['datem'] = fdatebk($_POST['datem']);
				$_POST['weekm'] = date('W', strtotime($_POST['datem']));
				$_POST['yearm'] = date('Y', strtotime($_POST['datem']));
				$_POST['kmpaye'] = fnbrbk($_POST['kmpaye']);
				if ($_POST['kmfacture'] == '0')	{
					$_POST['kmfacture'] = fnbrbk($_POST['kmpaye']);
				} else {
					$_POST['kmfacture'] = fnbrbk($_POST['kmfacture']);
				}
				$_POST['frais'] = fnbrbk($_POST['frais']);
				if ($_POST['fraisfacture'] == '0')	{
					$_POST['fraisfacture'] = fnbrbk($_POST['frais']);
				} else {
					$_POST['fraisfacture'] = fnbrbk($_POST['fraisfacture']);
				}

				$_POST['hin1'] = ftimebk($_POST['hin1']);
				$_POST['hout1'] = ftimebk($_POST['hout1']);
				$_POST['hin2'] = ftimebk($_POST['hin2']);
				$_POST['hout2'] = ftimebk($_POST['hout2']);

				$modif = new db('merchduplic', 'idmerch');
				$modif->MODIFIE($did, array('datem' , 'weekm', 'yearm', 'hin1', 'hout1', 'hin2', 'hout2', 'kmpaye' ,'kmfacture' , 'frais' , 'fraisfacture' ));

			include "dupliclisting.php" ;
			$PhraseBas = 'Planning des Merch : Fiche Mise &agrave; Jour';
		break;

############## Recherche d'une mission #########################################
		case "search": 
		default: 
			$_SESSION['archive'] = 'normal' ; 
			$PhraseBas = 'Recherche d\'merchs';
			$did = '';
			include "dupliclistingsearch.php" ;
}

?>

<!-- Barre des Boutons -->
<div id="topboutons">
<?php 
# debut définition des switches
	if (($_GET['act'] != 'planningsearch') 
	AND ($_GET['act'] != 'planning') 
	AND ($_GET['act'] != 'listingsearch') 
	AND ($_GET['act'] != 'listing') 
	AND ($_GET['act'] != 'historicsearch') 
	AND ($_GET['act'] != 'historic') 
	AND ($_GET['act'] != 'listingmodif') 
	AND ($_GET['act'] != 'duplic-set') 
	AND ($_GET['act'] != 'duplic-ready') 
	AND ($_GET['act'] != 'duplic-process') 
	AND ($_GET['act'] != 'search')
	AND ($did > 0)
	) 
	{ $viewj = '1'; }

	if (($viewj == '1') and 
	(($_GET['act'] == 'facture') 
	or ($_GET['act'] == 'faclistingsearch'))) 
	{ $viewj = '2'; }

	if (($_SESSION['roger'] == 'devel') or ($_SESSION['roger'] == 'admin'))
	{ $viewperm = '1'; }
# Fin définition des switches
?>

<table border="0" cellspacing="1" cellpadding="0">
	<tr>
		<td class="onmerch"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=duplic-set"><img src="<?php echo STATIK ?>illus/semaine.gif" alt="semaine.gif" width="32" height="32" border="0"><br>Cr&eacute;ation</a></td>
		<td class="onmerch"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=add"><img src="<?php echo STATIK ?>illus/ajouter.gif" alt="ajouter" width="32" height="32" border="0"><br>Ajouter</a></td>
		<td class="onmerch"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=listingsearch"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
		<td class="onmerch"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=listing&listing=direct"><img src="<?php echo STATIK ?>illus/liste.gif" alt="search" width="32" height="32" border="0"><br>Mes Jobs</a></td>
		<?php if (($viewj == '1') and ($_SESSION['archive'] != 'archive')){ ?>
			<td class="onmerch"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=duplic&idmerch=<?php echo $did ;?>"><img src="<?php echo STATIK ?>illus/dupliquer.gif" alt="show" width="32" height="32" border="0"><br>Dupliquer</a></td>
			<td class="onmerch"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=delete&idmerch=<?php echo $did ;?>" onClick="return confirm('Etes-vous certain de vouloir effacer ce job?')"><img src="<?php echo STATIK ?>illus/trash.gif" alt="show" width="32" height="32" border="0"><br>Effacer Job</a></td>
		<?php } ?>
		<?php if ($_SESSION['jobquid'] != "") { ?>
			<td class="onmerch"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=listing&action=skip"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Retour Liste</a></td>
		<?php } ?>
		<td class="onmerch"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=merge"><img src="<?php echo STATIK ?>illus/download.gif" width="29" height="32" border="0"><br>Finaliser</a></td>
		<?php if ($viewperm == '1') { ?><?php } ?>
	</tr>
</table> 
</div>

<?php
# Pied de Page
include NIVO."includes/pied.php" ;
?>