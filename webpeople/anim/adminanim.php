<?php
define('NIVO', '../../');
$niveauweb = '../';

# Classes utilisées
include NIVO."classes/anim.php" ;

# Entete de page
include $niveauweb."includes/entete.php" ;

### langue
include $niveauweb.'var'.$_SESSION['lang'].'.php';
$textehaut = $detail_00.' : '.$detail_01;


$Titre = $titre_00;

# Carousel des fonctions
switch ($_GET['act']) {
############## Disponibilités  #########################################
		case "sale1":
			switch ($_GET['udpate']) {
				case "animation":
					$DB->MAJ('animation');
				break;

				case "produit":
					$DB->MAJ('animproduit');
				break;

				case "cloturer":
					$DB->inline("UPDATE animation SET peopleonline = 1 WHERE idanimation = ".$_POST['idanimation']);
				break;
			}
			$textehaut = $dispo_00;
			$include = "sales.php" ;
		break;
############## Menu  #########################################
		default:
			$textehaut = $menu_00;
			$include = "sales.php" ;
}

include $niveauweb."includes/up.php" ;

include $include ;

include $niveauweb."includes/ppied.php" ;
?>