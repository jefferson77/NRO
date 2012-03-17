<div id="centerzonelarge">
<?php
$classe = 'standard' ;

$show = '25'; # Nombre de lignes a afficher

### Première étape : formulaire de recherche de Lieu
$_SESSION['lieuquid'] = $quid;
$_SESSION['lieusort'] = $sort;
# VIDER LA SESSION
?>
<h1 align="center">Recherche des Lieux ANIM</h1>
<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&etape=listeshop&idmerch='.$idmerch.'&sel=lieu';?>" method="post">
	<input type="hidden" name="etape" value="listeshop"> 
<fieldset>
	<legend>
		Infos de recherche
	</legend>
	<label for="codeshop">Code</label><input type="text" name="codeshop" id="codeshop"><br>
	<label for="societe">Soci&eacute;t&eacute;</label><input type="text" name="societe" id="societe"><br>
	<label for="snom">Contact</label><input type="text" name="snom" id="snom"><br>
	<label for="cp">CP</label><input type="text" name="cp" id="cp"><br> 
	<label for="ville">Ville</label><input type="text" name="ville" id="ville"> 
</fieldset>
<div align="center"><input type="submit" name="Rechercher" value="Rechercher"></div>
</form>
	<br>
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="40%">
		<tr bgcolor="#FF6699">
			<td class="<?php echo $classe; ?>" align="center"> 
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=lieu';?>" method="post">
					<input type="hidden" name="idmerch" value="<?php echo $idmerch ;?>"> 
					<input type="hidden" name="idshop" value=""> 
				<input type="submit" name="Selectionner" value="Remove LIEU from ANIM"> 
				</form>
			</td>
		</tr>
	</table>
</div>