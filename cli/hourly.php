#!/usr/bin/php
<?php
######################################################################################################################################################
## Fichier De maintenance a lancer toutes les heures              ####################################################################################
######################################################################################################################################################

## Classes
define("NIVO", "/NRO/core/");
include_once(NIVO."nro/fm.php");
	## Revérifie que l'imprimante n'est pas en pause
	exec('/usr/sbin/cupsenable kyocera');

	##########################################################################
	## Task 0 : Remove les .DS_Store inutiles
	exec('cd /NRO;find . -name "._*" -exec rm -f {} \;');

	##########################################################################
	## Task 1 : Backup SQL               Sauvegarde une dernière version des DB's dans le dossier media/sqldump et backup l'ancienne version dans backup/sql                             

	## init vars
	$binpath = "/usr/bin/";

	$lastsqlpath = "/NRO/media/sqldump/";
	$sqlbupath = "/safety/long/NRO/sql-h/";
	
	## rdiff backup
	exec("rdiff-backup ".$lastsqlpath." ".$sqlbupath);
	exec("rdiff-backup --remove-older-than 2W --force ".$sqlbupath);

	$DB->inline("show databases;");
	
	$ignoretables = array('information_schema');

	while ($dbn = mysql_fetch_array($DB->result)) {
		if (!in_array($dbn['Database'], $ignoretables)) {
			$dbfile = $lastsqlpath.$dbn['Database'].".sql";

			## Sauve les nouveaux
			exec($binpath."mysqldump -u".Conf::read('Sql.login')." -p".Conf::read('Sql.pass')." -B ".$dbn['Database']." > ".$dbfile);
		}
	}
?>
