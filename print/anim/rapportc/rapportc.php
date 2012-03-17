<?php
$pathpdf = 'document/temp/anim/rapportc/';

$animations = $DB->getArray("SELECT
	 	count(*) AS newclient, j.idcofficer, count(pr.idanimproduit) AS numprod, c.societe AS clsociete, co.onom, co.oprenom, co.docpref, c.societe
	FROM animation an
		LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
		LEFT JOIN agent a ON j.idagent = a.idagent
		LEFT JOIN client c ON j.idclient = c.idclient
		LEFT JOIN cofficer co ON j.idcofficer = co.idcofficer
		LEFT JOIN animproduit pr ON an.idanimation = pr.idanimation
	".$jobquid."
	GROUP BY j.idcofficer
	ORDER BY c.societe");

foreach ($animations as $infos) {
	if ($infos['numprod'] > 0) {
		$nompdf = 'rapportc-'.$infos['idcofficer'].'-'.date("Ymd").'.pdf';

		$pdf = pdf_new();
		pdf_open_file($pdf, Conf::read('Env.root').$pathpdf.$nompdf); # définit l'emplacement de la sauvegarde

		# Infos pour le document
		pdf_set_info($pdf, "Author", $infos['prenomagent'].' '.$infos['nomagent']);
		pdf_set_info($pdf, "Title", "Rapport client Anim");
		pdf_set_info($pdf, "Creator", "NEURO");
		pdf_set_info($pdf, "Subject", "Rapport client Anim");

		######## Variables de taille  ###############
		$LargeurPage = 595; # Largeur A4
		$HauteurPage = 842; # Hauteur A4
		$MargeLeft = 30;
		$MargeRight = 30;
		$MargeTop = 30;
		$MargeBottom = 30;

		$np = 0; # Numéro de la première page
		######## Variables de taille  ###############
		$LargeurUtile = $LargeurPage - $MargeRight - $MargeLeft;
		$HauteurUtile = $HauteurPage - $MargeTop - $MargeBottom;

		# Pages de détail ###########
		include 'rapportc2.php';

		pdf_end_document($pdf, '');
		pdf_delete($pdf);
		
		$parcofficer[$infos['idcofficer']][] = Conf::read('Env.root').$pathpdf.$nompdf;
	}
}
?>