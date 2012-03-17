<?php $classe = "planning" ; ?>
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
					<li> <?php echo $anim_sales_menu_06a; ?>
					<li> <b><?php echo $anim_sales_menu_06b; ?></b>
					<li> <?php echo $anim_sales_menu_06c; ?>
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
<Fieldset class="width">
	<legend class="width"><?php echo $anim_sales_00; ?></legend>
			<form action="anim-vente.php?table=table&lang=<?php echo $_SESSION['lang']; ?>" method="post" target="_blank">
				<table class="standard" border="0" cellspacing="2" cellpadding="2" align="center" width="90%">
					<tr>
						<td class="etiq3">
							<?php echo $anim_sales_01; ?>
						</td>
						<td class="contenu">
							<?php echo $anim_sales_02; ?> : <input type="text" size="10" name="date1" value=""> <?php echo $anim_sales_03; ?> : <input type="text" size="10" name="date2" value="">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							(<?php echo $tool_55; ?> 25/12/2006)
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="etiq3">
							Job ID 
						</td>
						<td class="contenu">
							N&deg; : <input type="text" size="5" name="idanimjob" value="">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="etiq3">
							<?php echo $anim_sales_04; ?>
						</td>
						<td class="contenu">
							<input type="radio" name="titre" value="0" checked> <?php echo $anim_sales_08; ?> / <input type="radio" name="titre" value="1"> <?php echo $anim_sales_07; ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="etiq3">
							<?php echo $anim_sales_05; ?>
						</td>
						<td class="contenu">
							<input type="radio" name="note" value="0" checked> <?php echo $anim_sales_08 ?> / <input type="radio" name="note" value="1"> <?php echo $anim_sales_07; ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="etiq3">
							<?php echo $anim_sales_06; ?>
						</td>
						<td class="contenu">
							<input type="radio" name="tot" value="0" checked> <?php echo $anim_sales_08 ?> / <input type="radio" name="tot" value="1"> <?php echo $anim_sales_07 ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="etiq3">
							<?php echo $anim_sales_09 ?>
						</td>
						<td class="contenu">
							<input type="radio" name="pdf" value="0" checked> <?php echo $anim_sales_08 ?> / <input type="radio" name="pdf" value="1"> <?php echo $anim_sales_07 ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
							<input type="reset" name="Reset" value="Reset">
						</td>

						<td align="center">
							<input type="submit" name="Modifier" value="<?php echo $anim_sales_10 ?>">
						</td>
					</tr>
				</table>
			</form>
	<br>	
</fieldset>
</div>