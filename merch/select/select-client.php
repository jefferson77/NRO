<div id="centerzonelarge">
<?php
$classe = 'standard' ;
### Première étape : formulaire de recherche de Clients

$_SESSION['clientquid'] = '';
$_SESSION['clientquod'] = '';
$_SESSION['clientsort'] = '';
?>
<h1 align="center">Recherche des Clients</h1>
<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&idmerch='.$idmerch.'&sel=client&etape=listeclient';?>" method="post">
	<input type="hidden" name="etape" value="listeclient"> 
	<table class="standard" border="0" cellspacing="2" cellpadding="2" align="center" width="50%">
		<tr>
			<td colspan="2" align="center">&nbsp;
				
			</td>
		</tr>
		<tr>
			<td>
				Code client
			</td>
			<td>
				<input type="text" size="20" name="codeclient" value="">
			</td>
		</tr>

		<tr>
			<td>
				Soci&eacute;t&eacute;
			</td>
			<td>
				<input type="text" size="20" name="societe" value="">
			</td>
		</tr>
		<tr>
			<td>
				CP
			</td>
			<td>
				<input type="text" size="20" name="cp" value="">
			</td>
		</tr>
		<tr>
			<td>
				Ville
			</td>
			<td>
				<input type="text" size="20" name="ville" value="">
			</td>
		</tr>
		<tr>
			<td>
				Etat
			</td>
			<td>
				<input type="radio" name="etat" value="5" checked> In &nbsp; <input type="radio" name="etat" value="0"> Out  &nbsp; <input type="radio" name="etat" value=""> Tous
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<br><br>
				<input type="submit" name="Rechercher" value="Rechercher">
				<br><br>
			</td>
		</tr>
	</table>
</form>
<br><br>
<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="40%">
	<tr bgcolor="#FF6699">
		<td class="<?php echo $classe; ?>" align="center"> 
				<form action="<?php echo $_SERVER['PHP_SELF'].'?act=modif&mod=client';?>" method="post">
					<input type="hidden" name="idmerch" value="<?php echo $idmerch ;?>"> 
					<input type="hidden" name="idclient" value=""> 
					<input type="hidden" name="idcofficer" value=""> 
			<input type="submit" name="Selectionner" value="Remove CLIENT from merch"> 
			</form>
		</td>
	</tr>
</table>
</div>