<?php
/*
	TODO : formater proprement, mois de tableaux en cascade
*/
?>
<div id="centerzonelarge">
	<div class="infosection">Recherche des people</div>
	<form name="searchpeople" action="?act=list&casting=<?php echo $_REQUEST['casting'];?>" method="post">
		<fieldset>
		<legend>Infos de recherche</legend>
		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
	        <tr>
	        	<td width="45%">
	        		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
	        			<tr>
	        				<td>Nom</td>
	        				<td><input type="text" size="20" name="pnom" value=""></td>
	        			</tr>
	        			<tr>
	        				<td>Pr&eacute;nom</td>
	        				<td><input type="text" size="20" name="pprenom" value=""> &nbsp; code (registre) <input type="text" size="5" name="codepeople" value=""></td>
	        			</tr>
	        			<tr>
	        				<td>Sexe</td>
	        				<td>
	        					<?php 
	        					echo '<input type="radio" name="sexe" value="x" '; if ($_GET['sexe'] == 'x') { echo 'checked';} echo'> X';
	        					echo '<input type="radio" name="sexe" value="f" '; if ($_GET['sexe'] == 'f') { echo 'checked';} echo'> F';
	        					echo '<input type="radio" name="sexe" value="m" '; if ($_GET['sexe'] == 'm') { echo 'checked';} echo'> M';
	        					?>
	        				</td>
	        			</tr>
	        			<tr>
	        				<td colspan="2">&nbsp;</td>
	        			</tr>
	        			<tr>
	        				<td>Langue Bureau</td>
	        				<td>
	        					<input type="radio" name="lbureau" value="FR"> FR
	        					<input type="radio" name="lbureau" value="NL"> NL
	        				</td>
	        			</tr>
	        			<tr>
	        				<td>Fr</td>
	        				<td>
        						<input type="radio" name="lfr" value="0"> 0
        						<input type="radio" name="lfr" value="1"> 1
        						<input type="radio" name="lfr" value="2"> 2
        						<input type="radio" name="lfr" value="3"> 3
        						<input type="radio" name="lfr" value="4"> 4
	        				</td>
	        			</tr>
	        			<tr>
	        				<td>NL</td>
	        				<td>
        						<input type="radio" name="lnl" value="0"> 0
        						<input type="radio" name="lnl" value="1"> 1
        						<input type="radio" name="lnl" value="2"> 2
        						<input type="radio" name="lnl" value="3"> 3
        						<input type="radio" name="lnl" value="4"> 4
	        				</td>
	        			</tr>
	        			<tr>
	        				<td>En</td>
							<td>
        						<input type="radio" name="len" value="0"> 0
        						<input type="radio" name="len" value="1"> 1
        						<input type="radio" name="len" value="2"> 2
        						<input type="radio" name="len" value="3"> 3
        						<input type="radio" name="len" value="4"> 4
	        				</td>
	        			</tr>
	        			<tr>
	        				<td colspan="2">&nbsp;</td>
	        			</tr>
	        			<tr>
	        				<td>Du</td>
	        				<td>
        						<input type="radio" name="ldu" value="0"> 0
        						<input type="radio" name="ldu" value="1"> 1
        						<input type="radio" name="ldu" value="2"> 2
        						<input type="radio" name="ldu" value="3"> 3
        						<input type="radio" name="ldu" value="4"> 4
	        				</td>
	        			</tr>
	        			<tr>
	        				<td>It</td>
	        				<td>
        						<input type="radio" name="lit" value="0"> 0
        						<input type="radio" name="lit" value="1"> 1
        						<input type="radio" name="lit" value="2"> 2
        						<input type="radio" name="lit" value="3"> 3
        						<input type="radio" name="lit" value="4"> 4
	        				</td>
	        			</tr>
	        			<tr>
	        				<td>Sp</td>
	        				<td>
        						<input type="radio" name="lsp" value="0"> 0
        						<input type="radio" name="lsp" value="1"> 1
        						<input type="radio" name="lsp" value="2"> 2
        						<input type="radio" name="lsp" value="3"> 3
        						<input type="radio" name="lsp" value="4"> 4
	        				</td>
	        			</tr>
	        			<tr>
	        				<td colspan="2">&nbsp;</td>
	        			</tr>
	        		</table>
	        	</td>
	        	<td>&nbsp;</td>
	        	<td width="45%">
	        		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
	        			<tr>
	        				<td>CP</td>
	        				<td>de : <input type="text" size="10" name="cp1a" value="" onkeyup="greyfield(document.searchpeople,this,'cp1b');"> à <input type="text" size="10" name="cp1b" id="cp1b" value="" disabled="disabled"></td>
	        			</tr>
	        			<tr>
	        				<td>Ville</td>
	        				<td><input type="text" size="20" name="ville1" value=""></td>
	        			</tr>
	        			<tr>
	        				<td colspan="2">&nbsp;</td>
	        			</tr>
	        			<tr>
	        				<td>GSM</td>
	        				<td><input type="text" size="20" name="gsm" value=""></td>
	        			</tr>
	        			<tr>
	        				<td>Tel Fixe</td>
	        				<td><input type="text" size="20" name="tel" value=""></td>
	        			</tr>
	        			<tr>
	        				<td>Secteur</td>
	        				<td>
	        					<input type="checkbox" name="categorie[0]" value="1"> Anim &nbsp; 
	        					<input type="checkbox" name="categorie[1]" value="1"> Merch &nbsp; 
	        					<input type="checkbox" name="categorie[2]" value="1"> Hotes &nbsp; 
	        				</td>
	        			</tr>
	        			<tr>
	        				<td colspan="2">&nbsp;</td>
	        			</tr>
	        			<tr>
	        				<td>Vipactivite</td>
	        				<td><input type="text" size="20" name="vipactivite" value=""></td>
	        			</tr>
	        			<tr>
	        				<td>Note</td>
	        				<td><input type="text" size="20" name="notegenerale" value=""></td>
	        			</tr>
	        			<tr>
	        				<td>Note Merch materiel</td>
	        				<td><input type="checkbox" name="notemerch" value="yes"> Non vide </td>
	        			</tr>
	        			<tr>
	        				<td>Out ?</td>
	        				<td>
	        					<input type="radio" checked name="isout" value="notout"> not out &nbsp; 
	        					<input type="radio" name="isout" value="out"> out &nbsp; 
	        					<input type="radio" name="isout" value=""> tous &nbsp; 
	        				</td>
	        			</tr>
	        		</table>
	        	</td>
	        </tr>
		</table>
		<br>
		<table class="standard" border="1" cellspacing="0" cellpadding="0" align="center" width="95%">
	        <tr>
	        	<td width="40%">
	        		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
	        			<tr>
	        				<td>N&deg; r national</td>
	        				<td><input type="text" size="20" name="nrnational" value=""></td>
	        			</tr>
	        			<tr>
	        				<td>Permis</td>
	        				<td>
	        					<input type="radio" name="permis" value="non"> Non
	        					<input type="radio" name="permis" value="A"> A
	        					<input type="radio" name="permis" value="B"> B
	        					<input type="radio" name="permis" value="C"> C
	        					<input type="radio" name="permis" value="D"> D
	        					<input type="radio" name="permis" value="E"> E
	        				</td>
	        			</tr>
	        			<tr>
	        				<td>Voiture</td>
	        				<td>
	        					<input type="radio" name="voiture" value="oui"> Oui
	        					<input type="radio" name="voiture" value="non"> Non
	        				</td>
	        			</tr>
	        			<tr>
	        				<td>Age</td>
	        				<td><input type="text" size="20" name="age" value=""></td>
	        			</tr>
	        			<tr>
	        				<td>Physionomie</td>
	        				<td>
		<?php 
		$physios = array(
		'occidental' => 'Occidental',
		'slave' => 'Slave',
		'asiatique' => 'Asiatique',
		'orientale' => 'Orientale',
		'black' => 'Black',
		'nordafricain' => 'Nord-africain',
		'hispanique' => 'Hispanique',
		'mediterraneen' => 'M&eacute;dit&eacute;rran&eacute;en'
		);

		echo '<select name="physio"><option value=""></option>';

		foreach ($physios as $key => $value) {
		echo '<option value="'.$key.'">'.$value.'</option>';
		}

		echo '</select>';
		?>
	        				</td>
	        			</tr>
	        			<tr>
	        				<td>Email</td>
	        				<td><input type="text" size="30" name="email" value=""></td>
	        			</tr>
	        			<tr>
	        				<td colspan="2">&nbsp;</td>
	        			</tr>
	        		</table>
	        	</td>
	        	<td width="30%">
	        		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
	        			<tr>
	        				<td>Be</td>
	        				<td>
        						<input type="radio" name="beaute" value="1"> 1
        						<input type="radio" name="beaute" value="2"> 2
        						<input type="radio" name="beaute" value="3"> 3
        						<input type="radio" name="beaute" value="4"> 4
	        				</td>
	        			</tr>
	        			<tr>
	        				<td>Ch</td>
	        				<td>
        						<input type="radio" name="charme" value="1"> 1
        						<input type="radio" name="charme" value="2"> 2
        						<input type="radio" name="charme" value="3"> 3
        						<input type="radio" name="charme" value="4"> 4
	        				</td>
	        			</tr>
	        			<tr>
	        				<td>Dy</td>
	        				<td>
        						<input type="radio" name="dynamisme" value="1"> 1
        						<input type="radio" name="dynamisme" value="2"> 2
        						<input type="radio" name="dynamisme" value="3"> 3
        						<input type="radio" name="dynamisme" value="4"> 4
	        				</td>
	        			</tr>
	        		</table>
	        	</td>
	        	<td>
	        		<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="95%">
	        			<tr>
	        				<td>Couleur Cheveux</td>
	        				<td>
	        					<select name="ccheveux" size="1">
	        						<option value=""></option>
	        						<option value="blond">blond</option>
	        						<option value="brun">brun</option>
	        						<option value="noir">noir</option>
	        						<option value="chatain">chatain</option>
	        						<option value="roux">roux</option>
	        					</select>
	        				</td>
	        			</tr>
	        			<tr>
	        				<td>Longueur cheveux</td>
	        				<td>
	        					<select name="lcheveux" size="1">
	        						<option value=""></option>
	        						<option value="long">long</option>
	        						<option value="mi-long">mi-long</option>
	        						<option value="court">court</option>
	        						<option value="rase">ras&eacute;</option>
	        					</select>
	        				</td>
	        			</tr>
	        			<tr>
	        				<td>Taille</td>
	        				<td><input type="text" size="3" name="taille" value=""> cm ( utilisez ... pour un intervale = ex : '160...170'</td>
	        			</tr>
	        			<tr>
	        				<td>Taille Veste</td>
	        				<td>
	        					<select name="tveste" size="1">
	        						<option value=""></option>
	        						<option value="34">34</option>
	        						<option value="36">36</option>
	        						<option value="38">38</option>
	        						<option value="40">40</option>
	        						<option value="42">42</option>
	        						<option value="44">44</option>
	        						<option value="50">50</option>
	        						<option value="52">52</option>
	        						<option value="54">54</option>
	        						<option value="56">56</option>
	        						<option value="L">L</option>
	        						<option value="XL">XL</option>
	        					</select>
	        				</td>
	        			</tr>
	        			<tr>
	        				<td>Taille Jupe</td>
	        				<td>
	        					<select name="tjupe" size="1">
	        						<option value=""></option>
	        						<option value="34">34</option>
	        						<option value="36">36</option>
	        						<option value="38">38</option>
	        						<option value="40">40</option>
	        						<option value="42">42</option>
	        						<option value="44">44</option>
	        						<option value="50">50</option>
	        						<option value="52">52</option>
	        						<option value="54">54</option>
	        						<option value="56">56</option>
	        					</select>
	        				</td>
	        			</tr>
	        			<tr>
	        				<td>ID</td>
	        				<td><input type="text" size="5" name="idpeople" value=""></td>
	        			</tr>
	        		</table>
	        	</td>
	        </tr>
		</table>
		</fieldset>


	<fieldset>
	<legend>Payements - Dimona / Groupe-S</legend>
	<table class="standard" border="0" cellspacing="1" cellpadding="1" align="center" width="95%">
		<tr>
	        <td>
	        	Erreurs dans les informations : &nbsp 
	        	<?php 
	        	echo '<input type="radio" name="err" value="Y"> Oui';
	        	echo '<input type="radio" name="err" value="N"> Non';
	        	?>
	        </td>
		</tr>
	</table>
	</fieldset>
	<br>
	<table class="standard" border="0" cellspacing="1" cellpadding="0" align="center" width="93%">
		<tr>
	        <td align="left" width="50%">
	        	<input type="reset" name="reset" value="Reset">
	        </td>
	        <td>
	        	<input type="submit" name="Rechercher" value="Rechercher">
	        </td>
		</tr>
	</table>
	</form>
</div>

<script type="text/javascript" charset="utf-8">
	function greyfield(formulaire, initfield, targetfield)
	{
		if (formulaire.initfield = '')
		document.getElementById(targetfield).disabled = true;
		else
		document.getElementById(targetfield).disabled = false;
	}
</script>
