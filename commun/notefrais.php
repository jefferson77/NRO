<?php
# Entete de page
define('NIVO', '../');
include NIVO."includes/ifentete.php";
include NIVO."print/commun/notefrais.php";

// secteur : VI, ME, AN (défini dans adminvip.php, adminanim.php, adminmerch.php)
if(isset($_GET['secteur'])) $secteur = $_GET['secteur'];
else $erreur = "Pas de secteur spécifié";

if($secteur=='VI') $infoDB = array('jobtable' => 'vipjob', 'jobfield' => 'idvipjob', 'missiontable' => 'vipmission', 'missionfield'=>'idvip', 'datefield'=>'vipdate');
else if($secteur=='AN') $infoDB = array('jobtable' => 'animjob', 'jobfield' => 'idanimjob', 'missiontable' => 'animation', 'missionfield'=>'idanimation', 'datefield'=>'datem');
else if($secteur=='ME') $infoDB = array('jobtable' => '', 'jobfield' => '', 'missiontable' => 'merch', 'missionfield'=>'idmerch', 'datefield'=>'datem');

$jobtable = $infoDB['jobtable'];
$jobfield = $infoDB['jobfield'];
$missiontable = $infoDB['missiontable'];
$missionfield = $infoDB['missionfield'];
$datefield = $infoDB['datefield'];
// ex. pour VI: $jobfield = 'idvipjob', $$jobfield = 3187
if(isset($_GET[$jobfield])) $$jobfield = $_GET[$jobfield];
else if(isset($_POST[$jobfield])) $$jobfield = $_POST[$jobfield];
if(isset($_GET[$missionfield])) $$missionfield = $_GET[$missionfield];
if(isset($_POST[$missionfield])) $$missionfield = $_POST[$missionfield];

// action sur la note de frais : add, search, show
if(isset($_GET['bdact'])) $action = $_GET['bdact']; 

#Error check
if(isset($erreur)) {
	echo '<b>'.$erreur.'<b>';
} else {
	switch($action) {
		# Ajout d'une note de frais
		case 'add':
		case 'modif':
			# Traite le formulaire
			if(isset($_POST['bdactformcheck'])) {
				if(empty($_POST['intitule'])) $erreur = 'Intitulé manquant.';
				if(empty($_POST['montantfacture'])) $erreur = 'Montant facture manquant.';
			}
			# Affiche le formulaire
			if($erreur OR !isset($_POST['bdactformcheck'])) {
				echo '
				<div align="center">
				<b>'.$erreur.'</b><br/>
				<form name="bdadd" method="post" action="/commun/notefrais.php?bdact='.$action.'&secteur='.$secteur.'&'.$jobfield.'='.$$jobfield.'&'.$missionfield.'='.$$missionfield.'">
				<input type="hidden" name="bdactformcheck" value="1">
				<input type="hidden" name="secteur" value="'.$secteur.'">
				<input type="hidden" name="'.$jobfield.'" value="'.$$jobfield.'">
				<input type="hidden" name="'.$idmission.'" value="'.$$idmission.'">				
				<input type="hidden" name="datemission" value="'.$_POST['datemission'].'">		
				<input type="hidden" name="idnfrais" value="'.$_POST['idnfrais'].'">
				<table border="0" cellspacing="0" cellpadding="0">
				  <tr bgcolor="#cccccc">
					<td>ID job </td>
					<td>'.$$jobfield.' ('.$secteur.')</td>
				  </tr>
				  <tr>
					<td>ID mission </td>
					<td>';						
						#selectionne les idmissions qui n'ont pas encore de note de frais
						$list = $DB->getArray('SELECT '.$missionfield.' FROM '.$missiontable.'
							WHERE '.$jobfield.'='.$$jobfield.'
							AND NOT EXISTS (SELECT * FROM notefrais WHERE notefrais.idmission = '.$missiontable.'.'.$missionfield.')');
							echo '<select name="'.$missionfield.'">
							<option value="">Aucune</option>';
							if(!empty($$missionfield)) echo '<option value="'.$$missionfield.'" selected>'.$$missionfield.'</option>';
							foreach($list as $row) {
								echo '<option value="'.$row[$missionfield].'">'.$row[$missionfield].'</option>';
							}
							echo '</select>';	
				echo '
					</td>
				  </tr>
				  <tr bgcolor="#cccccc">
					<td>Intitul&eacute;</td>
					<td><input type="text" name="intitule" value="'.$_POST['intitule'].'"></td>
				  </tr>
				  <tr>
					<td>Description</td>
					<td><textarea name="description">'.$_POST['description'].'</textarea></td>
				  </tr>
				  <tr bgcolor="#cccccc">
					<td>Montant facture</td>
					<td><input type="text" name="montantfacture" value="'.$_POST['montantfacture'].'"></td>
				  </tr>
				  <tr bgcolor="#cccccc">
					<td>Montant payé</td>
					<td><input type="text" name="montantpaye" value="'.$_POST['montantpaye'].'"></td>
				  </tr>
				  <tr>
					<td colspan="2" align="center"><input type="submit" value="';
					if($action=='add') echo 'Ajouter';
					else if($action=='modif') echo 'Modifier';
					echo '"></td>
					<td>&nbsp;</td>
				  </tr>
				</table>
				</form>
				</div>
				';
				break;
			} else {
				# Insertion DB
				if(!empty($_POST['intitule']) && !empty($_POST['montantfacture'])) {
					if($action=='add') $req = 'INSERT INTO';
					else if($action=='modif') $req = 'UPDATE';
					$req .= ' notefrais SET secteur="'.$_POST['secteur'].'",
							idjob="'.$_POST[$jobfield].'",
							idmission="'.$_POST[$missionfield].'",
							datemission="'.$_POST['datemission'].'",
							intitule="'.cleantext($_POST['intitule']).'",
							description="'.cleantext($_POST['description']).'",
							montantfacture="'.$_POST['montantfacture'].'",
							montantpaye="'.$_POST['montantpaye'].'"';
					if($action=='modif') $req.=' WHERE idnfrais='.$_POST['idnfrais'].' LIMIT 1';
					$DB->inline($req);
				} 
			}
		default:
			# Affiche les notes de frais pour ce job
			$sql = 'SELECT notefrais.*, '.$missiontable.'.'.$datefield.' AS datemission 
									FROM notefrais 
									LEFT JOIN '.$missiontable.' ON '.$missiontable.'.'.$missionfield.'=notefrais.idmission
									WHERE idjob='.$$jobfield;
			$list = $DB->getArray($sql);
			echo '<div align="center"><table border=0 cellspacing=2 cellpadding=0>
			<tr><th>Ref</th><th>ID mission</th><th width="120" align="left">Intitulé</th><th width="200" align="left">Description</th><th>Montant(fac)</th><th>Montant(payé)</th><th>&nbsp;</th></tr>';
			if($list) {
				foreach($list as $row) {
					echo '
					<form method="post" action="/commun/notefrais.php?&'.$jobfield.'='.$$jobfield.'&bdact=modif&secteur='.$secteur.'">
					<input type="hidden" name="idnfrais" value="'.$row['idnfrais'].'">
					<input type="hidden" name="idvip" value="'.$row['idmission'].'">
					<input type="hidden" name="datemission" value="'.$row['datemission'].'">
					<input type="hidden" name="intitule" value="'.$row['intitule'].'">
					<input type="hidden" name="description" value="'.$row['description'].'">
					<input type="hidden" name="montantfacture" value="'.$row['montantfacture'].'">
					<input type="hidden" name="montantpaye" value="'.$row['montantpaye'].'">
					<tr>
						<td>'.$row['idnfrais'].'</td>
						<td>';
						if(!empty($row['idmission'])) echo $row['idmission'];
						$pathnf = print_notefrais($row['idnfrais']);
					echo '
						</td>
						<td>'.$row['intitule'].'</td>
						<td>'.$row['description'].'</td>
						<td>'.$row['montantfacture'].'</td>
						<td>'.$row['montantpaye'].'</td>
						<td><input type="submit" value="M" alt="Modifier"></td>
						<td><a href="'.substr($pathnf,4).'"><img src="'.STATIK.'illus/printer.png" alt="Imprimer" border="0"></a></td>
					</tr>
					</form>';
				}
			}
			echo '</table><br/>
			<br/>
			<a href="#" onClick="javascript:window.open(\'/print/commun/notefraisjob.php?idjob='.$row['idjob'].'&secteur='.$secteur.'\', \'\',\'width=300,height=150,top=300,left=300,resizable=yes\');">
				Imprimer toutes les notes de frais pour ce job
			</a>
			</div>';
			break;
	}
}

# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
