<?php 
define('NIVO', '../../');
include NIVO."includes/entete.php" ;

function fphone ($num) {
	$fnum = array();

	if (!empty($num)) {
		## clean des charactères inutiles
		$num = cleannombreonly($num);
		$num = ltrim($num, "0");
		$fnum['clean'] = $num;
		
		## definir le type de num
		if ((($num{0} == 4) and (strlen($num) == 9)) or ((substr($num, 0, 3) == 324) and (strlen($num) == 11))) { ## gsm belge
			if (substr($num, 0, 3) == 324) $num = substr($num, 2);
			$fnum['type'] = 'gsm';
			$fnum['country'] = 'BE';
			$fnum['human'] = '0'.substr($num, 0, 3).' '.substr($num, 3, 2).' '.substr($num, 5, 2).' '.substr($num, 7, 2);
			$fnum['sms'] = '32'.$num;
			$fnum['err'] = 'OK';
		} elseif (((substr($num, 0, 3) == 336) and (strlen($num) == 11)) or (($num{0} == 6) and (strlen($num) == 9))) { ## gsm francais
			if ($num{0} == 6) $num = '33'.$num;
			$fnum['type'] = 'gsm';
			$fnum['country'] = 'FR';
			$fnum['human'] = '0033 6'.substr($num, 3, 2).' '.substr($num, 5, 2).' '.substr($num, 7, 2).' '.substr($num, 9, 2);
			$fnum['sms'] = '33'.substr($num, 2);
			$fnum['err'] = 'OK';
		} elseif ((substr($num, 0, 3) == 316) and (strlen($num) == 11)) { ## gsm Hollandais
			$fnum['type'] = 'gsm';
			$fnum['country'] = 'NL';
			$fnum['human'] = '0031 6'.substr($num, 3, 2).' '.substr($num, 5, 2).' '.substr($num, 7, 2).' '.substr($num, 9, 2);
			$fnum['sms'] = '31'.substr($num, 2);
			$fnum['err'] = 'OK';
		} else {
			$fnum['err'] = 'BAD';
		}

		return $fnum ;
	} else return '';
}

$smss = $DB->getArray("SELECT s.idsms, s.senddate, s.modifydate, s.message, s.gsmpeople, s.status, p.pnom, p.pprenom FROM sms s 
						LEFT JOIN people p ON p.idpeople = s.idpeople
						WHERE idagent = ".$_SESSION['idagent']."
						ORDER BY s.idsms DESC LIMIT 200");

?>
	<div id="centerzonelarge">
		Tes 200 derniers sms : 
		<table class="sortable-onload-0 paginate-25 rowstyle-alt no-arrow" border="0" width="90%" cellspacing="1" align="center">
			<thead>
				<tr>
					<th class="sortable-numeric favour-reverse">IDSMS</th>
					<th class="sortable-text">People</th>
					<th class="sortable-text">GSM People</th>
					<th class="sortable-keep">Date d'envoi'</th>
					<th class="sortable-date">Changement de statut</th>
					<th class="sortable-text">Texte</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($smss as $sms){
					switch($sms['status'])
					{
						case "003":
							$color = "#FAC087";
						break;
						case "004":
							$color = "#8DFA87";
						break;
						case "":
							$color = "#FF8B8B";
						break;
						default:
							$color = "#B7B7B7";
					}
					?>	<tr style="background-color:<?php echo $color; ?>;">
							<td><?php echo $sms['idsms']; ?></td>
							<td><?php echo $sms['pprenom']." ".$sms['pnom']; ?></td>
							<td><?php $phone = fphone($sms['gsmpeople']); echo $phone['human'] ?></td>
							<td><?php echo $sms['senddate']; ?></td>
							<td><?php echo $sms['modifydate']; ?></td>
							<td title="<?php echo $sms['message']; ?>"><?php echo showmax($sms['message'],40); ?></td>
						</tr>
						<?php
				} ?>
			</tbody>
		</table>
		<table align="center" style="color:black;">
			<tr style="background-color:#8DFA87;">
				<td>Message envoyé au destinataire</td>
			</tr>
			<tr style="background-color:#FAC087;">
				<td>Message envoyé mais pas encore reçu</td>
			</tr>
			<tr style="background-color:#FF8B8B;">
				<td>Echec de l'envoi du message</td>
			</tr>
			<tr style="background-color:#B7B7B7;">
				<td>Statut du message inconnu</td>
			</tr>
		</table>
	</div>

<?php include(NIVO."includes/pied.php"); ?>