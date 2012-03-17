<div id="centerzonelarge">
<?php

$classe = "planning";

if ($_GET['checkeddef'] == 'out') {$checkedout = 'checked'; }
if ($_GET['checkeddef'] == 'back') {$checkedback = 'checked'; }
if (($_GET['checkeddef'] == 'sb') or ($_GET['checkeddef'] == '')) {$checkedsb = 'checked'; }
?>
<fieldset>
	<legend>
		<b>listing des Merch &agrave; mettre en ARCHIVE</b>
	</legend>
	<b>ARCHIVER ?</b><br>
</fieldset>
<br>
	<div align="center">
		<a href="<?php echo $_SERVER['PHP_SELF'].'?act=purgeout1&listtype=1&action=skip&checkeddef=sb';?>">Reload Standby</a> &nbsp - &nbsp 
		<a href="<?php echo $_SERVER['PHP_SELF'].'?act=purgeout1&listtype=1&action=skip&checkeddef=out';?>">Reload OUT</a> &nbsp - &nbsp 
		<a href="<?php echo $_SERVER['PHP_SELF'].'?act=purgeout1&listtype=1&action=skip&checkeddef=back';?>">Reload Back</a>
	</div>
<?php
$listing1 = new db();
$listing1->inline("
	SELECT 
		me.idmerch, me.datem, me.genre, me.idpeople, me.hin1, me.hout1, me.hin2, 
		me.hout2, me.kmpaye, me.kmfacture, me.produit, me.facturation AS anfacturation, 
		me.ferie, 
		a.prenom, 
		c.codeclient, c.societe AS clsociete, c.facturation AS clfacturation, c.idclient, c.langue, 
		s.codeshop, s.societe AS ssociete, s.ville AS sville, 
		p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational
		nf.montantpaye
	FROM merch me
		LEFT JOIN agent a ON me.idagent = a.idagent 
		LEFT JOIN client c ON me.idclient = c.idclient 
		LEFT JOIN people p ON me.idpeople = p.idpeople
		LEFT JOIN shop s ON me.idshop = s.idshop
		LEFT JOIN notefrais ON nf.secteur = 'ME' AND 
	WHERE me.facturation = '25'
	ORDER BY c.idclient, me.datem");

$listing = 'listing1' ;

$founds = mysql_num_rows($listing1->result);

echo $founds.' Merch' ;

	if ($founds != '0') {
		include 'purgeout1_inc.php' ;
		?><br><?php
	} ?>
<hr>
</div>
