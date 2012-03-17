<?php

include_once NIVO.'print/commun/notecredit.php';
include_once NIVO.'print/dispatch/dispatch_functions.php';

#### Sortie des Notes de crédit
if ($web != 'web') { 
	echo '<div id="centerzonelarge">'; 
} else { 
	echo '<div id="infozoneweb">'; 
	include NIVO.'classes/document.php';
}

if (!empty($_POST['creds'])) {
	$f = explode(",", $_POST['creds']);
	
	foreach ($f as $val) {
		$val = trim($val);
		if (is_numeric($val)) $creds[] = $val;
	}
} elseif (!empty($_POST['print'])) {
	$creds = $_POST['print'];
} else {
	$creds[] = $_GET['idnc'];
}
	
if (count ($creds) >= 1) {

	foreach($creds as $cred) {
		$idcofficer = $DB->getOne("	SELECT f.idcofficer 
									FROM credit c
									LEFT JOIN facture f ON c.facref = f.idfac
									WHERE c.idfac = $cred
								");
		$temp = print_note_credit($cred, 'yes', $_POST['dup']);
		$notescreds[$idcofficer][] = $temp;
		$anotecred[] = $temp;
	}
	?>
	<div align="center">
	<br>
	<?php 

	if ($web == 'web') { 
		$printpage = ' NC n&deg; '.$_POST['creds']; 

		$book = reliure($anotecred,'NCre');
		$filepath = pathinfo($book['path']);
		$filename = $filepath['filename'];
	 ?>

			<img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0">
			<A href="<?php echo NIVO.'../document/temp/NCre/'.$filename.'.pdf'; ?>" target="_blank">Print <?php echo $printpage; ?></A>
			<br>
		</div>
	<?php

	}
	else
	{
		generateSendTable($notescreds,'cofficer','temp/NCre','NCre','Notes de Crédit');
	}
	} else {
?> 

Aucun num&eacute;ro de Note de Crédit entr&eacute;
</div>
<?php } ?> 
</div>