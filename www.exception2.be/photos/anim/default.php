<?xml version="1.0" encoding="utf-8"?>
<slide_show>
	<options>
		<background>transparent</background>
		<friction>90</friction>              	<!-- [1,100] -->
		<fullscreen>true</fullscreen>
		<margins>
            <top>-300</top>                   	<!-- [-1000,1000] pixels -->
            <left>0</left>                  	<!-- [-1000,1000] pixels -->
            <bottom>-100</bottom>             	<!-- [-1000,1000] pixels -->
            <right>0</right>                	<!-- [-1000,1000] pixels -->
        </margins>
		<interaction>
            <vertical>false</vertical>			<!-- true, false -->
            <speed>60</speed>					<!-- [-360,360] degrees per second -->
            <default_speed>20</default_speed>	<!-- [-360,360] degrees per second -->
        </interaction>
        
	</options>
<?php
$accepted = array('.jpg', '.png', '.gif');

$files = scandir(substr($_SERVER['SCRIPT_FILENAME'], 0, -11));
foreach ($files as $file) {
	if ((in_array(substr($file, -4), $accepted)) and (substr($file, 0, 1) != '.')) {
		$parts = explode(".", $file);
		echo '<photo href="#" title="'.$parts[0].'" target="_self">photos/anim/'.$file.'</photo>';
	}
}

?>
</slide_show>