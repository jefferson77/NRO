<div id="centerzonelarge">
<?php
$classe = 'standard' ;

### Troisième étape : Afficher les Officers du Client
?>
<h1 align="center">Selection d'un agent pour : <br><?php echo $_POST['societe']; ?></h1>
<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
	<tr>
		<th class="<?php echo $classe; ?>">Nom</th>
		<th class="<?php echo $classe; ?>">Department</th>
		<th class="<?php echo $classe; ?>">Langue</th>
		<th class="<?php echo $classe; ?>">Selection</th>
	</tr>
<?php
$idclient = $_POST['idclient'];
$client = new db();
$client->inline("SELECT * FROM `cofficer` WHERE `idclient` = '$idclient'");

while ($row = mysql_fetch_array($client->result)) { 
?>
	<tr>
		<td class="<?php echo $classe; ?>"><?php echo $row['qualite'].' '.$row['onom'].' '.$row['oprenom']  ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['departement']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['langue']; ?></td>
		<td class="<?php echo $classe; ?>">
			<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=client';?>" method="post">
				<input type="hidden" name="idmerch" value="<?php echo $idmerch ;?>"> 
				<input type="hidden" name="idclient" value="<?php echo $row['idclient'] ;?>"> 
				<input type="hidden" name="idcofficer" value="<?php echo $row['idcofficer'] ;?>"> 
				<input type="submit" name="Selectionner" value="Selectionner">
			</form>
		</td>
	</tr>
<?php 
} 
?>
</table>
</div>
