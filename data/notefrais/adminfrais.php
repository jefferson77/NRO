<?php
# Entete de page
define('NIVO', '../../');

setlocale (LC_ALL, 'fr_FR');

# classes
include NIVO."print/commun/notefrais.php";

# Entete
$Titre = 'NOTES DE FRAIS';
$classe = "standard";
include NIVO."includes/entete.php" ;

## GET vars ####
if(isset($_GET['nfyear'])) $nfyear=$_GET['nfyear'];
else $nfyear = date("Y");
if(isset($_GET['nfmonth'])) $nfmonth=$_GET['nfmonth'];
else $nfmonth = date("m");

## CLEAN des notes de frais vides
$DB->inline("DELETE FROM `notefrais` WHERE `intitule` LIKE '' AND `description` LIKE '' AND `montantpaye` = 0 AND `montantfacture` = 0");

## requête SQL ####
$tableau = array();
$secteurDB = array(
				'vip' => array('codesecteur'=>'VI', 'jobtable' => 'vipjob', 'jobfield' => 'idvipjob', 'missiontable' => 'vipmission', 'missionfield'=>'idvip', 'agentlink'=>'jobtable.idagent'),
				'animation' => array('codesecteur'=>'AN', 'jobtable' => 'animjob', 'jobfield' => 'idanimjob', 'missiontable' => 'animation', 'missionfield'=>'idanimation', 'agentlink'=>'missiontable.idagent'),
				'merch' => array('codesecteur'=>'ME', 'jobtable' => 'merch', 'jobfield' => 'idmerch', 'missiontable' => 'merch', 'missionfield'=>'idmerch', 'agentlink'=>'missiontable.idagent')
				);	
foreach($secteurDB as $secteur) {
	$sql = 'SELECT 	notefrais.*, 
					people.idpeople, people.codepeople, people.pnom, people.pprenom, people.banque,
					jobtable.idclient, jobtable.facnum as jfacnum,
					missiontable.facnum as mfacnum,
					client.societe AS client,
					agent.prenom, agent.nom
			FROM notefrais
			LEFT JOIN '.$secteur['missiontable'].' AS missiontable ON missiontable.'.$secteur['missionfield'].' = notefrais.idmission
			LEFT JOIN '.$secteur['jobtable'].' AS jobtable ON jobtable.'.$secteur['jobfield'].' = notefrais.';
	$sql .= ($secteur['codesecteur']=="ME")?'idmission':'idjob';
	$sql .= '
			LEFT JOIN people ON missiontable.idpeople = people.idpeople
			LEFT JOIN client ON jobtable.idclient = client.idclient
			LEFT JOIN agent ON '.$secteur['agentlink'].' = agent.idagent
			WHERE notefrais.secteur = "'.$secteur['codesecteur'].'"';
	if($_GET['nfact'] == 'search') {
		## search parameters
		if(!empty($_GET['idpeople'])) $sql .= ' AND people.idpeople="'.$_GET['idpeople'].'"';
		else {
			if(!empty($_POST['codepeople'])) $sql .= ' AND people.codepeople="'.$_POST['codepeople'].'"';
			else if(!empty($_POST['nompeople'])) $sql .= ' AND people.pnom LIKE "%'.$_POST['nompeople'].'%"';					
			if(!empty($_POST['datemission'])) $sql .= ' AND notefrais.datemission ="'.fdatebk($_POST['datemission']).'"';
		}
	} else {
		## list parameters
		$sql .= ' AND notefrais.datemission LIKE "'.$nfyear.'-'.$nfmonth.'%"';
	}
	$sql .= ' ORDER BY pnom ASC, datemission ASC';

	$res = $DB->getArray($sql);
	if(!empty($res)) $tableau = array_merge($tableau, $res);
}

## Affichage ########
# Barre des Boutons
?>
<div id="topboutonsadmin">
<table border="0" cellspacing="1" cellpadding="0">
	<tr>
		<td class="on"><a href="?nfact=search"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
		<td class="on"><a href="?nfyear=<?php echo $nfyear; ?>&nfmonth=<?php echo $nfmonth; ?>"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="retourliste" width="32" height="32" border="0"><br>Liste</a></td>
		<?php if(!empty($tableau)) ?><td class="on"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?nfact=print&nfyear=<?php echo $nfyear; ?>&nfmonth=<?php echo $nfmonth; ?>"><img src="<?php echo STATIK ?>illus/printr01.gif" alt="imprimer" width="32" height="32" border="0"><br>Imprimer le mois en cours</a>
		<?php if(!empty($tableau)) ?><td class="on"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?nfact=printpers&nfyear=<?php echo $nfyear; ?>&nfmonth=<?php echo $nfmonth; ?>"><img src="<?php echo STATIK ?>illus/printr01.gif" alt="imprimer" width="32" height="32" border="0"><br>Impression par personne</a></td></td>

	</tr>
</table>
</div>';

<?php
## leftmenu
echo '<div id="leftmenu">';	include('leftmenu.php'); echo '</div>';
echo '<div id="infozone">';
if($_GET['nfact'] == 'print') {
	include(NIVO.'print/commun/notefraislist.php');
} else if ($_GET['nfact'] == 'printpers') {
	include(NIVO.'print/commun/notefraispeople.php');
## Formulaire de recherche
} else if($_GET['nfact'] == 'search' AND empty($_GET['idpeople']) AND empty($_POST['codepeople']) AND empty($_POST['nompeople']) AND empty($_POST['datemission'])) {
	echo '<div align="center">
		<form method="post" action="'.$_SERVER['PHP_SELF'].'?nfact=search">
		<table border="0" cellspacing="0" cellpadding="0">
			<tr><td colspan="2"><strong>Recherche par :</strong></td></tr>
		  <tr>
			<td>Code people</td>
			<td><input type="text" name="codepeople"></td>
		  </tr>
		  <tr>
			<td><strong>OU</strong> nom people</td>
			<td><input type="text" name="nompeople"></td>
		  </tr>
		  <tr>
			<td><strong>ET/OU</strong> date</td>
			<td><input type="text" name="datemission"></td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td><input type="submit" name="Submit" value="Rechercher"></td>
		  </tr>
		</table>
		</form>
	</div>';
} else { 
	echo '
	<h2>'.$nfmonth.'/'.$nfyear.'</h2>
	<table width="100%" cellspacing="1" cellpadding="1">
	<tr bgcolor="#336699">
		<th>People</th>
		<th>Agent</th>
		<th>Client</th>
		<th>S</th>
		<th>Mission</th>
		<th>Facture</th>
		<th>Date</th>
		<th>Intitulé</th>
		<th>Description</th>
		<th width="50" align="center">Montant<br/>(facture)</th>
		<th width="50" align="center">Montant<br/>(payé)</th>
		<th align="center">print</th>
	</tr>';
	$i=0;
	$nftotalfac=null;
	$nftotalpaye=null;
	$currentpeople = null;
	if(!empty($tableau)) {
		foreach($tableau as $nfrais) {
			if ($nfrais['mfacnum'] > 0) $nfrais['facnum'] = $nfrais['mfacnum'];
			else $nfrais['facnum'] = $nfrais['jfacnum'];
			
			## print note frais
			$path = print_notefrais($nfrais['idnfrais']);

			if($currentpeople !== $nfrais['idpeople']) {
				$i++; 
				echo '<tr'; if(fmod($i,2)==1) echo ' bgcolor="#848588"'; echo '><td>'; 
				echo '<a href="'.$_SERVER['PHP_SELF'].'?nfact=search&idpeople='.$nfrais['idpeople'].'">';
				echo $nfrais['pnom'].' '.$nfrais['pprenom'].' ('.$nfrais['codepeople'].')';
				echo '</a></td>';
			} else {
				echo '<tr'; if(fmod($i,2)==1) echo ' bgcolor="#848588"'; echo '><td>&nbsp;</td>'; 
			}
			echo '
				<td>'.$nfrais['prenom'].'</td>
				<td>'.$nfrais['client'].'</td>
				<td>'.$nfrais['secteur'].'</td>
				<td>'.$nfrais['idmission'].'</td>
				<td>'.$nfrais['facnum'].'</td>
				<td>'.fdate($nfrais['datemission']).'</td>
				<td>'.$nfrais['intitule'].'</td>
				<td>'.$nfrais['description'].'</td>
				<td align="center">'.$nfrais['montantfacture'].'</td>
				<td align="center">';
					if($nfrais['montantpaye'] > $nfrais['montantfacture']) echo '<b><font color="#CC0000">'.$nfrais['montantpaye'].'</font></b>';
					else echo $nfrais['montantpaye'];
				echo '
				</td>
				<td><a href="'.substr($path, 4).'"><img border="0" src="'.STATIK.'illus/printer.png" width="16" height="16" alt=""></a></td>
			</tr>';
			$currentpeople = $nfrais['idpeople'];
			$nftotalfac += $nfrais['montantfacture'];
			$nftotalpaye += $nfrais['montantpaye'];
		}
	}
	echo '<tr>
		<td colspan="9" align="right"><strong>Total</strong></td>
		<td align="center"><strong>'.$nftotalfac.'</strong></td>
		<td align="center"><strong>'.$nftotalpaye.'</strong></td>
		</tr>
	</table>';
}
echo '</div>'; ## fin infozone

# Pied de Page
include NIVO."includes/pied.php" ;
?>