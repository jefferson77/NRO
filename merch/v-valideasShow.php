<?php

	require_once(NIVO."print/dispatch/dispatch_functions.php");

	$PhraseBas = 'R&eacute;sultats EAS valideas';

	$_POST['date1'] = fdatebk($_POST['date1']);
	$_POST['date2'] = fdatebk($_POST['date2']);

	$shops = $DB->getArray("SELECT 
			me.idshop, 
			a.prenom, a.nom, a.agsm, 
			s.societe AS ssociete, s.ville AS sville, s.slangue, s.codeshop, s.cp, s.snom, s.sprenom, s.fax
		FROM merch me
			LEFT JOIN agent a ON me.idagent = a.idagent 
			LEFT JOIN shop s ON me.idshop = s.idshop
		WHERE me.datem BETWEEN '".$_POST['date1']."' AND '".$_POST['date2']."' AND
			me.idshop IN (".implode(', ', $_POST['idshop']).")
		GROUP BY me.idshop
		ORDER BY sville, me.datem");

?>
<div id="centerzonelarge">
	<fieldset>
		<legend>R&eacute;sultats pour EAS valideas (<?php echo count($shops);?>)</legend>
			<table class="standard" border="0" cellspacing="1" cellpadding="2" align="center" width="90%">
				<?php if (count($shops) > 0) { ?>
				<tr>
					<td align="center" colspan="2">
						<?php 
						
						foreach ($shops as $valideas) {
							
							$pathpdf = 'document/temp/merch/eas/';
							$nompdf = 'Rap-valideas-'.$valideas['idshop'].'-'.date("Ymd").'.pdf';

							$LargeurPage = 595; # Largeur A4
							$HauteurPage = 842; # Hauteur A4
							$MargeLeft = 30;
							$MargeRight = 30;
							$MargeTop = 30;
							$MargeBottom = 30;

							######## Variables de taille  ###############
							$LargeurUtile = $LargeurPage - $MargeRight - $MargeLeft;
							$HauteurUtile = $HauteurPage - $MargeTop - $MargeBottom;


							$pdf = pdf_new();
							$nbpages = 0;

							pdf_open_file($pdf, Conf::read('Env.root').$pathpdf.$nompdf);

							# Infos pour le document
							pdf_set_info($pdf, "Author", "neuro");
							pdf_set_info($pdf, "Title", "valideas EAS");
							pdf_set_info($pdf, "Creator", "NEURO");
							pdf_set_info($pdf, "Subject", "EAS");
							
						## Communs ##
							$valideas['agent'] = $valideas['prenom'].' '.$valideas['nom'].' - '.$valideas['agsm'];
							$valideas['shop'] = $valideas['ssociete'].' ('.$valideas['idshop'].')';
			
							setlocale(LC_TIME, strtolower($valideas['slangue']));
			
							switch ($valideas['slangue']) {
								case "FR": include NIVO.'print/merch/eas/fr.php'; break;
								case "NL": include NIVO.'print/merch/eas/nl.php'; break;
								default: echo "<br>Langue manquante pour le shop : ".$valideas['sville']."<br>";
							}

							echo ' - '.$valideas['sville'];
							
							## valideas
							if (in_array("valideas", $_POST['doc'])) {
								include NIVO.'print/merch/eas/valideas.php';
							}

							## Detail EAS
							if (in_array("reas", $_POST['doc'])) {
								include NIVO.'print/merch/eas/easmagasin.php';
							}
							
							if ($nbpages > 0) {
								pdf_end_document($pdf, '');
								pdf_delete($pdf);
							} else {
								$warning[] = "Aucune Donnée ne correspond à votre recherche";
							}
						}
												
						foreach($fichiersparpeople as $key => $row)
						{
							$fichiersparpeople[$key] = array_unique($row);
						}
						
						generateSendTable($fichiersparpeople, $_POST['typeSend'], "temp/MVea", 'MVea', "Validation EAS");
						
 ?>						
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td>
						<b>Date</b>
					</td>
					<td>
						De : <?php echo fdate($_POST['date1']);?> &agrave; : <?php echo fdate($_POST['date2']);?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						&nbsp;
					</td>
				</tr>
			</table>
		<br>
		<div align="center">
			<a href="?act=valideas"><font color="white">Retour &agrave; la recherche</font></a>
		</div>
	</fieldset>
</div>