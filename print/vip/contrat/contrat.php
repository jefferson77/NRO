<?php
define('NIVO', '../../../');

$Style = 'vip';

include NIVO."includes/ifentete.php" ;
# Classes utilisées
include NIVO."print/commun/bdc-supplier.php";
include_once NIVO.'print/vip/contrat/contrat_inc.php';
include_once NIVO.'print/commun/notefrais.php';
include_once NIVO.'print/dispatch/dispatch_functions.php';

################### Get infos du job ########################
switch (@$_REQUEST['split']) {
	case 'client':
		$split   = 'cofficer';
		$splitid = 'idcofficer';
		break;

	case 'people':
	default:
		$split   = 'people';
		$splitid = 'idpeople';
		break;
}

$array = $DB->getArray("SELECT
		m.idvip, m.idpeople,
		j.idcofficer,
		nf.idnfrais,
		p.pnom, p.pprenom, p.idsupplier, p.docpref
	FROM vipmission m
	LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob
	LEFT JOIN notefrais nf ON nf.secteur = 'VI' AND nf.idmission = m.idvip
	LEFT JOIN people p ON m.idpeople = p.idpeople
	WHERE m.idvipjob = ".$_GET['idvipjob']." ORDER BY p.pnom");

#########################################################################################################
### 1ère passe : Génère les contrats individuels et des notes de frais
foreach($array as $row) {
	if($row['idsupplier']==0) {
		$thiscontrat = print_contratvip($row['idvip']);
	} else {
		$thiscontrat = print_bdc($row['idvip'], 'VI');
	}
	if (!empty($thiscontrat)) {
		$parpeople[$row[$splitid]][] = $thiscontrat;

		if (!empty($row['idnfrais'])) {
			$thisnotefrais = print_notefrais($row['idnfrais']);
			$parpeople[$row[$splitid]][] = $thisnotefrais;
		}
	}
}

#########################################################################################################
### 2ème passe : Assemblage des contrats et notes de frais
# Lien vers le PDF
?>
<link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8">
<div align="center">
	<a href="?idvipjob=<?php echo $_REQUEST['idvipjob'] ?>&amp;split=people">Par People</a> -
	<a href="?idvipjob=<?php echo $_REQUEST['idvipjob'] ?>&amp;split=client">Par Client</a>
</div>
<?php
	generateSendTable($parpeople, $split,"temp/VCpe", "VCpe", "Contrat");
?>

<br><br><a align="center" href="javascript:window.close();"><b>&gt; Fermer &lt;</b></a><br>
<?php include NIVO."includes/ifpied.php"; ?>