<?php

## variable $photostate :
#0 = pas de photo
#1 = pas de crop (donc thumbnail)
#2 = crop

##INIT
define("NIVO", "../../");

require_once(NIVO."nro/fm.php");
include NIVO."classes/photo.php";

    $id         = $_GET['id'];
    $rep        = isset($_GET['rep']) ? $_GET['rep'] : '';
    $newwidth   = isset($_GET['newwidth']) ? $_GET['newwidth'] : '200';
    $newheight  = isset($_GET['newheight']) ? $_GET['newheight'] : $newwidth/1.25;
    $sfx        = isset($_GET['sfx']) ? $_GET['sfx'] : '';
    $noscissors = isset($_GET['noscissors']);
    $cropmark   = false;

    ### faut il vérifier l'existence d'un crop ?
    if ($rep != 'web') {

        if (!file_exists(GetPhotoPath($id, '', 'path', $sfx))) {
            $photostate   = 1;
        } else{
            ### lit l'image source
            $photostate   = 2;
            $src_img      = ImageCreateFromJpeg(GetPhotoPath($id, '', 'path', $sfx));
            $oldwidthpic  = imageSX($src_img);
            $oldheightpic = imageSY($src_img);
        }
    }

    ### Si pas de crop,  un raw existe t-il ?

    if ( ($rep == 'web') || ($photostate == 1) ) {

	    if ($rep != 'web') $rep = 'raw';

  	    if (file_exists(GetPhotoPath($id, $rep, 'path', $sfx))) {
            $photostate = 1;
            $iminfo     = getimagesize(GetPhotoPath($id, $rep, 'path', $sfx));

			switch($iminfo['mime']) {
				# format standard
				case"image/jpeg":
					$src_img = ImageCreateFromJpeg(GetPhotoPath($id, $rep, 'path', $sfx));
				break;
				# formats a convertir
				case"image/gif":
					$src_img = ImageCreateFromGif(GetPhotoPath($id, $rep, 'path', $sfx));
					imagejpeg($src_img, GetPhotoPath($id, $rep, 'path', $sfx));
				break;
				case"image/png":
					$src_img = ImageCreateFromPng(GetPhotoPath($id, $rep, 'path', $sfx));
					imagejpeg($src_img, GetPhotoPath($id, $rep, 'path', $sfx));
				break;
				case"image/bmp":
					$src_img = imagecreatefrombmp(GetPhotoPath($id, $rep, 'path', $sfx));
					imagejpeg($src_img, GetPhotoPath($id, $rep, 'path', $sfx));
				break;
				default:
                    $src_img = imagecreatetruecolor(200, 160); /* Création d'une image blanche */
                    $bgc     = imagecolorallocate($src_img, 255, 255, 255);
                    $tc      = imagecolorallocate($src_img, 0, 0, 0);
					imagefilledrectangle($src_img, 0, 0, 200, 160, $bgc);
					/* Affichage d'un message d'erreur */
					imagestring($src_img, 1, 5, 5, "le type d'image '".$iminfo['mime']."' n'est pas supporté", $tc);

					## place le fichier dans un autre dossier
					rename(GetPhotoPath($id, $rep, 'path', $sfx), GetPhotoPath($id, $rep, 'path', $sfx,'XX').'badfiles/'.GetPhotoPath($id, $rep, 'path', $sfx,'FileName'));
			}

		### lit l'image source
		#
            $oldwidthpic  = imageSX($src_img);
            $oldheightpic = imageSY($src_img);

            if ($noscissors==false) {
                $cropmark       = ImageCreateFromJpeg(STATIK.'illus/icon_scissors.jpg');
                $cropmarkwidth  = imageSX($cropmark);
                $cropmarkheight = imageSY($cropmark);
            }

        ###sinon, point d'interrog !
        } else  {
            $photostate     = 0;
            $cropmark       = ImageCreateFromJpeg(STATIK.'illus/icon_empty.jpg');
            $cropmarkwidth  = imageSX($cropmark);
            $cropmarkheight = imageSY($cropmark);
        }
    }
	if ($photostate != 0)  {
		### vérifie quel est le côté le plus long
		$pos_x = $pos_y = 0;

		if ($oldheightpic > $oldwidthpic) {
            $newheightpic = $newheight;
            $ratio        = $newheight / $oldheightpic;
            $newwidthpic  = round($oldwidthpic * $ratio);
            $pos_x        = ($newwidth - $newwidthpic) /2;
		} else {
            $newwidthpic  = $newwidth;
            $ratio        = $newwidth / $oldwidthpic;
            $newheightpic = round($oldheightpic * $ratio);
            $pos_y        = ($newheight - $newheightpic) /2;
		}

   }
    ##crée une image vide
    $output_img=ImageCreateTrueColor($newwidth,$newheight);
    ##copie la source sur l'image vide
    if ($photostate != 0)  {
		imagecopyresampled($output_img,$src_img,$pos_x,$pos_y,0,0,$newwidthpic,$newheightpic,$oldwidthpic,$oldheightpic);
		##détruit l'image source
		imagedestroy($src_img);
	}
	##ajoute l'icone de crop si nécessaire
	if (($cropmark) && ($rep != 'web') ){
		$cropmark_pos_x = ($newwidth - $cropmarkwidth) /2;
		$cropmark_pos_y = ($newheight - $cropmarkheight) /2;
		imagecopymerge($output_img, $cropmark, $cropmark_pos_x, $cropmark_pos_y, 0, 0, $cropmarkwidth, $cropmarkheight, 80);
	}

    header("Content-type: image/jpeg");
    imagejpeg($output_img, '', 80);
    imagedestroy($output_img);
	if ($cropmark) imagedestroy($cropmark);
?>