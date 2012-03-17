<?php
define('NIVO', '../../'); 

# Entete de page
$Titre = 'Logins';
$PhraseBas = 'Statistiques des logins';
$Style = 'admin';
include NIVO."includes/entete.php" ;

$classe = "standard";

?>
<div id="leftmenu"></div>
<style type="text/css" title="text/css">
<!--
table.newtable { 
	background-color: #000;
	color: #000;
}
table.newtable td { 
	padding: 5px;
}
table.newtable tr.tr1
{
	background-color: #BBC0DD;
	text-align: center;
}
table.newtable tr.tr2 { 
	background-color: #C1C5D7;
	text-align: center;
}
table.newtable th { 
	padding: 5px;
	background-color: #7983A9;
}
-->
</style>

<div id="infozone">
<h1 align="center">Tableau des logs</h1>
<table align="center" class="newtable" cellspacing="1">
	<tr>
		<th>Nom</th>
		<th>INT</th>
		<th>EXT</th>
	</tr>
<?php
###################### Recherche des stats de logins ###########
$sql = "SELECT l.idagent, a.nom, a.prenom, l.idmachine, m.description, COUNT(l.idlog) as nbr FROM logins l LEFT JOIN agent a ON l.idagent = a.idagent LEFT JOIN machine m ON l.idmachine = m.idmachine WHERE l.logdate BETWEEN '2005-01-01' AND '2005-04-01' GROUP BY l.idmachine ORDER BY l.idagent ASC, l.idmachine ASC";
$searchlog = new db();
$searchlog->inline($sql);

while ($row = mysql_fetch_array($searchlog->result)) {
	if (($row['idagent'] != $oldidagent) and (!empty($oldidagent))) {
	$i++;
	
	if (fmod($i, 2) == 1) { $trcol = 'tr2' ;} else { $trcol = 'tr1' ;}
		echo '
		<tr class="'.$trcol.'">
			<td><b>'.$nom.'</b></td>
			<td>'.$INTcount.'</td>
			<td>'.$EXTcount.'</td>
		</tr>';
		
		unset($INTcount);
		unset($EXTcount);
		unset($nom);
	}

	$nom = $row['prenom'].' '.$row['nom'];
	
	if (strstr($row['description'],"INT")) {
		$INTcount += $row['nbr'];
	}
	
	if ((strstr($row['description'],"EXT")) or (strstr($row['description'],"CEL"))) {
		$EXTcount += $row['nbr'];
	}
	
	
	$oldidagent = $row['idagent'];

}
	# Afficher le dernier
		echo '
		<tr class="'.$trcol.'">
			<td><b>'.$nom.'</b></td>
			<td>'.$INTcount.'</td>
			<td>'.$EXTcount.'</td>
		</tr>';
	

?>
</table



</div>
<div id="infobouton">

</div>

<?php
	include NIVO."includes/pied.php" ;
?>