<?php
$outetat = 2;

?>
<style type="text/css" media="screen">
	td {
		vertical-align: top;
	}
</style>
<div id="centerzonelarge">
	<fieldset>
		<legend>Suppresion de la Mission ?</legend>
		<form action="?act=delete" method="post">
			<input type="hidden" name="idmerch" value="<?php echo $_REQUEST['idmerch'] ?>">
			<table border="0" cellspacing="5" cellpadding="5" align="center">
				<tr>
					<td width="40%">
						<h1>Motif ?</h1>
						<?php
						foreach ($raisonout as $key	=> $raison) {
							echo '<p><input type="radio" name="raisonout" value="'.$key.'"> '.$raison.' </p>';
						}
						?>
					</td>
					<td>
						<h1>Notes</h1>
						<p><textarea name="noteout" rows="10" cols="60"></textarea></p>
					</td>
				</tr>
			</table>
			<p align="center"><input type="submit" name="Selectionner" value="Delete Mission"></p>
		</form>
	</fieldset>
</div>