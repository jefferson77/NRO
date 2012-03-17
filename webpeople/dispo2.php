<?php
###############
$nbrmois = 2; # Nombre de mois d'avance ou l'on peut envoyer ses dispo
$on      = "#00CC00";
$off     = "#AAAAAA";
$work    = "#FF9900";
$loclang = $_SESSION['lang'];

include NIVO.'classes/hh.php';

###############
if ($titrepage != 'people2') {
$idpeople = $_SESSION['idpeople'];
?>
<div class="news">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="fulltitre" colspan="2"><?php echo $tool_01; ?></td>
		</tr>
		<tr>
			<td class="newstit"><?php echo $tool_11; ?></td>
		</tr>
		<tr>
			<td class="newstxt"><?php echo $dispo_01; ?></td>
		</tr>
		<tr>
			<td class="newstit"><?php echo $tool_10; ?></td>
		</tr>
		<tr>
			<td class="newstxt"><img src="/web/illus/legend-<?php echo $loclang; ?>.gif" alt="legend.gif" width="172" height="111" border="0" align="bottom"></td>
		</tr>
	</table>
</div>
<div class="corps">
<?php } else {
	$idpeople = $_REQUEST['idpeople'];
}

switch ($_GET['mode']) {
		case "mod":
			if ($_GET['nmonth'] > 0) {
#######################################
###### Ajout du mois en dispo    ##########################################################################################
#######################################
				$sql = "SELECT * FROM `disponib` WHERE `idboy` = '".$idpeople."' AND `annee` = '".substr($_GET['nmonth'], 0, 4)."' AND `mois` = '".substr($_GET['nmonth'], 4, 2)."'";
				$findexist = new db();
				$findexist->inline($sql);

				if (mysql_num_rows($findexist->result) >= 1) {
					$dispinf = mysql_fetch_array($findexist->result) ;
					$_GET['idd'] = $dispinf['iddispo'];
				} else {
					$sql = "INSERT INTO `disponib` (`idboy`, `annee`, `mois`) VALUES ('".$idpeople."' , '".substr($_GET['nmonth'], 0, 4)."' , '".substr($_GET['nmonth'], 4, 2)."');";
					$creerdispo = new db();
					$creerdispo->inline($sql);
					$_GET['idd'] = $creerdispo->addid;
				}
			}

#######################################
###### Grille des disponibilités ##########################################################################################
#######################################
		$sql = "SELECT * FROM `disponib` WHERE `iddispo` = '".$_GET['idd']."'";
		$finddispo = new db();
		$finddispo->inline($sql);
		$infos = mysql_fetch_array($finddispo->result) ;

########################################
###### Table des journées de travail ##########################################################################################
########################################

		$datein = date("Y-m-01", mktime(0, 0, 0, $infos['mois'], 1, $infos['annee']));
		$dateout = date("Y-m-t", mktime(0, 0, 0, $infos['mois'], 1, $infos['annee']));


		$calchh = new hh();
		$worktable = $calchh->hhtable($infos['idboy'], $datein, $dateout);

?>
<div align="center">
<?php if ($titrepage != 'people2') { echo $dispo_04; ?><br><br><?php } ?>

<script type="" language="JavaScript">
function ModBG(idligne){
	vidligne = idligne + "v";
	if (document.getElementById(vidligne).value == 1) {
		document.getElementById(idligne).style.background = "<?php echo $off ;?>";
		document.getElementById(vidligne).value = 0;
	} else {
		document.getElementById(idligne).style.background = "<?php echo $on ;?>";
		document.getElementById(vidligne).value = 1;
	}
}

function ModDay(idligne){
	idligne1 = idligne + "A";
	idligne2 = idligne + "B";
	idligne3 = idligne + "C";

	vidligne1 = idligne1 + "v";
	vidligne2 = idligne2 + "v";
	vidligne3 = idligne3 + "v";

	if (document.getElementById(vidligne1).value == 1) {
		document.getElementById(idligne1).style.background = "<?php echo $off ;?>";
		document.getElementById(idligne2).style.background = "<?php echo $off ;?>";
		document.getElementById(idligne3).style.background = "<?php echo $off ;?>";
		document.getElementById(vidligne1).value = 0;
		document.getElementById(vidligne2).value = 0;
		document.getElementById(vidligne3).value = 0;
	} else {
		document.getElementById(idligne1).style.background = "<?php echo $on ;?>";
		document.getElementById(idligne2).style.background = "<?php echo $on ;?>";
		document.getElementById(idligne3).style.background = "<?php echo $on ;?>";
		document.getElementById(vidligne1).value = 1;
		document.getElementById(vidligne2).value = 1;
		document.getElementById(vidligne3).value = 1;
	}
}
</script>
<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=dispo0&mode=mod2&s=8" method="post">
<input type="hidden" name="idd" value="<?php echo $infos['iddispo'] ;?>">
<table class="minical" border="0" cellspacing="1" cellpadding="0" align="center" >
<tr>
<td>
	<table class="minical" border="0" cellspacing="1" cellpadding="0" align="center" >
		<tr>
			<td class="fulltitre" colspan="7"><?php echo strftime("%B %Y", mktime(0, 0, 0, $infos['mois'], 1, $infos['annee']))?></td>
		</tr>
		<tr>
			<th align="center"><?php echo $tool_30; ?></th>
			<th align="center"><?php echo $tool_31; ?></th>
			<th align="center"><?php echo $tool_32; ?></th>
			<th align="center"><?php echo $tool_33; ?></th>
			<th align="center"><?php echo $tool_34; ?></th>
			<th align="center"><?php echo $tool_35; ?></th>
			<th align="center"><?php echo $tool_36; ?></th>
		</tr>
		<tr>
		<?php
			$firstday = mktime (0,0,0,$infos['mois'], 1, $infos['annee']);
			$passjour = date("w", $firstday) - 1;
			if ($passjour == -1) {$passjour = 6;}
			$firstweek = weekfromdate(date("Y-m-d", $firstday));
			$lastday = date("t", $firstday);
			$firstweek++;
			for ($i = 1; $i <= $passjour; $i++) {
				echo '<td></td>';
			}
			$n = 1;
			$reste = 7 - $passjour;
			for ($i = 1; $i <= $reste; $i++) {
				$champ = 'd'.str_repeat('0', 2 - strlen($n)).$n;
				$date =  $infos['annee'].'-'.str_repeat('0', 2 - strlen($infos['mois'])).$infos['mois'].'-'.str_repeat('0', 2 - strlen($n)).$n;

				switch ($infos[$champ]) {
					case "0":
						$bgA = $off; $bgAv = "0";
						$bgB = $off; $bgBv = "0";
						$bgC = $off; $bgCv = "0";
					break;
					case "1":
						$bgA = $on; $bgAv = "1";
						$bgB = $off; $bgBv = "0";
						$bgC = $off; $bgCv = "0";
					break;
					case "2":
						$bgA = $off; $bgAv = "0";
						$bgB = $on; $bgBv = "1";
						$bgC = $off; $bgCv = "0";
					break;
					case "3":
						$bgA = $on; $bgAv = "1";
						$bgB = $on; $bgBv = "1";
						$bgC = $off; $bgCv = "0";
					break;
					case "4":
						$bgA = $off; $bgAv = "0";
						$bgB = $off; $bgBv = "0";
						$bgC = $on; $bgCv = "1";
					break;
					case "5":
						$bgA = $on; $bgAv = "1";
						$bgB = $off; $bgBv = "0";
						$bgC = $on; $bgCv = "1";
					break;
					case "6":
						$bgA = $off; $bgAv = "0";
						$bgB = $on; $bgBv = "1";
						$bgC = $on; $bgCv = "1";
					break;
					case "7":
						$bgA = $on; $bgAv = "1";
						$bgB = $on; $bgBv = "1";
						$bgC = $on; $bgCv = "1";
					break;
			}

			if (is_array($worktable)) {
				if (array_sum(preg_split('//',substr($worktable[$date], 0, 9),-1,PREG_SPLIT_NO_EMPTY)) > 0) $bgA = $work;
				if (array_sum(preg_split('//',substr($worktable[$date], 9, 6),-1,PREG_SPLIT_NO_EMPTY)) > 0) $bgB = $work;
				if (array_sum(preg_split('//',substr($worktable[$date], 15, 9),-1,PREG_SPLIT_NO_EMPTY)) > 0) $bgC = $work;
			}



			echo '<td width="40">
			<table class="jour" cellspacing="1" width="100%">
				<tr>';

				if ($bgA == $work) { echo '<td id="'.$n.'A" bgcolor="'.$bgA.'"><input type="hidden" name="'.$n.'A" id="'.$n.'Av" value="0">&nbsp;</td>'; } else { echo '<td id="'.$n.'A" bgcolor="'.$bgA.'" onClick="ModBG(\''.$n.'A\')" ondblclick="ModDay(\''.$n.'\')">&nbsp;<input type="hidden" name="'.$n.'A" id="'.$n.'Av" value="'.$bgAv.'"></td>';}

				echo '</tr><tr>';

				if ($bgB == $work) { echo '<td class="dispotxt" id="'.$n.'B" bgcolor="'.$bgB.'"><input type="hidden" name="'.$n.'B" id="'.$n.'Bv" value="0">'.$n.'</td>'; } else { echo '<td class="dispotxt" id="'.$n.'B" bgcolor="'.$bgB.'" onClick="ModBG(\''.$n.'B\')" ondblclick="ModDay(\''.$n.'\')">&nbsp;<input type="hidden" name="'.$n.'B" id="'.$n.'Bv" value="'.$bgBv.'">'.$n.'</td>';}

				echo '</tr><tr>';

				if ($bgC == $work) { echo '<td id="'.$n.'C" bgcolor="'.$bgC.'"><input type="hidden" name="'.$n.'C" id="'.$n.'Cv" value="0">&nbsp;</td>'; } else {echo '<td id="'.$n.'C" bgcolor="'.$bgC.'" onClick="ModBG(\''.$n.'C\')" ondblclick="ModDay(\''.$n.'\')">&nbsp;<input type="hidden" name="'.$n.'C" id="'.$n.'Cv" value="'.$bgCv.'"></td>';}

				echo '</tr>
			</table>
			</td>';
				$n++;
			}
			$semainz = ceil(($lastday - $n + 1) / 7);
			for ($x = 1; $x <= $semainz; $x++) {
				echo '</tr><tr>';
				$firstweek++;
				for ($i = 1; $i <= 7; $i++) {
					$champ = 'd'.str_repeat('0', 2 - strlen($n)).$n;
					$date =  $infos['annee'].'-'.str_repeat('0', 2 - strlen($infos['mois'])).$infos['mois'].'-'.str_repeat('0', 2 - strlen($n)).$n;

				switch ($infos[$champ]) {
					case "0":
						$bgA = $off; $bgAv = "0";
						$bgB = $off; $bgBv = "0";
						$bgC = $off; $bgCv = "0";
					break;
					case "1":
						$bgA = $on; $bgAv = "1";
						$bgB = $off; $bgBv = "0";
						$bgC = $off; $bgCv = "0";
					break;
					case "2":
						$bgA = $off; $bgAv = "0";
						$bgB = $on; $bgBv = "1";
						$bgC = $off; $bgCv = "0";
					break;

					case "3":
						$bgA = $on; $bgAv = "1";
						$bgB = $on; $bgBv = "1";
						$bgC = $off; $bgCv = "0";
					break;

					case "4":
						$bgA = $off; $bgAv = "0";
						$bgB = $off; $bgBv = "0";
						$bgC = $on; $bgCv = "1";
					break;
					case "5":
						$bgA = $on; $bgAv = "1";
						$bgB = $off; $bgBv = "0";
						$bgC = $on; $bgCv = "1";
					break;
					case "6":
						$bgA = $off; $bgAv = "0";
						$bgB = $on; $bgBv = "1";
						$bgC = $on; $bgCv = "1";
					break;
					case "7":
						$bgA = $on; $bgAv = "1";
						$bgB = $on; $bgBv = "1";
						$bgC = $on; $bgCv = "1";
					break;
			}


			if (is_array($worktable)) {
				if (array_sum(preg_split('//',substr($worktable[$date], 0, 9),-1,PREG_SPLIT_NO_EMPTY)) > 0) $bgA = $work;
				if (array_sum(preg_split('//',substr($worktable[$date], 9, 6),-1,PREG_SPLIT_NO_EMPTY)) > 0) $bgB = $work;
				if (array_sum(preg_split('//',substr($worktable[$date], 15, 9),-1,PREG_SPLIT_NO_EMPTY)) > 0) $bgC = $work;
			}




					if (($infos[$champ] == '') or ($n > $lastday)) {
						echo '<td></td>';
					} else {
		echo '<td width="40" height="5">
		<table class="jour" cellspacing="1" width="100%">
			<tr>';

				if ($bgA == $work) { echo '<td id="'.$n.'A" bgcolor="'.$bgA.'"><input type="hidden" name="'.$n.'A" id="'.$n.'Av" value="0">&nbsp;</td>'; } else { echo '<td id="'.$n.'A" bgcolor="'.$bgA.'" onClick="ModBG(\''.$n.'A\')" ondblclick="ModDay(\''.$n.'\')">&nbsp;<input type="hidden" name="'.$n.'A" id="'.$n.'Av" value="'.$bgAv.'"></td>';}

				echo '</tr><tr>';

				if ($bgB == $work) { echo '<td class="dispotxt" id="'.$n.'B" bgcolor="'.$bgB.'"><input type="hidden" name="'.$n.'B" id="'.$n.'Bv" value="0">'.$n.'</td>'; } else { echo '<td class="dispotxt" id="'.$n.'B" bgcolor="'.$bgB.'" onClick="ModBG(\''.$n.'B\')" ondblclick="ModDay(\''.$n.'\')">&nbsp;<input type="hidden" name="'.$n.'B" id="'.$n.'Bv" value="'.$bgBv.'">'.$n.'</td>';}

				echo '</tr><tr>';

				if ($bgC == $work) { echo '<td id="'.$n.'C" bgcolor="'.$bgC.'"><input type="hidden" name="'.$n.'C" id="'.$n.'Cv" value="0">&nbsp;</td>'; } else {echo '<td id="'.$n.'C" bgcolor="'.$bgC.'" onClick="ModBG(\''.$n.'C\')" ondblclick="ModDay(\''.$n.'\')">&nbsp;<input type="hidden" name="'.$n.'C" id="'.$n.'Cv" value="'.$bgCv.'"></td>';}

				echo '</tr>
		</table>
		</td>';
					}
					$n++;
				}
			}
		?>
		</tr>
	</table>
</td>
<td>
	<input type="hidden" name="idpeople" value="<?php echo $infos['idboy']; ?>"><input type="submit" name="modifier" value="<?php echo $tool_11; ?>">
</td>
</tr>
</table>
<br>
<?php echo $dispo_06 ?>
</form>
</div>
<?php
		break;
		case "mod2":
#######################################
###### Effectue les changements   ##########################################################################################
#######################################
$matrix = array(
	'000' => '0',
	'100' => '1',
	'010' => '2',
	'110' => '3',
	'001' => '4',
	'101' => '5',
	'011' => '6',
	'111' => '7'
);
$sql = "UPDATE `disponib` SET";
for ($x = 1; $x <= 31; $x++) {
	$val = $_POST[$x.'A'].$_POST[$x.'B'].$_POST[$x.'C'];
	$nam = "d".str_repeat('0', 2 - strlen($x)).$x;
	$sql .= "`".$nam."` = '".$matrix[$val]."' , ";
}
$sql = substr($sql, 0, -2);
$sql .= "WHERE `iddispo`= '".$_POST['idd']."' LIMIT 1 ;";
	$makechange = new db();
	$makechange->inline($sql);
		default:
#######################################
###### Menu principal         ##########################################################################################
#######################################

	#### Effacement des fiches vides
	$delvide = new db();
	$delvide->inline("DELETE  FROM `disponib` WHERE ( d01 + d02 + d03 + d04 + d05 + d06 + d07 + d08 + d09 + d10 + d11 + d12 + d13 + d14 + d15 + d16 + d17 + d18 + d19 + d20 + d21 + d22 + d23 + d24 + d25 + d26 + d27 + d28 + d29 + d30 + d31) = 0");

	for ($v = 0; $v + 1 <= $nbrmois; $v++) {
		$inperiod[] = date("Yn", mktime(0, 0, 0, date("m") + $v, 1, date("Y")));
	}

	$sql = "SELECT iddispo, annee, mois FROM `disponib` WHERE `idboy` = '".$idpeople."' AND CONCAT(annee,mois) IN (".implode(", ", $inperiod).")";

	$finddispo = new db();
	$finddispo->inline($sql);

	while ($row = mysql_fetch_array($finddispo->result)) {
		$key = $row['annee'].str_repeat('0', 2 - strlen($row['mois'])).$row['mois'];
		$exists[$key] = $row['iddispo'];
	}
	$exists['a'] = '';
?>
<div align="center">
<?php if ($titrepage != 'people2') { echo $dispo_05; } ?>
<table border="0" cellspacing="1" cellpadding="20" align="center">
	<tr>
<?php
	setlocale(LC_TIME, $loclang);

	for ($v = 0; $v + 1 <= $nbrmois; $v++) {

	# dates
		$dateval = date("Ym", mktime(0, 0, 0, date("m") + $v, 1, date("Y")));
		$datelabel = strftime("%B %Y", mktime(0, 0, 0, date("m") + $v, 1, date("Y")));

		$datein = date("Y-m-01", mktime(0, 0, 0, date("m") + $v, 1, date("Y")));
		$dateout = date("Y-m-t", mktime(0, 0, 0, date("m") + $v, 1, date("Y")));

	# hhcode
		$calchh = new hh();
		$worktable = $calchh->hhtable($idpeople, $datein, $dateout);

		echo '<td align="center" valign="top">';

		if (array_key_exists ($dateval, $exists)) {
			#########
			$sql = "SELECT * FROM `disponib` WHERE `iddispo` = '".$exists[$dateval]."'";
			$detail = new db();
			$detail->inline($sql);
			$infos = mysql_fetch_array($detail->result) ;
		} else {
			$infos['annee'] = substr($dateval, 0, 4);
			$infos['mois'] = substr($dateval, 4, 2);
			for ($i = 1; $i <= 31; $i++) {
				$champ = 'd'.str_repeat('0', 2 - strlen($i)).$i;
				$infos[$champ] = '0';
			}
			$new = 'new' ;
		}
			?>
	<table class="minical" border="0" cellspacing="1" cellpadding="0" align="center" >
		<tr>
			<td class="fulltitre" colspan="7"><?php echo $datelabel; ?></td>
		</tr>
		<tr>
			<th align="center"><?php echo $tool_30; ?></th>
			<th align="center"><?php echo $tool_31; ?></th>
			<th align="center"><?php echo $tool_32; ?></th>
			<th align="center"><?php echo $tool_33; ?></th>
			<th align="center"><?php echo $tool_34; ?></th>
			<th align="center"><?php echo $tool_35; ?></th>
			<th align="center"><?php echo $tool_36; ?></th>
		</tr>
		<tr>
		<?php
			$firstday = mktime (0,0,0,$infos['mois'], 1, $infos['annee']);
			$passjour = date("w", $firstday) - 1;
			if ($passjour == -1) {$passjour = 6;}
			$firstweek = weekfromdate(date("Y-m-d", $firstday));
			$lastday = date("t", $firstday);
			$firstweek++;
			for ($i = 1; $i <= $passjour; $i++) {
				echo '<td></td>';
			}
			$n = 1;

			$reste = 7 - $passjour;
			for ($i = 1; $i <= $reste; $i++) {
				$champ = 'd'.str_repeat('0', 2 - strlen($n)).$n;
				$daydate = date("Y-m-", $firstday).str_repeat('0', 2 - strlen($n)).$n;
				echo '<td background="/data/people/dispo/dillu.php?size=B&disp='.$infos[$champ].'&hhcode='.$worktable[$daydate].'" align="center" valign="middle" width="20" height="23"><font size="3" color="#FFFFFF">'.$n.'</font></td>';
				$n++;
			}
			$semainz = ceil(($lastday - $n + 1) / 7);
			for ($x = 1; $x <= $semainz; $x++) {
				echo '</tr><tr>';
				$firstweek++;
				for ($i = 1; $i <= 7; $i++) {
					$champ = 'd'.str_repeat('0', 2 - strlen($n)).$n;
					$daydate = date("Y-m-", $firstday).str_repeat('0', 2 - strlen($n)).$n;
					if (($infos[$champ] == '') or ($n > $lastday)) {
						echo '<td></td>';
					} else {
						echo '<td background="/data/people/dispo/dillu.php?size=B&disp='.$infos[$champ].'&hhcode='.$worktable[$daydate].'" align="center" valign="middle" width="23" height="23"><font size="3" color="#FFFFFF">'.$n.'</font></td>';
					}
					$n++;
				}
			}
		?>
		</tr>
		<tr>
			<td class="mods" colspan="7">
			<?php
			if ($exists[$dateval] >= 1) {
				echo '<a class="white" href="'.$_SERVER['PHP_SELF'].'?act=dispo0&mode=mod&idd='.$exists[$dateval].'&s=8">'.$tool_11.'</a>';
			} else {
				echo '<a class="white" href="'.$_SERVER['PHP_SELF'].'?act=dispo0&mode=mod&nmonth='.$dateval.'&s=8&idpeople='.$idpeople.'">'.$tool_13.'</a>';
			}
			?>
			</td>
		</tr>
		</table>
		<?php
			#########
		echo '</td>';
	}
?>
	</tr>
</table>
</div>
<?php
}
?>
</div>
