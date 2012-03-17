<?php
class hh
{
	# Config Values
	var $hhv = array (
		'3' =>  '100000000000000000000000',
		'4' =>   '10000000000000000000000',
		'5' =>    '1000000000000000000000',
		'6' =>     '100000000000000000000',
		'7' =>      '10000000000000000000',
		'8' =>       '1000000000000000000',
		'9' =>        '100000000000000000',
		'03' => '100000000000000000000000',
		'04' =>  '10000000000000000000000',
		'05' =>   '1000000000000000000000',
		'06' =>    '100000000000000000000',
		'07' =>     '10000000000000000000',
		'08' =>      '1000000000000000000',
		'09' =>       '100000000000000000',
		'10' =>        '10000000000000000',
		'11' =>         '1000000000000000',
		'12' =>          '100000000000000',
		'13' =>           '10000000000000',
		'14' =>            '1000000000000',
		'15' =>             '100000000000',
		'16' =>              '10000000000',
		'17' =>               '1000000000',
		'18' =>                '100000000',
		'19' =>                 '10000000',
		'20' =>                  '1000000',
		'21' =>                   '100000',
		'22' =>                    '10000',
		'23' =>                     '1000',
		'24' =>                      '100',
		'25' =>                       '10',
		'26' =>                        '1',
		'27' =>                        '0',
		'28' =>                        '0',
		'29' =>                        '0',
		'30' =>                        '0'
	);

	var $prtab = array();
	
	
	# fonctions de formatage
	function makehhcode ($amin = '00:00:00', $amout = '00:00:00', $pmin = '00:00:00', $pmout = '00:00:00') {
		$aminx = explode(':', $amin);
		$amoutx = explode(':', $amout);
		$pminx = explode(':', $pmin);
		$pmoutx = explode(':', $pmout);
		
		if ($amoutx[0] < $aminx[0]) $amoutx[0] += 24;
		if ($amoutx[1] > 0) $amoutx[0]++;
		if ($pmoutx[0] < $pminx[0]) $pmoutx[0] += 24;
		if ($pmoutx[1] > 0) $pmoutx[0]++;
		
		$AMin = $aminx[0];
		$AMout = $amoutx[0];
		$PMin = $pminx[0];
		$PMout = $pmoutx[0];
	
		$hhcode = '0';
		
		
		while ($AMin < $AMout) {
			if ($AMin > 30) break;
			$hhcode += $this->hhv[$AMin];
			$AMin += 1;
		}

		while ($PMin < $PMout) {
			if ($PMin > 30) break;
			$hhcode += $this->hhv[$PMin];
			$PMin += 1;
		}
		
		return sprintf("%024.0f",$hhcode);
	}
	
	function hhtable ($idpeople, $datein, $dateout) {
		
		$table = array();
		
		# anim #################################################################
		$anim = new db();
		$anim->inline("SELECT j.idagent, a.facnum, a.idanimation, a.hhcode, a.hin1, a.hout1, a.hin2, a.hout2, a.datem, s.societe, s.ville, s.glat, s.glong, a.kmpaye, a.kmfacture, c.societe as clsociete, s.idshop, s.adresse
		FROM animation a 
		LEFT JOIN animjob j ON a.idanimjob = j.idanimjob 
		LEFT JOIN shop s ON a.idshop = s.idshop
		LEFT JOIN client c ON j.idclient = c.idclient
		WHERE a.idpeople = '$idpeople' AND a.datem BETWEEN '$datein' AND '$dateout'");
		
		while($row = mysql_fetch_array($anim->result)) {
			$hhcode = $this->makehhcode($row['hin1'], $row['hout1'], $row['hin2'], $row['hout2']);
			
			$this->prtab[$row['datem']][] = array(
				'secteur' => 'AN', 
				'agent' => $row['idagent'], 
				'idmission' => $row['idanimation'], 
				'h1' => $row['hin1'], 
				'h2' => $row['hout1'], 
				'h3' => $row['hin2'], 
				'h4' => $row['hout2'],
				'hb' => '', 
				'hs' => '', 
				'client' => $row['clsociete'],
				'idshop' => $row['idshop'],
				'shop' => $row['societe'].' '.$row['ville'],
				'shoplat' => $row['glat'],
				'shoplong' => $row['glong'],
				'shopadr' => str_replace(' ', '+', $row['adresse']),
				'kmp' => $row['kmpaye'],
				'kmf' => $row['kmfacture'],
				'idfac' => $row['facnum'],
				'hh' => $hhcode);
			
			if (!array_key_exists($row['datem'], $table)) $table[$row['datem']] = 0; # Notice Undefined index datem

			$table[$row['datem']] = sprintf("%024.0f",$table[$row['datem']] + $hhcode);
			# mise à jour si hhcode n'est pas correct dans la mission
			if ($row['hhcode'] != $hhcode) {
				$updhhcode = new db();
				$sql = "UPDATE `animation` SET `hhcode` = '$hhcode' WHERE `idanimation` = '".$row['idanimation']."' LIMIT 1;";
				$updhhcode->inline($sql);
			}
			
		}
		
		# vip #################################################################
		$vip = new db();
		$vip->inline("SELECT j.idagent, v.facnum, v.idvip, v.hhcode, v.vipin, v.vipout, v.brk, v.vipdate, v.night, s.ville, s.societe, s.glong, s.glat, v.km, v.vkm, c.societe as clsociete, s.idshop, s.adresse
		FROM vipmission v 
		LEFT JOIN vipjob j ON v.idvipjob = j.idvipjob 
		LEFT JOIN shop s ON v.idshop = s.idshop
		LEFT JOIN client c ON j.idclient = c.idclient
		WHERE v.idpeople = '$idpeople' AND v.vipdate BETWEEN '$datein' AND '$dateout'");
		
		while($row = mysql_fetch_array($vip->result)) {
			$hhcode = $this->makehhcode($row['vipin'], $row['vipout']);
			
			$this->prtab[$row['vipdate']][] = array(
				'secteur' => 'VI', 
				'agent' => $row['idagent'], 
				'idmission' => $row['idvip'], 
				'h1' => $row['vipin'], 
				'h2' => $row['vipout'], 
				'h3' => '', 
				'h4' => '',
				'hb' => $row['brk'], 
				'hs' => $row['night'], 
				'client' => $row['clsociete'],
				'idshop' => $row['idshop'],
				'shop' => $row['societe'].' '.$row['ville'],
				'shoplat' => $row['glat'],
				'shoplong' => $row['glong'],
				'shopadr' => str_replace(' ', '+', $row['adresse']),
				'kmp' => $row['vkm'],
				'kmf' => $row['km'],
				'idfac' => $row['facnum'],
				'hh' => $hhcode);

			if (!array_key_exists($row['vipdate'], $table)) $table[$row['vipdate']] = 0; # Notice Undefined index vipdate
			
			$table[$row['vipdate']] = sprintf("%024.0f",$table[$row['vipdate']] + $hhcode);
			# mise à jour si hhcode n'est pas correct dans la mission
			if ($row['hhcode'] != $hhcode) {
				$updhhcode = new db();
				$sql = "UPDATE `vipmission` SET `hhcode` = '$hhcode' WHERE `idvip` = '".$row['idvip']."' LIMIT 1;";
				$updhhcode->inline($sql);
			}
			
		}
		
		# merch #################################################################
		$merch = new db();
		$merch->inline("SELECT m.idagent, m.facnum, m.idmerch, m.hhcode, m.hin1, m.hout1, m.hin2, m.hout2, m.datem, s.societe, s.ville, s.glat, s.glong, m.kmpaye, m.kmfacture , c.societe as clsociete, s.idshop, s.adresse
		FROM merch m
		LEFT JOIN shop s ON m.idshop = s.idshop
		LEFT JOIN client c ON m.idclient = c.idclient
		WHERE idpeople = '$idpeople' AND datem BETWEEN '$datein' AND '$dateout'");
		
		while($row = mysql_fetch_array($merch->result)) {
			$hhcode = $this->makehhcode($row['hin1'], $row['hout1'], $row['hin2'], $row['hout2']);
			
			$this->prtab[$row['datem']][] = array(
				'secteur' => 'ME', 
				'agent' => $row['idagent'], 
				'idmission' => $row['idmerch'], 
				'h1' => $row['hin1'], 
				'h2' => $row['hout1'], 
				'h3' => $row['hin2'], 
				'h4' => $row['hout2'],
				'hb' => '', 
				'hs' => '', 
				'client' => $row['clsociete'],
				'idshop' => $row['idshop'],
				'shop' => $row['societe'].' '.$row['ville'],
				'shoplat' => $row['glat'],
				'shoplong' => $row['glong'],
				'shopadr' => str_replace(' ', '+', $row['adresse']),
				'kmp' => $row['kmpaye'],
				'kmf' => $row['kmfacture'],
				'idfac' => $row['facnum'],
				'hh' => $hhcode
			);

			if (!array_key_exists($row['datem'], $table)) $table[$row['datem']] = 0; # Notice Undefined index datem

			$table[$row['datem']] = sprintf("%024.0f",$table[$row['datem']] + $hhcode);
			# mise à jour si hhcode n'est pas correct dans la mission
			if ($row['hhcode'] != $hhcode) {
				$updhhcode = new db();
				$sql = "UPDATE `merch` SET `hhcode` = '$hhcode' WHERE `idmerch` = '".$row['idmerch']."' LIMIT 1;";
				$updhhcode->inline($sql);
			}
			
		}

		return $table;
	}
}