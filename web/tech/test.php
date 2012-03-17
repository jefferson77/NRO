<?php
include NIVO.'conf/pays.php';

class test
{
######## MySQL config site #########
    var $idpeople    = '';
    var $recid       = '';
    var $nomvs       = '';
    var $prenomvs    = '';
    var $nationalite = '';
    var $Erreur      = array();
    var $ErrGS       = array();
    var $errortable  = array();
    var $errs        = array();

#################################

    function test ($idpeople) {
        $this->idpeople = $idpeople;
    }

## Test Génériques Remplacements

    function triminvisibles ($field, $valeur, $DBchamp) {
        # Caractères invisibles en début ou en fin CORRIGE
        if (trim($valeur) != $valeur) {
            $newvaleur = trim($valeur);

            if ($this->idpeople != 'norecord') {

                # Inline Modification
        $DBvaleur = html_entity_decode($newvaleur); # evite de renvoyer de l'HTML dans SQL
        $idp = $this->idpeople;
        $people = new db();
        $people->inline("UPDATE `people` SET `$DBchamp` = '$DBvaleur' WHERE `idpeople` ='$idp';");


                $erreur['Champ'] = $field;
                $erreur['Valeur'] = $valeur;
                $erreur['ValeurCorrige'] = $DBvaleur;
                $erreur['Infos'] = 'Commence ou fini par des caractères invisibles';
                $this->Corrections[] = $erreur;
            }
            $valeur = $newvaleur;
        }
        return $valeur;
    }

    function remplacer ($field, $arrayfrom, $arrayto, $valeur2, $DBchamp) {

        $newvaleur = str_replace($arrayfrom, $arrayto, $valeur2);

        if ($newvaleur != $valeur2) {
            if ($this->idpeople != 'norecord') {

                # Inline Modification

                $idp = $this->idpeople;
                $people = new db();
                $people->inline("UPDATE `people` SET `$DBchamp` = '$newvaleur' WHERE `idpeople` ='$idp';");

                $erreur['Champ'] = $field;
                $erreur['Valeur'] = $valeur2;
                $erreur['ValeurCorrige'] = $newvaleur;
                $erreur['Infos'] = 'corrigée';
                $this->Corrections[] = $erreur;
            }
            $valeur2 = $newvaleur;
        }
        return $valeur2;
    }

    function contient ($field, $valeur, $contient) {
        if (preg_match("/$contient/i" , $valeur) == 1) {
            $erreur['Champ'] = $field;
            $erreur['Valeur'] = $valeur;
            $erreur['Infos'] = 'Ne peux pas contenir "'.$contient.'"';
            $this->ErrGS[] = $erreur;

            $resultat = 'NOTOK';
        } else {$resultat = 'OK';}
        return $resultat;
    }

    function longueur ($field, $valeur, $longueur) {
        if (strlen($valeur) > $longueur) {
            $erreur['Champ'] = $field;
            $erreur['Valeur'] = $valeur;
            $erreur['Infos'] = 'fait plus de "'.$longueur.'" caractères';
            $this->ErrGS[] = $erreur;

            $resultat = 'NOTOK';
        } else {$resultat = 'OK';}
        return $resultat;
    }

    function estvide ($field, $valeur) {
        if ($valeur == '') {
            $erreur['Champ'] = $field;
            $erreur['Valeur'] = $valeur;
            $erreur['Infos'] = 'Est Vide';
            $this->ErrGS[] = $erreur;

            $resultat = 'NOTOK';
        } else {$resultat = 'OK';}
        return $resultat;
    }

    function isalpha ($field, $valeur) {
        $accepte = array("|'|", "|â|", "|é|", "|è|", "|ê|", "|ç|", "|[A-Z]|", "|[a-z]|", "| |", "|-|", "|\.|");
        $nettoye = preg_replace($accepte, '', $valeur) ;
        if (!empty($nettoye)) {
            $erreur['Champ'] = $field;
            $erreur['Valeur'] = $valeur;
            $erreur['Infos'] = 'Contient des caractères non permis "'.$nettoye.'"';
            $this->ErrGS[] = $erreur;

            $resultat = 'NOTOK';
        } else {$resultat = 'OK';}
        return $resultat;
    }

    function isnumeric ($field, $valeur) {
        if (!ctype_digit($valeur)) {
            $erreur['Champ'] = $field;
            $erreur['Valeur'] = $valeur;
            $erreur['Infos'] = 'Ne peut contenir que des chiffres 0-9';
            $this->ErrGS[] = $erreur;

            $resultat = 'NOTOK';
        } else {$resultat = 'OK';}
        return $resultat;
    }

    function isalnum ($field, $valeur) {
        if (!ctype_alnum ($valeur)) {
            $erreur['Champ'] = $field;
            $erreur['Valeur'] = $valeur;
            $erreur['Infos'] = 'Ne peut contenir que des chiffres 0-9 ou des lettres A-Z';
            $this->ErrGS[] = $erreur;

            $resultat = 'NOTOK';
        } else {$resultat = 'OK';}
        return $resultat;
    }

    function modulo97 ($field, $valeur) {
        $reste = substr($valeur, -2) ;
        $nombre = substr($valeur, 0, -2) ;
        if (fmod($nombre, 97) == 0) {$mod = 97;} else {$mod = fmod($nombre, 97);}
        if ($mod != $reste) {
            $erreur['Champ'] = $field;
            $erreur['Valeur'] = $nombre.'-'.$reste;
            $erreur['Infos'] = 'Erreur sur les chiffres de controle';
            $this->ErrGS[] = $erreur;
        }


    }

    function estlongde ($field, $valeur, $longueur) {
        if (strlen($valeur) != $longueur) {
            $erreur['Champ'] = $field;
            $erreur['Valeur'] = $valeur;
            $erreur['Infos'] = 'Doit comporter '.$longueur.' caractères';

            if ($field == 'Registre National') {
                $this->ErrDI[] = $erreur;
            } else {
                $this->ErrGS[] = $erreur;
            }
        }

    }

###### Test des champs ###############################################################
    function checkNCP ($num, $pays) {

        $nomchamp   = 'Code Postal Naissance';
        $DBnomchamp = 'ncp';

        $num = $this->triminvisibles ($nomchamp, $num, $DBnomchamp);

        if (!empty($num)) {
            switch ($pays) {
                case 'BE': # Belgique
                    if (!preg_match("/^\d{4}$/", $num)) {
                        $this->ErrGS[]    = array(
                            'Champ'  => $nomchamp,
                            'Valeur' => $num,
                            'Infos'  => 'Un code Postal Belge s\'écrit sour la forme : 9999 (4 Chiffres)',
                        );
                    }
                    break;
                case 'FR': # France
                    if (!preg_match("/^((0[1-9])|([1-8][0-9])|(9[0-8])|(2A)|(2B))[0-9]{3}$/", $num)) {
                        $this->ErrGS[]    = array(
                            'Champ'  => $nomchamp,
                            'Valeur' => $num,
                            'Infos'  => 'Un code Postal Français s\'écrit sour la forme : 99999 (5 Chiffres)',
                        );
                    }
                    break;

                case 'CG': # Congo
                case 'CD': # Congo
                    break;

                case 'MA': # Maroc
                    if (!preg_match("/^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$/", $num)) {
                        $this->ErrGS[]    = array(
                            'Champ'  => $nomchamp,
                            'Valeur' => $num,
                            'Infos'  => 'Un code Postal Marocain s\'écrit sour la forme : 99999 (5 Chiffres)',
                        );
                    }
                    break;

                case 'RW': # Rwanda
                    break;

                case 'IT': # Italie
                    if (!preg_match("/^(V-|I-)?[0-9]{4}$/", $num)) {
                        $this->ErrGS[]    = array(
                            'Champ'  => $nomchamp,
                            'Valeur' => $num,
                            'Infos'  => 'Un code Postal Italien s\'écrit sour la forme : 9999 (4 Chiffres)',
                        );
                    }
                    break;

                case 'ES': # Espagne
                    if (!preg_match("/^([1-9]{2}|[0-9][1-9]|[1-9][0-9])[0-9]{3}$/", $num)) {
                        $this->ErrGS[]    = array(
                            'Champ'  => $nomchamp,
                            'Valeur' => $num,
                            'Infos'  => 'Un code Postal Espagnol s\'écrit sour la forme : 99999 (5 Chiffres)',
                        );
                    }
                    break;

                case 'CM': # Cameroun
                    break;

                case 'RO': # Roumanie
                    if (!preg_match("/^\d{5}$/", $num)) {
                        $this->ErrGS[]    = array(
                            'Champ'  => $nomchamp,
                            'Valeur' => $num,
                            'Infos'  => 'Un code Postal Roumain s\'écrit sour la forme : 99999 ((4) Chiffres)',
                        );
                    }
                    break;

                default:
                    break;
            }
        }
    }

    function checkPRE ($prenomvs) {

        $nomchamp = 'Prénom';
        $DBnomchamp = 'pprenom';

        $prenomvs = $this->triminvisibles ($nomchamp, $prenomvs, $DBnomchamp);
        if ($this->estvide ($nomchamp, $prenomvs) == 'OK') {
            $this->isalpha ($nomchamp, $prenomvs);
        }
        $this->longueur ($nomchamp, $prenomvs, 36);

        $this->prenomvs = $prenomvs;
    }

    function checkNOM ($nomvs) {

        $nomchamp = 'Nom';
        $DBnomchamp = 'pnom';

        $nomvs = $this->triminvisibles ($nomchamp, $nomvs, $DBnomchamp);
        if ($this->estvide ($nomchamp, $nomvs) == 'OK') {
            $this->isalpha ($nomchamp, $nomvs);

        }
        $this->contient ($nomchamp, $nomvs, $this->prenomvs);
        $this->longueur ($nomchamp, $nomvs, 36);

        $this->nomvs = $nomvs;
    }

    function checkCCC ($categorie) {

        $nomchamp   = 'Categorie Sociale';
        $DBnomchamp = 'catsociale';

        $categorie = $this->triminvisibles ($nomchamp, $categorie, $DBnomchamp);
        if ($this->estvide ($nomchamp, $categorie) == 'OK') {
            if (!in_array($categorie, array('1', '2', '3', '4'))) {
                $erreur['Champ']  = $nomchamp;
                $erreur['Valeur'] = $categorie;
                $erreur['Infos']  = 'Contient une valeur non permise (Ouvrier, Employé, Autre, Indépendant)';
                $this->ErrGS[]    = $erreur;
            }
        }
    }

    function checkSEX ($sexe) {

        $nomchamp   = 'Sexe';
        $DBnomchamp = 'sexe';

        $sexe = $this->triminvisibles ($nomchamp, $sexe, $DBnomchamp);
        if ($this->estvide ($nomchamp, $sexe) == 'OK') {
            if (!in_array($sexe, array('M', 'F'))) {
                $erreur['Champ']  = $nomchamp;
                $erreur['Valeur'] = $sexe;
                $erreur['Infos']  = 'Contient une valeur non permise (M ou F)';
                $this->ErrGS[]    = $erreur;
            }
        }
    }

    function checkRUE ($rue) {

        $nomchamp = 'Rue';
        $DBnomchamp = 'adresse1';

        $rue = $this->triminvisibles ($nomchamp, $rue, $DBnomchamp);
        if ($this->estvide ($nomchamp, $rue) == 'OK') {
            $this->isalpha ($nomchamp, $rue);

        }
        $this->longueur ($nomchamp, $rue, 36);
    }

    function checkLOC ($localite) {

        $nomchamp = 'Localité';
        $DBnomchamp = 'ville1';

        $localite = $this->triminvisibles ($nomchamp, $localite, $DBnomchamp);
        if ($this->estvide ($nomchamp, $localite) == 'OK') {
            $this->isalpha ($nomchamp, $localite);

        }
        $this->longueur ($nomchamp, $localite, 36);
    }

    function checkCPO ($copdepostal) {

        $nomchamp = 'Code Postal';
        $DBnomchamp = 'cp1';

        $copdepostal = $this->triminvisibles ($nomchamp, $copdepostal, $DBnomchamp);
        if ($this->estvide ($nomchamp, $copdepostal) == 'OK') {
            $this->isnumeric ($nomchamp, $copdepostal);

        }
        $this->longueur ($nomchamp, $copdepostal, 6);
    }

    function checkNCF ($compte) {

        $nomchamp = 'Compte Bancaire';
        $DBnomchamp = 'banque';

        $compte = $this->triminvisibles ($nomchamp, $compte, $DBnomchamp);
        if ($this->estvide ($nomchamp, $compte) == 'OK') {

        $carfrom = array('-', '.', ' ');
        $carto = "";

        $compte = $this->remplacer($nomchamp, $carfrom, $carto, $compte, $DBnomchamp);

            if ((preg_match("/([0-9]{3})([0-9]{7})([0-9]{2})/", $compte, $regs)) and (strlen($compte) == 12)) {
                $cleancompte = $regs[1].$regs[2].$regs[3];
                $this->modulo97 ($nomchamp, $cleancompte);
            } else {
                $erreur['Champ'] = $nomchamp;
                $erreur['Valeur'] = $compte;
                $erreur['Infos'] = 'Le numéro de compte doit être sous le format XXXXXXXXXXXX';
                $this->ErrGS[] = $erreur;
            }

        }
    }

    function checkDTN ($daten) {

        $nomchamp = 'Date de Naissance';
        $DBnomchamp = 'ndate';


        $aa = explode ('-', $daten);
        $age = strftime("%Y", time()) - $aa[0];

        if (($age > 80) or ($age < 15)) {
            $erreur['Champ'] = $nomchamp;
            $erreur['Valeur'] = $daten;
            $erreur['Infos'] = 'La date de Naissance semble erronnée';
            $this->ErrGS[] = $erreur;
        }
    }


    function checkRNA ($niss, $datenaissance, $sexe, $paysnaissance) {
        $nomchamp   = 'Registre National';
        $DBnomchamp = 'nrnational';
        $niss = $this->triminvisibles ($nomchamp, $niss, $DBnomchamp);

        $carfrom = array('-', '.', ' ');
        $carto = "";
        $niss = $this->remplacer($nomchamp, $carfrom, $carto, $niss, $DBnomchamp);


        if (($this->nationalite == 'B') or (!empty($niss))) {
            if ($this->estvide ($nomchamp, $niss) == 'OK') {
                if ($this->isnumeric ($nomchamp, $niss) == 'OK') {
                    $this->estlongde ($nomchamp, $niss, 11);
                    if ((preg_match("/([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{3})([0-9]{2})/", $niss, $regs)) and (strlen($niss) == 11)) {
                        $naissance = explode ('-', $datenaissance);
                        if (($regs[3] == $naissance[2]) and ($regs[2] == $naissance[1]) and ($regs[1] == substr($naissance[0], -2))) {
                            if (fmod($regs[4], 2) == 1) {$sx = 'M';} else {$sx = 'F';}
                            if ($sx == $sexe) {
                                $reste = substr($niss, -2) ;
                                $nombre = substr($niss, 0, -2) ;
                                $mod = 97 - fmod($nombre, 97);
                                if ($mod != $reste) {
                                    $erreur['Champ'] = $nomchamp;
                                    $erreur['Valeur'] = $nombre.'-'.$reste;
                                    $erreur['Infos'] = 'Erreur sur les chiffres de controle';
                                    $this->ErrDI[] = $erreur;
                                }
                            } else {
                                $erreur['Champ'] = $nomchamp;
                                $erreur['Valeur'] = $niss;
                                $erreur['Infos'] = 'Le NN ne correspond pas au sexe';
                                $this->ErrDI[] = $erreur;
                            }
                        } else {
                            $erreur['Champ'] = $nomchamp;
                            $erreur['Valeur'] = $niss;
                            $erreur['Infos'] = 'Le NN ne correspond pas a la date de naissance';
                            $this->ErrDI[] = $erreur;
                        }
                    }
                }
            }
        }
        else {
            global $iso_pays_fr;

            $nomchamp   = 'Pays de Naissance';
            $DBnomchamp = 'npays';
            $paysnaissance = $this->triminvisibles ($nomchamp, $paysnaissance, $DBnomchamp);

            if ($this->estvide ($nomchamp, $paysnaissance) == 'OK') {
                if (!in_array($paysnaissance, array_keys($iso_pays_fr))) {
                    # Ajout du pays de naissance non reconnu a un fichier liste
                    $string = $paysnaissance;
                    $string .= "\r\n";# CR/LF

                    $fileliste = fopen("paysnaissance.txt", "a");
                    $write = fputs($fileliste, $string);
                    fclose($fileliste);

                    $erreur['Champ']  = $nomchamp;
                    $erreur['Valeur'] = $paysnaissance;
                    $erreur['Infos']  = 'Pays non reconnu';
                    $this->ErrDI[]    = $erreur;
                }
            }
        }
    }

    function checkNAT($nationalite) {

        $nomchamp = 'Nationalité';
        $DBnomchamp = 'nationalite';

        $nationalite = $this->triminvisibles ($nomchamp, $nationalite, $DBnomchamp);


        if ($this->estvide ($nomchamp, $nationalite) == 'OK') {
            $natios = array( # Liste des nationalités Permises par Groupe-S
                    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
                    'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
                    'W', 'X', 'Y', 'Z', '9');

                if (!in_array($nationalite, $natios)) {
                    $erreur['Champ'] = $nomchamp;
                    $erreur['Valeur'] = $nationalite;
                    $erreur['Infos'] = 'Nationalité non reconnue';
                    $this->ErrGS[] = $erreur;
                }
        }
        $this->nationalite = $nationalite;
    }

    function checkNUI ($numcarte) {

        $nomchamp = 'Numéro Carte Identité';
        $DBnomchamp = 'ncidentite';

        $numcarte = $this->triminvisibles ($nomchamp, $numcarte, $DBnomchamp);

        $carfrom = array('-', '.', ' ');
        $carto = "";
        $numcarte = $this->remplacer($nomchamp, $carfrom, $carto, $numcarte, $DBnomchamp);

        if ($this->estvide ($nomchamp, $numcarte) == 'OK') {
            if ($this->nationalite == 'Belge') {
                if ($this->isnumeric ($nomchamp, $numcarte) == 'OK') {
                    $this->estlongde ($nomchamp, $numcarte, 12);
                    $this->modulo97 ($nomchamp, $numcarte);
                }
            }
        }
    }

    function checkRPE ($registre) {

        $nomchamp = 'Registre Personnel';
        $DBnomchamp = 'codepeople';


        $registre = $this->triminvisibles ($nomchamp, $registre, $DBnomchamp);

        if ($registre < 1) {

            $nreg = new db();
            $nextreg = $nreg->CONFIG('nextregperso');

            $idp = $this->idpeople;
            $people = new db();
            $people->inline("UPDATE `people` SET `$DBnomchamp` = '$nextreg' WHERE `idpeople` ='$idp';");

            $nextreg++;
            $updreg = new db();
            $updreg->inline("UPDATE `config` SET `vvaleur` = '$nextreg' WHERE `vnom` ='nextregperso';");

            $erreur['Champ'] = $nomchamp;
            $erreur['Valeur'] = $registre;
            $erreur['ValeurCorrige'] = $nextreg;
            $erreur['Infos'] = 'Registre Ajouté';
            $this->Corrections[] = $erreur;


        }
    }

    function checkAPC ($num) {

        $nomchamp = 'Personnes a charge';
        $DBnomchamp = 'pacharge';

        $num = $this->triminvisibles ($nomchamp, $num, $DBnomchamp);

        $this->isnumeric ($nomchamp, $num) ;
    }

    function checkNEC ($num) {

        $nomchamp = 'Enfants a charge';
        $DBnomchamp = 'eacharge';

        $num = $this->triminvisibles ($nomchamp, $num, $DBnomchamp);

        $this->isnumeric ($nomchamp, $num) ;
    }

    function checkNUR ($registre) {

        $nomchamp = 'Numéro de Rue';
        $DBnomchamp = 'num1';

        $registre = $this->triminvisibles ($nomchamp, $registre, $DBnomchamp);

        $this->isalnum ($nomchamp, $registre) ;
    }

    function checkNUB ($registre) {

        $nomchamp = 'Numéro de boite';
        $DBnomchamp = 'bte1';

        $registre = $this->triminvisibles ($nomchamp, $registre, $DBnomchamp);

        $this->isalnum ($nomchamp, $registre) ;
    }

    function checkCPP ($pays) {
        global $iso_pays_fr;

        $nomchamp   = 'Pays de résidence';
        $DBnomchamp = 'pays1';

        $pays = $this->triminvisibles ($nomchamp, $pays, $DBnomchamp);

        if ($this->estvide ($nomchamp, $pays) == 'OK') {
            if (!in_array($pays, array_keys($iso_pays_fr))) {
                $erreur['Champ']  = $nomchamp;
                $erreur['Valeur'] = $pays;
                $erreur['Infos']  = 'Pays non reconnu';
                $this->ErrGS[]    = $erreur;
            }
        }
    }

    function checkETC ($etatcivil) {

        $nomchamp = 'Etat Civil';
        $DBnomchamp = 'etatcivil';

        $etatcivil = $this->triminvisibles ($nomchamp, $etatcivil, $DBnomchamp);

        if ($this->estvide ($nomchamp, $etatcivil) == 'OK') {

            $etatscivils = array( # Liste Etats Civils Reconnus
                    '1', '2', '3', '4', '5');

                if (!in_array($etatcivil, $etatscivils)) {
                    $erreur['Champ'] = $nomchamp;
                    $erreur['Valeur'] = $etatcivil;
                    $erreur['Infos'] = 'Etat Civil non reconnu';
                    $this->ErrGS[] = $erreur;
                }
        }
    }

    function checkLGE ($languemat) {

        $nomchamp = 'Langue Usuelle';
        $DBnomchamp = 'lbureau';

        $languemat = $this->triminvisibles ($nomchamp, $languemat, $DBnomchamp);

        $lafrom = array(
            'Fr',
            'fr');

        $lato = array(
                    'FR',
                    'FR');

        $languemat = $this->remplacer($nomchamp, $lafrom, $lato, $languemat, $DBnomchamp);

        if ($this->estvide ($nomchamp, $languemat) == 'OK') {

            $langues = array( # Langues Maternelles reconnues
                    '1' => 'FR',
                    '2' => 'NL');

                if (!in_array($languemat, $langues)) {
                    $erreur['Champ'] = $nomchamp;
                    $erreur['Valeur'] = $languemat;
                    $erreur['Infos'] = 'Langue Usuelle Non reconnue';
                    $this->ErrGS[] = $erreur;
                }
        }
    }

    function checkZ05 ($datein) {


        $nomchamp = 'Date d\'entrée en service';
        $DBnomchamp = 'dateentree';

        #$datein = $this->triminvisibles ($nomchamp, $datein, $DBnomchamp);
        $idp = $this->idpeople;

        if (($datein == '0000-00-00') or ($datein == '')) {
            # Recherche de la date de la première mission du people ANIM

            $dateanim = new db();
            $dateanim->inline("SELECT `datem` FROM `animation` WHERE `idpeople` = '$idp' ORDER BY `datem` ASC LIMIT 0,1");
            $bloc = mysql_fetch_array($dateanim->result);
            $animdate = $bloc['datem'];

            if (($animdate == '0000-00-00') or ($animdate == '')) {
                $datevip = new db();
                $datevip->inline("SELECT `vipdate` FROM `vipmission` WHERE `idpeople` = '$idp' ORDER BY `vipdate` ASC LIMIT 0,1");
                $bloc = mysql_fetch_array($datevip->result);
                $animdate = $bloc['vipdate'];
            }

            # Inline Modification de la date d'entrée en service
            $people = new db();
            $people->inline("UPDATE `people` SET `$DBnomchamp` = '$animdate' WHERE `idpeople` ='$idp';");
            # Fin inline

            $erreur['Champ'] = $nomchamp;
            $erreur['Valeur'] = $datein;
            $erreur['ValeurCorrige'] = $animdate;
            $erreur['Infos'] = 'Date d\'entrée ajoutée';
            $this->Corrections[] = $erreur;

        }
    }

    function listerr() {
        if (count($this->ErrGS) >= 1) {
            foreach ($this->ErrGS as $value) {
                $this->errs[$value['Champ']] = $value['Infos'];
            }
        }

        if (count($this->ErrDI) >= 1) {
            foreach ($this->ErrDI as $value) {
                $this->errs[$value['Champ']] = $value['Infos'];
            }
        }
    }

    function erraffiche($champ, $affname) {
        if (array_key_exists($champ, $this->errs) ) {
            echo '<font color="#FF0000">'.$affname.'</font>';
        } else {
            echo $affname;
        }
    }
}
?>