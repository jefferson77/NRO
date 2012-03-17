<?php
session_id($_GET['SESSID']); if( !isset($_SESSION) ) session_start();
define('NIVO', '../../');
# Classes utilisées
require_once(NIVO."nro/fm.php");
include NIVO."classes/photo.php" ;

	if ($_FILES['Filedata']['name'] && ($log = fopen('/NRO/log/upload.log', 'a') ) )
	{
		$file = $_FILES['Filedata']['tmp_name'];
		$error = false;

		/**
		 * THESE ERROR CHECKS ARE JUST EXAMPLES HOW TO USE THE REPONSE HEADERS
		 * TO SEND THE STATUS OF THE UPLOAD, change them!
		 *
		 */

		if (!is_uploaded_file($file) || ($_FILES['Filedata']['size'] > 2 * 1024 * 1024) )
		{
			$error = '400 Bad Request';
		}
		if (!$error && !($size = @getimagesize($file)))
		{
			$error = '409 Conflict';
		}
		if (!$error && !in_array($size[2], array(1, 2, 3, 7, 8) ) )
		{
			$error = '415 Unsupported Media Type';
		}
		if (!$error && ($size[0] < 25) || ($size[1] < 25))
		{
			$error = '417 Expectation Failed';
		}

		if ($error)
		{
			/**
			 * ERROR DURING UPLOAD, one of the validators failed
			 * 
			 * see FancyUpload.js - onError for header handling
			 */
			header('HTTP/1.0 ' . $error);
			die('Error ' . $error);
		}
		else
		{
			/**
			 * UPLOAD SUCCESSFULL AND VALID
			 *
			 * Use move_uploaded_file here to save the uploaded file in your directory
			 */
			
			$sfx='';
			//si la var de session existe, c'est que c'est le deuxième fichier à uploader.
			if ($_SESSION['firstUpFile']) {
				$sfx='-b';
			} else { $sfx='';}
			
			$dest = GetPhotoPath($_SESSION['idpeople'],'web','path',$sfx);
			
			move_uploaded_file($file, $dest);
			chmod($dest, 0777);
			
			//si la var de session n'existe pas, c'est que c'est le 1er fichier à uploader
			//et donc on la crée pour pouvoir traiter le 2e fichier après.
			if (!isset($_SESSION['firstUpFile'])) {
				$_SESSION['firstUpFile'] = 1;
			} else {
				unset($_SESSION['firstUpFile']);
			}

		}

		die('Upload Successfull');

	}
?>