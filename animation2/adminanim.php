<?php 
/*
	TODO : Pat Code, repasser partout...
*/
define('NIVO', '../');

# Entete de page
$Titre = 'ANIM 2';
$Style = 'anim';

## Decl Vars
if (empty($facturation)) $facturation = '';
if (empty($idclient)) $idclient = '';
if (empty($_GET['down'])) $_GET['down'] = '';
if (empty($_POST['modprod'])) $_POST['modprod'] = '';
if (empty($_POST['modstate'])) $_POST['modstate'] = '';
if (empty($_POST['modallprod'])) $_POST['modallprod'] = '';
if(empty($_GET['listing'])) $_GET['listing']= '';

$idanimation = $_REQUEST['idanimation'];
$idanimjob = $_REQUEST['idanimjob'];

# Classes utilisees
if ($_GET['down'] == 'down') { ### $down ==> pour les pages en IFRAMES
	$down = 'down';
	include NIVO.'includes/ifentete.php';
} else {
	$down = 'up';
	include NIVO.'includes/entete.php';
} #/## $down ==> pour les pages en IFRAMES

include NIVO.'classes/anim.php';
include NIVO.'classes/hh.php';
include NIVO.'classes/geocalc.php';
include NIVO.'classes/notefrais.php';
include NIVO.'classes/makehtml.php';

include 'm-onglet.php';

# Carousel des fonctions
$onemonthago  = date("Y-m-01", mktime (0,0,0,date("m"),date("d") + 1,date("Y")));

#### Trouve le num de Job si on a uniquement le num de mission
	if (empty($idanimjob) and ($idanimation > 1)) {
		$idanimjob = $DB->getOne("SELECT idanimjob FROM `animation` WHERE `idanimation` = ".$_REQUEST['idanimation']);
	}
#/## num job
switch ($_REQUEST['act']) {

### JOB
	############## Ajout d'un JOB #############################################
			case "addjob":
				$_POST['idagent'] = $_SESSION['idagent'];
				$ajout = new db('animjob', 'idanimjob');
				$ajout->AJOUTE(array('idclient' , 'idcofficer', 'idagent'));
				$_REQUEST['idanimjob'] = $ajout->addid;

				$PhraseBas = 'Nouvelle Action Anim';
				include NIVO.'animation2/v-jobDetail.php';
			break;
	############## Affichage d'un JOB - Général #########################################
			case "showjob":
				$PhraseBas = 'Detail d\'une Action Animation';
				include NIVO.'animation2/v-jobDetail.php';
			break;
	############## Selection des Client #############################################
			case "selectclient":
				$_SESSION['archive'] = 'normal' ;
				$PhraseBas = 'Selection des clients';
				$etat = $_GET['etat'];
				include NIVO.'animation2/job-select-client.php';
			break;
	############## Modification agents ou Client et affichage d'un Job #########################################
			case "modifselect":
				$_SESSION['archive'] = 'normal' ;
				switch ($_GET['mod']) {
				#### Mise à jour des infos Client
						case "client":

							// Récupération des anciennes valeurs
							$result = $DB->getRow('SELECT idclient, idcofficer FROM animjob WHERE idanimjob = '.$_POST['idanimjob']);
							$oldClient = $result['idclient'];
							$oldOfficer = $result['idcofficer'];
							
							// Insertion dans le journal
							if ($oldClient==0) { // Ajout du client
								$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idclient, idcofficer) VALUE(".$_SESSION['idagent'].", 'ADD', 'JOB', 'CLIENT', 'AN', ".$_POST['idanimjob'].", ".$_POST['idclient'].", ".$_POST['idcofficer'].")");
								$PhraseBas = "AJOUT";
							} elseif ($_POST['idclient']=='') { // Suppression du client
								$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idclient, idcofficer) VALUE(".$_SESSION['idagent'].", 'DEL', 'JOB', 'CLIENT', 'AN', ".$_POST['idanimjob'].", ".$oldClient.", ".$oldOfficer.")");							
								$PhraseBas = "SUPPRESSION";
							} else { // Modification du client
								$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idclient, idcofficer) VALUE(".$_SESSION['idagent'].", 'MOD-DEL', 'JOB', 'CLIENT', 'AN', ".$_POST['idanimjob'].", ".$oldClient.", ".$oldOfficer.")");
								$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idclient, idcofficer) VALUE(".$_SESSION['idagent'].", 'MOD-ADD', 'JOB', 'CLIENT', 'AN', ".$_POST['idanimjob'].", ".$_POST['idclient'].", ".$_POST['idcofficer'].")");
								$PhraseBas = "INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idclient, idcofficer) VALUE(".$_SESSION['idagent'].", 'MOD-DEL', 'JOB', 'CLIENT', 'AN', ".$_POST['idanimjob'].", ".$oldClient.", ".$oldOfficer.")";
							}

							$modif = new db('animjob', 'idanimjob');
							$modif->MODIFIE($idanimjob, array('idclient' , 'idcofficer'));
							$_POST['anim'] = 1; ### pour flager le client comme un client anim
							$idclient = $_POST['idclient'];
						break;
				}
				include NIVO.'animation2/v-jobDetail.php';
			break;

	############## Mise à jour du Job #############################################
			case "modifjobfull":
				$DB->MAJ('animjob');
				#*# Mise des missions en fac = 98 si job est archivŽ
				if ($_POST['statutarchive'] == 'canceled') $DB->inline("UPDATE `animation` SET facturation = 98 WHERE `idanimjob` = '".$idanimjob."' AND facturation = 1");

				$PhraseBas = 'Detail d\'une Animation : Fiche Mise &agrave; Jour';

				include NIVO.'animation2/v-jobDetail.php';
			break;
	############## Recherche des Action : Search #############################################
		case "listingjobsearch":
				$_SESSION['archive'] = 'normal' ;
				$_SESSION['view'] = 'n' ;
				include 'v-jobSearch.php';

				$PhraseBas = 'Listing des Actions ANIM';
			break;
	############## Recherche des Action : Listing #############################################
			case "listingjob":
				$_SESSION['view'] = 'n';
				$_SESSION['archive'] = 'normal';
				include NIVO.'animation2/v-jobList.php';
				$PhraseBas = 'Planning des ANIM';
			break;
############## DELETE d'un JOB et du contenu ADMIN ONLY #########################################
		######## !!!!!!!!
		######## !!!!!!!!
		######## !!!!!!!!  DELETE  : AGENT = CELSYS /  CLIENT = 1498 ou 1499
				case "delcelsys":
					$did = $_GET['idanimjob'];
					$idanimjob = $_GET['idanimjob'];

					switch ($_GET['etat']) {
					############## JOB etat = 0 = DEVIS #############################################
						case "0":
						case "1":
						default:
							############## DELETE des Missions de ce JOB ################################
							if ($_GET['etat'] >= 0) {
								$prod1 = new db('animation', 'idanimation');
								$prod1->inline("SELECT idanimation FROM `animation` WHERE `idanimjob` = $idanimjob;");
								while ($infosanim = mysql_fetch_array($prod1->result)) {
									$idanimation = $infosanim['idanimation'];

									############## Recherche infos d'une Mission animation
									$anim1 = new db();
									$anim1->inline("SELECT * FROM `animation` WHERE `idanimation` = $idanimation;");
									$infosduplic = mysql_fetch_array($anim1->result) ;
										$idagent = $infosduplic['idagent'];
										$idclient = $infosduplic['idclient'];
										$idcofficer = $infosduplic['idcofficer'];

										$idshop = $infosduplic['idshop'];
										$idpeople = $infosduplic['idpeople'];
										$reference = addslashes($infosduplic['reference']);
										$produit=addslashes($infosduplic['produit']);
										$boncommande = addslashes($infosduplic['boncommande']);
										$datem = $infosduplic['datem'];
										$hin1 = $infosduplic['hin1'];
										$hout1 = $infosduplic['hout1'];
										$hin2 = $infosduplic['hin2'];
										$hout2 = $infosduplic['hout2'];
										$ferie = $infosduplic['ferie'];
										$kmauto = $infosduplic['kmauto'];
										$kmpaye = $infosduplic['kmpaye'];
										$kmfacture = $infosduplic['kmfacture'];
										$noteanim=addslashes($infosduplic['noteanim']);
										$genre = $infosduplic['genre'];
										$datecontrat = $infosduplic['datecontrat'];
									$didold = $idanimation;

									############## Duplication zoutanimation d'une Mission animation
									$detailanim = new db('zoutanimation', 'idanimation');
									$detailanim->inline("INSERT INTO `zoutanimation` (`idanimation` , `idanimjob` , `idagent` , `idclient` , `idcofficer` , `idshop` , `idpeople`, `reference`, `produit` , `boncommande` , `datem` , `hin1` , `hout1` , `hin2` , `hout2` , `ferie` , `kmpaye` , `kmfacture`, `noteanim` , `genre` , `datecontrat` , `kmauto` ) VALUES ('$idanimation', '$idanimjob', '$idagent', '$idclient' , '$idcofficer' , '$idshop' , '$idpeople' , '$reference', '$produit' , '$boncommande' , '$datem' , '$hin1' , '$hout1' , '$hin2' , '$hout2' , '$ferie' , '$kmpaye' , '$kmfacture' , '$noteanim' , '$genre' , '$datecontrat' , '$kmauto');");

									############## Duplication zoutanimproduit des animproduit d'une Mission animation
									$prod1 = new db('animproduit', 'idanimproduit');
									$prod1->inline("SELECT * FROM `animproduit` WHERE `idanimation` = $didold;");
									while ($infosduplic = mysql_fetch_array($prod1->result)) {
										$idanimproduit = $infosduplic['idanimproduit'];
										$types = addslashes($infosduplic['types']);
										$prix = $infosduplic['prix'];

										$produitin = $infosduplic['produitin'];
										$unite = $infosduplic['unite'];
										$produitend = $infosduplic['produitend'];
										$ventes = $infosduplic['ventes'];
										$produitno = $infosduplic['produitno'];
										$degustation = $infosduplic['degustation'];
										$promoin = $infosduplic['promoin'];
										$promoout = $infosduplic['promoout'];
										$promoend = $infosduplic['promoend'];

										############## Duplication d'un zoutanimproduit
											$prod2 = new db();
											$prod2->inline("INSERT INTO `zoutanimproduit` (`idanimproduit`, `idanimation`, `types` , `prix` , `produitin` , `unite` , `produitend` , `ventes` , `produitno` , `degustation` , `promoin` , `promoout` , `promoend` ) VALUES ('$idanimproduit', '$idanimation', '$types' , '$prix' , '$produitin' , '$unite' , '$produitend' , '$ventes' , '$produitno' , '$degustation' , '$promoin' , '$promoout' , '$promoend' );");

										############## Delete d'un zoutanimproduit
											$sqldel = "DELETE FROM `animproduit` WHERE `idanimproduit` = $idanimproduit;"; $proddel = new db(); $proddel->inline($sqldel);
									}

									############## Duplication zoutanimmateriel de l'animmateriel d'une Mission animation
										$mat1 = new db('animmateriel', 'idanimmateriel');
										$mat1->inline("SELECT * FROM `animmateriel` WHERE `idanimation` = $idanimation");
										$matos = mysql_fetch_array($mat1->result) ;
										$idanimmateriel = $matos['idanimmateriel'];
										$stand = $matos['stand'];
										$livraison = $matos['livraison'];
										$gobelet = $matos['gobelet'];
										$serviette = $matos['serviette'];
										$four = $matos['four'];
										$curedent = $matos['curedent'];
										$cuillere = $matos['cuillere'];
										$rechaud = $matos['rechaud'];
										$autre = $matos['autre'];

										############## Duplication d'un animmateriel
											$mat2 = new db('zoutanimmateriel', 'idanimmateriel');
											$mat2->inline("INSERT INTO `zoutanimmateriel` (`idanimmateriel`, `idanimation`, `stand` , `gobelet` , `serviette` , `four` , `curedent` , `cuillere` , `rechaud` , `autre` ) VALUES ('$idanimmateriel', '$idanimation', '$stand' , '$gobelet' , '$serviette' , '$four' , '$curedent' , '$cuillere' , '$rechaud' , '$autre' );");

										############## Delete d'un animmateriel
											$matdel = new db('animmateriel', 'idanimmateriel');
											$sqldel = "DELETE FROM `animmateriel` WHERE `idanimmateriel` = $idanimmateriel;";
											$matdel->inline($sqldel);

										############## Delete d'un Job  Animation
										$animdel = new db('animation', 'idanimation');
										$sqldel = "DELETE FROM `animation` WHERE `idanimation` = $idanimation;";
										$animdel->inline($sqldel);
									#/############# Duplication zoutanimmateriel de l'animmateriel d'une Mission animation

								}
							}
					}
					############## Duplication zoutanimjob d'un Job animation
						############## Recherche infos d'un Job animation
						$animjob1 = new db();
						$animjob1->inline("SELECT * FROM `animjob` WHERE `idanimjob` = $idanimjob;");
						$infosduplic = mysql_fetch_array($animjob1->result) ;
#`idanimjob` , `idagent` , `idclient` , `idcofficer` , `idshop` , `genre` , `reference` , `etat` , `boncommande` , `datecommande` , `datein` , `dateout` , `notejob` , `noteprest` , `notedeplac` , `noteloca` , `notefrais` , `briefing` , `casting` , `datedevis` , `planningweb` , `webdoc` , `agentmodif` , `datemodif` , `facnum` , `facnumtemp`

							$idagent = $infosduplic['idagent'];
							$idclient = $infosduplic['idclient'];
							$idcofficer = $infosduplic['idcofficer'];

							$idshop = $infosduplic['idshop'];
							$idpeople = $infosduplic['idpeople'];
							$reference = $infosduplic['reference'];
							$produit=addslashes($infosduplic['produit']);
							$boncommande = $infosduplic['boncommande'];
							$datem = $infosduplic['datem'];
							$hin1 = $infosduplic['hin1'];
							$hout1 = $infosduplic['hout1'];
							$hin2 = $infosduplic['hin2'];
							$hout2 = $infosduplic['hout2'];
							$ferie = $infosduplic['ferie'];
							$kmauto = $infosduplic['kmauto'];
							$kmpaye = $infosduplic['kmpaye'];
							$kmfacture = $infosduplic['kmfacture'];
							$briefing = $infosduplic['briefing'];
							$noteanim=addslashes($infosduplic['noteanim']);
							$genre = $infosduplic['genre'];
							$datecontrat = $infosduplic['datecontrat'];
						$didold = $idanimation;

						############## Duplication zoutanimjob d'un Job animation

						$detailanimjob = new db('zoutanimjob', 'idanimjob');
						$detailanimjob->inline("INSERT INTO `zoutanimjob` (`idanimjob` , `idagent` , `idclient` , `idcofficer` , `idshop` , `genre` , `reference` , `etat` , `boncommande` , `datecommande` , `datein` , `dateout` , `notejob` , `noteprest` , `notedeplac` , `noteloca` , `notefrais` , `briefing` , `casting` , `datedevis` , `planningweb` , `webdoc` , `agentmodif` , `datemodif` , `facnum` , `facnumtemp`) VALUES ('$idanimjob' , '$idagent' , '$idclient' , '$idcofficer' , '$idshop' , '$genre' , '$reference' , '$etat' , '$boncommande' , '$datecommande' , '$datein' , '$dateout' , '$notejob' , '$noteprest' , '$notedeplac' , '$noteloca' , '$notefrais' , '$briefing' , '$casting' , '$datedevis' , '$planningweb' , '$webdoc' , '$agentmodif' , '$datemodif' , '$facnum' , '$facnumtemp' );");

						############## Delete d'un Job  Animation
						$jobdel = new db('animjob', 'idanimjob');
						$sqldel = "DELETE FROM `animjob` WHERE `idanimjob` = $idanimjob;";
						$jobdel->inline($sqldel);
					#/############# Duplication zoutanimjob d'un Job animation

					############## Listing des Job Results ##################################
					$_GET['listing'] = 'direct';
					$sort = idagent;
					include NIVO.'animation2/v-jobList.php';
					$PhraseBas = 'Listing des ANIM';
				break;
		########
		########
		######## !!!!!!!! FIN DELETE  : AGENT = CELSYS /  CLIENT = 1498 ou 1499

#/## JOB

### Mission (page IFrame)
	############## Ajout/modif d'une mission #############################################
		case "listingmodifdevisadd":
			$_POST['facturation'] = '1';
		case "listingmodifdevis":
			$_POST['weekm'] = date('W', strtotime(fdatebk($_POST['datem'])));
			$_POST['yearm'] = date('Y', strtotime(fdatebk($_POST['datem'])));
			$_POST['idagent'] = $_SESSION['idagent'];

			if ($_GET['act'] == 'listingmodifdevisadd') {
				$Addid = $DB->ADD('animation');
				$PhraseBas = 'Nouvelle Mission Anim';
			} else {
				$DB->MAJ('animation');
				$PhraseBas = 'Planning des ANIM DEVIS: Fiche Mise &agrave; Jour';
			}

			include NIVO.'animation2/mission-listing.php';
		break;
	############### Duplication d'une Mission #############################################
		case "listingmodifdevisdupplic":
			############## Duplication de l'animation
			$animdup = new db();
			$champs = "`idanimjob`, `idagent`, `idshop`, `idpeople`, `antype`, `reference`, `produit`, `datem`, `weekm`, `yearm`, `hin1`, `hout1`, `hin2`, `hout2`, `ferie`, `kmpaye`, `kmfacture`, `livraisonpaye`, `livraisonfacture`, `noteanim`, `genre`, `datecontrat`, `peopleonline`, `standqualite`, `standnote`, `autreanim`, `autreanimnote`, `peoplenote`, `shopnote`, `facturedirect`, `facturation`, `facnumtemp`, `facnum`, `webdoc`, `agentmodif`, `datemodif`, `hhcode`, `peoplehome`, `kmauto`, `ccheck`, `tchkdate`, `tchkcomment`, `fmode`";
			$animdup->inline("INSERT INTO `animation` ($champs) SELECT $champs FROM `animation` WHERE `idanimation` = $idanimation");
			
			$newidanimation = mysql_insert_id();
			############## Duplication des produits
			$animdup->inline("INSERT INTO `animproduit` (`idanimation`, `types` , `prix` , `produitin` , `unite` , `produitend` , `ventes` , `produitno` , `degustation` , `promoin` , `promoout` , `promoend` )
							 SELECT '".$newidanimation."', `types` , `prix` , `produitin` , `unite` , `produitend` , `ventes` , `produitno` , `degustation` , `promoin` , `promoout` , `promoend`  FROM `animproduit` WHERE `idanimation` = $idanimation;");
			############## Duplication des notes de frais
			## Les notes de frais ne sont pas dupliquées

			include NIVO.'animation2/mission-listing.php';
		break;

	############### DELETE d'une Mission #############################################
		case "listingmodifdevisdel":
			$_SESSION['archive'] = 'normal';

			$del = new db();
			
			if ($idanimation > 1) {
			#
				$c = $DB->getOne("SELECT idpeople from animation where idanimation = $idanimation");
				if(!empty($c))
				{	
					$DB->inline("INSERT INTO peoplemission (dateout, idpeople, idanimation, note, motif, agentmodif, datemodif) VALUES (DATE(NOW()), $c, $idanimation, 'Delete mission avec people $c', 4, ".$_SESSION['idagent'].", NOW())");
				}
				
				$del->inline("DELETE FROM `zoutanimation` WHERE idanimation = $idanimation");
				$del->inline("INSERT INTO `zoutanimation` SELECT * FROM animation WHERE idanimation = $idanimation");
				$del->inline("DELETE FROM `animation` WHERE `idanimation` = $idanimation");
			#
				$del->inline("DELETE FROM `zoutanimproduit` WHERE idanimation = $idanimation");
				$del->inline("INSERT INTO `zoutanimproduit` SELECT * FROM animproduit WHERE idanimation = $idanimation");
				$del->inline("DELETE FROM `animproduit` WHERE `idanimation` = $idanimation;");
			#
				$del->inline("DELETE FROM `zoutanimmateriel` WHERE `idanimation` = $idanimation;");
				$del->inline("INSERT INTO `zoutanimmateriel` SELECT * FROM animmateriel WHERE idanimation = $idanimation");
				$del->inline("DELETE FROM `animmateriel` WHERE `idanimation` = $idanimation;");
			#
				$del->journal("Efface (->zoutanimation) la fiche 'animation' id=".$idanimation);
			} else {
				echo "<div id=\"sqlerror\">Perte de l'idanimation P:adminanim.php L:438</div>";
			}

			if ($down == 'down') { # Page IFrame
				include NIVO.'animation2/mission-listing.php';
			} else {
				include NIVO.'animation2/v-jobDetail.php';
			}
		break;
#/## DEVIS (page IFrame)


### MISSION

	############## affichage d'une Mission #############################################
		case "showmission":
			$PhraseBas = 'Detail d\'une animation';
			include NIVO.'animation2/mission-detail_inc.php';
		break;

	############## Selection des People #############################################
	############## Remove des People #############################################
		case "removeselect":
			$PhraseBas = 'People supprimé / ';
			$did = $_GET['idanimjob'];
			$idanimjob = $_GET['idanimjob'];
			$_POST['idanimjob'] = $_GET['idanimjob'];
			$idanimation = $_GET['idanimation'];
			$etat = $_GET['etat'];
			$_POST['dateout']  = date ("Y-m-d");
			$idpeople = $_POST['idpeople'];

			$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idpeople) VALUE(".$_SESSION['idagent'].", 'DEL', 'MISSION', 'PEOPLE', 'AN', ".$idanimjob.", ".$idanimation.", '".$idpeople."')");                        

			$ajout = new db('peoplemission', 'idpeoplemission');
			$ajout->AJOUTE(array('dateout', 'idanimation', 'idpeople', 'note', 'motif'));

			$_POST['idpeople'] = '';
			$_POST['datecontrat'] = '';
			$_POST['matospeople'] = '';
			$_POST['peoplehome'] = '';

			$modif = new db('animation', 'idanimation');
			$modif->MODIFIE($idanimation, array('idpeople', 'datecontrat', 'peoplehome'));
	############## Selection des people #############################################
		case "selectpeople":
			$_SESSION['archive'] = 'normal' ;
			$PhraseBas = 'Selection des people';
			$etat = $_GET['etat'];
			include NIVO.'animation2/mission-select-people.php';
		break;
	############## Remove + Selection des people #############################################
		case "removepeople":
			$_SESSION['archive'] = 'normal' ;
			$PhraseBas = 'Remove pour Selection des people';
			$etat = $_GET['etat'];

			include NIVO.'animation2/mission-select-p-remove.php';
		break;
	############## Modification people affichage d'une Mission #########################################
		case "modifpeople":
			$_SESSION['archive'] = 'normal' ;
			$modif = new db('animation', 'idanimation');
			$idpeople = $_POST['idpeople'];
			$idanimjob = $_POST['idanimjob'];
			
			$modif->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idpeople) VALUE(".$_SESSION['idagent'].", 'ADD', 'MISSION', 'PEOPLE', 'AN', ".$idanimjob.", ".$idanimation.", ".$idpeople.")");
			
			$modif->MODIFIE($idanimation, array('idpeople' ));
			$PhraseBas = 'Detail d\'une Animation : people Modifi&eacute;';
			if ($down == 'down') { # Page IFrame
				include NIVO.'animation2/mission-listing.php';
			} else {
				include NIVO.'animation2/mission-detail_inc.php';
			}
		break;

	############## Selection des lieux #############################################
		case "selectlieu":
			
			$_SESSION['archive'] = 'normal' ;
			$PhraseBas = 'Selection des lieux';
			$etat = $_GET['etat'];
			include NIVO.'animation2/mission-select-lieu.php';
		break;
	############## Modification lieu affichage d'une Mission #########################################
		case "modiflieu":

			// Récupération des anciennes valeurs
			$result = $DB->getRow('SELECT idshop FROM animation WHERE idanimation = '.$idanimation);
			$oldIdshop = $result['idshop'];
			
			// Insertion dans le journal
			if ($oldIdshop==0) { // Ajout du lieu
				$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idshop) VALUE(".$_SESSION['idagent'].", 'ADD', 'MISSION', 'SHOP', 'AN', ".$_POST['idanimjob'].", ".$idanimation.", ".$_POST['idshop'].")");
			} elseif ($_POST['idshop']=='') { // Suppression du lieu
				$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idshop) VALUE(".$_SESSION['idagent'].", 'DEL', 'MISSION', 'SHOP', 'AN', ".$_POST['idanimjob'].", ".$idanimation.", ".$oldIdshop.")");							
			} else { // Modification du lieu
				$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idshop) VALUE(".$_SESSION['idagent'].", 'MOD-DEL', 'MISSION', 'SHOP', 'AN', ".$_POST['idanimjob'].", ".$idanimation.", ".$oldIdshop.")");
				$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idshop) VALUE(".$_SESSION['idagent'].", 'MOD-ADD', 'MISSION', 'SHOP', 'AN', ".$_POST['idanimjob'].", ".$idanimation.", ".$_POST['idshop'].")");
			}
			
			$_SESSION['archive'] = 'normal' ;
			$modif = new db('animation', 'idanimation');
			$modif->MODIFIE($idanimation, array('idshop' ));
			$PhraseBas = 'Detail d\'une Animation : lieu Modifi&eacute;';
			if ($down == 'down') { # Page IFrame
				include NIVO.'animation2/mission-listing.php';
			} else {
				include NIVO.'animation2/mission-detail_inc.php';
			}
		break;
	#### Mise ˆ jour de la fiche complte MISSION
		case "modifmission":
			$hcode = new hh ();
			$_POST['hhcode'] = $hcode->makehhcode(ftimebk($_POST['hin1']), ftimebk($_POST['hout1']), ftimebk($_POST['hin2']), ftimebk($_POST['hout2']));

			$_POST['weekm'] = date('W', strtotime(fdatebk($_POST['datem'])));
			$_POST['yearm'] = date('Y', strtotime(fdatebk($_POST['datem'])));

			if(isset($_POST['kmfacture']) and isset($_POST['kmpaye'])) $_POST['kmfacture'] = ($_POST['kmfacture'] == '0')?$_POST['kmpaye']:fnbrbk($_POST['kmfacture']);
			if(isset($_POST['livraisonfacture']) and isset($_POST['livraisonpaye'])) $_POST['livraisonfacture'] = ($_POST['livraisonfacture'] == '0')?$_POST['livraisonpaye']:fnbrbk($_POST['livraisonfacture']);
			
			if (empty($_POST['ccheck'])) $_POST['ccheck'] = 'N';
			if (empty($_POST['kmauto'])) $_POST['kmauto'] = 'N';

			$DB->MAJ('animation');

			## Mise a jour de la note de frais
			updnotefrais($idanimation, 'AN', fnbrbk($_POST['nfrais-montantpaye']), fnbrbk($_POST['nfrais-montantfac']),$_POST['nfraisyn'], trim($_POST['nfrais-intitule']), trim($_POST['nfrais-descr']));
			
			$PhraseBas = 'Detail d\'une Animation : Fiche Mise &agrave; Jour';

			include NIVO.'animation2/mission-detail_inc.php';
		break;
	############### planning des Missions Search #############################################
## NOT OK
		case "listingmissionsearch":
			$_SESSION['archive'] = 'normal' ;
			$_SESSION['view'] = 'n' ;
			include 'v-missionSearch.php';

			$PhraseBas = 'Listing des ANIM';
		break;

	############### Listing des missions Results #############################################
## NOT OK
		case "listingmissionresult":
			if (($_GET['listing'] == 'direct') OR ($_GET['listing'] == 'missing')) {
				$_SESSION['view'] = 'n';
				$_SESSION['archive'] = 'normal';
				$_GET['listtype'] = '0';
			}
			switch ($_SESSION['archive']) {
				#### Normal
				case "normal":
					if ($_SESSION['view'] == 'f') {
						include NIVO.'animation2/mission-factoring'.$_SESSION['listtype'].'.php';
						$PhraseBas = 'FACTORING des ANIM';
					} else {
						include NIVO.'animation2/mission-listing.php';
						$PhraseBas = 'Planning des ANIM';
					}
				break;
				#### Normal
				case "archive":
					include NIVO.'animation2/mission-listing.php';
				break;
			}

		break;
	############### Affichage du listing des Missions EN DEVIS #########################################
		case "listing":

		## Ajout produit 1 Mission
			if ($_POST['modprod'] == 'Add') {
				$updprod = new db(); $updprod->inline("INSERT INTO `animproduit` (`idanimation` , `types`) VALUES ('".$_POST['idanimation']."' , '".addslashes($_POST['produit'])."');");
			}

		## Ajout produit toutes Mission
			if ($_POST['modallprod'] == 'Add') {
				$fndmissions = new db();
				$fndmissions->inline("SELECT idanimation FROM animation WHERE idanimjob = '".$_POST['idanimjob']."'");

				if (mysql_num_rows($fndmissions->result) > 0) {

					$sql = "INSERT INTO `animproduit` (`idanimation` , `types`) VALUES ";

					while ($miss = mysql_fetch_array($fndmissions->result)) {
						$sql .= "('".$miss['idanimation']."' , '".addslashes($_POST['produit'])."'),";
					}

					$sql = substr($sql, 0, -1).';';
				}

				$updprod = new db(); $updprod->inline($sql);
			}


			$PhraseBas = 'Detail d\'une Anim Devis';
			include NIVO.'animation2/mission-listing.php';
		break;

###############################################################################################################################
### Modifs venant de Infos Editables et de Matériel ##########################################################################
#############################################################################################################################
		case "listingmodif":
			$_SESSION['archive'] = 'normal' ;
				## Dates ##############
				$_POST['weekm'] = date('W', strtotime(fdatebk($_POST['datem'])));
				$_POST['yearm'] = date('Y', strtotime(fdatebk($_POST['datem'])));

				## Deplacements #######
				if (($_POST['kmfacture'] == '0') and ($_POST['kmpaye'] > 0)) $_POST['kmfacture'] = $_POST['kmpaye'];

				## Livraison ##########
				if ($_POST['livraisonfacture'] == '0') $_POST['livraisonfacture'] = $_POST['livraisonpaye'];

				## Venant de Infos Editables
				if ($_GET['matos'] != 'yes') {
					$DB->MAJ('animation', 'ccheck|kmauto');
					$dist = CalcDeplacement('AN', $_POST['idanimation']);

					## Mise a jout de la note de frais
					updnotefrais($idanimation, 'AN', fnbrbk($_POST['montantpaye']), fnbrbk($_POST['montantfacture']),$_POST['nfraisyn'] );
				}
				## Venant de Matériel
				if ($_GET['matos'] == 'yes') {
					$DB->MAJ('animation');

					if (empty($_POST['idanimmateriel'])) {

						$_POST['idanimation'] = $idanimation;

						$ajout = new db('animmateriel', 'idanimmateriel');
						$ajout->AJOUTE(array('idanimation', 'stand' , 'gobelet' , 'serviette' , 'four' , 'curedent' , 'cuillere' , 'rechaud' , 'autre' ));
					} else {
						$modif = new db('animmateriel', 'idanimmateriel');
						$modif->MODIFIE($_POST['idanimmateriel'], array('stand' , 'gobelet' , 'serviette' , 'four' , 'curedent' , 'cuillere' , 'rechaud' , 'autre' ));
					}
				}
				include NIVO.'animation2/mission-listing.php';
			$PhraseBas = 'Planning des ANIM : Fiche Mise &agrave; Jour';
		break;

#
#### HISTORIQUE
#			############## HISTORIQUE des Missions Search #############################################
## NOT OK
		case "historicsearch":
			$_SESSION['archive'] = 'archive' ;
			$_SESSION['view'] = 'n' ;
			include NIVO.'animation2/mission-historicsearch.php';
			$PhraseBas = 'historic des ANIM';
		break;
#
#		############## HISTORIQUE des Job Results #############################################
## NOT OK
		case "historic":
			$_SESSION['archive'] = 'archive' ;
			if (($_GET['listing'] == 'direct') OR ($_GET['listing'] == 'missing')) {
				$_SESSION['view'] = 'n';
			}
				include NIVO.'animation2/mission-historic.php';
				$PhraseBas = 'historic des ANIM';
		break;
#

############### Listing des FACTURATION #############################################
		case "facture":
			if (($_POST['modstate'] == 'Valider') and ($_POST['state'] > 0)) {
				$DB->inline("UPDATE animation SET facturation = '".$_POST['state']."' WHERE idanimation IN (".implode(", ", $_POST['ids']).");");
			}
			
			$mode = 'USER';

			include NIVO.'admin/animation/factoring.php';
			$PhraseBas = 'FACTORING des ANIM';
		break;

### MOULINETTE CREA-MISSSION
		case "moulinette":
			### Search animbuild et produits ############################################
			$infosbuild = $DB->getArray("SELECT * FROM `animbuild` WHERE `idanimjob` = '".$_REQUEST['idanimjob']."' ORDER BY 'idanimjob'");
			$produitsbuild = $DB->getOne("SELECT COUNT(*) FROM `animbuildproduit` WHERE `idanimjob` = ".$_REQUEST['idanimjob']);

			$i=0;
			foreach ($infosbuild as $infos) {
				$i++;
				$h = 0; #pour nombre hotesse
				$animdate1 = $infos['animdate1'];
				### fragmentation par date
				while ($animdate1 <= $infos['animdate2']) {
					$h++;
					$animnombre = $infos['animnombre'];
					### fragmentation par nombre dans date
					while ($animnombre > 0) {
						### fragmentation par POS par nombre dans date
						$f = array_filter(explode("|", $infos['shopselectionbuild']));

						foreach ($f as $val) {
							$val = trim($val);
							### si tableau des selection des shops est non vide
							if (is_numeric($val)) {
								$delbuildok = 1; # Switch pour del oui non des animbuild

								### Injection Animation
								$DB->inline("INSERT INTO `animation` SET
									`idanimjob` = '".$_REQUEST['idanimjob']."',
									`idshop` = '".$val."',
									`idagent` = '".$_SESSION['idagent']."',
									`datem` = '".$animdate1."',
									`weekm` = '".date('W', strtotime($animdate1))."',
									`yearm` = '".date('Y', strtotime($animdate1))."',
									`hin1` = '".$infos['animin1']."',
									`hout1` = '".$infos['animout1']."',
									`hin2` = '".$infos['animin2']."',
									`hout2` = '".$infos['animout2']."',
									`kmauto` = '".(($infos['kmauto'] == 'Y')?'Y':'N')."',
									`kmfacture` = '".(($infos['kmfacture'] > 0)?$infos['kmfacture']:0)."',
									`kmpaye` = '".(($infos['kmfacture'] > 0)?$infos['kmfacture']:0)."',
									`livraisonpaye` = '".$infos['livraisonpaye']."',
									`livraisonfacture` = '".$infos['livraisonfacture']."',
									`produit` = '".addslashes($infos['promo'])."'");
									
								$idanimation = $DB->addid;

								## Produits
								if ($produitsbuild > 0) {
									$fields = "`types`, `prix`, `produitin`, `unite`, `produitend`, `ventes`, `produitno`, `degustation`, `promoin`, `promoout`, `promoend`";
									$DB->inline("INSERT INTO animproduit (`idanimation`, ".$fields.", `datemodif`, `agentmodif`) SELECT ".$idanimation.", ".$fields.", NOW(), ".$_SESSION['idagent']." FROM `animbuildproduit` WHERE `idanimjob` = ".$_REQUEST['idanimjob']);
								}

								## Materiel (stand)
								$DB->inline("INSERT INTO animmateriel SET
									`idanimation` = '".$idanimation."',
									`stand` = '".$infos['stand']."'");
							}
						}
						$animnombre--;
					}
					$animdate1 = date ("Y-m-d", strtotime("+1 day", strtotime($animdate1)));
				}
			}

			############## Delete des animbuild de ce JOB ################################
			if ($delbuildok == 1) {	## si au moins 1 mission a été crée
				$DB->inline("DELETE FROM `animbuild` WHERE `idanimjob` = ".$_REQUEST['idanimjob']);
				$DB->inline("DELETE FROM `animbuildproduit` WHERE `idanimjob` = ".$_REQUEST['idanimjob']);
			}

			$PhraseBas = 'Validation d\'un JOB';
			include NIVO.'animation2/v-jobDetail.php';
		break;

############## Web #############################################
	############## Web list #############################################
		case "webanimlist":
			$PhraseBas = 'Liste des nouvelles demandes On-line';
			include NIVO.'animation2/weblist.php';
		break;

	############## Web show #############################################
		case "webshow":
			$idwebanimjob = $_GET['idwebanimjob'];
			$PhraseBas = 'Affichage des nouvelles demandes On-line';
			include NIVO.'animation2/webdetail.php';
		break;
	############## VALIDATION d'un JOB #########################################
		case "webvalider":
			$idwebanimjob = $_POST['idwebanimjob'];
			$did = $_POST['idwebanimjob'];

			# 1 webanimjob
				### search webanimjob ###
					$detailjob = new db('webanimjob', 'idwebanimjob ', 'webneuro');
					$detailjob->inline("SELECT * FROM `webanimjob` WHERE `idwebanimjob` = $idwebanimjob ");
					$infosjob = mysql_fetch_array($detailjob->result) ;
				#/## search webanimjob ###
			# 2 animjob
				### Add animjob  + update ###
					$_POST['idclient'] = $infosjob['idclient'];
					$idclient = $infosjob['idclient'];
					$_POST['idcofficer'] = $infosjob['idcofficer'];
					$idcofficer = $infosjob['idcofficer'];
					$_POST['idagent'] = $_SESSION['idagent'];
					$idagent = $infosjob['idagent'];

					$_POST['reference'] = addslashes($infosjob['reference']);
					$_POST['boncommande'] = addslashes($infosjob['bondecommande']);
					$_POST['notejob'] = addslashes($infosjob['notejob']);
					$_POST['datein'] = $infosjob['datein'];
					$_POST['dateout'] = $infosjob['dateout'];
					$_POST['dateout'] = $infosjob['dateout'];
					$_POST['shopselection'] = $infosjob['shopselection'];

					# Add
					$ajout = new db('animjob', 'idanimjob');
					$ajout->AJOUTE(array('idclient' , 'idcofficer', 'idagent', 'etat'));
					$did = $ajout->addid;
					$idanimjob = $ajout->addid;
					# update
					$modif = new db('animjob', 'idanimjob');
					$modif->MODIFIE($idanimjob, array('idclient' , 'idcofficer', 'idagent', 'etat', 'reference' , 'notejob' , 'boncommande' , 'datein' , 'dateout' , 'shopselection' ));
				#/## Add animjob  + update ###
			# 3 animdevis
				### Search Webanimbuild et creation animdevis
						#Search
						$detailbuild = new db('webanimbuild', 'idanimbuild', 'webneuro');
						$detailbuild->inline("SELECT * FROM `webanimbuild` WHERE `idwebanimjob` = $idwebanimjob ORDER BY 'animactivite'");
						while ($infos = mysql_fetch_array($detailbuild->result)) {
							$i++;
							$h = 0; #pour nombre hotesse
							$animnombre = $infos['animnombre'];
							#fragmentation par nombre dans date
							while ($animnombre > 0) {
								#Injection
								############## Duplication d'une Mission animMISSION #############################################
								$datem = $infos['animdate1'];
								$weekm = date('W', strtotime($infos['animdate1']));
								$yearm = date('Y', strtotime($infos['animdate1']));

								$animactivite = $infos['animactivite'];
								$sexe = $infos['sexe'];
								$hin1 = $infos['animin1'];
								$hout1 = $infos['animout1'];
								$hin2 = $infos['animin2'];
								$hout2 = $infos['animout2'];
								$idshop = $infos['idshop'];

								$detailanim2 = new db('animation', 'idanimation');
								$detailanim2->inline("INSERT INTO `animation` (`idanimjob` , `idshop` , `idclient`, `idcofficer`, `idagent`, `datem`,  `weekm` ,  `yearm` , `hin1` , `hout1`, `hin2` , `hout2`) VALUES ('$idanimjob' , '$idshop' , '$idclient', '$idcofficer', '$idagent', '$datem' , '$weekm' , '$yearm' , '$hin1' , '$hout1', '$hin2' , '$hout2');");
								############## Duplication d'une Mission animMISSION #############################################
								$animnombre--;
							}
						}
				# 4 Move des fichiers annexés
					############## Delete des fichier annexé de ce job de ce JOB ################################
						$path = Conf::read('Env.root').'../media/annexe/anim/';
						$path2 = Conf::read('Env.root').'../media/annexe/animweb/';
						$ledir = $path;
						$d = opendir ($ledir);
						$nomanim = 'anim-'.$idwebanimjob.'-';
						$nomanim2 = 'anim-'.$idanimjob.'-';
						while ($name = readdir($d)) {
							if (
								($name != '.') and
								($name != '..') and
								($name != 'index.php') and
								($name != 'index2.php') and
								($name != 'temp') and
								(strchr($name, $nomanim))
							) {
								if (!empty($name)) {
									$anim1 = $path.$name;
									$anim2a = str_replace($nomanim, $nomanim2, $name);
									$anim2 = $path2.$anim2a;
									rename($anim1, $anim2);
								}
							}
						}
						closedir ($d);

						#/ ## Search Webanimbuild et creation animdevis

			# 5 webanimjob
				### Delete des webanimbuild et Delete des webanimjob
					############## Delete des webanimbuild de ce JOB ################################
						$jobdel1 = new db('webanimbuild', 'idanimbuild', 'webneuro');
						$sqldel1 = "DELETE FROM `webanimbuild` WHERE `idwebanimjob` = $idwebanimjob;";
						$jobdel1->inline($sqldel1);

					############## PAS DE DEVIS + DEL ###########################################
						$jobdel2 = new db('webanimjob', 'idwebanimjob ', 'webneuro');
						$sqldel2 = "DELETE FROM `webanimjob` WHERE `idwebanimjob` = $idwebanimjob;";
						$jobdel2->inline($sqldel2);
						$oldidanimjob = $idwebanimjob;
				#/ ## Delete des webanimbuild et Delete des webanimjob

			$PhraseBas = 'Validation d\'un JOB';
			include NIVO.'animation2/v-jobDetail.php';
		break;

	############## Web Delete #############################################
		case "webdelete":
			$idwebanimjob = $_POST['idwebanimjob'];
			$did = $_POST['idwebanimjob'];

			############## Delete des fichier annexé de ce job de ce JOB ################################

				$path = Conf::read('Env.root').'media/annexe/animweb/';
				$ledir = $path;
				$d = opendir ($ledir);
				$nomanim = 'anim-'.$idwebanimjob.'-';
				while ($name = readdir($d)) {
					if (
						($name != '.') and
						($name != '..') and
						($name != 'index.php') and
						($name != 'index2.php') and
						($name != 'temp') and
						(strchr($name, $nomanim))
					) {
						if (!empty($name)) {
							if(!unlink("$ledir$name")) die ("this file cannot be delete");
						}
					}
				}
				closedir ($d);

			############## Delete des webanimbuild de ce JOB ################################
				$jobdel1 = new db('webanimbuild', 'idanimbuild', 'webneuro');
				$sqldel1 = "DELETE FROM `webanimbuild` WHERE `idwebanimjob` = $idwebanimjob;";
				$jobdel1->inline($sqldel1);

			############## PAS DE DEVIS + DEL ###########################################
				$jobdel2 = new db('webanimjob', 'idwebanimjob ', 'webneuro');
				$sqldel2 = "DELETE FROM `webanimjob` WHERE `idwebanimjob` = $idwebanimjob;";
				$jobdel2->inline($sqldel2);

			$PhraseBas = 'Liste des nouvelles demandes On-line';
			include NIVO.'animation2/weblist.php';
		break;
#/############# Web #############################################
}

# Pied de Page
if ($down != 'down') {
?>
	<!-- Barre des Boutons -->
	<div id="topboutonsanim">
		<table border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td class="on"><a href="?act=addjob"><img src="<?php echo STATIK ?>illus/ajouter.gif" alt="ajouter" width="32" height="32" border="0"><br>Ajout Action</a></td>
				<td class="on"><a href="?act=listingjobsearch"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
			<?php if (!empty($_SESSION['animjobquid'])) { /* retour liste JOB */ ?>
				<td class="on"><a href="?act=listingjob&action=skip"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Liste Job</a></td>
			<?php } ?>
				<td class="on"><a href="?act=listingjob&listing=direct"><img src="<?php echo STATIK ?>illus/liste.gif" alt="search" width="32" height="32" border="0"><br>Mes Jobs</a></td>
			<?php if (!empty($idanimjob)){ /* Retour Job */ ?>
				<td class="on"><a href="?act=showjob&idanimjob=<?php echo $idanimjob ;?>"><img src="<?php echo STATIK ?>illus/selectionner2.gif" alt="show" width="32" height="32" border="0"><br>Retour Job</a></td>
			<?php } ?>
			<?php if (!empty($idanimjob)) { /* Offre */ ?>
				<td class="on"><a href="javascript:;" onClick="OpenBrWindow('<?php echo NIVO ?>print/anim/facture/offre.php?idanimjob=<?php echo $idanimjob ;?>','Main','menubar=no, scrollbars=yes,status=yes,resizable=yes','300','500','true')"><img src="<?php echo STATIK ?>illus/printr01.gif" alt="print" width="32" height="32" border="0"><br>Offre</a></td>
			<?php } ?>
			<td class="sep" width="25"><br></td>
			<td class="on"><a href="?act=listingmissionsearch"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
			<td class="on"><a href="?act=listingmissionresult&listing=missing"><img src="<?php echo STATIK ?>illus/todo.gif" alt="search" width="32" height="32" border="0"><br>To do</a></td>
			<?php if (!empty($_SESSION['animmissionquid'])) { /* retour liste Mission */ ?>
				<td class="on"><a href="?act=listingmissionresult&action=skip"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Retour Liste</a></td>
			<?php } ?>
				<td class="on"><a href="?act=facture"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rech Fac</a></td>
			<?php if (!empty($_SESSION['animfacquid'])) { /* Liste Facture */ ?>
				<td class="on"><a href="?act=facture&action=skip"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Liste Facture</a></td>
			<?php } ?>
				<td class="on"><a href="?act=historicsearch"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Historic</a></td>
			<?php if (!empty($_SESSION['animhistoricquid'])) { ?>
				<td class="on"><a href="?act=historic&action=skip"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="historic" width="32" height="32" border="0"><br>Liste Historic</a></td>
			<?php }

				if (($idanimjob > 0) and ($facturation < 3)) {
					if (($_SESSION['roger'] == 'devel') and (($idclient == 1498) or ($idclient == 1499))) {
			?>
						<td class="on"><a href="?act=delcelsys&etat=<?php echo $etat ;?>&idanimjob=<?php echo $idanimjob ;?>&facturation<?php echo $facturation ;?>" onClick="return confirm('ATTENTION !!!!! Effacer le job ?')"><img src="<?php echo STATIK ?>illus/trash.gif" alt="show" width="32" height="32" border="0"><br>! CELSYS !</a></td>
			<?php
					}
				}

				if (($idanimation > 0) and ($facturation < 2)) { /* EFFACER MISSION */?>
				<td class="on"><a href="?act=listingmodifdevisdel&idanimation=<?php echo $idanimation ;?>" onClick="return confirm('Etes-vous sur de vouloir effacer cette mission?')"><img src="<?php echo STATIK ?>illus/trash.gif" alt="show" width="32" height="32" border="0"><br>Effacer Mis.</a></td>
			<?php } ?>
			</tr>
		</table>
	</div>
<?php
	include NIVO.'includes/pied.php';
} else {
	include NIVO.'includes/ifpied.php';
}
?>