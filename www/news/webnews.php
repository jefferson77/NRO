<?php
define('NIVO', '../../');

## Classes
require_once(NIVO."nro/fm.php");

## phrase book
if (in_array($_GET['l'], array('fr', 'nl', 'en'))) include '../lang/'.$_GET['l'].'.php';
else {
	include '../lang/fr.php';
	$_GET['l'] = 'fr';
}

setlocale(LC_TIME, Conf::read('Env.locale_'.$_GET['l']));

$news = $DB->getArray("SELECT * FROM webneuro.webnews WHERE `online` = 'oui' ORDER BY datepublic DESC LIMIT ".Conf::read('nbrnews'));

foreach ($news as $row) { ?>
		<div class="newstitre"><?php echo nl2br($row['titre'.$_GET['l']]) ?></div>
		<div class="newsdate"><?php echo strftime('<span class="fmois">%d %b</span><br><span class="fyear">%Y</span>',strtotime($row['datepublic'])) ?></div>
		<div class="newstexte"><?php echo nl2br($row['texte'.$_GET['l']]) ?></div>
<?php } ?>
