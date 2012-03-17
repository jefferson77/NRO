<div id="centerzonelarge">
	<div class="boite" style="margin: auto;width: 350px;">
		<fieldset id="ajouter_un_pdf">
			<legend>Ajouter un PDF</legend>
			<form action="?act=addPDF" method="post" accept-charset="utf-8" enctype="multipart/form-data">
				<label for="ftype">Type</label>
					<input type="radio" name="ftype" value="fiche"> Fiche de Salaire 
					<input type="radio" name="ftype" value="281.10"> 281.10 <br>
				<label for="newfile">Fichier</label><input type="file" name="newfile" value=""><br>
				<label for="mois">Mois</label><select name="mois"><?php for ($i=1; $i <= 12; $i++) echo '<option value="'.prezero($i, 2).'">'.utf8_encode(strftime("%B", strtotime("2010-".prezero($i, 2)."-01"))).'</option>'; ?></select><br>
				<label for="annee">Année</label><select name="annee"><?php for ($i=0; $i <= 10; $i++) echo '<option value="'.(date("Y") - $i).'">'.(date("Y") - $i).'</option>'; ?></select><br>
				<p align="center"><input type="submit" value="Envoyer"></p>
			</form>
		</fieldset>
		 <p>L'envoi du PDF va prendre une ou deux minutes. soyez patient.</p>
	</div>
</div>