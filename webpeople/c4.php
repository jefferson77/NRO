<div class="news">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="newstitinfo"><?php echo $contrat_09;?></td>
		</tr>
		<tr>
			<td class="fulltitre" colspan="2"><?php echo $tool_01;?></td>
		</tr>
		<tr>
			<td class="newstit"><?php echo $tool_09;?></td>
		</tr>
		<tr>
			<td class="newstxt"><?php echo $contrat_02;?><br><?php echo $contrat_03;?><br><?php echo $contrat_04;?></td>
		</tr>
		<tr>
			<td class="newstit"><?php echo $tool_10;?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<input type="checkbox" name="exemple" checked> : <?php echo $contrat_06;?><br>
				<img src="<?php echo STATIK ?>illus/printer.png" width="16" height="16"> : <?php echo $contrat_07;?><br>
				<img src="<?php echo STATIK ?>illus/minipdf.gif" width="16" height="16"> : <?php echo $contrat_08;?>
			</td>
		</tr>
	</table>
</div>

<div class="corps">
<?php $classe = "planning";

include_once(NIVO."classes/ptg.php");
include_once(NIVO."classes/payement.php");

$nreg = new db();

$ptable = paytable ($_SESSION['idpeople'], '2003-09-01', $nreg->CONFIG('lastpayement'));
$dtable = array_shift($ptable);

# tri de la table du plus récent au plus vieux
if (is_array($dtable)) {
?>
<form action="c4print.php" method="post" target="popup" onsubmit="OpenBrWindow('_blank','popup','scrollbars=yes,status=yes,resizable=yes','200','400','true')" >
<div align="center">
<input type="submit" class="btn printer">
</div>
<?php
$t = 3;
$lastyear = 'first';
$b = 0;
foreach ($dtable as $key => $value) {
	$sqldate = substr($key, 0, 4).'-'.substr($key, 4, 2).'-'.substr($key, 6, 2);
	$year = substr($key, 0, 4);
	$mois = substr($key, 4, 2);

	if ($_SESSION['lang'] == 'nl') {
		setlocale(LC_TIME, 'nl_NL');
	} else {
		setlocale(LC_TIME, 'fr_FR');
	}
	$ladate = strftime("%a %d/%m/%Y", mktime (0,0,0,substr($key, 4, 2),substr($key, 6, 2),substr($key, 0, 4)));
	$lemois = strftime("%B", mktime (0,0,0,substr($key, 4, 2),substr($key, 6, 2),substr($key, 0, 4)));


	if ($year != $lastyear) {
		$b = 0;

		if ($lastyear != 'first') {
?>
</table>
</td>
<?php
for ($v = fmod($b, $t) + 1; $v < $t; $v++) {
	echo '<td width="33%" bgcolor="#EEEEEE"></td>';
}
?>
	</tr>
	</table>
</fieldset>
<?php
		}

?>
<Fieldset class="width">
	<legend class="width">
		<b><?php echo $year;?></b>
	</legend>
	<table class="<?php echo $classe; ?>" border="0" cellspacing="5" cellpadding="5" align="center"  width="98%">
<tr>
<td width="33%" align="center" valign="top" bgcolor="#EEEEEE">
<?php
	$lastmois = 'first';
	}
	if ($mois != $lastmois) {
		if ($lastmois != 'first') {
?>
	</table>
	</td>
<?php if (fmod($b, $t) == 0) echo '</tr><tr>';?>
	<td width="33%" align="center" valign="top" bgcolor="#EEEEEE">
<?php
		}
?>
	<table border="0" cellspacing="1" cellpadding="1" width="150">
		<tr>
			<td class="tabtitre" colspan="2"><?php echo $lemois ;?></td>
		</tr>

<?php
		$b++;

	}
	#> Changement de couleur des lignes #####>>####
	$i++;
	if (fmod($i, 2) == 1) {
		echo '<tr bgcolor="#78ABD7">';
	} else {
		echo '<tr bgcolor="#97B4CD">';
	}
	#< Changement de couleur des lignes #####<<####
?>
			<td class="line"><?php echo $ladate ?></td>
			<td class="line" width="15"><input type="checkbox" name="print[]" value="<?php echo $key; ?>"></td>
		</tr>
<?php
	$lastyear = $year;
	$lastmois = $mois;
}
?>
	</table>	</td>

<?php
for ($v = fmod($b, $t); $v < $t; $v++) {
	echo '<td width="33%" bgcolor="#EEEEEE"></td>';
}
?>
	</tr>
	</table>
</fieldset>
</form>
<?php } ?>
</div>