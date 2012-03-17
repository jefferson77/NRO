<?php
# Emploi : 
# routingpng.php?mis=VI24543&sz=175x20
# routingpng.php?entete=1&sz=175x20

define('NIVO', '../');

require_once(NIVO."nro/fm.php");
include_once(NIVO.'classes/hh.php');

if (!empty($_GET['sz'])) {
	$ds = explode("x", $_GET['sz']);

	$largeur = $ds[0];
	$hauteur = $ds[1];
} else {
	$largeur = 200;
	$hauteur = 13;
}


# Creation fichier
$img = imagecreatetruecolor($largeur, $hauteur);

if ($_GET['entete'] == 1) {
#########################################################################################################################################################################
### entete #######################################################################################################################################################
	$fond = imagecolorallocate($img, 86, 87, 107);
	$lines1 = imagecolorallocate($img, 226, 226, 226);
	$lines2 = imagecolorallocate($img, 200, 200, 200);

	# remplissage fond
	imagefill($img, 0, 0, $fond);

	# h lines ###########
	$space = $largeur / 24;
	
	for ($i = 1; $i <= 23; $i++) {
		$pos = $i * $space;
		imageline($img, $pos, $hauteur - 5, $pos, $hauteur, $lines1);
	}

	# Heures ############
	for ($i = 0; $i <= 23; $i++) {
		$t = $i+3;
		
		if ($t > 23) $t -= 24; 

		$pos = (($i + 1) * $space) - 1 ;
		
		imagettftext($img, 6, 90, $pos, $hauteur - 1, $lines1, $_SERVER["DOCUMENT_ROOT"]."/merch/mini.ttf", $t);
	}


} elseif (!empty($_GET['mis'])) {
#########################################################################################################################################################################
### Mission #######################################################################################################################################################
	# Couleurs
	$fond = imagecolorallocate($img, 245, 245, 245);
	$lines = imagecolorallocate($img, 226, 226, 226);
	if (!empty($_GET['dim'])) {
		$vert = imagecolorallocate($img, 200, 200, 200);
	} else {
		$vert = imagecolorallocate($img, 7, 212, 2);
	}
	
	$rouge = imagecolorallocate($img, 212, 0, 0);
	$rougeclair = imagecolorallocatealpha($img, 212, 0, 0, 110);
	
	# remplissage fond
	imagefill($img, 0, 0, $fond);
	
	# h lines
	$space = $largeur / 24;
	
	for ($i = 1; $i <= 23; $i++) {
		$pos = $i * $space;
		imageline($img, $pos, 0, $pos, $hauteur , $lines);
	}
	
	## get infos sur la mission
	switch (substr($_GET['mis'], 0, 2)) {
		case"AN":
			$sql = "SELECT idpeople, datem FROM animation WHERE idanimation = '".substr($_GET['mis'], 2)."'";
		break;
		case"ME":
			$sql = "SELECT idpeople, datem FROM merch WHERE idmerch = '".substr($_GET['mis'], 2)."'";
		break;
		case"VI":
			$sql = "SELECT idpeople, vipdate as datem FROM vipmission WHERE idvip = '".substr($_GET['mis'], 2)."'";
		break;
	}
	
	$ginf = new db ();
	
	$ginf->inline($sql);
	
	$gf = mysql_fetch_array($ginf->result);
	
	$hinf = new hh();
	$hinf->hhtable($gf['idpeople'], $gf['datem'], $gf['datem']);
	
	foreach ($hinf->prtab[$gf['datem']] as $val) {
	
		## inscription heures reeles
			## Matin
			if (($val['h1'] != "00:00:00") or ($val['h2'] != "00:00:00")) {
				$h1 = explode(":", $val['h1']);
				$h2 = explode(":", $val['h2']);
				
				if ($h1[0] > $h2[0]) {$h2[0] += 24;}
				
				$stpoint = (0 - (3 * $space)) + ($h1[0] * $space) + ($h1[1] / 60 * $space);
				$endpoint = (0 - (3 * $space)) + ($h2[0] * $space) + ($h2[1] / 60 * $space);
			
				if ($_GET['mis'] == $val['secteur'].$val['idmission']) {
					imagefilledrectangle($img, $stpoint, 3, $endpoint, $hauteur - 4, $vert);
				}
				
				$redtab[] = array('in' => $stpoint, 'out' => $endpoint, 'mi' => ($val['secteur'].$val['idmission']));
			}
	
			## Aprem
			if ((($val['h3'] != "00:00:00") or ($val['h4'] != "00:00:00")) and ($val['secteur'] != 'VI')) {
				$h1 = explode(":", $val['h3']);
				$h2 = explode(":", $val['h4']);
				
				if ($h1[0] > $h2[0]) {$h2[0] += 24;}
				
				$stpoint = (0 - (3 * $space)) + ($h1[0] * $space) + ($h1[1] / 60 * $space);
				$endpoint = (0 - (3 * $space)) + ($h2[0] * $space) + ($h2[1] / 60 * $space);
			
				if ($_GET['mis'] == $val['secteur'].$val['idmission']) {
					imagefilledrectangle($img, $stpoint, 3, $endpoint, $hauteur - 4, $vert);
				}
	
				$redtab[] = array('in' => $stpoint, 'out' => $endpoint, 'mi' => ($val['secteur'].$val['idmission']));
			}
	}
	
	foreach($redtab as $vs) {
		foreach($redtab as $ch) {
		## cas 1
			if (($vs['in'] <= $ch['out']) and ($vs['in'] >= $ch['in']) and ($vs['mi'] != $ch['mi'])) {
				if ($vs['out'] < $ch['out']) {
					$reds[] = $vs['in'].'X'.$vs['out'].'X'.$vs['mi'];
					$reds[] = $vs['in'].'X'.$vs['out'].'X'.$ch['mi'];
				} else {
					$reds[] = $vs['in'].'X'.$ch['out'].'X'.$vs['mi'];
					$reds[] = $vs['in'].'X'.$ch['out'].'X'.$ch['mi'];
				}
			}
		## cas 2
			if (($vs['out'] <= $ch['out']) and ($vs['out'] >= $ch['in']) and ($vs['mi'] != $ch['mi'])) {
				if ($vs['in'] < $ch['in']) {
					$reds[] = $ch['in'].'X'.$vs['out'].'X'.$vs['mi'];
					$reds[] = $ch['in'].'X'.$vs['out'].'X'.$ch['mi'];
				} else {
					$reds[] = $vs['in'].'X'.$vs['out'].'X'.$vs['mi'];
					$reds[] = $vs['in'].'X'.$vs['out'].'X'.$ch['mi'];
				}
			}
		}
	}
	
	unset($redtab);
	
	if (is_array($reds)) {
		$reds = array_unique($reds);
	
		## Affiche redtab
		foreach ($reds as $bloc) {
			$r = explode("X", $bloc);
	
			if ($r[1] > $r[0]) {
		
				imagefilledrectangle($img, $r[0], 0, $r[1], $hauteur, $rougeclair);
				
				if ($r[2] == $_GET['mis']) {
					imagefilledrectangle($img, $r[0], 3, $r[1], $hauteur - 4, $rouge);
				}
			}
	
		}
		unset($reds);
	}
	

} else {
	# Couleurs
	$fond = imagecolorallocate($img, 245, 245, 245);
	$lines = imagecolorallocate($img, 226, 226, 226);
	
	# remplissage fond
	imagefill($img, 0, 0, $fond);
	
	# h lines
	$space = $largeur / 24;
	
	for ($i = 1; $i <= 23; $i++) {
		$pos = $i * $space;
		imageline($img, $pos, 0, $pos, $hauteur , $lines);
	}

}

header("Content-type: image/png");
imagepng($img);
imagedestroy($img);
?>