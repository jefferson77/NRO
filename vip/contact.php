<?php
define('NIVO', '../');

# Entete de page
include NIVO."includes/ifentete.php" ;

if (!isset($_GET['contact'])) $_GET['contact'] = '';
if (!isset($_GET['idvip'])) $_GET['idvip'] = '';

$classe = "vip2" ;

			### Add fiche contact
			if ($_GET['contact'] == 'yes') { 
			### recherche fiche contact
				$jobcontact1 = new db();
				$jobcontact1->inline("SELECT * FROM `jobcontact` WHERE `idvipjob` = ".$_GET['idvipjob']." AND `idpeople` = ".$_GET['idpeople'].";");
				$infocontact1 = mysql_fetch_array($jobcontact1->result) ; 

				$FoundCountcontact = mysql_num_rows($jobcontact1->result);
				### si 0 ADD
					if ($FoundCountcontact == 0) {
						$ajout = new db('jobcontact', 'idjobcontact');
						$_POST['idvipjob'] = $_GET['idvipjob'];
						$_POST['idpeople'] = $_GET['idpeople'];
						$ajout->AJOUTE(array('idvipjob', 'idpeople'));
					}
			#/## recherche fiche contact
			} 
			#/## Add fiche contact

			### Update fiche contact
			if ($_GET['contact'] == 'modif') { 
			### recherche fiche contact
				$jobcontact1 = new db();
				$jobcontact1->inline("SELECT * FROM `jobcontact` WHERE `idvipjob` = ".$_GET['idvipjob']." AND `idpeople` = ".$_GET['idpeople'].";");
				$infocontact1 = mysql_fetch_array($jobcontact1->result) ; 

				$FoundCountcontact = mysql_num_rows($jobcontact1->result);
				### si 1 UPDATE
					if ($FoundCountcontact == 1) {
						$modif = new db('jobcontact', 'idjobcontact');
						$modif->MODIFIE($infocontact1['idjobcontact'], array('etatcontact', 'notecontact'));
					}
			#/## recherche fiche contact
			} 
			#/## Update fiche contact


				### RECHERCHE Contact people
					$sql = "SELECT
					c.notecontact, c.etatcontact, 
					p.pprenom, p.pnom, p.banque, p.codepeople, p.nville, p.ndate, p.lbureau, p.idpeople, p.sexe, p.lfr, p.lnl, p.len, p.gsm, 
					p.adresse1, p.num1, p.bte1, p.cp1, p.ville1, 
					p.adresse2, p.num2, p.bte2, p.cp2, p.ville2,
					a.nom AS agentnom, a.prenom AS agentprenom, a.atel, a.agsm 
					FROM jobcontact c 
					LEFT JOIN people p ON c.idpeople = p.idpeople 
					LEFT JOIN agent a ON c.idagent = a.idagent 
					WHERE c.idvipjob = ".$_GET['idvipjob'];
					
					$detailcontact = new db();
					$detailcontact->inline($sql);
				#/## RECHERCHE Contact people

?>
<div id="miniinfozone">
		<fieldset>
			<legend>
				Recherche des contacts : <?php $FoundCount = mysql_num_rows($detailcontact->result); echo '('.$FoundCount.' Results)'; ?>
			</legend>
			<?php 

			if ($_GET['contact'] == 'yes') { 
				### RECHERCHE Contact people
					$sql = "SELECT
					c.notecontact, c.etatcontact, 
					p.pprenom, p.pnom, p.banque, p.codepeople, p.nville, p.ndate, p.lbureau, p.idpeople, p.sexe, p.lfr, p.lnl, p.len, p.gsm, 
					p.adresse1, p.num1, p.bte1, p.cp1, p.ville1, 
					p.adresse2, p.num2, p.bte2, p.cp2, p.ville2,
					a.nom AS agentnom, a.prenom AS agentprenom, a.atel, a.agsm 
					FROM jobcontact c 
					LEFT JOIN people p ON c.idpeople = p.idpeople 
					LEFT JOIN agent a ON c.idagent = a.idagent 
					WHERE c.idvipjob = ".$_GET['idvipjob']." AND c.idpeople = ".$_GET['idpeople'];
					
					$detailcontact1 = new db();
					$detailcontact1->inline($sql);
					$rowcontact1 = mysql_fetch_array($detailcontact1->result) ;
				#/## RECHERCHE Contact people
			?>
			<form action="<?php echo $_SERVER['PHP_SELF'].'?&idvipjob='.$_GET['idvipjob'].'&idvip='.$_GET['idvip'].'&act=select&sel=people&etape=listepeople&contact=modif&idpeople='.$rowcontact1['idpeople']; ?>" method="post">
			<input type="hidden" name="idvipjob" value="<?php echo $_GET['idvipjob'] ;?>">
			<input type="hidden" name="idvip" value="<?php echo $_GET['idvip'] ;?>">
			<input type="hidden" name="idpeople" value="<?php echo $rowcontact1['idpeople'] ;?>">
				<table border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
					<tr>
						<td class="<?php echo $classe; ?>">
							<?php echo $rowcontact1['codepeople'].' - '.$rowcontact1['idpeople']; ?> <?php echo $rowcontact1['pnom']; ?> <?php echo $rowcontact1['pprenom']; ?>
						</td>
						<td class="<?php echo $classe; ?>">
							<input type="radio" name="etatcontact" value="0" <?php if (($rowcontact1['etatcontact'] == '0') or ($rowcontact['etatcontact'] == '')) { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-grey.gif" border="0" alt="phone-grey.gif" width="15" height="15">'; ?>
							<input type="radio" name="etatcontact" value="10" <?php if ($rowcontact1['etatcontact'] == '10') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-blue.gif" border="0" alt="phone-green.gif" width="15" height="15">'; ?>
							<input type="radio" name="etatcontact" value="20" <?php if ($rowcontact1['etatcontact'] == '20') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-red.gif" border="0" alt="phone-green.gif" width="15" height="15">'; ?>
							<input type="radio" name="etatcontact" value="30" <?php if ($rowcontact1['etatcontact'] == '30') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-orange.gif" border="0" alt="phone-green.gif" width="15" height="15">'; ?>
							<input type="radio" name="etatcontact" value="40" <?php if ($rowcontact1['etatcontact'] == '40') { echo ' checked>';} echo '<img src="'.STATIK.'illus/phone-green.gif" border="0" alt="phone-green.gif" width="15" height="15">'; ?>

						</td>
						<td class="<?php echo $classe; ?>">
							Notes : <input type="text" name="notecontact" value="<?php echo $rowcontact1['notecontact']; ?>" size="40">
						</td>
						<td class="<?php echo $classe; ?>" align="right">
							<input type="submit" name="ok" value="ok">
						</td>
					</tr>
				</table>
			</form>
			<?php } ?>
			<table class="standard" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<th class="<?php echo $classe; ?>">Code - id</th>
					<th class="<?php echo $classe; ?>">E</th>
					<th class="<?php echo $classe; ?>">Nom</th>
					<th class="<?php echo $classe; ?>">Pr&eacute;nom</th>
					<th class="<?php echo $classe; ?>"></th>
					<th class="<?php echo $classe; ?>">Sexe</th>
					<th class="<?php echo $classe; ?>">Fr</th>
					<th class="<?php echo $classe; ?>">NL</th>
					<th class="<?php echo $classe; ?>">En</th>
					<th class="<?php echo $classe; ?>">CP</th>
					<th class="<?php echo $classe; ?>">Ville</th>
					<th class="<?php echo $classe; ?>">CP2</th>
					<th class="<?php echo $classe; ?>">Ville2</th>
					<th class="<?php echo $classe; ?>">GSM</th>
					<th class="<?php echo $classe; ?>"></th>
				</tr>
		<?php
		while ($row = mysql_fetch_array($detailcontact->result)) { 
		?>
				<tr>
					<td class="<?php echo $classe; ?>"><?php echo $row['codepeople'].' - '.$row['idpeople']; ?></td>
					<td class="<?php echo $classe; ?>">
						<?php ### CONTACT people ?>
						<a href="<?php echo $_SERVER['PHP_SELF'].'?&idvipjob='.$_GET['idvipjob'].'&idvip='.$_GET['idvip'].'&act=select&sel=people&etape=listepeople&contact=yes&idpeople='.$row['idpeople']; ?>">
							<?php if (($row['etatcontact'] == '0') or ($row['etatcontact'] == '')) {echo '<img src="'.STATIK.'illus/phone-grey.gif" border="0" alt="phone-grey.gif" width="15" height="15">';} ?>
							<?php if ($row['etatcontact'] == '10') {echo '<img src="'.STATIK.'illus/phone-blue.gif" border="0" alt="phone-green.gif" width="15" height="15">';} ?>
							<?php if ($row['etatcontact'] == '20') {echo '<img src="'.STATIK.'illus/phone-red.gif" border="0" alt="phone-green.gif" width="15" height="15">';} ?>
							<?php if ($row['etatcontact'] == '30') {echo '<img src="'.STATIK.'illus/phone-orange.gif" border="0" alt="phone-green.gif" width="15" height="15">';} ?>
							<?php if ($row['etatcontact'] == '40') {echo '<img src="'.STATIK.'illus/phone-green.gif" border="0" alt="phone-green.gif" width="15" height="15">';} ?>
						</a>
						<?php #/## CONTACT people ?>
					</td>
					<td class="<?php echo $classe; ?>"><?php echo $row['pnom']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['pprenom']; ?></td>
					<td class="<?php echo $classe; ?>"><a href="<?php echo NIVO.'data/people/adminpeople.php?act=show&idpeople='.$row['idpeople'];?>" target="_blank"><img src="<?php echo STATIK ?>illus/icon_profile.gif" border="0" width="15" height="12"></a></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['sexe']; ?></td> 
					<td class="<?php echo $classe; ?>"><?php echo $row['lfr']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['lnl']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['len']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['cp1']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['ville1']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['cp2']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['ville2']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['gsm']; ?></td>
					<td class="<?php echo $classe; ?>"><?php echo $row['notecontact']; ?></td>
				</tr>
		<?php 
			}
		?>
			</table>
			<br>

<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
</div>