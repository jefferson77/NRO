<?php 
define('NIVO', '../../');
include NIVO."includes/entete.php" ;

?>
	<div id="centerzonelarge">
		<?php 
		
		if($_SESSION['roger'] =="devel")
		{
			$requestagent = "";
		}
		else
		{
			$requestagentvip = "AND vj.idagent = ".$_SESSION['idagent'];
			$requestagentanim = "AND a.idagent = ".$_SESSION['idagent'];
			$requestagentmerch = "AND m.idagent = ".$_SESSION['idagent'];
		}
		
		$vip = $DB->getArray(
		   "SELECT v.idvip as idmission, v.idvipjob as idjob, v.vipdate as date, v.agentmodif as agent, 'VIP' as secteur FROM vipmission v
			LEFT JOIN vipjob vj ON v.idvipjob = vj.idvipjob
			LEFT JOIN peoplevac pv ON pv.idpeople = v.idpeople
			WHERE v.vipdate BETWEEN pv.vacin AND pv.vacout 
			AND v.vipdate NOT LIKE '0000-00-00'
			AND pv.etat = 'in' ".$requestagentvip
			); 
			
		$anim = $DB->getArray(
		   "SELECT a.idanimation as idmission, a.idanimjob as idjob, a.datem as date, a.idagent as agent, 'ANIM' as secteur FROM animation a 
			LEFT JOIN peoplevac pv ON pv.idpeople = a.idpeople
			WHERE a.datem BETWEEN pv.vacin AND pv.vacout
			AND pv.etat = 'in'
			AND a.datem NOT LIKE '0000-00-00' ".$requestagentanim);
				
		$merch = $DB->getArray(
		   "SELECT m.idmerch as idmission, m.idmerch as idjob, m.datem as date, m.idagent as agent, 'MERCH' as secteur FROM merch m 
			LEFT JOIN peoplevac pv ON pv.idpeople = m.idpeople
			WHERE m.datem BETWEEN pv.vacin AND pv.vacout 
			AND m.datem NOT LIKE '0000-00-00'
			AND pv.etat = 'in' ".$requestagentmerch);
			
		$total = array_merge($vip,$anim,$merch);
			?>
			<table class="sortable-onload-2 paginate-20 rowstyle-alt no-arrow" border="0" width="90%" cellspacing="1" align="center">
				<thead>
					<tr>
						<th class="sortable-text">Secteur</th>
						<th class="sortable-numeric">Mission</th>
						<th class="sortable-date-dmy">Date</th>
						<th class="sortable-text">Type</th>
					</tr>
				</thead>
				<tbody>
					<?php
				foreach($total as $t)
				{ 
					switch($t['secteur'])
					{
						case "VIP" : ?>
							<tr target = "infozone" ondblclick="location.href='<?php echo "/vip/adminvip.php?act=show&idvipjob=".$t['idjob']."&etat=1"; ?>'" >
						<?php
						break;
						case "ANIM" : ?>
							<tr target = "infozone" ondblclick="location.href='<?php echo "/animation2/adminanim.php?act=showjob&idanimjob=".$t['idjob']; ?>'" >
						<?php
						break;
						case "MERCH" : ?>
						<tr target = "infozone" ondblclick="location.href='<?php echo "/merch/adminmerch.php?act=show&act2=listing&idmerch=".$t['idjob']; ?>'" >
						<?php	
					}
					?>
						<td align="center"><?php echo $t['secteur'] ?></td>
						<td align="center"><?php echo $t['idmission']; ?></td>
						<td align="center"><?php echo fdate($t['date']); ?></td>
						<td align="center">Vacances</td>
		<?php 	}	?>
				</tbody>
				</table>
		<BR><BR><BR>
				<?php
				$tes = $DB->getONE("SELECT CONCAT(year(now()),'-',month(now()),'-',day(now()));");
				$ter = explode('-',$tes);
				$date = $ter[0].prezero($ter[1],2).prezero($ter[2],2);
				if($_SESSION['idagent']!=6)
				{
					$agent = $DB->getOne("SELECT prenom from agent WHERE idagent = ".$_SESSION['idagent']);
					$nomagent = strtoupper($agent);
				}
				else
				{
					$nomagent = "GLOBAL";
					$agent = "de tout le monde";
				}
				?>
		<div align="center"><a style="text-decoration:none;" href="../../document/temp/people/erreurs/ERR-<?php echo $date."-".$nomagent; ?>.pdf"><font color="red" size="16">Erreurs <?php echo $agent; ?></font></a></div>
		</div>

<?php include(NIVO."includes/pied.php"); ?>