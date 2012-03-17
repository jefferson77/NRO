<?php
define('NIVO', '../');

# Entete de page
include NIVO."includes/ifentete.php" ;

if ($_GET['s'] ==  0) {$l0  = '#FFFFFF';} else {$l0  = '#000000';}
if ($_GET['s'] ==  1) {$l1  = '#FFFFFF';} else {$l1  = '#000000';}
if ($_GET['s'] ==  2) {$l2  = '#FFFFFF';} else {$l2  = '#000000';}
if ($_GET['s'] ==  3) {$l3  = '#FFFFFF';} else {$l3  = '#000000';}
if ($_GET['s'] ==  4) {$l4  = '#FFFFFF';} else {$l4  = '#000000';}
if ($_GET['s'] ==  5) {$l5  = '#FFFFFF';} else {$l5  = '#000000';}
if ($_GET['s'] ==  6) {$l6  = '#FF6633';} else {$l6  = '#000000';}
if ($_GET['s'] ==  7) {$l7  = '#FF6633';} else {$l7  = '#000000';}
if ($_GET['s'] ==  8) {$l8  = '#FFFFFF';} else {$l8  = '#000000';}
if ($_GET['s'] ==  9) {$l9  = '#FFFFFF';} else {$l9  = '#000000';}
if ($_GET['s'] == 10) {$l10 = '#FFFFFF';} else {$l10 = '#000000';}
if ($_GET['s'] == 11) {$l11 = '#FFFFFF';} else {$l11 = '#000000';}
if ($_GET['s'] == 12) {$l12 = '#FFFFFF';} else {$l12 = '#000000';}
if ($_GET['s'] == 13) {$l13 = '#FFFFFF';} else {$l13 = '#000000';}

### COMPTEUR POUR LES ANNEXES dans menu up
	$fichier = 0;
	$path = NIVO.'../media/annexe/vip/';
	$ledir = $path;
	$d = opendir ($ledir);
	$nomvip = 'vip-'.$_GET['idvipjob'].'-';
	while ($name = readdir($d)) {
		if (($name != '.') and ($name != '..') and ($name != 'index.php') and ($name != 'index2.php') and ($name != 'temp') and (strchr($name, $nomvip))) {
			$fichier++;
		}
	}
	closedir ($d);
        
###/ compte des Bons de Commande 
	$numsbdc = $DB->getOne("SELECT COUNT(*) FROM `bondecommande` WHERE `idjob` = ".$_GET['idvipjob']." and etat = 'in'");
	$numsbdl = $DB->getOne("SELECT COUNT(*) FROM `bonlivraison` WHERE `idjob` = ".$_GET['idvipjob']." and etat = 'in'");
	

?>
<style>
    table.standard {
        border-color: #FFF;
        border-width: 1px;
        background-color: #000;
    }

    th.standard {
        font-size: 10px;
        padding: 2px;
        margin: 0px;
        background-color: #BBB;
    }

    th.standard a {
        text-decoration: none;
        display: block;

        }

    th.standard:hover {
        background-color: #666;
        }
</style>


				<table class="standard" cellspacing="1" align="center" width="100%">
					<tr>
						<th class="standard" align="center">
						<?php echo '<a href="onglet.php?idvipjob='.$_GET['idvipjob'].'&s=0" target="detail-main"><font color="'.$l0.'">Cr&eacute;a Missions</font></a>'; ?>
						</th>
						<th class="standard" align="center">
						<?php echo '<a href="onglet.php?idvipjob='.$_GET['idvipjob'].'&s=1" target="detail-main"><font color="'.$l1.'">'.$_GET['titre'].'</font></a>'; ?>
						</th>
						<?php if ($_GET['menufac'] == 1) { ?>
							<th class="standard" align="center">
								<?php echo '<a href="onglet.php?idvipjob='.$_GET['idvipjob'].'&s=5&act=jobfactoring" target="detail-main"><font color="'.$l5.'">Factoring</font></a>'; ?>
							</th>
						<?php } ?>
						<th class="standard" align="center">
							<?php
							echo '<a href="onglet.php?idvipjob='.$_GET['idvipjob'].'&s=9" target="detail-main"><font color="'.$l9.'">De-booking</font></a>';
							if ($_GET['FoundCountmatosvip'] > 0) {
								echo ' <img src="'.STATIK.'illus/taille-t-shirt-m-rouge.gif" alt="Supplier" width="16" height="14" border="0">';
							}
							?>
						</th>
						<th class="standard" align="center">
							<?php echo '<a href="onglet.php?idvipjob='.$_GET['idvipjob'].'&s=3" target="detail-main"><font color="'.$l3.'">Contacts</font></a>'; ?>
						</th>
						<th class="standard" align="center">
							<?php echo '<a href="onglet.php?idvipjob='.$_GET['idvipjob'].'&s=2" target="detail-main"><font color="'.$l2.'">Casting ('.$_GET['castingcount'].')</font></a>'; ?>
						</th>
						<th class="standard" align="center">
							<?php echo '<a href="onglet.php?idvipjob='.$_GET['idvipjob'].'&s=4" target="detail-main"><font color="'.$l4.'">Annexes ('.$fichier.')</font></a>'; ?>
						</th>
						<th class="standard" align="center">
							<?php echo '<a href="onglet.php?idvipjob='.$_GET['idvipjob'].'&s=11&secteur=VI" target="detail-main"><font color="'.$l11.'">Notes de Frais</font></a>'; ?>
						</th>
						<th class="standard" align="center">
							<?php echo '<a href="onglet.php?idvipjob='.$_GET['idvipjob'].'&s=6" target="detail-main"><font color="'.$l6.'">Mat&eacute;riel ('.$_GET['stockcount'].')</font></a>'; ?>
						</th>
						<th class="standard" align="center">
							<?php echo '<a href="onglet.php?idvipjob='.$_GET['idvipjob'].'&s=10&act=moulinetteupdate1" target="detail-main"><font color="'.$l10.'">Update M</font></a>'; ?>
						</th>
						<th class="standard" align="center">
							<?php echo '<a href="onglet.php?idvipjob='.$_GET['idvipjob'].'&s=12&act=list&secteur=VI" target="detail-main"><font color="'.$l12.'">Bon de commande <span id="nbrbdc">('.$numsbdc.')</span></font></a>'; ?>
						</th>
                  		<th class="standard" align="center">
							<?php echo '<a href="onglet.php?idvipjob='.$_GET['idvipjob'].'&s=14&secteur=VI" target="detail-main"><font color="'.$l12.'">Mail all</font></a>'; ?>
						</th> 
					</tr>
				</table>

<?php
# Pied de Page
include NIVO."includes/ifpied.php" ;
?>
</div>