<?php
## geoloc people
?>
<style type="text/css" title="text/css">
<!--
a.bton
{
	background-color: #CCC;
	border-color: #033;
	border-width: 1px;
	color: #000;
	padding: 0px 4px 0px 4px;
	border-style: solid;
	text-transform: inherit;
	text-decoration: none;
}

a.bton:hover {
	background-color: #EEE;
}
-->
</style>

<?php
	switch($geoloc[$pays]) {
		case"LU":
			$db = 'LU';
		break;
		case"FR":
			$db = 'FR';
		break;
		case"BE":
		default:	
			$db = 'BE';
	}
	
	$addr3 = '';
	$addr2 = str_replace(' ', '+', $geoloc['adresse'])."+".str_replace(' ', '+',$geoloc['num']);
	$pc = str_replace(' ', '+', $geoloc['cp']);
	

	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, 'http://www.multimap.com/map/places.cgi?client=public&lang=&advanced=&db='.$db.'&cname=Great+Britain&overviewmap=&addr2='.$addr2.'&addr3='.$addr3.'&pc='.$pc);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = html_entity_decode(curl_exec($ch));
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
	echo "POS3 = : " .$needle3;


###### Pas de résultats (avec code postal) #######
	if(is_numeric($pos3)){
?>
		<div id="geoloc">
			Pas de résultats!<br><br>
			<input type="button" value="Retour" onClick="history.back();">
		</div>
<?php
	} else {
###### Pas de résultats (sans code postal) #######
		if(is_numeric($pos2)){
?>
			<div id="geoloc">
				Pas de résultats!<br><br>
				<input type="button" value="Retour" onClick="history.back();">
			</div>
<?php
		} else {
###### Un resultat ######
			if($pos1 === FALSE)	{
				$startlat = strpos($data, 'lat=');
				$endlat = strpos($data, '&', $startlat);
				$startlon = strpos($data, 'lon=');
				$endlon = strpos($data, '&', $startlon);
				$lat = substr($data, $startlat+4, ($endlat-$startlat)-4);
				$lon = substr($data, $startlon+4, ($endlon-$startlon)-4);
				
				$DB->inline('UPDATE people SET glat'.$geoloc['peoplehome'].'='.$lat.', glong'.$geoloc['peoplehome'].' = '.$lon.' WHERE idpeople = '.$idpeople.' LIMIT 1');

				if(!empty($_GET['idpeople'])) $idpeople = $_GET['idpeople'];
				if($_SERVER['PHP_SELF'] == '/vip/adminvip.php') include "v-detailMission.php";
				else include "detail.php";
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
						<td colspan="4" style="font-size: 25px;">
							<?php echo str_replace("+", " ", $addr2 . " " .$pc); ?>
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
					$startlon = strpos($data2[$i], "&lon=")+4;
					$startlat = strpos($data2[$i], "&lat=")+4;
					$startpl = strpos($data2[$i], "&pl")+3;
					$lon[$i] = substr($data2[$i], $startlon, $startlat-$startlon);					
					$lat[$i] = substr($data2[$i], $startlat, $startpl-$startlat); 
					
					// NOM + CP + VILLE
					$data[$i] = substr($data[$i], strpos($data[$i], ' title="')+8);
					$rue[$i] = substr($data[$i], 0, strpos($data[$i], '">'));
					$adresse = explode(', ', $rue[$i]);
					if($adresse[0] != '') {		
?>			
					<tr>
						<td><?php echo $adresse[0]; ?></td>
						<td><?php echo $adresse[1]; ?></td>
						<td><?php echo $adresse[2]; ?></td>
						<td><a href="<?php echo $_SERVER['PHP_SELF'].'?act=injgeo&lon='.$lon[$i].'&lat='.$lat[$i].'&idpeople='.$idpeople.'&idvipjob='.$_SESSION['idvipjob'].'&idvip='.$_SESSION['idvip']; ?>" class="bton">OK</a></td>
					</tr>
<?php 	
					}
				}
?>
				</table>
				<div align="center">
				<a href="<?php echo $_SERVER['PHP_SELF'].'?act=show&idpeople='.$idpeople ?>" class="bton">Annuler</a>
				</div>
				
			</div>
<?php
			}
		}
	}
?>
