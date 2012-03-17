<div id="centerzonelarge">
<?php 
switch ($showpart) {
	# =========================================================================================
	# = Table des Erreurs TVA -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- =
	# =========================================================================================
	case 'tvaerror': ?>
	<h1>Erreurs :</h1>
		<table class="standard" border="0" width="60%" cellspacing="1" cellpadding="1" align="center">
			<tr>
				<th class="standard">Id</th>
				<th class="standard">Soci&eacute;t&eacute;</th>
				<th class="standard">Num Tva</th>
				<th class="standard">Erreur</th>
			</tr>
	<?php foreach ($hor->tvaerror as $key => $value): ?>
			<tr>
				<td class="standard"><?php echo $key ?></td>
				<td class="standard"><?php echo $value['soc'] ?></td>
				<td class="standard"><?php echo $value['num'] ?></td>
				<td class="standard"><?php echo $value['err'] ?></td>
			</tr>
	<?php endforeach ?>
		</table>
		<p>Veuillez corriger les fiches client correspondantes et réessayer</p>
		<form action="?act=generate" method="post" accept-charset="utf-8">
			<input type="hidden" name="facfromid" value="<?php echo $_POST['facfromid'] ?>">
			<input type="hidden" name="factoid" value="<?php echo $_POST['factoid'] ?>">
			<input type="hidden" name="ncfromid" value="<?php echo $_POST['ncfromid'] ?>">
			<input type="hidden" name="nctoid" value="<?php echo $_POST['nctoid'] ?>">
			<p><input type="submit" value="Réessayer"></p>
		</form>
<?php break;
# ==========================================================================================
# = Table des Nouveaux Clients -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- =
# ==========================================================================================
	case 'newclients': ?>
	<h1>Nouveau Clients :</h1>
		<table class="standard" border="0" width="60%" cellspacing="1" cellpadding="1" align="center">
			<tr>
				<th class="standard">Id</th>
				<th class="standard">Soci&eacute;t&eacute;</th>
				<th class="standard">Num Tva</th>
				<th class="standard"></th>
			</tr>
	<?php foreach ($hor->newclients as $key => $value): ?>
		<form action="?act=assignation" method="post">
			<input type="hidden" name="facfromid" value="<?php echo $_POST['facfromid'] ?>">
			<input type="hidden" name="factoid" value="<?php echo $_POST['factoid'] ?>">
			<input type="hidden" name="ncfromid" value="<?php echo $_POST['ncfromid'] ?>">
			<input type="hidden" name="nctoid" value="<?php echo $_POST['nctoid'] ?>">
			<tr>
				<td class="standard"><?php echo $key ?></td>
				<td class="standard"><?php echo $value['soc'] ?></td>
				<td class="standard"><?php echo $value['ctva'].' '.$value['tva'] ?></td>
				<td class="standard">
					<input type="hidden" name="idclient" value="<?php echo $key ?>"> 
					<input type="text" size="10" name="codecompta" maxlength="10">
					<input type="submit" name="submit" value="M"></td>
			</tr>
		</form>
	<?php endforeach ?>
		</table>
<?php break;
# ==========================================================================================
# = Fichiers Générés           -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- =
# ==========================================================================================
	case 'genresult':
		while ($name = readdir($d)) {
		if (($name != '.') and ($name != '..')) {
			echo '<br><a href="/horizon/'.$name.'" target="_blank">'.$name.'</a>';
		}}
	?> 
	<h1>Fichiers HORIZON générés :</h1>
	<?php echo $retclient ?>
	<?php echo $retencfac ?>
		<p>Placez ces fichiers dans le dossier Horizon en proc&eacute;dant comme suit :</p>
		<p><b>Pour chacun de ces fichiers :</b></p>
		<p>1. Click droit -> "Enregistrer la cible sous..."</p>
		<p>2. Choisir le Dossier : 'D:/HORIZON/data/O33'</p>
<?php break;
} ?>
</div>