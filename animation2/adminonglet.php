<?php
# Entete de page
define('NIVO', '../');
setlocale(LC_TIME, 'fr_FR');

include NIVO."includes/ifentete.php" ;
include 'm-onglet.php';
include NIVO.'classes/anim.php';

# Fichiers Langues
include 'varfr.php' ;

$infosjob = $DB->getRow("SELECT * FROM `animjob` WHERE `idanimjob` = ".$_REQUEST['idanimjob']);
## Cleaning du shopselection
$infosjob['shopselection'] = cleanShopSelection($infosjob['shopselection']);

switch ($_GET['act']) {
# =========================================================================================================
# = Onglet Creamission ================================================================================== =
# =========================================================================================================
############## Crea section ###################################### > #
	# Affichage de la liste des groupes de misssions #######
		case 'grpshow':
			include 'v-onglet-creaList.php';
		break;
	# Ajout d'un groupe de missions #######
		case "grpadd": 
			$DB->inline("INSERT INTO animbuild SET metat = 0, idanimjob = ".$_REQUEST['idanimjob']);
			include 'v-onglet-creaList.php';
		break;
	# Effacement d'un groupe de missions #######
		case "grpdel": 
			if ($_POST['idanimbuild'] > 0) $DB->inline("DELETE FROM `animbuild` WHERE `idanimbuild` = ".$_POST['idanimbuild']);
			include 'v-onglet-creaList.php';
		break;
	# Update d'un groupe de missions #######
		case "grpmod": 
		## km auto ou fixes
			if (empty($_POST['kmfacture'])) $_POST['kmauto'] = 'Y';
			if ($_POST['kmauto'] == 'Y') $_POST['kmfacture'] = '';
			if (empty($_POST['kmauto'])) $_POST['kmauto'] = 'N';
		## autres defaults
			if (empty($_POST['animdate2'])) $_POST['animdate2'] = $_POST['animdate1'];
			if (empty($_POST['animnombre'])) $_POST['animnombre'] = 1;
			$DB->MAJ('animbuild');
			include 'v-onglet-creaList.php';
		break;
	# Affichage du récapitulatif #######
		case "grpverif": 
			include 'v-onglet-creaVerif.php';
		break;

############## Produits section ######################################### > #
	# Affichage des produits #######
		case "prodshow": 
			include 'v-onglet-creaProduit.php';
		break;
	# Ajout d'un produit #######
		case "prodadd": 
			$Addid = $DB->ADD('animbuildproduit');
			include 'v-onglet-creaProduit.php';
		break;
	# Duplication d'un produit #######
		case "prodduplic": 
			############## Recherche infos d'un Produit
			$fields = "`idanimjob`, `types` , `prix` , `produitin` , `unite` , `produitend` , `ventes` , `produitno` , `degustation` , `promoin` , `promoout` , `promoend`";
			$DB->inline("INSERT INTO animbuildproduit (".$fields.") SELECT ".$fields." FROM animbuildproduit WHERE idanimbuildproduit = ".$_POST['idanimbuildproduit']);
			include 'v-onglet-creaProduit.php';
		break;
	# Modification d'un produit #######
		case "prodmod": 
			$DB->MAJ('animbuildproduit', 'produitno');
			include 'v-onglet-creaProduit.php';
		break;
	# Effacement d'un produit #######
		case "proddel": 
			if ($_POST['idanimbuildproduit'] > 0) $DB->inline("DELETE FROM `animbuildproduit` WHERE `idanimbuildproduit` = ".$_POST['idanimbuildproduit']);
			include 'v-onglet-creaProduit.php';
		break;
############## Shops section ######################################### > #
	# Listing des shops #######
		case 'shoplist':
			include 'v-onglet-creaShop.php';
		break;
	# Selection des shops #######
		case "shopselect": 
			if (!is_array($_POST['shopselectionbuild'])) $_POST['shopselectionbuild'] = array(); ## Evite un erreur dans la ligne du dessous avec implode
			$DB->inline("UPDATE `animbuild` SET shopselectionbuild = '|".implode("|", $_POST['shopselectionbuild'])."|' WHERE `idanimjob` = ".$_REQUEST['idanimjob']);
			include 'v-onglet-creaShop.php';
		break;
# =========================================================================================================
# = Onglet POS ========================================================================================== =
# =========================================================================================================
	# Affiche la liste des shops séléctionnés pour le Job ############
		case 'animshopshow':
			include 'v-onglet-shopShow.php';
		break;
	# Formulaire de recherche de Shops a associer au Job #############
		case 'animshopsearch':
			include 'v-onglet-shopSearch.php';
		break;
	# Résultats de la recheche de shops ##############################
		case 'animshoplist':
				if (empty($_POST['codeshop']) and empty($_POST['societe']) and empty($_POST['cp']) and empty($_POST['ville'])) {
					$textvide = "Veuillez remplir au moins un crit&egrave;re de recherche, s'il vous plait.<br>";
					include 'v-onglet-shopSearch.php';
				} else {
					$searchfields = array (
						'codeshop'  => 'codeshop',
						'societe'    => 'societe',
						'cp' => 'cp',
						'ville' => 'ville'
					);

					$shops = $DB->getArray("SELECT * FROM `shop` WHERE ".$DB->MAKEsearch($searchfields)." LIMIT 200");
					$titreListe = "Résultat de la recherche Shop";
					include 'v-onglet-shopList.php';
				}
		break;
	# Liste des jobs déjà utilisés pour ce client dans des jobs antérieurs #######
		case 'animshophistoric':
			$shops = $DB->getArray("SELECT 
					s.*
				FROM animation an
					LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
					LEFT JOIN shop s ON an.idshop = s.idshop
				WHERE j.idclient = ".$infosjob['idclient']."
				GROUP BY s.idshop");

			$titreListe = "Magasin de l'historique du Client";
			include 'v-onglet-shopList.php';
		break;
	# Ajout des shops choisis à la séléction du job ######################
		case 'animshopadd':
			if (count($_POST['shopadd']) > 0) {
				$shsel = array_filter(explode("|", $infosjob['shopselection']));
				$newshopsel = array_merge($shsel, array_filter($_POST['shopadd']));
				sort($newshopsel);
				$infosjob['shopselection'] = "|".implode("|", $newshopsel)."|";
				$DB->inline("UPDATE animjob SET shopselection = '".$infosjob['shopselection']."' WHERE idanimjob = ".$_REQUEST['idanimjob']);
			}

			include 'v-onglet-shopShow.php';
		break;
	# Modification des shops de la séléction du job ######################
		case "animshopmodif":
			$infosjob['shopselection'] = (count($_POST['shopselection'] > 0))?"|".implode("|", $_POST['shopselection'])."|":"";
			$DB->inline("UPDATE animjob SET shopselection = '".$infosjob['shopselection']."' WHERE idanimjob = ".$_REQUEST['idanimjob']);

			include 'v-onglet-shopShow.php';
		break;
# =========================================================================================================
# = Onglet Annexes ====================================================================================== =
# =========================================================================================================
	# Les variables $pathannexe et $pathgaleries sont définies dans m-onglet.php
		case 'annexeshow':
			include 'v-onglet-annexe.php';
		break;
	### Files
		case 'annexeupfile':
			$dbfilename = 'anim-'.$_REQUEST['idanimjob']."-".strtolower(basename($_FILES['userfile']['name']));
			if(move_uploaded_file($_FILES['userfile']['tmp_name'],$pathannexe.$dbfilename)){
				echo "Le fichier '".$dbfilename."' a bien été uploadé";
			} else {
				echo "<div class=\"error\">Le fichier '".$dbfilename."' n'a pas pu être uploadé</div>";
			}
			include 'v-onglet-annexe.php';
		break;
		case 'annexedelfile':
			if (!empty($_POST['delfile'])) {
				if(!unlink($pathannexe.$_POST['delfile'])) die ("this file cannot be delete");
			}
			include 'v-onglet-annexe.php';
		break;
	### Photos
		case 'annexeupphoto':
			## Nom du fichier
			if ($_POST['mission'] == 'none') $_POST['mission'] = 'JOB';
			$lets = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
			$destfolder = $pathgaleries.$_REQUEST['idanimjob'];
			if (!is_dir($destfolder)) mkdir ($destfolder, 0777); 	## check si folder existe, sinon le cree

			$files1 = scandir($destfolder);
			foreach ($files1 as $name) {
				if (strchr($name, $_POST['mission'])) $lets = str_replace(substr($name, -5, 1), "", $lets);
			}

			$dbfilename = $_POST['mission'].'_'.$lets{0}.'.jpg';

			if(move_uploaded_file($_FILES['photofile']['tmp_name'],$destfolder.'/'.$dbfilename)){
				chmod ($destfolder.'/'.$dbfilename, 0777);

				# create thumb
				$src_img = imagecreatefromjpeg($destfolder.'/'.$dbfilename);
				$origw=imagesx($src_img);
				$origh=imagesy($src_img);

				if ($origw > $origh) {
					$new_w = 100;
					$diff=$origw/$new_w;
					$new_h=$origh/$diff;
				} else {
					$new_h = 100;
					$diff=$origh/$new_h;
					$new_w=$origw/$diff;
				}

				$dst_img = imagecreatetruecolor($new_w,$new_h);
				imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img));

				imagejpeg($dst_img, $destfolder.'/t'.$dbfilename, 70);

				echo "La photo '".$dbfilename."' a bien été uploadée";
			} else {
				echo "<div class=\"error\">La photo '".$destfolder."/".$dbfilename."' n'a pas pu être uploadé</div>";
			}
			include 'v-onglet-annexe.php';
		break;
		case 'annexedelphoto':
			if(!unlink($pathgaleries.$_REQUEST['idanimjob'].'/'.$_POST['delfile'])) die ("this file cannot be delete");
			if(!unlink($pathgaleries.$_REQUEST['idanimjob'].'/t'.$_POST['delfile'])) die ("this file cannot be delete");
			include 'v-onglet-annexe.php';
		break;
# =========================================================================================================
# = Onglet Annexes ====================================================================================== =
# =========================================================================================================
	# Selection des Missions à modifier	
		case 'updateselect':
			$PhraseBas = 'Affichage du listing des Missions pour Update';
			include 'v-onglet-updateselect.php';
		break;
	# Liste des missions à modifier
		case "updatelist":
			if (count($_POST['idtoupdate']) > 0) {
				$PhraseBas = 'Grille de modifications pour Update';
				include 'v-onglet-updatelist.php';
			} else {
				include 'v-onglet-updateselect.php';
			}
		break;
	# Modification des missions
		case "updatemodif":
			if(in_array('datem', $_POST['fieldtoupdate'])) $_POST['datem'] = fdatebk($_POST['datem']);

			if(in_array('hin1', $_POST['fieldtoupdate']))  $_POST['hin1'] = ftimebk($_POST['hin1']);
			if(in_array('hout1', $_POST['fieldtoupdate'])) $_POST['hout1'] = ftimebk($_POST['hout1']);
			if(in_array('hin2', $_POST['fieldtoupdate']))  $_POST['hin2'] = ftimebk($_POST['hin2']);
			if(in_array('hout2', $_POST['fieldtoupdate'])) $_POST['hout2'] = ftimebk($_POST['hout2']);
			if(in_array('kmauto', $_POST['fieldtoupdate'])) {
				if ($_POST['kmauto'] == 'Y') {
					$_POST['fieldtoupdate'][] = 'kmpaye';
					$_POST['fieldtoupdate'][] = 'kmfacture';
					$_POST['kmpaye'] = 0;
					$_POST['kmfacture'] = 0;
				} else {
					$_POST['kmauto'] = 'N';
				}
			}

			foreach($_POST['fieldtoupdate'] as $fieldtoupdate) {
				if (!empty($fieldtoupdatex)) $fieldtoupdatex .= ", ";
				$fieldtoupdatex .= $fieldtoupdate." = '".$_POST[$fieldtoupdate]."'";
				if ($fieldtoupdate == 'datem') $fieldtoupdatex .= " , weekm = '".date('W', strtotime($_POST['datem']))."' , yearm = '".date('Y', strtotime($_POST['datem']))."'";
			}

			if(!empty($fieldtoupdatex)) $DB->inline("UPDATE `animation` SET ".$fieldtoupdatex." WHERE `idanimation` IN (".$_POST['updateids'].")");
			include 'v-onglet-updateselect.php';
		break;

# =========================================================================================================
# = Onglet Missions ====================================================================================== =
# =========================================================================================================
	# Listing des missions du job ######################
		case "missionshow":
		default:
			$PhraseBas = 'Detail des Anim';
			include 'mission-listing.php';
		break;
}

# Pied de Page
include NIVO."includes/ifpied.php" ;
?>