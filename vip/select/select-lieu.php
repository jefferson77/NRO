<?php
## init vars
if(isset($_GET['idvip'])) $idvip = $_GET['idvip']; 
else if(isset($_POST['idvip'])) $idvip = $_POST['idvip']; 
else $idvip = null;
if(isset($_GET['idvipjob'])) $idvipjob=$_GET['idvipjob']; 
else if(isset($_POST['idvipjob'])) $idvipjob=$_POST['idvipjob']; 
else $idvipjob=null;

if(empty($_POST['send']) && ($_GET['skip'] == '')) { ## ! ne pas mettre de empty pour skip : si la valeur est 0, on doit afficher la premiere page de resultats
	$_SESSION['quid']='';
	$_SESSION['quod']='';
	### Première étape : formulaire de recherche de Lieu ?>
	<h2 align="center">Recherche des Lieux <?php echo ($_GET['from']=='mission')?'mission':'job';?></h2>
	<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$idvipjob; if(!empty($idvip)) {echo '&idvip='.$idvip;} echo '&etat='.$_GET['etat'].'&sel=lieu&s='.$_GET['s'].'&from='.$_GET['from']; ?>" method="post">
		<input type="hidden" name="send" value="1"> 
		<fieldset>
			<legend>
				Infos de recherche
			</legend>
			<label for="codeshop">Code</label><input type="text" name="codeshop" id="codeshop"><br>
			<label for="societe">Soci&eacute;t&eacute;</label><input type="text" name="societe" id="societe"><br>
			<label for="snom">Contact</label><input type="text" name="snom" id="snom"><br>
			<label for="ville">Ville</label><input type="text" name="ville" id="ville"><br>
			<label for="cp">Code Postal</label><input type="text" name="cp" id="cp"><br>
		</fieldset>
		<div align="center"><input type="submit" name="Rechercher" value="Rechercher"></div>
	</form>
	<br>
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="40%">
		<tr bgcolor="#FF6699">
			<td class="<?php echo $classe; ?>" align="center"> 
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>?act=modif&mod=lieu&etat=<?php echo $_GET['etat'].'&from='.$_GET['from']; ?>" method="post">
					<input type="hidden" name="idvipjob" value="<?php echo $idvipjob ;?>"> 
					<input type="hidden" name="idvip" value="<?php echo $idvip; ?>">
					<input type="hidden" name="idshop" value=""> 
					<input type="submit" name="remove" value="Remove LIEU from <?php echo (!empty($idvip))?'mission':'JOB';?>"> 
				</form>
			</td>
		</tr>
	</table>
<?php
} else {
	### Afficher la liste des lieux correspondant a la recherche
	# VARIABLE skip
	$skip=(!empty($_GET['skip']) AND $_GET['skip']>0)?$_GET['skip']:0;
	$skipprev = $skip - 25;
	$skipnext = $skip + 25;

   $client = new db();

	if(empty($_SESSION['quid'])) {
      $fieldlist = array (
            'codeshop' => 'codeshop',                
            'societe' => 'societe',                
            'snom' => 'snom',
            'ville' => 'ville',
            'cp' => 'cp'
         );

      $_SESSION['quid'] = $client->MAKEsearch($fieldlist);
      $_SESSION['quod'] = $client->quod;
	} 

	$client->inline("SELECT idshop FROM `shop` WHERE ".$_SESSION['quid']);
   $count = mysql_num_rows($client->result);
	$client->inline("SELECT * FROM `shop` WHERE ".$_SESSION['quid']." ORDER BY codeshop LIMIT ".$skip.", 25");

	?><fieldset>
			<legend>
				R&eacute;sultats de recherche <?php echo (!empty($idvip))?'mission':'JOB', $quod; ?>
			</legend>
			<?php $classe = "standard" ?>
			<a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$idvipjob; if(!empty($idvip)) {echo '&idvip='.$idvip;} echo '&etat='.$_GET['etat'].'&sel=lieu';?>">Retour &agrave; la recherche</a><br>
			<br>
			<table class="<?php echo $classe; ?>" border="0" width="80%" cellspacing="1" cellpadding="1" align="center">
<?php if ($count > 25) { ?>
				<tr>
					<td class="<?php echo $classe; ?>" colspan="2">
               <?php if ($skipprev >= 0) {?>
                  <a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$idvipjob; if(!empty($idvip)) {echo '&idvip='.$idvip;} echo '&etat='.$_GET['etat'].'&sel=lieu&skip='.$skipprev;?>">Previous</a></td>
               <?php } ?>
					<td class="<?php echo $classe; ?>" colspan="3" align="center"><?php echo $skip + 1;?> à <?php echo (($skip+25)<=$count)?$skip+25:$count;?> sur <?php echo $count; ?></td>
					<td class="<?php echo $classe; ?>" colspan="2" align="right">
                <?php if ($skipnext < $count) {?>
               <a href="<?php echo $_SERVER['PHP_SELF'].'?act=select&idvipjob='.$idvipjob; if(!empty($idvip)) {echo '&idvip='.$idvip;} echo '&etat='.$_GET['etat'].'&sel=lieu&skip='.$skipnext;?>">Next</a></td>
               <?php } ?>
				</tr>
<?php } ?>
				<tr>
					<th class="<?php echo $classe; ?>">&nbsp;</th>
					<th class="<?php echo $classe; ?>">&nbsp;</th>
					<th class="<?php echo $classe; ?>">Code</th>
					<th class="<?php echo $classe; ?>">Client</th>
					<th class="<?php echo $classe; ?>">Adresse</th>
					<th class="<?php echo $classe; ?>">Nom</th>
					<th class="<?php echo $classe; ?>"></th>
				</tr>
		<?php
		while ($row = mysql_fetch_array($client->result)) { 
		?>
				<tr>
					<td class="<?php echo $classe; ?>">
						<?php
						if($row['glat']>0 && $row['glong']>0) echo '<img src="'.STATIK.'illus/geoloc.png" alt="'.$row['glat'].','.$row['glong'].'">';
						?>
					</td>
					<td class="<?php echo $classe; ?>">
						<form action="<?php echo NIVO ?>data/shop/adminshop.php?act=show&idshop=<?php echo $row['idshop'];?>&etat=<?php echo $_GET['etat'];?>&s=<?php echo $_GET['s'].'&embed=yes&from='.$_GET['from'];?>" method="post">
							<input type="hidden" name="idvipjob" value="<?php echo $idvipjob;?>">
							<input type="hidden" name="idshop" value="<?php echo $row['idshop'];?>">
							<input type="hidden" name="idvip" value="<?php echo $idvip;?>">
							<input type="submit" name="Modifier" value="Modif">
						</form>
					</td>
					<td class="<?php echo $classe; ?>"><?php echo $row['codeshop'] ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['societe']; ?></td>

					<td class="<?php echo $classe; ?>"><?php echo $row['adresse'].' '.$row['cp'].' '.$row['ville'].' '.$row['pays']; ?>
					</td>
					<td class="<?php echo $classe; ?>"><?php echo $row['snom']; ?></td>
					<td class="<?php echo $classe; ?>">
						<form action="<?php echo $_SERVER['PHP_SELF'];?>?act=modif&mod=lieu&etat=<?php echo $_GET['etat'];?>&s=<?php echo $_GET['s'].'&from='.$_GET['from'];?>" method="post">
							<input type="hidden" name="idvipjob" value="<?php echo $idvipjob ;?>">
							<input type="hidden" name="idshop" value="<?php echo $row['idshop'] ;?>">
							<input type="hidden" name="idvip" value="<?php echo $idvip ;?>">
							<input type="submit" name="Selectionner" value="Select">
						</form>
					</td>
				</tr>
		<?php
		} 
	?>
	</table>
	<br>
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="40%">
		<tr bgcolor="#FF6699">
			<td class="<?php echo $classe; ?>" align="center"> 
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=lieu&etat='.$_GET['etat'].'&from='.$_GET['from'];?>" method="post">
				<input type="hidden" name="idvipjob" value="<?php echo $idvipjob ;?>"> 
				<?php if(!empty($idvip)) echo '<input type="hidden" name="idvip" value="'.$idvip.'">'; ?> 
				<input type="hidden" name="idshop" value=""> 
				<input type="submit" name="Selectionner" value="Remove LIEU from <?php echo (!empty($idvip))?'Mission':'JOB'; ?>"> 
				</form>
			</td>
		</tr>
	</table>
	</fieldset>
		<br>	
	<?php echo $count;?> Results
	<?php
}
?>
