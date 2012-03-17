<?php
define('NIVO', '../../');
$Style = 'anim';
### langue
if ($_GET['lang'] == '') {$_GET['lang'] = 'fr';}
$pagevar = NIVO.'webclient/var'.$_GET['lang'].'.php';
include $pagevar;

include NIVO."webclient/includes/ifentete.php" ;

$onemonthago  = date ("Y-m-d", strtotime("-1 month"));
$classe = "planning" ;
?>
	<table border="0" cellspacing="1" cellpadding="2" align="center" width="98%" bgcolor="#FFFFFF">
		<?php if ($_POST['titre'] == 1) { ?>
			<tr class="<?php echo $classe; ?>">
				<td class="etiq3"><?php echo $anim_sales_11; ?></td>
				<td class="etiq3"><?php echo $anim_sales_12; ?></td>
				<td class="etiq3"><?php echo $anim_sales_13; ?></td>
				<td class="etiq3"><?php echo $anim_sales_14; ?></td>
				<td class="etiq3"><?php echo $anim_sales_15; ?></td>
				<td class="etiq3"><?php echo $anim_sales_16; ?></td>
				<td class="etiq3"><?php echo $anim_sales_17; ?></td>
				<td class="etiq3"><?php echo $anim_sales_18; ?></td>
			</tr>
		<?php } ?>
		<?php
			if ($sort == '') {$sort = 'an.datem DESC';}

		### Recherche du jour à partir duquel on affiche les missions
				$date = weekdate(date("W") - (((date("D") == 'Mon') and (date("H") < 8))?'1':'0'), date("Y"));
				$jourencours = $date['lun'];

			$quid = "c.idclient = '".$_SESSION['idclient']."'";
			$quid .= ' AND an.datem < "'.$jourencours.'"';

			if (!empty($_POST['idanimjob'])) {
				if (!empty($quid)) { $quid .= " AND "; }
				$quid .= "an.idanimjob = '".$_POST['idanimjob']."'";
			}

				if (!empty($quid)) { $quid .= " AND "; }
				$quid .= "an.datem > '".date ("Y-m-d", strtotime("-1 year"))."'";

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
				an.idanimation, an.idanimjob, an.datem, an.weekm, an.reference, an.genre, an.idpeople,
				an.hin1, an.hout1, an.hin2, an.hout2, an.kmpaye, an.kmfacture, an.frais, an.fraisfacture,
				an.produit, an.facturation, an.peoplenote,
				an.ferie,
				a.prenom, a.idagent,
				c.codeclient, c.societe AS clsociete, c.idclient, c.tel, c.fax,
				co.idcofficer, co.qualite, co.onom, co.oprenom, co.fax AS cofax,
				s.codeshop, s.societe AS ssociete, s.ville AS sville,
				p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.gsm, p.codepeople,
				ma.idanimmateriel, ma.stand, ma.gobelet, ma.serviette, ma.four, ma.curedent, ma.cuillere, ma.rechaud, ma.autre
				FROM animation an
				LEFT JOIN agent a ON j.idagent = a.idagent
				LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
				LEFT JOIN client c ON j.idclient = c.idclient
				LEFT JOIN cofficer co ON j.idcofficer = co.idcofficer
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
							echo '<tr bgcolor="#eeeeee">';
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
							<tr bgcolor="#ffffff">
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td class="contenu"><b><?php echo fnbr($ventestot); $ventestottot += $ventestot;?></b></td>
								<td class="contenu"><b><?php echo fnbr($degustationtot); $degustationtottot += $degustationtot;?></b></td>
							</tr>
						<?php
						}
					}
				}
			}
		?>
			<?php if (($ip == $FoundCount) and ($_POST['tot'] == 1)) { ?>
				<tr>
					<td class="contenu"></td>
					<td class="contenu"></td>
					<td class="contenu"></td>
					<td class="contenu"></td>
					<td class="contenu"></td>
					<td class="contenu"><b><?php echo fnbr($ventestottot);?></b></td>
					<td class="contenu"><b><?php echo fnbr($degustationtottot);?></b></td>
					<td class="contenu"></td>
				</tr>
			<?php } ?>
		<?php if ($_POST['titre'] == 1) { ?>
			<tr class="<?php echo $classe; ?>">
				<td class="etiq3"><?php echo $anim_sales_11; ?></td>
				<td class="etiq3"><?php echo $anim_sales_12; ?></td>
				<td class="etiq3"><?php echo $anim_sales_13; ?></td>
				<td class="etiq3"><?php echo $anim_sales_14; ?></td>
				<td class="etiq3"><?php echo $anim_sales_15; ?></td>
				<td class="etiq3"><?php echo $anim_sales_16; ?></td>
				<td class="etiq3"><?php echo $anim_sales_17; ?></td>
				<td class="etiq3"><?php echo $anim_sales_18; ?></td>
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
				'.$anim_sales_00.' - PDF PRINTABLE FORMAT<br>
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

<?php
# Pied de Page
include NIVO."webclient/includes/ifpied.php" ;
?>
