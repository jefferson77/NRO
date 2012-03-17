<?php
define('NIVO', '../');

$Titre     = 'ADMIN Zone';
$PhraseBas = 'Menu Administrateur';

include (NIVO."includes/entete.php");

$ad = $DB->getOne("SELECT adlevel FROM agent WHERE idagent =".$_SESSION['idagent']);
$Style=($ad != "user")?'admin':'';
?>
<div id="centerzonelarge">
<table border="0" width="100%" height="100%" cellspacing="10" cellpadding="10" align="center">
	<tr>
		<td class="fond" rowspan="2" width="200" valign="top">
			<iframe frameborder="0" marginwidth="0" marginheight="0" name="news" src="<?php echo NIVO ?>mod/news/news.php?n=admin&c=10" width="100%" height="90%">Marche pas les IFRAMES !</iframe>
			<a href="<?php echo NIVO ?>mod/news/news.php?n=admin&c=10" target="_blank">Plus de News</a>
		</td>
		<td class="fond" height="50%" valign="top">
			<?php $classe = "merch" ?>
			<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Facturation</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="factures/adminfac.php?act=print">Re-Imprimer</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="factures/adminfac.php?act=search">Rechercher</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="factures/adminfac.php?act=list">Liste</a></td>
				</tr>
				<tr>
					<th class="<?php echo $classe; ?>">Notes Cr&eacute;dit</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="notecredit/adminnc.php?act=list">Liste</a></td>
				</tr>
			</table>
			<br>
			<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="anim">Statistiques</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="stats/adminstats.php?act=search">Rechercher</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="../mod/analytique/admin.php?act=search">Analytique</a></td>
				</tr>
			</table>
		</td>
		<td class="fond" height="50%" valign="top">
			<?php $classe = "merch" ?>
			<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="anim">VIP</th>
				</tr>
			</table>
			<br>
			<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Facturation</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="vip/adminvip.php?act=facture">Listing</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="vip/adminvip.php?act=prefactoring">Pr&eacute;-factures</a></td>

				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="vip/adminvip.php?act=factoring">Factures</a></td>
				</tr>
			</table>
		</td>
		<td class="fond" height="50%" valign="top">
			<?php $classe = "anim" ?>
			<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="anim">Animation</th>
				</tr>
			</table>
			<br>
			<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Facturation</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="animation/adminanim.php?act=facture">Listing</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="animation/adminanim.php?act=prefac">Pr&eacute;-factures</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="animation/adminanim.php?act=facready">Print</a></td>
				</tr>
			</table>
			<br>
			<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Purge</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="animation/adminanim.php?act=purgeout1">Missions Out</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="animation/adminanim.php?act=purgefl1">Missions FL</a></td>
				</tr>
			</table>
		</td>
		<td class="fond" height="50%" valign="top" width="20%">
			<?php $classe = "merch" ?>
			<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="anim">Merch</th>
				</tr>
			</table>
			<br>
			<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Facturation</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/adminmerch.php?act=facture">Listing</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/adminmerch.php?act=prefac">Pr&eacute;-factures</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/adminmerch.php?act=facready">Print</a></td>
				</tr>
			</table>
			<br>
			<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Purge</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/adminmerch.php?act=purgeout1">Missions Out</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/adminmerch.php?act=purgefl1">Missions FL</a></td>
				</tr>
			</table>
			<br>
			<table class="<?php echo $classe; ?>" border="0" width="160" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>" colspan="2">Moyenne KM</th>
				</tr>
				<tr>
					<th class="<?php echo $classe; ?>">GB</th>
					<th class="<?php echo $classe; ?>">Carrefour</th>
				</tr>
				<tr>
					<?php $jour = $DB->getOne("SELECT AVG(kmpaye) FROM `merch` WHERE idclient = 2651 and datem = CURRENT_DATE"); ?>
					<td>Jour : <?php echo fnbr($jour*1.1); ?></td>
					<?php $jour = $DB->getOne("SELECT AVG(kmpaye) FROM `merch` WHERE idclient = 1713 and datem = CURRENT_DATE"); ?>
					<td>Jour : <?php echo fnbr($jour*1.1); ?></td>
				</tr>
				<tr>
					<?php $mois = $DB->getOne("SELECT AVG(kmpaye) FROM `merch` WHERE idclient = 2651 and datem BETWEEN CONCAT(YEAR(CURRENT_DATE), '-' , MONTH(CURRENT_DATE), '-01') AND CURRENT_DATE"); ?>
					<td>Mois : <?php echo fnbr($mois*1.1); ?></td>
					<?php $mois = $DB->getOne("SELECT AVG(kmpaye) FROM `merch` WHERE idclient = 1713 and datem BETWEEN CONCAT(YEAR(CURRENT_DATE), '-' , MONTH(CURRENT_DATE), '-01') AND CURRENT_DATE"); ?>
					<td>Mois : <?php echo fnbr($mois*1.1); ?></td>
				</tr>
				<tr>
					<?php $annee = $DB->getOne("SELECT AVG(kmpaye) FROM `merch` WHERE idclient = 2651 and datem BETWEEN CONCAT(YEAR(CURRENT_DATE), '-01-01') AND CURRENT_DATE"); ?>
					<td>Année : <?php echo fnbr($annee*1.1); ?></td>
					<?php $annee = $DB->getOne("SELECT AVG(kmpaye) FROM `merch` WHERE idclient = 1713 and datem BETWEEN CONCAT(YEAR(CURRENT_DATE), '-01-01') AND CURRENT_DATE"); ?>
					<td>Année : <?php echo fnbr($annee*1.1); ?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="fond" height="50%" valign="top">
<?php if ($_SESSION['roger'] == 'devel') {?>
		<?php $classe = "standard" ?>
		<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
			<tr>
				<th class="<?php echo $classe; ?>">Dev Toolbox</th>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>"><a href="<?php echo STATIK ?>illus/temp/index.php">Illus</a></td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>sql/">SQL Admin</a></td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>outils/disk.php">Disk Usage</a></td>
			</tr>
		</table>
			<br>
<?php } ?>
			<?php $classe = "vip" ?>
			<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Modules</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>mod/news/adminnews.php">News</a></td>
				</tr>
			</table>
			<br>
			<br>
			<?php $classe = "anim" ?>
			<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Agents</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>data/agent/adminagent.php">G&eacute;rer</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>data/agent/modifagent.php">Ajouter</a></td>
				</tr>
			</table>
			<br>
		</td>

		<td  class="fond" height="50%" valign="top">
			<?php $classe = "vip" ?>
<?php if ($_SESSION['roger'] == 'devel') {?>
			<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">New Clients Web</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>mod/news/adminnews.php">Listing</a></td>
				</tr>
			</table>
			<br>
				<?php } ?>
			<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Commissions</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="<?php echo NIVO; ?>mod/commission/admincommission.php">Listing</a></td>
				</tr>
			</table>
		</td>
		<td class="fond" height="50%" valign="top">
		<?php $classe = "standard" ?>
		<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
			<tr>
				<th class="<?php echo $classe; ?>">Groupe-S</th>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>groupe-s/admingroupes.php?act=showStock">1. Stocker</a></td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>groupe-s/admingroupes.php">2. Corriger</a></td>
			</tr>
<?php if ($_SESSION['roger'] == 'devel') {?>
			<tr>
				<th class="<?php echo $classe; ?>">G-S Manuel</th>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>"><a href="groupes/admings.php?act=list">Liste</a></td>
			</tr>
<?php } ?>
			<tr>
				<th class="<?php echo $classe; ?>">Salaires</th>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>mod/fichesalaire/index.php">Fiches de Salaires</a></td>
			</tr>
			<tr>
				<td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>mod/fichesalaire/index.php">281.10</a></td>
			</tr>
		</table>
		</td>
		<td class="fond" height="50%" valign="top">
		<?php $classe = "standard" ?>
			<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Horizon</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>mod/horizon/index.php">Export vers Horizon</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>mod/horizon/echeancier.php">Import de Horizon</a></td>
				</tr>
			</table>
			<br>
			<table class="<?php echo $classe; ?>" border="0" width="100" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Logins</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>mod/logs/index.php">Internes</a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>
<?php include (NIVO."includes/pied.php"); ?>