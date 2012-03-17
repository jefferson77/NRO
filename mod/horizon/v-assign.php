<?php
$codecompta = strtoupper(trim($_POST['codecompta']));

### Recherche des infos du client
$infcl = $DB->getArray("SELECT * FROM `client` WHERE `idclient` = '".$_POST['idclient']."'");
$nbrcmpt = $DB->getOne("SELECT * FROM `client` WHERE `codecompta` LIKE '".$codecompta."'");

?>
<div id="centerzonelarge">
	<table border="0" width="95%" cellspacing="1" cellpadding="1" align="center" bgcolor="#003333">
		<tr>
			<td bgcolor="#006666">ID: <?php echo $infcl['idclient'] ?></td>
			<td bgcolor="#006666"><?php echo $infcl['societe'] ?></td>
			<td bgcolor="#006666"><?php echo $infcl['cprenom'].' '.$infcl['cnom'] ?></td>
			<td bgcolor="#006666">TVA: <?php echo $infcl['codetva'].' '.$infcl['tva'] ?></td>
		</tr>
	</table>
	<br>

	<form action="?act=assign" method="post">
		<input type="hidden" name="facfromid" value="<?php echo $_POST['facfromid'] ?>">
		<input type="hidden" name="factoid" value="<?php echo $_POST['factoid'] ?>">
		<input type="hidden" name="ncfromid" value="<?php echo $_POST['ncfromid'] ?>">
		<input type="hidden" name="nctoid" value="<?php echo $_POST['nctoid'] ?>">
		<input type="hidden" name="codeh" value="<?php echo $codecompta ;?>"> 
		<input type="hidden" name="tva" value="<?php echo $infcl['tva'] ;?>"> 
		<input type="hidden" name="codetva" value="<?php echo $infcl['codetva'] ;?>"> 
		<input type="submit" name="valider" value="Valider">
	</form>
</div>