<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Untitled</title>
</head>
<body>
<?php 
define("NIVO", "../");

require_once(NIVO."nro/fm.php");

if (!empty($_POST['reqsql'])) {
	$req = new db();
	$reqs = explode(";", $_POST['reqsql']);
	foreach ($reqs as $sql) {
		$sql = trim($sql);
		if(!empty($sql)) $req->inline(stripslashes($sql));
	}
}

if (empty($_POST['jid'])) {
?>
<h1>Job a effacer : </h1>
<form action="<?php echo $_SERVER['PHP_SELF'] ;?>" method="post">
	<input name="jid" type="text" size="30">
	<input name="supprimer" type="submit" value="Supprimer">
</form>
<style type="text/css" title="text/css">
<!--
div.sql
{
	margin: 20px;
	border-color: #999;
	border-width: 1px;
	border-style: solid;
	background-color: #CCC;
}

-->
</style>
<?php
} else {
	$jobs = explode(",", trim($_POST['jid']));
	
	foreach($jobs as $job) {
		$job = trim($job);
	
		## trouver les num de missions du job
		$rch = "SELECT idanimation, idpeople FROM animation WHERE idanimjob = '".$job."'; "; $find = new db(); $find->inline($rch);
		
		## Effacer la fiche du job
		$sql = "DELETE FROM `animjob` WHERE idanimjob = ".$job." ; ";
		
		if (mysql_num_rows($find->result) > 0) {
		
			while($row = mysql_fetch_array($find->result)) {
				if ($row['idpeople'] > 0) {
					echo '<h1 style="color: #A00;" align="center"> PEOPLE '.$row['idpeople'].' SUR LA MISSION '.$row['idanimation'].'</h1>';
				} else {
					$ids[] = $row['idanimation'];
				}
			}
			
			if (is_array($ids)) {
				## Effacer les fiches anim
				$sql .= "DELETE FROM animation WHERE idanimation IN(".implode(", ", $ids).") ; ";
				
				
				## Effacer les fiches matos
				$sql .= "DELETE FROM animmateriel WHERE idanimation IN(".implode(", ", $ids).") ; ";
				
				## Effacer les fiches produit
				$sql .= "DELETE FROM animproduit WHERE idanimation IN(".implode(", ", $ids).") ; ";
			}
		}
	
		echo '<br><br><div class="sql">'.$sql.'</div>
<form action="'.$_SERVER['PHP_SELF'].'" method="post">
	<input name="reqsql" type="hidden" value="'.$sql.'">
	<input name="supprimer" type="submit" value="Supprimer">
</form>
		<br><br>';
		unset($sql);
	
	}
}


?>

</body>
</html>
