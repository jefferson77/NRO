<?php
## init
$s = (empty($s))?0:$s;

## Get infos
$infos = $DB->getRow("SELECT * FROM webneuro.webpeople WHERE `idwebpeople` = ".$_SESSION['idwebpeople']);
?>
<div class="news">
    <table border="0" cellspacing="1" cellpadding="1" align="center" width="98%">
    <?php
        echo '<tr><td'.(($s == 0)?' bgcolor="#437BAD"':'').'>1. '.$detail_01.'</td></tr>';
        echo '<tr><td'.(($s == 1)?' bgcolor="#437BAD"':'').'>2. '.$detail_02.'</td></tr>';
        echo '<tr><td'.(($s == 2)?' bgcolor="#437BAD"':'').'>3. '.$detail_03.'</td></tr>';
        echo '<tr><td'.(($s == 3)?' bgcolor="#437BAD"':'').'>4. '.$detail_04.'</td></tr>';
        echo '<tr><td'.(($s == 4)?' bgcolor="#437BAD"':'').'>5. '.$detail_05.'</td></tr>';
        echo '<tr><td'.(($s == 5)?' bgcolor="#437BAD"':'').'>6. '.$detail_06.'</td></tr>';
    ?>
    </table>
    <br>
<?php if ($s == 4) { ?>
    <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
        <tr>
            <td class="fulltitre" colspan="2"><?php echo $tool_01; ?></td>
        </tr>
        <tr>
            <td class="newstit"><?php echo $detail_05; ?></td>
        </tr>
        <tr>
            <td class="newstxt">
                <br>
                <?php echo $detail_4_01; ?>
            </td>
        </tr>
    </table>
<?php } ?>
</div>
<div class="corps">
<?php
### voir si fiche n'est pas VISUALISEE en management Exception
if ($infos['webetat'] < 4) {
    $validez = $tool_02; # pour le bouton du formulaire

    ####PAGE PHOTO
    if ($s == '4') {
        include("perso/photoupload.php");
    }
        ?>
        <form action="?act=modif<?php echo $s; ?>" method="post" name="people_detail" onsubmit="return false;">
            <input type="hidden" name="idwebpeople" value="<?php echo $infos['idwebpeople'];?>">
            <input type="hidden" name="s" value="<?php echo $s;?>">
            <table class="standard" border="0" cellspacing="2" cellpadding="2" align="center" width="98%">
            <?php if ($s == '0') {
                $validez = $tool_03; # pour le bouton du formulaire
            ?>
                    <td align="left">
                    <fieldset>
                    <legend><?php echo $detail_01; ?></legend>
                        <table class="standard" border="0" cellspacing="2" cellpadding="2" align="center" width="98%">
                            <tr>
                            <td>
                                <br>
                                <b><?php echo $menu_new_03; ?> !</b>
                                <br><br><br>
                                <?php if ($_SESSION['webtype'] == 0) { echo $detail_0_01; } else { echo $detail_0_02; } ?>
                                <br>
                                <?php echo $detail_0_03; ?>
                            </td>
                        </tr>
                    </table>
                    </fieldset>
                    <td>
            <?php } ?>

            <?php if ($s == '1') { ?>

                            <tr>
                                <td class="etiq"><?php echo $detail_1_01; ?></td>
                                <td class="contenu" colspan="3">
                                    <?php
                                    echo '<input type="radio" name="sexe" value="f" '; if (($infos['sexe'] == 'f') OR ($infos['sexe'] == '')) { echo 'checked';} echo'> F';
                                    echo '<input type="radio" name="sexe" value="m" '; if ($infos['sexe'] == 'm') { echo 'checked';} echo'> M';
                                    ?>
                                </td>

                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_1_02; ?></td>
                                <td colspan="3" class="contenu">
                                    <input type="text" size="20" name="pnom" value="<?php echo $infos['pnom']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_1_03; ?></td>
                                <td colspan="3" class="contenu">
                                    <input type="text" size="20" name="pprenom" value="<?php echo $infos['pprenom']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td rowspan="2" class="etiq"><?php echo $detail_1_04; ?></td>
                                <td colspan="3" class="contenu">
                                    <input type="text" size="40" name="adresse1" value="<?php echo $infos['adresse1']; ?>">
                                 &nbsp;  N&deg; <input type="text" size="5" name="num1" value="<?php echo $infos['num1']; ?>"> <?php echo $detail_1_05; ?> <input type="text" size="5" name="bte1" value="<?php echo $infos['bte1']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="contenu">
                                    <?php echo $detail_1_06; ?>
                                    <input type="text" size="10" name="cp1" value="<?php echo $infos['cp1']; ?>">
                                    <?php echo $detail_1_07; ?>
                                    <?php
                                        if (($infos['cp1'] != '') and (is_numeric($infos['cp1'])) and ($infos['pays1'] == 'BE')) {
                                            $cpbcode1 = $infos['cp1'];
                                            $detailcp2 = new db('', '', 'neuro');
                                            $detailcp2->inline("SELECT * FROM `codepost` WHERE `cpbcode`=$cpbcode1");
                                            echo '<select name="ville1">';
                                                while ($row = mysql_fetch_array($detailcp2->result))
                                                {
                                                    if ($infos['ville1'] == $row['cpblocalite']) {$select = ' selected';} else {$select = '';}
                                                    echo '<option value="'.$row['cpblocalite'].'"'.$select.'>'.$row['cpblocalite'].'</option>';
                                                }
                                                if ($infos['ville1'] != '')
                                                {
                                                echo '<option value="'.$infos['ville1'].'">'.$infos['ville1'].'</option>';

                                                }
                                            echo '</select>';

                                        } else { ?>
                                            <input type="text" size="10" name="ville1" value="<?php echo $infos['ville1']; ?>">
                                        <?php }
                                    ?>
                                         &nbsp;
                                         <?php echo $detail_1_08; ?>
                                            <?php
                                                echo '
                                                <select name="pays1" size="1">
                                                    <option value="BE"';    if (($infos['pays1'] == 'BE') OR ($infos['pays1'] == '')) {echo 'selected';}echo '>Belgique</option>
                                                    <option value="FR"';    if ($infos['pays1'] == 'FR') {echo 'selected';}echo '>France</option>
                                                    <option value="LU"';    if ($infos['pays1'] == 'LU') {echo 'selected';}echo '>Luxembourg</option>
                                                </select>';?>
                                </td>
                            </tr>
                            <tr>
                                <td rowspan="2" class="etiq">
                                     <?php echo $detail_1_04; ?> 2
                                </td>
                                <td colspan="3" class="contenu">
                                    <input type="text" size="40" name="adresse2" value="<?php echo $infos['adresse2']; ?>">
                                    &nbsp; N&deg; <input type="text" size="5" name="num2" value="<?php echo $infos['num2']; ?>">  <?php echo $detail_1_05; ?> <input type="text" size="5" name="bte2" value="<?php echo $infos['bte2']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="contenu">
                                     <?php echo $detail_1_06; ?> <input type="text" size="10" name="cp2" value="<?php echo $infos['cp2']; ?>">
                                     <?php echo $detail_1_07; ?>
                                    <?php
                                        if (($infos['cp2'] != '') and (is_numeric($infos['cp2'])) and ($infos['pays2'] == 'BE')) {
                                            $cpbcode2 = $infos['cp2'];
                                            $detailcp2 = new db('', '', 'neuro');
                                            $detailcp2->inline("SELECT * FROM `codepost` WHERE 1 AND `cpbcode`=$cpbcode2");
                                            echo '<select name="ville2">';
                                                while ($row = mysql_fetch_array($detailcp2->result))
                                                {
                                                    if ($infos['ville2'] == $row['cpblocalite']) {$select = ' selected';} else {$select = '';}
                                                    echo '<option value="'.$row['cpblocalite'].'"'.$select.'>'.$row['cpblocalite'].'</option>';
                                                }
                                                if ($infos['ville2'] != '')
                                                {
                                                echo '<option value="'.$infos['ville2'].'">'.$infos['ville2'].'</option>';

                                                }

                                            echo '</select>';
                                        } else { ?>
                                            <input type="text" size="10" name="ville2" value="<?php echo $infos['ville2']; ?>">
                                        <?php } ?>
                                    &nbsp;
                                         <?php
                                            echo $detail_1_08;
                                            echo '<select name="pays2" size="1">
                                                    <option value="BE"'.((($infos['pays2'] == 'BE') OR ($infos['pays2'] == ''))?'selected':'').'>Belgique</option>
                                                    <option value="FR"'.(($infos['pays2'] == 'FR')?'selected':'').'>France</option>
                                                    <option value="LU"'.(($infos['pays2'] == 'LU')?'selected':'').'>Luxembourg</option>
                                                </select>';
                                        ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_1_09; ?></td>
                                <td colspan="3" class="contenu"><input type="text" size="20" name="tel" value="<?php echo $infos['tel']; ?>"></td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_1_10; ?></td>
                                <td colspan="3" class="contenu"><input type="text" size="20" name="fax" value="<?php echo $infos['fax']; ?>"></td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_1_11; ?></td>
                                <td colspan="3" class="contenu"><input type="text" size="20" name="gsm" value="<?php echo $infos['gsm']; ?>"></td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_1_12; ?></td>
                                <td colspan="3" class="contenu"><input type="text" size="20" name="email" value="<?php echo $infos['email']; ?>"> (<?php echo $detail_1_13; ?>)</td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_1_14; ?></td>
                                <td colspan="3" class="contenu"><input type="text" size="20" name="webpass" value="<?php echo $infos['webpass']; ?>">  (<?php echo $detail_1_15; ?>)</td>
                            </tr>
            <?php } ?>
            <?php if ($s != '1') { ?>
                <tr>
            <?php } ?>
            <?php if ($s == '2') { ?>
                </tr>
                <tr>
                    <td valign="top">
                    <fieldset>
                        <legend><?php echo $detail_03; ?></legend>
                        <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
                            <tr>
                                <td class="etiq"><?php echo $detail_2_01; ?></td>
                                <td class="contenu">
<?php
if ($_SESSION['lang'] == 'nl') {
    $physios = array(
        'occidental'    => 'Occidentaal',
        'slave'         => 'Oosters',
        'asiatique'     => 'Asiatisch',
        'orientale'     => 'Orientals',
        'black'         => 'Black',
        'nordafricain'  => 'Noord Afrikaan',
        'hispanique'    => 'Spaans/ Latino',
        'mediterraneen' => 'Mediteraan'
    );
} else {
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
}
echo '<select name="physio">';

foreach ($physios as $key => $value) {
    echo '<option value="'.$key.'"';
    if ($infos['physio'] == $key) {echo 'selected';}
    echo '>'.$value.'</option>';
}

echo '</select>';
?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_2_03; ?></td>
                                <td class="contenu">
                                    <?php
                                        echo '<select name="ccheveux" size="1">
                                            <option value="blond"';if ($infos['ccheveux'] == 'blond') {echo 'selected';}echo '>'.$detail_2_04_01.'</option>
                                            <option value="brun"';if ($infos['ccheveux'] == 'brun') {echo 'selected';}echo '>'.$detail_2_04_02.'</option>
                                            <option value="noir"';if ($infos['ccheveux'] == 'noir') {echo 'selected';}echo '>'.$detail_2_04_03.'</option>
                                            <option value="chatain"';if ($infos['ccheveux'] == 'chatain') {echo 'selected';}echo '>'.$detail_2_04_04.'</option>
                                            <option value="roux"';if ($infos['ccheveux'] == 'roux') {echo 'selected';}echo '>'.$detail_2_04_05.'</option>
                                        </select>
                                        ';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_2_05; ?></td>
                                <td class="contenu">
                                    <?php
                                        echo '<select name="lcheveux" size="1">
                                            <option value="long"';if ($infos['lcheveux'] == 'long') {echo 'selected';}echo '>'.$detail_2_06_01.'</option>
                                            <option value="mi-long"';if ($infos['lcheveux'] == 'mi-long') {echo 'selected';}echo '>'.$detail_2_06_02.'</option>
                                            <option value="court"';if ($infos['lcheveux'] == 'court') {echo 'selected';}echo '>'.$detail_2_06_03.'</option>
                                            <option value="rase"';if ($infos['lcheveux'] == 'rase') {echo 'selected';}echo '>'.$detail_2_06_04.'</option>
                                        </select>
                                        ';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_2_07; ?></td>
                                <td class="contenu"><input type="text" size="3" name="taille" value="<?php echo $infos['taille']; ?>"> cm</td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_2_08; ?></td>
                                <td class="contenu">
                                    <?php
                                        echo '<select name="tveste" size="1">
                                            <option value="34"'.(($infos['tveste'] == '34')?'selected':'').'>34</option>
                                            <option value="36"'.(($infos['tveste'] == '36')?'selected':'').'>36</option>
                                            <option value="38"'.(($infos['tveste'] == '38')?'selected':'').'>38</option>
                                            <option value="40"'.(($infos['tveste'] == '40')?'selected':'').'>40</option>
                                            <option value="42"'.(($infos['tveste'] == '42')?'selected':'').'>42</option>
                                            <option value="44"'.(($infos['tveste'] == '44')?'selected':'').'>44</option>
                                            <option value="50"'.(($infos['tveste'] == '50')?'selected':'').'>50</option>
                                            <option value="52"'.(($infos['tveste'] == '52')?'selected':'').'>52</option>
                                            <option value="54"'.(($infos['tveste'] == '54')?'selected':'').'>54</option>
                                            <option value="56"'.(($infos['tveste'] == '56')?'selected':'').'>56</option>
                                            <option value="L" '.(($infos['tveste'] == 'L' )?'selected':'').'>L</option>
                                            <option value="XL"'.(($infos['tveste'] == 'XL')?'selected':'').'>XL</option>';
                                            if ($infos['sexe'] == 'm') { echo '<option value="XL" selected>XL</option>';}
                                        echo '</select>'; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_2_09; ?></td>
                                <td class="contenu">
                                    <?php
                                        echo '<select name="tjupe" size="1">
                                            <option value="34"'.(($infos['tjupe'] == '34')?'selected':'').'>34</option>
                                            <option value="36"'.(($infos['tjupe'] == '36')?'selected':'').'>36</option>
                                            <option value="38"'.(($infos['tjupe'] == '38')?'selected':'').'>38</option>
                                            <option value="40"'.(($infos['tjupe'] == '40')?'selected':'').'>40</option>
                                            <option value="42"'.(($infos['tjupe'] == '42')?'selected':'').'>42</option>
                                            <option value="44"'.(($infos['tjupe'] == '44')?'selected':'').'>44</option>
                                            <option value="50"'.(($infos['tjupe'] == '50')?'selected':'').'>50</option>
                                            <option value="52"'.(($infos['tjupe'] == '52')?'selected':'').'>52</option>
                                            <option value="54"'.(($infos['tjupe'] == '54')?'selected':'').'>54</option>
                                            <option value="56"'.(($infos['tjupe'] == '56')?'selected':'').'>56</option>';
                                            if ($infos['sexe'] == 'm') { echo '<option value="" selected>male</option>';}
                                        echo '</select>'; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_2_10; ?></td>
                                <td class="contenu"><input type="text" size="3" name="pointure" value="<?php echo $infos['pointure']; ?>"></td>
                            </tr>

                            <tr>
                                <td class="etiq"><?php echo $detail_2_14; ?></td>
                                <td class="contenu"><input type="text" size="3" name="menspoi" value="<?php echo $infos['menspoi']; ?>"></td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_2_16; ?></td>
                                <td class="contenu"><input type="text" size="3" name="menstai" value="<?php echo $infos['menstai']; ?>"></td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_2_15; ?></td>
                                <td class="contenu"><input type="text" size="3" name="menshan" value="<?php echo $infos['menshan']; ?>"></td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_2_11; ?></td>
                                <td class="contenu">
                                    <?php
                                    echo '<input type="radio" name="permis" value="non" '; if (($infos['permis'] == 'non') or ($infos['permis'] == '')) { echo 'checked';} echo'> '.$tool_04;
                                    echo '<input type="radio" name="permis" value="A" '; if ($infos['permis'] == 'A') { echo 'checked';} echo'> A';
                                    echo '<input type="radio" name="permis" value="B" '; if ($infos['permis'] == 'B') { echo 'checked';} echo'> B';
                                    echo '<input type="radio" name="permis" value="C" '; if ($infos['permis'] == 'C') { echo 'checked';} echo'> C';
                                    echo '<input type="radio" name="permis" value="D" '; if ($infos['permis'] == 'D') { echo 'checked';} echo'> D';
                                    echo '<input type="radio" name="permis" value="E" '; if ($infos['permis'] == 'E') { echo 'checked';} echo'> E';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq">
                                    <?php echo $detail_2_12; ?>
                                </td>
                                <td class="contenu">
                                    <?php
                                    echo '<input type="radio" name="voiture" value="non" '; if ($infos['voiture'] == 'non') { echo 'checked';} echo'> '.$tool_04;
                                    echo '<input type="radio" name="voiture" value="oui" '; if ($infos['voiture'] == 'oui') { echo 'checked';} echo'> '.$tool_05;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_2_17; ?></td>
                                <td class="contenu">
                                    <?php
                                    echo '<input type="radio" name="fume" value="non" '; if ($infos['fume'] == 'non') { echo 'checked';} echo'> '.$tool_04;
                                    echo '<input type="radio" name="fume" value="oui" '; if ($infos['fume'] == 'oui') { echo 'checked';} echo'> '.$tool_05;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_2_18; ?></td>
                                <td class="contenu">
                        <?php
                        $conninformatiq = array(
                            '0' => 'Word',
                            '1' => 'Excel',
                            '2' => 'Powerpoint',
                            '3' => 'Internet'
                        );
                            echo createNumericCheckboxList('conninformatiq',$conninformatiq,$infos['conninformatiq']);
                        ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <?php if ($_SESSION['webtype'] == 0) { ?>
                        <br>
                    <fieldset> <legend>Team</legend>
                        <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
                            <tr>
                            </tr>
                            <tr>
                                <td class="etiq">
                                    Je souhaite travailler en
                                </td>
                                <td class="contenu">
                            <?php
                            $categorie = array(
                                '0' => 'Animation',
                                '1' => 'Merchandising',
                                '2' => 'VIP'
                            );
                                echo createNumericCheckboxList('categorie',$categorie,$infos['categorie']);
                            ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <br>
                    <br>
                    <fieldset> <legend><?php echo $detail_2_13; ?></legend>
                        <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
                            <tr>
                            </tr>
                            <tr>
                                <td class="etiq">
                                    FR
                                </td>
                                <td class="contenu">
                                    <?php
                                    echo '<input type="radio" name="lfr" value="0" '; if ($infos['lfr'] == '0') { echo 'checked';} echo'> 0';
                                    echo '<input type="radio" name="lfr" value="1" '; if ($infos['lfr'] == '1') { echo 'checked';} echo'> 1';
                                    echo '<input type="radio" name="lfr" value="2" '; if ($infos['lfr'] == '2') { echo 'checked';} echo'> 2';
                                    echo '<input type="radio" name="lfr" value="3" '; if ($infos['lfr'] == '3') { echo 'checked';} echo'> 3';
                                    echo '<input type="radio" name="lfr" value="4" '; if ($infos['lfr'] == '4') { echo 'checked';} echo'> 4';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq">
                                    NL
                                </td>
                                <td class="contenu">
                                    <?php
                                    echo '<input type="radio" name="lnl" value="0" '; if ($infos['lnl'] == '0') { echo 'checked';} echo'> 0';
                                    echo '<input type="radio" name="lnl" value="1" '; if ($infos['lnl'] == '1') { echo 'checked';} echo'> 1';
                                    echo '<input type="radio" name="lnl" value="2" '; if ($infos['lnl'] == '2') { echo 'checked';} echo'> 2';
                                    echo '<input type="radio" name="lnl" value="3" '; if ($infos['lnl'] == '3') { echo 'checked';} echo'> 3';
                                    echo '<input type="radio" name="lnl" value="4" '; if ($infos['lnl'] == '4') { echo 'checked';} echo'> 4';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq">
                                    EN
                                </td>
                                <td class="contenu">
                                    <?php
                                    echo '<input type="radio" name="len" value="0" '; if ($infos['len'] == '0') { echo 'checked';} echo'> 0';
                                    echo '<input type="radio" name="len" value="1" '; if ($infos['len'] == '1') { echo 'checked';} echo'> 1';
                                    echo '<input type="radio" name="len" value="2" '; if ($infos['len'] == '2') { echo 'checked';} echo'> 2';
                                    echo '<input type="radio" name="len" value="3" '; if ($infos['len'] == '3') { echo 'checked';} echo'> 3';
                                    echo '<input type="radio" name="len" value="4" '; if ($infos['len'] == '4') { echo 'checked';} echo'> 4';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq">
                                    DU
                                </td>
                                <td class="contenu">
                                    <?php
                                    echo '<input type="radio" name="ldu" value="0" '; if ($infos['ldu'] == '0') { echo 'checked';} echo'> 0';
                                    echo '<input type="radio" name="ldu" value="1" '; if ($infos['ldu'] == '1') { echo 'checked';} echo'> 1';
                                    echo '<input type="radio" name="ldu" value="2" '; if ($infos['ldu'] == '2') { echo 'checked';} echo'> 2';
                                    echo '<input type="radio" name="ldu" value="3" '; if ($infos['ldu'] == '3') { echo 'checked';} echo'> 3';
                                    echo '<input type="radio" name="ldu" value="4" '; if ($infos['ldu'] == '4') { echo 'checked';} echo'> 4';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq">
                                    IT
                                </td>
                                <td class="contenu">
                                    <?php
                                    echo '<input type="radio" name="lit" value="0" '; if ($infos['lit'] == '0') { echo 'checked';} echo'> 0';
                                    echo '<input type="radio" name="lit" value="1" '; if ($infos['lit'] == '1') { echo 'checked';} echo'> 1';
                                    echo '<input type="radio" name="lit" value="2" '; if ($infos['lit'] == '2') { echo 'checked';} echo'> 2';
                                    echo '<input type="radio" name="lit" value="3" '; if ($infos['lit'] == '3') { echo 'checked';} echo'> 3';
                                    echo '<input type="radio" name="lit" value="4" '; if ($infos['lit'] == '4') { echo 'checked';} echo'> 4';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq">
                                    ES
                                </td>
                                <td class="contenu">
                                    <?php
                                    echo '<input type="radio" name="lsp" value="0" '; if ($infos['lsp'] == '0') { echo 'checked';} echo'> 0';
                                    echo '<input type="radio" name="lsp" value="1" '; if ($infos['lsp'] == '1') { echo 'checked';} echo'> 1';
                                    echo '<input type="radio" name="lsp" value="2" '; if ($infos['lsp'] == '2') { echo 'checked';} echo'> 2';
                                    echo '<input type="radio" name="lsp" value="3" '; if ($infos['lsp'] == '3') { echo 'checked';} echo'> 3';
                                    echo '<input type="radio" name="lsp" value="4" '; if ($infos['lsp'] == '4') { echo 'checked';} echo'> 4';
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    <?php } ?>
                    </td>
            <?php } ?>
            <?php if ($s == '3') { ?>
                    <td valign="top" width="50%">
                        <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
                                <td>
                                <fieldset>
                                <legend><?php echo $detail_3_01; ?></legend>
                                    <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
                            <tr>
                                <td class="etiq"><?php echo $detail_3_02; ?></td>
                                <td class="contenu">
                                    <input type="text" size="20" name="ndate" value="<?php echo fdate($infos['ndate']); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_3_03; ?></td>
                                <td class="contenu">
                                    <input type="text" size="4" name="ncp" value="<?php echo $infos['ncp']; ?>"> <input type="text" size="15" name="nville" value="<?php echo $infos['nville']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_3_04; ?></td>
                                <td class="contenu">
                                <?php

                        include(NIVO."conf/pays.php");

                        $country_list = ($_SESSION['lang'] == 'nl') ? $iso_pays_nl : $iso_pays_fr ;

                        echo '<select name="npays">';

                        foreach ($country_list as $key => $value) {
                            echo '<option value="'.$key.'"';
                            if ($infos['npays'] == $key) {echo 'selected';}
                            if (($infos['npays'] == '') and ($key == 'BE')) {echo 'selected';}
                            echo '>'.$value.'</option>';
                        }

                        echo '</select>';
        ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_3_05; ?></td>
                                <td class="contenu">
<?php
                include(NIVO."conf/nationalites.php");

                        echo '<select name="nationalite">';

                        foreach ($natios as $key => $value) {
                            echo '<option value="'.$key.'"';
                            if ($infos['nationalite'] == $key) {echo 'selected';}
                            if (($infos['nationalite'] == '') and ($key == 'B')) {echo 'selected';}
                            echo '>'.$value.'</option>';
                        }

                        echo '</select>';
        ?>
                                </td>
                                        </tr>
                                    </table>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
                            <tr>
                                <td>
                                <fieldset>
                                    <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
                            <tr>
                                <td class="etiq"><?php echo $detail_3_07; ?></td>
                                <td class="contenu" colspan="3">
                        <?php
                        echo '
                        <select name="etatcivil">
                            <option value="1"'; if (($infos['etatcivil'] == '') or ($infos['etatcivil'] == '1')) {echo 'selected';}echo '>'.$detail_3_08_01.'</option>
                            <option value="2"'; if ($infos['etatcivil'] == '2') {echo 'selected';}echo '>'.$detail_3_08_02.'</option>
                            <option value="3"'; if ($infos['etatcivil'] == '3') {echo 'selected';}echo '>'.$detail_3_08_03.'</option>
                            <option value="4"'; if ($infos['etatcivil'] == '4') {echo 'selected';}echo '>'.$detail_3_08_04.'</option>
                            <option value="5"'; if ($infos['etatcivil'] == '5') {echo 'selected';}echo '>'.$detail_3_08_05.'</option>
                        </select>';?>
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_3_09; ?></td>
                                <td class="contenu" colspan="3"><input type="text" size="20" name="datemariage" value="<?php echo fdate($infos['datemariage']); ?>"></td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_3_10; ?></td>
                                <td class="contenu" colspan="3"><input type="text" size="20" name="nomconjoint" value="<?php echo $infos['nomconjoint']; ?>"></td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_3_11; ?></td>
                                <td class="contenu" colspan="3"><input type="text" size="20" name="dateconjoint" value="<?php echo fdate($infos['dateconjoint']); ?>"></td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_3_12; ?></td>
                                <td class="contenu" colspan="3">
<?php
                $jobconjoint_list = ($_SESSION['lang'] == 'nl') ? array(
                            '0' => 'Onbekend',
                            '1' => 'Arbeider',
                            '2' => 'Huishoudelijk bediende',
                            '3' => 'Bediende',
                            '4' => 'Zelfstandige',
                            '5' => 'Mijnwerker',
                            '6' => 'Matroos',
                            '7' => 'Ambtenaar',
                            '8' => 'Andere',
                            '9' => 'Werkloos',
                        ) : array(
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
                        ) ;


                echo '<select name="jobconjoint">';

                foreach ($jobconjoint_list as $key => $value) {
                    echo '<option value="'.$key.'"'.((($infos['jobconjoint'] == $key) || (empty($infos['jobconjoint']) && ($key == '0'))) ? 'selected':'').'>'.$value.'</option>';
                }

                echo '</select>';
?>
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_3_14; ?></td>
                                <td class="contenu">
                                    <input type="text" size="2" name="pacharge" value="<?php echo $infos['pacharge']; ?>">
                                </td>
                                <td class="etiq"><?php echo $detail_3_15; ?></td>
                                <td class="contenu">
                                    <input type="text" size="2" name="eacharge" value="<?php echo $infos['eacharge']; ?>">
                                </td>
                            </tr>
                                    </table>
                                </fieldset>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <br>&nbsp;
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
                            <tr>
                                <td>
                                <fieldset>
                                <legend><?php echo $detail_3_16; ?></legend>
                                    <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
                            <tr>
                                <td class="etiq"><?php echo $detail_3_17; ?></td>
                                <td class="contenu">
                                    <input type="text" size="20" name="ncidentite" value="<?php echo $infos['ncidentite']; ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="etiq"><?php echo $detail_3_18; ?></td>
                                <td class="contenu">
                                    <input type="text" size="20" name="nrnational" value="<?php echo $infos['nrnational']; ?>">
                                </td>
                                        </tr>
                                    </table>
                                </fieldset>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top">
                        <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
                            <tr>
                                <td>
                                <fieldset>
                                    <legend><?php echo $detail_3_19; ?></legend>
                                    <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
                                        <tr>
                                            <td class="etiq"><?php echo $detail_3_20; ?></td>
                                            <td class="contenu">
                                                <input type="text" size="20" name="banque" value="<?php echo $infos['banque']; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" colspan="2">
                                                <?php echo $detail_3_21; ?>
                                            </td class="contenu">
                                        </tr>
                                    </table>
                                </fieldset>
                                </td>
                            </tr>
                        </table>
                    </td>
<?php }
// Validation
if ($s == '5') {
	$validez = $tool_06; # pour le bouton du formulaire
            ?>
                    <td>
                        <table class="standard" border="0" cellspacing="1" cellpadding="0" align="left" width="95%">
                            <tr>
                                <td>
                                <fieldset>
                                <legend><?php echo $detail_06; ?></legend>
                                    <table class="standard" border="0" cellspacing="2" cellpadding="2" align="left" width="95%">
                                        <tr>
                                            <td>
                                                <br>
                                            <?php if ($_SESSION['webtype'] == 0) { ?>
                                                <?php echo $detail_5_01; ?>
                                            <?php } else { ?>
                                                <?php echo $detail_5_02; ?>
                                            <?php } ?>
                                                <br>
                                                <br>
                                                <?php echo '<input id="ex_conditions_accepted" type="checkbox" name="conditions_accepted" value="yes" '.(($infos['conditions_accepted'] == 'yes')?'checked':'').'> '.$detail_5_04; ?> <a href="../www/people/doc/regl_travail_<?php echo $_SESSION['lang'] ?>.pdf"><?php echo strtolower($menu_15) ?> *</a>
                                                <br><br>
                                                <?php echo $detail_5_03; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                                <td>
                                <td class="vip" colspan="2"></td>
                            </tr>
                        </table>
                    <td>
            <?php } ?>
            <?php if ($s != '1') { ?>
                </tr>
            <?php } ?>
                        </table>
            <br>
            <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
                <tr>
                    <td width="98%" align="center">
                        <input type="submit" name="action" value="<?php echo $validez; ?>" onclick="checkForm();">
                    </td>
                </tr>
        <?php if ($s > 0) { ?>
                <tr>
                    <td align="left">
                        <input type="submit" name="retour" value="<?php echo $tool_07; ?>" onclick="checkForm();">
                    </td>
                </tr>
        <?php } ?>
            </table>
            <br>
        </form>
<?php
### voir si fiche n'est pas VISUALISEE en management Exception
#/## OK
} else {
### PAS OK
?>
            <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
                <tr>
                    <td width="98%" align="left">
                        <br><br>
                        <Fieldset><legend><b><?php echo $menu_03a; ?></b></legend>
                        <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
                            <tr>
                                <td width="98%" align="left">
                                    <br>
                                    <?php echo $menu_03; ?>
                                </td>
                            </tr>
                        </table>
                        </Fieldset>
                    </td>
                </tr>
            </table>

<?php
### voir si fiche n'est pas VISUALISEE en management Exception
}
#/## PAS OK
?>
</div>
<script type="text/javascript" charset="utf-8">
    function checkForm() {
        if(document.getElementById('ex_conditions_accepted') && !document.getElementById('ex_conditions_accepted').checked) {
            alert("<?php echo $detail_5_05 ?>");
        } else {
            document.people_detail.submit();
        }
    }
</script>
