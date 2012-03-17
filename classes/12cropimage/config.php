<?php
require_once(NIVO."nro/fm.php");
require_once(NIVO.'classes/photo.php');

$picUpRawDir = GetPhotoPath($idp,'raw','path',$sfx, '1');
$picUpRawDirFile = GetPhotoPath($idp,'raw','path',$sfx);

$picUpTempFilename = basename($picUpRawDirFile);
$picUpTempDir = NIVO."classes/12cropimage/temp/";
$picUpTempFile = $picUpTempDir.$picUpTempFilename;


$picUpCropDir = GetPhotoPath($idp,'','path',$sfx, '1');
$picUpCropDirFile = GetPhotoPath($idp,'','path',$sfx);
    
$crStandardVars['crResultDir'] = $picUpCropDir;				//Absolute path to the directory for the result. WITH trailing slash

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

	//Environment variables
	$crCropperDir = $_SERVER['DOCUMENT_ROOT'] . "/classes/12cropimage/";	//Absolute path to the cropper directory. WITH trailing slash
	$crCropperUrl = "/classes/12cropimage";							//URL to the cropper directory. No trailing slash.
	$crBgColor = "#ccc";												// Color of the background of the interface
	$crImgTheme = "white";											// Cropper image theme. Choose either black or white or create your own
	$crColorTheme = "#fff";											// Color theme. Choose either #000 or #fff to compliment the image theme.
	$crResultVar = "cropperResult";								// Name of the resultvariable  returned at the end of the crop.
	$crCropQuality = 95; 											// quality of the cropped image 1-100...
	$crKeepTempFiles = 1;											// The number of hours to keep a temporary file before deleting it. Set to 0 to keep the files forever.
	$crAllowRotation = 1;											// Set this value to 1 to allow rotation. Rotation asks more from your Server.


	// The standard variables for the cropper
	$crStandardVars['crWidth'] = 200;			//Standard Crop Width
	$crStandardVars['crHeight'] = 160;			//Standard Crop Height
	$crStandardVars['crDivWidth'] = 414;		//Interface width
	$crStandardVars['crDivHeight'] = 24;		//Interface height (excluding the uploaded image)
	$crStandardVars['crVarCropSize'] = 0;		//Set to 1 to get extra field for setting crop size;
	$crStandardVars['crShowPreview'] = 1;		//Set to 0 to not show a preview before the final crop is made.


	//Alerts to show by Javascript when something goes wrong
	$crAlertVars['noSupport'] = "Your browser doesn't support the cropper script! You need to use another Browser..."; // Text which appears in browsers that do not support W3C DOM
	$crAlertVars['onlyUseDigits'] = "You can only use digits to set the crop size";
	$crAlertVars['wrongCropSize'] = "The crop size that you chose is larger than the original.\\nThis is not possible, the maximum crop size for the picture\\nthat you uploaded is: ";
	$crAlertVars['cropEndTxt'] = "<strong>Your image is cropped and can be used...</strong>";


	//Errors to display by cropper.php
	$crErrorVars['wrongFileType'] = "You have uploaded the wrong file type. Only JPEG (.jpg) or PNG images are allowed.";
	$crErrorVars['orgTooSmall'] = "Your original image was too small. The minimum size is: ";
	$crErrorVars['uploadError'] = "Something went wrong while uploading your file. ";
	$crErrorVars['uploadErrors'][1] = "The upload was too big. The maximum is ".get_cfg_var('upload_max_filesize').".";
	$crErrorVars['uploadErrors'][3] = "The upload was interupted.";
	$crErrorVars['uploadErrors'][4] = "No file was uploaded.";
	$crErrorVars['uploadErrors'][6] = "No temporary directory was set.";


	//Texts used in the interface. Change to set the language
	$crTxt['uploadButton'] = "Upload Image";
	$crTxt['continue'] = "Continue";
	$crTxt['cropWidth'] = "Crop width";
	$crTxt['cropHeight'] = "crop height";
	$crTxt['cropSelection'] = "Crop the current selection";
	$crTxt['switchSelectColor'] = "Switch the color of the selector";
	$crTxt['rotateRight'] = "Rotate the image clockwise";
	$crTxt['rotateLeft'] = "Rotate the image anti-clockwise";
	$crTxt['Zoom in'] = "Enlarge the cropping area";
	$crTxt['Zoom out'] = "Reduce the cropping area";
	$crTxt['approveCrop'] = "Approve this crop and continue";
	$crTxt['cancelCrop'] = "Cancel this crop and try again";

?>