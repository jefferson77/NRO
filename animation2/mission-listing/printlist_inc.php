<?php
if ($down == 'down') {
	$colspa = 5;
} else {
	$colspa = 8;
}

###############################################################################################################################
### Entete et sort links #####################################################################################################
#############################################################################################################################
function sortlinks($down, $classe, $idanimjob) {
	$cURL = $_SERVER['PHP_SELF'].'?act=listing&down='.$down.'&action=skip&viewall='.$_GET['viewall'].'&idanimjob='.$idanimjob.'&';
?>
		<tr>
			<th class="<?php echo $classe; ?>">Ag</th>
		<?php if ($down != 'down') { ?>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=an.idanimjob'; ?>">J</a></th>
		<?php } ?>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=an.idanimation'; ?>">M</a></th>
			<th class="<?php echo $classe; ?>">C</th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=an.weekm'; ?>">S</a></th>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=an.datem'; ?>">Date</a></th>
			<th class="<?php echo $classe; ?>">code - <a href="<?php echo $cURL.'sort=p.pnom, p.pprenom'; ?>">Nom People</a></th>
		<?php if ($down != 'down') { ?>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=c.idclient'; ?>">Client</a></th>
		<?php } ?>
			<th class="<?php echo $classe; ?>"><a href="<?php echo $cURL.'sort=s.codeshop'; ?>">Lieux</a></th>
			<th class="<?php echo $classe; ?>"></th>
		<?php if ($down != 'down') { ?>
			<th class="<?php echo $classe; ?>">J</th>
		<?php } ?>
			<th class="<?php echo $classe; ?>">M</th>
		</tr>
<?php
}


###############################################################################################################################
### Tableau ##################################################################################################################
#############################################################################################################################

?>
<style type="text/css">
<!--
input[type="checkbox"]
{
	margin: 0px 2px 0px 0px;
}
-->
</style>
<form action="<?php echo NIVO ?>print/anim/printanim.php" method="post" target="popup" onsubmit="OpenBrWindow('_blank','popup','scrollbars=yes,status=yes,resizable=yes','500','400','true')" >
	<table class="<?php echo $classe; ?>" border="0" width="99%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="<?php echo $classe; ?>" colspan="<?php echo $colspa + 2; ?>" align="center">
				<input type="checkbox" name="ptype[]" value="planningint">Planning Int &nbsp;&nbsp;&nbsp;
				<input type="checkbox" name="ptype[]" value="planningxls">Planning XLS &nbsp;&nbsp;&nbsp;
				<input type="checkbox" name="ptype[]" value="planning">Planning Ext &nbsp;&nbsp;&nbsp;
				<input type="checkbox" name="ptype[]" value="telecheck">T&eacute;l&eacute;Check &nbsp;&nbsp;&nbsp;
				<input type="checkbox" name="ptype[]" value="contrat">Contrat &nbsp;&nbsp;&nbsp;
				<input type="checkbox" name="ptype[]" value="rapportp">Rapport people &nbsp;&nbsp;&nbsp;
				<input type="checkbox" name="ptype[]" value="rapportc">Rapport client &nbsp;&nbsp;&nbsp;
				<input type="checkbox" name="ptype[]" value="ccr">C + R &nbsp;&nbsp;&nbsp;
				<input type="checkbox" name="ptype[]" value="etiqpeop"> <input type="text" name="netiq" size="2">Etiquettes
			</th>
			<th class="<?php echo $classe; ?>"><input type="submit" class="btn printer"></th>
			<th class="<?php echo $classe; ?>"><input type="submit" class="btn phone" name="send" value="sms"></th>
		</tr>
<?php
## Sortlinks du haut
sortlinks($down, $classe, $idanimjob) ;

###############################################################################################################################
### Lignes ###################################################################################################################
#############################################################################################################################
	while ($row = mysql_fetch_array($listing->result)) {
		$i++;
		echo '<tr bgcolor="',(fmod($i, 2) == 1)?'#9CBECA':'#8CAAB5','">';
	?>
			<td class="<?php echo $classe; ?>"><?php echo $row['prenom'] ?></td>
	<?php if ($down != 'down') { ?>
			<td class="<?php echo $classe; ?>"><?php echo $row['idanimjob'] ?></td>
	<?php } ?>
			<td class="<?php echo $classe; ?>"><?php echo $row['idanimation'] ?> <?php echo $row['produit'] ?></td>
			<td class="<?php echo $classe; ?>" width="13"><?php if ($row['tchkdate'] != '0000-00-00') { echo '<img src="'.STATIK.'illus/stock_edit-16.png" alt="" width="16" height="16" title="'.fdate($row['tchkdate']).'">';} ?></td>
			<td class="<?php echo $classe; ?>"><?php echo $row['weekm'] ?></td>
			<td class="<?php echo $classe; ?>"><?php echo fdate($row['datem']) ?></td>
			<td class="<?php echo $classe; ?>">
					<img src="<?php echo STATIK ?>illus/<?php echo $row['lbureau']; ?>.gif" alt="<?php echo $row['lbureau']; ?>" width="12" height="9"> <?php echo $row['codepeople'].' - '.$row['pnom'].' '.$row['pprenom'] ?> <a href="<?php echo NIVO.'data/people/adminpeople.php?act=show&idpeople='.$row['idpeople'];?>" target="_blank"><img src="<?php echo STATIK ?>illus/icon_profile.gif" border="0" width="15" height="12"></a>
				<!--
				<?php if ($_SESSION['roger'] != 'devel') { ?>
					<img src="<?php echo STATIK ?>illus/<?php echo $row['lbureau']; ?>.gif" alt="<?php echo $row['lbureau']; ?>" width="12" height="9"> <?php echo $row['codepeople'].' - '.$row['pnom'].' '.$row['pprenom'] ?> <a href="<?php echo NIVO.'data/people/adminpeople.php?act=show&idpeople='.$row['idpeople'];?>" target="_blank"><img src="<?php echo STATIK ?>illus/icon_proofile.gif" border="0" width="15" height="12"></a>
				<?php } else { ?>
					<?php
						$selection = 'selection';
						if ((!empty($row['pprenom'])) OR (!empty($row['pnom']))) {$selection = 'ok';}

					if ($selection == 'selection') { ?>
						<a href="<?php echo $_SERVER['PHP_SELF'].'?act=selectpeople&idanimation='.$row['idanimation'].'&idanimjob='.$idanimjob.'&sexe='.$row['sexe'].'&sel=people';?>"><?php echo $selection; ?></a>
					<?php } else { ?>
						<img src="<?php echo STATIK ?>illus/<?php echo $row['lbureau']; ?>.gif" alt="<?php echo $row['lbureau']; ?>" width="12" height="9">
						<a href="<?php echo $_SERVER['PHP_SELF'].'?act=removepeople&idanimation='.$row['idanimation'].'&idanimjob='.$row['idanimjob'].'&sexe='.$row['sexe'].'&sel=people';?>">
							<?php echo $row['codepeople'].' - '.$row['pnom'].' '.$row['pprenom'] ?>
						</a>
						-
						<a href="<?php echo NIVO.'data/people/adminpeople.php?act=show&idpeople='.$row['idpeople'];?>" target="_blank">
							<img src="<?php echo STATIK ?>illus/icon_profile.gif" border="0" width="15" height="12">
						</a>
					<?php } ?>
				<?php } ?>
				-->
			</td>
			<?php if ($down != 'down') { /* ### PAS pour IFRAME */?>
				<td class="<?php echo $classe; ?>"><?php echo $row['codeclient']; ?> - <?php echo $row['clsociete']; ?></td>
			<?php } /* ### que pour PAS  */ ?>
			<td class="<?php echo $classe; ?>"><?php echo $row['codeshop']; ?> - <?php echo $row['ssociete']; ?> - <?php echo $row['sville']; ?></td>
			<th class="<?php echo $classe; ?>" width="15"><input type="checkbox" name="print[]" checked value="<?php echo $row['idanimation'] ?>"></th>
			<?php if ($down != 'down') { /* ### PAS pour IFRAME */ ?>
				<td class="<?php echo $classe; ?>" width="13"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=showjob&idanimation='.$row['idanimation'].'&idanimjob='.$row['idanimjob'];?>" target="_top"><img src="<?php echo STATIK ?>illus/stock_right-16.png" alt="search" width="13" height="15" border="0"></a></td>
			<?php } /* ### que pour PAS */ ?>
			<td class="<?php echo $classe; ?>" width="13"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=showmission&idanimation='.$row['idanimation'].'&idanimjob='.$row['idanimjob'];?>" target="_top"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
		</tr>
	<?php }
## sortlink du bas
sortlinks($down, $classe, $idanimjob) ;?>
	</table>
</form>
