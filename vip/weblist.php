<?php
$classe = "planning"; 

if (!isset($_GET['listing'])) $_GET['listing'] = '';
if (empty($_GET['sort'])) $_GET['sort'] = 'j.datein';

$listing = new db();

## Query
$sql ='SELECT 
	j.idwebvipjob, j.reference, j.etat, j.datein, j.dateout, j.datecommande,
	a.prenom, 
	c.idclient, c.societe AS clsociete 
	FROM webneuro.webvipjob j
	LEFT JOIN agent a ON j.idagent = a.idagent
	LEFT JOIN client c ON j.idclient = c.idclient
	WHERE  j.etat >= 5 AND j.isnew = 0 
	ORDER BY '.$_GET['sort'];

$listing->inline($sql);
?>
<div id="centerzonelarge">
<fieldset>
	<legend>
		<b>listing des VIPs</b>
	</legend>
	<b>Votre Recherche : ( <?php echo mysql_num_rows($listing->result); ?> )</b><br>
</fieldset>
<br>
	<table class="<?php echo $classe; ?>" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=webviplist&sort=j.idwebvipjob">Job</a></th>
			<th class="<?php echo $classe; ?>" colspan="2"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=webviplist&sort=c.idclient">Client</a></th>
			<th class="<?php echo $classe; ?>">R&eacute;f&eacute;rence</th>
			<th class="<?php echo $classe; ?>">Commande</th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=webviplist&sort=j.datein">Date d&eacute;but</a></th>
			<th class="<?php echo $classe; ?>">Date fin</th>
			<th class="<?php echo $classe; ?>">Statut</th>
			<th class="<?php echo $classe; ?>"></th>
		</tr>
<?php
while ($row = mysql_fetch_array($listing->result)) { 
	#> Changement de couleur des lignes #####>>####
	$i++;
	if (fmod($i, 2) == 1) {
		echo '<tr bgcolor="#9CBECA">';
	} else {
		echo '<tr bgcolor="#8CAAB5">';
	}
	#< Changement de couleur des lignes #####<<####
?>
			<td class="<?php echo $classe; ?>"><?php echo $row['idwebvipjob'] ?></td>
			<td class="<?php echo $classe; ?>"><?php echo $row['idclient']; ?></td>
			<td class="<?php echo $classe; ?>"><?php echo $row['clsociete']; ?></td>
			<td class="<?php echo $classe; ?>"><?php echo $row['reference']; ?></td>
			<td class="<?php echo $classe; ?>"><?php echo fdatetime2($row['datecommande']); ?></td>
			<td class="<?php echo $classe; ?>"><?php echo fdate($row['datein']); ?></td>
			<td class="<?php echo $classe; ?>"><?php echo fdate($row['dateout']); ?></td>
			<td class="<?php echo $classe; ?>">
				<?php
				switch ($row['etat']) {
					case "5": 
						echo '<font color="blue"> NEW';
					break;
					case "6": 
						echo '<font color="green"> S By';
					break;
				} 
				?>
					</font>
			</td>
			<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=webshow&idwebvipjob='.$row['idwebvipjob'].'&etat='.$row['etat'];?>"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
		</tr>
<?php } ?>
	</table>
<br>
</div>
