#!/usr/bin/php
<?php
define("NIVO", "/NRO/core/");

include (NIVO."nro/fm.php");
include (NIVO."classes/hh.php");
include (NIVO."classes/test.php");
include (NIVO.'nro/xlib/phpmailer/class.phpmailer.php');

setlocale(LC_TIME, 'fr_FR');
date_default_timezone_set('Europe/Brussels');

if (isset($_GET['datein']) and isset($_GET['dateout'])) {
    $datein  = fdatebk($_GET['datein']);
    $dateout = fdatebk($_GET['dateout']);
} else {
    $datein  = date("Y-m-d", strtotime("-30 days"));
    $dateout = date("Y-m-d", strtotime("+40 days"));
}

#######################################################################################################################################
# Init Vars
    $cCOR = array();
    $cGRS = array();
    $cDIM = array();
    $ctab = array();

#######################################################################################################################################
# Liste des PEOPLE qui travaillent sur la période demandée
    ## Vip
    $sql = "SELECT v.idpeople, j.idagent FROM vipmission v LEFT JOIN vipjob j ON v.idvipjob = j.idvipjob WHERE v.vipdate BETWEEN '".$datein."' AND '".$dateout."' AND v.idpeople > 0"; $rechvip = new db(); $rechvip->inline($sql);
    while ($infos = mysql_fetch_array($rechvip->result)) {
        $lespeoples[] = $infos['idpeople'];
        $peopagent[$infos['idagent']][] = $infos['idpeople'];
    }

    ## Anim
    $sql = "SELECT a.idpeople, j.idagent FROM animation a LEFT JOIN animjob j ON a.idanimjob = j.idanimjob WHERE a.datem BETWEEN '".$datein."' AND '".$dateout."' AND a.idpeople > 0"; $rechvip = new db(); $rechvip->inline($sql);
    while ($infos = mysql_fetch_array($rechvip->result)) {
        $lespeoples[] = $infos['idpeople'];
        $peopagent[$infos['idagent']][] = $infos['idpeople'];
    }

    ## Merch
    $sql = "SELECT idpeople, idagent FROM merch WHERE datem BETWEEN '".$datein."' AND '".$dateout."' AND idpeople > 0 GROUP BY idpeople "; $rechvip = new db(); $rechvip->inline($sql);
    while ($infos = mysql_fetch_array($rechvip->result)) {
        $lespeoples[] = $infos['idpeople'];
        $peopagent[$infos['idagent']][] = $infos['idpeople'];
    }

## Unique, sort liste people
    $lespeoples = array_unique ($lespeoples);
    natsort($lespeoples);

    ## Exceptions : ne pas en tenir compte
    $nobook = array(
        '1513', # Françoise Lannoo
        '8578', # CPM
        '9038', # Vedior
        '9459', # Vedior
        '11019',# Fanny Service
        '12073' # Vedior
    );

    $lespeoples = array_diff ($lespeoples, $nobook);

## Unique, sort liste people par agent
    foreach($peopagent as $agent => $liste) {
        $peopagent[$agent] = array_unique ($liste);
        natsort($peopagent[$agent]);
    }

    $peopagent['global'] = array();

#
#######

#######################################################################################################################################
# Infos People

$pinf = new db();
$pinf->inline("SELECT `idpeople` ,`err` ,`pprenom` ,`gsm` ,`pnom` ,`catsociale` ,`sexe` ,`adresse1` ,`ville1` ,`cp1`, `banque`, `iban`, `bic`, `modepay`, `nationalite`, `ncidentite`, `ndate`, `ncp` ,`pays1` ,`nrnational` ,`npays` ,`dateentree` ,`codepeople` ,`etatcivil` ,`num1` ,`bte1` ,`lbureau` ,`pacharge` ,`eacharge`, `idsupplier` FROM people WHERE idpeople IN(".implode(", ", $lespeoples).")");

while ($row = mysql_fetch_array($pinf->result)) {
    $pif[$row['idpeople']] = array('code' => $row['codepeople'], 'nom' => $row['pnom'].' '.$row['pprenom']);

    if (!in_array($row['catsociale'], array('3'))) {
        $Verif = new test($row['idpeople']);

        # Teste tous les champ !! ordre a de l'importance
        $Verif->checkPRE($row['pprenom']);
        $Verif->checkNOM($row['pnom']);
        $Verif->checkCCC($row['catsociale']);
        $Verif->checkSEX($row['sexe']);

        ## ne teste pas les sous-traités
        if (empty($row['idsupplier'])) {
            $Verif->checkRUE($row['adresse1']);
            $Verif->checkLOC($row['ville1']);
            $Verif->checkCPO($row['cp1']);
            switch ($row['modepay']) {
                case "1":
                    $Verif->checkNCF($row['banque']);
                break;

                case "3":
                    $Verif->checkZ75($row['iban']);
                    $Verif->checkBIC($row['bic']);
                break;
            }
            $Verif->checkNAT($row['nationalite']);
            $Verif->checkNUI($row['ncidentite']);
            $Verif->checkDTN($row['ndate']);
            $Verif->checkNCP($row['ncp'], $row['npays']);
            $Verif->checkCPP($row['pays1']);
            $Verif->checkRNA($row['nrnational'], $row['ndate'], $row['sexe'], $row['npays']);
            $Verif->checkZ05($row['dateentree']);
            $Verif->checkRPE($row['codepeople']);
            $Verif->checkETC($row['etatcivil']);
            $Verif->checkNUR($row['num1']);
            $Verif->checkNUB($row['bte1']);
            $Verif->checkLGE($row['lbureau']);
            $Verif->checkAPC($row['pacharge']);
            $Verif->checkNEC($row['eacharge']);
        }

    ### Stockage dans la table d'erreurs
        $errP = 0;

        foreach($peopagent as $agent => $liste) {
            if ((in_array ($Verif->idpeople, $liste)) or ($agent == 'global')) {

                if ((count($Verif->ErrGS) >= 1) or (count($Verif->ErrDI) >= 1) or (count($Verif->Corrections) >= 1)) {
                    $latable[$agent][$Verif->idpeople]['nom'] = $row['pnom'];
                    $latable[$agent][$Verif->idpeople]['pre'] = $row['pprenom'];
                    $latable[$agent][$Verif->idpeople]['reg'] = $row['codepeople'];
                    $latable[$agent][$Verif->idpeople]['gsm'] = $row['gsm'];
                    $latable[$agent][$Verif->idpeople]['lg']  = $row['lbureau'];
                    $latable[$agent][$Verif->idpeople]['EGS'] = array();
                    $latable[$agent][$Verif->idpeople]['EDI'] = array();
                    $latable[$agent][$Verif->idpeople]['MOD'] = array();
                }

                if (count($Verif->ErrGS) >= 1) {
                    $latable[$agent][$Verif->idpeople]['EGS'] = $Verif->ErrGS;
                    if (!isset($cGRS[$agent])) $cGRS[$agent] = 0;
                    $cGRS[$agent] += count($Verif->ErrGS);
                    $errP += count($Verif->ErrGS);
                }

                if (count($Verif->ErrDI) >= 1) {
                    $latable[$agent][$Verif->idpeople]['EDI'] = $Verif->ErrDI;
                    if (!isset($cDIM[$agent])) $cDIM[$agent] = 0;
                    $cDIM[$agent] += count($Verif->ErrDI);
                    $errP += count($Verif->ErrDI);
                }

                if (count($Verif->Corrections) >= 1) {
                    $latable[$agent][$Verif->idpeople]['MOD'] = $Verif->Corrections;
                    if (!isset($cCOR[$agent])) $cCOR[$agent] = 0;
                    $cCOR[$agent] += count($Verif->Corrections);
                }
            }
        }

        ### Anotes le people si il y a des erreurs

        $errpeople = new db();
        if (($errP > 0) and ($row['idpeople'] != 'Y')) {
            $errpeople->inline("UPDATE `people` SET `err` ='Y' WHERE `idpeople` ='".$row['idpeople']."' LIMIT 1;");
        } elseif ($row['idpeople'] != 'N') {
            $errpeople->inline("UPDATE `people` SET `err` ='N' WHERE `idpeople` ='".$row['idpeople']."' LIMIT 1;");
        }
    }
}

#
#######

#######################################################################################################################################
# Table des prenoms des agents

$sql = "SELECT idagent, prenom, email FROM agent"; $ainf = new db(); $ainf->inline($sql);

while ($row = mysql_fetch_array($ainf->result)) {
    $agents[$row['idagent']] = $row['prenom'];
    $emails[$row['idagent']] = $row['email'];
}

$agents['global'] = 'Global';
$emails['global'] = 'nico@exception2.be';
#
#######


#######################################################################################################################################
# Detection des Double Booking et construction du tableau

    foreach ($lespeoples as $peop) {
        $ht = new hh();
        $dbbs = $ht->hhtable ($peop, $datein, $dateout) ;

        foreach ($dbbs as $date => $dbb) {

            $hjour = array_sum(preg_split('//', $dbb, -1, PREG_SPLIT_NO_EMPTY)); # au lieu de PHP5 str_split

            if((preg_match('/[2-9]/', $dbb)) or (($hjour < 3) or ($hjour > 9))) {

                $minarray = array();
                $ttime = 0;

                foreach ($ht->prtab[$date] as $val) {
                    ## Matin
                    if (($val['h1'] != "00:00:00") or ($val['h2'] != "00:00:00")) {

                        $h1 = explode(":", $val['h1']);
                        $h2 = explode(":", $val['h2']);

                        if ($h1[0] > $h2[0]) {$h2[0] += 24;}

                        $stpoint = ($h1[0] * 60) + $h1[1];
                        $endpoint = ($h2[0] * 60) + $h2[1];

                        $ttime += $endpoint - $stpoint - ($val['hb'] * 60) - ($val['hs'] * 60);

                        for ($i = $stpoint; $i < $endpoint; $i++) {
                            if (isset($minarray["$i"])) {
                                $minarray["$i"] += 1;
                            } else {
                                $minarray["$i"] = 1;
                            }
                        }


                    }
                    ## Aprem
                    if ((($val['h3'] != "00:00:00") or ($val['h4'] != "00:00:00")) and ($val['secteur'] != 'VI')) {

                        $h3 = explode(":", $val['h3']);
                        $h4 = explode(":", $val['h4']);

                        if ($h3[0] > $h4[0]) {$h4[0] += 24;}

                        $stpoint = ($h3[0] * 60) + $h3[1];
                        $endpoint = ($h4[0] * 60) + $h4[1];

                        $ttime += $endpoint - $stpoint ;

                        for ($i = $stpoint; $i < $endpoint; $i++) {
                            if (isset($minarray["$i"])) {
                                $minarray["$i"] += 1;
                            } else {
                                $minarray["$i"] = 1;
                            }
                        }
                    }

                }

            ## DBB
                $recouv = array_count_values($minarray);

                foreach ($recouv as $key => $value) {
                    if (($key != 1) and ($value > 2)) {
                        if (empty($_GET['datein'])) $ctab[$val['agent']][$peop][$date] = $ht->prtab[$date];
                        $ctab['global'][$peop][$date] = $ht->prtab[$date];
                    }
                }

            ## MINMAX
                if (($ttime < 180) or ($ttime > 600)) {
                    if (empty($_GET['datein'])) $htab[$val['agent']][$peop][$date] = $ht->prtab[$date];
                    $htab['global'][$peop][$date] = $ht->prtab[$date];
                }

                unset($recouv);
            }
        }
    }


#
#######

#######################################################################################################################################
# Creation des PDF de résultats

# Variables Couleur pour le PDF
$gris       = array('0.1333', '0.0902', '0.0706', '0.0078');
$rouge      = array('0', '1', '1', '0');
$vert       = array('0.75', '0', '1', '0');
$bleu       = array('0.87', '0.77', '0', '0');
$jauneclair = array('0.02', '0', '0.21', '0');
$orange     = array('0.0275', '0.3333', '0.8784', '0.0039');
$bleufonce  = array('0.9048', '0.2902', '0.0549', '0.0078');


if ((count($ctab) > 0) or (count($htab) > 0) or (count($latable) > 0)) {

    foreach ($agents as $idagent => $nomagent) {

        $np = 0;

        if ((isset($ctab[$idagent])) or (isset($htab[$idagent])) or (isset($latable[$idagent]))) {

                # Variables Path PDF
                $pathpdf = 'document/temp/people/erreurs/';
                $nompdf = 'ERR-'.date("Ymd").'-'.strtoupper(remaccents($agents[$idagent])).'.pdf';

                $pdf = pdf_new();
                pdf_open_file($pdf, Conf::read('Env.root').$pathpdf.$nompdf); # definit l'emplacement de la sauvegarde
                # Infos pour le document
                pdf_set_info($pdf, "Author", "NEURO auto");
                pdf_set_info($pdf, "Title", "Rapport Double Booking");
                pdf_set_info($pdf, "Creator", "NEURO");
                pdf_set_info($pdf, "Subject", "Rapport Double Booking");

                ### Declaration des fontes
                $HelveticaBold = PDF_load_font($pdf, "Helvetica-Bold", "host", "");
                $Helvetica = PDF_load_font($pdf, "Helvetica", "host", "");

                ######## Variables de taille  ###############
                $LargeurPage = 595; # Largeur A4
                $HauteurPage = 842; # Hauteur A4
                $MargeLeft   = 30;
                $MargeRight  = 30;
                $MargeTop    = 30;
                $MargeBottom = 30;

                ######## Variables de taille  ###############
                $LargeurUtile = $LargeurPage - $MargeRight - $MargeLeft;
                $HauteurUtile = $HauteurPage - $MargeTop - $MargeBottom;

                ######################################################################################################################################################################################
                #######  Erreurs People
                ###################################

                if (isset($latable[$idagent])) {

                    $maxtab = 755;
                    $mintab = 25;
                    #####
                    $tab = 542;
                    $turn = 1;
                    $reste = count($latable[$idagent]);

                    foreach ($latable[$idagent] as $idp => $value) {
                        if (($tab == $maxtab) or ($turn == 1)) {
                        ######## Nouvelle Page ###############
                            $np++;
                            pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
                            PDF_create_bookmark($pdf, $np." People", "");
                            pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche

                        ######## Première Page ###############
                            if ($turn == 1) {
                            ## * Titre Page
                                pdf_setfont($pdf, $HelveticaBold, 22); pdf_set_value ($pdf, "leading", 26);
                                pdf_show_boxed($pdf, "Tableau des Erreurs People (Dimona / Groupe-S)" , 0 , 755 , 534, 26 , 'center', "");

                            ## * Cadres de couleur
                                pdf_setcolor($pdf, "both", "cmyk", $gris[0], $gris[1], $gris[2], $gris[3]);
                                pdf_rect($pdf, 0, 647, 534, 102);
                                pdf_rect($pdf, 180, 583, 172, 30);
                                pdf_fill_stroke($pdf);

                                pdf_setcolor($pdf, "both", "cmyk", $orange[0], $orange[1], $orange[2], $orange[3]);
                                pdf_rect($pdf, 0, 583, 172, 30);
                                pdf_fill_stroke($pdf);

                                pdf_setcolor($pdf, "both", "cmyk", $bleu[0], $bleu[1], $bleu[2], $bleu[3]);
                                pdf_rect($pdf, 0, 691, 534, 15);
                                pdf_fill_stroke($pdf);

                                pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);

                            ## * Lignes
                                pdf_moveto($pdf, 0, 707); pdf_lineto($pdf, $LargeurUtile, 707);
                                pdf_moveto($pdf, 0, 646); pdf_lineto($pdf, $LargeurUtile, 646);
                                pdf_moveto($pdf, 0, 614); pdf_lineto($pdf, $LargeurUtile, 614);
                                pdf_moveto($pdf, 0, 582); pdf_lineto($pdf, $LargeurUtile, 582);
                                pdf_moveto($pdf, 0, 542); pdf_lineto($pdf, $LargeurUtile, 542);
                                pdf_stroke($pdf);

                            ## * Texte Intro
                                pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, utf8_decode("Ce rapport regroupe les erreurs trouvées dans les informations des people et qu'il est impératif de corriger pour permettre le bon fonctionnement des envois de DIMONA et Groupe-S.\rCe rapport est à vérifier tous les jours. Ci-dessous la légende explicative des erreurs.") , 6 , 713 , 520, 36 , 'left', "");

                            ## * Titres sections
                                pdf_setfont($pdf, $HelveticaBold, 16); pdf_set_value ($pdf, "leading", 24);
                                pdf_show_boxed($pdf, utf8_decode("Récapitulatif des erreurs") , 0 , 616 , 534, 24 , 'left', "");
                                pdf_show_boxed($pdf, "Liste des erreurs" , 0 , 544 , 534, 24 , 'left', "");

                            ## * Récapitulatifs
                                pdf_setfont($pdf, $Helvetica, 20); pdf_set_value ($pdf, "leading", 28);
                                pdf_show_boxed($pdf, "Dimona" , 0 , 592 , 114, 28 , 'center', "");
                                pdf_show_boxed($pdf, "Groupe-S" , 180 , 592 , 114, 28 , 'center', "");
                                pdf_show_boxed($pdf, utf8_decode("Corrigées") , 361 , 592 , 114, 28 , 'center', "");

                                pdf_setfont($pdf, $HelveticaBold, 20); pdf_set_value ($pdf, "leading", 28);
                                pdf_show_boxed($pdf, (isset($cDIM[$idagent]))?$cDIM[$idagent]:'' , 112 , 592 , 60, 28 , 'center', "");
                                pdf_show_boxed($pdf, (isset($cGRS[$idagent]))?$cGRS[$idagent]:'' , 292 , 592 , 60, 28 , 'center', "");
                                pdf_show_boxed($pdf, (isset($cCOR[$idagent]))?$cCOR[$idagent]:'' , 473 , 592 , 60, 28 , 'center', "");

                            ## * Légende

                                ## Ligne Promoboy
                                $tab = 691;
                                pdf_setcolor($pdf, "both", "cmyk", $bleu[0], $bleu[1], $bleu[2], $bleu[3]);
                                pdf_rect($pdf, 0, $tab, 534, 15);
                                pdf_fill_stroke($pdf);

                                pdf_setcolor($pdf, "both", "gray", 1, 0, 0, 0);

                                pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 15);
                                pdf_show_boxed($pdf, "Nr Registre" , 6 , $tab + 3 , 76, 15 , 'right', "");
                                pdf_show_boxed($pdf, "Nom du promoboy" , 102 , $tab + 3 , 279, 15 , 'left', "");

                                pdf_show_boxed($pdf, "GSM du promoboy" , 295 , $tab + 3 , 143, 15 , 'left', "");

                                pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 13);
                                pdf_show_boxed($pdf, "Id" , 478 , $tab + 3 , 55, 15 , 'right', "");

                                pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);

                                ## Ligne Groupe-S
                                $tab = $tab - 14;

                                pdf_setcolor($pdf, "stroke", "gray", 0, 0, 0, 0);
                                pdf_setcolor($pdf, "fill", "gray", 1, 0, 0, 0);
                                pdf_rect($pdf, 7, $tab + 1, 10, 10);
                                pdf_fill_stroke($pdf);

                                pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);
                                pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, "Nom du Champ" , 24 , $tab + 2 , 107, 12 , 'left', "");

                                pdf_setcolor($pdf, "both", "cmyk", $rouge[0], $rouge[1], $rouge[2], $rouge[3]);
                                pdf_show_boxed($pdf, "Valeur" , 135 , $tab + 2 , 119, 12 , 'left', "");
                                pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);

                                pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, "Erreur bloquante pour le Groupe-S, pour la fin du mois" , 240 , $tab + 2 , 293, 12 , 'left', "");

                                ## Ligne DIMONA
                                $tab = $tab - 14;
                                pdf_setcolor($pdf, "both", "cmyk", $orange[0], $orange[1], $orange[2], $orange[3]);
                                pdf_rect($pdf, 6, $tab, 528, 12);
                                pdf_fill_stroke($pdf);

                                pdf_setcolor($pdf, "stroke", "gray", 0, 0, 0, 0);
                                pdf_setcolor($pdf, "fill", "gray", 1, 0, 0 ,0);
                                pdf_rect($pdf, 7, $tab + 1, 10, 10);
                                pdf_fill_stroke($pdf);

                                pdf_setcolor($pdf, "both", "gray", 1, 0, 0 ,0);
                                pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, "Nom du Champ" , 24 , $tab + 2 , 107, 12 , 'left', "");
                                pdf_show_boxed($pdf, "Valeur" , 135 , $tab + 2 , 119, 12 , 'left', "");

                                pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, utf8_decode("Erreur Bloquante pour la DIMONA, à corriger IMMEDIATEMENT") , 240 , $tab + 2 , 293, 12 , 'left', "");

                                pdf_setcolor($pdf, "both", "gray", 0, 0, 0 ,0);

                                ## Ligne Corrigée
                                $tab = $tab - 14;

                                pdf_setcolor($pdf, "both", "gray", 0.3, 0, 0 ,0);
                                pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, "Nom du Champ" , 24 , $tab + 2 , 107, 12 , 'left', "");
                                pdf_show_boxed($pdf, "Valeur" , 135 , $tab + 2 , 119, 12 , 'left', "");

                                pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, utf8_decode("Erreur Corrigée par le système") , 240 , $tab + 2 , 293, 12 , 'left', "");

                                pdf_setcolor($pdf, "both", "gray", 0, 0, 0 ,0);

                                $tab = 542;
                            }
                        }

                        $more = (17 + ((count($value['EDI']) + count($value['EGS']) + count($value['MOD'])) * 14));
                        $mixtab = $tab - $more;

                        if ($mixtab <= $mintab) {


                        ######## Fin de Page   ###############

                        ## * Ligne de bas de page
                            pdf_moveto($pdf, 0, 12); pdf_lineto($pdf, $LargeurUtile, 12);
                            pdf_stroke($pdf);

                        ## * Imprimé le
                            pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                            pdf_show_boxed($pdf, utf8_decode("Imprimé le : ").date("d/m/Y") , 0 , 0 , 276, 12 , 'left', "");

                        ## * NEURO
                            pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                            pdf_show_boxed($pdf, "NEURO" , 0 , 0 , 534, 12 , 'center', "");

                        ## * Page
                            pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                            pdf_show_boxed($pdf, "Page ".$np , 271 , 0 , 262, 12 , 'right', "");

                            pdf_end_page($pdf);
                            $tab = $maxtab;

                            $np++;
                            pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
                            PDF_create_bookmark($pdf, $np." People", "");
                            pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche

                        }

                    ### Répétition  #####
                        if ((count($value['EDI']) >= 1) or (count($value['EGS']) >= 1)) {
                            ## Ligne Promoboy
                            $tab = $tab - 17;
                            pdf_setcolor($pdf, "both", "cmyk", $bleu[0], $bleu[1], $bleu[2], $bleu[3]);
                            pdf_rect($pdf, 0, $tab, 534, 15);
                            pdf_fill_stroke($pdf);

                            pdf_setcolor($pdf, "both", "gray", 1, 0, 0 ,0);
                            pdf_setfont($pdf, $Helvetica, 12); pdf_set_value ($pdf, "leading", 15);
                            pdf_show_boxed($pdf, $value['reg'] , 6 , $tab + 3 , 36, 15 , 'right', "");
                            pdf_show_boxed($pdf, utf8_decode($value['pre'].' '.$value['nom']) , 55 , $tab + 3 , 279, 15 , 'left', "");
                            pdf_show_boxed($pdf, $value['gsm'] , 295 , $tab + 3 , 143, 15 , 'left', "");
                            pdf_show_boxed($pdf, $value['lg'] , 400 , $tab + 3 , 30, 15 , 'left', "");

                            pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 13);
                            pdf_show_boxed($pdf, $idp , 478 , $tab + 3 , 55, 15 , 'right', "");
                            pdf_setcolor($pdf, "both", "gray", 0, 0 ,0 ,0);
                        }

                        ## Lignes DIMONA
                        if (count($value['EDI']) >= 1) {
                            foreach ($value['EDI'] as $dim) {

                                $tab = $tab - 14;
                                pdf_setcolor($pdf, "both", "cmyk", $orange[0], $orange[1], $orange[2], $orange[3]);
                                pdf_rect($pdf, 6, $tab, 528, 12);
                                pdf_fill_stroke($pdf);

                                pdf_setcolor($pdf, "stroke", "gray", 0, 0 ,0 ,0);
                                pdf_setcolor($pdf, "fill", "gray", 1, 0 ,0 ,0);
                                pdf_rect($pdf, 7, $tab + 1, 10, 10);
                                pdf_fill_stroke($pdf);

                                pdf_setcolor($pdf, "both", "gray", 1, 0, 0, 0);
                                pdf_setfont($pdf, $HelveticaBold, 9); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, utf8_decode($dim['Champ']) , 24 , $tab + 3 , 107, 12 , 'left', "");
                                pdf_show_boxed($pdf, "'".utf8_decode($dim['Valeur'])."'" , 135 , $tab + 3 , 119, 12 , 'left', "");


                                pdf_setfont($pdf, $Helvetica, 8); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, utf8_decode($dim['Infos']) , 240 , $tab + 3 , 293, 12 , 'left', "");

                                pdf_setcolor($pdf, "both", "gray", 0, 0, 0 ,0);

                            }
                        }

                        ## Lignes Groupe-S
                        if (count($value['EGS']) >= 1) {
                            foreach ($value['EGS'] as $dim) {
                                $tab = $tab - 14;

                                pdf_setcolor($pdf, "stroke", "gray", 0, 0 ,0 ,0);
                                pdf_setcolor($pdf, "fill", "gray", 1, 0 ,0 ,0);
                                pdf_rect($pdf, 7, $tab + 1, 10, 10);
                                pdf_fill_stroke($pdf);

                                pdf_setcolor($pdf, "both", "gray", 0, 0, 0 ,0);
                                pdf_setfont($pdf, $HelveticaBold, 9); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, utf8_decode($dim['Champ']) , 24 , $tab + 3 , 107, 12 , 'left', "");

                                pdf_setcolor($pdf, "both", "cmyk", $rouge[0], $rouge[1], $rouge[2], $rouge[3]);
                                pdf_show_boxed($pdf, "'".utf8_decode($dim['Valeur'])."'" , 135 , $tab + 3 , 119, 12 , 'left', "");
                                pdf_setcolor($pdf, "both", "gray", 0, 0 ,0 ,0);

                                pdf_setfont($pdf, $Helvetica, 8); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, utf8_decode($dim['Infos']) , 240 , $tab + 3 , 293, 12 , 'left', "");

                            }
                        }

                        if ($reste == 1) {

                        ######## Fin de Page   ###############

                        ## * Ligne de bas de page
                            pdf_moveto($pdf, 0, 12); pdf_lineto($pdf, $LargeurUtile, 12);
                            pdf_stroke($pdf);

                        ## * Imprimé le
                            pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                            pdf_show_boxed($pdf, utf8_decode("Imprimé le : ").date("d/m/Y") , 0 , 0 , 276, 12 , 'left', "");

                        ## * NEURO
                            pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                            pdf_show_boxed($pdf, "NEURO" , 0 , 0 , 534, 12 , 'center', "");

                        ## * Page
                            pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                            pdf_show_boxed($pdf, "Page ".$np , 271 , 0 , 262, 12 , 'right', "");

                            pdf_end_page($pdf);
                        }


                    ### Fin Répétition ##

                        $turn++;
                        $reste--;
                    }
                }

                ######################################################################################################################################################################################
                #######  Double Booking
                ###################################

                if (isset($ctab[$idagent])) {

                    $constr = $ctab[$idagent];

                    $maxtab = 755;
                    $mintab = 25;
                    #####

                    $last = count($constr);

                        $tab = 690;
                        $turn = 1;

                        ######## Nouvelle Page ###############
                            $np++;
                            pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
                            PDF_create_bookmark($pdf, $np." Double Booking", "");
                            pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche

                            ## * Lignes des heures

                            $start = 240;
                            $space = 12;
                            $nombre = 24;

                            pdf_setlinewidth($pdf, 0.5);

                            for ($i = $start; $i <= ($start + ($space * $nombre)); $i += $space) {
                                pdf_moveto($pdf, $i, 30); pdf_lineto($pdf,  $i, $tab);
                            }

                            pdf_stroke($pdf);

                            pdf_setlinewidth($pdf, 1);
                            ## * Titre Page
                                pdf_setfont($pdf, $HelveticaBold, 21); pdf_set_value ($pdf, 'leading', 21);
                                pdf_show_boxed($pdf, 'Double Booking '.$agents[$idagent] , 0, 757, 534, 23 , 'center', "");
                                pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, 'leading', 10);
                                pdf_show_boxed($pdf, 'du '.fdate($datein).' au '.fdate($dateout) , 0, 744, 534, 11 , 'center', "");

                            ## * Explications
                                pdf_setlinewidth($pdf, 0.5);
                                pdf_setcolor($pdf, "fill", "cmyk", $jauneclair[0], $jauneclair[1], $jauneclair[2], $jauneclair[3]);
                                pdf_rect($pdf, 0, 710, 534, 30);
                                pdf_fill_stroke($pdf);
                                pdf_setlinewidth($pdf, 1);

                                pdf_setcolor($pdf, "both", "gray", 0, 0 ,0 ,0);
                                pdf_setfont($pdf, $Helvetica, 11); pdf_set_value ($pdf, 'leading', 13);
                                pdf_show_boxed($pdf, "Veuillez modifier les missions ci-dessous pour que les periodes horaires ne se chevauchent pas.
Les chevauchements actuels sont surlignes en rouge." , 5, 713, 525, 28 , 'left', "");

                    foreach ($constr as $idp => $peop) {

                        foreach ($peop as $date => $miss) {

                            $fintab = $tab - 14 - (count($miss) * 12);

                            if ($fintab < 30) {
                            ## * Ligne de bas de page
                                pdf_moveto($pdf, 0, 12); pdf_lineto($pdf, $LargeurUtile, 12);
                                pdf_stroke($pdf);

                            ## * Imprimé le
                                pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, utf8_decode("Imprimé le : ").date("d/m/Y") , 0 , 0 , 276, 12 , 'left', "");

                            ## * NEURO
                                pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, "NEURO" , 0 , 0 , 534, 12 , 'center', "");

                                pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, "Page ".$np , 271 , 0 , 262, 12 , 'right', "");

                                pdf_end_page($pdf);
                                $tab = 750;

                                $np++;
                                pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
                                PDF_create_bookmark($pdf, $np." Double Booking", "");
                                pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche

                                pdf_setfont($pdf, $HelveticaBold, 14); pdf_set_value ($pdf, 'leading', 15);
                                pdf_show_boxed($pdf, 'Double Booking '.utf8_decode($agents[$idagent]) , 0, 767, 534, 16 , 'center', "");
                                ## * Lignes des heures

                                pdf_setlinewidth($pdf, 0.5);

                                for ($i = $start; $i <= ($start + ($space * $nombre)); $i += $space) {
                                    pdf_moveto($pdf, $i, 30); pdf_lineto($pdf,  $i, $tab);
                                }

                                pdf_stroke($pdf);

                                pdf_setlinewidth($pdf, 1);
                            }

                            #### Barre Grise People
                            pdf_setcolor($pdf, "fill", "cmyk", $gris[0], $gris[1], $gris[2], $gris[3]);
                            pdf_rect($pdf, 0, $tab - 2, 533, 12);
                            pdf_fill_stroke($pdf);

                            pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);
                            ## Contenu barre grise

                            pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, 'leading', 10);
                            pdf_show_boxed($pdf, strftime("%a %d %b", strtotime($date)) , 146, $tab, 75, 11 , 'right', "");

                            pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, 'leading', 10);
                            pdf_show_boxed($pdf, $pif[$idp]['code'] , 4, $tab, 25, 11 , 'right', "");
                            pdf_show_boxed($pdf, utf8_decode($pif[$idp]['nom']) , 34, $tab, 135, 11 , 'left', "");

                            ### heures
                            pdf_setfont($pdf, $Helvetica, 7); pdf_set_value ($pdf, 'leading', 10);
                            $heures = array('03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '01', '02', '03');
                            for ($i = $start; $i <= ($start + ($space * $nombre)); $i += $space) {
                                pdf_show_boxed($pdf, array_shift($heures) , $i, $tab, 12, 11 , 'center', "");
                            }

                            $tab -= 14;

                            $redtab = array();

                            foreach ($miss as $val) {
                                ## Debut de Ligne
                                pdf_setfont($pdf, $HelveticaBold, 8); pdf_set_value ($pdf, 'leading', 10);
                                pdf_show_boxed($pdf, $val['secteur'].' '.$val['idmission'] , 7, $tab, 55, 11 , 'left', "");

                                pdf_setfont($pdf, $Helvetica, 8); pdf_set_value ($pdf, 'leading', 10);
                                pdf_show_boxed($pdf, ftime($val['h1']) , 125, $tab+1, 25, 11 , 'left', "");
                                pdf_show_boxed($pdf, ftime($val['h2']) , 150, $tab+1, 25, 11 , 'left', "");
                                pdf_show_boxed($pdf, ftime($val['h3']) , 185, $tab+1, 25, 11 , 'left', "");
                                pdf_show_boxed($pdf, ftime($val['h4']) , 210, $tab+1, 25, 11 , 'left', "");

                                pdf_setfont($pdf, $Helvetica, 8); pdf_set_value ($pdf, 'leading', 10);
                                pdf_show_boxed($pdf, utf8_decode($agents[$val['agent']]) , 60, $tab+1, 60, 11 , 'left', "");

                                ## Matin
                                if (($val['h1'] != "00:00:00") or ($val['h2'] != "00:00:00")) {
                                    $h1 = explode(":", $val['h1']);
                                    $h2 = explode(":", $val['h2']);

                                    if ($h1[0] > $h2[0]) {$h2[0] += 24;}

                                    $stpoint = ($start - (3 * $space)) + ($h1[0] * $space) + ($h1[1] / 60 * $space);
                                    $endpoint = ($start - (3 * $space)) + ($h2[0] * $space) + ($h2[1] / 60 * $space);

                                    pdf_setcolor($pdf, "both", "cmyk", $vert[0], $vert[1], $vert[2], $vert[3]);
                                    pdf_rect($pdf, $stpoint, $tab + 2, $endpoint - $stpoint, 5);
                                    pdf_fill($pdf);

                                    pdf_setcolor($pdf, "both", "gray", 0, 0 ,0 ,0);

                                    $redtab[] = array('in' => $stpoint, 'out' => $endpoint, 'tab' => $tab);

                                }
                                ## Aprem
                                if ((($val['h3'] != "00:00:00") or ($val['h4'] != "00:00:00")) and ($val['secteur'] != 'VI')) {
                                    $h3 = explode(":", $val['h3']);
                                    $h4 = explode(":", $val['h4']);

                                    if ($h3[0] > $h4[0]) {$h4[0] += 24;}

                                    $stpoint = ($start - (3 * $space)) + ($h3[0] * $space) + ($h3[1] / 60 * $space);
                                    $endpoint = ($start - (3 * $space)) + ($h4[0] * $space) + ($h4[1] / 60 * $space);

                                    pdf_setcolor($pdf, "both", "cmyk", $vert[0], $vert[1], $vert[2], $vert[3]);
                                    pdf_rect($pdf, $stpoint, $tab + 2, $endpoint - $stpoint, 5);
                                    pdf_fill($pdf);

                                    pdf_setcolor($pdf, "both", "gray", 0, 0 ,0 ,0);

                                    $redtab[] = array('in' => $stpoint, 'out' => $endpoint, 'tab' => $tab);
                                }

                                $tab -= 12;
                            }

                            ## calcul redtab
                            $reds = array();

                            foreach($redtab as $vs) {
                                foreach($redtab as $ch) {
                                ## cas 1
                                    if (($vs['in'] <= $ch['out']) and ($vs['in'] >= $ch['in']) and ($vs['tab'] != $ch['tab'])) {
                                        if ($vs['out'] < $ch['out']) {
                                            $reds[] = $vs['in'].'X'.$vs['out'].'X'.$vs['tab'];
                                            $reds[] = $vs['in'].'X'.$vs['out'].'X'.$ch['tab'];
                                        } else {
                                            $reds[] = $vs['in'].'X'.$ch['out'].'X'.$vs['tab'];
                                            $reds[] = $vs['in'].'X'.$ch['out'].'X'.$ch['tab'];
                                        }
                                    }
                                ## cas 2
                                    if (($vs['out'] <= $ch['out']) and ($vs['out'] >= $ch['in']) and ($vs['tab'] != $ch['tab'])) {
                                        if ($vs['in'] < $ch['in']) {
                                            $reds[] = $ch['in'].'X'.$vs['out'].'X'.$vs['tab'];
                                            $reds[] = $ch['in'].'X'.$vs['out'].'X'.$ch['tab'];
                                        } else {
                                            $reds[] = $vs['in'].'X'.$vs['out'].'X'.$vs['tab'];
                                            $reds[] = $vs['in'].'X'.$vs['out'].'X'.$ch['tab'];
                                        }
                                    }
                                }
                            }

                            if (count($reds) > 0) {
                                $reds = array_unique($reds);

                                ## Affiche redtab
                                pdf_setcolor($pdf, "both", "cmyk", $rouge[0], $rouge[1], $rouge[2], $rouge[3]);
                                foreach ($reds as $bloc) {
                                    $r = explode("X", $bloc);
                                    pdf_rect($pdf, $r[0], $r[2] + 2, $r[1] - $r[0], 5);
                                    pdf_fill($pdf);
                                }
                                pdf_setcolor($pdf, "both", "gray", 0, 0 ,0 ,0);
                                unset($reds);
                            }
                            unset($redtab);
                        }

                        $turn++;
                    }
                            pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                            pdf_show_boxed($pdf, "Page ".$np , 271 , 0 , 262, 12 , 'right', "");

                        ## * Ligne de bas de page
                            pdf_moveto($pdf, 0, 12); pdf_lineto($pdf, $LargeurUtile, 12);
                            pdf_stroke($pdf);

                        ## * Imprimé le
                            pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                            pdf_show_boxed($pdf, utf8_decode("Imprimé le : ").date("d/m/Y") , 0 , 0 , 276, 12 , 'left', "");

                        ## * NEURO
                            pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                            pdf_show_boxed($pdf, "NEURO" , 0 , 0 , 534, 12 , 'center', "");

                            pdf_end_page($pdf);
                }

                ######################################################################################################################################################################################
                #######  -3 +9
                ###################################

                if (count($htab[$idagent]) > 0) {

                    $constr = $htab[$idagent];

                    $maxtab = 755;
                    $mintab = 25;
                    #####

                    $last = count($constr);

                        $tab = 690;
                        $turn = 1;

                        ######## Nouvelle Page ###############
                            $np++;
                            pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
                            PDF_create_bookmark($pdf, $np." Min Max", "");
                            pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche

                            ## * Lignes des heures

                            $start = 240;
                            $space = 12;
                            $nombre = 24;

                            pdf_setlinewidth($pdf, 0.5);

                            for ($i = $start; $i <= ($start + ($space * $nombre)); $i += $space) {
                                pdf_moveto($pdf, $i, 30); pdf_lineto($pdf,  $i, $tab);
                            }

                            pdf_stroke($pdf);

                            pdf_setlinewidth($pdf, 1);
                            ## * Titre Page
                                pdf_setfont($pdf, $HelveticaBold, 21); pdf_set_value ($pdf, 'leading', 21);
                                pdf_show_boxed($pdf, 'MIN MAX '.$agents[$idagent] , 0, 757, 534, 23 , 'center', "");
                                pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, 'leading', 10);
                                pdf_show_boxed($pdf, 'du '.fdate($datein).' au '.fdate($dateout) , 0, 744, 534, 11 , 'center', "");

                            ## * Explications
                                pdf_setlinewidth($pdf, 0.5);
                                pdf_setcolor($pdf, "fill", "cmyk", $jauneclair[0], $jauneclair[1], $jauneclair[2], $jauneclair[3]);
                                pdf_rect($pdf, 0, 710, 534, 30);
                                pdf_fill_stroke($pdf);
                                pdf_setlinewidth($pdf, 1);

                                pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);
                                pdf_setfont($pdf, $Helvetica, 11); pdf_set_value ($pdf, 'leading', 13);
                                pdf_show_boxed($pdf, "Les missions reprises ci-dessous comportent soit trop peu (- de 3) soit trop (+ de 10) d'heures de prestation.
Merci de corriger" , 5, 713, 525, 28 , 'left', "");

                    foreach ($constr as $idp => $peop) {

                        foreach ($peop as $date => $miss) {

                            $fintab = $tab - 14 - (count($miss) * 12);

                            if ($fintab < 30) {
                                pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, "Page ".$np , 271 , 0 , 262, 12 , 'right', "");

                            ## * Ligne de bas de page
                                pdf_moveto($pdf, 0, 12); pdf_lineto($pdf, $LargeurUtile, 12);
                                pdf_stroke($pdf);

                            ## * Imprimé le
                                pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, utf8_decode("Imprimé le : ").date("d/m/Y") , 0 , 0 , 276, 12 , 'left', "");

                            ## * NEURO
                                pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                                pdf_show_boxed($pdf, "NEURO" , 0 , 0 , 534, 12 , 'center', "");
                                pdf_end_page($pdf);
                                $tab = 750;

                                $np++;
                                pdf_begin_page($pdf, $LargeurPage, $HauteurPage); # Nouvelle Page
                                PDF_create_bookmark($pdf, $np." Min Max", "");
                                pdf_translate($pdf, $MargeLeft, $MargeBottom); # Positionne le repère au point bas-gauche

                                pdf_setfont($pdf, $HelveticaBold, 14); pdf_set_value ($pdf, 'leading', 15);
                                pdf_show_boxed($pdf, 'Min Max '.utf8_decode($agents[$idagent]) , 0, 767, 534, 16 , 'center', "");
                                ## * Lignes des heures

                                pdf_setlinewidth($pdf, 0.5);

                                for ($i = $start; $i <= ($start + ($space * $nombre)); $i += $space) {
                                    pdf_moveto($pdf, $i, 30); pdf_lineto($pdf,  $i, $tab);
                                }

                                pdf_stroke($pdf);

                                pdf_setlinewidth($pdf, 1);
                            }

                            #### Barre Grise People
                            pdf_setcolor($pdf, "fill", "cmyk", $gris[0], $gris[1], $gris[2], $gris[3]);
                            pdf_rect($pdf, 0, $tab - 2, 533, 12);
                            pdf_fill_stroke($pdf);

                            pdf_setcolor($pdf, "both", "gray", 0, 0, 0, 0);
                            ## Contenu barre grise

                            pdf_setfont($pdf, $Helvetica, 9); pdf_set_value ($pdf, 'leading', 10);
                            pdf_show_boxed($pdf, strftime("%a %d %b", strtotime($date)) , 146, $tab, 75, 11 , 'right', "");

                            pdf_setfont($pdf, $HelveticaBold, 10); pdf_set_value ($pdf, 'leading', 10);
                            pdf_show_boxed($pdf, $pif[$idp]['code'] , 4, $tab, 25, 11 , 'right', "");
                            pdf_show_boxed($pdf, utf8_decode($pif[$idp]['nom']) , 34, $tab, 135, 11 , 'left', "");

                            ### heures
                            pdf_setfont($pdf, $Helvetica, 7); pdf_set_value ($pdf, 'leading', 10);
                            $heures = array('03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '01', '02', '03');
                            for ($i = $start; $i <= ($start + ($space * $nombre)); $i += $space) {
                                pdf_show_boxed($pdf, array_shift($heures) , $i, $tab, 12, 11 , 'center', "");
                            }

                            $tab -= 14;

                            $redtab = array();

                            foreach ($miss as $val) {
                                ## Debut de Ligne
                                pdf_setfont($pdf, $HelveticaBold, 8); pdf_set_value ($pdf, 'leading', 10);
                                pdf_show_boxed($pdf, $val['secteur'].' '.$val['idmission'] , 7, $tab, 55, 11 , 'left', "");

                                pdf_setfont($pdf, $Helvetica, 8); pdf_set_value ($pdf, 'leading', 10);
                                pdf_show_boxed($pdf, ftime($val['h1']) , 125, $tab+1, 25, 11 , 'left', "");
                                pdf_show_boxed($pdf, ftime($val['h2']) , 150, $tab+1, 25, 11 , 'left', "");
                                pdf_show_boxed($pdf, ftime($val['h3']) , 185, $tab+1, 25, 11 , 'left', "");
                                pdf_show_boxed($pdf, ftime($val['h4']) , 210, $tab+1, 25, 11 , 'left', "");

                                pdf_setfont($pdf, $Helvetica, 8); pdf_set_value ($pdf, 'leading', 10);
                                pdf_show_boxed($pdf, utf8_decode($agents[$val['agent']]) , 60, $tab+1, 60, 11 , 'left', "");

                                ## Matin
                                if (($val['h1'] != "00:00:00") or ($val['h2'] != "00:00:00")) {
                                    $h1 = explode(":", $val['h1']);
                                    $h2 = explode(":", $val['h2']);

                                    if ($h1[0] > $h2[0]) {$h2[0] += 24;}

                                    $stpoint = ($start - (3 * $space)) + ($h1[0] * $space) + ($h1[1] / 60 * $space);
                                    $endpoint = ($start - (3 * $space)) + ($h2[0] * $space) + ($h2[1] / 60 * $space);

                                ## barre principale
                                    pdf_setcolor($pdf, "both", "cmyk", $bleufonce[0], $bleufonce[1], $bleufonce[2], $bleufonce[3]);
                                    pdf_rect($pdf, $stpoint, $tab + 2, $endpoint - $stpoint, 5);
                                    pdf_fill($pdf);

                                    if ($val['hb'] > 0) {
                                        $breakpoint = $endpoint - ($val['hb'] * $space);

                                        pdf_setcolor($pdf, "both", "cmyk", $gris[0], $gris[1], $gris[2], $gris[3]);
                                        pdf_rect($pdf, $breakpoint, $tab + 2, $endpoint - $breakpoint, 5);
                                        pdf_fill($pdf);
                                    }

                                    if ($val['hs'] > 0) {
                                        $nightstpoint = $endpoint - ($val['hb'] * $space) - ($val['hs'] * $space);
                                        $nightendpoint = $endpoint - ($val['hb'] * $space);

                                        pdf_setcolor($pdf, "both", "cmyk", $orange[0], $orange[1], $orange[2], $orange[3]);
                                        pdf_rect($pdf, $nightstpoint, $tab + 2, $nightendpoint - $nightstpoint, 5);
                                        pdf_fill($pdf);
                                    }

                                    pdf_setcolor($pdf, "both", "gray", 0, 0 ,0 ,0);

                                    $redtab[] = array('in' => $stpoint, 'out' => $endpoint, 'tab' => $tab);

                                }
                                ## Aprem
                                if ((($val['h3'] != "00:00:00") or ($val['h4'] != "00:00:00")) and ($val['secteur'] != 'VI')) {
                                    $h3 = explode(":", $val['h3']);
                                    $h4 = explode(":", $val['h4']);

                                    if ($h3[0] > $h4[0]) {$h4[0] += 24;}

                                    $stpoint = ($start - (3 * $space)) + ($h3[0] * $space) + ($h3[1] / 60 * $space);
                                    $endpoint = ($start - (3 * $space)) + ($h4[0] * $space) + ($h4[1] / 60 * $space);

                                    pdf_setcolor($pdf, "both", "cmyk", $bleufonce[0], $bleufonce[1], $bleufonce[2], $bleufonce[3]);
                                    pdf_rect($pdf, $stpoint, $tab + 2, $endpoint - $stpoint, 5);
                                    pdf_fill($pdf);

                                    pdf_setcolor($pdf, "both", "gray", 0, 0 ,0 ,0);

                                    $redtab[] = array('in' => $stpoint, 'out' => $endpoint, 'tab' => $tab);
                                }

                                $tab -= 12;
                            }

                            ## calcul redtab
                            $reds = array();

                            if (is_array($redtab)) {
                                foreach($redtab as $vs) {
                                    foreach($redtab as $ch) {
                                    ## cas 1
                                        if (($vs['in'] <= $ch['out']) and ($vs['in'] >= $ch['in']) and ($vs['tab'] != $ch['tab'])) {
                                            if ($vs['out'] < $ch['out']) {
                                                $reds[] = $vs['in'].'X'.$vs['out'].'X'.$vs['tab'];
                                                $reds[] = $vs['in'].'X'.$vs['out'].'X'.$ch['tab'];
                                            } else {
                                                $reds[] = $vs['in'].'X'.$ch['out'].'X'.$vs['tab'];
                                                $reds[] = $vs['in'].'X'.$ch['out'].'X'.$ch['tab'];
                                            }
                                        }
                                    ## cas 2
                                        if (($vs['out'] <= $ch['out']) and ($vs['out'] >= $ch['in']) and ($vs['tab'] != $ch['tab'])) {
                                            if ($vs['in'] < $ch['in']) {
                                                $reds[] = $ch['in'].'X'.$vs['out'].'X'.$vs['tab'];
                                                $reds[] = $ch['in'].'X'.$vs['out'].'X'.$ch['tab'];
                                            } else {
                                                $reds[] = $vs['in'].'X'.$vs['out'].'X'.$vs['tab'];
                                                $reds[] = $vs['in'].'X'.$vs['out'].'X'.$ch['tab'];
                                            }
                                        }
                                    }
                                }
                            }

                            if (count($reds) > 0) {
                                $reds = array_unique($reds);

                                ## Affiche redtab
                                pdf_setcolor($pdf, "both", "cmyk", $rouge[0], $rouge[1], $rouge[2], $rouge[3]);
                                foreach ($reds as $bloc) {
                                    $r = explode("X", $bloc);
                                    pdf_rect($pdf, $r[0], $r[2] + 2, $r[1] - $r[0], 5);
                                    pdf_fill($pdf);
                                }
                                pdf_setcolor($pdf, "both", "gray", 0, 0 ,0 ,0);
                                unset($reds);
                            }
                            unset($redtab);
                        }

                        $turn++;
                    }
                            pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                            pdf_show_boxed($pdf, "Page ".$np , 271 , 0 , 262, 12 , 'right', "");

                        ## * Ligne de bas de page
                            pdf_moveto($pdf, 0, 12); pdf_lineto($pdf, $LargeurUtile, 12);
                            pdf_stroke($pdf);

                        ## * Imprimé le
                            pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                            pdf_show_boxed($pdf, utf8_decode("Imprimé le : ").date("d/m/Y") , 0 , 0 , 276, 12 , 'left', "");

                        ## * NEURO
                            pdf_setfont($pdf, $Helvetica, 10); pdf_set_value ($pdf, "leading", 12);
                            pdf_show_boxed($pdf, "NEURO" , 0 , 0 , 534, 12 , 'center', "");

                            pdf_end_page($pdf);

                }

                pdf_end_document($pdf, '');
                pdf_delete($pdf); # Efface le fichier en mémoire

            #######################################################################################################################################
            # Mails Users

            $mail               = new PHPMailer(true);

            $mail->IsSMTP(); // telling the class to use SMTP
            $mail->Host         = Conf::read('Mail.smtpHost'); // SMTP server

            $mail->SetFrom("nico@exception2.be", "Nico Callandt");

            $mail->Subject      = "['DBB'] Rapport du ".date("d/m/Y");

            $mail->MsgHTML("Voici vos probl&egrave;mes de double booking pour la p&eacute;riode allant du ".fdate($datein)." au ".fdate($dateout));
            $mail->AltBody      = "Voici vos problèmes de double booking pour la période allant du ".fdate($datein)." au ".fdate($dateout);

            $mail->WordWrap = 50;                                   // set word wrap to 50 characters

            # $mail->AddAddress("nico@exception2.be"); // pour test

            if (!empty($emails[$idagent])) {
                $mail->AddAddress($emails[$idagent]);
            } else {
                $mail->AddAddress("nico@exception2.be");
            }

            $mail->AddAttachment(Conf::read('Env.root').$pathpdf.$nompdf);         // add attachments

            if(!$mail->Send())
            {
               echo "Message could not be sent. Mailer Error: " . $mail->ErrorInfo."\r\n";
               exit;
            }

            echo "Message has been sent to ".$emails[$idagent]." on ".date("d/m/Y")."\r\n";

            #
            #######

        }
    }
} else {
    echo "Aucun Double Booking\r\n";
}

?>