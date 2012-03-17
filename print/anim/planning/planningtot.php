<?php
$animations = $DB->getArray('SELECT 
	count(*) AS newclient,
	j.idcofficer
	
	FROM animation an
	LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
	LEFT JOIN client c ON j.idclient = c.idclient
	LEFT JOIN cofficer o ON j.idcofficer = o.idcofficer
	
	WHERE an.idanimation IN('.implode(", ", $_POST['print']).')
	
	GROUP BY o.idcofficer
	ORDER BY c.societe
');

$xidcofficer = 'zz';

echo '<table width="95%">';

		$pathpdf = 'document/temp/anim/planning/';
		$jobnum = str_repeat('0', 5 - strlen($_GET['idanimjob'])).$_GET['idanimjob']; 
		$nompdf = 'planning ext glob-'.remaccents($_SESSION['prenom']).'-'.date("Ymd").'.pdf';
		$pdf = pdf_new();
		pdf_open_file($pdf, Conf::read('Env.root').$pathpdf.$nompdf); # définit l'emplacement de la sauvegarde
		# Infos pour le document
		pdf_set_info($pdf, "Author", $_SESSION['prenom']);
		pdf_set_info($pdf, "Title", "Planning Anim");
		pdf_set_info($pdf, "Creator", "NEURO");
		pdf_set_info($pdf, "Subject", "Planning Anim");
		
		######## Variables de taille  ###############
		$LargeurPage = 595; # Largeur A4
		$HauteurPage = 842; # Hauteur A4
		$MargeLeft = 30;
		$MargeRight = 30;
		$MargeTop = 30;
		$MargeBottom = 30;
		
		
		$np = 1; # Numéro de la première page
		######## Variables de taille  ###############
		$LargeurUtile = $LargeurPage - $MargeRight - $MargeLeft;
		$HauteurUtile = $HauteurPage - $MargeTop - $MargeBottom;

foreach ($animations as $infos) {
	$idcofficer = $infos['idcofficer'];
	if ($xidcofficer != $idcofficer) 
	{
		$i++;
		if (fmod($i, 2) == 1) {
			$trclient1 = '<tr>';
			$trclient2 = '';
		} else {
			$trclient1 = '';
			$trclient2 = '</tr>';
		}

		$xidclient = $idclient;
		$dernier = $infos['newclient'];

		include 'planning2.php';

	}
}
pdf_end_document($pdf, '');
		pdf_delete($pdf); # Efface le fichier en mémoire
		# Lien vers le PDF
		?>
		<?php echo $trclient1; ?>
		<td align="left">
		
		<A href="<?php echo NIVO.$pathpdf.$nompdf ;?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"> Global</A><br>
		</td>
		<?php echo $trclient2; ?>

</table>
