<?php
define('NIVO', '../');

# Classes utilisées
include NIVO."classes/hh.php" ;
include NIVO.'classes/vip.php';

include NIVO.'conf/vip.php';

# Entete de page
$Titre = 'VIP';
include NIVO."includes/ifentete.php" ;

### pour les valeur d'activités qui viennent de l'espace online
	$vipactivite_1  = "Accueil";
	$vipactivite_2  = "Chauffeur";
	$vipactivite_3  = "Chef Hotesse";
	$vipactivite_4  = "D&eacute;bit";
	$vipactivite_5  = "D&eacute;monstration";
	$vipactivite_6  = "Encadrement";
	$vipactivite_7  = "Encodage";
	$vipactivite_8  = "Flyering";
	$vipactivite_9  = "Information";
	$vipactivite_10 = "Parking";
	$vipactivite_11 = "Reception";
	$vipactivite_12 = "Sampling";
	$vipactivite_13 = "Service";
	$vipactivite_14 = "Shuttle";
	$vipactivite_15 = "Spraying";
	$vipactivite_16 = "Vestiaire";
	$vipactivite_17 = "Event Coordinator";

if (empty($_POST['vipactivite'])) $_POST['vipactivite'] = '';
if (($_POST['vipactivite'] >= 1) and ($_POST['vipactivite'] <= 16)) { $vipact = 'vipactivite_'.$_POST['vipactivite']; $_POST['vipactivite'] = $$vipact; }
#/## pour les valeur d'activités qui viennent de l'espace online

# Carousel des fonctions
switch ($_GET['act']) {
############## Ajout d'une Mission VIPDEVIS/VIPMISSION #############################################
		case "add":
			$idvipjob = $_GET['idvipjob'];
			$idshop = $DB->getOne("SELECT idshop FROM `vipjob` WHERE `idvipjob` = ".$_REQUEST['idvipjob']);

			switch ($_GET['etat']) {
			#### Ajout d'une Mission VIPDEVIS (search VIPJOB + ADD VIPDEVIS)
				case "0":
					$DB->inline("INSERT INTO `vipdevis` (`idvipjob`, `idshop` ) VALUES ('".$_REQUEST['idvipjob']."', '".$idshop."');");
					$idvip = mysql_insert_id();
					include "update-devis.php" ;
				break;
			#### Ajout d'une Mission VIPMISSION (search VIPJOB + ADD VIPMISSION)
				case "1":
					$DB->inline('UPDATE vipjob SET etat = 1 WHERE idvipjob='.$idvipjob.' LIMIT 1');
					############## Ajout d'une Mission VIPMISSION
					$DB->inline("INSERT INTO `vipmission` (`idvipjob`, `idshop` ) VALUES ('".$_REQUEST['idvipjob']."', '".$idshop."');");
					$idvip = mysql_insert_id();
					include "v-listMission.php" ;
				break;
			}
		break;

############## Duplication d'une Mission #############################################
		case "duplic":
			$idvipjob = $_POST['idvipjob'];
			$idvip = $_POST['idvip'];
			switch ($_GET['etat']) {
			#### Duplication VIPDEVIS
				case "0":
					$vipdevis = new db();
					$fields = "`idvipjob` , `idshop` , `vipdate` , `vipactivite` , `sexe` , `vipin` , `vipout` , `brk` , `night` , `ts` , `fts` , `h150` , `h200` , `km` , `fkm` , `unif` , `net` , `loc1` , `loc2` , `cat` , `disp`, `notes`, `fr1` ";
					$vipdevis->inline("INSERT INTO vipdevis ($fields) SELECT $fields FROM vipdevis WHERE `idvip` = $idvip");
					$idvip = mysql_insert_id();
					include "detail-devis_inc.php";
				break;
			#### Duplication VIPMISSION
				case "1":
					$vipdevis = new db();
					$vipdevis->inline('UPDATE vipjob SET etat = 1 WHERE idvipjob='.$_POST['idvipjob'].' LIMIT 1');
					$fields = "`idvipjob` , `idshop` , `vipdate` , `vipactivite` , `sexe` , `vipin` , `vipout` , `brk` , `night` , `ts` , `fts` , `h150` , `h200` , `km` , `fkm` , `unif` , `net` , `loc1` , `loc2` , `cat` , `disp`, `notes`, `ajust`, `vkm`, `vfkm`, `vcat`, `vdisp`, `vfr1`, `vfrpeople`, `notefrpeople`";
					$vipdevis->inline("INSERT INTO vipmission ($fields) SELECT $fields FROM vipmission WHERE `idvip` = $idvip");
					$idvip = mysql_insert_id();
					include "v-listMission.php" ;
				break;
			}
		break;

############## DELETE d'une Mission  #############################################
		case "delete":
			$idvipjob = $_POST['idvipjob'];
			$idvip = $_POST['idvip'];
			switch ($_GET['etat']) {
			#### DELETE VIPDEVIS
				case "0":
						############## Delete d'une Mission VIPDEVIS
						$detailvip3 = new db();
						$detailvip3->inline("DELETE FROM `vipdevis` WHERE `idvip` = $idvip;");
						include "detail-devis_inc.php" ;
				break;
			##### DELETE VIPMISSION (search VIPMISSION + duplic VIPDELETE + delete VIPMISSION)
				case "1":
					switch ($_GET['step']) {
					#### Affichage VIPDEVIS
						case "note":
							include "delete-mission.php" ;
						break;
					#### Affichage VIPMISSION
						case "delete":
							$modif = new db('vipmission', 'idvip');
							$modif->inline("UPDATE vipmission SET notedelete = '".addslashes($_POST['notedelete'])."' WHERE idvip = $idvip");
							$modif->inline("INSERT INTO vipdelete SELECT * FROM vipmission WHERE idvip = $idvip");
							$c = $DB->getOne("SELECT idpeople from vipmission where idvip = $idvip");
							if(!empty($c))
							{
								$DB->inline("INSERT INTO peoplemission (dateout, idpeople, idvip, note, motif, agentmodif, datemodif) VALUES (DATE(NOW()), $c, $idvip, '".addslashes($_POST['notedelete'])."', 4, ".$_SESSION['idagent'].", NOW())");
							}
							$modif->inline("DELETE FROM vipmission WHERE idvip = $idvip"); ## Efface la mission
							$modif->inline("DELETE FROM notefrais WHERE secteur = 'VI' AND idmission = $idvip LIMIT 1"); ## Efface la note de frais
							$modif->journal("EFFACE la mission vip $idvip");

							include "v-listMission.php" ;
						break;
					}
				break;
			}
		break;


############## Job onglet : Affichage du tableau pour factoring #########################################
		case "modfacmis":
			$DB->MAJ('vipmission');

		case "jobfactoring":
			$PhraseBas = 'tableau pour factoring';
			include "onglet-facturation.php" ;
		break;

############## Affichage d'une Mission #########################################
		case "show":
			$PhraseBas = 'Detail d\'une VIP';
			$idvipjob = $_GET['idvipjob'];
			switch ($_GET['etat']) {
			#### Affichage VIPDEVIS
				case "0":
					include "detail-devis_inc.php" ;
				break;
			#### Affichage VIPMISSION
				case "1":
					include "v-listMission.php" ;
				break;
				default:
					include "v-listMission-view.php" ;
			}
		break;

############## Affichage d'une Mission ? #########################################
		case "show1":
			$PhraseBas = 'Detail d\'une VIP';
			$idvipjob = $_GET['idvipjob'];
			switch ($_GET['etat']) {
			#### Affichage VIPDEVIS
				case "0":
					include "detail-devis_inc.php" ;
				break;
			#### Affichage VIPMISSION
				case "1":
					include "v-listMission-view.php" ;
				break;
			}
		break;
############## Affichage d'une Mission ? #########################################
		case "showview":
			$PhraseBas = 'Detail d\'une VIP';
			$idvipjob = $_GET['idvipjob'];
			include "v-listMission-view.php" ;
		break;
############## Modification d'une Mission (Affichage OU Update) #########################################
		case "modif":
			$idvip = $_POST['idvip'];
			$idvipjob = $_POST['idvipjob'];
			switch ($_GET['etat']) {
			#### Modification d'une Mission VIPDEVIS
				case "0":
					switch ($_GET['mod']) {
					#### Modification d'une Mission VIPDEVIS : Affichage
							case "mission":
								$PhraseBas = 'Detail d\'une VIP : Assistant Modifi&eacute;';
								include "update-devis.php" ;
							break;
					#### Modification d'une Mission VIPDEVIS : Update
							case "missionupdate":
								if(!isset($_POST['h200'])) $_POST['h200'] = 0;
								$DB->MAJ("vipdevis");
								$PhraseBas = 'Detail d\'une VIP : Assistant Modifi&eacute;';
								include "update-devis.php" ;
							break;
					#### Modification d'une Mission VIPMISSION : IDSHOP
							case "lieu":
								$idshop=(!empty($_POST['idshop']))?$_POST['idshop']:$_GET['idshop'];
								$idvip=(!empty($_POST['idvip']))?$_POST['idvip']:$_GET['idvip'];
								$idvipjob=(!empty($_POST['idvipjob']))?$_POST['idvipjob']:$_GET['idvipjob'];
								#verif si le codeshop existe
								$codeshop = $DB->getOne('SELECT codeshop FROM shop WHERE idshop="'.$idshop.'"');
								if(!isset($_POST['remove']) && (empty($codeshop) OR $codeshop=='')) {
									#si le codeshop est vide, en crée un
									$DB->inline('UPDATE shop SET codeshop="SH'.$idshop.'" WHERE idshop="'.$idshop.'"');
								}
								$DB->inline("UPDATE vipdevis SET idshop='".$idshop."', km=0, vkm=0 WHERE idvip=".$idvip);
								$PhraseBas = 'Detail d\'une VIP : Shop Modifi&eacute;';
								include "detail-devis_inc.php" ;
							break;
					}
				break;
			##### Modification d'une Mission VIPMISSION
				case "1":
					switch ($_GET['mod']) {
					#### Modification d'une Mission VIPMISSION : Affichage
							case "mission":
								$PhraseBas = 'Detail d\'une VIP : Assistant Modifi&eacute;';
								include "v-listMission.php" ;
								include "update-mission-small.php" ;
							break;
					#### Modification d'une Mission VIPMISSION : Update
							case "missionupdate":
								$_POST['vipdate'] = fdatebk($_POST['vipdate']);
								$_POST['vipin'] = ftimebk($_POST['vipin']);
								$_POST['vipout'] = ftimebk($_POST['vipout']);
								## force vcat si cat rempli
								if (($_POST['cat'] == $catfacture) AND ($_POST['vcat'] == 0)) {
										$_POST['vcat'] = $catpaye;
								}
								$modif = new db('vipmission', 'idvip');
								$modif->MODIFIE($idvip, array('vipdate', 'vipactivite', 'sexe', 'vipin', 'vipout', 'brk', 'night', 'ts', 'fts', 'h150', 'h200', 'km', 'fkm', 'unif', 'net', 'loc1', 'loc2', 'cat', 'disp', 'notes', 'ajust', 'vkm', 'vfkm', 'vcat', 'vdisp', 'vfr1', 'vfrpeople', 'notefrpeople' ));
								$PhraseBas = 'Detail d\'une VIP : Assistant Modifi&eacute;';
								include "v-listMission.php" ;
							break;
					#### Modification d'une Mission VIPMISSION : IDPEOPLE
							case "people":

                                                                $modif = new db();

								$idvipjob = $_POST['idvipjob'];
								$idpeople = $_POST['idpeople'];

                                                                $modif->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idpeople) VALUE(".$_SESSION['idagent'].", 'ADD', 'MISSION', 'PEOPLE', 'VI', ".$idvipjob.", ".$idvip.", ".$idpeople.")");

								$_POST['datecontrat'] = '';
								$modif = new db('vipmission', 'idvip');
								$modif->MODIFIE($idvip, array('idpeople', 'datecontrat' ));

								$_POST['etatcontact'] = '40';

								if ($idpeople > 0) {

								### recherche fiche contact
									$jobcontact1 = new db();
									$jobcontact1->inline("SELECT * FROM `jobcontact` WHERE `idvipjob` = $idvipjob AND `idpeople` = $idpeople;");
									$infocontact1 = mysql_fetch_array($jobcontact1->result) ;

									$FoundCountcontact = mysql_num_rows($jobcontact1->result);

									### si 0 ADD
										if ($FoundCountcontact == 0) {
											$ajout = new db('jobcontact', 'idjobcontact');
											$ajout->AJOUTE(array('idvipjob', 'idpeople', 'etatcontact'));
										}
									### si 1 UPDATE
										if ($FoundCountcontact == 1) {
											$idjobcontact = $infocontact1['idjobcontact'];
											$modif = new db('jobcontact', 'idjobcontact');
											$modif->MODIFIE($idjobcontact, array('etatcontact'));
										}
								#/## recherche fiche contact
								}


                                                                $PhraseBas = 'Detail d\'une VIP : People Modifi&eacute;';
								include "v-listMission.php" ;
							break;

					#### Modification d'une Mission VIPMISSION : IDSHOP
							case "lieu":
								$idshop=(isset($_POST['idshop']))?$_POST['idshop']:$_GET['idshop'];
								$idvip=(isset($_POST['idvip']))?$_POST['idvip']:$_GET['idvip'];
								$idvipjob=(isset($_POST['idvipjob']))?$_POST['idvipjob']:$_GET['idvipjob'];
								#verif si le codeshop existe

								// Récupération des anciennes valeurs
								$result = $DB->getRow('SELECT idshop FROM vipmission WHERE idvip = '.$_POST['idvip']);
								$oldIdshop = $result['idshop'];

								// Insertion dans le journal
								if ($oldIdshop==0) { // Ajout du lieu
									$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idshop) VALUE(".$_SESSION['idagent'].", 'ADD', 'MISSION', 'SHOP', 'VI', ".$idvipjob.", ".$idvip.", ".$idshop.")");
								} elseif ($_POST['idshop']=='') { // Suppression du lieu
									$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idshop) VALUE(".$_SESSION['idagent'].", 'DEL', 'MISSION', 'SHOP', 'VI', ".$idvipjob.", ".$idvip.", ".$oldIdshop.")");
								} else { // Modification du lieu
									$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idshop) VALUE(".$_SESSION['idagent'].", 'MOD-DEL', 'MISSION', 'SHOP', 'VI', ".$idvipjob.", ".$idvip.", ".$oldIdshop.")");
									$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idshop) VALUE(".$_SESSION['idagent'].", 'MOD-ADD', 'MISSION', 'SHOP', 'VI', ".$idvipjob.", ".$idvip.", ".$idshop.")");
								}

								$codeshop = $DB->getOne('SELECT codeshop FROM shop WHERE idshop="'.$idshop.'"');
								if(!isset($_POST['remove']) && (empty($codeshop) OR $codeshop=='')) {
									#si le codeshop est vide, en crée un
									$DB->inline('UPDATE shop SET codeshop="SH'.$idshop.'" WHERE idshop="'.$idshop.'"');
								}
								$DB->inline('UPDATE vipmission SET idshop="'.$idshop.'", km=0, vkm=0 WHERE idvip="'.$idvip.'"');
								$PhraseBas = 'Detail d\'une VIP : Shop Modifi&eacute;';
								include "v-listMission.php" ;
							break;
					}
				break;
			}
		break;
############## Selection des éléments #############################################
		case "removeselect":
			if (!empty($_REQUEST['idvip'])) {
				## Stocke le debooking
				$DB->inline("INSERT INTO peoplemission (`dateout`, `idvip`, `idpeople`, `note`, `motif`) VALUES ('".date ("Y-m-d")."', '".$_REQUEST['idvip']."', '".$_REQUEST['idpeople']."', '".addslashes($_POST['note'])."', '".$_POST['motif']."')");
				## Reset la fiche et les données propres au people
				$DB->inline("UPDATE vipmission SET idpeople='', datecontrat='', matospeople='' WHERE idvip = ".$_REQUEST['idvip']);
				$notify[] = "People ".$_REQUEST['idpeople']." Supprimé de la mission ".$_REQUEST['idvip'];
			}

############## Selection des éléments #############################################
		case "select":
			$_SESSION['selectfrom'] = 'job';
			$PhraseBas = 'Selection des &eacute;l&eacute;ments';

			## verification que la fiche contact existe, sinon creation
			if ($_GET['contact'] == 'yes') {
				$jcs = $DB->getOne("SELECT COUNT(idjobcontact) FROM `jobcontact` WHERE `idvipjob` = ".$_REQUEST['idvipjob']." AND `idpeople` = ".$_REQUEST['idpeople']);
				if ($jcs == 0) $DB->inline("INSERT INTO jobcontact (idvipjob, idpeople) VALUES (".$_REQUEST['idvipjob'].", ".$_REQUEST['idpeople'].")");
			## Modification de la fiche contact
			} elseif ($_GET['contact'] == 'modif') {
				$jcinfos = $DB->getRow("SELECT * FROM `jobcontact` WHERE `idvipjob` = ".$_REQUEST['idvipjob']." AND `idpeople` = ".$_REQUEST['idpeople']);
				if ($jcinfos['idjobcontact'] > 0) $DB->inline("UPDATE jobcontact SET etatcontact = '".$_POST['etatcontact']."', notecontact = '".$_POST['notecontact']."' WHERE idjobcontact = ".$jcinfos['idjobcontact']);
			}

			$etat     = $_REQUEST['etat'];
			$idvip    = $_REQUEST['idvip'];
			$idvipjob = $_REQUEST['idvipjob'];
			$idpeople = $_REQUEST['idpeople'];

			if($_GET['from']=='mission') echo '<div id="centerzonelarge">';
			if($_GET['etape']=="listepeople") include 'select/select-listepeople.php';
			else include 'select/select-'.$_GET['sel'].'.php' ;
			if($_GET['from']=='mission') echo '</div>';
		break;
############## Selection des éléments #############################################
		case "remove":
			$PhraseBas         = 'Suppresion du people';
			$idvipjob          = $_GET['idvipjob'];
			$_POST['idvipjob'] = $_GET['idvipjob'];
			$idvip             = $_GET['idvip'];
			$etat              = $_GET['etat'];

			include "select/select-remove.php" ;
		break;

############## Web list #############################################
		case "webviplist":
			$PhraseBas = 'Liste des nouvelles demande On-line';
			include "weblist.php" ;
		break;

############### Update MISSIONS : MOULINETTE #########################################
	############### Affichage du listing des Missions pour Update #########################################
		case "moulinetteupdate1":
			$PhraseBas = 'Affichage du listing des Missions pour Update';
			include "moulinette-update1.php" ;
		break;
	############### Grille de modifications (APRES Affichage du listing des Missions) pour Update #########################################
		case "moulinetteupdate2":
			$PhraseBas = 'Grille de modifications pour Update';
			include "moulinette-update2.php" ;
		break;
	############### Grille de modifications (APRES Affichage du listing des Missions) pour Update #########################################
		case "moulinetteupdate3":
			$PhraseBas = 'Affichage du listing des Missions pour Update';
				$_POST['vipdate'] = fdatebk($_POST['vipdate']);

				$_POST['vipin']   = ftimebk($_POST['vipin']);
				$_POST['vipout']  = ftimebk($_POST['vipout']);

				$_POST['h150']    = fnbrbk($_POST['h150']);
				$_POST['brk']     = fnbrbk($_POST['brk']);
				$_POST['night']   = fnbrbk($_POST['night']);
				$_POST['ts']      = fnbrbk($_POST['ts']);
				$_POST['fts']     = fnbrbk($_POST['fts']);
				$_POST['fkm']     = fnbrbk($_POST['fkm']);
				$_POST['unif']    = fnbrbk($_POST['unif']);
				$_POST['loc1']    = fnbrbk($_POST['loc1']);
				$_POST['cat']     = fnbrbk($_POST['cat']);
				$_POST['disp']    = fnbrbk($_POST['disp']);

			foreach($_POST['fieldtoupdate'] as $fieldtoupdate) {
				$i++;
				if ($i != 1) { $fieldtoupdatex .= " , ";}
				if ($fieldtoupdate == 'vipactivite1') {
					$fieldtoupdatex .= "vipactivite='".$_POST['vipactivite1']."'";
				} else {
					$fieldtoupdatex .= $fieldtoupdate."='".$_POST[$fieldtoupdate]."'";
				}
			}
			$idtoupdate = $_POST['idtoupdate'];
			$f = explode("%", $idtoupdate);
			foreach ($f as $val) {
				if (is_numeric($val)) {
					if($_GET['etat'] == '0') {
						$sql = "UPDATE `vipdevis` SET ".$fieldtoupdatex." WHERE `idvip` = ".$val;
					} else {
						$sql = "UPDATE `vipmission` SET ".$fieldtoupdatex." WHERE `idvip` = ".$val;
					}
					$modif = new db();
					$modif->inline($sql);
				}
			}
			include "moulinette-update1.php" ;
		break;
#/ ############## Update MISSIONS : MOULINETTE #########################################



############## Recherche d'une mission #########################################
		case "search":
		default:
			$PhraseBas = 'Recherche de VIP';

			$idvipjob = $_GET['idvipjob'];

			include "search.php" ;
}

# Pied de Page
include NIVO."includes/ifpied.php" ;
?>