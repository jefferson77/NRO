<div id="centerzonelarge">
<?php
### Deuxième étape : Afficher la liste des clients correspondant a la recherche
$classe = 'standard' ;


# VARIABLE skip
if (!empty($_GET['skip'])) {
	$skip=$_GET['skip'];
}
else {$skip=0;}
$skipprev = $skip - 35;
$skipnext = $skip + 35;
# VARIABLE SELECT
if (!empty($_POST['codeclient'])) {
	$quid .= "codeclient = ".$_POST['codeclient'];
	$quod .= "codeclient = ".$_POST['codeclient'];
}
if (!empty($_POST['societe'])) {
	if (!empty($quid)) 
	{
		$quid .= " AND ";
		$quod .= " ET ";
	}	
	$quid .= "societe LIKE '%".$_POST['societe']."%'";
	$quod .= "societe = ".$_POST['societe'];
}
if (!empty($_POST['cp'])) {
	if (!empty($quid)) 
	{
		$quid .= " AND ";
		$quod .= " ET ";
	}	
	$quid .= "cp LIKE '%".$_POST['cp']."%'";
	$quod .= "cp = ".$_POST['cp'];
}
if (!empty($_POST['ville'])) {
	if (!empty($quid)) 
	{
		$quid .= " AND ";
		$quod .= " ET ";
	}	
	$quid .= "ville LIKE '%".$_POST['ville']."%'";
	$quod .= "ville = ".$_POST['ville'];
}
if (!empty($_POST['etat'])) {
	if (!empty($quid)) 
	{
		$quid .= " AND ";
		$quod .= " ET ";
	}	
	$quid .= "etat = ".$_POST['etat'];
	$quod .= "etat = ".$_POST['etat'];
}
#	# ATTENTION POUR TEXTE : LIKE '%XXXX%'

if (!empty($quid)) {$quid='WHERE '.$quid;}
if ($sort == '') {$sort='codeclient';}
$recherche='
	SELECT * FROM client 
	'.$quid.'
	 ORDER BY '.$sort.'
	 LIMIT '.$skip.', 35
';
$_SESSION['clientquid'] = $quid;
$_SESSION['clientquod'] = $quod;
$_SESSION['clientsort'] = $sort;

?>
	<h1 align="center">S&eacute;lection d'un Client</h1>
	<b>Votre Recherche <br><?php echo $_SESSION['clientquod']?></b><br>
	<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$idmerch.'&sel=client';?>">Retour &agrave; la recherche</a><br>
	<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="<?php echo $classe; ?>">Code Client</th>
			<th class="<?php echo $classe; ?>">Client</th>
			<th class="<?php echo $classe; ?>">CP</th>
			<th class="<?php echo $classe; ?>">Ville</th>
			<th class="<?php echo $classe; ?>">Etat</th>
			<th class="<?php echo $classe; ?>">Selection</th>
		</tr>
<?php
$client = new db();
$client->inline("$recherche;");

while ($row = mysql_fetch_array($client->result)) {
?>
	<tr>
		<td class="<?php echo $classe; ?>"><?php echo $row['codeclient'] ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['societe']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['cp']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['ville']; ?></td>
		<td class="<?php echo $classe; ?>">
			<?php
			switch ($row['etat']) {
				case "0": 
					echo '<font color="red"> Out';
				break;
				case "5": 
					echo '<font color="green"> In';
				break;
			} 
			?>
			</font>
		</td>
		<td class="<?php echo $classe; ?>">
			<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$idmerch.'&sel=client&etape=listeofficers';?>" method="post">
				<input type="hidden" name="etape" value="listeofficers"> 
				<input type="hidden" name="idclient" value="<?php echo $row['idclient'] ;?>"> 
				<input type="hidden" name="societe" value="<?php echo $row['societe'] ;?>"> 
				<input type="submit" name="Selectionner" value="Selectionner"> 
			</form>
		</td>
	</tr>
<?php 
} 
?>
</table>
</div>