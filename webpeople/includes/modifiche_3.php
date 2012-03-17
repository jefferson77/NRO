<?php
$detail = new db('', '', 'webneuro');
$detail->inline("SELECT * FROM `webpeople` WHERE `idpeople` = $_SESSION[idpeople]");
$infos = mysql_fetch_array($detail->result);

if ($_POST['confirmfiche'] == '1') {

    ####vérification serveur du formulaire envoyé dans modifiche_2####

        $_POST['taille']   = trim($_POST['taille']);
        $_POST['pointure'] = trim($_POST['pointure']);
        $_POST['menspoi']  = trim($_POST['menspoi']);
        $_POST['menstai']  = trim($_POST['menstai']);
        $_POST['menshan']  = trim($_POST['menshan']);

    if (((!empty($_POST['taille'])) && (!is_numeric($_POST['taille']))) || ((!empty($_POST['pointure'])) && (!is_numeric($_POST['pointure'])))) {
        die($emodifiche2_01);
    }

    if ( ($infos['sexe'] == 'F') && (
        ((!empty($_POST['menspoi'])) && (!is_numeric($_POST['menspoi'])))
        || ((!empty($_POST['menstai'])) && (!is_numeric($_POST['menstai'])))
        || ((!empty($_POST['menshan'])) && (!is_numeric($_POST['menshan'])))) ) {
        die($emodifiche2_02);
    }

    if(!empty($_POST['conninformatiq'])) $_POST['conninformatiq'] = (($_POST['conninformatiq'][0]=='1')?'1':'0').(($_POST['conninformatiq'][1]=='1')?'1':'0').(($_POST['conninformatiq'][2]=='1')?'1':'0').(($_POST['conninformatiq'][3]=='1')?'1':'0');

    $sql = new db('', '', 'webneuro');
    $requete = "UPDATE webpeople SET physio='".$_POST['physio']."', ccheveux='".$_POST['ccheveux']."', lcheveux='".$_POST['lcheveux']."', taille='".$_POST['taille']."', tveste='".$_POST['tveste']."', tjupe='".$_POST['tjupe']."', pointure='".$_POST['pointure']."', menspoi='".$_POST['menspoi']."', menstai='".$_POST['menstai']."', menshan='".$_POST['menshan']."', permis='".$_POST['permis']."', voiture='".$_POST['voiture']."', fume='".$_POST['fume']."', conninformatiq='".$_POST['conninformatiq']."' WHERE idpeople='".$infos['idpeople']."'";
    $sql->inline($requete);

}
?>
<p></p>
<div class="formdiv">
<form id="modifiche_3" name="modifiche_3" action="<?php echo $_SERVER['PHP_SELF']?>?page=modifiche&step=4" method="post">
<h4>1. <?php echo $modifiche3_01; ?></h4>
        <table cellspacing="0" class="formulaire">
            <tr class="ligne2">
                <td class="lignename"><?php echo $modifiche3_02 ?></td>
                <td>
<?php
//creation de la select box date de naissance
if ($infos['ndate'] != '0000-00-00'){
    $new_ndate = explode("-", $infos['ndate']);
}

//arrays pour les dropdowns jour et année
$array_D  = getFork(1,31);
$array_M  = getFork(1,12);

$thisyear = date("Y");
$Y_start  = $thisyear -90;
$Y_end    = $thisyear - 14;
$array_Y  = getFork($Y_start,$Y_end);
arsort ($array_Y,SORT_NUMERIC);

echo createSelectList('ndate_D',$array_D,$new_ndate[2],'jj',1,'ndate_D','validate-selection');
echo createSelectList('ndate_M',$array_M,$new_ndate[1],'mm',1,'ndate_M','validate-selection');
echo createSelectList('ndate_Y',$array_Y,$new_ndate[0],'aaaa',1,'ndate_Y','validate-selection');
?>
                </td>
            </tr>
            <tr>
                <td class="lignename"><?php echo $modifiche3_03 ?></td>
                <td>
     <input class="validate-zipcode" id="zipcode" name="zipcode" type="text" onChange="zipCodeChanged(this,'');" size="6" maxlength="10" value="<?php echo $infos['ncp']?>"/>
                  <?php echo $modifiche1_07;?>
                <span id="ajaxlist"><input name="city" type="text" id="city" size="12" value="<?php echo $infos['nville']?>" class="required"/>
                <img src="snake_transparent.gif" style="display:none;" id="citySpinner"></span>
                </td>
            </tr>
            <tr class="ligne2">
                <td class="lignename"><?php echo $modifiche3_04 ?></td>
                <td>
                    <?php
                    echo createSelectList('npays',(( $_SESSION['lang'] == 'nl' ) ? $iso_pays_nl : $iso_pays_fr),$infos['npays'],'--','','npays','validate-selection');
                    ?>
                </td>
            </tr>
            <tr>
                <td class="lignename"><?php echo $modifiche3_05 ?></td>
                <td>
                    <?php
                    //l'array des nationalités est dans les variables de langue
                    asort ($natios);
                    echo createSelectList('nationalite',$natios,$infos['nationalite'],'--','','nationalite','validate-selection');
                    ?>
                </td>
            </tr>
        </table>
<h4>2. <?php echo $modifiche3_06; ?></h4>
        <table cellspacing="0" class="formulaire">
            <tr class="ligne2">
                <td class="lignename"><?php echo $modifiche3_07 ?></td>
                <td>
                        <?php
                        echo '
                        <select name="etatcivil" id="etatcivil" class="validate-selection" onChange="showSelectDiv(this.value);">
                            <option value="">--</option>
                            <option value="1"
                        ';
                            if (($infos['etatcivil'] == '') or ($infos['etatcivil'] == '1')) {echo 'selected';}
                        echo '
                            >'.$modifiche3_07a.'</option>
                            <option value="2"
                        ';
                            if ($infos['etatcivil'] == '2') {echo 'selected';}
                        echo '
                            >'.$modifiche3_07b.'</option>
                            <option value="3"
                        ';
                            if ($infos['etatcivil'] == '3') {echo 'selected';}
                        echo '
                            >'.$modifiche3_07c.'</option>
                            <option value="4"
                        ';
                            if ($infos['etatcivil'] == '4') {echo 'selected';}
                        echo '
                            >'.$modifiche3_07d.'</option>
                            <option value="5"
                        ';
                            if ($infos['etatcivil'] == '5') {echo 'selected';}
                        echo '
                            >'.$modifiche3_07e.'</option>
                        </select>
                        ';
                        ?>
                </td>
            </tr>
        <table id="moreetatcivil" cellspacing="0" class="formulaire" id="moreetatcivil" <?php if ($infos['etatcivil'] !='2') {echo 'style="display:none"';}?>>
            <tr>
                <td class="lignename"><?php echo $modifiche3_08 ?></td>
                <td>
<?php
//creation de la select box date de marriage

if ($infos['datemariage'] != '0000-00-00'){
    $new_datemariage = explode("-", $infos['datemariage']);
}

$Y_start = $thisyear -100;
$array_Y = getFork($Y_start,$thisyear);
arsort ($array_Y,SORT_NUMERIC);
echo createSelectList('datemariage_D',$array_D,$new_datemariage[2],'jj',1,'datemariage_D','validate-selection');
echo createSelectList('datemariage_M',$array_M,$new_datemariage[1],'mm',1,'datemariage_M','validate-selection');
echo createSelectList('datemariage_Y',$array_Y,$new_datemariage[0],'aaaa',1,'datemariage_Y','validate-selection');
?>
                </td>
            </tr>
            <tr class="ligne2">
                <td class="lignename"><?php echo $modifiche3_09 ?></td>
                <td>
                    <input class="required" type="text" size="20" name="nomconjoint" value="<?php echo $infos['nomconjoint']; ?>">
                </td>
            </tr>
            <tr>
                <td class="lignename"><?php echo $modifiche3_10 ?></td>
                <td>
<?php
//creation de la select box date de naissance conjoint

if ($infos['dateconjoint'] != '0000-00-00'){
    $new_dateconjoint = explode("-", $infos['dateconjoint']);
}

$Y_start = $thisyear -100;
$Y_end   = $thisyear -16;
$array_Y = getFork($Y_start,$Y_end);
arsort ($array_Y,SORT_NUMERIC);
echo createSelectList('dateconjoint_D',$array_D,$new_dateconjoint[2],'jj',1,'dateconjoint_D','validate-selection');
echo createSelectList('dateconjoint_M',$array_M,$new_dateconjoint[1],'mm',1,'dateconjoint_M','validate-selection');
echo createSelectList('dateconjoint_Y',$array_Y,$new_dateconjoint[0],'aaaa',1,'dateconjoint_Y','validate-selection');
?>
                </td>
            </tr>
            <tr class="ligne2">
                <td class="lignename"><?php echo $modifiche3_11 ?></td>
                <td>
                    <?php
                    //l'array des états civils
                    asort ($jobconjoint);
                    echo createSelectList('jobconjoint',$jobconjoint,$infos['jobconjoint'],'--','','jobconjoint','validate-selection');
                    ?>
                </td>
            </tr>
            <tr>
                <td class="lignename"><?php echo $modifiche3_12 ?></td>
                <td>
                    <input type="text" size="2" name="pacharge" value="<?php echo $infos['pacharge']; ?>">
                </td>
            </tr>
            <tr class="ligne2">
                <td class="lignename"><?php echo $modifiche3_13 ?></td>
                <td>
                    <input type="text" size="2" name="eacharge" value="<?php echo $infos['eacharge']; ?>">
                </td>
            </tr>
        </table>
        <h4>3. <?php echo $modifiche3_14 ?></h4>
        <table cellspacing="0" class="formulaire">
            <tr class="ligne2">
                <td class="lignename"><?php echo $modifiche3_15 ?></td>
                <td>
                    <input type="text" size="20" name="ncidentite" value="<?php echo $infos['ncidentite']; ?>" class="required-IDcard">
                </td>
            </tr>
            <tr>
                <td class="lignename"><?php echo $modifiche3_16 ?></td>
                <td>
                    <input type="text" size="20" name="nrnational" value="<?php echo $infos['nrnational']; ?>" class="required-regnat">
                </td>
            </tr>
        </table>
        <h4>4. <?php echo $modifiche3_17 ?></h4>
        <table cellspacing="0" class="formulaire">
            <tr class="ligne2">
                <td class="lignename"><?php echo $modifiche3_18 ?></td>
                <td>
                    <input type="text" size="20" name="banque" value="<?php echo $infos['banque']; ?>" class="required-bankaccount"> <img src="<?php echo STATIK ?>illus/bullet_error.png" width="16" height="16" /><?php echo $modifiche3_19;?>
                </td>
            </tr>
        </table>

    <input type="hidden" name="confirmfiche" value="1">
    <div style="text-align:right;padding-right:25px;padding-top:5px">
        <a href="?page=modifiche&step=2"><img src="../../web/illus/formback.png" width="70" height="38"></a>&nbsp;
        <input type="submit" class="btn formok" name="submit">
    </div>
    </form>
</div>
<script type="text/javascript">
    function formCallback(result, form) {
        window.status = "valiation callback for form '" + form.id + "': result = " + result;
    }

    var valid = new Validation('modifiche_3', {immediate : true, onFormValidate : formCallback});
</script>