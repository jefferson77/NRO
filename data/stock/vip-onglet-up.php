<?php
# Entete de page
define('NIVO', '../../'); 
$css = standard;
include NIVO."includes/ifentete.php" ;

$idvipjob = $_GET['idvipjob'];

if ($_GET['s'] == "7") {$l7 = '#FFFFFF';}
if ($_GET['s'] == "8") {$l8 = '#FFFFFF';}
?>

				<table class="standard" border="1" cellspacing="1" cellpadding="0" align="center" width="100%" height="25">
					<tr height="25">
						<th class="standard" align="center" width="14%">
						<?php echo '<a href="vip-onglet.php?idvipjob='.$idvipjob.'&s=0" target="detail-main"><font color="'.$l0.'">Cr&eacute;a Missions</font></a>'; ?>
						</th>
						<th class="standard" align="center" width="14%">
						<?php echo '<a href="vip-onglet.php?idvipjob='.$idvipjob.'&s=1" target="detail-main"><font color="'.$l1.'">Mission</font></a>'; ?>
						</th>
						<th class="standard" align="center" width="14%">
							<?php echo '<a href="vip-onglet.php?idvipjob='.$idvipjob.'&s=3" target="detail-main"><font color="'.$l3.'">Contacts</font></a>'; ?>
						</th>
						<th class="standard" align="center" width="14%">
							<?php echo '<a href="vip-onglet.php?idvipjob='.$idvipjob.'&s=2" target="detail-main"><font color="'.$l2.'">Casting ('.$_GET['castingcount'].')</font></a>'; ?>
						</th>
						<th class="standard" align="center" width="14%">
							<?php echo '<a href="vip-onglet.php?idvipjob='.$idvipjob.'&s=4" target="detail-main"><font color="'.$l4.'">Annexes</font></a>'; ?>
						</th>
						<th class="standard" align="center" width="14%">
							<?php echo '<a href="vip-onglet.php?idvipjob='.$idvipjob.'&s=7" target="detail-main"><font color="'.$l7.'">Mod&egrave;les</font></a>'; ?>
						</th>
						<th class="standard" align="center">
							<?php echo '<a href="vip-onglet.php?idvipjob='.$idvipjob.'&s=8" target="detail-main"><font color="'.$l8.'">Tickets</font></a>'; ?>
						</th>
					</tr>
				</table>

<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>