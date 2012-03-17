<?php
# Entete de page
define('NIVO', '../../'); 
$Titre = 'NEWS';
$PhraseBas = 'Liste des News';
$Style = 'admin';

# Classes utilisées

# Entete
include NIVO."includes/entete.php" ;

 #  Menu de gauche ?>
<div id="leftmenu">

</div>
<?php #  Corps de la Page ?>
<div id="infozone">
<!-- INFO GENERALES --> 
	<h1 align="center">Gestion des News</h1>
<?php
## Suppression de la ligne #############
if ($_GET['act'] == 'del') {

	$efface = new db('news', 'id'); #défini la table et le nom du champ ID
	$efface->EFFACE($_GET['id']);	# efface la fiche de cet ID
	
	echo '<div class="dbaction">News n&deg; '.$id.' effac&eacute;e</div><br>';
}

## Ajout de la ligne #############
if ($_POST['act'] == 'Ajouter') {

	$ajout = new db('news', 'id');
	$ajout->AJOUTE(array('ndate', 'description', 'newspage'));

	echo '<div class="dbaction">News ajout&eacute;e</div><br>';
}

## Modification de la ligne #############
if ($_POST['act'] == 'Modifier') {
	$DB->MAJ('news');
	echo '<div class="dbaction">Fiche '.$id.' mise &agrave; jour</div><br>';
}

?>
<?php $classe = "standard" ?>
	<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<td align="center"><a href="modifnews.php">Ajouter</a></td>
		</tr>
	</table>
	<br>
	<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="<?php echo $classe; ?>">Table</th>
			<th class="<?php echo $classe; ?>">Date</th>
			<th class="<?php echo $classe; ?>">News</th>
			<th class="<?php echo $classe; ?>">Modifier</th>
			<th class="<?php echo $classe; ?>">Supprimer</th>
		</tr>
<?php
# Recherche des 10 News
$news = new db();
$news->inline("SELECT * FROM `news` ORDER BY id DESC LIMIT 0, 15");

while ($row = mysql_fetch_array($news->result)) { 
	$stuckdate = explode ('-', $row['ndate']);
	$ldate = $stuckdate[2].'/'.$stuckdate[1].'/'.$stuckdate[0];
	
	$rog = explode ('%%', $row['newspage']);
	$roger = array_shift ($rog); 
	foreach ($rog as $value) {
		if (!empty($value)) {
			$roger .= ', '.$value;
		}
	}
	
?>
		<tr>
			<td class="<?php echo $classe; ?>"><?php echo $roger; ?></td>
			<td class="<?php echo $classe; ?>"><?php echo $ldate; ?></td>
			<td class="<?php echo $classe; ?>" align="left"><?php echo $row['description']; ?></td>
			<td class="<?php echo $classe; ?>"><a href="<?php echo 'modifnews.php?id='.$row['id'];?>">Modifier</a></td>
			<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=del&id='.$row['id'];?>">Supprimer</a></td>
		</tr>
<?php } ?>
	</table>
	<br>
	<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<td align="center"><a href="modifnews.php">Ajouter</a></td>
		</tr>
	</table>
	</div>

<?php
# Pied de Page
include NIVO."includes/pied.php" ;
?>
