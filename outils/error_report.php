<?php
# Entete de page
define('NIVO', '../');
$Titre = 'Rapport Erreur';
$PhraseBas = 'Rapport erreur';
$Style = 'admin';
#### Classes utilisées ####

# Entete de page
include NIVO."includes/entete.php" ;

$ferr = new db('', '', 'debug');

### Del erreur
if (!empty($_GET['delete'])) {
	$ferr->inline("DELETE FROM errors WHERE iderror = ".$_GET['delete']);
}

$ferr->inline("SELECT * FROM errors ORDER BY page, ligne LIMIT 40");
?>
<style type="text/css">
.rtable th { background-color: #666; color: #FFF; }
.rtable th.pag { background-color: #CCC; color: #000; }
.rtable td {
	background-color: #FC6;
	padding: 0px;
}
</style>


<div id="lmain">

<table cellspacing="1" class="rtable">
	<tr>
		<th>Ligne</th>
		<th>Type</th>
		<th>Description</th>
		<th>Freq</th>
		<th></th>
		<th></th>
	</tr>
	
<?php
$jumper = '';

while ($row = mysql_fetch_array($ferr->result)) {

	if ($jumper != $row['page']) {
		echo '<tr><th colspan="5" class="pag">'.$row['page'].'</th></tr>';
	}

	echo '<tr>
			<td>'.$row['ligne'].'</td>
			<td>'.$row['rtype'].'</td>
			<td>'.$row['description'].'</td>
			<td>'.$row['freq'].'</td>
			<td>bbedit '.$row['page'].':'.$row['ligne'].'</td>
			<td><a href="'.$_SERVER['PHP_SELF'].'?delete='.$row['iderror'].'"><img src="'.STATIK.'illus/bug_delete.png" alt="" width="10" height="10" border="0"></a></td>
		</tr>';

	$jumper = $row['page'];
}

	
?>
</table>

</div><?php
include NIVO."includes/pied.php" ;
?>