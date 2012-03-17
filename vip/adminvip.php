<?php
define('NIVO', '../');

$Titre = 'VIP';
$Style = 'vip';

$etat  = 0;

include_once NIVO.'includes/entete.php';
include_once NIVO.'conf/vip.php';

# Classes utilisées
include_once NIVO.'classes/vip.php';
include_once NIVO.'classes/geocalc.php';
include_once NIVO.'classes/notefrais.php';
include NIVO.'classes/makehtml.php';

#># Vars
$_SESSION['idmerch']     = null;
$_SESSION['idanimation'] = null;
$_SESSION['idanimjob']   = null;

## Job Infos
$job_infos = (isset($_REQUEST['idvipjob']) and $_REQUEST['idvipjob'] > 0) ?
    $DB->getRow('SELECT * FROM vipjob WHERE idvipjob = '.$_REQUEST['idvipjob']) :
    array();

#### pour le casting
$casting = isset($_REQUEST['casting']) ? $_REQUEST['casting'] : '';

if (isset($_POST['vipactivite']) && $_POST['vipactivite'] >= 1 && $_POST['vipactivite'] <= 20) $_POST['vipactivite'] = 'vipactivite_'.$_POST['vipactivite'];

# Carousel des fonctions
switch ($_GET['act']) {
############### Dupliquer Job #############################################
    case 'showDuplicate':
        include NIVO."vip/v-duplicJob.php";
        break;
    case 'doDuplicate':
        include NIVO."outils/v-debug.php";
        break;
############### Listing des FACTURATION #############################################
    case "facture":
        if ((!empty($_POST['modstate'])) and ($_POST['state'] > 0) and ($_POST['idvipjob'] > 0)) {
            ## Update des missions du Job
            $modif = new db();
            $modif->inline("UPDATE vipmission SET metat = ".$_POST['state']." WHERE idvipjob = ".$_POST['idvipjob']);
            $modif->inline("UPDATE vipjob SET etat = ".$_POST['state']." WHERE idvipjob = ".$_POST['idvipjob']);
        }

        $mode = 'USER';
        include NIVO."admin/vip/facturation.php" ;
        $PhraseBas = 'FACTORING des VIP';
        break;

############## Geoloc #####################################################
        case "geoloc":
            $coeff    = array(0 => 1, 5 => 1.40, 10 => 1.35, 20 => 1.42, 30 => 1.34, 40 => 1.30, 50 => 1.42, 75 => 1.29, 100 => 1.28);
            $idvip    = $_GET['idvip'];
            $idpeople = $_GET['idpeople'];

            $idshop   = $_GET['idshop'];
            $adr      = $_SESSION['adr'];
            $geoloc   = new GeoCalc();
            $geoloc->calculate($_GET['idpeople'], $_GET['idshop'], $_SESSION['adr']);
            ##### LON et LAN pour people et shop sont dans la base #####
            if($geoloc->plat != '0' && $geoloc->plong != '0' && $geoloc->slat != '0' && $geoloc->slong != '0') {
                $dist = $geoloc->EllipsoidDistance($geoloc->plat, $geoloc->plong, $geoloc->slat, $geoloc->slong);
                $dist = round($dist);
                ##### Chercher le bon coefficient dans l'array #####
                foreach($coeff as $k => $v) {
                    if($dist >= $k) {
                        $x = $v;
                    }
                }
                $dist = round($dist * $x);
                $inj = new db();
                $inj->inline("UPDATE vipmission SET km = ".$dist.", vkm = ".$dist." WHERE idvip = ".$idvip);
                unset($_SESSION['adr']);
                $PhraseBas = 'Detaild\'une VIP : Assistant Modifi&eacute;';
                include NIVO."vip/v-detailMission.php";
            }
            ##### LON et LAN pour people ou shop ne sont pas dans la base #####
            else
            {
                ##### LON et LAN pour people n'est pas dans la base #####
                if($geoloc->plat == '0' || $geoloc->plong == '0') {
                    if($_SESSION['adr'] == '1') {
                        $adresse = 'adresse1';
                        $num = 'num1';
                        $cp = 'cp1';
                    }
                    else {
                        $adresse = 'adresse2';
                        $num = 'num2';
                        $cp = 'cp2';
                    }
                    $geo = new db();
                    $sql = 'SELECT '.$adresse.', '.$num.', '.$cp.' FROM people WHERE idpeople = '.$_GET['idpeople'];
                    $geo->inline($sql);
                    $geoloc = mysql_fetch_array($geo->result);
                    include NIVO."data/people/geoloc.php";
                }

                ##### LON et LAN pour shop n'est pas dans la base #####
                if($geoloc->slat == '0' || $geoloc->slong == '0') {
                    $geo = new db();
                    $sql = 'SELECT adresse, cp FROM shop WHERE idshop = '.$_GET['idshop'];
                    $geo->inline($sql);
                    $geoloc = mysql_fetch_array($geo->result);
                    include NIVO."data/shop/geoloc.php" ;
                }
            }
        break;

################# Injecter lat et lon dans la base ###################################
        case "injgeo":
            $idvip = $_GET['idvip'];
            $idpeople = $_GET['idpeople'];
            $injgeo = new db();
            if($_SESSION['adr'] == '1') {
                $glat1 = 'glat1';
                $glong1 = 'glong1';
            }
            else {
                $glat1 = 'glat2';
                $glong1 = 'glong2';
            }
            if(isset($_GET['idpeople'])) {
                $sql = "UPDATE people SET ".$glat1." = ".$_GET['lat'].", ".$glong1." = ".$_GET['lon']." WHERE idpeople = ".$_GET['idpeople'];
            }
            else {
                if(isset($_GET['idshop'])) {
                    $sql = "UPDATE shop SET glat1 = ".$_GET['lat'].", glong1 = ".$_GET['lon']." WHERE idshop = ".$_GET['idshop'];
                }
            }
            $injgeo->inline($sql);
            ?>
                <script type="text/javascript">
                    window.location = "<?php echo $_SESSION['referer']; ?>"
                </script>
            <?php
        break;

############## Ajout d'un JOB #############################################
        case "add":
            $_POST['idagent'] = $_SESSION['idagent'];
            $_POST['idcommercial'] = $DB->CONFIG('vipcommercial');
            $_REQUEST['idvipjob'] = $DB->ADD('vipjob');
            include NIVO.'vip/detail_inc.php';
        break;
############## Selection des éléments #############################################
        case "select":
            $PhraseBas = 'Selection des &eacute;l&eacute;ments';
            $idvip = (!empty($_POST['idvip']))?$_POST['idvip']:$_GET['idvip'];
            $idvipjob = (!empty($_POST['idvipjob']))?$_POST['idvipjob']:$_GET['idvipjob'];
            echo '<div id="centerzonelarge">';
            if($_GET['etape']=="listepeople") include 'select/select-listepeople.php';
            else include 'select/select-'.$_GET['sel'].'.php' ;
            echo '</div>';
        break;
############## Affichage d'un JOB #########################################
        case "show":
            $PhraseBas = 'Detail d\'un job VIP';

            switch ($_GET['etat']) {
                case "0":
                case "1":
                case "11":
                case "12":
                    include NIVO.'vip/detail_inc.php';
                break;
                case "2":
                default:
                    include NIVO.'vip/detail-view.inc.php';
                break;
            }
        break;
############## Modification partielle et affichage d'un JOB #########################################
        case "modif":
            if(isset($_POST['idvipjob'])) $_GET['idvipjob'] = $_POST['idvipjob'];

            switch ($_GET['mod']) {
            #### Mise à jour des infos Client
                    case "client":
                        $modif = new db();

                        // Récupération des anciennes valeurs
                        $result = $DB->getRow('SELECT idclient, idcofficer FROM vipjob WHERE idvipjob = '.$_POST['idvipjob']);
                        $oldClient = $result['idclient'];
                        $oldOfficer = $result['idcofficer'];

                        // Insertion dans le journal
                        if (empty($oldClient)) { // Ajout du client
                            $DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idclient, idcofficer) VALUE(".$_SESSION['idagent'].", 'ADD', 'JOB', 'CLIENT', 'VI', ".$_GET['idvipjob'].", '".$_POST['idclient']."', '".$_POST['idcofficer']."')");
                        } elseif (empty($_POST['idclient'])) { // Suppression du client
                            $DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idclient, idcofficer) VALUE(".$_SESSION['idagent'].", 'DEL', 'JOB', 'CLIENT', 'VI', ".$_GET['idvipjob'].", '".$oldClient."', '".$oldOfficer."')");
                        } else { // Modification du client
                            $DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idclient, idcofficer) VALUE(".$_SESSION['idagent'].", 'MOD-DEL', 'JOB', 'CLIENT', 'VI', ".$_GET['idvipjob'].", '".$oldClient."', '".$oldOfficer."')");
                            $DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idclient, idcofficer) VALUE(".$_SESSION['idagent'].", 'MOD-ADD', 'JOB', 'CLIENT', 'VI', ".$_GET['idvipjob'].", '".$_POST['idclient']."', '".$_POST['idcofficer']."')");
                        }

                        $modif->inline("UPDATE `vipjob` SET idclient = '".$_POST['idclient']."', idcofficer = '".$_POST['idcofficer']."', forfait = '".$_POST['forfait']."' WHERE idvipjob = ".$_POST['idvipjob']);
                        if (!empty($_POST['idclient'])) {
                            $modif->inline("UPDATE `client` SET vip = 1 WHERE idclient = ".$_POST['idclient']);
                        }
                        $PhraseBas = 'Detail d\'une VIP : Client Modifi&eacute;';
                    break;
            #### Mise à jour des infos Lieu
                    case "lieu":

                        if($_GET['from']=='mission') {

                            // Récupération des anciennes valeurs
                            $result = $DB->getRow('SELECT idshop FROM vipmission WHERE idvip = '.$_POST['idvip']);
                            $oldIdshop = $result['idshop'];

                            // Insertion dans le journal
                            if ($oldIdshop==0) { // Ajout du lieu
                                $DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idshop) VALUE(".$_SESSION['idagent'].", 'ADD', 'MISSION', 'SHOP', 'VI', ".$_POST['idvipjob'].", ".$_POST['idvip'].", ".$_POST['idshop'].")");
                            } elseif ($_POST['idshop']=='') { // Suppression du lieu
                                $DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idshop) VALUE(".$_SESSION['idagent'].", 'DEL', 'MISSION', 'SHOP', 'VI', ".$_POST['idvipjob'].", ".$_POST['idvip'].", ".$oldIdshop.")");
                            } else { // Modification du lieu
                                $DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idshop) VALUE(".$_SESSION['idagent'].", 'MOD-DEL', 'MISSION', 'SHOP', 'VI', ".$_POST['idvipjob'].", ".$_POST['idvip'].", ".$oldIdshop.")");
                                $DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idshop) VALUE(".$_SESSION['idagent'].", 'MOD-ADD', 'MISSION', 'SHOP', 'VI', ".$_POST['idvipjob'].", ".$_POST['idvip'].", ".$_POST['idshop'].")");
                            }

                            $DB->inline('UPDATE vipmission SET idshop = "'.$_POST['idshop'].'", agentmodif = "'.$_SESSION['idagent'].'", datemodif = DATE(NOW()) WHERE idvip="'.$_POST['idvip'].'" LIMIT 1');
                        } else {

                            // Récupération des anciennes valeurs
                            $result = $DB->getRow('SELECT idshop FROM vipjob WHERE idvipjob = '.$_POST['idvipjob']);
                            $oldIdshop = $result['idshop'];

                            // Insertion dans le journal
                            if ($oldIdshop==0) { // Ajout du lieu
                                $DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idshop) VALUE(".$_SESSION['idagent'].", 'ADD', 'JOB', 'SHOP', 'VI', ".$_POST['idvipjob'].", ".$_POST['idshop'].")");
                            } elseif ($_POST['idshop']=='') { // Suppression du lieu
                                $DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idshop) VALUE(".$_SESSION['idagent'].", 'DEL', 'JOB', 'SHOP', 'VI', ".$_POST['idvipjob'].", ".$oldIdshop.")");
                            } else { // Modification du lieu
                                $DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idshop) VALUE(".$_SESSION['idagent'].", 'MOD-DEL', 'JOB', 'SHOP', 'VI', ".$_POST['idvipjob'].", ".$oldIdshop.")");
                                $DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idshop) VALUE(".$_SESSION['idagent'].", 'MOD-ADD', 'JOB', 'SHOP', 'VI', ".$_POST['idvipjob'].", ".$_POST['idshop'].")");
                            }

                            $DB->inline('UPDATE vipjob SET idshop="'.$_POST['idshop'].'", agentmodif="'.$_SESSION['idagent'].'", datemodif = DATE(NOW()) WHERE idvipjob="'.$_POST['idvipjob'].'" LIMIT 1');
                        }
                        $PhraseBas = 'Detail d\'une VIP : Lieu Modifi&eacute;';
                    break;
            #### Mise à jour des infos People
                    case "people":

                        $idvipjob = $_POST['idvipjob'];
                        $idpeople = $_POST['idpeople'];

                        // Récupération des anciennes valeurs
                        $result = $DB->getRow('SELECT idpeople FROM vipmission WHERE idpeople = '.$idpeople);
                        $oldPeople = $result['idpeople'];

                        // Insertion dans le journal
                        if ($oldPeople==0) { // Ajout du people
                            $DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idpeople) VALUE(".$_SESSION['idagent'].", 'ADD', 'MISSION', 'PEOPLE', 'VI', ".$idvipjob.", ".$_POST['idvip'].", ".$idpeople.")");
                        } elseif ($_POST['idpeople']=='') { // Suppression du people
                            $DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idpeople) VALUE(".$_SESSION['idagent'].", 'DEL', 'MISSION', 'PEOPLE', 'VI', ".$idvipjob.", ".$_POST['idvip'].", ".$oldPeople.")");
                        } else { // Modification du people
                            $DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idpeople) VALUE(".$_SESSION['idagent'].", 'DEL', 'MISSION', 'PEOPLE', 'VI', ".$idvipjob.", ".$_POST['idvip'].", ".$oldPeople.")");
                            $DB->inline("INSERT INTO journal(idagent, typeaction, element1, element2, secteur, idjob, idmission, idpeople) VALUE(".$_SESSION['idagent'].", 'ADD', 'MISSION', 'PEOPLE', 'VI', ".$idvipjob.", ".$_POST['idvip'].", ".$idpeople.")");
                        }

                        $DB->inline('UPDATE vipmission SET idpeople="'.$_POST['idpeople'].'", agentmodif="'.$_SESSION['idagent'].'", datemodif = DATE(NOW()) WHERE idvip="'.$_POST['idvip'].'" LIMIT 1');
                        $PhraseBas = 'Detail d\'une VIP : People Modifi&eacute;';
                    break;
            #### Mise à jour de la fiche complète
                    default:
                        $modif = new db('vipjob', 'idvipjob');
                        $modif->MODIFIE($_GET['idvipjob'], array('reference' , 'notejob' , 'noteprest' , 'notedeplac' , 'noteloca' , 'notefrais' , 'briefing', 'etat'));
                        $PhraseBas = 'Detail d\'une VIP : Fiche Mise &agrave; Jour';
            }
            if($_GET['from']=='mission') include NIVO.'vip/v-detailMission.php';
            else include NIVO.'vip/detail_inc.php';
        break;

############## Modification complete et affichage d'un JOB et possible switch #########################################
        case "modiffull":
            $DB->MAJ('vipjob');

            switch ($_POST['etat']) {
            #### Mise à jour des infos People
                    case "11":
                        $DB->inline("UPDATE vipmission SET metat = 11 WHERE `idvipjob` = ".$_POST['idvipjob']);
                        include NIVO.'vip/detail_inc.php';
                    break;
            #### Mise à jour de la fiche complète
                    case "12":
                    default:
                        include "detail_inc.php" ;
                        $PhraseBas = 'Detail d\'une VIP : Fiche Mise &agrave; Jour';
            }
        break;


############## DELETE d'un JOB #########################################
        case "raisonout":
            include 'v-raisonout.php';
        break;
        case "delete":
            switch ($_POST['etat']) {
                case '99':
                    $DB->MAJ('vipjob');
                    include 'v-listJob.php';
                break;
                case '2':
                    ## Job Normal
                    $ispeople = $DB->getOne("SELECT count(idvip) AS ispeople FROM `vipmission` WHERE `idpeople` > 0 AND `idvipjob` = ".$_REQUEST['idvipjob']);

                    if ($ispeople > 0) {
                        ## il y a des people, ne pas effacer
                        include 'detail_inc.php';
                        $PhraseBas = 'Impossible d&rsquo;effacer un job NON VIDE';
                    } else {
                        $DB->MAJ('vipjob');
                        include 'v-listJob.php';
                        $PhraseBas = 'Listing des VIP';
                    }
                break;
            }
        break;
############## DELETE d'un JOB et du contenu CELSYS ONLY #########################################
        case "delcelsys":
            $jobdel = new db();

            if ($_GET['etat'] == 0) {
                $jobdel->inline("DELETE FROM `vipdevis` WHERE `idvipjob` = ".$_GET['idvipjob']);
            } else {
                $jobdel->inline("DELETE FROM `vipmission` WHERE `idvipjob` = ".$_GET['idvipjob']);
            }

            $jobdel->inline("DELETE FROM `vipjob` WHERE `idvipjob` = ".$_GET['idvipjob']);
            ############## Listing des Job Results ##################################
            $_GET['listing'] = 'direct';
            $sort = 'j.idagent';
            include 'v-listJob.php';
            $PhraseBas = 'Listing des VIP';
        break;
############## Modification et affichage d'une MISSION LARGE #########################################
        case "showmission":
            $PhraseBas = 'Detail d\'une VIP : Assistant Modifi&eacute;';
            include "v-detailMission.php" ;
        break;
        case "modifmission":

    #### Calcul des KM entre le people et le shop
            if($_POST['Modifier'] == 'A') {
                ## calcul kilometrage
                if(empty($_REQUEST['idpeople'])) {
                    alertBox('Pas de people selectionné !');
                } else if(empty($_REQUEST['idshop'])) {
                    alertBox('Pas de shop selectionné !');
                } else {
                    $infopeople = $DB->getRow("SELECT glat".$_POST['peoplehome']." AS pglat, glong".$_POST['peoplehome']." AS pglong
                                                FROM people
                                                WHERE idpeople='".$_REQUEST['idpeople']."'");
                    $infoshop = $DB->getRow('SELECT glat, glong FROM shop WHERE idshop="'.$_REQUEST['idshop'].'"');
                    if($infoshop['glat']>0 && $infoshop['glong']>0) {
                        if($infopeople['pglat']>0 && $infopeople['pglong']>0) {
                            include_once(NIVO.'classes/geocalc.php');
                            $geo=new GeoCalc();
                            # si people geoloc
                            $coeff = array(5 => 1.40, 10 => 1.35, 20 => 1.42, 30 => 1.34, 40 => 1.30, 50 => 1.42, 75 => 1.29, 100 => 1.28);
                            $dist = round($geo->EllipsoidDistance($infoshop['glat'], $infoshop['glong'], $infopeople['pglat'], $infopeople['pglong']));
                            ##### Chercher le bon coefficient dans l'array #####
                            foreach($coeff as $k => $v) {
                                if($dist >= $k) {
                                    $x = $v;
                                }
                            }
                            $dist *= $x * 2;
                            $km = round($dist);
                            $DB->inline('UPDATE vipmission SET km='.round($km * 1.3).', vkm='.$km.', fkm = 0, vfkm = 0 WHERE idvip='.$_REQUEST['idvip']);
                            unset($_POST['km']);   # Pour ne pas les updater dans le MAJ
                            unset($_POST['vkm']);  #
                            unset($_POST['fkm']);  #
                            unset($_POST['vfkm']);  #
                        } else alertBox('People pas geolocalisé !');
                    } else alertBox('Shop pas geolocalisé !');
                }
            }

            if (empty($_POST['dontmaj'])) {
        #### Maj des données de la fiche VIP
            ## ajust doit être NEGATIF
                if ($_POST['ajust'] > 0) $_POST['ajust'] = '-'.$_POST['ajust'];

            ## Force vcat
                if (($_POST['cat'] >= $catfacture) AND ($_POST['vcat'] == 0)) {
                        $_POST['vcat'] = $catpaye;
                } else {
                        $_POST['vcat'] = $_POST['vcat'];
                }

                /*
                    TODO : attention a la localistaion : trouver autre code pour que cela fonctionne quelle que soit la langue
                */
                ## MAJ de la fiche
                $DB->MAJ('vipmission', 'h200|contratencode|nfraisyn');
                #### Mise à jour note de frais
                updnotefrais($_REQUEST['idvip'], 'VI', $_POST['nfrais-montantpaye'], $_POST['nfrais-montantfac'],$_POST['nfraisyn'], trim($_POST['nfrais-intitule']), trim($_POST['nfrais-descr']));
            }

        #### Redirection : Liste ou fiche next/prev
            switch ($_POST['Modifier']) {
                case "Modifier":
                case "Retour":
                    include "detail_inc.php" ;
                break;
                case "Previous":
                case "Next":
                case "A":
                default:
                    include "v-detailMission.php" ;
                break;
            }
        break;

############## VALIDATION d'un JOB #########################################
        case "valider":
            #### Mise à jour de la fiche : "etat"=1
            $DB->inline("UPDATE vipjob SET `etat` = 1 WHERE `idvipjob` = ".$_GET['idvipjob']);                                          # UPD etat du job

            $champs = "`idvipjob`, `idshop`, `vipdate`, `vipactivite`, `sexe`, `vipin`, `vipout`, `brk`, `h150`, `h200`, `night`,
                            `ts`, `fts`, `km`, `fkm`, `unif`, `net`, `loc1`, `loc2`, `cat`, `disp`, `notes`, `fr1`";

            $DB->inline("INSERT INTO `vipmission` ($champs) SELECT $champs FROM `vipdevis` WHERE `idvipjob` = ".$_GET['idvipjob']);     # Deplaces fiches de devis a mission
            $DB->inline("UPDATE `vipmission` SET `vcat` = $catpaye WHERE `cat` >= $catfacture AND `vcat` = 0 AND `idvipjob` = ".$_GET['idvipjob']);     # check catering
            ## Deplacement des frais de fr1 vers les Notes de Frais
            $rows = $DB->getArray("SELECT idvip, fr1 FROM `vipmission` WHERE `fr1` > 0 AND `idvipjob` = ".$_GET['idvipjob']);
            foreach ($rows as $row) {
                updnotefrais($row['idvip'], 'VI', '0', $row['fr1'], '');
                $DB->inline("UPDATE vipmission SET fr1 = '' WHERE idvip = ".$row['idvip']);
            }
            $DB->inline("DELETE FROM `vipdevis` WHERE `idvipjob` = ".$_GET['idvipjob']);                                                                            # efface les devis

            $PhraseBas = 'Validation d\'un JOB';
            include "detail_inc.php" ;
        break;
############## VALIDATION d'un JOB #########################################
        case "unvalider":
            #### Mise à jour de la fiche : "etat"=0
            $update = new db();
            $update->inline("UPDATE vipjob SET `etat` = 0 WHERE `idvipjob` = ".$_GET['idvipjob']);
            $update->inline("UPDATE vipmission v LEFT JOIN notefrais nf ON nf.secteur = 'VI' AND nf.idmission = v.idvip SET v.fr1 =  nf.montantfacture WHERE v.idvipjob = ".$_GET['idvipjob']); # replace le montant facture dnas fr1 avant transfert a vipdevis
            $update->inline("DELETE FROM notefrais WHERE secteur = 'VI' AND idjob = ".$_GET['idvipjob']." AND idmission > 0");
            $champs = "`idvipjob` , `idshop` , `vipdate` , `vipactivite` , `sexe` , `vipin` , `vipout` , `brk` , `night` , `h150` , `h200` ,
                            `ts` , `fts` , `km` , `fkm` , `unif` , `net` , `loc1` , `loc2` , `cat` , `disp` , `fr1` , `notes`";
            $update->inline("INSERT INTO `vipdevis` ($champs) SELECT $champs FROM `vipmission` WHERE `idvipjob` = ".$_GET['idvipjob']);     # Deplaces fiches de devis a mission
            $update->inline("DELETE FROM `vipmission` WHERE `idvipjob` = ".$_GET['idvipjob']);                                                                          # efface les devis

            $PhraseBas = 'NON Validation d\'un JOB';
            include "detail_inc.php" ;
        break;
############## planning des Missions Search #############################################
    case "planningsearch":
            include "v-searchPlanning.php" ;
            $PhraseBas = 'Listing des VIP';
        break;
############## planning des Missions Results #############################################
        case "planning":
            $quidsearch = $_GET['quidsearch'];
            if (isset($_POST['quidvalue'])) {
                $quidvalue = $_POST['quidvalue'];

            }
            if (isset($_GET['quidvalue'])) {
                $quidvalue = $_GET['quidvalue'];
            }
            $sort = $_GET['sort'];
            if (empty($sort)) $sort = 'idvip';
            include "planning.php" ;
            $PhraseBas = 'Listing des VIP';
        break;
############## planning des Missions Search #############################################
    case "listingsearch":
            empty($_SESSION['quid']);
            empty($_SESSION['quod']);
            include "v-searchJob.php" ;
            $PhraseBas = 'Listing des VIP';
        break;
############## Listing des Job Results #############################################
    case "listing":
        include 'v-listJob.php';
        $PhraseBas = 'Listing des VIP';
    break;

############## Web list #############################################
        case "webviplist":
            $PhraseBas = 'Liste des nouvelles demandes On-line';
            include "weblist.php" ;
        break;

############## Web show #############################################
        case "webshow":
            $idwebvipjob = $_GET['idwebvipjob'];
            $PhraseBas = 'Affichage des nouvelles demandes On-line';
            include "webdetail.php" ;
        break;
############## VALIDATION d'un JOB #########################################
        case "webvalider":
            $idwebvipjob = $_POST['idwebvipjob'];
            $_GET['idvipjob'] = $_POST['idwebvipjob'];

            # 1 webvipjob
                ### search webvipjob ###
                    $detailjob = new db('webvipjob', 'idwebvipjob ', 'webneuro');
                    $detailjob->inline("SELECT * FROM `webvipjob` WHERE `idwebvipjob` = $idwebvipjob ");
                    $infosjob = mysql_fetch_array($detailjob->result) ;
                #/## search webvipjob ###
            # 2 vipjob
                ### Add vipjob  + update ###
                    $_POST['idclient']      = $infosjob['idclient'];
                    $_POST['idcofficer']    = $infosjob['idcofficer'];
                    $_POST['idagent']       = $_SESSION['idagent'];

                    $_POST['reference']     = addslashes($infosjob['reference']);
                    $_POST['notejob']       = addslashes($infosjob['notejob']);
                    $_POST['noteprest']     = addslashes($infosjob['noteprest']);
                    $_POST['notedeplac']    = addslashes($infosjob['notedeplac']);
                    $_POST['noteloca']      = addslashes($infosjob['noteloca']);
                    $_POST['notefrais']     = addslashes($infosjob['notefrais']);
                    $_POST['datein']        = $infosjob['datein'];
                    $_POST['dateout']       = $infosjob['dateout'];
                    $_POST['datecommande']  = $infosjob['datecommande'];
                    $_POST['bondecommande'] = addslashes($infosjob['bondecommande']);

                    # Add
                    $ajout = new db('vipjob', 'idvipjob');
                    $ajout->AJOUTE(array('idclient' , 'idcofficer', 'idagent', 'etat'));
                    $_GET['idvipjob'] = $ajout->addid;
                    # update
                    $modif = new db('vipjob', 'idvipjob');
                    $modif->MODIFIE($_GET['idvipjob'], array('idclient' , 'idcofficer', 'idagent', 'etat', 'reference' , 'notejob' , 'noteprest' , 'notedeplac' , 'noteloca' , 'notefrais', 'datein' , 'dateout', 'datecommande', 'bondecommande' ));
                #/## Add vipjob  + update ###
            # 3 vipdevis
                ### Search Webvipbuild et creation Vipdevis

                    ### Table activités
                        $vipactivite_1  = "Accueil";
                        $vipactivite_2  = "Chauffeur";
                        $vipactivite_3  = "Chef H&ocirc;tesse";
                        $vipactivite_4  = "D&eacute;bit";
                        $vipactivite_5  = "D&eacute;monstration";
                        $vipactivite_6  = "Encadrement";
                        $vipactivite_7  = "Encodage";
                        $vipactivite_8  = "Flyering";
                        $vipactivite_9  = "Information";
                        $vipactivite_10 = "Parking";
                        $vipactivite_11 = "R&eacute;ception";
                        $vipactivite_12 = "Sampling";
                        $vipactivite_13 = "Service";
                        $vipactivite_14 = "Shuttle";
                        $vipactivite_15 = "Spraying";
                        $vipactivite_16 = "Vestiaire";
                        $vipactivite_17 = "Event-Coordinator";
                    #/## Table activités

                        #Search
                        $detailbuild = new db('webvipbuild', 'idvipbuild', 'webneuro');
                        $detailbuild->inline("SELECT * FROM `webvipbuild` WHERE `idwebvipjob` = $idwebvipjob ORDER BY 'vipactivite'");
                        while ($infos = mysql_fetch_array($detailbuild->result)) {
                            $i++;
                            $h = 0; #pour nombre hotesse
                            $vipdate1 = $infos['vipdate1'];
                            #fragmentation par date
                            while ($vipdate1 <= $infos['vipdate2']) {
                                $h++;
                                $vipnombre = $infos['vipnombre'];
                                #fragmentation par nombre dans date
                                while ($vipnombre > 0) {
                                    #Injection

                                    ############## Duplication d'une Mission VIPMISSION #############################################
                                    $vipdate = $vipdate1;
                                    $vipactivite_x = 'vipactivite_'.$infos['vipactivite'];
                                    $vipactivite = $$vipactivite_x;
                                    $sexe = strtolower($infos['sexe']);
                                    if ($sexe == 'h') {$sexe = 'm';}
                                    $vipin = $infos['vipin'];
                                    $vipout = $infos['vipout'];

                                    $detailvip2 = new db('vipdevis', 'idvipjob');
                                    $detailvip2->inline("INSERT INTO `vipdevis` (`idvipjob` , `vipdate` , `vipactivite` , `sexe` , `vipin` , `vipout`) VALUES ('".$_GET['idvipjob']."' , '$vipdate' , '$vipactivite' ,  '$sexe' , '$vipin' , '$vipout');");
                                    ############## Duplication d'une Mission VIPMISSION #############################################
                                    $vipnombre--;
                                }
                                $dd = explode('-', $vipdate1);
                                $vipdate1 = date ("Y-m-d", mktime (0,0,0,$dd[1],$dd[2]+1,$dd[0]));
                            }
                        }
                # 4 Move des fichiers annexés
                    ############## Delete des fichier annexé de ce job de ce JOB ################################
                        $path = Conf::read('Env.root').'media/annexe/vipweb/';
                        $path2 = Conf::read('Env.root').'media/annexe/vip/';
                        $ledir = $path;
                        $d = opendir ($ledir);
                        $nomvip = 'vip-'.$idwebvipjob.'-';
                        $nomvip2 = 'vip-'.$_GET['idvipjob'].'-';
                        while ($name = readdir($d)) {
                            if (
                                ($name != '.') and
                                ($name != '..') and
                                ($name != 'index.php') and
                                ($name != 'index2.php') and
                                ($name != 'temp') and
                                (strchr($name, $nomvip))
                            ) {
                                if (!empty($name)) {
                                    $vip1 = $path.$name;
                                    $vip2a = str_replace($nomvip, $nomvip2, $name);
                                    $vip2 = $path2.$vip2a;
                                    rename($vip1, $vip2);
                                }
                            }
                        }
                        closedir ($d);

                        #/ ## Search Webvipbuild et creation Vipdevis

            # 5 webvipjob
                ### Delete des webvipbuild et Delete des webvipjob
                    ############## Delete des webvipbuild de ce JOB ################################
                        $jobdel1 = new db('webvipbuild', 'idvipbuild', 'webneuro');
                        $sqldel1 = "DELETE FROM `webvipbuild` WHERE `idwebvipjob` = $idwebvipjob;";
                        $jobdel1->inline($sqldel1);

                    ############## PAS DE DEVIS + DEL ###########################################
                        $jobdel2 = new db('webvipjob', 'idwebvipjob ', 'webneuro');
                        $sqldel2 = "DELETE FROM `webvipjob` WHERE `idwebvipjob` = $idwebvipjob;";
                        $jobdel2->inline($sqldel2);
                        $oldidvipjob = $idwebvipjob;
                #/ ## Delete des webvipbuild et Delete des webvipjob

            $PhraseBas = 'Validation d\'un JOB';
            include "detail_inc.php" ;
        break;

############## Web Delete #############################################
        case "webdelete":
            $idwebvipjob = $_POST['idwebvipjob'];
            $_GET['idvipjob'] = $_POST['idwebvipjob'];

            ############## Delete des fichier annexé de ce job de ce JOB ################################

                $path = Conf::read('Env.root').'media/annexe/vipweb/';
                $ledir = $path;
                $d = opendir ($ledir);
                $nomvip = 'vip-'.$idwebvipjob.'-';
                while ($name = readdir($d)) {
                    if (
                        ($name != '.') and
                        ($name != '..') and
                        ($name != 'index.php') and
                        ($name != 'index2.php') and
                        ($name != 'temp') and
                        (strchr($name, $nomvip))
                    ) {
                        if (!empty($name)) {
                            if(!unlink("$ledir$name")) die ("this file cannot be delete");
                        }
                    }
                }
                closedir ($d);

            ############## Delete des webvipbuild de ce JOB ################################
                $jobdel1 = new db('webvipbuild', 'idvipbuild', 'webneuro');
                $sqldel1 = "DELETE FROM `webvipbuild` WHERE `idwebvipjob` = $idwebvipjob;";
                $jobdel1->inline($sqldel1);

            ############## PAS DE DEVIS + DEL ###########################################
                $jobdel2 = new db('webvipjob', 'idwebvipjob ', 'webneuro');
                $sqldel2 = "DELETE FROM `webvipjob` WHERE `idwebvipjob` = $idwebvipjob;";
                $jobdel2->inline($sqldel2);

            $PhraseBas = 'Liste des nouvelles demandes On-line';
            include NIVO.'vip/weblist.php';
        break;
    ############## VALIDATION de la moulinette #########################################
        case "moulinette":
            $_GET['idvipjob'] = $_POST['idvipjob'];

            # 1 vipjob
                ### search vipjob ###
                    $detailjob = new db('vipjob', 'idvipjob ');
                    $detailjob->inline("SELECT * FROM `vipjob` WHERE `idvipjob` = ".$_GET['idvipjob']);
                    $infosjob = mysql_fetch_array($detailjob->result) ;
                    $etat = $infosjob['etat'];
                    $idshop = $infosjob['idshop'];

                #/## search webvipjob ###
            # 2 vipjob
            # 3 vipdevis
                ### Search Webvipbuild et creation Vipdevis

                    ### Table activités
                        $vipactivite_1 = "Accueil";
                        $vipactivite_2 = "Chauffeur";
                        $vipactivite_3 = "Chef Hôtesse";
                        $vipactivite_4 = "Débit";
                        $vipactivite_5 = "Démonstration";
                        $vipactivite_6 = "Encadrement";
                        $vipactivite_7 = "Encodage";
                        $vipactivite_8 = "Flyering";
                        $vipactivite_9 = "Information";
                        $vipactivite_10 = "Parking";
                        $vipactivite_11 = "Réception";
                        $vipactivite_12 = "Sampling";
                        $vipactivite_13 = "Service";
                        $vipactivite_14 = "Shuttle";
                        $vipactivite_15 = "Spraying";
                        $vipactivite_16 = "Vestiaire";
                        $vipactivite_17 = "Event-Coordinator";
                    #/## Table activités

                        #Search
                        $detailbuild = new db('vipbuild', 'idvipbuild');
                        $detailbuild->inline("SELECT * FROM `vipbuild` WHERE `idvipjob` = ".$_GET['idvipjob']." ORDER BY 'vipactivite'");

                        $i = 0;
                        while ($infos = mysql_fetch_array($detailbuild->result)) {
                            $i++;
                            $h = 0; #pour nombre hotesse
                            $vipdate1 = $infos['vipdate1'];
                            #fragmentation par date
                            while ($vipdate1 <= $infos['vipdate2']) {
                                $h++;
                                $vipnombre = $infos['vipnombre'];
                                #fragmentation par nombre dans date
                                while ($vipnombre > 0) {
                                    #Injection
                                    ############## Duplication d'une Mission VIPMISSION #############################################
                                    $vipdate = $vipdate1;
                                    $vipactivite_x = 'vipactivite_'.$infos['vipactivite'];
                                    $vipactivite = $$vipactivite_x;
                                    if ($infos['vipactivite'] == 99) {
                                        $vipactivite = $infos['tvipactivite'];
                                    } else {
                                        $infos['tvipactivite'] = '';
                                    }

                                    $vipactivite = addslashes($vipactivite);

                                    $sexe   = $infos['sexe'];
                                    $vipin  = $infos['vipin'];
                                    $vipout = $infos['vipout'];
                                    $idshop = $infos['idshop'];
                                    $brk    = $infos['brk'];

                                    $night  = $infos['night'];
                                    $h150   = $infos['h150'];
                                    $ts     = $infos['ts'];
                                    $fts    = $infos['fts'];
                                    $km     = $infos['km'];
                                    $fkm    = $infos['fkm'];
                                    $unif   = $infos['unif'];
                                    $loc1   = $infos['loc1'];
                                    $cat    = $infos['cat'];
                                    $disp   = $infos['disp'];
                                    $fr1    = $infos['fr1'];

                                    if ($etat == 0) { ### vipdevis
                                        $DB->inline("INSERT INTO `vipdevis` (`idvipjob` , `vipdate` , `vipactivite` , `sexe` , `vipin` , `vipout` , `idshop`, `brk` , `night` , `h150` , `ts` , `fts` , `km` , `fkm` , `unif` , `loc1` , `cat` , `disp` , `fr1`)
                                                            VALUES ('".$_GET['idvipjob']."' , '$vipdate' , '$vipactivite' ,  '$sexe' , '$vipin' , '$vipout' , '$idshop' , '$brk' , '$night' , '$h150' ,  '$ts' , '$fts' , '$km' , '$fkm' , '$unif' , '$loc1' , '$cat' ,  '$disp' , '$fr1');");
                                    } else {
                                        $DB->inline('UPDATE vipjob SET etat = 1 WHERE idvipjob='.$_GET['idvipjob'].' LIMIT 1');
                                        $DB->inline("INSERT INTO `vipmission` (`idvipjob` , `vipdate` , `vipactivite` , `sexe` , `vipin` , `vipout` , `idshop`, `brk` , `night` , `h150` , `ts` , `fts` , `km` , `fkm` , `unif` , `loc1` , `cat` , `disp` , `fr1`)
                                                            VALUES ('".$_GET['idvipjob']."' , '$vipdate' , '$vipactivite' ,  '$sexe' , '$vipin' , '$vipout' , '$idshop' , '$brk' , '$night' , '$h150' ,  '$ts' , '$fts' , '$km' , '$fkm' , '$unif' , '$loc1' , '$cat' ,  '$disp' , '$fr1');");
                                    }
                                    ############## Duplication d'une Mission VIPMISSION #############################################
                                    $vipnombre--;
                                }
                                $dd = explode('-', $vipdate1);
                                $vipdate1 = date ("Y-m-d", mktime (0,0,0,$dd[1],$dd[2]+1,$dd[0]));
                            }
                        }
                # 4 Move des fichiers annexés
            # 5 vipjob
                ### Delete des vipbuild
                    ############## Delete des vipbuild de ce JOB ################################
                        $jobdel1 = new db('vipbuild', 'idvipbuild');
                        $sqldel1 = "DELETE FROM `vipbuild` WHERE `idvipjob` = ".$_GET['idvipjob'];
                        $jobdel1->inline($sqldel1);

                #/ ## Delete des vipbuild

            $PhraseBas = 'Validation de la moulinette';
            include "detail_inc.php" ;
        break;

############## Recherche d'un JOB #########################################
        case "search":
        default:
            $PhraseBas = 'Recherche de VIP';
            $_GET['idvipjob'] = '';

            empty($_SESSION['quid']);
            empty($_SESSION['quod']);

            include "v-searchJob.php" ;
}
?>
<div id="topboutons">
<table border="0" cellspacing="1" cellpadding="0">
    <tr>
        <td class="on"><a href="?act=add"><img src="<?php echo STATIK ?>illus/ajouter.gif" alt="ajouter" width="32" height="32" border="0"><br>Ajouter</a></td>
        <td class="on"><a href="?act=listingsearch"><img src="<?php echo STATIK ?>illus/rechercher2.gif" alt="search" width="32" height="32" border="0"><br>Rechercher</a></td>
<?php if (!empty($_SESSION['vipjobquid'])) { ?>
        <td class="on"><a href="?act=listing"><img src="<?php echo STATIK ?>illus/retourliste.gif" alt="show" width="32" height="32" border="0"><br>Back Liste</a></td>
<?php }
if (!empty($_REQUEST['idvipjob']) and (!($job_infos['facnum'] > 0) or ($_SESSION['roger'] == 'devel')) ) {
    if ($etat == '0') {
?>
        <td class="on"><a href="?act=valider&idvipjob=<?php echo $_REQUEST['idvipjob'] ;?>"><img src="<?php echo STATIK ?>illus/ok.gif" alt="search" width="32" height="32" border="0"><br>Valider</a></td>
<?php
    }
    if ($etat == '1') {
?>
            <td class="on"><a href="?act=unvalider&idvipjob=<?php echo $_REQUEST['idvipjob'] ;?>"><img src="<?php echo STATIK ?>illus/flechgb.gif" alt="search" width="32" height="20" border="0"><br>Invalider</a></td>
<?php
    }
}

?>
        <?php if (!empty($job_infos['idvipjob'])): ?>
            <td class="on"><a href="?act=showDuplicate&idvipjob=<?php echo $job_infos['idvipjob'] ;?>"><img src="<?php echo STATIK ?>illus/dupliquer.gif" alt="dupliquer" width="32" height="32" border="0"><br>Dupliquer</a></td>
        <?php endif ?>
        <td class="on"><a href="?act=listing&listing=direct"><img src="<?php echo STATIK ?>illus/liste.gif" alt="search" width="32" height="32" border="0"><br>Mes Jobs</a></td>
        <td class="on"><a href="?act=planningsearch"><img src="<?php echo STATIK ?>illus/planning.gif" alt="planning" width="32" height="32" border="0"><br>Planning</a></td>
<?php if (!empty($_SESSION['missionquid'])) { ?>
        <td class="on"><a href="?act=planning&action=skip&sort=m.idvip"><img src="<?php echo STATIK ?>illus/planning.gif" alt="planning" width="32" height="32" border="0"><br>R&eacute;sultats</a></td>
<?php } ?>
        <td class="on"><a href="?act=planning&listing=missing"><img src="<?php echo STATIK ?>illus/todo.gif" alt="search" width="32" height="32" border="0"><br>To do</a></td>


<?php if (($_REQUEST['act'] != 'planningsearch') AND ($_REQUEST['act'] != 'planning') AND ($_REQUEST['act'] != 'listingsearch') AND ($_REQUEST['act'] != 'listing') AND ($_REQUEST['act'] != 'search') and (!empty($_REQUEST['idvipjob']))) { ?>
        <td class="on"><a href="?act=show&idvipjob=<?php echo $_REQUEST['idvipjob'] ;?>&etat=<?php echo $etat ;?>"><img src="<?php echo STATIK ?>illus/selectionner2.gif" alt="show" width="32" height="32" border="0"><br>Back Job</a></td>
<?php }
if ((!empty($_REQUEST['idvipjob'])) AND ($etat != 2)) {
?>
        <td class="on"><a href="javascript:;" onClick="OpenBrWindow('<?php echo NIVO ?>print/vip/offre/offre.php?idvipjob=<?php echo $_REQUEST['idvipjob'] ;?>','Main','scrollbars=yes,status=yes,resizable=yes','300','500','true')"><img src="<?php echo STATIK ?>illus/printr01.gif" alt="print" width="32" height="32" border="0"><br>Offre</a></td>
        <td class="on"><a href="javascript:;" onClick="OpenBrWindow('<?php echo NIVO ?>print/vip/presence/presence.php?print=date&idvipjob=<?php echo $_REQUEST['idvipjob'] ;?>','Main','','300','300','true')"><img src="<?php echo STATIK ?>illus/listepresence.gif" alt="print" width="32" height="32" border="0"><br>Pr&eacute;sence</a></td>
        <td class="on"><a href="javascript:;" onClick="OpenBrWindow('<?php echo NIVO ?>print/vip/contrat/contrat.php?idvipjob=<?php echo $_REQUEST['idvipjob'] ;?>','Main','scrollbars=yes,status=yes,resizable=yes','300','300','true')"><img src="<?php echo STATIK ?>illus/rapports.gif" alt="print" width="32" height="32" border="0"><br>Contrats</a></td>
<?php } ?>
        <td class="on"><a href="?act=facture"><img src="<?php echo STATIK ?>illus/rapportmail.gif" alt="planning" width="32" height="32" border="0"><br>Factures</a></td>
<?php if (($_REQUEST['act'] != 'planningsearch') AND ($_REQUEST['act'] != 'planning') AND ($_REQUEST['act'] != 'listingsearch') AND ($_REQUEST['act'] != 'listing') AND ($_REQUEST['act'] != 'search') and (!empty($_REQUEST['idvipjob']))) { ?>
        <td class="on"><a href="?act=raisonout&idvipjob=<?php echo $_REQUEST['idvipjob'] ;?>"><img src="<?php echo STATIK ?>illus/trash.gif" alt="show" width="32" height="32" border="0"><br>Effacer Job</a></td>
<?php
    if ($_SESSION['roger'] == 'devel') { ?>
        <td class="on"><a href="?act=delcelsys&etat=<?php echo $etat ;?>&idvipjob=<?php echo $_REQUEST['idvipjob'] ;?>"><img src="<?php echo STATIK ?>illus/trash.gif" alt="show" width="32" height="32" border="0"><br>BIG DEL</a></td>
<?php } ?>
<?php } ?>
    </tr>
</table>
</div>
<?php include NIVO."includes/pied.php"; ?>