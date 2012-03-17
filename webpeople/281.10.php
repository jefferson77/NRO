<div class="news">
	<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
		<tr>
			<td class="fulltitre" colspan="2"><?php echo $tool_01;?></td>
		</tr>
		<tr>
			<td class="newstit"><?php echo $tool_10;?></td>
		</tr>
		<tr>
			<td class="newstxt">
				<img src="<?php echo STATIK ?>illus/minipdf.gif" width="16" height="16"> : <?php echo $contrat_08;?>
			</td>
		</tr>
	</table>
</div>

<div class="corps" align="center">
<?php
$files = array();
exec('find '.Conf::read('Env.root').'document/people/'.prezero($_SESSION['idpeople'], 5).' -name "281.10-????.pdf"', $files);
foreach ($files as $file) if (preg_match("/281.10\-(\d{4})\.pdf$/", $file, $m)) $f281[$m[1]] = $file;

foreach ($f281 as $annee => $fichier) {
	echo '<img src="'.STATIK.'illus/minipdf.gif" width="16" height="16"> <a href="/'.substr($fichier, strlen(Conf::read('Env.root'))).'" style="font-size: 12px;">'.$f281_01.' '.$annee.'</a><br>';
	echo '<span style="font-size: 12px;" >'.$f281_02.'</span><br><br>';
}
?>
</div>
