<div id="centerzonelarge">
<?php include 'v-list.php'; ?>
</div>
<div id="infobouton">
<table border="0" cellspacing="0" cellpadding="0" align="center" width="90%">
		<td><?php
if ($from > 0) { ?><a class="blanc" href="<?php echo $_SERVER['PHP_SELF']; ?>?skip=<?php echo $rwd;?>"><img src="<?php echo STATIK ?>illus/NAVprv.gif" alt="NAVprv.gif" width="13" height="15" border="0" align="middle"></a><?php }
echo '&nbsp;&nbsp;'.$from;?> &agrave; <?php if ($skip > $Count) {echo $Count;} else {echo $skip;} ?> sur <?php echo $Count.'&nbsp;&nbsp;';
if ($skip < $Count) { ?><a class="blanc" href="<?php echo $_SERVER['PHP_SELF']; ?>?skip=<?php echo $skip;?>"><img src="<?php echo STATIK ?>illus/NAVnxt.gif" alt="NAVnxt.gif" width="13" height="15" border="0" align="middle"></a><?php } ?></td>
		
		<td>
<?php
	$max = ceil($Count / $lister);
	
	if ($max > 50) $max = 60;
	
	for ($i = 1; $i <= $max; $i++) {
		$sk = ($i - 1) * $lister;
		if ($sk == $from) {
			echo '<img src="'.STATIK.'illus/hhvert.gif" alt="NAVoff.gif" width="6" height="6" border="0">';
		} else {
			echo '<a class="level2" href="'.$_SERVER['PHP_SELF'].'?skip='.$sk.'"><img src="'.STATIK.'illus/hhrouge.gif" alt="NAVoff.gif" width="6" height="6" border="0"></a>';
		}
	}
?>
		</td>
</table>
</div>
