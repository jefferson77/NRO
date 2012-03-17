#!/usr/bin/php
<?php

define("NIVO", "/NRO/core/");
include_once(NIVO."nro/fm.php");

include NIVO."classes/anim.php" ;
include NIVO."classes/merch.php";
include NIVO."classes/vip.php"  ;

$period = date("n-Y", strtotime("-1 day"));

$merch = $DB->getArray("SELECT m.idmerch, p.idsupplier, 'merch' as secteur FROM people p
LEFT JOIN merch m ON p.idpeople = m.idpeople
LEFT JOIN supplier s ON p.idsupplier = s.idsupplier
WHERE p.idsupplier > 0 AND CONCAT(month(m.datem),'-',year(m.datem)) LIKE '".$period."'
ORDER BY p.idsupplier;");

$anim = $DB->getArray("SELECT a.idanimation, p.idsupplier, 'anim' as secteur FROM people p
LEFT JOIN animation a ON p.idpeople = a.idpeople
LEFT JOIN supplier s ON p.idsupplier = s.idsupplier
WHERE p.idsupplier > 0 AND
CONCAT(month(a.datem),'-',year(a.datem)) LIKE '".$period."'
ORDER BY p.idsupplier");

$vip = $DB->getArray("SELECT v.idvip, p.idsupplier, 'vip' as secteur FROM people p
LEFT JOIN vipmission v ON p.idpeople = v.idpeople
LEFT JOIN supplier s ON p.idsupplier = s.idsupplier
WHERE p.idsupplier > 0
AND CONCAT( MONTH( v.vipdate ) , '-', YEAR( v.vipdate ) ) LIKE '".$period."'
ORDER BY p.idsupplier");

$res = array_merge($merch, $anim, $vip);
$heures[] = array();

$i=0;
foreach($res as $row)
{
	switch($row['secteur'])
	{
		case "merch" :
			$coremerch = new coremerch($row['idmerch']);
			$heures[$i] = array("idmission" => $row['idmerch'], "idsupplier" => $row['idsupplier'], "secteur"=>$row['secteur'], "heures"=>$coremerch->hprest, "km"=>$coremerch->kipay);
		break;
		
		case "anim" : 
			$coreanim = new coreanim($row['idanimation']);
			$heures[$i] = array("idmission" => $row['idanimation'], "idsupplier" => $row['idsupplier'], "secteur"=>$row['secteur'], "heures"=>$coreanim->hprest, "km"=>$coreanim->kipay);
		break;
		
		case "vip" :
			$corevip = new corevip($row['idvip']);
			$heures[$i] = array("idmission" => $row['idvip'], "idsupplier" => $row['idsupplier'], "secteur"=>$row['secteur'], "heures"=>$corevip->hprest, "km"=>$corevip->kipay);
		break;
	}
	$i++;
}
/* Ici j'ai le tableau heures sous forme 

heures[i]['idmission'] = idmerch / idanimation / idvip
heures[i]['idsupplier'] = idsupplier
heures[i]['secteur'] = merch / anim / vip
heures[i]['heures'] = heures prestées pour cette mission
heures[i]['km'] = km parcourus pour cette mission

trié par secteur / supplier
Tri par supplier de toutes les données : 
*/

foreach ($heures as $key => $row) 
{
    $idsupplier[$key]  = $row['idsupplier'];
}

array_multisort($idsupplier, SORT_NUMERIC, $heures);

//Création d'un bon de commande par fournisseur ($tot)

$j = 0;
$tot[] = array();
$idsupp = $heures[0]['idsupplier'];
$i=0;
$h=0;
$km=0;
foreach($heures as $r)
{
	if($r['idsupplier'] == $idsupp) // même supplier qu'avant
	{
		$h += $r['heures'];
		$km +=$r['km'];
	}
	else // autre supplier, remise à zéro et stockage dans la base du précédent
	{
		$tot[$j] = array("idsupplier" => $idsupp, "totheures" => $h, "totkm" => $km);
		$j++;
		$h=0;
		$km=0;
		$h += $r['heures'];
		$km +=$r['km'];
	}
	$idsupp = $r['idsupplier'];
	$i++;
}
$tot[$j] = array("idsupplier" => $idsupp, "totheures" => $h, "totkm" => $km); //stockage du dernier
foreach($tot as $bdc)
{
	$soc = $DB->getOne("SELECT societe from supplier where idsupplier=".$bdc['idsupplier']);
	$DB->inline("INSERT INTO bondecommande (idsupplier,secteur,date,titre,description,type) 
		VALUES (".$bdc['idsupplier'].",'' , date(NOW()), 'Bon de commande ".$soc." ".$period."', 'Heures prestées : ".$bdc['totheures']."
Nombre de kilomètres : ".$bdc['totkm']."' , 'ext')"); //pas toucher à l'identation !
}
$test = $DB->getColumn("SELECT idsupplier FROM bonlivraison WHERE etat = 'in' and CONCAT(MONTH(date),'-',YEAR(date)) LIKE '".$period."';");
$test = array_unique($test);

$prix = array();
foreach($test as $livsupplier)
{
	$prix = $DB->getOne("SELECT SUM(prix) FROM bonlivraison WHERE etat = 'in' and CONCAT(MONTH(date),'-',YEAR(date)) LIKE '".$period."' AND idsupplier=".$livsupplier);
	$fact = $DB->getOne("SELECT SUM(factureclient) FROM bonlivraison WHERE etat = 'in' and CONCAT(MONTH(date),'-',YEAR(date)) LIKE '".$period."' AND idsupplier=".$livsupplier);
	
	$soc = $DB->getOne("SELECT societe from supplier where idsupplier=".$livsupplier);
	$nbr = $DB->getOne("SELECT COUNT(*) FROM bonlivraison WHERE idsupplier = ".$livsupplier);
	$DB->inline("INSERT INTO bondecommande (idsupplier,secteur,date, montant,factureclient, titre,description,type) 
		VALUES ($livsupplier, '' , date(NOW()), '$prix','$fact', 'Bon de commande $soc $period', 'Nombre de livraisons : $nbr' , 'ext')");
		$id = $DB->addid;
	$DB->inline("UPDATE bonlivraison SET nbdc=$id WHERE etat = 'in' and CONCAT(MONTH(date),'-',YEAR(date)) LIKE '$period' AND idsupplier = $livsupplier;");
}
?>
