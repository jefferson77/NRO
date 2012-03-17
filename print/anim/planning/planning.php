<?php
if (empty($_SESSION['animjobmissionsort'])) $_SESSION['animjobmissionsort'] = $_SESSION['animmissionsort'];


		if ($_GET['web'] == 'web') { ### QUE POUR LE WEB	
			# VARIABLE SELECT
			$animjobsort = $_GET['sort'];
			$animjobquod = $_SESSION['animjobmissionquod'];
		} else {
			if ($_GET['historic'] == 'historic') {
				$animjobsort = $_SESSION['animhistoricsort'];
				$animjobquid = $_SESSION['animhistoricquid'];
				$animjobquod = $_SESSION['animhistoricquod'];
			} else {
				$animjobsort = $_SESSION['animmissionsort'];
				$animjobquid = $_SESSION['animmissionquid'];
			}
		}
		

		### SORT ###
			# echo $animjobsort.'<br>';
			if (($animjobsort == 'p.pnom, p.pprenom') or ($animjobsort == 'p.idpeople'))
			{
				$sort = 'p.pnom, p.pprenom, an.datem ';
			}
			else
			{
				if ($animjobsort == 's.codeshop') {
					$sort = 's.societe, s.ville, an.datem, p.pnom, p.pprenom ';
				} else {
					if ($animjobsort == 'an.datem') {
						$sort = 'an.datem, p.pnom, p.pprenom ';
					} else {
						$sort = 'an.weekm, s.societe, s.ville, an.datem, p.pnom, p.pprenom ';
					}
				}
			}
		#/## SORT ###

		#if ($_GET['web'] != 'web') { ### PAS POUR LE WEB	
			include 'planningtot.php';
		#} ### PAS POUR LE WEB		

$recherche = '
	SELECT count(*) AS newclient,
	j.idcofficer,
	an.weekm, an.produit,
	a.prenom, a.nom,
	c.societe as clsociete,
	c.idclient as idclient,
	o.docpref as docpref,
	o.onom as onom,
	o.oprenom as oprenom,
	c.societe
	FROM animation an
	LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
	LEFT JOIN client c ON j.idclient = c.idclient
	LEFT JOIN cofficer o ON j.idcofficer = o.idcofficer
	LEFT JOIN agent a ON j.idagent = a.idagent
	';

if ($_GET['web'] == 'web') {
	$recherche .= $animjobquid.' GROUP BY an.weekm ORDER BY an.datem';
} else {
	$recherche .= 'WHERE an.idanimation IN('.implode(", ", $_POST['print']).') GROUP BY o.idcofficer ORDER BY c.societe';
}

###### Fin recherche DB #######

$detail = new db();
$detail->inline("SET NAMES latin1");
$detail->inline($recherche);

$xidcofficer = 'zz';
$weekm = 'zz';

while ($infos = mysql_fetch_array($detail->result)) 
{ 
	$idcofficer = $infos['idcofficer'];
	
	if (
		(($_GET['web'] != 'web') and ($xidcofficer != $idcofficer)) 
		or
		(($_GET['web'] == 'web') and ($weekm != $infos['weekm']))
		)
	{
		$weekm = $infos['weekm'];

		$xidclient = $idclient;
		$dernier = $infos['newclient'];
		# Path PDF
		$pathpdf = 'document/temp/anim/planning/';
		$jobnum = str_repeat('0', 5 - strlen($_GET['idanimjob'])).$_GET['idanimjob']; 
		$nompdf = 'planning-ext-'.$idcofficer.'-week'.$infos['weekm'].'-'.date("Ymd").'.pdf';

		$pdf = pdf_new();
		pdf_open_file($pdf, Conf::read('Env.root').$pathpdf.$nompdf); # définit l'emplacement de la sauvegarde
		$parcofficer[$idcofficer][] = Conf::read('Env.root').$pathpdf.$nompdf;
		# Infos pour le document
		pdf_set_info($pdf, "Author", $infos['prenom'].' '.$infos['nom']);
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
		
		
		$np = 0; # Numéro de la première page
		######## Variables de taille  ###############
		$LargeurUtile = $LargeurPage - $MargeRight - $MargeLeft;
		$HauteurUtile = $HauteurPage - $MargeTop - $MargeBottom;
		
		# Pages de détail ###########
			include 'planning2.php';
		# Fin Pages de détail #######
		pdf_end_document($pdf, '');
		pdf_delete($pdf); # Efface le fichier en mémoire
	}
}
?>