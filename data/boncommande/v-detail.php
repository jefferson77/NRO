<?php
$act = $_REQUEST['act'];
include NIVO."classes/makehtml.php";

//on récupère les infos du bon de commande dont on veut le détail
$row = $DB->getRow("SELECT b.*, s.societe, a.prenom, a.nom FROM bondecommande b 
	LEFT JOIN supplier s ON b.idsupplier = s.idsupplier
	LEFT JOIN agent a ON b.idagent = a.idagent
	WHERE b.id = ".$id);

if($_REQUEST['all'] == 1) echo '<div id="centerzonelarge">';
        
?>
<div target="middle">
<FORM action="?act=modif&amp;idjob=<?php echo $idjob.'&secteur='.$_REQUEST['secteur'].'&all='.$_REQUEST['all'].'&liv='.$row['type'].'&from='.$_REQUEST['from'];?>" method="post">
<input type="hidden" name="id" value="<?php echo $row['id'];?>">
<table border="1" align="center">
	<caption> Informations du bon de commande </caption>
		<tr>
			<td>
				<table>
					<caption>Données pour le fournisseur</caption>
					<tr>
						<td>Agent : </td>
						<td><?php echo $row['prenom'].' '.$row['nom']; ?></td>
					</tr>
				<?php if(!empty($row['idjob'])) { ?>
    				<tr>
        				<td>Job (ID) : </td>
        				<td><?php echo $row['idjob']; ?></td>
    				</tr>
				<?php } ?>
    				<tr>
        				<td>Fournisseur (ID) : </td>
        				<td>
							<input type="text" name="idsupplier" value="<?php echo $row['idsupplier'];?>" id="idsupplier" size="4" title="Entrez le début d'un nom de supplier" style="text-align:center;">
        					<?php echo $row['societe'];?>
						</td>
    				</tr>
					<?php 
				// Si on ne vient pas du menu, ou que secteur n'est pas vide (empty=false), il faut afficher sinon, non
				if(!empty($row['secteur'])) { ?>
    				<tr>
        				<td>Secteur : </td>
        				<td>
        					<?php echo $row['secteur']; ?>
        				</td>
    				</tr>
				<?php } ?>
    				<tr>
        				<td>Date : </td>
        				<td><input type="text" name="date" value="<?php echo fdate($row['date']);?>"></td>
    				</tr>
    				<tr>
        				<td>Montant facturé : </td>
        				<td><input type="text" name="montant" value="<?php echo $row['montant'];?>"></td>
    				</tr>
        				<td>Titre : </td>
        				<td><input type="text" name="titre" value="<?php echo $row['titre'];?>"></td>
    				</tr>
    				<tr>
        				<td>Description : </td>
        				<td><textarea name="description" rows="5" cols="40"><?php echo $row['description'];?></textarea><br></td>
    				</tr>
    				<?php if($_REQUEST['liv']=="liv"||$row['type']=="liv") { ?>
    					<tr>
        					<td>Date de livraison : </td>
        					<td><input type="text" name="livdate" value="<?php echo $row['livdate'];?>"></td>
    					</tr>
    					<tr>
        					<td>Heure de livraison : </td>
        					<td><input type="text" name="livheure" value="<?php echo $row['livheure'];?>"></td>
    					</tr>
    					<tr>
        					<td>Adresse de livraison : </td>
        					<td><input type="text" name="livaddr" value="<?php echo $row['livaddr'];?>"></td>
    					</tr>
    					<tr>
        					<td>Code postal de livraison : </td>
        					<td><input type="text" name="livcp" value="<?php echo $row['livcp'];?>"></td>
    					</tr>
    					<tr>
        					<td>Localité de livraison : </td>
        					<td><input type="text" name="livlocalite" value="<?php echo $row['livlocalite'];?>"></td>
    					</tr>
    				<?php } ?>
				</table>
			</td>

			<td VALING="top">
				<table>
					<caption>Données pour le client</caption>
					<?php if(empty($row['secteur'])) { ?>
						<tr>
		        			<td>Numéro de facture : </td>
		        			<td><input type="text" name="nfac" value="<?php echo $row['nfac'];?>"></td>
		    			</tr>
					<?php } if(!empty($row['secteur'])) { ?>
		    			<tr>
		        			<td>Montant facturé au client : </td>
		        			<td><input type="text" name="factureclient" value="<?php echo $row['factureclient'];?>"></td>
		    			</tr>
		    			<tr>
					<?php } if(!empty($row['secteur'])) { ?>
						<tr>
		        			<td>Numéro de facture de la commande : </td>
		        			<td><input type="text" name="nfac" value="<?php echo $row['nfac'];?>"></td>
		    			</tr>
						<tr>
		        			<td>Numéro de facture pour le client : </td>
		        			<td><input type="text" name="nfacout" value="<?php echo $row['nfacout'];?>"></td>
		    			</tr>
					<?php } ?>
				</table>
			</td>
		</tr>
		<tr>
    		<td colspan="3" align="center"><input type="submit" value="Modifier"></td>
		</tr>
</table>
    </div>
</FORM>
<?php
if($_REQUEST['all']==1 ||$_REQUEST['from']=="menu") { ?>
	<div align="center"><a href="?act=listall&amp;all=1&amp;period=<?php echo date("n-Y") ?>">Retour</a></div>
<?php } else { ?>
	<div align="center"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=list&secteur='.$row['secteur'].'&idjob='.$row['idjob'].'&retour=true' ?>">Retour</a></div>
<?php } ?>
<script type="text/javascript" charset="utf-8">
	function formatResult(row) {return row[0];}
	function formatItem(row) {return "("+row[0]+") "+row[1];}
	$(document).ready(function() {
		$("input#idsupplier").autocomplete("<?php echo Conf::read('Env.urlroot') ?>query/supplier.php", 
		{
			inputClass: 'autocomp',
			width: 200,
			minChars: 2,
			formatItem: formatItem,
			formatResult: formatResult,
			delay: 200
		});
	});
</script>
