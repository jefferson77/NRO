<?php

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


function sendsms($dest, $from, $text)
{
	GLOBAL $DB;
	
	$to = implode(",",$dest);
	
	$arraygsm = $DB->getArray("SELECT gsm FROM people WHERE idpeople IN (".$to.")");
	
	$strgsm = array();

	foreach($arraygsm as $row)
	{
		$gsms = fphone($row['gsm']);
		if(($gsms['err'] == 'OK') and ($gsms['type']=='gsm'))
		{
			$strgsm[] = $gsms['sms'];
		}
	}

	$to = implode(",",$strgsm);
	
	$data = array("api_id"		=>	"3123166",
				  "user"		=>	"Exception2",
				  "password"  	=>	"Exception01",
				  "to" 			=>	$to,
				  "text" 		=>	$text,
				  "callback"  	=>	"3",
				  "req_feat"  	=>	"0x0010",
				  "from" 		=>	"Exception"
				);

	$ch = curl_init();
	## requête googlmaps
	curl_setopt($ch, CURLOPT_URL, 'http://api.clickatell.com/http/sendmsg');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$string = curl_exec($ch);
	curl_close($ch); 

	if(count($dest)>1)
	{
		$explid = explode("ID: ", $string);
		$expl = array();
		foreach($explid as $substr)
		{
			if(!empty($substr))
			{
				$explto = explode(" To: ", $substr);
				$expl[$explto[0]] = $explto[1];
			}
		}
		# expl = tableau final avec array(parsekey -> gsm)
		$i=0;
		foreach($expl as $parsekey => $gsm)
		{
			if(!empty($parsekey))
			{
				$DB->inline("INSERT INTO sms (senddate, modifydate, message, idagent, idpeople, gsmpeople, msgid) VALUES (NOW(), NOW(),'".addslashes($_POST['text'])."' , ".$from.", ".$dest[$i].", '".$gsm."', '".$parsekey."' )");
			}
			$i++;
		}
	}
	else
	{
		$parsekey = substr($string,4);
		$DB->inline("INSERT INTO sms (senddate, modifydate, message, idagent, idpeople, gsmpeople, msgid) VALUES (NOW(), NOW(),'".addslashes($_POST['text'])."' , ".$from.", ".$dest[0].", ".$to.", '".$parsekey."' )");
		
	}
}

?>