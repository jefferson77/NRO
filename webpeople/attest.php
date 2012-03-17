<div class="news">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="fulltitre" colspan="2"><?php echo $tool_01;?></td>
		</tr>
		<tr>
			<td class="newstit"><?php echo $tool_09;?></td>
		</tr>
		<tr>
			<td class="newstxt"><?php echo $contrat_02;?><br><?php echo $contrat_03;?><br><?php echo $contrat_04;?></td>
		</tr>
		<tr>
			<td class="newstit"><?php echo $tool_10;?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<input type="checkbox" name="exemple" checked> : <?php echo $contrat_06;?><br>
				<img src="<?php echo STATIK ?>illus/printer.png" width="16" height="16"> : <?php echo $contrat_07;?><br>
				<img src="<?php echo STATIK ?>illus/minipdf.gif" width="16" height="16"> : <?php echo $contrat_08;?>
			</td>
		</tr>
	</table>
</div>

<div class="corps">
<?php $classe = "planning"; ?>

<form action="attestprint.php" method="post" target="popup" onsubmit="OpenBrWindow('_blank','popup','scrollbars=yes,status=yes,resizable=yes','200','400','true')" >
		<fieldset class="width">
			<legend>
				<?php echo $attest_00;?>
			</legend>
			<div align="center">
<br><br>
			<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center">
				<tr>
					<td>
						<table class="standard" border="0" cellspacing="1" cellpadding="0">
							<tr>
								<td class="tabtitre" width="33%"><?php echo $attest_01;?></td>
								<td width="34%">&nbsp;</td>
								<td class="tabtitre" width="33%"><?php echo $attest_02;?></td>
							</tr>
							<tr>
								<td class="line"><input type="text" size="20" name="datein" value=""></td>
								<td></td>
								<td class="line"><input type="text" size="20" name="dateout" value=""></td>
							</tr>
							<tr>
								<td class="line"><div align="center"><?php echo $attest_04;?> : <b>01/09/2003</b></div></td>
								<td></td>
								<td class="line"><div align="center"><?php echo $attest_04;?> : <b><?php $nreg = new db(); echo fdate($nreg->CONFIG('lastpayement')); ?></b></div></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center">
						<input type="submit" name="Rechercher" value="Rechercher"><br><br><?php echo $attest_03;?> 01/09/2003 <?php echo $attest_05;?><?php $nreg = new db(); echo fdate($nreg->CONFIG('lastpayement')); ?>.<br> <?php echo $attest_06;?>
					</td>
				</tr>
			</table>
		</div>
		</fieldset>
</div>
