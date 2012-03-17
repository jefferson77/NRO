<?php
define('NIVO', '../../../');

# Classes utilisées
include NIVO.'classes/vip.php';
include NIVO.'classes/facture.php';
include NIVO.'classes/document.php';

# Entete de page
$Style = 'vip';
include NIVO."includes/ifentete.php" ;
include NIVO."print/vip/facture/facture_inc.php" ;

################### Début Création de la DB tempfacturevip########################
$detail0 = new db();
$detail0->inline("TRUNCATE TABLE `tempfacturevip`;"); ## Clear table tempfacturevip
$detail0->inline("UPDATE `vipjob` SET `facnumtemp` = 0 WHERE `facnumtemp` > 0"); ## vide les anciens nums de factemp dans les jobs
$detail0->inline("UPDATE `vipmission` SET `facnumtemp` = 0 WHERE `facnumtemp` > 0"); ## vide les anciens nums de factemp dans les jobs

$detail = $DB->getArray("SELECT 
		j.idvipjob, j.reference, j.etat, j.idclient, j.idcofficer,
		c.societe, 
		a.prenom 
	FROM vipjob j
		LEFT JOIN client c ON j.idclient = c.idclient
		LEFT JOIN agent a ON j.idagent = a.idagent
	WHERE j.etat = '15'
	ORDER BY j.idvipjob");

foreach ($detail as $infos)
{

	$idvipjob = $infos['idvipjob'];

	### Créer la Facture Temporaire
	$DB->inline("INSERT INTO `tempfacturevip` (`secteur` , `datefac` , `idclient` , `idcofficer` , `agentmodif` , `datemodif`) VALUES
			('1', NOW(), '".$infos['idclient']."', '".$infos['idcofficer']."', '".$_SESSION['idagent']."', NOW());");
	$did = $DB->addid;
	$DB->inline("UPDATE `vipjob` SET `facnumtemp` = '$did' WHERE idvipjob = '$idvipjob'");
	$DB->inline("UPDATE `vipmission` SET `facnumtemp` = '$did' WHERE `idvipjob` = '$idvipjob'");
	
	$prefacs[] = print_fac_vip($did,'entete','','prefac');
}

$book = reliure($prefacs,"VFPr");

$filepath = pathinfo($book['path']);
$filename = $filepath['filename'];

?>
<div align="center">
<a href="<?php echo NIVO.'../document/temp/VFPr/'.$filename.'.pdf'; ?>"><img border="0" src="<?php echo NIVO."illus/minipdf.gif"; ?>" title="Afficher le PDF"> Les pr&eacute;factures</a>
<br><br><br><a href="javascript:window.close();"><b>&gt; Fermer &lt;</b></a>
</div>

<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
