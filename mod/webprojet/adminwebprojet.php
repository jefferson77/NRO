<?php
define('NIVO', '../../'); 

# Entete
$Titre = 'WEB PROJET';
$PhraseBas = 'Liste des projet';
include NIVO."includes/entete.php" ;

?>
<div id="leftmenu"></div>
<div id="infozone">
<!-- INFO GENERALES --> 
	<h1 align="center">Gestion des Web projet</h1>
<?php
## Suppression de la ligne #############
if ($_GET['act'] == 'del') {

	$efface = new db('webprojet', 'idwebprojet', 'webneuro'); #défini la table et le nom du champ ID
	$efface->EFFACE($_GET['idwebprojet']);	# efface la fiche de cet ID
	
	echo '<div class="dbaction">projet n&deg; '.$id.' effac&eacute;e</div><br>';
}

## Ajout de la ligne #############
if ($_POST['act'] == 'Ajouter') {
	$_POST['date1'] = fdatebk($_POST['date1']);
	$ajout = new db('webprojet', 'idwebprojet', 'webneuro');
	$ajout->AJOUTE(array('date1', 'titrefr', 'textefr', 'titrenl', 'textenl', 'online'));

	echo '<div class="dbaction">projet ajout&eacute;e</div><br>';
}

## Modification de la ligne #############
if ($_POST['act'] == 'Modifier') {
	$_POST['date1'] = fdatebk($_POST['date1']);
	$_POST['date2'] = fdatebk($_POST['date2']);
	$modif = new db('webprojet', 'idwebprojet', 'webneuro');
	$modif->MODIFIE($_POST['idwebprojet'], array('date1', 'date2', 'titrefr', 'textefr', 'titrenl', 'textenl', 'online'));
	
	echo '<div class="dbaction">Fiche '.$idwebprojet.' mise &agrave; jour</div><br>';
}

?>
<?php $classe = "standard" ?>
	<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="<?php echo $classe; ?>">Date</th>
			<th class="<?php echo $classe; ?>">projet</th>
			<th class="<?php echo $classe; ?>">Online ?</th>
			<th class="<?php echo $classe; ?>">Modifier</th>
		</tr>
<?php
# Recherche des 10 projet
$projet = new db('webprojet', 'idwebprojet', 'webneuro');
$projet->inline("SELECT * FROM `webprojet` ORDER BY 'section' ASC, 'date1' DESC LIMIT 0, 15");
$section='0';
while ($row = mysql_fetch_array($projet->result)) { 
		#> Changement de couleur des lignes #####>>####
		if (($section != $row['section']) and ($row['section'] == 1)) { echo '<tr><td><b>VIP</b></td></tr>'; $section = $row['section'];}
		if (($section != $row['section']) and ($row['section'] == 2)) { echo '<tr><td><b>ANIM</b></td></tr>'; $section = $row['section'];}
		if (($section != $row['section']) and ($row['section'] == 3)) { echo '<tr><td><b>MERCH</b></td></tr>'; $section = $row['section'];}
		$i++;
		if (fmod($i, 2) == 1) {
			echo '<tr bgcolor="#9CBECA">';
		} else {
			echo '<tr bgcolor="#8CAAB5">';
		}
		#< Changement de couleur des lignes #####<<####
?>
			<td class="<?php echo $classe; ?>"><?php echo fdate($row['date1']); ?> - <?php echo fdate($row['date2']); ?></td>
			<td class="<?php echo $classe; ?>" align="left"><?php echo $row['titrefr']; ?></td>
			<td class="<?php echo $classe; ?>"><?php echo $row['online']; ?></td>
			<td class="<?php echo $classe; ?>"><a href="<?php echo 'modifwebprojet.php?idwebprojet='.$row['idwebprojet'];?>">Modifier</a></td>
		</tr>
<?php } ?>
	</table>
	<br>
	</div>

<?php
# Pied de Page
include NIVO."includes/pied.php" ;
?>
