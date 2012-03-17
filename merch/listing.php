<div id="centerzonelarge">
<?php
## init
$emails = '';
$displaymax = 300;

## Modifie les recherches
	if (!empty($_GET['msort'])) $_SESSION['msort'] = $_GET['msort'];
	if (!empty($_GET['mtype'])) $_SESSION['mtype'] = $_GET['mtype'];

## Default
	if (empty($_SESSION['msort'])) $_SESSION['msort'] = 'me.datem';
	if (empty($_SESSION['mtype'])) $_SESSION['mtype'] = 'L';
	if (($_SESSION['mtype'] == '3') and ($_SESSION['msort'] == '')) $_SESSION['msort'] = 'p.pnom, p.pprenom';

## creation du quid
	switch($_GET['listing']) {
	## Mes Jobs
		case "direct":
			$_SESSION['mquid'] = "AND me.idagent = '".$_SESSION['idagent']."'";
			$_SESSION['msort'] = 'me.datem';
		break;
	## Todo
		case "missing":
			$_SESSION['mquid'] = "AND me.idagent = '".$_SESSION['idagent']."' AND ( me.idpeople IS NULL OR me.idpeople = '')";
			$_SESSION['msort'] = 'me.datem';
		break;
	## New search
		case "newsearch":
			if ((!empty($_POST['weekm'])) and (empty($_POST['yearm']))) $_POST['yearm'] = date("Y");

			$searchfields = array (
				'a.idagent' 				=> 'idagent',
				'a.prenom' 				=> 'prenom',
				'c.codeclient' 			=> 'codeclient',
				'c.societe' 			=> 'csociete',
				'me.boncommande' 		=> 'boncommande',
				'me.datem' 				=> 'date',
				'me.recurrence' 		=> 'recurrence',
				'me.genre' 				=> 'genre',
				'me.idmerch' 			=> 'idmerch',
				'me.produit' 			=> 'produit',
				'me.rapportencode' 		=> 'rapportencode',
				'me.reference' 			=> 'reference',
				'me.yearm' 				=> 'yearm',
				'me.weekm' 				=> 'weekm',
				'me.boncommande' 		=> 'boncommande',
				'p.codepeople' 			=> 'codepeople',
				'p.pnom' 				=> 'pnom',
				'p.pprenom' 			=> 'pprenom',
				's.codeshop' 			=> 'codeshop',
				's.societe' 			=> 'ssociete',
				's.ville' 				=> 'sville',
				'p.lbureau' 			=> 'lbureau'
			);

			$_SESSION['mquid'] = ' AND '.$DB->MAKEsearch($searchfields);

		## Contrats encodés
			if ($_POST['contratencode'] == 'non') $_SESSION['mquid'] .= " AND me.contratencode != '1' "; # !! enum donc les '' autours du 1 sont importants
			if ($_POST['contratencode'] == '1') $_SESSION['mquid'] .= " AND me.contratencode = '1' ";

		## rech todo
			if (!empty($_POST['todo'])) {
				$_SESSION['mquid'] .= " AND (me.idpeople IS NULL OR me.idpeople = '')";
			}
	}
$merchs = $DB->getArray("SELECT
			me.idmerch, me.datem, me.weekm, me.genre, me.idpeople, me.yearm,
				me.hin1, me.hout1, me.hin2, me.hout2,
				me.kmpaye, me.kmfacture,
				me.produit, me.facturation,
				me.ferie, me.contratencode, me.rapportencode, me.recurrence, me.easremplac,
			a.prenom, a.idagent,
			c.codeclient, c.societe AS clsociete, c.idclient, c.tel, c.fax,
			co.idcofficer, co.qualite, co.onom, co.oprenom, co.fax AS cofax, co.langue, co.departement,
			s.idshop, s.codeshop, s.societe AS ssociete, s.ville AS sville,
			p.pnom, p.pprenom, p.lbureau, p.nrnational, p.gsm, p.codepeople, p.email,
			nf.montantfacture, nf.montantpaye
		FROM merch me
			LEFT JOIN agent a ON me.idagent = a.idagent
			LEFT JOIN client c ON me.idclient = c.idclient
			LEFT JOIN cofficer co ON me.idcofficer = co.idcofficer
			LEFT JOIN people p ON me.idpeople = p.idpeople
			LEFT JOIN shop s ON me.idshop = s.idshop
			LEFT JOIN notefrais nf ON nf.secteur = 'ME' AND nf.idmission = me.idmerch
		WHERE me.facturation = 1 ".$_SESSION['mquid']."
		ORDER BY ".$_SESSION['msort']."
		LIMIT ".$displaymax);

if (count($merchs) == $displaymax) $warning[] = "Recherche trop large, seuls les ".$displaymax." premiers résultats sont affichés";
?>
<fieldset>
	<legend><b>listing des Merch</b></legend>
	<b>R&eacute;sultats : ( <?php echo count($merchs); ?> ) </b><?php echo $DB->quod; ?><br>
</fieldset>
<br>
<div align="center">
	<a href="?act=listing&amp;mtype=L"><font color="white">Listing</font></a> /
	<a href="?act=listing&amp;mtype=2&amp;msort=me.weekm, me.datem"><font color="white">Info &eacute;ditable</font></a> /
	<a href="?act=listing&amp;mtype=3&amp;msort=p.idpeople"><font color="white">D&eacute;compte</font></a> /
	<a href="?act=listing&amp;mtype=4&amp;msort=me.weekm, me.datem"><font color="white">Info EAS</font></a>
</div>
<br>
<?php
# ------- DEBUT LISTING GENERAL --------
if ($_SESSION['mtype'] == 'L') {
	$colspa = 6;
?>
<form action="<?php echo NIVO ?>print/merch/printmerch.php" method="post" target="popup" onsubmit="OpenBrWindow('_blank','popup','scrollbars=yes,status=yes,resizable=yes','700','500','true')" >
	<table class="planning" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="planning" colspan="<?php echo $colspa; ?>" align="right">
				| Planning :
				<font color="#D7D3DB">
				<input type="checkbox" name="ptype[]" value="planningint"> Interne &nbsp;&nbsp;
				<input type="checkbox" name="ptype[]" value="planningclient">Client&nbsp;&nbsp;
				<input type="checkbox" name="ptype[]" value="planningshop">Shop</font>&nbsp;|&nbsp;
				<input type="checkbox" name="ptype[]" value="contrat">Contrat&nbsp;|&nbsp;
				Etiquettes : <font color="#D7D3DB">
				<input type="checkbox" name="ptype[]" value="etiqpeop">  <input type="text" name="netiq1" size="2">  People &nbsp;&nbsp;
				<input type="checkbox" name="ptype[]" value="etiqshop">  <input type="text" name="netiq2" size="2">  Shop </font>
			</th>
			<th class="planning"></th>
			<th class="planning"><input type="submit" class="btn printer"></th>
			<th class="planning" colspan="2" style="width: 40px;">
				<input type="submit" class="btn phone" name="send" value="sms">
				<img src="<?php echo STATIK ?>illus/mail.png" width="16" height="16" alt="Mail" border="0" title="Adresses Mail" align="top" id="showmails">
			</th>
		</tr>
		<tr>
			<th class="planning"><a href="?act=listing&amp;msort=a.idagent">Agent</a></th>
			<th class="planning"><a href="?act=listing&amp;msort=me.idmerch">ID</a></th>
			<th class="planning"><a href="?act=listing&amp;msort=me.datem">Week</a></th>
			<th class="planning"><a href="?act=listing&amp;msort=me.datem">Date</a></th>
			<th class="planning"><a href="?act=listing&amp;msort=p.idpeople">code - id</a> - <a href="?act=listing&msort=p.pnom, p.pprenom">Nom People</a></th>
			<th class="planning"><a href="?act=listing&amp;msort=c.idclient">Client</a></th>
			<th class="planning"><a href="?act=listing&amp;msort=s.codeshop">Lieux</a></th>
			<th class="planning"><a href="?act=listing&amp;msort=me.genre">Genre</a></th>
			<th class="planning"></th>
			<th class="planning"></th>
		</tr>
	<?php
	$i = 0;
	foreach ($merchs as $row) {
		if (!empty($row['email'])) $emails[] = $row['email'];
		$i++;
		echo '<tr bgcolor="'.((fmod($i, 2) == 1)?'#9CBECA':'#8CAAB5').'">';
	?>
					<td class="planning"><?php echo $row['prenom'] ?></td>
					<td class="planning"><?php echo $row['idmerch'] ?></td>
					<td class="planning"><?php echo $row['weekm'] ?></td>
					<td class="planning"><?php echo fdate($row['datem']) ?></td>
					<td class="planning"><img src="<?php echo STATIK ?>illus/<?php echo $row['lbureau']; ?>.gif" alt="<?php echo $row['lbureau']; ?>" width="12" height="9">
						<?php echo $row['codepeople'].' - '.$row['idpeople'].' - '.$row['pnom'].' '.$row['pprenom'] ?> <a href="<?php echo NIVO.'data/people/adminpeople.php?act=show&idpeople='.$row['idpeople'];?>" target="_blank"><img src="<?php echo STATIK ?>illus/icon_profile.gif" border="0" width="15" height="12"></a></td>
					<td class="planning"><?php echo $row['codeclient']; ?> - <?php echo $row['clsociete']; ?></td>
					<td class="planning"><?php echo $row['codeshop']; ?> - <?php echo $row['ssociete']; ?> - <?php echo $row['sville']; ?></td>
					<td class="planning"><?php echo $row['genre'] ?><?php if ($row['recurrence'] != 1) {echo '-';} ?></td>
					<th class="planning" width="15"><input type="checkbox" name="print[]" checked value="<?php echo $row['idmerch'] ?>"></th>
					<td class="planning" width="13"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=show&act2=listing&idmerch='.$row['idmerch'];?>"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
			</tr>
	<?php } ?>
	</table>
</form>
<div id="emails">
	<p>Pour envoyer un mail aux personnes suivantes, copiez le adresses ci-dessous et collez les dans un nouveau mail</p>
	<?php
	if (!empty($emails)) {
		echo implode(", ", $emails);
	} else {
		echo "Aucun mail";
	}
	?>
</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$("#showmails").click(function(){
				$("#emails").toggle();
		});
	});
</script>
<?php
}

if (($_SESSION['mtype'] == '2') or ($_SESSION['mtype'] == '3')) {
	$colspa = 8;?>
	<table class="planning" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
		<tr>
		<?php  $colspa = 9;?>
			<th class="planning"><a href="?act=listing&amp;msort=me.idmerch">ID</a></th>
			<th class="planning"><a href="?act=listing&amp;msort=me.weekm">S</a></th>
			<th class="planning"><a href="?act=listing&amp;msort=me.datem">Date</a></th>
			<th class="planning"><a href="?act=listing&amp;msort=p.idpeople">id</a> - <a href="?act=listing&msort=p.pnom, p.pprenom">Nom People</a></th>
			<th class="planning">id <a href="?act=listing&amp;msort=c.idclient">Clients</a></th>
			<th class="planning"><a href="?act=listing&amp;msort=co.departement">Dept</a></th>
			<th class="planning">id <a href="?act=listing&amp;msort=s.codeshop">Lieux</a></th>
			<th class="planning">F&eacute;ri&eacute;</th>
			<th class="planning">AM in</th>
			<th class="planning">AM out</th>
			<th class="planning">PM in</th>
			<th class="planning">Pm out</th>
			<th class="planning">tot</th>
			<th class="planning">KM P</th>
			<th class="planning">KM F</th>
			<th class="planning">P - F</th>
			<th class="planning">Fra P</th>
			<th class="planning">Fra F</th>
			<th class="planning">P - F</th>
			<th class="planning"><a href="?act=listing&amp;msort=me.contratencode">Enco</a></th>
			<th class="planning">Updt</th>
		</tr>
		<?php
		$xpnom = '';
		$xclient = '';
		$i = 0;

		$timetotx = 0;
		$timetotz = 0;
		$kmpayex = 0;
		$kmpayez = 0;
		$kmfacturex = 0;
		$kmfacturez = 0;
		$fraisx = 0;
		$fraisz = 0;
		$fraisfacturex = 0;
		$fraisfacturez = 0;


		foreach ($merchs as $row) {
			$i++;
			$tr = (fmod($i, 2) == 1)?'<tr bgcolor="#9CBECA">':'<tr bgcolor="#8CAAB5">';
		?>
			<form action="?act=listingmodif" method="post">
				<input type="hidden" name="idmerch" value="<?php echo $row['idmerch'] ;?>">
				<?php
					### récap people si tri people
					$pnom = $row['idpeople']; # people
					$pclient = $row['idclient']; # client
					$pweekm = $row['weekm']; # semaine
					$pdatem = $row['datem']; # jour
					$pcodeshop = $row['codeshop']; # shop
					$pcontratencode = $row['contratencode']; # contratencode
					if ($pnom == '') { $pnom = 'z'; }
					if ($pclient == '') { $pclient = 'z'; }
					if ($pweekm == '') { $pweekm = 'z'; }
					if ($pdatem == '') { $pdatem = 'z'; }
					if ($pcodeshop == '') { $pcodeshop = 'z'; }
					if ($pcontratencode == '') { $pcontratencode = 'z'; }


if ($_SESSION['mtype'] == '3') {
	if
		(
			((!empty($xpnom)) and ($xpnom != $pnom) and (($_GET['msort'] == 'p.idpeople') or ($_GET['msort'] == 'p.pnom, p.pprenom'))) #people
		 or
			((!empty($xclient)) and ($xclient != $pclient) and ($_GET['msort'] == 'c.idclient')) # client
		 or
			((!empty($xweekm)) and ($xweekm != $pweekm) and ($_GET['msort'] == 'me.weekm')) # week
		 or
			((!empty($xdatem)) and ($xdatem != $pdatem) and ($_GET['msort'] == 'me.datem')) # jour
		 or
			((!empty($xcodeshop)) and ($xcodeshop != $pcodeshop) and ($_GET['msort'] == 's.codeshop')) # shop
		or
			((!empty($xcontratencode)) and ($xcontratencode != $pcontratencode) and ($_GET['msort'] == 'me.contratencode')) # shop
		)
	{
					?>
						<tr bgcolor="white">
							<th colspan="6"></th>
							<th colspan="6"></th>
							<th class="planning"><?php echo $timetotz; $timetotz = 0; ?></th>
							<th class="planning"><?php echo $kmpayez; ?></th>
							<th class="planning"><?php echo $kmfacture; ?></th>
							<th class="planning"><?php $kmdifz = $kmfacturez - $kmpayez ; echo fnega($kmdifz); $kmdifz = 0; $kmpayez = 0; $kmfacturez = 0; ?></th>
							<th class="planning"><?php echo $fraisz; $fraisz = 0; ?></th>
							<th class="planning"><?php echo $fraisfacturez; $fraisfacturez = 0; ?></th>
							<th class="planning"><?php $fraisdifz = $fraisfacturez - $fraisz ; echo fnega($fraisdifz); $fraisdifz = 0; $fraisz = 0; $fraisfacturez = 0; ?></th>

							<th class="planning"><?php echo $briefingz; $briefingz = 0; ?></th>
							<th class="planning"></th>
						<?php
						echo '</tr>';
	}
}
					###/ récap people si tri people
						echo $tr;
				?>
						<td class="planning"><?php echo $row['idmerch'] ?></td>
						<td class="planning"><?php echo $row['weekm'] ?></td>
						<td class="planning"><input type="text" size="10" name="datem" value="<?php echo fdate($row['datem']); ?>"></td>
						<td class="planning"><?php echo $row['codepeople'].' - '.$row['pnom'].' '.$row['pprenom'] ?></td>
						<td class="planning"><?php echo $row['idclient'].' - '.$row['clsociete']; ?></td>
						<td class="planning"><?php echo $row['departement']; ?></td>
						<td class="planning"><?php echo $row['codeshop'].' - '.$row['ssociete'].' - '.$row['sville']; ?></td>
						<td class="planning"><?php echo $row['ferie']; ?></td>
						<td class="planning"><input type="text" size="4" name="hin1" value="<?php echo ftime($row['hin1']); ?>"></td>
						<td class="planning"><input type="text" size="4" name="hout1" value="<?php echo ftime($row['hout1']); ?>"></td>
						<td class="planning"><input type="text" size="4" name="hin2" value="<?php echo ftime($row['hin2']); ?>"></td>
						<td class="planning"><input type="text" size="4" name="hout2" value="<?php echo ftime($row['hout2']); ?>"></td>
						<td class="planning">
						<?php
							$merch = new coremerch($row['idmerch']);
							$timetot =  $merch->hprest;
							echo fnbr0($timetot);
							$timetotx += $timetot;
							$timetotz += $timetot;
						 ?>
						 </td>
						<td class="planning">
							<input type="text" size="2" name="kmpaye" value="<?php echo fnbr0($row['kmpaye']); ?>">
							<?php $kmpayex += $row['kmpaye']; ?>
							<?php $kmpayez += $row['kmpaye']; ?>
						</td>
						<td class="planning">
							<input type="text" size="2" name="kmfacture" value="<?php echo fnbr0($row['kmfacture']); ?>">
							<?php $kmfacturex += $row['kmfacture']; ?>
							<?php $kmfacturez += $row['kmfacture']; ?>
						</td>
						<td class="planning">
							<?php $kmtemp = $row['kmfacture'] - $row['kmpaye']; echo fnega($kmtemp); ?>
						</td>
						<td class="planning">
							<input type="text" size="3" name="montantpaye" value="<?php echo fnbr($row['montantpaye']); ?>">
							<?php $fraisx += $row['montantpaye']; ?>
							<?php $fraisz += $row['montantpaye']; ?>
						</td>
						<td class="planning">
							<input type="text" size="3" name="montantfacture" value="<?php echo fnbr($row['montantfacture']); ?>">
							<?php $fraisfacturex += $row['montantfacture']; ?>
							<?php $fraisfacturez += $row['montantfacture']; ?>
						</td>
						<td class="planning">
							<?php $fraistemp = $row['montantfacture'] - $row['montantpaye']; echo fnega($fraistemp); ?>
						</td>
						<td class="planning">
							<input type="checkbox" name="contratencode" value="1" <?php if ($row['contratencode'] == '1') { echo 'checked';} ?> >
						</td>
						<td><input type="submit" name="Modifier" value="M"><td>
						<td class="planning" width="13"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=show&act2=listing&idmerch='.$row['idmerch'];?>"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
					</tr>
			</form>
		<?php
		$xpnom = $pnom ;
		$xclient = $pclient ;
		$xweekm = $pweekm ;
		$xdatem = $pdatem ;
		$xcodeshop = $pcodeshop ;
		$xcontratencode = $pcontratencode ;
		} ?>

	<?php
if ($_SESSION['mtype'] == '3') {
	if
		(
			((($_GET['msort'] == 'p.idpeople') or ($_GET['msort'] == 'p.pnom, p.pprenom'))) #people
		 or
			($_GET['msort'] == 'c.idclient') # client
		 or
			($_GET['msort'] == 'me.weekm') # week
		 or
			($_GET['msort'] == 'me.datem') # jour
		 or
			($_GET['msort'] == 's.codeshop') # shop
		 or
			($_GET['msort'] == 'me.contratencode') # shop
		)
	{
	?>
			<tr bgcolor="white">
				<th colspan="6"></th>
				<th colspan="6"></th>
				<th class="planning"><?php echo $timetotz; $timetotz = 0; ?></th>
				<th class="planning"><?php echo $kmpayez; ?></th>
				<th class="planning"><?php echo $kmfacturez; ?></th>
				<th class="planning"><?php $kmdifz = $kmfacturez - $kmpayez ; echo fnega($kmdifz); $kmdifz = 0; $kmpayez = 0; $kmfacturez = 0; ?></th>
				<th class="planning"><?php echo fnbr($fraisz); ?></th>
				<th class="planning"><?php echo fnbr($fraisfacturez); ?></th>
				<th class="planning"><?php $fraisdifz = $fraisfacturez - $fraisz ; echo fnega($fraisdifz); $fraisdifz = 0; $fraisz = 0; $fraisfacturez = 0; ?></th>
				<th class="planning"></th>
			</tr>
	<?php
	}
}
	?>
		<tr>
			<th colspan="6"></th>
		</tr>
		<tr>
			<th colspan="6"></th>
			<th colspan="6"></th>
			<th class="planning"><?php echo $timetotx; ?></th>
			<th class="planning"><?php echo $kmpayex; ?></th>
			<th class="planning"><?php echo $kmfacturex; ?></th>
			<th class="planning"><?php $kmfinal = $kmfacturex - $kmpayex ; echo fnega($kmfinal);?></th>
			<th class="planning"><?php echo fnbr($fraisx); ?></th>
			<th class="planning"><?php echo fnbr($fraisfacturex); ?></th>
			<th class="planning"><?php $fraisfinal = $fraisfacturex - $fraisx ; echo fnega($fraisfinal);?></th>
			<th class="planning"></th>
		</tr>
	</table>
<?php } ?>
<?php
#----------------- FIN LISTING info éditable ET Décompte
?>

<?php
#----------------- LISTING info EAS
?>
<?php if ($_SESSION['mtype'] == '4') {
	$colspa = 8;?>
	<table class="planning" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
		<tr>
		<?php  $colspa = 9;?>
			<th class="planning"><a href="?act=listing&msort=me.idmerch">ID</a></th>
			<th class="planning"><a href="?act=listing&msort=me.weekm">S</a></th>
			<th class="planning"><a href="?act=listing&msort=me.datem">Date</a></th>
			<th class="planning"><a href="?act=listing&msort=p.idpeople">id</a> - <a href="?act=listing&msort=p.pnom, p.pprenom">Nom People</a></th>
			<th class="planning">id <a href="?act=listing&msort=c.idclient">Clients</a></th>
			<th class="planning">id <a href="?act=listing&msort=s.codeshop">Lieux</a></th>
			<th class="planning">KM P</th>
			<th class="planning">KM F</th>
			<th class="planning">P - F</th>
			<th class="planning">R&eacute;currence</th>
			<th class="planning"><a href="?act=listing&msort=me.easremplac">Journalier</a></th>
			<th class="planning"><a href="?act=listing&msort=me.contratencode">C enco</a></th>
			<th class="planning"><a href="?act=listing&msort=me.rapportencode">R enco</a></th>
			<th class="planning">Updt</th>
		</tr>
		<?php
		$xpnom = '';
		$xclient = '';
		foreach ($merchs as $row) {
		if ($row['genre'] == 'EAS') {
			#> Changement de couleur des lignes #####>>####
			$i++;
			if (fmod($i, 2) == 1) {
				$tr = '<tr bgcolor="#9CBECA">';
			} else {
				$tr = '<tr bgcolor="#8CAAB5">';
			}
			#< Changement de couleur des lignes #####<<####
		?>
			<form action="?act=listingmodifeas" method="post">
				<input type="hidden" name="idmerch" value="<?php echo $row['idmerch'] ;?>">
				<?php
					### récap people si tri people
					$pnom = $row['idpeople']; # people
					$pclient = $row['idclient']; # client
					$pweekm = $row['weekm']; # semaine
					$pdatem = $row['datem']; # jour
					$pcodeshop = $row['codeshop']; # shop
					$pcontratencode = $row['contratencode']; # contratencode
					if ($pnom == '') { $pnom = 'z'; }
					if ($pclient == '') { $pclient = 'z'; }
					if ($pweekm == '') { $pweekm = 'z'; }
					if ($pdatem == '') { $pdatem = 'z'; }
					if ($pcodeshop == '') { $pcodeshop = 'z'; }
					if ($pcontratencode == '') { $pcontratencode = 'z'; }

					###/ récap people si tri people
						echo $tr;
				?>
						<td class="planning"><?php echo $row['idmerch'] ?></td>
						<td class="planning"><?php echo $row['weekm'] ?></td>
						<td class="planning"><input type="text" size="10" name="datem" value="<?php echo fdate($row['datem']); ?>"></td>
						<td class="planning"><?php echo $row['codepeople'].' - '.$row['pnom'].' '.$row['pprenom'] ?></td>
						<td class="planning"><?php echo $row['idclient'].' - '.$row['clsociete']; ?></td>
						<td class="planning"><?php echo $row['codeshop'].' - '.$row['ssociete'].' - '.$row['sville']; ?></td>
						<td class="planning">
							<input type="text" size="2" name="kmpaye" value="<?php echo fnbr($row['kmpaye']); ?>">
							<?php $kmpayex += $row['kmpaye']; ?>
							<?php $kmpayez += $row['kmpaye']; ?>
						</td>
						<td class="planning">
							<input type="text" size="2" name="kmfacture" value="<?php echo fnbr($row['kmfacture']); ?>">
							<?php $kmfacturex += $row['kmfacture']; ?>
							<?php $kmfacturez += $row['kmfacture']; ?>
						</td>
						<td class="planning">
							<?php $kmtemp = $row['kmfacture'] - $row['kmpaye']; echo fnega($kmtemp); ?>
						</td>
						<td class="planning">
							<input type="radio" name="recurrence" value="1" <?php if ($row['recurrence'] == '1') { echo 'checked';} ?>>&nbsp;O&nbsp;<input type="radio" name="recurrence" value="0" <?php if ($row['recurrence'] == '0') { echo 'checked';} ?>>&nbsp;N
						</td>
						<td class="planning">
							<input type="radio" name="easremplac" value="1" <?php if ($row['easremplac'] == '1') { echo 'checked';} ?>>&nbsp;O&nbsp;<input type="radio" name="easremplac" value="0" <?php if ($row['easremplac'] != '1') { echo 'checked';} ?>>&nbsp;N
						</td>
						<td class="planning">
							<input type="checkbox" name="contratencode" value="1" <?php if ($row['contratencode'] == '1') { echo 'checked';} ?> >
						</td>
						<td class="planning">
							<input type="checkbox" name="rapportencode" value="1" <?php if ($row['rapportencode'] == '1') { echo 'checked';} ?> >
						</td>
						<td><input type="submit" name="Modifier" value="M"><td>
						<td class="planning" width="13"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=show&act2=listing&idmerch='.$row['idmerch'];?>"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
					</tr>
			</form>
		<?php
		$xpnom = $pnom ;
		$xclient = $pclient ;
		$xweekm = $pweekm ;
		$xdatem = $pdatem ;
		$xcodeshop = $pcodeshop ;
		$xcontratencode = $pcontratencode ;
		}
		}?>

		<tr>
			<th colspan="6"></th>
		</tr>
		<tr>
			<th colspan="6"></th>
			<th class="planning"><?php echo $kmpayex; ?></th>
			<th class="planning"><?php echo $kmfacturex; ?></th>
			<th class="planning"><?php $kmfinal = $kmfacturex - $kmpayex ; echo fnega($kmfinal);?></th>
		</tr>
	</table>
<?php }


#----------------- FIN Listing info EAS

######################################################################################################################################################################
### Routing #######################################################################################################################################################  #
if ($_SESSION['mtype'] == '5') {

	foreach ($merchs as $row) {
		$rtable[$row['idpeople']][] = $row['datem'];
	}

	foreach ($rtable as $peop => $dates) {







		/*
			TODO : à voir avec Nico
		*/






	}




	echo '<table class="'.$classe.'" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">';




	echo '</table>';
}
### Routing #######################################################################################################################################################  #
######################################################################################################################################################################
?>
<br>
<div align="center">
	<a href="<?php echo $_SERVER['PHP_SELF'].'?act=listing&mtype=L';?>"><font color="white">Listing</font></a> /
	<a href="<?php echo $_SERVER['PHP_SELF'].'?act=listing&mtype=2&msort=me.weekm, me.datem';?>"><font color="white">Info &eacute;ditable</font></a> /
	<a href="<?php echo $_SERVER['PHP_SELF'].'?act=listing&mtype=3&msort=p.idpeople';?>"><font color="white">D&eacute;compte</font></a> /
	<a href="<?php echo $_SERVER['PHP_SELF'].'?act=listing&mtype=4&msort=me.weekm, me.datem';?>"><font color="white">Info EAS</font></a>
</div>
<br>
</div>
