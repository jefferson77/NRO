<link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8">
<?php
## Switch mode USER/ADMIN
switch ($mode) {
	case"USER":
		$etatfac = '1, 3';
		$page2 = NIVO.'admin/animation/factoring1.php';
		$clauseagent = " AND a.idagent = ".$_SESSION['idagent'];
	break;
	case"ADMIN":
		$etatfac = '2, 4';
		$page2 = 'factoring1.php';
		$clauseagent = '';
	break;
}


?>
<div id="centerzonelarge" style="background-color: #D3D3D3;">
<?php
echo $mode.'<br>';

$recherche = "SELECT

	an.idanimation, an.datem, an.weekm, an.reference, an.hin1, an.hout1, an.hin2, an.hout2,
	an.kmpaye, an.kmfacture, an.isbriefing, an.produit, an.facturation, an.peoplenote, an.yearm,
	an.ferie, an.idanimjob, an.livraisonpaye, an.livraisonfacture, an.ccheck,
	j.genre, j.reference AS jobreference, j.boncommande, j.idclient, j.idcofficer,
	a.prenom, a.idagent,
	c.codeclient, c.societe AS clsociete, c.tel, c.fax,
	co.qualite, co.onom, co.oprenom, co.fax AS cofax,
	s.idshop, s.codeshop, s.societe AS ssociete, s.ville AS sville,
	p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.gsm, p.codepeople,
	ma.idanimmateriel, ma.stand, ma.gobelet, ma.serviette, ma.four, ma.curedent, ma.cuillere, ma.rechaud, ma.autre,
	nf.montantfacture, nf.montantpaye

	FROM animation an
	LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
	LEFT JOIN agent a ON j.idagent = a.idagent
	LEFT JOIN client c ON j.idclient = c.idclient
	LEFT JOIN cofficer co ON j.idcofficer = co.idcofficer
	LEFT JOIN people p ON an.idpeople = p.idpeople
	LEFT JOIN shop s ON an.idshop = s.idshop
	LEFT JOIN animmateriel ma ON an.idanimation = ma.idanimation
	LEFT JOIN notefrais nf ON nf.secteur = 'AN' and nf.idmission = an.idanimation

	WHERE an.facturation IN (".$etatfac.")".$clauseagent." AND ";

# Par semaine : client periode payement = 1 ###############################
	$quidsb = "c.facturation = '1' AND c.factureofficer = '2' AND an.datem < DATE(NOW()) ORDER BY an.yearm, an.weekm, j.idclient, j.boncommande, an.datem";
	$quidsc = "c.facturation = '1' AND c.factureofficer = '1' AND an.datem < DATE(NOW()) ORDER BY an.yearm, an.weekm, j.idclient, j.idcofficer, an.datem";

# Par mois : client periode payement = 3 ##################################
	$quidmb = "c.facturation = '3' AND c.factureofficer = '2' AND an.datem < '".$onemonthago."' ORDER BY an.yearm, MONTH(an.datem), j.idclient, j.boncommande, an.datem";
	$quidmc = "c.facturation = '3' AND c.factureofficer = '1' AND an.datem < '".$onemonthago."' ORDER BY an.yearm, MONTH(an.datem), j.idclient, j.idcofficer, an.datem";

# Autre : client modalite payement != 1, != 3 #############################
	$quida = "(c.facturation NOT IN (1,3) OR c.factureofficer NOT IN (1, 2)) AND an.datem < DATE(NOW()) ORDER BY an.yearm, MONTH(an.datem), j.idclient, an.datem";

?>
<br>
PAR SEMAINE (avant le <?php echo fdate(date ("Y-m-d", strtotime("-2 days"))); ?>)<br><br>
<?php
	$listing = new db();
	$listing->inline($recherche.' '.$quidsb);
	$jump = 'SE-PO';
	include $page2;

	$listing = new db();
	$listing->inline($recherche.' '.$quidsc);
	$jump = 'SE-OF';
	include $page2;
?>
PAR MOIS (avant le <?php echo fdate($onemonthago); ?>)<br><br>
<?php
	$listing = new db();
	$listing->inline($recherche.' '.$quidmb);
	$jump = 'MO-PO';
	include $page2;

	$listing = new db();
	$listing->inline($recherche.' '.$quidmc);
	$jump = 'MO-OF';
	include $page2;
?>
AUTRES (avant le <?php echo fdate(date("Y-m-d")); ?>)<br><br>
<?php
	$listing = new db();
	$listing->inline($recherche.' '.$quida);
	$jump = '';
	include $page2;
?>
</div>
