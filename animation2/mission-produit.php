<?php
# Entete de page
define('NIVO', '../');
$css = standard;
include NIVO."includes/ifentete.php" ;

$idanimation = $_GET['idanimation'];

switch ($_GET['act']) {
############## Ajout d'un Produit #############################################
		case "add": 
			$_POST['idanimation'] = $_GET['idanimation'];
			$ajout = new db('animproduit', 'idanimproduit');
			$ajout->AJOUTE(array('idanimation', 'types'));
		break;

############## Delete d'un Produit #############################################
		case "delete": 
			$delproduit = new db('animproduit', 'idanimproduit');
			$delproduit->inline("DELETE FROM `animproduit` WHERE `idanimproduit` = ".$_POST['idanimproduit']);
			$delproduit->journal("Effacement du produit");
		break;

############## DUPLIC d'un Produit #############################################
		case "duplic": 
			$prod1 = new db();
			$champs = "idanimation, types, prix, produitin, unite, produitend, ventes, produitno, degustation, promoin, promoout, promoend, agentmodif, datemodif";
			$prod1->inline("INSERT INTO animproduit ($champs) SELECT $champs FROM animproduit WHERE idanimproduit = ".$_POST['idanimproduit']);
		break;

############## Modif d'un Produit #############################################
		case "modif": 
			$_POST['prix'] = fnbrbk($_POST['prix']);
			$_POST['produitin'] = fnbrbk($_POST['produitin']);
			$_POST['produitend'] = fnbrbk($_POST['produitend']);
			$_POST['ventes'] = fnbrbk($_POST['ventes']);
			$_POST['promoin'] = fnbrbk($_POST['promoin']);
			$_POST['promoout'] = fnbrbk($_POST['promoout']);
			$_POST['promoend'] = fnbrbk($_POST['promoend']);
			$_POST['idanimation'] = $_GET['idanimation'];
			$modif = new db('animproduit', 'idanimproduit');
			$modif->MODIFIE($_POST['idanimproduit'], array('types', 'prix' , 'produitin' , 'unite' , 'produitend' , 'ventes' , 'produitno' , 'degustation' , 'promoin' , 'promoout' , 'promoend'));
		break;
}
?>
<?php /*  Corps de la Page */ ?>
<div id="orangepeople">
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
		<tr>
			<td>
				&nbsp; 
			</td>
			<th class="vip2">
				Types 
			</th>
			<th class="vip2">
				Prix 
			</th>
			<th class="vip2">
				In
			</th>
			<th class="vip2">
				Unit&eacute; 
			</th>
			<th class="vip2">
				End
			</th>
			<th class="vip2">
				Ventes 
			</th>
			<th class="vip2">
				No 
			</th>
			<th class="vip2">
				degustation 
			</th>
			<th class="vip2">
				Promo in 
			</th>
			<th class="vip2">
				Promo out 
			</th>
			<th class="vip2">
				Promo end 
			</th>
			<td>
				&nbsp; 
			</td>
			<td>
				&nbsp; 
			</td>
		</tr>
<?php
if (!empty($idanimation)) {
	# Recherche des r?sultats
	$produit = new db('animproduit', 'idanimproduit');
	$produit->inline("SELECT * FROM `animproduit` WHERE `idanimation` = $idanimation");
	
	$i = 0; # Pour les couleurs altern?es
	while ($prod = mysql_fetch_array($produit->result)) { 
	
	#> Changement de couleur des lignes #####>>####
	$i++;
	if (fmod($i, 2) == 1) {
		echo '<tr bgcolor="#9CBECA">';
	} else {
		echo '<tr bgcolor="#8CAAB5">';
	}
	#< Changement de couleur des lignes #####<<####
?>
			<td>
				<?php if ($disable != 'disabled') { ### page input ?>
					<form action="<?php echo $_SERVER['PHP_SELF'].'?act=duplic&idanimation='.$idanimation ;?>" method="post">
						<input type="hidden" name="idanimation" value="<?php echo $idanimation;?>">
						<input type="hidden" name="idanimproduit" value="<?php echo $prod['idanimproduit'];?>">
						<input type="submit" name="Modifier" value="D">
					</form>
				<?php } ### page input ?>
			</td>
			<?php if ($disable != 'disabled') { ### page input ?>
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&idanimation='.$idanimation.'&zone='.$zone ;?>" method="post">
			<?php } ### page input ?>
			<td align="center">
				<input type="text" size="20" name="types" value="<?php echo $prod['types']; ?>" <?php echo $disable; ?>>
			</td>
			<td align="center">
				<input type="text" size="6" name="prix" value="<?php echo fnbr($prod['prix']); ?>" <?php echo $disable; ?>>
			</td>
			<td align="center">
				<input type="text" size="6" name="produitin" value="<?php echo fnbr($prod['produitin']); ?>" <?php echo $disable; ?>>
			</td>
			<td align="center">
				<input type="text" size="10" name="unite" value="<?php echo $prod['unite']; ?>" <?php echo $disable; ?>>
			</td>
			<td align="center">
				<input type="text" size="6" name="produitend" value="<?php echo fnbr($prod['produitend']); ?>" <?php echo $disable; ?>>
			</td>
			<td align="center">
				<input type="text" size="6" name="ventes" value="<?php echo fnbr($prod['ventes']); ?>" <?php echo $disable; ?>>
				<?php $totvente = $totvente + $prod['ventes'] ;?>
			</td>
			<td align="center">
				<?php echo '<input type="checkbox" name="produitno" value="no" '; if ($prod['produitno'] == 'no') { echo 'checked';} echo'>'; ?>
			</td>
			<td align="center">
				<input type="text" size="10" name="degustation" value="<?php echo $prod['degustation']; ?>" <?php echo $disable; ?>>
			</td>
			<td align="center">
				<input type="text" size="6" name="promoin" value="<?php echo fnbr($prod['promoin']); ?>" <?php echo $disable; ?>>
			</td>
			<td align="center">
				<input type="text" size="6" name="promoout" value="<?php echo fnbr($prod['promoout']); ?>" <?php echo $disable; ?>>
			</td>
			<td align="center">
				<input type="text" size="6" name="promoend" value="<?php echo fnbr($prod['promoend']); ?>" <?php echo $disable; ?>>
			</td>
			<td>
				<?php if ($disable != 'disabled') { ### page input ?>
					<input type="hidden" name="idanimation" value="<?php echo $idanimation;?>">
					<input type="hidden" name="idanimproduit" value="<?php echo $prod['idanimproduit'];?>">
					<input type="submit" name="Modifier" value="M">
				<?php } ### page input ?>
				</form>
			</td>
			<td align="center">
				<?php if ($disable != 'disabled') { ### page input ?>
					<form action="<?php echo $_SERVER['PHP_SELF'].'?act=delete&idanimation='.$idanimation ;?>" method="post">
						<input type="hidden" name="idanimation" value="<?php echo $idanimation;?>">
						<input type="hidden" name="idanimproduit" value="<?php echo $prod['idanimproduit'];?>">
						<input type="submit" name="Modifier" value="S">
					</form>
				<?php } ### page input ?>
			</td>
		</tr>
<?php
$compt++;
	} 
?>
	<?php if ($disable != 'disabled') { ### page input ?>
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=add&idanimation='.$idanimation;?>" method="post">
		<input type="hidden" name="idanimation" value="<?php echo $idanimation;?>"> 
			<tr>
				<td>
					<input type="submit" name="Modifier" value="A">
				</td>
				<td align="center">
					<input type="text" size="20" name="types" value="<?php echo $prod['types']; ?>">
				</td>
				<td colspan="2">
					&nbsp;
				</td>
				<td colspan="2" align="right">
					<b>Total ventes :</b>
				</td>
				<td>
					<b><?php echo $totvente?></b>
				</td>
				<td colspan="6">
					&nbsp;
				</td>
				<td>
					<input type="submit" name="Modifier" value="A">
				</td>
			</tr>
		</form>
	<?php } ### page input ?>
<?php
}
?>
	</table>
</div>
<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
