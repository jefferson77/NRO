<script type="text/javascript" charset="utf-8">
	function ounCheck(status) {
		if (status == 0) uncheckAll(document.getElementsByName("print[]"));
		if (status == 1) checkAll(document.getElementsByName("print[]"));
	}

	function checkAll(field) {
		for (i = 0; i < field.length; i++) field[i].checked = true ;
	}

	function uncheckAll(field) {
		for (i = 0; i < field.length; i++) field[i].checked = false ;
	}
</script>
<div id="centerzonelarge">
<?php

$listing = new db();

if ($_POST['Modifier'] == 'Rechercher') {
	$searchfields = array(
			'a.idagent' => 'idagent',
			'c.idclient' => 'idclient',
			'c.societe' => 'csociete',
			'me.boncommande' => 'boncommande',
			'me.contratencode' => 'contratencode',
			'me.genre' => 'genre',
			'me.idmerch' => 'idmerch',
			'me.produit' => 'produit',
			'me.reference' => 'reference',
			'me.weekm' => 'weekm',
			'p.codepeople' => 'codepeople',
			'p.pnom' => 'pnom',
			'p.pprenom' => 'pprenom',
			's.codeshop' => 'codeshop',
			's.societe' => 'ssociete',
			's.ville' => 'sville',
			'YEAR(me.datem)' => 'yearm');

	$_SESSION['planquid'] = $listing->MAKEsearch($searchfields);

	if (!empty($_POST['todo'])) {
		$_SESSION['planquid'] .= ((!empty($_SESSION['planquid']))?' AND ':'')." (me.idpeople IS NULL OR me.idpeople = '')";
	}

	if ($_POST['arch'] != 'yes') { $_SESSION['planquid'] .= " AND me.facturation = 1"; }
}

$listing->inline("SELECT
		me.idmerch, me.idpeople, me.idshop, me.idclient,
		me.datem, YEAR(me.datem) as yearm, me.weekm, me.genre,
		p.lbureau, p.codepeople, p.pnom, p.pprenom,
		s.societe AS ssociete, s.ville AS sville, s.codeshop,
		c.societe AS clsociete
	FROM merch me
		LEFT JOIN agent a ON me.idagent = a.idagent
		LEFT JOIN client c ON me.idclient = c.idclient
		LEFT JOIN people p ON me.idpeople = p.idpeople
		LEFT JOIN shop s ON me.idshop = s.idshop
	WHERE ".$_SESSION['planquid']."
	ORDER BY YEAR(me.datem), me.weekm, me.idshop, p.idpeople ,me.datem");

$FoundCount = mysql_num_rows($listing->result);

if (($_POST['genre'] == 'EAS') or (strstr($_SESSION['planquid'], '%EAS%'))) $flagEAS = 'OUI'; # Flag si on est en Eas pour les rapports
?>
<fieldset>
	<legend>
		<b>Planning des Merch</b>
	</legend>
	<b>Votre Recherche : ( <?php echo $FoundCount; ?> ) <?php echo $_SESSION['planquod']; ?></b><br>
</fieldset>
<br>
<?php
# ------- DEBUT LISTING GENERAL --------
$colspa = 4;?>
<form name="leform" action="<?php echo NIVO ?>print/merch/printmerch.php?src=planning" method="post" target="popup" onsubmit="OpenBrWindow('about:blank','popup','scrollbars=yes,status=yes,resizable=yes','500','400','true')" >
	<table class="planning" border="0" width="98%" cellspacing="1" cellpadding="1" align="center">
		<tr>
			<?php if ($flagEAS == 'OUI') { $colspa = 2; ?>
				<th class="planning" colspan="<?php echo $colspa; ?>" align="right">
					<a href="<?php echo $_SERVER['PHP_SELF'].'?act=planning&action=skip&sort=me.yearm, me.weekm, me.idshop, p.idpeople';?>">Listing Classique</a>
					 &nbsp; / &nbsp;
					<a href="<?php echo $_SERVER['PHP_SELF'].'?act=planning&action=skip&sort=p.pnom, p.pprenom, p.idpeople, me.idshop, me.yearm, me.weekm';?>">Listing print</a>
				</th>
			<?php } ?>
			<th class="planning" colspan="<?php echo $colspa; ?>" align="right">
				<input type="checkbox" name="ptype[]" value="contrat" checked> Contrats </font>
				<input type="checkbox" name="ptype[]" value="semaine"><font color="#CCC"> Semaines </font>
				<?php if ($flagEAS == 'OUI') {?>
					<input type="checkbox" name="ptype[]" value="rapporteas"><font color="#CCC">Rapports EAS</font>
				<?php } ?>
				&nbsp;&nbsp;
			</th>
			<th class="planning"><input type="submit" class="btn printer"></th>
			<th class="planning"><input type="submit" class="btn phone" name="send" value="sms"></th>
			</th>
		</tr>
		<tr>
			<th class="planning">ID</a></th>
			<th class="planning">Date</a></th>
			<th class="planning">Client</a></th>
			<th class="planning">Lieux</a></th>
			<th class="planning"><input type="checkbox" onclick="ounCheck(this.checked ?'1':'0')" checked title="Check/UnCheck ALL"></th>
			<th class="planning"></th>
		</tr>
	<?php

	$zweekm = 'z';
	$zyearm = 'z';
	$zidpeople = 'z';
	$zidshop = 'z';
	while ($row = mysql_fetch_array($listing->result)) {

        $annee = substr($row['datem'], 0, 4);
        ## ligne de semaine ##
        if ($row['yearm'] != $zyearm) { $titre = 'go'; }
        if ($row['weekm'] != $zweekm) { $titre = 'go'; }
        if ($row['idpeople'] != $zidpeople) { $titre = 'go'; }
        if (($row['genre'] == 'Rack assistance') and ($row['idshop'] != $zidshop))	{ $titre = 'go'; }
        if (($row['genre'] == 'EAS') and ($row['idshop'] != $zidshop))	{ $titre = 'go'; }

        if ($titre == 'go') { ?>
        <tr>
        <td bgcolor="white" colspan="2">
            semaine : <?php echo $row['weekm']; $date = weekdate($row['weekm'], $row['yearm']); echo ' ( '.fdate($date['lun']).' au '.fdate($date['dim']).' )'; ?>
        </td>
        <td bgcolor="white">
            people : <img src="<?php echo STATIK ?>illus/<?php echo $row['lbureau']; ?>.gif" alt="<?php echo $row['lbureau']; ?>" width="12" height="9"> <?php echo $row['codepeople'].' - '.$row['idpeople'].' - '.$row['pnom'].' '.$row['pprenom'] ?>
        </td>
        <td bgcolor="white">
            <?php if ($row['genre'] == 'Rack assistance') { echo $row['idshop']; ?> - <?php echo $row['ssociete']; ?> - <?php echo $row['sville']; } ?>
        </td>
        <th class="planning" width="15">
            <?php if (!empty($row['idpeople'])){
				?><input type="checkbox" name="print[]" checked value="<?php echo $row['datem'].'/'.$row['idpeople'].'/'.$row['genre'].'/'.$row['idshop']; ?>"><?php
            } ?>
        </th>
        <th class="planning" width="13">
            <?php if (!empty($row['idpeople'])){
                    if ($row['genre'] == 'Rack assistance') {$act = 'planningweek';}
                    if ($row['genre'] == 'Store Check') {$act = 'planningweek';}
                    if ($row['genre'] == 'EAS') {$act = 'planningweekeas';}
            ?>
                <a href="<?php echo $_SERVER['PHP_SELF'].'?act='.$act.'&idpeople='.$row['idpeople'].'&weekm='.$row['weekm'].'&idshop='.$row['idshop'].'&genre='.$row['genre'].'&datem='.$row['datem'];?>"><img src="<?php echo STATIK ?>illus/w-select.gif" alt="search" width="13" height="13" border="0"></a>
            <?php } else { ?>
                X
            <?php } ?>
        </th>

        <?php
        $titre = 'roger';
        }
        #/ ## ligne de semaine ##

	        $i++;
			echo '<tr bgcolor="'.((fmod($i, 2) == 1)?'#9CBECA':'#8CAAB5').'">';
        ?>
                        <td class="planning"><?php echo $row['idmerch'] ?></td>
                        <td class="planning"><?php echo fdate($row['datem']) ?></td>
                        <td class="planning"><?php echo $row['idclient']; ?> - <?php echo $row['clsociete']; ?></td>
                        <td class="planning"><?php echo $row['codeshop']; ?> - <?php echo $row['ssociete']; ?> - <?php echo $row['sville']; ?></td>
                        <th class="planning" width="15"></th>
                        <td class="planning" width="13"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=show&act2=planning&idmerch='.$row['idmerch'];?>"><img src="<?php echo STATIK ?>illus/afficher.gif" alt="search" width="13" height="15" border="0"></a></td>
                </tr>
        <?php
        $zyearm = $row['yearm'];
        $zweekm = $row['weekm'];
        $zidpeople = $row['idpeople'];
        $zidshop = $row['idshop'];

	} ?>
	</table>
</form>

<?php
#-----------------
?>
<br>
</div>
