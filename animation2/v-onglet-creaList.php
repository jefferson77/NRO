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
	<legend class="width">Creation</legend>
	<table border="0" cellspacing="1" cellpadding="0" align="center" width="100%">
		<tr>
			<td colspan="22" align="right">
				<img src="<?php echo STATIK ?>illus/info.png" alt="<?php echo $tool_05b; ?>"> &nbsp;
				<?php echo $tool_55; ?> <font color="#00427B">01/01/2005</font> &nbsp;&nbsp;&nbsp;
				<img src="<?php echo STATIK ?>illus/info.png" alt="<?php echo $tool_05b; ?>"> &nbsp;
				<?php echo $tool_56; ?> <font color="#00427B">15:00</font> &nbsp;&nbsp;&nbsp;
				<img src="<?php echo STATIK ?>illus/redtrash.gif" alt="<?php echo $tool_05b; ?>"> : <?php echo $tool_05b; ?>
			</td>
			<td></td>
		</tr>
		<tr>
			<td colspan="4"></td>
			<td class="etiq2" align="center" colspan="4">Prestations </td>
			<td class="etiq2" align="center" colspan="2">D&eacute;plac.</td>
			<td class="etiq2" align="center" colspan="2">Frais </td>
			<td class="etiq2" align="center" colspan="2">Livraison </td>
			<td class="etiq2" align="center">Stand </td>
			<td class="etiq2" align="center">Produit </td>
			<td class="etiq2"></td>
		</tr>
		<tr class="vip">
			<td class="etiq2"></td>
			<td class="etiq2" align="center">#</td>
			<td class="etiq2" align="center">Du</td>
			<td class="etiq2" align="center">Au</td>
			<td class="etiq2" align="center">IN</td>
			<td class="etiq2" align="center">OUT</td>
			<td class="etiq2" align="center">IN</td>
			<td class="etiq2" align="center">OUT</td>
			<td class="etiq2" align="center">Auto</td>
			<td class="etiq2" align="center">KM</td>
			<td class="etiq2" align="center">FR p</td>
			<td class="etiq2" align="center">FR f</td>
			<td class="etiq2" align="center">Liv p</td>
			<td class="etiq2" align="center">Liv f</td>
			<td class="etiq2" align="center">stand</td>
			<td class="etiq2" align="center">promo</td>
			<td class="etiq2"></td>
		</tr>
		<tr>
			<td colspan="4"></td>
			<td colspan="4"></td>
			<td colspan="2"></td>
			<td colspan="2"></td>
			<td colspan="2"></td>
			<td></td>
		</tr>
<?php


$dateincheck = 1;
$dateoutcheck = 1;

foreach ($buildinfos as $infos) {
	$i++;
	if ($infos['animdate1'] == '0000-00-00') $dateincheck = 0;
	if ($infos['animdate2'] == '0000-00-00') $dateoutcheck = 0;
	if ($infos['animnombre'] < 1) $infos['animnombre'] = 1;
?>
			<tr class="contenu" style="background-color: #FFF;">
				<form action="?act=grpdel" method="post" onSubmit="return confirm('<?php echo $tool_20; ?>')">
					<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>">
					<input type="hidden" name="idanimbuild" value="<?php echo $infos['idanimbuild'];?>">
					<td class="standard"><input type="submit" class="btn redtrash" name="action" value="Delete" width="12"> </td>
				</form>
				<form action="?act=grpmod" method="post">
				<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>">
				<input type="hidden" name="idanimbuild" value="<?php echo $infos['idanimbuild'];?>">
					<td class="standard"><input class="mini" type="text" size="1" name="animnombre" value="<?php echo $infos['animnombre']; ?>"></td>
					<td class="standard"><input class="mini" type="text" size="7" name="animdate1" value="<?php echo fdate($infos['animdate1']); ?>"></td>
					<td class="standard"><input class="mini" type="text" size="7" name="animdate2" value="<?php echo fdate($infos['animdate2']); ?>"></td>
					<td class="standard"><input class="mini" type="text" size="3" name="animin1" value="<?php echo ftime($infos['animin1']); ?>"></td>
					<td class="standard"><input class="mini" type="text" size="3" name="animout1" value="<?php echo ftime($infos['animout1']); ?>"></td>
					<td class="standard"><input class="mini" type="text" size="3" name="animin2" value="<?php echo ftime($infos['animin2']); ?>"></td>
					<td class="standard"><input class="mini" type="text" size="3" name="animout2" value="<?php echo ftime($infos['animout2']); ?>"></td>
					<td class="standard"><input class="mini" type="checkbox" name="kmauto" value="Y" <?php echo ($infos['kmauto'] == 'Y')?' checked':'' ?>></td>
					<td class="standard"><input class="mini" type="text" size="3" name="kmfacture" value="<?php echo fnbr($infos['kmfacture']); ?>" <?php echo ($infos['kmauto'] == 'Y')?' disabled':'' ?>></td>
					<td class="standard"><input class="mini" type="text" size="3" name="frais" value="<?php echo fnbr($infos['frais']); ?>"></td>
					<td class="standard"><input class="mini" type="text" size="3" name="fraisfacture" value="<?php echo fnbr($infos['fraisfacture']); ?>"></td>
					<td class="standard"><input class="mini" type="text" size="3" name="livraisonpaye" value="<?php echo fnbr($infos['livraisonpaye']); ?>"></td>
					<td class="standard"><input class="mini" type="text" size="3" name="livraisonfacture" value="<?php echo fnbr($infos['livraisonfacture']); ?>"></td>
					<td class="standard"><input class="mini" type="text" size="3" name="stand" value="<?php echo fnbr($infos['stand']); ?>"></td>
					<td class="standard"><input class="mini" type="text" size="15" name="promo" value="<?php echo stripslashes($infos['promo']); ?>"></td>
					<td class="standard"><input class="mini" type="submit" name="action" value="M"></td>
				</form>
			</tr>
		<?php } ?>
		<tr>
			<td colspan="6"></td>
			<td colspan="7"></td>
			<td colspan="2"></td>
			<td colspan="2"></td>
			<td colspan="4"></td>
			<td colspan="2"></td>
		</tr>
		<tr class="vip">
		</tr>
		<?php if ($infosjob['datein'] != '3000-00-00') {?>
			<tr>
				<td colspan="23" align="left"><?php echo $vipdetail_1_06; ?> :
				<?php echo $tool_50; ?> <?php echo fdate($dates['datein']); ?> <?php echo $tool_51; ?> <?php echo fdate($dates['dateout']); ?></td>
			</tr>
		<?php } ?>
		<tr>
			<td colspan="23" align="center">
				<form action="?act=grpadd" method="post">
					<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>">
					<input type="submit" name="action" value="<?php echo $vipdetail_1_07; ?>">
				</form>
			</td>
		</tr>
	</table>
</fieldset>
<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
	<tr>
		<td colspan="23" align="right">
			<?php if (($dateincheck == 0) or ($dateoutcheck == 0)) { ?>
				Au moins une date n'est pas valide. &nbsp;
			<?php } else { ?>
				<form action="?act=grpverif" method="post">
					<input type="hidden" name="idanimjob" value="<?php echo $_REQUEST['idanimjob'];?>">
					<input type="submit" name="action" value="<?php echo $tool_03; ?> &nbsp;&gt;&gt;">
				</form>
			<?php } ?>
		</td>
	</tr>
</table>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		// Highlight modified lines
		$("tr input").change(function () { $(this).parents('tr').css({'background-color' : '#FF6600'}); })
		// disable de kmfacture sur kmauto est coché
		$("tr input[name='kmauto']").change(function () {
			if ($(this).attr('checked')) {
				$(this).parent("td").next("td").children("input[name='kmfacture']").attr({disabled: 'disabled', value:''});
			} else {
				$(this).parent("td").next("td").children("input[name='kmfacture']").attr('disabled', '');
			}

		})
	});
</script>