<?php
################### Code PHP ########################
$detailjob = new db('webanimjob', 'idwebanimjob ', 'webneuro');
$detailjob->inline("SELECT * FROM `webanimjob` WHERE `idwebanimjob` = $idwebanimjob ");
$infosjob = mysql_fetch_array($detailjob->result) ; 
################### Fin Code PHP ########################
?>
<?php /*  Corps de la Page */ ?>
<div id="centerzonelargewhite">
<?php $classe = "standard" ?>
	<br>
	<Fieldset class="gray">
		<Fieldset class="gray">
			<legend class="gray">Job</legend>
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
				<tr>
					<td class="etiq2">Client</td>
					<?php 
					# recherche client et cofficer
					if (!empty($infosjob['idclient'])) {
						$idclient=$infosjob['idclient'];
						$detail3 = new db();
						$detail3->inline("SELECT * FROM `client` WHERE `idclient`=$idclient");
	
						$infosclient = mysql_fetch_array($detail3->result) ; 
						
						$idcofficer=$infosjob['idcofficer'];
						$detail4 = new db();
						$detail4->inline("SELECT * FROM `cofficer` WHERE `idcofficer`=$idcofficer");
						$infosofficer = mysql_fetch_array($detail4->result) ; 
					}
					?>
					<td><?php echo $infosclient['codeclient']; ?> <?php echo $infosclient['societe']; ?></td>
					<td><?php echo $infosofficer['qualite'].' '.$infosofficer['onom'].' '.$infosofficer['oprenom'].' '.$infosofficer['langue']; ?></td>
				</tr>
			</table>
			<br>
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
				<tr>
					<td valign="top">
						<table class="<?php echo $classe; ?>" border="0" cellspacing="1" cellpadding="0" align="center" width="99%">
							<tr>
								<td class="etiq2">Nom de l&rsquo;action</td>
							</tr>
							<tr>
								<td><?php echo $infosjob['reference']; ?></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
					</td>
					<td valign="top">
						<table class="<?php echo $classe; ?>" border="0" cellspacing="1" cellpadding="0" align="center" width="99%">
							<tr>
								<td class="etiq2" colspan="2">Description de l&rsquo;action</td>
							</tr>
							<tr>
								<td><?php echo $infosjob['notejob']; ?></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
					</td>
					<td valign="top">
						<table class="<?php echo $classe; ?>" border="0" cellspacing="1" cellpadding="0" align="center" width="99%">
							<tr>
								<td class="etiq2">P O</td>
							</tr>
							<tr>
								<td><?php echo $infosjob['bondecommande']; ?></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>	
		<br>
		<?php
			################### début vérif datein et dateout  ########################
			$datein = '3000-00-00';
			$dateout = '0000-00-00';
		
			$anim = new db('webanimbuild', 'idanimbuild', 'webneuro');
			$anim->inline("SELECT animdate1 FROM `webanimbuild` WHERE `idwebanimjob` = $idwebanimjob");
			while ($row2 = mysql_fetch_array($anim->result)) { 
				if ($row2['animdate1'] < $datein) { $datein = $row2['animdate1']; }
				if ($row2['animdate1'] > $dateout) { $dateout = $row2['animdate1']; }
			}
				$_POST['datein'] = $datein;
				$_POST['dateout'] = $dateout;
		
				$modif = new db('webanimjob', 'idwebanimjob', 'webneuro');
				$modif->MODIFIE($idwebanimjob, array('datein' , 'dateout' ));
		
				$detailjob = new db('webanimjob', 'idwebanimjob ', 'webneuro');
				$detailjob->inline("SELECT * FROM `webanimjob` WHERE `idwebanimjob` = $idwebanimjob ");
				$infosjob = mysql_fetch_array($detailjob->result) ; 
		
			################### Fin vérif datein et dateout  ########################
		?>
		<fieldset class="gray">
			<legend class="gray">Activit&eacute;s</legend>
			<table class="standard" border="0" cellspacing="2" cellpadding="0" align="center" width="98%" bgcolor="#FFFFFF">
				<tr>
					<td align="left">
					 dates actuelles : Du <?php echo fdate($infosjob['datein']); ?> au <?php echo fdate($infosjob['dateout']); ?>
					 </td>
				</tr>
			</table>
			<br>
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="99%">
				<tr class="vip">
					<td class="etiq2"></td>
					<td class="etiq2" align="center">Lieu / Shop</td>
					<td class="etiq2" align="center"></td>
					<td class="etiq2"><?php /* echo $vipdetail_1_04; */ ?></td>
					<td class="etiq2" align="center">Le</td>
					<td class="etiq2" align="center">De</td>
					<td class="etiq2" align="center">A</td>
					<td class="etiq2" align="center">De</td>
					<td class="etiq2" align="center">A</td>
				</tr>
				<?php
				$classe = "standard";
				$recherche='
					SELECT 
					ab.animnombre, ab.animdate1, ab.animin1, ab.animout1, ab.animin2, ab.animout2, 
					s.idshop, s.societe, s.cp, s.ville, s.adresse, s.newweb 
					FROM webneuro.webanimbuild ab
					LEFT JOIN neuro.shop s ON ab.idshop = s.idshop
					WHERE ab.idwebanimjob = '.$idwebanimjob.' 
					ORDER BY ab.animdate1, s.societe, s.cp
				';

				$detailbuild = new db('webanimbuild', 'idanimbuild', 'webneuro');
				$detailbuild->inline("$recherche;");
				while ($infos = mysql_fetch_array($detailbuild->result)) { 
					$i++;
				?>
					<tr id="line<?php echo $i; ?>" class="contenu">
						<td class="<?php echo $classe; ?>"><?php echo $i; ?>&nbsp;</td>
						<td class="<?php echo $classe; ?>">
							<a href="../data/shop/adminshop.php?act=show&idshop=<?php echo $infos['idshop']; ?>" target="_blank"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a> &nbsp;
							<?php if ($infos['newweb'] == 1){ echo '<font color="red">NEW &nbsp;</font>';} 
							echo substr($infos['societe'], 0, 35).' - '.$infos['cp'].' '.substr($infos['ville'], 0, 20).' ('.substr($infos['adresse'], 0, 30).')'; ?>
						</td>
						<td class="<?php echo $classe; ?>"><?php echo $infos['animnombre']; ?></td>
						<td class="<?php echo $classe; ?>"><?php echo $animaction_00; ?></td>
						<td class="<?php echo $classe; ?>"><?php echo fdate($infos['animdate1']); ?></td>

						<td class="<?php echo $classe; ?>"><?php echo ftime($infos['animin1']); ?></td>
						<td class="<?php echo $classe; ?>"><?php echo ftime($infos['animout1']); ?></td>
						<td class="<?php echo $classe; ?>"><?php echo ftime($infos['animin2']); ?></td>
						<td class="<?php echo $classe; ?>"><?php echo ftime($infos['animout2']); ?></td>
					</tr>
				<?php } ?>
			</table>
		</fieldset>
		<br>
		<table border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
			<tr>
				<td align="center" colspan="2">
					<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=webvalider" method="post">
						<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>"> 
						<input type="hidden" name="s" value="2">
						<input type="submit" name="action" value="Cloturer"> 
					</form>
				</td>
			</tr>
			<tr>
				<td width="50%">
					<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=webdelete" method="post" onClick="return confirm('Etes-vous sur de vouloir effacer ce job du Web?')">
						<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>"> 
						<input type="hidden" name="s" value="2">
						<input type="submit" name="action" value="Effacer"> 
					</form>
				</td>
				<td width="50%" align="right">
					<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=webanimlist" method="post">
						<input type="hidden" name="idwebanimjob" value="<?php echo $idwebanimjob;?>"> 
						<input type="hidden" name="s" value="2">
						<input type="submit" name="action" value="Laisser en Brouillon et retourner au Menu"> 
					</form>
				</td>
			</tr>
		</table>
		<br>
		<?php
		$path = Conf::read('Env.root').'media/annexe/animweb/';
		$ledir = $path;
		$d = opendir ($ledir);
		$nomanim = 'anim-'.$idwebanimjob.'-';
		while ($name = readdir($d)) {
			if (
				($name != '.') and 
				($name != '..') and 
				($name != 'index.php') and 
				($name != 'index2.php') and 
				($name != 'temp') and
				(strchr($name, $nomanim))
			) {
				$fichier++;
				if ($fichier == 1) {
		?>
				<br>
				<fieldset class="gray">
					<legend class="gray">Fichiers en annexe</legend>
						<table class="standard" border="0" cellspacing="1" cellpadding="3" align="left" width="500">
			<?php } ?>					
							<tr>
								<td class="vip"><a href="<?php echo $path.$name; ?>" target="_blank"><?php echo $name ?></a></td>
							</tr>
		<?php 
			}
		}
		closedir ($d);
		if ($fichier > 0) { ?>
					</table>
				</fieldset>
		<?php }?>
	</fieldset>
</div>