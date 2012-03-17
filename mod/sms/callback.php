<?php
#### Note les infos reçues dans le fichier received.txt #####################
define("NIVO", "../../");

require_once(NIVO."nro/fm.php");

$DB->inline("UPDATE sms SET modifydate = NOW(), status = '".$_POST['status']."' WHERE msgid = '".$_POST['apiMsgId']."'");
?>
	
	