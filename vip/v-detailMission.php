<?php
## config
$editetats = array(1, 11, 12); ## liste des états ou la mission est modifiable

## Si update par Next ou Previous
	switch ($_POST['Modifier']) {
		case "Previous":
			$_REQUEST['idvip'] = $_POST['idprev'];
		break;
		case "Next":
			$_REQUEST['idvip'] = $_POST['idnext'];
	}

## Get mission infos
	if (!empty($_REQUEST['idvip'])) {
		$mis = $DB->getRow("SELECT
				v.*,
				j.briefing, j.idagent, j.idclient, j.idcofficer, j.reference, j.etat as jetat,
				a.nom, a.prenom,
				c.codeclient, c.societe,
				o.langue, o.onom, o.oprenom, o.qualite,
				s.adresse, s.codeshop, s.cp, s.glat, s.glong, s.societe, s.ville,
				p.codepeople, p.cp1, p.cp2, p.gsm, pnom, pprenom, p.sexe as psexe, p.ville1, p.ville2, IF(p.peoplehome=1,p.glat1,p.glat2) AS pglat, IF(p.peoplehome=1,p.glong1,p.glong2) AS pglong,
				COUNT(ma.idmatos) as nbrmatos,
				COUNT(blv.id) as nbrblv,
				nf.description, nf.idnfrais, nf.intitule, nf.montantfacture, nf.montantpaye
			FROM vipmission v
				LEFT JOIN vipjob j ON v.idvipjob = j.idvipjob
				LEFT JOIN agent a ON j.idagent = a.idagent
				LEFT JOIN client c ON j.idclient = c.idclient
				LEFT JOIN cofficer o ON j.idcofficer = o.idcofficer
				LEFT JOIN shop s ON v.idshop = s.idshop
				LEFT JOIN people p ON v.idpeople = p.idpeople
				LEFT JOIN matos ma ON p.idpeople = ma.idpeople
				LEFT JOIN bonlivraison blv ON blv.idjob = v.idvip AND blv.etat = 'in' AND blv.secteur = 'VI'
				LEFT JOIN notefrais nf ON nf.secteur = 'VI' AND nf.idmission = v.idvip
			WHERE v.idvip = ".$_REQUEST['idvip']."
			GROUP BY v.idvip");
	}

## find Previous and Next idvip en triant sur date/activité/idvip
	$seq = $DB->getColumn("SELECT idvip FROM vipmission WHERE idvipjob = ".$mis['idvipjob']." ORDER BY vipdate, vipactivite, idvip");
	$curpos = array_search($mis['idvip'], $seq);
	if(isset($seq[$curpos + 1])) $idnext = $seq[$curpos + 1];
	if(isset($seq[$curpos - 1])) $idprev = $seq[$curpos - 1];

## Calculs corevip
	$fich = new corevip ($mis['idvip']);

## page de retour en cas de géoloc
	$_SESSION['referer'] = $_SERVER['PHP_SELF'].'?act=geoloc&idpeople='.$mis['idpeople'].'&idshop='.$mis['idshop'].'&idvipjob='.$mis['idvipjob'].'&idvip='.$mis['idvip'];

?>
<style type="text/css" media="screen">
	.navigpanel {
		width: 90%;
		text-align:center;
		border: 0px;
		padding: 0px;
	}

	.boutauto {
		font-size:10px;
		text-decoration:none;
		background-color:#FFFFFF;
		padding:1px;
		border:1px solid #527184;
	}
</style>
<form action="?act=modifmission" method="post">
	<input type="hidden" name="idvip" value="<?php echo $mis['idvip'];?>" id="idvip">

	<div id="leftmenu">
		<table cellspacing="1" class="navigpanel">
			<tr><th colspan="2">JOB</th></tr>
			<tr><td colspan="2"><?php echo $mis['idvipjob']; ?></td></tr>
			<tr><th colspan="2">MISSION</th></tr>
			<tr><td colspan="2"><?php echo $mis['idvip']; ?></td></tr>
			<tr>
				<td colspan="2">
					<?php
					if (in_array($mis['jetat'], $editetats)) {
						echo '<input type="submit" name="Modifier" value="Modifier">';
					} else {
						echo '<input type="submit" name="Modifier" value="Retour" id="retbut">
						<input type="hidden" name="dontmaj" value="dontmaj" id="dontmaj">
						';
					}
					?>
				</td>
			</tr>
			<tr>
				<td width="50%">
					<?php if (!empty($idprev)) { ?>
					<input type="hidden" name="idprev" value="<?php echo $idprev;?>" id="idprev">
					<input type="submit" name="Modifier" value="Previous" id="prevbut">
					<?php } ?>
				</td>
				<td>
					<?php if (!empty($idnext)) { ?>
					<input type="hidden" name="idnext" value="<?php echo $idnext;?>" id="idnext">
					<input type="submit" name="Modifier" value="Next" id="nextbut">
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?php echo ($curpos + 1).' sur '.count($seq) ?></td>
			</tr>
		</table>
	</div>

<div id="infozone">
	<table border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
		<tr>
			<td valign="top">
				<table class="planning" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
					<tr>
						<th class="vip" width="90">Assistant</th>
						<td colspan="5"><?php echo $mis['prenom'].' '.$mis['nom']; ?></td>
					</tr>
					<tr>
						<th class="space4" colspan="6">&nbsp;</th>
					</tr>
					<tr>
						<th class="vip" width="90">R&eacute;f&eacute;rence</td>
						<td colspan="5"><?php echo $mis['reference']; ?></td>
					</tr>
					<tr>
						<th class="space4" colspan="6">&nbsp;</th>
					</tr>
					<tr>
						<th class="vip"  rowspan="2">
							<?php
								if (in_array($mis['jetat'], $editetats)) {
									echo '<a href="?act=select&sel=client&idvip='.$mis['idvip'].'&idvipjob='.$mis['idvipjob'].'&from=mission">Client</a>';
								} else {
									echo "Client";
								}
							?>
						</th>
						<td><?php echo $mis['codeclient']; ?></td>
						<td colspan="4"><?php echo $mis['societe']; ?></td>
					</tr>
					<tr>
						<td><?php echo $mis['qualite']; ?></td>
						<td colspan="3"><?php echo $mis['onom'].' '.$mis['oprenom']; ?></td>
						<td><?php echo $mis['langue']; ?></td>
					</tr>
					<tr>
						<th class="space4" colspan="6">&nbsp;</th></tr>
					<tr>
						<th class="vip" rowspan="2">
							<?php
								if (in_array($mis['jetat'], $editetats)) {
									echo '<a href="?act=select&sel=lieu&idvip='.$mis['idvip'].'&idvipjob='.$mis['idvipjob'].'&from=mission">lieux</a>';
								} else {
									echo "Lieu";
								}
							?>
						</th>
						<td>
						<?php
						if (!empty($mis['idshop'])) {
							if($mis['glat']>0 && $mis['glong']>0) echo '<img src="'.STATIK.'illus/geoloc.png">';
							else echo '<a href="'.NIVO.'data/shop/adminshop.php?act=show&idshop='.$mis['idshop'].'&idvip='.$mis['idvip'].'&idvipjob='.$mis['idvipjob'].'&from=mission"><img src="'.STATIK.'illus/nogeoloc.png" border="0" alt="pas de geoloc"></a>';
							echo $mis['codeshop'];
						}
						?>
						</td>
						<td colspan="4"><?php echo $mis['societe']; ?></td>
					</tr>
					<tr>
						<td colspan="3"><?php echo $mis['adresse']; ?></td>
						<td colspan="2"><?php echo $mis['cp'].' '.$mis['ville']; ?></td>
					</tr>
					<tr>
						<th class="space4" colspan="6">&nbsp;</th></tr>
<?php
						if ($mis['psexe'] != $mis['sexe'])	{
							$colorsex1 = '<Font color="red">';
							$colorsex2 = '</Font>';
						} else {
							$colorsex1 = '';
							$colorsex2 = '';
						}
?>
					<tr>
						<th class="vip" rowspan="2">
							<?php
								if (in_array($mis['jetat'], $editetats)) {
									echo '<a href="?act=select&sel=people&idvip='.$mis['idvip'].'&idvipjob='.$mis['idvipjob'].'&from=mission">People</a>';
								} else {
									echo "People";
								}
							?>
						</th>
						<td>
							<?php
							if(!empty($mis['idpeople'])) {
								if($mis['pglat']>0 && $mis['pglong']>0) echo '<img src="'.STATIK.'illus/geoloc.png">';
								else echo '<a href="'.NIVO.'data/people/adminpeople.php?act=show&idpeople='.$mis['idpeople'].'&idvip='.$mis['idvip'].'&idvipjob='.$mis['idvipjob'].'&from=mission"><img src="'.STATIK.'illus/nogeoloc.png" border="0" alt="pas de geoloc"></a>';
								echo $mis['idpeople']; ?>-<?php echo $mis['codepeople'];
							}
							?>
						</td>
						<td colspan="3"><?php echo $mis['pprenom']; ?> <?php echo $mis['pnom']; ?></td>
						<td><?php echo $colorsex1.$mis['sexe'].$colorsex2; ?></td>
					</tr>
					<tr>
						<td>
							<select name="sexe">
								<option value="f" <?php echo ($mis['sexe'] == 'f')?'selected':''; ?>>F</option>
								<option value="m" <?php echo ($mis['sexe'] == 'm')?'selected':''; ?>>M</option>
								<option value="x" <?php echo ($mis['sexe'] == 'x')?'selected':''; ?>>X</option>
							</select>
						</td>
						<td colspan="4">
							<?php echo $mis['gsm']; ?>
						</td>
					</tr>
					<tr>
						<th class="space4" colspan="6">&nbsp;</th></tr>
					<tr>
						<th class="vip" width="90">Date</td>
						<td colspan="2"><input type="text" size="12" name="vipdate" value="<?php echo fdate($mis['vipdate']); ?>"></td>
						<th class="vip">Activite</td>
						<td colspan="2"><input type="text" size="20" name="vipactivite" value="<?php echo $mis['vipactivite']; ?>"></td>
					</tr>
					<tr>
						<th class="space4" colspan="6">&nbsp;</th></tr>
					<tr>
						<th class="vip" width="90">Briefing Job</td>
						<td colspan="5"><?php echo nl2br($row['briefing']); ?></td>
					</tr>
					<tr>
						<th class="vip" width="90">Briefing Particulier mission</td>
						<td colspan="3"><textarea name="notes" rows="3" cols="60"><?php echo $mis['notes']; ?></textarea></td>
						<td colspan="2"><input type="checkbox" id="contratencode" onclick="changemodif();" name="contratencode" value="1" <?php echo ($mis['contratencode'] == 1)?' checked':''; ?>>Contrat Encodé</td>
					</tr>
					<tr>
						<th class="space4" colspan="6">&nbsp;</th></tr>
					<tr>
						<td colspan="6">
							<table class="standard" border="0" cellspacing="1" cellpadding="0" width="99%">
								<tr height="25">
									<td class="navbarleft"></td>
									<td class="navbar">
										<?php echo '<a href="'.NIVO.'data/people/matos.php?idpeople='.$mis['idpeople'].'&s=0" target="matos"><span id="nbrmatos">Matos('.$mis['nbrmatos'].')</span></a>' ?>
									</td>
									<td class="navbarmid"></td>
									<td class="navbar">
										<?php echo '<a href="'.NIVO.'data/bonlivraison/adminbonlivraison.php?act=list&secteur=VI&idvipjob='.$mis['idvip'].'" target="matos">Bons de livraison <span id="nbrbdl">('.$mis['nbrblv'].')</span></a>'; ?>
									</td>
								</tr>
								<tr>
									<td valign="top" colspan="17" bgcolor="#304052">
										<iframe frameborder="0" marginwidth="0" marginheight="0" name="matos" src="<?php echo NIVO.'data/people/matos.php?idpeople='.$mis['idpeople'];?>" width="100%" height="180" align="top">Marche pas les IFRAMES !</iframe>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
<td width="200" valign="top">
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
		<tr>
			<th colspan="4" nowrap class="vip">Facturation</th>
			<th colspan="2" nowrap class="vip">Paiement</th>
		</tr>
		<tr>
			<th colspan="4" nowrap class="space4">&nbsp;   </th>
			<th width="25%" colspan="2" nowrap class="space4">&nbsp; </th>
		</tr>
		<tr>
			<th colspan="6" nowrap class="vip2">Prestations</th>
		</tr>
		<tr>
			<td width="25%" nowrap class="etiq">In</td>
			<td width="25%" colspan="3" nowrap><input type="text" size="5" name="vipin" value="<?php echo ftime($mis['vipin']); ?>"></td>
			<td width="25%" nowrap class="etiq">Out</td>
			<td width="25%" nowrap><input type="text" size="5" name="vipout" value="<?php echo ftime($mis['vipout']); ?>"></td>
		</tr>
		<tr>
			<td nowrap class="etiq">Break</td>
			<td colspan="3" nowrap><input class="droite" type="text" size="5" name="brk" value="<?php echo fnbr($mis['brk']); ?>"> h</td>
			<td colspan="2" nowrap>&nbsp;</td>
		</tr>
		<tr>
			<td nowrap class="etiq">Night</td>
			<td colspan="3" nowrap><input class="droite" type="text" size="5" name="night" value="<?php echo fnbr($mis['night']); ?>"> h</td>
			<td colspan="2" nowrap>&nbsp;</td>
		</tr>
		<tr>
			<td nowrap class="etiq">Tar sp.</td>
			<td colspan="3" nowrap><input type="text" class="droite" size="5" name="ts" value="<?php echo fnbr($mis['ts']); ?>"> &euro;</td>
			<td nowrap class="etiq">Forf. TS</td>
			<td nowrap><input type="text" class="droite" size="5" name="fts" value="<?php echo fnbr($mis['fts']); ?>">h </td>
		</tr>
		<tr>
			<td nowrap class="etiq">150 %</td>
			<td colspan="3" nowrap><input type="text" class="droite" size="5" name="h150" value="<?php echo fnbr($mis['h150']); ?>"> h</td>
			<td nowrap class="etiq">200 %</td>
			<td nowrap><input type="checkbox" name="h200" value="1" <?php if ($mis['h200'] == 1) echo 'checked'; ?>></td>
		</tr>
		<tr>
			<td colspan="6" nowrap><hr></td>
		</tr>
		<tr>
			<td colspan="2" nowrap class="etiq">H r&eacute;elles</td>
			<td colspan="2" nowrap><?php echo fnbr($fich->thfact);?></td>
			<td nowrap class="etiq"> H r&eacute;elles </td>
			<td nowrap><?php echo $fich->thfact;?> </td>
		</tr>
		<tr>
			<th colspan="4" nowrap class="space4">&nbsp;</th>
			<td nowrap class="etiq"> Ajust </td>
			<td nowrap><input type="text" size="8" name="ajust" value="<?php echo fnbr($mis['ajust']); ?>"></td>
		</tr>
		<tr>
			<th colspan="4" nowrap class="space4">&nbsp;</th>
			<th colspan="2" nowrap class="space4">&nbsp;</th>
		</tr>
		<tr valign="top">
			<td colspan="4" nowrap>
				<table class="standard" border="1" cellspacing="0" cellpadding="1" align="center">
					<tr>
						<?php if ($mis['h200'] == 1) { echo '<td align="center" bgcolor="#CC0000"><font color="#FFFFFF">200%</font></td>';} else { echo '<td>&nbsp;</td>';}?>
						<th class="vip2">H</th>
						<th class="vip2">Tarif</th>
					</tr>
					<tr>
						<th class="vip2">H 0-5</th>
						<td><?php echo $fich->hhigh; ?></td>
						<td><?php echo feuro($fich->Tarif['high']); ?></td>
					</tr>
					<tr>
						<th class="vip2">H 6+</th>
						<td><?php echo $fich->hlow; ?></td>
						<td><?php echo feuro($fich->Tarif['low']); ?></td>
					</tr>
					<tr>
						<th class="vip2">H Night</th>
						<td><?php echo $fich->hnight; ?></td>
						<td><?php echo feuro($fich->Tarif['night']); ?></td>

					</tr>
					<tr>
						<th class="vip2">H Sup. (150%)</th>
						<td><?php echo $fich->h150; ?></td>
						<td><?php echo feuro($fich->Tarif['150']); ?></td>
					</tr>
					<tr>
						<th class="vip2">H Spec.</th>
						<td><?php echo $fich->hspec; ?></td>
						<td><?php echo feuro($fich->Tarif['spec']); ?></td>
					</tr>
				</table>
			</td>
			<td colspan="2" nowrap><table class="standard" border="1" cellspacing="0" cellpadding="0" align="center">
			  <tr>
				<th class="vip2">H Pay&eacute;es 100%</th>
				<td><?php echo $fich->thp100;?></td>
			  </tr>
			  <tr>
				<th class="vip2">H Pay&eacute;es 150%</th>
				<td><?php echo $fich->thp150;?></td>
			  </tr>
			  <tr>
				<th class="vip2">H Pay&eacute;es 200%</th>
				<td><?php echo $fich->thp200;?></td>
			  </tr>
			</table></td>
		</tr>
		<tr>
			<th colspan="4" nowrap class="space4">&nbsp;</th>
			<th colspan="2" nowrap class="space4">&nbsp;</th>
		</tr>
		<tr>
			<th colspan="4" nowrap class="vip2">D&eacute;placement</th>
			<th colspan="2" nowrap class="vip2">D&eacute;placement</th>
		</tr>
		<tr>
			<td colspan="4" align="center" nowrap>
				<select name="peoplehome">
					<option value="1" <?php echo ($mis['peoplehome'] == '1')?' selected':''; ?>><?php echo $mis['cp1'] . " " . $mis['ville1']; ?></option>
				<?php if(!empty($mis['cp2']) and !empty($mis['ville2'])) { ?>
					<option value="2" <?php echo ($mis['peoplehome'] == '2')?' selected':''; ?>><?php echo $mis['cp2'] . " " . $mis['ville2']; ?></option>
				<?php } ?>
				</select>
			</td>
			<td colspan="2" align="center" nowrap>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4" nowrap style="text-align: left;">
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="standard">
					<tr>
						<td nowrap class="etiq" style="text-align: left;">
							<input type="hidden" name="idpeople" value="<?php echo $mis['idpeople']; ?>">
							<input type="hidden" name="idshop" value="<?php echo $mis['idshop']; ?>">
							<input type="submit" value="A" name="Modifier">
						</td>
						<td nowrap class="etiq"> KM </td>
						<td colspan="2" nowrap><input type="text" class="droite" size="5" name="km" value="<?php echo fnbr($mis['km']); ?>" id="kmfac" onblur="syncKM(this);"> Km </td>
			  		</tr>
					<tr>
						<td nowrap class="etiq" style="text-align: left;"><a href="javascript:ModKM();" class="boutauto">A</a></td>
						<td nowrap class="etiq"> Forfait </td>
						<td colspan="2" nowrap><input type="text" class="droite" size="5" name="fkm" value="<?php echo fnbr($mis['fkm']); ?>" id="fkmfac">&euro; </td>
					</tr>
				</table>
			</td>
			<td colspan="2" nowrap>
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="standard">
				  <tr>
					<td nowrap class="etiq">&nbsp;</td>
					<td nowrap class="etiq">KM</td>
					<td nowrap><input type="text" size="5" name="vkm" value="<?php echo fnbr($mis['vkm']); ?>" id="kmpay" onblur="syncKM(this);">Km </td>
				  </tr>
				  <tr>
					<td nowrap class="etiq"><a href="javascript:ModpfKM();" class="boutauto">A</a></td>
					<td nowrap class="etiq"> KM Forfait</td>
					<td nowrap><input type="text" size="5" name="vfkm" value="<?php echo fnbr($mis['vfkm']); ?>" id="fkmpay">&euro; </td>
				  </tr>
				</table>
			</td>
		</tr>
		<tr>
			<th colspan="4" nowrap class="space4">&nbsp;</th>
			<th colspan="2" nowrap class="space4">&nbsp;</th>
		</tr>
		<tr>
		  <th colspan="4" nowrap class="vip2"> Location </th>
		  <th colspan="2" nowrap>&nbsp;</th>
	  </tr>
		<tr>
		  <td colspan="2" nowrap class="etiq"> Uniforme </td>
		  <td colspan="2" nowrap><?php if ($mis['unif'] == '') {$mis['unif'] = $mis['net'];} ?>
              <input type="text" class="droite" size="5" name="unif" value="<?php echo fnbr($mis['unif']); ?>">&euro; </td>
		  <th colspan="2" nowrap>&nbsp;</th>
	  </tr>
		<tr>
		  <td colspan="2" nowrap class="etiq"> Location </td>
		  <td colspan="2" nowrap><input type="text" class="droite" size="5" name="loc1" value="<?php echo fnbr($mis['loc1']); ?>">&euro; </td>
		  <th colspan="2" nowrap>&nbsp;</th>
	  </tr>
		<tr>
		  <th colspan="4" nowrap class="vip2">Frais</th>
		  <th colspan="2" nowrap class="vip2">Frais</th>
        </tr>
        <tr>
            <td colspan="2" nowrap class="etiq"> Frais 1 </td>
            <td colspan="2" nowrap><input type="text" class="droite" size="5" name="fr1" value="<?php echo fnbr($mis['fr1']); ?>" disabled>&euro; </td>
            <td nowrap class="etiq"> Frais 1 </td>
            <td nowrap><input type="text" class="droite" size="5" name="vfr1" value="<?php echo fnbr($mis['vfr1']); ?>">&euro; </td>
        </tr>
        <tr>
            <td colspan="2" nowrap class="etiq">Catering</td>
            <td colspan="2" nowrap><input type="text" class="droite" size="5" name="cat" value="<?php echo fnbr($mis['cat']); ?>" onChange="javascript:if(this.form.cat.value>9){this.form.vcat.value=6;}">&euro; </td>
            <td nowrap class="etiq">Catering</td>
            <td nowrap><input type="text" class="droite" size="5" name="vcat" value="<?php echo fnbr($mis['vcat']); ?>">&euro; </td>
        </tr>
        <tr>
            <td colspan="2" nowrap class="etiq">Dispatch.</td>
            <td colspan="2" nowrap><input type="text" class="droite" size="5" name="disp" value="<?php echo fnbr($mis['disp']); ?>">&euro; </td>
            <td nowrap class="etiq">Dispatch.</td>
            <td nowrap><input type="text" class="droite" size="5" name="vdisp" value="<?php echo fnbr($mis['vdisp']); ?>">&euro; </td>
        </tr>
        <tr>
            <td colspan="4" nowrap class="etiq"></td>
            <td nowrap class="etiq">Fr People</td>
            <td nowrap><input type="text" class="droite" size="5" name="vfrpeople" value="<?php echo fnbr($mis['vfrpeople']); ?>">&euro; </td>
        </tr>
		<tr>
		  <th colspan="4" nowrap>&nbsp;</th>
		  <th colspan="2" nowrap>&nbsp;</th>
	  </tr>
		<tr>
			<th colspan="6" nowrap class="vip2">
			  <input type="checkbox" name="nfraisyn" value="y" <?php if ($mis['idnfrais'] > 0) { echo 'checked';} ?>>
			  Note de frais (n°<?php echo $mis['idnfrais']; ?>)
			</tr>
		<tr>
			<td colspan="2" valign="top" nowrap class="etiq">Intitul&eacute;</td>
			<td colspan="4" valign="top" nowrap><input name="nfrais-intitule" type="text" size="43" value="<?php echo $mis['intitule']; ?>"></td>
	    </tr>
		<tr>
		  <td colspan="2" nowrap class="etiq">Description</td>
		  <td colspan="4" nowrap><textarea name="nfrais-descr" cols="40" rows="2"><?php echo $mis['description']; ?></textarea></td>
      </tr>
		<tr>
		  <td colspan="2" nowrap class="etiq">Facturé</td>
		  <td colspan="2" nowrap><input name="nfrais-montantfac" type="text" size="5" value="<?php echo fnbr($mis['montantfacture']); ?>">&euro;</td>
		  <td nowrap class="etiq">Payé</td>
	      <td nowrap><input name="nfrais-montantpaye" type="text" size="5" value="<?php echo fnbr($mis['montantpaye']); ?>">&euro;</td>
	  </tr>
	</table>
    </td>
</tr>
</table>

</div>
<div id="infobouton">
	<input type="submit" name="Modifier" id="mod" value="Modifier">
</div>
</form>
<script type="" language="JavaScript">
	function syncKM(ob) {
		if ((ob.id == 'kmfac') && (parseInt(document.getElementById('kmpay').value) > parseInt(ob.value))) {
			document.getElementById('kmpay').value = parseInt(ob.value);
		} else if ((ob.id == 'kmpay') && (parseInt(document.getElementById('kmfac').value) < parseInt(ob.value))) {
			document.getElementById('kmfac').value = parseInt(ob.value);
		}
	}

	function lock () {
		$("input, select, textarea").attr("disabled", "disabled");
		$("#prevbut, #nextbut, #retbut, #idvip, #idprev, #idnext, #dontmaj, #contratencode").removeAttr("disabled");
	}

	function ModKM(){
		document.getElementById('fkmfac').value = '12';
		document.getElementById('fkmpay').value = '4,5';
		document.getElementById('kmfac').value  = '';
		document.getElementById('kmpay').value  = '';
	}

	function ModCat(){
		document.getElementById('catfac').value = '9';
		document.getElementById('catpay').value = '6';
	}

	function ModpfKM(){
		document.getElementById('fkmpay').value = '4,5';
	}

	var nomodif = true;

	function changemodif(){

		nomodif = !nomodif;

		document.getElementById('mod').disabled = nomodif;

		if(nomodif == true)
		{
			document.getElementById('dontmaj').value = "nomodif";
		}
		else
		{
			document.getElementById('dontmaj').value = "";
		}
	}

<?php
	if (!in_array($mis['jetat'], $editetats)) {
		echo "lock();";
	}
?>
</script>
