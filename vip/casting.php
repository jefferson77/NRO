<?php
define('NIVO', '../');

# Entete de page
include NIVO."includes/ifentete.php";
# Classes utilisées
include NIVO."classes/photo.php" ;

if (!isset($_POST['act'])) $_POST['act'] = '';
?>
<div id="orangepeople">
<?php
$form = 'client-onglet-down';

## Modification de la ligne #############
	$idvipjob = $_GET['idvipjob'];
	if (!empty($_POST['idvipjob'])) {$idvipjob = $_POST['idvipjob'];}
	$s = $_GET['s'];
	if (!empty($_POST['s'])) {$s = $_POST['s'];}

if ($_POST['act'] == 'Modifier') {
	## Modification de la ligne SECTION = 2 Tarifs #############
	if ($s == '2') {

		if (!empty($_POST['castingx'])) 
		{
			foreach($_POST['castingx'] as $people) {
				if (!empty($castingx)) {$castingx .= '-';}
				$castingx .= $people;
			}
		} 
		$_POST['casting'] = $castingx;

 		$modif = new db('vipjob', 'idvipjob');
 		$modif->MODIFIE($idvipjob, array('casting'));
	}

}
	$detail = new db();
	$detail->inline("SELECT * FROM `vipjob` WHERE `idvipjob` = $idvipjob");
	$infos = mysql_fetch_array($detail->result) ; 
	$etat=$infos['etat'];
	$casting=$infos['casting'];

	### recherche people si casting != ''
	if (!empty($casting)) {
		## Formater une sequence de casting en explode
		$explo = explode("-", $casting);
			foreach ($explo as $x)
			{
				if (!empty($quid)) {$quid .= ' OR ';}
				$quid .= 'idpeople = '.$x;
				# echo $x;
			}
		#echo $quid;
		
		if (!empty($quid)) {$quid = 'WHERE '.$quid;}
		$recherche='
			SELECT * 
			FROM people
			'.$quid.'
			 ORDER BY pnom, pprenom
		';
		#echo '<br>'.$recherche.'<br>';
	}
	###/ recherche people si casting != ''
?>
	<?php if ($s == '2') { ?>
	<table border="0" class="standard" cellspacing="1" cellpadding="0" align="center" width="98%">
		<tr>
			<td>
				<table class="standard" border="0" cellspacing="0" cellpadding="0" align="center" width="98%">
					<tr>
						<td valign="middle">
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
							<input type="hidden" name="s" value="2"> 
							<input type="hidden" name="idvipjob" value="<?php echo $infos['idvipjob']; ?>"> 

							<table border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
								<tr>
									<td width="25%" align="center"><?php echo '<a href="'.NIVO.'data/people/adminpeople.php?act=search&casting='.$infos['idvipjob'].'" target="_top"> <img src="'.STATIK.'illus/add_casting.gif" border="0" width="20" height="20"> <b>Ajouter</b></a><br>&nbsp;';?></td>
									<td width="25%"></td>
									<td width="25%"></td>
									<td width="25%" align="center"><?php if (!empty($casting)) { ?> <a href="<?php echo NIVO ?>print/people/casting/casting1.php?act=search&casting=<?php echo $infos['casting']; ?>&idvipjob=<?php echo $infos['idvipjob']; ?>" method="post" target="popupC" onclick="window.open('','popupC','scrollbars=yes,status=yes,resizable=yes,width=500,height=400');"> <img src="<?php echo STATIK ?>illus/printer.png" border="0" width="16" height="16"> <b>Imprimer</b></a><br>&nbsp;<?php } ?></td>
								</tr>
								<tr>
						<?php
						if (!empty($quid)) 
						{
								$people = new db();
								$people->inline("$recherche;");
						
								while ($row = mysql_fetch_array($people->result)) {
								$i++;
								?>
								<td class="<?php echo $classe; ?>"  align="center">
									<?php echo '<a href="'.NIVO.'data/people/adminpeople.php?act=show&idpeople='.$row['idpeople'].'&casting='.$infos['idvipjob'].'" target="_top"><img src="'.NIVO.'data/people/photo.php?id='.$row['idpeople'].'" alt=""></a>'; ?>
									<br>
									(<?php echo $row['codepeople'].'-'.$row['idpeople']; ?>) <?php echo $row['pnom']; ?> <?php echo $row['pprenom']; ?> <input type="checkbox" name="castingx[]" checked value="<?php echo $row['idpeople'] ?>"> 
								</td>
								<?php if ($i == 4) {echo '</tr><tr><td colspan="4"><hr></td></tr><tr>'; $i=0;}?>
								<?php
								}
						}
						?>
								</tr>
								<tr>
									<td colspan="4" align="center">
										<?php
										### Modifier people si casting != ''
										if (!empty($casting)) {echo '<div align="center"><input type="submit" name="act" value="Modifier"></div>';}
										### Modifier people si casting != ''
										?>	
									</td>
								</tr>
								<tr>
									<td width="25%" align="center"><?php if (!empty($casting)) { ?><?php echo '<a href="'.NIVO.'data/people/adminpeople.php?act=search&casting='.$infos['idvipjob'].'" target="_top"> <img src="'.STATIK.'illus/add_casting.gif" border="0" width="20" height="20"> <b>Ajouter</b></a><br>&nbsp;';?><?php } ?></td>
									<td width="25%"></td>
									<td width="25%"></td>
									<td width="25%" align="center"><?php if (!empty($casting)) { ?> <a href="<?php echo NIVO ?>print/people/casting/casting1.php?act=search&casting=<?php echo $infos['casting']; ?>&idvipjob=<?php echo $infos['idvipjob']; ?>" method="post" target="popupC" onclick="window.open('','popupC','scrollbars=yes,status=yes,resizable=yes,width=500,height=400');"> <img src="<?php echo STATIK ?>illus/printer.png" border="0" width="16" height="16"> <b>Imprimer</b></a><br>&nbsp;<?php } ?></td>
								</tr>
						</table>
						</form>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<?php } ?>
<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;

#destruction de la variable sinon l'icône d'ajout subsite dans la receherche de people
unset($_SESSION['casting']);
?>
</div>