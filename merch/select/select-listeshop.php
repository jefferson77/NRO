<div id="centerzonelarge">
<?php
$idmerch = $_GET['idmerch'];
$classe = 'standard' ;
$show = '25'; # Nombre de lignes a afficher

### Deuxième étape : Afficher la liste des lieux correspondant a la recherche
switch ($_GET['action']) {
	case "skip": 

		### Deuxième étape - 2 B : Afficher la liste des lieux correspondant a la recherche après SKIP NEXT

		# VARIABLE skip
		if (!empty($_GET['skip'])) {
			$skip=$_GET['skip'];
		}
		else {$skip=0;}
		$skipprev = $skip - $show;
		$skipnext = $skip + $show;
		# VARIABLE SELECT
		if (!empty($_GET['sort'])) {
			$_SESSION['lieusort'] = $_GET['sort'];
		}
		$lieusort = $_SESSION['lieusort'];
		$lieuquid = $_SESSION['lieuquid'];
		$recherche='
			SELECT * FROM `shop` WHERE
			'.$lieuquid.'
			 ORDER BY '.$lieusort.'
			 LIMIT '.$skip.', 25
		';

		# Recherche des résultats
		$lieu = new db();
		$lieu->inline("$recherche;");
	break;
	### Deuxième étape - 2 A : Afficher la liste des lieux correspondant a la recherche SANS SKIP NEXT
	default: 
		# VARIABLE skip
		if (!empty($_GET['skip'])) {
			$skip=$_GET['skip'];
		}
		else {$skip=0;}
		

		$skipprev = $skip - $show;
		$skipnext = $skip + $show;
		
		# VARIABLE SELECT
		
		$lieu = new db();
		
		$searchfields = array (
			'codeshop' 	=> 'codeshop', 
			'societe' 	=> 'societe', 
			'snom' 		=> 'snom', 
			'cp' 		=> 'cp',
			'ville'		=> 'ville'
		);
		
		if ($sort == '') {$sort = 'codeshop';} # sort field

		$sql = "SELECT * FROM shop WHERE ".$lieu->MAKEsearch($searchfields)." ORDER BY $sort ASC LIMIT $skip, $show";
		
		# Recherche des résultats
		$lieu->inline($sql);
		$_SESSION['lieuquid'] = $lieu->quid;
		$_SESSION['lieusort'] = $sort;
}
### Deuxième étape - 2 A Et 2B COMMUN : Afficher la liste des lieux 
?>
<fieldset>
<legend>
	R&eacute;sultats de recherche Job
</legend>
<?php $classe = "standard" ?>
<b>Votre Recherche <br><?php echo $quod?></b><br>
<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$idmerch.'&sel=lieu';?>">Retour &agrave; la recherche</a><br>
<br>
<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
	<tr>
		<td>&nbsp;</td>
		<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&etape=listeshop&action=skip&idmerch='.$idmerch.'&sel=lieu&skip='.$skipprev;?>">Previous</a></td>
		<td class="<?php echo $classe; ?>"><?php echo $skip;?></td>
		<td class="<?php echo $classe; ?>"><?php echo $skip+24;?></td>
		<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&etape=listeshop&action=skip&idmerch='.$idmerch.'&sel=lieu&skip='.$skipnext;?>">Next</a></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<th class="<?php echo $classe; ?>">&nbsp;</th>
		<th class="<?php echo $classe; ?>">Code LIEUX</th>
		<th class="<?php echo $classe; ?>">Client</th>
		<th class="<?php echo $classe; ?>">CP</th>
		<th class="<?php echo $classe; ?>">Ville</th>
		<th class="<?php echo $classe; ?>">Adresse</th>
		<th class="<?php echo $classe; ?>">Nom</th>
		<th class="<?php echo $classe; ?>"></th>
		<th class="<?php echo $classe; ?>"></th>
	</tr>
<?php
while ($row = mysql_fetch_array($lieu->result)) { 
?>
	<tr>
		<td class="<?php echo $classe; ?>"><?php if($row['glat']>0 && $row['glong']>0) echo '<img src="'.STATIK.'illus/geoloc.png" alt="'.$row['glat'].','.$row['glong'].'">';?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['codeshop'] ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['societe']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['ville']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['cp']; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['adresse'].' '.$row['cp'].' '.$row['pays'] ; ?></td>
		<td class="<?php echo $classe; ?>"><?php echo $row['snom']; ?></td>
		<td class="<?php echo $classe; ?>">
			<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=lieu'?>" method="post">
				<input type="hidden" name="idmerch" value="<?php echo $idmerch ;?>"> 
				<input type="hidden" name="idshop" value="<?php echo $row['idshop'] ;?>"> 
				<input type="hidden" name="codeshop" value="<?php echo (!empty($row['codeshop']))?$row['codeshop']:'SH'.$row['idshop'] ;?>"> 
				<input type="submit" name="select" value="Select"> 
			</form>
		</td>
		<td class="<?php echo $classe; ?>">
			<form action="<?php echo NIVO ?>data/shop/adminshop.php?act=show&idshop=<?php echo $row['idshop'];?>" method="post">
				<input type="hidden" name="idmerch" value="<?php echo $idmerch ;?>"> 
				<input type="hidden" name="idshop" value="<?php echo $row['idshop'] ;?>"> 
				<input type="submit" name="modif" value="Modif"> 
			</form>
		</td>
	</tr>
	<?php 
	$count++;
} ?>
</table>
<br>
<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="40%">
<tr bgcolor="#FF6699">
	<td class="<?php echo $classe; ?>" align="center"> 
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=lieu';?>" method="post">
			<input type="hidden" name="idmerch" value="<?php echo $idmerch ;?>"> 
			<input type="hidden" name="idshop" value=""> 
		<input type="submit" name="Selectionner" value="Remove LIEU from JOB"> 
		</form>
	</td>
</tr>
</table>
	</fieldset>
<?php echo $client->quid;?>
	<br>	
<?php echo $count;?>	Results
</div>