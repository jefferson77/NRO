<?php
# Entete de page
define('NIVO', '../../');
$Titre     = 'PEOPLE';
$Style     = 'standard';
$PhraseBas = 'Gestion de la photo ';

# Classes utilisées
include NIVO."includes/entete.php" ;
include NIVO."classes/photo.php" ;

$sfx = ($_REQUEST['picnb'] == 2) ? '-b' : '';

$picUpRawDir      = GetPhotoPath($_REQUEST['idp'],'raw','path',$sfx, '1');
$picUpRawDirFile  = GetPhotoPath($_REQUEST['idp'],'raw','path',$sfx);
$picUpCropDir     = GetPhotoPath($_REQUEST['idp'],'','path',$sfx, '1');
$picUpCropDirFile = GetPhotoPath($_REQUEST['idp'],'','path',$sfx);
$picRawFileUrl    = GetPhotoPath($_REQUEST['idp'],'raw','',$sfx);

if (file_exists($picUpRawDirFile)) {
	$picRawExist = 1;
}

### Rapatriement des infos du people
$pinfos = $DB->getRow("SELECT `idpeople`, `codepeople`, `pnom`, `pprenom`, `photo` FROM `people` WHERE `idpeople` = ".$_REQUEST['idp']);

?>
<script type="text/javascript" src="<?php echo NIVO ?>classes/12cropimage/javascript.php?idp=<?php echo $_REQUEST['idp']; ?>&sfx=<?php echo $sfx?>"></script>

<div id="leftmenu"> </div>
<div id="infozone">
	<div id="bigphoto">
	<?php
	##si l'image RAW existe, l'afficher.  Sinon, proposition d'upload.
	if ($picRawExist) {

		$rawsize   = getimagesize($picUpRawDirFile);
		$rawwidth  = $rawsize[0];
		$rawheight = $rawsize[1];

		echo'<table border="0" align="center" cellspacing="1" cellpadding="2">
			<tr  align="center" bgcolor="#003333">
				<td>Reg : '.$pinfos['codepeople'].'</td>
				<td>'.$pinfos['pprenom'].' '.$pinfos['pnom'].'</td>
			</tr>
			<tr align="center">
				<td colspan="2">
					<img src="'.$picRawFileUrl.'" border="0"><br>';

	##si le crop n'exite pas, avertissement
	if (!file_exists($picUpCropDirFile)) {
		echo '<img src="'.STATIK.'illus/attention.gif"> Cette image n\'a pas encore été recadrée.  Cliquez sur l\'icône découper';
	}
		echo'</td>
			</tr>
		</table>';
			 } else {

				echo'Il n\'y a pas encore d\'image pour ce people.  Vous pouvez en ajouter en cliquant sur le bouton <i>uploader</i> du menu.';
			} ?>
	</div>

	<div class="cropper" style="margin-left:auto;margin-right:auto;margin-top:20px"></div>
</div>

<div id="infobouton"></div>

<div id="topboutons">
	<table border="0" cellspacing="1" cellpadding="0">
		<tr>
			<td class="on"><a href="<?php echo NIVO ?>data/people/adminpeople.php?act=show&idpeople=<?php echo $_REQUEST['idp'] ?>"><img src="<?php echo STATIK ?>illus/back.gif" alt="back" width="32" height="32" border="0"><br>Retour</a></td>
			<td class="on"><a href="#" onclick="crInitCropper();hideDiv('bigphoto',1);"><img src="<?php echo STATIK ?>illus/photo.gif" alt="photo" width="32" height="32" border="0"><br>Uploader</a></td>
			<?php
			if ($picRawExist) { ?>
				<td class="on"><a href="#"  onclick="crInitCropper2('<?php echo $rawwidth ?>','<?php echo $rawheight ?>');hideDiv('bigphoto',1);"><img src="<?php echo STATIK ?>illus/cut.gif" alt="cut" width="32" height="32" border="0"><br>D&eacute;couper</a></td>
			<?php } ?>
		</tr>
	</table>
</div>
<?php

# Pied de Page
include NIVO."includes/pied.php" ;