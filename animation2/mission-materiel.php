<?php
# Entete de page
define('NIVO', '../');
$css = standard;
include NIVO."includes/ifentete.php" ;

$idanimation = $_GET['idanimation'];
$disable = $_GET['disable'];

?>
<div id="orangepeople">
<?php
## Modification de la ligne #############
if ($_GET['act'] == 'Modifier') {
		
			$ajout = new db('animmateriel', 'idanimmateriel');
			$ajout->AJOUTE(array('idanimation' ));

		
		$_POST['stand'] = fnbrbk($_POST['stand']);
		$_POST['gobelet'] = fnbrbk($_POST['gobelet']);
		$_POST['serviette'] = fnbrbk($_POST['serviette']);
		$_POST['four'] = fnbrbk($_POST['four']);
		$_POST['curedent'] = fnbrbk($_POST['curedent']);
		$_POST['cuillere'] = fnbrbk($_POST['cuillere']);
		$_POST['rechaud'] = fnbrbk($_POST['rechaud']);
		$_POST['autre'] = fnbrbk($_POST['autre']);

		$modif = new db('animmateriel', 'idanimmateriel');
		$modif->MODIFIE($_POST['idanimmateriel'], array('stand' , 'gobelet' , 'serviette' , 'four' , 'curedent' , 'cuillere' , 'rechaud' , 'autre'));
}

	$detail1 = new db('animmateriel', 'idanimmateriel');
	$detail1->inline("SELECT * FROM `animmateriel` WHERE `idanimation` = $idanimation");
	$matos = mysql_fetch_array($detail1->result) ; 
	$idanimmateriel = $matos['idanimmateriel'];
	if ($idanimmateriel == '') {
		$_POST['idanimation'] = $idanimation ;
		$ajout = new db('animmateriel', 'idanimmateriel');
		$ajout->AJOUTE(array('idanimation' ));
		$idanimmateriel = $ajout->addid;
	}
?>
<?php if ($disable != 'disabled') { ### page input ?>
	<form action="<?php echo $_SERVER['PHP_SELF'].'?act=Modifier&idanimation='.$idanimation;?>" method="post">
	<input type="hidden" name="idanimmateriel" value="<?php echo $idanimmateriel;?>"> 
<?php } ### page input ?>
	<table border="0" class="standard" cellspacing="1" cellpadding="0" align="center" width="99%">
		<tr>
			<td>
				<table border="0" class="standard" cellspacing="1" cellpadding="0" align="center" width="99%">
					<tr>
						<td>
							Stand
						</td>
						<td>
							<input type="text" size="5" name="stand" value="<?php echo fnbr($matos['stand']); ?>" <?php echo $disable; ?>>
						</td>
					</tr>
					<tr>
						<td>
							Gobelets
						</td>
						<td>
							<input type="text" size="5" name="gobelet" value="<?php echo fnbr($matos['gobelet']); ?>" <?php echo $disable; ?>>
						</td>
					</tr>
					<tr>
						<td>
							Serviettes
						</td>
						<td>
							<input type="text" size="5" name="serviette" value="<?php echo fnbr($matos['serviette']); ?>" <?php echo $disable; ?>>
						</td>
					</tr>
					<tr>
						<td>
							Four
						</td>
						<td>
							<input type="text" size="5" name="four" value="<?php echo fnbr($matos['four']); ?>" <?php echo $disable; ?>>
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table border="0" class="standard" cellspacing="1" cellpadding="0" align="center" width="99%">
					<tr>
						<td>
							Cure-dents
						</td>
						<td>
							<input type="text" size="5" name="curedent" value="<?php echo fnbr($matos['curedent']); ?>" <?php echo $disable; ?>>
						</td>
					</tr>
					<tr>
						<td>
							Cuill&egrave;res
						</td>
						<td>
							<input type="text" size="5" name="cuillere" value="<?php echo fnbr($matos['cuillere']); ?>" <?php echo $disable; ?>>
						</td>
					</tr>
					<tr>
						<td>
							R&eacute;chaud
						</td>
						<td>
							<input type="text" size="5" name="rechaud" value="<?php echo fnbr($matos['rechaud']); ?>" <?php echo $disable; ?>>
						</td>
					</tr>
					<tr>
						<td>
							Autres
						</td>
						<td>
							<input type="text" size="5" name="autre" value="<?php echo fnbr($matos['autre']); ?>" <?php echo $disable; ?>>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<?php if ($disable != 'disabled') { ### page input ?>
								<input type="submit" name="Modifier" value="Modifier">
							<?php } ### page input ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<?php if ($disable != 'disabled') { ### page input ?>
</form>
<?php } ### page input ?>
<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
</div>
