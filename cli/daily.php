#!/usr/bin/php
<?php
## Fichier De maintenance a lancer tous les jours              #####################

	##########################################################################
	## Task 1 : BU du dossier svn (rdiff-backup)
	exec("rdiff-backup /svn/ /safety/long/NRO/svn/");

	##########################################################################
	## Task 2 : BU journalier SQL
	$lastsqlpath = "/NRO/media/sqldump/";
	$sqlbupath = "/safety/long/NRO/sql/";

	## rdiff backup
	exec("rdiff-backup ".$lastsqlpath." ".$sqlbupath);

	##########################################################################

	function snapshot ($path, $bupath, $exclude = '') {
		if (is_dir($bupath."latest")) $linkdir = "--link-dest=".$bupath."latest";
		if (!empty($exclude)) $excl = "--exclude '".$exclude."'";

		exec("rsync -a ".$excl." --delete ".$linkdir." ".$path." ".$bupath."newest/");

		if (is_dir($bupath."newest/")) {
			rename($bupath."latest", $bupath.date("Y-m-d", strtotime("-1 day")));
			rename($bupath."newest", $bupath."latest");
		}
	}

	## Task 3 : BU des documents media
		snapshot ("/NRO/media/", "/safety/long/NRO/media/", "sqldump");
	## Task 4 : BU des documents
		snapshot ("/NRO/document/", "/safety/long/NRO/document/");
	## Task 5 : BU de history
		snapshot ("/NRO/history/", "/safety/long/NRO/history/");
	## Task 6 : BU de /shared
		snapshot ("/shared/", "/safety/long/shared/");

	##########################################################################
	## Task 7 : vidage du /NRO/tmp et des logs samba
	exec("rm -Rf /NRO/tmp/*");
	exec("rm -f /var/log/samba/__ffff*");
?>