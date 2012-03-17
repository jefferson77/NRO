<div id="pied">
<table width="99%" cellspacing="1" cellpadding="0">
	<tr>
		<td>
			<a href="<?php echo NIVO.$_SESSION['menu']; ?>">
				<img src="<?php echo STATIK ?>nro/illus/menu.png" alt="menu.png" width="13" height="11" border="0" align="left">
			</a>
		</td>
		<td>&nbsp;<?php if (!empty($PhraseBas)) echo $PhraseBas;?></td>
		<td align="right">
<?php if (@$_SESSION['roger'] == 'admin' || @$_SESSION['roger'] == 'devel') { ?>
	<a id="resButton" style="color: #991814; text-decoration: none;" href="#" >Res</a> -
	<a id="varsButton" style="color: #991814; text-decoration: none;" href="#" >Vars</a><?php
?>
		</td>
		<td align="right">
<?php


if ($_SESSION['menu'] == 'admin/admenu.php') {
?><a class="rouge" href="<?php echo NIVO ?>menu.php"><b>User</b></a> | <?php
} else {
?><a class="rouge" href="<?php echo NIVO ?>admin/admenu.php"><b>Admin</b></a> | <?php
}

?>
<?php } ?>
		<?php echo @$_SESSION['prenom'].' '.@$_SESSION['nom']; ?> | <?php  echo strftime("%R | %d/%m/%Y", time()); ?> |
			<a href="<?php echo NIVO ?>index.php"><img src="<?php echo STATIK ;?>nro/illus/logout.png" alt="logout.png" width="11" height="11" border="0" align="middle"></a>
		</td>
	</tr>
</table>
</div>
<?php include NIVO.'includes/footer.php'; ?>
</body>
</html>