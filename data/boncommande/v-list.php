<?php
include(NIVO."print/commun/boncommande-detail.php");

if(empty($idjob))
{
	$idjob = $_REQUEST['idjob'];
}

$tab = $DB->getArray("SELECT 
				b.date, b.factureclient, b.id, b.idjob, b.idsupplier, b.montant, b.secteur, b.titre, 
				s.societe
			FROM bondecommande b
				LEFT JOIN supplier s ON b.idsupplier = s.idsupplier
			WHERE b.idjob = ".$idjob." AND b.etat LIKE 'in';");
				$temp = $DB->result;

$nbr = count($tab);
?>
<script type="text/javascript" charset="utf-8">
	parent.frames['up'].document.getElementById("nbrbdc").innerHTML = '('+<?php echo $nbr;?>+')';
</script>
<br>
<table class="sortable-onload-0 paginate-20 rowstyle-alt no-arrow" border="0" width="90%" cellspacing="1" align="center">
	<thead>
		<tr>
			<th class="sortable-text">ID</th>
			<th class="sortable-text">Supplier</th>
			<th class="sortable-text">Date</th>
			<th class="sortable-text">Montant</th>
			<th class="sortable-text">Re-facturé</th>
			<th class="sortable-text">Intitulé</th>
			<th class="sortable-text" width="16"></th>
			<th class="sortable-text" width="16"></th>
		</tr>
	</thead>
	<tbody>
<?php foreach($tab as $row) { ?>
	<tr ondblclick="location.href='<?php echo $_SERVER['PHP_SELF'].'?act=show&idjob='.$idjob.'&all='.$_REQUEST['all'].'&id='.$row['id'].'&liv='.$row['type'].'&secteur='.$_REQUEST['secteur'] ?>'">
		<td align="center"><?php echo $row['id']; ?></td>
		<td align="center"><?php echo $row['societe']; ?></td>
		<td align="center"><?php echo fdate($row['date']); ?></td>
		<td align="center"><?php echo fnbr($row['montant']); ?> €</td>
		<td align="center"><?php echo fnbr($row['factureclient']); ?> €</td>
		<td align="center"><?php echo $row['titre']; ?></td>
		<td align="center"><A href="<?php echo substr(print_bdc($row['id']), strlen(Conf::read('Env.root')) - 1 ); ?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"></A></td>
		<td style="padding: 0px;"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=del&id='.$row['id'].'&secteur='.$_REQUEST['secteur'].'&idjob='.$row['idjob'].'&retour=true' ?>"><img src="<?php echo STATIK ?>illus/delete.png" heigth="16" width="16" border="0"></td>
	</tr>
<?php } ?>
	</tbody>
</table>			
<div align="center">
	<a style="font-size:16px; text-decoration:none;" href="<?php echo $_SERVER['PHP_SELF'].'?act=add&liv=ext&secteur='.$_REQUEST['secteur'].'&idjob='.$idjob ?>"><img src="<?php echo STATIK ?>illus/add.png" align="middle" heigth="16" width="16" border="0"> Ajouter </a>
</div>