<?php
###############################################################################################################################
### Mise a jour d'une note de frais ##########################################################################################
#############################################################################################################################
function updnotefrais($idanimation, $secteur, $montantpaye, $montantfacture, $nfraisyn, $intitule = 'NOTTOUCH', $description = 'NOTTOUCH') {
	global $DB;
    ## CLean vars
    $montantpaye = fnbrbk($montantpaye);
    $montantfacture = fnbrbk($montantfacture);
    $intitule = cleantext($intitule);
    $description = cleantext($description);

    ## récupère les infos de la mission
    switch($secteur) {
        case"AN":
            $infmis = $DB->getRow("SELECT idanimjob as idjob, datem as datemission FROM animation WHERE idanimation = '".$idanimation."'");
        break;
        case"VI":
            $infmis = $DB->getRow("SELECT idvipjob as idjob, vipdate as datemission FROM vipmission WHERE idvip ='".$idanimation."'");
        break;
        case"ME":
            $infmis = $DB->getRow("SELECT datem as datemission FROM merch WHERE idmerch = '".$idanimation."'");
        break;
    }

    ## Vérifie l'existence d'une note de frais pour cette mission
    $infnfrais = $DB->getRow("SELECT * FROM notefrais WHERE idmission='".$idanimation."' AND secteur='".$secteur."'");

    ## recupere les infos manquantes de la notes frais si besoin
    if ($intitule == 'NOTTOUCH') $intitule = $infnfrais['intitule'];
    if ($description == 'NOTTOUCH') $description = $infnfrais['description'];

    ## efface le nfrais si tout est vide
    if(empty($montantpaye) AND empty($montantfacture) AND empty($intitule) AND empty($description) AND (empty($nfraisyn) OR $nfraisyn == 'NULL')) {
        if ($infnfrais['idnfrais'] > 0) $DB->inline('DELETE FROM notefrais WHERE idnfrais = "'.$infnfrais['idnfrais'].'" LIMIT 1');
    } else {
    ## update ou ajoute la nfrais
        if(empty($infnfrais['idnfrais'])) $sql = 'INSERT INTO ';
        else $sql = 'UPDATE ';

        $sql .= 'notefrais SET ';

        $subsql = '';
        if ($idanimation != $infnfrais['idmission'] ) $subsql .= 'idmission="'.$idanimation.'", ';
        if ($secteur != $infnfrais['secteur'] ) $subsql .= 'secteur="'.$secteur.'", ';
        if ($infmis['idjob'] != $infnfrais['idjob'] ) $subsql .= 'idjob="'.$infmis['idjob'].'", ';
        if ($infmis['datemission'] != $infnfrais['datemission'] ) $subsql .= 'datemission="'.$infmis['datemission'].'", ';
        if ($montantfacture != $infnfrais['montantfacture'] ) $subsql .= 'montantfacture="'.$montantfacture.'", ';
        if ($montantpaye != $infnfrais['montantpaye'] ) $subsql .= 'montantpaye="'.$montantpaye.'", ';
        if (($intitule != $infnfrais['intitule']) and ($intitule != 'NOTTOUCH')) $subsql .= 'intitule="'.$intitule.'", ';
        if (($description != $infnfrais['description'] ) and ($description != 'NOTTOUCH')) $subsql .= 'description="'.$description.'", ';

        if (!empty($subsql)) {
            $sql .= substr($subsql, 0, -2); ## enleve le trailing ', '
        
            if($infnfrais['idnfrais'] > 0) $sql .= ' WHERE idnfrais="'.$infnfrais['idnfrais'].'" LIMIT 1';
         $DB->inline($sql);
        }
    }
}

?>