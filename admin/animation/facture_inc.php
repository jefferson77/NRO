<?php
		$recherche0 = "SELECT
			an.idanimation, an.datem, an.reference, an.genre, an.idpeople, an.hin1, an.hout1, an.hin2, an.hout2,
			an.kmpaye, an.kmfacture, an.frais, an.livraisonpaye, an.livraisonfacture,
			an.produit, an.facturation AS anfacturation,
			an.ferie,
			j.boncommande, j.idclient, j.idcofficer,
			a.nom AS nomagent, a.prenom,
			c.adresse, c.cp, c.ville, c.tvheure05, c.tvheure6, c.tvnight, c.tvkm,
			c.codeclient, c.societe AS clsociete, c.facturation AS clfacturation, c.factureofficer,
			s.codeshop, s.societe AS ssociete, s.ville AS sville,
			o.onom , o.oprenom , o.tel, o.fax, o.qualite, o.langue,
			p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational,
			ma.idanimation AS maidanimation, ma.idanimmateriel, ma.stand, ma.gobelet, ma.serviette, ma.four, ma.curedent, ma.cuillere, ma.rechaud, ma.autre
			FROM animation an
			LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
			LEFT JOIN agent a ON j.idagent = a.idagent
			LEFT JOIN client c ON j.idclient = c.idclient
			LEFT JOIN cofficer o ON j.idcofficer  = o.idcofficer
			LEFT JOIN people p ON an.idpeople = p.idpeople
			LEFT JOIN shop s ON an.idshop = s.idshop
			LEFT JOIN animmateriel ma ON an.idanimation = ma.idanimation
		";

		$quid = "WHERE an.facturation = '6' AND ";
		$sort = 'c.idclient, an.datem';
#/##### Facturation VARIABLE GENERALES ###

### Par semaine client value="1"
					$quidsemaine .= $quid;
				# client modalite payement = 1
					$quidsemaine .= "c.facturation = '1'";
				# Date < oneweekago càd ce lundi-ci
					$quidsemaine .= " AND ";
					$quidsemaine .= "an.datem < DATE(NOW())";
				# recherche Sql :
					$recherchesemaine='
						'.$recherche0.'
						'.$quidsemaine.'
						 ORDER BY '.$sort.'
					';
#/## Par semaine client value="1"

### Par Fin de job client value="2"
					$quidjob .= $quid;
				# client modalite payement = 2
						$quidjob .= "c.facturation = '2'";
				# Date < today
						$quidjob .= " AND ";
						$quidjob .= "an.datem < DATE(NOW())";
				# recherche Sql :
					$recherchejob='
						'.$recherche0.'
						'.$quidjob.'
						 ORDER BY '.$sort.'
					';
#/## Par Fin de job client value="2"

### Par mois client value="3"
					$quidmois .= $quid;
				# client modalite payement = 3
						$quidmois .= "c.facturation = '3'";
				# Date < onemonthago
						$quidmois .= " AND ";
						$quidmois .= "an.datem < '".$onemonthago."'";
				# recherche Sql :
					$recherchemois='
						'.$recherche0.'
						'.$quidmois.'
						 ORDER BY '.$sort.'
					';
#/## Par mois client value="3"

### Par Manuel client value="5"
					$quidmanuel .= $quid;
				# client modalite payement = 5
						$quidmanuel .= "c.facturation = '5'";
				# Date < today
						$quidmanuel .= " AND ";
						$quidmanuel .= "an.datem < DATE(NOW())";
				# recherche Sql :
					$recherchemanuel='
						'.$recherche0.'
						'.$quidmanuel.'
						 ORDER BY '.$sort.'
					';
#/## Par Manuel client value="5"

###
### pour VERIF only
### Pas Par Semaine ET PAS Par Mois  : client value != "1" Et client value != "3"
					$quidverif .= $quid;
				# client modalite payement != 1
						$quidverif .= "c.facturation != '1'";
				# client modalite payement != 3
						$quidverif .= " AND ";
						$quidverif .= "c.facturation != '3'";
				# Date < twodaysago
					$quidsemaine .= " AND ";
					$quidsemaine .= "an.datem < '".date ("Y-m-d", strtotime("-2 days"))."'";
				# recherche Sql :
					$rechercheverif='
						'.$recherche0.'
						'.$quidverif.'
						 ORDER BY '.$sort.'
					';

		$_SESSION['jobquid'] = $quid;
		$_SESSION['jobquod'] = $quod;
		$_SESSION['jobsort'] = $sort;
?>
<div id="centerzone">
	<fieldset>
		<legend><b>Animation : factoring</b></legend>
		<b>FACTURES A IMPRIMER</b><br>
	</fieldset>
	<br>
	<br>Facturation par semaine (avant ce lundi <?php echo fdate($oneweekago); ?>) :
	<?php
		$i = 0;
		$listingsemaine = new db();
		$listingsemaine->inline("$recherchesemaine;");
		while ($row = mysql_fetch_array($listingsemaine->result)) {
				$i++;
			}
		echo $i;

		if ($i != 0) {
		?>
			<div align="center">
				<form action="<?php echo NIVO ?>print/anim/facture/facture.php" method="post" target="popup" onsubmit="OpenBrWindow('about:blank','popup','scrollbars=yes,status=yes,resizable=yes','600','600','true')" >
					<input type="submit" class="printer_48">
					<input type="hidden" name="action" value="<?php echo $recherchesemaine; ?>">
				</form>
			</div>
		<?php
		}
	?>
	<br>
	<hr>
	<hr>
	<br>Facturation par mois (avant le <?php echo fdate($onemonthago); ?>) :
	<?php
		$i = 0;
		$listingmois = new db();
		$listingmois->inline("$recherchemois;");
			while ($row = mysql_fetch_array($listingmois->result)) {
				$i++;
			}
		echo $i;

		if ($i != 0) {
		?>
			<div align="center">
				<form action="<?php echo NIVO ?>print/anim/facture/facture.php" method="post" target="popup" onsubmit="OpenBrWindow('about:blank','popup','scrollbars=yes,status=yes,resizable=yes','600','600','true')" >
					<input type="submit" class="printer_48">
					<input type="hidden" name="action" value="<?php echo $recherchemois; ?>">
					<input type="hidden" name="factu" value="mois">
				</form>
			</div>
		<?php
		}
	?>
	<br>
	<hr>
	<hr>
	<br>VERIFICATION : <br>
		Facturation ni par semaine ni par mois (avant le <?php echo fdate($onemonthago); ?>) :
	<?php
		$i = 0;
		$listingverif = new db();
		$listingverif->inline("$rechercheverif;");
			while ($row = mysql_fetch_array($listingverif->result)) {
				$i++;
			}
		echo $i;
		if ($i != 0) {
		?>
			<br>
			<br>
			<Font color="red" size="4">ATTENTION : </font><Font color="white" size="4"><?php echo $i; ?> jobs avec des clients en facturation autre
		</font>
		<?php
		}
	?>
	<br>
	<hr>
</div>
