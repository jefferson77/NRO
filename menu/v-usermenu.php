<style type="text/css" media="screen">
	td.bbton { background-color: #E6E6E6; color: #4C4C4C; width: 130px; }
	td.bbton a { vertical-align: middle; text-decoration: none; display: block; padding: 10px 0px 0px 2px; }
	td.bbton a:hover { background-color: #CCC; }
</style>
<div id="centerzonelarge">
<table border="0" width="100%" height="100%" cellspacing="3" cellpadding="5" align="center">
	<tr>
		<td class="fond" rowspan="2" width="200" valign="top">
			<iframe frameborder="0" marginwidth="0" marginheight="0" name="news" src="mod/news/news.php?n=user&c=5" width="100%" height="500">Marche pas les IFRAMES !</iframe>
			<a href="mod/news/news.php?n=user&c=100" target="_blank">Plus de News</a>
		</td>
		<td  class="fond" height="50%" valign="top">
		<?php $classe = "vip" ?>
			<table class="<?php echo $classe; ?>" border="0" width="125" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">VIP</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="vip/adminvip.php?act=listingsearch">Recherche Job</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="vip/adminvip.php?act=listing&listing=direct">Mes Jobs</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="vip/adminvip.php?act=planningsearch">Planning Mission</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="vip/adminvip.php?act=planning&listing=missing">To do</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="vip/adminvip.php?act=add">Ajouter un Job</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="vip/adminvip.php?act=facture">Facturation</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><?php
			### NEW VIP JOB FROM THE WEB
				$count = $DB->getOne("SELECT COUNT(*) FROM `webneuro`.`webvipjob` WHERE `etat` = 5 AND `isnew` = 0");
				if ($count > 0) {
					$link1 = '<a href="vip/adminvip.php?act=webviplist">';
					$link2 = '</a>';
				} else {
					$link1 = '';
					$link2 = '';
				}

				echo $link1.'Web Job'.$link2.' ('.$count.')';
					?></td>
				</tr>
			</table>
		</td>
		<td class="fond" height="50%" valign="top" colspan="2">
		<?php $classe = "anim" ?>
			<table class="<?php echo $classe; ?>" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>" colspan="2">Animation</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>" width="50%">
						<table class="<?php echo $classe; ?>" border="0" width="125" cellspacing="1" cellpadding="1" align="center">
							<tr>
								<th class="vip">Jobs</th>
							</tr>
							<tr>
								<td class="<?php echo $classe; ?>"><a href="animation2/adminanim.php?act=listingjobsearch">Rechercher</a></td>
							</tr>
							<tr>
								<td class="<?php echo $classe; ?>"><a href="animation2/adminanim.php?act=listingjob&listing=direct">Mes Job</a></td>
							</tr>
							<tr>
								<td class="<?php echo $classe; ?>"><a href="animation2/adminanim.php?act=addjob">Ajouter</a></td>
							</tr>
							<tr>
								<td class="<?php echo $classe; ?>">&nbsp;</td>
							</tr>
							<tr>
								<th class="vip">Web</th>
							</tr>
								<td class="<?php echo $classe; ?>">
							<?php
						### NEW anim JOB FROM THE WEB
							$count = $DB->getOne("SELECT COUNT(*) FROM `webneuro`.`webanimjob` WHERE `etat` = 5 AND `isnew` = 0");
							if ($count > 0) {
								$link1 = '<a href="animation2/adminanim.php?act=webanimlist">';
								$link2 = '</a>';
							} else {
								$link1 = '';
								$link2 = '';
							}
							echo $link1.'Web Job'.$link2.' ('.$count.')';
							?>
								</td>
							</tr>
						</table>
					</td>
					<td class="<?php echo $classe; ?>" width="50%">
						<table class="<?php echo $classe; ?>" border="0" width="125" cellspacing="1" cellpadding="1" align="center">
							<tr>
								<th class="vip">Missions</th>
							</tr>
							<tr>
								<th class="<?php echo $classe; ?>">
									<form name="ffidanimation" action="<?php echo NIVO ?>animation2/adminanim.php" method="get" accept-charset="utf-8">
										<input type="hidden" name="act" value="showmission">
										<input type="text" name="idanimation" value="" id="pidanimation" size="18" title="Entrez un numéro de Mission" style="text-align:center;">
									</form>
								</th>
							</tr>
							<tr>
								<td class="<?php echo $classe; ?>"><a href="animation2/adminanim.php?act=listingmissionsearch">Rechercher</a></td>
							</tr>
							<tr>
								<td class="<?php echo $classe; ?>"><a href="animation2/adminanim.php?act=listing&listing=direct">Mes Missions</a></td>
							</tr>
							<tr>
								<td class="<?php echo $classe; ?>"><a href="animation2/adminanim.php?act=listingmissionresult&listing=missing">To do</a></td>
							</tr>
							<tr>
								<td class="<?php echo $classe; ?>">&nbsp;</td>
							</tr>
							<tr>
								<td class="<?php echo $classe; ?>"><a href="animation2/adminanim.php?act=facture">Facturation</a></td>
							</tr>
							<tr>
								<td class="<?php echo $classe; ?>"><a href="animation2/adminanim.php?act=historicsearch">Historique</a></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<table class="<?php echo $classe; ?>" border="0" width="125" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="vip">Erreurs</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="mod/error/erreur.php">Mes erreurs</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="mod/sms/sms-error.php">SMS</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="print/dispatch/feedback.php">Mails envoyés</a></td>
				</tr>
			</table>
		</td>
		<td class="fond" height="50%" valign="top">
		<?php $classe = "merch" ?>
			<table class="<?php echo $classe; ?>" border="0" width="125" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Merchandising</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/adminmerch.php?act=listingsearch">Rechercher</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/adminmerch.php?act=listing&listing=direct">Mes Jobs</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/adminmerch.php?act=add">Ajouter</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/adminmerch.php?act=planningsearch">Planning</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/duplicadminmerch.php?act=duplic-set">Duplic. semaine</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/adminmerch.php?act=historicsearch">Historique</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/routing.php">Routing</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/reportmasterfood.php">R MasterFood</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/adminmerch.php?act=facture">Facturation</a></td>
				</tr>
			</table>
			<br>
			<table class="<?php echo $classe; ?>" border="0" width="125" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">EAS</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/adminmerch.php?act=valideas">Validation EAS</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/etatrapports.php">Etat encodage</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="admin/merch/easmensuel.php">Rapport Mensuel</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="merch/adminmerch.php?act=faceas">Facturation EAS</a></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="fond" height="50%" valign="top">
		<?php $classe = "standard" ?>
			<table class="<?php echo $classe; ?>" border="0" width="120" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Jobistes</th>
				</tr>
				<tr>
					<th class="<?php echo $classe; ?>">
						<form name="ffpeople" action="<?php echo NIVO ?>data/people/adminpeople.php" method="get" accept-charset="utf-8">
							<input type="hidden" name="act" value="show">
							<input type="text" name="idpeople" value="" id="pname" size="18" title="Entrez le début d'un nom ou un codepeople" style="text-align:center;">
						</form>
					</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="data/people/adminpeople.php?etat=0">Rechercher</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="data/people/adminpeople.php?act=add">Ajouter</a></td>
				</tr>
				<?php if (@$_SESSION['roger'] == 'devel' || @$_SESSION['roger'] == 'admin') { ?>
					<tr>
						<td class="<?php echo $classe; ?>"><a href="data/people/adminpeople.php?act=groupeselect">Regroupement</a></td>
					</tr>
				<?php } ?>
				<tr>
					<td class="<?php echo $classe; ?>"><b><a href="admin/cdoc/admincdoc.php?act=search">Documents</a></b></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>">&nbsp;</td>
				</tr>
				<?php
			## Inscriptions Web
				$count = $DB->getOne("SELECT COUNT(*) FROM `webneuro`.`webpeople` WHERE `webtype` = '0' AND `webetat` >= '4';");
				if ($count > 0) {
					$link1 = '<a href="data/people/adminpeople.php?act=webnew0">';
					$link2 = '</a>';
				} else {
					$link1 = '';
					$link2 = '';
				}
				echo'<tr><td class="'.$classe.'">'.$link1.'Inscription Web'.$link2.' ('.$count.')</td></tr>';

			## Updates Web
				$count = $DB->getOne("SELECT COUNT(*) FROM `webneuro`.`webpeople` WHERE `webtype` = '1' AND `webetat` >= '4';");
				if ($count > 0) {
					$link1 = '<a href="data/people/adminpeople.php?act=webupdate0">';
					$link2 = '</a>';
				} else {
					$link1 = '';
					$link2 = '';
				}
				echo'<tr><td class="'.$classe.'">'.$link1.'Update Web'.$link2.' ('.$count.')</td></tr>';

				echo '<tr><td class="'.$classe.'">&nbsp</td></tr>';

			## Anniversaires
				$count = $DB->getOne("SELECT COUNT(*) FROM `people` WHERE MONTH(ndate) = ".date("m")." AND DAY(ndate) LIKE ".date("d")." AND `isout` NOT LIKE 'out'");
				if ($count > 0) {
					$link1 = '<a href="data/people/annifpeople.php" target="popupC" onclick="window.open(\'\',\'popupC\',\'scrollbars=yes,status=yes,resizable=yes,width=800,height=500\');">';
					$link2 = '</a>';
				} else {
					$link1 = '';
					$link2 = '';
				}
				echo'<tr><td class="'.$classe.'">'.$link1.'Anniversaire'.$link2.' ('.$count.')</td></tr>'; ?>
			</table>
		</td>
		<td class="fond" height="50%" valign="top">
			<table class="<?php echo $classe; ?>" border="0" width="120" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Clients</th>
				</tr>
				<tr>
					<th class="<?php echo $classe; ?>">
						<form name="ffclient" action="<?php echo NIVO ?>data/client/adminclient.php" method="get" accept-charset="utf-8">
							<input type="hidden" name="act" value="show">
							<input type="text" name="idclient" value="" id="cname" size="18" title="Entrez le début d'un nom de société" style="text-align:center;">
						</form>
					</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="data/client/adminclient.php?etat=0">Rechercher</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="data/client/adminclient.php?act=add">Ajouter</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>">&nbsp</td>
				</tr>
				<?php
			### Web Clients
				$count = $DB->getOne("SELECT COUNT(*) FROM `webneuro`.`webclient` WHERE etat = 1;");
				if ($count > 0) {
					$link1 = '<a href="data/client/adminclient.php?act=weblist">';
					$link2 = '</a>';
				} else {
					$link1 = '';
					$link2 = '';
				}
				echo'<tr><td class="'.$classe.'">'.$link1.'New clients'.$link2.' ('.$count.')</td></tr>';

				?>
				<tr><td class="<?php echo $classe; ?>"><a href="data/client/adminclient.php?act=webvisit">Visites clients</a></td></tr>
			</table>
			<br><br>
			<table class="<?php echo $classe; ?>" border="0" width="125" cellspacing="1" cellpadding="1" align="center">
				<tr><th class="<?php echo $classe; ?>">Notes de frais</th></tr>
				<tr><td class="<?php echo $classe; ?>"><a href="data/notefrais/adminfrais.php">Liste</a></td></tr>
			</table>
			<br><br>
			<table class="<?php echo $classe; ?>" border="0" width="125" cellspacing="1" cellpadding="1" align="center">
				<tr><th class="<?php echo $classe; ?>">Lieux</th></tr>
				<tr><td class="<?php echo $classe; ?>"><a href="data/shop/adminshop.php?etat=0">Rechercher</a></td></tr>
				<tr><td class="<?php echo $classe; ?>"><a href="data/shop/adminshop.php?act=add">Ajouter</a></td></tr>
			</table>
		</td>
		<td class="fond" height="50%" valign="top">
		<?php $classe = "vip" ?>
			<table class="<?php echo $classe; ?>" border="0" width="120" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Mat&eacute;riel</th>
				</tr>
		<?php $classe = "standard" ?>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="data/matos/adminmatos.php?etat=0">Rechercher</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="data/matos/adminmatos.php?act=add">Ajouter</a></td>
				</tr>
			</table>
			<br><br>
			<table class="<?php echo $classe; ?>" border="0" width="120" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Bons de commande</th>
				</tr>
		<?php $classe = "standard" ?>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="../data/boncommande/adminboncommande.php?act=listall&all=1&from=menu&period=<?php echo date("n-Y")?>">Liste</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="../data/boncommande/adminboncommande.php?act=add&liv=int&all=1&from=menu">Ajouter</a></td>
				</tr>
			</table>
			<br><br>

			<table class="<?php echo $classe; ?>" border="0" width="120" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Stock</th>
				</tr>
				<?php $classe = "vip" ?>
				<tr>
					<th class="<?php echo $classe; ?>">Famille</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="data/stock/adminstock.php?act=famillesearch">Rechercher</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="data/stock/adminstock.php?act=familleadd">Nouveau</a></td>
				</tr>
				<tr>
					<th class="<?php echo $classe; ?>">Unit&eacute;s</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="data/stock/adminstock.php?act=unitsearch">Rechercher</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="data/stock/adminstock.php?act=unitsearchresult&skip=out">OUT</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="data/stock/adminstock.php?act=stockprojectionsearch">Projection</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="data/stock/adminstock.php?act=build">notes</a></td>
				</tr>

			</table>
		</td>
		<td class="fond" height="50%" valign="top">
		<?php $classe = "standard" ?>
			<table class="<?php echo $classe; ?>" border="0" width="125" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Fournisseurs</th>
				</tr>
				<tr>
					<th class="<?php echo $classe; ?>">
						<form name="ffsupplier" action="<?php echo NIVO ?>data/supplier/adminsupplier.php" method="post" accept-charset="utf-8">
							<input type="hidden" name="act" value="show">
							<input type="text" name="idsupplier" value="" id="sname" size="18" title="Entrez le début d'un nom de société" style="text-align:center;">
						</form>
					</th>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="data/supplier/adminsupplier.php?act=search">Rechercher</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="data/supplier/adminsupplier.php?act=add">Ajouter</a></td>
				</tr>
				<tr>
					<td class="<?php echo $classe; ?>"><a href="data/supplier/adminsupplier.php?act=bdc&period=<?php echo date("Ym"); ?>">Listing commandes</a></td>
				</tr>
			</table>
			<br><br>
			<table class="<?php echo $classe; ?>" border="0" width="120" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Web Site</th>
				</tr>
				<tr><td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>www/news/adminnews.php">News</a></td></tr>
				<tr><td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>www/events/adminevents.php">Events</a></td></tr>
				<tr><td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>mod/webnewspeople/adminwebnewspeople.php">News people</a></td></tr>
				<tr><td class="<?php echo $classe; ?>"><a href="<?php echo NIVO ?>mod/webprojet/adminwebprojet.php">Projets Web</a></td></tr>
				</table>
				<br><br>
				<table class="<?php echo $classe; ?>" border="0" width="120" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Facturation</th>
				</tr>
				<tr><td class="<?php echo $classe; ?>"><a href="<?php echo NIVO.'admin/'; ?>factures/adminfac.php?act=list&from=menu"  title="Réimprimer, rechercher des factures">Factures</a></td></tr>
				<tr><td class="<?php echo $classe; ?>"><a href="<?php echo NIVO.'admin/'; ?>notecredit/adminnc.php?act=list&from=menu" title="Réimprimer, rechercher des notes de crédit">Notes Crédit</a></td></tr>
			</table>
		</td>
	</tr>
</table>
</div>
<script type="text/javascript" charset="utf-8">
	function formatResult(row) {return row[0];}
	function formatItem(row) {return "("+row[0]+") "+row[1];}
	function formatItemPeople(row) {return "("+row[2]+") "+row[1];}

	$("input#pname").autocomplete("<?php echo Conf::read('Env.urlroot') ?>query/peoplebyname.php?mode=full", {
		inputClass: 'autocomp',
		width: 250,
		minChars: 2,
		formatItem: formatItemPeople,
		formatResult: formatResult,
		delay: 200
	});

	// client FF
	$("input#pname").result(function(data) {
		if (data) document.ffpeople.submit();
	});

	$("input#cname").autocomplete("<?php echo Conf::read('Env.urlroot') ?>query/client.php", {
		inputClass: 'autocomp',
		width: 250,
		minChars: 2,
		formatItem: formatItem,
		formatResult: formatResult,
		delay: 200
	});

	$("input#cname").result(function(data) {
		if (data) document.ffclient.submit();
	});

	// Supplier FF
	$("input#sname").autocomplete("<?php echo Conf::read('Env.urlroot') ?>query/supplier.php", {
		inputClass: 'autocomp',
		width: 200,
		minChars: 2,
		formatItem: formatItem,
		formatResult: formatResult,
		delay: 200
	});

	$("input#sname").result(function(data) {
		if (data) document.ffsupplier.submit();
	});

	// Animation mission FF
	$("input#pidanimation").autocomplete("<?php echo Conf::read('Env.urlroot') ?>query/animation_mission.php", {
		inputClass: 'autocomp',
		width: 200,
		minChars: 2,
		formatItem: formatItem,
		formatResult: formatResult,
		delay: 200
	});

	$("input#pidanimation").result(function(data) {
		if (data) document.ffidanimation.submit();
	});
</script>