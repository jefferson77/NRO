<div id="centerzone">
<?php
$classe = "planning" ;

###### Facturation VARIABLE GENERALES ###
$sqlstart = 'SELECT me.idmerch FROM merch me LEFT JOIN client c ON me.idclient = c.idclient WHERE me.facturation = 6 AND me.facnum IS NULL AND ';
?>
<fieldset>
	<legend>
		<b>listing des Merch</b>
	</legend>
	<b>FACTURES</b><br>
</fieldset>
<br>
<br>Facturation par semaine (avant ce lundi <?php echo fdate($oneweekago); ?>) :
<?php
	$listing1 = new db();
	$listing1->inline($sqlstart." c.facturation = 1 AND me.datem < '".$oneweekago."'");

    echo mysql_num_rows($listing1->result);

    if (mysql_num_rows($listing1->result) > 0) {
	?>
    <div align="center">
        <form action="<?php echo NIVO ?>print/merch/facture/facture.php" method="post" target="popup" onsubmit="OpenBrWindow('about:blank','popup','scrollbars=yes,status=yes,resizable=yes','600','600','true')" >
            <input type="submit" class="printer_48">
        </form>
    </div>
	<?php
	}
?>
<hr>
<hr>
<br>Facturation par mois (avant le <?php echo fdate($onemonthago); ?>) :
<?php
    $listing1->inline($sqlstart." c.facturation = 3 AND me.datem < '".$onemonthago."'");
    echo mysql_num_rows($listing1->result);

    if (mysql_num_rows($listing1->result) > 0) {
	?>
    <div align="center">
        <form action="<?php echo NIVO ?>print/merch/facture/facture.php" method="post" target="popup" onsubmit="OpenBrWindow('about:blank','popup','scrollbars=yes,status=yes,resizable=yes','600','600','true')" >
            <input type="submit" class="btn printer_48">
        </form>
    </div>
	<?php
	}
?>
<br>
<hr>
<br>VERIFICATION : <br>
	Facturation ni par semaine ni par mois (avant le <?php echo fdate(date ("Y-m-d", strtotime("-2 days"))); ?>) :
<?php
	$listingverif = new db();
	$listingverif->inline($sqlstart." c.facturation NOT IN (1, 3) AND me.datem < '".date ("Y-m-d", strtotime("-2 days"))."'");
    $nums = mysql_num_rows($listingverif->result);

	echo $nums;

	if ($nums > 0) {
	?>
		<br>
		<br>
		<font color="red" size="4">ATTENTION : </font><Font color="white" size="4"><?php echo $nums; ?> jobs avec des clients en facturation autre</font>
	<?php
	}
?>
<br>
<hr>
</div>
