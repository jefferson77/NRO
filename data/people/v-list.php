<?php
## init
$emails = '';

## Get people List
$peopleList = $DB->getArray("SELECT
		p.idpeople, p.idsupplier, p.pnom, p.pprenom, p.sexe, p.lfr, p.lnl, p.len, p.codepeople, p.glat1, p.glat2,
			p.cp1, p.ville1, p.cp2, p.ville2, p.gsm, p.isout, p.err, p.categorie, p.notemerch,
			p.permis, p.voiture, p.physio, p.province, p.taille, p.ccheveux, p.lcheveux, p.tveste, p.tjupe, p.ndate, p.email  ,
		sup.societe as supsociete
	FROM people p
		LEFT JOIN vipmission m  ON p.idpeople = m.idpeople
		LEFT JOIN supplier sup ON p.idsupplier = sup.idsupplier
	WHERE ".$_SESSION['searchpeoplequid']."
	GROUP BY p.idpeople
	ORDER BY p.idpeople DESC
	LIMIT 300");

## < ## Castings ################################################################################################
 if (($_SESSION['casting'] != 'z') and (!empty($_SESSION['casting']))) {

	## get les ids des peoples dans le casting
 	$casting = $DB->getOne("SELECT casting FROM `vipjob` WHERE `idvipjob` = ".$_SESSION['casting']);

	## si ajout de peoples au casting : ajout puis retour sur le job
	if ($_REQUEST['castingadd'] == 'add') {
		if (!empty($casting)) { $casteds = explode('-', $casting); } else { $casteds = array(); }
		if (count($_POST['castingx']) > 0) { $newpeops = $_POST['castingx']; } else { $newpeops = array();}

		$casting = array_unique(array_merge($casteds, $newpeops));
		sort($casting, SORT_NUMERIC);

		$DB->inline("UPDATE vipjob SET casting = '".implode('-', $casting)."' WHERE `idvipjob` = ".$_SESSION['casting']);

		echo MetaRedirect(NIVO.'vip/adminvip.php?act=show&idvipjob='.$_SESSION['casting'].'&etat=0');
 	}
 }
## > ## Castings ################################################################################################
?>
<div id="centerzonelarge">
	<form action="<?php echo NIVO."mod/sms/adminsms.php?act=show";?>" target="centerzonelarge" method="post">
		<input type="hidden" name="query" value="<?php echo $_SESSION['searchpeoplequid'];?>">
		SEND:
		<input type="submit" class="btn phone" name="send" value="sms" title="Envoyer un SMS group&eacute;">
		<img src="<?php echo STATIK ?>illus/mail.png" width="16" height="16" alt="Mail" border="0" title="Adresses Mail" align="top" id="showmails">
	</form>
		<?php if (($_SESSION['casting'] != 'z') and (!empty($_SESSION['casting']))) { ?>
			<form action="<?php echo $_SERVER['PHP_SELF'] .'?act=list&etat=1&action=skip&skip='.$_SESSION['searchpeopleskip'].'&castingadd=add'; ?>" method="post">
		<?php } ?>
			<table class="sortable-onload-2r paginate-25 rowstyle-alt no-arrow" border="0" width="99%" cellspacing="1" align="center">
				<thead>
				<tr>
					<th class="sortable-sortImage" title="Fournisseur">F</th>
					<th class="sortable-sortImage" title="Erreurs">E</th>
					<th></th>
					<th class="sortable-numeric">ID</th>
					<th class="sortable-numeric">Code</th>
					<th class="sortable-text">Nom</th>
					<th class="sortable-text">Pr&eacute;nom</th>
					<th class="sortable-text">Sex</th>
					<th class="sortable-numeric">Fr</th>
					<th class="sortable-numeric">NL</th>
					<th class="sortable-numeric">En</th>
					<th class="sortable-numeric">CP</th>
					<th class="sortable-text">Ville</th>
					<th class="sortable-numeric">CP2</th>
					<th class="sortable-text">Ville2</th>
					<th class="sortable-text">GSM</th>
					<th class="sortable-text">mail</th>
					<?php if (($_SESSION['casting'] != 'z') and (!empty($_SESSION['casting']))) { ?>
					<th><input type="submit" class="btn add_casting"></th>
					<?php } ?>
				</tr>
				</thead>
				<tbody>
<?php foreach ($peopleList as $row) {
	if (!empty($row['email'])) $emails[] = $row['email'];
	if($row['idsupplier'] > 0) $backgroundColor = 'style="background-color: #D0CAE7;color:#65657C;"';
	elseif($row['err'] == 'Y') $backgroundColor = 'style="background-color: #d9c77a;"';
	else $backgroundColor = '';
?>
				<tr ondblclick="location.href='<?php echo $_SERVER['PHP_SELF'].'?act=show&idpeople='.$row['idpeople'];?>'" <?php echo $backgroundColor; ?>>
					<td style="padding: 0px; width: 10px;">
						<?php if ($row['idsupplier'] > 0) { echo '<img src="'.STATIK.'illus/group_link.png" alt="group_link" width="16" height="16" title="'.$row['supsociete'].'">'; } ?>
					</td>
					<td style="padding: 0px; width: 10px;">
						<?php if ($row['err'] == 'Y') { echo '<img src="'.STATIK.'illus/attention.gif" alt="attention.gif" width="14" height="14">'; } ?>
					</td>
					<td style="padding: 0px; width: 10px;">
						<?php if (($row['glat1'] > 0) or ($row['glat2'] > 0)) { echo '<img src="'.STATIK.'illus/geoloc.png" alt="geoloc.png" width="16" height="15">'; } ?>
					</td>
					<td style="text-align: center;"><?php echo fnbr0($row['idpeople']); ?></td>
					<td style="text-align: center;"><?php echo fnbr0($row['codepeople']); ?></td>
					<td><b><?php echo showmax($row['pnom'], 15); ?></b></td>
					<td><b><?php echo $row['pprenom']; ?></b></td>
					<td align="center"><?php echo $row['sexe']; ?></td>
					<td><?php echo $row['lfr']; ?></td>
					<td><?php echo $row['lnl']; ?></td>
					<td><?php echo $row['len']; ?></td>
					<td><?php echo $row['cp1']; ?></td>
					<td><?php echo showmax($row['ville1'], 12); ?></td>
					<td><?php echo $row['cp2']; ?></td>
					<td><?php echo showmax($row['ville2'], 12); ?></td>
					<td><?php echo $row['gsm']; ?></td>
					<td><a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a></td>
					<?php if (($_SESSION['casting'] != 'z') and (!empty($_SESSION['casting']))) { ?>
						<td>
							<?php if (strchr($casting, $row['idpeople'])) { ?>
								x
							<?php } else { ?>
								<input type="checkbox" name="castingx[]" value="<?php echo $row['idpeople'] ?>">
							<?php } ?>
						</td>
					<?php } ?>
				</tr>
		<?php } ?>
				</tbody>
			</table>
		<?php if (($_SESSION['casting'] != 'z') and (!empty($_SESSION['casting']))) { ?>
			</form>
		<?php } ?>
<br>
<?php echo count($peopleList); ?>	Results
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
