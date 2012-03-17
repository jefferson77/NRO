<?php
####nouveau formulaire####
?>

<p></p>
<div class="formdiv" id="container">

	<h4><?php echo $modifiche5_01; ?></h4>
	<div>
		<?php
		$webtype = $_SESSION['webtype'];
		if ($webtype == 0) {
			echo $modifiche5_02a;
		} else {
			echo $modifiche5_02b;
		} ?>
			<br><br>
			<?php echo $modifiche5_03; ?>
			</div>
	<div align="right" style="height:50px;">
		<form action="adminpeople2.php" method="post">
			<input type="hidden" name="validation" value="1">
			<input type="hidden" name="idwebpeople" value="<?php echo $_SESSION['idpeople'] ?>">
			<a href="?page=modifiche&step=4"><img src="../../web/illus/formback.png" width="70" height="38"></a>&nbsp;
			<input type="submit" name="submit" class="btn formok">
	</div>
</div>