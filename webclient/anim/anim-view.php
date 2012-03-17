<style type="text/css" title="text/css">
<!--
a { color: white; }

-->
</style>
<div class="corps2">
<?php

## Sort
$sortarray[0] = "an.idanimation ASC";
$sortarray[1] = "an.datem ASC, an.idanimation ASC";
$sortarray[2] = "an.weekm ASC, an.datem ASC";
$sortarray[3] = "an.hin1 ASC, an.idanimation ASC";
$sortarray[4] = "an.hout1 ASC, an.idanimation ASC";
$sortarray[5] = "an.hin2 ASC, an.idanimation ASC";
$sortarray[6] = "an.hout2 ASC, an.idanimation ASC";
$sortarray[7] = "s.societe ASC, s.ville ASC, an.datem ASC";
$sortarray[8] = "p.pnom ASC, p.pprenom ASC, an.datem ASC";
$sortarray[9] = "an.produit ASC, an.datem ASC";	

if (empty($_GET['sort'])) $_GET['sort'] = $sortarray[1];

$detailjob = new db();
$detailjob->inline("SELECT * FROM `animjob` WHERE `idanimjob` = $idanimjob ");
$infosjob = mysql_fetch_array($detailjob->result) ; 

################### Fin Code PHP ########################
?>
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<td class="etiq2">
							<?php echo $vipdetail_0_01; ?>
						</td>
						<td class="contenu">
							<?php echo '('.$infosjob['etat'].') '.$idanimjob.' - '.stripslashes($infosjob['reference']); ?>
						</td>
					</tr>
					<tr>
						<td class="etiq2">
							<?php echo $vipdetail_0_02; ?>
						</td>
						<td class="contenu">
							<?php echo stripslashes($infosjob['notejob']); ?>
						</td>
					</tr>
				</table>
				<br>
<?php 
# Recherche des reésultats
	
$sql = 'SELECT 
	an.idanimation, an.datem, an.weekm, an.hin1, an.hout1, an.hin2, an.hout2, an.produit, 
	s.societe AS ssociete, s.ville AS sville,
	p.pnom, p.pprenom
	FROM animation an
	LEFT JOIN people p ON an.idpeople = p.idpeople
	LEFT JOIN shop s ON an.idshop = s.idshop
	WHERE an.idanimjob = '.$idanimjob.' ORDER BY '.$_GET['sort'];
$anim = new db();
$anim->inline($sql);
$FoundCount = mysql_num_rows($anim->result); 
?>
<div align="center">
	<?php echo $FoundCount.' '.$animaction_32; ?><br>
	<?php echo $tool_50; ?> <?php echo fdate($infosjob['datein']); ?> <?php echo $tool_51; ?> <?php echo fdate($infosjob['dateout']); ?><br>

	<?php
		if ($FoundCount >= 1) { ?>
			<a class="white2" href="javascript:;" style="color: #000000;" onClick="OpenBrWindow('<?php echo $print; ?>anim/printanim.php?web=web&ptype=planning&print=go&idanimjob=<?php echo $idanimjob;?>&sort=<?php echo urlencode($_GET['sort']); ?>','Main','','300','300','true')"><img src="<?php echo STATIK ?>illus/minipdf.gif" width="12" height="12" border="0" alt="">&nbsp;<?php echo $animaction_07; ?></a>
		<?php } ?>	

</div>
		<table class="planning" border="0" cellspacing="1" cellpadding="4" align="center" width="98%">
			<tr class="planning">
				<td class="tabtitre"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=animview&idanimjob='.$_GET['idanimjob'].'&sort='.$sortarray[0]; ?>"><?php echo $animaction_32; # Mission?></a></td>
				<td class="tabtitre"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=animview&idanimjob='.$_GET['idanimjob'].'&sort='.$sortarray[1]; ?>"><?php echo $tool_49; # Date ?></a></td>
				<td class="tabtitre"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=animview&idanimjob='.$_GET['idanimjob'].'&sort='.$sortarray[2]; ?>"><?php echo $animaction_34; # Semaine ?></a></td>
				<td class="tabtitre"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=animview&idanimjob='.$_GET['idanimjob'].'&sort='.$sortarray[3]; ?>"><?php echo $tool_52; # De?></a></td>
				<td class="tabtitre"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=animview&idanimjob='.$_GET['idanimjob'].'&sort='.$sortarray[4]; ?>"><?php echo $tool_53; # A?></a></td>
				<td class="tabtitre"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=animview&idanimjob='.$_GET['idanimjob'].'&sort='.$sortarray[5]; ?>"><?php echo $tool_52; # De?></a></td>
				<td class="tabtitre"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=animview&idanimjob='.$_GET['idanimjob'].'&sort='.$sortarray[6]; ?>"><?php echo $tool_53; # A?></a></td>
				<td class="tabtitre"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=animview&idanimjob='.$_GET['idanimjob'].'&sort='.$sortarray[7]; ?>"><?php echo $vipdetail_0_03; # Lieu?></a></td>
				<td class="tabtitre"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=animview&idanimjob='.$_GET['idanimjob'].'&sort='.$sortarray[8]; ?>"><?php echo $animaction_00; # Animatrice?></a></td>
				<td class="tabtitre"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=animview&idanimjob='.$_GET['idanimjob'].'&sort='.$sortarray[9]; ?>"><?php echo $animaction_33; # Promotion?></a></td>
			</tr>
									
			<?php

			$i = 0;
			while ($infos = mysql_fetch_array($anim->result)) { 
				$i++;
				if (fmod($i, 2) == 1) {
					echo '<tr bgcolor="#BFD7EC">';
				} else {
					echo '<tr bgcolor="#CEDCE7">';
				}
			?>
				<td class="line"><?php echo $infos['idanimation']; ?></td>
				<td class="line"><?php echo fdate($infos['datem']); ?></td>
				<td class="line"><?php echo $infos['weekm']; ?></td>
				<td class="line"><?php echo ftime($infos['hin1']); ?></td>
				<td class="line"><?php echo ftime($infos['hout1']); ?></td>
				<td class="line"><?php echo ftime($infos['hin2']); ?></td>
				<td class="line"><?php echo ftime($infos['hout2']); ?></td>
				<td class="line"><?php echo $infos['ssociete'].' - '.$infos['sville']; ?></td>
				<td class="line"><?php echo $infos['pnom'].' '.$infos['pprenom']; ?></td>
				<td class="line"><?php echo $infos['produit']; ?></td>
			</tr>
		<?php } ?>
		</table>
</div>				