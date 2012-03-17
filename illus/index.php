<?php
define("NIVO", "../");

# Entete de page
$Titre = 'ILLUS';
$PhraseBas = 'Liste des Illustrations utilis&eacute;es dans le site';
include NIVO."includes/entete.php" ;

?>
<div id="leftmenu">

</div>
<div id="infozone">
	<h1>Liste des illus - UTILISEES</h1>
	<a href="index.php"><b>Liste utilis&eacute;e</b></a> - <a href="index2.php"><b>Liste utilis&eacute;e - tools</b></a> - <a href="temp/index.php"><b>Liste Draft</b></a>
	<br>
		<table border="0" cellspacing="1" cellpadding="4" align="center" bgcolor="#000000">
		<tr>
			<td colspan="3"><h2 align="center">Illus</h2></td>
			<td><h2 align="center">Nom</h2></td>
		</tr>
	<?php
	$ledir = substr($_SERVER["SCRIPT_FILENAME"], 0, -9);
	$d = opendir ($ledir);

	while ($name = readdir($d)) {
	if (
		($name != '.') and 
		($name != '..') and 
		($name != 'index.php') and 
		($name != 'index2.php') and 
		($name != 'temp')
	) {

	$imginfo = getimagesize($name); 
	$width = $imginfo[0]; 
	$height = $imginfo[1]; 
	$dimension = " width=\"$width\" height=\"$height\""; 
	?>
		<tr>
			<td align="center"><img src="<?php echo $name ?>" alt="<?php echo $name ?>" border="0"<?php echo $dimension ?> align="middle"></td>
			<td bgcolor="#003366" align="center"><img src="<?php echo $name ?>" alt="<?php echo $name ?>" border="0"<?php echo $dimension ?> align="middle"></td>
			<td bgcolor="#CCFFFF" align="center"><img src="<?php echo $name ?>" alt="<?php echo $name ?>" border="0"<?php echo $dimension ?> align="middle"></td>
			<td>'<?php echo $dimension ?>'</td>
			<td>"<?php echo $name ?>"</td>
		</tr>
	<?php }
	}

	closedir ($d);

	 ?>
	</table>
</div>
<?php
# Pied de Page
include NIVO."includes/pied.php" ;
?>
