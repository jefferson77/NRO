<?php
## init
$lastyear = '';
$years = array();
$thisyear = '20'.substr($_SESSION['table'], -4, 2);
## extrait les années 
foreach ($lestables as $table) $years[] = substr($table, -4, 2);
$years = array_unique($years);

$lesencours = $DB->tableliste("/^salaires[a-z]*".substr($tablesalaires, -4, 4)."$/", 'grps');

?>
<div id="leftmenu">
	<div id="accordion">
<?php

$i = 0;
$cssactive = ' style="background-color: #1D5987; color: #FFF;"';

$lesmois = array(
		'01' => 'Jan',
		'02' => 'Fév',
		'03' => 'Mar',
		'04' => 'Avr',
		'05' => 'Mai',
		'06' => 'Jun',
		'07' => 'Jui',
		'08' => 'Aou',
		'09' => 'Sep',
		'10' => 'Oct',
		'11' => 'Nov',
		'12' => 'Déc',
	);

foreach ($years as $year) {
	$tyears['20'.$year] = $i;
	$i++;
	echo '
	<h3><a href="#">20'.$year.'</a></h3>
    <div style="margin: 0;padding:0">
    	<table class="paymenu" border="0" cellspacing="1" cellpadding="0" align="center" width="100%"><tr>';

			foreach ($lesmois as $nbr => $mois) {
				if ((fmod($nbr, 3) == 1) and ($nbr != '01')) echo '</tr><tr>';
				echo '<td>'.((in_array('salaires'.$year.$nbr, $lestables))?'<a '.((preg_match("/^salaires\D*".$year.$nbr."$/", $tablesalaires))?$cssactive:'').' href="?nt=salaires'.$year.$nbr.'">'.$mois.'</a>':$mois).'</td>';
			}
		
	echo '</tr></table></div>';
} 
?>
</div>
<?php
## bis / ter
if (count($lesencours) > 1) {
	echo '<br><table class="paymenu" border="0" cellspacing="1" cellpadding="0" align="center" width="100%"><tr><th>Envois</th></tr>';
	$s=1;
	foreach ($lesencours as $stable) {
		echo '<tr><td><a href="?nt='.$stable.'" '.(($tablesalaires == $stable)?$cssactive:'').'>ENVOI '.$s.'</a></td></tr>';
		$s++;
	}
	echo'</table>';
}
?>
</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('#accordion').accordion({ active: <?php echo $tyears[$thisyear] ?> });
	});
</script>