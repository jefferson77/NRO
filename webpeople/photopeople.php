<?php
# Entete de page
define('NIVO', '../');
$Titre = 'PEOPLE - Photo';

include "includes/entete.php" ;

### langue
include 'var'.$_SESSION['lang'].'.php';

$idp = $_SESSION['idpeople'];

### Variables de POST ou de GET
if (isset($_POST['actimage'])) {$actimage = $_POST['actimage'];} else {$actimage = $_GET['actimage'];}

### Rapatriement des infos du people
$photoname      = str_repeat('0', 6 - strlen($idp)).$idp;
$photofile      = NIVO."photoraw/".$photoname.".jpg";
$photofilethumb = NIVO."photoraw/".$photoname."-thumb.jpg";
$photoweb       = NIVO."image/rawweb/".$photoname.".jpg";
$photowebthumb  = NIVO."image/rawweb/".$photoname."-thumb.jpg";

switch ($actimage) {
	case "upl":
		if (!empty($_FILES['photo']['tmp_name'])) {
			move_uploaded_file($_FILES['photo']['tmp_name'] , $photoweb);

			### verif taille image upload en taille minimum
				$file = $photoweb;
				$finfo = getimagesize($file);
				if ($finfo[2] == 2) {
					$src = ImageCreateFromJPEG($file);

					$filesize = filesize($file);
					$org_h = imagesy($src);
					$org_w = imagesx($src);

					if (($org_h < 270) or ($org_w < 290) or ($org_h > 1000) or ($org_w > 1000)) {
						$taillephoto1 = 'non';
						if (($org_h < 270) or ($org_w < 290)) { # PETIT
							$taillephotowhy = 'petit';
						} else { # GRAND
							$taillephotowhy = 'grand';
						}
						unlink($photoweb); # delete old raw
						if (file_exists($photowebthumb)) {
							unlink($photowebthumb); # delete old raw thumb
						}
					}
				#/## verif taille image upload en taille minimum

				if ($taillephoto1 != 'non') { # seulement si taille ok
				### création du thumb #################################
				#                                                     ##
	#			$file = $image.''.$dbfilename.$my_uploader->file["extention"];
				$file = $photoweb;

				$src = ImageCreateFromJPEG($file);

				$filesize = filesize($file);
				$org_h = imagesy($src);
				$org_w = imagesx($src);

				#		print "$org_h - $org_w"; # affichage des hauteurs/largeurs de la photo uploadée

				if ($org_h > $org_w) { //see if image is horizontal or vertical
					$cfg->height = 100;  //max thumb size
					$cfg->width  = floor ($cfg->height * $org_w / $org_h);
				} else {
					$cfg->width = 100;
					$cfg->height = floor ($cfg->width * $org_h / $org_w);
				}

				$img = imagecreatetruecolor($cfg->width,$cfg->height);

				ImageCopyResampled ($img, $src, 0, 0, 0, 0, $cfg->width, $cfg->height, $org_w, $org_h );

	#			ImageJPEG($img, $image.'t-'.$dbfilename.''.$my_uploader->file["extention"], 100); //save file with 80 quality
				ImageJPEG($img, $photowebthumb, 100); //save file with 80 quality
				#		print "thumb generated and saved";

				ImageDestroy ($img); //free resources from the image
				ImageDestroy ($src);

	#			echo 'Thumb : <img src="'.$image.'t-'.$dbfilename.$my_uploader->file["extention"].'" alt="thumb" border="0">';

				#                                                     ##
				### création du thumb #################################
				} # seulement si taille ok

			} else {
				$typephoto1 = 'non';
			}
		}
	case "shw":
	default:
?>
<fieldset><legend class="orange"><?php echo $detail_05; ?> 1</legend>
<table border="0" align="center" cellspacing="1" cellpadding="2">
	<tr align="center">
		<td colspan="2">
			<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
				<input type="hidden" name="actimage" value="upl">
				<input type="hidden" name="idp" value="<?php echo $idp;  ?>">
				<input type="file" name="photo" size="40">
				<input type="submit" value="<?php echo $tool_08 ;?>">
			</form>
		</td>
	</tr>
	<tr align="center">
		<td valign="top">
				<?php echo $detail_4_06; ?><br>
			<?php if (file_exists($photofilethumb)) { ?>
				<img src="<?php echo $photofilethumb ?>" border="0">
			<?php } ?>
		</td>
		<td valign="top">
				<?php echo $detail_4_07; ?><br>
			<?php
			if ($typephoto1 == 'non') { # Type image upload en NOT OK
				echo '<br>'.$detail_4_02.'<br>';
			} else {
				if ($taillephoto1 == 'non') { # taille image upload en taille minimum NOT OK
					if ($taillephotowhy == 'petit') { # taille image upload en taille minimum NOT OK
						echo '<br>'.$detail_4_03.' :<br><br>
						<font color="red">L '.$org_w.' x H '.$org_h.' '.$detail_4_04.'</font> <br>';
					} else { # taille image upload en taille maximum NOT OK
						echo '<br>'.$detail_4_03a.' :<br><br>
						<font color="red">L '.$org_w.' x H '.$org_h.' '.$detail_4_04a.'</font> <br>';
					}
				} else {
					if (file_exists($photowebthumb)) { ?>
						<img src="<?php echo $photowebthumb ?>" border="0">
				<?php
					}
				}
			}
			?>

		</td>
	</tr>
</table>
</fieldset>
<?php } ?>
<?php
# Pied de Page
include "includes/ppied.php" ;
?>