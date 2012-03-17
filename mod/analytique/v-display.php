<?php
$notin = array();

$datein = fdatebk($_REQUEST['datein']);
$dateout = fdatebk($_REQUEST['dateout']);

$easclients = array(1713,2346,2933,2929,2931,2316,2651,2928);

$secteurs = Conf::read('Secteurs');

## Recherche de toutes les factures 2006 pour des clients étrangers
if (!empty($_REQUEST['idclient'])) $quid = " AND f.idclient = ".$_REQUEST['idclient'];

$factures = $DB->getArray("SELECT
			f.idfac, f.datefac, f.secteur, f.modefac,
			f.idclient, c.societe
		FROM facture f
			LEFT JOIN client c ON c.idclient = f.idclient
		WHERE f.datefac BETWEEN '".$datein."' AND '".$dateout."' ".$quid);

### tableau de datas
foreach ($factures as $row) {
	if (!in_array($row['idfac'], $notin))
	{
		$fac = new facture($row['idfac'], "FAC");

		## tableau client
		$infosclients[$row['idclient']]['societe'] = "(".$row['idclient'].") ".$row['societe'];

		## Nombre d'heures et montant
		$tbl[$row['secteur']][$row['idclient']]['nbheures'] += $fac->DetHeure;
		$tbl[$row['secteur']][$row['idclient']]['montant'] += $fac->MontHTVA;

		## tableau découpage des montants
		foreach ($fac->CompteHoriz as $code => $montant) {
			$tbl[$row['secteur']][$row['idclient']]['postescompta'][$code] += $montant;
		}
	}
}

$prestaposte = array(
	'1' => '700120',
	'2' => '700100',
	'3' => '700110',
	'4' => '700130'
);

## VIP ##
$scp[1] = array(
	'700120' => 'Prestations',
	'700220' => 'Déplacements',
	'700125' => 'Briefing',
	'700420' => 'Catering',
	'700933' => 'Dimona',

	'700320' => 'Loc Uniformes',
	'700920' => 'Frais remboursés',
	'700820' => 'Loc Vehicule',
	'700720' => 'Essence',
	'700620' => 'Parking',
	'700520' => 'Loc Stock',
	'700570' => 'Loc stand',
    '700960' => 'Coursier',

	'A' => '',
	'B' => '',
	'C' => '',
	'D' => '',
	'E' => '',
	'F' => '',

	'703000' => 'Prestations Informatique'
);

## ANIM ##
$scp[2] = array(
	'700100' => 'Prestations',
	'700200' => 'Déplacements',
	'700105' => 'Briefing',
	'700400' => 'Catering',
	'700931' => 'Dimona',

	'700300' => 'Loc Uniformes',
	'700900' => 'Frais remboursés',
	'700800' => 'Loc Vehicule',
	'700700' => 'Essence',
	'700600' => 'Parking',
	'700500' => 'Loc Stock',
	'700550' => 'Loc stand',
    '700940' => 'Coursier',

	'700941' => 'Gobelets',
	'700942' => 'Serviettes',
	'700943' => 'Four',
	'700944' => 'Cure dents',
	'700945' => 'Cuillere',
	'700946' => 'Rechaud',

	'G' => ''
);

## MERCH ##
$scp[3] = array(
	'700110' => 'Prestations',
	'700210' => 'Déplacements',
	'700115' => 'Briefing',
	'700410' => 'Catering',
	'700932' => 'Dimona',

	'700310' => 'Loc Uniformes',
	'700910' => 'Frais remboursés',
	'700810' => 'Loc Vehicule',
	'700710' => 'Essence',
	'700610' => 'Parking',
	'700510' => 'Loc Stock',
	'700560' => 'Loc stand',
    '700950' => 'Coursier',

	'A' => '',
	'B' => '',
	'C' => '',
	'D' => '',
	'E' => '',
	'F' => '',

	'G' => ''
);

## EAS ##
$scp[4] = array(
    '700130' => 'Prestations',
    '700230' => 'Déplacements',
    '700135' => 'Briefing',
    '700430' => 'Catering',
    '700934' => 'Dimona',

	'700330' => 'Loc Uniformes',
    '700930' => 'Frais remboursés',
	'700830' => 'Loc Vehicule',
	'700730' => 'Essence',
	'700630' => 'Parking',
	'700530' => 'Loc Stock',
	'700580' => 'Loc stand',
    '700970' => 'Coursier',

	'A' => '',
	'B' => '',
	'C' => '',
	'D' => '',
	'E' => '',
	'F' => '',

	'G' => ''
);

?>
<style type="text/css" media="screen">
	.ri {
		text-align: right;
	}
</style>
<div id="centerzonelarge">
	<h1>Periode : du <?php echo fdate($datein) ?> au <?php echo fdate($dateout) ?></h1>
	<div id="tabs">
		<ul>
<?php foreach($secteurs as $scode => $secteur) echo '<li><a href="#sec_'.$scode.'">'.$secteur.' ('.count($tbl[$scode]).')</a></li>'; ?>
		</ul>
<?php

foreach ($scp as $sect => $data) {
	echo '<div id="sec_'.$sect.'">';
	if (isset($tbl[$sect])) { ?>
	<table class="sortable rowstyle-alt no-arrow" border="0" width="90%" cellspacing="1" align="center">
		<thead>
			<tr>
				<th class="sortable-text">Client</th>
				<?php foreach ($data as $hpost => $intitule) { echo '<th class="sortable-numeric">'.$intitule.'</th>'; } ?>
				<th class="sortable-numeric">Total €</th>
				<th class="sortable-numeric">Heures</th>
				<th class="sortable-numeric">Tarif Moyen €/h</th>
			</tr>
		</thead>
		<tbody>
	<?php
	foreach ($tbl[$sect] as $idclient => $cldata) {
		## Recupère les totaux des notes de crédit et déduit 
		$nc = $DB->getArray("SELECT cd.poste, SUM(cd.montant) as montant, SUM(cd.units) as nbheure
			FROM creditdetail cd 
				LEFT JOIN credit cr ON cd.idfac = cr.idfac
				LEFT JOIN facture f ON cr.facref = f.idfac
			WHERE f.idclient = ".$idclient." AND f.secteur = ".$sect."
				AND f.datefac BETWEEN '".$datein."' AND '".$dateout."'
			GROUP BY cd.poste ", "poste");
			
		foreach ($nc as $poste => $ncd) {
			$cldata['montant'] -= $ncd['montant'];
			$cldata['nbheures'] -= $ncd['nbheure'];
		}
		## / fin deduction des NC
		
		$sectot += $cldata['montant'];
		$heuretot += $cldata['nbheures'];
		
		## Tarif Moyen
		if (abs($cldata['nbheures']) > 0) {
			$tarmoyen = ($cldata['postescompta'][$prestaposte[$sect]] - $nc[$prestaposte[$sect]]['montant']) / $cldata['nbheures'];
		} else {
			$tarmoyen = 0;
		}

		foreach ($data as $hpost => $intitule) {
			$totp[$hpost] += $cldata['postescompta'][$hpost] - $nc[$hpost]['montant'];
		}

		## Affichage
		echo '<tr>
				<td>'.$infosclients[$idclient]['societe'].'</td>';

		foreach ($data as $hpost => $intitule) {
			echo '<td class="ri">'.((abs($cldata['postescompta'][$hpost] - $nc[$hpost]['montant']) > 0)?(number_format($cldata['postescompta'][$hpost] - $nc[$hpost]['montant'], 2, ',', '')):'').'</td>';
		}

		echo '<td class="ri">'.number_format($cldata['montant'], 2, ',', '').'</td>
			<td class="ri">'.number_format($cldata['nbheures'], 2, ',', '').'</td>
			<td class="ri">'.number_format($tarmoyen, 2, ',', '').'</td>
		</tr>';
	} ?>
		</tbody>
		<tr>
			<th>Totaux</th>
<?php foreach ($data as $hpost => $intitule) {
		echo '<th class="ri">'.number_format($totp[$hpost], 2, ',', '').'</th>';
} ?>
			<th class="ri"><?php echo number_format($sectot, 2, ',', '') ?></th>
			<th class="ri"><?php echo number_format($heuretot, 2, ',', '') ?></th>
			<th class="ri"><?php echo number_format($totp[$prestaposte[$sect]] / $heuretot, 2, ',', '') ?></th>
		</tr>
	</table>
<?php
	}
	echo '</div>';

	unset($totp);
	unset($sectot);
	unset($heuretot);
} ?>
	</div>
</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('#tabs').tabs();
	});
</script>