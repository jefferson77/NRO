<?php

require_once(NIVO."classes/document.php");

function generateSendTable($documentsParX, $type, $path, $reliure, $docType = "Fichiers")
{
	global $DB;

	$idsContact = array_keys($documentsParX);
	
	switch(count($idsContact))
	{
		case 0:
			$asql = ' = 0';
		break;
		case 1:
			$asql = ' = '.$idsContact[0];
		break;
		default:
			$asql = ' IN ('.implode(',',$idsContact).')';
		break;
	}
	
	$DB->inline("SET NAMES utf8");
	
	switch($type)
	{
		case "people":
			$title = "People";
			$sql = "SELECT idpeople as idContact, CONCAT(pprenom, ' ', pnom) as contactName, email as email, fax as fax, docpref as docpref FROM people WHERE idpeople ".$asql;
		break;
		case "cofficer":
			$title = " Client - Officer";
			$sql = "SELECT idcofficer as idContact, CONCAT(c.societe,' - ', co.oprenom, ' ', co.onom) as contactName, co.email as email, co.fax as fax, co.docpref as docpref 
					FROM cofficer co 
					LEFT JOIN client c ON c.idclient = co.idclient
					WHERE idcofficer ".$asql;
		break;
		case "shop":
			$title = "Magasin";
			$sql = "SELECT idshop as idContact, CONCAT(societe, ' ', ville) as contactName, email as email, fax as fax, docpref as docpref FROM shop WHERE idshop ".$asql;
		break;
	}

	$contactInfo = $DB->getArray($sql);
	
	if(!empty($contactInfo))
	{
		$contactInfos = array();
		foreach($contactInfo as $row) $contactInfos[$row['idContact']] = $row;
	?>
		<link rel="stylesheet" href="<?php echo STATIK ?>css/facture.css" type="text/css" media="screen" title="no title" charset="utf-8">
		<div align="center">
			<h2><?php echo utf8_encode($docType); ?> par <?php echo $title; ?></h2>
			<form method="POST" action="<?php echo NIVO."print/dispatch/"; ?>print_doc.php">
				<input type="hidden" name="file_path" value="<?php echo $path; ?>" />
				<input type="hidden" name="type" value="<?php echo $type; ?>">
			<table align="center"  class="fac" cellspacing='1'>
				<thead>
				<tr>
					<th align="center" ><?php echo $title; ?></th>
					<th align="center" width="25"><img onclick="unSelectAll('email');" src="<?php echo NIVO."illus/mail.png"; ?>" title="Envoyer par email"></th>
					<th align="center" width="25"><img onclick="unSelectAll('fax');"src="<?php echo NIVO."illus/telephone-fax.png"; ?>" title="Envoyer par Fax"></th>
					<th align="center" width="25"><img onclick="unSelectAll('post');"src="<?php echo NIVO."illus/printer.png"; ?>" title="Imprimer - Envoyer par Poste"></th>
					<th align="center" width="25"></th>
					<th align="center" ><a href="#" onclick="unSelectAll('unchecked');">Ne pas imprimer</a></th>
				</tr>
				<tr>
					<th colspan="6"><textarea name="generalText" cols="60" rows="6"></textarea></th>
				</tr>
				</thead>
				<tbody>
			<?php
			
			$i = 0;
			foreach ($documentsParX as $idContact => $fichiers) {
				$no_email = false;
				$no_fax = false;
				if($reliure != null)
				{
					$book = reliure($fichiers, $reliure);
				}
				else
				{
					$book['path'] = $fichiers[0];
				}
				$filepath = pathinfo($book['path']);
				$filename = $filepath['filename'];
				
				$fullpath = $filepath['dirname'].'/'.$filepath['basename'];
				?>
				
				<tr>
					<td><?php echo $contactInfos[$idContact]['contactName']; ?></td>
					<td align="center">
						<input 
							id='<?php echo 'email'.$i; ?>' 
							type="radio" 
							name="<?php echo $idContact."|".$filename; ?>" 
							value="email"
							<?php if($contactInfos[$idContact]['docpref'] == 'email' and valid_email($contactInfos[$idContact]['email']))
									{ 
										echo "checked"; 
									} 
									else 
									{
										echo "disabled"; 
										$no_email = true; 
									}?> 
							title="<?php echo $contactInfos[$idContact]['email']; ?>">
					</td>
					<td align="center">
						<input 
							id='<?php echo 'fax'.$i; ?>' 
							type="radio" 
							name="<?php echo $idContact."|".$filename; ?>" 
							value="fax"	
							<?php if($contactInfos[$idContact]['docpref'] == 'fax' and valid_fax($contactInfos[$idContact]['fax']))
								{ 
									echo "checked";
								} 
								else 
								{ 
									echo "disabled"; 
									$no_fax = true;
								} ?> 
							title="<?php echo $contactInfos[$idContact]['fax']; ?>">
					</td>
					<td align="center">
						<input 
							id='<?php echo 'post'.$i; ?>' 
							type="radio" 
							name="<?php echo $idContact."|".$filename; ?>" 
							value="post"	
							<?php 
								if($contactInfos[$idContact]['docpref'] == 'post') echo "checked";
								if($contactInfos[$idContact]['docpref'] == 'email' and $no_email == true ) echo "checked";
								if($contactInfos[$idContact]['docpref'] == 'fax' and $no_fax == true) echo "checked"; ?>>
					</td>
					<td align="center">
						<a href="<?php echo NIVO.'../document/'.$path.'/'.$filename.'.pdf'; ?>"><img border="0" src="<?php echo NIVO."illus/minipdf.gif"; ?>" title="Afficher le PDF"></a>
					</td>
					<td align="center">
						<input id='<?php echo 'unchecked'.$i; ?>' type="radio" name="<?php echo $idContact."|".$filename; ?>" value="nosend">
					</td>
				</tr>
				
				<?php $i++;
			}
		?>	</tbody>
		</table>
		<br>
		<input type="submit" name="Envoyer" value="            Envoyer            ">
		</form>
		<script type="text/javascript" charset="utf-8">
			var count = <?php echo $i; ?>;
		
			function unSelectAll(type)
			{
				var i = 0;
				while(i < count)
				{
					if(document.getElementById(type+i).disabled != false)
					{
						document.getElementById(type+i).checked = false;
						document.getElementById('post'+i).checked = true;
					}
					else
					{
						document.getElementById(type+i).checked = true;
					}
					
					i++;
				}
			}
		</script>
		</div>
		
		
	<?php
	}
	else
	{
		echo "<br><br>Pas de documents à envoyer";
	}
}

function valid_email($email)
{
	$pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i";
	
	if(empty($email))
	{
		return false;
	}
	if(preg_match($pattern,$email))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function valid_fax($email)
{
	return false;
}

function dispatcher_feedback($type, $idContact)
{
	global $DB;
	switch($type)
	{
		case "people":
			$docs = $DB->getArray("SELECT path, has_read, date_envoi, idagent FROM webdocs WHERE typecontact = 'people' AND idcontact = ".$idContact);
		break;
		case "shop":
			$docs = $DB->getArray("SELECT path, has_read, date_envoi, idagent FROM webdocs WHERE typecontact = 'shop' AND idcontact = ".$idContact);
		break;
		case "client":
			$docs = $DB->getArray("SELECT path, has_read, date_envoi, idagent FROM webdocs WHERE typecontact = 'client' AND idcontact = ".$idContact);
		break;
		case "agent":
			$docs = $DB->getArray("SELECT path, has_read, date_envoi, idagent, typecontact, idcontact FROM webdocs WHERE  idagent = ".$_SESSION['idagent']);
		break;
	}
	
	if(!empty($docs)) {
		if($type == 'agent'){
			echo "<div id='centerzonelarge'>";
		}
	?>
		<td>
			<br>
			<table>
				<tr>
					<th>
						Document
					</th>
					<th>
						Date d'envoi
					</th>
					<th>
						Lu
					</th>
					<th>
						<?php if($type == 'agent'){ ?>Envoyé à <?php } else { ?>Envoyé par <?php } ?>
					</th>
					<?php if($type == 'agent'){ echo "<th>Type</th>"; } ?>
				</tr>
				<?php foreach($docs as $row){ ?>
				<tr>
					<td>
						<?php
							$url = NIVO."../".substr($row['path'],4);
						?>

						<a href="<?php echo $url; ?>">Document</a>
					</td>
					<td>
						<?php echo fdate($row['date_envoi']); ?>
					</td>
					<td>
						<?php

						/*
							TODO ICONE lu ou pas lu
						*/

						if($row['has_read'] == 0)
						{
							?><img src="<?php echo NIVO.'illus/accept.png'; ?>" /><?php
						}
						else
						{
							?><img src="<?php echo NIVO.'illus/delete.png'; ?>" /><?php
						}

						?>
					</td>
					<td>
						<?php 
						
						if($type != 'agent') {
							echo $DB->getOne("SELECT CONCAT(nom, ' ', prenom) FROM agent WHERE idagent=".$row['idagent']); 
						} else {
							switch($row['typecontact'])
							{
								case "people":
									$title = "People";
									$sql = "SELECT CONCAT(pnom, ' ', pprenom) as contactName FROM people WHERE idpeople = ".$row['idcontact'];
								break;
								case "cofficer":
									$title = " Client - Officer";
									$sql = "SELECT CONCAT(c.societe,' - ', co.onom, ' ', co.oprenom) as contactName
											FROM cofficer co 
											LEFT JOIN client c ON c.idclient = co.idclient
											WHERE idcofficer = ".$row['idcontact'];
								break;
								case "shop":
									$title = "Magasin";
									$sql = "SELECT CONCAT(societe, ' ', ville) as contactName FROM shop WHERE idshop = ".$row['idcontact'];
								break;
								
								
							}
							echo $DB->getOne($sql);
						}
						
						?>
					</td>
					<?php if($type == 'agent'){
						?><td>
							<?php echo $title; ?>
							</td><?php
					} ?>
				</tr>
				<?php } ?>
			</table>
		</td>
<?php } else {
	echo "Pas de fichiers envoyés à cette personne.";
}
	?>
		
		
		
	<?php
}

?>