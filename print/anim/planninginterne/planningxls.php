<?php
## Classes
	require_once NIVO."classes/xls/class.writeexcel_workbook.inc.php";
	require_once NIVO."classes/xls/class.writeexcel_worksheet.inc.php";

#######################################################################
# Get Datas

	$DB->inline("SET NAMES latin1");
	$datas = $DB->getArray("SELECT
	an.idanimation, an.idanimjob, an.datem, an.hin1, an.hout1, an.hin2, an.hout2, an.produit,
	p.gsm, p.pnom, p.pprenom,
	s.societe as ssociete, s.ville as sville, s.tel,
	c.societe as clsociete

	FROM animation an
	LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
	LEFT JOIN people p ON an.idpeople = p.idpeople
	LEFT JOIN shop s ON an.idshop = s.idshop
	LEFT JOIN client c ON j.idclient = c.idclient
	LEFT JOIN agent a ON j.idagent = a.idagent

	WHERE an.idanimation IN (".implode(", ", $_POST['print']).")
	ORDER BY ".$_SESSION['animmissionsort']);

#######################################################################
# fichier

	$pathxls = 'document/temp/anim/planningint/';
	$fname = 'planningint-'.remaccents($_SESSION['prenom']).'-'.date("Ymd").'.xls';
    $workbook = &new writeexcel_workbook(Conf::read('Env.root').$pathxls.$fname);
	
#######################################################################
# Sheets

	$plan =& $workbook->addworksheet('Planning Animation');

#######################################################################
# Styles

	# Titre
	$titre =& $workbook->addformat();
	$titre->set_bold();
	$titre->set_size(18);
	$titre->set_align('center');	
	$titre->set_align('vcenter');
	$titre->set_merge();
	
	# entete
	$entete =& $workbook->addformat();
	$entete->set_align('center');
	$entete->set_align('vcenter');
	$entete->set_size(10);
	$entete->set_bold();
	$entete->set_color('white');
	$entete->set_fg_color('black');
	$entete->set_text_wrap();	
	
	# datas
	$data =& $workbook->addformat();
	$data->set_align('center');
	$data->set_align('vcenter');
	$data->set_size(10);
	$data->set_border(1);
	$data->set_text_wrap();
	
	# datas Heures
	$dataH =& $workbook->addformat();
	$dataH->set_align('center');
	$dataH->set_align('vcenter');
	$dataH->set_size(10);
	$dataH->set_border(1);
	$dataH->set_text_wrap();
	$dataH->set_num_format('hh:mm');
	
	# datas Dates
	$dataD =& $workbook->addformat();
	$dataD->set_align('center');
	$dataD->set_align('vcenter');
	$dataD->set_size(10);
	$dataD->set_border(1);
	$dataD->set_text_wrap();
	$dataD->set_num_format('d/mm/yy');

	$tour = 1;
	$startrow = 5;
	
	function htoxls ($heuresql) {
		if (($heuresql != '00:00:00') and !empty($heuresql)) {
			$h = explode(":", $heuresql);
			$secs = $h[2] + ($h[1] * 60) + ($h[0] * 3600);
			$daysecs = 24 * 3600;
			$heure = $secs / $daysecs;
		} else {
			$heure = '';
		}
		
		return $heure;
	}
	
	function dtoxls ($datesql) {
		$sp = explode("-", $datesql);
		$start = 36526; # days @ 01/01/2000
		$styear = 2000;
		
		for ($i=$styear; $i < $sp[0]; $i++) { 
			$start += date("z", strtotime($i."-12-31")) + 1;
		}

		$jour = $start + date("z", strtotime($datesql));
		
		return $jour;
	}
	
foreach ($datas as $row)
{
	## Colonnes
	$colarray = array(                                                                  
		array('A', 12.5	, 'Date'		, dtoxls($row['datem'])                  , 'dataD'),
		array('B',  7.5 , 'from'		, htoxls($row['hin1'])                   , 'dataH'),
		array('C',  7.5 , 'to'			, htoxls($row['hout1'])                  , 'dataH'),
		array('D',  7.5 , 'from'	  	, htoxls($row['hin2'])                   , 'dataH'),
		array('E',  7.5 , 'to'			, htoxls($row['hout2'])                  , 'dataH'),
		array('F', 37.5	, 'Place'		, $row['ssociete']." - ".$row['sville']  , 'data'),
		array('G', 15	, 'Tel'			, $row['tel']						     , 'data'),
		array('H', 35	, 'Name'		, $row['pprenom']." ".$row['pnom']       , 'data'),
		array('I', 15	, 'GSM'			, $row['gsm']                            , 'data'),
		array('J', 35	, 'Customer' 	, $row['clsociete']                      , 'data'),
		array('K', 22.5	, 'Promotion'	, $row['produit']                        , 'data'),
		array('L',  7.5 , 'Mis.'	  	, $row['idanimation']                    , 'data')
	);             

	#######################################################################
	# Menu

	if ($tour == 1)
	{
		# Logo
		$plan->insert_bitmap('A1', NIVO.'print/illus/logotypo.bmp', 1, 1);

		# Tailles
		foreach ($colarray as $v) $plan->set_column($v[0].':'.$v[0], $v[1]);

		## Entetes
		foreach ($colarray as $v) $plan->write($v[0].$startrow, $v[2], $entete);
	}
	
	foreach ($colarray as $v)
	{
		$plan->write($v[0].($startrow + $tour), $v[3], $$v[4]);
	}

	$tour++;
}

$workbook->close();

?>
<br>
   <fieldset>
       <legend>Planning</legend>
	<img src="<?php echo STATIK ?>illus/excel.png" alt="print" width="16" height="16" border="0">
	<A href="/<?php echo $pathxls.$fname ;?>" target="_blank">Planning</A>
   </fieldset>
