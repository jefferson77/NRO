<?php
# Entete de page
define('NIVO', '../../'); 
$Titre = 'VIP ADMIN';
$Style = 'admin';

# Entete
include NIVO."includes/entete.php" ;
# Classes utilisées
include NIVO.'classes/vip.php';

# Carousel des fonctions
switch ($_GET['act']) {
############## SWITCH des FACTURATION a Veérifier  #############################################
	case "facture": 
		if (($_POST['modstate'] == 'Valider') and ($_POST['state'] > 0) and ($_POST['idvipjob'] > 0)) {
			## Update des missions du Job
			$DB->inline("UPDATE vipmission SET metat = ".$_POST['state']." WHERE idvipjob = ".$_POST['idvipjob']);

			## Update du job lui meme
			$DB->inline("UPDATE vipjob SET etat = ".$_POST['state']." WHERE idvipjob = ".$_POST['idvipjob']);
		}

		$mode = 'ADMIN';

		include "facturation.php" ;
		$PhraseBas = 'FACTORING des VIP';
	break;
############## SWITCH des PRE FACTURATION a Veérifier  #############################################
	case "prefactoring": 
		if ($_GET['action'] == 'job') {
			foreach($_POST as $key => $valeur) {
				if ((substr($key, 0, 3) == 'idj') and ($valeur > 0)) {
					$dd = explode('-', $key);
					$idvip = $dd[1];
					
					$DB->inline("UPDATE vipmission SET metat = '".$valeur."' WHERE idvipjob = ".$idvip);
					$DB->inline("UPDATE vipjob SET etat = '".$valeur."' WHERE idvipjob = ".$idvip);
				}
			}
		}

		include "prefactoring.php" ;
		$PhraseBas = 'FACTORING des VIP';

	break;
############## IMPRESSION FACTURES #############################################
	case "factoring": 
			include "facture_inc.php" ;
			$PhraseBas = 'IMPRESSION facturation des VIP';
	break;
}
?>
<div id="topboutonsadmin">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="on"><a href="?act=facture"><img src="<?php echo STATIK ?>illus/planning.gif" alt="planning" width="32" height="32" border="0"><br>Check list</a></td>
			<td class="on"><a href="?act=prefactoring"><img src="<?php echo STATIK ?>illus/listepresence.gif" alt="planning" width="32" height="32" border="0"><br>Pr&eacute; Factures</a></td>
			<td class="on"><a href="?act=factoring"><img src="<?php echo STATIK ?>illus/rapportmail.gif" alt="planning" width="32" height="32" border="0"><br>Factures</a></td>
		</tr>
	</table> 
</div>
<?php include NIVO."includes/pied.php"; ?>