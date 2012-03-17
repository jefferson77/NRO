<?php
define('NIVO', '../');

include NIVO."nro/xlib/jpgraph/jpgraph.php";
include NIVO."nro/xlib/jpgraph/jpgraph_pie.php";

$free1 = round(disk_free_space("/")/1073741824,2);
$tot1 = round(disk_total_space("/")/1073741824, 2);

$free2 = round(disk_free_space("/backup/")/1073741824,2);
$tot2 = round(disk_total_space("/backup/")/1073741824, 2);

$free3 = round(disk_free_space("/shared/")/1073741824,2);
$tot3 = round(disk_total_space("/shared/")/1073741824, 2);

$free4 = round(disk_free_space("/var/opt/axigen/")/1073741824,2);
$tot4 = round(disk_total_space("/var/opt/axigen/")/1073741824, 2);

$data1 = array($free1,$tot1 - $free1); # Données
$data2 = array($free2,$tot2 - $free2); # Données
$data3 = array($free3,$tot3 - $free3); # Données
$data4 = array($free4,$tot4 - $free4); # Données

$legends = array("Free", "Used"); # Données

$graph = new PieGraph(1000,250,"auto"); #Taille
$graph->img->SetAntiAliasing();


$size = 0.3;

$graph->SetMarginColor('#CCCCCC');

$p1 = new PiePlot($data1);
$p1->SetSize($size);
$p1->SetCenter(0.20);
$p1->SetStartAngle(90);
$p1->SetLabelType(PIE_VALUE_ABS);
$p1->value->SetFormat('%01.2f Gb');
$p1->value->SetFont(FF_FONT0);
$p1->title->Set("NRO $tot1 Gb");

$p2 = new PiePlot($data2);
$p2->SetSize($size);
$p2->SetCenter(0.40);
$p2->SetStartAngle(90);
$p2->SetLabelType(PIE_VALUE_ABS);
$p2->value->SetFormat('%01.2f Gb');
$p2->value->SetFont(FF_FONT0);
$p2->title->Set("Backup $tot2 Gb");

$p3 = new PiePlot($data3);
$p3->SetSize($size);
$p3->SetCenter(0.60);
$p3->SetStartAngle(90);
$p3->SetLabelType(PIE_VALUE_ABS);
$p3->value->SetFormat('%01.2f Gb');
$p3->value->SetFont(FF_FONT0);
$p3->title->Set("Shared $tot3 Gb");

$p4 = new PiePlot($data4);
$p4->SetSize($size);
$p4->SetCenter(0.80);
$p4->SetStartAngle(90);
$p4->SetLabelType(PIE_VALUE_ABS);
$p4->value->SetFormat('%01.2f Gb');
$p4->value->SetFont(FF_FONT0);
$p4->title->Set("Mail $tot3 Gb");

$graph->Add($p1);
$graph->Add($p2);
$graph->Add($p3);
$graph->Add($p4);

$graph->Stroke();
?>
