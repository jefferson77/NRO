<?php
# Entete de page
define('NIVO', '../../'); 
$Titre = 'WEB NEWS';
$PhraseBas = 'Liste des News';
$Style = 'vip';

# Entete
include NIVO."includes/entete.php" ;

 #  Menu de gauche ?>
<div id="leftmenu">

</div>
<?php #  Corps de la Page ?>
<div id="infozone">
<!-- INFO GENERALES --> 
	<h1 align="center">Gestion des Web News PEOPLE</h1>
<?php
## Suppression de la ligne #############
if ($_GET['act'] == 'del') {

	$efface = new db('webnewspeople', 'idwebnewspeople', 'webneuro'); #défini la table et le nom du champ ID
	$efface->EFFACE($_GET['idwebnewspeople']);	# efface la fiche de cet ID
	
	echo '<div class="dbaction">News n&deg; '.$_GET['idwebnewspeople'].' effac&eacute;e</div><br>';
}

## Ajout de la ligne #############
if ($_POST['act'] == 'Ajouter') {
	$_POST['datepublic'] = fdatebk($_POST['datepublic']);
	$ajout = new db('webnewspeople', 'idwebnewspeople', 'webneuro');
	$ajout->AJOUTE(array('datepublic', 'titrefr', 'textefr', 'titrenl', 'textenl', 'online', 'urgent'));

	echo '<div class="dbaction">News ajout&eacute;e</div><br>';
}

## Modification de la ligne #############
if ($_POST['act'] == 'Modifier') {
	$_POST['datepublic'] = fdatebk($_POST['datepublic']);
	$modif = new db('webnewspeople', 'idwebnewspeople', 'webneuro');
	$modif->MODIFIE($_POST['idwebnewspeople'], array('datepublic', 'titrefr', 'textefr', 'titrenl', 'textenl', 'online', 'urgent'));
	
	echo '<div class="dbaction">Fiche '.$idwebnewspeople.' mise &agrave; jour</div><br>';
}

?>
<?php $classe = "standard" ?>
	<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<td align="center"><a href="modifwebnewspeople.php">Ajouter</a></td>
		</tr>
	</table>
	<br>
	<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="<?php echo $classe; ?>">Urgent</th>
			<th class="<?php echo $classe; ?>">Date</th>
			<th class="<?php echo $classe; ?>">News</th>
			<th class="<?php echo $classe; ?>">Online ?</th>
			<th class="<?php echo $classe; ?>">Modifier</th>
			<th class="<?php echo $classe; ?>">Supprimer</th>
		</tr>
<?php
# Recherche des 10 News
$news = new db('webnewspeople', 'idwebnewspeople', 'webneuro');
$news->inline("SELECT * FROM `webnewspeople` ORDER BY idwebnewspeople DESC LIMIT 0, 15");

while ($row = mysql_fetch_array($news->result)) { 
		#> Changement de couleur des lignes #####>>####
		$i++;
		if (fmod($i, 2) == 1) {
			echo '<tr bgcolor="#9CBECA">';
		} else {
			echo '<tr bgcolor="#8CAAB5">';
		}
		#< Changement de couleur des lignes #####<<####
?>
			<td class="<?php echo $classe; ?>"><?php if ($row['urgent'] == 1) {echo '<img src="'.STATIK.'illus/100light.gif" alt="100light.gif" width="16" height="16" border="0" align="bottom">'; } ?></td>
			<td class="<?php echo $classe; ?>"><?php echo fdate($row['datepublic']); ?></td>
			<td class="<?php echo $classe; ?>" align="left"><?php echo $row['titrefr']; ?></td>
			<td class="<?php echo $classe; ?>"><?php echo $row['online']; ?></td>
			<td class="<?php echo $classe; ?>"><a href="<?php echo 'modifwebnewspeople.php?idwebnewspeople='.$row['idwebnewspeople'];?>">Modifier</a></td>
			<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=del&idwebnewspeople='.$row['idwebnewspeople'];?>">Supprimer</a></td>
		</tr>
<?php } ?>
	</table>
	<br>
	<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<td align="center"><a href="modifwebnewspeople.php">Ajouter</a></td>
		</tr>
	</table>
	</div>

<?php
# Pied de Page
include NIVO."includes/pied.php" ;
?>
