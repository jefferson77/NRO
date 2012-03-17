<?php
# Entete de page
define('NIVO', '../../'); 
$Titre = 'ILLUS';
$PhraseBas = 'R&eacute;serve d\'illustrations';
#$Style = 'admin';
include NIVO."includes/entete.php" ;
?>
<!-- Barre des Boutons -->
<div id="topboutons">
<table border="0" cellspacing="1" cellpadding="0">
	<tr>
		<td class="on"><a href="../index.php"><br><br>Utilis&eacute;s</a></td>
		<td class="on"><a href="../index2.php"><br><br>Util Tools</a></td>
		<td class="on"><a href="index.php"><br><br>Drafts</a></td>
	</tr>
</table> 
</div>
<!-- Menu de Gauche -->
<div id="leftmenu">

</div>
<?php #  Corps de la Page ?>
<div id="infozone">
<h1>Liste des illus - DRAFT SPACE</h1>
<br>
<?php
# Debut copy
if ($_GET['v9'] == 'delete')
{
	if(!copy("files/".$_GET['v8'], "del/".$_GET['v8'])) die ("this file cannot be delete");
	$_GET['v9']='delete';
}
# Fin copy
# Debut copy
if ($_GET['v9'] == 'move')
{
	if(!copy("files/".$_GET['v8'], "../".$_GET['v8'])) die ("this file cannot be delete");
	$_GET['v9']='delete';
}
# Fin copy
# Debut delete
if ($_GET['v9'] == 'delete')
{
	if(!unlink("files/".$_GET['v8'])) die ("this file cannot be delete");
}
# Fin delete

require(NIVO."classes/fileupload.php");
#--------------------------------#
# Variables
#--------------------------------#
// Nom du fichier qui sera genere par des valeurs venant de la base.

	$path = 'files/';
	$upload_file_name = "userfile";
	# $acceptable_file_types = "";

	$default_extension = "";
	$mode = 1;


#--------------------------------#
# PHP
#--------------------------------#
	if (isset($_REQUEST['submitted'])) {
			
		// Create a new instance of the class
		$my_uploader = new uploader('fr');
		
		// OPTIONAL: set the max filesize of uploadable files in bytes
		$my_uploader->max_filesize(200000); // 200 kb
		
		// OPTIONAL: if you're uploading images, you can set the max pixel dimensions 
		$my_uploader->max_image_size(800, 800); // max_image_size($width, $height)
		
		// UPLOAD the file
		if ($my_uploader->upload($upload_file_name, $acceptable_file_types, $default_extension)) {
			$my_uploader->save_file($path, $mode);
		}
				
		if ($my_uploader->error) {
			echo $my_uploader->error . "<br><br>";
		
		} else {
			// Successful upload!
			print($my_uploader->file['name'] . " was successfully uploaded!</a><br>");
			
 		}
 	}
?>
<!-- Tableau des illus START-->
	<table border="0" cellspacing="1" cellpadding="4" align="center" bgcolor="#000000">
	<tr>
		<td colspan="3"><h2 align="center">Illus</h2></td>
		<td><h2 align="center">Nom</h2></td>
		<td><h2 align="center">Action</h2></td>
	</tr>
<?php
$ledir = substr($_SERVER["SCRIPT_FILENAME"], 0, -9).'files/';
$d = opendir ($ledir);

while ($name = readdir($d)) {
if (
	($name != '.') and 
	($name != '..') and 
	($name != 'index.php')
) {
?>
	<tr>
		<td align="center"><img src="files/<?php echo $name ?>" alt="<?php echo $name ?>" border="0" align="middle"></td>
		<td bgcolor="#003366" align="center"><img src="files/<?php echo $name ?>" alt="<?php echo $name ?>" border="0" align="middle"></td>
		<td bgcolor="#CCFFFF" align="center"><img src="files/<?php echo $name ?>" alt="<?php echo $name ?>" border="0" align="middle"></td>
		<td>"<?php echo $name ?>"</td>
		<td><a href="index.php?v9=delete&v8=<?php echo $name ?>"><font color="red">DEL</font></a> &nbsp;-&nbsp; <a href="index.php?v9=move&v8=<?php echo $name ?>"><font color="yellow">Move</font></a></td>
	</tr>
<?php }
}

closedir ($d);

 ?>
</table>
<!-- Tableau des illus STOP -->
<br>
<?php
	if ($acceptable_file_types) {
		print("This form only accepts <b>" . str_replace("|", " or ", $acceptable_file_types) . "</b> files\n");
	}
?>
</div>
<div id="infobouton">
<form enctype="multipart/form-data" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
	<input type="hidden" name="submitted" value="true">
	Upload this file: 
	<input name="userfile" type="file"> 
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Send File">
</form>
</div>
<?php
# Pied de Page
include NIVO."includes/pied.php" ;
?>
