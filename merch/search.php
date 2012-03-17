<?php #  Centre de recherche d'merch ?>
<div id="centerzone">
	<h1 align="center">Gestion des merch</h1>
<?php $classe = "standard" ?>
	<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<th class="<?php echo $classe; ?>">Code merch</th>
			<th class="<?php echo $classe; ?>">Client</th>
			<th class="<?php echo $classe; ?>">Shop</th>
			<th class="<?php echo $classe; ?>">Selection</th>
		</tr>
<?php
# Recherche des résultats
$merch = new db();
$merch->inline("SELECT * FROM `merch`");

while ($row = mysql_fetch_array($merch->result)) { 
?>
		<tr>
		<?php 
		# recherche client
		if (!empty($row['idclient'])) {
			$idclient=$row['idclient'];
			$detail3 = new db();
			$detail3->inline("SELECT * FROM `client` WHERE `idclient`=$idclient");
			$infosclient = mysql_fetch_array($detail3->result) ; 
		}
		# recherche shop
		if (!empty($row['idshop'])) {
			$idshop=$row['idshop'];
			$detail5 = new db();
			$detail5->inline("SELECT * FROM `shop` WHERE `idshop`=$idshop");
			$infosshop = mysql_fetch_array($detail5->result) ; 
		}
		?>
			<td class="<?php echo $classe; ?>"><?php echo $row['idmerch'] ?></td>
			<td class="<?php echo $classe; ?>"><?php echo $infosclient['societe']; ?></td>
			<td class="<?php echo $classe; ?>" align="left"><?php echo $infosshop['societe'].' ('.$infosshop['ville'].' )' ; ?></td>
			<td class="<?php echo $classe; ?>"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=show&idmerch='.$row['idmerch'];?>">Afficher</a></td>
		</tr>
<?php } ?>
	</table>
<?php echo $yesterday; ?>
</div>
