<?php
$datein = date("01/m/Y", strtotime("-25 days"));
$dateout = date("t/m/Y", strtotime("-25 days"));

unset($_SESSION['count']);
unset($_SESSION['skip']);
unset($_SESSION['datein']);
unset($_SESSION['dateout']);
?>
<form action="?act=addStock" method="post">
	<div id="centerzonelarge">
		<table border="0" align="center">
			<tr>
				<td>Date de : </td>
				<td><input type="text" name="datein" value="<?php echo $datein ;?>"></td>
			</tr>
			<tr>
				<td>Date &agrave; :</td>
				<td><input type="text" name="dateout" value="<?php echo $dateout ;?>"></td>
			</tr>
		</table>
		<div align="center">
			<p><input type="submit" value="Stocker"></p>
			<br>
			<i>
				<b>Attention !!</b> le stockage va effacer les donn&eacute;es d&eacute;ja enregistr&eacute;es pour ce mois.<br>
				Si vous avez d&eacute;ja modifi&eacute; le contenu du tableau et que vous ne voulez pas perdre ces infos, contactez <a href="mailto:nico@exception2.be">Nico</a><br>
			</i>
		</div>
	</div>
</form>