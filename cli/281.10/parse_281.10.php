#!/usr/bin/php
<?php
/*
###  Parsing du PDF de 281.10 envoyé par Groupe-S  ###
0. telecharger les outils java sur

	http://multivalent.sourceforge.net/Tools/index.html

1. decouper le fichier en double pages :

	Placer le fichier des 281.10 a l'emplacement suivant '/NRO/tool/281/67442.pdf'

	cd /NRO/tool/281
	java -classpath /NRO/core/cli/281.10/Multivalent20060102.jar tool.pdf.Split -page "1-end/2" 67442.pdf

2. process les fichiers découpés avec les instruction suivantes :
	!! a utiliser en local, sur le desktop.

*/

if ($handle = opendir('/NRO/tool/281')) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != ".."&& $file != ".DS_Store") {
			$last = exec('java -classpath /NRO/core/cli/281.10/Multivalent20060102.jar tool.doc.ExtractText /NRO/tool/281/'.$file, $res);
			
			foreach($res as $line) {
				if (strstr($line, '431 / 67442 / ')) {
					$inf = explode(" / ", $line);
					
					if (rename("/NRO/tool/281/".$file, "/NRO/tool/2008/281.10 ".$inf[2].".pdf")) echo "281.10/".$file." -> 281/281.10 ".$inf[2].".pdf\n";
				}
			}
			unset($res);
		}
	}
	closedir($handle);
}

/*

3. Upload des fichiers dans /documents/people/281_2006

*/
?>