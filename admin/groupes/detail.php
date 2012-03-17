<div id="leftmenu">

</div>

<div id="infozone">
<?php

$periodes = array(
	'2004-01-01',
	'2004-02-01',
	'2004-03-01',
	'2004-04-01',
	'2004-05-01',
	'2004-06-01',
	'2004-07-01',
	'2004-08-01',
	'2004-09-01',
	'2004-10-01',
	'2004-11-01',
	'2004-12-01'
) ;



### Infos PTG
echo '
<form action="'.$_SERVER['PHP_SELF'].'?act=detail&idptg='.$_GET['idptg'].'" method="post">
	<table border="0" width="95%" cellspacing="1" cellpadding="1" align="center" bgcolor="#003333">
		<tr>
			<td bgcolor="#006666"><b>ID</b>: '.$infosptg['idptg'].'</td>
			<td bgcolor="#006666"><input type="text" name="nomptg" value="'.$infosptg['nomptg'].'" size="50"></td>
			<td bgcolor="#006666"><b>PERIODE</b><select name="datein">';
setlocale(LC_TIME, "fr");

foreach ($periodes as $value) {
	echo '<option value="'.$value.'"';	
	if ($infosptg['datein'] == $value) {echo 'selected';}
	
	$periode = strftime("%Y %B", strtotime($value));

	echo '>'.$periode.'</option>';
}
echo'</select></td>
			<td bgcolor="#006666"><b>ENVOYE</b>: '.fdate($infosptg['datesend']).'</td>
			<td bgcolor="#006666">
				<input type="hidden" name="idptg" value="'.$infosptg['idptg'].'">';
?>				<input type="submit" name="modinf" value="Infos">
			</td>
		</tr>
	</table>
</form>
<br>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top">
<fieldset>
<legend>People en payement en <?php echo strftime("%Y %B", strtotime($infosptg['datein']));?></legend>
<table class="<?php echo $classe; ?>" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
<?php
if ($_GET['dskip'] > 0) {
	$from = $_GET['dskip'];
} else {
	$from = 0;
}

$lister = 25;

$dskip = $from + $lister;
$rwd = $from - $lister;

$sql = "
SELECT s.idpeople, p.pnom, p.pprenom, p.codepeople, COUNT(s.date) AS nbjours 
FROM grps.custom009 s
LEFT JOIN neuro.people p ON s.idpeople = p.idpeople
WHERE s.idptg = ".$infosptg['idptg']."
GROUP BY s.idpeople
ORDER BY p.codepeople
LIMIT $from, $lister";

$afficher = new db('', '', 'grps');
$afficher->inline($sql);

$sql = "SELECT COUNT(DISTINCT idpeople) FROM `custom009` WHERE idptg = ".$infosptg['idptg'];

$compter = new db('', '', 'grps');
$compter->inline($sql);
$Count = mysql_result($compter->result,0); 

	echo '
	<tr align="center">
		<th></th>
		<th align="left">Registre</th>
		<th align="left">Nom</th>
		<th>ID</th>
		<th>Nombre de jours</th>
		<th></th>
	</tr>';

while ($row = mysql_fetch_array($afficher->result)) {
		#> Changement de couleur des lignes #####>>####
		$i++;
		if (fmod($i, 2) == 1) {
			echo '<tr bgcolor="#9CBECA" align="right">';
		} else {
			echo '<tr bgcolor="#8CAAB5" align="right">';
		}
		#< Changement de couleur des lignes #####<<####
	echo '
		<td align="center"><a href="'.$_SERVER['PHP_SELF'].'?act=mod009&idptg='.$infosptg['idptg'].'&idpeople='.$row['idpeople'].'&dskip='.$from.'"><font color="#006600">Modif</font></a></td>
		<td align="left">'.$row['codepeople'].'</td>
		<td align="left">'.$row['pprenom'].' '.$row['pnom'].'</td>
		<td>'.$row['idpeople'].'</td>
		<td>'.$row['nbjours'].'</td>
		<td align="center"><a href="'.$_SERVER['PHP_SELF'].'?act=detail&idptg='.$infosptg['idptg'].'&ptgact=sup&idpeople='.$row['idpeople'].'&dskip='.$from.'"><font color="#CC0000">Suppr</font></a></td>
	</tr>';
}
?>
</table>
</fieldset>
		</td>
		<td width="100" valign="top" align="center">
<fieldset>
<legend>Ajouter un people</legend>
	<form action="<?php echo $_SERVER['PHP_SELF'].'?act=detail&idptg='.$infosptg['idptg'].'&skip='.$_GET['skip'].'&dskip='.$from;?>" method="post">
	
	<input type="text" name="numreg" size="5">
	<input type="submit" name="ptgact" value="add"><br>
	Entrez le num&eacute;ro de registre du people a ajouter aux paiements
	</form>
</fieldset>
		</td>
	</tr>
</table>


</div>


<div id="infobouton">

</div>
