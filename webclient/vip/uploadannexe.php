<?php
if (!empty($_POST['delfile'])) {
	if(!unlink(Conf::read('Env.root')."media/annexe/vipweb/".$_POST['delfile'])) die ("this file cannot be delete");
}

$classe = "standard";

if ($_GET['temp'] != 'temp')
{

$userfile = $_GET['userfile'] ;
$userfile = $_POST['userfile'] ;
$userfile2 = $userfile;

require(NIVO."classes/fileupload.php");


#--------------------------------#
# Variables
#--------------------------------#
// Nom du fichier qui sera genere par des valeurs venant de la base.

$nom1 = $_FILES['userfile']['name'];
# echo $nom1;
if (isset($idwebvipjob))
{
	$dbfilename = 'vip-'.$idwebvipjob."-".$nom1;
	$forminfo = '?idwebvipjob='.$idwebvipjob.'';
	$path = Conf::read('Env.root').'media/annexe/vipweb/';
}
else
{
	$path = "";
	$dbfilename = "no";
}
	$dbfilename = 'vip-'.$idwebvipjob."-".$nom1;
	$forminfo = '?idwebvipjob='.$idwebvipjob.'&s='.$s.'&act=vipmodif2a';
	$path = Conf::read('Env.root').'media/annexe/vipweb/';


if ($_POST['uploadfichier'] == 'uploadfichier') {
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
#			 $acceptable_file_types = "image/gif|image/jpeg|image/tiff|image/png";
			// Accept GIF and JPEG files
			$acceptable_file_types = "image/gif|image/jpeg|image/pjpeg|image/tiff|image/png|image/x-ms-bmp|application/rtf|application/doc|text/rtf|text/plain|text/tab-separated-values|text/richtext|application/msword|application/vnd.ms-excel|application/msexcell|application/vnd.ms-powerpoint|application/powerpoint|application/pdf|video/x-msvideo|video/quicktime|video/mpeg|application/zip|application/x-stuffit";
			// Accept ALL files
#			$acceptable_file_types = "";

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
			$my_uploader = new uploader($_SESSION['lang']);

			// OPTIONAL: set the max filesize of uploadable files in bytes
			$my_uploader->max_filesize(12000000);

			// OPTIONAL: if you're uploading images, you can set the max pixel dimensions
			#$my_uploader->max_image_size(800, 800); // max_image_size($width, $height)

			// UPLOAD the file
			if ($my_uploader->upload($upload_file_name, $acceptable_file_types, $default_extension)) {
				$success = $my_uploader->save_file($path, $mode, $filename);
			}

			if ($success) {
				// Successful upload!
				$file_name = $my_uploader->uploaded_file; // The name of the file uploaded
		#		print_file($file_name, $my_uploader->file["type"], 2);
					$vip1 = $path.$my_uploader->file["name"];
					$vip2z = 'vip-'.$idwebvipjob."-".$my_uploader->file["name"];
					$vip2 = $path.$vip2z;
					rename($vip1, $vip2);
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
 echo '<font color="white">type = '.$my_uploader->file["type"].'</font>'; #### pour voir le type du fichier envoy� (et refus� !!!)
}
?>
	<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'].$forminfo; ?>" method="POST">
	<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob;?>">
	<input type="hidden" name="uploadfichier" value="uploadfichier">
		<table align="center" border="0" cellspacing="1" cellpadding="5" width="95%">
			<tr>
				<td>
					<font color="#00427B"><?php echo $detail_2_05; ?></font>
				</td>
				<td>
					<input name="userfile" type="file">
				</td>
				<td width="150" bgcolor="#FFCC66">
					<input type="submit" value="<?php echo $tool_08a; ?>">
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<br>
					<?php echo $detail_2_06; ?>
					<br>
					<br>
					<br>
					<font color="#00427B"><?php echo $detail_2_07; ?></font>
				</td>
			</tr>
		</table>
	</form>
<?php
#	if ($acceptable_file_types) {
#		print("This form only accepts <b>" . str_replace("|", " or ", $acceptable_file_types) . "</b> files\n");
#	}
?>
	<table border="0" cellspacing="0" cellpadding="4">
	<?php
	$ledir = $path;
	$d = opendir ($ledir);
	$nomvip = 'vip-'.$idwebvipjob.'-';
	while ($name = readdir($d)) {
		if (($name != '.') and ($name != '..') and ($name != 'index.php') and ($name != 'index2.php') and ($name != 'temp') and (strchr($name, $nomvip))) {
	?>
		<tr>
			<td class="contenu">
				<a href="<?php echo $path.$name ?>" target="_blank"><?php echo $name; ?></a>
			</td>
			<form action="<?php echo $_SERVER['PHP_SELF'].$forminfo; ?>" method="POST" onSubmit="return confirm('<?php echo $tool_21; ?>')">
			<input type="hidden" name="idwebvipjob" value="<?php echo $idwebvipjob; ?>">
			<input type="hidden" name="delfile" value="<?php echo $name; ?>">
				<td class="contenu">
					<input type="submit" name="action" value="Delete" class="btn redtrash">
				</td>
			</form>
		</tr>
	<?php
		}
	}
	closedir ($d);
	 ?>
	</table>
<br>
<?php
}
?>