<?php
if (!empty($_REQUEST['idvipjob'])) {
    $infos = $DB->getRow("SELECT
            j.briefing, j.datein, j.dateout, j.etat, j.facnum, j.forfait, j.idagent, j.idcofficer, j.idshop, j.idvipjob, j.notedeplac, j.notefrais, j.notejob, j.noteloca, j.noteprest, j.reference, j.raisonout, j.noteout,
            a.nom as anom, a.prenom as aprenom,
            o.qualite, o.onom, o.oprenom, o.langue,
            c.codeclient, c.societe as csociete,
            s.adresse, s.codeshop, s.cp, s.societe as ssociete, s.ville
        FROM vipjob j
            LEFT JOIN agent a ON j.idagent = a.idagent
            LEFT JOIN cofficer o ON j.idcofficer = o.idcofficer
            LEFT JOIN client c ON j.idclient = c.idclient
            LEFT JOIN shop s ON j.idshop = s.idshop
        WHERE `idvipjob` = ".$_REQUEST['idvipjob']);

    switch ($infos['etat']) {
        case "0":
        case "99":
            $vipbase = 'vipdevis';
        break;
        case "1":
            $vipbase = 'vipmission';
        break;
        case "2":
            $vipbase = 'vipdelete';
        break;
        case "11":
        default:
            $vipbase = 'vipmission';
        break;
    }

    ## notes de credit
    $ncs   = ($infos['facnum'] > 0) ? $DB->getColumn("SELECT idfac FROM credit WHERE facref = ".$infos['facnum']) : array();
    $dates = $DB->getRow("SELECT MIN(vipdate) as datein, MAX(vipdate) as dateout FROM ".$vipbase." WHERE idvipjob = ".$_REQUEST['idvipjob']);

    if (($infos['datein'] != $dates['datein']) or ($infos['dateout'] != $dates['dateout'])) {
        $DB->inline("UPDATE vipjob SET datein = '".$dates['datein']."', dateout = '".$dates['datein']."' WHERE idvipjob = ".$_REQUEST['idvipjob']);
        $infos['datein'] = $dates['datein'];
        $infos['dateout'] = $dates['dateout'];
    }
}

?>
<div id="leftmenu">
    <table border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
        <tr><th>Code Job</th></tr>
        <tr><td><?php echo $infos['idvipjob']; ?></td></tr>
        <tr><th>Facture</th></tr>
        <tr><td><?php echo $infos['facnum']; ?></td></tr>
        <tr><th>Notes Credit</th></tr>
        <tr><td><?php echo implode("<br>", $ncs); ?></td></tr>
    </table>
</div>
<div id="infozone">
    <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
        <tr>
            <td valign="top">
                <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
                    <tr>
                        <th class="vip" width="90">Assistant</th>
                        <td colspan="5"><?php echo $infos['aprenom'].' '.$infos['anom']; ?></td>
                    </tr>
                    <tr>
                        <th class="space4" colspan="6">&nbsp;</th>
                    </tr>
                    <tr>
                        <th class="vip" width="90">R&eacute;f&eacute;rence</td>
                        <td colspan="5"><?php echo $infos['reference']; ?></td>
                    </tr>
                    <tr>
                        <th class="space4" colspan="6">&nbsp;</th>
                    </tr>
                    <tr>
                        <th class="vip" width="90">Notes Job</td>
                        <td colspan="4"><?php echo $infos['notejob']; ?></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th class="space4">&nbsp;</th>
                        <th class="space4" width="250">&nbsp;</th>
                        <th class="space4">&nbsp;</th>
                        <th class="space4">&nbsp;</th>
                        <th class="space4">&nbsp;</th>
                        <th class="space4">&nbsp;</th>
                    </tr>
                    <tr>
                        <th class="vip"  rowspan="2">Client</th>
                        <td colspan="3"><?php echo $infos['codeclient']; ?> <?php echo $infos['csociete']; ?></td>
                        <td><b>Facturation par Forfait</b></td>
                    </tr>
                    <tr>
                        <td colspan="3"><?php echo $infos['qualite'].' '.$infos['onom'].' '.$infos['oprenom'].' '.$infos['langue']; ?></td>
                        <td>
                            <?php if ($infos['forfait'] == 'Y') echo 'Oui'; ?>
                            <?php if ($infos['forfait'] == 'N') echo 'Non'; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="space4" colspan="6">&nbsp;</th>
                    </tr>
                    <tr>
                        <th class="vip" rowspan="2">lieux</th>
                        <td><?php echo $infos['codeshop']; ?> <?php echo $infos['ssociete']; ?></td>
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <td><?php echo $infos['adresse']; ?></td>
                        <td colspan="4"><?php echo $infos['cp'].' '.$infos['ville']; ?></td>
                    </tr>
                    <tr>
                        <th class="space4" colspan="6">&nbsp;</th>
                    </tr>
                    <tr>
                        <th class="vip" width="90">Date</td>
                        <td colspan="5"><?php echo 'du '.fdate($infos['datein']).' au '.fdate($infos['dateout']); ?></td>
                    </tr>
                    <tr>
                        <th class="vip" width="90">Etat</td>
                        <td colspan="5">
                            <?php
                            if ($infos['etat'] == 0) {echo 'DEVIS';}
                            if ($infos['etat'] == 1) {echo 'JOB';}
                            if ($infos['etat'] == 2) {echo 'Job OUT';}
                            if ($infos['etat'] == 99) {echo 'Devis OUT';}
                            if ($infos['etat'] == 11) {echo 'JOB Ready';}
                            echo ' '.$infos['etat'].' :<br>';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="space4" colspan="6">&nbsp;</th>
                    </tr>
                </table>
            </td>
            <td width="300" valign="top">
                <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
                    <tr>
                        <td>Notes Prestations</td>
                        <td>Notes D&eacute;placement</td>
                    </tr>
                    <tr>
                        <td><?php echo $infos['noteprest']; ?></td>
                        <td><?php echo $infos['notedeplac']; ?></td>
                    </tr>
                    <tr>
                        <th class="space4" colspan="2">&nbsp;</th>
                    </tr>
                    <tr>
                        <td>Notes Location</td>
                        <td>Notes Frais</td>
                    </tr>
                    <tr>
                        <td><?php echo $infos['noteloca']; ?></td>
                        <td><?php echo $infos['notefrais']; ?></td>
                    </tr>
                    <tr>
                        <th class="space4" colspan="2">&nbsp;</th>
                    </tr>
                    <tr>
                        <td colspan="2">Briefing promoboy</td>
                    </tr>
                    <tr>
                        <td colspan="2"><?php echo $infos['briefing']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><?php if (($infos['etat'] == 2) or ($infos['etat'] == 99)) echo '<div class="warning"><b>'.$raisonout[$infos['raisonout']].'</b><br>'.$infos['noteout'].'</div>'; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
        <tr>
            <th class="vip" align="left">
                <?php
                switch ($infos['etat']) {
                    case "2":
                        echo 'OUT (';
                        $compter = new db();
                        $compter->inline("SELECT  COUNT(idvip)  FROM  `vipdelete` WHERE `idvipjob` = ".$_GET['idvipjob']);
                        $Count = mysql_result($compter->result,0);
                        echo $Count.')';
                    break;
                    case "11":
                    default:
                        echo 'JOB (';
                        $compter = new db();
                        $compter->inline("SELECT  COUNT(idvip)  FROM  `vipmission` WHERE `idvipjob` = ".$_GET['idvipjob']);
                        $Count = mysql_result($compter->result,0);
                        echo $Count.')';
                    break;
                }

             ?>
            </th>
        </tr>
        <tr>
            <td valign="top" height="330">
                <?php if (!empty($_GET['idvipjob'])) { ?>
                    <iframe frameborder="0" marginwidth="0" marginheight="0" name="devisdetail" src="<?php echo 'adminvip-devis.php?act=showview&etat=1&idvipjob='.$_GET['idvipjob'].'';?>" width="98%" height="98%" align="top">Marche pas les IFRAMES !</iframe>
                <?php } ?>
            </td>
        </tr>
    </table>
</div>
</div>