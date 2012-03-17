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
	exec('find '.Conf::read('Env.root').'document/people/'.prezero($_SESSION['idpeople'], 5).' -name "FicheSalaire-??????.pdf"', $files);

	foreach ($files as $file) if (preg_match("/FicheSalaire\-(\d{6})\.pdf$/", $file, $m)) $fichessalaire[$m[1]] = $file;

	asort($fichessalaire);

	$last_annee = '';

	foreach ($fichessalaire as $periode => $fichier) {
		$annee = substr($periode, 0, 4);
		$mois  = substr($periode, 4, 2);
		if ($annee != $last_annee) echo '<h1 style="text-align: center;">'.$annee.'</h1>';
		echo '<img src="'.STATIK.'illus/minipdf.gif" width="16" height="16"> <a href="/'.substr($fichier, strlen(Conf::read('Env.root'))).'" style="font-size: 12px;">'.$fichesalaire_01.' '.mb_convert_encoding(strftime("%B %Y", strtotime($annee."-".$mois."-01")), 'HTML-ENTITIES', 'ISO-8859-1').'</a><br>';

		$last_annee = $annee;
	}
?>
</div>