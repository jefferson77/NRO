<div class="news">
	<table width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="fulltitre" colspan="2"><?php echo $tool_01; ?><br><?php echo $anim_sales_00; ?></td>
		</tr>
		<tr>
			<td class="newstit">1. <?php echo $anim_sales_menu_01; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li> <?php echo $anim_sales_menu_02a; ?>
					<li> <?php echo $anim_sales_menu_02b; ?>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="newstit">2. <?php echo $anim_sales_menu_03; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li><?php echo $anim_sales_menu_04; ?>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="newstit">3. <?php echo $anim_sales_menu_05; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li> <?php echo $anim_sales_menu_06; ?>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="newstit">4. <?php echo $anim_sales_menu_07; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li> <?php echo $anim_sales_menu_08; ?>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="newstxt">
				<?php echo $anim_sales_menu_11; ?>
			</td>
		</tr>
	</table>
</div>
<div class="corps">
<?php $classe = "planning" ; 

# VARIABLE SELECT
	# genre
	if ($_GET['genre'] == 'Rack assistance') {
		if (!empty($quid)) 
		{
			$quid .= " AND ";
			$quod .= " ET ";
		}	
		$quid .= "s.idshop = ".$_GET['idshop'];
		$quod .= "idshop = ".$_GET['idshop'];

	}
		if (!empty($quid)) 
		{
			$quid .= " AND ";
			$quod .= " ET ";
		}	
		$quid .= "me.genre LIKE '%".$_GET['genre']."%'";
		$quod .= "genre = ".$_GET['genre'];
	# genre

	# idpeople
		if (!empty($quid)) 
		{
			$quid .= " AND ";
			$quod .= " ET ";
		}	
		$quid .= "p.idpeople = ".$_GET['idpeople'];
		$quod .= "idpeople = ".$_GET['idpeople'];
	#/ idpeople

	# weekm1
		if (!empty($quid)) 
		{
			$quid .= " AND ";
			$quod .= " ET ";
		}	
		$quid .= "me.weekm = '".$_GET['weekm']."'";
		$quod .= "weekm1 = ".$_GET['weekm'];
	#/ weekm1
#/ VARIABLE SELECT

$listing = new db();
$listing->inline("
		SELECT
			me.idmerch, me.datem, me.weekm, me.genre, 
				me.hin1, me.hout1, me.hin2, me.hout2, 
				me.kmpaye, me.kmfacture,
				me.produit, me.facturation, 
				me.ferie, me.contratencode,
			a.prenom, a.idagent, 
			c.idclient, c.codeclient, c.societe AS clsociete, c.idclient, c.tel, c.fax, 
			co.idcofficer, co.qualite, co.onom, co.oprenom, co.fax AS cofax, 
			s.idshop, s.codeshop, s.societe AS ssociete, s.ville AS sville, 
			p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.gsm, p.codepeople,
			nf.montantfacture, nf.montantpaye
			
		FROM merch me
			LEFT JOIN agent a ON me.idagent = a.idagent 
			LEFT JOIN client c ON me.idclient = c.idclient 
			LEFT JOIN cofficer co ON me.idcofficer = co.idcofficer 
			LEFT JOIN people p ON me.idpeople = p.idpeople
			LEFT JOIN shop s ON me.idshop = s.idshop
			LEFT JOIN notefrais nf ON nf.secteur = 'ME' AND nf.idmission = me.idmerch
		
		WHERE
			".$quid."
		ORDER BY me.datem, me.hin1");
$FoundCount = mysql_num_rows($listing->result); 

# ------- DEBUT LISTING GENERAL --------
$colspa = 4;?>

<?php $tableau = '
	<table class="'.$classe.'" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="'.$classe.'">ID</a></th>
			<th class="'.$classe.'">Date</a></th>
			<th class="'.$classe.'">Lieux</a></th>
			<th class="'.$classe.'">Heures</a></th>
			<th class="'.$classe.'">km</a></th>
			<th class="'.$classe.'">Frais</a></th>
			<th class="'.$classe.'">Remarque</a></th>
		</tr>
	';

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
	people : <img src='".NIVO."webclient/illus/".$row['lbureau'].".gif' alt='".$row['lbureau']."' width='12' height='9'> ".$row['codepeople']." - ".$row['idpeople']." - ".$row['pnom']." ".$row['pprenom']."
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
					<td class="<?php echo $classe; ?>"><?php echo $row['idshop']; ?> - <?php echo $row['ssociete']; ?> - <?php echo $row['sville']; ?></td>
					<?php $merch = new coremerch($row['idmerch']); ?>
					<td class="<?php echo $classe; ?>"><?php echo fnbr($merch->hprest); ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['kmpaye']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo fnbr($row['frais']); ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['produit']; ?></td>
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
<br>
</div>
