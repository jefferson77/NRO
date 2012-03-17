<?php
################### Code PHP ########################
if (!empty($idmerch)) {
	$detail = new db();
	$detail->inline("SELECT * FROM `merch` WHERE `idmerch` = $idmerch");
	$infos = mysql_fetch_array($detail->result) ; 
	
	$idmerch = $infos['idmerch'];
}
################### Fin Code PHP ########################
?>
<style type="text/css">
<!--
.Style1 {
	font-size: 9px;
	color: #333333;
}
-->
</style>

<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif';?>" method="post">
	<input type="hidden" name="idmerch" value="<?php echo $idmerch;?>"> 

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
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="left" width="98%">
					<tr>
						<th class="vip" width="100">
							Assistant
						</th>
					</tr>
					<tr>
						<td>
							<?php
								$DB->inline("SELECT idagent, nom, prenom FROM `agent` WHERE isout = 'N'");
								while ($row = mysql_fetch_array($DB->result))
								{
									$agent[$row['idagent']]= $row['prenom']." ".$row['nom'];
								 } 
								 echo createSelectList('idagent',$agent,$infos['idagent'],'--');
							?>
<!-- Bouton de sélection final-->
					</select>
						</td>
					</tr>
					<tr>
						<th class="blanc">
						</th>
					</tr>
					<tr>
						<th class="vip" width="100">
							<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$idmerch.'&sel=client';?>">client</a>
						</th>
					</tr>
					<tr>
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
							(<?php echo $infosclient['codeclient']; ?>) <?php echo $infosclient['societe']; ?><br>
							<?php echo $infosofficer['qualite'].' '.$infosofficer['onom'].' '.$infosofficer['oprenom']; ?><br>
							<?php echo $infosofficer['departement']; ?>
						</td>
					</tr>
					<tr>
						<th class="blanc">
						</th>
					</tr>
					<tr>
						<th class="vip" valign="top">
							<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$idmerch.'&sel=lieu';?>">lieux</a>
						</th>
					</tr>
					<tr>
						<td>
						<?php 
						# recherche shop
						if (!empty($infos['idshop'])) {
							$idshop=$infos['idshop'];
							$detail5 = new db();
							$detail5->inline("SELECT * FROM `shop` WHERE `idshop`=$idshop");
							$infosshop = mysql_fetch_array($detail5->result) ; 
						}
						if($infosshop['glat']>0 && $infosshop['glong']>0) echo '<img src="'.STATIK.'illus/geoloc.png" alt="'.$row['glat'].','.$row['glong'].'">'; 
						echo ' '.$infosshop['societe'].' ('.$infosshop['codeshop'].')<br>
						'.$infosshop['adresse'].'<br>
						'.$infosshop['cp'].' '.$infosshop['ville'].((!empty($infosshop['fax']))?'<br>fax: '.$infosshop['fax']:''); 
						?>
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
							<?php if ($infos['idpeople'] > 0) { ### page Remove ?>
								<a href="<?php echo $_SERVER['PHP_SELF'].'?act=removepeople&idmerch='.$idmerch;?>">People</a>
							<?php } else { ### page input ?>
								<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$idmerch.'&sel=people';?>">People</a>
							<?php } ?>
						</th>
					</tr>
					<tr>
						<td>
						<?php 
							# recherche people
							if (!empty($infos['idpeople'])) {
								$idpeople=$infos['idpeople'];
								$detail6 = new db();
								$detail6->inline('SELECT people.*, IF(peoplehome=1,glat1,glat2) AS pglat, IF(peoplehome=1,glong1,glong2) AS pglong
													FROM people WHERE idpeople='.$idpeople);
								$infospeople = mysql_fetch_array($detail6->result) ; 
							}
						?>
							<?php echo $infospeople['pnom']; ?> <?php echo $infospeople['pprenom']; ?> (<?php echo $infospeople['codepeople']; ?>)
							<br>
							<?php 
							if($infospeople['pglat']>0 && $infospeople['pglong']>0) echo '<img src="'.STATIK.'illus/geoloc.png" alt="geoloc.png">&nbsp;&nbsp;';
							else echo '<a href="'.NIVO.'data/people/adminpeople.php?act=show&idpeople='.$infos['idpeople'].'&idmerch='.$idmerch.'"><img src="'.STATIK.'illus/nogeoloc.png" alt="pas de geoloc" border="0"></a>&nbsp;&nbsp;';
							 
							echo $infospeople['adresse1']; ?> <?php echo $infospeople['num1']; ?> bte <?php echo $infospeople['bte1']; ?>
							<br>
							<?php echo $infospeople['cp1']; ?> <?php echo $infospeople['ville1']; ?>
							<br>
							Tel : <?php echo $infospeople['tel']; ?>
							<br>
							Gsm : <?php echo $infospeople['gsm']; ?>
							<br>
							Email : <a href="mailto:<?php echo $infospeople['email']; ?>"><?php echo $infospeople['email']; ?></a>
						</td>
					</tr>
				</table>
<?php
###  Cadre DBB

if (!empty($infos['idpeople']) and ($infos['datem'] != '0000-00-00') and !empty($infos['datem'])) {
	#echo '<img src="'.NIVO.'mod/hh/hhillu.php?idpeople='.$infos['idpeople'].'&date='.$infos['datem'].'&mission=AN'.$infos['idanimation'].'" alt="" border="0">';
	
	$ht = new hh(); 
	$ht->hhtable ($infos['idpeople'], $infos['datem'], $infos['datem']) ;
	
	$sum = 0;
	
	if (count($ht->prtab[$infos['datem']]) > 1) {
		foreach ($ht->prtab[$infos['datem']] as $val) {
			$sum += $val['hh'];
		}
			$sum = sprintf("%024.0f",$sum);
			#echo $sum;
	
		if(preg_match('/[2-9]/', $sum)) {
			## Tableau Rouge : Double booking
			echo '<div style="padding: 2px;background-color: #F60;border-color: red; border-width: 2px; border-style: solid;width: 250px; display: block;">';
			echo '<b>Double Booking</b><br>';
		foreach ($ht->prtab[$infos['datem']] as $val) {
			if ($val['idmission'] != $infos['idmerch']) {
			
				switch($val['secteur']) {
					case"ME":
						$sql = "SELECT hin1, hout1, hin2, hout2 FROM merch WHERE idmerch = ".$val['idmission']; $ch = new db(); $ch->inline($sql); $res = mysql_fetch_array($ch->result);
						$period = ftime($res['hin1'])." - ".ftime($res['hout1']).' | '.ftime($res['hin2'])." - ".ftime($res['hout2']);
					break;
					case"AN":
						$sql = "SELECT hin1, hout1, hin2, hout2 FROM animation WHERE idanimation = ".$val['idmission']; $ch = new db(); $ch->inline($sql); $res = mysql_fetch_array($ch->result);
						$period = ftime($res['hin1'])." - ".ftime($res['hout1']).' | '.ftime($res['hin2'])." - ".ftime($res['hout2']);
					break;
					case"VI":
						$sql = "SELECT vipin, vipout FROM vipmission WHERE idvip = ".$val['idmission']; $ch = new db(); $ch->inline($sql); $res = mysql_fetch_array($ch->result);
						$period = ftime($res['vipin'])." - ".ftime($res['vipout']);
					break;
				}	
	
				echo $val['secteur'].' '.$val['idmission'].' | '.$period.'<br>';
			}
		}
			echo '</div>';
		} else {
			## Tableau vert (pas de chevauchement
			echo '<div style="padding: 2px;background-color: #6C3;border-color: green; border-width: 2px; border-style: solid;width: 250px; display: block;">';
			echo '<b>Autre Mission le meme jour</b><br>';
		foreach ($ht->prtab[$infos['datem']] as $val) {
			if ($val['idmission'] != $infos['idmerch']) {
			
				switch($val['secteur']) {
					case"ME":
						$sql = "SELECT hin1, hout1, hin2, hout2 FROM merch WHERE idmerch = ".$val['idmission']; $ch = new db(); $ch->inline($sql); $res = mysql_fetch_array($ch->result);
						$period = ftime($res['hin1'])." - ".ftime($res['hout1']).' | '.ftime($res['hin2'])." - ".ftime($res['hout2']);
					break;
					case"AN":
						$sql = "SELECT hin1, hout1, hin2, hout2 FROM animation WHERE idanimation = ".$val['idmission']; $ch = new db(); $ch->inline($sql); $res = mysql_fetch_array($ch->result);
						$period = ftime($res['hin1'])." - ".ftime($res['hout1']).' | '.ftime($res['hin2'])." - ".ftime($res['hout2']);
					break;
					case"VI":
						$sql = "SELECT vipin, vipout FROM vipmission WHERE idvip = ".$val['idmission']; $ch = new db(); $ch->inline($sql); $res = mysql_fetch_array($ch->result);
						$period = ftime($res['vipin'])." - ".ftime($res['vipout']);
					break;
				}	
	
				echo $val['secteur'].' '.$val['idmission'].' | '.$period.'<br>';
			}
		}		echo '</div>';
		}
	}
}


### / Cadre DBB

?>				
			</td>
			<td width="40" valign="top">
			</td>
			<td width="350" valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip" width="150">
							Produit / remarque
						</th>
						<td>
							<input type="text" size="35" name="produit" value="<?php echo $infos['produit']; ?>">
						</td>
					</tr>
					<tr>
						<th class="blanc">&nbsp;
							
						</th>
					</tr>
					<tr>
						<th class="vip">
							Bon commande
						</th>
						<td>
							<input type="text" size="20" name="boncommande" value="<?php echo $infos['boncommande']; ?>">
						</td>
					</tr>
					<tr>
						<th class="blanc">&nbsp;
							
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
						<th class="blanc">&nbsp;
							
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
							<?php $Remplacement = 'Remplacement'; if ($infos['genre'] == 'EAS') {$Remplacement = 'Contrat journalier';} echo $Remplacement; ?>
						</th>
						<td>
							<input type="radio" name="easremplac" value="1" <?php if ($infos['easremplac'] == '1') { echo 'checked';} ?> > Oui <input type="radio" name="easremplac" value="0" <?php if ($infos['easremplac'] != '1') { echo 'checked';} ?> > Non 
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<th class="blanc">&nbsp;
				
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
							<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
								<tr>
									<th class="vip" width="100">
										Date 
									</th>
									<td>
										<input type="text" size="10" name="datem" value="<?php echo fdate($infos['datem']); ?>">
									</td>
									<td width="40">&nbsp;</td>
									<th class="vip">
										Semaine 
									</th>
									<td>
										<?php echo $infos['weekm']; ?>
									</td>
								</tr>
								<tr>
									<th class="blanc" colspan="5">&nbsp;
										
									</th>
								</tr>
								<tr>
									<th class="vip">
										Matin
									</th>
									<td>
										IN : <input type="text" size="5" name="hin1" value="<?php echo ftime($infos['hin1']); ?>"> OUT : <input type="text" size="5" name="hout1" value="<?php echo ftime($infos['hout1']); ?>">
									</td>
									<td width="40">&nbsp;</td>
									<th class="vip">
										Apr&egrave;s-midi
									</th>
									<td>
										IN : <input type="text" size="5" name="hin2" value="<?php echo ftime($infos['hin2']); ?>"> OUT : <input type="text" size="5" name="hout2" value="<?php echo ftime($infos['hout2']); ?>">
									</td>
								</tr>
								<tr>
									<td colspan="5">
										<?php
											$merch = new coremerch($infos['idmerch']);
											echo $merch->hprest;
										?>
										heures prest&eacute;es
									</td>
								</tr>
								<tr>
									<th class="blanc" colspan="5">&nbsp;
										
									</th>
								</tr>
								<tr>
									<th class="vip">Notes</th>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td colspan="2" valign="top">
										<textarea name="note" rows="3" cols="87"><?php echo stripslashes($infos['note']); ?></textarea>
									<br>
									<br>
									
									
				<table class="standard" border="0" cellspacing="0" cellpadding="0" align="center" width="99%">
					<tr height="25">
						<td class="navbarleft"></td>
						<td class="navbar">
							
							<?php if($idpeople>0) $nbr = $DB->getOne("SELECT COUNT(*) FROM matos WHERE idpeople =".$idpeople);
							//$nbr = mysql_num_rows($DB->result);
							echo '<a href='.NIVO.'data/people/matos.php?idpeople='.$idpeople.'&s=0" target="matos"><span id="nbrmatos">Matos('.$nbr.')</span></a>' ?>
						</td>
						<td class="navbarmid"></td>
						<td class="navbar">
						<?php 
						if(!empty($_REQUEST['idmerch']))
						{
							$count = $DB->getOne("SELECT COUNT(*) FROM bondecommande WHERE idjob = ".$_REQUEST['idmerch']." and etat = 'in'");
						}
						else // peut être vide si on crée une nouvelle mission
						{
							$count = 0;
						}
						echo '<a href="'.NIVO.'data/boncommande/adminboncommande.php?act=list&secteur=ME&idmerch='.$infos['idmerch'].'" target="matos">Bon de commande <span id="nbrbdc">('.$count.')</span></a>'; ?>
						</td>
						<td class="navbarmid"></td>
						<td class="navbar">
						<?php 
						if(!empty($_REQUEST['idmerch']))
						{
							$nbr = $DB->getOne("SELECT COUNT(id) FROM bonlivraison WHERE idjob = ".$_REQUEST['idmerch']." and etat = 'in'");
						}
						else // peut être vide si on crée une nouvelle mission
						{
							$nbr = 0;
						}
						echo '<a href="'.NIVO.'data/bonlivraison/adminbonlivraison.php?act=list&secteur=ME&idmerch='.$infos['idmerch'].'" target="matos">Bon de livraison <span id="nbrbdl">('.$nbr.')</span></a>'; ?>
						</td>
						<td class="navbarmid"></td>
						
					</tr>
					<tr>
						<td valign="top" colspan="17" bgcolor="#304052">
							<iframe frameborder="0" marginwidth="0" marginheight="0" name="matos" src="<?php echo NIVO.'data/people/matos.php?idpeople='.$infos['idpeople'];?>" width="100%" height="180" align="top">Marche pas les IFRAMES !</iframe> 
						</td>
					</tr>
				</table>
									</td>
									<td width="40">&nbsp;</td>
									<td valign="top" colspan="2">
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
											
											<?php if(!empty($infos['bdcm'])){ ?>
											<tr>
												<th class="vip">
													Bon de commande magasin
												</th>
												<td>
													<?php echo $infos['bdcm']; ?>
												</td>
											</tr>
											<?php } ?>
											
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
											<tr>
												<th class="vip">
													Rapport encod&eacute;
												</th>
												<td>
													<input type="checkbox" name="rapportencode" value="1" <?php if ($infos['rapportencode'] == '1') { echo 'checked';} ?> > Oui 
												</td>
											</tr>
											<tr>
												<th class="blanc">&nbsp;
													
												</th>
											</tr>
											<tr>
												<th bgcolor="#FF9933" >
													Contrat disponible Online :
												</th>
												<td bgcolor="#FF9933" >
													<?php 
													echo '<input type="radio" name="webdoc" value="yes" '; if (strchr($infos['webdoc'], yes)) { echo 'checked';} echo'> oui';
													echo '<input type="radio" name="webdoc" value="no" '; if (strchr($infos['webdoc'], no)) { echo 'checked';} echo'> non';
													?>
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
					  <th colspan="2" class="vip">Auto Km </th>
				  </tr>
					<tr>
					  <td> Pay&eacute;</td>
						<td nowrap><input type="text" size="3" name="kmpaye" value="<?php echo fnbr($infos['kmpaye']); ?>">
Km
						</td>
					</tr>
					<tr>
					  <td>Factur&eacute;</td>
						<td nowrap><input type="text" size="3" name="kmfacture" value="<?php echo fnbr($infos['kmfacture']); ?>">
Km 
						</td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
						<td>&nbsp;				
						</td>
					</tr>
					<?php
					## Notes de frais ##	
					$note = $DB->getRow('SELECT * FROM notefrais WHERE idmission='.$idmerch.' AND secteur="ME" LIMIT 1');			
					?>

					<tr>
					  <th colspan="2" class="vip">
					  <input type="CHECKBOX" name="nfraisyn" value="y" <?php if ($note['idnfrais'] > 0) { echo 'checked';} ?>>	
					  Note de frais (n°<?php echo $note['idnfrais']; ?>)
					  </th>
					</tr>
					<tr>
					  <td>Pay&eacute;</td>
					  <td nowrap>
						<input type="text" size="3" name="montantpaye" value="<?php echo fnbr0($note['montantpaye']); ?>">					
						&euro;</td>
					</tr>
					<tr>
					  <td><span class="vip"> Factur&eacute; </span></td>
						<td nowrap>
						<input type="text" size="3" name="montantfacture" value="<?php echo fnbr0($note['montantfacture']); ?>">
						&euro;</td>
					</tr>
					<tr>
					  <td colspan="2">Intitul&eacute;:<br>
<input name="intitule" type="text" size="20" value="<?php echo $note['intitule']; ?>"></td>
					</tr>
					<tr>
					  <td colspan="2">Description:<br>
				      <textarea name="description" cols="16" rows="2"> <?php echo $note['description']; ?></textarea></td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
						<td>&nbsp;
							
						</td>
					</tr>
					<tr>
					  <th colspan="2" class="vip"> Jour Feri&eacute;					  </th>
					</tr>
					<tr align="center">
					  <td colspan="2">
						  <input type="text" size="5" name="ferie" value="<?php echo $infos['ferie']; ?>">%</td>
					</tr>
				</table>
			</fieldset>
				<br>	
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip">
							<a href="javascript:;" onClick="OpenBrWindow('contact.php?idmerch=<?php echo $idmerch ;?>','Main','scrollbars=yes,status=yes,resizable=yes','900','300','true')">Contact</a>
						</th>
					</tr>
				</table>
			</td>
		</tr>
	</table>		
	</fieldset>
</div>

<div id="infobouton">
	<input type="submit" name="Modifier" value="Modifier">
</div>

</form>
