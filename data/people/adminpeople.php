<?php
define('NIVO', '../../');

$Titre = 'PEOPLE';
$Style = 'standard';
$PhraseBas = 'S&eacute;lection d\'un people';

## Entete
include_once NIVO."includes/entete.php" ;

include_once NIVO."classes/geocalc.php" ;
include_once NIVO."classes/makehtml.php" ;
include_once NIVO."classes/photo.php";

include_once "m-people.php";

$geocalc = new GeoCalc();

#### pour le casting
if (!empty($_REQUEST['casting'])) $_SESSION['casting'] = $_REQUEST['casting'];

# Carousel des fonctions
switch ($_REQUEST['act'])
{
################# GEOLOC #############################################################
		case "geoloc":
			$idpeople = $_REQUEST['idpeople'];
			$peoplehome = $DB->getOne('SELECT peoplehome FROM people WHERE idpeople='.$idpeople.' LIMIT 1');
			$pinfo = $DB->getRow('SELECT num'.$peoplehome.' AS num,
									adresse'.$peoplehome.' AS adresse,
									ville'.$peoplehome.' AS ville,
									pays'.$peoplehome.' AS pays,
									cp'.$peoplehome.' AS cp
								 FROM people WHERE idpeople = "'.$idpeople.'" LIMIT 1');

			$results = $geocalc->getCoordinates($pinfo['num'], $pinfo['adresse'], $pinfo['ville'], $pinfo['pays'], $pinfo['cp']);
			if(count($results['response'])>0) {
				if(count($results['response'])==1) { #un seul résultat
					$DB->inline('UPDATE people SET
									glat'.$peoplehome.'='.$results['response'][0]['lat'].',
									glong'.$peoplehome.'= '.$results['response'][0]['lon'].'
									WHERE idpeople = '.$idpeople.' LIMIT 1');
					include "v-detail.php" ;
				} else { #plusieurs résulats
					echo '<div id="centerzonelarge">
					Résultats pour: '.$results['query'].'<br/>
					<table border=0>';
					foreach($results['response'] as $row) {
						echo '<tr>
						<form action="'.$_SERVER['PHP_SELF'].'?act=injgeo&from='.$_GET['from'].'" method="post">
						<input type="hidden" name="idpeople" value="'.$idpeople.'">
						<input type="hidden" name="lon" value="'.$row['lon'].'">
						<input type="hidden" name="lat" value="'.$row['lat'].'">
						<input type="hidden" name="address" value="'.$row['address'].'">
						<input type="hidden" name="cp" value="'.$row['cp'].'">
						<input type="hidden" name="ville" value="'.$row['ville'].'">
						<td>'.$row['lon'].'</td>
						<td>'.$row['lat'].'</td>
						<td>'.$row['address'].' '.$row['num'].', '.$row['cp'].' '.$row['ville'].'</td>
						<td><input type="submit" value="Select"></td>
						</form>
						</tr>';
					}
					echo '<td colspan="3"></td><td><a href="'.$_SERVER['PHP_SELF'].'?act=show&idpeople='.$idpeople.'&from='.$_GET['from'].'" class="bton">Annuler</a></td>
					</table>
					</div>';
				}
			} else {
				include "v-detail.php" ;
			}
		break;

################# Injecter lat et lon dans la base ###################################
		case "injgeo":
			$idpeople = $_REQUEST['idpeople'];
			$peoplehome = $DB->getOne('SELECT peoplehome FROM people WHERE idpeople='.$idpeople.' LIMIT 1');

			$sql = 'UPDATE people SET glat'.$peoplehome.' = "'.$_REQUEST['lat'].'", glong'.$peoplehome.' = "'.$_REQUEST['lon'].'"';
			if(isset($_POST['address'])) $sql .= ', adresse'.$peoplehome.' = "'.$_POST['address'].'"
													, cp'.$peoplehome.' = "'.$_POST['cp'].'"
													, ville'.$peoplehome.' = "'.$_POST['ville'].'"';
			$sql.=' WHERE idpeople = "'.$idpeople.'" LIMIT 1';
			$DB->inline($sql);
			include "v-detail.php" ;
		break;

#OK ############# Effacement d'un PEOPLE #############################################
		case "delete":
			if (is_numeric($_GET['idpeople'])) $DB->inline("DELETE FROM people WHERE idpeople = ".$_GET['idpeople']);

			$_GET['action'] = skip;
			$PhraseBas = 'People Effac&eacute;';
			include "v-list.php" ;
		break;
#OK############# Ajout d'un PEOPLE #############################################
		case "add":
			$badempty = array();
			$defaultsql = " webetat = 8, dateinscription = NOW(), agentmodif = ".$_SESSION['idagent'].", datemodif = NOW()";

			# Récupère une ancienne fiche incomplète
			while (empty($DB->addid)) {
				$evite = (count($badempty) > 0)?' AND idpeople NOT IN ('.implode(", ", $badempty).')':'';
				$emptypeople = $DB->getOne("SELECT idpeople FROM people WHERE pnom = '' AND pprenom = '' AND adresse1 = '' AND codepeople = 0 ".$evite." ORDER BY idpeople LIMIT 1");

				if ($emptypeople > 0) {
					## s'assurer que le people n'est utilisé nulle part
					$anims = $DB->getOne("SELECT COUNT(*) FROM animation WHERE idpeople = ".$emptypeople);
					$vips = $DB->getOne("SELECT COUNT(*) FROM vipmission WHERE idpeople = ".$emptypeople);
					$merchs = $DB->getOne("SELECT COUNT(*) FROM merch WHERE idpeople = ".$emptypeople);
					$totmiss = (int)$anims + (int)$vips + (int)$merchs;

					if ($totmiss == 0) {
						## remplacer par une fiche vide
						$DB->inline("REPLACE INTO people SET idpeople = ".$emptypeople.", ".$defaultsql);
						## clean les photos

						$notify[] = "Réutilisation d'une ancienne fiche people vide";
					} else {
						## store bad fiche
						$warning[] = "Oups cette fiche people (".$emptypeople.") est vide mais est utilisée dans des missions !! Nico s'en occupe";
						$badempty[] = $emptypeople;
					}
				} else {
					## Ajout normal
					$DB->inline("INSERT INTO people SET ".$defaultsql);
				}
			}

			## notify admin si mauvaise fiche
			if (count($badempty) > 0) mail(Conf::read('Env.adminmail'), "ERR [NEURO] : People vide mais utilisé", "Les people suivants :".implode(", ", $badempty)." sont vides mais utilisés.");

			$idpeople = $DB->addid;

			$PhraseBas = 'Nouveau People';
			include "v-detail.php" ;
		break;
#OK############# Affichage d'un PEOPLE #########################################
		case "show":
			$PhraseBas = 'Detail d\'un PEOPLE';
			$idpeople = $_REQUEST['idpeople'];

			if(is_numeric($idpeople)) {
				include "v-detail.php" ;
			} else {
				include "v-search.php" ;
			}
		break;
############## Modification et affichage d'un PEOPLE #########################################
		case "modif":
		# vars
			$idpeople = $_POST['idpeople'];
			$_POST['datemodifweb'] = fdatebk($_POST['datemodifweb']);

			$oldrow = $DB->getRow('SELECT CONCAT(adresse1, num1, cp1, ville1, pays1) AS adresse1, CONCAT(adresse2, num2, cp2, ville2, pays2) AS adresse2, peoplehome
									FROM people WHERE idpeople = '.$idpeople);
			# mise a jour des infos
			if (empty($_POST['isout'])) $_POST['isout'] = 'ok';
			if(!empty($_POST['categorie'])) $_POST['categorie'] = (($_POST['categorie'][0]=='1')?'1':'0').(($_POST['categorie'][1]=='1')?'1':'0').(($_POST['categorie'][2]=='1')?'1':'0');
			if(!empty($_POST['conninformatiq'])) $_POST['conninformatiq'] = (($_POST['conninformatiq'][0]=='1')?'1':'0').(($_POST['conninformatiq'][1]=='1')?'1':'0').(($_POST['conninformatiq'][2]=='1')?'1':'0').(($_POST['conninformatiq'][3]=='1')?'1':'0');

			$DB->MAJ("people", "conditions_accepted");

			# relance geoloc si adresse modifiee
			$newadresse1 = $_POST['adresse1'].$_POST['num1'].$_POST['cp1'].$_POST['ville1'].$_POST['pays1'];
			$newadresse2 = $_POST['adresse2'].$_POST['num2'].$_POST['cp2'].$_POST['ville2'].$_POST['pays2'];
			if($oldrow['adresse1'] !== $newadresse1) $DB->inline('UPDATE people SET glat1="0", glong1="0" WHERE idpeople="'.$_POST['idpeople'].'" LIMIT 1');
			if($oldrow['adresse2'] !== $newadresse2) $DB->inline('UPDATE people SET glat2="0", glong2="0" WHERE idpeople="'.$_POST['idpeople'].'" LIMIT 1');
			if($oldrow['peoplehome'] !== $_POST['peoplehome'] OR $oldrow['adresse1'] !== $newadresse1 OR $oldrow['adresse2'] !== $newadresse2) {
				redirURL(NIVO.'data/people/adminpeople.php?act=geoloc&idpeople='.$idpeople);
			}

		## redirection selon page de provenance
			if(isset($_POST['select']) && $_POST['select'] == 'Valider et Retour') {
				if(!empty($_REQUEST['idmerch'])) redirURL(NIVO.'merch/adminmerch.php?act=show&act2=listing&idmerch='.$_REQUEST['idmerch']);
				else if(!empty($_REQUEST['idanimation'])) redirURL(NIVO.'animation2/adminanim.php?act=showmission&idanimation='.$_REQUEST['idanimation'].'&idanimjob='.$_REQUEST['idanimjob']);
				else if(!empty($_REQUEST['idvip'])) redirURL(NIVO.'vip/adminvip.php?act=showmission&idvip='.$_REQUEST['idvip']);
			} else include "v-detail.php" ;
		break;
############## Listing webupdate0 PEOPLE #########################################
		case "webupdate0":
			include "webupdatelist.php" ;
		break;
############## Listing webupdate1 PEOPLE #########################################
		case "webupdate1":
			$idpeople = $_GET['idpeople'];
			include "webdetail.php" ;
		break;
############## Modif des PEOPLE Web Updated #########################################
		case "webmodif":

#			if (!empty($_POST['champs'])) { $_POST['champs'] = fdatebk($_POST['champs']); }
			if (!empty($_POST['ndate'])) { $_POST['ndate'] = fdatebk($_POST['ndate']); }
			if (!empty($_POST['datemariage'])) { $_POST['datemariage'] = fdatebk($_POST['datemariage']); }
			if (!empty($_POST['dateconjoint'])) { $_POST['dateconjoint'] = fdatebk($_POST['dateconjoint']); }

			foreach ($_POST['champs'] as $val) {
				if (!empty($val)) {
					$larray[] = $val;
				}
			}
			$idpeople = $_POST['idpeople'];
			$idwebpeople = $_POST['idwebpeople'];
			$modif = new db('people', 'idpeople');
			$modif->MODIFIE($idpeople, $larray);

			$del = new db('webpeople', 'idwebpeople', 'webneuro');
			$del->EFFACE($idwebpeople);

				### illu
				$idp = $idpeople;

				if ($_POST['switchphoto'] == 'oui') {

					$photoweb = GetPhotoPath($idwebpeople, 'web', 'path');
					$photofile2 = GetPhotoPath($idp, 'photo', 'path');
					$photoraw2 = GetPhotoPath($idp, 'raw', 'path');

					$photoweb2 = GetPhotoPath($idwebpeople, 'web', 'path', '-b');
					$photofile = GetPhotoPath($idp, 'photo', 'path', '-b');
					$photoraw = GetPhotoPath($idp, 'raw', 'path', '-b');

				} else {

					$photofile = GetPhotoPath($idp, 'photo', 'path');
					$photoraw = GetPhotoPath($idp, 'raw', 'path');
					$photoweb = GetPhotoPath($idwebpeople, 'web', 'path');

					$photofile2 = GetPhotoPath($idp, 'photo', 'path', '-b');
					$photoraw2 = GetPhotoPath($idp, 'raw', 'path', '-b');
					$photoweb2 = GetPhotoPath($idwebpeople, 'web', 'path', '-b');

				}

				$photoout = GetPhotoPath($idp, 'out', 'path');
				$photoout2 = GetPhotoPath($idp, 'out', 'path', '-b');

				if (file_exists($photoweb)) $ok1 = true;
				if (file_exists($photoweb2)) $ok2 = true;


				if ($_POST['choixphoto1'] == "nouvelle") {

					if (file_exists($photoweb)) {

						if (file_exists($photoraw))  {
							$test = rename($photoraw, $photoout);
						}
						if (file_exists($photofile)) {
							unlink($photofile);
						}
						rename($photoweb, $photoraw);
					}
				}


				if ($_POST['choixphoto2'] == "nouvelle") {

					if (file_exists($photoweb2)) {

						if (file_exists($photoraw2)) {
							if ($ok1)
								rename($photoraw2, $photoout2);
							else
								rename($photoraw2, $photoout);
						}
						if (file_exists($photofile2)) {
							unlink($photofile2);
						}
						rename($photoweb2, $photoraw2);
					}
				}

				if ($_POST['choixphoto1'] == "ancienne") {
					rename($photoweb, $photoout);
				}

				if ($_POST['choixphoto2'] == "ancienne") {
					if ($ok1)
						rename($photoweb2, $photoout2);
					else
						rename($photoweb2, $photoout);
				}

				#/## illu

			$PhraseBas = 'Mise &agrave; jour des People web update';
			include "webupdatelist.php" ;
		#	$menuhaut = array ("Ajouter", "Rechercher", "Re-chercher", "To do", "Retour Liste", "Liste Web");
		break;
############## Listing webnew0 PEOPLE #########################################
		case "webnew0":
			include "webnewlist.php" ;
		break;
############## Detail webnew0 PEOPLE  #########################################
		case "webnew1":
			$idwebpeople = $_GET['idwebpeople'];
			include "webnewdetail.php" ;
		break;
############## Listing webnew0 PEOPLE Delete #########################################
		case "webnewdelete":
#			$idpeople = $_GET['idpeople'];
			$del = new db('webpeople', 'idwebpeople', 'webneuro'); #défini la table et le nom du champ ID
			$del->EFFACE($_POST['idwebpeople']);	# efface la fiche de cet ID

			$PhraseBas = 'People Effac&eacute;';
			include "webnewlist.php" ;
		break;

############## Modif des PEOPLE Web Updated #########################################
		case "webnewmodif":

#			if (!empty($_POST['champs'])) { $_POST['champs'] = fdatebk($_POST['champs']); }
			if (!empty($_POST['ndate'])) { $_POST['ndate'] = fdatebk($_POST['ndate']); }
			if (!empty($_POST['datemariage'])) { $_POST['datemariage'] = fdatebk($_POST['datemariage']); }
			if (!empty($_POST['dateconjoint'])) { $_POST['dateconjoint'] = fdatebk($_POST['dateconjoint']); }
			if(!empty($_POST['categorie'])) $_POST['categorie'] = (($_POST['categorie'][0]=='1')?'1':'0').(($_POST['categorie'][1]=='1')?'1':'0').(($_POST['categorie'][2]=='1')?'1':'0');
			if(!empty($_POST['conninformatiq'])) $_POST['conninformatiq'] = (($_POST['conninformatiq'][0]=='1')?'1':'0').(($_POST['conninformatiq'][1]=='1')?'1':'0').(($_POST['conninformatiq'][2]=='1')?'1':'0').(($_POST['conninformatiq'][3]=='1')?'1':'0');

			$idwebpeople = $_POST['idwebpeople'];
			$_POST['taille'] = cleannombreonly($_POST['taille']);

			//(à cause de l'ajax)
			$_POST['cp1'] = $_POST['zipcode'];
			$_POST['ville1'] = $_POST['city'];
			$_POST['cp2'] = $_POST['zipcode2'];
			$_POST['ville2'] = $_POST['city2'];
			$_POST['ncp'] = $_POST['zipcode3'];
			$_POST['nville'] = $_POST['city3'];

			if (empty($_POST['cp1'])) {
				$_POST['adresse1'] = '';$_POST['num1'] = '';$_POST['bte1'] = '';$_POST['ville1'] = '';$_POST['pays1'] = '';
			}
			if (empty($_POST['cp2'])) {
				$_POST['adresse2'] = '';$_POST['num2'] = '';$_POST['bte2'] = '';$_POST['ville2'] = '';$_POST['pays2'] = '';
			}


			$modif = new db('webpeople', 'idwebpeople', 'webneuro');
			$modif->MODIFIE($_POST['idwebpeople'], array('sexe' , 'pnom' , 'pprenom' , 'adresse1' , 'num1' , 'bte1' , 'cp1' , 'ville1' , 'pays1' , 'adresse2' , 'num2' , 'bte2' , 'cp2' , 'ville2' , 'pays2' , 'categorie', 'tel', 'fax', 'gsm', 'email', 'webpass', 'lfr', 'lnl', 'len', 'ldu', 'lit', 'lsp', 'physio', 'ccheveux', 'lcheveux', 'taille', 'tveste', 'tjupe', 'pointure', 'lbureau', 'notelangue', 'province', 'permis', 'voiture', 'ndate', 'ncp', 'nville', 'npays', 'ncidentite', 'nrnational', 'nationalite', 'etatcivil', 'datemariage', 'nomconjoint', 'dateconjoint', 'jobconjoint', 'pacharge', 'eacharge', 'banque', 'menspoi', 'menstai', 'menshan', 'conninformatiq', 'fume', 'conditions_accepted' ));


			### Injecter dans people et delete de webpeople
			if (!empty($_POST['Injecter']))
			{
				$_POST['isout'] = 'ok';
				# add
					$_POST['dateinscription'] = date("Y-m-d");
					$_POST['datemodifweb'] = date("Y-m-d");
					$ajout = new db('people', 'idpeople');
					$ajout->AJOUTE(array('pnom', 'pprenom', 'sexe', 'isout', 'dateinscription', 'datemodifweb'));
					$idpeople = $ajout->addid;
				# modif
					$_POST['taille'] = cleannombreonly($_POST['taille']);
					$modif = new db('people', 'idpeople');
					$modif->MODIFIE($idpeople, array('sexe' , 'pnom' , 'pprenom' , 'adresse1' , 'num1' , 'bte1' , 'cp1' , 'ville1' , 'pays1' , 'adresse2' , 'num2' , 'bte2' , 'cp2' , 'ville2' , 'pays2' , 'categorie', 'tel', 'fax', 'gsm', 'email', 'webpass', 'lfr', 'lnl', 'len', 'ldu', 'lit', 'lsp', 'physio', 'ccheveux', 'lcheveux', 'taille', 'tveste', 'tjupe', 'pointure', 'lbureau', 'notelangue', 'province', 'permis', 'voiture', 'ndate', 'ncp', 'nville', 'npays', 'ncidentite', 'nrnational', 'nationalite', 'etatcivil', 'datemariage', 'nomconjoint', 'dateconjoint', 'jobconjoint', 'pacharge', 'eacharge', 'banque', 'menspoi', 'menstai', 'menshan', 'conninformatiq', 'fume', 'conditions_accepted'));

				# del

					$del = new db('webpeople', 'idwebpeople', 'webneuro');
					$del->EFFACE($idwebpeople);


				### illu
					$idp = $idpeople;

					$photoweb = GetPhotoPath($idwebpeople, 'web', 'path');
					$photofile = GetPhotoPath($idp, 'photo', 'path');
					$photoraw = GetPhotoPath($idp, 'raw', 'path');

					$photoweb2 = GetPhotoPath($idwebpeople, 'web', 'path', '-b');
					$photofile2 = GetPhotoPath($idp, 'photo', 'path', '-b');
					$photoraw2 = GetPhotoPath($idp, 'raw', 'path', '-b');


					if ($_POST['image'] == 'oui') {

						if (file_exists($photoweb)) {

						  if (file_exists($photoraw))  {
						    ulink($photoraw);
						  }
						  if (file_exists($photofile)) {
					 	    unlink($photofile);
						  }
						  rename($photoweb, $photoraw);
						}

					}

					if ($_POST['image2'] == 'oui') {
						if (file_exists($photoweb2)) {

						  if (file_exists($photoraw2))  {
						    ulink($photoraw2);
						  }
						  if (file_exists($photofile2)) {
					 	    unlink($photofile2);
						  }
						  rename($photoweb2, $photoraw2);
						}
					}
				#/## illu
				include "webnewlist.php" ;
			}
			#/## Injecter dans people et delete de webpeople
			else
			{
				include "webnewdetail.php" ;
			}
			$PhraseBas = 'Mise &agrave; jour des People web New';
		break;

############## Regroupement PEOPLE  #########################################
		case "groupeassign":
			$DB->inline("UPDATE `vipmission` SET `idpeople` = '".$_REQUEST['idpeoplecible']."' WHERE `idpeople` = '".$_REQUEST['idpeoplesource']."'");
			$vipassign = $DB->affected;

			$DB->inline("UPDATE `animation` SET `idpeople` = '".$_REQUEST['idpeoplecible']."' WHERE `idpeople` = '".$_REQUEST['idpeoplesource']."'");
			$animassign = $DB->affected;

			$DB->inline("UPDATE `merch` SET `idpeople` = '".$_REQUEST['idpeoplecible']."' WHERE `idpeople` = '".$_REQUEST['idpeoplesource']."'");
			$merchassign = $DB->affected;

			if ($_REQUEST['idpeoplesource'] > 0) $DB->inline("DELETE FROM people WHERE idpeople = ".$_REQUEST['idpeoplesource']);
		case "groupeshow":
		case "groupeselect":
			if (empty($_REQUEST['idpeoplesource']) or empty($_REQUEST['idpeoplecible'])) $_REQUEST['act'] = 'groupeselect';
			$menuhaut = array ("Groupe");
			include "groupepeople.php" ;
		break;

############## executer une recherche #########################################
		case "list":
			if ($_GET['action'] != 'skip') {
				makePeopleSearch();
			}
			include "v-list.php" ;
		break;

############## Recherche d'un people #########################################
		case "search":
		default:
			$PhraseBas = 'Recherche People';
			include "v-search.php" ;
}
?>
<!-- Barre des Boutons -->
<div id="topboutons">
<table border="0" cellspacing="1" cellpadding="0">
	<tr>
		<td class="on"><a href="?act=add"><img src="<?php echo STATIK ?>illus/ajouter.gif" alt="ajouter" width="32" height="32" border="0"><br>Ajouter</a></td>
		<td class="on"><a href="?act=search"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
<?php
if (!empty($_SESSION['searchpeoplequid'])) { ?>
		<td class="on"><a href="?act=list&action=skip"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Retour Liste</a></td>
<?php } ?>
		<td width="32">&nbsp</td>
<?php
	if (($_SESSION['roger'] == 'devel') or ($_SESSION['idagent'] == '20') or ($_SESSION['idagent'] == '22')) {
		if ((!empty($idpeople)) and !($infos['codepeople'] > 0)) {  ?>
			<td class="on"><a href="?act=delete&idpeople=<?php echo $idpeople ?>" onClick="return confirm('Etes-vous sur de vouloir effacer ce jobiste?')"><img src="<?php echo STATIK ?>illus/trash.gif" alt="search" width="32" height="32" border="0"><br>Supprimer</a></td>
		<?php } ?>
	<?php } ?>
	<?php if (!empty($idpeople)) {  ?>
		<td class="on"><a href="<?php echo NIVO ?>print/people/casting/casting1.php?act=search&casting=<?php echo $infos['idpeople']; ?>" method="post" target="popupC" onclick="window.open('','popupC','scrollbars=yes,status=yes,resizable=yes,width=500,height=400');"><img src="<?php echo STATIK ?>illus/casting.gif" alt="search" width="32" height="32" border="0"><br>Print Cast</a></td>
	<?php } ?>
	<?php if(!empty($idpeople))
		  {
				?>
					<td class="on"><a href="<?php echo NIVO ?>mod/sms/adminsms.php?act=show&dest[]=<?php echo $idpeople; ?>" method="post" target="centerzonelarge"><img src="<?php echo STATIK ?>illus/gsm.png" alt="search" width="32" height="32" border="0"><br>SMS</a></td>
				<?php
			} ?>

	<?php if (isset($_SESSION['casting']) && ($_SESSION['casting'] != 'z') && !empty($_SESSION['casting'])) { ?>
		<td class="on"><a href="<?php echo NIVO.'vip/adminvip.php?act=show&casting='.$_SESSION['casting'].'&idvipjob='.$_SESSION['casting'].'&etat=1'; ?>"><img src="<?php echo STATIK ?>illus/casting.gif" alt="search" width="32" height="32" border="0"><br>Retour Cast</a></td>
	<?php } ?>
		<td width="32">&nbsp</td>
		<td class="on"><a href="?act=webupdate0"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Web Update</a></td>
		<td class="on"><a href="?act=webnew0"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Web New</a></td>
		<td width="32">&nbsp</td>
		<td class="on"><a href="?act=groupeselect"><img src="<?php echo STATIK ?>illus/regroupe.gif" alt="regroupement" width="32" height="32" border="1"><br>Regroupe</a></td>
	</tr>
</table>
</div>
<?php
# Pied de Page
include NIVO."includes/pied.php" ;
?>