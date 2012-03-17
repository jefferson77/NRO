<?php
################### Code PHP ########################
	$vipdevis = new db();
	$vipdevis->inline("SELECT * FROM `vipmission` WHERE `idvip` = $idvip;");
	$infos = mysql_fetch_array($vipdevis->result) ; 
echo $idvip;
echo $idvipjob;
################### Fin Code PHP ########################
?>
<?php #  Corps de la Page 
?>
<div id="minicentervip">
<form action="<?php echo $_SERVER['PHP_SELF'].'?act=delete&etat=1&step=delete';?>" method="post">
	<input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>"> 
	<input type="hidden" name="idvip" value="<?php echo $idvip;?>"> 
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
		<tr>
			<td valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
					<tr>
						<th class="vip2" width="70">
							Motif :
						</th>
					</tr>
					<tr>
						<td align="center">
							<textarea name="notedelete" rows="2" cols="50"><?php echo $infos['notedelete']; ?></textarea>
						</td>
					</tr>
				</table>		
			</td>
		</tr>
	</table>	
	<br>
	<div align="center">
		<input type="submit" name="Modifier" value="Effacer">
	</div>
</form>
