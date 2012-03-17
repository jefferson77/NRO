<?php
define('NIVO', '../');
require_once(NIVO."nro/fm.php");

$queryZip = $_REQUEST["zip"];
if ($_REQUEST["whichZip"]) {
	$whichZip = $_REQUEST["whichZip"];
} else {$whichZip='';}

//contenu à insérer si la ville est vide ou inconnue
if ($whichZip!='') {
	$virgincity = "<input id='city".$whichZip."' name='city".$whichZip."' type='text' value=''/>";
} else {
	$virgincity = "<input class='required' id='city' name='city' type='text' value=''/>";
}

	$detail2 = new db('', '', 'neuro');
	$detail2->inline("SELECT * FROM `codepost` WHERE `cpbcode` = '$queryZip' ORDER BY cpblocalite ASC");
	if (mysql_numrows($detail2->result) > 0) {
		$newliste = "<select name='city".$whichZip."'>";
		while ($infos2 = mysql_fetch_array($detail2->result)) {
			$city = $infos2['cpblocalite'];
			$newliste .= "<option value='".$city."'>".$city."</option>";
		}
	$newliste .="</select>";?>
	document.getElementById("ajaxlist<?php echo $whichZip ?>").innerHTML = "<?php echo $newliste ?>";
	<?php
	} else {
?>
	document.getElementById("ajaxlist<?php echo $whichZip ?>").innerHTML = "<?php echo $virgincity ?>";
<?php
	}
?>
/* toggleCityStateEnabled();  pour désactiver le focus sur le champs*/
