<?php
$pagebody = 'onLoad="iniFilePage()"';
//******************************************************************************************************
//   ATTENTION: THIS FILE HEADER MUST REMAIN INTACT. DO NOT DELETE OR MODIFY THIS FILE HEADER.
//
//   Name: uu_file_upload.php
//   Revision: 2.3
//   Date: 25/05/2007 11:06PM
//   Link: http://uber-uploader.sourceforge.net 
//   Initial Developer: Peter Schmandra  http://www.webdice.org
//   Description: Upload files and show progress in popup.
//   Note: The popup is called on the submit and it submits the upload. This method defeats pop up blockers
//
//   Licence:
//   The contents of this file are subject to the Mozilla Public
//   License Version 1.1 (the "License"); you may not use this file
//   except in compliance with the License. You may obtain a copy of
//   the License at http://www.mozilla.org/MPL/
// 
//   Software distributed under the License is distributed on an "AS
//   IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or
//   implied. See the License for the specific language governing
//   rights and limitations under the License.
//
//***************************************************************************************************************
include NIVO."classes/UberUploader/uu_conlib.php";

$THIS_VERSION = "2.3";

if(isset($_GET['cmd']) && $_GET['cmd'] == 'about'){ kak("<u><b>UBER UPLOADER FILE UPLOAD</b></u><br>UBER UPLOADER VERSION =  <b>" . $UBER_VERSION . "</b><br>UU_FILE_UPLOAD = <b>" . $THIS_VERSION . "<b><br>\n"); }

$tmp_sid = md5(uniqid(mt_rand(), true));

///////////////////////////////////////////////////////////////////////
// This is where you might set your config file eg.                  //
// if($_SESSION['user'] == "tom"){ $config_file = 'uu_tom_config'; } //
///////////////////////////////////////////////////////////////////////
$config_file = $default_config_file;

$path_to_upload_script .= '?tmp_sid=' . $tmp_sid;
$path_to_ini_status_script .= '?tmp_sid=' . $tmp_sid; 

if($MULTI_CONFIGS_ENABLED){ 
	$path_to_upload_script .= "&config_file=$config_file";
	$path_to_ini_status_script .= "&config_file=$config_file"; 
}                                                                 

?>
  <style>
    .info {font:18px Arial;}
    .data {background-color:#b3b3b3; border:1px solid #898989; width:40%;}
    .data tr td {background-color:#dddddd; font:13px Arial; width:35%;}
    .bar1 {background-color:#b3b3b3; position:relative; text-align:left; height:20px; width:<?php print $progress_bar_width; ?>px; border:1px solid #505050;}
    .bar2 {background-color:#000099; position:relative; text-align:left; height:20px; width:0%;}
  </style>
  <script language="javascript" type="text/javascript">
    var path_to_ini_status_script = "<?php print $path_to_ini_status_script; ?>";
    var check_file_name_format = <?php print $check_file_name_format; ?>;
    var check_disallow_extensions = <?php print $check_disallow_extensions; ?>;
    <?php if($check_disallow_extensions){ print "var disallow_extensions = $disallow_extensions;\n"; } ?>
    var check_allow_extensions = <?php print $check_allow_extensions; ?>;
    <?php if($check_allow_extensions){ print "var allow_extensions = $allow_extensions;\n"; } ?>
    var check_null_file_count = <?php print $check_null_file_count; ?>;
    var check_duplicate_file_count = <?php print $check_duplicate_file_count; ?>;
    var max_upload_slots = <?php print $max_upload_slots; ?>;
    var cedric_progress_bar = <?php print $cedric_progress_bar; ?>;
    var progress_bar_width = <?php print $progress_bar_width; ?>;         
  </script>
  <script language="javascript" type="text/javascript" src="<?php echo NIVO ?>classes/UberUploader/uu_file_upload.js"></script>