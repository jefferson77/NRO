<?php

include_once NIVO.'print/vip/facture/facture_inc.php';
include_once NIVO.'print/anim/facture/facture_inc.php';
include_once NIVO.'print/merch/facture/facture_inc.php';
include_once NIVO.'print/commun/facman.php';

include_once NIVO.'print/dispatch/dispatch_functions.php';
## Init Vars
if (!isset($mode)) $mode = '';
if (!isset($_GET['idf'])) $_GET['idf'] = '';
if (!isset($_POST['dup'])) $_POST['dup'] = '';

if ((!empty($_POST['facs'])) or (!empty($_POST['print']))) {
    if (empty($web)) {
        echo '<div id="centerzonelarge">';
    } else {
        echo '<div id="infozoneweb">';
        $dupa = 'OK';
        $_POST['ent'] = 'entete';
    }

    if (!empty($_POST['facs'])) {
        $searchfields = array ('f.idfac'  => 'facs');

        $where = 'WHERE '.$DB->MAKEsearch($searchfields);

        $gd = 'OK';
        $dt = 'OK';
    } elseif ( count($_POST['print']) >= 1 ) {
        if ( in_array("garde", $_POST['page']) ) $gd = 'OK';
        if ( in_array("detail", $_POST['page']) ) $dt = 'OK';
        if ( !empty($_POST['dup']) ) $dupa = 'OK';

        $where = "WHERE f.idfac IN ('".implode("', '", $_POST['print'])."')";
    }

    if (!empty($where)) {
    ### Construction de la requete
        $factures = $DB->getArray("SELECT
                f.*, o.langue, c.langue as clangue, c.societe
            FROM facture f
                LEFT JOIN cofficer o ON f.idcofficer = o.idcofficer
                LEFT JOIN client c ON f.idclient = c.idclient
            $where
            ORDER BY f.secteur ASC");

        foreach ($factures as $facinf) {
            if (empty($facinf['langue'])) $facinf['langue'] = $facinf['clangue'];

            if ($facinf['modefac'] == 'M') {
                ## Manuelles
                $printed_fac[$facinf['idcofficer']][] = print_fac_man($facinf['idfac'], 'yes', $_POST['dup'], null, $facinf);
            } else {
                ## Automatiques
                switch ($facinf['secteur']) {
                    case "1": # VIP
                        $printed_fac[$facinf['idcofficer']][] = print_fac_vip($facinf['idfac'], 'entete',$_POST['dup'], '');
                        break;

                    case "2": # Anim
                        $printed_fac[$facinf['idcofficer']][] = print_fac_anim($facinf['idfac'],'entete',$_POST['dup'],'FACTURE');
                        break;

                    case "3": # Merch
                    case "4": # EAS
                        $printed_fac[$facinf['idcofficer']][] = print_fac_merch($facinf['idfac'], 'entete',$_POST['dup'],'FAC');
                        break;
                }
            }
        }

        generateSendTable($printed_fac, 'cofficer', 'temp/facture', 'facture', "Factures");

    } else { ?>
    Aucun num&eacute;ro de facture entr&eacute;
<?php } ?>
    </div>
    <?php
} else {
    include 'v-reprintSearch.php';
}