<?php
define('NIVO', '../../../');

# Classes
include NIVO.'classes/anim.php';

## Entete
$Style = 'anim';
include NIVO.'includes/ifentete.php';
include NIVO.'print/anim/facture/facture_inc.php';


# init vars
$creation = $_POST['creation'];
if (!empty($_POST['idanimjob'])) $_GET['idanimjob'] = $_POST['idanimjob'];
if (empty($_POST['updated'])) $_POST['updated'] = '';

$jobnum = prezero($_GET['idanimjob'],5);
$pathpdf = 'document/offre/anim/';

###############################################################################################################################
### Manips listing offre #####################################################################################################
#############################################################################################################################
	
	########## update JOB
	if ($_POST['updated'] == 'yes') {
		$DB->inline("UPDATE animjob SET datedevis = NOW() WHERE idanimjob = ".$_REQUEST['idanimjob']);
		$creation = 'no';
	}
	
	########## search JOB
	$datedevis = $DB->getOne("SELECT datedevis FROM `animjob` WHERE `idanimjob` = ".$_GET['idanimjob']);
	if (empty($datedevis ))  $creation = 'yes';
	
	### pour création d'une nouvelle offre
	if ($creation == 'yes') {
		#### Mise à jour de la fiche : datedevis
		if (empty($datedevis)) $datedevis = date("Y-m-d");#pour comparaison dans tableau
	
###############################################################################################################################
### Impression de l'offre ####################################################################################################
#############################################################################################################################
		print_fac_anim($_REQUEST['idanimjob'],'yes','','OFFRE');
	
	}
	?>
	<br>
	<form action="<?php echo $_SERVER['PHP_SELF'].'?idanimjob='.$_GET['idanimjob'];?>" method="post">
		<input type="hidden" name="updated" value="yes">
	
		<table border="0" cellspacing="1" cellpadding="4" align="center" bgcolor="#ffffff">
		<tr>
			<td><h2 align="center">Offres du job <?php echo $jobnum; ?></h2></td>
		</tr>
	<?php
	$files = dirFiles(Conf::read('Env.root').$pathpdf,"/^offre-".prezero($_GET['idanimjob'],5)."-(\d{8})\.pdf$/");

	foreach ($files as $name) {
		preg_match("/^offre-".prezero($_GET['idanimjob'],5)."-(\d{4})(\d{2})(\d{2})\.pdf$/", $name, $matches);
		# format de date du fichier en yyyy-mm-dd et dd/mm/yyyy
		$nomfichier2 = fdate($matches[1].'-'.$matches[2].'-'.$matches[3]);
		if ($nomfichier == $datedevis) { $nomfichier2 = '<font color="#003399"><b>'.$nomfichier2.'</b></font>'; $checked = 'checked';} else {$checked = '';}
	?>
		<tr>
			<td align="center">
				<A href="<?php echo NIVO.$pathpdf.$name ;?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"> <?php echo $nomfichier2; ?></A> <input type="radio" name="datedevis" value="<?php echo $nomfichier; ?>" <?php echo $checked; ?>>
			</td>
		</tr>
	<?php }
	#######/ Création du tableau des offres
	 ?>
		<tr>
			<td align="center">
				<input type="submit" name="updat" value="Mettre la base de donn&eacute;e &agrave; jour">
			</td>
		</tr>
	
	</table>
	</form>
	<br>
	<br>
	<div align="center">
	<form action="<?php echo $_SERVER['PHP_SELF'].'?idanimjob='.$_GET['idanimjob'];?>" method="post">
		<input type="hidden" name="creation" value="yes">
		<input type="submit" name="nouvelle" value="Cr&eacute;er une nouvelle offre">
	</form>
	<br>
	<br><a href="javascript:window.close();"><b>&gt; Fermer &lt;</b></a>
	<br>
	<br>
	</div>
	<table border="0" cellspacing="1" cellpadding="4" align="center" bgcolor="#ffffff">
		<tr>
			<td>
				<font color="black">
					Note : Le fichier en <img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"> <font color="#003399"><b>Bleu</b></font> est le fichier de l'offre qui a &eacute;t&eacute; envoy&eacute;e au client. (Pas un brouillon mais la v&eacute;ritable offre)<br><br>
					Apr&egrave;s la cr&eacute;ation d'une nouvelle offre, il faut, si n&eacute;cessaire, mettre la base de donn&eacute;e &agrave; jour afin de la valider comme &eacute;tant "l'offre qui a &eacute;t&eacute; envoy&eacute;e au client".
				</font>
			</td>
		</tr>
	</table>
<?php include NIVO.'includes/ifpied.php'; ?>
