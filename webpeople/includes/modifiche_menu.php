<?php
$titre = 'titre'.$_SESSION['lang'];
$texte = 'texte'.$_SESSION['lang'];

if (!$mclass1) { $mclass1 = 'menu_title2'; }
if (!$mclass2) { $mclass2 = 'menu_title2'; }
if (!$mclass3) { $mclass3 = 'menu_title2'; }
if (!$mclass4) { $mclass4 = 'menu_title2'; }
if (!$mclass5) { $mclass5 = 'menu_title2'; }
if (!$mclass6) { $mclass6 = 'menu_title2'; }

?>
<div class="menudiv">
	<div class="titrerubmenu"><h4><?php echo $menu_04 ?></h4></div>


<div class="menu_item">
		<div class="<?php echo $mclass1 ?>">
        	<div class="menu_title_icon">1</div>
        	<div class="menu_title_txt"><?php echo $modiFmenu_01 ?></div>
			<div class="spacer">&nbsp;</div>
		</div>
		<?php
		if ($step == 0) {
			echo'<div class="menu_txt">';
			if ($webtype == 0) {
				echo $modiFmenu_01a;
			} else {
				echo $modiFmenu_01b;
			}
			echo'<br>'.$modiFmenu_01c.'</div>';
		}
		?>
		<div class="<?php echo $mclass2 ?>">
        	<div class="menu_title_icon">2</div>
        	<div class="menu_title_txt"><?php echo $modiFmenu_02 ?></div>
			<div class="spacer">&nbsp;</div>
   	    </div>
		<div class="<?php echo $mclass3 ?>">
        	<div class="menu_title_icon">3</div>
        	<div class="menu_title_txt"><?php echo $modiFmenu_03 ?></div>
			<div class="spacer">&nbsp;</div>
   	    </div>
		<div class="<?php echo $mclass4 ?>">
        	<div class="menu_title_icon">4</div>
        	<div class="menu_title_txt"><?php echo $modiFmenu_04 ?></div>
			<div class="spacer">&nbsp;</div>
   	    </div>
		<div class="<?php echo $mclass5 ?>">
        	<div class="menu_title_icon">5</div>
        	<div class="menu_title_txt"><?php echo $modiFmenu_05 ?></div>
			<div class="spacer">&nbsp;</div>
   	    </div>
		<span id="menuvalidate">
		<div class="<?php echo $mclass6 ?>">
        	<div class="menu_title_icon">6</div>
        	<div class="menu_title_txt"><?php echo $modiFmenu_06 ?></div>
			<div class="spacer">&nbsp;</div>
   	    </div>
		</span>
    </div>
