<?php
define('NIVO', '../../');

$Titre = 'EAS';
$PhraseBas = 'G&eacute;n&eacute;ration du rapport Mensuel';

# Entete de page
include NIVO."includes/entete.php";
include NIVO."merch/easclients.php";

## init vars
if (!isset($_POST['mois'])) $_POST['mois'] = array();
if (!isset($_POST['sem'])) $_POST['sem'] = array();
if (!isset($_POST['hebdomadaire'])) $_POST['hebdomadaire'] = '';

?>
<div id="leftmenu"> </div>
<div id="infozone">
<?php
##########################################################################################################################################################################
# >> ################# Formulaire de choix période #####
if ((count($_POST['mois']) == 0) and (count($_POST['sem']) == 0)) {
    setlocale(LC_TIME, 'fr_FR');
    
?>
    <h1>Rapport Périodique EAS</h1>
    Cochez les mois ou semaines pour lesquels vous voulez obtenir les rapports périodiques EAS
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
    <table width="80%" align="center">
        <tr>
            <td colspan="2" align="center">
                <fieldset>
                    <legend>Client</legend>
                    <?php
                    foreach ($clients as $key => $value) {
                        echo '<input type="radio" name="client" value="'.$key.'"'.(($value=='Carrefour')?' checked':'').'>&nbsp;'.$value.'&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                    ?>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td width="50%" align="center">
                <fieldset>
                    <legend>Mensuel</legend>
                    <table border="0" align="center">
                    <?php
                    for ($i=0 ; $i < 10 ; $i++) {
                        $mois = date("Y-m", strtotime('-'.(25 + ($i*30)).' days'));
                        echo '<tr><td><input type="checkbox" name="mois[]" value="'.$mois.'"> '.utf8_encode(ucfirst(strftime("%B %Y", strtotime($mois."-01")))).'</td><tr>';
                    }
                    ?>
                    </table>
                    <input type="submit" name="mensuel" value="Creer">
                </fieldset>
            </td>
            <td align="center">
                <fieldset>
                    <legend>Hebdomadaire</legend>
                    <table border="0" align="center">
                    <?php
                    for ($i=0 ; $i < 10 ; $i++) {
						$rapdate = mktime(0,0,0,date("m"),date("d") - 3 - ($i * 7),date("Y"));
						$mois = date("m", $rapdate);
                        $semaine = weekfromdate(date("Y-m-d", $rapdate));
                        $year = date("Y", $rapdate);
						if(($semaine == 1) and ($mois == 12)) $year++;
						$sem = $year.'-'.$semaine;

                        echo '<tr><td><input type="checkbox" name="sem[]" value="'.$sem.'"> Sem '.substr($sem, 5).' '.substr($sem, 0,4).'</td><tr>';
                    }
                    ?>
                    </table>
                    <input type="submit" name="hebdomadaire" value="Creer">
                </fieldset>
            </td>
        </tr>
    </table>
    </form>

</div>
<?php
# << ################## Fin Formulaire de choix période ####
##########################################################################################################################################################################
 } else {
##########################################################################################################################################################################
# >> ################# Rassemblement des Datas #####
?> 
<div align="center">
<?php
setlocale(LC_TIME, 'fr_FR');

## Classes
require_once NIVO."classes/xls/class.writeexcel_workbook.inc.php";
require_once NIVO."classes/xls/class.writeexcel_worksheet.inc.php";

if ($_POST['hebdomadaire'] == 'Creer') {
    $mode = "semaine";
    $tableau = $_POST['sem'];
} else {
    $mode = "mois";
    $tableau = $_POST['mois'];
}

foreach ($tableau as $ligne) {

    switch($mode) {
        case"semaine":
            $parts = explode("-", $ligne);
            $lesdates = weekdate($parts[1], $parts[0]);
            $datein = $lesdates['lun'];
            $dateout = $lesdates['dim'];
            $fname = "sEAS-".remaccents($clients[$_POST['client']])."-".$ligne.".xls";
            $titrexls = "RECAPITULATIF HEBDOMADAIRE par POS Sem ".$ligne;
            $linktext = "Rapport Hebdomadaire ".$ligne;
        break;
        case"mois":
            $datein = date("Y-m-01", strtotime($ligne."-01"));
            $dateout = date("Y-m-t", strtotime($ligne."-01"));
            $fname = "mEAS-".remaccents($clients[$_POST['client']])."-".$ligne.".xls";
            $titrexls = "RECAPITULATIF MENSUEL par POS";
            $linktext = "Rapport Mensuel ".ucfirst(strftime("%B %Y", strtotime($ligne."-01")));
        break;
    }

#######################################################################
# fichier

	$pathxls = Conf::read('Env.root')."media/easmensuel/";
	$workbook = &new writeexcel_workbook($pathxls.$fname);
	
#######################################################################
# Sheets

	$recaptype =& $workbook->addworksheet('Recap POS');
	$recapfamille =& $workbook->addworksheet('Recap Famille');
	$checkcaisse =& $workbook->addworksheet('Check Caisses');

#######################################################################
# Styles

	# Titre
	$bigtitre =& $workbook->addformat();
	$bigtitre->set_bold();
	$bigtitre->set_size(18);
	$bigtitre->set_align('center');	
	$bigtitre->set_align('vcenter');
	$bigtitre->set_merge();
	
	$bigtitreM =& $workbook->addformat();	
	$bigtitreM->set_bold();
	$bigtitreM->set_size(18);
	$bigtitreM->set_align('center');	
	$bigtitreM->set_align('vcenter');
	$bigtitreM->set_merge();
	
	# normalcentre
	$normalcentre =& $workbook->addformat();
	$normalcentre->set_align('center');
	$normalcentre->set_size(10);
	$normalcentre->set_merge();
	
	$normalcentreM =& $workbook->addformat();	
	$normalcentreM->set_align('center');
	$normalcentreM->set_size(10);
	$normalcentreM->set_merge();
	
	# entete
	$entete =& $workbook->addformat();
	$entete->set_align('center');
	$entete->set_align('vcenter');
	$entete->set_size(10);
	$entete->set_bold();
	$entete->set_color('white');
	$entete->set_fg_color('black');
	$entete->set_text_wrap();	
	
	# datas
	$datas =& $workbook->addformat();
	$datas->set_align('center');
	$datas->set_align('vcenter');
	$datas->set_size(10);
	$datas->set_border(1);
	$datas->set_text_wrap();
	$datas->set_num_format('# ##0');
	
	$datasB =& $workbook->addformat();
	$datasB->set_align('center');
	$datasB->set_align('vcenter');
	$datasB->set_size(10);
	$datasB->set_border(1);
	$datasB->set_bold();
	$datasB->set_num_format('# ##0');

	$datasN =& $workbook->addformat();
	$datasN->set_align('center');
	$datasN->set_align('vcenter');
	$datasN->set_size(10);
	$datasN->set_border(1);
	$datasN->set_num_format('#.0');
	
	$datasT =& $workbook->addformat();
	$datasT->set_align('center');
	$datasT->set_align('vcenter');
	$datasT->set_color('white');
	$datasT->set_fg_color('black');
	$datasT->set_size(10);
	$datasT->set_border(1);
	$datasT->set_num_format('# ##0');
	
	
	$datasTN =& $workbook->addformat();
	$datasTN->set_align('center');
	$datasTN->set_align('vcenter');
	$datasTN->set_color('white');
	$datasTN->set_fg_color('black');
	$datasTN->set_size(10);
	$datasTN->set_border(1);
	$datasTN->set_num_format('#.0');
	
	$datasPRC =& $workbook->addformat();
	$datasPRC->set_align('center');
	$datasPRC->set_align('vcenter');
	$datasPRC->set_size(10);
	$datasPRC->set_border(1);
	$datasPRC->set_num_format('0%');
	
	$datasTPRC =& $workbook->addformat();
	$datasTPRC->set_align('center');
	$datasTPRC->set_align('vcenter');
	$datasTPRC->set_color('white');
	$datasTPRC->set_fg_color('black');
	$datasTPRC->set_size(10);
	$datasTPRC->set_border(1);
	$datasTPRC->set_num_format('0%');
	
#####################################################################################################################################################################################
#####################################################################################################################################################################################
## recaptype

#######################################################################
# Tailles
	# Set the column width for columns 2 and 3
	$recaptype->set_column('A:A', 21);
	$recaptype->set_column('I:L', 23);
	
#######################################################################
# Menu

	## Recap mensuel
	$recaptype->write('A2', $titrexls, $bigtitre);
	foreach (array('C2','B2','D2','E2','F2','G2','H2') as $cell) {
		$recaptype->write_blank($cell, $bigtitreM); #merge
	}

	## Periode
	$recaptype->write('A3', ucfirst(strftime("%B %Y", strtotime($datein)))." (".fdate($datein)." -> ".fdate($dateout).")", $normalcentre);
	foreach (array('B3','C3','D3','E3','F3','G3','H3') as $cell) {
		$recaptype->write_blank($cell, $normalcentreM); #merge
	}

	## Entetes
	$recaptype->write('A5', "POS", $entete);
	$recaptype->write('B5', "Serre cols", $entete);
	$recaptype->write('C5', "Hard tags", $entete);
	$recaptype->write('D5', "Etiquettes", $entete);
	$recaptype->write('E5', "Total", $entete);
	$recaptype->write('F5', utf8_decode("Heures prestées (Mensuel)"), $entete);
	$recaptype->write('G5', "Moyenne", $entete);
	$recaptype->write('H5', "Caisse (minutes)", $entete);
	
#######################################################################
# Data

	$sql = "
SELECT m.idshop, s.codeshop, s.ville,

SUM(eas.fo1a + eas.fo2a + eas.fo3a + eas.fo4a + eas.pa1a + eas.pa2a + eas.pa3a + eas.pa4a + eas.pa5a + eas.pa6a + eas.pa7a + eas.pa8a + eas.pa9a + eas.pa10a + eas.te1a + eas.te2a + eas.te3a + eas.te4a + eas.te5a + eas.te6a + eas.te7a + eas.te8a + eas.ep1a + eas.ep2a + eas.ep3a + eas.ep4a + eas.ep5a + eas.ba1a + eas.ba2a + eas.ba3a + eas.ba4a + eas.ba5a + eas.ba6a + eas.au1a + eas.au2a + eas.au3a + eas.au4a + eas.au5a) AS totalSC,
SUM(eas.fo1b + eas.fo2b + eas.fo3b + eas.fo4b + eas.pa1b + eas.pa2b + eas.pa3b + eas.pa4b + eas.pa5b + eas.pa6b + eas.pa7b + eas.pa8b + eas.pa9b + eas.pa10b + eas.te1b + eas.te2b + eas.te3b + eas.te4b + eas.te5b + eas.te6b + eas.te7b + eas.te8b + eas.ep1b + eas.ep2b + eas.ep3b + eas.ep4b + eas.ep5b + eas.ba1b + eas.ba2b + eas.ba3b + eas.ba4b + eas.ba5b + eas.ba6b + eas.au1b + eas.au2b + eas.au3b + eas.au4b + eas.au5b) AS totalHT,
SUM(eas.fo1c + eas.fo2c + eas.fo3c + eas.fo4c + eas.pa1c + eas.pa2c + eas.pa3c + eas.pa4c + eas.pa5c + eas.pa6c + eas.pa7c + eas.pa8c + eas.pa9c + eas.pa10c + eas.te1c + eas.te2c + eas.te3c + eas.te4c + eas.te5c + eas.te6c + eas.te7c + eas.te8c + eas.ep1c + eas.ep2c + eas.ep3c + eas.ep4c + eas.ep5c + eas.ba1c + eas.ba2c + eas.ba3c + eas.ba4c + eas.ba5c + eas.ba6c + eas.au1c + eas.au2c + eas.au3c + eas.au4c + eas.au5c) AS totalET,
(SUM((TIME_TO_SEC(m.hout1) - TIME_TO_SEC(m.hin1)) + (TIME_TO_SEC(m.hout2) - TIME_TO_SEC(m.hin2))) / 3600) AS heures,
	SUM(eas.caisse) AS caisse
FROM `merch` m
LEFT JOIN mercheasproduit eas ON eas.idmerch = m.idmerch 
LEFT JOIN shop s ON m.idshop = s.idshop 
WHERE m.idclient IN (".$_POST['client'].") AND m.datem BETWEEN '$datein' AND  '$dateout'
GROUP BY m.idshop
ORDER BY s.codeshop
";



$titres = array(	
	'fo1' => "Alcool, vin, champagne",
	'fo2' => "Saumon Fum&eacute;",
	'fo3' => "Foie gras",
	'fo4' => "Gibier",
	'pa1' => "Maquillage",
	'pa2' => "Lames de rasoirs",
	'pa3' => "Cr&egrave;mes de soins",
	'pa4' => "Parfums - eaux de toilette",
	'pa5' => "Coloration",
	'pa6' => "Parapharmacie",
	'pa7' => "Colis fin d'ann&eacute;e",
	'pa8' => "Produits solaires",
	'pa9' => "Soins b&eacute;b&eacute;s",
	'pa10' => "Test grossesse",
	'te1' => "Pantys",
	'te2' => "Sac &agrave; main",
	'te3' => "Bonnets, &eacute;charpes",
	'te4' => "Chaussures",
	'te5' => "Lingerie",
	'te6' => "Vestes cuir",
	'te7' => "Pu&eacute;riculture",
	'te8' => "Jeans",
	'ep1' => "Cartouches encre",
	'ep2' => "Access, GSM",
	'ep3' => "Acc. informat. + TV + Audio",
	'ep4' => "Films photos",
	'ep5' => "Agendas",
	'ba1' => "Papeterie",
	'ba2' => "Tabac",
	'ba3' => "L&eacute;go",
	'ba4' => "Barbie",
	'ba5' => "Accessoires auto",
	'ba6' => "Sacs communaux"
);

$lesdatas = new db();
$lesdatas->inline("SET NAMES latin1");
$lesdatas->inline($sql);

$i = 5;

while ($row = mysql_fetch_array($lesdatas->result)) {

	$recaptype->write($i, 0, '('.$row['codeshop'].') '.$row['ville'], $datas);
	$recaptype->write_number($i, 1, $row['totalSC'], $datas);
	$recaptype->write_number($i, 2, $row['totalHT'], $datas);
	$recaptype->write_number($i, 3, $row['totalET'], $datas);
	$recaptype->write_formula($i, 4, '=SUM(B'.($i+1).':D'.($i+1).')', $datasB);
	$recaptype->write_number($i, 5, $row['heures'], $datas);
	$recaptype->write_formula($i, 6, '=E'.($i+1).' / F'.($i+1).'', $datasN);
	$recaptype->write_number($i, 7, $row['caisse'], $datas);

	$i++;
} 

#######################################################################
# Totaux
	$i++;

	$recaptype->write($i, 0, 'Totaux', $datasT);
	$recaptype->write_formula($i, 1, '=SUM(B6:B'.($i-1).')', $datasT);
	$recaptype->write_formula($i, 2, '=SUM(C6:C'.($i-1).')', $datasT);
	$recaptype->write_formula($i, 3, '=SUM(D6:D'.($i-1).')', $datasT);
	$recaptype->write_formula($i, 4, '=SUM(E6:E'.($i-1).')', $datasT);
	$recaptype->write_formula($i, 5, '=SUM(F6:F'.($i-1).')', $datasT);
	$recaptype->write_formula($i, 6, '=AVERAGE(G6:G'.($i-1).')', $datasTN);
	$recaptype->write_formula($i, 7, '=SUM(H6:H'.($i-1).')', $datasT);

#####################################################################################################################################################################################
#####################################################################################################################################################################################
## recapfamille

#######################################################################
# Tailles
	# Set the column width for columns 2 and 3
	$recapfamille->set_column('A:A', 21);
	$recapfamille->set_column('B:F', 10);
	
#######################################################################
# Menu

	## Recap mensuel
	$recapfamille->write('A2', "RECAPITULATIF MENSUEL par FAMILLE", $bigtitre);
	foreach (array('B2','C2','D2','E2','F2') as $cell) {
		$recapfamille->write_blank($cell, $bigtitreM); #merge
	}

	## Periode
	$recapfamille->write('A3', ucfirst(strftime("%B %Y", strtotime($datein)))." (".fdate($datein)." -> ".fdate($dateout).")", $normalcentre);
	foreach (array('B3','C3','D3','E3','F3') as $cell) {
		$recapfamille->write_blank($cell, $normalcentreM); #merge
	}

	## Entetes
	$recapfamille->write('A5', "Famille", $entete);
	$recapfamille->write('B5', "Serre cols", $entete);
	$recapfamille->write('C5', "Hard tags", $entete);
	$recapfamille->write('D5', "Etiquettes", $entete);
	$recapfamille->write('E5', "Total", $entete);
	$recapfamille->write('F5', "%", $entete);
	
#######################################################################
# Data

	$sql = "
SELECT 1,

SUM(eas.fo1a + eas.fo2a + eas.fo3a + eas.fo4a ) 																		as foA,
SUM(eas.pa1a + eas.pa2a + eas.pa3a + eas.pa4a + eas.pa5a + eas.pa6a + eas.pa7a + eas.pa8a + eas.pa9a + eas.pa10a ) 		as paA,
SUM(eas.te1a + eas.te2a + eas.te3a + eas.te4a + eas.te5a + eas.te6a + eas.te7a + eas.te8a ) 							as teA,
SUM(eas.ep1a + eas.ep2a + eas.ep3a + eas.ep4a + eas.ep5a ) 																as epA,
SUM(eas.ba1a + eas.ba2a + eas.ba3a + eas.ba4a + eas.ba5a + eas.ba6a ) 													as baA,
SUM(eas.au1a + eas.au2a + eas.au3a + eas.au4a + eas.au5a )																as auA,

SUM(eas.fo1b + eas.fo2b + eas.fo3b + eas.fo4b ) as foB,
SUM(eas.pa1b + eas.pa2b + eas.pa3b + eas.pa4b + eas.pa5b + eas.pa6b + eas.pa7b + eas.pa8b + eas.pa9b + eas.pa10b ) 		as paB,
SUM(eas.te1b + eas.te2b + eas.te3b + eas.te4b + eas.te5b + eas.te6b + eas.te7b + eas.te8b ) 							as teB,
SUM(eas.ep1b + eas.ep2b + eas.ep3b + eas.ep4b + eas.ep5b ) 																as epB,
SUM(eas.ba1b + eas.ba2b + eas.ba3b + eas.ba4b + eas.ba5b + eas.ba6b ) 													as baB,
SUM(eas.au1b + eas.au2b + eas.au3b + eas.au4b + eas.au5b ) 																as auB,

SUM(eas.fo1c + eas.fo2c + eas.fo3c + eas.fo4c ) 																		as foC,
SUM(eas.pa1c + eas.pa2c + eas.pa3c + eas.pa4c + eas.pa5c + eas.pa6c + eas.pa7c + eas.pa8c + eas.pa9c + eas.pa10c ) 		as paC,
SUM(eas.te1c + eas.te2c + eas.te3c + eas.te4c + eas.te5c + eas.te6c + eas.te7c + eas.te8c ) 							as teC,
SUM(eas.ep1c + eas.ep2c + eas.ep3c + eas.ep4c + eas.ep5c ) 																as epC,
SUM(eas.ba1c + eas.ba2c + eas.ba3c + eas.ba4c + eas.ba5c + eas.ba6c ) 													as baC,
SUM(eas.au1c + eas.au2c + eas.au3c + eas.au4c + eas.au5c ) 																as auC

FROM `mercheasproduit` eas
LEFT JOIN merch m ON eas.idmerch = m.idmerch 
WHERE m.idclient IN (".$_POST['client'].") AND m.datem BETWEEN '$datein' AND  '$dateout'  AND eas.idmerch > 1
GROUP BY 1
";

	$famil = new db();
	$famil->inline($sql);

	$row = mysql_fetch_array($famil->result);

	$recapfamille->write('A6', 'Food', $datas);
	$recapfamille->write_number('B6', $row['foA'], $datas);
	$recapfamille->write_number('C6', $row['foB'], $datas);
	$recapfamille->write_number('D6', $row['foC'], $datas);
	$recapfamille->write_formula('E6', '=SUM(B6:D6)', $datasB);
	$recapfamille->write_formula('F6', '=E6/E13', $datasPRC);

	$recapfamille->write('A7', 'Parfumerie', $datas);
	$recapfamille->write_number('B7', $row['paA'], $datas);
	$recapfamille->write_number('C7', $row['paB'], $datas);
	$recapfamille->write_number('D7', $row['paC'], $datas);
	$recapfamille->write_formula('E7', '=SUM(B7:D7)', $datasB);
	$recapfamille->write_formula('F7', '=E7/E13', $datasPRC);

	$recapfamille->write('A8', 'Textile', $datas);
	$recapfamille->write_number('B8', $row['teA'], $datas);
	$recapfamille->write_number('C8', $row['teB'], $datas);
	$recapfamille->write_number('D8', $row['teC'], $datas);
	$recapfamille->write_formula('E8', '=SUM(B8:D8)', $datasB);
	$recapfamille->write_formula('F8', '=E8/E13', $datasPRC);

	$recapfamille->write('A9', 'EPCS', $datas);
	$recapfamille->write_number('B9', $row['epA'], $datas);
	$recapfamille->write_number('C9', $row['epB'], $datas);
	$recapfamille->write_number('D9', $row['epC'], $datas);
	$recapfamille->write_formula('E9', '=SUM(B9:D9)', $datasB);
	$recapfamille->write_formula('F9', '=E9/E13', $datasPRC);

	$recapfamille->write('A10', 'Bazar', $datas);
	$recapfamille->write_number('B10', $row['baA'], $datas);
	$recapfamille->write_number('C10', $row['baB'], $datas);
	$recapfamille->write_number('D10', $row['baC'], $datas);
	$recapfamille->write_formula('E10', '=SUM(B10:D10)', $datasB);
	$recapfamille->write_formula('F10', '=E10/E13', $datasPRC);

	$recapfamille->write('A11', 'Autre', $datas);
	$recapfamille->write_number('B11', $row['auA'], $datas);
	$recapfamille->write_number('C11', $row['auB'], $datas);
	$recapfamille->write_number('D11', $row['auC'], $datas);
	$recapfamille->write_formula('E11', '=SUM(B11:D11)', $datasB);
	$recapfamille->write_formula('F11', '=E11/E13', $datasPRC);

#######################################################################
# Totaux

	$recapfamille->write('A13', 'Totaux', $datasT);
	$recapfamille->write_formula('B13', '=SUM(B6:B11)', $datasT);
	$recapfamille->write_formula('C13', '=SUM(C6:C11)', $datasT);
	$recapfamille->write_formula('D13', '=SUM(D6:D11)', $datasT);
	$recapfamille->write_formula('E13', '=SUM(E6:E11)', $datasT);
	$recapfamille->write_formula('F13', '=SUM(F6:F11)', $datasTPRC);

#####################################################################################################################################################################################
#####################################################################################################################################################################################
## checkcaisse

#######################################################################
# Tailles
	# Set the column width for columns 2 and 3
	$checkcaisse->set_column('A:A', 21);
	$checkcaisse->set_column('B:F', 15);
	
#######################################################################
# Menu

	## Recap mensuel
	$checkcaisse->write('A2', utf8_decode("Fonctionnement du système"), $bigtitre);
	foreach (array('B2','C2','D2','E2','F2') as $cell) {
		$checkcaisse->write_blank($cell, $bigtitreM); #merge
	}

	## Periode
	$checkcaisse->write('A3', ucfirst(strftime("%B %Y", strtotime($datein)))." (".fdate($datein)." -> ".fdate($dateout).")", $normalcentre);
	foreach (array('B3','C3','D3','E3','F3') as $cell) {
		$checkcaisse->write_blank($cell, $normalcentreM); #merge
	}


#######################################################################
# Data
	
	
	$sql = "
SELECT m.idshop, s.codeshop, s.ville, m.datem, 
eas.badcaisses
FROM `mercheasproduit` eas
LEFT JOIN merch m ON eas.idmerch = m.idmerch 
LEFT JOIN shop s ON m.idshop = s.idshop 
WHERE m.idclient IN (".$_POST['client'].") AND m.datem BETWEEN '$datein' AND  '$dateout'  AND eas.idmerch > 1
ORDER BY s.codeshop
";

$lesdatas = new db();
$lesdatas->inline("SET NAMES latin1");
$lesdatas->inline($sql);
$shops = array();
$lesweeks = array();

while ($row = mysql_fetch_array($lesdatas->result)) {
    if (!isset($shops[$row['idshop']])) $shops[$row['idshop']] = array();

    $nomshops[$row['idshop']] = '('.$row['codeshop'].') '.$row['ville'];

    $sem = weekfromdate($row['datem']);
    if (date("w", strtotime($row['datem'])) == 0) $sem -= 1;
    
    if (!isset($shops[$row['idshop']][$sem])) $shops[$row['idshop']][$sem] = "";
    if (!empty($row['badcaisses'])) $shops[$row['idshop']][$sem] .= $row['badcaisses'];

    $lesweeks[] = $sem;
}

$lesweeks = array_unique($lesweeks);
sort($lesweeks, SORT_NUMERIC);

$i = 5;

foreach ($shops as $id => $data) {
	$checkcaisse->write($i, 0, $nomshops[$id], $datas);
    for ($j = 0; $j <= 4; $j++) {
        if (!empty($data[$lesweeks[$j]])) {
            $caisses = array_unique(explode("%%", $data[$lesweeks[$j]]));
            sort($caisses);
            $checkcaisse->write($i, 1 + $j, implode(" ", $caisses), $datas);
        }
    }
	$i++;
} 

	## Entetes
	$checkcaisse->write('A5', "POS", $entete);
	$checkcaisse->write('B5', "Sem ".$lesweeks[0], $entete);
	$checkcaisse->write('C5', "Sem ".$lesweeks[1], $entete);
	$checkcaisse->write('D5', "Sem ".$lesweeks[2], $entete);
	$checkcaisse->write('E5', "Sem ".$lesweeks[3], $entete);
	$checkcaisse->write('F5', "Sem ".$lesweeks[4], $entete);

#######################################################################
# Lien Fichier

$workbook->close();

?>
<br>
    <fieldset>
        <legend><?php echo $clients[$_POST['client']] ?></legend>
<img src="<?php echo STATIK ?>illus/excel.png" alt="print" width="16" height="16" border="0">
<A href="<?php echo "/easmensuel/".$fname ;?>" target="_blank"><?php echo $linktext;?></A>
    </fieldset>
    
<br>
<?php
	}
?>
</div>
</div>
<?php
}

# Pied de Page
include NIVO."includes/pied.php" ; 
?>