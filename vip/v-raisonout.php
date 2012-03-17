<?php
$jobetat = $DB->getOne("SELECT etat FROM vipjob WHERE idvipjob = ".$_REQUEST['idvipjob']);

switch ($jobetat) {
	case '0':
		$typejob = 'Devis';
		$raisons = array(1, 2, 3, 9);
		$outetat = 99;
	break;
	case '1':
		default:
		$typejob = 'Job';
		$raisons = array(11, 12, 19);
		$outetat = 2;
	break;
}

?>
<style type="text/css" media="screen">
	td {
		vertical-align: top;
	}
</style>
<div id="centerzonelarge">
	<fieldset>
		<legend>Suppresion du <?php echo $typejob ?> ?</legend>
		<form action="?act=delete" method="post">
			<input type="hidden" name="idvipjob" value="<?php echo $_REQUEST['idvipjob'] ?>">
			<input type="hidden" name="etat" value="<?php echo $outetat ?>">
			<table border="0" cellspacing="5" cellpadding="5" align="center">
				<tr>
					<td width="40%">
						<h1>Motif ?</h1>
						<?php
						foreach ($raisons as $raison) {
							echo '<p><input type="radio" name="raisonout" value="'.$raison.'"> '.$raisonout[$raison].' </p>';
						}
						?>
					</td>
					<td>
						<h1>Notes</h1>
						<p><textarea name="noteout" rows="10" cols="60"></textarea></p>
					</td>
				</tr>
			</table>
			<p align="center"><input type="submit" name="Selectionner" value="Delete <?php echo $typejob ?>"></p>
		</form>
	</fieldset>
</div>