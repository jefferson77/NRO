<?php
# Fiches Salaires
exec('find '.Conf::read('Env.root').'document/people -name "FicheSalaire-??????.pdf"', $files);
foreach ($files as $file) if (preg_match("/people\/(\d{5})\/FicheSalaire\-(\d{6})\.pdf$/", $file, $m)) $parmois[$m[2]][] = $m[1];
ksort($parmois);

# Fiches 281.10
$files = array();
exec('find '.Conf::read('Env.root').'document/people -name "281.10-????.pdf"', $files);
foreach ($files as $file) if (preg_match("/people\/(\d{5})\/281.10\-(\d{4})\.pdf$/", $file, $m)) $paran[$m[2]][] = $m[1];

?>
<div id="centerzonelarge">
	<table border="0" cellspacing="5" cellpadding="5" width="100%">
		<tr>
			<td width="50%" valign="top">
				<h1>Fiches Salaires</h1>
				<form action="?act=send" method="post" accept-charset="utf-8">
					<input type="hidden" name="doctype" value="fiches">
					<?php
					$jump = '';
					foreach ($parmois as $period => $peoples) {
						$year = substr($period, 0, 4);
						$month = substr($period, 4, 2);

						if ($jump != $year) {
							echo '<h2>'.$year.'</h2>';
							$jump = $year;
						}
						echo '<input type="checkbox" name="periods[]" value="'.$period.'"> '.strftime("%B", strtotime($year."-".$month."-01")).' '.count($peoples).' peoples<br>';
					}
					?>
					<p align="center"><input type="submit" value="Continue"></p>
				</form>
			</td>
			<td width="50%" valign="top">
				<h1>281.10</h1>
				<form action="?act=send" method="post" accept-charset="utf-8">
					<input type="hidden" name="doctype" value="281.10">
					<?php
					$jump = '';
					foreach ($paran as $period => $peoples) {
						$year = substr($period, 0, 4);
						$month = substr($period, 4, 2);

						if ($jump != $year) {
							echo '<h2>'.$year.'</h2>';
							$jump = $year;
						}
						echo '<input type="checkbox" name="periods[]" value="'.$period.'"> '.count($peoples).' peoples<br>';
					}
					?>
					<p align="center"><input type="submit" value="Continue"></p>
				</form>
			</td>
		</tr>
	</table>
</div>