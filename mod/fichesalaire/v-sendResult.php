<div id="centerzonelarge">
<?php
switch ($_POST['doctype']) {
	### Fiches de Salaire
	case 'fiches':
		if (count($_POST['periods']) > 0) {
			$parpeople = array();
			foreach ($_POST['periods'] as $period) {
				exec('find '.Conf::read('Env.root').'document/people -name "FicheSalaire-'.$period.'.pdf"', $files);

				# Array des fiches /mois
				foreach ($files as $file) if (preg_match("/people\/(\d{5})\/FicheSalaire\-(\d{6})\.pdf$/", $file, $m)) $parpeople[(int)$m[1]][] = $file;
			}
			if (count($parpeople) > 0) generateSendTable($parpeople,'people','temp/PFsa','PFsa','Fiches de Salaire');
		}
	break;
	### 281.10
	case '281.10':
		if (count($_POST['periods']) > 0) {
			$parpeople = array();
			foreach ($_POST['periods'] as $period) {
				exec('find '.Conf::read('Env.root').'document/people -name "281.10-'.$period.'.pdf"', $files);
				
				$alreadysend = $DB->getColumn("SELECT idcontact FROM `webdocs` WHERE `path` LIKE '%D281%'");

				# Array des 281.10
				foreach ($files as $file) {
					if (preg_match("/people\/(\d{5})\/281.10\-(\d{4})\.pdf$/", $file, $m)) {
						if(!in_array((int)$m[1], $alreadysend)) {
							$parpeople[(int)$m[1]][] = $file;
						}
					}
				}
			}
			if (count($parpeople) > 0) generateSendTable($parpeople,'people','temp/D281','D281','281.10');
		}
	break;
}
?>
</div>