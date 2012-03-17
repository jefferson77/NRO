<?php
################### Code PHP ########################
if (!empty($did)) {
	$detail = new db();
	$detail->inline("SELECT * FROM `merchduplic` WHERE `idmerch` = $did");
	$infos = mysql_fetch_array($detail->result) ; 
}
################### Fin Code PHP ########################
?>
<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif';?>" method="post">
	<input type="hidden" name="idmerch" value="<?php echo $did;?>"> 

<?php #  Menu de gauche ?>
<div id="leftmenu">
	<table border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
		<tr><td>ID Merch</td></tr>
		<tr><td><?php echo $infos['idmerch']; ?></td></tr>
		<tr><td>Semaine</td></tr>
		<tr><td><?php echo $infos['weekm']; ?></td></tr>
	</table>
</div>
<?php #  Corps de la Page ?>
<div id="infozone">
<!-- INFO GENERALES --> 
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
		<tr>
			<th class="blanc">
				&nbsp;
			</th>
		</tr>
		<tr>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="left" width="98%">
					<tr>
						<th class="vip" width="100">
							<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$did.'&sel=agent';?>">Assistant</a>
						</th>
						<?php 
						# recherche client et cofficer
						if (!empty($infos['idagent'])) {
							$idagent=$infos['idagent'];
							$detail1 = new db();
							$detail1->inline("SELECT * FROM `agent` WHERE `idagent`=$idagent");

							$infosagent = mysql_fetch_array($detail1->result) ; 
						}
						?>
						<td>
							<?php echo $infosagent['prenom']; ?> <?php echo $infosagent['nom']; ?>
						</td>
					</tr>
					<tr>
						<th class="blanc">
							&nbsp;
						</th>
					</tr>
					<tr>
						<th class="vip" width="100">
							<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$did.'&sel=client';?>">client</a>
						</th>
						<?php 
						# recherche client et cofficer
						if (!empty($infos['idclient'])) {
							$idclient=$infos['idclient'];
							$detail3 = new db();
							$detail3->inline("SELECT * FROM `client` WHERE `idclient`=$idclient");

							$infosclient = mysql_fetch_array($detail3->result) ; 
							
							$idcofficer=$infos['idcofficer'];
							$detail4 = new db();
							$detail4->inline("SELECT * FROM `cofficer` WHERE `idcofficer`=$idcofficer");
							$infosofficer = mysql_fetch_array($detail4->result) ; 
						}
						?>
						<td>
							(<?php echo $infosclient['codeclient']; ?>) <?php echo $infosclient['societe']; ?>
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td>
							<?php echo $infosofficer['qualite'].' '.$infosofficer['onom'].' '.$infosofficer['oprenom']; ?>
						</td>
					</tr>
					<tr>
						<th class="blanc">
							&nbsp;
						</th>
					</tr>
					<tr>
						<th class="vip" valign="top">
							<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$did.'&sel=lieu';?>">lieux</a>
						</th>
						<td>
						<?php 
						# recherche shop
						if (!empty($infos['idshop'])) {
							$idshop=$infos['idshop'];
							$detail5 = new db();
							$detail5->inline("SELECT * FROM `shop` WHERE `idshop`=$idshop");
							$infosshop = mysql_fetch_array($detail5->result) ; 
						}
						?>
							(<?php echo $infosshop['codeshop']; ?>) <?php echo $infosshop['societe']; ?>
						</td>
					</tr>
					<tr>
						<td>
						</td>
						<td>
							<?php echo $infosshop['adresse']; ?>
							<br>
							<?php echo $infosshop['cp'].' '.$infosshop['ville']; ?>
						</td>
					</tr>
				</table>
			</td>
			<td width="40" valign="top">
			</td>
			<td valign="top" width="200">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip" width="100" valign="top">
							<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$did.'&sel=people';?>">People</a>
						</th>
					</tr>
					<tr>
						<td>
						<?php 
							# recherche people
							if (!empty($infos['idpeople'])) {
								$idpeople=$infos['idpeople'];
								$detail6 = new db();
								$detail6->inline("SELECT * FROM `people` WHERE `idpeople`=$idpeople");
								$infospeople = mysql_fetch_array($detail6->result) ; 
							}
						?>
							(<?php echo $infospeople['codepeople']; ?>) <?php echo $infospeople['pnom']; ?> <?php echo $infospeople['pprenom']; ?>
							<br>
							tel : <?php echo $infospeople['tel']; ?>
							<br>
							<?php echo $infospeople['adresse1']; ?> <?php echo $infospeople['num1']; ?> bte <?php echo $infospeople['bte1']; ?>
							<br>
							<?php echo $infospeople['cp1']; ?> <?php echo $infospeople['ville1']; ?>
						</td>
					</tr>
				</table>
			</td>
			<td width="40" valign="top">
			</td>
			<td width="300" valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip" width="110">
							Produit / remarque
						</th>
						<td>
							<input type="text" size="35" name="produit" value="<?php echo fnbr($infos['produit']); ?>">
						</td>
					</tr>
					<tr>
						<th class="blanc">
							&nbsp;
						</th>
					</tr>
					<tr>
						<th class="vip">
							Genre
						</th>
						<td>
							<?php 
							echo '
							<select name="genre" size="1">
								<option value="Rack assistance"
							';	
								if ($infos['genre'] == 'Rack assistance') {echo 'selected';}
							echo '
								>Rack assistance</option>
								<option value="Store Check"
							';	
								if ($infos['genre'] == 'Store Check') {echo 'selected';}
							echo '
								>Store Check</option>
								<option value="EAS"
							';	
								if ($infos['genre'] == 'EAS') {echo 'selected';}
							echo '
								>EAS</option>
							</select>
							';
							?>
						</td>
					</tr>
					<tr>
						<th class="blanc">
							&nbsp;
						</th>
					</tr>
					<tr>
						<th class="vip">
							R&eacute;currence
						</th>
						<td>
							<input type="radio" name="recurrence" value="1" <?php if ($infos['recurrence'] == '1') { echo 'checked';} ?> > Oui <input type="radio" name="recurrence" value="0" <?php if ($infos['recurrence'] == '0') { echo 'checked';} ?> > Non 
						</td>
					</tr>
					<tr>
						<th class="vip">
							Remplacement
						</th>
						<td>
							<input type="radio" name="easremplac" value="1" <?php if ($infos['easremplac'] == '1') { echo 'checked';} ?> > Oui <input type="radio" name="easremplac" value="0" <?php if ($infos['easremplac'] != '1') { echo 'checked';} ?> > Non 
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th class="blanc">
				&nbsp;
			</th>
		</tr>
	</table>		
<!-- INFO PARTICULIERE -->
	<fieldset>
		<legend>
			INFO PARTICULIERE
		</legend>
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
		<tr>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<td>
						<fieldset>
							<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
								<tr>
									<th class="vip" width="100">
										Date 
									</th>
									<td>
										<input type="text" size="10" name="datem" value="<?php echo fdate($infos['datem']); ?>">
									</td>
									<th class="vip">
										Semaine 
									</th>
									<td>
										<?php echo $infos['weekm']; ?>
									</td>
								</tr>
								<tr>
									<th class="vip">
										Matin
									</th>
									<td>
										IN : <input type="text" size="5" name="hin1" value="<?php echo ftime($infos['hin1']); ?>"> OUT : <input type="text" size="5" name="hout1" value="<?php echo ftime($infos['hout1']); ?>">
									</td>
									<th class="vip">
										Apr&egrave;s-midi
									</th>
									<td>
										IN : <input type="text" size="5" name="hin2" value="<?php echo ftime($infos['hin2']); ?>"> OUT : <input type="text" size="5" name="hout2" value="<?php echo ftime($infos['hout2']); ?>">
									</td>
								</tr>
								<tr>
									<td colspan="2">
										<?php
											$merch = new coremerch($infos['idmerch']);
											echo $merch->hprest;
										?>
										heures prest&eacute;es
									</td>
								</tr>
								<tr>
									<th class="blanc" colspan="4">
										&nbsp;
									</th>
								</tr>
								<tr>
									<th class="vip">
										Notes
									</th>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td colspan="2">
										<textarea name="note" rows="10" cols="65"><?php echo $infos['note']; ?></textarea>
									</td>
									<td valign="top">
										<table>
											<tr>
												<th class="vip">
													Livraison
												</th>
												<td>
													<input type="text" size="10" name="livraison" value="<?php echo fnbr($infos['livraison']); ?>"> Euro
												</td>
											</tr>
											<tr>
												<th class="vip">
													Divers (frais)
												</th>
												<td>
													<input type="text" size="10" name="diversfrais" value="<?php echo fnbr($infos['diversfrais']); ?>"> Euro
												</td>
											</tr>
											<tr>
												<td><br></td>
												<td><br></td>
											</tr>
											<tr>
												<th class="vip">
													Contrat encod&eacute;
												</th>
												<td>
													<input type="checkbox" name="contratencode" value="1" <?php if ($infos['contratencode'] == '1') { echo 'checked';} ?> > Oui 
												</td>
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
					<tr>
						<th class="vip">
							Km Pay&eacute;
						</th>
					</tr>
					<tr>
						<td>
							<input type="text" size="10" name="kmpaye" value="<?php echo fnbr($infos['kmpaye']); ?>"> Km
						</td>
					</tr>
					<tr>
						<th class="blanc">
							&nbsp;
						</th>
					</tr>
					<tr>
						<th class="vip">
							Km Factur&eacute;
						</th>
					</tr>
					<tr>
						<td>
							<input type="text" size="10" name="kmfacture" value="<?php echo fnbr($infos['kmfacture']); ?>"> Km
						</td>
					</tr>
					<tr>
						<th class="blanc">
							&nbsp;
						</th>
					</tr>
					<tr>
						<th class="vip">
							Frais Pay&eacute;
						</th>
					</tr>
					<tr>
						<td>
							<input type="text" size="10" name="frais" value="<?php echo fnbr($infos['frais']); ?>"> &euro;
						</td>
					</tr>
					<tr>
						<th class="blanc">
							&nbsp;
						</th>
					</tr>
					<tr>
						<th class="vip">
							Frais Factur&eacute;
						</th>
					</tr>
					<tr>
						<td>
							<input type="text" size="10" name="fraisfacture" value="<?php echo fnbr($infos['fraisfacture']); ?>"> &euro;
						</td>
					</tr>
					<tr>
						<th class="blanc">
							&nbsp;
						</th>
					</tr>
					<tr>
						<th class="vip">
							Jour Feri&eacute;
						</th>
					</tr>
					<tr>
						<td>
							<input type="text" size="5" name="ferie" value="<?php echo $infos['ferie']; ?>"> %
						</td>
					</tr>
				</table>
			</fieldset>
			</td>
		</tr>
	</table>		
	</fieldset>
</div>

<div id="infobouton">
<?php if (isset($idclient)) { ?>
	<input type="submit" name="Modifier" value="Modifier">
<?php } ?>
</div>

</form>
