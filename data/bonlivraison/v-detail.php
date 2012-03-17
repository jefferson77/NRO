<?php
$act = $_REQUEST['act'];
include NIVO."classes/makehtml.php";

//on récupère les infos du bon de livraison dont on veut le détail
$row = $DB->getRow("SELECT b.*, s.societe, a.prenom, a.nom FROM bonlivraison b 
	LEFT JOIN supplier s ON b.idsupplier = s.idsupplier
	LEFT JOIN agent a ON b.idagent = a.idagent
	WHERE b.id = ".$id);

//recherche de toutes les données dans la base et création de la select list 

//Requête pour les adresses des peoples en fonction du secteur
if($row['secteur']=='VI')
{
	$menu = $DB->getArray("SELECT idpeople as id, pnom as nom, pprenom as prenom, gsm as gsm,
		IF(peoplehome=1, CONCAT(adresse1, ' ', num1, '/', bte1), CONCAT(adresse2, ' ', num2, '/', bte2)) as adresse, 
		IF(peoplehome=1, cp1, cp2) as cp, 
		IF(peoplehome=1, ville1, ville2) as ville,
		'people' as lieu
		from people 
		where idpeople in (SELECT idpeople from vipmission WHERE idvipjob='".$_REQUEST['idjob']."');");
}
else if($row['secteur']=='AN')
{
	$menu= $DB->getArray("SELECT idpeople as id, pnom as nom, pprenom as prenom, gsm as gsm,
		IF(peoplehome=1, CONCAT(adresse1, ' ', num1, '/', bte1), CONCAT(adresse2, ' ', num2, '/', bte2)) as adresse, 
		IF(peoplehome=1, cp1,cp2) as cp, 
		IF(peoplehome=1, ville1,ville2) as ville,
		'people' as lieu
		from people 
		where idpeople in (SELECT idpeople from animation WHERE idanimjob='".$_REQUEST['idjob']."');");
}
else if($row['secteur']=='ME')
{
	$menu= $DB->getArray("SELECT idpeople as id, pnom as nom, pprenom as prenom, gsm as gsm, 
		IF(peoplehome=1, CONCAT(adresse1, ' ', num1, '/', bte1), CONCAT(adresse2, ' ', num2, '/', bte2)) as adresse, 
		IF(peoplehome=1, cp1,cp2) as cp, 
		IF(peoplehome=1, ville1,ville2) as ville,
		'people' as lieu
		from people 
		where idpeople in (SELECT idpeople from merch WHERE idmerch='".$_REQUEST['idjob']."');");
}
else
{
	$menu=array();
}

//on cherche l'adresse du client
if(!empty($row['secteur']))
{
	if($row['secteur']=="VI")
	{
		$menu2 = $DB->getArray("SELECT idclient as id, societe as nom, '' as prenom, adresse as adresse, cp as cp, ville as ville, 'client' as lieu
		from client
		where idclient in (SELECT idclient from vipjob WHERE idvipjob='".$_REQUEST['idjob']."');");
	}
	else if($row['secteur']=='AN')
	{
		$menu2 = $DB->getArray("SELECT idclient as id, societe as nom, '' as prenom, adresse as adresse, cp as cp, ville as ville, 'client' as lieu
		from client
		where idclient in (SELECT idclient from animjob WHERE idanimjob='".$_REQUEST['idjob']."');");
	}
	else if($row['secteur']=='ME')
	{
		$menu2 = $DB->getArray("SELECT idclient as id, societe as nom, '' as prenom, adresse as adresse, cp as cp, ville as ville, 'client' as lieu
		from client
		where idclient in (SELECT idclient from merch WHERE idmerch='".$_REQUEST['idjob']."');");
	}
}
else
{
	echo "vide";
	$menu2=array();
}


//on recherche l'adresse du lieu de travail
if(!empty($row['secteur']))
{
	if($row['secteur']=="VI")
	{
		$menu3 = $DB->getArray("SELECT idshop as id, societe as nom, '' as prenom, adresse as adresse, cp as cp, ville as ville, 'shop' as lieu
		from shop
		where idshop in (SELECT idshop from vipmission WHERE idvipjob='".$_REQUEST['idjob']."');");
	}
	else if($row['secteur']=='AN')
	{
		$menu3 = $DB->getArray("SELECT idshop as id, societe as nom, '' as prenom, adresse as adresse, cp as cp, ville as ville, 'shop' as lieu
		from shop
		where idshop in (SELECT idshop from animation WHERE idanimjob='".$_REQUEST['idjob']."');");
	}
	else if($row['secteur']=='ME')
	{
		$menu3 = $DB->getArray("SELECT idshop as id, societe as nom, '' as prenom, adresse as adresse, cp as cp, ville as ville, 'shop' as lieu
		from shop
		where idshop in (SELECT idshop from merch WHERE idmerch='".$_REQUEST['idjob']."');");
	}
}
else
{
	$menu3=array();
}

$exception = array(	"id"			=>'4350',
					"nom"			=>'Exception',
					"prenom"		=>'scrl',
					"adresse"		=>'Avenue de la chasse 195',
					"cp"			=>'1040',
					"ville"			=>'Etterbeek',
					"lieu"			=>'shop',
					"gsm" 			=>'02 732 74 40');

$autre = array(		"id"			=>'0',
					"nom"			=>'',
					"prenom"		=>'',
					"adresse"		=>'',
					"cp"			=>'',
					"ville"			=>'',
					"lieu"			=>'',
					"gsm" 			=>'');
                    
if($_REQUEST['all']==1)
{
	echo '<div id="centerzonelarge">';
}
        
?>
<div target="middle">
<FORM action="<?php echo '?act=modif&all='.$_REQUEST['all'].'&liv='.$row['type'].'&from='.$_REQUEST['from'];?>" method="post">
<input type="hidden" name="id" value="<?php echo $row['id'];?>">
<input type="hidden" name="idjob" value="<?php echo $row['idjob'];?>">
<input type="hidden" name="type" value="<?php echo $row['type'];?>">
<input type="hidden" name="ftype" id="ftype" value="<?php echo $row['ftype'] ?>">
<input type="hidden" name="ttype" id="ttype" value="<?php echo $row['ttype'] ?>">
<input type="hidden" name="fid" id="fid" value="<?php echo $row['fid'] ?>">
<input type="hidden" name="tid" id="tid" value="<?php echo $row['tid'] ?>">
<table border=1 align="center">
	<caption> Informations du bon de livraison </caption>
	
		<tr>
			<th>
				<table>
					<caption>Données d'expédition</caption>
					<tr>
						<td>
							De : 
						</td>
						<td>
							<select name="fnom" id="de">
								<option value=""></option>
							<?php
							//premier optgroup : exception
							$i=0;
							
							echo '<optgroup label="Exception">';
							echo '<option value="'.$i.'">Exception</option>';
							$total[$i] = $exception;
							$i++;
							echo "</optgroup>";
							//deuxième optgroup : Peoples
							echo '<optgroup label="People">';
							foreach($menu as $ligne)
							{
								echo '<option value="'.$i.'">'.$ligne['prenom'].' '.$ligne['nom'].'</option>';
								$total[$i]=$ligne;
								$i++;
							}
							echo "</optgroup>";
							//troisième optgroup : Client
							echo '<optgroup label="Client">';
							foreach($menu2 as $ligne)
							{
								echo '<option value="'.$i.'">'.$ligne['nom'].'</option>';
								$total[$i]=$ligne;
								$i++;
							}
							echo "</optgroup>";
							//quatrième optgroup : Lieu de travail
							echo '<optgroup label="Lieu de travail">';
							foreach($menu3 as $ligne)
							{
								echo '<option value="'.$i.'">'.$ligne['nom'].'</option>';
								$total[$i]=$ligne;
								$i++;
							}
							echo "</optgroup>";
							echo '<optgroup label="Autre">';
							echo '<option value="'.$i.'">autre adresse</option>';
							$i++;
							echo "</optgroup>";
							?>	
						</td>
					</tr>
					<tr>
						<td>
							Personne / Lieu : 
						</td>
						<td><input type="text" name="fnom" id="fnom"value="<?php echo $row['fnom'];?>"></td>
					</tr>
					<tr>
						<td>
							Adresse : 
						</td>
						<td><input type="text" name="fadr" id="fadr"value="<?php echo $row['fadr'];?>"></td>
					</tr>
    				<tr>
        				<td>Ville : </td>
        				<td><input type="text" name="fville" id ="fville" value="<?php echo $row['fville'];?>"></td>
    				</tr>
    				<tr>
        				<td>Code postal : </td>
        				<td><input type="text" name="fcp" id="fcp" value="<?php echo $row['fcp'];?>"></td>
    				</tr>
					<tr>
        				<td>GSM : </td>
        				<td><input type="text" id="fgsm" name="fgsm" value="<?php echo $row['fgsm'];?>"></td>
        
    				</tr>
    				<tr>
        				<td>Date : </td>
        				<td><input type="text" name="fdate" value="<?php echo fdate($row['fdate']);?>"></td>
    				</tr>
				</table>
			</th>

			<th>
				
				<table>
					<caption>Données de réception</caption>
					<tr>
						<td>
							De : 
						</td>
						<td>
							<select name="tnom" id="a">
								<option value=""></option>
							<?php
							//premier optgroup : exception
							$i=0;
							
							echo '<optgroup label="Exception">';
							echo '<option value="'.$i.'">Exception</option>';
							$i++;
							echo "</optgroup>";
							//deuxième optgroup : Peoples
							echo '<optgroup label="People">';
							foreach($menu as $ligne)
							{
								echo '<option value="'.$i.'">'.$ligne['prenom'].' '.$ligne['nom'].'</option>';
								$i++;
							}
							echo "</optgroup>";
							//troisième optgroup : Client
							echo '<optgroup label="Client">';
							foreach($menu2 as $ligne)
							{
								echo '<option value="'.$i.'">'.$ligne['nom'].'</option>';
								$i++;
							}
							echo "</optgroup>";
							//quatrième optgroup : Lieu de travail
							echo '<optgroup label="Lieu de travail">';
							foreach($menu3 as $ligne)
							{
								echo '<option value="'.$i.'">'.$ligne['nom'].'</option>';
								$i++;
							}
							echo "</optgroup>";
							echo '<optgroup label="Autre">';
							echo '<option value="'.$i.'">autre adresse</option>';
							$i++;
							echo "</optgroup>";
							?>
						</td>
					</tr>
					<tr>
						<td>
							Personne / Lieu : 
						</td>
						<td><input type="text" name="tnom" id="tnom"value="<?php echo $row['tnom'];?>"></td>
					</tr>
					<tr>
					<tr>
						<td>
							Adresse : 
						</td>
						<td><input type="text" id="tadr" name="tadr" value="<?php echo $row['tadr'];?>"></td>
					</tr>
    				<tr>
        				<td>Ville : </td>
        				<td><input type="text" id="tville" name="tville" value="<?php echo $row['tville'];?>"></td>
    				</tr>
    				<tr>
        				<td>Code postal : </td>
        				<td><input type="text" id="tcp" name="tcp" value="<?php echo $row['tcp'];?>"></td>
        
    				</tr>
					<tr>
        				<td>GSM : </td>
        				<td><input type="text" id="tgsm" name="tgsm" value="<?php echo $row['tgsm'];?>"></td>
        
    				</tr>
    				<tr>
        				<td>Date : </td>
        				<td><input type="text" name="tdate" value="<?php echo fdate($row['tdate']);?>"></td>
    				</tr>
				</table>
			</th>
			</tr>
			<tr>
			<th colspan="2">
				<table>
					<caption>Données générales</caption>
					<tr>
						<td>Type de livraison : </td>
						<td>
							Normale <input type="radio" name="ltype" value="normal" <?php echo ($row['ltype']=="normal")?"CHECKED":""; ?>>
							Jet <input type="radio" name="ltype" value="jet" <?php echo ($row['ltype']=="jet")?"CHECKED":""; ?>>
							Express <input type="radio" name="ltype" value="Express" <?php echo ($row['ltype']=="Express")?"CHECKED":""; ?>>
						</td>
					</tr>
					<tr>
						<td>Agent : </td>
						<td><?php echo $row['prenom'].' '.$row['nom']; ?></td>
					</tr>
				<?php if(!empty($row['idjob'])) { ?>
    				<tr>
        				<td>Mission (ID) : </td>
        				<td>
        					<?php if($_REQUEST['all']==1) {
                			echo '<input type="text" name="idjob" value="'.$row['idjob'].'">';
            			} else {
                			echo $row['idjob'];
            			}
        				?>
        			</td>
            
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
        				<td><?php echo $row['secteur']; ?></td>
    				</tr>
				<?php } ?>
    				<tr>
        				<td>Prix : </td>
        				<td><input type="text" name="prix" value="<?php echo $row['prix'];?>"></td>
    				</tr>
					<tr>
        				<td>Refacturé : </td>
        				<td><input type="text" name="factureclient" value="<?php echo $row['factureclient'];?>"></td>
    				</tr>
        				<td>Titre : </td>
        				<td><input type="text" name="titre" value="<?php echo $row['titre'];?>"></td>
    				</tr>
    				<tr>
        				<td>Description : </td>
        				<td><textarea name="detail" rows="5" cols="40"><?php echo $row['detail'];?></textarea><br></td>
    				</tr>
					</tr>
        				<td>Num Bon de commande : </td>
        				<td><?php echo $row['nbdc'];?></td>
    				</tr>
    				</table>
			</td>
				</table>
			</th>
		</tr>
		<tr>
    		<td colspan="3" align="center"><input type="submit" value="Modifier"></td>
		</tr>
</table>
    </div>
</FORM>
<?php if($_REQUEST['all']==1 ||$_REQUEST['from']=="menu") { ?>
	<div align="center"><a href="?act=listall&amp;all=1&amp;period=<?php echo date("n-Y") ?>">Retour</a></div>
<?php } else { ?>
	<div align="center"><a href="<?php echo $_SERVER['PHP_SELF'].'?act=list&secteur='.$row['secteur'].'&idjob='.$row['idjob'].'&retour=true' ?>">Retour</a></div>
<?php } ?>
<script type="text/javascript" charset="utf-8">
	function formatResult(row) {return row[0];}
	function formatItem(row) {return "("+row[0]+") "+row[1];}

/*
	TODO : afficher le nom du fournisseur dans le champ au lieu de l'ID fournisseur
*/

	$(document).ready(function(){
		// autocomplete supplier
		$("input#idsupplier").autocomplete("<?php echo Conf::read('Env.urlroot') ?>query/supplier.php", {
			inputClass: 'autocomp',
			width: 200,
			minChars: 2,
			formatItem: formatItem,
			formatResult: formatResult,
			delay: 200
		});

		$("#de").change(function() {
			var val_index
			var txtvaleurs = new Array();
		// a looper selon les valeurs php
		<?php
		$i=0;
		foreach($total as $ligne)
		{
			echo 'txtvaleurs['.$i.'] = new Array("'.$ligne['prenom'].' '.$ligne['nom'].'","'.$ligne['adresse'].'","'.$ligne['ville'].'","'.$ligne['cp'].'", "'.$ligne['lieu'].'", "'.$ligne['id'].'", "'.$ligne['gsm'].'");';
			$i++;
		}
		echo 'txtvaleurs['.$i.'] = new Array("","","", "", "0");';
		?>
			val_index = $("#de").val();
			$("#fnom").val(txtvaleurs[val_index][0]);
			$("#fadr").val(txtvaleurs[val_index][1]);
			$("#fville").val(txtvaleurs[val_index][2]);
			$("#fcp").val(txtvaleurs[val_index][3]);
			$("#ftype").val(txtvaleurs[val_index][4]);
			$("#fid").val(txtvaleurs[val_index][5]);
			$("#fgsm").val(txtvaleurs[val_index][6]);
		});
		/*
			TODO : NETTOYAGE DOUBLON INUTILE ET DEGUEULASSE
		*/
		$("#a").change(function()
		{
			var val_index
			var txtvaleurs = new Array();
		// a looper selon les valeurs php
		<?php
		$i=0;
		foreach($total as $ligne)
		{
			echo 'txtvaleurs['.$i.'] = new Array("'.$ligne['prenom'].' '.$ligne['nom'].'","'.$ligne['adresse'].'","'.$ligne['ville'].'","'.$ligne['cp'].'", "'.$ligne['lieu'].'", "'.$ligne['id'].'", "'.$ligne['gsm'].'");';
			//echo 'txtvaleurs['.$i.'] = new Array("'.$ligne['prenom'].' '.$ligne['nom'].'","'.$ligne['adresse'].'","'.$ligne['ville'].'","'.$ligne['cp'].'", "'.$ligne['lieu'].'", "'.$ligne['id'].'");';
			$i++;
		}
		echo 'txtvaleurs['.$i.'] = new Array("","","", "", "0");';
		?>
			val_index = $("#a").val();
			$("#tnom").val(txtvaleurs[val_index][0]);
			$("#tadr").val(txtvaleurs[val_index][1]);
			$("#tville").val(txtvaleurs[val_index][2]);
			$("#tcp").val(txtvaleurs[val_index][3]);
			$("#ttype").val(txtvaleurs[val_index][4]);
			$("#tid").val(txtvaleurs[val_index][5]);
			$("#tgsm").val(txtvaleurs[val_index][6]);
		});
	});
</script>

