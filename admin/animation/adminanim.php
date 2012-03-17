<?php
# Entete de page
define('NIVO', '../../');
$Titre = 'ADMIN Anim';
$Style = 'admin';

# Classes utilisees
include NIVO.'classes/anim.php';
include NIVO.'classes/hh.php';

# Entete
include NIVO.'includes/entete.php';

# Carousel des fonctions
$onemonthago  = date ("Y-m-01", mktime (0,0,0,date("m"),date("d") + 1,date("Y")));

# week
$datex = weekdate(date('W'));
$oneweekago = $datex['lun'];

switch ($_GET['act']) {

###################################################################################################################################################################################
# 1 ########## Facturation #############################################																										###
#####   																																										###
		case "facture": 
			if (($_POST['modstate'] == 'Valider') and ($_POST['state'] > 0)) {
				$DB->inline("UPDATE animation SET facturation = '".$_POST['state']."' WHERE idanimation IN(".implode(", ", $_POST['ids']).");");
			}
			## Affichage de la liste des missions a facturer
			$mode = 'ADMIN';
			include "factoring.php" ;
			$PhraseBas = 'FACTORING des ANIM';
		break;

############## Listing des PRE-FACTURATION #############################################
	case "prefac": 
		if ($_POST['valider'] == 'Modifier') {
			foreach ($_POST as $key => $value) {
				if (substr($key, 0, 4) == 'ids-') {
					$ids = explode('-', substr($key, 4));
					$sql = "UPDATE animation SET facturation = '".$value."' WHERE idanimation IN(".implode(", ", $ids).");";
					$maj = new db();
					$maj->inline($sql);
				}
			}	
		}

		include "prefactoring.php" ;
		$PhraseBas = 'PRE-FACTORING des ANIM';
	break;

#####   																																										###
############## Facturation #############################################																										###
###################################################################################################################################################################################

############## SWITCH des PRE-FACTURATION  #############################################
	case "prefac1": 
		foreach($_POST as $key => $valeur) {
			if (substr($key, 0, 10) == 'factswitch') {
				$dd = explode('-', $key);
				$idanim = $dd[1];
				$_POST['facturation'] = $valeur;
				$modif = new db('animation', 'idanimation');
				$modif->MODIFIE($idanim, array('facturation' ));
			}
		}

		include "prefactoring.php" ;
		$PhraseBas = 'PRE-FACTORING des ANIM';
	break;

############## Menu pour print facture - etat = 6 #############################################
	case "facready": 
		include "facture_inc.php" ;
		$PhraseBas = 'PRINT de PRE-FACTORING des ANIM';
	break;

############## Listing des PURGE FL #############################################
	case "purgefl1": 
		include "purgefl1.php" ;
		$PhraseBas = 'PURGE FL des ANIM';
	break;

############## SWITCH des PURGE FL  #############################################
	case "purgefl2": 
		foreach($_POST as $key => $valeur) {
			if (substr($key, 0, 10) == 'factswitch') {
				$dd = explode('-', $key);
				$idanim = $dd[1];
				$_POST['facturation'] = $valeur;
				$facturation = $valeur;
				$modif = new db('animation', 'idanimation');
				$modif->MODIFIE($idanim, array('facturation' ));
			}
		}	
		include "purgefl1.php" ;
		$PhraseBas = 'PURGE FL des ANIM';
	break;

############## Listing des PURGE OUT #############################################
	case "purgeout1": 
		include "purgeout1.php" ;
		$PhraseBas = 'PURGE OUT des ANIM';
	break;

############## SWITCH des PURGE OUT  #############################################
	case "purgeout2": 
		foreach($_POST as $key => $valeur) {
			if (substr($key, 0, 10) == 'factswitch') {
				$dd = explode('-', $key);
				$idanim = $dd[1];
				$_POST['facturation'] = $valeur;
				$facturation = $valeur;
				$modif = new db('animation', 'idanimation');
				$modif->MODIFIE($idanim, array('facturation' ));
			} else {
				unset($facturation);
			}

				### etat = 26 ==> Duplication zoutanimation d'une Mission animation et Delete d'une Animation
				if ($facturation == '26') {
						$idanimation = $idanim;
						############## Recherche infos d'une Mission animation 
						$anim1 = new db();
						$anim1->inline("SELECT * FROM `animation` WHERE `idanimation` = $idanimation;");
						$infosduplic = mysql_fetch_array($anim1->result) ;
							$idagent=$infosduplic['idagent'];
							$idclient=$infosduplic['idclient'];
							$idcofficer=$infosduplic['idcofficer'];
							$idshop=$infosduplic['idshop'];
							$idpeople=$infosduplic['idpeople'];
							$reference=$infosduplic['reference'];
							$produit=addslashes($infosduplic['produit']);
							$boncommande=$infosduplic['boncommande'];
							$datem=$infosduplic['datem'];
							$hin1=$infosduplic['hin1'];
							$hout1=$infosduplic['hout1'];
							$hin2=$infosduplic['hin2'];
							$hout2=$infosduplic['hout2'];
							$ferie=$infosduplic['ferie'];
							$kmpaye=$infosduplic['kmpaye'];
							$kmfacture=$infosduplic['kmfacture'];
							$frais=$infosduplic['frais'];
							$fraisfacture=$infosduplic['fraisfacture'];
							$briefing=$infosduplic['briefing'];

							$noteanim=addslashes($infosduplic['noteanim']);
							$genre=$infosduplic['genre'];
							$datecontrat=$infosduplic['datecontrat'];
						$didold = $idanimation;
			
						############## Duplication zoutanimation d'une Mission animation 
						$detailanim = new db();
						$detailanim->inline("INSERT INTO `zoutanimation` (`idanimation` , `idagent` , `idclient` , `idcofficer` , `idshop` , `idpeople` , `type`, `reference`, `produit` , `boncommande` , `datem` , `hin1` , `hout1` , `hin2` , `hout2` , `ferie` , `kmpaye` , `kmfacture` , `frais` , `fraisfacture` , `briefing` , `noteanim` , `genre` , `datecontrat` ) VALUES ('$idanimation', '$idagent', '$idclient' , '$idcofficer' , '$idshop' , '$idpeople' , '$type', '$reference', '$produit' , '$boncommande' , '$datem' , '$hin1' , '$hout1' , '$hin2' , '$hout2' , '$ferie' , '$kmpaye' , '$kmfacture' , '$frais' , '$fraisfacture' , '$briefing' , '$noteanim' , '$genre' , '$datecontrat');");
			
						############## Duplication zoutanimproduit des animproduit d'une Mission animation 
						$prod1 = new db('animproduit', 'idanimproduit');
						$prod1->inline("SELECT * FROM `animproduit` WHERE `idanimation` = $didold;");
						while ($infosduplic = mysql_fetch_array($prod1->result)) { 
							$idanimproduit=$infosduplic['idanimproduit'];
							$types=$infosduplic['types'];
							$prix=$infosduplic['prix'];
							$produitin=$infosduplic['produitin'];
							$unite=$infosduplic['unite'];
							$produitend=$infosduplic['produitend'];
							$ventes=$infosduplic['ventes'];
							$produitno=$infosduplic['produitno'];
							$degustation=$infosduplic['degustation'];
							$promoin=$infosduplic['promoin'];
							$promoout=$infosduplic['promoout'];
							$promoend=$infosduplic['promoend'];
			
							############## Duplication d'un zoutanimproduit
								$prod2 = new db('zoutanimproduit', 'idanimproduit');
								$prod2->inline("INSERT INTO `zoutanimproduit` (`idanimproduit`, `idanimation`, `types` , `prix` , `produitin` , `unite` , `produitend` , `ventes` , `produitno` , `degustation` , `promoin` , `promoout` , `promoend` ) VALUES ('$idanimproduit', '$idanimation', '$types' , '$prix' , '$produitin' , '$unite' , '$produitend' , '$ventes' , '$produitno' , '$degustation' , '$promoin' , '$promoout' , '$promoend' );");
			
							############## Delete d'un zoutanimproduit

								$proddel = new db('animproduit', 'idanimproduit');
								$sqldel = "DELETE FROM `animproduit` WHERE `idanimproduit` = $idanimproduit;";
								$proddel->inline($sqldel);
						}
			
						############## Duplication zoutanimmateriel de l'animmateriel d'une Mission animation 
							$mat1 = new db('animmateriel', 'idanimmateriel');
							$mat1->inline("SELECT * FROM `animmateriel` WHERE `idanimation` = $idanimation");
							$matos = mysql_fetch_array($mat1->result) ; 
							$idanimmateriel = $matos['idanimmateriel'];
							$stand = $matos['stand'];
							$gobelet = $matos['gobelet'];
							$serviette = $matos['serviette'];
							$four = $matos['four'];
							$curedent = $matos['curedent'];
							$cuillere = $matos['cuillere'];
							$rechaud = $matos['rechaud'];
							$autre = $matos['autre'];
			
							############## Duplication d'un animmateriel
								$mat2 = new db('zoutanimmateriel', 'idanimmateriel');
								$mat2->inline("INSERT INTO `zoutanimmateriel` (`idanimmateriel`, `idanimation`, `stand`, `gobelet` , `serviette` , `four` , `curedent` , `cuillere` , `rechaud` , `autre` ) VALUES ('$idanimmateriel', '$idanimation', '$stand', '$gobelet' , '$serviette' , '$four' , '$curedent' , '$cuillere' , '$rechaud' , '$autre' );");
			
							############## Delete d'un animmateriel
								$matdel = new db('animmateriel', 'idanimmateriel');
								$sqldel = "DELETE FROM `animmateriel` WHERE `idanimmateriel` = $idanimmateriel;";
								$matdel->inline($sqldel);
			
					############## Delete d'une Animation
						$animdel = new db('animation', 'idanimation');
						$sqldel = "DELETE FROM `animation` WHERE `idanimation` = $idanimation;";
						$animdel->inline($sqldel);
				}
				#/## etat = 26 ==> Duplication zoutanimation d'une Mission animation et Delete d'une Animation
		}
		include "purgeout1.php" ;
		$PhraseBas = 'PURGE OUT des ANIM';
	break;

############## SEARCH des FACTURATION #############################################
	case "faclistingsearch": 
	default: 
		$_SESSION['view'] = 'f' ; 
		include "faclistingsearch.php" ;
		$PhraseBas = 'FACTORING des ANIM';
	break;

######
}

?>
<div id="topboutonsadmin">
<table border="0" cellspacing="1" cellpadding="0">
	<tr>
<?php 
if ($_SESSION['jobquid'] != "") { ?>
		<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=facture&action=skip"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Retour Liste</a></td>
<?php } ?>
		<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=faclistingsearch"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Recherche</a></td>
		<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=prefac"><img src="<?php echo STATIK ?>illus/listepresence.gif" alt="search" width="32" height="32" border="0"><br>Pr&eacute;-factures</a></td>
		<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=facready"><img src="<?php echo STATIK ?>illus/rapportmail.gif" alt="search" width="32" height="32" border="0"><br>Factures</a></td>
		<td class="on" width="32"><br></td>
		<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=purgeout1"><img src="<?php echo STATIK ?>illus/trash.gif" alt="out" width="32" height="32" border="0"><br>OUT</a></td>
		<td class="on"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=purgefl1"><img src="<?php echo STATIK ?>illus/girl.png" alt="out" width="32" height="32" border="0"><br>F L</a></td>
	<?php if (($_SESSION['roger'] == 'devel') or ($_SESSION['roger'] == 'admin')) { ?>
	<?php } ?>
	</tr>
</table> 
</div>
<?php
# Pied de Page
include NIVO."includes/pied.php" ;

?>