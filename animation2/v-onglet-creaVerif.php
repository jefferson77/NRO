<?php
## Get infos
$dates = $DB->getRow("SELECT MAX(animdate2) AS dateout, MIN(animdate1) AS datein FROM `animbuild` WHERE `idanimjob` = ".$_REQUEST['idanimjob']);

$buildinfos = $DB->getArray("SELECT 
		vb.*
	FROM animbuild vb
	WHERE `idanimjob` = ".$_REQUEST['idanimjob']." 
	ORDER BY 'idanimbuild'");

?>
<Fieldset class="width">
	<legend class="width"><?php echo $vipdetail_02; ?></legend>
		<table class="standard" border="0" cellspacing="3" cellpadding="0" align="center" width="98%">
			<tr>
				<td width="33%"></td>
				<td width="34%"></td>
				<td width="33%"></td>
			</tr>
			<tr>
				<td colspan="3" align="left">
					<?php echo $vipdetail_1_06; ?> : <?php echo $tool_50; ?> <?php echo fdate($dates['datein']); ?> <?php echo $tool_51; ?> <?php echo fdate($dates['dateout']); ?><br>&nbsp
				</td>
			</tr>
			<?php

			foreach ($buildinfos as $infos) {
			$i++;
			?>
				<tr>
					<td colspan="3" class="etiq2">&nbsp; <?php echo $i; ?> : </td>
				<?php 
				$h = 0; #pour nombre hotesse 
				$animdate1 = $infos['animdate1'];
				while ($animdate1 <= $infos['animdate2']) {
				$h++;

						if (fmod($h, 3) == 1) {
							echo '</tr><tr>';
						}
					?>	
					<td>
						<table border="0" cellspacing="3" cellpadding="0" align="center" width="100%">
							<tr>
								<td colspan="6"><?php echo fdate($animdate1);?></th>	
							</tr>	
							<tr class="vip">
								<td class="vip"><b><?php echo $infos['animnombre'];?></b></td>
								<td class="vip"></td>
								<td class="vip"><?php echo $tool_52;?></td>
								<td class="vip"><?php echo ftime($infos['animin1']); ?> <?php echo $tool_54;?></td>
								<td class="vip"><?php echo $tool_53;?></td>
								<td class="vip"><?php echo ftime($infos['animout1']); ?> <?php echo $tool_54;?></td>
								<td class="vip"></td>
								<td class="vip"><?php echo $tool_52;?></td>
								<td class="vip"><?php echo ftime($infos['animin2']); ?> <?php echo $tool_54;?></td>
								<td class="vip"><?php echo $tool_53;?></td>
								<td class="vip"><?php echo ftime($infos['animout2']); ?> <?php echo $tool_54;?></td>
							</tr>
						</table>
					</td>
				<?php 
					$dd = explode('-', $animdate1);
					$animdate1 = date ("Y-m-d", mktime (0,0,0,$dd[1],$dd[2]+1,$dd[0]));
				} 
				?>	
				</tr>
			<?php 
			}
			?>
		</table>
	</fieldset>		
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
		<tr>
			<td width="50%">
				<form action="?act=grpshow" method="post">
					<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>"> 
					<input type="submit" name="action" value="&lt;&lt;&nbsp; <?php echo $tool_05c;?>"> 
				</form>
			</td>
			<td width="50%" align="right">
				<form action="?act=prodshow" method="post">
					<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>"> 
					<input type="submit" name="action" value="<?php echo $tool_03; ?> &nbsp;&gt;&gt;"> 
				</form>
			</td>
		</tr>
	</table>
