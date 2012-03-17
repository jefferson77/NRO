<?php #  Centre de recherche de Clients ?>
<div id="centerzonelargewhite">
<!-- NOTES GENERALES -->
<img src="<?php echo STATIK ?>illus/100light.gif" alt="ajouter" width="16" height="16" border="0"><font color="peru"> &nbsp <b>FOCUS</b></font><br>
<?php /* ### search Form Unité */ ?>
	<h2>1. Search Form / Unit&eacute;s</h2>
	<ul>
		<li> Situation : est-ce complet ? j'ai l'impression que cela ne recouvre pas totalement la vie d'un ticket. Il faut &ecirc;tre certain que quel que soit la situation du materiel, un &eacute;tat puisse clairement le situer.
		<li> Situation : exemple ? j'ai re&ccedil;u ce soir un Costume en retour. Il partira demain en fournisseur. Quel est sont &eacute;tat actuel ?
	</ul>
	<br><hr color="peru"><hr color="peru">
<?php /* ### search Results Unité */ ?>
	<h2>2. Search Results / Unit&eacute;s</h2>
	<ul>
		<li> Modification du tableau
			<ul>
				<li> Nom de l'unit&eacute; : est-ce utile ? le code n'est pas suffisante sachant qu'il y a d&eacute;j&agrave; la r&eacute;ference famille et la r&eacute;f&eacute;rence mod&egrave;le ?
				<li> Date, Mission, Reference, People, GSM et mail : ne sont affich&eacute;s que si l'unit&eacute; est en situation "out", malgr&eacute; les tickets en cours.
			</ul>
	</ul>
	<br><hr color="peru"><hr color="peru">
<?php /* ### ### Detail / Unités */ ?>
	<h2>3. Detail / Unit&eacute;s</h2>
	<h2>3.1 Detail / Unit&eacute;s / listing tickets</h2>
	<br><hr color="peru"><hr color="peru">
<?php /* ### Famille / Général ### */ ?>
	<h2>4.0 Famille / G&eacute;n&eacute;ral</h2>
	<ul>
		<li> Famille :
			<ul>
				<li> Type : d&eacute;fini le comportement de "ajout" dans mod&egrave;le : soit par "unit&eacute;", soit par paquet
				<li> Type : Doit on pr&eacute;voir dans la m&ecirc;me famille des mod&egrave;les "unit&eacute;" ET des "paquets" ?
				<li> Type : "unit&eacute;" et "paquets" : ne faut-il pas ajouter une troisi&egrave;me "taille" (par exemple")?
				<li> Type : "unit&eacute;" : tous les m&ecirc;mes chapeau et code barre
				<li> Type : "taille" : pas tous les m&ecirc;mes chemisier (34, 36, ...) et code barre
				<li> Type : "paquets" : tous les m&ecirc;mes packs de flyers et pas de code barre
			</ul>
	</ul>
	<ul>
		<li> Mod&egrave;les :
			<ul>
				<li> Comment le materiel actuel sera reli&eacute; aux mod&egrave;les ?
				<li> Proposition :
					<ul>
						<li> Dans l'espace Materiel existant;
						<li> Dupplication de l'espace de recherche et listing de r&eacute;sultat;
						<li> Recherche modifi&eacute;e avec en plus : n'appartient &agrave; aucun mod&egrave;le;
						<li> Listing modifi&eacute;: input d'un "id modele";
					</ul>
			</ul>
	</ul>
	<br><hr color="peru"><hr color="peru">
<?php /* ### Famille / Unité ### */ ?>
	<h2>4.2 Famille / Unit&eacute;s</h2>
	<br><hr color="peru"><hr color="peru">
<?php /* 	### Famille / Listing ### */ ?>
	<h2>4.3 Famille / Listing</h2>
	<ul>
		<li> Unit&eacute;s :
			<ul>
				<li> Modification des infos de base
				<li> + Modification idvip pour attribution ou retour ?
				<li> et Merch ? et Anim ?
			</ul>
	</ul>
	<br><hr color="peru"><hr color="peru">
<?php /* ### Famille / Tickets ### */ ?>
	<h2>4.4 Famille / Tickets</h2>
	<ul>
	<li> Planning : Comment mettre deux pr&eacute;visions de r&eacute;servation &agrave; 1 unit&eacute;  ?
		<ul>
			<li> Syst&egrave;me de tickets :
				<ul>
					<li> Concept proche de dispo people
					<li> une attribution = 1 date pour "out"
					<li> une p&eacute;riode de w jours d'indisponibilit&eacute; avant "out"
					<li> une p&eacute;riode de x jours d'indisponibilit&eacute; avant "retour"
					<li> une p&eacute;riode de y jours d'indisponibilit&eacute; avant "fournisseur"
					<li> une p&eacute;riode de z jours d'indisponibilit&eacute; avant retour en "in" et fin du ticket
					<li> PS : pour chaque &eacute;tape, quelles cons&eacute;quences par raport &agrave; samedi ou dimanche ?
				</ul>
			<li> R&eacute;sultat :
				<ul>
					<li> Date - w = d&eacute;but du ticket
					<li> Date + x + y + z = fin du ticket
				</ul>
			<li> R&eacute;sultat dans cet exemple:
				<ul>
					<li> Date - 1 = d&eacute;but du ticket
					<li> Date + 13 + 1 + 3 = fin du ticket
				</ul>
			<li> Info Celsys :
				<ul>
					<li> Chaque unit&eacute; est g&eacute;r&eacute;e comme un terrain pour un PAF
					<li> si libre dans tranche horraire ==> attribution
					<li> si pas libre recherche d'un autre
				</ul>
			<li> Liste tickets :
				<ul>
					<li> Comme "Unit&eacute;s" mais non modifiable
					<li> Pour chaque unit&eacute;, un sous-listing des tickets "en cours" ou "&agrave; venir"
					<li> Info : j'ai donne M1, et donc ouvert un T1, mais le M1 ne convient pas ... on me le rend, et je donne un M2 &agrave; la place ==> !!! il faut modifier T1 !!!
				</ul>
			<li> Dur&eacute;e des tickets
				<ul>
					<li> Vip : Info Vincent : d&eacute;but du VipJob ==> fin du VipJob
					<li> Comment g&eacute;rer les changement de dur&eacute;e d'un Job ? Modification massive dans un onglet Vip de tous les tickets ?
				</ul>
			<li> PS :
				<ul>
					<li> Un mauvais Concept dans la structure des tickets ? tout le stock s'&eacute;ffondre !!!
				</ul>
		</ul>
	</ul>
	<br><hr color="peru"><hr color="peru">
<?php /* ### Vip / Modèles ### */ ?>
	<h2>5.1 VIP / Mod&egrave;les</h2>
	<ul>
		<li> Structure &agrave; 2 niveaux : Responsable et People
		<li> Structure &agrave; 2 cat&eacute;gories :
			<ul>
				<li> Packets  (sans code barre)
				<li> Unit&eacute;s (avec code barre)
			</ul>
		<li> Structure &agrave; 3 sexes : F / M / X
			<ul>
				<li> La mission est d&eacute;finie F
				<li> Le people choisi (don en rouge) est d&eacute;fini H
				<li> Que se passe-t-il ?
			</ul>
	</ul>
	<br><hr color="peru"><hr color="peru">
<?php
if (($_GET['sectionbuild'] == 0) or ($_GET['sectionbuild'] == 9)) {
	### Vip / Tickets ###
		echo '<h2>5.2 VIP / Tickets</h2>';
		$did = 2107;
		$idvipjob = 2107;
		$_GET['s'] = 8;
		echo '<fieldset class="blue">
			<legend class="blue">
				VIP
			</legend>
		';
		include 'vip-detail.php';
		echo '</fieldset>';
		if ($_SESSION['infostock'] == 1) { ?>
			<br>
			<img src="<?php echo STATIK ?>illus/100light.gif" alt="ajouter" width="16" height="16" border="0"><font color="peru"> &nbsp <b>FOCUS</b></font><br>
			<ul>
				<li> Type : "taille" : non gestion d'une attribution automatique
					<ul>
					<li> Attribution automatique :
						<ul>
							<li> La DB est totalement Inconsistante
							<li> une partie de r&eacute;sultat sur une recherche people : taille jupe
							<ul>
								<li> 38/40
								<li> 36/38
								<li> 3638
								<li> 34/36
								<li> 37
								<li> -3436
							</ul>
							<li> Ne pas oublier de cr&eacute;er de "mod&egrave;les" Homme : Xl - L - M ...
						</ul>
				</ul>
			</ul>
	<?php
		}
	#/
	echo '<br><hr color="peru"><hr color="peru">';
}
if (($_GET['sectionbuild'] == 0) or ($_GET['sectionbuild'] == 10)) {
	### Vip / Tickets ###
		echo '<h2>6.1 Stock / Projection sur date</h2>';
		$did = 2107;
		$idvipjob = 2107;
		$_GET['s'] = 8;
		echo '<fieldset class="blue">
			<legend class="blue">
				VIP
			</legend>
		';
		include 'stock-projection.php';
		echo '</fieldset>';
		if ($_SESSION['infostock'] == 1) { ?>
			<br>
			<img src="<?php echo STATIK ?>illus/100light.gif" alt="ajouter" width="16" height="16" border="0"><font color="peru"> &nbsp <b>FOCUS</b></font><br>
			<ul>
				<li> Affichage sur une p&eacute;riode donn&eacute;e de
					<ul>
					<li> Tickets en cours pour une famille par mod&egrave;le
					<li> Job en cours &agrave; cette p&eacute;riode
					</ul>
				<li> R&eacute;sultat :
					<ul>
					<li> Vision globalisante des r&eacute;servations existantes
					<li> Vision globalisante des besoins potetiels
					</ul>
			</ul>
	<?php
		}
	#/
	echo '<br><hr color="peru"><hr color="peru">';
}
?>
<br>
<div align="center">
	<?php if ($_SESSION['infostock'] == 1) { ?>
		<a href="<?php echo $_SERVER['PHP_SELF'].'?infostock=0';?>">Sans Commentaire</a>
	<?php } else { ?>
		<a href="<?php echo $_SERVER['PHP_SELF'].'?infostock=1';?>">Avec Commentaire</a>
	<?php } ?>
</div>
<div align="center">
	<table class="standard" width="99%" cellspacing="2" cellpadding="2">
		<tr>
			<th class="standard"><a href="<?php echo $_SERVER['PHP_SELF'].'?infostock='.$_GET['infostock'].'&sectionbuild=0';?>"><font color="<?php echo $l0; ?>">All</font></a></th>
			<th class="standard"><a href="<?php echo $_SERVER['PHP_SELF'].'?infostock='.$_GET['infostock'].'&sectionbuild=1';?>"><font color="<?php echo $l1; ?>">Search</font></a></th>
			<th class="standard"><a href="<?php echo $_SERVER['PHP_SELF'].'?infostock='.$_GET['infostock'].'&sectionbuild=2';?>"><font color="<?php echo $l2; ?>">Result</font></a></th>
			<th class="standard"><a href="<?php echo $_SERVER['PHP_SELF'].'?infostock='.$_GET['infostock'].'&sectionbuild=3';?>"><font color="<?php echo $l3; ?>">Detail unit</font></a></th>
			<th class="standard"><a href="<?php echo $_SERVER['PHP_SELF'].'?infostock='.$_GET['infostock'].'&sectionbuild=4';?>"><font color="<?php echo $l4; ?>">Famille mod&egrave;les</font></a></th>
			<th class="standard"><a href="<?php echo $_SERVER['PHP_SELF'].'?infostock='.$_GET['infostock'].'&sectionbuild=5';?>"><font color="<?php echo $l5; ?>">Famille unit</font></a></th>
			<th class="standard"><a href="<?php echo $_SERVER['PHP_SELF'].'?infostock='.$_GET['infostock'].'&sectionbuild=6';?>"><font color="<?php echo $l6; ?>">Famille Listing</font></a></th>
			<th class="standard"><a href="<?php echo $_SERVER['PHP_SELF'].'?infostock='.$_GET['infostock'].'&sectionbuild=7';?>"><font color="<?php echo $l7; ?>">Famille Ticket</font></a></th>
			<th class="standard"><a href="<?php echo $_SERVER['PHP_SELF'].'?infostock='.$_GET['infostock'].'&sectionbuild=8';?>"><font color="<?php echo $l8; ?>">Vip mod&egrave;les</font></a></th>
			<th class="standard"><a href="<?php echo $_SERVER['PHP_SELF'].'?infostock='.$_GET['infostock'].'&sectionbuild=9';?>"><font color="<?php echo $l9; ?>">Vip Ticket</font></a></th>
			<th class="standard"><a href="<?php echo $_SERVER['PHP_SELF'].'?infostock='.$_GET['infostock'].'&sectionbuild=10';?>"><font color="<?php echo $l10; ?>">Projection</font></a></th>
		</tr>
	</table>
</div>

</div>
