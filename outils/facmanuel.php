<?php
define("NIVO", "../");

include NIVO.'includes/entete.php';

require_once NIVO.'classes/facture.php';
require_once NIVO.'classes/anim.php';
require_once NIVO.'classes/vip.php';
require_once NIVO.'classes/merch.php';

if (($_REQUEST['act'] == 'update') and ($_REQUEST['idman'] > 0)) {
	$DB->MAJ('creditdetail');
	$not = $_REQUEST['idman'].' updated to '.$_REQUEST['units'];
}



########################################################################
#### Search ########################################################################
$fac = $DB->getRow("SELECT cd.*, cr.facref FROM creditdetail cd LEFT JOIN credit cr ON cd.idfac = cr.idfac WHERE units = 0 AND poste IN (700100, 700110, 700120, 700130) AND cd.description NOT LIKE '' AND cd.datemodif <= '2009-11-10 07:00:00' ORDER BY RAND() LIMIT 0,1");
$count = $DB->getOne("SELECT COUNT(*) FROM creditdetail WHERE units = 0 AND poste IN (700100, 700110, 700120, 700130) AND description NOT LIKE '' AND datemodif <= '2009-11-10 07:00:00' ");

if ($fac['facref'] > 0) $rfac = new facture($fac['facref']);

?>
<div id="centerzonelarge">
	<h1>reste : <?php echo $count ?></h1>
	<div align="center">
		<h2>NC : <?php echo $fac['description'] ?> : <?php echo feuro($fac['montant']) ?></h2>
		<h2>FAC : <?php echo $rfac->Detail['prestations'] ?> : <?php echo feuro($rfac->CompteHoriz[$fac['poste']]) ?></h2>

		<form action="?act=update" method="post" accept-charset="utf-8">
			<input type="hidden" name="idman" value="<?php echo $fac['idman'] ?>">
			<input type="text" name="units" value="<?php echo $fac['units'] ?>" id="nits">

			<p><input type="submit" value="Update"></p>
		</form>
	</div>
</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$("#nits").focus();
		$("#nits").select();
	});
</script>
<?php 
include NIVO.'includes/pied.php';
?>