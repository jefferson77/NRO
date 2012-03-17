<?php #  Sélection des éléments ?>
<div id="centerzonelarge">
<?php

$classe = 'standard' ;
$show = '25'; # Nombre de lignes a afficher

if (!empty($_POST['etape'])) {$_GET['etape'] = $_POST['etape'];}
################# Sélection du Lieu   ###################
	switch ($_GET['etape']) {
		### Deuxième étape : Afficher la liste des lieux correspondant a la recherche
		case "listeshop": 
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
					$lieuquid = $_SESSION['lieuquid2'];
					$recherche='
						SELECT * FROM `shop`
						'.$lieuquid.'
						 ORDER BY '.$lieusort.'
						 LIMIT '.$skip.', '.$show.'
					';
		
					# Recherche des résultats
					$client = new db();
					$client->inline("$recherche;");
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
					$_SESSION['lieuquid2'] = $lieu->quid;
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
						<a href="<?php echo $_SERVER['PHP_SELF'].'?act=selectlieu&down='.$down.'&idanimation='.$idanimation.'&idanimjob='.$idanimjob.'&sel=lieu';?>">Retour &agrave; la recherche</a><br>
						<br>
						<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
							<tr>
								<td class="<?php echo $classe; ?>" colspan="2"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=selectlieu&down='.$down.'&etape=listeshop&action=skip&idanimation='.$idanimation.'&idanimjob='.$idanimjob.'&sel=lieu&skip='.$skipprev;?>">Previous</a></td>
								<td class="<?php echo $classe; ?>" colspan="4" align="center"><?php echo $skip + 1;?> - <?php echo $skip + $show;?></td>
								<td class="<?php echo $classe; ?>" colspan="2" align="right"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=selectlieu&down='.$down.'&etape=listeshop&action=skip&idanimation='.$idanimation.'&idanimjob='.$idanimjob.'&sel=lieu&skip='.$skipnext;?>">Next</a></td>
							</tr>
							<tr>
								<th class="<?php echo $classe; ?>"></th>
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
<?php
								## Geoloc
								if ($row['glat1'] > 0) {
									echo '<td class="'.$classe.'er"><img src="'.STATIK.'illus/geoloc.png" alt="geoloc.png" width="16" height="15" align="right"></td>';
								} else {
									echo '<td class="'.$classe.'"></td>';
								} ?>
								<td class="<?php echo $classe; ?>"><?php echo $row['codeshop'] ?></td>
								<td class="<?php echo $classe; ?>"><?php echo $row['societe']; ?></td>
								<td class="<?php echo $classe; ?>"><?php echo $row['ville']; ?></td>
								<td class="<?php echo $classe; ?>"><?php echo $row['cp']; ?></td>
								<td class="<?php echo $classe; ?>"><?php echo $row['adresse'].' '.$row['cp'].' '.$row['pays'] ; ?></td>
								<td class="<?php echo $classe; ?>"><?php echo $row['snom']; ?></td>
								<td class="<?php echo $classe; ?>">
										<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modiflieu&down='.$down.'';?>" method="post">
											<input type="hidden" name="idanimation" value="<?php echo $idanimation ;?>"> 
											<input type="hidden" name="idanimjob" value="<?php echo $idanimjob ;?>"> 
											<input type="hidden" name="idshop" value="<?php echo $row['idshop'] ;?>"> 
											<input type="submit" name="select" value="Selectionner">
										</form>
								</td>
								<td class="<?php echo $classe; ?>">
									<form action="<?php echo NIVO ?>data/shop/adminshop.php?act=show&idshop=<?php echo $row['idshop'];?>" method="post">
											<input type="hidden" name="idanimation" value="<?php echo $idanimation ;?>"> 
											<input type="hidden" name="idanimjob" value="<?php echo $idanimjob ;?>"> 
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
						<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modiflieu&down='.$down.'';?>" method="post">
							<input type="hidden" name="idanimation" value="<?php echo $idanimation ;?>"> 
							<input type="hidden" name="idanimjob" value="<?php echo $idanimjob ;?>"> 
							<input type="hidden" name="idshop" value=""> 
						<input type="submit" name="Selectionner" value="Remove LIEU from JOB"> 
						</form>
					</td>
				</tr>
			</table>
					</fieldset>
				<?php echo $client->quid;?><br>	
				<?php echo $count;?>	Results
<?php

		break;
		### Première étape : formulaire de recherche de Lieu
		default: 
			$_SESSION['lieuquid'] = $quid;
			$_SESSION['lieusort'] = $sort;
		# VIDER LA SESSION
?>
		<h1 align="center">Recherche des Lieux ANIM</h1>
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=selectlieu&etape=listeshop&down='.$down.'&idanimation='.$idanimation.'&idanimjob='.$idanimjob.'&sel=lieu';?>" method="post">
			<input type="hidden" name="etape" value="listeshop"> 
		<fieldset>
			<legend>
				Infos de recherche
			</legend>
			<label for="codeshop">Code</label><input type="text" name="codeshop" id="codeshop"><br>
			<label for="societe">Soci&eacute;t&eacute;</label><input type="text" name="societe" id="societe"><br>
			<label for="snom">Contact</label><input type="text" name="snom" id="snom"><br>
			<label for="cp">CP</label><input type="text" name="cp" id="cp"><br> 
			<label for="ville">Ville</label><input type="text" name="ville" id="ville"> 
		</fieldset>
		<div align="center"><input type="submit" name="Rechercher" value="Rechercher"></div>
		</form>
			<br>
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="40%">
				<tr bgcolor="#FF6699">
					<td class="<?php echo $classe; ?>" align="center"> 
						<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modiflieu&down='.$down.'';?>" method="post">
							<input type="hidden" name="idanimation" value="<?php echo $idanimation ;?>"> 
							<input type="hidden" name="idanimjob" value="<?php echo $idanimjob ;?>"> 
							<input type="hidden" name="idshop" value=""> 
						<input type="submit" name="Selectionner" value="Remove LIEU from ANIM"> 
						</form>
					</td>
				</tr>
			</table>
<?php
	}
?>
</div>