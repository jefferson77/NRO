<?php
switch ($secteur) {

	### ANIM ############################################################################################################################################
	case"anim":
		$sql = "SELECT p.gsm, p.pnom, p.pprenom
		FROM animation a
		LEFT JOIN people p ON a.idpeople = p.idpeople
		WHERE a.idanimation
		IN (
		'".implode("', '", $_POST['print'])."'
		) GROUP BY a.idpeople";
	break;
	### MERCH ############################################################################################################################################
	case"merch":
		foreach ($_POST['print'] as $val) {
			$v = explode("/", $val);
			$missm[] = $v[2]; 
		}

		$sql = "SELECT p.gsm, p.pnom, p.pprenom
		FROM merch m
		LEFT JOIN people p ON m.idpeople = p.idpeople
		WHERE m.idmerch
		IN (
		'".implode("', '", $missm)."'
		) GROUP BY m.idpeople";
	break;
}

$detail = new db();
$detail->inline("SET NAMES latin1");
$detail->inline($sql);

$i = 1;

echo '<table>';

while ($row = mysql_fetch_array($detail->result)) {
	$gsm = trim(preg_replace('@[^0-9]@','',$row['gsm']));

	echo '<tr><td>'.$row['pnom'].' '.$row['pprenom'].'</td><td>'.$gsm.'</td></tr>';


	if (!empty($gsm)) $listgsm .= $gsm.';';
	if (fmod($i, 20) == 0) $listgsm .= "\r";
	$i++;

}
echo '<table>';


?>
<div align="center">
<textarea rows="6" cols="80">
<?php echo $listgsm; ?>
</textarea>
</div>

