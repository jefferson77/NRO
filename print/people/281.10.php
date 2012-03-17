<div id="centerzonelarge" align="center">
<?php
## Find 281.10
$parpeople = array();

foreach ($_POST['period281'] as $annee) {
	$file281 = Conf::read('Env.root').'document/people/'.prezero($_POST['idpeople'], 5).'/281.10-'.$annee.'.pdf';

	if (file_exists($file281)) {
		$parpeople[$_POST['idpeople']][] = $file281;
	}
}

if (count($parpeople) > 0) {
	generateSendTable($parpeople, "people","temp/P281", "P281", "281.10");
}

?>
</div>