<?php if (($_GET['sel'] == 'people') OR ($_GET['sel'] == 'lieu2')) { echo '<div id="miniinfozone">';}

else { echo '<div id="centerzone">';}

$classe = 'standard' ;

switch ($_GET['sel']) {

################# Sélection de l'Agent ######################################
	case "agent": 
	
	switch ($_POST['etape']) {
		### Deuxième étape : Afficher la liste des Agents correspondant a la recherche
		case "listeagent": 

?>
			<h1 align="center">S&eacute;lection des Agents</h1>
			<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$idvipjob.'&sel=agent';?>">Retour &agrave; la recherche</a><br>
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
							<input type="hidden" name="idvipjob" value="<?php echo $_GET['idvipjob'] ;?>"> 
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
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$_GET['idvipjob'].'&sel=agent';?>" method="post">
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

################# Sélection du Lieu POUR JOB ######################################
	case "lieu": 
	
	switch ($_GET['etape']) {
		### Deuxième étape : Afficher la liste des lieux correspondant a la recherche 
		case "listeshop": 
			switch ($_GET['action']) {
				case "skip": 

					### Deuxième étape - 2 B : Afficher la liste des lieux correspondant a la recherche après SKIP NEXT
		
					# VARIABLE skip
					if (!empty($_GET['skip'])) {
						$skip = $_GET['skip'];
					}
					else {$skip = 0;}
					$skipprev = $skip - 25;
					$skipnext = $skip + 25;
					# VARIABLE SELECT
					if (!empty($_GET['sort'])) {
						$_SESSION['lieusort'] = $_GET['sort'];
					}
					$lieusort = $_SESSION['lieusort'];
					$lieuquid = $_SESSION['lieuquid'];
					$recherche = '
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
						$skip = $_GET['skip'];
					}
					else {$skip=0;}
					$skipprev = $skip - 25;
					$skipnext = $skip + 25;
					# VARIABLE SELECT
					
					$quid = '';
					$quod = '';
					
					if (!empty($_POST['codeshop'])) {
						$quid .= "codeshop LIKE '%".addslashes($_POST['codeshop'])."%'";
						$quod .= "codeshop = ".$_POST['codeshop'];
					}
					
					if (!empty($_POST['societe'])) {
						if (!empty($quid)) $quid .= " AND ";
							$quid .= "societe LIKE '%".addslashes($_POST['societe'])."%'";
						$quod .= "societe = ".$_POST['societe'];
					}
					if (!empty($_POST['snom'])) {
						if (!empty($quid)) $quid .= " AND ";
							$quid .= "snom LIKE '%".addslashes($_POST['snom'])."%'";
						$quod .= "nom = ".$_POST['snom'];
					}

					if (!empty($quid)) {$quid = 'WHERE '.$quid;}
					if ($sort == '') {$sort = 'codeshop';}
					
					$sql = '
						SELECT * FROM `shop`
						'.$quid.'
						ORDER BY '.$sort.'
						LIMIT '.$skip.', 25';

					# Recherche des résultats
					$client = new db();
					$client->inline($sql);
					$_SESSION['lieuquid'] = $quid;
					$_SESSION['lieusort'] = $sort;
				}
				### Deuxième étape - 2 A Et 2B COMMUN : Afficher la liste des lieux 
				?>
					<fieldset>
						<legend>
							R&eacute;sultats de recherche Job : <?php echo $quod?>
						</legend>
						<?php $classe = "standard" ?>
						<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$idvipjob.'&sel=lieu';?>">Retour &agrave; la recherche</a><br>
						<br>
						<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
							<tr>
								<td>&nbsp;</td>
								<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&etape=listeshop&action=skip&idvipjob='.$_GET['idvipjob'].'&sel=lieu&skip='.$skipprev;?>">Previous</a></td>
								<td class="<?php echo $classe; ?>"><?php echo $skip;?></td>
								<td class="<?php echo $classe; ?>"><?php echo $skip+24;?></td>
								<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&etape=listeshop&action=skip&idvipjob='.$_GET['idvipjob'].'&sel=lieu&skip='.$skipnext;?>">Next</a></td>
							</tr>
							<tr>
								<th class="<?php echo $classe; ?>">&nbsp;</th>
								<th class="<?php echo $classe; ?>">Code LIEUX</th>
								<th class="<?php echo $classe; ?>">Client</th>
								<th class="<?php echo $classe; ?>">Adresse</th>
								<th class="<?php echo $classe; ?>">Nom</th>
								<th class="<?php echo $classe; ?>"></th>
							</tr>
					<?php
					while ($row = mysql_fetch_array($client->result)) { 
					?>
							<tr>
								<td><?php if(!empty($row['glat1']) && !empty($row['glong1'])) echo '<img src="'.STATIK.'illus/geoloc.png">';?></td>
								<td class="<?php echo $classe; ?>"><?php echo $row['codeshop'] ?></td>
								<td class="<?php echo $classe; ?>"><?php echo $row['societe']; ?></td>
								<td class="<?php echo $classe; ?>"><?php echo $row['adresse'].' '.$row['cp'].' '.$row['ville'].' '.$row['pays'] ; ?></td>
								<td class="<?php echo $classe; ?>"><?php echo $row['snom']; ?></td>
								<td class="<?php echo $classe; ?>">
										<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=lieu';?>" method="post">
											<input type="hidden" name="idvipjob" value="<?php echo $_GET['idvipjob'] ;?>"> 
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
							<input type="hidden" name="idvipjob" value="<?php echo $_GET['idvipjob'] ;?>"> 
							<input type="hidden" name="idshop" value=""> 
						<input type="submit" name="Selectionner" value="Remove LIEU from JOB"> 
						</form>
					</td>
				</tr>
			</table>
					</fieldset>
				<?php echo $quid;?><br>	
				<?php echo $count;?>	Results
<?php
		break;
		### Première étape : formulaire de recherche de Lieu
		default: 
			$_SESSION['lieuquid'] = $quid;
			$_SESSION['lieusort'] = $sort;
		# VIDER LA SESSION
	?>

		<h2 align="center">Recherche des Lieux JOB</h2>
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&etape=listeshop&idvipjob='.$_GET['idvipjob'].'&sel=lieu';?>" method="post">
			<input type="hidden" name="etape" value="listeshop"> 
		<fieldset>
			<legend>
				Infos de recherche
			</legend>
			<label for="codeshop">Code</label><input type="text" name="codeshop" id="codeshop"><br>
			<label for="societe">Soci&eacute;t&eacute;</label><input type="text" name="societe" id="societe"><br>
			<label for="snom">Contact</label><input type="text" name="snom" id="snom"> 
		</fieldset>
		<div align="center"><input type="submit" name="Rechercher" value="Rechercher"></div>
		</form>
			<br>
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="40%">
				<tr bgcolor="#FF6699">
					<td class="<?php echo $classe; ?>" align="center"> 
						<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=lieu';?>" method="post">
							<input type="hidden" name="idvipjob" value="<?php echo $_GET['idvipjob'] ;?>"> 
							<input type="hidden" name="idshop" value=""> 
						<input type="submit" name="Selectionner" value="Remove LIEU from JOB"> 
						</form>
					</td>
				</tr>
			</table>
<?php
	}
	break;



################# Sélection du Lieu POUR MISSION ######################################
	case "lieu2": 
	
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
					$skipprev = $skip - 25;
					$skipnext = $skip + 25;
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
					$client->inline($recherche);
				break;
				### Deuxième étape - 2 A : Afficher la liste des lieux correspondant a la recherche SANS SKIP NEXT
				default: 
					# VARIABLE skip
					if (!empty($_GET['skip'])) {
						$skip=$_GET['skip'];
					}
					else {$skip=0;}
					$skipprev = $skip - 25;
					$skipnext = $skip + 25;
					# VARIABLE SELECT
					if (!empty($_POST['codeshop'])) {
						$quid="codeshop LIKE '%".addslashes($_POST['codeshop'])."%'";
						$quod="codeshop = ".$_POST['codeshop'];
					}
					if (!empty($_POST['societe'])) {
						if (!empty($quid)) $quid .= " AND ";
							$quid .= "societe LIKE '%".addslashes($_POST['societe'])."%'";
						$quod .= "societe = ".$_POST['societe'];
					}
					if (!empty($_POST['snom'])) {
						if (!empty($quid)) $quid .= " AND ";
							$quid .= "snom LIKE '%".addslashes($_POST['snom'])."%'";
						$quod .= "nom = ".$_POST['snom'];
					}

					if (!empty($quid)) {$quid = 'WHERE '.$quid;}
					if ($sort == '') {$sort = 'codeshop';}
					$recherche='
						SELECT * FROM `shop`
						'.$quid.'
						 ORDER BY '.$sort.'
						 LIMIT '.$skip.', 25
					';

					# Recherche des résultats
					$client = new db();
					$client->inline($recherche);
					$_SESSION['lieuquid'] = $quid;
					$_SESSION['lieusort'] = $sort;
				}
				### Deuxième étape - 2 A Et 2B COMMUN : Afficher la liste des lieux 
				?>
					<fieldset>
						<legend>
							R&eacute;sultats de recherche mission <?php echo $quod?>
						</legend>
						<?php $classe = "standard" ?>
						<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$idvipjob.'&idvip='.$idvip.'&etat='.$_GET['etat'].'&sel=lieu';?>">Retour &agrave; la recherche</a><br>
						<br>
						<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
							<tr>
								<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$idvipjob.'&idvip='.$idvip.'&etat='.$_GET['etat'].'&etape=listeshop&action=skip&idvipjob='.$_GET['idvipjob'].'&sel=lieu&skip='.$skipprev;?>">Previous</a></td>
								<td class="<?php echo $classe; ?>"><?php echo $skip;?></td>
								<td class="<?php echo $classe; ?>"><?php echo $skip+24;?></td>
								<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$idvipjob.'&idvip='.$idvip.'&etat='.$_GET['etat'].'&etape=listeshop&action=skip&idvipjob='.$_GET['idvipjob'].'&sel=lieu&skip='.$skipnext;?>">Next</a></td>
							</tr>
							<tr>
								<th class="<?php echo $classe; ?>">Code LIEUX</th>
								<th class="<?php echo $classe; ?>">Client</th>
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
								<td class="<?php echo $classe; ?>"><?php echo $row['adresse'].' '.$row['cp'].' '.$row['ville'].' '.$row['pays'] ; ?></td>
								<td class="<?php echo $classe; ?>"><?php echo $row['snom']; ?></td>
								<td class="<?php echo $classe; ?>">
										<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=lieu2&etat='.$_GET['etat'].'';?>" method="post">
										<input type="hidden" name="idvipjob" value="<?php echo $idvipjob ;?>"> 
										<input type="hidden" name="idvip" value="<?php echo $idvip ;?>"> 
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
						<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=lieu2&etat='.$_GET['etat'].'';?>" method="post">
						<input type="hidden" name="idvipjob" value="<?php echo $idvipjob ;?>"> 
						<input type="hidden" name="idvip" value="<?php echo $idvip ;?>"> 
						<input type="hidden" name="idshop" value=""> 
						<input type="submit" name="Selectionner" value="Remove LIEU from Mission"> 
						</form>
					</td>
				</tr>
			</table>

					</fieldset>
				<?php echo $quid;?><br>	
				<?php echo $count;?>	Results
<?php
		break;
		### Première étape : formulaire de recherche de Lieu
		default: 
			$_SESSION['lieuquid'] = $quid;
			$_SESSION['lieusort'] = $sort;
		# VIDER LA SESSION
	?>

		<h2 align="center">Recherche des Lieux mission</h2>
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&etape=listeshop&idvipjob='.$idvipjob.'&idvip='.$idvip.'&etat='.$_GET['etat'].'&sel=lieu';?>" method="post">
			<input type="hidden" name="etape" value="listeshop"> 
		<fieldset>
			<legend>
				Infos de recherche
			</legend>
			<label for="codeshop">Code</label><input type="text" name="codeshop" id="codeshop"><br>
			<label for="societe">Soci&eacute;t&eacute;</label><input type="text" name="societe" id="societe"><br>
			<label for="snom">Contact</label><input type="text" name="snom" id="snom"> 
		</fieldset>
		<div align="center"><input type="submit" name="Rechercher" value="Rechercher"></div>
		</form>
			<br>
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="40%">
				<tr bgcolor="#FF6699">
					<td class="<?php echo $classe; ?>" align="center"> 
						<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=lieu2&etat='.$_GET['etat'].'';?>" method="post">
						<input type="hidden" name="idvipjob" value="<?php echo $idvipjob ;?>"> 
						<input type="hidden" name="idvip" value="<?php echo $idvip ;?>"> 
						<input type="hidden" name="idshop" value=""> 
						<input type="submit" name="Selectionner" value="Remove LIEU from Mission"> 
						</form>
					</td>
				</tr>
			</table>

<?php
	}
	break;





################# Sélection du People ######################################
	case "people": 
	
	switch ($_GET['etape']) {
		### Deuxième étape : Afficher la liste des People correspondant à la recherche
		case "listepeople": 
		
				### Date de la mission VIP pour disponibilités
					$sql = "SELECT v.vipdate, s.glat, s.glong, v.idshop FROM vipmission v LEFT JOIN shop s ON v.idshop = s.idshop WHERE v.idvip = $idvip";

					$datevip = new db();
					$datevip->inline($sql);

					$datev = mysql_fetch_array($datevip->result);
					$mm = explode("-", $datev['vipdate']);
					
					$dd = 'd'.str_repeat('0', 2 - strlen($mm[2])).$mm[2];
				#/## Date de la mission VIP pour disponibilités


					if (($datev['glat1'] == 0) and ($datev['glong1'] == 0)) {
					
					
					}

					# affichage avec skip
					if (!empty($_GET['skip']) or ($_GET['contact'] == 'yes') or ($_GET['contact'] == 'modif')) {
						if ($_GET['skip'] == 'start') {
							$skip = 0;
						} else {
							$skip = $_GET['skip'];
						}
					} else {
					# Création de la recherche
						# recherche shop
						if(!empty($_POST['rayon'])) {
						
							include('../classes/geocalc.php');

							$rayon = $_POST['rayon'];	// Rayon en km
							
							$coeff = array(
								 '5' => '1.40', 
								'10' => '1.35', 
								'20' => '1.42', 
								'30' => '1.34', 
								'40' => '1.30', 
								'50' => '1.42', 
								'75' => '1.29', 
							   '100' => '1.28'); 
							##### Chercher le bon coefficient dans l'array #####
							foreach($coeff as $k => $v) {
								if($rayon >= $k) {
									$x = $v;
								}
							}
							
							$rayon = $rayon / $x;

							$geo = new Geocalc;
							
							$dAddLat = $geo->getLatPerKm() * $rayon;
							$dAddLon = $geo->getLonPerKmAtLat($datev['glat1']) * $rayon;
							
							$dNorthBounds = $datev['glat1'] + $dAddLat;
							$dSouthBounds = $datev['glat1'] - $dAddLat;
							$dWestBounds = $datev['glong1'] - $dAddLon;
							$dEastBounds = $datev['glong1'] + $dAddLon;
							$quid .= " p.glat1 > ".$dSouthBounds." AND p.glat1 < ".$dNorthBounds." AND
									  p.glong1 > ".$dWestBounds." AND p.glong1 < ".$dEastBounds;
						}
						
						if (!empty($_POST['pnom'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.pnom LIKE '%".addslashes($_POST['pnom'])."%'";
						}
						if (!empty($_POST['pprenom'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.pprenom LIKE '%".addslashes($_POST['pprenom'])."%'";
						}
						if (!empty($_POST['codepeople'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.codepeople = ".$_POST['codepeople'];
						}
						if (!empty($_POST['notegenerale'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.notegenerale LIKE '%".addslashes($_POST['notegenerale'])."%'";
						}
	
						if ((!empty($_POST['sexe'])) AND ($_POST['sexe'] != 'x')) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.sexe LIKE '%".$_POST['sexe']."%'";
						}
						if (!empty($_POST['lfr'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.lfr >= ".$_POST['lfr'];
						}
						if (!empty($_POST['lnl'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.lnl >= ".$_POST['lnl'];
						}
						if (!empty($_POST['len'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.len >= ".$_POST['len'];
						}
	
						if (!empty($_POST['ldu'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.ldu >= ".$_POST['ldu'];
						}
						if (!empty($_POST['lit'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.lit >= ".$_POST['lit'];
						}
						if (!empty($_POST['lsp'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.lsp >= ".$_POST['lsp'];
						}
	
	
						if (!empty($_POST['beaute'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.beaute >= ".$_POST['beaute'];
						}
	
						if (!empty($_POST['charme'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.charme >= ".$_POST['charme'];
						}
	
						if (!empty($_POST['dynamisme'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.dynamisme >= ".$_POST['dynamisme'];
						}
	
	
						if (!empty($_POST['cp1a'])) {
							if (empty($_POST['cp1b'])) { $_POST['cp1b'] = $_POST['cp1a']; }
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "((p.cp1 >= ".$_POST['cp1a']." AND p.cp1 <= ".$_POST['cp1b'].") OR (p.cp2 >= ".$_POST['cp1a']." AND p.cp2 <= ".$_POST['cp1b']."))";
						}
						
						if ($_POST['ville1'] != '') {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "(";
							$quid .= "p.ville1 LIKE '%".addslashes($_POST['ville1'])."%'";
							$quid .= " OR ";
							$quid .= "p.ville2 LIKE '%".addslashes($_POST['ville2'])."%'";
							$quid .= ")";
						}
					if (!empty($_POST['categorie'])) {
						$searchstr = ($_POST['categorie'][0]=='1')?'1':'_';
$searchstr.=($_POST['categorie'][1]=='1')?'1':'_';
$searchstr.=($_POST['categorie'][2]=='1')?'1':'_';
						if (!empty($quid)) $quid .= " AND ";
						$quid = $quid."p.categorie LIKE '".$searchstr."'";
					}
if (!empty($_POST['err'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.err LIKE '".$_POST['err']."'";
						}

						## Taille ### ajouté le 2005-08-17 à la demande de Vincent ############ 
						if (!empty($_POST['taillea'])) {
							if ($_POST['tailleb'] == '') { $_POST['tailleb'] = $_POST['taillea']; }
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "((p.taille >= '".$_POST['taillea']."' AND p.taille <= '".$_POST['tailleb']."') OR (p.cp2 >= '".$_POST['taillea']."' AND p.cp2 <= '".$_POST['tailleb']."'))";
						}
						#/ ###### ajouté le 2005-08-17 à la demande de Vincent ############ 

						#### Couleur des cheveux  ###
						if (!empty($_POST['ccheveux'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.ccheveux LIKE '".$_POST['ccheveux']."'";
						}
						#### Physionomie  ###
						if (!empty($_POST['physio'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.physio LIKE '".$_POST['physio']."'";
						}
						#### Permis de conduire  ###
						if (!empty($_POST['permis'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.permis LIKE '".$_POST['permis']."'";
						}
						#### Voiture  ###
						if (!empty($_POST['voiture'])) {
							if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.voiture LIKE '".$_POST['voiture']."'";
						}



						## Pas les OUT
						if (!empty($quid)) $quid .= " AND ";
							$quid .= "p.isout NOT LIKE '%out%'";

						$skip = 0;
						
						## Assemblage de la recherche
						if (!empty($quid)) { $quid='WHERE '.$quid; }
						
						$recherche = '
							SELECT p.idpeople, p.pnom, p.pprenom, p.sexe, p.lfr, p.lnl, p.len, 
							p.glat1, p.glong1, p.cp1, p.ville1, p.cp2, p.ville2, p.gsm, p.err, p.codepeople, 
							p.province, 
							d.'.$dd.' AS disp
							FROM people p
							LEFT JOIN vipmission m  ON p.idpeople = m.idpeople 
							LEFT JOIN disponib d  ON p.idpeople = d.idboy AND d.annee = \''.$mm[0].'\' AND d.mois = \''.$mm[1].'\' 
							'.$quid.'
							GROUP BY p.idpeople
						';

						$_SESSION['peoplevipsearch'] = $recherche;
						$_SESSION['peoplevipquid'] = $quid;
					}
					
					if (empty($sort)) { $sort = 'disp DESC, idpeople DESC'; }
					
					if ($skip > 25) { $skipprev = $skip - 25; } else { $skipprev = 'start'; }
					$skipnext = $skip + 25;

				### Deuxième étape - 2 A Et 2B COMMUN : Afficher la liste des people 
					$classe = standard;

				### recherchetot 
					$peopletot = new db();
					$peopletot->inline('SELECT idpeople FROM people p '.$_SESSION['peoplevipquid']);

				### RECHERCHE Info de base de Mission
					$sql = "SELECT
					m.vipactivite, m.vipdate, m.vipin, m.vipout,
					s.societe, s.adresse, s.cp AS shopcp, s.ville AS shopville 
					FROM vipmission m 
					LEFT JOIN shop s ON m.idshop = s.idshop 
					WHERE m.idvip = ".$idvip;
					
					$detailmission = new db();
					$detailmission->inline($sql);
					$infosmission = mysql_fetch_array($detailmission->result) ;
				#/## RECHERCHE Info de base de Mission
?>
		<fieldset>
			<legend>
				Recherche d'un People : <?php echo $_SESSION['peoplevipquod'].' &nbsp; &nbsp; &nbsp; &nbsp;'; $FoundCount = mysql_num_rows($peopletot->result); echo '('.$FoundCount.' Results)'; ?>
			</legend>
			<table border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<td class="<?php echo $classe; ?>">
						<?php echo "(".$infosmission['vipactivite'].") &nbsp; ".fdate($infosmission['vipdate'])." &nbsp; &nbsp;".ftime($infosmission['vipin'])."-".ftime($infosmission['vipout']); ?>
					</td>
					<td class="<?php echo $classe; ?>">
						Lieu : <?php echo $infosmission['societe']." ".$infosmission['adresse']." ".$infosmission['shopcp']." ".$infosmission['shopville']; ?>
					</td>
					<td class="<?php echo $classe; ?>" align="right">
						<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$idvipjob.'&idvip='.$idvip.'&sel=people';?>"> &gt;&gt; Retour &agrave; la recherche &lt;&lt;</a><br>
					</td>
				</tr>
			</table>
			<?php 
			if ($_GET['contact'] == 'yes') { 
				### RECHERCHE Contact people
					$sqlcontact1 = "SELECT
					c.notecontact, c.etatcontact, 
					p.pprenom, p.pnom, p.banque, p.codepeople, p.nville, p.ndate, p.lbureau, p.idpeople, p.sexe, p.lfr, p.lnl, p.len, p.gsm, 
					p.adresse1, p.num1, p.bte1, p.cp1, p.ville1, 
					p.adresse2, p.num2, p.bte2, p.cp2, p.ville2,
					a.nom AS agentnom, a.prenom AS agentprenom, a.atel, a.agsm 
					FROM jobcontact c 
					LEFT JOIN people p ON c.idpeople = p.idpeople 
					LEFT JOIN agent a ON c.idagent = a.idagent 
					WHERE c.idvipjob = ".$idvipjob." AND c.idpeople = ".$_GET['idpeople'];

					$detailcontact1 = new db();
					$detailcontact1->inline($sqlcontact1);
					$rowcontact1 = mysql_fetch_array($detailcontact1->result) ;
				#/## RECHERCHE Contact people
			?>
			<form action="<?php echo $_SERVER['PHP_SELF'].'?idvipjob='.$idvipjob.'&idvip='.$idvip.'&act=select&sel=people&etape=listepeople&skip='.$skip.'&contact=modif&idpeople='.$rowcontact1['idpeople']; ?>" method="post">
			<input type="hidden" name="idvipjob" value="<?php echo $idvipjob ;?>">
			<input type="hidden" name="idvip" value="<?php echo $idvip ;?>">
			<input type="hidden" name="idpeople" value="<?php echo $rowcontact1['idpeople'] ;?>">
				<table border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
					<tr>
						<td class="<?php echo $classe; ?>">
							<?php echo $rowcontact1['codepeople'].' - '.$rowcontact1['idpeople']; ?> <?php echo $rowcontact1['pnom']; ?> <?php echo $rowcontact1['pprenom']; ?>
						</td>
						<td class="<?php echo $classe; ?>">
							<input type="radio" name="etatcontact" value="0" <?php if (($rowcontact1['etatcontact'] == '0') or ($rowcontact['etatcontact'] == '')) { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-grey.gif" border="0" alt="phone-grey.gif" width="15" height="15">'; ?> N&eacute;ant
							<input type="radio" name="etatcontact" value="10" <?php if ($rowcontact1['etatcontact'] == '10') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-blue.gif" border="0" alt="phone-blue.gif" width="15" height="15">'; ?> Pas de r&eacute;p
							<input type="radio" name="etatcontact" value="20" <?php if ($rowcontact1['etatcontact'] == '20') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-red.gif" border="0" alt="phone-red.gif" width="15" height="15">'; ?> Non dispo
							<input type="radio" name="etatcontact" value="30" <?php if ($rowcontact1['etatcontact'] == '30') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-orange.gif" border="0" alt="phone-green.gif" width="15" height="15">'; ?> Message
							<input type="radio" name="etatcontact" value="40" <?php if ($rowcontact1['etatcontact'] == '40') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-green.gif" border="0" alt="phone-green.gif" width="15" height="15">'; ?> Ok
						</td>
						<td class="<?php echo $classe; ?>">
							Notes : <input type="text" name="notecontact" value="<?php echo $rowcontact1['notecontact']; ?>" size="25">
						</td>
						<td class="<?php echo $classe; ?>" align="right">
							<input type="submit" name="ok" value="ok">
						</td>
					</tr>
				</table>
			</form>
			<?php }

if ($skip > 0) {
	$prevlink = '<a href="'.$_SERVER['PHP_SELF'].'?&idvipjob='.$idvipjob.'&idvip='.$idvip.'&act=select&sel=people&etape=listepeople&skip='.$skipprev.'"><img src="'.STATIK.'illus/avant.gif" alt="search" width="20" height="20" border="0"></a>';
} else {
	$prevlink = '';	
}

if ($skipnext < $FoundCount) {
	$nextlink = '<a href="'.$_SERVER['PHP_SELF'].'?&idvipjob='.$idvipjob.'&idvip='.$idvip.'&act=select&sel=people&etape=listepeople&skip='.$skipnext.'"><img src="'.STATIK.'illus/apres.gif" alt="search" width="20" height="20" border="0"></a>';
} else {
	$nextlink = '';	
}
			
			
			?>
			<table class="<?php echo $classe; ?>" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<td class="<?php echo $classe; ?>"><?php echo $prevlink; ?></td>
					<td class="<?php echo $classe; ?>" align="center"><?php echo $skip;?> - <?php echo $skip+24;?></td>
					<td class="<?php echo $classe; ?>" align="right"><?php echo $nextlink; ?></td>
				</tr>
			</table>
			<table class="standard" border="0" width="100%" cellspacing="1" cellpadding="0" align="center">
				<tr>
					<th class="vip2">Code</th>
					<th class="vip2">D</th>
					<th class="vip2">Nom</th>
					<th class="vip2">Pr&eacute;nom</th>
					<th class="vip2"></th>
					<th class="vip2">Sx</th>
					<th class="vip2">Fr</th>
					<th class="vip2">NL</th>
					<th class="vip2">En</th>
					<th class="vip2">KM</th>
					<th class="vip2">CP</th>
					<th class="vip2">Ville</th>
					<th class="vip2">CP2</th>
					<th class="vip2">Ville2</th>
					<th class="vip2">GSM</th>
					<th class="vip2"></th>
				</tr>
		<?php
		# Recherche des 15 clients

		$recherche = $_SESSION['peoplevipsearch'].' ORDER BY '.$sort.' LIMIT '.$skip.', 25';

		$people = new db();
		$people->inline($recherche);
		
		include_once(NIVO."classes/geocalc.php");
		
		$geo = new GeoCalc();
		while ($row = mysql_fetch_array($people->result)) { 
				### RECHERCHE Contact people
					$sqlcontact = "SELECT
					c.notecontact, c.etatcontact 
					FROM jobcontact c 
					WHERE c.idvipjob = ".$idvipjob." AND c.idpeople = ".$row['idpeople'];
					$detailcontact = new db();
					$detailcontact->inline($sqlcontact);
					$rowcontact = mysql_fetch_array($detailcontact->result) ;
				#/## RECHERCHE Contact people

			# if ($row['disp'] != '0') {
			
			$calchh = new hh();
			$dds = explode("-", $datev['vipdate']);			


			if (date("w", strtotime($datev['vipdate'])) == 0) {
				$lastweek = date("Y-m-d", mktime(0, 0, 0, $dds[1], $dds[2] - 7, $dds[0]));

				$worktable = $calchh->hhtable($row['idpeople'], $lastweek, $datev['vipdate']);
				if (array_sum(preg_split('//',$worktable[$lastweek],-1,PREG_SPLIT_NO_EMPTY)) > 0) { $ddillu = '<img src="'.STATIK.'illus/ddim.gif" alt="dim" width="7" height="13">'; } else { $ddillu = ''; }
			} else {
				$worktable = $calchh->hhtable($row['idpeople'], $datev['vipdate'], $datev['vipdate']);
			}
			
			#echo $datev['vipdate'].'<br>';
			

	#> Changement de couleur des lignes #####>>####
	$i++;
	
	if (fmod($i, 2) == 1) {
		echo '<tr bgcolor="#9CBECA">';
	} else {
		echo '<tr bgcolor="#8CAAB5">';
	}
	#< Changement de couleur des lignes #####<<####
		?>
					<td><?php echo $row['codepeople']; ?></td>
					<td><?php echo $ddillu; if ((!empty($row['disp'])) or (array_sum(preg_split('//',$worktable[$datev['vipdate']],-1,PREG_SPLIT_NO_EMPTY)) > 0)) {echo '<img src="/data/people/dispo/dillu.php?disp='.$row['disp'].'&hhcode='.$worktable[$datev['vipdate']].'" border="1" alt="1.gif" width="8" height="11">';} ?></td>
					<td><?php echo showmax($row['pnom'], 20); ?></td>
					<td><?php echo $row['pprenom']; ?></td>
					<td>
						<a href="<?php echo NIVO.'data/people/adminpeople.php?act=show&idpeople='.$row['idpeople'];?>" target="_blank"><img src="<?php echo STATIK ?>illus/icon_profile.gif" border="0" width="15" height="12"></a>

						<a href="<?php echo $_SERVER['PHP_SELF'].'?&idvipjob='.$idvipjob.'&idvip='.$idvip.'&act=select&sel=people&etape=listepeople&skip='.$skip.'&contact=yes&idpeople='.$row['idpeople']; ?>">
							<?php if (($rowcontact['etatcontact'] == '0') or ($rowcontact['etatcontact'] == '')) {echo '<img src="'.STATIK.'illus/phone-grey.gif" border="0" alt="phone-grey.gif" width="15" height="15">';} ?>
							<?php if ($rowcontact['etatcontact'] == '10') {echo '<img src="'.STATIK.'illus/phone-blue.gif" border="0" alt="phone-green.gif" width="15" height="15">';} ?>
							<?php if ($rowcontact['etatcontact'] == '20') {echo '<img src="'.STATIK.'illus/phone-red.gif" border="0" alt="phone-green.gif" width="15" height="15">';} ?>
							<?php if ($rowcontact['etatcontact'] == '30') {echo '<img src="'.STATIK.'illus/phone-orange.gif" border="0" alt="phone-green.gif" width="15" height="15">';} ?>
							<?php if ($rowcontact['etatcontact'] == '40') {echo '<img src="'.STATIK.'illus/phone-green.gif" border="0" alt="phone-green.gif" width="15" height="15">';} ?>
						</a>

					</td>
					<td><?php echo $row['sexe']; ?></td>
					<td><?php echo $row['lfr']; ?></td>
					<td><?php echo $row['lnl']; ?></td>
					<td><?php echo $row['len']; ?></td>
					<td>
<?php 
						if($row['glat1'] != 0 && $row['glong1'] != 0) {						
							$coeff = array(5 => 1.40, 10 => 1.35, 20 => 1.42, 30 => 1.34, 40 => 1.30, 50 => 1.42, 75 => 1.29, 100 => 1.28); 
							$dist = round($geo->EllipsoidDistance($datev['glat1'], $datev['glong1'], $row['glat1'], $row['glong1']));
							##### Chercher le bon coefficient dans l'array #####
							foreach($coeff as $k => $v) {
								if($dist >= $k) {
									$x = $v;
								}
							}
							$dist = $dist * $x;
							echo round($dist);
						}
						else {
							echo '-';
						}
?>
					</td>
					<td><?php echo $row['cp1']; ?></td>
					<td><?php echo showmax($row['ville1'], 12); ?></td>
					<td><?php echo $row['cp2']; ?></td>
					<td><?php echo showmax($row['ville2'], 12); ?></td>
					<td><?php echo $row['gsm']; ?></td>
					<td><form action="?act=modif&mod=people&etat=1" method="post"><input type="hidden" name="idvipjob" value="<?php echo $idvipjob ;?>"><input type="hidden" name="idvip" value="<?php echo $idvip ;?>"><input type="hidden" name="idpeople" value="<?php echo $row['idpeople'] ;?>"><input type="submit" name="Selectionner" value="Selectionner"></form></td>
				</tr>
		<?php 
			$count++;
			# 	}
			}
		?>
			</table>
			<br>
		</fieldset>

<br>	
<?php echo $count;?>	Results

		<fieldset>
			<legend>Legende</legend>
			<table>
				<tr>
					<td><img src="<?php echo STATIK ?>illus/ddim.gif" alt="dim" width="7" height="13"></td>
					<td>La personne a d&eacute;j&agrave; travaill&eacute; le dimanche pr&eacute;cendent</td>
				</tr>
				<tr>
					<td><img src="/data/people/dispo/dillu.php?disp=&hhcode=000001111001110000000000" border="1" alt="1.gif" width="8" height="11"></td>
					<td>Travaille d&eacute;ja le matin et l'apr&egrave;s midi</td>
				</tr>
				<tr>
					<td><img src="/data/people/dispo/dillu.php?disp=6" border="1" alt="1.gif" width="8" height="11"></td>
					<td>Est disponible l'apr&egrave;s midi et en soir&eacute;e</td>
				</tr>
				<tr>
					<td><img src="/data/people/dispo/dillu.php?disp=0" border="1" alt="1.gif" width="8" height="11"></td>
					<td>N'est pas disponible</td>
				</tr>
			</table>
		</fieldset>
<?php
		break;
		### Première étape : formulaire de recherche de People
		default: 
#	echo $idvip;
#	echo $idvipjob;
			$_SESSION['peoplevipskip'] = $skip;
			$_SESSION['peoplevipquid'] = $quid;
			$_SESSION['peoplevipquod'] = $quod;
			$_SESSION['peoplevipsort'] = $sort;
			$_SESSION['peoplevipsearch'] = $recherche1;
			$skip = 0;
	?>
		<fieldset>
			<legend>
				Infos de recherche
			</legend>

		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvip='.$idvip.'&etape=listepeople&idvipjob='.$_GET['idvipjob'].'&sel=people';?>" method="post">

			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
				<tr>
					<td valign="top">
						<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
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
									code (registre) 
								</td>
								<td>
									<input type="text" size="5" name="codepeople" value="">
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
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td>
									Be
								</td>
								<td>
										<input type="radio" name="beaute" value="1"> 1
										<input type="radio" name="beaute" value="2"> 2
										<input type="radio" name="beaute" value="3"> 3
										<input type="radio" name="beaute" value="4"> 4
								</td>
							</tr>
							<tr>
								<td>
									Ch
								</td>
								<td>
										<input type="radio" name="charme" value="1"> 1
										<input type="radio" name="charme" value="2"> 2
										<input type="radio" name="charme" value="3"> 3
										<input type="radio" name="charme" value="4"> 4
								</td>
							</tr>
							<tr>
								<td>
									Dy
								</td>
								<td>
										<input type="radio" name="dynamisme" value="1"> 1
										<input type="radio" name="dynamisme" value="2"> 2
										<input type="radio" name="dynamisme" value="3"> 3
										<input type="radio" name="dynamisme" value="4"> 4
								</td>
							</tr>
							<tr>
								<td>
									Rayon (km)
								</td>
								<td><input type="text" name="rayon" size="5"></td>
							</tr>
						</table>
					</td>
					<td valign="top">
						<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
							<tr>
								<td colspan="2">&nbsp;</td>
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
									En
								</td>
								<td>
										<input type="radio" name="len" value="0"> 0
										<input type="radio" name="len" value="1"> 1
										<input type="radio" name="len" value="2"> 2
										<input type="radio" name="len" value="3"> 3
										<input type="radio" name="len" value="4"> 4
								</td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td>
									Du
								</td>
								<td>
										<input type="radio" name="ldu" value="0"> 0
										<input type="radio" name="ldu" value="1"> 1
										<input type="radio" name="ldu" value="2"> 2
										<input type="radio" name="ldu" value="3"> 3
										<input type="radio" name="ldu" value="4"> 4
								</td>
							</tr>
							<tr>
								<td>
									It
								</td>
								<td>
										<input type="radio" name="lit" value="0"> 0
										<input type="radio" name="lit" value="1"> 1
										<input type="radio" name="lit" value="2"> 2
										<input type="radio" name="lit" value="3"> 3
										<input type="radio" name="lit" value="4"> 4
								</td>
							</tr>
							<tr>
								<td>
									Sp
								</td>
								<td>
										<input type="radio" name="lsp" value="0"> 0
										<input type="radio" name="lsp" value="1"> 1
										<input type="radio" name="lsp" value="2"> 2
										<input type="radio" name="lsp" value="3"> 3
										<input type="radio" name="lsp" value="4"> 4
								</td>
							</tr>
						</table>
					</td>
					<td valign="top">
						<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
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
								<td colspan="2">&nbsp;</td>
							</tr>
							<tr>
								<td>
									Note
								</td>
								<td>
									<input type="text" size="20" name="notegenerale" value="">
								</td>
							</tr>
							<tr>
								<td>
									Secteur
								</td>
								<td>
									<input type="checkbox" name="categorie[0]" value="1"> Anim &nbsp; 
									<input type="checkbox" name="categorie[1]" value="1"> Merch &nbsp; 
									<input type="checkbox" name="categorie[2]" value="1"> Hotes &nbsp; 
								</td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									Taille
								</td>
								<td>
									<input type="text" size="3" name="taillea" value=""> cm &nbsp; &agrave; &nbsp;
									<input type="text" size="3" name="tailleb" value=""> cm
								</td>
							</tr>
							<tr>
								<td colspan="2">&nbsp;
								</td>
							</tr>
							<tr>
								<td>
									Cheveux
								</td>
								<td>
							<?php 
							$coulcheveux = array(
								'blond' => 'Blond',
								'brun' => 'Brun',
								'noir' => 'Noir',
								'chatain' => 'Chatain',
								'roux' => 'Roux'
							);

								echo '<select name="ccheveux"><option label="" selected></option>';
				
								foreach ($coulcheveux as $key => $value) {
									echo '<option value="'.$key.'"';	
									echo '>'.$value.'</option>';
								}
								
								echo '</select>';
							?>
								</td>
							</tr>
							<tr>
								<td>
									Physionomie
								</td>
								<td>
							<?php 
							
							$physios = array(
								'occidental' => 'Occidental',
								'slave' => 'Slave',
								'asiatique' => 'Asiatique',
								'orientale' => 'Orientale',
								'black' => 'Black',
								'nordafricain' => 'Nord-africain',
								'hispanique' => 'Hispanique',
								'mediterraneen' => 'M&eacute;dit&eacute;rran&eacute;en'
							);

							
								echo '<select name="physio"><option label="" selected></option>';
				
								foreach ($physios as $key => $value) {
									echo '<option value="'.$key.'"';	
									echo '>'.$value.'</option>';
								}
								
								echo '</select>';
							?>
								</td>
							</tr>
							<tr>
								<td>
									Permis
								</td>
								<td>
									<input type="radio" name="permis" value="non"> Non
									<input type="radio" name="permis" value="A"> A
									<input type="radio" name="permis" value="B"> B
									<input type="radio" name="permis" value="C"> C
									<input type="radio" name="permis" value="D"> D
									<input type="radio" name="permis" value="E"> E
								</td>
							</tr>
							<tr>
								<td>
									Voiture
								</td>
								<td>
									
									<input type="radio" name="voiture" value="oui"> Oui
									<input type="radio" name="voiture" value="non"> Non
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
				<tr>
					<td align="left" width="50%">
						<input type="reset" name="reset" value="Reset">
					</td>
					<td>
						<input type="submit" name="Rechercher" value="Rechercher">
					</td>
				</tr>
			</table>
		</form>
</fieldset>
<?php
	}
	break;
################# Sélection du Client ######################################
	case "client": 
	default: 
		
	switch ($_POST['etape']) {
		### Deuxième étape : Afficher la liste des clients correspondant a la recherche
		case "listeclient": 

		# VARIABLE skip
		if (!empty($_GET['skip'])) {
			$skip = $_GET['skip'];
		}
		else {$skip = 0;}
		$skipprev = $skip - 35;
		$skipnext = $skip + 35;
		# VARIABLE SELECT
		if (!empty($_POST['codeclient'])) {
			$quid .= "codeclient = ".$_POST['codeclient'];
			$quod .= "codeclient = ".$_POST['codeclient'];
		}
		if (!empty($_POST['societe'])) {
			if (!empty($quid)) $quid .= " AND ";
							$quid .= "societe LIKE '%".$_POST['societe']."%'";
			$quod .= "societe = ".$_POST['societe'];
		}
		if (!empty($_POST['etat'])) {
			if (!empty($quid)) $quid .= " AND ";
							$quid .= "etat = ".$_POST['etat'];
			$quod .= "etat = ".$_POST['etat'];
		}
	#	# ATTENTION POUR TEXTE : LIKE '%XXXX%'
		
		if (!empty($quid)) { $quid = 'WHERE '.$quid; }
		if ($sort == '') { $sort = 'codeclient'; }
		
		$recherche='
			SELECT codeclient, societe, etat, idclient, societe, hforfait FROM client 
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
			<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$idvipjob.'&sel=client';?>">Retour &agrave; la recherche</a><br>
			<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Code Client</th>
					<th class="<?php echo $classe; ?>">Client</th>
					<th class="<?php echo $classe; ?>">Etat</th>
					<th class="<?php echo $classe; ?>">Selection</th>
				</tr>
<?php

		$client = new db();
		$client->inline($recherche);
		
		while ($row = mysql_fetch_array($client->result)) {
?>
				<tr>
					<td class="<?php echo $classe; ?>"><?php echo $row['codeclient'] ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['societe']; ?></td>
					<td class="<?php echo $classe; ?>">
						<?php
						switch ($row['etat']) {
							case "0": 
								echo '<font color="red"> Out';
							break;
							case "5": 
								echo '<font color="green"> In';
							break;
						} 
						?>
						</font>
					</td>
					<td class="<?php echo $classe; ?>">
						<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$_GET['idvipjob'].'&sel=client';?>" method="post">
							<input type="hidden" name="etape" value="listeofficers"> 
							<input type="hidden" name="idclient" value="<?php echo $row['idclient'] ;?>"> 
							<input type="hidden" name="societe" value="<?php echo $row['societe'] ;?>"> 
							
							<input type="hidden" name="forfait" value="<?php if($row['hforfait'] == '2') { echo 'Y'; } else { echo 'N'; } ?>"> 
							<input type="submit" name="Selectionner" value="Selectionner"> 
						</form>
					</td>
				</tr>
		<?php } ?>
			</table>
			<br>
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="40%">
				<tr bgcolor="#FF6699">
					<td class="<?php echo $classe; ?>" align="center"> 
							<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=client';?>" method="post">
								<input type="hidden" name="idvipjob" value="<?php echo $_GET['idvipjob'] ;?>"> 
								<input type="hidden" name="idclient" value=""> 
								<input type="hidden" name="idcofficer" value=""> 
						<input type="submit" name="Selectionner" value="Remove CLIENT from JOB"> 
						</form>
					</td>
				</tr>
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
						<th class="<?php echo $classe; ?>">Departement</th>
						<th class="<?php echo $classe; ?>">Langue</th>
						<th class="<?php echo $classe; ?>">Selection</th>
					</tr>
			<?php
			# Recherche des 15 clients
			$idclient = $_POST['idclient'];
			$client = new db();
			$client->inline("SELECT * FROM `cofficer` WHERE `idclient` = '$idclient'");
			
			while ($row = mysql_fetch_array($client->result)) { 
			?>
					<tr>
						<td class="<?php echo $classe; ?>"><?php echo $row['qualite'].' '.$row['onom'].' '.$row['oprenom']  ?></td>
						<td class="<?php echo $classe; ?>"><?php echo $row['departement']; ?></td>
						<td class="<?php echo $classe; ?>"><?php echo $row['langue']; ?></td>
						<td class="<?php echo $classe; ?>">
							<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=client';?>" method="post">
								<input type="hidden" name="idvipjob" value="<?php echo $_GET['idvipjob'] ;?>"> 
								<input type="hidden" name="idclient" value="<?php echo $row['idclient'] ;?>"> 
								<input type="hidden" name="idcofficer" value="<?php echo $row['idcofficer'] ;?>"> 
								<input type="hidden" name="forfait" value="<?php echo $_POST['forfait'] ;?>"> 
								<input type="submit" name="Selectionner" value="Selectionner">
							</form>
						</td>
					</tr>
			<?php } ?>
				</table>
			<br>
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="40%">
				<tr bgcolor="#FF6699">
					<td class="<?php echo $classe; ?>" align="center"> 
							<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=client';?>" method="post">
								<input type="hidden" name="idvipjob" value="<?php echo $_GET['idvipjob'] ;?>"> 
								<input type="hidden" name="idclient" value=""> 
								<input type="hidden" name="idcofficer" value=""> 
						<input type="submit" name="Selectionner" value="Remove CLIENT from JOB"> 
						</form>
					</td>
				</tr>
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
			<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$_GET['idvipjob'].'&sel=client';?>" method="post">
				<input type="hidden" name="etape" value="listeclient"> 
				<table class="standard" border="0" cellspacing="2" cellpadding="2" align="center" width="50%">
					<tr>
						<td colspan="2" align="center">&nbsp;
							
						</td>
					</tr>
					<tr>
						<td>
							Code client
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
							Etat
						</td>
						<td>
							<input type="radio" name="etat" value="5" checked> In &nbsp; <input type="radio" name="etat" value="0"> Out  &nbsp; <input type="radio" name="etat" value=""> Tous
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<br><br>
							<input type="submit" name="Rechercher" value="Rechercher">
							<br><br>
						</td>
					</tr>
				</table>
			</form>
			<br><br>
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="40%">
				<tr bgcolor="#FF6699">
					<td class="<?php echo $classe; ?>" align="center"> 
							<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=client';?>" method="post">
								<input type="hidden" name="idvipjob" value="<?php echo $_GET['idvipjob'] ;?>"> 
								<input type="hidden" name="idclient" value=""> 
								<input type="hidden" name="idcofficer" value=""> 
						<input type="submit" name="Selectionner" value="Remove CLIENT from JOB"> 
						</form>
					</td>
				</tr>
			</table>
<?php
	}

}
?>
</div>