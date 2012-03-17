<?php
define('NIVO', '../../../');

# Classes utilisées
include NIVO.'classes/facture.php';
include NIVO."classes/anim.php";

# Entete de page
$Style = 'vip';

include NIVO."includes/ifentete.php" ;
include NIVO."print/anim/facture/facture_inc.php";
require_once(NIVO."classes/document.php");
################### Début Création de la DB tempfactureanim########################
$detail0 = new db();
$detail0->inline("TRUNCATE TABLE `tempfactureanim`;");
$detail0->inline("UPDATE `animation` SET `facnumtemp` = 0 WHERE `facnumtemp` > 0"); ## vide les anciens nums de factemp dans les missions
$detail0->inline("UPDATE `animjob` SET `facnumtemp` = 0 WHERE `facnumtemp` > 0"); ## vide les anciens nums de factemp dans les jobs

$sql = "SELECT

YEAR(an.datem) as annee, an.weekm, an.datem, an.idanimation,
j.idclient, j.boncommande, j.idcofficer,
c.facturation, c.factureofficer, c.societe,
a.prenom,
co.langue

FROM animation an
LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
LEFT JOIN agent a ON j.idagent = a.idagent
LEFT JOIN client c ON j.idclient = c.idclient
LEFT JOIN cofficer co ON j.idcofficer = co.idcofficer

WHERE an.facturation = 5";

$infofacs = $DB->getArray($sql);

foreach ($infofacs as $row) {
	switch ($row['facturation'].$row['factureofficer']) {
		case"12":
			$lakey = 'SB//'.$row['annee'].'//'.$row['weekm'].'//'.$row['idclient'].'//'.$row['boncommande'];
			$tabl[$lakey]['ids'] .= '-'.$row['idanimation'];
			$tabl[$lakey]['reference'] = 'PO '.$row['boncommande'].' - Semaine '.$row['weekm'].' '.$row['annee'];
		break;
		case"11":
			$lakey = 'SO//'.$row['annee'].'//'.$row['weekm'].'//'.$row['idclient'].'//'.$row['idcofficer'];
			$tabl[$lakey]['ids'] .= '-'.$row['idanimation'];
			$tabl[$lakey]['reference'] = 'Semaine '.$row['weekm'].' '.$row['annee'];
		break;
		case"32":
			$lakey = 'MB//'.$row['annee'].'//'.date("m", strtotime($row['datem'])).'//'.$row['idclient'].'//'.$row['boncommande'];
			$tabl[$lakey]['ids'] .= '-'.$row['idanimation'];
			$tabl[$lakey]['reference'] = 'PO '.$row['boncommande'].'Mois '.date("m", strtotime($row['datem'])).' '.$row['annee'];
		break;
		case"31":
			$lakey = 'MO//'.$row['annee'].'//'.date("m", strtotime($row['datem'])).'//'.$row['idclient'].'//'.$row['idcofficer'];
			$tabl[$lakey]['ids'] .= '-'.$row['idanimation'];
			$tabl[$lakey]['reference'] =  'Mois '.date("m", strtotime($row['datem'])).' '.$row['annee'];
		break;
		default:
			$lakey = 'AU//'.$row['annee'].'//'.$row['weekm'].'//'.$row['idclient'].'//'.$row['idcofficer'];
			$tabl[$lakey]['ids'] .= '-'.$row['idanimation'];
			$tabl[$lakey]['reference'] = 'Semaine '.$row['weekm'].' '.$row['annee'];
	}
		$tabl[$lakey]['client'] = $row['societe'].'('.$row['idclient'].')';
		$tabl[$lakey]['planner'] = $row['prenom'];
		$tabl[$lakey]['langue'][$row['langue']]++ ;
		$tabl[$lakey]['idcofficer'][$row['idcofficer']]++ ;
}

foreach ($tabl as $key => $value) {

	$infs = explode("//", $key);

	asort($value['langue']);
	asort($value['idcofficer']);

	$DB->inline("INSERT INTO `tempfactureanim` (`secteur`, `datefac`, `idclient`, `langue`, `idcofficer`) VALUES ('2', NOW(), '".$infs[3]."', '".key($value['langue'])."', '".key($value['idcofficer'])."');");
	$did = $DB->addid;

	$ids = explode("-", substr($value['ids'], 1));

	$DB->inline("UPDATE `animation` SET `facnumtemp` = '".$did."' WHERE `idanimation` IN(".implode(", ", $ids).");");
	$DB->inline("UPDATE `animjob` SET `facnumtemp` = '".$did."' WHERE idanimjob IN (SELECT DISTINCT(idanimjob) from animation WHERE `idanimation` IN(".implode(", ", $ids)."));");
	$infos = $value;
	$prefacs[] = print_fac_anim($did,'entete','','PREFAC');
}

$book = reliure($prefacs,"AFPr");

$filepath = pathinfo($book['path']);
$filename = $filepath['filename'];
?>
<div align="center">
<br><br><br><a href="<?php echo NIVO.'../document/temp/AFPr/'.$filename.'.pdf'; ?>"><img border="0" src="<?php echo NIVO."illus/minipdf.gif"; ?>" title="Afficher le PDF"> Les pr&eacute;factures</a><a href="javascript:window.close();"><b>&gt; Fermer &lt;</b></a>
</div>

<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
