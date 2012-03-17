<div id="centerzonelarge" align="center">
<?php
## Find 281.10
$parpeople = array();

foreach ($_POST['periodFiche'] as $periode) {
	$fileFiche = Conf::read('Env.root').'document/people/'.prezero($_POST['idpeople'], 5).'/FicheSalaire-'.$periode.'.pdf';

	if (file_exists($fileFiche)) {
		$parpeople[$_POST['idpeople']][] = $fileFiche;
	}
}

if (count($parpeople) > 0) {
	generateSendTable($parpeople, "people","temp/FiSa", "FiSa", "Fiche Salaire");
}

?>
</div>