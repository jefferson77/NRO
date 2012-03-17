<div id="centerzonelarge">
<?php
	$ideasprod = $DB->getOne("SELECT * FROM `mercheasproduit` WHERE `idshop` = '".$_GET['idshop']."' AND `weekm` = '".$_GET['weekm']."' AND `plus` = '1'");

	if (empty($ideasprod)) {
		$DB->inline("INSERT INTO mercheasproduit (weekm, idshop, plus) VALUES ('".$_GET['weekm']."', '".$_GET['idshop']."', 1)");
		$ideasprod = $DB->addid;
	}

	$datetemp = explode ('-', $_GET['datem']);
	$easannee = $datetemp[0];
	$easmois = $datetemp[1];

	$merchproduits = $DB->getArray("SELECT  
			mp.fo1a, mp.fo1b, mp.fo1c,mp.fo2a, mp.fo2b, mp.fo2c,mp.fo3a, mp.fo3b, mp.fo3c,mp.fo4a, mp.fo4b, mp.fo4c,
			mp.pa1a, mp.pa1b, mp.pa1c,mp.pa2a, mp.pa2b, mp.pa2c,mp.pa3a, mp.pa3b, mp.pa3c,mp.pa4a, mp.pa4b, mp.pa4c,
			mp.pa5a, mp.pa5b, mp.pa5c,mp.pa6a, mp.pa6b, mp.pa6c,mp.pa7a, mp.pa7b, mp.pa7c,mp.pa8a, mp.pa8b, mp.pa8c,
			mp.pa9a, mp.pa9b, mp.pa9c,mp.pa10a, mp.pa10b, mp.pa10c,
			mp.te1a, mp.te1b, mp.te1c,mp.te2a, mp.te2b, mp.te2c,mp.te3a, mp.te3b, mp.te3c,mp.te4a, mp.te4b, mp.te4c,
			mp.te5a, mp.te5b, mp.te5c,mp.te6a, mp.te6b, mp.te6c,mp.te7a, mp.te7b, mp.te7c,mp.te8a, mp.te8b, mp.te8c,
			mp.ep1a, mp.ep1b, mp.ep1c,mp.ep2a, mp.ep2b, mp.ep2c,mp.ep3a, mp.ep3b, mp.ep3c,mp.ep4a, mp.ep4b, mp.ep4c,
			mp.ep5a, mp.ep5b, mp.ep5c,
			mp.ba1a, mp.ba1b, mp.ba1c,mp.ba2a, mp.ba2b, mp.ba2c,mp.ba3a, mp.ba3b, mp.ba3c,mp.ba4a, mp.ba4b, mp.ba4c,
			mp.ba5a, mp.ba5b, mp.ba5c,mp.ba6a, mp.ba6b, mp.ba6c,
			mp.au1a, mp.au1b, mp.au1c,mp.au2a, mp.au2b, mp.au2c,mp.au3a, mp.au3b, mp.au3c,mp.au4a, mp.au4b, mp.au4c,
			mp.au5a, mp.au5b, mp.au5c,
			mp.au1n, mp.au2n, mp.au3n, mp.au4n, mp.au5n, 
			mp.caisse, mp.ideasprod, mp.weekm, mp.remarque,
			s.idshop, s.codeshop, s.societe AS ssociete, s.ville AS sville, s.eassemaine
			FROM mercheasproduit mp
				LEFT JOIN shop s ON mp.idshop = s.idshop
			WHERE `ideasprod` = '".$ideasprod."'
			ORDER BY mp.weekm");

foreach ($merchproduits as $row) {
	$zz = 2;
	$zzz++;
	if ($zzz == 1) {	
		$date = weekdate($row['weekm'], $easannee);
		$weekm = $row['weekm'];
		$jourencours = $date['lun'];
		echo "
			<fieldset>
				<legend>
					<b>Planning des Merch EAS</b>
				</legend>
				<table class='planning' border='0' width='98%' cellspacing='1' cellpadding='1' align='center'>
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
				'caisse' => 'Caisse',
				'vide2' => 'blanc',
				'modifier2' => 'modifier'

				);
#/### ARRAY pour ligne produit

?>
<form action="?act=planningweekeas3&amp;idmerch=<?php echo $_GET['idmerch'].'&weekm='.$row['weekm'].'&idshop='.$row['idshop'].'&genre='.$_GET['genre'].'&datem='.$_GET['datem'];?>" method="post">
	<input type="hidden" name="idmerch" value="<?php echo $_GET['idmerch'];?>"> 
	<input type="hidden" name="ideasprod" value="<?php echo $row['ideasprod'];?>"> 
<?php
echo '
		<table class="planning" border="1" width="75%" cellspacing="1" cellpadding="1" align="center">
			<tr>
	';
?>
			<td valign="top">
				Remarque : <br>
				<textarea name="remarque" rows="10" cols="35"><?php echo $row['remarque']; ?></textarea>
			</td>
<?php
				echo '
					<td valign="top">
						<table class="planning" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
				';
		foreach ($nomchamps as $champ => $nomch){
			switch ($nomch) {
				#### entete
				case "modifier":
					echo '<tr><td height="20" class="planning">Semaine'.$weekm.'&nbsp;</td></tr>';
				break;
				#### entete
				case "entete":
					echo '<tr><td class="planning" colspan="'.$zz.'">&nbsp;</td></tr>';
				break;

				#### titre
				case "titre":
					echo '<tr><th class="planning" colspan="'.$zz.'" align="left">'.$champ.'</th></tr>';
				break;
				#### blanc
				case "blanc":
					echo '<tr><td class="planning" colspan="'.$zz.'">&nbsp;</td></tr>';
				break;
				#### total articles
				case "Total Articles":
				break;
				
				#### Total jour
				case "Total jour":
				break;
				
				#### heure/jour
				case "heure / jour":
				break;

				#### heure/jour
				case "Moyenne par heure":
				break;
				
				#### Caisse
				case "Caisse":
					echo '<tr><td height="20" class="planning" colspan="'.$zz.'" align="left">'.$nomch.'</td></tr>';
				break;
				#### Autre
				case "Autre":
					echo '<tr><td height="20" class="planning" colspan="'.$zz.'" align="left">'.$nomch.'</td></tr>';
				break;
				#### Normal
				default: 
					echo '<tr><td height="20" class="planning" colspan="'.$zz.'" align="left">'.$nomch.'</td></tr>';
				break;
			}
}
echo '</table></td>';

#/### COLONE de TITRE



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
						<table class="planning" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
				';
			
				setlocale(LC_TIME, 'fr_FR');
				$data = explode('-',$row['datem']);
				$jaar = $data[0];
				$maand = $data[1];
				$dag = $data[2];
				$jourdhui = date("l", mktime(0, 0, 0, $maand  , $dag , $jaar));

				echo '
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
							<td colspan="2" height="20" class="planning" align="center">
					'; ?>
								<a href="<?php echo $_SERVER['PHP_SELF'].'?act=planningweekeas&idmerch='.$_GET['idmerch'].'&weekm='.$row['weekm'].'&idshop='.$row['idshop'].'&genre='.$_GET['genre'].'&datem='.$_GET['datem'];?>"><b>Retour semaine</b></a>
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
						<td class="planning">Plus</td>
						<td class="planning">Moins</td>
						</tr>
					';
				break;

				#### titre
				case "titre":
					echo '<tr><th class="planning" colspan="'.$zz.'" align="left">&nbsp;</th></tr>';
				break;
				#### blanc
				case "blanc":
					echo '<tr><td class="planning" colspan="'.$zz.'">&nbsp;</td></tr>';
				break;
				#### total articles
				case "Total Articles":
				break;
				
				#### Total jour
				case "Total jour":
				break;
				
				#### heure/jour
				case "heure / jour":
				break;

				#### heure/jour
				case "Moyenne par heure":
				break;
				
				#### Caisse
				case "Caisse":
					echo '
						<tr>
						<td height="20" class="planning"><input type="text" size="4" name="'.$champ.'" value="'.$row[$champ].'"> min</td>
						</tr>
					';
						$totcaisse += $row[$champ];
				break;

				#### Normal
				default: 
					echo '<tr>';
					echo'
						<td height="20" class="planning"><input type="text" size="4" name="'.$champ.'a" value="'.fnbr($row[$champ.'a']).'"></td>
						<td height="20" class="planning"><input type="text" size="4" name="'.$champ.'b" value="'.fnbr($row[$champ.'b']).'"></td>
						</tr>
					';
						$nomvala = 'totempa'.$i;
						$nomvalb = 'totempb'.$i;
						$$nomvala += $row[$champ.'a'];
						$$nomvalb += $row[$champ.'b'];
						$eassemaine = $row['eassemaine'];
				break;
			}
}

echo '</table></td>';
?>
	</table>
</form>
<?php } ?>
<br>
</div>