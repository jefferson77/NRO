<?php
define('NIVO', '../../');

$Titre = 'my Week';
$Style = 'standard';

$PhraseBas = 'Aperçu de la semaine d\'un people';

## Entete
include_once NIVO."includes/entete.php" ;
include_once NIVO."classes/photo.php";
include_once NIVO."classes/hh.php";

include_once NIVO."classes/vip.php";
include_once NIVO."classes/anim.php";
include_once NIVO."classes/merch.php";

###### Modifs mission ########
if ($_REQUEST['act'] == "weekModif") {
	foreach ($_POST as $key => $value) {
		if (is_numeric($key)) {
			$_POST[$key]['idvip'] = $key;
			$DB->MAJ('vipmission', 'contratencode|h200', $_POST[$key]);
		}
	}
}

### semaine
if (empty($_REQUEST['date'])) $_REQUEST['date'] = date("Y-m-d"); 
$sem = weekfromdate($_REQUEST['date']);
$semdates = weekdate($sem, date("Y", strtotime($_REQUEST['date'])));
?>
<style type="text/css" media="screen">
/* table MY */
	table.my { background-color: #FFF; font-family:"Trebuchet MS",Verdana,Arial,sans-serif; font-size:10px; color:#333333; }
	table.my th { background-color:#56576B; color:#EBEBEE; text-align: center; width:25px; }
	table.my th.tete { background-color:#B4B5BD; font-size:12px; text-align:center; color:#333333; }
	table.my h1 { padding: 0px; margin: 0px; }
	table.my td { background-color:#EEE; text-align: center; padding: 1px 2px; }
	tr.tot td { background-color:#FFB267; font-weight:bold; text-align:center; }
	table.my td.imag { padding: 1px; margin: 0px; }
	table.my td.pay, 
	table.my td.payt, 
	table.my td.fac, 
	table.my td.fact { width: 25px; }
	table.my td.pay { background-color:#CCC4F5; }
	table.my td.payt { background-color:#8067FF; font-weight:bold; }
	table.my td.fac { background-color:#C7F5C4; }
	table.my td.fact { background-color:#6BFF67; font-weight:bold; }

/* LEFTY */
	table.lefty { background-color: #FFF; font-family:"Trebuchet MS",Verdana,Arial,sans-serif; font-size:10px; color:#333333; text-align: center; }
	table.lefty td { background-color:#B4B5BD; padding: 2px; }
	table.lefty td:hover { background-color:#FFB267; }
	table.lefty td.itsme { background-color:#8067FF; }
	table.lefty th { padding: 2px; background-color:#56576B; color:#EBEBEE; }
	table.lefty a { text-decoration:none; }
	
/* FORM */
	input[type="submit"] { display: none; }
	input[type="text"] { background-color:#B4B5BD; height: 18px; width: 30px; color: #002F5D; border: 0px; margin: -1px -2px; text-align: center; padding-top: 8px; }
	
/* DIM */
	tr.dim th { background-color: #C7C7C7; }
	tr.dim td.pay, tr.dim td.fac {background-color: #DFDFDF;}
	tr.dim td.payt, tr.dim td.fact {background-color: #BBB;}
	tr.dim th.tete { background-color: #FBFBFB;}
	tr.dim input[type="text"] { background-color:#EEE; }
</style>
<div id="centerzonelarge">	
	<table border="0" cellspacing="2" cellpadding="2" width="100%">
		<tr>
			<td>
<!-- table job vip ################################################################################################################################################################################ -->
<?php
if (!empty($_GET['job'])) {
	echo '<table  class="lefty" cellspacing="1">
		<tr><th><a href="'.NIVO.'vip/adminvip.php?act=show&amp;idvipjob='.$_GET['job'].'&amp;etat=1">Job '.$_GET['job'].'</a></th></tr>';

	$missions = $DB->getArray("SELECT 
		v.idpeople,
		p.pnom, pprenom
		FROM vipmission v
		LEFT JOIN people p ON v.idpeople = p.idpeople
		WHERE idvipjob = ".$_GET['job']."
		GROUP BY v.idpeople
		ORDER BY p.pnom");

	foreach ($missions as $row) {
		$itsme = ($_REQUEST['idpeople'] == $row['idpeople'])?' class="itsme"':'';
		echo '<tr><td'.$itsme.'><a href="'.$_SERVER['PHP_SELF'].'?idpeople='.$row['idpeople'].'&amp;date='.$_REQUEST['date'].'&amp;job='.$_GET['job'].'">'.$row['pnom'].' '.$row['pprenom'].'</a></td></tr>';
	}

	echo'</table>';
}
?>
			</td>
			<td valign="top">
<!-- table myweek ################################################################################################################################################################################ -->
<?php
if (!empty($_REQUEST['idpeople'])) {

	# People
	$infp = $DB->getRow("SELECT pnom, pprenom FROM people WHERE idpeople = ".$_REQUEST['idpeople']);

	$ht = new hh();
	$ht->hhtable($_REQUEST['idpeople'], date("Y-m-d", strtotime("-1 day", strtotime($semdates['lun']))), $semdates['dim']);
	$table = $ht->prtab;

	?>
	<form action="?act=weekModif&amp;idpeople=<?php echo $_REQUEST['idpeople'] ?>&amp;date=<?php echo $_REQUEST['date'] ?>&amp;job=<?php echo $_GET['job'] ?>" method="post" accept-charset="utf-8">
		<table class="my" cellspacing="1" align="center">
		    <tr>
		        <td colspan="2" class="imag"><img src="<?php echo NIVO.'data/people/photo.php?id='.$_REQUEST['idpeople'].'&amp;newwidth=92&amp;newheight=69" alt="'.$infp['pprenom'].$infp['pnom'] ?>"></td>
		        <th class="tete" colspan="26"><h1><?php echo $infp['pnom'].' '.$infp['pprenom']; ?></h1>
		        <?php echo 'du '.fdate($semdates['lun']).' au '.fdate($semdates['dim']); ?></th>
		    </tr>
			<tr>
				<th colspan="2">
				<?php echo '<a href="?idpeople='.$_REQUEST['idpeople'].'&amp;date='.date("Y-m-d", strtotime("-7 days", strtotime($semdates['lun']))).'&amp;job='.$_GET['job'].'"><img border="0" src="'.STATIK.'illus/date_previous.png" width="16" height="16" alt=""></a>'; ?>
					&nbsp;Date&nbsp;
					<?php echo '<a href="?idpeople='.$_REQUEST['idpeople'].'&amp;date='.date("Y-m-d", strtotime("+7 days", strtotime($semdates['lun']))).'&amp;job='.$_GET['job'].'"><img border="0" src="'.STATIK.'illus/date_next.png" width="16" height="16" alt=""></a>'; ?>
				</th>
				<th>S</th>
				<th>J</th>
				<th>M</th>
				<th><?php echo '<img src="'.NIVO.'merch/routingpng.php?entete=1&amp;sz=200x15" alt="routingpng" width="200" height="15">' ?></th>
				<th>B</th>
				<th>P</th>
				<th>NI</th>
				<th>SUP</th>
				<th>SP</th>
				<th>Fac</th>
				<th>100%</th>
				<th>150%</th>
				<th>200%</th>
				<th>Pay</th>
				<th>ENC</th>
				<th>In</th>
				<th>Out</th>
				<th>Brk</th>
				<th>NF</th>
				<th>NP</th>
				<th>150</th>
				<th>200</th>
				<th>Spec</th>
				<th>Adj</th>
				<th>KM</th>
				<th>fKM</th>
			</tr>

		<?php
		setlocale (LC_ALL, 'fr_FR');

		## init totaux
		$totalhhigh = 0;
		$totalhlow = 0;
		$totalhnight = 0;
		$totalh150 = 0;
		$totalhspec = 0;
		$totalhprest = 0;

		$total100 = 0;
		$total150 = 0;
		$total200 = 0;
		$totalpay = 0;

		## Semaine
		array_unshift($semdates, date("Y-m-d", strtotime("-1 day", strtotime($semdates['lun']))));
	
		foreach ($semdates as $day => $ddate) {
			#### pour le dimanche précédent
			if (empty($day)) {
				$icolor = "&amp;dim=yes";
				$dim = ' class="dim"';
				$dis = ' disabled';
				$cbcolor = '#EEE';
			} else {
				$icolor = "";
				$dim = "";
				$dis = "";
				$cbcolor = '#B4B5BD';
			}

			if (array_key_exists($ddate, $table)) {

				$i=0;
				$jt = array();
			
				### regroupement des heures prestés sur un jour si plusieurs missions
				foreach ($table[$ddate] as $value) {
					$fich = new corevip ($value['idmission']);
			
					$jt['100'] += $fich->thp100;
					$jt['150'] += $fich->thp150;
					$jt['200'] += $fich->thp200;
					$jt['tot'] += $fich->thp100 + $fich->thp150 + $fich->thp200;
				}
			
				foreach ($table[$ddate] as $value) {
					$i++;
				
					## get datas
					switch ($value['secteur']) {
						case 'VI':
							$fich = new corevip ($value['idmission']);
							$minf = $DB->getRow("SELECT idvip as idmission, idvipjob as idjob, vipin, vipout, brk, ajust, night, h150, h200, contratencode, ts, vkm, vfkm FROM vipmission WHERE idvip = ".$value['idmission']);
							if ($fich->facnum > 0) {
								$icolor = "&amp;dim=yes";
								$dim = ' class="dim"';
								$dis = ' disabled';
								$cbcolor = '#EEE';
							}
							$mislink = NIVO.'vip/adminvip.php?act=showmission&amp;idvip='.$value['idmission'];
						break;

						case 'AN':
							$fich = new coreanim ($value['idmission']);
							$mislink = NIVO.'animation2/adminanim.php?act=showmission&amp;idanimation='.$value['idmission'];
						break;

						case 'ME':
							$fich = new coremerch ($value['idmission']);
							$mislink = NIVO.'merch/adminmerch.php?act=show&act2=listing&amp;idmerch='.$value['idmission'];
						break;
					}

					## Affichage ##
					echo '<tr'.$dim.'>';

					if ($i == 1) echo '<th class="tete" rowspan="'.count($table[$ddate]).'">'.ucfirst(strftime("%a", strtotime($ddate))).'</th>
			        <th class="tete" rowspan="'.count($table[$ddate]).'" >'.strftime("%d / %m", strtotime($ddate)).'</th>
						<th>'.$value['secteur'].'</th>
						<th>'.$minf['idjob'].'</th>
						<th><a href="'.$mislink.'" target="_blank">'.$value['idmission'].'</a></th>
				        <td class="imag"><img src="'.NIVO.'merch/routingpng.php?mis='.$value['secteur'].$value['idmission'].'&amp;sz=200x25'.$icolor.'" alt="routingpng" width="200" height="25"></td>
				        <td class="fac">'.fnbr0($fich->hhigh).'</td>
				        <td class="fac">'.fnbr0($fich->hlow).'</td>
				        <td class="fac">'.fnbr0($fich->hnight).'</td>
				        <td class="fac">'.fnbr0($fich->h150).'</td>
				        <td class="fac">'.fnbr0($fich->hspec).'</td>
				        <td class="fact">'.fnbr0($fich->hprest).'</td>';

						if ($i == 1) echo '<td class="pay" rowspan="'.count($table[$ddate]).'">'.fnbr0($jt['100']).'</td>
				        <td class="pay" rowspan="'.count($table[$ddate]).'">'.fnbr0($jt['150']).'</td>
				        <td class="pay" rowspan="'.count($table[$ddate]).'">'.fnbr0($jt['200']).'</td>
				        <td class="payt" rowspan="'.count($table[$ddate]).'">'.fnbr0($jt['tot']).'</td>';

						echo '<td style="background-color:'.$cbcolor.';"><input type="checkbox" name="'.$minf['idmission'].'[contratencode]" value="1" '.(($minf['contratencode'] == 1)?' checked':'').' '.$dis.'></td>
				        <td><input type="text" name="'.$minf['idmission'].'[vipin]" value="'.ftime($minf['vipin']).'"'.$dis.'></td>
				        <td><input type="text" name="'.$minf['idmission'].'[vipout]" value="'.ftime($minf['vipout']).'"'.$dis.'></td>
				        <td><input type="text" name="'.$minf['idmission'].'[brk]" value="'.fnbr0($minf['brk']).'"'.$dis.'></td>
				        <td><input type="text" name="'.$minf['idmission'].'[night]" value="'.fnbr0($minf['night']).'"'.$dis.'></td>
				        <td></td>
				        <td><input type="text" name="'.$minf['idmission'].'[h150]" value="'.fnbr0($minf['h150']).'"'.$dis.'></td>
				        <td style="background-color:'.$cbcolor.';"><input type="checkbox" name="'.$minf['idmission'].'[h200]" value="1" '.(($minf['h200'] == 1)?' checked':'').' '.$dis.'></td>
				        <td><input type="text" name="'.$minf['idmission'].'[ts]" value="'.fnbr0($minf['ts']).'"'.$dis.'></td>
				        <td><input type="text" name="'.$minf['idmission'].'[ajust]" value="'.fnbr0($minf['ajust']).'"'.$dis.'></td>
						<td><input type="text" name="'.$minf['idmission'].'[vkm]" value="'.fnbr0($minf['vkm']).'"'.$dis.'></td>
						<td><input type="text" name="'.$minf['idmission'].'[vfkm]" value="'.fnbr0($minf['vfkm']).'"'.$dis.'></td>
				    </tr>';

					## Totaux ##
					if (empty($dim)) {                        
						$totalhhigh += $fich->hhigh ;
						$totalhlow += $fich->hlow ;
						$totalhnight += $fich->hnight ;
						$totalh150 += $fich->h150 ;
						$totalhspec += $fich->hspec ;
						$totalhprest += $fich->hprest ;

						$total100 += $fich->thp100;
						$total150 += $fich->thp150;
						$total200 += $fich->thp200;
						$totalpay += $fich->thp100 + $fich->thp150 + $fich->thp200;
					}
				}
			} else {
				echo '<tr'.$dim.'>
			        <th class="tete" >'.ucfirst(strftime("%a", strtotime($ddate))).'</th>
			        <th class="tete" >'.strftime("%d / %m", strtotime($ddate)).'</th>
					<th></th>
					<th></th>
					<th></th>
			        <td class="imag"><img src="'.NIVO.'merch/routingpng.php?sz=200x25" alt="routingpng" width="200" height="25"></td>
			        <td class="fac">&nbsp;</td>
			        <td class="fac">&nbsp;</td>
			        <td class="fac">&nbsp;</td>
			        <td class="fac">&nbsp;</td>
			        <td class="fac">&nbsp;</td>
			        <td class="fact">&nbsp;</td>
			        <td class="pay">&nbsp;</td>
			        <td class="pay">&nbsp;</td>
			        <td class="pay">&nbsp;</td>
			        <td class="payt">&nbsp;</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
			    </tr>';
			}
		}

		echo '<tr class="tot">
		    <td colspan="2">Total</td>
			<td></td>
			<td></td>
			<td></td>
		    <td>'.$i.'</td>
		    <td>'.$totalhhigh.'</td>
		    <td>'.$totalhlow.'</td>
		    <td>'.$totalhnight.'</td>
		    <td>'.$totalh150.'</td>
		    <td>'.$totalhspec.'</td>
		    <td>'.$totalhprest.'</td>
		    <td>'.(($total100 > 38)?"<font color=\"red\">$total100</font>":$total100).'</td>
		    <td>'.$total150.'</td>
		    <td>'.$total200.'</td>
		    <td>'.$totalpay.'</td>
			<td colspan="12"></td>
		</tr>
		';
		?>
		<tr>
			<th colspan="28"><input type="submit" name="mod" value="Modifier" id="subBut"></th>
		</tr>
		</table>
	</form>
	<?php
}
?>

			</td>
		</tr>
	</table>
	<br>
	<br>
</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$("input").change(function () {
			$("#subBut").show(); 
		});
	});
</script>
<?php include NIVO."includes/pied.php" ; ?>