<?php
# Entete de page
define('NIVO', '../../'); 
$Titre = 'STATS';
$Style = 'admin';

# Entete
include NIVO."includes/entete.php" ;
# Classes utilisées
include NIVO.'classes/vip.php';
include NIVO.'classes/anim.php';
include NIVO.'classes/merch.php';
?>
<div id="centerzonelarge" style="background-color: #D3D3D3;">
<?php

## si aucun secteur coché, cocher les trois
if (!is_array($_POST['secteur'])) $_POST['secteur'] = array('vip', 'anim', 'merch', 'eas');

setlocale(LC_TIME, 'fr_FR'); 

# Carousel des fonctions
switch ($_GET['act']) {
############## SWITCH des FACTURATION a Veérifier  #############################################
	case "search": 
		include "search.php" ;
		$PhraseBas = 'Recherche';
	break;
############## SWITCH des PRE FACTURATION a Veérifier  #############################################
	case "list": 
	?>
		<link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8">
	<?php
##################################################################################################################################################
####### VIP       				  ################################################################################################################
##################################################################################################################################################
		if (in_array("vip", $_POST['secteur'])) {
		## Quid
			$listingVI = new db();

			$searchfields = array (
				'm.idvipjob' 	=> 'job', 
				'm.vipdate' 	=> 'date', 
				'm.facnum' 		=> 'facnum', 
				#'m.semaine' 	=> 'semaine', # pas de semaine en vip
				'j.idagent' 	=> 'idagent',
				'c.idclient' 	=> 'cid',
				'c.societe' 	=> 'csociete'
			);
			
			$quid = $listingVI->MAKEsearch($searchfields);

		## Totaux
			switch($_POST['totaux']) {
				case"facture":
					$order = "m.facnum, m.vipdate";
					$labeljumper = "Facture";
				break;

				case"people":
					$order = "m.idpeople, m.vipdate";
					$labeljumper = "People";
				break;
				
				case"agent":
					$order = "j.idagent, m.vipdate";
					$labeljumper = "Planner";
				break;

				case"client":
					$order = "j.idclient";
					$labeljumper = "Client";
				break;

				case"jour":
					$order = "m.vipdate, m.idvip";
					$labeljumper = "Jour";
				break;
				
				case"mois":
				case"semaine":
					$order = "moism, m.vipdate";
					$labeljumper = "Mois";
				break;
				break;
				
				case"job":
				default:
					$order = "m.idvipjob, m.vipdate, m.idvip";
					$labeljumper = "Job";
			}

			$recherche = "SELECT 
				m.idvip, m.idvipjob, m.idpeople, m.facnum, m.vipdate, MONTH(m.vipdate) as moism, YEAR(m.vipdate) as yearm, m.h200, 
				m.vipin, m.vipout, m.brk, m.km, m.vkm, m.fkm, m.vfkm, m.loc1, m.loc2, m.unif, m.cat, m.vcat, m.disp, m.vdisp, 
				p.pnom, p.pprenom, p.codepeople,
				c.societe AS clsociete, 
				j.idclient, j.idagent, j.reference, j.etat, 
				s.codeshop, s.societe AS ssociete, s.ville AS sville, 
				a.prenom,
				f.intitule

				FROM vipmission m
				LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob
				LEFT JOIN agent a ON j.idagent = a.idagent
				LEFT JOIN client c ON j.idclient = c.idclient
				LEFT JOIN people p ON m.idpeople = p.idpeople
				LEFT JOIN shop s ON m.idshop = s.idshop
				LEFT JOIN facture f ON j.facnum = f.idfac
			
				WHERE ".$quid."
				
				ORDER BY ".$order.""; 
			
			$listingVI->inline($recherche);
			
			include "statsVI.php" ;
		}
##################################################################################################################################################
####### ANIM       				  ################################################################################################################
##################################################################################################################################################
		if (in_array("anim", $_POST['secteur'])) {
		## Quid
			$listingAN = new db();

			if (!empty($_POST['semaine']) and (empty($_POST['year']))) $_POST['year'] = date("Y");

			$searchfields = array (
				'an.idanimjob' 	=> 'job', 
				'an.datem' 		=> 'date', 
				'an.facnum' 	=> 'facnum', 
				'an.weekm'	 	=> 'semaine', # pas de semaine en vip
				'j.idagent' 	=> 'idagent',
				'c.idclient' 	=> 'cid',
				'c.societe' 	=> 'csociete'
			);
			
			$quid = $listingAN->MAKEsearch($searchfields);
			
			if ($_POST['year'] > 0) $quid .= " AND YEAR(an.datem) = '".$_POST['year']."'";

		## Totaux
			switch($_POST['totaux']) {
				case"facture":
					$order = "an.facnum, an.datem";
					$labeljumper = "Facture";
				break;

				case"people":
					$order = "an.idpeople, an.datem";
					$labeljumper = "People";
				break;
				
				case"agent":
					$order = "j.idagent, an.datem";
					$labeljumper = "Planner";
				break;
				
				case"semaine":
					$order = "yearm, an.weekm";
					$labeljumper = "Semaine";
				break;
				
				case"client":
					$order = "j.idclient, an.datem";
					$labeljumper = "Client";
				break;

				case"jour":
					$order = "an.datem, an.idanimation";
					$labeljumper = "Jour";
				break;
				
				case"mois":
					$order = "moism, an.datem";
					$labeljumper = "Mois";
				break;
				
				case"job":
				default:
					$order = "an.idanimjob, an.idanimation";
					$labeljumper = "Job";
			}

			$recherche = "SELECT

				an.idanimation, an.datem, an.weekm, an.reference, an.hin1, an.hout1, an.hin2, an.hout2, an.facnum, an.weekm,
				an.kmpaye, an.kmfacture, an.isbriefing, an.produit, an.facturation, an.peoplenote, 
				an.ferie, an.idanimjob, an.livraisonpaye, an.livraisonfacture, YEAR(an.datem) as yearm, MONTH(an.datem) as moism,
				j.genre, j.reference AS jobreference, j.boncommande, j.idclient, j.idcofficer,
				nf.montantpaye, nf.montantfacture,
				
				a.prenom, a.idagent, 
				c.codeclient, c.societe AS clsociete, c.tel, c.fax,
				co.qualite, co.onom, co.oprenom, co.fax AS cofax, 
				s.idshop, s.codeshop, s.societe AS ssociete, s.ville AS sville, 
				p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.gsm, p.codepeople,
				ma.idanimmateriel, ma.stand, ma.gobelet, ma.serviette, ma.four, ma.curedent, ma.cuillere, ma.rechaud, ma.autre,
				f.intitule
				
				FROM animation an
				LEFT JOIN animjob j ON an.idanimjob = j.idanimjob 
				LEFT JOIN agent a ON j.idagent = a.idagent 
				LEFT JOIN client c ON j.idclient = c.idclient 
				LEFT JOIN cofficer co ON j.idcofficer = co.idcofficer 
				LEFT JOIN people p ON an.idpeople = p.idpeople
				LEFT JOIN shop s ON an.idshop = s.idshop
				LEFT JOIN facture f ON an.facnum = f.idfac
				LEFT JOIN animmateriel ma ON an.idanimation = ma.idanimation
				LEFT JOIN notefrais nf ON an.idanimation = nf.idmission AND nf.secteur = 'AN'
				
			
				WHERE ".$quid."
				
				ORDER BY ".$order."
			
				";	

			
			$listingAN->inline($recherche);
	
			include "statsAN.php" ;
		}
		
		
		
##################################################################################################################################################
####### MERCH      				  ################################################################################################################
##################################################################################################################################################
		if (in_array("merch", $_POST['secteur'])) {
		## Quid
			$listingME = new db();

			if (!empty($_POST['semaine']) and (empty($_POST['year']))) $_POST['year'] = date("Y");

			$searchfields = array (
				'me.genre' 		=> 'genre', 
				'me.datem' 		=> 'date', 
				'me.facnum' 	=> 'facnum', 
				'me.weekm'	 	=> 'semaine', # pas de semaine en vip
				'me.idagent' 	=> 'idagent',
				'c.idclient' 	=> 'cid',
				'c.societe' 	=> 'csociete'
			);
			
			$quid = $listingME->MAKEsearch($searchfields);

			if ($_POST['year'] > 0) $quid .= " AND YEAR(me.datem) = '".$_POST['year']."'";
			
		## Totaux
			switch($_POST['totaux']) {
				case"facture":
					$order = "me.facnum, me.datem";
					$labeljumper = "Facture";
				break;

				case"people":
					$order = "me.idpeople, me.datem";
					$labeljumper = "People";
				break;
				
				case"agent":
					$order = "me.idagent, me.datem";
					$labeljumper = "Planner";
				break;
				
				case"semaine":
					$order = "yearm, me.weekm";
					$labeljumper = "Semaine";
				break;
				
				case"jour":
					$order = "me.datem, me.idmerch";
					$labeljumper = "Jour";
				break;
				
				case"mois":
					$order = "moism, me.datem";
					$labeljumper = "Mois";
				break;
				
				case"client":
				default:
					$order = "me.idclient, me.datem";
					$labeljumper = "Client";
			}

			$recherche = "SELECT 
			
				me.hin1, me.hout1, me.hin2, me.hout2, me.ferie, me.kmfacture, me.kmpaye, me.idclient,
				me.idmerch, me.datem, me.idmerch, me.genre, me.idpeople, me.weekm,
				me.produit, me.facturation, me.contratencode, me.idcofficer, me.facnum, YEAR(me.datem) as yearm, MONTH(me.datem) as moism,
				
				a.prenom, a.idagent, 
				c.codeclient, c.societe AS clsociete, c.tel, c.fax,
				s.idshop, s.codeshop, s.societe AS ssociete, s.ville AS sville, 
				p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.gsm, p.codepeople,
				co.qualite, co.onom, co.oprenom, co.fax AS cofax, 
				f.intitule,
				nf.montantpaye, nf.montantfacture
				
				FROM merch me
				LEFT JOIN agent a ON me.idagent = a.idagent 
				LEFT JOIN client c ON me.idclient = c.idclient 
				LEFT JOIN cofficer co ON me.idcofficer = co.idcofficer
				LEFT JOIN people p ON me.idpeople = p.idpeople
				LEFT JOIN shop s ON me.idshop = s.idshop
				LEFT JOIN facture f ON me.facnum = f.idfac
				LEFT JOIN notefrais nf ON nf.secteur = 'ME' and me.idmerch = nf.idmission

				WHERE ".$quid."
				
				ORDER BY ".$order;	

			$listingME->inline($recherche);
	
			include "statsME.php" ;
		}

		$PhraseBas = 'Statistiques';
	break;

}
?>
</div>
<div id="topboutonsadmin">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="on"><a href="?act=search"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="planning" width="32" height="32" border="0"><br>Rechercher</a></td>
			<td class="on"><a href="?act=list"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="planning" width="32" height="32" border="0"><br>Liste</a></td>
		</tr>
	</table> 
</div>
<?php include NIVO."includes/pied.php"; ?>