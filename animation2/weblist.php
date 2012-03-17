<?php
switch ($_GET['action']) {
	case "skip": 

		# VARIABLE SELECT
		if (!empty($_GET['sort'])) {
			$_SESSION['jobsort'] = $_GET['sort'];
		}
		$jobsort = $_SESSION['jobsort'];
		$jobquid = $_SESSION['jobquid'];
		$jobsearch = $_SESSION['jobsearch'];
		
		$recherche='
			'.$jobsearch.'
			'.$jobquid.'
			 ORDER BY '.$jobsort.'
		';
	break;

	default: 
		if ($sort == '') { $sort = 'j.datein'; }

		$recherche="SELECT 
				j.idwebanimjob, j.reference, j.etat, j.datein, j.dateout, 
				a.prenom, 
				c.idclient, c.societe AS clsociete 
				FROM webneuro.webanimjob j
					LEFT JOIN agent a ON j.idagent = a.idagent
					LEFT JOIN client c ON j.idclient = c.idclient
				WHERE j.etat >= 5 AND j.isnew = 0 
			 	ORDER BY ".$sort;
		
		$_SESSION['jobquid'] = $quid;
		$_SESSION['jobquod'] = $quod;
}

$listing = new db();
$listing->inline($recherche);
$FoundCount = mysql_num_rows($listing->result); 
?>
<div id="centerzonelargewhite">
	<fieldset class="gray">
		<legend class="gray">
			<b>listing des ANIMs</b>
		</legend>
		<b>Votre Recherche : <?php echo $_SESSION['jobquod']; ?> ( <?php echo $FoundCount; ?> )</b><br>
		<br>
		<table class="planning" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
			<tr>
				<th class="planning"><a href="?act=webanimlist&amp;action=skip&amp;sort=j.idwebanimjob">Job</a></th>
				<th class="planning" colspan="2"><a href="?act=webanimlist&amp;action=skip&amp;sort=c.idclient">Client</a></th>
				<th class="planning">R&eacute;f&eacute;rence</th>
				<th class="planning"><a href="?act=webanimlist&amp;action=skip&amp;sort=j.datein">Date d&eacute;but</a></th>
				<th class="planning">Date fin</th>
				<th class="planning">Statut</th>
				<th class="planning"></th>
			</tr>
			<?php
			while ($row = mysql_fetch_array($listing->result)) {
				$i++;
				echo '<tr bgcolor="'.((fmod($i, 2) == 1)?'#9CBECA">':'#8CAAB5').'">';
			?>
					<td class="planning"><?php echo $row['idwebanimjob'] ?></td>
					<td class="planning"><?php echo $row['idclient']; ?></td>
					<td class="planning"><?php echo $row['clsociete']; ?></td>
					<td class="planning"><?php echo $row['reference'] ?></td>
					<td class="planning"><?php echo fdate($row['datein']); ?></td>
					<td class="planning"><?php echo fdate($row['dateout']); ?></td>
					<td class="planning">
						<?php
						switch ($row['etat']) {
							case "5": 
								echo '<font color="blue"> NEW';
							break;
							case "6": 
								echo '<font color="green"> S By';
							break;
						} 
						?>
							</font>
					</td>
					<td class="planning"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=webshow&idwebanimjob='.$row['idwebanimjob'].'&etat='.$row['etat'];?>"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
				</tr>
			<?php } ?>
		</table>
	</fieldset>
</div>