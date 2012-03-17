<?php
function formatFaxNumber($number) {
	$formated_number = $number;
	
	if(substr($formated_number,0,1) == '+') {
		$justformat = true;
	}
	
	$formated_number = preg_replace("/[^0-9]/", "", $formated_number); # vire les crasses
	
	if($justformat) {
		return "+".$formated_number;
	}
	
	if(substr($formated_number,0,2) == "00") { // numéro international
		$formated_number = substr($formated_number,2);
		return "+".$formated_number;
	}
	
	if(substr($formated_number,0,1) == "0") { // numéro national
		$formated_number = substr($formated_number,1);
	}
	
	if(empty($formated_number)) {
		return "";
	}
	
	return "+32".$formated_number;
}

class dispatcher {
	var $filepath = "";
	var $action = "";
	var $no_contract = array();
	var $type = "";
	var $feeder = true;
	
	function dispatcher($file_path,$no_contract,$type) {
		$this->filepath = $file_path;
		$this->no_contract = $no_contract;
		$this->type = $type;
	}
	
	function send($peopleAndDocAndMethod) {
		foreach($peopleAndDocAndMethod as $key => $value) {
			if(!in_array($key,$this->no_contract)) {
				$content = explode("|",$key);				
				
				$filepath = Conf::read("Env.root")."document/".$this->filepath."/".$content[1].".pdf";
				
				if(file_exists($filepath)) {
					if($value != 'nosend') {
						if($value != 'fax') {
							if($this->$value($filepath,$content[0])==false) {
								if($value != "post") {
									$this->post($filepath,$content[0]);
								}
							}
						} else {
							$this->post($filepath,$content[0]);
							$this->action[$content[0]][] = "no_fax";
						}
					} else {
						$this->action[$content[0]][] = "no_send";
					}
				} else {
					$this->action[$content[0]][] = "file_error";
				}
			}
		}
				
		return $this->format_output($this->action);
	}
	
	function email($filepath, $idContact) {
		global $DB;
		
		$infoAgents = $DB->getRow("SELECT email, nom,  prenom  FROM agent  WHERE idagent  = ".$_SESSION['idagent']);

		switch ($this->type) {
			case 'people':
				$infoContact = $DB->getRow("SELECT email, lbureau as langue FROM people WHERE idpeople = ".$idContact);
			break;
			
			case 'cofficer':
				$infoContact = $DB->getRow("SELECT email, langue as langue FROM cofficer WHERE idcofficer = ".$idContact);
			break;
			
			case 'shop':
				$infoContact = $DB->getRow("SELECT email, slangue as langue FROM shop WHERE idshop = ".$idContact);
			break;
			
			default:
				$this->action[$idContact][] = "wrong_type";
				return false;
		}

		#### phrasebook ####################
		switch ($infoContact['langue']) {
			case 'NL':
				include NIVO.'print/dispatch/nl.php';
			break;

			case 'FR':
			default:
				include NIVO.'print/dispatch/fr.php';
			break;
		}
		#### phrasebook ####################

		$mail				= new PHPMailer(true);
		$mail->IsSMTP(); 
		$mail->Host			= Conf::read('Mail.smtpHost'); // SMTP server
		$mail->SetFrom($infoAgents['email'], $infoAgents['pprenom']." ".$infoAgents['pnom']);
		$mail->Subject		= $phrase['mail_titre'];
		
		include NIVO."print/dispatch/mail_body.php";
		
		$mail->MsgHTML($body);

		$mail->AltBody		= "Allez &agrave; l'adresse suivante pour acc&eacute;der &agrave; vos documents : http://www.exception2.be/documents/document.php?l=".$infoContact['langue']."&doc=".md5($filepath);

		$mail->WordWrap = 50;									// set word wrap to 50 characters
		
		if(empty($infoContact['email'])) {
			$this->action[$idContact][] = "email_none";
			return false;
		}

		$mail->AddAddress($infoContact['email']);

		if(!$mail->Send()) {
			$this->action[$idContact][] = "email_error";
			return false;
		}
		
		$_POST['typecontact'] = $this->type;
		$_POST['idcontact'] = $idContact;
		$_POST['path'] = $filepath;
		$_POST['hash'] = md5($filepath);
		$_POST['date_envoi'] = date("Y-m-d H:i:s");
		$_POST['idagent'] = $_SESSION['idagent'];
		$DB->ADD("webdocs");
		
		$this->action[$idContact][] = "email_success";
		return true;
	}

	function fax($filepath, $idContact) {
		global $DB;
		/**************** Settings begin **************/

		$username = "magniesg";
		$password = "obigames";
		$filetype = 'PDF';
		$filename = $filepath;
		/**************** Settings end ****************/
		
		
		if($this->type == "people") {
			$infoContact = $DB->getRow("SELECT fax FROM people WHERE idpeople = $idContact");
		} elseif($this->type == "cofficer") {                                         
			$infoContact = $DB->getRow("SELECT fax FROM cofficer WHERE idcofficer = $idContact");
		} elseif($this->type == "shop") {                                         
			$infoContact = $DB->getRow("SELECT fax FROM shop WHERE idshop = $idContact");
		} else {
			$this->action[$idContact][] = "wrong_type";
			return false;
		}
		
		if(empty($infoContact['fax'])) {
			$this->action[$idContact][] = "fax_none";
			return false;
		}
		
		$faxnumber = formatFaxNumber($infoContact['fax']);
				
		if(empty($faxnumber)) {
			$this->action[$idContact][] = "fax_wrong_number";
			return false;
		}
		
		$faxnumber = "+3227327938"; // à enlever pour prod

		// Open File
		if( !($fp = fopen($filename, "r"))) {
			$this->action[$idContact][] = "fax_wrong_file";
		    return false;
		}

		// Read data from the file into $data
		$data = "";
		while (!feof($fp)) $data .= fread($fp,1024);
		fclose($fp);

		$client = new SoapClient("http://ws.interfax.net/dfs.asmx?WSDL");

		$params->Username  = $username;
		$params->Password  = $password;
		$params->FaxNumber = $faxnumber;
		$params->FileData  = $data;
		$params->FileType  = "PDF";

		$result = $client->Sendfax($params);
		$endresult = $result->SendfaxResult;
		if($endresult > 0) {
			$this->action[$idContact][] = "fax_success";
			return true;
		}
		$this->action[$idContact][] = "fax_send_fail";
		return false;
	}

	function post($filepath, $idContact) {
		$this->feeder = !$this->feeder;
		
		if($this->feeder) {
			$feeder = "-o media=PF700B";
		} else {
			$feeder = "-o media=PF700A";
		}
		
		$cmd = "lpr -P Kyocera $feeder $filepath";
		exec($cmd,$output);
		if(empty($output)) {
			$this->action[$idContact][] = "print_success";
			return true;
		}
		$this->action[$idContact][] = "print_fail";
		return false;
		
	}
	
	function format_output($actions)
	{		
		global $DB;
		$contacts = array_keys($actions);
		
		if(count($contacts) > 1) {
			$asql = implode("','",$contacts);
			$asql = "IN ('".$asql."')";
		} else {
			$asql = " = ".$contacts[0];
		}

		switch($this->type) {
			case "people":
				$sql = "SELECT idpeople as id, pnom as nom, pprenom as prenom, email as email, fax as fax FROM people WHERE idpeople ".$asql;
			break;
			case "cofficer":
				$sql = "SELECT idcofficer as id, onom as nom, oprenom as prenom, email as email, fax as fax FROM cofficer WHERE idcofficer ".$asql;
			break;
			case "shop":
				$sql = "SELECT idshop as id, societe as nom, ville as prenom, email as email, fax as fax FROM shop WHERE idshop ".$asql;
			break;
			default:
				return false;
			break;
		}
			
		$infosContact = $DB->getArray($sql);
		
		$todisplay = "<table align='center'  class='fac' cellspacing='1'>
						<thead>
						<tr>
							<th>Contact</th>
							<th>Feedback</th>
							<th>Email</th>
							<th>Fax</th>
						</tr>
						</thead><tbody>";
		
		foreach($infosContact as $row) {
			$todisplay .= utf8_decode("<tr>
								<td>".$row['prenom']." ".$row['nom']."</td>
								<td>");
								
			$returns = $actions[$row['id']];
			foreach($returns as $ret) {
				switch($ret) {
					case "wrong_type":
						$todisplay .= "Grosse erreur, mauvais type de contact. Appeler Nico.<br />";
					break;
					case "file_error":
						$todisplay .= "Grosse erreur, fichier inexistant. Appeler Nico.<br />";
					break;
					case "print_success":
						$todisplay .= "Fichier imprimé <br />";
					break;
					case "print_fail":
						$todisplay .= "Pas pu imprimer le fichier, appeler Nico. <br />";
					break;
					case "fax_send_fail":
						$todisplay .= "Envoi du fax raté <br />";
					break;
					case "fax_success":
						$todisplay .= "Fax envoyé avec succes. <br />";
					break;
					case "fax_wrong_file":
						$todisplay .= "Pas de document à faxer. <br />";
					break;
					case "fax_wrong_number":
						$todisplay .= "Mauvais numéro de fax. <br />";
					break;
					case "fax_none":
						$todisplay .= "Pas de numéro de fax. <br />";
					break;
					case "email_success":
						$todisplay .= "Mail envoyé avec succès. <br />";
					break;
					case "email_none":
						$todisplay .= "Pas d'adresse mail. <br />";
					break;
					case "email_error":
						$todisplay .= "Envoi du mail raté. <br />";
					break;
					case "no_fax":
						$todisplay .= "La fonctionnalité fax n'est pas encore prête. <br />";
					break;
					case "no_send":
						$todisplay .= "Pas envoyé, comme demandé. <br />";
					break;
				}
			}
			$todisplay .= "</td>
							<td>".$row['email']."</td>
							<td>".formatFaxNumber($row['fax'])."</td>
						</tr>
						";
		
		}
		$todisplay .= "<tr>
		<td colspan=4>
			<a href='javascript:window.close();'><b>&gt; Fermer &lt;</b></a>
		</td>
		</tr>
		</tbody></table>";
		return $todisplay;
	}
}
?>