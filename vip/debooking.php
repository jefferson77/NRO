<?php
define('NIVO', '../');

# Entete de page
include NIVO."includes/ifentete.php" ;

if ($_GET['act'] == "notemodif") {
	$modif = new db('peoplemission', 'idpeoplemission');
	$modif->MODIFIE($_POST['idpeoplemission'], array('note'));
}
	$classe = 'standard' ;
?>
<div id="miniinfozone">
	<table class="<?php echo $classe; ?>" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">

<?php
	### VIP
		$quidvip='WHERE m.idvipjob = '.$_GET['idvipjob'].' AND pm.idvip > 0';
		if (!empty($_GET['sort'])) { $sort = $_GET['sort'].', m.vipdate DESC, m.vipactivite DESC'; }
		if (empty($sort)) { $sort='st.suser DESC, st.inuse DESC, m.vipdate DESC, m.vipactivite DESC'; }
		$recherchevip='
			SELECT 
			pm.dateout, pm.note, pm.motif, pm.idpeoplemission, pm.idvip, 
			m.vipdate, m.vipactivite, m.matospeople, m.sexe, p.pprenom, p.pnom, m.vipin, m.vipout, 
			j.idvipjob, j.reference, j.etat, 
			p.idpeople,
			st.suser, st.inuse 
			FROM peoplemission pm
			LEFT JOIN vipmission m ON pm.idvip = m.idvip
			LEFT JOIN vipjob j ON m.idvipjob = j.idvipjob
			LEFT JOIN people p ON pm.idpeople = p.idpeople
			LEFT JOIN stockticket st ON p.idpeople = st.idpeople
			'.$quidvip.'
			 ORDER BY '.$sort.'
		';
		
		# Recherche des résultats VIP
		$listingvip = new db();
		$listingvip->inline("$recherchevip;");
		$FoundCountvip = mysql_num_rows($listingvip->result); 

	if ($FoundCountvip > 0) {
	?>
				<tr><th class="<?php echo $classe; ?>">VIP</th></tr>
				<tr>
					<th class="<?php echo $classe; ?>" colspan="2"><a href="<?php echo $_SERVER['PHP_SELF'].'?idvipjob='.$_GET['idvipjob'].'&sort=p.pnom, p.pprenom, p.idpeople';?>">People</a></th>
					<th class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?idvipjob='.$_GET['idvipjob'];?>">Date</a></th>
					<th class="<?php echo $classe; ?>">In</th>
					<th class="<?php echo $classe; ?>">Out</th>
					<th class="<?php echo $classe; ?>">debook&eacute; le</th>
					<th class="<?php echo $classe; ?>">Motif</th>
					<th class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?idvipjob='.$_GET['idvipjob'].'&sort=m.vipactivite';?>">Activit&eacute;</a></th>
					<th class="<?php echo $classe; ?>"></th>
					<th class="<?php echo $classe; ?>">Note</th>
				</tr>
		<?php
		while ($row = mysql_fetch_array($listingvip->result)) {
		?>
				<tr>
					<td class="<?php echo $classe; ?>"><?php echo $row['idpeople']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['pnom'].' '.$row['pprenom']; ?></td>

					<td class="<?php echo $classe; ?>" align="center"><?php echo fdate($row['vipdate']); ?></color></td>
					<td class="<?php echo $classe; ?>" align="center"><?php echo ftime($row['vipin']) ?></td>
					<td class="<?php echo $classe; ?>" align="center"><?php echo ftime($row['vipout']) ?></td>
					<td class="<?php echo $classe; ?>" align="center"><?php echo fdate($row['dateout']) ?></td>
					<td class="<?php echo $classe; ?>">
						<?php 
						if ($row['motif'] == '1') {$motif = 'D&eacute;sistement';}
						if ($row['motif'] == '2') {$motif = 'Absent';}
						if ($row['motif'] == '3') {$motif = 'Re-booking';}
						if ($row['motif'] == '4') {$motif = 'Annulation Exception';}
						if ($row['motif'] == '5') {$motif = 'De-booking Exception';}
						if ($row['motif'] == '6') {$motif = 'Autre';}
						echo $motif; 
						?>
					</td>
					<td class="<?php echo $classe; ?>" align="center"><?php echo $row['vipactivite'] ?></td>
					<td class="<?php echo $classe; ?>" align="center">
						<?php
						if (($row['suser'] == 'people') and ($row['inuse'] == '1')) { 
						echo '<a href="vipmatosstock-debooking.php?idvipjob='.$_GET['idvipjob'].'&idpeople='.$row['idpeople'].'" target="_blank"><img src="'.STATIK.'illus/taille-t-shirt-m-rouge.gif" alt="Supplier" width="16" height="14" border="0"></a>'; 
						}
						?>
					</td>
					<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=notemodif&idvipjob=<?php echo $_GET['idvipjob']; ?>" method="post" name="CelsysForm" onsubmit="return scanForm(this)">
					<input type="hidden" name="idvipjob" value="<?php echo $_GET['idvipjob'];?>"> 
					<input type="hidden" name="idpeoplemission" value="<?php echo $row['idpeoplemission'];?>"> 
						<td class="<?php echo $classe; ?>" align="center"><input type="text" size="60" name="note" value="<?php echo $row['note']; ?>"></td>
						<td class="<?php echo $classe; ?>"><input class="mini" type="submit" name="action" value="M"></td>
					</form>
				</tr>
		<?php 
		} ?>
	<?php
	}
	#/ ## VIP
	?>
	</table>
<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
</div>