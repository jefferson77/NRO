<div id="centerzonelarge">
<?php
if (!empty($_GET['sort'])) {
	$_SESSION['animhistoricsort'] = $_GET['sort'].', p.pnom, p.pprenom, an.datem';
} else {
	$_SESSION['animhistoricsort'] = 'p.pnom, p.pprenom, an.datem';
}
### PATCODE !!!

switch ($_GET['action']) {
	case "skip":
		$skip = (!empty($_GET['skip']))?$_GET['skip']:0;

		$skipprev = $skip - 25;
		$skipnext = $skip + 25;

		# VARIABLE SELECT
		$historicquod = $_SESSION['animhistoricquod'];

		$listing = new db();

		$recherche='
			'.$_SESSION['animhistoricsearch'].'
			'.$_SESSION['animhistoricquid'].'
			 ORDER BY '.$_SESSION['animhistoricsort'].'
		';

	break;
	### Première étape : Afficher la liste des Anim a la recherche SANS SORT
	default:
		$sort = $_SESSION['animhistoricsort'];
		$quod = $_SESSION['animhistoricquod'];
		$quid = $_SESSION['animhistoricquid'];
		$search = $_SESSION['animhistoricsearch'];

		# VARIABLE $_SESSION['prenom'] pour recherche direct
		if ($_GET['listing'] == 'direct') {
			$quid = "a.prenom LIKE '%".$_SESSION['prenom']."%'";
			$quod = "prenom agent = ".$_SESSION['prenom'];
			$sort = 'an.datem';
		}


		# VARIABLE $_SESSION['prenom'] pour recherche missing / to do
		if ($_GET['listing'] == 'missing') {
			$quid = "a.prenom LIKE '%".$_SESSION['prenom']."%'";
			$_POST['todo'] = 'IS NULL';
			$quid .= " AND (";
			$quid .= "an.idpeople ".$_POST['todo'];
			$_POST['todo'] = "'')";
			$quid .= " OR ";
			$quid .= "an.idpeople = ".$_POST['todo'];
			$quod = "prenom agent = ".$_SESSION['prenom'];
			$sort = 'an.datem';
		}

		# VARIABLE skip
		if (!empty($_GET['skip'])) {
			$skip=$_GET['skip'];
		}
		else {$skip = 0;}
		$skipprev = $skip-25;
		$skipnext = $skip+25;

		# VARIABLE SELECT


		$listing = new db();

		$searchfields = array (
			'a.idagent' 			=> 'idagent',
			'an.idanimation' 	=> 'idanimation',
			'an.idanimjob' 		=> 'idanimjob',
			'an.genre' 			=> 'genre',
			'j.reference' 		=> 'referencejob',
			'an.reference' 		=> 'reference',
			'p.pnom' 			=> 'pnom',
			'p.pprenom' 		=> 'pprenom',
			'p.codepeople' 		=> 'codepeople',
			'c.codeclient' 		=> 'codeclient',
			'c.societe' 		=> 'csociete',
			's.codeshop' 		=> 'codeshop',
			's.societe' 		=> 'ssociete',
			's.ville' 			=> 'sville',
			'an.produit' 		=> 'produit',
			'j.boncommande' 	=> 'boncommande'
		);

		$quid = $listing->MAKEsearch($searchfields);

		if (!empty($_POST['todo'])) {
			if (!empty($quid))
			{
				$quid .= " AND ";
				$quod .= " ET ";
			}
			$quid .= "( an.idpeople IS NULL OR an.idpeople = '')";
			$quod .= "To Do ";
		}

		if (($_POST['date2'] == '') and ($_POST['date1'] != '')) { $_POST['date2'] = $_POST['date1'];}

		if ($_POST['date1'] != '') {
			$_POST['date1'] = fdatebk($_POST['date1']);
			if (!empty($quid))
			{
				$quid .= " AND ";
				$quod .= " ET ";
			}
			$quid .= "an.datem >= '".$_POST['date1']."'";
			$quod .= "date1 = ".$_POST['date1'];
		}
		if ($_POST['date2'] != '') {
			$_POST['date2'] = fdatebk($_POST['date2']);
			if (!empty($quid))
			{
				$quid .= " AND ";
				$quod .= " ET ";
			}

			$quid .= "an.datem <= '".$_POST['date2']."'";
			$quod .= "date2 = ".$_POST['date2'];
		}


		if (($_POST['weekm2'] == '') and ($_POST['weekm1'] != '')) { $_POST['weekm2'] = $_POST['weekm1'];}

		if ($_POST['weekm1'] != '') {
			if (!empty($quid))
			{
				$quid .= " AND ";
				$quod .= " ET ";
			}
			$quid .= "an.weekm >= '".$_POST['weekm1']."'";
			$quod .= "weekm1 = ".$_POST['weekm1'];
		}
		if ($_POST['weekm2'] != '') {
			if (!empty($quid))
			{
				$quid .= " AND ";
				$quod .= " ET ";
			}

			$quid .= "an.weekm <= '".$_POST['weekm2']."'";
			$quod .= "weekm2 = ".$_POST['weekm2'];
		}

		if (($_POST['weekm2'] != '') or ($_POST['weekm1'] != '')) {
			if (!empty($_POST['yearm'])) {
				if (!empty($quid))
				{
					$quid .= " AND ";
					$quod .= " ET ";
				}
				$quid .= "an.yearm = '".$_POST['yearm']."'";
				$quod .= "yearm = ".$_POST['yearm'];
			}
		}

		if (!empty($_POST['facturation'])) {
			if (!empty($quid))
			{
				$quid .= " AND ";
				$quod .= " ET ";
			}
			$quod = $quod." facturation = ";
			$quid .= "(";
			foreach ($_POST['facturation'] as $val) {
				if (!empty($val)) {
					if ($quid1 != '')
					{
						$quid1=$quid1." OR ";
					}
					$quid1 .= "an.facturation = ".$val;
					$quod = $quod." ".$val;
				}
			}
			$quid .= ''.$quid1.")";
		}




		if (!empty($quid)) {$quid='WHERE '.$quid;}

		$recherche1 = "SELECT
				an.idanimation, an.datem, an.weekm, an.yearm, an.reference, an.hin1, an.hout1, an.hin2, an.hout2,
				an.kmpaye, an.kmfacture, an.isbriefing, an.produit,  an.facturation, an.peoplenote,
				an.ferie, an.idanimjob, an.livraisonpaye, an.livraisonfacture,
				j.genre, j.reference, j.boncommande,
				a.prenom, a.idagent,
				c.codeclient, c.societe AS clsociete, c.idclient, c.tel, c.fax,
				co.idcofficer, co.qualite, co.onom, co.oprenom, co.fax AS cofax,
				s.idshop, s.codeshop, s.societe AS ssociete, s.ville AS sville,
				p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.gsm, p.codepeople,
				f.idfac, f.modefac,
				nf.montantpaye, nf.montantfacture

				FROM animation an
				LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
				LEFT JOIN agent a ON j.idagent = a.idagent
				LEFT JOIN client c ON j.idclient = c.idclient
				LEFT JOIN cofficer co ON j.idcofficer = co.idcofficer
				LEFT JOIN people p ON an.idpeople = p.idpeople
				LEFT JOIN shop s ON an.idshop = s.idshop
				LEFT JOIN facture f ON an.facnum = f.idfac
				LEFT JOIN notefrais nf ON an.idanimation = nf.idmission AND nf.secteur = 'AN'";

		$recherche='
			'.$recherche1.'
			'.$quid.'
			 ORDER BY '.$sort.'
		';

		$_SESSION['animhistoricquid'] = $quid;
		$_SESSION['animhistoricquod'] = $quod;
		$_SESSION['animhistoricsort'] = $sort;
		$_SESSION['animhistoricsearch'] = $recherche1;
}

# Recherche des résultats
$listing->inline($recherche);
$FoundCount = mysql_num_rows($listing->result);

?>
<fieldset>
	<legend><b>listing des Anim - Historic</b></legend>
	<b>Votre Recherche : (<?php echo $FoundCount; ?>) <?php echo $_SESSION['animhistoricquod']; ?></b><br>
</fieldset>
<br>
<font color="white">
1 En cours - 2 A v&eacute;rifier - 3 A corriger - 4 A re v&eacute;rifier - 5 Prefactoring - 6 Factoring - 8 Factur&eacute;es - 9 FL
<br>
20 Purge FL - 25 Purge Out - 95 Out Celsys - 97 Out Exception - 98 Out Annul&eacute;
</font>
<br><br>
<?php
# ------- DEBUT LISTING GENERAL --------
	$colspa = 8;?>
<form action="<?php echo NIVO ?>print/anim/printanim.php?historic=historic" method="post" target="popup" onsubmit="OpenBrWindow('_blank','popup','scrollbars=yes,status=yes,resizable=yes','500','400','true')" >
	<table class="planning" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="planning" colspan="7" align="right">
				<input type="checkbox" name="ptype[]" value="planningint"><font color="#CCC">Planning Int</font>
				<input type="checkbox" name="ptype[]" value="planning"><font color="#CCC">Planning Ext</font>
				<input type="checkbox" name="ptype[]" value="contrat">Contrat
				<input type="checkbox" name="ptype[]" value="rapportp"><font color="#CCC">Rapport people</font>
				<input type="checkbox" name="ptype[]" value="rapportc">Rapport client
				 &nbsp;
			</th>
			<th class="planning" colspan="12"></th>
			<th class="planning"><input type="submit" class="btn printer"></th>
			<th class="planning" colspan="2"></th>
		</tr>
		<tr>
		<?php  $colspa = 9;?>
			<th class="planning"><a href="?act=historic&action=skip&sort=an.idanimjob">J</a></th>
			<th class="planning"><a href="?act=historic&action=skip&sort=an.idanimation">M</a></th>
			<th class="planning">Fac</th>
			<th class="planning"><a href="?act=historic&action=skip&sort=an.datem">Date</a></th>
			<th class="planning"><a href="?act=historic&action=skip&sort=p.idpeople">code - id</a> - <a href="?act=historic&action=skip&sort=p.pnom, p.pprenom">Nom People</a></th>
			<th class="planning"><a href="?act=historic&action=skip&sort=c.idclient">Clients</a></th>
			<th class="planning"><a href="?act=historic&action=skip&sort=s.codeshop">Lieux</a></th>
			<th class="planning">heures</th>
			<th class="planning">KM P</th>
			<th class="planning">KM F</th>
			<th class="planning">P - F</th>
			<th class="planning">Fra P</th>
			<th class="planning">Fra F</th>
			<th class="planning">P - F</th>
			<th class="planning">Liv P</th>
			<th class="planning">Liv F</th>
			<th class="planning">P - F</th>
			<th class="planning">Bri</th>
			<th class="planning">view</th>
			<th class="planning"></th>
			<th class="planning">J</th>
			<th class="planning">M</th>
		</tr>
		<?php
		$xpnom = '';
		while ($row = mysql_fetch_array($listing->result)) {
			#> Changement de couleur des lignes #####>>####
			$i++;
			if (fmod($i, 2) == 1) {
				$tr = '<tr bgcolor="#9CBECA">';
			} else {
				$tr = '<tr bgcolor="#8CAAB5">';
			}
			#< Changement de couleur des lignes #####<<####
		?>
				<?php
					$pnom = $row['idpeople'];
#					if ((!empty($xpnom)) AND ($xpnom != $pnom)) {
					if ($xpnom != $pnom) {
					?>
						<tr bgcolor="white">
							<th colspan="7"></th>
							<th class="planning"><?php echo $timetotz; $timetotz = 0; ?></th>
							<th class="planning"><?php echo $kmpayez; ?></th>
							<th class="planning"><?php echo $kmfacture; ?></th>
							<th class="planning"><?php $kmdifz = $kmfacturez - $kmpayez ; echo fnega($kmdifz); $kmdifz = 0; $kmpayez = 0; $kmfacturez = 0; ?></th>
							<th class="planning"><?php echo $fraisz; $fraisz = 0; ?></th>
							<th class="planning"><?php echo $fraisfacturez; $fraisfacturez = 0; ?></th>
							<th class="planning"><?php $fraisdifz = $fraisfacturez - $fraisz ; echo fnega($fraisdifz); $fraisdifz = 0; $fraisz = 0; $fraisfacturez = 0; ?></th>
							<th class="planning"><?php echo $livraisonpayez; $livraisonpayez = 0; ?></th>
							<th class="planning"><?php echo $livraisonfacturez; $livraisonfacturez = 0; ?></th>
							<th class="planning"><?php $livraisondifz = $livraisonfacturez - $livraisonpayez ; echo fnega($livraisondifz); $livraisondifz = 0; $livraisonpayez = 0; $livraisonfacturez = 0; ?></th>
							<th class="planning"><?php echo $briefingz; $briefingz = 0; ?></th>
							<th colspan="4"></th>
						<?php
						echo '</tr>'.$tr;
					}
				?>
						<td class="planning"><?php echo $row['idanimjob'] ?></td>
						<td class="planning"><?php echo $row['idanimation'] ?> <?php echo $row['reference'] ?></td>
						<td class="planning"><?php echo $row['idfac'] ?> / <?php echo $row['modefac'] ?></td>
						<td class="planning"><?php echo fdate($row['datem']); ?></td>
						<td class="planning"><img src="<?php echo STATIK ?>illus/<?php echo $row['lbureau']; ?>.gif" alt="<?php echo $row['lbureau']; ?>" width="12" height="9"> <?php echo $row['codepeople'].' - '.$row['idpeople'].' - '.$row['pnom'].' '.$row['pprenom'] ?> <a href="<?php echo NIVO.'data/people/adminpeople.php?act=show&idpeople='.$row['idpeople'];?>" target="_blank"><img src="<?php echo STATIK ?>illus/icon_profile.gif" border="0" width="15" height="12"></a></td>
						<td class="planning"><?php echo $row['codeclient']; ?> - <?php echo $row['clsociete']; ?></td>
							<td class="planning"><?php echo $row['codeshop']; ?> - <?php echo $row['ssociete']; ?> - <?php echo $row['sville']; ?></td>
						<td class="planning">
						<?php
							$anim = new coreanim($row['idanimation']);
							$timetot =  $anim->hprest;
							echo $timetot;
							$timetotx = $timetotx + $timetot;
							$timetotz = $timetotz + $timetot;
						 ?>
						 </td>
						<td class="planning">
							<?php echo fnbr($row['kmpaye']); ?>
							<?php $kmpayex = $kmpayex + fnbr($row['kmpaye']); ?>
							<?php $kmpayez = $kmpayez + fnbr($row['kmpaye']); ?>
						</td>
						<td class="planning">
							<?php echo fnbr($row['kmfacture']); ?>
							<?php $kmfacturex = $kmfacturex + fnbr($row['kmfacture']); ?>
							<?php $kmfacturez = $kmfacturez + fnbr($row['kmfacture']); ?>
						</td>
						<td class="planning">
							<?php $kmtemp = fnbr($row['kmfacture']) - fnbr($row['kmpaye']); echo fnega($kmtemp); ?>
						</td>
						<td class="planning">
							<?php echo fnbr($row['montantpaye']); ?>
							<?php $fraisx = $fraisx + $row['montantpaye']; ?>
							<?php $fraisz = $fraisz + $row['montantpaye']; ?>
						</td>
						<td class="planning">
							<?php echo fnbr($row['montantfacture']); ?>
							<?php $fraisfacturex = $fraisfacturex + $row['montantfacture']; ?>
							<?php $fraisfacturez = $fraisfacturez + $row['montantfacture']; ?>
						</td>
						<td class="planning">
							<?php $fraistemp = fnbr($row['montantfacture']) - fnbr($row['montantpaye']); echo fnega($fraistemp); ?>
						</td>

						<td class="planning">
							<?php echo fnbr($row['livraisonpaye']); ?>
							<?php $livraisonpayex = $livraisonpayex + $row['livraisonpaye']; ?>
							<?php $livraisonpayez = $livraisonpayez + $row['livraisonpaye']; ?>
						</td>
						<td class="planning">
							<?php echo fnbr($row['livraisonfacture']); ?>
							<?php $livraisonfacturex = $livraisonfacturex + $row['livraisonfacture']; ?>
							<?php $livraisonfacturez = $livraisonfacturez + $row['livraisonfacture']; ?>
						</td>
						<td class="planning">
							<?php $livraisontemp = fnbr($row['livraisonfacture']) - fnbr($row['livraisonpaye']); echo fnega($livraisontemp); ?>
						</td>
						<td class="planning">
						<?php echo fnbr($row['briefing']); ?>
						<?php $briefingx = $briefingx + fnbr($row['briefing']); ?>
						<?php $briefingz = $briefingz + fnbr($row['briefing']); ?>
						</td>
						<td><?php echo $row['facturation']; ?></td>
						<th class="planning" width="15"><input type="checkbox" name="print[]" checked value="<?php echo $row['idanimation'] ?>"></th>
						<td class="planning" width="13"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=showjob&idanimation='.$row['idanimation'].'&idanimjob='.$row['idanimjob'].'&facturation='.$row['facturation'];?>"><img src="<?php echo STATIK ?>illus/stock_right-16.png" alt="search" width="13" height="15" border="0"></a></td>
						<td class="planning" width="13"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=showmission&idanimation='.$row['idanimation'].'&idanimjob='.$row['idanimjob'].'&facturation='.$row['facturation'];?>"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
					</tr>
		<?php
		$xpnom = $pnom ;
		} ?>

			<tr bgcolor="white">
				<th colspan="7"></th>
				<th class="planning"><?php echo $timetotz; $timetotz = 0; ?></th>
				<th class="planning"><?php echo $kmpayez; ?></th>
				<th class="planning"><?php echo $kmfacturez; ?></th>
				<th class="planning"><?php $kmdifz = $kmfacturez - $kmpayez ; echo fnega($kmdifz); $kmdifz = 0; $kmpayez = 0; $kmfacturez = 0; ?></th>
				<th class="planning"><?php echo fnbr($fraisz); $fraisz = 0; ?></th>
				<th class="planning"><?php echo fnbr($fraisfacturez); $fraisfacturez = 0; ?></th>
				<th class="planning"><?php $fraisdifz = $fraisfacturez - $fraisz ; echo fnega($fraisdifz); $fraisdifz = 0; $fraisz = 0; $fraisfacturez = 0; ?></th>
				<th class="planning"><?php echo fnbr($livraisonpayez); $livraisonpayez = 0; ?></th>
				<th class="planning"><?php echo fnbr($livraisonfacturez); $livraisonfacturez = 0; ?></th>
				<th class="planning"><?php $livraisondifz = $livraisonfacturez - $livraisonpayez ; echo fnega($livraisondifz); $livraisondifz = 0; $livraisonpayez = 0; $livraisonfacturez = 0; ?></th>
				<th class="planning"><?php echo $briefingz; $briefingz = 0; ?></th>
				<th colspan="4"></th>
			</tr>
		<tr>
			<th colspan="7"></th>
			<th class="planning"><?php echo $timetotx; ?></th>
			<th class="planning"><?php echo $kmpayex; ?></th>
			<th class="planning"><?php echo $kmfacturex; ?></th>
			<th class="planning"><?php $kmfinal = $kmfacturex - $kmpayex ; echo fnega($kmfinal);?></th>
			<th class="planning"><?php echo fnbr($fraisx); ?></th>
			<th class="planning"><?php echo fnbr($fraisfacturex); ?></th>
			<th class="planning"><?php $fraisfinal = $fraisfacturex - $fraisx ; echo fnega($fraisfinal);?></th>
			<th class="planning"><?php echo fnbr($livraisonpayex); ?></th>
			<th class="planning"><?php echo fnbr($livraisonfacturex); ?></th>
			<th class="planning"><?php $livraisonfinal = $livraisonfacturex - $livraisonpayex ; echo fnega($livraisonfinal);?></th>
			<th class="planning"><?php echo $briefingx; ?></th>
			<th colspan="4"></th>
		</tr>
		<tr>
			<th class="planning"><a href="?act=historic&action=skip&sort=an.idanimjob">J</a></th>
			<th class="planning"><a href="?act=historic&action=skip&sort=an.idanimation">M</a></th>
			<th class="planning">Fac</th>
			<th class="planning"><a href="?act=historic&action=skip&sort=an.datem">Date</a></th>
			<th class="planning"><a href="?act=historic&action=skip&sort=p.idpeople">code - id</a> - <a href="?act=historic&action=skip&sort=p.pnom, p.pprenom">Nom People</a></th>
			<th class="planning"><a href="?act=historic&action=skip&sort=c.idclient">Clients</a></th>
			<th class="planning"><a href="?act=historic&action=skip&sort=s.codeshop">Lieux</a></th>
			<th class="planning">heures</th>
			<th class="planning">KM P</th>
			<th class="planning">KM F</th>
			<th class="planning">P - F</th>
			<th class="planning">Fra P</th>
			<th class="planning">Fra F</th>
			<th class="planning">P - F</th>
			<th class="planning">Liv P</th>
			<th class="planning">Liv F</th>
			<th class="planning">P - F</th>
			<th class="planning">Bri</th>
			<th class="planning">view</th>
			<th class="planning"></th>
			<th class="planning">J</th>
			<th class="planning">M</th>
		</tr>
	</table>
</form>
<?php echo 'total anims: '.$i; ?>
<br>
<?php echo 'total people si tri&eacute; par idpeople : '.$y; ?>
<br>
</div>