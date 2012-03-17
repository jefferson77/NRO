<?php
define('NIVO', '../');

# Entete de page
$Titre = 'Groupe-S';
$PhraseBas = 'Erreurs People';
include NIVO."includes/entete.php" ;
?>
<div id="leftmenu"></div>
<div id="infozone">
<h1>Tableau des r&eacute;sultats</h1>
<table class="blue" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
<?php
include "fichiers/rapporterreurs.html" ;
?>
</table>
</div>
<div id="infobouton">
</div>
<?php
# Pied de page
include NIVO."includes/pied.php" ; 
?>