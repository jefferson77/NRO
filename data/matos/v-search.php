<?php 
	unset($_SESSION['matosquid']);
?>
<div id="centerzonelarge">
	<div class="infosection">Rechercher</div>
	<fieldset>
		<legend>Infos de recherche</legend>
		<form action="?act=list" method="post">
			<label for="codematos">Code</label><input type="text" name="codematos" id="codematos"><br>
			<label for="mnom">Nom</label><input type="text" name="mnom" id="mnom"><br>
			<label for="dateout">date out est avant</label><input type="text" name="dateout" id="dateout"> <br>
			<label for="out">Materiel out</label><input type="checkbox" name="out" id="out" value="NULL"> <br>
			<div align="center"><input type="submit" name="Rechercher" value="Rechercher"></div>
			<input type="reset" name="Reset" value="Reset">
	  	</form>
	</fieldset>
</div>