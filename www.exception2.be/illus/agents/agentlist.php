<?php
$files = scandir(dirname(__FILE__));
foreach($files as $file) {
	$parts = explode(".", $file);
	if (($parts[0] >= 1) and ($parts[1] == 'png')) {
		echo $parts[0]."\r\n";
	}
}
?>