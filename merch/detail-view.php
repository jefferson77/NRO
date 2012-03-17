<?php
################### Code PHP ########################
if (!empty($_REQUEST['idmerch'])) {
	if ($_REQUEST['table'] == 'zoutmerch') {
		$morefields = 'me.raisonout, me.noteout,';
		$table = 'zoutmerch';
	} else {
		$table = 'merch';
	}

	$infos = $DB->getRow("SELECT 
			me.boncommande, me.contratencode, me.datem, me.diversfrais, me.easremplac, me.facnum, me.ferie, me.frais, me.fraisfacture, me.genre, me.hin1, me.hin2, 
				me.hout1, me.hout2, me.idagent, me.idclient, me.idcofficer, me.idmerch, me.idpeople, me.idshop, me.kmfacture, me.kmpaye, me.livraison, me.note, me.produit, me.recurrence, me.weekm,
				".$morefields."
			a.nom, a.prenom,
			c.codeclient, c.societe,
			o.onom, o.oprenom, o.qualite,
			s.adresse, s.codeshop, s.cp, s.glat, s.glong, s.societe, s.ville,
			p.adresse1, p.bte1, p.codepeople, p.cp1, p.email, p.gsm, p.num1, p.pnom, p.pprenom, p.tel, p.ville1
			
		FROM ".$table." me
			LEFT JOIN agent a ON me.idagent = a.idagent
			LEFT JOIN client c ON me.idclient = c.idclient
			LEFT JOIN cofficer o ON me.idcofficer = o.idcofficer
			LEFT JOIN shop s ON me.idshop = s.idshop
			LEFT JOIN people p ON me.idpeople = p.idpeople
		WHERE `idmerch` = ".$_REQUEST['idmerch']);
		
		if ($table == 'merch') $merch = new coremerch($infos['idmerch']);
}
?>
<div id="leftmenu">
	<table border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
		<tr><td>ID Merch</td></tr>
		<tr><td><?php echo $infos['idmerch']; ?></td></tr>
		<tr><td>Semaine</td></tr>
		<tr><td><?php echo $infos['weekm']; ?></td></tr>
		<tr><td>Facture n&deg;</td></tr>
		<tr><td><?php echo $infos['facnum']; ?></td></tr>
	</table>
</div>
<div id="infozone">
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
		<tr>
			<th class="blanc" colspan="3"><?php if (!empty($infos['raisonout']) or !empty($infos['noteout'])) echo '<div class="warning">EFFACEE : '.$raisonout[$infos['raisonout']].' , '.$infos['noteout'].'</div>'; ?></th>
		</tr>
		<tr>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="left" width="98%">
					<tr>
						<th class="vip" width="100">Assistant</th>
						<td><?php echo $infos['prenom']; ?> <?php echo $infos['nom']; ?></td>
					</tr>
					<tr>
						<th class="blanc">&nbsp;</th>
					</tr>
					<tr>
						<th class="vip" width="100">client</th>
						<td>(<?php echo $infos['codeclient']; ?>) <?php echo $infos['societe']; ?></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo $infos['qualite'].' '.$infos['onom'].' '.$infos['oprenom']; ?></td>
					</tr>
					<tr>
						<th class="blanc">&nbsp;</th>
					</tr>
					<tr>
						<th class="vip" valign="top">lieux</th>
						<td>
						<?php 
						if($infos['glat']>0 && $infos['glong']>0) echo '<img src="'.STATIK.'illus/geoloc.png" alt="'.$infos['glat'].','.$infos['glong'].'">'; 
						echo ' '.$infos['societe'].' ('.$infos['codeshop'].')'; ?>
						</td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo $infos['adresse']; ?><br><?php echo $infos['cp'].' '.$infos['ville']; ?></td>
					</tr>
				</table>
			</td>
			<td valign="top" width="200">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip" width="100" valign="top">People</th>
					</tr>
					<tr>
						<td>
							(<?php echo $infos['codepeople']; ?>) <?php echo $infos['pnom']; ?> <?php echo $infos['pprenom']; ?>
							<br>
							<?php echo $infos['adresse1']; ?> <?php echo $infos['num1']; ?> bte <?php echo $infos['bte1']; ?>
							<br>
							<?php echo $infos['cp1']; ?> <?php echo $infos['ville1']; ?>
							<br>
							Tel : <?php echo $infos['tel']; ?>
							<br>
							Gsm : <?php echo $infos['gsm']; ?>
							<br>
							Email : <a href="mailto:<?php echo $infos['email']; ?>"><?php echo $infos['email']; ?></a>
						</td>
					</tr>
				</table>
			</td>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip" width="110">Produit / remarque</th>
						<td><?php echo $infos['produit']; ?></td>
					</tr>
					<tr>
						<th class="blanc">&nbsp;</th>
					</tr>
					<tr>
						<th class="vip">Bon commande</th>
						<td><?php echo $infos['boncommande']; ?></td>
					</tr>
					<tr>
						<th class="blanc">&nbsp;</th>
					</tr>
					<tr>
						<th class="vip">Genre</th>
						<td><?php echo $infos['genre']; ?></td>
					</tr>
					<tr>
						<th class="blanc">&nbsp;</th>
					</tr>
					<tr>
						<th class="vip">R&eacute;currence</th>
						<td><?php if ($infos['recurrence'] == '1') { echo 'Oui';} ?><?php if ($infos['recurrence'] == '0') { echo 'Non';} ?>  </td>
					</tr>
					<tr>
						<th class="vip"><?php $Remplacement = 'Remplacement'; if ($infos['genre'] == 'EAS') {$Remplacement = 'Contrat journalier';} echo $Remplacement; ?></th>
						<td><?php if ($infos['easremplac'] == '1') { echo 'Oui';} ?><?php if ($infos['easremplac'] != '1') { echo 'Non';} ?>  </td>
					</tr>
				</table>
			</td>
		</tr>
	</table>		
	<fieldset>
		<legend>INFO PARTICULIERE</legend>
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
		<tr>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<td>
						<fieldset>
							<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
								<tr>
									<th class="vip" width="100">Date </th>
									<td><?php echo fdate($infos['datem']); ?></td>
									<th class="vip">Semaine </th>
									<td><?php echo $infos['weekm']; ?></td>
								</tr>
								<tr>
									<th class="vip">Matin</th>
									<td>IN : <?php echo ftime($infos['hin1']); ?> OUT : <?php echo ftime($infos['hout1']); ?></td>
									<th class="vip">Apr&egrave;s-midi</th>
									<td>IN : <?php echo ftime($infos['hin2']); ?> OUT : <?php echo ftime($infos['hout2']); ?></td>
								</tr>
								<tr>
									<td colspan="2"><?php echo $merch->hprest; ?> heures prest&eacute;es</td>
								</tr>
								<tr>
									<th class="blanc" colspan="4">&nbsp;</th>
								</tr>
								<tr>
									<th class="vip">Notes</th>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td colspan="2"><?php echo $infos['note']; ?></td>
									<td valign="top">
										<table>
											<tr>
												<th class="vip">Livraison</th>
												<td><?php echo fnbr($infos['livraison']); ?> Euro</td>
											</tr>
											<tr>
												<th class="vip">Divers (frais)</th>
												<td><?php echo fnbr($infos['diversfrais']); ?> Euro</td>
											</tr>
											<tr>
												<td><br></td>
												<td><br></td>
											</tr>
											<tr>
												<th class="vip">Contrat encod&eacute;</th>
												<td><?php if ($infos['contratencode'] == '1') { echo 'Oui';} else { echo 'Non';} ?> </td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</fieldset>
						</td>
					</tr>
				</table>		
			</td>
			<td width="115" valign="top">
		<fieldset>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr><th class="vip">Km Pay&eacute;</th></tr>
					<tr><td><?php echo fnbr($infos['kmpaye']); ?> Km</td></tr>
					<tr><th class="blanc">&nbsp;</th></tr>
					<tr><th class="vip">Km Factur&eacute;</th></tr>
					<tr><td><?php echo fnbr($infos['kmfacture']); ?> Km</td></tr>
					<tr><th class="blanc">&nbsp;</th></tr>
					<tr><th class="vip">Frais Pay&eacute;</th></tr>
					<tr><td><?php echo fnbr($infos['frais']); ?> &euro;</td></tr>
					<tr><th class="blanc">&nbsp;</th></tr>
					<tr><th class="vip">Frais Factur&eacute;</th></tr>
					<tr><td><?php echo fnbr($infos['fraisfacture']); ?> &euro;</td></tr>
					<tr><th class="blanc">&nbsp;</th></tr>
					<tr><th class="vip">Jour Feri&eacute;</th></tr>
					<tr><td><?php echo $infos['ferie']; ?> %</td></tr>
				</table>
			</fieldset>
			</td>
		</tr>
	</table>		
	</fieldset>
</div>
