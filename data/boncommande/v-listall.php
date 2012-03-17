<div id="leftmenu" style="overflow:auto;">
	<table class="paymenu" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
<?php
//création du menu de gauche
include(NIVO."print/commun/boncommande-detail.php");
include(NIVO."print/commun/boncommande-liste.php");

$row = $DB->getColumn("SELECT CONCAT(MONTH(date), '-',YEAR(date)) as period FROM bondecommande WHERE etat LIKE 'in' GROUP BY CONCAT(MONTH(date), '-',YEAR(date));");
$temp = $DB->getRow("SELECT max(year(b.date)) as max, min(year(b.date)) as min from bondecommande b WHERE b.etat LIKE 'in';");
$min = $temp['min'];
$max = $temp['max']; 
$min=2008;
while($min != $max+1)
{
		echo '<tr><th colspan="3">'.$min.'</th></tr>
		<tr>
			<td>'; if (in_array('1-'.$min, $row)) { echo '<a href="'.$_SERVER['PHP_SELF'].'?period=1-'.$min.'&act=listall&all=1">Jan</a>'; } else { echo'Jun';} echo '</td>
			<td>'; if (in_array('2-'.$min, $row)) { echo '<a href="'.$_SERVER['PHP_SELF'].'?period=2-'.$min.'&act=listall&all=1">F&eacute;v</a>'; } else { echo'F&eacute;v';} echo '</td>
			<td>'; if (in_array('3-'.$min, $row)) { echo '<a href="'.$_SERVER['PHP_SELF'].'?period=3-'.$min.'&act=listall&all=1">Mar</a>'; } else { echo'Mar';} echo '</td>
		</tr>
		<tr>
			<td>'; if (in_array('4-'.$min, $row)) { echo '<a href="'.$_SERVER['PHP_SELF'].'?period=4-'.$min.'&act=listall&all=1">Avr</a>'; } else { echo'Avr';} echo '</td>
			<td>'; if (in_array('5-'.$min, $row)) { echo '<a href="'.$_SERVER['PHP_SELF'].'?period=5-'.$min.'&act=listall&all=1">Mai</a>'; } else { echo'Mai';} echo '</td>
			<td>'; if (in_array('6-'.$min, $row)) { echo '<a href="'.$_SERVER['PHP_SELF'].'?period=6-'.$min.'&act=listall&all=1">Jun</a>'; } else { echo'Jun';} echo '</td>
		</tr>
		<tr>
			<td>'; if (in_array('7-'.$min, $row)) { echo '<a href="'.$_SERVER['PHP_SELF'].'?period=7-'.$min.'&act=listall&all=1">Jun</a>'; } else { echo'Jui';} echo '</td>
			<td>'; if (in_array('8-'.$min, $row)) { echo '<a href="'.$_SERVER['PHP_SELF'].'?period=8-'.$min.'&act=listall&all=1">Aou</a>'; } else { echo'Aou';} echo '</td>
			<td>'; if (in_array('9-'.$min, $row)) { echo '<a href="'.$_SERVER['PHP_SELF'].'?period=9-'.$min.'&act=listall&all=1">Sep</a>'; } else { echo'Sep';} echo '</td>
		</tr>
		<tr>
			<td>'; if (in_array('10-'.$min, $row)) { echo '<a href="'.$_SERVER['PHP_SELF'].'?period=10-'.$min.'&act=listall&all=1">Oct</a>'; } else { echo'Oct';} echo '</td>
			<td>'; if (in_array('11-'.$min, $row)) { echo '<a href="'.$_SERVER['PHP_SELF'].'?period=11-'.$min.'&act=listall&all=1">Nov</a>'; } else { echo'Nov';} echo '</td>
			<td>'; if (in_array('12-'.$min, $row)) { echo '<a href="'.$_SERVER['PHP_SELF'].'?period=12-'.$min.'&act=listall&all=1">D&eacute;c</a>'; } else { echo'D&eacute;c';} echo '</td>
		</tr>';

	
$min++;
} ?>
	</table>
</div>
<?php

if(empty($_REQUEST['period'])) $_REQUEST['period'] = date("n-Y");

$DB->inline("SELECT 
				b.date, b.factureclient, b.id, b.idjob, b.idsupplier, b.montant, b.secteur, b.titre, b.nfac, 
				s.societe, a.prenom
			FROM bondecommande b
				LEFT JOIN supplier s ON b.idsupplier = s.idsupplier
				LEFT JOIN agent a ON b.idagent = a.idagent
			WHERE b.etat LIKE 'in' AND b.type='int' AND CONCAT(MONTH(b.date), '-',YEAR(b.date)) LIKE '".$_REQUEST['period']."';");
			$res = $DB->result;
//affichage des bons de commande Exception pour Exception
?>
<div id="infozone">
	<div align="center">
		<a href="<?php echo makebdcsup($_REQUEST['period']); ?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"> Imprimer le compte-rendu de ce mois</a><br>
		Bons de commande Exception : <br><br>
	</div>
<table class="sortable-onload-0 paginate-20 rowstyle-alt no-arrow" border="0" width="90%" cellspacing="1" align="center">
	<thead>
		<tr>
			<th class="sortable-numeric">ID</th>
			<th class="sortable-text">Agent</th>
			<th class="sortable-text">Date</th>
			<th class="sortable-text">Supplier</th>
			<th class="sortable-text">Montant</th>
			<th class="sortable-text">Intitulé</th>
			<th class="sortable-text">Facture associée</th>
			<th class="sortable-text" width="16"></th>
			<th class="sortable-text" width="16"></th>
		</tr>
	</thead>
	<tbody>
<?php while($row = mysql_fetch_array($res)) { ?>
	<tr target = "infozone" ondblclick="location.href='<?php echo $_SERVER['PHP_SELF'].'?act=show&all='.$_REQUEST['all'].'&id='.$row['id'].'&liv='.$row['type'].'&period='.$_REQUEST['period'] ?>'">
		<td align="center"><?php echo $row['id']; ?></td>
		<td align="center"><?php echo $row['prenom']; ?></td>
		<td align="center"><?php echo fdate($row['date']); ?></td>
		<td align="center"><?php echo $row['societe']; ?></td>
		<td align="center"><?php echo fnbr($row['montant']); ?> €</td>
		<td><?php echo $row['titre']; ?></td>
		<td align="center"><?php echo $row['nfac']; ?></td>
		<td align="center"><a href="<?php echo substr(print_bdc($row['id']), 4) ; ?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"></a></td>
		<td style="padding: 0px;"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=del&id='.$row['id'].'&secteur='.$_REQUEST['secteur'].'&idjob='.$row['idjob'].'&retour=true&all=1' ?>"><img src="<?php echo STATIK ?>illus/delete.png" heigth="16" width="16" border="0"></td>
	</tr>
<?php } ?>
	</tbody>
</table>
<?php 
// affichage des bons de commande Exception vers l'extérieur
$bonsdecommandes = $DB->getArray("SELECT 
				b.date, b.factureclient, b.id, b.idjob, b.idsupplier, b.montant, b.secteur, b.titre, b.nfac, b.nfacout,
				s.societe, a.prenom
			FROM bondecommande b
				LEFT JOIN supplier s ON b.idsupplier = s.idsupplier
				LEFT JOIN agent a ON b.idagent = a.idagent
			WHERE b.etat LIKE 'in' AND b.type ='ext' AND CONCAT(MONTH(b.date), '-',YEAR(b.date)) LIKE '".$_REQUEST['period']."'");

?>
<br><br>
<div align="center"> Bons de commande refacturés : </div>
<br><br>
<table class="sortable-onload-0 paginate-20 rowstyle-alt no-arrow" border="0" width="90%" cellspacing="1" align="center">
	<thead>
		<tr>
			<th class="sortable-numeric">ID</th>
			<th class="sortable-text">Agent</th>
			<th class="sortable-date">Date</th>
			<th class="sortable-text">Secteur - ID Job</td>
			<th class="sortable-text">Supplier</th>
			<th class="sortable-numeric">Montant</th>
			<th class="sortable-numeric">Re-facturé</th>
			<th class="sortable-text">Intitulé</th>
			<th class="sortable-text">Facture Supplier</th>
			<th class="sortable-text">Facture Client</th>
			<th class="sortable-text" width="16"></th>
			<th class="sortable-text" width="16"></th>
		</tr>
	</thead>
	<tbody>
<?php foreach($bonsdecommandes as $row) { ?>
	<tr ondblclick="location.href='<?php echo $_SERVER['PHP_SELF'].'?act=show&all='.$_REQUEST['all'].'&id='.$row['id'].'&liv='.$row['type'].'&period='.$_REQUEST['period'].'&from='.$_REQUEST['menu'] ?>'">
		<td align="center"><?php echo $row['id']; ?></td>
		<td align="center"><?php echo $row['prenom']; ?></td>
		<td align="center"><?php echo fdate($row['date']); ?></td>
		<td align="center"><?php echo $row['secteur'].' '.$row['idjob'];?></td>
		<td align="center"><?php echo $row['societe']; ?></td>
		<td align="center"><?php echo fnbr($row['montant']); ?> €</td>
		<td align="center"><?php echo fnbr($row['factureclient']); ?> €</td>
		<td><?php echo $row['titre']; ?></td>
		<td align="center"><?php echo $row['nfac']; ?></td>
		<td align="center"><?php echo $row['nfacout']; ?></td>
		<td><a href="<?php echo substr(print_bdc($row['id']), 4) ; ?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"></A></td>
		<td style="padding: 0px;"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=del&id='.$row['id'].'&secteur='.$_REQUEST['secteur'].'&idjob='.$row['idjob'].'&retour=true&all=1' ?>"><img src="<?php echo STATIK ?>illus/delete.png" heigth="16" width="16" border="0"></td>
	</tr>
<?php } ?>
	</tbody>
</table>
</div>