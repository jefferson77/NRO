<?php
define('NIVO','../../../');

# Classes utilisées
include NIVO.'classes/vip.php';
include NIVO.'classes/facture.php';
include NIVO.'print/vip/facture/facture_inc.php';
include NIVO.'print/dispatch/dispatch_functions.php';

# Entete de page
include NIVO."includes/ifentete.php" ;

$jobafac = $DB->getArray("SELECT j.idvipjob, j.idclient, o.langue, o.docpref, o.idcofficer, o.onom, o.oprenom, c.societe
	FROM vipjob j
		LEFT JOIN cofficer o ON j.idcofficer  = o.idcofficer 
		LEFT JOIN client c ON j.idclient = c.idclient
	WHERE j.etat = 16
	ORDER BY j.idvipjob");

$count = 0;
if(!empty($jobafac))
{
	foreach ($jobafac as $infos)
	{
		if (empty($infos['langue'])) {
			echo '<br> Langue pas définie pour le client : '.$infos['idclient'].' facture du job '.$infos['idvipjob'].' non traitée';
		} else {
			## cree une facture par job
			$DB->inline("INSERT INTO facture (secteur, datefac, idclient, langue) VALUES ('1', '".facdate()."', '".$infos['idclient']."', '".$infos['langue']."')");
			$newfac = $DB->addid;
			$DB->inline("UPDATE vipjob SET facnum = ".$newfac.", etat = 18 WHERE idvipjob = ".$infos['idvipjob']);
			$DB->inline("UPDATE vipmission SET facnum = ".$newfac.", metat = 18 WHERE idvipjob = ".$infos['idvipjob']);
			$printed_fac[$infos['idcofficer']][0] = print_fac_vip($newfac, 'entete','', '');
			
		}
	}	
	
    $DB->inline ("UPDATE `facture` SET `etat` = 2 WHERE `etat` = 1 AND `secteur` = 1;");
    
    generateSendTable($printed_fac, "cofficer","temp/facture", "facture","Facture");
		
} 
else 
{ 
	echo "Pas de jobs à facturer"; 
} ?>
<div align="center">
<br><br><br><a href="javascript:window.close();"><b>&gt; Fermer &lt;</b></a>
</div>
<?php include NIVO."includes/ifpied.php" ?>