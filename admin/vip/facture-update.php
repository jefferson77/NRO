<?php
# Entete de page
define('NIVO', '../');
$Titre = 'VIP';
$Style = 'vip';

# Entete de page
include NIVO."includes/ifentete.php" ;
include NIVO.'classes/vip.php';

if ($_REQUEST['idvip'] != 'zz')
{
	$infos = $DB->getRow("SELECT 
	idvip, ajust, vkm, km, vfkm, fkm, unif, loc1, loc2, net, vcat, cat, vdisp, disp, vfr1, vfrpeople
	FROM `vipmission` WHERE `idvip` = ".$_REQUEST['idvip']);
	
	$fich = new corevip ($infos['idvip']);
?>
<form action="adminvip.php?act=facture1&action=mission" method="post" target="_top">
	<input type="hidden" name="idvip" value="<?php echo $infos['idvip'] ;?>">
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
		<tr>
			<th class="vip2">ID </th>
			<th class="vip2">Tot </th>
			<th class="vip2">Ajust </th>
			<th class="vip2">Pay&eacute;</th>
			<th class="vip2" width="20">&nbsp;</th>
			<th class="vip2">Km P</th>
			<th class="vip2">km F</th>
			<th class="vip2">Kmf P</th>
			<th class="vip2">Kmf F </th>
		</tr>
		<tr>
			<td align="center"><?php echo $infos['idvip']; ?></td>
			<td align="center"><?php echo $fich->thfact; ?></td>
			<td align="center"><input type="text" size="8" name="ajust" value="<?php echo $infos['ajust']; ?>"></td>
			<td align="center"><?php  echo $fich->thpaye; ?></td>
			<td align="center">&nbsp;</td>
			<td align="center"><input type="text" size="8" name="vkm" value="<?php echo $infos['vkm']; ?>"></td>
			<td align="center"><input type="text" size="8" name="km" value="<?php echo $infos['km']; ?>"></td>
			<td align="center"><input type="text" size="8" name="vfkm" value="<?php echo $infos['vfkm']; ?>"></td>
			<td align="center"><input type="text" size="8" name="fkm" value="<?php echo $infos['fkm']; ?>"></td>
		</tr>
	</table>		
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
		<tr>
			<th class="vip2">Ca P </th>
			<th class="vip2">Ca F </th>
			<th class="vip2">Di P </th>
			<th class="vip2">Di F </th>
			<th class="vip2">FR P</th>
			<th class="vip2">Fr people P</th>
			<th class="vip2">FR F</th>
		</tr>
		<tr>
			<td align="center"><input type="text" size="8" name="vcat" value="<?php echo $infos['vcat']; ?>"></td>
			<td align="center"><input type="text" size="8" name="cat" value="<?php echo $infos['cat']; ?>"></td>
			<td align="center"><input type="text" size="8" name="vdisp" value="<?php echo $infos['vdisp']; ?>"></td>
			<td align="center"><input type="text" size="8" name="disp" value="<?php echo $infos['disp']; ?>"></td>
			<td align="center"><input type="text" size="8" name="vfr1" value="<?php echo $infos['vfr1']; ?>"></td>
			<td align="center"><input type="text" size="8" name="vfrpeople" value="<?php echo $infos['vfrpeople']; ?>"></td>
			<td align="center"><?php echo $fich->MontNfrais ?></td>
			<td align="center"><input type="submit" name="Modifier" value="Modifier"></td>
		</tr>
	</table>		
</form>
<?php
}
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>