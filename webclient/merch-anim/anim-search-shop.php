<?php #  Centre de recherche de shop
if ($_GET['shopsearch'] == 2) { ### pour emp�cher la recherche � vide
	if (($_POST['codeshop'] == '') and ($_POST['societe'] == '') and ($_POST['cp'] == '') and ($_POST['ville'] == '')) {
		$_GET['shopsearch'] = 1;
		$textvide = "Veuillez remplir au moins un crit&egrave;re de recherche, s'il vous plait.<br>";
	} else {
		$_GET['shopsearch'] = 3;
	}
}

if ($_GET['shopsearch'] == 1) 
{
	echo $textvide;
	$_SESSION['animwebshopquid'] = $quid;
	$_SESSION['animwebshopquod'] = $quod;
	$_SESSION['animwebshopsort'] = $sort;
	$_SESSION['animwebshopsearch'] = $recherche1;
?>
	
	<form action="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=2';?>" method="post">
		<table class="standard" border="0" cellspacing="1" cellpadding="6" align="center" width="98%">
			<tr>
				<td><label for="codeshop">Code</label> <input type="text" name="codeshop" id="codeshop"></td>
				<td><label for="societe">Soci&eacute;t&eacute;</label> <input type="text" name="societe" id="societe"></td>
				<td><label for="cp">CP</label> <input type="text" name="cp" id="cp"></td>
				<td><label for="ville">Ville</label> <input type="text" name="ville" id="ville"></td>
			</tr>
			<tr>
				<td align="center" colspan="4"><input type="submit" name="Rechercher" value="Rechercher"></td>
			</tr>
		</table>	
  	</form>
<?php 
} 
if ($_GET['shopsearch'] == 3) {
	switch ($_GET['action']) {
		case "skip": 
		### Deuxi�me �tape : Afficher la liste des clients correspondant a la recherche apr�s SORT
#	
#			# VARIABLE skip
#			if (!empty($_GET['skip'])) {
#				$skip = $_GET['skip'];
#			}
#			else {$skip = 0;}
#			$skipprev = $skip - 20;
#			$skipnext = $skip + 20;
#	
			# VARIABLE SELECT
			if (!empty($_GET['sort'])) {
				$_SESSION['animwebshopsort'] = $_GET['sort'];
			}
			$shopsort = $_SESSION['animwebshopsort'];
			$shopquid = $_SESSION['animwebshopquid'];
			$shopquod = $_SESSION['animwebshopquod'];
			$shopsearch = $_SESSION['animwebshopsearch'];
#			$recherchetotal = $_SESSION['animwebshoprecherchetotal'];
			$recherche='
				'.$shopsearch.'
				'.$shopquid.'
				 ORDER BY '.$shopsort.'
			';
#				 LIMIT '.$skip.', 20
		break;
		### Premi�re �tape : Afficher la liste des Anim a la recherche SANS SORT
		default: 

#			# VARIABLE skip
#			if (!empty($_GET['skip'])) {
#				$skip=$_GET['skip'];
#			}
#			else {$skip=0;}
#			$skipprev=$skip-20;
#			$skipnext=$skip+20;
			# VARIABLE SELECT
			if (!empty($_POST['codeshop'])) {
				$quid="codeshop LIKE '%".$_POST['codeshop']."%'";
				$quod="codeshop = ".$_POST['codeshop'];
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
				$quod=$quod."CP = ".$_POST['cp'];
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
		
			# ATTENTION POUR TEXTE : LIKE '%XXXX%'
			
			if (!empty($quid)) {$quid='WHERE '.$quid;}
			if ($sort == '') {$sort='codeshop';}
			$recherche='
				SELECT * FROM `shop`
				'.$quid.'
				 ORDER BY '.$sort.'
			';
#			$recherche='
#				SELECT * FROM `shop`
#				'.$quid.'
#				 ORDER BY '.$sort.'
#				 LIMIT '.$skip.', 20
#			';
#			$recherchetotal='
#				SELECT idshop FROM `shop`
#				'.$quid.'
#				 ORDER BY '.$sort.'
#			';

			$_SESSION['animwebshopsort'] = $sort ;
			$_SESSION['animwebshopquid'] = $quid ;
			$_SESSION['animwebshopquod'] = $quod ;
			$_SESSION['animwebshopsearch'] = 'SELECT * FROM `shop`';
#			$_SESSION['animwebshoprecherchetotal'] = $recherchetotal;
			#echo $recherche;
		}
#echo $recherche;

	# Recherche des r�sultats pour FoundCount
#	$shop1 = new db();
#	$shop1->inline("$recherchetotal;");
#	$FoundCount = mysql_num_rows($shop1->result); 

	# Recherche des r�sultats pour la liste
	$shop = new db();
	$shop->inline("$recherche;");
	$FoundCount = mysql_num_rows($shop->result); 
?>
			<?php echo $_SESSION['animwebshopquod']; ?> (<?php echo $FoundCount; ?>)
		<?php $classe = "standard" ?>
	<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=animmodif<?php echo $s; ?>&section=search" method="post">
		<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>"> 
		<input type="hidden" name="s" value="<?php echo $s;?>">
		<input type="hidden" name="shopselection" value="<?php echo $shopselection;?>">
<!--
		<table class="standard" border="0" cellspacing="0" cellpadding="2" align="center" width="98%">
			<tr>
				<td class="etiq3"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=2&action=skip&skip='.$skipprev; ?>"><img src="<?php echo $illus ?>avant.gif" alt="Previous Results" width="20" height="20" border="0"></a></td>
				<td class="etiq3" align="center"><?php echo $skip;?> - <?php echo $skip+19;?></td>
				<td class="etiq3" align="right"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=2&action=skip&skip='.$skipnext; ?>"><img src="<?php echo $illus ?>apres.gif" alt="Next Results" width="20" height="20" border="0"></a></td>
			</tr>
		</table>
-->
		<table class="standard" border="0" cellspacing="1" cellpadding="6" align="center" width="98%">
			<tr>
				<td class="etiq3"><a class="white2" href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=3&action=skip&skip='.$skip.'&sort=codeshop';?>">Code</a></td>
				<td class="etiq3"><a class="white2" href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=3&action=skip&skip='.$skip.'&sort=societe';?>">Soci&eacute;t&eacute;</a></td>
				<td class="etiq3"><a class="white2" href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=3&action=skip&skip='.$skip.'&sort=cp';?>">CP</a></td>
				<td class="etiq3"><a class="white2" href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=3&action=skip&skip='.$skip.'&sort=ville';?>">Ville</a></td>
				<td class="etiq3">adresse</td>
				<td class="etiq3">S&eacute;lection</td>
			</tr>
	<?php
	while ($row = mysql_fetch_array($shop->result)) { 
	?>
			<tr>
				<td class="contenu"><?php echo $row['codeshop'] ?></td>
				<td class="contenu"><?php echo $row['societe']; ?></td>
				<td class="contenu"><?php echo $row['cp']; ?></td>
				<td class="contenu"><?php echo $row['ville']; ?></td>
				<td class="contenu"><?php echo $row['adresse']; ?></td>
				<td class="etiq3" valign="top">
					<input type="checkbox" name="shopselectionsearch[]" value="<?php echo $row['idshop'].'-'; ?>" <?php if (strchr($infosjob['shopselection'], $row['idshop'])) { echo 'checked'; } ?>>
				</td>
			</tr>
	<?php 
	} ?>
			<tr>
				<td class="etiq3"><a class="white2" href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=3&action=skip&skip='.$skip.'&sort=codeshop';?>">Code</a></td>
				<td class="etiq3"><a class="white2" href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=3&action=skip&skip='.$skip.'&sort=societe';?>">Soci&eacute;t&eacute;</a></td>
				<td class="etiq3"><a class="white2" href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=3&action=skip&skip='.$skip.'&sort=cp';?>">CP</a></td>
				<td class="etiq3"><a class="white2" href="<?php echo $_SERVER['PHP_SELF'].'?act=animmodif'.$s.'a&idwebanimjob='.$idwebanimjob.'&shopsearch=3&action=skip&skip='.$skip.'&sort=ville';?>">Ville</a></td>
				<td class="etiq3">adresse</td>
				<td class="etiq3">S&eacute;lection</td>
			</tr>
			<tr>
				<td align="center" colspan="6">
					<input type="submit" name="action" value="Ajouter la s&eacute;lection"> 
				</td>
			</tr>
		</table>
	</form>
<?php 
} 
?>

