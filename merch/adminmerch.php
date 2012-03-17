<?php
# Entete de page
define('NIVO', '../');
$Titre = 'MERCH';
$Style = 'merch';

include NIVO."includes/entete.php";
include_once NIVO.'conf/merch.php';

# Classes utilisées
include NIVO."classes/merch.php";
include NIVO."classes/hh.php";
include NIVO."classes/notefrais.php" ;
include NIVO."classes/makehtml.php";

## Clients EAS
include NIVO."merch/easclients.php";

# Init Vars
if (!isset($_GET['listing'])) $_GET['listing'] = '';
$_SESSION['idvip']=null;
$_SESSION['idvipjob']=null;
$_SESSION['idanimation']=null;
$_SESSION['idanimjob']=null;

# Carousel des fonctions
$onemonthago  = date ("Y-m-01", strtotime("+1 day")); 

$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Dupliquer", "Effacer Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");

$idmerch = $_REQUEST['idmerch'];

switch ($_GET['act']) {
############## Listing des PRE-FACTURATION #############################################
		case "faceas": 
			if (($_POST['modstate'] == 'Valider') and ($_POST['state'] > 0)) {
				$DB->inline("UPDATE merch SET facturation = '".$_POST['state']."' WHERE idmerch IN (".implode(", ", $_POST['ids']).");");
			}
			$mode = 'USER';
	
			include NIVO."admin/merch/faceas.php" ;
			$PhraseBas = 'Facturation EAS';
		break;

############## Ajout d'une Mission #############################################
		case "add": 
            $_SESSION['archive'] = 'normal' ; 
            ## Nouvelle fiche merch
            $ajout = new db();
            $ajout->inline("INSERT INTO merch (`idagent`, `facturation`, `recurrence`, `genre`, `agentmodif`, `datemodif`)
                           VALUES ('".$_SESSION['idagent']."', '1', '1', 'Store Check', '".$_SESSION['idagent']."', NOW())");
            $idmerch = $ajout->addid;

            $PhraseBas = 'Nouvelle merch';
            $menuhaut = array ("Effacer Job");
            
            include "detail_inc.php" ;
		break;
	############## Remoove + Selection des people #############################################
		case "removepeople": 
			$_SESSION['archive'] = 'normal' ; 
			$PhraseBas = 'Remove pour Selection des people';
			$etat = $_GET['etat'];
			include "mission-select-p-remove.php" ;
		break;

############## Selection des eéleéments #############################################
	############## Remove des People #############################################
		case "removeselect": 
            $PhraseBas = 'People supprimé / ';
            $etat = $_GET['etat'];

            if ($idmerch > 0) {
                $modif = new db();
		
		// Insertion dans le journal
		$modif->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idmission, idpeople) VALUE(".$_SESSION['idagent'].", 'DEL', 'MISSION', 'PEOPLE', 'ME', ".$idmerch.", ".$_POST['idpeople'].")");                        
		
                $modif->inline("INSERT INTO peoplemission (`dateout`, `idmerch`, `idpeople`, `note`, `motif`, agentmodif, datemodif) VALUES
                        (NOW(), $idmerch, ".$_POST['idpeople'].", '".addslashes($_POST['note'])."', ".$_POST['motif'].", ".$_SESSION['idagent'].", NOW())");
                $modif->inline("UPDATE merch SET idpeople = '', datecontrat = '', agentmodif = '".$_SESSION['idagent']."', datemodif = NOW() WHERE idmerch = $idmerch LIMIT 1");
            } else {
                echo '## error : perdu idmerch dans removeselct';    
            }

		case "select": 
			$_SESSION['archive'] = 'normal' ; 
			$PhraseBas = 'Selection des &eacute;l&eacute;ments';

			$etat = $_GET['etat'];
                        
                        //on a clické sur le téléphone : création d'un élément dans la table jobcontact, si elle n'existe pas encore
			if ($_GET['contact'] == 'yes') {				
			### recherche fiche contact et insertion si elle n'existe pas
				$infocontact1 = $DB->getOne("SELECT COUNT(*) FROM `jobcontact` WHERE `idmerch` = '$idmerch' AND `idpeople` = ".$_REQUEST['idpeople'].";");
				### si 0 ADD
                                if ($infocontact1 == 0) {
                                        $DB->inline("INSERT INTO jobcontact SET idmerch= ".$_REQUEST['idmerch'].",idpeople=".$_REQUEST['idpeople'].", agentmodif=".$_SESSION['idagent'].", datemodif=NOW();");
                                }
			#/## recherche fiche contact et insertion si elle n'existe pas
			} 
                        
                        // On a clické sur OK pour modifier
			if ($_GET['contact'] == 'modif') {
				$DB->inline("UPDATE jobcontact SET etatcontact=".$_REQUEST['etatcontact'].", datemodif = NOW(), notecontact='".$_REQUEST['notecontact']."' WHERE idmerch='".$_REQUEST['idmerch']."' AND idpeople='".$_REQUEST['idpeople']."';");
			} 
			include 'select/select-'.((isset($_REQUEST['etape']))?$_REQUEST['etape']:$_REQUEST['sel']).'.php' ;
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");

		break;
############## Affichage d'une Mission #########################################
		case "show": 
			$PhraseBas = 'Detail d\'une merch';
			$idmerch = $_REQUEST['idmerch'];
			if (($_GET['facturation'] > 2) or ($_REQUEST['table'] =='zoutmerch')) {
				#### archive
				include "detail-view.php" ;
				$menuhaut = array ("Ajouter", "Rechercher", "Mes Jobs", "Retour Job", "Dupliquer", "Effacer Job", "Planning", "valideas", "Retour PL", "Rech Fac", "Liste Facture", "Historicsearch");
			} else {
			#	#### Normal
				include "detail_inc.php" ;
				$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Dupliquer", "Effacer Job", "Planning", "valideas", "Retour PL", "Rech Fac", "Liste Facture", "Historicsearch");
			}	
		break;

######
######
######
######
#TEMPORAIRE !!!
############## Affichage d'une Mission comme si archive #########################################
		case "showview": 
			$PhraseBas = 'Detail d\'une merch';
			$idmerch = $_GET['idmerch'];
			include "detail-view.php" ;
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
		break;
#/############# Affichage d'une Mission comme si archive #########################################
#TEMPORAIRE !!!
######
######
######
######


############## Modification et affichage d'une Mission #########################################
		case "modif": 
			$_SESSION['archive'] = 'normal' ; 
			$idmerch = (!empty($_POST['idmerch']))?$_POST['idmerch']:$_GET['idmerch'];
			$toinclude = 'detail_inc.php';
			switch ($_GET['mod']) {
			#### Mise à jour des infos Client
					case "client":
                                                
                                               
                                                
                                                // Récupération des anciennes valeurs
						$result = $DB->getRow('SELECT idclient, idcofficer FROM merch WHERE idmerch = '.$idmerch);
                                                $oldClient = $result['idclient'];
						$oldOfficer = $result['idcofficer'];
						
						// Insertion dans le journal
						if ($oldClient==0) { // Ajout du client
							$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idmission, idclient, idcofficer) VALUE(".$_SESSION['idagent'].", 'ADD', 'JOB', 'CLIENT', 'ME', ".$idmerch.", ".$_POST['idclient'].", ".$_POST['idcofficer'].")");
						} elseif ($_POST['idclient']=='') { // Suppression du client
							$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idmission, idclient, idcofficer) VALUE(".$_SESSION['idagent'].", 'DEL', 'JOB', 'CLIENT', 'ME', ".$idmerch.", ".$oldClient.", ".$oldOfficer.")");							
						} else { // Modification du client
							$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idmission, idclient, idcofficer) VALUE(".$_SESSION['idagent'].", 'MOD-DEL', 'JOB', 'CLIENT', 'ME', ".$idmerch.", ".$oldClient.", ".$oldOfficer.")");
							$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idmission, idclient, idcofficer) VALUE(".$_SESSION['idagent'].", 'MOD-ADD', 'JOB', 'CLIENT', 'ME', ".$idmerch.", ".$_POST['idclient'].", ".$_POST['idcofficer'].")");
						}
                                                
						$modif = new db('merch', 'idmerch');
						$modif->MODIFIE($idmerch, array('idclient' , 'idcofficer'));
						$_POST['merch'] = 1; ### pour flager le client comme un client merch
						$idclient = $_POST['idclient'];
						$_REQUEST['idmerch'] = $idmerch;
						//$modifclient = new db('client', 'idclient');
						//$modifclient->MODIFIE($idclient, array('merch'));

						$PhraseBas = 'Detail d\'une merch : Client Modifi&eacute;';
					break;
			#### Mise à jour des infos Lieu
					case "lieu":
                                                
                                                
                                                
                                                // Récupération des anciennes valeurs
						$result = $DB->getRow('SELECT idshop FROM merch WHERE idmerch = '.$_POST['idmerch']);
						$oldIdshop = $result['idshop'];
						
                                                // Insertion dans le journal
						if ($oldIdshop==0) { // Ajout du lieu
							$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idmission, idshop) VALUE(".$_SESSION['idagent'].", 'ADD', 'MISSION', 'SHOP', 'ME', ".$_POST['idmerch'].", ".$_POST['idshop'].")");
						} elseif ($_POST['idshop']=='') { // Suppression du lieu
							$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idmission, idshop) VALUE(".$_SESSION['idagent'].", 'DEL', 'MISSION', 'SHOP', 'ME', ".$_POST['idmerch'].", ".$oldIdshop.")");							
						} else { // Modification du lieu
							$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idmission, idshop) VALUE(".$_SESSION['idagent'].", 'MOD-DEL', 'MISSION', 'SHOP', 'ME', ".$_POST['idmerch'].", ".$oldIdshop.")");
							$DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idmission, idshop) VALUE(".$_SESSION['idagent'].", 'MOD-ADD', 'MISSION', 'SHOP', 'ME', ".$_POST['idmerch'].", ".$_POST['idshop'].")");
						}
                                                
						$modif = new db('merch', 'idmerch');
						$modif->MODIFIE($idmerch, array('idshop'));
						//$DB->inline('UPDATE shop SET codeshop="'.$_POST['codeshop'].'" WHERE idshop='.$_POST['idshop'].' LIMIT 1');
						$PhraseBas = 'Detail d\'une merch : Lieu Modifi&eacute;';
					break;
			#### Mise à jour des infos People
					case "people": 
						$modif = new db('merch', 'idmerch');
						$modif->MODIFIE($idmerch, array('idpeople'));
						$idmerch = (!empty($_POST['idmerch']))?$_POST['idmerch']:$_GET['idmerch'];
						$idpeople = $_POST['idpeople'];
						
						$modif->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idmission, idpeople) VALUE(".$_SESSION['idagent'].", 'ADD', 'MISSION', 'PEOPLE', 'ME', ".$idmerch.", ".$idpeople.")");
						
						$_POST['etatcontact'] = '40';
						if (!empty($idpeople)) { # tous les cas sauf Remove People from Mission
							### recherche fiche contact
							$jobcontact1 = new db();
							$jobcontact1->inline("SELECT * FROM `jobcontact` WHERE `idmerch` = $idmerch AND `idpeople` = $idpeople;");
							$infocontact1 = mysql_fetch_array($jobcontact1->result) ; 

							$FoundCountcontact = mysql_num_rows($jobcontact1->result);
						
							### si 0 ADD
								if ($FoundCountcontact == 0) {
									$ajout = new db('jobcontact', 'idjobcontact');
									$ajout->AJOUTE(array('idmerch', 'idpeople', 'etatcontact'));
								}
							### si 1 UPDATE
								if ($FoundCountcontact == 1) {
									$idjobcontact = $infocontact1['idjobcontact'];
									$modif = new db('jobcontact', 'idjobcontact');
									$modif->MODIFIE($idjobcontact, array('etatcontact'));
								}
							#/## recherche fiche contact
						} #/ tous les cas sauf Remove People from Mission

						$PhraseBas = 'Detail d\'une merch : People Modifi&eacute;';
					break;
			#### Mise ? jour de la fiche compl?te
					default:
						$_POST['weekm'] = weekfromdate($_POST['datem']);
						$_POST['yearm'] = date('Y', strtotime(fdatebk($_POST['datem'])));

						/*
							TODO : modifier MAJ pour permettre la gestion des cases a cocher non cochées. (si pas coché, aucune valeur retournée)
						*/
						
						## cases a cocher : si pas cochées, ne renvoient rien...
						$_POST['contratencode'] = $_POST['contratencode'];
						$_POST['rapportencode'] = $_POST['rapportencode'];

						$hcode = new hh ();
						$_POST['hhcode'] = $hcode->makehhcode(ftimebk($_POST['hin1']), ftimebk($_POST['hout1']), ftimebk($_POST['hin2']), ftimebk($_POST['hout2']));
						
						## KM 
						if ($_POST['kmfacture'] == '0') $_POST['kmfacture'] = $_POST['kmpaye'];

						## Notes de Frais 
						if (($_POST['montantpaye'] > 0) and ($_POST['montantfacture'] == 0)) $_POST['montantfacture'] = $_POST['montantpaye'];

						$DB->MAJ('merch');

						## Mise a jout de la note de frais
						updnotefrais($_POST['idmerch'], 'ME', $_POST['montantpaye'], $_POST['montantfacture'], $_POST['nfraisyn'], trim($_POST['intitule']), trim($_POST['description']));

						$PhraseBas = 'Detail d\'une merch : Fiche Mise &agrave; Jour';
			}

			include "$toinclude" ;
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Dupliquer", "Effacer Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
		break;

############## Duplication d'une Mission (search + duplic) #############################################
		case "duplic":
			$merch1 = new db();
			$fields = "`idagent`, `idclient`, `easremplac`, `idcofficer`, `idshop`, `idpeople`, `produit`, `datem`, `weekm`, `hin1`, `hout1`, `hin2`, `hout2`, `recurrence`, `genre`, `kmpaye`,
			`kmfacture`, `note`";

			$merch1->inline("INSERT INTO merch (".$fields.", `ferie`, `facturation`, `yearm`) SELECT ".$fields.", '100', '1', YEAR(datem) FROM `merch` WHERE `idmerch` = ".$_REQUEST['idmerch']." LIMIT 1;");
			$idmerch = $merch1->addid; # Pour accéder directement a la fiche dupliquée

			$PhraseBas = 'Detail d\'une merch : Fiche dupication';
			
			include "detail_inc.php" ;
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Dupliquer", "Effacer Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
		break;
############## DELETE d'une Mission (search + duplic + delete) #############################################
		case "raisonout":
			include 'v-raisonout.php';
		break;
		case "delete": 
			## Deplace la mission dans zoutmerch
			$fields = "`idmerch`, `idagent`, `idclient`, `idcofficer`, `idshop`, `idpeople`, `produit`, `boncommande`, `datem`, `recurrence`, `easremplac`, `weekm`,
			`hin1`, `hout1`, `hin2`, `hout2`, `ferie`, `kmpaye`, `kmfacture`, `livraison`, `diversfrais`, `note`, `genre`, `datecontrat`, `contratencode`";
			$DB->inline("INSERT INTO zoutmerch (".$fields.", `agentmodif`, `datemodif`) SELECT ".$fields.", '".$_SESSION['idagent']."', NOW() FROM `merch` WHERE `idmerch` = ".$_REQUEST['idmerch'].";");
			$DB->MAJ('zoutmerch'); #stocke la raison out
			
			## Deplace la notes de frais
			$fields = "`idnfrais`, `secteur`, `datemission`, `idjob`, `idmission`, `intitule`, `description`, `montantpaye`, `montantfacture`, `dateachat`, `facnum`";
			$DB->inline("INSERT INTO zoutnotefrais (".$fields.", `delnote`) SELECT ".$fields.", 'Efface par ".$_SESSION['prenom']." ".$_SESSION['nom']." le ".date("d/m/Y")."' FROM notefrais WHERE secteur = 'ME' AND idmission = ".$_REQUEST['idmerch']." LIMIT 1");

			## Debooking People
			$c = $DB->getOne("SELECT idpeople from merch where idmerch = ".$_REQUEST['idmerch']);
			if(!empty($c)) $DB->inline("INSERT INTO peoplemission (dateout, idpeople, idmerch, note, motif, agentmodif, datemodif) VALUES (DATE(NOW()), $c, ".$_REQUEST['idmerch'].", 'Delete mission avec people $c', 4, ".$_SESSION['idagent'].", NOW())");
			## Efface les fiches

			$DB->inline("DELETE FROM `merch` WHERE `idmerch` = ".$_REQUEST['idmerch']." LIMIT 1;");
			$DB->inline("DELETE FROM `notefrais` WHERE `secteur` = 'ME' AND `idmission` = ".$_REQUEST['idmerch']." LIMIT 1");
			$DB->inline("DELETE FROM `mercheasproduit` WHERE `idmerch` = ".$_REQUEST['idmerch']);

			include "listing.php" ;
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
		break;

############## Listing des Missions Search #############################################
	case "listingsearch": 
			$_SESSION['archive'] = 'normal' ; 
			$_SESSION['view'] = 'n' ; 
			include "listingsearch.php" ;
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");

			$PhraseBas = 'Listing des Merch';
		break;

############## Listing des Job Results #############################################
		case "listing": 
			if (($_GET['listing'] == 'direct') OR ($_GET['listing'] == 'missing')) {
				$_SESSION['view'] = 'n';
				$_SESSION['archive'] = 'normal';
			}	
			switch ($_SESSION['archive']) {

				#### Normal
				case "normal":
					if ($_SESSION['view'] == 'f') { 
						include "factoring".$_SESSION['listtype'].".php" ;
						$PhraseBas = 'FACTORING des Merch';
					} else {
						include "listing.php" ;

						$PhraseBas = 'Planning des Merch';
					}
				break;

				#### Normal
				case "archive":
					include "historic.php" ;
				break;
			}
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
		break;

####
############## planning des Missions Search #############################################
	case "planningsearch": 
			$_SESSION['archive'] = 'normal' ; 
			$_SESSION['view'] = 'n' ; 
			include "planningsearch.php" ;
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");

			$PhraseBas = 'planning des Merch';
		break;

############## planning des Job Results #############################################
		case "planning": 
			if (($_GET['listing'] == 'direct') OR ($_GET['listing'] == 'missing')) {
				$_SESSION['view'] = 'n';

				$_SESSION['archive'] = 'normal';
				$_GET['listtype'] = '0';
			}	
			switch ($_SESSION['archive']) {
				#### Normal
				case "normal":
					if ($_SESSION['view'] == 'f') { 

						include "factoring".$_SESSION['listtype'].".php" ;
						$PhraseBas = 'FACTORING des Merch';
					} else {
						include "planning.php" ;
						$PhraseBas = 'Planning des Merch';
					}
				break;
				#### Normal
				case "archive":
					include "historic.php" ;
				break;
			}
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
		break;
############## planning des Missions Search #############################################
	case "planningweek": 
			$_SESSION['archive'] = 'normal' ; 
			$_SESSION['view'] = 'n' ; 
			include "planningweek.php" ;

			$PhraseBas = 'planning des Merch par semaine';
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
		break;
####
############## planning des Missions tableau planning week eas #############################################
	case "planningweekeas": 
			$_SESSION['archive'] = 'normal' ; 
			$_SESSION['view'] = 'n' ; 
			include "planningweekeas.php" ;
			$PhraseBas = 'planning des Merch EAS par semaine';
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
		break;
####
############## planning des Missions edition d'un jour planning week eas #############################################
	case "planningweekeas2": 
			$_SESSION['archive'] = 'normal' ; 
			$_SESSION['view'] = 'n' ; 
			include "planningweekeas2.php" ;
			$PhraseBas = 'planning des Merch EAS par semaine';
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
		break;
####
############## planning des Missions tableau planning week eas fiche mise a jour #############################################
	case "planningweekeas3": 
			#### Mise à jour d'une fiche prouit EAS
				$idmerch = $_GET['idmerch'];
				$_POST['idmerch'] = $idmerch;
				$ideasprod = $_POST['ideasprod'];

				$modif = new db('mercheasproduit', 'ideasprod');
				$modif->MODIFIE($ideasprod, array(
					'au1n', 'au2n', 'au3n', 'au4n', 'au5n',
					'fo1a', 'fo1b', 'fo1c',
					'fo2a', 'fo2b', 'fo2c',
					'fo3a', 'fo3b', 'fo3c',
					'fo4a', 'fo4b', 'fo4c',
					'pa1a', 'pa1b', 'pa1c',
					'pa2a', 'pa2b', 'pa2c',
					'pa3a', 'pa3b', 'pa3c',
					'pa4a', 'pa4b', 'pa4c',
					'pa5a', 'pa5b', 'pa5c',
					'pa6a', 'pa6b', 'pa6c',
					'pa7a', 'pa7b', 'pa7c',
					'pa8a', 'pa8b', 'pa8c',
					'pa9a', 'pa9b', 'pa9c',
					'pa10a', 'pa10b', 'pa10c',
					'te1a', 'te1b', 'te1c',
					'te2a', 'te2b', 'te2c',
					'te3a', 'te3b', 'te3c',
					'te4a', 'te4b', 'te4c',
					'te5a', 'te5b', 'te5c',
					'te6a', 'te6b', 'te6c',
					'te7a', 'te7b', 'te7c',
					'te8a', 'te8b', 'te8c',
					'ep1a', 'ep1b', 'ep1c',
					'ep2a', 'ep2b', 'ep2c',
					'ep3a', 'ep3b', 'ep3c',
					'ep4a', 'ep4b', 'ep4c',
					'ep5a', 'ep5b', 'ep5c',
					'ba1a', 'ba1b', 'ba1c',
					'ba2a', 'ba2b', 'ba2c',
					'ba3a', 'ba3b', 'ba3c',
					'ba4a', 'ba4b', 'ba4c',
					'ba5a', 'ba5b', 'ba5c',
					'ba6a', 'ba6b', 'ba6c',
					'au1a', 'au1b', 'au1c',
					'au2a', 'au2b', 'au2c',
					'au3a', 'au3b', 'au3c',
					'au4a', 'au4b', 'au4c',
					'au5a', 'au5b', 'au5c',
					'caisse', 'remarque', 'badcaisses'
				));
			#/### Mise à jour d'une fiche prouit EAS
#					$toinclude = "planningweekeas2plus";
					if ($_POST['Modifier'] == 'Modifier') { $toinclude = "planningweekeas"; }
					else { $toinclude = "planningweekeas2plus"; }

			#### Mise à jour d'une fiche Merche
				if ($_POST['fiche'] == 'oui') {

						$_POST['hin1'] = ftimebk($_POST['hin1']);
						$_POST['hout1'] = ftimebk($_POST['hout1']);
						$_POST['hin2'] = ftimebk($_POST['hin2']);
						$_POST['hout2'] = ftimebk($_POST['hout2']);
						
						$hcode = new hh ();
						$_POST['hhcode'] = $hcode->makehhcode($_POST['hin1'], $_POST['hout1'], $_POST['hin2'], $_POST['hout2']);
						
						$_POST['kmpaye'] = fnbrbk($_POST['kmpaye']);
						$_POST['kmfacture'] = fnbrbk($_POST['kmpaye']); # quelque soit (demande de Nath 04-05-21)

						$modif = new db('merch', 'idmerch');
						$modif->MODIFIE($idmerch, array(

						'hin1' , 'hout1' , 'hin2' , 'hout2' ,
						'kmpaye' , 'kmfacture' , 'hhcode', 'contratencode', 'rapportencode'));
#					$toinclude = "planningweekeas2";
					if ($_POST['Modifier'] == 'Modifier') { $toinclude = "planningweekeas"; }
					else { $toinclude = "planningweekeas2"; }
				}
			#/### Mise à jour d'une fiche Merche

			$_SESSION['archive'] = 'normal' ; 
			$_SESSION['view'] = 'n' ; 

			include $toinclude.".php" ;
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
			$PhraseBas = 'planning des Merch EAS par semaine';
		break;
####
############## planning des Missions edition d'un jour planning week eas #############################################
	case "planningweekeas2plus": 
			$_SESSION['archive'] = 'normal' ; 
			$_SESSION['view'] = 'n' ; 
			include "planningweekeas2plus.php" ;
			$PhraseBas = 'planning des Merch EAS par semaine - plus et moins';
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
		break;
####

### HISTORIQUE
			############## HISTORIQUE des Missions Search #############################################
		case "historicsearch": 
			$_SESSION['archive'] = 'archive' ; 
			$_SESSION['view'] = 'n' ; 
			include "historicsearch.php" ;
			$PhraseBas = 'historic des Merch';
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
		break;

		############## HISTORIQUE des Job Results #############################################
		case "historic": 
			$_SESSION['archive'] = 'archive' ; 
			if (($_GET['listing'] == 'direct') OR ($_GET['listing'] == 'missing')) {
				$_SESSION['view'] = 'n';
			}	
				include "historic.php" ;
				$PhraseBas = 'historic des Merch';
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
		break;


############## UPDATE dans Listing des Job Results info éditable et décompte #############################################
		case "listingmodif":
			## Notes de Frais
			if (($_POST['montantpaye'] > 0) and ($_POST['montantfacture'] == 0)) $_POST['montantfacture'] = $_POST['montantpaye'];
			updnotefrais($_POST['idmerch'], 'ME', $_POST['montantpaye'], $_POST['montantfacture'], $_POST['nfraisyn']);
			
		case "listingmodifeas": 
			$_POST['contratencode'] = $_POST['contratencode'];
			if ($_REQUEST['act'] == 'listingmodifeas') $_POST['rapportencode'] = $_POST['rapportencode'];
			
			$_SESSION['archive'] = 'normal' ; 

			## weekm and yearm
			$_POST['weekm'] = weekfromdate($_POST['datem']);
			$_POST['yearm'] = date('Y', strtotime(fdatebk($_POST['datem'])));

			if ($_POST['kmfacture'] == '0') $_POST['kmfacture'] = $_POST['kmpaye'];
			
			$DB->MAJ('merch');

			include "listing.php" ;
			$PhraseBas = 'Planning des Merch : Fiche Mise &agrave; Jour';
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
		break;

###################################################################################################################################################################################
# 1 ########## Facturation #############################################																										###
#####   																																										###
		case "facture": 
			## Validation d'une facture
			if (($_POST['modstate'] == 'Valider') and ($_POST['state'] > 0)) $DB->inline("UPDATE merch SET facturation = '".$_POST['state']."' WHERE idmerch IN (".implode(", ", $_POST['ids']).");");

			## Affichage de la liste des missions a facturer
			$mode = 'USER';
			include NIVO."admin/merch/factoring.php" ;

			$PhraseBas = 'FACTORING des Merch';
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
		break;
#####   																																										###
############## Facturation #############################################																										###
###################################################################################################################################################################################

	### Affichage du search engine de validation des heures EAS ############ OK #####
		case "valideas": 
			$PhraseBas = 'Recherche EAS valideas';
			include "v-valideasSearch.php" ;
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
		break;

############## Affichage du search engine valideas EAS #########################################
		case "valideasresult": 
			if (validSqlDate(fdatebk($_POST['date1'])) and validSqlDate(fdatebk($_POST['date2']))) {
				include "v-valideasShow.php" ;
			} else {
				$warning[] = "Dates non valides : du ".$_POST['date1']." au ".$_POST['date2'];
				include "v-valideasSearch.php" ;
			}
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
		break;
#/############# Affichage du search engine valideas EAS #########################################

############## Recherche d'une mission #########################################
		case "search": 
		default: 
			$_SESSION['archive'] = 'normal' ; 
			$PhraseBas = 'Recherche d\'merchs';
			$idmerch = '';
			include "search.php" ;
			$menuhaut = array ("Ajouter", "Rechercher", "Retour Liste", "Mes Jobs", "Retour Job", "Planning", "Retour PL", "valideas", "Rech Fac", "Liste Facture", "Historicsearch");
}
if(isset($_SESSION['sqlwhere']) && $_GET['act'] <> 'historic') $menuhaut[]="Historic";
?>

<!-- Barre des Boutons -->
<div id="topboutons">
<?php 
# debut définition des switches
	if (($_GET['act'] != 'planningsearch') 
	AND ($_GET['act'] != 'planning') 
	AND ($_GET['act'] != 'listingsearch') 
	AND ($_GET['act'] != 'listing') 
	AND ($_GET['act'] != 'planningweek') 
	
	AND ($_GET['act'] != 'historicsearch') 

	AND ($_GET['act'] != 'historic') 
	AND ($_GET['act'] != 'search')) 
	{ $viewj = '1'; } else { $viewj = '0'; }

	if (($_SESSION['roger'] == 'devel') or ($_SESSION['roger'] == 'admin'))
	{ $viewperm = '1'; }
# Fin definition des switches
?>

<table border="0" cellspacing="1" cellpadding="0">
	<tr>
		<?php if (in_array("Ajouter", $menuhaut)) { ?>
			<td class="on"><a href="?act=add"><img src="<?php echo STATIK ?>illus/ajouter.gif" alt="ajouter" width="32" height="32" border="0"><br>Ajouter</a></td>
		<?php } ?>
		
		<?php if (in_array("Rechercher", $menuhaut)) { ?>
			<td class="on"><a href="?act=listingsearch"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
		<?php } ?>
		
		<?php if (in_array("Retour Liste", $menuhaut)) { ?>
			<?php if (!empty($_SESSION['mquid'])) { ?>
				<td class="on"><a href="?act=listing&action=skip"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Retour Liste</a></td>
			<?php } ?>
		<?php } ?>
		
		<?php if (in_array("Mes Jobs", $menuhaut)) { ?>
			<td class="on"><a href="?act=listing&listing=direct"><img src="<?php echo STATIK ?>illus/liste.gif" alt="search" width="32" height="32" border="0"><br>Mes Jobs</a></td>
		<?php } ?>
		
		<?php if (!empty($idmerch)) { ?>
			<?php if (in_array("Retour Job", $menuhaut)) { ?>
				<td class="on"><a href="?act=show&idmerch=<?php echo $idmerch ;?>"><img src="<?php echo STATIK ?>illus/selectionner2.gif" alt="show" width="32" height="32" border="0"><br>Retour Job</a></td>
			<?php } ?>
			
			<?php if (($viewj == '1') and ($_SESSION['archive'] != 'archive')){ ?>
				<?php if (in_array("Dupliquer", $menuhaut)) { ?>
					<td class="on"><a href="?act=duplic&idmerch=<?php echo $idmerch ;?>"><img src="<?php echo STATIK ?>illus/dupliquer.gif" alt="show" width="32" height="32" border="0"><br>Dupliquer</a></td>
				<?php } ?>
				
				<?php if (in_array("Effacer Job", $menuhaut)) { ?>
					<td class="on"><a href="?act=raisonout&idmerch=<?php echo $idmerch ;?>" onClick="return confirm('Etes-vous sur de vouloir effacer ce job?')"><img src="<?php echo STATIK ?>illus/trash.gif" alt="show" width="32" height="32" border="0"><br>Effacer Job</a></td>
				<?php } ?>
			<?php } ?>

		<?php } ?>

		<?php if (in_array("Planning", $menuhaut)) { ?>
			<td class="on"><a href="?act=planningsearch"><img src="<?php echo STATIK ?>illus/planning.gif" alt="search" width="32" height="32" border="0"><br>Planning</a></td>
		<?php } ?>
		
		<?php if (!empty($_SESSION['planquid'])) { ?>
			<?php if (in_array("Retour PL", $menuhaut)) { ?>
				<td class="on"><a href="?act=planning&action=skip"><img src="<?php echo STATIK ?>illus/plresult.gif" alt="show" width="32" height="32" border="0"><br>Retour PL</a></td>
			<?php } ?>
		<?php } ?>

		<?php if (in_array("valideas", $menuhaut)) { ?>
			<td class="on"><a href="?act=valideas"><img src="<?php echo STATIK ?>illus/valideas.png" alt="show" width="32" height="32" border="0"><br>Valid EAS</a></td>
		<?php } ?>
		
		<?php if (in_array("Rech Fac", $menuhaut)) { ?>
			<td class="on"><a href="?act=faclistingsearch"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rech Fac</a></td>
		<?php } ?>

		<?php if (in_array("Liste Facture", $menuhaut)) { ?>
			<td class="on"><a href="?act=facture&action=skip"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Liste Facture</a></td>
		<?php } ?>
		
		<?php if (in_array("Historic", $menuhaut)) { ?>
			<td class="on"><a href="?act=historic"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="search" width="32" height="32" border="0"><br>Retour liste Historic</a></td> 
		<?php } ?>

		<?php if (in_array("Historicsearch", $menuhaut)) { ?>
			<td class="on"><a href="?act=historicsearch"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>R. Historic</a></td> 
		<?php } ?>
	</tr>
</table> 
</div>

<?php
# Pied de Page
include NIVO."includes/pied.php" ;
?>