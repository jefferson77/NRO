<?php
define('NIVO', '../');

$Titre     = 'Groupe-S';
$PhraseBas = 'G&eacute;n&eacute;ration du PTG';
$Style     = 'admin';

# Classes utilisées
include_once(NIVO."classes/payement.php");
include_once(NIVO."classes/anim.php");
include_once(NIVO."classes/vip.php");
include_once(NIVO."classes/merch.php");
include_once(NIVO."classes/ptg.php");
include (NIVO.'nro/xlib/phpmailer/class.phpmailer.php');

# Entete de page
include NIVO."includes/entete.php" ;

$mois     = substr($_SESSION['table'], -2);
$annee    = '20'.substr($_SESSION['table'], -4, 2);
$datec4   = date("Y-m-t", mktime(0, 0, 0, $mois, 1, $annee));
$tablesql = $_SESSION['table'];

?>
<div id="leftmenu"></div>
<div id="infozone">
	<h1>Ecriture du PTG</h1>
	<?php
	if (($annee >= 2000) and ($mois >= 1) and ($mois <= 12)) {
		$PTG = new ptg('media/ptg/');

		$PTG->enreg000();
		$PTG->enreg001(1, '00100', 'ANIMATION', 'ANIMATION');
		$PTG->enreg001(1, '00200', 'VIP', 'VIP');
		$PTG->enreg001(1, '00300', 'MERCHANDISING', 'MERCHANDISING');
		$PTG->enreg001(1, '00400', 'EAS', 'EAS');
		$PTG->enreg003($mois, $annee, '3', '02'); # 3-Employes 02-remunération normale

		$afficher = new db('', '', 'grps');

		$afficher->inline("
		SELECT
			s.idpeople,
			p.codepeople AS registre,
			SUM( s.mod433 )  AS mod433,
			SUM( s.mod437 )  AS mod437,
			SUM( s.mod441 )  AS mod441,
			SUM( s.modh150 )  AS modh150,
			SUM( s.modh200 )  AS modh200

		FROM grps.$tablesql s
		LEFT  JOIN neuro.people p ON s.idpeople = p.idpeople
		GROUP  BY s.idpeople
		ORDER  BY p.codepeople");

		$nbr_people = 0;
		while ($row = mysql_fetch_array($afficher->result)) {
			$PTG->enreg006($row['registre'], $PTG->datefrom, $PTG->dateto);
			$PTG->affichepeople($row['idpeople']);
			unset($PTG->stocksql);
			$PTG->affichesalaire($row['idpeople'], $tablesql);
			$nbr_people++;
		}

		$PTG->enreg999(); # fermer le fichier

		## Mise a jour de la dernière date d'envoi pour la sortie des C4. (si la date est plus avancée que l'ancienne stockée)
		$updreg = new db();
		if ($updreg->CONFIG('lastpayement') < $datec4) {
			$updreg->inline("UPDATE `config` SET `vvaleur` = '$datec4' WHERE `vnom` ='lastpayement';");
		}

		?>
	<h1>Envoie du PTG</h1>
<?php

setlocale(LC_TIME, 'fr_FR');

$agent_infos = $DB->getRow("SELECT * from agent where idagent = ".$_SESSION['idagent']);

$mail               = new PHPMailer(true);

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host         = Conf::read('Mail.smtpHost'); // SMTP server

$mail->SetFrom("nico@exception2.be", "NEURO Exception2");

$mail->Subject      = "[NEURO] Salaires du ".date("d/m/Y");

$mail->Body      = "
Fichiers PTG a forwarder au groupe-s avec le texte suivant:

---------------------

	Bonjour Katrien,

Voici le PTG pour Exception2 pour le  mois de ".strftime("%B %Y", strtotime($annee.'-'.$mois.'-01')).".
(".$nbr_people." Personnes).

En cas de problèmes n'hésitez pas à me contacter directement au 0476 74 85 82

Bien à vous

Nicolas


";

$mail->WordWrap = 50;                                   // set word wrap to 50 characters

            # $mail->AddAddress("nico@exception2.be"); // pour test

            if (!empty($agent_infos['email'])) {
                $mail->AddAddress($agent_infos['email']);
            } else {
                $mail->AddAddress("nico@exception2.be");
            }

            $mail->AddAttachment($PTG->filepath);         // add attachments

            if(!$mail->Send())
            {
               echo "Message could not be sent. Mailer Error: " . $mail->ErrorInfo."\r\n";
               exit;
            }

            echo "<p>Le PTG vous a &eacute;t&eacute; envoy&eacute; par mail &agrave; l&#x27;adresse : ".$agent_infos['email']."</p>";

?>

</div>
<div id="infobouton">
	<a href="<?php echo $PTG->urlPTG; ?>" target="_blank">Ouvrir le Fichier PTG</a><br><br>
	<font size="10">Vérifier que la mise à jour de la date pour les C4 fonctionne correctement</font>
</div>
<?php


} else { ?>
	<p>
	Erreur, l'ann&eacute;e et le mois je sont pas pass&eacute;s, veuillez contacter <a href="mailto:nico@exception2.be">Nico</a> en lui expliquant la situation.

	<?php echo "mois : $mois<br>annee : $annee";?>
	</p>
	</div>
<?php
}

include NIVO."includes/pied.php" ;
?>