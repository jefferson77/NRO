<?php
$row = $DB->getRow("SELECT webdoc_id, path, idcontact, typecontact FROM webdocs WHERE hash = '".md5($filepath)."'");

#### phrasebook ####################
switch ($infoContact['langue']) {
	case 'NL':
		include 'nl.php';
	break;

	case 'FR':
	default:
		include 'fr.php';
	break;
}


#### phrasebook ####################

$body  = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>'.utf8_decode($phrase['exc_new_doc']).'</title>
		<style>
		<!--
			body {margin: 0; text-align: center; line-height: 1; color: #000;background-color: #596672; font-size:12px; font-family:\'Lucida Grande\', Helvetica, Geneva, Arial, sans-serif;}
			table.latable {background-color: #FFFFFF; background: url('.Conf::read('WebSiteURL').'illus/mail_fondcentre.png) repeat;}
			p.piedpage {color: #dadddf;font-size:10px;font-family: \'Lucida Grande\', Verdana, sans-serif;text-align: center; margin: 0;}
			hr {border: 0;background-color: #CCC;margin: 10px 0px;height: 1px;}
			-->
		</style>
	</head>
	<body style="margin: 0; text-align: center; line-height: 1; color: #000;background-color: #596672; font-size:12px; font-family:\'Lucida Grande\', Helvetica, Geneva, Arial, sans-serif;">
		<br><br>
		<table border="0" cellspacing="0" cellpadding="0" align="center" width="704" bgcolor="#FFFFFF" class="latable" style="background-color: #FFFFFF; background: url('.Conf::read('WebSiteURL').'illus/mail_fondcentre.png) repeat;">
			<tr>
				<td colspan="2"><img src="'.Conf::read('WebSiteURL').'illus/mail_banner.jpg" width="704" height="105" alt="Mail Banner"></td>
			</tr>
			<tr>
				<td width="168" height="168" align="center" valign="top">
					<img src="'.Conf::read('WebSiteURL').'illus/printer_big.png" width="128" height="128" alt="Printer Big">
				</td>
				<td align="center">
					<h1 style="display: block; margin:20px 0 10px 0; color: #020f1c; font-family: \'HelveticaNeue-Light\', \'Helvetica Neue Light\', Helvetica, Arial, sans-serif; font-weight: 500; font-size: 28px; ">
						'.utf8_decode($phrase['imprimer']).'
					</h1>
					<p style="margin: 10px 0 30px;">
						<div style="color: #732016; text-align: left; font-size: 16px; font-family: \'Times New Roman\',Times,FreeSerif,serif;">'.nl2br(utf8_decode($_POST['generalText'])).'</div><br>
						'.utf8_decode($phrase['doc_vous_attend']).'
						<a href="'.Conf::read('DataSiteURL').'document/index.php?l='.$infoContact['langue'].'&doc='.md5($filepath).'" title="Exception2" style="color: #007dd0; text-decoration: none;">www.exception2.be</a><br>
					</p>
					<div style="text-align: center; color:#AAAAAA">
						<a href="'.Conf::read('DataSiteURL').'document/index.php?l='.$infoContact['langue'].'&doc='.md5($filepath).'" title="Exception2" style="color: #007dd0; text-decoration: none; font-size: 25px;">'.utf8_decode($phrase['votre_document']).'</a><br>
						<hr width="90%" style="border: 0;background-color: #CCC;margin: 10px 0px;height: 1px;">
						'.utf8_decode($phrase['besoin_acrobat']).'<br><br>
						<a href="http://www.adobe.com/go/EN_UK-H-GET-READER"><img src="'.Conf::read('WebSiteURL').'illus/get_adobe_reader.png" width="158" height="39" alt="Get Adobe Reader"></a>
						<hr width="90%" style="border: 0;background-color: #CCC;margin: 10px 0px;height: 1px;">
						<span>
							'.utf8_decode($phrase['contactez']).'<a href="mailto:nico@exception2.be">nico@exception2.be</a>
						</span>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<img src="'.Conf::read('WebSiteURL').'illus/mail_bottom.png" width="704" height="13" alt="Mail Bottom">
				</td>
			</tr>
		</table>
		<p class="piedpage" style="color: #dadddf; font-size:10px; font-family: \'Lucida Grande\', Verdana, sans-serif; text-align: center; margin: 0;">
			'.$phrase['adresse_exc'].'
		</p>
		<br><br>
	</body>
</html>';


?>