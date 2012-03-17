<?php 
if (!isset($_GET['checkeddef'])) $_GET['checkeddef'] = '';

$classe = "planning" ;

### ANIM ###
$sqlanim = "SELECT 
	an.idanimation, an.datem, an.reference, an.genre, an.idpeople, an.hin1, an.hout1, an.hin2, an.hout2, an.kmpaye, an.kmfacture, an.frais,
	an.produit, an.facturation AS anfacturation, an.ferie, an.facnum,
	j.boncommande, j.idcofficer,
	a.nom AS nomagent, a.prenom, 
	c.adresse, c.cp, c.ville, c.tvheure05, c.tvheure6, c.tvnight, c.tvkm, c.idclient, 
	c.codeclient, c.societe AS clsociete, c.facturation AS clfacturation, c.idclient, 
	s.codeshop, s.societe AS ssociete, s.ville AS sville, 
	o.onom , o.oprenom , o.tel, o.fax, o.qualite, o.langue, 
	p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational,
	ma.idanimation AS maidanimation, ma.idanimmateriel, ma.stand, ma.gobelet, ma.serviette, ma.four, ma.curedent, ma.cuillere, ma.rechaud, ma.autre
	FROM animation an
	LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
	LEFT JOIN client c ON j.idclient = c.idclient 
	LEFT JOIN cofficer o ON j.idcofficer  = o.idcofficer 
	LEFT JOIN agent a ON j.idagent = a.idagent 
	LEFT JOIN people p ON an.idpeople = p.idpeople
	LEFT JOIN shop s ON an.idshop = s.idshop
	LEFT JOIN animmateriel ma ON an.idanimation = ma.idanimation
	WHERE 1 AND an.facturation = '9'
	ORDER BY c.idclient, an.datem";

### MERCH ###
$sqlmerch = "SELECT 
		me.idmerch, me.datem, me.genre, me.idpeople, me.hin1, me.hout1, me.hin2, me.hout2, 
		me.kmpaye, me.kmfacture, me.produit, me.facturation AS anfacturation, 
		me.ferie, me.facnum,
		a.prenom, 
		c.codeclient, c.societe AS clsociete, c.facturation AS clfacturation, c.idclient, c.langue, 
		s.codeshop, s.societe AS ssociete, s.ville AS sville, 
		p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational,
		nf.montantpaye
	FROM merch me
		LEFT JOIN agent a ON me.idagent = a.idagent 
		LEFT JOIN client c ON me.idclient = c.idclient 
		LEFT JOIN people p ON me.idpeople = p.idpeople
		LEFT JOIN shop s ON me.idshop = s.idshop
		LEFT JOIN notefrais nf ON nf.secteur = 'ME' and nf.idmission = me.idmerch
	WHERE 1 AND me.facturation = '9'
	ORDER BY c.idclient, me.datem";

### COMMUN : Afficher la liste des Anim OUT
# Recherche des résultats
switch ($_GET['checkeddef']) {
	case"fl":
		$checkedfl = 'checked';
	break;
	case"out":
		$checkedout = 'checked';
	break;
	case"back":
		$checkedback = 'checked';
	break;
	case"sb":
	default:
		$checkedsb = 'checked';
}
?>
<div id="centerzonelarge">
		<fieldset>
			<legend>
				<b>listing des Jobs en r&eacute;conciliation avec facture manuelle</b>
			</legend>
			<b>R&eacute;concilier ?</b><br>
			<?php if (!empty($erreurmodefac)) { echo $erreurmodefac; } ?>
		</fieldset>
			<div align="center">
				<a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl1&listtype=1&action=skip&checkeddef=fl';?>">Reload Ok</a> &nbsp - &nbsp 
				<a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl1&listtype=1&action=skip&checkeddef=out';?>">Reload OUT</a> &nbsp - &nbsp
				<a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl1&listtype=1&action=skip&checkeddef=back';?>">Reload Back</a> &nbsp - &nbsp
				<a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl1&listtype=1&action=skip&checkeddef=sb';?>">Reload Standby</a> &nbsp - &nbsp 
			</div>
<?php
### ANIM ###
?>
		<?php
		#-----------------
		$page = 'purgefl1anim.php';
		?>
			<?php 
			# Recherche des résultats
				$listing1anim = new db();
				$listing1anim->inline($sqlanim);
				$listing = 'listing1anim' ;
				$foundsanim = mysql_num_rows($listing1anim->result);
				echo '<h2>ANIM : '.$foundsanim.'</h2>' ;
			if ($foundsanim != '0') {
			?>
				<?php
					include $page ;
				?>
			<?php } ?>
		<hr>
<?php
#/## ANIM ###

### Merch ###
?>
		<?php
		#-----------------
		$page = 'purgefl1merch.php';
		?>
			<?php 
			# Recherche des résultats
				$listing1merch = new db();
				$listing1merch->inline($sqlmerch);
				$listing = 'listing1merch' ;
				$foundsmerch = mysql_num_rows($listing1merch->result);
				echo '<h2>MERCH : '.$foundsmerch.'</h2>' ;
					include $page ;
			if ($foundsmerch != '0') {
			?>
				<?php
### POUR MERCH EN STAND BY					include $page ;
				?>
			<?php } ?>
		<hr>
<?php
#/## MERCH ###
?>
			<div align="center">
				<a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl1&listtype=1&action=skip&checkeddef=fl';?>">Reload Ok</a> &nbsp - &nbsp 
				<a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl1&listtype=1&action=skip&checkeddef=out';?>">Reload OUT</a> &nbsp - &nbsp
				<a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl1&listtype=1&action=skip&checkeddef=back';?>">Reload Back</a> &nbsp - &nbsp
				<a class="creme" href="<?php echo $_SERVER['PHP_SELF'].'?act=purgefl1&listtype=1&action=skip&checkeddef=sb';?>">Reload Standby</a> &nbsp - &nbsp 
			</div>
			<br>
</div>
