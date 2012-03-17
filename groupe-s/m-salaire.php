<?php
/**
 * Ajout un/les peoples a la table des paiements du mois
 * 
 * @return void
 * @author Nico
 **/
function salaire_add ($codepeople, $table)
{
	global $DB, $warning, $notify, $error;

	if (preg_match("/^salaires(bis|ter)*(?<annee>\d{2})(?<mois>\d{2})$/", $table, $m)) {

		## Mode ##
		if ($codepeople == 'ALL') {
			$idpeople = 'ALL';
		} elseif(is_numeric($codepeople)) {
			$idpeople = $DB->getOne("SELECT `idpeople` FROM `people` WHERE `codepeople` = ".$codepeople);
		} else {
			$error[] = "salaire_add : code people incorrect";
			return;
		}

		$ttime = mktime (0,0,0,$m['mois'],01,'20'.$m['annee']);

		$datein = date("Y-m-01", $ttime);
		$dateout = date("Y-m-t", $ttime);

		$ptable = paytable($idpeople, $datein, $dateout);
		
		foreach ($ptable as $idpeople => $dtable) {
			foreach ($dtable as $inf) $vals[] = "('".$idpeople."', '".$inf['date']."', '".$inf['h150']."', '".$inf['h200']."')";
		}

		$DB->inline("INSERT INTO grps.".$table." (`idpeople`, `date`, `modh150`, `modh200`) VALUES ".implode(", ", $vals).";");

		$notify[] = 'Salaire du people n°'.$codepeople.' ajouté';
	} else {
		$warning[] = 'Aucune table de paiement sélectionnée';
	}
}

/**
 * Converti le nom de la table SQL en human lisible
 *
 * @return void
 * @author Nico
 **/
function nomtable($table)
{
	if (preg_match("/^salaires(bis|ter)*(?<annee>\d{2})(?<mois>\d{2})$/", $table, $m)) {
		setlocale(LC_TIME, 'fr_FR');
		$nomtable = strftime("%B %Y", mktime(0, 0, 0, $m['mois'], 1, $m['annee']));

		return $nomtable;
	} else {
		return 'fonction "nomtables" : nom de table invalide';
	}
}


?>