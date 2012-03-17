<?php

// This code was converted to PHP from Visual C++
// by Steven Brendtro on behalf of imaginerc.com

// The original code and an article can be found
// on CodeGuru at:
// http://www.codeguru.com/Cpp/Cpp/algorithms/print.php/c5115/

// Example Usage:
//   $oGC = new GeoCalc();
//   print "GCDistance: " . $oGC->GCDistance(38.9333,-94.3253,38.9314,-94.4876) . "<br />\n";
//   print "GCAzimuth: " . $oGC->GCAzimuth(38.9333,-94.3253,38.9314,-94.4876) . "<br />\n";
//   print "ApproxDistance: " . $oGC->ApproxDistance(38.9333,-94.3253,38.9314,-94.4876) . "<br />\n";
//   print "EllipsoidDistance: " . $oGC->EllipsoidDistance(38.80126649,-94.44590241,43.3368,-96.8755) . "<br /><br />\n";
//   print "EllipsoidDistance In Miles: " . ConvKilometersToMiles($oGC->EllipsoidDistance(38.80126649,-94.44590241,43.3368,-96.8755));

class GeoCalc {

  var $PI = 3.14159265359;
  var $TWOPI = 6.28318530718;
  var $DE2RA = 0.01745329252;
  var $RA2DE = 57.2957795129;
  var $ERAD = 6378.135;
  var $ERADM = 6378135.0;
  var $AVG_ERAD = 6371.0;
  var $EPS = 0.000000000005;
  var $KM2MI = 0.621371;
  var $FLATTENING =  0;
  
  var $plat = 0;
  var $plong = 0;
  var $slat = 0;
  var $slong = 0;

  function GeoCalc() {
  	$this->FLATTENING = 1.0/298.26;  // Earth flattening
                                     // (WGS 1972)
    return;
  }
  function getCoordinates($num,$adresse,$ville,$pays, $cp) {
  	if(empty($pays)) $pays='Belgique';
  	if(empty($adresse)) $alert = 'adresse ';
	if(empty($ville)) $alert .= 'ville ';
	if(!empty($alert)) {
		alertBox('champ manquant: '.$alert);
		return false;
	} else {
		$query = remaccents($num).'+'.(preg_replace('`[^\w]`', '+', remaccents($adresse))).',+'.((!empty($cp)?"$cp":'')).'+'.(preg_replace('`[^\w]`', '+', remaccents($ville))).',+'.(preg_replace('`[^\w]`', '+', remaccents($pays)));
		
		$ch = curl_init();
		## requête googlmaps 
		# NEw Key : ABQIAAAAncK7u6Y6vCOout4CjUjPfhSbH9EkmmQTmyUx1tkKHz09g2FIOhSnJBa42uTM9Ld3c_iZ_wRU9n7fiQ (pour l'IP 77.109.79.37)
		# Old Key : ABQIAAAAsrLDziJp5Mx8mtml8DSNVxRhiG2tBNWSgC51-L9sk-C3HvT7BRQfj7CeA-VPM-WapMa0fmwBAqZj_Q (pour l'IP 62.166.203.210)
		curl_setopt($ch, CURLOPT_URL, 'http://maps.google.be/maps/geo?q='.$query.'&output=xml&key=ABQIAAAAncK7u6Y6vCOout4CjUjPfhSbH9EkmmQTmyUx1tkKHz09g2FIOhSnJBa42uTM9Ld3c_iZ_wRU9n7fiQ');
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		print_r($data);
		curl_close($ch);
		## parsing googlemaps
		preg_match('`<name>([^><]+)</name>`', $data, $name);
		preg_match_all('`<ThoroughfareName>([^><]+)</ThoroughfareName>`', $data, $address);
		preg_match_all('`<PostalCodeNumber>([^><]+)</PostalCodeNumber>`', $data, $cp);
		preg_match_all('`<LocalityName>([^><]+)</LocalityName>`', $data, $ville);
		preg_match_all('`<coordinates>([^><]+)</coordinates>`', $data, $coordinates);
		$results['query'] = $query;
		for($i=0; $i<count($address[1]); $i++) {
			$coordinates[1][$i] = explode(',',$coordinates[1][$i]);
			$results['response'][$i] = array('address'=>$address[1][$i], 'cp'=>$cp[1][$i], 'ville'=>$ville[1][$i], 'lon'=>$coordinates[1][$i][0], 'lat'=>$coordinates[1][$i][1]);
		}
		return $results;		
	}
  }
  
  function calculate($idpeople, $idshop, $adr) {
  	$people = new db();
	$shop = new db();
	
	##### glat1 et glong1 de quelle adresse #####
		// 1 = adresse 1
		// 2 = adresse 2
	if($adr == '1') {
		$glat1 = 'glat1';
		$glong1 = 'glong1';
	}
	else {
		$glat1 = 'glat2';
		$glong1 = 'glong2';
	}
	###########################################
	$sql = "SELECT ".$glat1.", ".$glong1." FROM people WHERE idpeople = ".$idpeople;
	$people->inline($sql);
	
	$sql2 = "SELECT glat, glong FROM shop WHERE idshop = ".$idshop;
	$shop->inline($sql2);
	//$people->inline("SELECT '".$glat1."', '".$glong1."' FROM people WHERE idpeople = ".$idpeople);
	//$shop->inline("SELECT glat, glong FROM shop WHERE idshop = ".$idshop);
	
	$res = mysql_fetch_array($people->result);
	$this->plat = $res[$glat1];
	$this->plong = $res[$glong1];

	$res = mysql_fetch_array($shop->result);
	$this->slat = $res['glat'];
	$this->slong = $res['glong'];	
  }

  function GCDistance($lat1, $lon1, $lat2, $lon2) {
    $lat1 *= $this->DE2RA;
    $lon1 *= $this->DE2RA;
    $lat2 *= $this->DE2RA;
    $lon2 *= $this->DE2RA;
    $d = sin($lat1)*sin($lat2) + cos($lat1)*cos($lat2)*cos($lon1 - $lon2);
    return ($this->AVG_ERAD * acos($d));
  }


  function GCAzimuth($lat1, $lon1, $lat2, $lon2) {
    $result = 0.0;

    $ilat1 = intval(0.50 + $lat1 * 360000.0);
    $ilat2 = intval(0.50 + $lat2 * 360000.0);
    $ilon1 = intval(0.50 + $lon1 * 360000.0);
    $ilon2 = intval(0.50 + $lon2 * 360000.0);

    $lat1 *= $this->DE2RA;
    $lon1 *= $this->DE2RA;
    $lat2 *= $this->DE2RA;
    $lon2 *= $this->DE2RA;

    if (($ilat1 == $ilat2) && ($ilon1 == $ilon2)) {
      return result;
    }
    else if ($ilat1 == $ilat2) {
      if ($ilon1 > $ilon2)
        $result = 90.0;
      else
        $result = 270.0;
    }
    else if ($ilon1 == $ilon2) {
      if ($ilat1 > $ilat2)
        $result = 180.0;
    }
    else {
      $c = acos(sin($lat2)*sin($lat1) + cos($lat2)*cos($lat1)*cos(($lon2-$lon1)));
      $A = asin(cos($lat2)*sin(($lon2-$lon1))/sin($c));
      $result = ($A * $this->RA2DE);


      if (($ilat2 > $ilat1) && ($ilon2 > $ilon1)) {
        $result = $result;
      }
      else if (($ilat2 < $ilat1) && ($ilon2 < $ilon1)) {
        $result = 180.0 - $result;
      }
      else if (($ilat2 < $ilat1) && ($ilon2 > $ilon1)) {
        $result = 180.0 - $result;
      }
      else if (($ilat2 > $ilat1) && ($ilon2 < $ilon1)) {
        $result += 360.0;
      }
    }

    return $result;
  }

  function ApproxDistance($lat1, $lon1, $lat2, $lon2) {
    $lat1 = $this->DE2RA * $lat1;
    $lon1 = -$this->DE2RA * $lon1;
    $lat2 = $this->DE2RA * $lat2;
    $lon2 = -$this->DE2RA * $lon2;

    $F = ($lat1 + $lat2) / 2.0;
    $G = ($lat1 - $lat2) / 2.0;
    $L = ($lon1 - $lon2) / 2.0;

    $sing = sin($G);
    $cosl = cos($L);
    $cosf = cos($F);
    $sinl = sin($L);
    $sinf = sin($F);
    $cosg = cos($G);

    $S = $sing*$sing*$cosl*$cosl + $cosf*$cosf*$sinl*$sinl;
    $C = $cosg*$cosg*$cosl*$cosl + $sinf*$sinf*$sinl*$sinl;
    $W = atan2(sqrt($S),sqrt($C));
    $R = sqrt(($S*$C))/$W;
    $H1 = (3 * $R - 1.0) / (2.0 * $C);
    $H2 = (3 * $R + 1.0) / (2.0 * $S);
    $D = 2 * $W * $this->ERAD;
    $return = ($D * (1 + $this->FLATTENING * $H1 * $sinf*$sinf*$cosg*$cosg - $this->FLATTENING*$H2*$cosf*$cosf*$sing*$sing));
    return $return;
  }

  function EllipsoidDistance($lat1, $lon1, $lat2, $lon2) {
	$distance = 0.0;
	$faz = 0.0;
	$baz = 0.0;
	$r = 1.0 - $this->FLATTENING;
	$tu1 = 0.0;
	$tu2 = 0.0;
	$cu1 = 0.0;
	$su1 = 0.0;
	$cu2 = 0.0;
	$x = 0.0;
	$sx = 0.0;
	$cx = 0.0;
	$sy = 0.0;
	$cy = 0.0;
	$y = 0.0;
	$sa = 0.0;
	$c2a = 0.0;
	$cz = 0.0;
	$e = 0.0;
	$c = 0.0;
	$d = 0.0;

	$cosy1 = 0.0;
	$cosy2 = 0.0;

	if(($lon1 == $lon2) && ($lat1 == $lat2))
	  return $distance;
	$lon1 *= $this->DE2RA;
	$lon2 *= $this->DE2RA;
	$lat1 *= $this->DE2RA;
	$lat2 *= $this->DE2RA;

	$cosy1 = cos($lat1);
	$cosy2 = cos($lat2);

	if($cosy1 == 0.0) $cosy1 = 0.0000000001;
	if($cosy2 == 0.0) $cosy2 = 0.0000000001;

	$tu1 = $r * sin($lat1) / $cosy1;
	$tu2 = $r * sin($lat2) / $cosy2;
	$cu1 = 1.0 / sqrt($tu1 * $tu1 + 1.0);
	$su1 = $cu1 * $tu1;
	$cu2 = 1.0 / sqrt($tu2 * $tu2 + 1.0);
	$x = $lon2 - $lon1;

	$distance = $cu1 * $cu2;
	$baz = $distance * $tu2;
	$faz = $baz * $tu1;

   while(abs($d - $x) > $this->EPS) {
		$sx = sin($x);
		$cx = cos($x);
		$tu1 = $cu2 * $sx;
		$tu2 = $baz - $su1 * $cu2 * $cx;
		$sy = sqrt($tu1 * $tu1 + $tu2 * $tu2);
		$cy = $distance * $cx + $faz;
		$y = atan2($sy, $cy);
		$sa = $distance * $sx / $sy;
		$c2a = - $sa * $sa + 1.0;
		$cz = $faz + $faz;
		if($c2a > 0.0) $cz = - $cz / $c2a + $cy;
		$e = $cz * $cz * 2.0 - 1.0;
		$c = ((-3.0 * $c2a + 4.0) * $this->FLATTENING + 4.0) * $c2a * $this->FLATTENING / 16.0;
		$d = $x;
		$x = (($e * $cy * $c + $cz) * $sy * $c + $y) * $sa;
		$x = (1.0 - $c) * $x * $this->FLATTENING + $lon2 - $lon1;
	}

	$x = sqrt((1.0 / $r / $r - 1.0) * $c2a + 1.0) + 1.0;
	$x = ($x - 2.0) / $x;
	$c = 1.0 - $x;
	$c = ($x * $x / 4.0 + 1.0) / $c;
	$d = (0.375 * $x * $x - 1.0) * $x;
	$x = $e * $cy;
	$distance = 1.0 - $e - $e;
	$distance = (((($sy * $sy * 4.0 - 3.0) * $distance * $cz * $d / 6.0 - $x) * $d / 4.0 + $cz) * $sy * $d + $y) * $c * $this->ERAD * $r;

    return $distance;
  }

  function getKmPerLonAtLat($dLatitude) {
    // Thanks to Eric Iverson for this correction!  Must convert degrees to radians...
    $dLatitude *= $this->DE2RA;
    return 111.321 * cos($dLatitude);
  }

  function getLonPerKmAtLat($dLatitude) {
    return 1 / $this->getKmPerLonAtLat($dLatitude);
  }

  function getKmPerLat() {
    return 111.000;
  }

  function getLatPerKm() {
    return 1 / $this->getKmPerLat();
  }

}

function ConvKilometersToMiles($dValue) {
	return $dValue / 1.609344;
}

/**
 * Calcul la distance parcourue pour la mission (de chez le people au shop et retour)
 *
 * @return array =
 * 	['peoplehome'] numéro de l'adresse de départ (1 ou 2)
 *  ['AS'] distance Aller simple en km
 *  ['AR'] distance Aller - Retour en km
 * @author Nico
 **/
function CalcDeplacement($secteur, $idmission)
{
	/*
		TODO : CalcDeplacement VIP et MERCH
	*/
	global $DB;
	## Vars
	$coeff = array(0 => 1, 5 => 1.40, 10 => 1.35, 20 => 1.42, 30 => 1.34, 40 => 1.30, 50 => 1.42, 75 => 1.29, 100 => 1.28); 

	## Get infos
	switch ($secteur) {
		case 'VI':
			break;
		
		case 'AN':
			$infs = $DB->getRow("SELECT 
					a.idpeople, a.idshop, a.peoplehome, a.kmpaye, a.kmfacture, a.kmauto,
					p.peoplehome as ppeoplehome
				FROM animation a
					LEFT JOIN shop s ON a.idshop = s.idshop
					LEFT JOIN people p ON a.idpeople = p.idpeople
				WHERE idanimation = ".$idmission."
			");
			
			$kmpay = 'kmpaye';
			$kmfac = 'kmfacture';
			$kmauto = 'kmauto';
			$table = 'animation';
			$idfield = 'idanimation';
			
			break;

		case 'ME':
		case 'EA':

			break;
	}
	
	## People Home
	$ret['peoplehome'] = (empty($infs['peoplehome']))?$infs['ppeoplehome']:$infs['peoplehome'];
	
	## Calcul Distance
	if (($infs['idpeople'] > 0) and ($infs['idshop'] > 0)) {
		$geoloc = new GeoCalc();			
		$geoloc->calculate($infs['idpeople'], $infs['idshop'], $ret['peoplehome']);

		if($geoloc->plat != '0' && $geoloc->plong != '0' && $geoloc->slat != '0' && $geoloc->slong != '0') {
			$dist = $geoloc->EllipsoidDistance($geoloc->plat, $geoloc->plong, $geoloc->slat, $geoloc->slong);
			$dist = round($dist);
			
			##### Chercher le bon coefficient dans l'array #####
			foreach($coeff as $k => $v) {
				if($dist >= $k) {
					$x = $v;
				}
			}
			
			$ret['AS'] = round($dist * $x); 	# Aller
			$ret['AR'] = 2 * $ret['AS']; 		# Aller-Retour
		}
	} else {
		$ret['AS'] = '--';
		$ret['AR'] = '--';
	}
	
	## Stock infos si différentes de celles stockées
	if (($ret['AR'] > 0) and (($infs[$kmpay] != $ret['AR']) or ($infs[$kmfac] != $ret['AR'])) and ($infs[$kmauto] == 'Y')) {
		$DB->inline("UPDATE ".$table." SET ".$kmpay." = ".$ret['AR'].", ".$kmfac." = ".$ret['AR']." WHERE ".$idfield." = ".$idmission);
	}
	
	## return
	return $ret;
}

?>