<?php
# Entete de page
$niveau = '../';
$Titre = 'ILLUS';
$PhraseBas = 'Liste des Illustrations utilis&eacute;es dans le site';
#$Style = 'admin';
include $niveau."includes/entete.php" ;

# Attention a la dŽlaraction des Vars !!
$v9 = $_GET['v9'];
$v8 = $_GET['v8'];


?>
<div id="leftmenu">

</div>
<?php #  Corps de la Page ?>
<div id="infozone">
	<h1>Liste des illus - UTILISEES</h1>
<a href="index.php"><b>Liste utilis&eacute;e</b></a> - <a href="index2.php"><b>Liste utilis&eacute;e - tools</b></a> - <a href="temp/index.php"><b>Liste Draft</b></a>
<br>

<?php
# Debut copy
if ($v9 == 'move')
{
	if(!copy("$v8", "temp/files/$v8")) die ("this file cannot be delete");
	$v9='delete';
}
# Fin copy
# Debut delete
if ($v9 == 'delete')
{
	if(!unlink("$v8")) die ("this file cannot be delete");
}
# Fin delete
?>

	<table border="0" cellspacing="1" cellpadding="4" align="center" bgcolor="#000000">
	<tr>
		<td colspan="3"><h2 align="center">Illus</h2></td>
		<td><h2 align="center">Nom</h2></td>
		<td><h2 align="center">Action</h2></td>
	</tr>
<?php
$ledir = substr($_SERVER["SCRIPT_FILENAME"], 0, -10);
$d = opendir ($ledir);

while ($name = readdir($d)) {
if (
	($name != '.') and 
	($name != '..') and 
	($name != 'index.php') and 
	($name != 'index2.php') and 
	($name != 'temp')
) {
?>
	<tr>
		<td align="center"><img src="<?php echo $name ?>" alt="<?php echo $name ?>" border="0" align="middle"></td>

		<td bgcolor="#003366" align="center"><img src="<?php echo $name ?>" alt="<?php echo $name ?>" border="0" align="middle"></td>
		<td bgcolor="#CCFFFF" align="center"><img src="<?php echo $name ?>" alt="<?php echo $name ?>" border="0" align="middle"></td>
		<td>"<?php echo $name ?>"</td>
		<td><a href="index2.php?v9=move&v8=<?php echo $name ?>"><font color="yellow">Move</font></a></td>
	</tr>
<?php }
}

closedir ($d);

 ?>
</table>
	</div>

<?php
# Pied de Page
include $niveau."includes/pied.php" ;
?>
