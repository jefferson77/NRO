<?php
$jobDates = $DB->getArray("SELECT vipdate, count(*) as nbr from ".(($job_infos['etat'] == 0) ? 'vipdevis' : 'vipmission')." WHERE idvipjob = ".$job_infos['idvipjob']. " GROUP BY vipdate ORDER BY vipdate");
?>
<style type="text/css">
    table.rowstyle-alt td, table.rowstyle-alt th, input {
        padding: 4px;
        font-size: 14px;
    }
</style>
<div id="centerzonelarge">
    <fieldset>
        <legend>Duplication Job <?php echo $job_infos['idvipjob'] ?></legend>
        <form action="?act=doDuplicate" method="post">
            <input type="hidden" name="idvipjob" value="<?php echo $_REQUEST['idvipjob'] ?>">
            Nouveau Nom du Job :
            <input type="text" name="new_reference" value="<?php echo $job_infos['reference'] ?>" size="150">
            <br>
            <br>
            <table class="rowstyle-alt" border="0" cellspacing="1" cellpadding="0" align="center">
                <thead>
                    <tr align="center">
                        <th>Dates</th>
                        <th>Missions</th>
                        <th></th>
                        <th>Nouvelle Date</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($jobDates as $row): ?>
                    <tr>
                        <td><?php echo $row['vipdate'] ?></td>
                        <td align="center"><?php echo $row['nbr'] ?></td>
                        <td> => </td>
                        <td><input type="text" id="nd_<?php echo $row['vipdate'] ?>" name="nd_<?php echo $row['vipdate'] ?>" value="<?php echo $row['vipdate'] ?>"></td>
                    </tr>
<?php endforeach ?>
                </tbody>
            </table>
            <p align="center"><input type="submit" name="Dupliquer" value="Dupliquer Job <?php echo $job_infos['idvipjob'] ?>"></p>
        </form>
    </fieldset>
</div>