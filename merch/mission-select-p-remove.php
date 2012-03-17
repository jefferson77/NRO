<?php #  Sélection des éléments ?>
<?php 
if (($_GET['act'] == 'people') OR ($_GET['sel'] == 'lieu2')) { echo '<div id="minicentervipwhite">';}
else { echo '<div id="centerzonelargewhite">';}
?>
<?php
$classe = 'etiq2' ;

				### RECHERCHE Info de base de Mission
					$sql = "SELECT
					me.idmerch, me.datem, me.weekm, me.genre, 
					me.hin1, me.hout1, me.hin2, me.hout2, 
					me.produit, 
					s.societe, s.adresse, s.cp AS shopcp, s.ville AS shopville,
					p.idpeople, p.pnom, p.pprenom
					FROM merch me 
					LEFT JOIN shop s ON me.idshop = s.idshop 
					LEFT JOIN people p ON me.idpeople = p.idpeople 
					WHERE me.idmerch = ".$idmerch;
					
					$detailmission = new db();
					$detailmission->inline($sql);
					$infosmission = mysql_fetch_array($detailmission->result) ;
				#/## RECHERCHE Info de base de Mission
?>
		<fieldset class="blue">
			<legend class="blue">Etat actuel</legend>
			<table class="<?php echo $classe; ?>" border="0" width="100%" cellspacing="1" cellpadding="1" align="center">
				<tr>
					<td class="<?php echo $classe; ?>">
						<?php echo "(".$infosmission['produit'].") &nbsp; ".fdate($infosmission['datem'])." &nbsp; &nbsp;".ftime($infosmission['hin1'])."-".ftime($infosmission['hout1']); ?>
					</td>
					<td class="<?php echo $classe; ?>">
						People : <?php echo $infosmission['pprenom']." ".$infosmission['pnom']." - ".$infosmission['idpeople']; ?>
					</td>
					<td class="<?php echo $classe; ?>">
						Lieu : <?php echo $infosmission['societe']." ".$infosmission['adresse']." ".$infosmission['shopcp']." ".$infosmission['shopville']; ?>
					</td>
				</tr>
			</table>
		</fieldset>
		<br>
		<fieldset class="blue">
		<legend class="blue">Suppresion du people ?</legend>
		<form action="<?php echo $_SERVER['PHP_SELF'].'?act=removeselect&idmerch='.$idmerch.'&sel=people';?>" method="post">
			<input type="hidden" name="idmerch" value="<?php echo $idmerch ;?>"> 
			<input type="hidden" name="idpeople" value="<?php echo $infosmission['idpeople']; ?>"> 

			<table class="<?php echo $classe; ?>" border="0" cellspacing="3" cellpadding="3" align="center" width="100%">
				<tr>
					<td width="75">
						&nbsp;
					</td>
					<td class="<?php echo $classe; ?>" align="center" width="75">
						Motif ? 
					</td>
					<td>
						<input type="radio" name="motif" value="1" checked> D&eacute;sistement <br>
						<input type="radio" name="motif" value="2"> Absent <br>
						<input type="radio" name="motif" value="3"> Re-booking <br>
						<input type="radio" name="motif" value="4"> Annulation Exception <br>
						<input type="radio" name="motif" value="5"> De-booking Exception <br>
						<input type="radio" name="motif" value="6"> Autre <br>
					</td>
					<td width="75">
						&nbsp;
					</td>
					<td class="<?php echo $classe; ?>" align="center" width="75">
						Notes
					</td>
					<td>
						<textarea name="note" rows="4" cols="53"></textarea>
					</td>
					<td width="75">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td colspan="6">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td>
						&nbsp;
					</td>
					<td class="<?php echo $classe; ?>" align="center" colspan="5">
						<input type="submit" name="Selectionner" value="Remove People from Mission"> 
					</td>
				</tr>
			</table>
		</form>
	</fieldset>
</div>