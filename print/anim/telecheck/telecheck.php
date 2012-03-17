<?php
##################################################################################################################################################################
### Requete ##################################################################################################################################################
	$recherche = "SELECT 
		an.idanimation, an.datem, an.hin1, an.hout1, an.hin2, an.hout2, an.produit, an.weekm, an.yearm, an.tchkdate, an.tchkcomment,
		j.idcofficer, j.idclient, j.idagent,
		c.societe as clsociete,
		o.qualite, o.onom, o.oprenom, o.fax, o.langue,
		s.societe as ssociete, s.ville as sville,
		p.pprenom, p.pnom
		
		FROM animation an
		LEFT JOIN animjob j ON an.idanimjob = j.idanimjob
		LEFT JOIN client c ON j.idclient = c.idclient
		LEFT JOIN cofficer o ON j.idcofficer = o.idcofficer
		LEFT JOIN shop s ON an.idshop = s.idshop
		LEFT JOIN people p ON an.idpeople = p.idpeople 
	";
	
		$recherche .= " WHERE an.idanimation IN('".implode("', '", $_POST['print'])."') ORDER BY j.idcofficer ASC, datem ASC, an.idshop";
	
	$det = new db();
	$det->inline("SET NAMES latin1");
	$det->inline($recherche);

## dates in et out ###################################################
	while ($row = mysql_fetch_array($det->result)) {
		## dates
		if (($dattin[$row['idcofficer']] > $row['datem']) or (empty($dattin[$row['idcofficer']]))) $dattin[$row['idcofficer']] = $row['datem'];
		if (($dattout[$row['idcofficer']] < $row['datem']) or (empty($dattout[$row['idcofficer']]))) $dattout[$row['idcofficer']] = $row['datem'];
	}

## Infos Agent #######################################################
	
	mysql_data_seek($det->result, 0);
	
##################################################################################################################################################################
### PDF init ##################################################################################################################################################
	$pdf = pdf_new();
	
	$pathpdf = 'document/temp/anim/telecheck/';
	$nompdf = 'TeleCheck-'.date("Ymd_Hi").'.pdf';
	
	pdf_open_file($pdf, Conf::read('Env.root').$pathpdf.$nompdf); # définit l'emplacement de la sauvegarde

### Infos pour le document ####################
	pdf_set_info($pdf, "Title", "TeleCheck Anim");
	pdf_set_info($pdf, "Creator", "NEURO");
	pdf_set_info($pdf, "Subject", "TeleCheck Anim");

######## Variables de taille  #################
	$LargeurPage = 595; # Largeur A4
	$HauteurPage = 842; # Hauteur A4
	$MargeLeft = 30;
	$MargeRight = 30;
	$MargeTop = 30;
	$MargeBottom = 30;

	$LargeurUtile = $LargeurPage - $MargeRight - $MargeLeft;
	$HauteurUtile = $HauteurPage - $MargeTop - $MargeBottom;

######## Variables de structure  ###############
	$np = 0; 
	$tour = 1;
	$turntot = 1; 
	$oldjumper = 'XX';
	
	$nbr = mysql_num_rows($det->result);

##################################################################################################################################################################
### Construction du PDF #######################################################################################################################################
	while ($row = mysql_fetch_array($det->result)) { 
		
		$jumper = $row['idcofficer'].'-'.$row['weekm'].'-'.$row['yearm'];
	
	#### Entete de Page  ####################################################################################################
	#																														#
		if ($jumper != $oldjumper) {
			if ($tour != 1) {
				$part = 'politesseout'; 	include "parts.php";
				$part = 'pied'; 			include "parts.php";
				$tour = 1;
				$np = 0;
			}

			### Phrasebook
			switch($row['langue']) {
				case"FR": 
					include "fr.php"; 
				break;
				case"NL": 
					include "nl.php"; 
					$row['qualite'] = qualiteNL($row['qualite']);
				break;
			}

			$np++;
			$tab = 400;
			$tabt = 401;
			$jump = 23;

			$part = 'entete1'; 				include "parts.php";
			$part = 'politessein'; 			include "parts.php";
			$part = 'entete2'; 				include "parts.php";
		} else {
			if ($tour == 1) {
				$np++;
				$tab = 520;
				$tabt = 521;
				$jump = 28;

				$part = 'entete1'; 			include "parts.php";
				$part = 'entete2'; 			include "parts.php";
			}
 		}
	#																														#
	#### Entete de Page  ####################################################################################################
	
	
	
	#### Corps  #############################################################################################################
	#																														#
		$part = 'corps'; 					include "parts.php";

		$agents[$row['idagent']] += 1;
	#																														#
	#### Corps  #############################################################################################################
			
			
			
	#### Pied  ##############################################################################################################
	#																														#
		if (($tour >= $jump) or ($turntot >= $nbr)) {
			if ($turntot == $nbr) {
				$part = 'politesseout'; 	include "parts.php";
			}
	
			$part = 'pied'; 				include "parts.php";
			$tour = 0;
		}
	
		$tour++;
		$turntot++;
		
		$oldjumper = $jumper;
	#																														#
	#### Pied  ##############################################################################################################
	}

	pdf_end_document($pdf, '');
	pdf_delete($pdf); # Efface le fichier en mémoire
	
##################################################################################################################################################################
### HTML lien #######################################################################################################################################
?>
<table width="95%">
	<tr>
		<td align="left">
			<A href="<?php echo NIVO.$pathpdf.$nompdf ;?>" target="_blank"><img src="<?php echo STATIK ?>illus/minipdf.gif" alt="print" width="16" height="16" border="0">T&eacute;l&eacute; Check</A><br>
		</td>
	</tr>
</table>