<?php
#########################################################################################################################################################################
### Page de Rapport People ##############################################################################################################################################
#########################################################################################################################################################################
# TODO : !! moteur PDFlib, a remplacer
require_once(NIVO."nro/fm.php");
require_once(NIVO.'classes/document.php');

##############################################################################################################################
# renvoie les path 'dossier', 'full', 'history', 'filename'
function path_rapportpbetv($idpeople, $idshop, $week, $year) {
	global $DB;	
	$idanimjob = $DB->getOne("SELECT idanimjob FROM animation 
		WHERE idpeople = ".$idpeople."
			AND idshop = ".$idshop."
			AND weekm = ".$week."
			AND YEAR(datem) = ".$year."
		GROUP BY idanimjob");
		
## declarations
    $fname = prezero($idpeople, 6).'-'.prezero($idshop, 5).'-'.prezero($week, 2).'-'.$year;

    $lespath['dossier'] = Conf::read('Env.root').'document/anim/'.prezero($idanimjob, 5).'/rapportp/';
    $lespath['filename'] = $fname.'.pdf';
    $lespath['full'] = $lespath['dossier'].$lespath['filename'];
    $lespath['history'] = Conf::read('Env.root').'history/anim/'.prezero($idanimjob, 5).'/rapportp/';

    if (file_exists($lespath['full'])) $lespath['historyfull'] = $lespath['history'].$fname.'-'.date ("Ymd.Hi", filemtime($lespath['full'])).'.pdf';

## check et creation si besoin
    if(!is_dir($lespath['dossier'])) mkdir($lespath['dossier'], 0777, true); #le 'true' c'est pour le recursif
    if(!is_dir($lespath['history'])) mkdir($lespath['history'], 0777, true); 

    return $lespath;
}

##############################################################################################################################
# sauve un contrat vip d'une mission donnée et retourne le chemin du fichier
function print_rapportpbetv($peopshopweekyear) {
	list($idpeople, $idshop, $week, $year) = explode("-", $peopshopweekyear);
	
###### GEt infos ######
	global $DB;

    $row = $DB->getRow("SELECT
			GROUP_CONCAT(DISTINCT an.datem ORDER BY an.datem SEPARATOR ',') AS dates,
			p.codepeople, p.pnom, p.pprenom,
			s.societe AS ssociete, s.ville AS sville
		FROM animation an
			LEFT JOIN people p ON an.idpeople = p.idpeople
			LEFT JOIN shop s ON an.idshop = s.idshop
		WHERE an.idpeople = ".$idpeople."
			AND an.idshop = ".$idshop."
			AND an.weekm = ".$week."
			AND YEAR(an.datem) = ".$year."
		GROUP BY CONCAT(an.idpeople, '-', an.idshop, '-', an.weekm, '-', YEAR(an.datem))");

	if (!empty($idpeople)) {
		
	###### check dir ######
		$path = path_rapportpbetv($idpeople, $idshop, $week, $year);

		###### check file ######
		if (file_exists($path['full'])) rename($path['full'], $path['historyfull']);

	## Open PDF
		$pdf= new fpdi('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetAutoPageBreak(0);

	## Template
		$pagecount = $pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"].'/print/anim/rapportp/t-rapportBetv.pdf');
		$page1 = $pdf->ImportPage(1);

		$pdf->addPage();
		$pdf->useTemplate($page1,0,0);

	## Datas
		$pdf->SetFont('Times','B',13);

		$pdf->SetXY(35, 44.5); 
		$pdf->Cell(81, 8, $row['pprenom'].' '.$row['pnom'], 0, 0, 'C');		# Nom du people
		
		$pdf->SetXY(35, 56.5); 
		$pdf->MultiCell(81, 5, $row['ssociete']."
".$row['sville'], 0, 'C');					# POS

		$lesdates = explode(",", $row['dates']);
		setlocale(LC_TIME, 'fr_FR');
		foreach ($lesdates as $date) $formatdates[] = strftime("%e %b", strtotime($date));

		$pdf->SetFont('Times','B',12);
		$pdf->SetXY(35, 79.5); 
		$pdf->Cell(81, 10, implode(", ", $formatdates), 0, 0, 'C');			# Date

	## Close PDF
		$pdf->Output($path['full'], 'F');

		return $path['full'];
	} else {
		return "";
	}
}
?>