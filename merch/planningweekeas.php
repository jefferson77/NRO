<div id="centerzonelarge">
<?php $classe = "planning" ;

# VARIABLE SELECT
		# idshop
			if (!empty($quid)) #
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

		# datem
		list($yyyy, $mm, $dd) = explode('-',$_GET['datem']);
		$datemavant = date('Y-m-d', mktime(0,0,0,$mm,$dd-7,$yyyy));
		$datemapres = date('Y-m-d', mktime(0,0,0,$mm,$dd+7,$yyyy));

			$quid .= " AND me.datem BETWEEN '".$datemavant."' AND '".$datemapres."'";
		#/ weekm1

#/ VARIABLE SELECT

		$recherche='
			SELECT 
			me.idmerch, me.datem, me.weekm, me.genre, 
			me.hin1, me.hout1, me.hin2, me.hout2, 
			me.kmpaye, me.kmfacture,
			me.produit, me.facturation, 
			me.ferie, me.contratencode, me.rapportencode, me.easremplac,
			
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
			mp.caisse,
			
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
			WHERE '.$quid.'
			ORDER BY me.datem, me.hin1 DESC';

#############
############# pour easmois
			$datetemp = explode ('-', $_GET['datem']);
			$easannee = $datetemp[0];
			$easmois = $datetemp[1];
			
	$detail = new db();
	$detail->inline("SELECT * FROM `mercheasmois` WHERE `easmois` = $easmois AND `easannee` = $easannee");
	$infos = mysql_fetch_array($detail->result) ; 
#/############ pour easmois
############# pour + / - 
		$quidplus = "`idshop` = '".$_GET['idshop']."' AND `weekm` = '".$_GET['weekm']."' AND `plus` = '1'";
		$rechercheplus1="SELECT * FROM `mercheasproduit` WHERE ";
		$rechercheplus='
			'.$rechercheplus1.'
			'.$quidplus.'
		';

$detailplus = new db();
$detailplus->inline($rechercheplus);
$infosplus = mysql_fetch_array($detailplus->result) ; 

$listing = new db();
$listing->inline($recherche);
$FoundCount = mysql_num_rows($listing->result); 

mysql_data_seek($listing->result, 0);
while ($row = mysql_fetch_array($listing->result)) { 
	if ($row['easremplac'] == '1') {
		$people1 .= $row['codepeople']." - ".$row['idpeople']." - ".$row['pnom']." ".$row['pprenom']." / ";
	} else {
		$people0 = "c: ".$row['codepeople']." - id: ".$row['idpeople']." - ".$row['pnom']." ".$row['pprenom'];
	}
}
if ($people1 != '') { $people1 = '<font color="#CC0033">('.$people1.')</font>'; $people0 = $people0.' '.$people1;}
mysql_data_seek($listing->result, 0);
while ($row = mysql_fetch_array($listing->result)) { 
	# $plusetmoins pour passer les $ vers la page plus et moins 
	$plusetmoins = 'idmerch='.$row['idmerch'].'&weekm='.$row['weekm'].'&idshop='.$row['idshop'].'&genre='.$row['genre'].'&datem='.$row['datem'];
	$zz = 3;
	$zzz++;
	if ($zzz == 1) {	
		$date = weekdate($row['weekm'], $easannee);
		$weekm = $row['weekm'];
		$jourencours = $date['lun'];
		echo "
			<fieldset>
				<legend>
					<b>Planning des Merch EAS -</b>
				</legend>
				<table class='".$classe."' border='0' width='98%' cellspacing='1' cellpadding='1' align='center'>
					<tr>
						<td width='50%'>		
							semaine : ".$row['weekm'].'-'.$easannee." ( ".fdate($date['lun'])." au ".fdate($date['ven'])." )<br>
							people : ".$people0."
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
}
#/ ------- FIN Info GENERAL haut de page --------




# ------- DEBUT LISTING GENERAL --------
$colspa = 4;

echo '<table class="'.$classe.'" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">';

#### ARRAY pour ligne produit
$nomchamps = array(
				'link1' => 'link',
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
				'te7' => 'Puericult',
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
				'au1' => 'Autre 1',
				'au2' => 'Autre 2',
				'au3' => 'Autre 3',
				'au4' => 'Autre 4',
				'au5' => 'Autre 5',

				'Totaux' => 'titre',
				'tottemp' => 'Total Articles',
				'totjour' => 'Total jour',
				'heurejour' => 'heure / jour',
				'moyennejour' => 'Moyenne par heure',
				'caisse' => 'Caisse',
				'link2' => 'link'

				);
#/### ARRAY pour ligne produit

echo '
		</table>
		<table class="'.$classe.'" border="1" width="98%" cellspacing="1" cellpadding="1" align="center">
			<tr>
	';

#### COLONE de TITRE

				echo '
					<td valign="top">
						<table class="'.$classe.'" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
				';
				echo '
					<tr><td class="'.$classe.'" align="center">Semaine<br>'.$weekm.'<br>&nbsp;</td></tr>
					<tr><td class="'.$classe.'">&nbsp;</td></tr>
					<tr><td class="'.$classe.'">&nbsp;</td></tr>
					<tr><td class="'.$classe.'">&nbsp;</td></tr>
				';
		foreach ($nomchamps as $champ => $nomch){
			switch ($nomch) {
				#### Link
				case "link":
					echo '<tr><td class="'.$classe.'" colspan="'.$zz.'">&nbsp;</td></tr>';
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
				
				#### Caisse
				case "Caisse":
					echo '<tr><th class="'.$classe.'" colspan="'.$zz.'" align="left">'.$nomch.'</th></tr>';
				break;

				#### Normal
				default: 
					echo '<tr><td height="20" class="'.$classe.'" colspan="'.$zz.'" align="left">'.$nomch.'</td></tr>';
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

		#$datetemp = implode('',explode("-",$jourencours));
		list($yyyy, $mm, $dd) = explode('-',$jourencours);
		$datetemp = date('Ymd', mktime(0,0,0,$mm,$dd,$yyyy));

			mysql_data_seek($listing->result, 0);
			while ($row = mysql_fetch_array($listing->result)) { 
						$i++;
						if ($datetempz == $row['datem']) {
							$jj--;
							$jourprint = 'jour'.$jj; 
							$jourprintx = 'jourx'.$jj; 
							# $datetemp++;
							list($yyyy, $mm, $dd) = explode('-',$date[$$jourprint]);
							$datetemp = date('Ymd', mktime(0,0,0,$mm,$dd,$yyyy));
							echo $datetemp;
						}
						### pour jour en cours
					
							$jourprint = 'jour'.$jj; 
							$jourprintx = 'jourx'.$jj; 
							list($yyyy, $mm, $dd) = explode('-',$row['datem']);
							$datemtemp = date('Ymd', mktime(0,0,0,$mm,$dd,$yyyy));
							#$datemtemp = implode('',explode("-",$row['datem']));
							
							### jours vides avant
								while (($datetemp < $datemtemp) and ($jj < 6)) {
										echo '
											<td valign="top" width="100">
												<table class="'.$classe.'" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
													<tr><td class="'.$classe.'" colspan="3" align="center">'
													.$$jourprintx.'<br>'
													.fdate($date[$$jourprint])
													.'&nbsp;<br>&nbsp;</td></tr>
													<tr><td class="'.$classe.'">&nbsp;</td><td class="'.$classe.'" colspan="2">&nbsp;</td></tr>
													<tr><td class="'.$classe.'">&nbsp;</td><td class="'.$classe.'" colspan="2">&nbsp;</td></tr>
													<tr><td class="'.$classe.'">&nbsp;</td><td class="'.$classe.'" colspan="2">&nbsp;</td></tr>
													<tr><td class="'.$classe.'">&nbsp;</td><td class="'.$classe.'" colspan="2">&nbsp;</td></tr>
												</table>
											</td>
										';
										$date2 = $date[$$jourprint]; 
										$jj++;
										$jourprint = 'jour'.$jj; 
										$jourprintx = 'jourx'.$jj; 
										# $datetemp++;
										list($yyyy, $mm, $dd) = explode('-',$date[$$jourprint]);
										$datetemp = date('Ymd', mktime(0,0,0,$mm,$dd,$yyyy));
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
									
									if ($row['easremplac'] == '1') {$font1 = '<font color="#CC0033">'; $font2 = '</font>'; } else {$font1 = ' '; $font2 = ' '; }
									echo '
										<tr><td class="'.$classe.'" colspan="3" align="center">'.$font1.''.$$jourprintx.'<br>'.fdate($row['datem']).'<br>job '.$row['idmerch'].''.$font2.'
										</td></tr>
										<tr><td class="'.$classe.'">Am</td><td class="'.$classe.'" colspan="2">'.ftime($row['hin1']).' - '.ftime($row['hout1']).'</td></tr>
										<tr><td class="'.$classe.'">Pm</td><td class="'.$classe.'" colspan="2">'.ftime($row['hin2']).' - '.ftime($row['hout2']).'</td></tr>
										<tr>
											<td class="'.$classe.'">KM</td>
											<td class="'.$classe.'">'.fnbr($row['kmpaye']).'</td>
											<td class="'.$classe.'">';
											if ($row['contratencode'] == '1') { echo '<font color="green">cV</font>';} else { echo '<font color="red">X</font>';}
											echo ' &nbsp; ';
											if ($row['rapportencode'] == '1') { echo '<font color="green">rV</font>';} else { echo '<font color="red">X</font>';}
											echo '</td>
										</tr>
									';
										$date2 = $date[$$jourprint];
										$jj++;
										$datetempz = $row['datem'];
										$jourprint = 'jour'.$jj; 
										$jourprintx = 'jourx'.$jj; 
										list($yyyy, $mm, $dd) = explode('-',$date[$$jourprint]);
										$datetemp = date('Ymd', mktime(0,0,0,$mm,$dd,$yyyy));
					
							foreach ($nomchamps as $champ => $nomch){
								switch ($nomch) {
									#### Link
									case "link":
									echo '
										<tr>
											<td colspan="3" class="'.$classe.'" align="center">
									'; ?>
												<a href="<?php echo $_SERVER['PHP_SELF'].'?act=planningweekeas2&idmerch='.$row['idmerch'].'&weekm='.$row['weekm'].'&idshop='.$row['idshop'].'&genre='.$row['genre'].'&datem='.$row['datem'];?>"><b>Edition</b></a>
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
									
									#### Caisse
									case "Caisse":
										echo '
											<tr>
											<td class="'.$classe.'" colspan="3" align="right">'.$row[$champ].'</td>
											</tr>
										';
											$totcaisse += $row[$champ];
									break;
					
									#### Normal
									default: 
										echo'
										<tr>
											<td height="20" class="'.$classe.'">'.$row[$champ.'a'].'</td>
											<td height="20" class="'.$classe.'">'.$row[$champ.'b'].'</td>
											<td height="20" class="'.$classe.'">'.$row[$champ.'c'].'</td>
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
}
#/### COLONES de PRODUITS
			### jours vides apres
				while (($datetemp > $datemtemp) and ($jj < 6)) {
					echo '
						<td valign="top" width="100">
							<table class="'.$classe.'" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
								<tr><td class="'.$classe.'" colspan="3" align="center">'.$$jourprintx.'<br>&nbsp;<br>&nbsp;</td></tr>
								<tr><td class="'.$classe.'">Am</td><td class="'.$classe.'" colspan="2">&nbsp;</td></tr>
								<tr><td class="'.$classe.'">Pm</td><td class="'.$classe.'" colspan="2">&nbsp;</td></tr>
								<tr><td class="'.$classe.'" colspan="3">&nbsp;</td></tr>
								<tr><td class="'.$classe.'" colspan="3">&nbsp;</td></tr>
							</table>
						</td>
					';
					$date2 = $date[$$jourprint]; 
					$jj++;
					$jourprint = 'jour'.$jj; 
					$jourprintx = 'jourx'.$jj; 
					list($yyyy, $mm, $dd) = explode('-',$date[$$jourprint]);
					$datetemp = date('Ymd', mktime(0,0,0,$mm,$dd,$yyyy));
					# $datetemp++;
					} 
			#/## jours vides apres

#########################	
#########################	
	
	?>
	</table>
<br>
</div>
