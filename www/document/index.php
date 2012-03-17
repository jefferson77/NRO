<?php
define("NIVO", "../../");

include NIVO.'nro/fm.php';

$row = $DB->getRow("SELECT webdoc_id, path, idcontact, typecontact FROM webdocs WHERE hash = '".$_GET['doc']."'");

## Update du has_read
if(!empty($row) and file_exists($row['path'])) {
	$_POST['webdoc_id'] = $row['webdoc_id'];
	$_POST['has_read'] = 1;
	$DB->MAJ('webdocs');

	$finfo = pathinfo($row['path']);

	header('Content-type: application/pdf');
	header('Content-Disposition: attachment; filename="'.$finfo['basename'].'"');
	readfile($row['path']);

} else {
	include NIVO.'www/includes/entete.php';

	#### phrasebook ####################
	switch ($_REQUEST['l']) {
		case 'NL':
			include NIVO.'print/dispatch/nl.php';
		break;

		case 'FR':
		default:
			include NIVO.'print/dispatch/fr.php';
		break;
	}
	#### phrasebook ####################

?>
<div class="logbloc">
	<img src="<?php echo Conf::read('WebSiteURL') ?>illus/warning.png" width="128" height="128" alt="Warning" style="float: left; margin-right: 15px;">
	<div id="name" class="greentitre"><?php echo $phrase['oups']; ?></div>
	<p><?php echo $phrase['exite_pas'] ?></p>
	<span>
		<?php echo $phrase['verifiez'] ?><br><br>
		<hr>
		<?php echo $phrase['contactez'] ?><a href="mailto:nico@exception2.be">nico@exception2.be</a>
	</span>
</div>
<?php
	include NIVO.'www/includes/pied.php';
} ?>