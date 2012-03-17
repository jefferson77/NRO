<?php
# Recherche des résultats
$rows = $DB->getArray('SELECT 
		an.idanimation, an.datem, an.frais, an.fraisfacture, an.hin1, an.hin2, an.hout1, an.hout2, an.isbriefing, 
			an.kmfacture, an.kmpaye, an.kmauto, an.livraisonfacture, an.livraisonpaye, an.produit, an.weekm,
		s.codeshop, s.societe as ssociete, s.ville as sville
	FROM animation an
		LEFT JOIN shop s ON an.idshop = s.idshop
	WHERE an.idanimjob = '.$_REQUEST['idanimjob'].' AND (an.facturation < 5 OR an.facturation IS NULL) 
	ORDER BY an.datem');
?>
<br>
<form action="?act=updatelist" method="post">
	<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'] ?>">
	<table class="sortable-onload-0 rowstyle-alt no-arrow" border="0" width="99%" cellspacing="1" align="center" id="listmissions">
		<thead>
			<tr>
				<td class="etiq2" colspan="18" align="center"><b>Update Missions - S&eacute;l&eacute;ction des missions</b></td>
				<td class="etiq2" colspan="4" align="center"><input type="submit" name="Modifier" value="Continuer"></td>
			</tr>
			<tr>
				<th class="sortable-numeric">M</th>
				<th class="sortable-numeric">S</th>
				<th class="sortable-date-dmy">Date</th>
				<th class="sortable-text">Lieux</th>	
				<th class="sortable-text">Prod</th>
				<th>Am In</th>
				<th>Am Out</th>
				<th>Pm In</th>
				<th>Pm Out</th>
				<th class="sortable-numeric">tot</th>
				<th class="sortable-numeric">AUTO</th>
				<th class="sortable-numeric">KM P</th>
				<th class="sortable-numeric">KM F</th>
				<th class="sortable-numeric">F - P</th>
				<th class="sortable-numeric">Fra P</th>
				<th class="sortable-numeric">Fra F</th>
				<th class="sortable-numeric">F - P</th>
				<th class="sortable-numeric">Liv P</th>
				<th class="sortable-numeric">Liv F</th>
				<th class="sortable-numeric">F - P</th>
				<th>Brief</th>
				<th><input type="checkbox" onclick="ounCheck(this.checked ?'1':'0')" checked title="Check/UnCheck ALL"></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($rows as $row) { ?>
			<tr>
				<td><?php echo $row['idanimation'] ?> <?php echo $row['produit'] ?></td>
				<td><?php echo $row['weekm'] ?></td>
				<td><?php echo fdate($row['datem']); ?></td>
				<td><?php echo $row['codeshop']; ?> - <?php echo $row['ssociete']; ?> - <?php echo $row['sville']; ?></td>
				<td><?php echo $row['produit'] ?></td>
				<td><?php echo ftime($row['hin1']); ?></td>
				<td><?php echo ftime($row['hout1']); ?></td>
				<td><?php echo ftime($row['hin2']); ?></td>
				<td><?php echo ftime($row['hout2']); ?></td>
				<td>
				<?php
					$anim = new coreanim($row['idanimation']);
					$timetot =  $anim->hprest;
					echo $timetot;
				 ?>
				 </td>
				<td><?php echo $row['kmauto']; ?></td>
				<td><?php echo fnbr($row['kmpaye']); ?></td>
				<td><?php echo fnbr($row['kmfacture']); ?></td>
				<td><?php echo fnega($row['kmfacture'] - $row['kmpaye']); ?></td>
				<td><?php echo fnbr($row['frais']); ?></td>
				<td><?php echo fnbr($row['fraisfacture']); ?></td>
				<td><?php echo fnega($row['fraisfacture'] - $row['frais']); ?></td>
				<td><?php echo fnbr($row['livraisonpaye']); ?></td>
				<td><?php echo fnbr($row['livraisonfacture']); ?></td>
				<td><?php echo fnega($row['livraisonfacture'] - $row['livraisonpaye']); ?></td>
				<td><?php echo($row['isbriefing'] == 1)?'oui':''; ?></td>
				<td width="15"><input type="checkbox" name="idtoupdate[]" <?php if ($_GET['selectall'] != 'no') { echo 'checked'; } ?> value="<?php echo $row['idanimation'] ?>"></td>
			</tr>
		<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<td class="etiq2" colspan="18" align="center"><b>Update Missions - S&eacute;l&eacute;ction des missions</b></td>
				<td class="etiq2" colspan="4" align="center"><input type="submit" name="Modifier" value="Continuer"></td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript" charset="utf-8">
	function ounCheck(w) {
		$('input:checkbox').each(function() {
        	this.checked = (w == 0) ? false : true;
		});
	}
</script>