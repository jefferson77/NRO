<?php #  Centre de recherche PEOPLE ?>
<?php 

switch ($_GET['action']) {
	case "skip": 

		### Deuxième étape - 2 B : Afficher la liste des People correspondant a la recherche après SKIP NEXT

		# VARIABLE skip
		if (!empty($_GET['skip'])) {
			$skip=$_GET['skip'];
			$_SESSION['peoplewebnewskip'] = $skip;
		}
		else {$skip=0;
		}
		$skipprev=$skip-25;
		$skipnext=$skip+25;
		# VARIABLE SELECT
		if (!empty($_GET['sort'])) {
			$_SESSION['peoplewebnewsort'] = $_GET['sort'];
		}
		$skip = $_SESSION['peoplewebnewskip'] ;
		$peoplesort = $_SESSION['peoplewebnewsort'];
		$peoplequid = $_SESSION['peoplewebnewquid'];
		$peoplequod = $_SESSION['peoplewebnewquod'];
		$peoplecatego = $_SESSION['peoplewebnewcatego'];

		$recherche='
			SELECT p.idpeople, p.pnom, p.pprenom, p.sexe, p.lfr, p.lnl, p.len, p.codepeople, 
			p.cp1, p.ville1, p.cp2, p.ville2, p.gsm, p.err, p.email
			FROM webpeople p
			'.$peoplequid.'
			 GROUP BY p.idpeople
			 ORDER BY '.$peoplesort.'
			 LIMIT '.$skip.', 25
		';
		
		# Recherche des résultats
		$client = new db();
		$client->inline("$recherche;");
	break;
	### Deuxième étape - 2 A : Afficher la liste des People correspondant a la recherche SANS SKIP NEXT
	default: 


		# VARIABLE skip
		if (!empty($_GET['skip'])) {
			$skip=$_GET['skip'];
		}
		else {$skip=0;}
		$skipprev=$skip-25;
		$skipnext=$skip+25;
		# VARIABLE SELECT
			if (!empty($quid)) 
			{
				$quid=$quid." AND ";
				$quod=$quod." ET ";
			}	
			$quid=$quid."p.webetat >= 4 ";
				$quid=$quid." AND ";
			$quid=$quid."p.webtype = 0 ";



	#	# ATTENTION POUR TEXTE : LIKE '%XXXX%'
		
		if (!empty($quid)) {$quid='WHERE '.$quid;}
		if ($sort == '') {$sort='p.idwebpeople DESC';}
		$recherche='
			SELECT p.idwebpeople, p.pnom, p.pprenom, p.sexe, p.lfr, p.lnl, p.len, p.codepeople, 
			p.cp1, p.ville1, p.cp2, p.ville2, p.gsm, p.isout, p.err, p.categorie, p.notemerch,
			p.permis, p.voiture, p.physio, p.province, p.taille, p.ccheveux, p.lcheveux, p.tveste, p.tjupe, p.ndate, p.email,
			p.webtype, p.webetat, p.categorie
			FROM webpeople p
			'.$quid.'
			 GROUP BY p.idwebpeople
			 ORDER BY '.$sort.'
		';
#						 LIMIT '.$skip.', 25
		
		
		$_SESSION['peoplewebnewskip'] = $skip;
		$_SESSION['peoplewebnewquid'] = $quid;
		$_SESSION['peoplewebnewquod'] = $quod;
		$_SESSION['peoplewebnewsort'] = $sort;
	
	#	echo $recherche;
	#	echo $idvip;
	#	echo $idvipjob;
		# Recherche des résultats
	}
	### Deuxième étape - 2 A Et 2B COMMUN : Afficher la liste des people 
$classe = standard;

$people = new db('webpeople', 'idwebpeople', 'webneuro');
$people->inline("$recherche;");
$FoundCount = mysql_num_rows($people->result);
?>
<div id="centerzonelarge">	
<fieldset>
<legend>
	Recherche des People Web NEW : <?php echo '('.$FoundCount.' Results)'; ?>
</legend>
<table class="<?php echo $classe; ?>" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
	<tr>
		<th class="<?php echo $classe; ?>"></th>
		<th class="<?php echo $classe; ?>">Code-id</th>
		<th class="<?php echo $classe; ?>">Nom</th>
		<th class="<?php echo $classe; ?>">Pr&eacute;nom</th>
		<th class="<?php echo $classe; ?>">Sexe</th>
		<th class="<?php echo $classe; ?>">Fr</th>
		<th class="<?php echo $classe; ?>">NL</th>
		<th class="<?php echo $classe; ?>">En</th>
		<th class="<?php echo $classe; ?>">CP</th>
		<th class="<?php echo $classe; ?>">Ville</th>
		<th class="<?php echo $classe; ?>">CP2</th>
		<th class="<?php echo $classe; ?>">Ville2</th>
		<th class="<?php echo $classe; ?>">GSM</th>
		<th class="<?php echo $classe; ?>">mail</th>
		<th class="<?php echo $classe; ?>"></th>
	</tr>
<?php while ($row = mysql_fetch_array($people->result)) {

### comparaison newpeople et people sur : pnom AND pprenom
$pnom = addslashes($row['pnom']);
$pprenom = addslashes($row['pprenom']);

$verifp = new db();
$verifp->inline("SELECT * FROM `people` WHERE `pnom` = '$pnom' AND `pprenom` = '$pprenom'");
$infosverifp = mysql_fetch_array($verifp->result) ; 
#/## comparaison newpeople et people sur : pnom AND pprenom

if ($row['err'] == 'Y') {
echo '<tr style="background-color: #F4D25C;"><td class="'.$classe.'er"><img src="'.STATIK.'illus/attention.gif" alt="attention.gif" width="14" height="14" align="right"></td>';
} else {
echo '<tr><td class="'.$classe.'"></td>';
}
### comparaison newpeople et people sur : pnom AND pprenom
if (!empty($infosverifp['idpeople'])) {
echo '<tr style="background-color: #FF9900;"><td class="'.$classe.'er"><img src="'.STATIK.'illus/attention.gif" alt="attention.gif" width="14" height="14" align="right"></td>';
?>
		<td class="<?php echo $classe; ?>"><?php echo $infosverifp['codepeople'].'-'.$infosverifp['idpeople']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $infosverifp['pnom']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $infosverifp['pprenom']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $infosverifp['sexe']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $infosverifp['lfr']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $infosverifp['lnl']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $infosverifp['len']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $infosverifp['cp1']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $infosverifp['ville1']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $infosverifp['cp2']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $infosverifp['ville2']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $infosverifp['gsm']; ?></td>
		<td class="<?php echo $classe; ?>"><a href="mailto:<?php echo $infosverifp['email']; ?>"><?php echo $infosverifp['email']; ?></a></td>
		<td class="<?php echo $classe; ?>"></td>
	</tr>
<?php
echo '<tr style="background-color: #FF9900;"><td class="'.$classe.'er"><img src="'.STATIK.'illus/attention.gif" alt="attention.gif" width="14" height="14" align="right"></td>';
}
#/## comparaison newpeople et people sur : pnom AND pprenom

?>		
		<td class="<?php echo $classe; ?>"><?php echo $row['codepeople'].'-'.$row['idpeople']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['pnom']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['pprenom']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['sexe']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['lfr']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['lnl']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['len']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['cp1']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['ville1']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['cp2']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['ville2']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['gsm']; ?></td>
		<td class="<?php echo $classe; ?>"><a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a></td>
		<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=webnew1&idwebpeople='.$row['idwebpeople'];?>"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
	</tr>
<?php } ?>
</table>
</fieldset>
</div>