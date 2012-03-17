<?php
define('NIVO', '../../'); 
$Titre = 'CLIENTS';
$Style = 'standard';

## Entete
include NIVO."includes/entete.php" ;

switch ($_REQUEST['act']) {
# OK ############# Effacement d'un client #############################################
		case "delete": 
			## Delete fiche Client
			$fields = "`idclient`, `codeclient`, `societe`, `secteur`, `qualite`, `cprenom`, `cnom`, `fonction`, `langue`, `adresse`, `cp`, `ville`, `pays`, `email`, 
					`notes`, `notelarge`, `tva`, `astva`, `codetva`, `logo`, `codecompta`, `tel`, `fax`, `login`, `password`, `facturation`, `factureofficer`, `hforfait`, 
					`taheure`, `takm`, `taforfait`, `taforfaitkm`, `tastand`, `tabriefing`, `tmheure`, `tmkm`, `tmforfait`, `tvheure05`, `tvheure6`, `tvnight`, `tvkm`, 
					`tvforfait`, `fraisdimona`, `etat`, `anim`, `merch`, `vip`, `agentmodif`, `datemodif`, `tv150`, `tm150`, `delai`";

			$DB->inline("INSERT INTO `zoutclient` (".$fields.") SELECT ".$fields." FROM `client` WHERE idclient = ".$_REQUEST['idclient']);
			$DB->inline("DELETE FROM `client` WHERE idclient = ".$_REQUEST['idclient']);
			
			## Delete Officers
			$cfields = "`idcofficer`, `idclient`, `langue`, `qualite`, `onom`, `oprenom`, `tel`, `fax`, `gsm`, `email`, `departement`, `agentmodif`, `datemodif`, `oldidclient`";

			$DB->inline("INSERT INTO `zoutcofficer` (".$cfields.") SELECT ".$cfields." FROM `cofficer` WHERE idclient = ".$_REQUEST['idclient']);
			$DB->inline("DELETE FROM `cofficer` WHERE idclient = ".$_REQUEST['idclient']);

			$PhraseBas = "Client ".$_REQUEST['idclient']." Effac&eacute;";
			include "v-list.php" ;
		break;

# OK ############# Ajout d'un client #############################################
		case "add": 
		
			$badempty = array();
			$defaultsql = " codetva = 'BE', pays = 'Belgique', agentmodif = ".$_SESSION['idagent'].", datemodif = NOW()";
		
			# Récupère une ancienne fiche incomplète
			while (empty($DB->addid)) {
				$evite = (count($badempty) > 0)?' AND idclient NOT IN ('.implode(", ", $badempty).')':'';
				$emptyclient = $DB->getOne("SELECT idclient FROM client WHERE societe = '' AND cprenom = '' AND cnom = '' AND tva = '' ".$evite." ORDER BY idclient LIMIT 1");

				if ($emptyclient > 0) {
					## s'assurer que le client n'est utilisé nulle part
					$anims = $DB->getOne("SELECT COUNT(*) FROM animjob WHERE idclient = ".$emptyclient);
					$vips = $DB->getOne("SELECT COUNT(*) FROM vipjob WHERE idclient = ".$emptyclient);
					$merchs = $DB->getOne("SELECT COUNT(*) FROM merch WHERE idclient = ".$emptyclient);
					$totmiss = (int)$anims + (int)$vips + (int)$merchs;

					if ($totmiss == 0) {
						## remplacer par une fiche vide 
						$DB->inline("DELETE FROM cofficer WHERE idclient = ".$emptyclient);
						$DB->inline("REPLACE INTO client SET idclient = ".$emptyclient.", ".$defaultsql);

						$notify[] = "Réutilisation d'une ancienne fiche client vide";
					} else {
						## store bad fiche
						$warning[] = "Oups cette fiche client (".$emptyclient.") est vide mais est utilisée dans des missions !! Nico s'en occupe";
						$badempty[] = $emptyclient;
					}
				} else {
					## Ajout normal
					$DB->inline("INSERT INTO client SET ".$defaultsql);
				}
			}
		
			## notify admin si mauvaise fiche
			if (count($badempty) > 0) mail(Conf::read('Env.adminmail'), "ERR [NEURO] : Client vide mais utilisé", "Les clients suivants :".implode(", ", $badempty)." sont vides mais utilisés.");

			$did = $DB->addid;

			$PhraseBas = 'Nouveau Client';
			include "detail.php" ;
		break;
# OK ############# Affichage d'un client #########################################
		case "show": 
			$PhraseBas = 'Detail d\'un client';
			$did = $_GET['idclient'];
			include "detail.php" ;
		break;
# OK ############# Modification et affichage d'un client #########################################
		case "modif": 
			$did = $_POST['idclient'];

			$_POST['codeclient'] = $did;
			$_POST['codecompta'] = $did;

			$DB->MAJ('client');

			$PhraseBas = 'Detail d\'un Client : Fiche Mise &agrave; Jour';
			include "detail.php" ;
		break;

############## Web VISIT list #############################################
		case "webvisit": 
			$PhraseBas = 'Liste des derni&egrave;res visites On-line';
			include "webvisitlist.php" ;
		break;

############## Web list #############################################
		case "weblist": 
			$PhraseBas = 'Liste des nouvelles demandes On-line';
			include "weblist.php" ;
		break;
############## Web show VIP #############################################
		case "webshowvip": 
			$idwebclient = $_GET['idwebclient'];
			$PhraseBas = 'Affichage des nouvelles demandes On-line';
			include "webdetailvip.php" ;
		break;
############## Web list  VIP #############################################
		case "webdeletevip": 
			$did = $_POST['idvipjob'];

		# Delete del old CLIENT
			$DB->inline("DELETE FROM webneuro.webclient WHERE `idwebclient` = ".$_POST['idwebclient']);
			$DB->inline("DELETE FROM webneuro.webvipbuild WHERE `idwebvipjob` = ".$_POST['idwebvipjob']);
			$DB->inline("DELETE FROM webneuro.webvipjob WHERE `idwebvipjob` = ".$_POST['idwebvipjob']);

			$PhraseBas = 'Liste des nouvelles demandes On-line';
			include "weblist.php" ;
		break;

############## VALIDATION d'un JOB VIP #########################################
		case "webvalidervip": 
		/*
			TODO : Cleaner CodaPat validation client web
		*/
			$idwebvipjob = $_POST['idwebvipjob'];
			$idwebclient = $_POST['idwebclient'];
			$did = $_POST['idwebvipjob'];

			### Search old CLIENT + Add new CLIENT + add new CLIENT officer + del old CLIENT

				# Search old CLIENT 
					$detailwebclient = new db('webclient', 'idwebclient ', 'webneuro');
					$detailwebclient->inline("SELECT * FROM `webclient` WHERE `idwebclient` = $idwebclient ");
					$infosclient = mysql_fetch_array($detailwebclient->result) ; 
	
					$_POST['astva'] = $infosclient['astva'];

					$_POST['societe'] = $infosclient['societe'];
					$_POST['qualite'] = $infosclient['qualite'];
					$_POST['cprenom'] = $infosclient['cprenom'];
					$_POST['cnom'] = $infosclient['cnom'];
					$_POST['fonction'] = $infosclient['fonction'];
					$_POST['langue'] = $infosclient['langue'];
					$_POST['adresse'] = $infosclient['adresse'];
					$_POST['cp'] = $infosclient['cp'];
					$_POST['ville'] = $infosclient['ville'];
					$_POST['pays'] = $infosclient['pays'];
					$_POST['email'] = $infosclient['email'];
					$_POST['tva'] = $infosclient['tva'];
					$_POST['codetva'] = $infosclient['codetva'];
					$_POST['tel'] = $infosclient['tel'];
					$_POST['fax'] = $infosclient['fax'];
					$_POST['password'] = $infosclient['password'];

				# Add new CLIENT
					$ajoutclient = new db('client', 'idclient');
					$ajoutclient->AJOUTE(array('astva', 'societe' , 'qualite' , 'cprenom' , 'cnom' , 'fonction' , 'langue' , 'adresse' , 'cp' , 'ville' , 'pays' , 'email' , 'tva' , 'codetva' , 'tel' , 'fax' , 'password'));
					$idclient = $ajoutclient->addid;
					$_POST['idclient'] = $ajoutclient->addid;

				# Add new CLIENT officer
					$_POST['oprenom'] = $infosclient['cprenom'];
					$_POST['onom'] = $infosclient['cnom'];
	
					$ajoutofficer = new db('cofficer', 'idcofficer');
					$ajoutofficer->AJOUTE(array('idclient' , 'langue' , 'qualite' , 'oprenom' , 'onom' , 'email' , 'tel' , 'fax'));
					$idcofficer = $ajoutofficer->addid;
					$_POST['idcofficer'] = $ajoutofficer->addid;

				# Delete del old CLIENT
					$clientdel = new db('webclient', 'idwebclient ', 'webneuro');
					$sqldel = "DELETE FROM `webclient` WHERE `idwebclient` = $idwebclient;";
					$clientdel->inline($sqldel);
			#/## Search old CLIENT + Add new CLIENT + add new CLIENT officer + del old CLIENT


			### search webvipjob ###
				$detailjob = new db('webvipjob', 'idvipjob ', 'webneuro');
				$detailjob->inline("SELECT * FROM `webvipjob` WHERE `idwebvipjob` = $idwebvipjob ");
				$infosjob = mysql_fetch_array($detailjob->result) ; 
			#/## search webvipjob ###

			### Add vipjob  + update ###
				$_POST['idclient'] = $idclient;
				$_POST['idcofficer'] = $idcofficer;
				$_POST['idagent'] = $_SESSION['idagent'];
	
				$_POST['reference'] = $infosjob['reference'];
				$_POST['notejob'] = $infosjob['notejob'];
				$_POST['noteprest'] = $infosjob['noteprest'];
				$_POST['notedeplac'] = $infosjob['notedeplac'];
				$_POST['noteloca'] = $infosjob['noteloca'];
				$_POST['notefrais'] = $infosjob['notefrais'];
				$_POST['datein'] = $infosjob['datein'];
				$_POST['dateout'] = $infosjob['dateout'];
	
				# Add
				$ajout = new db('vipjob', 'idvipjob');
				$ajout->AJOUTE(array('idclient' , 'idcofficer', 'idagent', 'etat'));
				$did = $ajout->addid;
				$idvipjob = $ajout->addid;
				# update
				$modif = new db('vipjob', 'idvipjob');
				$modif->MODIFIE($idvipjob, array('idclient' , 'idcofficer', 'idagent', 'etat', 'reference' , 'notejob' , 'noteprest' , 'notedeplac' , 'noteloca' , 'notefrais', 'datein' , 'dateout' ));
			#/## Add vipjob  + update ###
			### Search Webvipbuild et creation Vipdevis 
						#Search
						$detailbuild = new db('webvipbuild', 'idvipbuild', 'webneuro');
						$detailbuild->inline("SELECT * FROM `webvipbuild` WHERE `idwebvipjob` = $idwebvipjob ORDER BY 'vipactivite'");
						while ($infos = mysql_fetch_array($detailbuild->result)) { 
							$i++;
							$h = 0; #pour nombre hotesse 
							$vipdate1 = $infos['vipdate1'];
							#fragmentation par date 
							while ($vipdate1 <= $infos['vipdate2']) {
								$h++;
								$vipnombre = $infos['vipnombre'];
								#fragmentation par nombre dans date 
								while ($vipnombre > 0) { 
									#Injection 
									############## Duplication d'une Mission VIPMISSION #############################################
									$vipdate = $vipdate1;
									$vipactivite = $infos['vipactivite'];
									$sexe = $infos['sexe'];
									$vipin = $infos['vipin'];
									$vipout = $infos['vipout'];

									$detailvip2 = new db('vipdevis', 'idvipjob');
									$detailvip2->inline("INSERT INTO `vipdevis` (`idvipjob` , `vipdate` , `vipactivite` , `sexe` , `vipin` , `vipout`) VALUES ('$idvipjob' , '$vipdate' , '$vipactivite' ,  '$sexe' , '$vipin' , '$vipout');");
									############## Duplication d'une Mission VIPMISSION #############################################
									$vipnombre--;
								}
								$dd = explode('-', $vipdate1);
								$vipdate1 = date ("Y-m-d", mktime (0,0,0,$dd[1],$dd[2]+1,$dd[0]));
							} 
						}
						$oldidvipjob = $idvipjob;

				# Delete del old Webvipbuild
					$webvipbuilddel = new db('webvipbuild', 'idvipbuild', 'webneuro');
					$sqldel = "DELETE FROM `webvipbuild` WHERE `idwebvipjob` = $idwebvipjob;";
					$webvipbuilddel->inline($sqldel);

				# Delete del old Webvipjob
					$webvipjobdel = new db('webvipjob', 'idvipjob ', 'webneuro');
					$sqldel = "DELETE FROM `webvipjob` WHERE `idwebvipjob` = $idwebvipjob;";
					$webvipjobdel->inline($sqldel);

			$PhraseBas = 'Validation d\'un JOB';
			$did = $idclient;
			include "detail.php" ;
		break;

# OK ############# Résultat de la recherche #########################################
		case "makesearch":
			$searchfields = array (
				'c.idclient'  => 'idclient',
				'c.codeclient'    => 'codeclient',
				'c.societe' => 'societe',
				'co.onom' => 'onom',
				'c.cp'  => 'cp',
				'c.ville' => 'ville',
				'c.etat' => 'etat'
			);

			$_SESSION['clientquid'] = $DB->MAKEsearch($searchfields) ;
			$_SESSION['clientquod'] = $DB->quod ;
		case "list": 
			$PhraseBas = 'Recherche Client';
			include "v-list.php" ;
		break;

# OK ############# Recherche d'une mission #########################################
		case "search": 
		default: 
			$PhraseBas = 'Recherche Client';
			include "v-searchForm.php" ;
}
?>
<div id="topboutons">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="on"><a href="?act=add"><img src="<?php echo STATIK ?>illus/ajouter.gif" alt="ajouter" width="32" height="32" border="0"><br>Ajouter</a></td>
			<td class="on"><a href="?act=search"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
<?php if (!empty($_SESSION['clientquid'])) { ?>
			<td class="on"><a href="?act=list"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Retour Liste</a></td>
<?php } ?>
<?php if ((($_SESSION['roger'] == 'devel') or ($_SESSION['roger'] == 'admin')) and ($did > 0)) { ?>
			<td class="on"><a href="?act=delete&idclient=<?php echo $did ?>" onClick="return confirm('Etes-vous sur de vouloir effacer ce client?')"><img src="<?php echo STATIK ?>illus/trash.gif" alt="search" width="32" height="32" border="0"><br>Supprimer</a></td>
<?php } ?>
		</tr>
	</table> 
</div>
<?php
# Pied de Page
include NIVO."includes/pied.php" ;
?>