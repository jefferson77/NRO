<?php
$titre = 'titre'.$_SESSION['lang'];
$texte = 'texte'.$_SESSION['lang'];
?>
<div class="newsdiv">
	<div class="titrerubnews"><h4>News</h4></div>
<?php
	$detail2 = new db('webnewspeople', 'idwebnewspeople', 'webneuro');
	$detail2->inline("SELECT * FROM `webnewspeople` WHERE `online` = 'oui' ORDER BY datepublic DESC LIMIT 7");
	while ($infos2 = mysql_fetch_array($detail2->result)) {

echo'<div class="news_item">';
		if ($infos2['urgent'] == 1) { echo'<div class="news_title2">';} else { echo'<div class="news_title">';}
        	echo'<div class="news_icon">';
			if ($infos2['urgent'] == 1) {
				echo '<img src="<?php echo STATIK ?>illus/bell_error.png" width="16" height="16" border="0">';
				} else { echo'&nbsp;';}
				echo'</div>
        	<div class="news_title_txt">'.stripslashes($infos2[$titre]).'</div>
            <div class="news_title_date">'.fdate($infos2['datepublic']).'</div>
			<div class="spacer">&nbsp;</div>
   	    </div>
        <div class="news_txt">'.stripslashes(nl2br($infos2[$texte])).'</div>
    </div>';
	}
?>