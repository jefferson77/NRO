<?php
class dimona {
######## Config #####################################
    var $numexpediteur      = '106740'; # Numéro d'expéditeur Dimona (6 N)
    var $numemploy          = '112651531'; # Numero d'employeur ONSS
    var $versionfichier     = '20113'; # Numéro de version du fichier 2011/1
    var $KEYpass            = 'Exception01';
    var $login              = "EXP_CALLANDT";
    var $password           = "exception01";

######## SFTP config #####################################
    var $SFTP_host          = 'sftp.socialsecurity.be';
    var $SFTP_port          = 8022;
    var $SFTP_secret        = '';
    var $sftp               = null;

######## Default values #############################
    var $connection         = false;
    var $PEMfile            = '';
    var $KEYfile            = '';
    var $dimpath            = '';
    var $sendpath           = '';
    var $parsepath          = '';
    var $mode               = 'R';

######## Initianlisation des Variables ##############
    var $nomfichierFI       = ''; # Nom du fichier INPUT
    var $nomfichierFS       = ''; # Nom du fichier SIGNATURE
    var $nomfichierGO       = ''; # Nom du fichier GO

    var $nombredecl         = 0;
    var $nombresuppressions = 0;
    var $alreadysentdecl    = 0;
    var $xml                = '';

## Init
    function init_dimona() {
        $this->SFTP_key_pub = Conf::read('Env.root').'core/cli/dimona/id_rsa.pub'; # cree avec : ssh-keygen -t rsa -b 2048
        $this->SFTP_key     = Conf::read('Env.root').'core/cli/dimona/id_rsa';
        $this->PEMfile      = Conf::read('Env.root')."core/cli/dimona/dimona.pem"; # cree avec : openssl pkcs12 -in flannoosign.p12 -passin pass:Exception01 -out dimona.pem -clcerts -nokeys
        $this->KEYfile      = Conf::read('Env.root')."core/cli/dimona/dimona.key"; # cree avec : openssl pkcs12 -in flannoosign.p12 -passin pass:Exception01 -passout pass:Exception01 -out dimona.key
        $this->dimpath      = Conf::read('Env.root')."media/dimona/";
        $this->sendpath     = $this->dimpath.'tosend/';
        $this->parsepath    = $this->dimpath.'toparse/';
        $this->archivepath  = $this->dimpath.'parsed/';
        $this->date         = date("Y-m-d");
        $this->hour         = date("H:i:s");
    }

################################################################################################################
#### Formating tools
################################################################################################################
    function prechar ($string, $nombchar, $char) {
        return str_repeat($char, $nombchar - strlen($string)).$string;
    }

    function postchar ($string, $nombchar, $char) {
        return $string.str_repeat($char, $nombchar - strlen($string));
    }

################################################################################################################
#### Init
################################################################################################################
    function set_nomfichiers () {
        global $DB;

        $nombase = 'DIMN.';                                             # DIMONA
        $nombase .= $this->numexpediteur.'.';                           # Numéro de l'expéditeur
        $nombase .= date("Ymd").'.';                                    # Date de l'envoi

        ## Nombre de fichiers aujourd'hui
        $nbrfichiers = $DB->getOne("SELECT count(*) as nbr FROM dimona.fichiers WHERE fname LIKE '".$nombase."%'");
        $nombase .= $this->prechar(($nbrfichiers + 1), 5, '0').'.' ;    # Numéro d'ordre par jour
        $nombase .= $this->mode.'.' ;
        $nombase .= '1' ;                                               # 1 partie sur 1

        $this->nomfichierFI = $this->sendpath.'FI.'.$nombase.'.1';
        $this->nomfichierFS = $this->sendpath.'FS.'.$nombase.'.1';
        $this->nomfichierGO = $this->sendpath.'GO.'.$nombase;

        echo PHP_EOL."Nom fichiers : ".$nombase.PHP_EOL.PHP_EOL;
    }

################################################################################################################
#### Fonctions Principales
################################################################################################################

    function __construct($mode) {
        $this->mode = $mode;
        $this->init_dimona();
        $this->set_nomfichiers();

        if (file_exists($this->nomfichierFI)) unlink($this->nomfichierFI);
        if (file_exists($this->nomfichierFS)) unlink($this->nomfichierFS);
        if (file_exists($this->nomfichierGO)) unlink($this->nomfichierGO);
    }

    function render_FI($formtable, $decltable) {
        $this->xml = new DOMDocument('1.0', 'UTF-8');
        $this->xml->formatOutput = true;

        $dim = $this->xml->createElement("Dimona");
        $dim->setAttribute('xsi:noNamespaceSchemaLocation','Dimona_'.$this->versionfichier.'.xsd');
        $dim->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');

        # Nouvelles Declaration
        foreach($formtable as $date => $tpeople) {
            foreach($tpeople as $idpeople => $niss) {
                if (!array_key_exists($date, $decltable)) $decltable[$date] = array(); # a cause du warning de la liste suivante si aucune declaration n'a ete envoyee pour cette date
                if (array_key_exists($niss, $decltable[$date]) and ($decltable[$date][$niss]['typedecl'] == 'entree')) {
                    ## Deja envoye
                    $this->alreadysentdecl++;
                } else {
                    # Nouvelle declaration
                    $form = $this->add_form();
                    $form->appendChild($this->DimonaIn($date, $niss));
                    $dim->appendChild($form);

                    $decltable[$date][$niss]['typedecl'] = 'entree';

                    $this->nombredecl++;
                }
            }
        }

        ## Suppressions déclarations inutiles
        foreach ($decltable as $date => $decl) {
            foreach ($decl as $niss => $infos) {
                if (!in_array($niss, $formtable[$date]) and ($infos['typedecl'] == 'entree')) {
                    # Suppression de la déclaration
                    $form = $this->add_form();
                    $form->appendChild($this->DimonaCancel($infos['numaccuse'], $niss));
                    $dim->appendChild($form);

                    $decltable[$date][$niss]['typedecl'] = 'annulation';

                    $this->nombresuppressions++;
                }
            }
        }

        $this->xml->appendChild($dim);

        echo "Fichier ".$this->nomfichierFI." crée : ".file_put_contents($this->nomfichierFI, str_replace("\n", "\r\n", $this->xml->saveXML()))." octets".PHP_EOL;
    }

    function render_FS() {
        exec("openssl smime -sign -in ".$this->nomfichierFI." -signer ".$this->PEMfile." -inkey ".$this->KEYfile." -passin pass:".$this->KEYpass." -outform PEM -out ".$this->nomfichierFS);
        $signature = file_get_contents($this->nomfichierFS);
        $signature = trim(preg_replace("/(-----BEGIN PKCS7-----|-----END PKCS7-----)/", "", $signature));
        echo "Fichier ".$this->nomfichierFS." crée : ".file_put_contents($this->nomfichierFS, str_replace("\n", "\r\n", $signature))." octets".PHP_EOL;
    }

    function render_GO() {
        echo "Fichier ".$this->nomfichierGO." crée : ".file_put_contents($this->nomfichierGO, "")." octets".PHP_EOL;
    }

    function add_form() {
        $form = $this->xml->createElement("Form");
        $form->appendChild($this->xml->createElement("Identification", "DIMONA"));
        $form->appendChild($this->xml->createElement("FormCreationDate", $this->date));
        $form->appendChild($this->xml->createElement("FormCreationHour", $this->hour.".000"));
        $form->appendChild($this->xml->createElement("AttestationStatus", 0));
        $form->appendChild($this->xml->createElement("TypeForm", "SU"));

        return $form;
    }

    function DimonaIn($date, $tpeople) {
        $dimona = $this->xml->createElement("DimonaIn");
        $dimona->appendChild($this->xml->createElement("StartingDate", $date));
        $dimona->appendChild($this->xml->createElement("EndingDate", $date));

        $EmployerId = $this->xml->createElement("EmployerId");
        $EmployerId->appendChild($this->xml->createElement("NOSSRegistrationNbr", $this->numemploy));
        $dimona->appendChild($EmployerId);

        $NaturalPerson = $this->xml->createElement("NaturalPerson");
        $NaturalPerson->appendChild($this->xml->createElement("INSS", $tpeople));
        $dimona->appendChild($NaturalPerson);

        $DimonaFeatures = $this->xml->createElement("DimonaFeatures");
        $DimonaFeatures->appendChild($this->xml->createElement("JointCommissionNbr", "XXX"));
        $DimonaFeatures->appendChild($this->xml->createElement("WorkerType", "OTH"));
        $dimona->appendChild($DimonaFeatures);

        return $dimona;
    }

    function DimonaCancel($numdimona, $niss) {
        $dimona = $this->xml->createElement("DimonaCancel");
        $dimona->appendChild($this->xml->createElement("DimonaPeriodId", $numdimona));
        $dimona->appendChild($this->xml->createElement("INSS", $niss));

        $EmployerId = $this->xml->createElement("EmployerId");
        $EmployerId->appendChild($this->xml->createElement("NOSSRegistrationNbr", $this->numemploy));
        $dimona->appendChild($EmployerId);

        return $dimona;
    }

################################################################################################################
#### SEND & RETRIEVE files
################################################################################################################
    function connectSFTP() {
        $connection = ssh2_connect($this->SFTP_host, $this->SFTP_port, array('hostkey'=>'ssh-rsa'));

        if (!ssh2_auth_pubkey_file($connection, $this->login, $this->SFTP_key_pub, $this->SFTP_key, $this->SFTP_secret)) {
            die('SFTP CONNECTION Failed');
        }

        $this->sftp = ssh2_sftp($connection);
    }

    function getFiles() {

        $sftp_dir_out = "ssh2.sftp://".$this->sftp."/OUT/";

        if ($handle = opendir($sftp_dir_out)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != '..') {
                    if ($stream = fopen($sftp_dir_out.$file, 'r')) {
                        $contents = stream_get_contents($stream);
                        if (file_put_contents ($this->parsepath.$file, $contents) === false) {
                            echo "There was a problem during the write of file ".$file." to ".$this->parsepath.$file.PHP_EOL; die;
                        } else {
                            if (unlink($sftp_dir_out.$file)) {
                                echo $file." retrieved to ".$this->parsepath.$file." and deleted".PHP_EOL;
                            } else {
                                echo "could not delete ".$file.PHP_EOL; die;
                            }
                        }
                        fclose($stream);
                    } else {
                        echo "There was a problem during the download of file ".$file." to ".$this->parsepath.$file.PHP_EOL; die;
                    }
                }
            }

            closedir($handle);
        } else {
            echo "Couldn't change directory OUT".PHP_EOL; die;
        }

        echo PHP_EOL."OK : All Files received".PHP_EOL.PHP_EOL;
    }

    function sendFiles() {

        $sftp_dir_in = "ssh2.sftp://".$this->sftp."/IN/";

            foreach(scandir($this->sendpath) as $file) {
                if (strpos($file, "DIMN") !== false) {
                    if ($stream = fopen($sftp_dir_in.$file, 'w')) {
                        if (false !== ($data_to_send = file_get_contents($this->sendpath.$file))) {
                            if (fwrite($stream, $data_to_send) === false) {
                                echo "There was a problem during the WRITING of file '".$this->sendpath.$file."' to '".$sftp_dir_in.$file."'".PHP_EOL;
                            } else {
                                if (rename($this->sendpath.$file, $this->parsepath.$file)) {
                                    echo "$file uploaded and moved".PHP_EOL;
                                } else {
                                    echo "could not move file $file".PHP_EOL;
                                    print_r(error_get_last()); die;
                                }
                            }
                        } else {
                            echo "Could not open local file : '".$this->sendpath.$file."'".PHP_EOL;
                            print_r(error_get_last());
                            die;
                        }
                        fclose($stream);
                    } else {
                        echo "There was a problem during the UPLOAD of file '".$this->sendpath.$file."' to '".$sftp_dir_in.$file."'".PHP_EOL;
                        print_r(error_get_last());
                        die;
                    }
                }
            }

        echo PHP_EOL."All files sent".PHP_EOL.PHP_EOL;

    }

################################################################################################################
#### Parse Dimona Files
################################################################################################################

    function parseFiles($parseList = 'all') {
        $ignoredFiles = array('.', '..', '.DS_Store', '.svn');

        foreach (scandir($this->parsepath) as $file) {
            if (!in_array($file, $ignoredFiles)) {

                $fname = explode(".", $file);
                $fileDebut = $fname[0].".".$fname[1];

                if (($parseList == 'all') || in_array($fileDebut, $parseList)) {
                    switch ($fileDebut) {
                    ### Declarations
                        case"FI.DIMN":
                            $this->parseDIMN($file);
                        break;
                    ### Accuse de reception
                        case"FO.ACRF":
                            $this->parseACRF($file);
                        break;
                    ### Avis DIMONA
                        case"FO.NOTI":
                            $this->parseNOTI($file);
                        break;
                    ### signature files
                        case"FS.ACRF":
                        case"FS.DIMN":
                        case"FS.NOTI":
                            rename($this->parsepath.$file, $this->archivepath.$file);
                            # unlink($this->parsepath.$file);
                        break;

                    ### GO files
                        case"GO.ACRF":
                        case"GO.DIMN":
                        case"GO.NOTI":
                            unlink($this->parsepath.$file);
                        break;

                        default:
                            echo "StrangeFile !!!! : ".$file." I don't know what to do with it !".PHP_EOL;
                    }
                }
            }
        }
    }

    function parseDIMN($file) {

        global $DB;
        $fdata = array();
        $dims = array();
        $i=0;

        echo "------- Declaration : ".PHP_EOL;
        echo "  Parsed File : ".$file.PHP_EOL;

        $xml = simplexml_load_file($this->parsepath.$file);
        $fdata['name'] = substr(strstr($file, 'DIMN'), 0, -2);

        ## Process Declarations
        foreach ($xml->Form as $form) {
            $fdata['datesend'] = $form->FormCreationDate.' '.$form->FormCreationHour;
            ## Dimona IN ##################################
            if (isset($form->DimonaIn)) {
                $dims[$i] = array(
                    'typedecl'      => 'entree',
                    'rna'           => $form->DimonaIn->NaturalPerson->INSS,
                    'datein'        => $form->DimonaIn->StartingDate,
                    'dateout'       => $form->DimonaIn->EndingDate,
                    'numdimona'     => '',
                );
                $i++;
            }
            ## Dimona CANCEL ##############################
            elseif (isset($form->DimonaCancel)) {
                $prevdim = $DB->getRow("SELECT * FROM dimona.declarations WHERE numaccuse = '".$form->DimonaCancel->DimonaPeriodId."'");

                $dims[$i] = array(
                    'typedecl'      => 'annulation',
                    'rna'           => $form->DimonaCancel->INSS,
                    'datein'        => $prevdim['datein'],
                    'dateout'       => $prevdim['dateout'],
                    'numdimona'     => $form->DimonaCancel->DimonaPeriodId,
                );
                $i++;
            } else {
                echo "Objet non reconnu : ".PHP_EOL;
                print_r($form);
            }
        }

        ## Store file
        $DB->inline("INSERT INTO dimona.fichiers (`ftype` , `fname` , `datesend` , `version` , `nbrenregistrements` , `nbredecl`)
                VALUES ('declaration',
                    '".$fdata['name']."',
                    '".$fdata['datesend']."',
                    '".substr($this->versionfichier, -3)."',
                    '0',
                    '".count($dims)."');");

        $idfile = $DB->addid;

        ## Store Decl
        $sql = "INSERT INTO dimona.declarations (`idfile` , `typedecl` , `rna` , `datein` , `dateout` , `numdimona`) VALUES ";

        foreach ($dims as $dat) $sql .= "('".$idfile."' , '".$dat['typedecl']."' , '".$dat['rna']."' , '".$dat['datein']."' , '".$dat['dateout']."', '".$dat['numdimona']."'),";

        $DB->inline(substr($sql, 0, -1));

        if (count($dims) > 0) rename($this->parsepath.$file, $this->archivepath.$file);
    }

    function parseACRF($file) {
        global $DB;

        $xml = simplexml_load_file($this->parsepath.$file);
        $form = $xml->Form;
        $fname = strstr($form->FileReference->FileName, 'DIMN');

        echo "------- Accusé : ".PHP_EOL;
        echo "  Parsed File : ".$fname.PHP_EOL;
        if (
                ($form->Identification == 'ACRF001') &&                 # Accusé
                ($form->TypeForm == 'FA') &&                            # Final Answer
                ($form->FileReference->ReferenceOrigin == 2) &&         # Envoi par la SecuSociale
                ($form->ReceptionResult->ResultCode == 1)               # Fichier accepté
            ) {
            echo "  Status : Accepté".PHP_EOL;

            # Update status of file
            $DB->inline("UPDATE dimona.fichiers SET
                            valid = 'Y',
                            dateprocessed = '".$form->FormCreationDate." ".$form->FormCreationHour."',
                            fnum = '".$form->FileReference->ReferenceNbr."'
                        WHERE fname = '".$fname."'");
        } else {
            echo "  Status : Refusé ".PHP_EOL;
            echo "  Erreur : ".$form->ReceptionResult->ErrorID.PHP_EOL;
        }

        rename($this->parsepath.$file, $this->archivepath.$file);
    }

    function parseNOTI($file) {

        global $DB;

        $parsed = array(
            'OK annulations'  => 0,
            'OK declarations' => 0,
            'ERR duplic'      => 0,
            'ERR range'       => 0,
            'ERR unknown'     => 0,
        );

        echo "------- Notification : ".PHP_EOL;
        echo "  Parsed File : ".$file.PHP_EOL;

        $xml = simplexml_load_file($this->parsepath.$file);

        foreach ($xml->Form as $form) {
            $idfile = $DB->getOne("SELECT idfile FROM dimona.fichiers WHERE fname = '".strstr($form->FileReference->FileName, 'DIMN')."' AND fnum = '".$form->FileReference->ReferenceNbr."'");

            if ($form->Identification == 'NOTI001') {
                ## Success
                if ($form->HandlingResult->ResultCode == 1) {
                    ## Annulation
                    if (isset($form->HandlingResult->ImpactReport->DimonaImpactReport->DimonaPeriodBefore)) {
                        $typedecl  = 'annulation';
                        $datein    = $form->HandlingResult->ImpactReport->DimonaImpactReport->DimonaPeriodBefore->StartingDate;
                        $dateout   = $form->HandlingResult->ImpactReport->DimonaImpactReport->DimonaPeriodBefore->EndingDate;
                        $numdimona = $form->HandlingResult->ImpactReport->DimonaImpactReport->DimonaPeriodId;
                        $status    = 'ok';
                        $notes     = '';
                        $parsed['OK annulations']++;
                    }
                    ## Declaration
                    elseif (isset($form->HandlingResult->ImpactReport->DimonaImpactReport->DimonaPeriodAfter)) {
                        $typedecl  = 'entree';
                        $datein    = $form->HandlingResult->ImpactReport->DimonaImpactReport->DimonaPeriodAfter->StartingDate;
                        $dateout   = $form->HandlingResult->ImpactReport->DimonaImpactReport->DimonaPeriodAfter->EndingDate;
                        $numdimona = '';
                        $status    = 'ok';
                        $notes     = '';
                        $parsed['OK declarations']++;
                    } else {
                        echo "Notification form non reconnu".PHP_EOL;
                        print_r($form);
                    }
                }
                ## Decl Failed
                elseif ($form->HandlingResult->ResultCode == 0) {
                    switch ($form->HandlingResult->AnomalyReport->ErrorID) {
                        ## Periode deja declarée
                        case '90373-333':
                            $typedecl  = 'entree';
                            $datein    = $form->HandlingResult->AnomalyReport->AnomalyComplInformation->DimonaPeriod->StartingDate;
                            $dateout   = $form->HandlingResult->AnomalyReport->AnomalyComplInformation->DimonaPeriod->EndingDate;
                            $numdimona = $form->HandlingResult->AnomalyReport->AnomalyComplInformation->DimonaPeriod->DimonaPeriodId;
                            $status    = 'error';
                            $notes     = 'Declaration en double | dimona initiale introduite le '.$form->HandlingResult->AnomalyReport->AnomalyComplInformation->DimonaPeriod->LastUpdateDate.' a '.$form->HandlingResult->AnomalyReport->AnomalyComplInformation->DimonaPeriod->LastUpdateHour ;
                            $parsed['ERR duplic']++;

                            break;

                        ## Période Dimona en chevauchement
                        case '90373-334':
                            $typedecl = 'entree';
                            $datein = $form->HandlingResult->AnomalyReport->AnomalyComplInformation->DimonaPeriod->StartingDate;
                            $dateout = isset($form->HandlingResult->AnomalyReport->AnomalyComplInformation->DimonaPeriod->EndingDate)
                                            ?$form->HandlingResult->AnomalyReport->AnomalyComplInformation->DimonaPeriod->EndingDate
                                            :$form->FormCreationDate;
                            $numdimona = $form->HandlingResult->AnomalyReport->AnomalyComplInformation->DimonaPeriod->DimonaPeriodId;
                            $status = 'error';
                            $notes = 'Période déjà couverte | dimona initiale introduite le '.$form->HandlingResult->AnomalyReport->AnomalyComplInformation->DimonaPeriod->LastUpdateDate.' a '.$form->HandlingResult->AnomalyReport->AnomalyComplInformation->DimonaPeriod->LastUpdateHour ;
                            $parsed['ERR range']++;
                            break;

                        default:
                            echo " Declaration failed : ".PHP_EOL;
                            print_r($form);
                            $parsed['ERR unknown']++;
                            break;
                    }
                } else {
                    ## not valid form
                    echo " Declaration failed result other than 0 or 1 : ".PHP_EOL;
                    print_r($form);
                }

                # Store Mod
                if (!empty($typedecl) and !empty($datein) and !empty($dateout) ) {
                    if (strtotime($dateout) == strtotime($datein)) {
                        $datesql = "datein = '".$datein."' AND dateout = '".$dateout."' AND";
                    } elseif (strtotime($dateout) > strtotime($datein)) {
                        $datesql = "datein >= '".$datein."' AND dateout <= '".$dateout."' AND";
                        echo 'Chevauchement pour '.$form->NaturalPerson->INSS.' : du '.$datein.' au '.$dateout.' ('.$numdimona.')'.PHP_EOL;
                    }

                    $sql = "UPDATE dimona.declarations SET
                            numaccuse = '".$form->HandledReference->ReferenceNbr."',
                            numdimona = '".$numdimona."',
                            status = '".$status."',
                            notes = '".$notes."'
                        WHERE
                            rna = '".$form->NaturalPerson->INSS."' AND
                            ".$datesql."
                            typedecl = '".$typedecl."' AND
                            idfile = '".$idfile."'";

                    $DB->inline($sql);
                } else {
                    echo "Acune date detectée";
                }
                unset($typedecl);

            } else {
                echo " Unrecognized form".PHP_EOL;
                print_r($form);
            }
        }

        foreach ($parsed as $type => $count) echo '  - '.$type.' : '.$count.PHP_EOL;

        rename($this->parsepath.$file, $this->archivepath.$file);
    }

}

?>