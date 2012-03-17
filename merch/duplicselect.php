<?php #  Sélection des éléments ?>
<div id="centerzone">
<?php
$classe = 'standard' ;


switch ($_GET['sel']) {


################# Sélection de l'Agent ######################################
	case "agent": 
	
	switch ($_POST['etape']) {
		### Deuxième étape : Afficher la liste des Agents correspondant a la recherche
		case "listeagent": 

?>
			<h1 align="center">S&eacute;lection des Agents</h1>
			<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$did.'&sel=agent';?>">Retour &agrave; la recherche</a><br>
			<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Nom</th>
					<th class="<?php echo $classe; ?>">Pr&eacute;nom</th>
					<th class="<?php echo $classe; ?>">Selection</th>
				</tr>
		<?php
		# Recherche des 15 clients
		$searchagent = $_POST['searchagent'];
		$agent = new db();
		$agent->inline("SELECT * FROM `agent` WHERE `nom` LIKE '%$searchagent%'");
		
		while ($row = mysql_fetch_array($agent->result)) { 
		?>
				<tr>
					<td class="<?php echo $classe; ?>"><?php echo $row['nom']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['prenom']; ?></td>
					<td class="<?php echo $classe; ?>">
						<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=agent';?>" method="post">
							<input type="hidden" name="idmerch" value="<?php echo $did ;?>"> 
							<input type="hidden" name="idagent" value="<?php echo $row['idagent'] ;?>"> 
							<input type="submit" name="Selectionner" value="Selectionner"> 
						</form>
					</td>
				</tr>
		<?php } ?>
			</table>
<?php

		break;
		### Première étape : formulaire de recherche de Agents
		default: 
?>
		<h1 align="center">Recherche des Agents</h1>
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$did.'&sel=agent';?>" method="post">
			<input type="hidden" name="etape" value="listeagent"> 
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
				<tr>
					<td>
						Nom de l&rsquo;Agent
					</td>
					<td>
						<input type="text" size="20" name="searchagent" value="">
					</td>
				</tr>
			</table>
			<input type="submit" name="Rechercher" value="Rechercher"> 
		</form>
<?php
	}
	
	break;
####





################# Sélection du Lieu   ###################
	case "lieu": 
	
	switch ($_POST['etape']) {
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
					$skipprev=$skip-25;
					$skipnext=$skip+25;
					# VARIABLE SELECT
					if (!empty($_GET['sort'])) {
						$_SESSION['lieusort'] = $_GET['sort'];
					}
					$lieusort = $_SESSION['lieusort'];
					$lieuquid = $_SESSION['lieuquid'];
					$recherche='
						SELECT * FROM `shop`
						'.$lieuquid.'
						 ORDER BY '.$lieusort.'
						 LIMIT '.$skip.', 25
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
					$skipprev=$skip-25;
					$skipnext=$skip+25;
					# VARIABLE SELECT
					
					$client = new db();
					
					$searchfields = array (
						'codeshop' 	=> 'TXT', 
						'societe' 	=> 'TXT', 
						'snom' 		=> 'TXT', 
						'cp' 		=> 'TXT',
						'ville'		=> 'TXT'
					);
					
					if ($sort == '') {$sort='codeshop';} # sort field
					$show = '25'; # Nombre de lignes a afficher
					
					$client->MAKEsearch($searchfields, "*", 'shop', $sort, $skip, $show);
					
					# Recherche des résultats
					$client->inline($client->recherche);
					$_SESSION['lieuquid'] = $client->quid;
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
						<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$did.'&sel=lieu';?>">Retour &agrave; la recherche</a><br>
						<br>
						<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
							<tr>
								<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&etape=listeshop&action=skip&idmerch='.$did.'&sel=lieu&skip='.$skipprev;?>">Previous</a></td>
								<td class="<?php echo $classe; ?>"><?php echo $skip;?></td>
								<td class="<?php echo $classe; ?>"><?php echo $skip+24;?></td>
								<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&etape=listeshop&action=skip&idmerch='.$did.'&sel=lieu&skip='.$skipnext;?>">Next</a></td>
							</tr>
							<tr>
								<th class="<?php echo $classe; ?>">Code LIEUX</th>
								<th class="<?php echo $classe; ?>">Client</th>
								<th class="<?php echo $classe; ?>">CP</th>
								<th class="<?php echo $classe; ?>">Ville</th>
								<th class="<?php echo $classe; ?>">Adresse</th>
								<th class="<?php echo $classe; ?>">Nom</th>
								<th class="<?php echo $classe; ?>"></th>
							</tr>
					<?php
					while ($row = mysql_fetch_array($client->result)) { 
					?>
							<tr>
								<td class="<?php echo $classe; ?>"><?php echo $row['codeshop'] ?></td>
								<td class="<?php echo $classe; ?>"><?php echo $row['societe']; ?></td>
								<td class="<?php echo $classe; ?>"><?php echo $row['ville']; ?></td>
								<td class="<?php echo $classe; ?>"><?php echo $row['cp']; ?></td>
								<td class="<?php echo $classe; ?>"><?php echo $row['adresse'].' '.$row['cp'].' '.$row['pays'] ; ?></td>
								<td class="<?php echo $classe; ?>"><?php echo $row['snom']; ?></td>
								<td class="<?php echo $classe; ?>">
										<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=lieu';?>" method="post">
											<input type="hidden" name="idmerch" value="<?php echo $did ;?>"> 
											<input type="hidden" name="idshop" value="<?php echo $row['idshop'] ;?>"> 
											<input type="submit" name="Selectionner" value="Selectionner"> 
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
							<input type="hidden" name="idmerch" value="<?php echo $did ;?>"> 
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
		<h1 align="center">Recherche des Lieux merch</h1>
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&etape=listeshop&idmerch='.$did.'&sel=lieu';?>" method="post">
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
						<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=lieu';?>" method="post">
							<input type="hidden" name="idmerch" value="<?php echo $did ;?>"> 
							<input type="hidden" name="idshop" value=""> 
						<input type="submit" name="Selectionner" value="Remove LIEU from merch"> 
						</form>
					</td>
				</tr>
			</table>
<?php
	}
	break;






################# Sélection du People ###################
	case "people": 
	
	switch ($_POST['etape']) {
		### Deuxième étape : Afficher la liste des People correspondant à la recherche
		case "listepeople": 
			switch ($_GET['action']) {
				case "skip": 

					### Deuxième étape - 2 B : Afficher la liste des People correspondant a la recherche après SKIP NEXT
		
					# VARIABLE skip
					if (!empty($_GET['skip'])) {
						$skip=$_GET['skip'];
					}
					else {$skip=0;
					}
					$skipprev=$skip-25;
					$skipnext=$skip+25;
					# VARIABLE SELECT
					if (!empty($_GET['sort'])) {
						$_SESSION['peoplesort'] = $_GET['sort'];
					}
					$peoplesort = $_SESSION['peoplesort'];
					$peoplequid = $_SESSION['peoplequid'];
					$recherche='
						SELECT * FROM `people`
						'.$peoplequid.'
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
					if (!empty($_POST['pnom'])) {
						$quid=$quid."pnom LIKE '%".$_POST['pnom']."%'";
						$quod=$quod."Nom = ".$_POST['pnom'];
					}
					if (!empty($_POST['pprenom'])) {
						if (!empty($quid)) 
						{
							$quid=$quid." AND ";
							$quod=$quod." ET ";
						}	
						$quid=$quid."pprenom LIKE '%".$_POST['pprenom']."%'";
						$quod=$quod."pprenom = ".$_POST['pprenom'];
					}
					if (!empty($_POST['codepeople'])) {
						if (!empty($quid)) 
						{
							$quid=$quid." AND ";
							$quod=$quod." ET ";
						}	
						$quid=$quid."codepeople = ".$_POST['codepeople'];
						$quod=$quod."codepeople = ".$_POST['codepeople'];
					}
					if ((!empty($_POST['sexe'])) AND ($_POST['sexe'] != 'x')) {
						if (!empty($quid)) 
						{
							$quid=$quid." AND ";
							$quod=$quod." ET ";
						}	
						$quid=$quid."sexe LIKE '%".$_POST['sexe']."%'";
						$quod=$quod."sexe = ".$_POST['sexe'];
					}
					if (!empty($_POST['lfr'])) {
						if (!empty($quid)) 
						{
							$quid=$quid." AND ";
							$quod=$quod." ET ";
						}	
						$quid=$quid."lfr >= ".$_POST['lfr'];
						$quod=$quod."lfr >= ".$_POST['lfr'];
					}
					if (!empty($_POST['lnl'])) {
						if (!empty($quid)) 
						{
							$quid=$quid." AND ";
							$quod=$quod." ET ";
						}	
						$quid=$quid."lnl >= ".$_POST['lnl'];
						$quod=$quod."lnl >= ".$_POST['lnl'];
					}
					if ($_POST[cp1a] != '') {
						if (!empty($quid)) 
						{
							$quid=$quid." AND ";
							$quod=$quod." ET ";
						}	
						$quid=$quid."cp1 >= ".$_POST[cp1a];
						$quod=$quod."cp1 >= ".$_POST[cp1a];
					}
					if ($_POST[cp1b] != '') {
						if (!empty($quid)) 
						{
							$quid=$quid." AND ";
							$quod=$quod." ET ";
						}	
						$quid=$quid."cp1 <= ".$_POST[cp1b];
						$quod=$quod."cp1 <= ".$_POST[cp1b];
					}
					if ($_POST[ville1] != '') {
						if (!empty($quid)) 
						{
							$quid=$quid." AND ";
							$quod=$quod." ET ";
						}	
						$quid=$quid."ville1 LIKE '%".$_POST[ville1]."%'";
						$quod=$quod."ville = ".$_POST[ville1];
					}
					if (!empty($_POST['gsm'])) {
						if (!empty($quid)) 
						{
							$quid=$quid." AND ";
							$quod=$quod." ET ";
						}	
						$quid=$quid."gsm LIKE '%".$_POST['gsm']."%'";
						$quod=$quod."gsm = ".$_POST['gsm'];
					}
				#	# ATTENTION POUR TEXTE : LIKE '%XXXX%'
					
					if (!empty($quid)) {$quid='WHERE '.$quid;}
					if ($sort == '') {$sort='codepeople';}
					$recherche='
						SELECT * FROM `people`
						'.$quid.'
						 ORDER BY '.$sort.'
						 LIMIT '.$skip.', 25
					';
					$_SESSION['peoplequid'] = $quid;
					$_SESSION['peoplequod'] = $quod;
					$_SESSION['peoplesort'] = $sort;
				
				#	echo $recherche;
				#	echo $idvip;
				#	echo $idvipjob;
					# Recherche des résultats
				}
				### Deuxième étape - 2 A Et 2B COMMUN : Afficher la liste des people 
?>

			<h1 align="center">S&eacute;lection d'un People </h1>
		<b>Votre Recherche <br><?php echo $_SESSION['peoplequod']?></b><br>
			<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$did.'&sel=people';?>">Retour &agrave; la recherche</a><br>
		<br>
			<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<td class="<?php echo $classe; ?>" colspan="2"><a href="<?php echo $_SERVER['PHP_SELF'].'?&idmerch='.$did.'&act=select&sel=people&etape=listepeople&action=skip&skip='.$skipprev; ?>">Previous</a></td>
					<td class="<?php echo $classe; ?>" colspan="2"><?php echo $skip;?></td>
					<td class="<?php echo $classe; ?>" colspan="2"><?php echo $skip+24;?></td>
					<td class="<?php echo $classe; ?>" colspan="2"><a href="<?php echo $_SERVER['PHP_SELF'].'?&idmerch='.$did.'&act=select&sel=people&etape=listepeople&action=skip&skip='.$skipnext; ?>">Next</a></td>
				</tr>
				<tr>
					<th class="<?php echo $classe; ?>">Code - id</th>
					<th class="<?php echo $classe; ?>">Nom</th>
					<th class="<?php echo $classe; ?>">Pr&eacute;nom</th>
					<th class="<?php echo $classe; ?>">Sexe</th>
					<th class="<?php echo $classe; ?>">Fr</th>
					<th class="<?php echo $classe; ?>">NL</th>
					<th class="<?php echo $classe; ?>">CP</th>
					<th class="<?php echo $classe; ?>">Ville</th>
					<th class="<?php echo $classe; ?>">GSM</th>
					<th class="<?php echo $classe; ?>"></th>
				</tr>
		<?php
		# Recherche des 15 clients
		$people = new db();
		$people->inline("$recherche;");
		
		while ($row = mysql_fetch_array($people->result)) { 
		?>
				<tr>
					<td class="<?php echo $classe; ?>"><?php echo $row['codepeople'].' - '.$row['idpeople']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['pnom']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['pprenom']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['sexe']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['lfr']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['lnl']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['cp1']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['ville1']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['gsm']; ?></td>
					<td class="<?php echo $classe; ?>">
						<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=people&etat=1';?>" method="post">
							<input type="hidden" name="idmerch" value="<?php echo $did ;?>"> 
							<input type="hidden" name="idpeople" value="<?php echo $row['idpeople'] ;?>"> 
							<input type="submit" name="Selectionner" value="Selectionner"> 
						</form>
					</td>
				</tr>
		<?php 
			$count++;
			}
		?>
			</table>
			<br>
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="40%">
				<tr bgcolor="#FF6699">
					<td class="<?php echo $classe; ?>" align="center"> 
						<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=people&etat=1';?>" method="post">
							<input type="hidden" name="idmerch" value="<?php echo $did ;?>"> 
							<input type="hidden" name="idpeople" value=""> 
							<input type="submit" name="Selectionner" value="Remove People from Mission"> 
						</form>
					</td>
				</tr>
			</table>

<?php #echo $quid;?><br>	
<?php echo $count;?>	Results
<?php
		break;
		### Première étape : formulaire de recherche de People
		default: 
			$_SESSION['peoplequid'] = $quid;
			$_SESSION['peoplequod'] = $quod;
			$_SESSION['peoplesort'] = $sort;
?>
		<h1 align="center">Recherche des People</h1>
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&etape=listepeople&idmerch='.$did.'&sel=people';?>" method="post">
			<input type="hidden" name="etape" value="listepeople"> 
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
				<tr>
					<td>
						Nom
					</td>
					<td>
						<input type="text" size="20" name="pnom" value="">
					</td>
				</tr>
				<tr>
					<td>
						Pr&eacute;nom
					</td>
					<td>
						<input type="text" size="20" name="pprenom" value="">
					</td>
				</tr>
				<tr>
					<td>
						code people (registre)
					</td>
					<td>
						<input type="text" size="20" name="codepeople" value="">
					</td>
				</tr>
				<tr>
					<td>
						Sexe
					</td>
					<td>
						<?php 
						echo '<input type="radio" name="sexe" value="x" '; if ($_GET['sexe'] == 'x') { echo 'checked';} echo'> X';
						echo '<input type="radio" name="sexe" value="f" '; if ($_GET['sexe'] == 'f') { echo 'checked';} echo'> F';
						echo '<input type="radio" name="sexe" value="m" '; if ($_GET['sexe'] == 'm') { echo 'checked';} echo'> M';
						?>
					</td>
				</tr>
				<tr>
					<td>
						Fr
					</td>
					<td>
							<input type="radio" name="lfr" value="0"> 0
							<input type="radio" name="lfr" value="1"> 1
							<input type="radio" name="lfr" value="2"> 2
							<input type="radio" name="lfr" value="3"> 3
							<input type="radio" name="lfr" value="4"> 4
					</td>
				</tr>
				<tr>
					<td>
						NL
					</td>
					<td>
							<input type="radio" name="lnl" value="0"> 0
							<input type="radio" name="lnl" value="1"> 1
							<input type="radio" name="lnl" value="2"> 2
							<input type="radio" name="lnl" value="3"> 3
							<input type="radio" name="lnl" value="4"> 4
					</td>
				</tr>
				<tr>
					<td>
						CP
					</td>
					<td>
						<input type="text" size="10" name="cp1a" value=""> à <input type="text" size="10" name="cp1b" value="">
					</td>
				</tr>
				<tr>
					<td>
						Ville
					</td>
					<td>
						<input type="text" size="20" name="ville1" value="">
					</td>
				</tr>
				<tr>
					<td>
						GSM
					</td>
					<td>
						<input type="text" size="20" name="gsm" value="">
					</td>
				</tr>
			</table>
				<input type="submit" name="Rechercher" value="Rechercher"><br>
				<input type="reset" name="reset" value="Reset"> 
		</form>
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="40%">
				<tr bgcolor="#FF6699">
					<td class="<?php echo $classe; ?>" align="center"> 
						<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=people&etat=1';?>" method="post">
							<input type="hidden" name="idmerch" value="<?php echo $did ;?>"> 
							<input type="hidden" name="idpeople" value=""> 
							<input type="submit" name="Selectionner" value="Remove People from Mission"> 
						</form>
					</td>
				</tr>
			</table>
<?php
	}
	
	break;








################# Sélection du Client ###################
	case "client": 
	default: 
		
	switch ($_POST['etape']) {
		### Deuxième étape : Afficher la liste des clients correspondant a la recherche
		case "listeclient": 

		# VARIABLE skip
		if (!empty($_GET['skip'])) {
			$skip=$_GET['skip'];
		}
		else {$skip=0;}
		$skipprev=$skip-35;
		$skipnext=$skip+35;
		# VARIABLE SELECT
		if (!empty($_POST['codeclient'])) {
			$quid=$quid."codeclient = ".$_POST['codeclient'];
			$quod=$quod."codeclient = ".$_POST['codeclient'];
		}
		if (!empty($_POST['societe'])) {
			if (!empty($quid)) 
			{
				$quid=$quid." AND ";
				$quod=$quod." ET ";
			}	
			$quid=$quid."societe LIKE '%".$_POST['societe']."%'";
			$quod=$quod."societe = ".$_POST['societe'];
		}
		if (!empty($_POST['cp'])) {
			if (!empty($quid)) 
			{
				$quid=$quid." AND ";
				$quod=$quod." ET ";
			}	
			$quid=$quid."cp LIKE '%".$_POST['cp']."%'";
			$quod=$quod."cp = ".$_POST['cp'];
		}
		if (!empty($_POST['ville'])) {
			if (!empty($quid)) 
			{
				$quid=$quid." AND ";
				$quod=$quod." ET ";
			}	
			$quid=$quid."ville LIKE '%".$_POST['ville']."%'";
			$quod=$quod."ville = ".$_POST['ville'];
		}
	#	# ATTENTION POUR TEXTE : LIKE '%XXXX%'
		
		if (!empty($quid)) {$quid='WHERE '.$quid;}
		if ($sort == '') {$sort='codeclient';}
		$recherche='
			SELECT * FROM client 
			'.$quid.'
			 ORDER BY '.$sort.'
			 LIMIT '.$skip.', 35
		';
		$_SESSION['clientquid'] = $quid;
		$_SESSION['clientquod'] = $quod;
		$_SESSION['clientsort'] = $sort;

?>
			<h1 align="center">S&eacute;lection d'un Client</h1>
			<b>Votre Recherche <br><?php echo $_SESSION['clientquod']?></b><br>
			<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$did.'&sel=client';?>">Retour &agrave; la recherche</a><br>
			<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Code Client</th>
					<th class="<?php echo $classe; ?>">Client</th>
					<th class="<?php echo $classe; ?>">CP</th>
					<th class="<?php echo $classe; ?>">Ville</th>
					<th class="<?php echo $classe; ?>">Selection</th>
				</tr>
		<?php
		$client = new db();
		$client->inline("$recherche;");
		
		while ($row = mysql_fetch_array($client->result)) {
		?>
				<tr>
					<td class="<?php echo $classe; ?>"><?php echo $row['codeclient'] ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['societe']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['cp']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['ville']; ?></td>
					<td class="<?php echo $classe; ?>">
						<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$did.'&sel=client';?>" method="post">
							<input type="hidden" name="etape" value="listeofficers"> 
							<input type="hidden" name="idclient" value="<?php echo $row['idclient'] ;?>"> 
							<input type="hidden" name="societe" value="<?php echo $row['societe'] ;?>"> 
							<input type="submit" name="Selectionner" value="Selectionner"> 
						</form>
					</td>
				</tr>
		<?php } ?>
			</table>
<?php 
		break;
		### Troisième étape : Afficher les Officers du Client
		case "listeofficers": 
?>
				<h1 align="center">Selection d'un agent pour : <br><?php echo $_POST['societe']; ?></h1>
				<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
					<tr>
						<th class="<?php echo $classe; ?>">Nom</th>
						<th class="<?php echo $classe; ?>">Langue</th>
						<th class="<?php echo $classe; ?>">Selection</th>
					</tr>
			<?php
			$idclient = $_POST['idclient'];
			$client = new db();
			$client->inline("SELECT * FROM `cofficer` WHERE `idclient`='$idclient'");
			
			while ($row = mysql_fetch_array($client->result)) { 
			?>
					<tr>
						<td class="<?php echo $classe; ?>"><?php echo $row['qualite'].' '.$row['onom'].' '.$row['oprenom']  ?></td>
						<td class="<?php echo $classe; ?>"><?php echo $row['langue']; ?></td>
						<td class="<?php echo $classe; ?>">
							<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=client';?>" method="post">
								<input type="hidden" name="idmerch" value="<?php echo $did ;?>"> 
								<input type="hidden" name="idclient" value="<?php echo $row['idclient'] ;?>"> 
								<input type="hidden" name="idcofficer" value="<?php echo $row['idcofficer'] ;?>"> 
								<input type="submit" name="Selectionner" value="Selectionner">
							</form>
						</td>
					</tr>
			<?php } ?>
				</table>
<?php
		break;
		### Première étape : formulaire de recherche de Clients
		default: 

		$_SESSION['clientquid'] = '';
		$_SESSION['clientquod'] = '';
		$_SESSION['clientsort'] = '';
?>
			<h1 align="center">Recherche des Clients</h1>
			<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$did.'&sel=client';?>" method="post">
				<input type="hidden" name="etape" value="listeclient"> 
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td>
							code client
						</td>
						<td>
							<input type="text" size="20" name="codeclient" value="">
						</td>
					</tr>

					<tr>
						<td>
							Soci&eacute;t&eacute;
						</td>
						<td>
							<input type="text" size="20" name="societe" value="">
						</td>
					</tr>
					<tr>
						<td>
							CP
						</td>
						<td>
							<input type="text" size="20" name="cp" value="">
						</td>
					</tr>
					<tr>
						<td>
							ville
						</td>
						<td>
							<input type="text" size="20" name="ville" value="">
						</td>
					</tr>
				</table>
				<input type="submit" name="Rechercher" value="Rechercher"> 
			</form>
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="40%">
				<tr bgcolor="#FF6699">
					<td class="<?php echo $classe; ?>" align="center"> 
							<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=client';?>" method="post">
								<input type="hidden" name="idmerch" value="<?php echo $did ;?>"> 
								<input type="hidden" name="idclient" value=""> 
								<input type="hidden" name="idcofficer" value=""> 
						<input type="submit" name="Selectionner" value="Remove CLIENT from merch"> 
						</form>
					</td>
				</tr>
			</table>
<?php
	}

}
?>
</div>