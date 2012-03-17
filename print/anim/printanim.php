<?php
# Entete de page
define('NIVO', '../../');
$Style = 'anim';
include NIVO."includes/ifentete.php" ;

# Classes utilisées
include_once NIVO.'print/anim/contrat/contrat_inc.php';
include_once NIVO.'print/commun/notefrais.php';
include_once NIVO.'print/anim/rapportp/rapportp.php';
include_once NIVO.'print/anim/rapportp/p-rapportBetv.php';
include_once NIVO.'print/commun/bdc-supplier.php';
include_once NIVO.'print/dispatch/dispatch_functions.php';

?><link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8"><?php

#init vars
$onemonthago  = date ("Y-m-d", strtotime("-1 month"));

if (!isset ($_GET['ptype'])) $_GET['ptype'] = '';

if ($_GET['web'] == 'web') {
	$_POST['ptype'] = array($_GET['ptype']);

	$detail = new db();

	if (in_array ("rapportc", $_POST['ptype'])) { #Planning ONLY
		$jourencours = date("Y-m-d", strtotime("next Monday"));

		$detail->inline("SELECT idanimation, idanimjob FROM `animation` WHERE `idanimjob` = ".$_GET['idanimjob']." AND `datem` < '".$jourencours."'");
		$animjobquid = "WHERE an.idanimjob = ".$_GET['idanimjob']." AND `datem` < '".$jourencours."'";
	} else {
		$detail->inline("SELECT idanimation, idanimjob FROM `animation` WHERE `idanimjob` = ".$_GET['idanimjob']);
		$animjobquid = "WHERE an.idanimjob = ".$_GET['idanimjob'];
	}

	while ($rowjob = mysql_fetch_array($detail->result)) {
		$_POST['print'][] = $rowjob['idanimation'];
	}
} ### pour le print WEb

if (!empty($_POST['ptype'])) {

	$jobquid = "WHERE an.idanimation IN('".implode("', '", $_POST['print'])."')";

# ==========================================================================================================================
# = 1. Impression des Contrats ============================================================================== STORAGE OK ===
# ==========================================================================================================================
	if (in_array ("contrat", $_POST['ptype'])) {
		$DB->inline("SELECT an.idanimation, nf.idnfrais, p.idsupplier, p.idpeople, p.pnom, p.pprenom, p.docpref
			FROM animation an
			LEFT JOIN notefrais nf ON nf.secteur = 'AN' AND nf.idmission = an.idanimation
			LEFT JOIN people p ON an.idpeople = p.idpeople
			".$jobquid);
		$result = $DB->result;
		#########################################################################################################
		### 1ère passe : Génère les contrats individuels et des notes de frais
		while ($row = mysql_fetch_array($result)) {
			$thiscontrat = ($row['idsupplier'] == 0) ? print_contratanim($row['idanimation']) : print_bdc($row['idanimation'],'AN') ;
			if (!empty($thiscontrat)) {
				$parpeople[$row['idpeople']][] = $thiscontrat;

				if (!empty($row['idnfrais'])) {
					$thisnotefrais = print_notefrais($row['idnfrais']);
					$parpeople[$row['idpeople']][] = $thisnotefrais;
				}
			}
		}

		#########################################################################################################
		### 2ème passe : Assemblage des contrats et notes de frais
		# # Génération du tableau d'envoi de documents

		generateSendTable($parpeople, "people","temp/ACpe", "ACpe", "Contrats");
		unset($parpeople);
		echo "<hr>";
	}

# ==========================================================================================================================
# = 2. Plannign Interne ====================================================================================================
# ==========================================================================================================================
	# PDF
	if (in_array ("planningint", $_POST['ptype'])) {
		echo '<hr width="100%"><h2>Internal Plannings</h2>';
		include NIVO.'/print/anim/planninginterne/planning.php';
	}

	## XLS
	if (in_array ("planningxls", $_POST['ptype'])) {
		echo '<hr width="100%"><h2>Internal Plannings</h2>';
		include NIVO.'/print/anim/planninginterne/planningxls.php';
	}

# ==========================================================================================================================
# = 3. Plannign Externe ====================================================================================================
# ==========================================================================================================================
	if (in_array ("planning", $_POST['ptype'])) {
		include NIVO.'/print/anim/planning/planning.php';
		generateSendTable($parcofficer, "cofficer","temp/anim/planning", null, "Planning Externe");
		unset($parcofficer);
		echo "<hr>";
	}

# ==========================================================================================================================
# = 4. Telecheck ===========================================================================================================
# ==========================================================================================================================
	if (in_array ("telecheck", $_POST['ptype'])) {
		echo '<hr width="100%"><h2>Telecheck</h2>';
		include NIVO.'/print/anim/telecheck/telecheck.php';
	}

# ==========================================================================================================================
# = 5. Rapport Client  =====================================================================================================
# ==========================================================================================================================
	if (in_array ("rapportc", $_POST['ptype'])) {
		include NIVO.'/print/anim/rapportc/rapportc.php';
		generateSendTable($parcofficer, "cofficer","temp/anim/rapportc", null, "Rapport Client");
		unset($parcofficer);
		echo "<hr>";
	}

# ==========================================================================================================================
# = 6. Rapport People (rapport de vente) ==================================================================== STORAGE OK ===
# ==========================================================================================================================
	if (in_array ("rapportp", $_POST['ptype'])) {
		$rows = $DB->getArray("SELECT
				an.idanimation, j.idclient, an.weekm, an.idshop, an.idpeople, an.datem, YEAR(an.datem) AS yearm
			FROM animation an
				LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
			".$jobquid);

		$nodup = array();

		foreach ($rows as $row) {
			## Clients Normaux + construct array BeTV
			if (($row['idclient'] == '2053') and !empty($row['idpeople']) and !empty($row['idshop']) and !empty($row['datem'])) {
				$nodup[$row['idpeople'].'-'.$row['idshop'].'-'.$row['weekm'].'-'.$row['yearm']][] = $row['idanimation'];
			} else {
				$rpath = print_rapportpanim($row['idanimation']);
				$idpeople = $DB->getOne("SELECT idpeople FROM animation WHERE idanimation = ".$row['idanimation']);

				if(!empty($rpath)) {
					$global[] = $rpath;
					$peopleRapports[$idpeople][] = $rpath;
				}
			}
		}

		unset($global);

		generateSendTable($peopleRapports,'people','temp/ARap','ARap', "Rapport de vente");
		unset($peopleRapports);
		echo "<hr>";
	}

# ==========================================================================================================================
# = 7. Etiquettes ==========================================================================================================
# ==========================================================================================================================
	if (in_array ("etiqpeop", $_POST['ptype'])) {
		echo '<hr width="100%"><h2>Etiquettes People</h2>';
		$etiqmode = "people";
		include NIVO.'/print/commun/etiquette.php';
	}

# ==========================================================================================================================
# = 8. Contrats + note frais + rapport people =============================================================== STORAGE OK ===
# ==========================================================================================================================


/*
	TODO : TOUT vérifier, dans les fichiers parpeople y'a des trucs louches qui traînent, style contrats de quelqu'un d'autre
*/

	if (in_array ("ccr", $_POST['ptype'])) {
		$rows = $DB->getArray("SELECT an.idanimation, nf.idnfrais, an.idpeople, an.idshop, an.weekm, YEAR(an.datem) AS yearm, j.idclient, p.pnom, p.pprenom
			FROM animation an
				LEFT JOIN people p ON an.idpeople = p.idpeople
				LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
				LEFT JOIN notefrais nf ON nf.secteur = 'AN' AND nf.idmission = an.idanimation
			".$jobquid."
			ORDER BY an.weekm, an.idshop, an.idpeople, an.datem");

		#########################################################################################################
		### 1ère passe : Génère les contrats individuels et des notes de frais
		$lastrkey = '';

		foreach ($rows as $row) {
			$peopinfo[$row['idpeople']] = $row['pnom'].' '.$row['pprenom'];
			## key pour le rapport BeTV
			if (!empty($row['idpeople']) and !empty($row['idshop']) and !empty($row['weekm']) and ($row['idclient'] == 2053)) {
				$rkey = $row['idpeople'].'-'.$row['idshop'].'-'.$row['weekm'].'-'.$row['yearm'];
			} else {
				$rkey = '';
			}

			## rapport BeTV du groupe précédent
			if ((!empty($lastrkey)) and ($lastrkey != $rkey) and ($lastclient == 2053)) {
				$thisreportbetv           = print_rapportpbetv($lastrkey);
				$global[]                 = $thisreportbetv;
				$parpeople[$lastpeople][] = $thisreportbetv;
			}

			## contrat
			$contr = print_contratanim($row['idanimation']);
			if (!empty($contr)) {
				$global[] = $contr;
				$parpeople[$row['idpeople']][] = $contr;
				# Note de frais
				if (!empty($row['idnfrais'])) {
					$global[] = print_notefrais($row['idnfrais']);
				}
				# rapport people client normal
				if ($row['idclient'] != 2053) {
					$rappeople                     = print_rapportpanim($row['idanimation']);
					$global[]                      = $rappeople;
					$parpeople[$row['idpeople']][] = $rappeople;
				}
			}

			$lastrkey   = $rkey;
			$lastpeople = $row['idpeople'];
			$lastclient = $row['idclient'];
		}

		if(!empty($lastrkey)) {
			$thisreportbetv           = print_rapportpbetv($lastrkey);
			$global[]                 = $thisreportbetv;
			$parpeople[$lastpeople][] = $thisreportbetv;
		}
		#########################################################################################################
		### 2ème passe : Assemblage des contrats et notes de frais
		$book = reliure($global, 'ACcg');
		unset($global);
		# Lien vers le PDF
		?>
		<div align="center">
			<h2> Contrat + Rapport</h2>
			<img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0">
			<a href="<?php echo NIVO.$book['path'] ;?>" target="_blank">Imprimer les C + R (<?php echo $book['pages'];?> pages)</a>
		</div>
		<hr>
		<div align="center">
			<h2>Contrat + Rapport par People</h2>
			<?php
			foreach ($parpeople as $idpeop => $fichiers) {
				$book = reliure($fichiers, 'ACCp');

				echo '<img src="'.STATIK.'illus/minipdf.gif" alt="print" width="16" height="16" border="0">';
				echo '<a href="'.NIVO.$book['path'].'" target="_blank">'.$peopinfo[$idpeop].' ( '.$book['pages'].' pages)</a><br>';
			}
	?>
		</div>
		<hr>
<?php
	}
	# fin rapport people
} else {
	## page SMS
	if ($_POST['send'] == 'sms') {
		$secteur = 'anim';
		$_POST['act'] = "show";
		$_POST['dest'] = $DB->getColumn("SELECT idpeople FROM animation WHERE idanimation IN (".implode(",", $_POST['print']).") AND idpeople > 0 GROUP BY idpeople");
		include NIVO.'mod/sms/m-sms.php';
		include NIVO.'mod/sms/v-sendsms.php';
	} else {
		echo '<Font size="4" color="#FF6633"><br><b>Aucun type de document n&rsquo;a &eacute;t&eacute; s&eacute;l&eacute;ctionn&eacute;...</b></font>';
	}
}
	echo '
		<hr width="100%">
		<div align="center">
		<br><br><a href="javascript:window.close();"><b>&gt; Close this window &lt;</b></a><br><br>
		</div>
	';


# Pied de Page
include NIVO."includes/ifpied.php" ;
?>