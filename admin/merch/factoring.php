<link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8">
<?php
## Switch mode USER/ADMIN
switch ($mode) {
	case"USER":
		$etatfac = '1, 2';
		$page2 = NIVO.'admin/merch/factoring1.php';
		/*
			TODO  : passer a un système général de délégation des missions (définir un/des agents user qui peuvent manager les missions d'un admin )
		*/
		if ($_SESSION['idagent'] == 69) {
			$clauseagent = " AND a.idagent IN (23, 69)";
		} else {
			$clauseagent = " AND a.idagent = ".$_SESSION['idagent'];
		}
	break;
	case"ADMIN":
		$etatfac = '3, 4';
		$page2 = 'factoring1.php';
		$clauseagent = '';
	break;
}


?>
<div id="centerzonelarge" style="background-color: #D3D3D3;">
<?php
$recherche = "SELECT 
	me.hin1, me.hout1, me.hin2, me.hout2, me.ferie, me.kmfacture, me.kmpaye, me.idshop,
	me.idmerch, me.datem, me.idmerch, me.genre, me.idpeople, me.weekm, me.yearm,
	me.produit, me.facturation AS mefacturation, me.contratencode, me.idcofficer,
	
	a.prenom, 
	c.codeclient, c.societe AS clsociete, c.facturation AS clfacturation, c.idclient, 
	s.codeshop, s.societe AS ssociete, s.ville AS sville, 
	p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.codepeople,
	o.oprenom, o.onom,
	nf.montantfacture, nf.montantpaye
	
	FROM merch me
	LEFT JOIN agent a ON me.idagent = a.idagent 
	LEFT JOIN client c ON me.idclient = c.idclient 
	LEFT JOIN cofficer o ON me.idcofficer = o.idcofficer
	LEFT JOIN people p ON me.idpeople = p.idpeople
	LEFT JOIN shop s ON me.idshop = s.idshop
	LEFT JOIN notefrais nf ON nf.secteur = 'ME' and me.idmerch = nf.idmission
	
	WHERE me.facnum IS NULL AND me.facturation IN (".$etatfac.") ".$clauseagent." AND ";

# Par semaine : client periode payement = 1 ###############################
	$quidsb = "c.facturation = '1' AND c.factureofficer = '2' AND me.datem < '".date("Y-m-d")."' AND me.genre NOT LIKE 'EAS' ORDER BY me.yearm, me.weekm, me.idclient, me.boncommande, me.datem";
	$quidsc = "c.facturation = '1' AND c.factureofficer = '1' AND me.datem < '".date("Y-m-d")."' AND me.genre NOT LIKE 'EAS' ORDER BY me.yearm, me.weekm, me.idclient, me.idcofficer, me.datem";

# Par mois : client periode payement = 3 ##################################
	$quidmb = "c.facturation = '3' AND c.factureofficer = '2' AND me.datem < '".$onemonthago."' AND me.genre NOT LIKE 'EAS' ORDER BY me.yearm, MONTH(me.datem), me.idclient, me.boncommande, me.datem";
	$quidmc = "c.facturation = '3' AND c.factureofficer = '1' AND me.datem < '".$onemonthago."' AND me.genre NOT LIKE 'EAS' ORDER BY me.yearm, MONTH(me.datem), me.idclient, me.idcofficer, me.datem";

# EAS : regroupement par mois par client #############################
	$quidEAS = "me.genre LIKE 'EAS' AND me.datem < '".date("Y-m-d")."' AND me.idclient NOT IN (1713, 2929, 2651, 2928) ORDER BY CONCAT(YEAR(me.datem),MONTH(me.datem)), me.idclient, me.idshop, me.datem";

# Autre : client modalite payement != 1, != 3 #############################
	$quida = "(c.facturation NOT IN (1,3) OR c.factureofficer NOT IN (1, 2)) AND me.datem < '".date("Y-m-d")."' ORDER BY me.yearm, MONTH(me.datem), me.idclient, me.datem";

?>
<br>
PAR SEMAINE (avant le <?php echo fdate(date ("Y-m-d", strtotime("-2 days"))); ?>)<br><br>
<?php
	$DB->inline($recherche.' '.$quidsb);
	$jump = 'SE-PO';
	include $page2;

	$DB->inline($recherche.' '.$quidsc);
	$jump = 'SE-OF';
	include $page2;
?>
PAR MOIS (avant le <?php echo fdate($onemonthago); ?>)<br><br>
<?php
	$DB->inline($recherche.' '.$quidmb);
	$jump = 'MO-PO';
	include $page2;

	$DB->inline($recherche.' '.$quidmc);
	$jump = 'MO-OF';
	include $page2;
?>
EAS (avant le <?php echo fdate(date("Y-m-d")); ?>)<br><br>
<?php
	$DB->inline($recherche.' '.$quidEAS);
	$jump = 'EAS';
	include $page2;
?>
AUTRES (avant le <?php echo fdate(date("Y-m-d")); ?>)<br><br>
<?php
	$DB->inline($recherche.' '.$quida);
	$jump = '';
	include $page2;
?>
</div>