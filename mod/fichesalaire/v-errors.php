<?php
setlocale(LC_TIME, 'fr_FR');
$splitdir = Conf::read('Env.root').'document/people/fichesalaire/split/'

?>
<div id="centerzonelarge">
<?php
## trouve les fiches qui n'ont pas été reconnues
$splitfolders = dirFiles($splitdir);

$unstored = array();

if (is_array($splitfolders)) {
	foreach ($splitfolders as $folder) {
		if (isEmptyDir($splitdir.$folder)) {
			rmdir($splitdir.$folder);
		} else {
			$unstored[$folder] = dirFiles($splitdir.$folder);
		}
	}
}

if (count($unstored) > 0) {
	echo '<div id="tabs">';
	echo '<ul>';
	foreach ($unstored as $period => $files) echo '<li><a href="#Page_'.$period.'">'.$period.' ('.count($files).')</a></li>';
	echo '</ul>';
	foreach ($unstored as $period => $files) {
		$fs = new FicheSalaire(substr($period, 0, 4), substr($period, 4, 2));
		?>
		<div id="Page_<?php echo $period ?>" width="100%">
			<table class="sortable-onload-0 rowstyle-alt no-arrow" border="0" width="100%" cellspacing="1" align="center">
				<thead>
					<tr>
						<th class="sortable-text">Fichier</th>
						<th class="sortable-text">Registre</th>
						<th class="sortable-text">Nom</th>
						<th class="sortable-text">Code</th>
						<th class="sortable-keep">Matches</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
			<?php
			foreach ($files as $file)
			{
				$ret = $fs->findPeopleInfos($splitdir.$period."/".$file);
				$peoples = (!empty($ret['regnat']))?$fs->storeFiche28110($splitdir.$period."/".$file, $ret['regnat']):array();
				if ((count($peoples) == 0) or (count($peoples) > 1)) {
					echo '<tr>
							<td><a href="'.Conf::read('Env.urlroot').'document/people/fichesalaire/split/'.$period.'/'.$file.'">'.$file.'</a></td>
							<td>'.$ret['regnat'].'</td>
							<td>'.$ret['name'].'</td>
							<td>'.$ret['codepeople'].'</td>
							<td>';
							foreach ($peoples as $key => $peop) echo '('.$peop['codepeople'].') '.$peop['pprenom'].' '.$peop['pnom'].'<br>';
					echo '</td><td>';

						if (count($peoples) > 1) {
							echo '<a href="'.NIVO.'data/people/adminpeople.php?act=groupeshow&amp;idpeoplesource='.$peoples[0]['idpeople'].'&amp;idpeoplecible='.$peoples[1]['idpeople'].'" target="_blank"><img border="0" src="'.STATIK.'illus/arrow-circle-double-135.png" width="16" height="16" alt="Arrow Circle Double 135"></a>';

						} else {
							# TODO : ajouter bouton d'effacement de la fiche
						}

					echo '</td></tr>';
				}
			} ?>
				</tbody>
			</table>

		</div>
		<?php
	}
	echo '</div>';
} else {
	echo 'Toutes les fiches de salaires sont identifiées';
}
?>
</div>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('#tabs').tabs();
	});
</script>