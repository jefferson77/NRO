<?php
$detailid = new db('webpeople', 'idwebpeople', 'webneuro');
$detailid->inline("SELECT * FROM `webpeople` WHERE `idpeople` = '$idpeople'");
$infos = mysql_fetch_array($detailid->result) ;
$webetat = $infos['webetat'];	
$webtype = $_SESSION['webtype'];



switch($step) {

//DEUXIEME ETAPE
	case 1:
		$steptitle = $detail_02;
	break;
	case 2:
		$steptitle= $detail_03;
    break;
	case 3:
		$steptitle = $detail_04;
    break;
	case 3:
		$steptitle = $detail_05;
    break;
	case 3:
		$steptitle = $detail_06;
    break;

//1ERE ETAPE
  default:
		$steptitle = $detail_01;
}
?>