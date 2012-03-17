<div id="centerzonelarge">
<?php
$classe = "planning" ;

# Recherche des résultats
$listing = new db();
$listing->inline("
	SELECT
		me.idmerch, me.idclient, me.idpeople, me.idshop, 
			me.weekm, me.genre, me.datem, me.kmpaye, me.produit, 
		p.lbureau, p.codepeople, p.pnom, p.pprenom, 
		s.societe AS ssociete, s.ville AS sville, 
		c.societe AS clsociete,
		nf.montantpaye
		
	FROM merch me
		LEFT JOIN people p ON me.idpeople = p.idpeople
		LEFT JOIN shop s ON me.idshop = s.idshop
		LEFT JOIN client c ON me.idclient = c.idclient 
		LEFT JOIN notefrais nf ON nf.secteur = 'ME' AND nf.idmission = me.idmerch

	WHERE
		me.idpeople = ".$_GET['idpeople']." AND
		me.weekm = '".$_GET['weekm']."' AND
		me.yearm = ".substr($_GET['datem'], 0, 4)." AND
		me.genre LIKE '%".$_GET['genre']."%' AND
		s.idshop = ".$_GET['idshop']."
	ORDER BY me.datem, me.hin1");

$FoundCount = mysql_num_rows($listing->result); 

# ------- DEBUT LISTING GENERAL --------
$colspa = 4;?>

<?php $tableau = '
	<table class="'.$classe.'" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="'.$classe.'">ID</a></th>
			<th class="'.$classe.'">Date</a></th>
			<th class="'.$classe.'">Client</a></th>
			<th class="'.$classe.'">Lieux</a></th>
			<th class="'.$classe.'">Heures</a></th>
			<th class="'.$classe.'">km</a></th>
			<th class="'.$classe.'">Frais</a></th>
			<th class="'.$classe.'">Remarque</a></th>
			<th class="'.$classe.'"></th>
			<th class="'.$classe.'"></th>
		</tr>';

	$titre = 'go';	
	$date2 = '0000-00-00';
	$jour1 = 'lun';
	$jour2 = 'mar';
	$jour3 = 'mer';
	$jour4 = 'jeu';
	$jour5 = 'ven';
	$jour6 = 'sam';
	$jour7 = 'dim';
	$jourx1 = 'lundi';
	$jourx2 = 'mardi';
	$jourx3 = 'mercredi';
	$jourx4 = 'jeudi';
	$jourx5 = 'vendredi';
	$jourx6 = 'samedi';
	$jourx7 = 'dimanche';
	$jj = 1;
	
	$hautdepage = 'oui';

	while ($row = mysql_fetch_array($listing->result)) { 
	## ligne de semaine ##

	if ($hautdepage == 'oui') {
		$date = weekdate($row['weekm']);
$entete1 = "
<fieldset>
	<legend>
		<b>Planning des Merch week</b>
	</legend>
	<b>
	semaine : ".$row['weekm']." ( ".fdate($date['lun'])." au ".fdate($date['dim'])." )<br>
	people : <img src='".STATIK."illus/".$row['lbureau'].".gif' alt='".$row['lbureau']."' width='12' height='9'> ".$row['codepeople']." - ".$row['idpeople']." - ".$row['pnom']." ".$row['pprenom']."
";
$entete3 = "
	</b><br>
</fieldset>
<br>
";

		echo $entete1;
		if ($row['genre'] == 'Rack assistance') { $entete2 = '<br>lieu : '.$row['idshop'].' - '.$row['ssociete'].'- '.$row['sville']; }
		echo $entete2;
		echo $entete3;
		echo $tableau;
		$hautdepage = 'roger';
	}

$jourprint = 'jour'.$jj; 
$jourprintx = 'jourx'.$jj; 
$datetemp = implode('',explode("-",$date[$$jourprint]));
$datemtemp = implode('',explode("-",$row['datem']));

	### jours vides avant
		while (($datetemp <= $datemtemp) and ($date[$$jourprint] != $date2)) {
#		while ($datetemp <= $datemtemp) {
		?>
			<tr>
				<td bgcolor="white" align="left">
					<?php echo $$jourprintx; ?>
				</td>
				<td bgcolor="white" align="left">
					<?php echo fdate($date[$$jourprint]); ?>
				</td>
				<td bgcolor="white"  colspan="8">&nbsp;</td>
			</tr>
			<?php 
			$jj++;
			$jourprint = 'jour'.$jj; 
			$jourprintx = 'jourx'.$jj; 
	
			list($yyyy, $mm, $dd) = explode('-',$date[$$jourprint]);
			$datetemp = date('Ymd', mktime(0,0,0,$mm,$dd++,$yyyy));
		} 
	#/## jours vides avant

	#/ ## ligne de semaine ##

		#> Changement de couleur des lignes #####>>####
		$i++;
		if (fmod($i, 2) == 1) {
			echo '<tr bgcolor="#9CBECA">';
		} else {
			echo '<tr bgcolor="#8CAAB5">';
		}
		#< Changement de couleur des lignes #####<<####
	?>
					<td class="<?php echo $classe; ?>"><?php echo $row['idmerch']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo fdate($row['datem']); ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['idclient']; ?> - <?php echo $row['clsociete']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['idshop']; ?> - <?php echo $row['ssociete']; ?> - <?php echo $row['sville']; ?></td>
					<?php $merch = new coremerch($row['idmerch']); ?>
					<td class="<?php echo $classe; ?>"><?php echo fnbr($merch->hprest); ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['kmpaye']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo fnbr($row['montantpaye']); ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['produit']; ?></td>
					<th class="<?php echo $classe; ?>" width="15"></th>
					<td class="<?php echo $classe; ?>" width="13"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=show&idmerch='.$row['idmerch'];?>"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
			</tr>
	<?php 
	$date2 = $row['datem']; 
	$zweekm = $row['weekm'];
	$zidpeople = $row['idpeople'];
	$zidshop = $row['idshop'];

	} 
	
	### jours vides après
		$datetemp++;
		while (($datetemp > $datemtemp) and ($date[$$jourprint] != $date2) and ($jj < 8)) {
		?>
		<tr>
			<td bgcolor="white" align="left">
				<?php echo $$jourprintx; ?>
			</td>
			<td bgcolor="white" align="left">
				<?php echo fdate($date[$$jourprint]); ?>
			</td>
			<td bgcolor="#52565F" colspan="8"></td>
		</tr>
		<?php 
		$date2 = $date[$$jourprint]; 
		$jj++;
		$jourprint = 'jour'.$jj; 
		$jourprintx = 'jourx'.$jj; 
		list($yyyy, $mm, $dd) = explode('-',$date[$$jourprint]);
		$datetemp = date('Ymd', mktime(0,0,0,$mm,$dd++,$yyyy));
		} 
	#/## jours vides après
	
	?>
	</table>
</div>
