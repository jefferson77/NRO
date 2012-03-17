<?php
define("NIVO", "../");

require_once(NIVO."nro/fm.php");
include NIVO."classes/vip.php";

########################################################################
#### Search ########################################################################
$sql = "

SELECT v.idvip, v.vipactivite,
j.forfait,
c.tvforfait 
FROM vipmission v 
LEFT JOIN vipjob j ON v.idvipjob = j.idvipjob
LEFT JOIN client c ON j.idclient = c.idclient

WHERE 
j.idclient = 2104 AND
(v.vipactivite LIKE '%chef%' OR v.vipactivite LIKE '%assist%') AND
YEAR(v.vipdate) = 2007
";

$search = new db();

$search->inline($sql);

########################################################################
#### Calcul ########################################################################

while ($row = mysql_fetch_array($search->result)) {
    
    $vd = new corevip($row['idvip']);
    
    ## Chef
    if (stristr($row['vipactivite'], 'chef')) {
        $chef['nbr'] += 1;
        
        if ($row['forfait'] == 'Y') {
            $chef['forfaits'] += 1; 
            $chef['MontPrest'] += $row['tvforfait'];
        } else {
            $chef['hprest'] += $vd->hprest; 
            $chef['MontPrest'] += $vd->MontPrest;
        }
        
        $chef['MontDepl'] += $vd->MontDepl;
        $chef['MontLoc'] += $vd->MontLoc;
        $chef['MontFrais'] += $vd->MontFrais;
    } elseif (stristr($row['vipactivite'], 'assist')) {
        $assist['nbr'] += 1;
        
        if ($row['forfait'] == 'Y') {
            $assist['forfaits'] += 1; 
            $assist['MontPrest'] += $row['tvforfait'];
        } else {
            $assist['hprest'] += $vd->hprest; 
            $assist['MontPrest'] += $vd->MontPrest;
        }
        
        $assist['MontDepl'] += $vd->MontDepl;
        $assist['MontLoc'] += $vd->MontLoc;
        $assist['MontFrais'] += $vd->MontFrais;
    }
    
}

########################################################################
#### Affichage ########################################################################
var_dump($chef);
var_dump($assist);
?>