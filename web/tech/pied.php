<br>
<div align="center">
<?php 
	# Chrono
		$time_string2 = explode(" ", microtime());
		$etime = $time_string2[1] . substr($time_string2[0], 1, strlen($time_string2[0]));
		echo 'data process : ';
		echo substr($etime - $stime, 0, 6) . "sec";
	# Chrono fin
	 ?> | <?php  
if (($_SESSION['roger'] == 'admin') or ($_SESSION['roger'] == 'devel')) {
	echo $_SESSION['prenom'].' '.$_SESSION['nom'];  ?> | <?php 
}
echo strftime("%R | %d/%m/%Y", time()); 
?>
</div>
</body>
</html>