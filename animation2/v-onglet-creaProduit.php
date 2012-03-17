<?php
## get datas
$produits = $DB->getArray("SELECT * FROM `animbuildproduit` WHERE `idanimjob` = ".$_REQUEST['idanimjob']);

?>
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="98%">
		<tr>
			<td>&nbsp;</td>
			<th class="vip2">Types</th>
			<th class="vip2">Prix</th>
			<th class="vip2">In</th>
			<th class="vip2">Unit&eacute;</th>
			<th class="vip2">End</th>
			<th class="vip2">Ventes</th>
			<th class="vip2">No</th>
			<th class="vip2">Free</th>
			<th class="vip2">Promo in</th>
			<th class="vip2">Promo out</th>
			<th class="vip2">Promo end</th>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
<?php
$i = 0; # Pour les couleurs alternees
foreach ($produits as $prod) {
	$i++;
?>
		<tr bgcolor="<?php echo (fmod($i, 2) == 1)?'#8CAAB5':'#9CBECA' ?>">
			<td>
				<form action="?act=prodduplic" method="post">
					<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>">
					<input type="hidden" name="idanimbuildproduit" value="<?php echo $prod['idanimbuildproduit'];?>">
					<input type="submit" name="Modifier" value="D">
				</form>
			</td>
				<form action="?act=prodmod" method="post">
			<td align="center"><input type="text" size="20" name="types" value="<?php echo $prod['types']; ?>" ></td>
			<td align="center"><input type="text" size="6" name="prix" value="<?php echo fnbr($prod['prix']); ?>" ></td>
			<td align="center"><input type="text" size="6" name="produitin" value="<?php echo fnbr($prod['produitin']); ?>" ></td>
			<td align="center"><input type="text" size="10" name="unite" value="<?php echo $prod['unite']; ?>" ></td>
			<td align="center"><input type="text" size="6" name="produitend" value="<?php echo fnbr($prod['produitend']); ?>" ></td>
			<td align="center">
				<input type="text" size="6" name="ventes" value="<?php echo fnbr($prod['ventes']); ?>" >
				<?php $totvente = $totvente + $prod['ventes'] ;?>
			</td>
			<td align="center"><?php echo '<input type="checkbox" name="produitno" value="no" '; if ($prod['produitno'] == 'no') { echo 'checked';} echo'>'; ?></td>
			<td align="center"><input type="text" size="10" name="degustation" value="<?php echo $prod['degustation']; ?>" ></td>
			<td align="center"><input type="text" size="6" name="promoin" value="<?php echo fnbr($prod['promoin']); ?>" ></td>
			<td align="center"><input type="text" size="6" name="promoout" value="<?php echo fnbr($prod['promoout']); ?>" ></td>
			<td align="center"><input type="text" size="6" name="promoend" value="<?php echo fnbr($prod['promoend']); ?>" ></td>
			<td>
					<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>">
					<input type="hidden" name="idanimbuildproduit" value="<?php echo $prod['idanimbuildproduit'];?>">
					<input type="submit" name="Modifier" value="M">
				</form>
			</td>
			<td align="center">
					<form action="?act=proddel" method="post">
						<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>">
						<input type="hidden" name="idanimbuildproduit" value="<?php echo $prod['idanimbuildproduit'];?>">
						<input type="submit" name="Modifier" value="S">
					</form>
			</td>
		</tr>
<?php
$compt++;
} ?>
	<form action="?act=prodadd" method="post">
		<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>"> 
		<tr>
			<td><input type="submit" name="Modifier" value="A"></td>
			<td align="center"><input type="text" size="20" name="types" value=""></td>
			<td colspan="2">&nbsp;</td>
			<td colspan="2" align="right"><b>Total ventes :</b></td>
			<td><b><?php echo $totvente?></b></td>
			<td colspan="6">&nbsp;</td>
			<td><input type="submit" name="Modifier" value="A"></td>
		</tr>
	</form>
</table>
<br>
<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
	<tr>
		<td width="50%">
			<form action="?act=grpshow" method="post">
				<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>"> 
				<input type="submit" name="action" value="&lt;&lt;&nbsp; <?php echo $tool_05c;?>"> 
			</form>
		</td>
		<td width="50%" align="right">
			<form action="?act=shoplist" method="post">
				<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>"> 
				<input type="submit" name="action" value="<?php echo $tool_03; ?> &nbsp;&gt;&gt;"> 
			</form>
		</td>
	</tr>
</table>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		// Highlight modified lines
		$("tr input").change(function () { $(this).parents('tr').css({'background-color' : '#FF6600'}); })
	});
</script>