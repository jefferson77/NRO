<div id="pied">
<table width="99%" cellspacing="1" cellpadding="0">
	<tr>
		<td> 
			<a href="<?php echo $_SERVER['PHP_SELF'];?>">
			<img src="<?php echo STATIK ?>illus/menu.gif" alt="menu.gif" width="13" height="11" border="0" align="middle"></a>
		</td>
		<td>&nbsp;<?php #echo $PhraseBas; ?><?php echo $textehaut; ?></td>
		<td align="right"> | &nbsp; &copy; Celsys 2003 - 2004 | </td>
		<td align="right"> | 
			<?php 
			# Chrono
				$time_string2 = explode(" ", microtime());
				$etime = $time_string2[1] . substr($time_string2[0], 1, strlen($time_string2[0]));
				echo substr($etime - $stime, 0, 6) . "sec";
			# Chrono fin
			?>
			| <?php echo $_SESSION['prenom'].' '.$_SESSION['nom']; ?> | 
		</td>
		<td align="right">
			<?php  echo strftime("| %d/%m/%Y", time()); ?> |
		</td>
	</tr>
</table>
</div>
</body>
</html>