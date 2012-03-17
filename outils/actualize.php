#!/usr/bin/php
<?php

$script = array_shift($argv);
if ($argv[0] == 'all') $argv = array('system', 'core', 'db', 'phpdoc', 'media', 'document', 'history', 'redmine');


foreach ($argv as $argument) {
	switch ($argument) {
		case 'system':
			echo "Mise a Jour du Système\r\n------------------------------\r\n";
			system('yum clean all');
			system('yum -y update');
		break;
		case 'core':
			echo "Mise a Jour du Core\r\n------------------------------\r\n";
			system('svn update /NRO/core');
		break;
		case 'static':
			system('rsync --include-from=/NRO/core/nro/static.conf --exclude="*" -avzm --delete -e ssh /NRO/core/ piixdgur@ftp.piix.be:~/www/static/NRO/');
		break;
		case 'db':
			echo "Mise a Jour de la DB\r\n------------------------------\r\n";
			system('rsync -avz -e ssh root@77.109.79.37:/NRO/media/sqldump/ /NRO/media/sqldump/');
			# rsync -avz -e ssh root@194.78.202.160:/NeoLingo/media/sqldump/ /NeoLingo/media/sqldump/
			# system('mysql -uroot -pnoctua07 < /NeoLingo/media/sqldump/NeoLingo.sql');

			system('mysql -uroot -pnoctua07 < /NRO/media/sqldump/neuro.sql');
			echo "db : neuro OK\r\n";

			system('mysql -uroot -pnoctua07 < /NRO/media/sqldump/webneuro.sql');
			echo "db : webneuro OK\r\n";

			system('mysql -uroot -pnoctua07 < /NRO/media/sqldump/grps.sql');
			echo "db : grps OK\r\n";

			system('mysql -uroot -pnoctua07 < /NRO/media/sqldump/dimona.sql');
			echo "db : dimona OK\r\n";

			echo 'DB mise a jour @'.date("H:i:s")."\r\n";
		break;
		case 'phpdoc':
		echo "Mise a Jour de la documentation Offline de PHP\r\n------------------------------\r\n";
			system("rsync -avzC --timeout=600 --delete --delete-after --include='manual/fr/' --include='manual/fr/**' --exclude='manual/**' --exclude='distributions/**' --exclude='extra/**' rsync.php.net::phpweb /webroot/phpdoc");
		break;
		case 'media':
			echo "Mise a Jour de Media\r\n------------------------------\r\n";
			system("rsync -avz --exclude='sqldump/**' -e ssh root@77.109.79.37:/NRO/media/ /NRO/media/");
		break;
		case 'document':
			echo "Mise a Jour de document\r\n------------------------------\r\n";
			system("rsync -avz -e ssh root@77.109.79.37:/NRO/document/ /NRO/document/");
		break;
		case 'history':
			echo "Mise a Jour de history\r\n------------------------------\r\n";
			system("rsync -avz -e ssh root@77.109.79.37:/NRO/history/ /NRO/history/");
		break;
		case 'redmine':
			echo "Mise a Jour de redmine\r\n------------------------------\r\n";
			system('mysql -uroot -pnoctua07 < /NRO/media/sqldump/redmine.sql');
			system("svn update /NRO/tool/redmine");
		break;
	}
}

?>