<?php
define('NIVO', '../../../');

# Entete de page
$Style = 'anim';
include NIVO."includes/ifentete.php" ;
# Classes utilisées
include NIVO.'classes/photo.php';

echo '<hr width="100%"><h2>casting</h2>';
if (!empty($_GET['casting'])) {
	$casting = $_GET['casting'];
	if (!empty($_GET['idvipjob']))
	{
### début casting de Global
	# Path PDF
	$pathpdf = 'document/temp/people/casting/';
	$nompdf = 'casting-'.$_GET['idvipjob'].'-'.date("Ymd").'.pdf';

	$pdf = pdf_new();
	pdf_open_file($pdf, Conf::read('Env.root').$pathpdf.$nompdf); # définit l'emplacement de la sauvegarde

	# Infos pour le document
	pdf_set_info($pdf, "Author", "neuro");
	pdf_set_info($pdf, "Title", "Contrat Exception");
	pdf_set_info($pdf, "Creator", "NEURO");
	pdf_set_info($pdf, "Subject", "casting");

	######## Variables de taille  ###############
	$LargeurPage = 595; # Largeur A4
	$HauteurPage = 842; # Hauteur A4
	$MargeLeft = 30;
	$MargeRight = 30;
	$MargeTop = 30;
	$MargeBottom = 30;

	$np = 1; # Numéro de la première page
	######## Variables de taille  ###############
	$LargeurUtile = $LargeurPage - $MargeRight - $MargeLeft;
	$HauteurUtile = $HauteurPage - $MargeTop - $MargeBottom;

	### SOrtie des contrats

	### recherche people si casting != ''
	if (!empty($_GET['casting'])) {
		if (!empty($_GET['idvipjob']))
		{
			$idvipjob = $_GET['idvipjob'];
			$detailvip = new db();
			$detailvip->inline("SELECT * FROM `vipjob` WHERE `idvipjob` = $idvipjob");
			$rowvip = mysql_fetch_array($detailvip->result);
			$reference = $rowvip['reference'].' ('.$rowvip['idvipjob'].')';

			$idclient = $rowvip['idclient'];

			$detailclient = new db();
			$detailclient->inline("SELECT * FROM `client` WHERE `idclient` = $idclient");
			$rowclient = mysql_fetch_array($detailclient->result);

			$idclient2 = $rowclient['societe'].' ('.$rowvip['idclient'].')';

		}
		## Formater une sequence de casting en explode
		$explo = explode("-", $casting);
			foreach ($explo as $x)
			{
			$quid = $x;
			include 'casting2.php';
			}

	}
	###/ recherche people si casting != ''


	pdf_end_document($pdf, '');
	pdf_delete($pdf); # Efface le fichier en m?moire
	# Lien vers le PDF
	?>
	<div align="center">
		<A href="<?php echo NIVO.$pathpdf.$nompdf ;?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"> Print the Global Casting  : <?php echo $reference; ?></A><br><br>
	</div>
<?php
###/ début casting de Global
	}
?>
		<table border="0" cellspacing="1" cellpadding="0" width="98%">
		<tr><td width="40"></td>

<?php
###  casting pour UNE fiche PEOPLE
	## Formater une sequence de casting en explode
	$explo = explode("-", $casting);
	foreach ($explo as $x)
	{
		echo '<td>';
		$i++;
		$quid = $x;
		# Path PDF
		$pathpdf = 'document/temp/people/casting/';
		$nompdf = 'casting-'.$_GET['idvipjob'].'-'.$x.'-'.date("Ymd").'.pdf';

		$pdf = pdf_new();
		pdf_open_file($pdf, Conf::read('Env.root').$pathpdf.$nompdf); # définit l'emplacement de la sauvegarde

		# Infos pour le document
		pdf_set_info($pdf, "Author", "neuro");
		pdf_set_info($pdf, "Title", "Contrat Exception");
		pdf_set_info($pdf, "Creator", "NEURO");
		pdf_set_info($pdf, "Subject", "casting");

		######## Variables de taille  ###############
		$LargeurPage = 595; # Largeur A4
		$HauteurPage = 842; # Hauteur A4
		$MargeLeft = 30;
		$MargeRight = 30;
		$MargeTop = 30;
		$MargeBottom = 30;

		$np = 1; # Numéro de la première page
		######## Variables de taille  ###############
		$LargeurUtile = $LargeurPage - $MargeRight - $MargeLeft;
		$HauteurUtile = $HauteurPage - $MargeTop - $MargeBottom;

		### SOrtie des contrats
		include 'casting2.php';

		pdf_end_document($pdf, '');
		pdf_delete($pdf); # Efface le fichier en m?moire
		# Lien vers le PDF
		?>
			<A href="<?php echo NIVO.$pathpdf.$nompdf ;?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"> <?php echo utf8_encode($row['pprenom']." ".$row['pnom']); ?></A>
		</td>
<?php
		if ($i == 2) {echo '</tr><tr><td width="40"></td>'; $i=0; }

	}
?>
		</td>
	</tr>
</table>
<?php
}
###/ casting pour UNE fiche PEOPLE
	echo '
		<hr width="100%">
		<div align="center">
		<br><br><a href="javascript:window.close();"><b>&gt; Exit &lt;</b></a><br><br>
		</div>
	';


# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
