<?php
# Classes spécifiques utilisées
include NIVO."classes/makehtml.php" ;
?>

<script src="<?php echo STATIK ?>js/scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="<?php echo STATIK ?>js/scriptaculous/src/effects.js" type="text/javascript"></script>
<script src="<?php echo STATIK ?>js/formvalidation/validation.js" type="text/javascript"></script>
<link href="<?php echo STATIK ?>css/formvalidation.css" rel="stylesheet" type="text/css">

<style type="text/css">
<!--

/*FORMULAIRE*/
div.formdiv {
	width:80%;
	margin:auto;
	padding:5px;
	background-color:#C8C9C8;

}
.formulaire {
	width:100%;
	background-color: #cddddc;
}
.formulaire td {
	padding:2px;
	padding-left:20px;
}
.ligne2 td {
	background-color:#D6EEF4;
}
.lignename {
	border-right-width: 5px;
	border-right-style: solid;
	border-right-color: #C8C9C8;
	font-size: 1.1em;
	font-weight: bold;
	width:12%;

}
-->
</style>

<?php
$Titre = $titre_00;

###GESTION DE l'AFFICHAGE DES PAGES
if (!empty($_GET['step'])) { $step = $_GET['step']; } else {$step = 0;}
switch($step) {
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
  default:
		$steptitle = $detail_01;
}

$textehaut = $detail_00.' : '.$steptitle;
?>