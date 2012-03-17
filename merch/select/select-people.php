<div id="centerzonelarge">
<?php
$idmerch = $_GET['idmerch'];

$classe = 'standard' ;

### Première étape : formulaire de recherche de People
$_SESSION['quid'] = '';
$_SESSION['quod'] = '';
$_SESSION['sort'] = '';
$_SESSION['skip'] = 0;
?>


<div class="infosection">Recherche des people</div>
	<form action="<?php echo $_SERVER['PHP_SELF'].'?act=select&etape=listepeople&idmerch='.$idmerch.'&sel=people';?>" method="post">
	<input type="hidden" name="etape" value="listepeople"> 
	<fieldset>
	<legend>
		Infos de recherche
	</legend>
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
		<tr>
			<td width="45%" align="left" valign="top"><table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
					<tr>
						<td>
							Nom
						</td>
						<td>
							<input type="text" size="20" name="pnom" value="">
						</td>
					    <td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							Pr&eacute;nom
						</td>
						<td>
							<input type="text" size="20" name="pprenom" value="">&nbsp;						</td>
					    <td>code (registre)
                        <input type="text" size="5" name="codepeople" value=""></td>
					</tr>
					<tr>
						<td>
							Sexe
						</td>
						<td colspan="2">
							<?php 
							echo '<input type="radio" name="sexe" value="x" '; if ($_GET['sexe'] == 'x') { echo 'checked';} echo'> X';
							echo '<input type="radio" name="sexe" value="f" '; if ($_GET['sexe'] == 'f') { echo 'checked';} echo'> F';
							echo '<input type="radio" name="sexe" value="m" '; if ($_GET['sexe'] == 'm') { echo 'checked';} echo'> M';
							?>
						</td>
					</tr>
					<tr>
						<td>
							Sous-traitance :
						</td>
						<td>
							<input type="radio" name="indep" value="n" checked> Internes
							<input type="radio" name="indep" value="y"> Sous-trait&eacute;
							<input type="radio" name="indep" value="t"> Tous
						</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;
							
						</td>
					</tr>
					<tr>
						<td>
							Langue Bureau
						</td>
						<td colspan="2">
							<input type="radio" name="lbureau" value="FR"> FR
							<input type="radio" name="lbureau" value="NL"> NL
						</td>
					</tr>
					<tr>
						<td>
							Fr
						</td>
						<td colspan="2">
								<input type="radio" name="lfr" value="0"> 0
								<input type="radio" name="lfr" value="1"> 1
								<input type="radio" name="lfr" value="2"> 2
								<input type="radio" name="lfr" value="3"> 3
								<input type="radio" name="lfr" value="4"> 4
						</td>
					</tr>
					<tr>
						<td>
							NL
						</td>
						<td colspan="2">
								<input type="radio" name="lnl" value="0"> 0
								<input type="radio" name="lnl" value="1"> 1
								<input type="radio" name="lnl" value="2"> 2
								<input type="radio" name="lnl" value="3"> 3
								<input type="radio" name="lnl" value="4"> 4
						</td>
					</tr>
					<tr>
						<td>
							En
						</td>
						<td colspan="2">
								<input type="radio" name="len" value="0"> 0
								<input type="radio" name="len" value="1"> 1
								<input type="radio" name="len" value="2"> 2
								<input type="radio" name="len" value="3"> 3
								<input type="radio" name="len" value="4"> 4
						</td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td colspan="2">&nbsp;</td>
			  </tr>
					<tr>
					  <td>Permis</td>
					  <td colspan="2"><input type="radio" name="permis" value="non">
Non
  <input type="radio" name="permis" value="A">
A
<input type="radio" name="permis" value="B">
B
<input type="radio" name="permis" value="C">
C
<input type="radio" name="permis" value="D">
D
<input type="radio" name="permis" value="E">
E</td>
			  </tr>
					<tr>
					  <td>Voiture</td>
					  <td colspan="2"><input type="radio" name="voiture" value="oui">
Oui
  <input type="radio" name="voiture" value="non">
Non </td>
			  </tr>
					<tr>
					  <td>Email</td>
					  <td colspan="2"><input type="text" size="30" name="email" value=""></td>
			  </tr>
					<tr>
					  <td>GSM</td>
					  <td colspan="2"><input type="text" size="20" name="gsm" value=""></td>
			  </tr>
					<tr>
					  <td>ID</td>
					  <td colspan="2"><input type="text" size="5" name="idpeople" value=""></td>
			  </tr>
				</table>
			</td>
			<td width="45%" align="left" valign="top">
				<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
					<tr>
						<td>
							CP
						</td>
						<td>
							<input type="text" size="10" name="cp1a" value=""> à <input type="text" size="10" name="cp1b" value="">
						</td>
					</tr>
					<tr>
						<td>
							Ville
						</td>
						<td>
							<input type="text" size="20" name="ville1" value="">
						</td>
					</tr>
					<tr>
					  <td>Rayon (km) </td>
				      <td><input name="rayon" type="text" id="rayon" size="5"></td>
				  </tr>
					<tr>
					  <td>Secteur</td>
				      <td><input type="checkbox" name="categorie[2]" value="1">
			          VI 
			            <input type="checkbox" name="categorie[0]" value="1">
		              AN 
		              <input type="checkbox" name="categorie[1]" value="1" checked>
		              ME </td>
				  </tr>
					<tr>
						<td colspan="2">&nbsp;
							
						</td>
					</tr>
					<tr>
						<td>
							Note
						</td>
						<td>
							<input type="text" size="20" name="notegenerale" value="">
						</td>
					</tr>
					<tr>
						<td>
							Note Merch materiel
						</td>
						<td>
							<input type="checkbox" name="notemerch" value="yes"> Non vide 
						</td>
					</tr>
					<tr>
						<td>
							Out ?
						</td>
						<td>
							<input type="radio" checked name="out" value="notout"> not out &nbsp; 
							<input type="radio" name="out" value="out"> out &nbsp; 
							<input type="radio" name="out" value=""> tous &nbsp; 
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
		  <td><input type="reset" name="reset" value="Reset"></td>
		  <td><input type="submit" name="Rechercher" value="Rechercher"></td>
	  </tr>
	</table>
	</fieldset>
</form>
</div>
