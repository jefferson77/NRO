<div id="centerzonelarge">
<?php $classe = "planning" ;

################### Code PHP ########################
if (!empty($_GET['idmerch'])) {
	$idmerch = $_GET['idmerch'];
	$_POST['idmerch'] = $idmerch;
	$detail = new db();
	$detail->inline("SELECT * FROM `mercheasproduit` WHERE `idmerch` = $idmerch");
	$infos = mysql_fetch_array($detail->result) ; 
	$FoundCount = mysql_num_rows($detail->result); 
	if ($FoundCount < 1) {
		$ajout = new db('mercheasproduit', 'ideasprod');
		$ajout->AJOUTE(array('idmerch'));
	}
}
################### Fin Code PHP ########################

# VARIABLE SELECT
		# idshop
			if (!empty($quid)) 
			{
				$quid .= " AND ";
				$quod .= " ET ";
			}	
			$quid .= "s.idshop = ".$_GET['idshop'];
			$quod .= "idshop = ".$_GET['idshop'];
		# idshop

		# genre
			if (!empty($quid)) 
			{
				$quid .= " AND ";
				$quod .= " ET ";
			}	
			$quid .= "me.genre LIKE '%".$_GET['genre']."%'";
			$quod .= "genre = ".$_GET['genre'];
		# genre

		# weekm1
			if (!empty($quid)) 
			{
				$quid .= " AND ";
				$quod .= " ET ";
			}	
			$quid .= "me.weekm = '".$_GET['weekm']."'";
			$quod .= "weekm = ".$_GET['weekm'];
		#/ weekm1
		# idmerch
			if (!empty($quid)) 
			{
				$quid .= " AND ";
				$quod .= " ET ";
			}	
			$quid .= "me.idmerch = '".$_GET['idmerch']."'";
			$quod .= "idmerch = ".$_GET['idmerch'];
		#/ idmerch

#/ VARIABLE SELECT
		
		
		$sort .= 'me.datem, me.hin1';

		if (!empty($quid)) {$quid='WHERE '.$quid;}
		$recherche2='
			me.idmerch, me.datem, me.weekm, me.genre, 
			me.hin1, me.hout1, me.hin2, me.hout2, 
			me.kmpaye, me.kmfacture, me.frais, me.fraisfacture, 
			me.produit, me.facturation, 
			me.ferie, me.contratencode, me.rapportencode,
			
			mp.fo1a, mp.fo1b, mp.fo1c,
			mp.fo2a, mp.fo2b, mp.fo2c,
			mp.fo3a, mp.fo3b, mp.fo3c,
			mp.fo4a, mp.fo4b, mp.fo4c,
			
			mp.pa1a, mp.pa1b, mp.pa1c,
			mp.pa2a, mp.pa2b, mp.pa2c,
			mp.pa3a, mp.pa3b, mp.pa3c,
			mp.pa4a, mp.pa4b, mp.pa4c,
			mp.pa5a, mp.pa5b, mp.pa5c,
			mp.pa6a, mp.pa6b, mp.pa6c,
			mp.pa7a, mp.pa7b, mp.pa7c,
			mp.pa8a, mp.pa8b, mp.pa8c,
			mp.pa9a, mp.pa9b, mp.pa9c,
			mp.pa10a, mp.pa10b, mp.pa10c,
			
			mp.te1a, mp.te1b, mp.te1c,
			mp.te2a, mp.te2b, mp.te2c,
			mp.te3a, mp.te3b, mp.te3c,
			mp.te4a, mp.te4b, mp.te4c,
			mp.te5a, mp.te5b, mp.te5c,
			mp.te6a, mp.te6b, mp.te6c,
			mp.te7a, mp.te7b, mp.te7c,
			mp.te8a, mp.te8b, mp.te8c,
			
			mp.ep1a, mp.ep1b, mp.ep1c,
			mp.ep2a, mp.ep2b, mp.ep2c,
			mp.ep3a, mp.ep3b, mp.ep3c,
			mp.ep4a, mp.ep4b, mp.ep4c,
			mp.ep5a, mp.ep5b, mp.ep5c,
			
			mp.ba1a, mp.ba1b, mp.ba1c,
			mp.ba2a, mp.ba2b, mp.ba2c,
			mp.ba3a, mp.ba3b, mp.ba3c,
			mp.ba4a, mp.ba4b, mp.ba4c,
			mp.ba5a, mp.ba5b, mp.ba5c,
			mp.ba6a, mp.ba6b, mp.ba6c,

			mp.au1a, mp.au1b, mp.au1c,
			mp.au2a, mp.au2b, mp.au2c,
			mp.au3a, mp.au3b, mp.au3c,
			mp.au4a, mp.au4b, mp.au4c,
			mp.au5a, mp.au5b, mp.au5c,
			
			mp.au1n, mp.au2n, mp.au3n, mp.au4n, mp.au5n, 
			mp.caisse, mp.badcaisses, mp.ideasprod,
			
			a.prenom, a.idagent, 
			c.idclient, c.codeclient, c.societe AS clsociete, c.idclient, c.tel, c.fax, 
			co.idcofficer, co.qualite, co.onom, co.oprenom, co.fax AS cofax, 
			s.idshop, s.codeshop, s.societe AS ssociete, s.ville AS sville, s.eassemaine,
			p.pnom, p.pprenom, p.lbureau, p.idpeople, p.nrnational, p.gsm, p.codepeople 
			
			FROM merch me
			LEFT JOIN agent a ON me.idagent = a.idagent 
			LEFT JOIN client c ON me.idclient = c.idclient 
			LEFT JOIN cofficer co ON me.idcofficer = co.idcofficer 
			LEFT JOIN people p ON me.idpeople = p.idpeople
			LEFT JOIN shop s ON me.idshop = s.idshop
			LEFT JOIN mercheasproduit mp ON me.idmerch  = mp.idmerch 
		';
		$recherche1='
			SELECT '.$recherche2;
		
		$recherche='
			'.$recherche1.'
			'.$quid.'
			 ORDER BY '.$sort.'
		';

#############
############# pour easmois

			$datetemp = explode ('-', $_GET['datem']);
			$easannee = $datetemp[0];
			$easmois = $datetemp[1];
			
	$detail = new db();
	$detail->inline("SELECT * FROM `mercheasmois` WHERE `easmois` = $easmois AND `easannee` = $easannee");
	$infos = mysql_fetch_array($detail->result) ; 

#/############ pour easmois

### COMMUN : Afficher la liste des merch 
# Recherche des résultats

$listing = new db();
$listing->inline("$recherche;");
$FoundCount = mysql_num_rows($listing->result); 

# ------- DEBUT Info GENERAL haut de page --------

mysql_data_seek($listing->result, 0);
while ($row = mysql_fetch_array($listing->result)) { 
	$zz = 3;
	$zzz++;
	if ($zzz == 1) {	
		$date = weekdate($row['weekm'], $easannee);
		$weekm = $row['weekm'];
		$jourencours = $date['lun'];
		echo "
			<fieldset>
				<legend>
					<b>Planning des Merch EAS 2</b>
				</legend>
				<table class='".$classe."' border='0' width='98%' cellspacing='1' cellpadding='1' align='center'>
					<tr>
						<td width='50%'>		
							semaine : ".$row['weekm']." ( ".fdate($date['lun'])." au ".fdate($date['ven'])." )<br>
							people : <img src='".STATIK."illus/".$row['lbureau'].".gif' alt='".$row['lbureau']."' width='12' height='9'> ".$row['codepeople']." - ".$row['idpeople']." - ".$row['pnom']." ".$row['pprenom']."
						</td>
						<td width='50%'>		
							lieu : ".$row['idshop']." - ".$row['ssociete']."- ".$row['sville']."<br>					
							Heures semaine : ".$row['eassemaine']."				
						</td>	
					</tr>
				</table>
			</fieldset>
		";
	}
#/ ------- FIN Info GENERAL haut de page --------




# ------- DEBUT LISTING GENERAL --------
$colspa = 4;

#### ARRAY pour ligne produit
$nomchamps = array(

				'modifier1' => 'modifier',
				'vide1' => 'blanc',
				'entete' => 'entete',
				'FOOD' => 'titre',
				'fo1' => 'Alcool',
				'fo2' => 'Saumon',
				'fo3' => 'Foie',
				'fo4' => 'Gibier',
				
#				'NON FOOD' => 'titre',
				'Parfumerie' => 'titre',
				'pa1' => 'Maquillage',
				'pa2' => 'Lames',
				'pa3' => 'Cr&egrave;me',
				'pa4' => 'parfums',
				'pa5' => 'Coloration',
				'pa6' => 'Parapharma',
				'pa7' => 'Colis',
				'pa8' => 'Produits',
				'pa9' => 'Soins',
				'pa10' => 'Test',
				
				'Textile' => 'titre',
				'te1' => 'Pantys',
				'te2' => 'Sac',
				'te3' => 'Bonnet',
				'te4' => 'Chaussure',
				'te5' => 'Lingerie',
				'te6' => 'Veste',
				'te7' => 'Pericult',
				'te8' => 'Jeans',
				
				'EPCS' => 'titre',
				'ep1' => 'Cartouches',
				'ep2' => 'Gsm',
				'ep3' => 'Inform',
				'ep4' => 'Film',
				'ep5' => 'Agenda',
				
				'Bazar' => 'titre',
				'ba1' => 'Papeterie',
				'ba2' => 'Tabac',
				'ba3' => 'Lego',
				'ba4' => 'Barbie',
				'ba5' => 'Auto',
				'ba6' => 'Sac',

				'Autres' => 'titre',
				'au1' => 'Autre',
				'au2' => 'Autre',
				'au3' => 'Autre',
				'au4' => 'Autre',
				'au5' => 'Autre',

				'Totaux' => 'titre',
				'tottemp' => 'Total Articles',
				'totjour' => 'Total jour',
				'heurejour' => 'heure / jour',
				'moyennejour' => 'Moyenne par heure',
				'vide2' => 'blanc',
				'modifier2' => 'modifier'

				);
#/### ARRAY pour ligne produit

?>
<form action="<?php echo $_SERVER['PHP_SELF'].'?act=planningweekeas3&idmerch='.$row['idmerch'].'&weekm='.$row['weekm'].'&idshop='.$row['idshop'].'&genre='.$row['genre'].'&datem='.$row['datem'];?>" method="post" autocomplete="off">
	<input type="hidden" name="idmerch" value="<?php echo $row['idmerch'];?>"> 
	<input type="hidden" name="ideasprod" value="<?php echo $row['ideasprod'];?>"> 
	<input type="hidden" name="fiche" value="oui"> 
<?php

echo '
		<table class="'.$classe.'" border="1" width="50%" cellspacing="1" cellpadding="1" align="center">
			<tr>
	';

#### COLONE de TITRE

				echo '
					<td valign="top">
						<table class="'.$classe.'" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
				';
				echo '
					<tr><td class="'.$classe.'" align="center">Semaine<br>'.$weekm.'<br>&nbsp;</td></tr>
					<tr><td height="20" class="'.$classe.'">&nbsp;</td></tr>
					<tr><td height="20" class="'.$classe.'">&nbsp;</td></tr>
					<tr><td height="20" class="'.$classe.'">&nbsp;</td></tr>
					<tr><td height="25" class="'.$classe.'">&nbsp;</td></tr>
					<tr><td height="25" class="'.$classe.'">&nbsp;</td></tr>
				';
		foreach ($nomchamps as $champ => $nomch){
			switch ($nomch) {
				#### entete
				case "modifier":
					echo '<tr><td height="20" class="'.$classe.'">&nbsp;</td></tr>';
				break;
				#### entete
				case "entete":
					echo '<tr><td class="'.$classe.'" colspan="'.$zz.'">&nbsp;</td></tr>';
				break;

				#### titre
				case "titre":
					echo '<tr><th class="'.$classe.'" colspan="'.$zz.'" align="left">'.$champ.'</th></tr>';
				break;
				#### blanc
				case "blanc":
					echo '<tr><td class="'.$classe.'" colspan="'.$zz.'">&nbsp;</td></tr>';
				break;
				#### total articles
				case "Total Articles":
					echo '<tr><th class="'.$classe.'" colspan="'.$zz.'" align="left">'.$nomch.'</th></tr>';
				break;
				
				#### Total jour
				case "Total jour":
					echo '<tr><th class="'.$classe.'" colspan="'.$zz.'" align="left">'.$nomch.'</th></tr>';
				break;
				
				#### heure/jour
				case "heure / jour":
					echo '<tr><th class="'.$classe.'" colspan="'.$zz.'" align="left">'.$nomch.'</th></tr>';
				break;

				#### heure/jour
				case "Moyenne par heure":
					echo '<tr><th class="'.$classe.'" colspan="'.$zz.'" align="left">'.$nomch.'</th></tr>';
				break;
				
				#### Autre
				case "Autre":
					echo '<tr>
						<td height="20" class="'.$classe.'">
							<input type="text" size="15" name="'.$champ.'n" value="'.$row[$champ.'n'].'">
						</td>
						</tr>
					';
				break;
				#### Normal
				default: 
					echo '<tr><td height="20" class="'.$classe.'" colspan="'.$zz.'" align="left">'.$nomch.'</td></tr>';
				break;
			}
}
echo '</table></td>';

#/### COLONE de TITRE



## TODO essayer d'utiliser strtotime("%A") a la place d'une array avec les noms de jours (pas beau) (CODAPAT)

#### COLONES de PRODUITS
		$titre = 'go';	
		$date2 = $jourencours;
		$jour1 = 'lun';
		$jour2 = 'mar';
		$jour3 = 'mer';
		$jour4 = 'jeu';
		$jour5 = 'ven';
		$jour6 = 'sam';
		$jour7 = 'dim';
		$jourx1 = 'lundi';
		$jourx2 = 'mardi';
		$jourx3 = 'mercredi';
		$jourx4 = 'jeudi';
		$jourx5 = 'vendredi';
		$jourx6 = 'samedi';
		$jourx7 = 'dimanche';
		$jj = 1;
		$date = weekdate($row['weekm'], $easannee);
		$datetemp = implode('',explode("-",$jourencours));

				$i++;
	### pour jour en cours

		$jourprint = 'jour'.$jj; 
		$jourprintx = 'jourx'.$jj; 
		$datemtemp = implode('',explode("-",$row['datem']));
			### jours vides avant
				while (($datetemp < $datemtemp) and ($jj < 6)) {
					$date2 = $date[$$jourprint]; 
					$jj++;
					$jourprint = 'jour'.$jj; 
					$jourprintx = 'jourx'.$jj; 
					$datetemp++;
					} 
			#/## jours vides avant

				echo '
					<td valign="top">
						<table class="'.$classe.'" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
				';
			
				setlocale(LC_TIME, 'fr_FR');
				$data = explode('-',$row['datem']);
				$jaar = $data[0];
				$maand = $data[1];
				$dag = $data[2];
				$jourdhui = date("l", mktime(0, 0, 0, $maand  , $dag , $jaar));

				echo '
					<tr><td class="'.$classe.'" colspan="3" align="center">'.strftime("%A", strtotime($row['datem'])).'<br>'.fdate($row['datem']).'<br>job '.$row['idmerch'].'</td></tr>
					<tr><td class="'.$classe.'" height="20">Am</td><td class="'.$classe.'" colspan="2">
						<input type="text" size="5" name="hin1" value="'.ftime($row['hin1']).'"> -
						<input type="text" size="5" name="hout1" value="'.ftime($row['hout1']).'">
					</td></tr>
					<tr><td class="'.$classe.'" height="20">Pm</td><td class="'.$classe.'" colspan="2">
						<input type="text" size="5" name="hin2" value="'.ftime($row['hin2']).'"> -
						<input type="text" size="5" name="hout2" value="'.ftime($row['hout2']).'">
					</td></tr>
					<tr><td class="'.$classe.'" height="20">Km</td><td class="'.$classe.'" colspan="2">
						<input type="text" size="5" name="kmpaye" value="'.$row['kmpaye'].'">
					</td></tr>
					<tr><td class="'.$classe.'" height="25">C Encod&eacute;</td><td class="'.$classe.'" colspan="2">
						<input type="checkbox" name="contratencode" value="1"';	if ($row['contratencode'] == '1') { echo 'checked';} echo ' > Oui 
					</td></tr>
					<tr><td class="'.$classe.'" height="25">R Encod&eacute;</td><td class="'.$classe.'" colspan="2">
						<input type="checkbox" name="rapportencode" value="1"';	if ($row['rapportencode'] == '1') { echo 'checked';} echo ' > Oui 
					</td></tr>

				';
					$date2 = $date[$$jourprint];
					$jj++;
					$jourprint = 'jour'.$jj; 
					$jourprintx = 'jourx'.$jj; 
					$datetemp++;

		foreach ($nomchamps as $champ => $nomch){
			switch ($nomch) {
				#### entete
				case "modifier":
					echo '
					<tr>
							<td colspan="3" height="20" class="'.$classe.'" align="center">
					'; ?>
								<a href="<?php echo $_SERVER['PHP_SELF'].'?act=planningweekeas&idmerch='.$row['idmerch'].'&weekm='.$row['weekm'].'&idshop='.$row['idshop'].'&genre='.$row['genre'].'&datem='.$row['datem'];?>"><b>Retour semaine</b></a>
								 &nbsp; - &nbsp; 
								<input type="submit" name="Modifier" value="Modifier">
								 &nbsp; - &nbsp; 
								<input type="submit" name="Modifier" value="Modifier et rester">
					<?php echo '
							</td>
						</tr>
					';	
				break;
				#### entete
				case "entete":
					echo '<tr>';
						echo'
						<td class="'.$classe.'">SC/HK</td>
						<td class="'.$classe.'">ET</td>
						<td class="'.$classe.'">PL</td>
						</tr>
					';
				break;

				#### titre
				case "titre":
					echo '<tr><th class="'.$classe.'" colspan="'.$zz.'" align="left">&nbsp;</th></tr>';
				break;
				#### blanc
				case "blanc":
					echo '<tr><td class="'.$classe.'" colspan="'.$zz.'">&nbsp;</td></tr>';
				break;
				#### total articles
				case "Total Articles":
					echo '<tr>';
						$nomvala = 'totempa'.$i;
						$nomvalb = 'totempb'.$i;
						$nomvalc = 'totempc'.$i;
						echo'
						<td class="'.$classe.'">'.$$nomvala.'</td>
						<td class="'.$classe.'">'.$$nomvalb.'</td>
						<td class="'.$classe.'">'.$$nomvalc.'</td>
						</tr>
					';
				break;
				
				#### Total jour
				case "Total jour":
					echo '<tr>';
						$nomvala = 'totempa'.$i;
						$nomvalb = 'totempb'.$i;
						$nomvalc = 'totempc'.$i;
						$nomvaltot = 'totjour'.$i;
						$$nomvaltot = $$nomvala + $$nomvalb + $$nomvalc;
					echo'
						<td class="'.$classe.'" colspan="3" align="right">'.$$nomvaltot.'</td>
						</tr>
					';
						$totalarticle += $$nomvaltot;
				break;
				
				#### heure/jour
				case "heure / jour":
					echo '<tr>';
						$merch = new coremerch($row['idmerch']);
						$nomvala = 'totheure'.$i;
						$$nomvala = $merch->hprest;
					echo'
						<td class="'.$classe.'" colspan="3" align="right"> '.$$nomvala.'</td>
						</tr>
					';
						$totheuresemaine += $$nomvala ;
				break;

				#### heure/jour
				case "Moyenne par heure":
					echo '<tr>';
						$nomvala = 'totheure'.$i;
						$nomvaltot = 'totjour'.$i;
						# pour pas de division par 0
						if ($$nomvala != 0) {
							$moyenne = $$nomvaltot / $$nomvala;
						}
						#/ pour pas de division par 0
					echo'
						<td class="'.$classe.'" colspan="3" align="right"> '.fnbr($moyenne).'</td>
						</tr>
					';
						# pour pas de division par 0
						if ($$nomvala != 0) {
							$moyennetot = $totalarticle / $totheuresemaine;
						}
						#/ pour pas de division par 0
				break;
				
				#### Normal
				default: 
					echo '<tr>';
					echo'
						<td height="20" class="'.$classe.'"><input type="text" size="4" name="'.$champ.'a" value="'.fnbr($row[$champ.'a']).'"></td>
						<td height="20" class="'.$classe.'"><input type="text" size="4" name="'.$champ.'b" value="'.fnbr($row[$champ.'b']).'"></td>
						<td height="20" class="'.$classe.'"><input type="text" size="4" name="'.$champ.'c" value="'.fnbr($row[$champ.'c']).'"></td>
						</tr>
					';
						$nomvala = 'totempa'.$i;
						$nomvalb = 'totempb'.$i;
						$nomvalc = 'totempc'.$i;
						$$nomvala += $row[$champ.'a'];
						$$nomvalb += $row[$champ.'b'];
						$$nomvalc += $row[$champ.'c'];
						$eassemaine = $row['eassemaine'];

				break;
			}
}
echo '</table></td>';

#/### COLONES de PRODUITS

#### Caisses (nico 27/01/2006)
echo '<td valign="top">
<table class="'.$classe.'" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
<tr><td>Caisses</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><input type="text" size="4" name="caisse" value="'.$row['caisse'].'"> min</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
';
for ($i=1 ; $i<=36 ; $i++) {
	$nn = str_repeat('0', 2 - strlen($i)).$i; 
	if (strstr($row['badcaisses'], 'C'.$nn)) {$chk = 'checked';} else {$chk = '';}
	echo '<tr><td><input name="badcaisses[]" type="checkbox" value="C'.$nn.'" '.$chk.'> C '.$nn.'</td></tr>';
}


echo '</table></td>';
#/## Caisses






#########################	
#########################	
	
	?>
	</table>
</form>
<?php
}
#-----------------
?>
<br>
</div>
