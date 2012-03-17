<?php #  Centre de recherche d'VIPs ?>
<div id="centerzonelarge">
<?php $classe = "planning" ; ?>
<?php

###### Facturation VARIABLE GENERALES ###
		$recherche0='
			SELECT 
			an.idanimation, an.datem, an.reference, an.genre, an.idpeople, an.hin1, an.hout1, an.hin2, an.hout2, an.kmpaye, an.kmfacture, an.frais, an.produit, an.boncommande, an.facturation AS anfacturation, an.idcofficer, 
			an.ferie, 
			a.nom AS nomagent, a.prenom, 
			c.adresse, c.cp, c.ville, c.tvheure05, c.tvheure6, c.tvnight, c.tvkm, c.idclient, 
			c.codeclient, c.societe AS clsociete, c.facturation AS clfacturation, c.idclient, 
			s.codeshop, s.societe AS ssociete, s.ville AS sville, 
			o.onom , o.oprenom , o.tel, o.fax, o.qualite, o.langue, 
			p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational,
			ma.idanimation AS maidanimation, ma.idanimmateriel, ma.stand, ma.gobelet, ma.serviette, ma.four, ma.curedent, ma.cuillere, ma.rechaud, ma.autre
			FROM animation an
			LEFT JOIN agent a ON j.idagent = a.idagent 
			LEFT JOIN client c ON an.idclient = c.idclient 
			LEFT JOIN cofficer o ON an.idcofficer  = o.idcofficer 
			LEFT JOIN people p ON an.idpeople = p.idpeople
			LEFT JOIN shop s ON an.idshop = s.idshop
			LEFT JOIN animmateriel ma ON an.idanimation = ma.idanimation
		';

		$quid="WHERE 1 AND an.facturation = '20'";
		$sort = 'c.idclient, an.datem';
#/##### OUT VARIABLE GENERALES ###

# recherche Sql :
	$rechercheout='
	'.$recherche0.'
	'.$quid.'
	 ORDER BY '.$sort.'
					';

### COMMUN : Afficher la liste des Anim OUT
# echo $recherche;
# Recherche des résultats
if ($_GET['checkeddef'] == 'fl') {$checkedfl = 'checked'; }
if ($_GET['checkeddef'] == 'out') {$checkedout = 'checked'; }
if ($_GET['checkeddef'] == 'back') {$checkedback = 'checked'; }
if (($_GET['checkeddef'] == 'sb') or ($_GET['checkeddef'] == '')) {$checkedsb = 'checked'; }
?>



<fieldset>
	<legend>
		<b>listing des Anim &agrave; faire passer chez FL pour r&eacute;conciliation</b>
	</legend>
	<b>ARCHIVER ?</b><br>
</fieldset>
<br>
	<div align="center">
		<a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl1&listtype=1&action=skip&checkeddef=fl';?>">Reload FL</a> &nbsp - &nbsp 
		<a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl1&listtype=1&action=skip&checkeddef=out';?>">Reload OUT</a> &nbsp - &nbsp 
		<a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl1&listtype=1&action=skip&checkeddef=back';?>">Reload Back</a> &nbsp - &nbsp 
		<a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl1&listtype=1&action=skip&checkeddef=sb';?>">Reload Standby</a>
	</div>
<?php
#-----------------
$page = 'purgefl1_inc.php';
?>
	<?php 
	# echo $rechercheout;
	# Recherche des résultats
	#	echo $recherche1;
		$listing1 = new db();
		$listing1->inline("$rechercheout;");
		$listing = 'listing1' ;
		$founds = mysql_num_rows($listing1->result);
		echo $founds.' anims' ;

	if ($founds != '0') {
	?>
		<?php
			include $page ;
		?>
		<br>
	<?php } ?>
<?php ############################# ?>
	<div align="center">
		<a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl1&listtype=1&action=skip&checkeddef=fl';?>">Reload FL</a> &nbsp - &nbsp 
		<a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl1&listtype=1&action=skip&checkeddef=out';?>">Reload OUT</a> &nbsp - &nbsp 
		<a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl1&listtype=1&action=skip&checkeddef=back';?>">Reload Back</a> &nbsp - &nbsp 
		<a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl1&listtype=1&action=skip&checkeddef=sb';?>">Reload Standby</a>
	</div>
<br>
</div>
