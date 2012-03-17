<div id="centerzone">
		<fieldset>
			<legend>Duplication d'une semaine compl&egrave;te</legend>
<?php
if (($_POST['semainesource'] == '') or ($_POST['anneesource'] == '')){ $_GET['act'] = 'duplic-set'; } # si semaine pas remplie
if ($_GET['act'] == 'duplic-set') {
?>
			<form action="<?php echo $_SERVER['PHP_SELF'].'?act=duplic-ready';?>" method="post">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td width="200" align="right">
							Agent : &nbsp;
						</td>
						<td>
							<?php echo $_SESSION['prenom']; ?>
							<input type="hidden" name="prenom" value="<?php echo $_SESSION['prenom']; ?>">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td align="right">
							Vous allez cr&eacute;er la semaine : &nbsp;
						</td>
						<td>
							<input type="text" size="20" name="semainecible" value="">  <input type="text" size="6" name="anneecible" value="<?php echo date("Y") ;?>">  
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td align="right">
							Sur base de la semaine : &nbsp;
						</td>
						<td>
							<input type="text" size="20" name="semainesource" value="">  <input type="text" size="6" name="anneesource" value="<?php echo date("Y") ;?>">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td align="right" valign="top">
							Pour <b>vos</b> missions Merch : &nbsp;<br>
							(sauf pour <b>EAS</b>) &nbsp;
						</td>
						<td align="left" valign="top">
							<?php if (strchr($genretemp, 'Rack assistance')) { echo ' &nbsp; - &nbsp; Rack assistance <i>(indisponible : section non vide)</i>'; } else { ?><input type="radio" name="genre" value="Rack assistance" checked>Rack assistance<?php } ?><br>
							<?php if (strchr($genretemp, 'Store Check')) { echo ' &nbsp; - &nbsp; Store Check <i>(indisponible : section non vide)</i>'; } else { ?><input type="radio" name="genre" value="Store Check" checked>Store Check<?php } ?><br>
							<?php if (strchr($genretemp, 'EAS')) { echo ' &nbsp; - &nbsp; EAS <i>(indisponible : section non vide)</i>'; } else { ?><input type="radio" name="genre" value="EAS" checked>EAS<?php } ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td align="center" colspan="2">
							<input type="submit" name="Modifier" value="Valider">
						</td>
					</tr>
					<tr>
						<td>
							<input type="reset" name="Reset" value="Reset">
						</td>
					</tr>
				</table>
			</form>
<?php
}
if ($_GET['act'] == 'duplic-ready' ) {
				$genre = $_POST['genre'];
				$idagent = $_SESSION['idagent'];

				$SQLsource = weekdate($_POST['semainesource'], $_POST['anneesource']);
				$datelun = $SQLsource['lun'];
				$datedim = $SQLsource['dim'];
				$sql = "SELECT idmerch FROM `merch` WHERE `idagent` = ".$idagent." AND `datem` BETWEEN '".$datelun."' AND '".$datedim."' AND `genre` = '".$genre."' AND `recurrence` = 1;";
				
				//echo $sql."<br>";
				$merch1 = new db();
				$merch1->inline($sql);



				$FoundCount = mysql_num_rows($merch1->result); 
				
				$date = weekdate($_POST['semainecible'], $_POST['anneecible']);
				$datelun2 = $date['lun'];
				$datedim2 = $date['dim'];
				$countsource = $DB->getOne("SELECT COUNT(idmerch) FROM `merch` WHERE `idagent` = ".$idagent." AND `datem` BETWEEN '".$datelun."' AND '".$datedim."' AND `genre` = '".$genre."' AND `recurrence` = 1;");
				$countcible = $DB->getOne("SELECT COUNT(idmerch) FROM `merch` WHERE `idagent` = ".$idagent." AND `datem` BETWEEN '".$datelun2."' AND '".$datedim2."' AND `genre` = '".$genre."' AND `recurrence` = 1;");
				$tempcountcible = $DB->getOne("SELECT COUNT(idmerch) FROM `merchduplic` WHERE `idagent` = ".$idagent." AND `datem` BETWEEN '".$datelun2."' AND '".$datedim2."' AND `genre` = '".$genre."' AND `recurrence` = 1;");
				if(($countcible <= ($countsource/2)) and ($tempcountcible <= ($countsource/2)))
				{
?>
			<form action="<?php echo $_SERVER['PHP_SELF'].'?act=duplic-process';?>" method="post">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<?php 
							
							$vacances = $DB->getColumn("SELECT dateconge FROM conges");
							foreach($date as $jour)
							{
								if(in_array($jour,$vacances)) $warning[] = "ATTENTION : le ".fdate($jour)." est un jour f&eacute;ri&eacute;";
							}
							?>Vous allez cr&eacute;er la semaine <font color="red"><?php 
							echo $_POST['semainecible'].' '.$_POST['anneecible'] ?></font> du <?php echo fdate($date['lun'])." au ".fdate($date['dim']);
							?>
							<input type="hidden" name="semainecible" value="<?php echo $_POST['semainecible']; ?>">
							<input type="hidden" name="anneecible" value="<?php echo $_POST['anneecible']; ?>">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2">
							Sur base de la semaine  <font color="red">
							<?php
							$date = weekdate($_POST['semainesource'], $_POST['anneesource']); echo $_POST['semainesource'].' '.$_POST['anneesource']; ?></font> du <?php echo fdate($date['lun'])." au ".fdate($date['dim']);
							?>
							<input type="hidden" name="semainesource" value="<?php echo $_POST['semainesource']; ?>">
							<input type="hidden" name="anneesource" value="<?php echo $_POST['anneesource']; ?>">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2">
							Pour les missions Merch : <font color="red"><?php echo $_POST['genre']; ?></font>
							<input type="hidden" name="genre" value="<?php echo $_POST['genre']; ?>">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2">
							De l'agent <font color="red"><?php echo $_SESSION['prenom']; ?></font>
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
						<td colspan="2">
							<font color="red"><?php echo $FoundCount; ?> missions</font> seront dupliqu&eacute;es.
						</td>
					</tr>
					<tr>
						<td colspan="2">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td>
						</td>

						<td align="center">
							<input type="submit" name="Modifier" value="Valider">
						</td>
					</tr>
				</table>
			</form>
			<?php } else {
				$error[] = "Semaine très probablement déjà dupliquée";
			}
			?>
			<form action="<?php echo $_SERVER['PHP_SELF'].'?act=duplic-set';?>" method="post">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="90%">
					<tr>
						<td align="left">
							<input type="submit" name="Retour" value="Retour">
						</td>
						<td>
						</td>
					</tr>
				</table>
			</form>

<?php
}
?>
		</fieldset>

</div>