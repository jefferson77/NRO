<?php
define('NIVO', '../');
$classe = "etiq2";
$classe2 = "standard";

# Entete de page
include NIVO."includes/ifentete.php" ;

$req = new db();

# Carousel des fonctions
switch ($_GET['act']) {
############## Effacement d'une Famille stock #############################################
		case "delete": 
			$supprimer = new db('stockticket', 'idticket');
			$supprimer->EFFACE($_POST['idticket']);
		break;
############## Ajout d'une Famille stock #############################################
		case "add": 
			$_POST['stockout'] = fdatebk($_POST['stockout']);
			$_POST['stockin'] = fdatebk($_POST['stockin']);
			$_POST['dateticket'] = date ("Y-m-d");
			$_POST['sview'] = 1;
			$_POST['jobstatut'] = 'job';
			$ajout = new db('stockticket', 'idticket');
			$ajout->AJOUTE(array('idstockf', 'stockout', 'stockin', 'nombre', 'idvipjob', 'dateticket', 'sview', 'sex', 'jobstatut'));
		break;
############## Modification et affichage d'une Famille stock #########################################
		case "modifier": 
			$_POST['stockout'] = fdatebk($_POST['stockout']);
			$_POST['stockin'] = fdatebk($_POST['stockin']);
			$modif = new db('stockticket', 'idticket');
			$modif->MODIFIE($_POST['idticket'], array('idstockf', 'stockout', 'stockin', 'nombre', 'sview', 'sex'));	
		break;
############## Modification Job pour materiel necessaire#########################################
		case "jobstock":
			$modif = new db('vipjob', 'idvipjob');
			$modif->MODIFIE($_GET['idvipjob'], array('stock'));
			$PhraseBas = 'Detail d\'une VIP : Assistant Modifi&eacute;';
		break;

}
?>
<div id="minicentervipwhite">
<?php
### recherche date job
	$req->inline("SELECT dateout, datein  FROM `vipjob` WHERE `idvipjob` = ".$_GET['idvipjob']);
	$dt = mysql_fetch_array($req->result);
#/## recherche date job

### menu de toutes les familles
	$listingfamille = new db();
	$listingfamille->inline('SELECT idstockf, reference FROM stockf WHERE idstockf >= 1 ORDER BY reference');

#/## menu de toutes les familles

		$req->inline("SELECT stock FROM `vipjob` WHERE `idvipjob` = ".$_GET['idvipjob']);
		$infos = mysql_fetch_array($req->result) ; 
		?>
		<table class="etiq" border="0" width="300" cellspacing="1" cellpadding="5" align="center">
			<form action="<?php echo $_SERVER['PHP_SELF'].'?act=jobstock&idvipjob='.$_GET['idvipjob'];?>" method="post">
				<input type="hidden" name="idvipjob" value="<?php echo $_GET['idvipjob']; ?>"></td>
				<tr>
					<td bgcolor="#FF9933">
						Mat&eacute;riel n&eacute;cessaire ? 
					</td>
					<td bgcolor="#FF9933">
						<input type="radio" name="stock" value="oui" <?php if ($infos['stock'] == 'oui') { echo 'checked';} ?>> oui
						<input type="radio" name="stock" value="non" <?php if ($infos['stock'] == 'non') { echo 'checked';} ?>> non 
					</td>
					<td bgcolor="#FF9933">
						<input type="submit" name="Modifier" value="Modifier">
					</td>
				</tr>
			</form>
		</table>		

	<fieldset class="blue">
		<legend class="blue">
			Projection de r&eacute;servation
		</legend>
		<table class="etiq" border="0" width="65%" cellspacing="1" cellpadding="1" align="center">
			<tr>
				<td></td>
				<td class="<?php echo $classe; ?>">Famille</td>
				<td class="<?php echo $classe; ?>">#</td>
				<td class="<?php echo $classe; ?>">Du</td>
				<td class="<?php echo $classe; ?>">Au</td>
				<td class="<?php echo $classe; ?>">Sex</td>
			</tr>
			<form action="<?php echo $_SERVER['PHP_SELF'].'?act=add&idvipjob='.$_GET['idvipjob'];?>" method="post">
				<input type="hidden" name="idvipjob" value="<?php echo $_GET['idvipjob']; ?>"></td>
				<tr>
					<td></td>
					<td>
						<?php $option2 = '';
							while ($rowfamille = mysql_fetch_array($listingfamille->result)) { 
								$option2 .= '<option value="'.$rowfamille['idstockf'].'">'.substr($rowfamille['reference'], 0, 65).'</option>';
							}
						?>
						<select name="idstockf" size="1">
							<?php echo $option2; ?>
						</select>
					</td>
					<td><input type="text" size="5" name="nombre" value=""></td>
					<td><input type="text" size="10" name="stockout" value="<?php echo fdate($dt['datein']); ?>"></td>
					<td><input type="text" size="10" name="stockin" value="<?php echo fdate($dt['dateout']); ?>"></td>
					<td>	
						<select name="sex" size="1">
							<option value="f" selected>f</option>
							<option value="m">m</option>
							<option value="x">x</option>
						</select>
					</td>
					<td colspan="2"><input type="submit" name="action" value="Add"></td>
				</tr>
			</form>
			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td></td>
				<td class="<?php echo $classe; ?>">Famille</td>
				<td class="<?php echo $classe; ?>">#</td>
				<td class="<?php echo $classe; ?>">Du</td>
				<td class="<?php echo $classe; ?>">Au</td>
				<td class="<?php echo $classe; ?>">Sex</td>
			</tr>
			<?php 
				$sql = 'SELECT 
					idticket, idvipjob, dateticket, stockout, stockin, suser, inuse, note, idstockf, nombre, sex
					FROM stockticket
					WHERE idvipjob = '.$_GET['idvipjob'].' AND jobstatut = "job"
					ORDER BY sex, stockout, stockin, idstockf';
				$ticket = new db();
				$ticket->inline($sql);
				#	echo $recherche;
			while ($row = mysql_fetch_array($ticket->result)) {
			?>
				<tr>
					<form action="<?php echo $_SERVER['PHP_SELF'].'?act=delete&idvipjob='.$_GET['idvipjob'];?>" method="post"  onSubmit="return confirm('Etes-vous sur de vouloir effacer ce mat&eacute;riel?')">
						<input type="hidden" name="idticket" value="<?php echo $row['idticket']; ?>"></td>
						<td><input type="submit" name="action" value="S"></td>
					</form>
					<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modifier&idvipjob='.$_GET['idvipjob'];?>" method="post">
						<input type="hidden" name="idticket" value="<?php echo $row['idticket']; ?>"></td>
						<td>
							<?php $option2 = '';
								mysql_data_seek($listingfamille->result, 0);
								while ($rowfamille = mysql_fetch_array($listingfamille->result)) { 
									$option2 .= '<option value="'.$rowfamille['idstockf'].'"';
									if ($row['idstockf'] == $rowfamille['idstockf']) { $option2 .= ' selected '; }
									$option2 .= '>'.substr($rowfamille['reference'], 0, 65).'</option>';
								}
							?>
							<select name="idstockf" size="1">
								<?php echo $option2; ?>
							</select>
						</td>
						<td><input type="text" size="5" name="nombre" value="<?php echo $row['nombre']; ?>"></td>
						<td><input type="text" size="10" name="stockout" value="<?php echo fdate($row['stockout']); ?>"></td>
						<td><input type="text" size="10" name="stockin" value="<?php echo fdate($row['stockin']); ?>"></td>
						<td>	
							<select name="sex" size="1">
								<option value="f" <?php if ($row['sex'] == 'f') { echo'selected'; } ?>>f</option>
								<option value="m" <?php if ($row['sex'] == 'm') { echo'selected'; } ?>>m</option>
								<option value="x" <?php if ($row['sex'] == 'x') { echo'selected'; } ?>>x</option>
							</select>
						</td>
						<td><input type="submit" name="action" value="M"></td>
					</form>
				</tr>
			<?php } ?>
		</table>
	</fieldset>
<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
</div>
