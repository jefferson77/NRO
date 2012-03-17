#!/usr/bin/php
<?php

error_reporting(E_ALL);
/*
=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+

    Moteur DIMONA cli
    -----------------

    Ce fichier envoie la DIMONA pour tous les employes stockes dans NEURO
    Il effectue une connection en FTP au serveur de l'ONSS, recupere les fichiers accuses, genere les nouveaux fichiers et les envoie

    Version du Glossaire DIMONA : 2011/1

=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+=+

    Connexion au site www.securitesociale.be
    -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    log : ncallandt
    pass : Exception01

    SFTP :
    sshkey generes avec : ssh-keygen -t rsa -b 2048

*/

## Start Chrono  ##
$time_string = explode(" ", microtime());
$stime       = $time_string[1] . substr($time_string[0],1,strlen($time_string[0]));
## fin Start Chrono ##

define('NIVO', dirname(__FILE__).'/../../');
# Classes utilisées
require_once(NIVO."nro/fm.php");
require_once(NIVO."classes/hh.php");
require_once(NIVO."classes/dimona.php");

$firstdate = '2010-08-01';                          # date de lancement de la version 061
$startdate = date("Y-m-d", strtotime('-150 days')); # Date de debut du traitement
$enddate   = date("Y-m-d", strtotime('+4 days'));     # Date de fin

if ($startdate < $firstdate) $startdate = $firstdate; #Ne commence pas de corrections avant la firstdate

echo "---------------------------------------------------------------------------------------------------".PHP_EOL;
echo "Dimona Exception du ".date("d m Y")."              (du ".$startdate." au ".$enddate.")".PHP_EOL;
echo "---------------------------------------------------------------------------------------------------".PHP_EOL;

$DIMONA = new dimona('R');  # Test-Réel T/R
$DIMONA->connectSFTP();

######################################################################################################################################################
## 1 ## Connexion FTP et recuperation des accuses precedents
$DIMONA->getFiles();

######################################################################################################################################################
## 2 ## Parsing des fichiers recuperes
$DIMONA->parseFiles();

######################################################################################################################################################
## 3 ## Tableau des DEJA ENVOYES + Rapport d'erreurs

    $declared = $DB->getArray("SELECT
         d.rna, d.datein, d.typedecl, d.numaccuse
        FROM dimona.declarations d
            LEFT JOIN dimona.fichiers f ON d.idfile = f.idfile
        WHERE d.datein BETWEEN '".$startdate."' AND '".$enddate."'
            AND d.numaccuse > 0
        ORDER BY f.datesend");

    $decltable = array();

    foreach ($declared as $row) {
        $decltable[$row['datein']][$row['rna']]['typedecl']  = $row['typedecl'];
        $decltable[$row['datein']][$row['rna']]['numaccuse'] = $row['numaccuse'];
    }

######################################################################################################################################################
## 4 ## Tableau des A ENVOYER

### People travaillant dans la plage de date

    # Internes
    $people_internes = $DB->getColumn("SELECT idpeople FROM ".Conf::read('Sql.database').".agent WHERE dimonaauto = 'no' AND idpeople > 0");

    # Anim
    $DB->inline("SELECT  a.idpeople, p.nrnational, p.nationalite FROM ".Conf::read('Sql.database').".animation a LEFT JOIN ".Conf::read('Sql.database').".people p ON a.idpeople = p.idpeople WHERE a.datem BETWEEN '".$startdate."' AND '".$enddate."' AND p.catsociale IN (1, 'E') GROUP BY a.idpeople ORDER BY a.idpeople");
    while ($row = mysql_fetch_array($DB->result)) {
        if(!in_array($row['idpeople'], $people_internes)) $peops[$row['idpeople']] = $row['nationalite'].'-'.$row['nrnational'];
    }
    # Vip
    $DB->inline("SELECT v.idpeople, p.nrnational, p.nationalite FROM ".Conf::read('Sql.database').".vipmission v LEFT JOIN ".Conf::read('Sql.database').".people p ON v.idpeople = p.idpeople WHERE v.vipdate BETWEEN '".$startdate."' AND '".$enddate."' AND p.catsociale IN (1, 'E') GROUP BY v.idpeople ORDER BY v.idpeople");
    while ($row = mysql_fetch_array($DB->result)) {
        if(!in_array($row['idpeople'], $people_internes))$peops[$row['idpeople']] = $row['nationalite'].'-'.$row['nrnational'];
    }
    # Merch
    $DB->inline("SELECT m.idpeople, p.nrnational, p.nationalite FROM ".Conf::read('Sql.database').".merch m LEFT JOIN ".Conf::read('Sql.database').".people p ON m.idpeople = p.idpeople WHERE m.datem BETWEEN '".$startdate."' AND '".$enddate."' AND p.catsociale IN (1, 'E') GROUP BY m.idpeople ORDER BY m.idpeople");
    while ($row = mysql_fetch_array($DB->result)) {
        if(!in_array($row['idpeople'], $people_internes))$peops[$row['idpeople']] = $row['nationalite'].'-'.$row['nrnational'];
    }

    if (count($peops) == 0) {
        echo PHP_EOL." Aucun People pour cette période. ".PHP_EOL;
        exit;
    }

    ksort($peops);

### check RegNational
    $errp = array();

    foreach ($peops as $idpeop => $niss) {
        $dts = explode("-", $niss);
        $reste = substr($dts[1], -2) ;
        $nombre = substr($dts[1], 0, -2) ;
        ## Chiffres de controle
        if ((ctype_digit($nombre.$reste)) and (strlen($nombre.$reste) == 11)) {
            $mod = 97 - fmod($nombre, 97);
            if ($mod == $reste) {
                $goodp[$idpeop] = $dts[1];
                if ($dts[0] != 'B') {
                    $warnp[$idpeop] = 'Nationalite non belge : '.$niss; # Warning nationalite non belge et num RN valide
                }
            } else {
                $errp[$idpeop] = 'Chiffre controle incorrect : '.$nombre.' - '.$reste; # RN incorrect : erreur chiffres controle
            }
        } else {
            if (!empty($nombre)) {
                $errp[$idpeop] = 'RN Invalide : '.$niss;    # RN invalide
            } else {
                $errp[$idpeop] = 'RN Vide : '.$niss; # RN Vide
            }
        }
    }


### Rapport D'erreurs : ****

    # echo 'Warn peoples : '.count($warnp)."\r\n";
    # foreach ($warnp as $idpeop => $erreur) {
    #   echo $idpeop.' '.$erreur."\r\n";
    # }

    /*
        TODO : switch affichage dimona to a sent mail avec les erreurs.
    */
    if (count($errp) > 0) {
        $people_infos = $DB->getArray("SELECT pnom, pprenom, idpeople, codepeople FROM people WHERE idpeople IN (".implode(", ", array_keys($errp)).")", "idpeople");
        echo PHP_EOL.'------------------------------------------------------------------------------------------'.PHP_EOL;
        echo 'Bad  peoples : '.count($errp).PHP_EOL;
        echo '------------------------------------------------------------------------------------------'.PHP_EOL;
        foreach ($errp as $idpeop => $erreur) {
            echo str_pad($people_infos[$idpeop]['codepeople'], 7, " ", STR_PAD_LEFT).' | '.str_pad($people_infos[$idpeop]['pnom'].' '.$people_infos[$idpeop]['pprenom'], 35).' : '.$erreur.PHP_EOL;
        }
        echo '------------------------------------------------------------------------------------------'.PHP_EOL.PHP_EOL;
    } else {
        echo PHP_EOL.'------------------------------------------------------------------------------------------'.PHP_EOL;
        echo 'No people RNA error '.PHP_EOL;
        echo '------------------------------------------------------------------------------------------'.PHP_EOL;
    }

### Tables des declarations
    foreach ($goodp as $idpeop => $niss) {

        $thispeop = new hh();
        $peoptable = $thispeop->hhtable($idpeop, $startdate, $enddate);

        foreach ($peoptable as $date => $hh) {
            $formtable[$date][$idpeop] = $niss;
        }
    }

    ksort($formtable);

######################################################################################################################################################
## 5 ## Creation des fichiers a envoyer
    ### Création du fichier DIMONA
    $DIMONA->render_FI($formtable, $decltable);
    $DIMONA->render_FS();
    $DIMONA->render_GO();

    echo PHP_EOL;
    echo $DIMONA->nombredecl." nouvelles déclarations.\n";
    echo $DIMONA->alreadysentdecl." declarations déjà envoyées.\n";
    echo $DIMONA->nombresuppressions." declarations supprimées.\n";
    echo PHP_EOL;

#####################################################################################################################################################
# 6 ## Envoi des fichiers et cloture de la connection FTP

    $DIMONA->sendFiles();

######################################################################################################################################################
## 7 ## Parsing final des fichiers envoyés

    $DIMONA->parseFiles();

# Chrono
    $time_string2 = explode(" ", microtime());
    $etime = $time_string2[1] . substr($time_string2[0], 1, strlen($time_string2[0]));
    echo "\r\n ex time: ".substr($etime - $stime, 0, 6)." sec\r\n";
# Chrono fin
?>