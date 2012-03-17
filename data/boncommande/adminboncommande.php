<?php
/*
	TODO : enlever liv/int/ext etc. plus utilisés
*/
## administration des bons de commande : ajout/suppression/modif/affichage simple/multiple
define('NIVO', '../../');
if($_REQUEST['all']==1){
include NIVO."includes/entete.php" ;
}
else
{
    include NIVO."includes/ifentete.php" ;
}

if(isset($_GET['retour']))
{
    $idjob=$_REQUEST['idjob'];
}
else
{
    switch($_REQUEST['secteur'])
    {
        case "VI":
                $idjob = $_REQUEST['idvipjob'];
            break;
        case "AN":
                $idjob = $_REQUEST['idanimjob'];
            break;
        case "ME":
                $idjob = $_REQUEST['idmerch'];
            break;
    }
}

switch($_REQUEST['act'])
{
    case "add" :
        $DB->inline("INSERT INTO `bondecommande` (`idjob` , `idagent` ,`secteur` ,`date`,`type`)
			VALUES ('".$_REQUEST['idjob']."','".$_SESSION['idagent']."', '".$_REQUEST['secteur']."', NOW(),'".$_REQUEST['liv']."');");
        $id = $DB->addid;
		$idjob = $_REQUEST['idjob'];
        include "v-detail.php";
        
        break;
    case "del" :
        $_REQUEST['idvipjob'] = $DB->getOne("SELECT idjob FROM bondecommande WHERE id LIKE ".$_GET['id']);
        $DB->inline("UPDATE bondecommande SET etat = 'out' WHERE id LIKE ".$_GET['id']);
		$DB->inline("UPDATE bonlivraison SET nbdc='0' WHERE nbdc LIKE ".$_GET['id']);
        if($_REQUEST['all']==1)
        {
            include "v-listall.php";
        }
        else
        {
        	include "v-list.php";
        }
        break;
    case "modif" :
		$DB->MAJ("bondecommande");
		$idjob = $_POST['idjob'];
		if($_REQUEST['from']=="menu" or $_REQUEST['all']==1)
		{
			$_REQUEST['secteur']="";
			$_REQUEST['idjob']="";
			$_POST['all']=1;
			$_POST['from']="menu";
			$_POST['period']=date("n-Y");
			
			include "v-listall.php";		
		}
		else 
		{
			$idjob = $_POST['idjob'];
			//ici perte du secteur
			include("v-list.php");
		}
	break;
    case "show":
		$idjob = $_REQUEST['idjob'];
        $id=$_REQUEST['id'];
		include "v-detail.php";
	break;
    case 'list':
		include "v-list.php";
	break;
    case 'listall':
            include "v-listall.php";
        break;
}
if($_REQUEST['all']==1){
    include NIVO."includes/pied.php";
}
else
{
    include NIVO."includes/ifpied.php";
}
?>