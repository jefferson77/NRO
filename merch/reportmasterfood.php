<?php
define('NIVO', '../'); 

# Classes utilisées
include_once(NIVO."classes/merch.php");

# Entete de page
$Titre = 'Rapport';
$PhraseBas = 'G&eacute;n&eacute;ration du rapport Mensuel MasterFood';
include NIVO."includes/entete.php" ;

$classe = "vip";
?>
<div id="leftmenu"></div>
<div id="infozone">
<?php
##########################################################################################################################################################################
# >> ################# Formulaire de choix période #####
if (!is_array($_POST['mois'])) {

for ($i=0 ; $i < 20 ; $i++) {
	$mois[$i] = date("Y-m", mktime(0,0,0,date("m") - $i,date("d") - 25,date("Y")));
}
?>
<h1>Rapport Mensuel MasterFood</h1>
Cochez les mois pour lesquel vous voulez obtenir les rapports mensuels MasterFood

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">

<table border="0" align="center">
<?php 
setlocale(LC_TIME, 'fr_FR');

$chk = 'checked';

foreach ($mois as $mo) {
	echo '<tr><td><input type="checkbox" name="mois[]" value="'.$mo.'" '.$chk.'> '.ucfirst(utf8_decode(strftime("%B %Y", strtotime($mo."-01")))).'</td><tr>';
	$chk = '';
}
?>
</table>
		
<div align="center">
</div>
</div>
<div id="infobouton">
	<input type="submit" value="Stocker">
</div>

</form>

<?php
# << ################## Fin Formulaire de choix période ####
##########################################################################################################################################################################
 } else {
##########################################################################################################################################################################
# >> ################# Rassemblement des Datas #####
?> 
    <div align="center">
<?php
    ## Classes
    require_once NIVO."classes/xls/class.writeexcel_workbook.inc.php";
    require_once NIVO."classes/xls/class.writeexcel_worksheet.inc.php";
    
#######################################################################
# fichier
    
    $pathxls = Conf::read('Env.root')."media/rmasterfood/";
    $fname = "MensMasterF-".date("Ymd").".xls";
    $workbook = &new writeexcel_workbook($pathxls.$fname);

    setlocale(LC_TIME, 'fr_FR');
    
    $gdata = new db();
    
    foreach ($_POST['mois'] as $mois) {

        $datein = date("Y-m-01", strtotime($mois."-01"));
        $dateout = date("Y-m-t", strtotime($mois."-01"));

    #######################################################################
    # Sheets
    
        $recaptype =& $workbook->addworksheet($mois);
        
    #######################################################################
    # Styles
    
        # datas
        $datas =& $workbook->addformat();
        $datas->set_align('left');
        $datas->set_align('vcenter');
        $datas->set_size(10);
        $datas->set_text_wrap();

        # entete
        $entete =& $workbook->addformat();
        $entete->set_align('center');
        $entete->set_align('vcenter');
        $entete->set_size(10);
        $entete->set_bold();
        $entete->set_color('white');
        $entete->set_fg_color('black');
        $entete->set_text_wrap();
        
        # Largeur des colonnes
        $recaptype->set_column('A:A', 42);
        $recaptype->set_column('B:B', 21);
        $recaptype->set_column('C:C', 24);
        $recaptype->set_column('D:D', 10);
        $recaptype->set_column('E:E', 5);
        $recaptype->set_column('F:F', 5);
        $recaptype->set_column('G:G', 5);
        $recaptype->set_column('H:H', 67);
        $recaptype->set_column('I:I', 7);
        $recaptype->set_column('J:J', 5);
        $recaptype->set_column('K:K', 5);

   #######################################################################
    # Titres
    
           	$recaptype->write('A1', "POS", $entete); # POS
           	$recaptype->write('B1', "Category", $entete); # Category
           	$recaptype->write('C1', "Merchandiser", $entete); # Merchandiser
           	$recaptype->write('D1', "Datum", $entete); # datem
           	$recaptype->write('E1', "Week", $entete); # Semaine
           	$recaptype->write('F1', "Jaar", $entete); # Year
           	$recaptype->write('G1', "", $entete); # Heures
           	$recaptype->write('H1', "Jobomschr", $entete); # produit
           	$recaptype->write('I1', "Mission", $entete); # N° mission
           	$recaptype->write('J1', "Uuren", $entete); # Heures réelles
           	$recaptype->write('K1', "Bedrag", $entete); # Heures réelles
            
    #######################################################################
    # Get datas
    	$gdata->inline("SET NAMES latin1");
        $gdata->inline("
			SELECT
				me.idmerch, me.datem, me.weekm, YEAR(me.datem) as yearm, me.produit, me.kmfacture, 
				s.ville, s.societe,
				o.departement,
				p.pnom, p.pprenom,
				c.tmkm, c.tmheure
			
			FROM merch me
				LEFT JOIN shop s ON me.idshop = s.idshop
				LEFT JOIN client c ON me.idclient = c.idclient
				LEFT JOIN people p ON me.idpeople = p.idpeople
				LEFT JOIN cofficer o ON me.idcofficer = o.idcofficer
			
			WHERE me.idclient = 671 AND me.datem BETWEEN '$datein' AND '$dateout'
			ORDER BY me.datem, s.societe");
        
        $i = 2;
        
        while ($row = mysql_fetch_array($gdata->result)) {
            
            $m = new coremerch($row['idmerch']);
            
           	$recaptype->write('A'.$i, $row['societe'].' - '.$row['ville'], $datas); # POS
           	$recaptype->write('B'.$i, $row['departement'], $datas); # Category
           	$recaptype->write('C'.$i, $row['pnom'].' '.$row['pprenom'], $datas); # Merchandiser
           	$recaptype->write('D'.$i, fdate($row['datem']), $datas); # date
           	$recaptype->write('E'.$i, $row['weekm'], $datas); # Semaine
           	$recaptype->write('F'.$i, $row['yearm'], $datas); # Year
           	$recaptype->write('G'.$i, "", $datas); # Heures
           	$recaptype->write('H'.$i, $row['produit'], $datas); # produit
           	$recaptype->write('I'.$i, $row['idmerch'], $datas); # N° mission
           	$recaptype->write('J'.$i, $m->hprest, $datas); # Heures réelles
           	$recaptype->write('K'.$i, ($m->hprest * $row['tmheure']) + ($row['kmfacture'] * $row['tmkm']), $datas); # Montant
            
            $i++;
        }
    }

    $workbook->close();
?>
    <br>
    <img src="<?php echo STATIK ?>illus/excel.png" alt="print" width="16" height="16" border="0">
    <a href="<?php echo "/rmasterfood/".$fname ;?>" target="_blank">Rapport Mensuel MasterFood</a>
    <br>
    </div>
    </div>
<?php
}

# Pied de Page
include NIVO."includes/pied.php" ; 

?>