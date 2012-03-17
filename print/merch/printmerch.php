<?php
define('NIVO', '../../');

$Style = 'merch';

include NIVO.'includes/ifentete.php';

include_once NIVO.'print/merch/contrat/contrat_inc.php';
include_once NIVO.'print/merch/semaine/semaine.php';
include_once NIVO.'print/merch/eas/rapport.php';
include_once NIVO.'print/commun/notefrais.php';
include_once NIVO.'print/commun/bdc-supplier.php';
include_once NIVO.'print/dispatch/dispatch_functions.php';

?>
<link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8">
<?php
$DB->inline("SET NAMES latin1");

if (!empty($_POST['ptype'])) {

	if ($_GET['src'] == 'planning') {
		## Venant de Planning
		$quid = array();
		foreach ($_POST['print'] as $print) {
			$parts = explode("/", $print);
			# 0:date, 1:idpeople, 2:genre, 3:idshop
			$sem = weekfromdate($parts[0]);
			$year = date("Y", strtotime($parts[0]));
			
			switch ($parts[2]) {
				case "Store Check":
					$maga = "";	
				break;
				
				default:
					$maga = " AND me.idshop = '".$parts[3]."'";	
				break;
			}


			$quid[] = "(me.weekm = '".$sem."' AND YEAR(me.datem) = '".$year."' AND me.idpeople = '".$parts[1]."' AND me.genre = '".$parts[2]."'".$maga.")";
		}
		$merchjobquid = 'WHERE '.implode(" OR ", $quid);
	} else {
		## Venant de listing
		$merchjobquid = 'WHERE me.idmerch IN ('.implode(', ',$_POST['print']).')';
	}

# ==========================================================================================================================
# = 1. Impression des Contrats et semaines ================================================================== STORAGE OK ===
# ==========================================================================================================================
	if ((in_array ("contrat", $_POST['ptype'])) or (in_array ("semaine", $_POST['ptype'])) or (in_array ("rapporteas", $_POST['ptype']))) {

		$DB->inline("SELECT 
				me.idshop, me.datem, me.idpeople, me.genre, me.weekm, YEAR(me.datem) as yearm, me.idmerch,
				nf.idnfrais,
				p.pnom, p.pprenom, p.idsupplier, p.docpref
			FROM merch me 
				LEFT JOIN notefrais nf ON nf.secteur = 'ME' AND nf.idmission = me.idmerch
				LEFT JOIN people p ON p.idpeople = me.idpeople
			".$merchjobquid."
			ORDER BY p.pnom, p.pprenom, yearm, me.weekm, me.datem");
			
			$temp = $DB->result;

		#########################################################################################################
		### 1ère passe : Génère les contrats journaliers et les notes de frais

		## init
		$daytable = array();
		$lastwpg = '';
		$global = array();
		$parpeople = array();
		$lshops = array();

		## Pour ne par répéter le code 2x
		function attach_rapporteas ($lastinfos) {
			global $global, $parpeople, $lshops;
			if ((count($lshops) > 0) and (in_array ("rapporteas", $_POST['ptype']))) {
				$lshops = array_unique($lshops);
				foreach ($lshops as $shop) {
					$thisrepport = print_rapporteasmerch($shop, $lastinfos['weekm'], $lastinfos['yearm'], $lastinfos['idpeople']);
					$parpeople[$lastinfos['idpeople']][] = $thisrepport;
				}
				$lshops = array();
			}
		}

		while ($row = mysql_fetch_array($temp)) {
			$newwpg = $row['idpeople']."|".$row['yearm']."|".$row['weekm'].'|'.$row['genre'];
			$daypeop = $row['genre'].'-'.$row['datem'].'-'.$row['idpeople'];

			if ($newwpg != $lastwpg) {
				if (in_array ("rapporteas", $_POST['ptype'])) {
				## rapport EAS
					attach_rapporteas($lastinfos);
				}
				
				if (in_array ("semaine", $_POST['ptype'])) {
				## Semaine
					$thissemaine = print_semainemerch($row['genre'], $row['weekm'], $row['yearm'], $row['idpeople']);
					$parpeople[$row['idpeople']][] = $thissemaine;
				}
			}

		## Contrats Journaliers
			if (in_array ("contrat", $_POST['ptype'])) {
				if($row['idsupplier']==0)
				{
					if (!in_array($daypeop, $daytable)) { 
						$thiscontrat = print_contratmerch($row['genre'], $row['datem'], $row['idpeople']);
					} else {
						$thiscontrat = '';
					}
				}
				else
				{
					$thiscontrat = print_bdc($row['idmerch'],'ME');
				}
				if (!empty($thiscontrat)) {
					$daytable[] = $daypeop;
					$parpeople[$row['idpeople']][] = $thiscontrat;
				}

		## notes frais
				if (!empty($row['idnfrais'])) {
					$thisnotefrais = print_notefrais($row['idnfrais']);
					$parpeople[$row['idpeople']][] = $thisnotefrais;
				}
			}

			$lastwpg = $newwpg;
			$lastinfos = $row; # sert pour imprimer les rapports eas
			$lshops[] = $row['idshop'];
		}

		## rapport EAS
		attach_rapporteas($lastinfos);

		#########################################################################################################
		### 2ème passe : Assemblage et affichage


		if (count($parpeople) > 0) {
			
			generateSendTable($parpeople, "people","temp/MCpe", "MCpe", "Contrats");
			unset($parpeople);
		}
	}

# ==========================================================================================================================
# = 2. Planning Interne ====================================================================================================
# ==========================================================================================================================
	if (in_array ("planningint", $_POST['ptype'])) {
		echo '<hr width="100%"><h2>Planning Interne</h2>';
		include NIVO.'print/merch/planninginterne/planning.php';
	}

# ==========================================================================================================================
# = 3. Planning Client =====================================================================================================
# ==========================================================================================================================
	if (in_array ("planningclient", $_POST['ptype'])) {
		include_once NIVO.'print/merch/planning/planningclient.php';

		#########################################################################################################
		### 1ère passe : Génère les Plannings client
		$lesPlanningsClient = $DB->getArray("SELECT
			me.weekm, me.yearm, me.idcofficer, me.genre, me.idclient,
			c.societe, co.onom, co.oprenom, co.departement, co.idcofficer, co.docpref
			FROM merch me 
				LEFT JOIN cofficer co ON me.idcofficer = co.idcofficer
				LEFT JOIN client c ON co.idclient = c.idclient
			".$merchjobquid."
			GROUP BY CONCAT(me.idcofficer, '-', me.yearm, '-', me.weekm, '-', me.genre) 
			ORDER BY c.societe, co.departement, co.onom, me.genre, me.yearm, me.weekm");

		foreach ($lesPlanningsClient as $row) {
			$thisfile = print_planningclientmerch($row['weekm'], $row['yearm'], $row['idcofficer'], $row['genre']);
			if (!empty($thisfile)) {
				$parclient[$row['idcofficer']][] = $thisfile;
			}
		}

		#########################################################################################################
		### 2ème passe : Assemblage et affichage
		if (count($parclient) > 0) {
			generateSendTable($parclient, "cofficer","temp/MPCcl", "MPCcl", "Planning Client");
			unset($parclient);
		}
	}

# ==========================================================================================================================
# = 3. Planning Shop ====================================================================================================
# ==========================================================================================================================
	if (in_array ("planningshop", $_POST['ptype']))
	{
		include_once NIVO.'print/merch/planning/planningshop.php';
		
		#########################################################################################################
		### 1ère passe : Génère les Plannings shop
		$lesPlanningsShop = $DB->getArray("SELECT
			me.weekm, me.yearm, me.idshop, me.genre, 
			s.societe, s.docpref
			FROM merch me 
				LEFT JOIN shop s ON me.idshop = s.idshop
			".$merchjobquid."
			GROUP BY CONCAT(me.idshop, '-', me.yearm, '-', me.weekm, '-', me.genre) 
			ORDER BY s.societe, me.genre, me.yearm, me.weekm");
		
		foreach ($lesPlanningsShop as $row) {
			$thisfile = print_planningshopmerch($row['weekm'], $row['yearm'], $row['idshop'], $row['genre']);

			if (!empty($thisfile)) {
				$parshop[$row['idshop']][] = $thisfile;
			}
		}

		#########################################################################################################
		### 2ème passe : Assemblage et affichage

		if (count($parshop) > 0) {
			generateSendTable($parshop, "shop","temp/MPSsh", "MPSsh", "Planning");
			unset($parshop);
		}
	}

# ==========================================================================================================================
# = 4. Etiquettes ==========================================================================================================
# ==========================================================================================================================
	## People
	if (in_array ("etiqpeop", $_POST['ptype'])) {
		echo '<hr width="100%"><h2>Etiquettes People</h2>';
		$etiqmode = "people";
		include NIVO.'print/commun/etiquette.php';
	}

	## Shop
	if (in_array ("etiqshop", $_POST['ptype'])) {
		echo '<hr width="100%"><h2>Etiquettes Shop</h2>';
		$etiqmode = "shop";
		include NIVO.'print/commun/etiquette.php';
	}

} else {
	if ($_POST['send'] == 'sms') {
		
		include NIVO.'mod/sms/m-sms.php';
		
		$secteur = 'merch';
		$_POST['act'] = "show";
		
		$dest = array();
		
		$src = $DB->getColumn("SELECT idpeople FROM merch WHERE idmerch IN (".implode(",", $_POST['print']).") AND idpeople > 0 GROUP BY idpeople");
		$_POST['dest'] = $src;
		include NIVO.'mod/sms/v-sendsms.php';
	} else {
		echo '<Font size="4" color="#FF6633"><br><b>Aucun type de document n&rsquo;a &eacute;t&eacute; s&eacute;l&eacute;ctionn&eacute;...</b></font>';
	}
}

	echo '
		<hr width="100%">
		<div align="center">
		<br><br><a href="javascript:window.close();"><b>&gt; Fermer &lt;</b></a><br><br>
		</div>';

# Pied de Page
include NIVO.'includes/ifpied.php' ;
?>
