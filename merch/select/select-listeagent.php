<div id="centerzonelarge">
<?php
$classe = 'standard' ;
### Deuxième étape : Afficher la liste des Agents correspondant a la recherche
?>
<h1 align="center">S&eacute;lection des Agents</h1>
<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$idmerch.'&sel=agent';?>">Retour &agrave; la recherche</a><br>
<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
	<tr>
		<th class="<?php echo $classe; ?>">Nom</th>
		<th class="<?php echo $classe; ?>">Pr&eacute;nom</th>
		<th class="<?php echo $classe; ?>">Selection</th>
	</tr>
<?php
# Recherche des 15 clients
$searchagent = $_POST['searchagent'];
$agent = new db();
$agent->inline("SELECT * FROM `agent` WHERE `prenom` LIKE '%$searchagent%'");

while ($row = mysql_fetch_array($agent->result)) { 
?>
		<tr>
			<td class="<?php echo $classe; ?>"><?php echo $row['nom']; ?></td>
			<td class="<?php echo $classe; ?>"><?php echo $row['prenom']; ?></td>
			<td class="<?php echo $classe; ?>">
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=agent';?>" method="post">
					<input type="hidden" name="idmerch" value="<?php echo $idmerch ;?>"> 
					<input type="hidden" name="idagent" value="<?php echo $row['idagent'] ;?>"> 
					<input type="submit" name="Selectionner" value="Selectionner"> 
				</form>
			</td>
		</tr>
<?php 
} 
?>
</table>
