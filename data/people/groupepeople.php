<div id="centerzonelarge">	
<?php
switch ($_REQUEST['act']) {
############## Regroupement Tableau PEOPLE  #########################################
		case "groupeshow": 
			# recherche people source			
			$infos1 = $DB->getRow("SELECT * FROM `people` WHERE `idpeople` = ".$_REQUEST['idpeoplesource']);
			$infos2 = $DB->getRow("SELECT * FROM `people` WHERE `idpeople` = ".$_REQUEST['idpeoplecible']);
?>
	<table border="0" class="standard" cellspacing="3" cellpadding="2" align="center" width="98%">
		<tr>
			<th colspan="3">S&eacute;lection du people source et du people cible</th>
		</tr>
		<tr>
			<th width="45%">
				#ID People Source : <?php echo $_REQUEST['idpeoplesource'] ?><br>
				<?php echo $infos1['pnom']; ?> - <?php echo $infos1['pprenom']; ?>
			</th>
			<th>&nbsp;</th>
			<th width="45%">
				#ID People Cible : <?php echo $_REQUEST['idpeoplecible'] ?><br>
				<?php echo $infos2['pnom']; ?> - <?php echo $infos2['pprenom']; ?>
			</th>
		</tr>
		<tr>
			<td colspan="3" align="center">
				<?php
				if (!empty($infos1['idpeople']) and !empty($infos2['idpeople'])) { ?>
					<form action="?act=groupeassign" method="post" onClick="return confirm('Etes-vous certain de vouloir Regrouper ces jobistes?')">
						<input type="hidden" name="idpeoplesource" value="<?php echo $infos1['idpeople']; ?>">
						<input type="hidden" name="idpeoplecible" value="<?php echo $infos2['idpeople']; ?>">
						<input type="submit" name="action" value="Assigner">
					</form>
				<?php } else { ?>
					<form action="?act=groupeselect" method="post">
						<input type="submit" name="action" value="Retour">
					</form>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td valign="top" bgcolor="#FFFFFF">
				<?php 
					if (!empty($infos1['idpeople'])) {
						$_REQUEST['idpeople'] = $infos1['idpeople'];
						include 'experiencepeople.php';	
					} else { echo 'inconnu'; }
				?>	
			</td>
			<th>
				==>
			</th>
			<td valign="top" bgcolor="#FFFFFF">
				<?php 
					if (!empty($infos2['idpeople'])) {
						$_REQUEST['idpeople'] = $infos2['idpeople'];
						include 'experiencepeople.php';	
					} else { echo 'inconnu'; }
				?>	
			</td>
		</tr>
	</table>

<?php
		break;
############## Regroupement SELECT PEOPLE  #########################################
		case "groupeselect": 
		default: 
?>
<br>
	<?php if (($vipassign > 0) or ($animassign > 0) or ($merchassign > 0)) { 
	echo 'Regroupement effectu&eacute; de '.$_REQUEST['idpeoplesource'].' vers '.$_REQUEST['idpeoplecible'].' :<br>
	Vip : '.$vipassign.' - Anim : '.$animassign.' - Merch : '.$merchassign.'<br>';
	}
	?>
<form action="?act=groupeshow" method="post">
	<table border="0" class="standard" cellspacing="5" cellpadding="5" align="center" width="50%">
		<tr>
			<th colspan="3">S&eacute;lection du people source et du people cible</td>
		</tr>
		<tr>
			<td width="45%">#ID People Source</td>
			<th>&nbsp;</th>
			<td width="45%">#ID People Cible</td>
		</tr>
		<tr>
			<td><input type="text" size="20" name="idpeoplesource" value=""></td>
			<th>==></th>
			<td><input type="text" size="20" name="idpeoplecible" value=""></td>
		</tr>
		<tr>
			<td colspan="3" align="center"><input type="submit" name="action" value="Afficher"></td>
		</tr>
	</table>
</form>
<?php } ?>
</div>