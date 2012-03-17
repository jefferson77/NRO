<?php #  Sélection des éléments ?>
<div id="centerzone">
<?php
$classe = 'standard' ;
?>
<h2><?php echo $titreduplic; ?></h2>
		<fieldset>
			<legend>Finalisation par genre d'une semaine compl&egrave;te</legend>
<?php
			$idagent = $_SESSION['idagent'];
			$merch = new db();
			$merch->inline("SELECT * FROM `merchduplic` WHERE `idagent` = $idagent GROUP BY genre ORDER BY idmerch;");
			while ($infosmerge = mysql_fetch_array($merch->result)) { 
?>
			<form action="<?php echo $_SERVER['PHP_SELF'].'?act=mergefinal';?>" method="post">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td>
							Agent
						</td>
						<td>
							<?php echo $_SESSION['prenom']; ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2">
							Vous allez finaliser la semaine 
							<?php
								$date = weekdate($infosmerge['weekm'], date("Y", strtotime($infosmerge['datem']))); echo $infosmerge['weekm']." du ".fdate($date['lun'])." au ".fdate($date['dim']);
							?>
							<input type="hidden" name="weekm" value="<?php echo $infosmerge['weekm']; ?>">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2">
							Pour les missions Merch : <b><?php echo $infosmerge['genre']; ?></b>
							<input type="hidden" name="genre" value="<?php echo $infosmerge['genre']; ?>">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" name="Modifier" value="Valider">
						</td>
					</tr>
				</table>
			</form>
			<form action="<?php echo $_SERVER['PHP_SELF'].'?act=nettoyage&idagent='.$idagent.'&genre='.$infosmerge['genre'];?>" method="post" onsubmit="return confirm('Etes-vous certain de vouloir effacer TOUT ce dossier de duplication?')">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td colspan="2" align="right">
							<input type="submit" name="Modifier" value="Tout supprimer">
						</td>
					</tr>
				</table>
			</form>
<br><br>
<?php } ?>
		</fieldset>

</div>