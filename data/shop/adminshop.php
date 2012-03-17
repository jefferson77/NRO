<?php
# Entete de page
define('NIVO', '../../'); 
$Titre = 'LIEUX';
$Style = 'standard';

$PhraseBas = 'S&eacute;lection d\'un SHOP';

## Classes
include_once NIVO."classes/geocalc.php" ;
include_once NIVO."classes/makehtml.php" ;

## entete
if($_GET['embed']=='yes') {
	include NIVO."includes/ifentete.php" ;
} else include NIVO."includes/entete.php" ;

$geocalc = new GeoCalc();

# vars
$idshop=(!empty($_GET['idshop']))?$_GET['idshop']:$_POST['idshop'];
$idmerch=(!empty($_GET['idmerch']))?$_GET['idmerch']:$_POST['idmerch'];
$idanimation=(!empty($_GET['idanimation']))?$_GET['idanimation']:$_POST['idanimation'];
$idanimjob=(!empty($_GET['idanimjob']))?$_GET['idanimjob']:$_POST['idanimjob'];
$idvip=(!empty($_GET['idvip']))?$_GET['idvip']:$_POST['idvip'];
$idvipjob=(!empty($_GET['idvipjob']))?$_GET['idvipjob']:$_POST['idvipjob'];

# Carousel des fonctions
switch ($_GET['act']) {
################# GEOLOC #############################################################
		case "geoloc":
			$idshop = (!empty($_GET['idshop']))?$_GET['idshop']:$_POST['idshop'];
			$shopinfo = $DB->getRow('SELECT adresse, ville, pays, cp FROM shop WHERE idshop = "'.$idshop.'" LIMIT 1');
			preg_match('`\d+`', $shopinfo['adresse'], $reg1);
			preg_match('`[^\d]+`', $shopinfo['adresse'], $reg2);
			$shopinfo['num'] = trim($reg1[0]);
			$shopinfo['adresse']=trim($reg2[0]);
			$results = $geocalc->getCoordinates($shopinfo['num'], $shopinfo['adresse'], $shopinfo['ville'], $shopinfo['pays'], $shopinfo['cp']);
			if(count($results['response'])>0) {
				if(count($results['response'])==1) { #un seul résultat
					$DB->inline('UPDATE shop SET glat='.$results['response'][0]['lat'].', glong= '.$results['response'][0]['lon'].' WHERE idshop = '.$idshop.' LIMIT 1');
					include "detail.php" ;
					alertBox('Geoloc recalculée');
				} else { #plusieurs résulats
					echo '<div id="centerzonelarge">
					<table border=0>';
					foreach($results['response'] as $row) {
						echo '<tr>
						<form action="'.$_SERVER['PHP_SELF'].'?act=injgeo&embed='.$_GET['embed'].'&from='.$_GET['from'].'" method="post">
						<input type="hidden" name="idshop" value="'.$idshop.'">
						<input type="hidden" name="lon" value="'.$row['lon'].'">
						<input type="hidden" name="lat" value="'.$row['lat'].'">
						<input type="hidden" name="address" value="'.$row['address'].'">
						<input type="hidden" name="cp" value="'.$row['cp'].'">
						<input type="hidden" name="ville" value="'.$row['ville'].'">
						<td>'.$row['lon'].'</td>
						<td>'.$row['lat'].'</td>
						<td>'.$row['address'].', '.$row['cp'].' '.$row['ville'].'</td>
						<td><input type="submit" value="Select"></td>
						</form>
						</tr>';
					}
					echo '<td colspan="3"></td><td><a href="'.$_SERVER['PHP_SELF'].'?act=show&idshop='.$idshop.'&embed='.$_GET['embed'].'&from='.$_GET['from'].'" class="bton">Annuler</a></td>
					</table>
					</div>';
				}
			} else {
				alertBox('Aucun résultat pour: '.$results['query']);
				include "detail.php" ;			
			}
		break;

################# Injecter lat et lon dans la base ###################################
		case "injgeo":
			$idshop = (!empty($_POST['idshop']))?$_POST['idshop']:$_GET['idshop'];
			$lat = (!empty($_POST['lat']))?$_POST['lat']:$_GET['lat'];
			$lon = (!empty($_POST['lon']))?$_POST['lon']:$_GET['lon'];
			$sql = 'UPDATE shop SET glat = "'.$lat.'", glong = "'.$lon.'"';
			if(isset($_POST['address'])) $sql .= ', adresse = "'.$_POST['address'].'"
													, cp'.$peoplehome.' = "'.$_POST['cp'].'"
													, ville'.$peoplehome.' = "'.$_POST['ville'].'"';
			$sql.=' WHERE idshop = "'.$idshop.'" LIMIT 1';
			$DB->inline($sql);
			include "detail.php" ;
		break;

############## Effacement d'une Mission #############################################
		case "delete": 
			$del = new db('shop', 'idshop'); 
			$del->EFFACE($_GET['idshop']);

			$PhraseBas = 'Client Effac&eacute;';

			include "search.php" ;
		break;
############## Ajout d'un SHOP #############################################
		case "add": 
			$ajout = new db('shop', 'idshop');
			$ajout->AJOUTE(array('codeshop'));
			$PhraseBas = 'Nouveau Lieu';
			$idshop = $ajout->addid;
			include "detail.php" ;
		break;
############## Affichage d'une Mission #########################################
		case "show": 
			$PhraseBas = 'Detail d\'un Shop';
			include "detail.php" ;
		break;
############## Modification et affichage d'une Mission #########################################
		case "modif": 
			$oldadresse = $DB->getOne('SELECT CONCAT(adresse, cp, ville) FROM shop WHERE idshop="'.$idshop.'" LIMIT 1');
			/*
				TODO passer à MAJ()
			*/
			## verif si codeshop doublon + update
			$exists = $DB->getOne('SELECT codeshop FROM shop WHERE codeshop="'.$_POST['codeshop'].'"');
			$codeshop = $DB->getOne('SELECT codeshop FROM shop WHERE idshop="'.$idshop.'"');
			$modif = new db('shop', 'idshop');
			$arraymodif = array('societe' , 'adresse' , 'cp' , 'ville' , 'pays' , 'tel' , 'fax' , 'email' , 'web' , 'qualite' , 'sprenom' , 'snom' , 'slangue' , 'fonction' , 'notes', 'eassemaine', 'ccfd', 'newweb','docpref');
			if($exists AND $codeshop !== $_POST['codeshop']) alertBox('Ce codeshop est déjà attribué.');
			else $arraymodif[]='codeshop';			
			$modif->MODIFIE($idshop, $arraymodif);
			
			
			if(!empty($_POST['modif'])) { ## re-geoloc si adresse modifiée
				if($oldadresse !== $_POST['adresse'].$_POST['cp'].$_POST['ville']) {					
					redirURL(NIVO.'data/shop/adminshop.php?embed='.$_GET['embed'].'&from='.$_GET['from'].'&act=geoloc&idshop='.$idshop.'&idvip='.$idvip.'&idvipjob='.$idvipjob.'&idmerch='.$idmerch.'&idanimation='.$idanimation.'&idanimjob='.$idanimjob);
				} else include "detail.php";
			} else { # redirection page merch, anim ou vip (mission/job)
				if(!empty($idmerch)) {
					$DB->inline('UPDATE merch SET idshop='.$idshop.' WHERE idmerch='.$idmerch.' LIMIT 1');
					redirURL(NIVO.'merch/adminmerch.php?act=show&act2=listing&idmerch='.$idmerch);
				} else if(!empty($idanimation)) {
					$DB->inline('UPDATE animation SET idshop='.$idshop.' WHERE idanimation='.$idanimation.' LIMIT 1');
					redirURL(NIVO.'animation2/adminanim.php?act=showmission&idanimation='.$idanimation.'&idanimjob='.$idanimjob);
				} else if(!empty($idvipjob)) {
               switch ($_GET['from']) {
                  case"vip": # listing mission vip
                     $DB->inline('UPDATE vipmission SET idshop='.$idshop.' WHERE idvip='.$idvip.' LIMIT 1');
                     redirURL(NIVO.'vip/adminvip-devis.php?act=show&etat=1&s=1&idvipjob='.$idvipjob);
                  break;
                  case"vipdevis": # listing devis
                     $DB->inline('UPDATE vipdevis SET idshop='.$idshop.' WHERE idvip='.$idvip.' LIMIT 1');
                     redirURL(NIVO.'vip/adminvip-devis.php?act=show&etat=0&s=1&idvipjob='.$idvipjob.'&secteur=VI');
                  break;
                  case"JOB": # Job
                     $DB->inline('UPDATE vipjob SET idshop='.$idshop.' WHERE idvipjob='.$idvipjob.' LIMIT 1');
                     redirURL(NIVO.'vip/adminvip.php?act=show&etat=1&idvipjob='.$idvipjob);
                  break;
                  case"mission": # detail d'une mission
                     $DB->inline('UPDATE vipmission SET idshop='.$idshop.' WHERE idvip='.$idvip.' LIMIT 1');
                     redirURL(NIVO.'vip/adminvip.php?act=showmission&idvip='.$idvip);
                  break;
                  case"": # creamission
                     $DB->inline('UPDATE vipbuild SET idshop='.$idshop.' WHERE idvipjob='.$idvipjob.' LIMIT 1');
                     redirURL(NIVO.'vip/adminvip-moulinette.php?&idvipjob='.$idvipjob);
                  break;
                  default: ##
                     echo '<div class="sqlerror">PERDU LE FROM</div>';
               }
				} 
			} 			
			
		break;
############## UPDATE dans Listing des Recherche d'un SHOP #############################################
		case "listingmodif": 
			$modif = new db('shop', 'idshop');
			$modif->MODIFIE($idshop, array('qualite', 'sprenom' , 'snom', 'slangue'));
			include "search.php" ;
			$PhraseBas = 'Recherche des lieux : Fiche Mise &agrave; Jour';
		break;
############## Regrouperment de deux SHOP #########################################
		case "merge":
			echo '<div id="centerzonelarge">
			<div align="center">
			<br/>';			
			if(!isset($_POST['idshopfrom']) OR !isset($_POST['idshopto'])) {
			## Affiche le formulaire de recherche
				?>
				<form name="mergeshopcode" method="post" action="<?php $_SERVER['PHP_SELF'].'?act=merge'; ?>">
				ID shop source : <input type="text" name="idshopfrom"> ==> ID shop cible : <input type="text" name="idshopto">
				<input type="submit" value="Selectionner">
				</form>
				<?php
			} else if(!isset($_POST['send'])){
			## Affiche le formulaire de modif
				$infoshopfrom = $DB->getRow('SELECT * FROM shop WHERE idshop="'.$_POST['idshopfrom'].'" LIMIT 1');
				$infoshopto = $DB->getRow('SELECT * FROM shop WHERE idshop="'.$_POST['idshopto'].'" LIMIT 1');
				echo '
				<div>
					<strong>Cochez les infos à conserver après le regroupement.</strong>
				</div>
				<br/>
				<form name="mergeform" method="post" action="'.$_SERVER['PHP_SELF'].'?act=merge">
				<input type="hidden" name="send" value="1">
				<input type="hidden" name="idshopfrom" value="'.$_POST['idshopfrom'].'">
				<input type="hidden" name="idshopto" value="'.$_POST['idshopto'].'">
				<table cellspacing="1" cellpadding="2">
					<tr>
						<th width="20%">&nbsp;</th>
						<th width="40%" class="vip" colspan="2">Shop Source</th>
						<th width="40%" class="vip" colspan="2">Shop Cible</th>
					</tr>';
					$fields = array_keys($infoshopfrom);
					for($i=0; $i<count($infoshopfrom); $i++) {
						$bgcolor=(fmod($i,2)==1)?'#666':'';
						echo '
						<tr bgcolor="'.$bgcolor.'">
							<td class="etiq">'.$fields[$i].'</td>';
							echo ($infoshopfrom[$fields[$i]] !== $infoshopto[$fields[$i]] AND $fields[$i] !== 'idshop')?'<td><input type="radio" value="'.$infoshopfrom[$fields[$i]].'" name="'.$fields[$i].'"></td>':'<td>&nbsp;</td>';
							echo '<td>'.$infoshopfrom[$fields[$i]].'</td>';
							echo ($infoshopfrom[$fields[$i]] !== $infoshopto[$fields[$i]] AND $fields[$i] !== 'idshop')?'<td><input type="radio" value="'.$infoshopto[$fields[$i]].'" name="'.$fields[$i].'" checked></td>':'<td>&nbsp;</td>';
							echo '<td>'.$infoshopto[$fields[$i]].'</td>
						</tr>';
					}
				echo '
				</table>
				<div><input type="submit" value="Regrouper"></div>
				</form>';
			} else {
				## Traite le forulaire de modif et affiche le résultat
				$infoshopfrom = $DB->getRow('SELECT * FROM shop WHERE idshop="'.$_POST['idshopfrom'].'" LIMIT 1');
				$infoshopto = $DB->getRow('SELECT * FROM shop WHERE idshop="'.$_POST['idshopto'].'" LIMIT 1');
				$sql = 'UPDATE shop SET ';
				$fields = array_keys($_POST);
				for($i=3; $i<count($_POST); $i++) {
					$sql.= $fields[$i].'="'.$_POST[$fields[$i]].'"';
					if($i<count($_POST)-1) $sql.=', ';
				}
				$sql.=' WHERE idshop="'.$_POST['idshopto'].'" LIMIT 1';
				$DB->inline($sql);
				$DB->inline('DELETE FROM shop WHERE idshop="'.$_POST['idshopfrom'].'" LIMIT 1');
				$DB->inline('UPDATE vipmission SET idshop="'.$_POST['idshopto'].'" WHERE idshop="'.$_POST['idshopfrom'].'"');
				$DB->inline('UPDATE vipjob SET idshop="'.$_POST['idshopto'].'" WHERE idshop="'.$_POST['idshopfrom'].'"');
				$DB->inline('UPDATE animation  SET idshop="'.$_POST['idshopto'].'" WHERE idshop="'.$_POST['idshopfrom'].'"');
				$DB->inline('UPDATE animjob SET idshop="'.$_POST['idshopto'].'" WHERE idshop="'.$_POST['idshopfrom'].'"');
				$DB->inline('UPDATE merch SET idshop="'.$_POST['idshopto'].'" WHERE idshop="'.$_POST['idshopfrom'].'"');
				echo 'Shop modifié';

			}
			echo '</div>';
		break;
############## Recherche d'un SHOP #########################################
		case "search": 
		default: 
			$PhraseBas = 'Recherche LIEUX';
			$idshop = '';
			include "search.php" ;
}

if($_GET['embed']!== 'yes') {
	?>
	<!-- Barre des Boutons -->
	<div id="topboutons">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=add"><img src="<?php echo STATIK ?>illus/ajouter.gif" alt="ajouter" width="32" height="32" border="0"><br>Ajouter</a></td>
			<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=search&etat=0"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
	<?php 
	if ($_SESSION['shopquid'] != "") { ?>
			<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=search&etat=1&action=skip"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Retour Liste</a></td>
	<?php } ?>
		<?php if ($idshop != "") {
			if (($_SESSION['roger'] == 'devel') or ($_SESSION['roger'] == 'admin')) { ?>
			<?php } 
			} ?>
		<?php if (($idshop != "") and ($newweb == 1)) {
			if (($_SESSION['roger'] == 'devel') or ($_SESSION['roger'] == 'admin')) { ?>
				<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=delete&idshop=<?php echo $idshop ?>&etat=1" onClick="return confirm('Etes-vous certain de vouloir effacer ce nouveau POS?\nAucun job li&eacute; &agrave; ce POS ?')"><img src="<?php echo STATIK ?>illus/trash.gif" alt="search" width="32" height="32" border="0"><br>Supprimer</a></td>
			<?php } 
		} ?>
			<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=merge"><img src="<?php echo STATIK ?>illus/regroupe.gif" alt="search" width="32" height="32" border="0"><br>Regrouper</a></td>
		</tr>
	</table> 
	</div>
	<?php
	
	# Pied de Page
	 include NIVO."includes/pied.php" ;
}
	?>
	
