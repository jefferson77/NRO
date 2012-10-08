<?php
$path = "document/temp/people/c4/";
$file = 'c4-'.$_POST['idpeople'].'-'.date("Ymd").'.pdf';

#### Get infos people ###############################
$row = $DB->getRow("SELECT idpeople, pprenom, pnom, lbureau, nrnational FROM people WHERE idpeople = ".$_POST['idpeople']);

#Infos People
$infp['regnat'] = $row['nrnational'];
$infp['nom']    = $row['pnom'].' '.$row['pprenom'];

# Fixes
$infp['emplcategorie']   = '010';
$infp['numentreprise']   = '0430597846';
$infp['comparitaire']    = '21800';
$infp['numonss']         = '112651531';
$infp['maxhebdo']        = '3800';
$infp['nomemployeur']    = 'Françoise Lannoo';
$infp['codetravailleur'] = '495';
$infp['x']               = 'x';

# switch langue
$ln = $row['lbureau'];

switch ($ln) {
    case"NL":
        $pdfsource           = NIVO.'/print/people/c4/C4NL.pdf';
        $infp['employeur']   = 'Exception2 bvba';
        $infp['empladresse'] = 'Jachtlaan 195 - 1040 Etterbeek';
        $infp['motif']       = 'Contract bebaaplde duurt';
    break;
    case"":
        echo "erreur langue people";
        $ln                  = "FR";
    case"FR":
        $pdfsource           = NIVO.'/print/people/c4/C4FR.pdf';
        $infp['employeur']   = 'Exception2 scrl';
        $infp['empladresse'] = '195 Avenue de la Chasse - 1040 Etterbeek';
        $infp['motif']       = 'Contrat a durée déterminée';
    break;
}
#### Ouverture du PDF et import des pages template ###############################

// initiate FPDI
$pdf = new FPDI('P', 'mm', 'A4', true, 'UTF-8', false);

$pagecount = $pdf->setSourceFile($pdfsource);

$page1 = $pdf->ImportPage(1);
$page2 = $pdf->ImportPage(2);

$pdf->SetAutoPageBreak(0);
$pdf->SetFont('Courier','B',13);

$np = 0;
$dayplusun = "";

#### Get data prestas ###############################
foreach ($dtable as $key => $value) {
    $lestables = $DB->tableliste("/^salaires/", 'grps');

    $nomtables = 'salaires'.substr($key, 2, 2).substr($key, 4, 2);

    #># Heure de repas
    if (($value['heures'] >= Conf::read('Payement.maxheure')) and ($value['date'] < '2010-02-01')) $value['heures'] -= 1;

    $infs = $DB->getRow("SELECT `idsalaire`, `modh` FROM `grps`.`$nomtables` WHERE `idpeople` = ".$_POST['idpeople']." AND `date` = '".$value['date']."'");

    ### total heure de la journée
    $totheures = $value['heures'] + $infs['modh'];

    ### trimestre
    switch (date("m", strtotime($value['date']))) {
        case"01":
        case"02":
        case"03":
            $thistrimin  = date("Y", strtotime($value['date'])).'-01-01';
            $thistrimout = date("Y", strtotime($value['date'])).'-03-31';
        break;
        case"04":
        case"05":
        case"06":
            $thistrimin  = date("Y", strtotime($value['date'])).'-04-01';
            $thistrimout = date("Y", strtotime($value['date'])).'-06-30';
        break;
        case"07":
        case"08":
        case"09":
            $thistrimin  = date("Y", strtotime($value['date'])).'-07-01';
            $thistrimout = date("Y", strtotime($value['date'])).'-09-30';
        break;
        case"10":
        case"11":
        case"12":
            $thistrimin  = date("Y", strtotime($value['date'])).'-10-01';
            $thistrimout = date("Y", strtotime($value['date'])).'-12-31';
        break;
    }

    $lej = date("w", strtotime($value['date']));
    if ($lej == 0) $lej = 7; # dimanche a la fin

    $regime = ($totheures <= 8) ? 6 : 5 ;


    ### switcher
    if (
            ($value['date'] != $dayplusun) or   # Jours non consécutifs
            ($lasttrimin != $thistrimin) or     # Changement de Trimestre
            ($lasttrimout != $thistrimout) or   # Changement de Trimestre
            ($regime != $lastregime)            # Changement de régime
        )
    {
        # Nouveau C4
        $np++;
        $sem                         = 1;
        $c4[$np]['in']               = $value['date'];
        $c4[$np]['out']              = $value['date'];
        $c4[$np]['trimin']           = $thistrimin;
        $c4[$np]['trimout']          = $thistrimout;
        $c4[$np]['moyhebdo'][$sem][] = $totheures;
    }
    else
    {
        # Continue sur le même C4
        $c4[$np]['out'] = $value['date'];
        if ($lej <= $lastj) $sem++;
        $c4[$np]['moyhebdo'][$sem][] = $totheures;
    }

    $c4[$np]['GrilleT'][$sem][$lej] = $totheures;

    $part3 = 'OK';  ### Need plus d'infos pour la Partie 3

    ## jumpers
    $lastj       = $lej;
    $lasttrimin  = $thistrimin;
    $lasttrimout = $thistrimout;
    $lastregime  = $regime;
    $dayplusun   = date("Y-m-d", strtotime($value['date']." +1 day"));
}

#################################################################################################################################
#### Generation du PDF ########################################################################################################
#############################################################################################################################
foreach ($c4 as $dat) {

    # Formatage Moyenne

    ## Pour l'instant je ne tiens compte que des jours de la première semaine, ca je ne sais pa comment tenir compte de plusieurs semaines consécutives.
    if (count($dat['moyhebdo']) > 1) {
        error_log("More than 1 week for C4 of " . $infp['nom'] . "(" . $infp['regnat'] . ") for period : " . $dat['in'] . " - ". $dat['out']);
    }
    $datas_semaine = array_shift($dat['moyhebdo']);

    $mth = explode(".", round(array_sum($datas_semaine) / count($datas_semaine) * $regime, 2));

    $dat['moyhebdo'] =  prezero(@$mth[0], 2).postzero(@$mth[1], 2);
    if ($dat['moyhebdo'] > $infp['maxhebdo']) $dat['moyhebdo'] = $infp['maxhebdo']; # Maximum du Q a 38 heures

    $dat['in']  = fdate($dat['in']);
    $dat['out'] = fdate($dat['out']);
    #################################################################################################################################
    #### Page 1 #######################################################################
    $pdf->addPage();
    $pdf->useTemplate($page1,0,0);

    ## Registre National
    $FR = array(33.8, 48); $NL = array(33.6, 48.25); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell(4.6, 5, $infp['regnat']{0});
        $pdf->Cell(4.6, 5, $infp['regnat']{1});
        $pdf->Cell(4.6, 5, $infp['regnat']{2});
        $pdf->Cell(4.6, 5, $infp['regnat']{3});
        $pdf->Cell(4.6, 5, $infp['regnat']{4});
        $pdf->Cell(4.6, 5, $infp['regnat']{5});
        $pdf->Cell(4.6, 5, $infp['regnat']{6});
        $pdf->Cell(4.6, 5, $infp['regnat']{7});
        $pdf->Cell(4.6, 5, $infp['regnat']{8});
    $FR = array(77, 48); $NL = array(76.75, 48.25); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell(4.6, 5, $infp['regnat']{9});
        $pdf->Cell(4.6, 5, $infp['regnat']{10});

    ## Nom du people
    $FR = array(95, 48); $NL = array(95, 48.25); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(110, 5, $infp['nom']);

    ## Employeur
    $FR = array(34, 58.75); $NL = array(34, 59); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(80, 5, $infp['employeur']);

    $FR = array(120.75, 58); $NL = array(120.75, 58.25); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell(4.6, 5, $infp['emplcategorie']{0});
        $pdf->Cell(4.6, 5, $infp['emplcategorie']{1});
        $pdf->Cell(4.6, 5, $infp['emplcategorie']{2});

    ## Numéro d'entreprise
    $FR = array(147.05, 58.25); $NL = array(147.05, 58.5); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell(4.6, 5, $infp['numentreprise']{0});
        $pdf->Cell(4.6, 5, $infp['numentreprise']{1});
        $pdf->Cell(4.6, 5, $infp['numentreprise']{2});
        $pdf->Cell(4.6, 5, $infp['numentreprise']{3});
    $FR = array(167.55, 58.25); $NL = array(167.55, 58.5); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell(4.6, 5, $infp['numentreprise']{4});
        $pdf->Cell(4.6, 5, $infp['numentreprise']{5});
        $pdf->Cell(4.6, 5, $infp['numentreprise']{6});
    $FR = array(183.55, 58.25); $NL = array(183.55, 58.5); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell(4.6, 5, $infp['numentreprise']{7});
        $pdf->Cell(4.6, 5, $infp['numentreprise']{8});
        $pdf->Cell(4.6, 5, $infp['numentreprise']{9});

    ## Commission Paritaire
    $FR = array(118.75, 66.75); $NL = array(118.75, 67); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell(4.6, 5, $infp['comparitaire']{0});
        $pdf->Cell(4.6, 5, $infp['comparitaire']{1});
        $pdf->Cell(4.6, 5, $infp['comparitaire']{2});
    $FR = array(134.75, 66.75); $NL = array(134.75, 67); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell(4.6, 5, $infp['comparitaire']{3});
        $pdf->Cell(4.6, 5, $infp['comparitaire']{4});

    ## Numéro ONSS
    $FR = array(153.25, 66.75); $NL = array(153.25, 67); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell(4.6, 5, $infp['numonss']{0});
        $pdf->Cell(4.6, 5, $infp['numonss']{1});
        $pdf->Cell(4.6, 5, $infp['numonss']{2});
        $pdf->Cell(4.6, 5, $infp['numonss']{3});
        $pdf->Cell(4.6, 5, $infp['numonss']{4});
        $pdf->Cell(4.6, 5, $infp['numonss']{5});
        $pdf->Cell(4.6, 5, $infp['numonss']{6});
    $FR = array(187.25, 66.75); $NL = array(187.25, 67); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell(4.6, 5, $infp['numonss']{7});
        $pdf->Cell(4.6, 5, $infp['numonss']{8});

    ## Adresse Employeur
    $FR = array(12, 75.5); $NL = array(12, 75.5); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(45, 5, $infp['empladresse']);

## Partie A ###

    ## Date IN
    $sep['NL'] = 3.6;
    $sep['FR'] = 4.4;
    $FR = array(48.75, 91.5); $NL = array(46.5, 92); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['in']{0});
        $pdf->Cell($sep[$ln], 5, $dat['in']{1});
    $FR = array(59.5, 91.5); $NL = array(55.25, 92); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['in']{3});
        $pdf->Cell($sep[$ln], 5, $dat['in']{4});
    $FR = array(70, 91.5); $NL = array(64.25, 92); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['in']{6});
        $pdf->Cell($sep[$ln], 5, $dat['in']{7});
        $pdf->Cell($sep[$ln], 5, $dat['in']{8});
        $pdf->Cell($sep[$ln], 5, $dat['in']{9});

    ## Date OUT
    $FR = array(47, 97.5); $NL = array(46.5, 98); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['out']{0});
        $pdf->Cell($sep[$ln], 5, $dat['out']{1});
    $FR = array(57.5, 97.5); $NL = array(55.25, 98); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['out']{3});
        $pdf->Cell($sep[$ln], 5, $dat['out']{4});
    $FR = array(68.25, 97.5); $NL = array(64.25, 98); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['out']{6});
        $pdf->Cell($sep[$ln], 5, $dat['out']{7});
        $pdf->Cell($sep[$ln], 5, $dat['out']{8});
        $pdf->Cell($sep[$ln], 5, $dat['out']{9});

    ## Date SERVICE
    $FR = array(124.5, 91.5); $NL = array(124.25, 92); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['in']{0});
        $pdf->Cell($sep[$ln], 5, $dat['in']{1});
    $FR = array(135, 91.5); $NL = array(133, 92); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['in']{3});
        $pdf->Cell($sep[$ln], 5, $dat['in']{4});
    $FR = array(145.5, 91.5); $NL = array(141.75, 92); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['in']{6});
        $pdf->Cell($sep[$ln], 5, $dat['in']{7});
        $pdf->Cell($sep[$ln], 5, $dat['in']{8});
        $pdf->Cell($sep[$ln], 5, $dat['in']{9});

    ## Code Travailleur
    $FR = array(112.25, 97.25); $NL = array(124.25, 98); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell(4.6, 5, $infp['codetravailleur']{0});
        $pdf->Cell(4.6, 5, $infp['codetravailleur']{1});
        $pdf->Cell(4.6, 5, $infp['codetravailleur']{2});

    ## ont été prélevés
    $FR = array(58.3, 114.5); $NL = array(62.75, 112.5); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(5, 5, $infp['x']);

    ## duree hebdomadaire Q
    $FR = array(19.5, 133); $NL = array(19.5, 131); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell(6.25, 5, $dat['moyhebdo']{0});
        $pdf->Cell(6.25, 5, $dat['moyhebdo']{1});
    $FR = array(34, 133); $NL = array(33.75, 131); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell(6.25, 5, $dat['moyhebdo']{2});
        $pdf->Cell(6.25, 5, $dat['moyhebdo']{3});

    ## max hebdomadaire S
    $FR = array(19.5, 142.5); $NL = array(19.5, 140); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell(6.25, 5, $infp['maxhebdo']{0});
        $pdf->Cell(6.25, 5, $infp['maxhebdo']{1});
    $FR = array(34, 142.5); $NL = array(34, 140); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell(6.25, 5, $infp['maxhebdo']{2});
        $pdf->Cell(6.25, 5, $infp['maxhebdo']{3});

    ## salaire moyen brut
    $s = explode(".", salaire($row['idpeople'], fdatebk($dat['in'])));

    if ($s[0] < 10) {
        $FR = array(58.5, 160.25); $NL = array(75, 158.75); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
            $pdf->Cell(4.6, 5, $s[0]{0});
    } else {
        $FR = array(63.1, 160.25); $NL = array(70.25, 158.75); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
            $pdf->Cell(4.6, 5, $s[0]{0});
            $pdf->Cell(4.6, 5, $s[0]{1});
    }

    $FR = array(76.5, 160.25); $NL = array(84.25, 158.75); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell(4.6, 5, $s[1]{0});
        $pdf->Cell(4.6, 5, $s[1]{1});
        $pdf->Cell(4.6, 5, $s[1]{2});
        $pdf->Cell(4.6, 5, $s[1]{3});

    $FR = array(54, 166.5); $NL = array(61.5, 165); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(5, 5, $infp['x']); # uur

    ## Grille T
    $pdf->SetFont('Courier','B',10);
    $startX = 78;
    $startY = ($ln == 'FR')?132.5:130.5;

    $tX = 13;
    $tY = 5.5;

    foreach ($dat['GrilleT'] as $sem => $jours) {
        foreach ($jours as $j => $time) {
            list($hrs, $mins) = explode(".", $time);
            $pdf->SetXY($startX + ($tX * ($j - 1)), $startY + ($tY * ($sem - 1)));
            $pdf->Cell($tX, $tY, $hrs.'h'.ceil($mins / 100 * 60).str_repeat('0', 2 - strlen(ceil($mins / 100 * 60))), '', 0, "C");
        }
    }

## Partie B ###

    $pdf->SetFont('Courier','B',12);

    ## Date IN Trimestre
    $FR = array(16, 218); $NL = array(17, 218.25); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['trimin']{8});
        $pdf->Cell($sep[$ln], 5, $dat['trimin']{9});
    $FR = array(26.75, 218); $NL = array(25.75, 218.25); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['trimin']{5});
        $pdf->Cell($sep[$ln], 5, $dat['trimin']{6});
    $FR = array(37.5, 218); $NL = array(34.5, 218.25); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['trimin']{0});
        $pdf->Cell($sep[$ln], 5, $dat['trimin']{1});
        $pdf->Cell($sep[$ln], 5, $dat['trimin']{2});
        $pdf->Cell($sep[$ln], 5, $dat['trimin']{3});

    ## Date OUT Trimestre
    $FR = array(59.5, 218); $NL = array(53, 218.25); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['trimout']{8});
        $pdf->Cell($sep[$ln], 5, $dat['trimout']{9});
    $FR = array(70, 218); $NL = array(61.75, 218.25); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['trimout']{5});
        $pdf->Cell($sep[$ln], 5, $dat['trimout']{6});
    $FR = array(80.5, 218); $NL = array(70.25, 218.25); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['trimout']{0});
        $pdf->Cell($sep[$ln], 5, $dat['trimout']{1});
        $pdf->Cell($sep[$ln], 5, $dat['trimout']{2});
        $pdf->Cell($sep[$ln], 5, $dat['trimout']{3});

    $FR = array(141.75, 217.75); $NL = array(134, 218.75); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(5, 5, $infp['x']); # non interruption


    #################################################################################################################################
    #### Page 2 #######################################################################
    $pdf->addPage();
    # Signature illus
    $pdf->Image($_SERVER["DOCUMENT_ROOT"]."/print/illus/signature/20.jpg",70,170,50);

    # Cachet illus
    $pdf->Image($_SERVER["DOCUMENT_ROOT"]."/print/illus/cachet.jpg",140,170,60);

    $pdf->useTemplate($page2,0,0);

## Partie C ###

    ## aucune indemnité
    $FR = array(10.25, 68.5); $NL = array(10, 68.5); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(5, 5, $infp['x']);

    ## Date fin
    $FR = array(96, 68.75); $NL = array(109, 68.5); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['out']{0});
        $pdf->Cell($sep[$ln], 5, $dat['out']{1});
    $FR = array(106.5, 68.75); $NL = array(117.5, 68.5); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['out']{3});
        $pdf->Cell($sep[$ln], 5, $dat['out']{4});
    $FR = array(117, 68.75); $NL = array(127, 68.5); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]);
        $pdf->Cell($sep[$ln], 5, $dat['out']{6});
        $pdf->Cell($sep[$ln], 5, $dat['out']{7});
        $pdf->Cell($sep[$ln], 5, $dat['out']{8});
        $pdf->Cell($sep[$ln], 5, $dat['out']{9});

    $FR = array(45, 82.5); $NL = array(65, 82.5); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(5, 5, $infp['motif']);

    $FR = array(18, 185); $NL = array(18, 185); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(5, 5, date("d/m/Y"));
    $FR = array(70, 180); $NL = array(70, 180); $varL = $$ln; $pdf->SetXY($varL[0], $varL[1]); $pdf->Cell(5, 5, $infp['nomemployeur']);
}

$pdf->Output(Conf::read('Env.root').$path.$file, 'F');

if($web) { ?>
    <div align="center">
        <br><br>
        <img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0">
        <a href="<?php echo NIVO.$path.$file ?>" target="_blank">Imprimer le(s) C4</a>
    </div>
<?php } else {
    $global[$_POST['idpeople']][] = Conf::read('Env.root').$path.$file;

    echo '<div id="centerzonelarge" align="center">';
    generateSendTable($global, 'people', 'temp/PeC4', 'PeC4', "C4");
    echo '</div>';
}
?>