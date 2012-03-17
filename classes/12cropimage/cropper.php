<?php
define('NIVO', '../../');

if (isset($_POST['idp'])) {	$idp = $_POST['idp'];} elseif(isset($_GET['idp'])) { $idp = $_GET['idp'];}
if (isset($_POST['sfx'])) {	$sfx = $_POST['sfx'];} elseif(isset($_GET['sfx'])) { $sfx = $_GET['sfx'];}

// include the config file
include_once("config.php");

//DUPLIQUE LE RAW DANS LE TEMP SI ON VEUT CROPPER SANS AVOIR UPLOADE D'IMAGE (var définie dans le javascript.php)
$fakeupload = '';
if(!empty($_GET['fakeupload'])) {
	copy($picUpRawDirFile,$picUpTempFile);
	unset($fakeupload);
}






	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////                                                        ////////////////////
	////////////////////                      12CropImage                       ////////////////////
	////////////////////                                                        ////////////////////
	////////////////////                      v. 2.12 beta                      ////////////////////
	////////////////////                 (c) 2005 Roel Meurders                 ////////////////////
	////////////////////        mail: support [at] roelmeurders [dot] com       ////////////////////
	////////////////////                                                        ////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
	//  (copyright) info: http://roel.meurders.nl/web_php/12cropimage-2-php-image-crop-plugin/    //
	////////////////////////////////////////////////////////////////////////////////////////////////

	$onloadFunction = "";

	// put the
	if ($_POST){
		$vars = $_POST;
	} else {
		$vars = $_GET;
	}
	$do = $vars['do'];

	// Handles the file upload!
	if (isset($_FILES['crUpload'])){
		// first check outdated files.
		if ($crKeepTempFiles > 0){
			if ($handle = opendir($crCropperDir."temp")) {
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						$theFile = $crCropperDir."temp/".$file;
						$minTime = time() - ($crKeepTempFiles * 60 * 60);
						if (filemtime($theFile) < $minTime){
							@unlink($theFile);
						}
					}
				}
				closedir($handle);
			}
		}
		// now process the uploaded file
		// si le fichier a été uploadé
		if (is_uploaded_file($_FILES['crUpload']['tmp_name'])){
			$tmp = $_FILES['crUpload']['tmp_name'];
			$tmpData = getimagesize($tmp);
			
			//si l'extension du fichier est bonne
			//if ($tmpData[2] == 2 || $tmpData == 3){
			if ($tmpData[2] < 4){
				//si l'image est trop petite
				if ($tmpData[0] < $crWidth || $tmpData[1] < $crHeight) {
					$do = "error";
					$error = $crErrorVars['orgTooSmall'].$crWidth." x ".$crHeight;
				//si le fichier est aux dimensions correctes de crop	
				} else if ($tmpData[0] == $crWidth && $tmpData[1] == $crHeight && $crVarCropSize == 0) {
					$vars['crOrgFile'] = $vars['crResultDir'] . time() . "-" . $_FILES['crUpload']['name'];
					if (move_uploaded_file($tmp, $vars['crOrgFile'])){
						@chmod($vars['crOrgFile'], 0644);
						$onloadFunction = " onload=\"parent.crFinishCrop(".$vars['crId'].",".$vars['crDivHeight'].",'".$vars['crOrgFile']."');\"";
					} else {
						$do = "error";
						$error = $crErrorVars['uploadError'].$crErrorVars['uploadErrors'][$FILES['crUpload']['error']];
					}
				} else {
					$vars['crOrgFile'] = $picUpTempFile;
					if (move_uploaded_file($tmp, $vars['crOrgFile'])){
						@chmod($vars['crOrgFile'], 0644);
						$do = "uploaded";
						$vars['crOrgWidth'] = $tmpData[0];
						$vars['crOrgHeight'] = $tmpData[1];
					} else {
						$do = "error";
						$error = $crErrorVars['uploadError'].$crErrorVars['uploadErrors'][$FILES['crUpload']['error']];
					}
				}
			} else {
				$do = "error";
				$error = $crErrorVars['wrongFileType'];
				@unlink($tmp);
			}
		} else {
			$do = "error";
			$error = $crErrorVars['uploadError'].$crErrorVars['uploadErrors'][$_FILES['crUpload']['error']];
		}
	}

	// handle the file rotation
	if (isset($vars['crRotate']) && $do == "crop" && $crAllowRotation == 1){
		$orgFile = $vars['crOrgFile'];
		$crRotate = $vars['crRotate'];
		unset($vars['crRotate']);
		include("image.php");
		$tmpData = getimagesize($orgFile);
		$vars['crOrgWidth'] = $tmpData[0];
		$vars['crOrgHeight'] = $tmpData[1];
		$vars['crAntiCache'] = $vars['crAntiCache'] + 1;
	}	else {
		$vars['crAntiCache'] = 1;
	}

	// Do stuff before the page is printed!
	if ($do == "uploaded"){
		
		$keys = array_keys($vars);
		$goto = "cropper.php?do=crop&idp=$idp&sfx=$sfx";
		for ($i = 0; $i < count($keys); $i++){
			if ($keys[$i] != "do"){
		   	$goto .= "&".$keys[$i]."=".$vars[$keys[$i]];
			}
		}
		$onloadFunction = " onload=\"document.location.href='".$goto."';\"";

	} else if ($do == "crop"){
		copy($vars['crOrgFile'],$picUpRawDirFile);
		if ($vars['crOrgWidth'] > ($vars['crDivWidth'] - 6)) {
			$vars['crProp'] = ($vars['crDivWidth'] - 6) / $vars['crOrgWidth'];
			$dw = $vars['crDivWidth'] - 6;
			$dh = floor($vars['crProp'] * $vars['crOrgHeight']);
		} else {
			$vars['crProp'] = 1;
			$dw = $vars['crOrgWidth'];
			$dh = $vars['crOrgHeight'];
		}
		if ($vars['crShowPreview'] == 0)	{
			$crCropOption = "finish";
		} else {
			$crCropOption = "preview";
		}
		$onloadFunction = " onload=\"parent.crSetDivHeight(".$vars['crId'].",".($dh + $vars['crDivHeight'] + 6)."); crInitCropIf();\"";

	} else if ($do == "preview"){
		$onloadFunction = " onload=\"parent.crSetDivHeight(".$vars['crId'].",".($vars['crHeight'] + $vars['crDivHeight'] + 6).");\"";

	} else if ($do == "finish"){
		$orgFile = $vars['crOrgFile'];
		include("image.php");
		if ($vars['crResultDir'] != ""){
			$crNewFile = $vars['crResultDir'].basename($orgFile);
			if(copy($crOrgFile,$crNewFile)){
				@unlink($crOrgFile);
				$crOrgFile = $crNewFile;
			}
		}
		@chmod($crOrgFile, 0644);
		$onloadFunction = " onload=\"parent.crFinishCrop(".$vars['crId'].",".$vars['crDivHeight'].",'".$crOrgFile."');\"";
	}

?>
<html>

<head>
	<title>Cropper Interface</title>
	<script type="text/javascript" src="<?php echo $crCropperUrl?>/javascript.php?idp=<?php echo $idp?>&sfx=<?php echo $sfx?>"></script>
	<style>
		* {
			margin: 0;
			padding: 0;
		}
		#crControl img {
			border: none;
		}
<?php	if ($do == "crop" && $vars['crVarCropSize'] == 1){ ?>
		#crSizeDiv {
			float: left;
			width: 60px;
			height: 15px;
			padding: 1px;
			margin-right: 4px;
			background: transparent url(<?php echo $crCropperUrl?>/images/<?php echo $crImgTheme?>/cropsize.gif) no-repeat left top;
		}
		#crSizeDiv input {
			display: block;
			width: 24px;
			height: 13px;
			text-align: center;
			border: none;
			font-type: Arial, Verdana, sans-serif;
			font-size: 9px;
			color: <?php echo $crColorTheme?>;
			background-color: transparent;
		}
		#crSizeLeft {
			float: left;
		}
		#crSizeRight {
			float: right;
		}
<?php	}	?>
	</style>
</head>

<body<?php echo $onloadFunction?>>
	<?php //print_r($vars); ?>
	<form id="crForm" method="post" enctype="multipart/form-data" action="cropper.php?idp=<?php echo $idp ?>&sfx=<?php echo $sfx ?>">
		<div id="crInterface" style="position: relative;">
<?php
	if ($do == "error"){
		echo $error;

	} else if ($do == "upload") {
		echo "			<div id=\"crUploadIndicator\" style=\"position: absolute; width: ".($vars['crDivWidth'] - 4)."px; height: ".($vars['crDivHeight'] - 4)."; background: ".$crBgColor." url(images/".$crImgTheme."/cropbg.gif) repeat left top; top: 0px; left: -".($vars['crDivWidth'] + 10)."px; border: solid 2px ".$crBgColor.";\">\n";
		echo "				<img src=\"images/".$crImgTheme."/indicator.gif\" alt=\"uploading indicator\" width=\"100%\" height=\"100%\" />\n";
		echo "			</div>\n";
		echo "			<div id=\"crUploadDiv\">\n";
		echo "				<input type=\"file\" name=\"crUpload\" id=\"crUpload\" class=\"crClassUpload\" />\n";
		echo "				<input type=\"button\" value=\"".$crTxt['uploadButton']."\" class=\"crClassButton\" onclick=\"crSubmitUpload();\" />\n";
		echo "			</div>\n";
		$vars['do'] = "uploadcheck";

	} else if ($do == "crop"){
		echo "			<div id=\"crImgDiv\" style=\"border: solid 2px ".$crBgColor."; background: ".$crBgColor." url(images/".$crImgTheme."/cropbg.gif) repeat left top;\">\n";
		echo "				<img src=\"image.php?do=crop&orgFile=".$vars['crOrgFile']."&idp=".$idp."&divWidth=".$dw."&divHeight=".$dh."&crAntiCache=".$vars['crAntiCache']."\" id=\"crImg\" style=\"border: solid 1px ".$crColorTheme.";\" /><br />\n";
		echo "			</div>\n";
		echo "			<div id=\"crSelect\" style=\"position:absolute; z-index:1000; left:0px; top:0px; border: solid 1px ".$crColorTheme."; cursor: pointer\" onclick=\"crDrag();\"><img src=\"images/spacer.gif\" width=\"100%\" height=\"100%\" /></div>\n";
		echo "			<div id=\"crControl\" style=\"height: ".$crStandardVars['crDivHeight']."px; background: ".$crBgColor."; border-top: solid 1px ".$crColorTheme."; padding: 3px;\">\n";
		echo "				<div style=\"float: left;\">\n";
		if ($vars['crVarCropSize'] == 1){
			echo "				<div id=\"crSizeDiv\">\n";
			echo "					<input type=\"text\" id=\"crSizeLeft\" value=\"".$vars['crWidth']."\" onkeyup=\"crSetCropSize(this);\" />\n";
			echo "					<input type=\"text\" id=\"crSizeRight\" value=\"".$vars['$crHeight']."\" onkeyup=\"crSetCropSize(this);\" />\n";
			echo "				</div>\n";
		}
		echo "					<a title=\"".$crTxt['switchSelectColor']."\" style=\"cursor: pointer;\" onclick=\"if (this.firstChild.src.indexOf('switch1.gif')>0) { this.firstChild.src = 'images/".$crImgTheme."/switch2.gif'; } else { this.firstChild.src = 'images/".$crImgTheme."/switch1.gif'; } crSwitchSelectColor();\"><img alt=\"".$crTxt['switchSelectColor']."\" src=\"images/".$crImgTheme."/switch1.gif\" /></a>\n";
		if ($crAllowRotation == 1){
			echo "					<a title=\"".$crTxt['rotateRight']."\" style=\"cursor: pointer;\" onmouseover=\"this.firstChild.src='images/".$crImgTheme."/rotater2.gif';\" onmouseout=\"this.firstChild.src='images/".$crImgTheme."/rotater1.gif';\" onclick=\"crDoCrop('right');\"><img alt=\"".$crTxt['rotateRight']."\" src=\"images/".$crImgTheme."/rotater1.gif\" /></a>\n";
			echo "					<a title=\"".$crTxt['rotateLeft']."\" style=\"cursor: pointer;\" onmouseover=\"this.firstChild.src='images/".$crImgTheme."/rotatel2.gif';\" onmouseout=\"this.firstChild.src='images/".$crImgTheme."/rotatel1.gif';\" onclick=\"crDoCrop('left');\"><img alt=\"".$crTxt['rotateLeft']."\" src=\"images/".$crImgTheme."/rotatel1.gif\" /></a>\n";
		}
		echo "					<a title=\"".$crTxt['Zoom in']."\" style=\"cursor: pointer;\" onmousedown=\"this.firstChild.src='images/".$crImgTheme."/zoomin2.gif'; crStartZoom(1);\" onmouseup=\"this.firstChild.src='images/".$crImgTheme."/zoomin1.gif'; crStopZoom();\" onmouseout=\"this.firstChild.src='images/".$crImgTheme."/zoomin1.gif'; crStopZoom();\"><img alt=\"".$crTxt['Zoom in']."\" src=\"images/".$crImgTheme."/zoomin1.gif\" /></a>\n";
		echo "					<a title=\"".$crTxt['Zoom out']."\" style=\"cursor: pointer;\" onmousedown=\"this.firstChild.src='images/".$crImgTheme."/zoomuit2.gif'; crStartZoom(0);\" onmouseup=\"this.firstChild.src='images/".$crImgTheme."/zoomuit1.gif'; crStopZoom();\" onmouseout=\"this.firstChild.src='images/".$crImgTheme."/zoomuit1.gif'; crStopZoom();\"><img alt=\"".$crTxt['Zoom out']."\" src=\"images/".$crImgTheme."/zoomuit1.gif\" /></a>\n";
		echo "				</div>\n";
		echo "				<div style=\"float: right;\">\n";
		echo "					<a title=\"".$crTxt['cropSelection']."\" style=\"cursor: pointer;\" onmouseover=\"this.firstChild.src='images/".$crImgTheme."/forward2.gif'\" onmouseout=\"this.firstChild.src='images/".$crImgTheme."/forward1.gif'\" onclick=\"crDoCrop('".$crCropOption."');\"><img alt=\"".$crTxt['cropSelection']."\" src=\"images/".$crImgTheme."/forward1.gif\" /></a>\n";
		echo "				</div>\n";
		echo "			</div>\n";

	} else if ($do == "preview"){
		echo "			<div id=\"crImgDiv\" style=\"border: solid 2px ".$crBgColor."; background: ".$crBgColor." url(images/".$crImgTheme."/cropbg.gif) repeat left top;\">\n";
		echo "				<img id=\"crImg\" src=\"image.php?do=preview&orgFile=".$vars['crOrgFile']."&idp=".$idp."&crWidth=".$vars['crWidth']."&crHeight=".$vars['crHeight']."&crSelLeft=".$vars['crSelLeft']."&crSelTop=".$vars['crSelTop']."&crSelWidth=".$vars['crSelWidth']."&crSelHeight=".$vars['crSelHeight']."&crProp=".$vars['crProp']."\" style=\"border: solid 1px ".$crColorTheme.";\" /><br />\n";
		echo "			</div>\n";
		echo "			<div id=\"crControl\" style=\"height: ".$crStandardVars['crDivHeight']."px; background: ".$crBgColor."; border-top: solid 1px ".$crColorTheme."; padding: 3px;\">\n";
		echo "				<div style=\"float: left;\">\n";
		echo "					<a title=\"".$crTxt['cancelCrop']."\" style=\"cursor: pointer;\" onclick=\"crDoCrop('crop');\" onmouseover=\"this.firstChild.src='images/".$crImgTheme."/back2.gif';\" onmouseout=\"this.firstChild.src='images/".$crImgTheme."/back1.gif';\"><img alt=\"".$crTxt['cancelCrop']."\" src=\"images/".$crImgTheme."/back1.gif\" /></a>\n";
		echo "				</div>\n";
		echo "				<div style=\"float: right;\">\n";
		echo "					<a title=\"".$crTxt['approveCrop']."\" style=\"cursor: pointer;\" onclick=\"crDoCrop('finish');\" onmouseover=\"this.firstChild.src='images/".$crImgTheme."/forward2.gif';\" onmouseout=\"this.firstChild.src='images/".$crImgTheme."/forward1.gif';\"><img alt=\"".$crTxt['approveCrop']."\" src=\"images/".$crImgTheme."/forward1.gif\" /></a>\n";
		echo "				</div>\n";
		echo "			</div>\n";

	}

	if ($do != "finish"){
		$keys = array_keys($vars);
		for ($i = 0; $i < count($keys); $i++){
			echo "			<input type=\"hidden\" name=\"".$keys[$i]."\" id=\"".$keys[$i]."\" value=\"".$vars[$keys[$i]]."\" />\n";
		}
	}
?>
		</div>
	</form>
</body>

</html>