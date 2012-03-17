<?php
################### Code PHP ########################
	$dist = CalcDeplacement('AN', $idanimation);

	$infos = $DB->getRow("SELECT 
		a.* ,
		j.idclient, j.idcofficer,
		c.codeclient, c.societe as csociete, 
		o.qualite as oqualite, o.onom, o.oprenom, o.langue as olangue,
		s.idshop, s.codeshop, s.societe as ssociete, s.adresse as sadresse, s.cp as scp, s.ville as sville, s.glat as sglat, s.glong as sglong,
		p.codepeople, p.pnom, p.pprenom, p.gsm as gsm, p.adresse1, p.num1, p.bte1, p.cp1, p.ville1, p.adresse2, p.num2, p.bte2, p.cp2, p.ville2, p.glat1 as pglat1, p.glong1 as pglong1, p.glat2 as pglat2, p.glong2 as pglong2,
		nf.idnfrais, nf.montantpaye, nf.montantfacture, nf.intitule, nf.description
	FROM animation a
		LEFT JOIN animjob j ON a.idanimjob = j.idanimjob
		LEFT JOIN shop s ON a.idshop = s.idshop
		LEFT JOIN people p ON a.idpeople = p.idpeople
		LEFT JOIN client c ON j.idclient = c.idclient
		LEFT JOIN cofficer o ON j.idcofficer = o.idcofficer
		LEFT JOIN notefrais nf ON a.idanimation = nf.idmission AND nf.secteur = 'AN'
	WHERE `idanimation` = ".$idanimation);

##> historic si en facturation ###
	$disable = (($infos['facturation'] > 1) and ($infos['facturation'] != 3))?' disabled':'';

?>
<?php if ($disable != 'disabled') { ?>
	<style type="text/css">
		.Style5 {color: #666666; font-size: 9px; }
	</style>
	<form action="?act=modifmission" method="post">
		<input type="hidden" name="idanimation" value="<?php echo $idanimation;?>"> 
		<input type="hidden" name="idanimjob" value="<?php echo $idanimjob;?>"> 
<?php }  ?>
<div id="centerzonelarge">
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="99%">
		<tr>
			<td>
				<table class="standard" border="0" cellspacing="2" cellpadding="2" align="center" width="99%">
					<tr>
						<th class="vip" width="100">Code Anim</th>
						<td class="lid"><?php echo $infos['idanimation']; ?></td>
						<td></td>
						<td></td>
						<th class="vip">Produit promo</th>
						<td><input type="text" size="20" name="produit" value="<?php echo $infos['produit']; ?>" <?php echo $disable; ?>></td>
						<th class="vip">facturation</th>
						<td width="100">( <?php echo $infos['facturation']; ?> ) <?php echo $infos['facnum']; ?></td>
					</tr>
					<tr>
						<th class="vip">
							<?php if ($disable == 'disabled') { ### page view ?>
								lieux
							<?php } else { ### page input ?>
								<a href="<?php echo $_SERVER['PHP_SELF'].'?act=selectlieu&idanimation='.$idanimation.'&idanimjob='.$idanimjob;?>">lieux</a>
							<?php } ?>
						</th>
						<td class="lid"><?php echo $infos['codeshop']; ?></td>
						<td>
							<?php echo $infos['ssociete']; ?>
						</td>
						<td colspan="3">
							<?php if (($infos['sglat'] > 0) and ($infos['sglong'] > 0)) echo '<img src="'.STATIK.'illus/geoloc.png" alt="geoloc.png">&nbsp;&nbsp;';
							else echo '<a href="'.NIVO.'data/shop/adminshop.php?idshop='.$infos['idshop'].'&idanimation='.$idanimation.'&idanimjob='.$idanimjob.'"><img src="'.STATIK.'illus/nogeoloc.png" border="0" alt="pas de geoloc"></a>&nbsp;&nbsp;';
							echo $infos['sadresse'].' '.$infos['scp'].' '.$infos['sville']; ?>
						</td>
						<th class="vip">
							Contrat Encod&eacute;
						</th>
						<td>
							<input type="checkbox" name="ccheck" value="Y" <?php echo ($infos['ccheck'] == 'Y')?'checked':''; ?>>
						</td>
					</tr>
					<tr>
						<th class="vip">
							<?php if ($disable == 'disabled') { ### page view ?>
								People
							<?php } else { ### page input ?>
								<?php if ($infos['idpeople'] > 0) { ### page Remove ?>
									<a href="<?php echo $_SERVER['PHP_SELF'].'?act=removepeople&idanimation='.$idanimation.'&idanimjob='.$idanimjob;?>">People</a>
								<?php } else { ### page input ?>
									<a href="<?php echo $_SERVER['PHP_SELF'].'?act=selectpeople&idanimation='.$idanimation.'&idanimjob='.$idanimjob;?>">People</a>
								<?php } ?>
							<?php } ?>
						</th>
						<td class="lid"><?php echo $infos['codepeople']; ?></td>
						<td>
							<?php echo $infos['pnom']; ?> <?php echo $infos['pprenom']; ?>
						</td>
						<td>
							<?php
							if ($dist['peoplehome'] == 2) { $lat = 'pglat2'; $long = 'pglong2'; } else { $lat = 'pglat1'; $long = 'pglong1'; }
							if (($infos[$lat] > 0) and ($infos[$long] > 0)) echo '<img src="'.STATIK.'illus/geoloc.png" alt="geoloc.png" width="16" height="15" align="left">&nbsp;&nbsp;';
							else echo '<a href="'.NIVO.'data/people/adminpeople.php?act=show&idpeople='.$infos['idpeople'].'&idanimation='.$idanimation.'&idanimjob='.$idanimjob.'"><img src="'.STATIK.'illus/nogeoloc.png" border="0" alt="pas de geoloc"></a>&nbsp;&nbsp;';
							?>
							<select name="peoplehome">
								<option value="1" <?php if ($dist['peoplehome'] == 1) echo "selected";?>><?php echo $infos['adresse1']; ?> <?php echo $infos['num1']; ?> bte <?php echo $infos['bte1']; ?>  <?php echo $infos['cp1']; ?> <?php echo $infos['ville1']; ?></option>
								<option value="2" <?php if ($dist['peoplehome'] == 2) echo "selected";?>><?php echo $infos['adresse2']; ?> <?php echo $infos['num2']; ?> bte <?php echo $infos['bte2']; ?>  <?php echo $infos['cp2']; ?> <?php echo $infos['ville2']; ?></option>
							</select>
						</td>
						<td colspan="4">
							<?php echo $infos['gsm']; ?>
						</td>
					</tr>
					<tr>
						<th class="anim">
							Client
						</th>
						<td class="lid"><?php echo $infos['codeclient']; ?></td>
						<td colspan="6">
							 <?php echo $infos['csociete']; ?> (<?php echo $infos['oqualite'].' '.$infos['onom'].' '.$infos['oprenom'].' '.$infos['olangue']; ?>)
						</td>
					</tr>

				</table>		
			</td>
		</tr>
	</table>		

	<fieldset>
		<legend>
			INFO PARTICULIERE				
		</legend>
		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
<?php
if ($infos['fmode'] == 'briefing')  echo '<tr><th colspan="2" style="background-color: #FC0;">BRIEFING</th></tr>';
if ($infos['fmode'] == 'noforfait') echo '<tr><th colspan="2" style="background-color: #F00;">HORS FORFAIT</th></tr>';
?>

			<tr>
				<td width="95" valign="top">
									<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="standard">
                      <tr>
                        <th colspan="4" nowrap class="vip">Auto KM </th>
                      </tr>
                      <tr>
                        <td colspan="2" nowrap><input type="hidden" name="distauto" value="<?php echo $dist['AR'] ?>"><?php echo '<input type="checkbox" name="kmauto" value="Y" '; if ($infos['kmauto'] == 'Y') { echo 'checked';}  echo $disable; echo'>'; ?>&nbsp; <?php echo $dist['AR'].' km'; ?> </td>
                        <td align="left" nowrap>Km Pay&eacute; : </td>
                        <td nowrap><input type="text" size="4" name="kmpaye" value="<?php echo fnbr($infos['kmpaye']) ?>" <?php echo $disable ?> <?php echo ($infos['kmauto'] == 'Y')?' disabled':'' ?>>Km </td>
                      </tr>
                      <tr>
                        <td colspan="2" nowrap>&nbsp;</td>
                        <td align="left" nowrap>Km Fac : </td>
                        <td nowrap><input type="text" size="4" name="kmfacture" value="<?php echo fnbr($infos['kmfacture']) ?>" <?php echo $disable ?> <?php echo ($infos['kmauto'] == 'Y')?' disabled':'' ?>>Km </td>
                      </tr>
                      <tr>
                        <th colspan="4" nowrap>&nbsp;</th>
                      </tr>
                      <tr>
                        <th colspan="4" nowrap class="vip"><input type="CHECKBOX" name="nfraisyn" value="y" <?php if ($infos['idnfrais'] > 0) { echo 'checked';} ?>> Note de frais (n°<?php echo $infos['idnfrais']; ?>)</th>
                      </tr>
                      <tr>
                        <td colspan="4" valign="top" nowrap>Intitul&eacute;<br/>
                        <input name="nfrais-intitule" type="text" size="20" value="<?php echo $infos['intitule']; ?>"></td>
                        </tr>
                      <tr>
                        <td colspan="4" nowrap>Description<br/><textarea name="nfrais-descr" cols="20" rows="2"><?php echo $infos['description']; ?></textarea>
                        </td>
                        </tr>
                      <tr>
                        <td align="right" nowrap>Factur&eacute; </td>
                        <td nowrap><input name="nfrais-montantfac" type="text" size="5" value="<?php echo fnbr($infos['montantfacture']); ?>">&euro;</td>
                        <td align="right" nowrap>Pay&eacute; </td>
                        <td nowrap><input name="nfrais-montantpaye" type="text" size="5" value="<?php echo fnbr($infos['montantpaye']); ?>">&euro;</td>
                      </tr>
                      <tr>
                        <td nowrap>&nbsp;</td>
                        <td nowrap>&nbsp;</td>
                        <td nowrap>&nbsp;</td>
                        <td nowrap>&nbsp;</td>
                      </tr>
                      <tr>
                        <th colspan="4" nowrap class="vip">Livraison</th>
                      </tr>
                      <tr>
                        <td align="right" nowrap>Liv Pay&eacute;</td>
                        <td nowrap><input type="text" size="4" name="livraisonpaye" value="<?php echo fnbr($infos['livraisonpaye']); ?>" <?php echo $disable; ?>>
&euro; </td>
                        <td align="right" nowrap>Liv Fac </td>
                        <td nowrap><input type="text" size="4" name="livraisonfacture" value="<?php echo fnbr($infos['livraisonfacture']); ?>" <?php echo $disable; ?>>
&euro; </td>
                      </tr>
                      <tr>
                        <td colspan="4" nowrap>&nbsp;</td>
                        </tr>
                      <tr>
                        <th colspan="4" nowrap class="vip">Mode Fac</th>
                      </tr>
                      <tr>
                        <td colspan="4" nowrap><div align="center">
                          <select name="fmode">
                            <option value="normal" <?php if (($infos['fmode'] == 'normal') or (empty($infos['fmode']))) echo "selected";?>>Normal</option>
                            <option value="briefing" <?php if ($infos['fmode'] == 'briefing') echo "selected";?>>Briefing</option>
                            <option value="noforfait" <?php if ($infos['fmode'] == 'noforfait') echo "selected";?>>Hors Forfait</option>
                          </select>
                        </div></td>
                      </tr>
                      <tr>
                        <td colspan="4" nowrap>&nbsp;</td>
                      </tr>
                      <tr>
                        <th colspan="4" nowrap class="vip">J. Feri&eacute;</th>
                      </tr>
                      <tr>
                        <td colspan="4" nowrap align="center"><?php
									echo '<input type="radio" name="ferie" value="100" '; if (strchr($infos['ferie'], '100')) { echo 'checked';}  echo $disable; echo'> 100 %<br>';
									echo '<input type="radio" name="ferie" value="150" '; if (strchr($infos['ferie'], '150')) { echo 'checked';} echo $disable; echo'> 150 %<br>';
									echo '<input type="radio" name="ferie" value="200" '; if (strchr($infos['ferie'], '200')) { echo 'checked';} echo $disable; echo'> 200 %';
									?>
                       </td>
                      </tr>
                      <tr>
                        <td colspan="4" nowrap>&nbsp;</td>
                      </tr>
                      <tr bgcolor="#FF9933">
                        <th colspan="4" nowrap classe="vip">Contrat online</th>
                      </tr>
                      <tr bgcolor="#FF9933">
                        <td colspan="4" nowrap align="center"><?php 
									echo '<input type="radio" name="webdoc" value="yes" '; if (strchr($infos['webdoc'], 'yes')) { echo 'checked';} echo'> oui';
									echo '<input type="radio" name="webdoc" value="no" '; if (strchr($infos['webdoc'], 'no')) { echo 'checked';} echo'> non';
									?>
                      </td>
                      </tr>
                      <tr>
                        <td colspan="4" nowrap>&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="4" nowrap>&nbsp;                        </td>
                      </tr>
                  </table>

				</td>
				<td valign="top">
					<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="99%">
						<tr>
							<td>
								<br>
								<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="99%">
									<tr>
										<th class="vip">
											Date 
										</th>
										<td>
											<input type="text" size="10" name="datem" value="<?php echo fdate($infos['datem']); ?>" <?php echo $disable; ?>>
										</td>
										<th class="vip">
											Matin
										</th>
										<td>
											IN : <input type="text" size="5" name="hin1" value="<?php echo ftime($infos['hin1']); ?>" <?php echo $disable; ?>> OUT : <input type="text" size="5" name="hout1" value="<?php echo ftime($infos['hout1']); ?>" <?php echo $disable; ?>>
										</td>
										<th class="vip">
											Apr&egrave;s-midi
										</th>
										<td>
											IN : <input type="text" size="5" name="hin2" value="<?php echo ftime($infos['hin2']); ?>" <?php echo $disable; ?>> OUT : <input type="text" size="5" name="hout2" value="<?php echo ftime($infos['hout2']); ?>" <?php echo $disable; ?>>
										</td>
										<th class="vip">
											<?php
												$anim = new coreanim($infos['idanimation']);
												$timetot =  $anim->hprest;
												echo $timetot;
											?>
											heures prest&eacute;es
										</th>
									</tr>
								</table>
								<br>
								<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
									<tr>
										<th class="vip" style="width: 100px;">
											T&eacute;l&eacute;check<br><input type="text" size="10" name="tchkdate" value="<?php echo fdate($infos['tchkdate']); ?>" <?php echo $disable; ?>>
										</th>
										<td>
											<textarea name="tchkcomment" rows="4" cols="40" <?php echo $disable; ?>><?php echo stripslashes($infos['tchkcomment']); ?></textarea>
										</td>
										<td>
<?php 
if ($infos['idpeople'] > 1) {

	$ht = new hh(); 
	$ht->hhtable ($infos['idpeople'], $infos['datem'], $infos['datem']) ;
	
	if (count($ht->prtab[$infos['datem']]) > 1) {
		foreach ($ht->prtab[$infos['datem']] as $val) {
			$sum = sprintf("%024.0f",$sum + $val['hh']);
		}
	
		if(preg_match('/[2-9]/', $sum)) {
			## Tableau Rouge : Double booking
			echo '<div style="padding: 2px;background-color: #F60;border-color: red; border-width: 2px; border-style: solid;width: 300px; display: block;">';
			echo '<b>Double Booking</b><br>';
			foreach ($ht->prtab[$infos['datem']] as $val) {
				if ($val['idmission'] != $infos['idanimation']) {
				
					switch($val['secteur']) {
						case"ME":
						case"EAS":
							$res = $DB->getRow("SELECT hin1, hout1, hin2, hout2 FROM merch WHERE idmerch = ".$val['idmission']);
							$period = ftime($res['hin1'])." - ".ftime($res['hout1']).' | '.ftime($res['hin2'])." - ".ftime($res['hout2']);
						break;
						case"AN":
							$res = $DB->getRow("SELECT hin1, hout1, hin2, hout2 FROM animation WHERE idanimation = ".$val['idmission']);
							$period = ftime($res['hin1'])." - ".ftime($res['hout1']).' | '.ftime($res['hin2'])." - ".ftime($res['hout2']);
						break;
						case"VI":
							$res = $DB->getRow("SELECT vipin, vipout FROM vipmission WHERE idvip = ".$val['idmission']);
							$period = ftime($res['vipin'])." - ".ftime($res['vipout']);
						break;
					}	
		
					echo $val['secteur'].' '.$val['idmission'].' | '.$period.'<br>';
				}
			}
			echo '</div>';
		} else {
			## Tableau vert (pas de chevauchement
			echo '<div style="padding: 2px;background-color: #6C3;border-color: green; border-width: 2px; border-style: solid;width: 300px; display: block;">';
			echo '<b>Autre Mission le meme jour</b><br>';
			foreach ($ht->prtab[$infos['datem']] as $val) {
				if ($val['idmission'] != $infos['idanimation']) {
				
					switch($val['secteur']) {
						case"ME":
						case"EAS":
							$res = $DB->getRow("SELECT hin1, hout1, hin2, hout2 FROM merch WHERE idmerch = ".$val['idmission']);
							$period = ftime($res['hin1'])." - ".ftime($res['hout1']).' | '.ftime($res['hin2'])." - ".ftime($res['hout2']);
						break;
						case"AN":
							$res = $DB->getRow("SELECT hin1, hout1, hin2, hout2 FROM animation WHERE idanimation = ".$val['idmission']);
							$period = ftime($res['hin1'])." - ".ftime($res['hout1']).' | '.ftime($res['hin2'])." - ".ftime($res['hout2']);
						break;
						case"VI":
							$res = $DB->getRow("SELECT vipin, vipout FROM vipmission WHERE idvip = ".$val['idmission']);
							$period = ftime($res['vipin'])." - ".ftime($res['vipout']);
						break;
					}	
		
					echo $val['secteur'].' '.$val['idmission'].' | '.$period.'<br>';
				}
			}		echo '</div>';
		}
	}

}


?></td>
										<th class="vip" align="center" width="80" bgcolor="#339999">
											<br>
											<?php if ($disable != 'disabled') { ?>
												<input type="submit" name="Modifier" value="Modifier">
											<?php } ?>
											<br>
											&nbsp;
										</th>
									</tr>
								</table>
								<br>
								<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
									<tr>
										<th class="vip" valign="top" width="300">
											Changements online par le people?
										</th>
										<td bgcolor="#FF9933" width="250">
											&nbsp;
											<?php 
											echo '<input type="radio" name="peopleonline" value="0" '; if ($infos['peopleonline'] == 0) { echo 'checked';} echo'> Ouvert &nbsp; ';
											echo '<input type="radio" name="peopleonline" value="1" '; if ($infos['peopleonline'] == 1) { echo 'checked';} echo'> Modifi&eacute; &nbsp; ';
											echo '<input type="radio" name="peopleonline" value="2" '; if ($infos['peopleonline'] == 2) { echo 'checked';} echo'> Ferm&eacute;';
											?>
										</td>
										<td>&nbsp;</td>
									</tr>
								</table>
								<table class="standard" border="0" cellspacing="0" cellpadding="0" align="center" width="99%">
									<tr>
										<td valign="middle">
											<table border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
												<tr>
													<td valign="middle" width="100%" height="250">
														<iframe frameborder="0" marginwidth="0" marginheight="0" name="detail-main" src="<?php echo 'mission-onglet.php?act=show&etat='.$etat.'&disable='.$disable.'&idanimation='.$idanimation.'&s='.$s.'&action='.$_POST['act'].'';?>" width="100%" height="98%" align="top">Marche pas les IFRAMES !</iframe> 
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>		
				</td>
			</tr>
		</table>		
	</fieldset>
</div>
<?php if ($disable != 'disabled') { ?>
	</form>
<?php } ?>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$("input[name='kmauto']").change( function () {
			if ($(this).attr('checked')) {
				$("input[name='kmfacture']").attr('disabled', 'disabled');
				$("input[name='kmpaye']").attr('disabled', 'disabled');
			} else {
				$("input[name='kmfacture']").attr('disabled', '');
				$("input[name='kmpaye']").attr('disabled', '');
			}
			$("input[name='kmfacture']").val($("input[name='distauto']").val());
			$("input[name='kmpaye']").val($("input[name='distauto']").val());
		})
	});
</script>