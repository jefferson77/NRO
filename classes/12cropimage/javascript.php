<?php
# Entete de page
define('NIVO', '../../');

$idp = !empty($_REQUEST['idp']) ? $_REQUEST['idp'] : '';
$sfx = !empty($_REQUEST['sfx']) ? $_REQUEST['sfx'] : '';

include_once("config.php");

header("Content-type: text/javascript;");

?>
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


// Initiate the global variables...
var crStandardVars = new Array();
var crAlertVars = new Array();
var crLoop = 0;
var crScaleFactor = 0.01;
var crMouseX = 0;
var crMouseY = 0;
var crImgWidth = 0;
var crImgHeight = 0;
var crBorderColor = "";
var crSelWidth = 0;
var crSelHeight = 0;
var crSelMinWidth = 0;
var crSelLeft = 0;
var crSelTop = 0;


// Set standard variables here...
<?php
	$keys = array_keys($crStandardVars);
	for ($i = 0; $i < count($keys); $i++){
		echo "crStandardVars.".$keys[$i]." = ";
		if (is_int($crStandardVars[$keys[$i]])){
			echo $crStandardVars[$keys[$i]].";\n";
		} else {
			echo "\"".$crStandardVars[$keys[$i]]."\";\n";
		}
	}
?>


// Set texts of Javascript Alerts here...
<?php
	$keys = array_keys($crAlertVars);
	for ($i = 0; $i < count($keys); $i++){
		echo "crAlertVars.".$keys[$i]." = \"".$crAlertVars[$keys[$i]]."\";\n";
	}
?>

//
function crGetVars(div){
	var vars = new Array();
	var inputs = div.getElementsByTagName('input');
	for (var v in crStandardVars){
		vars[v] = crStandardVars[v];
	}
	for(var i=0; i<inputs.length; i++){
		if (inputs[i].type == "hidden"){
			if (inputs[i].id){
				vars[inputs[i].id] = inputs[i].value;
			} else {
				vars[inputs[i].name] = inputs[i].value;
			}
		}
	}
	return vars;
}


//
function crInitCropper(){
	if (document.getElementById && document.createElement){
		var divs = document.getElementsByTagName('div');
		var j = 0;
		for(var i=0; i<divs.length; i++){
			if (divs[i].className == "cropper"){
				if (divs[i].id){
					divs[i].title = divs[i].id;
				}
				divs[i].id = "crDiv"+j;
				crCurrentDiv = divs[i].id;
				var vars = new Array();
				vars = crGetVars(divs[i]);
				if (document.all){
					vars.crDivWidth = (vars.crDivWidth * 1) + 5;
				}
				divs[i].style.width = vars.crDivWidth + "px";
				divs[i].style.height = vars.crDivHeight + "px";
				var crFrame = '<iframe name="crFrame'+ j +'" id="crFrame'+ j +'" src="<?php echo $crCropperUrl?>/cropper.php?do=upload&idp=<?php echo $idp?>&sfx=<?php echo $sfx?>&crId=' + j;
				for (var v in vars) {
					crFrame += '&' + v + '=' + vars[v];
				}
				crFrame += '" width="' + vars.crDivWidth + '" height="' + vars.crDivHeight + '" scrolling="no" frameborder="0"></iframe>';

				divs[i].innerHTML = crFrame;
				j++;
			}
		}
	} else {
		alert(crAlertVars.noSupport);
	}
}

function crInitCropper2(rawWidth, rawHeight){

// ON SAUTE L'UPLOAD DONC ON LE FEINTE EN CREANT UN FICHIER TEMP A PARTIR DU RAW

	if (document.getElementById && document.createElement){
		var divs = document.getElementsByTagName('div');
		var j = 0;
		for(var i=0; i<divs.length; i++){
			if (divs[i].className == "cropper"){
				if (divs[i].id){
					divs[i].title = divs[i].id;
				}
				divs[i].id = "crDiv"+j;
				crCurrentDiv = divs[i].id;
				var vars = new Array();
				vars = crGetVars(divs[i]);
				if (document.all){
					vars.crDivWidth = (vars.crDivWidth * 1) + 5;
				}
				divs[i].style.width = vars.crDivWidth + "px";
				divs[i].style.height = vars.crDivHeight + "px";

				var crFrame = '<iframe name="crFrame'+ j +'" id="crFrame'+ j +'" src="<?php echo $crCropperUrl?>/cropper.php?do=crop&fakeupload=1&idp=<?php echo $idp?>&sfx=<?php echo $sfx?>&crId=0&crResultDir=<?php echo $picUpCropDir ?>&crWidth=<?php echo $crStandardVars['crWidth'] ?>&crHeight=<?php echo $crStandardVars['crHeight'] ?>&crDivWidth=414&crDivHeight=24&crVarCropSize=<?php echo $crStandardVars['crVarCropSize'] ?>&crShowPreview=<?php echo $crStandardVars['crShowPreview'] ?>&crAntiCache=1&crOrgFile=<?php echo $picUpTempFile ?>&crOrgWidth=' + rawWidth + '&crOrgHeight=' + rawHeight + '"';
				crFrame += '" width="' + vars.crDivWidth + '" height="' + vars.crDivHeight + '" scrolling="no" frameborder="0"></iframe>';

				divs[i].innerHTML = crFrame;
				j++;
			}
		}
	} else {
		alert(crAlertVars.noSupport);
	}

}


//
function crSubmitUpload(){
	document.getElementById('crUploadIndicator').style.left = "0px";
	document.getElementById('crForm').submit();
	document.getElementById('crUploadDiv').innerHTML = "";
}


//
function crSetDivHeight(crId,divHeight){
	var crDiv = document.getElementById("crDiv"+crId);
	var crFrame = document.getElementById("crFrame"+crId);
	crDiv.style.height = divHeight + "px";
	crFrame.style.height = divHeight + "px";
}


//
function crInitCropIf(){
	var crSelect = document.getElementById('crSelect');
	var vars = new Array();
	vars = crGetVars(document.getElementById('crInterface'));
	crSelWidth = Math.floor(vars.crProp * vars.crWidth) - 2;
	crSelHeight = Math.floor(vars.crProp * vars.crHeight) - 2;
	crSelMinWidth = crSelWidth;
	crSelect.style.width = crSelWidth + "px";
	crSelect.style.height = crSelHeight + "px";
	crImgWidth = Math.floor(vars.crProp * vars.crOrgWidth);
	crImgHeight = Math.floor(vars.crProp * vars.crOrgHeight);
	crSelLeft = Math.ceil((crImgWidth - (crSelWidth + 1)) / 2) + 3;
	crSelTop = Math.ceil((crImgHeight - (crSelHeight + 1)) / 2) + 3;
	crSelect.style.left = crSelLeft + "px";
	crSelect.style.top = crSelTop + "px";
	crBorderColor = crSelect.style.borderColor;
}


//
function crDrag(){
	var div = document.getElementById('crSelect');
	if (crLoop == 0){
		div.style.cursor = "move";
		div.onmousemove = crDoDrag;
	} else {
		div.style.cursor = "pointer";
		crLoop = 0;
		div.onmousemove = null;
	}
}


//
function crDoDrag(e){
	if (!e) e = window.event
	if (crLoop == 0){
		crLoop = 1;
		crMouseX = e.screenX;
		crMouseY = e.screenY;
	} else {
		var div = document.getElementById('crSelect');
		var divx = e.screenX - crMouseX;
		var divy = e.screenY - crMouseY;
		if ((crSelLeft + divx + crSelWidth - 1) < crImgWidth && (crSelLeft + divx) >= 3){
			crMouseX = crMouseX + divx;
			crSelLeft = crSelLeft + divx;
			div.style.left = crSelLeft + "px";
		}
		if ((crSelTop + divy + crSelHeight - 1) < crImgHeight && (crSelTop + divy) >= 3){
			crMouseY = crMouseY + divy;
			crSelTop = crSelTop + divy;
			div.style.top = crSelTop + "px";
		}
	}
}


//
function crStartZoom(dir){
	crLoop = 1;
	crDoZoom(dir);
}


//
function crStopZoom(){
	crLoop = 0;
}


//
function crDoZoom(dir){
	var div = document.getElementById('crSelect');
	crTimer = null;
	if (crLoop == 1){
		if (dir == 1){
			crScaleFactor = (1 - crScaleFactor) * crScaleFactor;
			crSelWidth = (1 + crScaleFactor) * crSelWidth;
			crSelHeight = (1 + crScaleFactor) * crSelHeight;
		} else {
			crScaleFactor = (1 + crScaleFactor) * crScaleFactor;
			crSelWidth = (1 - crScaleFactor) * crSelWidth;
			crSelHeight = (1 - crScaleFactor) * crSelHeight;
		}
		if ((Math.round(crSelWidth) + crSelLeft - 1) <= crImgWidth && (Math.round(crSelHeight) + crSelTop - 1) <= crImgHeight && Math.round(crSelWidth) > crSelMinWidth){
			div.style.width = Math.round(crSelWidth) + "px";
			div.style.height = Math.round(crSelHeight) + "px";
			crTimer = setTimeout("crDoZoom("+dir+")", 10);
		} else {
			crStopZoom();
		}
	}
}


//
function crSwitchSelectColor(){
	div = document.getElementById('crSelect');
	div.style.borderColor = "#fff";
	if (div.style.borderColor == crBorderColor){
		div.style.borderColor = "#000";
	} else {
		div.style.borderColor = "#fff";
	}
	crBorderColor = div.style.borderColor;
}


//
function crSetCropSize(theObj){
	var vars = new Array();
	vars = crGetVars(document.getElementById('crInterface'));
	var div = document.getElementById('crSelect');
	var theVal = (theObj.value) * 1;
	if (theVal > 9){
		if (theObj.id == "crSizeLeft"){
			if (theVal > vars.crOrgWidth){
				alert(crAlertVars.wrongCropSize + crImgWidth + " x " + crImgHeight);
				theObj.value = "";
				return;
			}
			crSelWidth = Math.ceil(vars.crProp * theVal);
			crSelHeight = Math.ceil(vars.crProp * vars.crHeight);
			crSelMinWidth = crSelWidth;
			document.getElementById('crWidth').value = theVal;
		} else {
			if (theVal > vars.crOrgHeight){
				alert(crAlertVars.wrongCropSize + crImgWidth + " x " + crImgHeight);
				theObj.value = "";
				return;
			}
			crSelWidth = Math.ceil(vars.crProp * vars.crWidth);
			crSelHeight = Math.ceil(vars.crProp * theVal);
			document.getElementById('crHeight').value = theVal;
		}
		div.style.width = crSelWidth +"px";
		div.style.height = crSelHeight +"px";
		crSelLeft = Math.ceil((crImgWidth - (crSelWidth + 1)) / 2) + 3;
		crSelTop = Math.ceil((crImgHeight - (crSelHeight + 1)) / 2) + 3;
		div.style.left = crSelLeft + "px";
		div.style.top = crSelTop + "px";
	}
}


//
function crDoCrop(opt){
	var vars = new Array();
	vars = crGetVars(document.getElementById('crInterface'));
	var url = "cropper.php?do=";
	if (opt == "left" || opt == "right"){
		url += "crop&crRotate=" + opt;
	} else if (vars['do'] == "crop"){
		url += opt;
		vars['crSelWidth'] = Math.ceil(crSelWidth + 2);
		vars['crSelHeight'] =  Math.ceil(crSelHeight + 2);
		vars['crSelLeft'] = crSelLeft - 3;
		vars['crSelTop'] = crSelTop - 3;
	} else {
		url += opt;
	}
	for (var i in vars){
		if (i != 'do')
			url += "&" + i + "=" + vars[i];
	}
	document.location.href = url;
}


//
function crFinishCrop(crId,divHeight,crResult){
	crSetDivHeight(crId,divHeight);
	crTimer = setTimeout("crExitCrop("+crId+",'"+crResult+"')", 500);
}


//
function crExitCrop(crId,crResult){
	var div = document.getElementById("crDiv"+crId);
	var result = crAlertVars.cropEndTxt;
	var resultName = '<?php echo $crResultVar?>[' + crId + ']';
	if (div.title){
		resultName = div.title;
		div.id = div.title;
		div.title = "";
	}
	result += '<input type="hidden" name="'+resultName+'" value="' + crResult +'" />';
	div.innerHTML = result;
}


function hideDiv(idpr,valeur)
{
	var pr = document.getElementById(idpr);

	if (valeur == 1) {
		pr.style.display = "none";
	} else {
		pr.style.display = "";
	}
}
