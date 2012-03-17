<?php
$classe = "planning" ;

###### POUR IFRAME
$listing = new db();

if ($down == 'down') {
### dans un Job
		$tableau = 'miniinfozone';

		## Sort Order
		if (!empty($_GET['sort'])) {
			$sort = $_GET['sort'];
		} else {
			$sort = $_SESSION['animmissionsort'];
		}
		if (empty($sort)) { $sort = 'an.datem'; } ## par défaut

		## 
		if (!empty($_POST['listtype'])) { $_SESSION['animjobmissionlisttype'] = $_POST['listtype']; }
		if (!empty($_GET['listtype'])) { $_SESSION['animjobmissionlisttype'] = $_GET['listtype']; }
		if ($_SESSION['animjobmissionlisttype'] == '3') { $sort = 'p.pnom, '.$sort; }

		# VARIABLE $_SESSION['prenom'] pour recherche missing / to do
		if ($_GET['listing'] == 'missing') {
			$tableau = 'centerzonelarge';

			$quid = "WHERE (an.idpeople IS NULL OR an.idpeople = '')";
			$quod = "prenom agent = ".$_SESSION['prenom'];
			$sort = 'an.datem';
		} else {
			$quid = "WHERE an.idanimjob = ".$idanimjob;
		}

		### affichage de toutes missions ou seulement en cours
			if ($_GET['viewall'] != 'oui') {
				$quid .= ' AND (an.facturation < 5 OR an.facturation IS NULL) ';
			}
		#/## affichage de toutes missions ou seulement en cours

		### Session et variable IFRAME
		$_SESSION['animmissionquid'] = $quid;
		$_SESSION['animmissionsort'] = $sort;

		$listtype = $_SESSION['animjobmissionlisttype'];
} else {
### Pleine page

		$tableau = 'centerzonelarge';

		## Sort
		if (!empty($_GET['sort'])) { $_SESSION['animmissionsort'] = $_GET['sort']; }
		if (empty($_SESSION['animmissionsort'])) { $_SESSION['animmissionsort'] = 'an.datem'; }

		## ListingType
		if (!empty($_POST['listtype'])) { $_SESSION['animmissionlisttype'] = $_POST['listtype']; }
		if (!empty($_GET['listtype']))  { $_SESSION['animmissionlisttype'] = $_GET['listtype']; }
		if ($_SESSION['animmissionlisttype'] == '3') { $_SESSION['animmissionsort'] = 'p.pnom, '.$_SESSION['animmissionsort']; }
		
		switch ($_GET['action']) {
			case "skip":

			break;
			### Première étape : Afficher la liste des Anim a la recherche SANS SORT
			default:
				$quid = '';
				$quod = '';
				$sort .= 'an.datem';

				$_SESSION['animmissionlisttype'] = '4';

				# Mes jobs
				if ($_GET['listing'] == 'direct') {
					$quid .= "an.idagent = '".$_SESSION['idagent']."'";
				# Todo
				} elseif ($_GET['listing'] == 'missing') {
					$quid .= " (an.idpeople IS NULL OR an.idpeople = '') ";
					$quod .= " Todo";
				} else {
					if (($_POST['weekm']  > 0) and empty($_POST['yearm'])) $_POST['yearm'] = date("Y");

					if (($_POST['yearm'] > 1) and ($_POST['yearm'] < 1900)) {
						$rdate = fdatebk("1/1/".$_POST['yearm']);
						$_POST['yearm'] = substr($rdate, 0, 4);
					}

					$searchfields = array (
						'a.idagent' 		=> 'idagent',
						'an.idanimation'	=> 'idanimation',
						'an.idanimjob' 		=> 'idanimjob',
						'an.genre' 			=> 'genre',
						'an.reference' 		=> 'reference',
						'p.pnom' 			=> 'pnom',
						'p.pprenom' 		=> 'pprenom',
						'p.codepeople' 		=> 'codepeople',
						'p.lbureau' 		=> 'lbureau',
						'c.codeclient' 		=> 'codeclient',
						'c.societe' 		=> 'csociete',
						's.codeshop' 		=> 'codeshop',
						's.societe' 		=> 'ssociete',
						's.ville' 			=> 'sville',
						'an.produit' 		=> 'produit',
						'j.boncommande' 	=> 'boncommande',
						'an.datem' 			=> 'date',
						'an.weekm' 			=> 'weekm',
						'YEAR(an.datem)' 	=> 'yearm');
					
					$quid = $listing->MAKEsearch($searchfields);
	
					if (!empty($_POST['todo'])) {
						if (!empty($quid)) { $quid .= " AND "; $quod .= " ET "; }
						$quid .= " (an.idpeople IS NULL OR an.idpeople = '') ";
						$quod .= ' + to do';
					}
					
					if ($_POST['ccheck'] == 'Y') {
						if (!empty($quid)) { $quid .= " AND "; $quod .= " ET "; }
						$quid .= " an.ccheck = 'Y' ";
						$quod .= " + contrats encod&eacute;s";
					}
					
					if ($_POST['ccheck'] == 'N') {
						if (!empty($quid)) { $quid .= " AND "; $quod .= " ET "; }
						$quid .= " an.ccheck != 'Y' ";
						$quod .= " + contrats a encoder";
					}
					
				}
	
			# ANIM etat Facturation
				if (!empty($quid)) { $quid .= " AND ";}
				$quid .= "(an.facturation <= 1 OR an.facturation = '' OR an.facturation IS NULL)";

				$quid= 'WHERE '.$quid;
				
				

				$_SESSION['animmissionquid'] = $quid;
				$_SESSION['animmissionsort'] = $sort;
		}
		
		$listtype = $_SESSION['animmissionlisttype'];

}
#/##### POUR PLEINE PAGE

$listing->inline("SELECT
	an.idanimation, an.datem, an.weekm, an.yearm, an.reference, an.hin1, an.hout1, an.hin2, an.hout2, an.ccheck,an.tchkdate,
	an.kmpaye, an.kmfacture, an.fmode, an.produit,
	an.facturation, an.peoplenote,
	an.ferie, an.idanimjob, an.livraisonpaye, an.livraisonfacture, an.kmauto,
	j.genre, j.boncommande, j.statutarchive,
	a.prenom, a.idagent,
	c.codeclient, c.societe AS clsociete, c.idclient, c.tel, c.fax,
	co.idcofficer, co.qualite, co.onom, co.oprenom, co.fax AS cofax,
	s.idshop, s.codeshop, s.societe AS ssociete, s.ville AS sville,
	p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.gsm, p.codepeople,
	ma.idanimmateriel, ma.stand, ma.gobelet, ma.serviette, ma.four, ma.curedent, ma.cuillere, ma.rechaud, ma.autre,
	nf.idnfrais, nf.montantpaye, nf.montantfacture

	FROM animation an
	LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
	LEFT JOIN agent a ON j.idagent = a.idagent
	LEFT JOIN client c ON j.idclient = c.idclient
	LEFT JOIN cofficer co ON j.idcofficer = co.idcofficer
	LEFT JOIN people p ON an.idpeople = p.idpeople
	LEFT JOIN shop s ON an.idshop = s.idshop
	LEFT JOIN animmateriel ma ON an.idanimation = ma.idanimation
	LEFT JOIN notefrais nf ON an.idanimation = nf.idmission AND nf.secteur = 'AN'
	".$_SESSION['animmissionquid']."
	ORDER BY ".$_SESSION['animmissionsort'].""); # removed LIMIT 300 pour avoir tout . (attention c'est lent)

$FoundCount = mysql_num_rows($listing->result);

###############################################################################################################################
### List menus ###############################################################################################################
#############################################################################################################################
function listmenus($down, $idanimjob) {
	$cURL = $_SERVER['PHP_SELF'].'?act=listing&down='.$down.'&action=skip&idanimjob='.$idanimjob.'&';
?>

<div style="color: #000000; text-align: center;">
<?php if ($down == 'down') { ?>
	<a href="<?php echo $cURL.'listtype=creation&viewall='.$_GET['viewall'];?>">Cr&eacute;ation</a> -
<?php } ?>
	<a href="<?php echo $cURL.'listtype=4&viewall='.$_GET['viewall'];?>">Listing</a> -
	<a href="<?php echo $cURL.'listtype=1&sort=p.pnom, p.pprenom&viewall='.$_GET['viewall'];?>">Info &eacute;ditable</a> -
	<!-- <a href="<?php echo $cURL.'listtype=3&sort=p.pnom, p.pprenom&viewall='.$_GET['viewall'];?>">D&eacute;compte</a> - -->
	<a href="<?php echo $cURL.'listtype=2&sort=p.pnom, p.pprenom&viewall='.$_GET['viewall'];?>">Mat&eacute;riel</a> -
	<a href="<?php echo $cURL.'listtype=9&sort=p.pnom, p.pprenom&viewall='.$_GET['viewall'];?>">Produits</a>
<?php if ($down == 'down') { ?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a style="color: #039;" href="<?php echo $cURL.'listtype='.$_GET['listtype'].'&sort='.$_GET['sort'].'&viewall=oui';?>">Archive</a> -
	<a style="color: #039;" href="<?php echo $cURL.'listtype='.$_GET['listtype'].'&sort='.$_GET['sort'].'&viewall=non';?>">En cours</a>
<?php } ?>
</div>	
<?php }
###############################################################################################################################
### Affichage  ###############################################################################################################
#############################################################################################################################
?>
<div id="<?php echo $tableau; ?>">
	<?php echo $_SESSION['animmissionsort']; ?> - <?php echo '('.$FoundCount.') &nbsp; '; ?>
<?php listmenus($down, $idanimjob) ?>
<br>
<?php

switch($listtype) {
	## Creation des missions
	case"creation":
		include NIVO.'animation2/mission-listing/creation_inc.php';
	break;
	## Infos Editables
	case"1":
		include NIVO.'animation2/mission-listing/infoeditable_inc.php';
	break;
	## Matériel
	case"2":
		include NIVO.'animation2/mission-listing/materiel_inc.php';
	break;
	## Décompte
	#case"3":
	#	include NIVO.'animation2/mission-listing/decompte_inc.php';
	#break;
	## Creation des missions
	case"9":
		include NIVO.'animation2/mission-listing/produit_inc.php';
	break;
	## Listing par défaut (4)
	case"4":
	default:
		include NIVO.'animation2/mission-listing/printlist_inc.php';
}

?>
<br>
<?php listmenus($down, $idanimjob) ?>
<br>
</div>
