<?php
include NIVO.'classes/test.php';
include NIVO.'webpeople/varfr.php';

################### Code PHP ########################
if (!empty($idwebpeople)) {

    $DB->inline("UPDATE webneuro.webpeople SET `webetat` = 5 WHERE `idwebpeople` ='".$idwebpeople."' LIMIT 1;");
    $infosweb = $DB->getRow("SELECT * FROM webneuro.webpeople WHERE `idwebpeople` = ".$idwebpeople);
    $idwebpeople = $infosweb['idwebpeople'];

}

?>
<form action="<?php echo $_SERVER['PHP_SELF'].'?act=webnewmodif';?>" method="post">
    <input type="hidden" name="idwebpeople" value="<?php echo $idwebpeople;?>">
<div id="leftmenu">
    <div id="idsquare">
        <table border="0" cellspacing="1" cellpadding="2" align="center" width="100%">
            <tr><td align="center">Modif web: <br><?php echo fdatetime($infosweb['datemodif']); ?></td></tr>
        </table>
    </div>
</div>
<div id="infozone">
<table border="0" cellspacing="1" cellpadding="1" align="center" width="98%">
  <tr>
    <?php
    $photoweb  = GetPhotoPath($idwebpeople, 'web', 'path');
    $photoweb2 = GetPhotoPath($idwebpeople, 'web', 'path', '-b');
    if (file_exists($photoweb) or file_exists($photoweb2)) {?>
    <td align="center" width="50%">
    <?php
      if (file_exists($photoweb)) {
      echo 'photo 1<br>';
      echo '<img src="'.NIVO.'data/people/photo.php?id='.$idwebpeople.'&rep=web" alt=""><br><br>';
      echo '<input type="checkbox" name="image" checked value="oui">';
    }
    ?>
    </td>
    <td align="center" width="50%">
    <?php
      if (file_exists($photoweb2)) {
      echo 'photo 2<br>';
      echo '<img src="'.NIVO.'data/people/photo.php?id='.$idwebpeople.'&rep=web&sfx=-b" alt=""><br><br>';
      echo '<input type="checkbox" name="image2" checked value="oui">';
    }
    ?>
    </td>
    <?php
    } else {
      echo '<td colspan="2" width="100%">';
      echo '<br><br><div class="infosection2">Pas de photos transmises !<br><br></div>';
      echo '</td>';
    }
    ?>
    <tr class="standard">
    <td colspan="2">
      <hr color="white" size="1">
    </td>
    </tr>
    <tr class="standard">
    <td>
      <div class="infosection">
      Infos G&eacute;n&eacute;rales
      </div>
    </td>
    <td></td>
    </tr>
                    <tr class="standard">
                        <td>
                            Sexe
                        </td>
                        <td>
                            <?php
                            $sexe = array(
                                'f' => 'F',
                                'm' => 'M'
                                );
                            echo createRadioList('sexe',$sexe,$infosweb['sexe']);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Nom
                        </td>
                        <td>
                            <input type="text" size="33" name="pnom" value="<?php echo $infosweb['pnom']; ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Pr&eacute;nom
                        </td>
                        <td>
                            <input type="text" size="33" name="pprenom" value="<?php echo $infosweb['pprenom']; ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td colspan="2">
                            <hr color="white" size="1">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Adresse 1
                        </td>
                        <td>
                            <input type="text" size="35" name="adresse1" value="<?php echo $infosweb['adresse1']; ?>">
                             &nbsp; num : &nbsp; <input type="text" size="5" name="num1" value="<?php echo $infosweb['num1']; ?>">
                             &nbsp; bte : &nbsp; <input type="text" size="2" name="bte1" value="<?php echo $infosweb['bte1']; ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Code postal &nbsp; Ville
                        </td>
                        <td>
                <input class="required-zipcode" id="zipcode" name="zipcode" type="text" onChange="zipCodeChanged(this,'');" size="6" maxlength="10" value="<?php echo $infosweb['cp1']?>"/>
                <span id="ajaxlist"><input class="required" name="city" type="text" id="city" size="12" value="<?php echo $infosweb['ville1']?>"/>
                <img src="<?php echo STATIK ?>illus/snake_transparent.gif" style="display:none;" id="citySpinner"></span>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Pays
                        </td>
                        <td>
                            <?php
                            $payseu = array(
                                'BE' => 'Belgique',
                                'FR' => 'France',
                                'LU' => 'Luxembourg'
                                );
                            echo createSelectList('pays1',$payseu,$infosweb['pays1'],'--');?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td colspan="2">
                            <hr color="white" size="1">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Adresse 2
                        </td>
                        <td>
                            <input type="text" size="35" name="adresse2" value="<?php echo $infosweb['adresse2']; ?>">
                             &nbsp; num : &nbsp; <input type="text" size="5" name="num2" value="<?php echo $infosweb['num2']; ?>">
                             &nbsp; bte : &nbsp; <input type="text" size="2" name="bte2" value="<?php echo $infosweb['bte2']; ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Code postal &nbsp; Ville
                        </td>
                        <td>
                    <input class="required-zipcode" id="zipcode2" name="zipcode2" type="text" onChange="zipCodeChanged(this,'2');" size="6" maxlength="10" value="<?php echo $infosweb['cp2']?>"/>
                    <span id="ajaxlist2"><input class="required" name="city2" type="text" id="city2" size="12" value="<?php echo $infosweb['ville2']?>"/>
                    <img src="<?php echo STATIK ?>illus/snake_transparent.gif" style="display:none;" id="citySpinner2"></span>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Pays
                        </td>
                        <td>
                            <?php echo createSelectList('pays2',$payseu,$infosweb['pays2'],'--');?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td colspan="2">
                            <hr color="white" size="1">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Province
                        </td>
                        <td>
                        <?php
                        $provinces = array(
                            'Bruxelles'      => 'Bruxelles',
                            'Antwerpen'      => 'Antwerpen',
                            'Oost-Vlanderen' => 'Oost-Vlanderen',
                            'West-Vlanderen' => 'West-Vlanderen',
                            'Vlaams Brabant' => 'Vlaams Brabant',
                            'Brabant Wallon' => 'Brabant Wallon',
                            'Namur'          => 'Namur',
                            'Hainaut'        => 'Hainaut',
                            'Liege'          => 'Li&egrave;ge',
                            'Limburg'        => 'Limburg',
                            'Luxembourg'     => 'Luxembourg',
                            'GDL'            => 'GDL'
                        );
                        echo createSelectList('province',$provinces,$infosweb['province'],'--');
                        ?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td colspan="2">
                            <hr color="white" size="1">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            <div class="infosection">
                                Team
                            </div>
                        </td>
                        <td></td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Je souhaite travailler en
                        </td>
                        <td>
                            <?php
                            $categories = array(
                            '0' => 'Anim',
                            '1' => 'Merch',
                            '2' => 'Hotes'
                            );
                            echo createNumericCheckboxList('categorie',$categories,$infosweb['categorie']);
                            ?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td colspan="2">
                            <hr color="white" size="1">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            <div class="infosection">
                                Contact
                            </div>
                        </td>
                        <td></td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Tel
                        </td>
                        <td>
                            <input type="text" size="33" name="tel" value="<?php echo $infosweb['tel']; ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Fax
                        </td>
                        <td>
                            <input type="text" size="33" name="fax" value="<?php echo $infosweb['fax']; ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            GSM
                        </td>
                        <td>
                            <input type="text" size="33" name="gsm" value="<?php echo $infosweb['gsm']; ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Email
                        </td>
                        <td>
                            <input type="text" size="33" name="email" value="<?php echo $infosweb['email']; ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Web password
                        </td>
                        <td>
                            <input type="text" size="33" name="webpass" value="<?php echo $infosweb['webpass']; ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Langue Bureau : <?php echo $infosweb['lbureau']; ?>
                        </td>
                        <td>
                            <?php
                            echo '<input type="radio" name="lbureau" value="FR" '; if (strtoupper($infosweb['lbureau']) == 'FR') { echo 'checked';} echo'> FR';
                            echo '<input type="radio" name="lbureau" value="NL" '; if (strtoupper($infosweb['lbureau']) == 'NL') { echo 'checked';} echo'> NL';
                            ?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td colspan="2">
                            <hr color="white" size="1">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            <div class="infosection">
                                Hotesses
                            </div>
                        </td>
                        <td></td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Physionomie
                        </td>
                    <td>
<?php
$physios = array(
    'occidental'    => 'Occidental',
    'slave'         => 'Slave',
    'asiatique'     => 'Asiatique',
    'orientale'     => 'Orientale',
    'black'         => 'Black',
    'nordafricain'  => 'Nord-africain',
    'hispanique'    => 'Hispanique',
    'mediterraneen' => 'M&eacute;dit&eacute;rran&eacute;en'
);
echo createSelectList('physio',$physios,$infosweb['physio'],'--');
?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Couleur des cheveux
                        </td>
                        <td>
                            <?php
                            $ccheveux = array(
                                'blond'   => 'blond',
                                'brun'    => 'brun',
                                'noir'    => 'noir',
                                'chatain' => 'chatain',
                                'roux'    => 'roux'
                            );
                            echo createSelectList('ccheveux',$ccheveux,$infosweb['ccheveux'],'--');
                            ?>
                        </td>
                    </tr>

                    <tr class="standard">
                        <td>
                            Longueur cheveux
                        </td>
                        <td>
                            <?php
                            $lcheveux = array(
                                'long'    => 'long',
                                'mi-long' => 'mi-long',
                                'court'   => 'court',
                                'rase'    => 'ras&eacute;'
                            );
                            echo createSelectList('lcheveux',$lcheveux,$infosweb['lcheveux'],'--');
                            ?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Taille
                        </td>
                        <td>
                            <input type="text" size="3" name="taille" value="<?php echo $infosweb['taille']; ?>"> cm
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Taille Veste
                        </td>
                        <td>
                    <?php
                    if ($infosweb['sexe'] == 'f') {
                        $tailles = array(
                            '34' => '34',
                            '36' => '36',
                            '38' => '38',
                            '40' => '40',
                            '42' => '42',
                            '44' => '44',
                            '46' => '46',
                            '48' => '48',
                            '50' => '50',
                            '52' => '52',
                            '54' => '54',
                            '56' => '56'
                        );
                    } else {
                        $tailles = array(
                            'S'  => 'S',
                            'M'  => 'M',
                            'L'  => 'L',
                            'X'  => 'X',
                            'XL' => 'XL'
                        );
                    }
                    echo createSelectList('tveste',$tailles,$infos['tveste']);
                    ?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Taille <?php if ($infosweb['sexe'] == 'f') { echo 'Jupe';} else { echo 'Pantalon';} ?>
                        </td>
                        <td>
                    <?php
                    echo createSelectList('tjupe',$tailles,$infos['tjupe']);
                    ?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Pointure
                        </td>
                        <td>
                            <input type="text" size="3" name="pointure" value="<?php echo $infosweb['pointure']; ?>">
                        </td>
                    </tr>
                <?php  if ($infosweb['sexe'] == 'f') { ?>
                    <tr class="standard">
                        <td>
                            poitrine
                        </td>
                        <td>
                            <input type="text" size="5" name="menspoi" value="<?php echo $infosweb['menspoi']; ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            taille
                        </td>
                        <td>
                            <input type="text" size="5" name="menstai" value="<?php echo $infosweb['menstai']; ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            hanches
                        </td>
                        <td>
                            <input type="text" size="5" name="menshan" value="<?php echo $infosweb['menshan']; ?>">
                        </td>
                    </tr>
                <?php } ?>
                    <tr class="standard">
                        <td colspan="2">
                            <hr color="white" size="1">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Permis
                        </td>
                        <td>
                    <?php
                    $affneg = array(
                        'oui' => 'oui',
                        'non' => 'non'
                    );
                    $permis = array(
                        'non' => 'non',
                        'A'   => 'A',
                        'B'   => 'B',
                        'C'   => 'C',
                        'D'   => 'D',
                        'E'   => 'E'
                    );
                    echo createRadioList('permis',$permis,$infosweb['permis']);
                    ?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Voiture
                        </td>
                        <td>
                    <?php
                    echo createRadioList('voiture',$affneg,$infosweb['voiture']);
                    ?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Fume
                        </td>
                        <td>
                    <?php
                    echo createRadioList('fume',$affneg,$infosweb['fume']);
                    ?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Informatique
                        </td>
                        <td>
                    <?php
                    $conninformatiq = array(
                        '0' => 'Word',
                        '1' => 'Excel',
                        '2' => 'Powerpoint',
                        '3' => 'Internet'
                    );
                        echo createNumericCheckboxList('conninformatiq',$conninformatiq,$infosweb['conninformatiq']);
                    ?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td colspan="2">
                            <hr color="white" size="1">
                        </td>
                    </tr>
                            <tr>
                                <td>
                                    FR
                                </td>
                                <td>
                                    <?php
                                    $langues = getFork(0,4);
                                    echo createRadioList('lfr',$langues,$infosweb['lfr']);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    NL
                                </td>
                                <td>
                                <?php echo createRadioList('lnl',$langues,$infosweb['lnl']); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    EN
                                </td>
                                <td>
                                    <?php echo createRadioList('len',$langues,$infosweb['len']); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    DU
                                </td>
                                <td>
                                    <?php echo createRadioList('ldu',$langues,$infosweb['ldu']); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    IT
                                </td>
                                <td>
                                    <?php echo createRadioList('lit',$langues,$infosweb['lit']); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    ES
                                </td>
                                <td>
                                    <?php echo createRadioList('lsp',$langues,$infosweb['lsp']); ?>
                                </td>
                    <tr class="standard">
                        <td colspan="2">
                            <hr color="white" size="1">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            <div class="infosection">
                                S-Social
                            </div>
                        </td>
                        <td></td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Naissance
                        </td>
                        <td>
                            <input type="text" size="33" name="ndate" value="<?php echo fdate($infosweb['ndate']); ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            CP - Localit&eacute;
                        </td>
                        <td>
                    <input id="zipcode3" name="zipcode3" type="text" onChange="zipCodeChanged(this,'3');" size="6" maxlength="10" value="<?php echo $infosweb['ncp']?>"/>
                    <span id="ajaxlist3"><input name="city3" type="text" id="city3" size="12" value="<?php echo $infosweb['nville']?>"/>
                    <img src="<?php echo STATIK ?>illus/snake_transparent.gif" style="display:none;" id="citySpinner3"></span>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Pays de Naissance
                        </td>
                        <td>
                    <?php
                    echo createSelectList('npays',$iso_pays_fr,$infosweb['npays'],'--');
                    ?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td colspan="2">
                            <hr color="white" size="1">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            N&deg; Carte d&rsquo;identit&eacute;
                        </td>
                        <td>
                            <input type="text" size="33" name="ncidentite" value="<?php echo $infosweb['ncidentite']; ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            N&deg; Reg national
                        </td>
                        <td>
                            <input type="text" size="33" name="nrnational" value="<?php echo $infosweb['nrnational']; ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Nationalit&eacute;
                        </td>
                        <td>
                    <?php
                    asort ($natios);
                    echo createSelectList('nationalite',$natios,$infosweb['nationalite'],'--');
                    ?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td colspan="2">
                            <hr color="white" size="1">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Etat civil
                        </td>
                        <td>
                        <?php
                        echo '
                        <select name="etatcivil" id="etatcivil" onChange="showSelectDiv(this.value);">
                            <option value="">--</option>
                            <option value="1"
                        ';
                            if (($infosweb['etatcivil'] == '') or ($infosweb['etatcivil'] == '1')) {echo 'selected';}
                        echo '
                            >C&eacute;libataire</option>
                            <option value="2"
                        ';
                            if ($infosweb['etatcivil'] == '2') {echo 'selected';}
                        echo '
                            >Mari&eacute;</option>
                            <option value="3"
                        ';
                            if ($infosweb['etatcivil'] == '3') {echo 'selected';}
                        echo '
                            >Veuf</option>
                            <option value="4"
                        ';
                            if ($infosweb['etatcivil'] == '4') {echo 'selected';}
                        echo '
                            >Divorc&eacute;</option>
                            <option value="5"
                        ';
                            if ($infosweb['etatcivil'] == '5') {echo 'selected';}
                        echo '
                            >S&eacute;par&eacute</option>
                        </select>
                        ';
                        ?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td> Date Mariage </td>
                        <td>
                            <input type="text" size="33" name="datemariage" value="<?php echo fdate($infosweb['datemariage']); ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td> Nom Conjoint </td>
                        <td>
                            <input type="text" size="33" name="nomconjoint" value="<?php echo $infosweb['nomconjoint']; ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td> Date Naissance Conj. </td>
                        <td>
                            <input type="text" size="33" name="dateconjoint" value="<?php echo fdate($infosweb['dateconjoint']); ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td> Job Conj. </td>
                        <td>
<?php
                $jobconjoint_list = array(
                    '0' => 'Inconnue',
                    '1' => 'Ouvrier',
                    '2' => 'Gens De Maison',
                    '3' => 'Employe',
                    '4' => 'Independant',
                    '5' => 'Ouvrier Des Mines',
                    '6' => 'Marin',
                    '7' => 'Travailleur Des Services Publics',
                    '8' => 'Autre',
                    '9' => 'Sans',
                );

                echo '<select name="jobconjoint">';

                foreach ($jobconjoint_list as $key => $value) {
                    echo '<option value="'.$key.'"'.((($infosweb['jobconjoint'] == $key) || (empty($infosweb['jobconjoint']) && ($key == '0'))) ? 'selected':'').'>'.$value.'</option>';
                }

                echo '</select>';
?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td> Pers &agrave; Ch. </td>
                        <td>
                            <input type="text" size="3" name="pacharge" value="<?php echo $infosweb['pacharge']; ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td> Enf &agrave; Ch. </td>
                        <td>
                            <input type="text" size="3" name="eacharge" value="<?php echo $infosweb['eacharge']; ?>">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td colspan="2">
                            <hr color="white" size="1">
                        </td>
                    </tr>
                    <tr class="standard">
                        <td> Compte Bancaire </td>
                        <td> <input type="text" size="33" name="banque" value="<?php echo $infosweb['banque']; ?>"> </td>
                    </tr>
                    <tr class="standard">
                        <td colspan="2"> <hr color="white" size="1"> </td>
                    </tr>
                    <tr class="standard">
                        <td> Accepte le r&egrave;glement de travail </td>
                        <td>
                            <input type="checkbox" size="33" name="conditions_accepted" value="yes" <?php echo ($infosweb['conditions_accepted'] == 'yes')?' checked':''; ?>>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td colspan="2"> <hr color="white" size="1"></td>
                    </tr>
                    <tr class="standard">
                        <td>
                            Notes
                        </td>
                        <td>
                            <?php echo $infos['notegenerale']; ?>
                            <?php echo '<br>'; ?>
                            <?php echo $infosweb['notegenerale']; ?>
                        </td>
                    </tr>
                    <tr class="standard">
                        <td colspan="2">
                            <hr color="white" size="1">
                        </td>
                    </tr>
    </table>
    <br><br>
    <?php if (isset($idwebpeople)) { ?>
    <table border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
        <tr>
            <td align="right" width="50%">
                <input type="submit" name="Modifier" value="Modifier" accesskey="M">
            </td>
            <td align="right">
                <input type="submit" name="Injecter" value="Modifier et Injecter" accesskey="M">
            </td>
        </tr>
    </table>
    <?php } ?>
</form>
<?php if (isset($idwebpeople)) { ?>
<form action="<?php echo $_SERVER['PHP_SELF'].'?act=webnewdelete';?>" method="post" onClick="return confirm('Etes-vous certain de vouloir effacer ce jobiste?')">
    <input type="hidden" name="idwebpeople" value="<?php echo $idwebpeople;?>">
    <table border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
        <tr>
            <td align="center" bgcolor="#FF6600">
                <input type="submit" name="Modifier" value="Supprimer" accesskey="M">
            </td>
            <td align="right" width="75%">
            </td>
        </tr>
    </table>
</form>
<?php } ?>

</div>
