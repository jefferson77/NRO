<?php
## administration des bons de commande : ajout/suppression/modif/affichage simple/multiple
define('NIVO', '../../');
if($_REQUEST['all']==1){
include NIVO."includes/entete.php" ;
}
else
{
    include NIVO."includes/ifentete.php" ;
}

$DB = new db();
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
				$idjob = $_REQUEST['idanimation'];
		    break;
        case "ME":
                $idjob = $_REQUEST['idmerch'];
            break;
    }
}

switch($_REQUEST['act'])
{
    case "add" :
        $DB->inline("INSERT INTO `bonlivraison` (`idjob` , `idagent` ,`secteur` ,`date`,`idsupplier`, `fdate`,`tdate`, `factureclient`,`valide`)
			VALUES ('".$_REQUEST['idjob']."','".$_SESSION['idagent']."', '".$_REQUEST['secteur']."', NOW(), 91, NOW(), NOW(), 25,'Y');");
        $id = $DB->addid;
        include "v-detail.php";
        
        break;
    case "del" :
        $DB->inline("DELETE FROM bonlivraison WHERE id = ".$_REQUEST['id']." LIMIT 1");
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
		$DB->MAJ("bonlivraison");
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
			include("v-list.php");
		}
	break;
	case "valide":
		$DB->inline("UPDATE bonlivraison SET date = DATE(NOW()), fdate = DATE(NOW()), tdate = DATE(NOW()), valide='Y' WHERE id = ".$_REQUEST['id']);
		include "v-list.php";
	break;
    case "show":
        $id = $_REQUEST['id'];
		include "v-detail.php";
	break;
    case 'list':
		include "v-list.php";
	break;
    case 'listall':
            include "v-listall.php";
        break;
}
if($_REQUEST['all']==1)
{
    include NIVO."includes/pied.php";
}
else
{
    include NIVO."includes/ifpied.php";
}
?>