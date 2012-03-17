<div id="leftmenu">

</div>

<div id="infozone">
<table class="" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
	<tr align="center">
		<th class="alt">Num</th>
		<th class="alt">Intitule</th>
		<th class="alt">P&eacute;riode</th>
		<th class="alt">Date send</th>
		<th class="alt"></th>
	</tr>
<?php
while ($row = mysql_fetch_array($afficher->result)) {
	## Période en fonctino de la date in
	if ($row['datein'] != "0000-00-00") {
		setlocale(LC_TIME, "fr");
		$periode = strftime("%B	%Y", strtotime($row['datein']));
	} else {$periode = "-";}
	## Affichage
	echo '
	<tr class="mediumblanc" align="right">
		<td class="standard" align="center"><b>'.$row['idptg'].'</b></td>
		<td class="standard" align="left">'.$row['nomptg'].'</td>
		<td class="standard" align="center">'.$periode.'</td>
		<td class="standard" align="center">'.fdate($row['datesend']).'</td>
		<td class="standard" align="center"><a href="'.$_SERVER['PHP_SELF'].'?act=detail&idptg='.$row['idptg'].'&skip='.$from.'"><img src="'.STATIK.'illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
	</tr>			
';
}
?>
</table>
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
