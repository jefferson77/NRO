<div id="centerzonelarge">
<?php
## init
$emails = '';

$listing = new db();

$table = ($_REQUEST['zoutmerch'] == 'yes')?'zoutmerch':'merch';

if(empty($_SESSION['sqlwhere'])) {

	$searchfields = array(
			'a.idagent' => 'idagent',
			'me.idmerch' => 'idmerch',
			'me.genre' => 'genre',
			'p.pnom' => 'pnom',
			'p.pprenom' => 'pprenom',
			'p.codepeople' => 'codepeople',
			'c.codeclient' => 'codeclient',
			'c.societe' => 'csociete',
			's.codeshop' => 'codeshop',
			's.societe' => 'ssociete',
			's.ville' => 'sville',
			'me.datem' => 'date',
			'me.weekm' => 'weekm',
			'me.produit' => 'produit'
		);

	if ($table == 'zoutmerch') {
		$searchfields['me.raisonout'] = 'raisonout';
		$searchfields['me.noteout'] = 'noteout';
	}

	$quid = $listing->MAKEsearch($searchfields);

	if (!empty($_POST['weekm']) and !empty($_POST['yearm'])) {
		if (!empty($quid)) { $quid .= " AND "; $quod .= " ET "; }
		$quid .= "me.yearm = '".$_POST['yearm']."'";
		$quod .= "yearm = ".$_POST['yearm'];
	}

	if (!empty($_POST['todo'])) {
		if (!empty($quid)) { $quid .= " AND "; $quod .= " ET "; }
		$quid .= " (me.idpeople IS NULL OR me.idpeople = '')";
		$quod .= ' + to do';
	}

	$_SESSION['sqlwhere'] = $quid;
	$_SESSION['sqltxt'] = $quod;
}

$listing->inline("
	SELECT
		me.idmerch, me.datem, me.weekm, me.genre, me.facnum,
		me.hin1, me.hout1, me.hin2, me.hout2,
		me.kmpaye, me.kmfacture,
		me.produit, me.facturation,
		me.ferie, me.contratencode, me.rapportencode, me.recurrence, me.easremplac,
		a.prenom, a.idagent,
		c.codeclient, c.societe AS clsociete, c.idclient, c.tel, c.fax,
		co.idcofficer, co.qualite, co.onom, co.oprenom, co.fax AS cofax, co.langue, co.departement,
		s.idshop, s.codeshop, s.societe AS ssociete, s.ville AS sville,
		p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.gsm, p.codepeople, p.email,
		nf.montantpaye, nf.montantfacture
	FROM ".$table." me
		LEFT JOIN agent a ON me.idagent = a.idagent
		LEFT JOIN client c ON me.idclient = c.idclient
		LEFT JOIN cofficer co ON me.idcofficer = co.idcofficer
		LEFT JOIN people p ON me.idpeople = p.idpeople
		LEFT JOIN shop s ON me.idshop = s.idshop
		LEFT JOIN notefrais nf ON nf.secteur = 'ME' AND nf.idmission = me.idmerch
	WHERE ".$_SESSION['sqlwhere']."
	ORDER BY me.datem DESC LIMIT 400");

$FoundCount = mysql_num_rows($listing->result);

?>
<fieldset>
	<legend>
		<b>listing des Merch - Historic</b>
	</legend>
	<b>Votre Recherche : <?php echo $quod; ?></b><br>
	<?php if($FoundCount == 400) echo '(Il y a trop de résultats pour cette recherche, seuls les 200 premiers seront affichés)'; ?>
</fieldset>


<br>
<?php
$colspa = 8;?>
<form action="<?php echo NIVO ?>print/merch/printmerch.php" method="post" target="popup" onsubmit="OpenBrWindow('_blank','popup','scrollbars=yes,status=yes,resizable=yes','500','400','true')" >
	<table class="sortable-onload-1r rowstyle-alt no-arrow" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
		<thead>
		<?php if ($table != 'zoutmerch'): ?>
			<tr>
				<th colspan="2">SEND : <img src="<?php echo STATIK ?>illus/mail.png" width="16" height="16" alt="Mail" border="0" title="Adresses Mail" align="top" id="showmails"></th>
				<th colspan="10" align="right">
					Planning : <font color="#333F69">
						<input type="checkbox" name="ptype[]" value="planningint"> Interne &nbsp;&nbsp;
						<input type="checkbox" name="ptype[]" value="planningclient"> Client &nbsp;&nbsp;
						<input type="checkbox" name="ptype[]" value="planningshop"> Shop &nbsp;</font>|&nbsp;
					<input type="checkbox" name="ptype[]" value="contrat">Contrat &nbsp;|&nbsp;
					<input type="checkbox" name="ptype[]" value="etiqpeop">Etiquettes People &nbsp;&nbsp;
				</th>
				<th colspan="9" align="center">
				</th>
				<th><input type="submit" class="btn printer"></th>
			</tr>
		<?php endif ?>
			<tr>
				<th colspan="2"></th>
				<th colspan="3">People</th>
				<th colspan="4">Client</th>
				<th colspan="3">Lieu</th>
				<th></th>
				<th colspan="3">D&eacute;pl</th>
				<th colspan="3">Frais</th>
				<th colspan="3"></th>
			</tr>
			<tr>
				<th class="sortable-numeric">Mission</th>
				<th class="sortable-date-dmy">Date</th>
				<th>L</th>
				<th class="sortable-numeric">Code</th>
				<th class="sortable-text">Nom People</th>
				<th>ID</th>
				<th>Societe</th>
				<th>L</th>
				<th>Departement</th>
				<th>ID</th>
				<th>Societe</th>
				<th>Ville</th>
				<th>heures</th>
				<th>Pay</th>
				<th>Fac</th>
				<th>&#8710;</th>
				<th>Pay</th>
				<th>Fac</th>
				<th>&#8710;</th>
				<th>etat</th>
				<th>Fac n&deg;</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php while ($row = mysql_fetch_array($listing->result)) {
			if (!empty($row['email'])) $emails[] = $row['email'];

			?>
			<tr ondblclick="location.href='<?php echo $_SERVER['PHP_SELF'].'?act=show&idmerch='.$row['idmerch'].'&facturation='.$row['facturation'].'&table='.$table;?>'">
				<td><?php echo $row['idmerch'] ?></td>
				<td><?php echo fdate($row['datem']); ?></td>
				<td><img src="<?php echo STATIK ?>illus/<?php echo $row['lbureau']; ?>.gif" alt="<?php echo $row['lbureau']; ?>" width="12" height="9"></td>
				<td><?php echo $row['codepeople']; ?></td>
				<td><a href="<?php echo NIVO.'data/people/adminpeople.php?act=show&idpeople='.$row['idpeople'];?>" target="_blank"><?php echo $row['pnom'].' '.$row['pprenom']; ?></a></td>
				<td><?php echo $row['codeclient']; ?></td>
				<td><?php echo $row['clsociete']; ?></td>
				<td><?php echo $row['langue']; ?></td>
				<td><?php echo $row['departement']; ?></td>
				<td><?php echo $row['codeshop']; ?></td>
				<td><?php echo $row['ssociete']; ?></td>
				<td><?php echo $row['sville']; ?></td>
				<td style="text-align: center;">
				<?php
					$merch = new coremerch($row['idmerch']);
					echo fnbr0($merch->hprest);
					$timetotx += $merch->hprest;
				 ?>
				 </td>
				<td style="text-align: center;">
				<?php echo fnbr0($row['kmpaye']); $kmpayex += $row['kmpaye']; ?>
				</td>
				<td style="text-align: center;">
				<?php echo fnbr0($row['kmfacture']); $kmfacturex += $row['kmfacture']; ?>
				</td>
				<td style="text-align: center;">
				<?php $kmtemp = $row['kmfacture'] - $row['kmpaye']; echo fnega($kmtemp); ?>
				</td>
				<td style="text-align: center;">
				<?php echo fnbr0($row['montantpaye']); $fraisx += $row['montantpaye']; ?>
				</td>
				<td style="text-align: center;">
				<?php echo fnbr0($row['montantfacture']); $fraisfacturex += $row['montantfacture']; ?>
				</td>
				<td style="text-align: center;">
				<?php echo fnega($row['montantfacture'] - $row['montantpaye']); ?>
				</td>
				<td><?php echo $row['facturation']; ?></td>
				<td><?php echo $row['facnum']; ?></td>
				<td width="15"><input type="checkbox" name="print[]" checked value="<?php echo $row['idmerch']; ?>"></td>
			</tr>
		<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="12"></th>
				<th><?php echo $timetotx; ?></th>
				<th><?php echo $kmpayex; ?></th>
				<th><?php echo $kmfacturex; ?></th>
				<th><?php $kmfinal = $kmfacturex - $kmpayex ; echo fnega($kmfinal);?></th>
				<th><?php echo $fraisx; ?></th>
				<th><?php echo $fraisfacturex; ?></th>
				<th><?php $fraisfinal = $fraisfacturex - $fraisx ; echo fnega($fraisfinal);?></th>
				<th></th>
				<th></th>
				<th></th>
			</tr>
		</tfoot>
	</table>
</form>
</div>
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