<?php
## geoloc shop
	switch($geoloc['pays']) {
		case"Pays-Bas":
		case"Duitsland":
			$db = 'NL';
		break;
		case"Luxembourg":
			$db = 'LU';
		break;
		case"France":
			$db = 'FR';
		break;
		case"Belgique":
		default:	
			$db = 'BE';
	}

	$addr3 = '';
	$addr2 = str_replace(' ', '+', $geoloc['adresse']);
	$pc = str_replace(' ', '+', $geoloc['cp']);
	
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, 'http://www.multimap.com/map/places.cgi?client=public&lang=&advanced=&db='.$db.'&cname=Great+Britain&overviewmap=&addr2='.$addr2.'&addr3='.$addr3.'&pc='.$pc);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	curl_close($ch);
	
	##### Needle pour plusieurs adresses #####		
	$needle = "<ol id='localinfo' class='localinfo'>";
	$pos1 = strpos($data , $needle);

	##### Needle si pas de résultats (sans code postal) #####
	$needle2 = "</cite> found no matching places</h1>";
	$pos2 = strpos($data, $needle2);
	
	##### Needle si pas de résultats (avec code postal) #####
	$needle3 = "<title>Map of  ".$pc." ".$pc."  Belgium";
	$pos3 = strpos($data, $needle3);


###### Pas de résultats (avec code postal) #######
	if(is_numeric($pos3))
	{
?>
		<div id="geoloc">
			Pas de résultats!<br><br>
			<input type="button" value="Retour" onClick="history.back();">
		</div>
<?php
	}
	else
	{
###### Pas de résultats (sans code postal) #######
		if(is_numeric($pos2))
		{
?>
			<div id="geoloc">
				Pas de résultats!<br><br>
				<input type="button" value="Retour" onClick="history.back();">
			</div>
<?php
		}
		else
		{
###### Un resultat ######
			if($pos1 === FALSE)
			{
				$needle1 = "<dt>Lat:</dt>";
				$pos1 = strpos($data, $needle1);
				$needle2 = "<dt>Web Address:</dt>";
				$pos2 = strpos($data, $needle2);
				$pos3 = $pos2 - $pos1;
				$data = substr($data, $pos1, $pos3 - strlen($needle2));
				$pos1 = strpos($data, "(");
				$pos2 = strpos($data, ")");
				$pos3 = $pos2 - $pos1;
				$lat = substr($data, $pos1 + 1, $pos3 - 1);
				$lon = strrchr($data, "(");
				$lon = str_replace("(", "", $lon);
				$lon = str_replace(")", "", $lon);
				
				$injgeo = new db();
				$sql = "UPDATE shop SET glat = ".$lat.", glong = ".$lon." WHERE idshop = ".$idshop;
				$injgeo->INLINE($sql);
				$did = $_GET['idshop'];
				$idshop = $_GET['idshop'];
				if($_SERVER['PHP_SELF'] == '/vip/adminvip.php') {
					include "v-detailMission.php";
				}
				else
				{
					include "detail.php";
				}
			}
###### Plusieurs résultats ######
			else
			{
				$pos2 = strpos($data, "</ol>");
				$pos3 = $pos2 - $pos1;
				$data = substr($data, $pos1 + strlen($needle), $pos3 - strlen($needle));
				$data = explode('<li id="', $data);
				$data2 = $data;
				$rue = array();
				$lon = array();
				$lat = array();
?>
			<div id="geoloc">
				<table cellpadding="3" cellspacing="3" align="center" width="75%">
					<tr>
						<td colspan="4">
							<?php echo "<b>Recherche:</b> " .str_replace("+", " ", $addr2 . " " .$pc); ?>
						</td>
					</tr>
					<tr>
						<td><b>Adresse</b></td>
						<td><b>Code Postal</b></td>
						<td><b>Commune</b></td>
						<td>&nbsp;</td>
					</tr>
<?php
				for($i = 1; $i < count($data); $i++)
				{
					//LAT + LON 
					$data2[$i] = str_replace("&amp;", "&", $data2[$i]);
					
					$needle1 = "&lon=";
					$pos1 = strpos($data2[$i], $needle1);
		
					$needle2 = "&lat=";
					$pos2 = strpos($data2[$i], $needle2);
								
					$needle3 = "&pl";
					$pos3 = strpos($data2[$i], $needle3);
					
					$pos4 = ($pos2 + strlen($needle2)) - ($pos1 + strlen($needle1));
					$pos5 = ($pos3 + strlen($needle3)) - ($pos2 + strlen($needle2));
					
					$lon[$i] = substr($data2[$i], $pos1 + strlen($needle1), $pos4 - strlen($needle2));
					
					$lat[$i] = substr($data2[$i], $pos2 + strlen($needle2), $pos5 - strlen($needle3)); 
					
					// NOM + CP + VILLE
					$needle1 = ' title="';
					$pos1 = strpos($data[$i], $needle1);
					$data[$i] = substr($data[$i], $pos1 + strlen($needle1));
					$needle2 = '">';
					$pos2 = strpos($data[$i], $needle2);
					$pos1 = strpos($data[$i], $needle2);
					$rue[$i] = substr($data[$i], 0, $pos1);
					$adresse = explode(', ', $rue[$i]);
					if($_SERVER['PHP_SELF'] == '/vip/adminvip.php') {
						$link = $_SERVER['PHP_SELF'].'?act=injgeo&lon='.$lon[$i].'&lat='.$lat[$i].'&idshop='.$idshop.'&idvipjob='.$idvipjob.'&idvip='.$idvip;
					}
					else {
						$link = $_SERVER['PHP_SELF'].'?act=injgeo&lon='.$lon[$i].'&lat='.$lat[$i].'&idshop='.$idshop.'&idvipjob='.$idvipjob.'&idvip='.$idvip;
					}	
					if($adresse[0] != '') {		
?>			
					<tr>
						<td><?php echo $adresse[0]; ?></td>
						<td><?php echo $adresse[1]; ?></td>
						<td><?php echo $adresse[2]; ?></td>
						<td><a href="<?php echo $link; ?>" style="color:#FFFFFF">OK</a></td>
					</tr>
<?php 	
					}
				}
?>
				</table>
				
				<a href="adminshop.php?act=show&idshop=<?php echo $idshop?>">Annuler Geoloc</a>
			</div>
<?php
			}
		}
	}
?>