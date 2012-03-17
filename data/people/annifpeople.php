<?php
define('NIVO', '../../'); 

include NIVO."includes/ifentete.php" ;

$peoples = $DB->getArray("SELECT idpeople, pnom, pprenom, gsm, email, codepeople, err, ndate FROM `people` WHERE MONTH(ndate) = ".date("m")." AND DAY(ndate) LIKE ".date("d")." AND `isout` NOT LIKE 'out'");
?>
<style type="text/css" media="screen">
	body {
		background-color: #FFF;
	}

	table
	{
		background-color: #FCC;
		color: #30C;
	}

	th
	{
		font-size: 14px;
		background-color: #06F;
		color: #FCC;
	}

	td
	{
		border-bottom: #527184;
		border-width: 0 0 1px 0;
		border-style: none none dotted none;
		text-align: center;
	}

	fieldset
	{
		padding: 5px;
		border-color: #6CF;
		border-width: 1px;
		border-style: solid;
		margin: 3px;
	}

	legend 
	{
		font-weight: bold;
		color: #06F;
	}
</style>
<fieldset>
	<legend>People : Anniversaires (<?php echo count($peoples); ?>)</legend>
	<div align="center">
		<img src="<?php echo STATIK ?>illus/happyb.gif" alt="search" border="0"><br>
		<table border="0" width="90%" cellspacing="1" cellpadding="1" align="center">
			<tr>
				<th></th>
				<th colspan="2">Code/ID</th>
				<th>Nom</th>
				<th>Pr&eacute;nom</th>
				<th>Age</th>
				<th>email</th>
				<th>GSM</th>
			</tr>
	<?php 
	foreach ($peoples as $row) {
	if ($row['err'] == 'Y') {
		echo '<tr style="background-color: #F4D25C;"><td><img src="'.STATIK.'illus/attention.gif" alt="attention.gif" width="14" height="14" align="right"></td>';
	} else {
		echo '<tr><td></td>';
	}
	?>				
				<td><?php echo $row['codepeople']; ?></td>
				<td><?php echo $row['idpeople']; ?></td>
				<td><?php echo $row['pnom']; ?></td>
				<td><?php echo $row['pprenom']; ?></td>
				<td><?php echo age($row['ndate']); ?></td>
				<td><a href="mailto:<?php echo $row['email'];?>?subject=Happy Birthday <?php echo $row['pprenom']; ?>"><?php echo $row['email']; ?></a></td>
				<td><?php echo $row['gsm']; ?></td>
			</tr>
	<?php } ?>				
		</table>
		<br><br>
		<a href="javascript:window.close();"><b>&gt; J'ai fini, merci.&lt;<br>&gt; On peut fermer la page &lt;<br>&gt; Et aller faire la f&ecirc;te &lt;</b></a>
	</div>
</fieldset>
<?php include NIVO."includes/ifpied.php"; ?>