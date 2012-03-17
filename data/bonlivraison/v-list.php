<?php
include(NIVO."print/commun/bonlivraison-detail.php");
//b.date, b.factureclient, b.id, b.idjob, b.idsupplier, b.montant, b.secteur, b.titre, 
$DB->inline("SELECT 
				b.*,
				s.societe
			FROM bonlivraison b
				LEFT JOIN supplier s ON b.idsupplier = s.idsupplier
			WHERE b.idjob = ".$idjob." AND b.etat LIKE 'in';");
				
$nbr = mysql_num_rows($DB->result);

switch($_REQUEST['secteur'])
{
	case "VI":
		$pvip = $DB->getOne("SELECT idpeople FROM vipmission WHERE idvip = ".$idjob);
		echo $pvip;
	break;
	case "AN":
		$panim = $DB->getOne("SELECT idpeople FROM animation WHERE idanimation = ".$idjob);
		echo $panim;
	break;
	case "ME":
		$pmerch = $DB->getOne("SELECT idpeople FROM merch WHERE idmerch = ".$idjob);
		echo $pmerch;
	break;
}

if($nbr == 0 && $_REQUEST['retour']!=true)
{
	if(!empty($pvip) or !empty($panim) or !empty($pmerch))
	{
	/* génération auto de bons de livraison standard : 
	
	- Exception -> People
	- People -> Exception
	- Exception -> Client
	*/
	$vip = array();
	$anim = array();
	$merch = array();
	switch($_REQUEST['secteur'])
	{
		case "VI":
			$vip = $DB->getArray("SELECT v.idpeople, vj.idclient FROM vipmission v  LEFT JOIN vipjob vj ON v.idvipjob = vj.idvipjob WHERE v.idvip = ".$idjob);
		break;
		case "AN":
			$anim = $DB->getArray("SELECT a.idpeople, aj.idclient FROM animation a LEFT JOIN animjob aj ON aj.idanimjob = a.idanimjob WHERE idanimation = ".$idjob);
		break;
		case "ME":
			$merch = $DB->getArray("SELECT m.idpeople, m.idclient FROM merch m WHERE idmerch = ".$idjob);
		break;
	}
	
	$ids = $vip + $anim + $merch;
			
	$infopeople = $DB->getArray("SELECT idpeople as id, CONCAT(pnom,' ',pprenom) as nom, gsm as gsm,
		IF(peoplehome=1, CONCAT(adresse1, ' ', num1, '/', bte1), CONCAT(adresse2, ' ', num2, '/', bte2)) as adr, 
		IF(peoplehome=1, cp1, cp2) as cp, 
		IF(peoplehome=1, ville1, ville2) as ville,
		'people' as type
		from people 
		where idpeople = ".$ids[0]['idpeople']);
			
	$infoclient = $DB->getArray("SELECT idclient as id, societe as nom, adresse as adr, cp as cp, ville as ville, 'client' as type
	from client
	where idclient = ".$ids[0]['idclient']);
		
	$infoexception = $DB->getArray("SELECT idclient as id, societe as nom, adresse as adr, cp as cp, ville as ville, 'exception' as type
	from client
	where idclient = 1038");
		
		$gsmagent = $DB->getOne("SELECT agsm FROM agent WHERE idagent=".$_SESSION['idagent']);
		$datetomorrow = date("Y-m-d",strtotime("+1 days"));
	//Insertion du bon People->Exception
	$DB->inline("INSERT INTO `neuro`.`bonlivraison` 
		(`idagent`,`fdate`,`secteur`,`idjob`,
		`fid`,`ftype`,`fnom`,`fadr`,
		`fcp`,`fville`,`fgsm`,
		`tid`,`ttype`,`tnom`,`tadr`,
		`tcp`,`tville`,`tgsm`,
		`idsupplier`,`factureclient`, `tdate`)
VALUES (".$_SESSION['idagent'].",NOW(),'".$_REQUEST['secteur']."','".$idjob."',
	".$infopeople[0]['id'].",'".$infopeople[0]['type']."','".addslashes($infopeople[0]['nom'])."','".addslashes($infopeople[0]['adr'])."',
	'".$infopeople[0]['cp']."','".addslashes($infopeople[0]['ville'])."','".$infopeople[0]['gsm']."',
	'".$infoexception[0]['id']."','".$infoexception[0]['type']."','".addslashes($infoexception[0]['nom'])."','".addslashes($infoexception[0]['adr'])."',
	'".$infoexception[0]['cp']."','".addslashes($infoexception[0]['ville'])."','".$gsmagent."',
	91,25, '$datetomorrow');");
	
	//insertion du bon Exception -> People
	$DB->inline("INSERT INTO `neuro`.`bonlivraison` 
		(`idagent`,`fdate`,`secteur`,`idjob`,
		`fid`,`ftype`,`fnom`,`fadr`,
		`fcp`,`fville`,`fgsm`,
		`tid`,`ttype`,`tnom`,`tadr`,
		`tcp`,`tville`,`tgsm`,
		`idsupplier`,`factureclient`, `tdate`)
VALUES (".$_SESSION['idagent'].",NOW(),'".$_REQUEST['secteur']."','".$idjob."',
	".$infoexception[0]['id'].",'".$infoexception[0]['type']."','".addslashes($infoexception[0]['nom'])."','".addslashes($infoexception[0]['adr'])."',
	'".$infoexception[0]['cp']."','".addslashes($infoexception[0]['ville'])."','".$gsmagent."',
	'".$infopeople[0]['id']."','".$infopeople[0]['type']."','".addslashes($infopeople[0]['nom'])."','".addslashes($infopeople[0]['adr'])."',
	'".$infopeople[0]['cp']."','".addslashes($infopeople[0]['ville'])."','".$infopeople[0]['gsm']."',
	91,25, '$datetomorrow');");
	
	//insertion du bon Exception -> Client
	$DB->inline("INSERT INTO `neuro`.`bonlivraison` 
		(`idagent`,`fdate`,`secteur`,`idjob`,
		`fid`,`ftype`,`fnom`,`fadr`,
		`fcp`,`fville`,`fgsm`,
		`tid`,`ttype`,`tnom`,`tadr`,
		`tcp`,`tville`,`tgsm`,
		`idsupplier`,`factureclient`, `tdate`)
VALUES (".$_SESSION['idagent'].",NOW(),'".$_REQUEST['secteur']."','".$idjob."',
	".$infoexception[0]['id'].",'".$infoexception[0]['type']."','".addslashes($infoexception[0]['nom'])."','".addslashes($infoexception[0]['adr'])."',
	'".$infoexception[0]['cp']."','".addslashes($infoexception[0]['ville'])."','".$gsmagent."',
	'".$infoclient[0]['id']."','".$infoclient[0]['type']."','".addslashes($infoclient[0]['nom'])."','".addslashes($infoclient[0]['adr'])."',
	'".$infoclient[0]['cp']."','".addslashes($infoclient[0]['ville'])."','',
	91,25, '$datetomorrow');");
	}
}

/*
	TODO ne pas générer les bons de livraison dans la table
*/

$DB->inline("SELECT 
				b.*,
				s.societe
			FROM bonlivraison b
				LEFT JOIN supplier s ON b.idsupplier = s.idsupplier
			WHERE b.idjob = ".$idjob." AND b.etat LIKE 'in';");
?>
<script type="text/javascript" charset="utf-8">
	parent.frames['up'].document.getElementById("nbrbdl").innerHTML = '('+<?php echo $nbr;?>+')';
</script>
<table class="sortable-onload-0 paginate-20 rowstyle-alt no-arrow" border="0" width="90%" cellspacing="1" align="center">
	<thead>
		<tr>
			<th class="sortable-numeric">ID</th>
			<th class="sortable-text">Livreur</th>
			<th class="sortable-text">Référence</th>
			<th class="sortable-text">De</th>
			<th class="sortable-text">A</th>
			<th class="sortable-text">Date</th>
			<th class="sortable-text">Intitulé</th>
			<th class="sortable-text" width="16"></th>
			<th class="sortable-text" width="16"></th>
			<th class="sortable-text" width="16"></th>
		</tr>
	</thead>
	<tbody>
<?php while($row = mysql_fetch_array($DB->result)) {
	switch ($row['ftype']) {
		case 'people':
			$de = $DB->getOne("SELECT CONCAT(pprenom,' ',pnom) from people where idpeople='".$row['fid']."';");
			break;
		case 'client':
			$de = $DB->getOne("SELECT societe from client where idclient='".$row['fid']."';");
			break;
		case 'shop':
			$de = $DB->getOne("SELECT societe from shop where idshop='".$row['fid']."';");
			break;
		case 'exception':
			$de = $DB->getOne("SELECT societe from client where idclient='".$row['fid']."';");
			break;
		case 'people':
			$de = $DB->getOne("SELECT CONCAT(pprenom,' ',pnom) from people where idpeople='".$row['fid']."';");
			break;
		default:
			$de = $DB->getOne("SELECT fadr from bonlivraison where id='".$row['id']."';");
			break;
	}

	if($row['ttype']=="people")
	{
		$a = $DB->getOne("SELECT CONCAT(pprenom,' ',pnom) from people where idpeople='".$row['tid']."';");
	}
	else if($row['ttype']=="client")
	{
		$a = $DB->getOne("SELECT societe from client where idclient='".$row['tid']."';");
	}
	else if($row['ttype']=="shop")
	{
		$a = $DB->getOne("SELECT societe from shop where idshop='".$row['tid']."';");
	}
	else if($row['ttype']=="exception")
	{
		$a = $DB->getOne("SELECT societe from client where idclient='".$row['tid']."';");
	}
	else
	{
		$a = $DB->getOne("SELECT tadr from bonlivraison where id='".$row['id']."';");
	}
	
	if($row['valide'] == "Y")
	{
		$color = "black";
	}
	else
	{
		$color = "grey";
	}
	 ?>
	<tr ondblclick="location.href='<?php echo $_SERVER['PHP_SELF'].'?act=show&all='.$_REQUEST['all'].'&id='.$row['id'].'&liv='.$row['type'].'&secteur='.$_REQUEST['secteur'].'&idjob='.$row['idjob']; ?>'">
		<td align="center"><font color="<?php echo $color; ?>"><?php echo $row['id']; ?></font></td>
		<td align="center"><font color="<?php echo $color; ?>"><?php echo $row['societe']; ?></font></td>
		<td align="center"><font color="<?php echo $color; ?>"><?php echo $row['secteur'].' '.$row['idjob']; ?></font></td>
		<td align="center"><font color="<?php echo $color; ?>"><?php echo $de; ?></font></td>
		<td align="center"><font color="<?php echo $color; ?>"><?php echo $a; ?></font></td>
		<td align="center"><font color="<?php echo $color; ?>"><?php echo fdate($row['date']); ?></font></td>
		<td align="center"><font color="<?php echo $color; ?>"><?php echo $row['titre']; ?></font></td>
		<td style="padding: 0px;"><?php if($row['valide']=='N'){ ?><a href="<?php echo $_SERVER['PHP_SELF'].'?act=valide&id='.$row['id'].'&idjob='.$idjob.'&secteur='.$_REQUEST['secteur'].'&retour=true' ?>"><?php $img = "add";}else{ $img = "accept"; } ?><img src="<?php echo STATIK."illus/".$img.".png"?>" heigth="16" width="16" <?php if($row['valide']=='N'){ ?> title="Cliquez pour valider ce bon de livraison" <?php } ?> border="0"></td>
		<td align="center"><?php if($row['valide']=='Y'){ ?><A href="<?php echo substr(print_bdl($row['id']), 4); ?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0"></A><?php } ?></td>
		<td style="padding: 0px;"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=del&id='.$row['id'].'&secteur='.$_REQUEST['secteur'].'&idjob='.$row['idjob'].'&retour=true' ?>"><img src="<?php echo STATIK ?>illus/delete.png" heigth="16" width="16" border="0"></td>
	</tr>
<?php } ?>
	</tbody>
</table>			
<div align="center">
	<a style="font-size:16px; text-decoration:none;" href="<?php echo $_SERVER['PHP_SELF'].'?act=add&secteur='.$_REQUEST['secteur'].'&idjob='.$idjob ?>"><img src="<?php echo STATIK ?>illus/add.png" align="absmiddle" heigth="16" width="16" border="0"> Ajouter</a>
</div>