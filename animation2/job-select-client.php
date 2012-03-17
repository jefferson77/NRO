<?php #  Sélection des éléments ?>
<div id="centerzonelarge">
<?php
$classe = 'standard' ;
if (!empty($_POST['etape'])) {$_GET['etape'] = $_POST['etape'];}


################# Sélection du Client ###################
	switch ($_GET['etape']) {
		### Deuxième étape : Afficher la liste des clients correspondant a la recherche
		case "listeclient": 

		# VARIABLE skip
		if (!empty($_GET['skip'])) {
			$skip=$_GET['skip'];
		}
		else {$skip=0;}
		$skipprev = $skip - 35;
		$skipnext = $skip + 35;
		# VARIABLE SELECT
		if (!empty($_POST['codeclient'])) {
			$quid .= "codeclient = ".$_POST['codeclient'];
			$quod .= "codeclient = ".$_POST['codeclient'];
		}
		if (!empty($_POST['societe'])) {
			if (!empty($quid)) 
			{
				$quid .= " AND ";
				$quod .= " ET ";
			}	
			$quid .= "societe LIKE '%".$_POST['societe']."%'";
			$quod .= "societe = ".$_POST['societe'];
		}
		if (!empty($_POST['cp'])) {
			if (!empty($quid)) 
			{
				$quid .= " AND ";
				$quod .= " ET ";
			}	
			$quid .= "cp LIKE '%".$_POST['cp']."%'";
			$quod .= "cp = ".$_POST['cp'];
		}
		if (!empty($_POST['ville'])) {
			if (!empty($quid)) 
			{
				$quid .= " AND ";
				$quod .= " ET ";
			}	
			$quid .= "ville LIKE '%".$_POST['ville']."%'";
			$quod .= "ville = ".$_POST['ville'];
		}
		if (!empty($_POST['etat'])) {
			if (!empty($quid)) 
			{
				$quid .= " AND ";
				$quod .= " ET ";
			}	
			$quid .= "etat = ".$_POST['etat'];
			$quod .= "etat = ".$_POST['etat'];
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
			<a href="<?php echo $_SERVER['PHP_SELF'].'?act=selectclient&idanimjob='.$idanimjob.'&sel=client';?>">Retour &agrave; la recherche</a><br>
			<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Code Client</th>
					<th class="<?php echo $classe; ?>">Client</th>
					<th class="<?php echo $classe; ?>">CP</th>
					<th class="<?php echo $classe; ?>">Ville</th>
					<th class="<?php echo $classe; ?>">Etat</th>
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
						<form action="<?php echo $_SERVER['PHP_SELF'].'?act=selectclient&idanimjob='.$idanimjob.'&sel=client&etape=listeofficers';?>" method="post">
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
						<th class="<?php echo $classe; ?>">Dep</th>
						<th class="<?php echo $classe; ?>">Langue</th>
						<th class="<?php echo $classe; ?>">Selection</th>
					</tr>
			<?php
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
							<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modifselect&mod=client';?>" method="post">
								<input type="hidden" name="idanimjob" value="<?php echo $idanimjob ;?>"> 
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
			<form action="<?php echo $_SERVER['PHP_SELF'].'?act=selectclient&idanimjob='.$idanimjob.'&sel=client&etape=listeclient';?>" method="post">
				<input type="hidden" name="etape" value="listeclient"> 
				<table class="standard" border="0" cellspacing="2" cellpadding="2" align="center" width="50%">
					<tr>
						<td colspan="2" align="center">
							&nbsp;
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
							CP
						</td>
						<td>
							<input type="text" size="20" name="cp" value="">
						</td>
					</tr>
					<tr>
						<td>
							Ville
						</td>
						<td>
							<input type="text" size="20" name="ville" value="">
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
							<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modifselect&mod=client';?>" method="post">
								<input type="hidden" name="idanimjob" value="<?php echo $idanimjob ;?>"> 
								<input type="hidden" name="idclient" value=""> 
								<input type="hidden" name="idcofficer" value=""> 
						<input type="submit" name="Selectionner" value="Remove CLIENT from ANIM"> 
						</form>
					</td>
				</tr>
			</table>
<?php
	}
?>
</div>