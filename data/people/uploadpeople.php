<?php
# Entete de page
define('NIVO', '../../'); 
$css = standard;
include NIVO."includes/ifentete.php" ;

?>

<?php 
if ($_GET['temp'] == 'temp') 
{
?>
<?php $classe = "standard" ?>
	<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<td colspan="9">La gestion des donn&eacute;es particuli&egrave;res sera accesible apr&egrave;s cr&eacute;ation de la fiche g&eacute;n&eacute;rale</td>
		</tr>
	</table>
<?php
}
# FIN PAGE TEMP
if ($_GET['temp'] != 'temp') 
{
?>

<?php

$v3 = "people";	# <-- Changer le type ici PAT&MOUNE
$idpeople = $_GET['idpeople'] ;
$userfile = $_GET['userfile'] ;
$userfile = $_POST['userfile'] ;

require(NIVO."classes/fileupload.php");

#--------------------------------#
# Variables
#--------------------------------#
// Nom du fichier qui sera genere par des valeurs venant de la base.

if ($_GET['photo'] == 'view') {
	$idpeople = $_GET['idpeople'] ;
	$detailp = new db();
	$detailp->inline("SELECT * FROM `people` WHERE `idpeople` = $idpeople");
	$infos = mysql_fetch_array($detailp->result) ; 
	$photo=$infos['photo'];
echo '
	<img src="'.NIVO.'photo/'.$photo.'" border="0" alt="">
';
}


if (isset($idpeople))
{
	$dbfilename = $idpeople."-".$v3;    					
	$forminfo = '?idpeople='.$idpeople.'';
	$path = '../../photo/';
}
else
{
	$path = "";
	$dbfilename = "no";
}
	$dbfilename = $idpeople."-".$v3.'.jpg';    					
	$forminfo = '?idpeople='.$idpeople.'';
	$path = '../../photo/';




// The path to the directory where you want the 
// uploaded files to be saved. This MUST end with a 
// trailing slash unless you use $path = ""; to 
// upload to the current directory. Whatever directory
// you choose, please chmod 777 that directory.


// The name of the file field in your form.

	$upload_file_name = "userfile";

// ACCEPT mode - if you only want to accept
// a certain type of file.
// possible file types that PHP recognizes includes:
//
// OPTIONS INCLUDE:
//  text/plain
//  image/gif
//  image/jpeg
//  image/png
	
	// Accept ONLY jpeg's
	# $acceptable_file_types = "image/jpeg";
	
	// Accept GIF and JPEG files
	# $acceptable_file_types = "image/gif|image/jpeg";

	// Accept ALL files
	$acceptable_file_types = "";

// If no extension is supplied, and the browser or PHP
// can not figure out what type of file it is, you can
// add a default extension - like ".jpg" or ".txt"

	$default_extension = "";

// MODE: if your are attempting to upload
// a file with the same name as another file in the
// $path directory
//
// OPTIONS:
//   1 = overwrite mode
//   2 = create new with incremental extention
//   3 = do nothing if exists, highest protection

	$mode = 1;


#--------------------------------#
# PHP
#--------------------------------#
	// Create a new instance of the class
	$my_uploader = new uploader;
	
	// OPTIONAL: set the max filesize of uploadable files in bytes
	$my_uploader->max_filesize(900000);
	
	// OPTIONAL: if you're uploading images, you can set the max pixel dimensions 
	$my_uploader->max_image_size(800, 800); // max_image_size($width, $height)
	
	// UPLOAD the file
	if ($my_uploader->upload($upload_file_name, $acceptable_file_types, $default_extension)) {
		$success = $my_uploader->save_file($path, $mode, $filename);
	}
		
	if ($success) {
		// Successful upload!
		$file_name = $my_uploader->uploaded_file; // The name of the file uploaded
#		print_file($file_name, $my_uploader->file["type"], 2);
	} else {
		// ERROR uploading...
 		if($my_uploader->errors) {
			while(list($key, $var) = each($my_uploader->errors)) {
				echo $var . "<br>";
			}
 		}
 	}

#--------------------------------#
# HTML FORM
#--------------------------------#
## Modification de la ligne #############
if ($_POST['act'] == 'Modifier') {

	$dbfilename1 = $dbfilename.$my_uploader->file["extention"];
	$modif = new db();
	$modif->inline("UPDATE `people` SET `photo` = '$dbfilename1' WHERE `idpeople` = '".$_POST['idpeople']."';");
}

if (isset($_GET['idpeople'])) {
	$idpeople = $_GET['idpeople'] ;
	$detail = new db();
	$detail->inline("SELECT * FROM `people` WHERE `idpeople` = $idpeople");
	$infos = mysql_fetch_array($detail->result) ; 
}

if (isset($_POST['idpeople'])) {
	$idpeople = $_POST['idpeople'] ;
	$detail = new db();
	$detail->inline("SELECT * FROM `people` WHERE `idpeople` = $idpeople");
	$infos = mysql_fetch_array($detail->result) ; 
}
?>
	<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'].$forminfo; ?>" method="POST">
	<input type="hidden" name="idpeople" value="<?php echo $idpeople;?>"> 
	<input type="hidden" name="act" value="Modifier"> 
		<table align="center" border="0" cellspacing="5" cellpadding="1" width="80%">
			<tr>
				<td>			

					Choisir une photo:<br>
					<input name="userfile" type="file" size="10">
					<br>
					<input type="submit" value="Envoyer">
				</td>
			</tr>
		</table>
	</form>
<?php
	if ($acceptable_file_types) {
		print("This form only accepts <b>" . str_replace("|", " or ", $acceptable_file_types) . "</b> files\n");
	}

#--------------------------------#
# EXTRA DISPLAY FUNCTION
#--------------------------------#
	function print_file($file, $type) {
		if($file) {
			if(preg_match("/image/", $type)) {
				echo "<img src=\"" . $file . "\" border=\"0\" alt=\"\">";
			} else {
				$userfile = fopen($file, "r");
				while(!feof($userfile)) {
					$line = fgets($userfile, 255);
					echo $line;
				}
			}
		}
	}
?>
<?php
}
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>