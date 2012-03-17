<?php
# Entete de page
define('NIVO', '../');
$Titre = 'MERCH';

# Classes utilisées
include NIVO."includes/entete.php" ;

$DB->inline("SELECT
	m.weekm, m.yearm, m.idshop, m.idpeople, MIN(m.datem) AS mindate, m.contratencode, m.rapportencode,
	s.societe, s.ville,
	p.pprenom, p.pnom, p.gsm
FROM merch m
	LEFT JOIN mercheasproduit pr ON m.idmerch = pr.idmerch
	LEFT JOIN people p ON m.idpeople = p.idpeople
	LEFT JOIN shop s ON m.idshop = s.idshop
WHERE m.datem BETWEEN '".date("Y-m-d", strtotime("-15 weeks"))."' AND '".date("Y-m-d", strtotime("last sunday"))."'
	AND m.genre LIKE 'EAS'
	AND m.idclient != '2346'
GROUP BY m.idmerch
HAVING (SUM(fo1a + fo1b + fo1c + fo2a + fo2b + fo2c + fo3a + fo3b + fo3c + fo4a + fo4b + fo4c + pa1a + pa1b + pa1c + pa2a + pa2b + pa2c + pa3a + pa3b + pa3c + pa4a + pa4b + pa4c + pa5a + pa5b + pa5c + pa6a + pa6b + pa6c + pa7a + pa7b + pa7c + pa8a + pa8b + pa8c + pa9a + pa9b + pa9c + pa10a + pa10b + pa10c + te1a + te1b + te1c + te2a + te2b + te2c +
			te3a + te3b + te3c + te4a + te4b + te4c + te5a + te5b + te5c + te6a + te6b + te6c + te7a + te7b + te7c + te8a + te8b + te8c + ep1a + ep1b + ep1c + ep2a + ep2b + ep2c + ep3a + ep3b + ep3c + ep4a + ep4b + ep4c + ep5a + ep5b + ep5c + ba1a + ba1b + ba1c + ba2a + ba2b + ba2c + ba3a + ba3b + ba3c + ba4a + ba4b + ba4c + ba5a + ba5b + ba5c + ba6a + ba6b + ba6c + au1a +
			au1b + au1c + au2a + au2b + au2c + au3a + au3b + au3c + au4a + au4b + au4c + au5a + au5b + au5c) IS NULL
		OR SUM(fo1a + fo1b + fo1c + fo2a + fo2b + fo2c + fo3a + fo3b + fo3c + fo4a + fo4b + fo4c + pa1a + pa1b + pa1c + pa2a + pa2b + pa2c + pa3a + pa3b + pa3c + pa4a + pa4b + pa4c +
			pa5a + pa5b + pa5c + pa6a + pa6b + pa6c + pa7a + pa7b + pa7c + pa8a + pa8b + pa8c + pa9a + pa9b + pa9c + pa10a + pa10b + pa10c + te1a + te1b + te1c + te2a + te2b + te2c +
			te3a + te3b + te3c + te4a + te4b + te4c + te5a + te5b + te5c + te6a + te6b + te6c + te7a + te7b + te7c + te8a + te8b + te8c + ep1a + ep1b + ep1c + ep2a + ep2b + ep2c + ep3a + ep3b +
			ep3c + ep4a + ep4b + ep4c + ep5a + ep5b + ep5c + ba1a + ba1b + ba1c + ba2a + ba2b + ba2c + ba3a + ba3b + ba3c + ba4a + ba4b + ba4c + ba5a + ba5b + ba5c + ba6a + ba6b + ba6c + au1a +
			au1b + au1c + au2a + au2b + au2c + au3a + au3b + au3c + au4a + au4b + au4c + au5a + au5b + au5c) = 0)
ORDER BY m.weekm, p.pnom, s.societe
");

?>
<div id="centerzonelarge">
<?php

echo '<table class="sortable-onload-0 rowstyle-alt paginate-27 no-arrow" border="0" width="95%" cellspacing="1" align="center">
			<thead>
				<tr>
					<th class="sortable-numeric" width="50">Sem</th>
					<th class="sortable-text" width="20">C</th>
					<th class="sortable-text" width="20">R</th>
					<th class="sortable-text">People</th>
					<th class="sortable-text">Gsm</th>
					<th class="sortable-text">Magasin</th>
					<th class="sortable-text">Ville</th>
					<th><input type="submit" class="btn phone" name="send" value="sms"></th>
				</tr>
			</thead>
			<tbody>';

while ($row = mysql_fetch_array($DB->result)) {
			echo '
			<tr ondblclick="location.href=\'adminmerch.php?act=planningweekeas&idpeople='.$row['idpeople'].'&weekm='.$row['weekm'].'&idshop='.$row['idshop'].'&genre=EAS&datem='.$row['mindate'].'\'">
				<td><font color="#A8A8A8">'.$row['yearm'].'</font> '.prezero($row['weekm'], 2).'</td>
				<td style="text-align: center;padding: 0px;">'.((!empty($row['contratencode']))?'x':'').'</td>
				<td style="text-align: center;padding: 0px;">'.((!empty($row['rapportencode']))?'x':'').'</td>
				<td>'.$row['pnom'].' '.$row['pprenom'].'</td>
				<td>'.$row['gsm'].'</td>
				<td>'.$row['societe'].'</td>
				<td>'.$row['ville'].'</td>
				<td style="padding: 0px; width: 10px;"><input type="checkbox" name="idsms[]" value="'.$row['idpeople'].'"></td>
			</tr>';
}
echo '
		</table>';
?>
<div style="font-size: 10px;padding: 0px 10px;">
	C = Contrat Encod&eacute;<br>
	R = Rapport Encod&eacute;
</div>
</div>
<?php include NIVO."includes/pied.php" ; ?>