<?php
define('NIVO', '../../'); 
$classe = "planning";

# Classes utilisées
include NIVO.'classes/vip.php';
include NIVO."classes/anim.php";

# Entete de page
$css = 'standard';
include NIVO."includes/ifentete.php" ;
$DB=new db();
# Carousel des fonctions
?>
<div id="orangepeople">
<?php
switch ($_REQUEST['act']) {
############## UNASSIGN #########################################
		case "unasign": 
			$idmatos = $_REQUEST['idmatos'];
				$DB->inline("UPDATE matos SET idpeople = NULL, dateout = NULL WHERE idmatos = ".$_REQUEST['idmatos'].";");
			$message = $_GET['idmatos'].' unasigned';
		break;
############## Recherche d'une mission #########################################
## code Greg
		case "asign" :
			$codematos = $_REQUEST['codematos'];
			$idagent = $_SESSION['idagent'];
			$idppl = $_REQUEST['idpeople'];
			//trouver la syntaxe MODIFIE
			$modifgreg = new db();
			$idpeoplegreg = $modifgreg->getOne("SELECT idpeople FROM matos WHERE codematos LIKE '$codematos'");
			if($idpeoplegreg > 0 && empty($_GET['force']))
			{
				$force='<div style="border: 2px solid #CE160F; background-color: #DD5845; color:#FFF; font-size:14px; padding:4px; font-weight: bold; text-align: center;">'.
				"Matériel déjà attribué?".
				'<a href="'.$_SERVER['PHP_SELF'].'?force=yes&act=asign&codematos='.$codematos.'&idpeople='.$idppl.'"> Réattribuer </a>'.
				"</div>";
				
			}
			else
			{
			$modifgreg->inline("UPDATE matos SET idpeople = '$idppl', agentmodif = '$idagent',dateout = NOW() WHERE codematos LIKE '$codematos';");
			$message = 'Ajout fini';
			}
		break;
## code Greg
		default: 
			$message = '';
}
?>
<fieldset>
	<legend>
		<Font color="#527184"><b>MATERIEL PEOPLE <?php echo $message; ?></b></font>
	</legend>
<?php
	echo $force;
	$listing = new db();
	$listing->inline("SELECT * FROM `matos` WHERE `idpeople` LIKE '".$_REQUEST['idpeople']."'AND idpeople > 0 ORDER BY dateout");
	if($_REQUEST['idpeople']!=0){  ?>
		<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
			<div align="center">Ajouter le materiel (code) : 
				<input type="text" name="codematos">
				<input type="hidden" name="act" value="asign">
				<input type="hidden" name="idpeople" value= "<?php echo $_REQUEST['idpeople'];?>">
				<input type="submit" value="Ajouter">
			</div>
		</form>
	<?php } ?>
	<table class="<?php echo $classe; ?>" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
<?php $sectionz == ''; ?>
		<tr>
			<th class="<?php echo $classe; ?>">Date</th>
			<th class="<?php echo $classe; ?>">Code</th>
			<th class="<?php echo $classe; ?>">Nom</th>
			<th class="<?php echo $classe; ?>">Mission</th>
			<th class="<?php echo $classe; ?>"></th>
		</tr>
<?php
$sectionz = '';
$count = mysql_num_rows($listing->result);
while ($row = mysql_fetch_array($listing->result)) {
	if ($row['idvip'] > 0) { $section = 'VIP'; $mission = $row['idvip']; }
	if ($row['idanimation'] > 0) { $section = 'Animation'; $mission = $row['idanimation']; }
	if ($row['idmerch'] > 0) { $section = 'Merch'; $mission = $row['idmerch']; }
	if ($sectionz != $section) {
	?>
			<tr>
				<td colspan="5" class="white" align="center"><b><?php echo $section; ?></b></td>
			
				
								
			</tr>
	<?php
	}
		#> Changement de couleur des lignes #####>>####
		$i++;
		if (fmod($i, 2) == 1) {
			echo '<tr bgcolor="#9CBECA">';
		} else {
			echo '<tr bgcolor="#8CAAB5">';
		}
		#< Changement de couleur des lignes #####<<####
	?>
				<td class="<?php echo $classe; ?>"><?php echo fdate($row['dateout']); ?></td>
				<td class="<?php echo $classe; ?>"><?php echo $row['codematos'] ;?></td>
				<td class="<?php echo $classe; ?>"><?php echo $row['mnom'] ;?></td>
				<td class="<?php echo $classe; ?>"><?php echo $mission ;?></td>
				<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'];?>?act=unasign&idpeople=<?php echo $_REQUEST['idpeople'];?>&idmatos=<?php echo $row['idmatos'];?>">Unasign</a></td>
			</tr>
	<?php 
	$sectionz = $section;
} 
?>
	</table>
		<script type="text/javascript">
		top.document.getElementById("nbrmatos").innerHTML = 'Matos ('+<?php echo $count;?>+')';
		parent.frames['up'].document.getElementById("nbrmatos").innerHTML = 'Matos ('+<?php echo $count;?>+')';
		</script>
</fieldset>

<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
</div>
