<div id="centerzonelarge" align="center">
<?php
include_once NIVO.'print/anim/contrat/contrat_inc.php';
include_once NIVO.'print/merch/contrat/contrat_inc.php';
include_once NIVO.'print/vip/contrat/contrat_inc.php';

$dest = $_POST['destination'];

#########################################################################################################
### 1ère passe : Génère les contrats individuels et des notes de frais

	# = Anim -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- =
	$anims = $DB->getArray("SELECT an.idanimation, an.datem, an.idshop as shop, aj.idcofficer as cofficer, an.idpeople as people
							FROM animation an
							LEFT JOIN animjob aj ON an.idanimjob = aj.idanimjob
							WHERE an.idpeople = ".$_POST['idpeople']." AND an.datem BETWEEN '".$dates['datein']."' AND '".$dates['dateout']."'");
		
	foreach ($anims as $row)
	{
		$contr = print_contratanim($row['idanimation']);
		if (!empty($contr)) 
		{
			if(!empty($row[$dest]))
			{
				$global[$row[$dest]][] = $contr;
			}
		}
	}

	# = Vip -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- =
	$vips = $DB->getArray("SELECT vm.idvip, vm.vipdate, vm.idshop as shop, vj.idcofficer as cofficer, vm.idpeople as people
							FROM vipmission vm
							LEFT JOIN vipjob vj ON vj.idvipjob = vm.idvipjob
							WHERE idpeople = ".$_POST['idpeople']." AND vipdate BETWEEN '".$dates['datein']."' AND '".$dates['dateout']."'");

	foreach ($vips as $row)
	{
		$contr = print_contratvip($row['idvip']);
		if (!empty($contr)) 
		{
			if(!empty($row[$dest]))
			{
				$global[$row[$dest]][] = $contr;
			}
		}
	}

	# = Merch -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=- =
	$merchs = $DB->getArray("SELECT genre, idmerch, datem, idshop as shop, idcofficer as cofficer, idpeople as people
								FROM merch 
								WHERE idpeople = ".$_POST['idpeople']." AND datem BETWEEN '".$dates['datein']."' AND '".$dates['dateout']."'");

	foreach ($merchs as $row)
	{
		$contr = print_contratmerch($row['genre'], $row['datem'], $_POST['idpeople']);
		if (!empty($contr)) 
		{
			if(!empty($row[$dest]))
			{
				$global[$row[$dest]][] = $contr;
			}
		}
	}
	
	ksort($global);

#########################################################################################################
### 2ème passe : Assemblage des contrats et notes de frais
	//$book = reliure($global, 'PeCo');
	
	
	generateSendTable($global, $dest, 'temp/PeCo', 'PeCo', "Contrats pour ce people");
	
	unset($global);
?>
</div>