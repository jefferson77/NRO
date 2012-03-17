<?php
# Recherche des résultats
$row = $DB->getRow('SELECT 
		GROUP_CONCAT(DISTINCT datem) as gdatem, 
		GROUP_CONCAT(DISTINCT frais) as gfrais, 
		GROUP_CONCAT(DISTINCT fraisfacture) as gfraisfacture, 
		GROUP_CONCAT(DISTINCT hin1) as ghin1, 
		GROUP_CONCAT(DISTINCT hin2) as ghin2, 
		GROUP_CONCAT(DISTINCT hout1) as ghout1, 
		GROUP_CONCAT(DISTINCT hout2) as ghout2, 
		GROUP_CONCAT(DISTINCT kmauto) as gkmauto, 
		GROUP_CONCAT(DISTINCT kmfacture) as gkmfacture, 
		GROUP_CONCAT(DISTINCT kmpaye) as gkmpaye, 
		GROUP_CONCAT(DISTINCT livraisonfacture) as glivraisonfacture, 
		GROUP_CONCAT(DISTINCT livraisonpaye) as glivraisonpaye, 
		GROUP_CONCAT(DISTINCT produit  SEPARATOR "|") as gproduit, 
		GROUP_CONCAT(DISTINCT idanimation) as gidanimation, 
		GROUP_CONCAT(DISTINCT idanimjob) as gidanimjob
	FROM animation
	WHERE idanimation IN ('.implode(", ", $_POST['idtoupdate']).')
		AND (facturation < 5 OR facturation IS NULL)
	GROUP BY idanimjob');

?>
<style type="text/css" media="screen">
	th {
		text-align: center;
		background-color: #788599;
		color: #FFF;
	}
	td {
		text-align: center;
	}
</style>
<br>
<form action="?act=updatemodif" method="post">
	<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'] ;?>">
	<input type="hidden" name="updateids" value="<?php echo $row['gidanimation'] ;?>">
	<table border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<td class="etiq2" colspan="13"><b>Update Missions - S&eacute;l&eacute;ction des champs (pour <?php echo count(explode(",", $row['gidanimation']));?> missions)</b></td>
		</tr>
		<tr>
			<th>Date</th>
			<th>Produit</th>
			<th>Am In</th>
			<th>Am Out</th>
			<th>Pm In</th>
			<th>Pm Out</th>
			<th>KM AUTO</th>
			<th>KM P</th>
			<th>KM F</th>
			<th>Fra P</th>
			<th>Fra F</th>
			<th>Liv P</th>
			<th>Liv F</th>
		</tr>
		<tr>
			<td><input type="text" size="9" name="datem" value="<?php echo (strstr($row['gdatem'], ','))?'':fdate($row['gdatem']); ?>"></td>
			<td><input type="text" size="25" name="produit" value="<?php echo (strstr($row['gproduit'], '|'))?'':$row['gproduit']; ?>"></td>
			<td><input type="text" size="3" name="hin1" value="<?php echo (strstr($row['ghin1'], ','))?'':ftime($row['ghin1']); ?>"></td>
			<td><input type="text" size="3" name="hout1" value="<?php echo (strstr($row['ghout1'], ','))?'':ftime($row['ghout1']); ?>"></td>
			<td><input type="text" size="3" name="hin2" value="<?php echo (strstr($row['ghin2'], ','))?'':ftime($row['ghin2']); ?>"></td>
			<td><input type="text" size="3" name="hout2" value="<?php echo (strstr($row['ghout2'], ','))?'':ftime($row['ghout2']); ?>"></td>
			<td><input type="checkbox" name="kmauto" value="Y" <?php echo ($row['gkmauto'] == 'Y')?' checked':''; ?>></td>
			<td><input type="text" size="3" name="kmpaye" value="<?php echo (strstr($row['gkmpaye'], ','))?'':fnbr($row['gkmpaye']); ?>"></td>
			<td><input type="text" size="3" name="kmfacture" value="<?php echo (strstr($row['gkmfacture'], ','))?'':fnbr($row['gkmfacture']); ?>"></td>
			<td><input type="text" size="3" name="frais" value="<?php echo (strstr($row['gfrais'], ','))?'':fnbr($row['gfrais']); ?>"></td>
			<td><input type="text" size="3" name="fraisfacture" value="<?php echo (strstr($row['gfraisfacture'], ','))?'':fnbr($row['gfraisfacture']); ?>"></td>
			<td><input type="text" size="3" name="livraisonpaye" value="<?php echo (strstr($row['glivraisonpaye'], ','))?'':fnbr($row['glivraisonpaye']); ?>"></td>
			<td><input type="text" size="3" name="livraisonfacture" value="<?php echo (strstr($row['glivraisonfacture'], ','))?'':fnbr($row['glivraisonfacture']); ?>"></td>
		</tr>
		<tr>
			<th><input type="checkbox" name="fieldtoupdate[]" value="datem"></th>
			<th><input type="checkbox" name="fieldtoupdate[]" value="produit"></th>
			<th><input type="checkbox" name="fieldtoupdate[]" value="hin1"></th>
			<th><input type="checkbox" name="fieldtoupdate[]" value="hout1"></th>
			<th><input type="checkbox" name="fieldtoupdate[]" value="hin2"></th>
			<th><input type="checkbox" name="fieldtoupdate[]" value="hout2"></th>
			<th><input type="checkbox" name="fieldtoupdate[]" value="kmauto"></th>
			<th><input type="checkbox" name="fieldtoupdate[]" value="kmpaye"></th>
			<th><input type="checkbox" name="fieldtoupdate[]" value="kmfacture"></th>
			<th><input type="checkbox" name="fieldtoupdate[]" value="frais"></th>
			<th><input type="checkbox" name="fieldtoupdate[]" value="fraisfacture"></th>
			<th><input type="checkbox" name="fieldtoupdate[]" value="livraisonpaye"></th>
			<th><input type="checkbox" name="fieldtoupdate[]" value="livraisonfacture"></th>
		</tr>
		<tr>
			<td class="etiq2" colspan="13"><input type="submit" name="Modifier" value="Mettre &agrave; jour"></td>
		</tr>
	</table>
</form>
	