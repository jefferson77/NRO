<div class="news">
	<table width="95%" border="0" align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="fulltitre" colspan="2"><?php echo $tool_01; ?><br><?php echo $anim_sales_00; ?></td>
		</tr>
		<tr>
			<td class="newstit">1. <?php echo $anim_sales_menu_01; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li> <?php echo $anim_sales_menu_02a; ?>
					<li> <?php echo $anim_sales_menu_02b; ?>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="newstit">2. <?php echo $anim_sales_menu_03; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li><?php echo $anim_sales_menu_04; ?>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="newstit">3. <?php echo $anim_sales_menu_05; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li> <?php echo $anim_sales_menu_06; ?>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="newstit">4. <?php echo $anim_sales_menu_07; ?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<ul>
					<li> <?php echo $anim_sales_menu_08; ?>
				</ul>
			</td>
		</tr>
		<tr>
			<td class="newstxt">
				<?php echo $anim_sales_menu_11; ?>
			</td>
		</tr>
	</table>
</div>
<div class="corps">
		<fieldset class="width">
			<legend  class="width">Recherche pour le planning des Merch</legend>
			<form action="?act=planning" method="post">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td>Genre</td>
						<td>Rack assistance</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>Semaine</td>
						<td>De : <input type="text" size="10" name="weekm1" value=""> &agrave; : <input type="text" size="10" name="weekm2" value=""></td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td>Ann&eacute;e</td>
						<td>
							<input type="text" size="10" name="yearm1" value="<?php echo date("Y") ?>">
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td align="center" colspan="2"><input type="submit" name="Modifier" value="Rechercher"></td>
					</tr>
				</table>
			</form>
		</fieldset>
</div>