<?php
# Classes utilisées
include NIVO."classes/photo.php" ;

$session = md5(uniqid(rand(), true));

$picUpWebDir = GetPhotoPath($idpeople,'','path',$sfx, '1');
$picUpWebDirFile = GetPhotoPath($idpeople,'raw','path');
$picUpWebDirFile2 = GetPhotoPath($idpeople,'raw','path','-b');

$picWebFileUrl = GetPhotoPath($idpeople,'','',$sfx);
?>
<link href="/classes/lightloader/upload.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="/classes/lightloader/json_c.js"></script>
<script type="text/javascript" src="/classes/lightloader/upload_form.js"></script>
<script type="text/javascript" src="/classes/lightloader/sr_c.js"></script>
<br>
<div align="left"><font color="#900" size="4">&nbsp;&nbsp;<?php echo $detail_4_10 ?></font></div><br><br>
<table border="0" cellpadding="0" cellspacing="0" width="90%">
	<tr>
		<td>
		<fieldset> 
		<legend> &nbsp; <?php echo $detail_4_12 ?> &nbsp; </legend>
		<h1><?php echo $detail_4_11; ?></h2>
		<table width="90%" align="center" cellpaddingright="30">
		<tr>
			<td>
				<table class="standard" align="center" border="0" cellpadding="0" cellspacing="0" width="300px">
				  <tr>
				    <td class="etiq2"><?php echo $detail_4_13 ?></td>
				  </tr>
				  <tr>
				    <td class="contenu2" align="center">
				        <?php
					if (file_exists($picUpWebDirFile)) {
						echo'<img src="'.NIVO.'data/people/photo.php?id='.$idpeople.'&noscissors=true">';
					} else  {
						echo'<div class="pola_erreur">'.$detail_4_15.'</div>';
					}			
					?>
				    </td>
				  </tr>
				</table>
			</td>
			<td>
			  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
			<td>
				<table class="standard" align="center" border="0" cellpadding="0" cellspacing="0" width="300px">
				  <tr>
				    <td class="etiq2"><?php echo $detail_4_14 ?></td>
				  </tr>
				  <tr>
				    <td class="contenu2" align="center">
				    <?php
				    if (file_exists($picUpWebDirFile2)) {
					echo'<img id="img2" src="'.NIVO.'data/people/photo.php?id='.$idpeople.'&sfx=-b&noscissors=true">';
				    } else  {
					echo'<div class="pola_erreur">'.$detail_4_16.'</div>';
				    }	
				    ?>
				    </td>
				  </tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table class="standard" align="center" border="0" cellpadding="0" cellspacing="0" width="300px">
					<tr>
						<td class="etiq2"><?php echo $detail_4_17 ?></td>
					</tr>
					<tr>
					<td class="contenu2">
						<div id="img0">
							<form METHOD="POST" enctype="multipart/form-data" name="form0" id="form0" action="/cgi/lightloader/upload.cgi?sID=<?php echo $session; ?>form0" target="form0_iframe">
								<input style="" type="file" name="file0" onchange="uploadForm('form0', '<?php echo $session . 'form0'; ?>');" />
								<br><br><?php echo $detail_4_19 ?> 
								<div class="progressBox"><div class="progressBar" id="<?php echo $session . 'form0_progress'; ?>">&nbsp;</div></div>
								<div class="fileName" id="<?php echo $session . 'form0_fileName'; ?>"></div>
								<input type="hidden" name="idpeople" value="<?php echo $_SESSION['idwebpeople']; ?>">
							</form>
							<iframe name="form0_iframe" id="form0_iframe" src="/classes/lightloader/blank.html" class="loader"></iframe>
						</div>
					</td>
					</tr>
				</table>
			</td>
			<td></td>
			<td>
				<table class="standard" align="center" border="0" cellpadding="0" cellspacing="0" width="300px">
					<tr>
						<td class="etiq2"><?php echo $detail_4_18 ?></td>
					</tr>
					<tr>
					    <td class="contenu2">
							<div id="img1">
								<form METHOD="POST" enctype="multipart/form-data" name="form1" id="form1" action="/cgi/lightloader/upload.cgi?sID=<?php echo $session; ?>form1" target="form1_iframe">
									<input style="" type="file" name="file1" onchange="uploadForm('form1', '<?php echo $session . 'form1'; ?>');" />
									<br><br><?php echo $detail_4_19 ?> 
									<div class="progressBox"><div class="progressBar" id="<?php echo $session . 'form1_progress'; ?>">&nbsp;</div></div>
									<div class="fileName" id="<?php echo $session . 'form1_fileName'; ?>"></div>
									<input type="hidden" name="idpeople" value="<?php echo $_SESSION['idwebpeople']; ?>">
								</form>
								<iframe name="form1_iframe" id="form1_iframe" src="/classes/lightloader/blank.html" class="loader"></iframe>
							</div>
					    </td>
					</tr>
				</table>
			</td>
		</tr>
		</table>
		</fieldset>
		</td>
	</tr>
</table>