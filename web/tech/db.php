<?php
class db
{
######## MySQL config site #########
	var $serveur = '127.0.0.1';
	var $login = 'root';
	var $pass = 'roger195';
	
#################################

	var $dbname;
	var $lang;
	var $result;
	var $connection;
	var $db;
	var $table;
	var $idname;
	var $addid;
	var $FoundCount = 0;
	var $quid;
	var $quod;
	var $recherche;
	
# Exception Functions	
	function db ($table = 'notdefined', $idname = 'notdefined', $db = 'webneuro')
#	function db ($table = 'notdefined', $idname = 'notdefined', $db = 'neuro')
	{
		$this->dbname = $db;
		$this->table = $table;
		$this->idname = $idname;
	
		$this->connection = @mysql_connect($this->serveur, $this->login, $this->pass) or die("Couldn't Connect.");
		$this->db = @mysql_select_db($this->dbname, $this->connection) or die("Couldn't select database.");
	}
	
	function inline ($sql)
	{
		$this->result = @mysql_query($sql) or die("Error #". mysql_errno() . ": " . mysql_error());
		if (is_array($this->result)) {
			$this->FoundCount = mysql_num_rows($this->result); 
		}
		$this->addid = mysql_insert_id() ;

	}

	function EFFACE ($id)
	{
		$this->result = @mysql_query("DELETE FROM `$this->table` WHERE `$this->idname` = $id") or die("Error #". mysql_errno() . ": " . mysql_error());

	}

	function AJOUTE ($liste)
	{
		# passe en revue les infos de Liste et regroupe les valeurs multiples (issues de checkbox)
		foreach ($liste as $value) {
			if (is_array($_POST[$value])) { 
				foreach ($_POST[$value] as $value2) {
					$vari[$value] .= addslashes($value2).'%%';
				}
			} else {
				$vari[$value] = addslashes($_POST[$value]);
			}
		
		}
		# Fin 

		$thistime = strftime("%Y-%m-%d %T");
	
		$sql = "INSERT INTO `$this->table` (";
		foreach ($vari as $key => $value) {
			$sql .= "`$key`, ";
		}
		$sql .= "`agentmodif`, `datemodif`) VALUES (";
		foreach ($vari as $key => $value) {
			$sql .= "'".$value."', ";
		}
		$sql .= "'".$_SESSION['idagent']."', '".$thistime."');";
				
		$this->result = @mysql_query($sql) or die("Error #". mysql_errno() . ": " . mysql_error());
		$this->addid = mysql_insert_id() ;
	}

	function MODIFIE ($id, $liste)
	{
		# passe en revue les infos de Liste et regroupe les valeurs multiples (issues de checkbox)
		foreach ($liste as $value) {
			if (is_array($_POST[$value])) { 
				foreach ($_POST[$value] as $value2) {
					$vari[$value] .= $value2.'%%';
				}
			} else {
				$vari[$value] = $_POST[$value];
			}
		
		}
		# Fin 
		
		$thistime = strftime("%Y-%m-%d %T");
	
		$sql = "UPDATE `$this->table` SET ";
		foreach ($vari as $key => $value) {
			if ($value == 'NULL') {
				$sql .= "`$key` = NULL, ";
			} else {
				$sql .= "`$key` = '".$value."', ";
			}
		}
		$sql .= "`agentmodif` = '".$_SESSION['idagent']."', `datemodif` = '$thistime' WHERE `$this->idname` = $id;";

		$this->result = @mysql_query($sql) or die("Error #". mysql_errno() . ": " . mysql_error());
	}
	
	## Va chercher une valeur unique stockée dans la base 'config'
	function CONFIG ($valconfig) {
	
		$sql = "SELECT `vvaleur` FROM `config` WHERE `vnom` = '$valconfig'";

		$this->result = @mysql_query($sql) or die("Error #". mysql_errno() . ": " . mysql_error());
	
		$row = mysql_fetch_array($this->result);
		return $row['vvaleur'];
	
	}
	
	function MAKEsearch ($searchfield, $afficher, $table, $sort, $skip, $show) {
			### Boucle de construction de la recherche					
		foreach ($searchfield as $srchfld => $format) {
			if ($_POST[$srchfld] != '') {
				## Ajout un AND si la requete existe déja
				if ($this->quid != '')
				{
					$this->quid .= " AND ";
					$this->quod .= " ET ";
				}
				
				## Détermine le type de cas
				if ((strstr($_POST[$srchfld], ',')) and (strstr($_POST[$srchfld], '...'))) {$cas = '01';} else {
				if (strstr($_POST[$srchfld], ',')) {$cas = '02';} else {
				if (strstr($_POST[$srchfld], '...')) {$cas = '03';} else {
				if (strstr($_POST[$srchfld], '>=')) {$cas = '04';} else {
				if (strstr($_POST[$srchfld], '<=')) {$cas = '05';} else {
				if (strstr($_POST[$srchfld], '>')) {$cas = '06';} else {
				if (strstr($_POST[$srchfld], '<')) {$cas = '07';} else {
				if (strstr($_POST[$srchfld], '=')) {$cas = '08';} else {
				if (strstr($_POST[$srchfld], '*')) {$cas = '09';} else {
				if (strstr($_POST[$srchfld], '?')) {$cas = '10';}}}}}}}}}}
				
				
				## Construit la requete selon le cas
				
				
				switch ($cas) {
					case "01":
					break;
				
					case "02":
					break;
				
					case "03":
					break;
				
					case "04":
					break;
				
					case "05":
					break;
				
					case "06":
					break;
				
					case "07":
					break;
				
					case "08":
					break;
				
					case "09":
					break;
				
					case "10":
					break;

					default: 
						$this->quid .= "$srchfld LIKE '%".$_POST[$srchfld]."%'";
						$this->quod .= "$srchfld = ".$_POST[$srchfld];
					
				}
			}
		}
		
		if ($this->quid != '') {$this->quid='WHERE '.$this->quid;}
		
		$this->recherche = 'SELECT '.$afficher.' FROM `'.$table.'` '.$this->quid.' ORDER BY '.$sort.' LIMIT '.$skip.', '.$show.' ;';
	}

###############################################################################
##################### Old Functions ###########################################
###############################################################################

# Code pas encore utilisé dans le projet NERO
	function tablexist ($tableName)
	{
		$tables = array();
		$tablesResult = mysql_list_tables($this->dbname, $this->connection);
		while ($row = mysql_fetch_row($tablesResult)) $tables[] = $row[0];
		return(in_array($tableName, $tables));
	}

	function liste ()
	{
		$nbrchamps = mysql_num_fields($this->result);
		
		echo '<table cellspacing="1" cellpadding="1" align="center"><tr>';
		for ($i = 0; $i < $nbrchamps; $i++) {echo '<td class="level1">'.mysql_field_name($this->result, $i).'</td>';}
		echo '</tr>';
		
		while ($row = mysql_fetch_row($this->result))
		{
			echo '<tr>';
			foreach($row as $value)
			{
				echo '<td class="level2">'.$value.'</td>';
			}
			echo'
			<td><a href="#">edit</a></td>
		</tr>';
		}
		echo'</table>';
	}
}
?>