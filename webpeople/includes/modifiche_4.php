<?php
# Classes utilisées
include NIVO."classes/photo.php" ;

$detail = new db('', '', 'webneuro');
$detail->inline("SELECT * FROM `webpeople` WHERE `idpeople` = $_SESSION[idpeople]");
$infos = mysql_fetch_array($detail->result);

if ($_POST['confirmfiche'] == '1') {


	function checkDataItalian($date) {
		list($dd,$mm,$yy)=explode("/",$date);
	    if ($dd!="" && $mm!="" && $yy!=""){
			return checkdate($mm,$dd,$yy);
		} else {
			return(false);
		}
	}


	####vérification serveur du formulaire envoyé dans modifiche_3####

		///check des données obligatoires///

		if ( (empty($_POST['ndate_D'] ))
			|| (empty($_POST['ndate_M'] ))
			|| (empty($_POST['ndate_Y'] ))
			|| (empty($_POST['zipcode'] ))
			|| (empty($_POST['city'] ))
			|| (empty($_POST['npays'] ))
			|| (empty($_POST['nationalite'] ))
			|| (empty($_POST['etatcivil'] ))
			|| (empty($_POST['ncidentite'] ))
			|| (empty($_POST['nrnational'] ))
			|| (empty($_POST['banque'] )) ) {

				die($emodifiche1_01);
		}

		$_POST['zipcode'] = trim($_POST['zipcode']);
		$_POST['city'] = ucfirst(trim($_POST['city']));
		$ndate = $_POST['ndate_Y'].'-'.$_POST['ndate_M'].'-'.$_POST['ndate_D'];


		//traitement en cas de personne mariée
		if ($_POST['etatcivil'] == 2) {
			if (
				 (!empty($_POST['datemariage_D']))
				 && (!empty($_POST['datemariage_M']))
				 && (!empty($_POST['datemariage_Y']))
				 && (!empty($_POST['nomconjoint']))
				 && (!empty($_POST['dateconjoint_D']))
				 && (!empty($_POST['dateconjoint_M']))
				 && (!empty($_POST['dateconjoint_Y']))
				 && (!empty($_POST['jobconjoint']))
			   ) {
					$datemariage = $_POST['datemariage_Y'].'-'.$_POST['datemariage_M'].'-'.$_POST['datemariage_D'];
					$_POST['nomconjoint']= trim($_POST['nomconjoint']);
					$dateconjoint = $_POST['dateconjoint_Y'].'-'.$_POST['dateconjoint_M'].'-'.$_POST['dateconjoint_D'];
					if (
						((!empty($_POST['pacharge'])) && (!is_numeric($_POST['pacharge'])))
						||
						((!empty($_POST['eacharge'])) && (!is_numeric($_POST['eacharge'])))
						) {
						die($emodifiche3_02);
					}
			} else {
				die($emodifiche3_01);
			}
		} else {
			$datemariage          ='';
			$nomconjoint          = '';
			$dateconjoint         = '';
			$_POST['jobconjoint'] = '';
			$_POST['pacharge']    = '';
			$_POST['eacharge']    = '';
		}

	///vérif des numéros
	$ncidentite  = $_POST['ncidentite'];
	$nrnational = $_POST['nrnational'];

	$reste = substr($valeur, -2) ;
	$nombre = substr($valeur, 0, -2) ;
	if (fmod($nombre, 97) == 0) {$mod = 97;} else {$mod = fmod($nombre, 97);}

	$banque = $_POST['banque'];

	$sql = new db('', '', 'webneuro');
	$requete = "UPDATE webpeople SET ndate='".$ndate."', ncp='".$_POST['zipcode']."', nville='".$_POST['city']."', npays='".$_POST['npays']."',
	nationalite='".$_POST['nationalite']."', etatcivil='".$_POST['etatcivil']."', datemariage='".$datemariage."',
	nomconjoint='".$_POST['nomconjoint']."', dateconjoint='".$dateconjoint."', jobconjoint='".$_POST['jobconjoint']."',
	pacharge='".$_POST['pacharge']."', eacharge='".$_POST['eacharge']."', ncidentite='".$ncidentite."',
	nrnational='".$nrnational."', banque='".$banque."' WHERE idpeople='".$infos['idpeople']."'";
	$sql->inline($requete);

}

####nouveau formulaire####
?>

<p></p>
<div class="formdiv" id="container">
<form action="/js/fancyuploader/upload.php" method="post" id="photoupload" enctype="multipart/form-data">
<h4><?php echo $modifiche4_01; ?></h4>
<?php

if (file_exists(GetPhotoPath($infos['idpeople'], '', 'path', ''))) {
	$image1='<img src="'.NIVO.'data/people/photo.php?id='.$infos['idpeople'].'" alt="Photo n°1">';
}
if (file_exists(GetPhotoPath($infos['idpeople'], '', 'path', '-b'))) {
	$image2='<img src="'.NIVO.'data/people/photo.php?id='.$infos['idpeople'].'&sfx=-b" alt="Photo n°2">';
}


if ((file_exists(GetPhotoPath($infos['idpeople'], '', 'path', ''))|| file_exists(GetPhotoPath($infos['idpeople'], '', 'path', '-b')))) {
	echo '<div class="photosactu">
	'.$image1.'
	'.$image2.'<br>'.$modifiche4_05 .'
	</div><p></p>';
}
?>
		<table cellspacing="0" class="formulaire">
			<tr class="ligne2">
				<td class="lignename">1. <?php echo $modifiche4_02 ?></td>
				<td style="height:50px;">
			<div class="halfsize">
					<div class="label emph">
						<label for="photoupload-filedata-1">
						</label>
						<input type="file" name="Filedata" id="photoupload-filedata-1" /><br>
						<img src="<?php echo STATIK ?>illus/bullet_error.png" alt="" width="16" height="16" /><?php echo $modifiche4_02a ?>
					</div>
			</div>
				</td>
			</tr>
			<tr>
				<td class="lignename">2. <?php echo $modifiche4_03 ?></td>
				<td style="height:50px;">
			<div class="halfsize"><br><?php echo $modifiche4_03a ?>

					<ul class="photoupload-queue" id="photoupload-queue">
						<li style="display: none" />
					</ul>
			</div>
				</td>
			</tr>
			</table>
			<br>
			<table cellspacing="0" class="formulaire">
			<tr class="ligne2">
				<td class="lignename">3. <?php echo $modifiche4_04 ?></td>
				<td align="right" style="height:50px;">
				<span style="margin-right:30px"><input type="submit" class="submit" id="profile-submit" value="Envoyer les photos"/></span>
				</td>
			</tr>
</form>
		</table>
<br>
	<div style="text-align:right;padding-right:25px;padding-top:5px">
		<a href="?page=modifiche&step=3"><img src="../../web/illus/formback.png" width="70" height="38"></a>&nbsp;
		<a href="?page=modifiche&step=5"><img src="../../web/illus/formok.png" width="70" height="38"></a>
	</div>
</div>