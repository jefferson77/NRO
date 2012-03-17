<?php

define('NIVO', '../../');
include NIVO."includes/ifentete.php" ;
include (NIVO.'nro/xlib/phpmailer/class.phpmailer.php');
include (NIVO.'print/dispatch/dispatcher.class.php');

?><link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8"><?php

$no_contract = array("Envoyer","file_path","type","generalText");

$sendDocs = new dispatcher($_POST['file_path'],$no_contract,$_POST['type']);

$peopleAndDocAndMethod = array();

foreach($_POST as $key => $value)
{
	if(!in_array($key,$no_contract))
	{
		$peopleAndDocAndMethod[$key] = $value;
	}
}

echo $sendDocs->send($peopleAndDocAndMethod);


include NIVO."includes/ifpied.php" ;

?>