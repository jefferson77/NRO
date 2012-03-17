<?php
define('NIVO', '../../'); 
$Style = 'anim';
include NIVO."includes/ifentete.php" ;

$classe = "planning" ; 
?>
<div class="corps2">
	<table border="0" cellspacing="1" cellpadding="4" align="center" width="98%" bgcolor="#FFFFFF">
		<?php if ($_POST['titre'] == 1) { ?>
			<tr class="<?php echo $classe; ?>">
				<th class="vip2">Date</th>
				<th class="vip2">Place</th>
				<th class="vip2">Name</th>
				<th class="vip2">Product</th>
				<th class="vip2">Units</th>
				<th class="vip2">Quantity sold</th>
				<th class="vip2">degustation</th>
				<th class="vip2">Out of Stock</th>
			</tr>
		<?php } ?>
		<?php
			if ($sort == '') {$sort = 'an.datem';}

			$quid = "c.idclient = '".$_SESSION['idclient']."'";
			if (($_POST['date2'] == '') and ($_POST['date1'] != '')) { $_POST['date2'] = $_POST['date1'];}
	
			if ($_POST['date1'] != '') {
				$_POST['date1'] = fdatebk($_POST['date1']);
				if (!empty($quid)) { $quid .= " AND "; }	
				$quid .= "an.datem >= '".$_POST['date1']."'";
			}
			if ($_POST['date2'] != '') {
				$_POST['date2'] = fdatebk($_POST['date2']);
				if (!empty($quid)) { $quid .= " AND "; }	
				$quid .= "an.datem <= '".$_POST['date2']."'";
			}
			if (!empty($quid)) {$quid = 'WHERE '.$quid;}
			$recherche2='
				an.idanimation, an.datem, an.weekm, an.reference, an.genre, an.idpeople, an.hin1, an.hout1, an.hin2, an.hout2, an.kmpaye, 
				an.kmfacture, an.frais, an.fraisfacture, an.produit, an.facturation, an.peoplenote, an.ferie, 
				j.boncommande, 
				a.prenom, a.idagent, 
				c.codeclient, c.societe AS clsociete, c.idclient, c.tel, c.fax, 
				co.idcofficer, co.qualite, co.onom, co.oprenom, co.fax AS cofax, 
				s.codeshop, s.societe AS ssociete, s.ville AS sville, 
				p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.gsm, p.codepeople,
				ma.idanimmateriel, ma.stand, ma.gobelet, ma.serviette, ma.four, ma.curedent, ma.cuillere, ma.rechaud, ma.autre
				FROM animation an
				LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
				LEFT JOIN agent a ON j.idagent = a.idagent 
				LEFT JOIN client c ON an.idclient = c.idclient 
				LEFT JOIN cofficer co ON an.idcofficer = co.idcofficer 
				LEFT JOIN people p ON an.idpeople = p.idpeople
				LEFT JOIN shop s ON an.idshop = s.idshop
				LEFT JOIN animmateriel ma ON an.idanimation = ma.idanimation
			';
			$recherche1='
				SELECT '.$recherche2;
			
			$recherche='
				'.$recherche1.'
				'.$quid.'
				 ORDER BY '.$sort.'
			';
			$listing = new db();
			$listing->inline("$recherche;");

			### POUR PDF
			$jobquid = 'WHERE an.idanimation IN(';
			#/ ## POUR PDF

			while ($row = mysql_fetch_array($listing->result))
			{ 
				$idanimation = $row['idanimation'];
				### POUR PDF
					if ($jobquid1 != '') 
					{
						$jobquid1.=" , ";
					}	
					$jobquid1 .= "'".$idanimation."'";
				#/ ## POUR PDF

				$produit = new db('animproduit', 'idanimproduit');
				$produit->inline("SELECT * FROM `animproduit` WHERE `idanimation` = $idanimation");

				$ip = 0; 
				$ventestot = 0;
				$degustationtot = 0;

				$FoundCount = mysql_num_rows($produit->result); 
				if ($FoundCount > 0) {
					while ($prod = mysql_fetch_array($produit->result)) 
					{ 
	
						$ip++;
						#> Changement de couleur des lignes #####>>####
						$i++;
						if (fmod($i, 2) == 1) {
							echo '<tr bgcolor="#dddddd">';
						} else {
							echo '<tr bgcolor="#ffffff">';
						}
						#< Changement de couleur des lignes #####<<####
						?>
							<td class="black"><?php echo fdate($row['datem']); ?></td>
							<td class="black"><?php echo $row['ssociete']." - ".$row['sville']; ?></td>
							<td class="black"><?php echo $row['pprenom']." ".$row['pnom']; ?></td>
							<td class="black"><?php echo $prod['types']; ?></td>
							<td class="black"><?php echo $prod['unite']; ?></td>
							<td class="black"><?php echo fnbr($prod['ventes']); $ventestot += fnbr($prod['ventes']); ?></td>
							<td class="black"><?php echo fnbr($prod['degustation']); $degustationtot += fnbr($prod['degustation']); ?></td>
							<td class="black"><?php if ($prod['produitno'] == 'no') { echo 'X'; } ?></td>
							<?php if ($_POST['note'] == 1) { ?>
								<td class="black"><?php echo $row['peoplenote']; ?></td>
							<?php } ?>
						</tr>
						<?php if (($ip == $FoundCount) and ($_POST['tot'] == 1)) { ?>
							<tr>
								<td class="line"></td>
								<td class="line"></td>
								<td class="line"></td>
								<td class="line"></td>
								<td class="line"></td>
								<td class="line"><b><?php echo fnbr($ventestot); $ventestottot += $ventestot;?></b></td>
								<td class="line"><b><?php echo fnbr($degustationtot); $degustationtottot += $degustationtot;?></b></td>
							</tr>
						<?php 
						}
					}
				}
			}
		?>
			<?php if (($ip == $FoundCount) and ($_POST['tot'] == 1)) { ?>
				<tr>
					<td class="line"></td>
					<td class="line"></td>
					<td class="line"></td>
					<td class="line"></td>
					<td class="line"></td>
					<td class="line"><b><?php echo fnbr($ventestottot);?></b></td>
					<td class="line"><b><?php echo fnbr($degustationtottot);?></b></td>
				</tr>
			<?php } ?>
		<?php if ($_POST['titre'] == 1) { ?>
			<tr class="<?php echo $classe; ?>">
				<th class="vip2">Date</th>
				<th class="vip2">Place</th>
				<th class="vip2">Name</th>
				<th class="vip2">Product</th>
				<th class="vip2">Units</th>
				<th class="vip2">Quantity sold</th>
				<th class="vip2">degustation</th>
				<th class="vip2">Out of Stock</th>
			</tr>
		<?php } ?>
		</table>
<?php if ($_POST['pdf'] == 1) {
	### POUR PDF
		$jobquid .= $jobquid1.') ';
	#/ ## POUR PDF

	$_GET['web'] = 'web';
	echo '<br>';
	echo '<div align="center">
				SALES REPPORT - PDF PRINTABLE FORMAT<br>
		<table class="<?php echo $classe; ?>" border="0" cellspacing="1" cellpadding="4" align="center" width="90%">
			<tr class="<?php echo $classe; ?>">
				<td align="center"width="40%"></td>
				<td align="center">
	';

	include NIVO.'print/anim/rapportc/rapportc.php';
	echo '
				</td>
				<td align="center"width="40%"></td>
			</tr>
		</table>
	</div>';
	echo '<br>';
 } ?>
</div>

<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
