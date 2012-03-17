<?php
	include NIVO.'classes/test.php';

################### Code PHP ########################
if (!empty($idpeople)) {
	$infos = $DB->getRow("SELECT * FROM `people` WHERE `idpeople` = ".$idpeople);
	$DB->inline("UPDATE webneuro.webpeople SET `webetat` = 5 WHERE `idpeople` ='$idpeople' LIMIT 1;");
	$infosweb = $DB->getRow("SELECT * FROM webneuro.webpeople WHERE `idpeople` = $idpeople");
	$idwebpeople = $infosweb['idwebpeople'];
}
################### Fin Code PHP ########################
?>
<form action="?act=webmodif" method="post">
	<input type="hidden" name="idwebpeople" value="<?php echo $idwebpeople;?>">
	<input type="hidden" name="idpeople" value="<?php echo $idpeople;?>">
	<input type="hidden" name="datemodifweb" value="<?php echo date("Y-m-d");?>">
	<input type="hidden" name="champs[]" value="datemodifweb">
<div id="leftmenu">
	<div id="idsquare">
		<table border="0" cellspacing="1" cellpadding="2" align="center" width="100%">
			<tr><td align="center">Modif web: <br><?php echo fdatetime($infosweb['datemodif']); ?></td></tr>
		</table>
	</div>
</div>
<div id="infozone">
	<table border="0" cellspacing="1" cellpadding="1" align="center" width="98%">
		<tr>
			<td align="center" width="50%">
					photo 1 : Actuelle<br>
					<?php echo '<img src="'.NIVO.'data/people/photo.php?id='.$idpeople.'" alt=""><br><br>';
					$id = $_GET['idpeople'];
					$photoweb = GetPhotoPath($idwebpeople, 'web', 'path');
					if (file_exists($photoweb)) {
						$photo1 = true;
						echo '<input type="radio" name="choixphoto1" value="ancienne">';
					} else {
						$photo1 = false;
						echo '<input type="radio" name="choixphoto1" value="ancienne" DISABLED checked>';
					}
					?>
			</td>
			<td align="center">
				<?php
					if (file_exists($photoweb)) {
						echo 'photo 1 : Nouvelle<br>';
						echo '<img src="'.NIVO.'data/people/photo.php?id='.$idwebpeople.'&rep=web" alt=""><br><br>';
						echo '<input type="radio" name="choixphoto1" value="nouvelle" checked >';
					}
				?>
			</td>
			<td>
			</td>
		</tr>
		<tr class="standard">
			<td colspan="3">
				<hr color="white" size="1">
			</td>
			<td>
			</td>
		</tr>
		<tr>
			<td align="center" width="50%">
				photo 2 : Actuelle<br>
					<?php echo '<img src="'.NIVO.'data/people/photo.php?id='.$idpeople.'&sfx=-b" alt=""><br><br>';

						$photoweb2 = GetPhotoPath($idwebpeople, 'web', 'path', '-b');
						if (file_exists($photoweb2)) {
							$photo2 = true;
							echo '<input type="radio" name="choixphoto2" value="ancienne">';
						} else {
							$photo2 = false;
							echo '<input type="radio" name="choixphoto2" value="ancienne" DISABLED checked>';
						}
					?>
			</td>
			<td align="center">
				<?php
					if (file_exists($photoweb2)) {
						echo 'photo 2 : Nouvelle<br>';
						echo '<img src="'.NIVO.'data/people/photo.php?id='.$idwebpeople.'&rep=web&sfx=-b" alt=""><br><br>';
						echo '<input type="radio" name="choixphoto2" value="nouvelle" checked >';
					}
				?>
			</td>
			<td>
			</td>
		</tr>
		<?php
		if ($photo1 or $photo2) {
		  echo '<tr class="standard">
			<td colspan="3">
				<hr color="white" size="1">
			</td>
			<td>
			</td>
		  </tr>
		  <tr>
			<td align="center" width="50%">
			</td>
			<td align="right">';
				if ($photo1 && $photo2)
					print("La nouvelle photo 1 ramplacera l'actuelle photo 2 &nbsp;&nbsp;<br>
					       La nouvelle photo 2 ramplacera l'actuelle photo 1 &nbsp;&nbsp;<br>");
				elseif ($photo1)
					print("La nouvelle photo 1 ramplacera l'actuelle photo 2 &nbsp;&nbsp;<br>");
				elseif ($photo2)
					print("La nouvelle photo 2 ramplacera l'actuelle photo 1 &nbsp;&nbsp;<br>");
			echo '</td>
			<td>
				<input type="checkbox" name="switchphoto" value="oui">
			</td>
		  </tr>';
		};
		?>
		<tr class="standard">
			<td colspan="3">
				<hr color="white" size="1">
			</td>
			<td>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<table border="0" cellspacing="1" cellpadding="2" align="center" width="100%">
					<tr class="standard">
						<td colspan="2">
							<div class="infosection">
								Infos G&eacute;n&eacute;rales
							</div>
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['sexe'] != $infosweb['sexe']) { echo 'class="red"' ;} ?>>
							Sexe
						</td>
						<td>
							<?php echo $infos['sexe']; ?>
						</td>
						<td>
							<?php
							echo '<input type="radio" name="sexe" value="f" '; if (($infosweb['sexe'] == 'f') OR ($infosweb['sexe'] == '')) { echo 'checked';} echo'> F';
							echo '<input type="radio" name="sexe" value="m" '; if ($infosweb['sexe'] == 'm') { echo 'checked';} echo'> M';
							?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="sexe">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['pnom'] != $infosweb['pnom']) { echo 'class="red"' ;} ?>>
							Nom
						</td>
						<td>
							<?php echo $infos['pnom']; ?>
						</td>
						<td>
							<input type="text" size="33" name="pnom" value="<?php echo $infosweb['pnom']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="pnom">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['pprenom'] != $infosweb['pprenom']) { echo 'class="red"' ;} ?>>
							Pr&eacute;nom
						</td>
						<td>
							<?php echo $infos['pprenom']; ?>
						</td>
						<td>
							<input type="text" size="33" name="pprenom" value="<?php echo $infosweb['pprenom']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="pprenom">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="4">
							<hr color="white" size="1">
						</td>
						<td>
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['adresse1'] != $infosweb['adresse1']) { echo 'class="red"' ;} ?>>
							Adresse 1
						</td>
						<td <?php if (
							($infos['num1'] != $infosweb['num1']) or
							($infos['bte1'] != $infosweb['bte1'])
							) { echo 'class="red"' ;} ?>>
							<?php echo $infos['adresse1']; ?> &nbsp; num : &nbsp; <?php echo $infos['num1']; ?> &nbsp; bte : &nbsp; <?php echo $infos['bte1']; ?>
						</td>
						<td>
							<input type="text" size="35" name="adresse1" value="<?php echo $infosweb['adresse1']; ?>">
							 &nbsp; num : &nbsp; <input type="text" size="5" name="num1" value="<?php echo $infosweb['num1']; ?>">
							 &nbsp; bte : &nbsp; <input type="text" size="2" name="bte1" value="<?php echo $infosweb['bte1']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="adresse1"> &nbsp;
							<input type="checkbox" name="champs[]" checked value="num1"> &nbsp;
							<input type="checkbox" name="champs[]" checked value="bte1">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['cp1'] != $infosweb['cp1']) { echo 'class="red"' ;} ?>>
							Code postal &nbsp; Ville
						</td>
						<td>
							<?php echo $infos['cp1']; ?> &nbsp; <?php echo $infos['ville1']; ?>
						</td>
						<td>
							<input type="text" size="10" name="cp1" value="<?php echo $infosweb['cp1']; ?>">
							 &nbsp; <input type="text" size="30" name="ville1" value="<?php echo $infosweb['ville1']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="cp1">  &nbsp;
							<input type="checkbox" name="champs[]" checked value="ville1">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['pays1'] != $infosweb['pays1']) { echo 'class="red"' ;} ?>>
							Pays
						</td>
						<td>
							<?php echo $infos['pays1']; ?>
						</td>
						<td>
							<input type="text" size="20" name="pays1" value="<?php echo $infosweb['pays1']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="pays1">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="4">
							<hr color="white" size="1">
						</td>
						<td>
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['adresse2'] != $infosweb['adresse2']) { echo 'class="red"' ;} ?>>
							Adresse 2
						</td>
						<td>
							<?php echo $infos['adresse2']; ?> &nbsp; num : &nbsp; <?php echo $infos['num2']; ?> &nbsp; bte : &nbsp; <?php echo $infos['bte2']; ?>
						</td>
						<td>
							<input type="text" size="35" name="adresse2" value="<?php echo $infosweb['adresse2']; ?>">
							 &nbsp; num : &nbsp; <input type="text" size="5" name="num2" value="<?php echo $infosweb['num2']; ?>">
							 &nbsp; bte : &nbsp; <input type="text" size="2" name="bte2" value="<?php echo $infosweb['bte2']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="adresse2"> &nbsp;
							<input type="checkbox" name="champs[]" checked value="num2"> &nbsp;
							<input type="checkbox" name="champs[]" checked value="bte2">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['cp2'] != $infosweb['cp2']) { echo 'class="red"' ;} ?>>
							Code postal &nbsp; Ville
						</td>
						<td>
							<?php echo $infos['cp2']; ?> &nbsp; <?php echo $infos['ville2']; ?>
						</td>
						<td>
							<input type="text" size="10" name="cp2" value="<?php echo $infosweb['cp2']; ?>">
							 &nbsp; <input type="text" size="30" name="ville2" value="<?php echo $infosweb['ville2']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="cp2">  &nbsp;
							<input type="checkbox" name="champs[]" checked value="ville2">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['pays2'] != $infosweb['pays2']) { echo 'class="red"' ;} ?>>
							Pays
						</td>
						<td>
							<?php echo $infos['pays2']; ?>
						</td>
						<td>
							<input type="text" size="20" name="pays2" value="<?php echo $infosweb['pays2']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="pays2">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="4">
							<hr color="white" size="1">
						</td>
						<td>
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['province'] != $infosweb['province']) { echo 'class="red"' ;} ?>>
							Province
						</td>
						<td>
							<?php echo $infos['province']; ?>
						</td>
						<td>
						<?php
							echo '<select name="province" size="1">
								<option value="Bruxelles"';if ($infosweb['province'] == 'Bruxelles') {echo 'selected';}echo '>Bruxelles</option>
								<option value="Antwerpen"';if ($infosweb['province'] == 'Antwerpen') {echo 'selected';}echo '>Antwerpen</option>
								<option value="Oost-Vlanderen"';if ($infosweb['province'] == 'Oost-Vlanderen') {echo 'selected';}echo '>Oost-Vlanderen</option>
								<option value="West-Vlanderen"';if ($infosweb['province'] == 'West-Vlanderen') {echo 'selected';}echo '>West-Vlanderen</option>
								<option value="Vlaams Brabant"';if ($infosweb['province'] == 'Vlaams Brabant') {echo 'selected';}echo '>Vlaams Brabant</option>
								<option value="Brabant Wallon"';if ($infosweb['province'] == 'Brabant Wallon') {echo 'selected';}echo '>Brabant Wallon</option>
								<option value="Namur"';if ($infosweb['province'] == 'Namur') {echo 'selected';}echo '>Namur</option>
								<option value="Hainaut"';if ($infosweb['province'] == 'Hainaut') {echo 'selected';}echo '>Hainaut</option>
								<option value="Liege"';if ($infosweb['province'] == 'Liege') {echo 'selected';}echo '>Li&egrave;ge</option>
								<option value="Limburg"';if ($infosweb['province'] == 'Limburg') {echo 'selected';}echo '>Limburg</option>
								<option value="Luxembourg"';if ($infosweb['province'] == 'Luxembourg') {echo 'selected';}echo '>Luxembourg</option>
								<option value="GDL"';if ($infosweb['province'] == 'GDL') {echo 'selected';}echo '>GDL</option>
							</select>
							';
						?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="province">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="4">
							<hr color="white" size="1">
						</td>
						<td>
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2">
							<div class="infosection">
								Contact
							</div>
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['tel'] != $infosweb['tel']) { echo 'class="red"' ;} ?>>
							Tel
						</td>
						<td>
							<?php echo $infos['tel']; ?>
						</td>
						<td>
							<input type="text" size="33" name="tel" value="<?php echo $infosweb['tel']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="tel">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['fax'] != $infosweb['fax']) { echo 'class="red"' ;} ?>>
							Fax
						</td>
						<td>
							<?php echo $infos['fax']; ?>
						</td>
						<td>
							<input type="text" size="33" name="fax" value="<?php echo $infosweb['fax']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="fax">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['gsm'] != $infosweb['gsm']) { echo 'class="red"' ;} ?>>
							GSM
						</td>
						<td>
							<?php echo $infos['gsm']; ?>
						</td>
						<td>
							<input type="text" size="33" name="gsm" value="<?php echo $infosweb['gsm']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="gsm">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['email'] != $infosweb['email']) { echo 'class="red"' ;} ?>>
							Email
						</td>
						<td>
							<?php echo $infos['email']; ?>
						</td>
						<td>
							<input type="text" size="33" name="email" value="<?php echo $infosweb['email']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="email">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['webpass'] != $infosweb['webpass']) { echo 'class="red"' ;} ?>>
							Web password
						</td>
						<td>
							<?php echo $infos['webpass']; ?>
						</td>
						<td>
							<input type="text" size="33" name="webpass" value="<?php echo $infosweb['webpass']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="webpass">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['lbureau'] != $infosweb['lbureau']) { echo 'class="red"' ;} ?>>
							Langue Bureau
						</td>
						<td>
							<?php echo $infos['lbureau']; ?>
						</td>
						<td>
							<?php
							echo '<input type="radio" name="lbureau" value="FR" '; if ($infosweb['lbureau'] == 'FR') { echo 'checked';} echo'> FR';
							echo '<input type="radio" name="lbureau" value="NL" '; if ($infosweb['lbureau'] == 'NL') { echo 'checked';} echo'> NL';
							?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="lbureau">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="4">
							<hr color="white" size="1">
						</td>
						<td>
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['categorie'] != $infosweb['categorie']) { echo 'class="red"' ;} ?>>
							Categories
						</td>
						<td>
							<?php echo $infos['categorie']; ?>
						</td>
						<td>
						<?php
							echo '<input type="checkbox" name="categorie[0]" value="1" '; if ($infosweb['categorie']{0} == '1') { echo 'checked';} echo'> Anim &nbsp; ';
							echo '<input type="checkbox" name="categorie[1]" value="1" '; if ($infosweb['categorie']{1} == '1') { echo 'checked';} echo'> Merch &nbsp; ';
							echo '<input type="checkbox" name="categorie[2]" value="1" '; if ($infosweb['categorie']{2} == '1') { echo 'checked';} echo'> Hotes &nbsp; ';
						?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="conninformatiq">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="4">
							<hr color="white" size="1">
						</td>
						<td>
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2">
							<div class="infosection">
								Hotesses
							</div>
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['physio'] != $infosweb['physio']) { echo 'class="red"' ;} ?>>
							Physionomie
						</td>
						<td>
							<?php echo $infos['physio']; ?>
						</td>
						<td>
<?php
$physios = array(
	'occidental'    => 'Occidental',
	'slave'         => 'Slave',
	'asiatique'     => 'Asiatique',
	'orientale'     => 'Orientale',
	'black'         => 'Black',
	'nordafricain'  => 'Nord-africain',
	'hispanique'    => 'Hispanique',
	'mediterraneen' => 'M&eacute;dit&eacute;rran&eacute;en'
);

echo '<select name="physio"><option value=""></option>';

foreach ($physios as $key => $value) {
	echo '<option value="'.$key.'"';
	if ($infosweb['physio'] == $key) {echo 'selected';}
	echo '>'.$value.'</option>';
}

echo '</select>';
?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="physio">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['ccheveux'] != $infosweb['ccheveux']) { echo 'class="red"' ;} ?>>
							Couleur des cheveux
						</td>
						<td>
							<?php echo $infos['ccheveux']; ?>
						</td>
						<td>
						<?php
							echo '
								<select name="ccheveux" size="1">
									<option value="blond"';if ($infosweb['ccheveux'] == 'blond') {echo 'selected';}echo '>blond</option>
									<option value="brun"';if ($infosweb['ccheveux'] == 'brun') {echo 'selected';}echo '>brun</option>
									<option value="noir"';if ($infosweb['ccheveux'] == 'noir') {echo 'selected';}echo '>noir</option>
									<option value="chatain"';if ($infosweb['ccheveux'] == 'chatain') {echo 'selected';}echo '>chatain</option>
									<option value="roux"';if ($infosweb['ccheveux'] == 'roux') {echo 'selected';}echo '>roux</option>
								</select>
							';
						?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="ccheveux">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['lcheveux'] != $infosweb['lcheveux']) { echo 'class="red"' ;} ?>>
							Longueur cheveux
						</td>
						<td>
							<?php echo $infos['lcheveux']; ?>
						</td>
						<td>
						<?php
							echo '
								<select name="lcheveux" size="1">
									<option value="long"';if ($infosweb['lcheveux'] == 'long') {echo 'selected';}echo '>long</option>
									<option value="mi-long"';if ($infosweb['lcheveux'] == 'mi-long') {echo 'selected';}echo '>mi-long</option>
									<option value="court"';if ($infosweb['lcheveux'] == 'court') {echo 'selected';}echo '>court</option>
									<option value="rase"';if ($infosweb['lcheveux'] == 'rase') {echo 'selected';}echo '>ras&eacute;</option>
								</select>
							';
						?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="lcheveux">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['taille'] != $infosweb['taille']) { echo 'class="red"' ;} ?>>
							Taille
						</td>
						<td>
							<?php echo $infos['taille']; ?> cm
						</td>
						<td>
							<input type="text" size="33" name="taille" value="<?php echo $infosweb['taille']; ?>"> cm
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="taille">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['tveste'] != $infosweb['tveste']) { echo 'class="red"' ;} ?>>
							Taille Veste
						</td>
						<td>
							<?php echo $infos['tveste']; ?>
						</td>
						<td>
						<?php
							echo '<select name="tveste" size="1">
								<option value="34"';if ($infosweb['tveste'] == '34') {echo 'selected';}echo '>34</option>
								<option value="36"';if ($infosweb['tveste'] == '36') {echo 'selected';}echo '>36</option>
								<option value="38"';if ($infosweb['tveste'] == '38') {echo 'selected';}echo '>38</option>
								<option value="40"';if ($infosweb['tveste'] == '40') {echo 'selected';}echo '>40</option>
								<option value="42"';if ($infosweb['tveste'] == '42') {echo 'selected';}echo '>42</option>
								<option value="44"';if ($infosweb['tveste'] == '44') {echo 'selected';}echo '>44</option>
								<option value="50"';if ($infosweb['tveste'] == '50') {echo 'selected';}echo '>50</option>
								<option value="52"';if ($infosweb['tveste'] == '52') {echo 'selected';}echo '>52</option>
								<option value="54"';if ($infosweb['tveste'] == '54') {echo 'selected';}echo '>54</option>
								<option value="56"';if ($infosweb['tveste'] == '56') {echo 'selected';}echo '>56</option>
								<option value="L"';if ($infosweb['tveste'] == 'L') {echo 'selected';}echo '>L</option>
								<option value="XL"';if ($infosweb['tveste'] == 'XL') {echo 'selected';}echo '>XL</option>
							';
								if ($infosweb['sexe'] == 'm') { echo '<option value="XL" selected>XL</option>';}
							echo '
								</select>
							';
						?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="tveste">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['tjupe'] != $infosweb['tjupe']) { echo 'class="red"' ;} ?>>
							Taille Jupe
						</td>
						<td>
							<?php echo $infos['tjupe']; ?>
						</td>
						<td>
						<?php
							echo '<select name="tjupe" size="1">
								<option value="34"';if ($infosweb['tjupe'] == '34') {echo 'selected';}echo '>34</option>
								<option value="36"';if ($infosweb['tjupe'] == '36') {echo 'selected';}echo '>36</option>
								<option value="38"';if ($infosweb['tjupe'] == '38') {echo 'selected';}echo '>38</option>
								<option value="40"';if ($infosweb['tjupe'] == '40') {echo 'selected';}echo '>40</option>
								<option value="42"';if ($infosweb['tjupe'] == '42') {echo 'selected';}echo '>42</option>
								<option value="44"';if ($infosweb['tjupe'] == '44') {echo 'selected';}echo '>44</option>
								<option value="50"';if ($infosweb['tjupe'] == '50') {echo 'selected';}echo '>50</option>
								<option value="52"';if ($infosweb['tjupe'] == '52') {echo 'selected';}echo '>52</option>
								<option value="54"';if ($infosweb['tjupe'] == '54') {echo 'selected';}echo '>54</option>
								<option value="56"';if ($infosweb['tjupe'] == '56') {echo 'selected';}echo '>56</option>
							';
								if ($infosweb['sexe'] == 'm') { echo '<option value="" selected>male</option>';}
							echo '
								</select>
							';
						?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="tjupe">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['pointure'] != $infosweb['pointure']) { echo 'class="red"' ;} ?>>
							Pointure
						</td>
						<td>
							<?php echo $infos['pointure']; ?>
						</td>
						<td>
							<input type="text" size="5" name="pointure" value="<?php echo $infosweb['pointure']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="pointure">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['menstai'] != $infosweb['menstai']) { echo 'class="red"' ;} ?>>
							taille
						</td>
						<td>
							<?php echo $infos['menstai']; ?>
						</td>
						<td>
							<input type="text" size="5" name="menstai" value="<?php echo $infosweb['menstai']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="menstai">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['menspoi'] != $infosweb['menspoi']) { echo 'class="red"' ;} ?>>
							poitrine
						</td>
						<td>
							<?php echo $infos['menspoi']; ?>
						</td>
						<td>
							<input type="text" size="5" name="menspoi" value="<?php echo $infosweb['menspoi']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="menspoi">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['menshan'] != $infosweb['menshan']) { echo 'class="red"' ;} ?>>
							hanches
						</td>
						<td>
							<?php echo $infos['menshan']; ?>
						</td>
						<td>
							<input type="text" size="5" name="menshan" value="<?php echo $infosweb['menshan']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="menshan">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="4">
							<hr color="white" size="1">
						</td>
						<td>
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['fume'] != $infosweb['fume']) { echo 'class="red"' ;} ?>>
							Fume
						</td>
						<td>
							<?php echo $infos['fume']; ?>
						</td>
						<td>
						<?php
							echo '<input type="radio" name="fume" value="non" '; if (($infosweb['fume'] == 'non') or ($infosweb['permis'] == '')) { echo 'checked';} echo'> Non';
							echo '<input type="radio" name="fume" value="oui" '; if ($infosweb['fume'] == 'oui') { echo 'checked';} echo'> Oui';
						?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="fume">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['conninformatiq'] != $infosweb['conninformatiq']) { echo 'class="red"' ;} ?>>
							Informatique
						</td>
						<td>
							<?php echo $infos['conninformatiq']; ?>
						</td>
						<td>
						<?php
							echo '<input type="checkbox" name="conninformatiq[]" value="Word" '; if (strchr($infosweb['conninformatiq'], 'Word')) { echo 'checked';} echo'> Word &nbsp; ';
							echo '<input type="checkbox" name="conninformatiq[]" value="Excel" '; if (strchr($infosweb['conninformatiq'], 'Excel')) { echo 'checked';} echo'> Excel &nbsp; ';
							echo '<input type="checkbox" name="conninformatiq[]" value="Powerpoint" '; if (strchr($infosweb['conninformatiq'], 'Powerpoint')) { echo 'checked';} echo'> Powerpoint &nbsp; ';
							echo '<input type="checkbox" name="conninformatiq[]" value="Internet" '; if (strchr($infosweb['conninformatiq'], 'Internet')) { echo 'checked';} echo'> Internet';
						?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="conninformatiq">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="4">
							<hr color="white" size="1">
						</td>
						<td>
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['permis'] != $infosweb['permis']) { echo 'class="red"' ;} ?>>
							Permis
						</td>
						<td>
							<?php echo $infos['permis']; ?>
						</td>
						<td>
						<?php
							echo '<input type="radio" name="permis" value="non" '; if (($infosweb['permis'] == 'non') or ($infosweb['permis'] == '')) { echo 'checked';} echo'> Non';
							echo '<input type="radio" name="permis" value="A" '; if ($infosweb['permis'] == 'A') { echo 'checked';} echo'> A';
							echo '<input type="radio" name="permis" value="B" '; if ($infosweb['permis'] == 'B') { echo 'checked';} echo'> B';
							echo '<input type="radio" name="permis" value="C" '; if ($infosweb['permis'] == 'C') { echo 'checked';} echo'> C';
							echo '<input type="radio" name="permis" value="D" '; if ($infosweb['permis'] == 'D') { echo 'checked';} echo'> D';
							echo '<input type="radio" name="permis" value="E" '; if ($infosweb['permis'] == 'E') { echo 'checked';} echo'> E';
						?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="permis">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['voiture'] != $infosweb['voiture']) { echo 'class="red"' ;} ?>>
							Voiture
						</td>
						<td>
							<?php echo $infos['voiture']; ?>
						</td>
						<td>
						<?php
							echo '<input type="radio" name="voiture" value="non" '; if ($infosweb['voiture'] == 'non') { echo 'checked';} echo'> Non';
							echo '<input type="radio" name="voiture" value="oui" '; if ($infosweb['voiture'] == 'oui') { echo 'checked';} echo'> Oui';
						?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="voiture">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="4">
							<hr color="white" size="1">
						</td>
						<td>
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2">
							<div class="infosection">
								S-Social
							</div>
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['ndate'] != $infosweb['ndate']) { echo 'class="red"' ;} ?>>
							Naissance
						</td>
						<td>
							<?php echo fdate($infos['ndate']); ?>
						</td>
						<td>
							<input type="text" size="33" name="ndate" value="<?php echo fdate($infosweb['ndate']); ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="ndate">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['ncp'] != $infosweb['ncp']) { echo 'class="red"' ;} ?>>
							Code Postal de Naissance
						</td>
						<td>
							<?php echo $infos['ncp']; ?>
						</td>
						<td>
							<input type="text" size="10" name="ncp" value="<?php echo $infosweb['ncp']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="ncp">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['nville'] != $infosweb['nville']) { echo 'class="red"' ;} ?>>
							Localit&eacute; de Naissance
						</td>
						<td>
							<?php echo $infos['nville']; ?>
						</td>
						<td>
							<input type="text" size="33" name="nville" value="<?php echo $infosweb['nville']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="nville">
						</td>
					</tr>
					<tr class="standard">
<?php
							include(NIVO."conf/pays.php");
?>
						<td colspan="2" <?php if ($infos['npays'] != $infosweb['npays']) { echo 'class="red"' ;} ?>>
							Pays de Naissance
						</td>
						<td>
							<?php echo $iso_pays_fr[$infos['npays']]; ?>
						</td>
						<td>
						<?php

							echo '<select name="npays">';

							foreach ($iso_pays_fr as $key => $value) {
								echo '<option value="'.$key.'"';
								if ($infosweb['npays'] == $key) {echo 'selected';}
								if (($infosweb['npays'] == '') and ($key == 'BE')) {echo 'selected';}
								echo '>'.$value.'</option>';
							}

							echo '</select>';
						?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="npays">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="4">
							<hr color="white" size="1">
						</td>
						<td>
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['ncidentite'] != $infosweb['ncidentite']) { echo 'class="red"' ;} ?>>
							N&deg; Carte d&rsquo;identit&eacute;
						</td>
						<td>
							<?php echo $infos['ncidentite']; ?>
						</td>
						<td>
							<input type="text" size="33" name="ncidentite" value="<?php echo $infosweb['ncidentite']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="ncidentite">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['nrnational'] != $infosweb['nrnational']) { echo 'class="red"' ;} ?>>
							N&deg; Reg national
						</td>
						<td>
							<?php echo $infos['nrnational']; ?>
						</td>
						<td>
							<input type="text" size="33" name="nrnational" value="<?php echo $infosweb['nrnational']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="nrnational">
						</td>
					</tr>
					<tr class="standard">
							<?php
								include(NIVO."/conf/nationalites.php");
							?>
						<td colspan="2" <?php if ($infos['nationalite'] != $infosweb['nationalite']) { echo 'class="red"' ;} ?>>
							Nationalit&eacute;
						</td>
						<td>
							<?php echo $natios[$infos['nationalite']]; ?>
						</td>
						<td>
							<?php
								echo '<select name="nationalite">';

								foreach ($natios as $key => $value) {
									echo '<option value="'.$key.'"';
									if ($infosweb['nationalite'] == $key) {echo 'selected';}
									if (($infosweb['nationalite'] == '') and ($key == 'B')) {echo 'selected';}
									echo '>'.$value.'</option>';
								}

								echo '</select>';
							?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="nationalite">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="4">
							<hr color="white" size="1">
						</td>
						<td>
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['etatcivil'] != $infosweb['etatcivil']) { echo 'class="red"' ;} ?>>
							Etat civil
						</td>
						<td>
							<?php
								if (($infos['etatcivil'] == '') or ($infos['etatcivil'] == '1')) {echo 'C&eacute;libataire';}
								if ($infos['etatcivil'] == '2') {echo 'Mari&eacute;';}
								if ($infos['etatcivil'] == '3') {echo 'Veuf';}
								if ($infos['etatcivil'] == '4') {echo 'Divorc&eacute;';}
								if ($infos['etatcivil'] == '5') {echo 'S&eacute;par&eacute;';}
							?>
						</td>
						<td>
						<?php
							echo '
							<select name="etatcivil">
								<option value="1"
							';
								if (($infosweb['etatcivil'] == '') or ($infosweb['etatcivil'] == '1')) {echo 'selected';}
							echo '
								>C&eacute;libataire</option>
								<option value="2"
							';
								if ($infosweb['etatcivil'] == '2') {echo 'selected';}
							echo '
								>Mari&eacute;</option>
								<option value="3"
							';
								if ($infosweb['etatcivil'] == '3') {echo 'selected';}
							echo '
								>Veuf</option>
								<option value="4"
							';
								if ($infosweb['etatcivil'] == '4') {echo 'selected';}
							echo '
								>Divorc&eacute;</option>
								<option value="5"
							';
								if ($infosweb['etatcivil'] == '5') {echo 'selected';}
							echo '
								>S&eacute;par&eacute;</option>
							</select>
							';
						?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="etatcivil">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['datemariage'] != $infosweb['datemariage']) { echo 'class="red"' ;} ?>>
							Date Mariage
						</td>
						<td>
							<?php echo fdate($infos['datemariage']); ?>
						</td>
						<td>
							<input type="text" size="33" name="datemariage" value="<?php echo fdate($infosweb['datemariage']); ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="datemariage">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['nomconjoint'] != $infosweb['nomconjoint']) { echo 'class="red"' ;} ?>>
							Nom Conjoint
						</td>
						<td>
							<?php echo $infos['nomconjoint']; ?>
						</td>
						<td>
							<input type="text" size="33" name="nomconjoint" value="<?php echo $infosweb['nomconjoint']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="nomconjoint">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['dateconjoint'] != $infosweb['dateconjoint']) { echo 'class="red"' ;} ?>>
							Naissance Conj.
						</td>
						<td>
							<?php echo fdate($infos['dateconjoint']); ?>
						</td>
						<td>
							<input type="text" size="33" name="dateconjoint" value="<?php echo fdate($infosweb['dateconjoint']); ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="dateconjoint">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['jobconjoint'] != $infosweb['jobconjoint']) { echo 'class="red"' ;} ?>>
							Job Conj.
						</td>
						<td>
<?php
				$jobconjoint_list = array(
					'0' => 'Inconnue',
					'1' => 'Ouvrier',
					'2' => 'Gens De Maison',
					'3' => 'Employe',
					'4' => 'Independant',
					'5' => 'Ouvrier Des Mines',
					'6' => 'Marin',
					'7' => 'Travailleur Des Services Publics',
					'8' => 'Autre',
					'9' => 'Sans',
				);

				if (empty($infos['jobconjoint'])) $infos['jobconjoint'] = 0;
				echo $jobconjoint_list[$infos['jobconjoint']];

?>
						</td>
						<td>
<?php

				echo '<select name="jobconjoint">';

				foreach ($jobconjoint_list as $key => $value) {
					echo '<option value="'.$key.'"'.((($infosweb['jobconjoint'] == $key) || (empty($infosweb['jobconjoint']) && ($key == '0'))) ? 'selected':'').'>'.$value.'</option>';
				}

				echo '</select>';
?>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="jobconjoint">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['pacharge'] != $infosweb['pacharge']) { echo 'class="red"' ;} ?>>
							Pers &agrave; Ch.
						</td>
						<td>
							<?php echo $infos['pacharge']; ?>
						</td>
						<td>
							<input type="text" size="33" name="pacharge" value="<?php echo $infosweb['pacharge']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="pacharge">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['eacharge'] != $infosweb['eacharge']) { echo 'class="red"' ;} ?>>
							Enf &agrave; Ch.
						</td>
						<td>
							<?php echo $infos['eacharge']; ?>
						</td>
						<td>
							<input type="text" size="33" name="eacharge" value="<?php echo $infosweb['eacharge']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="eacharge">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="4">
							<hr color="white" size="1">
						</td>
						<td>
						</td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['banque'] != $infosweb['banque']) { echo 'class="red"' ;} ?>>
							Compte Bancaire
						</td>
						<td>
							<?php echo $infos['banque']; ?>
						</td>
						<td>
							<input type="text" size="33" name="banque" value="<?php echo $infosweb['banque']; ?>">
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="banque">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="4"> <hr color="white" size="1"> </td>
						<td></td>
					</tr>
					<tr class="standard">
						<td colspan="2" <?php if ($infos['conditions_accepted'] != $infosweb['conditions_accepted']) { echo 'class="red"' ;} ?>>
							Accepte le r&egrave;glement de travail
						</td>
						<td>
							<?php echo $infos['conditions_accepted']; ?>
						</td>
						<td>
							<input type="checkbox" size="33" name="conditions_accepted" value="yes" <?php echo ($infosweb['conditions_accepted'] == 'yes')?' checked':''; ?>>
						</td>
						<td>
							<input type="checkbox" name="champs[]" checked value="conditions_accepted">
						</td>
					</tr>
					<tr class="standard">
						<td colspan="4"> <hr color="white" size="1"> </td>
						<td></td>
					</tr>
					<tr class="standard">
						<td colspan="2">
							Notes
						</td>
						<td>
							<?php echo $infos['notegenerale']; ?>
						</td>
						<td colspan="2">
							<?php echo $infosweb['notegenerale']; ?>
						</td>
					</tr>

				</table>
			</td>
		</tr>
	</table>
	<table border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
		<tr>
			<td align="center">
					<input type="submit" name="Modifier" value="Modifier" accesskey="M">
			</td>
		</tr>
	</table>
</form>
</div>
