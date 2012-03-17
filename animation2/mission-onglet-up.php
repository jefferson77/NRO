<?php
# Entete de page
define('NIVO', '../');
$css = 'standard';
include NIVO."includes/ifentete.php" ;

$idanimation = $_GET['idanimation'];
$disable = $_GET['disable'];

if ($_GET['s'] == 0) {$l0 = '#FFFFFF';}
if ($_GET['s'] == 1) {$l1 = '#FFFFFF';}
if ($_GET['s'] == 2) {$l2 = '#FFFFFF';}
if ($_GET['s'] == 3) {$l3 = '#FFFFFF';}
if ($_GET['s'] == 4) {$l4 = '#FFFFFF';}
if ($_GET['s'] == 5) {$l5 = '#FF6633';}
if ($_GET['s'] == 6) {$l6 = '#FF6633';}
if ($_GET['s'] == 7) {$l7 = '#FF6633';}
if ($_GET['s'] == 9) {$l7 = '#FF6633';}


$result = $DB->getOne("SELECT idpeople FROM animation WHERE idanimation=".$idanimation);
?>
	<table class="standard" border="0" cellspacing="0" cellpadding="0" align="center" width="100%" height="25">
		<tr height="25">
			<td class="navbarleft"></td>
			<td class="navbar" align="center">
				<?php echo '<a href="mission-onglet.php?idanimation='.$idanimation.'&s=0&disable='.$disable.'" target="detail-main"><font color="'.$l0.'">Produits</font></a>'; ?>
			</td>
			<td class="navbarmid"></td>
			<td class="navbar" align="center">
				<?php echo '<a href="mission-onglet.php?idanimation='.$idanimation.'&s=1&disable='.$disable.'" target="detail-main"><font color="'.$l1.'">Frais client</font></a>'; ?>
			</td>
			<td class="navbarmid"></td>
			<td class="navbar" align="center">
				<?php echo '<a href="mission-onglet.php?idanimation='.$idanimation.'&s=2&disable='.$disable.'&idpeople='.$result.'" target="detail-main"><font color="'.$l2.'"><span id="nbrmatos">Matos</span></font></a>'; ?>
			</td>
			<td class="navbarmid"></td>
			<td class="navbar" align="center">
				<?php echo '<a href="mission-onglet.php?idanimation='.$idanimation.'&s=3&disable='.$disable.'" target="detail-main"><font color="'.$l3.'">Notes terrain</font></a>'; ?>
			</td>
			<td class="navbarmid"></td>
			<td class="navbar" align="center">
				<?php echo '<a href="mission-onglet.php?idanimation='.$idanimation.'&s=4&disable='.$disable.'" target="detail-main"><font color="'.$l4.'">Contact</font></a>'; ?>
			</td>
			<td class="navbarmid"></td>
			<td class="navbar" align="center">
				<?php
				$nbr = $DB->getOne("SELECT COUNT(id) FROM bonlivraison WHERE idjob = ".$idanimation." AND etat = 'in'");			
				echo '<a href="mission-onglet.php?idanimation='.$idanimation.'&s=9&secteur=AN&act=list" target="detail-main"><font color="'.$l7.'">Bons de livraison<span id="nbrbdl">('.$nbr.')</span></font></a>'; ?>
			</td>
			<td class="navbarright"></td>
		</tr>
	</table>
<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
</div>