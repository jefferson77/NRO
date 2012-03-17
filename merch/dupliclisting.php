<?php
$listing = new db();

if ($_GET['action'] != "skip") {
	if ($_GET['listing'] == 'direct') {
		$quid = "a.prenom LIKE '%".$_SESSION['prenom']."%'";
		$quod = "prenom agent = ".$_SESSION['prenom'];
	} elseif ($_GET['listing'] == 'missing') {
		$quid = "a.prenom LIKE '%".$_SESSION['prenom']."%' AND (me.idpeople IS NULL OR me.idpeople = '')";
		$quod = "prenom agent = ".$_SESSION['prenom'];
	} else {
		$searchfields = array(
				'a.prenom' => 'prenom',
				'me.idmerch' => 'idmerch',
				'me.genre' => 'genre',
				'me.reference' => 'reference',
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
				'me.produit' => 'produit',
				'me.boncommande' => 'boncommande',
				'me.contratencode' => 'contratencode'
			);

		$quid = $listing->MAKEsearch($searchfields);

		if (!empty($_POST['todo'])) {
			if (!empty($quid)) 
			{
				$quid .= " AND "; $quod .= " ET ";
			}	
			$quid .= "(me.idpeople IS NULL OR me.idpeople = '')";
			$quod .= "To Do ";
		}
	}
	
	$_SESSION['merchjobquid'] = $quid;
}

$listing->inline('SELECT
				me.idmerch, me.datem, me.weekm, me.genre, 
				me.hin1, me.hout1, me.hin2, me.hout2, 
				me.kmpaye, me.kmfacture, me.frais, me.fraisfacture, 
				me.produit, me.facturation, 
				me.ferie, me.contratencode,
				a.prenom, a.idagent, 
				c.codeclient, c.societe AS clsociete, c.idclient, c.tel, c.fax, 
				co.idcofficer, co.qualite, co.onom, co.oprenom, co.fax AS cofax, 
				s.idshop, s.codeshop, s.societe AS ssociete, s.ville AS sville, 
				p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.gsm, p.codepeople 
			FROM merchduplic me
			LEFT JOIN agent a ON me.idagent = a.idagent 
			LEFT JOIN client c ON me.idclient = c.idclient 
			LEFT JOIN cofficer co ON me.idcofficer = co.idcofficer 
			LEFT JOIN people p ON me.idpeople = p.idpeople
			LEFT JOIN shop s ON me.idshop = s.idshop
			WHERE me.facturation = 1 AND '.$_SESSION['merchjobquid'].' ORDER BY me.datem');

$FoundCount = mysql_num_rows($listing->result); 

?>
<div id="centerzonelarge">
<fieldset>
	<legend>
		<b>listing des Merch</b>
	</legend>
	<b>Votre Recherche : ( <?php echo $FoundCount; ?> )</b><br>
</fieldset>
<br>
<br>
	<table class="sortable-onload-3 rowstyle-alt no-arrow" border="0" width="98%" cellspacing="1" align="center">
		<thead>
			<tr>
				<th colspan="2"></th>
				<th colspan="2">People</th>
				<th colspan="2">Client</th>
				<th colspan="3">Lieu</th>
				<th colspan="5">Prestations</th>
				<th colspan="3">Déplacements</th>
				<th colspan="3">Frais</th>
				<th></th>
			</tr>
			<tr>
				<th class="sortable-numeric">Sem</th>
				<th class="sortable-keep">Date</th>
				<th class="sortable-numeric">Id</th>
				<th class="sortable-text">Nom</th>
				<th class="sortable-numeric">Id</th>
				<th class="sortable-text">Societe</th>
				<th class="sortable-numeric">Id</th>
				<th class="sortable-text">Societe</th>
				<th class="sortable-text">Ville</th>
				<th>AM in</th>
				<th>AM out</th>
				<th>PM in</th>
				<th>Pm out</th>
				<th>tot</th>
				<th>Fac</th>
				<th>Pay</th>
				<th>&#8710;</th>
				<th>Fac</th>
				<th>Pay</th>
				<th>&#8710;</th>
				<th>Updt</th>
			</tr>
		</thead>
		<tbody>
<?php
		while ($row = mysql_fetch_array($listing->result)) { 

		$xpnom = '';
		$xclient = '';

		?>
					<tr ondblclick="location.href='<?php echo $_SERVER['PHP_SELF'].'?act=show&idmerch='.$row['idmerch'];?>'">
						<form action="<?php echo $_SERVER['PHP_SELF'].'?act=listingmodif&action=skip';?>" method="post">
							<input type="hidden" name="idmerch" value="<?php echo $row['idmerch'] ;?>"> 
						<td><?php echo $row['weekm'] ?></td>
						<td><input type="text" size="10" name="datem" value="<?php echo fdate($row['datem']); ?>"></td>
						<td><?php echo $row['idpeople'] ?></td>
						<td><?php echo $row['pnom'].' '.$row['pprenom'] ?></td>
						<td><?php echo $row['idclient']; ?></td>
						<td><?php echo $row['clsociete']; ?></td>
						<td><?php echo $row['idshop']; ?></td>
						<td><?php echo $row['ssociete']; ?></td>
						<td><?php echo $row['sville']; ?></td>
						<td><input type="text" size="5" name="hin1" value="<?php echo ftime($row['hin1']); ?>"></td>
						<td><input type="text" size="5" name="hout1" value="<?php echo ftime($row['hout1']); ?>"></td>
						<td><input type="text" size="5" name="hin2" value="<?php echo ftime($row['hin2']); ?>"></td>
						<td><input type="text" size="5" name="hout2" value="<?php echo ftime($row['hout2']); ?>"></td>
						<td>
						<?php 
							$merch = new coremerch($row['idmerch']);
							echo $merch->hprest;
							$timetotx += $merch->hprest;
							$timetotz += $merch->hprest;
						 ?>
						 </td>
						<td>
							<input type="text" size="6" name="kmfacture" value="<?php echo fnbr($row['kmfacture']); ?>">
							<?php $kmfacturex += fnbr($row['kmfacture']); ?>
							<?php $kmfacturez += fnbr($row['kmfacture']); ?>
						</td>
						<td>
							<input type="text" size="6" name="kmpaye" value="<?php echo fnbr($row['kmpaye']); ?>">
							<?php $kmpayex += fnbr($row['kmpaye']); ?>
							<?php $kmpayez += fnbr($row['kmpaye']); ?>
						</td>
						<td>
							<?php $kmtemp = fnbr($row['kmfacture']) - fnbr($row['kmpaye']); echo fnega($kmtemp); ?>
						</td>
						<td>
							<input type="text" size="6" name="fraisfacture" value="<?php echo fnbr($row['fraisfacture']); ?>">
							<?php $fraisfacturex += $row['fraisfacture']; ?>
							<?php $fraisfacturez += $row['fraisfacture']; ?>
						</td>
						<td>
							<input type="text" size="6" name="frais" value="<?php echo fnbr($row['frais']); ?>">
							<?php $fraisx += $row['frais']; ?>
							<?php $fraisz += $row['frais']; ?>
						</td>
						<td>
							<?php $fraistemp = $row['fraisfacture'] - $row['frais']; echo fnega($fraistemp); ?>
						</td>
						<td><input type="submit" name="Modifier" value="Modifier"></td>
						</form>
					</tr>
		<?php } ?>
		</tbody>
		<tfoot>		
		<tr>
			<th colspan="9"></th>
			<th colspan="4"></th>
			<th><?php echo $timetotx; ?></th>
			<th><?php echo $kmfacturex; ?></th>
			<th><?php echo $kmpayex; ?></th>
			<th><?php $kmfinal = $kmfacturex - $kmpayex ; echo fnega($kmfinal);?></th>
			<th><?php echo fnbr($fraisfacturex); ?></th>
			<th><?php echo fnbr($fraisx); ?></th>
			<th><?php $fraisfinal = $fraisfacturex - $fraisx ; echo fnega($fraisfinal);?></th>
			<th></th>
		</tr>
		</tfoot>
	</table>
<br>
</div>
